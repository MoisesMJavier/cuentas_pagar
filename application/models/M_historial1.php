<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class M_historial extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function obtenerSolCaja(){
        return $this->db->query("SELECT group_concat(s.idsolicitud) AS ID, 
             CONCAT(u.nombres, ' ', u.apellidos) AS Responsable,emp.abrev,MAX(DATE_FORMAT(s.fecelab,'%d/%b/%Y')) AS FECHAFACP ,SUM(s.cantidad) AS Cantidad ,s.nomdepto AS Departamento,s.idEmpresa AS Empresa,
             s.idResponsable IDR 
             FROM  solpagos s INNER JOIN usuarios u ON (s.idResponsable =  u.idusuario)
             INNER JOIN empresas emp ON(emp.idEmpresa = s.idEmpresa)
             INNER JOIN proveedores p ON(s.idProveedor = p.idProveedor)
             WHERE s.caja_chica = 1 AND s.idetapa IN (7)
              GROUP BY s.idResponsable ,s.idEmpresa ORDER BY s.fecelab;");
    }
 
    function getSolicitudesCCH( $idsolicitudes ){   
        return $this->db->query("SELECT solpagos.idusuario, solpagos.idsolicitud, solpagos.folio, solpagos.moneda, solpagos.justificacion, empresas.abrev, solpagos.nomdepto, proveedores.nombre AS nombre_proveedor, DATE_FORMAT( solpagos.fecelab, '%d/%m/%Y') AS fecelab, DATE_FORMAT( solpagos.fecelab, '%d/%m/%Y') AS fecautorizacion, CONCAT(usuarios.nombres, ' ', usuarios.apellidos) AS nombre_capturista, solpagos.cantidad, solpagos.proyecto, solpagos.folio, solpagos.justificacion, IFNULL(SUBSTRING(facturas.uuid, -5, 5 ), 'SF') AS ffiscal FROM solpagos INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor INNER JOIN usuarios ON usuarios.idusuario = solpagos.idusuario LEFT JOIN (SELECT *, MIN(facturas.feccrea) FROM facturas WHERE facturas.tipo_factura IN ( 1, 3 ) GROUP BY facturas.idsolicitud) AS facturas ON facturas.idsolicitud = solpagos.idsolicitud WHERE solpagos.idetapa in (7)  AND solpagos.caja_chica = 1 AND FIND_IN_SET( solpagos.idsolicitud, '$idsolicitudes' ) ORDER BY solpagos.fecha_autorizacion")->result_array();
    }

    function getSolicitudesCerCH( $idsolicitudes ){
        return $this->db->query("SELECT solpagos.idusuario, solpagos.idsolicitud, solpagos.folio, solpagos.moneda, solpagos.justificacion, empresas.abrev, solpagos.nomdepto, proveedores.nombre AS nombre_proveedor, DATE_FORMAT( solpagos.fecelab, '%d/%m/%Y') AS fecelab, DATE_FORMAT( solpagos.fecelab, '%d/%m/%Y') AS fecautorizacion, CONCAT(usuarios.nombres, ' ', usuarios.apellidos) AS nombre_capturista, solpagos.cantidad, solpagos.proyecto, solpagos.folio, solpagos.justificacion, IFNULL(SUBSTRING(facturas.uuid, -5, 5 ), 'SF') AS ffiscal FROM solpagos INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor INNER JOIN usuarios ON usuarios.idusuario = solpagos.idusuario LEFT JOIN (SELECT *, MIN(facturas.feccrea) FROM facturas WHERE facturas.tipo_factura IN ( 1, 3 ) GROUP BY facturas.idsolicitud) AS facturas ON facturas.idsolicitud = solpagos.idsolicitud WHERE solpagos.idetapa in (31) AND solpagos.caja_chica = 1 AND FIND_IN_SET( solpagos.idsolicitud, '$idsolicitudes' ) ORDER BY solpagos.fecha_autorizacion")->result_array();
    }

    function getHistorialPagosAdm() {
        switch ($this->session->userdata("inicio_sesion")['rol']) {
            case 'FP':
                return $this->db->query("SELECT
                solpagos.idsolicitud, 
                solpagos.folio,
                SUBSTR(facturas.uuid, -1, 5) uuid, 
                proveedores.nombre, 
                autpagos.fechaDis fecha_dispersion,
                autpagos.fecreg fechaaut,
                autpagos.fecha_cobro,
                autpagos.referencia,
                empresas.abrev, 
                solpagos.nomdepto, 
                autpagos.cantidad * IFNULL(autpagos.tipoCambio, 1) cantidad, 
                IFNULL(autpagos.formaPago, solpagos.metoPago) metoPago,
                IFNULL(autpagos.referencia, '') referencia,
                autpagos.estatus
                FROM autpagos 
                INNER JOIN solpagos ON autpagos.idsolicitud = solpagos.idsolicitud 
                INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor 
                LEFT JOIN factura_registro facturas ON facturas.idsolicitud = solpagos.idsolicitud 
                INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa 
                WHERE nomdepto IN ( SELECT departamento FROM departamentos INNER JOIN usuario_depto ON iddepartamentos = iddepartamento WHERE usuario_depto.idusuario = ? ) 
                ORDER BY autpagos.fecreg DESC", array( $this->session->userdata("inicio_sesion")['id'] ));
                break;
            case 'AD':
                return $this->db->query("SELECT
                solpagos.idsolicitud, 
                solpagos.folio,
                NULL uuid, 
                proveedores.nombre, 
                autpagos.fechaDis fecha_dispersion,
                autpagos.fecreg fechaaut,
                autpagos.fecha_cobro,
                autpagos.referencia,
                empresas.abrev, 
                solpagos.nomdepto, 
                autpagos.cantidad * IFNULL(autpagos.tipoCambio, 1) cantidad, 
                IFNULL(autpagos.formaPago, solpagos.metoPago) metoPago,
                IFNULL(autpagos.referencia, '') referencia,
                autpagos.estatus
                FROM autpagos 
                INNER JOIN solpagos ON autpagos.idsolicitud = solpagos.idsolicitud 
                INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor 
                INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa 
                WHERE nomdepto IN ( 'DEVOLUCIONES', 'TRASPASO' )
                AND ( solpagos.idusuario = '".$this->session->userdata("inicio_sesion")['id']."' OR solpagos.idusuario IN ( SELECT usuarios.idusuario FROM usuarios WHERE FIND_IN_SET('".$this->session->userdata("inicio_sesion")['id']."', usuarios.sup ) ) )
                ORDER BY autpagos.fecreg DESC", array( $this->session->userdata("inicio_sesion")['id'] ));
                break;
            case 'CT':
            case 'CX':
                return $this->db->query("SELECT 
                autpagos.estatus as estapag, 
                solpagos.justificacion, 
                polizas_provision.numpoliza, 
                solpagos.idsolicitud, 
                capturista.nombre_capturista, 
                responsable.nombre_responsable, 
                solpagos.etapa, 
                solpagos.condominio, 
                solpagos.fecelab fecha_factura, 
                DATE_FORMAT(solpagos.fechaCreacion, '%d/%m/%Y') AS fecha_captura,  
                autpagos.estatus, 
                DATE_FORMAT(autpagos.fecreg, '%Y/%m/%d') AS fechaaut, 
                solpagos.folio, 
                solpagos.moneda, 
                proveedores.nombre, 
                solpagos.nomdepto, 
                autpagos.cantidad, 
                IFNULL(DATE_FORMAT( IFNULL(autpagos.fechaOpe, autpagos.fecha_cobro), '%d/%m/%Y'), '') AS fechaOpe, 
                IFNULL(autpagos.formaPago, solpagos.metoPago) metoPago,
                empresas.abrev, 
                IFNULL(autpagos.referencia, '') AS referencia, 
                solpagos.proyecto, 
                solpagos.cantidad AS solicitado, 
                IFNULL(SUBSTR(facturas.uuid, -1, 5), 'SF') AS uuid, 
                IFNULL(DATE_FORMAT(autpagos.fechaDis, '%Y/%m/%d'),'') AS fecha_dispersion, 
                IFNULL(DATE_FORMAT(autpagos.fecreg, '%d/%b/%Y'),'') AS fecreg_2  
                FROM autpagos INNER JOIN solpagos ON autpagos.idsolicitud = solpagos.idsolicitud INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor INNER JOIN ( SELECT usuarios.idusuario, CONCAT( usuarios.nombres, ' ', usuarios.apellidos ) AS nombre_capturista FROM usuarios ) AS capturista ON capturista.idusuario = solpagos.idusuario INNER JOIN ( SELECT usuarios.idusuario, CONCAT( usuarios.nombres, ' ', usuarios.apellidos ) AS nombre_responsable FROM usuarios ) AS responsable ON responsable.idusuario = solpagos.idResponsable LEFT JOIN (SELECT *, MIN(facturas.feccrea) FROM facturas WHERE facturas.tipo_factura IN ( 1, 3 ) GROUP BY facturas.idsolicitud) AS facturas ON facturas.idsolicitud = solpagos.idsolicitud INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa LEFT JOIN ( SELECT * FROM polizas_provision GROUP BY polizas_provision.idsolicitud ) polizas_provision ON polizas_provision.idsolicitud = solpagos.idsolicitud WHERE ( solpagos.caja_chica IS NULL OR solpagos.caja_chica != 1 ) AND ( solpagos.idusuario = '".$this->session->userdata("inicio_sesion")['id']."' OR FIND_IN_SET( solpagos.idEmpresa, '".$this->session->userdata("inicio_sesion")['depto']."' ) ) ORDER BY autpagos.fecreg DESC");
                break;
            default:
                return $this->db->query("SELECT  
                solpagos.idsolicitud,
                IFNULL(autpagos.referencia, '') referencia,
                solpagos.folio, 
                IFNULL(SUBSTR(facturas.uuid, -1, 5), 'SF') AS uuid, 
                proveedores.nombre, 
                autpagos.fechaDis fecha_dispersion, 
                autpagos.fecreg fechaaut,
                empresas.abrev,
                solpagos.nomdepto, 
                IF( autpagos.tipoCambio IS NULL, autpagos.cantidad, autpagos.tipoCambio * autpagos.cantidad ) cantidad, 
                IFNULL(autpagos.formaPago, solpagos.metoPago) metoPago,
                solpagos.metoPago,
                autpagos.estatus
                FROM autpagos 
                INNER JOIN solpagos ON autpagos.idsolicitud = solpagos.idsolicitud 
                INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor 
                LEFT JOIN factura_registro facturas ON facturas.idsolicitud = solpagos.idsolicitud 
                INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa
                ORDER BY autpagos.fecreg DESC");
                break;
        }
    }

    function getHistorialTablaSol() {
        ini_set('memory_limit','-1');
        switch ($this->session->userdata("inicio_sesion")['rol']) {
            case 'DA':

                if($this->session->userdata("inicio_sesion")['depto'] == 'CONSTRUCCION'){
                    $departamentos = "'NOMINA DESTAJO', 'CONSTRUCCION'";
                }elseif( $this->session->userdata("inicio_sesion")['depto'] == 'COMPRAS' ){
                    $departamentos = "'CONSTRUCCION', 'COMPRAS', 'JARDINERIA', 'SISTEMAS'";
                }else{
                    $departamentos = "'".$this->session->userdata("inicio_sesion")['depto']."'";
                }

                return $this->db->query("SELECT 
                IF(facturas.uuid IS null, 'NO', 'SI') AS tienefac, 
                solpagos.programado, 
                facturas.tipo_factura, 
                solpagos.idetapa, 
                polizas_provision.idprovision, 
                solpagos.proyecto,
                IF( solpagos.programado IS NOT NULL, CASE WHEN solpagos.programado < 7 THEN TIMESTAMPDIFF(MONTH, CONCAT( YEAR(solpagos.fecelab),'-',MONTH(solpagos.fecelab),'-', DAY(solpagos.fecha_fin) ), IFNULL( solpagos.fecha_fin, DATE_ADD( solpagos.fecelab, INTERVAL IF( solpagos.idetapa NOT IN ( 11, 30 ), tpagos + 1, IFNULL(tpagos, 1) )  MONTH) ) ) ELSE TIMESTAMPDIFF(WEEK, solpagos.fecelab, IFNULL(solpagos.fecha_fin, DATE_ADD( solpagos.fecelab, INTERVAL IFNULL( tpagos, 1 )  WEEK) ) ) END, NULL ) mpagar,
                pago_generado.estatus_pago, 
                solpagos.idetapa, 
                solpagos.folio, 
                solpagos.moneda, 
                SUBSTRING(facturas.uuid, -5, 5) AS folifis, 
                solpagos.metoPago, 
                director.nombredir, 
                capturista.nombre_completo, 
                solpagos.caja_chica, 
                solpagos.idsolicitud, 
                solpagos.justificacion, 
                solpagos.nomdepto, 
                proveedores.nombre, 
                IF(solpagos.programado IS NOT NULL, solpagos.cantidad + liquidado.pagado,  solpagos.cantidad) AS cantidad, 
                empresas.abrev, 
                solpagos.fechaCreacion AS feccrea, 
                solpagos.fecha_autorizacion AS fecha_autorizacion, 
                solpagos.fecelab AS fecelab, 
                etapas.nombre AS etapa, 
                IFNULL(pago_aut.autorizado, 0) AS autorizado,   
                IFNULL(liquidado.pagado, 0) AS pagado,  
                autpagos.fechaDis as fechaDis2, 
                solpagos.fecha_autorizacion AS fech_auto  
                FROM solpagos 
                LEFT JOIN ( SELECT *, autpagos.estatus AS estatus_pago, MAX(autpagos.fecreg) FROM autpagos GROUP BY autpagos.idsolicitud ) AS pago_generado ON pago_generado.idsolicitud = solpagos.idsolicitud 
                INNER JOIN ( SELECT usuarios.idusuario, CONCAT( usuarios.nombres,' ', usuarios.apellidos ) AS nombre_completo FROM usuarios ) AS capturista ON capturista.idusuario = solpagos.idusuario 
                LEFT JOIN (SELECT autpagos.idsolicitud, SUM( autpagos.cantidad ) AS pagado FROM autpagos GROUP BY autpagos.idsolicitud) AS liquidado ON solpagos.idsolicitud = liquidado.idsolicitud 
                LEFT JOIN ( SELECT CONCAT( usuarios.nombres,' ',usuarios.apellidos ) AS nombredir, usuarios.idusuario FROM usuarios ) AS director ON director.idusuario = solpagos.idResponsable 
                INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor 
                INNER JOIN etapas ON etapas.idetapa = solpagos.idetapa 
                LEFT JOIN (SELECT *, MIN(facturas.feccrea) FROM facturas WHERE facturas.tipo_factura IN ( 1, 3 ) GROUP BY facturas.idsolicitud) AS facturas ON facturas.idsolicitud = solpagos.idsolicitud 
                LEFT JOIN ( SELECT autpagos.idsolicitud, SUM(autpagos.cantidad) AS autorizado FROM autpagos WHERE autpagos.estatus NOT IN (2) GROUP BY autpagos.idsolicitud ) AS pago_aut ON pago_aut.idsolicitud = solpagos.idsolicitud  
                INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa LEFT JOIN ( SELECT *, MAX(autpagos.fecreg), COUNT(autpagos.idsolicitud) tpagos FROM autpagos GROUP BY autpagos.idsolicitud ) AS autpagos ON autpagos.idsolicitud = solpagos.idsolicitud 
                LEFT JOIN ( SELECT polizas_provision.idprovision, polizas_provision.idsolicitud, MIN( polizas_provision.fecreg ) FROM polizas_provision GROUP BY polizas_provision.idsolicitud ) AS polizas_provision ON polizas_provision.idsolicitud = solpagos.idsolicitud 
                WHERE ( 
                    solpagos.nomdepto IN ( $departamentos )  OR 
                    solpagos.idusuario IN ( SELECT usuarios.idusuario FROM usuarios WHERE usuarios.da = '".$this->session->userdata("inicio_sesion")['id']."' OR FIND_IN_SET('".$this->session->userdata("inicio_sesion")['id']."', usuarios.sup ) ) ) AND 
                    solpagos.idetapa IN ( 10, 11, 14 ) ORDER BY solpagos.idetapa, solpagos.fechaCreacion DESC");             
                break;
            case 'AS':
                if( $this->session->userdata("inicio_sesion")['depto'] == 'CONSTRUCCION' )
                    return $this->db->query("SELECT 
                    IF(facturas.uuid IS null, 'NO', 'SI') AS tienefac, 
                    solpagos.programado, 
                    facturas.tipo_factura, 
                    solpagos.idetapa, 
                    polizas_provision.idprovision, 
                    solpagos.proyecto,
                    IF( solpagos.programado IS NOT NULL, CASE WHEN solpagos.programado < 7 THEN TIMESTAMPDIFF(MONTH, CONCAT( YEAR(solpagos.fecelab),'-',MONTH(solpagos.fecelab),'-', DAY(solpagos.fecha_fin) ), IFNULL( solpagos.fecha_fin, DATE_ADD( solpagos.fecelab, INTERVAL IF( solpagos.idetapa NOT IN ( 11, 30 ), tpagos + 1, IFNULL(tpagos, 1) )  MONTH) ) ) ELSE TIMESTAMPDIFF(WEEK, solpagos.fecelab, IFNULL(solpagos.fecha_fin, DATE_ADD( solpagos.fecelab, INTERVAL IFNULL( tpagos, 1 )  WEEK) ) ) END, NULL ) mpagar,
                    pago_generado.estatus_pago, 
                    solpagos.idetapa, 
                    solpagos.folio, 
                    solpagos.moneda, 
                    SUBSTRING(facturas.uuid, -5, 5) AS folifis, 
                    solpagos.metoPago, 
                    director.nombredir, 
                    capturista.nombre_completo, 
                    solpagos.caja_chica, 
                    solpagos.idsolicitud, 
                    solpagos.justificacion, 
                    solpagos.nomdepto,
                    proveedores.idproveedor,
                    proveedores.nombre, 
                    IF(solpagos.programado IS NOT NULL, solpagos.cantidad + liquidado.pagado,  solpagos.cantidad) AS cantidad, 
                    empresas.abrev, 
                    solpagos.fechaCreacion AS feccrea, 
                    solpagos.fecha_autorizacion AS fecha_autorizacion, 
                    solpagos.fecelab AS fecelab, 
                    etapas.nombre AS etapa, 
                    IFNULL(pago_aut.autorizado, 0) AS autorizado,   
                    IFNULL(liquidado.pagado, 0) AS pagado,  
                    autpagos.fechaDis as fechaDis2, 
                    solpagos.fecha_autorizacion AS fech_auto  
                    FROM solpagos LEFT JOIN ( SELECT *, autpagos.estatus AS estatus_pago, MAX(autpagos.fecreg) FROM autpagos GROUP BY autpagos.idsolicitud ) AS pago_generado ON pago_generado.idsolicitud = solpagos.idsolicitud INNER JOIN ( SELECT usuarios.idusuario, CONCAT( usuarios.nombres,' ', usuarios.apellidos ) AS nombre_completo FROM usuarios ) AS capturista ON capturista.idusuario = solpagos.idusuario LEFT JOIN (SELECT autpagos.idsolicitud, SUM( autpagos.cantidad ) AS pagado FROM autpagos GROUP BY autpagos.idsolicitud) AS liquidado ON solpagos.idsolicitud = liquidado.idsolicitud LEFT JOIN ( SELECT CONCAT( usuarios.nombres,' ',usuarios.apellidos ) AS nombredir, usuarios.idusuario FROM usuarios ) AS director ON director.idusuario = solpagos.idResponsable INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor INNER JOIN etapas ON etapas.idetapa = solpagos.idetapa LEFT JOIN (SELECT *, MIN(facturas.feccrea) FROM facturas WHERE facturas.tipo_factura IN ( 1, 3 ) GROUP BY facturas.idsolicitud) AS facturas ON facturas.idsolicitud = solpagos.idsolicitud LEFT JOIN ( SELECT autpagos.idsolicitud, SUM(autpagos.cantidad) AS autorizado FROM autpagos WHERE autpagos.estatus NOT IN (2) GROUP BY autpagos.idsolicitud ) AS pago_aut ON pago_aut.idsolicitud = solpagos.idsolicitud  INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa LEFT JOIN ( SELECT *, MAX(autpagos.fecreg), COUNT(autpagos.idsolicitud) tpagos FROM autpagos GROUP BY autpagos.idsolicitud ) AS autpagos ON autpagos.idsolicitud = solpagos.idsolicitud LEFT JOIN ( SELECT polizas_provision.idprovision, polizas_provision.idsolicitud, MIN( polizas_provision.fecreg ) FROM polizas_provision GROUP BY polizas_provision.idsolicitud ) AS polizas_provision ON polizas_provision.idsolicitud = solpagos.idsolicitud WHERE nomdepto IN ('CONSTRUCCION', 'JARDINERIA', 'NOMINA DESTAJO') AND solpagos.idetapa IN ( 10, 11, 14 ) ORDER BY solpagos.idetapa, solpagos.fechaCreacion DESC"); 
                else
                    return $this->db->query("SELECT 
                    IF(facturas.uuid IS null, 'NO', 'SI') AS tienefac, 
                    solpagos.programado, 
                    facturas.tipo_factura, 
                    solpagos.idetapa, 
                    polizas_provision.idprovision, 
                    solpagos.proyecto,
                    IF( solpagos.programado IS NOT NULL, CASE WHEN solpagos.programado < 7 THEN TIMESTAMPDIFF(MONTH, CONCAT( YEAR(solpagos.fecelab),'-',MONTH(solpagos.fecelab),'-', DAY(solpagos.fecha_fin) ), IFNULL( solpagos.fecha_fin, DATE_ADD( solpagos.fecelab, INTERVAL IF( solpagos.idetapa NOT IN ( 11, 30 ), tpagos + 1, IFNULL(tpagos, 1) )  MONTH) ) ) ELSE TIMESTAMPDIFF(WEEK, solpagos.fecelab, IFNULL(solpagos.fecha_fin, DATE_ADD( solpagos.fecelab, INTERVAL IFNULL( tpagos, 1 )  WEEK) ) ) END, NULL ) mpagar,
                    pago_generado.estatus_pago, 
                    solpagos.idetapa, 
                    solpagos.folio, 
                    solpagos.moneda, 
                    SUBSTRING(facturas.uuid, -5, 5) AS folifis, 
                    solpagos.metoPago, 
                    director.nombredir, 
                    capturista.nombre_completo, 
                    solpagos.caja_chica, 
                    solpagos.idsolicitud, 
                    solpagos.justificacion, 
                    solpagos.nomdepto,
                    proveedores.idproveedor,
                    proveedores.nombre, 
                    IF(solpagos.programado IS NOT NULL, solpagos.cantidad + liquidado.pagado,  solpagos.cantidad) AS cantidad, 
                    empresas.abrev, 
                    solpagos.fechaCreacion AS feccrea, 
                    solpagos.fecha_autorizacion AS fecha_autorizacion, 
                    solpagos.fecelab AS fecelab, 
                    etapas.nombre AS etapa, 
                    IFNULL(pago_aut.autorizado, 0) AS autorizado,   
                    IFNULL(liquidado.pagado, 0) AS pagado,  
                    autpagos.fechaDis as fechaDis2, 
                    solpagos.fecha_autorizacion AS fech_auto  
                    FROM solpagos LEFT JOIN ( SELECT *, autpagos.estatus AS estatus_pago, MAX(autpagos.fecreg) FROM autpagos GROUP BY autpagos.idsolicitud ) AS pago_generado ON pago_generado.idsolicitud = solpagos.idsolicitud INNER JOIN ( SELECT usuarios.idusuario, CONCAT( usuarios.nombres,' ', usuarios.apellidos ) AS nombre_completo FROM usuarios ) AS capturista ON capturista.idusuario = solpagos.idusuario LEFT JOIN (SELECT autpagos.idsolicitud, SUM( autpagos.cantidad ) AS pagado FROM autpagos GROUP BY autpagos.idsolicitud) AS liquidado ON solpagos.idsolicitud = liquidado.idsolicitud LEFT JOIN ( SELECT CONCAT( usuarios.nombres,' ',usuarios.apellidos ) AS nombredir, usuarios.idusuario FROM usuarios ) AS director ON director.idusuario = solpagos.idResponsable INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor INNER JOIN etapas ON etapas.idetapa = solpagos.idetapa LEFT JOIN (SELECT *, MIN(facturas.feccrea) FROM facturas WHERE facturas.tipo_factura IN ( 1, 3 ) GROUP BY facturas.idsolicitud) AS facturas ON facturas.idsolicitud = solpagos.idsolicitud LEFT JOIN ( SELECT autpagos.idsolicitud, SUM(autpagos.cantidad) AS autorizado FROM autpagos WHERE autpagos.estatus NOT IN (2) GROUP BY autpagos.idsolicitud ) AS pago_aut ON pago_aut.idsolicitud = solpagos.idsolicitud  INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa LEFT JOIN ( SELECT *, MAX(autpagos.fecreg), COUNT(autpagos.idsolicitud) tpagos FROM autpagos GROUP BY autpagos.idsolicitud ) AS autpagos ON autpagos.idsolicitud = solpagos.idsolicitud LEFT JOIN ( SELECT polizas_provision.idprovision, polizas_provision.idsolicitud, MIN( polizas_provision.fecreg ) FROM polizas_provision GROUP BY polizas_provision.idsolicitud ) AS polizas_provision ON polizas_provision.idsolicitud = solpagos.idsolicitud WHERE ( solpagos.nomdepto = '" . $this->session->userdata("inicio_sesion")['depto'] . "'  OR solpagos.idusuario IN ( SELECT usuarios.idusuario FROM usuarios WHERE FIND_IN_SET('".$this->session->userdata("inicio_sesion")['id']."', usuarios.sup ) ) ) AND solpagos.idetapa IN ( 10, 11, 14 ) ORDER BY solpagos.idetapa, solpagos.fechaCreacion DESC"); 
                break;
            case 'CJ':
            case 'CA':
                return $this->db->query("SELECT IFNULL(facturas.nombre_archivo, 'NA') tienefac, solpagos.programado, facturas.tipo_factura, solpagos.idetapa, polizas_provision.idprovision, solpagos.proyecto,
                IF( solpagos.programado IS NOT NULL, CASE WHEN solpagos.programado < 7 THEN TIMESTAMPDIFF(MONTH, CONCAT( YEAR(solpagos.fecelab),'-',MONTH(solpagos.fecelab),'-', DAY(solpagos.fecha_fin) ), IFNULL( solpagos.fecha_fin, DATE_ADD( solpagos.fecelab, INTERVAL IF( solpagos.idetapa NOT IN ( 11, 30 ), tpagos + 1, IFNULL(tpagos, 1) )  MONTH) ) ) ELSE TIMESTAMPDIFF(WEEK, solpagos.fecelab, IFNULL(solpagos.fecha_fin, DATE_ADD( solpagos.fecelab, INTERVAL IFNULL( tpagos, 1 )  WEEK) ) ) END, NULL ) mpagar,
                autpagos.estatus_pago, solpagos.idetapa, IFNULL( facturas.foliofac, solpagos.folio) folio, solpagos.moneda, SUBSTRING(facturas.uuid, -5, 5) AS folifis, solpagos.metoPago, director.nombredir, capturista.nombre_completo, solpagos.caja_chica, solpagos.idsolicitud, solpagos.justificacion, solpagos.nomdepto, proveedores.nombre, solpagos.cantidad, empresas.abrev, solpagos.fechaCreacion AS feccrea, solpagos.fecha_autorizacion AS fecha_autorizacion, solpagos.fecelab AS fecelab, IFNULL(autpagos.fautorizado, ' - ') fautorizado, etapas.nombre AS etapa, IFNULL(autpagos.autorizado, 0) AS autorizado, IFNULL(autpagos.pagado, 0) AS pagado, IFNULL(autpagos.fdispersion, ' - ') fechaDis2 FROM solpagos INNER JOIN ( SELECT usuarios.idusuario, CONCAT( usuarios.nombres,' ', usuarios.apellidos ) AS nombre_completo FROM usuarios ) AS capturista ON capturista.idusuario = solpagos.idusuario LEFT JOIN ( SELECT CONCAT( usuarios.nombres,' ',usuarios.apellidos ) AS nombredir, usuarios.idusuario FROM usuarios ) AS director ON director.idusuario = solpagos.idResponsable INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor INNER JOIN etapas ON etapas.idetapa = solpagos.idetapa LEFT JOIN (SELECT *, MIN(facturas.feccrea) FROM facturas WHERE facturas.tipo_factura IN ( 1, 3 ) GROUP BY facturas.idsolicitud) AS facturas ON facturas.idsolicitud = solpagos.idsolicitud INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa LEFT JOIN ( SELECT autpagos.idsolicitud, autpagos.estatus estatus_pago, SUM( IF( autpagos.estatus != 2, autpagos.cantidad, 0 ) ) pagado, SUM( autpagos.cantidad ) autorizado, MAX( autpagos.fecreg ) fautorizado, MAX( autpagos.fechaDis ) fdispersion, COUNT(autpagos.idsolicitud) tpagos FROM autpagos GROUP BY autpagos.idsolicitud ) AS autpagos ON autpagos.idsolicitud = solpagos.idsolicitud LEFT JOIN ( SELECT polizas_provision.idprovision, polizas_provision.idsolicitud, MIN( polizas_provision.fecreg ) FROM polizas_provision GROUP BY polizas_provision.idsolicitud ) AS polizas_provision ON polizas_provision.idsolicitud = solpagos.idsolicitud WHERE /*( solpagos.caja_chica IS NULL OR solpagos.caja_chica != 1 ) AND*/( solpagos.idusuario = '".$this->session->userdata("inicio_sesion")['id']."' OR solpagos.idusuario IN ( SELECT usuarios.idusuario FROM usuarios WHERE FIND_IN_SET('".$this->session->userdata("inicio_sesion")['id']."', usuarios.sup ) ) ) AND solpagos.idetapa IN ( 10, 11, 14 ) ORDER BY solpagos.idetapa, solpagos.fechaCreacion DESC");
                break;
            case 'FP':
                return $this->db->query("SELECT
                solpagos.idsolicitud, 
                empresas.abrev, 
                solpagos.proyecto, 
                solpagos.folio, 
                proveedores.nombre, 
                solpagos.fecha_autorizacion,
                director.nombre_completo nombredir,
                capturista.nombre_completo,
                IF(solpagos.programado IS NOT NULL, solpagos.cantidad + IFNULL(cant_pag.pagado, 0),  solpagos.cantidad) AS mpagar,
                solpagos.descuento,
                cant_pag.pagado, 
                etapas.nombre AS etapa, 
                solpagos.etapa AS soletapa, 
                solpagos.condominio,
                solpagos.metoPago, 
                solpagos.nomdepto, 
                solpagos.fechaCreacion feccrea, 
                solpagos.programado, 
                solpagos.homoclave, 
                solpagos.justificacion, 
                facturas.uuid tienefac, 
                DATE(solpagos.fecelab) AS fecelab, 
                IFNULL(notifi.visto, 1) AS visto, 
                solpagos.moneda
                FROM solpagos
                LEFT JOIN factura_registro facturas ON facturas.idsolicitud = solpagos.idsolicitud 
                INNER JOIN listado_usuarios director ON director.idusuario = solpagos.idusuario 
                INNER JOIN listado_usuarios capturista ON capturista.idusuario = solpagos.idusuario 
                INNER JOIN empresas ON solpagos.idEmpresa = empresas.idempresa 
                INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor 
                INNER JOIN etapas ON etapas.idetapa = solpagos.idetapa 
                LEFT JOIN vw_autpagos cant_pag ON cant_pag.idsolicitud = solpagos.idsolicitud 
                LEFT JOIN ( SELECT notificaciones.idsolicitud, notificaciones.visto FROM notificaciones WHERE notificaciones.idusuario = '".$this->session->userdata("inicio_sesion")['id']."' GROUP BY notificaciones.idsolicitud ) AS notifi ON notifi.idsolicitud = solpagos.idsolicitud 
                WHERE ( solpagos.idusuario = '".$this->session->userdata("inicio_sesion")['id']."' OR solpagos.idusuario IN ( SELECT usuarios.idusuario FROM usuarios WHERE FIND_IN_SET('".$this->session->userdata("inicio_sesion")['id']."', usuarios.sup ) ) ) 
                AND solpagos.idetapa IN ( 10, 11, 14 ) 
                ORDER BY solpagos.idetapa, solpagos.fechaCreacion DESC");
                break;
            case 'CX':
                return $this->db->query("SELECT 
                facturas.nombre_archivo tienefac, 
                solpagos.programado, 
                facturas.tipo_factura, 
                solpagos.idetapa, 
                polizas_provision.idprovision, 
                IF( solpagos.programado IS NOT NULL, CASE WHEN solpagos.programado < 7 THEN TIMESTAMPDIFF(MONTH, CONCAT( YEAR(solpagos.fecelab),'-',MONTH(solpagos.fecelab),'-', DAY(solpagos.fecha_fin) ), IFNULL( solpagos.fecha_fin, DATE_ADD( solpagos.fecelab, INTERVAL IF( solpagos.idetapa NOT IN ( 11, 30 ), tpagos + 1, IFNULL(tpagos, 1) )  MONTH) ) ) ELSE TIMESTAMPDIFF(WEEK, solpagos.fecelab, IFNULL(solpagos.fecha_fin, DATE_ADD( solpagos.fecelab, INTERVAL IFNULL( tpagos, 1 )  WEEK) ) ) END, NULL ) mpagar,
                autpagos.estatus_pago, 
                solpagos.idetapa, 
                IFNULL( facturas.foliofac, solpagos.folio) folio, 
                solpagos.moneda, 
                SUBSTRING(facturas.uuid, -5, 5) AS folifis, 
                solpagos.metoPago, 
                director.nombredir, 
                capturista.nombre_completo, 
                solpagos.caja_chica, 
                solpagos.idsolicitud, 
                solpagos.justificacion, 
                solpagos.nomdepto, 
                proveedores.nombre, 
                IF(solpagos.programado IS NOT NULL, solpagos.cantidad + autpagos.pagado,  solpagos.cantidad) AS cantidad, 
                empresas.abrev, 
                solpagos.fechaCreacion AS feccrea, 
                solpagos.fecha_autorizacion AS fecha_autorizacion, 
                solpagos.fecelab AS fecelab, 
                IFNULL(autpagos.fautorizado, ' - ') fautorizado, 
                etapas.nombre AS etapa, 
                IFNULL(autpagos.autorizado, 0) AS autorizado, 
                IFNULL(autpagos.pagado, 0) AS pagado, 
                IFNULL(autpagos.fdispersion, ' - ') fechaDis2 
                FROM solpagos INNER JOIN ( SELECT usuarios.idusuario, CONCAT( usuarios.nombres,' ', usuarios.apellidos ) AS nombre_completo FROM usuarios ) AS capturista ON capturista.idusuario = solpagos.idusuario LEFT JOIN ( SELECT CONCAT( usuarios.nombres,' ',usuarios.apellidos ) AS nombredir, usuarios.idusuario FROM usuarios ) AS director ON director.idusuario = solpagos.idResponsable INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor INNER JOIN etapas ON etapas.idetapa = solpagos.idetapa LEFT JOIN (SELECT *, MIN(facturas.feccrea) FROM facturas WHERE facturas.tipo_factura IN ( 1, 3 ) GROUP BY facturas.idsolicitud) AS facturas ON facturas.idsolicitud = solpagos.idsolicitud INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa LEFT JOIN ( SELECT autpagos.idsolicitud, autpagos.estatus estatus_pago, SUM( IF( autpagos.estatus != 2, autpagos.cantidad, 0 ) ) pagado, SUM( autpagos.cantidad ) autorizado, MAX( autpagos.fecreg ) fautorizado, MAX( autpagos.fechaDis ) fdispersion, COUNT(autpagos.idsolicitud) tpagos FROM autpagos GROUP BY autpagos.idsolicitud ) AS autpagos ON autpagos.idsolicitud = solpagos.idsolicitud LEFT JOIN ( SELECT polizas_provision.idprovision, polizas_provision.idsolicitud, MIN( polizas_provision.fecreg ) FROM polizas_provision GROUP BY polizas_provision.idsolicitud ) AS polizas_provision ON polizas_provision.idsolicitud = solpagos.idsolicitud WHERE ( solpagos.caja_chica IS NULL OR solpagos.caja_chica != 1 ) AND ( solpagos.idusuario = '".$this->session->userdata("inicio_sesion")['id']."' OR FIND_IN_SET( solpagos.idEmpresa, '".$this->session->userdata("inicio_sesion")['depto']."' ) ) AND solpagos.idetapa IN ( 10, 11, 14 ) ORDER BY solpagos.idetapa, solpagos.fechaCreacion DESC");
                break;
            case 'CI':
                return $this->db->query("SELECT * FROM solicitudes_sin_factura WHERE 
                ( ( solicitudes_sin_factura.nomdepto LIKE 'DEVOLUCION%' OR solicitudes_sin_factura.nomdepto LIKE 'TRASPASO%' ) AND solicitudes_sin_factura.proyecto NOT IN ( 'TRASPASO INTERNO', 'DEVOLUCION POR DOMICILIACION', 'DEVOLUCION', 'DEVOLUCION POR DEPOSITO EN GARANTIA', 'DEVOLUCION POR MEDIDOR' ) )
                AND solicitudes_sin_factura.idetapa IN (10 , 11, 14)"); 
                break;
            default:
                //return $this->db->query("SELECT IF(facturas.uuid IS null, 'NO', 'SI') AS tienefac, facturas.tipo_factura, solpagos.idetapa, polizas_provision.idprovision, pago_generado.estatus_pago, solpagos.idetapa, solpagos.folio, solpagos.moneda, SUBSTRING(facturas.uuid, -5, 5) AS folifis, solpagos.metoPago, director.nombredir, capturista.nombre_completo, solpagos.caja_chica, solpagos.idsolicitud, solpagos.justificacion, solpagos.nomdepto, proveedores.nombre, solpagos.cantidad, empresas.abrev, DATE_FORMAT(solpagos.fechaCreacion, '%d/%m/%Y') AS feccrea, DATE_FORMAT(solpagos.fecha_autorizacion, '%d/%b/%Y') AS fecha_autorizacion, DATE_FORMAT(solpagos.fecelab, '%d/%m/%Y') AS fecelab, etapas.nombre AS etapa, IFNULL(pago_aut.autorizado, 0) AS autorizado,   IFNULL(liquidado.pagado, 0) AS pagado,   DATE_FORMAT(autpagos.fechaDis, '%d/%b/%Y') as fechaDis2 ,DATE_FORMAT(solpagos.fecha_autorizacion, '%d/%b/%Y') AS fech_auto  FROM solpagos LEFT JOIN ( SELECT *, autpagos.estatus AS estatus_pago, MAX(autpagos.fecreg) FROM autpagos GROUP BY autpagos.idsolicitud ) AS pago_generado ON pago_generado.idsolicitud = solpagos.idsolicitud INNER JOIN ( SELECT usuarios.idusuario, CONCAT( usuarios.nombres,' ', usuarios.apellidos ) AS nombre_completo FROM usuarios ) AS capturista ON capturista.idusuario = solpagos.idusuario LEFT JOIN (SELECT autpagos.idsolicitud, SUM( autpagos.cantidad ) AS pagado FROM autpagos GROUP BY autpagos.idsolicitud) AS liquidado ON solpagos.idsolicitud = liquidado.idsolicitud LEFT JOIN ( SELECT CONCAT( usuarios.nombres,' ',usuarios.apellidos ) AS nombredir, usuarios.idusuario FROM usuarios ) AS director ON director.idusuario = solpagos.idResponsable INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor INNER JOIN etapas ON etapas.idetapa = solpagos.idetapa LEFT JOIN (SELECT *, MIN(facturas.feccrea) FROM facturas WHERE facturas.tipo_factura IN ( 1, 3 ) GROUP BY facturas.idsolicitud) AS facturas ON facturas.idsolicitud = solpagos.idsolicitud LEFT JOIN ( SELECT autpagos.idsolicitud, SUM(autpagos.cantidad) AS autorizado FROM autpagos WHERE autpagos.estatus NOT IN (2) GROUP BY autpagos.idsolicitud ) AS pago_aut ON pago_aut.idsolicitud = solpagos.idsolicitud  INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa LEFT JOIN ( SELECT *, MAX(autpagos.fecreg) FROM autpagos GROUP BY autpagos.idsolicitud ) AS autpagos ON autpagos.idsolicitud = solpagos.idsolicitud LEFT JOIN ( SELECT polizas_provision.idprovision, polizas_provision.idsolicitud, MIN( polizas_provision.fecreg ) FROM polizas_provision GROUP BY polizas_provision.idsolicitud ) AS polizas_provision ON polizas_provision.idsolicitud = solpagos.idsolicitud WHERE ( solpagos.idetapa = 2 OR solpagos.idetapa >= 5 ) ORDER BY solpagos.idetapa, solpagos.fechaCreacion DESC");
                return $this->db->query("SELECT 
                    facturas.nombre_archivo tienefac,
                    facturas.tipo_factura,
                    solpagos.idetapa,
                    solpagos.proyecto,
                    polizas_provision.idprovision,
                    solpagos.programado,
                    IF( solpagos.programado IS NOT NULL, CASE WHEN solpagos.programado < 7 THEN TIMESTAMPDIFF(MONTH, CONCAT( YEAR(solpagos.fecelab),'-',MONTH(solpagos.fecelab),'-', DAY(solpagos.fecha_fin) ), IFNULL( solpagos.fecha_fin, DATE_ADD( solpagos.fecelab, INTERVAL IF( solpagos.idetapa NOT IN ( 11, 30 ), tpagos + 1, IFNULL(tpagos, 1) )  MONTH) ) ) ELSE TIMESTAMPDIFF(WEEK, solpagos.fecelab, IFNULL(solpagos.fecha_fin, DATE_ADD( solpagos.fecelab, INTERVAL IFNULL( tpagos, 1 )  WEEK) ) ) END, NULL ) mpagar,
                    autpagos.estatus_pago,
                    IFNULL(facturas.foliofac, solpagos.folio) folio,
                    solpagos.moneda,
                    SUBSTRING(facturas.uuid, - 5, 5) AS folifis,
                    solpagos.metoPago,
                    director.nombredir,
                    capturista.nombre_completo,
                    solpagos.caja_chica,
                    solpagos.idsolicitud,
                    solpagos.justificacion,
                    solpagos.nomdepto,
                    proveedores.nombre,
                    IF(solpagos.programado IS NOT NULL, solpagos.cantidad + autpagos.pagado,  solpagos.cantidad) AS cantidad,
                    empresas.abrev,
                    solpagos.fechaCreacion AS feccrea,
                    solpagos.fecha_autorizacion AS fecha_autorizacion,
                    solpagos.fecelab AS fecelab,
                    IFNULL(autpagos.fautorizado,
                            ' - ') AS fautorizado,
                    etapas.nombre AS etapa,
                    IFNULL(autpagos.autorizado, 0) AS autorizado,
                    IFNULL(autpagos.pagado, 0) AS pagado,
                    IFNULL(autpagos.fdispersion,
                            ' - ') AS fechaDis2
                FROM
                    solpagos
                        INNER JOIN
                    (SELECT 
                        usuarios.idusuario,
                            CONCAT(usuarios.nombres, ' ', usuarios.apellidos) AS nombre_completo
                    FROM
                        usuarios) AS capturista ON capturista.idusuario = solpagos.idusuario
                        LEFT JOIN
                    (SELECT 
                        CONCAT(usuarios.nombres, ' ', usuarios.apellidos) AS nombredir,
                            usuarios.idusuario
                    FROM
                        usuarios) AS director ON director.idusuario = solpagos.idResponsable
                        INNER JOIN
                    proveedores ON proveedores.idproveedor = solpagos.idProveedor
                        INNER JOIN
                    etapas ON etapas.idetapa = solpagos.idetapa
                        LEFT JOIN
                    (SELECT 
                        *, MIN(facturas.feccrea)
                    FROM
                        facturas
                    WHERE
                        facturas.tipo_factura IN (1 , 3)
                    GROUP BY facturas.idsolicitud) AS facturas ON facturas.idsolicitud = solpagos.idsolicitud
                        INNER JOIN
                    empresas ON empresas.idempresa = solpagos.idEmpresa
                        LEFT JOIN
                    (SELECT 
                        autpagos.idsolicitud,
                            COUNT(autpagos.idsolicitud) tpagos,
                            autpagos.estatus estatus_pago,
                            SUM(IF(autpagos.estatus != 2, autpagos.cantidad, 0)) pagado,
                            SUM(autpagos.cantidad) autorizado,
                            MAX(autpagos.fecreg) fautorizado,
                            MAX(autpagos.fechaDis) fdispersion
                    FROM
                        autpagos
                    GROUP BY autpagos.idsolicitud) AS autpagos ON autpagos.idsolicitud = solpagos.idsolicitud
                        LEFT JOIN
                    (SELECT 
                        polizas_provision.idprovision,
                            polizas_provision.idsolicitud,
                            MIN(polizas_provision.fecreg)
                    FROM
                        polizas_provision
                    GROUP BY polizas_provision.idsolicitud) AS polizas_provision ON polizas_provision.idsolicitud = solpagos.idsolicitud
                WHERE
                    ( solpagos.caja_chica IS NULL OR solpagos.caja_chica != 0 )
                    AND solpagos.idetapa NOT IN (1, 3, 25, 30, 42, 45, 47, 49) /*AND ( solpagos.caja_chica = 0 OR solpagos.caja_chica IS NULL )*/
                ORDER BY solpagos.idetapa , solpagos.fechaCreacion DESC");
                break;
        }
    }

    //HISTORIAL DE CUENTAS POR PAGAR - PAGO A PROVEEDOR
    function getHistorialTablaSolA() {

        ini_set('memory_limit','-1');

        return $this->db->query("SELECT 
            solpagos.idsolicitud,
            solpagos.idetapa,
            empresas.abrev,
            solpagos.folio,
            solpagos.fechaCreacion AS feccrea,
            solpagos.fecha_autorizacion AS fecha_autorizacion,
            solpagos.fecelab AS fecelab,
            proveedores.nombre,
            solpagos.nomdepto,
            IF( solpagos.programado IS NOT NULL, IF( solpagos.idetapa NOT IN ( 11, 30, 0 ), solpagos.cantidad, 0 ) + IFNULL (autpagos.pagado, 0),  solpagos.cantidad ) AS cantidad,
            IF( solpagos.programado IS NOT NULL, CASE WHEN solpagos.programado < 7 THEN TIMESTAMPDIFF(MONTH, CONCAT( YEAR(solpagos.fecelab),'-',MONTH(solpagos.fecelab),'-', DAY(solpagos.fecha_fin) ), IFNULL( solpagos.fecha_fin, DATE_ADD( solpagos.fecelab, INTERVAL IF( solpagos.idetapa NOT IN ( 11, 30 ), tpagos + 1, IFNULL(tpagos, 1) )  MONTH) ) ) ELSE TIMESTAMPDIFF(WEEK, solpagos.fecelab, IFNULL(solpagos.fecha_fin, DATE_ADD( solpagos.fecelab, INTERVAL IFNULL( tpagos, 1 )  WEEK) ) ) END, NULL ) mpagar,
            IFNULL( autpagos.pagado, 0 ) AS pagado,
            autpagos.estatus_pago,
            solpagos.caja_chica,
            etapas.nombre AS etapa,
            solpagos.metoPago,
            solpagos.justificacion,
            capturista.nombre_completo,
            solpagos.programado
            FROM solpagos
            LEFT JOIN ( SELECT  vw_autpagos.idsolicitud, tpagos, fechaDis, pagado, estatus_pago, upago FROM vw_autpagos GROUP BY vw_autpagos.idsolicitud ) AS autpagos ON autpagos.idsolicitud = solpagos.idsolicitud
            INNER JOIN (SELECT  usuarios.idusuario, CONCAT(usuarios.nombres, ' ', usuarios.apellidos) AS nombre_completo FROM usuarios) AS capturista ON capturista.idusuario = solpagos.idusuario
            INNER JOIN ( SELECT CONCAT(usuarios.nombres, ' ', usuarios.apellidos) AS nombredir, usuarios.idusuario FROM usuarios ) AS director ON director.idusuario = solpagos.idResponsable
            INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor
            INNER JOIN etapas ON etapas.idetapa = solpagos.idetapa
            LEFT JOIN ( SELECT  *, MIN(facturas.feccrea) FROM facturas WHERE facturas.tipo_factura IN (1 , 3) GROUP BY facturas.idsolicitud ) AS facturas ON facturas.idsolicitud = solpagos.idsolicitud
            INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa
            LEFT JOIN ( SELECT  polizas_provision.idprovision, polizas_provision.idsolicitud, MIN( polizas_provision.fecreg ) FROM polizas_provision GROUP BY polizas_provision.idsolicitud) AS polizas_provision ON polizas_provision.idsolicitud = solpagos.idsolicitud
        WHERE
            solpagos.idetapa NOT IN (1 , 25, 30, 42, 45, 47, 49) AND ( caja_chica = 0 OR caja_chica IS NULL )
        ORDER BY solpagos.idetapa , solpagos.fechaCreacion DESC");
    }
    // function getHistorialTablaSolA() {

    //     ini_set('memory_limit','-1');

        // return $this->db->query("SELECT polizas_provision.idprovision,
        //     autpagos.estatus_pago,
        //     solpagos.idetapa,
        //     solpagos.proyecto,
        //     solpagos.condominio, 
        //     solpagos.etapa setapa,
        //     solpagos.folio,
        //     solpagos.moneda,
        //     solpagos.programado,
        //     IF( solpagos.programado IS NOT NULL, CASE WHEN solpagos.programado < 7 THEN TIMESTAMPDIFF(MONTH, CONCAT( YEAR(solpagos.fecelab),'-',MONTH(solpagos.fecelab),'-', DAY(solpagos.fecha_fin) ),solpsolpagos.fecelab),'-',MONTH(solpagos.fecelab),'-', DAY(solpagos.fecha_fin) ), IFNULL( solpagos.fecha_fin, DATE_ADD( solpagos.fecelab, INTERVAL IF( solpagos.idetapa NOT IN ( 11, 30 ), tpagos + 1, IFNULL(tpagos, 1) )  MONTH) ) ) ELSE TIMESTAMPDIFF(WEEK, solpagos.fecelab, IFNULL(solpagos.fecha_fin, DATE_ADD( solpagos.fecelab, INTERVAL IFNULL( tpagos, 1 )  WEEK) ) ) END, NULL ) mpagar,
        //     facturas.tipo_factura,
        //     IFNULL( facturas.uuid, 'NO' ) AS tienefac,
        //     SUBSTRING(facturas.uuid, - 5, 5) AS folifis,
        //     solpagos.metoPago,
        //     director.nombredir,
        //     capturista.nombre_completo,
        //     solpagos.caja_chica,
        //     solpagos.idsolicitud,
        //     solpagos.justificacion,
        //     solpagos.nomdepto,
        //     proveedores.nombre,
        //     solpagos.cantidad,
        //     empresas.abrev,
        //     DATE_FORMAT(solpagos.fechaCreacion, '%d/%m/%Y') AS feccrea,
        //     DATE_FORMAT(solpagos.fecha_autorizacion, '%d/%b/%Y') AS fecha_autorizacion,
        //     DATE_FORMAT(solpagos.fecelab, '%d/%m/%Y') AS fecelab,
        //     etapas.nombre AS etapa,
        //     IFNULL( autpagos.pagado, 0 ) AS pagado,
        //     DATE_FORMAT(autpagos.fechaDis, '%d/%b/%Y') AS fechaDis2,
        //     DATE_FORMAT(solpagos.fecha_autorizacion, '%d/%b/%Y') AS fech_auto
        //     FROM solpagos
        //     LEFT JOIN ( SELECT  autpagos.idsolicitud, COUNT(autpagos.idsolicitud) tpagos, MAX( autpagos.fechaDis ) fechaDis, SUM( autpagos.cantidad ) pagado, MIN( autpagos.estatus ) estatus_pago, MAX( autpagos.fecreg ) upago FROM autpagos GROUP BY autpagos.idsolicitud ) AS autpagos ON autpagos.idsolicitud = solpagos.idsolicitud
        //     INNER JOIN (SELECT  usuarios.idusuario, CONCAT(usuarios.nombres, ' ', usuarios.apellidos) AS nombre_completo FROM usuarios) AS capturista ON capturista.idusuario = solpagos.idusuario
        //     INNER JOIN ( SELECT CONCAT(usuarios.nombres, ' ', usuarios.apellidos) AS nombredir, usuarios.idusuario FROM usuarios ) AS director ON director.idusuario = solpagos.idResponsable
        //     INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor
        //     INNER JOIN etapas ON etapas.idetapa = solpagos.idetapa
        //     LEFT JOIN ( SELECT  *, MIN(facturas.feccrea) FROM facturas WHERE facturas.tipo_factura IN (1 , 3) GROUP BY facturas.idsolicitud ) AS facturas ON facturas.idsolicitud = solpagos.idsolicitud
        //     INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa
        //     LEFT JOIN ( SELECT  polizas_provision.idprovision, polizas_provision.idsolicitud, MIN( polizas_provision.fecreg ) FROM polizas_provision GROUP BY polizas_provision.idsolicitud) AS polizas_provision ON polizas_provision.idsolicitud = solpagos.idsolicitud
        // WHERE
        //     solpagos.idetapa NOT IN (1 , 25, 30, 42, 45, 47, 49) AND ( caja_chica = 0 OR caja_chica IS NULL )
        // ORDER BY solpagos.idetapa , solpagos.fechaCreacion DESC");
    // }

    //HISTORIAL DE CUENTAS POR PAGAR - PAGO A CAJA CHICA
    function getHistorialTablaSolCajachica() {

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
            IF(solpagos.programado IS NOT NULL, solpagos.cantidad + IFNULL(autpagos.pagado, 0),  solpagos.cantidad) AS cantidad,
            IF( solpagos.programado IS NOT NULL, CASE WHEN solpagos.programado < 7 THEN TIMESTAMPDIFF(MONTH, CONCAT( YEAR(solpagos.fecelab),'-',MONTH(solpagos.fecelab),'-', DAY(solpagos.fecha_fin) ), IFNULL( solpagos.fecha_fin, DATE_ADD( solpagos.fecelab, INTERVAL IF( solpagos.idetapa NOT IN ( 11, 30 ), tpagos + 1, IFNULL(tpagos, 1) )  MONTH) ) ) ELSE TIMESTAMPDIFF(WEEK, solpagos.fecelab, IFNULL(solpagos.fecha_fin, DATE_ADD( solpagos.fecelab, INTERVAL IFNULL( tpagos, 1 )  WEEK) ) ) END, NULL ) mpagar,
            IFNULL( autpagos.pagado, 0 ) AS pagado,
            autpagos.estatus_pago,
            solpagos.caja_chica,
            etapas.nombre AS etapa,
            solpagos.metoPago,
            solpagos.justificacion,
            solpagos.idetapa,
            capturista.nombre_completo,
            solpagos.programado
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
            solpagos.idetapa NOT IN (1 , 25, 30, 42, 45, 47, 49) AND ( caja_chica = 1 )
        ORDER BY solpagos.idetapa , solpagos.fechaCreacion DESC");
    }

    //HISTORIAL DE CUENTAS POR PAGAR - PAGO A CAJA CHICA
    function getHistorialTablaSolTDC() {

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
            IF(solpagos.programado IS NOT NULL, solpagos.cantidad + IFNULL(autpagos.pagado, 0),  solpagos.cantidad) AS cantidad,
            IF( solpagos.programado IS NOT NULL, CASE WHEN solpagos.programado < 7 THEN TIMESTAMPDIFF(MONTH, CONCAT( YEAR(solpagos.fecelab),'-',MONTH(solpagos.fecelab),'-', DAY(solpagos.fecha_fin) ), IFNULL( solpagos.fecha_fin, DATE_ADD( solpagos.fecelab, INTERVAL IF( solpagos.idetapa NOT IN ( 11, 30 ), tpagos + 1, IFNULL(tpagos, 1) )  MONTH) ) ) ELSE TIMESTAMPDIFF(WEEK, solpagos.fecelab, IFNULL(solpagos.fecha_fin, DATE_ADD( solpagos.fecelab, INTERVAL IFNULL( tpagos, 1 )  WEEK) ) ) END, NULL ) mpagar,
            IFNULL( autpagos.pagado, 0 ) AS pagado,
            autpagos.estatus_pago,
            solpagos.caja_chica,
            etapas.nombre AS etapa,
            solpagos.metoPago,
            solpagos.justificacion,
            solpagos.idetapa,
            capturista.nombre_completo,
            solpagos.programado
            FROM solpagos
            LEFT JOIN ( SELECT  autpagos.idsolicitud, COUNT(autpagos.idsolicitud) tpagos, MAX( autpagos.fechaDis ) fechaDis, SUM( autpagos.cantidad ) pagado, MIN( autpagos.estatus ) estatus_pago, MAX( autpagos.fecreg ) upago FROM autpagos GROUP BY autpagos.idsolicitud ) AS autpagos ON autpagos.idsolicitud = solpagos.idsolicitud
            INNER JOIN listado_usuarios capturista ON capturista.idusuario = solpagos.idusuario
            INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor
            INNER JOIN etapas ON etapas.idetapa = solpagos.idetapa
            INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa
        WHERE
            solpagos.idetapa NOT IN ( 1, 25, 30, 42, 45, 47, 49 ) AND ( caja_chica = 2 )
        ORDER BY solpagos.idetapa , solpagos.fechaCreacion DESC");
    }

    function getHistorialTablaSolC(){
        /*LISTADO DE SOLICITUDES REALIZADAS Y SE HAN CANCELADO*/
        //TODO LAS LINEAS COMENTADAS EN LA COLSULTA SON PARTE PARA LOS PAGOS REALIZADOS A LAS SOLICITUDES
        //AL SER SOLICITUDES CANCELADAS NO DEBEN DE TENER ALGUN PAGO HECHO         
        return $this->db->query("SELECT polizas_provision.idprovision,
            /*autpagos.estatus_pago,*/
            solpagos.idetapa,
            solpagos.folio,
            solpagos.moneda,
            facturas.tipo_factura,
            IFNULL( facturas.uuid, 'NO' ) AS tienefac,
            SUBSTRING(facturas.uuid, - 5, 5) AS folifis,
            solpagos.metoPago,
            director.nombredir,
            capturista.nombre_completo,
            solpagos.caja_chica,
            solpagos.idsolicitud,
            solpagos.justificacion,
            solpagos.nomdepto,
            proveedores.nombre,
            solpagos.cantidad,
            empresas.abrev,
            solpagos.fechaCreacion AS feccrea,
            solpagos.fecha_autorizacion AS fecha_autorizacion,
            solpagos.fecelab AS fecelab,
            etapas.nombre AS etapa,
            /*IFNULL( autpagos.pagado, 0 ) AS pagado,*/
            /*DATE_FORMAT(autpagos.fechaDis, %d/%b/%Y) AS fechaDis2,*/
            solpagos.fecha_autorizacion AS fech_auto
            FROM solpagos
            /*LEFT JOIN ( SELECT  autpagos.idsolicitud, MAX( autpagos.fechaDis ) fechaDis, SUM( autpagos.cantidad ) pagado, MIN( autpagos.estatus ) estatus_pago, MAX( autpagos.fecreg ) upago FROM autpagos GROUP BY autpagos.idsolicitud ) AS autpagos ON autpagos.idsolicitud = solpagos.idsolicitud*/
            INNER JOIN (SELECT  usuarios.idusuario, CONCAT(usuarios.nombres, ' ', usuarios.apellidos) AS nombre_completo FROM usuarios) AS capturista ON capturista.idusuario = solpagos.idusuario
            INNER JOIN ( SELECT CONCAT(usuarios.nombres, ' ', usuarios.apellidos) AS nombredir, usuarios.idusuario FROM usuarios ) AS director ON director.idusuario = solpagos.idResponsable
            INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor
            INNER JOIN etapas ON etapas.idetapa = solpagos.idetapa
            LEFT JOIN ( SELECT  *, MIN(facturas.feccrea) FROM facturas WHERE facturas.tipo_factura IN (1 , 3) GROUP BY facturas.idsolicitud ) AS facturas ON facturas.idsolicitud = solpagos.idsolicitud
            INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa
            LEFT JOIN ( SELECT  polizas_provision.idprovision, polizas_provision.idsolicitud, MIN( polizas_provision.fecreg ) FROM polizas_provision GROUP BY polizas_provision.idsolicitud) AS polizas_provision ON polizas_provision.idsolicitud = solpagos.idsolicitud
        WHERE
            solpagos.idetapa IN ( 30 )
        ORDER BY solpagos.idetapa , solpagos.fechaCreacion DESC");    
    }

    function getHistorialTablaSolP() {
        //CONSULTA ORIGINAL PARA SACAR TODOS LOS PAGOS PAUSADOS EN EL SISTEMA
        //return $this->db->query("SELECT  IF(facturas.uuid IS null, 'NO', facturas.nombre_archivo) AS tienefac ,pago_generado.estatus_pago, IF(solpagos.folio NOT IN ('NA'),solpagos.folio, SUBSTRING(facturas.uuid, - 5, 5)) AS folio, solpagos.moneda, SUBSTRING(facturas.uuid, -5, 5) AS folifis, solpagos.metoPago, director.nombredir, solpagos.moneda, capturista.nombre_completo, solpagos.caja_chica, solpagos.idsolicitud, solpagos.justificacion, solpagos.nomdepto, proveedores.nombre, solpagos.cantidad, empresas.abrev, autpagos.estatus as estatus_def,  DATE_FORMAT(solpagos.fechaCreacion, '%d/%m/%Y') AS feccrea, DATE_FORMAT(solpagos.fecha_autorizacion, '%d/%b/%Y') AS fecha_autorizacion, DATE_FORMAT(solpagos.fecelab, '%d/%m/%Y') AS fecelab, etapas.nombre AS etapa, IFNULL(pago_aut.autorizado, 0) AS autorizado,   IFNULL(liquidado.pagado, 0) AS pagado,   DATE_FORMAT(autpagos.fechaDis, '%d/%b/%Y') as fechaDis2 ,DATE_FORMAT(solpagos.fecha_autorizacion, '%d/%b/%Y') AS fech_auto  FROM solpagos LEFT JOIN ( SELECT *, autpagos.estatus AS estatus_pago, MAX(autpagos.fecreg) FROM autpagos GROUP BY autpagos.idsolicitud ) AS pago_generado ON pago_generado.idsolicitud = solpagos.idsolicitud INNER JOIN ( SELECT usuarios.idusuario, CONCAT( usuarios.nombres,' ', usuarios.apellidos ) AS nombre_completo FROM usuarios ) AS capturista ON capturista.idusuario = solpagos.idusuario LEFT JOIN (SELECT autpagos.idsolicitud, SUM( autpagos.cantidad ) AS pagado FROM autpagos GROUP BY autpagos.idsolicitud) AS liquidado ON solpagos.idsolicitud = liquidado.idsolicitud LEFT JOIN ( SELECT CONCAT( usuarios.nombres,' ',usuarios.apellidos ) AS nombredir, usuarios.idusuario FROM usuarios ) AS director ON director.idusuario = solpagos.idResponsable  INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor INNER JOIN etapas ON etapas.idetapa = solpagos.idetapa LEFT JOIN (SELECT *, MIN(facturas.feccrea) FROM facturas WHERE facturas.tipo_factura IN ( 1, 3 ) GROUP BY facturas.idsolicitud) AS facturas ON facturas.idsolicitud = solpagos.idsolicitud LEFT JOIN ( SELECT autpagos.idsolicitud, SUM(autpagos.cantidad) AS autorizado FROM autpagos  WHERE autpagos.estatus NOT IN (12, 2)  GROUP BY autpagos.idsolicitud ) AS pago_aut ON pago_aut.idsolicitud = solpagos.idsolicitud  INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa LEFT JOIN ( SELECT *, MAX(autpagos.fecreg) FROM autpagos GROUP BY autpagos.idsolicitud ) AS autpagos ON autpagos.idsolicitud = solpagos.idsolicitud WHERE solpagos.idetapa IN ( 42, 45, 47, 49 ) ORDER BY solpagos.idetapa, solpagos.fechaCreacion DESC");
        return $this->db->query("SELECT polizas_provision.idprovision,
            autpagos.estatus_pago,
            solpagos.idetapa,
            solpagos.folio,
            solpagos.moneda,
            facturas.tipo_factura,
            IFNULL( facturas.uuid, 'NO' ) AS tienefac,
            SUBSTRING(facturas.uuid, - 5, 5) AS folifis,
            solpagos.metoPago,
            director.nombredir,
            capturista.nombre_completo,
            solpagos.caja_chica,
            solpagos.idsolicitud,
            solpagos.justificacion,
            solpagos.nomdepto,
            proveedores.nombre,
            solpagos.cantidad,
            empresas.abrev,
            solpagos.fechaCreacion AS feccrea,
            solpagos.fecha_autorizacion AS fecha_autorizacion,
            solpagos.fecelab AS fecelab,
            etapas.nombre AS etapa,
            IFNULL( autpagos.pagado, 0 ) AS pagado,
            autpagos.fechaDis AS fechaDis2,
            solpagos.fecha_autorizacion AS fech_auto
            FROM solpagos
            LEFT JOIN ( SELECT  autpagos.idsolicitud, MAX( autpagos.fechaDis ) fechaDis, SUM( autpagos.cantidad ) pagado, MIN( autpagos.estatus ) estatus_pago, MAX( autpagos.fecreg ) upago FROM autpagos GROUP BY autpagos.idsolicitud ) AS autpagos ON autpagos.idsolicitud = solpagos.idsolicitud
            INNER JOIN (SELECT  usuarios.idusuario, CONCAT(usuarios.nombres, ' ', usuarios.apellidos) AS nombre_completo FROM usuarios) AS capturista ON capturista.idusuario = solpagos.idusuario
            INNER JOIN ( SELECT CONCAT(usuarios.nombres, ' ', usuarios.apellidos) AS nombredir, usuarios.idusuario FROM usuarios ) AS director ON director.idusuario = solpagos.idResponsable
            INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor
            INNER JOIN etapas ON etapas.idetapa = solpagos.idetapa
            LEFT JOIN ( SELECT  *, MIN(facturas.feccrea) FROM facturas WHERE facturas.tipo_factura IN (1 , 3) GROUP BY facturas.idsolicitud ) AS facturas ON facturas.idsolicitud = solpagos.idsolicitud
            INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa
            LEFT JOIN ( SELECT  polizas_provision.idprovision, polizas_provision.idsolicitud, MIN( polizas_provision.fecreg ) FROM polizas_provision GROUP BY polizas_provision.idsolicitud) AS polizas_provision ON polizas_provision.idsolicitud = solpagos.idsolicitud
        WHERE
            solpagos.idetapa IN ( 42, 45, 47, 49 )
        ORDER BY solpagos.idetapa , solpagos.fechaCreacion DESC");
    }


    function ChequesCancelados() {
          return $this->db->query("( SELECT  DISTINCT
          autpagos.idpago,
          solpagos.idsolicitud,
          historial_cheques.numCan,
          historial_cheques.numRem,
          IFNULL(solpagos.nom_prov,
                  responsable.nom_responsable) AS nombre,
          IFNULL(autpagos.cantidad,
                  autpagos_caja_chica.cantidad) AS cantidad,
                  historial_cheques.fecha_creacion fecha_cancelacion_f,
          IFNULL(empresas.abrev, empresascc.abrev) AS abrev,
          historial_cheques.observaciones,
          autpagos.referencia,
          autpagos.estatus,
          historial_cheques.tipo
      FROM
          ( SELECT * FROM historial_cheques WHERE historial_cheques.idautpago IS NOT NULL ) historial_cheques
              LEFT JOIN
          autpagos ON historial_cheques.idautpago = autpagos.idpago
              AND historial_cheques.tipo = 1
              LEFT JOIN
          autpagos_caja_chica ON autpagos_caja_chica.idpago = historial_cheques.idautpago
              AND historial_cheques.tipo = 2
              LEFT JOIN
          (SELECT 
              solpagos.idsolicitud,
                  solpagos.nomdepto,
                  proveedores.nombre AS nom_prov
          FROM
              solpagos
          INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor) AS solpagos ON solpagos.idsolicitud = autpagos.idsolicitud
              LEFT JOIN
          (SELECT 
              usuarios.idusuario,
                  CONCAT(usuarios.nombres, ' ', usuarios.apellidos) AS nom_responsable
          FROM
              usuarios) AS responsable ON autpagos_caja_chica.idResponsable = responsable.idusuario
              LEFT JOIN
          (SELECT 
              solpagos.idsolicitud, empresas.abrev
          FROM
              empresas
          INNER JOIN solpagos ON solpagos.idEmpresa = empresas.idempresa) AS empresas ON solpagos.idsolicitud = empresas.idsolicitud
              LEFT JOIN
          (SELECT 
              empresas.idempresa, empresas.abrev
          FROM
              empresas) AS empresascc ON autpagos_caja_chica.idEmpresa = empresascc.idempresa 
            
            ) UNION (
                SELECT 
                'NA' idpago, 
                'NA' idsolicitud, 
                numCan, 
                numRem, 
                'NA' nombre,
                cantidad, 
                historial_cheques.fecha_creacion fecha_cancelacion_f, 
                empresas.abrev, 
                historial_cheques.observaciones, 
                'NA' referencia,
                NULL estatus, 
                historial_cheques.tipo
                FROM historial_cheques INNER JOIN empresas ON empresas.idempresa = historial_cheques.idempresa WHERE idautpago IS NULL
            )
      ORDER BY fecha_cancelacion_f DESC"); 
      
    }

    function Chequesactivos() {
        return $this->db->query("(SELECT solpagos.programado, solpagos.intereses, autpagos.idpago,
            proveedores.nombre AS responsable,
            IFNULL( autpagos.fechaOpe, autpagos.fechaDis ) fecha_operacion,
            autpagos.cantidad,
            autpagos.referencia,
            '1' AS bd,
            empresas.abrev,
            autpagos.idsolicitud,
            autpagos.estatus
        FROM
            autpagos
                INNER JOIN
            (SELECT 
                solpagos.idsolicitud, proveedores.nombre
            FROM
                proveedores
            INNER JOIN solpagos ON solpagos.idProveedor = proveedores.idproveedor) AS proveedores ON proveedores.idsolicitud = autpagos.idsolicitud
                INNER JOIN
            (SELECT 
                solpagos.idsolicitud, empresas.abrev
            FROM
                empresas
            INNER JOIN solpagos ON solpagos.idEmpresa = empresas.idempresa) AS empresas ON autpagos.idsolicitud = empresas.idsolicitud
                LEFT JOIN
            obssols ON autpagos.idsolicitud = obssols.idsolicitud
                
                INNER JOIN
            solpagos ON solpagos.idsolicitud = autpagos.idsolicitud
        WHERE
            (autpagos.tipoPago IN ('ECHQ', 'EFEC')
                OR solpagos.metoPago IN ('ECHQ', 'EFEC'))
                AND autpagos.estatus IN (14, 15)
        GROUP BY idpago ) UNION (SELECT 0 as programado, 0 as intereses,
            autpagos_caja_chica.idpago,
            responsable.responsable,
            IFNULL( autpagos_caja_chica.fechaOpe, autpagos_caja_chica.fechaDis ) fecha_operacion,
            autpagos_caja_chica.cantidad,
            autpagos_caja_chica.referencia,
            '2' AS bd,
            empresas.abrev,
            autpagos_caja_chica.idsolicitud,
            autpagos_caja_chica.estatus
        FROM
            autpagos_caja_chica
                INNER JOIN
            (SELECT 
                usuarios.idusuario,
                    CONCAT(usuarios.nombres, ' ', usuarios.apellidos) AS responsable
            FROM
                usuarios) AS responsable ON responsable.idusuario = autpagos_caja_chica.idResponsable
                INNER JOIN
            empresas ON autpagos_caja_chica.idEmpresa = empresas.idempresa
                LEFT JOIN
            obssols ON autpagos_caja_chica.idsolicitud = obssols.idsolicitud
                
        WHERE
            autpagos_caja_chica.tipoPago = 'ECHQ'
                AND autpagos_caja_chica.estatus IN (13, 14, 15)
        GROUP BY idpago
        ORDER BY fecha_operacion DESC)");
    }


    function ChequesCobrados() {
        return $this->db->query("(SELECT autpagos.idsolicitud, proveedores.nombre AS responsable, autpagos.fecha_cobro AS fecha, IFNULL((autpagos.fechaOpe), '---') 
AS fecha_operacion, autpagos.cantidad, autpagos.referencia, '1' AS bd, empresas.abrev, IFNULL(autpagos.fecha_cobro, '---')
 AS fecha_cobro , solpagos.programado, solpagos.intereses
 
 FROM ( SELECT * FROM autpagos WHERE idpago NOT IN ( SELECT idpago FROM autpagos WHERE autpagos.formaPago IN ( 'ECHQ', 'EFEC' ) GROUP BY autpagos.referencia HAVING COUNT( referencia ) > 1 ) ) autpagos 
 INNER JOIN (SELECT solpagos.idsolicitud, proveedores.nombre, solpagos.metoPago, solpagos.programado, solpagos.intereses FROM proveedores INNER JOIN solpagos ON solpagos.idProveedor = proveedores.idproveedor) AS proveedores ON proveedores.idsolicitud = autpagos.idsolicitud 

 INNER JOIN (SELECT solpagos.idsolicitud, empresas.abrev FROM empresas INNER JOIN solpagos ON solpagos.idEmpresa = empresas.idempresa) AS empresas 
 ON autpagos.idsolicitud = empresas.idsolicitud 
 
 INNER JOIN solpagos on autpagos.idsolicitud  = solpagos.idsolicitud
 WHERE ( autpagos.formaPago IN ( 'ECHQ', 'EFEC' ) OR proveedores.metoPago IN ( 'EFEC', 'ECHQ' ) ) AND autpagos.estatus = 16)

            UNION (
                SELECT
                autpagos_caja_chica.idpago,
                responsable.responsable,
                autpagos_caja_chica.fecha_cobro AS fecha,
                IFNULL(autpagos_caja_chica.fechaDis, '---') AS fecha_operacion,
                autpagos_caja_chica.cantidad,
                autpagos_caja_chica.referencia,
                '2' AS bd,
                empresas.abrev,
                autpagos_caja_chica.fecha_cobro fecha_cobro,
                0 as programado, 
                0 as intereses
            FROM
                autpagos_caja_chica
                INNER JOIN (SELECT usuarios.idusuario, CONCAT(usuarios.nombres, ' ', usuarios.apellidos) AS responsable FROM usuarios) AS responsable ON responsable.idusuario = autpagos_caja_chica.idResponsable
                INNER JOIN empresas ON autpagos_caja_chica.idEmpresa = empresas.idempresa
            WHERE
                ( autpagos_caja_chica.formaPago IN ( 'ECHQ', 'EFEC' )
                    OR autpagos_caja_chica.tipoPago IN ( 'ECHQ', 'EFEC' ) )
                    AND autpagos_caja_chica.estatus = 16 
            )UNION (
                SELECT 
                'NA' idpago,
                'MULTIPLES PAGOS EN EFEC' responsable,
                IFNULL(MAX(autpagos.fecha_cobro), MAX(autpagos.fechaOpe)) fecha,
                MAX(autpagos.fechaDis) fecha_operacion,
                SUM(solpagos.cantidad) cantidad, 
                autpagos.referencia ,
                '1' bd,
                empresas.abrev, 
                IFNULL(MAX(autpagos.fecha_cobro), MAX(autpagos.fechaOpe) ) fecha_cobro,
                0 programado,
                0 intereses
                FROM solpagos 
                INNER JOIN empresas ON empresas.idempresa = solpagos.idempresa
                INNER JOIN ( SELECT * FROM autpagos WHERE idpago IN ( SELECT idpago FROM autpagos WHERE autpagos.referencia IS NOT NULL AND  autpagos.formaPago IN ( 'ECHQ', 'EFEC' ) GROUP BY autpagos.referencia HAVING COUNT( referencia ) > 1 ) ) autpagos ON autpagos.idsolicitud = solpagos.idsolicitud 
                WHERE solpagos.metoPago = 'EFEC' AND autpagos.referencia IS NOT NULL AND concat('', autpagos.referencia * 1) = autpagos.referencia AND autpagos.estatus = 16
                GROUP BY solpagos.idEmpresa, autpagos.referencia
            ) ORDER BY fecha DESC;");
    }
    
    
    function allcheques() {
        return $this->db->query("SELECT * FROM ( (SELECT 
            solpagos.programado, 
            solpagos.intereses, 
            autpagos.idsolicitud idpago, 
            proveedores.nombre AS responsable, 
            autpagos.cantidad, 
            IFNULL(autpagos.fecha_cobro, 'SIN COBRAR') AS fecha_operacion, 
            IFNULL(autpagos.referencia, 'SIN DEFINIR') AS referencia, 
            empresas.abrev, '1' AS bd, 
            ( CASE WHEN autpagos.estatus = '16' THEN 'COBRADO' WHEN autpagos.estatus = '14' THEN 'ENTREGADO' ELSE 'POR ENTREGAR' END ) AS estatus, 
            autpagos.fecha_cobro AS orderby 
            FROM ( SELECT cantidad, idsolicitud, fecha_cobro, referencia, estatus, formaPago, tipoPago FROM autpagos
            LEFT JOIN ( SELECT idpago FROM autpagos WHERE autpagos.formaPago IN ( 'ECHQ', 'EFEC' ) GROUP BY autpagos.referencia HAVING COUNT( referencia ) > 1 ) autpagos2 ON autpagos.idpago = autpagos2.idpago
            WHERE autpagos2.idpago IS NULL AND ( autpagos.formaPago IN ( 'ECHQ', 'EFEC' ) OR autpagos.tipoPago IN ( 'ECHQ', 'EFEC' ) ) AND autpagos.estatus IN (13, 14, 15, 16) ) autpagos 
            INNER JOIN ( SELECT solpagos.idsolicitud, proveedores.nombre, solpagos.metoPago FROM proveedores INNER JOIN solpagos ON solpagos.idProveedor = proveedores.idproveedor ) AS proveedores ON proveedores.idsolicitud = autpagos.idsolicitud 
            INNER JOIN ( SELECT solpagos.idsolicitud, empresas.abrev FROM empresas INNER JOIN solpagos ON solpagos.idEmpresa = empresas.idempresa) AS empresas ON autpagos.idsolicitud = empresas.idsolicitud  
            INNER JOIN solpagos ON solpagos.idsolicitud = autpagos.idsolicitud 
        ) UNION (
            SELECT 
            0 AS programado,  
            0 AS intereses, 
            IFNULL( autpagos.idsolicitud, 'NA' ) AS idpago, 
            IFNULL(solpagos.nom_prov, responsable.nom_responsable) AS responsable, 
            IFNULL(autpagos.cantidad, 0) AS cantidad, 
            historial_cheques.fecha_creacion fecha_operacion, 
            historial_cheques.numCan AS referencia, 
            empresas.abrev, 
            '1' AS bd, 
            'CANCELADO' AS estatus, 
            historial_cheques.fecha_creacion AS orderby 
            FROM historial_cheques LEFT JOIN autpagos ON historial_cheques.idautpago = autpagos.idpago AND historial_cheques.tipo = 1 LEFT JOIN (SELECT solpagos.idsolicitud, solpagos.nomdepto, proveedores.nombre AS nom_prov FROM solpagos INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor) AS solpagos ON solpagos.idsolicitud = autpagos.idsolicitud LEFT JOIN (SELECT usuarios.idusuario, CONCAT(usuarios.nombres, ' ', usuarios.apellidos) AS nom_responsable FROM usuarios) AS responsable ON autpagos.idrealiza = responsable.idusuario LEFT JOIN (SELECT solpagos.idsolicitud, empresas.abrev FROM empresas INNER JOIN solpagos ON solpagos.idEmpresa = empresas.idempresa) AS empresas ON solpagos.idsolicitud = empresas.idsolicitud WHERE autpagos.cantidad > 0 AND idautpago IS NOT NULL
        ) UNION (
            SELECT 
            0 AS programado, 
            0 AS intereses, 
            autpagos_caja_chica.idpago, 
            responsable.responsable, 
            autpagos_caja_chica.cantidad, 
            IF(autpagos_caja_chica.fecha_cobro != '0000-00-00' AND autpagos_caja_chica.fecha_cobro IS NOT NULL, autpagos_caja_chica.fecha_cobro, 'SIN COBRAR') AS fecha_operacion, 
            autpagos_caja_chica.referencia, 
            empresas.abrev, 
            '2' AS bd, 
            IF(autpagos_caja_chica.estatus = '16', 'COBRADO', 'ACTIVO') AS estatus, 
            fecha_cobro AS orderby 
            FROM autpagos_caja_chica INNER JOIN (SELECT usuarios.idusuario, CONCAT(usuarios.nombres, ' ', usuarios.apellidos) AS responsable FROM usuarios) AS responsable ON responsable.idusuario = autpagos_caja_chica.idResponsable INNER JOIN empresas ON autpagos_caja_chica.idEmpresa = empresas.idempresa 
            WHERE ( autpagos_caja_chica.tipoPago IN ( 'ECHQ' ) AND autpagos_caja_chica.estatus IN ( 13, 15, 16 ) ) OR (  autpagos_caja_chica.tipoPago IN ( 'EFEC' ) AND autpagos_caja_chica.estatus IN ( 16 ) )
        )UNION (
            SELECT 
            0 programado,
            0 intereses,
            'NA' idpago, 
            'NA' responsable, 
            cantidad, 
            historial_cheques.fecha_creacion fecha_operacion,
            numCan referencia, 
            empresas.abrev, 
            '1' AS bd, 
            'CANCELADO' estatus,
            historial_cheques.fecha_creacion AS orderby
            FROM historial_cheques INNER JOIN empresas ON empresas.idempresa = historial_cheques.idempresa WHERE idautpago IS NULL
        ) ) todos_cheques ORDER BY todos_cheques.orderby DESC");
    }
    
    function cajas_chicas_pagadas(){

        switch( $this->session->userdata("inicio_sesion")['rol'] ){
            case 'AS':
            case 'DA':
                return $this->db->query("SELECT NULL AS solicitudes, autpagos_caja_chica.idpago, autpagos_caja_chica.idResponsable, autpagos_caja_chica.idsolicitud, autpagos_caja_chica.tipoPago, autpagos_caja_chica.referencia, autpagos_caja_chica.fecha_cobro AS fecha_cobro, CONCAT(usuarios.nombres, ' ', usuarios.apellidos) AS responsable, empresas.abrev, autpagos_caja_chica.fecreg AS fecha, autpagos_caja_chica.cantidad, autpagos_caja_chica.nomdepto FROM autpagos_caja_chica INNER JOIN empresas ON autpagos_caja_chica.idEmpresa = empresas.idempresa INNER JOIN usuarios ON usuarios.idusuario = autpagos_caja_chica.idResponsable WHERE autpagos_caja_chica.nomdepto = '".$this->session->userdata("inicio_sesion")['depto']."' AND autpagos_caja_chica.estatus IN ( 14, 15, 16) ORDER BY autpagos_caja_chica.fecreg DESC");
                break;
            case 'CJ':
                return $this->db->query("SELECT NULL AS solicitudes, autpagos_caja_chica.idpago, autpagos_caja_chica.idResponsable, autpagos_caja_chica.idsolicitud, autpagos_caja_chica.tipoPago, autpagos_caja_chica.referencia, autpagos_caja_chica.fecha_cobro AS fecha_cobro, CONCAT(usuarios.nombres, ' ', usuarios.apellidos) AS responsable, empresas.abrev, autpagos_caja_chica.fecreg AS fecha, autpagos_caja_chica.cantidad, autpagos_caja_chica.nomdepto FROM autpagos_caja_chica INNER JOIN empresas ON autpagos_caja_chica.idEmpresa = empresas.idempresa INNER JOIN usuarios ON usuarios.idusuario = autpagos_caja_chica.idResponsable WHERE autpagos_caja_chica.nomdepto != 'TARJETA DE CREDITO' AND autpagos_caja_chica.idResponsable = '".$this->session->userdata("inicio_sesion")['id']."' AND autpagos_caja_chica.estatus IN ( 14, 15, 16) ORDER BY autpagos_caja_chica.fecreg DESC");
                break;
            case 'CA':
            case 'FP':
                return $this->db->query("SELECT NULL AS solicitudes, autpagos_caja_chica.idpago, autpagos_caja_chica.idResponsable, autpagos_caja_chica.idsolicitud, autpagos_caja_chica.tipoPago, autpagos_caja_chica.referencia, autpagos_caja_chica.fecha_cobro AS fecha_cobro, CONCAT(usuarios.nombres, ' ', usuarios.apellidos) AS responsable, empresas.abrev, autpagos_caja_chica.fecreg AS fecha, autpagos_caja_chica.cantidad, autpagos_caja_chica.nomdepto FROM autpagos_caja_chica INNER JOIN ( SELECT solpagos.idsolicitud FROM solpagos WHERE solpagos.idusuario = '".$this->session->userdata("inicio_sesion")['id']."' ) solpagos ON FIND_IN_SET( solpagos.idsolicitud, autpagos_caja_chica.idsolicitud) INNER JOIN empresas ON autpagos_caja_chica.idEmpresa = empresas.idempresa INNER JOIN usuarios ON usuarios.idusuario = autpagos_caja_chica.idResponsable WHERE autpagos_caja_chica.nomdepto != 'TARJETA DE CREDITO' AND autpagos_caja_chica.estatus IN ( 14, 15, 16) GROUP BY autpagos_caja_chica.idpago ORDER BY autpagos_caja_chica.fecreg DESC");
                break;
            default:
                return $this->db->query("SELECT NULL AS solicitudes, autpagos_caja_chica.idpago, autpagos_caja_chica.estatus, autpagos_caja_chica.idResponsable, autpagos_caja_chica.idsolicitud, autpagos_caja_chica.tipoPago, autpagos_caja_chica.referencia, autpagos_caja_chica.fechaDis AS fecha_cobro, CONCAT(usuarios.nombres, ' ', usuarios.apellidos) AS responsable, empresas.abrev, autpagos_caja_chica.fecreg AS fecha, autpagos_caja_chica.cantidad, autpagos_caja_chica.nomdepto FROM autpagos_caja_chica INNER JOIN empresas ON autpagos_caja_chica.idEmpresa = empresas.idempresa INNER JOIN usuarios ON usuarios.idusuario = autpagos_caja_chica.idResponsable WHERE autpagos_caja_chica.nomdepto != 'TARJETA DE CREDITO' AND autpagos_caja_chica.estatus IN ( 5, 14, 15, 16 ) ORDER BY autpagos_caja_chica.fecreg  DESC");
                break;
        }
    }

    function caja_chicas_cerradas(){
        return $this->db->query("SELECT 
            NULL AS solicitudes, 
            'NA' idpago, 
            31 estatus, 
            autpagos_caja_chica.idResponsable, 
            autpagos_caja_chica.idsolicitud, 
            'NA' tipoPago, 
            '' referencia, 
            NULL fecha_cobro, 
            usuarios.nombre_completo responsable, 
            empresas.abrev, 
            autpagos_caja_chica.fecelab fecha, 
            autpagos_caja_chica.cantidad, 
            autpagos_caja_chica.nomdepto 
        FROM  (
            SELECT GROUP_CONCAT(s.idsolicitud) idsolicitud, s.idResponsable, s.idEmpresa, DATE_FORMAT(s.fecha_autorizacion, '%Y-%m-%d') fecelab, SUM(cantidad) cantidad, nomdepto
            FROM solpagos s
            WHERE s.idetapa = 31
            GROUP BY DATE_FORMAT(s.fecha_autorizacion, '%d-%m-%Y'), s.idResponsable, s.idEmpresa
        ) autpagos_caja_chica 
        INNER JOIN empresas ON autpagos_caja_chica.idEmpresa = empresas.idempresa 
        INNER JOIN listado_usuarios usuarios ON usuarios.idusuario = autpagos_caja_chica.idResponsable 
        WHERE autpagos_caja_chica.nomdepto != 'TARJETA DE CREDITO'
        ORDER BY autpagos_caja_chica.fecelab DESC");
    }

    function tdc_pagadas(){
        switch( $this->session->userdata("inicio_sesion")['rol'] ){
            case 'AS':
            case 'DA':
                return $this->db->query("SELECT NULL AS solicitudes, autpagos_caja_chica.idpago, autpagos_caja_chica.idResponsable, autpagos_caja_chica.idsolicitud, autpagos_caja_chica.tipoPago, autpagos_caja_chica.referencia, autpagos_caja_chica.fecha_cobro AS fecha_cobro, CONCAT(usuarios.nombres, ' ', usuarios.apellidos) AS responsable, empresas.abrev, autpagos_caja_chica.fecreg AS fecha, autpagos_caja_chica.cantidad, autpagos_caja_chica.nomdepto FROM autpagos_caja_chica INNER JOIN empresas ON autpagos_caja_chica.idEmpresa = empresas.idempresa INNER JOIN usuarios ON usuarios.idusuario = autpagos_caja_chica.idResponsable WHERE autpagos_caja_chica.nomdepto = '".$this->session->userdata("inicio_sesion")['depto']."' AND autpagos_caja_chica.estatus IN ( 14, 15, 16) ORDER BY autpagos_caja_chica.fecreg DESC");
                break;
            case 'CJ':
                return $this->db->query("SELECT NULL AS solicitudes, autpagos_caja_chica.idpago, autpagos_caja_chica.idResponsable, autpagos_caja_chica.idsolicitud, autpagos_caja_chica.tipoPago, autpagos_caja_chica.referencia, autpagos_caja_chica.fecha_cobro AS fecha_cobro, CONCAT(usuarios.nombres, ' ', usuarios.apellidos) AS responsable, empresas.abrev, autpagos_caja_chica.fecreg AS fecha, autpagos_caja_chica.cantidad, autpagos_caja_chica.nomdepto FROM autpagos_caja_chica INNER JOIN empresas ON autpagos_caja_chica.idEmpresa = empresas.idempresa INNER JOIN usuarios ON usuarios.idusuario = autpagos_caja_chica.idResponsable WHERE autpagos_caja_chica.nomdepto = 'TARJETA DE CREDITO' AND autpagos_caja_chica.idResponsable = '".$this->session->userdata("inicio_sesion")['id']."' AND autpagos_caja_chica.estatus IN ( 14, 15, 16) ORDER BY autpagos_caja_chica.fecreg DESC");
                break;
            case 'CA':
            case 'FP':
                return $this->db->query("SELECT NULL AS solicitudes, autpagos_caja_chica.idpago, autpagos_caja_chica.idResponsable, autpagos_caja_chica.idsolicitud, autpagos_caja_chica.tipoPago, autpagos_caja_chica.referencia, autpagos_caja_chica.fecha_cobro AS fecha_cobro, CONCAT(usuarios.nombres, ' ', usuarios.apellidos) AS responsable, empresas.abrev, autpagos_caja_chica.fecreg AS fecha, autpagos_caja_chica.cantidad, autpagos_caja_chica.nomdepto FROM autpagos_caja_chica INNER JOIN ( SELECT solpagos.idsolicitud FROM solpagos WHERE solpagos.idusuario = '".$this->session->userdata("inicio_sesion")['id']."' ) solpagos ON FIND_IN_SET( solpagos.idsolicitud, autpagos_caja_chica.idsolicitud) INNER JOIN empresas ON autpagos_caja_chica.idEmpresa = empresas.idempresa INNER JOIN usuarios ON usuarios.idusuario = autpagos_caja_chica.idResponsable WHERE autpagos_caja_chica.nomdepto = 'TARJETA DE CREDITO' AND autpagos_caja_chica.estatus IN ( 14, 15, 16) GROUP BY autpagos_caja_chica.idpago ORDER BY autpagos_caja_chica.fecreg DESC");
                break;
            default:
                return $this->db->query("SELECT 
                    autpagos_caja_chica.idpago, 
                    autpagos_caja_chica.estatus, 
                    autpagos_caja_chica.idResponsable, 
                    autpagos_caja_chica.idsolicitud, 
                    autpagos_caja_chica.tipoPago, 
                    autpagos_caja_chica.referencia, 
                    autpagos_caja_chica.fechaDis AS fecha_cobro, 
                    p.nombre responsable, 
                    empresas.abrev, autpagos_caja_chica.fecreg AS fecha, 
                    autpagos_caja_chica.cantidad, 
                    autpagos_caja_chica.nomdepto,
                    null solicitudes
                FROM autpagos_caja_chica 
                INNER JOIN empresas ON autpagos_caja_chica.idEmpresa = empresas.idempresa 
                INNER JOIN proveedores p ON p.idproveedor = autpagos_caja_chica.idResponsable 
                WHERE autpagos_caja_chica.nomdepto = 'TARJETA CREDITO' AND autpagos_caja_chica.estatus IN ( 5, 14, 15, 16 ) ORDER BY autpagos_caja_chica.fecreg  DESC");
                break;
        }
    }

    function cajas_chicas_pagadas1($fecha_ini, $fecha_fin, $dep, $res, $emp, $cant, $metopago, $fecha, $autorizacion){

        switch( $this->session->userdata("inicio_sesion")['rol'] ){
            case 'AS':
            case 'DA':
                return $this->db->query("SELECT NULL AS solicitudes, autpagos_caja_chica.idResponsable, autpagos_caja_chica.idsolicitud, autpagos_caja_chica.tipoPago, autpagos_caja_chica.referencia, DATE_FORMAT( autpagos_caja_chica.fecha_cobro, '%d/%b/%Y') AS fecha_cobro, CONCAT(usuarios.nombres, ' ', usuarios.apellidos) AS responsable, empresas.abrev, DATE_FORMAT( autpagos_caja_chica.fecreg ,'%d/%b/%Y') AS fecha, autpagos_caja_chica.cantidad, autpagos_caja_chica.nomdepto FROM autpagos_caja_chica INNER JOIN empresas ON autpagos_caja_chica.idEmpresa = empresas.idempresa INNER JOIN usuarios ON usuarios.idusuario = autpagos_caja_chica.idResponsable WHERE autpagos_caja_chica.nomdepto = '".$this->session->userdata("inicio_sesion")['depto']."' AND autpagos_caja_chica.estatus IN ( 14, 15, 16)  AND DATE_FORMAT( autpagos_caja_chica.fechaDis , '%d/%b/%Y') LIKE '%$autorizacion%' AND DATE_FORMAT( autpagos_caja_chica.fecreg ,'%d/%b/%Y') LIKE '%$fecha%' AND autpagos_caja_chica.tipoPago LIKE '%$metopago%'  AND autpagos_caja_chica.nomdepto LIKE '%$dep%' AND empresas.abrev LIKE '%$emp%' AND autpagos_caja_chica.cantidad LIKE '%$cant%' AND CONCAT(usuarios.nombres, ' ', usuarios.apellidos) LIKE '%$res%' AND autpagos_caja_chica.fecreg BETWEEN '$fecha_ini' AND '$fecha_fin' ORDER BY autpagos_caja_chica.idResponsable, autpagos_caja_chica.fecreg DESC ");
                break;
            case 'CJ':
                return $this->db->query("SELECT NULL AS solicitudes, autpagos_caja_chica.idResponsable, autpagos_caja_chica.idsolicitud, autpagos_caja_chica.tipoPago, autpagos_caja_chica.referencia, DATE_FORMAT( autpagos_caja_chica.fecha_cobro, '%d/%b/%Y') AS fecha_cobro, CONCAT(usuarios.nombres, ' ', usuarios.apellidos) AS responsable, empresas.abrev, DATE_FORMAT( autpagos_caja_chica.fecreg ,'%d/%b/%Y') AS fecha, autpagos_caja_chica.cantidad, autpagos_caja_chica.nomdepto FROM autpagos_caja_chica INNER JOIN empresas ON autpagos_caja_chica.idEmpresa = empresas.idempresa INNER JOIN usuarios ON usuarios.idusuario = autpagos_caja_chica.idResponsable WHERE autpagos_caja_chica.idResponsable = '".$this->session->userdata("inicio_sesion")['id']."' AND autpagos_caja_chica.estatus IN ( 14, 15, 16)  AND DATE_FORMAT( autpagos_caja_chica.fechaDis , '%d/%b/%Y') LIKE '%$autorizacion%' AND DATE_FORMAT( autpagos_caja_chica.fecreg ,'%d/%b/%Y') LIKE '%$fecha%' AND autpagos_caja_chica.tipoPago LIKE '%$metopago%'  AND autpagos_caja_chica.nomdepto LIKE '%$dep%' AND empresas.abrev LIKE '%$emp%' AND autpagos_caja_chica.cantidad LIKE '%$cant%' AND CONCAT(usuarios.nombres, ' ', usuarios.apellidos) LIKE '%$res%' AND autpagos_caja_chica.fecreg BETWEEN '$fecha_ini' AND '$fecha_fin' ORDER BY autpagos_caja_chica.idResponsable, autpagos_caja_chica.fecreg DESC ");
                break;
            case 'CA':
                return $this->db->query("SELECT NULL AS solicitudes, autpagos_caja_chica.idResponsable, autpagos_caja_chica.idsolicitud, autpagos_caja_chica.tipoPago, autpagos_caja_chica.referencia, DATE_FORMAT( autpagos_caja_chica.fecha_cobro, '%d/%b/%Y') AS fecha_cobro, CONCAT(usuarios.nombres, ' ', usuarios.apellidos) AS responsable, empresas.abrev, DATE_FORMAT( autpagos_caja_chica.fecreg ,'%d/%b/%Y') AS fecha, autpagos_caja_chica.cantidad, autpagos_caja_chica.nomdepto FROM autpagos_caja_chica INNER JOIN ( SELECT solpagos.idsolicitud FROM solpagos WHERE solpagos.idusuario = '".$this->session->userdata("inicio_sesion")['id']."' ) solpagos ON FIND_IN_SET( solpagos.idsolicitud, autpagos_caja_chica.idsolicitud) INNER JOIN empresas ON autpagos_caja_chica.idEmpresa = empresas.idempresa INNER JOIN usuarios ON usuarios.idusuario = autpagos_caja_chica.idResponsable WHERE autpagos_caja_chica.estatus IN ( 14, 15, 16)  AND DATE_FORMAT( autpagos_caja_chica.fechaDis , '%d/%b/%Y') LIKE '%$autorizacion%' AND DATE_FORMAT( autpagos_caja_chica.fecreg ,'%d/%b/%Y') LIKE '%$fecha%' AND autpagos_caja_chica.tipoPago LIKE '%$metopago%'  AND empresas.abrev LIKE '%$emp%' AND autpagos_caja_chica.cantidad LIKE '%$cant%' AND autpagos_caja_chica.nomdepto LIKE '%$dep%' AND CONCAT(usuarios.nombres, ' ', usuarios.apellidos) LIKE '%$res%' AND autpagos_caja_chica.fecreg BETWEEN '$fecha_ini' AND '$fecha_fin' GROUP BY autpagos_caja_chica.idpago ORDER BY autpagos_caja_chica.idResponsable, autpagos_caja_chica.fecreg DESC");
                break;
            default:
                return $this->db->query("SELECT NULL AS solicitudes, autpagos_caja_chica.estatus, autpagos_caja_chica.idResponsable, autpagos_caja_chica.idsolicitud, autpagos_caja_chica.tipoPago, autpagos_caja_chica.referencia, DATE_FORMAT( autpagos_caja_chica.fechaDis , '%d/%b/%Y') AS fecha_cobro, CONCAT(usuarios.nombres, ' ', usuarios.apellidos) AS responsable, empresas.abrev, DATE_FORMAT( autpagos_caja_chica.fecreg ,'%d/%b/%Y') AS fecha, autpagos_caja_chica.cantidad, autpagos_caja_chica.nomdepto FROM autpagos_caja_chica INNER JOIN empresas ON autpagos_caja_chica.idEmpresa = empresas.idempresa INNER JOIN usuarios ON usuarios.idusuario = autpagos_caja_chica.idResponsable WHERE autpagos_caja_chica.estatus IN ( 14, 15, 16) AND DATE_FORMAT( autpagos_caja_chica.fechaDis , '%d/%b/%Y') LIKE '%$autorizacion%'  AND DATE_FORMAT( autpagos_caja_chica.fecreg ,'%d/%b/%Y') LIKE '%$fecha%' AND autpagos_caja_chica.tipoPago LIKE '%$metopago%' AND autpagos_caja_chica.nomdepto LIKE '%$dep%' AND CONCAT(usuarios.nombres, ' ', usuarios.apellidos) LIKE '%$res%' AND empresas.abrev LIKE '%$emp%' AND autpagos_caja_chica.cantidad LIKE '%$cant%' AND autpagos_caja_chica.fecreg BETWEEN '$fecha_ini' AND '$fecha_fin' ORDER BY autpagos_caja_chica.idResponsable, autpagos_caja_chica.fecreg DESC");
                break;
        }
    }

    function obtenerSolCerradas(){        
        return $this->db->query("SELECT group_concat(`s`.`idsolicitud`) AS `ID`, 
             CONCAT(u.nombres, ' ', u.apellidos) AS Responsable,emp.abrev,MAX(DATE_FORMAT(s.fecelab,'%d/%b/%Y')) AS FECHAFACP ,SUM(s.cantidad) AS Cantidad ,s.nomdepto AS Departamento,s.idEmpresa AS Empresa,
             s.idResponsable IDR 
             FROM  solpagos s INNER JOIN usuarios u ON (s.idResponsable =  u.idusuario)
             INNER JOIN empresas emp ON(emp.idEmpresa = s.idEmpresa)
             INNER JOIN proveedores p ON(s.idProveedor = p.idProveedor)
             WHERE s.caja_chica = 1 AND s.idetapa IN (31)
              GROUP BY s.idResponsable  ORDER BY s.fecelab;");

    }

    //LISTADO PERSONAL QUE HA AUTORIZADO PAGOS
    function personal_autorizaciones(){
        //return $this->db->query("SELECT a.idrealiza, SUM(a.cantidad) as cnt, CONCAT(u.nombres, ' ',u.apellidos) AS nombres, SUBSTR(a.fecreg, 1, 10) as fecreg FROM autpagos a INNER JOIN usuarios u on (u.idusuario = a.idrealiza) INNER JOIN solpagos s on (s.idsolicitud = a.idsolicitud) WHERE a.idrealiza is not null GROUP BY a.idrealiza, SUBSTR(a.fecreg, 1, 10) ORDER BY a.fecreg DESC");
        //return $this->db->query("SELECT idrealiza, SUM( cnt ) AS cnt, fecreg, CONCAT(usuarios.nombres, ' ', usuarios.apellidos) AS nombres FROM ( ( SELECT autpagos.idrealiza, SUM(autpagos.cantidad) AS cnt, SUBSTR(autpagos.fecreg, 1, 10) as fecreg FROM autpagos WHERE autpagos.fecreg != '0000-00-00 00:00:00' AND autpagos.idrealiza IS NOT NULL GROUP BY autpagos.idrealiza, SUBSTR(autpagos.fecreg, 1, 10) ) UNION ( SELECT autpagos_caja_chica.idrealiza, SUM(autpagos_caja_chica.cantidad) AS cnt, SUBSTR(autpagos_caja_chica.fecreg, 1, 10) as fecreg FROM autpagos_caja_chica WHERE autpagos_caja_chica.fecreg != '0000-00-00 00:00:00' AND autpagos_caja_chica.idrealiza IS NOT NULL GROUP BY autpagos_caja_chica.idrealiza, SUBSTR(autpagos_caja_chica.fecreg, 1, 10) ) ) AS autopagos INNER JOIN usuarios on usuarios.idusuario = autopagos.idrealiza GROUP BY idrealiza, fecreg ORDER BY fecreg DESC");
        /*
        return $this->db->query("SELECT idrealiza, SUM( cnt ) AS cnt, ( MAX(fecreg) ) fecreg, DATE_FORMAT( DATE_SUB( MAX(fecreg), INTERVAL 12 HOUR ), '%d/%m/%Y %H:%i:%s' ) upago, CONCAT(usuarios.nombres, ' ', usuarios.apellidos) AS nombres FROM ( ( 
            SELECT 
                autpagos.idrealiza,
                SUM(autpagos.cantidad) AS cnt,
                DATE_ADD(MAX(fecreg), INTERVAL 12 HOUR) fecreg
            FROM
                autpagos
            WHERE
                autpagos.fecreg != '0000-00-00 00:00:00'
                    AND autpagos.idrealiza IS NOT NULL
                    AND (autpagos.referencia NOT IN ( 'ABONO' )
                    OR autpagos.referencia IS NULL)
            GROUP BY autpagos.idrealiza , DATE_FORMAT(DATE_ADD(fecreg, INTERVAL 12 HOUR),
                    '%Y-%m-%d')
                    
             ) UNION (
             
            SELECT 
                autpagos_caja_chica.idrealiza,
                SUM(autpagos_caja_chica.cantidad) AS cnt,
                DATE_ADD( MAX(fecreg), INTERVAL 12 HOUR) fecreg
            FROM
                autpagos_caja_chica
            WHERE
                autpagos_caja_chica.fecreg != '0000-00-00 00:00:00'
                    AND autpagos_caja_chica.idrealiza IS NOT NULL
            GROUP BY autpagos_caja_chica.idrealiza , DATE_FORMAT(DATE_ADD(fecreg, INTERVAL 12 HOUR),
                    '%Y-%m-%d')
             
             ) ) AS autopagos INNER JOIN usuarios on usuarios.idusuario = autopagos.idrealiza GROUP BY idrealiza, DATE_FORMAT( fecreg, '%Y-%m-%d' ) ORDER BY fecreg DESC");
        */
        return $this->db->query("SELECT 
                    idrealiza,
                    SUM(cnt) AS cnt,
                    fecreg,
                    DATE_FORMAT( MAX( mfecreg ), '%d/%m/%Y %H:%i:%s' ) upago,
                    CONCAT(usuarios.nombres,
                            ' ',
                            usuarios.apellidos) AS nombres
                FROM
                    ((SELECT 
                        autpagos.idrealiza,
                            SUM(autpagos.cantidad) AS cnt,
                            SUBSTR(autpagos.fecreg, 1, 10) AS fecreg,
                            MAX(autpagos.fecreg) mfecreg
                    FROM
                        autpagos
                    WHERE
                        autpagos.fecreg != '0000-00-00 00:00:00'
                            AND autpagos.idrealiza IS NOT NULL
                    GROUP BY autpagos.idrealiza , SUBSTR(autpagos.fecreg, 1, 10)) UNION (SELECT 
                        autpagos_caja_chica.idrealiza,
                            SUM(autpagos_caja_chica.cantidad) AS cnt,
                            SUBSTR(autpagos_caja_chica.fecreg, 1, 10) AS fecreg,
                            MAX(autpagos_caja_chica.fecreg) mfecreg
                    FROM
                        autpagos_caja_chica
                    WHERE
                        autpagos_caja_chica.fecreg != '0000-00-00 00:00:00'
                            AND autpagos_caja_chica.idrealiza IS NOT NULL
                    GROUP BY autpagos_caja_chica.idrealiza , SUBSTR(autpagos_caja_chica.fecreg, 1, 10))) AS autopagos
                        INNER JOIN
                    usuarios ON usuarios.idusuario = autopagos.idrealiza
                GROUP BY idrealiza , fecreg
                ORDER BY fecreg DESC");
    }
    
    function get_solicitudes_auto( $idResponsable,$fecha_autorizacion ){
         return $this->db->query("SELECT empresas.abrev, solpagos.moneda, solpagos.cantidad, solpagos.proyecto, solpagos.justificacion, solpagos.nomdepto, proveedores.nombre FROM autpagos INNER JOIN solpagos ON solpagos.idsolicitud = autpagos.idsolicitud INNER JOIN usuarios ON usuarios.idusuario = autpagos.idrealiza INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa inner join proveedores on proveedores.idproveedor = solpagos.idProveedor WHERE autpagos.idrealiza='".$idResponsable."' and autpagos.fecreg like '".$fecha_autorizacion."%' ORDER BY empresas.abrev")->result_array();
    }

 

    function get_solicitudes_nuevas_caja_chica( /*$idsolicitudes,$idResponsable*/ ){
        /*
        if( in_array( $this->session->userdata("inicio_sesion")['rol'], array( 'AS', 'CA' ) )){
            return $this->db->query("SELECT solpagos.idusuario, solpagos.idsolicitud, solpagos.folio, solpagos.moneda, solpagos.justificacion, empresas.abrev, solpagos.nomdepto, proveedores.nombre AS nombre_proveedor, DATE_FORMAT( solpagos.fecelab, '%d/%m/%Y') AS fecelab, DATE_FORMAT( solpagos.fecelab, '%d/%m/%Y') AS fecautorizacion, CONCAT(usuarios.nombres, ' ', usuarios.apellidos) AS nombre_capturista, solpagos.cantidad, solpagos.proyecto, solpagos.folio, solpagos.justificacion, IFNULL(SUBSTRING(facturas.uuid, -5, 5 ), 'SF') AS ffiscal FROM solpagos INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor INNER JOIN usuarios ON usuarios.idusuario = solpagos.idusuario LEFT JOIN facturas ON facturas.idsolicitud = solpagos.idsolicitud WHERE FIND_IN_SET( solpagos.idsolicitud, '$idsolicitudes') AND solpagos.idusuario LIKE '".$this->session->userdata("inicio_sesion")['id']."' ORDER BY solpagos.fecha_autorizacion")->result_array();
        }
        else if( $this->session->userdata("inicio_sesion")['rol'] == 'CJ' ){
            if( $this->session->userdata("inicio_sesion")['id'] != $idResponsable){
                return $this->db->query("SELECT solpagos.idusuario, solpagos.idsolicitud, solpagos.folio, solpagos.moneda, solpagos.justificacion, empresas.abrev, solpagos.nomdepto, proveedores.nombre AS nombre_proveedor, DATE_FORMAT( solpagos.fecelab, '%d/%m/%Y') AS fecelab, DATE_FORMAT( solpagos.fecelab, '%d/%m/%Y') AS fecautorizacion, CONCAT(usuarios.nombres, ' ', usuarios.apellidos) AS nombre_capturista, solpagos.cantidad, solpagos.proyecto, solpagos.folio, solpagos.justificacion, IFNULL(SUBSTRING(facturas.uuid, -5, 5 ), 'SF') AS ffiscal FROM solpagos INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor INNER JOIN usuarios ON usuarios.idusuario = solpagos.idusuario LEFT JOIN facturas ON facturas.idsolicitud = solpagos.idsolicitud WHERE FIND_IN_SET( solpagos.idsolicitud, '$idsolicitudes') AND solpagos.idusuario LIKE '".$this->session->userdata("inicio_sesion")['id']."' ORDER BY solpagos.fecha_autorizacion")->result_array();
            }else{
                return $this->db->query("SELECT solpagos.idusuario, solpagos.idsolicitud, solpagos.folio, solpagos.moneda, solpagos.justificacion, empresas.abrev, solpagos.nomdepto, proveedores.nombre AS nombre_proveedor, DATE_FORMAT( solpagos.fecelab, '%d/%m/%Y') AS fecelab, DATE_FORMAT( solpagos.fecelab, '%d/%m/%Y') AS fecautorizacion, CONCAT(usuarios.nombres, ' ', usuarios.apellidos) AS nombre_capturista, solpagos.cantidad, solpagos.proyecto, solpagos.folio, solpagos.justificacion, IFNULL(SUBSTRING(facturas.uuid, -5, 5 ), 'SF') AS ffiscal FROM solpagos INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor INNER JOIN usuarios ON usuarios.idusuario = solpagos.idusuario LEFT JOIN facturas ON facturas.idsolicitud = solpagos.idsolicitud WHERE FIND_IN_SET( solpagos.idsolicitud, '$idsolicitudes') ORDER BY solpagos.fecha_autorizacion")->result_array();
            }
        }
        else{
            return $this->db->query("SELECT solpagos.idusuario, solpagos.idsolicitud, solpagos.folio, solpagos.moneda, solpagos.justificacion, empresas.abrev, solpagos.nomdepto, proveedores.nombre AS nombre_proveedor, DATE_FORMAT( solpagos.fecelab, '%d/%m/%Y') AS fecelab, DATE_FORMAT( solpagos.fecelab, '%d/%m/%Y') AS fecautorizacion, CONCAT(usuarios.nombres, ' ', usuarios.apellidos) AS nombre_capturista, solpagos.cantidad, solpagos.proyecto, solpagos.folio, solpagos.justificacion, IFNULL(SUBSTRING(facturas.uuid, -5, 5 ), 'SF') AS ffiscal FROM solpagos INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor INNER JOIN usuarios ON usuarios.idusuario = solpagos.idusuario LEFT JOIN facturas ON facturas.idsolicitud = solpagos.idsolicitud WHERE FIND_IN_SET( solpagos.idsolicitud, '$idsolicitudes') ORDER BY solpagos.fecha_autorizacion")->result_array();
        }
        */
        switch( $this->session->userdata("inicio_sesion")['rol'] ){
            case 'AS':
            case 'DA':
                return $this->db->query("SELECT solpagos.idusuario, solpagos.idsolicitud, solpagos.folio, solpagos.moneda, solpagos.justificacion, empresas.abrev, solpagos.nomdepto, proveedores.nombre AS nombre_proveedor, DATE_FORMAT( solpagos.fecelab, '%d/%m/%Y') AS fecelab, DATE_FORMAT( solpagos.fecelab, '%d/%m/%Y') AS fecautorizacion, CONCAT(usuarios.nombres, ' ', usuarios.apellidos) AS nombre_capturista, solpagos.cantidad, solpagos.proyecto, solpagos.folio, solpagos.justificacion, IFNULL(SUBSTRING(facturas.uuid, -5, 5 ), 'SF') AS ffiscal FROM solpagos INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor INNER JOIN usuarios ON usuarios.idusuario = solpagos.idusuario LEFT JOIN (SELECT *, MIN(facturas.feccrea) FROM facturas WHERE facturas.tipo_factura IN ( 1, 3 ) GROUP BY facturas.idsolicitud) AS facturas ON facturas.idsolicitud = solpagos.idsolicitud WHERE solpagos.idetapa = 11 AND solpagos.caja_chica = 1 AND solpagos.nomdepto = '".$this->session->userdata("inicio_sesion")['depto']."' ORDER BY solpagos.fecha_autorizacion")->result_array();
                break;
            case 'CJ':
                return $this->db->query("SELECT solpagos.idusuario, solpagos.idsolicitud, solpagos.folio, solpagos.moneda, solpagos.justificacion, empresas.abrev, solpagos.nomdepto, proveedores.nombre AS nombre_proveedor, DATE_FORMAT( solpagos.fecelab, '%d/%m/%Y') AS fecelab, DATE_FORMAT( solpagos.fecelab, '%d/%m/%Y') AS fecautorizacion, CONCAT(usuarios.nombres, ' ', usuarios.apellidos) AS nombre_capturista, solpagos.cantidad, solpagos.proyecto, solpagos.folio, solpagos.justificacion, IFNULL(SUBSTRING(facturas.uuid, -5, 5 ), 'SF') AS ffiscal FROM solpagos INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor INNER JOIN usuarios ON usuarios.idusuario = solpagos.idusuario LEFT JOIN (SELECT *, MIN(facturas.feccrea) FROM facturas WHERE facturas.tipo_factura IN ( 1, 3 ) GROUP BY facturas.idsolicitud) AS facturas ON facturas.idsolicitud = solpagos.idsolicitud WHERE solpagos.idetapa = 11 AND solpagos.caja_chica = 1 AND solpagos.idResponsable = '".$this->session->userdata("inicio_sesion")['id']."' ORDER BY solpagos.fecha_autorizacion")->result_array();
                break;
            case 'CA':
                return $this->db->query("SELECT solpagos.idusuario, solpagos.idsolicitud, solpagos.folio, solpagos.moneda, solpagos.justificacion, empresas.abrev, solpagos.nomdepto, proveedores.nombre AS nombre_proveedor, DATE_FORMAT( solpagos.fecelab, '%d/%m/%Y') AS fecelab, DATE_FORMAT( solpagos.fecelab, '%d/%m/%Y') AS fecautorizacion, CONCAT(usuarios.nombres, ' ', usuarios.apellidos) AS nombre_capturista, solpagos.cantidad, solpagos.proyecto, solpagos.folio, solpagos.justificacion, IFNULL(SUBSTRING(facturas.uuid, -5, 5 ), 'SF') AS ffiscal FROM solpagos INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor INNER JOIN usuarios ON usuarios.idusuario = solpagos.idusuario LEFT JOIN (SELECT *, MIN(facturas.feccrea) FROM facturas WHERE facturas.tipo_factura IN ( 1, 3 ) GROUP BY facturas.idsolicitud) AS facturas ON facturas.idsolicitud = solpagos.idsolicitud WHERE solpagos.idetapa = 11 AND solpagos.caja_chica = 1 AND solpagos.idusuario = '".$this->session->userdata("inicio_sesion")['id']."' ORDER BY solpagos.fecha_autorizacion")->result_array();
                break;
            default:
                return $this->db->query("SELECT solpagos.idusuario, solpagos.idsolicitud, solpagos.folio, solpagos.moneda, solpagos.justificacion, empresas.abrev, solpagos.nomdepto, proveedores.nombre AS nombre_proveedor, DATE_FORMAT( solpagos.fecelab, '%d/%m/%Y') AS fecelab, DATE_FORMAT( solpagos.fecelab, '%d/%m/%Y') AS fecautorizacion, CONCAT(usuarios.nombres, ' ', usuarios.apellidos) AS nombre_capturista, solpagos.cantidad, solpagos.proyecto, solpagos.folio, solpagos.justificacion, IFNULL(SUBSTRING(facturas.uuid, -5, 5 ), 'SF') AS ffiscal FROM solpagos INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor INNER JOIN usuarios ON usuarios.idusuario = solpagos.idusuario LEFT JOIN (SELECT *, MIN(facturas.feccrea) FROM facturas WHERE facturas.tipo_factura IN ( 1, 3 ) GROUP BY facturas.idsolicitud) AS facturas ON facturas.idsolicitud = solpagos.idsolicitud WHERE solpagos.idetapa = 11 AND solpagos.caja_chica = 1 ORDER BY solpagos.fecha_autorizacion")->result_array();
                break;
        }
    }

    function get_solicitudes_nuevas_caja_chica_propuesta( $idsolicitudes ){
        return $this->db->query("SELECT solpagos.idusuario, solpagos.idsolicitud, solpagos.folio, solpagos.moneda, solpagos.justificacion, empresas.abrev, solpagos.nomdepto, proveedores.nombre AS nombre_proveedor, DATE_FORMAT( solpagos.fecelab, '%d/%m/%Y') AS fecelab, DATE_FORMAT( solpagos.fecelab, '%d/%m/%Y') AS fecautorizacion, CONCAT(usuarios.nombres, ' ', usuarios.apellidos) AS nombre_capturista, solpagos.cantidad, solpagos.proyecto, solpagos.folio, solpagos.justificacion, IFNULL(SUBSTRING(facturas.uuid, -5, 5 ), 'SF') AS ffiscal FROM solpagos INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor INNER JOIN usuarios ON usuarios.idusuario = solpagos.idusuario LEFT JOIN (SELECT *, MIN(facturas.feccrea) FROM facturas WHERE facturas.tipo_factura IN ( 1, 3 ) GROUP BY facturas.idsolicitud) AS facturas ON facturas.idsolicitud = solpagos.idsolicitud WHERE solpagos.idetapa IN ( 7, 11, 31 ) AND solpagos.caja_chica != 0 AND FIND_IN_SET( solpagos.idsolicitud, '$idsolicitudes' ) ORDER BY solpagos.fecha_autorizacion")->result_array();
    }

    function backPag( $id, $idSol ){
        $this->db->trans_start();
        $this->db->query("UPDATE autpagos SET estatus = 0 , formaPago = 'TEA' , tipoPago = 'TEA' , fecha_pago = null WHERE idpago = '$id'");
        $this->db->query("UPDATE solpagos SET metoPago = 'TEA' WHERE idsolicitud = '$idSol'");
        return $this->db->trans_complete();
    }


     function update_referencia_cancelar($bd,$idpago){
 
        if($bd=='1'){
             $this->db->update(($bd == 1 ? "autpagos" : "autpagos_caja_chica"), array("estatus" => "98"), "idpago = '$idpago'");
            $cons = $this->db->query("SELECT idsolicitud from autpagos WHERE idpago = '".$idpago."'");
            $this->db->query("UPDATE solpagos SET idetapa = 30  WHERE idsolicitud = '".$cons->row()->idsolicitud."'");
            log_sistema($this->session->userdata("inicio_sesion")['id'], $cons->row()->idsolicitud, "CANCELACION DEFINITIVA DE CHEQUE");
            }  
            else{

                $this->db->update(($bd == 1 ? "autpagos" : "autpagos_caja_chica"), array("estatus" => "98"), "idpago = '$idpago'");

            } 

            
        // $cons = $this->db->query("SELECT idsolicitud from autpagos WHERE idpago = '".$idpago."'");
        // $this->db->query("UPDATE solpagos SET idetapa = 30  WHERE idsolicitud = '".$cons->row()->idsolicitud."'");

    }




    function get_solicitudes_programadas( $estatus ){
            return $this->db->query("SELECT 
                IFNULL(ptotales, 0) ptotales, tparcial,
                IF(solpagos.fecha_fin IS NULL, 'SIN DEFINIR', ROUND( CASE WHEN solpagos.programado < 7 THEN TIMESTAMPDIFF(MONTH, CONCAT( YEAR(solpagos.fecelab),'-',MONTH(solpagos.fecelab),'-', DAY(solpagos.fecha_fin) ), solpagos.fecha_fin) / solpagos.programado ELSE TIMESTAMPDIFF(WEEK, solpagos.fecelab, solpagos.fecha_fin) END ) + 1 ) ppago,
                IF(solpagos.idetapa != 11, (CASE WHEN solpagos.programado < 7 THEN DATE_ADD( solpagos.fecelab, INTERVAL ( IFNULL(ptotales, 0) / solpagos.programado ) MONTH ) ELSE DATE_ADD( solpagos.fecelab, INTERVAL IFNULL(ptotales, 0) WEEK ) END ), solpagos.fecha_fin) proximo_pago,
                solpagos.programado,
                solpagos.idsolicitud,
                solpagos.justificacion,
                solpagos.metoPago,
                solpagos.fecha_fin,
                proveedores.nombre,
                solpagos.intereses,
                solpagos.cantidad,
                autpagos.cantidad_confirmada,
                autpagos.interes,
                solpagos.fecelab fecreg,
                solpagos.nomdepto,
                empresas.abrev AS nemp,
                nombre_capturista,
                estatus_ultimo_pago,
                solpagos.idetapa,
                IFNULL(pp.cant_plan,0) as cant_plan
                FROM
                solpagos
                INNER JOIN
                proveedores ON proveedores.idproveedor = solpagos.idProveedor
                    INNER JOIN
                empresas ON solpagos.idempresa = empresas.idempresa
                    LEFT JOIN
                ( SELECT autpagos.idsolicitud, COUNT(autpagos.idpago) ptotales,  SUM(autpagos.cantidad) AS tparcial, SUM( IF(estatus IN ( 14, 16), autpagos.cantidad,0) ) cantidad_confirmada, SUM(IF(estatus IN ( 14, 16), autpagos.interes, 0) ) AS interes, MAX( autpagos.fecreg ) AS ultimo_pago 
            FROM autpagos GROUP BY autpagos.idsolicitud  ) autpagos ON solpagos.idsolicitud = autpagos.idsolicitud
                LEFT JOIN ( SELECT autpagos.idsolicitud, autpagos.estatus estatus_ultimo_pago FROM autpagos WHERE autpagos.estatus NOT IN ( 14, 16) GROUP BY autpagos.idsolicitud HAVING MAX(autpagos.idpago) ) estatus_pago ON estatus_pago.idsolicitud = solpagos.idsolicitud
                    INNER JOIN
                ( SELECT usuarios.idusuario, CONCAT(usuarios.nombres,' ', usuarios.apellidos) AS nombre_capturista FROM usuarios ) usuarios ON usuarios.idusuario = solpagos.idusuario
                LEFT JOIN (SELECT idsolicitud,COUNT(idplan_pago) as cant_plan from planes_pagosprog where estatus=1 group by idsolicitud) pp on pp.idsolicitud=solpagos.idsolicitud 
            WHERE
                solpagos.programado IS NOT NULL AND solpagos.idetapa NOT IN ( $estatus )
            ORDER BY proveedores.nombre ASC");
    }

    function insertHC($data) {
        $this->db->insert("historial_cheques", $data);
    }

    function update_referencia( $bd, $serie, $idpago, $cuenta ) {
        $this->db->update(($bd == 1 ? "autpagos" : "autpagos_caja_chica"), array("referencia" => $serie, "estatus" => ($bd == 1 ? 1 : 5)), "idpago = '$idpago'");
        $consecutivo = $serie + 1 ;
        $this->db->query("UPDATE serie_cheques SET serie = '".$consecutivo."' WHERE idCuenta = '$cuenta'");
    }
    
    function numserie($cuenta){
        $this->db->where("idCuenta", $cuenta);
       $serie = $this->db->get('serie_cheques');
       return ($serie->num_rows() > 0) ? $serie->row() : null;
    }

    function autCheque($bd, $data, $idpago) {
        $this->db->update( $bd, $data, "idpago = '$idpago'");
    }
    
    function insert_observ_cheque($data){
         $this->db->insert("obssols", $data);
    }
    
    function search_cheque($id){
        return $this->db->query("SELECT * FROM obssols WHERE idsolicitud = '".$id."'");
    }
    
    function update_observ_cheque($id,$observacion){
         $this->db->query("UPDATE obssols SET obssols.obervacion = '".$observacion."' WHERE obssols.idsolicitud = '".$id."' ");
         return $this->db->affected_rows();
    }

    function insertsolCh($data) {
        $this->db->insert("solpagos", $data);
        return $this->db->insert_id();
    }

    function insertautpCh($data) {
        $this->db->insert("autpagos", $data);
    }

    function getidsolicitud($idpago){
        return $this->db->query("SELECT idsolicitud FROM autpagos WHERE idpago = '$idpago'")->row();
    }
	
	function cajas_chicas_transito(){
        return $this->db->query("SELECT * FROM cajas_chicas_transito");
    }

    function tdc_transito(){
        return $this->db->query("SELECT 
            s.idsolicitud ID, 
            p.nombre responsable, 
            e.abrev, 
            DATE_FORMAT(s.fecelab,'%d/%b/%Y') FECHAFACP, 
            cantidad, 
            'TARJETA CREDITO' nomdepto, 
            0 pa, 
            null solicitudes
        FROM
        ( SELECT 
            GROUP_CONCAT(s.idsolicitud) idsolicitud, 
            s.idempresa, 
            s.fecelab, 
            SUM(s.cantidad) cantidad,
            tdc.idproveedor
        FROM ( SELECT GROUP_CONCAT( idsolicitud ) idsolicitud, idresponsable, idempresa, MAX( fecelab ) fecelab, SUM( cantidad ) cantidad FROM solpagos s WHERE caja_chica = 2 AND idetapa = 7 GROUP BY idresponsable, idempresa ) s
        INNER JOIN listado_tdc tdc ON tdc.idtcredito = s.idresponsable 
        GROUP BY tdc.idproveedor ) s
        INNER JOIN empresas e ON s.idempresa = e.idempresa
        INNER JOIN proveedores p ON s.idproveedor = p.idproveedor");
    }

    //HISTORIAL DE CUENTAS POR PAGAR - PAGO A FACTORAJE
    function getHSolFactorajes() {
        ini_set('memory_limit','-1');
        return $this->db->query("SELECT * FROM listado_factoraje");
    }

    //
    function getHistorialPagosDir(){
        return $this->db->query("SELECT 
        autpagos.referencia as ref, 
        solpagos.metoPago, 
        autpagos.estatus as estapag, 
        solpagos.justificacion, 
        polizas_provision.numpoliza, 
        solpagos.idsolicitud, 
        capturista.nombre_capturista, 
        responsable.nombre_responsable, 
        solpagos.etapa, 
        solpagos.condominio, 
        DATE_FORMAT(solpagos.fecelab , '%d/%m/%Y') AS fecha_factura, 
        DATE_FORMAT(solpagos.fechaCreacion, '%d/%m/%Y') AS fecha_captura,  
        autpagos.estatus, 
        DATE_FORMAT(autpagos.fecreg, '%d/%b/%Y') AS fechaaut, 
        solpagos.folio, 
        solpagos.moneda, 
        proveedores.nombre, 
        solpagos.nomdepto, 
        autpagos.cantidad, 
        IFNULL(DATE_FORMAT( IFNULL(autpagos.fechaOpe, autpagos.fecha_cobro), '%d/%m/%Y'), '') AS fechaOpe, 
        IFNULL(autpagos.formaPago, '') AS formaPago, 
        empresas.abrev, IFNULL(autpagos.referencia, '') AS referencia, 
        solpagos.proyecto, solpagos.cantidad AS solicitado, 
        IFNULL(SUBSTR(facturas.uuid, -1, 5), 'SF') AS uuid, 
        IFNULL(DATE_FORMAT(autpagos.fechaDis, '%d/%b/%Y'),'') AS fecha_dispersion, 
        IFNULL(DATE_FORMAT(autpagos.fecreg, '%d/%b/%Y'),'') AS fecreg_2 
        FROM autpagos 
        INNER JOIN solpagos ON autpagos.idsolicitud = solpagos.idsolicitud 
        INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor 
        INNER JOIN ( SELECT usuarios.idusuario, CONCAT( usuarios.nombres, ' ', usuarios.apellidos ) AS nombre_capturista FROM usuarios ) AS capturista ON capturista.idusuario = solpagos.idusuario 
        INNER JOIN ( SELECT usuarios.idusuario, CONCAT( usuarios.nombres, ' ', usuarios.apellidos ) AS nombre_responsable FROM usuarios ) AS responsable ON responsable.idusuario = solpagos.idResponsable 
        LEFT JOIN (SELECT *, MIN(facturas.feccrea) FROM facturas WHERE facturas.tipo_factura IN ( 1, 3 ) GROUP BY facturas.idsolicitud) AS facturas ON facturas.idsolicitud = solpagos.idsolicitud INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa 
        LEFT JOIN ( SELECT * FROM polizas_provision GROUP BY polizas_provision.idsolicitud ) polizas_provision ON polizas_provision.idsolicitud = solpagos.idsolicitud 
        ORDER BY autpagos.fecreg DESC");
    }

    //HISTORIAL DE PAGOS PARA LOS DEPARTAMENTOS (DA, AS, CA, CJ)
    function getHistorialPagosDepto() {
        ini_set('memory_limit','-1');

        if( in_array( $this->session->userdata("inicio_sesion")['rol'], array( 'AS', 'DA' ) ) ){

            if($this->session->userdata("inicio_sesion")['depto'] == 'CONSTRUCCION'){
                $departamento = "'NOMINA DESTAJO', 'CONSTRUCCION'";
            }elseif( $this->session->userdata("inicio_sesion")['depto'] == 'COMPRAS' ){
                $departamento = "'CONSTRUCCION', 'COMPRAS', 'JARDINERIA'";
            }else{
                $departamento = "'".$this->session->userdata("inicio_sesion")['depto']."'";
            }

            $filtro = "solpagos.nomdepto IN ( $departamento  )";
        }

        if( in_array( $this->session->userdata("inicio_sesion")['rol'], array( 'CJ', 'CA' ) ) ){
            $filtro = "solpagos.idusuario = '" . $this->session->userdata("inicio_sesion")['id'] . "'";
        }

        return $this->db->query("SELECT 
        autpagos.estatus as estapag, 
        solpagos.justificacion, 
        polizas_provision.numpoliza, 
        solpagos.idsolicitud, 
        capturista.nombre_completo nombre_capturista, 
        responsable.nombre_completo nombre_responsable, 
        solpagos.etapa, solpagos.condominio, 
        solpagos.fecelab fecha_factura, 
        solpagos.fechaCreacion fecha_captura,  
        autpagos.estatus, 
        autpagos.fecreg fechaaut, 
        solpagos.folio, 
        solpagos.moneda, 
        proveedores.nombre, 
        solpagos.nomdepto, 
        autpagos.cantidad, 
        IFNULL(autpagos.fechaOpe, autpagos.fecha_cobro) fechaOpe, 
        IFNULL(autpagos.formaPago, solpagos.metoPago) formaPago, 
        empresas.abrev, 
        IFNULL(autpagos.referencia, '') AS referencia, 
        solpagos.proyecto, 
        solpagos.cantidad AS solicitado, 
        IFNULL(SUBSTR(facturas.uuid, -1, 5), 'SF') AS uuid, 
        autpagos.fechaDis fecha_dispersion
        FROM autpagos 
        INNER JOIN solpagos ON autpagos.idsolicitud = solpagos.idsolicitud 
        INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor 
        INNER JOIN listado_usuarios capturista ON capturista.idusuario = solpagos.idusuario 
        INNER JOIN listado_usuarios responsable ON responsable.idusuario = solpagos.idResponsable 
        LEFT JOIN factura_registro facturas ON facturas.idsolicitud = solpagos.idsolicitud 
        INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa 
        LEFT JOIN ( SELECT * FROM polizas_provision GROUP BY polizas_provision.idsolicitud ) polizas_provision ON polizas_provision.idsolicitud = solpagos.idsolicitud 
        WHERE $filtro ORDER BY autpagos.fecreg DESC");
    }

    //HISTORIAL DE SOLICITUDES DEVOLUCION Y TRASPASO
    //RECIBE EL PARAMETRO DE QUE ESTATUS SON LOS QUE VA A PODER VISUALIZAR
    function getHistorialDevTrap( $etapas ){
        return $this->db->query("SELECT
            solpagos.idsolicitud, 
            empresas.abrev, 
            solpagos.proyecto, 
            solpagos.folio, 
            proveedores.nombre, 
            solpagos.fecha_autorizacion,
            director.nombre_completo nombredir,
            capturista.nombre_completo,
            IF(solpagos.programado IS NOT NULL, solpagos.cantidad + IFNULL(cant_pag.pagado, 0),  solpagos.cantidad) AS mpagar,
            solpagos.descuento,
            cant_pag.pagado, 
            etapas.nombre AS etapa, 
            solpagos.etapa AS soletapa, 
            solpagos.condominio,
            solpagos.metoPago, 
            solpagos.nomdepto, 
            solpagos.fechaCreacion feccrea, 
            solpagos.programado, 
            solpagos.homoclave, 
            solpagos.justificacion, 
            facturas.uuid tienefac, 
            DATE(solpagos.fecelab) AS fecelab, 
            IFNULL(notifi.visto, 1) AS visto, 
            solpagos.moneda
            FROM solpagos
            LEFT JOIN factura_registro facturas ON facturas.idsolicitud = solpagos.idsolicitud 
            INNER JOIN listado_usuarios director ON director.idusuario = solpagos.idusuario 
            INNER JOIN listado_usuarios capturista ON capturista.idusuario = solpagos.idusuario 
            INNER JOIN empresas ON solpagos.idEmpresa = empresas.idempresa 
            INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor 
            INNER JOIN etapas ON etapas.idetapa = solpagos.idetapa 
            LEFT JOIN vw_autpagos cant_pag ON cant_pag.idsolicitud = solpagos.idsolicitud 
            LEFT JOIN ( SELECT notificaciones.idsolicitud, notificaciones.visto FROM notificaciones WHERE notificaciones.idusuario = ? GROUP BY notificaciones.idsolicitud ) AS notifi ON notifi.idsolicitud = solpagos.idsolicitud 
            WHERE solpagos.idetapa IN ?  
            AND nomdepto IN ( SELECT departamento FROM departamentos INNER JOIN usuario_depto ON iddepartamentos = iddepartamento WHERE usuario_depto.idusuario = ? )  
            ORDER BY solpagos.idetapa, solpagos.fechaCreacion DESC", array( $this->session->userdata("inicio_sesion")['id'], $etapas, $this->session->userdata("inicio_sesion")['id']  ) );
    }

    function getHistorialTablaSolOri($post) {
        ini_set('memory_limit','-1');
        switch ($this->session->userdata("inicio_sesion")['rol']) {
            case 'FP':                
                return $this->db->query("SELECT
                solpagos.idsolicitud, 
                empresas.abrev, 
                solpagos.proyecto, 
                solpagos.folio, 
                SUBSTRING(facturas.uuid, -5, 5) AS folifis,
                proveedores.nombre, 
                solpagos.fecha_autorizacion,
                director.nombre_completo nombredir,
                capturista.nombre_completo,
                IF(solpagos.programado IS NOT NULL, solpagos.cantidad + IFNULL(cant_pag.pagado, 0),  solpagos.cantidad) AS mpagar,
                solpagos.descuento,
                cant_pag.pagado, 
                etapas.nombre AS etapa, 
                solpagos.etapa AS soletapa, 
                solpagos.condominio,
                solpagos.metoPago, 
                solpagos.nomdepto, 
                solpagos.fechaCreacion feccrea, 
                solpagos.programado, 
                solpagos.homoclave, 
                solpagos.justificacion, 
                facturas.uuid, 
                DATE(solpagos.fecelab) AS fecelab, 
                IFNULL(notifi.visto, 1) AS visto, 
                solpagos.moneda
                FROM solpagos
                LEFT JOIN factura_registro facturas ON facturas.idsolicitud = solpagos.idsolicitud 
                INNER JOIN listado_usuarios director ON director.idusuario = solpagos.idusuario 
                INNER JOIN listado_usuarios capturista ON capturista.idusuario = solpagos.idusuario 
                INNER JOIN empresas ON solpagos.idEmpresa = empresas.idempresa 
                INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor 
                INNER JOIN etapas ON etapas.idetapa = solpagos.idetapa 
                LEFT JOIN vw_autpagos cant_pag ON cant_pag.idsolicitud = solpagos.idsolicitud 
                LEFT JOIN ( SELECT notificaciones.idsolicitud, notificaciones.visto FROM notificaciones WHERE notificaciones.idusuario = ? GROUP BY notificaciones.idsolicitud ) AS notifi ON notifi.idsolicitud = solpagos.idsolicitud 
                WHERE nomdepto IN ( SELECT departamento FROM departamentos INNER JOIN usuario_depto ON iddepartamentos = iddepartamento WHERE usuario_depto.idusuario = ? ) 
                AND solpagos.idetapa NOT IN ( 1 )
                ORDER BY solpagos.idetapa, solpagos.fechaCreacion DESC", array( $this->session->userdata("inicio_sesion")['id'], $this->session->userdata("inicio_sesion")['id'] ) );
                break;
            case 'CX':
                return $this->db->query("SELECT facturas.nombre_archivo tienefac, solpagos.programado, facturas.tipo_factura, solpagos.idetapa, polizas_provision.idprovision, 
                IF( solpagos.programado IS NOT NULL, CASE WHEN solpagos.programado < 7 THEN TIMESTAMPDIFF(MONTH, CONCAT( YEAR(solpagos.fecelab),'-',MONTH(solpagos.fecelab),'-', DAY(solpagos.fecha_fin) ), IFNULL( solpagos.fecha_fin, DATE_ADD( solpagos.fecelab, INTERVAL IF( solpagos.idetapa NOT IN ( 11, 30 ), tpagos + 1, IFNULL(tpagos, 1) )  MONTH) ) ) ELSE TIMESTAMPDIFF(WEEK, solpagos.fecelab, IFNULL(solpagos.fecha_fin, DATE_ADD( solpagos.fecelab, INTERVAL IFNULL( tpagos, 1 )  WEEK) ) ) END, NULL ) mpagar,
                autpagos.estatus_pago, 
                solpagos.idetapa, 
                IFNULL( facturas.foliofac, solpagos.folio) folio, 
                solpagos.moneda, SUBSTRING(facturas.uuid, -5, 5) AS folifis, 
                solpagos.metoPago, director.nombredir, 
                capturista.nombre_completo, 
                solpagos.caja_chica, 
                solpagos.idsolicitud, 
                solpagos.justificacion, 
                solpagos.nomdepto, 
                proveedores.nombre, 
                solpagos.cantidad, 
                empresas.abrev, 
                solpagos.fechaCreacion feccrea, 
                solpagos.fecha_autorizacion fecha_autorizacion, 
                solpagos.fecelab fecelab, 
                autpagos.fautorizado fautorizado, 
                etapas.nombre AS etapa, IFNULL(autpagos.autorizado, 0) AS autorizado, IFNULL(autpagos.pagado, 0) AS pagado, 
                autpagos.fdispersion fechaDis2 
                FROM solpagos INNER JOIN ( SELECT usuarios.idusuario, CONCAT( usuarios.nombres,' ', usuarios.apellidos ) AS nombre_completo FROM usuarios ) AS capturista ON capturista.idusuario = solpagos.idusuario LEFT JOIN ( SELECT CONCAT( usuarios.nombres,' ',usuarios.apellidos ) AS nombredir, usuarios.idusuario FROM usuarios ) AS director ON director.idusuario = solpagos.idResponsable INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor INNER JOIN etapas ON etapas.idetapa = solpagos.idetapa LEFT JOIN (SELECT *, MIN(facturas.feccrea) FROM facturas WHERE facturas.tipo_factura IN ( 1, 3 ) GROUP BY facturas.idsolicitud) AS facturas ON facturas.idsolicitud = solpagos.idsolicitud INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa LEFT JOIN ( SELECT autpagos.idsolicitud, autpagos.estatus estatus_pago, SUM( IF( autpagos.estatus != 2, autpagos.cantidad, 0 ) ) pagado, SUM( autpagos.cantidad ) autorizado, MAX( autpagos.fecreg ) fautorizado, MAX( autpagos.fechaDis ) fdispersion, COUNT(autpagos.idsolicitud) tpagos FROM autpagos GROUP BY autpagos.idsolicitud ) AS autpagos ON autpagos.idsolicitud = solpagos.idsolicitud LEFT JOIN ( SELECT polizas_provision.idprovision, polizas_provision.idsolicitud, MIN( polizas_provision.fecreg ) FROM polizas_provision GROUP BY polizas_provision.idsolicitud ) AS polizas_provision ON polizas_provision.idsolicitud = solpagos.idsolicitud WHERE ( solpagos.caja_chica IS NULL OR solpagos.caja_chica != 1 ) AND ( solpagos.idusuario = '".$this->session->userdata("inicio_sesion")['id']."' OR FIND_IN_SET( solpagos.idEmpresa, '".$this->session->userdata("inicio_sesion")['depto']."' ) ) AND solpagos.idetapa IN ( 10, 11, 14 ) ORDER BY solpagos.idetapa, solpagos.fechaCreacion DESC");
                break;
            case 'CI':
                return $this->db->query("SELECT * FROM solicitudes_sin_factura WHERE 
                ( ( solicitudes_sin_factura.nomdepto LIKE 'DEVOLUCION%' OR solicitudes_sin_factura.nomdepto LIKE 'TRASPASO%' ) AND solicitudes_sin_factura.proyecto NOT IN ( 'TRASPASO INTERNO', 'DEVOLUCION POR DOMICILIACION', 'DEVOLUCION', 'DEVOLUCION POR DEPOSITO EN GARANTIA', 'DEVOLUCION POR MEDIDOR' ) )
                AND solicitudes_sin_factura.idetapa IN (10 , 11, 14)");
                break;
            case 'CT':
                //return $this->db->query("SELECT * FROM solicitudes_sin_factura WHERE solicitudes_sin_factura.nomdepto NOT IN ( 'NOMINAS', 'TRASPASO', 'DEVOLUCIONES', 'FINIQUITO', 'FINIQUITO POR PARCIALIDAD', 'FINIQUITO POR PARCIALIDAD', 'FINIQUITO POR RENUNCIA' ) AND FIND_IN_SET( solicitudes_sin_factura.idempresa, '".$this->session->userdata("inicio_sesion")['depto']."' ) AND solicitudes_sin_factura.idetapa NOT IN ( 0, 1, 2, 3, 4, 30 ) AND ( caja_chica IS NULL OR caja_chica = 0 )");
                return $this->db->query("SELECT 
                    facturas.tipo_factura,
                    solpagos.idetapa,
                    solpagos.proyecto,
                    polizas_provision.idprovision,
                    solpagos.programado,
                    IF( solpagos.programado IS NOT NULL, calcular_monto_programado ( CONCAT( YEAR(solpagos.fecelab),'-',MONTH(solpagos.fecelab),'-', DAY(solpagos.fecha_fin) ), solpagos.fecha_fin, solpagos.idetapa, tpagos, cantidad, solpagos.programado), solpagos.cantidad ) mpagar,
                    autpagos.estatus_pago,
                    solpagos.folio,
                    solpagos.moneda,
                    SUBSTRING(facturas.uuid, - 5, 5) AS folifis,
                    solpagos.metoPago,
                    director.nombre_completo nombredir,
                    capturista.nombre_completo,
                    solpagos.caja_chica,
                    solpagos.idsolicitud,
                    solpagos.justificacion,
                    solpagos.nomdepto,
                    proveedores.nombre,
                    empresas.abrev,
                    solpagos.fechaCreacion AS feccrea,
                    solpagos.fecha_autorizacion AS fecha_autorizacion,
                    solpagos.fecelab AS fecelab,
                    autpagos.upago fautorizado,
                    etapas.nombre AS etapa,
                    IFNULL(autpagos.pagado, 0) AS pagado,
                    autpagos.fechaDis AS fechaDis2
                FROM
                    solpagos
                        INNER JOIN listado_usuarios capturista ON capturista.idusuario = solpagos.idusuario
                        LEFT JOIN listado_usuarios director ON director.idusuario = solpagos.idResponsable
                        INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor
                        INNER JOIN etapas ON etapas.idetapa = solpagos.idetapa
                        LEFT JOIN factura_registro facturas ON facturas.idsolicitud = solpagos.idsolicitud
                        INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa
                        LEFT JOIN vw_autpagos autpagos ON autpagos.idsolicitud = solpagos.idsolicitud
                        LEFT JOIN
                            (SELECT 
                                polizas_provision.idprovision,
                                    polizas_provision.idsolicitud,
                                    MIN(polizas_provision.fecreg)
                            FROM
                                polizas_provision
                            GROUP BY polizas_provision.idsolicitud) AS polizas_provision ON polizas_provision.idsolicitud = solpagos.idsolicitud
                        WHERE
                            ( solpagos.caja_chica IS NULL OR solpagos.caja_chica != 0 )
                    AND solpagos.idetapa NOT IN ( 0, 1, 2, 3, 4, 25, 30, 42, 45, 47, 49) 
                    AND solpagos.nomdepto NOT IN ( 'NOMINAS', 'TRASPASO', 'DEVOLUCIONES', 'FINIQUITO', 'FINIQUITO POR PARCIALIDAD', 'FINIQUITO POR PARCIALIDAD', 'FINIQUITO POR RENUNCIA' ) 
                    AND FIND_IN_SET( solpagos.idempresa, '".$this->session->userdata("inicio_sesion")['depto']."' ) 
                ORDER BY solpagos.fechaCreacion DESC");
                break;
            default:
                return $this->db->query("SELECT * FROM solpagos_cajas_chicas_TODO");
                break;
        }
    }
    
    function getHistorialTablaSolOri_filtrado($filtro){
        ini_set('memory_limit','-1');
        switch ($this->session->userdata("inicio_sesion")['rol']) {
            case 'DA':
                if( $this->session->userdata("inicio_sesion")['depto'] == 'CONSTRUCCION' )
                    return $this->db->query("SELECT IF(facturas.uuid IS null, 'NO', 'SI') AS tienefac, solpagos.programado, facturas.tipo_factura, solpagos.idetapa, polizas_provision.idprovision, solpagos.proyecto,
                    IF( solpagos.programado IS NOT NULL, CASE WHEN solpagos.programado < 7 THEN TIMESTAMPDIFF(MONTH, CONCAT( YEAR(solpagos.fecelab),'-',MONTH(solpagos.fecelab),'-', DAY(solpagos.fecha_fin) ), IFNULL( solpagos.fecha_fin, DATE_ADD( solpagos.fecelab, INTERVAL IF( solpagos.idetapa NOT IN ( 11, 30 ), tpagos + 1, IFNULL(tpagos, 1) )  MONTH) ) ) ELSE TIMESTAMPDIFF(WEEK, solpagos.fecelab, IFNULL(solpagos.fecha_fin, DATE_ADD( solpagos.fecelab, INTERVAL IFNULL( tpagos, 1 )  WEEK) ) ) END, NULL ) mpagar,
                    pago_generado.estatus_pago, solpagos.idetapa, solpagos.folio, solpagos.moneda, SUBSTRING(facturas.uuid, -5, 5) AS folifis, solpagos.metoPago, director.nombredir, capturista.nombre_completo, solpagos.caja_chica, solpagos.idsolicitud, solpagos.justificacion, solpagos.nomdepto, proveedores.nombre, solpagos.cantidad, empresas.abrev, solpagos.fechaCreacion AS feccrea, solpagos.fecha_autorizacion AS fecha_autorizacion, solpagos.fecelab AS fecelab, etapas.nombre AS etapa, IFNULL(pago_aut.autorizado, 0) AS autorizado,   IFNULL(liquidado.pagado, 0) AS pagado, autpagos.fechaDis as fechaDis2 ,solpagos.fecha_autorizacion AS fech_auto  FROM solpagos LEFT JOIN ( SELECT *, autpagos.estatus AS estatus_pago, MAX(autpagos.fecreg) FROM autpagos GROUP BY autpagos.idsolicitud ) AS pago_generado ON pago_generado.idsolicitud = solpagos.idsolicitud INNER JOIN ( SELECT usuarios.idusuario, CONCAT( usuarios.nombres,' ', usuarios.apellidos ) AS nombre_completo FROM usuarios ) AS capturista ON capturista.idusuario = solpagos.idusuario LEFT JOIN (SELECT autpagos.idsolicitud, SUM( autpagos.cantidad ) AS pagado FROM autpagos GROUP BY autpagos.idsolicitud) AS liquidado ON solpagos.idsolicitud = liquidado.idsolicitud LEFT JOIN ( SELECT CONCAT( usuarios.nombres,' ',usuarios.apellidos ) AS nombredir, usuarios.idusuario FROM usuarios ) AS director ON director.idusuario = solpagos.idResponsable INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor INNER JOIN etapas ON etapas.idetapa = solpagos.idetapa LEFT JOIN (SELECT *, MIN(facturas.feccrea) FROM facturas WHERE facturas.tipo_factura IN ( 1, 3 ) GROUP BY facturas.idsolicitud) AS facturas ON facturas.idsolicitud = solpagos.idsolicitud LEFT JOIN ( SELECT autpagos.idsolicitud, SUM(autpagos.cantidad) AS autorizado FROM autpagos WHERE autpagos.estatus NOT IN (2) GROUP BY autpagos.idsolicitud ) AS pago_aut ON pago_aut.idsolicitud = solpagos.idsolicitud  INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa LEFT JOIN ( SELECT *, MAX(autpagos.fecreg), COUNT(autpagos.idsolicitud) tpagos FROM autpagos GROUP BY autpagos.idsolicitud ) AS autpagos ON autpagos.idsolicitud = solpagos.idsolicitud LEFT JOIN ( SELECT polizas_provision.idprovision, polizas_provision.idsolicitud, MIN( polizas_provision.fecreg ) FROM polizas_provision GROUP BY polizas_provision.idsolicitud ) AS polizas_provision ON polizas_provision.idsolicitud = solpagos.idsolicitud WHERE ( solpagos.nomdepto = '" . $this->session->userdata("inicio_sesion")['depto'] . "'  OR solpagos.idusuario IN ( SELECT usuarios.idusuario FROM usuarios WHERE usuarios.da = '".$this->session->userdata("inicio_sesion")['id']."' OR FIND_IN_SET('".$this->session->userdata("inicio_sesion")['id']."', usuarios.sup ) ) ) AND solpagos.idetapa IN ( 7, 9, 10, 11, 14 ) ORDER BY solpagos.idetapa, solpagos.fechaCreacion DESC");             
                else
                    return $this->db->query("SELECT IF(facturas.uuid IS null, 'NO', 'SI') AS tienefac, solpagos.programado, facturas.tipo_factura, solpagos.idetapa, polizas_provision.idprovision, solpagos.proyecto,
                    IF( solpagos.programado IS NOT NULL, CASE WHEN solpagos.programado < 7 THEN TIMESTAMPDIFF(MONTH, CONCAT( YEAR(solpagos.fecelab),'-',MONTH(solpagos.fecelab),'-', DAY(solpagos.fecha_fin) ), IFNULL( solpagos.fecha_fin, DATE_ADD( solpagos.fecelab, INTERVAL IF( solpagos.idetapa NOT IN ( 11, 30 ), tpagos + 1, IFNULL(tpagos, 1) )  MONTH) ) ) ELSE TIMESTAMPDIFF(WEEK, solpagos.fecelab, IFNULL(solpagos.fecha_fin, DATE_ADD( solpagos.fecelab, INTERVAL IFNULL( tpagos, 1 )  WEEK) ) ) END, NULL ) mpagar,
                    pago_generado.estatus_pago, solpagos.idetapa, solpagos.folio, solpagos.moneda, SUBSTRING(facturas.uuid, -5, 5) AS folifis, solpagos.metoPago, director.nombredir, capturista.nombre_completo, solpagos.caja_chica, solpagos.idsolicitud, solpagos.justificacion, solpagos.nomdepto, proveedores.nombre, solpagos.cantidad, empresas.abrev, solpagos.fechaCreacion AS feccrea, solpagos.fecha_autorizacion AS fecha_autorizacion, solpagos.fecelab AS fecelab, etapas.nombre AS etapa, IFNULL(pago_aut.autorizado, 0) AS autorizado,   IFNULL(liquidado.pagado, 0) AS pagado, autpagos.fechaDis as fechaDis2 ,solpagos.fecha_autorizacion AS fech_auto  FROM solpagos LEFT JOIN ( SELECT *, autpagos.estatus AS estatus_pago, MAX(autpagos.fecreg) FROM autpagos GROUP BY autpagos.idsolicitud ) AS pago_generado ON pago_generado.idsolicitud = solpagos.idsolicitud INNER JOIN ( SELECT usuarios.idusuario, CONCAT( usuarios.nombres,' ', usuarios.apellidos ) AS nombre_completo FROM usuarios ) AS capturista ON capturista.idusuario = solpagos.idusuario LEFT JOIN (SELECT autpagos.idsolicitud, SUM( autpagos.cantidad ) AS pagado FROM autpagos GROUP BY autpagos.idsolicitud) AS liquidado ON solpagos.idsolicitud = liquidado.idsolicitud LEFT JOIN ( SELECT CONCAT( usuarios.nombres,' ',usuarios.apellidos ) AS nombredir, usuarios.idusuario FROM usuarios ) AS director ON director.idusuario = solpagos.idResponsable INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor INNER JOIN etapas ON etapas.idetapa = solpagos.idetapa LEFT JOIN (SELECT *, MIN(facturas.feccrea) FROM facturas WHERE facturas.tipo_factura IN ( 1, 3 ) GROUP BY facturas.idsolicitud) AS facturas ON facturas.idsolicitud = solpagos.idsolicitud LEFT JOIN ( SELECT autpagos.idsolicitud, SUM(autpagos.cantidad) AS autorizado FROM autpagos WHERE autpagos.estatus NOT IN (2) GROUP BY autpagos.idsolicitud ) AS pago_aut ON pago_aut.idsolicitud = solpagos.idsolicitud  INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa LEFT JOIN ( SELECT *, MAX(autpagos.fecreg), COUNT(autpagos.idsolicitud) tpagos FROM autpagos GROUP BY autpagos.idsolicitud ) AS autpagos ON autpagos.idsolicitud = solpagos.idsolicitud LEFT JOIN ( SELECT polizas_provision.idprovision, polizas_provision.idsolicitud, MIN( polizas_provision.fecreg ) FROM polizas_provision GROUP BY polizas_provision.idsolicitud ) AS polizas_provision ON polizas_provision.idsolicitud = solpagos.idsolicitud WHERE ( solpagos.nomdepto = '" . $this->session->userdata("inicio_sesion")['depto'] . "'  OR solpagos.idusuario IN ( SELECT usuarios.idusuario FROM usuarios WHERE usuarios.da = '".$this->session->userdata("inicio_sesion")['id']."' OR FIND_IN_SET('".$this->session->userdata("inicio_sesion")['id']."', usuarios.sup ) ) ) AND solpagos.idetapa IN ( 10, 11, 14 ) ORDER BY solpagos.idetapa, solpagos.fechaCreacion DESC"); 
                break;
            case 'AS':
                if( $this->session->userdata("inicio_sesion")['depto'] == 'CONSTRUCCION' ){
                    return $this->db->query("SELECT IF(facturas.uuid IS null, 'NO', 'SI') AS tienefac, solpagos.programado, facturas.tipo_factura, solpagos.idetapa, polizas_provision.idprovision, solpagos.proyecto,
                    IF( solpagos.programado IS NOT NULL, CASE WHEN solpagos.programado < 7 THEN TIMESTAMPDIFF(MONTH, CONCAT( YEAR(solpagos.fecelab),'-',MONTH(solpagos.fecelab),'-', DAY(solpagos.fecha_fin) ), IFNULL( solpagos.fecha_fin, DATE_ADD( solpagos.fecelab, INTERVAL IF( solpagos.idetapa NOT IN ( 11, 30 ), tpagos + 1, IFNULL(tpagos, 1) )  MONTH) ) ) ELSE TIMESTAMPDIFF(WEEK, solpagos.fecelab, IFNULL(solpagos.fecha_fin, DATE_ADD( solpagos.fecelab, INTERVAL IFNULL( tpagos, 1 )  WEEK) ) ) END, NULL ) mpagar,
                    pago_generado.estatus_pago, solpagos.idetapa, solpagos.folio, solpagos.moneda, SUBSTRING(facturas.uuid, -5, 5) AS folifis, solpagos.metoPago, director.nombredir, capturista.nombre_completo, solpagos.caja_chica, solpagos.idsolicitud, solpagos.justificacion, solpagos.nomdepto, proveedores.nombre, solpagos.cantidad, empresas.abrev, solpagos.fechaCreacion AS feccrea, solpagos.fecha_autorizacion AS fecha_autorizacion, solpagos.fecelab AS fecelab, etapas.nombre AS etapa, IFNULL(pago_aut.autorizado, 0) AS autorizado,   IFNULL(liquidado.pagado, 0) AS pagado, autpagos.fechaDis as fechaDis2 ,solpagos.fecha_autorizacion AS fech_auto  FROM solpagos LEFT JOIN ( SELECT *, autpagos.estatus AS estatus_pago, MAX(autpagos.fecreg) FROM autpagos GROUP BY autpagos.idsolicitud ) AS pago_generado ON pago_generado.idsolicitud = solpagos.idsolicitud INNER JOIN ( SELECT usuarios.idusuario, CONCAT( usuarios.nombres,' ', usuarios.apellidos ) AS nombre_completo FROM usuarios ) AS capturista ON capturista.idusuario = solpagos.idusuario LEFT JOIN (SELECT autpagos.idsolicitud, SUM( autpagos.cantidad ) AS pagado FROM autpagos GROUP BY autpagos.idsolicitud) AS liquidado ON solpagos.idsolicitud = liquidado.idsolicitud LEFT JOIN ( SELECT CONCAT( usuarios.nombres,' ',usuarios.apellidos ) AS nombredir, usuarios.idusuario FROM usuarios ) AS director ON director.idusuario = solpagos.idResponsable INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor INNER JOIN etapas ON etapas.idetapa = solpagos.idetapa LEFT JOIN (SELECT *, MIN(facturas.feccrea) FROM facturas WHERE facturas.tipo_factura IN ( 1, 3 ) GROUP BY facturas.idsolicitud) AS facturas ON facturas.idsolicitud = solpagos.idsolicitud LEFT JOIN ( SELECT autpagos.idsolicitud, SUM(autpagos.cantidad) AS autorizado FROM autpagos WHERE autpagos.estatus NOT IN (2) GROUP BY autpagos.idsolicitud ) AS pago_aut ON pago_aut.idsolicitud = solpagos.idsolicitud  INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa LEFT JOIN ( SELECT *, MAX(autpagos.fecreg), COUNT(autpagos.idsolicitud) tpagos FROM autpagos GROUP BY autpagos.idsolicitud ) AS autpagos ON autpagos.idsolicitud = solpagos.idsolicitud LEFT JOIN ( SELECT polizas_provision.idprovision, polizas_provision.idsolicitud, MIN( polizas_provision.fecreg ) FROM polizas_provision GROUP BY polizas_provision.idsolicitud ) AS polizas_provision ON polizas_provision.idsolicitud = solpagos.idsolicitud WHERE nomdepto IN ('CONSTRUCCION', 'JARDINERIA', 'NOMINA DESTAJO') AND solpagos.idetapa IN ( 10, 11, 14 ) ORDER BY solpagos.idetapa, solpagos.fechaCreacion DESC");     
                }else{
                    return $this->db->query("SELECT IF(facturas.uuid IS null, 'NO', 'SI') AS tienefac, solpagos.programado, facturas.tipo_factura, solpagos.idetapa, polizas_provision.idprovision, solpagos.proyecto,
                    IF( solpagos.programado IS NOT NULL, CASE WHEN solpagos.programado < 7 THEN TIMESTAMPDIFF(MONTH, CONCAT( YEAR(solpagos.fecelab),'-',MONTH(solpagos.fecelab),'-', DAY(solpagos.fecha_fin) ), IFNULL( solpagos.fecha_fin, DATE_ADD( solpagos.fecelab, INTERVAL IF( solpagos.idetapa NOT IN ( 11, 30 ), tpagos + 1, IFNULL(tpagos, 1) )  MONTH) ) ) ELSE TIMESTAMPDIFF(WEEK, solpagos.fecelab, IFNULL(solpagos.fecha_fin, DATE_ADD( solpagos.fecelab, INTERVAL IFNULL( tpagos, 1 )  WEEK) ) ) END, NULL ) mpagar,
                    pago_generado.estatus_pago, solpagos.idetapa, solpagos.folio, solpagos.moneda, SUBSTRING(facturas.uuid, -5, 5) AS folifis, solpagos.metoPago, director.nombredir, capturista.nombre_completo, solpagos.caja_chica, solpagos.idsolicitud, solpagos.justificacion, solpagos.nomdepto, proveedores.nombre, solpagos.cantidad, empresas.abrev, solpagos.fechaCreacion AS feccrea, solpagos.fecha_autorizacion AS fecha_autorizacion, solpagos.fecelab AS fecelab, etapas.nombre AS etapa, IFNULL(pago_aut.autorizado, 0) AS autorizado,   IFNULL(liquidado.pagado, 0) AS pagado, autpagos.fechaDis as fechaDis2 ,solpagos.fecha_autorizacion AS fech_auto  FROM solpagos LEFT JOIN ( SELECT *, autpagos.estatus AS estatus_pago, MAX(autpagos.fecreg) FROM autpagos GROUP BY autpagos.idsolicitud ) AS pago_generado ON pago_generado.idsolicitud = solpagos.idsolicitud INNER JOIN ( SELECT usuarios.idusuario, CONCAT( usuarios.nombres,' ', usuarios.apellidos ) AS nombre_completo FROM usuarios ) AS capturista ON capturista.idusuario = solpagos.idusuario LEFT JOIN (SELECT autpagos.idsolicitud, SUM( autpagos.cantidad ) AS pagado FROM autpagos GROUP BY autpagos.idsolicitud) AS liquidado ON solpagos.idsolicitud = liquidado.idsolicitud LEFT JOIN ( SELECT CONCAT( usuarios.nombres,' ',usuarios.apellidos ) AS nombredir, usuarios.idusuario FROM usuarios ) AS director ON director.idusuario = solpagos.idResponsable INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor INNER JOIN etapas ON etapas.idetapa = solpagos.idetapa LEFT JOIN (SELECT *, MIN(facturas.feccrea) FROM facturas WHERE facturas.tipo_factura IN ( 1, 3 ) GROUP BY facturas.idsolicitud) AS facturas ON facturas.idsolicitud = solpagos.idsolicitud LEFT JOIN ( SELECT autpagos.idsolicitud, SUM(autpagos.cantidad) AS autorizado FROM autpagos WHERE autpagos.estatus NOT IN (2) GROUP BY autpagos.idsolicitud ) AS pago_aut ON pago_aut.idsolicitud = solpagos.idsolicitud  INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa LEFT JOIN ( SELECT *, MAX(autpagos.fecreg), COUNT(autpagos.idsolicitud) tpagos FROM autpagos GROUP BY autpagos.idsolicitud ) AS autpagos ON autpagos.idsolicitud = solpagos.idsolicitud LEFT JOIN ( SELECT polizas_provision.idprovision, polizas_provision.idsolicitud, MIN( polizas_provision.fecreg ) FROM polizas_provision GROUP BY polizas_provision.idsolicitud ) AS polizas_provision ON polizas_provision.idsolicitud = solpagos.idsolicitud WHERE ( solpagos.nomdepto = '" . $this->session->userdata("inicio_sesion")['depto'] . "'  OR solpagos.idusuario IN ( SELECT usuarios.idusuario FROM usuarios WHERE FIND_IN_SET('".$this->session->userdata("inicio_sesion")['id']."', usuarios.sup ) ) ) AND solpagos.idetapa IN ( 10, 11, 14 ) ORDER BY solpagos.idetapa, solpagos.fechaCreacion DESC");     
                }
                break;
            case 'CJ':
            case 'CA':
            case 'CE':
                return $this->db->query("SELECT IFNULL(facturas.nombre_archivo, 'NA') tienefac, solpagos.programado, facturas.tipo_factura, solpagos.idetapa, polizas_provision.idprovision, solpagos.proyecto,
                IF( solpagos.programado IS NOT NULL, CASE WHEN solpagos.programado < 7 THEN TIMESTAMPDIFF(MONTH, CONCAT( YEAR(solpagos.fecelab),'-',MONTH(solpagos.fecelab),'-', DAY(solpagos.fecha_fin) ), IFNULL( solpagos.fecha_fin, DATE_ADD( solpagos.fecelab, INTERVAL IF( solpagos.idetapa NOT IN ( 11, 30 ), tpagos + 1, IFNULL(tpagos, 1) )  MONTH) ) ) ELSE TIMESTAMPDIFF(WEEK, solpagos.fecelab, IFNULL(solpagos.fecha_fin, DATE_ADD( solpagos.fecelab, INTERVAL IFNULL( tpagos, 1 )  WEEK) ) ) END, NULL ) mpagar,
                autpagos.estatus_pago, solpagos.idetapa, IFNULL( facturas.foliofac, solpagos.folio) folio, solpagos.moneda, SUBSTRING(facturas.uuid, -5, 5) AS folifis, solpagos.metoPago, director.nombredir, capturista.nombre_completo, solpagos.caja_chica, solpagos.idsolicitud, solpagos.justificacion, solpagos.nomdepto, proveedores.nombre, solpagos.cantidad, empresas.abrev, solpagos.fechaCreacion AS feccrea, solpagos.fecha_autorizacion AS fecha_autorizacion, solpagos.fecelab AS fecelab, IFNULL(autpagos.fautorizado, ' - ') fautorizado, etapas.nombre AS etapa, IFNULL(autpagos.autorizado, 0) AS autorizado, IFNULL(autpagos.pagado, 0) AS pagado, IFNULL(autpagos.fdispersion, ' - ') fechaDis2 FROM solpagos INNER JOIN ( SELECT usuarios.idusuario, CONCAT( usuarios.nombres,' ', usuarios.apellidos ) AS nombre_completo FROM usuarios ) AS capturista ON capturista.idusuario = solpagos.idusuario LEFT JOIN ( SELECT CONCAT( usuarios.nombres,' ',usuarios.apellidos ) AS nombredir, usuarios.idusuario FROM usuarios ) AS director ON director.idusuario = solpagos.idResponsable INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor INNER JOIN etapas ON etapas.idetapa = solpagos.idetapa LEFT JOIN (SELECT *, MIN(facturas.feccrea) FROM facturas WHERE facturas.tipo_factura IN ( 1, 3 ) GROUP BY facturas.idsolicitud) AS facturas ON facturas.idsolicitud = solpagos.idsolicitud INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa LEFT JOIN ( SELECT autpagos.idsolicitud, autpagos.estatus estatus_pago, SUM( IF( autpagos.estatus != 2, autpagos.cantidad, 0 ) ) pagado, SUM( autpagos.cantidad ) autorizado, MAX( autpagos.fecreg ) fautorizado, MAX( autpagos.fechaDis ) fdispersion, COUNT(autpagos.idsolicitud) tpagos FROM autpagos GROUP BY autpagos.idsolicitud ) AS autpagos ON autpagos.idsolicitud = solpagos.idsolicitud LEFT JOIN ( SELECT polizas_provision.idprovision, polizas_provision.idsolicitud, MIN( polizas_provision.fecreg ) FROM polizas_provision GROUP BY polizas_provision.idsolicitud ) AS polizas_provision ON polizas_provision.idsolicitud = solpagos.idsolicitud WHERE /*( solpagos.caja_chica IS NULL OR solpagos.caja_chica != 1 ) AND*/( solpagos.idusuario = '".$this->session->userdata("inicio_sesion")['id']."' OR solpagos.idusuario IN ( SELECT usuarios.idusuario FROM usuarios WHERE FIND_IN_SET('".$this->session->userdata("inicio_sesion")['id']."', usuarios.sup ) ) ) AND solpagos.idetapa IN ( 10, 11, 14 ) ORDER BY solpagos.idetapa, solpagos.fechaCreacion DESC");
                break;
            case 'AD':
            case 'FP':
                if( ( isset( $this->session->userdata('inicio_sesion')["im"] ) && $this->session->userdata('inicio_sesion')["im"] == 3 ) || $this->session->userdata('inicio_sesion')["im"] == NULL ){
                    
                    $filtro = str_replace("solpagos.fechaCreacion", "solicitudes_sin_factura.feccrea", $filtro);
                    
                    $original = ["solpagos", "empresa", "solpagos","facturas","proveedores","solpagos"];
                    $filtro = str_replace($original, "solicitudes_sin_factura", $filtro);
                                        
                    return $this->db->query("SELECT * FROM solicitudes_sin_factura WHERE solicitudes_sin_factura.nomdepto IN ( 'DEVOLUCIONES', 'TRASPASO' )$filtro 
                    AND solicitudes_sin_factura.idetapa NOT IN ( 1, 30, 0 ) ");   
                }else
                    return $this->db->query("SELECT IFNULL(facturas.nombre_archivo, 'NA') tienefac, solpagos.programado, facturas.tipo_factura, solpagos.idetapa, polizas_provision.idprovision, solpagos.proyecto,
                    IF( solpagos.programado IS NOT NULL, CASE WHEN solpagos.programado < 7 THEN TIMESTAMPDIFF(MONTH, CONCAT( YEAR(solpagos.fecelab),'-',MONTH(solpagos.fecelab),'-', DAY(solpagos.fecha_fin) ), IFNULL( solpagos.fecha_fin, DATE_ADD( solpagos.fecelab, INTERVAL IF( solpagos.idetapa NOT IN ( 11, 30 ), tpagos + 1, IFNULL(tpagos, 1) )  MONTH) ) ) ELSE TIMESTAMPDIFF(WEEK, solpagos.fecelab, IFNULL(solpagos.fecha_fin, DATE_ADD( solpagos.fecelab, INTERVAL IFNULL( tpagos, 1 )  WEEK) ) ) END, NULL ) mpagar,
                    autpagos.estatus_pago, solpagos.idetapa, IFNULL( facturas.foliofac, solpagos.folio) folio, solpagos.moneda, SUBSTRING(facturas.uuid, -5, 5) AS folifis, solpagos.metoPago, director.nombredir, capturista.nombre_completo, solpagos.caja_chica, solpagos.idsolicitud, solpagos.justificacion, solpagos.nomdepto, proveedores.nombre, solpagos.cantidad, empresas.abrev, solpagos.fechaCreacion AS feccrea, solpagos.fecha_autorizacion AS fecha_autorizacion, solpagos.fecelab AS fecelab, IFNULL(autpagos.fautorizado, ' - ') fautorizado,IFNULL(autpagos.fechapago, ' - ') fechapago, etapas.nombre AS etapa, IFNULL(autpagos.autorizado, 0) AS autorizado, IFNULL(autpagos.pagado, 0) AS pagado, IFNULL(autpagos.fdispersion, ' - ') fechaDis2 FROM solpagos INNER JOIN ( SELECT usuarios.idusuario, CONCAT( usuarios.nombres,' ', usuarios.apellidos ) AS nombre_completo FROM usuarios ) AS capturista ON capturista.idusuario = solpagos.idusuario LEFT JOIN ( SELECT CONCAT( usuarios.nombres,' ',usuarios.apellidos ) AS nombredir, usuarios.idusuario FROM usuarios ) AS director ON director.idusuario = solpagos.idResponsable INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor INNER JOIN etapas ON etapas.idetapa = solpagos.idetapa LEFT JOIN (SELECT *, MIN(facturas.feccrea) FROM facturas WHERE facturas.tipo_factura IN ( 1, 3 )$filtro GROUP BY facturas.idsolicitud) AS facturas ON facturas.idsolicitud = solpagos.idsolicitud INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa LEFT JOIN ( SELECT autpagos.idsolicitud, autpagos.estatus estatus_pago, SUM( IF( autpagos.estatus != 2, autpagos.cantidad, 0 ) ) pagado, SUM( autpagos.cantidad ) autorizado, MAX( autpagos.fecreg ) fautorizado, MAX(`cpp`.`autpagos`.`fecha_cobro`) AS `fechapago`, MAX( autpagos.fechaDis ) fdispersion, COUNT(autpagos.idsolicitud) tpagos FROM autpagos GROUP BY autpagos.idsolicitud ) AS autpagos ON autpagos.idsolicitud = solpagos.idsolicitud LEFT JOIN ( SELECT polizas_provision.idprovision, polizas_provision.idsolicitud, MIN( polizas_provision.fecreg ) FROM polizas_provision GROUP BY polizas_provision.idsolicitud ) AS polizas_provision ON polizas_provision.idsolicitud = solpagos.idsolicitud WHERE /*( solpagos.caja_chica IS NULL OR solpagos.caja_chica != 1 ) AND*/( solpagos.idusuario = '".$this->session->userdata("inicio_sesion")['id']."' OR solpagos.idusuario IN ( SELECT usuarios.idusuario FROM usuarios WHERE FIND_IN_SET('".$this->session->userdata("inicio_sesion")['id']."', usuarios.sup ) ) ) AND solpagos.idetapa IN ( 10, 11, 14 ) ORDER BY solpagos.idetapa, solpagos.fechaCreacion DESC");
                break;
            case 'CX':
                return $this->db->query("SELECT facturas.nombre_archivo tienefac, solpagos.programado, facturas.tipo_factura, solpagos.idetapa, polizas_provision.idprovision, 
                IF( solpagos.programado IS NOT NULL, CASE WHEN solpagos.programado < 7 THEN TIMESTAMPDIFF(MONTH, CONCAT( YEAR(solpagos.fecelab),'-',MONTH(solpagos.fecelab),'-', DAY(solpagos.fecha_fin) ),solpagos.fecelab),'-',MONTH(solpagos.fecelab),'-', DAY(solpagos.fecha_fin) ), IFNULL( solpagos.fecha_fin, DATE_ADD( solpagos.fecelab, INTERVAL IF( solpagos.idetapa NOT IN ( 11, 30 ), tpagos + 1, IFNULL(tpagos, 1) )  MONTH) ) ) ELSE TIMESTAMPDIFF(WEEK, solpagos.fecelab, IFNULL(solpagos.fecha_fin, DATE_ADD( solpagos.fecelab, INTERVAL IFNULL( tpagos, 1 )  WEEK) ) ) END, NULL ) mpagar,
                autpagos.estatus_pago, 
                solpagos.idetapa, 
                IFNULL( facturas.foliofac, solpagos.folio) folio, 
                solpagos.moneda, SUBSTRING(facturas.uuid, -5, 5) AS folifis, 
                solpagos.metoPago, director.nombredir, 
                capturista.nombre_completo, 
                solpagos.caja_chica, 
                solpagos.idsolicitud, 
                solpagos.justificacion, 
                solpagos.nomdepto, 
                proveedores.nombre, 
                solpagos.cantidad, 
                empresas.abrev, 
                solpagos.fechaCreacion feccrea, 
                solpagos.fecha_autorizacion fecha_autorizacion, 
                solpagos.fecelab fecelab, 
                autpagos.fautorizado fautorizado, 
                etapas.nombre AS etapa, IFNULL(autpagos.autorizado, 0) AS autorizado, IFNULL(autpagos.pagado, 0) AS pagado, 
                autpagos.fdispersion fechaDis2 
                FROM solpagos INNER JOIN ( SELECT usuarios.idusuario, CONCAT( usuarios.nombres,' ', usuarios.apellidos ) AS nombre_completo FROM usuarios ) AS capturista ON capturista.idusuario = solpagos.idusuario LEFT JOIN ( SELECT CONCAT( usuarios.nombres,' ',usuarios.apellidos ) AS nombredir, usuarios.idusuario FROM usuarios ) AS director ON director.idusuario = solpagos.idResponsable INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor INNER JOIN etapas ON etapas.idetapa = solpagos.idetapa LEFT JOIN (SELECT *, MIN(facturas.feccrea) FROM facturas WHERE facturas.tipo_factura IN ( 1, 3 ) GROUP BY facturas.idsolicitud) AS facturas ON facturas.idsolicitud = solpagos.idsolicitud INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa LEFT JOIN ( SELECT autpagos.idsolicitud, autpagos.estatus estatus_pago, SUM( IF( autpagos.estatus != 2, autpagos.cantidad, 0 ) ) pagado, SUM( autpagos.cantidad ) autorizado, MAX( autpagos.fecreg ) fautorizado, MAX( autpagos.fechaDis ) fdispersion, COUNT(autpagos.idsolicitud) tpagos FROM autpagos GROUP BY autpagos.idsolicitud ) AS autpagos ON autpagos.idsolicitud = solpagos.idsolicitud LEFT JOIN ( SELECT polizas_provision.idprovision, polizas_provision.idsolicitud, MIN( polizas_provision.fecreg ) FROM polizas_provision GROUP BY polizas_provision.idsolicitud ) AS polizas_provision ON polizas_provision.idsolicitud = solpagos.idsolicitud WHERE ( solpagos.caja_chica IS NULL OR solpagos.caja_chica != 1 ) AND ( solpagos.idusuario = '".$this->session->userdata("inicio_sesion")['id']."' OR FIND_IN_SET( solpagos.idEmpresa, '".$this->session->userdata("inicio_sesion")['depto']."' ) ) AND solpagos.idetapa IN ( 10, 11, 14 ) ORDER BY solpagos.idetapa, solpagos.fechaCreacion DESC");
                break;
            case 'CI':
                return $this->db->query("SELECT * FROM solicitudes_sin_factura WHERE ((solicitudes_sin_factura.nomdepto LIKE 'DEVOLUC%'
                AND solicitudes_sin_factura.proyecto != 'DEVOLUCION DOMICILIACION')
                OR solicitudes_sin_factura.proyecto = 'TRASPASO - DEVOLUCION')
                AND solicitudes_sin_factura.idetapa IN (10 , 11, 14)");
                break;
            case 'CT':
                return $this->db->query("SELECT * FROM solicitudes_sin_factura WHERE solicitudes_sin_factura.nomdepto NOT IN ( 'NOMINAS', 'TRASPASO', 'DEVOLUCIONES', 'FINIQUITO', 'FINIQUITO POR PARCIALIDAD', 'FINIQUITO POR PARCIALIDAD', 'FINIQUITO POR RENUNCIA' ) AND FIND_IN_SET( solicitudes_sin_factura.idempresa, '".$this->session->userdata("inicio_sesion")['depto']."' ) AND solicitudes_sin_factura.idetapa NOT IN ( 0, 1, 2, 3, 30 ) AND ( caja_chica IS NULL OR caja_chica = 0 )");
                break;
            default:
                return $this->db->query("SELECT * FROM solpagos_cajas_chicas_TODO");
                break;
        }
    }

    function abonos_solM($idsol){
        return $this->db->query("SELECT a.idpago,a.idsolicitud,a.idrealiza,a.cantidad,u.nombres,u.apellidos FROM autpagos a JOIN usuarios u ON u.idusuario=a.idrealiza 
        WHERE a.referencia='ABONO' AND a.formaPago in('TEA','MAN') and a.idsolicitud=$idsol");
    }

    function regmod_abonoM($POST){
        if( in_array( $this->session->userdata("inicio_sesion")['rol'], array( 'CP' )) ){
            $POST["justificacion"]=$POST["razon_abono"];
            $POST["cantidad_abonada"]=str_replace(["$",","],"",$POST["cantidad_abonada"]);
            
    
            $new_justificacion = str_replace(array("/","-",".",",","_","","%20")," ",(TRIM($POST["justificacion"])));

            
            if($POST["accion"]=="reg"){
                $data=array("idsolicitud"=>$POST["idsolicitud"], "idfactura"=>null, "idrealiza"=>$this->session->userdata("inicio_sesion")['id'], "cantidad"=>$POST["cantidad_abonada"], "fecreg"=>'0000-00-00 00:00:00'
                , "fechacp"=>NULL, "estatus"=>16, "estatus_factura"=>NULL, "reg_factura"=>NULL, "tipoPago"=>NULL, "formaPago"=>"MAN", "referencia"=>'ABONO', "fechaOpe"=>'0000-00-00', "tipoCambio"=>null, 
                "fecha_pago"=>'0000-00-00', "descarga"=>null, "fechDesc"=>null, "fechaDis"=>null, "motivoEspera"=>"", "fecha_cobro"=>null);
                $this->db->insert("autpagos",$data);
            }else if($POST["accion"]=="mod"){
                $data=array("cantidad"=>$POST["cantidad_abonada"]);
                $this->db->update("autpagos",$data,array("idpago"=>$POST["idpago"]));
            }
            
            $this->db->query("UPDATE solpagos INNER JOIN ( SELECT autpagos.idsolicitud, SUM( autpagos.cantidad ) AS pagado FROM autpagos GROUP BY autpagos.idsolicitud ) AS pagado ON pagado.idsolicitud = solpagos.idsolicitud SET solpagos.idetapa = CASE WHEN solpagos.cantidad - pagado.pagado > 0 THEN 9 WHEN ( solpagos.idsolicitud NOT IN (SELECT idsolicitud FROM facturas WHERE tipo_factura = 3) AND solpagos.idsolicitud IN (SELECT idsolicitud FROM facturas WHERE tipo_factura = 1) ) OR ( solpagos.tendrafac = 1 AND solpagos.idsolicitud NOT IN (SELECT idsolicitud FROM facturas ) )THEN 10 ELSE 11 END WHERE solpagos.idsolicitud IN (".$POST["idsolicitud"].")");

            chat( $POST["idsolicitud"], $new_justificacion, $this->session->userdata("inicio_sesion")['id'] );
            if($POST["accion"]=="reg")
                log_sistema($this->session->userdata("inicio_sesion")['id'], $POST["idsolicitud"], "AGREGÓ ABONO DESDE HISTORIAL, $ ".number_format( $POST["cantidad_abonada"], 2, ".", "," ) );
            if($POST["accion"]=="mod")
                log_sistema($this->session->userdata("inicio_sesion")['id'], $POST["idsolicitud"], "EDITÓ EL ABONO $POST[idpago] DESDE HISTORIAL, DE $ ".number_format( $POST["abono_old"], 2, ".", "," )." A $".number_format( $POST["cantidad_abonada"], 2, ".", "," ) );
            $datos_sol = $this->db->query("SELECT  solpagos.cantidad, IFNULL(liquidado.pagado, 0) AS pagado FROM solpagos LEFT JOIN ( SELECT *, autpagos.estatus AS estatus_pago, MAX(autpagos.fecreg) FROM autpagos GROUP BY autpagos.idsolicitud ) AS pago_generado ON pago_generado.idsolicitud = solpagos.idsolicitud INNER JOIN ( SELECT usuarios.idusuario, CONCAT( usuarios.nombres,' ', usuarios.apellidos ) AS nombre_completo FROM usuarios ) AS capturista ON capturista.idusuario = solpagos.idusuario LEFT JOIN (SELECT autpagos.idsolicitud, SUM( autpagos.cantidad ) AS pagado FROM autpagos GROUP BY autpagos.idsolicitud) AS liquidado ON solpagos.idsolicitud = liquidado.idsolicitud where solpagos.idsolicitud IN (".$POST["idsolicitud"].")"); 
            return $datos_sol->result_array();
        }
    }

    function elimina_abonoM($POST){
        $query=$this->db->query("delete from autpagos where idpago='$POST[idpago]'");
        if($query)
            log_sistema($this->session->userdata("inicio_sesion")['id'], $POST["idsolicitud"], "ELIMINÓ ABONO '$POST[idpago]' DESDE HISTORIAL POR: $".number_format( $POST["cantidad_abonada"], 2, ".", "," ) );
        $datos_sol = $this->db->query("SELECT solpagos.cantidad, IFNULL(liquidado.pagado, 0) AS pagado FROM solpagos LEFT JOIN ( SELECT *, autpagos.estatus AS estatus_pago, MAX(autpagos.fecreg) FROM autpagos GROUP BY autpagos.idsolicitud ) AS pago_generado ON pago_generado.idsolicitud = solpagos.idsolicitud INNER JOIN ( SELECT usuarios.idusuario, CONCAT( usuarios.nombres,' ', usuarios.apellidos ) AS nombre_completo FROM usuarios ) AS capturista ON capturista.idusuario = solpagos.idusuario LEFT JOIN (SELECT autpagos.idsolicitud, SUM( autpagos.cantidad ) AS pagado FROM autpagos GROUP BY autpagos.idsolicitud) AS liquidado ON solpagos.idsolicitud = liquidado.idsolicitud where solpagos.idsolicitud IN (".$POST["idsolicitud"].")"); 
        return $datos_sol->result_array();
    }
}
