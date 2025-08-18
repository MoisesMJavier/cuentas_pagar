<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH . "vendor/dropbox/vendor/autoload.php";

use  Kunnu\Dropbox\Dropbox;
use  Kunnu\Dropbox\DropboxApp;
use  Kunnu\Dropbox\DropboxFile;

class Soporte extends CI_Controller
{   
    function __construct(){
        parent::__construct();
        $this->load->model(array('M_opciones', 'Solicitudes_solicitante'));
    }

    function subirarchivo_s()
    {

        /**ACCESO PRUDCCTIVOS**/
        /////////////////////////
        //$app = new DropboxApp("9vkalyc8qnkdyu4", "9z1gmsrap71gnyh", "DHBVzF2_RoQAAAAAAAAAAQTFz_Gj51gd1_aezVei-CJ4qHIVMbHZqFkoAtxObVpK");
        /**ACCESO PRUEBAS**/
        //$app = new DropboxApp("cdhgkbj4jtlhrbi","ikw9v4idmrylcz7","sl.BNfxIz7o-qOJO6dQUF1Goi56wU4g-qcZXvJK02RNzSjsHANMBfdk6Cfqxi-5SlbLtJwRlsOcn0HxRzaVTy2TzUl90bT_E9LG1L9vuer6S_sGr7MLfgAff3BwJUsIcYJfsF_jDoDJmPvZ");
        /////////////////////////
        $app = new DropboxApp("9vkalyc8qnkdyu4", "9z1gmsrap71gnyh", "DHBVzF2_RoQAAAAAAAAAAQTFz_Gj51gd1_aezVei-CJ4qHIVMbHZqFkoAtxObVpK");
        $dropbox = new Dropbox($app);
        $respuesta = ["resultado" => false];
        $idsolicitud = $this->input->post('idsolicitud');
        $this->db->trans_begin();

            if ($_FILES['soportefile']['tmp_name'] != "") {
                $filePath = $_FILES['soportefile']['tmp_name'];
                    $ext = explode(".", $_FILES['soportefile']['name']);
                    $ext = end($ext);
                $fileName = date("Ymd-Hi")."_ID-" . $idsolicitud .  "_" . "Soporte" . "." . $ext;
                try {
                    $dropboxFile = new DropboxFile($filePath);
                        $uploadedFile = $dropbox->upload($dropboxFile, "/SOPORTES_PROV_CCH/$idsolicitud/". $fileName, ['autorename' => true]);
                        $ruta =$uploadedFile->getPathDisplay();
                        $this->M_opciones->doc_soporte($idsolicitud,$ruta );
                        $iddoc_sol = $this->db->insert_id();
                        log_sistema($this->session->userdata("inicio_sesion")['id'], $idsolicitud, 'SE HA AGREGADO ARCHIVO DE SOPORTE');
                        $respuesta = [  
                            "resultado" => true, 
                            "ruta"=> $dropbox->getTemporaryLink($ruta)->getLink(),
                            "idsolicitud" => $idsolicitud ,
                            "iddocumentos_solicitud"=> $iddoc_sol
                        ];
                } catch (DropboxClientException $e) {
                    $respuesta["mensaje"] = $e->getMessage();
                }
            }
            $docsRequest = $this->Solicitudes_solicitante->getDocsRequestSol($idsolicitud);
            if ($docsRequest->num_rows() > 0) {
                $respuesta["mostrarModalEliminacion"] = $docsRequest->row()->idetapa !== "1" && $this->session->userdata("inicio_sesion")['rol'] === "CP" ?  'S' : 'N';
                $respuesta["mostrarBotonEliminacion"] = $docsRequest->row()->idetapa === "1" 
                    ? ($docsRequest->row()->idusuario === $this->session->userdata("inicio_sesion")['id'] 
                        ? 'S' : 'N')
                    : ($this->session->userdata("inicio_sesion")['rol'] == "CP" ?  'S' : 'N');
            }

        if ( $respuesta["resultado"] === FALSE || $this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }

        echo json_encode($respuesta);
    }

