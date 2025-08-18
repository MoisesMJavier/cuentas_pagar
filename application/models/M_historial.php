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
        return $this->db->query("SELECT 
            solpagos.idusuario, 
            solpagos.idsolicitud, 
            solpagos.folio, 
            solpagos.moneda, 
            solpagos.justificacion,
            empresas.abrev, 
            solpagos.nomdepto, 
            proveedores.nombre AS nombre_proveedor, 
            DATE_FORMAT( solpagos.fecelab, '%d/%m/%Y') AS fecelab, 
            DATE_FORMAT( solpagos.fecelab, '%d/%m/%Y') AS fecautorizacion, 
            CONCAT(usuarios.nombres, ' ', usuarios.apellidos) AS nombre_capturista, 
            solpagos.cantidad, 
            IF(spo.idProyectos is null, solpagos.proyecto, pd.nombre) as proyecto,
            solpagos.folio, 
            solpagos.justificacion, 
            IFNULL(SUBSTRING(facturas.uuid, -5, 5 ), 'SF') AS ffiscal 
        FROM solpagos 
        INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa 
        INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor 
        INNER JOIN usuarios ON usuarios.idusuario = solpagos.idusuario 
        LEFT JOIN (SELECT *, MIN(facturas.feccrea) FROM facturas
        WHERE facturas.tipo_factura IN ( 1, 3 ) 
        GROUP BY facturas.idsolicitud) AS facturas ON facturas.idsolicitud = solpagos.idsolicitud
        LEFT JOIN solicitud_proyecto_oficina spo  on spo.idsolicitud = solpagos.idsolicitud  
        LEFT JOIN proyectos_departamentos pd  on spo.idProyectos = pd.idProyectos
        WHERE solpagos.idetapa in (7)  
            AND solpagos.caja_chica = 1 
            AND FIND_IN_SET( solpagos.idsolicitud,  '$idsolicitudes' )
        ORDER BY solpagos.fecha_autorizacion")->result_array();
    }

    function getSolicitudesCerCH( $idsolicitudes ){
        return $this->db->query("SELECT 
            solpagos.idusuario, 
            solpagos.idsolicitud, 
            solpagos.folio, 
            solpagos.moneda, 
            solpagos.justificacion,
            empresas.abrev, 
            solpagos.nomdepto, 
            proveedores.nombre AS nombre_proveedor, 
            DATE_FORMAT( solpagos.fecelab, '%d/%m/%Y') AS fecelab, 
            DATE_FORMAT( solpagos.fecelab, '%d/%m/%Y') AS fecautorizacion, 
            CONCAT(usuarios.nombres, ' ', usuarios.apellidos) AS nombre_capturista, 
            solpagos.cantidad, 
            if(spo.idProyectos is null, solpagos.proyecto, pd.nombre) as proyecto,
            solpagos.folio, 
            solpagos.justificacion, 
            IFNULL(SUBSTRING(facturas.uuid, -5, 5 ), 'SF') AS ffiscal 
        FROM solpagos 
        INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa 
        INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor 
        INNER JOIN usuarios ON usuarios.idusuario = solpagos.idusuario 
        LEFT JOIN (
            SELECT 
                *, 
                MIN(facturas.feccrea) 
            FROM facturas 
            WHERE facturas.tipo_factura IN ( 1, 3 ) 
            GROUP BY facturas.idsolicitud) AS facturas ON facturas.idsolicitud = solpagos.idsolicitud
        left join solicitud_proyecto_oficina spo  on spo.idsolicitud = solpagos.idsolicitud 
        left join proyectos_departamentos pd  on spo.idProyectos = pd.idProyectos
        WHERE solpagos.idetapa in (31) 
            AND solpagos.caja_chica = 1 
            AND FIND_IN_SET( solpagos.idsolicitud, '$idsolicitudes' ) 
        ORDER BY solpagos.fecha_autorizacion")->result_array();
    }

    function getHistorialPagosAdm($finicial = null ,$ffinal = null) { /** FECHA: 26-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
        ini_set('memory_limit', '-1'); /** FECHA: 26-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
        set_time_limit(0); /** FECHA: 26-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
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
                autpagos.estatus,
                IF(spo.idProyectos IS NULL, solpagos.proyecto, pd.nombre) AS proyecto,
                os.nombre oficina,
                tsp.nombre servicioPartida,
                solpagos.justificacion -- FECHA: 26-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com>
                FROM autpagos 
                INNER JOIN solpagos ON autpagos.idsolicitud = solpagos.idsolicitud 
                INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor 
                LEFT JOIN factura_registro facturas ON facturas.idsolicitud = solpagos.idsolicitud 
                INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa
                LEFT JOIN solicitud_proyecto_oficina spo  on spo.idsolicitud = solpagos.idsolicitud 
                LEFT JOIN proyectos_departamentos pd  on spo.idProyectos = pd.idProyectos
                LEFT JOIN oficina_sede os on spo.idOficina = os.idOficina
                left join tipo_servicio_partidas tsp on tsp.idTipoServicioPartida = spo.idTipoServicioPartida 
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
            case 'CE':
            case 'CX':
                return $this->db->query("SELECT -- FECHA: 26-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com>
                        autpagos.estatus AS estapag, 
                        solpagos.idsolicitud, 
                        solpagos.justificacion, 
                        solpagos.etapa, 
                        solpagos.condominio, 
                        solpagos.fecelab AS fecha_factura, 
                        DATE_FORMAT(solpagos.fechaCreacion, '%d/%m/%Y') AS fecha_captura,  
                        solpagos.folio,
                        solpagos.nomdepto, 
                        IFNULL(autpagos.formaPago, solpagos.metoPago) AS metoPago,
                        IF(spo.idProyectos IS NULL, solpagos.proyecto, pd.nombre) AS proyecto,
                        polizas_provision.numpoliza, 
                        capturista.nombre_capturista, 
                        responsable.nombre_responsable, 
                        autpagos.estatus, 
                        DATE_FORMAT(autpagos.fecreg, '%Y/%m/%d') AS fechaaut, 
                        proveedores.nombre,
                        solpagos.cantidad AS solicitado,
                        autpagos.cantidad,
                        solpagos.moneda,
                        COALESCE(
                            CASE 
                                WHEN autpagos.tipoCambio IS NULL AND solpagos.moneda = 'MXN' 
                                THEN autpagos.cantidad 
                                ELSE autpagos.tipoCambio * autpagos.cantidad 
                            END,
                            0
                        ) AS conversion,
                        IFNULL(DATE_FORMAT( IFNULL(autpagos.fechaOpe, autpagos.fecha_cobro), '%d/%m/%Y'), '') AS fechaOpe, 
                        empresas.abrev, 
                        IFNULL(autpagos.referencia, '') AS referencia, 
                        IFNULL(SUBSTR(facturas.uuid, -1, 5), 'SF') AS uuid, 
                        IFNULL(DATE_FORMAT(autpagos.fechaDis, '%Y/%m/%d'),'') AS fecha_dispersion, 
                        IFNULL(DATE_FORMAT(autpagos.fecreg, '%d/%b/%Y'),'') AS fecreg_2,
                        os.nombre AS oficina,
                        tsp.nombre AS servicioPartida
                    FROM autpagos
                    INNER JOIN ( SELECT 
                            idsolicitud,
                            idEmpresa,
                            idusuario,
                            idResponsable,
                            justificacion,
                            etapa,
                            condominio,
                            fecelab,
                            fechaCreacion,
                            folio,
                            moneda,
                            nomdepto,
                            metoPago,
                            proyecto,
                            idProveedor,
                            cantidad
                    FROM solpagos WHERE solpagos.caja_chica IS NULL OR solpagos.caja_chica = 0 AND FIND_IN_SET( solpagos.idEmpresa, ? ) ) solpagos ON autpagos.idsolicitud = solpagos.idsolicitud 
                    INNER JOIN ( SELECT idproveedor, nombre FROM proveedores ) proveedores ON proveedores.idproveedor = solpagos.idProveedor 
                    INNER JOIN ( SELECT usuarios.idusuario, CONCAT( usuarios.nombres, ' ', usuarios.apellidos ) AS nombre_capturista FROM usuarios ) AS capturista ON capturista.idusuario = solpagos.idusuario 
                    INNER JOIN ( SELECT usuarios.idusuario, CONCAT( usuarios.nombres, ' ', usuarios.apellidos ) AS nombre_responsable FROM usuarios ) AS responsable ON responsable.idusuario = solpagos.idResponsable 
                    INNER JOIN ( SELECT idempresa, abrev FROM empresas ) empresas ON empresas.idempresa = solpagos.idEmpresa 
                    LEFT JOIN ( SELECT uuid, idsolicitud FROM facturas WHERE facturas.tipo_factura IN ( 1, 3 ) GROUP BY facturas.idsolicitud HAVING MIN(facturas.feccrea) ) AS facturas ON facturas.idsolicitud = solpagos.idsolicitud 
                    LEFT JOIN ( SELECT idsolicitud, numpoliza FROM polizas_provision GROUP BY polizas_provision.idsolicitud ) polizas_provision ON polizas_provision.idsolicitud = solpagos.idsolicitud   
                    LEFT JOIN solicitud_proyecto_oficina spo  ON spo.idsolicitud = solpagos.idsolicitud 
                    LEFT JOIN proyectos_departamentos pd  ON spo.idProyectos = pd.idProyectos
                    LEFT JOIN oficina_sede os ON spo.idOficina = os.idOficina
                    LEFT JOIN tipo_servicio_partidas tsp ON tsp.idTipoServicioPartida = spo.idTipoServicioPartida
                    WHERE (DATE(autpagos.fecreg) BETWEEN '$finicial 00:00:00' AND '$ffinal 23:59:59') OR (DATE(autpagos.fecha_cobro) BETWEEN '$finicial' AND '$ffinal')
                    ORDER BY autpagos.fecreg DESC"
                    , [ $this->session->userdata("inicio_sesion")['depto'] ]
                ); /** FECHA: 26-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
            break;
            case 'CP':
                return $this->db->query("SELECT solpagos.idsolicitud,
                                                IFNULL(autpagos.referencia, '') referencia,
                                                solpagos.folio, 
                                                IFNULL(SUBSTR(facturas.uuid, -1, 5), 'SF') AS uuid, 
                                                proveedores.nombre, 
                                                autpagos.fechaDis fecha_dispersion, 
                                                autpagos.fecreg fechaaut,
                                                empresas.abrev,
                                                solpagos.nomdepto, 
                                                IF( autpagos.tipoCambio IS NULL, autpagos.cantidad, autpagos.tipoCambio * autpagos.cantidad ) conversion, -- FECHA: 26-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com>
                                                IFNULL(autpagos.formaPago, solpagos.metoPago) metoPago,
                                                solpagos.metoPago,
                                                autpagos.estatus,
                                                IF(spo.idProyectos IS NULL, solpagos.proyecto, pd.nombre) AS proyecto,
                                                os.nombre oficina,
                                                tsp.nombre servicioPartida
                                        FROM autpagos 
                                        INNER JOIN ( SELECT * FROM solpagos WHERE solpagos.caja_chica = 0 AND idetapa NOT IN ( 0, 30 ) ) AS solpagos
                                            ON autpagos.idsolicitud = solpagos.idsolicitud
                                        INNER JOIN ( SELECT idproveedor, nombre FROM proveedores ) AS proveedores
                                            ON proveedores.idproveedor = solpagos.idProveedor 
                                        LEFT JOIN factura_registro AS facturas 
                                            ON facturas.idsolicitud = solpagos.idsolicitud 
                                        INNER JOIN (SELECT idempresa, abrev FROM empresas ) AS empresas 
                                            ON empresas.idempresa = solpagos.idEmpresa
                                        LEFT JOIN solicitud_proyecto_oficina spo
                                            ON spo.idsolicitud = solpagos.idsolicitud 
                                        LEFT JOIN proyectos_departamentos pd
                                            ON spo.idProyectos = pd.idProyectos
                                        LEFT JOIN oficina_sede os
                                            ON spo.idOficina = os.idOficina
                                        LEFT JOIN tipo_servicio_partidas tsp
                                            ON tsp.idTipoServicioPartida = spo.idTipoServicioPartida 
                                        WHERE (DATE(autpagos.fecreg) BETWEEN '$finicial 00:00:00' AND '$ffinal 23:59:59') OR (DATE(autpagos.fecha_cobro) BETWEEN '$finicial' AND '$ffinal')
                                        ORDER BY autpagos.fecreg DESC");
                break;
            default:
                return $this->db->query("SELECT  -- FECHA: 26-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com>
                                            solpagos.idsolicitud,
                                            IFNULL(autpagos.referencia, '') referencia,
                                            solpagos.folio, 
                                            IFNULL(SUBSTR(facturas.uuid, -1, 5), 'SF') AS uuid, 
                                            proveedores.nombre, 
                                            autpagos.fechaDis fecha_dispersion, 
                                            autpagos.fecreg fechaaut,
                                            empresas.abrev,
                                            solpagos.nomdepto,
                                            IFNULL(autpagos.formaPago, solpagos.metoPago) metoPago,
                                            solpagos.metoPago,
                                            autpagos.estatus,
                                            IF(spo.idProyectos IS NULL, solpagos.proyecto, pd.nombre) AS proyecto,
                                            os.nombre oficina,
                                            tsp.nombre servicioPartida,
                                            solpagos.cantidad AS solicitado,
                                            autpagos.cantidad,
                                            solpagos.moneda,
                                            IF(autpagos.tipoCambio IS NULL, autpagos.cantidad, autpagos.tipoCambio * autpagos.cantidad) AS conversion
                                        FROM autpagos 
                                        INNER JOIN ( 
                                            SELECT solpagos.* 
                                            FROM solpagos 
                                            JOIN ( SELECT depto FROM departamento WHERE idusuario = ? ) depto ON depto.depto = solpagos.nomdepto  
                                            -- AND solpagos.nomdepto <> ?
                                            WHERE solpagos.caja_chica = 0 AND idetapa NOT IN ( 0, 30 ) 
                                        )solpagos ON autpagos.idsolicitud = solpagos.idsolicitud
                                        INNER JOIN ( SELECT idproveedor, nombre FROM proveedores ) proveedores ON proveedores.idproveedor = solpagos.idProveedor 
                                        LEFT JOIN factura_registro facturas ON facturas.idsolicitud = solpagos.idsolicitud 
                                        INNER JOIN (SELECT idempresa, abrev FROM empresas ) empresas ON empresas.idempresa = solpagos.idEmpresa
                                        LEFT JOIN solicitud_proyecto_oficina spo  on spo.idsolicitud = solpagos.idsolicitud 
                                        LEFT JOIN proyectos_departamentos pd  on spo.idProyectos = pd.idProyectos
                                        LEFT JOIN oficina_sede os on spo.idOficina = os.idOficina
                                        left join tipo_servicio_partidas tsp on tsp.idTipoServicioPartida = spo.idTipoServicioPartida
                                        WHERE (DATE(autpagos.fecreg) BETWEEN '$finicial 00:00:00' AND '$ffinal 23:59:59') OR (DATE(autpagos.fecha_cobro) BETWEEN '$finicial' AND '$ffinal')
                                        ORDER BY autpagos.fecreg DESC"
                , [ $this->session->userdata("inicio_sesion")['id'], $this->session->userdata("inicio_sesion")['depto'] ]); /** FECHA: 26-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                break;
        }
    }

    function getHistorialTablaSol( $fecha_inicio = "", $fecha_fin = "" ) {
        ini_set('memory_limit','-1');
        /** @author DANTE ALDAIR GUERRERO ALDANA <coordinador6.desarrollo@ciudadmaderas.com> | FECHA: 11-06-2025 | MODIFICACION AL CARGAR DATATABLE*/
        $where = '';
        $etapa = '';
        $inner = '';
        if (in_array($this->session->userdata("inicio_sesion")['id'], ['255', '320']) && $this->input->post("tipo_reporte") == 0) {
            $where = 'UNION ';
            $where .= "SELECT * FROM solpagos WHERE solpagos.metoPago = 'INTERCAMBIO' AND solpagos.idetapa IN (12) AND solpagos.fecelab BETWEEN '$fecha_inicio' AND '$fecha_fin'";
            $etapa .= ', 12';

            if ($this->input->post("tipo_reporte") == 0) {
                $inner = "INNER JOIN vw_autpagos AS vap ON solpagos.idsolicitud = vap.idsolicitud AND vap.estatus_pago = 16";
            }
        }

        switch ($this->session->userdata("inicio_sesion")['id'] == 2685 ? 'DA' : $this->session->userdata("inicio_sesion")['rol']) {
            case 'DA':
            case 'AS':
                    return $this->db->query("SELECT 
                    solpagos.idsolicitud,
                    solpagos.idproceso,
                    IF(spo.idProyectos IS NULL, solpagos.proyecto, pd.nombre) as proyecto, /** FECHA: 10-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                    os.nombre oficina, /** FECHA: 10-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                    tsp.nombre servicioPartida, /** FECHA: 10-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                    empresas.abrev,
                    solpagos.fecelab,
                    solpagos.folio,
                    SUBSTRING(facturas.uuid, -5, 5) folifis,
                    autpagos.upago fech_auto,
                    proveedores.nombre,
                    IF( solpagos.programado IS NOT NULL, autpagos.pagado, solpagos.cantidad ) cantidad,
                    solpagos.metoPago,
                    capturista.nombre_completo,
                    autpagos.estatus_pago,
                    etapas.nombre etapa,
                    solpagos.caja_chica,
                    solpagos.fechaCreacion feccrea,
                    proveedores.idproveedor,
                    solpagos.nomdepto,
                    solpagos.justificacion,
                    notifi.visto,
                    solpagos.financiamiento
                FROM
                    ( 
                        SELECT solpagos.*
                        FROM solpagos
                        JOIN ( SELECT departamento depto FROM departament_usuario WHERE idusuario = ? ) depto 
                            ON solpagos.nomdepto = depto.depto
                        $inner
                        WHERE solpagos.fecelab BETWEEN '$fecha_inicio' AND '$fecha_fin' AND
                            solpagos.caja_chica = ? AND
                            solpagos.idetapa IN (  10 , 11 )
                        UNION  
                        SELECT 
                            solpagos.*
                        FROM
                            solpagos
                        JOIN ( SELECT usuarios.idusuario FROM usuarios WHERE FIND_IN_SET( ?, usuarios.sup ) ) usuario ON usuario.idusuario = solpagos.idusuario
                        WHERE
                            solpagos.fecelab BETWEEN '$fecha_inicio' AND '$fecha_fin' AND
                            solpagos.caja_chica = ? AND
                            solpagos.idetapa IN (  10 , 11 )
                        UNION
                        SELECT 
                            s.* 
                        FROM 
                            solpagos s 
                        JOIN proveedores_usuario p ON p.idproveedor = s.idproveedor AND s.nomdepto = p.nomdepto AND p.idusuario = ?
                        WHERE
                            s.fecelab BETWEEN '$fecha_inicio' AND '$fecha_fin' AND
                            s.caja_chica = ? AND
                            s.idetapa IN (  10 , 11 )

                        $where

                    ) solpagos
                    CROSS JOIN listado_usuarios capturista ON capturista.idusuario = solpagos.idusuario
                    CROSS JOIN ( SELECT idproveedor, nombre FROM proveedores ) proveedores ON proveedores.idproveedor = solpagos.idProveedor
                    CROSS JOIN ( SELECT idetapa, nombre FROM etapas WHERE idetapa IN ( 10 , 11, 14$etapa) ) etapas ON etapas.idetapa = solpagos.idetapa
                    CROSS JOIN ( SELECT idempresa, abrev FROM empresas ) empresas ON empresas.idempresa = solpagos.idEmpresa
                    LEFT JOIN notifi ON notifi.idsolicitud = solpagos.idsolicitud AND notifi.idusuario = ?
                    LEFT JOIN vw_autpagos autpagos ON autpagos.idsolicitud = solpagos.idsolicitud
                    LEFT JOIN vw_autpagos_caja_chica AS autpagos_caja_chica ON autpagos_caja_chica.idsolicitud = solpagos.idsolicitud
                    LEFT JOIN factura_registro facturas ON facturas.idsolicitud = solpagos.idsolicitud
                    LEFT JOIN solicitud_proyecto_oficina spo  on spo.idsolicitud = solpagos.idsolicitud 
                    LEFT JOIN proyectos_departamentos pd  on spo.idProyectos = pd.idProyectos
                    LEFT JOIN oficina_sede os on spo.idOficina = os.idOficina /** FECHA: 10-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                    LEFT JOIN tipo_servicio_partidas tsp on tsp.idTipoServicioPartida = spo.idTipoServicioPartida /** FECHA: 10-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                    ORDER BY solpagos.idetapa , solpagos.fechaCreacion DESC", array(
                    $this->session->userdata("inicio_sesion")['id'],
                    $this->input->post("tipo_reporte"), 
                     
                    $this->session->userdata("inicio_sesion")['id'],                    
                    $this->input->post("tipo_reporte"), 
 
                    $this->session->userdata("inicio_sesion")['id'],
                    $this->input->post("tipo_reporte"), 
                    
                    $this->session->userdata("inicio_sesion")['id'] ) ); 
                break;
            case 'CJ':
            case 'CA':
            case 'CI':
                return $this->db->query("SELECT 
                    solpagos.idsolicitud,
                    solpagos.idproceso,
                    IF(spo.idProyectos IS NULL, solpagos.proyecto, pd.nombre) as proyecto, /** FECHA: 10-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                    os.nombre oficina, /** FECHA: 10-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                    tsp.nombre servicioPartida, /** FECHA: 10-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                    empresas.abrev,
                    solpagos.fecelab,
                    solpagos.folio,
                    SUBSTRING(facturas.uuid, -5, 5) folifis,
                    autpagos.upago fech_auto,
                    proveedores.nombre,
                    IF( solpagos.programado IS NOT NULL, autpagos.pagado, solpagos.cantidad ) cantidad,
                    solpagos.metoPago,
                    capturista.nombre_completo,
                    autpagos.estatus_pago,
                    etapas.nombre etapa,
                    solpagos.caja_chica,
                    solpagos.fechaCreacion feccrea,
                    proveedores.idproveedor,
                    solpagos.nomdepto,
                    solpagos.justificacion,
                    notifi.visto
                FROM
                    ( 
                        SELECT 
                            solpagos.*
                        FROM
                            solpagos
                        WHERE
                            solpagos.idusuario = ? AND
                            solpagos.fecelab BETWEEN '$fecha_inicio' AND '$fecha_fin' AND
                            solpagos.caja_chica = ? AND
                            solpagos.idetapa IN (  10 , 11 )
                        UNION  
                        SELECT 
                            solpagos.*
                        FROM
                            solpagos
                        JOIN ( SELECT usuarios.idusuario FROM usuarios WHERE FIND_IN_SET( ?, usuarios.sup ) ) usuario ON usuario.idusuario = solpagos.idusuario
                        WHERE
                            solpagos.fecelab BETWEEN '$fecha_inicio' AND '$fecha_fin' AND
                            solpagos.caja_chica = ? AND
                            solpagos.idetapa IN (  10 , 11 )
                        UNION
                        SELECT 
                            s.* 
                        FROM 
                            solpagos s 
                        JOIN proveedores_usuario p ON p.idproveedor = s.idproveedor AND s.nomdepto = p.nomdepto AND p.idusuario = ?
                        WHERE
                            s.fecelab BETWEEN '$fecha_inicio' AND '$fecha_fin' AND
                            s.caja_chica = ? AND
                            s.idetapa IN (  10 , 11 )

                    ) solpagos
                    CROSS JOIN listado_usuarios capturista ON capturista.idusuario = solpagos.idusuario
                    CROSS JOIN ( SELECT idproveedor, nombre FROM proveedores ) proveedores ON proveedores.idproveedor = solpagos.idProveedor
                    CROSS JOIN ( SELECT idetapa, nombre FROM etapas WHERE idetapa IN ( 10 , 11, 14) ) etapas ON etapas.idetapa = solpagos.idetapa
                    CROSS JOIN ( SELECT idempresa, abrev FROM empresas ) empresas ON empresas.idempresa = solpagos.idEmpresa
                    LEFT JOIN notifi ON notifi.idsolicitud = solpagos.idsolicitud AND notifi.idusuario = ?
                    LEFT JOIN vw_autpagos autpagos ON autpagos.idsolicitud = solpagos.idsolicitud
                    LEFT JOIN vw_autpagos_caja_chica AS autpagos_caja_chica ON autpagos_caja_chica.idsolicitud = solpagos.idsolicitud
                    LEFT JOIN factura_registro facturas ON facturas.idsolicitud = solpagos.idsolicitud
                    LEFT JOIN solicitud_proyecto_oficina spo  on spo.idsolicitud = solpagos.idsolicitud 
                    LEFT JOIN proyectos_departamentos pd  on spo.idProyectos = pd.idProyectos
                    LEFT JOIN oficina_sede os on spo.idOficina = os.idOficina /** FECHA: 10-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/ /** FECHA: 10-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                    LEFT JOIN tipo_servicio_partidas tsp on tsp.idTipoServicioPartida = spo.idTipoServicioPartida /** FECHA: 10-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/ /** FECHA: 10-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                ORDER BY solpagos.idetapa , solpagos.fechaCreacion DESC", array( 
                    $this->session->userdata("inicio_sesion")['id'],
                    $this->input->post("tipo_reporte"), 
                    
                    $this->session->userdata("inicio_sesion")['id'],                    
                    $this->input->post("tipo_reporte"), 

                    $this->session->userdata("inicio_sesion")['id'],
                    $this->input->post("tipo_reporte"), 
                    
                    $this->session->userdata("inicio_sesion")['id'] ) ); 
                break;
            case 'FP':
                return $this->db->query("SELECT
                solpagos.idsolicitud,
                solpagos.idproceso, 
                empresas.abrev, 
                IF(spo.idProyectos IS NULL, solpagos.proyecto, pd.nombre) as proyecto, /** FECHA: 10-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/ /** FECHA: 10-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                os.nombre oficina, /** FECHA: 10-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/ /** FECHA: 10-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                tsp.nombre servicioPartida, /** FECHA: 10-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/ /** FECHA: 10-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
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
                LEFT JOIN vw_autpagos_caja_chica AS autpagos_caja_chica ON autpagos_caja_chica.idsolicitud = solpagos.idsolicitud
                LEFT JOIN solicitud_proyecto_oficina spo  on spo.idsolicitud = solpagos.idsolicitud 
                LEFT JOIN proyectos_departamentos pd  on spo.idProyectos = pd.idProyectos
                LEFT JOIN oficina_sede os on spo.idOficina = os.idOficina /** FECHA: 10-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                LEFT JOIN tipo_servicio_partidas tsp on tsp.idTipoServicioPartida = spo.idTipoServicioPartida /** FECHA: 10-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                LEFT JOIN ( SELECT notificaciones.idsolicitud, notificaciones.visto FROM notificaciones WHERE notificaciones.idusuario = '".$this->session->userdata("inicio_sesion")['id']."' GROUP BY notificaciones.idsolicitud ) AS notifi ON notifi.idsolicitud = solpagos.idsolicitud 
                WHERE ( solpagos.idusuario = '".$this->session->userdata("inicio_sesion")['id']."' OR solpagos.idusuario IN ( SELECT usuarios.idusuario FROM usuarios WHERE FIND_IN_SET('".$this->session->userdata("inicio_sesion")['id']."', usuarios.sup ) ) ) 
                AND solpagos.idetapa IN ( 10, 11, 14 ) 
                ORDER BY solpagos.idetapa, solpagos.fechaCreacion DESC");
                break;
            default:
                //return $this->db->query("SELECT IF(facturas.uuid IS null, 'NO', 'SI') AS tienefac, facturas.tipo_factura, solpagos.idetapa, polizas_provision.idprovision, pago_generado.estatus_pago, solpagos.idetapa, solpagos.folio, solpagos.moneda, SUBSTRING(facturas.uuid, -5, 5) AS folifis, solpagos.metoPago, director.nombredir, capturista.nombre_completo, solpagos.caja_chica, solpagos.idsolicitud, solpagos.justificacion, solpagos.nomdepto, proveedores.nombre, solpagos.cantidad, empresas.abrev, DATE_FORMAT(solpagos.fechaCreacion, '%d/%m/%Y') AS feccrea, DATE_FORMAT(solpagos.fecha_autorizacion, '%d/%b/%Y') AS fecha_autorizacion, DATE_FORMAT(solpagos.fecelab, '%d/%m/%Y') AS fecelab, etapas.nombre AS etapa, IFNULL(pago_aut.autorizado, 0) AS autorizado,   IFNULL(liquidado.pagado, 0) AS pagado,   DATE_FORMAT(autpagos.fechaDis, '%d/%b/%Y') as fechaDis2 ,DATE_FORMAT(solpagos.fecha_autorizacion, '%d/%b/%Y') AS fech_auto  FROM solpagos LEFT JOIN ( SELECT *, autpagos.estatus AS estatus_pago, MAX(autpagos.fecreg) FROM autpagos GROUP BY autpagos.idsolicitud ) AS pago_generado ON pago_generado.idsolicitud = solpagos.idsolicitud INNER JOIN ( SELECT usuarios.idusuario, CONCAT( usuarios.nombres,' ', usuarios.apellidos ) AS nombre_completo FROM usuarios ) AS capturista ON capturista.idusuario = solpagos.idusuario LEFT JOIN (SELECT autpagos.idsolicitud, SUM( autpagos.cantidad ) AS pagado FROM autpagos GROUP BY autpagos.idsolicitud) AS liquidado ON solpagos.idsolicitud = liquidado.idsolicitud LEFT JOIN ( SELECT CONCAT( usuarios.nombres,' ',usuarios.apellidos ) AS nombredir, usuarios.idusuario FROM usuarios ) AS director ON director.idusuario = solpagos.idResponsable INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor INNER JOIN etapas ON etapas.idetapa = solpagos.idetapa LEFT JOIN (SELECT *, MIN(facturas.feccrea) FROM facturas WHERE facturas.tipo_factura IN ( 1, 3 ) GROUP BY facturas.idsolicitud) AS facturas ON facturas.idsolicitud = solpagos.idsolicitud LEFT JOIN ( SELECT autpagos.idsolicitud, SUM(autpagos.cantidad) AS autorizado FROM autpagos WHERE autpagos.estatus NOT IN (2) GROUP BY autpagos.idsolicitud ) AS pago_aut ON pago_aut.idsolicitud = solpagos.idsolicitud  INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa LEFT JOIN ( SELECT *, MAX(autpagos.fecreg) FROM autpagos GROUP BY autpagos.idsolicitud ) AS autpagos ON autpagos.idsolicitud = solpagos.idsolicitud LEFT JOIN ( SELECT polizas_provision.idprovision, polizas_provision.idsolicitud, MIN( polizas_provision.fecreg ) FROM polizas_provision GROUP BY polizas_provision.idsolicitud ) AS polizas_provision ON polizas_provision.idsolicitud = solpagos.idsolicitud WHERE ( solpagos.idetapa = 2 OR solpagos.idetapa >= 5 ) ORDER BY solpagos.idetapa, solpagos.fechaCreacion DESC");
                return $this->db->query("SELECT 
                    facturas.nombre_archivo tienefac,
                    solpagos.idproceso,
                    facturas.tipo_factura,
                    solpagos.idetapa,
                    IF(spo.idProyectos IS NULL, solpagos.proyecto, pd.nombre) as proyecto, /** FECHA: 10-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                    os.nombre oficina, /** FECHA: 10-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                    tsp.nombre servicioPartida, /** FECHA: 10-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
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
                FROM solpagos
                INNER JOIN (SELECT usuarios.idusuario, CONCAT(usuarios.nombres, ' ', usuarios.apellidos) AS nombre_completo
                            FROM usuarios) AS capturista 
                    ON capturista.idusuario = solpagos.idusuario
                LEFT JOIN ( SELECT CONCAT(usuarios.nombres, ' ', usuarios.apellidos) AS nombredir, usuarios.idusuario
                            FROM usuarios) AS director 
                    ON director.idusuario = solpagos.idResponsable
                INNER JOIN proveedores 
                    ON proveedores.idproveedor = solpagos.idProveedor
                INNER JOIN etapas
                    ON etapas.idetapa = solpagos.idetapa
                LEFT JOIN ( SELECT *, MIN(facturas.feccrea)
                            FROM facturas
                            WHERE facturas.tipo_factura IN (1 , 3)
                            GROUP BY facturas.idsolicitud) AS facturas
                    ON facturas.idsolicitud = solpagos.idsolicitud
                INNER JOIN empresas
                    ON empresas.idempresa = solpagos.idEmpresa
                LEFT JOIN (SELECT   autpagos.idsolicitud,
                                    COUNT(autpagos.idsolicitud) tpagos,
                                    autpagos.estatus estatus_pago,
                                    SUM(IF(autpagos.estatus != 2, autpagos.cantidad, 0)) pagado,
                                    SUM(autpagos.cantidad) autorizado,
                                    MAX(autpagos.fecreg) fautorizado,
                                    MAX(autpagos.fechaDis) fdispersion
                            FROM autpagos
                            GROUP BY autpagos.idsolicitud) AS autpagos
                    ON autpagos.idsolicitud = solpagos.idsolicitud
                LEFT JOIN (SELECT   polizas_provision.idprovision,
                                    polizas_provision.idsolicitud,
                                    MIN(polizas_provision.fecreg)
                            FROM polizas_provision
                            GROUP BY polizas_provision.idsolicitud) AS polizas_provision 
                    ON polizas_provision.idsolicitud = solpagos.idsolicitud
                LEFT JOIN solicitud_proyecto_oficina spo  on spo.idsolicitud = solpagos.idsolicitud  /** FECHA: 10-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                LEFT JOIN proyectos_departamentos pd  on spo.idProyectos = pd.idProyectos /** FECHA: 10-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                LEFT JOIN oficina_sede os on spo.idOficina = os.idOficina /** FECHA: 10-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                LEFT JOIN tipo_servicio_partidas tsp on tsp.idTipoServicioPartida = spo.idTipoServicioPartida /** FECHA: 10-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                WHERE ( solpagos.caja_chica IS NULL OR solpagos.caja_chica != 0 ) AND 
                        solpagos.idetapa NOT IN (1, 3, 25, 30, 42, 45, 47, 49) /*AND ( solpagos.caja_chica = 0 OR solpagos.caja_chica IS NULL )*/
                ORDER BY solpagos.idetapa , solpagos.fechaCreacion DESC");
                break;
        }
    }

    //HISTORIAL DE CONTABILIDAD PROVEEDOR / CAJA CHICA
    function getTHistorialConta( $tipo_gasto = 0, $fecInicio = "", $fecFin = ""  ){
        ini_set('memory_limit', '-1');

        $filtro = "AND sp.fechaCreacion BETWEEN '" . $fecInicio . "' AND '" . $fecFin . "'";
        
        if( in_array( $this->session->userdata("inicio_sesion")['rol'], array( 'CC' ) ))
            $filtro .= " AND nomdepto NOT IN ( 
                'DEVOLUCIONES', 
                'TRASPASO',
                'CESION OOAM',
                'RESCISION OOAM',
                'DEVOLUCION OOAM',
                'TRASPASO OOAM',
                'DEVOLUCION DOM OOAM',
                'INFORMATIVA'
            ) ";

        if (in_array($this->session->userdata("inicio_sesion")['rol'], array('CX', 'CT')))
            $filtro .= " AND sp.nomdepto NOT IN ( 'DEVOLUCIONES', 
            'TRASPASO',
            'CESION OOAM',
            'RESCISION OOAM',
            'DEVOLUCION OOAM',
            'TRASPASO OOAM',
            'DEVOLUCION DOM OOAM',
            'INFORMATIVA' ) AND sp.idEmpresa IN ( " . $this->session->userdata("inicio_sesion")['depto'] . " )";

        /**@author Dante Aldair Guerrero Aldana  <coordinador6.desarrollo@ciudadmaderas.com> */
        $etapaIntercambios = '';
        if (in_array($this->session->userdata("inicio_sesion")['id'], ['320', '339'])) {
            $etapaIntercambios = ', 12'; // Cambios para traer solicitudes de metodo o forma de pago intercambio
        }

        return $this->db->query("SELECT	facturas.uuid, 
                                        sp.programado, 
                                        facturas.tipo_factura, 
                                        sp.idetapa, 
                                        autpagos.estatus_pago, 
                                        sp.idetapa, 
                                        sp.folio, 
                                        if(spo.idProyectos is null, sp.proyecto, pd.nombre) as proyecto,
                                        sp.moneda, 
                                        SUBSTRING(facturas.uuid, -5, 5) AS folifis, 
                                        sp.metoPago, 
                                        director.nombre_completo nombredir, 
                                        capturista.nombre_completo, 
                                        sp.caja_chica, 
                                        sp.idsolicitud, 
                                        sp.justificacion, 
                                        sp.nomdepto, 
                                        proveedores.nombre, 
                                        IF(sp.programado IS NOT NULL, sp.cantidad + IFNULL(autpagos.pagado, 0),  sp.cantidad) AS cantidad, 
                                        empresas.abrev, 
                                        sp.fechaCreacion feccrea, 
                                        sp.fecha_autorizacion, 
                                        sp.fecelab, 
                                        autpagos.upago fautorizado, 
                                        etapas.nombre AS etapa, 
                                        IFNULL(autpagos.pagado, 0), 
                                        autpagos.fechaDis,
                                        autpagos.etapa_pago,
                                        poliza.numpoliza,
                                        poliza.fprovision,
                                        tsp.nombre AS tipoServParti,
                                        sp.etapa AS etapaSp,
                                        sp.homoclave,
                                        ins.insumo
                                FROM solpagos AS sp
                                INNER JOIN listado_usuarios AS capturista 
                                    ON capturista.idusuario = sp.idusuario 
                                INNER JOIN listado_usuarios AS director 
                                    ON director.idusuario = sp.idResponsable 
                                INNER JOIN proveedores AS proveedores
                                    ON proveedores.idproveedor = sp.idProveedor 
                                INNER JOIN etapas AS etapas
                                    ON etapas.idetapa = sp.idetapa 
                                INNER JOIN empresas AS empresas
                                    ON empresas.idempresa = sp.idEmpresa 
                                LEFT JOIN factura_registro AS facturas 
                                    ON facturas.idsolicitud = sp.idsolicitud 
                                LEFT JOIN vw_autpagos AS autpagos 
                                    ON autpagos.idsolicitud = sp.idsolicitud
                                LEFT JOIN ( SELECT idsolicitud, numpoliza, fecreg fprovision FROM polizas_provision WHERE estatus = 1 GROUP BY idsolicitud) AS poliza 
                                    ON poliza.idsolicitud = sp.idsolicitud
                                LEFT JOIN solicitud_proyecto_oficina AS spo
                                    ON spo.idsolicitud = sp.idsolicitud
                                LEFT JOIN proyectos_departamentos AS pd
                                    ON spo.idProyectos = pd.idProyectos
                                LEFT JOIN oficina_sede AS os
                                    ON spo.idOficina = os.idOficina
                                LEFT JOIN tipo_servicio_partidas AS tsp
                                    ON spo.idTipoServicioPartida = tsp.idTipoServicioPartida
                                LEFT JOIN insumos AS ins
                                    ON ins.idinsumo = sp.tipo_insumo
                                WHERE sp.idetapa IN ( 7, 10, 11, 20, 9, 47, 49, 30$etapaIntercambios ) AND
                                      sp.caja_chica = $tipo_gasto
                                      $filtro
                                ORDER BY sp.fechaCreacion DESC");
    }

    //HISTORIAL DE CUENTAS POR PAGAR - PAGO A PROVEEDOR TablaHistorialSolicitudesA()
    function getHistorialTablaSolA($finicial,$ffinal) {

        ini_set('memory_limit','-1');

        return $this->db->query("SELECT solpagos.idsolicitud,
                                        solpagos.idetapa,
                                        empresas.abrev,
                                        solpagos.folio,
                                        solpagos.fechaCreacion AS feccrea,
                                        solpagos.fecha_autorizacion AS fecha_autorizacion,
                                        solpagos.fecelab AS fecelab,
                                        proveedores.nombre,
                                        solpagos.nomdepto,
                                        CASE                /**  FIN Cambio se modificó el campo de cantidad, el campo del monto pagado y seagregó el campo de porceso de la etapa | FECHA: 16-Mayo-2024 | @author Lalo Silva <programador.analista38@ciudadmaderas.com>  **/
                                            WHEN solpagos.idproceso = 30 and solpagos.programado IS NOT NULL THEN
                                                solpagos.cantidad
                                            WHEN solpagos.idproceso IS NULL AND solpagos.programado IS NOT NULL THEN
                                                IF( solpagos.idetapa NOT IN ( 30, 0 ),
                                                    (ROUND( CASE
                                                                WHEN solpagos.programado = 8 THEN
                                                                    ROUND(TIMESTAMPDIFF(DAY,
                                                                    solpagos.fecelab,
                                                                    solpagos.fecha_fin) / 14)
                                                                WHEN solpagos.programado < 7 THEN
                                                                    TIMESTAMPDIFF(MONTH,
                                                                        CONCAT( YEAR(solpagos.fecelab),'-',MONTH(solpagos.fecelab),'-', DAY(solpagos.fecha_fin) ),
                                                                        solpagos.fecha_fin) / solpagos.programado
                                                                ELSE
                                                                    TIMESTAMPDIFF(WEEK, solpagos.fecelab, solpagos.fecha_fin)
                                                            END ) + 1) * solpagos.cantidad, 0)
                                            ELSE solpagos.cantidad
                                        END AS cantidad,
                                        IF( solpagos.programado IS NOT NULL, 
                                            CASE 
                                                WHEN solpagos.programado < 7 THEN 
                                                    TIMESTAMPDIFF(MONTH, 
                                                                  CONCAT( YEAR(solpagos.fecelab),'-',MONTH(solpagos.fecelab),'-', DAY(solpagos.fecelab) ), 
                                                                  IFNULL( solpagos.fecha_fin, DATE_ADD( solpagos.fecelab, INTERVAL IF( solpagos.idetapa NOT IN ( 11, 30 ), tpagos + 1, IFNULL(tpagos, 1) )  MONTH) ) ) 
                                                ELSE TIMESTAMPDIFF(WEEK, solpagos.fecelab, IFNULL(solpagos.fecha_fin, DATE_ADD( solpagos.fecelab, INTERVAL IFNULL( tpagos, 1 )  WEEK) ) ) END, NULL ) mpagar,
                                        CASE 
                                            WHEN solpagos.idproceso = 30 THEN 
                                                pagosDevoluciones.cantidadPagada
                                            ELSE 
                                                IFNULL( autpagos.pagado, 0 ) 
                                        END AS pagado,
                                        autpagos.estatus_pago,
                                        autpagos.etapa_pago,
                                        solpagos.caja_chica,
                                        UPPER(IF( solpagos.idetapa IN ( 9, 10, 11 ) AND autpagos.estatus_pago IS NOT NULL AND autpagos.estatus_pago != 16, autpagos.etapa_pago, etapas.nombre )) etapa,
                                        solpagos.metoPago,
                                        solpagos.justificacion,
                                        capturista.nombre_completo,
                                        solpagos.programado,
                                        solpagos.tendrafac,
                                        solpagos.idetapa AS etapa_sol,
                                        IFNULL( solpagos.fecha_recordatorio, 'NA' ) AS fecha_recordatorio, -- FECHA: 05-SEPTIEMBRE-2024 | CAMBIO RECORDATORIO | @author Angel Victoriano <programador.analista30@ciudadmaderas.com>
                                        solpagos.idproceso
                                FROM solpagos
                                CROSS JOIN listado_usuarios capturista ON capturista.idusuario = solpagos.idusuario
                                CROSS JOIN listado_usuarios director ON director.idusuario = solpagos.idResponsable
                                CROSS JOIN ( SELECT idproveedor, nombre FROM proveedores ) proveedores ON proveedores.idproveedor = solpagos.idProveedor
                                CROSS JOIN etapas ON etapas.idetapa = solpagos.idetapa
                                CROSS JOIN ( SELECT idempresa, abrev FROM empresas ) empresas ON empresas.idempresa = solpagos.idEmpresa
                                LEFT JOIN ( SELECT * FROM vw_autpagos ) AS autpagos ON autpagos.idsolicitud = solpagos.idsolicitud
                                LEFT JOIN ( SELECT ad.idsolicitud, ifnull(count(ad.idpago), 0) AS pagos, SUM(cantidad) AS cantidadPagada
                                            FROM autpagos ad
                                            WHERE ad.estatus = 16
                                            GROUP BY idsolicitud ) AS pagosDevoluciones
                                    ON pagosDevoluciones.idsolicitud = solpagos.idsolicitud           /**  FIN Cambio se modificó el campo de cantidad, el campo del monto pagado y seagregó el campo de porceso de la etapa | FECHA: 16-Mayo-2024 | @author Lalo Silva <programador.analista38@ciudadmaderas.com>  **/
                                /**  FIN Cambio se modificó el campo de cantidad, el campo del monto pagado y seagregó el campo de porceso de la etapa | FECHA: 16-Mayo-2024 | @author Lalo Silva <programador.analista38@ciudadmaderas.com>  **/
                            WHERE solpagos.idetapa NOT IN (1 , 25, 30, 42, 45, 47, 49) AND ( caja_chica = 0 OR caja_chica IS NULL ) AND solpagos.fecelab BETWEEN '$finicial' AND '$ffinal'
                            ORDER BY solpagos.idetapa , solpagos.fechaCreacion DESC");
    }

    //HISTORIAL DE CUENTAS POR PAGAR - PAGO A CAJA CHICA TablaHistorialSolicitudesB()
    function getHistorialTablaSolCajachica($finicial,$ffinal) {

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
            solpagos.cantidad cantidad,
            NULL mpagar,
            0 pagado,
            CASE WHEN solpagos.fecha_autorizacion > '".date( "Y-m-d", strtotime('monday this week') )." 23:59:00' THEN 1 ELSE 0 END AS estado,
            NULL estatus_pago,
            solpagos.caja_chica,
            etapas.nombre AS etapa,
            solpagos.metoPago,
            solpagos.justificacion,
            solpagos.idetapa,
            capturista.nombre_completo,
            solpagos.programado,
            CONCAT(responsable.nombres, ' ', responsable.apellidos) responsable,
            responsable_cch.nombre_reembolso_cch
        FROM (  
            SELECT *
            FROM solpagos
            WHERE solpagos.idetapa NOT IN ( 1 , 25, 30, 42, 45, 47, 49 ) AND ( caja_chica = 1 ) AND solpagos.fecelab BETWEEN '$finicial' AND '$ffinal'
        ) solpagos
        INNER JOIN (SELECT  usuarios.idusuario, CONCAT(usuarios.nombres, ' ', usuarios.apellidos) AS nombre_completo FROM usuarios) AS capturista ON capturista.idusuario = solpagos.idusuario
        INNER JOIN ( SELECT CONCAT(usuarios.nombres, ' ', usuarios.apellidos) AS nombredir, usuarios.idusuario FROM usuarios ) AS director ON director.idusuario = solpagos.idResponsable
        INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor
        INNER JOIN etapas ON etapas.idetapa = solpagos.idetapa
        INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa
        LEFT JOIN usuarios responsable ON responsable.idusuario = solpagos.idresponsable
        LEFT JOIN ( SELECT  *, MIN(facturas.feccrea) FROM facturas WHERE facturas.tipo_factura IN (1 , 3) GROUP BY facturas.idsolicitud ) AS facturas ON facturas.idsolicitud = solpagos.idsolicitud
        LEFT JOIN (
                SELECT
                    cajas_ch.idusuario
                    , GROUP_CONCAT(cajas_ch.nombre_reembolso_ch SEPARATOR ', ') nombre_reembolso_cch
                FROM cajas_ch
                GROUP BY cajas_ch.idusuario
        ) AS responsable_cch ON responsable_cch.idusuario = solpagos.idResponsable
        ORDER BY solpagos.idetapa , solpagos.fechaCreacion DESC");
    }

    //HISTORIAL DE CUENTAS POR PAGAR - PAGO A CAJA CHICA  TablaHistorialSolicitudesTDC()
    function getHistorialTablaSolTDC($finicial,$ffinal) {

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
            NULL mpagar,
            0 pagado,
            NULL estatus_pago,
            solpagos.caja_chica,
            etapas.nombre AS etapa,
            solpagos.metoPago,
            solpagos.justificacion,
            solpagos.idetapa,
            capturista.nombre_completo,
            solpagos.programado,
            responsable.nresponsable,
            IFNULL(tarjeta.titular_nombre, 'NA') as titular_nombre
        FROM (
            SELECT *
            FROM solpagos
            WHERE solpagos.idetapa NOT IN ( 1, 25, 30, 42, 45, 47, 49 ) 
            AND ( caja_chica = 2 ) 
            AND solpagos.fecelab BETWEEN '$finicial' AND '$ffinal'
        ) solpagos
        INNER JOIN listado_usuarios capturista ON capturista.idusuario = solpagos.idusuario
        INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor
        INNER JOIN etapas ON etapas.idetapa = solpagos.idetapa
        INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa
        /* INNER JOIN (SELECT usuarios.idusuario, CONCAT(usuarios.nombres, ' ', usuarios.apellidos) AS responsable FROM usuarios) AS responsable ON responsable.idusuario = solpagos.idResponsable*/
        INNER JOIN listado_tdc responsable ON responsable.idtcredito = solpagos.idResponsable /* TICKET: 108823 FECHA: 09-JUNIO-2025 */
        LEFT JOIN tcredito tdc ON solpagos.idResponsable = tdc.idtcredito
        LEFT JOIN ( SELECT usuarios.idusuario, CONCAT( usuarios.nombres,' ', usuarios.apellidos ) AS titular_nombre FROM usuarios ) AS tarjeta ON tarjeta.idusuario = tdc.idtitular
        ORDER BY solpagos.idetapa , solpagos.fechaCreacion DESC");
    }

    function getHistorialTablaSolC(){
        /*LISTADO DE SOLICITUDES REALIZADAS Y SE HAN CANCELADO*/
        //TODO LAS LINEAS COMENTADAS EN LA COLSULTA SON PARTE PARA LOS PAGOS REALIZADOS A LAS SOLICITUDES
        //AL SER SOLICITUDES CANCELADAS NO DEBEN DE TENER ALGUN PAGO HECHO
        $departamentos = in_array($this->session->userdata("inicio_sesion")['depto'], array('CONSTRUCCION', 'JARDINERIA', 'OOAM', 'OOAM TECNICO', 'OOAM ADMINISTRATIVO'));
        $nuevosCampos = 'solpagos.proyecto';
        if(!$departamentos){
            if($this->session->userdata("inicio_sesion")['rol'] == 'CP')
                $nuevosCampos = "if(spo.idsolicitud is null, null, pd.nombre) proyecto,
                                os.nombre oficina,
                                if(spo.idsolicitud is null, solpagos.proyecto, tsp.nombre) servicioPartida";
            else
                $nuevosCampos = "ifnull(solpagos.proyecto, pd.nombre) proyecto,
                                os.nombre oficina,
                                tsp.nombre servicioPartida";
        }         
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
            solpagos.fecha_autorizacion AS fech_auto,
            $nuevosCampos
            FROM solpagos
            /*LEFT JOIN ( SELECT  autpagos.idsolicitud, MAX( autpagos.fechaDis ) fechaDis, SUM( autpagos.cantidad ) pagado, MIN( autpagos.estatus ) estatus_pago, MAX( autpagos.fecreg ) upago FROM autpagos GROUP BY autpagos.idsolicitud ) AS autpagos ON autpagos.idsolicitud = solpagos.idsolicitud*/
            INNER JOIN (SELECT  usuarios.idusuario, CONCAT(usuarios.nombres, ' ', usuarios.apellidos) AS nombre_completo FROM usuarios) AS capturista ON capturista.idusuario = solpagos.idusuario
            INNER JOIN ( SELECT CONCAT(usuarios.nombres, ' ', usuarios.apellidos) AS nombredir, usuarios.idusuario FROM usuarios ) AS director ON director.idusuario = solpagos.idResponsable
            INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor
            INNER JOIN etapas ON etapas.idetapa = solpagos.idetapa
            LEFT JOIN ( SELECT  *, MIN(facturas.feccrea) FROM facturas WHERE facturas.tipo_factura IN (1 , 3) GROUP BY facturas.idsolicitud ) AS facturas ON facturas.idsolicitud = solpagos.idsolicitud
            INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa
            LEFT JOIN ( SELECT  polizas_provision.idprovision, polizas_provision.idsolicitud, MIN( polizas_provision.fecreg ) FROM polizas_provision GROUP BY polizas_provision.idsolicitud) AS polizas_provision ON polizas_provision.idsolicitud = solpagos.idsolicitud
            LEFT JOIN solicitud_proyecto_oficina spo on solpagos.idsolicitud =  spo.idsolicitud
            LEFT JOIN proyectos_departamentos pd on spo.idProyectos = pd.idProyectos
            LEFT JOIN oficina_sede os on spo.idOficina = os.idOficina
            LEFT JOIN tipo_servicio_partidas tsp on spo.idTipoServicioPartida = tsp.idTipoServicioPartida
        WHERE
            solpagos.idetapa IN ( 30 )
        ORDER BY solpagos.idetapa , solpagos.fechaCreacion DESC");    
    }

    function getHistorialTablaSolP() {
        //CONSULTA ORIGINAL PARA SACAR TODOS LOS PAGOS PAUSADOS EN EL SISTEMA
        //return $this->db->query("SELECT  IF(facturas.uuid IS null, 'NO', facturas.nombre_archivo) AS tienefac ,pago_generado.estatus_pago, IF(solpagos.folio NOT IN ('NA'),solpagos.folio, SUBSTRING(facturas.uuid, - 5, 5)) AS folio, solpagos.moneda, SUBSTRING(facturas.uuid, -5, 5) AS folifis, solpagos.metoPago, director.nombredir, solpagos.moneda, capturista.nombre_completo, solpagos.caja_chica, solpagos.idsolicitud, solpagos.justificacion, solpagos.nomdepto, proveedores.nombre, solpagos.cantidad, empresas.abrev, autpagos.estatus as estatus_def,  DATE_FORMAT(solpagos.fechaCreacion, '%d/%m/%Y') AS feccrea, DATE_FORMAT(solpagos.fecha_autorizacion, '%d/%b/%Y') AS fecha_autorizacion, DATE_FORMAT(solpagos.fecelab, '%d/%m/%Y') AS fecelab, etapas.nombre AS etapa, IFNULL(pago_aut.autorizado, 0) AS autorizado,   IFNULL(liquidado.pagado, 0) AS pagado,   DATE_FORMAT(autpagos.fechaDis, '%d/%b/%Y') as fechaDis2 ,DATE_FORMAT(solpagos.fecha_autorizacion, '%d/%b/%Y') AS fech_auto  FROM solpagos LEFT JOIN ( SELECT *, autpagos.estatus AS estatus_pago, MAX(autpagos.fecreg) FROM autpagos GROUP BY autpagos.idsolicitud ) AS pago_generado ON pago_generado.idsolicitud = solpagos.idsolicitud INNER JOIN ( SELECT usuarios.idusuario, CONCAT( usuarios.nombres,' ', usuarios.apellidos ) AS nombre_completo FROM usuarios ) AS capturista ON capturista.idusuario = solpagos.idusuario LEFT JOIN (SELECT autpagos.idsolicitud, SUM( autpagos.cantidad ) AS pagado FROM autpagos GROUP BY autpagos.idsolicitud) AS liquidado ON solpagos.idsolicitud = liquidado.idsolicitud LEFT JOIN ( SELECT CONCAT( usuarios.nombres,' ',usuarios.apellidos ) AS nombredir, usuarios.idusuario FROM usuarios ) AS director ON director.idusuario = solpagos.idResponsable  INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor INNER JOIN etapas ON etapas.idetapa = solpagos.idetapa LEFT JOIN (SELECT *, MIN(facturas.feccrea) FROM facturas WHERE facturas.tipo_factura IN ( 1, 3 ) GROUP BY facturas.idsolicitud) AS facturas ON facturas.idsolicitud = solpagos.idsolicitud LEFT JOIN ( SELECT autpagos.idsolicitud, SUM(autpagos.cantidad) AS autorizado FROM autpagos  WHERE autpagos.estatus NOT IN (12, 2)  GROUP BY autpagos.idsolicitud ) AS pago_aut ON pago_aut.idsolicitud = solpagos.idsolicitud  INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa LEFT JOIN ( SELECT *, MAX(autpagos.fecreg) FROM autpagos GROUP BY autpagos.idsolicitud ) AS autpagos ON autpagos.idsolicitud = solpagos.idsolicitud WHERE solpagos.idetapa IN ( 42, 45, 47, 49 ) ORDER BY solpagos.idetapa, solpagos.fechaCreacion DESC");
        $departamentos = in_array($this->session->userdata("inicio_sesion")['depto'], array('CONSTRUCCION', 'JARDINERIA', 'OOAM', 'OOAM TECNICO', 'OOAM ADMINISTRATIVO'));
        $nuevosCampos = 'solpagos.proyecto';
        if(!$departamentos){
            if($this->session->userdata("inicio_sesion")['rol'] == 'CP')
                $nuevosCampos = "if(spo.idsolicitud is null, null, pd.nombre) proyecto,
                                os.nombre oficina,
                                if(spo.idsolicitud is null, solpagos.proyecto, tsp.nombre) servicioPartida";
            else
                $nuevosCampos = "ifnull(solpagos.proyecto, pd.nombre) proyecto,
                                os.nombre oficina,
                                tsp.nombre servicioPartida";
        }
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
            solpagos.fecha_autorizacion AS fech_auto,
            $nuevosCampos
            FROM ( SELECT * FROM solpagos WHERE solpagos.idetapa IN ( 42, 45, 47, 49 ) ) solpagos
            LEFT JOIN ( SELECT  autpagos.idsolicitud, MAX( autpagos.fechaDis ) fechaDis, SUM( autpagos.cantidad ) pagado, MIN( autpagos.estatus ) estatus_pago, MAX( autpagos.fecreg ) upago FROM autpagos GROUP BY autpagos.idsolicitud ) AS autpagos ON autpagos.idsolicitud = solpagos.idsolicitud
            INNER JOIN (SELECT  usuarios.idusuario, CONCAT(usuarios.nombres, ' ', usuarios.apellidos) AS nombre_completo FROM usuarios) AS capturista ON capturista.idusuario = solpagos.idusuario
            INNER JOIN ( SELECT CONCAT(usuarios.nombres, ' ', usuarios.apellidos) AS nombredir, usuarios.idusuario FROM usuarios ) AS director ON director.idusuario = solpagos.idResponsable
            INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor
            INNER JOIN etapas ON etapas.idetapa = solpagos.idetapa
            LEFT JOIN ( SELECT  *, MIN(facturas.feccrea) FROM facturas WHERE facturas.tipo_factura IN (1 , 3) GROUP BY facturas.idsolicitud ) AS facturas ON facturas.idsolicitud = solpagos.idsolicitud
            INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa
            LEFT JOIN ( SELECT  polizas_provision.idprovision, polizas_provision.idsolicitud, MIN( polizas_provision.fecreg ) FROM polizas_provision GROUP BY polizas_provision.idsolicitud) AS polizas_provision ON polizas_provision.idsolicitud = solpagos.idsolicitud
            LEFT JOIN solicitud_proyecto_oficina spo on solpagos.idsolicitud =  spo.idsolicitud
            LEFT JOIN proyectos_departamentos pd on spo.idProyectos = pd.idProyectos
            LEFT JOIN oficina_sede os on spo.idOficina = os.idOficina
            LEFT JOIN tipo_servicio_partidas tsp on spo.idTipoServicioPartida = tsp.idTipoServicioPartida
        ORDER BY solpagos.idetapa , solpagos.fechaCreacion DESC");
    }

    function getHistorialTablaSolPV($mesesSeleccionados, $anio) { /** Ticket #76788 Folios en reporte de trámites PV | FECHA: 18-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
        /** Ticket #76788 Folios en reporte de trámites PV | FECHA: 18-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
        $where = '';
        if (!empty($mesesSeleccionados) && !empty($anio)) {
            $meses = implode(",", $mesesSeleccionados); 
            $where = "AND (MONTH(LFC.fecha) IN ($meses) AND YEAR(LFC.fecha) IN ($anio))";
        } elseif (!empty($mesesSeleccionados)) {
            $meses = implode(",", $mesesSeleccionados); 
            $where = "AND MONTH(LFC.fecha) IN ($meses)";
        } elseif (!empty($anio)) {
            $where = "AND YEAR(LFC.fecha) IN ($anio)";
        }
        /** Ticket #76788 Folios en reporte de trámites PV | FECHA: 18-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/

        //CONSULTA ORIGINAL PARA SACAR TODOS LOS TRÁMITES DE POSTVENTA
        return $this->db->query("SELECT 
            SP.idsolicitud,
            SP.folio,
            CONCAT(US.nombres, ' ', US.apellidos) usuario,
            substring(SP.condominio, 1, position('-' in SP.condominio) -1) desarrollo,
            substring(SP.condominio, CHAR_LENGTH(SP.condominio) - LOCATE('-', REVERSE(SP.condominio))+2) lote,
            RE.rechazos,
            CASE 
                WHEN LS.etapa = 67 THEN 
                    LS.fecha
                END AS fechaContraloria,
            LFC.fecha AS fechaAutContraloria,
            IFNULL(ET.nombre, '-') etapa
            FROM logs AS LS
            LEFT JOIN solpagos AS SP 
                ON LS.idsolicitud = SP.idsolicitud
            LEFT JOIN proceso_etapa AS PE
                ON LS.etapa = PE.idetapa AND PE.idproceso = SP.idproceso
            LEFT JOIN usuarios  US 
                ON SP.idusuario = US.idusuario
            LEFT JOIN etapas ET 
                ON ET.idetapa = SP.idetapa
            LEFT JOIN logs AS LFC
                ON LS.idsolicitud = LFC.idsolicitud AND LFC.etapa = 68
            LEFT JOIN (
                    select 
                        SP.idsolicitud,  
                        SUM(CASE 
                            WHEN PE.orden > PE1.orden 
                            THEN 1 ELSE 0 
                            END) rechazos
                    from logs LS
                    LEFT JOIN solpagos AS SP 
                        ON LS.idsolicitud = SP.idsolicitud
                    LEFT JOIN proceso_etapa AS PE
                        ON LS.etapa = PE.idetapa AND PE.idproceso = SP.idproceso
                    LEFT JOIN logs AS LS1 
                        ON LS.idsolicitud = LS1.idsolicitud and LS1.idlog = (select LS2.idlog from logs LS2 where LS2.idlog > LS.idlog and idsolicitud = SP.idsolicitud limit 1)
                    LEFT JOIN solpagos AS SP1 
                        ON LS1.idsolicitud = SP1.idsolicitud
                    LEFT JOIN proceso_etapa AS PE1
                        ON LS1.etapa = PE1.idetapa AND PE1.idproceso = SP1.idproceso
                    WHERE SP.idproceso IN (26, 27, 28, 29, 30, 25, 22, 21, 15, 14, 13, 12, 11, 10, 9, 8, 6, 5)
                    AND LS.etapa IN (67, 68)
                    GROUP BY SP.idsolicitud) AS RE ON RE.idsolicitud = SP.idsolicitud
            WHERE SP.idproceso IN (26, 27, 28, 29, 30, 25, 22, 21, 15, 14, 13, 12, 11, 10, 9, 8, 6, 5)
            AND LS.etapa IN (67, 68)
            $where /** Ticket #76788 Folios en reporte de trámites PV | FECHA: 18-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
            group by SP.idsolicitud;");
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
        return $this->db->query("(SELECT 
            solpagos.programado, 
            solpagos.intereses, 
            autpagos.idpago,
            proveedores.nombre AS responsable,
            IFNULL( autpagos.fechaOpe, autpagos.fechaDis ) fecha_operacion,
            autpagos.cantidad,
            autpagos.referencia,
            '1' AS bd,
            empresas.abrev,
            autpagos.idsolicitud,
            autpagos.estatus
        FROM
            (
                SELECT *
                FROM autpagos
                WHERE autpagos.estatus IN (14, 15)
                GROUP BY idpago 
            ) autpagos
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
                INNER JOIN
            solpagos ON solpagos.idsolicitud = autpagos.idsolicitud
            WHERE
                autpagos.tipoPago IN ('ECHQ', 'EFEC') OR solpagos.metoPago IN ('ECHQ', 'EFEC')
        ) UNION (
        SELECT 0 as programado, 0 as intereses,
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
            ( 
                SELECT
                    autpagos_caja_chica.idpago,
                    autpagos_caja_chica.fechaOpe,
                    autpagos_caja_chica.fechaDis,
                    autpagos_caja_chica.cantidad,
                    autpagos_caja_chica.referencia,
                    autpagos_caja_chica.idsolicitud,
                    autpagos_caja_chica.estatus,
                    autpagos_caja_chica.idResponsable,
                    autpagos_caja_chica.idEmpresa
                FROM autpagos_caja_chica
                WHERE
                    autpagos_caja_chica.tipoPago = 'ECHQ'
                    AND autpagos_caja_chica.estatus IN (13, 14, 15)
                GROUP BY idpago
            ) autpagos_caja_chica
                INNER JOIN
            (SELECT 
                usuarios.idusuario,
                    CONCAT(usuarios.nombres, ' ', usuarios.apellidos) AS responsable
            FROM
                usuarios) AS responsable ON responsable.idusuario = autpagos_caja_chica.idResponsable
                INNER JOIN
            empresas ON autpagos_caja_chica.idEmpresa = empresas.idempresa
            ORDER BY fecha_operacion DESC)");
    }


    function ChequesCobrados() {
        ini_set('memory_limit', '512M'); // Duplica el límite actual
        return $this->db->query("SELECT autpagos.idsolicitud,
                proveedores.nombre AS responsable, 
                autpagos.fecha_cobro AS fecha, 
                IFNULL((autpagos.fechaOpe), '---') AS fecha_operacion, 
                autpagos.cantidad, 
                autpagos.referencia, 
                '1' AS bd, 
                empresas.abrev, 
                IFNULL(autpagos.fecha_cobro, '---') AS fecha_cobro, 
                solpagos.programado, 
                solpagos.intereses,
                solpagos.folio, 
                fr.uuid, 
                autpagos.fechaDis AS fecha_dispersion, 
                autpagos.fecreg AS fechaaut, 
                solpagos.nomdepto,
                if(spo.idProyectos is null, solpagos.proyecto, pd.nombre) as proyecto,
                etapas.nombre AS etapa,
                solpagos.condominio,
                CONCAT(us.nombres, ' ', us.apellidos) AS responsableDA,
                solpagos.justificacion,
                solpagos.metoPago,
                fr.idfactura,
                fr.descripcion AS descFac,
                CONCAT(usc.nombres, ' ', usc.apellidos) AS usuarioAlta
        FROM (	SELECT * 
                FROM autpagos 
                WHERE idpago NOT IN ( SELECT idpago FROM autpagos WHERE autpagos.formaPago IN ( 'ECHQ', 'EFEC' ) GROUP BY autpagos.referencia HAVING COUNT( referencia ) > 1 ) ) autpagos 
        INNER JOIN (SELECT solpagos.idsolicitud, proveedores.nombre, solpagos.metoPago, solpagos.programado, solpagos.intereses
                    FROM proveedores 
                    INNER JOIN solpagos 
                        ON solpagos.idProveedor = proveedores.idproveedor) AS proveedores 
            ON proveedores.idsolicitud = autpagos.idsolicitud 
        INNER JOIN (SELECT solpagos.idsolicitud, empresas.abrev FROM empresas INNER JOIN solpagos ON solpagos.idEmpresa = empresas.idempresa) AS empresas 
            ON autpagos.idsolicitud = empresas.idsolicitud 
        INNER JOIN solpagos 
            ON autpagos.idsolicitud  = solpagos.idsolicitud
        LEFT JOIN factura_registro fr 
            ON fr.idsolicitud = solpagos.idsolicitud -- de aquí saco UUID
        LEFT JOIN etapas 
            ON solpagos.idetapa = etapas.idetapa
        LEFT JOIN usuarios AS us
            ON solpagos.idResponsable = us.idusuario
        LEFT JOIN usuarios AS usc
            ON solpagos.idusuario = usc.idusuario
        LEFT JOIN solicitud_proyecto_oficina spo 
             on spo.idsolicitud = solpagos.idsolicitud 
        LEFT JOIN proyectos_departamentos pd 
             on spo.idProyectos = pd.idProyectos
        WHERE ( autpagos.formaPago IN ( 'ECHQ', 'EFEC' ) OR proveedores.metoPago IN ( 'EFEC', 'ECHQ' ) ) AND autpagos.estatus = 16
        UNION (
        SELECT
            autpagos_caja_chica.idpago,
            responsable.responsable AS responsableDA,
            autpagos_caja_chica.fecha_cobro AS fecha,
            IFNULL(autpagos_caja_chica.fechaDis, '---') AS fecha_operacion,
            autpagos_caja_chica.cantidad,
            autpagos_caja_chica.referencia,
            '2' AS bd,
            empresas.abrev,
            autpagos_caja_chica.fecha_cobro fecha_cobro,
            0 as programado, 
            0 as intereses,
            'NA' folio,
            'NA' uuid,
            autpagos_caja_chica.fechaDis AS fecha_dispersion,
            autpagos_caja_chica.fecreg AS fechaaut,
            'NA' nomdepto,

            'NA' AS proyecto,
            'NA' AS etapa,
            'NA' AS condominio,
            pro.nombre AS proveedor,
            'NA' AS justificacion,
            'NA' AS metoPago,
            'NA' AS idfactura,
            'NA' AS  descFac,
            CONCAT(usc.nombres, ' ', usc.apellidos) AS usuarioAlta
        FROM autpagos_caja_chica
        INNER JOIN (SELECT usuarios.idusuario, CONCAT(usuarios.nombres, ' ', usuarios.apellidos) AS responsable FROM usuarios) AS responsable 
            ON responsable.idusuario = autpagos_caja_chica.idResponsable
        LEFT JOIN usuarios AS usc
            ON autpagos_caja_chica.idrealiza = usc.idusuario
        INNER JOIN empresas 
            ON autpagos_caja_chica.idEmpresa = empresas.idempresa
        LEFT JOIN proveedores AS pro
            ON autpagos_caja_chica.idProveedor = pro.idproveedor
        WHERE(	autpagos_caja_chica.formaPago IN ( 'ECHQ', 'EFEC' ) OR 
                autpagos_caja_chica.tipoPago IN ( 'ECHQ', 'EFEC' ) ) AND 
                autpagos_caja_chica.estatus = 16 )
        UNION (
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
            0 intereses,
            solpagos.folio, 
            fr.uuid, 
            'NA' fecha_dispersion, 
            'NA' fechaaut, 
            solpagos.nomdepto,
            if(spo.idProyectos is null, solpagos.proyecto, pd.nombre) as proyecto,
            etapas.nombre AS etapa,
            solpagos.condominio,
            CONCAT(us.nombres, ' ', us.apellidos) AS responsableDA,
            solpagos.justificacion,
            solpagos.metoPago,
            fr.idfactura,
            fr.descripcion AS descFac,
            CONCAT(usc.nombres, ' ', usc.apellidos) AS usuarioAlta
        FROM solpagos 
        INNER JOIN empresas 
            ON empresas.idempresa = solpagos.idempresa
        INNER JOIN (SELECT * 
                    FROM autpagos 
                    WHERE idpago IN (SELECT idpago 
                                    FROM autpagos
                                    WHERE	autpagos.referencia IS NOT NULL AND  
                                            autpagos.formaPago IN ( 'ECHQ', 'EFEC' )
                                    GROUP BY autpagos.referencia HAVING COUNT( referencia ) > 1 ) ) autpagos 
            ON autpagos.idsolicitud = solpagos.idsolicitud 
        LEFT JOIN factura_registro fr 
            ON fr.idsolicitud = solpagos.idsolicitud -- de aquí saco UUID
        LEFT JOIN etapas 
            ON solpagos.idetapa = etapas.idetapa
        LEFT JOIN usuarios AS us
            ON solpagos.idResponsable = us.idusuario
        LEFT JOIN usuarios AS usc
            ON solpagos.idusuario = usc.idusuario
        LEFT JOIN solicitud_proyecto_oficina spo  
            on spo.idsolicitud = solpagos.idsolicitud 
        LEFT JOIN proyectos_departamentos pd  
            on spo.idProyectos = pd.idProyectos
        WHERE	solpagos.metoPago = 'EFEC' AND 
                autpagos.referencia IS NOT NULL AND 
                concat('', autpagos.referencia * 1) = autpagos.referencia AND 
                autpagos.estatus = 16
        GROUP BY solpagos.idEmpresa, autpagos.referencia)
        ORDER BY fecha DESC;");
    }
    
    
    function allcheques() {
        return $this->db->query("SELECT * FROM ( (SELECT 
            solpagos.programado, 
            solpagos.intereses, 
            solpagos.nomdepto, 
            if(spo.idProyectos is null, solpagos.proyecto, pd.nombre) as proyecto, 
            solpagos.condominio lote,
            autpagos.fecreg fautorizacion, 
            autpagos.fechaDis fdispersion, 
            autpagos.idsolicitud idpago, 
            proveedores.nombre AS responsable, 
            autpagos.cantidad, 
            IFNULL(autpagos.fecha_cobro, autpagos.fechaOpe) AS fecha_operacion, 
            IFNULL(autpagos.referencia, 'SIN DEFINIR') AS referencia, 
            empresas.abrev, '1' AS bd, 
            ( CASE WHEN autpagos.estatus = '16' THEN 'COBRADO' WHEN autpagos.estatus = '14' THEN 'ENTREGADO' ELSE 'POR ENTREGAR' END ) AS estatus, 
            autpagos.fecha_cobro AS orderby 
            FROM ( SELECT cantidad, idsolicitud, fecha_cobro, fechaOpe, referencia, estatus, formaPago, tipoPago, fecreg, fechaDis FROM autpagos
            LEFT JOIN ( SELECT idpago FROM autpagos WHERE autpagos.formaPago IN ( 'ECHQ', 'EFEC' ) GROUP BY autpagos.referencia HAVING COUNT( referencia ) > 1 ) autpagos2 ON autpagos.idpago = autpagos2.idpago
            WHERE autpagos2.idpago IS NULL AND ( autpagos.formaPago IN ( 'ECHQ', 'EFEC' ) OR autpagos.tipoPago IN ( 'ECHQ', 'EFEC' ) ) AND autpagos.estatus IN (13, 14, 15, 16) ) autpagos 
            INNER JOIN solpagos ON solpagos.idsolicitud = autpagos.idsolicitud 
            INNER JOIN ( SELECT proveedores.idproveedor, proveedores.nombre FROM proveedores ) AS proveedores ON solpagos.idProveedor = proveedores.idproveedor
            INNER JOIN ( SELECT empresas.idEmpresa, empresas.abrev FROM empresas ) AS empresas ON solpagos.idEmpresa = empresas.idempresa  
            LEFT JOIN solicitud_proyecto_oficina spo  on spo.idsolicitud = solpagos.idsolicitud 
            LEFT JOIN proyectos_departamentos pd  on spo.idProyectos = pd.idProyectos
        ) UNION (
            SELECT 
            0 AS programado,  
            0 AS intereses, 
            solpagos.nomdepto, 
            if(spo.idProyectos is null, solpagos.proyecto, pd.nombre) as proyecto, 
            solpagos.condominio lote,
            autpagos.fecreg fautorizacion, 
            autpagos.fechaDis fdispersion, 
            IFNULL( autpagos.idsolicitud, 'NA' ) AS idpago, 
            IFNULL(proveedores.nom_prov, responsable.nom_responsable) AS responsable, 
            IFNULL(autpagos.cantidad, 0) AS cantidad, 
            historial_cheques.fecha_creacion fecha_operacion, 
            historial_cheques.numCan AS referencia, 
            empresas.abrev, 
            '1' AS bd, 
            'CANCELADO' AS estatus, 
            historial_cheques.fecha_creacion AS orderby 
            FROM historial_cheques 
            JOIN autpagos ON historial_cheques.idautpago = autpagos.idpago AND historial_cheques.tipo = 1 
            JOIN ( SELECT solpagos.idsolicitud, solpagos.idProveedor, solpagos.idEmpresa, solpagos.nomdepto, solpagos.proyecto, solpagos.condominio FROM solpagos ) solpagos ON solpagos.idsolicitud = autpagos.idsolicitud 
            INNER JOIN ( SELECT proveedores.idproveedor, proveedores.nombre nom_prov FROM proveedores ) AS proveedores ON solpagos.idProveedor = proveedores.idproveedor
            INNER JOIN ( SELECT empresas.idEmpresa, empresas.abrev FROM empresas ) AS empresas ON solpagos.idEmpresa = empresas.idempresa  
            LEFT JOIN (SELECT usuarios.idusuario, CONCAT(usuarios.nombres, ' ', usuarios.apellidos) AS nom_responsable FROM usuarios) AS responsable ON autpagos.idrealiza = responsable.idusuario 
            LEFT JOIN solicitud_proyecto_oficina spo  on spo.idsolicitud = solpagos.idsolicitud 
            LEFT JOIN proyectos_departamentos pd  on spo.idProyectos = pd.idProyectos
            WHERE autpagos.cantidad > 0 AND idautpago IS NOT NULL
        ) UNION (
            SELECT 
            0 AS programado, 
            0 AS intereses, 
            autpagos_caja_chica.nomdepto, 
            'NA' proyecto, 
            'NA' lote,
            autpagos_caja_chica.fecreg fautorizacion, 
            autpagos_caja_chica.fechaDis fdispersion, 
            autpagos_caja_chica.idpago, 
            responsable.responsable, 
            autpagos_caja_chica.cantidad, 
            IF(autpagos_caja_chica.fecha_cobro != '0000-00-00' AND autpagos_caja_chica.fecha_cobro IS NOT NULL, autpagos_caja_chica.fecha_cobro, 'SIN COBRAR') AS fecha_operacion, 
            autpagos_caja_chica.referencia, 
            empresas.abrev, 
            '2' AS bd, 
            IF(autpagos_caja_chica.estatus = '16', 'COBRADO', 'ACTIVO') AS estatus, 
            fecha_cobro AS orderby 
            FROM autpagos_caja_chica 
            INNER JOIN (SELECT usuarios.idusuario, CONCAT(usuarios.nombres, ' ', usuarios.apellidos) AS responsable FROM usuarios) AS responsable ON responsable.idusuario = autpagos_caja_chica.idResponsable 
            INNER JOIN empresas ON autpagos_caja_chica.idEmpresa = empresas.idempresa 
            WHERE ( autpagos_caja_chica.tipoPago IN ( 'ECHQ' ) AND autpagos_caja_chica.estatus IN ( 13, 15, 16 ) ) OR (  autpagos_caja_chica.tipoPago IN ( 'EFEC' ) AND autpagos_caja_chica.estatus IN ( 16 ) )
        )UNION (
            SELECT 
            0 programado,
            0 intereses,
            'INGRESO CONTROL' nomdepto, 
            'NA' proyecto, 
            'NA' lote,
            'NA' fautorizacion, 
            'NA' fdispersion, 
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
    
    function cajas_chicas_pagadas($tipo_reembolso){
        $id_usuario = $this->session->userdata("inicio_sesion")['id'];
        $or = "";
        if( $tipo_reembolso ){

        }else{
            $tipo_reembolso = "AND autpagos_caja_chica.nomdepto != 'TARJETA CREDITO'";
        }
        
        $inner = ""; /** INICIO FECHA: 23-SEPTIEMBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
        if (in_array( $this->session->userdata("inicio_sesion")['id'], array( 2932 ))) { //2932 -> XOCHITL CATALINA GOMEZ LOPEZ
            $inner .= "OR (autpagos_caja_chica.idResponsable = ". $id_usuario ." AND autpagos_caja_chica.nomdepto != 'TARJETA CREDITO')"; /** FECHA: 30-SEPTIEMBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
        }/** FIN FECHA: 23-SEPTIEMBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/

        /**INICIO FECHA: 23-OCTUBRE-2024 | @author DANTE ALDAIR GUERRERO ALDANA
        * Filtro para usuario de Anet (ADMON MERCADOTECNIA) para que pueda visualizar las solicitudes de caja chica reembolsadas en el historial.
        * Se filtra por usuario para no generar conflictos con usuarios que estén como supervisores de otros usuarios y se siga cumpliendo con el filtro.
        */
        if(in_array( $this->session->userdata("inicio_sesion")['id'], array( 2876 ))){
            $or = " OR solpagos.idusuario IN (SELECT idusuario FROM usuarios WHERE FIND_IN_SET('$id_usuario', sup))";
        }
        /**INICIO FECHA: 23-OCTUBRE-2024 | @author DANTE ALDAIR GUERRERO ALDANA */

        switch($id_usuario == 2685 ? 'DA' : $this->session->userdata("inicio_sesion")['rol']){
            case 'AS':
            case 'DA':
                return $this->db->query("SELECT 
                                            NULL AS solicitudes,
                                            autpagos_caja_chica.idpago,
                                            autpagos_caja_chica.idResponsable,
                                            autpagos_caja_chica.idsolicitud,
                                            autpagos_caja_chica.tipoPago,
                                            autpagos_caja_chica.referencia,
                                            autpagos_caja_chica.fecha_cobro AS fecha_cobro,
                                            CONCAT(usuarios.nombres,
                                                    ' ',
                                                    usuarios.apellidos) AS responsable,
                                            empresas.abrev,
                                            empresas.idempresa,
                                            autpagos_caja_chica.fecreg AS fecha,
                                            autpagos_caja_chica.cantidad,
                                            autpagos_caja_chica.nomdepto,
                                            responsable_cch.nombre_reembolso_cch
                                        FROM (
                                            SELECT 
                                                autpagos_caja_chica.*
                                            FROM autpagos_caja_chica
                                            INNER JOIN solpagos sp /** FECHA: 07-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                                                        ON sp.idsolicitud IN (autpagos_caja_chica.idsolicitud) AND sp.caja_chica IN (1,4) /** FECHA: 07-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                                            WHERE ( autpagos_caja_chica.nomdepto IN ( SELECT DISTINCT( departamento ) departamento 
                                                                                      FROM departament_usuario 
                                                                                      WHERE idusuario = ? OR idusuario IN (SELECT idusuario FROM usuarios WHERE FIND_IN_SET(?, sup) AND estatus = 1)) AND 
                                                        autpagos_caja_chica.estatus IN ( 14, 15, 16 ) )
                                            $inner ) autpagos_caja_chica
                                        INNER JOIN empresas 
                                            ON autpagos_caja_chica.idEmpresa = empresas.idempresa
                                        INNER JOIN usuarios 
                                            ON usuarios.idusuario = autpagos_caja_chica.idResponsable
                                        LEFT JOIN ( SELECT cajas_ch.idusuario, GROUP_CONCAT(cajas_ch.nombre_reembolso_ch SEPARATOR ', ') AS nombre_reembolso_cch
                                                    FROM cajas_ch
                                                    GROUP BY cajas_ch.idusuario) AS responsable_cch
                                            ON responsable_cch.idusuario = autpagos_caja_chica.idResponsable
                                        ORDER BY autpagos_caja_chica.fecreg DESC", [ $id_usuario, $id_usuario ]); /** FECHA: 30-SEPTIEMBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                break;
            case 'CJ':
                return $this->db->query("SELECT NULL AS solicitudes, 
                                                autpagos_caja_chica.idpago, 
                                                autpagos_caja_chica.idResponsable, 
                                                autpagos_caja_chica.idsolicitud,
                                                autpagos_caja_chica.tipoPago,
                                                autpagos_caja_chica.referencia,
                                                autpagos_caja_chica.fecha_cobro AS fecha_cobro, 
                                                CONCAT(usuarios.nombres, ' ', usuarios.apellidos) AS responsable, 
                                                empresas.abrev, 
                                                empresas.idempresa, 
                                                autpagos_caja_chica.fecreg AS fecha,
                                                autpagos_caja_chica.cantidad,
                                                autpagos_caja_chica.nomdepto,
                                                responsable_cch.nombre_reembolso_cch
                                        FROM autpagos_caja_chica 
                                        INNER JOIN solpagos sp 
                                            ON sp.idsolicitud IN (autpagos_caja_chica.idsolicitud) AND sp.caja_chica = 1 /** FECHA: 07-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                                        INNER JOIN empresas 
                                            ON autpagos_caja_chica.idEmpresa = empresas.idempresa 
                                        INNER JOIN usuarios
                                            ON usuarios.idusuario = autpagos_caja_chica.idResponsable
                                        LEFT JOIN ( SELECT cajas_ch.idusuario, GROUP_CONCAT(cajas_ch.nombre_reembolso_ch SEPARATOR ', ') nombre_reembolso_cch
                                                    FROM cajas_ch
                                                    GROUP BY cajas_ch.idusuario) AS responsable_cch
                                            ON responsable_cch.idusuario = autpagos_caja_chica.idResponsable
                                        WHERE autpagos_caja_chica.nomdepto != 'TARJETA CREDITO' AND 
                                              autpagos_caja_chica.idResponsable = '$id_usuario' AND 
                                              autpagos_caja_chica.estatus IN ( 14, 15, 16) 
                                        ORDER BY autpagos_caja_chica.fecreg DESC");
                break;
            case 'CA':
            case 'FP':
                /** INICIO FECHA: 07-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                return $this->db->query("SELECT 
                                            NULL AS solicitudes,
                                            autpagos_caja_chica.idpago,
                                            autpagos_caja_chica.idResponsable,
                                            autpagos_caja_chica.idsolicitud,
                                            autpagos_caja_chica.tipoPago,
                                            autpagos_caja_chica.referencia,
                                            autpagos_caja_chica.fecha_cobro AS fecha_cobro,
                                            CONCAT(usuarios.nombres, ' ', usuarios.apellidos) AS responsable,
                                            empresas.abrev,
                                            empresas.idempresa,
                                            autpagos_caja_chica.fecreg AS fecha,
                                            autpagos_caja_chica.cantidad,
                                            autpagos_caja_chica.nomdepto,
                                            responsable_cch.nombre_reembolso_cch
                                        FROM autpagos_caja_chica
                                        INNER JOIN (
                                            SELECT
                                                solpagos.idsolicitud 
                                            FROM solpagos
                                            WHERE solpagos.caja_chica = 1
                                            AND solpagos.idusuario = '$id_usuario' $or
                                        ) solpagos ON FIND_IN_SET( solpagos.idsolicitud, autpagos_caja_chica.idsolicitud)
                                        INNER JOIN empresas 
                                            ON autpagos_caja_chica.idEmpresa = empresas.idempresa
                                        INNER JOIN usuarios
                                            ON usuarios.idusuario = autpagos_caja_chica.idResponsable
                                        LEFT JOIN ( SELECT cajas_ch.idusuario, GROUP_CONCAT(cajas_ch.nombre_reembolso_ch SEPARATOR ', ') nombre_reembolso_cch
                                                    FROM cajas_ch
                                                    GROUP BY cajas_ch.idusuario) AS responsable_cch 
                                            ON responsable_cch.idusuario = autpagos_caja_chica.idResponsable
                                        WHERE autpagos_caja_chica.nomdepto != 'TARJETA CREDITO'
                                        AND autpagos_caja_chica.estatus IN ( 14, 15, 16)
                                        GROUP BY autpagos_caja_chica.idpago 
                                        ORDER BY autpagos_caja_chica.fecreg DESC");
                /** FIN FECHA: 07-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                break;
            default:
                return $this->db->query("SELECT 
                                            NULL AS solicitudes, 
                                            autpagos_caja_chica.idpago,
                                            autpagos_caja_chica.estatus,
                                            autpagos_caja_chica.idResponsable,
                                            autpagos_caja_chica.idsolicitud,
                                            autpagos_caja_chica.tipoPago,
                                            autpagos_caja_chica.referencia,
                                            autpagos_caja_chica.fechaDis AS fecha_cobro,
                                            CONCAT(usuarios.nombres, ' ', usuarios.apellidos) AS responsable,
                                            empresas.idempresa,
                                            empresas.abrev,
                                            autpagos_caja_chica.fecreg AS fecha,
                                            autpagos_caja_chica.cantidad,
                                            autpagos_caja_chica.nomdepto,
                                            responsable_cch.nombre_reembolso_cch 
                                    FROM autpagos_caja_chica 
                                    INNER JOIN solpagos sp /** FECHA: 07-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                                        ON sp.idsolicitud IN (autpagos_caja_chica.idsolicitud) AND caja_chica = 1 /** FECHA: 07-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                                    INNER JOIN empresas
                                        ON autpagos_caja_chica.idEmpresa = empresas.idempresa 
                                    INNER JOIN usuarios 
                                        ON usuarios.idusuario = autpagos_caja_chica.idResponsable
                                    LEFT JOIN (
                                            SELECT
                                                cajas_ch.idusuario
                                                , GROUP_CONCAT(cajas_ch.nombre_reembolso_ch SEPARATOR ', ') nombre_reembolso_cch
                                            FROM cajas_ch
                                            GROUP BY cajas_ch.idusuario
                                    ) AS responsable_cch ON responsable_cch.idusuario = autpagos_caja_chica.idResponsable 
                                    WHERE autpagos_caja_chica.nomdepto != 'TARJETA CREDITO' 
                                    AND autpagos_caja_chica.estatus IN ( 5, 14, 15, 16 )
                                    ORDER BY autpagos_caja_chica.fecreg DESC");
                /** FIN FECHA: 07-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
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
            autpagos_caja_chica.nomdepto,
            responsable_cch.nombre_reembolso_cch
        FROM  (
            SELECT  GROUP_CONCAT(s.idsolicitud) idsolicitud, s.idResponsable, s.idEmpresa, 
                    DATE_FORMAT(s.fecha_autorizacion, '%Y-%m-%d') fecelab, SUM(cantidad) cantidad, nomdepto
            FROM solpagos s
            WHERE s.idetapa = 31 AND s.caja_chica = 1
            GROUP BY DATE_FORMAT(s.fecha_autorizacion, '%d-%m-%Y'), s.idResponsable, s.idEmpresa
        ) autpagos_caja_chica 
        INNER JOIN empresas ON autpagos_caja_chica.idEmpresa = empresas.idempresa 
        INNER JOIN listado_usuarios usuarios ON usuarios.idusuario = autpagos_caja_chica.idResponsable
        LEFT JOIN ( SELECT cajas_ch.idusuario, GROUP_CONCAT(cajas_ch.nombre_reembolso_ch SEPARATOR ', ') nombre_reembolso_cch
                    FROM cajas_ch
                    GROUP BY cajas_ch.idusuario) AS responsable_cch 
            ON responsable_cch.idusuario = autpagos_caja_chica.idResponsable
        WHERE autpagos_caja_chica.nomdepto != 'TARJETA CREDITO'
        ORDER BY autpagos_caja_chica.fecelab DESC");
    }

    function tdc_pagadas(){
        switch( $this->session->userdata("inicio_sesion")['rol'] ){
            case 'AS':
            case 'DA':
                return $this->db->query("SELECT 
                    NULL AS solicitudes, 
                    autpagos_caja_chica.idpago,
                    autpagos_caja_chica.idResponsable,
                    autpagos_caja_chica.idsolicitud ID,
                    autpagos_caja_chica.tipoPago,
                    autpagos_caja_chica.referencia,
                    autpagos_caja_chica.fecha_cobro AS fecha_cobro,
                    CONCAT(usuarios.nombres, ' ', usuarios.apellidos) AS responsable,
                    empresas.abrev,
                    autpagos_caja_chica.fecreg AS fecha,
                    autpagos_caja_chica.cantidad,
                    autpagos_caja_chica.nomdepto 
                FROM autpagos_caja_chica 
                INNER JOIN empresas 
                    ON autpagos_caja_chica.idEmpresa = empresas.idempresa 
                INNER JOIN usuarios 
                    ON usuarios.idusuario = autpagos_caja_chica.idResponsable
                WHERE autpagos_caja_chica.nomdepto = '".$this->session->userdata("inicio_sesion")['depto']."' AND autpagos_caja_chica.estatus IN ( 14, 15, 16) 
                ORDER BY autpagos_caja_chica.fecreg DESC");
                break;
            case 'CJ':
                return $this->db->query("SELECT NULL AS solicitudes, autpagos_caja_chica.idpago, autpagos_caja_chica.idResponsable, autpagos_caja_chica.idsolicitud ID, autpagos_caja_chica.tipoPago, autpagos_caja_chica.referencia, autpagos_caja_chica.fecha_cobro AS fecha_cobro, CONCAT(usuarios.nombres, ' ', usuarios.apellidos) AS responsable, empresas.abrev, autpagos_caja_chica.fecreg AS fecha, autpagos_caja_chica.cantidad, autpagos_caja_chica.nomdepto FROM autpagos_caja_chica INNER JOIN empresas ON autpagos_caja_chica.idEmpresa = empresas.idempresa INNER JOIN usuarios ON usuarios.idusuario = autpagos_caja_chica.idResponsable WHERE autpagos_caja_chica.nomdepto = 'TARJETA CREDITO' AND autpagos_caja_chica.idResponsable = '".$this->session->userdata("inicio_sesion")['id']."' AND autpagos_caja_chica.estatus IN ( 14, 15, 16) ORDER BY autpagos_caja_chica.fecreg DESC");
                break;
            case 'CA':
            case 'FP':
                return $this->db->query("SELECT NULL AS solicitudes, autpagos_caja_chica.idpago, autpagos_caja_chica.idResponsable, autpagos_caja_chica.idsolicitud ID, autpagos_caja_chica.tipoPago, autpagos_caja_chica.referencia, autpagos_caja_chica.fecha_cobro AS fecha_cobro, CONCAT(usuarios.nombres, ' ', usuarios.apellidos) AS responsable, empresas.abrev, autpagos_caja_chica.fecreg AS fecha, autpagos_caja_chica.cantidad, autpagos_caja_chica.nomdepto FROM autpagos_caja_chica INNER JOIN ( SELECT solpagos.idsolicitud FROM solpagos WHERE solpagos.idusuario = '".$this->session->userdata("inicio_sesion")['id']."' ) solpagos ON FIND_IN_SET( solpagos.idsolicitud, autpagos_caja_chica.idsolicitud) INNER JOIN empresas ON autpagos_caja_chica.idEmpresa = empresas.idempresa INNER JOIN usuarios ON usuarios.idusuario = autpagos_caja_chica.idResponsable WHERE autpagos_caja_chica.nomdepto = 'TARJETA CREDITO' AND autpagos_caja_chica.estatus IN ( 14, 15, 16) GROUP BY autpagos_caja_chica.idpago ORDER BY autpagos_caja_chica.fecreg DESC");
                break;
            default:
                return $this->db->query("SELECT 
                    autpagos_caja_chica.idpago, 
                    autpagos_caja_chica.estatus as estado, 
                    autpagos_caja_chica.idResponsable, 
                    autpagos_caja_chica.idsolicitud ID, 
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
                WHERE autpagos_caja_chica.nomdepto = 'TARJETA CREDITO' 
                AND autpagos_caja_chica.estatus IN ( 5, 14, 15, 16 )
                ORDER BY autpagos_caja_chica.fecreg  DESC");
                break;
        }
    }

    function cajas_chicas_pagadas1($fecha_ini, $fecha_fin, $dep, $res, $emp, $cant, $metopago, $fecha, $autorizacion){
        $departamento = $this->session->userdata("inicio_sesion")['depto'];
        $id_usuario = $this->session->userdata("inicio_sesion")['id'];
        switch( $this->session->userdata("inicio_sesion")['rol'] ){
            case 'AS':
            case 'DA':
                return $this->db->query("SELECT NULL AS solicitudes, 
                                                autpagos_caja_chica.idResponsable,
                                                autpagos_caja_chica.idsolicitud,
                                                autpagos_caja_chica.tipoPago,
                                                autpagos_caja_chica.referencia,
                                                DATE_FORMAT( autpagos_caja_chica.fecha_cobro, '%d/%b/%Y') AS fecha_cobro,
                                                CONCAT(usuarios.nombres, ' ', usuarios.apellidos) AS responsable,
                                                empresas.abrev,
                                                DATE_FORMAT( autpagos_caja_chica.fecreg ,'%d/%b/%Y') AS fecha,
                                                autpagos_caja_chica.cantidad,
                                                autpagos_caja_chica.nomdepto
                                        FROM autpagos_caja_chica
                                        INNER JOIN empresas
                                            ON autpagos_caja_chica.idEmpresa = empresas.idempresa
                                        INNER JOIN usuarios
                                            ON usuarios.idusuario = autpagos_caja_chica.idResponsable
                                        WHERE autpagos_caja_chica.nomdepto = '$departamento' AND
                                              autpagos_caja_chica.estatus IN ( 14, 15, 16) AND
                                              DATE_FORMAT( autpagos_caja_chica.fechaDis , '%d/%b/%Y') LIKE '%$autorizacion%' AND 
                                              DATE_FORMAT( autpagos_caja_chica.fecreg ,'%d/%b/%Y') LIKE '%$fecha%' AND
                                              autpagos_caja_chica.tipoPago LIKE '%$metopago%' AND
                                              autpagos_caja_chica.nomdepto LIKE '%$dep%' AND
                                              empresas.abrev LIKE '%$emp%' AND
                                              autpagos_caja_chica.cantidad LIKE '%$cant%' AND
                                              CONCAT(usuarios.nombres, ' ', usuarios.apellidos) LIKE '%$res%' AND
                                              autpagos_caja_chica.fecreg BETWEEN '$fecha_ini' AND '$fecha_fin' 
                                        ORDER BY autpagos_caja_chica.idResponsable, autpagos_caja_chica.fecreg DESC ");
                break;
            case 'CJ':
                return $this->db->query("SELECT NULL AS solicitudes, 
                                                autpagos_caja_chica.idResponsable,
                                                autpagos_caja_chica.idsolicitud,
                                                autpagos_caja_chica.tipoPago,
                                                autpagos_caja_chica.referencia,
                                                DATE_FORMAT( autpagos_caja_chica.fecha_cobro, '%d/%b/%Y') AS fecha_cobro,
                                                CONCAT(usuarios.nombres, ' ', usuarios.apellidos) AS responsable,
                                                empresas.abrev,
                                                DATE_FORMAT( autpagos_caja_chica.fecreg ,'%d/%b/%Y') AS fecha,
                                                autpagos_caja_chica.cantidad,
                                                autpagos_caja_chica.nomdepto
                                        FROM autpagos_caja_chica
                                        INNER JOIN empresas
                                            ON autpagos_caja_chica.idEmpresa = empresas.idempresa
                                        INNER JOIN usuarios
                                            ON usuarios.idusuario = autpagos_caja_chica.idResponsable 
                                        WHERE autpagos_caja_chica.idResponsable = '$id_usuario' AND
                                              autpagos_caja_chica.estatus IN ( 14, 15, 16) AND
                                              DATE_FORMAT( autpagos_caja_chica.fechaDis , '%d/%b/%Y') LIKE '%$autorizacion%' AND
                                              DATE_FORMAT( autpagos_caja_chica.fecreg ,'%d/%b/%Y') LIKE '%$fecha%' AND
                                              autpagos_caja_chica.tipoPago LIKE '%$metopago%' AND
                                              autpagos_caja_chica.nomdepto LIKE '%$dep%' AND
                                              empresas.abrev LIKE '%$emp%' AND
                                              autpagos_caja_chica.cantidad LIKE '%$cant%' AND
                                              CONCAT(usuarios.nombres, ' ', usuarios.apellidos) LIKE '%$res%' AND
                                              autpagos_caja_chica.fecreg BETWEEN '$fecha_ini' AND '$fecha_fin' 
                                        ORDER BY autpagos_caja_chica.idResponsable, autpagos_caja_chica.fecreg DESC ");
                break;
            case 'CA':
                return $this->db->query("SELECT NULL AS solicitudes, 
                                                autpagos_caja_chica.idResponsable,
                                                autpagos_caja_chica.idsolicitud,
                                                autpagos_caja_chica.tipoPago,
                                                autpagos_caja_chica.referencia,
                                                DATE_FORMAT( autpagos_caja_chica.fecha_cobro, '%d/%b/%Y') AS fecha_cobro,
                                                CONCAT(usuarios.nombres, ' ', usuarios.apellidos) AS responsable,
                                                empresas.abrev,
                                                DATE_FORMAT( autpagos_caja_chica.fecreg ,'%d/%b/%Y') AS fecha,
                                                autpagos_caja_chica.cantidad,
                                                autpagos_caja_chica.nomdepto 
                                        FROM autpagos_caja_chica 
                                        INNER JOIN ( SELECT solpagos.idsolicitud 
                                                     FROM solpagos 
                                                     WHERE solpagos.idusuario = '$id_usuario' ) solpagos 
                                            ON FIND_IN_SET( solpagos.idsolicitud, autpagos_caja_chica.idsolicitud)
                                        INNER JOIN empresas 
                                            ON autpagos_caja_chica.idEmpresa = empresas.idempresa 
                                        INNER JOIN usuarios
                                            ON usuarios.idusuario = autpagos_caja_chica.idResponsable
                                        WHERE autpagos_caja_chica.estatus IN ( 14, 15, 16) AND
                                              DATE_FORMAT( autpagos_caja_chica.fechaDis , '%d/%b/%Y') LIKE '%$autorizacion%' AND
                                              DATE_FORMAT( autpagos_caja_chica.fecreg ,'%d/%b/%Y') LIKE '%$fecha%' AND
                                              autpagos_caja_chica.tipoPago LIKE '%$metopago%' AND
                                              empresas.abrev LIKE '%$emp%' AND
                                              autpagos_caja_chica.cantidad LIKE '%$cant%' AND
                                              autpagos_caja_chica.nomdepto LIKE '%$dep%' AND
                                              CONCAT(usuarios.nombres, ' ', usuarios.apellidos) LIKE '%$res%' AND
                                              autpagos_caja_chica.fecreg BETWEEN '$fecha_ini' AND '$fecha_fin' 
                                        GROUP BY autpagos_caja_chica.idpago ORDER BY autpagos_caja_chica.idResponsable, autpagos_caja_chica.fecreg DESC");
                break;
            default:
                return $this->db->query("SELECT NULL AS solicitudes,
                                                autpagos_caja_chica.estatus,
                                                autpagos_caja_chica.idResponsable,
                                                autpagos_caja_chica.idsolicitud,
                                                autpagos_caja_chica.tipoPago,
                                                autpagos_caja_chica.referencia,
                                                DATE_FORMAT( autpagos_caja_chica.fechaDis , '%d/%b/%Y') AS fecha_cobro,
                                                CONCAT(usuarios.nombres, ' ', usuarios.apellidos) AS responsable,
                                                empresas.abrev,
                                                DATE_FORMAT( autpagos_caja_chica.fecreg ,'%d/%b/%Y') AS fecha,
                                                autpagos_caja_chica.cantidad,
                                                autpagos_caja_chica.nomdepto 
                                        FROM autpagos_caja_chica
                                        INNER JOIN empresas
                                            ON autpagos_caja_chica.idEmpresa = empresas.idempresa
                                        INNER JOIN usuarios ON usuarios.idusuario = autpagos_caja_chica.idResponsable 
                                        WHERE autpagos_caja_chica.estatus IN ( 14, 15, 16) AND
                                              DATE_FORMAT( autpagos_caja_chica.fechaDis , '%d/%b/%Y') LIKE '%$autorizacion%' AND
                                              DATE_FORMAT( autpagos_caja_chica.fecreg ,'%d/%b/%Y') LIKE '%$fecha%' AND
                                              autpagos_caja_chica.tipoPago LIKE '%$metopago%' AND
                                              autpagos_caja_chica.nomdepto LIKE '%$dep%' AND
                                              CONCAT(usuarios.nombres, ' ', usuarios.apellidos) LIKE '%$res%' AND
                                              empresas.abrev LIKE '%$emp%' AND
                                              autpagos_caja_chica.cantidad LIKE '%$cant%' AND
                                              autpagos_caja_chica.fecreg BETWEEN '$fecha_ini' AND
                                              '$fecha_fin' 
                                        ORDER BY autpagos_caja_chica.idResponsable, autpagos_caja_chica.fecreg DESC");
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
        return $this->db->query("SELECT 
                empresas.abrev, 
                solpagos.moneda, 
                solpagos.cantidad, 
                solpagos.proyecto, 
                solpagos.justificacion, 
                solpagos.nomdepto, 
                proveedores.nombre 
            FROM autpagos 
            INNER JOIN solpagos ON solpagos.idsolicitud = autpagos.idsolicitud 
            INNER JOIN usuarios ON usuarios.idusuario = autpagos.idrealiza 
            INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa 
            inner join proveedores on proveedores.idproveedor = solpagos.idProveedor 
            WHERE autpagos.idrealiza='".$idResponsable."' 
                and autpagos.fecreg like '".$fecha_autorizacion."%' 
            ORDER BY empresas.abrev")->result_array();
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
                return $this->db->query("SELECT 
                    solpagos.idusuario, 
                    solpagos.idsolicitud, 
                    solpagos.folio, 
                    solpagos.moneda, 
                    solpagos.justificacion, 
                    empresas.abrev, 
                    solpagos.nomdepto, 
                    proveedores.nombre AS nombre_proveedor, 
                    DATE_FORMAT( solpagos.fecelab, '%d/%m/%Y') AS fecelab, 
                    DATE_FORMAT( solpagos.fecelab, '%d/%m/%Y') AS fecautorizacion, 
                    CONCAT(usuarios.nombres, ' ', usuarios.apellidos) AS nombre_capturista, 
                    solpagos.cantidad, 
                    if(spo.idProyectos is null, solpagos.proyecto, pd.nombre) as proyecto, 
                    solpagos.folio, 
                    solpagos.justificacion, 
                    IFNULL(SUBSTRING(facturas.uuid, -5, 5 ), 'SF') AS ffiscal 
                FROM solpagos 
                INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa 
                INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor 
                INNER JOIN usuarios ON usuarios.idusuario = solpagos.idusuario 
                LEFT JOIN (
                    SELECT 
                        *,
                        MIN(facturas.feccrea) 
                    FROM facturas 
                    WHERE facturas.tipo_factura IN ( 1, 3 ) 
                    GROUP BY facturas.idsolicitud) AS facturas ON facturas.idsolicitud = solpagos.idsolicitud
                LEFT JOIN solicitud_proyecto_oficina spo  on spo.idsolicitud = solpagos.idsolicitud 
                LEFT JOIN proyectos_departamentos pd  on spo.idProyectos = pd.idProyectos
                WHERE solpagos.idetapa = 11 
                    AND solpagos.caja_chica = 1 
                    AND solpagos.nomdepto = '".$this->session->userdata("inicio_sesion")['depto']."'
                    ORDER BY solpagos.fecha_autorizacion")->result_array();
                break;
            case 'CJ':
                return $this->db->query("SELECT 
                        solpagos.idusuario, 
                        solpagos.idsolicitud, 
                        solpagos.folio, 
                        solpagos.moneda, 
                        solpagos.justificacion, 
                        empresas.abrev, 
                        solpagos.nomdepto, 
                        proveedores.nombre AS nombre_proveedor,
                        DATE_FORMAT( solpagos.fecelab, '%d/%m/%Y') AS fecelab, 
                        DATE_FORMAT( solpagos.fecelab, '%d/%m/%Y') AS fecautorizacion,
                        CONCAT(usuarios.nombres, ' ', usuarios.apellidos) AS nombre_capturista,
                        solpagos.cantidad,
                        if(spo.idProyectos is null, solpagos.proyecto, pd.nombre) as proyecto,
                        solpagos.folio, 
                        solpagos.justificacion, 
                        IFNULL(SUBSTRING(facturas.uuid, -5, 5 ), 'SF') AS ffiscal 
                    FROM solpagos 
                    INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa 
                    INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor 
                    INNER JOIN usuarios ON usuarios.idusuario = solpagos.idusuario 
                    LEFT JOIN (
                        SELECT 
                            *, 
                            MIN(facturas.feccrea) 
                        FROM facturas 
                        WHERE facturas.tipo_factura IN ( 1, 3 ) 
                        GROUP BY facturas.idsolicitud) AS facturas ON facturas.idsolicitud = solpagos.idsolicitud
                    LEFT JOIN solicitud_proyecto_oficina spo  on spo.idsolicitud = solpagos.idsolicitud 
                    LEFT JOIN proyectos_departamentos pd  on spo.idProyectos = pd.idProyectos
                    WHERE solpagos.idetapa = 11 
                        AND solpagos.caja_chica = 1 
                        AND solpagos.idResponsable = '".$this->session->userdata("inicio_sesion")['id']."' 
                        ORDER BY solpagos.fecha_autorizacion")->result_array();
                break;
            case 'CA':
                return $this->db->query("SELECT 
                    solpagos.idusuario, 
                    solpagos.idsolicitud, 
                    solpagos.folio, 
                    solpagos.moneda, 
                    solpagos.justificacion, 
                    empresas.abrev, 
                    solpagos.nomdepto, 
                    proveedores.nombre AS nombre_proveedor,
                    DATE_FORMAT( solpagos.fecelab, '%d/%m/%Y') AS fecelab, 
                    DATE_FORMAT( solpagos.fecelab, '%d/%m/%Y') AS fecautorizacion, 
                    CONCAT(usuarios.nombres, ' ', usuarios.apellidos) AS nombre_capturista, 
                    solpagos.cantidad,
                    if(spo.idProyectos is null, solpagos.proyecto, pd.nombre) as proyecto, 
                    solpagos.folio, 
                    solpagos.justificacion, 
                    IFNULL(SUBSTRING(facturas.uuid, -5, 5 ), 'SF') AS ffiscal 
                FROM solpagos 
                INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa 
                INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor 
                INNER JOIN usuarios ON usuarios.idusuario = solpagos.idusuario 
                LEFT JOIN (
                    SELECT 
                        *,
                        MIN(facturas.feccrea) 
                    FROM facturas 
                    WHERE facturas.tipo_factura IN ( 1, 3 ) 
                    GROUP BY facturas.idsolicitud) AS facturas ON facturas.idsolicitud = solpagos.idsolicitud
                    LEFT JOIN solicitud_proyecto_oficina spo  on spo.idsolicitud = solpagos.idsolicitud 
                    LEFT JOIN proyectos_departamentos pd  on spo.idProyectos = pd.idProyectos
                WHERE solpagos.idetapa = 11 
                    AND solpagos.caja_chica = 1 
                    AND solpagos.idusuario = '".$this->session->userdata("inicio_sesion")['id']."' 
                    ORDER BY solpagos.fecha_autorizacion")->result_array();
                break;
            default:
                return $this->db->query("SELECT 
                        solpagos.idusuario, 
                        solpagos.idsolicitud, 
                        solpagos.folio, 
                        solpagos.moneda, 
                        solpagos.justificacion, 
                        empresas.abrev, 
                        solpagos.nomdepto, 
                        proveedores.nombre AS nombre_proveedor, 
                        DATE_FORMAT( solpagos.fecelab, '%d/%m/%Y') AS fecelab, 
                        DATE_FORMAT( solpagos.fecelab, '%d/%m/%Y') AS fecautorizacion, 
                        CONCAT(usuarios.nombres, ' ', usuarios.apellidos) AS nombre_capturista, 
                        solpagos.cantidad, 
                        if(spo.idProyectos is null, solpagos.proyecto, pd.nombre) as proyecto, 
                        solpagos.folio, 
                        solpagos.justificacion, 
                        IFNULL(SUBSTRING(facturas.uuid, -5, 5 ), 'SF') AS ffiscal 
                    FROM solpagos 
                    INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa 
                    INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor 
                    INNER JOIN usuarios ON usuarios.idusuario = solpagos.idusuario 
                    LEFT JOIN (
                        SELECT 
                            *,
                            MIN(facturas.feccrea) 
                        FROM facturas 
                        WHERE facturas.tipo_factura IN ( 1, 3 ) 
                        GROUP BY facturas.idsolicitud) AS facturas ON facturas.idsolicitud = solpagos.idsolicitud
                    LEFT JOIN solicitud_proyecto_oficina spo  on spo.idsolicitud = solpagos.idsolicitud 
                    LEFT JOIN proyectos_departamentos pd  on spo.idProyectos = pd.idProyectos
                    WHERE solpagos.idetapa = 11 
                        AND solpagos.caja_chica = 1 
                    ORDER BY solpagos.fecha_autorizacion")->result_array();
                break;
        }
    }

    function get_solicitudes_nuevas_caja_chica_propuesta( $idsolicitudes ){
        return $this->db->query("SELECT solpagos.idusuario, 
                                        solpagos.idsolicitud, 
                                        solpagos.folio,
                                        solpagos.moneda,
                                        solpagos.justificacion,
                                        empresas.abrev,
                                        solpagos.nomdepto,
                                        proveedores.nombre AS nombre_proveedor,
                                        DATE_FORMAT( solpagos.fecelab, '%d/%m/%Y') AS fecelab,
                                        DATE_FORMAT( solpagos.fecha_autorizacion, '%d/%m/%Y') AS fecautorizacion, /** FECHA: 27-FEBRERO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                                        CONCAT(usuarios.nombres, ' ', usuarios.apellidos) AS nombre_capturista,
                                        solpagos.cantidad,
                                        ifnull(solpagos.proyecto, pd.nombre) as proyecto, 
                                        solpagos.folio,
                                        solpagos.justificacion,
                                        IFNULL(SUBSTRING(facturas.uuid, -5, 5 ), 'SF') AS ffiscal,
                                        CASE solpagos.caja_chica WHEN 1 THEN 'CAJA CHICA' WHEN 4 THEN 'VIÁTICOS' WHEN 3 THEN 'REEMBOLSO' ELSE '-' END AS tipo_sol, /* FECHA: 02-ENERO-2025 @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                                        e.estado,
                                        e.pais,
                                        z.nombre AS zona,
                                        solpagos.colabs as colabs,
                                        CASE 
                                            WHEN solpagos.tipo_insumo = 1 THEN IFNULL(CONCAT('DESAYUNO (', se.diasDesayuno, ' días)'), 'DESAYUNO')
                                            WHEN solpagos.tipo_insumo = 2 THEN IFNULL(CONCAT('COMIDA (', se.diasComida, ' días)'), 'COMIDA')
                                            WHEN solpagos.tipo_insumo = 3 THEN IFNULL(CONCAT('CENA (', se.diasCena, ' días)'), 'CENA')
                                            WHEN solpagos.tipo_insumo = 4 THEN CONCAT(IFNULL(CONCAT('DESAYUNO (', se.diasDesayuno, ' días)'), 'DESAYUNO'), ', ', IFNULL(CONCAT('COMIDA (', se.diasComida, ' días)'), 'COMIDA'))
                                            WHEN solpagos.tipo_insumo = 5 THEN CONCAT(IFNULL(CONCAT('DESAYUNO (', se.diasDesayuno, ' días)'), 'DESAYUNO'), ', ', IFNULL(CONCAT('CENA (', se.diasCena, ' días)'), 'CENA'))
                                            WHEN solpagos.tipo_insumo = 6 THEN CONCAT(IFNULL(CONCAT('COMIDA (', se.diasComida, ' días)'), 'COMIDA'), ', ', IFNULL(CONCAT('CENA (', se.diasCena, ' días)'), 'CENA'))
                                            WHEN solpagos.tipo_insumo = 7 THEN CONCAT(IFNULL(CONCAT('DESAYUNO (', se.diasDesayuno, ' días)'), 'DESAYUNO'), ', ', IFNULL(CONCAT('COMIDA (', se.diasComida, ' días)'), 'COMIDA'), ', ', IFNULL(CONCAT('CENA (', se.diasCena, ' días)'), 'CENA'))
                                            ELSE 'N/A'
                                        END AS tipo_insumo
                                FROM solpagos 
                                INNER JOIN empresas 
                                    ON empresas.idempresa = solpagos.idEmpresa 
                                INNER JOIN proveedores 
                                    ON proveedores.idproveedor = solpagos.idProveedor 
                                INNER JOIN usuarios
                                    ON usuarios.idusuario = solpagos.idusuario
                                LEFT JOIN ( SELECT *, MIN(facturas.feccrea) 
                                            FROM facturas 
                                            WHERE facturas.tipo_factura IN ( 1, 3 )
                                            GROUP BY facturas.idsolicitud) AS facturas
                                    ON facturas.idsolicitud = solpagos.idsolicitud 
                                LEFT JOIN solicitud_proyecto_oficina spo  on spo.idsolicitud = solpagos.idsolicitud 
                                LEFT JOIN proyectos_departamentos pd  on spo.idProyectos = pd.idProyectos
                                LEFT JOIN solicitud_estados se on solpagos.idsolicitud = se.idsolicitud
                                LEFT JOIN estados e on e.id_estado = se.id_estado
                                LEFT JOIN zonas z on e.idZonas = z.idZonas
                                WHERE solpagos.idetapa IN ( 5, 7, 11, 31 ) AND
                                      solpagos.caja_chica != 0 AND
                                      FIND_IN_SET( solpagos.idsolicitud, '$idsolicitudes' )
                                ORDER BY solpagos.fecha_autorizacion")->result_array();
    }

    function backPag( $id, $idSol ){
        // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
        $this->db->trans_start();
        $this->db->query("UPDATE autpagos SET estatus = 0 , formaPago = 'TEA' , tipoPago = 'TEA' , fecha_pago = null WHERE idpago = '$id'");
        $this->db->query("UPDATE solpagos SET metoPago = 'TEA' WHERE idsolicitud = '$idSol'");
        return $this->db->trans_complete();
    }


     function update_referencia_cancelar($bd,$idpago){
        // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
 
        if($bd=='1'){
             $this->db->update(($bd == 1 ? "autpagos" : "autpagos_caja_chica"), array("estatus" => "98"), "idpago = '$idpago'");
            $cons = $this->db->query("SELECT idsolicitud from autpagos WHERE idpago = '".$idpago."'");
            $this->db->query("UPDATE solpagos SET idetapa = 30  WHERE idsolicitud = '".$cons->row()->idsolicitud."'");
            log_sistema($this->session->userdata("inicio_sesion")['id'], $cons->row()->idsolicitud, "CANCELACION DEFINITIVA DE CHEQUE");
        }else{
            $this->db->update(($bd == 1 ? "autpagos" : "autpagos_caja_chica"), array("estatus" => "98"), "idpago = '$idpago'");
        } 
        // $cons = $this->db->query("SELECT idsolicitud from autpagos WHERE idpago = '".$idpago."'");
        // $this->db->query("UPDATE solpagos SET idetapa = 30  WHERE idsolicitud = '".$cons->row()->idsolicitud."'");

    }




    function get_solicitudes_programadas( $estatus ){

        /** INICIO FECHA: 17-JUNIO-2025 | PANEL CP A USUARIOS ESPECIFICOS | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> 
         * 2409	- AS - cap.finanza LUGO SOSA	JUAN MANUEL
         * 2681	- CA - VA.MONCADA MONCADA GUERRA VALERIA EDITH
         * **/
        $proveedoresEspecificos = '';
        if (in_array( $this->session->userdata("inicio_sesion")['id'], [ 2681, 2409 ] )) {
            $proveedoresEspecificos = 'AND solpagos.idProveedor IN (341,355) OR solpagos.idsolicitud IN (188800, 208038)'; /** FECHA: 08-07-2025 | PETICION CHAT GOOGLE: AGREGAR ESPECIFICAMENTE ESTAS SOLICITUDES: 188800, 208038 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com>*/
        }

            return $this->db->query("SELECT 
                    IFNULL(ptotales, 0) ptotales, tparcial,
                    IF( solpagos.fecha_fin IS NULL, 
                        'SIN DEFINIR', 
                        ROUND(CASE 
                                WHEN solpagos.programado = 8 THEN 
                                    ROUND(TIMESTAMPDIFF(DAY,solpagos.fecelab, solpagos.fecha_fin )/15) 
                                WHEN solpagos.programado < 7 THEN 
                                    TIMESTAMPDIFF(MONTH, CONCAT( YEAR(solpagos.fecelab),'-',MONTH(solpagos.fecelab),'-', DAY(solpagos.fecelab) ), solpagos.fecha_fin) / solpagos.programado 
                                ELSE TIMESTAMPDIFF(WEEK, solpagos.fecelab, solpagos.fecha_fin)
                              END ) + 1 ) ppago,
                    IF(solpagos.idetapa != 11, (CASE WHEN solpagos.programado = 8 THEN IF(ptotales >= TIMESTAMPDIFF(DAY,solpagos.fecelab, solpagos.fecha_fin )/15 , solpagos.fecha_fin , DATE_ADD( solpagos.fecelab, INTERVAL (IFNULL(ptotales, 0)*15) DAY )) WHEN solpagos.programado < 7 THEN DATE_ADD( solpagos.fecelab, INTERVAL ( IFNULL(ptotales, 0) / solpagos.programado ) MONTH ) ELSE DATE_ADD( solpagos.fecelab, INTERVAL IFNULL(ptotales, 0) WEEK ) END ), solpagos.fecha_fin) proximo_pago,
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
                FROM ( 
                    SELECT 
                        * 
                    FROM solpagos 
                    WHERE solpagos.idetapa IN ( 5, 9, 7, 11 ) 
                    AND solpagos.programado IS NOT NULL 
                    AND solpagos.idetapa NOT IN ( $estatus )
                    $proveedoresEspecificos
                ) solpagos
                INNER JOIN proveedores 
                    ON proveedores.idproveedor = solpagos.idProveedor
                INNER JOIN empresas 
                    ON solpagos.idempresa = empresas.idempresa
                LEFT JOIN ( SELECT  autpagos.idsolicitud, 
                                    COUNT(autpagos.idpago) AS ptotales,
                                    SUM(autpagos.cantidad) AS tparcial,
                                    SUM( IF(estatus IN ( 14, 16), autpagos.cantidad,0) ) AS cantidad_confirmada,
                                    SUM(IF(estatus IN ( 14, 16), autpagos.interes, 0) ) AS interes,
                                    MAX( autpagos.fecreg ) AS ultimo_pago 
                            FROM autpagos 
                            GROUP BY autpagos.idsolicitud) AS autpagos 
                    ON solpagos.idsolicitud = autpagos.idsolicitud
                LEFT JOIN ( SELECT autpagos.idsolicitud, autpagos.estatus estatus_ultimo_pago 
                            FROM autpagos 
                            WHERE autpagos.estatus NOT IN ( 14, 16)
                            GROUP BY autpagos.idsolicitud
                            HAVING MAX(autpagos.idpago) ) AS estatus_pago 
                    ON estatus_pago.idsolicitud = solpagos.idsolicitud
                INNER JOIN (SELECT usuarios.idusuario, CONCAT(usuarios.nombres,' ', usuarios.apellidos) AS nombre_capturista 
                            FROM usuarios ) AS usuarios
                    ON usuarios.idusuario = solpagos.idusuario
                LEFT JOIN (SELECT idsolicitud,COUNT(idplan_pago) AS cant_plan
                           FROM planes_pagosprog
                           WHERE estatus = 1
                           GROUP BY idsolicitud) AS pp
                    ON pp.idsolicitud=solpagos.idsolicitud 
            ORDER BY proveedores.nombre ASC");
    }

    function insertHC($data) {
        $this->db->insert("historial_cheques", $data);
    }

    function update_referencia( $bd, $serie, $idpago, $cuenta ) {
        // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
        $this->db->update(($bd == 1 ? "autpagos" : "autpagos_caja_chica"), array("referencia" => $serie, "estatus" => ($bd == 1 ? 1 : 5)), "idpago = '$idpago'");
        $consecutivo = $serie + 1 ;
        $this->db->query("UPDATE serie_cheques SET serie = '".$consecutivo."' WHERE idCuenta = '$cuenta'");
    }
    
    function numserie($cuenta){
        $this->db->where("idCuenta", $cuenta);
       $serie = $this->db->get('serie_cheques');
       return ($serie->num_rows() > 0) ? $serie->row() : null;
    }

    function autCheque($bd, $data, $idpago, $estatus_actual ) {
        $this->db->update( $bd, $data, "idpago = '$idpago' AND estatus = '$estatus_actual'");
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
	
	function cajas_chicas_transito($post) {
        /*$fechaInicioDefecto = (new DateTimeImmutable('first day of January'))->format('Y-m-d 00:00:00');
        $fechaFinDefecto = (new DateTimeImmutable())->format('Y-m-d 23:59:59');
    
        $where = "cajas_chicas_transito.fecha BETWEEN '$fechaInicioDefecto' AND '$fechaFinDefecto'";
    
        $fechaInicio = $post['fechaInicio'];
        $fechaFin = $post['fechaFin'];
        
        if($fechaInicio && $fechaFin){
            $fechaI = $fechaInicio 
                ? DateTime::createFromFormat('d/m/Y', $fechaInicio)
                : date("Y").'-01-01'.' 00:00:00';
            
            if ($fechaI instanceof DateTime) 
                $fechaI = $fechaI->format('Y-m-d').' 00:00:00';
            

            $fechaF = $fechaFin
                ? DateTime::createFromFormat('d/m/Y', $fechaFin)
                : date("Y-m-d").' 23:59:59';

            if ($fechaF instanceof DateTime) 
                $fechaF = $fechaF->format('Y-m-d').' 23:59:59';

            $where = "cajas_chicas_transito.fecha BETWEEN '". $fechaI."' AND '". $fechaF."'";
        }*/
        
        $query = "SELECT * FROM cajas_chicas_transito";

        return $this->db->query($query);
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
            null solicitudes,
            s.idetapa as estado
        FROM
        ( SELECT 
            GROUP_CONCAT(s.idsolicitud) idsolicitud, 
            s.idempresa, 
            s.fecelab, 
            SUM(s.cantidad) cantidad,
            tdc.idproveedor,
            s.idetapa
        FROM ( SELECT GROUP_CONCAT( idsolicitud ) idsolicitud, idresponsable, idempresa, MAX( fecelab ) fecelab, SUM( cantidad ) cantidad, idetapa FROM solpagos s WHERE caja_chica = 2 AND idetapa IN ( 5, 7 ) GROUP BY idresponsable, idempresa, idetapa ) s
        INNER JOIN listado_tdc tdc ON tdc.idtcredito = s.idresponsable 
        GROUP BY tdc.idproveedor, s.idetapa ) s
        INNER JOIN empresas e ON s.idempresa = e.idempresa
        INNER JOIN proveedores p ON s.idproveedor = p.idproveedor");
    }

    //HISTORIAL DE CUENTAS POR PAGAR - PAGO A FACTORAJE
    function getHSolFactorajes() {
        ini_set('memory_limit','-1');
        return $this->db->query("SELECT * FROM listado_factoraje");
    }

    //
    function getHistorialPagosDir($post) {
        $where = " YEAR(autpagos.fechaDis) = ".date("Y");

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

            
             $where = "(autpagos.fechaDis BETWEEN '". $fechaInicio."' AND '". $fechaFin."') OR (autpagos.fecha_cobro BETWEEN '". $fechaInicio."' AND '". $fechaFin."')";
        }
        
        return $this->db->query("SELECT 
            autpagos.referencia as ref, 
            DATE_FORMAT(autpagos.fecreg, '%d/%b/%Y') AS fechaaut, 
            autpagos.estatus, 
            autpagos.estatus as estapag, 
            autpagos.cantidad, 
            IFNULL(DATE_FORMAT( autpagos.fecha_cobro, '%d/%m/%Y'), '') AS fechaOpe, 
            IFNULL(autpagos.formaPago, '') AS formaPago, 
            IFNULL(autpagos.referencia, '') AS referencia, 
            IFNULL(DATE_FORMAT(autpagos.fechaDis, '%d/%b/%Y'),'') AS fecha_dispersion, 
            IFNULL(DATE_FORMAT(autpagos.fecreg, '%d/%b/%Y'),'') AS fecreg_2,
            if(spo.idProyectos is null, solpagos.proyecto, pd.nombre) as proyecto, solpagos.cantidad AS solicitado, 
            solpagos.metoPago, 
            solpagos.folio, 
            solpagos.moneda, 
            solpagos.justificacion, 
            solpagos.idsolicitud, 
            solpagos.etapa, 
            solpagos.condominio, 
            DATE_FORMAT(solpagos.fecelab , '%d/%m/%Y') AS fecha_factura, 
            DATE_FORMAT(solpagos.fechaCreacion, '%d/%m/%Y') AS fecha_captura,  
            solpagos.nomdepto, 
            CONCAT( capturista.nombres, ' ', capturista.apellidos) nombre_capturista, 
	        CONCAT( responsable.nombres, ' ', responsable.apellidos ) nombre_responsable, 
            proveedores.nombre, 
            empresas.abrev, 
            IFNULL(SUBSTR(facturas.uuid, -1, 5), 'SF') AS uuid 
        FROM autpagos autpagos 
        CROSS JOIN solpagos ON autpagos.idsolicitud = solpagos.idsolicitud 
        CROSS JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor 
        CROSS JOIN usuarios AS capturista ON capturista.idusuario = solpagos.idusuario 
        CROSS JOIN usuarios AS responsable ON responsable.idusuario = solpagos.idResponsable 
        CROSS JOIN empresas ON empresas.idempresa = solpagos.idEmpresa
        LEFT JOIN factura_registro facturas ON facturas.idsolicitud = solpagos.idsolicitud 
        left join solicitud_proyecto_oficina spo  on spo.idsolicitud = solpagos.idsolicitud 
        left join proyectos_departamentos pd  on spo.idProyectos = pd.idProyectos
        WHERE $where
        order by autpagos.fecreg desc;");
    }

    //HISTORIAL DE PAGOS PARA LOS DEPARTAMENTOS (DA, AS, CA, CJ)
    function getHistorialPagosDepto( $fecha_inicio = "", $fecha_fin = "" ) {

        $fecha_fin = date("Y-m-d");
        $fecha_inicio = date('Y-m-d', strtotime( $fecha_fin." -2 year"));
        
        ini_set('memory_limit','-1');

        if(in_array( $this->session->userdata("inicio_sesion")['rol'], array( 'AS', 'DA' )) || $this->session->userdata("inicio_sesion")['id'] == 2685){
            /*
            if($this->session->userdata("inicio_sesion")['depto'] == 'CONSTRUCCION'){
                $departamento = "'NOMINA DESTAJO', 'CONSTRUCCION'";
            }elseif( $this->session->userdata("inicio_sesion")['depto'] == 'COMPRAS' ){
                $departamento = "'CONSTRUCCION', 'COMPRAS', 'JARDINERIA'";
            }else{
                $departamento = "'".$this->session->userdata("inicio_sesion")['depto']."'";
            }

            $filtro = "solpagos.nomdepto IN ( $departamento  )";
            */
            $filtro = "solpagos.nomdepto IN ( SELECT departamento FROM departament_usuario WHERE idusuario = '".$this->session->userdata("inicio_sesion")['id']."' )";
        }

        if(in_array($this->session->userdata("inicio_sesion")['rol'], array( 'CJ', 'CA' )) && $this->session->userdata("inicio_sesion")['id'] != 2685){
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
            if(spo.idProyectos is null, solpagos.proyecto, pd.nombre) as proyecto, 
            solpagos.cantidad AS solicitado, 
            IFNULL(SUBSTR(facturas.uuid, -1, 5), 'SF') AS uuid, 
            autpagos.fechaDis fecha_dispersion
        FROM ( SELECT * FROM autpagos WHERE fecreg BETWEEN '$fecha_inicio 00:00:00' AND '$fecha_fin 23:59:59' ) autpagos 
        INNER JOIN ( 
            SELECT solpagos.* FROM solpagos WHERE $filtro 
            UNION 
            SELECT solpagos.* FROM solpagos
            JOIN proveedores_usuario p ON p.idproveedor = solpagos.idproveedor AND solpagos.nomdepto = p.nomdepto AND p.idusuario = ?
        ) solpagos ON autpagos.idsolicitud = solpagos.idsolicitud 
        INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor 
        INNER JOIN listado_usuarios capturista ON capturista.idusuario = solpagos.idusuario 
        INNER JOIN listado_usuarios responsable ON responsable.idusuario = solpagos.idResponsable 
        INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa 
        LEFT JOIN ( SELECT idsolicitud, uuid FROM factura_registro ) facturas ON facturas.idsolicitud = solpagos.idsolicitud 
        LEFT JOIN ( SELECT idsolicitud, numpoliza FROM polizas_provision GROUP BY polizas_provision.idsolicitud ) polizas_provision ON polizas_provision.idsolicitud = solpagos.idsolicitud 
        LEFT JOIN solicitud_proyecto_oficina spo  on spo.idsolicitud = solpagos.idsolicitud 
        LEFT JOIN proyectos_departamentos pd  on spo.idProyectos = pd.idProyectos
        ORDER BY autpagos.fecreg DESC", [ $this->session->userdata("inicio_sesion")['id'] ]);
    }

    //HISTORIAL DE SOLICITUDES DEVOLUCION Y TRASPASO
    //RECIBE EL PARAMETRO DE QUE ESTATUS SON LOS QUE VA A PODER VISUALIZAR

    /**
     * @uthor Efrain Martinez Muñoz | Fecha : 27/03/2025 
     * Se agrega un LEFT JOIN a esta consulta en la cual obtiene datos de las tablas (historial_documento, etiquetas, etiqueta_sol), y se agregan las columnas idetiqueta,
     * estatusLote y tipo_ doc  que se utilizara para mostrar la atiqueta del lote en la vista.
     */
    function getHistorialDevTrap( $etapas, $finicial, $ffinal ){ /** FECHA: 27-FEBRERO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
        return $this->db->query("SELECT
                solpagos.idsolicitud,
                empresas.abrev,
                IF(spo.idProyectos IS NULL, solpagos.proyecto, pd.nombre) as proyecto, /** FECHA: 10-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                os.nombre oficina, /** FECHA: 10-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                tsp.nombre servicioPartida, /** FECHA: 10-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                solpagos.folio,
                proveedores.nombre,
                solpagos.fecha_autorizacion,
                capturista.nombre_completo,
                solpagos.cantidad, /** FECHA: 07-ABRIL-20255 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                IFNULL(etapas.nombre, 'ELIMINADA') AS etapa,
                etapas.idetapa, /* Inicio @author Efrain Martinez Muñoz | Fecha: 12/08/2024 | Creación de columna idetapa */ 
                solpagos.etapa AS soletapa,
                solpagos.condominio,
                solpagos.condominio lote,
                IFNULL(sdrl.referencia, 'NA') AS referencia, /** FECHA: 07-ABRIL-20255 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                solpagos.metoPago,
                solpagos.nomdepto,
                solpagos.fechaCreacion feccrea,
                solpagos.justificacion,
                DATE (solpagos.fecelab) AS fecelab,
                IFNULL (notifi.visto, 1) AS visto,
                solpagos.moneda,
                cant_pag.fechaDis,
                cant_pag.upago,
                cant_pag.fEntrega,
                cant_pag.fecha_cobro,
                cant_pag.estatus_pago,
                cant_pag.etapa_pago,
                IFNULL (diasTrans, 0) AS diasTrans,
                IFNULL (numCancelados, 0) AS numCancelados,
                IF(msd.predial IS NULL OR msd.predial = '', 'NA', msd.predial) AS predial, /** @author Efrain Martinez Muñoz | Fecha: 18/09/2024 | Creación de columna predial | ACTUALIZACION: 18-09-2024 - @author Angel Victoriano <programador.analista30@ciudadmaderas.com>*/
                IF(msd.mantenimiento IS NULL OR msd.mantenimiento = '', 'NA', msd.mantenimiento) AS mantenimiento, /** @author Efrain Martinez Muñoz | Fecha: 18/09/2024 | Creación de columna mantenimiento | ACTUALIZACION: 18-09-2024 - @author Angel Victoriano <programador.analista30@ciudadmaderas.com>*/
                IF(msd.motivo IS NULL OR msd.motivo = '', 'NA', msd.motivo) AS motivo, /** FECHA: 17-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                IF(msd.detalle_motivo IS NULL OR msd.detalle_motivo = '', 'NA', msd.detalle_motivo) AS detalle_motivo, /** FECHA: 20-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                CASE 
                    WHEN solpagos.idproceso IN (1, 2, 3, 4, 7, 31, 32, 33, 35) THEN
                        'ADMINISTRACIÓN'
                    WHEN solpagos.idproceso IN (5, 6, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 25, 26, 27, 28, 29, 30, 34) THEN
                        'POSTVENTA'
                    ELSE
                        NULL
                END deptoInicia,
                eti.idetiqueta,
                eti.etiqueta AS estatusLote,
                eti.tipo_doc
            FROM ( SELECT * FROM solpagos WHERE solpagos.fechaCreacion BETWEEN '$finicial' AND '$ffinal') solpagos
            JOIN listado_usuarios capturista ON capturista.idusuario = solpagos.idusuario 
            JOIN empresas ON solpagos.idEmpresa = empresas.idempresa 
            JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor 
            LEFT JOIN etapas ON etapas.idetapa = solpagos.idetapa 
            LEFT JOIN ( SELECT * FROM vw_autpagos ) cant_pag ON cant_pag.idsolicitud = solpagos.idsolicitud 
            LEFT JOIN (SELECT idsolicitud, SUM(diastrans) diasTrans, SUM(tipoavance) AS numCancelados FROM log_efi 
            GROUP BY idsolicitud) log_efi ON log_efi.idsolicitud = solpagos.idsolicitud
            LEFT JOIN ( SELECT notificaciones.idsolicitud, notificaciones.visto FROM notificaciones WHERE notificaciones.idusuario = ? GROUP BY notificaciones.idsolicitud ) AS notifi ON notifi.idsolicitud = solpagos.idsolicitud 
            LEFT JOIN motivo_sol_dev msd ON msd.idsolicitud = solpagos.idsolicitud /** FECHA: 17-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
            LEFT JOIN solicitud_proyecto_oficina spo  on spo.idsolicitud = solpagos.idsolicitud 
            LEFT JOIN proyectos_departamentos pd  on spo.idProyectos = pd.idProyectos
            LEFT JOIN oficina_sede os on spo.idOficina = os.idOficina /** FECHA: 10-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
            LEFT JOIN tipo_servicio_partidas tsp on tsp.idTipoServicioPartida = spo.idTipoServicioPartida /** FECHA: 10-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
            LEFT JOIN (
                        SELECT esol.idetiqueta,
                            e.etiqueta,
                            esol.idsolicitud,
                            esol.rol,
                            hd.tipo_doc
                        FROM etiqueta_sol esol
                            JOIN etiquetas e ON esol.idetiqueta = e.idetiqueta
                            AND esol.rol IS NOT NULL
                        LEFT JOIN historial_documento hd ON esol.idsolicitud = hd.idsolicitud
                            AND hd.tipo_doc = 9
                    ) eti ON eti.idsolicitud = solpagos.idsolicitud
            LEFT JOIN solicitud_drl sdrl ON sdrl.idsolicitud = solpagos.idsolicitud /** FECHA: 24-MARZO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
            WHERE solpagos.idetapa IN ?
            AND nomdepto IN (
                SELECT
                    departamento
                FROM departamentos
                INNER JOIN usuario_depto ON iddepartamentos = iddepartamento
                WHERE usuario_depto.idusuario = ?
            )
            ORDER BY solpagos.idsolicitud DESC", array( $this->session->userdata("inicio_sesion")['id'], $etapas, $this->session->userdata("inicio_sesion")['id']  ) );
    }

    function getHistorialTablaSolOri($post) {
        $where = " YEAR(fecelab) = ".date("Y");

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

            in_array($this->session->userdata("inicio_sesion")['rol'], ['FP', 'CX', 'CI', 'CT'])
                ? $where = "fecelab BETWEEN '". $fechaInicio."' AND '". $fechaFin."'"
                : $where = "fecelab BETWEEN '". $fechaInicio."' AND '". $fechaFin."'";
        }

        ini_set('memory_limit','-1');
        switch ($this->session->userdata("inicio_sesion")['rol']) {
            case 'FP':                
                return $this->db->query("SELECT
                        solpagos.idsolicitud, 
                        empresas.abrev, 
                        if(spo.idProyectos is null, solpagos.proyecto, pd.nombre) as proyecto, 
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
                    LEFT JOIN solicitud_proyecto_oficina spo  on spo.idsolicitud = solpagos.idsolicitud 
                    LEFT JOIN proyectos_departamentos pd  on spo.idProyectos = pd.idProyectos
                    WHERE nomdepto IN ( SELECT departamento FROM departamentos INNER JOIN usuario_depto ON iddepartamentos = iddepartamento WHERE usuario_depto.idusuario = ? ) 
                    AND solpagos.idetapa NOT IN ( 1 ) AND
                    $where
                    ORDER BY solpagos.idetapa, solpagos.fechaCreacion DESC", array( $this->session->userdata("inicio_sesion")['id'], $this->session->userdata("inicio_sesion")['id'] ) );
                break;
            case 'CX':
                return $this->db->query("SELECT facturas.nombre_archivo tienefac, solpagos.programado, facturas.tipo_factura, solpagos.idetapa, polizas_provision.idprovision, 
                    IF( solpagos.programado IS NOT NULL, CASE WHEN solpagos.programado < 7 THEN TIMESTAMPDIFF(MONTH, CONCAT( YEAR(solpagos.fecelab),'-',MONTH(solpagos.fecelab),'-', DAY(solpagos.fecelab) ), IFNULL( solpagos.fecha_fin, DATE_ADD( solpagos.fecelab, INTERVAL IF( solpagos.idetapa NOT IN ( 11, 30 ), tpagos + 1, IFNULL(tpagos, 1) )  MONTH) ) ) ELSE TIMESTAMPDIFF(WEEK, solpagos.fecelab, IFNULL(solpagos.fecha_fin, DATE_ADD( solpagos.fecelab, INTERVAL IFNULL( tpagos, 1 )  WEEK) ) ) END, NULL ) mpagar,
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
                FROM solpagos 
                INNER JOIN ( SELECT usuarios.idusuario, CONCAT( usuarios.nombres,' ', usuarios.apellidos ) AS nombre_completo FROM usuarios ) AS capturista ON capturista.idusuario = solpagos.idusuario
                LEFT JOIN ( SELECT CONCAT( usuarios.nombres,' ',usuarios.apellidos ) AS nombredir, usuarios.idusuario FROM usuarios ) AS director ON director.idusuario = solpagos.idResponsable 
                INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor 
                INNER JOIN etapas ON etapas.idetapa = solpagos.idetapa 
                LEFT JOIN (SELECT *, MIN(facturas.feccrea) FROM facturas WHERE facturas.tipo_factura IN ( 1, 3 ) GROUP BY facturas.idsolicitud) AS facturas ON facturas.idsolicitud = solpagos.idsolicitud 
                INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa 
                LEFT JOIN ( SELECT  autpagos.idsolicitud,
                                    autpagos.estatus estatus_pago, 
                                    SUM( IF( autpagos.estatus != 2, autpagos.cantidad, 0 ) ) pagado, 
                                    SUM( autpagos.cantidad ) autorizado, 
                                    MAX( autpagos.fecreg ) fautorizado, 
                                    MAX( autpagos.fechaDis ) fdispersion, 
                                    COUNT(autpagos.idsolicitud) tpagos 
                            FROM autpagos 
                            GROUP BY autpagos.idsolicitud ) AS autpagos ON autpagos.idsolicitud = solpagos.idsolicitud 
                LEFT JOIN ( SELECT  polizas_provision.idprovision, 
                                    polizas_provision.idsolicitud, 
                                    MIN( polizas_provision.fecreg )
                            FROM polizas_provision 
                            GROUP BY polizas_provision.idsolicitud ) AS polizas_provision ON polizas_provision.idsolicitud = solpagos.idsolicitud 
                WHERE   ( solpagos.caja_chica IS NULL OR solpagos.caja_chica = 0 ) AND 
                        ( solpagos.idusuario = '".$this->session->userdata("inicio_sesion")['id']."'OR 
                        FIND_IN_SET( solpagos.idEmpresa, '".$this->session->userdata("inicio_sesion")['depto']."' ) ) AND
                        solpagos.idetapa IN ( 10, 11, 14 ) AND 
                $where
                ORDER BY solpagos.idetapa, solpagos.fechaCreacion DESC");
                break;
            case 'CI':
                return $this->db->query("SELECT * 
                    FROM solicitudes_sin_factura 
                    WHERE ( (solicitudes_sin_factura.nomdepto LIKE 'DEVOLUCION%' OR solicitudes_sin_factura.nomdepto LIKE 'TRASPASO%') AND 
                        solicitudes_sin_factura.proyecto NOT IN ( 'TRASPASO INTERNO', 'DEVOLUCION POR DOMICILIACION', 'DEVOLUCION', 'DEVOLUCION POR DEPOSITO EN GARANTIA', 'DEVOLUCION POR MEDIDOR' ) ) AND
                        solicitudes_sin_factura.idetapa IN (10 , 11, 14) AND
                        $where");
                break;
            case 'CT':
                //return $this->db->query("SELECT * FROM solicitudes_sin_factura WHERE solicitudes_sin_factura.nomdepto NOT IN ( 'NOMINAS', 'TRASPASO', 'DEVOLUCIONES', 'FINIQUITO', 'FINIQUITO POR PARCIALIDAD', 'FINIQUITO POR PARCIALIDAD', 'FINIQUITO POR RENUNCIA' ) AND FIND_IN_SET( solicitudes_sin_factura.idempresa, '".$this->session->userdata("inicio_sesion")['depto']."' ) AND solicitudes_sin_factura.idetapa NOT IN ( 0, 1, 2, 3, 4, 30 ) AND ( caja_chica IS NULL OR caja_chica = 0 )");
                return $this->db->query("SELECT 
                    facturas.tipo_factura,
                    solpagos.idetapa,
                    if(spo.idProyectos is null, solpagos.proyecto, pd.nombre) as proyecto,
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
                FROM solpagos
                INNER JOIN listado_usuarios capturista ON capturista.idusuario = solpagos.idusuario
                LEFT JOIN listado_usuarios director ON director.idusuario = solpagos.idResponsable
                INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor
                INNER JOIN etapas ON etapas.idetapa = solpagos.idetapa
                LEFT JOIN factura_registro facturas ON facturas.idsolicitud = solpagos.idsolicitud
                INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa
                LEFT JOIN vw_autpagos autpagos ON autpagos.idsolicitud = solpagos.idsolicitud
                LEFT JOIN solicitud_proyecto_oficina spo  on spo.idsolicitud = solpagos.idsolicitud 
                LEFT JOIN proyectos_departamentos pd  on spo.idProyectos = pd.idProyectos
                LEFT JOIN
                    (SELECT 
                        polizas_provision.idprovision,
                            polizas_provision.idsolicitud,
                            MIN(polizas_provision.fecreg)
                    FROM
                        polizas_provision
                    GROUP BY polizas_provision.idsolicitud) AS polizas_provision ON polizas_provision.idsolicitud = solpagos.idsolicitud
                    WHERE(  solpagos.caja_chica IS NULL OR solpagos.caja_chica != 0 ) AND 
                            solpagos.idetapa NOT IN ( 0, 1, 2, 3, 4, 25, 30, 42, 45, 47, 49) AND 
                            solpagos.nomdepto NOT IN ( 'NOMINAS', 'TRASPASO', 'DEVOLUCIONES', 'FINIQUITO', 'FINIQUITO POR PARCIALIDAD', 'FINIQUITO POR PARCIALIDAD', 'FINIQUITO POR RENUNCIA' ) AND
                            FIND_IN_SET( solpagos.idempresa, '".$this->session->userdata("inicio_sesion")['depto']."' )
                WHERE $where
                ORDER BY solpagos.fechaCreacion DESC");
                break;
            default:
                return $this->db->query("SELECT * FROM solpagos_cajas_chicas_TODO WHERE $where ORDER BY fecelab ASC");
                break;
        }
    }
    
    function getHistorialTablaSolOri_filtrado($filtro){
        ini_set('memory_limit','-1');
        switch ($this->session->userdata("inicio_sesion")['rol']) {
            case 'DA':
                if( $this->session->userdata("inicio_sesion")['depto'] == 'CONSTRUCCION' )
                    return $this->db->query("SELECT IF(facturas.uuid IS null, 'NO', 'SI') AS tienefac, solpagos.programado, facturas.tipo_factura, solpagos.idetapa, polizas_provision.idprovision, if(spo.idProyectos is null, solpagos.proyecto, pd.nombre) as proyecto,
                    IF( solpagos.programado IS NOT NULL, CASE WHEN solpagos.programado < 7 THEN TIMESTAMPDIFF(MONTH, CONCAT( YEAR(solpagos.fecelab),'-',MONTH(solpagos.fecelab),'-', DAY(solpagos.fecha_fin) ), IFNULL( solpagos.fecha_fin, DATE_ADD( solpagos.fecelab, INTERVAL IF( solpagos.idetapa NOT IN ( 11, 30 ), tpagos + 1, IFNULL(tpagos, 1) )  MONTH) ) ) ELSE TIMESTAMPDIFF(WEEK, solpagos.fecelab, IFNULL(solpagos.fecha_fin, DATE_ADD( solpagos.fecelab, INTERVAL IFNULL( tpagos, 1 )  WEEK) ) ) END, NULL ) mpagar,
                    pago_generado.estatus_pago, solpagos.idetapa, solpagos.folio, solpagos.moneda, SUBSTRING(facturas.uuid, -5, 5) AS folifis, solpagos.metoPago, director.nombredir, capturista.nombre_completo, solpagos.caja_chica, solpagos.idsolicitud, solpagos.justificacion, solpagos.nomdepto, proveedores.nombre, solpagos.cantidad, empresas.abrev, solpagos.fechaCreacion AS feccrea, solpagos.fecha_autorizacion AS fecha_autorizacion, solpagos.fecelab AS fecelab, etapas.nombre AS etapa, IFNULL(pago_aut.autorizado, 0) AS autorizado,   IFNULL(liquidado.pagado, 0) AS pagado, autpagos.fechaDis as fechaDis2 ,solpagos.fecha_autorizacion AS fech_auto  FROM solpagos LEFT JOIN ( SELECT *, autpagos.estatus AS estatus_pago, MAX(autpagos.fecreg) FROM autpagos GROUP BY autpagos.idsolicitud ) AS pago_generado ON pago_generado.idsolicitud = solpagos.idsolicitud INNER JOIN ( SELECT usuarios.idusuario, CONCAT( usuarios.nombres,' ', usuarios.apellidos ) AS nombre_completo FROM usuarios ) AS capturista ON capturista.idusuario = solpagos.idusuario LEFT JOIN (SELECT autpagos.idsolicitud, SUM( autpagos.cantidad ) AS pagado FROM autpagos GROUP BY autpagos.idsolicitud) AS liquidado ON solpagos.idsolicitud = liquidado.idsolicitud LEFT JOIN ( SELECT CONCAT( usuarios.nombres,' ',usuarios.apellidos ) AS nombredir, usuarios.idusuario FROM usuarios ) AS director ON director.idusuario = solpagos.idResponsable INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor INNER JOIN etapas ON etapas.idetapa = solpagos.idetapa LEFT JOIN (SELECT *, MIN(facturas.feccrea) FROM facturas WHERE facturas.tipo_factura IN ( 1, 3 ) GROUP BY facturas.idsolicitud) AS facturas ON facturas.idsolicitud = solpagos.idsolicitud LEFT JOIN ( SELECT autpagos.idsolicitud, SUM(autpagos.cantidad) AS autorizado FROM autpagos WHERE autpagos.estatus NOT IN (2) GROUP BY autpagos.idsolicitud ) AS pago_aut ON pago_aut.idsolicitud = solpagos.idsolicitud  INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa LEFT JOIN ( SELECT *, MAX(autpagos.fecreg), COUNT(autpagos.idsolicitud) tpagos FROM autpagos GROUP BY autpagos.idsolicitud ) AS autpagos ON autpagos.idsolicitud = solpagos.idsolicitud LEFT JOIN ( SELECT polizas_provision.idprovision, polizas_provision.idsolicitud, MIN( polizas_provision.fecreg ) FROM polizas_provision GROUP BY polizas_provision.idsolicitud ) AS polizas_provision ON polizas_provision.idsolicitud = solpagos.idsolicitud left join solicitud_proyecto_oficina spo  on spo.idsolicitud = solpagos.idsolicitud left join proyectos_departamentos pd  on spo.idProyectos = pd.idProyectos WHERE ( solpagos.nomdepto = '" . $this->session->userdata("inicio_sesion")['depto'] . "'  OR solpagos.idusuario IN ( SELECT usuarios.idusuario FROM usuarios WHERE usuarios.da = '".$this->session->userdata("inicio_sesion")['id']."' OR FIND_IN_SET('".$this->session->userdata("inicio_sesion")['id']."', usuarios.sup ) ) ) AND solpagos.idetapa IN ( 7, 9, 10, 11, 14 ) ORDER BY solpagos.idetapa, solpagos.fechaCreacion DESC");             
                else
                    return $this->db->query("SELECT IF(facturas.uuid IS null, 'NO', 'SI') AS tienefac, solpagos.programado, facturas.tipo_factura, solpagos.idetapa, polizas_provision.idprovision, if(spo.idProyectos is null, solpagos.proyecto, pd.nombre) as proyecto,
                    IF( solpagos.programado IS NOT NULL, CASE WHEN solpagos.programado < 7 THEN TIMESTAMPDIFF(MONTH, CONCAT( YEAR(solpagos.fecelab),'-',MONTH(solpagos.fecelab),'-', DAY(solpagos.fecha_fin) ), IFNULL( solpagos.fecha_fin, DATE_ADD( solpagos.fecelab, INTERVAL IF( solpagos.idetapa NOT IN ( 11, 30 ), tpagos + 1, IFNULL(tpagos, 1) )  MONTH) ) ) ELSE TIMESTAMPDIFF(WEEK, solpagos.fecelab, IFNULL(solpagos.fecha_fin, DATE_ADD( solpagos.fecelab, INTERVAL IFNULL( tpagos, 1 )  WEEK) ) ) END, NULL ) mpagar,
                    pago_generado.estatus_pago, solpagos.idetapa, solpagos.folio, solpagos.moneda, SUBSTRING(facturas.uuid, -5, 5) AS folifis, solpagos.metoPago, director.nombredir, capturista.nombre_completo, solpagos.caja_chica, solpagos.idsolicitud, solpagos.justificacion, solpagos.nomdepto, proveedores.nombre, solpagos.cantidad, empresas.abrev, solpagos.fechaCreacion AS feccrea, solpagos.fecha_autorizacion AS fecha_autorizacion, solpagos.fecelab AS fecelab, etapas.nombre AS etapa, IFNULL(pago_aut.autorizado, 0) AS autorizado,   IFNULL(liquidado.pagado, 0) AS pagado, autpagos.fechaDis as fechaDis2 ,solpagos.fecha_autorizacion AS fech_auto  FROM solpagos LEFT JOIN ( SELECT *, autpagos.estatus AS estatus_pago, MAX(autpagos.fecreg) FROM autpagos GROUP BY autpagos.idsolicitud ) AS pago_generado ON pago_generado.idsolicitud = solpagos.idsolicitud INNER JOIN ( SELECT usuarios.idusuario, CONCAT( usuarios.nombres,' ', usuarios.apellidos ) AS nombre_completo FROM usuarios ) AS capturista ON capturista.idusuario = solpagos.idusuario LEFT JOIN (SELECT autpagos.idsolicitud, SUM( autpagos.cantidad ) AS pagado FROM autpagos GROUP BY autpagos.idsolicitud) AS liquidado ON solpagos.idsolicitud = liquidado.idsolicitud LEFT JOIN ( SELECT CONCAT( usuarios.nombres,' ',usuarios.apellidos ) AS nombredir, usuarios.idusuario FROM usuarios ) AS director ON director.idusuario = solpagos.idResponsable INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor INNER JOIN etapas ON etapas.idetapa = solpagos.idetapa LEFT JOIN (SELECT *, MIN(facturas.feccrea) FROM facturas WHERE facturas.tipo_factura IN ( 1, 3 ) GROUP BY facturas.idsolicitud) AS facturas ON facturas.idsolicitud = solpagos.idsolicitud LEFT JOIN ( SELECT autpagos.idsolicitud, SUM(autpagos.cantidad) AS autorizado FROM autpagos WHERE autpagos.estatus NOT IN (2) GROUP BY autpagos.idsolicitud ) AS pago_aut ON pago_aut.idsolicitud = solpagos.idsolicitud  INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa LEFT JOIN ( SELECT *, MAX(autpagos.fecreg), COUNT(autpagos.idsolicitud) tpagos FROM autpagos GROUP BY autpagos.idsolicitud ) AS autpagos ON autpagos.idsolicitud = solpagos.idsolicitud LEFT JOIN ( SELECT polizas_provision.idprovision, polizas_provision.idsolicitud, MIN( polizas_provision.fecreg ) FROM polizas_provision GROUP BY polizas_provision.idsolicitud ) AS polizas_provision ON polizas_provision.idsolicitud = solpagos.idsolicitud left join solicitud_proyecto_oficina spo  on spo.idsolicitud = solpagos.idsolicitud left join proyectos_departamentos pd  on spo.idProyectos = pd.idProyectos  WHERE ( solpagos.nomdepto = '" . $this->session->userdata("inicio_sesion")['depto'] . "'  OR solpagos.idusuario IN ( SELECT usuarios.idusuario FROM usuarios WHERE usuarios.da = '".$this->session->userdata("inicio_sesion")['id']."' OR FIND_IN_SET('".$this->session->userdata("inicio_sesion")['id']."', usuarios.sup ) ) ) AND solpagos.idetapa IN ( 10, 11, 14 ) ORDER BY solpagos.idetapa, solpagos.fechaCreacion DESC"); 
                break;
            case 'AS':
                if( $this->session->userdata("inicio_sesion")['depto'] == 'CONSTRUCCION' ){
                    return $this->db->query("SELECT IF(facturas.uuid IS null, 'NO', 'SI') AS tienefac, solpagos.programado, facturas.tipo_factura, solpagos.idetapa, polizas_provision.idprovision, if(spo.idProyectos is null, solpagos.proyecto, pd.nombre) as proyecto,
                    IF( solpagos.programado IS NOT NULL, CASE WHEN solpagos.programado < 7 THEN TIMESTAMPDIFF(MONTH, CONCAT( YEAR(solpagos.fecelab),'-',MONTH(solpagos.fecelab),'-', DAY(solpagos.fecha_fin) ), IFNULL( solpagos.fecha_fin, DATE_ADD( solpagos.fecelab, INTERVAL IF( solpagos.idetapa NOT IN ( 11, 30 ), tpagos + 1, IFNULL(tpagos, 1) )  MONTH) ) ) ELSE TIMESTAMPDIFF(WEEK, solpagos.fecelab, IFNULL(solpagos.fecha_fin, DATE_ADD( solpagos.fecelab, INTERVAL IFNULL( tpagos, 1 )  WEEK) ) ) END, NULL ) mpagar,
                    pago_generado.estatus_pago, solpagos.idetapa, solpagos.folio, solpagos.moneda, SUBSTRING(facturas.uuid, -5, 5) AS folifis, solpagos.metoPago, director.nombredir, capturista.nombre_completo, solpagos.caja_chica, solpagos.idsolicitud, solpagos.justificacion, solpagos.nomdepto, proveedores.nombre, solpagos.cantidad, empresas.abrev, solpagos.fechaCreacion AS feccrea, solpagos.fecha_autorizacion AS fecha_autorizacion, solpagos.fecelab AS fecelab, etapas.nombre AS etapa, IFNULL(pago_aut.autorizado, 0) AS autorizado,   IFNULL(liquidado.pagado, 0) AS pagado, autpagos.fechaDis as fechaDis2 ,solpagos.fecha_autorizacion AS fech_auto  FROM solpagos LEFT JOIN ( SELECT *, autpagos.estatus AS estatus_pago, MAX(autpagos.fecreg) FROM autpagos GROUP BY autpagos.idsolicitud ) AS pago_generado ON pago_generado.idsolicitud = solpagos.idsolicitud INNER JOIN ( SELECT usuarios.idusuario, CONCAT( usuarios.nombres,' ', usuarios.apellidos ) AS nombre_completo FROM usuarios ) AS capturista ON capturista.idusuario = solpagos.idusuario LEFT JOIN (SELECT autpagos.idsolicitud, SUM( autpagos.cantidad ) AS pagado FROM autpagos GROUP BY autpagos.idsolicitud) AS liquidado ON solpagos.idsolicitud = liquidado.idsolicitud LEFT JOIN ( SELECT CONCAT( usuarios.nombres,' ',usuarios.apellidos ) AS nombredir, usuarios.idusuario FROM usuarios ) AS director ON director.idusuario = solpagos.idResponsable INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor INNER JOIN etapas ON etapas.idetapa = solpagos.idetapa LEFT JOIN (SELECT *, MIN(facturas.feccrea) FROM facturas WHERE facturas.tipo_factura IN ( 1, 3 ) GROUP BY facturas.idsolicitud) AS facturas ON facturas.idsolicitud = solpagos.idsolicitud LEFT JOIN ( SELECT autpagos.idsolicitud, SUM(autpagos.cantidad) AS autorizado FROM autpagos WHERE autpagos.estatus NOT IN (2) GROUP BY autpagos.idsolicitud ) AS pago_aut ON pago_aut.idsolicitud = solpagos.idsolicitud  INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa LEFT JOIN ( SELECT *, MAX(autpagos.fecreg), COUNT(autpagos.idsolicitud) tpagos FROM autpagos GROUP BY autpagos.idsolicitud ) AS autpagos ON autpagos.idsolicitud = solpagos.idsolicitud LEFT JOIN ( SELECT polizas_provision.idprovision, polizas_provision.idsolicitud, MIN( polizas_provision.fecreg ) FROM polizas_provision GROUP BY polizas_provision.idsolicitud ) AS polizas_provision ON polizas_provision.idsolicitud = solpagos.idsolicitud left join solicitud_proyecto_oficina spo  on spo.idsolicitud = solpagos.idsolicitud left join proyectos_departamentos pd  on spo.idProyectos = pd.idProyectos WHERE nomdepto IN ('CONSTRUCCION', 'JARDINERIA', 'NOMINA DESTAJO') AND solpagos.idetapa IN ( 10, 11, 14 ) ORDER BY solpagos.idetapa, solpagos.fechaCreacion DESC");     
                }else{
                    return $this->db->query("SELECT IF(facturas.uuid IS null, 'NO', 'SI') AS tienefac, solpagos.programado, facturas.tipo_factura, solpagos.idetapa, polizas_provision.idprovision, if(spo.idProyectos is null, solpagos.proyecto, pd.nombre) as proyecto,
                    IF( solpagos.programado IS NOT NULL, CASE WHEN solpagos.programado < 7 THEN TIMESTAMPDIFF(MONTH, CONCAT( YEAR(solpagos.fecelab),'-',MONTH(solpagos.fecelab),'-', DAY(solpagos.fecha_fin) ), IFNULL( solpagos.fecha_fin, DATE_ADD( solpagos.fecelab, INTERVAL IF( solpagos.idetapa NOT IN ( 11, 30 ), tpagos + 1, IFNULL(tpagos, 1) )  MONTH) ) ) ELSE TIMESTAMPDIFF(WEEK, solpagos.fecelab, IFNULL(solpagos.fecha_fin, DATE_ADD( solpagos.fecelab, INTERVAL IFNULL( tpagos, 1 )  WEEK) ) ) END, NULL ) mpagar,
                    pago_generado.estatus_pago, solpagos.idetapa, solpagos.folio, solpagos.moneda, SUBSTRING(facturas.uuid, -5, 5) AS folifis, solpagos.metoPago, director.nombredir, capturista.nombre_completo, solpagos.caja_chica, solpagos.idsolicitud, solpagos.justificacion, solpagos.nomdepto, proveedores.nombre, solpagos.cantidad, empresas.abrev, solpagos.fechaCreacion AS feccrea, solpagos.fecha_autorizacion AS fecha_autorizacion, solpagos.fecelab AS fecelab, etapas.nombre AS etapa, IFNULL(pago_aut.autorizado, 0) AS autorizado,   IFNULL(liquidado.pagado, 0) AS pagado, autpagos.fechaDis as fechaDis2 ,solpagos.fecha_autorizacion AS fech_auto  FROM solpagos LEFT JOIN ( SELECT *, autpagos.estatus AS estatus_pago, MAX(autpagos.fecreg) FROM autpagos GROUP BY autpagos.idsolicitud ) AS pago_generado ON pago_generado.idsolicitud = solpagos.idsolicitud INNER JOIN ( SELECT usuarios.idusuario, CONCAT( usuarios.nombres,' ', usuarios.apellidos ) AS nombre_completo FROM usuarios ) AS capturista ON capturista.idusuario = solpagos.idusuario LEFT JOIN (SELECT autpagos.idsolicitud, SUM( autpagos.cantidad ) AS pagado FROM autpagos GROUP BY autpagos.idsolicitud) AS liquidado ON solpagos.idsolicitud = liquidado.idsolicitud LEFT JOIN ( SELECT CONCAT( usuarios.nombres,' ',usuarios.apellidos ) AS nombredir, usuarios.idusuario FROM usuarios ) AS director ON director.idusuario = solpagos.idResponsable INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor INNER JOIN etapas ON etapas.idetapa = solpagos.idetapa LEFT JOIN (SELECT *, MIN(facturas.feccrea) FROM facturas WHERE facturas.tipo_factura IN ( 1, 3 ) GROUP BY facturas.idsolicitud) AS facturas ON facturas.idsolicitud = solpagos.idsolicitud LEFT JOIN ( SELECT autpagos.idsolicitud, SUM(autpagos.cantidad) AS autorizado FROM autpagos WHERE autpagos.estatus NOT IN (2) GROUP BY autpagos.idsolicitud ) AS pago_aut ON pago_aut.idsolicitud = solpagos.idsolicitud  INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa LEFT JOIN ( SELECT *, MAX(autpagos.fecreg), COUNT(autpagos.idsolicitud) tpagos FROM autpagos GROUP BY autpagos.idsolicitud ) AS autpagos ON autpagos.idsolicitud = solpagos.idsolicitud LEFT JOIN ( SELECT polizas_provision.idprovision, polizas_provision.idsolicitud, MIN( polizas_provision.fecreg ) FROM polizas_provision GROUP BY polizas_provision.idsolicitud ) AS polizas_provision ON polizas_provision.idsolicitud = solpagos.idsolicitud left join solicitud_proyecto_oficina spo  on spo.idsolicitud = solpagos.idsolicitud left join proyectos_departamentos pd  on spo.idProyectos = pd.idProyectos WHERE ( solpagos.nomdepto = '" . $this->session->userdata("inicio_sesion")['depto'] . "'  OR solpagos.idusuario IN ( SELECT usuarios.idusuario FROM usuarios WHERE FIND_IN_SET('".$this->session->userdata("inicio_sesion")['id']."', usuarios.sup ) ) ) AND solpagos.idetapa IN ( 10, 11, 14 ) ORDER BY solpagos.idetapa, solpagos.fechaCreacion DESC");     
                }
                break;
            case 'CJ':
            case 'CA':
            case 'CE':
                return $this->db->query("SELECT IFNULL(facturas.nombre_archivo, 'NA') tienefac, solpagos.programado, facturas.tipo_factura, solpagos.idetapa, polizas_provision.idprovision, if(spo.idProyectos is null, solpagos.proyecto, pd.nombre) as proyecto,
                IF( solpagos.programado IS NOT NULL, CASE WHEN solpagos.programado < 7 THEN TIMESTAMPDIFF(MONTH, CONCAT( YEAR(solpagos.fecelab),'-',MONTH(solpagos.fecelab),'-', DAY(solpagos.fecha_fin) ), IFNULL( solpagos.fecha_fin, DATE_ADD( solpagos.fecelab, INTERVAL IF( solpagos.idetapa NOT IN ( 11, 30 ), tpagos + 1, IFNULL(tpagos, 1) )  MONTH) ) ) ELSE TIMESTAMPDIFF(WEEK, solpagos.fecelab, IFNULL(solpagos.fecha_fin, DATE_ADD( solpagos.fecelab, INTERVAL IFNULL( tpagos, 1 )  WEEK) ) ) END, NULL ) mpagar,
                autpagos.estatus_pago, solpagos.idetapa, IFNULL( facturas.foliofac, solpagos.folio) folio, solpagos.moneda, SUBSTRING(facturas.uuid, -5, 5) AS folifis, solpagos.metoPago, director.nombredir, capturista.nombre_completo, solpagos.caja_chica, solpagos.idsolicitud, solpagos.justificacion, solpagos.nomdepto, proveedores.nombre, solpagos.cantidad, empresas.abrev, solpagos.fechaCreacion AS feccrea, solpagos.fecha_autorizacion AS fecha_autorizacion, solpagos.fecelab AS fecelab, IFNULL(autpagos.fautorizado, ' - ') fautorizado, etapas.nombre AS etapa, IFNULL(autpagos.autorizado, 0) AS autorizado, IFNULL(autpagos.pagado, 0) AS pagado, IFNULL(autpagos.fdispersion, ' - ') fechaDis2 FROM solpagos INNER JOIN ( SELECT usuarios.idusuario, CONCAT( usuarios.nombres,' ', usuarios.apellidos ) AS nombre_completo FROM usuarios ) AS capturista ON capturista.idusuario = solpagos.idusuario LEFT JOIN ( SELECT CONCAT( usuarios.nombres,' ',usuarios.apellidos ) AS nombredir, usuarios.idusuario FROM usuarios ) AS director ON director.idusuario = solpagos.idResponsable INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor INNER JOIN etapas ON etapas.idetapa = solpagos.idetapa LEFT JOIN (SELECT *, MIN(facturas.feccrea) FROM facturas WHERE facturas.tipo_factura IN ( 1, 3 ) GROUP BY facturas.idsolicitud) AS facturas ON facturas.idsolicitud = solpagos.idsolicitud INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa LEFT JOIN ( SELECT autpagos.idsolicitud, autpagos.estatus estatus_pago, SUM( IF( autpagos.estatus != 2, autpagos.cantidad, 0 ) ) pagado, SUM( autpagos.cantidad ) autorizado, MAX( autpagos.fecreg ) fautorizado, MAX( autpagos.fechaDis ) fdispersion, COUNT(autpagos.idsolicitud) tpagos FROM autpagos GROUP BY autpagos.idsolicitud ) AS autpagos ON autpagos.idsolicitud = solpagos.idsolicitud LEFT JOIN ( SELECT polizas_provision.idprovision, polizas_provision.idsolicitud, MIN( polizas_provision.fecreg ) FROM polizas_provision GROUP BY polizas_provision.idsolicitud ) AS polizas_provision ON polizas_provision.idsolicitud = solpagos.idsolicitud left join solicitud_proyecto_oficina spo  on spo.idsolicitud = solpagos.idsolicitud left join proyectos_departamentos pd  on spo.idProyectos = pd.idProyectos WHERE /*( solpagos.caja_chica IS NULL OR solpagos.caja_chica != 1 ) AND*/( solpagos.idusuario = '".$this->session->userdata("inicio_sesion")['id']."' OR solpagos.idusuario IN ( SELECT usuarios.idusuario FROM usuarios WHERE FIND_IN_SET('".$this->session->userdata("inicio_sesion")['id']."', usuarios.sup ) ) ) AND solpagos.idetapa IN ( 10, 11, 14 ) ORDER BY solpagos.idetapa, solpagos.fechaCreacion DESC");
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
                    return $this->db->query("SELECT IFNULL(facturas.nombre_archivo, 'NA') tienefac, solpagos.programado, facturas.tipo_factura, solpagos.idetapa, polizas_provision.idprovision, if(spo.idProyectos is null, solpagos.proyecto, pd.nombre) as proyecto,
                    IF( solpagos.programado IS NOT NULL, CASE WHEN solpagos.programado < 7 THEN TIMESTAMPDIFF(MONTH, CONCAT( YEAR(solpagos.fecelab),'-',MONTH(solpagos.fecelab),'-', DAY(solpagos.fecha_fin) ), IFNULL( solpagos.fecha_fin, DATE_ADD( solpagos.fecelab, INTERVAL IF( solpagos.idetapa NOT IN ( 11, 30 ), tpagos + 1, IFNULL(tpagos, 1) )  MONTH) ) ) ELSE TIMESTAMPDIFF(WEEK, solpagos.fecelab, IFNULL(solpagos.fecha_fin, DATE_ADD( solpagos.fecelab, INTERVAL IFNULL( tpagos, 1 )  WEEK) ) ) END, NULL ) mpagar,
                    autpagos.estatus_pago, solpagos.idetapa, IFNULL( facturas.foliofac, solpagos.folio) folio, solpagos.moneda, SUBSTRING(facturas.uuid, -5, 5) AS folifis, solpagos.metoPago, director.nombredir, capturista.nombre_completo, solpagos.caja_chica, solpagos.idsolicitud, solpagos.justificacion, solpagos.nomdepto, proveedores.nombre, solpagos.cantidad, empresas.abrev, solpagos.fechaCreacion AS feccrea, solpagos.fecha_autorizacion AS fecha_autorizacion, solpagos.fecelab AS fecelab, IFNULL(autpagos.fautorizado, ' - ') fautorizado,IFNULL(autpagos.fechapago, ' - ') fechapago, etapas.nombre AS etapa, IFNULL(autpagos.autorizado, 0) AS autorizado, IFNULL(autpagos.pagado, 0) AS pagado, IFNULL(autpagos.fdispersion, ' - ') fechaDis2 FROM solpagos INNER JOIN ( SELECT usuarios.idusuario, CONCAT( usuarios.nombres,' ', usuarios.apellidos ) AS nombre_completo FROM usuarios ) AS capturista ON capturista.idusuario = solpagos.idusuario LEFT JOIN ( SELECT CONCAT( usuarios.nombres,' ',usuarios.apellidos ) AS nombredir, usuarios.idusuario FROM usuarios ) AS director ON director.idusuario = solpagos.idResponsable INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor INNER JOIN etapas ON etapas.idetapa = solpagos.idetapa LEFT JOIN (SELECT *, MIN(facturas.feccrea) FROM facturas WHERE facturas.tipo_factura IN ( 1, 3 )$filtro GROUP BY facturas.idsolicitud) AS facturas ON facturas.idsolicitud = solpagos.idsolicitud INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa LEFT JOIN ( SELECT autpagos.idsolicitud, autpagos.estatus estatus_pago, SUM( IF( autpagos.estatus != 2, autpagos.cantidad, 0 ) ) pagado, SUM( autpagos.cantidad ) autorizado, MAX( autpagos.fecreg ) fautorizado, MAX(`cpp`.`autpagos`.`fecha_cobro`) AS `fechapago`, MAX( autpagos.fechaDis ) fdispersion, COUNT(autpagos.idsolicitud) tpagos FROM autpagos GROUP BY autpagos.idsolicitud ) AS autpagos ON autpagos.idsolicitud = solpagos.idsolicitud LEFT JOIN ( SELECT polizas_provision.idprovision, polizas_provision.idsolicitud, MIN( polizas_provision.fecreg ) FROM polizas_provision GROUP BY polizas_provision.idsolicitud ) AS polizas_provision ON polizas_provision.idsolicitud = solpagos.idsolicitud left join solicitud_proyecto_oficina spo  on spo.idsolicitud = solpagos.idsolicitud left join proyectos_departamentos pd  on spo.idProyectos = pd.idProyectos WHERE /*( solpagos.caja_chica IS NULL OR solpagos.caja_chica != 1 ) AND*/( solpagos.idusuario = '".$this->session->userdata("inicio_sesion")['id']."' OR solpagos.idusuario IN ( SELECT usuarios.idusuario FROM usuarios WHERE FIND_IN_SET('".$this->session->userdata("inicio_sesion")['id']."', usuarios.sup ) ) ) AND solpagos.idetapa IN ( 10, 11, 14 ) ORDER BY solpagos.idetapa, solpagos.fechaCreacion DESC");
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
                FROM solpagos INNER JOIN ( SELECT usuarios.idusuario, CONCAT( usuarios.nombres,' ', usuarios.apellidos ) AS nombre_completo FROM usuarios ) AS capturista ON capturista.idusuario = solpagos.idusuario LEFT JOIN ( SELECT CONCAT( usuarios.nombres,' ',usuarios.apellidos ) AS nombredir, usuarios.idusuario FROM usuarios ) AS director ON director.idusuario = solpagos.idResponsable INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor INNER JOIN etapas ON etapas.idetapa = solpagos.idetapa LEFT JOIN (SELECT *, MIN(facturas.feccrea) FROM facturas WHERE facturas.tipo_factura IN ( 1, 3 ) GROUP BY facturas.idsolicitud) AS facturas ON facturas.idsolicitud = solpagos.idsolicitud INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa LEFT JOIN ( SELECT autpagos.idsolicitud, autpagos.estatus estatus_pago, SUM( IF( autpagos.estatus != 2, autpagos.cantidad, 0 ) ) pagado, SUM( autpagos.cantidad ) autorizado, MAX( autpagos.fecreg ) fautorizado, MAX( autpagos.fechaDis ) fdispersion, COUNT(autpagos.idsolicitud) tpagos FROM autpagos GROUP BY autpagos.idsolicitud ) AS autpagos ON autpagos.idsolicitud = solpagos.idsolicitud LEFT JOIN ( SELECT polizas_provision.idprovision, polizas_provision.idsolicitud, MIN( polizas_provision.fecreg ) FROM polizas_provision GROUP BY polizas_provision.idsolicitud ) AS polizas_provision ON polizas_provision.idsolicitud = solpagos.idsolicitud WHERE ( solpagos.caja_chica IS NULL OR solpagos.caja_chica = 0 ) AND ( solpagos.idusuario = '".$this->session->userdata("inicio_sesion")['id']."' OR FIND_IN_SET( solpagos.idEmpresa, '".$this->session->userdata("inicio_sesion")['depto']."' ) ) AND solpagos.idetapa IN ( 10, 11, 14 ) ORDER BY solpagos.idetapa, solpagos.fechaCreacion DESC");
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
        // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
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




	function reembolsos_pagados( $tipo_reembolso ){

		if( $tipo_reembolso ){

		}else{
			$tipo_reembolso = "AND autpagos_caja_chica.nomdepto != 'TARJETA CREDITO'";
		}

		switch( $this->session->userdata("inicio_sesion")['rol'] ){
			case 'AS':
			case 'DA':
				return $this->db->query("SELECT 
                    NULL AS solicitudes,
                    autpagos_caja_chica.idpago,
                    autpagos_caja_chica.idResponsable,
                    autpagos_caja_chica.idsolicitud,
                    autpagos_caja_chica.tipoPago,
                    autpagos_caja_chica.referencia,
                    autpagos_caja_chica.fecha_cobro AS fecha_cobro,
                    CONCAT(usuarios.nombres,
                            ' ',
                            usuarios.apellidos) AS responsable,
                    empresas.abrev,
                    empresas.idempresa,
                    autpagos_caja_chica.fecreg AS fecha,
                    autpagos_caja_chica.cantidad,
                    autpagos_caja_chica.nomdepto
                FROM (
                        SELECT * FROM 
                            autpagos_caja_chica
                        WHERE
                            autpagos_caja_chica.nomdepto IN (
                                SELECT DISTINCT( departamento ) departamento FROM departament_usuario WHERE idusuario = ?
                            ) AND autpagos_caja_chica.estatus IN ( 14, 15, 16 )
                    ) autpagos_caja_chica
                        INNER JOIN
                    empresas ON autpagos_caja_chica.idEmpresa = empresas.idempresa
                        INNER JOIN
                    usuarios ON usuarios.idusuario = autpagos_caja_chica.idResponsable
                ORDER BY autpagos_caja_chica.fecreg DESC", [ $this->session->userdata("inicio_sesion")['id'] ]);
				break;
			case 'CJ':
				return $this->db->query("SELECT NULL AS solicitudes, autpagos_caja_chica.idpago, autpagos_caja_chica.idResponsable, autpagos_caja_chica.idsolicitud, autpagos_caja_chica.tipoPago, autpagos_caja_chica.referencia, autpagos_caja_chica.fecha_cobro AS fecha_cobro, CONCAT(usuarios.nombres, ' ', usuarios.apellidos) AS responsable, empresas.abrev, empresas.idempresa, autpagos_caja_chica.fecreg AS fecha, autpagos_caja_chica.cantidad, autpagos_caja_chica.nomdepto FROM autpagos_caja_chica INNER JOIN empresas ON autpagos_caja_chica.idEmpresa = empresas.idempresa INNER JOIN usuarios ON usuarios.idusuario = autpagos_caja_chica.idResponsable WHERE autpagos_caja_chica.nomdepto != 'TARJETA CREDITO' AND autpagos_caja_chica.idResponsable = '".$this->session->userdata("inicio_sesion")['id']."' AND autpagos_caja_chica.estatus IN ( 14, 15, 16) ORDER BY autpagos_caja_chica.fecreg DESC");
				break;
			case 'CA':
			case 'FP':
				return $this->db->query("SELECT NULL AS solicitudes, autpagos_caja_chica.idpago, autpagos_caja_chica.idResponsable, autpagos_caja_chica.idsolicitud, autpagos_caja_chica.tipoPago, autpagos_caja_chica.referencia, autpagos_caja_chica.fecha_cobro AS fecha_cobro, CONCAT(usuarios.nombres, ' ', usuarios.apellidos) AS responsable, empresas.abrev, empresas.idempresa, autpagos_caja_chica.fecreg AS fecha, autpagos_caja_chica.cantidad, autpagos_caja_chica.nomdepto FROM autpagos_caja_chica INNER JOIN ( SELECT solpagos.idsolicitud FROM solpagos WHERE solpagos.idusuario = '".$this->session->userdata("inicio_sesion")['id']."' ) solpagos ON FIND_IN_SET( solpagos.idsolicitud, autpagos_caja_chica.idsolicitud) INNER JOIN empresas ON autpagos_caja_chica.idEmpresa = empresas.idempresa INNER JOIN usuarios ON usuarios.idusuario = autpagos_caja_chica.idResponsable WHERE autpagos_caja_chica.nomdepto != 'TARJETA CREDITO' AND autpagos_caja_chica.estatus IN ( 14, 15, 16) GROUP BY autpagos_caja_chica.idpago ORDER BY autpagos_caja_chica.fecreg DESC");
				break;
			default:
				return $this->db->query("     SELECT NULL AS solicitudes, autpagos_caja_chica.idpago, autpagos_caja_chica.estatus, autpagos_caja_chica.idResponsable, autpagos_caja_chica.idsolicitud, autpagos_caja_chica.tipoPago, 
				 autpagos_caja_chica.referencia, autpagos_caja_chica.fechaDis AS fecha_cobro, CONCAT(usuarios.nombres, ' ', usuarios.apellidos) AS responsable, empresas.idempresa, empresas.abrev, 
				 autpagos_caja_chica.fecreg AS fecha, autpagos_caja_chica.cantidad, autpagos_caja_chica.nomdepto 
				 FROM autpagos_caja_chica 
				 INNER JOIN solpagos sp ON autpagos_caja_chica.idsolicitud = sp.idsolicitud AND caja_chica=3
				 INNER JOIN empresas ON autpagos_caja_chica.idEmpresa = empresas.idempresa 
				 INNER JOIN usuarios ON usuarios.idusuario = autpagos_caja_chica.idResponsable 
				 WHERE autpagos_caja_chica.nomdepto != 'TARJETA CREDITO' AND autpagos_caja_chica.estatus IN ( 5, 14, 15, 16 ) ORDER BY autpagos_caja_chica.fecreg  DESC");
				break;
		}
	}
	function reembolsos_cerrados(){
		return $this->db->query("    SELECT 
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
            AND caja_chica=3
            GROUP BY DATE_FORMAT(s.fecha_autorizacion, '%d-%m-%Y'), s.idResponsable, s.idEmpresa
        ) autpagos_caja_chica 
        INNER JOIN empresas ON autpagos_caja_chica.idEmpresa = empresas.idempresa 
        INNER JOIN listado_usuarios usuarios ON usuarios.idusuario = autpagos_caja_chica.idResponsable 
        WHERE autpagos_caja_chica.nomdepto != 'TARJETA CREDITO'
        ORDER BY autpagos_caja_chica.fecelab DESC");
	}
	function reembolsos_transito(){
		return $this->db->query("SELECT * FROM reembolsos_transito");
	}

	/***VIATICOS***/
	function viaticos_pagados( $tipo_reembolso ){

		if( $tipo_reembolso ){

		}else{
			$tipo_reembolso = "AND autpagos_caja_chica.nomdepto != 'TARJETA CREDITO'";
		}

		switch( $this->session->userdata("inicio_sesion")['rol'] ){
			case 'AS':
			case 'DA':
				return $this->db->query("SELECT 
                    NULL AS solicitudes,
                    autpagos_caja_chica.idpago,
                    autpagos_caja_chica.idResponsable,
                    autpagos_caja_chica.idsolicitud,
                    autpagos_caja_chica.tipoPago,
                    autpagos_caja_chica.referencia,
                    autpagos_caja_chica.fecha_cobro AS fecha_cobro,
                    CONCAT(usuarios.nombres,
                            ' ',
                            usuarios.apellidos) AS responsable,
                    empresas.abrev,
                    empresas.idempresa,
                    autpagos_caja_chica.fecreg AS fecha,
                    autpagos_caja_chica.cantidad,
                    autpagos_caja_chica.nomdepto,
                    responsable_cch.nombre_reembolso_cch
                FROM (
                        SELECT * FROM 
                            autpagos_caja_chica
                        WHERE
                            autpagos_caja_chica.nomdepto IN (
                                SELECT DISTINCT( departamento ) departamento FROM departament_usuario WHERE idusuario = ?
                            ) AND autpagos_caja_chica.estatus IN ( 14, 15, 16 )
                    ) autpagos_caja_chica
                        INNER JOIN
                    empresas ON autpagos_caja_chica.idEmpresa = empresas.idempresa
                        INNER JOIN
                    usuarios ON usuarios.idusuario = autpagos_caja_chica.idResponsable
                    LEFT JOIN (
                            SELECT
                                cajas_ch.idusuario
                                , GROUP_CONCAT(cajas_ch.nombre_reembolso_ch SEPARATOR ', ') nombre_reembolso_cch
                            FROM cajas_ch
                            GROUP BY cajas_ch.idusuario
                    ) AS responsable_cch ON responsable_cch.idusuario = solpagos.idResponsable
                ORDER BY autpagos_caja_chica.fecreg DESC", [ $this->session->userdata("inicio_sesion")['id'] ]);
				break;
			case 'CJ':
				return $this->db->query("SELECT NULL AS solicitudes,
                                                autpagos_caja_chica.idpago,
												autpagos_caja_chica.idResponsable,
												autpagos_caja_chica.idsolicitud,
												autpagos_caja_chica.tipoPago,
												autpagos_caja_chica.referencia,
												autpagos_caja_chica.fecha_cobro AS fecha_cobro,
												CONCAT(usuarios.nombres,
												' ',
												usuarios.apellidos) AS responsable,
												empresas.abrev,
												empresas.idempresa,
												autpagos_caja_chica.fecreg AS fecha,
												autpagos_caja_chica.cantidad,
                                                autpagos_caja_chica.nomdepto,
                                                responsable_cch.nombre_reembolso_cch 
                                        FROM autpagos_caja_chica 
                                        INNER JOIN empresas 
                                            ON autpagos_caja_chica.idEmpresa = empresas.idempresa 
                                        INNER JOIN usuarios
                                            ON usuarios.idusuario = autpagos_caja_chica.idResponsable
                                        LEFT JOIN (
                                                SELECT
                                                    cajas_ch.idusuario
                                                    , GROUP_CONCAT(cajas_ch.nombre_reembolso_ch SEPARATOR ', ') nombre_reembolso_cch
                                                FROM cajas_ch
                                                GROUP BY cajas_ch.idusuario
                                        ) AS responsable_cch ON responsable_cch.idusuario = autpagos_caja_chica.idResponsable
                                        WHERE   autpagos_caja_chica.nomdepto != 'TARJETA CREDITO' AND 
                                                autpagos_caja_chica.idResponsable = '".$this->session->userdata("inicio_sesion")['id']."' AND 
                                                autpagos_caja_chica.estatus IN ( 14, 15, 16) 
                                        ORDER BY autpagos_caja_chica.fecreg DESC");
				break;
			case 'CA':
			case 'FP':
				return $this->db->query("SELECT NULL AS solicitudes,
												autpagos_caja_chica.idpago,
												autpagos_caja_chica.idResponsable,
												autpagos_caja_chica.idsolicitud,
												autpagos_caja_chica.tipoPago,
												autpagos_caja_chica.referencia,
												autpagos_caja_chica.fecha_cobro AS fecha_cobro,
												CONCAT(usuarios.nombres,
												' ',
												usuarios.apellidos) AS responsable,
												empresas.abrev,
												empresas.idempresa,
												autpagos_caja_chica.fecreg AS fecha,
												autpagos_caja_chica.cantidad,
                                                autpagos_caja_chica.nomdepto,
                                                responsable_cch.nombre_reembolso_cch
                                        FROM autpagos_caja_chica 
                                        INNER JOIN ( SELECT solpagos.idsolicitud
                                                     FROM solpagos 
                                                     WHERE solpagos.idusuario = '".$this->session->userdata("inicio_sesion")['id']."' ) solpagos 
                                            ON FIND_IN_SET( solpagos.idsolicitud, autpagos_caja_chica.idsolicitud) 
                                        INNER JOIN empresas 
                                            ON autpagos_caja_chica.idEmpresa = empresas.idempresa
                                        INNER JOIN usuarios
                                            ON usuarios.idusuario = autpagos_caja_chica.idResponsable
                                        LEFT JOIN (
                                                SELECT
                                                    cajas_ch.idusuario
                                                    , GROUP_CONCAT(cajas_ch.nombre_reembolso_ch SEPARATOR ', ') nombre_reembolso_cch
                                                FROM cajas_ch
                                                GROUP BY cajas_ch.idusuario
                                        ) AS responsable_cch ON responsable_cch.idusuario = autpagos_caja_chica.idResponsable
                                        WHERE   autpagos_caja_chica.nomdepto != 'TARJETA CREDITO' AND
                                                autpagos_caja_chica.estatus IN ( 14, 15, 16)
                                        GROUP BY autpagos_caja_chica.idpago
                                        ORDER BY autpagos_caja_chica.fecreg DESC");
				break;
			default:
				return $this->db->query("SELECT NULL AS solicitudes, 
                                                autpagos_caja_chica.idpago,
                                                autpagos_caja_chica.estatus,
                                                autpagos_caja_chica.idResponsable,
												autpagos_caja_chica.idsolicitud,
												autpagos_caja_chica.tipoPago,
												autpagos_caja_chica.referencia,
												autpagos_caja_chica.fechaDis AS fecha_cobro,
												CONCAT(usuarios.nombres,
												' ',
												usuarios.apellidos) AS responsable,
												empresas.idempresa,
												empresas.abrev,
												autpagos_caja_chica.fecreg AS fecha,
												autpagos_caja_chica.cantidad,
												autpagos_caja_chica.nomdepto,
                                                responsable_cch.nombre_reembolso_cch 
                                        FROM autpagos_caja_chica 
                                        INNER JOIN solpagos sp 
                                            ON sp.idsolicitud IN (autpagos_caja_chica.idsolicitud) AND caja_chica = 4
                                        INNER JOIN empresas
                                            ON autpagos_caja_chica.idEmpresa = empresas.idempresa 
                                        INNER JOIN usuarios 
                                            ON usuarios.idusuario = autpagos_caja_chica.idResponsable
                                        LEFT JOIN (
                                                SELECT
                                                    cajas_ch.idusuario
                                                    , GROUP_CONCAT(cajas_ch.nombre_reembolso_ch SEPARATOR ', ') nombre_reembolso_cch
                                                FROM cajas_ch
                                                GROUP BY cajas_ch.idusuario
                                        ) AS responsable_cch ON responsable_cch.idusuario = autpagos_caja_chica.idResponsable 
                                        WHERE  autpagos_caja_chica.nomdepto != 'TARJETA CREDITO' AND 
                                                autpagos_caja_chica.estatus IN ( 5, 14, 15, 16 ) 
                                        ORDER BY autpagos_caja_chica.fecreg  DESC");
				break;
		}
	}
	function viaticos_transito(){
		return $this->db->query("SELECT * FROM viaticos_transito");
	}
	function viaticos_cerrados(){
		return $this->db->query("SELECT 
            NULL AS solicitudes, 
            'NA' idpago, 
            autpagos_caja_chica.idetapa AS estatus, 
            autpagos_caja_chica.idResponsable, 
            autpagos_caja_chica.idsolicitud, 
            'NA' tipoPago, 
            '' referencia, 
            NULL fecha_cobro, 
            usuarios.nombre_completo responsable, 
            empresas.abrev, 
            autpagos_caja_chica.fecelab fecha, 
            autpagos_caja_chica.cantidad, 
            autpagos_caja_chica.nomdepto,
            responsable_cch.nombre_reembolso_cch
        FROM  ( SELECT  GROUP_CONCAT(s.idsolicitud) idsolicitud, s.idResponsable, s.idEmpresa, 
                        DATE_FORMAT(s.fecha_autorizacion, '%Y-%m-%d') fecelab, SUM(cantidad) cantidad, nomdepto, s.idetapa
                FROM solpagos s
                WHERE s.idetapa = 31 AND s.caja_chica = 4
                GROUP BY DATE_FORMAT(s.fecha_autorizacion, '%d-%m-%Y'), s.idResponsable, s.idEmpresa
        ) autpagos_caja_chica 
        INNER JOIN empresas ON autpagos_caja_chica.idEmpresa = empresas.idempresa 
        INNER JOIN listado_usuarios usuarios ON usuarios.idusuario = autpagos_caja_chica.idResponsable
        LEFT JOIN ( SELECT cajas_ch.idusuario, GROUP_CONCAT(cajas_ch.nombre_reembolso_ch SEPARATOR ', ') nombre_reembolso_cch
                    FROM cajas_ch
                    GROUP BY cajas_ch.idusuario) AS responsable_cch 
            ON responsable_cch.idusuario = autpagos_caja_chica.idResponsable
        WHERE autpagos_caja_chica.nomdepto != 'TARJETA CREDITO'
        ORDER BY autpagos_caja_chica.fecelab DESC");
		// return $this->db->query("    SELECT 
        //     NULL AS solicitudes, 
        //     'NA' idpago, 
        //     11 estatus, 
        //     autpagos_caja_chica.idResponsable, 
        //     autpagos_caja_chica.idsolicitud, 
        //     'NA' tipoPago, 
        //     '' referencia, 
        //     NULL fecha_cobro, 
        //     usuarios.nombre_completo responsable, 
        //     empresas.abrev, 
        //     autpagos_caja_chica.fecelab fecha, 
        //     autpagos_caja_chica.cantidad, 
        //     autpagos_caja_chica.nomdepto,
        //     responsable_cch.nombre_reembolso_cch
        // FROM  (
        //     SELECT GROUP_CONCAT(s.idsolicitud) idsolicitud, s.idResponsable, s.idEmpresa, DATE_FORMAT(s.fecha_autorizacion, '%Y-%m-%d') fecelab, SUM(cantidad) cantidad, nomdepto
        //     FROM solpagos s
        //     WHERE s.idetapa = 11
        //     AND caja_chica = 4
        //     GROUP BY DATE_FORMAT(s.fecha_autorizacion, '%d-%m-%Y'), s.idResponsable, s.idEmpresa
        // ) autpagos_caja_chica 
        // INNER JOIN empresas ON autpagos_caja_chica.idEmpresa = empresas.idempresa 
        // INNER JOIN listado_usuarios usuarios ON usuarios.idusuario = autpagos_caja_chica.idResponsable 
        // LEFT JOIN cajas_ch cch on cch.idusuario = autpagos_caja_chica.idResponsable
        // LEFT JOIN (
        //         SELECT
        //             cajas_ch.idusuario
        //             , GROUP_CONCAT(cajas_ch.nombre_reembolso_ch SEPARATOR ', ') nombre_reembolso_cch
        //         FROM cajas_ch
        //         GROUP BY cajas_ch.idusuario
        // ) AS responsable_cch ON responsable_cch.idusuario = autpagos_caja_chica.idResponsable
        // WHERE autpagos_caja_chica.nomdepto != 'TARJETA CREDITO'
        //     AND cch.estatus = 0
        // ORDER BY autpagos_caja_chica.fecelab DESC");
	}

    function autorizacion_yola(){
        
        ini_set('memory_limit','-1');
        return $this->db->query(" SELECT 
        idsolicitud, proyecto, fecha_autorizacion, folio, empresas.nombre 
        AS empresa, proveedores.nombre 
        AS proveedor 
        FROM solpagos 
        INNER JOIN empresas 
        ON solpagos.idempresa = empresas.idempresa
        INNER JOIN proveedores 
        ON solpagos.idproveedor = proveedores.idproveedor 
        WHERE idAutoriza = ?  " , [77]);
    }

    function getHistorialTablaSolViaticos($finicial,$ffinal) {

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
            autpagos.etapa_pago,
            solpagos.caja_chica,
            UPPER(IF( solpagos.idetapa IN ( 9, 10, 11 ) AND autpagos.estatus_pago IS NOT NULL AND autpagos.estatus_pago != 16, autpagos.etapa_pago, etapas.nombre )) etapa,
            solpagos.metoPago,
            solpagos.justificacion,
            capturista.nombre_completo,
            solpagos.programado,
            solpagos.tendrafac,
            responsable_cch.nombre_reembolso_cch
            FROM solpagos
            CROSS JOIN listado_usuarios capturista ON capturista.idusuario = solpagos.idusuario
            CROSS JOIN listado_usuarios director ON director.idusuario = solpagos.idResponsable
            CROSS JOIN ( SELECT idproveedor, nombre FROM proveedores ) proveedores ON proveedores.idproveedor = solpagos.idProveedor
            CROSS JOIN etapas ON etapas.idetapa = solpagos.idetapa
            CROSS JOIN ( SELECT idempresa, abrev FROM empresas ) empresas ON empresas.idempresa = solpagos.idEmpresa
            LEFT JOIN ( SELECT * FROM vw_autpagos ) autpagos ON autpagos.idsolicitud = solpagos.idsolicitud
            LEFT JOIN ( SELECT cajas_ch.idusuario, GROUP_CONCAT(cajas_ch.nombre_reembolso_ch SEPARATOR ', ') nombre_reembolso_cch
                        FROM cajas_ch
                        GROUP BY cajas_ch.idusuario) AS responsable_cch 
                ON responsable_cch.idusuario = solpagos.idResponsable
        WHERE
            solpagos.idetapa NOT IN (1 , 25, 30, 42, 45, 47, 49) AND ( caja_chica = 4 ) AND solpagos.fecelab BETWEEN '$finicial' AND '$ffinal'
        ORDER BY solpagos.idetapa , solpagos.fechaCreacion DESC");
    }

    function obtenerInfoFacturaSolicitud($idSolicitud) {
        try {
            $infoFacturas = $this->db->query("SELECT idfactura, idsolicitud, uuid, tipo_factura, foliofac FROM facturas WHERE idsolicitud IN(?) AND tipo_factura <> 0 ORDER BY tipo_factura", [$idSolicitud]);

            if ($infoFacturas->num_rows() > 0) {
                return $infoFacturas;
            }else {
                return null;
            }

        } catch (Exception $error) {
            log_message('Error', 'No se pudo obtener el registro de la factura asociado a la solicitud. Por favor, póngase en contacto con el administrador para mayor asistencia.');
            return null;
        }
    }

    function eliminarFacturaSolicitud($datosSolFactura, $idSolicitud) {
        // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);  
        // Iniciar la transacción
        $this->db->trans_begin();

        try {
            if( array_key_exists('comprobante', $datosSolFactura) ){

                $idsSolicitudes = array_map(function($datos){
                    return "{$datos['idSolicitud']}";
                }, $datosSolFactura["comprobante"]);

                $idsSolicitudes = implode(",", array_unique($idsSolicitudes));
            
                $condicionesQuery = array_map(function($datos){
                    return "({$datos['idFactura']}, {$datos['idSolicitud']}, {$datos['tipoFactura']})";
                }, $datosSolFactura["comprobante"]);
    
                $condicionesQuery = implode(",", $condicionesQuery);
                
                $this->db->query("UPDATE facturas 
                                  SET tipo_factura = 0, uuid = SUBSTRING( uuid, 1, CHAR_LENGTH(uuid) - 1)
                                  WHERE (idfactura, idsolicitud, tipo_factura) IN ($condicionesQuery)");
    
                if ($this->db->trans_status() === FALSE) {
                    throw new Exception("Error: No se pudo completar la transacción referenciada a la solicitud. Por favor, póngase en contacto con el administrador.");
                }

                // log_sistema($this->session->userdata("inicio_sesion")['id'], $idSolicitud, "SE ELIMINÓ LA FACTURA DEL REGISTRO");
            }

            if( array_key_exists('pagoUnicaExibision', $datosSolFactura) ){

                $idsSolicitudes = array_map(function($datos){
                    return "{$datos['idSolicitud']}";
                }, $datosSolFactura["pagoUnicaExibision"]);

                $idsSolicitudes = implode(",", array_unique($idsSolicitudes));
            
                $condicionesQuery = array_map(function($datos){
                    return "({$datos['idFactura']}, {$datos['idSolicitud']}, {$datos['tipoFactura']})";
                }, $datosSolFactura["pagoUnicaExibision"]);
    
                $condicionesQuery = implode(",", $condicionesQuery);
                
                $this->db->query("UPDATE facturas 
                                  SET tipo_factura = 0, uuid = SUBSTRING( uuid, 1, CHAR_LENGTH(uuid) - 1)
                                  WHERE (idfactura, idsolicitud, tipo_factura) IN ($condicionesQuery)");
    
                if ($this->db->trans_status() === FALSE) {
                    throw new Exception("Error: No se pudo completar la transacción referenciada a la solicitud. Por favor, póngase en contacto con el administrador.");
                }

                // log_sistema($this->session->userdata("inicio_sesion")['id'], $idSolicitud, "SE ELIMINÓ LA FACTURA DEL REGISTRO");
            }
    
            if(array_key_exists('complementos', $datosSolFactura)){

                $condicionante = array_map(function($datos){
                    return "({$datos['idFactura']}, {$datos['idSolicitud']}, {$datos['tipoFactura']})";
                }, $datosSolFactura["complementos"]);
    
                $condicionante = implode(",", $condicionante);
    
                $this->db->query("UPDATE facturas 
                                  SET tipo_factura = 0, uuid = SUBSTRING( uuid, 1, CHAR_LENGTH(uuid) - 1)
                                  WHERE (idfactura, idsolicitud, tipo_factura) IN ($condicionante)");
                
                if ($this->db->trans_status() === FALSE) {
                    throw new Exception("Error: No se pudo completar la transacción referenciada a la solicitud. Por favor, póngase en contacto con el administrador.");
                }

                $this->db->query("DELETE FROM temp_complementos WHERE IdDocumento IN ( SELECT uuid FROM facturas WHERE idsolicitud = ? )", [ $idsSolicitudes ]);

                // log_sistema($this->session->userdata("inicio_sesion")['id'], $idSolicitud, "HA ELIMINADO LOS COMPLEMENTOS DEL REGISTRO");

            }

            if(array_key_exists('notaCredito', $datosSolFactura)){

                $condicionante = array_map(function($datos){
                    return "({$datos['idFactura']}, {$datos['idSolicitud']}, {$datos['tipoFactura']})";
                }, $datosSolFactura["notaCredito"]);
    
                $condicionante = implode(",", $condicionante);

                $this->db->query("UPDATE facturas 
                                  SET tipo_factura = 0, uuid = SUBSTRING( uuid, 1, CHAR_LENGTH(uuid) - 1)
                                  WHERE (idfactura, idsolicitud, tipo_factura) IN ($condicionante)");
                
                if ($this->db->trans_status() === FALSE) {
                    throw new Exception("Error: No se pudo completar la transacción referenciada a la solicitud. Por favor, póngase en contacto con el administrador.");
                }

                // log_sistema($this->session->userdata("inicio_sesion")['id'], $idSolicitud, "HA ELIMINADO LAS NOTAS DE CREDITO DEL REGISTRO");

            }

            $this->db->trans_commit();
            return array("estatus" => 200, "mensaje" => "Actualización Correcta. El movimiento solicitado se ha realizado con éxito.");

        } catch (Exception $error) {
            $this->db->trans_rollback();
            // Registrar el error para diagnóstico
            log_message('error', 'Error al agregar usuario: ' . $error->getMessage());
            return array("estatus" => 400, "mensaje" => "Error. Favor de contactar al administrador.");
        }
    }
}