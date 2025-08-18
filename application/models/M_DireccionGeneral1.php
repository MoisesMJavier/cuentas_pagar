<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/* Regla de negocio para la gestion de Direccion General 
 * de acuerdo alas Facturas emitidas por el solicitante */

class M_DireccionGeneral extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function obtenerSolCompra()
    {

        if ($this->session->userdata("inicio_sesion")['depto'] == "COMPRAS")
            $compras = "rcompra = 1";
        else
            $compras = "(rcompra = 0 OR rcompra IS NULL )";

        return $this->db->query("SELECT
            s.idsolicitud AS ID,
            IFNULL(s.requisicion, 'NA') requisicion,
            IFNULL(s.orden_compra, 'NA') oc,
            capturista.nombre_completo nombre_capturista,
            direccion_general.nombre_completo nombre_dg,
            s.moneda,
            s.justificacion AS Observacion,
            p.nombre AS Proveedor,
            s.cantidad AS Cantidad, 
            s.fecelab AS Fecha, 
            s.nomdepto AS Departamento, 
            e.nombre AS Estatus,
            s.proyecto,
            s.idProveedor,
            DATE_FORMAT(s.fecelab,'%d/%b/%Y') AS FECHAFAC,
            empre.abrev AS EMPRESA,
            IFNULL(notifi.visto, 1) AS visto,
            DATE_FORMAT(s.fechaCreacion,'%d/%m/%Y') AS fecha_creacion,
            IFNULL(DATE_FORMAT(s.fechaCreacion,'%d/%b/%Y'), '-') AS fecha_autorizacion,
            IFNULL( s.prioridad, 0) AS prioridad,
            IFNULL(f.descripcion, '') descripcion
            FROM solpagos s
            INNER JOIN proveedores p ON s.idProveedor = p.idProveedor
            INNER JOIN etapas e ON e.idetapa = s.idetapa
            INNER JOIN listado_usuarios capturista ON capturista.idusuario = s.idResponsable
            INNER JOIN empresas empre ON empre.idempresa = s.idempresa
            LEFT JOIN factura_registro f ON f.idsolicitud = s.idsolicitud
            LEFT JOIN listado_usuarios direccion_general ON direccion_general.idusuario = s.idusuario
            LEFT JOIN notifi ON notifi.idsolicitud = s.idsolicitud AND notifi.idusuario = ?
            WHERE s.idetapa = 2 AND 
            " . $compras . "
            ORDER BY s.fecelab", array($this->session->userdata("inicio_sesion")['id']))->result();
    }

    function obtenerSolPendientesOtros()
    {
        //return $this->db->query("SELECT IFNULL(notifi.visto, 1) AS visto, solpagos.fechaCreacion AS fecha_creacion, solpagos.proyecto, solpagos.idsolicitud AS ID, capturista.nombre_capturista, IFNULL(dg.nombre_dg, '') AS nombre_dg, solpagos.moneda, solpagos.justificacion AS Observacion, proveedores.nombre AS Proveedor, solpagos.nomdepto AS Departamento, empresas.abrev, solpagos.fecelab AS FECHAFACP, IFNULL(pagUlt.ultimo_pago, '') AS FECHAU, solpagos.cantidad AS Cantidad, IFNULL(pagado.pagado, 0) AS Autorizado, IFNULL(solpagos.fecha_autorizacion, '') AS fecha_autorizacion, solpagos.prioridad FROM solpagos INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa LEFT JOIN ( SELECT autpagos.idsolicitud, MAX( autpagos.fecreg ) AS ultimo_pago FROM autpagos GROUP BY autpagos.idsolicitud ) AS pagUlt ON solpagos.idsolicitud = pagUlt.idsolicitud LEFT JOIN ( SELECT autpagos.idsolicitud, SUM( autpagos.cantidad ) AS pagado FROM autpagos GROUP BY autpagos.idsolicitud ) AS pagado ON pagado.idsolicitud = solpagos.idsolicitud LEFT JOIN ( SELECT usuarios.idusuario, CONCAT( usuarios.nombres, ' ', usuarios.apellidos ) AS nombre_dg FROM usuarios ) AS dg ON dg.idusuario = solpagos.idAutoriza INNER JOIN (SELECT usuarios.idusuario, CONCAT( usuarios.nombres,' ', usuarios.apellidos ) AS nombre_capturista FROM usuarios) AS capturista ON capturista.idusuario = solpagos.idResponsable INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor LEFT JOIN ( SELECT notificaciones.idsolicitud, notificaciones.visto FROM notificaciones WHERE notificaciones.idusuario = '' ) AS notifi ON notifi.idsolicitud = solpagos.idsolicitud WHERE solpagos.idetapa IN ( 7, 9 ) AND (solpagos.caja_chica IS NULL OR solpagos.caja_chica = 0) AND solpagos.nomdepto IN ( 'DEVOLUCIONES', 'PRESTAMO', 'FINIQUITO', 'FINIQUITO POR RENUNCIA', 'FINIQUITO ASIMILADO', 'FINIQUITO POR PARCIALIDAD', 'BONO','TRASPASO','DEVOLUCION', 'NOMINA DESTAJO' ) AND solpagos.metoPago NOT IN ( 'FACT BAJIO', 'FACT BANREGIO' ) ORDER BY solpagos.prioridad DESC, solpagos.fecelab");
        return $this->db->query("SELECT 
            IFNULL(notifi.visto, 1) AS visto,
            solpagos.fechaCreacion AS fecha_creacion,
            solpagos.proyecto,
            solpagos.idsolicitud AS ID,
            capturista.nombre_completo nombre_capturista,
            IFNULL(dg.nombre_completo, '') AS nombre_dg,
            solpagos.moneda,
            solpagos.justificacion AS Observacion,
            proveedores.nombre AS Proveedor,
            solpagos.nomdepto AS Departamento,
            empresas.abrev,
            solpagos.fecelab AS FECHAFACP,
            IFNULL(autpagos.upago, '') AS FECHAU,
            solpagos.cantidad AS Cantidad,
            IFNULL(autpagos.pagado, 0) AS Autorizado,
            IFNULL(solpagos.fecha_autorizacion, '') AS fecha_autorizacion,
            solpagos.prioridad
        FROM
            solpagos
            CROSS JOIN empresas ON empresas.idempresa = solpagos.idEmpresa
            CROSS JOIN listado_usuarios capturista ON capturista.idusuario = solpagos.idResponsable
            CROSS JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor
            LEFT JOIN vw_autpagos autpagos ON solpagos.idsolicitud = autpagos.idsolicitud
            LEFT JOIN listado_usuarios dg ON dg.idusuario = solpagos.idAutoriza
            LEFT JOIN ( SELECT * FROM notifi WHERE notifi.idusuario = ? ) AS notifi ON notifi.idsolicitud = solpagos.idsolicitud
            WHERE
                solpagos.idetapa IN (7 , 9)
                AND ( solpagos.caja_chica IS NULL OR solpagos.caja_chica = 0 )
                AND solpagos.nomdepto IN (
                    'PRESTAMO',
                    'FINIQUITO',
                    'FINIQUITO POR RENUNCIA',
                    'FINIQUITO ASIMILADO',
                    'FINIQUITO POR PARCIALIDAD',
                    'BONO',
                    'NOMINA DESTAJO'
                )
                AND solpagos.metoPago NOT IN ('FACT BAJIO', 'FACT BANREGIO')
            ORDER BY solpagos.prioridad DESC , solpagos.fecelab", array($this->session->userdata("inicio_sesion")['id']));
    }

    function obtenerSolPendientesFactoraje()
    {
        return $this->db->query("SELECT IFNULL(notifi.visto, 1) AS visto, solpagos.fechaCreacion AS fecha_creacion, solpagos.proyecto, solpagos.idsolicitud AS ID, capturista.nombre_capturista, IFNULL(dg.nombre_dg, '') AS nombre_dg, solpagos.moneda, solpagos.justificacion AS Observacion, proveedores.nombre AS Proveedor, solpagos.nomdepto AS Departamento, empresas.abrev, solpagos.fecelab AS FECHAFACP, IFNULL(pagUlt.ultimo_pago, '') AS FECHAU, solpagos.cantidad AS Cantidad, IFNULL(pagado.pagado, 0) AS Autorizado, IFNULL(solpagos.fecha_autorizacion, '') AS fecha_autorizacion, solpagos.prioridad FROM solpagos INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa LEFT JOIN ( SELECT autpagos.idsolicitud, MAX( autpagos.fecreg ) AS ultimo_pago FROM autpagos GROUP BY autpagos.idsolicitud ) AS pagUlt ON solpagos.idsolicitud = pagUlt.idsolicitud LEFT JOIN ( SELECT autpagos.idsolicitud, SUM( autpagos.cantidad ) AS pagado FROM autpagos GROUP BY autpagos.idsolicitud ) AS pagado ON pagado.idsolicitud = solpagos.idsolicitud LEFT JOIN ( SELECT usuarios.idusuario, CONCAT( usuarios.nombres, ' ', usuarios.apellidos ) AS nombre_dg FROM usuarios ) AS dg ON dg.idusuario = solpagos.idAutoriza INNER JOIN (SELECT usuarios.idusuario, CONCAT( usuarios.nombres,' ', usuarios.apellidos ) AS nombre_capturista FROM usuarios) AS capturista ON capturista.idusuario = solpagos.idResponsable INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor LEFT JOIN ( SELECT notificaciones.idsolicitud, notificaciones.visto FROM notificaciones WHERE notificaciones.idusuario = '" . $this->session->userdata("inicio_sesion")['id'] . "' ) AS notifi ON notifi.idsolicitud = solpagos.idsolicitud WHERE solpagos.idetapa IN ( 7, 9 ) AND (solpagos.caja_chica IS NULL OR solpagos.caja_chica = 0) AND solpagos.nomdepto NOT IN ( 'DEVOLUCIONES', 'PRESTAMO', 'FINIQUITO', 'FINIQUITO POR RENUNCIA', 'FINIQUITO ASIMILADO', 'FINIQUITO POR PARCIALIDAD', 'BONO','TRASPASO','DEVOLUCION', 'NOMINA DESTAJO' ) AND solpagos.metoPago IN ( 'FACT BAJIO', 'FACT BANREGIO' ) ORDER BY solpagos.prioridad DESC, solpagos.fecelab");
    }

    //OBTIENE TODAS LOS GRUPOS PARA LA TABLA DE PAGOS POR PAGAR A LOS PROVEEDORES POR FILTRO O SIN FILTRO
    function obtenerSolPendientes()
    {
        //return $this->db->query("SELECT proveedores.idproveedor,proveedores.nombre AS Proveedor, MIN( solpagos.fecelab ) AS FECHAFACP, (SUM( solpagos.cantidad ) - IFNULL(abono.abonado, 0)) AS Cantidad FROM solpagos INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor LEFT JOIN ( SELECT autpagos.idsolicitud, SUM(autpagos.cantidad) AS abonado FROM autpagos GROUP BY autpagos.idsolicitud ) AS abono ON abono.idsolicitud = solpagos.idsolicitud WHERE solpagos.idetapa IN ( 7, 9 ) AND (solpagos.caja_chica IS NULL OR solpagos.caja_chica = 0) AND solpagos.nomdepto NOT IN ( 'DEVOLUCIONES', 'PRESTAMO', 'FINIQUITO', 'FINIQUITO POR RENUNCIA', 'FINIQUITO POR PARCIALIDAD', 'BONO','TRASPASO','DEVOLUCION', 'IMPUESTOS' ) AND solpagos.metoPago NOT IN ( 'FACT BAJIO', 'FACT BANREGIO' ) AND solpagos.programado IS NULL GROUP BY proveedores.idproveedor ORDER BY proveedores.nombre, solpagos.fecelab");
        //PARA TRAER CON LOS PAGOS PROGRAMADOS POR GRUPO
        return $this->db->query(/*"SELECT proveedores.idproveedor,proveedores.nombre AS Proveedor, MIN( IF(solpagos.programado IS NULL, solpagos.fecelab, CASE WHEN solpagos.programado < 7 THEN DATE_ADD( solpagos.fecelab, INTERVAL ( IFNULL(prealizados, 0) * solpagos.programado ) MONTH ) ELSE DATE_ADD( solpagos.fecelab, INTERVAL IFNULL(prealizados, 0) WEEK ) END ) ) AS FECHAFACP, SUM( solpagos.cantidad ) - SUM( IF( solpagos.programado IS NULL, IFNULL(abonado, 0), 0 ) ) Cantidad FROM solpagos INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor LEFT JOIN ( SELECT autpagos.idsolicitud, COUNT(autpagos.idpago) prealizados, SUM( autpagos.cantidad ) abonado FROM autpagos WHERE autpagos.estatus IN ( 14, 15, 16 ) GROUP BY autpagos.idsolicitud ) AS abono ON abono.idsolicitud = solpagos.idsolicitud WHERE solpagos.idetapa IN ( 7, 9) AND (solpagos.caja_chica IS NULL OR solpagos.caja_chica = 0) AND solpagos.nomdepto NOT IN ( 'DEVOLUCIONES', 'PRESTAMO', 'FINIQUITO', 'FINIQUITO POR RENUNCIA', 'FINIQUITO POR PARCIALIDAD', 'BONO','TRASPASO','DEVOLUCION', 'IMPUESTOS', 'NOMINA DESTAJO' ) AND solpagos.metoPago NOT IN ( 'FACT BAJIO', 'FACT BANREGIO' ) GROUP BY proveedores.idproveedor ORDER BY proveedores.nombre, solpagos.fecelab"*/
                    "SELECT
                        SUM( solp.prioridad ) urgentes,
                        prov.ids, 
                        prov.rfc, 
                        catPro.porcentaje,
                        UPPER(TRIM(prov.nombre)) As Proveedor, 
                        MIN(solp.FECHAFACP) AS FECHAFACP,
                        0 Cantidad, 
                        prov.rfcs, 
                        solp.moneda, 
                        SUM(prov.totalPagado) as totalPagado, 
                        SUM(solp.abonado)AS abonado,
                        MAX( solp.contrato ) AS contratos
                    FROM
                        ( 
                            SELECT
                                solpagos.idProveedor,
                                MIN( IF(solpagos.programado IS NULL, 
                                        solpagos.fecelab, 
                                        prox_fecha_pago(solpagos.programado, solpagos.fecelab, solpagos.fecha_fin, IFNULL(prealizados, 0) ) ) ) AS FECHAFACP,
                                solpagos.moneda,
                                SUM( IFNULL( solpagos.prioridad, 0 ) ) prioridad,
                                SUM( abono.abonado ) abonado,
                                MAX(solpagos.contrato) contrato
                            FROM (
                                SELECT 
                                    solpagos.idsolicitud,
                                    idProveedor,
                                    programado,
                                    fecelab,
                                    fecha_fin,
                                    moneda,
                                    prioridad,
                                    scont.p_intercambio AS contrato
                                FROM (
                                    SELECT * 
                                    FROM solpagos
                                    WHERE solpagos.idetapa IN ( 7, 9) AND (solpagos.caja_chica IS NULL OR solpagos.caja_chica = 0) 
                                    AND solpagos.nomdepto NOT IN ( 
                                        'PRESTAMO', 
                                        'FINIQUITO', 
                                        'FINIQUITO POR RENUNCIA', 
                                        'FINIQUITO ASIMILADO', 
                                        'FINIQUITO POR PARCIALIDAD', 
                                        'BONO',
                                        'INFORMATIVA','INFORMATIVA CERO',
                                        'IMPUESTOS', 
                                        'NOMINA DESTAJO' ) 
                                    AND solpagos.nomdepto NOT LIKE 'DEVOLUCION%' AND solpagos.nomdepto NOT LIKE 'TRASPASO%' AND solpagos.nomdepto NOT LIKE 'CESION%'
                                    AND solpagos.metoPago NOT IN ( 'FACT BAJIO', 'FACT BANREGIO' )
                                ) solpagos
                                LEFT JOIN (
                                    SELECT c.p_intercambio, sc.idsolicitud FROM contratos c
                                    JOIN sol_contrato sc ON c.idcontrato = sc.idcontrato
                                ) scont ON scont.idsolicitud = solpagos.idsolicitud 
                            ) solpagos 
                            LEFT JOIN ( 
                                SELECT autpagos.idsolicitud, COUNT(autpagos.idpago) prealizados, SUM( autpagos.cantidad ) abonado 
                                FROM autpagos 
                                WHERE autpagos.estatus IN ( 14, 15, 16 ) 
                                GROUP BY autpagos.idsolicitud 
                            ) AS abono ON abono.idsolicitud = solpagos.idsolicitud 
                            GROUP BY solpagos.idProveedor
                        ) solp
                        JOIN (
                            SELECT  GROUP_CONCAT(proveedores.idproveedor SEPARATOR ',') AS ids, nombre, COUNT(proveedores.rfc) AS rfcs, proveedores.rfc, SUM(totalpagadoprov.totalPagado) AS totalPagado
                            FROM ( SELECT * FROM proveedores where CHAR_LENGTH(proveedores.rfc) > 0 AND rfc NOT IN ( 'XAXX010101000','CLIENTE') AND estatus NOT IN ( 4 ) ) proveedores
                            LEFT JOIN totalpagadoprov ON totalpagadoprov.idProveedor = proveedores.idproveedor
                            GROUP BY proveedores.rfc   
                            UNION
                            SELECT proveedores.idproveedor AS ids, proveedores.nombre , COUNT(proveedores.rfc) AS rfcs, proveedores.rfc, SUM(totalpagadoprov.totalPagado) AS totalPagado
                            FROM(
                                SELECT idproveedor, nombre, rfc FROM proveedores WHERE rfc IN ('' , 'XAXX010101000') AND estatus NOT IN ( 4 )
                                UNION
                                SELECT idproveedor, nombre, rfc FROM proveedores WHERE rfc IS NULL AND estatus NOT IN ( 4 )
                            ) proveedores
                            LEFT JOIN totalpagadoprov ON totalpagadoprov.idProveedor = proveedores.idproveedor
                            GROUP BY proveedores.idproveedor
                        ) prov
                        ON CONCAT(',',prov.ids,',') LIKE concat('%,', solp.idProveedor ,',%') 
                        LEFT JOIN cat_proveedor catPro on catPro.rfc_proveedor = prov.rfc
                    GROUP BY prov.ids ORDER BY prov.nombre");
    }

    //OBTIENE TODAS LAS SOLICITUDES PARA LA TABLA DE PAGOS POR PAGAR A LOS PROVEEDORES POR FILTRO O SIN FILTRO
    function obtenerSolDiferida()
    {
        //return $this->db->query("SELECT solpagos.idproveedor, solpagos.folio, solpagos.idsolicitud AS ID , solpagos.nomdepto AS nomDepto, solpagos.moneda, solpagos.justificacion AS Observacion, cesp.comentario_especial, empresas.abrev, solpagos.fecelab AS FECHAFACP, IFNULL(pagado.ultimo_pago, '') AS FECHAU, solpagos.cantidad AS Cantidad, IFNULL(pagado.pagado, 0) AS Autorizado, IFNULL(dg.nombre_dg, '') AS nombre_dg, IFNULL(solpagos.fecha_autorizacion, '') AS fecha_autorizacion, solpagos.prioridad FROM solpagos INNER JOIN ( SELECT empresas.idempresa, empresas.abrev FROM empresas ) AS empresas ON empresas.idempresa = solpagos.idEmpresa LEFT JOIN (SELECT *, MIN(facturas.feccrea) FROM facturas WHERE facturas.tipo_factura IN ( 1, 3 ) GROUP BY facturas.idsolicitud) AS facturas ON facturas.idsolicitud = solpagos.idsolicitud LEFT JOIN ( SELECT autpagos.idsolicitud, SUM( autpagos.cantidad ) AS pagado, MAX( autpagos.fecreg ) AS ultimo_pago FROM autpagos GROUP BY autpagos.idsolicitud ) AS pagado ON pagado.idsolicitud = solpagos.idsolicitud LEFT JOIN ( SELECT usuarios.idusuario, CONCAT( usuarios.nombres, ' ', usuarios.apellidos ) AS nombre_dg FROM usuarios ) AS dg ON dg.idusuario = solpagos.idAutoriza LEFT JOIN ( SELECT comentario_especial.idsolicitud, GROUP_CONCAT( CONCAT( '<b>',comentario_especial.tipo_comentario,'</b>: ', comentario_especial.observacion ) SEPARATOR ' ' ) comentario_especial FROM comentario_especial GROUP BY comentario_especial.idsolicitud ) cesp ON cesp.idsolicitud = solpagos.idsolicitud WHERE solpagos.idetapa IN ( 7, 9 ) AND (solpagos.caja_chica IS NULL OR solpagos.caja_chica = 0) AND solpagos.nomdepto NOT IN ( 'DEVOLUCIONES', 'PRESTAMO', 'FINIQUITO', 'FINIQUITO POR RENUNCIA', 'FINIQUITO POR PARCIALIDAD', 'BONO','TRASPASO','DEVOLUCION', 'IMPUESTOS' ) AND solpagos.metoPago NOT IN ( 'FACT BAJIO', 'FACT BANREGIO' ) AND solpagos.programado IS NULL ORDER BY solpagos.fecelab ASC, solpagos.folio ASC");
        //PARA TRAER CON LOS PAGOS PROGRAMADOS
        return $this->db->query("SELECT 
            0 pa, 
            solpagos.idproveedor, 
            solpagos.folio, 
            solpagos.idsolicitud AS ID , 
            solpagos.nomdepto AS nomDepto, 
            solpagos.moneda, 
            solpagos.justificacion AS Observacion, 
            cesp.comentario_especial, 
            empresas.abrev, 
            solpagos.programado, 
            IFNULL(prealizados, 0) prealizados,
            IF(solpagos.programado IS NULL, solpagos.fecelab, prox_fecha_pago(solpagos.programado, solpagos.fecelab,solpagos.fecha_fin, IFNULL(prealizados, 0) ) ) AS FECHAFACP,
            IF(solpagos.fecha_fin IS NULL, 'SIN DEFINIR', calculo_ppago(solpagos.programado, solpagos.fecelab, solpagos.fecha_fin) ) ppago,
            IFNULL(pagado.ultimo_pago, '') AS FECHAU, 
            solpagos.cantidad AS Cantidad, 
            IF( pagado.pagado IS NULL OR solpagos.programado IS NOT NULL, 0, pagado.pagado ) Autorizado, 
            IFNULL(dg.nombre_dg, '') AS nombre_dg, 
            IFNULL(solpagos.fecha_autorizacion, '') AS fecha_autorizacion, 
            solpagos.prioridad, 
            solpagos.fecha_fin, 
            scont.idcontrato AS contrato,
            cont.p_intercambio AS porcentaje
        FROM solpagos 
        INNER JOIN ( SELECT empresas.idempresa, empresas.abrev FROM empresas ) AS empresas ON empresas.idempresa = solpagos.idEmpresa 
        LEFT JOIN ( SELECT autpagos.idsolicitud, COUNT(autpagos.idpago) prealizados,  SUM( autpagos.cantidad ) AS pagado, MAX( autpagos.fecreg ) AS ultimo_pago FROM autpagos GROUP BY autpagos.idsolicitud ) AS pagado ON pagado.idsolicitud = solpagos.idsolicitud 
        LEFT JOIN ( SELECT usuarios.idusuario, CONCAT( usuarios.nombres, ' ', usuarios.apellidos ) AS nombre_dg FROM usuarios ) AS dg ON dg.idusuario = solpagos.idAutoriza 
        LEFT JOIN ( SELECT comentario_especial.idsolicitud, GROUP_CONCAT( CONCAT( '<b>',comentario_especial.tipo_comentario,'</b>: ', comentario_especial.observacion ) SEPARATOR ' ' ) comentario_especial FROM comentario_especial GROUP BY comentario_especial.idsolicitud ) cesp ON cesp.idsolicitud = solpagos.idsolicitud 
        LEFT JOIN sol_contrato scont ON scont.idsolicitud = solpagos.idsolicitud
        LEFT JOIN contratos cont ON cont.idcontrato = scont.idcontrato
        WHERE solpagos.idetapa IN ( 7, 9 ) AND (solpagos.caja_chica IS NULL OR solpagos.caja_chica = 0) AND solpagos.nomdepto NOT IN ( 'DEVOLUCIONES', 'PRESTAMO', 'FINIQUITO', 'FINIQUITO POR RENUNCIA', 'FINIQUITO ASIMILADO', 'FINIQUITO ASIMILADO', 'FINIQUITO POR PARCIALIDAD', 'BONO','TRASPASO','DEVOLUCION', 'IMPUESTOS', 'NOMINA DESTAJO' ) AND solpagos.metoPago NOT IN ( 'FACT BAJIO', 'FACT BANREGIO' ) 
        ORDER BY FECHAFACP ASC, solpagos.folio ASC");
    }

    //OBTENEMOS TODO EL LISTADO DE PAGOS A PROVEEDOR PERO PROGAMADO
    /*
    function obtenerPagosProgramados(){
        return $this->db->query("SELECT proveedores.nombre Proveedor,  ROUND( CASE WHEN solpagos.programado < 7 THEN TIMESTAMPDIFF(MONTH, CONCAT( YEAR(solpagos.fecelab),'-',MONTH(solpagos.fecelab),'-', DAY(solpagos.fecha_fin) ), solpagos.fecha_fin) / solpagos.programado ELSE TIMESTAMPDIFF(WEEK, solpagos.fecelab, solpagos.fecha_fin) END ) ppago, solpagos.proyecto, solpagos.idsolicitud AS ID , solpagos.nomdepto AS Departamento, solpagos.moneda, solpagos.justificacion AS Observacion, cesp.comentario_especial, empresas.abrev, solpagos.programado, IFNULL(prealizados, 0) prealizados, ( CASE WHEN solpagos.programado < 7 THEN DATE_ADD( solpagos.fecelab, INTERVAL ( IFNULL(prealizados, 0) * solpagos.programado ) MONTH ) ELSE DATE_ADD( solpagos.fecelab, INTERVAL IFNULL(prealizados, 0) WEEK ) END ) AS FECHAFACP, IFNULL(pagado.ultimo_pago, '') AS FECHAU, solpagos.cantidad AS Cantidad, IFNULL(dg.nombre_dg, '') AS nombre_dg, IFNULL(solpagos.fecha_autorizacion, '') AS fecha_autorizacion, solpagos.prioridad, solpagos.fecha_fin FROM solpagos INNER JOIN ( SELECT proveedores.idproveedor, proveedores.nombre FROM proveedores ) proveedores ON proveedores.idproveedor = solpagos.idProveedor INNER JOIN ( SELECT empresas.idempresa, empresas.abrev FROM empresas ) AS empresas ON empresas.idempresa = solpagos.idEmpresa LEFT JOIN (SELECT *, MIN(facturas.feccrea) FROM facturas WHERE facturas.tipo_factura IN ( 1, 3 ) GROUP BY facturas.idsolicitud) AS facturas ON facturas.idsolicitud = solpagos.idsolicitud LEFT JOIN ( SELECT autpagos.idsolicitud, COUNT(autpagos.idpago) prealizados,  SUM( autpagos.cantidad ) AS pagado, MAX( autpagos.fecreg ) AS ultimo_pago FROM autpagos GROUP BY autpagos.idsolicitud ) AS pagado ON pagado.idsolicitud = solpagos.idsolicitud LEFT JOIN ( SELECT usuarios.idusuario, CONCAT( usuarios.nombres, ' ', usuarios.apellidos ) AS nombre_dg FROM usuarios ) AS dg ON dg.idusuario = solpagos.idAutoriza LEFT JOIN ( SELECT comentario_especial.idsolicitud, GROUP_CONCAT( CONCAT( '<b>',comentario_especial.tipo_comentario,'</b>: ', comentario_especial.observacion ) SEPARATOR ' ' ) comentario_especial FROM comentario_especial GROUP BY comentario_especial.idsolicitud ) cesp ON cesp.idsolicitud = solpagos.idsolicitud WHERE solpagos.idetapa IN ( 7, 14 ) AND solpagos.programado IS NOT NULL AND ( '2019-12-30' ) >= ( CASE WHEN solpagos.programado < 7 THEN DATE_ADD( solpagos.fecelab, INTERVAL ( IFNULL(prealizados, 0) * solpagos.programado ) MONTH ) ELSE DATE_ADD( solpagos.fecelab, INTERVAL IFNULL(prealizados, 0) WEEK ) END ) ORDER BY FECHAFACP ASC, Proveedor ASC, solpagos.folio ASC");
    }
    */

    function obtenerSolCaja()
    {   
        /*
        return $this->db->query("SELECT 
            group_concat(`s`.`idsolicitud`) AS `ID`, 
            CONCAT(u.nombres, ' ', u.apellidos) AS Responsable,
            emp.abrev,
            MAX(DATE_FORMAT(s.fecelab,'%d/%b/%Y')) AS FECHAFACP,
            SUM(s.cantidad) AS Cantidad,
            s.nomdepto AS Departamento,
            s.idEmpresa AS Empresa,
            s.idResponsable IDR, 0 pa, null solicitudes
            FROM solpagos s 
            INNER JOIN usuarios u ON (s.idResponsable =  u.idusuario)
            INNER JOIN empresas emp ON(emp.idEmpresa = s.idEmpresa)
            INNER JOIN proveedores p ON(s.idProveedor = p.idProveedor)
            WHERE s.caja_chica = 1 AND s.idetapa IN (7)
            GROUP BY s.idResponsable ,s.idEmpresa ORDER BY s.fecelab");
        */
        return $this->db->query("SELECT 
                s.ID,
                u.nombre_completo Responsable,
                emp.abrev,
                s.FECHAFACP,
                s.Cantidad,
                s.Departamento,
                s.idEmpresa Empresa,
                s.idResponsable IDR, 
                0 pa, 
                null solicitudes
            FROM ( 
                SELECT 
                    GROUP_CONCAT( idsolicitud ) ID,  
                    MAX(DATE_FORMAT( fecelab,'%d/%b/%Y') ) FECHAFACP,
                    nomdepto Departamento,
                    idResponsable,
                    idEmpresa,
                    SUM(cantidad) Cantidad
                FROM solpagos 
                WHERE caja_chica = 1 AND idetapa IN ( 7 )
                GROUP BY idResponsable, idEmpresa
            ) s
            INNER JOIN listado_usuarios u ON s.idResponsable =  u.idusuario
            INNER JOIN empresas emp ON emp.idEmpresa = s.idEmpresa
            ORDER BY s.FECHAFACP");
    }

    function obtenersolTDC()
    {
        return $this->db->query("SELECT s.idsolicitud ID, p.nombre Responsable, e.abrev, DATE_FORMAT(s.fecelab,'%d/%b/%Y') FECHAFACP, cantidad, 'TARJETA CREDITO' Departamento, s.idempresa Empresa, s.idproveedor IDR, 0 pa, null solicitudes
        FROM
        ( SELECT GROUP_CONCAT( idsolicitud ) idsolicitud, idproveedor, MAX( fecelab ) fecelab,  SUM( cantidad ) cantidad, tc.idempresa
        FROM tcredito tc
        INNER JOIN ( SELECT GROUP_CONCAT( idsolicitud ) idsolicitud, idresponsable, idempresa, MAX( fecelab ) fecelab, SUM( cantidad ) cantidad FROM solpagos s WHERE caja_chica = 2 AND idetapa = 7 GROUP BY idresponsable ) r ON tc.idtcredito = r.idresponsable
        GROUP BY idproveedor, idempresa ) s
        INNER JOIN proveedores p ON s.idproveedor = p.idproveedor
        INNER JOIN empresas e ON s.idempresa = e.idempresa");
    }

    function getSolicitudesCCH($idsolicitudes)
    {
        return $this->db->query("SELECT s.idsolicitud,s.cantidad, s.proyecto ,IFNULL(s.etapa, '') AS ETAPA , IFNULL(s.condominio, '') AS Condominio , p.nombre AS Proveedor,"
            . "DATE_FORMAT(s.fecelab,'%d/%b/%Y') AS FECHAFACP , s.folio AS Folio , s.justificacion AS Observacion,IFNULL(notifi.visto, 1) AS visto, ltdc.nombre_completo "
            . "FROM solpagos s "
            . "LEFT JOIN lista_rtdc ltdc ON ( ltdc.idtcredito = s.idresponsable AND s.caja_chica = 2 ) "
            . "INNER JOIN proveedores p ON(s.idProveedor = p.idProveedor)"
            . "LEFT JOIN ( SELECT * FROM cpp.notifi "
            . "WHERE idusuario = '" . $this->session->userdata("inicio_sesion")['id'] . "' ) AS notifi ON (notifi.idsolicitud = s.idsolicitud)"
            . " WHERE FIND_IN_SET( s.idsolicitud, '$idsolicitudes' ) ORDER BY s.fecelab");
    }

    function autSolicitud($id)//
    {
        return $this->db->query("UPDATE solpagos SET solpagos.idetapa = CASE 
        WHEN solpagos.rcompra = 1 THEN 3 
        ELSE 5 END, 
        solpagos.rcompra = 0,
        solpagos.idAutoriza = '" . $this->session->userdata('inicio_sesion')['id'] . "', solpagos.fecha_autorizacion = '" . date("Y-m-d H:i:s") . "' 
        WHERE idsolicitud = '$id' AND solpagos.idetapa IN ( 2 )");
    }

    function  declSolicitud($id)
    {
        $this->db->set("idetapa", "4");
        $this->db->set("idAutoriza", $this->session->userdata('inicio_sesion')['id']);
        $this->db->where("idsolicitud", $id);
        return $this->db->update("solpagos");
    }

    function  autorizarPago($id, $cantidad)
    {
        $this->db->update("solpagos", array("idetapa" => 9), " idsolicitud = '$id'");

        //return $this->db->affected_rows();
    }

    function autorizarPagoCompleto($id, $cantidad)
    {
        //$this->db->query("UPDATE solpagos SET solpagos.idetapa = IF( solpagos.idsolicitud NOT IN (SELECT idsolicitud FROM facturas WHERE tipo_factura = 3) AND ( solpagos.idsolicitud IN (SELECT idsolicitud FROM facturas WHERE tipo_factura = 1) OR solpagos.tendrafac = 1 ), 10, 11 ) WHERE idsolicitud = '$id' ");
        //$this->db->query("INSERT INTO `autpagos`(`idsolicitud`,`cantidad`,`idrealiza`)VALUES(".$id.",'".$cantidad."','".$this->session->userdata('inicio_sesion')['id']."')");
        //return $this->db->affected_rows();
    }

    function autorizarPagoC($solicitudes, $comentarios, $idsolicitudes)
    {
        //CONSULTA ORIGINAL
        // $this->db->query("UPDATE solpagos SET solpagos.idetapa = IF( solpagos.idsolicitud NOT IN (SELECT idsolicitud FROM facturas), 10, 11 ) WHERE FIND_IN_SET( idsolicitud, '$id' )");
        /*
        $this->db->query("UPDATE solpagos SET solpagos.idetapa = 11 WHERE FIND_IN_SET( idsolicitud, '$id' )");
        $this->db->query("INSERT INTO ``(`idsolicitud`,`cantidad`,`idrealiza`,`idEmpresa`,`idResponsable`,`nomdepto`)VALUES('".$id."','".$cantidad."','".$this->session->userdata('inicio_sesion')['id']."'"
                . ",'".$idempresa."','".$idresponsable."','".$nomdepto."')");
        return $this->db->affected_rows();
        */
        $this->db->trans_begin();

        //$this->db->insert_batch('autpagos_caja_chic', $solicitudes);
        $this->db->query("INSERT INTO autpagos_caja_chica( idsolicitud, cantidad, idrealiza, idEmpresa, idResponsable, idproveedor, nomdepto ) VALUES ".$solicitudes);
        $this->db->insert_batch('logs', $comentarios);
        $this->db->update("solpagos", array("idetapa" => 11), "FIND_IN_SET( idsolicitud, '$idsolicitudes' )");


        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }

        return $this->db->trans_status();
    }

    function declinarPago($id)
    {
        $this->db->set("idetapa", "30");
        $this->db->where("idsolicitud", $id);
        return $this->db->update("solpagos");
    }

    function esperaSol($id)
    {
        $this->db->set("idetapa", "30");
        $this->db->where("idsolicitud", $id);
        return $this->db->update("solpagos");
    }

    function regresaSol($id)
    {
        $this->db->set("idetapa", "9");
        $this->db->where("idsolicitud", $id);
        return $this->db->update("solpagos");
    }

    function contarNoticaciones()
    {
        $query = $this->db->query("SELECT COUNT(idnotificacion) AS count_id FROM notificaciones WHERE visto = 0");
        return $query->row();
    }

    function declinarPagoCH($id)
    {
        $this->db->set("idetapa", "30");
        $this->db->set("idAutoriza", $this->session->userdata('inicio_sesion')['id']);
        $this->db->where("idsolicitud", $id);
        return $this->db->update("solpagos");
    }

    /*
    function nuevaNotificacionAG(){
        $this->db->query("INSERT INTO notificaciones(`contenido`,`visto`) VALUES('AUTORIZODG',0)");
        return $this->db->affected_rows();
    }
    
    function notificaconV(){
        $this->db->query("UPDATE notificaciones SET visto = 1 WHERE visto = 0");
    }
    */
    function obtenerSolCompra_AutM($filtro) {
        return $this->db->query("SELECT
            s.idsolicitud AS ID,
            IFNULL(s.requisicion, 'NA') requisicion,
            IFNULL(s.orden_compra, 'NA') oc,
            capturista.nombre_completo nombre_capturista,
            direccion_general.nombre_completo nombre_dg,
            s.moneda,
            s.justificacion AS Observacion,
            p.nombre AS Proveedor,
            s.cantidad AS Cantidad, 
            s.fecelab AS Fecha, 
            s.nomdepto AS Departamento, 
            e.nombre AS Estatus,
            s.proyecto,
            s.idProveedor,
            DATE_FORMAT(s.fecelab,'%d/%b/%Y') AS FECHAFAC,
            empre.abrev AS EMPRESA,
            IFNULL(notifi.visto, 1) AS visto,
            DATE_FORMAT(s.fechaCreacion,'%d/%m/%Y') AS fecha_creacion,
            IFNULL(DATE_FORMAT(s.fecha_autorizacion,'%d/%m/%Y'), '-') AS fecha_autorizacion,
            IFNULL( s.prioridad, 0) AS prioridad,
            IFNULL(f.descripcion, '') descripcion
            FROM solpagos s
            INNER JOIN proveedores p ON s.idProveedor = p.idProveedor
            INNER JOIN etapas e ON e.idetapa = s.idetapa
            INNER JOIN listado_usuarios capturista ON capturista.idusuario = s.idResponsable
            INNER JOIN empresas empre ON empre.idempresa = s.idempresa
            LEFT JOIN factura_registro f ON f.idsolicitud = s.idsolicitud
            LEFT JOIN listado_usuarios direccion_general ON direccion_general.idusuario = s.idusuario
            LEFT JOIN notifi ON notifi.idsolicitud = s.idsolicitud AND notifi.idusuario = ?
            $filtro
            ORDER BY s.fecelab", array( $this->session->userdata("inicio_sesion")['id'] ));
    }
}
