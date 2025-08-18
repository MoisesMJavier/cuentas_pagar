<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . "vendor/dropbox/vendor/autoload.php";
use  Kunnu\Dropbox\Dropbox;
use  Kunnu\Dropbox\DropboxApp;
use phpDocumentor\Reflection\Types\String_;

class Cajas_ch extends CI_Controller {

    function __construct(){
        parent::__construct();
        if ($this->session->userdata("inicio_sesion") && 
            (
                in_array($this->session->userdata("inicio_sesion")['rol'], array('SO')) ||
                (
                    in_array($this->session->userdata("inicio_sesion")['rol'], ["CP"]) &&
                    in_array($this->session->userdata("inicio_sesion")['depto'], ["CONTROL INTERNO", "CI-COMPRAS", "ADMINISTRACION"])
                )
            ) /** FECHA: 21-JUNIO-2024 | @autor Angel Victoriano <programador.analista30@ciudadmaderas.com> **/ ||
            in_array($this->session->userdata("inicio_sesion")['id'], [2259, 257]) /** Solo es para estos usuarios ver Cajas chicas en modo consulta  FECHA: 27-JUNIO-2024 | @autor Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
        ) {
  
            $this->load->model(array('Lista_dinamicas','M_cajach' ));

            $this->tipos = [
                ["idMotivo" => 1, "motivo" => "ACTUALIZACIÓN DE DOCUMENTOS"],
                ["idMotivo" => 2, "motivo" => "ARCHIVO EQUIVOCADO"],
                ["idMotivo" => 3, "motivo" => "CAMBIO DE NOMBRE"],
                ["idMotivo" => 4, "motivo" => "DOCUMENTOS FALTANTES"],
                ["idMotivo" => 5, "motivo" => "DUPLICIDAD DE DOCUMENTOS"],
                ["idMotivo" => 6, "motivo" => "EL ARCHIVO ESTÁ EN BLANCO"]
            ];

        } else{
            redirect("Login", "refresh");
        }
    }

    public function index(){

        $data['tipo_motivos'] = $this->tipos;

        $this->load->view("v_admin_caja_ch", $data);
    }

    public function listas(){
        // $url = 'https://rh.gphsis.com/index.php/WS/getPersonalTodos';
        // $ch = curl_init($url);
        // curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // $result = curl_exec($ch);
        // curl_close($ch);

        // $info["deptos"] = $this->Lista_dinamicas->get_lista_departamento()->result_array();
        // $info["empresas"] = $this->Lista_dinamicas->get_empresas_lista()->result_array();
        // $info["usuarios"] = $this->Lista_dinamicas->getUsuarios()->result_array();

        // if($result){
        //     $info["responsable"] = base64_decode( $result );
        // }else{
        //     $info["responsable"] = false;
        // }
        
        // echo json_encode($info);

        $url = 'https://rh.gphsis.com/index.php/WS/getPersonalTodos';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $result = curl_exec($ch);
        $curlError = curl_error($ch);
        curl_close($ch);

        $info = [];
        $info["success"] = true; // Indicador de éxito

        // Validar errores de cURL
        if ($result === false) {
            $info["success"] = false;
            $info["message"] = "No se pudo conectar al servicio remoto.";
            $info["error_detail"] = $curlError;
            echo json_encode($info, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            return;
        }

        // Decodificar la respuesta en base64
        $decoded = base64_decode($result, true); // true para modo estricto
        if ($decoded === false) {
            $info["success"] = false;
            $info["message"] = "El servicio remoto respondió, pero los datos no son válidos (base64 inválido).";
            echo json_encode($info, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            return;
        }

        // Agregar los datos decodificados
        $info["responsable"] = $decoded;

        // Validar consultas a la base de datos
        try {
            $info["deptos"] = $this->Lista_dinamicas->get_lista_departamento()->result_array();
            $info["empresas"] = $this->Lista_dinamicas->get_empresas_lista()->result_array();
            $info["usuarios"] = $this->Lista_dinamicas->getUsuarios()->result_array();
        } catch (Exception $e) {
            $info["success"] = false;
            $info["message"] = "Ocurrió un error al obtener datos de la base de datos.";
            $info["error_detail"] = $e->getMessage();
            echo json_encode($info, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            return;
        }

        // Enviar respuesta exitosa
        echo json_encode($info, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);


    }

    public function cajaschicas(){
        echo json_encode(["data"=>$this->M_cajach->getInfo()->result_array()]);
    }

    public function nueva_caja(){
        
        $newCaja["nombre"] = $_POST["nombre"];
        $newCaja["monto"] = $_POST["monto"];
        $newCaja["idcontrato"] = $_POST["idcontrato"];
        $newCaja["fcrea"] = date("Y-m-d H:i:s");
        $newCaja["idcrea"] = $this->session->userdata("inicio_sesion")['id'];
        $newCaja["estatus"] = 1;// 1=> Activa , 0 => inactiva, 2= Eliminada/Bja

        $this->M_cajach->insert_caja($newCaja);

        $idCaja = $this->db->insert_id();
        /**
         * INICIO | FECHA : 25-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Se inserta en la tabla log_cajasch la observacion
         * al momento de crear una nueva caja chica.
         */
        $this->M_cajach->insert_logCaja($idCaja, "create", "", "Se creó la caja chica.", "", $_POST["observacion"]);
        /**
         * FIN : 25-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com
         */
        // insertar empresa
        foreach($_POST["empresa"] as $emp){
            $this->M_cajach->insert_cajaempresa([ "idcaja" => $idCaja, "idempresa" => $emp, "estatus" => 1 ]);
        }
        // insertar departamento
        foreach($_POST["depto"] as $depto){
            $this->M_cajach->insert_cajadepto(["idcaja" => $idCaja, "iddepto" => $depto, "estatus" => 1 ]);
        }

        echo json_encode(["res" => 1, "respuesta" => "¡CAJA CHICA CREADA CON ÉXITO!"]);
    }

    public function updateEstatus(){
        if($this->db->update("cajas_ch", array( "estatus" => $_POST['estatus']), "idcaja = '".$_POST["idcaja"]."'")){
            // $this->M_cajach->insert_logCaja($_POST["idcaja"],"update",$_POST['estatus_ant'],$_POST['estatus'],"estatus");
            echo json_encode(["res" => true, "data"=>[]]);
        }else    
            echo json_encode(["res" => false, "data"=>[]]);
    }

    public function edit_caja(){
        $c_new = $_POST;
        if($_POST["idcaja"])
            /**
             * INICIO | FECHA : 25-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Se agregan las columnas idreembolso_ch y nombre_reembolso_ch
             * a la consulta para poder realizar los inserts correspondientes al actualizar estos campos.
             */
            $c_ant = $this->db->query("SELECT monto, nombre, idcontrato, idreembolso_ch, nombre_reembolso_ch FROM cajas_ch WHERE idcaja =?",[$_POST["idcaja"]])->result_array();
            /**
             * FIN | FECHA : 25-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com 
             */  
        $dataUpdate["idmodifica"] = $this->session->userdata("inicio_sesion")['id'];
        $dataUpdate["fmodifica"] =date("Y-m-d H:i:s");
        $this->db->db_debug = FALSE; //disable debugging for queries
        $this->db->trans_begin();

        // if($c_ant[0]["idcontrato"] != $c_new["idcontrato"]){ SE COMENTA ESTA PARTE YA QUE NO SE PUEDE ACTUALIZAR EL RESPONSABLE Y IDCONTRATO
        //     $dataUpdate["nombre"] = $c_new["nombre"];
        //     $dataUpdate["idcontrato"] = $c_new["idcontrato"];
        //     // log cambio de nombre
        //     $this->M_cajach->insert_logCaja($c_new["idcaja"],"update",$c_ant[0]["idcontrato"],$c_new["idcontrato"],"idcontrato", $c_new["observacion"]);
        //     $this->M_cajach->insert_logCaja($c_new["idcaja"],"update",$c_ant[0]["nombre"],$c_new["nombre"],"nombre", $c_new["observacion"]);
        // } SE COMENTA ESTA PARTE YA QUE NO SE PUEDE ACTUALIZAR EL RESPONSABLE Y IDCONTRATO
        /**
         * INICIO | FECHA : 25-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Se valida que existan las dos variables en el POST para poder
         * actualizar estos campos en la tabla cajas_ch y realizar el insert en log_cajasch y auditoria.
         */
        if(isset($c_new['idreembolsoEdit'])&& isset($c_new['nombre_reembolsoEdit'])){
            $dataUpdate["idreembolso_ch"] = $c_new['idreembolsoEdit'];
            $dataUpdate["nombre_reembolso_ch"] = $c_new['nombre_reembolsoEdit'];
            $this->M_cajach->insert_logCaja($c_new["idcaja"],"update",$c_ant[0]["idreembolso_ch"],$c_new["idreembolsoEdit"],"idreembolso_ch", $c_new["observacion"]);
            $this->M_cajach->insert_logCaja($c_new["idcaja"],"update",$c_ant[0]["nombre_reembolso_ch"],$c_new["nombre_reembolsoEdit"],"nombre_reembolso_ch", $c_new["observacion"]);
            $this->M_cajach->insertAuditoria($c_new["idcaja"], $c_ant[0]["idreembolso_ch"], $c_new["idreembolsoEdit"], "idreembolso_ch");
            $this->M_cajach->insertAuditoria($c_new["idcaja"], $c_ant[0]["nombre_reembolso_ch"], $c_new["nombre_reembolsoEdit"], "nombre_reembolso_ch");
        }
        /**
         * FIN | FECHA : 25-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com
         */
        if($c_ant[0]["monto"] != $c_new["monto"]){
            $dataUpdate["monto"] = $c_new["monto"];
            // log cambio monto
            $this->M_cajach->insert_logCaja($c_new["idcaja"],"update",$c_ant[0]["monto"],$c_new["monto"],"monto", $c_new["observacion"]);
        }
        // Update Tabla Caja_ch
        /**
         * INICIO | FECHA : 25-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Se agrega la isset($dataUpdate["idreembolso_ch"]) que valida si existe esta 
         * varible actualiza la tabla cajas_ch con los datos que trae el arreglo dataUpdate.
         */
        if(isset($dataUpdate["idcontrato"]) || isset($dataUpdate["monto"]) || isset($dataUpdate["idreembolso_ch"]))
            $this->db->update("cajas_ch", $dataUpdate, "idcaja = '".$c_new["idcaja"]."'");
        /**
         * FIN | FECHA : 25-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com
         */
        // Empresas
        // $c_ant["empresa"] = $this->db->query("SELECT idempresa FROM caja_empresa WHERE idcaja = ? AND estatus = 1 ",[$_POST["idcaja"]])->result_array();
        $empAnt = $this->db->query("SELECT idempresa FROM caja_empresa WHERE idcaja = ? AND estatus = 1 ",[$_POST["idcaja"]])->result_array();
        // Empresas Eliminadas
        $empDel=[];
        foreach($empAnt as $ant){
            $val=0;
            foreach($c_new["empresa"] as $new){
                if($ant["idempresa"] == $new)
                    $val+=1;
            }
            if($val==0)
                array_push($empDel, $ant["idempresa"]);
        }
        // Empresas Nuevas
        $empNew=[];
        foreach($c_new["empresa"] as $new){
            $val=0;
            foreach($empAnt as $ant){
                if($new == $ant["idempresa"])
                    $val +=1;
            }
            if($val==0)
                array_push($empNew, $new);
        }

        if(!empty($empDel)){ // Actualiza estatus de empresa eliminada
            foreach($empDel as $ed){
                $this->db->update("caja_empresa", ["estatus" => 0], "idcaja='".$c_new["idcaja"]."' AND idempresa = '".$ed."'");
                $this->M_cajach->insert_logCaja($c_new["idcaja"],"update_empresa",$ed,$ed,"estatus_empresa", $c_new["observacion"]);
            }
        }
        if(!empty($empNew)){ // Inserta nuevas empresas 
            foreach($empNew as $en){
                $this->M_cajach->insert_cajaempresa([ "idcaja" => $c_new["idcaja"], "idempresa" => $en, "estatus" => 1 ]);
                $this->M_cajach->insert_logCaja($c_new["idcaja"],"insert_empresa", "" ,$en,"empresa", $c_new["observacion"]);
            }
        }

        // Deptos
        if(isset($c_new["depto"]) && !empty($c_new["depto"])){      
            $deptoAnt = $this->db->query("SELECT iddepto FROM caja_depto WHERE idcaja = ? AND estatus =1 ",[$_POST["idcaja"]])->result_array();
            // Deptos Eliminados
            $depDel=[];
            foreach($deptoAnt as $dep_a){
                $val=0;
                foreach($c_new["depto"] as $dep_n){
                    if($dep_a["iddepto"] == $dep_n)
                        $val +=1;
                }
                if($val==0)
                    array_push($depDel, $dep_a["iddepto"]);
            }
            // Deptos Nuevos
            $depNew=[];
            foreach($c_new["depto"] as $dn){
                $val=0;
                foreach($deptoAnt as $da){
                    if($dn == $da["iddepto"])
                        $val +=1;
                }
                if($val ==0)
                    array_push($depNew, $dn);
            }
    
            if(!empty($depDel)){ // Actualiza estatus de depto eliminado
                foreach($depDel as $de){
                    $this->db->update("caja_depto", ["estatus" => 0], "idcaja='".$c_new["idcaja"]."' AND iddepto = '".$de."'");
                    $this->M_cajach->insert_logCaja($c_new["idcaja"], "update_depto","iddepto: ".$de." estatus: 1","iddepto :".$de." estatus: 0","estatus_depto", $c_new["observacion"]);
                }
            }
            if(!empty($depNew)){ // Insert de nuevos deptos
                foreach($depNew as $dn){
                    $this->M_cajach->insert_cajadepto(["idcaja" => $c_new["idcaja"], "iddepto" => $dn, "estatus" => 1 ]);
                    $this->M_cajach->insert_logCaja($c_new["idcaja"], "insert_depto", "" ,"iddeptp: ".$dn." estatus: 1","depto", $c_new["observacion"]);
                }
            }
        }


        if ($this->db->trans_status() === FALSE ){ 
            $this->db->trans_rollback();
            $respuesta = array("res" => 0 , "respuesta" => "TRANSACCIÓN FALLIDA." );
        }else{
            $this->db->trans_commit();
            $respuesta = array("res" => 1 , "respuesta" => "TRANSACCIÓN FINALIZADA CORRECTAMENTE." );
            
        }     
        echo json_encode($respuesta);
    }

    public function asignar_usuario(){
        $user = $_POST["user"];
        $idreembolso_ch = $_POST["idreembolso_ch"];
        $nombre_reembolso_ch = $_POST["nombre_reembolso_ch"];
        $idcaja = $_POST["idcaja"];
        $archivo = $_FILES ? $_FILES : NULL;

        if (!empty($archivo['usrfile']['name'])) { //Validar que el archivo a cargar sea PDF
            $extension = pathinfo($archivo['usrfile']['name'], PATHINFO_EXTENSION);
            if ($extension !== 'pdf' && $archivo['usrfile']['type'] !== 'application/pdf') {
                $res = ["res" => 0, "respuesta" => "EL ARCHIVO NO CUMPLE CON EL FORMATO PDF REQUERIDO."];
                echo json_encode($res);
                exit;
            }
        }

        if(isset($_POST) && !empty($_POST) ){
            // Guardar en LOCAL/SERVIDOR
            // Configuración de carga de archivos
            $config = [
                'upload_path'   => './UPLOADS/DCCH/',
                'allowed_types' => 'pdf',
                'overwrite'     => FALSE,
            ];
            $this->load->library('upload', $config);

            $ruta_pdf = './UPLOADS/DCCH/Caja_' . $idcaja . '/';

            if (!is_dir($ruta_pdf)) {
                mkdir($ruta_pdf, 0777, true); // 0777 otorga permisos completos, ajusta según tus necesidades
            }

            if ($this->upload->do_upload('usrfile')) { //Se guarda en ./UPLOADS/DCCH/
                $pdf_subido = $this->upload->data();
                $ndocname = 'DOCUMENTO DE AUTORIZACIÓN' . '.pdf';
                $nueva_ruta = $ruta_pdf . $ndocname;

                if (!rename($pdf_subido['full_path'], $nueva_ruta)) { 
                    $res = ["res" => 0, "respuesta" => "SE HA PRODUCIDO UN ERROR AL INTENTAR RENOMBRAR EL ARCHIVO."];
                    echo json_encode($res);
                    exit;
                }else{
                    $insertDc = [
                        "movimiento"=> 'Documento de autorización para caja chica',
                        "expediente" => $ndocname,
                        "modificado"=> null,
                        "idmodificado" => null,
                        "estatus" => 1,
                        "idSolicitud" => $idcaja, 
                        "idUsuario" => $this->session->userdata("inicio_sesion")['id'],
                        "tipo_doc" => 7
                    ];

                    // Insert historial_documento
                    $this->db->insert("historial_documento", $insertDc);
                    $this->db->insert_id();

                    $dataUpdate = [
                        "idusuario"=> $user,
                        "fmodifica"=> date("Y-m-d H:i:s"),
                        "idmodifica"=> $this->session->userdata("inicio_sesion")['id'],
                        "idreembolso_ch"=> $idreembolso_ch,
                        "nombre_reembolso_ch"=> $nombre_reembolso_ch
                    ];
        
                    // Update tabla cajas_ch 
                    $this->db->update("cajas_ch", $dataUpdate, "idcaja = '$idcaja'");

                    $this->M_cajach->insert_logCaja($idcaja,"insert","",$ndocname,"documento", 'DOCUMENTO DE AUTORIZACIÓN');
        
                    $res =["res" => 1, "¡REGISTRO CON ÉXITO!."];
                    echo json_encode($res);
                    exit;
                }
            }else{
                $res = ["res" => 0, "respuesta" => "NO SE PUDO PROCESAR EL DOCUMENTO."];
                echo json_encode($res);
                exit;
            }

        }else {
            $res = ["res" => 0, "respuesta" => "NO HAY DATOS."];
            echo json_encode($res);
            exit;
        }
    }
    
    public function cerrar_caja(){
        // $numDoc = 0;
        $res = ["res"=> 0];
        if(isset($_POST) && !empty($_POST) ){

            $idcaja = $_POST["idcaja"];
            $observacion = $_POST["observacion_crrfile1"];
            $ndocname = $_POST["archivo1"];
            $archivo = $_FILES ? $_FILES : NULL;

            if (!empty($archivo['crrfile1']['name'])) { //Validar que el archivo a cargar sea PDF
                $extension = pathinfo($archivo['crrfile1']['name'], PATHINFO_EXTENSION);
                if ($extension !== 'pdf' && $archivo['crrfile1']['type'] !== 'application/pdf') {
                    $res = ["res" => 0, "respuesta" => "EL ARCHIVO NO CUMPLE CON EL FORMATO PDF REQUERIDO."];
                    echo json_encode($res);
                    exit;
                }
            }

            $config = [
                'upload_path'   => './UPLOADS/DCCH/',
                'allowed_types' => 'pdf',
                'overwrite'     => FALSE,
            ];
            $this->load->library('upload', $config);

            $ruta_pdf = './UPLOADS/DCCH/Caja_' . $idcaja . '/';

            if (!is_dir($ruta_pdf)) {
                mkdir($ruta_pdf, 0777, true); // 0777 otorga permisos completos, ajusta según tus necesidades
            }

            if ($this->upload->do_upload('crrfile1')) { //Se guarda en ./UPLOADS/DCCH/
                $pdf_subido = $this->upload->data();
                $nuevo_nombre = $ndocname . '.pdf';
                $nueva_ruta = $ruta_pdf . $nuevo_nombre;

                if (!rename($pdf_subido['full_path'], $nueva_ruta)) { 
                    $res = ["res" => 0, "respuesta" => "SE HA PRODUCIDO UN ERROR AL INTENTAR RENOMBRAR EL ARCHIVO."];
                    echo json_encode($res);
                    exit;
                }else{
                    $insertDc = [
                        "movimiento"=> $observacion,
                        "expediente" => $nuevo_nombre,
                        "modificado"=> null,
                        "idmodificado" => null,
                        "estatus" => 1,
                        "idSolicitud" => $idcaja, 
                        "idUsuario" => $this->session->userdata("inicio_sesion")['id'],
                        "tipo_doc" => 8
                    ];

                    // Insert historial_documento
                    $this->db->insert("historial_documento", $insertDc);
                    $this->db->insert_id();

                    $dataUpdate = [
                        "fmodifica"=> date("Y-m-d H:i:s"),
                        "idmodifica"=> $this->session->userdata("inicio_sesion")['id'],
                        "estatus" => 0
                    ];
        
                    // Update tabla cajas_ch 
                    $this->db->update("cajas_ch", $dataUpdate, "idcaja = '$idcaja'");

                    $this->M_cajach->insert_logCaja($_POST["idcaja"],"close","",$nuevo_nombre,"documento", $observacion);
        
                    $res =["res" => 1, "¡REGISTRO CON ÉXITO!."];
                    echo json_encode($res);
                    exit;
                }
            }else{
                $res = ["res" => 0, "respuesta" => "NO SE PUDO PROCESAR EL DOCUMENTO."];
                echo json_encode($res);
                exit;
            }
        }else {
            $res = ["res" => 0, "respuesta" => "NO HAY DATOS."];
            echo json_encode($res);
            exit;
        }
    }

    public function edit_doc(){

        $ndocname = $_POST['ndocname']; //Nombre de documento (VISTA)
        $motivo_documento = $_POST['motivo_documento']; //Motivo del cambio
        $observacion = $_POST['observacion']; //Comentario Adicional

        $ids = array_column($this->tipos, 'idMotivo');
        $index = array_search($motivo_documento, $ids);

        if ($index !== false) {
            $motivo_documento = $this->tipos[$index]['motivo'];
        } else {
            $res = ["res" => 0, "respuesta" => "NO SE ENCONTRO MOTIVO DE LA ACTUALIZACIÓN"];
            echo json_encode($res);
            exit;
        }

        $motivo_cambio = strtoupper($motivo_documento . ($observacion ? ': ' . $observacion : '')); //Si hay un comentario adicional, se concatena con el motivo
        $ubicacion = $_POST['ubicacion']; //Desde donde esta el documento (dropbox, local)
        $estatusDoc = $_POST['estatusDoc']; //Estatus actual del documento -> 0 Inactivo, 1 Activo, 2 En espera de validacion, 3 Rechazo, 4 Eliminacion
        $idcaja = $_POST['idcaja']; //Identificador de la caja a actualizar
        $idDocumento = $_POST['idDoc']; //Identificador del documento a actualizar
    
        $dir = dirname($_SERVER['SCRIPT_FILENAME']); //C:/xampp/htdocs/cuentas_pagar
        $rutaDocumentosCch = $dir . '/UPLOADS/DCCH/Caja_'; //C:/xampp/htdocs/cuentas_pagar/UPLOADS/DCCH/Caja_
    
        //FILES
        $archivo = $_FILES ? $_FILES : NULL;
        $res = ["res" => 0];

        if (!empty($archivo['crrfile3']['name'])) { //Validar que el archivo a cargar sea PDF
            $extension = pathinfo($archivo['crrfile3']['name'], PATHINFO_EXTENSION);
            if ($extension !== 'pdf' && $archivo['crrfile3']['type'] !== 'application/pdf') {
                $res = ["res" => 0, "respuesta" => "EL ARCHIVO NO CUMPLE CON EL FORMATO PDF REQUERIDO."];
                echo json_encode($res);
                exit;
            }
        }
    
        // Obtener los resultados actuales
        $documento = $this->db->query("SELECT iddoc_cajach AS idDoc, estatus, 'dropbox' AS ubicacion, iddocumento as tipo, IF(nombre_documento IS NOT NULL, nombre_documento, ruta) AS nombre_documento, descripcion
                FROM cajach_documentos
                WHERE idcaja = ? AND iddoc_cajach = ? AND ruta NOT LIKE './UPLOADS/%' AND estatus IN (1, 3)
            UNION ALL
                SELECT idDocumento as idDoc, estatus, 'local' AS ubicacion, tipo_doc as tipo, expediente as nombre_documento, movimiento as descripcion
                FROM historial_documento
                WHERE tipo_doc IN (5, 7, 8) AND idSolicitud = ? AND idDocumento = ? AND estatus IN (1, 3)",
                [$idcaja, $idDocumento, $idcaja, $idDocumento])->result_array();
            
        if(count($documento) < 1){            
            $res = ["res" => 0, "respuesta" => "NO SE ENCONTRARON DATOS DISPONIBLES."];
            echo json_encode($res);
            exit;
        }
    
        $respuesta = NULL;
        $nombreActual = NULL;
    
        //Validar nombre actual del archivo
        if (substr($documento[0]['nombre_documento'], -4) == ".pdf" && $ubicacion == 'local') {
            $nombreActual = substr($documento[0]['nombre_documento'], 0, -4); //SI TIENE .PDF LOCAL
        }else{
            //DROPBOX
            $cadena = $documento[0]['nombre_documento']; //Trae campo nombre_documento || nombre_documento (ruta)
    
            if (strpos($cadena, '/') !== false) { //Si lleva una ruta /CAJAS_CHICAS/*/* -> nombre_documento (ruta)
                $nombreConExtension = basename($cadena);
                $info = pathinfo($nombreConExtension);
                $nombreSinExtension = $info['filename'];
                $nombreActual = $nombreSinExtension;
            } else {
                $nombreSinExtension = $cadena; //Si solo es un texto -> (nombre_documento)
                $nombreActual = $nombreSinExtension;
            }
            $nombreActual = strtoupper($nombreActual);
        }
    
        if (!empty($ndocname) && $nombreActual !== $ndocname && empty($archivo['crrfile3']['name'])){ 
            if ($ubicacion === 'dropbox') { 
                if ($documento[0]['tipo'] === '2' && strpos($documento[0]['nombre_documento'], '/') !== false) {
                    $nombreActual = "DOCUMENTO DE AUTORIZACIÓN";
                }
    
                $dataUpdate = [
                    "modificado" => date("Y-m-d H:i:s"),
                    "idmodificado" => $this->session->userdata("inicio_sesion")['id'],
                    "nombre_documento" => $ndocname
                ];
    
                $this->db->where('iddoc_cajach', $idDocumento);
                $this->db->where('idcaja', $idcaja);
    
                $exito = $this->db->update('cajach_documentos', $dataUpdate);
    
                if ($exito) {
                    $res = ["res" => 1];
                    $this->M_cajach->insert_logCaja($idcaja,"update",$nombreActual,$ndocname,"documento", $motivo_cambio);
                } else {
                    $error = $this->db->error();
                    echo json_encode(["res" => 0, "error" => $error['message']]);
                    exit;
                }
    
                echo json_encode($res);
                exit;
            } 
    
            if($ubicacion === 'local'){ 
                $dataUpdate = [ "modificado" =>date("Y-m-d H:i:s")
                    , "idmodificado" => $this->session->userdata("inicio_sesion")['id']
                    , "expediente" => $ndocname.'.pdf'
                ];
                
                if(file_exists($rutaDocumentosCch. $idcaja . '/' . $nombreActual . '.pdf')){
                    if (rename($rutaDocumentosCch. $idcaja . '/' . $nombreActual . '.pdf', $rutaDocumentosCch. $idcaja . '/' . $ndocname . '.pdf')) {
                        if(touch($rutaDocumentosCch. $idcaja . '/' . $ndocname . '.pdf', time())){
                            
                            $this->db->where('idDocumento', $idDocumento);
                            $this->db->where('idSolicitud', $idcaja);
    
                            $exito = $this->db->update('historial_documento', $dataUpdate);
    
                            if ($exito) {
                                $res = ["res" => 1];
                                $this->M_cajach->insert_logCaja($idcaja,"update",$nombreActual,$ndocname,"documento", $motivo_cambio);
                            } else {
                                $error = $this->db->error();
                                echo json_encode(["res" => 0, "error" => $error['message']]);
                                exit;
                            }
                        }
                    } else {
                        $res = ["res" => 0, $respuesta = "NO SE PUDO PROCEDER CON EL CAMBIO DE NOMBRE DEL DOCUMENTO."];
                    }
                    echo json_encode($res);
                    exit;
                }else{
                    $res = ["res" => 0, $respuesta = "NO SE ENCONTRÓ EL ARCHIVO EN EL SERVIDOR PARA PROCEDER CON LA ACTUALIZACIÓN."];
                    echo json_encode($res);
                    exit;
                }
            } 
        } else if (!empty($archivo['crrfile3']['name']) && !empty($ndocname)) { 
            
            if ($ubicacion === 'dropbox') { 
    
                $nombreArchivoTemp = './UPLOADS/DCCH/Temp/Caja_' . $idcaja . '/' . $ndocname . '.pdf'; //Ruta Temp
    
                if (file_exists($nombreArchivoTemp)) {
                    $res = ["res" => 0, "respuesta" => "EL ARCHIVO EXISTE EN LA CARPETA TEMPORAL."];
                    echo json_encode($res);
                    exit;
                } else {
                    // Configuración de carga de archivos
                    $config = [
                        'upload_path'   => './UPLOADS/DCCH/Temp/',
                        'allowed_types' => 'pdf',
                        'overwrite'     => FALSE,
                    ];
                    
                    $this->load->library('upload', $config);
                    
                    $ruta_pdf = './UPLOADS/DCCH/Temp/Caja_' . $idcaja . '/';
    
                    if (!is_dir($ruta_pdf)) {
                        mkdir($ruta_pdf, 0777, true); // 0777 otorga permisos completos, ajusta según tus necesidades
                    }
    
                    if ($this->upload->do_upload('crrfile3')) { //Se guarda en ./UPLOADS/DCCH/Temp/
                        $pdf_subido = $this->upload->data();
                        $nueva_ruta = $ruta_pdf . $ndocname . '.pdf';
                        
                        if (!rename($pdf_subido['full_path'], $nueva_ruta)) { 
                            $res = ["res" => 0, "respuesta" => "SE HA PRODUCIDO UN ERROR AL INTENTAR RENOMBRAR EL ARCHIVO."];
                            echo json_encode($res);
                            exit;
                        }else{ 
                            // Se mueve a ./UPLOADS/DCCH/Temp/Caja_$idcaja/$nuevo_nombre_pdf.pdf
                            $dataUpdate = [ "modificado" => date("Y-m-d H:i:s")
                                , "idmodificado" => $this->session->userdata("inicio_sesion")['id']
                                , "estatus" => 0
                            ];
    
                            $this->db->where('iddoc_cajach', $idDocumento);
                            $this->db->where('idcaja', $idcaja);
    
                            $paso1 = $this->db->update('cajach_documentos', $dataUpdate);
    
                            if(!$paso1){
                                $error = $this->db->error();
                                echo json_encode(["res" => 0, "respuesta" => $error['message']]);
                                exit;
                            }else{
                                $dataInsert = array(
                                        'movimiento' => '"'.$idDocumento.'". ' . $motivo_cambio
                                        , 'expediente' => $ndocname . '.pdf'
                                        , 'estatus' => 2
                                        , 'idSolicitud' => $idcaja
                                        , 'idUsuario' => $this->session->userdata("inicio_sesion")['id']
                                        , 'tipo_doc' => $documento[0]['tipo'] == 2 || $documento[0]['tipo'] == "2" ? 7 : 5
                                );
    
                                $this->db->where('idDocumento', $idDocumento);
                                $this->db->where('idSolicitud', $idcaja);
    
                                $paso2 = $this->db->insert('historial_documento', $dataInsert);
                            }
    
                            echo json_encode(["res" => 1, "respuesta" => "Archivo subido y renombrado exitosamente."]);
                            $this->M_cajach->insert_logCaja($idcaja,"update",$nombreActual.'.pdf',$ndocname. '.pdf', "documento", $motivo_cambio);
                            exit;
                        }
                    }
                }
            }
    
            if ($ubicacion === 'local'){ 
                // print_r('local <br><br>');
                $nombreArchivoTemp = './UPLOADS/DCCH/Temp/Caja_' . $idcaja . '/' . $ndocname . '.pdf'; //Ruta Temp
    
                if (file_exists($nombreArchivoTemp)) {

                    $res = ["res" => 0, "respuesta" => "EL ARCHIVO EXISTE EN LA CARPETA TEMPORAL.."];
                    echo json_encode($res);
                    exit;

                } else {
                    // Configuración de carga de archivos
                    $config = [
                        'upload_path'   => './UPLOADS/DCCH/Temp/',
                        'allowed_types' => 'pdf',
                        'overwrite'     => FALSE,
                    ];
                    
                    $this->load->library('upload', $config);
                    
                    $ruta_pdf = './UPLOADS/DCCH/Temp/Caja_' . $idcaja . '/';
    
                    if (!is_dir($ruta_pdf)) {
                        mkdir($ruta_pdf, 0777, true); // 0777 otorga permisos completos, ajusta según tus necesidades
                    }
    
                    if ($this->upload->do_upload('crrfile3')) { //Se guarda en ./UPLOADS/DCCH/Temp/
                        $pdf_subido = $this->upload->data();
    
                        $nombreDocumento = NULL;
                        if($nombreActual !== $ndocname){
                            $nueva_ruta = $ruta_pdf . $ndocname . '.pdf';
                            $nombreDocumento = $ndocname . '.pdf';
                        }else if($nombreActual === $ndocname){
                            $nueva_ruta = $ruta_pdf . $nombreActual . '.pdf';
                            $nombreDocumento = $nombreActual . '.pdf';
                        }
                        
                        if (!rename($pdf_subido['full_path'], $nueva_ruta)) { //Se mueve a ./UPLOADS/DCCH/Temp/Caja_$idcaja/$nuevo_nombre_pdf.pdf
    
                            $res = ["res" => 0, "respuesta" => "SE HA PRODUCIDO UN ERROR AL INTENTAR RENOMBRAR EL ARCHIVO."];
                            echo json_encode($res);
                            exit;
    
                        } else {
                            // $res = ["res"=> 1, "respuesta" => "Archivo subido y renombrado exitosamente."];-
                            $dataUpdate = [ "modificado" =>date("Y-m-d H:i:s")
                                , "idmodificado" => $this->session->userdata("inicio_sesion")['id']
                                , "estatus" => 0
                            ];
    
                            $this->db->where('idDocumento', $idDocumento);
                            $this->db->where('idSolicitud', $idcaja);
                            // 0 Inactivo, 1 Activo, 2 En espera de validacion, 3 Rechazo, 4 Eliminacion
                            $paso1 = $this->db->update('historial_documento', $dataUpdate); // -- ACTUALIZAR EL DOCUMENTO ACTUAL A: 0 Inactivo
    
                            if ($paso1) { 
                                // -- NUEVO ARCHIVO INSERTARLO Y DARLO COMO ARCHIVO EN REVISION (ESTATUS 2)
                                $dataInsert = array(
                                        'movimiento' => '"'.$idDocumento.'". ' . $motivo_cambio
                                        , 'expediente' => $nombreDocumento
                                        , 'estatus' => 2
                                        , 'idSolicitud' => $idcaja
                                        , 'idUsuario' => $this->session->userdata("inicio_sesion")['id']
                                        , 'tipo_doc' => $documento[0]['tipo'] == 2 || $documento[0]['tipo'] == "2" ? 7 : 5
                                );
    
                                $this->db->where('idDocumento', $idDocumento);
                                $this->db->where('idSolicitud', $idcaja);
    
                                $paso2 = $this->db->insert('historial_documento', $dataInsert);
    
                                if ($paso2) {
                                    if($nombreDocumento !== $nombreActual){
                                        $this->M_cajach->insert_logCaja($idcaja,"update",$nombreActual.'.pdf',$nombreDocumento. '.pdf', "documento", $motivo_cambio);
                                    }else if ($nombreDocumento === $nombreActual){
                                        $this->M_cajach->insert_logCaja($idcaja,"update",$nombreActual.'.pdf',$nombreActual. '.pdf', "documento", $motivo_cambio);
                                    }
                                    echo json_encode(["res" => 1, "respuesta" => "Se agrego nuevo archivo, reemplazado el anterior (0)."]);
                                    exit;
                                } else {
                                    $error = $this->db->error();
                                    echo json_encode(["res" => 0, "respuesta" => $error['message']]);
                                    exit;
                                }
                            } else {
                                $error = $this->db->error();
                                echo json_encode(["res" => 0, "respuesta" => $error['message']]);
                                exit;
                            }
                        }
                    } else {
                        $res = ["res" => 0, "respuesta" => "ERROR AL SUBIR EL ARCHIVO: " . $this->upload->display_errors()];
                        echo json_encode($res);
                        exit;
                    }
                }
            }
        } else {
            $res = ["res" => 0, $respuesta => "NO SE HAN REALIZADO OPERACIONES."];
            echo json_encode($res);
            exit;
        }
    }

    function link_documentos(){
        $idcaja = $_POST["idcaja"];
        $app = new DropboxApp("9vkalyc8qnkdyu4", "9z1gmsrap71gnyh", "DHBVzF2_RoQAAAAAAAAAAQTFz_Gj51gd1_aezVei-CJ4qHIVMbHZqFkoAtxObVpK");
        $dropbox = new Dropbox($app);
        /** FECHA INICIO: 29-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
        $docs =  $this->db->query("SELECT
                iddoc_cajach AS idDoc,
                estatus,
                iddocumento,
                ruta,
                descripcion,
                'dropbox' AS ubicacion,
                nombre_documento
            FROM
                cajach_documentos
            WHERE
                idcaja = '$idcaja'
            AND ruta NOT LIKE './UPLOADS/%'
            AND estatus >= 1

            UNION
            
            SELECT
                idDocumento AS idDoc,
                estatus,
                tipo_doc AS iddocumento,
                expediente AS ruta,
                movimiento AS descripcion,
                'local' AS ubicacion,
                NULL AS nombre_documento
            FROM
                historial_documento
            WHERE
                tipo_doc IN (5, 7, 8)
            AND idSolicitud = '$idcaja'
            AND estatus >= 1"
        ); /** FECHA FIN: 29-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
        $json["data"] = array();

        if ($docs->num_rows() > 0) {
            foreach ($docs->result() as $row) {
                
                if($row->ubicacion == "dropbox"){
                    $ruta = $row->ruta;
                    if (!empty($row->nombre_documento)) {
                        $docname = $row->nombre_documento;
                    }else{
                        $patron = '/\/CAJAS_CHICAS\/(\d+)\/addoc_(.+)/';
                        if (preg_match($patron, $ruta, $coincidencias)) {
                            $numero = $coincidencias[1];
                            $docname = $coincidencias[2];
                        } else {
                            $docname = '';
                        }
                    }

                    try {
                        $links = $dropbox->getTemporaryLink($ruta);
                        $link = $links->getLink();
                    } catch (\Exception $e) {
                        $respuesta['respuestas'] = false;
                    }
                }else if ($row->ubicacion == "local"){

                    $link = NULL;

                    
                    if(file_exists('./UPLOADS/DCCH/Temp/Caja_'. $idcaja. '/' . $row->ruta) && $row->estatus == "2"){
                        $link = '../UPLOADS/DCCH/Temp/Caja_'. $idcaja. '/' . $row->ruta;
                    } else{
                        if(file_exists('./UPLOADS/DCCH/Caja_'. $idcaja. '/' . $row->ruta)){
                            $link = '../UPLOADS/DCCH/Caja_'. $idcaja. '/' . $row->ruta;
                        }
                    }

                    $docname = $row->ruta;
                    $docname = $docname ? substr($docname, 0, -4) : $docname;
                }

                $descripcion = preg_replace('/"\d+"\.\s/', '', $row->descripcion);

                $json["data"][] = array(
                    "iddocumento" => $row->iddocumento,
                    "link" => $link,
                    "desc" => $descripcion,
                    "ndocumento" => $docname,
                    "ubicacion" => $row->ubicacion, /** FECHA: 29-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                    "estatus" => $row->estatus, /** FECHA: 29-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                    "idDoc" => $row->idDoc, /** FECHA: 29-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                );
            }
        }
        // $respuesta['documentos'] = $data;
        echo json_encode(["res"=> true, "docs"=>$json["data"]]);
    }
    function historial_incrementos(){
        $data = $this->db->query("SELECT idcaja, anterior, nuevo, fmovimiento, observacion FROM log_cajasch WHERE campo = 'monto' AND idcaja = ? ORDER BY idlog DESC",[$_POST["idcaja"]])->result_array();
        if(!empty($data)){
            echo json_encode(["res"=> true, "info"=>$data]);
        }else{
            echo json_encode(["res"=> false]);
        }
    }
    public function busca_empresas(){
        echo json_encode([ "res" => true , "data" => $this->M_cajach->busca_emp($_POST["idcontrato"])->result_array()]);
    }

    function historialGeneral(){
        $empresas = $this->db->query("SELECT log_cajasch.idcaja, log_cajasch.tipo, log_cajasch.campo, log_cajasch.idcaja, log_cajasch.anterior, log_cajasch.nuevo, log_cajasch.fmovimiento, log_cajasch.observacion,
                                    CONCAT(usuarios.apellidos, ' ', usuarios.nombres) AS nombreUsuario
                                    FROM log_cajasch
                                    LEFT JOIN usuarios on log_cajasch.idusuario = usuarios.idusuario 
                                    WHERE log_cajasch.idcaja = ?
                                    AND log_cajasch.campo LIKE '%empresa%'
                                    ORDER BY log_cajasch.fmovimiento DESC", [$_POST["idcaja"]]);

        /** FECHA: 29-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
        $documentos = $this->db->query("SELECT log_cajasch.idusuario, log_cajasch.idcaja, log_cajasch.tipo, log_cajasch.campo, log_cajasch.idcaja, log_cajasch.anterior, log_cajasch.nuevo, log_cajasch.fmovimiento, log_cajasch.observacion,
                                    CONCAT(usuarios.apellidos, ' ', usuarios.nombres) AS nombreUsuario
                                    FROM log_cajasch
                                    LEFT JOIN usuarios on log_cajasch.idusuario = usuarios.idusuario 
                                    WHERE log_cajasch.idcaja = ?
                                    AND log_cajasch.campo = 'documento'
                                    ORDER BY log_cajasch.fmovimiento DESC", [$_POST["idcaja"]]);


        $historial = ["empresas" => false, "documentos" => false];

        if($empresas->num_rows() > 0){
            $dataEmpresas = array();
            $auxEmpresas = array();
            foreach ($empresas->result_array() as $key => $row)
            {
                if ($row['anterior'] != null && $row['nuevo'] != null) {
                    $buscarEmpresaAnterior =  $this->db->query("SELECT idempresa, abrev from empresas where idempresa = ?", $row['anterior'])->result_array();
                    $auxEmpresas['tipo'] = "SE ELIMINÓ EMPRESA: <b>". $buscarEmpresaAnterior[0]["abrev"] . "</b>";
                }else{
                    $buscarEmpresaNueva =  $this->db->query("SELECT idempresa, abrev from empresas where idempresa = ?", $row['nuevo'])->result_array();
                    $auxEmpresas['tipo'] = "SE AGREGÓ EMPRESA: <b>". $buscarEmpresaNueva[0]["abrev"]."</b>";
                }
                $auxEmpresas['fmovimiento'] = $row['fmovimiento'];
                $auxEmpresas['observacion'] = $row['observacion'];
                $auxEmpresas['nombreUsuario'] = $row['nombreUsuario'];
                array_push($dataEmpresas, $auxEmpresas);
            }
            $historial["empresas"] = $dataEmpresas;
        }

        if($documentos->num_rows() > 0){
            $dataDocumentos = array();
            $auxDocumentos = array();
            foreach ($documentos->result_array() as $key => $row)
            {
                if ($row['tipo'] == "update") {
                    $auxDocumentos['tipo'] = "SE ACTUALIZO DOCUMENTO: <b>". $row['anterior'] . "</b><br> POR: <b>". $row['nuevo'] . "</b>";
                }
                if ($row['tipo'] == "insert") {
                    if ($row['idusuario'] == 2259) { /** INICIO FECHA: 29-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                        $auxDocumentos['tipo'] = "DOCUMENTO: <br><b>". $row['nuevo'] . "</b>";
                    }else{
                        $auxDocumentos['tipo'] = "SE AGREGÓ DOCUMENTO: <br><b>". $row['nuevo'] . "</b>";
                    } /** FIN FECHA: 29-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                }
                if ($row['tipo'] == "close") {
                    $auxDocumentos['tipo'] = "<label style='color: #762129;'>CIERRE DE CAJA:</label> <br><b>". $row['nuevo'] . "</b>";
                }
                if ($row['tipo'] == "delete") {
                    $auxDocumentos['tipo'] = "<label style='color: #762129;'>SE ELIMINO DOCUMENTO:</label> <br><b>". $row['anterior'] . "</b>";
                }

                $auxDocumentos['fmovimiento'] = $row['fmovimiento'];
                $auxDocumentos['observacion'] = $row['observacion'];
                $auxDocumentos['nombreUsuario'] = $row['nombreUsuario'];
                array_push($dataDocumentos, $auxDocumentos);
            }
            $historial["documentos"] = $dataDocumentos;
        }

        echo json_encode($historial);
    }

    public function delete_doc(){
        $observacion = $_POST['observacion'];
        $ubicacion = $_POST['ubicacion'];
        $idDoc = $_POST['idDoc'];
        $idcaja = $_POST['idCaja'];
        $estatus = $_POST['estatus'];

        $motivo_documento = $_POST['motivo_documento_delete'];

        $ids = array_column($this->tipos, 'idMotivo');
        $index = array_search($motivo_documento, $ids);

        if ($index !== false) {
            $motivo_documento = $this->tipos[$index]['motivo'];
        } else {
            $res = ["res" => 0, "respuesta" => "NO SE ENCONTRO MOTIVO DE LA ACTUALIZACIÓN"];
            echo json_encode($res);
            exit;
        }

        $comentario = strtoupper($motivo_documento . ($observacion ? ': ' . $observacion : ''));

        $dir = dirname($_SERVER['SCRIPT_FILENAME']);
        $rutadir = $dir . '/UPLOADS/DCCH/Caja_';
        
        $res = ["res"=> 0];

        if ($ubicacion === 'dropbox') {
            $buscar = $this->db->query( //Buscar documento en historial_documento
                "SELECT iddoc_cajach AS idDoc, estatus, iddocumento, IF(nombre_documento IS NOT NULL, nombre_documento, ruta) AS expediente, 'dropbox' AS ubicacion
                FROM cajach_documentos 
                WHERE idcaja = ?
                AND iddoc_cajach = ?
                AND estatus >= 1
                ", array($idcaja, $idDoc)
            )->row();

            $expediente = $buscar->expediente;

            if (strpos($expediente, '/') !== false) {
                $expediente = str_replace('/CAJAS_CHICAS/125/', '', $expediente);
            }else{
                $expediente = $buscar->expediente;
            }

            if($buscar){ //Si se encuentra
                $mensaje = '"'. $idDoc. '". ' . $comentario;
                $this->db->query( //Actualizar estatus Actualizar Estatus Doc(BAJA LOGICA)
                    "UPDATE cajach_documentos SET estatus = 4, descripcion = '$mensaje'
                     WHERE idcaja = ?
                     AND iddoc_cajach = ?
                     AND estatus >= 1", array($idcaja, $idDoc)
                ); 
                // $nombreArchivo = str_replace("/CAJAS_CHICAS/".$idcaja."/", "", $buscar->expediente);
                
                $this->M_cajach->insert_logCaja($idcaja,"delete",$expediente,"","documento", $comentario); //Insertar log
                $res["res"] = 1;
                echo json_encode($res);
                exit;
    
            }else{
                echo json_encode($res);
                exit;
            }
        }else if ($ubicacion === 'local') {
            $buscar = $this->db->query(
                "SELECT idDocumento as idDoc, estatus, tipo_doc as iddocumento, expediente, 'local' AS ubicacion
                FROM historial_documento 
                WHERE idSolicitud = ?
                AND idDocumento = ?
                AND estatus >= 1", array($idcaja, $idDoc)
            )->row();

            if($buscar){
                $mensaje = '"'. $idDoc. '". ' . $comentario;
                $this->db->query( //Actualizar estatus Actualizar Estatus Doc (BAJA LOGICA)
                    "UPDATE historial_documento SET estatus = 4, movimiento = '$mensaje'
                     WHERE idSolicitud = ?
                     AND idDocumento = ?
                     AND estatus >= 1", array($idcaja, $idDoc)
                );

                $this->M_cajach->insert_logCaja($idcaja,"delete",$buscar->expediente,"","documento", $comentario); //Insertar log
                $res["res"] = 1;
                echo json_encode($res);
                exit;
            }else{
                echo json_encode($res);
                exit;
            }
        }else{
            $res["res"] = 0;
            echo json_encode($res);
            exit;
        }
    }

    public function add_doc(){
        $numDoc = 0;
        $res = ["res"=> 0];
        
        $name = strtoupper($_POST["docname"]) . ".pdf";

        $dir = dirname($_SERVER['SCRIPT_FILENAME']);
        $rutadir = $dir . '/UPLOADS/DCCH/Caja_';
        $idcaja = $_POST["idcaja"];
        $observacion = strtoupper($_POST["observacion_crrfile_addoc"]);

        if(isset($_FILES) && !empty($_FILES) ){
        
            foreach ($_FILES as $valor) {
                $numDoc++;

                if (!file_exists($rutadir . $idcaja . "/". $name)) {
                    $config['upload_path'] = './UPLOADS/';
                    $config['allowed_types'] = 'pdf';
                    $this->load->library('upload', $config);

                    $ruta_pdf = './UPLOADS/DCCH/Caja_' . $idcaja . '/';
                    if (!is_dir($ruta_pdf)) {
                        mkdir($ruta_pdf, 0777, true); // 0777 otorga permisos completos, ajusta según tus necesidades
                    }

                    $extensionPDF = pathinfo($_POST["docname"], PATHINFO_EXTENSION);
                    $resultado = $this->upload->do_upload('crrfile2', $config);
                    
                    if( $resultado ){  

                        $pdf_subido = $this->upload->data();
                        $nuevo_nombre_pdf = strtoupper($_POST["docname"]);
                        
                        
                        $nueva_ruta = $ruta_pdf.$nuevo_nombre_pdf.'.pdf';

                        if(rename($pdf_subido['full_path'], $nueva_ruta)){
                            $arrayPdf = array(
                                "movimiento" => 'Documento agregado a registros de caja chica',
                                "expediente" => $nuevo_nombre_pdf.'.pdf',
                                "modificado" => date('Y-m-d H:i:s'),
                                "estatus" => 1,
                                "idSolicitud"  => $idcaja,
                                "idUsuario" => $this->session->userdata("inicio_sesion")['id'],
                                "tipo_doc" => 5 
                            );
                            
                            $this->M_cajach->insertPdfSol($arrayPdf);

                            $this->M_cajach->insert_logCaja($idcaja,"insert","",$nuevo_nombre_pdf.'.pdf',"documento", $observacion); /** INICIO FECHA: 29-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/

                            // update estatus de caja chica
                            $dataUpdate = [ "estatus"=> 1, 
                                            "fmodifica"=> date('Y-m-d H:i:s'),
                                            "idmodifica"=> $this->session->userdata("inicio_sesion")['id']];
        
                            $this->db->update("cajas_ch", $dataUpdate, "idcaja = '".$idcaja."'");
                        }

                    }else{
                        $mensaje = $this->upload->display_errors();
                        $res =["res" => 0, "respuesta" => $mensaje];
                        echo json_encode($res);
                    }

                    $res =["res"=>1];
                }else{
                    $res =["res"=>2];
                }
            } 
            
        }
        echo json_encode($res);
    }

    /** INICIO FECHA: 29-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
    public function aprobar_documentos() { 
        $idDoc = $this->input->post("idDoc");
        $ubicacion = $this->input->post("ubicacion");
        $idcaja = $this->input->post("idCaja");
        $estatusDoc = $this->input->post("estatusDoc");

        if (empty($idDoc) || empty($ubicacion)) {
            $this->responder("NO SE HA PODIDO COMPLETAR LA SOLICITUD.");
            return;
        }

        switch ($ubicacion) {
            case "dropbox":
                $this->aprobarDocumentoDropbox($idDoc, $idcaja, $estatusDoc);
                break;
            case "local":
                $this->aprobarDocumentoLocal($idDoc, $idcaja, $estatusDoc);
                break;
            default:
                $this->responder("UBICACIÓN NO VÁLIDA.");
                break;
        }
    }

    private function aprobarDocumentoDropbox($idDoc, $idcaja, $estatusDoc) {
        $sql = $this->db->query(
            "SELECT COUNT(*) AS total, cajach_documentos.ruta, cajach_documentos.estatus
            FROM cajach_documentos
            WHERE iddoc_cajach = ?
            AND ruta NOT LIKE './UPLOADS/%'
            AND estatus = ?", 
            array($idDoc, $estatusDoc)
        )->row();

        if (!$sql || $sql->total == 0) {
            $this->responder("NO SE HA PODIDO ENCONTRAR DOCUMENTO.");
            return;
        }

        if (!preg_match('/[^\/]+$/', $sql->ruta, $coincidencias)) {
            $this->responder("NO SE HA PODIDO OBTENER NOMBRE DEL DOCUMENTO.");
            return;
        }

        $docname = $coincidencias[0];
        $nuevoEstatus = ($sql->estatus == 4) ? 0 : 1;
        $dataUpdate = ["estatus" => $nuevoEstatus];
        $update = $this->db->update("cajach_documentos", $dataUpdate, ["iddoc_cajach" => $idDoc]);
        $txtConfirm = ($sql->estatus == 4) ? '¡DOCUMENTO APROBADO ELIMINADO!' : '¡DOCUMENTO APROBADO!';

        if (!$update) {
            $this->responder("NO SE HA PODIDO COMPLETAR LA APROBACIÓN.");
            return;
        } else {
            $this->M_cajach->insert_logCaja($idcaja, "insert", '', $docname, "documento", $txtConfirm);
            $this->responder(null, true);
        }
    }

    private function aprobarDocumentoLocal($idDoc, $idcaja, $estatusDoc) { 
        $sql = $this->db->query(
            "SELECT COUNT(*) AS total, hd.expediente, hd.idDocumento, hd.estatus
            FROM historial_documento hd
            WHERE hd.tipo_doc IN (5, 7, 8)
            AND hd.idDocumento = ?
            AND hd.estatus = ?", 
            array($idDoc, $estatusDoc)
        )->row();
        
        if (!$sql || $sql->total == 0) {
            $this->responder("NO SE HA PODIDO ENCONTRAR DOCUMENTO.");
            exit;
        }

        $nombreArchivoTemp = './UPLOADS/DCCH/Temp/Caja_' . $idcaja . '/' . $sql->expediente; //Verificar si hubo un cambio de archivo

        if ($sql->estatus !== "4"){ // Aprobar Cambio de Archivo
            if (file_exists($nombreArchivoTemp)) {
                $nueva_ruta = './UPLOADS/DCCH/Caja_' . $idcaja . '/' . $sql->expediente; //Quitar de Temp y Mover a Carpeta Caja_$idcaja
                if (rename($nombreArchivoTemp, $nueva_ruta)) {
                    touch($nueva_ruta, time());
                }else{
                    $this->responder("NO SE LOGRO RENOMBRAR ARCHIVO.");
                    return;   
                }
            }else{
                $this->responder("NO SE ENCUENTRA ARCHIVO EN SERVIDOR.");
                return;
            }
        }

        if ($sql->estatus == "4") { // Aprobar Eliminacion

            $dir = dirname($_SERVER['SCRIPT_FILENAME']);
            $rutadir = $dir . '/UPLOADS/DCCH/Caja_';
            $docEliminarName = $rutadir . $idcaja . "/" . $sql->expediente;
        
            if (file_exists($docEliminarName)) { // BAJA FISICA
                unlink($docEliminarName);
            }else{
                $this->responder("NO SE ENCUENTRA ARCHIVO EN SERVIDOR.");
                return;
            }
        }

        $nuevoEstatus = ($sql->estatus == 4) ? 0 : 1; //0 baja, 1 activo, 2 validacion, 3 rechazo, 4 baja logica
        $dataUpdate = [
            "estatus" => $nuevoEstatus
            , "modificado" => date('Y-m-d H:i:s')
            , "idmodificado" => $this->session->userdata("inicio_sesion")['id']
            , "movimiento" => ($sql->estatus == 4 ) ? 'Documento eliminado de registros de caja chica' : 'Documento agregado a registros de caja chica'
        ];

        $update = $this->db->update("historial_documento", $dataUpdate, ["idDocumento" => $idDoc]);
        $txtConfirm = ($sql->estatus == 4) ? '¡DOCUMENTO APROBADO ELIMINADO!' : '¡DOCUMENTO APROBADO!';
        
        if (!$update) {
            $this->responder("NO SE HA PODIDO COMPLETAR LA APROBACIÓN.");
            return;
        }
        
        $this->M_cajach->insert_logCaja($idcaja, "insert", '', $sql->expediente, "documento", $txtConfirm);
        $this->responder(null, true);
    }
    
    public function rechazar_documentos() {
        $idDoc = $this->input->post("idDoc");
        $ubicacion = $this->input->post("ubicacion");
        $idcaja = $this->input->post("idCaja");
        $estatusDoc = $this->input->post("estatusDoc");

        if (empty($idDoc) || empty($ubicacion)) {
            $this->responder("NO SE HA PODIDO COMPLETAR LA SOLICITUD.");
            return;
        }
    
        switch ($ubicacion) {
            case "dropbox":
                $this->rechazarDropbox($idDoc, $idcaja, $estatusDoc);
                break;
            case "local":
                $this->rechazarLocal($idDoc, $idcaja, $estatusDoc);
                break;
            default:
                $this->responder("UBICACIÓN NO VÁLIDA.");
                break;
        }
    }
    
    private function rechazarDropbox($idDoc, $idcaja, $estatusDoc) {
        $sql = $this->db->query(
            "SELECT COUNT(*) AS total, if(cch.nombre_documento is null , cch.ruta, cch.nombre_documento) as ruta, iddocumento
             FROM cajach_documentos
             WHERE iddoc_cajach = ?
             AND ruta NOT LIKE './UPLOADS/%'
             AND estatus = ?",
            array($idDoc,$estatusDoc)
        )->row();
    
        if (!$sql || $sql->total == 0) {
            $this->responder("NO SE HA PODIDO ENCONTRAR DOCUMENTO.");
            return;
        }

        if ($sql->iddocumento == "60") {
            $descripcion = "Documento de cierre para caja chica";
        }else if ($sql->iddocumento == "2"){
            $descripcion = "Documento de autorizacion para caja chica";
        }
    
        if (preg_match('/[^\/]+$/', $sql->ruta, $coincidencias)) {
            $docname = $coincidencias[0];
        }else{
            $docname = $sql->ruta;
        }
    
        $dataUpdate = ["descripcion" => $descripcion, "estatus" => 3];
    
        if ($this->db->update("cajach_documentos", $dataUpdate, ["iddoc_cajach" => $idDoc])) {
            $this->M_cajach->insert_logCaja($idcaja, "insert", '', $docname, "documento", '¡DOCUMENTO RECHAZADO!');
            $this->responder(null, true);
            return;
        } else {
            $this->responder("NO SE HA PODIDO COMPLETAR LA OPERACIÓN RECHAZADA.");
        }
    }
    
    private function rechazarLocal($idDoc, $idcaja, $estatusDoc) {
        $archivoActual = $this->db->query(
            "SELECT hd.idDocumento as idDoc, hd.expediente, hd.movimiento, hd.estatus
             FROM historial_documento hd
             WHERE tipo_doc IN (5, 7, 8)
             AND idDocumento = ?
             AND estatus <> 0", array($idDoc)
        )->row();

        preg_match('/"([^"]+)"/', $archivoActual->movimiento, $idDocumentoDevolver);

        $idDevolver = NULL;
        if (isset($idDocumentoDevolver[1])) {
            $idDevolver = $idDocumentoDevolver[1];
        }

        if(empty($idDevolver)){
            $this->responder("NO SE PUEDE RECHAZAR ESTE DOCUMENTO.");
            exit;
        }

        $recuperarArchivoAnterior  = $this->db->query(
            "SELECT hd.idDocumento as idDoc, hd.expediente, hd.estatus, 'local' AS ubicacion
             FROM historial_documento hd
             WHERE 
             tipo_doc IN (5, 7, 8)
             AND 
             idDocumento = ?
             AND estatus IN (0, 4)
             UNION
             SELECT cch.iddoc_cajach as idDoc, if(cch.nombre_documento is null , cch.ruta, cch.nombre_documento) as expediente, cch.estatus, 'dropbox' AS ubicacion	
             FROM cajach_documentos cch
             WHERE estatus IN (0, 4)
             AND iddoc_cajach = ?", array($idDevolver, $idDevolver)
        )->row();
    
        if (!$archivoActual) {
            $this->responder("NO SE HA PODIDO ENCONTRAR DOCUMENTO.");
            exit;
        }

        if($recuperarArchivoAnterior->estatus === '4'){
            $dataUpdate = ["estatus" => 3, "modificado" => date('Y-m-d H:i:s'), "idmodificado" => $this->session->userdata("inicio_sesion")['id']];
            $this->db->update("historial_documento", $dataUpdate, ["idDocumento" => $idDoc]);

            $this->M_cajach->insert_logCaja($idcaja, "insert", '', $archivoActual->expediente, "documento", '¡DOCUMENTO RECHAZADO!');

            $this->responder(null, true);
            return;
        }

        if($recuperarArchivoAnterior->ubicacion === 'dropbox'){
            if ($recuperarArchivoAnterior->estatus == "0") {
                $dataUpdateAnterior = ["estatus" => 3, "modificado" => date('Y-m-d H:i:s'), "idmodificado" => $this->session->userdata("inicio_sesion")['id']];
                $this->db->update("cajach_documentos", $dataUpdateAnterior, ["iddoc_cajach" => $recuperarArchivoAnterior->idDoc]);
            }

            if ($archivoActual->estatus == "2") {
                $dataUpdate = ["estatus" => 0, "modificado" => date('Y-m-d H:i:s'), "idmodificado" => $this->session->userdata("inicio_sesion")['id']];
                $this->db->update("historial_documento", $dataUpdate, ["idDocumento" => $idDoc]);

                if (file_exists('./UPLOADS/DCCH/Temp/Caja_'.$idcaja.'/'.$archivoActual->expediente)) {
                    unlink('./UPLOADS/DCCH/Temp/Caja_'.$idcaja.'/'.$archivoActual->expediente);
                }
            }

            $this->M_cajach->insert_logCaja($idcaja, "insert", '', $archivoActual->expediente, "documento", '¡DOCUMENTO RECHAZADO!');

            $this->responder(null, true);
            exit;
        }

        if($recuperarArchivoAnterior->ubicacion === 'local'){
            $dataUpdateAnterior = ["movimiento" => "Documento agregado a registros de caja chica.", "estatus" => 3, "modificado" => date('Y-m-d H:i:s'), "idmodificado" => $this->session->userdata("inicio_sesion")['id']];
            if ($this->db->update("historial_documento", $dataUpdateAnterior, ["idDocumento" => $recuperarArchivoAnterior->idDoc])) {

                $dataUpdate = ["estatus" => 0, "modificado" => date('Y-m-d H:i:s'), "idmodificado" => $this->session->userdata("inicio_sesion")['id']];
                $this->db->update("historial_documento", $dataUpdate, ["idDocumento" => $archivoActual->idDoc]);

                $this->M_cajach->insert_logCaja($idcaja, "insert", '', $archivoActual->expediente, "documento", '¡DOCUMENTO RECHAZADO!');

                if (file_exists('./UPLOADS/DCCH/Temp/Caja_'.$idcaja.'/'.$archivoActual->expediente)) {
                    unlink('./UPLOADS/DCCH/Temp/Caja_'.$idcaja.'/'.$archivoActual->expediente);
                }

                $this->responder(null, true);
                return;
            } else {
                $this->responder("NO SE HA PODIDO REESTABLECER ARCHIVO ANTERIOR.");
                return;
            }
        }

    }
    
    private function responder($mensaje, $exito = false) {
        $respuesta = ["res" => $exito, "data" => $mensaje];
        echo json_encode($respuesta);
    }
    /** FIN FECHA: 29-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
}