<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Provedores_model extends CI_Model {

    function __construct(){
        parent::__construct();
    }

    /*ESTATUS DE PROVEEDORES*/
    /*
      0 PROVEEDOR BLOQUEADO
      1 PROVEEDOR EN EL CATALOGO PRINCIPAL
      2 PROVEEDOR TEMPORAL
      3 PROVEEDOR ELIMINADO
      4 PROVEEDORES DE CAJA CHICA

    */

    function getbancos(){
      return $this->db->query("SELECT idbanco, nombre, SUBSTRING( clvbanco, -3, 3 ) clvbanco FROM bancos WHERE estatus = 1 ORDER BY nombre");
    }

    function getbancos_extranjero(){
      return $this->db->query("SELECT idbanco, nombre, clvbanco FROM bancos WHERE estatus = 2 GROUP BY clvbanco");
    }

    function getProveedoresConta(){
      return $this->db->query("SELECT rfc_proveedor rfc, rs_proveedor nproveedor, IFNULL(rf_proveedor,'SIN DEFINIR') regimenfiscal, cp_proveedor dirfiscal FROM cat_proveedor");
    }

    function getProv_cat($rfc){
      return $this->db->query("SELECT * FROM cat_proveedor WHERE rfc_proveedor = ? ",[$rfc]);
    }

    function getRFCEmpresa( $rfc ){
      return $this->db->query("SELECT * FROM prov_empresa_clvneo WHERE rfc_prov = ?", [ $rfc ]);
    }

    function insertRFCEmp( $rfc, $concepto, $empresas, $moneda, $tasa ){
      $this->db->trans_begin();

      $data = [];
      $c = is_array( $empresas ) ? count( $empresas ) : 0;
      for( $i = 0; $i < $c; $i++ ){
        $data[] = [
          "idempresa" =>  $empresas[$i],
          "clvneo" => strtoupper($concepto[$i]),
          "moneda_sat" => strtoupper($moneda[$i]),
          "tasa" => strtoupper($tasa[$i]),
          "rfc_prov" => $rfc,
          "idcrea" => $this->session->userdata("inicio_sesion")["id"],
          "fcreacion" => date("Y-m-d H:i:s")
        ];
      }

      $this->db->delete( "prov_empresa_clvneo", "rfc_prov = '$rfc'" );

      if( count( $data ) > 0 )
        $this->db->insert_batch( "prov_empresa_clvneo", $data );

      $resultado = $this->db->trans_status();
      if ( $resultado === FALSE){
          $this->db->trans_rollback();
      }else{
          $this->db->trans_commit();
      }
      return [ "resultado" => $resultado, "data" => $data ];
    }

    function txt_proveedores( $idproveedor ){
      return $this->db->query("SELECT 
      bancos.clvbanco banco,
      proveedores.idproveedor idprov,
      proveedores.cuenta num_cuenta,
      proveedores.alias alias,
      proveedores.nombre nombre_beneficiario,
      proveedores.rfc rfc,
      proveedores.email email,
      proveedores.tipocta tipo
      FROM proveedores INNER JOIN bancos ON bancos.idbanco = proveedores.idbanco
      WHERE idproveedor IN ( $idproveedor )
      ORDER BY nombre_beneficiario");
    }

    function getCuenta( $cuenta ){
      return $this->db->query("SELECT * FROM proveedores WHERE proveedores.cuenta = '$cuenta'");
    }

    function cambiar_estatus_proveedor( $idproveedor, $estatus, $observaciones ){
      // FECHA : 01-JULIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
      $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
      /**
       * FECHA : 01-JULIO-2025 | @author Efrain Martinez progamador.analista38@ciudadmaderas.com | SE AGREGA EL ID DEL USUARIO QUE REALIZO LA ACTUALIZACION
       * Y LA FECHA EN QUE FUE REALIZADA
       */
      return $this->db->update( "proveedores", array(
        "idupdate" => $this->session->userdata("inicio_sesion")['id'],
        "fecha_update" => date("Y-m-d H:i:s"),
        "estatus" => $estatus,
        "observaciones" => $observaciones
        ), "idproveedor = '$idproveedor'" );
    }
    
    function getsucursal(){
        return $this->db->query("SELECT * FROM estados WHERE pais = 'MX' AND estatus = '1' ORDER BY estado");
    }
    
    function getusers(){
      return $this->db->query("SELECT usuarios.idusuario, usuarios.nombres, usuarios.apellidos FROM usuarios ORDER BY usuarios.nombres, usuarios.apellidos");
    }
    
    function getuserscc(){
      return $this->db->query("SELECT usuarios.idusuario, usuarios.nombres, usuarios.apellidos 
        FROM ( SELECT usuarios.idusuario, usuarios.nombres, usuarios.apellidos FROM usuarios WHERE estatus = 1 ) usuarios
        INNER JOIN ( SELECT idusuario FROM cajas_ch WHERE estatus = 1 ) cch ON cch.idusuario = usuarios.idusuario
        ORDER BY usuarios.nombres, usuarios.apellidos");
    }

    function insertar_nuevo($data){  
        return $this->db->insert("proveedores", $data);
    }

    /************************************************************************/
    //LISTADO DE PROVEEDORES DE TIPO proveedores
    function get_provs(){
      return $this->db->query("SELECT p.*,
                                      b.idbanco, 
                                      b.nomba AS nomba,
                                      e.id_estado,
                                      e.estado,
                                      DATE_FORMAT(fecadd,'%d/%m/%Y') AS fecha,
                                      CASE 
                                        WHEN p.excp = 0 THEN 
                                          'OBLIGATORIO CARGAR XML' 
                                        WHEN p.excp = 1 THEN 
                                          'XML POSTERIOR AL PAGO'
                                        ELSE 'NUNCA SE RECIBIRA FACTURA' 
                                      END AS facturacion
                                FROM proveedores p
                                LEFT JOIN (SELECT idbanco, nombre AS nomba FROM bancos ) AS b ON p.idbanco = b.idbanco 
                                LEFT JOIN estados e ON p.sucursal = e.id_estado 
                                WHERE p.estatus IN ( 0, 1, 9 ) 
                                ORDER BY p.fecadd DESC");
    } 
    
    //LISTADO DE PROVEEDORES DE TIPO temporales
    function get_provs_temp(){ /** INICIO FECHA: 05-AGOSTO-2025 | AJUSTE CATALOGO PROVEEDORES | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
      ini_set('memory_limit', '-1');

      /** 
       * m.velazquez -> 77
       * yolzquez -> 257
      */
      $esUsuariosPermitidos = in_array($this->session->userdata('inicio_sesion')['id'], [77, 257])  || ( $this->session->userdata('inicio_sesion')['depto'] == 'ADMINISTRACION' && $this->session->userdata('inicio_sesion')['rol'] == 'CP');
      $fecha_bloqueo = "";
      $joinAuditoria = "";
      $condicionEstatus = "p.estatus = 2";
      if ($esUsuariosPermitidos) {
          $fecha_bloqueo = ", DATE_FORMAT(a.ultima_fecha, '%d/%m/%Y') AS fecha_bloqueo";
          $joinAuditoria = "LEFT JOIN (
              SELECT 
                  a.id_parametro, 
                  a.fecha_creacion AS ultima_fecha,
                  a.anterior,
                  a.nuevo
              FROM auditoria a
              WHERE a.tabla = 'proveedores'
              AND a.col_afect = 'estatus'
              AND ((a.anterior = 2 AND a.nuevo = 3))
              AND a.id_auditoria = (
                  SELECT MAX(a2.id_auditoria)
                  FROM auditoria a2
                  WHERE a2.id_parametro = a.id_parametro
                  AND a2.tabla = 'proveedores'
                  AND a2.col_afect = 'estatus'
              )
          ) a ON p.idproveedor = a.id_parametro";
          
          $condicionEstatus .= " OR (a.anterior = 2 AND a.nuevo = 3)";
      }

      return $this->db->query("SELECT
                                p.*,
                                IF(p.fecadd IS NOT NULL AND p.fecadd <> '0000-00-00 00:00:00', DATE_FORMAT(p.fecadd, '%d/%m/%Y'), 'SIN DEFINIR') AS fecha,
                                b.nombre AS nomba,
                                e.estado,
                                CASE
                                  WHEN p.excp = 0 THEN 'OBLIGATORIO CARGAR XML'
                                  WHEN p.excp = 1 THEN 'XML POSTERIOR AL PAGO'
                                  ELSE 'NUNCA SE RECIBIRA FACTURA'
                                END AS facturacion,
                                CASE
                                  WHEN $condicionEstatus THEN 'TEMPORAL'
                                  WHEN p.estatus = 3 THEN 'BLOQUEADO'
                                  ELSE '---'
                                END AS tipo_proveedor
                                $fecha_bloqueo
                              FROM proveedores p
                              LEFT JOIN bancos b ON p.idbanco = b.idbanco
                              LEFT JOIN estados e ON p.sucursal = e.id_estado
                              LEFT JOIN (
                                SELECT
                                  DISTINCT idProveedor
                                FROM solpagos
                                WHERE nomdepto IN (
                                  'FINIQUITO', 'NOMINAS', 'FINIQUITO POR PARCIALIDAD'
                                  , 'FINIQUITO POR RENUNCIA', 'PRESTAMO', 'PRESTAMO POR SUSTITUCION PATRONAL'
                                  , 'PRESTAMO POR ADEUDO', 'BONO'
                                )
                              ) sp ON sp.idProveedor = p.idproveedor
                              $joinAuditoria
                              WHERE ( $condicionEstatus )
                              AND p.nombre IS NOT NULL
                              AND sp.idproveedor IS NULL
                              ORDER BY p.fecadd DESC");
    } /** FIN FECHA: 05-AGOSTO-2025 | AJUSTE CATALOGO PROVEEDORES | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */

    //LISTADO DE PROVEEDORES DE TIPO colaboradores
    function get_provs_colaboradores(){ /** INICIO FECHA: 05-AGOSTO-2025 | AJUSTE CATALOGO PROVEEDORES | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
      /** 
       * m.velazquez -> 77
       * yolzquez -> 257
      */
      $esUsuariosPermitidos = in_array($this->session->userdata('inicio_sesion')['id'], [77, 257])  || ( $this->session->userdata('inicio_sesion')['depto'] == 'ADMINISTRACION' && $this->session->userdata('inicio_sesion')['rol'] == 'CP');
      $fecha_bloqueo = "";
      $joinAuditoria = "";
      $condicionEstatus = "p.estatus = 5";
      if ($esUsuariosPermitidos) {
          $fecha_bloqueo = ", DATE_FORMAT(a.ultima_fecha, '%d/%m/%Y') AS fecha_bloqueo";
          $joinAuditoria = "LEFT JOIN (
                              SELECT 
                              a.id_parametro, 
                              a.fecha_creacion AS ultima_fecha,
                                a.anterior,
                                a.nuevo
                              FROM auditoria a
                              WHERE a.tabla = 'proveedores'
                              AND a.col_afect = 'estatus'
                              AND ((a.anterior = 5 AND a.nuevo = 3))
                              AND a.id_auditoria = (
                                SELECT MAX(a2.id_auditoria)
                                FROM auditoria a2
                                WHERE a2.id_parametro = a.id_parametro
                                AND a2.tabla = 'proveedores'
                                AND a2.col_afect = 'estatus'
                              )
                            ) a ON p.idproveedor = a.id_parametro";
          
          $condicionEstatus .= " OR (a.anterior = 5 AND a.nuevo = 3)";
      }

      return $this->db->query("SELECT 
                                  p.*, 
                                  b.nombre AS nomba,
                                  IF(p.fecadd IS NOT NULL AND p.fecadd <> '0000-00-00 00:00:00', DATE_FORMAT(p.fecadd, '%d/%m/%Y'), 'SIN DEFINIR') AS fecha,
                                  CASE 
                                    WHEN p.excp = 0 THEN 'OBLIGATORIO CARGAR XML' 
                                    WHEN p.excp = 1 THEN 'XML POSTERIOR AL PAGO' 
                                    ELSE 'NUNCA SE RECIBIRA FACTURA' 
                                  END AS facturacion,
                                  CASE
                                    WHEN $condicionEstatus THEN 'COLABORADOR'
                                    WHEN p.estatus = 2 THEN 'TEMPORAL'
                                    WHEN p.estatus = 3 THEN 'BLOQUEADO'
                                    ELSE '---'	
                                  END AS tipo_proveedor
                                  $fecha_bloqueo
                                FROM proveedores p
                                LEFT JOIN bancos b ON p.idbanco = b.idbanco 
                                LEFT JOIN estados ON p.sucursal = estados.id_estado
                                $joinAuditoria
                                WHERE (
                                  ( $condicionEstatus )
                                  OR (
                                    p.idproveedor IN (
                                    SELECT
                                      idProveedor
                                    FROM solpagos
                                    WHERE nomdepto IN (
                                      'FINIQUITO', 'NOMINAS'
                                      , 'FINIQUITO POR PARCIALIDAD', 'FINIQUITO POR RENUNCIA'
                                      , 'PRESTAMO', 'PRESTAMO POR SUSTITUCION PATRONAL'
                                      , 'PRESTAMO POR ADEUDO', 'BONO'
                                    )
                                        GROUP BY idProveedor
                                    ) AND p.estatus = 2
                                  )
                                )
                                AND p.nombre IS NOT NULL
                                ORDER BY p.fecadd DESC"); 
    } /** FIN FECHA: 05-AGOSTO-2025 | AJUSTE CATALOGO PROVEEDORES | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
    /************************************************************************/
    function getprovscc(){
      return $this->db->query("SELECT * FROM proveedores WHERE proveedores.estatus IN ( 0, 1 ) ORDER BY proveedores.fecadd DESC"); 
    }

    function get_provs2($idproveedor){
    return  $this->db->query("SELECT proveedores.idproveedor,proveedores.nombre as nomp, IFNULL(rfc,'') AS rfc, IFNULL(contacto,'') AS contacto, IFNULL(email,'') AS email, tipocta, cuenta, sucursal, fecadd, IFNULL(bancos.nombre,'') as nomba,IFNULL(estados.estado,'') AS estado ,usuarios.nombres AS ProveedorI,tipo_prov,"
            . "bancos.idbanco,estados.id_estado"
            . " FROM proveedores LEFT JOIN bancos ON proveedores.idbanco=bancos.idbanco LEFT JOIN estados ON proveedores.sucursal = estados.id_estado LEFT JOIN usuarios ON proveedores.idusuario = usuarios.idusuario"
            . " WHERE proveedores.idproveedor='$idproveedor'");
    
    }

    function get_provs3($idproveedor){
      return  $this->db->query("SELECT idproveedor,nombre FROM proveedores WHERE proveedores.idproveedor=".$idproveedor);
    }
    
    function edita_proveedor($data,$id){
        // FECHA : 01-JULIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
       return $this->db->update("proveedores", $data, "idproveedor = '$id'");
    }

    function editcat_prov($data,$id){
      return $this->db->update("cat_proveedor", $data, "rfc_proveedor = '$id'");
   }
  
    function getProveedor( $idprovedor ){
      return $this->db->query("SELECT * 
                               FROM proveedores 
                               LEFT JOIN ( SELECT bancos.idbanco, bancos.nombre AS nomba FROM bancos ) AS bancos
                                  ON proveedores.idbanco=bancos.idbanco
                               LEFT JOIN estados 
                                  ON proveedores.sucursal = estados.id_estado 
                               WHERE proveedores.idproveedor = '$idprovedor' AND proveedores.estatus != 0"); 
    }

    function getProvBanco($rfc){
      return $this->db->query("SELECT proveedores.idproveedor, proveedores.nombre as nomp, IFNULL(rfc,'') AS rfc,
                                      tipocta, IFNULL(cuenta,'SIN DEFINIR') as cuenta, sucursal, IFNULL(nomba,'SIN DEFINIR') as nomba, bancos.idbanco 
                              FROM proveedores 
                              LEFT JOIN ( SELECT bancos.idbanco, bancos.nombre AS nomba FROM bancos ) AS bancos ON proveedores.idbanco=bancos.idbanco 
                              LEFT JOIN estados ON proveedores.sucursal = estados.id_estado 
                              WHERE proveedores.rfc = '$rfc' AND proveedores.estatus = 1 ORDER by proveedores.tipo_prov ASC;");
    }
    function getProveedorbyrfc( $rfc ){
      return $this->db->query("SELECT *, IFNULL( nomba, 'SIN DEFINIR' ) AS nomba FROM proveedores LEFT JOIN ( SELECT bancos.idbanco, bancos.nombre AS nomba FROM bancos ) AS bancos ON proveedores.idbanco=bancos.idbanco LEFT JOIN estados ON proveedores.sucursal = estados.id_estado WHERE proveedores.rfc = '$rfc' AND proveedores.estatus NOT IN ( 0, 3) ORDER by proveedores.tipo_prov ASC"); 
    }
    
    //PROVEEDORES POR AUTORIZAR PARA CATALOGO
    function Proveedores_Autorizar(){
      return $this->db->query("SELECT *, DATE_FORMAT(fecadd,'%d/%m/%Y') AS fecha FROM proveedores LEFT JOIN ( SELECT bancos.idbanco, bancos.nombre AS nomba FROM bancos ) AS bancos ON proveedores.idbanco=bancos.idbanco LEFT JOIN estados ON proveedores.sucursal = estados.id_estado WHERE proveedores.estatus IN ( 9 ) ORDER BY proveedores.fecadd DESC"); 
    }

    //ACTUALIZAR ESTATUS DE PROVEEDOR
    function actualizar_estatus( $idproveedor, $estatus ){
      // FECHA : 01-JULIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
      $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
      /**
       * FECHA : 01-JULIO-2025 | @author Efrain Martinez progamador.analista38@ciudadmaderas.com | SE AGREGA LA FECHA EN LA QUE SE REALIZO LA ACTUALIZACION
       */
      return $this->db->update( "proveedores", array(
        "idupdate" => $this->session->userdata('inicio_sesion')['id'],
        "fecha_update" => date("Y-m-d H:i:s"),
        "estatus" => $estatus ), " idproveedor = '$idproveedor'" );
    }

    //COLOCA AL PROVEEDOR EN ESTATUS 3 (ELIMINADO)
    function eliminar_proveedor( $idproveedor ){
      /** FECHA: 28-MAYO-2025 | ELIMINAR PROVEEDOR RECHAZADO | @author Angel Victoriano <programador.analista30@ciudadmaderas.com>
       * Contexto: Se requiere que, al rechazar un proveedor por datos erróneos, este sea eliminado de forma definitiva del sistema.
       * Cambios realizados:
       *    Se reemplazó la lógica existente por una sentencia DELETE para eliminar al proveedor.
       *    Se agregó un trigger AFTER DELETE en la tabla proveedores para registrar la eliminación en la tabla auditoria.
      */
      
      // Setear la variable global que el trigger usará
      $this->db->query("SET @proveedores_creado_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
      
      // Ejecutar el delete
      return $this->db->delete('proveedores', ['idproveedor' => $idproveedor]);

    }

    function getProductosXprovM(){
      $sql="SELECT pv.*,pr.nombre,pr.alias FROM productosxprov pv JOIN proveedores pr ON pr.idproveedor=pv.idproveedor;";
      return $this->db->query($sql);
  }

  function inserta_prodXprov($post){
    return $this->db->query("insert into productosxprov(id,producto,idproveedor,idcrea,fechacrea) values(0,'$post[producto]','$post[proveedor]',".$this->session->userdata('inicio_sesion')['id'].",now());");
  }

  function edita_prodXprov($post){
      return $this->db->query("update productosxprov set producto='$post[producto]',idproveedor='$post[proveedor]',idmodifica=".$this->session->userdata('inicio_sesion')['id'].",fechamodifica=now() where id='$post[idregistro]';");
  }

  function des_activa_prodXprov($post){
    return $this->db->query("update productosxprov set activo = case when activo=1 then 0 else 1 end where id='$post[idregistro]';");
  }

  function update_porcent($prov){
    return $this->db->query("UPDATE cat_proveedor SET porcentaje = 1 WHERE rfc_proveedor='$prov' ");
  }

  function totalPagadoProvDepto( $departamento ){
    return $this->db->query("SELECT
      idproveedor,
      UPPER(TRIM(prov.nombre)) pnombre,
      prov.rfcs,
      prov.rfc,
      prov.totalPagado,
      null deuda
        FROM (
          SELECT 
      GROUP_CONCAT( proveedores.idproveedor ) idproveedor,
            nombre, 
            COUNT(proveedores.rfc) AS rfcs, 
            proveedores.rfc, 
            SUM(totalpagadoprov.totalPagado) AS totalPagado
          FROM ( SELECT idproveedor, nombre, rfc FROM proveedores WHERE CHAR_LENGTH(proveedores.nombre) > 1 AND CHAR_LENGTH(proveedores.rfc) > 0 AND rfc NOT IN ( 'XAXX010101000','CLIENTE') AND estatus NOT IN ( 2, 4 ) AND nombre IS NOT NULL ) proveedores
          LEFT JOIN (
            SELECT 
              solp.idproveedor AS idProveedor,
              SUM(autpagos.pagado) AS totalPagado,
              GROUP_CONCAT(DISTINCT autpagos.year_registro
                SEPARATOR ',') AS year_registro
            FROM
              (((SELECT 
                solpagos.idsolicitud AS idsolicitud,
                  solpagos.idProveedor AS idproveedor
              FROM
                solpagos
              WHERE
                ((solpagos.idetapa <> 0)
                  AND (ISNULL(solpagos.caja_chica)
                  OR (solpagos.caja_chica = 0))
                  AND (solpagos.nomdepto IN ( ? ))
                  AND (solpagos.metoPago NOT IN ('FACT BAJIO' , 'FACT BANREGIO'))))) solp
              JOIN (SELECT 
                autpagos.idsolicitud AS idsolicitud,
                  SUM(IF((autpagos.tipoCambio > 1), (autpagos.tipoCambio * autpagos.cantidad), autpagos.cantidad)) AS pagado,
                  GROUP_CONCAT(DISTINCT autpagos.year_registro
                    SEPARATOR ',') AS year_registro
              FROM
                (SELECT 
                autpagos.idsolicitud AS idsolicitud,
                  autpagos.cantidad AS cantidad,
                  autpagos.tipoCambio AS tipoCambio,
                  YEAR(autpagos.fecreg) AS year_registro
              FROM
                autpagos
              WHERE
                (autpagos.referencia <> 'ABONO') UNION SELECT 
                autpagos.idsolicitud AS idsolicitud,
                  autpagos.cantidad AS cantidad,
                  autpagos.tipoCambio AS tipoCambio,
                  YEAR(autpagos.fecreg) AS year_registro
              FROM
                autpagos
              WHERE
                ISNULL(autpagos.referencia)) autpagos
              GROUP BY autpagos.idsolicitud) autpagos ON ((autpagos.idsolicitud = solp.idsolicitud)))
            GROUP BY solp.idproveedor
          ) totalpagadoprov ON totalpagadoprov.idProveedor = proveedores.idproveedor
          GROUP BY proveedores.rfc   
          UNION
          SELECT 
      proveedores.idproveedor,
            proveedores.nombre, 
            COUNT(proveedores.rfc) AS rfcs, 
            proveedores.rfc, 
            SUM(totalpagadoprov.totalPagado) AS totalPagado
          FROM(
              SELECT idproveedor, nombre, rfc FROM proveedores WHERE CHAR_LENGTH(proveedores.nombre) > 1 AND rfc IN ('' , 'XAXX010101000') AND estatus NOT IN ( 2, 4 ) AND nombre IS NOT NULL
              UNION
              SELECT idproveedor, nombre, rfc FROM proveedores WHERE CHAR_LENGTH(proveedores.nombre) > 1 AND rfc IS NULL AND estatus NOT IN ( 2, 4 ) AND nombre IS NOT NULL
          ) proveedores
          LEFT JOIN (
            SELECT 
              solp.idproveedor AS idProveedor,
              SUM(autpagos.pagado) AS totalPagado,
              GROUP_CONCAT(DISTINCT autpagos.year_registro
                SEPARATOR ',') AS year_registro
            FROM
              (((SELECT 
                solpagos.idsolicitud AS idsolicitud,
                  solpagos.idProveedor AS idproveedor
              FROM
                solpagos
              WHERE
                ((solpagos.idetapa <> 0)
                  AND (ISNULL(solpagos.caja_chica)
                  OR (solpagos.caja_chica = 0))
                  AND (solpagos.nomdepto IN ( ? ))
                  AND (solpagos.metoPago NOT IN ('FACT BAJIO' , 'FACT BANREGIO'))))) solp
              JOIN (SELECT 
                autpagos.idsolicitud AS idsolicitud,
                  SUM(IF((autpagos.tipoCambio > 1), (autpagos.tipoCambio * autpagos.cantidad), autpagos.cantidad)) AS pagado,
                  GROUP_CONCAT(DISTINCT autpagos.year_registro
                    SEPARATOR ',') AS year_registro
              FROM
                (SELECT 
                autpagos.idsolicitud AS idsolicitud,
                  autpagos.cantidad AS cantidad,
                  autpagos.tipoCambio AS tipoCambio,
                  YEAR(autpagos.fecreg) AS year_registro
              FROM
                autpagos
              WHERE
                (autpagos.referencia <> 'ABONO') UNION SELECT 
                autpagos.idsolicitud AS idsolicitud,
                  autpagos.cantidad AS cantidad,
                  autpagos.tipoCambio AS tipoCambio,
                  YEAR(autpagos.fecreg) AS year_registro
              FROM
                autpagos
              WHERE
                ISNULL(autpagos.referencia)) autpagos
              GROUP BY autpagos.idsolicitud) autpagos ON ((autpagos.idsolicitud = solp.idsolicitud)))
            GROUP BY solp.idproveedor
          ) totalpagadoprov ON totalpagadoprov.idProveedor = proveedores.idproveedor
          GROUP BY proveedores.idproveedor
      ) prov ORDER BY prov.nombre", [ $departamento, $departamento ]);
  }

  function totalPagadoProv(){
    return $this->db->query("SELECT
      idproveedor,
      UPPER(TRIM(prov.nombre)) pnombre,
      prov.rfcs,
      prov.rfc,
      prov.totalPagado,
      null deuda
        FROM (
          SELECT 
            GROUP_CONCAT( proveedores.idproveedor ) idproveedor,
            nombre, 
            COUNT(proveedores.rfc) AS rfcs, 
            proveedores.rfc, 
            SUM(totalpagadoprov.totalPagado) AS totalPagado
          FROM ( SELECT idproveedor, nombre, rfc FROM proveedores WHERE CHAR_LENGTH(proveedores.nombre) > 1 AND CHAR_LENGTH(proveedores.rfc) > 0 AND rfc NOT IN ( 'XAXX010101000','CLIENTE') AND estatus NOT IN ( 2, 4 ) AND nombre IS NOT NULL ) proveedores
          LEFT JOIN totalpagadoprov ON totalpagadoprov.idProveedor = proveedores.idproveedor
          GROUP BY proveedores.rfc   
          UNION
          SELECT 
            proveedores.idproveedor,
            proveedores.nombre, 
            COUNT(proveedores.rfc) AS rfcs, 
            proveedores.rfc, 
            SUM(totalpagadoprov.totalPagado) AS totalPagado
          FROM(
              SELECT idproveedor, nombre, rfc FROM proveedores WHERE CHAR_LENGTH(proveedores.nombre) > 1 AND rfc IN ('' , 'XAXX010101000') AND estatus NOT IN ( 2, 4 ) AND nombre IS NOT NULL
              UNION
              SELECT idproveedor, nombre, rfc FROM proveedores WHERE CHAR_LENGTH(proveedores.nombre) > 1 AND rfc IS NULL AND estatus NOT IN ( 2, 4 ) AND nombre IS NOT NULL
          ) proveedores
          LEFT JOIN totalpagadoprov ON totalpagadoprov.idProveedor = proveedores.idproveedor
          GROUP BY proveedores.idproveedor
      ) prov ORDER BY prov.nombre");
  }

  function totalDeudaProveedor( $departamento = FALSE ){

    if( $departamento !== FALSE ){
      $filtro = "AND solpagos.nomdepto = '$departamento' ";
    }else{
      $filtro = "AND solpagos.nomdepto NOT IN ( 
        'PRESTAMO', 
        'FINIQUITO', 
        'FINIQUITO POR RENUNCIA', 
        'FINIQUITO ASIMILADO', 
        'FINIQUITO POR PARCIALIDAD', 
        'BONO',
        'INFORMATIVA','INFORMATIVA CERO',
        'IMPUESTOS', 
        'NOMINA DESTAJO' ) 
      AND solpagos.nomdepto NOT LIKE 'DEVOLUCION%' AND solpagos.nomdepto NOT LIKE 'TRASPASO%' AND solpagos.nomdepto NOT LIKE 'CESION%' ";
    }

    return $this->db->query("SELECT
          solpagos.idProveedor,
          (SUM(solpagos.deuda) - SUM(abono.abonado)) deuda,
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
              solpagos.cantidad deuda,
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
              WHERE solpagos.idetapa IN ( 7, 9 ) AND (solpagos.caja_chica IS NULL OR solpagos.caja_chica = 0) 
              AND solpagos.metoPago NOT IN ( 'FACT BAJIO', 'FACT BANREGIO' )
              $filtro
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
      GROUP BY solpagos.idProveedor");
  }

  public function year_operaciones(){
    return $this->db->query("SELECT 
        MIN( foperacion ) minoperacion, 
          MAX( foperacion ) maxoperacion
      FROM ( SELECT DISTINCT DATE_FORMAT( fecreg, '%Y-%m-%d' ) foperacion FROM autpagos WHERE YEAR( fecreg ) != 0 ) pagos");
  }

  public function totalDeudaProveedor_year( $minoperacion, $maxoperacion ){

    $pagos = "SELECT solp.idproveedor AS idProveedor, SUM(autpagos.pagado) AS totalPagado
      FROM ((
        SELECT solpagos.idsolicitud AS idsolicitud, solpagos.idProveedor AS idproveedor
        FROM solpagos
        WHERE ( (solpagos.idetapa <> 0) AND (ISNULL(solpagos.caja_chica) OR (solpagos.caja_chica = 0) ) AND 
            (solpagos.nomdepto NOT IN( 'PRESTAMO', 'FINIQUITO', 'FINIQUITO POR RENUNCIA', 'FINIQUITO ASIMILADO', 'FINIQUITO POR PARCIALIDAD', 'BONO', 'INFORMATIVAS', 'IMPUESTOS', 'NOMINA DESTAJO')) AND 
            (NOT(( solpagos.nomdepto LIKE 'DEVOLUCION%'))) AND 
            (NOT((solpagos.nomdepto LIKE 'TRASPASO%'))) AND
            (NOT((solpagos.nomdepto LIKE 'CESION%'))) AND 
            (NOT((solpagos.nomdepto LIKE 'RESCISION%'))) AND 
            (solpagos.metoPago NOT IN('FACT BAJIO', 'FACT BANREGIO'))
          )
                  ) solp
                  JOIN (SELECT autpagos.idsolicitud AS idsolicitud,
            SUM( IF((autpagos.tipoCambio > 1), (autpagos.tipoCambio * autpagos.cantidad), autpagos.cantidad)) AS pagado, 
            GROUP_CONCAT(DISTINCT YEAR(autpagos.fecreg) SEPARATOR ',') AS year_registro
          FROM autpagos
          WHERE (autpagos.referencia <> 'ABONO') AND
            DATE_FORMAT(autpagos.fecreg, '%Y-%m-%d') BETWEEN '$minoperacion' AND '$maxoperacion'
          GROUP BY autpagos.idsolicitud
          UNION
          SELECT autpagos.idsolicitud AS idsolicitud,
            SUM( IF((autpagos.tipoCambio > 1), (autpagos.tipoCambio * autpagos.cantidad), autpagos.cantidad)) AS pagado, 
            GROUP_CONCAT(DISTINCT YEAR(autpagos.fecreg) SEPARATOR ',') AS year_registro
          FROM autpagos
          WHERE ISNULL(autpagos.referencia) AND 
              DATE_FORMAT(autpagos.fecreg, '%Y-%m-%d') BETWEEN '$minoperacion' AND '$maxoperacion'
          GROUP BY autpagos.idsolicitud) AS autpagos ON autpagos.idsolicitud = solp.idsolicitud
              ) GROUP BY solp.idproveedor";

    return $this->db->query("SELECT
      idproveedor,
      UPPER(TRIM(prov.nombre)) pnombre,
      prov.rfcs,
      prov.rfc,
      prov.totalPagado,
      null deuda
        FROM (
          SELECT 
      GROUP_CONCAT( proveedores.idproveedor ) idproveedor,
            nombre, 
            COUNT(proveedores.rfc) AS rfcs, 
            proveedores.rfc, 
            SUM(totalpagadoprov.totalPagado) AS totalPagado
          FROM ( SELECT idproveedor, nombre, rfc FROM proveedores WHERE CHAR_LENGTH(proveedores.nombre) > 1 AND CHAR_LENGTH(proveedores.rfc) > 0 AND rfc NOT IN ( 'XAXX010101000','CLIENTE') AND estatus NOT IN ( 2, 4 ) AND nombre IS NOT NULL ) proveedores
          LEFT JOIN ( $pagos ) totalpagadoprov ON totalpagadoprov.idProveedor = proveedores.idproveedor
          GROUP BY proveedores.rfc   
          UNION
          SELECT 
      proveedores.idproveedor,
            proveedores.nombre, 
            COUNT(proveedores.rfc) AS rfcs, 
            proveedores.rfc, 
            SUM(totalpagadoprov.totalPagado) AS totalPagado
          FROM(
              SELECT idproveedor, nombre, rfc FROM proveedores WHERE CHAR_LENGTH(proveedores.nombre) > 1 AND rfc IN ('' , 'XAXX010101000') AND estatus NOT IN ( 2, 4 ) AND nombre IS NOT NULL
              UNION
              SELECT idproveedor, nombre, rfc FROM proveedores WHERE CHAR_LENGTH(proveedores.nombre) > 1 AND rfc IS NULL AND estatus NOT IN ( 2, 4 ) AND nombre IS NOT NULL
          ) proveedores
          LEFT JOIN ( $pagos ) totalpagadoprov ON totalpagadoprov.idProveedor = proveedores.idproveedor
          GROUP BY proveedores.idproveedor
      ) prov ORDER BY prov.nombre");
  }

  function actualizarProveedoresExcel($tabla, $datos){
    $this->db->trans_start(); // Iniciar transacción

    foreach (array_chunk($datos, 5000) as $batch) { // Procesar en lotes
        $this->db->update_batch($tabla, $batch, 'idproveedor'); // update_batch permite actualizar múltiples registros
    }

    $this->db->trans_complete(); // Finalizar transacción
    return $this->db->trans_status();
  }
}