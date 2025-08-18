<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class UTILS extends CI_Model {

    function __construct(){
        parent::__construct();
    }

    function transaction($tipo = 'start', $mensaje = ["exito" => 'Exito', "error" => 'Hubo un problema en la transacción', "info"], $rollback = 0, $data = []){
        if ($tipo == 'start') {
          $this->db->trans_start();
        } else if ($tipo == 'end') {
          if (!$this->db->trans_status()) {
            $this->db->trans_rollback();
            return array_merge(['estatus' => 0, 'mensaje' => $mensaje["error"]], $data);
          }
    
          if ($rollback) {
            $this->db->trans_rollback();
          } else {
            $this->db->trans_commit();
          }
          
          return array_merge(['estatus' => 1, 'mensaje' => $mensaje["exito"]], $data);
        } else if($tipo == 'validate') {
          $this->db->trans_rollback();
          $estatus = 0;
          $msn = '';
          if (isset($mensaje['info'])) {
            $msn = $mensaje['info'];
          } else if ($mensaje['error']) {
            $estatus = -1;
            $msn = $mensaje['error'];
          }
          return array_merge(['estatus' => $estatus, 'mensaje' => $msn], $data);
        }
    }
}
