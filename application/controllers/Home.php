<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

    function __construct(){
        parent::__construct();
        if( !$this->session->userdata("inicio_sesion") )
            redirect("Login", "refresh");
    }

    public function index(){
        $this->load->view("pagina_bienvenido");
    }

    public function cerrar_sesion(){
        $this->session->sess_destroy();
        redirect(base_url());
    }

    public function cambiar_password(){
        $respuesta = FALSE;
        $msj = '';
        
        if( $this->input->post("input_password") ){
            
            $query = $this->db->query("SELECT * FROM usuarios WHERE usuarios.idusuario = '".$this->session->userdata("inicio_sesion")['id']."';");            
            if($query->num_rows() > 0){
                if($this->input->post("input_password") != $query->row()->nickname){
                    if($this->input->post("input_usuario")!= $query->row()->nickname && $this->input->post("input_usuario")!='')
                        $respuesta = $this->db->update( "usuarios", array("pass" => encriptar($this->input->post("input_password")) ,"nickname"=>$this->input->post("input_usuario")), "idusuario = '".$this->session->userdata("inicio_sesion")['id']."'" );
                    else
                        $respuesta = $this->db->update( "usuarios", array("pass" => encriptar($this->input->post("input_password")) ), "idusuario = '".$this->session->userdata("inicio_sesion")['id']."'" );
                    
                    $newdata = array(
                        "id" => $this->session->userdata("inicio_sesion")['id'],
                        "nombres" =>  $this->session->userdata("inicio_sesion")['nombres'],
                        "apellidos" =>  $this->session->userdata("inicio_sesion")['apellidos'],
                        "rol" => $this->session->userdata("inicio_sesion")['rol'],
                        "da" => $this->session->userdata("inicio_sesion")['da'],
                        "depto" => $this->session->userdata("inicio_sesion")['depto'],
                        "usuario" => $this->input->post("input_usuario"),
                        "pass" => 0
                    );
                    
                    $this->session->set_userdata("inicio_sesion", $newdata);
                    
                }
                else{
                    $msj = '1';
                }
            }
            else{
                $msj = '2';
            }
        }
        echo json_encode(  array('result' => $respuesta , 'msj' => $msj ) );
    }
}