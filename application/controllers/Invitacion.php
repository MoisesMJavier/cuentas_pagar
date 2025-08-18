<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Invitacion extends CI_Controller {

	public function __construct(){
        parent::__construct();
        $this->load->model('Token');
    }

    public function getProveedores(){
        $this->load->model('Lista_dinamicas');
        echo json_encode(array($this->Lista_dinamicas->get_proveedores_lista()->result_array()));
    }

	public function Nuevo_proveedor(){
		$datos = $this->Token->vertkn( $this->uri->segment(3) );
		if( $datos->num_rows() > 0 && $datos->row()->activo == 1 ){
            
            $this->session->set_userdata('token', $this->uri->segment(3));
            $this->load->view( 'frmaddprov' );
        }else{
            $this->load->view( 'upsi_invitacion' );
        }
    }

    public function alta_proveedor(){

        $respuesta = array( FALSE );

        if( isset( $_POST ) && !empty( $_POST ) ){
            
            $this->load->model('Provedores_model');

            $token_generado = $this->Token->vertkn( $this->session->userdata('token') )->row();
            $data = array(
                "nombre"=> $this->input->post("nombreprov"),
                "alias"=> substr(str_replace(" ", "", limpiar_dato($this->input->post("nombreprov"))), 0, 10).str_pad(rand(0,999), 3, "0", STR_PAD_LEFT),
                "rfc"=> limpiar_dato( $this->input->post("rfc") ),
                "contacto" => limpiar_dato( $this->input->post("contacto") ),
                "email" => $this->input->post("correo"),
                "tipocta" => $this->input->post("tipcont"),
                "cuenta" => limpiar_dato( $this->input->post("cuenta") ),
                "sucursal" => $this->input->post("sucursal"),  
                "idbanco" => $this->input->post("nombanco"),
                "idby" => $token_generado->creadopor,
                "estatus" => 9,
                "fecadd"=> date('Y-m-d H:s:i'),
                "rf_proveedor" => $this->input->post("rf_proveedor"),
                "cp_proveedor" => $this->input->post("cp_proveedor") 
            );

            $respuesta = array( $this->Provedores_model->insertar_nuevo( $data ) );
            
            if( $respuesta[0] ){
                //$this->db->update("proveedores", array("alias" => limpiar_dato(substr( str_replace(" ","", $this->input->post("nombreprov") ), 0, 5)).$this->db->insert_id()), "idproveedor = '".$this->db->insert_id()."'");
                $this->Token->destkn( $this->session->userdata('token') );

                $this->load->library('email');
                $this->email->from('noreaply@ciudadmaderas.com', 'No responder');
                $this->email->to( $this->db->query("SELECT IFNULL(GROUP_CONCAT( usuarios.correo ), 'programador.analista3@ciudadmaderas.com') email FROM usuarios WHERE usuarios.impuesto = 3")->row()->email );
                $this->email->subject('SE HA INSCRITO UN NUEVO PROVEEDOR');

                $this->email->message('<!DOCTYPE html>
                    <html lang="en">
                    <head>
                        <meta charset="UTF-8">
                        <meta name="viewport" content="width=device-width, initial-scale=1.0">
                        <meta http-equiv="X-UA-Compatible" content="ie=edge">
                        <title>Document</title>
                        <style>
                            body{
                                width: 100%;
                                height: 100vh;
                                top: 0;
                                left: 0;
                                margin: 0;
                                padding: 0;
                                font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, Oxygen, Ubuntu, Cantarell, \'Open Sans\', \'Helvetica Neue\', sans-serif;
                            }
                            img{
                                width:50%;
                                height: auto;
                                margin: 1em 25%;
                                padding: 0;
                            }
                            p{
                                width: 50%;
                                height: auto;
                                margin: 2em 25%;
                                padding: 0;
                                text-align: justify;
                            }
                            @media only screen and (max-width: 1024px) {
                                img{
                                    width:75%;
                                    height: auto;
                                    margin: 1em 12.5%;
                                    padding: 0;
                                }
                                p{
                                    width: 75%;
                                    height: auto;
                                    margin: 2em 12.5%;
                                    padding: 0;
                                    text-align: justify;
                                }
                            }
                        </style>
                    </head>
                    <body>
                        <img src="https://www.ciudadmaderas.com/queretaro/assets/img/log_sistemao.png" alt="Ciudad Maderas" title="Ciudad Maderas">
                        <p>Se ha dado de alta "'.$this->input->post("nombreprov").'"como un nuevo proveedor. Es necesario que acepte para que este disponible en el catalogo.</p>
                    </body>
                    </html>');

                $this->email->send();
            }

        }

        echo json_encode( $respuesta );
    }

    public function listas_dinamicas(){
        $this->load->model(array('Provedores_model' , 'Lista_dinamicas' ));
        echo json_encode( array( $this->Provedores_model->getbancos()->result_array(), $this->Provedores_model->getsucursal()->result_array() , $this->Lista_dinamicas->get_cat_regimenfiscal()->result_array() ) );
    }

    public function checar_cuenta(){
        $resultado = TRUE;
        if( $this->input->post("cuenta_proveedor") ){
            $this->load->model('Provedores_model');
            $resultado = $this->Provedores_model->getCuenta( $this->input->post("cuenta_proveedor") )->num_rows() > 0 ? FALSE : TRUE;
            
        }

        echo json_encode( array( $resultado ) );
    }
}