<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Solicitudes_cxp extends CI_Model {

  function __construct(){
    parent::__construct();
  }

  function get_solicitudes_nuevas_proveedor(){
    return $this->db->query('SELECT 
    solpagos.programado, 
    DATE_FORMAT(solpagos.fechaCreacion, "%d/%m/%Y") AS fechaCreacion, 
    facturas.uuid okfactura, 
    facturas.tipo_factura,
    facturas.idfactura,
    facturas.idsolicitudr,
    solpagos.prioridad, 
    solpagos.idsolicitud, 
    solpagos.folio, 
    solpagos.moneda, 
    solpagos.justificacion, 
    empresas.abrev as nemp, 
    solpagos.nomdepto, 
    proveedores.nombre, 
    solpagos.cantidad, 
    DATE_FORMAT(solpagos.fecelab, "%d/%m/%Y") AS fecelab, 
    etapas.idetapa, 
    etapas.nombre AS etapan, 
    solpagos.idusuario, 
    usuarios.nombre_completo nombres, 
    capturista.nombre_completo nombre_capturista, 
    solpagos.metoPago 
    FROM solpagos 
    INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor 
    INNER JOIN etapas ON etapas.idetapa = solpagos.idetapa 
    INNER JOIN empresas ON solpagos.idempresa=empresas.idempresa 
    LEFT JOIN factura_registro facturas ON solpagos.idsolicitud = facturas.idsolicitud 
    INNER JOIN listado_usuarios usuarios ON usuarios.idusuario = solpagos.idResponsable 
    INNER JOIN listado_usuarios capturista ON capturista.idusuario = solpagos.idusuario 
    WHERE 
    solpagos.idetapa IN (5) AND 
    solpagos.programado IS NULL AND 
    ( solpagos.caja_chica is null or solpagos.caja_chica = 0 ) AND 
    solpagos.metoPago NOT LIKE "FACT%" AND 
    solpagos.nomdepto NOT IN ("DEVOLUCIONES", "BONO", "PRESTAMO", "FINIQUITO", "FINIQUITO POR RENUNCIA", "FINIQUITO POR PARCIALIDAD","TRASPASO", "TRASPASO OOAM", "DEVOLUCION" )
    ORDER BY solpagos.prioridad, solpagos.fecelab DESC');
    //AND solpagos.fecha_autorizacion < "'.date( "Y-m-d", strtotime('monday this week') ).'"
  }

  function get_solicitudes_nuevas_factoraje(){
    return $this->db->query('SELECT solpagos.idetapa as etap, DATE_FORMAT(solpagos.fechaCreacion, "%d/%m/%Y") AS fechaCreacion, facturas.idsolicitud as okfactura, solpagos.prioridad, solpagos.idsolicitud, solpagos.folio, solpagos.moneda, solpagos.justificacion, empresas.abrev as nemp, solpagos.nomdepto, proveedores.nombre, solpagos.cantidad, DATE_FORMAT(solpagos.fecelab, "%d/%m/%Y") AS fecelab, etapas.idetapa, etapas.nombre AS etapan, solpagos.idusuario,  usuarios.nombres,  facturas.tipo_factura, usuarios.apellidos, capturista.nombre_capturista, solpagos.metoPago FROM solpagos INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor INNER JOIN etapas ON etapas.idetapa = solpagos.idetapa INNER JOIN empresas ON solpagos.idempresa=empresas.idempresa LEFT JOIN (select *, MIN(facturas.feccrea) from facturas where facturas.tipo_factura in (1, 3) GROUP BY facturas.idsolicitud) as facturas ON solpagos.idsolicitud = facturas.idsolicitud  INNER JOIN usuarios ON usuarios.idusuario = solpagos.idResponsable INNER JOIN ( SELECT usuarios.idusuario, CONCAT(usuarios.nombres, " ", usuarios.apellidos) AS nombre_capturista FROM usuarios ) AS capturista ON capturista.idusuario = solpagos.idusuario WHERE solpagos.idetapa IN (5) AND ( solpagos.caja_chica is null or solpagos.caja_chica = 0 ) AND solpagos.metoPago LIKE "FACT%" AND solpagos.nomdepto NOT IN ("DEVOLUCIONES", "BONO", "PRESTAMO", "FINIQUITO", "FINIQUITO POR RENUNCIA", "FINIQUITO POR PARCIALIDAD","TRASPASO","DEVOLUCION" ) ORDER BY solpagos.prioridad DESC');
  }

  function getsolicitudescajasChicas( $idsolicitudes, $isCajaNoDeducible = null ){
    //$isCajaNoDeducible != true ? $where = "AND proveedores.idproveedor <> 2936" : $where = 'AND proveedores.idproveedor = 2936';
    $isCajaNoDeducible != true ? $where = "" : $where = 'AND proveedores.idproveedor = 2936';
    return $this->db->query("SELECT 
      sp.idusuario, 
      sp.idsolicitud, 
      sp.folio, 
      sp.moneda, 
      sp.justificacion, 
      sp.metoPago,
      empresas.abrev, 
      sp.nomdepto, 
      proveedores.nombre AS nombre_proveedor, 
      DATE_FORMAT( sp.fecelab, '%d/%m/%Y') AS fecelab, 
      DATE_FORMAT( sp.fecelab, '%d/%m/%Y') AS fecautorizacion, 
      usuarios.nombre_completo nombre_capturista, 
      sp.cantidad, 
      if(sp.proyecto is not null, sp.proyecto, pd.nombre) as proyecto, 
      sp.folio, 
      sp.justificacion, 
      /*IF( facturas.uuid IS NULL OR facturas.tipo_factura IN ( 1, 3) , 0, 1 )*/0 nfac,
      facturas.idfactura,
      facturas.idsolicitudr,
      CASE
        WHEN sp.tipo_insumo = 1 THEN IFNULL(se.diasDesayuno, 1) * z.cantidadDesayuno * sp.colabs
        WHEN sp.tipo_insumo = 2 THEN IFNULL(se.diasComida, 1) * z.cantidadComida * sp.colabs
        WHEN sp.tipo_insumo = 3 THEN IFNULL(se.diasCena, 1) * z.cantidadCena * sp.colabs
        WHEN sp.tipo_insumo = 4 THEN (IFNULL(se.diasDesayuno, 1) * z.cantidadDesayuno * sp.colabs) + (IFNULL(se.diasComida, 1) * z.cantidadComida) * sp.colabs
        WHEN sp.tipo_insumo = 5 THEN (IFNULL(se.diasDesayuno, 1) * z.cantidadDesayuno * sp.colabs) + (IFNULL(se.diasCena, 1) * z.cantidadCena) * sp.colabs
        WHEN sp.tipo_insumo = 6 THEN (IFNULL(se.diasComida, 1) * z.cantidadComida * sp.colabs) + (IFNULL(se.diasCena, 1) * z.cantidadCena) * sp.colabs
        WHEN sp.tipo_insumo = 7 THEN (IFNULL(se.diasDesayuno, 1) * z.cantidadDesayuno * sp.colabs) + (IFNULL(se.diasComida, 1) * z.cantidadComida * sp.colabs) + IFNULL(se.diasCena, 1) * z.cantidadCena * sp.colabs
        ELSE NULL
      END cantidad_limite,
      e.estado,
      e.pais,
      z.nombre AS zona,
      sp.colabs,
      sp.tipo_insumo,
      CASE 
        WHEN sp.tipo_insumo = 1 THEN IFNULL(CONCAT('DESAYUNO (', se.diasDesayuno, ' días)'), 'DESAYUNO')
        WHEN sp.tipo_insumo = 2 THEN IFNULL(CONCAT('COMIDA (', se.diasComida, ' días)'), 'COMIDA')
        WHEN sp.tipo_insumo = 3 THEN IFNULL(CONCAT('CENA (', se.diasCena, ' días)'), 'CENA')
        WHEN sp.tipo_insumo = 4 THEN CONCAT(IFNULL(CONCAT('DESAYUNO (', se.diasDesayuno, ' días)'), 'DESAYUNO'), ', ', IFNULL(CONCAT('COMIDA (', se.diasComida, ' días)'), 'COMIDA'))
        WHEN sp.tipo_insumo = 5 THEN CONCAT(IFNULL(CONCAT('DESAYUNO (', se.diasDesayuno, ' días)'), 'DESAYUNO'), ', ', IFNULL(CONCAT('CENA (', se.diasCena, ' días)'), 'CENA'))
        WHEN sp.tipo_insumo = 6 THEN CONCAT(IFNULL(CONCAT('COMIDA (', se.diasComida, ' días)'), 'COMIDA'), ', ', IFNULL(CONCAT('CENA (', se.diasCena, ' días)'), 'CENA'))
        WHEN sp.tipo_insumo = 7 THEN CONCAT(IFNULL(CONCAT('DESAYUNO (', se.diasDesayuno, ' días)'), 'DESAYUNO'), ', ', IFNULL(CONCAT('COMIDA (', se.diasComida, ' días)'), 'COMIDA'), ', ', IFNULL(CONCAT('CENA (', se.diasCena, ' días)'), 'CENA'))
        ELSE 'N/A'
      END AS tipo_insumo
    FROM (  
      SELECT * FROM solpagos
      WHERE FIND_IN_SET( solpagos.idsolicitud, '$idsolicitudes' ) 
    ) sp 
    CROSS JOIN (SELECT idempresa, abrev FROM empresas ) empresas ON empresas.idempresa = sp.idEmpresa 
    CROSS JOIN (SELECT idproveedor, nombre FROM proveedores) proveedores ON proveedores.idproveedor = sp.idProveedor $where
    CROSS JOIN listado_usuarios usuarios ON usuarios.idusuario = sp.idusuario 
    LEFT JOIN factura_registro facturas ON facturas.idsolicitud = sp.idsolicitud 
    LEFT JOIN solicitud_proyecto_oficina spo ON spo.idsolicitud = sp.idsolicitud 
    LEFT JOIN proyectos_departamentos pd ON spo.idProyectos = pd.idProyectos
    LEFT JOIN solicitud_estados se ON sp.idsolicitud = se.idsolicitud
    LEFT JOIN estados e ON e.id_estado = se.id_estado
    LEFT JOIN zonas z ON z.idZonas = e.idZonas
    ORDER BY sp.fecha_autorizacion")->result_array();
  }

  function get_solicitudes_nuevas_caja_chica($idsolicitudes){
    return $this->db->query("SELECT s.idsolicitud as solinum, s.folio, s.moneda, s.justificacion, e.abrev as nemp, s.nomdepto, p.nombre, s.cantidad, DATE_FORMAT(s.fecelab, '%d/%m/%Y') AS fecelab, s.metoPago, s.idusuario, s.caja_chica, u.nombres,  u.apellidos, s.cantidad as cnt2, s.proyecto ,IFNULL(s.etapa, '') AS ETAPA , IFNULL(s.condominio, '') AS Condominio , p.nombre AS Proveedor, DATE_FORMAT(s.fecelab,'%d/%m/%Y') AS FECHAFACP , s.folio AS Folio , s.justificacion AS Observacion, IFNULL(f.nfac, 0) nfac FROM solpagos s 
    LEFT JOIN ( SELECT idsolicitud, count(idsolicitud) nfac FROM facturas WHERE tipo_factura IN ( 1, 3 ) GROUP BY facturas.idsolicitud ) f on f.idsolicitud=s.idsolicitud 
    INNER JOIN proveedores p ON(s.idProveedor = p.idProveedor) 
    INNER JOIN empresas e ON(s.idempresa=e.idempresa) 
    INNER JOIN usuarios u ON(u.idusuario = s.idResponsable) 
    WHERE s.fecha_autorizacion < '".date( "Y-m-d", strtotime('monday this week') )." 23:59:00' AND FIND_IN_SET( s.idsolicitud, '$idsolicitudes') ORDER BY u.nombres ASC")->result_array();
    //
  }
 
  function obtenerSolCajachica(){
    return $this->db->query("SELECT group_concat(`s`.`idsolicitud`) AS `ID`, 
                                    CONCAT(u.nombres, ' ', u.apellidos) AS Responsable,
                                    emp.abrev,MAX(DATE_FORMAT(s.fecelab,'%d/%b/%Y')) AS FECHAFACP,
                                    SUM(s.cantidad) AS Cantidad,
                                    s.nomdepto AS Departamento,
                                    s.idEmpresa AS Empresa,
                                    s.idResponsable IDR,
                                    responsable_cch.nombre_reembolso_cch
                              FROM  solpagos s 
                              INNER JOIN usuarios u 
                                ON (s.idResponsable =  u.idusuario)
                              INNER JOIN empresas emp 
                                ON(emp.idEmpresa = s.idEmpresa)
                              INNER JOIN proveedores p 
                                ON(s.idProveedor = p.idProveedor) AND p.idproveedor <> 2936
                              LEFT JOIN (
                                  SELECT
                                      cajas_ch.idusuario
                                      , GROUP_CONCAT(cajas_ch.nombre_reembolso_ch SEPARATOR ', ') nombre_reembolso_cch
                                  FROM cajas_ch
                                  GROUP BY cajas_ch.idusuario
                              ) AS responsable_cch ON responsable_cch.idusuario = s.idResponsable AND s.caja_chica = 1
                              WHERE s.caja_chica = 1 
                                AND s.idetapa IN (5) AND s.fecha_autorizacion < '".date( "Y-m-d", strtotime('monday this week') )." 23:59:00'
                              GROUP BY s.idResponsable ,s.idEmpresa ORDER BY s.fecelab;");
  }

	function obtenerSolReembolsos(){
		return $this->db->query("SELECT group_concat(`s`.`idsolicitud`) AS `ID`, 
      CONCAT(u.nombres, ' ', u.apellidos) AS Responsable,emp.abrev,MAX(DATE_FORMAT(s.fecelab,'%d/%b/%Y')) AS FECHAFACP ,SUM(s.cantidad) AS Cantidad ,s.nomdepto AS Departamento,s.idEmpresa AS Empresa,
             s.idResponsable IDR 
             FROM  solpagos s INNER JOIN usuarios u ON (s.idResponsable =  u.idusuario)
             INNER JOIN empresas emp ON(emp.idEmpresa = s.idEmpresa)
             INNER JOIN proveedores p ON(s.idProveedor = p.idProveedor)
             WHERE s.caja_chica = 3 AND s.idetapa IN (5)  AND s.fecha_autorizacion < '".date( "Y-m-d", strtotime('monday this week') )." 23:59:00'
              GROUP BY s.idResponsable ,s.idEmpresa ORDER BY s.fecelab;");
	}

	function obtenerSolViaticos(){
		return $this->db->query("SELECT group_concat(`s`.`idsolicitud`) AS `ID`, 
      CONCAT(u.nombres, ' ', u.apellidos) AS Responsable,emp.abrev,MAX(DATE_FORMAT(s.fecelab,'%d/%b/%Y')) AS FECHAFACP ,SUM(s.cantidad) AS Cantidad ,s.nomdepto AS Departamento,s.idEmpresa AS Empresa,
             s.idResponsable IDR, responsable_cch.nombre_reembolso_cch
             FROM  solpagos s INNER JOIN usuarios u ON (s.idResponsable =  u.idusuario)
             INNER JOIN empresas emp ON(emp.idEmpresa = s.idEmpresa)
             INNER JOIN proveedores p ON(s.idProveedor = p.idProveedor)
             LEFT JOIN (
                SELECT
                    cajas_ch.idusuario
                    , GROUP_CONCAT(cajas_ch.nombre_reembolso_ch SEPARATOR ', ') nombre_reembolso_cch
                FROM cajas_ch
                GROUP BY cajas_ch.idusuario
             ) AS responsable_cch ON responsable_cch.idusuario = s.idResponsable
             WHERE s.caja_chica = 4 AND s.idetapa IN (5) AND s.fecha_autorizacion < '".date( "Y-m-d", strtotime('monday this week') )." 23:59:00'
              GROUP BY s.idResponsable ,s.idEmpresa ORDER BY s.fecelab;");
	}

  function obtenerSolCajachica_TDC(){
    $this->db->query("SET SESSION group_concat_max_len = 1000000;");
    return $this->db->query("SELECT group_concat(`s`.`idsolicitud`) AS `ID`, 
                                    CONCAT(u.nresponsable, '-', u.ntarjeta) Responsable,
                                    emp.abrev,
                                    MAX(DATE_FORMAT(s.fecelab,'%d/%b/%Y')) AS FECHAFACP,
                                    SUM(s.cantidad) AS Cantidad,
                                    s.nomdepto Departamento,
                                    s.idEmpresa Empresa,
                                    s.idResponsable IDR,
                                    0 pa,
                                    null solicitudes,
                                    IFNULL(tarjeta.titular_nombre, 'NA') as titular_nombre,
                                    s.moneda
                            FROM  solpagos s 
                            INNER JOIN listado_tdc u ON ( s.idResponsable =  u.idtcredito )
                            INNER JOIN empresas emp ON ( emp.idEmpresa = s.idEmpresa )
                            INNER JOIN proveedores p ON( s.idProveedor = p.idProveedor )
                            LEFT JOIN tcredito tdc ON s.idResponsable = tdc.idtcredito
                            LEFT JOIN ( SELECT usuarios.idusuario, CONCAT( usuarios.nombres,' ', usuarios.apellidos ) AS titular_nombre FROM usuarios ) AS tarjeta ON tarjeta.idusuario = tdc.idtitular
                            WHERE s.caja_chica = 2 AND s.idetapa IN ( 5 )
                            GROUP BY s.idResponsable ,s.idEmpresa ORDER BY Responsable");
  }
 /*Solicitudes de gastos no deducibles*/
	function obtenerSolCajachicaNoDDLS(){
		return $this->db->query("SELECT group_concat(`s`.`idsolicitud`) AS `ID`, 
      CONCAT(u.nombres, ' ', u.apellidos) AS Responsable,emp.abrev,MAX(DATE_FORMAT(s.fecelab,'%d/%b/%Y')) AS FECHAFACP ,SUM(s.cantidad) AS Cantidad ,s.nomdepto AS Departamento,s.idEmpresa AS Empresa,
             s.idResponsable IDR 
             FROM  solpagos s INNER JOIN usuarios u ON (s.idResponsable =  u.idusuario)
             INNER JOIN empresas emp ON(emp.idEmpresa = s.idEmpresa)
             INNER JOIN proveedores p ON(s.idProveedor = p.idProveedor)
             WHERE s.caja_chica = 1 AND s.idetapa IN (5) AND s.fecha_autorizacion < '".date( "Y-m-d", strtotime('monday this week') )." 23:59:00'
             AND p.idproveedor=2936
              GROUP BY s.idResponsable ,s.idEmpresa ORDER BY s.fecelab;");
	}



	function regresar_pago( $idpago ){
    // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
    $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
    $query = $this->db->query("SELECT * FROM autpagos WHERE autpagos.idpago = '$idpago'");
    $this->db->update("solpagos", array("idetapa"=> 7), "idsolicitud = '".$query->row()->idsolicitud."'");
    $this->db->delete("autpagos", array('idpago'=> $idpago));
  }


  function get_provs(){
    return $this->db->query("SELECT * from proveedores WHERE estatus not in (4)");
  }

  

  function borrar_solicitud( $idsolicitud ){
    if( $this->db->delete("solpagos", "idetapa IN ( 5 ) AND idsolicitud = '$idsolicitud'") ){
        return $this->db->delete("facturas", array('idsolicitud' => $idsolicitud) );
    }
  }
 
  function get_valor_echq($data){
    return $this->db->query("SELECT referencia from autpagos WHERE idpago  = ".$data);
  }

 
  function get_pagos_todos($filtro){
    return $this->db->query('SELECT autpagos.idpago, solpagos.idsolicitud, solpagos.folio, solpagos.moneda, solpagos.justificacion, empresas.abrev as nemp, solpagos.nomdepto, proveedores.nombre, autpagos.cantidad as canpago, solpagos.cantidad, DATE_FORMAT(solpagos.fecelab, "%d/%m/%Y") AS fecelab,  facturas.descripcion, facturas.total, solpagos.idusuario, solpagos.caja_chica, autpagos.estatus as estpago, IFNULL(notifi.visto, 1) AS visto FROM solpagos INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor  LEFT JOIN (select *, MIN(facturas.feccrea) from facturas where facturas.tipo_factura in (1, 3) GROUP BY facturas.idsolicitud) as facturas ON solpagos.idsolicitud = facturas.idsolicitud INNER JOIN empresas ON solpagos.idempresa=empresas.idempresa  INNER JOIN autpagos ON solpagos.idsolicitud=autpagos.idsolicitud LEFT JOIN ( SELECT notificaciones.idsolicitud, notificaciones.visto FROM notificaciones WHERE notificaciones.idusuario = "'.$this->session->userdata("inicio_sesion")['id'].'"  ) AS notifi ON notifi.idsolicitud = solpagos.idsolicitud WHERE autpagos.estatus IN ( 3 ) AND fecha_pago IS NULL'); 
  } 

  //LISTADODE PAGOS AUTORIZADOS TRANSFERENCIAS
  function get_solicitudes_historial_area(){
    return $this->db->query("SELECT 
    solpagos.idEmpresa, 
    autpagos.idpago, 
    solpagos.idsolicitud, 
    solpagos.folio, 
    solpagos.prioridad, 
    solpagos.moneda, 
    solpagos.justificacion, 
    autpagos.tipoCambio, 
    empresas.abrev as nemp, 
    solpagos.nomdepto, 
    proveedores.nombre, 
    solpagos.metoPago, 
    solpagos.cantidad, 
    DATE_FORMAT(solpagos.fecelab, '%d/%m/%Y') AS fecelab,
    solpagos.idusuario, 
    autpagos.cantidad AS autorizado, 
    IF( proveedores.cuenta IS NOT NULL AND proveedores.cuenta < 1 AND proveedores.cuenta != '', 0, autpagos.estatus ) estatus
    FROM solpagos 
    LEFT JOIN autpagos ON solpagos.idsolicitud = autpagos.idsolicitud  
    LEFT JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor 
    LEFT JOIN empresas ON solpagos.idempresa = empresas.idempresa
    WHERE ( solpagos.programado IN ( '', 0 ) 
        OR solpagos.programado IS NULL 
        OR (solpagos.programado is not null 
        AND solpagos.idproceso = 30 ))
      AND	solpagos.idetapa IN ( 9, 10, 11, 80, 81 )
      AND IFNULL( autpagos.tipoPago, solpagos.metoPago ) LIKE 'TEA' 
      AND autpagos.estatus IN ( 0, 11 ) 
      AND autpagos.fecha_pago IS NULL
    ORDER BY proveedores.nombre ASC"); 
  }

  //LISTADO DE PAGOS AUTORIZADOS PROGRAMADOS TRANSFERENCIA
  function get_pagos_aut_prog(){
    return $this->db->query("SELECT 
      (autpagos.cantidad+autpagos.interes) AS interes_agregado, 
      solpagos.intereses, 
      solpagos.programado, 
      solpagos.idEmpresa, 
      autpagos.motivoEspera, 
      autpagos.idpago, 
      autpagos.referencia, 
      solpagos.idsolicitud, 
      solpagos.folio, 
      solpagos.prioridad, 
      solpagos.moneda, 
      solpagos.justificacion, 
      autpagos.tipoCambio, 
      autpagos.interes, 
      empresas.abrev as nemp, 
      solpagos.nomdepto, 
      proveedores.nombre, 
      solpagos.metoPago, solpagos.cantidad, DATE_FORMAT(solpagos.fecelab, '%d/%m/%Y') AS fecelab, etapas.idetapa, etapas.nombre AS etapan, 
      facturas.descripcion, facturas.total, solpagos.idusuario, solpagos.caja_chica, autpagos.cantidad AS autorizado, autpagos.estatus, facturas.idsolicitud as okfactura 
    FROM ( 
      SELECT * FROM solpagos
      WHERE
      solpagos.programado IS NOT NULL 
      AND solpagos.programado != '' 
      AND solpagos.programado != 0 
      AND solpagos.idetapa IN ( 5, 7, 9, 10, 11 )
      AND solpagos.metoPago IN ( 'ECHQ', 'MAN', 'DOMIC', 'EFEC', 'TEA' )
      AND solpagos.idproceso IS NULL
    ) solpagos 
    INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor 
    INNER JOIN etapas ON etapas.idetapa = solpagos.idetapa 
    INNER JOIN empresas ON solpagos.idempresa=empresas.idempresa 
    INNER JOIN autpagos ON solpagos.idsolicitud = autpagos.idsolicitud AND autpagos.estatus IN ( 0, 11, 13 ) AND autpagos.fecha_pago IS NULL
    LEFT JOIN (select *, MIN(facturas.feccrea) from facturas where facturas.tipo_factura in (1, 3) GROUP BY facturas.idsolicitud) as facturas ON solpagos.idsolicitud = facturas.idsolicitud 
    ORDER BY proveedores.nombre ASC"); 
 }
 
 //ACTUALIZAR DATOS DEL PAGO
  function update_pagos( $base_datos, $data, $idpago ){
    return $this->db->update( $base_datos, $data, "idpago = '$idpago'");
  }

  function get_solicitudes_historial_fact($valor_filtro){
    if($valor_filtro==0 or $valor_filtro==""){
      return $this->db->query('SELECT solpagos.idEmpresa, autpagos.motivoEspera, autpagos.idpago, solpagos.idsolicitud, solpagos.folio, solpagos.prioridad, solpagos.moneda, solpagos.justificacion, autpagos.tipoCambio, empresas.abrev as nemp, solpagos.nomdepto, proveedores.nombre, solpagos.metoPago, solpagos.cantidad, DATE_FORMAT(solpagos.fecelab, "%d/%m/%Y") AS fecelab, etapas.idetapa, etapas.nombre AS etapan, facturas.descripcion, facturas.total, solpagos.idusuario, solpagos.caja_chica, autpagos.cantidad AS autorizado, autpagos.estatus, facturas.idsolicitud as okfactura FROM solpagos INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor INNER JOIN etapas ON etapas.idetapa = solpagos.idetapa LEFT JOIN (select *, MIN(facturas.feccrea), "1" as notab from facturas where facturas.tipo_factura in (1, 3) GROUP BY facturas.idsolicitud) as facturas ON solpagos.idsolicitud = facturas.idsolicitud INNER JOIN empresas ON solpagos.idempresa=empresas.idempresa INNER JOIN autpagos ON solpagos.idsolicitud = autpagos.idsolicitud WHERE solpagos.idetapa IN (9, 10, 11) AND (autpagos.estatus = 0 OR autpagos.estatus = 11) AND autpagos.fecha_pago IS NULL  AND (solpagos.metoPago LIKE "FACT BAJIO" OR  solpagos.metoPago LIKE "FACT BANREGIO" ) ORDER BY proveedores.nombre ASC'); 
    }else{ 
      return $this->db->query('SELECT solpagos.idEmpresa, autpagos.motivoEspera, autpagos.idpago, solpagos.idsolicitud, solpagos.folio, solpagos.prioridad, solpagos.moneda, solpagos.justificacion, autpagos.tipoCambio, empresas.abrev as nemp, solpagos.nomdepto, proveedores.nombre, solpagos.metoPago, solpagos.cantidad, DATE_FORMAT(solpagos.fecelab, "%d/%m/%Y") AS fecelab, etapas.idetapa, etapas.nombre AS etapan, facturas.descripcion, facturas.total, solpagos.idusuario, solpagos.caja_chica, autpagos.cantidad AS autorizado, autpagos.estatus, facturas.idsolicitud as okfactura FROM solpagos INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor INNER JOIN etapas ON etapas.idetapa = solpagos.idetapa LEFT JOIN (select *, MIN(facturas.feccrea), "1" as notab from facturas where facturas.tipo_factura in (1, 3) GROUP BY facturas.idsolicitud) as facturas ON solpagos.idsolicitud = facturas.idsolicitud INNER JOIN empresas ON solpagos.idempresa=empresas.idempresa INNER JOIN autpagos ON solpagos.idsolicitud = autpagos.idsolicitud WHERE empresas.idempresa in ("'.$valor_filtro.'") and solpagos.idetapa IN (9, 10, 11) AND (autpagos.estatus = 0 OR autpagos.estatus = 11) AND autpagos.fecha_pago IS NULL  AND (solpagos.metoPago LIKE "FACT BAJIO" OR  solpagos.metoPago LIKE "FACT BANREGIO" ) ORDER BY proveedores.nombre ASC'); 
    }
  }

  function get_solicitudes_autorizadas_area(){
    return $this->db->query("(SELECT autpagos.referencia, autpagos.idsolicitud, autpagos.idpago,  CONCAT(usuarios.nombres,'\n',usuarios.apellidos) AS auto, autpagos.fecreg AS fecha_autorizacion,  proveedores.nombre, solpagos.cantidad, autpagos.cantidad as CA, IFNULL(autpagos.tipoPago, solpagos.metoPago) AS tipoPago, empresas.abrev, autpagos.estatus, '1' as notab, autpagos.fechaDis FROM autpagos INNER JOIN solpagos ON solpagos.idsolicitud = autpagos.idsolicitud INNER JOIN proveedores on solpagos.idProveedor = proveedores.idproveedor INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa LEFT JOIN usuarios ON usuarios.idusuario = autpagos.idrealiza WHERE autpagos.estatus IN(1,25,35)) UNION (SELECT autpagos_caja_chica.referencia, autpagos_caja_chica.idsolicitud, autpagos_caja_chica.idpago, 'CAJA CHICA' AS auto, autpagos_caja_chica.fecreg AS fecha_autorizacion, CONCAT(usuarios.nombres, ' ',usuarios.apellidos) AS nombre, autpagos_caja_chica.cantidad, autpagos_caja_chica.cantidad AS CA,autpagos_caja_chica.tipoPago  , empresas.abrev, autpagos_caja_chica.estatus, 2 as notab, autpagos_caja_chica.fechaDis FROM autpagos_caja_chica INNER JOIN usuarios ON usuarios.idusuario=autpagos_caja_chica.idResponsable INNER JOIN empresas ON empresas.idempresa = autpagos_caja_chica.idempresa WHERE autpagos_caja_chica.estatus IN (5,25,35)) ");
  } 

  function get_solicitudes_programadas_nuevas(){
        /** INICIO FECHA: 17-JUNIO-2025 | PANEL CP A USUARIOS ESPECIFICOS | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> 
         * 2409	- AS - cap.finanza LUGO SOSA	JUAN MANUEL
         * 2681	- CA - VA.MONCADA MONCADA GUERRA VALERIA EDITH
         * **/
        $proveedoresEspecificos = '';
        if (in_array( $this->session->userdata("inicio_sesion")['id'], [ 2681, 2409 ] )) {
          $proveedoresEspecificos = 'AND solpagos.idProveedor IN (341,355,29322) OR solpagos.idsolicitud IN (188800, 208038)'; /** FECHA: 08-07-2025 | PETICION CHAT GOOGLE: AGREGAR ESPECIFICAMENTE ESTAS SOLICITUDES: 188800, 208038 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com>*/
        }else if($this->session->userdata("inicio_sesion")['rol'] == 'CP'){
            $proveedoresEspecificos = 'AND solpagos.idProveedor NOT IN (341,355,29322) AND solpagos.idsolicitud NOT IN (188800, 208038)'; /** FECHA: 08-07-2025 | PETICION CHAT GOOGLE: AGREGAR ESPECIFICAMENTE ESTAS SOLICITUDES: 188800, 208038 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com>*/
        }

        return $this->db->query("SELECT 
                                    IFNULL(ptotales, 0) ptotales, tparcial,
                                    IF(solpagos.fecha_fin IS NULL
                                      , 'SIN DEFINIR'
                                      , ROUND(
                                        CASE
                                          WHEN solpagos.programado = 8
                                            THEN
                                              ROUND(TIMESTAMPDIFF(DAY, solpagos.fecelab, solpagos.fecha_fin) / 15)
                                          WHEN solpagos.programado < 7
                                            THEN 
                                              TIMESTAMPDIFF(
                                                MONTH
                                                , CONCAT( YEAR(solpagos.fecelab),'-',MONTH(solpagos.fecelab),'-', DAY(solpagos.fecelab) )
                                                , solpagos.fecha_fin
                                              ) / solpagos.programado
                                          ELSE
                                            TIMESTAMPDIFF(WEEK, solpagos.fecelab, solpagos.fecha_fin)
                                        END
                                      ) + 1
                                    ) ppago,
                                    ( CASE 
                                        WHEN solpagos.programado = 8
                                          THEN
                                            IF(
                                              ptotales >= TIMESTAMPDIFF(DAY, solpagos.fecelab, solpagos.fecha_fin) / 15,
                                              solpagos.fecha_fin, 
                                              DATE_ADD(solpagos.fecelab, INTERVAL (IFNULL(ptotales, 0) * 15) DAY)
                                            )
                                        WHEN solpagos.programado < 7 
                                        THEN 
                                          DATE_ADD( solpagos.fecelab, INTERVAL ( IFNULL(ptotales, 0) / solpagos.programado ) MONTH ) 
                                        ELSE 
                                          DATE_ADD( solpagos.fecelab, INTERVAL IFNULL(ptotales, 0) WEEK )
                                        END 
                                    ) proximo_pago,
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
                                    estatus_ultimo_pago
                                  FROM 
                                    solpagos
                                  INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor
                                  INNER JOIN empresas ON solpagos.idempresa = empresas.idempresa
                                  LEFT JOIN ( 
                                    SELECT 
                                      autpagos.idsolicitud
                                          , COUNT(autpagos.idpago) ptotales
                                          , SUM(autpagos.cantidad) AS tparcial
                                          , SUM( IF(estatus IN ( 14, 16), autpagos.cantidad,0) ) AS cantidad_confirmada
                                          , SUM(IF(estatus IN ( 14, 16), autpagos.interes, 0) ) AS interes
                                          , MAX( autpagos.fecreg ) AS ultimo_pago 
                                    FROM autpagos 
                                      GROUP BY autpagos.idsolicitud  
                                  ) autpagos ON solpagos.idsolicitud = autpagos.idsolicitud
                                  LEFT JOIN ( 
                                    SELECT 
                                      autpagos.idsolicitud
                                          , autpagos.estatus estatus_ultimo_pago 
                                    FROM autpagos 
                                      WHERE autpagos.estatus NOT IN ( 14, 16)
                                      GROUP BY autpagos.idsolicitud
                                      HAVING MAX(autpagos.idpago) 
                                  ) estatus_pago ON estatus_pago.idsolicitud = solpagos.idsolicitud
                                  INNER JOIN (
                                    SELECT 
                                      usuarios.idusuario
                                      , CONCAT(usuarios.nombres,' ', usuarios.apellidos) AS nombre_capturista 
                                    FROM usuarios 
                                  ) usuarios ON usuarios.idusuario = solpagos.idusuario
                                  WHERE solpagos.programado IS NOT NULL
                                    AND IFNULL(solpagos.idproceso, 0) <> 30
                                    AND solpagos.idetapa NOT IN ( 0, 1, 3, 11, 30, 2, 7, 47 )
                                    $proveedoresEspecificos
                                  ORDER BY proveedores.nombre ASC");
  }


  function get_solicitudes_historial_area2($nomdepto, $idempresa){
    return $this->db->query("SELECT 
      ( autpagos.cantidad + autpagos.interes ) interes_agregado, 
      solpagos.intereses, 
      solpagos.programado, 
      solpagos.idEmpresa, 
      autpagos.motivoEspera, 
      autpagos.idpago, 
      autpagos.referencia, 
      solpagos.idsolicitud, 
      solpagos.folio, 
      solpagos.prioridad, 
      solpagos.moneda, 
      solpagos.justificacion, 
      autpagos.tipoCambio, 
      autpagos.interes, 
      empresas.abrev as nemp, 
      solpagos.nomdepto, 
      proveedores.nombre, 
      solpagos.metoPago, 
      solpagos.cantidad, 
      DATE_FORMAT(solpagos.fecelab, '%d/%m/%Y') AS fecelab, 
      autpagos.cantidad AS autorizado, autpagos.estatus
    FROM solpagos
    INNER JOIN autpagos ON solpagos.idsolicitud = autpagos.idsolicitud
    INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor 
    INNER JOIN empresas ON solpagos.idempresa=empresas.idempresa 
    WHERE ( solpagos.programado IN ( '', 0 ) 
      OR solpagos.programado IS NULL 
      OR (solpagos.programado is not null 
      AND solpagos.idproceso = 30 ))
      AND	solpagos.idetapa IN ( 9, 10, 11, 80, 81 )
      AND IFNULL( autpagos.tipoPago, solpagos.metoPago ) IN ( 'ECHQ', 'MAN', 'DOMIC', 'EFEC' )
      AND autpagos.estatus IN ( 0, 11, 13 ) 
      AND autpagos.fecha_pago IS NULL
    ORDER BY proveedores.nombre ASC;"); 
  }


  function  autorizarPago($id,$cantidad,$status){

    // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
    $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
     $this->db->set("idetapa",$status);
     $this->db->where("idsolicitud",$id);
     $this->db->update("solpagos");
     $this->db->query("INSERT INTO `autpagos`(`idsolicitud`,`cantidad`,`idrealiza`) VALUES(".$id.",'".$cantidad."','".$_SESSION['idusuario']."')");
     return $this->db->affected_rows();
  
  }
    
  function autorizarPagoC($id,$cantidad,$idempresa,$idresponsable,$nomdepto){
    // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
    $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
    $this->db->query("UPDATE solpagos SET solpagos.idetapa = IF( solpagos.idsolicitud NOT IN (SELECT idsolicitud FROM facturas), 10, 11 ) WHERE FIND_IN_SET( idsolicitud, '$id' )");
    $this->db->query("INSERT INTO `autpagos_caja_chica`(`idsolicitud`,`cantidad`,`idrealiza`,`idEmpresa`,`idResponsable`,`nomdepto`)VALUES('".$id."','".$cantidad."','".$this->session->userdata('inicio_sesion')['id']."'"
             . ",'".$idempresa."','".$idresponsable."','".$nomdepto."')");
    return $this->db->affected_rows();
  }

  function obtenerSolCaja(){
    return $this->db->query("SELECT 
      s.tipoPago, 
      s.referencia, 
      u.idusuario, 
      s.motivoEspera, 
      s.estatus AS ESTATUS, 
      s.idpago as PAG, 
      s.cantidad as cnt,  
      s.idsolicitud AS ID, 
      u.nombres AS Responsable, 
      u.apellidos, 
      emp.abrev, 
      DATE_FORMAT(s.fecreg, '%d/%m/%Y') AS FECHAFACP, 
      s.cantidad AS Cantidad,
      s.nomdepto AS Departamento,
      s.idEmpresa AS Empresa,
      s.idResponsable IDR, 
      0 pa,
      null solicitudes,
      s.idetapa,
      responsable_cch.nombre_reembolso_cch
    FROM ( SELECT acc.*, sp.idetapa
           FROM autpagos_caja_chica AS acc
             INNER JOIN solpagos AS sp
           ON sp.idsolicitud IN (acc.idsolicitud)
             WHERE acc.estatus IN( 0, 1, 2) AND acc.nomdepto != 'TARJETA CREDITO' AND sp.caja_chica = 1 ) s 
    INNER JOIN ( SELECT idusuario, nombres, apellidos FROM usuarios ) u ON (s.idResponsable =  u.idusuario) 
    INNER JOIN ( SELECT idEmpresa, abrev FROM empresas ) emp ON(emp.idEmpresa = s.idEmpresa)
    LEFT JOIN (
        SELECT
            cajas_ch.idusuario
            , GROUP_CONCAT(cajas_ch.nombre_reembolso_ch SEPARATOR ', ') nombre_reembolso_cch
        FROM cajas_ch
        GROUP BY cajas_ch.idusuario
    ) AS responsable_cch ON responsable_cch.idusuario = s.idResponsable
    WHERE s.idetapa NOT IN (31)
    ORDER BY Responsable, apellidos");
  }

  function obtenerSolReemb(){
  	$query = $this->db->query("
  	SELECT 
      s.tipoPago, 
      s.referencia, 
      u.idusuario, 
      s.motivoEspera, 
      s.estatus AS ESTATUS, 
      s.idpago as PAG, 
      s.cantidad as cnt,  
      s.idsolicitud AS ID, 
      u.nombres AS Responsable, 
      u.apellidos, 
      emp.abrev, 
      DATE_FORMAT(s.fecreg, '%d/%m/%Y') AS FECHAFACP, 
      s.cantidad AS Cantidad,
      s.nomdepto AS Departamento,
      s.idEmpresa AS Empresa,
      s.idResponsable IDR, 
      0 pa,
      null solicitudes
    FROM ( SELECT autpcc.* FROM autpagos_caja_chica autpcc INNER JOIN solpagos sp ON sp.idsolicitud = autpcc.idsolicitud WHERE sp.caja_chica=3 AND estatus IN( 0, 1, 2)) s 
    INNER JOIN ( SELECT idusuario, nombres, apellidos FROM usuarios ) u ON (s.idResponsable =  u.idusuario) 
    INNER JOIN ( SELECT idEmpresa, abrev FROM empresas ) emp ON(emp.idEmpresa = s.idEmpresa)
    ORDER BY Responsable, apellidos;
  	");
  	return $query;
  }

	function obtenerSolViat(){
    $query = $this->db->query("SELECT 
      s.tipoPago, 
      s.referencia, 
      u.idusuario, 
      s.motivoEspera, 
      s.estatus AS ESTATUS, 
      s.idpago as PAG, 
      s.cantidad as cnt,  
      s.idsolicitud AS ID, 
      u.nombres AS Responsable, 
      u.apellidos, 
      emp.abrev, 
      DATE_FORMAT(s.fecreg, '%d/%m/%Y') AS FECHAFACP, 
      s.cantidad AS Cantidad,
      s.nomdepto AS Departamento,
      s.idEmpresa AS Empresa,
      s.idResponsable IDR, 
      0 pa,
      null solicitudes,
      s.idetapa,
      responsable_cch.nombre_reembolso_cch
      FROM ( SELECT acc.*, sp.idetapa
             FROM autpagos_caja_chica AS acc
             INNER JOIN solpagos AS sp
                ON sp.idsolicitud IN (acc.idsolicitud)
            WHERE acc.estatus IN( 0, 1, 2) AND acc.nomdepto != 'TARJETA CREDITO' AND sp.caja_chica = 4 ) s 
      INNER JOIN ( SELECT idusuario, nombres, apellidos FROM usuarios ) u ON (s.idResponsable =  u.idusuario) 
      INNER JOIN ( SELECT idEmpresa, abrev FROM empresas ) emp ON(emp.idEmpresa = s.idEmpresa)
      LEFT JOIN (
        SELECT
            cajas_ch.idusuario
            , GROUP_CONCAT(cajas_ch.nombre_reembolso_ch SEPARATOR ', ') nombre_reembolso_cch
        FROM cajas_ch
        GROUP BY cajas_ch.idusuario
      ) AS responsable_cch ON responsable_cch.idusuario = s.idResponsable
      WHERE s.idetapa NOT IN (31)
      ORDER BY Responsable, apellidos");
    return $query;
	}

  function obtenerSolTDC(){
    return $this->db->query('SELECT s.tipoPago, s.referencia, u.idproveedor idusuario, s.motivoEspera, s.estatus AS ESTATUS, s.idpago as PAG, 
     s.cantidad as cnt,  s.idsolicitud AS `ID`, u.nombre Responsable, "" apellidos, emp.abrev, DATE_FORMAT(s.fecreg,"%d/%m/%Y")  AS FECHAFACP , s.cantidad AS Cantidad ,s.nomdepto AS Departamento,s.idEmpresa AS Empresa, s.idResponsable IDR 
     FROM autpagos_caja_chica s 
     INNER JOIN proveedores u ON (s.idResponsable =  u.idproveedor) 
     INNER JOIN empresas emp ON(emp.idEmpresa = s.idEmpresa) 
     WHERE s.estatus IN( 0, 1, 2) AND s.nomdepto = "TARJETA CREDITO"');
  }
    
  function getSolicitudesCCH($idsolicitudes){
    return $this->db->query("SELECT s.idsolicitud, 
        s.cantidad,
        s.moneda, 
        s.proyecto,
        IFNULL(s.etapa, '') AS ETAPA,
        IFNULL(s.condominio, '') AS Condominio,
        p.nombre AS nombre_proveedor,
        DATE_FORMAT(s.fecelab,'%d/%m/%Y') AS FECHAFACP,
        s.folio AS Folio, 
        s.justificacion, 
        CONCAT(usuarios.nombres, ' ', usuarios.apellidos) AS nombre_capturista, 
        s.folio, 
        IFNULL(SUBSTRING(facturas.uuid, -5, 5 ), 'SF') AS ffiscal,
        DATE_FORMAT(s.fecelab, '%d/%m/%Y') AS fecelab,
        DATE_FORMAT(s.fecelab, '%d/%m/%Y') AS fecautorizacion
      FROM solpagos AS s
      INNER JOIN proveedores AS p 
        ON(s.idProveedor = p.idProveedor)
      INNER JOIN usuarios
        ON usuarios.idusuario = s.idusuario
      LEFT JOIN (SELECT *, MIN(facturas.feccrea) 
                 FROM facturas
                 WHERE facturas.tipo_factura IN ( 1, 3 ) 
                 GROUP BY facturas.idsolicitud) AS facturas 
        ON facturas.idsolicitud = s.idsolicitud
      WHERE FIND_IN_SET( s.idsolicitud, '$idsolicitudes')")->result_array();
  }
 
  function get_solicitudes_caja_chica(){ 
    return $this->db->query('SELECT CONCAT(usuarios.nombres, usuarios.apellidos) AS names , usuarios.depto, autpagos_caja_chica.cantidad, autpagos_caja_chica.fecha_pago, empresas.abrev from autpagos_caja_chica INNER JOIN usuarios on autpagos_caja_chica.idrealiza=usuarios.idusuario INNER JOIN solpagos on solpagos.idsolicitud = autpagos_caja_chica.idsolicitud INNER JOIN empresas on solpagos.idEmpresa = empresas.idempresa'); 
  } 
 
  function get_solicitudes_pagadas(){
 
     return $this->db->query('SELECT solpagos.idsolicitud, solpagos.folio, solpagos.moneda, solpagos.metoPago,  solpagos.justificacion, empresas.abrev as nemp, solpagos.nomdepto, proveedores.nombre, solpagos.cantidad, DATE_FORMAT(solpagos.fecelab, "%d/%m/%Y") AS fecelab, etapas.idetapa, etapas.nombre AS etapan, facturas.descripcion, facturas.total, solpagos.idusuario, solpagos.caja_chica FROM solpagos INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor INNER JOIN etapas ON etapas.idetapa = solpagos.idetapa inner join facturas on solpagos.idsolicitud=facturas.idsolicitud INNER JOIN empresas ON solpagos.idempresa=empresas.idempresa  WHERE solpagos.idetapa IN ( 9, 10 ) AND solpagos.caja_chica IN (1)'); 
  } 

  function get_solicitudes_pagadaseg(){
       // $filtro =  $this->input->post("nomdepto");
    return $this->db->query('SELECT autpagos.idpago, solpagos.folio, solpagos.moneda, solpagos.metoPago,  solpagos.idsolicitud, solpagos.justificacion, empresas.abrev as nemp, solpagos.nomdepto, proveedores.nombre, solpagos.cantidad, DATE_FORMAT(solpagos.fecelab, "%d/%m/%Y") AS fecelab, etapas.idetapa, etapas.nombre AS etapan, facturas.descripcion, facturas.total, solpagos.idusuario, solpagos.caja_chica, autpagos.cantidad AS autorizado, IFNULL(notifi.visto, 1) AS visto FROM solpagos INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor INNER JOIN etapas ON etapas.idetapa = solpagos.idetapa LEFT JOIN (select *, MIN(facturas.feccrea) from facturas where facturas.tipo_factura in (1, 3) GROUP BY facturas.idsolicitud) as facturas ON solpagos.idsolicitud = facturas.idsolicitud INNER JOIN empresas ON solpagos.idempresa=empresas.idempresa INNER JOIN autpagos ON solpagos.idsolicitud=autpagos.idsolicitud LEFT JOIN ( SELECT notificaciones.idsolicitud, notificaciones.visto FROM notificaciones WHERE notificaciones.idusuario = "'.$this->session->userdata("inicio_sesion")['id'].'" ) AS notifi ON notifi.idsolicitud = solpagos.idsolicitud WHERE autpagos.estatus = 3 AND fecha_pago IS NULL');       
  } 

  function get_solicitudes_pagada_chic(){
       // $filtro =  $this->input->post("nomdepto");
    return $this->db->query('SELECT autpagos_caja_chica.idpago, autpagos_caja_chica.estatus, CONCAT(usuarios.nombres, usuarios.apellidos) AS names_CH, empresas.abrev, autpagos_caja_chica.cantidad, autpagos_caja_chica.fecreg, autpagos_caja_chica.nomdepto FROM autpagos_caja_chica INNER JOIN usuarios ON usuarios.idusuario=autpagos_caja_chica.idResponsable INNER JOIN empresas ON empresas.idempresa = autpagos_caja_chica.idempresa WHERE autpagos_caja_chica.estatus=1');       
  } 

  function update_con_factura($idsol) { 
    // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
    $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
    $this->db->query("UPDATE solpagos SET idetapa=20 WHERE idsolicitud=".$idsol."");
  }

  //PAGO PARA FACTURAJE
  //PARA GENERAR LOS PAGOS QUE SE ACEPTAN PARA FACTORAJE
  //EVIAMOS UN STRING EN FORMATO DE NUMERO, NUMERO
  function update_factoraje_solicitud( $idsolicitudes ) {
    // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
    $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);

    $this->db->trans_begin();

    $factorajes =  $this->db->query("SELECT * from solpagos where idsolicitud IN ( $idsolicitudes )");

    $data = array();
    $log = array();
    $fecha_operacion = date("Y-m-d H:i:s");

    foreach( $factorajes->result() as $row ){
      $data[] = array(
        "idsolicitud" => $row->idsolicitud,
        "idrealiza" => $this->session->userdata("inicio_sesion")['id'],
        "cantidad" => $row->cantidad,
        "fecreg" => $fecha_operacion,
        "estatus" => 0
      );

      $log[] = array(
        "idusuario" => $this->session->userdata("inicio_sesion")['id'],
        "idsolicitud" => $row->idsolicitud,
        "tipomov" => "SE GENERÓ PAGO DE FACTORAJE EN CXP",
        "fecha" => $fecha_operacion
      );
    }

    $this->db->insert_batch( "autpagos", $data );
    $this->db->insert_batch( "logs", $log );

    $this->db->query("UPDATE solpagos SET idetapa = CASE WHEN metoPago LIKE '%BANREGIO' THEN 10 ELSE 11 END WHERE solpagos.idsolicitud IN ( $idsolicitudes ) AND idetapa = 5");
  
    if( $this->db->trans_status() ){
        $this->db->trans_commit();
        return TRUE;
    }else{
        $this->db->trans_rollback();
        return FALSE;
    }

  }

  //ACTUALIZAR ESTATUS DEL 5 AL 7 SOLO SI SON VALIDAS
  function update_sin_factura($idsol) { 
    // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
    $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
    $this->db->query("UPDATE solpagos SET idetapa = 7 WHERE idetapa = 5 AND idsolicitud = $idsol");
  }

  function update_devolucion($idsolicitud, $idetapa) { 
    ///MODIFICAR PARA EL CICLO
    // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
    $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
    $this->db->query("UPDATE solpagos SET idetapa = $idetapa WHERE idsolicitud = $idsolicitud"); //Continua con el flujo de aceptacion
  }

  function update_con_factura_sinprov($idsol) { 
    // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
    $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
    $this->db->query("UPDATE solpagos SET idetapa=7 WHERE idsolicitud=".$idsol."");
  }

    function update_genero_txt($idpago) { 
    // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
    $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
    $this->db->query("UPDATE autpagos SET estatus=1 WHERE idpago IN (".$idpago.")");
  }

  function update_genero_PDF($idpago){ 
    // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
    $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
    $this->db->query("UPDATE autpagos SET estatus=1 WHERE
    idpago IN (".$idpago.")");
  }
  
  function update_proceso3($idsol){ 
    // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
    $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
    $this->db->query("UPDATE solpagos SET idetapa=8 WHERE idsolicitud=".$idsol."");
  }

   function update_proceso3_trans($idpago){ 
    // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
    $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
    $this->db->query("UPDATE autpagos SET estatus=12 WHERE idpago=".$idpago."");
  }

   function update_proceso3_trans_back($idpago){ 
    // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
    $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
    return $this->db->update("autpagos", array( "estatus" => 0 ), "idpago= $idpago");
  }

  function update_chica_enviar($idpago){ 
    $this->db->query("UPDATE autpagos_caja_chica SET estatus=12 WHERE idpago=".$idpago."");
  }

  function update_chica_back($idpago){ 
    return $this->db->update("autpagos_caja_chica", array( "estatus" => 0 ), "idpago= $idpago");
  }
  
  function update_proceso2b($idpago){ 
    // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
    $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
    $this->db->query("UPDATE autpagos SET fecha_pago=current_date() WHERE idpago= '$idpago'");
  }

  function update_proceso2b_ch2($idpago) { 
    $this->db->query("UPDATE autpagos_caja_chica SET fecha_pago=current_date() WHERE idpago= '$idpago'");
  }

  function update_proceso2b_ch($idpago) { 
    $this->db->query("UPDATE autpagos_caja_chica SET fecha_pago=current_date() WHERE idpago= '$idpago'");
  }

  function update_proceso3b($idsol) { 
    // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
    $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
    $this->db->query("UPDATE autpagos SET estatus = 2 WHERE idpago = ".$idsol."");
  }

  function update_caja_chica($formaPago,$numProv,$dato3) {
    $this->db->query('UPDATE autpagos_caja_chica  SET estatus=1,formaPago="'.$formaPago.'", idproveedor='.$numProv.' WHERE idpago='.$dato3.'');
  }

 

 function soli($emp, $cta, $filtro){
    if (($filtro!=""||$filtro!=NULL) && $filtro == 1) {
          return $query = $this->db->query("SELECT solpagos.idsolicitud, solpagos.proyecto, solpagos.justificacion, solpagos.metoPago, facturas.fecfac, facturas.foliofac, facturas.total, facturas.observaciones, autpagos.cantidad, usuarios.nombres, usuarios.apellidos, proveedores.nombre, empresas.abrev,  solpagos.moneda, autpagos.tipoCambio, autpagos.idpago, empresas.idempresa ,solpagos.idProveedor,solpagos.idproveedor, proveedores.email,proveedores.rfc, proveedores.tipocta, proveedores.cuenta, proveedores.nombre as prov, bancos.clvbanco,proveedores.alias FROM solpagos INNER JOIN empresas ON empresas.idempresa=solpagos.idEmpresa INNER JOIN proveedores ON proveedores.idproveedor=solpagos.idProveedor INNER JOIN bancos ON bancos.idbanco=proveedores.idbanco   INNER JOIN autpagos ON autpagos.idsolicitud=solpagos.idsolicitud INNER JOIN usuarios ON solpagos.idusuario=usuarios.idusuario LEFT JOIN (select *, MIN(facturas.feccrea) from facturas where facturas.tipo_factura in (1, 3) GROUP BY facturas.idsolicitud) as facturas ON solpagos.idsolicitud = facturas.idsolicitud WHERE autpagos.estatus IN (0,11)  AND solpagos.metoPago LIKE 'TEA' AND ((autpagos.tipoCambio <> '' AND solpagos.moneda != 'MXN') OR (solpagos.moneda = 'MXN')) AND solpagos.idempresa= ".$emp." AND autpagos.fecha_pago IS NULL AND solpagos.nomdepto='CONSTRUCCION'");
    }

    if (($filtro!=""||$filtro!=NULL) && $filtro == 2) {
      return $query = $this->db->query("SELECT solpagos.idsolicitud, solpagos.proyecto, solpagos.justificacion, solpagos.metoPago, facturas.fecfac, facturas.foliofac, facturas.total, facturas.observaciones, autpagos.cantidad, usuarios.nombres, usuarios.apellidos, proveedores.nombre, empresas.abrev,  solpagos.moneda, autpagos.tipoCambio, autpagos.idpago, empresas.idempresa ,solpagos.idProveedor,solpagos.idproveedor, proveedores.email,proveedores.rfc, proveedores.tipocta, proveedores.cuenta, proveedores.nombre as prov, bancos.clvbanco,proveedores.alias FROM solpagos INNER JOIN empresas ON empresas.idempresa=solpagos.idEmpresa INNER JOIN proveedores ON proveedores.idproveedor=solpagos.idProveedor INNER JOIN bancos ON bancos.idbanco=proveedores.idbanco   INNER JOIN autpagos ON autpagos.idsolicitud=solpagos.idsolicitud INNER JOIN usuarios ON solpagos.idusuario=usuarios.idusuario LEFT JOIN (select *, MIN(facturas.feccrea) from facturas where facturas.tipo_factura in (1, 3) GROUP BY facturas.idsolicitud) as facturas ON solpagos.idsolicitud = facturas.idsolicitud WHERE autpagos.estatus IN (0,11)  AND solpagos.metoPago LIKE 'TEA' AND ((autpagos.tipoCambio <> '' AND solpagos.moneda != 'MXN') OR (solpagos.moneda = 'MXN')) AND solpagos.idempresa= ".$emp." AND autpagos.fecha_pago IS NULL AND solpagos.nomdepto<>'CONSTRUCCION'");
    }

    if (($filtro!=""||$filtro!=NULL) && $filtro == 0) {
      return $query = $this->db->query("SELECT solpagos.idsolicitud, solpagos.proyecto, solpagos.justificacion, solpagos.metoPago, facturas.fecfac, facturas.foliofac, facturas.total, facturas.observaciones, autpagos.cantidad, usuarios.nombres, usuarios.apellidos, proveedores.nombre, empresas.abrev,  solpagos.moneda, autpagos.tipoCambio, autpagos.idpago, empresas.idempresa ,solpagos.idProveedor,solpagos.idproveedor, proveedores.email,proveedores.rfc, proveedores.tipocta, proveedores.cuenta, proveedores.nombre as prov, bancos.clvbanco,proveedores.alias FROM solpagos INNER JOIN empresas ON empresas.idempresa=solpagos.idEmpresa INNER JOIN proveedores ON proveedores.idproveedor=solpagos.idProveedor INNER JOIN bancos ON bancos.idbanco=proveedores.idbanco   INNER JOIN autpagos ON autpagos.idsolicitud=solpagos.idsolicitud INNER JOIN usuarios ON solpagos.idusuario=usuarios.idusuario LEFT JOIN (select *, MIN(facturas.feccrea) from facturas where facturas.tipo_factura in (1, 3) GROUP BY facturas.idsolicitud) as facturas ON solpagos.idsolicitud = facturas.idsolicitud WHERE autpagos.estatus IN (0,11)  AND solpagos.metoPago LIKE 'TEA' AND ((autpagos.tipoCambio <> '' AND solpagos.moneda != 'MXN') OR (solpagos.moneda = 'MXN')) AND solpagos.idempresa= ".$emp." AND autpagos.fecha_pago IS NULL");
    }     
  }





 function soli_F($emp, $cta, $filtro){

        if (($filtro!=""||$filtro!=NULL) && $filtro == 1) {
             return $query = $this->db->query("SELECT solpagos.idsolicitud, solpagos.proyecto, solpagos.justificacion, solpagos.metoPago, facturas.fecfac, facturas.foliofac, facturas.total, facturas.observaciones, autpagos.cantidad, usuarios.nombres, usuarios.apellidos, proveedores.nombre, empresas.abrev,  solpagos.moneda, autpagos.tipoCambio, autpagos.idpago, empresas.idempresa ,solpagos.idProveedor,solpagos.idproveedor, proveedores.email,proveedores.rfc, proveedores.tipocta, proveedores.cuenta, proveedores.nombre as prov, bancos.clvbanco,proveedores.alias FROM solpagos INNER JOIN empresas ON empresas.idempresa=solpagos.idEmpresa INNER JOIN proveedores ON proveedores.idproveedor=solpagos.idProveedor INNER JOIN bancos ON bancos.idbanco=proveedores.idbanco   INNER JOIN autpagos ON autpagos.idsolicitud=solpagos.idsolicitud INNER JOIN usuarios ON solpagos.idusuario=usuarios.idusuario LEFT JOIN (select *, MIN(facturas.feccrea) from facturas where facturas.tipo_factura in (1, 3) GROUP BY facturas.idsolicitud) as facturas ON solpagos.idsolicitud = facturas.idsolicitud WHERE autpagos.estatus IN (0,11)  AND (solpagos.metoPago LIKE 'FACT BAJIO' or solpagos.metoPago LIKE 'FACT BANREGIO') AND ((autpagos.tipoCambio <> '' AND solpagos.moneda != 'MXN') OR (solpagos.moneda = 'MXN')) AND solpagos.idempresa= ".$emp." AND autpagos.fecha_pago IS NULL AND solpagos.nomdepto='CONSTRUCCION'");
        }

        if (($filtro!=""||$filtro!=NULL) && $filtro == 2) {
             return $query = $this->db->query("SELECT solpagos.idsolicitud, solpagos.proyecto, solpagos.justificacion, solpagos.metoPago, facturas.fecfac, facturas.foliofac, facturas.total, facturas.observaciones, autpagos.cantidad, usuarios.nombres, usuarios.apellidos, proveedores.nombre, empresas.abrev,  solpagos.moneda, autpagos.tipoCambio, autpagos.idpago, empresas.idempresa ,solpagos.idProveedor,solpagos.idproveedor, proveedores.email,proveedores.rfc, proveedores.tipocta, proveedores.cuenta, proveedores.nombre as prov, bancos.clvbanco,proveedores.alias FROM solpagos INNER JOIN empresas ON empresas.idempresa=solpagos.idEmpresa INNER JOIN proveedores ON proveedores.idproveedor=solpagos.idProveedor INNER JOIN bancos ON bancos.idbanco=proveedores.idbanco   INNER JOIN autpagos ON autpagos.idsolicitud=solpagos.idsolicitud INNER JOIN usuarios ON solpagos.idusuario=usuarios.idusuario LEFT JOIN (select *, MIN(facturas.feccrea) from facturas where facturas.tipo_factura in (1, 3) GROUP BY facturas.idsolicitud) as facturas ON solpagos.idsolicitud = facturas.idsolicitud WHERE autpagos.estatus IN (0,11)  AND (solpagos.metoPago LIKE 'FACT BAJIO' or solpagos.metoPago LIKE 'FACT BANREGIO') AND ((autpagos.tipoCambio <> '' AND solpagos.moneda != 'MXN') OR (solpagos.moneda = 'MXN')) AND solpagos.idempresa= ".$emp." AND autpagos.fecha_pago IS NULL AND solpagos.nomdepto<>'CONSTRUCCION'");
        }


         if (($filtro!=""||$filtro!=NULL) && $filtro == 0) {
            return $query = $this->db->query("SELECT solpagos.idsolicitud, solpagos.proyecto, solpagos.justificacion, solpagos.metoPago, facturas.fecfac, facturas.foliofac, facturas.total, facturas.observaciones, autpagos.cantidad, usuarios.nombres, usuarios.apellidos, proveedores.nombre, empresas.abrev,  solpagos.moneda, autpagos.tipoCambio, autpagos.idpago, empresas.idempresa ,solpagos.idProveedor,solpagos.idproveedor, proveedores.email,proveedores.rfc, proveedores.tipocta, proveedores.cuenta, proveedores.nombre as prov, bancos.clvbanco,proveedores.alias 
              FROM solpagos 
              INNER JOIN empresas ON empresas.idempresa=solpagos.idEmpresa 
              INNER JOIN proveedores ON proveedores.idproveedor=solpagos.idProveedor 
              LEFT JOIN bancos ON bancos.idbanco=proveedores.idbanco   
              INNER JOIN autpagos ON autpagos.idsolicitud=solpagos.idsolicitud 
              INNER JOIN usuarios ON solpagos.idusuario=usuarios.idusuario 

              LEFT JOIN (select *, MIN(facturas.feccrea) from facturas where facturas.tipo_factura in (1, 3) GROUP BY facturas.idsolicitud) as facturas ON solpagos.idsolicitud = facturas.idsolicitud 

              WHERE autpagos.estatus IN (0,11)  AND (solpagos.metoPago LIKE 'FACT BAJIO' or solpagos.metoPago LIKE 'FACT BANREGIO') AND ((autpagos.tipoCambio <> '' AND solpagos.moneda != 'MXN') OR (solpagos.moneda = 'MXN')) AND solpagos.idempresa= ".$emp." AND autpagos.fecha_pago IS NULL");

        }

       
          
    }

  function soli_ot($data){
    return $this->db->query("SELECT autpagos.tipoCambio, usuarios.apellidos,usuarios.nombres,solpagos.proyecto,solpagos.justificacion, autpagos.idpago,solpagos.metoPago, facturas.fecfac,autpagos.tipoCambio, solpagos.moneda,  autpagos.referencia,solpagos.idEmpresa, solpagos.idsolicitud,solpagos.idProveedor,solpagos.idproveedor, proveedores.email,proveedores.rfc, proveedores.tipocta, proveedores.nombre , proveedores.cuenta,  facturas.foliofac, facturas.observaciones, facturas.total, autpagos.cantidad, empresas.abrev FROM solpagos inner JOIN empresas ON empresas.idempresa=solpagos.idEmpresa inner JOIN proveedores ON proveedores.idproveedor=solpagos.idProveedor LEFT JOIN (select *, MIN(facturas.feccrea) from facturas where facturas.tipo_factura in (1, 3) GROUP BY facturas.idsolicitud) as facturas ON solpagos.idsolicitud = facturas.idsolicitud INNER JOIN autpagos ON autpagos.idsolicitud=solpagos.idsolicitud  INNER JOIN usuarios ON solpagos.idResponsable =usuarios.idusuario WHERE solpagos.idEmpresa = ".$data." AND (autpagos.estatus IN (0, 11) AND solpagos.metoPago IN ('MAN', 'DOMIC', 'EFEC') OR autpagos.estatus IN ( 11, 13 ) AND solpagos.metoPago = 'ECHQ') AND(solpagos.moneda = 'MXN' OR solpagos.moneda<>'MXN' AND autpagos.tipoCambio IS NOT NULL)");
  }

  //RECUPERAMOS TODOS LOS PAGOS QUE SE HAN MARCADO COMO PAUSA.
  function get_solicitudes_stop(){
    return $this->db->query("SELECT ap.*, ep.abrev
      FROM (
        SELECT
          ap.idpago,
          ap.idsolicitud,
          ap.fecreg,
          ap.cantidad,
          IFNULL(ap.tipoPago, sp.metoPago) AS tipoPago,
          ap.motivoEspera,
          sp.justificacion,
          sp.prioridad,
          ap.referencia folio,
          sp.nomdepto,
          pr.nombre,
          sp.idempresa,
          1 notab
        FROM
          autpagos ap
        CROSS JOIN solpagos sp ON sp.idsolicitud = ap.idsolicitud AND ap.estatus IN (12)
        CROSS JOIN (SELECT idproveedor, nombre FROM proveedores) pr ON pr.idproveedor = sp.idproveedor
        UNION
        SELECT 
          apch.idpago,
          'ND' idsolicitud,
          apch.fecreg,
          apch.cantidad,
          apch.tipoPago,
          apch.motivoEspera,
          'REEMBOLSO DE CAJA CHICA' justificacion,
          NULL prioridad,
          apch.referencia folio,
          apch.nomdepto,
          usuario. nombre_completo nombre,
          apch.idempresa,
          2 notab
        FROM
          autpagos_caja_chica apch
        CROSS JOIN
          listado_usuarios usuario ON usuario.idusuario = apch.idResponsable
        WHERE 
          apch.estatus IN (12) ) ap
      CROSS JOIN empresas ep ON ap.idempresa = ep.idempresa
      ORDER BY ap.fecreg DESC");
  } 

  function soli_chica( $data ){
    return $this->db->query("SELECT autpagos_caja_chica.estatus, autpagos_caja_chica.idpago, autpagos_caja_chica.referencia, autpagos_caja_chica.tipoPago, autpagos_caja_chica.fecreg, autpagos_caja_chica.nomdepto, autpagos_caja_chica.cantidad, usuarios.nombres, usuarios.apellidos, proveedores.nombre, empresas.abrev FROM autpagos_caja_chica LEFT JOIN usuarios ON autpagos_caja_chica.idResponsable =usuarios.idusuario LEFT JOIN proveedores ON autpagos_caja_chica.idproveedor=proveedores.idproveedor LEFT JOIN empresas ON autpagos_caja_chica.idEmpresa=empresas.idempresa WHERE (autpagos_caja_chica.estatus=1 or autpagos_caja_chica.estatus=2 or autpagos_caja_chica.estatus = 0 ) AND autpagos_caja_chica.idempresa = $data");
  }

  function get_solicitudes_autorizadas_dp(){
    return $this->db->query("SELECT solpagos.programado, 
          autpagos.idsolicitud, 
          autpagos.idpago,
          autpagos.fechaDis,
          proveedores.nombre,
          proveedores.excp,
          solpagos.cantidad,
          autpagos.cantidad as CA, 
          solpagos.metoPago, 
          empresas.abrev, 
          empresas.idempresa, 
          autpagos.estatus, 
          solpagos.nomdepto, 
          solpagos.prioridad, 
          solpagos.folio,
          if(solpagos.nomdepto = 'DEVOLUCIONES' and solpagos.programado is not null, 'S', 'N' ) esParcialidad
        FROM autpagos 
        INNER JOIN solpagos 
          ON solpagos.idsolicitud = autpagos.idsolicitud 
        INNER JOIN proveedores 
          ON solpagos.idProveedor =  proveedores.idproveedor 
        INNER JOIN empresas 
          ON empresas.idempresa = solpagos.idEmpresa 
        WHERE autpagos.estatus IN(15) AND IFNULL( autpagos.tipoPago,solpagos.metoPago ) NOT IN ('ECHQ', 'EFEC')");
  }

  //RECUPERAMOS SOLO LOS GASTOS DE CAJA CHICA.
  function get_solicitudes_autorizadas_dp_cch(){
    return $this->db->query("SELECT
        ach.idpago,
        ach.fechaDis,
        CONCAT (u.nombres, ' ', u.apellidos) AS responsable,
        ach.cantidad,
        ach.tipoPago,
        e.abrev,
        e.idempresa,
        ach.estatus,
        ach.nomdepto,
        responsable_cch.nombre_reembolso_cch
      FROM
        autpagos_caja_chica as ach
      INNER JOIN usuarios as u ON u.idusuario = ach.idResponsable
      INNER JOIN empresas as e ON e.idempresa = ach.idEmpresa AND nomdepto != 'TARJETA CREDITO'
      LEFT JOIN (
          SELECT
              cajas_ch.idusuario
              , GROUP_CONCAT(cajas_ch.nombre_reembolso_ch SEPARATOR ', ') nombre_reembolso_cch
          FROM cajas_ch
          GROUP BY cajas_ch.idusuario
      ) AS responsable_cch ON responsable_cch.idusuario = ach.idResponsable
      WHERE ach.estatus IN (15)
      AND ach.tipoPago != 'ECHQ'
    ");
  }

	//RECUPERAMOS SOLO LOS GASTOS DE TARJETAS DE CREDITO.
  function get_solicitudes_autorizadas_dp_TDC(){
    return $this->db->query("SELECT ach.idpago, ach.fechaDis,  r.nombre responsable, ach.cantidad, ach.tipoPago, e.abrev, e.idempresa, ach.estatus, ach.nomdepto 
	FROM autpagos_caja_chica as ach 
	INNER JOIN proveedores r ON r.idproveedor = ach.idResponsable 
	INNER JOIN empresas as e ON e.idempresa = ach.idEmpresa AND nomdepto = 'TARJETA CREDITO' 
	WHERE ach.estatus IN (15) AND ach.tipoPago != 'ECHQ'");
  } 

  function abonos($abonos){
        // return $this->db->query('SELECT folio FROM solpagos WHERE idsolicitud='.$abonos.'');
    return $this->db->query('SELECT sum(cantidad) as abono FROM autpagos WHERE fecha_pago IS NOT NULL AND estatus = 3 AND idsolicitud='.$abonos.'');
  } 

  function abonos_ot($abonos){
        // return $this->db->query('SELECT folio FROM solpagos WHERE idsolicitud='.$abonos.'');
    return $this->db->query('SELECT sum(cantidad) as abono FROM autpagos WHERE fecha_pago IS NOT NULL AND estatus = 3 AND idsolicitud='.$abonos.'');
  } 

  function get_listado_proveedores($dato){
    return $this->db->query('SELECT proveedores.idproveedor, proveedores.alias, proveedores.cuenta, bancos.nombre as nombanco FROM proveedores INNER JOIN bancos ON bancos.idbanco = proveedores.idbanco WHERE idusuario='.$dato.'');
  }

  function get_listado_proveedoresbyid($dato){
    return $this->db->query('SELECT proveedores.idproveedor, proveedores.alias, proveedores.cuenta, bancos.nombre as nombanco FROM proveedores INNER JOIN bancos ON bancos.idbanco = proveedores.idbanco WHERE idproveedor='.$dato.'');
  }
  
  function update_autoPago( $idautpago, $data ){
    // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
    $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
    return $this->db->update( "autpagos", $data, "idpago = '$idautpago'" );
  }

    function update_autoPago_interes( $idautpago, $data ){
      // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
      $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
    return $this->db->update( "autpagos", $data, "idpago = '$idautpago'" );
  }

  function update_referencia($serie_cheque, $numpago, $numctaserie){
    // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
    $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
    return $this->db->query("UPDATE autpagos SET referencia = ".$serie_cheque.", tipoPago = 'ECHQ' WHERE idpago = ".$numpago);
    return $this->db->query("UPDATE serie_cheques SET serie = ".($serie_cheque+1)." WHERE idCuenta = ".$numctaserie."");
  }


  function get_Ctas_epago($dato){
    $cons_emp =  $this->db->query('SELECT idempresa FROM solpagos INNER JOIN autpagos ON autpagos.idsolicitud = solpagos.idsolicitud WHERE idpago ='.$dato.'');
    return $this->db->query('SELECT bancos.nombre as nombanco, cuentas.idcuenta, cuentas.nombre, cuentas.nodecta FROM cuentas INNER JOIN bancos ON bancos.idbanco = cuentas.idbanco WHERE idempresa ='.$cons_emp->row()->idempresa.'');
  }


  function get_Ctas_epago_ch($dato){
    $cons_emp =  $this->db->query('SELECT idempresa FROM  autpagos_caja_chica WHERE idpago ='.$dato.'');
    return $this->db->query('SELECT bancos.nombre as nombanco, cuentas.idcuenta, cuentas.nombre, cuentas.nodecta FROM cuentas INNER JOIN bancos ON bancos.idbanco = cuentas.idbanco WHERE idempresa ='.$cons_emp->row()->idempresa.'');
  }

  function get_Ctas_value_chques($dato){
    if ($dato!="") {
      return $this->db->query('SELECT serie FROM serie_cheques WHERE idcuenta ='.$dato.'');
    }else{
      return "";
    }
  }

  function get_Ctas_value_chques_chica($dato){
    if ($dato!="") {
     return $this->db->query('SELECT serie FROM serie_cheques WHERE idcuenta ='.$dato.'');
    }else{
      return "";
    }
  }

  function actualizar_solictud( $idsolicitud, $datos ){
    // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
    $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
    return $this->db->update( "solpagos", $datos, "idsolicitud = $idsolicitud" );
  }

  function sol_individual( $idsolicitud ){
      return $this->db->query("SELECT polizas_provision.idprovision,
          autpagos.estatus_pago,
          solpagos.idetapa,
          solpagos.proyecto,
          solpagos.condominio, 
          solpagos.etapa setapa,
          solpagos.folio,
          solpagos.moneda,
          solpagos.programado,
          IF( solpagos.programado IS NOT NULL, CASE WHEN solpagos.programado < 7 THEN TIMESTAMPDIFF(MONTH, CONCAT( YEAR(solpagos.fecelab),'-',MONTH(solpagos.fecelab),'-', DAY(solpagos.fecha_fin) ), IFNULL( solpagos.fecha_fin, DATE_ADD( solpagos.fecelab, INTERVAL IF( solpagos.idetapa NOT IN ( 11, 30 ), tpagos + 1, IFNULL(tpagos, 1) )  MONTH) ) ) ELSE TIMESTAMPDIFF(WEEK, solpagos.fecelab, IFNULL(solpagos.fecha_fin, DATE_ADD( solpagos.fecelab, INTERVAL IFNULL( tpagos, 1 )  WEEK) ) ) END, NULL ) mpagar,
          facturas.tipo_factura,
          IFNULL( facturas.uuid, 'NO' ) AS tienefac,
          SUBSTRING(facturas.uuid, - 5, 5) AS folifis,
          solpagos.metoPago,
          usuarios.nombre_completo nombres,
          capturista.nombre_completo,
          solpagos.caja_chica,
          solpagos.idsolicitud,
          solpagos.justificacion,
          solpagos.nomdepto,
          proveedores.nombre,
          IF(solpagos.programado IS NOT NULL, solpagos.cantidad + IFNULL(autpagos.pagado, 0),  solpagos.cantidad) AS cantidad,
          empresas.abrev,
          empresas.abrev as nemp,
          solpagos.fechaCreacion feccrea,
          DATE_FORMAT(solpagos.fechaCreacion, '%d/%m/%Y') AS fechaCreacion, 
          solpagos.fecha_autorizacion,
          solpagos.fecelab,
          etapas.nombre AS etapa,
          IFNULL( autpagos.pagado, 0 ) AS pagado,
          autpagos.fechaDis fechaDis2,
          solpagos.fecha_autorizacion fech_auto,
          facturas.idfactura,
          facturas.idsolicitudr,
          facturas.uuid okfactura
        FROM 
        ( SELECT * FROM solpagos WHERE solpagos.idsolicitud = ? ) solpagos
        INNER JOIN listado_usuarios usuarios ON usuarios.idusuario = solpagos.idResponsable
        INNER JOIN listado_usuarios capturista ON capturista.idusuario = solpagos.idusuario
        INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor
        INNER JOIN etapas ON etapas.idetapa = solpagos.idetapa
        INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa
        LEFT JOIN  vw_autpagos autpagos ON autpagos.idsolicitud = solpagos.idsolicitud
        LEFT JOIN factura_registro facturas ON facturas.idsolicitud = solpagos.idsolicitud
        LEFT JOIN ( SELECT  polizas_provision.idprovision, polizas_provision.idsolicitud, MIN( polizas_provision.fecreg ) FROM polizas_provision GROUP BY polizas_provision.idsolicitud) AS polizas_provision ON polizas_provision.idsolicitud = solpagos.idsolicitud", [ $idsolicitud ] );
  }

  function cambiar_cant_pprogM($post){
    // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
    $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
    $data_db=array("cantidad"=>preg_replace('#[^0-9\.]#', '', $post["cantidad"]));
    $data_db["fecelab"]=DateTime::createFromFormat('d/m/Y', $post["fechaini"])->format('Y-m-d');
    $data_db["programado"]=$post["per_pago"];
    if($post["fechafin"]=='')
      $data_db["fecha_fin"]=null;
    else
      $data_db["fecha_fin"]=DateTime::createFromFormat('d/m/Y', $post["fechafin"])->format('Y-m-d');
    return $this->db->update("solpagos",$data_db,array("idsolicitud"=>$post["idsolicitud"])); /**  Modificación al actualizar las solicitudes desde el modulo de cuentas por pagar | FECHA: 16-Mayo-2024 | @author Lalo Silva <programador.analista38@ciudadmaderas.com>  **/
  }

  function cambiarmetodoM($idpago,$metodo){
    // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
    $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
    $sol_consulta =  $this->db->query("SELECT a.*,s.metoPago FROM autpagos a join solpagos s on a.idsolicitud=s.idsolicitud where a.idpago = ".$idpago);
    $this->db->query("UPDATE autpagos SET 
     fechacp=null,  estatus='0',  estatus_factura=null,  reg_factura=null,  tipoPago=null,  formaPago=null,  referencia=null,  fechaOpe=null,  tipoCambio=null,  interes=null,  fecha_pago=null,  descarga=null,  fechDesc=null
     ,fechaDis=null, motivoEspera=null, fecha_cobro=null WHERE idpago = ".$idpago);

    $this->db->query("UPDATE solpagos SET metoPago = '".$metodo."' WHERE idsolicitud = ".$sol_consulta->row(0)->idsolicitud);

    if($this->db->affected_rows() > 0){
      log_sistema($this->session->userdata("inicio_sesion")['id'], $sol_consulta->row()->idsolicitud, "CAMBIÓ EL METODO DE PAGO DE ".$sol_consulta->row(0)->metoPago." A $metodo");
      return true;
    }else
      return false;
  }
  function reg_plan_prog($datos){
    return $this->db->insert("planes_pagosprog",$datos);
  }
  function act_plan_prog($datos,$where){
    return $this->db->update("planes_pagosprog",$datos,$where);
  }

  //INSERCION DE GASTOS NO DEDUCIBLES
  function insertGastoNodeducible( $data ){
    return $this->db->query("INSERT INTO solpagos (
        proyecto,
        folio,
        idEmpresa,
        idResponsable,
        idusuario,
        nomdepto,
        idProveedor,
        caja_chica,
        cantidad,
        moneda,
        metoPago,
        justificacion,
        fecelab,
        idetapa,
        fechaCreacion,
        fecha_autorizacion
    ) 
    SELECT 
        ? proyecto,
        'NA' folio,
        idempresa idEmpresa,
        ? idResponsable,
        ? idusuario,
        ? nomdepto,
        2936 idProveedor,
        2 caja_chica,
        ? cantidad,
        ? moneda,
        'MAN' metoPago,
        ? justificacion,
        ? fecelab,
        5 idetapa,
        ? fechaCreacion,
        ? fecha_autorizacion
    FROM tcredito
    WHERE idtcredito = ?", $data );
  }

  function updateSolicitudFactura( $idsolicitud, $isTendraFact ) {
    // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
    $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
    return $this->db->query("UPDATE solpagos SET tendrafac = ? WHERE idsolicitud = ?",[$isTendraFact, $idsolicitud]);
  }

  public function tabla_proyectos_departamentos(){
    return $this->db->query("SELECT idProyectos, nombre as nombre_proyecto, estatus FROM proyectos_departamentos");
  }
  public function tabla_oficinas_sedes(){
    return $this->db->query("SELECT
        ofi.idOficina,
        ofi.nombre nombre_oficina,
        est.id_estado id_estado,
        ofi.estatus,
        est.estado
      FROM
        oficina_sede ofi
      LEFT JOIN estados est on ofi.id_estado = est.id_estado");
  }
  function get_solicitudes_devolucion(){
         
       $query = "SELECT 
            solpagos.idsolicitud,
            proveedores.nombre proveedor,
            solpagos.cantidad,
            solpagos.moneda,
            solpagos.justificacion,
            solpagos.metoPago,
            solpagos.fecha_fin,
            proveedores.nombre,
            solpagos.intereses,
            solpagos.cantidad,
            solpagos.fecelab fecreg,
            solpagos.nomdepto,
            empresas.abrev AS nemp,
            CONCAT(usuarios.nombres,' ', usuarios.apellidos) capturista
          FROM solpagos
            INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor
            INNER JOIN empresas ON solpagos.idempresa = empresas.idempresa
            INNER JOIN usuarios ON usuarios.idusuario = solpagos.idusuario
          WHERE
              solpagos.programado IS NULL 
              AND solpagos.nomdepto IN ('DEVOLUCIONES', 'TRASPASO', 'DEVOLUCION' )
              AND solpagos.idetapa in ( 5 )
          ORDER BY proveedores.nombre ASC";

        return $this->db->query($query);
  }

  function update_devoluciones_pago_completado($idsolicitud){ 
    // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
    $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
    $this->db->query("UPDATE autpagos SET estatus=16 WHERE idsolicitud = $idsolicitud");
  }
}
