<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Opciones extends CI_Controller {

  function __construct(){
    parent::__construct();
    
    if( !$this->session->userdata("inicio_sesion") && !in_array( $this->session->userdata("inicio_sesion")['rol'], array( 'CP', 'SU' ) ) )
      echo json_encode( [ "resultado"  => FALSE, "mensaje" => "¡Ups! Ha cerrado la sesión, abra otra pestaña he ingrese nuevamente al sistema." ] );//redirect("Login", "refresh");
  }

  function poner_urgente( $idsolicitud ){
    // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
    $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
    $this->db->query("UPDATE solpagos SET prioridad = '1'  WHERE idsolicitud = '$idsolicitud'");
    log_sistema( $this->session->userdata('inicio_sesion')['id'], $idsolicitud, "CXP MARCA COMO URGENTE" );
  }

  function poner_bajio( $idsolicitud ){
    // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
    $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
    return  $this->db->query("UPDATE solpagos SET metoPago = 'FACT BAJIO' WHERE idsolicitud = '$idsolicitud'");
    log_sistema( $this->session->userdata('inicio_sesion')['id'], $idsolicitud, "CXP ENVIA A FACTORAJE BAJIO" );  
  }

  function poner_banregio( $idsolicitud ){
    // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
    $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
    $this->db->query("UPDATE solpagos SET  metoPago = 'FACT BANREGIO' WHERE idsolicitud = '$idsolicitud'");

    if( $this->db->affected_rows() > 0 )
      log_sistema( $this->session->userdata('inicio_sesion')['id'], $idsolicitud, "CXP ENVIA A FACTORAJE BANREGIO" );

    echo json_encode( array( "resultado" => $this->db->affected_rows() > 0 ) );
  }

  function tendra_factura( $idsolicitud ){
    // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
    $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
    $this->db->query("UPDATE solpagos SET  tendrafac = '1', idetapa = CASE WHEN idetapa = 11 THEN 10 ELSE idetapa END WHERE idsolicitud = '$idsolicitud'");
    
    if( $this->db->affected_rows() > 0 )
      log_sistema( $this->session->userdata('inicio_sesion')['id'], $idsolicitud, "CXP DEFINE TENDRA FACTURA" );
    
      echo json_encode( array( "resultado" => $this->db->affected_rows() > 0 ) );
  }

  function no_tendra( $idsolicitud ){
    // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
    $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
    $this->db->query("UPDATE solpagos SET  tendrafac = null, idetapa = CASE WHEN idetapa = 10 THEN 11 ELSE idetapa END WHERE idsolicitud = '$idsolicitud'");
    
    if( $this->db->affected_rows() > 0 )
      log_sistema( $this->session->userdata('inicio_sesion')['id'], $idsolicitud, "CXP DEFINE SIN FACTURA" );

    echo json_encode( array( "resultado" => $this->db->affected_rows() > 0 ) );
  }

  public function pausar_historial(){
    // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
    $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
    $data =  json_decode(base64_decode(file_get_contents('php://input')));
    $resultado = array( "resultado" => FALSE, "mensaje" => "¡Algo a ocurrido! Intente mas tarde." );

    if( $data !== FALSE & isset($data->idsolicitud) && isset($data->justificacion) ){

      $this->db->trans_begin();
      $update_resultado = $this->db->query("UPDATE solpagos SET idetapa = 
        CASE 
          WHEN idetapa = 2 THEN 42 
          WHEN idetapa = 5 THEN 45 
          WHEN idetapa = 7 THEN 47 
          WHEN idetapa = 9 THEN 49 
          ELSE idetapa 
        END 
      WHERE idsolicitud = ? AND idetapa IN ( 2, 5, 7, 9 )", array( $data->idsolicitud ) );

      if( $update_resultado ){
        chat( $data->idsolicitud, $data->justificacion, $this->session->userdata("inicio_sesion")['id']);
        log_sistema($this->session->userdata("inicio_sesion")['id'], $data->idsolicitud, "SE HA PAUSADO EL REGISTRO.");
      }else{
        $resultado["mensaje"] = "No se afecto a ningun registro.";
      }


      if ($this->db->trans_status() === FALSE){
        $this->db->trans_rollback();
        $resultado = array( "resultado" => FALSE, "mensaje" => "No se pudo finalizar la transacción." );
      }else{
        $this->db->trans_commit();
        $resultado = array( "resultado" => TRUE, "mensaje" => "Transacción realizada con exíto." );
      }
      
    }

    echo json_encode( $resultado );
  }

  public function quitar_pausa_historial( $index_c ){
    // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
    $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
    $this->db->query("UPDATE solpagos SET idetapa = CASE WHEN idetapa = 42 THEN 2 WHEN idetapa = 45 THEN 5 WHEN idetapa = 47 THEN 7 WHEN idetapa = 49 THEN 9 ELSE idetapa END WHERE idsolicitud = $index_c");
    if( $this->db->affected_rows() > 0 )
      log_sistema($this->session->userdata("inicio_sesion")['id'], $index_c, "SE REGRESÓ LA SOLICITUD A SU CURSO");
      
    echo json_encode( array( "resultado" => $this->db->affected_rows() > 0 ) );
  }
  /**
   * Inicio
   * Fecha : 12-Agosto-2025 @author Efrain Martinez programador.analista38@ciudadmaderas.com
   * Se actualiza la fucion que cancela las solicitudes desde el historial para las solicitudes de proveedores.
   */
  public function cancelar_historial(){
    // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
    $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
    $data =  base64_decode(file_get_contents('php://input'));
    
    $json_string_utf8 = mb_convert_encoding($data, 'UTF-8', 'ISO-8859-1');
    
    // $data = json_decode($json_string_utf8, true);
    
    $resultado = array( "resultado" => FALSE );
    //Se crea otro nodo del arreglo el cual indica si la solicitud fue cancelada correctamente y se inicializa como false.
    $resultado["eliminada"] = FALSE;
    if( $data && $data !== FALSE ){
      $this->load->model("Complemento_cxpModel");
      $data = json_decode($json_string_utf8, true);

      if( $data ){
        $idsolicitud = $data['idsolicitud'];
        $observaciones = $data['justificacion'];
        $idusuario = $this->session->userdata("inicio_sesion")['id'];
        // como $data->sols_elim solo tiene un registro, lo forzamos a string
        $sols_elim = is_array($idsolicitud) ? $idsolicitud[0] : $idsolicitud;

        $this->db->trans_begin();
        // Consulta que permite saber si la solicitud ya tiene un registro en autpagos pero solo se obtienen los datos del primer registro que se registro son esta solicitud
        // para veificar si ya se pago un parcialidad de la solicitud. 
        $idPago = $this->db->query("SELECT * FROM autpagos WHERE idsolicitud = $idsolicitud ORDER BY idpago ASC LIMIT 1")->row();
        if($idPago){
          // En caso de que si se tengan registros de la solicitud en autpagos se valida que la columna fechaDis sea null o que el idusuario sea igual a 257.
          if(is_null($idPago->fechaDis) || $idusuario == 257){
            // Se eliminan todos los registros de autpagos donde el idsolicitud sea igual a la solcitud que se esta cancelando.
            $this->db->query("DELETE FROM autpagos WHERE idsolicitud = $idsolicitud");
            //Busca si la solicitud que se va a cancelar esta relacionada con otra factura
            $solr = $this->db->query("SELECT idfactura, idsolicitudr FROM facturas WHERE idsolicitudr LIKE '%$idsolicitud%' AND idsolicitud <> $idsolicitud");
            // Si esta relacionada con otra factura la elimina de la columna idsolicitudr y se elimina el registro de la tabla relacionar sol_factura.
            if($solr->num_rows()>0){
              $facturaRelcacionadaSol = $solr->result_array();
              for($i=0; $i < count($facturaRelcacionadaSol); $i++){
                $solicitudr = explode(",", $facturaRelcacionadaSol[$i]['idsolicitudr']);
                $idFacturaR = $facturaRelcacionadaSol[$i]['idfactura'];
                // quitamos el valor único de la lista original
                $sol_elim = array_diff($solicitudr, [$sols_elim]);
                $sol_a_actualizar = implode(",", $sol_elim);
          
                $this->db->query("UPDATE facturas SET idsolicitudr = '$sol_a_actualizar' WHERE idfactura = $idFacturaR");
                $this->db->query("DELETE FROM sol_factura WHERE idsolicitud = $idsolicitud AND idfactura=$idFacturaR");
              }
            }
            // Busca si la solicitud que se va a eliminar ya cuenta con con solicitudes cargadas.
            $facturas = $this->db->query("SELECT idfactura FROM facturas WHERE idsolicitud = ?", array($idsolicitud));
            if($facturas->num_rows()>0){
              $resultadoF = $facturas->result_array();

              // Si la solicitud ya tiene facturas cargadas se verifica en cada una si tienen otras solicitudes relacionadas, de ser asi las solicitudes relacionadas
              // se regresan a la etapa 10 si ya estaban en la etapa 11 y se realiza un registro a logs de cada una de las solicitudes relacionadas con la factura de la solicitud
              // que se va a cancelar.
              for($i=0; $i < count($resultadoF); $i++){

                $solicitiudesRelacionadas = $this->db->query("SELECT idsolicitudr, uuid, idsolicitud FROM facturas WHERE idfactura = ? AND idsolicitudr IS NOT NULL AND idsolicitudr != ''", $resultadoF[$i]['idfactura'])->row();
                
                if($solicitiudesRelacionadas){
                  $this->db->query("UPDATE solpagos SET idetapa = 10 WHERE idsolicitud IN ($solicitiudesRelacionadas->idsolicitudr) AND idetapa = 11");
                  $this->Complemento_cxpModel->multiLogs( "SE ELIMINÓ LA RELACIÓN QUE LA FACTURA CON FOLIO FISCAL: ".$solicitiudesRelacionadas->uuid." TENÍA CON LA (S) SOLICITUD (ES): ".$solicitiudesRelacionadas->idsolicitudr, $solicitiudesRelacionadas->idsolicitud);
                  $sols_elimR = is_array($solicitiudesRelacionadas->idsolicitud) ? $solicitiudesRelacionadas->idsolicitud[0] : $solicitiudesRelacionadas->idsolicitud;
                  $solicitudR = explode(",", $solicitiudesRelacionadas->idsolicitudr);
                  $sol_elimR = array_diff($solicitudR, [$sols_elimR]);
                  $sol_a_actualizarR = implode(",", $sol_elimR);
                  if($sol_a_actualizarR){
                    $this->Complemento_cxpModel->multiLogs( "SE ELIMINÓ LA SOLICITUD DE LA FACTURA CON FOLIO FISCAL: ".$solicitiudesRelacionadas->uuid, $sol_a_actualizarR);
                  }
                }
                
                //Se cancela cada una de las facturas que estan realcionadas con la factura que se va a eliminar y se elimina la relación de la tabla sol_factura
                $this->db->query("UPDATE facturas SET tipo_factura = 0, uuid = SUBSTRING( uuid, 1, CHAR_LENGTH(uuid) - 1) WHERE idfactura = ?",$resultadoF[$i]['idfactura']); 
                $this->db->query("DELETE FROM sol_factura WHERE idfactura = ?", $resultadoF[$i]['idfactura']);

              }
            }
            // Se actualiza la etapa de la solicitud a 30 y se realiza un registro en logs de este movimiento y se guardaq la observacion del porque se cancelo la solicitud.
        $this->db->query("UPDATE solpagos SET idetapa = 30 WHERE idsolicitud = ?", array( $idsolicitud ) );

        chat( $idsolicitud, $observaciones, $this->session->userdata("inicio_sesion")['id']);
            log_sistema($this->session->userdata("inicio_sesion")['id'], $idsolicitud, "SE CANCELÓ DESDE EL HISTORIAL ¬¬");
            // Se reescribe el nodo del arreglo [eliminada] y se le asigna true que indica que la solicitud se cancelo correctamente.
            $resultado["eliminada"] = TRUE;
          }
        } else{
          //En caso de que no exista un registro de la solicitud en autpagos se realiza solo la parte en la que no se involucran las tablas de autpagos y sol_factura
          //Busca si la solicitud que se va a cancelar esta relacionada con otra factura
          $solr = $this->db->query("SELECT idfactura, idsolicitudr FROM facturas WHERE idsolicitudr LIKE '%$idsolicitud%' AND idsolicitud <> $idsolicitud");
          // Si esta relacionada con otra factura la elimina de la columna idsolicitudr y se elimina el registro de la tabla relacionar sol_factura.
          if($solr->num_rows()>0){
            $facturaRelcacionadaSol = $solr->result_array();
            for($i=0; $i < count($facturaRelcacionadaSol); $i++){
              $solicitudr = explode(",", $facturaRelcacionadaSol[$i]['idsolicitudr']);
              $idFacturaR = $facturaRelcacionadaSol[$i]['idfactura'];
              // quitamos el valor único de la lista original
              $sol_elim = array_diff($solicitudr, [$sols_elim]);
              $sol_a_actualizar = implode(",", $sol_elim);
        
              $this->db->query("UPDATE facturas SET idsolicitudr = '$sol_a_actualizar' WHERE idfactura = $idFacturaR");
              $this->db->query("DELETE FROM sol_factura WHERE idsolicitud = $idsolicitud AND idfactura=$idFacturaR");
            }
          }
          // Busca si la solicitud que se va a eliminar ya cuenta con con solicitudes cargadas.
          $facturas = $this->db->query("SELECT idfactura FROM facturas WHERE idsolicitud = ?", array($idsolicitud));
          if($facturas->num_rows()>0){
            $resultadoF = $facturas->result_array();
            // Si la solicitud ya tiene facturas cargadas se verifica en cada una si tienen otras solicitudes relacionadas, de ser asi las solicitudes relacionadas
            // se regresan a la etapa 10 si ya estaban en la etapa 11 y se realiza un registro a logs de cada una de las solicitudes relacionadas con la factura de la solicitud
            // que se va a cancelar.
      
            for($i=0; $i < count($resultadoF); $i++){

              $solicitiudesRelacionadas = $this->db->query("SELECT idsolicitudr, uuid, idsolicitud FROM facturas WHERE idfactura = ? AND idsolicitudr IS NOT NULL AND idsolicitudr != ''", $resultadoF[$i]['idfactura'])->row();
              
              if($solicitiudesRelacionadas){
                $this->db->query("UPDATE solpagos SET idetapa = 10 WHERE idsolicitud IN ($solicitiudesRelacionadas->idsolicitudr) AND idetapa = 11");
                $this->Complemento_cxpModel->multiLogs( "SE ELIMINÓ LA RELACIÓN QUE LA FACTURA CON FOLIO FISCAL: ".$solicitiudesRelacionadas->uuid." TENÍA CON LA (S) SOLICITUD (ES): ".$solicitiudesRelacionadas->idsolicitudr, $solicitiudesRelacionadas->idsolicitud);
                $sols_elimR = is_array($solicitiudesRelacionadas->idsolicitud) ? $solicitiudesRelacionadas->idsolicitud[0] : $solicitiudesRelacionadas->idsolicitud;
                $solicitudR = explode(",", $solicitiudesRelacionadas->idsolicitudr);
                $sol_elimR = array_diff($solicitudR, [$sols_elimR]);
                $sol_a_actualizarR = implode(",", $sol_elimR);
                if($sol_a_actualizarR){
                  $this->Complemento_cxpModel->multiLogs( "SE ELIMINÓ LA SOLICITUD DE LA FACTURA CON FOLIO FISCAL: ".$solicitiudesRelacionadas->uuid, $sol_a_actualizarR);
                }
              }
              
              //Se cancela cada una de las facturas que estan realcionadas con la factura que se va a eliminar y se elimina la relación de la tabla sol_factura.
              $this->db->query("UPDATE facturas SET tipo_factura = 0, uuid = SUBSTRING( uuid, 1, CHAR_LENGTH(uuid) - 1) WHERE idfactura = ?",$resultadoF[$i]['idfactura']); 
              $this->db->query("DELETE FROM sol_factura WHERE idfactura = ?", $resultadoF[$i]['idfactura']);

            }
          }
          // Se actualiza la etapa de la solicitud a 30 y se realiza un registro en logs de este movimiento y se guarda la observacion del porque se cancelo la solicitud.
          $this->db->query("UPDATE solpagos SET idetapa = 30 WHERE idsolicitud = ?", array( $idsolicitud ));

          chat( $idsolicitud, $observaciones, $this->session->userdata("inicio_sesion")['id']);
          log_sistema($this->session->userdata("inicio_sesion")['id'], $idsolicitud, "SE CANCELÓ DESDE EL HISTORIAL ¬¬");
          // Se reescribe el nodo del arreglo [eliminada] y se le asigna true que indica que la solicitud se cancelo correctamente.
          $resultado["eliminada"] = TRUE;
        }

        if ($this->db->trans_status() === FALSE){
          $this->db->trans_rollback();
          $resultado["resultado"] = FALSE;
        }else{
          $this->db->trans_commit();
          $resultado["resultado"] = TRUE;
        }
      }
    }

    echo json_encode( $resultado );
  }
  /**
   * Fin 
   * Fecha : 12-Agosto-2025 @author Efrain Martinez programador.analista38@ciudadmaderas.com
   */

  function agregar_referencia($idsolicitud, $ref){  
    // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
    $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
    $this->db->query("UPDATE solpagos SET  ref_bancaria = '$ref' WHERE idsolicitud = '$idsolicitud'");
    log_sistema( $this->session->userdata('inicio_sesion')['id'], $idsolicitud, "SE AGREGA REFERENCIA BANCARIA" );
  }
  
  function cambiar_empresa(){
    // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
    $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
    $resultado = array( "resultado" => FALSE );
    
    if( isset($_POST) && !empty( $_POST ) ){

      $this->load->model("Solicitudes_cxp");

      $data = array(
        "idEmpresa" => $this->input->post("idempresa")
      );

      $dato_previo = $this->db->query("SELECT CONCAT( empresas.nombre ) empresa FROM empresas WHERE idEmpresa IN ( SELECT solpagos.idEmpresa FROM solpagos WHERE solpagos.idsolicitud = '".$this->input->post("idsolicitud")."' )");
      $this->Solicitudes_cxp->actualizar_solictud( $this->input->post("idsolicitud"), $data );

      if($_POST['observaciones']==""){
        $observaciones = "SE CAMBIADO LA EMPRESA DE ESTE REGISTRO. EMPRESA ORIGEN ".$dato_previo->row()->empresa;
      }else{
        $observaciones = $_POST['observaciones'];
      }
      log_sistema($this->session->userdata("inicio_sesion")['id'], $this->input->post("idsolicitud"), strtoupper($observaciones));
    
      $resultado = array( "resultado" => true, "solicitud" => $this->Solicitudes_cxp->sol_individual( $this->input->post("idsolicitud") )->result_array()[0] );
    }

    echo json_encode( $resultado );
  }

  //SET FORMA DE PAGO PARA LAS CAJAS CHICAS TEA, EFEC, ECHQ
  public function update_serie_ch(){

    $resultado = FALSE;

    if( isset( $_POST ) && !empty( $_POST ) ){

      $idproveedor = $this->input->post("idcuenta");
      $tipoPago = $this->input->post("tipoPago_chica");
      $numctaserie = limpiar_dato($this->input->post("serie_cheque_ch"));
      $idpago = $this->input->post("idpago");

      if( $tipoPago == 'TEA' ) {
        $data = array(
          "estatus" => 1,
          "idproveedor" => $idproveedor,
          "referencia" => $numctaserie,
          "tipoPago" => "TEA"
        );

      }else{
        $data = array(
          "estatus" => 1,
          "referencia" => $numctaserie,
          "tipoPago" => $tipoPago
        );
      }

      $resultado = $this->db->update( "autpagos_caja_chica", $data, "idpago = $idpago" );
      
      if($resultado){/*Esta sección agrega comentario a cada uno de los pago de la caja chica*/
          $ids_sol="";
          $query=$this->db->query("select idsolicitud from autpagos_caja_chica where idpago=$idpago");
          foreach ($query->result() as $row)
              $ids_sol=$row->idsolicitud;
          
          $ids_sol= explode(",", $ids_sol);
          $comentarios=array();
          foreach ($ids_sol as $id){
              array_push($comentarios, array(
                    "idsolicitud" => $id,
                    "tipomov" => "SE HA ASIGNADO EL REEMBOLSO COMO $tipoPago",
                    "idusuario" => $this->session->userdata('inicio_sesion')['id'],
                    "fecha" => date("Y-m-d H:i:s")
                ));
          }
          $resultado=$this->db->insert_batch( 'logs', $comentarios )>0;
      }
      
    }

    echo json_encode( array( "resultado" => $resultado, "valores_enviados" => $data ) );
  }

  //SET FROMA PAGOS REEMBOLSOS
  public function update_serie_reembolsos(){
		$resultado = FALSE;

		if( isset( $_POST ) && !empty( $_POST ) ){

			$idproveedor = $this->input->post("idcuentaReem");
			$tipoPago = $this->input->post("tipoPago_reem");
			$numctaserie = limpiar_dato($this->input->post("serie_cheque_reem"));
			$idpago = $this->input->post("idpago");

			if( $tipoPago == 'TEA' ) {
				$data = array(
					"estatus" => 1,
					"idproveedor" => $idproveedor,
					"referencia" => $numctaserie,
					"tipoPago" => "TEA"
				);

			}else{
				$data = array(
					"estatus" => 1,
					"referencia" => $numctaserie,
					"tipoPago" => $tipoPago
				);
			}

			$resultado = $this->db->update( "autpagos_caja_chica", $data, "idpago = $idpago" );

			if($resultado){/*Esta sección agrega comentario a cada uno de los pago de la caja chica*/
				$ids_sol="";
				$query=$this->db->query("select idsolicitud from autpagos_caja_chica where idpago=$idpago");
				foreach ($query->result() as $row)
					$ids_sol=$row->idsolicitud;

				$ids_sol= explode(",", $ids_sol);
				$comentarios=array();
				foreach ($ids_sol as $id){
					array_push($comentarios, array(
						"idsolicitud" => $id,
						"tipomov" => "SE HA ASIGNADO EL REEMBOLSO COMO $tipoPago",
						"idusuario" => $this->session->userdata('inicio_sesion')['id'],
						"fecha" => date("Y-m-d H:i:s")
					));
				}
				$resultado=$this->db->insert_batch( 'logs', $comentarios )>0;
			}

		}

		echo json_encode( array( "resultado" => $resultado, "valores_enviados" => $data ) );
	}


  public function update_serie_viaticos(){
		$resultado = FALSE;

		if( isset( $_POST ) && !empty( $_POST ) ){

			$idproveedor = $this->input->post("idcuentaVia");
			$tipoPago = $this->input->post("tipoPago_via");
			$numctaserie = limpiar_dato($this->input->post("serie_cheque_via"));
			$idpago = $this->input->post("idpago");

			if( $tipoPago == 'TEA' ) {
				$data = array(
					"estatus" => 1,
					"idproveedor" => $idproveedor,
					"referencia" => $numctaserie,
					"tipoPago" => "TEA"
				);

			}else{
				$data = array(
					"estatus" => 1,
					"referencia" => $numctaserie,
					"tipoPago" => $tipoPago
				);
			}

			$resultado = $this->db->update( "autpagos_caja_chica", $data, "idpago = $idpago" );

			if($resultado){/*Esta sección agrega comentario a cada uno de los pago de la caja chica*/
				$ids_sol="";
				$query=$this->db->query("select idsolicitud from autpagos_caja_chica where idpago=$idpago");
				foreach ($query->result() as $row)
					$ids_sol=$row->idsolicitud;

				$ids_sol= explode(",", $ids_sol);
				$comentarios=array();
				foreach ($ids_sol as $id){
					array_push($comentarios, array(
						"idsolicitud" => $id,
						"tipomov" => "SE HA ASIGNADO EL REEMBOLSO COMO $tipoPago",
						"idusuario" => $this->session->userdata('inicio_sesion')['id'],
						"fecha" => date("Y-m-d H:i:s")
					));
				}
				$resultado=$this->db->insert_batch( 'logs', $comentarios )>0;
			}

		}

		echo json_encode( array( "resultado" => $resultado, "valores_enviados" => $data ) );
	}

  public function revisar_proveedores(){
    if( isset( $_POST ) && !empty( $_POST  ) ){
      $this->load->model("Solicitudes_cxp");

      $proveedores = array();

      if( $this->input->post("departamento") == 'TARJETA CREDITO' ){
        $proveedores = $this->Solicitudes_cxp->get_listado_proveedoresbyid( $this->input->post("idusuario") )->result_array();
      }else{
        $proveedores = $this->Solicitudes_cxp->get_listado_proveedores( $this->input->post("idusuario") )->result_array();
      }

      echo json_encode( $proveedores );
    }
  }

  //RECHAZAR DEVOLCIONES O TRASPASOS DESDE CXP POR MEDIO DEL PAGO
  /***********CONSULTAMOS EL HISTORIAL DE REGISTRO**************/
  function consultar_areas_proceso_menor(){
    $data_array = FALSE;
    if (!empty($_POST)){

      $this->load->model("M_Devolucion_Traspaso");

      //MANEJAMOS EL ID DE LA SOLICITUD
      //@uthor Efraín Martinez Muñoz || Fecha: 31/01/2024 || Se agrego el idproceso al arreglo.
      $idsolicitud = $this->db->query("SELECT idsolicitud FROM autpagos WHERE idpago = ?", [ $this->input->post('idpago') ])->row()->idsolicitud;

      $info_sol = $this->M_Devolucion_Traspaso->info_sol($idsolicitud);
      $etapa = $info_sol->row()->idetapa;
      $idproceso = $info_sol->row()->idproceso;
      $orden = $this->M_Devolucion_Traspaso->proceso_etapas_menor($idproceso, $etapa);
      $data_array["data"] = array();
      if ($orden->num_rows() > 0) {
        foreach ($orden->result() as  $value) {
            $data_array["data"][] = array(
                "nombre" => $value->etapa_regresar,
                "solicitud" => $idsolicitud,
                "idrol" => $value->idrol,
                "orden" => $value->orden,
                "idetapa" => $value->idetapa,
                "idproceso" => $idproceso
            );
        }
      }
    }
    echo json_encode($data_array);
  }

  /***********CONSULTAMOS EL HISTORIAL DE REGISTRO**************/
  function regresar_sol_area(){
    $respuesta = FALSE;
    if (!empty($_POST)) {

        $this->db->trans_begin();
        $this->load->model("M_Devolucion_Traspaso");
        $orden = $this->input->post('radios');
        $text_comentario = $this->input->post('text_comentario');
        $idsolicitud = $this->input->post('solicitud_fom');

        $info_sol = $this->M_Devolucion_Traspaso->info_sol($idsolicitud);

        if ($info_sol->num_rows() > 0) {

            $proceso_info = $this->M_Devolucion_Traspaso->proceso_info($info_sol->row()->idproceso, $orden);
            $this->M_Devolucion_Traspaso->actualizar_etapa($proceso_info->row()->idetapa, $idsolicitud, 2);
            $this->M_Devolucion_Traspaso->observaciones_doc_descartado($idsolicitud, 'SE RECHAZA POR: ' . strtoupper($text_comentario));
            $respuesta = TRUE;
        }

        if ($respuesta === FALSE || $this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $respuesta = FALSE;
        } else {
            $this->db->trans_commit();
            $respuesta = TRUE;
        }
    }

    echo json_encode($respuesta);
  }
  /**
   * Inicio
   * Fecha 12-Agosto-2025 @author Efrain Martinez programador.analista38@ciudadmaderas.com
   * Se crea la función cancelar_historial_cch que permite cancelar las solicitudes de caja chica, TDC, y viáticos
   */
  public function cancelar_historial_cch(){
    // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
    $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
    $data =  base64_decode(file_get_contents('php://input'));
    $resultado = array( "resultado" => FALSE );
    $resultado["eliminada"] = FALSE;
    
    if( $data && $data !== FALSE ){
      $this->load->model("Complemento_cxpModel");
      $data = json_decode( $data );

      if( $data ){
        $idsolicitud = $data->idsolicitud;
        $observaciones = $data->justificacion;
        $idusuario = $this->session->userdata("inicio_sesion")['id'];
        // como $data->sols_elim solo tiene un registro, lo forzamos a string
        $sols_elim = is_array($idsolicitud) ? $idsolicitud[0] : $idsolicitud;
        $this->db->trans_begin();
        // Consulta que permite saber si la solicitud ya tiene un registro en autpagos_caja_chica. 
        $idPago = $this->db->query("SELECT * FROM autpagos_caja_chica WHERE idsolicitud LIKE '%$idsolicitud%'")->row();
        if($idPago){
          // Se valida si la fechaDis del pago es null o si el idusuario es 257 se realiza lo que se encuentra dentro del if
          if(is_null($idPago->fechaDis) || $idusuario == 257){
            //Se elimina la solicitud que se esta cancelando del la columna idsolicitud de autpagos_caja_chica
            $solicitudr = explode(",", $idPago->idsolicitud);
            $elimSolcch = array_diff($solicitudr, [$sols_elim]);
            $solCch = implode(",", $elimSolcch);
            // Si al eliminar la solicitud que se va a cancelar, aun quedan solicitudes, entonces se hace un select a la tabla de solpagos con las solicitudes que quedan
            // y se realiza la suma de las cantidades de estas solicitudes para actualizar el pago con estos nuevos datos, tambien se elimina la relacion de la solicitud
            // con el pago de la tabla autpagos_cchsol.
            if($solCch){
              $cantidadAutpagoscch = $this->db->query("SELECT SUM(cantidad) AS sumaCantidadTotal FROM solpagos WHERE idsolicitud IN ($solCch)  ")->row();
              $this->db->query("UPDATE autpagos_caja_chica SET idsolicitud = '$solCch', cantidad = $cantidadAutpagoscch->sumaCantidadTotal WHERE idpago = $idPago->idpago");
              $this->db->query("DELETE FROM autpagos_cchsol WHERE idsolicitud = $idsolicitud");
            }else{
              //En caso de que $solCch no tenga datos, se elimina el pago de autpagos_caja_chica y la relación de autpagos_cchsol.
              $this->db->query("DELETE FROM autpagos_caja_chica WHERE idpago = $idPago->idpago");
              $this->db->query("DELETE FROM autpagos_cchsol WHERE idsolicitud = $idPago->idpago");
            }
      
            // Se busca si la solicitud a cancelar esta relacionada con otra factura 
            $solr = $this->db->query("SELECT idfactura, idsolicitudr FROM facturas WHERE idsolicitudr LIKE '%$idsolicitud%' AND idsolicitud <> $idsolicitud");
            if($solr->num_rows()>0){
              // Si la solcitud esta relacionada con otra o otras facturas, se elimina la relacion con la factura de la columna idsolicitudr y de la tabla relacional sol_factura
              $facturaRelcacionadaSol = $solr->result_array();
              for($i=0; $i < count($facturaRelcacionadaSol); $i++){
                $solicitudr = explode(",", $facturaRelcacionadaSol[$i]['idsolicitudr']);
                $idFacturaR = $facturaRelcacionadaSol[$i]['idfactura'];
                // quitamos el valor único de la lista original
                $sol_elim = array_diff($solicitudr, [$sols_elim]);
                $sol_a_actualizar = implode(",", $sol_elim);
          
                $this->db->query("UPDATE facturas SET idsolicitudr = '$sol_a_actualizar' WHERE idfactura = $idFacturaR");
                $this->db->query("DELETE FROM sol_factura WHERE idsolicitud = $idsolicitud AND idfactura=$idFacturaR");
              }
            }
            //Se busca si la solicitud a cancelar ya cuenta con facturas cargadas.
            $facturas = $this->db->query("SELECT idfactura FROM facturas WHERE idsolicitud = ?", array($idsolicitud));
            if($facturas->num_rows()>0){
              $resultadoF = $facturas->result_array();
              // En caso de que ya cuente con facturas cargadas, de cada una de las facturas de la solicitud se busca si tienen solicitudes relacionadas.
              // y de ser asi se elimina la relación.
              for($i=0; $i < count($resultadoF); $i++){

                $solicitiudesRelacionadas = $this->db->query("SELECT idsolicitudr, uuid, idsolicitud FROM facturas WHERE idfactura = ? AND idsolicitudr IS NOT NULL AND idsolicitudr != ''", $resultadoF[$i]['idfactura'])->row();
                
                if($solicitiudesRelacionadas){
                  $this->db->query("UPDATE solpagos SET idetapa = 10 WHERE idsolicitud IN ($solicitiudesRelacionadas->idsolicitudr) AND idetapa = 11");
                  $this->Complemento_cxpModel->multiLogs( "SE ELIMINÓ LA RELACIÓN QUE LA FACTURA CON FOLIO FISCAL: ".$solicitiudesRelacionadas->uuid." TENÍA CON LA (S) SOLICITUD (ES): ".$solicitiudesRelacionadas->idsolicitudr, $solicitiudesRelacionadas->idsolicitud);
                  $sols_elimR = is_array($solicitiudesRelacionadas->idsolicitud) ? $solicitiudesRelacionadas->idsolicitud[0] : $solicitiudesRelacionadas->idsolicitud;
                  $solicitudR = explode(",", $solicitiudesRelacionadas->idsolicitudr);
                  $sol_elimR = array_diff($solicitudR, [$sols_elimR]);
                  $sol_a_actualizarR = implode(",", $sol_elimR);
                  if($sol_a_actualizarR){
                    $this->Complemento_cxpModel->multiLogs( "SE ELIMINÓ LA SOLICITUD DE LA FACTURA CON FOLIO FISCAL: ".$solicitiudesRelacionadas->uuid, $sol_a_actualizarR);
                  }
                }
                // A la factura relacionada con la solicitud a cancelar se cancela y se elimina la relacion de la tabla sol_factura.
                $this->db->query("UPDATE facturas SET tipo_factura = 0, uuid = SUBSTRING( uuid, 1, CHAR_LENGTH(uuid) - 1) WHERE idfactura = ?",$resultadoF[$i]['idfactura']); 
                $this->db->query("DELETE FROM sol_factura WHERE idfactura = ?", $resultadoF[$i]['idfactura']);

              }
            }
            // Se actualiza la etapa de la solicitud a 30 y se realiza un registro en logs de este movimiento y se guardaq la observacion del porque se cancelo la solicitud.
            $this->db->query("UPDATE solpagos SET idetapa = 30 WHERE idsolicitud = ?", array( $idsolicitud ) );
            $this->db->query("DELETE FROM autpagos WHERE idsolicitud = ?", array( $idsolicitud ));

            chat( $idsolicitud, $observaciones, $this->session->userdata("inicio_sesion")['id']);
            log_sistema($this->session->userdata("inicio_sesion")['id'], $idsolicitud, "SE CANCELÓ DESDE EL HISTORIAL ¬¬");
            // Se reescribe el nodo del arreglo [eliminada] y se le asigna true que indica que la solicitud se cancelo correctamente.
            $resultado["eliminada"] = TRUE;
            
          }

        }else{
          // Se busca si la solicitud a cancelar esta relacionada con otra factura 
          $solr = $this->db->query("SELECT idfactura, idsolicitudr FROM facturas WHERE idsolicitudr LIKE '%$idsolicitud%' AND idsolicitud <> $idsolicitud");
          if($solr->num_rows()>0){
            // Si la solcitud esta relacionada con otra o otras facturas, se elimina la relacion con la factura de la columna idsolicitudr y de la tabla relacional sol_factura
            $facturaRelcacionadaSol = $solr->result_array();
            for($i=0; $i < count($facturaRelcacionadaSol); $i++){
              $solicitudr = explode(",", $facturaRelcacionadaSol[$i]['idsolicitudr']);
              $idFacturaR = $facturaRelcacionadaSol[$i]['idfactura'];
              // quitamos el valor único de la lista original
              $sol_elim = array_diff($solicitudr, [$sols_elim]);
              $sol_a_actualizar = implode(",", $sol_elim);
        
              $this->db->query("UPDATE facturas SET idsolicitudr = '$sol_a_actualizar' WHERE idfactura = $idFacturaR");
              $this->db->query("DELETE FROM sol_factura WHERE idsolicitud = $idsolicitud AND idfactura=$idFacturaR");
            }
          }
          //Se busca si la solicitud a cancelar ya cuenta con facturas cargadas.
          $facturas = $this->db->query("SELECT idfactura FROM facturas WHERE idsolicitud = ?", array($idsolicitud));
          if($facturas->num_rows()>0){
            $resultadoF = $facturas->result_array();
            // En caso de que ya cuente con facturas cargadas, de cada una de las facturas de la solicitud se busca si tienen solicitudes relacionadas.
            // y de ser asi se elimina la relación.
            for($i=0; $i < count($resultadoF); $i++){

              $solicitiudesRelacionadas = $this->db->query("SELECT idsolicitudr, uuid, idsolicitud FROM facturas WHERE idfactura = ? AND idsolicitudr IS NOT NULL AND idsolicitudr != ''", $resultadoF[$i]['idfactura'])->row();
              
              if($solicitiudesRelacionadas){
                $this->db->query("UPDATE solpagos SET idetapa = 10 WHERE idsolicitud IN ($solicitiudesRelacionadas->idsolicitudr) AND idetapa = 11");
                $this->Complemento_cxpModel->multiLogs( "SE ELIMINÓ LA RELACIÓN QUE LA FACTURA CON FOLIO FISCAL: ".$solicitiudesRelacionadas->uuid." TENÍA CON LA (S) SOLICITUD (ES): ".$solicitiudesRelacionadas->idsolicitudr, $solicitiudesRelacionadas->idsolicitud);
                $sols_elimR = is_array($solicitiudesRelacionadas->idsolicitud) ? $solicitiudesRelacionadas->idsolicitud[0] : $solicitiudesRelacionadas->idsolicitud;
                $solicitudR = explode(",", $solicitiudesRelacionadas->idsolicitudr);
                $sol_elimR = array_diff($solicitudR, [$sols_elimR]);
                $sol_a_actualizarR = implode(",", $sol_elimR);
                if($sol_a_actualizarR){
                  $this->Complemento_cxpModel->multiLogs( "SE ELIMINÓ LA SOLICITUD DE LA FACTURA CON FOLIO FISCAL: ".$solicitiudesRelacionadas->uuid, $sol_a_actualizarR);
                }
              }
              
              // A la factura relacionada con la solicitud a cancelar se cancela y se elimina la relacion de la tabla sol_factura.
              $this->db->query("UPDATE facturas SET tipo_factura = 0, uuid = SUBSTRING( uuid, 1, CHAR_LENGTH(uuid) - 1) WHERE idfactura = ?",$resultadoF[$i]['idfactura']); 
              $this->db->query("DELETE FROM sol_factura WHERE idfactura = ?", $resultadoF[$i]['idfactura']);

            }
          }
          // Se actualiza la etapa de la solicitud a 30 y se realiza un registro en logs de este movimiento y se guardaq la observacion del porque se cancelo la solicitud.
          $this->db->query("UPDATE solpagos SET idetapa = 30 WHERE idsolicitud = ?", array( $idsolicitud ) );
          $this->db->query("DELETE FROM autpagos WHERE idsolicitud = ?", array( $idsolicitud ));

          chat( $idsolicitud, $observaciones, $this->session->userdata("inicio_sesion")['id']);
          log_sistema($this->session->userdata("inicio_sesion")['id'], $idsolicitud, "SE CANCELÓ DESDE EL HISTORIAL ¬¬");
          // Se reescribe el nodo del arreglo [eliminada] y se le asigna true que indica que la solicitud se cancelo correctamente.
          $resultado["eliminada"] = TRUE;

        }
        
        if ($this->db->trans_status() === FALSE){
          $this->db->trans_rollback();
          $resultado["resultado"] = FALSE;
          
        }else{
          $this->db->trans_commit();
          $resultado["resultado"] = TRUE;
        }
      }
    }
    echo json_encode( $resultado );
  }
  /**
   * Fin 
   * Fecha : 12-Agosto-2025 @author Efrain Martinez programador.analista38@ciudadmaderas.com
   */
  /************************************************************/
}