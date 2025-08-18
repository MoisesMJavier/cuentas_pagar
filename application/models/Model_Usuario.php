<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_Usuario extends CI_Model {

    function __construct(){
        parent::__construct();
    } 

    function verificar_usuario(){
        $password = encriptar($this->input->post("login_password"));
        $usuario = $this->input->post("login_usuario");

        $query = $this->db->query("SELECT id_usuario,usuarios.nombre as nombre,usuarios.apellido_paterno, usuarios.apellido_materno, id_lider,id_sede, opcs_x_cats.nombre as rol, usuarios.id_rol AS idrol, usuarios.estatus,IFNULL(IF(imagen_perfil='','../img/default.png',imagen_perfil), '../img/default.png') AS imagen_perfil FROM usuarios INNER JOIN (SELECT * FROM opcs_x_cats WHERE id_catalogo =  1) opcs_x_cats ON usuarios.id_rol = opcs_x_cats.id_opcion WHERE usuarios.usuario COLLATE utf8_bin = '$usuario' AND usuarios.contrasena = '$password' AND usuarios.estatus in (1,2);");
        if($query->num_rows() > 0){
            $this->db->update("usuarios", array("sesion_activa" => 1), "id_usuario = '".$query->row()->id_usuario."'");
            $this->session->set_userdata("inicio_sesion",array(
                "id" => $query->row()->id_usuario,
                "nombres" => $query->row()->nombre,
                "apellidos" => $query->row()->apellido_paterno . ' ' . $query->row()->apellido_materno,
                "rol" => $query->row()->rol,
                "id_rol" => $query->row()->idrol,
                "lider" => $query->row()->id_lider,
                "sede" =>$query->row()->id_sede,
                "imagen" =>$query->row()->imagen_perfil,
                "pass" => $query->row()->estatus
            ));
        }else{
            $this->session->set_flashdata('error_usuario', '<div class="alert alert-danger text-danger" role="alert"><strong>¡ERROR!</strong>  El nombre de usuario o contraseña son incorrectos. </div>');
        }
    }

    function CerrarSesion(){
        return $this->db->update("usuarios", array("sesion_activa" => 0), "id_usuario = '".$this->session->userdata('inicio_sesion')["id"]."'");
    }

    function RecuperarContrasena($usuario){
        return $this->db->query("SELECT id_usuario,usuarios.nombre as nombre,usuarios.apellido_paterno, usuarios.apellido_materno, id_lider,id_sede, opcs_x_cats.nombre as rol, usuarios.id_rol AS idrol, usuarios.correo FROM usuarios 
        INNER JOIN (SELECT * FROM opcs_x_cats WHERE id_catalogo =  1) opcs_x_cats ON usuarios.id_rol = opcs_x_cats.id_opcion 
        WHERE (usuarios.usuario COLLATE utf8_bin = '$usuario' OR usuarios.correo = '$usuario') AND usuarios.estatus in (1,2);");
        
    }

    function RegistrarNuevoUsuario($data ){
        $this->db->insert("usuarios", $data);
        return $this->db->insert_id();
    }

    function EditarUsuario($data,$id_usuario){
        return $this->db->update("usuarios", $data, "id_usuario = '$id_usuario'");
    }

    function Eliminar($id_usuario){
        return $this->db->delete('usuarios', array('id_usuario' => $id_usuario)); 
    }

    function TablaUsuarios(){
        if($this->session->userdata('inicio_sesion')["rol"] == 'Soporte' ){
            return $this->db->query("SELECT id_usuario, opcs_x_cats.nombre as rol, CONCAT( apellido_paterno,' ',apellido_materno,' ',usuarios.nombre) AS nombre, telefono, usuarios.estatus, IFNULL(IF(imagen_perfil='','../img/default.png',imagen_perfil), '../img/default.png') AS src FROM usuarios INNER JOIN (SELECT * FROM opcs_x_cats  WHERE id_catalogo = 1) as opcs_x_cats ON usuarios.id_rol = opcs_x_cats.id_opcion ORDER BY nombre;");
        }else{
            return $this->db->query("SELECT id_usuario, opcs_x_cats.nombre as rol, CONCAT( apellido_paterno,' ',apellido_materno,' ',usuarios.nombre) AS nombre, telefono, usuarios.estatus, IFNULL(IF(imagen_perfil='','../img/default.png',imagen_perfil), '../img/default.png') AS src FROM usuarios INNER JOIN (SELECT * FROM opcs_x_cats  WHERE id_catalogo = 1) as opcs_x_cats ON usuarios.id_rol = opcs_x_cats.id_opcion WHERE id_lider = ".$this->session->userdata('inicio_sesion')['lider']." AND opcs_x_cats.nombre = 'Asesor' ORDER BY nombre;");
        }
    }

    function InformacionUsuario($id_usuario){
        return $this->db->query("SELECT IFNULL(IF(imagen_perfil='','../img/default.png',imagen_perfil), '../img/default.png') AS src,telefono,usuarios.nombre,apellido_paterno,apellido_materno,correo,usuario,contrasena, tiene_hijos as id_opcion, id_sede, id_lider, id_rol, usuarios.estatus as id_estatus, perfiles.nombre as perfil FROM usuarios INNER JOIN (SELECT * FROM opcs_x_cats WHERE id_catalogo =  1) perfiles ON usuarios.id_rol = perfiles.id_opcion WHERE usuarios.id_usuario = '$id_usuario';");
    }

    function VerficarUsuario($usuario){
        return $this->db->query("SELECT * FROM usuarios WHERE usuarios.usuario COLLATE utf8_bin = '$usuario'");
    }

    function ExisteUsuario($correo){
        return $this->db->query("SELECT * FROM usuarios WHERE correo = '$correo';");
    }
}
