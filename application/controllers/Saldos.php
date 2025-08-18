<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Saldos extends CI_Controller {

    function __construct(){
        parent::__construct();
        if( $this->session->userdata("inicio_sesion") && in_array( $this->session->userdata("inicio_sesion")['rol'], array( 'DP', 'SU' ) ) )
            $this->load->model('Saldos_model');
        else
            redirect("Login", "refresh");  
    }

    public function index(){
        $this->load->view("vista_saldos");
    }

    public function guardar_solicitud(){

        $resultado = array("resultado" => TRUE);

        if( (isset($_POST) && !empty($_POST)) ){
           

            $this->db->trans_begin();

             $data = array(
                "cantidad" => limpiar_dato($this->input->post('cantidad_saldo')),
                "fecha_saldo" => limpiar_dato($this->input->post('fecha_saldo')),
                "usuario" => $this->session->userdata("inicio_sesion")['id'],
                "tipo_saldo" => $this->input->post('movimiento_saldo'),
                "concepto" => $this->input->post('concepto'),
                "idEmpresa" => ( $this->input->post('empresa') != "NULL" ? $this->input->post('empresa') : NULL ),
                "idProyecto" => $this->input->post('proyecto'),
                "fechacreacion" => date("Y-m-d H:i:s"),
                "estatus" => 1
            );

            $this->Saldos_model->insertar_solicitud( $data );
            $resultado = TRUE;
 
            if ( $resultado === FALSE || $this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                $resultado = array("resultado" => FALSE);
            }else{
                $this->db->trans_commit();
                $resultado = array("resultado" => TRUE, "data" => $this->Saldos_model->get_datos_saldos()->result_array(), "saldos" => $this->Saldos_model->get_result_saldos()->result_array() );
            }
       }

        echo json_encode($resultado);
    
    }



       public function editar_datos(){

        $resultado = array("resultado" => TRUE);

        if( (isset($_POST) && !empty($_POST)) ){
 
            $this->db->trans_begin();
 
            $id_saldo = $this->input->post('id_saldo');

            $data = array(
                "cantidad" => limpiar_dato($this->input->post('monto')),
                "idEmpresa" => limpiar_dato($this->input->post('empresa2')),
                "idProyecto" => limpiar_dato($this->input->post('proyecto2'))               
            );
 
            $this->Saldos_model->update_solicitud($data, $id_saldo);
            $resultado = TRUE;
 
            if ( $resultado === FALSE || $this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                $resultado = array("resultado" => FALSE);
            }else{
                $this->db->trans_commit();
                $resultado = array("resultado" => TRUE, "data" => $this->Saldos_model->get_datos_saldos()->result_array(), "saldos" => $this->Saldos_model->get_result_saldos()->result_array() );
            }
       }

        echo json_encode($resultado);
    
    }



    



    public function ver_datos(){
        echo json_encode( array( "data" => $this->Saldos_model->get_datos_saldos()->result_array(), "saldos" => $this->Saldos_model->get_result_saldos()->result_array() ));
    }


    //  public function historial_logs($dato){
    //       $resultado = array("data" => $this->Saldos_model->get_datos_logs($dato)->result_array());
    // }

            public function historial_logs($dato){
        //if(in_array( $depto, array( 'CPP' ) )){
            echo json_encode( $this->Saldos_model->get_datos_logs($dato) );
        //}   
    }
    





    


}