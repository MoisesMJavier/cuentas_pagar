<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_Clientes extends CI_Model {

    function __construct(){
        parent::__construct();
    }

    function RegistrarNuevoCliente($data ){
        $this->db->insert("clientes", $data);
        return $this->db->insert_id();
    }

    function EditarCliente($data,$id_cliente){
        return $this->db->update("clientes", $data, "id_cliente = '$id_cliente'");
    }

    function Eliminar($id_cliente){
        return $this->db->delete('clientes', array('id_cliente' => $id_cliente)); 
    }

    function TablaClientes($tipo){
        switch ($this->session->userdata("inicio_sesion")['rol']) {
            case 'Subdirector':
            case 'Asistente subdirector':
                return $this->db->query("SELECT id_cliente, cliente, clientes.telefono, CONCAT( apellido_paterno,' ',apellido_materno,' ',nombre) AS asesor, gerente, clientes.estatus FROM usuarios 
                                        INNER JOIN (SELECT id_cliente, CONCAT( apellido_paterno,' ',apellido_materno,' ',nombre) AS cliente, telefono, clientes.estatus, id_asesor, tipo, id_gerente, id_sede from clientes) as clientes on clientes.id_asesor = usuarios.id_usuario
                                        LEFT JOIN (SELECT id_usuario as id_gerente,  CONCAT( apellido_paterno,' ',apellido_materno,' ',nombre) AS gerente  FROM usuarios) AS gerente ON clientes.id_gerente = gerente.id_gerente
                                        WHERE clientes.id_sede = '".$this->session->userdata("inicio_sesion")['sede']."' AND clientes.tipo = '$tipo';");
                break;
            case 'Gerente':
                return $this->db->query("SELECT id_cliente, cliente, clientes.telefono, CONCAT( apellido_paterno,' ',apellido_materno,' ',nombre) AS asesor, gerente, clientes.estatus FROM usuarios 
                                        INNER JOIN (SELECT id_cliente, CONCAT( apellido_paterno,' ',apellido_materno,' ',nombre) AS cliente, telefono, clientes.estatus, id_asesor, tipo, id_gerente from clientes) as clientes on clientes.id_asesor = usuarios.id_usuario
                                        LEFT JOIN (SELECT id_usuario as id_gerente,  CONCAT( apellido_paterno,' ',apellido_materno,' ',nombre) AS gerente  FROM usuarios) AS gerente ON clientes.id_gerente = gerente.id_gerente
                                        WHERE clientes.id_gerente = '".$this->session->userdata("inicio_sesion")['id']."' AND clientes.tipo = '$tipo';");
                break;
            case 'Asistente gerente':
                return $this->db->query("SELECT id_cliente, cliente, clientes.telefono, CONCAT( apellido_paterno,' ',apellido_materno,' ',nombre) AS asesor, gerente, clientes.estatus FROM usuarios 
                                        INNER JOIN (SELECT id_cliente, CONCAT( apellido_paterno,' ',apellido_materno,' ',nombre) AS cliente, telefono, clientes.estatus, id_asesor, tipo, id_gerente from clientes) as clientes on clientes.id_asesor = usuarios.id_usuario
                                        LEFT JOIN (SELECT id_usuario as id_gerente,  CONCAT( apellido_paterno,' ',apellido_materno,' ',nombre) AS gerente  FROM usuarios) AS gerente ON clientes.id_gerente = gerente.id_gerente
                                        WHERE  clientes.id_gerente = '".$this->session->userdata("inicio_sesion")['lider']."' AND clientes.tipo = '$tipo';");
                break;
            case 'Asesor':
                return $this->db->query("SELECT id_cliente, cliente, clientes.telefono, CONCAT( apellido_paterno,' ',apellido_materno,' ',nombre) AS asesor, gerente, clientes.estatus FROM usuarios
                                        INNER JOIN (SELECT id_cliente, CONCAT( apellido_paterno,' ',apellido_materno,' ',nombre) AS cliente, telefono, clientes.estatus, id_asesor, tipo, id_gerente from clientes) as clientes on clientes.id_asesor = usuarios.id_usuario
                                        LEFT JOIN (SELECT id_usuario as id_gerente,  CONCAT( apellido_paterno,' ',apellido_materno,' ',nombre) AS gerente  FROM usuarios) AS gerente ON clientes.id_gerente = gerente.id_gerente
                                        WHERE id_usuario = '".$this->session->userdata("inicio_sesion")['id']."' AND clientes.tipo = '$tipo';");
                    break;
            case 'Director':
            case 'Asistente director':
            default: // ve todos los registros
                return $this->db->query("SELECT id_cliente, cliente, clientes.telefono, CONCAT( apellido_paterno,' ',apellido_materno,' ',nombre) AS asesor, gerente, clientes.estatus FROM usuarios
                                        INNER JOIN (SELECT id_cliente, CONCAT( apellido_paterno,' ',apellido_materno,' ',nombre) AS cliente, telefono, clientes.estatus, id_asesor, tipo, id_gerente from clientes) as clientes on clientes.id_asesor = usuarios.id_usuario
                                        LEFT JOIN (SELECT id_usuario as id_gerente,  CONCAT( apellido_paterno,' ',apellido_materno,' ',nombre) AS gerente  FROM usuarios) AS gerente ON clientes.id_gerente = gerente.id_gerente
                                        WHERE clientes.tipo = '$tipo';");
                break;
        }
        
    }

    function TablaProspectos(){
        return $this->db->query("SELECT id_cliente, opcs_x_cats.nombre as tipo, CONCAT( apellido_paterno,' ',apellido_materno,' ',nombre) AS nombre, telefono, clientes.estatus FROM clientes INNER JOIN (SELECT * FROM opcs_x_cats  WHERE id_catalogo = 8) as opcs_x_cats ON clientes.tipo = opcs_x_cats.id_opcion WHERE clientes.tipo = 0;");
    }

    function InformacionCliente($id_cliente){
        return $this->db->query("SELECT clientes.nombre,apellido_paterno,apellido_materno,rfc,curp,clave,correo,telefono,telefono_2,observaciones,facturable, personalidad_juridica as id_personalidad, tipo as  id_tipo, lugar_prospeccion as id_lugar,medio_publicitario as id_medio, medio_publicitario as id_territorio, zona_venta as id_zona, nacionalidad as id_nacionalidad FROM clientes WHERE clientes.id_cliente = '$id_cliente';");
    }

    function ExisteUsuario($usuario){
        return $this->db->query("SELECT id_cliente, CONCAT(nombre,' ',apellido_paterno,' ',apellido_materno) AS cliente, rfc, curp, correo, telefono, fecha_creacion, asesor FROM clientes 
                                LEFT JOIN (SELECT id_usuario, CONCAT(nombre,' ',apellido_paterno,' ',apellido_materno) AS asesor FROM usuarios) AS asesores ON asesores.id_usuario = clientes.creado_por 
                                WHERE (nombre LIKE '%".$usuario["nombre"]."%' AND apellido_paterno LIKE '%".$usuario["apellido_paterno"]."%' AND apellido_materno LIKE '%".$usuario["apellido_materno"]."%') 
                                OR rfc LIKE '%".$usuario["rfc"]."%' OR curp LIKE '%".$usuario["curp"]."%' OR correo LIKE '".$usuario["correo"]."' OR telefono LIKE '".$usuario["telefono"]."';");
    }

    function guardar_observacion($data){
        $this->db->insert("observaciones", $data);
        return $this->db->insert_id();
    }

    function VerInformacionCliente($id_cliente){
        return $this->db->query("SELECT id_cliente, CONCAT(apellido_paterno, ' ', apellido_materno, ' ', nombre) AS cliente, curp, rfc, telefono, telefono_2, correo, personalidad, nacionalidades.nacionalidad, lugar, metodo, plaza, asesor, telefono_asesor, gerente, telefono_gerente, CONCAT(creador, ' ', fecha_creacion) as creacion FROM clientes
                                INNER JOIN (SELECT id_opcion AS id_personalidad, nombre AS personalidad FROM opcs_x_cats WHERE id_catalogo LIKE 10) AS personalidades ON personalidades.id_personalidad = clientes.personalidad_juridica
                                INNER JOIN (SELECT id_opcion AS id_nacionalidad, nombre AS nacionalidad FROM opcs_x_cats WHERE id_catalogo LIKE 11) AS nacionalidades ON nacionalidades.id_nacionalidad = clientes.nacionalidad
                                INNER JOIN (SELECT id_opcion AS id_lugar, nombre AS lugar FROM opcs_x_cats WHERE id_catalogo LIKE 9) AS lugares ON lugares.id_lugar = clientes.lugar_prospeccion
                                INNER JOIN (SELECT id_opcion AS id_metodo, nombre AS metodo FROM opcs_x_cats WHERE id_catalogo LIKE 7) AS metodos ON metodos.id_metodo = clientes.medio_publicitario
                                INNER JOIN (SELECT id_opcion AS id_plaza, nombre AS plaza FROM opcs_x_cats WHERE id_catalogo LIKE 5) AS plazas ON plazas.id_plaza = clientes.territorio_venta
                                INNER JOIN (SELECT id_usuario AS id_asesor, CONCAT(apellido_paterno, ' ', apellido_materno, ' ', nombre) AS asesor, telefono AS telefono_asesor FROM usuarios) AS asesores ON asesores.id_asesor = clientes.id_asesor
                                LEFT JOIN (SELECT id_usuario AS id_gerente, CONCAT(apellido_paterno, ' ', apellido_materno, ' ', nombre) AS gerente, telefono AS telefono_gerente FROM usuarios) AS gerentes ON gerentes.id_gerente = clientes.id_gerente
                                LEFT JOIN (SELECT id_usuario AS id_creador, CONCAT(apellido_paterno, ' ', apellido_materno, ' ', nombre) AS creador FROM usuarios) AS creadores ON creadores.id_creador = clientes.creado_por
                                WHERE id_cliente = $id_cliente");
    }

    function VerComentarios($id_cliente){
        return $this->db->query("SELECT observacion, fecha_creacion, creador FROM observaciones
                                INNER JOIN (SELECT id_usuario AS id_creador, CONCAT(apellido_paterno, ' ', apellido_materno, ' ', nombre) AS creador FROM usuarios) AS creadores ON creadores.id_creador = observaciones.creado_por
                                WHERE id_cliente = $id_cliente");
    }
}