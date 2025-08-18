<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Descargar_XML extends CI_Controller {

    function __construct(){
        parent::__construct();
        if( $this->session->userdata("inicio_sesion") && in_array( $this->session->userdata("inicio_sesion")['rol'], array( 'CT', 'CC', 'CP', 'CE', 'CX') ))
            array_map('unlink', glob( "./*.zip"));
        else
            redirect("Login", "refresh");

    }

    public function index(){
        $this->load->view("v_descarga_masiva");
    }

    public function descargar_zip(){
        if( isset($_POST) && !empty($_POST) ){
            $facturas_disponibles = array(); ;

            switch( $this->input->post("tipo_factura") ){
                case 1:
                    $facturas_disponibles = $this->db->query("SELECT facturas.nombre_archivo, empresas.abrev FROM facturas INNER JOIN solpagos ON solpagos.idsolicitud = facturas.idsolicitud INNER JOIN empresas ON solpagos.idEmpresa = empresas.idempresa WHERE solpagos.idEmpresa = '".$this->input->post("idempresa")."' AND facturas.feccrea BETWEEN '".$this->input->post("fecha_ini")." 00:00:00' AND '".$this->input->post("fecha_fin")." 23:59:59' AND facturas.tipo_factura = '1'");
                    break;
                case 2:
                    $facturas_disponibles = $this->db->query("SELECT facturas.nombre_archivo, empresas.abrev FROM facturas INNER JOIN solpagos ON solpagos.idsolicitud = facturas.idsolicitud INNER JOIN empresas ON solpagos.idEmpresa = empresas.idempresa WHERE ( solpagos.caja_chica IS NULL OR solpagos.caja_chica != 1 ) AND solpagos.idsolicitud NOT IN (SELECT facturas.idsolicitud FROM facturas WHERE facturas.tipo_factura = 1) AND solpagos.idEmpresa = '".$this->input->post("idempresa")."' AND facturas.feccrea BETWEEN '".$this->input->post("fecha_ini")." 00:00:00' AND '".$this->input->post("fecha_fin")." 23:59:59' AND facturas.tipo_factura = '3'");
                    break;
                case 3:
                    $facturas_disponibles = $this->db->query("SELECT facturas.nombre_archivo, empresas.abrev FROM facturas INNER JOIN solpagos ON solpagos.idsolicitud = facturas.idsolicitud INNER JOIN empresas ON solpagos.idEmpresa = empresas.idempresa WHERE solpagos.caja_chica = 1 AND solpagos.idEmpresa = '".$this->input->post("idempresa")."' AND facturas.feccrea BETWEEN '".$this->input->post("fecha_ini")." 00:00:00' AND '".$this->input->post("fecha_fin")." 23:59:59' AND facturas.tipo_factura = '3'");
                    break;
                case 4:
                    //DESCARGA_GENERAL_COMPLEMENTOS
                    $facturas_disponibles = $this->db->query("SELECT facturas.nombre_archivo, empresas.abrev FROM facturas INNER JOIN solpagos ON solpagos.idsolicitud = facturas.idsolicitud INNER JOIN empresas ON solpagos.idEmpresa = empresas.idempresa INNER JOIN (SELECT * FROM facturas WHERE facturas.tipo_factura = 1) provisionar ON provisionar.idsolicitud = facturas.idsolicitud WHERE solpagos.idEmpresa = '".$this->input->post("idempresa")."' AND facturas.feccrea BETWEEN '".$this->input->post("fecha_ini")." 00:00:00' AND '".$this->input->post("fecha_fin")." 23:59:59' AND facturas.tipo_factura IN ( '2', '3' )");
                    //$facturas_disponibles = $this->db->query("SELECT facturas.nombre_archivo, empresas.abrev FROM facturas INNER JOIN solpagos ON solpagos.idsolicitud = facturas.idsolicitud INNER JOIN empresas ON solpagos.idEmpresa = empresas.idempresa WHERE solpagos.idEmpresa = '".$this->input->post("idempresa")."' AND facturas.feccrea BETWEEN '".$this->input->post("fecha_ini")." 00:00:00' AND '".$this->input->post("fecha_fin")." 23:59:59' AND facturas.idsolicitud IN ( SELECT facturas.idsolicitud FROM facturas WHERE facturas.tipo_factura = 1 ) AND facturas.tipo_factura = 3")
                    break;
                case 5:
                    $facturas_disponibles = $this->db->query("SELECT facturas.nombre_archivo, empresas.abrev FROM facturas INNER JOIN solpagos ON solpagos.idsolicitud = facturas.idsolicitud INNER JOIN empresas ON solpagos.idEmpresa = empresas.idempresa WHERE solpagos.caja_chica = 2 AND solpagos.idEmpresa = '".$this->input->post("idempresa")."' AND facturas.feccrea BETWEEN '".$this->input->post("fecha_ini")." 00:00:00' AND '".$this->input->post("fecha_fin")." 23:59:59'");
                    break;
                case 6:
                    $facturas_disponibles = $this->db->query("SELECT facturas.nombre_archivo, empresas.abrev FROM facturas INNER JOIN solpagos ON solpagos.idsolicitud = facturas.idsolicitud INNER JOIN empresas ON solpagos.idEmpresa = empresas.idempresa WHERE solpagos.caja_chica = 4 AND solpagos.idEmpresa = '".$this->input->post("idempresa")."' AND facturas.feccrea BETWEEN '".$this->input->post("fecha_ini")." 00:00:00' AND '".$this->input->post("fecha_fin")." 23:59:59'");
                    break;
            }

            if( $facturas_disponibles->num_rows() > 0 ){
                $this->load->library('zip');
                $nombre_documento = $facturas_disponibles->row()->abrev.date("YmdHis").'.zip';

                
                foreach( $facturas_disponibles->result() as $row ){
                    $this->zip->read_file( './UPLOADS/XMLS/'.$row->nombre_archivo );
                }
                
                /*
                $documentos_array = array();
                foreach( $facturas_disponibles->result() as $row ){
                    $documentos_array[$row->nombre_archivo] = './UPLOADS/XMLS/'.$row->nombre_archivo;
                }

                $facturas_disponibles = $documentos_array;
                unset( $documentos_array );
                $this->zip->add_data( $facturas_disponibles );
                */

                $this->zip->archive( './UPLOADS/'.$nombre_documento );
                $this->zip->download( $nombre_documento );

            }else{
                echo '<script type="text/javascript">
                alert("No hay facturas que descargar");
                window.location.href="../Descargar_XML";
                </script>';
            }
            
        }
        redirect("Login", "refresh");
    }
}