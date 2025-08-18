<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contratos extends CI_Controller {

    function __construct(){
        parent::__construct();
        if( ( in_array( $this->session->userdata('inicio_sesion')["rol"], array( 'DA' ) ) && in_array($this->session->userdata("inicio_sesion")['depto'] , array("CONSTRUCCION","COMPRAS") ) ) 
        || ( in_array( $this->session->userdata('inicio_sesion')["rol"], array( 'AS', 'CA' ) ) && in_array( $this->session->userdata("inicio_sesion")['id'], array( '99', '1844' ) ) ) )
            $this->load->model(array('M_historial', 'Consulta', 'Solicitudes_solicitante', 'Lista_dinamicas', 'm_contratos'));
        else
            redirect("Login", "refresh"); 
    }

    public function index(){
        $this->load->view("v_contratos");
    }

    public function add_contrato(){
        $resultado = FALSE;

        if( isset( $_POST ) && !empty( $_POST ) && $this->input->post("idcontrato") ){
            $porcent = $this->input->post('porcentaje');
            $rfc =  $this->input->post('proveedor');
            $data = array(
                //"idproveedor" => $this->input->post('proveedor'),
                "rfc_proveedor" =>  $rfc,
                "nombre" => strtoupper($this->input->post('nombre_contrato')),
                "cantidad" => $this->input->post('cantidad'),
                "estatus" => 1,
                "idcrea" => $this->session->userdata("inicio_sesion")['id'],
                "p_intercambio" => $porcent
            );
            $resultado = $this->m_contratos->insertar_contrato( $data );

            if($porcent > 0){
                $consulta=$this->db->query("SELECT * FROM cat_proveedor WHERE rfc_proveedor ='$rfc' AND porcentaje = 0");
                if($consulta->num_rows()==0){
                    $this->load->model(array('Provedores_model'));
                    $this->Provedores_model->update_porcent( $rfc);
                }
            }
            echo json_encode( array( "data" => $this->m_contratos->get_contratos()->result_array(), "resultado" => $resultado ) );
        }
    }

    public function updatePorcentProv(){
        $resultado = FALSE;
        $this->load->model(array('Provedores_model'));
        if( isset( $_POST ) && !empty( $_POST )){
            
                $prov = $this->input->post('prov_intercambio');
                $porcent = $this->input->post('porcentaje');
            
            // update porcentaje
           $resultado = $this->Provedores_model->update_porcent( $prov, $porcent );
           echo json_encode( array( "resultado" => $resultado ) );
        }
    }

    function tablaContratos(){
        echo json_encode( array( "data" => $this->m_contratos->get_contratos()->result_array() ) );
    }

    public function activar_contrato(){
        if($this->input->post("idcontrato")){
            $this->m_contratos->updateContrato( array( "estatus" => 1, "idmodifica" => $this->session->userdata("inicio_sesion")['id'], "fecha_mod" => date("Y-m-d H:i:s") ), $this->input->post("idcontrato"));
        }
    }

    public function desactivar_contrato(){
        if($this->input->post("idcontrato")){
            $this->m_contratos->updateContrato( array( "estatus" => 0, "idmodifica" => $this->session->userdata("inicio_sesion")['id'], "fecha_mod" => date("Y-m-d H:i:s") ), $this->input->post("idcontrato"));
        }
    }

    public function editar_contrato(){
        $resultado = FALSE;

        if( isset( $_POST ) && !empty( $_POST ) && $this->input->post("idcontrato") ){

            $data = array(
                //"idproveedor" => $this->input->post("proveedor"),
                "rfc_proveedor"=> $this->input->post("proveedor"),
                "nombre" => $this->input->post("nombre_contrato"),
                "cantidad" => $this->input->post("cantidad"),
                "idmodifica" => $this->session->userdata("inicio_sesion")['id'],
                "p_intercambio" => $this->input->post("porcentaje"),
                "fecha_mod" => date("Y-m-d H:i:s")
            );

            $resultado = $this->m_contratos->updateContrato( $data, $this->input->post("idcontrato") );
            
            echo json_encode( array( "data" => $this->m_contratos->get_contratos()->result_array(), "resultado" => $resultado ) );
        }
        
    }
}