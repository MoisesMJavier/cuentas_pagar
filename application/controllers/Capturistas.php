<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Capturistas extends CI_Controller {

    function __construct(){
        parent::__construct();
        if( $this->session->userdata("inicio_sesion") && in_array( $this->session->userdata("inicio_sesion")['rol'], array( 'DA', 'AS', 'SB', 'SU' )))
            $this->load->model( array('Usuarios_model') );   
        else
            redirect("Login", "refresh");
        
    }

    public function index(){
        $this->load->view("v_capturistas");
    }

    function nuevo_capturista(){
        $respuesta = array( false );
        if( isset($_POST) && !empty( $_POST ) ){
            $data = array(
                "rol" => $this->input->post("rol_usuario"),
                "nickname" => $this->input->post("usuario"),
                "pass" => encriptar($this->input->post("password_usuario")),
                "apellidos" => limpiar_dato($this->input->post("apellido_usuario")),
                "nombres" => limpiar_dato($this->input->post("nombre_usuario")),
                "correo" => limpiar_dato($this->input->post("correo_usuario")),
                "estatus" => $this->input->post("estatus"),
                "depto" => $this->session->userdata("inicio_sesion")['depto'],
                "da" => $this->session->userdata("inicio_sesion")['id'],
                "creadopor" => $this->session->userdata("inicio_sesion")['id']
            );

            $respuesta = $this->Usuarios_model->insertar_nuevo( $data );
        }
        echo json_encode( array($respuesta) );
    }

    function editar_capturista(){
        $respuesta = array( false );
        if( isset($_POST) && !empty( $_POST ) ){
            $data = array(
                "rol" => $this->input->post("rol_usuario"),
                "nickname" => $this->input->post("usuario"),
                "pass" => encriptar($this->input->post("password_usuario")),
                "apellidos" => limpiar_dato($this->input->post("apellido_usuario")),
                "nombres" => limpiar_dato($this->input->post("nombre_usuario")),
                "correo" => limpiar_dato($this->input->post("correo_usuario")),
                "estatus" => $this->input->post("estatus")
            );

            $respuesta = $this->Usuarios_model->update_registro( $data, $this->input->post("idusuario") );
        }
        echo json_encode( array($respuesta) );
    }

    public function TablaCapturistas(){
        $query = $this->Usuarios_model->getCapturistas();
        
        if( $query->num_rows() ){
            $query = $query->result_array();
            for( $i = 0; $i < count( $query ); $i++ ){
                $query[$i]['pass'] = desencriptar( $query[$i]['pass'] );
            }
        }else{
            $query = array();
        }

        echo json_encode( array( "data" => $query ) );
    }
}