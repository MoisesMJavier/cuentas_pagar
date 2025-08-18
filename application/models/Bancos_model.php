<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bancos_model extends CI_Model {

    function __construct(){
        parent::__construct();
    }


     function getbancos(){
        return $this->db->query("SELECT * FROM bancos ORDER BY nombre");
    }
 
    function insertar_nuevo_banco($data)  
      {  
        return $this->db->insert("bancos", $data);
      }
 
    function insertar_nuevo_serie($data)  
      {  
       return $this->db->insert("serie_cheques", $data);
      }


       function insertar_nuevo_cuenta($data)  
      {  
          return $this->db->insert("cuentas", $data);
      }


     function insertar_nuevo_empresa($data)  
      {  
          return $this->db->insert("empresas", $data);
      }


      function get_bancos(){
         return $this->db->query('SELECT * FROM bancos WHERE estatus IN (1)'); 
          
    } 


      function get_seriado(){
         return $this->db->query('SELECT  serie_cheques.idNum,serie_cheques.serieInicial,serie_cheques.serie,empresas.nombre as empresaCheque, cuentas.nodecta as numeroCta FROM serie_cheques INNER JOIN empresas ON serie_cheques.idEmp = empresas.idempresa INNER JOIN cuentas ON cuentas.idcuenta = serie_cheques.idCuenta'); 
          
    } 




    function get_cuenta_bancos($data){
         return $this->db->query("SELECT cuentas.idcuenta, cuentas.nodecta, bancos.nombre FROM cuentas INNER JOIN bancos ON bancos.idbanco = cuentas.idbanco WHERE cuentas.idempresa =".$data); 
    } 

    



function get_cuentas(){
         return $this->db->query('SELECT idcuenta, cuentas.nombre as nomcue, bancos.nombre as nomban, tipocta, nodecta,  empresas.nombre as nomemp, activa, regpor, fecreg FROM cuentas INNER JOIN bancos ON cuentas.idbanco = bancos.idbanco INNER JOIN empresas ON cuentas.idempresa = empresas.idempresa'); 
          
    } 



    function get_empresas(){
         return $this->db->query('SELECT * FROM empresas ORDER BY nombre'); 
          
    } 
    

 

    function get_provs2($idproveedor)  
    {
      return  $this->db->query('SELECT idproveedor, proveedores.nombre as nomp, rfc, domicilio, contacto, email, tels, tipocta, clabe, cuenta, nomplaza, sucursal, fecadd, bancos.nombre as nomba FROM proveedores INNER JOIN bancos ON proveedores.idbanco=bancos.idbanco WHERE idproveedor="'.$idproveedor.'"');
    }



    function edita_banco($data,$idbanco){
       return $this->db->update("bancos", $data, "idbanco = '$idbanco'");
    }


     function edita_empresa($data,$idempresa){
       return $this->db->update("empresas", $data, "idempresa = '$idempresa'");
    }


 
}