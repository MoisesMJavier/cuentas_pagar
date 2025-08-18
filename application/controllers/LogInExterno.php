<?php

use phpDocumentor\Reflection\Types\Object_;

    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
    header("Access-Control-Allow-Methods: POST");

    defined('BASEPATH') OR exit('No direct script access allowed');

class LogInExterno extends CI_Controller {

    var $json = FALSE;

    public function __construct(){
        parent::__construct();
        
        $this->load->model("Token");

        /*
        $data = (Object)[
            "param_user" => "soporte",
            "param_pass" => "Soporte1."
        ];

        echo $this->Token->base64url_encode( $this->Token->encryptCreds( json_encode( $data  ) ) );
        */

        if( $_SERVER['REQUEST_METHOD'] == 'POST' ){
            $this->json = file_get_contents('php://input');
            $this->json = $this->Token->decryptCreds( $this->json );
        }

        if( $_SERVER['REQUEST_METHOD'] == 'GET' && isset( $_GET["token"] ) ){
            $this->json = $this->Token->decryptCreds( $this->Token->base64url_decode( $_GET["token"] ) );
        }

        if( $this->json != FALSE )
            $this->json = json_decode( $this->json );

        if( $this->json === FALSE ){
            http_response_code(403);
            die();
        }
    }

    //INICIAR SESION EN LA APLICACIÓN
    public function index(){
        $data = $this->json;
        if( isset($data->param_user) && isset($data->param_pass) ){

            $this->load->model("Usuario");

            $_POST = [
                "login_password" => $data->param_pass,
                "login_usuario" => $data->param_user
            ];

            $this->Usuario->verificar_usuario();
            
            if( $this->session->userdata("inicio_sesion") ){
                redirect("Login");
            }
        }
        http_response_code(403);
        die();
    }

    //VALIDAR LAS CONTRASEÑAS COMPARTIDAS
    public function validar_credenciales(){

        $data = $this->json;

        if( isset($data->param_user) && isset($data->param_pass) ){

            $this->load->model("Usuario");

            $_POST = [
                "login_password" => $data->param_pass,
                "login_usuario" => $data->param_user
            ];

            $this->Usuario->verificar_usuario();
            
            if( $this->session->userdata("inicio_sesion") ){
                echo json_encode( [ 
                    "status" => 1, 
                    "msj" => "Ok"
                ] );
            }else{
                echo json_encode( [ 
                    "status" => -1, 
                    "msj" => "Usuario o contraseña invalidos"
                ] );
            }

            die();
        }else{
            echo json_encode( [ 
                "status" => -1, 
                "msj" => "Parametros invalidos"
            ] );
        }
    }
}