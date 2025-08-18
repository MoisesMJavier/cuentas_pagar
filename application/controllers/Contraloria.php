<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*Validaciones que controlan el flujo de la gestion
 *  de las facturas que puede controlar Contabiliad */

class Contraloria extends CI_Controller {
    
    function __construct(){
        parent::__construct();
        if( $this->session->userdata("inicio_sesion") && ( in_array( $this->session->userdata("inicio_sesion")['rol'], array( 'CI' ) ) || in_array( $this->session->userdata("inicio_sesion")['id'], array( 46, 174 ) ) ) )
            $this->load->model("M_Contraloria");
        else
            redirect("Login", "refresh");
    }
    
    function index(){
        $this->load->view("vista_revisar_contraloria");
    }
    
    public function ver_datos(){
        echo json_encode( array( "data" => $this->M_Contraloria->get_solicitudes_autorizadas_area()->result_array() ));
    }

    public function ver_datos_historial(){
        echo json_encode( array( "data" => $this->M_Contraloria->ver_datos_historial()->result_array(), "direccion_contraloria" => in_array( $this->session->userdata("inicio_sesion")['id'] , array( 46, 174, 219 ) ) ) );
    }

    public function aceptar_devolucion(){
        $resultado["resultado"] = false;
        if( $this->input->post("idsolicitud") && $this->M_Contraloria->aceptar_devolucion( $this->input->post("idsolicitud") ) > 0 ){
            if( $this->input->post("comentario") ){
                chat( $this->input->post("idsolicitud"), $this->input->post("comentario"), $this->session->userdata("inicio_sesion")['id'] );
            }
            log_sistema($this->session->userdata("inicio_sesion")['id'], $this->input->post("idsolicitud"), "CONTRALORÍA HA ACEPTADO LA DEVOLUCIÓN");

            $resultado["resultado"] = true;
        }
        
        //$resultado["devoluciones"] = $this->M_Contraloria->get_solicitudes_autorizadas_area()->result_array();
        //$resultado["historial"] = $this->M_Contraloria->ver_datos_historial()->result_array();
        

        echo json_encode( $resultado );
    }

    public function aceptar_devolucion_contabilidad(){
        $resultado["resultado"] = false;
        if( $this->input->post("idsolicitud") && $this->M_Contraloria->aceptar_devolucion_contabilidad( $this->input->post("idsolicitud") ) > 0 ){
            log_sistema($this->session->userdata("inicio_sesion")['id'], $this->input->post("idsolicitud"), "VoBo POR DIRECCIÓN DE CONTRALORIA");
            $resultado["resultado"] = true;
        }
        
        //$resultado["devoluciones"] = $this->M_Contraloria->get_solicitudes_autorizadas_area()->result_array();
        $resultado["historial"] = $this->M_Contraloria->ver_datos_historial()->result_array();
        

        echo json_encode( $resultado );
    }

    public function regresar_devolucion(){
        $resultado["resultado"] = false;
        if( $this->input->post("idsolicitud") && $this->M_Contraloria->regresar_devolucion( $this->input->post("idsolicitud") ) > 0 ){
            chat( $this->input->post("idsolicitud"), $this->input->post("comentario"), $this->session->userdata("inicio_sesion")['id'] );
            log_sistema($this->session->userdata("inicio_sesion")['id'], $this->input->post("idsolicitud"), "CONTRALORÍA REGRESÓ LA DEVOLUCIÓN");

            $resultado["resultado"] = true;
        }

        //$resultado["devoluciones"] = $this->M_Contraloria->get_solicitudes_autorizadas_area()->result_array();

        echo json_encode( $resultado );

    } 
   
    
}



