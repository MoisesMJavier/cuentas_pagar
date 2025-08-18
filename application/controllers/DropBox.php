<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DropBox extends CI_Controller {

    function __construct(){
        parent::__construct();
        include APPPATH . 'third_party/Dropbox/Dropbox.php';
    }

    public function cargar_documento(){
        
        $app = new DropboxApp("9vkalyc8qnkdyu4", "52y6qjshmel2sdv","GdmmBA2vrsAAAAAAAAAAAUlxc0sV8veSGvMPN0rt5GL6Lt5MgBFsaDu493TfytQG");
        $dropbox = new Dropbox($app);

        if( isset($_FILES['documento_incapacidad']) ){
           $nombre = uniqid();
           $tempfile = $_FILES['documento_incapacidad']['tmp_name'];
           $ext = explode(".", $_FILES['documento_incapacidad']['type']);
           $ext = end($ext);
           $nombredropbox = "/".$nombre.".".$ext;
           print_r($nombredropbox);
       }

       try{
          $file = $dropbox->simpleUpload($tempfile,$nombredropbox, ['autorename' => true]);           
       }
       catch(Exception $e){
           print_r($e);
       }
    }

    
}