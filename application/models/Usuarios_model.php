<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Usuarios_model extends CI_Model {

  function __construct(){
    parent::__construct();
  }

  function getbancos(){
    return $this->db->query("SELECT * FROM bancos ORDER BY nombre");
  }
 
  function insertar_nuevo( $data ){  
    return $this->db->insert("usuarios", $data);
  }

  function update_registro( $data, $idusuario ){  
    return $this->db->update("usuarios", $data, "idusuario = '$idusuario'");
  }
  
  function getCapturistas(){
    return $this->db->query("SELECT * FROM usuarios WHERE usuarios.da = '".$this->session->userdata("inicio_sesion")['id']."'");
  }

  function getUsuario( $usuario ){
    return $this->db->query("SELECT * FROM usuarios WHERE usuarios.nickname = '$usuario' AND estatus != 3");
  }

  function getCorreo( $correo, $estatus = FALSE ){

    if( $estatus != FALSE )
      $where_estatus = " AND estatus = 1";

    return $this->db->query("SELECT * FROM usuarios WHERE usuarios.correo = '$correo' $where_estatus");
  }

  function get_users( $rol = FALSE ){

    $filtro = "";
    $filtro2 = "";
    $idusuarios_nopermitidos = ['2579', '2582', '2583', '2416'];
    if( $rol != FALSE ){
        if( $rol == 'CP' )
            $filtro = " AND rol IN ('DA', 'AS', 'CA')";
    }
    if(in_array( $this->session->userdata('inicio_sesion')["id"], $idusuarios_nopermitidos ) )
      $filtro2 = " AND idusuario NOT IN (2083,2585,98,2588)";
    
    return $this->db->query("SELECT 
    usuarios.idusuario, 
    IFNULL(roles.idrol, '-') rol,
    IFNULL(roles.descripcion, '-') rol_descripcion,
    usuarios.nickname, 
    usuarios.pass, 
    usuarios.apellidos, 
    usuarios.nombres, 
    usuarios.correo, 
    usuarios.depto, 
    usuarios.estatus, 
    usuarios.da, 
    usuarios.feccrea, 
    usuarios.creadopor, 
    usuarios.impuesto, 
    usuarios.sup, 
    usuarios.idmodifica, 
    usuarios.fecha_mod,
    udepto.iddepto, 
    autUsr.deptosAut, 
    autUsr.permiso 
    FROM (
      SELECT * FROM usuarios
      WHERE estatus IN ( 0, 1 ) AND rol NOT IN ('DP','DG','SU') $filtro2 $filtro
    ) usuarios
    LEFT JOIN ( SELECT idrol, descripcion FROM roles WHERE estatus = 1 ) roles ON roles.idrol = usuarios.rol
    LEFT JOIN ( SELECT idusuario, GROUP_CONCAT( usuario_depto.iddepartamento ) iddepto FROM usuario_depto GROUP BY idusuario ) udepto ON udepto.idusuario = usuarios.idusuario
    LEFT JOIN (SELECT idusuario, GROUP_CONCAT(iddepartamento ) deptosAut, permiso FROM usuario_aut_gastos where estatus = 1 GROUP BY idusuario) autUsr ON autUsr.idusuario = usuarios.idusuario
    ORDER BY usuarios.nombres, usuarios.apellidos");
    
    
  } 

  function get_provs2($idproveedor){
    return  $this->db->query('SELECT idproveedor, proveedores.nombre as nomp, rfc, domicilio, contacto, email, tels, tipocta, clabe, cuenta, nomplaza, sucursal, fecadd, bancos.nombre as nomba FROM proveedores INNER JOIN bancos ON proveedores.idbanco=bancos.idbanco WHERE idproveedor="'.$idproveedor.'"');
  }
  //LISTAO DE ROLES EN SISTEMA
  function get_roles(){
    return  $this->db->query("SELECT idrol, descripcion FROM roles");
  }
  //LISTADO DE ROLES CON SUS DEPARTAMENTOS ASIGNADOS
  function get_roles_depto(){
    return  $this->db->query("SELECT idrol, departamento FROM roles_departamentos rl
    CROSS JOIN departamentos dp ON dp.iddepartamentos = rl.iddepartamentos");
  }

  function edita_usuario( $data, $id ){
    //$this->db->update("solpagos", array( "nomdepto" => $data['depto'] ), "idusuario = '$id'");
    return $this->db->update("usuarios", $data, "idusuario = '$id'");
  }

  function borra_usuario($idusuario ) {
    $id = $this->session->userdata("inicio_sesion")['id'];
    return $this->db->query("UPDATE usuarios SET estatus = 3, idmodifica = $id, fecha_mod = current_timestamp() WHERE idusuario = $idusuario");
  }

  function insert_user_depto( $data, $idusuario ){
    $this->db->delete( "usuario_depto", "idusuario = $idusuario" );
    return $this->db->insert_batch( "usuario_depto", $data );
  }

  function insert_auttgasto($data){
    //return $this->db->insert('usuario_aut_gastos', $data);
    return $this->db->insert_batch( "usuario_aut_gastos", $data );
  }

  function del_depto($idusuario){
    return $this->db->delete("usuario_depto", ["idusuario"=>$idusuario]);
  }

  function updateSup($idusuario){
    return $this->db->update('usuarios', ['sup'=>null], "idusuario = ".$idusuario );
  }
}