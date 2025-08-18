<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Movimientos_BB extends CI_Controller {

    function __construct(){
        parent::__construct();
        if( $this->session->userdata("inicio_sesion") && ( in_array( $this->session->userdata("inicio_sesion")['rol'], array( 'DP', 'SU' ) ) || ( $this->session->userdata("inicio_sesion")['rol'] == 'DA' && $this->session->userdata("inicio_sesion")['depto'] == 'SISTEMAS' ) ) )
            $this->load->model("MBanbajio");            
        else
            redirect("Login", "refresh");

    }

    public function index(){
        $this->load->view("Banbajio/edo_cuenta_bbv2");
    }

    public function saldos_bb(){
        echo json_encode( $this->MBanbajio->saldosBB( $this->input->post("mes_consulta"), $this->input->post("year_consult") ) );
    }

}