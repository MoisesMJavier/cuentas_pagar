<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Idioma extends CI_Model {

    function __construct(){
        parent::__construct();
        $this->db->query("SET lc_time_names = 'es_MX'");
    }
}