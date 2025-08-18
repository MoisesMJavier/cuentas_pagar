<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Alta_proveedores extends CI_Controller {

    function __construct(){
        parent::__construct();
        if( !isset($this->session->userdata('inicio_sesion')["im"]) && $this->session->userdata('inicio_sesion')["im"] != 3 )
            redirect("Login", "refresh");
        else
            $this->load->model('Provedores_model');
    }

    public function index(){
        $this->load->view("vista_provedores_alta");
    }
    
    public function tablaProveedoresAutorizar(){
        echo json_encode( array( "data" => $this->Provedores_model->Proveedores_Autorizar()->result_array() ) );
    }

    public function aceptar_proveedor(){
        
        $data = array( "resultado" => false );
        
        if( isset( $_POST ) && !empty( $_POST ) ){
            $data["resultado"] = $this->Provedores_model->actualizar_estatus( $this->input->post("idproveedor"), 1 );
        }

        echo json_encode( $data );
    }

    public function eliminar_proveedor($idproveedor){
        $resultado = $this->Provedores_model->eliminar_proveedor( $idproveedor ); /** FECHA: 28-MAYO-2025 | ELIMINAR PROVEEDOR RECHAZADO | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
        echo json_encode(['success' => $resultado]); /** FECHA: 28-MAYO-2025 | ELIMINAR PROVEEDOR RECHAZADO | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
    }
}


 