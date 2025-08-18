<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Usuario extends CI_Model {

    function __construct(){
        parent::__construct();
    }
 

    function verificar_usuario(){

        $igual = $this->input->post("login_password") == $this->input->post("login_usuario");
        $password = encriptar($this->input->post("login_password"));
        $usuario = $this->input->post("login_usuario");

        // $query = $this->db->query("SELECT * FROM usuarios WHERE usuarios.nickname COLLATE utf8_bin = '$usuario' AND usuarios.pass = '$password' AND usuarios.estatus = 1");
        $query = $this->db->query("SELECT us.*, tdc.idtitular
                                FROM usuarios AS us
                                LEFT JOIN tcredito AS tdc
                                    ON us.idusuario = tdc.idtitular
                                WHERE us.nickname COLLATE utf8_bin = ? AND us.pass = ? AND us.estatus = 1", [$usuario, $password]);
        $titularTDC = $query->result_array()[0]['depto'] == 'COMERCIALIZACION' && !empty($query->result_array()[0]['idtitular'])
            ? $query->row()->idusuario
            : null;
        if($query->num_rows() > 0){
            $this->session->set_userdata("inicio_sesion",array(
                "id" => $query->row()->idusuario,
                "nombres" => $query->row()->nombres,
                "apellidos" => $query->row()->apellidos,
                "rol" => $query->row()->rol,
                "da" => $query->row()->da,
                "depto" =>$query->row()->depto,
                "pass" => $igual,
                "im" => $query->row()->impuesto,
                "sup" => $query->row()->sup,
                "usuario" => $query->row()->nickname,
                "titularTDC" => $titularTDC == 2192 ? null : $titularTDC
            ));
        }else{
            $this->session->set_flashdata('error_usuario', '<div class="alert alert-danger text-danger" role="alert"><strong>¡ERROR!</strong> Usuario / Contraseña erroneos. </div>');
        }
    }
}
