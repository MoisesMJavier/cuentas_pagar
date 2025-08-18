<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*Validaciones que controlan el flujo de la gestion
 *  de las facturas que puede controlar Contabiliad */

class Contabilidad extends CI_Controller {
    
    function __construct(){
        parent::__construct();
        if( $this->session->userdata("inicio_sesion") && in_array( $this->session->userdata("inicio_sesion")['rol'], array( 'CT', 'CC', 'CE', 'CX' ) ))
            $this->load->model("M_Contabilidad");
        else
            redirect("Login", "refresh");
    }
    
    function index(){
        $this->load->view("v_Contabilidad");
    }
    
    function Devoluciones(){
        $this->load->view("v_devoluciones_conta"); 
    }


    function getFC(){
        echo json_encode( array( "data" => $this->M_Contabilidad->obtenerSol()->result_array() ) ); 
    }

   function getFP(){
      echo json_encode( array( "data" => $this->M_Contabilidad->obtenerSolP()));  
   }

   public function savpoliza(){

        $resultado = false;

        if( isset( $_POST ) && !empty( $_POST ) ){
            $this->load->model('M_Contabilidad');

            $data = array(
                "numpoliza"=> $this->input->post("folprov"),
                "idsolicitud"=> $this->input->post("id_sol"),
                "rutaArchivo"=> $this->input->post("url"),
                "idusuario" => $this->session->userdata('inicio_sesion')['id']
            );

            $resultado = $this->db->query("SELECT * FROM polizas_provision WHERE estatus = 1 AND idsolicitud='".$this->input->post("id_sol")."'")->num_rows() == 0;
            if( $resultado ){
                if( $this->M_Contabilidad->gdapoliza( $data ) )
                    log_sistema($this->session->userdata("inicio_sesion")['id'], $this->input->post("id_sol"), "SE HA PROVISIONADO LA SOLICITUD");
            }
        }

        echo json_encode( array( "resultado" => $resultado ) );
        
    }
      
      function revisarPoliza(){
          $nombrepoliza=$this->input->post('folprov');
          echo json_encode($this->M_Contabilidad->revisarNombrePoliza($nombrepoliza)->num_rows());
      }
      
      
   // public function savpolizaP(){
   //    $this->load->model('M_Contabilidad');
   //      $data=array(
   //          "numpoliza"=>strtoupper($_POST['folprov']),"idsol"=>$_POST['id_sol'],
   //          "idsolicitud"=> strtoupper($_POST['id_sol']),
   //          "idpago"=>strtoupper($_POST['id_pago'])
   //      );
   //      $datos=$this->M_Contabilidad->gdapolizaP($data);
   //      //chat($data['idsolicitud'],"SE A ECHO POLIZA DE PAGO",$this->session->userdata('inicio_sesion')['id']);
   // }

   public function UpdatePoliza(){
    $this->load->model('M_Contabilidad');
      $data=array(
          "numpoliza"=>strtoupper($_POST['folprov']),
          "idprovision"=>strtoupper($_POST['id_prov']),
          "rutaArchivo"=>strtoupper($_POST['url']),
          "idsolicitud"=>strtoupper($_POST['id_sol']),
          "idusuario" => $this->session->userdata('inicio_sesion')['id']
          
      );
      $datos=$this->M_Contabilidad->UpdatePoliza($data);
      //chat($data['idsolicitud'],"SE A ECHO POLIZA DE PAGO",$this->session->userdata('inicio_sesion')['id']);
 }
   
   function ProvisionDeclinada(){
      $id = $this->input->post('id_sol');
      $obs = $this->input->post('Obervacion');
      $this->M_Contabilidad->declProvision($id); 
      log_sistema($this->session->userdata("inicio_sesion")['id'], $this->input->post("id_sol"), "CONTABILIDAD HA RECHAZADO LA FACTURA SE SOLICITA REFACTURACION");
     // chat($id,"FACTURA RECHAZADA POR CONTABILIDAD",$this->session->userdata('inicio_sesion')['id']);
      chat($id,$obs,$this->session->userdata('inicio_sesion')['id']);
    }
    
    function HistorialProvision(){
        echo json_encode( array( "data" => $this->M_Contabilidad->HistorialProvisiones($this->input->post()))); 
        
    }

    public function EliminarProvison($idprovision){
        echo json_encode( array( "data" => $this->M_Contabilidad->EliminarProvison($idprovision)));         
    }
   
    public function CancelarProvison($idprovision){
        echo json_encode( array( "data" => $this->M_Contabilidad->CancelarProvison($idprovision)));         
    }

    public function GetProvison($idprovision){
        echo json_encode( array( "data" => $this->M_Contabilidad->GetProvison($idprovision)));         
    }
    
    public function ver_devXaut(){
        echo json_encode( array( "data" => $this->M_Contabilidad->get_solicitudes_autorizadas_area()->result_array() ));
    }

    public function ver_devautorizadas(){
        $this->load->model("Solicitudes_solicitante");
        echo json_encode( array( 
            "data" => $this->M_Contabilidad->ver_datos_historial()->result_array(), 
            "por_autorizar" => $this->Solicitudes_solicitante->solicitudes_formato()->num_rows() 
        ));
    }

 }



