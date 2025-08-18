<?php

    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, authorization");
    header("Access-Control-Allow-Methods: GET, POST, HEAD");
    defined('BASEPATH') OR exit('No direct script access allowed');

class MY_PrivateToken extends CI_Controller {

    public function __construct() {
        parent::__construct();
     }

}