    function get_docSoporte(){
        $idsolicitud = $this->input->post('idsolicitud');
        $doc = $this->M_opciones->get_doc($idsolicitud);
        $docsRequest = $this->Solicitudes_solicitante->getDocsRequestSol($idsolicitud);
        if ($docsRequest->num_rows() > 0) {
            $data["mostrarModalEliminacion"] = $docsRequest->row()->idetapa !== "1" && $this->session->userdata("inicio_sesion")['rol'] === "CP" ?  'S' : 'N';
            $data["mostrarBotonEliminacion"] = in_array($docsRequest->row()->idetapa, [1, 2, 3, 5, 8, 6])
                ? ($docsRequest->row()->idusuario === $this->session->userdata("inicio_sesion")['id'] || ($this->session->userdata("inicio_sesion")['rol'] == "CP")
                    ? 'S' : 'N')
                : ($this->session->userdata("inicio_sesion")['rol'] == "CP" ?  'S' : 'N');
        }

        if($doc->num_rows() > 0){
            // $app = new DropboxApp("9vkalyc8qnkdyu4", "9z1gmsrap71gnyh", "DHBVzF2_RoQAAAAAAAAAAQTFz_Gj51gd1_aezVei-CJ4qHIVMbHZqFkoAtxObVpK");
            $app = new DropboxApp("d8iwf1yu17j3dmp","4a7f1p7y5kk4uvg","DHBVzF2_RoQAAAAAAAAAAQTFz_Gj51gd1_aezVei-CJ4qHIVMbHZqFkoAtxObVpK");
            $dropbox = new Dropbox($app);
            $docs =   $doc->row();
                    $ruta = $docs->ruta;
                    try {
                        $links = $dropbox->getTemporaryLink($ruta);
                        $ruta = $links->getLink();
                        $data["doc"]=[
                            "iddocumentos_solicitud" => $docs->iddocumentos_solicitud,
                            "iddocumento" => $docs->iddocumento,
                            "documento" => $docs->ndocumento,
                            "ruta" => $ruta,
                            "idsolicitud" =>  $idsolicitud
                        ];
                    } catch (\Exception $e) {
                        $data['respuesta'] = $e->getMessage();
                    }
            echo json_encode($data);
        }else{
            echo json_encode($data);
        }
    }

    function borrar_doc(){
        if ($this->input->post('tipodoc') == 0) {
            $data =   $this->M_opciones->borrarDocumento($this->input->post('idsol'),$this->input->post('iddoc'));
            log_sistema($this->session->userdata("inicio_sesion")['id'], $this->input->post('idsol'), 'SE ELIMINO ARCHIVO DE SOPORTE '.$this->input->post('justificacion'));
        }else {
            $data = array("modificado" => date('Y-m-d H:i:s'), "estatus" => 0, "movimiento" => "Se elimino documento PDF: ".$this->input->post('justificacion'));
            $data = $this->Solicitudes_solicitante->updateHistorialDocumento($data, $this->input->post('idsol'), $this->input->post('tipodoc'));
            if($this->input->post('tipodoc') == 3 && file_exists('./UPLOADS/AUTSVIATICOS/'.$this->input->post('nombreArchivo'))) unlink('./UPLOADS/AUTSVIATICOS/'.$this->input->post('nombreArchivo'));
            else unlink('./UPLOADS/PDFS/'.$this->input->post('nombreArchivo'));            
            log_sistema($this->session->userdata("inicio_sesion")['id'], $this->input->post('idsol'), 'SE ELIMINO ARCHIVO PDF: '.$this->input->post('justificacion'));
        }
     echo json_encode($data);
    }

