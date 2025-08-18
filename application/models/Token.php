<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Token extends CI_Model {

    function __construct(){
        parent::__construct();
    }

    function savtkn( $correo, $data ){
        $this->db->update( "tokenprov", array( "activo" => 0 ), "correo = '$correo'");
        return $this->db->insert( "tokenprov", $data );
    }

    function vertkn( $token ){
        return $this->db->query("SELECT * FROM tokenprov WHERE tokenprov.token = '$token'");
    }

    function invitacion( $token ){
        return $this->db->query("SELECT * FROM tokenprov WHERE tokenprov.token = '$token' AND tokenprov.activo = '1'");
    }
    
    function destkn( $tkn ){
        return $this->db->update("tokenprov", array( "activo" => 0, "fechausado" => date( "Y-m-d H:i:s" ) ), " token = '$tkn'" );
    }
}