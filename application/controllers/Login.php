<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {
  
  public function __construct(){
    parent::__construct();
    if( $this->session->userdata("inicio_sesion") )
      redirect("Home", "refresh");
    else
      $this->load->model('Usuario');
  }

  public function index(){
    $this->load->view('formulario_inicio_sesion');
  }

  public function verificar(){
    if(isset($_POST) && !empty($_POST)){
      $this->Usuario->verificar_usuario();
    }
    redirect("Login");
  }
}


 
  