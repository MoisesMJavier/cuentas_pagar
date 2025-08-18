<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once './scripts/JWT.php';
use Firebase\JWT\JWT; 

class Ventas extends CI_Controller {
    function __construct(){
        parent::__construct();
        if( !$this->session->userdata("inicio_sesion") || $this->session->userdata("inicio_sesion")['id'] != 2636 && !in_array($this->session->userdata("inicio_sesion")['rol'], array('DG')) ){ /** FECHA: 17-DICIEMBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
            redirect(base_url());
        }
    }

    public function index(){

        $time = time();
        $token = array(
            "iat" => $time, // Tiempo en que inició el token
            "exp" => $time + (24*60*60), // Tiempo en el que expirará el token (24 horas)
            "data" => array("id_rol" => 1, "id_usuario" => 1, "id" => '0', "username" => 'caja', "descripcion" => ''),
        );
        $token = JWT::encode($token, '571335_8549+3668_','HS256');
        
        /*
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://maderascrm.gphsis.com/Api/external_dashboard?tkn=$token",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_HTTPHEADER => [ "Authorization: $token" ],
            CURLOPT_POSTFIELDS => []
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        echo ($response);
        */
        redirect("https://maderascrm.gphsis.com/Api/external_dashboard?tkn=$token");
        
    }
}