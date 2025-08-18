<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dispersion extends CI_Controller {

    function __construct(){
        parent::__construct();
        if( !$this->session->userdata("inicio_sesion") && !in_array( $this->session->userdata("inicio_sesion")['rol'], array( 'DP' ) ) )
            redirect("Login", "refresh");
        else
            $this->load->model('Dispercion_model');
        
    }

    public function index(){
        $this->load->view("vista_dispercion");
    }

    //
    public function ver_datos(){
        $data['data'] = $this->Dispercion_model->get_solicitudes_autorizadas_area()->result_array();
        print_r(json_encode($data));
        // echo json_encode( array( "data" => $this->Dispercion_model->get_solicitudes_autorizadas_area()->result_array() ));
    }

    public function ver_datos_historial(){
        echo json_encode( array( "data" => $this->Dispercion_model->get_solicitudes_pagadas()->result_array() ));
    }

    public function ver_datos_historial_echq(){
        echo json_encode( array( "data" => $this->Dispercion_model->get_solicitudes_echq()->result_array() ));
    }
 



    public function datos_para_rechazo_dp(){
        // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
        $json['resultado'] = FALSE;
        if( $this->input->post("idpago")){
            $this->load->model("Dispercion_model");
    
            $idpago = $this->input->post("idpago");
            $observacion =  $this->input->post("observacion");
 
        $this->db->query('UPDATE autpagos SET estatus = 5 WHERE idpago = '.$idpago.'');
        $consulta_01 = $this->db->query("SELECT idsolicitud FROM autpagos WHERE idpago=$idpago");
    
             chat($consulta_01->row()->idsolicitud, $observacion, $this->session->userdata("inicio_sesion")["id"]); 
             $json['resultado'] = TRUE;
              
        }

        echo json_encode( $json );
    }

    public function datos_para_rechazo_dp_ch(){
        $json['resultado'] = FALSE;
        if( $this->input->post("idpago") ){
            $this->load->model("Dispercion_model");
    
            $idpago = $this->input->post("idpago");
            $observacion =  $this->input->post("observacion");
 
        $this->db->query('UPDATE autpagos_caja_chica SET estatus = 5 WHERE idpago = '.$idpago.'');

         
             $json['resultado'] = TRUE;
              
        }

        echo json_encode( $json );
    }

    //DISPERSAR EL PAGO PROVEEDOR / CAJA CHICA
    public function provisionardp(  ){

        $data["resultado"] = FALSE;
        // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
        if( isset( $_POST ) && !empty( $_POST ) ){
            $this->load->model("Dispercion_model");   
            $query=$this->db->update( ( $this->input->post("tabla") == 1 ? "autpagos" : "autpagos_caja_chica" ), array( "estatus" => 15, "fechaDis" => date("Y-m-d h:i:s") ), " idpago = '".$this->input->post("idpago")."'" );
            
            if( $this->input->post("tabla") == 1 ){
                $consulta_sol =  $this->db->query("SELECT idsolicitud FROM autpagos WHERE idpago = '".$this->input->post("idpago")."'");
                log_sistema($this->session->userdata("inicio_sesion")['id'], $consulta_sol->row()->idsolicitud, "HA DISPERSADO LA SOLICITUD");
            }else{

                if($query){
                    $query=$this->db->query("select idsolicitud from autpagos_caja_chica where idpago=".$this->input->post("idpago").";");
                    $ids_sol= explode (",", $query->row()->idsolicitud);
                    $comentarios=array();
                    foreach ($ids_sol as $id){
                        array_push($comentarios, array(
                            "idsolicitud" => $id,
                            "tipomov" => "SE HA DISPERSADO EL REEMBOLSO",
                            "idusuario" => $this->session->userdata('inicio_sesion')['id'],
                            "fecha" => date("Y-m-d H:i:s")
                        ));
                    }
                    $resultado = $this->db->insert_batch( 'logs', $comentarios ) > 0;
                }
            }

            //RECUPERA LOS DATOS CORRESPONDIENTE PARA LOS HISTORIALES DE DISPERCION.
            //$data["tabla"] = $this->input->post("forma_pago") == "ECHQ" ? $this->Dispercion_model->get_solicitudes_echq()->result_array() : $this->Dispercion_model->get_solicitudes_pagadas()->result_array();

            $data["resultado"] = TRUE;
        }

        echo json_encode( $data );
    } 




  public function regresar_cp_new($idpago, $tabla){
     // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
     $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
     $this->load->model("Dispercion_model");
 
     if($tabla=="1"){
      $this->db->query("UPDATE autpagos SET estatus = 1 WHERE idpago = ".$idpago);
     }
     if($tabla=="2"){
     $this->db->query("UPDATE autpagos_caja_chica SET estatus = 5 WHERE idpago = ".$idpago);

     }
   
 } 

    //REGRESA EL PAGO PARA SER NUEVAMENTE PROCESADA POR CUENTAS POR PAGAR
    public function rechazo_para_cuentas(){
        // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
        if( isset( $_POST ) && !empty( $_POST )){
            $this->load->model("Dispercion_model");
 
            if( $this->input->post("tabla") == 1 ){
                $this->db->query("UPDATE autpagos SET estatus = 11 WHERE idpago = '".$this->input->post("idpago")."'");
                $consulta_regreso =  $this->db->query("SELECT idsolicitud FROM autpagos WHERE idpago = '".$this->input->post("idpago")."'");
                log_sistema($this->session->userdata("inicio_sesion")['id'], $consulta_regreso->row()->idsolicitud, "DISPERSIÓN REGRESO EL PAGO A CXP");
            }else{
                $this->db->query("UPDATE autpagos_caja_chica SET estatus = 2 WHERE idpago = '".$this->input->post("idpago")."'");
            } 
        }  
    } 

    public function eliminar_pago($idpago ){
        // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
        $this->load->model("Dispercion_model");

        $consult = $this->db->query("SELECT idsolicitud FROM autpagos WHERE idpago = ".$idpago);

        $var = $consult->row()->idsolicitud;

        $consult2 = $this->db->query("SELECT count(*) as total FROM autpagos WHERE idsolicitud = ".$var);

        if ($consult2->row()->total==1) {
        $this->db->query("UPDATE solpagos SET idetapa = 30 WHERE idsolicitud = ".$var);
        $this->db->query("DELETE FROM autpagos WHERE idpago = ".$idpago);
        }

        if ($consult2->row()->total>=2) {
            $this->db->query("UPDATE solpagos SET idetapa = 9 WHERE idsolicitud = ".$var);
            $this->db->query("DELETE FROM autpagos WHERE idpago = ".$idpago);
        }

    log_sistema( $this->session->userdata('inicio_sesion')['id'],  $consult->row()->idsolicitud, "SE ELIMINO EL PAGO POR DP" );

 
    } 

    public function regresar_pago(){
        // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
        if( isset( $_POST ) && !empty( $_POST )){
            $this->load->model("Dispercion_model");
            
            if( $this->input->post("tabla") == 1 ){
                $this->db->query("UPDATE autpagos SET estatus = 1 WHERE idpago = '".$this->input->post("idpago")."'");
                $consulta_sol =  $this->db->query("SELECT idsolicitud FROM autpagos WHERE idpago = '".$this->input->post("idpago")."'");
                log_sistema($this->session->userdata("inicio_sesion")['id'], $consulta_sol->row()->idsolicitud, "SE REGRESÓ A ACTIVO EN DISPERSIÓN");
            }else{
                $this->db->query("UPDATE autpagos_caja_chica SET estatus = 5 WHERE idpago = '".$this->input->post("idpago")."'");
            }

        }   
    } 
 
    public function provisionardp_ch($idsol){
        $this->load->model("Dispercion_model");
        $this->Dispercion_model->update_proceso2_chica($idsol);
    } 


    public function eliminarpago($idsol){
        $this->load->model("Dispercion_model");
        $this->Dispercion_model->update_eliminar($idsol);
    } 
 
    public function regresarcolapagosdp(){
        // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
        if( isset( $_POST ) && !empty( $_POST )){
            $this->load->model("Dispercion_model");

            if( $this->input->post("tabla") == 1 ){
                $this->db->query("UPDATE autpagos SET fechacp = CURRENT_DATE(), estatus = CASE WHEN estatus = 25 THEN 1 ELSE 25 END WHERE idpago= '".$this->input->post("idpago")."'");
            }else{
                $this->db->query("UPDATE autpagos_caja_chica SET fechacp = CURRENT_DATE(), estatus = CASE WHEN estatus = 25 THEN 5 ELSE 25 END WHERE idpago= '".$this->input->post("idpago")."'");
            }
        }
    }


    function DispersionarTodo(){
        $json = json_decode($this->input->post('jsonProv'));
        
        foreach ($json as $row) {
            $this->provisionardp($row->idsolicitud, $row->idtabla);
        }

    }
 
    function EliminarTodo(){
        $json = json_decode($this->input->post('jsonProv'));
        
        foreach ($json as $row) {
            $this->eliminar_pago($row->idsolicitud);
        }

    }

    function RegresarColaTodo(){
        $json = json_decode($this->input->post('jsonProv'));
        
        foreach ($json as $row) {
            $this->regresarcolapagosdp($row->idsolicitud, $row->idtabla);
        }

    }

}