<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dispercion_model extends CI_Model {

    function __construct(){
        parent::__construct();
    }

    function get_solicitudes_autorizadas_area(){

       return $this->db->query("SELECT  historial_cheques.numCan AS tienehistorial, (autpagos.cantidad + autpagos.interes) AS int_acum, 
                solpagos.programado, solpagos.intereses, solpagos.folio, autpagos.referencia, autpagos.idsolicitud, 
                autpagos.idpago, solpagos.justificacion, nombre_completo auto, DATE_FORMAT(autpagos.fecreg, '%d/%m/%Y') AS fecha_autorizacion, 
                proveedores.nombre, autpagos.cantidad, solpagos.moneda, (autpagos.cantidad * IFNULL(autpagos.tipoCambio, 1)) as CA, autpagos.tipoCambio,
                IFNULL(autpagos.tipoPago, solpagos.metoPago) AS tipoPago, empresas.abrev, autpagos.estatus, '1' as notab,
                DATE_FORMAT(autpagos.fechaDis, '%d/%m/%Y') AS fechaDis, autpagos.interes, solpagos.caja_chica,
                if(solpagos.nomdepto = 'DEVOLUCIONES' and solpagos.programado is not null, 'S', 'N' ) esParcialidad
            FROM autpagos
            INNER JOIN solpagos ON solpagos.idsolicitud = autpagos.idsolicitud
            INNER JOIN proveedores on solpagos.idProveedor = proveedores.idproveedor
            INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa
            LEFT JOIN listado_usuarios lu ON lu.idusuario = autpagos.idrealiza
            LEFT JOIN (SELECT *
                FROM historial_cheques
                GROUP BY historial_cheques.idautpago, historial_cheques.tipo
                HAVING MAX(historial_cheques.fecha_creacion) AND historial_cheques.tipo = 1) historial_cheques ON historial_cheques.idautpago = autpagos.idpago
            WHERE autpagos.estatus IN (1, 25, 35)
            UNION
            SELECT  historial_cheques.numCan AS tienehistorial, 0 as int_acum, 0 as programado, 0 as intereses, 'CAJA CH' AS folio, 
                autpagos_caja_chica.referencia, autpagos_caja_chica.idsolicitud, autpagos_caja_chica.idpago, 'CAJA CHICA' AS justificacion,
                nombre_completo auto, DATE_FORMAT(autpagos_caja_chica.fecreg, '%d/%m/%Y') AS fecha_autorizacion, 
                CONCAT(usuarios.nombres, ' ', usuarios.apellidos) AS nombre, autpagos_caja_chica.cantidad, 'MXN' moneda, 
                autpagos_caja_chica.cantidad AS CA, null tipoCambio, autpagos_caja_chica.tipoPago, empresas.abrev, autpagos_caja_chica.estatus,
                2 as notab, DATE_FORMAT(autpagos_caja_chica.fechaDis, '%d/%m/%Y') AS fechaDis, 0 as interes, 0 AS caja_chica,
                'N' esParcialidad
            FROM autpagos_caja_chica
            LEFT JOIN listado_usuarios lu ON lu.idusuario = autpagos_caja_chica.idrealiza
            INNER JOIN usuarios ON usuarios.idusuario = autpagos_caja_chica.idResponsable
            INNER JOIN empresas ON empresas.idempresa = autpagos_caja_chica.idempresa
            LEFT JOIN ( SELECT *
                    FROM historial_cheques
                    GROUP BY historial_cheques.idautpago, historial_cheques.tipo
                    HAVING MAX(historial_cheques.fecha_creacion) AND historial_cheques.tipo = 2
            ) historial_cheques ON historial_cheques.idautpago = autpagos_caja_chica.idpago
            WHERE autpagos_caja_chica.estatus IN (5, 25, 35) AND autpagos_caja_chica.nomdepto != 'TARJETA CREDITO'
            UNION
            SELECT  historial_cheques.numCan AS tienehistorial, 0 as int_acum, 0 as programado, 0 as intereses, nomdepto folio,
                autpagos_caja_chica.referencia, autpagos_caja_chica.idsolicitud, autpagos_caja_chica.idpago, nomdepto justificacion, 
                nombre_completo auto, DATE_FORMAT(autpagos_caja_chica.fecreg, '%d/%m/%Y') AS fecha_autorizacion, p.nombre,
                autpagos_caja_chica.cantidad, 'MXN' moneda, autpagos_caja_chica.cantidad AS CA, null tipoCambio, autpagos_caja_chica.tipoPago,
                empresas.abrev, autpagos_caja_chica.estatus, 2 as notab, DATE_FORMAT(autpagos_caja_chica.fechaDis, '%d/%m/%Y') AS fechaDis,
                0 as interes, 0 AS caja_chica,
                'N' esParcialidad
            FROM autpagos_caja_chica
            INNER JOIN proveedores p ON p.idproveedor = autpagos_caja_chica.idResponsable
            LEFT JOIN listado_usuarios lu ON lu.idusuario = autpagos_caja_chica.idrealiza
            INNER JOIN empresas ON empresas.idempresa = autpagos_caja_chica.idempresa
            LEFT JOIN ( SELECT *
                    FROM historial_cheques
                    GROUP BY historial_cheques.idautpago, historial_cheques.tipo
                    HAVING MAX(historial_cheques.fecha_creacion) AND historial_cheques.tipo = 2 ) historial_cheques ON historial_cheques.idautpago = autpagos_caja_chica.idpago
            WHERE autpagos_caja_chica.estatus IN (5, 25, 35) AND autpagos_caja_chica.nomdepto = 'TARJETA CREDITO'");
    } 
     


  function get_solicitudes_pagadas(){
 
    return $this->db->query("SELECT * FROM ( (
            SELECT COUNT(autpagos.idsolicitud) AS num_pago,
            ROUND(CASE
                WHEN
                    solpagos.programado < 7
                THEN
                    TIMESTAMPDIFF(MONTH,
                        solpagos.fecelab,
                        solpagos.fecha_fin) + ( IF( solpagos.programado = 1, 1, 0 ) ) / solpagos.programado
                ELSE TIMESTAMPDIFF(WEEK,
                    solpagos.fecelab,
                    solpagos.fecha_fin)
            END) AS ppago, 
            solpagos.intereses, 
            autpagos.interes,
            (autpagos.cantidad+autpagos.interes) AS sum_interes,  
            solpagos.programado,  
            solpagos.folio,autpagos.referencia, 
            autpagos.idsolicitud, 
            autpagos.idpago,
            CONCAT(usuarios.nombres,'\n',usuarios.apellidos) AS auto,
            DATE_FORMAT(autpagos.fecreg, '%d/%m/%Y') AS fecha_autorizacion,
            proveedores.nombre,
            solpagos.cantidad,
            autpagos.cantidad as CA,
            IFNULL(autpagos.tipoPago, solpagos.metoPago) AS tipoPago, 
            empresas.abrev, 
            autpagos.estatus, '1' as notab, 
            solpagos.nomdepto,
            DATE_FORMAT(autpagos.fechaDis, '%d/%m/%Y') AS fechaDis, 
            solpagos.justificacion 
            FROM autpagos 
            INNER JOIN solpagos ON solpagos.idsolicitud = autpagos.idsolicitud 
            INNER JOIN proveedores on solpagos.idProveedor =  proveedores.idproveedor 
            INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa 
            LEFT JOIN usuarios ON usuarios.idusuario = autpagos.idrealiza 
            WHERE autpagos.estatus IN ( 15 ) and metoPago not in ('ECHQ') GROUP BY autpagos.idsolicitud )
            UNION
            (
                SELECT 0 num_pago,
                NULL ppago, 
                NULL intereses, 
                0 interes,
                autpagos_caja_chica.cantidad sum_interes,  
                NULL programado,  
                'NA' folio,
                autpagos_caja_chica.referencia, 
                'NA' idsolicitud, 
                autpagos_caja_chica.idpago,
                CONCAT(usuarios.nombres,'\n',usuarios.apellidos) AS auto,
                DATE_FORMAT(autpagos_caja_chica.fecreg, '%d/%m/%Y') AS fecha_autorizacion,
                proveedores.nombre,
                autpagos_caja_chica.cantidad,
                autpagos_caja_chica.cantidad as CA,
                autpagos_caja_chica.tipoPago, 
                empresas.abrev, 
                autpagos_caja_chica.estatus, '1' as notab, 
                autpagos_caja_chica.nomdepto,
                DATE_FORMAT(autpagos_caja_chica.fechaDis, '%d/%m/%Y') AS fechaDis, 
                'PAGO DE CAJA CHICA' justificacion 
                FROM autpagos_caja_chica 
                INNER JOIN proveedores on autpagos_caja_chica.idProveedor =  proveedores.idproveedor 
                INNER JOIN empresas ON empresas.idempresa = autpagos_caja_chica.idEmpresa 
                LEFT JOIN usuarios ON usuarios.idusuario = autpagos_caja_chica.idrealiza 
                WHERE autpagos_caja_chica.estatus IN ( 15 ) and tipoPago NOT IN ('ECHQ')
            ) ) pagos_dispersados_tea
            

            ORDER BY pagos_dispersados_tea.nombre ASC");
    
    } 


  function get_solicitudes_echq(){

    return $this->db->query("(SELECT 
        solpagos.programado,
        solpagos.intereses,
        (a.cantidad+a.interes) AS int_acum,
        a.interes,
        solpagos.justificacion,
        a.idpago,
        a.fecreg AS fecreg,
        proveedores.nombre AS responsable,
        DATE_FORMAT(a.fecreg, '%d/%b/%Y') AS fecha_operacion,
        a.cantidad,
        a.referencia,
        '1' AS bd,
        empresas.abrev,
        a.idsolicitud,
        a.referencia,
        solpagos.metoPago as tipoPago,
        solpagos.cantidad,
        a.cantidad as CA, 
        CONCAT(usuarios.nombres,'\n',usuarios.apellidos) AS auto, 
        DATE_FORMAT(a.fecreg, '%d/%m/%Y') AS fecha_autorizacion 

    FROM
        autpagos as a
            INNER JOIN
        (SELECT 
            solpagos.idsolicitud, proveedores.nombre
        FROM
            proveedores
        INNER JOIN solpagos ON solpagos.idProveedor = proveedores.idproveedor) AS proveedores ON proveedores.idsolicitud = a.idsolicitud
            INNER JOIN
        (SELECT 
            solpagos.idsolicitud, empresas.abrev
        FROM
            empresas
        INNER JOIN solpagos ON solpagos.idEmpresa = empresas.idempresa) AS empresas ON a.idsolicitud = empresas.idsolicitud
            LEFT JOIN
        obssols ON a.idsolicitud = obssols.idsolicitud
            
            INNER JOIN
        solpagos ON solpagos.idsolicitud = a.idsolicitud

         LEFT JOIN usuarios ON usuarios.idusuario = a.idrealiza

    WHERE
        (a.tipoPago = 'ECHQ'
            OR solpagos.metoPago = 'ECHQ')
            AND a.estatus IN ( 15)
    GROUP BY idpago
    ORDER BY fecreg DESC) UNION (SELECT 
        0 as programado,
        0 as intereses,
        0 AS int_acum,
        0 as interes,
        'CAJA CHICA' AS justificacion,
        ach.idpago,
        ach.fecreg AS fecreg,
        responsable.responsable,
        DATE_FORMAT(ach.fecreg, '%d/%b/%Y') AS fecha_operacion,
        ach.cantidad,
        ach.referencia,
        '2' AS bd,
        empresas.abrev,
        ach.idsolicitud,
        ach.referencia,
        ach.tipoPago,
        ach.cantidad,
        ach.cantidad as CA, 
        CONCAT(usuarios.nombres,'\n',usuarios.apellidos) AS auto, 
        DATE_FORMAT(ach.fecreg, '%d/%m/%Y') AS fecha_autorizacion 
        
    FROM
        autpagos_caja_chica as ach
            INNER JOIN
        (SELECT 
            usuarios.idusuario,
                CONCAT(usuarios.nombres, ' ', usuarios.apellidos) AS responsable
        FROM
            usuarios) AS responsable ON responsable.idusuario = ach.idResponsable
            INNER JOIN
        empresas ON ach.idEmpresa = empresas.idempresa
            LEFT JOIN
        obssols ON ach.idsolicitud = obssols.idsolicitud
             
             LEFT JOIN usuarios ON usuarios.idusuario = ach.idrealiza
    WHERE
        ach.tipoPago = 'ECHQ'
            AND ach.estatus IN ( 15)
    GROUP BY idpago
    ORDER BY fecreg DESC)");
    }

    function update_proceso2_chica( $idsol ){ 
        $query=$this->db->query("UPDATE autpagos_caja_chica SET estatus = 3 WHERE idpago = '$idsol'");
    }

 
  function update_eliminar($idsol) 
    { 
      // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
      $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']); 
      echo ("UPDATE autpagos SET estatus=5 WHERE idsolicitud=".$idsol."");
      $this->db->query("UPDATE autpagos SET estatus = 5 WHERE idpago = '$idsol'");
    }
 

   function update_proceso3($idsol) 
    { 
        // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
        $this->db->query("UPDATE autpagos SET estatus = 2, fechacp = CURRENT_DATE() WHERE idpago= $idsol");
    }

    function update_proceso3_ch($idsol) 
      { 
      $this->db->query("UPDATE autpagos_caja_chica SET estatus = 2, fechacp = CURRENT_DATE() WHERE idpago= $idsol");
    }

    function insert_data( $data ){  
        $this->db->insert("obssols", $data);  
    }  
}
