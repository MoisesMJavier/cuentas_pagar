<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*Validaciones que controlan el flujo de la gestion
 *  de las facturas que puede controlar Dirreccion General*/

class Indicadores extends CI_Controller {
    
    function __construct(){
        parent::__construct();
        
        if( $this->session->userdata("inicio_sesion") && ($this->session->userdata("inicio_sesion")['rol'] == 'DA' || $this->session->userdata("inicio_sesion")['id'] == 1844 ) ){
            $this->load->model("Provedores_model");
        }else        
            redirect("Login", "refresh");
    }

    public function index(){
        $this->load->view("v_indicadores_proveedor");
    }

    public function total_adeudo_proveedores(){
        $permiso = FALSE;
        if( $this->session->userdata("inicio_sesion")['depto'] == "COMPRAS" ){
            $permiso = FALSE;
            if( $this->input->post("minoperacion") === "" ){
                $proveedores =  $this->Provedores_model->totalPagadoProv()->result_array();
                
            }else{
                $proveedores =  $this->Provedores_model->totalDeudaProveedor_year( $this->input->post("minoperacion"), $this->input->post("maxoperacion") )->result_array();
            }

            $totalpendiente = $this->Provedores_model->totalDeudaProveedor()->result_array();
            $permiso = true;
            
        }else{
            $proveedores =  $this->Provedores_model->totalPagadoProvDepto( $this->session->userdata("inicio_sesion")['depto'] )->result_array();
            $totalpendiente = $this->Provedores_model->totalDeudaProveedor()->result_array();
        }

        $proveedores = $this->procesar_totales_proveedor( $proveedores, $totalpendiente );
        

        echo json_encode( [ "data" => $proveedores, "operaciones" => $this->Provedores_model->year_operaciones()->result_array(), "permiso" => $permiso ] );
    }

    function procesar_totales_proveedor( $proveedores, $totalpendiente ){

        $i = 0;
        do{
            $c = 0;

            do{ 

                $idproveedores = explode( ",", $proveedores[$i]["idproveedor"] );

                if( isset( $totalpendiente[$c]["idProveedor"] ) && in_array( $totalpendiente[$c]["idProveedor"], $idproveedores ) ){

                    if( $proveedores[$i]["deuda"] == NULL ){
                        $proveedores[$i]["deuda"] = 0;
                    }

                    $proveedores[$i]["deuda"] += $totalpendiente[$c]["deuda"];
                    array_splice($totalpendiente, $c, 1);

                }else{
                    $c++;
                }

            }while( isset($totalpendiente[$c]) && $c < count( $totalpendiente ) );
            
            
            if( $proveedores[$i]["totalPagado"] == NULL && $proveedores[$i]["deuda"] == NULL  ){
            
                array_splice($proveedores, $i, 1);

            }else{
                $i++;
            }
            
        }while( isset($proveedores[$i]) && $i < count( $proveedores ) );

        return array_values($proveedores);

    }
}