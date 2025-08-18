<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tarjetas_credito extends CI_Controller {

    function __construct(){
        parent::__construct();
        if( $this->session->userdata("inicio_sesion") && in_array( $this->session->userdata("inicio_sesion")['rol'], array( 'CP' ) ) || $this->session->userdata("inicio_sesion")["id"] == "257" )
            $this->load->model('Tarjeta_credito');
        else
            redirect("Login", "refresh");
    }

    /*Sección que se encargará de administrar las solicitudes de las tarjetas AMEX*/
    function index(){
        $this->load->view("v_catalogo_tarjetas");
    }

    function tabla_tarjetas(){
        echo json_encode( array("data"=>$this->Tarjeta_credito->getTarjetas()->result_array() ) );
    }

    function nueva_tarjeta(){
        $resultado = FALSE;

        if( !empty( $_POST ) && isset( $_POST ) ){

            $tarjeta_credito = array_keys ( $_POST );
            $data = array();
            foreach( $tarjeta_credito as $row ){
                if( $row != 'id' )
                $data[$row] = strtoupper( $this->input->post( $row ) );
            }
            
            $data["idusuario"] = $this->session->userdata("inicio_sesion")['id'];

            $resultado = $this->Tarjeta_credito->insertTarjeta( $data );
        }
        echo json_encode(  array( "resultado" => $resultado, "mensaje" => null, "data"=>$this->Tarjeta_credito->getTarjetas()->result_array() ) );        
    }

    function actualizar_tarjeta(){
        $resultado = FALSE;

        if( !empty( $_POST ) && isset( $_POST ) ){

            $tarjeta_credito = array_keys ( $_POST );
            $data = array();
            foreach( $tarjeta_credito as $row ){
                if( $row != 'id' )
                    $data[$row] = strtoupper($this->input->post( $row ));
            }
            
            $data["idupdate"] = $this->session->userdata("inicio_sesion")['id'];
            $data["fupdate"] = date("Y-m-d H:i:s");

            $resultado = $this->Tarjeta_credito->updateTarjeta( $data, $this->input->post( 'id' ) );
        }
        echo json_encode(  array( "resultado" => $resultado, "mensaje" => null, "data"=>$this->Tarjeta_credito->getTarjetas()->result_array() ) );  
    }
    /* ------------------------------------------------------------- */
}
?>