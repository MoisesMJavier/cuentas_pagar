<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Invitado extends CI_Controller {
    function __construct(){
        parent::__construct();
        if( !$this->session->userdata("inicio_sesion")/* || in_array( $this->session->userdata("inicio_sesion")['rol'], array( 'FP' ))*/ )
            redirect("Login", "refresh");
        else
            $this->load->model(array('M_invitado'));
    }

    public function index(){
        if ($this->session->userdata("inicio_sesion")['rol'] == 'CS') {
            $this->load->view("vista_invitado_cs");
        }

        // if( in_array( $this->session->userdata("inicio_sesion")['rol'], array('DA', 'AS', 'CA', 'CJ' )) ){
        //     $this->load->view("v_historial_departamento");
        // }elseif( in_array( $this->session->userdata("inicio_sesion")['rol'], array('CX')) ){
        //     $this->load->view("v_historial_contabilidad_egresos");
        // }else{
        //    if( in_array( $this->session->userdata("inicio_sesion")['rol'], array( 'CP' )) || $this->session->userdata('inicio_sesion')["id"] == '257' ){
        //         $this->load->view("v_historial_cp");
        //     }else{
        //         $this->load->view("v_historial");
        //     }
        // }
    }

    public function TablaInvitadoSolicitudesA() {
        echo json_encode(array( "data" => $this->M_invitado->TablaInvitadoSolicitudesA()->result_array()));
    }

    public function TablaInvitadoSolicitudesB() {
        echo json_encode(array( "data" => $this->M_invitado->TablaInvitadoSolicitudesB()->result_array()));
    }

    ////////////// vista proveedor /////////////////////
    public function bloquear_proveedores(){
        $this->load->view("vista_proveedores_bloquear");
    }

    public function TablaBloquearProveedores(){
        echo json_encode(array("data" => $this->M_invitado->TablaBloquearProveedores()->result_array()));
    }

    public function reactivar_proveedor(){
        if( $this->input->post("idproveedor") ){
            echo json_encode( $this->M_invitado->cambiar_estatus_proveedor( $this->input->post("idproveedor"), 1, "" ) );
        }
    }

    public function bloquear_proveedor(){
        if( $this->input->post("idproveedor") ){
            echo json_encode( $this->M_invitado->cambiar_estatus_proveedor( $this->input->post("idproveedor"), 0, $this->input->post("observacion") ) );
        }
    }
    ////////////// fin vista proveedor /////////////////////
    
    public function historial_pagos(){
        $this->load->view("v_historial_transferencias");
    }

    public function historialcch(){
        $this->load->view("vista_historial_cch");
    }

    public function factorajes(){
        $this->load->view("v_historial_factorajes");
    }

    public function p_programados(){
        $this->load->view("vista_programados_cxp");
    }
}
?>