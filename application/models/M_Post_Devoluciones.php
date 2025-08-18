<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class M_Post_Devoluciones extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    } 

    function docs_proceso_sol_avanzar($idsolicitud, $idrol)
    {  
        return $this->db->query("SELECT 
                                    s.idsolicitud, 
                                    s.idproceso,
                                    SUM( IF(dproc.requerido = 1 AND iddocumentos_solicitud IS NULL, 1, 0 ) ) requerido
                                FROM ( select p.idpost_dev idproceso, sl.idsolicitud from proceso p 
                                        JOIN (SELECT * FROM solpagos WHERE idsolicitud = ?) sl ON p.idproceso = sl.idproceso
                                        where idpost_dev IS NOT NULL ) s
                                LEFT JOIN ( SELECT idproceso, iddocumentos, requerido, rol FROM documentos_proceso WHERE FIND_IN_SET( '".$this->session->userdata("inicio_sesion")['rol']."', rol) ) dproc ON dproc.idproceso = s.idproceso 
                                LEFT JOIN ( SELECT iddocumentos_solicitud, iddocumento, idsolicitud 
                                                FROM documentos_solicitud ds WHERE ds.estatus = 1 AND ds.idsolicitud = ? ) docsol ON docsol.idsolicitud = s.idsolicitud AND docsol.iddocumento = dproc.iddocumentos    
                                GROUP BY s.idsolicitud, s.idproceso", [ $idsolicitud, $idsolicitud ]);
    }


    function get_solicitudes_autorizadas_area()
    {    
            $condicion = " SELECT  s.idsolicitud, s.proyecto, s.homoclave, s.etapa, s.condominio, s.folio, s.idEmpresa, s.idResponsable, s.idProveedor, s.idusuario,  s.nomdepto, s.tendrafac, 0 prioridad , s.caja_chica, s.servicio, s.justificacion, s.cantidad, s.descuento,
            s.moneda, s.metoPago, s.dfacturaje, s.fecha_fin, s.fecelab, pe.idetapa, s.fechaCreacion, s.idAutoriza, s.fecha_autorizacion, s.ref_bancaria, s.programado, s.intereses, s.orden_compra,
            s.crecibo, s.rcompra, s.requisicion, s.idproceso, s.Api
        FROM solpagos s
                        JOIN ( select idpost_dev, idproceso from proceso where idpost_dev IS NOT NULL ) p ON p.idproceso = s.idproceso AND s.idetapa IN ( 11 )
                        JOIN ( SELECT idproceso, idetapa FROM proceso_etapa WHERE FIND_IN_SET( '".$this->session->userdata("inicio_sesion")['rol']."', idrol) AND depto IS NULL AND single = 0 AND orden = 1  ) pe ON pe.idproceso = p.idpost_dev
                        LEFT JOIN post_devoluciones pd ON pd.idsolicitud = s.idsolicitud
                        WHERE pd.idsolicitud IS NULL
                        UNION
                        Select psol.idsolicitud, s.proyecto, s.homoclave, s.etapa, s.condominio, s.folio, s.idEmpresa, s.idResponsable, s.idProveedor, psol.idmodifica, s.nomdepto, s.tendrafac, psol.prioridad, s.caja_chica, s.servicio, s.justificacion, s.cantidad, s.descuento,
                        s.moneda, s.metoPago, s.dfacturaje, s.fecha_fin, s.fecelab, psol.idetapa, s.fechaCreacion, s.idAutoriza, s.fecha_autorizacion, s.ref_bancaria, s.programado, s.intereses, s.orden_compra,
                        s.crecibo, s.rcompra, s.requisicion, psol.idproceso, s.Api from solpagos s 
                        INNER  JOIN (
                        SELECT ps.* FROM post_devoluciones ps
                        JOIN ( SELECT idproceso, idetapa FROM proceso_etapa WHERE FIND_IN_SET( '".$this->session->userdata("inicio_sesion")['rol']."', idrol) AND depto IS NULL AND single = 0  ) pe ON pe.idproceso = ps.idproceso AND pe.idetapa = ps.idetapa 
                        )
                        psol ON psol.idsolicitud = s.idsolicitud";

            return $this->db->query("SELECT 
                solpagos.idproceso, 
                solpagos.folio, 
                solpagos.crecibo, solpagos.requisicion, solpagos.orden_compra, solpagos.homoclave, solpagos.metoPago, 
                solpagos.prioridad, 
                solpagos.nomdepto, 
                solpagos.prioridad, 
                solpagos.condominio, 
                solpagos.idAutoriza, 
                solpagos.proyecto, 
                solpagos.fecha_autorizacion, 
                solpagos.idsolicitud, 
                solpagos.justificacion, 
                proveedores.nombre, 
                proveedores.tipo_prov, 
                solpagos.cantidad,
                DATE_FORMAT(solpagos.fecelab, '%d/%m/%Y') AS fecelab, 
                solpagos.idetapa, 
                etapas.nombre AS etapa, 
                capturista.nombre_completo, 
                solpagos.idusuario,
                solpagos.caja_chica, 
                solpagos.servicio, 
                1 AS visto, 
                solpagos.moneda, 
                proceso.nombre AS nombre_proceso, 
                empresas.abrev AS nempresa
            FROM
                (
                    $condicion 
                ) solpagos 
            CROSS JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor
            CROSS JOIN listado_usuarios capturista ON capturista.idusuario = solpagos.idusuario
            CROSS JOIN empresas ON empresas.idempresa = solpagos.idEmpresa
            CROSS JOIN proceso proceso ON proceso.idproceso = solpagos.idproceso
            CROSS JOIN etapas ON etapas.idetapa = solpagos.idetapa
            ORDER BY FIELD(solpagos.idetapa, 1, 8, 21, 3, 30) , solpagos.fechaCreacion");
    }

    function avanzar_insert($idsolicitud){
        $idusuario = $this->session->userdata("inicio_sesion")['id'];
        $rol_usuario = $this->session->userdata("inicio_sesion")['rol'];
        $fecha = date("Y-m-d H:i:s");

        return $this->db->query("INSERT INTO post_devoluciones(idsolicitud, idmodifica, idetapa, idproceso, fecha_modifica)
                                SELECT $idsolicitud,$idusuario,MIN(pe2.sig_etapa) sig_etapa, pe2.idproceso, '$fecha' FROM  (
                                SELECT pe.idproceso, pe.idetapa, pe.orden, pe.orden + 1 sig_orden from solpagos s
                                inner join ( select idpost_dev, idproceso from proceso where idpost_dev IS NOT NULL ) p ON p.idproceso = s.idproceso
                                INNER JOIN proceso_etapa pe ON pe.idproceso = p.idpost_dev AND FIND_IN_SET( '$rol_usuario', idrol)
                                WHERE s.idsolicitud = $idsolicitud) sol
                                INNER JOIN ( SELECT idproceso, orden sig_orden, idetapa sig_etapa FROM proceso_etapa ) pe2 ON pe2.idproceso = sol.idproceso AND pe2.sig_orden = sol.sig_orden");

    }

    function avanzar_update($idsolicitud){
        $idusuario = $this->session->userdata("inicio_sesion")['id'];
        $rol_usuario = $this->session->userdata("inicio_sesion")['rol'];
        $fecha = date("Y-m-d H:i:s");

        return $this->db->query("UPDATE post_devoluciones sp
                                INNER JOIN 
                                (   SELECT 
                                        pe.idproceso,
                                        pe.idetapa,
                                        pe.orden,
                                        pe.orden + 1 sig_orden
                                    FROM post_devoluciones s
                                    INNER JOIN proceso_etapa pe ON s.idproceso = pe.idproceso AND s.idetapa = pe.idetapa AND FIND_IN_SET( '$rol_usuario', idrol)
                                    WHERE s.idsolicitud = $idsolicitud
                                ) pe ON pe.idproceso = sp.idproceso AND sp.idetapa = sp.idetapa
                                INNER JOIN ( SELECT idproceso, orden sig_orden, idetapa sig_etapa FROM proceso_etapa ) pe2 ON pe2.idproceso = sp.idproceso AND pe2.sig_orden = pe.sig_orden
                                SET sp.idetapa = pe2.sig_etapa, fecha_modifica = '$fecha', idmodifica = $idusuario 
                                WHERE sp.idsolicitud IN ($idsolicitud) AND sp.idproceso IS NOT NULL ");

    }

    function actualizar_etapa($etapa, $idsolicitud, $prioridad){
        return $this->db->update("post_devoluciones", array(
            "idetapa" => $etapa,
            "prioridad" => $prioridad,
            "fecha_modifica" => date("Y-m-d H:i:s"),
            "idmodifica" => $this->session->userdata("inicio_sesion")['id'],
        ),  "idsolicitud = '$idsolicitud'");
    }

    function proceso_etapas_menor($idproceso, $etapa)
    {
        return $this->db->query("SELECT pe.idrol, pe.orden, UPPER(e.nombre) AS etapa_regresar, e.idetapa FROM proceso p 
        INNER JOIN proceso_etapa pe ON pe.idproceso = p.idproceso
        INNER JOIN etapas e ON e.idetapa = pe.idetapa INNER JOIN roles r ON FIND_IN_SET(r.idrol,pe.idrol) WHERE p.idproceso = $idproceso 
        AND pe.orden < (SELECT orden FROM proceso_etapa where idproceso = $idproceso and idetapa = $etapa) GROUP BY e.idetapa");
    }

    function docs_proceso_sol($idsolicitud)
    {
        return $this->db->query(" SELECT *, IF( (dp.requerido = 1 AND dp.rol = '".$this->session->userdata('inicio_sesion')['rol']."' ) OR (dp.fpago = sp.metoPago AND dp.rol = '".$this->session->userdata('inicio_sesion')['rol']."' ) , '*', '') AS doc_req  
        FROM solpagos sp
        JOIN ( select idpost_dev, idproceso from proceso where idpost_dev IS NOT NULL ) p ON p.idproceso = sp.idproceso 
        INNER JOIN documentos_proceso dp ON dp.idproceso = p.idpost_dev
        INNER JOIN documentos d ON d.iddocumentos = dp.iddocumentos  
        INNER JOIN proveedores ON sp.idProveedor = proveedores.idproveedor
        WHERE idsolicitud = '$idsolicitud' 
        AND dp.iddocumentos NOT IN ( SELECT iddocumento FROM documentos_solicitud ds WHERE ds.idsolicitud = '$idsolicitud' AND ds.estatus = 1);");
    }

    function ruta_doc_dropbox($idsolicitud){
        return $this->db->query("SELECT * FROM documentos_solicitud
                                INNER JOIN documentos ON documentos_solicitud.iddocumento = documentos.iddocumentos 
                                LEFT JOIN (SELECT dp.idproceso,dp.iddocumentos, dp.rol, IF( (dp.requerido = 1 AND dp.rol = '".$this->session->userdata('inicio_sesion')['rol']."' ) OR (dp.fpago = sp.metoPago AND dp.rol = '".$this->session->userdata('inicio_sesion')['rol']."' ) , '*', '') AS doc_req  
                                        FROM solpagos sp
                                        JOIN ( select idpost_dev, idproceso from proceso where idpost_dev IS NOT NULL ) p ON p.idproceso = sp.idproceso 
                                        INNER JOIN documentos_proceso dp ON dp.idproceso = p.idpost_dev
                                        WHERE idsolicitud = '$idsolicitud' ) docp ON docp.iddocumentos = documentos_solicitud.iddocumento
                                WHERE idsolicitud = '$idsolicitud' AND estatus = 1     ");
    }

    function get_info_docs($idsolicitud)
    {
       
        return $this->db->query("SELECT sol.idsolicitud, sol.idproceso, sol.proyecto, proveedores.rfc, proveedores.nombre, proveedores.alias, d.iddocumentos, d.ndocumento   FROM proceso p
        INNER JOIN solpagos sol ON p.idproceso = sol.idproceso
        INNER JOIN documentos_proceso dp ON  dp.idproceso = p.idpost_dev
        INNER JOIN  documentos d ON d.iddocumentos = dp.iddocumentos 
        INNER JOIN proveedores ON sol.idProveedor = proveedores.idproveedor
        WHERE idsolicitud ='$idsolicitud'");
    }

    function validar_solpagos_estatus( $idrol, $idsolicitud ){
        $data = [ $idrol, $idsolicitud ];
        return $this->db->query("SELECT idsolicitud from (SELECT * FROM solpagos WHERE idsolicitud = $idsolicitud) sp
                                JOIN(SELECT * from proceso where idpost_dev is not null) p ON p.idproceso = sp.idproceso   
                                INNER JOIN  proceso_etapa pe on pe.idproceso = p.idpost_dev AND FIND_IN_SET( '$idrol', pe.idrol ) AND pe.orden = 1
                                UNION
                                SELECT idsolicitud FROM post_devoluciones s
                                JOIN ( SELECT idetapa, idproceso FROM proceso_etapa WHERE FIND_IN_SET('$idrol', idrol ) ) ep ON ep.idetapa = s.idetapa AND s.idproceso = ep.idproceso AND s.idsolicitud = $idsolicitud");
    }

    function get_solicitudes_en_curso($finicial, $ffinal){
        return $this->db->query("SELECT
                                s.idsolicitud,
                                s.prioridad,
                                s.condominio,
                                s.etapa,
                                s.justificacion,
                                s.homoclave cuenta_contable,
                                s.orden_compra cuenta_orden,
                                pr.nombre nproceso,
                                empresas.abrev, 
                                s.proyecto, 
                                proveedores.nombre, 
                                DATE(s.fecelab) AS fecelab, 
                                capturista.nombre_completo, 
                                IF(s.programado IS NOT NULL, s.cantidad + IFNULL(cant_pag.pagado, 0),  s.cantidad) AS cantidad, 
                                s.moneda, 
                                IFNULL(cant_pag.pagado, 0) AS pagado, 
                                etapas.nombre etapa_estatus, 
                                IFNULL(DATE(s.fecha_autorizacion), '--') AS fecha_autorizacion, 
                                s.folio,
                                s.fechaCreacion,
                                aut.nombre_completo nautoriza,
                                msd.motivo, 
                                msd.costo_lote, 
                                msd.superficie, 
                                msd.preciom, 
                                msd.predial, 
                                msd.por_penalizacion, 
                                msd.penalizacion, 
                                msd.importe_aportado, 
                                msd.mantenimiento,
                                1 visto,
                                IFNULL(diasTrans,0) AS diasTrans, 
                                IFNULL(numCancelados , 0) AS numCancelados
                                FROM (
                                        SELECT pd.*, sol.nomdepto, sol.condominio, sol.etapa, sol.justificacion, sol.homoclave, sol.orden_compra, sol.proyecto,
                                                    sol.moneda, sol.folio, sol.fecha_autorizacion, sol.fechaCreacion, sol.fecelab, sol.programado, sol.cantidad, sol.idautoriza,
                                                    sol.idProveedor, sol.idEmpresa, sol.idusuario
                                            FROM  post_devoluciones pd 
                                            LEFT JOIN (SELECT * FROM solpagos WHERE fechaCreacion BETWEEN '$finicial' AND '$ffinal' ) sol ON  sol.idsolicitud = pd.idsolicitud
                                            JOIN ( SELECT departamento FROM departamentos 
                                                    INNER JOIN usuario_depto ON iddepartamentos = iddepartamento 
                                                    WHERE usuario_depto.idusuario = ? ) depto ON depto.departamento = sol.nomdepto 
                                            WHERE pd.idproceso IS NOT NULL AND pd.idetapa NOT IN ( select idetapa from proceso_etapa where idproceso = pd.idproceso AND orden  IN(select MAX(orden) from proceso_etapa where idproceso=pd.idproceso ) )
                                            
                                        UNION 
                                            
                                            SELECT pd.*, sol.nomdepto, sol.condominio, sol.etapa, sol.justificacion, sol.homoclave, sol.orden_compra, sol.proyecto,
                                                    sol.moneda, sol.folio, sol.fecha_autorizacion, sol.fechaCreacion, sol.fecelab, sol.programado, sol.cantidad, sol.idautoriza,
                                                    sol.idProveedor, sol.idEmpresa, sol.idusuario
                                            FROM  post_devoluciones pd
                                            LEFT JOIN (SELECT * FROM solpagos WHERE fechaCreacion BETWEEN '$finicial' AND '$ffinal' ) sol ON  sol.idsolicitud = pd.idsolicitud
                                            JOIN ( SELECT departamento FROM departamentos 
                                                    INNER JOIN usuario_depto ON iddepartamentos = iddepartamento 
                                                    WHERE usuario_depto.idusuario = ? ) depto ON depto.departamento = sol.nomdepto
                                            WHERE pd.idproceso IS NOT NULL AND pd.prioridad = 2 AND pd.idetapa IN ( select idetapa from proceso_etapa where idproceso = pd.idproceso AND orden  IN(select MIN(orden) from proceso_etapa where idproceso=pd.idproceso ) ) 
                                    ) s 
                                CROSS JOIN listado_usuarios capturista ON capturista.idusuario = s.idusuario 
                                CROSS JOIN empresas ON s.idEmpresa = empresas.idempresa 
                                CROSS JOIN proveedores ON proveedores.idproveedor = s.idProveedor 
                                CROSS JOIN etapas ON etapas.idetapa = s.idetapa 
                                CROSS JOIN proceso pr ON s.idproceso = pr.idproceso
                                CROSS JOIN listado_usuarios aut ON s.idautoriza = aut.idusuario
                                LEFT JOIN motivo_sol_dev msd ON msd.idsolicitud = s.idsolicitud
                                LEFT JOIN vw_autpagos cant_pag ON cant_pag.idsolicitud = s.idsolicitud 
                                LEFT JOIN (SELECT idsolicitud, SUM(diastrans) diasTrans, SUM(tipoavance) AS numCancelados FROM log_efi_postdev 
                                GROUP BY idsolicitud) log_efi ON log_efi.idsolicitud = s.idsolicitud
                                ORDER BY s.fechaCreacion", array( $this->session->userdata("inicio_sesion")['id'], $this->session->userdata("inicio_sesion")['id'] )
        );
    }
    // WHERE pd.idproceso IS NOT NULL AND pd.idetapa NOT IN ( 0, 1, 30, 10, 11 )
    // WHERE pd.idproceso IS NOT NULL AND pd.prioridad = 1 AND pd.idetapa IN ( 1 )
    
    function getHistorialPostDev( $etapas,$finicial,$ffinal ){
        return $this->db->query("SELECT
                                    solpagos.idsolicitud, 
                                    empresas.abrev, 
                                    solpagos.proyecto, 
                                    solpagos.folio, 
                                    proveedores.nombre, 
                                    solpagos.fecha_autorizacion,
                                    capturista.nombre_completo,
                                    solpagos.cantidad,
                                    etapas.nombre AS etapa, 
                                    solpagos.etapa AS soletapa, 
                                    solpagos.condominio,
                                    solpagos.metoPago, 
                                    solpagos.nomdepto, 
                                    solpagos.fechaCreacion feccrea, 
                                    solpagos.justificacion, 
                                    DATE(solpagos.fecelab) AS fecelab, 
                                    IFNULL(notifi.visto, 1) AS visto, 
                                    solpagos.moneda,
                                    cant_pag.fechaDis, 
                                    cant_pag.upago, 
                                    cant_pag.fecha_cobro,
                                    cant_pag.estatus_pago,
                                    cant_pag.etapa_pago,
                                    IFNULL(diasTrans,0) AS diasTrans, 
                                    IFNULL(numCancelados , 0) AS numCancelados
                                FROM (  SELECT sp.*, pd.idetapa, pd.idproceso, pd.prioridad FROM post_devoluciones pd
                                        JOIN (SELECT idsolicitud ,proyecto, folio, fecha_autorizacion, cantidad, nomdepto, condominio, fechaCreacion, metoPago, 
                                                    justificacion, fecelab, moneda, idusuario, idEmpresa, idProveedor, etapa
                                            FROM solpagos WHERE solpagos.fechaCreacion BETWEEN '$finicial' AND '$ffinal') sp ON sp.idsolicitud = pd.idsolicitud
                                        ) solpagos
                                CROSS JOIN listado_usuarios capturista ON capturista.idusuario = solpagos.idusuario 
                                CROSS JOIN empresas ON solpagos.idEmpresa = empresas.idempresa 
                                CROSS JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor 
                                CROSS JOIN etapas ON etapas.idetapa = solpagos.idetapa 
                                LEFT JOIN ( SELECT * FROM vw_autpagos ) cant_pag ON cant_pag.idsolicitud = solpagos.idsolicitud 
                                LEFT JOIN (SELECT idsolicitud, SUM(diastrans) diasTrans, SUM(tipoavance) AS numCancelados FROM log_efi_postdev 
                                GROUP BY idsolicitud) log_efi ON log_efi.idsolicitud = solpagos.idsolicitud
                                LEFT JOIN ( SELECT notificaciones.idsolicitud, notificaciones.visto FROM notificaciones WHERE notificaciones.idusuario = ? GROUP BY notificaciones.idsolicitud ) AS notifi ON notifi.idsolicitud = solpagos.idsolicitud 
                                WHERE solpagos.idetapa IN  $etapas
                                AND nomdepto IN ( SELECT departamento FROM departamentos INNER JOIN usuario_depto ON iddepartamentos = iddepartamento WHERE usuario_depto.idusuario = ? )  
                                ORDER BY solpagos.idsolicitud DESC", array( $this->session->userdata("inicio_sesion")['id'], $this->session->userdata("inicio_sesion")['id']  ) );
    }

    function proceso_etapas($idsolicitud){
        return $this->db->query("SELECT sp.idproceso, logs.idusuario, logs.idsolicitud, logs.etapa, pe.idproceso, pe.idrol, pe.orden, 
        UPPER(CONCAT(e.nombre, ' - ', REPLACE( REPLACE(r.descripcion, 'Contabilidad Ingresos', 'CONTABILIDAD'), 'Contabilidad Egresos / Ingresos',  'CONTABILIDAD')  )) AS etapa_regresar, e.idetapa
        FROM (
            SELECT logs.* FROM (
                SELECT * FROM logs WHERE logs.idsolicitud = $idsolicitud AND logs.etapa IS NOT NULL GROUP BY logs.etapa
            ) logs INNER JOIN post_devoluciones pd ON pd.idsolicitud = logs.idsolicitud AND logs.etapa NOT IN ( 7, 9, 10, 11 ) AND pd.prioridad = 2
            UNION 
            SELECT logs.* FROM (
                SELECT * FROM logs WHERE logs.idsolicitud = $idsolicitud AND logs.etapa IN ( 10, 11 ) ORDER BY logs.fecha DESC LIMIT 1
            ) logs INNER JOIN post_devoluciones pd ON pd.idsolicitud = logs.idsolicitud AND pd.prioridad = 2
            UNION
            SELECT logs.* FROM (
                SELECT * FROM logs WHERE logs.idsolicitud = '$idsolicitud' AND logs.etapa IS NOT NULL GROUP BY logs.etapa
            ) logs INNER JOIN solpagos ON solpagos.idsolicitud = logs.idsolicitud AND logs.etapa NOT IN ( 7, 9, 10, 11 ) AND idproceso = 30
        ) logs 
        INNER JOIN post_devoluciones sp ON sp.idsolicitud = logs.idsolicitud
        INNER JOIN proceso_etapa pe ON pe.idproceso = sp.idproceso AND logs.etapa = pe.idetapa
        INNER JOIN etapas e ON e.idetapa = pe.idetapa
        LEFT JOIN roles r ON FIND_IN_SET( r.idrol, pe.idrol) WHERE logs.etapa != sp.idetapa GROUP BY e.idetapa ");
    }
    
}