    function get_docPdf() {
        $idsolicitud = $this->input->post('idsolicitud');
        $infodoc = $this->Solicitudes_solicitante->getInfoRequestSol($idsolicitud, true);
        $docsRequest = $this->Solicitudes_solicitante->getDocsRequestSol($idsolicitud)->row();
        $arrayData = array();
        $docsRequest->mostrarModalEliminacion = $docsRequest->idetapa > 1 && $this->session->userdata("inicio_sesion")['rol'] == "CP" ?  'S' : 'N';
        $docsRequest->mostrarBotonEliminacion = $docsRequest->idetapa == 1 
            ? ($docsRequest->idusuario === $this->session->userdata("inicio_sesion")['id'] 
                ? 'S' : 'N')
            : ($this->session->userdata("inicio_sesion")['rol'] == "CP" ?  'S' : 'N');
        $archivos['documentosRequired'] = $docsRequest;
        $archivos['archivosInfo'] = array();
        if ($infodoc->num_rows() > 0) {
            foreach ($infodoc->result() as $docs) {
                
                $nombrearchivo = $docs->expediente;
                $pdfOtros = file_exists('./UPLOADS/PDFS/'.$nombrearchivo);
                $viaticos = file_exists('./UPLOADS/AUTSVIATICOS/'.$nombrearchivo);
                
                if ($pdfOtros || $viaticos) {
                    $data['nom_archivo'] = $nombrearchivo;
                    $data['ruta'] = $pdfOtros ? '/UPLOADS/PDFS/' : '/UPLOADS/AUTSVIATICOS/';
                    // $data['ruta'] = $pdfOtros ? basename(getcwd()).'/UPLOADS/PDFS/' : basename(getcwd()).'/UPLOADS/AUTSVIATICOS/';
                    $data["iduser_crea"] = $docs->idUsuario;
                    $data["datos"] = $docs;                         
                }else{
                    $data["datos"] = false;
                    json_encode($data);
                }
                array_push($archivos['archivosInfo'], $data);
                
            }
            array_push($arrayData, $archivos);
            echo json_encode($arrayData);
        }else{
            array_push($arrayData, $archivos);
            echo json_encode($arrayData);
        }
    }

    function descargaPDF($data) {
        $docInfo = json_decode(urldecode($data));
        if(!$docInfo->tipoDocumento) $docInfo->tipoDocumento = 0;
        $rutapdf = $docInfo->tipoDocumento != 3 ? './UPLOADS/PDFS/' : './UPLOADS/AUTSVIATICOS/';
        $archivo = $rutapdf . $docInfo->nombreArchivo;
        if(file_exists($archivo)){
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="' . $docInfo->nombreArchivo . '"');
            readfile($archivo);
            exit;
        }else {
            echo "El archivo no existe";
        }
    }

    function subir_archivos_otros(){
        $nomenclartura = $this->input->post('nomenclatura');
        $proyecto = $this->input->post('proyecto');
        $idSolicitud = $this->input->post('idSolicitud');
        $tipoDocumento = $this->input->post('tipoDocumento');
        $movimiento = $this->input->post('movimiento');

        $respuesta = ["resultado" => false];
        $config['upload_path'] = './UPLOADS/';
        $config['allowed_types'] = 'xml|pdf';
        $this->load->library('upload', $config);
        if((isset($_FILES['pdfFile'])) && array_key_exists('pdfFile', $_FILES)){
            $resultado = $this->upload->do_upload('pdfFile', $config);
            if( $resultado ){
                $pdf_subido = $this->upload->data();
                $ruta_pdf = $tipoDocumento != '3' ? './UPLOADS/PDFS/': './UPLOADS/AUTSVIATICOS/';
                $nuevo_nombre_pdf = $idSolicitud.'_'.$nomenclartura.'_'.   str_replace(' ', '_', $proyecto).'_'.date('Ymd');
                $nueva_ruta = $ruta_pdf.$nuevo_nombre_pdf.'.pdf';
                if(rename($pdf_subido['full_path'], $nueva_ruta)){
                    $arrayPdf = array(
                        "movimiento" => $movimiento,
                        "expediente" => $nuevo_nombre_pdf.'.pdf',
                        "modificado" => date('Y-m-d H:i:s'),
                        "estatus" => 1,
                        "idSolicitud"  => $idSolicitud,
                        "idUsuario" => $this->session->userdata("inicio_sesion")['id'],
                        "tipo_doc" => $tipoDocumento
                    );
                    $idDocumento = $this->Solicitudes_solicitante->insertPdfSol_otros($arrayPdf);
                    
                    if($idDocumento != null) {
                        $respuesta['resultado'] = true;
                        $respuesta['ruta'] = $tipoDocumento != '3' ? basename(getcwd()).'/UPLOADS/PDFS/' : basename(getcwd()).'/UPLOADS/AUTSVIATICOS/';
                        $respuesta['idDocumentoPdfGPND'] = $idDocumento;
                        $respuesta['pdfFileGPND'] = $nuevo_nombre_pdf.'.pdf';
                        $respuesta['usuarioSubioPdfGPND'] = $this->session->userdata("inicio_sesion")['id'];
                    }

                }
            }else{
                $mensaje = $this->upload->display_errors();
            }
        }
        log_sistema($this->session->userdata("inicio_sesion")['id'], $idSolicitud, $movimiento);
        echo json_encode($respuesta);
    }
}