<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mproyectos extends CI_Model {

    function __construct(){
        parent::__construct();
    }

    function getProyectoAdministrar(){
        return $this->db->query("SELECT * FROM cat_proyecto_empresa WHERE idestatus IN ( 0, 1 )");
    }

    function getProyectoAdministrarActivo(){
        return $this->db->query("SELECT * FROM cat_proyecto_empresa WHERE idestatus IN ( 1 )");
    }

    function getProyectoEmpresa( $concepto ){
        return $this->db->query("SELECT * FROM cat_proyecto_empresa WHERE concepto = ?", [ $concepto ]);
    }

    function getEmpresas(){
        return $this->db->query("SELECT idempresa, nombre FROM empresas");
    }

    function uProyecto( $data, $proyecto ){
        $data["idusuario"] = $this->session->userdata("inicio_sesion")['id'];
        $data["fmodifica"] = date("Y-m-d H:i:s");
        return $this->db->update( "cat_proyecto_empresa", $data, "nproyecto_neo = '$proyecto'" );
    }

    function insertProyEmp( $proyecto, $concepto, $empresas ){
        $this->db->trans_begin();

        $data = [];

        for( $i = 0; $i < count( $empresas ); $i++ ){
          $data[] = [
            "idempresa" => $empresas[$i],
            "nproyecto_neo" => $concepto[$i],
            "concepto" => $proyecto
          ];
        }
        $this->db->delete( "cat_proyecto_empresa", "concepto = '$proyecto'" );
        $this->db->insert_batch( "cat_proyecto_empresa", $data );

        $resultado = $this->db->trans_status();
        if ( $resultado === FALSE){
            $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
        }
        return $resultado;
    }

    function insertProyecto( $bdtabla, $data ){

        $data["idusuario"] = $this->session->userdata("inicio_sesion")['id'];
        $data["fcreacion"] = date("Y-m-d H:i:s");

        return $this->db->insert( $bdtabla, $data);
    }

    function updateProyecto( $bdtabla, $data, $idproyecto ){
        return $this->db->update( $bdtabla, $data, "nproyecto_neo = '$idproyecto'");
    }
}