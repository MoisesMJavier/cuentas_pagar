<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Consultar extends CI_Controller
{

    function __construct()
    {
        parent::__construct();

        if (!$this->session->userdata("inicio_sesion"))
            redirect("Login", "refresh");
        else {
            $this->load->model(array("Consulta", "Complemento_cxpModel", "Usuarios_model", "M_Devolucion_Traspaso", "Solicitudes_solicitante"));
        }
    }

    function usuario_disponible()
    {
        if ($this->input->post("usuario")) {
            echo json_encode($this->Usuarios_model->getUsuario($this->input->post("usuario"))->num_rows());
        }
    }


    function nombreBanco_disponible()
    {
        if ($this->input->post("nombreBanco")) {
            echo json_encode($this->Consulta->getnombreBanco($this->input->post("nombreBanco"))->num_rows());
        }
    }


    function clvbanco_disponible()
    {
        if ($this->input->post("clvbanco")) {
            echo json_encode($this->Consulta->getclvbanco($this->input->post("clvbanco"))->num_rows());
        }
    }

    function cuenta_disponible()
    {
        if ($this->input->post("cuenta")) {
            echo json_encode($this->Consulta->getCta($this->input->post("cuenta"))->num_rows());
        }
    }

    function cta_disponible()
    {
        if ($this->input->post("cta")) {
            echo json_encode($this->Consulta->getCtaEmp($this->input->post("cta"),  $this->input->post("emp"))->num_rows());
        }
    }



    function rfc_disponible()
    {
        if ($this->input->post("rfc")) {
            echo json_encode($this->Consulta->getRfc($this->input->post("rfc"))->num_rows());
        }
    }

    function correo_disponible()
    {
        if ($this->input->post("correo")) {
            echo json_encode($this->Usuarios_model->getCorreo($this->input->post("correo"), TRUE)->num_rows());
        }
    }

    function getConversacion() /** INICIO FECHA: 27-FEBRERO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
    {
        $conversacion = [];

        if ($this->input->post("idsolicitud")) {
            $rol_usuario = array("DA" => "DIRECCIÓN DE ÁREA", "AS" => "CAPTURISTA", "CC" => "CAPTURISTA", "CE" => "CAPTURISTA", "CJ" => "CAPTURISTA", "CA" => "CAPTURISTA", "CP" => "CUENTAS POR PAGAR", "CT" => "CONTABILIDAD", "DP" => "DISPERSIÓN DE PAGOS", "SU" => "DIRECCIÓN GENERAL", "SD" => "DIRECCIÓN GENERAL", "DG" => "DIRECCIÓN GENERAL","PV" => "POST VENTA","SPV" => "SUPERVISION POST VENTA");
            $query = $this->Consulta->getConversacion($this->input->post("idsolicitud"));
            $conversacion['comentarios'] = $query->num_rows();
            if ($query->num_rows() > 0) {
                $esContraloria = ($this->session->userdata("inicio_sesion")['depto'] == 'CONTRALORIA');
                foreach ($query->result() as $row) {
                    $esPropio = ($row->idusuario == $this->session->userdata("inicio_sesion")['id']);
                    $clase = $esPropio ? 'msnmio' : 'msndemas';
                    $contenido = '
                        <div class="' . $clase . '">
                            ' . $row->obervacion . '
                            <br>
                            
                        <span class="smspan">
                                ' . $row->nombre_usuario . '<br>';

                    if ($esContraloria) {
                        $contenido .= '
                            <label style="font-size: 9.5px; margin: 0; padding: 0;">' . $row->depto_usuario . '</label>
                        '; 
                    }

                    $contenido .= '
                        </span>
                            <p class="fchp">
                                ' . $row->fecha_formateada . '
                            </p>
                        </div>
                    ';

                    $conversacion['observaciones'][] = $contenido;
                }
            }
        }

        echo json_encode($conversacion);
    } /** FIN FECHA: 27-FEBRERO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/

    function getConversacionDepartamento()/** INICIO FECHA: 27-FEBRERO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
    {
        $conversacion = [];

        $esContraloria = ($this->session->userdata("inicio_sesion")['depto'] == 'CONTRALORIA');

        if(!$esContraloria){
            $contenido = '<center><p>No tienes acceso a esta pestaña.</p></center>';
            $conversacion['observaciones'][] = $contenido;
        }{
            if ($this->input->post("idsolicitud")) {
                $rol_usuario = array("DA" => "DIRECCIÓN DE ÁREA", "AS" => "CAPTURISTA", "CC" => "CAPTURISTA", "CE" => "CAPTURISTA", "CJ" => "CAPTURISTA", "CA" => "CAPTURISTA", "CP" => "CUENTAS POR PAGAR", "CT" => "CONTABILIDAD", "DP" => "DISPERSIÓN DE PAGOS", "SU" => "DIRECCIÓN GENERAL", "SD" => "DIRECCIÓN GENERAL", "DG" => "DIRECCIÓN GENERAL","PV" => "POST VENTA","SPV" => "SUPERVISION POST VENTA");
                $query = $this->Consulta->getConversacion($this->input->post("idsolicitud"));
                $conversacion['comentarios'] = $query->num_rows();
                if ($query->num_rows() < 1) {
                    $contenido = '<center><p>Actualmente no hay observaciones disponibles.</p></center>';
                    $conversacion['observaciones'][] = $contenido;
                }

                if ($query->num_rows() > 0) {
                    // Agrupar observaciones por departamento
                    $departamentos = [];
                    foreach ($query->result() as $row) {
                        $departamentos[$row->depto_usuario][] = $row;
                    }

                    // Ordenar los departamentos alfabéticamente
                    ksort($departamentos);
    
                    // Generar el HTML con Tabs
                    $contenido = '<ul class="nav nav-tabs">';
    
                    $i = 0;
                    foreach ($departamentos as $depto => $observaciones) {
                        $active = ($i === 0) ? 'active' : ''; // La primera pestaña es activa
                        $tabID = "tab" . $i;
                        $contenido .= '<li class="' . $active . '"><a href="#' . $tabID . '" data-toggle="tab">' . $depto . '</a></li>';
                        $i++;
                    }
    
                    $contenido .= '</ul>'; // Cierra la lista de tabs
    
                    $contenido .= '<div class="tab-content">';

                    $i = 0;
                    foreach ($departamentos as $depto => $observaciones) {
                        $active = ($i === 0) ? 'active' : ''; // La primera pestaña es activa
                        $tabID = "tab" . $i;
                        $contenido .= '<div class="tab-pane fade in ' . $active . '" id="' . $tabID . '">';

                        // Agregamos la clase `chat` para hacer scroll dentro de la pestaña
                        $contenido .= '<div class="chat">';
    
                        $total = count($observaciones);
                        $contador = 0;

                        foreach ($observaciones as $row) {
                            $esPropio = ($row->idusuario == $this->session->userdata("inicio_sesion")['id']);
                            $clase = $esPropio ? 'msnmio' : 'msndemas';
                            $contenido .= '
                                <div class="' . $clase . '">
                                    ' . $row->obervacion . '
                                    <br>
                                    <span class="smspan">
                                        ' . $row->nombre_usuario . '<br>
                                        <label style="font-size: 9.5px; margin: 0; padding: 0;">' . $row->depto_usuario . '</label>
                                    </span>
                                    <p class="fchp">
                                        ' . $row->fecha_formateada . '
                                    </p>
                                </div>';
    
                            if (++$contador < $total) {
                                $contenido .= '<hr>';
                            }
                        }

                        $contenido .= '</div>'; // Cierra div.chat
                        $contenido .= '</div>'; // Cierra tab-pane

                        $i++;
                    }
    
                    $contenido .= '</div>'; // Cierra tab-content
    
                    $conversacion['observaciones'][] = $contenido;
                }
            }
        }

        echo json_encode($conversacion);
    } /** FIN: 27-FEBRERO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/

    function agregar_comentario() /** INICIO FECHA: 27-FEBRERO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
    {
        chat($this->input->post("idsolicitud"), $this->input->post("comentario"), $this->session->userdata("inicio_sesion")['id']);
        $rol_usuario = array("DA" => "DIRECCIÓN DE ÁREA", "AS" => "CAPTURISTA", "CC" => "CAPTURISTA", "CE" => "CAPTURISTA", "CJ" => "CAPTURISTA", "CA" => "CAPTURISTA", "CP" => "CUENTAS POR PAGAR", "CT" => "CONTABILIDAD", "DP" => "DISPERSIÓN DE PAGOS", "SU" => "DIRECCIÓN GENERAL", "SD" => "DIRECCIÓN GENERAL", "DG" => "DIRECCIÓN GENERAL", "PV" => "POST VENTA" , "SPV" => "SUPERVISION POST VENTA");

        $usuario = $this->session->userdata("inicio_sesion")['nombres'] . ' ' . $this->session->userdata("inicio_sesion")['apellidos'];

        if ($this->session->userdata("inicio_sesion")['depto'] === 'CONTRALORIA') {
            $chat = '<div class="msnmio">
                '. $this->input->post("comentario") .'
                <br>
                <span class="smspan">
                    '. $usuario .' <br>
                    <label style="font-size: 9.5px; margin: 0; padding: 0;">'.$this->session->userdata("inicio_sesion")['depto'].'</label>
                </span>
                <p class="fchp">
                    '. date("h:i:s d-m-Y") .'
                </p>
            </div>';
        } else {
            $chat = '<div class="msnmio">' .$this->input->post("comentario"). '<br><span class="smspan">' .$usuario. ' - ' .date("h:i:s d-m-Y"). '</span></div>';
        }

        echo json_encode(
            array(
                "mensaje" => $chat
            )
        );
    } /** FIN FECHA: 27-FEBRERO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/

    function solicitud($id, $tipo_consulta)
    {

        if(in_array( $tipo_consulta, array( "SOL", "PAG", "BAS", "FAC","FACU", "DEV", "DEV_BASICA","POST_DEV" ) )){

            $datos['datos_solicitud_array'] = null;

            if( $tipo_consulta == "PAG" ){
                $datos['datos_pago'] = $this->Consulta->factura_pago( $id )->row();//
                $id = $datos['datos_pago']->idsolicitud;
            } else if($tipo_consulta == "FAC" || $tipo_consulta == "FACU" ){
                
                $datos['datos_solicitud'] = $this->Consulta->factura_byuuid( $id, $tipo_consulta == "FAC")->row();
                $id = empty($datos['datos_solicitud']->idsolicitud) ? "" : $datos['datos_solicitud']->idsolicitud;
                $datos['datos_solicitud_array']=$this->Consulta->factura_solicitud( $id );

            } else if ($tipo_consulta == "DEV" || $tipo_consulta == "DEV_BASICA" || $tipo_consulta == "POST_DEV" ) {
                $datos['datos_solicitud'] = $this->M_Devolucion_Traspaso->getSolicitudadm($id)->row();
                $datos['datos_adicionales'] = $this->Consulta->devolucion_solicitud( $id );
                $datos['tipo_consulta']=$tipo_consulta;
            }else{
                $resultado = $this->Consulta->factura_solicitud( $id );
                $datos['datos_solicitud_array']= $resultado;
                $datos['datos_solicitud'] = $resultado->row();
            }  

            $datos['idsolicitud'] = $id;

            /** INICIO FECHA: 14-MARZO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
            $historial = json_decode( json_encode( $this->Consulta->Movimientos_Solicitud( $id )->result_array()));
            $dataMovimientos = '';
            $i = 0;
            $total = count($historial);
            if (!empty($historial)) {
                foreach ($historial as $movimiento) {
                    $dataMovimientos .= '<div class="row">
                                            <div class="col-lg-12">
                                                <h5>
                                                    <label class="text-primary">' . date_format(date_create($movimiento->fecha), 'd-m-Y H:i:s') . '</label>  
                                                    <b>' . $movimiento->nombre_completo;
            
                    // Verificar si el usuario tiene el rol y el departamento requerido
                    if (in_array($this->session->userdata("inicio_sesion")['rol'], array('CI', 'DA')) && in_array($this->session->userdata("inicio_sesion")['depto'], array('CONTRALORIA'))) {
                        $dataMovimientos .= ' (<label style="color: #675521">' . $movimiento->depto . '</label>):</b>';
                        $dataMovimientos .= '<p style="margin:0; padding:0;">' . $movimiento->tipomov . '</p>
                                                </h5>
                                            </div>
                                          </div>';
                    } else {
                        $dataMovimientos .= '</b> - ' . $movimiento->tipomov . '</h5>
                                            </div>
                                          </div>';
                    }            
                    // Agregar <hr> si no es el último registro
                    if ($i < $total - 1) {
                        $dataMovimientos .= '<hr style="margin:0; padding:0;">';
                    }
            
                    $i++;
                }
            } else {
                $dataMovimientos = '<h5 class="text-red">SIN MOVIMIENTOS REALIZADOS</h5>';
            }
            $datos['movimientos'] = $dataMovimientos;
            /** FIN FECHA: 14-MARZO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/

            if( isset($datos['datos_solicitud']->nombre_archivo) && file_exists ( "./UPLOADS/XMLS/".$datos['datos_solicitud']->nombre_archivo )  ){
                $datos['datos_factura'] = $this->Complemento_cxpModel->leerxml( "./UPLOADS/XMLS/".$datos['datos_solicitud']->nombre_archivo, FALSE );
            }

            $datos['notificacion'] = $this->Consulta->get_notificacion( $id, $this->session->userdata("inicio_sesion")['id'] );
            $this->Consulta->leer_notificacion( $id, $this->session->userdata("inicio_sesion")['id'] );

            $existe_historial_doc = $this->Solicitudes_solicitante->getInfoRequestSol($id);
            $datos['pdf_gastos_caja_chica'] = '';
            if (!empty($existe_historial_doc->result_array())) {
                $datos_historial_doc = $existe_historial_doc->result_array();
                $datos['pdf_gastos_caja_chica'] = file_exists("./UPLOADS/PDFS/".$datos_historial_doc[0]['expediente']) && ($datos_historial_doc[0]['tipo_doc'] == 2) && ($datos['datos_solicitud']->caja_chica == 1)
                    ? $datos_historial_doc[0]['expediente']
                    : FALSE;
            }
            
            switch( $tipo_consulta ){
                case 'SOL':
                case 'FAC':
                case 'FACU':
                    (empty($datos['datos_solicitud']))
                    ? null
                    : $this->load->view( "consultar_solicitud", $datos );
                    break;
                case 'BAS':
                	$this->load->view( "consulta_basica", $datos );
                    break;
                case 'POST_DEV':    
                case 'DEV':
                    $this->load->view("consulta_basica_dev", $datos);
                    break;
                case 'DEV_BASICA':
                    $this->load->view("consulta_basica_dev_basica", $datos);
                    break;
                case 'PAG':
                    $this->load->view( "consultar_conta", $datos );
                    break;
            }
        }
    }
    function documentos_sol()
    {
        $idsolicitud = $this->input->post('idsolicitud');
        // $respuesta = false;

        // $respuesta['documentos'] = $this->M_Devolucion_Traspaso->get_consultar_docs($idsolicitud)->result();

        $json["data"] = array();

        $query = $this->M_Devolucion_Traspaso->docs_proceso_sol($idsolicitud)->result();
        foreach ($query as $row) {

            // if( $row->estatus == 9){
            //     $json["data"][] = array(
            //         "alias" => $row->alias,
            //         "estatus" => $row->estatus,
            //         "iddocumentos" => $row->iddocumentos,
            //         "idproceso" => $row->idproceso,
            //         "idsolicitud" => $row->idsolicitud,
            //         "ndocumento" => $row->ndocumento,
            //         "nombre" => $row->nombre,
            //         "proyecto" => $row->proyecto,
            //         "rfc" => $row->rfc
            //     );
            // }
            $json["data"][] = array(
                "alias" => $row->alias,
                "estatus" => $row->estatus,
                "iddocumentos" => $row->iddocumentos,
                "idproceso" => $row->idproceso,
                "idsolicitud" => $row->idsolicitud,
                "ndocumento" => (in_array($row->idproceso, [26, 27, 28, 29]) && $row->iddocumentos == 15)  ? 'CARTA DE TRÁMITE' : $row->ndocumento,
                "nombre" => $row->nombre,
                "proyecto" => $row->proyecto,
                "rfc" => $row->rfc,
                "req" => $row->doc_req,
            );
        }


        // $respuesta['documentos'] = $data;
        echo json_encode($json["data"]);
    }

    function doc_sol_post(){
        $this->load->model("M_Post_Devoluciones");
        $idsolicitud = $this->input->post('idsolicitud');

        $json["data"] = array();

        $query = $this->M_Post_Devoluciones->docs_proceso_sol($idsolicitud)->result();
        foreach ($query as $row) {
            $json["data"][] = array(
                "alias" => $row->alias,
                "estatus" => $row->estatus,
                "iddocumentos" => $row->iddocumentos,
                "idproceso" => $row->idproceso,
                "idsolicitud" => $row->idsolicitud,
                "ndocumento" => (in_array($row->idproceso, [26, 27, 28, 29]) && $row->iddocumentos == 15)  ? 'CARTA DE TRÁMITE' : $row->ndocumento,
                "nombre" => $row->nombre,
                "proyecto" => $row->proyecto,
                "rfc" => $row->rfc,
                "req" => $row->doc_req,
            );
        }

        echo json_encode($json["data"]);
    }



    function documentos_autorizacion()
    {
        set_time_limit(60); //Otorga mas tiempo de respuesta a la BD
        $this->load->library('Pdf');
        $pdf = new TCPDF('P', 'mm', 'LETTER', 'UTF-8', false);

        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Sistemas Victor Manuel Sanchez Ramirez');
        $pdf->SetTitle('DOCUMENTO RELACIÓN GASTOS');
        $pdf->SetSubject('DOCUMENTO RELACIÓN GASTOS POR ÁREA');
        $pdf->SetKeywords('DOCUMENTO, CIUDAD MADERAS, ÁREA, EMPRESA, GASTOS');
        // se pueden modificar en el archivo tcpdf_config.php de libraries/config
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // se pueden modificar en el archivo tcpdf_config.php de libraries/config
        $pdf->SetAutoPageBreak(TRUE, 0);

        //relación utilizada para ajustar la conversión de los píxeles
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        $pdf->setFontSubsetting(true);
        $pdf->SetMargins(5, 5, 5, true);


        $facturas = $this->Consulta->cajachcon_solauto(); //solicitudes_autorizar();
        $total_solicitudes = $this->Consulta->total_solicitude_prov();
        $responsables_solicitud = $this->Consulta->numero_responsables();
        $caja_chica = $this->Consulta->autorizar_caja_chica();
        $responsables_cajach = $this->Consulta->responsable_cajach();
        $tdc_comprobantes = $this->Consulta->autorizar_comprobantes_TDC();
        $responsables_tdc_comprobante = $this->Consulta->responsable_TDC();
        $viaticos = $this->Consulta->autorizar_viaticos();
        // $departamenetos = in_array($this->session->userdata("inicio_sesion")['depto'], array('CONSTRUCCION', 'JARDINERIA', 'OOAM', 'OOAM TECNICO', 'OOAM ADMINISTRATIVO'));
        $fecha = date("d/m/Y H:i:s");



        if ($total_solicitudes->num_rows() > 0) {
            $i = $responsables_solicitud->num_rows();
            $ii = $total_solicitudes->num_rows();
            $ntotal = 0;

            // while( $i > 0 ){
            $responsable_actual = $responsables_solicitud->result()[$responsables_solicitud->num_rows() - $i]->nresonsable;

            //PROVEEDORES HOJA RELACION
            if($facturas->num_rows() > 0){
                $pdf->AddPage('L', 'LETTER LANDSCAPE');
                $pdf->SetFont('Helvetica', '', 9, '', true);
                $pdf->Cell(0, 0, 'SISTEMA DE CUENTAS POR PAGAR - ' . $fecha, 0, false, 'C', 0, '', 0, false, 'D', 'T');

                $html_facturas = '';
                $registros = 0;

                $filasPorPagina = 23; // Número de filas por página
                $totalFilas = count($facturas->result());

                $totalSumatoria = 0;
                for ($m = 0; $m < count($facturas->result_array()); $m++) {
                    $totalSumatoria += $facturas->result()[$m]->cantidad;
                }

                $html = '<table>
                    <tr>
                        <td>
                            <img src="' . base_url("img/logo_inicio.jpg") . '">
                        </td>
                        <td>
                            <p><b>Solicitud de pago a proveedores</b></p>
                            <table>
                                <tr align="center">
                                    <td>
                                    <br/>
                                    <br/>
                                        ____________________________________<br/><br/>
                                        Firma Director de Área
                                    </td>
                                    <td>
                                    <br/>
                                    <br/>
                                        ____________________________________<br/><br/>
                                        Firma Capturista
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                    <td colspan="2">
                        Total de la solicitud: $ ' . number_format($totalSumatoria) . ' | Responsable: ' . $this->session->userdata("inicio_sesion")['nombres'] . ' ' . $this->session->userdata("inicio_sesion")['apellidos'] . ' | Total de registros: ' . count($facturas->result_array()) . '| Fecha: ' . date("d/m/Y H:i:s") . ' | Departamento: ' . $this->session->userdata("inicio_sesion")['depto'] . '
                    </td>
                    </tr>
                </table>';

            
                $pdf->writeHTMLCell(0, 0, $x = '', $y = '10', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
                
                $f= 0;
                for ($i = 0; $i < $totalFilas; $i += $filasPorPagina) {
                    for ($j = $i; $j < min($i + $filasPorPagina, $totalFilas); $j++) {
                        $row = $facturas->result()[$j];

                        /*if($row->idsolicitud == 364668){
                            $j+=1;
                        }*/
                        /*echo "<pre>";
                        print_r($j . ": " . $row->idsolicitud);
                        echo "</pre>";*/
                        
                        $html_facturas .= 
                        // $departamenetos ? '
                        // <tr nobr="true">
                        //     <td height="20">' . $row->idsolicitud . '</td>
                        //     <td>' . $row->fecha_ela . '</td>
                        //     <td>' . $row->mov . '</td>
                        //     <td>' . $row->proyecto . '</td>
                        //     <td>' . $row->etapa . '</td>
                        //     <td>' . $row->condominio . '</td>
                        //     <td>' . $row->nempresa . '</td>
                        //     <td>' . $row->nprov . '</td>
                        //     <td>' . $row->orden_compra . '</td>
                        //     <td>' . $row->fecha_gasto . '</td>
                        //     <td>' . $row->folio . '</td><td>' . $row->homoclave . '</td><td>' . $row->crecibo . '</td>
                        //     <td>' . $row->justificacion . '</td>
                        //     <td>$ ' . number_format($row->cantidad, 2, ".", ",") . '</td>
                        //     <td>' . $row->metodo_pago . '</td>
                        //     <td>' . $row->observaciones . '</td>
                        // </tr>': 
                        '<tr nobr="true">
                            <td height="15">' . $row->idsolicitud . '</td>
                            <td>' . $row->fecha_ela . '</td>
                            <td>' . $row->mov . '</td>
                            <td>' . $row->proyecto . '</td>
                            <td>' . $row->oficina . '</td>
                            <td>' . $row->servicioPartida . '</td>
                            <td>' . $row->etapa . '</td>
                            <td>' . $row->condominio . '</td>
                            <td>' . $row->nempresa . '</td>
                            <td>' . $row->nprov . '</td>
                            <td>' . $row->orden_compra . '</td>
                            <td>' . $row->fecha_gasto . '</td>
                            <td>' . $row->folio . '</td><td>' . $row->homoclave . '</td><td>' . $row->crecibo . '</td>
                            <td>' . $row->justificacion . '</td>
                            <td>$ ' . number_format($row->cantidad, 2, ".", ",") . '</td>
                            <td>' . $row->metodo_pago . '</td>
                            <td>' . $row->observaciones . '</td>
                        </tr>';  
                        
                        $ntotal = (float) $ntotal + (float) $row->cantidad;
                    }
                    if($j==$facturas->num_rows())
                        $f =1;

                    $html = 
                        // $departamenetos ? '<table align="center" border=".8">
                        // <tr style="background-color: #000000; color: #FFF;">
                        //     <td height="15" width="3%" >No.</td>
                        //     <td width="4%">FECHA</td>
                        //     <td width="3%">MOV</td>
                        //     <td width="8%">PROYECTO</td>
                        //     <td width="3%">ETAPA</td>
                        //     <td width="5%">CONDOMINIO</td>
                        //     <td width="3%">EMPRESA</td>
                        //     <td width="8%">PROVEEDOR</td>
                        //     <td width="5%">ORDEN DE COMPRA</td>
                        //     <td width="5%">FECHA FAC</td>
                        //     <td width="3%">FOLIO</td>
                        //     <td width="5%">HOMOCLAVE</td>
                        //     <td width="5%">CONTRA RECIBO</td>
                        //     <td width="24%">DESCRIPCIPÓN</td>
                        //     <td width="5%">CANTIDAD</td>
                        //     <td width="3%">PAGO</td>
                        //     <td width="8%">OBSERVACIONES</td>
                        // </tr>
                        // <tbody>
                        // ' . $html_facturas . '</tbody>
                        // </table>' : 
                        '<table align="center" border=".8">
                            <tr style="background-color: #000000; color: #FFF;">
                                <td height="15" width="3%" >No.</td>
                                <td width="4%">FECHA</td>
                                <td width="3%">MOV</td>
                                <td width="8%">PROYECTO</td>
                                <td width="4%">OFICINA</td>
                                <td width="8%">SERVICIO PARTIDA</td>
                                <td width="3%">ETAPA</td>
                                <td width="5%">CONDOMINIO</td>
                                <td width="3%">EMPRESA</td>
                                <td width="8%">PROVEEDOR</td>
                                <td width="5%">ORDEN DE COMPRA</td>
                                <td width="5%">FECHA FAC</td>
                                <td width="3%">FOLIO</td>
                                <td width="5%">HOMOCLAVE</td>
                                <td width="5%">CONTRA RECIBO</td>
                                <td width="12%">DESCRIPCIPÓN</td>
                                <td width="5%">CANTIDAD</td>
                                <td width="3%">PAGO</td>
                                <td width="8%">OBSERVACIONES</td>
                            </tr>
                            <tbody>
                            ' . $html_facturas . '</tbody>
                        </table>';

                    
                    $html_facturas = '';
                    $pdf->SetFont('Helvetica', '', 5, '', true);
                    if($i==0){
                        $pdf->writeHTMLCell(0, 0, $x = '', $y = '52', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
                    }else{
                        $pdf->writeHTMLCell(0, 0, $x = '', $y = '12', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
                    }
                    $html = '';

                    if($f == 0)
                        $pdf->AddPage('L', 'LETTER LANDSCAPE');
                }
            }
            
            //exit;
            
            //$i -= 1;
            // }
            
            //CAJAS CHICAS HOJA RELACION
            if ($responsables_cajach->num_rows() > 0) {

                $i = $responsables_cajach->num_rows();
                while ($i > 0) {
                    $responsable_actual = $responsables_cajach->result()[$responsables_cajach->num_rows() - $i]->nresonsable;
                    $html_facturas = '';
                    $registros = 0;

                    $pdf->AddPage('L', 'LETTER LANDSCAPE');
                    $pdf->SetFont('Helvetica', '', 9, '', true);
                    $pdf->Cell(0, 0, 'SISTEMA DE CUENTAS POR PAGAR - ' . $fecha, 0, false, 'C', 0, '', 0, false, 'D', 'T');

                    foreach ($caja_chica->result()  as $row) {
                        if ($responsable_actual == $row->nresonsable) {
                            $registros += 1;
                            $html_facturas .= 
                            // $departamenetos ? '
                            // <tr nobr="true">
                            //     <td height="15">' . $row->idsolicitud . '</td>
                            //     <td>' . $row->fecha_crecion . '</td>
                            //     <td>' . $row->nresonsable . '</td>
                            //     <td>' . $row->proyecto . '</td>
                            //     <td>' . $row->etapa . '</td>
                            //     <td>' . $row->condominio . '</td>
                            //     <td>' . $row->nempresa . '</td>
                            //     <td>' . $row->nprov . '</td>
                            //     <td>' . $row->fecha_gasto . '</td>
                            //     <td>' . $row->folio . '</td>
                            //     <td>$ ' . number_format($row->cantidad, 2, ".", ",") . '</td>
                            //     <td>' . $row->justificacion . '</td>
                            //     <td>' . $row->observaciones . '</td>
                            // </tr>':
                            '<tr nobr="true">
                                <td height="15">' . $row->idsolicitud . '</td>
                                <td>' . $row->fecha_crecion . '</td>
                                <td>' . $row->nresonsable . '</td>
                                <td>' . $row->proyecto . '</td>
                                <td>' . $row->oficina . '</td>
                                <td>' . $row->servicioPartida . '</td>
                                <td>' . $row->etapa . '</td>
                                <td>' . $row->condominio . '</td>
                                <td>' . $row->nempresa . '</td>
                                <td>' . $row->nprov . '</td>
                                <td>' . $row->fecha_gasto . '</td>
                                <td>' . $row->folio . '</td>
                                <td>$ ' . number_format($row->cantidad, 2, ".", ",") . '</td>
                                <td>' . $row->justificacion . '</td>
                                <td>' . $row->observaciones . '</td>
                            </tr>';
                        }
                    }

                    $html = '<table>
                        <tr>
                            <td>
                                <img src="' . base_url("img/logo_inicio.jpg") . '">
                            </td>
                            <td>
                                <p><b>Solicitud de pago a Caja Chica</b></p>
                                <table>
                                    <tr align="center">
                                        <td>
                                        <br/>
                                        <br/>
                                            ____________________________________<br/>
                                            Firma Director del Área
                                        </td>
                                        <td>
                                        <br/>
                                        <br/>
                                            ____________________________________<br/>
                                            Firma Capturista
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                Total de la solicitud: $ ' . number_format($responsables_cajach->result()[$responsables_cajach->num_rows() - $i]->total, 2, ".", ",") . ' | Responsable: ' . $this->session->userdata("inicio_sesion")['nombres'] . ' ' . $this->session->userdata("inicio_sesion")['apellidos'] . ' | Total de registros: ' . $registros . '
                            </td>
                        </tr>
                    </table>';
                    $pdf->writeHTMLCell(0, 0, $x = '', $y = '10', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
                                    
                    $html = 
                    //     $departamenetos ? '<table align="center" border=".8">
                    //     <tr style="background-color: #000000; color: #FFF;">
                    //         <td height="15" width="2%">No.</td>
                    //         <td width="5%">FECHA</td>
                    //         <td width="12%">RESPONSABLE CAJA CHICA</td>
                    //         <td width="8%">PROYECTO</td>
                    //         <td width="5%">ETAPA</td>
                    //         <td width="6%">CONDOMINIO</td>
                    //         <td width="5%">EMPRESA</td>
                    //         <td width="15%">PROVEEDOR</td>                
                    //         <td width="7%">FECHA FAC</td>
                    //         <td width="4%">FOLIO</td>
                    //         <td width="4%">CANTIDAD</td>
                    //         <td width="15%">DESCRIPCIPÓN</td>
                    //         <td width="12%">OBSERVACIONES</td>
                    //     </tr>
                    //     <tbody>
                    //     ' . $html_facturas . '</tbody>
                    // </table>':
                    '<table align="center" border=".8">
                        <tr style="background-color: #000000; color: #FFF;">
                            <td height="15" width="2%">No.</td>
                            <td width="5%">FECHA</td>
                            <td width="11%">RESPONSABLE CAJA CHICA</td>
                            <td width="8%">PROYECTO</td>
                            <td width="4%">OFICINA</td>
                            <td width="8%">SERVICIO PARTIDA</td>
                            <td width="5%">ETAPA</td>
                            <td width="6%">CONDOMINIO</td>
                            <td width="5%">EMPRESA</td>
                            <td width="12%">PROVEEDOR</td>                
                            <td width="7%">FECHA FAC</td>
                            <td width="4%">FOLIO</td>
                            <td width="4%">CANTIDAD</td>
                            <td width="11%">DESCRIPCIPÓN</td>
                            <td width="8%">OBSERVACIONES</td>
                        </tr>
                        <tbody>
                        ' . $html_facturas . '</tbody>
                    </table>';


                    $pdf->SetFont('Helvetica', '', 5, '', true);
                    $pdf->writeHTMLCell(0, 0, $x = '', $y = '52', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
                    $i -= 1;
                }
            }

            //COMPROBACION DE TARJETAS DE CREDITO HOJA RELACION
            if ($responsables_tdc_comprobante->num_rows() > 0) {

                $i = $responsables_tdc_comprobante->num_rows();
                
                while ($i > 0) {
                    $responsable_actual = $responsables_tdc_comprobante->result()[$responsables_tdc_comprobante->num_rows() - $i]->nresonsable;
                    $html_facturas = '';
                    $registros = 0;

                    $pdf->AddPage('L', 'LETTER LANDSCAPE');
                    $pdf->SetFont('Helvetica', '', 9, '', true);
                    $pdf->Cell(0, 0, 'SISTEMA DE CUENTAS POR PAGAR - ' . $fecha, 0, false, 'C', 0, '', 0, false, 'D', 'T');

                    foreach ($tdc_comprobantes->result()  as $row) {
                        if ($responsable_actual == $row->nresonsable) {
                            $registros += 1;
                            $html_facturas .= 
                        //     $departamenetos ? '
                        // <tr nobr="true">
                        //     <td height="15">' . $row->idsolicitud . '</td>
                        //     <td>' . $row->fecha_crecion . '</td>
                        //     <td>' . $row->nresonsable . '</td>
                        //     <td>' . $row->proyecto . '</td>
                        //     <td>' . $row->etapa . '</td>
                        //     <td>' . $row->condominio . '</td>
                        //     <td>' . $row->nempresa . '</td>
                        //     <td>' . $row->nprov . '</td>
                        //     <td>' . $row->fecha_gasto . '</td>
                        //     <td>' . $row->folio . '</td>
                        //     <td>$ ' . number_format($row->cantidad, 2, ".", ",") . '</td>
                        //     <td>' . $row->justificacion . '</td>
                        //     <td>' . $row->observaciones . '</td>
                        // </tr>': 
                        '<tr nobr="true">
                            <td height="15">' . $row->idsolicitud . '</td>
                            <td>' . $row->fecha_crecion . '</td>
                            <td>' . $row->nresonsable . '</td>
                            <td>' . $row->proyecto . '</td>
                            <td>' . $row->oficina . '</td>
                            <td>' . $row->servicioPartida . '</td>
                            <td>' . $row->etapa . '</td>
                            <td>' . $row->condominio . '</td>
                            <td>' . $row->nempresa . '</td>
                            <td>' . $row->nprov . '</td>
                            <td>' . $row->fecha_gasto . '</td>
                            <td>' . $row->folio . '</td>
                            <td>$ ' . number_format($row->cantidad, 2, ".", ",") . '</td>
                            <td>' . $row->justificacion . '</td>
                            <td>' . $row->observaciones . '</td>
                        </tr>';
                        }
                    }

                    $html = '<table>
                        <tr>
                            <td>
                                <img src="' . base_url("img/logo_inicio.jpg") . '">
                            </td>
                            <td>
                                <p><b>Solicitud de comprobantes de TDC</b></p>
                                <table>
                                    <tr align="center">
                                        <td>
                                        <br/>
                                        <br/>
                                            ____________________________________<br/>
                                            Firma Director del Área
                                        </td>
                                        <td>
                                        <br/>
                                        <br/>
                                            ____________________________________<br/>
                                            Firma Capturista
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                Total de la solicitud: $ ' . number_format($responsables_tdc_comprobante->result()[$responsables_tdc_comprobante->num_rows() - $i]->total, 2, ".", ",") . ' | Responsable: ' . $this->session->userdata("inicio_sesion")['nombres'] . ' ' . $this->session->userdata("inicio_sesion")['apellidos'] . ' | Total de registros: ' . $registros . '
                            </td>
                        </tr>
                    </table>';
                    $pdf->writeHTMLCell(0, 0, $x = '', $y = '10', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);

                    $html = 
                    //     $departamenetos ? '<table align="center" border=".8">
                    //     <tr style="background-color: #000000; color: #FFF;">
                    //         <td height="15" width="2%">No.</td>
                    //         <td width="5%">FECHA</td>
                    //         <td width="12%">RESPONSABLE CAJA CHICA</td>
                    //         <td width="8%">PROYECTO</td>
                    //         <td width="5%">ETAPA</td>
                    //         <td width="6%">CONDOMINIO</td>
                    //         <td width="5%">EMPRESA</td>
                    //         <td width="15%">PROVEEDOR</td>                
                    //         <td width="7%">FECHA FAC</td>
                    //         <td width="4%">FOLIO</td>
                    //         <td width="4%">CANTIDAD</td>
                    //         <td width="15%">DESCRIPCIPÓN</td>
                    //         <td width="12%">OBSERVACIONES</td>
                    //     </tr>
                    //     <tbody>
                    //     ' . $html_facturas . '</tbody>
                    // </table>' :
                    '<table align="center" border=".8">
                        <tr style="background-color: #000000; color: #FFF;">
                            <td height="15" width="3%">No.</td>
                            <td width="5%">FECHA</td>
                            <td width="12%">RESPONSABLE CAJA CHICA</td>
                            <td width="8%">PROYECTO</td>
                            <td width="4%">OFICINA</td>
                            <td width="8%">SERVICIO PARTIDA</td>
                            <td width="5%">ETAPA</td>
                            <td width="6%">CONDOMINIO</td>
                            <td width="5%">EMPRESA</td>
                            <td width="10%">PROVEEDOR</td>                
                            <td width="7%">FECHA FAC</td>
                            <td width="4%">FOLIO</td>
                            <td width="4%">CANTIDAD</td>
                            <td width="10%">DESCRIPCIPÓN</td>
                            <td width="10%">OBSERVACIONES</td>
                        </tr>
                        <tbody>
                        ' . $html_facturas . '</tbody>
                    </table>';


                    $pdf->SetFont('Helvetica', '', 5, '', true);
                    $pdf->writeHTMLCell(0, 0, $x = '', $y = '52', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
                    $i -= 1;
                }
            }

            //VIATICOS
            if ($viaticos->num_rows() > 0) {

                $i = $viaticos->num_rows();

                $totalSumatoria = 0;
                for ($m = 0; $m < count($viaticos->result_array()); $m++) {
                    $totalSumatoria += $viaticos->result()[$m]->cantidad;
                }
                
                $totalFilas = count($viaticos->result());
                    
                $html_facturas = '';
                $registros = 0;

                $pdf->AddPage('L', 'LETTER LANDSCAPE');
                $pdf->SetFont('Helvetica', '', 9, '', true);
                $pdf->Cell(0, 0, 'SISTEMA DE CUENTAS POR PAGAR - ' . $fecha, 0, false, 'C', 0, '', 0, false, 'D', 'T');

                 $html = '<table>
                            <tr>
                                <td>
                                    <img src="' . base_url("img/logo_inicio.jpg") . '">
                                </td>
                                <td>
                                    <p><b>Solicitud de comprobantes de Viáticos</b></p>
                                    <table>
                                        <tr align="center">
                                            <td>
                                            <br/>
                                            <br/>
                                                ____________________________________<br/>
                                                Firma Director del Área
                                            </td>
                                            <td>
                                            <br/>
                                            <br/>
                                                ____________________________________<br/>
                                                Firma Capturista
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    Total de la solicitud: $ ' . number_format($totalSumatoria) . ' | Responsable: ' . $this->session->userdata("inicio_sesion")['nombres'] . ' ' . $this->session->userdata("inicio_sesion")['apellidos'] . ' | Total de registros: ' . count($viaticos->result_array()) . '| Fecha: ' . date("d/m/Y H:i:s") . ' | Departamento: ' . $this->session->userdata("inicio_sesion")['depto'] . '
                                </td>
                            </tr>
                        </table>';
                    
                $pdf->writeHTMLCell(0, 0, $x = '', $y = '10', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
                $f=0;
                $filasPorPagina = 23;
                for ($i = 0; $i < $totalFilas; $i += $filasPorPagina) {

                    for ($j = $i; $j < min($i + $filasPorPagina, $totalFilas); $j++) {
                        $row = $viaticos->result()[$j];
                       
                        $html_facturas .= 
                        // $departamenetos ? '
                        // <tr nobr="true">
                        //     <td height="20">' . $row->idsolicitud . '</td>
                        //     <td>' . $row->fecha_ela . '</td>
                        //     <td>' . $row->mov . '</td>
                        //     <td>' . $row->proyecto . '</td>
                        //     <td>' . $row->etapa . '</td>
                        //     <td>' . $row->condominio . '</td>
                        //     <td>' . $row->nempresa . '</td>
                        //     <td>' . $row->nprov . '</td>
                        //     <td>' . $row->orden_compra . '</td>
                        //     <td>' . $row->fecha_gasto . '</td>
                        //     <td>' . $row->folio . '</td><td>' . $row->homoclave . '</td><td>' . $row->crecibo . '</td>
                        //     <td>' . $row->justificacion . '</td>
                        //     <td>$ ' . number_format($row->cantidad, 2, ".", ",") . '</td>
                        //     <td>' . $row->metodo_pago . '</td>
                        //     <td>' . $row->observaciones . '</td>
                        // </tr>': 
                        '<tr nobr="true">
                            <td height="20">' . $row->idsolicitud . '</td>
                            <td>' . $row->fecha_ela . '</td>
                            <td>' . $row->proyecto . '</td>
                            <td>' . $row->oficina . '</td>
                            <td>' . $row->servicioPartida . '</td>
                            <td>' . $row->etapa . '</td>
                            <td>' . $row->condominio . '</td>
                            <td>' . $row->nempresa . '</td>
                            <td>' . $row->nprov . '</td>
                            <td>' . $row->orden_compra . '</td>
                            <td>' . $row->fecha_gasto . '</td>
                            <td>' . $row->folio . '</td><td>' . $row->homoclave . '</td><td>' . $row->crecibo . '</td>
                            <td>' . $row->justificacion . '</td>
                            <td>$ ' . number_format($row->cantidad, 2, ".", ",") . '</td>
                            <td>' . $row->metodo_pago . '</td>
                            <td>' . $row->observaciones . '</td>
                        </tr>';
                    }

                    if($j==$viaticos->num_rows())
                        $f =1;

                    $html = 
                    //     $departamenetos ? '<table align="center" border=".8">
                    //     <tr style="background-color: #000000; color: #FFF;">
                    //         <td height="15" width="3%" >No.</td>
                    //         <td width="4%">FECHA</td>
                    //         <td width="3%">MOV</td>
                    //         <td width="8%">PROYECTO</td>
                    //         <td width="3%">ETAPA</td>
                    //         <td width="5%">CONDOMINIO</td>
                    //         <td width="3%">EMPRESA</td>
                    //         <td width="8%">PROVEEDOR</td>
                    //         <td width="5%">ORDEN DE COMPRA</td>
                    //         <td width="5%">FECHA FAC</td>
                    //         <td width="3%">FOLIO</td>
                    //         <td width="5%">HOMOCLAVE</td>
                    //         <td width="5%">CONTRA RECIBO</td>
                    //         <td width="24%">DESCRIPCIPÓN</td>
                    //         <td width="5%">CANTIDAD</td>
                    //         <td width="3%">PAGO</td>
                    //         <td width="8%">OBSERVACIONES</td>
                    //     </tr>
                    //     <tbody>
                    //     ' . $html_facturas . '</tbody>
                    // </table>': 
                    '<table align="center" border=".8">
                        <tr style="background-color: #000000; color: #FFF;">
                            <td height="15" width="3%" >No.</td>
                            <td width="4%">FECHA</td>
                            <td width="8%">PROYECTO</td>
                            <td width="4%">OFICINA</td>
                            <td width="8%">SERVICIO PARTIDA</td>
                            <td width="3%">ETAPA</td>
                            <td width="5%">CONDOMINIO</td>
                            <td width="4%">EMPRESA</td>
                            <td width="8%">PROVEEDOR</td>
                            <td width="5%">ORDEN DE COMPRA</td>
                            <td width="5%">FECHA FAC</td>
                            <td width="3%">FOLIO</td>
                            <td width="5%">HOMOCLAVE</td>
                            <td width="5%">CONTRA RECIBO</td>
                            <td width="14%">DESCRIPCIPÓN</td>
                            <td width="5%">CANTIDAD</td>
                            <td width="3%">PAGO</td>
                            <td width="8%">OBSERVACIONES</td>
                        </tr>
                        <tbody>
                        ' . $html_facturas . '</tbody>
                    </table>';
                    
                    $html_facturas = '';
                    $pdf->SetFont('Helvetica', '', 5, '', true);
                    if($i==0){
                        $pdf->writeHTMLCell(0, 0, $x = '', $y = '52', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
                    }else{
                        $pdf->writeHTMLCell(0, 0, $x = '', $y = '12', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
                    }
                    $html = '';

                    if($f == 0)
                        $pdf->AddPage('L', 'LETTER LANDSCAPE');
                
                }
            }
            
            ob_end_clean();
            $pdf->Output(utf8_decode("DOCUMENTO_AUTORIZACION_" . date("Y-m-d_Hi") . ".pdf"), 'D');
        }
    }

    //DOCUMENTO RELACION PARA CH
    function documentos_autorizacion_otros(){

        ini_set('memory_limit', '-1');

        $facturas = '';
        $total_solicitudes = '';
        $filtros = "";

        $idsolicitudes = $this->input->get("idsolicitudes");
        
        if( $this->input->get("filtros")   ){
            $idsol = $this->input->get("N");
            $proyecto = $this->input->get("PROYECTO");
            $fecha = $this->input->get("FECHA");
            $proovedor = $this->input->get("PROVEEDOR");
            $empresa = $this->input->get("EMPRESA");
            $operacion = $this->input->get("OPERACION");
            $capturista = $this->input->get("CAPTURISTA");
            $cantidad =  $this->input->get("CANTIDAD");
            $estatus = $this->input->get("ESTATUS");
            $cantidad = str_replace("$","",$cantidad);
            $cantidad = str_replace(",","",$cantidad);

            $filtros = " WHERE solpagos.idsolicitud LIKE '%$idsol%' AND solpagos.proyecto LIKE '%$proyecto%' AND DATE_FORMAT(solpagos.fecelab, '%d/%m/%Y') LIKE '%$fecha%' 
                        AND proveedores.nombre LIKE '%$proovedor%' AND empresas.abrev LIKE '%$empresa%' AND solpagos.nomdepto LIKE '%$operacion%' AND solpagos.cantidad LIKE '%$cantidad%' ";

            if( $capturista != '')
                $filtros .= " AND capturista.nombre_completo LIKE '%$capturista%'  ";
        }

        if( isset($idsolicitudes) )
            $filtros = " WHERE solpagos.idsolicitud IN ($idsolicitudes) ";

        $this->load->library('Pdf');
        $pdf = new TCPDF('P', 'mm', 'LETTER', 'UTF-8', false);

        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Sistemas Victor Manuel Sanchez Ramirez');
        $pdf->SetTitle('DOCUMENTO RELACIÓN GASTOS');
        $pdf->SetSubject('DOCUMENTO RELACIÓN GASTOS POR ÁREA');
        $pdf->SetKeywords('DOCUMENTO, CIUDAD MADERAS, ÁREA, EMPRESA, GASTOS');
        // se pueden modificar en el archivo tcpdf_config.php de libraries/config
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // se pueden modificar en el archivo tcpdf_config.php de libraries/config
        $pdf->SetAutoPageBreak(TRUE, 0);

        //relación utilizada para ajustar la conversión de los píxeles
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        $pdf->setFontSubsetting(true);
        $pdf->SetFont('Helvetica', '', 9, '', true);
        $pdf->SetMargins(20, 7, 15, true);

        $pdf->AddPage('L', 'LETTER LANDSCAPE');

        //if (isset($idsolicitudes) || $filtros != '') {


        $facturas = $this->Consulta->solicitudes_autorizar_otros_gastos_idsolicitud( $filtros, $this->session->userdata("inicio_sesion")['id']);
        $total_solicitudes = $this->Consulta->total_solicitude_prov_otros_gastos_idsolicitud( $filtros, $this->session->userdata("inicio_sesion")['id']);
        /*
        } else {
            $facturas = $this->Consulta->solicitudes_autorizar_otros_gastos();
            $total_solicitudes = $this->Consulta->total_solicitude_prov_otros_gastos();
        }
        */
        $caja_chica_conglomerado =  $this->Consulta->autorizar_caja_chica_conglomerado_otros_gastos();
        //$caja_chica = $this->Consulta->autorizar_caja_chica_otros_gastos();
        $fecha = date("d/m/Y H:i:s");

        if ($facturas->num_rows() > 0 || $caja_chica_conglomerado->num_rows() > 0) {
            $pdf->Cell(0, 0, 'SISTEMA DE CUENTAS POR PAGAR - ' . $fecha, 0, false, 'C', 0, '', 0, false, 'D', 'T');

            $html = '<table>
                <tr>
                    <td>
                        <img src="' . base_url("img/logo_inicio.jpg") . '">
                    </td>
                    <td>
                        <h2>Solicitud de pago a proveedores</h2>
                        <table>
                            <tr align="center">
                                <td>
                                    ____________________________________<br/>
                                    Firma Director de Área
                                </td>
                                <td>
                                    ____________________________________<br/>
                                    Firma Capturista
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        Total de la solicitud: $ ' . ($total_solicitudes->num_rows() > 0 ? number_format($total_solicitudes->row()->total, 2, ".", ",") : '') . ' | Responsable: ' . $this->session->userdata("inicio_sesion")['nombres'] . ' ' . $this->session->userdata("inicio_sesion")['apellidos'] . ' | Total de registros: ' . ($facturas->num_rows() /*+ $caja_chica_conglomerado->num_rows()*/) . '| Fecha: ' . date("d/m/Y H:i:s") . '
                    </td>
                </tr>
            </table>';

            $pdf->writeHTMLCell(0, 0, $x = '', $y = '10', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);

            $html_facturas = '';

            if ($facturas->num_rows() > 0) {
                foreach ($facturas->result()  as $row) {
                    $html_facturas .= '
                    <tr nobr="true">
                        <td height="20">
                            ' . $row->idsolicitud . '
                        </td>
                        <td>
                            ' . $row->fecha_ela . '
                        </td>
                        <td>
                            P PROV
                        </td>
                        <td>
                            ' . $row->proyecto . '
                        </td>
                        <td>
                            ' . $row->oficina . '
                        </td>
                        <td>
                            ' . $row->servicioPartida . '
                        </td>
                        <td>
                            ' . $row->etapa . '
                        </td>
                        <td>
                            ' . $row->condominio . '
                        </td>
                        <td>
                            ' . $row->nempresa . '
                        </td>
                        <td>
                            ' . $row->nresonsable . '
                        </td>
                        <td>
                            ' . $row->nprov . '
                        </td>
                        <td>
                            ' . $row->fecha_gasto . '
                        </td>
                        <td>
                            ' . $row->folio . '
                        </td>
                        <td>
                            ' . $row->justificacion . '
                        </td>
                        <td>
                            $ ' . number_format($row->cantidad, 2, ".", ",") . '
                        </td>
                        <td>
                            ' . $row->metodo_pago . '
                        </td>
                        <td>
                            ' . $row->observaciones . '
                        </td>
                    </tr>';
                }
            }
            /*
            if ($caja_chica_conglomerado->num_rows() > 0) {

                $suma_caja_chica = 0;

                foreach ($caja_chica_conglomerado->result()  as $row) {
                    $html_facturas .= '
                    <tr nobr="true">
                        <td height="15">
                            
                        </td>
                        <td >
                            ' . $row->fecha_ela . '
                        </td>
                        <td >
                            C CHICA
                        </td>
                        <td>
                            
                        </td>
                        <td>
                            
                        </td>
                        <td>
                            
                        </td>
                        <td>
                            ' . $row->abrev . '
                        </td>
                        <td>
                            ' . $row->nresonsable . '
                        </td>
                        <td>
                            ' . $row->nresonsable . '
                        </td>
                        <td>
                            ' . $row->fecha_gasto . '
                        </td>
                        <td>
                            
                        </td>
                        <td>
                            
                        </td>
                        <td>
                            $ ' . number_format($row->cantidad, 2, ".", ",") . '
                        </td>
                        <td>
                            
                        </td>
                        <td>
                            
                        </td>
                    </tr>';

                    $suma_caja_chica += $row->cantidad;
                }
            }
            */
            $html = '<table align="center" border=".8">
                <tr style="background-color: #000000; color: #FFF;">
                    <td height="15" width="3%">
                        No.
                    </td>
                    <td width="5%">
                        FECHA
                    </td>
                    <td width="5%">
                        MOV
                    </td>
                    <td width="7%">
                        PROYECTO
                    </td>
                    <td width="6%">
                        OFICINA
                    </td>
                    <td width="7%">
                        SERVICIO PARTIDA
                    </td>
                    <td width="5%">
                        ETAPA
                    </td>
                    <td width="6%">
                        CONDOMINIO
                    </td>
                    <td width="5%">
                        EMPRESA
                    </td>
                    <td width="10%">
                        DIRECTOR DE ÁREA
                    </td>
                    <td width="10%">
                        PROVEEDOR
                    </td>
                    <td >
                        FECHA FAC
                    </td>
                    <td width="4%">
                        FOLIO
                    </td>
                    <td >
                        DESCRIPCIPÓN
                    </td>
                    <td >
                        CANTIDAD
                    </td>
                    <td width="3%">
                        PAGO
                    </td>
                    <td width="9%">
                        OBSERVACIONES
                    </td>
                </tr>
                <tbody>
                ' . $html_facturas . '</tbody>
            </table>';
            $pdf->SetFont('Helvetica', '', 5, '', true);
            $pdf->writeHTMLCell(0, 0, $x = '', $y = '52', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);

            /*
            if ($caja_chica_conglomerado->num_rows() > 0) {
                $pdf->AddPage('L', 'LETTER LANDSCAPE');

                $html = '<table>
                <tr>
                    <td>
                        <img src="' . base_url("img/logo_inicio.jpg") . '">
                    </td>
                    <td>
                        <h2>Solicitud de pago a Caja Chica</h2>
                        <table>
                            <tr align="center">
                                <td>
                                    ____________________________________<br/>
                                    Firma Director del Área
                                </td>
                                <td>
                                    ____________________________________<br/>
                                    Firma Capturista
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        Total de la solicitud: $ ' . number_format($suma_caja_chica, 2, ".", ",") . ' | Responsable: ' . $this->session->userdata("inicio_sesion")['nombres'] . ' ' . $this->session->userdata("inicio_sesion")['apellidos'] . ' | Total de registros: ' . $caja_chica->num_rows() . '
                    </td>
                </tr>
            </table>';
                $pdf->SetFont('Helvetica', '', 9, '', true);
                $pdf->Cell(0, 0, 'SISTEMA DE CUENTAS POR PAGAR - ' . $fecha, 0, false, 'C', 0, '', 0, false, 'D', 'T');
                $pdf->writeHTMLCell(0, 0, $x = '', $y = '10', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);

                $html_facturas = '';

                foreach ($caja_chica->result()  as $row) {
                    $html_facturas .= '
                <tr nobr="true">
                    <td height="15">
                        ' . $row->idsolicitud . '
                    </td>
                    <td >
                        ' . $row->fecha_crecion . '
                    </td>
                    <td >
                        ' . $row->proyecto . '
                    </td>
                    <td>
                        ' . $row->etapa . '
                    </td>
                    <td>
                        ' . $row->condominio . '
                    </td>
                    <td>
                        ' . $row->nempresa . '
                    </td>
                    <td>
                        ' . $row->nprov . '
                    </td>
                    <td>
                        ' . $row->fecha_gasto . '
                    </td>
                    <td>
                        ' . $row->folio . '
                    </td>
                    <td>
                        $ ' . number_format($row->cantidad, 2, ".", ",") . '
                    </td>
                    <td>
                        ' . $row->justificacion . '
                    </td>
                    <td>
                        ' . $row->observaciones . '
                    </td>
                </tr>';
                }

                $html = '<table align="center" border=".8">
            <tr style="background-color: #000000; color: #FFF;">
                <td height="15" width="2%">
                    No.
                </td>
                <td width="5%">
                    FECHA
                </td>
                <td width="10%">
                    PROYECTO
                </td>
                <td width="5%">
                    ETAPA
                </td>
                <td width="6%">
                    CONDOMINIO
                </td>
                <td width="5%">
                    EMPRESA
                </td>
                <td width="17%">
                    PROVEEDOR
                </td>                
                <td >
                    FECHA FAC
                </td>
                <td width="4%">
                    FOLIO
                </td>
                <td width="4%">
                    CANTIDAD
                </td>
                <td width="15%">
                    DESCRIPCIPÓN
                </td>
                <td width="18%">
                    OBSERVACIONES
                </td>
            </tr>
            <tbody>
            ' . $html_facturas . '</tbody>
        </table>';
                $pdf->SetFont('Helvetica', '', 5, '', true);
                $pdf->writeHTMLCell(0, 0, $x = '', $y = '52', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
            }
            */
            $pdf->Output(utf8_decode("DOCUMENTO_AUTORIZACIÓN_.pdf"), 'D');
        }
    }

    function documentos_autorizacion_devtras()
    {
        $this->load->library('Pdf');
        $pdf = new TCPDF('P', 'mm', 'LETTER', 'UTF-8', false);

        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Sistemas Victor Manuel Sanchez Ramirez');
        $pdf->SetTitle('DOCUMENTO RELACIÓN GASTOS');
        $pdf->SetSubject('DOCUMENTO RELACIÓN GASTOS POR ÁREA');
        $pdf->SetKeywords('DOCUMENTO, CIUDAD MADERAS, ÁREA, EMPRESA, GASTOS');
        // se pueden modificar en el archivo tcpdf_config.php de libraries/config
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // se pueden modificar en el archivo tcpdf_config.php de libraries/config
        $pdf->SetAutoPageBreak(TRUE, 0);

        //relación utilizada para ajustar la conversión de los píxeles
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        $pdf->setFontSubsetting(true);
        $pdf->SetMargins(5, 7, 5, true);


        $facturas = $this->Consulta->cajachcon_solauto_2(); //solicitudes_autorizar();
        $total_solicitudes = $this->Consulta->total_solicitude_prov();
        $responsables_solicitud = $this->Consulta->numero_responsables();
        $fecha = date("d/m/Y H:i:s");

        if ($facturas->num_rows() > 0  && $total_solicitudes->num_rows() > 0) {
            $i = $responsables_solicitud->num_rows();
            $ii = $total_solicitudes->num_rows();
            $ntotal = 0;

            $pdf->AddPage('L', 'LETTER LANDSCAPE');
            $pdf->SetFont('Helvetica', '', 9, '', true);
            $pdf->Cell(0, 0, 'SISTEMA DE CUENTAS POR PAGAR - ' . $fecha, 0, false, 'C', 0, '', 0, false, 'D', 'T');

            $html_facturas = '';
            $registros = 0;

            foreach ($facturas->result()  as $row) {

                $registros += 1;
                $html_facturas .= '
                        <tr nobr="true">
                            <td height="20">' . $row->idsolicitud . '</td>
                            <td>' . $row->fecha_ela . '</td>
                            <td>' . $row->mov . '</td>
                            <td>' . $row->proyecto . '</td>
                            <td>' . $row->etapa . '</td>
                            <td>' . $row->condominio . '</td>
                            <td>' . $row->nempresa . '</td>
                            <td>' . $row->nresonsable . '</td>
                            <td>' . $row->nprov . '</td>
                            <td>' . $row->fecha_gasto . '</td>
                            <td>' . $row->justificacion . '</td>
                            <td>$ ' . number_format($row->cantidad, 2, ".", ",") . '</td>
                            <td>' . $row->metodo_pago . '</td>
                            <td>' . $row->banco . '<br>' . $row->cuenta . '</td>
                        </tr>';
                $ntotal = (float) $ntotal + (float) $row->cantidad;
            }


            $html = '<table>
                    <tr>
                        <td>
                            <img src="' . base_url("img/logo_inicio.jpg") . '">
                        </td>
                        <td>
                            <h2>Solicitud de devoluciones</h2>
                            <table>
                                <tr align="center">
                                    <td>
                                        ____________________________________<br/>
                                        Firma Director de Área
                                    </td>
                                    <td>
                                        ____________________________________<br/>
                                        Firma Capturista
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                    <td colspan="2">
                        Total de la solicitud: $ ' . number_format($ntotal, 2, ".", ",") . ' | Responsable: ' . $this->session->userdata("inicio_sesion")['nombres'] . ' ' . $this->session->userdata("inicio_sesion")['apellidos'] . ' | Total de registros: ' . $registros . '| Fecha: ' . date("d/m/Y H:i:s") . '
                    </td>
                    </tr>
                </table>';

            $pdf->writeHTMLCell(0, 0, $x = '', $y = '10', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);

            $html = '<table align="center" border=".8">
                    <tr style="background-color: #000000; color: #FFF;">
                        <td height="15" width="3%">No.</td>
                        <td width="4%">FECHA</td>
                        <td width="4%">MOV</td>
                        <td width="10%">PROYECTO</td>
                        <td width="5%">ETAPA</td>
                        <td width="10%">CONDOMINIO</td>
                        <td width="6%">EMPRESA</td>
                        <td width="10%">CAPTURISTA</td>
                        <td width="10%">PROVEEDOR</td>
                        <td width="5%">FECHA FAC</td>
                        <td width="16%">DESCRIPCIÓN</td>
                        <td width="5%">CANTIDAD</td>
                        <td width="3%">PAGO</td>
                        <td width="9%">CUENTA DESTINO</td>
                    </tr>
                    <tbody>
                    ' . $html_facturas . '</tbody>
                </table>';
            $pdf->SetFont('Helvetica', '', 6, '', true);
            $pdf->writeHTMLCell(0, 0, $x = '', $y = '52', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
            $i -= 1;

            ob_end_clean();
            $pdf->Output(utf8_decode("DOCUMENTO_AUTORIZACION_.pdf"), 'D');
        }
    }
    
    function verificarDatos()
    {   
        // print_r();
        // $cuenta = $this->input->post('cuenta');
        $idproveedor = $this->input->post('idproveedor');
        // print_r($idproveedor);
        //  $pro = explode("(", $idproveedor);
        //  print_r($idproveedor);

         $str = $idproveedor; 
         $start = strpos ($str, '('); 
         $end = strpos ($str, ')', $start + 1); 
         $Length = $end - $start; 
       if($Length == 0){
            $cuenta = $this->input->post('cuenta');
       }else{
            $cuenta = substr ($str, $start + 1, $Length - 1);
       }
            
        //  print_r($cuenta);
        //  print_r($Length);
         


        $idbanco = $this->input->post('idbanco');
        $respuesta['ok'] = false;
        $query =  $this->M_Devolucion_Traspaso->validacion_cuenta($cuenta, $idbanco);
        if ($query->num_rows() > 0) {
            $respuesta['ok'] = true;
            $respuesta['quien'] = $query->row();
        }

        echo json_encode($respuesta);
        // echo json_encode($respuesta);
    }
    function verificarDatos1()
    {   
        // print_r();
        $cuenta = $this->input->post('cuenta');
        $idproveedor = $this->input->post('idproveedor');
        // print_r($idproveedor);
        //  $pro = explode("(", $idproveedor);
        //  print_r($idproveedor);

         $str = $idproveedor; 
         $start = strpos ($str, '('); 
         $end = strpos ($str, ')', $start + 1); 
         $Length = $end - $start; 
         $cuenta = substr ($str, $start + 1, $Length - 1);

        $idbanco = $this->input->post('idbanco');
        $respuesta['ok'] = false;
        $query =  $this->M_Devolucion_Traspaso->validacion_cuenta($cuenta, $idbanco);
        if ($query->num_rows() > 0) {
            $respuesta['ok'] = true;
            $respuesta['quien'] = $query->row();
        }

        echo json_encode($respuesta);
        // echo json_encode($respuesta);
    }

    function generarZip(){ /** INICIO FECHA: 26-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
        $idsolicitud = $_POST["idsolicitud"];
        if (!$idsolicitud) {
            echo json_encode(['status' => 404, 'mensaje' => 'El parámetro "idsolicitud" no ha sido encontrado.']);
            return;
        }
            $xmls = $this->Consulta->xmlDescarga($idsolicitud)->result_array();

        if (!$xmls) {
            echo json_encode(array('status' => 404, 'mensaje' => 'NO SE ENCONTRARON ARCHIVOS QUE COINCIDAN CON LOS CRITERIOS ESPECIFICADOS.'));
            return;
        }

        $this->load->library('zip');
        $folder_path = './UPLOADS/XMLS/'; // Cambia esta ruta a la ubicación de tus archivos XML

        $existentes = [];

        // Añadir los archivos al zip
        foreach ($xmls as $xml) {
            $file_path = $folder_path . $xml["nombre_archivo"];
            if (file_exists($file_path)) {
                $this->zip->add_data($xml["nombre_archivo"], file_get_contents($file_path));
                $existentes[] = [
                    "archivo" => $xml["nombre_archivo"], 
                    "estatus" => 200
                ];
            } else {
                $existentes[] = [
                    "archivo" => $xml["nombre_archivo"],
                    "estatus" => 204
                ];
            }
        }

        // Guardar el archivo zip
        $zip_data = $this->zip->get_zip();
        $this->zip->clear_data();

        $encoded_zip = 'data:application/zip;base64,' . base64_encode($zip_data);

        echo json_encode([
            "status"        =>  200,
            "mensaje"       =>  "El archivo .zip ha sido creado exitosamente.",
            "zip_data"      =>  $encoded_zip,
            "existentes"    =>  $existentes,
            "date"          =>  date('Ymd')
        ]);
        exit;        
    }

    function generarUrlXMLS(){
        $idsolicitud = $this->input->post("idsolicitud");
        
        if (!$idsolicitud) {
            echo json_encode(['status' => 404, 'mensaje' => 'El parámetro "idsolicitud" no ha sido encontrado.']);
            return;
        }
        
        $xmls = $this->Consulta->xmlDescarga($idsolicitud)->result_array();

        if (!$xmls) {
            echo json_encode(array('status' => 404, 'mensaje' => 'NO SE ENCONTRARON ARCHIVOS QUE COINCIDAN CON LOS CRITERIOS ESPECIFICADOS.'));
            return;
        }

        $folder_path = './UPLOADS/XMLS/';
        $urls = [];

        foreach ($xmls as $key => $xml) {
            $file_path = $folder_path . $xml["nombre_archivo"];
            if (file_exists($file_path)) {
                $urls[] = [ "url" => base_url()."UPLOADS/XMLS/".$xml["nombre_archivo"] ];
            }
        }

        // Devolver la ruta del archivo zip
        echo json_encode(
            array(
                'status' => 200,
                'mensaje' => '¡Archivos encontrados exitosamente!.',
                'urls' => $urls
            )
        );
        exit;
    } 
    /** FIN FECHA: 26-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
}
