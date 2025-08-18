<?php

class MTipoCambio extends CI_Model {

  function __construct(){
    parent::__construct();
  }
  
  function getdataM(){
      $sql="select * from seriespormoneda;";
      return $this->db->query($sql);
  }
  
  function inserta_serie($post){
      return $this->db->query("insert into seriespormoneda values('$post[moneda]','$post[serie]');");
  }
  
  function edita_serie($post){
      return $this->db->query("update seriespormoneda set moneda='$post[moneda]',serie='$post[serie]' where moneda='$post[idregistro]';");
  }
}

