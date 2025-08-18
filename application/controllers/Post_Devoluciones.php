<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH . "vendor/dropbox/vendor/autoload.php";

use  Kunnu\Dropbox\Dropbox;
use  Kunnu\Dropbox\DropboxApp;

class Post_Devoluciones extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        if ($this->session->userdata("inicio_sesion") && in_array($this->session->userdata("inicio_sesion")['rol'], array('CE','CX','FAD','CI')))
            $this->load->model(array('M_Post_Devoluciones'));
        else
            redirect("Login", "refresh");
    }

    public function index(){
        if (in_array($this->session->userdata("inicio_sesion")['rol'], array('CE','CX','FAD','CI'))) {
            $this->load->view("vista_post_devolucion");
        } else {
            redirect("Login", "refresh");
        }
    }

    public function tabla_postdevoluciones(){
        $resultado = $this->M_Post_Devoluciones->get_solicitudes_autorizadas_area();
        
        if ($resultado->num_rows() > 0) {
            $resultado = $resultado->result_array();
            for ($i = 0; $i < count($resultado); $i++) {
                $d = $resultado[$i]['idetapa'] == 1 || $this->M_Post_Devoluciones->docs_proceso_sol_avanzar($resultado[$i]['idsolicitud'], $this->session->userdata("inicio_sesion")['rol'])->row()->requerido == 0 ? 1 : 0 ;
                
                $regr = ($resultado[$i]['idetapa'] == 1  ? 1 : 0); //0  NO avanza, 1 si avanza
                $resultado[$i]['opciones'] = $this->opciones_autorizaciones($resultado[$i]['idsolicitud'], $resultado[$i]['idetapa'], $resultado[$i]['idusuario'], $resultado[$i]['visto'], $resultado[$i]['prioridad'], $resultado[$i]['idAutoriza'], $resultado[$i]['nomdepto'], $resultado[$i]['tipo_prov'], $d, $regr);
                $resultado[$i]['etapa'] .= in_array($resultado[$i]['prioridad'],[2])  ? "<br/><small class='label pull-center bg-red'>RECHAZADA</small>" : "";
            }
        } else {
            $resultado = array();
        }

        echo json_encode(array("data" => $resultado, "por_autorizar" => ''));
    }

    public function opciones_autorizaciones($idsolicitud, $estatus, $autor, $visto, $prioridad, $dg, $departamento, $tipo_prov, $docs, $regre){

        $opciones = '<div class="btn-group-vertical">';

        $opciones .= '<button style="margin:2px;" type="button" class="btn btn-sm btn-primary consultar_modal notification" value="' . $idsolicitud . '" data-value="POST_DEV" title="VER SOLICITUD"><i class="fas fa-eye"></i>' . ($visto == 0 ? '</i><span class="badge">!</span>' : '') . '</button> ';
        if ( TRUE) {
            if ( in_array( $this->session->userdata("inicio_sesion")['rol'], ['AD'] ) && $autor != $this->session->userdata("inicio_sesion")['id'] ) {
                $opciones .= '<button style="margin:2px;" type="button" class="btn btn-sm btn-success avanzar_contable" value="' . $idsolicitud . '" title="ENVIAR SOLICITUD"><i class="fas fa-share-square"></i></button> ';
       
            } else {
                if ( in_array($prioridad, [3, 2]) ) {
                    $opciones .= '<button style="margin:2px;" type="button" class="btn btn-sm btn-success avanzar_lista" value="' . $idsolicitud . '" title="ENVIAR SOLICITUD"><i class="fas fa-share-square"></i></button> ';
                } else if ($prioridad == 0 || $prioridad == NULL ) {
                    $opciones .= '<button style="margin:2px;" type="button" class="btn btn-sm btn-success avanzar" value="' . $idsolicitud . '" title="ENVIAR SOLICITUD"><i class="fas fa-share-square"></i></button> ';
                }
            }
        }
        if ($regre != 1 && !in_array( $prioridad, [0, 2 ]) || $prioridad == NULL) { // && !in_array($estatus,[11,68,71])
            $opciones .= '<button style="margin:2px;" type="button" class="btn btn-sm btn-danger cancelar_sol" value="' . $idsolicitud . '" title="CANCELAR SOL"><i class="fas fa-undo-alt"></i></button> ';
        }

        return $opciones . "</div>";
    }

    function devolucion_sigetapa(){
        //$respuesta = FALSE;
        $respuesta = array("res"=>FALSE, "msn"=>"Ocurrio un Error.");
        if (!empty($_POST)) {
            $idsolicitud = $this->input->post('id_sol_avancom1');
            $comentario = $this->input->post('text_comentario_ava');
            // validar Doc Requerido
            $d = $this->M_Post_Devoluciones->docs_proceso_sol_avanzar($idsolicitud, $this->session->userdata("inicio_sesion")['rol'])->row()->requerido ;
            if($d == 0){
                $this->db->trans_begin();
                $this->load->model(array('M_Devolucion_Traspaso'));
                

                // existe en  post dovolucion? 
                $info_sol = $this->db->query("SELECT * FROM post_devoluciones WHERE idsolicitud = $idsolicitud");

                if ($info_sol->num_rows() > 0) // update post_devoluciones
                    $this->M_Post_Devoluciones->avanzar_update($idsolicitud);
                else                          // insert en post_devoluciones
                    $this->M_Post_Devoluciones->avanzar_insert($idsolicitud);
                

                if( $this->db->affected_rows() > 0 )
                    $this->M_Devolucion_Traspaso->observaciones_doc_descartado($idsolicitud, strtoupper( $comentario ) ); 
                    
                //$respuesta = TRUE;
                $respuesta = array("res"=>TRUE);
                if ($respuesta === FALSE || $this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $respuesta = array("res"=>FALSE, "msn"=>"Ocurrio un Error.");
                } else {
                    $this->db->trans_commit();
                    $respuesta = array("res"=>TRUE);
                }
            }else{
                $respuesta = array("res"=>FALSE, "msn"=>"Antes de avanzar es necesario cargar los documentos requeridos.");
            }
            echo json_encode( $respuesta );
        }
    }

    function avanzar_area_r()
    {
        $respuesta = false;
        if (!empty($_POST)) {
            $orden =  $this->input->post('areas_avanzar');
            $idsolicitud =  $this->input->post('idsol_area');
            $info_sol = $this->db->query("SELECT * FROM post_devoluciones WHERE idsolicitud = $idsolicitud");
            if ($info_sol->num_rows() > 0) {
                $proceso = $info_sol->row()->idproceso;
                //$this->M_Devolucion_Traspaso->proceso_info($proceso, $orden);
                $proceso_info = $this->db->query("SELECT * FROM proceso_etapa WHERE idproceso = $proceso AND orden ='$orden'; ");
                if ($proceso_info->num_rows() > 0) {
                    $nueva_etapa = $proceso_info->row()->idetapa;
                    $prioridad = NULL;
                    $this->M_Post_Devoluciones->actualizar_etapa($nueva_etapa, $idsolicitud, $prioridad);
                    //$this->M_Devolucion_Traspaso->actualizar_etapa($nueva_etapa, $idsolicitud, $prioridad);
                    $comentari = 'SE AUTORIZA';
                    $this->load->model(array('M_Devolucion_Traspaso'));
                    $this->M_Devolucion_Traspaso->observaciones_doc_descartado($idsolicitud, $comentari);
                    $respuesta = TRUE;
                }
            }
        }
        echo json_encode($respuesta);
    }

    function conusltar_areas_proceso_menor(){
        if (!empty($_POST)) {
            $this->load->model(array('M_Devolucion_Traspaso'));
            $idsolicitud = $this->input->post('idsolicitud');
            $info_sol = $this->db->query("SELECT * FROM post_devoluciones WHERE idsolicitud = $idsolicitud");
            $etapa = $info_sol->row()->idetapa;
            $idproceso = $info_sol->row()->idproceso;
            $orden = $this->M_Post_Devoluciones->proceso_etapas_menor($idproceso, $etapa);
            $data_array["data"] = array();
            if ($orden->num_rows() > 0) {
                foreach ($orden->result() as  $value) {
                    $data_array["data"][] = array(
                        "nombre" => $value->etapa_regresar,
                        "solicitud" => $idsolicitud,
                        "idrol" => $value->idrol,
                        "orden" => $value->orden,
                        "idetapa" => $value->idetapa
                    );
                }
            }

            echo json_encode($data_array);
        }
    }

    function conusltar_areas_proceso(){
        if (!empty($_POST)) {
            $this->load->model(array('M_Devolucion_Traspaso'));
            $idsolicitud = $this->input->post('idsolicitud');
            // $orden = $this->M_Devolucion_Traspaso->proceso_etapas( $idsolicitud );
            $orden = $this->M_Post_Devoluciones->proceso_etapas( $idsolicitud );
            $data_array["data"] = array();
            if ($orden->num_rows() > 0) {
                foreach ( $orden->result() as  $value ) {
                    $data_array["data"][] = array(
                        "nombre" => $value->etapa_regresar,
                        "solicitud" => $idsolicitud,
                        "idrol" => $value->idrol,
                        "orden" => $value->orden,
                        "idetapa" => $value->idetapa
                    );
                }
            }else{
                $data_array["data"] = FALSE;
            }

            $data_array["info"] = $this->M_Devolucion_Traspaso->getSolicitudadm( $idsolicitud )->result_array();
            echo json_encode( $data_array );
        }
    }

    function regresar_sol_area(){
        $respuesta = FALSE;
        if (!empty($_POST)) {

            $this->db->trans_begin();
            $this->load->model(array('M_Devolucion_Traspaso'));
            $orden = $this->input->post('radios');
            $text_comentario = $this->input->post('text_comentario');
            $idsolicitud = $this->input->post('solicitud_fom');

            $info_sol = $this->db->query("SELECT * FROM post_devoluciones WHERE idsolicitud = $idsolicitud");

            if ($info_sol->num_rows() > 0) {
                $proceso_info = $this->db->query("SELECT * FROM proceso_etapa WHERE idproceso = ? AND orden ='$orden'; ",[$info_sol->row()->idproceso]);   
                $this->M_Post_Devoluciones->actualizar_etapa($proceso_info->row()->idetapa, $idsolicitud, 2);
           
                $this->M_Devolucion_Traspaso->observaciones_doc_descartado($idsolicitud, 'SE RECHAZA POR: ' . strtoupper($text_comentario));
                
                $respuesta = TRUE;
            }
            if ($respuesta === FALSE || $this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $respuesta = FALSE;
            } else {
                $this->db->trans_commit();
                $respuesta = TRUE;
            }
        }

        echo json_encode($respuesta);
    }

    function borrar_documento(){
        $this->load->model('M_Devolucion_Traspaso');
        $resultado = FALSE;

        if (isset($_POST) && !empty($_POST)) {
            $iddocumento = $this->input->post('iddocumento');
            $comentario = 'SE BORRA DOCUMENTO POR ERROR.';

            $this->M_Devolucion_Traspaso->insertar_comentario_rechazado($comentario, $iddocumento);

            if ($this->db->trans_status() === FALSE && !$resultado) {
                $this->db->trans_rollback();
                $resultado = array("resultado" => FALSE);
            } else {
                $this->db->trans_commit();
                $resultado = array("resultado" => TRUE);
            }
        }
        echo json_encode($resultado);
    }

    function descargar_docs()
    {
        $idsolicitud = $this->input->post('idsolicitud');
        $app = new DropboxApp("9vkalyc8qnkdyu4", "9z1gmsrap71gnyh", "DHBVzF2_RoQAAAAAAAAAAQTFz_Gj51gd1_aezVei-CJ4qHIVMbHZqFkoAtxObVpK");
        $dropbox = new Dropbox($app);
        $docs =  $this->M_Post_Devoluciones->ruta_doc_dropbox($idsolicitud);
        $json["data"] = array();

        if ($docs->num_rows() > 0) {
            foreach ($docs->result() as $row) {
                $ruta = $row->ruta;

                try {
                    $links = $dropbox->getTemporaryLink($ruta);
                    $ruta = $links->getLink();
                } catch (\Exception $e) {
                    $respuesta['respuestas'] = false;
                }
                $json["data"][] = array(
                    "iddocumentos_solicitud" => $row->iddocumentos_solicitud,
                    "iddocumento" => $row->iddocumento,
                    "documento" => $row->ndocumento,
                    "ruta" => $ruta,
                    "idsolicitud" =>  $idsolicitud,
                    "idproceso"=> $row->idproceso
                );
            }
        }

        // $respuesta['documentos'] = $data;
        echo json_encode($json["data"]);
    }

    function agregar_documentos(){
        $this->load->model('M_Devolucion_Traspaso');
        $app = new DropboxApp("9vkalyc8qnkdyu4", "9z1gmsrap71gnyh", "DHBVzF2_RoQAAAAAAAAAAQTFz_Gj51gd1_aezVei-CJ4qHIVMbHZqFkoAtxObVpK");
        $dropbox = new Dropbox($app);
        $respuesta = ["resultado" => false];
        $idsolicitud = $this->input->post('idsolicitud');
        $documentos = $this->M_Post_Devoluciones->get_info_docs($idsolicitud);

        if( $this->M_Post_Devoluciones->validar_solpagos_estatus( $this->session->userdata("inicio_sesion")['rol'], $idsolicitud )->num_rows() > 0 ){ 
            if( $documentos->num_rows() > 0) {
                $this->db->trans_begin();
                foreach ($documentos->result() as $row) {
                    $id = $row->iddocumentos;
                    $nombre_doc = $row->ndocumento;
                    if (isset($_FILES[$id]['tmp_name']) && $_FILES[$id]['tmp_name'] != "") {

                        $tempfile = $_FILES[$id]['tmp_name'];
                        $ext = explode(".", $_FILES[$id]['name']);
                        $ext = end($ext);
                        $nombredropbox = "/DEVOLUCIONES_TRASPASOS/$idsolicitud/" . date('Y') . "_" . date('m') . '_' . date('d') . "_" . date('H') . "_" . date('i') . "_" . date('s') . "_" . $idsolicitud . "_" . str_replace([ ' ', '.' ], [ '_', '_' ],$row->ndocumento . "_" . $row->nombre) . "." . $ext;
                        try {

                            $file = $dropbox->simpleUpload($tempfile, $nombredropbox, ['autorename' => true]);
                            $this->M_Devolucion_Traspaso->insert_documento($id, $idsolicitud, $nombredropbox);
                            $tipo_mov = 'SE HA AGREGADO ' . $nombre_doc;
                            log_sistema($this->session->userdata("inicio_sesion")['id'], $idsolicitud, $tipo_mov);
                            $respuesta = ["resultado" => true];

                        } catch (Exception $e) {
                            $respuesta["mensaje"] = $e;
                            break;
                        }

                    }
                }
                if ( $respuesta["resultado"] === FALSE || $this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                } else {
                    $this->db->trans_commit();
                }
            }
        }else{
            $respuesta = ["resultado" => false, "msj" => "La solicitud ya sido avanzada, no es posible adjuntar mas documentos"];
        }

        echo json_encode($respuesta);
    }

    public function tabla_dev_encurso() {
        echo json_encode(array("data" => $this->M_Post_Devoluciones->get_solicitudes_en_curso($this->input->post("finicial").' 00:00:00',$this->input->post("ffinal").' 23:59:59')->result_array(), "idloguin_usuario" => $this->session->userdata("inicio_sesion")['id']));
    }

    public function tablaHistorial()
    {     // Obtener etapa final del proceso // array(11)
        echo json_encode(array("data" => $this->M_Post_Devoluciones->getHistorialPostDev($this->input->post("activo") > 0 ? "(select idetapa from proceso_etapa where idproceso = solpagos.idproceso AND orden  IN(select MAX(orden) from proceso_etapa where idproceso= solpagos.idproceso ))" : "(30)", $this->input->post("finicial").' 00:00:00',$this->input->post("ffinal").' 23:59:59')->result_array(), "rol" => $this->session->userdata("inicio_sesion")['rol']));
    }
}
