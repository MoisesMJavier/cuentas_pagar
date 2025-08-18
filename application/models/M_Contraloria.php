<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/* Regla de negocio para la gestion de CONTRALORIA 
 * de acuerdo a las devoluciones emitidas por el solicitante */

class M_Contraloria extends CI_Model {

    function __construct() {
        parent::__construct();
    }
    
      
    function get_solicitudes_autorizadas_area(){
       return $this->db->query("SELECT solpagos.nomdepto, solpagos.folio, solpagos.proyecto, solpagos.idsolicitud,  solpagos.justificacion,  CONCAT(usuarios.nombres,'\n',usuarios.apellidos) AS auto, DATE_FORMAT(solpagos.fechaCreacion, '%d/%m/%Y') AS fecha_autorizacion,  proveedores.nombre, solpagos.cantidad, empresas.abrev  FROM  solpagos INNER JOIN proveedores on solpagos.idProveedor = proveedores.idproveedor INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa LEFT JOIN usuarios ON usuarios.idusuario = solpagos.idusuario WHERE solpagos.idetapa IN( 50 );");
    } 


    function ver_datos_historial(){
       return $this->db->query("SELECT solpagos.idetapa, solpagos.nomdepto, solpagos.folio, solpagos.proyecto, solpagos.idsolicitud,  solpagos.justificacion,  CONCAT(usuarios.nombres,'\n',usuarios.apellidos) AS auto, DATE_FORMAT(solpagos.fechaCreacion, '%d/%m/%Y') AS fecha_autorizacion,  proveedores.nombre, solpagos.cantidad, empresas.abrev,e.nombre as etapa  FROM  solpagos INNER JOIN proveedores on solpagos.idProveedor = proveedores.idproveedor INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa LEFT JOIN usuarios ON usuarios.idusuario = solpagos.idusuario join etapas e on e.idetapa=solpagos.idetapa WHERE solpagos.idetapa NOT IN( 0,1,10,11,30,50 ) AND ( ( solpagos.nomdepto LIKE 'DEVOLUCION%' OR solpagos.nomdepto LIKE 'TRASPASO%' ) AND solpagos.proyecto NOT IN ( 'TRASPASO INTERNO', 'DEVOLUCION POR DOMICILIACION', 'DEVOLUCION' ) ) ORDER BY FIELD(solpagos.idetapa, '51') DESC;");
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
     
    //TODOS LOS RECHAZOS POR CONTRALORIA PASAN AL ESTATUS 54 (DEVOLUCIÓN / TRASPASO PARA REVISION)
    function regresar_devolucion( $idsol ){
        // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
        $this->db->query("UPDATE solpagos SET idetapa = 54 WHERE idsolicitud = $idsol AND idetapa IN ( 50, 51 )");
        return $this->db->affected_rows();
    }
}

