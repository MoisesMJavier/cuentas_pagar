<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class M_invitado extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function TablaInvitadoSolicitudesA() {

        ini_set('memory_limit','-1');

        return $this->db->query("SELECT 
        solpagos.metoPago, 
        solpagos.nomdepto,
        IF( solpagos.programado IS NOT NULL, CASE WHEN solpagos.programado < 7 THEN TIMESTAMPDIFF(MONTH, CONCAT( YEAR(solpagos.fecelab),'-',MONTH(solpagos.fecelab),'-', DAY(solpagos.fecha_fin) ), IFNULL( solpagos.fecha_fin, DATE_ADD( solpagos.fecelab, INTERVAL IF( solpagos.idetapa NOT IN ( 11, 30 ), tpagos + 1, IFNULL(tpagos, 1) )  MONTH) ) ) ELSE TIMESTAMPDIFF(WEEK, solpagos.fecelab, IFNULL(solpagos.fecha_fin, DATE_ADD( solpagos.fecelab, INTERVAL IFNULL( tpagos, 1 )  WEEK) ) ) END, NULL ) mpagar,
        IF(solpagos.fechaCreacion = '0000-00-00', NULL, solpagos.fechaCreacion) AS feccrea,
        solpagos.programado, 
        solpagos.idsolicitud, 
        solpagos.folio, 
        solpagos.proyecto, 
        solpagos.homoclave, 
        solpagos.etapa AS soletapa, 
        solpagos.condominio, 
        solpagos.idsolicitud, 
        solpagos.justificacion, 
        empresas.abrev, 
        proveedores.nombre, 
        solpagos.cantidad, 
        IFNULL(facturas.descripcion, 'SF') AS descripcion, 
        IFNULL(facturas.uuid, 'SF') AS uuid, 
        capturista.nombre_completo, 
        solpagos.fecelab AS fecelab, 
        etapas.nombre AS etapa, 
        IFNULL(cant_pag.autorizado, 0) AS autorizado, 
        IFNULL(cant_pag.pagado, 0) AS pagado, 
        IFNULL(notifi.visto, 1) AS visto, 
        solpagos.moneda, 
        solpagos.fecha_autorizacion AS fecha_autorizacion 
        FROM solpagos 
        LEFT JOIN (SELECT *, MIN(facturas.feccrea) FROM facturas WHERE facturas.tipo_factura IN ( 1, 3 ) GROUP BY facturas.idsolicitud) AS facturas ON facturas.idsolicitud = solpagos.idsolicitud 
        INNER JOIN ( SELECT usuarios.idusuario, CONCAT( usuarios.nombres,' ', usuarios.apellidos ) AS nombre_completo FROM usuarios ) AS capturista ON capturista.idusuario = solpagos.idusuario 
        INNER JOIN empresas ON solpagos.idEmpresa = empresas.idempresa 
        INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor 
        INNER JOIN etapas ON etapas.idetapa = solpagos.idetapa 
        LEFT JOIN ( SELECT autpagos.idsolicitud, COUNT(autpagos.idsolicitud) tpagos, SUM(IF( autpagos.fecha_pago IS NOT NULL OR autpagos.referencia = 'ABONO', autpagos.cantidad, 0 )) AS pagado, SUM( IF( autpagos.estatus NOT IN (2), autpagos.cantidad, 0 ) ) AS autorizado FROM autpagos GROUP BY autpagos.idsolicitud ) AS cant_pag ON cant_pag.idsolicitud = solpagos.idsolicitud 
        LEFT JOIN ( SELECT notificaciones.idsolicitud, notificaciones.visto FROM notificaciones WHERE notificaciones.idusuario = '".$this->session->userdata("inicio_sesion")['id']."' GROUP BY notificaciones.idsolicitud ) AS notifi ON notifi.idsolicitud = solpagos.idsolicitud 
        WHERE solpagos.idetapa NOT IN ( 1, 3, 4, 6, 8, 21, 25, 30 ) AND ( caja_chica = 0 OR caja_chica IS NULL )
        ORDER BY solpagos.idsolicitud DESC, solpagos.fechaCreacion");
    }

    function TablaInvitadoSolicitudesB() {
        ini_set('memory_limit','-1');

        return $this->db->query("SELECT
            solpagos.idsolicitud,
            empresas.abrev,
            solpagos.folio,
            solpagos.fechaCreacion AS feccrea,
            solpagos.fecha_autorizacion AS fecha_autorizacion,
            solpagos.fecelab AS fecelab,
            proveedores.nombre,
            solpagos.nomdepto,
            solpagos.cantidad,
            IF( solpagos.programado IS NOT NULL, CASE WHEN solpagos.programado < 7 THEN TIMESTAMPDIFF(MONTH, CONCAT( YEAR(solpagos.fecelab),'-',MONTH(solpagos.fecelab),'-', DAY(solpagos.fecha_fin) ), IFNULL( solpagos.fecha_fin, DATE_ADD( solpagos.fecelab, INTERVAL IF( solpagos.idetapa NOT IN ( 11, 30 ), tpagos + 1, IFNULL(tpagos, 1) )  MONTH) ) ) ELSE TIMESTAMPDIFF(WEEK, solpagos.fecelab, IFNULL(solpagos.fecha_fin, DATE_ADD( solpagos.fecelab, INTERVAL IFNULL( tpagos, 1 )  WEEK) ) ) END, NULL ) mpagar,
            IFNULL( autpagos.pagado, 0 ) AS pagado,
            autpagos.estatus_pago,
            solpagos.caja_chica,
            etapas.nombre AS etapa,
            solpagos.metoPago,
            solpagos.justificacion,
            capturista.nombre_completo
            FROM solpagos
            LEFT JOIN ( SELECT  autpagos.idsolicitud, COUNT(autpagos.idsolicitud) tpagos, MAX( autpagos.fechaDis ) fechaDis, SUM( autpagos.cantidad ) pagado, MIN( autpagos.estatus ) estatus_pago, MAX( autpagos.fecreg ) upago FROM autpagos GROUP BY autpagos.idsolicitud ) AS autpagos ON autpagos.idsolicitud = solpagos.idsolicitud
            INNER JOIN (SELECT  usuarios.idusuario, CONCAT(usuarios.nombres, ' ', usuarios.apellidos) AS nombre_completo FROM usuarios) AS capturista ON capturista.idusuario = solpagos.idusuario
            INNER JOIN ( SELECT CONCAT(usuarios.nombres, ' ', usuarios.apellidos) AS nombredir, usuarios.idusuario FROM usuarios ) AS director ON director.idusuario = solpagos.idResponsable
            INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor
            INNER JOIN etapas ON etapas.idetapa = solpagos.idetapa
            LEFT JOIN ( SELECT  *, MIN(facturas.feccrea) FROM facturas WHERE facturas.tipo_factura IN (1 , 3) GROUP BY facturas.idsolicitud ) AS facturas ON facturas.idsolicitud = solpagos.idsolicitud
            INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa
            LEFT JOIN ( SELECT  polizas_provision.idprovision, polizas_provision.idsolicitud, MIN( polizas_provision.fecreg ) FROM polizas_provision GROUP BY polizas_provision.idsolicitud) AS polizas_provision ON polizas_provision.idsolicitud = solpagos.idsolicitud
        WHERE
            solpagos.idetapa NOT IN (1, 3, 25, 30, 42, 45, 47, 49) AND ( caja_chica = 1 OR caja_chica IS NOT NULL )
        ORDER BY solpagos.idetapa , solpagos.fechaCreacion DESC");
    }

    function TablaBloquearProveedores(){
        return $this->db->query("SELECT 
        *, 
        fecadd AS fecha 
        FROM proveedores 
        LEFT JOIN ( SELECT bancos.idbanco, bancos.nombre AS nomba FROM bancos ) AS bancos ON proveedores.idbanco=bancos.idbanco 
        LEFT JOIN estados ON proveedores.sucursal = estados.id_estado 
        WHERE proveedores.estatus IN ( 1 , 0) 
        ORDER BY proveedores.fecadd DESC");
    }

    function cambiar_estatus_proveedor( $idproveedor, $estatus, $observaciones ){
        // FECHA : 01-JULIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
        /**
         * FECHA : 01-JULIO-2025 | @author Efrain Martinez progamador.analista38@ciudadmaderas.com | SE AGREGA EL ID DEL USUARIO QUE REALIZO LA ACTUALIZACION
         * Y LA FECAH EN QUE FUE REALIZADA.
         */
        return $this->db->update( "proveedores", array( 
            "idupdate" => $this->session->userdata("inicio_sesion")['id'],
            "fecha_update" => date("Y-m-d H:i:s"),
            "estatus" => $estatus, 
            "observaciones" => $observaciones 
            ),"idproveedor = '$idproveedor'" );
    }
}
?>