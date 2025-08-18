<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Error404 extends CI_Controller { 

    public function __construct(){
        parent::__construct();
        if(!$this->session->userdata("inicio_sesion")){
            redirect("Login", "refresh");
        }
    }

    public function index(){
        $this->load->view("vista_error_404");
    }
}