<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tarjeta_credito extends CI_Model {

    function __construct(){
        parent::__construct();
    }

    function getTarjetas(){
        return $this->db->query("SELECT * FROM listado_tdc ORDER BY fecha_umodificada");
    }

    function insertTarjeta( $data ){
        return $this->db->insert( "tcredito", $data );
    }

    function updateTarjeta( $data, $id ){
        return $this->db->update( "tcredito", $data, "idtcredito = $id" );
    }
}
?>