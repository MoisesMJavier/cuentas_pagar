<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Historial_pagos extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model(array('m_historial'));
    }

    public function index(){
        $this->load->view("v_historial_pagos");
    }

    public function TablaHistorial(){
        echo json_encode( array( "data" => $this->m_historial->getHistorialTabla()->result_array() ) );
    }
}