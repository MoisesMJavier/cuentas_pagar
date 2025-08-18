<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    require_once FCPATH . "application/vendor/autoload.php";
    use Google\Cloud\Storage\StorageClient;

    class MGCS extends CI_Model {
        
        var $gcs;
        var $storage;
        var $bucket = "3176-prohabitacion-cobranza";

        function __construct(){

            parent::__construct();

            //VERIFICAMOS LA AUTENTIFICACIÓN DE USUARIO POR MEDIO DEL ARCHIVO JSON COMPARTIDO POR AKRI.
            $this->storage = new StorageClient([
                'keyFilePath' => FCPATH.'application/vendor/bCobranza.json'
            ]);

            $this->gcs = $this->storage->bucket( $this->bucket );
        }

        function uploadFile( $archivo_upload, $nuevo_directorio_renombre ){
            return $this->gcs->upload(
                $archivo_upload,
                [
                    "name" => $nuevo_directorio_renombre
                ]
            ) != NULL;
        }

        function getFile( $directory ){    
            return ( $this->gcs->object( $directory ) )->signedUrl(new \DateTime('+3 hour'),['method' => 'GET'] );
        }

        function delete_file($routefile){
            $object = $this->gcs->object($routefile);
            return $object->delete();
        }
}