<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Impuesto extends CI_Model {

    function __construct(){
        parent::__construct();
    }

    function obtenerSolPendientesImpuestos(){
        return $this->db->query("SELECT 
            IFNULL(notifi.visto, 1) AS visto, 
            solpagos.folio, 
            solpagos.fechaCreacion AS fecha_creacion, 
            solpagos.proyecto, 
            solpagos.idsolicitud AS ID, 
            capturista.nombre_capturista, 
            solpagos.moneda, 
            solpagos.justificacion AS Observacion, 
            proveedores.nombre AS Proveedor, 
            empresas.abrev, 
            solpagos.fecelab AS FECHAFACP, 
            solpagos.cantidad AS Cantidad 
            FROM solpagos 
            INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa 
            INNER JOIN (SELECT usuarios.idusuario, CONCAT( usuarios.nombres,' ', usuarios.apellidos ) AS nombre_capturista FROM usuarios) AS capturista ON capturista.idusuario = solpagos.idResponsable 
            INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor 
            LEFT JOIN ( SELECT notificaciones.idsolicitud, notificaciones.visto FROM notificaciones WHERE notificaciones.idusuario = '".$this->session->userdata("inicio_sesion")['id']."' ) AS notifi ON notifi.idsolicitud = solpagos.idsolicitud 
            WHERE solpagos.idetapa IN ( 7, 9 ) AND (solpagos.caja_chica IS NULL OR solpagos.caja_chica = 0) AND solpagos.nomdepto IN ( 'IMPUESTOS' ) 
            ORDER BY solpagos.prioridad DESC, solpagos.fecelab");
    }

    function solicitudes_impuestos(){
        return $this->db->query("SELECT 
            solpagos.folio, 
            solpagos.prioridad, 
            solpagos.nomdepto, 
            solpagos.prioridad, 
            solpagos.idAutoriza, 
            ifnull(solpagos.proyecto, pd.nombre) as proyecto,   /**   Modificación a la consulta  para traer el nombre del proyecto | FECHA: 05-Junio-2024 | @author Lalo Silva <programador.analista38@ciudadmaderas.com>  **/
            solpagos.idsolicitud, 
            solpagos.justificacion, 
            proveedores.nombre, 
            solpagos.cantidad, 
            DATE_FORMAT(solpagos.fecelab, '%d/%m/%Y') AS fecelab, 
            solpagos.idetapa, 
            etapas.nombre netapa, 
            capturista.nombre_completo, 
            solpagos.idusuario, 
            solpagos.caja_chica, 
            solpagos.servicio, 
            IFNULL(notifi.visto, 1) AS visto, 
            solpagos.moneda, 
            empresas.abrev AS nempresa 
            FROM (
                SELECT 
                    *
                FROM 
                    solpagos
                WHERE 
                    solpagos.caja_chica = 0 AND 
                    solpagos.idetapa IN (1, 3, 4, 6, 8, 21, 25) AND
                    solpagos.nomdepto = 'IMPUESTOS' AND (
                        solpagos.idusuario = ? OR
                        solpagos.idusuario IN ( SELECT usuarios.idusuario FROM usuarios WHERE FIND_IN_SET( ?, usuarios.sup ) )
                    )
            ) solpagos 
            CROSS JOIN listado_usuarios capturista ON capturista.idusuario = solpagos.idusuario 
            CROSS JOIN empresas ON empresas.idempresa = solpagos.idEmpresa 
            CROSS JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor 
            LEFT JOIN etapas ON etapas.idetapa = solpagos.idetapa 
            LEFT JOIN notifi ON notifi.idsolicitud = solpagos.idsolicitud AND notifi.idusuario = solpagos.idusuario AND notifi.idusuario = ?
            LEFT JOIN solicitud_proyecto_oficina spo ON spo.idsolicitud = solpagos.idsolicitud 
            LEFT JOIN proyectos_departamentos pd ON spo.idProyectos = pd.idProyectos /**  FIN Modificación a la consulta  para traer el nombre del proyecto | FECHA: 05-Junio-2024 | @author Lalo Silva <programador.analista38@ciudadmaderas.com>  **/
            ORDER BY solpagos.idsolicitud DESC", array(
                $this->session->userdata("inicio_sesion")['id'],
                $this->session->userdata("inicio_sesion")['id'],
                $this->session->userdata("inicio_sesion")['id']
            ));
    }

    function solicitudes_formato_impuestos(){
        return $this->db->query("SELECT * FROM solpagos WHERE solpagos.idusuario = '".$this->session->userdata("inicio_sesion")['id']."' AND solpagos.idetapa IN ( 1 ) AND solpagos.nomdepto = 'IMPUESTOS'");
    }
    
    function insertar_solicitud($data ){
        $this->db->insert("solpagos", $data);
        return $this->db->insert_id();
    }

    function verficar_duplicidad( $idsolicitud, $linea_captura ){
        return $this->db->query("SELECT /**  Correción a la query para poder realizar ediciones sin que aparezca el error de la línea de captura | FECHA: 05-Junio-2024 | @author Lalo Silva <programador.analista38@ciudadmaderas.com>  **/
                idsolicitud 
            FROM solpagos 
            WHERE nomdepto = 'IMPUESTOS' 
                AND idsolicitud <> ? 
                AND folio = ? 
                AND idetapa != 0", [ 
            $idsolicitud,  /**  FIN Correción a la query para poder realizar ediciones sin que aparezca el error de la línea de captura | FECHA: 05-Junio-2024 | @author Lalo Silva <programador.analista38@ciudadmaderas.com>  **/
            $linea_captura 
        ]);
    }
}