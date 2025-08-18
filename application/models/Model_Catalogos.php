<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_Catalogos extends CI_Model {

    function __construct(){
        parent::__construct();
    }

    function ObtenerRoles(){
        return $this->db->query("SELECT id_opcion as value, opcs_x_cats.nombre as label FROM catalogos INNER JOIN opcs_x_cats ON opcs_x_cats.id_catalogo = catalogos.id_catalogo WHERE catalogos.nombre = 'Rol' AND catalogos.estatus = 1 AND opcs_x_cats.estatus = 1;");
    }

    function ObtenerGerentes(){
        return $this->db->query("SELECT id_usuario as value, CONCAT(nombre,' ', apellido_paterno, ' ',apellido_materno ) as label FROM usuarios WHERE estatus = 1 AND id_rol = 4;");
    }

    function ObtenerSedes(){
        return $this->db->query("SELECT id_sede as value, nombre as label FROM sedes WHERE estatus = 1;");
    }

    function ObtenerSN(){
        return $this->db->query("SELECT id_opcion as value, opcs_x_cats.nombre as label FROM catalogos INNER JOIN opcs_x_cats ON opcs_x_cats.id_catalogo = catalogos.id_catalogo WHERE catalogos.nombre = 'SN' AND catalogos.estatus = 1 AND opcs_x_cats.estatus = 1;");
    }

    function ObtenerEstatus(){
        return $this->db->query("SELECT id_opcion as value, opcs_x_cats.nombre as label FROM catalogos INNER JOIN opcs_x_cats ON opcs_x_cats.id_catalogo = catalogos.id_catalogo WHERE catalogos.nombre = 'Estatus' AND catalogos.estatus = 1 AND opcs_x_cats.estatus = 1;");
    }

    function ObtenerTipoClientes(){
        return $this->db->query("SELECT id_opcion as value, opcs_x_cats.nombre as label FROM catalogos INNER JOIN opcs_x_cats ON opcs_x_cats.id_catalogo = catalogos.id_catalogo WHERE catalogos.nombre = 'TipoCliente' AND catalogos.estatus = 1 AND opcs_x_cats.estatus = 1;");
    }

    function ObtenerTerritorios(){
        return $this->db->query("SELECT id_opcion as value, opcs_x_cats.nombre as label FROM catalogos INNER JOIN opcs_x_cats ON opcs_x_cats.id_catalogo = catalogos.id_catalogo WHERE catalogos.nombre = 'Territorio' AND catalogos.estatus = 1 AND opcs_x_cats.estatus = 1;");
    }

    function ObtenerZonas(){
        return $this->db->query("SELECT id_opcion as value, opcs_x_cats.nombre as label FROM catalogos INNER JOIN opcs_x_cats ON opcs_x_cats.id_catalogo = catalogos.id_catalogo WHERE catalogos.nombre = 'Zona' AND catalogos.estatus = 1 AND opcs_x_cats.estatus = 1;");
    }

    function ObtenerMedios(){
        return $this->db->query("SELECT id_opcion as value, opcs_x_cats.nombre as label FROM catalogos INNER JOIN opcs_x_cats ON opcs_x_cats.id_catalogo = catalogos.id_catalogo WHERE catalogos.nombre = 'MedioPublicitario' AND catalogos.estatus = 1 AND opcs_x_cats.estatus = 1;");
    }

    function ObtenerLugares(){
        return $this->db->query("SELECT id_opcion as value, opcs_x_cats.nombre as label FROM catalogos INNER JOIN opcs_x_cats ON opcs_x_cats.id_catalogo = catalogos.id_catalogo WHERE catalogos.nombre = 'Lugar' AND catalogos.estatus = 1 AND opcs_x_cats.estatus = 1;");
    }

    function ObtenerPersonalidadesJuridicas(){
        return $this->db->query("SELECT id_opcion as value, opcs_x_cats.nombre as label FROM catalogos INNER JOIN opcs_x_cats ON opcs_x_cats.id_catalogo = catalogos.id_catalogo WHERE catalogos.nombre = 'PersonalidadJuridica' AND catalogos.estatus = 1 AND opcs_x_cats.estatus = 1;");
    }

    function ObtenerNacionalidades(){
        return $this->db->query("SELECT id_opcion as value, opcs_x_cats.nombre as label FROM catalogos INNER JOIN opcs_x_cats ON opcs_x_cats.id_catalogo = catalogos.id_catalogo WHERE catalogos.nombre = 'Nacionalidad' AND catalogos.estatus = 1 AND opcs_x_cats.estatus = 1;");
    }

    function ObtenerLider($info){
        switch($info->tipo->value){
            case '6': // asistente gerente
            case '7': // asesor ->  gerente
                return $this->db->query("SELECT id_usuario AS value, CONCAT( apellido_paterno,' ',apellido_materno,' ',nombre) as label FROM usuarios WHERE id_rol = 3 AND  id_sede = ".$info->sede->value." AND estatus = 1");
                break;
            case '3': // gerente -> asistente subdirector
                return $this->db->query("SELECT id_usuario AS value, CONCAT( apellido_paterno,' ',apellido_materno,' ',nombre) as label FROM usuarios WHERE id_rol = 5 AND  id_sede = ".$info->sede->value." AND estatus = 1");
                break;
            case '5': // asistente subdirector -> subdirector
                return $this->db->query("SELECT id_usuario AS value, CONCAT( apellido_paterno,' ',apellido_materno,' ',nombre) as label FROM usuarios WHERE id_rol = 2 AND  id_sede = ".$info->sede->value." AND estatus = 1");
                break;
            case '2': // subdirector
            case '4': // asistente director -> director
                return $this->db->query("SELECT id_usuario AS value, CONCAT( apellido_paterno,' ',apellido_materno,' ',nombre) as label FROM usuarios WHERE id_rol = 1 AND  id_sede = ".$info->sede->value." AND estatus = 1");
                break;

        }        
    }

    function ObtenerTodasSedes(){
        return $this->db->query("SELECT id_sede, nombre, sedes.estatus, creador FROM sedes LEFT JOIN (SELECT id_usuario , CONCAT( apellido_paterno,' ',apellido_materno,' ',nombre) AS creador FROM usuarios) AS creadores ON creadores.id_usuario = sedes.creado_por;");
    }

    function ObtenerTodosMedios(){
        return $this->db->query("SELECT opcs_x_cats.id_opcion, opcs_x_cats.nombre, opcs_x_cats.estatus, creador FROM opcs_x_cats LEFT JOIN (SELECT id_usuario, CONCAT(apellido_paterno, ' ', apellido_materno, ' ', nombre) AS creador FROM usuarios) AS creadores ON opcs_x_cats.creado_por = creadores.id_usuario INNER JOIN catalogos ON catalogos.id_catalogo = opcs_x_cats.id_catalogo WHERE catalogos.nombre = 'MedioPublicitario';");
    }

    function ObtenerTodosLugares(){
        return $this->db->query("SELECT opcs_x_cats.id_opcion, opcs_x_cats.nombre, opcs_x_cats.estatus, creador FROM opcs_x_cats LEFT JOIN (SELECT id_usuario, CONCAT(apellido_paterno, ' ', apellido_materno, ' ', nombre) AS creador FROM usuarios) AS creadores ON opcs_x_cats.creado_por = creadores.id_usuario INNER JOIN catalogos ON catalogos.id_catalogo = opcs_x_cats.id_catalogo WHERE catalogos.nombre = 'Lugar';");
    }

    function ObtenerTodosTerritorios(){
        return $this->db->query("SELECT opcs_x_cats.id_opcion, opcs_x_cats.nombre, opcs_x_cats.estatus, creador FROM opcs_x_cats LEFT JOIN (SELECT id_usuario, CONCAT(apellido_paterno, ' ', apellido_materno, ' ', nombre) AS creador FROM usuarios) AS creadores ON opcs_x_cats.creado_por = creadores.id_usuario INNER JOIN catalogos ON catalogos.id_catalogo = opcs_x_cats.id_catalogo WHERE catalogos.nombre = 'Territorio';");
    }

    function Nuevo_id_opcion($id_catalogo){
        return $this->db->query("SELECT IFNULL(max(id_opcion)+1,1) AS new_id_option FROM opcs_x_cats WHERE id_catalogo = '$id_catalogo';")->row()->new_id_option;
    }

    function RegistrarNuevaSede($data){
        $this->db->insert("sedes", $data);
        return $this->db->insert_id();
    }

    function RegistrarNuevItem($data){
        $this->db->insert("opcs_x_cats", $data);
        return $this->db->insert_id();
    }

    function EditarSede($data,$id_sede){
        return $this->db->update("sedes", $data, "id_sede = '$id_sede'");
    }

    function EditarItem($data,$id_opcion,$id_catalogo){
        $this->db->where('id_opcion',$id_opcion);
        $this->db->where('id_catalogo',$id_catalogo);
        return $this->db->update('opcs_x_cats',$data);
    }

    function ValidarSede($value,$tipo){
        if($tipo == 1){
            return $this->db->query("SELECT * FROM sedes WHERE nombre = '$value'");
        }else{
            return $this->db->query("SELECT * FROM sedes WHERE abreviacion = '$value'");
        }
    }

    function ValidarItem($value,$id_catalogo){
        return $this->db->query("SELECT * FROM opcs_x_cats WHERE nombre = '$value' AND id_catalogo = '$id_catalogo'");        
    }
}