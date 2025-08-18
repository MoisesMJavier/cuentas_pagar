<?php
class Reload_session{
 
    private $CI;

    function __construct(){
        $this->CI =& get_instance();
    }

    function Reconstruir(){

        $this->CI->load->library('session');

        if( $this->CI->session->userdata("inicio_sesion") ){
            $nueva_session = $this->CI->session->userdata("inicio_sesion");
            $this->CI->session->set_userdata( "inicio_sesion", $nueva_session);
        }
    }
}