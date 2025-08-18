<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_Reportes extends CI_Model {

    function __construct(){
        parent::__construct();
    }
    
    function get_name(){
        $id = 19;
        return $this->db->query("SELECT nombre, apellido_paterno FROM usuarios WHERE id_usuario = '$id'");
    }

    function get_ventas($año){
        $id = 19;
        return $this->db->query("SELECT año, mes, ventas FROM datos_prueba WHERE id_usuario = '$id' AND año = '$año'");
    }

    function get_ventas_compar(){
        $id = 19;
        return $this->db->query("SELECT v1.mes, v1.ventas AS ventas1, v2.ventas AS ventas2 FROM ventas2017 v1, ventas2018 v2 WHERE v1.mes = v2.mes AND v1.id_usuario = '$id'");
    }

    function get_year_byuser($user){
        $user = 19;
        return $this->db->query("SELECT DISTINCT año FROM datos_prueba WHERE id_usuario = '$user'");
    }

    function get_history_observations($fecha_ini = null, $fecha_fin = null, $limit=null, $offset=null) {

        $fechaI = $fecha_ini 
            ? DateTime::createFromFormat('d/m/Y', $fecha_ini) 
            : date("Y").'-01-01'.' 00:00:00';

        if ($fechaI instanceof DateTime) 
            $fechaI = $fechaI->format('Y-m-d').' 00:00:00';

        $fechaF = $fecha_fin 
            ? DateTime::createFromFormat('d/m/Y', $fecha_fin)
            : date("Y-m-d").' 23:59:59';
        
        if ($fechaF instanceof DateTime)
            $fechaF = $fechaF->format('Y-m-d').' 23:59:59';
        return $this->db->query("SELECT	his_obs.idsolicitud, 
                                        GROUP_CONCAT(DISTINCT his_obs.nom_usuario ORDER BY his_obs.nom_usuario) AS nom_usuario,
                                        GROUP_CONCAT(DISTINCT his_obs.depto ORDER BY his_obs.depto) AS depto,
                                        GROUP_CONCAT(DISTINCT his_obs.msj_historial ORDER BY his_obs.msj_historial) AS msj_historial,
                                        GROUP_CONCAT(DISTINCT his_obs.msj_obs ORDER BY his_obs.msj_obs) AS msj_obs,
                                        GROUP_CONCAT(DISTINCT his_obs.etapa ORDER BY his_obs.etapa) AS etapa,
                                        his_obs.fecha,
                                        GROUP_CONCAT(DISTINCT his_obs.etapa_solicitud ORDER BY his_obs.etapa_solicitud) AS etapa_solicitud,
                                        GROUP_CONCAT(DISTINCT his_obs.proc_nombre ORDER BY his_obs.proc_nombre) AS nom_proc
                                FROM(SELECT ls.idsolicitud, CONCAT(us.nombres, ' ', us.apellidos) AS nom_usuario, us.depto, ls.tipomov AS msj_historial, null AS msj_obs, ls.etapa, ls.fecha, et.nombre AS etapa_solicitud, pro.nombre AS proc_nombre
                                    FROM solpagos AS sp
                                    INNER JOIN logs AS ls
                                        ON sp.idsolicitud = ls.idsolicitud
                                    INNER JOIN usuarios AS us
                                        ON ls.idusuario = us.idusuario
                                    INNER JOIN etapas AS et
                                        ON sp.idetapa = et.idetapa
                                    INNER JOIN proceso AS pro
                                        ON sp.idproceso = pro.idproceso
                                    WHERE sp.idproceso IS NOT NULL AND sp.idetapa <> 0 AND (sp.fechaCreacion BETWEEN ? AND ?  )
                                UNION
                                SELECT obs.idsolicitud, CONCAT(us.nombres, ' ', us.apellidos) AS nom_usuario, us.depto, null AS msj_historial, obs.obervacion AS msj, null AS etapa, obs.fecreg AS fech_obs, null AS etapa_solicitud, null AS proc_nombre
                                FROM solpagos AS sp
                                INNER JOIN obssols AS obs
                                    ON sp.idsolicitud = obs.idsolicitud
                                INNER JOIN usuarios AS us
                                    ON obs.idusuario = us.idusuario
                                WHERE sp.idproceso IS NOT NULL AND idetapa <> 0 AND (sp.fechaCreacion BETWEEN ? AND ?  ) ) AS his_obs
                                GROUP BY his_obs.idsolicitud, fecha
                                LIMIT ? OFFSET ?", [$fechaI, $fechaF, $fechaI, $fechaF, $limit, $offset]);
    }

    function getRequestWithInvoices($fecha_inicial, $fecha_final) {
        /**
         * facturas.subtotal,
         * facturas.tasatras16,
         * facturas.importet,
         * facturas.total
        */

        ini_set('memory_limit', '-1'); // O más, dependiendo de la cantidad de datos
        set_time_limit(0); // Sin límite de tiempo de ejecución
        ini_set('max_execution_time', 0);
        
        $where = " YEAR(fecreg) = 2024";
        
        if($fecha_inicial || $fecha_final){
            $fecha_inicial = $fecha_inicial
                ? DateTime::createFromFormat('d/m/Y', $fecha_inicial)
                : date("Y").'-01-01'.' 00:00:00';
            if ($fecha_inicial instanceof DateTime) {
                $fecha_inicial = $fecha_inicial->format('Y-m-d').' 00:00:00';
            }

            $fecha_final = $fecha_final
                ? DateTime::createFromFormat('d/m/Y', $fecha_final)
                : date("Y-m-d").' 23:59:59';
            if ($fecha_final instanceof DateTime) {
                $fecha_final = $fecha_final->format('Y-m-d').' 23:59:59';
            }

            
             $where = "fecreg BETWEEN '".$fecha_inicial."' AND '".$fecha_final."'";
        }

        return $this->db->query("SELECT polizas_provision.idsolicitud,
                                        polizas_provision.idprovision,
                                        DATE_FORMAT(polizas_provision.fecreg, '%d/%m/%Y') AS fecreg,
                                        polizas_provision.numpoliza,
                                        proveedores.nom_proveedor,
                                        proveedores.rfc,
                                        SUBSTRING(facturas.uuid, - 5, 5) AS folio_fiscal,
                                        empresas.abrev,
                                        usuarios.nombre_completo Responsable,
                                        sp.fecelab AS fecha_fact,
                                        sp.fechaCreacion,
                                        autpagos.fechaDis,
                                        if(spo.idProyectos is null, sp.proyecto, pd.nombre) as proyecto,
                                        sp.nomdepto,
                                        sp.justificacion,
                                        et.nombre AS etapa,
                                        facturas.idfactura,
                                        facturas.uuid AS uuid_xml,
                                        facturas.total,
                                        facturas.importet,
                                        facturas.importer,
                                        '' AS cantidad_conceptos_xml,
                                        '' AS unidad_conceptos_xml,
                                        '' AS descripcion_conceptos_xml,
                                        '' AS valor_unitario_conceptos_xml,
                                        '' AS importe_conceptos_xml,
                                        '' AS descuento_concepto_xml,
                                        '' AS base_concepto_impuestos_traslado,
                                        '' AS tipo_factor_concepto_impuestos_traslado,
                                        '' AS tasa_o_cuota_concepto_impuestos_traslado,
                                        '' AS importe_concepto_impuestos_traslado,
                                        '' AS base_concepto_impuestos_retenciones,
                                        '' AS tipo_factor_concepto_impuestos_retenciones,
                                        '' AS tasa_o_cuota_concepto_impuestos_retenciones,
                                        '' AS importe_concepto_impuestos_retenciones
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
                                LEFT JOIN ( SELECT MAX(autpagos.idpago) AS resultado, autpagos.idsolicitud, MAX(autpagos.fechaDis) AS fechaDis
                                            FROM autpagos 
                                            GROUP BY autpagos.idsolicitud ) AS autpagos 
                                    ON autpagos.idsolicitud = polizas_provision.idsolicitud
                                CROSS JOIN listado_usuarios usuarios 
                                    ON polizas_provision.idusuario = usuarios.idusuario
                                INNER JOIN solpagos AS sp
                                    ON polizas_provision.idsolicitud = sp.idsolicitud
                                INNER JOIN ( SELECT * FROM facturas WHERE facturas.tipo_factura = 1 ) facturas 
                                    ON facturas.idsolicitud = sp.idsolicitud
                                INNER JOIN( SELECT proveedores.idproveedor, proveedores.nombre AS nom_proveedor, proveedores.rfc FROM proveedores ) proveedores
                                    ON proveedores.idproveedor = sp.idProveedor
                                INNER JOIN empresas AS empresas
                                    ON sp.idEmpresa = empresas.idempresa
                                INNER JOIN listado_usuarios AS capturista
                                    ON sp.idusuario = capturista.idusuario
                                INNER JOIN etapas AS et
                                    ON sp.idetapa = et.idetapa
                                LEFT JOIN listado_usuarios AS usu_autoriza
                                    ON sp.idautoriza = usu_autoriza.idusuario
                                LEFT JOIN proceso AS pro
                                    ON sp.idproceso = pro.idproceso
                                LEFT JOIN motivo_sol_dev AS msd
                                    ON sp.idsolicitud = msd.idsolicitud
                                LEFT JOIN vw_autpagos AS cant_pag
                                    ON sp.idsolicitud = cant_pag.idsolicitud
                                LEFT JOIN (SELECT idsolicitud, SUM(diastrans) diasTrans, SUM(tipoavance) AS numCancelados 
                                            FROM log_efi 
                                            GROUP BY idsolicitud) log_efi 
                                    ON log_efi.idsolicitud = sp.idsolicitud
                                LEFT JOIN solicitud_proyecto_oficina spo  
                                    ON spo.idsolicitud = sp.idsolicitud 
                                LEFT JOIN proyectos_departamentos pd  
                                    ON spo.idProyectos = pd.idProyectos
                                WHERE $where
                                UNION
                                SELECT  fac.idsolicitud AS idsolicitud,
                                        NULL AS idprovision,
                                        NULL AS fecreg,
                                        NULL AS numpoliza,
                                        NULL AS nom_proveedor,
                                        NULL AS rfc,
                                        NULL AS folio_fiscal,
                                        NULL AS abrev,
                                        NULL AS Responsable,
                                        NULL AS fecha_fact,
                                        NULL AS fechaCreacion,
                                        NULL AS fechaDis,
                                        NULL AS proyecto,
                                        NULL AS nomdepto,
                                        NULL AS justificacion,
                                        NULL AS etapa,
                                        fac.idfactura,
                                        xnc.uuid_xml,
                                        NULL AS total,
                                        NULL AS importet,
                                        NULL AS importer,
                                        con.cantidad_conceptos_xml,
                                        con.unidad_conceptos_xml,
                                        con.descripcion_conceptos_xml,
                                        con.valor_unitario_conceptos_xml,
                                        con.importe_conceptos_xml,
                                        con.descuento_concepto_xml,
                                        tras.base_concepto_impuestos_traslado,
                                        tras.tipo_factor_concepto_impuestos_traslado,
                                        tras.tasa_o_cuota_concepto_impuestos_traslado,
                                        tras.importe_concepto_impuestos_traslado,
                                        ret.base_concepto_impuestos_retenciones,
                                        ret.tipo_factor_concepto_impuestos_retenciones,
                                        ret.tasa_o_cuota_concepto_impuestos_retenciones,
                                        ret.importe_concepto_impuestos_retenciones
                                FROM xml_nodo_comprobante AS xnc
                                INNER JOIN facturas AS fac
                                    ON xnc.uuid_xml = fac.uuid AND fac.tipo_factura = 1
                                INNER JOIN polizas_provision AS pp
                                    ON fac.idsolicitud = pp.idsolicitud
                                LEFT JOIN xml_nodo_conceptos AS con
                                    ON xnc.id_nodo_comprobante = con.id_nodo_comprobante_xml
                                LEFT JOIN xml_nodo_concepto_impuestos_traslado AS tras
                                    ON con.id_nodo_conceptos_xml = tras.id_nodo_conceptos_xml
                                LEFT JOIN xml_nodo_concepto_impuestos_retenciones AS ret
                                    ON con.id_nodo_conceptos_xml = ret.id_nodo_conceptos_xml
                                WHERE $where
                                ORDER BY idsolicitud");
    }

    function obtenerInfoXml($idFactura){
        return $this->db->query("SELECT con.cantidad_conceptos_xml,
                                        con.unidad_conceptos_xml,
                                        con.descripcion_conceptos_xml,
                                        con.valor_unitario_conceptos_xml,
                                        con.importe_conceptos_xml,
                                        con.cantidad_conceptos_xml,
                                        con.descuento_concepto_xml,
                                        
                                        tras.base_concepto_impuestos_traslado,
                                        tras.tipo_factor_concepto_impuestos_traslado,
                                        tras.tasa_o_cuota_concepto_impuestos_traslado,
                                        tras.importe_concepto_impuestos_traslado,
                                        
                                        ret.base_concepto_impuestos_retenciones,
                                        ret.tipo_factor_concepto_impuestos_retenciones,
                                        ret.tasa_o_cuota_concepto_impuestos_retenciones,
                                        ret.importe_concepto_impuestos_retenciones

                                FROM xml_nodo_comprobante AS xnc
                                LEFT JOIN xml_nodo_conceptos AS con
                                    ON xnc.id_nodo_comprobante = con.id_nodo_comprobante_xml
                                LEFT JOIN xml_nodo_concepto_impuestos_traslado AS tras
                                    ON con.id_nodo_conceptos_xml = tras.id_nodo_conceptos_xml
                                LEFT JOIN xml_nodo_concepto_impuestos_retenciones AS ret
                                    ON con.id_nodo_conceptos_xml = ret.id_nodo_conceptos_xml
                                WHERE xnc.uuid_xml IN (SELECT uuid FROM facturas WHERE idfactura IN ($idFactura));");
        
    }

    /** INICIO FECHA: 07-ABRIL-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
    /** FECHA ACTUALIZACION: 21-MAYO-2025 | @author DANTE ALDAIR GUERRERO ALDANA <coordinador6.desarrollo@ciudadmaderas.com> @version 1.2**/
    function reporteHistorialTraspasosDevolucionesModel($activos, $finicio, $ffin, $idUsuario, $filtros){

        return $this->db->query("SELECT sp.idsolicitud,
                                emp.abrev,
                                IF(spo.idProyectos IS NULL, sp.proyecto, pd.nombre) as proyecto,
                                IFNULL(tsp.nombre, 'NA') AS servicioPartida,
                                IFNULL(os.nombre, 'NA') AS oficina,
                                sp.folio,
                                sp.fechaCreacion AS feccrea,
                                sp.fecha_autorizacion,
                                DATE(sp.fecelab) AS fecelab,
                                capturista.nombre_completo,
                                prov.nombre,
                                sp.condominio AS lote,
                                IFNULL(sdrl.referencia, 'NA') AS referencia,
                                IFNULL(etq.etiqueta, 'N/A') AS etiqueta,
                                msd.mantenimiento,
                                msd.predial,
                                IF(msd.motivo IS NULL OR msd.motivo = '', 'NA', msd.motivo) AS motivo,
                                IF(msd.detalle_motivo IS NULL OR msd.detalle_motivo = '', 'NA', msd.detalle_motivo) AS detalle_motivo,
                                sp.nomdepto,
                                sp.metoPago,
                                sp.cantidad,
                                sp.justificacion,
                                cant_pag.upago,
                                cant_pag.fechaDis,
                                cant_pag.fEntrega,
                                cant_pag.fecha_cobro,
                                IFNULL(log_efi.diasTrans, 0) AS diasTrans,
                                IFNULL(log_efi.numCancelados, 0) AS numCancelados,
                                CASE 
                                    WHEN sp.idproceso IN (1, 2, 3, 4, 7, 31, 32, 33, 35) THEN 'ADMINISTRACIÓN'
                                    WHEN sp.idproceso IN (5, 6, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 25, 26, 27, 28, 29, 30, 34) THEN 'POSTVENTA'
                                    ELSE NULL
                                END AS deptoInicia,
                                CASE
                                    WHEN cant_pag.estatus_pago = 15 AND sp.metoPago = 'TEA' THEN 'POR CONFIRMAR PAGO'
                                    WHEN cant_pag.estatus_pago = 15 AND sp.metoPago = 'ECHQ' THEN 'POR ENTREGAR ECHQ'
                                    WHEN cant_pag.estatus_pago = 0 AND sp.idetapa IN (6, 8, 21, 54, 30) THEN et.nombre
                                    ELSE cant_pag.etapa_pago
                                END as etapa_pago
                        FROM solpagos AS sp
                        LEFT JOIN listado_usuarios AS capturista 
                            ON capturista.idusuario = sp.idusuario
                        LEFT JOIN empresas AS emp
                            ON sp.idEmpresa = emp.idempresa
                        LEFT JOIN proveedores AS prov
                            ON sp.idProveedor = prov.idproveedor
                        LEFT JOIN etapas AS et
                            ON sp.idetapa = et.idetapa
                        LEFT JOIN vw_autpagos AS cant_pag
                            ON sp.idsolicitud = cant_pag.idsolicitud
                        LEFT JOIN (SELECT idsolicitud, SUM(diastrans) diasTrans, SUM(tipoavance) AS numCancelados FROM log_efi GROUP BY idsolicitud) AS log_efi
                            ON sp.idsolicitud = log_efi.idsolicitud
                        LEFT JOIN motivo_sol_dev AS msd
                            ON sp.idsolicitud = msd.idsolicitud
                        LEFT JOIN solicitud_proyecto_oficina AS spo
                            ON sp.idsolicitud = spo.idsolicitud
                        LEFT JOIN proyectos_departamentos AS pd
                            ON spo.idProyectos = pd.idProyectos
                        LEFT JOIN oficina_sede AS os
                            ON spo.idOficina = os.idOficina
                        LEFT JOIN tipo_servicio_partidas AS tsp
                            ON spo.idTipoServicioPartida = tsp.idTipoServicioPartida
                        LEFT JOIN etiqueta_sol AS esol 
                            ON sp.idsolicitud = esol.idsolicitud
                        LEFT JOIN etiquetas AS etq
                            ON esol.idetiqueta = etq.idetiqueta
                        LEFT JOIN solicitud_drl AS sdrl
                            ON sp.idsolicitud = sdrl.idsolicitud
                        WHERE (sp.fechaCreacion BETWEEN ? AND ?) AND 
                            sp.idetapa IN ? AND 
                            sp.nomdepto IN (SELECT departamento
                                            FROM departamentos 
                                            INNER JOIN usuario_depto 
                                                ON iddepartamentos = iddepartamento
                                            WHERE usuario_depto.idusuario = ?) $filtros;", [$finicio, $ffin, $activos, $idUsuario]);
    
        /** FECHA: 03-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
    }

    function getSecundariosPorPrincipales($idsPrincipales){ /** INICIO FECHA: 03-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/

        // Si no hay solicitudes padres, no hacemos la consulta de hijos
        if (!empty($idsPrincipales)) {
    
            $datosObservaciones = $this->db->query("SELECT 
                                                        o.idsolicitud,
                                                        o.fecreg,
                                                        o.obervacion AS observacion,
                                                        CONCAT(u.nombres, ' ', u.apellidos) AS usuario,
                                                        CASE 
                                                            WHEN u.rol = 'CE' THEN 'DEVOLUCIONES'
                                                            WHEN u.rol IN ('CT', 'CX') THEN 'CONTABILIDAD'
                                                            ELSE u.depto
                                                        END AS depto_usuario
                                                    FROM (SELECT ce.idsolicitud, 
                                                                 ce.fecha AS fecreg,
                                                                 CONCAT( 'TIPO COMENTARIO ', ce.tipo_comentario, ': ', ce.observacion) AS obervacion,
                                                                 ce.idusario as idusuario
                                                          FROM comentario_especial ce
                                                          WHERE (ce.observacion IS NOT NULL OR ce.observacion <>'')
                                                          UNION ALL
                                                          SELECT obs.idsolicitud,
                                                                 obs.fecreg,
                                                                 obs.obervacion,
                                                                 obs.idusuario
                                                          FROM obssols obs
                                                          WHERE (obs.obervacion IS NOT NULL OR obs.obervacion <>'')) AS o
                                                    INNER JOIN usuarios u ON u.idusuario = o.idusuario
                                                    -- INNER JOIN departamentos d ON d.departamento = u.depto
                                                    AND o.idsolicitud IN ($idsPrincipales)
                                                    ORDER BY o.fecreg ASC")->result_array();
        } else {
            $datosObservaciones = [];
        }
        return $datosObservaciones;

    } 
    /** FIN FECHA: 03-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
    /** FECHA ACTUALIZACION: 21-MAYO-2025 | @author DANTE ALDAIR GUERRERO ALDANA <coordinador6.desarrollo@ciudadmaderas.com> @version 1.2**/

    function reporteSolicitudDevolucionesTraspasosEnCursoModel($finicio, $ffin, $idUsuario, $filtros, $limit, $offset){
        // Aplicamos LIMIT y OFFSET para obtener solo un bloque de registros
        $this->db->limit($limit, $offset);

        // IFNULL(null, 'NA') AS estatus_lote,
        $this->db->select("
            s.idsolicitud,
            empresas.abrev,
            proveedores.nombre,
            s.condominio,
            IFNULL(sdrl.referencia, 'NA') AS referencia,
            IFNULL(eti.etiqueta, 'N/A') AS etiqueta,
            s.etapa,
            DATE(s.fecelab) AS fecelab,
            pr.nombre AS nproceso,
            s.justificacion,
            capturista.nombre_completo,
            s.homoclave AS cuenta_contable,
            s.orden_compra AS cuenta_orden,
            msd.costo_lote,
            msd.superficie,
            msd.preciom,
            msd.predial,
            msd.penalizacion,
            msd.importe_aportado,
            msd.mantenimiento,
            IFNULL(msd.motivo, 'NA') AS motivo,
            IFNULL(msd.detalle_motivo, 'NA') AS detalle_motivo,
            aut.nombre_completo AS nautoriza,
            s.fecha_autorizacion,
            s.cantidad,
            CASE
                WHEN s.idproceso = 30 THEN IFNULL(pagosDevoluciones.cantidad, 0)
                ELSE IFNULL(cant_pag.pagado, 0)
            END AS pagado,
            ((CASE
                WHEN s.idproceso = 30 AND s.programado IS NOT NULL THEN s.cantidad
                WHEN s.idproceso <> 30 AND s.programado IS NOT NULL THEN s.cantidad + IFNULL(cant_pag.pagado, 0)
                ELSE s.cantidad
            END) - 
            (CASE
                WHEN s.idproceso = 30 THEN IFNULL(pagosDevoluciones.cantidad, 0)
                ELSE IFNULL(cant_pag.pagado, 0)
            END)) AS restante,
            IFNULL(diasTrans, 0) AS diasTrans,
            IFNULL(numCancelados, 0) AS numCancelados,
            IF(s.prioridad IS NOT NULL AND s.prioridad <> '', CONCAT(etapas.nombre, ' | ', 'RECHAZADA'), etapas.nombre) AS etapa_estatus,
            s.fechaCreacion
        ");
        // NO SE OCUPAN
        // s.prioridad,
        // s.proyecto, 
        // s.moneda, 
        // IFNULL(DATE(s.fecha_autorizacion), '--') AS fecha_autorizacion, 
        // s.folio,
        // msd.por_penalizacion, 
        // // , IFNULL(h.historial, '') AS historial
    
        $this->db->from("solpagos s", false);

        $this->db->join('(SELECT departamento 
                        FROM departamentos 
                        INNER JOIN usuario_depto ON iddepartamentos = iddepartamento 
                        WHERE usuario_depto.idusuario = "'.$idUsuario.'") depto', 'depto.departamento = s.nomdepto', 'inner');
        $this->db->join('listado_usuarios capturista', 'capturista.idusuario = s.idusuario');
        $this->db->join('empresas', 's.idEmpresa = empresas.idempresa');
        $this->db->join('proveedores', 'proveedores.idproveedor = s.idProveedor');
        $this->db->join('etapas', 'etapas.idetapa = s.idetapa');
        $this->db->join('proceso pr', 's.idproceso = pr.idproceso');
        $this->db->join('listado_usuarios aut', 's.idautoriza = aut.idusuario', 'left');
        $this->db->join('motivo_sol_dev msd', 'msd.idsolicitud = s.idsolicitud', 'left');
        $this->db->join('vw_autpagos cant_pag', 'cant_pag.idsolicitud = s.idsolicitud', 'left');
        $this->db->join('(SELECT idsolicitud, SUM(diastrans) diasTrans, SUM(tipoavance) AS numCancelados FROM log_efi GROUP BY idsolicitud) log_efi', 'log_efi.idsolicitud = s.idsolicitud', 'left');
        $this->db->join('(SELECT idsolicitud, IFNULL(COUNT(idpago), 0) AS pagos, SUM(cantidad) AS cantidad FROM autpagos GROUP BY idsolicitud) pagosDevoluciones', 'pagosDevoluciones.idsolicitud = s.idsolicitud', 'left');
        /**INICIO FECHA : 16-ABRIL-2025 | @author Efrain Martinez Muñoz <programador.analista38@ciudadmaderas.com> 
         * LEFT JOIN que permite agregar el estatus del lote de la solicitud y que se muestra en la columna ESTATUS_LOTE 
         */
        $this->db->join('(SELECT esol.idsolicitud, e.etiqueta FROM etiqueta_sol esol JOIN etiquetas e ON esol.idetiqueta = e.idetiqueta AND esol.rol IS NOT NULL) eti','eti.idsolicitud = s.idsolicitud', 'left');
        /**
         * FIN FECHA : 16-ABRIL-2025 | @author Efrain Martinez Muñoz <programador.analista38@ciudadmaderas.com>
         */
        $this->db->join("solicitud_drl sdrl", "sdrl.idsolicitud = s.idsolicitud", "left");

        $this->db->where("s.idproceso IS NOT NULL");
        $this->db->where("( 
            (s.idetapa NOT IN (0, 1, 30, 10, 11) 
            AND s.fechaCreacion BETWEEN '$finicio' AND '$ffin')
            OR 
            (s.prioridad = 1 
            AND s.idetapa IN (1) 
            AND s.fechaCreacion BETWEEN '$finicio' AND '$ffin')
        )");

        // **Aplicando filtros dinámicos solo si tienen valores**
        foreach ($filtros as $key => $value) {
            if (!empty($value) && $value !== '') {
                switch ($key) {
                    case 'idsolicitud':
                        $this->db->where('s.idsolicitud', $value);
                        break;
                    case 'empresa':
                        $this->db->where('empresas.abrev', $value);
                        break;
                    case 'cliente':
                        $this->db->like('proveedores.nombre', $value);
                        break;
                    case 'lote':
                        // FECHA: 16-ABRIL-2025 Se agrega en el filtro de la columna "LOTE" para que cuando el usuario desee filtrar exportar los datos de la tabla con el estatus del lote "EN CONSTRUCCIÓN O BALDÍO"
                        $this->db->like('IF(eti.etiqueta IS NOT NULL AND (eti.etiqueta = "con construcción" OR eti.etiqueta = "baldío"), CONCAT(s.condominio, " | ", eti.etiqueta), s.condominio)',$value);
                        break;
                    case 'fEntregaPV':
                        $fecha = $this->_convertirFecha($value);
                        $this->db->where('DATE(s.fecelab)', $fecha);
                        break;
                    case 'proceso':
                        $this->db->where('pr.nombre', $value);
                        break;
                    case 'fechVoBo':
                        $fecha = $this->_convertirFecha($value);
                        $this->db->where('DATE(s.fecha_autorizacion)', $fecha);
                        break;
                    case 'restante':
                        $this->db->where('(s.cantidad - IFNULL(cant_pag.pagado, 0)) >', $value);
                        break;
                    case 'diasT':
                        $this->db->where('diasTrans', $value);
                        break;
                    case 'rechazos':
                        $this->db->where('numCancelados', $value);
                        break;
                    case 'estatus':
                        // $this->db->where('etapas.nombre', $value);
                        $this->db->like('IF(s.prioridad IS NOT NULL AND s.prioridad <> "", CONCAT(etapas.nombre, " | ", "RECHAZADA"), etapas.nombre)', $value);
                        break;
                    case 'fCaptura':
                        $fecha = $this->_convertirFecha($value);
                        $this->db->where('DATE(s.fechaCreacion)', $fecha);
                        break;
                }
            }
        }
        $this->db->order_by('s.fechaCreacion', 'DESC');
    
        $datosSolicitudes = $this->db->get()->result_array();
    
        // Obtener los IDs de las solicitudes padres obtenidas
        $idsSolicitudes = array_column($datosSolicitudes, 'idsolicitud');
        
        // Si no hay solicitudes padres, no hacemos la consulta de hijos
        if (!empty($idsSolicitudes)) {
            $idsSolicitudesStr = implode(',', $idsSolicitudes);
    
            $datosObservaciones = $this->db->query("SELECT 
                                                        o.idsolicitud,
                                                        o.fecreg,
                                                        o.obervacion AS observacion,
                                                        CONCAT(u.nombres, ' ', u.apellidos) AS usuario,
                                                        IF(u.rol = 'CE', 'DEVOLUCIONES', 
                                                                IF(u.rol = 'CT' OR u.rol = 'CX', 'CONTABILIDAD', d.departamento)
                                                        ) AS depto_usuario
                                                    FROM (
                                                        SELECT 
                                                            ce.idsolicitud
                                                            , ce.fecha AS fecreg
                                                            , CONCAT( 'TIPO COMENTARIO ', ce.tipo_comentario, ': ', ce.observacion) AS obervacion
                                                            , ce.idusario as idusuario
                                                        FROM comentario_especial ce
                                                        
                                                        UNION 
                                                        
                                                        SELECT 
                                                            obs.idsolicitud
                                                            , obs.fecreg
                                                            , obs.obervacion
                                                            , obs.idusuario
                                                        FROM obssols obs
                                                    ) AS o
                                                    INNER JOIN usuarios u ON u.idusuario = o.idusuario
                                                    INNER JOIN departamentos d ON d.departamento = u.depto
                                                    WHERE o.fecreg BETWEEN '$finicio' AND '$ffin'
                                                    AND (o.obervacion IS NOT NULL OR o.obervacion <>'')
                                                    AND o.idsolicitud IN ($idsSolicitudesStr)
                                                    ORDER BY o.fecreg ASC")->result_array();
        } else {
            $datosObservaciones = [];
        }
    
        return [$datosSolicitudes, $datosObservaciones];
    }

    // Función privada para convertir fechas en formato d/M/Y (español) a Y-m-d
    private function _convertirFecha($fecha) {
        // Array de meses en español a su equivalente en inglés
        $meses_espanol = array(
            'Ene' => 'Jan', 'Feb' => 'Feb', 'Mar' => 'Mar', 'Abr' => 'Apr', 'May' => 'May', 'Jun' => 'Jun', 
            'Jul' => 'Jul', 'Ago' => 'Aug', 'Sep' => 'Sep', 'Oct' => 'Oct', 'Nov' => 'Nov', 'Dic' => 'Dec'
        );

        // Reemplazamos el mes en español con su equivalente en inglés
        foreach ($meses_espanol as $mes_es => $mes_en) {
            if (strpos($fecha, $mes_es) !== false) {
                $fecha = str_replace($mes_es, $mes_en, $fecha);
                break;
            }
        }

        // Convertir la fecha al formato Y-m-d
        $fecha_convertida = date("Y-m-d", strtotime(str_replace("/", "-", $fecha)));
        
        // Retornar la fecha formateada
        return $fecha_convertida;
    } /** FIN FECHA: 07-ABRIL-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/   
}
