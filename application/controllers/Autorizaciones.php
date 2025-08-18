<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Autorizaciones extends CI_Controller {

    function __construct(){
        parent::__construct();
        if( !$this->session->userdata("inicio_sesion")/* || in_array( $this->session->userdata("inicio_sesion")['rol'], array( 'FP' ))*/ )
            redirect("Login", "refresh");
        else
            $this->load->model(array('M_historial'));
    }

    public function index(){
        // if( in_array( $this->session->userdata("inicio_sesion")['rol'], array('DA', 'AS', 'CA', 'CJ' )) ){
        //     $this->load->view("v_historial_departamento");
        // }else if( in_array( $this->session->userdata("inicio_sesion")['rol'], array('CT', 'CC', "CX")) ){
        //     $this->load->view("v_historial_contabilidad_egresos");
        // }else if( in_array( $this->session->userdata("inicio_sesion")['rol'], array('AD','PV','SPV','CAD','CPV','GAD','CE', 'CC', 'CI','DIO', 'SAC', 'IOO', 'COO', 'AOO','PVM')) ){
        //     $this->load->view("v_historial_admon", [ "consulta" => "DEV" ]);
        // }else{
        //    if( in_array( $this->session->userdata("inicio_sesion")['rol'], array( 'CP', 'SO' )) || $this->session->userdata('inicio_sesion')["id"] == '257' ){
        //         $this->load->view("v_historial_cp");
        //     }else{
        //         $this->load->view("v_historial");
        //     }
        // }
    }

    /*
    public function devolucionesytraspasos(){
        if( in_array( $this->session->userdata("inicio_sesion")['rol'], array('AD','PV','CE', 'CC', "CX")) ){
            $this->load->view("v_historial_admon");
        }else{
            redirect("Login", "refresh");
        }
    }
    */
    /**HISTORIAL DE FACTORAJEES */

    public function historial_pagos(){
        // if( in_array( $this->session->userdata("inicio_sesion")['rol'], array('SU', 'DG', 'DP')) )
            $this->load->view("v_Solicitudes_autorizacion");
        // elseif( in_array( $this->session->userdata("inicio_sesion")['rol'], array('FP')) )
        //     $this->load->view("v_historial_transferencias_fp");
        // else
        //     $this->load->view("v_historial_transferencias");
    }
	
}
