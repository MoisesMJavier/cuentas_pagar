<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/* Regla de negocio para la gestion de Contabilidad 
 * de acuerdo alas Facturas emitidas por el solicitante */

class M_opciones extends CI_Model {

    function __construct() {
        parent::__construct();
    }
    
      
    function get_solicitudes_autorizadas_area(){
       return $this->db->query("SELECT solpagos.nomdepto, solpagos.folio, solpagos.proyecto, solpagos.idsolicitud,  solpagos.justificacion,  CONCAT(usuarios.nombres,'\n',usuarios.apellidos) AS auto, DATE_FORMAT(solpagos.fechaCreacion, '%d/%m/%Y') AS fecha_autorizacion,  proveedores.nombre, solpagos.cantidad, empresas.abrev  FROM  solpagos INNER JOIN proveedores on solpagos.idProveedor = proveedores.idproveedor INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa LEFT JOIN usuarios ON usuarios.idusuario = solpagos.idusuario WHERE solpagos.idetapa IN( 50 ) AND nomdepto like '%DEVOLUCION%'");
    } 


    function ver_datos_historial(){
       return $this->db->query("SELECT solpagos.idetapa, solpagos.nomdepto, solpagos.folio, solpagos.proyecto, solpagos.idsolicitud,  solpagos.justificacion,  CONCAT(usuarios.nombres,'\n',usuarios.apellidos) AS auto, DATE_FORMAT(solpagos.fechaCreacion, '%d/%m/%Y') AS fecha_autorizacion,  proveedores.nombre, solpagos.cantidad, empresas.abrev  FROM  solpagos INNER JOIN proveedores on solpagos.idProveedor = proveedores.idproveedor INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa LEFT JOIN usuarios ON usuarios.idusuario = solpagos.idusuario WHERE solpagos.idetapa IN( 3, 51, 7 ) AND nomdepto like '%DEVOLUCION%' ORDER BY solpagos.idetapa DESC");
    } 

    function aceptar_devolucion( $idsol ){
        // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
        $this->db->query("UPDATE solpagos SET idetapa = 51 WHERE idsolicitud = $idsol AND idetapa = 50");
        return $this->db->affected_rows(); 
    }

    function aceptar_devolucion_contabilidad( $idsol ){
        // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
        $this->db->query("UPDATE solpagos SET idetapa = 3 WHERE idsolicitud = $idsol AND idetapa = 51");
        return $this->db->affected_rows(); 
    }
     
    function regresar_devolucion( $idsol ){
        // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
        $this->db->query("UPDATE solpagos SET idetapa = 4 WHERE idsolicitud = $idsol AND idetapa = 50");
        return $this->db->affected_rows();
    }

    function doc_soporte($idsolicitud, $ruta)
    {
        
        return $this->db->insert(
            "documentos_solicitud",
            array(
                "idsolicitud" => $idsolicitud,
                "iddocumento" => 65,
                "ruta" => $ruta,
                "estatus" => 1,
                "idcrea" => $this->session->userdata("inicio_sesion")['id'],
                "fecha_crea" => date("Y-m-d h:i:s")
            )
        );
    }
    function get_doc($idSol){
        return $this->db->query("SELECT * FROM documentos_solicitud dsol
        inner join (Select * from documentos WHERE ndocumento = 'SOPORTE') docs on docs.iddocumentos = dsol.iddocumento
        WHERE estatus=1  AND idsolicitud='$idSol'");
    }
    function borrarDocumento($idSol, $idDoc){
        return $this->db->query("UPDATE documentos_solicitud SET estatus = 0 WHERE idsolicitud = $idSol AND iddocumentos_solicitud =$idDoc ");
    }
}

