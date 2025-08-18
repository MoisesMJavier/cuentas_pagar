<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/* Regla de negocio para la gestion de Contabilidad 
 * de acuerdo alas Facturas emitidas por el solicitante */

class M_Devolucion_Traspaso extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }
    function get_consultar_docs($idsolicitud)
    {
        // return $this->db->query("SELECT * FROM documentos WHERE estatus_doc = 1 limit 5");
        return $this->db->query("SELECT * FROM proceso p INNER JOIN solpagos s ON p.idproceso = s.idproceso INNER JOIN documentos_proceso dp ON  dp.idproceso = p.idproceso 
        INNER JOIN  documentos d ON d.iddocumentos = dp.iddocumentos  WHERE idsolicitud = '$idsolicitud'");
    }
    function get_info_docs($idsolicitud)
    {
        // return $this->db->query("SELECT * FROM documentos WHERE estatus_doc = 1 limit 5");
        return $this->db->query("SELECT sol.idsolicitud, sol.idproceso, sol.proyecto, proveedores.rfc, proveedores.nombre, proveedores.alias, d.iddocumentos, d.ndocumento   FROM proceso p
        INNER JOIN solpagos sol ON p.idproceso = sol.idproceso
        INNER JOIN documentos_proceso dp ON  dp.idproceso = p.idproceso
        INNER JOIN  documentos d ON d.iddocumentos = dp.iddocumentos 
        INNER JOIN proveedores ON sol.idProveedor = proveedores.idproveedor
        WHERE idsolicitud ='$idsolicitud'");
    }
    function docs_proceso_sol($idsolicitud)
    {
        $rol = $this->session->userdata("inicio_sesion")['rol'];
        // return $this->db->query("SELECT * FROM documentos WHERE estatus_doc = 1 limit 5");
        return $this->db->query("SELECT *, 
                                         IF((dp.requerido = 1 AND
                                             dp.rol = ? ) OR 
                                            (dp.fpago = sp.metoPago AND
                                             dp.rol = ? ) , '*', '') AS doc_req
                                FROM solpagos sp 
                                INNER JOIN documentos_proceso dp 
                                    ON dp.idproceso = sp.idproceso 
                                INNER JOIN documentos d
                                    ON d.iddocumentos = dp.iddocumentos  
                                INNER JOIN proveedores 
                                    ON sp.idProveedor = proveedores.idproveedor
                                WHERE idsolicitud = ? AND 
                                      dp.iddocumentos NOT IN (SELECT iddocumento 
                                                              FROM documentos_solicitud ds 
                                                              WHERE ds.idsolicitud = ? AND ds.estatus = 1);", array($rol, $rol, $idsolicitud, $idsolicitud));
    }
    // function docs_proceso_sol($idsolicitud)
    // {
    //     // return $this->db->query("SELECT * FROM documentos WHERE estatus_doc = 1 limit 5");
    //     return $this->db->query("SELECT sol.idsolicitud, sol.idproceso, sol.proyecto, proveedores.rfc, proveedores.nombre, proveedores.alias, d.iddocumentos, d.ndocumento, documentos_solicitud.estatus   FROM proceso p
    //     INNER JOIN solpagos sol ON p.idproceso = sol.idproceso
    //     INNER JOIN documentos_proceso dp ON  dp.idproceso = p.idproceso
    //     INNER JOIN  documentos d ON d.iddocumentos = dp.iddocumentos 
    //     INNER JOIN proveedores ON sol.idProveedor = proveedores.idproveedor
    //     LEFT JOIN  (SELECT * FROM documentos_solicitud WHERE idsolicitud = '$idsolicitud' AND (estatus = 9 OR estatus = NULL)) AS documentos_solicitud ON documentos_solicitud.iddocumento = dp.iddocumentos 
    //     WHERE sol.idsolicitud ='$idsolicitud'");
    // }
    function check_docs($idsolicitud)
    {
        // return $this->db->query("SELECT * FROM documentos WHERE estatus_doc = 1 limit 5");
        return $this->db->query("SELECT sol.idsolicitud, sol.idproceso, sol.proyecto, proveedores.rfc, proveedores.nombre, proveedores.alias, d.iddocumentos, d.ndocumento, documentos_solicitud.estatus   FROM proceso p
        INNER JOIN solpagos sol ON p.idproceso = sol.idproceso
        INNER JOIN documentos_proceso dp ON  dp.idproceso = p.idproceso
        INNER JOIN  documentos d ON d.iddocumentos = dp.iddocumentos 
        INNER JOIN proveedores ON sol.idProveedor = proveedores.idproveedor
        LEFT JOIN  (SELECT * FROM documentos_solicitud WHERE idsolicitud = '$idsolicitud' ) AS documentos_solicitud ON documentos_solicitud.iddocumento = dp.iddocumentos 
        WHERE sol.idsolicitud ='$idsolicitud'");
    }
    function insert_documento($iddocumento, $idsolicitud, $ruta)
    {
        // return $this->db->query("SELECT * FROM documentos WHERE estatus_doc = 1 limit 5");
        return $this->db->insert(
            "documentos_solicitud",
            array(
                "idsolicitud" => $idsolicitud,
                "iddocumento" => $iddocumento,
                "ruta" => $ruta,
                "estatus" => 1,
                "idcrea" => $this->session->userdata("inicio_sesion")['id'],
                "fecha_crea" => date("Y-m-d h:i:s")
            )
        );
    }
    function info_sol($idsolicitud)
    {
        return $this->db->query("SELECT * FROM solpagos WHERE idsolicitud = '$idsolicitud'");
    }
    //@uthor Efraín Martinez Muñoz || Fecha: 31/01/2024 || Se creo la función info_autpagos la cual obtien el ultimo registro
    // que existe de la solicitud en la tabla de autpagos.
    function info_autpagos($idsolicitud)
    {
        return $this->db->query("SELECT * FROM autpagos WHERE idsolicitud = '$idsolicitud' ORDER BY fecreg DESC LIMIT 1");
    }
    function info_autpago($idsolicitud)
    {
        return $this->db->query("SELECT * FROM autpagos WHERE idrealiza = 0 AND fecreg = '0000-00-00 00:00:00' AND idsolicitud = '$idsolicitud'");
    }
    function get_procesos($idproceso, $orden)
    {
        return $this->db->query("SELECT * FROM proceso_etapa WHERE idproceso = '$idproceso' AND orden = $orden ORDER BY orden ASC");
    }
    function ruta_doc_dropbox($idsolicitud)
    {
        return $this->db->query("SELECT * FROM documentos_solicitud INNER JOIN documentos ON documentos_solicitud.iddocumento = documentos.iddocumentos WHERE idsolicitud = '$idsolicitud' AND estatus = 1 ");
    }
    function avanzar_proceso($orden, $idsolicitud, $etapa, $empresa_ad)
    {
        // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
        return $this->db->update("solpagos", array(
            "idetapa" => $orden,
            "fecha_autorizacion" => date("Y-m-d H:i:s"),
            "etapa" => $etapa,
            "idEmpresa" => $empresa_ad,
        ), "idsolicitud = '$idsolicitud'");
    }

    //ACTUALIZA LA SOLICITUD CON SOLO AGREGAR LOS DATOS.
    function updateDevTras( $data, $idsolicitud, $idetapa ){
        // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
        return $this->db->update("solpagos", $data, "idsolicitud = '$idsolicitud' AND idetapa = '$idetapa'");
    }

    function regrezar_proceso($orden, $idsolicitud)
    {
        // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
        return $this->db->update("solpagos", array(
            "idetapa" => $orden,
            "fecha_autorizacion" => date("Y-m-d H:i:s")
        ), "idsolicitud = '$idsolicitud'");
    }
    /**
     * @uthor Efrain Martinez Muñoz | Fecha : 27/03/2025
     * Se agrega un LEFT JOIN a esta consulta en la cual obtiene datos de las tablas (historial_documento, etiquetas, etiqueta_sol), ys e agregan las columnas idetiqueta,
     * estatusLote y tipo_ doc que se utilizaran para mostrar los botones en la vista.
     */
    function get_solicitudes_autorizadas_area()
    {
        $rol = $this->session->userdata("inicio_sesion")['rol'];
        $idUsuario = $this->session->userdata("inicio_sesion")['id'];
        $deptoUsuario = $this->session->userdata("inicio_sesion")['depto'];
        /*
        switch ($this->session->userdata("inicio_sesion")['rol']){
            // case "AD":
            // case "CAD":
            case "CE":
            case "CX":
                $condicion = "SELECT * FROM solpagos  WHERE solpagos.idetapa != 1 AND solpagos.idetapa IN (SELECT DISTINCT idetapa FROM proceso_etapa WHERE depto IS NULL AND FIND_IN_SET('".$this->session->userdata("inicio_sesion")['rol']."', idrol)) AND SUBSTRING_INDEX(solpagos.condominio, '-', 1) IN (SELECT desarrollo FROM usuario_desarrollo WHERE idusuario = '".$this->session->userdata("inicio_sesion")['id']."' )";
            break;
            default:
                $condicion = "SELECT * FROM solpagos WHERE solpagos.idetapa != 1 AND solpagos.idetapa IN ( SELECT DISTINCT idetapa idetapa FROM proceso_etapa WHERE depto IS NULL  AND FIND_IN_SET('".$this->session->userdata("inicio_sesion")['rol']."', idrol) ) UNION  SELECT  * FROM solpagos WHERE solpagos.idetapa IN (SELECT  idetapa FROM proceso_etapa WHERE ((depto IS NOT NULL AND depto = '".$this->session->userdata("inicio_sesion")['depto']."') AND FIND_IN_SET('".$this->session->userdata("inicio_sesion")['rol']."', idrol)) ) UNION  SELECT * FROM solpagos WHERE solpagos.idsolicitud IN (SELECT idsolicitud FROM solpagos sp  INNER JOIN proceso p ON p.idproceso = sp.idproceso  INNER JOIN proceso_etapa pe ON (pe.idetapa = sp.idetapa AND pe.idproceso = sp.idproceso ) INNER JOIN rol_grupo rg ON pe.idrol = rg.rol AND rg.grupo IN (SELECT grupo FROM rol_grupo WHERE rol = 'GAD') WHERE 'GAD' = '".$this->session->userdata("inicio_sesion")['rol']."')  ";
            break;
        }
        */
        if(in_array( $this->session->userdata("inicio_sesion")['rol'], ['SPV'] ))//MAR
        {
            $condicion = "SELECT * FROM solpagos 
                            WHERE solpagos.idetapa NOT IN ( 1, 7 ) AND solpagos.idetapa 
                                IN ( SELECT DISTINCT idetapa idetapa 
                                FROM proceso_etapa WHERE depto IS NULL  
                                AND FIND_IN_SET('$rol', idrol)) 
                            UNION  
                                SELECT  * FROM solpagos 
                                WHERE solpagos.idetapa NOT IN ( 1, 7 ) AND solpagos.idetapa IN 
                                (SELECT  idetapa FROM proceso_etapa 
                                WHERE ((depto IS NOT NULL AND depto = '$deptoUsuario') 
                                AND FIND_IN_SET('$rol', idrol) ) ) 
                            AND solpagos.idproceso IN (SELECT idproceso FROM proceso_etapa WHERE FIND_IN_SET('$rol', idrol))
                UNION 
                    SELECT * FROM solpagos WHERE solpagos.idetapa NOT IN ( 1, 7 ) AND solpagos.idsolicitud IN 
                        (SELECT idsolicitud FROM solpagos sp  INNER JOIN proceso p ON p.idproceso = sp.idproceso  
                        INNER JOIN proceso_etapa pe ON (pe.idetapa = sp.idetapa AND pe.idproceso = sp.idproceso ) 
                        INNER JOIN rol_grupo rg ON pe.idrol = rg.rol AND rg.grupo 
                        IN (SELECT grupo FROM rol_grupo WHERE rol = 'GAD') 
                        WHERE 'GAD' = '$rol')  ";

            return $this->db->query("SELECT solpagos.idproceso, solpagos.folio, solpagos.crecibo, 
                                            solpagos.requisicion, solpagos.orden_compra, solpagos.homoclave, 
                                            solpagos.metoPago, solpagos.prioridad, solpagos.nomdepto, 
                                            solpagos.prioridad, solpagos.condominio, solpagos.idAutoriza, 
                                            solpagos.proyecto, solpagos.fecha_autorizacion, solpagos.idsolicitud, 
                                            solpagos.justificacion, proveedores.nombre, proveedores.tipo_prov, 
                                            solpagos.cantidad, DATE_FORMAT(solpagos.fecelab, '%d/%m/%Y') AS fecelab, 
                                            solpagos.idetapa, etapas.nombre AS etapa, capturista.nombre_completo, 
                                            solpagos.idusuario, solpagos.caja_chica, solpagos.servicio, 
                                            IFNULL(notifi.visto, 1) AS visto, solpagos.moneda, 
                                            proceso.nombre AS nombre_proceso, empresas.abrev AS nempresa,
                                            eti.idetiqueta,
                                            eti.etiqueta AS estatusLote,
                                            eti.tipo_doc
                    FROM
                        (SELECT * FROM solpagos
                            WHERE solpagos.idproceso IN (SELECT idproceso FROM proceso_etapa WHERE FIND_IN_SET('$rol', idrol))
                            AND solpagos.idetapa IN (1) UNION $condicion 
                        ) solpagos 
                        LEFT JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor
                        CROSS JOIN listado_usuarios capturista ON capturista.idusuario = solpagos.idusuario
                        CROSS JOIN empresas ON empresas.idempresa = solpagos.idEmpresa
                        CROSS JOIN proceso proceso ON proceso.idproceso = solpagos.idproceso
                        LEFT JOIN etapas ON etapas.idetapa = solpagos.idetapa
                        LEFT JOIN notifi ON notifi.idsolicitud = solpagos.idsolicitud
                        LEFT JOIN (SELECT esol.idetiqueta,
                                             e.etiqueta,
                                             esol.idsolicitud,
                                             esol.rol,
                                             hd.tipo_doc
                                     FROM etiqueta_sol esol
                                     JOIN etiquetas e 
                                        ON esol.idetiqueta = e.idetiqueta AND esol.rol IS NOT NULL
                                     LEFT JOIN historial_documento hd 
                                        ON esol.idsolicitud = hd.idsolicitud AND hd.tipo_doc = 9 ) eti 
                                ON eti.idsolicitud = solpagos.idsolicitud
                        GROUP BY idsolicitud
                        ORDER BY notifi.visto DESC , FIELD(solpagos.idetapa, 1, 8, 21, 3, 30) , solpagos.fechaCreacion");

        }else{    
            /*  
            "SELECT solpagos.* FROM solpagos 
                INNER JOIN ( SELECT DISTINCT idetapa idetapa FROM proceso_etapa 
                WHERE depto IS NULL AND FIND_IN_SET( '".$this->session->userdata("inicio_sesion")['rol']."', idrol) ) et ON et.idetapa = solpagos.idetapa WHERE solpagos.idetapa NOT IN ( 1, 7 )
                UNION  
            SELECT solpagos.* FROM solpagos 
                INNER JOIN ( SELECT DISTINCT idetapa idetapa FROM proceso_etapa WHERE depto IS NOT NULL AND depto = '".$this->session->userdata("inicio_sesion")['depto']."' AND FIND_IN_SET('".$this->session->userdata("inicio_sesion")['rol']."', idrol ) )
                    WHERE solpagos.idetapa NOT IN ( 1, 7 )  
                UNION    
            SELECT sp.* FROM solpagos sp 
                INNER JOIN proceso p ON p.idproceso = sp.idproceso  
                INNER JOIN proceso_etapa pe ON ( pe.idetapa = sp.idetapa AND pe.idproceso = sp.idproceso ) 
                INNER JOIN rol_grupo rg ON pe.idrol = rg.rol AND rg.grupo IN ( SELECT grupo FROM rol_grupo WHERE rol = 'GAD') WHERE 'GAD' = '".$this->session->userdata("inicio_sesion")['rol']."') 
                WHERE sp.idetapa NOT IN ( 1, 7 )";
            */
            $condicion = "SELECT solpagos.* 
                          FROM ( SELECT idproceso, idetapa 
                                 FROM proceso_etapa 
                                 WHERE  (FIND_IN_SET( '$rol', idrol) OR FIND_IN_SET('$idUsuario', idpermitido) ) AND 
                                        depto IS NULL AND single = 0 AND
                                        (idnopermitido IS NULL OR idnopermitido not in ('$idUsuario'))) et
                          INNER JOIN ( SELECT * 
                                       FROM solpagos 
                                       WHERE solpagos.idetapa NOT IN ( 1, 7, 11, 78, 79, 80, 81 ) AND idproceso IS NOT NULL ) solpagos 
                            ON et.idetapa = solpagos.idetapa AND solpagos.idproceso = et.idproceso
                        UNION
                          SELECT solpagos.*
                          FROM ( SELECT idproceso, idetapa 
                                 FROM proceso_etapa 
                                 WHERE FIND_IN_SET( '$rol', idrol) AND depto IS NULL AND single = 1 ) et
                          INNER JOIN (  SELECT *
                                        FROM solpagos 
                                        WHERE solpagos.idetapa NOT IN ( 1, 7, 11, 78, 79, 80, 81 ) AND idproceso IS NOT NULL ) solpagos 
                            ON et.idetapa = solpagos.idetapa AND solpagos.idproceso = et.idproceso AND solpagos.idusuario = '$idUsuario'
                        UNION
                          SELECT solpagos.* 
                          FROM ( SELECT DISTINCT idetapa idetapa 
                                 FROM proceso_etapa 
                                 WHERE  depto IS NOT NULL AND depto = '$deptoUsuario' AND
                                        FIND_IN_SET('$rol', idrol ) ) pe 
                          INNER JOIN (  SELECT * 
                                        FROM solpagos 
                                        WHERE solpagos.idetapa NOT IN ( 1, 7, 11, 78, 79, 80, 81 ) AND idproceso IS NOT NULL ) solpagos 
                            ON pe.idetapa = solpagos.idetapa
                        UNION    
                          SELECT sp.* 
                          FROM ( SELECT * 
                                 FROM solpagos
                                 WHERE  solpagos.idetapa NOT IN ( 1, 7, 11, 78, 79, 80, 81 ) AND 
                                        idproceso IS NOT NULL AND 'GAD' = '$rol' ) sp 
                          INNER JOIN proceso p 
                            ON p.idproceso = sp.idproceso  
                          INNER JOIN proceso_etapa pe
                            ON pe.idetapa = sp.idetapa AND pe.idproceso = sp.idproceso 
                          INNER JOIN rol_grupo rg
                            ON pe.idrol = rg.rol AND rg.grupo IN ( SELECT grupo FROM rol_grupo WHERE rol = 'GAD')
                        UNION
                            SELECT sp.*
                            FROM solpagos AS sp
                            INNER JOIN (SELECT *
                                        FROM proceso_etapa
                                        WHERE	(FIND_IN_SET( '$rol', idrol) OR FIND_IN_SET('$idUsuario', idpermitido) ) AND
                                                depto IS NULL AND
                                                (idnopermitido IS NULL OR NOT FIND_IN_SET('$idUsuario', idnopermitido))) AS pe
                                ON sp.idetapa = pe.idetapa AND sp.idproceso = pe.idproceso
                            INNER JOIN (SELECT *
                                        FROM usuarios
                                        WHERE FIND_IN_SET('$idUsuario', sup)) AS us
                                ON sp.idusuario = us.idusuario
                            WHERE sp.idetapa NOT IN ( 7, 11, 78, 79, 80 ) AND sp.idproceso IS NOT NULL";

            return $this->db->query("SELECT solpagos.idproceso, 
                                            solpagos.folio, 
                                            solpagos.crecibo, solpagos.requisicion, solpagos.orden_compra, solpagos.homoclave, solpagos.metoPago, 
                                            solpagos.nomdepto, 
                                            solpagos.prioridad, 
                                            solpagos.condominio, 
                                            solpagos.idAutoriza, 
                                            solpagos.proyecto, 
                                            solpagos.fecha_autorizacion, 
                                            solpagos.idsolicitud, 
                                            solpagos.justificacion, 
                                            IFNULL(proveedores.nombre, '-') nombre, 
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
                                            empresas.abrev AS nempresa,
                                            IF(solpagos.idproceso = 30 AND solpagos.programado IS NOT NULL, 'S', 'N') AS esParcialidad,
                                            solpagos.programado, 
                                            sp.montoParcialidad,
                                            sp.numeroParcialidades,
                                            eti.idetiqueta,
                                            eti.etiqueta AS estatusLote,
                                            eti.tipo_doc
                                        FROM
                                            (
                                                SELECT * FROM solpagos WHERE solpagos.idusuario = ? AND idproceso IS NOT NULL AND solpagos.idetapa IN ( 1 ) 
                                                UNION 
                                                $condicion 
                                            ) solpagos 
                                        CROSS JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor
                                        CROSS JOIN listado_usuarios capturista ON capturista.idusuario = solpagos.idusuario
                                        CROSS JOIN empresas ON empresas.idempresa = solpagos.idEmpresa
                                        CROSS JOIN proceso proceso ON proceso.idproceso = solpagos.idproceso
                                        CROSS JOIN etapas ON etapas.idetapa = solpagos.idetapa
                                        LEFT JOIN solicitudesParcialidades sp ON sp.idsolicitud = solpagos.idsolicitud
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
                                        ORDER BY FIELD(solpagos.idetapa, 1, 8, 21, 3, 30) , solpagos.fechaCreacion", [ $this->session->userdata("inicio_sesion")['id'] ]);
                                    }
                                
                                }
    //@uthor Efraín Martinez Muñoz || Fecha: 31/01/2024 || Se hace un INNER JOIN a una subconsulta que extrae el penultimo registro de la solicitud
    // que se encuentra en la tabla logs. para obtener la etapa anterior de la solicitud.
    function get_devoluciones_parcialidad(){
        $rolUsuario = $this->session->userdata("inicio_sesion")['rol'];
        $idUsuario = $this->session->userdata("inicio_sesion")['id'];
        $deptoUsuario = $this->session->userdata("inicio_sesion")['depto'];
        $query =    "SELECT solpagos.idsolicitud,
                            proveedores.nombre,
                            IFNULL(ptotales, 0) ptotales, 
                            IFNULL(tparcial, 0) tparcial,
                            spa.numeroParcialidades ppago,
                            (CASE 
                                WHEN solpagos.programado = 8 THEN
                                    IF( ptotales >= TIMESTAMPDIFF(DAY,
                                            solpagos.fecelab,
                                            solpagos.fecha_fin) / 15,
                                        solpagos.fecha_fin,
                                        CASE
                                            WHEN WEEKDAY(DATE_ADD(solpagos.fecelab, INTERVAL (IFNULL(ptotales, 0) * 15) DAY)) = 5 THEN 
                                                        DATE_ADD(solpagos.fecelab, INTERVAL (IFNULL(ptotales, 0) * 15)+2 DAY)
                                            WHEN WEEKDAY(DATE_ADD(solpagos.fecelab, INTERVAL (IFNULL(ptotales, 0) * 15) DAY)) = 6 THEN 
                                                        DATE_ADD(solpagos.fecelab, INTERVAL (IFNULL(ptotales, 0) * 15)+1 DAY)
                                            ELSE
                                            DATE_ADD(solpagos.fecelab, INTERVAL (IFNULL(ptotales, 0) * 15) DAY)
                                        END)
                                WHEN solpagos.programado < 7 THEN 
                                    CASE
                                        WHEN WEEKDAY(DATE_ADD( solpagos.fecelab, INTERVAL ( IFNULL(ptotales, 0) * solpagos.programado ) MONTH )) = 5 THEN 
                                        DATE_ADD(DATE_ADD( solpagos.fecelab, INTERVAL ( IFNULL(ptotales, 0) * solpagos.programado ) MONTH), interval 2 DAY)
                                        WHEN WEEKDAY(DATE_ADD( solpagos.fecelab, INTERVAL ( IFNULL(ptotales, 0) * solpagos.programado ) MONTH ) ) = 6 THEN 
                                        DATE_ADD(DATE_ADD( solpagos.fecelab, INTERVAL ( IFNULL(ptotales, 0) * solpagos.programado ) MONTH), interval 1 DAY)
                                        ELSE 
                                            DATE_ADD( solpagos.fecelab, INTERVAL ( IFNULL(ptotales, 0) * solpagos.programado ) MONTH)
                                    END
                                ELSE
                                    CASE
                                        WHEN WEEKDAY(DATE_ADD( solpagos.fecelab, INTERVAL IFNULL(ptotales, 0) WEEK )) = 5 THEN 
                                                    DATE_ADD(DATE_ADD( solpagos.fecelab, INTERVAL IFNULL(ptotales, 0) WEEK ), interval 2 DAY)
                                        WHEN WEEKDAY(DATE_ADD( solpagos.fecelab, INTERVAL IFNULL(ptotales, 0) WEEK ) ) = 6 THEN 
                                                    DATE_ADD(DATE_ADD( solpagos.fecelab, INTERVAL IFNULL(ptotales, 0) WEEK ), interval 1 DAY)
                                        ELSE
                                        DATE_ADD( solpagos.fecelab, INTERVAL IFNULL(ptotales, 0) WEEK )
                                    END
                            END ) proximo_pago,
                            solpagos.programado,
                            solpagos.moneda,
                            solpagos.justificacion,
                            solpagos.metoPago,
                            solpagos.fecha_fin,
                            solpagos.cantidad,
                            IFNULL(autpagos.cantidad_confirmada, 0) cantidad_confirmada,
                            solpagos.fecelab,
                            solpagos.nomdepto,
                            empresas.abrev AS nemp,
                            nombre_capturista,
                            solpagos.homoclave,
                            solpagos.orden_compra,
                            solpagos.idProveedor,
                            proveedores.tipocta,
                            proveedores.cuenta,
                            bancos.idbanco,
                            bancos.nombre banco, 
                            bancos.clvbanco,
                            if(ptotales = (spa.numeroParcialidades - 1), solpagos.cantidad - IFNULL(autpagos.cantidad_confirmada, 0), spa.montoParcialidad) parcialidad,
                            usuarios.idusuario,
                            solpagos.prioridad,
                            spa.montoParcialidad,
                            autpagosPagAut.cantidad_aut AS cantidad_autorizada_total,
                            autpagosPagAut.pagosAut,
                            estatus_pago.estatus_ultimo_pago,
                            solpagos.idproceso,
                            spa.numeroParcialidades AS totalPagos,
                            pagosAutorizados.numPagosAutorizados,
                            pagosAutorizados.totalAutorizado,
                            pagosAutorizados.ultimoPagoAut,
                            ifnull(pagosDevoluciones.pagos, 0) pagos,
                            IF(spa.numeroParcialidades = (autpagos.ptotales + 1 ), solpagos.cantidad - autpagos.cantidad_confirmada, NULL) AS ultimoPagoParcialidad
                    FROM solpagos
                    INNER JOIN proveedores 
                        ON proveedores.idproveedor = solpagos.idProveedor
                    INNER JOIN empresas 
                        ON solpagos.idempresa = empresas.idempresa
                LEFT JOIN(SELECT autpagos.idsolicitud, 
                                 COUNT(autpagos.idpago) ptotales,
                                 0 AS tparcial, 
                                 SUM(autpagos.cantidad) AS cantidad_confirmada, 
                                 MAX( autpagos.fecreg ) AS ultimo_pago 
                          FROM autpagos 
                          WHERE estatus in (16)
                          GROUP BY autpagos.idsolicitud ) AS autpagos
                    ON solpagos.idsolicitud = autpagos.idsolicitud
                LEFT JOIN(SELECT autpagos.idsolicitud,
                                 COUNT(autpagos.idsolicitud) AS pagosAut,
                                 SUM(autpagos.cantidad) AS cantidad_aut
                          FROM autpagos 
                          WHERE idrealiza IS NOT NULL AND fecreg <> '0000-00-00 00:00:00'
                          GROUP BY autpagos.idsolicitud ) autpagosPagAut
                    ON solpagos.idsolicitud = autpagosPagAut.idsolicitud
                
                LEFT JOIN ( SELECT  ap.idsolicitud, IFNULL(COUNT(ap.idpago), 0) AS numPagosAutorizados, 
                                    SUM(ap.cantidad) AS totalAutorizado, 
                                    (SELECT cantidad FROM autpagos AS ap2 WHERE ap2.idsolicitud = ap.idsolicitud ORDER BY ap2.idpago DESC LIMIT 1) AS ultimoPagoAut
                            FROM autpagos ap
                            GROUP BY idsolicitud) AS pagosAutorizados
                    ON pagosAutorizados.idsolicitud = solpagos.idsolicitud
                
                LEFT JOIN ( SELECT ap.idsolicitud, ifnull(count(ap.idpago), 0) pagos, sum(ap.cantidad) pagado
                            FROM autpagos ap
                            WHERE ap.estatus = 16
                            GROUP BY idsolicitud) AS pagosDevoluciones
                    ON pagosDevoluciones.idsolicitud = solpagos.idsolicitud
                    
                INNER JOIN(SELECT usuarios.idusuario, CONCAT(usuarios.nombres,' ', usuarios.apellidos) AS nombre_capturista FROM usuarios ) usuarios 
                    ON usuarios.idusuario = solpagos.idusuario
                LEFT JOIN ( SELECT autpagos.idsolicitud, autpagos.estatus AS estatus_ultimo_pago 
                            FROM autpagos 
                            GROUP BY autpagos.idsolicitud 
                            HAVING MAX(autpagos.idpago) ) AS estatus_pago 
                    ON solpagos.idsolicitud = estatus_pago.idsolicitud
                INNER JOIN(SELECT solpagos.* 
                                    FROM (SELECT idproceso, idetapa 
                                        FROM proceso_etapa 
                                        WHERE FIND_IN_SET( '$rolUsuario', idrol) AND depto IS NULL AND single = 0 ) et
                                    INNER JOIN ( SELECT * FROM solpagos WHERE solpagos.idetapa IN ( 78, 79, 80, 81 ) AND idproceso IS NOT NULL ) solpagos 
                                        ON et.idetapa = solpagos.idetapa AND solpagos.idproceso = et.idproceso
                                UNION
                                    SELECT solpagos.*
                                    FROM (SELECT idproceso, idetapa 
                                        FROM proceso_etapa 
                                        WHERE FIND_IN_SET( '$rolUsuario', idrol) AND depto IS NULL AND single = 1 ) et
                                    INNER JOIN ( SELECT * FROM solpagos WHERE solpagos.idetapa IN ( 78, 79, 80, 81 ) AND idproceso IS NOT NULL ) solpagos 
                                        ON et.idetapa = solpagos.idetapa AND solpagos.idproceso = et.idproceso AND solpagos.idusuario = $idUsuario
                                UNION  
                                    SELECT solpagos.* 
                                    FROM (SELECT DISTINCT idetapa idetapa 
                                        FROM proceso_etapa 
                                        WHERE depto IS NOT NULL AND depto = '$deptoUsuario' AND FIND_IN_SET('$rolUsuario', idrol ) ) pe 
                                    INNER JOIN ( SELECT * FROM solpagos WHERE solpagos.idetapa IN ( 78, 79, 80, 81 ) AND idproceso IS NOT NULL ) solpagos 
                                        ON pe.idetapa = solpagos.idetapa
                                UNION    
                                    SELECT sp.* 
                                    FROM ( SELECT * FROM solpagos WHERE solpagos.idetapa IN ( 78, 79, 80, 81 ) AND idproceso IS NOT NULL AND 'GAD' = 'PV' ) sp 
                                    INNER JOIN proceso p 
                                        ON p.idproceso = sp.idproceso  
                                    INNER JOIN proceso_etapa pe
                                        ON pe.idetapa = sp.idetapa AND pe.idproceso = sp.idproceso 
                                    INNER JOIN rol_grupo rg 
                                        ON pe.idrol = rg.rol AND rg.grupo IN ( SELECT grupo FROM rol_grupo WHERE rol = 'GAD')
                                UNION
                                    SELECT sp.*
                                    FROM solpagos AS sp
                                    INNER JOIN (SELECT *
                                                FROM proceso_etapa
                                                WHERE	(FIND_IN_SET( '$rolUsuario', idrol) OR FIND_IN_SET('$idUsuario', idpermitido) ) AND
                                                        depto IS NULL AND
                                                        (idnopermitido IS NULL OR NOT FIND_IN_SET('$idUsuario', idnopermitido))) AS pe
                                        ON sp.idetapa = pe.idetapa AND sp.idproceso = pe.idproceso
                                    INNER JOIN (SELECT *
                                                FROM usuarios
                                                WHERE FIND_IN_SET('$idUsuario', sup)) AS us
                                        ON sp.idusuario = us.idusuario
                                    WHERE sp.idetapa IN ( 78, 79, 80, 81 ) AND sp.idproceso IS NOT NULL) AS sp
                        ON sp.idsolicitud = solpagos.idsolicitud
                    LEFT JOIN solicitudesParcialidades spa 
                        ON spa.idsolicitud = sp.idsolicitud
                    LEFT JOIN bancos 
                        ON bancos.idbanco = proveedores.idbanco
                    ORDER BY proveedores.nombre ASC";
            return $this->db->query($query);
        //return "Hola";

    }
    
    function insertar_comentario_rechazado($info, $id)
    {
        return $this->db->update("documentos_solicitud", array(
            "comentario" => $info,
            "estatus" => 9
        ), "iddocumentos_solicitud = '$id'");
    }
    function insertar_comentario_rechazado_otro($info, $id)
    {
        return $this->db->update("documentos_solicitud", array(
            "comentario" => $info,
            "estatus" => 9
        ), "iddocumentos_solicitud = '$id'");
    }
    function observaciones_doc_descartado($idsol, $observacion)
    {
        return $this->db->insert("obssols", array(
            "idsolicitud" => $idsol,
            "obervacion" => $observacion,
            "idusuario" =>  $this->session->userdata("inicio_sesion")['id'],
            "fecreg" =>  date("Y-m-d H:i:s")
        ));
    }
    function buscar_docs($idsolicitud)
    {
        return $this->db->query("SELECT * FROM documentos_solicitud WHERE idsolicitud = '$idsolicitud' AND estatus = 9");
    }
    function docs_proceso_sol_avanzar($idsolicitud, $idrol)
    {   
        /*
        return $this->db->query("SELECT *  
        FROM ( SELECT * FROM solpagos WHERE idsolicitud = '$idsolicitud' ) sp 
        INNER JOIN (SELECT * FROM  documentos_proceso WHERE rol = '$idrol' ) dp ON dp.idproceso = sp.idproceso 
        INNER JOIN documentos d ON d.iddocumentos = dp.iddocumentos  
        INNER JOIN proveedores ON sp.idProveedor = proveedores.idproveedor
        LEFT JOIN ( SELECT iddocumento FROM documentos_solicitud ds WHERE ds.estatus = 1 AND ds.idsolicitud = '$idsolicitud' GROUP BY idsolicitud ) docc ON dp.iddocumentos = docc.iddocumento
        WHERE ( dp.requerido = 1 OR FIND_IN_SET( sp.metoPago, dp.fpago ) ) AND docc.iddocumento IS NULL");
        */
        return $this->db->query("SELECT 
            s.idsolicitud, 
            s.idproceso,
            SUM( IF(dproc.requerido = 1 AND iddocumentos_solicitud IS NULL, 1, 0 ) ) requerido
        FROM ( SELECT * FROM solpagos WHERE idsolicitud = ? ) s
        LEFT JOIN ( SELECT idproceso, iddocumentos, requerido FROM documentos_proceso WHERE rol = ? ) dproc ON dproc.idproceso = s.idproceso 
        LEFT JOIN ( SELECT iddocumentos_solicitud, iddocumento, idsolicitud FROM documentos_solicitud ds WHERE ds.estatus = 1 AND ds.idsolicitud = ? ) docsol ON docsol.idsolicitud = s.idsolicitud AND docsol.iddocumento = dproc.iddocumentos    
        GROUP BY s.idsolicitud, s.idproceso", [ $idsolicitud, $idrol, $idsolicitud ]);
    }
    function ultimo_regis()
    {
        return $this->db->query(" SELECT max(iddocumentos_solicitud) AS id FROM documentos_solicitud ");
    }
    // function validacion_cuenta($cuenta, $nombre, $idbanco)
    // {
    //     return $this->db->query("SELECT * FROM proveedores WHERE cuenta = '$cuenta'  AND idbanco ='$idbanco' AND cuenta != 0  AND cuenta IS NOT NULL AND idbanco != 0 AND idbanco IS NOT NULL");
    // }
    // }
    function validacion_cuenta($cuenta, $idbanco)
    {
        return $this->db->query("SELECT * FROM proveedores WHERE cuenta = '$cuenta'   AND cuenta != 0  AND cuenta IS NOT NULL AND idbanco != 0 AND idbanco IS NOT NULL");
    }
    function validacion_cuenta1($cuenta, $idbanco)
    {
        return $this->db->query("SELECT * FROM proveedores WHERE cuenta = '$cuenta'   AND cuenta != 0  AND cuenta IS NOT NULL AND idbanco != 0 AND idbanco IS NOT NULL");
    }
    // function agregar_log($idsolicitud,$tipo_mov){
    //     return $this->db->insert('logs', array(
    //         "idsolicitud" => $idsolicitud,
    //         "tipomov" => $tipo_mov,
    //         "idusuario" => $this->session->userdata("inicio_sesion")['id'],
    //         "fecha" => date("Y-m-d h:i:s")
    //     ));
    // }
    function info_doc($id)
    {
        // return $this->db->query("SELECT * FROM documentos_solicitud WHERE iddocumentos_solicitud = '$id' ");
        return $this->db->query("SELECT * FROM documentos_solicitud INNER JOIN documentos ON documentos_solicitud.iddocumento = documentos.iddocumentos WHERE iddocumentos_solicitud = '$id' ");
    }
    function get_orden_sol($idetpa, $idproceso)
    {
        return $this->db->query("SELECT * FROM proceso_etapa WHERE idetapa = '$idetpa' AND idproceso = '$idproceso' ");
    }
    function consulta($idrol, $idproceso, $orden)
    {
        return $this->db->query("SELECT * FROM proceso_etapa WHERE idrol = '$idrol' AND idproceso = '$idproceso' AND orden < $orden ORDER BY orden DESC LIMIT 1; ");
    }
    function consulta1($iddocumentos, $idproceso)
    {
        return $this->db->query("SELECT * FROM documentos_proceso WHERE iddocumentos = '$iddocumentos' AND idproceso = $idproceso; ");
    }
    function proceso_info($idproceso, $orden)
    {
        return $this->db->query("SELECT * FROM proceso_etapa WHERE idproceso = $idproceso AND orden ='$orden'; ");
    }
    function factura_solicitud($idsolicitud)
    {
        $query = "SELECT LEFT(solpagos.fechaCreacion, 10) AS fech_creacion, solpagos.requisicion,solpagos.idsolicitud,solpagos.ref_bancaria, solpagos.intereses, solpagos.proyecto, facturas.finalfact, solpagos.homoclave,solpagos.homoclave as homo , solpagos.etapa,
    solpagos.condominio,solpagos.orden_compra as orden_compras,solpagos.orden_compra, empresas.rfc AS rfc_receptor, empresas.nombre AS n_receptor, proveedores.rfc, proveedores.nombre,
    proveedores.cuenta,solpagos.nomdepto, b.nombre as banco,
    case proveedores.tipocta when 1 then 'Cuenta Banco del Bajío' when 3 then 'TDD ó TDC' when 40 then 'CLABE' end as tipocta ,
    IFNULL(facturas.metpago, 'SIN FACTURA') AS metodo_pago, IFNULL( facturas.foliofac, solpagos.folio ) AS folio,  
    IFNULL(facturas.fecfac, 'SIN DEFINIR') AS fecha_factura, facturas.uuid, facturas.descripcion, facturas.observaciones, 
    solpagos.cantidad, facturas.total, facturas.nombre_archivo, solpagos.justificacion, solpagos.caja_chica, solpagos.prioridad, 
    IFNULL(solpagos.metoPago, '---') AS forma_pago, solpagos.tendrafac, IF(solpagos.tendrafac IS NULL OR solpagos.tendrafac = 0,'1', 
    IF(facturas.nombre_archivo IS NULL,'2','3')) AS fac_status, insumos.insumo, proceso.nombre AS nombre_proceso, fecelab FROM solpagos
    INNER JOIN proceso ON solpagos.idproceso = proceso.idproceso
    LEFT JOIN insumos ON insumos.idinsumo = solpagos.servicio 
    INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor 
    INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa 
    left join bancos b on b.idbanco=proveedores.idbanco 
    LEFT JOIN 
    (SELECT *, 0 as finalfact  FROM facturas WHERE facturas.tipo_factura IN ( 1, 3 ) )
     AS facturas ON (facturas.idsolicitud = solpagos.idsolicitud OR CONCAT(',', facturas.idsolicitudr, ',') LIKE CONVERT( CONCAT('%,', solpagos.idsolicitud, ',%') USING UTF8)) WHERE solpagos.idsolicitud = '$idsolicitud' ORDER BY facturas.feccrea;";
        return $this->db->query($query/*"SELECT solpagos.ref_bancaria, solpagos.intereses, solpagos.proyecto, facturas.finalfact, solpagos.homoclave, solpagos.etapa, solpagos.condominio, empresas.rfc AS rfc_receptor, empresas.nombre AS n_receptor, proveedores.rfc, proveedores.nombre, IFNULL(facturas.metpago, 'SIN FACTURA') AS metodo_pago, IFNULL( facturas.foliofac, solpagos.folio ) AS folio,  IFNULL(facturas.fecfac, 'SIN DEFINIR') AS fecha_factura, facturas.uuid, facturas.descripcion, facturas.observaciones, solpagos.cantidad, facturas.total, facturas.nombre_archivo, solpagos.justificacion, solpagos.caja_chica, solpagos.prioridad, IFNULL(solpagos.metoPago, '---') AS forma_pago, solpagos.tendrafac, IF(solpagos.tendrafac IS NULL OR solpagos.tendrafac = 0,'1', IF(facturas.nombre_archivo IS NULL,'2','3')) AS fac_status, insumos.insumo FROM solpagos LEFT JOIN insumos ON insumos.idinsumo = solpagos.servicio INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa LEFT JOIN (SELECT *, count(idfactura) as finalfact ,MIN(facturas.feccrea) FROM facturas WHERE facturas.tipo_factura IN ( 1, 3 ) GROUP BY facturas.idsolicitud) AS facturas ON facturas.idsolicitud = solpagos.idsolicitud WHERE solpagos.idsolicitud = '$idsolicitud'"*/);
    }
    function proceso_etapas($idsolicitud){
        return $this->db->query("SELECT sp.idproceso, logs.idusuario, logs.idsolicitud, logs.etapa, pe.idproceso, pe.idrol, pe.orden, 
        UPPER(CONCAT(e.nombre, ' - ', REPLACE( REPLACE(r.descripcion, 'Contabilidad Ingresos', 'CONTABILIDAD'), 'Contabilidad Egresos / Ingresos',  'CONTABILIDAD')  )) AS etapa_regresar, e.idetapa
        FROM (
            /*
            SELECT * FROM logs WHERE logs.idsolicitud = '$idsolicitud' AND logs.etapa IS NOT NULL GROUP BY logs.etapa
            */
            SELECT logs.* FROM (
                SELECT * FROM logs WHERE logs.idsolicitud = '$idsolicitud' AND logs.etapa IS NOT NULL GROUP BY logs.etapa
            ) logs INNER JOIN solpagos ON solpagos.idsolicitud = logs.idsolicitud AND logs.etapa NOT IN ( 7, 9, 10, 11 ) AND solpagos.prioridad = 1
            UNION 
            SELECT logs.* FROM (
                SELECT * FROM logs WHERE logs.idsolicitud = '$idsolicitud' AND logs.etapa IN ( 10, 11 ) ORDER BY logs.fecha DESC LIMIT 1
            ) logs INNER JOIN solpagos ON solpagos.idsolicitud = logs.idsolicitud AND solpagos.prioridad = 2
            UNION
            SELECT logs.* FROM (
                SELECT * FROM logs WHERE logs.idsolicitud = '$idsolicitud' AND logs.etapa IS NOT NULL GROUP BY logs.etapa
            ) logs INNER JOIN solpagos ON solpagos.idsolicitud = logs.idsolicitud AND logs.etapa NOT IN ( 7, 9, 10, 11 ) AND idproceso = 30
        ) logs 
        INNER JOIN solpagos sp ON sp.idsolicitud = logs.idsolicitud
        INNER JOIN proceso_etapa pe ON pe.idproceso = sp.idproceso AND logs.etapa = pe.idetapa
        INNER JOIN etapas e ON e.idetapa = pe.idetapa
        LEFT JOIN roles r ON FIND_IN_SET( r.idrol, pe.idrol) WHERE logs.etapa != sp.idetapa GROUP BY e.idetapa");
    }

    function proceso_etapas_menor($idproceso, $etapa)
    {
        // return $this->db->query("SELECT pe.idrol, pe.orden, UPPER(CONCAT(e.nombre, ' - ', r.descripcion)) AS etapa_regresar, e.idetapa FROM proceso p INNER JOIN proceso_etapa pe ON pe.idproceso = p.idproceso
        // INNER JOIN etapas e ON e.idetapa = pe.idetapa INNER JOIN roles r ON r.idrol = pe.idrol WHERE p.idproceso = $idproceso AND pe.orden < (SELECT orden FROM proceso_etapa where idproceso = $idproceso and idetapa = $etapa)");
        return $this->db->query("SELECT pe.idrol,
                                        pe.orden,
                                        UPPER(CONCAT(e.nombre, ' - ', REPLACE( REPLACE(r.descripcion, 'Contabilidad Ingresos', 'CONTABILIDAD'), 'Contabilidad Egresos / Ingresos',  'CONTABILIDAD')  )) AS etapa_regresar,
                                        e.idetapa
                                FROM proceso p 
                                INNER JOIN proceso_etapa pe 
                                    ON pe.idproceso = p.idproceso
                                INNER JOIN etapas e 
                                    ON e.idetapa = pe.idetapa 
                                INNER JOIN roles r 
                                    ON FIND_IN_SET(r.idrol,pe.idrol) 
                                WHERE p.idproceso = $idproceso AND pe.orden < (SELECT orden FROM proceso_etapa where idproceso = $idproceso and idetapa = $etapa) AND pe.idetapa NOT IN ( 7 )
                                GROUP BY e.idetapa");
    }
    //@uthor Efraín Martinez Muñoz || Fecha: 31/01/2024 || Se crea la función proceso_etapas_mayor en la cual se obtienen la etapas que se encuentran
    //despues de la etapa actual en la que se encuentra la solicitud.
    function proceso_etapas_mayor($idproceso, $etapa, $idSolicitud)
    {
        $ultimaEtapa = $this->db->query("SELECT * 
                                 FROM log_efi AS le
                                 WHERE idsolicitud = ? 
                                 ORDER BY idefi DESC 
                                 LIMIT 1;", [$idSolicitud])->row()->idetapa;
        return $this->db->query("SELECT pe.idrol,
                                        pe.orden, 
                                        UPPER(CONCAT(e.nombre, ' - ', REPLACE( REPLACE(r.descripcion, 'Contabilidad Ingresos', 'CONTABILIDAD'), 'Contabilidad Egresos / Ingresos',  'CONTABILIDAD')  )) AS etapa_regresar,
                                        e.idetapa
                                FROM proceso p 
                                INNER JOIN proceso_etapa pe
                                    ON pe.idproceso = p.idproceso
                                INNER JOIN etapas e 
                                    ON e.idetapa = pe.idetapa 
                                INNER JOIN roles r 
                                    ON FIND_IN_SET(r.idrol,pe.idrol) 
                                WHERE p.idproceso = $idproceso AND pe.idetapa IN (?) AND pe.idetapa NOT IN ( 7 )
                                GROUP BY e.idetapa", [$ultimaEtapa]);
        // return $this->db->query("SELECT pe.idrol,
        //                                 pe.orden, 
        //                                 UPPER(CONCAT(e.nombre, ' - ', REPLACE( REPLACE(r.descripcion, 'Contabilidad Ingresos', 'CONTABILIDAD'), 'Contabilidad Egresos / Ingresos',  'CONTABILIDAD')  )) AS etapa_regresar,
        //                                 e.idetapa 
        //                         FROM proceso p 
        //                         INNER JOIN proceso_etapa pe
        //                             ON pe.idproceso = p.idproceso
        //                         INNER JOIN etapas e 
        //                             ON e.idetapa = pe.idetapa 
        //                         INNER JOIN roles r 
        //                             ON FIND_IN_SET(r.idrol,pe.idrol) 
        //                         WHERE p.idproceso = $idproceso AND pe.orden > (SELECT orden FROM proceso_etapa where idproceso = $idproceso and idetapa = $etapa) AND pe.idetapa NOT IN ( 7 )
        //                         GROUP BY e.idetapa");
    }
    function actualizar_etapa($etapa, $idsolicitud, $prioridad){
        // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
        return $this->db->update("solpagos", array(
            "idetapa" => $etapa,
            "prioridad" => $prioridad,
            "fecha_autorizacion" => date("Y-m-d H:i:s"),
            "idAutoriza" => $this->session->userdata("inicio_sesion")['id'],
        ),  "idsolicitud = '$idsolicitud'");
    }

    function actualizar_etapa_new($etapa, $idsolicitud, $prioridad, $empresa_ad_new, $etapa_conta_new)
    {
        // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
        return $this->db->update("solpagos", array(
            "idetapa" => $etapa,
            "prioridad" => $prioridad,
            "idEmpresa" => $empresa_ad_new,
            "etapa" => $etapa_conta_new,
            "fecha_autorizacion" => date("Y-m-d H:i:s"),
            "idAutoriza"=> $this->session->userdata("inicio_sesion")['id'],
        ),  "idsolicitud = '$idsolicitud'");
    }
    function notificacion($idsolicitud)
    {
        $this->db->insert("notificaciones", array("fecha" => date("Y-m-d h:i:s"), "visto" => 0, "idsolicitud" => $idsolicitud, "idusuario" => $this->session->userdata("inicio_sesion")['id']));
    }

    function cuenta_contable($info_sol, $cuenta_contable,$cuenta_orden)
    {
        // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
        return $this->db->update("solpagos", array(
            "homoclave" => $cuenta_contable,
            "orden_compra" => $cuenta_orden
        ),  "idsolicitud = '$info_sol'");
    }

    //AGREGAMOS UN NUEVO BANCO EXTRANJERO AL SISTEMA
    function Nuevo_banco_extra($nombre, $aba){
        $this->db->insert("bancos", array(
                "nombre" => $nombre,
                "clvbanco" => $aba,
                "estatus" => 2
            )
        );
        return  $this->db->insert_id();
    }

    function getSolicitud($idsolicitud)
    {
        return $this->db->query("SELECT
        *, SUM(total) AS pagado,
        @num := 1 + LENGTH(condominio) - LENGTH(REPLACE(condominio, '-', ''))              AS sum,
        IF(@num > 1, SUBSTRING_INDEX(condominio, '-', 1), NULL)                           AS etapas,
        IF(@num > 1, SUBSTRING_INDEX(SUBSTRING_INDEX(condominio, '-', 2), '-', -1), NULL) AS condominios,
        IF(@num > 2, SUBSTRING_INDEX(SUBSTRING_INDEX(condominio, '-', 3), '-', -1), NULL) AS lotes
    FROM
        solpagos
            INNER JOIN proceso 
            ON proceso.idproceso = solpagos.idproceso
            INNER JOIN
        (SELECT 
            empresas.rfc AS rfc_empresas, empresas.idempresa
        FROM
            empresas) empresas ON solpagos.idEmpresa = empresas.idempresa
            INNER JOIN
        (SELECT 
            proveedores.nombre AS nombre_proveedor,
                proveedores.idproveedor,
                proveedores.rfc AS rfc_proveedores,
                proveedores.estatus AS EstatusProv,
                proveedores.tipo_prov AS TipoProv,
                bancos.nombre AS nombre_banco,
                bancos.clvbanco AS clave_banco,
                proveedores.sucursal AS sucursal_p,
                proveedores.tipocta,
                proveedores.idbanco,
            proveedores.cuenta AS cuenta_p
        FROM
            proveedores LEFT JOIN bancos ON proveedores.idbanco = bancos.idbanco) proveedores ON proveedores.idproveedor = solpagos.idProveedor
            LEFT JOIN
        (SELECT 
            *, MIN(facturas.feccrea)
        FROM
            facturas
        WHERE
            facturas.tipo_factura IN (1 , 3)
        GROUP BY facturas.idsolicitud) AS facturas ON facturas.idsolicitud = solpagos.idsolicitud
        LEFT JOIN motivo_sol_dev ON motivo_sol_dev.idsolicitud = solpagos.idsolicitud
    WHERE
        solpagos.idsolicitud =  '$idsolicitud'");
    }

    //OBTENEMOS LA INFORMCION PARA SOLICITUD PARA MOSTRAR PARA ADMON.

    /**
     * @uthor Efrain Martinez Muñoz | Fecha : 27/03/2025 
     * Se agrega un LEFT JOIN a esta consulta en la cual obtiene datos de las tablas (historial_documento, etiquetas, etiqueta_sol), y se agregan las columnas idetiqueta,
     * estatusLote, tipo_ doc, idusuario, expediente, idDocumneto, y rol  que se utilizaran para permitir la actualizacion y carga de la imagen de la solicitud.
     */
    function getSolicitudadm($idsolicitud){
        return $this->db->query("SELECT solpagos.*,
            cap.nombre_completo capturista,
            proceso.nombre nombre_proceso,
            proceso.motivo proceso_motivo, -- FECHA: 20-SEPTIEMBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com>
            proveedores.nombre nomprove,
            proveedores.tipocta,
            proveedores.cuenta,
            empresas.nempresa, 
            bancos.idbanco,
            bancos.nombre nomban,
            bancos.clvbanco,
            mot.costo_lote,
            mot.superficie,
            mot.preciom,
            mot.predial,
            mot.por_penalizacion,
            mot.penalizacion,
            mot.importe_aportado,
            mot.motivo,
            mot.mantenimiento,
            mot.detalle_motivo, -- INICIO FECHA: 20-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com>
            IF(@num > 1, SUBSTRING_INDEX(condominio, '-', 1), NULL) etapas,
            solpagos.idproceso,
            solpagos.fecha_fin,
            solpagos.fecelab,
            solpagos.programado,
            sp.tipoParcialidad, 
            CASE sp.tipoParcialidad
                WHEN 'T' THEN
                    'TIEMPO'
                WHEN 'M' THEN
                    'MONTO'
                WHEN 'MA' THEN 
                    'MANUAL'
                ELSE 
                    'SIN DEFINR'
            END AS caratulaTipoparcialidad,
            CASE
                WHEN IFNULL(ap.numPagosCompletos, 0) < IFNULL((sp.numeroParcialidades - 1), 0) THEN 
                    sp.montoParcialidad
                WHEN IFNULL(ap.numPagosCompletos, 0) >= IFNULL((sp.numeroParcialidades - 1), 0) THEN 
                    solpagos.cantidad - ap.cantidadTotal
            END AS montoParcialidadCaratula,
            sp.montoParcialidad,
            sp.numeroParcialidades AS numeroPagos,
            solpagos.cantidad,
            numPagosCompletos AS pagoActual,
            eti.idetiqueta,
            eti.etiqueta AS estatusLote,
            eti.tipo_doc,
            solpagos.idusuario,
            eti.expediente,
            eti.idDocumento,
            eti.rol,
            sdrl.iddesarrollo, /** FECHA: 07-ABRIL-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
            sdrl.isDesarrolloManual, /** FECHA: 08-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
            sdrl.idcondominio, /** FECHA: 07-ABRIL-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
            sdrl.isCondominioManual, /** FECHA: 08-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
            sdrl.idlote, /** FECHA: 07-ABRIL-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
            sdrl.isLoteManual, /** FECHA: 08-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
            sdrl.referencia, /** FECHA: 07-ABRIL-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
            sdrl.isReferenciaManual, /** FECHA: 08-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
            pp.idproceso_proyecto
        FROM
            solpagos
        CROSS JOIN listado_usuarios cap ON cap.idusuario = solpagos.idusuario
        LEFT JOIN proceso ON proceso.idproceso = solpagos.idproceso
        CROSS JOIN ( SELECT empresas.rfc rfc_empresas, nombre nempresa, empresas.idempresa FROM empresas) empresas ON solpagos.idEmpresa = empresas.idempresa
        CROSS JOIN ( SELECT idproveedor, nombre, idbanco, cuenta, tipocta FROM proveedores ) proveedores ON proveedores.idproveedor = solpagos.idProveedor 
        LEFT JOIN bancos ON proveedores.idbanco = bancos.idbanco 
        LEFT JOIN ( SELECT * FROM motivo_sol_dev ) mot ON mot.idsolicitud = solpagos.idsolicitud
        LEFT JOIN solicitudesParcialidades sp on sp.idsolicitud = solpagos.idsolicitud 
        LEFT JOIN (SELECT idsolicitud, SUM(cantidad) AS cantidadTotal, COUNT(idsolicitud) AS numPagosCompletos 
                   FROM autpagos 
                   WHERE idsolicitud = ? AND estatus = 16 AND fecha_cobro IS NOT NULL AND fechaDis IS NOT NULL
                   GROUP BY idsolicitud) AS ap
            ON solpagos.idsolicitud = ap.idsolicitud
        LEFT JOIN catalogo_proyecto AS cp
            ON solpagos.proyecto = cp.concepto
        LEFT JOIN proceso_proyecto AS pp
            ON solpagos.idproceso = pp.idproceso AND pp.idproyecto = cp.idproyecto AND pp.estatus = 1
        LEFT JOIN (
                        SELECT esol.idetiqueta,
                            e.etiqueta,
                            esol.idsolicitud,
                            esol.rol,
                            hd.tipo_doc,
                            hd.expediente,
                            hd.idDocumento
                        FROM etiqueta_sol esol
                            JOIN etiquetas e ON esol.idetiqueta = e.idetiqueta
                            AND esol.rol IS NOT NULL
                            LEFT JOIN historial_documento hd ON esol.idsolicitud = hd.idsolicitud
                            AND hd.tipo_doc = 9
                    ) eti ON eti.idsolicitud = solpagos.idsolicitud
        LEFT JOIN solicitud_drl sdrl ON sdrl.idsolicitud = solpagos.idsolicitud /** FECHA: 24-MARZO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
        WHERE solpagos.idsolicitud = ?", [ $idsolicitud, $idsolicitud ]);
    }

    function getProxEtapa( $idsolicitud ){
        return $this->db->query("SELECT 
                s.*,
                pe.orden,
                pe.sig_orden,
                pe2.idetapa sig_etapa
            FROM solpagos s
            INNER JOIN 
            (
                SELECT 
                    pe.idproceso,
                    pe.idetapa,
                    pe.orden,
                    pe.orden + 1 sig_orden
                FROM solpagos s
                INNER JOIN proceso_etapa pe ON s.idproceso = pe.idproceso AND s.idetapa = pe.idetapa AND FIND_IN_SET( ?, idrol)
                WHERE s.idsolicitud = ?
            ) pe ON pe.idproceso = s.idproceso AND s.idetapa = pe.idetapa
            INNER JOIN proceso_etapa pe2 ON pe2.idproceso = s.idproceso AND pe2.orden = pe.sig_orden
            WHERE s.idsolicitud = ?", [ $this->session->userdata("inicio_sesion")['rol'], $idsolicitud, $idsolicitud ] );
    }

    //AVANZA LOS REGISTRO A LA SIGUIENTE ETAPA ASEGURANDO QUE EL LA SOLICITUD ESTE EN LA ETPA CORRESPONDIENTE A LA ETAPA
    function avanzar2_0( $idsolicitudes ){
        
        // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
        $idusuario = $this->session->userdata("inicio_sesion")['id'];
        $rol_usuario = $this->session->userdata("inicio_sesion")['rol'];
        $fecha_avance = date("Y-m-d H:i:s");

        return $this->db->query("UPDATE solpagos sp
            INNER JOIN 
            (
                SELECT 
                    pe.idproceso,
                    pe.idetapa,
                    pe.orden,
                    pe.orden + 1 sig_orden
                FROM solpagos s
                INNER JOIN proceso_etapa pe 
                    ON s.idproceso = pe.idproceso AND s.idetapa = pe.idetapa AND (FIND_IN_SET( '$rol_usuario', idrol) OR FIND_IN_SET( '$idusuario', idpermitido))
                WHERE s.idsolicitud = $idsolicitudes
            ) pe ON pe.idproceso = sp.idproceso AND sp.idetapa = sp.idetapa
            INNER JOIN ( SELECT idproceso, orden sig_orden, idetapa sig_etapa FROM proceso_etapa ) pe2 
                ON pe2.idproceso = sp.idproceso AND pe2.sig_orden = pe.sig_orden
            SET sp.idetapa = pe2.sig_etapa, fecha_autorizacion = '$fecha_avance', idAutoriza = $idusuario 
            WHERE sp.idsolicitud IN ($idsolicitudes) AND sp.idproceso IS NOT NULL");
    }
    
    function updateAutpago( $idSol){
        // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
        $idusuario = $this->session->userdata("inicio_sesion")['id'];
        $fecha = date("Y-m-d H:i:s");
        //$this->db->query("UPDATE autpagos SET idrealiza = $idusuario, fecreg = '$fecha' WHERE idsolicitud= '$idSol'");
        $this->db->query("UPDATE autpagos 
        INNER JOIN ( SELECT idsolicitud, idetapa, fecha_autorizacion FROM solpagos WHERE idsolicitud = '$idSol' AND idetapa IN (11, 81, 80) ) s ON s.idsolicitud = autpagos.idsolicitud 
            SET autpagos.idrealiza = $idusuario, 
                autpagos.fecreg = s.fecha_autorizacion 
        WHERE autpagos.idsolicitud= '$idSol' AND autpagos.idrealiza = 0 AND autpagos.fecreg = '0000-00-00 00:00:00'");
    }
 
    function condominio_info($idsolicitudes)
    {
        $this->db->query("SELECT
        @num := 1 + LENGTH(condominio) - LENGTH(REPLACE(condominio, '_', ''))              AS sum,
        IF(@num > 1, SUBSTRING_INDEX(condominio, '_', 1), NULL)                           AS etapa,
        IF(@num > 1, SUBSTRING_INDEX(SUBSTRING_INDEX(condominio, '_', 2), '_', -1), NULL) AS condominio,
        IF(@num > 2, SUBSTRING_INDEX(SUBSTRING_INDEX(condominio, '_', 3), '_', -1), NULL) AS lote
        FROM solpagos
        WHERE idsolicitud = $idsolicitudes");
    }

     /**
     * @uthor Efrain Martinez Muñoz | Fecha : 27/03/2025 
     * Se agrega un LEFT JOIN a esta consulta en la cual obtiene datos de las tablas (historial_documento, etiquetas, etiqueta_sol), y se agregan las columnas idetiqueta,
     * estatusLote y tipo_ doc  estatusLote y tipo_ doc que se utilizaran para mostrar los botones en la vista.
     */
    function get_solicitudes_en_curso($finicial, $ffinal){
        return $this->db->query("SELECT
            s.idsolicitud,
            s.prioridad,
            s.condominio,
            IFNULL(sdrl.referencia, 'NA') AS referencia, /** FECHA: 07-ABRIL-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
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
            CASE                    /**  Para el listadde solicitudes activas se modifico para considerar las solicitudes de devoluciones en parcialidade | FECHA: 16-Mayo-2024 | @author Lalo Silva <programador.analista38@ciudadmaderas.com>  **/
                WHEN s.idproceso = 30 AND s.programado IS NOT NULL THEN 
                    s.cantidad
                WHEN s.idproceso <> 30 AND s.programado IS NOT NULL THEN
                    s.cantidad + IFNULL(cant_pag.pagado, 0)
                ELSE
                    s.cantidad
            END cantidad,
            s.moneda, 
            CASE
                WHEN s.idproceso = 30 THEN IFNULL(pagosDevoluciones.cantidad,0)
                ELSE IFNULL(cant_pag.pagado, 0)
            END pagado,             /**  FIN Para el listadde solicitudes activas se modifico para considerar las solicitudes de devoluciones en parcialidade | FECHA: 16-Mayo-2024 | @author Lalo Silva <programador.analista38@ciudadmaderas.com>  **/
            etapas.nombre etapa_estatus, 
            IFNULL(DATE(s.fecha_autorizacion), '--') AS fecha_autorizacion, 
            s.folio,
            s.fecha_autorizacion,
            s.fechaCreacion,
            aut.nombre_completo nautoriza,
            IF(msd.motivo IS NULL OR msd.motivo = '', 'NA', msd.motivo) AS motivo, -- FECHA: 17-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com>
            IF(msd.detalle_motivo IS NULL OR msd.detalle_motivo = '', 'NA', msd.detalle_motivo) AS detalle_motivo, -- FECHA: 20-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com>
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
            IFNULL(numCancelados , 0) AS numCancelados,
            eti.idetiqueta,
            eti.etiqueta AS estatusLote,
            eti.tipo_doc
        FROM (
                SELECT 
                    solpagos.*
                FROM ( SELECT * FROM  solpagos WHERE solpagos.idproceso IS NOT NULL AND solpagos.idetapa NOT IN ( 0, 1, 30, 10, 11 ) AND solpagos.fechaCreacion BETWEEN '$finicial' AND '$ffinal' ) solpagos
                JOIN ( SELECT departamento FROM departamentos INNER JOIN usuario_depto ON iddepartamentos = iddepartamento WHERE usuario_depto.idusuario = ? ) depto ON depto.departamento = solpagos.nomdepto
            UNION
            SELECT 
                    solpagos.*
                FROM ( SELECT * FROM  solpagos WHERE solpagos.idproceso IS NOT NULL AND solpagos.prioridad = 1 AND solpagos.idetapa IN ( 1 ) AND solpagos.fechaCreacion BETWEEN '$finicial' AND '$ffinal' ) solpagos
                JOIN ( SELECT departamento FROM departamentos INNER JOIN usuario_depto ON iddepartamentos = iddepartamento WHERE usuario_depto.idusuario = ? ) depto ON depto.departamento = solpagos.nomdepto
        ) s 
        CROSS JOIN listado_usuarios capturista ON capturista.idusuario = s.idusuario 
        CROSS JOIN empresas ON s.idEmpresa = empresas.idempresa 
        CROSS JOIN proveedores ON proveedores.idproveedor = s.idProveedor 
        CROSS JOIN etapas ON etapas.idetapa = s.idetapa 
        CROSS JOIN proceso pr ON s.idproceso = pr.idproceso
        CROSS JOIN listado_usuarios aut ON s.idautoriza = aut.idusuario
        LEFT JOIN motivo_sol_dev msd ON msd.idsolicitud = s.idsolicitud
        LEFT JOIN vw_autpagos cant_pag ON cant_pag.idsolicitud = s.idsolicitud 
        LEFT JOIN (SELECT idsolicitud, SUM(diastrans) diasTrans, SUM(tipoavance) AS numCancelados FROM log_efi 
        GROUP BY idsolicitud) log_efi ON log_efi.idsolicitud = s.idsolicitud
        LEFT JOIN (             /**  Para el listadde solicitudes activas se modifico para considerar las solicitudes de devoluciones en parcialidade | FECHA: 16-Mayo-2024 | @author Lalo Silva <programador.analista38@ciudadmaderas.com>  **/
                SELECT
                    ad.idsolicitud,
                    ifnull(count(ad.idpago), 0) pagos,
                    SUM(cantidad) AS cantidad
                from
                    autpagos ad
				group by idsolicitud
            ) pagosDevoluciones on pagosDevoluciones.idsolicitud = s.idsolicitud /**  FIN Para el listadde solicitudes activas se modifico para considerar las solicitudes de devoluciones en parcialidade | FECHA: 16-Mayo-2024 | @author Lalo Silva <programador.analista38@ciudadmaderas.com>  **/
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
                    ) eti ON eti.idsolicitud = s.idsolicitud
        LEFT JOIN solicitud_drl sdrl ON sdrl.idsolicitud = s.idsolicitud /** FECHA: 07-ABRIL-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
        ORDER BY s.fechaCreacion",
            array( $this->session->userdata("inicio_sesion")['id'], $this->session->userdata("inicio_sesion")['id']) );
    }

    function solicitudes_formato()
    {
        switch ($this->session->userdata("inicio_sesion")['rol']) {
            case 'DA':

            case 'CP':
                return $this->db->query("SELECT solpagos.idsolicitud FROM solpagos WHERE solpagos.idusuario = '" . $this->session->userdata("inicio_sesion")['id'] . "' AND solpagos.idetapa IN ( 1, 3 )");
                break;
            case 'PV':
            case 'AD':
            case 'CAD':
            case 'GAD':
            case 'CPV':
                return $this->db->query("SELECT solpagos.idsolicitud FROM solpagos WHERE solpagos.idusuario = '" . $this->session->userdata("inicio_sesion")['id'] . "' AND solpagos.idetapa = 1");
                break;
            case 'SU':
                return $this->db->query("SELECT solpagos.idsolicitud FROM solpagos WHERE solpagos.nomdepto = ? AND solpagos.idetapa IN ( 1, 3 )", array($this->session->userdata("inicio_sesion")['depto']));
                break;
            case 'SB':
            case 'CC':
                return $this->db->query("SELECT solpagos.idsolicitud FROM solpagos WHERE solpagos.nomdepto = '" . $this->session->userdata("inicio_sesion")['depto'] . "' AND solpagos.idetapa = 3");
                break;
            case 'AS':
            case 'CA':
            case 'FP':
                return $this->db->query("SELECT solpagos.idsolicitud FROM solpagos WHERE solpagos.idusuario = '" . $this->session->userdata("inicio_sesion")['id'] . "' AND solpagos.idetapa IN ( 1, 3 )");
                break;
            case 'CJ':
                return $this->db->query("SELECT solpagos.idsolicitud FROM solpagos WHERE ( ( solpagos.idResponsable = '" . $this->session->userdata("inicio_sesion")['id'] . "' AND solpagos.caja_chica = 1 ) OR solpagos.idusuario = '" . $this->session->userdata("inicio_sesion")['id'] . "' ) AND solpagos.idetapa IN ( 1, 3 )");
                break;
            case 'CE':
            case 'CX':
                return $this->db->query("SELECT solpagos.idsolicitud FROM solpagos WHERE ( solpagos.idAutoriza = '" . $this->session->userdata("inicio_sesion")['id'] . "' ) AND solpagos.idetapa IN ( 3 )");
                break;
        }
    }

    
    function obtenerSolCompra_AutM($filtro)
    {
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
            ORDER BY s.fecelab", array($this->session->userdata("inicio_sesion")['id']));
    }

    function obtenerSolPendientesProcesos(){
    	$filtroRol = $this->session->userdata("inicio_sesion")['rol'];
		if($this->session->userdata('inicio_sesion')["id"]=='1835'){
			$filtroRol = 'SU';
		}
        return $this->db->query("SELECT 
            solpagos.idsolicitud,
            1 visto,
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
            solpagos.idproceso, 
            IFNULL(autpagos.upago, '') AS FECHAU,
            IF(solpagos.idproceso = 30 AND solpagos.programado IS NOT NULL, 'S', 'N') AS esParcialidad,
            solpagos.programado, 
            sp.numeroParcialidades numeroPagos,
            solpagos.cantidad AS Cantidad,
            IFNULL(autpagos.pagado, 0) AS Autorizado,
            IFNULL(solpagos.fecha_autorizacion, '') AS fecha_autorizacion,
            solpagos.prioridad,
            sp.montoParcialidad,
            IFNULL(0, ap.pagosAutorizados) AS pagosAutorizados,
            IFNULL(0, ap.totalAutorizado) AS totalAutorizado
        FROM
            ( 
                SELECT 
                    solpagos.*
                FROM
                    solpagos
                JOIN ( SELECT idproceso, idetapa FROM proceso_etapa WHERE idrol = ? GROUP BY idproceso, idrol, idetapa  ) proceso ON proceso.idetapa = solpagos.idetapa AND proceso.idproceso = solpagos.idproceso
            ) solpagos
            CROSS JOIN empresas ON empresas.idempresa = solpagos.idEmpresa
            CROSS JOIN listado_usuarios capturista ON capturista.idusuario = solpagos.idusuario
            CROSS JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor
            LEFT JOIN vw_autpagos autpagos ON solpagos.idsolicitud = autpagos.idsolicitud
            LEFT JOIN listado_usuarios dg ON dg.idusuario = solpagos.idAutoriza
            LEFT JOIN solicitudesParcialidades sp on sp.idsolicitud = solpagos.idsolicitud
            LEFT JOIN (SELECT idsolicitud,
                              COUNT(idsolicitud) AS pagosAutorizados,
                              SUM(cantidad) AS totalAutorizado 
                       FROM autpagos
                       WHERE idsolicitud IN (SELECT idsolicitud FROM solpagos WHERE idproceso IS NOT NULL)
                       GROUP BY idsolicitud) AS ap
                ON ap.idsolicitud = solpagos.idsolicitud
            ORDER BY solpagos.prioridad DESC", array($filtroRol));
        //385, obtener etapas segun su rol
    }

    function get_info_proceso($idproceso)
    {
        return $this->db->query("SELECT * FROM proceso WHERE idproceso = '$idproceso'");
    }
    function mas_pv($data)
    {
        $this->db->insert("motivo_sol_dev", $data);
    }
    function reactivar_prov($id_proveedor){
        // FECHA : 01-JULIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
        $fecha = date("Y-m-d h:i:s");
        /**
         * FECHA : 01-JULIO-2025 | @author Efrain Martinez progamador.analista38@ciudadmaderas.com | SE AGREGA EL ID DEL USUARIO QUE REALIZO LA ACTUALIZACION
         */
        $this->db->query("UPDATE proveedores SET estatus = 2, idupdate = ?, fecha_update = '$fecha' WHERE idproveedor = ?",[$this->session->userdata("inicio_sesion")['id'],$id_proveedor]);
    }
    function mas_proveedores($data)
    {
        $this->db->insert("proveedores", $data);
       return $this->db->insert_id();
    }
    function update_mas_proveedores($data,$idproveedor)
    {
        // FECHA : 01-JULIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
        $this->db->update("proveedores", $data , "idproveedor = '$idproveedor'");
        // $this->db->insert("proveedores", $data);
    //    return $this->db->insert_id();
    }
    function update_mas_pv($data,$idsol)
    {
        // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
        $this->db->update("motivo_sol_dev", $data , "idsolicitud = '$idsol'");
    }

    function validar_solpagos_estatus( $idrol, $idsolicitud ){
        $data = [ $idrol, $this->session->userdata('inicio_sesion')["id"], $this->session->userdata('inicio_sesion')["id"], $idsolicitud ];
        return $this->db->query("SELECT idsolicitud 
                                 FROM solpagos s
                                 INNER JOIN (SELECT idetapa, idproceso
                                             FROM proceso_etapa
                                             WHERE	(FIND_IN_SET( ?, idrol) OR FIND_IN_SET(?, idpermitido) ) AND
                                                    (idnopermitido IS NULL OR NOT FIND_IN_SET(?, idnopermitido)) ) ep 
                                     ON ep.idetapa = s.idetapa AND s.idproceso = ep.idproceso AND s.idsolicitud = ?", $data );
    }
    // function update_copropietario($idsolicitud,$copropiedad){
    //     $this->db->query("UPDATE solpagos SET rcompra = '$copropiedad' WHERE idsolicitud = '$idsolicitud' ");

    // }

    // function update_motivo($idsolicitud,$motivo){
    //     $this->db->query("DELETE FROM motivo_sol_dev WHERE idsolicitud = '$idsolicitud' ");
    //     $this->db->insert("motivo_sol_dev" , array(
    //         "idsolicitud" => $idsolicitud,
    //         "motivo" => $motivo
    //     ));

    // }
    /**  Consulta que obtiene el listado para el historial de las solicitudes de parcialidades | FECHA: 16-Mayo-2024 | @author Lalo Silva <programador.analista38@ciudadmaderas.com>  **/
    function obtenerHistrialParcialidades($fechaInicial, $fechaFinal, $tipoReporte){
        if(!$fechaFinal) $fechaFinal = date("Y-m-d");
        else $fechaFinal = implode("-", array_reverse(explode("/", $fechaFinal)));
        
                
        if(!$fechaInicial) $fechaInicial = date('Y-m-d', strtotime('-1 year'));
        else $fechaInicial = implode("-", array_reverse(explode("/", $fechaInicial)));
        
        $filtroReporte = "AND sp.idetapa <> 1";
        if($tipoReporte == "PC") $filtroReporte = "AND sp.idetapa = 11";
        if($tipoReporte == "EC") $filtroReporte = "AND sp.idetapa not in (1, 11, 30, 0)";
        if($tipoReporte == "CC") $filtroReporte = "AND sp.idetapa in (0, 30)";

        return $this->db->query("SELECT sp.idsolicitud,
                                        case
                                            WHEN sp.programado = 1 THEN 'MENSULAL'
                                            WHEN sp.programado = 2 THEN 'BIMESTRAL'
                                            WHEN sp.programado = 3 THEN 'TRIMESTRAL'
                                            WHEN sp.programado = 4 THEN 'CUATRIMESTRAL'
                                            WHEN sp.programado = 6 THEN 'SEMESTRAL'
                                            WHEN sp.programado = 7 THEN 'SEMANAL'
                                            WHEN sp.programado = 8 THEN 'QUINCENAL'
                                        END periodo,
                                        pr.nombre proveedor,
                                        sp.cantidad,
                                        spa.numeroParcialidades AS totalPagos,
                                        sp.metoPago,
                                        ifnull(pagosDevoluciones.pagos, 0) pagos,
                                        sp.fecha_autorizacion,
                                        spa.montoParcialidad AS parcialidad,
                                        pagosDevoluciones.pagado pagado,
                                        sp.fecelab,
                                        sp.fecelab fechaInicio,
                                        sp.fecha_fin fechaFin,
                                        CASE 
                                            WHEN sp.programado = 8 AND sp.idetapa not in (11, 30) THEN
                                                IF( pagos >= TIMESTAMPDIFF(DAY,
                                                        sp.fecelab,
                                                        sp.fecha_fin) / 15,
                                                    sp.fecha_fin,
                                                    CASE
                                                    WHEN WEEKDAY(DATE_ADD(sp.fecelab, INTERVAL (IFNULL(pagos, 0) * 15) DAY)) = 5 THEN 
                                                                DATE_ADD(sp.fecelab, INTERVAL (IFNULL(pagos, 0) * 15) DAY)
                                                    WHEN WEEKDAY(DATE_ADD(sp.fecelab, INTERVAL (IFNULL(pagos, 0) * 15) DAY)) = 6 THEN 
                                                                DATE_ADD(sp.fecelab, INTERVAL (IFNULL(pagos, 0) * 15) DAY)
                                                    ELSE
                                                    DATE_ADD(sp.fecelab, INTERVAL (IFNULL(pagos, 0) * 15) DAY)
                                                    END)
                                            WHEN sp.programado = 7 AND sp.idetapa NOT IN (11, 30) THEN 
                                                CASE
                                                    WHEN WEEKDAY(DATE_ADD( sp.fecelab, INTERVAL ( IFNULL(pagos, 0) * sp.programado ) MONTH )) = 5 THEN 
                                                    DATE_ADD(DATE_ADD( sp.fecelab, INTERVAL ( IFNULL(pagos, 0) * sp.programado ) MONTH), interval 2 DAY)
                                                    WHEN WEEKDAY(DATE_ADD( sp.fecelab, INTERVAL ( IFNULL(pagos, 0) * sp.programado ) MONTH ) ) = 6 THEN 
                                                    DATE_ADD(DATE_ADD( sp.fecelab, INTERVAL ( IFNULL(pagos, 0) * sp.programado ) MONTH), interval 1 DAY)
                                                    ELSE 
                                                        DATE_ADD( sp.fecelab, INTERVAL ( IFNULL(pagos, 0) * sp.programado ) MONTH)
                                                END
                                            WHEN sp.programado > 7 AND sp.idetapa NOT IN (11, 30) THEN
                                            CASE
                                                WHEN WEEKDAY(DATE_ADD( sp.fecelab, INTERVAL IFNULL(pagos, 0) WEEK )) = 5 THEN 
                                                            DATE_ADD(DATE_ADD( sp.fecelab, INTERVAL IFNULL(pagos, 0) WEEK ), interval 2 DAY)
                                                WHEN WEEKDAY(DATE_ADD( sp.fecelab, INTERVAL IFNULL(pagos, 0) WEEK ) ) = 6 THEN 
                                                            DATE_ADD(DATE_ADD( sp.fecelab, INTERVAL IFNULL(pagos, 0) WEEK ), interval 1 DAY)
                                                ELSE
                                                DATE_ADD( sp.fecelab, INTERVAL IFNULL(pagos, 0) WEEK )
                                            END
                                            WHEN sp.idetapa IN (11, 30) THEN '-'
                                        END proximo_pago,
                                        IF(sp.idetapa = 0, 'ELIMINADA', et.nombre)  etapa,
                                        sp.idetapa,
                                        sp.condominio lote,
                                        sp.idproceso,
                                        pagosAutorizados.numPagosAutorizados,
                                        pagosAutorizados.totalAutorizado,
                                        sp.programado,
                                        pagosAutorizados.ultimoPagoAut,
                                        estatus_pago.estatus_ultimo_pago,
                                        CASE 
                                            WHEN pagosDevoluciones.pagos = (spa.numeroParcialidades) THEN
                                                pagosAutorizados.ultimoPagoAut
                                            WHEN IFNULL(pagosDevoluciones.pagos, 0) = (IF(spa.numeroParcialidades > 1, spa.numeroParcialidades - 1, spa.numeroParcialidades)) THEN
                                                sp.cantidad - pagosDevoluciones.pagado
                                            WHEN sp.idetapa IN (0, 30) AND IFNULL(pagosDevoluciones.pagos, 0) = 0 THEN
                                                spa.montoParcialidad
                                            WHEN IFNULL(pagosDevoluciones.pagos, 0) < (spa.numeroParcialidades) THEN
                                                spa.montoParcialidad
                                        END ultimoPagoParcialidades,
                                        CASE 
                                            WHEN pagosDevoluciones.pagos = (spa.numeroParcialidades) THEN
                                                pagosDevoluciones.pagos
                                            WHEN IFNULL(pagosDevoluciones.pagos, 0) < (spa.numeroParcialidades) THEN
                                                (IFNULL(pagosDevoluciones.pagos, 0) + 1)
                                        END AS pagoPlanPagos
                                FROM solpagos AS sp
                                LEFT JOIN proveedores pr ON sp.idproveedor = pr.idproveedor
                                LEFT JOIN etapas et ON et.idetapa = sp.idetapa
                                LEFT JOIN ( SELECT ap.idsolicitud, IFNULL(count(ap.idpago), 0) pagos, sum(ap.cantidad) pagado
                                            FROM autpagos ap
                                            WHERE ap.estatus = 16
                                            GROUP BY idsolicitud) AS pagosDevoluciones
                                    ON pagosDevoluciones.idsolicitud = sp.idsolicitud
                                LEFT JOIN ( SELECT  ap.idsolicitud, IFNULL(COUNT(ap.idpago), 0) AS numPagosAutorizados, 
                                                    SUM(ap.cantidad) AS totalAutorizado, 
                                                    (SELECT cantidad FROM autpagos AS ap2 WHERE ap2.idsolicitud = ap.idsolicitud ORDER BY ap2.idpago DESC LIMIT 1) AS ultimoPagoAut
                                            FROM autpagos ap
                                            GROUP BY idsolicitud) AS pagosAutorizados
                                    ON pagosAutorizados.idsolicitud = sp.idsolicitud
                                LEFT JOIN ( SELECT autpagos.idsolicitud, autpagos.estatus AS estatus_ultimo_pago
                                            FROM autpagos 
                                            GROUP BY autpagos.idsolicitud 
                                            HAVING MAX(autpagos.idpago) ) AS estatus_pago 
                                    ON estatus_pago.idsolicitud = sp.idsolicitud
                                LEFT JOIN solicitudesParcialidades spa ON spa.idsolicitud = sp.idsolicitud    
                                WHERE sp.idproceso = 30 AND sp.fechaCreacion BETWEEN '$fechaInicial 00:00:00' AND '$fechaFinal 23:59:59' $filtroReporte");
    }
    /** FIN Consulta que obtiene el listado para el historial de las solicitudes de parcialidades | FECHA: 16-Mayo-2024 | @author Lalo Silva <programador.analista38@ciudadmaderas.com>  **/

    function obtenerHistrialParcialidadesPagos($idsolicitud){
        return $this->db->query("SELECT CONCAT(u.nombres,' ', u.apellidos) usuario, 
                                        ad.idpago,
                                        ad.cantidad,
                                        ad.fecreg fechaPV,
                                        ad.fecha_pago fechaOperacion,
                                        ifnull(ad.formaPago, ad.tipoPago) tipoPago,
                                        ad.fecha_Pago AS fechaPago
                                FROM autpagos ad
                                LEFT JOIN usuarios u ON ad.idrealiza = idusuario
                                WHERE ad.idsolicitud = $idsolicitud
                                ORDER BY ad.fechacp ASC");
    }

    function guardarSolicitudesParcialidades($data)
    {
        $this->db->insert("solicitudesParcialidades", $data );
    }

    function obtenerRegistroParcialidades($idsolicitud)
    {
        return $this->db->query("SELECT
            * 
        FROM solicitudesParcialidades 
        WHERE idsolicitud = $idsolicitud");
    }
    
    function actualizarRegistroParcialidades($data, $idsolicitud)
    {
        // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
        return $this->db->update("solicitudesParcialidades", $data, "idsolicitud = $idsolicitud");
    }
    
    function eliminacionRegistroParcialidades($idsolicitud)
    {
        return $this->db->query("DELETE FROM solicitudesParcialidades WHERE idsolicitud = $idsolicitud");
    }
    
    function updateFormaPago($tabla, $data, $where){
        return $this->db->update("$tabla", $data, $where);
    }

    function insertAutpagosDevolucion($data){
        return $this->db->insert("autpagos ", $data);
    }

    function obtenerParcialidad($idsolicitud){
        return $this->db->query("SELECT 
                if(ptotales = (spa.numeroParcialidades - 1), sp.cantidad - IFNULL(autpagos.cantidad_confirmada, 0), spa.montoParcialidad) parcialidad
            FROM solpagos sp
            LEFT JOIN ( SELECT 
                    autpagos.idsolicitud, 
                    COUNT(autpagos.idpago) ptotales,
                    0 AS tparcial, 
                    SUM(autpagos.cantidad) AS cantidad_confirmada, 
                    MAX( autpagos.fecreg ) AS ultimo_pago 
                FROM autpagos GROUP BY autpagos.idsolicitud  ) autpagos ON sp.idsolicitud = autpagos.idsolicitud
            LEFT JOIN solicitudesParcialidades spa on spa.idsolicitud = sp.idsolicitud 
            WHERE sp.idsolicitud = $idsolicitud");
    }

    function updateAutpagoDevolucionParcialidad($idsolicitud, $metoPago){
        // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
        $pagos = $this->db->query("SELECT * FROM autpagos WHERE idsolicitud = $idsolicitud");

        if($pagos->num_rows() == 1){
            return $this->db->query("UPDATE autpagos SET tipoPago = '$metoPago' where idsolicitud = $idsolicitud");
        }
    }

    function updateEtapaSolpagos($idsolicitud, $idetapa){
        // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
        return $this->db->update("solpagos", array("idetapa" => $idetapa), "idsolicitud = ".$idsolicitud);

    }

    function obtenerHistrialParcialidadesPagosDesglosado($fechaInicial, $fechaFinal, $tipoReporte){
        if(!$fechaFinal) $fechaFinal = date("Y-m-d");
        else $fechaFinal = implode("-", array_reverse(explode("/", $fechaFinal)));
        
                
        if(!$fechaInicial) $fechaInicial = date('Y-m-d', strtotime('-1 year'));
        else $fechaInicial = implode("-", array_reverse(explode("/", $fechaInicial)));
        
        $filtroReporte = "AND sp.idetapa <> 1";
        if($tipoReporte == "PC") $filtroReporte = "AND sp.idetapa = 11";
        if($tipoReporte == "EC") $filtroReporte = "AND sp.idetapa not in (1, 11, 30)";
        if($tipoReporte == "CC") $filtroReporte = "AND sp.idetapa in (0, 30)";

        
        return $this->db->query("SELECT
            ap.idpago, 
            sp.idsolicitud,
            case
                WHEN sp.programado = 1 THEN 'MENSULAL'
                WHEN sp.programado = 2 THEN 'BIMESTRAL'
                WHEN sp.programado = 3 THEN 'TRIMESTRAL'
                WHEN sp.programado = 4 THEN 'CUATRIMESTRAL'
                WHEN sp.programado = 6 THEN 'SEMESTRAL'
                WHEN sp.programado = 7 THEN 'SEMANAL'
                WHEN sp.programado = 8 THEN 'QUINCENAL'
            END periodo,
            pr.nombre proveedor,
            sp.condominio lote,
            sp.cantidad,
            IFNULL(ap.formaPago, ap.tipoPago) tipoPago, 
            sp.fecha_autorizacion,
            ap.cantidad parcialidad,
            ap.fecreg fechaPV,
            ap.fechaDis,
            ap.fecha_cobro,
            et.etapa_pago
        FROM autpagos ap
        LEFT JOIN solpagos sp on ap.idsolicitud = sp.idsolicitud
        LEFT JOIN proveedores pr on sp.idproveedor = pr.idproveedor
        LEFT JOIN etapas_pago et on ap.estatus = et.estatus
        LEFT JOIN solicitudesParcialidades spa ON spa.idsolicitud = sp.idsolicitud    
        WHERE sp.idproceso = 30
            AND sp.fechaCreacion BETWEEN '$fechaInicial 00:00:00' AND '$fechaFinal 23:59:59'
            $filtroReporte
            ORDER BY sp.idsolicitud");
    }

    function obtenerHistrialParcialidadesDesglosado($idsolicitudes){
        $ids = $idsolicitudes ? implode(',', $idsolicitudes) : '""';
        return $this->db->query("SELECT
                a.idsolicitud,
                a.idpago,
                a.cantidad,
                CONCAT(u.nombres, ' ', u.apellidos) usuarioPV, 
                a.fecreg fechaPV,
                a.fechaDis,
                a.fecha_cobro,
                ifnull(a.tipoPago, a.formaPago) tipoPago,
                CONCAT(us.nombres, ' ', us.apellidos) usuarioSP,
                et.etapa_pago 
            FROM autpagos a
                LEFT JOIN usuarios u ON a.idrealiza = u.idusuario
                LEFT JOIN solpagos sp ON a.idsolicitud = sp.idsolicitud
                LEFT JOIN usuarios us ON sp.idusuario = us.idusuario
                LEFT JOIN etapas_pago et on a.estatus = et.estatus
            WHERE a.idsolicitud in ($ids)
            ORDER BY fecreg;"
        );
    }
    function validarUsuarioPermitido($idProceso, $idEtapa) {
        return $this->db->query("SELECT idpermitido
                        FROM proceso_etapa 
                        WHERE idproceso = ? AND idetapa = ?
                        ORDER BY idproceso, orden", [$idProceso, $idEtapa]);
    }

    function obtenerUsuarioSupervisado($idUsuario){
        return $this->db->query("SELECT GROUP_CONCAT(idusuario) AS idpermitido 
                                FROM usuarios
                                WHERE FIND_IN_SET('$idUsuario', sup)");
    }

}
