<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
require_once dirname(__FILE__) . '/Dropbox/Dropbox.php';
 
class DP extends Dropbox{
    function __construct(){
        parent::__construct();
    }
}