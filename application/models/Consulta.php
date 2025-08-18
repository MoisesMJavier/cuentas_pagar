<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Consulta extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    //OBTENEMOS LA INFORMACION ADICIONAL PARA LAS DEVOLUCIONES
    function devolucion_solicitud( $idsolicitud ){
        return $this->db->query("SELECT * FROM motivo_sol_dev WHERE idsolicitud = ?", [ $idsolicitud ] );
    }
    //se agregaron las dos ultimas columnas con el objetivo de validar si el archivo que se va a subir será de tipo invoice. Efrain Martinez Muñoz 29/07/2024
    function factura_solicitud($idsolicitud){
        $query="SELECT 
            solpagos.requisicion,
            solpagos.orden_compra,
            solpagos.crecibo,
            CONCAT(proveedores.nombre, ' - ', cts.nombre) AS contrato,
            solpagos.ref_bancaria, 
            solpagos.intereses, 
            if(solpagos.proyecto is not null, solpagos.proyecto, pd.nombre) as proyecto,
            facturas.finalfact, 
            solpagos.homoclave, 
            solpagos.etapa,
            solpagos.condominio, 
            empresas.rfc AS rfc_receptor, 
            empresas.nombre AS n_receptor, 
            proveedores.rfc, 
            proveedores.nombre, 
            proveedores.cuenta,
            solpagos.nomdepto, 
            b.nombre as banco,
            etapas.nombre AS nom_etapa,
            etapas.idetapa, -- @author Efraín Martinez Muñoz 31/07/2024
            solpagos.pais_gasto,
            solpagos.colabs,
            solpagos.tipo_insumo,
            case proveedores.tipocta when 1 then 'Cuenta Banco del Bajío' when 3 then 'TDD ó TDC' when 40 then 'CLABE' end as tipocta,
            IFNULL(facturas.metpago, 'SIN FACTURA') AS metodo_pago, 
            IFNULL( facturas.foliofac, solpagos.folio ) AS folio,  
            IFNULL(facturas.fecfac, 'SIN DEFINIR') AS fecha_factura, 
            facturas.uuid, 
            facturas.descripcion, 
            facturas.observaciones,
            facturas.regf_emisor AS rf_proveedor,
            facturas.regf_recep AS rf_empresa,
            facturas.domf_recep AS cp_empresa,
            facturas.lugarexp AS cp_proveedor,
            solpagos.cantidad, 
            facturas.total, 
            facturas.nombre_archivo, -- FECHA: 26-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com>
            facturas.ver AS version, -- FECHA: 26-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com>
            case WHEN isnull(hd.idDocumento) then 0 when hd.idDocumento > 0 then 1 END as existePdf,
            case WHEN isnull(hd2.idDocumento) then 0 when hd2.idDocumento > 0 then 1 END as existePdfAut,
            case WHEN isnull(hd3.idDocumento) then 0 when hd3.idDocumento > 0 then 1 END as existePdfReemAut,
            hd.movimiento,
            hd2.movimiento AS movimientoAut,
            hd.expediente AS pdfFile,
            hd2.expediente AS pdfFileAut,
            hd.idUsuario AS usuarioSubioPdf,
            hd2.idUsuario AS usuarioSubioPdfAut,
            hd.idDocumento AS idDocumentoPdf,
            hd2.idDocumento AS idDocumentoPdfAut,
            hd3.movimiento AS movimientoReemAut,
			hd3.expediente AS pdfFileReemAut,
			hd3.idUsuario AS usuarioSubioPdfReemAut,
			hd3.idDocumento AS idDocumentoPdfReemAut,
            solpagos.justificacion, 
            solpagos.caja_chica, 
            solpagos.prioridad, 
            IFNULL(solpagos.metoPago, '---') AS forma_pago, 
            solpagos.tendrafac, 
            IF(solpagos.tendrafac IS NULL OR solpagos.tendrafac = 0,'1', 
            IF(facturas.nombre_archivo IS NULL,'2','3')) AS fac_status, 
            insumos.insumo,
            DATE_FORMAT(solpagos.fecelab, '%Y%m%d') AS fecelab,
            hd.idUsuario as usuarioSubioPdf,
            case WHEN isnull(hd1.idDocumento) then 0 when hd1.idDocumento > 0 then 1 END as existePdfGPND,
            hd1.idUsuario AS usuarioSubioPdfGPND,
            hd1.expediente AS pdfFileGPND,
            hd.idDocumento idDocumentoPdf,
            hd1.idDocumento idDocumentoPdfGPND,
            solpagos.idusuario,
            solpagos.servicio,
            os.nombre oficina,
            pd.nombre proyectoNuevo,
            tsp.nombre tServicioPartida,
            e.estado,
            z.nombre zona,
            se.diasDesayuno,
            se.diasComida,
            se.diasCena,
            proveedores.excp, 
            ds.estatus as estatus_documento
        FROM (SELECT * FROM solpagos WHERE idsolicitud = ?) solpagos 
        INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor 
        INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa 
        LEFT join bancos b on b.idbanco=proveedores.idbanco 
        LEFT JOIN insumos ON insumos.idinsumo = solpagos.servicio 
        LEFT JOIN (
            SELECT 
                facturas.*, 
                0 as finalfact, 
                sl.idsolicitud idrelacion
            FROM (
                SELECT * 
                FROM sol_factura 
                WHERE idsolicitud = ?
            ) sl 
            JOIN facturas ON sl.idfactura = facturas.idfactura AND facturas.tipo_factura IN ( 1, 3 )
        ) AS facturas ON facturas.idrelacion = solpagos.idsolicitud
        LEFT JOIN ( SELECT idetapa, nombre FROM etapas ) etapas ON etapas.idetapa = solpagos.idetapa
        LEFT JOIN historial_documento hd ON hd.idSolicitud = solpagos.idsolicitud AND hd.tipo_doc = 1 AND hd.estatus = 1
        LEFT JOIN historial_documento hd2 ON hd2.idSolicitud = solpagos.idsolicitud AND hd2.tipo_doc = 3 AND hd.estatus = 1
        LEFT JOIN historial_documento hd3 ON hd3.idSolicitud = solpagos.idsolicitud AND hd3.tipo_doc = 4 AND hd.estatus = 1
        LEFT JOIN historial_documento hd1 ON hd1.idSolicitud = solpagos.idsolicitud AND hd1.tipo_doc = 6 AND hd1.estatus = 1
        LEFT JOIN sol_contrato AS sc ON solpagos.idsolicitud = sc.idsolicitud
        LEFT JOIN contratos AS cts ON cts.idcontrato = sc.idcontrato
        LEFT JOIN solicitud_proyecto_oficina spo ON spo.idsolicitud = solpagos.idsolicitud 
        LEFT JOIN oficina_sede os ON spo.idoficina = os.idoficina
        LEFT JOIN proyectos_departamentos pd ON spo.idProyectos = pd.idProyectos
        LEFT JOIN tipo_servicio_partidas tsp ON spo.idTipoServicioPartida = tsp.idTipoServicioPartida
        LEFT JOIN solicitud_estados se on solpagos.idsolicitud = se.idsolicitud
        LEFT JOIN estados e on e.id_estado = se.id_estado
        LEFT JOIN zonas z on e.idZonas = z.idZonas
        LEFT JOIN documentos_solicitud ds on  ds.idsolicitud = solpagos.idsolicitud and ds.estatus = 1
        ORDER BY facturas.feccrea;";
        return $this->db->query( $query, [
            $idsolicitud,
            $idsolicitud
        ]);
    }
    
    //LOCALIZAMOS LA SOLICITU POR MEDIO DE LA FACTURA UUID/ID DEPENDIENDO DE LOS PARAMETROS
    function factura_byuuid( $uuid, $identificador = 1){

        $columna = "";

        if( $identificador )
            $columna = "idsolicitud";
        else 
            $columna = "uuid";

        return $this->db->query("SELECT 
            facturas.idsolicitud, 
            solpagos.ref_bancaria, 
            solpagos.proyecto, 
            solpagos.homoclave, 
            solpagos.etapa, 
            solpagos.condominio, 
            solpagos.fecelab,
            empresas.rfc AS rfc_receptor,
            facturas.regf_recep,
            facturas.domf_recep,
            empresas.nombre AS n_receptor,
            proveedores.rfc, proveedores.nombre, facturas.regf_emisor, facturas.lugarexp, 
            etapas.nombre AS nom_etapa, solpagos.nomdepto,
            IFNULL(facturas.metpago, 'SIN FACTURA') AS metodo_pago, IFNULL( facturas.foliofac, solpagos.folio ) AS folio,  
            IFNULL(facturas.fecfac, 'SIN DEFINIR') AS fecha_factura, facturas.uuid, facturas.descripcion, 
            facturas.observaciones, solpagos.cantidad, facturas.total, facturas.nombre_archivo, solpagos.justificacion, 
            solpagos.caja_chica, solpagos.prioridad, IFNULL(solpagos.metoPago, '---') AS forma_pago, solpagos.tendrafac, 
            IF(solpagos.tendrafac IS NULL OR solpagos.tendrafac = 0,'1', IF(facturas.nombre_archivo IS NULL,'2','3')) 
            AS fac_status,
            facturas.regf_emisor AS rf_proveedor,
            facturas.regf_recep AS rf_empresa,
            facturas.domf_recep AS cp_empresa,
            facturas.lugarexp AS cp_proveedor,
            insumos.insumo 
        FROM ( SELECT * FROM facturas WHERE $columna = ? AND tipo_factura IN ( 1, 3 ) ) facturas
        INNER JOIN solpagos ON facturas.idsolicitud = solpagos.idsolicitud 
        INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor 
        INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa 
        LEFT JOIN ( SELECT idetapa, nombre FROM etapas ) etapas ON etapas.idetapa = solpagos.idetapa
        LEFT JOIN insumos ON insumos.idinsumo = solpagos.servicio", [ $uuid ] );
    }

    function insertar_log($data)
    {
        $this->db->insert("logs", $data);
    }


    function total_solicitude_prov()
    {   //Inicio
        if($this->session->userdata("inicio_sesion")['id']==2876){
            $where = in_array($this->session->userdata("inicio_sesion")['id'], ['2367', '2637', '2673', '2692']) ? '' : 'AND solpagos.idusuario IN (SELECT idusuario FROM usuarios WHERE depto IN ("ADMON MERCADOTECNIA"))';
        }else{
            $where = in_array($this->session->userdata("inicio_sesion")['id'], ['2367', '2637', '2673', '2692']) ? '' : 'AND solpagos.idusuario = ' . $this->session->userdata("inicio_sesion")['id'];
        } 
        /*
        *Fin 
        *@author Efrain Martinez Muñoz 01/08/2024
        */
        switch ($this->session->userdata("inicio_sesion")['rol']) {
            case 'SU':
            case 'CC':
                return $this->db->query("SELECT SUM( solpagos.cantidad ) AS total, CONCAT(responsable.nombres, ' ', responsable.apellidos) AS nresonsable   FROM solpagos INNER JOIN usuarios AS responsable ON responsable.idusuario = solpagos.idResponsable  WHERE ( solpagos.idetapa IN (1 , 3) OR ( solpagos.idetapa = 2 AND solpagos.rcompra = 1 ) ) AND solpagos.nomdepto = '" . $this->session->userdata("inicio_sesion")['depto'] . "'  GROUP BY solpagos.idResponsable order by nresonsable");
                break;
            case 'DA':
            case 'AS':
            case 'CJ':
            case 'CA':
            case 'FP':
            case 'AD':
            case 'CP':
            case 'FP':
                return $this->db->query("SELECT SUM( solpagos.cantidad ) AS total, CONCAT(responsable.nombres, ' ', responsable.apellidos) AS nresonsable   FROM solpagos INNER JOIN usuarios AS responsable ON responsable.idusuario = solpagos.idResponsable  WHERE (solpagos.idetapa IN (1,3) or (solpagos.idetapa=2 and solpagos.rcompra=1)) $where  GROUP BY solpagos.idResponsable order by nresonsable");
                break;
            case 'CX':
            case 'CE':
                return $this->db->query("SELECT SUM( solpagos.cantidad ) AS total, CONCAT(responsable.nombres, ' ', responsable.apellidos) AS nresonsable   FROM solpagos INNER JOIN usuarios AS responsable ON responsable.idusuario = solpagos.idResponsable  
                WHERE solpagos.idetapa IN (1,3) $where GROUP BY solpagos.idResponsable order by nresonsable ");
                break;
        }
    }

    function total_solicitude_prov_otros_gastos()
    {
        return $this->db->query("SELECT SUM( solpagos.cantidad ) AS total FROM solpagos WHERE solpagos.idetapa IN (1,3) AND solpagos.idusuario = '" . $this->session->userdata("inicio_sesion")['id'] . "' AND solpagos.nomdepto != '" . $this->session->userdata("inicio_sesion")['depto'] . "' AND SUBDATE( solpagos.fecelab, 7) <= NOW() GROUP BY solpagos.idetapa");
    }

    //TOTAL REGISTRADO PARA LOS GASTOS DEL REGISTRO
    function total_solicitude_prov_otros_gastos_idsolicitud($idsolicitudes, $idusuario)
    {
        return $this->db->query("SELECT 
            SUM(solpagos.cantidad) as total 
            FROM (
                SELECT 
                    solpagos.*
                FROM 
                    solpagos 
                JOIN ( SELECT DISTINCT( departamento ) departamento FROM departament_usuario WHERE idusuario = ? ) deptos ON solpagos.idetapa IN ( 1, 3, 4, 6, 8, 21, 25, 52 ) AND solpagos.idusuario = ? AND deptos.departamento = solpagos.nomdepto
                UNION
                SELECT 
                    solpagos.*
                FROM 
                    solpagos
                JOIN ( SELECT DISTINCT( departamento ) departamento FROM departament_usuario WHERE idusuario = ? ) deptos ON solpagos.idetapa IN ( 1, 3, 4, 6, 8, 21, 25, 52 ) AND deptos.departamento = solpagos.nomdepto 
                JOIN ( SELECT usuarios.idusuario FROM usuarios WHERE FIND_IN_SET( ?, usuarios.sup ) ) vigilados ON vigilados.idusuario = solpagos.idusuario
            ) solpagos 
            INNER JOIN usuarios AS responsable ON responsable.idusuario = solpagos.idResponsable 
            INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa 
            INNER JOIN proveedores ON solpagos.idProveedor = proveedores.idproveedor 
            $idsolicitudes", [ $idusuario, $idusuario, $idusuario, $idusuario ]);
    }

    function solicitudes_autorizar()
    {
        switch ($this->session->userdata("inicio_sesion")['rol']) {
            case 'DA':
            case 'CC':
            case 'SU':
                return $this->db->query("SELECT solpagos.idsolicitud, CONCAT( responsable.nombres,' ', responsable.apellidos ) AS nresonsable, solpagos.proyecto, solpagos.etapa, solpagos.condominio, empresas.abrev AS nempresa, proveedores.nombre AS nprov, DATE_FORMAT( solpagos.fecelab, '%d/%m/%Y' ) AS fecha_gasto, DATE_FORMAT( solpagos.fechaCreacion, '%d/%m/%Y' ) AS fecha_ela,  IFNULL(facturas.foliofac, 'NA') AS folio, repocision.foliofac AS repsicion, solpagos.justificacion, solpagos.cantidad, solpagos.metoPago AS metodo_pago, CONCAT( IF(solpagos.prioridad = 1, 'PAGO URGENTE', ''), ' ', IF(solpagos.servicio = 1, 'SERVICIO', '') ) AS observaciones FROM solpagos INNER JOIN usuarios AS responsable ON responsable.idusuario = solpagos.idResponsable INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa INNER JOIN proveedores ON solpagos.idProveedor = proveedores.idproveedor LEFT JOIN (SELECT *, MIN(facturas.feccrea) FROM facturas WHERE facturas.tipo_factura IN ( 1, 3 ) GROUP BY facturas.idsolicitud) AS facturas ON facturas.idsolicitud = solpagos.idsolicitud LEFT JOIN ( SELECT facturas.idsolicitud, MAX(facturas.idfactura) AS idfactura, facturas.foliofac FROM facturas WHERE facturas.tipo_factura = 0 GROUP BY facturas.idsolicitud ) AS repocision ON solpagos.idsolicitud = repocision.idsolicitud WHERE solpagos.idetapa IN (1 , 3) AND solpagos.nomdepto = '" . $this->session->userdata("inicio_sesion")['depto'] . "' AND (solpagos.caja_chica != 1 OR solpagos.caja_chica IS NULL) ORDER BY solpagos.idsolicitud");
                break;
            case 'CJ':
            case 'CA':
            case 'AD':
            case 'AS':
                return $this->db->query("SELECT solpagos.idsolicitud, CONCAT( responsable.nombres,' ', responsable.apellidos ) AS nresonsable, solpagos.proyecto, solpagos.etapa, solpagos.condominio, empresas.abrev AS nempresa, proveedores.nombre AS nprov, DATE_FORMAT( solpagos.fecelab, '%d/%m/%Y' ) AS fecha_gasto, DATE_FORMAT( solpagos.fechaCreacion, '%d/%m/%Y' ) AS fecha_ela,  IFNULL(facturas.foliofac, 'NA') AS folio, repocision.foliofac AS repsicion, solpagos.justificacion, solpagos.cantidad, solpagos.metoPago AS metodo_pago, CONCAT( IF(solpagos.prioridad = 1, 'PAGO URGENTE', ''), ' ', IF(solpagos.servicio = 1, 'SERVICIO', '') ) AS observaciones FROM solpagos INNER JOIN usuarios AS responsable ON responsable.idusuario = solpagos.idResponsable INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa INNER JOIN proveedores ON solpagos.idProveedor = proveedores.idproveedor LEFT JOIN (SELECT *, MIN(facturas.feccrea) FROM facturas WHERE facturas.tipo_factura IN ( 1, 3 ) GROUP BY facturas.idsolicitud) AS facturas ON facturas.idsolicitud = solpagos.idsolicitud LEFT JOIN ( SELECT facturas.idsolicitud, MAX(facturas.idfactura) AS idfactura, facturas.foliofac FROM facturas WHERE facturas.tipo_factura = 0 GROUP BY facturas.idsolicitud ) AS repocision ON solpagos.idsolicitud = repocision.idsolicitud WHERE solpagos.idetapa IN (1 , 3) AND solpagos.idusuario = '" . $this->session->userdata("inicio_sesion")['id'] . "' AND (solpagos.caja_chica != 1 OR solpagos.caja_chica IS NULL) ORDER BY solpagos.idsolicitud");
                break;
        }
    }

    function solicitudes_autorizar_otros_gastos()
    {
        return $this->db->query("SELECT solpagos.idsolicitud, CONCAT( responsable.nombres,' ', responsable.apellidos ) AS nresonsable, solpagos.proyecto, solpagos.etapa, solpagos.condominio, empresas.abrev AS nempresa, proveedores.nombre AS nprov, DATE_FORMAT( solpagos.fecelab, '%d/%m/%Y' ) AS fecha_gasto, DATE_FORMAT( solpagos.fechaCreacion, '%d/%m/%Y' ) AS fecha_ela,  IFNULL(facturas.foliofac, 'NA') AS folio, repocision.foliofac AS repsicion, solpagos.justificacion, solpagos.cantidad, solpagos.metoPago AS metodo_pago, CONCAT( IF(solpagos.prioridad = 1, 'PAGO URGENTE', ''), ' ', IF(solpagos.servicio = 1, 'SERVICIO', '') ) AS observaciones FROM solpagos INNER JOIN usuarios AS responsable ON responsable.idusuario = solpagos.idResponsable INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa INNER JOIN proveedores ON solpagos.idProveedor = proveedores.idproveedor LEFT JOIN (SELECT *, MIN(facturas.feccrea) FROM facturas WHERE facturas.tipo_factura IN ( 1, 3 ) GROUP BY facturas.idsolicitud) AS facturas ON facturas.idsolicitud = solpagos.idsolicitud LEFT JOIN ( SELECT facturas.idsolicitud, MAX(facturas.idfactura) AS idfactura, facturas.foliofac FROM facturas WHERE facturas.tipo_factura = 0 GROUP BY facturas.idsolicitud ) AS repocision ON solpagos.idsolicitud = repocision.idsolicitud WHERE solpagos.idetapa IN (1,3) AND solpagos.idusuario IN (  SELECT usuarios.idusuario FROM usuarios WHERE usuarios.depto IN ('" . $this->session->userdata("inicio_sesion")['depto'] . "') ) AND solpagos.nomdepto != '" . $this->session->userdata("inicio_sesion")['depto'] . "' AND SUBDATE( solpagos.fecelab, 7) <= NOW() AND (solpagos.caja_chica != 1 OR solpagos.caja_chica IS NULL) ORDER BY solpagos.idsolicitud");
    }


    function solicitudes_autorizar_otros_gastos_idsolicitud( $filtros, $idusuario ){
        return $this->db->query("SELECT 
            solpagos.idsolicitud, 
            CONCAT( responsable.nombres,' ', responsable.apellidos ) AS nresonsable, 
            IFNULL(solpagos.proyecto, pd.nombre) proyecto,
            solpagos.etapa, 
            solpagos.condominio, 
            empresas.abrev AS nempresa, 
            proveedores.nombre AS nprov, 
            DATE_FORMAT( solpagos.fecelab, '%d/%m/%Y' ) AS fecha_gasto, 
            DATE_FORMAT( solpagos.fechaCreacion, '%d/%m/%Y' ) AS fecha_ela,  
            IFNULL(facturas.foliofac, 'NA') AS folio, 
            repocision.foliofac AS repsicion, 
            solpagos.justificacion, 
            solpagos.cantidad, 
            solpagos.metoPago AS metodo_pago, 
            CONCAT( IF(solpagos.prioridad = 1, 'PAGO URGENTE', ''), ' ', IF(solpagos.servicio = 1, 'SERVICIO', '') ) AS observaciones,
            os.idOficina,
            os.nombre oficina,
            pd.idProyectos,
            pd.nombre proyectoNuevo,
            tsp.idTipoServicioPartida,
            pd.nombre servicioPartida
            FROM (
                SELECT 
                    solpagos.*
                FROM 
                    solpagos 
                JOIN ( SELECT DISTINCT( departamento ) departamento FROM departament_usuario WHERE idusuario = ? ) deptos ON solpagos.idetapa IN ( 1, 3, 4, 6, 8, 21, 25, 52 ) AND solpagos.idusuario = ? AND deptos.departamento = solpagos.nomdepto
                UNION
                SELECT 
                    solpagos.*
                FROM 
                    solpagos
                JOIN ( SELECT DISTINCT( departamento ) departamento FROM departament_usuario WHERE idusuario = ? ) deptos ON solpagos.idetapa IN ( 1, 3, 4, 6, 8, 21, 25, 52 ) AND deptos.departamento = solpagos.nomdepto 
                JOIN ( SELECT usuarios.idusuario FROM usuarios WHERE FIND_IN_SET( ?, usuarios.sup ) ) vigilados ON vigilados.idusuario = solpagos.idusuario
            ) solpagos 
            INNER JOIN usuarios AS responsable ON responsable.idusuario = solpagos.idResponsable 
            INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa 
            INNER JOIN proveedores ON solpagos.idProveedor = proveedores.idproveedor 
            LEFT JOIN (SELECT *, MIN(facturas.feccrea) FROM facturas WHERE facturas.tipo_factura IN ( 1, 3 ) GROUP BY facturas.idsolicitud) AS facturas ON facturas.idsolicitud = solpagos.idsolicitud 
            LEFT JOIN ( SELECT facturas.idsolicitud, MAX(facturas.idfactura) AS idfactura, facturas.foliofac FROM facturas WHERE facturas.tipo_factura = 0 GROUP BY facturas.idsolicitud ) AS repocision ON solpagos.idsolicitud = repocision.idsolicitud 
            LEFT JOIN listado_usuarios capturista ON capturista.idusuario = solpagos.idusuario 
            LEFT JOIN solicitud_proyecto_oficina spo ON spo.idsolicitud = solpagos.idsolicitud 
            LEFT JOIN oficina_sede os ON spo.idoficina = os.idoficina
            LEFT JOIN proyectos_departamentos pd ON spo.idProyectos = pd.idProyectos
            LEFT JOIN tipo_servicio_partidas tsp ON spo.idTipoServicioPartida = tsp.idTipoServicioPartida
            $filtros ORDER BY solpagos.idsolicitud", [ $idusuario, $idusuario, $idusuario, $idusuario ]);
    }

    function autorizar_caja_chica_conglomerado()
    {
        $where = in_array($this->session->userdata("inicio_sesion")['id'], ['2367', '2637', '2673', '2692', '2843']) ? '' : 'AND solpagos.idusuario = ' . $this->session->userdata("inicio_sesion")['id'];
        switch ($this->session->userdata("inicio_sesion")['rol']) {
            case 'SU':
            case 'CC':
                return $this->db->query("SELECT DATE_FORMAT( MAX(solpagos.fechaCreacion), '%d/%m/%Y' ) AS fecha_ela, empresas.abrev, CONCAT( responsable.nombres,' ', responsable.apellidos ) AS nresonsable,  DATE_FORMAT( MAX( solpagos.fecelab ), '%d/%m/%Y' ) AS fecha_gasto, SUM( solpagos.cantidad ) AS cantidad FROM solpagos INNER JOIN usuarios AS responsable ON responsable.idusuario = solpagos.idResponsable INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa WHERE solpagos.caja_chica = 1 AND solpagos.idetapa IN (1,3) AND solpagos.nomdepto = '" . $this->session->userdata("inicio_sesion")['depto'] . "' GROUP BY solpagos.idResponsable, solpagos.idEmpresa");
                break;
            case 'DA':
            case 'CJ':
            case 'CA':
            case 'FP':
            case 'AD':
            case 'AS':
            case 'CP':
                return $this->db->query("SELECT DATE_FORMAT( MAX(solpagos.fechaCreacion), '%d/%m/%Y' ) AS fecha_ela, empresas.abrev, CONCAT( responsable.nombres,' ', responsable.apellidos ) AS nresonsable,  DATE_FORMAT( MAX( solpagos.fecelab ), '%d/%m/%Y' ) AS fecha_gasto, SUM( solpagos.cantidad ) AS cantidad FROM solpagos INNER JOIN usuarios AS responsable ON responsable.idusuario = solpagos.idResponsable INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa WHERE solpagos.caja_chica = 1 AND solpagos.idetapa IN (1,3) 
                $where GROUP BY solpagos.idResponsable, solpagos.idEmpresa");
                break;
        }
    }

    function autorizar_caja_chica_conglomerado_otros_gastos()
    {
        return $this->db->query("SELECT DATE_FORMAT( MAX(solpagos.fechaCreacion), '%d/%m/%Y' ) AS fecha_ela, empresas.abrev, CONCAT( responsable.nombres,' ', responsable.apellidos ) AS nresonsable,  DATE_FORMAT( MAX( solpagos.fecelab ), '%d/%m/%Y' ) AS fecha_gasto, SUM( solpagos.cantidad ) AS cantidad FROM solpagos INNER JOIN usuarios AS responsable ON responsable.idusuario = solpagos.idResponsable INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa WHERE solpagos.caja_chica = 1 AND solpagos.idetapa IN (1,3) AND solpagos.idusuario = '" . $this->session->userdata("inicio_sesion")['id'] . "' AND solpagos.nomdepto != '" . $this->session->userdata("inicio_sesion")['depto'] . "' AND SUBDATE( solpagos.fecelab, 7) <= NOW() GROUP BY solpagos.idResponsable, solpagos.idEmpresa");
    }

    //COMPROBANTES DE CAJA CHICA
    function autorizar_caja_chica()
    {
        if($this->session->userdata("inicio_sesion")['id']==2876){
            $where = in_array($this->session->userdata("inicio_sesion")['id'], ['2367', '2637', '2673', '2692']) ? '' : 'AND solpagos.idusuario IN (SELECT idusuario FROM usuarios WHERE depto IN ("ADMON MERCADOTECNIA"))';
        }else{
            $where = in_array($this->session->userdata("inicio_sesion")['id'], ['2367', '2637', '2673', '2692']) ? '' : 'AND solpagos.idusuario = ' . $this->session->userdata("inicio_sesion")['id'];
        } 
        switch ($this->session->userdata("inicio_sesion")['rol']) {
            case 'DA':
            case 'SU':
            case 'CC':
                return 
                    $this->db->query("SELECT solpagos.idsolicitud, 
                                         CONCAT( responsable.nombres,' ', responsable.apellidos ) AS nresonsable, 
                                         if(spo.idProyectos is null, solpagos.proyecto, pd.nombre) as proyecto,
                                         solpagos.etapa,
                                         solpagos.condominio,
                                         empresas.abrev AS nempresa,
                                         proveedores.nombre AS nprov,
                                         DATE_FORMAT( solpagos.fechaCreacion, '%d/%m/%Y' ) AS fecha_crecion,
                                         DATE_FORMAT( solpagos.fecelab, '%d/%m/%Y' ) AS fecha_gasto,
                                         IFNULL(facturas.foliofac, 'NA') AS folio,
                                         solpagos.justificacion,
                                         CONCAT( IF(solpagos.prioridad = 1, 'PAGO URGENTE', ''), ' ', IF(solpagos.servicio = 1, 'SERVICIO', '') ) AS observaciones, 
                                         solpagos.cantidad,
                                         os.nombre oficina,
                                         tsp.nombre servicioPartida
                                    FROM solpagos 
                                    INNER JOIN usuarios AS responsable 
                                        ON responsable.idusuario = solpagos.idResponsable 
                                    INNER JOIN empresas 
                                        ON empresas.idempresa = solpagos.idEmpresa 
                                    INNER JOIN proveedores
                                        ON solpagos.idProveedor = proveedores.idproveedor
                                    LEFT JOIN ( SELECT *, MIN(facturas.feccrea) 
                                                FROM facturas
                                                WHERE facturas.tipo_factura IN ( 1, 3 )
                                                GROUP BY facturas.idsolicitud) AS facturas 
                                        ON facturas.idsolicitud = solpagos.idsolicitud 
                                    LEFT JOIN solicitud_proyecto_oficina spo  
                                        ON spo.idsolicitud = solpagos.idsolicitud
                                    LEFT JOIN proyectos_departamentos pd
                                        ON spo.idProyectos = pd.idProyectos
                                    LEFT JOIN oficina_sede os 
                                        on spo.idOficina = os.idOficina 
                                    left join tipo_servicio_partidas tsp 
                                        on tsp.idTipoServicioPartida = spo.idTipoServicioPartida 
                                    WHERE solpagos.idetapa IN (1,3) AND 
                                        solpagos.caja_chica = 1 AND 
                                        solpagos.nomdepto = '" . $this->session->userdata("inicio_sesion")['depto'] . "'");
                break;
            case 'AS':
            case 'CJ':
            case 'CA':
            case 'AD':
            case 'CP':
            case 'FP':
                return $this->db->query("SELECT solpagos.idsolicitud, 
                                                CONCAT( responsable.nombres,' ', responsable.apellidos ) AS nresonsable, 
                                                if(spo.idProyectos is null, solpagos.proyecto, pd.nombre) as proyecto, 
                                                solpagos.etapa, 
                                                solpagos.condominio, 
                                                empresas.abrev AS nempresa,
                                                proveedores.nombre AS nprov, 
                                                DATE_FORMAT( solpagos.fechaCreacion, '%d/%m/%Y' ) AS fecha_crecion,
                                                DATE_FORMAT( solpagos.fecelab, '%d/%m/%Y' ) AS fecha_gasto, 
                                                IFNULL(facturas.foliofac, 'NA') AS folio,  
                                                solpagos.justificacion, 
                                                CONCAT( IF(solpagos.prioridad = 1, 'PAGO URGENTE', ''), ' ', IF(solpagos.servicio = 1, 'SERVICIO', '') ) AS observaciones, 
                                                solpagos.cantidad,
                                                os.nombre oficina, 
                                                tsp.nombre servicioPartida 
                                        FROM solpagos 
                                        INNER JOIN usuarios AS responsable 
                                            ON responsable.idusuario = solpagos.idResponsable 
                                        INNER JOIN empresas 
                                            ON empresas.idempresa = solpagos.idEmpresa 
                                        INNER JOIN proveedores 
                                            ON solpagos.idProveedor = proveedores.idproveedor 
                                        LEFT JOIN (SELECT *, MIN(facturas.feccrea) FROM facturas WHERE facturas.tipo_factura IN ( 1, 3 ) GROUP BY facturas.idsolicitud) AS facturas 
                                            ON facturas.idsolicitud = solpagos.idsolicitud 
                                        LEFT JOIN solicitud_proyecto_oficina spo
                                            ON spo.idsolicitud = solpagos.idsolicitud 
                                        LEFT JOIN proyectos_departamentos pd
                                            ON spo.idProyectos = pd.idProyectos 
                                        LEFT JOIN oficina_sede os 
                                            ON spo.idOficina = os.idOficina 
                                        LEFT JOIN tipo_servicio_partidas tsp 
                                        ON tsp.idTipoServicioPartida = spo.idTipoServicioPartida  
                                        WHERE solpagos.idetapa IN (1,3) AND solpagos.caja_chica = 1 $where");
                break;
            }


        }

    function autorizar_comprobantes_TDC()
    {
        //Inicio
        if($this->session->userdata("inicio_sesion")['id']==2876){
            $where = in_array($this->session->userdata("inicio_sesion")['id'], ['2367', '2637', '2673', '2692']) ? '' : 'AND solpagos.idusuario IN (SELECT idusuario FROM usuarios WHERE depto IN ("ADMON MERCADOTECNIA"))';
        }else{
            $where = in_array($this->session->userdata("inicio_sesion")['id'], ['2367', '2637', '2673', '2692']) ? '' : 'AND solpagos.idusuario = ' . $this->session->userdata("inicio_sesion")['id'];
        } 
        /*
        *Fin 
        *@author Efrain Martinez Muñoz 01/08/2024
        *validacion del usuario Anet
        */
        switch ($this->session->userdata("inicio_sesion")['rol']) {
            case 'DA':
            case 'SU':
            case 'CC':
                return $this->db->query("SELECT solpagos.idsolicitud, CONCAT( responsable.nombre_completo, ' TDC' ) nresonsable, if(solpagos.proyecto is not null, solpagos.proyecto, pd.nombre) as proyecto, solpagos.etapa, solpagos.condominio, empresas.abrev AS nempresa, proveedores.nombre AS nprov, DATE_FORMAT( solpagos.fechaCreacion, '%d/%m/%Y' ) AS fecha_crecion, DATE_FORMAT( solpagos.fecelab, '%d/%m/%Y' ) AS fecha_gasto, 
                IFNULL(facturas.uuid, 'NA') AS folio,  
                solpagos.justificacion, CONCAT( IF(solpagos.prioridad = 1, 'PAGO URGENTE', ''), ' ', IF(solpagos.servicio = 1, 'SERVICIO', '') ) AS observaciones, solpagos.cantidad, 
                os.nombre oficina,
                tsp.nombre servicioPartida 
                FROM solpagos 
                INNER JOIN lista_rtdc responsable ON responsable.idtcredito = solpagos.idResponsable 
                INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa 
                INNER JOIN proveedores ON solpagos.idProveedor = proveedores.idproveedor 
                LEFT JOIN factura_registro AS facturas ON facturas.idsolicitud = solpagos.idsolicitud 
                LEFT JOIN solicitud_proyecto_oficina spo  on spo.idsolicitud = solpagos.idsolicitud 
                LEFT JOIN proyectos_departamentos pd  on spo.idProyectos = pd.idProyectos
                LEFT JOIN oficina_sede os on spo.idOficina = os.idOficina 
                left join tipo_servicio_partidas tsp on tsp.idTipoServicioPartida = spo.idTipoServicioPartida 
                WHERE solpagos.idetapa IN ( 1, 3 ) AND solpagos.caja_chica = 2 AND solpagos.nomdepto = '" . $this->session->userdata("inicio_sesion")['depto'] . "'
                ORDER BY nresonsable");
                break;
            case 'AS':
            case 'CJ':
            case 'CA':
            case 'AD':
            case 'CP':
            case 'FP':
                return $this->db->query("SELECT solpagos.idsolicitud, CONCAT( responsable.nombre_completo, ' TDC' ) nresonsable, if(solpagos.proyecto is not null, solpagos.proyecto, pd.nombre) as proyecto, solpagos.etapa, solpagos.condominio, empresas.abrev AS nempresa, proveedores.nombre AS nprov, DATE_FORMAT( solpagos.fechaCreacion, '%d/%m/%Y' ) AS fecha_crecion, DATE_FORMAT( solpagos.fecelab, '%d/%m/%Y' ) AS fecha_gasto, 
                IFNULL(facturas.uuid, 'NA') AS folio,  
                solpagos.justificacion, CONCAT( IF(solpagos.prioridad = 1, 'PAGO URGENTE', ''), ' ', IF(solpagos.servicio = 1, 'SERVICIO', '') ) AS observaciones, solpagos.cantidad, 
                os.nombre oficina,
                tsp.nombre servicioPartida 
                FROM solpagos 
                INNER JOIN lista_rtdc responsable ON responsable.idtcredito = solpagos.idResponsable 
                INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa 
                INNER JOIN proveedores ON solpagos.idProveedor = proveedores.idproveedor 
                LEFT JOIN factura_registro AS facturas ON facturas.idsolicitud = solpagos.idsolicitud 
                LEFT JOIN solicitud_proyecto_oficina spo  on spo.idsolicitud = solpagos.idsolicitud 
                LEFT JOIN proyectos_departamentos pd  on spo.idProyectos = pd.idProyectos
                LEFT JOIN oficina_sede os on spo.idOficina = os.idOficina 
                left join tipo_servicio_partidas tsp on tsp.idTipoServicioPartida = spo.idTipoServicioPartida 
                WHERE solpagos.idetapa IN (1,3) AND solpagos.caja_chica = 2 $where
                ORDER BY nresonsable");
                break;
        }
    }

    function autorizar_caja_chica_otros_gastos()
    {
        return $this->db->query("SELECT solpagos.idsolicitud, CONCAT( responsable.nombres,' ', responsable.apellidos ) AS nresonsable, solpagos.proyecto, solpagos.etapa, solpagos.condominio, empresas.abrev AS nempresa, proveedores.nombre AS nprov, DATE_FORMAT( solpagos.fechaCreacion, '%d/%m/%Y' ) AS fecha_crecion, DATE_FORMAT( solpagos.fecelab, '%d/%m/%Y' ) AS fecha_gasto, IFNULL(facturas.foliofac, 'NA') AS folio,  solpagos.justificacion, CONCAT( IF(solpagos.prioridad = 1, 'PAGO URGENTE', ''), ' ', IF(solpagos.servicio = 1, 'SERVICIO', '') ) AS observaciones, solpagos.cantidad FROM solpagos INNER JOIN usuarios AS responsable ON responsable.idusuario = solpagos.idResponsable INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa INNER JOIN proveedores ON solpagos.idProveedor = proveedores.idproveedor LEFT JOIN (SELECT *, MIN(facturas.feccrea) FROM facturas WHERE facturas.tipo_factura IN ( 1, 3 ) GROUP BY facturas.idsolicitud) AS facturas ON facturas.idsolicitud = solpagos.idsolicitud WHERE solpagos.idetapa IN (1,3) AND solpagos.caja_chica = 1 AND solpagos.idusuario = '" . $this->session->userdata("inicio_sesion")['id'] . "' AND solpagos.nomdepto != '" . $this->session->userdata("inicio_sesion")['depto'] . "' AND SUBDATE( solpagos.fecelab, 7) <= NOW() ORDER BY solpagos.idsolicitud");
    }

    function insertar_observacion($idsolicitud, $comentarios, $usuario)
    {
        $this->db->insert("obssols", array("idsolicitud" => $idsolicitud, "idusuario" => $usuario, "obervacion" => $comentarios));
    }

    function getConversacion($idsolicitud) /** INICIO FECHA: 27-FEBRERO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
    {
        return $this->db->query("SELECT * 
                FROM (
                    (
                        SELECT 
                            usuarios.idusuario
                            , usuarios.rol
                            , obs.obervacion
                            , DATE_FORMAT( obs.fecreg, '%H:%i:%s %d-%m-%Y' ) AS fecha_formateada
                            , CONCAT( usuarios.nombres,' ',usuarios.apellidos ) AS nombre_usuario
                            , IF(usuarios.rol = 'CE', 'DEVOLUCIONES', 
                                    IF(usuarios.rol = 'CT' OR usuarios.rol = 'CX', 'CONTABILIDAD', d.departamento)
                            ) AS depto_usuario
                        FROM obssols obs
                        INNER JOIN usuarios ON usuarios.idusuario = obs.idusuario
                        INNER JOIN departamentos d ON d.departamento = CASE WHEN usuarios.rol IN('CX', 'CT') THEN 'CONTABILIDAD' WHEN usuarios.rol IN('CE') THEN 'DEVOLUCIONES' ELSE usuarios.depto END
                        WHERE obs.idsolicitud = ?
                        GROUP BY obs.idobs
                    ) 
                    UNION 
                    ( 
                        SELECT 
                            usuarios.idusuario
                            , usuarios.rol
                            , CONCAT( 'TIPO COMENTARIO ', comentario_especial.tipo_comentario, ': ', comentario_especial.observacion) observacion
                            , DATE_FORMAT( comentario_especial.fecha, '%H:%i:%s %d-%m-%Y' ) AS fecha_formateada
                            , CONCAT( usuarios.nombres,' ',usuarios.apellidos ) AS nombre_usuario
                            , IF(usuarios.rol = 'CE', 'DEVOLUCIONES', 
                                    IF(usuarios.rol = 'CT' OR usuarios.rol = 'CX', 'CONTABILIDAD', d.departamento)
                            ) AS depto_usuario
                        FROM comentario_especial 
                        INNER JOIN usuarios ON usuarios.idusuario = comentario_especial.idusario 
                        INNER JOIN departamentos d ON d.departamento = CASE WHEN usuarios.rol IN('CX', 'CT') THEN 'CONTABILIDAD' WHEN usuarios.rol IN('CE') THEN 'DEVOLUCIONES' ELSE usuarios.depto END
                        WHERE comentario_especial.idsolicitud = ?
                    )
                ) AS subconsulta
                ORDER BY STR_TO_DATE(fecha_formateada, '%H:%i:%s %d-%m-%Y') ASC;
            ", array($idsolicitud, $idsolicitud)
        );
    } /** FIN FECHA: 27-FEBRERO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/

    function getCta($cuenta)
    {
        return $this->db->query("SELECT * FROM cuentas WHERE cuentas.nodecta = '$cuenta'");
    }

    function getnombreBanco($nombreBanco)
    {
        return $this->db->query("SELECT * FROM bancos WHERE bancos.nombre = '$nombreBanco'");
    }

    function getclvbanco($clvbanco)
    {
        return $this->db->query("SELECT * FROM bancos WHERE bancos.clvbanco = $clvbanco");
    }


    function getCtaEmp($cta, $emp)
    {
        return $this->db->query("SELECT * FROM serie_cheques WHERE serie_cheques.idCuenta = '$cta' AND serie_cheques.idEmp = '$emp' ");
    }

    function getRfc($rfc)
    {
        return $this->db->query("SELECT * FROM empresas WHERE empresas.rfc = '$rfc'");
    }

    function factura_pago($idpago)
    {
        return $this->db->query("SELECT * FROM autpagos WHERE autpagos.idpago = '$idpago'");
    }

    function get_notificacion($idsolicitud, $idusuario)
    {
        return $this->db->query("SELECT * FROM notificaciones WHERE notificaciones.visto = 0 AND notificaciones.idsolicitud = '$idsolicitud' AND notificaciones.idusuario = '$idusuario'")->num_rows();
    }

    function leer_notificacion($idsolicitud, $idusuario)
    {
        $this->db->update("notificaciones", array("visto" => 1), "idsolicitud = '$idsolicitud' AND idusuario = '$idusuario'");
    }

    function Movimientos_Solicitud($idsolicitud)
    {
        /** FECHA: 14-MARZO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
        return $this->db->query("SELECT 
                                    tipomov
                                    , fecha, concat(usuarios.nombres , ' ' , usuarios.apellidos) as nombre_completo
                                    , CASE 
                                        WHEN usuarios.rol = 'CE' THEN 'DEVOLUCIONES'
                                        WHEN usuarios.rol IN ('CT', 'CX') THEN 'CONTABILIDAD'
                                        ELSE usuarios.depto
                                    END AS depto
                                FROM logs 
                                INNER JOIN usuarios ON usuarios.idusuario = logs.idusuario 
                                WHERE idsolicitud = '$idsolicitud' 
                                ORDER BY fecha DESC;");
    }

    function numero_responsables()
    {
        //Inicio
        if($this->session->userdata("inicio_sesion")['id']==2876){
            $where = in_array($this->session->userdata("inicio_sesion")['id'], ['2367', '2637', '2673', '2692']) ? '' : 'AND solpagos.idusuario IN (SELECT idusuario FROM usuarios WHERE depto IN ("ADMON MERCADOTECNIA"))';
        }else{
            $where = in_array($this->session->userdata("inicio_sesion")['id'], ['2367', '2637', '2673', '2692']) ? '' : 'AND solpagos.idusuario = ' . $this->session->userdata("inicio_sesion")['id'];
        } 
        /*
        *Fin 
        *@author Efrain Martinez Muñoz 01/08/2024
        *validacion del usuario Anet
        */
        switch ($this->session->userdata("inicio_sesion")['rol']) {
            case 'SU':
            case 'CC':
                return $this->db->query("(SELECT DISTINCT  CONCAT(responsable.nombres, ' ', responsable.apellidos) AS nresonsable, empresas.abrev AS nempresa FROM solpagos INNER JOIN    usuarios AS responsable ON responsable.idusuario = solpagos.idResponsable 
                INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa 
                INNER JOIN proveedores ON solpagos.idProveedor = proveedores.idproveedor 
                LEFT JOIN (SELECT *, MIN(facturas.feccrea) FROM facturas WHERE facturas.tipo_factura IN (1 , 3) GROUP BY facturas.idsolicitud) AS facturas ON facturas.idsolicitud = solpagos.idsolicitud        
                LEFT JOIN (SELECT facturas.idsolicitud, MAX(facturas.idfactura) AS idfactura, facturas.foliofac FROM facturas WHERE facturas.tipo_factura = 0 GROUP BY facturas.idsolicitud) AS repocision ON solpagos.idsolicitud = repocision.idsolicitud 
                WHERE solpagos.idetapa IN (1 , 3) AND solpagos.nomdepto = '" . $this->session->userdata("inicio_sesion")['depto'] . "' AND (solpagos.caja_chica != 1 OR solpagos.caja_chica IS NULL) ORDER BY nresonsable ASC , nempresa)
                UNION 
                (SELECT DISTINCT  CONCAT(responsable.nombres, ' ', responsable.apellidos) AS nresonsable, empresas.abrev AS nempresa FROM solpagos 
                INNER JOIN usuarios AS responsable ON responsable.idusuario = solpagos.idResponsable 
                INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa
                WHERE solpagos.caja_chica = 1 AND solpagos.idetapa IN (1 , 3) AND solpagos.nomdepto = '" . $this->session->userdata("inicio_sesion")['depto'] . "' GROUP BY solpagos.idResponsable , solpagos.idEmpresa)  ORDER BY nresonsable");
                break;
            case 'DA':
            case 'AS':
            case 'CJ':
            case 'CA':
            case 'FP':
            case 'AD':
            case 'CP':
            case 'FP':
                return $this->db->query("SELECT DISTINCT  CONCAT(responsable.nombres, ' ', responsable.apellidos) AS nresonsable, empresas.abrev AS nempresa FROM solpagos
                INNER JOIN    usuarios AS responsable ON responsable.idusuario = solpagos.idResponsable 
                INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa 
                INNER JOIN proveedores ON solpagos.idProveedor = proveedores.idproveedor 
                LEFT JOIN (SELECT *, MIN(facturas.feccrea) FROM facturas WHERE facturas.tipo_factura IN (1 , 3) GROUP BY facturas.idsolicitud) AS facturas ON facturas.idsolicitud = solpagos.idsolicitud        
                LEFT JOIN (SELECT facturas.idsolicitud, MAX(facturas.idfactura) AS idfactura, facturas.foliofac FROM facturas WHERE facturas.tipo_factura = 0 GROUP BY facturas.idsolicitud) AS repocision ON solpagos.idsolicitud = repocision.idsolicitud 
                WHERE solpagos.idetapa IN (1 , 3) $where AND (solpagos.caja_chica = 0 OR solpagos.caja_chica IS NULL )
                UNION 
                SELECT DISTINCT  CONCAT(responsable.nombres, ' ', responsable.apellidos) AS nresonsable, empresas.abrev AS nempresa FROM solpagos
                INNER JOIN usuarios AS responsable ON responsable.idusuario = solpagos.idResponsable 
                INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa 
                WHERE solpagos.caja_chica = 1 AND solpagos.idetapa IN (1 , 3) $where
                UNION
                SELECT DISTINCT  CONCAT(responsable.nombres, ' ', responsable.apellidos, ' TDC') AS nresonsable, empresas.abrev AS nempresa FROM solpagos
                INNER JOIN usuarios AS responsable ON responsable.idusuario = solpagos.idResponsable 
                INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa 
                WHERE solpagos.caja_chica = 2 AND solpagos.idetapa IN (1 , 3) $where");
                break;
            case 'CE':
            case 'CX':
                return $this->db->query("(SELECT DISTINCT  CONCAT(responsable.nombres, ' ', responsable.apellidos) AS nresonsable, empresas.abrev AS nempresa FROM solpagos
                INNER JOIN    usuarios AS responsable ON responsable.idusuario = solpagos.idResponsable 
                INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa 
                INNER JOIN proveedores ON solpagos.idProveedor = proveedores.idproveedor 
                LEFT JOIN (SELECT *, MIN(facturas.feccrea) FROM facturas WHERE facturas.tipo_factura IN (1 , 3) GROUP BY facturas.idsolicitud) AS facturas ON facturas.idsolicitud = solpagos.idsolicitud        
                LEFT JOIN (SELECT facturas.idsolicitud, MAX(facturas.idfactura) AS idfactura, facturas.foliofac FROM facturas WHERE facturas.tipo_factura = 0 GROUP BY facturas.idsolicitud) AS repocision ON solpagos.idsolicitud = repocision.idsolicitud 
                WHERE solpagos.idetapa IN (3) AND solpagos.idAutoriza = '" . $this->session->userdata("inicio_sesion")['id'] . "' AND (solpagos.caja_chica != 1 OR solpagos.caja_chica IS NULL) ORDER BY nresonsable ASC , nempresa)
                UNION 
                (SELECT DISTINCT  CONCAT(responsable.nombres, ' ', responsable.apellidos) AS nresonsable, empresas.abrev AS nempresa FROM solpagos
                INNER JOIN usuarios AS responsable ON responsable.idusuario = solpagos.idResponsable 
                INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa 
                WHERE solpagos.caja_chica = 1 AND solpagos.idetapa IN (3) AND solpagos.idAutoriza = '" . $this->session->userdata("inicio_sesion")['id'] . "' GROUP BY solpagos.idResponsable , solpagos.idEmpresa)  ORDER BY nresonsable");
                break;
        }
    }

    function cajachcon_solauto()
    {
        //Inicio
        if($this->session->userdata("inicio_sesion")['id']==2876){
            $where = in_array($this->session->userdata("inicio_sesion")['id'], ['2367', '2637', '2673', '2692']) ? '' : 'AND solpagos.idusuario IN (SELECT idusuario FROM usuarios WHERE depto IN ("ADMON MERCADOTECNIA"))';
        }else{
            $where = in_array($this->session->userdata("inicio_sesion")['id'], ['2367', '2637', '2673', '2692']) ? '' : 'AND solpagos.idusuario = ' . $this->session->userdata("inicio_sesion")['id'];
        }   
        /*
        *Fin 
        *@author Efrain Martinez Muñoz 01/08/2024
        *validacion del usuario Anet
        */  
        switch ($this->session->userdata("inicio_sesion")['rol']) {
            case 'SU':
                return $this->db->query("(SELECT 
            solpagos.idsolicitud, 'P PROV' as mov, CONCAT(responsable.nombres, ' ', responsable.apellidos) AS nresonsable, if(solpagos.proyecto is not null, solpagos.proyecto, pd.nombre) as proyecto, solpagos.etapa, solpagos.condominio, empresas.abrev AS nempresa, proveedores.nombre AS nprov, DATE_FORMAT(solpagos.fecelab, '%d/%m/%Y') AS fecha_gasto, DATE_FORMAT(solpagos.fechaCreacion, '%d/%m/%Y') AS fecha_ela, IFNULL(facturas.foliofac, 'NA') AS folio, solpagos.justificacion, solpagos.cantidad, solpagos.metoPago AS metodo_pago, CONCAT(IF(solpagos.prioridad = 1,'PAGO URGENTE',''),' ',IF(solpagos.servicio = 1,'SERVICIO','')) AS observaciones, solpagos.orden_compra, solpagos.homoclave,solpagos.crecibo, os.nombre oficina, tsp.nombre servicioPartida FROM solpagos 
            INNER JOIN usuarios AS responsable ON responsable.idusuario = solpagos.idResponsable 
            INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa 
            INNER JOIN proveedores ON solpagos.idProveedor = proveedores.idproveedor 
            LEFT JOIN (SELECT *, MIN(facturas.feccrea) FROM facturas WHERE facturas.tipo_factura IN (1 , 3) GROUP BY facturas.idsolicitud) AS facturas ON facturas.idsolicitud = solpagos.idsolicitud        
            LEFT JOIN (SELECT facturas.idsolicitud, MAX(facturas.idfactura) AS idfactura, facturas.foliofac FROM facturas WHERE facturas.tipo_factura = 0 GROUP BY facturas.idsolicitud) AS repocision ON solpagos.idsolicitud = repocision.idsolicitud 
            LEFT JOIN solicitud_proyecto_oficina spo  on spo.idsolicitud = solpagos.idsolicitud 
            LEFT JOIN proyectos_departamentos pd  on spo.idProyectos = pd.idProyectos
            LEFT JOIN oficina_sede os on spo.idOficina = os.idOficina 
            left join tipo_servicio_partidas tsp on tsp.idTipoServicioPartida = spo.idTipoServicioPartida 
            WHERE ( solpagos.idetapa IN (1 , 3) OR ( solpagos.idetapa = 2 AND solpagos.rcompra = 1 ) ) AND solpagos.nomdepto = '" . $this->session->userdata("inicio_sesion")['depto'] . "' AND (solpagos.caja_chica != 1 OR solpagos.caja_chica IS NULL) ORDER BY nresonsable ASC , nempresa)");
                break;
            case 'CC':
                return $this->db->query("(SELECT 
            solpagos.idsolicitud, 'P PROV' as mov, CONCAT(responsable.nombres, ' ', responsable.apellidos) AS nresonsable, if(solpagos.proyecto is not null, solpagos.proyecto, pd.nombre) as proyecto, solpagos.etapa, solpagos.condominio, empresas.abrev AS nempresa, proveedores.nombre AS nprov, DATE_FORMAT(solpagos.fecelab, '%d/%m/%Y') AS fecha_gasto, DATE_FORMAT(solpagos.fechaCreacion, '%d/%m/%Y') AS fecha_ela, IFNULL(facturas.foliofac, 'NA') AS folio, solpagos.justificacion, solpagos.cantidad, solpagos.metoPago AS metodo_pago, CONCAT(IF(solpagos.prioridad = 1,'PAGO URGENTE',''),' ',IF(solpagos.servicio = 1,'SERVICIO','')) AS observaciones, solpagos.orden_compra, solpagos.homoclave,solpagos.crecibo, os.nombre oficina, tsp.nombre servicioPartida FROM solpagos 
            INNER JOIN usuarios AS responsable ON responsable.idusuario = solpagos.idResponsable 
            INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa 
            INNER JOIN proveedores ON solpagos.idProveedor = proveedores.idproveedor 
            LEFT JOIN (SELECT *, MIN(facturas.feccrea) FROM facturas WHERE facturas.tipo_factura IN (1 , 3) GROUP BY facturas.idsolicitud) AS facturas ON facturas.idsolicitud = solpagos.idsolicitud        
            LEFT JOIN (SELECT facturas.idsolicitud, MAX(facturas.idfactura) AS idfactura, facturas.foliofac FROM facturas WHERE facturas.tipo_factura = 0 GROUP BY facturas.idsolicitud) AS repocision ON solpagos.idsolicitud = repocision.idsolicitud 
            LEFT JOIN solicitud_proyecto_oficina spo  on spo.idsolicitud = solpagos.idsolicitud 
            LEFT JOIN proyectos_departamentos pd  on spo.idProyectos = pd.idProyectos
            LEFT JOIN oficina_sede os on spo.idOficina = os.idOficina 
            left join tipo_servicio_partidas tsp on tsp.idTipoServicioPartida = spo.idTipoServicioPartida 
            WHERE solpagos.idetapa IN (3) AND solpagos.nomdepto = '" . $this->session->userdata("inicio_sesion")['depto'] . "' AND (solpagos.caja_chica != 1 OR solpagos.caja_chica IS NULL) ORDER BY nresonsable ASC , nempresa)");
                break;
            case 'DA':
            case 'AS':
            case 'CJ':
            case 'CA':
            case 'FP':
            case 'AD':
            case 'CX':
            case 'CP':
            case 'CE':
                return $this->db->query("(SELECT solpagos.idsolicitud, 'P PROV' as mov, CONCAT(responsable.nombres, ' ', responsable.apellidos) AS nresonsable, if(solpagos.proyecto is not null, solpagos.proyecto, pd.nombre) as proyecto, solpagos.etapa, solpagos.condominio, empresas.abrev AS nempresa, proveedores.nombre as nprov, DATE_FORMAT(solpagos.fecelab, '%d/%m/%Y') AS fecha_gasto, DATE_FORMAT(solpagos.fechaCreacion, '%d/%m/%Y') AS fecha_ela, IFNULL(facturas.foliofac, 'NA') AS folio, solpagos.justificacion, solpagos.cantidad, solpagos.metoPago AS metodo_pago, CONCAT(IF(solpagos.prioridad = 1,'PAGO URGENTE',''),' ',IF(solpagos.servicio = 1,'SERVICIO','')) AS observaciones, solpagos.orden_compra,solpagos.homoclave,solpagos.crecibo, os.nombre oficina, tsp.nombre servicioPartida FROM solpagos 
            INNER JOIN    usuarios AS responsable ON responsable.idusuario = solpagos.idResponsable 
            INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa 
            INNER JOIN proveedores ON solpagos.idProveedor = proveedores.idproveedor 
            LEFT JOIN (SELECT *, MIN(facturas.feccrea) FROM facturas WHERE facturas.tipo_factura IN (1 , 3) GROUP BY facturas.idsolicitud) AS facturas ON facturas.idsolicitud = solpagos.idsolicitud        
            LEFT JOIN (SELECT facturas.idsolicitud, MAX(facturas.idfactura) AS idfactura, facturas.foliofac FROM facturas WHERE facturas.tipo_factura = 0 GROUP BY facturas.idsolicitud) AS repocision ON solpagos.idsolicitud = repocision.idsolicitud 
            LEFT JOIN solicitud_proyecto_oficina spo  on spo.idsolicitud = solpagos.idsolicitud 
            LEFT JOIN proyectos_departamentos pd  on spo.idProyectos = pd.idProyectos
            LEFT JOIN oficina_sede os on spo.idOficina = os.idOficina 
            left join tipo_servicio_partidas tsp on tsp.idTipoServicioPartida = spo.idTipoServicioPartida 
            WHERE  ( solpagos.idetapa IN (1 , 3) OR ( solpagos.idetapa = 2 AND solpagos.rcompra = 1 ) ) $where AND (solpagos.caja_chica = 0 OR solpagos.caja_chica IS NULL) ORDER BY nresonsable ASC , nempresa, solpagos.idsolicitud)");
                break;
        }
    }

    //DOCUMENTO RELACION PARA LAS DEVOLUCIONES / TRASPASOS DEPENDIENDO DEL ROL SON LOS DATOS QUE ARROJA EL SISTEMA
    function cajachcon_solauto_2()
    {
        switch ($this->session->userdata("inicio_sesion")['rol']) {
            case 'CC':
                return $this->db->query("(SELECT 
            solpagos.idsolicitud, 'P PROV' as mov, CONCAT(responsable.nombres, ' ', responsable.apellidos) AS nresonsable, solpagos.proyecto, solpagos.etapa, solpagos.condominio, empresas.abrev AS nempresa, proveedores.nombre AS nprov, DATE_FORMAT(solpagos.fecelab, '%d/%m/%Y') AS fecha_gasto, DATE_FORMAT(solpagos.fechaCreacion, '%d/%m/%Y') AS fecha_ela, IFNULL(facturas.foliofac, 'NA') AS folio, solpagos.justificacion, solpagos.cantidad, solpagos.metoPago AS metodo_pago, CONCAT(IF(solpagos.prioridad = 1,'PAGO URGENTE',''),' ',IF(solpagos.servicio = 1,'SERVICIO','')) AS observaciones, solpagos.orden_compra, proveedores.cuenta,bancos.nombre as banco FROM solpagos 
            INNER JOIN usuarios AS responsable ON responsable.idusuario = solpagos.idResponsable 
            INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa 
            INNER JOIN proveedores ON solpagos.idProveedor = proveedores.idproveedor 
            left join bancos on bancos.idbanco=proveedores.idbanco 
            LEFT JOIN (SELECT *, MIN(facturas.feccrea) FROM facturas WHERE facturas.tipo_factura IN (1 , 3) GROUP BY facturas.idsolicitud) AS facturas ON facturas.idsolicitud = solpagos.idsolicitud        
            LEFT JOIN (SELECT facturas.idsolicitud, MAX(facturas.idfactura) AS idfactura, facturas.foliofac FROM facturas WHERE facturas.tipo_factura = 0 GROUP BY facturas.idsolicitud) AS repocision ON solpagos.idsolicitud = repocision.idsolicitud 
            WHERE solpagos.idetapa IN ( 3 ) AND solpagos.nomdepto IN ( 'DEVOLUCIONES', 'TRASPASOS' ) AND (solpagos.caja_chica != 1 OR solpagos.caja_chica IS NULL) ORDER BY nresonsable ASC , nempresa)");
                break;
            case 'FP':
            case 'AD':
                return $this->db->query("(SELECT solpagos.idsolicitud, 'P PROV' as mov, CONCAT(responsable.nombres, ' ', responsable.apellidos) AS nresonsable, solpagos.proyecto, solpagos.etapa, solpagos.condominio, empresas.abrev AS nempresa, proveedores.nombre as nprov, DATE_FORMAT(solpagos.fecelab, '%d/%m/%Y') AS fecha_gasto, DATE_FORMAT(solpagos.fechaCreacion, '%d/%m/%Y') AS fecha_ela, IFNULL(facturas.foliofac, 'NA') AS folio, solpagos.justificacion, solpagos.cantidad, solpagos.metoPago AS metodo_pago, CONCAT(IF(solpagos.prioridad = 1,'PAGO URGENTE',''),' ',IF(solpagos.servicio = 1,'SERVICIO','')) AS observaciones,solpagos.orden_compra,proveedores.cuenta,bancos.nombre as banco FROM solpagos 
            INNER JOIN    usuarios AS responsable ON responsable.idusuario = solpagos.idResponsable 
            INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa 
            INNER JOIN proveedores ON solpagos.idProveedor = proveedores.idproveedor
            left join bancos on bancos.idbanco= proveedores.idbanco 
            LEFT JOIN (SELECT *, MIN(facturas.feccrea) FROM facturas WHERE facturas.tipo_factura IN (1 , 3) GROUP BY facturas.idsolicitud) AS facturas ON facturas.idsolicitud = solpagos.idsolicitud        
            LEFT JOIN (SELECT facturas.idsolicitud, MAX(facturas.idfactura) AS idfactura, facturas.foliofac FROM facturas WHERE facturas.tipo_factura = 0 GROUP BY facturas.idsolicitud) AS repocision ON solpagos.idsolicitud = repocision.idsolicitud 
            WHERE solpagos.idetapa IN (1 , 3) AND solpagos.idusuario = '" . $this->session->userdata("inicio_sesion")['id'] . "' AND (solpagos.caja_chica != 1 OR solpagos.caja_chica IS NULL) ORDER BY nresonsable ASC , nempresa)");
                break;
            case 'CX':
            case 'CE':
                return $this->db->query("(SELECT 
            solpagos.idsolicitud, 'P PROV' as mov, CONCAT(responsable.nombres, ' ', responsable.apellidos) AS nresonsable, solpagos.proyecto, solpagos.etapa, solpagos.condominio, empresas.abrev AS nempresa, proveedores.nombre AS nprov, DATE_FORMAT(solpagos.fecelab, '%d/%m/%Y') AS fecha_gasto, DATE_FORMAT(solpagos.fechaCreacion, '%d/%m/%Y') AS fecha_ela, IFNULL(facturas.foliofac, 'NA') AS folio, solpagos.justificacion, solpagos.cantidad, solpagos.metoPago AS metodo_pago, CONCAT(IF(solpagos.prioridad = 1,'PAGO URGENTE',''),' ',IF(solpagos.servicio = 1,'SERVICIO','')) AS observaciones, solpagos.orden_compra, proveedores.cuenta,bancos.nombre as banco FROM solpagos 
            INNER JOIN usuarios AS responsable ON responsable.idusuario = solpagos.idResponsable 
            INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa 
            INNER JOIN proveedores ON solpagos.idProveedor = proveedores.idproveedor 
            left join bancos on bancos.idbanco=proveedores.idbanco 
            LEFT JOIN (SELECT *, MIN(facturas.feccrea) FROM facturas WHERE facturas.tipo_factura IN (1 , 3) GROUP BY facturas.idsolicitud) AS facturas ON facturas.idsolicitud = solpagos.idsolicitud        
            LEFT JOIN (SELECT facturas.idsolicitud, MAX(facturas.idfactura) AS idfactura, facturas.foliofac FROM facturas WHERE facturas.tipo_factura = 0 GROUP BY facturas.idsolicitud) AS repocision ON solpagos.idsolicitud = repocision.idsolicitud 
            WHERE solpagos.idetapa IN (3) AND solpagos.idAutoriza = '" . $this->session->userdata("inicio_sesion")['id'] . "' AND (solpagos.caja_chica != 1 OR solpagos.caja_chica IS NULL) ORDER BY nresonsable ASC , nempresa)");
                break;
        }
    }

    //OBTENEMOS EL LISTADO DE LOS RESPONSABLES DE LAs TARJETAS DE CREDITO
    function responsable_TDC()
    {
        //Inicio
        if($this->session->userdata("inicio_sesion")['id']==2876){
            $where = in_array($this->session->userdata("inicio_sesion")['id'], ['2367', '2637', '2673', '2692']) ? '' : 'AND solpagos.idusuario IN (SELECT idusuario FROM usuarios WHERE depto IN ("ADMON MERCADOTECNIA"))';
        }else{
            $where = in_array($this->session->userdata("inicio_sesion")['id'], ['2367', '2637', '2673', '2692']) ? '' : 'AND solpagos.idusuario = ' . $this->session->userdata("inicio_sesion")['id'];
        }
        /*
        *Fin 
        *@author Efrain Martinez Muñoz 01/08/2024
        *validacion del usuario Anet
        */ 
        switch ($this->session->userdata("inicio_sesion")['rol']) {
            case 'DA':
            case 'AS':
                return $this->db->query("SELECT solpagos.idsolicitud, CONCAT( responsable.nombre_completo, ' TDC' ) AS nresonsable, 
                SUM(solpagos.cantidad) as total 
                FROM solpagos 
                INNER JOIN lista_rtdc responsable ON responsable.idtcredito = solpagos.idResponsable 
                WHERE solpagos.idetapa IN ( 1, 3 ) AND solpagos.caja_chica = 2 AND solpagos.nomdepto = '" . $this->session->userdata("inicio_sesion")['depto'] . "'
                GROUP BY CONCAT( responsable.nombre_completo, ' TDC' )");
            case 'CJ':
            case 'CA':
            case 'FP':
            case 'AD':
            case 'CX':
            case 'CP':
                return $this->db->query("SELECT solpagos.idsolicitud, CONCAT( responsable.nombre_completo, ' TDC' ) AS nresonsable, 
                SUM(solpagos.cantidad) as total 
                FROM solpagos 
                INNER JOIN lista_rtdc responsable ON responsable.idtcredito = solpagos.idResponsable 
                WHERE solpagos.idetapa IN ( 1, 3 ) AND solpagos.caja_chica = 2 $where
                GROUP BY CONCAT( responsable.nombre_completo, ' TDC' )");
                break;
        }
    }

    //OBTENEMOS EL LISTADO DE LOS RESPONSABLES DE LA CAJA CHICA
    function responsable_cajach()
    {
        //Inicio
        if($this->session->userdata("inicio_sesion")['id']==2876){
            $where = in_array($this->session->userdata("inicio_sesion")['id'], ['2367', '2637', '2673', '2692']) ? '' : 'AND solpagos.idusuario IN (SELECT idusuario FROM usuarios WHERE depto IN ("ADMON MERCADOTECNIA"))';
        }else{
            $where = in_array($this->session->userdata("inicio_sesion")['id'], ['2367', '2637', '2673', '2692']) ? '' : 'AND solpagos.idusuario = ' . $this->session->userdata("inicio_sesion")['id'];
        } 
        /*
        *Fin 
        *@author Efrain Martinez Muñoz 01/08/2024
        *validacion del usuario Anet
        */
        switch ($this->session->userdata("inicio_sesion")['rol']) {
            case 'SU':
            case 'CC':
                return $this->db->query("SELECT solpagos.idsolicitud, 
                                                CONCAT( responsable.nombres,' ', responsable.apellidos ) AS nresonsable,
												SUM(solpagos.cantidad) as total 
                                         FROM solpagos 
                                         INNER JOIN usuarios AS responsable 
                                            ON responsable.idusuario = solpagos.idResponsable
                                        INNER JOIN empresas 
                                            ON empresas.idempresa = solpagos.idEmpresa 
                                        INNER JOIN proveedores 
                                            ON solpagos.idProveedor = proveedores.idproveedor
                                        LEFT JOIN ( SELECT *, MIN(facturas.feccrea) 
                                                    FROM facturas
                                                    WHERE facturas.tipo_factura IN ( 1, 3 ) 
                                                    GROUP BY facturas.idsolicitud) AS facturas 
                                            ON facturas.idsolicitud = solpagos.idsolicitud 
                                        WHERE solpagos.idetapa IN (1,3) AND solpagos.caja_chica = 1 AND solpagos.nomdepto = '" . $this->session->userdata("inicio_sesion")['depto'] . "'
                                        UNION
                                        SELECT  solpagos.idsolicitud,
												CONCAT( responsable.nombres,' ', responsable.apellidos, ' TDC' ) AS nresonsable,
                                                SUM(solpagos.cantidad) as total 
                                        FROM solpagos 
                                        INNER JOIN usuarios AS responsable 
                                            ON responsable.idusuario = solpagos.idResponsable 
                                        INNER JOIN empresas 
                                            ON empresas.idempresa = solpagos.idEmpresa
                                        INNER JOIN proveedores 
                                            ON solpagos.idProveedor = proveedores.idproveedor 
                                        LEFT JOIN ( SELECT *, MIN(facturas.feccrea) 
                                                    FROM facturas 
                                                    WHERE facturas.tipo_factura IN ( 1, 3 ) 
                                                    GROUP BY facturas.idsolicitud) AS facturas 
                                            ON facturas.idsolicitud = solpagos.idsolicitud
                                        WHERE solpagos.idetapa IN (1,3) AND solpagos.caja_chica = 2 AND solpagos.nomdepto = '" . $this->session->userdata("inicio_sesion")['depto'] . "'");
                break;
            case 'DA':
            case 'AS':
                return $this->db->query("SELECT solpagos.idsolicitud, 
                                                CONCAT( responsable.nombres,' ', responsable.apellidos ) AS nresonsable,
                                                SUM(solpagos.cantidad) as total 
                                        FROM solpagos 
                                        INNER JOIN usuarios AS responsable 
                                            ON responsable.idusuario = solpagos.idResponsable 
                                        INNER JOIN empresas 
                                            ON empresas.idempresa = solpagos.idEmpresa 
                                        INNER JOIN proveedores 
                                            ON solpagos.idProveedor = proveedores.idproveedor 
                                        LEFT JOIN (SELECT *, MIN(facturas.feccrea) FROM facturas WHERE facturas.tipo_factura IN ( 1, 3 ) GROUP BY facturas.idsolicitud) AS facturas 
                                            ON facturas.idsolicitud = solpagos.idsolicitud 
                                        WHERE solpagos.idetapa IN ( 1, 3) AND solpagos.caja_chica = 1 AND solpagos.nomdepto = '" . $this->session->userdata("inicio_sesion")['depto'] . "' 
                                        GROUP BY CONCAT( responsable.nombres,' ', responsable.apellidos )");
            case 'CJ':
            case 'CA':
            case 'FP':
            case 'AD':
            case 'CX':
            case 'CP':
                return $this->db->query("SELECT solpagos.idsolicitud, CONCAT( responsable.nombres,' ', responsable.apellidos ) AS nresonsable, SUM(solpagos.cantidad) as total FROM solpagos INNER JOIN usuarios AS responsable ON responsable.idusuario = solpagos.idResponsable INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa INNER JOIN proveedores ON solpagos.idProveedor = proveedores.idproveedor LEFT JOIN (SELECT *, MIN(facturas.feccrea) FROM facturas WHERE facturas.tipo_factura IN ( 1, 3 ) GROUP BY facturas.idsolicitud) AS facturas ON facturas.idsolicitud = solpagos.idsolicitud 
                WHERE solpagos.idetapa IN (1,3) AND solpagos.caja_chica = 1 $where
                GROUP BY CONCAT( responsable.nombres,' ', responsable.apellidos )");
                break;
            case 'CE':
                return $this->db->query("SELECT solpagos.idsolicitud, CONCAT( responsable.nombres,' ', responsable.apellidos ) AS nresonsable, SUM(solpagos.cantidad) as total FROM solpagos INNER JOIN usuarios AS responsable ON responsable.idusuario = solpagos.idResponsable INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa INNER JOIN proveedores ON solpagos.idProveedor = proveedores.idproveedor LEFT JOIN (SELECT *, MIN(facturas.feccrea) FROM facturas WHERE facturas.tipo_factura IN ( 1, 3 ) GROUP BY facturas.idsolicitud) AS facturas ON facturas.idsolicitud = solpagos.idsolicitud WHERE solpagos.idetapa IN (3) AND solpagos.caja_chica = 1 AND solpagos.idAutoriza = '" . $this->session->userdata("inicio_sesion")['id'] . "' GROUP BY CONCAT( responsable.nombres,' ', responsable.apellidos )
                UNION
                SELECT solpagos.idsolicitud, CONCAT( responsable.nombres,' ', responsable.apellidos, ' TDC' ) AS nresonsable, SUM(solpagos.cantidad) as total FROM solpagos INNER JOIN usuarios AS responsable ON responsable.idusuario = solpagos.idResponsable INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa INNER JOIN proveedores ON solpagos.idProveedor = proveedores.idproveedor LEFT JOIN (SELECT *, MIN(facturas.feccrea) FROM facturas WHERE facturas.tipo_factura IN ( 1, 3 ) GROUP BY facturas.idsolicitud) AS facturas ON facturas.idsolicitud = solpagos.idsolicitud WHERE solpagos.idetapa IN (3) AND solpagos.caja_chica = 2 AND solpagos.idAutoriza = '" . $this->session->userdata("inicio_sesion")['id'] . "' GROUP BY CONCAT( responsable.nombres,' ', responsable.apellidos )");
                break;
        }
    }

    function agregar_comentario_especial($data)
    {
        return $this->db->insert("comentario_especial", $data);
    }

    function get_comentario_especial($idsolicitud)
    {
        return $this->db->query("SELECT *, DATE_FORMAT( comentario_especial.fecha, '%H:%i:%s %d-%m-%Y' ) AS fecha_formateada, CONCAT( usuarios.nombres,' ',usuarios.apellidos ) AS nombre_usuario FROM comentario_especial INNER JOIN usuarios ON usuarios.idusuario = comentario_especial.idusario WHERE comentario_especial.idsolicitud = '$idsolicitud'");
    }

    function autorizar_viaticos()
    {
        if($this->session->userdata("inicio_sesion")['id']==2876){
            $where = in_array($this->session->userdata("inicio_sesion")['id'], ['2367', '2637', '2673', '2692']) ? '' : 'AND solpagos.idusuario IN (SELECT idusuario FROM usuarios WHERE depto IN ("ADMON MERCADOTECNIA"))';
        }else{
            $where = in_array($this->session->userdata("inicio_sesion")['id'], ['2367', '2637', '2673', '2692']) ? '' : 'AND solpagos.idusuario = ' . $this->session->userdata("inicio_sesion")['id'];
        } 
        //$where = in_array($this->session->userdata("inicio_sesion")['id'], ['2367', '2637']) ? '' : 'AND solpagos.idusuario = ' . $this->session->userdata("inicio_sesion")['id'];
        switch ($this->session->userdata("inicio_sesion")['rol']) {
            case 'DA':
            case 'SU':
            case 'CC':
                return $this->db->query("SELECT 
                                solpagos.idsolicitud, 
                                'VIÁTICOS' as mov, 
                                CONCAT(responsable.nombres, ' ', responsable.apellidos) AS nresonsable, 
                                if(spo.idProyectos is null, solpagos.proyecto, pd.nombre) as proyecto, 
                                solpagos.etapa, 
                                solpagos.condominio, 
                                empresas.abrev AS nempresa, 
                                proveedores.nombre as nprov, 
                                DATE_FORMAT(solpagos.fecelab, '%d/%m/%Y') AS fecha_gasto, 
                                DATE_FORMAT(solpagos.fechaCreacion, '%d/%m/%Y') AS fecha_ela, 
                                IFNULL(facturas.foliofac, 'NA') AS folio, 
                                solpagos.justificacion, 
                                solpagos.cantidad,
                                solpagos.metoPago AS metodo_pago, 
                                CONCAT(IF(solpagos.prioridad = 1,'PAGO URGENTE',''),' ',IF(solpagos.servicio = 1,'SERVICIO','')) AS observaciones, 
                                solpagos.orden_compra,
                                solpagos.homoclave,
                                solpagos.crecibo,
                                os.nombre oficina,
                                tsp.nombre servicioPartida  
                            FROM solpagos 
                            INNER JOIN usuarios AS responsable ON responsable.idusuario = solpagos.idResponsable 
                            INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa 
                            INNER JOIN proveedores ON solpagos.idProveedor = proveedores.idproveedor
                            LEFT JOIN (
                            SELECT *
                                , MIN(facturas.feccrea)
                            FROM facturas
                            WHERE facturas.tipo_factura IN ( 1, 3 )
                            GROUP BY facturas.idsolicitud
                            ) AS facturas ON facturas.idsolicitud = solpagos.idsolicitud
                            left join solicitud_proyecto_oficina spo  on spo.idsolicitud = solpagos.idsolicitud 
                            left join proyectos_departamentos pd  on spo.idProyectos = pd.idProyectos
                            LEFT JOIN oficina_sede os on spo.idOficina = os.idOficina 
                            left join tipo_servicio_partidas tsp on tsp.idTipoServicioPartida = spo.idTipoServicioPartida 
                            WHERE solpagos.idetapa IN ( 1, 3 )
                            AND solpagos.caja_chica = 4
                            AND solpagos.nomdepto = '".$this->session->userdata("inicio_sesion")['depto']."'
                            AND SUBDATE(solpagos.fecelab, 7) <= NOW() ORDER BY solpagos.idsolicitud");
                break;
            case 'AS':
            case 'CJ':
            case 'CA':
            case 'AD':
            case 'CP':
            case 'FP':
                return $this->db->query("SELECT
                                solpagos.idsolicitud, 
                                'VIÁTICOS' as mov, 
                                CONCAT(responsable.nombres, ' ', responsable.apellidos) AS nresonsable, 
                                if(spo.idProyectos is null, solpagos.proyecto, pd.nombre) as proyecto, 
                                solpagos.etapa, 
                                solpagos.condominio, 
                                empresas.abrev AS nempresa, 
                                proveedores.nombre as nprov, 
                                DATE_FORMAT(solpagos.fecelab, '%d/%m/%Y') AS fecha_gasto, 
                                DATE_FORMAT(solpagos.fechaCreacion, '%d/%m/%Y') AS fecha_ela, 
                                IFNULL(facturas.foliofac, 'NA') AS folio, 
                                solpagos.justificacion, 
                                solpagos.cantidad,
                                solpagos.metoPago AS metodo_pago, 
                                CONCAT(IF(solpagos.prioridad = 1,'PAGO URGENTE',''),' ',IF(solpagos.servicio = 1,'SERVICIO','')) AS observaciones, 
                                solpagos.orden_compra,
                                solpagos.homoclave,
                                solpagos.crecibo,
                                os.nombre oficina,
                                tsp.nombre servicioPartida  
                            FROM solpagos 
                            INNER JOIN usuarios AS responsable ON responsable.idusuario = solpagos.idResponsable 
                            INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa 
                            INNER JOIN proveedores ON solpagos.idProveedor = proveedores.idproveedor
                            LEFT JOIN (
                            SELECT *
                                , MIN(facturas.feccrea)
                            FROM facturas
                            WHERE facturas.tipo_factura IN (
                                    1
                                    , 3
                                    )
                            GROUP BY facturas.idsolicitud
                            ) AS facturas ON facturas.idsolicitud = solpagos.idsolicitud 
                            left join solicitud_proyecto_oficina spo  on spo.idsolicitud = solpagos.idsolicitud 
                            left join proyectos_departamentos pd  on spo.idProyectos = pd.idProyectos
                            LEFT JOIN oficina_sede os on spo.idOficina = os.idOficina 
                            left join tipo_servicio_partidas tsp on tsp.idTipoServicioPartida = spo.idTipoServicioPartida 
                            WHERE solpagos.idetapa IN ( 1, 3 )
                            AND solpagos.caja_chica = 4 
                            $where");
                            break;
        }
        
        
    }

    function xmlDescarga($idsolicitud) {
        return($this->db->query("SELECT * 
                                 FROM facturas 
                                 WHERE idfactura IN (SELECT idfactura FROM sol_factura WHERE idsolicitud = ?)", [$idsolicitud]));
    }
}
