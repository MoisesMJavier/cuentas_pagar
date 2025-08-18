<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/* Regla de negocio para la gestion de Contabilidad 
 * de acuerdo alas Facturas emitidas por el solicitante */

class M_Contabilidad extends CI_Model {

    function __construct() {
        parent::__construct();
    }
    
    function obtenerSol() {        
        switch( $this->session->userdata("inicio_sesion")['rol'] ){
            case 'CC':
                return $this->db->query("SELECT s.idsolicitud AS ID, s.metoPago, f.foliofac AS folio, s.moneda, s.justificacion AS Concepto, p.nombre AS Proveedor, s.cantidad AS Cantidad, DATE_FORMAT(s.fecelab, '%d/%m/%Y') AS Fecha, s.nomdepto AS Departamento, s.idEmpresa, s.idetapa AS IDETAPA, emp.abrev, IFNULL(notifi.visto, 1) AS visto 
                FROM solpagos s JOIN proveedores p ON s.idProveedor = p.idProveedor 
                JOIN empresas emp ON emp.idempresa = s.idEmpresa 
                /*JOIN ( SELECT * FROM facturas WHERE facturas.tipo_factura = 1 ) f ON f.idsolicitud = s.idsolicitud*/ 
                JOIN facturas f ON (f.idsolicitud = s.idsolicitud and (f.tipo_factura=1 or (s.metoPago='INTERCAMBIO' and f.tipo_factura=3)))
                LEFT JOIN ( SELECT notificaciones.idsolicitud, notificaciones.visto FROM notificaciones WHERE notificaciones.idusuario = '".$this->session->userdata("inicio_sesion")['id']."' GROUP BY notificaciones.idsolicitud, notificaciones.idusuario ) AS notifi ON notifi.idsolicitud = s.idsolicitud 
                LEFT JOIN ( SELECT idsolicitud FROM polizas_provision WHERE estatus = 1 GROUP BY idsolicitud ) pp ON pp.idsolicitud = s.idsolicitud 
                WHERE ( s.idetapa IN( 7, 20 ) OR ( metoPago LIKE 'FACT%' AND s.idetapa NOT IN ( 9, 10, 11 ) ) ) AND (s.caja_chica IS NULL OR s.caja_chica != 1 ) AND pp.idsolicitud IS NULL");    
                break;
            default:
                return $this->db->query("SELECT s.idsolicitud AS ID, s.metoPago, f.foliofac AS folio, s.moneda, s.justificacion AS Concepto, p.nombre AS Proveedor, s.cantidad AS Cantidad, DATE_FORMAT(s.fecelab, '%d/%m/%Y') AS Fecha, s.nomdepto AS Departamento, s.idEmpresa, s.idetapa AS IDETAPA, emp.abrev, IFNULL(notifi.visto, 1) AS visto 
                FROM ( 
                    SELECT * FROM solpagos 
                    WHERE FIND_IN_SET( idEmpresa, '".$this->session->userdata("inicio_sesion")['depto']."' ) AND ( idetapa IN( 7, 20 ) OR ( metoPago LIKE 'FACT%' AND idetapa NOT IN ( 9, 10, 11 ) ) ) AND (caja_chica IS NULL OR caja_chica != 1 )
                ) s 
                JOIN empresas emp ON emp.idempresa = s.idEmpresa 
                JOIN proveedores p ON s.idProveedor = p.idProveedor 
                JOIN facturas f ON (f.idsolicitud = s.idsolicitud and (f.tipo_factura=1 or (s.metoPago='INTERCAMBIO' and f.tipo_factura=3)))
                LEFT JOIN ( SELECT notificaciones.idsolicitud, notificaciones.visto FROM notificaciones WHERE notificaciones.idusuario = '".$this->session->userdata("inicio_sesion")['id']."' GROUP BY notificaciones.idsolicitud, notificaciones.idusuario ) AS notifi ON notifi.idsolicitud = s.idsolicitud 
                LEFT JOIN ( SELECT idsolicitud FROM polizas_provision WHERE estatus = 1 GROUP BY idsolicitud ) pp ON pp.idsolicitud = s.idsolicitud 
                WHERE pp.idsolicitud IS NULL");
                break;
        }
    }
    
    function obtenerSolP(){
        $results = array();        
        
        $this->db->select('s.idsolicitud AS ID, s.folio, s.moneda, s.justificacion AS Concepto , p.nombre AS Proveedor , '
                    . 's.cantidad AS Cantidad , DATE_FORMAT(s.fecelab,"%d/%m/%Y") AS Fecha , s.nomdepto AS Departamento, '
                    . 'aut.idpago AS IDPAGO, aut.cantidad  AS pago,'
                    . 's.idEmpresa, emp.abrev,IFNULL(notifi.visto, 1) AS visto');
            $this->db->from('autpagos aut');
            $this->db->join('solpagos s', 's.idsolicitud = aut.idsolicitud');
            $this->db->join('proveedores p', 's.idProveedor = p.idProveedor');
            $this->db->join('etapas e', 'e.idetapa = s.idetapa'); 
            $this->db->join('empresas emp', 'emp.idempresa = s.idEmpresa');
            $this->db->join("( SELECT notificaciones.idsolicitud, notificaciones.visto FROM notificaciones "
                    . "WHERE notificaciones.idusuario = '".$this->session->userdata("inicio_sesion")['id']."' ) AS notifi "," notifi.idsolicitud = s.idsolicitud","left");
            $this->db->where("aut.idpago NOT IN (SELECT idpago FROM polizas_pago) AND aut.fecha_pago IS NOT NULL AND s.idsolicitud IN(SELECT idsolicitud FROM polizas_provision) AND FIND_IN_SET(s.idEmpresa, '".$this->session->userdata("inicio_sesion")['depto']."')");
            $query = $this->db->get();            

         if ($query->num_rows() > 0) {
            $results = $query->result();
         }
         return $results;

    }
    
    function revisarNombrePoliza($nombrepoliza){
        return $this->db->query("SELECT * FROM polizas_provision WHERE numpoliza like binary '".$nombrepoliza."' ");        
    }
                
    function gdapoliza( $data ){
        // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
        $this->db->insert("polizas_provision", $data);
        return $this->db->query("UPDATE solpagos SET solpagos.idetapa = CASE WHEN metoPago='INTERCAMBIO' AND solpagos.idetapa = 20 THEN 12 WHEN solpagos.idetapa = 20 THEN 7 ELSE solpagos.idetapa END WHERE idsolicitud = '".$data['idsolicitud']."'");
    }
    
    function UpdatePoliza($data){
        $this->db->UPDATE( "polizas_provision", array( "numpoliza" => $data['numpoliza'], "rutaArchivo" => $data['rutaArchivo'], "idusuario" => $this->session->userdata('inicio_sesion')['id'] ), "idprovision = '".$data['idprovision']."'");
        return $this->db->affected_rows();
    }

    function gdapolizaP($data){
        $this->db->query("INSERT INTO `polizas_pago`"
                . "(`numpoliza`,`idsolicitud`,`idusuario`,`idpago`)"
            . "VALUES('".$data['numpoliza']."','".$data['idsolicitud']."','".$this->session->userdata('inicio_sesion')['id']."','".$data['idpago']."')");
        return $this->db->affected_rows();
    }
    
    function estatusFacPoliza(){
        $this->db->query("SELECT * FROM solpagos s WHERE s.idsolicitud = s.idsolicitud");
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $results = $query->result();
        }
        return $results;
    }
    
    function declProvision($id){
        // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
        if($this->db->query("SELECT * FROM autpagos WHERE idsolicitud = '$id'")->num_rows() > 0){
            return $this->db->update("facturas", array("tipo_factura" => 0), "idsolicitud = '$id'");
        }
        else{
            return $this->db->update("solpagos", array( "idetapa" => 21 ), "idsolicitud = $id" );
        }
        
    }
    
    
    function facturasPendientes(){
       return $this->db->query("SELECT solpagos.idProveedor,proveedores.nombre,solpagos.folio,solpagos.idsolicitud AS ID, solpagos.justificacion AS justificacion,solpagos.cantidad 
        AS Cantidad ,DATE_FORMAT(solpagos.fecelab,'%d/%m/%Y') AS Fecha ,solpagos.moneda,solpagos.nomdepto AS Departamento,empresas.abrev FROM solpagos 
        INNER JOIN empresas ON empresas.idempresa = solpagos.idempresa INNER JOIN proveedores ON proveedores.idProveedor = solpagos.idProveedor
        WHERE solpagos.nomdepto = '".$this->session->userdata("inicio_sesion")['depto']."' AND solpagos.idsolicitud NOT IN ( SELECT facturas.idsolicitud FROM facturas  WHERE facturas.tipo_factura = 3 )
        AND proveedores.idproveedor NOT IN ( SELECT solpagos.idProveedor FROM solpagos WHERE solpagos.nomdepto = '".$this->session->userdata("inicio_sesion")['depto']."' AND solpagos.idsolicitud 
        IN( SELECT autpagos.idsolicitud FROM autpagos HAVING SUM( IF( autpagos.idfactura IS NULL, 1 , 0) ) > 1 ) ) ");
    }
    
    function HistorialProvisiones($post) {
        $where = " YEAR(fecreg) = ".date("Y");

        if($post){
            $fechaInicio = $post['fechaInicio'] 
                ? DateTime::createFromFormat('d/m/Y', $post['fechaInicio'])
                : date("Y").'-01-01'.' 00:00:00';
            if ($fechaInicio instanceof DateTime) {
                $fechaInicio = $fechaInicio->format('Y-m-d').' 00:00:00';
            }

            $fechaFin = $post['fechaFin'] 
                ? DateTime::createFromFormat('d/m/Y', $post['fechaFin'])
                : date("Y-m-d").' 23:59:59';
            if ($fechaFin instanceof DateTime) {
                $fechaFin = $fechaFin->format('Y-m-d').' 23:59:59';
            }

            
             $where = "fecreg BETWEEN '". $fechaInicio."' AND '". $fechaFin."'";
        }
    
        switch( $this->session->userdata("inicio_sesion")['rol'] ){
            case 'CC':
                return $this->db->query("SELECT
                polizas_provision.idprovision,
                SUBSTRING(facturas.uuid, - 5, 5) AS folio_fiscal,
                proveedores.nom_proveedor,
                polizas_provision.idsolicitud,
                DATE_FORMAT(polizas_provision.fecreg, '%d/%m/%Y') AS fecreg,
                polizas_provision.numpoliza,
                usuarios.nombre_completo Responsable,
                empresas.abrev,
                polizas_provision.estatus,
                autpagos.resultado
                
            FROM
                ( SELECT 
                    MIN(polizas_provision.idprovision) idprovision, 
                    MIN( polizas_provision.fecreg ) fecreg, 
                    polizas_provision.numpoliza, 
                    polizas_provision.estatus, 
                    polizas_provision.idsolicitud,
                    polizas_provision.idusuario
                    FROM polizas_provision 
                    GROUP BY polizas_provision.idsolicitud 
                    HAVING MIN( polizas_provision.idprovision ) 
                ) polizas_provision
                LEFT JOIN
                ( SELECT MAX(autpagos.idpago) AS resultado, autpagos.idsolicitud FROM autpagos GROUP BY autpagos.idsolicitud ) AS autpagos ON autpagos.idsolicitud = polizas_provision.idsolicitud
                CROSS JOIN
                listado_usuarios usuarios ON polizas_provision.idusuario = usuarios.idusuario
                INNER JOIN
                ( SELECT 
                    solpagos.idsolicitud, empresas.abrev, solpagos.idproveedor
                FROM
                    solpagos
                CROSS JOIN empresas ON solpagos.idEmpresa = empresas.idempresa) AS empresas ON polizas_provision.idsolicitud = empresas.idsolicitud
                INNER JOIN ( SELECT facturas.idsolicitud, facturas.uuid FROM facturas WHERE facturas.tipo_factura = 1 ) facturas ON facturas.idsolicitud = empresas.idsolicitud
                CROSS JOIN( SELECT proveedores.idproveedor, proveedores.nombre AS nom_proveedor FROM proveedores ) proveedores ON proveedores.idproveedor = empresas.idProveedor
                WHERE $where")->result_array();
                break;
            default:
                return $this->db->query("SELECT
                    polizas_provision.idprovision,
                    SUBSTRING(facturas.uuid, - 5, 5) AS folio_fiscal,
                    proveedores.nom_proveedor,
                    polizas_provision.idsolicitud,
                    DATE_FORMAT(polizas_provision.fecreg, '%d/%m/%Y') AS fecreg,
                    polizas_provision.numpoliza,
                    usuarios.nombre_completo Responsable,
                    empresas.abrev,
                    polizas_provision.estatus,
                    autpagos.resultado
                    
                FROM
                    ( SELECT 
                        MIN(polizas_provision.idprovision) idprovision, 
                        MIN( polizas_provision.fecreg ) fecreg, 
                        polizas_provision.numpoliza, 
                        polizas_provision.estatus, 
                        polizas_provision.idsolicitud,
                        polizas_provision.idusuario
                        FROM polizas_provision 
                        GROUP BY polizas_provision.idsolicitud 
                        HAVING MIN( polizas_provision.idprovision ) 
                    ) polizas_provision
                    LEFT JOIN
                    ( SELECT MAX(autpagos.idpago) AS resultado, autpagos.idsolicitud FROM autpagos GROUP BY autpagos.idsolicitud ) AS autpagos ON autpagos.idsolicitud = polizas_provision.idsolicitud
                    CROSS JOIN
                    listado_usuarios usuarios ON polizas_provision.idusuario = usuarios.idusuario
                    INNER JOIN
                    ( SELECT 
                        solpagos.idsolicitud, solpagos.idEmpresa, empresas.abrev, solpagos.idproveedor
                    FROM
                        solpagos
                    CROSS JOIN empresas ON solpagos.idEmpresa = empresas.idempresa) AS empresas ON polizas_provision.idsolicitud = empresas.idsolicitud
                    INNER JOIN ( SELECT facturas.idsolicitud, facturas.uuid FROM facturas WHERE facturas.tipo_factura = 1 ) facturas ON facturas.idsolicitud = empresas.idsolicitud
                    CROSS JOIN( SELECT proveedores.idproveedor, proveedores.nombre AS nom_proveedor FROM proveedores ) proveedores ON proveedores.idproveedor = empresas.idProveedor
                WHERE FIND_IN_SET( empresas.idEmpresa, '".$this->session->userdata("inicio_sesion")['depto']."')
                AND $where")->result_array();
                break;
        }
    }

    public function EliminarProvison($id){
        return $this->db->query("DELETE FROM polizas_provision WHERE idprovision = $id;");
    }

    public function CancelarProvison($id){
        return $this->db->query("UPDATE polizas_provision SET estatus = 0 WHERE idprovision = $id;");
    }

    public function GetProvison($id){
        $results = array();
        $this->db->select("numpoliza, rutaArchivo");
        $this->db->from('polizas_provision');
        $this->db->where("idprovision LIKE $id");
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $results = $query->result();
        }

        return $results;
    }
    
    function get_solicitudes_autorizadas_area(){
       return $this->db->query("SELECT solpagos.nomdepto, solpagos.folio, solpagos.proyecto, solpagos.idsolicitud,  solpagos.justificacion, CONCAT(usuarios.nombres,'\n',if(usuarios.apellidos IS NOT NULL,usuarios.apellidos,'')) AS auto, DATE_FORMAT(solpagos.fechaCreacion, '%d/%m/%Y') AS fecha_autorizacion,  proveedores.nombre, solpagos.cantidad, empresas.abrev  FROM  solpagos INNER JOIN proveedores on solpagos.idProveedor = proveedores.idproveedor INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa LEFT JOIN usuarios ON usuarios.idusuario = solpagos.idusuario WHERE solpagos.idetapa IN( 53 )");
    } 


    function ver_datos_historial(){
       return $this->db->query("SELECT solpagos.idetapa, solpagos.nomdepto, solpagos.folio, solpagos.proyecto, solpagos.idsolicitud,  solpagos.justificacion,  CONCAT(usuarios.nombres,'\n',usuarios.apellidos) AS auto, DATE_FORMAT(solpagos.fechaCreacion, '%d/%m/%Y') AS fecha_autorizacion,  proveedores.nombre, solpagos.cantidad, empresas.abrev,et.nombre as etapa  FROM  solpagos INNER JOIN proveedores on solpagos.idProveedor = proveedores.idproveedor INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa LEFT JOIN usuarios ON usuarios.idusuario = solpagos.idusuario join etapas et on et.idetapa=solpagos.idetapa WHERE solpagos.idetapa IN( 3,51,50,7 ) AND solpagos.idAutoriza = '".$this->session->userdata("inicio_sesion")['id']."'");
    }
}

