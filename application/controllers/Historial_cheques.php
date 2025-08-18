<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Historial_cheques extends CI_Controller {

    function __construct(){
        parent::__construct();
        if( $this->session->userdata("inicio_sesion") && in_array( $this->session->userdata("inicio_sesion")['rol'], array( 'CP', 'DP', 'SU', 'CX', 'CE', 'CT', 'CC' )))
            $this->load->model(array('M_historial')); 
        else
            redirect("Login", "refresh");
    }

    function index(){
        if( in_array( $this->session->userdata("inicio_sesion")['rol'], array( 'CP' ) ) || in_array( $this->session->userdata("inicio_sesion")['id'], array( '257' ) ) ){
            $this->load->view("v_historial_pagos");
        }else{
            $this->load->view("v_historial_pagos_historial");
        }
        
    }

    function tablaCheques(){
        echo json_encode( array( "data" => $this->M_historial->Chequesactivos()->result_array()) );
    }

    function tablaChequesCancelados(){
        echo json_encode( array( "data" =>  $this->M_historial->ChequesCancelados()->result_array() ));
    }
    function  tablaChequesCobrados(){
        echo json_encode( array( "data" =>  $this->M_historial->ChequesCobrados()->result_array() ));
    }
    
    function tablaAllCheques(){
          echo json_encode( array( "data" =>  $this->M_historial->allcheques()->result_array() ));
    }

    function editar_cheque(){
        $respuesta = array( FALSE );       
        if( isset( $_POST ) && !empty( $_POST ) ){
            $data = array(
                "idautpago" => $this->input->post("idpago"),
                "numCan" => $this->input->post("vieja_referencia"),
                "numRem" =>$this->input->post("serie_cheque"),
                "tipo" => $this->input->post("bd"),
                "observaciones" => $this->input->post("observacion"),
                "fecha_creacion" => date("Y-m-d h:i:s"),
                "idUsuario" => $this->session->userdata("inicio_sesion")['id']
            );

            $this->M_historial->update_referencia($this->input->post("bd"),$this->input->post("serie_cheque"),$this->input->post("idpago"),$this->input->post("cuenta_valor"));
            $this->M_historial->insertHC( $data );
            
            $resultado=true;
            if($this->input->post("bd") == 1){
                $idsolicitud = $this->M_historial->getidsolicitud(  $this->input->post("idpago"))->idsolicitud;
                log_sistema($this->session->userdata("inicio_sesion")['id'], $idsolicitud, "SE HA EDITADO EL NO. DE CHEQUE. REGRESA A DISPERSIÓN.");
            }else{
                $query=$this->db->query("select idsolicitud from autpagos_caja_chica where idpago=".$this->input->post("idpago"));
                $ids_sol= explode (",", $query->row()->idsolicitud);
                $comentarios=array();
                foreach ($ids_sol as $id){
                    array_push($comentarios, array(
                        "idsolicitud" => $id,
                        "tipomov" => "SE HA EDITADO EL NO. DE CHEQUE. REGRESA A DISPERSIÓN.",
                        "idusuario" => $this->session->userdata('inicio_sesion')['id'],
                        "fecha" => date("Y-m-d H:i:s")
                    ));
                }
                $resultado=$this->db->insert_batch( 'logs', $comentarios )>0;
            }
            $respuesta = array( $resultado );
        }
        echo json_encode( $respuesta );
    }




 function editar_cheque_DEFIN(){
        $respuesta = array( FALSE );       
        if( isset( $_POST ) && !empty( $_POST ) ){
            $data = array(
                "idautpago" => $this->input->post("idpago"),
                "numRem" =>"NA",
                "tipo" => $this->input->post("bd"),
                "observaciones" => $this->input->post("observacion"),
                "numCan" => $this->input->post("ref"),
                "fecha_creacion" => date("Y-m-d h:i:s"),
                "idUsuario" => $this->session->userdata("inicio_sesion")['id']
            );

            $this->M_historial->update_referencia_cancelar($this->input->post("bd"),$this->input->post("idpago"));
            $this->M_historial->insertHC( $data );

            $respuesta = array( TRUE );
        }
        echo json_encode( $respuesta );
    }

    //JUEGA CON LOS ESTATUS DEL SISTEMA 15 -> ENTREGADO 14 Y 14 -> COBRADO 16
    function aprovar_cheque(){
        // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
        $respuesta = array( FALSE );
        if( isset( $_POST ) && !empty( $_POST ) ){

            $bd = $this->input->post("bd") == 1 ? "autpagos" : "autpagos_caja_chica";
            
            if( $this->db->query("SELECT idsolicitud from $bd where idpago = ? AND estatus = ?", array( $this->input->post("idpago"), $this->input->post("estatus") ) )->num_rows() > 0 ){
                $this->db->trans_begin();
                $estatus = $this->input->post("estatus");
                if( $estatus == 15 ){
                    $data = array(
                        "estatus" => "14",
                        "fechaOpe" => date("Y-m-d H:i:s"),
                    );
                }else{
                    $data = array(
                        "estatus" => "16",
                        "fecha_pago" => date("Y-m-d", strtotime( str_replace( '/', '-', $this->input->post("fecha_movimiento") ) ) ),
                        "fecha_cobro" => date("Y-m-d", strtotime( str_replace( '/', '-', $this->input->post("fecha_movimiento") ) ) )
                    );
                }

                $this->M_historial->autCheque( $bd, $data, $this->input->post("idpago"), $this->input->post("estatus") );

                $ids_sol= explode (",", $this->db->query("SELECT idsolicitud from $bd where idpago = ?", array( $this->input->post("idpago") ) )->row()->idsolicitud );
                $comentarios = array();
                foreach ($ids_sol as $id){
                    array_push($comentarios, array(
                        "idsolicitud" => $id,
                        "tipomov" => ( $this->input->post("estatus") == 15 ? "SE HA ENTREGADO EL CHEQUE" : "SE HA MARCADO COMO COBRADO EL CHEQUE. CON FECHA DE COBRO ".$this->input->post("fecha_movimiento") ),
                        "idusuario" => $this->session->userdata('inicio_sesion')['id'],
                        "fecha" => date("Y-m-d H:i:s")
                    ));
                }

                $this->db->insert_batch( 'logs', $comentarios );

                //Metodo para buscar si el proveedor esta vinculado a otra sol activa y si status != 1, de no ser asi desactivar prov pasar a estatus 3
                if( $estatus == 14 ){
                    $noDeptos = array("TRASPASO","DEVOLUCIONES","CESION OOAM","RESCISION OOAM","DEVOLUCION OOAM","TRASPASO OOAM","DEVOLUCION DOM OOAM","INFORMATIVAS");
                    $depto_prov = $this->db->query("SELECT nomdepto, idProveedor, idproceso FROM solpagos  WHERE idsolicitud =? ",[$this->input->post("idSol")]);
                    if(  in_array($depto_prov->row()->nomdepto, $noDeptos)){
                        $sol_pend = $this->db->query("SELECT * FROM solpagos WHERE idetapa NOT IN (0,30,11) AND idProveedor = ? ",[$depto_prov->row()->idProveedor]);
                        $status =  $this->db->query("SELECT estatus FROM proveedores  WHERE idproveedor = ? ",[$depto_prov->row()->idProveedor])->row()->estatus;
                        if( $sol_pend->num_rows() == 0 && $status == 2 ){ 
                            /**
                             * FECHA : 01-JULIO-2025 | @author Efrain Martinez progamador.analista38@ciudadmaderas.com | SE AGREGA EL ID DEL USUARIO QUE REALIZO LA ACTUALIZACION
                             */
                            $this->db->query("UPDATE proveedores SET estatus = 3, idupdate = ?, fecha_update = now() WHERE idproveedor = ?",[$this->session->userdata("inicio_sesion")['id'],$depto_prov->row()->idProveedor]);
                        }
                    }

                    if($depto_prov->row()->idproceso == 30){
                        $parcialidades = $this->db->query("SELECT 
                            ap.idsolicitud, 
                            count(ap.idpago) pagos,
                            sum(ap.cantidad) pagado,
                            spa.numeroParcialidades,
                            sp.cantidad,
                            if(count(ap.idpago) = spa.numeroParcialidades, 'S', 'N') esUltimaParcialidad
                        FROM autpagos ap
                        LEFT JOIN solpagos sp on sp.idsolicitud = ap.idsolicitud
                        LEFT JOIN solicitudesParcialidades spa on spa.idsolicitud = ap.idsolicitud
                        WHERE estatus IN (16) AND ap.idsolicitud = ? ",[$this->input->post("idSol")]);
                        if($parcialidades->num_rows() > 0){
                            if($parcialidades->row()->esUltimaParcialidad == 'S')
                                $this->db->query("UPDATE solpagos SET idetapa = 11, idAutoriza = ?, fecha_autorizacion = ? WHERE idsolicitud = ?",[$this->session->userdata('inicio_sesion')['id'], date("Y-m-d H:i:s"), $this->input->post("idSol")]);
                            else
                                $this->db->query("UPDATE solpagos SET idetapa = 78, idAutoriza = ?, fecha_autorizacion = ? WHERE idsolicitud = ?",[$this->session->userdata('inicio_sesion')['id'], date("Y-m-d H:i:s"), $this->input->post("idSol")]);
                        } 
                    }
                }


                if ($this->db->trans_status() === FALSE){
                    $this->db->trans_rollback();
                    $resultado = FALSE;
                }else{
                    $this->db->trans_commit();
                    $resultado = TRUE;
                }

                $respuesta = array( $resultado );
            }else{
                $respuesta = array( TRUE );
            }
            
        }

        echo json_encode( $respuesta );
        
    }
    /********************************************************************/

    //EN CASO DE CAMBIAR LA FORMA DE PAGO DE CHEQUE A TEA.
    function regresar_cheque(){
        $respuesta = array( FALSE );
        if( isset( $_POST ) && !empty( $_POST ) ){
            $this->load->model(array( "Solicitudes_cxp", "M_historial" ));

            $this->db->trans_begin();
            
            $data = array(
                "idautpago" => $this->input->post("id_pago"),
                "numRem" =>"NA",
                "tipo" => 1,
                "observaciones" => "SE PAGARÁ AHORA EN TRASFERENCIA.",
                "numCan" => $this->input->post("referencia"),
                "fecha_creacion" => date("Y-m-d h:i:s"),
                "idUsuario" => $this->session->userdata("inicio_sesion")['id']
            );

            $this->M_historial->insertHC( $data );
            
            $respuesta = $this->Solicitudes_cxp->cambiarmetodoM($this->input->post("id_pago"),"TEA");
            
            if ( $respuesta === FALSE || $this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                $respuesta = FALSE;
            }else{
                $this->db->trans_commit();
                $respuesta = [TRUE];
            }
        }
        echo json_encode( $respuesta );
    }


    function regresar_cheque_definitivo(){
        $id =  $this->input->post("id_pago");
        $idSol = $this->input->post("id_Sol");
        echo json_encode($this->M_historial->cancelacion_definitiva($id,$idSol));
    }

    function agregar_observacion_cheque(){
        $id=$this->input->post("id");
        $observacion = $this->input->post("observacion_cheque");
        $data = array(
            "idsolicitud" =>$this->input->post("id"),
            "idUsuario" => $this->session->userdata("inicio_sesion")['id'],
            "obervacion" =>$this->input->post("observacion_cheque")
        );
        if($this->M_historial->search_cheque($id)->num_rows() > 0){
            echo json_encode($this->M_historial->update_observ_cheque( $id,$observacion ));
        }else{
            echo json_encode($this->M_historial->insert_observ_cheque($data));
        }
       
        
    }

    function registrar_cheque(){
        $respuesta = array( FALSE );       
        if( isset( $_POST ) && !empty( $_POST ) ){

            $data = array(
                "numRem" =>"NA",
                "idempresa" => $this->input->post("empresa"),
                "tipo" => 1,
                "observaciones" => "CHEQUE CANCELADO, INGRESADO POR EL CONTROL",
                "numCan" => $this->input->post("ncheque"),
                "cantidad" => '0',
                "fecha_creacion" => date("Y-m-d h:i:s"),
                "idUsuario" => $this->session->userdata("inicio_sesion")['id']
            );

            $this->M_historial->insertHC( $data );

            // switch( $this->input->post("estatus_ch") ){
            //     case '30':
            //         $data = array(
            //             "numRem" =>"NA",
            //             "idempresa" => $this->input->post("empresa"),
            //             "tipo" => 1,
            //             "observaciones" => "CHEQUE CANCELADO, INGRESADO POR EL CONTROL",
            //             "numCan" => $this->input->post("ncheque"),
            //             "cantidad" => '0',
            //             "fecha_creacion" => date("Y-m-d h:i:s"),
            //             "idUsuario" => $this->session->userdata("inicio_sesion")['id']
            //         );

            //         $this->M_historial->insertHC( $data );

            //         break;
            //     default:
            //         $data = array(
            //             "proyecto" => $this->input->post("proyecto"),
            //             "idEmpresa" => $this->input->post("empresa"),
            //             "idProveedor" =>$this->input->post("proveedor"),
            //             "idUsuario" => $this->session->userdata("inicio_sesion")['id'],
            //             "folio" => $this->input->post("ncheque"),
            //             "fechaCreacion" => date("Y-m-d h:i:s"),
            //             "cantidad" => $this->input->post("cantidad"),
            //             "moneda" => "MXN",
            //             "metoPago" => "ECHQ",
            //             "fecelab" => $this->input->post("fecha"),
            //             "idetapa" => "11",
            //             "nomdepto" => $this->session->userdata("inicio_sesion")['depto'],
            //             "idResponsable" => $this->session->userdata("inicio_sesion")['id'],
            //             "justificacion" => $this->input->post("solobs")
            //         );
        
            //         //$this->M_historial->update_referencia($this->input->post("bd"),$this->input->post("serie_cheque"),$this->input->post("idpago"),$this->input->post("cuenta_valor"));
            //         $idsolicitud = $this->M_historial->insertsolCh( $data );
        
            //         $data2 = array(
            //             "idsolicitud" => $idsolicitud,
            //             "estatus" => $this->input->post("estatus_ch"),
            //             "idrealiza" => 0,//$this->session->userdata("inicio_sesion")['id'],
            //             "fechaDis" => $this->input->post("fecha"),
            //             "cantidad" => $this->input->post("cantidad"),
            //             "referencia" => $this->input->post("ncheque")
            //         );
        
            //         $this->M_historial->insertautpCh( $data2 );
            //         break;

            // }

            $respuesta = array( TRUE );

        }
        echo json_encode( $respuesta );
    }
}