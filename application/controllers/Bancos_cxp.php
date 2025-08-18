<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bancos_cxp extends CI_Controller {

    function __construct(){
        parent::__construct();
        if( $this->session->userdata("inicio_sesion") && in_array( $this->session->userdata("inicio_sesion")['rol'], array( 'CP' ) ) || $this->session->userdata("inicio_sesion")["id"] == "257" )
            $this->load->model('Bancos_model');
        else
            redirect("Login", "refresh");
    }

    public function index(){
        $this->load->view("bancos_view");
    }


     public function tabla_autorizaciones(){
        $resultado = $this->Bancos_model->get_solicitudes_autorizadas_area();

        if( $resultado->num_rows() > 0 ){
            $resultado = $resultado->result_array();
            for( $i = 0; $i < count( $resultado ); $i++ ){
                $resultado[$i]['opciones'] = $this->opciones_autorizaciones( $resultado[$i]['idetapa'], $resultado[$i]['idusuario'] );

                /**TODAS LAS SOLICITUDES QUE SON CAJA CHICA SALTAN A DG Y SE CREA LA PROPIA ETAPA QUE ES GASTO CAJA CHICA PARA SEGUIMIENTO**/
                if( $resultado[$i]['idetapa'] === 3 && $resultado[$i]['caja_chica'] === 1 ){
                    $resultado[$i]['etapa'] = "Caja Chica";
                }

            }
        }else{
            $resultado = array();
        }

        echo json_encode(array( "data" => $resultado ));
    }



    public function opciones_autorizaciones( $estatus, $autor ){
        return "";
    }


    public function verbancos(){
        $this->load->model('Bancos_model');

        $datos = $this->Bancos_model->getbancos( NULL );
        $respuesta['data'] = array();

        if($datos->num_rows()>0){
            foreach($datos->result() as $row){
                $respuesta['data'][] = array( "idbanco"=>$row->idbanco, "nombre"=>$row->nombre );
            }
        }

        echo json_encode($respuesta);
    }




  public function vercuentas($data){
        $this->load->model('Bancos_model');

        $datos = $this->Bancos_model->get_cuenta_bancos($data);
        $respuesta['data'] = array();

        if($datos->num_rows()>0){
            foreach($datos->result() as $row){
                $respuesta['data'][] = array("idcuenta"=>$row->idcuenta, "nodecta"=>$row->nodecta,  "nombre"=>$row->nombre);
            }
        }

        echo json_encode($respuesta);
    }

 

 public function registrar_nuevo_banco(){
        $respuesta = array( false );
          if( isset($_POST) && !empty($_POST) ){
            $this->load->model("Bancos_model");  

            $data = array(  
                "nombre" => $this->input->post("nombreBanco"),
                "clvbanco" => $this->input->post("clvbanco") 
 
            );
    
            $respuesta = array( $this->Bancos_model->insertar_nuevo_banco( $data ) );  
         }

        echo json_encode($respuesta);        
    }







 public function registrar_nuevo_cuenta(){
        $respuesta = array(false);
            if(isset($_POST) && !empty($_POST) ){
            $this->load->model("Bancos_model");  

            $data = array(  
            "nombre"=>$this->input->post("nombreCta"),
            "tipocta"=>$this->input->post("tipocta"),
            "nodecta"=>$this->input->post("nodecta"),
            "idbanco"=>$this->input->post("idbanco"),
            "idempresa"=>$this->input->post("idempresa"),
            "activa"=>1,
            "fecreg"=>date('Y-m-d g:ia')
            );
    
            $respuesta = array( $this->Bancos_model->insertar_nuevo_cuenta($data));  
           }

        echo json_encode($respuesta);        
    }


 



  public function registrar_nuevo_empresa(){
        $respuesta = array(false);
            if(isset($_POST) && !empty($_POST) ){
            $this->load->model("Bancos_model");  

            $data = array(  
            "nombre"=>$this->input->post("nombreEmpresa"),
            "rfc"=>$this->input->post("rfc"),
            "abrev"=>$this->input->post("abrev"),
            "estatus"=>1,
            "rf_empresa" =>$this->input->post("rf_empresa"),
            "cp_empresa" =>$this->input->post("cp_empresa"),
            );
            $respuesta = array( $this->Bancos_model->insertar_nuevo_empresa($data));  
           }

        echo json_encode($respuesta);        
    }



  public function registrar_nuevo_serie(){
        $respuesta = array(false);
            if(isset($_POST) && !empty($_POST) ){
            $this->load->model("Bancos_model");  

            $data = array(  
            "idEmp"=>$this->input->post("idEmp"),
            "idCuenta"=>$this->input->post("cuenta_valor"),
            "serie"=>$this->input->post("serie"),
            "serieInicial"=>$this->input->post("serie")
            );
            $respuesta = array( $this->Bancos_model->insertar_nuevo_serie($data));  
           }

        echo json_encode($respuesta);        
    }



// public function registrar_nuevo_serie()  
//       { 

//        $empresa = $this->input->post("idEmp");
//        $cuenta = $this->input->post("cuenta_valor");

//         $this->load->model("Bancos_model");  
//         $data = array(  
//             "idEmp"=>$this->input->post("idEmp"),
//             "idCuenta"=>$this->input->post("cuenta_valor"),
//             "serie"=>$this->input->post("serie"),
//             "serieInicial"=>$this->input->post("serie")
//         );

 
//             $this->Bancos_model->insertar_nuevo_serie($data);  
//         redirect(base_url() . "index.php/Bancos_cxp"); 
 
// }






public function ver_datos_banco(){
     $this->load->model("Bancos_model");
   $dat =  $this->Bancos_model->get_bancos()->result_array();
    echo json_encode( array( "data" => $dat ));
    }


public function ver_datos_cheques_serie(){
     $this->load->model("Bancos_model");
   $dat = $this->Bancos_model->get_seriado()->result_array();
    echo json_encode( array( "data" => $dat ));
    }

    




public function ver_datos_cuentas(){
     $this->load->model("Bancos_model");
   $dat =  $this->Bancos_model->get_cuentas()->result_array();
    echo json_encode( array( "data" => $dat ));
    }


public function ver_datos_empresas(){
     $this->load->model("Bancos_model");
   $dat =  $this->Bancos_model->get_empresas()->result_array();
    echo json_encode( array( "data" => $dat ));
    }



public function ver_datosprovs2($idproveedor){

     $this->load->model("Bancos_model");

    $dat =  $this->Bancos_model->get_provs2($idproveedor)->result_array();
    echo json_encode($dat);
    }




    function updateBanco(){
        $respuesta = array( false );
        if( isset($_POST) && !empty( $_POST ) ){
        $data = array(
            "nombre"=>$this->input->post("nombreBanco"),
            "clvbanco" => $this->input->post("clvbanco") 
            
        );
       
        $respuesta = $this->Bancos_model->edita_banco( $data, $this->input->post('idbanco') );
        
       }
       echo json_encode( array($respuesta) );
    }



     function updateEmpresa(){
        $respuesta = array( false );
        if( isset($_POST) && !empty( $_POST ) ){
        $data = array(
            "nombre"=>$this->input->post("nombreEmpresa"),
            "rfc" => $this->input->post("rfc"),
            "abrev" => $this->input->post("abrev"),
            "rf_empresa"=>$this->input->post("rf_empresa"),
            "cp_empresa"=>$this->input->post("cp_empresa")
        );
       
        $respuesta = $this->Bancos_model->edita_empresa($data, $this->input->post('idempresa') );
        
       }
       echo json_encode( array($respuesta) );
    }


    
 
     
}