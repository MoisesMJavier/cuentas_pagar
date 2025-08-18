<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class TipoCambio extends CI_Controller { 
    
    function __construct(){
        parent::__construct();
        if( $this->session->userdata("inicio_sesion") && in_array( $this->session->userdata("inicio_sesion")['rol'], array( 'SO' ) )  )
            $this->load->model(array("MTipoCambio"));
        else{
            redirect("Login", "refresh");
        }
    }
    
    public function index() {
        $this->load->view("v_TipoCambio");
    }
    
    public function getdata() {
        echo json_encode($this->MTipoCambio->getdataM()->result());
    }
    
    function acciones_serie(){
        if( isset( $_POST ) && !empty( $_POST ) ){

            if($this->input->post("accion") == "registrar"){
                $resultado= $this->MTipoCambio->inserta_serie( $this->input->post() );
            }else if($this->input->post("accion") == "editar"){
                $resultado= $this->MTipoCambio->edita_serie( $this->input->post() );
            }

            $respuesta= array("resultado"=>$resultado,"msj"=> $msj, "data"=>$data );
        }

        echo json_encode( $respuesta );
    }
}

