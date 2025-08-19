<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cuentasxp extends CI_Controller
{

  function __construct()
  {
    parent::__construct();
    /** INICIO FECHA: 17-JUNIO-2025 | PANEL CP A USUARIOS ESPECIFICOS | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> 
     * 2409	- AS - cap.finanza LUGO SOSA	JUAN MANUEL
     * 2681	- CA - VA.MONCADA MONCADA GUERRA VALERIA EDITH
     * **/
    if ($this->session->userdata("inicio_sesion") && (in_array($this->session->userdata("inicio_sesion")['rol'], array('CP', 'SO')) ||   in_array($this->session->userdata("inicio_sesion")['id'], array('257', '2681', '2409'))))
      /**
       * Fecha: 12/Agosto/2025 @author Efrain Martinez programador.analista.38@ciudadmaderas.com
       * Se agrega el modelo Complemento_cxpModel al load se utilizará la función multiLogs en la funcionde cancelar la solicitud. 
       */
      $this->load->model(array('Solicitudes_cxp', 'Provedores_model', 'Complemento_cxpModel'));
    else
      redirect("Login", "refresh");
  }

  public function index()
  {
    $this->load->view("vista_cxp");
  }

  public function nuevas()
  {
    $this->load->view("vista_nuevas_cxp");
  }

  public function tabla_programados_espera()
  {
    $this->load->model("Solicitudes_cxp");
    echo json_encode(array("data" => $this->Solicitudes_cxp->get_solicitudes_programadas_nuevas()->result_array()));
  }

  public function vista_confirmar()
  {
    $this->load->view("vista_confirmar_cxp");
  }

  public function borrar_solicitud()
  {
    $respuesta = array("resultado" => FALSE);

    if ($this->input->post("idsolicitud")) {
      $respuesta['resultado'] = $this->Solicitudes_cxp->borrar_solicitud($this->input->post("idsolicitud"));
      log_sistema($this->session->userdata("inicio_sesion")['id'], $this->input->post("idsolicitud"), "HA ELIMINADO LA SOLICITUD DEL SISTEMA POR CXP ¬¬");
    }

    echo json_encode($respuesta);
  }

  public function cambiarmetodo($pago, $metodo)
  {
    $json['resultado'] = FALSE;
    if ($pago) {
      $this->load->model("Solicitudes_cxp");
      $this->Solicitudes_cxp->cambiarmetodoM($pago, $metodo);
      $json['resultado'] = TRUE;
    }

    echo json_encode($json);
  }


  public function cambiarempresa($pago, $empresa)
  {
    // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
    $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
    $json['resultado'] = FALSE;
    if ($pago) {
      $this->load->model("Solicitudes_cxp");

      $idpago = $pago;
      $nuevo_empresa = $empresa;

      $empresa_consulta = $this->db->query("SELECT idempresa, abrev FROM empresas where idempresa in(" . $empresa . ")");
      $solicitud_consulta = $this->db->query("SELECT idsolicitud FROM autpagos where idpago = " . $idpago);

      $this->db->query("UPDATE solpagos SET idEmpresa = '" . $empresa_consulta->row()->idempresa . "' WHERE idsolicitud = " . $solicitud_consulta->row()->idsolicitud);


      log_sistema($this->session->userdata("inicio_sesion")['id'], $solicitud_consulta->row()->idsolicitud, "CXP CAMBIO LA EMPRESA A " . $empresa_consulta->row()->abrev . "");
      $json['resultado'] = TRUE;
    }

    echo json_encode($json);
  }





  public function cambiarmetodofact()
  {
    // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
    $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
    $json['resultado'] = FALSE;
    if ($this->input->post("idpago")) {
      $this->load->model("Solicitudes_cxp");

      $idpago = $this->input->post("idpago");
      $nuevo_metodo = $this->input->post("nuevo_metodo");

      $sol_consulta =    $this->db->query("SELECT * FROM autpagos where idpago = " . $idpago);


      $this->db->query("UPDATE autpagos SET estatus = 0, tipoPago = '" . $nuevo_metodo . "', formaPago = '" . $nuevo_metodo . "' WHERE idpago = " . $idpago);
      $this->db->query("UPDATE solpagos SET metoPago = '" . $nuevo_metodo . "' WHERE idsolicitud = " . $sol_consulta->row()->idsolicitud);


      log_sistema($this->session->userdata("inicio_sesion")['id'], $sol_consulta->row()->idsolicitud, "CXP CAMBIO EL METODO DE PAGO");
      $json['resultado'] = TRUE;
    }

    echo json_encode($json);
  }




  public function cambiarmetodoOTROS()
  {
    // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
    $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
    $json['resultado'] = FALSE;
    if ($this->input->post("idpago")) {
      $this->load->model("Solicitudes_cxp");

      $idpago = $this->input->post("idpago");
      $nuevo_metodo = $this->input->post("nuevo_metodo2");
      $sol_consulta_ot = $this->db->query("SELECT * FROM autpagos where idpago = " . $idpago);

      $this->db->query("UPDATE autpagos SET estatus = 0, tipoPago = '" . $nuevo_metodo . "', formaPago = '" . $nuevo_metodo . "', tipoPago = '" . $nuevo_metodo . "'  WHERE idpago = " . $idpago);
      $this->db->query("UPDATE solpagos SET metoPago = '" . $nuevo_metodo . "' WHERE idsolicitud = " . $sol_consulta_ot->row()->idsolicitud);
      log_sistema($this->session->userdata("inicio_sesion")['id'], $sol_consulta_ot->row()->idsolicitud, "CXP CAMBIO EL METODO DE PAGO");

      $json['resultado'] = TRUE;
    }

    echo json_encode($json);
  }




  public function lista_provs()
  {
    echo json_encode($this->Solicitudes_cxp->get_provs()->result_array());
  }



  public function regresar_pago()
  {
    if ($this->input->post("idpago")) {
      $this->Solicitudes_cxp->regresar_pago($this->input->post("idpago"));
    }
  }

  public function tabla_autorizaciones()
  {
    $resultado = $this->Solicitudes_cxp->get_solicitudes_autorizadas_area();

    if ($resultado->num_rows() > 0) {
      $resultado = $resultado->result_array();
      for ($i = 0; $i < count($resultado); $i++) {
        $resultado[$i]['opciones'] = $this->opciones_autorizaciones($resultado[$i]['idetapa'], $resultado[$i]['idusuario']);
        if ($resultado[$i]['idetapa'] === 3 && $resultado[$i]['caja_chica'] === 1) {
          $resultado[$i]['etapa'] = "Caja Chica";
        }
      }
    } else {
      $resultado = array();
    }

    echo json_encode(array("data" => $resultado));
  }



  public function opciones_autorizaciones($estatus, $autor)
  {
    return "";
  }

  //OBTENEMOS TODOS LOS REGISTROS PARA PAGO A PROVEEDOR EN ESPERA DE VoBo DE CXP
  public function ver_nuevas_proveedor()
  {
    $this->load->model("Solicitudes_cxp");
    echo json_encode(array("data" => $this->Solicitudes_cxp->get_solicitudes_nuevas_proveedor()->result_array()));
  }



  public function ver_nuevas_fact()
  {

    $this->load->model("Solicitudes_cxp");
    $dat =  $this->Solicitudes_cxp->get_solicitudes_nuevas_factoraje()->result_array();
    echo json_encode(array("data" => $dat));
  }



  public function getValorCheque($data)
  {

    $this->load->model("Solicitudes_cxp");
    $dat =  $this->Solicitudes_cxp->get_valor_echq($data)->result_array();
    echo json_encode(array("data" => $dat));
  }



  public function ver_datos_fin()
  {

    $this->load->model("Solicitudes_cxp");

    $dat =  $this->Solicitudes_cxp->get_pagos_todos($this->input->post("nomdepto"), $this->input->post("idempresa"), $this->input->post("idproveedor"), $this->input->post("fecha_pago"))->result_array();
    echo json_encode(array("data" => $dat));
  }




  public function ver_datos_finales()
  {
    $this->load->model("Solicitudes_cxp");
    $dat =  $this->Solicitudes_cxp->get_solicitudes_autorizadas_dp()->result_array();
    echo json_encode(array("data" => $dat));
  }

  //APLICA SOLO PARA LAS CAJAS CHICAS.
  public function ver_datos_finales_ch()
  {
    $this->load->model("Solicitudes_cxp");
    echo json_encode(array("data" => $this->Solicitudes_cxp->get_solicitudes_autorizadas_dp_cch()->result_array()));
  }

  //APLICA SOLO PARA LAS TARJETAS DE CREDITO.
  public function ver_datos_finales_tdc()
  {
    $this->load->model("Solicitudes_cxp");
    echo json_encode(array("data" => $this->Solicitudes_cxp->get_solicitudes_autorizadas_dp_TDC()->result_array()));
  }

  //CONFIRMAMOS EL PAGO REALIZADO
  /**
   * @author DANTE ALDAIR GUERRERO ALDANA <programador.analista18@ciudadmaderas.com>
   *
   * @param [bit] $tendraFact
   * 
   * Línea con comentario "Inicio tendrá factura":
   * Se condiciona el tipo de facturación correspondiente al proveedor de la solicitud
   * Si la opción es "OBLIGATORIO CARGAR XML (0)", la variable tendraFactura tomará el valor "1", lo que indica que 
   * dicha solicitud pedirá o requerirá factura. En caso de que el proveedor sea del tipo de factura: 
   * "XML POSTERIOR AL PAGO (1)", se recibe una respuesta del lado del cliente para definir si lleva o no factura (1 o 0).
   */
  public function datos_para_poliza($pago)
  {
    // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
    $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
    $json['resultado'] = FALSE;
    if (!empty($_POST) && isset($_POST)) {

      $etapa = 16;

      $this->db->update("autpagos", array(
        "estatus" => '16',
        "fecha_pago" => $this->input->post("fecha_pago"),
        "formaPago" => $this->input->post("formaPago"),
        "fecha_cobro" => $this->input->post("fecha_pago"),
        "referencia" => ($this->input->post("idcheque_general") ? $this->input->post("idcheque_general") : null)
      ), "idpago = $pago AND estatus IN ( 11, 15 )");

      $solicitud =  $this->db->query("SELECT 
              ap.*,
                sp.idProveedor,
                sp.nomdepto,
                sp.idproceso
            FROM autpagos ap
            LEFT JOIN solpagos sp on ap.idsolicitud = sp.idsolicitud 
            WHERE idpago = $pago");
      $idsolicitud =  $this->db->query("SELECT idsolicitud FROM autpagos  WHERE idpago = $pago")->row()->idsolicitud;
      log_sistema($this->session->userdata("inicio_sesion")['id'], $idsolicitud, "SE CONFIRMÓ PAGO POR CXP CON FECHA: " . $this->input->post("fecha_pago"));

      /**
       * Inicio Cambios Dante Aldair 23-04-2024
       * "Inicio tendrá factura"
       */
      if ($this->input->post("excp") == 0) {
        $tendraFact = 1;
        $json['tendraFact'] = $this->Solicitudes_cxp->updateSolicitudFactura($idsolicitud, $tendraFact);
        // log_sistema($this->session->userdata("inicio_sesion")['id'], $idsolicitud, "CXP DEFINE QUE TENDRÁ FACTURA DESDE LA CONFIRMACIÓN DE PAGO.");
      } elseif ($this->input->post("excp") == 1) {
        $tendraFact = $this->input->post("tendraFactura") == 1
          ? 1
          : null;
        $json['tendraFact'] = $this->Solicitudes_cxp->updateSolicitudFactura($idsolicitud, $tendraFact);
        log_sistema($this->session->userdata("inicio_sesion")['id'], $idsolicitud, $tendraFact == 1 ? "CXP DEFINE QUE TENDRÁ FACTURA DESDE LA CONFIRMACIÓN DE PAGO." : "CXP DEFINE SIN FACTURA DESDE LA CONFIRMACIÓN DE PAGO.");
      } else {
        $tendraFact = null;
        $json['tendraFact'] = $this->Solicitudes_cxp->updateSolicitudFactura($idsolicitud, $tendraFact);
      }
      /**Fin cambios Dante Aldair**/

      //Metodo para buscar si el proveedor esta vinculado a otra sol activa y si status != 1, de no ser asi desactivar prov pasar a estatus 3
      $depto_prov = $this->db->query("SELECT nomdepto, idProveedor FROM solpagos  WHERE idsolicitud = $idsolicitud");
      $noDeptos = array('TRASPASO', 'DEVOLUCIONES', 'CESION OOAM', 'RESCISION OOAM', 'DEVOLUCION OOAM', 'TRASPASO OOAM', 'DEVOLUCION DOM OOAM', 'INFORMATIVA', 'INFORMATIVA CERO');
      if (in_array($depto_prov->row()->nomdepto, $noDeptos)) {
        $sol_pend = $this->db->query("SELECT * FROM solpagos WHERE idetapa NOT IN (0,30,11) AND idProveedor = ? ", [$depto_prov->row()->idProveedor]);
        $status =  $this->db->query("SELECT estatus FROM proveedores  WHERE idproveedor = ? ", [$depto_prov->row()->idProveedor])->row()->estatus;
        if ($sol_pend->num_rows() == 0 && $status == 2) {
          /**
           * FECHA : 01-JULIO-2025 | @author Efrain Martinez progamador.analista38@ciudadmaderas.com | SE AGREGA EL ID DEL USUARIO QUE REALIZO LA ACTUALIZACION
           */
          $this->db->query("UPDATE proveedores SET estatus = 3, idupdate = ?, fecha_update = now() WHERE idproveedor = ?", [$this->session->userdata('inicio_sesion')['id'], $depto_prov->row()->idProveedor]);
        }

        if ($solicitud->row()->idproceso == 30) {
          $parcialidades = $this->db->query("SELECT ap.idsolicitud, 
                                                    count(ap.idpago) pagos,
                                                    sum(ap.cantidad) pagado,
                                                    spa.numeroParcialidades,
                                                    sp.cantidad,
                                                    if(count(ap.idpago) = spa.numeroParcialidades, 'S', 'N') esUltimaParcialidad
                                            FROM autpagos ap
                                            LEFT JOIN solpagos sp
                                              ON sp.idsolicitud = ap.idsolicitud
                                            LEFT JOIN solicitudesParcialidades spa
                                              ON spa.idsolicitud = ap.idsolicitud
                                            WHERE estatus IN (16) AND ap.idsolicitud = ? ", [$solicitud->row()->idsolicitud]);
          if ($parcialidades->num_rows() > 0) {
            /**
             * @author DANTE ALDAIR GUERRERO ALDANA <coordinador6.desarrollo@ciudadmaderas.com>
             * CAMBIO DE IDAUTORIZA Y FECHA DE AUTORIZACION EN CASO DE LAS SOLICITUDES DE TRASPASO Y DEVOLUCIONES PARA EL CORRECTO FUNCIONAMIENTO EN EL GATILLO A LA TABLA DE SOLPAGOS
             */
            $this->db->trans_start();
            $fecha = date("Y-m-d H:i:s");
            if ($parcialidades->row()->esUltimaParcialidad == 'S') {
              $this->db->query("UPDATE solpagos SET idetapa = 11, idAutoriza = ?, fecha_autorizacion = ? WHERE idsolicitud = ?", [$this->session->userdata("inicio_sesion")['id'], $fecha, $solicitud->row()->idsolicitud]);
            } else {
              $this->db->query("UPDATE solpagos SET idetapa = 78, idAutoriza = ?, fecha_autorizacion = ?, prioridad = NULL WHERE idsolicitud = ?", [$this->session->userdata("inicio_sesion")['id'], $fecha, $solicitud->row()->idsolicitud]);
            }
            $this->db->trans_complete(); // Confirma la transacción
          }
        }
      }

      $json['resultado'] = TRUE;
      $json['data'] =  $this->Solicitudes_cxp->get_solicitudes_autorizadas_dp()->result_array();
    }
    echo json_encode($json);
  }

  public function datos_para_chch($pago)
  {

    $json['resultado'] = FALSE;

    if (!empty($_POST) && isset($_POST)) {

      $query = $this->db->update("autpagos_caja_chica", array(
        "estatus" => ($this->input->post("formaPago") == "ECHQ" ? 15 : 16),
        "tipoPago" => $this->input->post("formaPago"),
        "fechaOpe" => date("Y-m-d H:i:s"),
        "referencia" => $this->input->post("idcheque_general"),
        "fecha_cobro" => $this->input->post("fecha_pago"),
      ), "idpago = '$pago'");
      if ($query) {
        $query = $this->db->query("select idsolicitud from autpagos_caja_chica where idpago=$pago;");
        $ids_sol = explode(",", $query->row()->idsolicitud);
        $comentarios = array();
        foreach ($ids_sol as $id) {
          array_push($comentarios, array(
            "idsolicitud" => $id,
            "tipomov" => "SE HA CONFIRMADO EL REEMBOLSO DE CAJA CHICA",
            "idusuario" => $this->session->userdata('inicio_sesion')['id'],
            "fecha" => date("Y-m-d H:i:s")
          ));
        }
        $json['resultado'] = $this->db->insert_batch('logs', $comentarios) > 0;
      }
    }

    echo json_encode($json);
  }

  //RECHAZO DE SOLICITUDES EN EL SISTEMA DE CXP
  public function datos_para_rechazo1()
  {
    // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
    $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
    $json['resultado'] = FALSE;
    if ($this->input->post("idsolicitud")) {
      $this->load->model("Solicitudes_cxp");

      $idsolicitud = $this->input->post("idsolicitud");
      $observacion = $this->input->post("observacion");

      $this->db->query("UPDATE solpagos SET idetapa = CASE WHEN solpagos.programado IS NULL THEN 8 ELSE 30 END WHERE idsolicitud = $idsolicitud");
      chat($idsolicitud, $observacion, $this->session->userdata("inicio_sesion")["id"]);

      /** INICIO FECHA: 17-JUNIO-2025 | PANEL CP A USUARIOS ESPECIFICOS | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
      if (in_array($this->session->userdata("inicio_sesion")['id'], [2681, 2409])) {
        log_sistema($this->session->userdata("inicio_sesion")['id'], $this->input->post("idsolicitud"), "SE RECHAZÓ POR " . $this->session->userdata("inicio_sesion")['depto']);
      } else {
        log_sistema($this->session->userdata("inicio_sesion")['id'], $this->input->post("idsolicitud"), "SE RECHAZÓ POR CXP");
      }
      /** FIN FECHA: 17-JUNIO-2025 | PANEL CP A USUARIOS ESPECIFICOS | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
      $json['resultado'] = TRUE;
    }
    echo json_encode($json);
  }




  public function datos_para_rechazochica()
  {
    // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
    $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
    $json['resultado'] = FALSE;
    if ($this->input->post("idsolicitud")) {
      $this->load->model("Solicitudes_cxp");

      $idsolicitud = $this->input->post("idsolicitud");
      $observacion = $this->input->post("observacion");

      $this->db->query('UPDATE solpagos SET idetapa = 8 WHERE idsolicitud = ' . $idsolicitud . '');
      chat($idsolicitud, $observacion, $this->session->userdata("inicio_sesion")["id"]);
      $json['resultado'] = TRUE;
    }
    echo json_encode($json);
  }





  public function datos_para_rechazo2()
  {
    // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
    $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
    $json['resultado'] = FALSE;
    if ($this->input->post("idpago")) {
      $this->load->model("Solicitudes_cxp");

      $idpago = $this->input->post("idpago");
      $observacion = $this->input->post("observacion");
      $this->db->query('UPDATE autpagos SET estatus = 0 , fecha_pago = null WHERE idpago = ' . $idpago . '');

      $respuesta_regresa_dispersion =  $this->db->query('SELECT idsolicitud FROM autpagos  WHERE idpago = ' . $idpago . '');
      log_sistema($this->session->userdata("inicio_sesion")['id'], $respuesta_regresa_dispersion->row()->idsolicitud, "SE REGRESÓ A GENERAR NUEVAMENTE PAGO EN CXP");
      log_sistema($this->session->userdata("inicio_sesion")['id'], $respuesta_regresa_dispersion->row()->idsolicitud,  $observacion);



      $json['resultado'] = TRUE;
    }
    echo json_encode($json);
  }




  public function datos_para_rechazo3()
  {
    $json['resultado'] = FALSE;
    if ($this->input->post("idpago")) {
      $this->load->model("Solicitudes_cxp");

      $idpago = $this->input->post("idpago");
      $observacion = $this->input->post("observacion");
      $this->db->query('UPDATE autpagos_caja_chica SET estatus = 0 , fecha_cobro = null WHERE idpago = ' . $idpago . '');

      // $respuesta_regresa_dispersion =  $this->db->query('SELECT idsolicitud FROM autpagos  WHERE idpago = '.$idpago.'');
      // log_sistema($this->session->userdata("inicio_sesion")['id'], $respuesta_regresa_dispersion->row()->idsolicitud, "SE REGRESÓ A GENERAR NUEVAMENTE PAGO EN CXP");
      // log_sistema($this->session->userdata("inicio_sesion")['id'], $respuesta_regresa_dispersion->row()->idsolicitud,  $observacion);



      $json['resultado'] = TRUE;
    }
    echo json_encode($json);
  }







  public function datos_para_rechazo2_ch()
  {
    $json['resultado'] = FALSE;
    if ($this->input->post("idpago")) {
      $this->load->model("Solicitudes_cxp");

      $idpago = $this->input->post("idpago");
      $observacion = $this->input->post("observacion");

      $this->db->query('UPDATE autpagos_caja_chica SET estatus = 51 WHERE idpago = ' . $idpago . '');


      $json['resultado'] = TRUE;
    }

    echo json_encode($json);
  }

  //ENVIAMOS A PAUSA EL PAGO QUE SE AUTORIZO
  public function enviarcolapagos_una()
  {
    // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
    $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
    $json['resultado'] = FALSE;
    if ($this->input->post("idpago")) {
      $this->load->model("Solicitudes_cxp");

      $idpago = $this->input->post("idpago");
      $observacion = $this->input->post("observacion");

      $this->db->query('UPDATE autpagos SET estatus = 12, motivoEspera = "' . $observacion . '" WHERE idpago = ' . $idpago . '');
      $respuesta =  $this->db->query('SELECT idsolicitud FROM autpagos  WHERE idpago = ' . $idpago . '');
      log_sistema($this->session->userdata("inicio_sesion")['id'], $respuesta->row()->idsolicitud, "SE ENVIÓ A ESPERA EL PAGO AUTORIZADO");
      chat($respuesta->row()->idsolicitud, "MÓTIVO ESPERA: " . $observacion, $this->session->userdata("inicio_sesion")['id']);

      $json['resultado'] = TRUE;
    }

    echo json_encode($json);
  }




  public function enviarcolapagos_fact()
  {
    // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
    $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
    $json['resultado'] = FALSE;
    if ($this->input->post("idpago")) {
      $this->load->model("Solicitudes_cxp");

      $idpago = $this->input->post("idpago");
      $observacion = $this->input->post("observacion");

      $this->db->query('UPDATE autpagos SET estatus = 12, motivoEspera = "' . $observacion . '" WHERE idpago = ' . $idpago . '');
      $respuesta =  $this->db->query('SELECT idsolicitud FROM autpagos  WHERE idpago = ' . $idpago . '');
      log_sistema($this->session->userdata("inicio_sesion")['id'], $respuesta->row()->idsolicitud, "SE ENVIÓ A ESPERA");
      log_sistema($this->session->userdata("inicio_sesion")['id'], $respuesta->row()->idsolicitud, "MÓTIVO ESPERA: " . $observacion);

      $json['resultado'] = TRUE;
    }

    echo json_encode($json);
  }

  public function enviarcolapagos_caja_chica()
  {
    $json['resultado'] = FALSE;
    if ($this->input->post("idpago")) {
      $this->load->model("Solicitudes_cxp");

      $idpago = $this->input->post("idpago");
      $observacion = $this->input->post("observacion");
      $this->db->query('UPDATE autpagos_caja_chica SET estatus=12, motivoEspera = "' . $observacion . '" WHERE idpago=' . $idpago);

      $json['resultado'] = TRUE;
    }

    echo json_encode($json);
  }

  //LISTADO DE PAGOS AUTORIZADOS TRANSFERENCIA
  public function ver_datos_autdg()
  {
    $this->load->model("Solicitudes_cxp");
    $dat =  $this->Solicitudes_cxp->get_solicitudes_historial_area()->result_array();
    echo json_encode(array("data" => $dat));
  }

  //LISTADO DE PAGOS PROGRAMADOS AUTORIZADOS TRANSFERENCIA
  public function ver_datos_autdg_prgo()
  {
    $this->load->model("Solicitudes_cxp");
    echo json_encode(array("data" => $this->Solicitudes_cxp->get_pagos_aut_prog()->result_array()));
  }


  public function ver_datos_autdgfact()
  {
    $this->load->model("Solicitudes_cxp");
    $dat =  $this->Solicitudes_cxp->get_solicitudes_historial_fact($this->input->post("valor_filtro"))->result_array();
    echo json_encode(array("data" => $dat));
  }

  public function ver_datos_autdg_otros()
  {
    $this->load->model("Solicitudes_cxp");
    echo json_encode(array("data" => $this->Solicitudes_cxp->get_solicitudes_historial_area2($this->input->post("nomdepto"), $this->input->post("idempresa"))->result_array()));
  }

  public function ver_datos_pausados()
  {
    $dat2 =  $this->Solicitudes_cxp->get_solicitudes_stop()->result_array();
    echo json_encode(array("data" => $dat2));
  }

  /*******************************************************/
  //SERVICIO DE PARA CONSULTAR PAGOS DE CAJA CHICA PARA PAGO
  function tablaSolCaja()
  {

    $data = $this->Solicitudes_cxp->obtenerSolCaja()->result_array();
    /*
    if( !empty($data) ){

      $data = $data->result_array();

      for( $i = 0; $i < count($data); $i++ ){
        $data[$i]['pa'] = 0;
        $data[$i]['solicitudes'] = $this->Solicitudes_cxp->getSolicitudesCCH( $data[$i]['ID'] );
      }
    }
    */
    echo json_encode(array("data" => $data));
  }
  function tablaSolReem()
  {

    $data = $this->Solicitudes_cxp->obtenerSolReemb()->result_array();
    echo json_encode(array("data" => $data));
  }
  function tablaSolViaticos()
  {

    $data = $this->Solicitudes_cxp->obtenerSolViat()->result_array();
    echo json_encode(array("data" => $data));
  }

  //SERVICIO PARA CONSULTAR REGISTROS DE AUTORIZADO PAGO DE TDC
  function tablaTDC()
  {
    $data = $this->Solicitudes_cxp->obtenerSolTDC();
    echo json_encode(array("data" => $data->result_array()));
  }

  /*******************************************************/

  /*******************************************************/
  //SERVICIO DE PARA CONSULTAR REGISTROS DE CAJA CHICA PARA PAGO
  function ver_nuevas_caja_chica()
  {
    $data = $this->Solicitudes_cxp->obtenerSolCajachica();
    if (!empty($data)) {
      $data = $data->result_array();
      for ($i = 0; $i < count($data); $i++) {
        $data[$i]['pa'] = 0;
        $data[$i]['solicitudes'] = null;
        //$data[$i]['solicitudes'] = $this->Solicitudes_cxp->get_solicitudes_nuevas_caja_chica( $data[$i]['ID'] );
      }
    }
    echo json_encode(array("data" => $data));
  }
  /*******************************************************/
  //SERVICIO DE PARA CONSULTAR REGISTROS DE CREEMBOLSOS
  function ver_nuevas_reembolsos()
  {
    $data = $this->Solicitudes_cxp->obtenerSolReembolsos();
    if (!empty($data)) {
      $data = $data->result_array();
      for ($i = 0; $i < count($data); $i++) {
        $data[$i]['pa'] = 0;
        $data[$i]['solicitudes'] = null;
        //$data[$i]['solicitudes'] = $this->Solicitudes_cxp->get_solicitudes_nuevas_caja_chica( $data[$i]['ID'] );
      }
    }
    echo json_encode(array("data" => $data));
  }

  //SERVICIO DE PARA CONSULTAR REGISTROS DE CREEMBOLSOS
  function ver_nuevas_viaticos()
  {
    $data = $this->Solicitudes_cxp->obtenerSolViaticos();
    if (!empty($data)) {
      $data = $data->result_array();
      for ($i = 0; $i < count($data); $i++) {
        $data[$i]['pa'] = 0;
        $data[$i]['solicitudes'] = null;
        //$data[$i]['solicitudes'] = $this->Solicitudes_cxp->get_solicitudes_nuevas_caja_chica( $data[$i]['ID'] );
      }
    }
    echo json_encode(array("data" => $data));
  }


  //SERVICIO PARA CONSULTAR REGISTROS DE REEMBOLSOS DE TDC
  function ver_nuevas_caja_chica_TDC()
  {
    $data = $this->Solicitudes_cxp->obtenerSolCajachica_TDC();
    if (!empty($data)) {
      $data = $data->result_array();
    }
    echo json_encode(array("data" => $data));
  }

  public function carga_cajas_chicas()
  {
    echo json_encode($this->Solicitudes_cxp->getsolicitudescajasChicas($this->input->post("idcajachicas"), $this->input->post("isCajaNoDeducible")));
  }
  /*******************************************************/


  function PagarTotalCajaChica()
  {
    $json = json_decode($this->input->post('jsonApagar'));
    foreach ($json as $row) {
      $this->Solicitudes_cxp->autorizarPagoC($row->idsolicitud, $row->totalpagar, $row->idempresa, $row->idresponsable, $row->nomdepto);
      $idsolicitudes = explode(',', $row->idsolicitud);
    }
  }

  public function ver_datos_cajachica()
  {
    $this->load->model("Solicitudes_cxp");
    $dat =  $this->Solicitudes_cxp->get_solicitudes_caja_chica()->result_array();
    echo json_encode(array("data" => $dat));
  }

  public function ver_datos_pagadas()
  {
    $this->load->model("Solicitudes_cxp");
    $dat =  $this->Solicitudes_cxp->get_solicitudes_pagadas()->result_array();
    echo json_encode(array("data" => $dat));
  }


  public function ver_datos_pagadaseg()
  {
    $this->load->model("Solicitudes_cxp");
    $dat =  $this->Solicitudes_cxp->get_solicitudes_pagadaseg()->result_array();
    echo json_encode(array("data" => $dat));
  }



  public function ver_datos_pagada_chic()
  {
    $this->load->model("Solicitudes_cxp");
    $dat =  $this->Solicitudes_cxp->get_solicitudes_pagada_chic()->result_array();
    echo json_encode(array("data" => $dat));
  }

  //MOVEMOS LA SOLICITUD A PAGO DE PROVEEDORES A FORMAR EN PAGO DE DIRECCION GENERAL
  public function enviarcpp()
  { //
    $respuesta = array(FALSE);

    if (isset($_POST) && !empty($_POST)) {
      $this->Solicitudes_cxp->update_sin_factura($this->input->post("idsolicitud"));
      /** INICIO FECHA: 17-JUNIO-2025 | PANEL CP A USUARIOS ESPECIFICOS | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
      if (in_array($this->session->userdata("inicio_sesion")['id'], [2681, 2409])) {
        log_sistema($this->session->userdata("inicio_sesion")['id'], $this->input->post("idsolicitud"), "SE ACEPTÓ LA SOLICTUD EN " . $this->session->userdata("inicio_sesion")['depto']);
      } else {
        log_sistema($this->session->userdata("inicio_sesion")['id'], $this->input->post("idsolicitud"), "SE ACEPTÓ LA SOLICTUD EN CXP");
      }
      /** FIN FECHA: 17-JUNIO-2025 | PANEL CP A USUARIOS ESPECIFICOS | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
      $respuesta = array(TRUE);
    }

    echo json_encode($respuesta);
  }

  public function enviarcpp_pago($idsol)
  {
    // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
    $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
    $consulta = $this->db->query("SELECT IF(idetapa=5, '1', '0') as res FROM solpagos WHERE idsolicitud =" . $idsol . "");
    if ($consulta->row()->res == '1') {
      $this->Solicitudes_cxp->update_factoraje_solicitud($idsol);
      log_sistema($this->session->userdata("inicio_sesion")['id'], $idsol, "SE GENERÓ PAGO DE FACTORAJE EN CXP");
      echo json_encode(array("data" => $consulta->row()->res));
    } else {
      echo json_encode(array("data" => $consulta->row()->res));
    }
  }


  public function provisionar_ok($idsol)
  {
    // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
    $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
    $consulta = $this->db->query("SELECT IF(idetapa=5, '1', '0') as res FROM solpagos WHERE idsolicitud =" . $idsol . "");
    if ($consulta->row()->res == '1') {
      $this->Solicitudes_cxp->update_con_factura($idsol);
      /** INICIO FECHA: 17-JUNIO-2025 | PANEL CP A USUARIOS ESPECIFICOS | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
      if (in_array($this->session->userdata("inicio_sesion")['id'], [2681, 2409])) {
        log_sistema($this->session->userdata("inicio_sesion")['id'], $idsol, $this->session->userdata("inicio_sesion")['depto'] . " ENVIÓ A PROVISIÓN");
      } else {
        log_sistema($this->session->userdata("inicio_sesion")['id'], $idsol, "CXP ENVIÓ A PROVISIÓN");
      }
      /** FIN FECHA: 17-JUNIO-2025 | PANEL CP A USUARIOS ESPECIFICOS | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
      echo json_encode(array("data" => $consulta->row()->res));
    } else {
      echo json_encode(array("data" => $consulta->row()->res));
    }
  }



  public function provisionar_mal($idsol)
  {
    // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
    $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
    $consulta = $this->db->query("SELECT IF(idetapa=5, '1', '0') as res FROM solpagos WHERE idsolicitud =" . $idsol . "");
    if ($consulta->row()->res == '1') {
      $this->Solicitudes_cxp->update_con_factura_sinprov($idsol);
      /** INICIO FECHA: 17-JUNIO-2025 | PANEL CP A USUARIOS ESPECIFICOS | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
      if (in_array($this->session->userdata("inicio_sesion")['id'], [2681, 2409])) {
        log_sistema($this->session->userdata("inicio_sesion")['id'], $idsol, $this->session->userdata("inicio_sesion")['depto'] . " ACEPTO Y NO ENVIÓ A PROVISIÓN");
      } else {
        log_sistema($this->session->userdata("inicio_sesion")['id'], $idsol, "CXP ACEPTO Y NO ENVIÓ A PROVISIÓN");
      }
      /** FIN FECHA: 17-JUNIO-2025 | PANEL CP A USUARIOS ESPECIFICOS | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
      echo json_encode(array("data" => $consulta->row()->res));
    } else {
      echo json_encode(array("data" => $consulta->row()->res));
    }
  }



  public function enviar_pag_caja()
  {
    $resulta = FALSE;

    if (isset($_POST) && !empty($_POST)) {
      $this->load->model("Solicitudes_cxp");

      $idpago = $this->input->post("idpago");

      $resulta = $this->db->query("UPDATE autpagos_caja_chica SET estatus = 5 WHERE idpago = $idpago");
    }

    echo json_encode(array("resultado" => $resulta));
  }

  //ACEPTAMOS TODOS LOS PAGOS PARA MANDAR A DISPERSAR
  public function aceptocpp($idpago)
  {
    // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
    $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
    $this->load->model("Solicitudes_cxp");
    $this->Solicitudes_cxp->update_genero_txt($idpago);
    $consulta_pagos = $this->db->query("SELECT idsolicitud FROM autpagos where idpago IN ( $idpago )");
    if ($consulta_pagos->num_rows() > 0) {
      $consulta_pagos = $consulta_pagos->result_array();
      for ($i = 0; $i < count($consulta_pagos); $i++) {
        log_sistema($this->session->userdata("inicio_sesion")['id'], $consulta_pagos[$i]['idsolicitud'], "CXP ENVIÓ PAGO A DISPERSIÓN");
      }
    }
  }


  public function actualizar_caja_chica($a, $b, $c)
  {

    $json['resultado'] = FALSE;

    $this->load->model("Solicitudes_cxp");

    $formaPago = $this->input->post("tipoPago_chica");

    if ($this->input->post("idcuentas") != '') {
      $numProv = $this->input->post("idcuentas");
      $referencia = "N/A";
    } else {
      $numProv = 0;
      $referencia = $this->input->post("idcheque");
    }

    $this->db->query('UPDATE autpagos_caja_chica  SET estatus=1,formaPago="' . $formaPago . '",  tipoPago="' . $formaPago . '", idproveedor=' . $numProv . ', referencia="' . $referencia . '" WHERE idpago=' . $c . '');


    $verificar_serie = $this->db->query('SELECT serie from serie_cheques WHERE idNum  = 1');
    if ($verificar_serie->row()->serie == $referencia) {
      $this->db->query('UPDATE serie_cheques  SET serie=(serie+1)');
    }

    $json['resultado'] = TRUE;
    echo json_encode($json);
  }



  public function aceptocpp_OT($idpago)
  {
    // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
    $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
    $this->load->model("Solicitudes_cxp");
    $this->Solicitudes_cxp->update_genero_PDF($idpago);
    $consulta_pagos_otros = $this->db->query("SELECT idsolicitud FROM autpagos where idpago IN (" . $idpago . ")");

    if ($consulta_pagos_otros->num_rows() > 0) {
      $consulta_pagos_otros = $consulta_pagos_otros->result_array();

      for ($i = 0; $i < count($consulta_pagos_otros); $i++) {
        log_sistema($this->session->userdata("inicio_sesion")['id'], $consulta_pagos_otros[$i]['idsolicitud'], "CXP ENVIÓ PAGO A DISPERSIÓN");
      }
    } else {
      $consulta_pagos_otros = array();
    }
  }

  public function aceptocpp_fact()
  {
    $respuesta = array("resultado" => FALSE, "data" => null);
    if (isset($_POST) && !empty($_POST)) {

      $idsolicitudes = implode(",", $this->input->post("idfactorajes"));

      $respuesta["resultado"] = $this->Solicitudes_cxp->update_factoraje_solicitud($idsolicitudes);
      $respuesta["data"] = $respuesta["resultado"] ? $this->Solicitudes_cxp->get_solicitudes_nuevas_factoraje()->result_array() : null;
    }
    echo json_encode($respuesta);
  }


  public function enviar_da($idsol)
  {
    $this->load->model("Solicitudes_cxp");
    $this->Solicitudes_cxp->update_proceso3($idsol);
  }


  public function enviarcolapagos($idsol)
  {
    $this->load->model("Solicitudes_cxp");
    $this->Solicitudes_cxp->update_proceso3($idsol);
  }
  public function enviarcolapagos_transf($idpago)
  {
    $this->load->model("Solicitudes_cxp");
    $this->Solicitudes_cxp->update_proceso3_trans($idpago);
  }

  /*********************************************************/
  //COLOCAMOS EL PAGO NUEVAMENTE EN CURSO PARA SER AUTORIZADO
  //PAGO A PROVEEDOR
  public function regresarcolapagos_transf()
  {
    // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
    $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
    $resultado = FALSE;
    if (isset($_POST) && !empty($_POST)) {
      $this->load->model("Solicitudes_cxp");
      $resultado = $this->Solicitudes_cxp->update_proceso3_trans_back($this->input->post("idpago"));

      $consulta_espera =  $this->db->query("SELECT idsolicitud FROM autpagos where idpago = " . $this->input->post("idpago"));
      log_sistema($this->session->userdata('inicio_sesion')['id'],  $consulta_espera->row()->idsolicitud, "SE DESBLOQUEO LA ESPERA EL PAGO");
    }
    echo json_encode(array("resultado" => $resultado));
  }

  //PAGO DE CAJA CHICA
  public function regresarcolapagos_CHICA()
  {
    $resultado = FALSE;
    if (isset($_POST) && !empty($_POST)) {
      $this->load->model("Solicitudes_cxp");
      $resultado = $this->Solicitudes_cxp->update_chica_back($this->input->post("idpago"));
    }
    echo json_encode(array("resultado" => $resultado));
  }
  /*********************************************************/


  public function regresarcolapagos($idsol)
  {
    $this->load->model("Solicitudes_cxp");
    $this->Solicitudes_cxp->update_proceso3b($idsol);
  }

  public function revisar_cuentas_empresa($dato)
  {
    $this->load->model('Solicitudes_cxp');

    $datos = $this->Solicitudes_cxp->get_listado_cuentas($dato);
    $respuesta['data'] = array();

    if ($datos->num_rows() > 0) {
      foreach ($datos->result() as $row) {
        $respuesta['data'][] = array("idproveedor" => $row->idproveedor, "nombre" => $row->nombre, "cuenta" => $row->cuenta);
      }
    }

    echo json_encode($respuesta);
  }

  public function revisar_serie_cheque($idempresa)
  {
    $this->load->model("Solicitudes_cxp");
    $dat =  $this->Solicitudes_cxp->get_serie_cheque($idempresa)->result_array();
    echo json_encode(array("data" => $dat));
  }

  function cargar_tipo_cambio()
  {
    $respuesta = array(FALSE);
    if ($this->input->post("idautopago")) {

      $data = array(
        "tipoCambio" => $this->input->post("tipo_cambio")
      );

      $respuesta = array($this->Solicitudes_cxp->update_autoPago($this->input->post("idautopago"), $data));
    }
    echo json_encode($respuesta);
  }

  function cargar_interes_agregado()
  {
    $respuesta = array(FALSE);
    if ($this->input->post("idautopago")) {
      $data = array("interes" => $this->input->post("valor_interes"));
      $respuesta = array($this->Solicitudes_cxp->update_autoPago_interes($this->input->post("idautopago"), $data), $this->input->post("valor_interes"));
    }

    echo json_encode($respuesta);
  }

  public function lista_cta($valor)
  {
    echo json_encode($this->Solicitudes_cxp->get_Ctas_epago($valor)->result_array());
  }

  public function lista_cta_ch($valor)
  {
    echo json_encode($this->Solicitudes_cxp->get_Ctas_epago_ch($valor)->result_array());
  }

  public function getConsecutivo($valor)
  {
    echo json_encode($this->Solicitudes_cxp->get_Ctas_value_chques($valor)->result_array());
  }

  public function getConsecutivo_chica($valor)
  {
    echo json_encode($this->Solicitudes_cxp->get_Ctas_value_chques_chica($valor)->result_array());
  }
  //update_serie
  public function putReferencia()
  {
    // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
    $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
    $reponse = array("resultado" => FALSE);

    if (isset($_POST) && !empty($_POST)) {
      $this->load->model("Solicitudes_cxp");

      $base_datos = $this->input->post("base_datos") == 1 ? "autpagos" : "autpagos_caja_chica";

      $data = array(
        "estatus" => 13,
        "referencia" => $this->input->post("referencia"),
        "tipoPago" => $this->input->post("forma_pago")
      );

      $reponse = $this->Solicitudes_cxp->update_pagos($base_datos, $data, $this->input->post("numpago"));
    }
    echo json_encode($reponse);
  }

  public function putReferencia_programados()
  {
    // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
    $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
    $reponse = array("resultado" => FALSE);

    if (isset($_POST) && !empty($_POST)) {
      $this->load->model("Solicitudes_cxp");

      $base_datos = $this->input->post("base_datos") == 1 ? "autpagos" : "autpagos_caja_chica";

      $data = array(
        "referencia" => $this->input->post("referencia"),
        "tipoPago" => $this->input->post("forma_pago")
      );

      $reponse = $this->Solicitudes_cxp->update_pagos($base_datos, $data, $this->input->post("numpago"));
    }
    echo json_encode($reponse);
  }

  public function eliminar_pagoregresar_dg($idpago)
  {
    // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
    $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
    $this->load->model("Solicitudes_cxp");

    $consult = $this->db->query("SELECT idsolicitud FROM autpagos WHERE idpago = " . $idpago);
    $var = $consult->row()->idsolicitud;
    $consult2 = $this->db->query("SELECT count(*) as total FROM autpagos WHERE idsolicitud = " . $var);

    if ($consult2->row()->total == 1) {
      $this->db->query("UPDATE solpagos SET idetapa = 7 WHERE idsolicitud = " . $var);
      $this->db->query("DELETE FROM autpagos WHERE idpago = " . $idpago);
    }

    if ($consult2->row()->total >= 2) {
      $this->db->query("UPDATE solpagos SET idetapa = 9 WHERE idsolicitud = " . $var);
      $this->db->query("DELETE FROM autpagos WHERE idpago = " . $idpago);
    }

    log_sistema($this->session->userdata('inicio_sesion')['id'],  $consult->row()->idsolicitud, "SE ELIMINO EL PAGO EN CXP, REGRESO A DG");
  }

  public function aceptar_facturaje($idpago)
  {
    // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
    $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
    $this->load->model("Solicitudes_cxp");

    $this->db->query("UPDATE autpagos SET estatus = 1 WHERE idpago = " . $idpago);
  }

  public function eliminar_pago($idpago)
  {
    // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
    $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
    $this->load->model("Solicitudes_cxp");

    $consult = $this->db->query("SELECT * 
                                 FROM solpagos 
                                 WHERE idsolicitud IN (SELECT idsolicitud FROM autpagos WHERE idpago = ?)", [$idpago]);
    $var = $consult->row()->idsolicitud;
    $consult2 = $this->db->query("SELECT count(*) as total FROM autpagos WHERE idsolicitud = " . $var);

    if ($consult->row()->idproceso == 30) {
      $this->db->query("UPDATE solpagos SET idetapa = 30 WHERE idsolicitud = " . $var);
      $this->db->query("DELETE FROM autpagos WHERE idpago = " . $idpago);
      log_sistema($this->session->userdata('inicio_sesion')['id'],  $consult->row()->idsolicitud, "SE ELIMINO EL PAGO EN CXP");
      log_sistema($this->session->userdata("inicio_sesion")['id'], $consult->row()->idsolicitud, "SE CANCELÓ DESDE PANEL CXP");
    } else {
      if ($consult2->row()->total == 1) {
        $this->db->query("UPDATE solpagos SET idetapa = 30 WHERE idsolicitud = " . $var);
        $this->db->query("DELETE FROM autpagos WHERE idpago = " . $idpago);
      }

      if ($consult2->row()->total >= 2) {
        $this->db->query("UPDATE solpagos SET idetapa = 9 WHERE idsolicitud = " . $var);
        $this->db->query("DELETE FROM autpagos WHERE idpago = " . $idpago);
      }
      log_sistema($this->session->userdata('inicio_sesion')['id'],  $consult->row()->idsolicitud, "SE ELIMINO EL PAGO EN CXP");
    }
  }

  public function txtProveedores()
  {
    // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
    $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);

    if ($this->input->post("idpago") && !empty(json_decode($this->input->post("idpago")))) {
      $idpagoes = json_decode($this->input->post("idpago"));

      $idpagoes = implode(",", $idpagoes);

      $cuenta_banca = $this->input->post("cuenta_val");

      $datos_cuenta_banco =  $this->db->query("SELECT c.nodecta, e.abrev  FROM cuentas as c inner join empresas as e on e.idempresa = c.idempresa WHERE idcuenta = '" . $cuenta_banca . "'");
      $datos =  $this->db->query("SELECT solpagos.metoPago, solpagos.moneda, autpagos.tipoCambio, autpagos.idpago, empresas.idempresa, solpagos.idsolicitud,solpagos.idProveedor,solpagos.idproveedor, proveedores.email,proveedores.rfc, proveedores.tipocta, proveedores.cuenta, proveedores.nombre as prov, bancos.clvbanco,bancos.nombre, CONCAT(solpagos.folio,proveedores.nombre)  AS descri, solpagos.cantidad as total, proveedores.alias, autpagos.cantidad, empresas.abrev FROM solpagos INNER JOIN empresas ON empresas.idempresa=solpagos.idEmpresa INNER JOIN proveedores ON proveedores.idproveedor=solpagos.idProveedor LEFT JOIN bancos ON bancos.idbanco=proveedores.idbanco INNER JOIN autpagos ON autpagos.idsolicitud=solpagos.idsolicitud INNER JOIN usuarios ON solpagos.idusuario=usuarios.idusuario WHERE autpagos.estatus IN (0,11)  AND (solpagos.metoPago LIKE 'FACT BAJIO' or solpagos.metoPago LIKE 'FACT BANREGIO')  AND ((autpagos.tipoCambio <> '' AND solpagos.moneda != 'MXN') OR (solpagos.moneda = 'MXN')) AND autpagos.fecha_pago IS NULL AND idpago IN ( $idpagoes )");
      $cuenta_bancaria = $datos_cuenta_banco->row()->nodecta;

      if ($datos->num_rows() > 0) {

        $response = array();
        $response['resultado'] = FALSE;

        $empr = NULL;
        $totpagar = 0;
        $noarchivo = 0;
        $encabezado = "010000001" . date("Ymd") . str_pad($noarchivo, 3, "0", STR_PAD_LEFT) . chr(13) . chr(10);
        $ctaemi = str_pad($cuenta_bancaria, 20, "0", STR_PAD_LEFT);
        $mensaje = $encabezado;
        $lineas = NULL;
        $regist = NULL;
        $carperta = "UPLOADS/txtbancos/";

        $i = 2;
        foreach ($datos->result() as $row2) {
          if ($row2->moneda != 'MXN' && $row2->tipoCambio != '') {
            $new_valor = $row2->cantidad * $row2->tipoCambio;
            $new_cantidad =  number_format(str_replace(array(".", ","), array(",", "."), $new_valor), 2, ".", "");
          } else {
            $new_valor2 = $row2->cantidad;
            $new_cantidad =  number_format(str_replace(array(".", ","), array(",", "."), $new_valor2), 2, ".", "");
          }


          if ($row2->metoPago != 'FACT BAJIO') {
            $new_alias = "FACT BANREGIO";
            $new_tipocta = "03";
            $new_cuenta = "165994370021";
            $new_bconom = "BANREGIO";
            $new_clvbanco = "058";
          } else {
            $new_alias = "FINAN5608";
            $new_tipocta = "40";
            $new_cuenta = "030225066260102016";
            $new_bconom = "BAJIO";
            $new_clvbanco = "030";
          }

          $new_descri = substr($row2->descri, 0, 27);

          // echo $new_descri;


          $spi = $new_clvbanco == "030" ? "BCO" : "SPI";

          // $empr = $row2->abrev;
          $totpagar = $totpagar + $new_cantidad;

          $mensaje .= "02" . str_pad($i, 7, "0", STR_PAD_LEFT) . "01" . $ctaemi . "0140" . str_pad($new_clvbanco, 3, "0", STR_PAD_LEFT) . str_pad(str_replace(".", "", ($new_cantidad * 100)), 15, "0", STR_PAD_LEFT) . date("Ymd") . $spi . str_pad($new_tipocta, 2, "0", STR_PAD_LEFT) . str_pad($new_cuenta, 20, "0", STR_PAD_LEFT) . "000000000" . str_pad($new_alias, 15, " ", STR_PAD_RIGHT) . "000000000000000" . "FOLIO FACT " . str_pad(str_replace(array("/", "-", ".", ",", "_", " "), "", (TRIM($new_descri))), 29, " ", STR_PAD_RIGHT) . chr(13) . chr(10);



          $i++;
          $lineas = $i;
          $regist = $i - 2;
        }


        $nombre = "PAGOS_" . $datos_cuenta_banco->row()->abrev . "_" . date("d_m_Y_H_i") . ".txt";
        $nombre_archivo = $carperta . $nombre;

        $response['totpag'] = $totpagar;

        $mensaje .= "09" . str_pad($lineas, 7, "0", STR_PAD_LEFT) . str_pad($regist, 7, "0", STR_PAD_LEFT) . str_pad(str_replace(".", "", ($totpagar * 100)), 18, "0", STR_PAD_LEFT) . chr(13) . chr(10);



        if (file_exists($nombre_archivo)) {
          unlink($nombre_archivo);
        }

        if ($archivo = fopen($nombre_archivo, "a")) {
          $response['arch'] = fwrite($archivo, $mensaje) ? "ok" : "error";
          fclose($archivo);
        }

        $response['file'] = base_url("UPLOADS/txtbancos/" . $nombre);

        $this->db->query("UPDATE autpagos SET estatus = 11 where idpago in( $idpagoes )");
      } else {

        $response['resultado'] = TRUE;
        $response['mensaje'] = 'SIN PAGOS SELECCIONADOS';
      }
      echo json_encode($response);
    }
  }

  function cambiar_proveedor()
  {
    // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
    $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
    $resultado = array("resultado" => FALSE);

    if (isset($_POST) && !empty($_POST)) {

      $data = array(
        "idProveedor" => $this->input->post("idproveedor")
      );

      $dato_previo = $this->db->query("SELECT CONCAT(proveedores.nombre,' - ',proveedores.alias ) nombre_proveedor FROM proveedores WHERE proveedores.idproveedor IN ( SELECT solpagos.idProveedor FROM solpagos WHERE solpagos.idsolicitud = '" . $this->input->post("idsolicitud") . "' )");
      $this->Solicitudes_cxp->actualizar_solictud($this->input->post("idsolicitud"), $data);
      log_sistema($this->session->userdata("inicio_sesion")['id'], $this->input->post("idsolicitud"), "SE CAMBIADO AL PROVEEDOR " . $dato_previo->row()->nombre_proveedor . " DE ESTE REGISTRO.");

      $resultado = array("resultado" => true, "solicitud" => $this->Solicitudes_cxp->sol_individual($this->input->post("idsolicitud"))->result_array()[0]);
    }

    echo json_encode($resultado);
  }

  public function cambiar_cant_pprog()
  {
    $this->load->model("Solicitudes_cxp");
    $respuesta = $this->Solicitudes_cxp->cambiar_cant_pprogM($this->input->post());
    $msj = $respuesta ? "La solicitud $_POST[idsolicitud] ha sido actualizada " : "Ha ocurrido un error en el proceso o no se actualizó ningún dato";
    if ($respuesta) {
      /** INICIO FECHA: 17-JUNIO-2025 | PANEL CP A USUARIOS ESPECIFICOS | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
      if (in_array($this->session->userdata("inicio_sesion")['id'], [2681, 2409])) {
        log_sistema($this->session->userdata("inicio_sesion")['id'], $this->input->post("idsolicitud"), $this->session->userdata("inicio_sesion")['depto'] . " HA ACTUALIZADO EL PAGO PROGRAMADO");
      } else {
        log_sistema($this->session->userdata("inicio_sesion")['id'], $this->input->post("idsolicitud"), "HA ACTUALIZADO EL PAGO PROGRAMADO");
      }
      /** FIN FECHA: 17-JUNIO-2025 | PANEL CP A USUARIOS ESPECIFICOS | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
      chat($this->input->post("idsolicitud"), $this->input->post("observaciones"), $this->session->userdata("inicio_sesion")['id']);
    }
    $resultado = array("resultado" => $respuesta, "msj" => $msj);
    echo json_encode($resultado);
  }

  public function reg_plan_pagoprog()
  {
    $idusuario = $this->session->userdata("inicio_sesion")['id'];
    $resultado = false;
    $msj = "Ha ocurrido un error en el proceso, favor de reportarlo";
    if (isset($_FILES['plan_excel']) && $_FILES['plan_excel']['tmp_name']) {
      $post = $this->input->post();
      /**CON ESTO SE CARGARAN LOS ARCHIVOS DE EXCEL**/
      $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
      $reader->setReadDataOnly(true);
      $spreadsheet = $reader->load($_FILES['plan_excel']['tmp_name']);
      if ($spreadsheet->getActiveSheet()->getHighestRow() >= 2 && (($post["total_pagos"] - $post["pago_act"] + 1) == ($spreadsheet->getActiveSheet()->getHighestRow()) || $post["total_pagos"] == -1)) {
        $this->db->trans_begin();
        $this->Solicitudes_cxp->act_plan_prog(["estatus" => 0], "idautpago is null and idsolicitud=$post[idsolicitud]");
        for ($i = 2; $i <= $spreadsheet->getActiveSheet()->getHighestRow(); $i++) {
          if (!empty($spreadsheet->getActiveSheet()->getCell('A' . $i)) && !empty($spreadsheet->getActiveSheet()->getCell('B' . $i))) {
            $datos = [
              "idsolicitud" => $post["idsolicitud"],
              "fecha_plan" => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($spreadsheet->getActiveSheet()->getCell('A' . $i)->getValue())->format('Y-m-d H:i:s'),
              "cantidad" => floatval($spreadsheet->getActiveSheet()->getCell('B' . $i)->getValue()),
              "idusuario" => $idusuario,
              "estatus" => 1
            ];
            $resultado = $this->Solicitudes_cxp->reg_plan_prog($datos);
          } else {
            $msj = "Uno de los registros del plan no cumple con la estructura, se ha cancelado el proceso";
            $this->db->trans_rollback();
            break;
            $resultado = false;
          }
        }
        if ($resultado) {
          $this->db->trans_commit();
          /** INICIO FECHA: 17-JUNIO-2025 | PANEL CP A USUARIOS ESPECIFICOS | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
          if (in_array($this->session->userdata("inicio_sesion")['id'], [2681, 2409])) {
            log_sistema($idusuario, $post["idsolicitud"], $this->session->userdata("inicio_sesion")['depto'] . " HA REGISTRADO UN PLAN DE PAGOS");
          } else {
            log_sistema($idusuario, $post["idsolicitud"], "HA REGISTRADO UN PLAN DE PAGOS");
          }
          $msj = "Se ha agregado el plan de pagos correctamente";
        }
      } else {
        $msj = "El archivo no cuenta con la misma cantidad de pagos que requiere la solicitud seleccionada, verifíquelo";
      }
    }
    fin:
    $respuesta = array("resultado" => $resultado, "msj" => $msj);
    echo json_encode($respuesta);
  }

  //AGREGA REGISTROS NO DEDUCIBLES PARA TDC
  public function gGastoNodeducible()
  {
    $resultado = array("resultado" => FALSE);

    if ((isset($_POST) && !empty($_POST))) {

      $this->db->trans_begin();

      $data = array(
        $this->input->post('proyecto'),
        $this->input->post('responsable_cc'),
        $this->session->userdata("inicio_sesion")['id'],
        $this->session->userdata("inicio_sesion")['depto'],
        $this->input->post('total'),
        $this->input->post('moneda'),
        $this->input->post('solobs'),
        $this->input->post('fecha'),
        date("Y-m-d H:i:s"),
        date("Y-m-d H:i:s"),
        $this->input->post('responsable_cc')
      );

      $resultado = $this->Solicitudes_cxp->insertGastoNodeducible($data);
      log_sistema($this->session->userdata("inicio_sesion")['id'], $this->db->insert_id(), "SE HA CARGADO UN GASTO NO DEDUCIBLE TDC");

      if ($resultado === FALSE || $this->db->trans_status() === FALSE) {
        $this->db->trans_rollback();
        $resultado = array("resultado" => FALSE);
      } else {
        $this->db->trans_commit();
        $resultado = array("resultado" => TRUE);
      }
    }
    echo json_encode($resultado);
  }

  ////// Manda solicitudes a caja cerrada   //////////////
  public function cerrar_caja_sol()
  {

    // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
    $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);

    $solicitudes = explode(",", $_POST["ids"]);

    $dataUpdate = [];

    $this->db->db_debug = FALSE; //disable debugging for queries
    $this->db->trans_begin();

    foreach ($solicitudes as $sol) {
      $info_sol = $this->db->query("SELECT * FROM solpagos WHERE idsolicitud = ?", [$sol])->row();
      array_push($dataUpdate, [
        "idsolicitud" =>  $sol,
        "idetapa"    => 31,
        "idAutoriza" => $this->session->userdata("inicio_sesion")['id'],
        "fecha_autorizacion" => date("Y-m-d H:i:s")
      ]);
    }

    $this->db->update_batch("solpagos", $dataUpdate, "idsolicitud");
    //The first parameter will contain the table name, the second is an associative array of values, the third parameter is the where key.

    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $resultado = array("resultado" => FALSE);
    } else {
      $this->db->trans_commit();
      $resultado = array("resultado" => TRUE);
    }
    echo json_encode($resultado);

    //echo json_encode(["ids"=>$solicitudes, "dataUpdate"=>$dataUpdate]);

  }

  /*******************************************************/
  //SERVICIO DE PARA CONSULTAR NO DEDUUCIBLES REGISTROS DE CAJA CHICA PARA PAGO
  function ver_nuevas_caja_chica_no_deducibles()
  {
    $data = $this->Solicitudes_cxp->obtenerSolCajachicaNoDDLS();
    if (!empty($data)) {
      $data = $data->result_array();
      for ($i = 0; $i < count($data); $i++) {
        $data[$i]['pa'] = 0;
        $data[$i]['solicitudes'] = null;
        //$data[$i]['solicitudes'] = $this->Solicitudes_cxp->get_solicitudes_nuevas_caja_chica( $data[$i]['ID'] );
      }
    }
    echo json_encode(array("data" => $data));
  }
  /**  Cambiar de etapa 7 a 5 | FECHA: 05-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com>  **/
  public function cambiar_eapdg_rcpp()
  {
    // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
    $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
    $resultado = ["resultado" => false, "solicitud" => '', 'msj'];

    if (isset($_POST) && !empty($_POST)) {

      $observacionCambioEtapa = strtoupper($this->input->post("observacionCambioEtapa"));
      $idsolicitud = strtoupper($this->input->post("idsolicitud"));

      $q = $this->db->query("UPDATE solpagos SET idetapa = '5' WHERE (idsolicitud = ?)", [$idsolicitud]);

      if ($q) {
        log_sistema($this->session->userdata("inicio_sesion")['id'], $idsolicitud, "CXP CAMBIO SOLICITUD A ETAPA REVISIÓN CUENTAS POR PAGAR: " . $observacionCambioEtapa);
        $resultado["resultado"] = true;
        $resultado["solicitud"] = $idsolicitud;
      } else {
        $resultado["solicitud"] = $idsolicitud;
      }
    }
    echo json_encode($resultado);
  }
  /**  FIN Cambiar de etapa 7 a 5 | FECHA: 05-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com>  **/

  public function get_solicitudes_devolucion()
  {
    $this->load->model("Solicitudes_cxp");
    echo json_encode(array("data" => $this->Solicitudes_cxp->get_solicitudes_devolucion()->result_array()));
  }
  /**
   * Inicio
   * Fecha : 12-Agosto-2025 @author Efrain Martinez programador.analista38@ciudadmaderas.com
   * Función que permite cancelar las solicitudes desde el panel de solicitudes nuevas de CXP.
   */
  public function datosParaCancelar()
  {
    // FECHA : 12-Agosto-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
    // FECHA : 19-Agosto-2025 | @author Mahonri Javier programador.analista63@ciudadmaderas.com | Validacion de usuario y rol. unicos usuarios autorizados a cancelar 

    $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
    $json['resultado'] = FALSE;

    $idUsuario = (int)$this->session->userdata("inicio_sesion")['id'];
    $rol = $this->session->userdata("inicio_sesion")['rol'];



    if ((($idUsuario === 309 && $rol === "CP") || ($idUsuario === 257 && $rol === "SU"))) {
      if ($this->input->post("idsolicitud")) {

        $idsolicitud = $this->input->post("idsolicitud");
        $observacion = $this->input->post("observacionC");

        //Busca si la solicitud que se va a cancelar esta relacionada con otra factura
        $solr = $this->db->query("SELECT idfactura, idsolicitudr FROM facturas WHERE idsolicitudr LIKE '%$idsolicitud%' AND idsolicitud <> $idsolicitud");
        // Si esta relacionada con otra factura la elimina de la columna idsolicitudr y se elimina el registro de la tabla relacionar sol_factura.
        if ($solr->num_rows() > 0) {
          $facturaRelcacionadaSol = $solr->result_array();
          // como $data->sols_elim solo tiene un registro, lo forzamos a string
          $sols_elim = is_array($idsolicitud) ? $idsolicitud[0] : $idsolicitud;

          for ($i = 0; $i < count($facturaRelcacionadaSol); $i++) {
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
        $facturas = $this->db->query("SELECT idfactura FROM facturas WHERE idsolicitud = $idsolicitud");
        if ($facturas->num_rows() > 0) {
          $resultadoF = $facturas->result_array();
          // Si la solicitud ya tiene facturas cargadas se verifica en cada una si tienen otras solicitudes relacionadas, de ser asi las solicitudes relacionadas
          // se regresan a la etapa 10 si ya estaban en la etapa 11 y se realiza un registro a logs de cada una de las solicitudes relacionadas con la factura de la solicitud
          // que se va a cancelar.
          for ($i = 0; $i < count($resultadoF); $i++) {

            $solicitiudesRelacionadas = $this->db->query("SELECT idsolicitudr, uuid, idsolicitud FROM facturas WHERE idfactura = ? AND idsolicitudr IS NOT NULL AND idsolicitudr != ''", $resultadoF[$i]['idfactura'])->row();

            if ($solicitiudesRelacionadas) {
              $this->db->query("UPDATE solpagos SET idetapa = 10 WHERE idsolicitud IN ($solicitiudesRelacionadas->idsolicitudr) AND idetapa = 11");
              $this->Complemento_cxpModel->multiLogs("SE ELIMINÓ LA RELACIÓN QUE LA FACTURA CON FOLIO FISCAL: " . $solicitiudesRelacionadas->uuid . " TENÍA CON LA (S) SOLICITUD (ES): " . $solicitiudesRelacionadas->idsolicitudr, $solicitiudesRelacionadas->idsolicitud);
              $sols_elimR = is_array($solicitiudesRelacionadas->idsolicitud) ? $solicitiudesRelacionadas->idsolicitud[0] : $solicitiudesRelacionadas->idsolicitud;
              $solicitudR = explode(",", $solicitiudesRelacionadas->idsolicitudr);
              $sol_elimR = array_diff($solicitudR, [$sols_elimR]);
              $sol_a_actualizarR = implode(",", $sol_elimR);
              if ($sol_a_actualizarR) {
                $this->Complemento_cxpModel->multiLogs("SE ELIMINÓ LA SOLICITUD DE LA FACTURA CON FOLIO FISCAL: " . $solicitiudesRelacionadas->uuid, $sol_a_actualizarR);
              }
            }

            //Se cancela cada una de las facturas que estan realcionadas con la factura que se va a eliminar y se elimina la relación de la tabla sol_factura
            $this->db->query("UPDATE facturas SET tipo_factura = 0, uuid = SUBSTRING( uuid, 1, CHAR_LENGTH(uuid) - 1) WHERE idfactura = ?", $resultadoF[$i]['idfactura']);
            $this->db->query("DELETE FROM sol_factura WHERE idfactura = ?", $resultadoF[$i]['idfactura']);
          }
        }
        // Se actualiza la etapa de la solicitud a 30 y se realiza un registro en logs de este movimiento y se guardaq la observacion del porque se cancelo la solicitud.
        $this->db->query("UPDATE solpagos SET idetapa = 30 WHERE idsolicitud = $idsolicitud");
        chat($idsolicitud, $observacion, $this->session->userdata("inicio_sesion")["id"]);
        log_sistema($this->session->userdata("inicio_sesion")['id'], $this->input->post("idsolicitud"), "SE CANCELÓ POR CXP ¬¬");

        $json['resultado'] = TRUE;
      }
      echo json_encode($json);
    } 
  }
  /**
   * Fin 
   * Fecha : 12-Agosto-2025 @author Efrain Martines Muñoz programador.analista38@ciudadmaderas.com
   */
}
