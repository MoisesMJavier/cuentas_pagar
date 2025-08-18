<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Saldos_model extends CI_Model {

    function __construct(){
        parent::__construct();
    }


     function insertar_solicitud($data ){
        return $this->db->insert("saldos", $data);
        // return $this->db->insert_id();
    }
    function update_solicitud($data,$id_saldo){

       $this->db->query('INSERT INTO logs_saldos(idusuario, idsolicitud, tipo_mov, fecha) VALUES ('.$this->session->userdata("inicio_sesion")['id'].','.$id_saldo.',"EDITO EL REGISTRO","'.date("Y-m-d H:i:s").'")'); 

 
       // "usuario" => $this->session->userdata("inicio_sesion")['id'],
            // "fechacreacion" => date("Y-m-d H:i:s"),

        // return $this->db->update("saldos", $data,'id_saldo'= $id_saldo);
        $this->db->where('id_saldo', $id_saldo);
       return $this->db->update('saldos', $data);
        // return $this->db->insert_id();
    }

 
  function get_datos_saldos(){
    return $this->db->query('SELECT s.id_saldo, s.cantidad, IFNULL(e.abrev, "SIN DEFINIR") abrev, s.fecha_saldo, CONCAT(u.nombres," " ,u.apellidos) AS nomuser, s.fechacreacion, s.estatus, s.tipo_saldo, s.concepto, IFNULL(cp.concepto, "SIN DEFINIR") as con_proy FROM saldos AS s LEFT JOIN empresas AS e ON e.idempresa = s.idEmpresa INNER JOIN usuarios as u ON u.idusuario = s.usuario  LEFT JOIN catalogo_proyecto as cp ON cp.idproyecto = s.idProyecto'); 
  }

  function get_result_saldos(){
    return $this->db->query('SELECT (select sum(IF(saldos.tipo_saldo=1,saldos.cantidad,saldos.cantidad*-1)) from saldos) as general, IFNULL(e.abrev, "SIN DEFINIR") abrev, sum(IF(s.tipo_saldo=1,s.cantidad,s.cantidad*-1)) as suma FROM saldos as s LEFT JOIN empresas AS e ON e.idempresa = s.idEmpresa group by s.idEmpresa'); 
  }

  function get_datos_logs($dato){
    $i = 0;
    $html='';
    $movimientos=$this->db->query("select s.*,concat(u.nombres,' ',u.apellidos) as nombre_completo from logs_saldos s join usuarios u on u.idusuario=s.idusuario where idsolicitud=$dato;");
    foreach($movimientos->result() as $movimiento ){
      $html= '  <div class="row">
                        <div class="col-lg-12">
                            <h5><label class="text-primary">'.date_format(date_create($movimiento->fecha), 'd-m-Y H:i:s') .'</label>:  <b>'. $movimiento->nombre_completo .'</b> - '. $movimiento->tipo_mov .'</h5>
                        </div></div>';
        $i = $i + 1;
    }

    if($i == 0){
      $html= '<h5 class="text-red">SIN MOVIMIENTOS REALIZADOS</h5>';
    }
    return $html;
  }
}