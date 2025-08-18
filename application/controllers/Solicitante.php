<?php
error_reporting(-1);
ini_set('display_errors', 1);
defined('BASEPATH') OR exit('No direct script access allowed');

class Solicitante extends CI_Controller {

    function __construct(){
        parent::__construct();
        if( ($this->session->userdata("inicio_sesion") && in_array( $this->session->userdata("inicio_sesion")['rol'], array( 'CAD','CPV','GAD','PV','DA', 'FP', 'CA', 'SU', 'SB', 'CJ', 'AS', 'CE', 'CX', 'CC', 'CP','AD' ))) || ( in_array( $this->session->userdata("inicio_sesion")['rol'], array( 'CI' )) && in_array($this->session->userdata('inicio_sesion')["id"], ['2668', '2815', '2514', '2613', '2109', '2513', '2892', '3177'])))
            $this->load->model(array('Solicitudes_solicitante', 'Consulta', 'Lista_dinamicas', 'Complemento_cxpModel'));
        else 
            redirect("Login", "refresh");
    }

    public function index(){
        if($this->session->userdata("inicio_sesion")['rol'] == 'DA' ){
            switch( $this->session->userdata("inicio_sesion")['depto'] ){
                case 'CONSTRUCCION':
                    $this->load->view("vista_solicitante_construccion_da");
                    break;
                case 'CAPITAL HUMANO':
                case 'COMERCIALIZACION':
                case 'CONTABILIDAD':
                //case 'OOAM':
                case 'COMPRAS':
                //case 'DIRECCION GENERAL':
                case 'CI-COMPRAS':
                case 'CONTROL INTERNO':
                case 'CONTRATACIÓN Y TITULACIÓN':
                case 'NYSSA':
                    $this->load->view("vista_solicitante_capital_humanos_da");
                    break;
                default:
                    $this->load->view("vista_solicitante_construccion");
                    break;
            }
        }else{
            $this->load->view("vista_solicitante_construccion");
        }
    }

    /***NUEVA FUNCION PARA EL PAGO DE NOMINAS PRESTAMOS Y OTROS**/
    public function otros(){
        if( in_array( $this->session->userdata('inicio_sesion')["rol"], array( 'FP' ) ) && in_array( $this->session->userdata('inicio_sesion')["depto"], array( 'CONTABILIDAD', 'CAPITAL HUMANO' ) ) )
            $this->load->view("vista_solicitante_recursos_humanos");
        else
            redirect("Login", "refresh");
    }
    /**********************************************************/
    /*
    public function Devolucion(){
        if( in_array( $this->session->userdata("inicio_sesion")['rol'], array( 'CE', 'CC') ) ){
            $this->load->view("vista_solicitante_cont_ingresos");
        }else if (in_array( $this->session->userdata("inicio_sesion")['rol'], array("AD", "FP" , "PV" ) )) {
            $this->load->view("vista_solicitante_devoluciones");
             //$this->load->view("vista_solicitante_devoluciones_new");
        }else{
            redirect("Login", "refresh");
        }
    }
    */
    /**---------------------------------------------------------INICIO-------------------------------------------------**/
    /**FUNCION QUE LLAMA LAS FACTURAS AUTORIZADAS O BORRADORS PARA SEGUIR EDITAR EN LA PARTEDE LOS DIRECTORORES DE AREA**/
    /**LA FUNCION DE ABAJO HACE EL TRABAJO DE TRAER TODAS LAS OPCIONES PARA QUE LOS DEL AREA SOLICITANTE PUEDAN TRABAJAR*/
    public function tabla_autorizaciones(){
        $resultado = $this->Solicitudes_solicitante->get_solicitudes_autorizadas_area();
        
        $numero_solicitudes = $this->Solicitudes_solicitante->solicitudes_formato()->num_rows();
        if(in_array($_SESSION['inicio_sesion']['id'], ['2367', '2637', '2673', '2692', '2843'])) //Para este usuario en especifico siempre trae informacion
            $numero_solicitudes = '1';
        
        if( $resultado->num_rows() > 0 ){
            $resultado = $resultado->result_array();
            $nuevoArray = array(); /** VALIDAR APROBACION TDC | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> | FECHA: 08-ABRIL-2024 **/
            for( $i = 0; $i < count( $resultado ); $i++ ){
                $aprobacion = false;
                /**TODAS LAS SOLICITUDES QUE SON CAJA CHICA SALTAN A DG Y SE CREA LA PROPIA ETAPA QUE ES GASTO CAJA CHICA PARA SEGUIMIENTO**/

                if( $resultado[$i]['caja_chica'] == 1 ){
                    $resultado[$i]['tgasto'] = "CAJA CHICA";
                    
                }elseif( $resultado[$i]['caja_chica'] == 2 ){
                    $resultado[$i]['tgasto'] = "TDC COMPROBANTE";
                    if ($resultado[$i]['idtitular'] === $this->session->userdata("inicio_sesion")['id']) {
                        $aprobacion = true;
                    }  
                }elseif( $resultado[$i]['servicio'] == 1 && $resultado[$i]['caja_chica'] == 0 ){
                    $resultado[$i]['tgasto'] = "SERVICIO";
                    
                }elseif( $resultado[$i]['caja_chica'] == 3 ){
                    $resultado[$i]['tgasto'] = "REEMBOLSO";
                    
				}elseif( $resultado[$i]['caja_chica'] == 4 ){
                    $resultado[$i]['tgasto'] = "VIÁTICOS";
                    
				}	else{
                    $resultado[$i]['tgasto'] = "PROVEEDOR";
                    
                }
                
                $resultado[$i]['opciones'] = $this->opciones_autorizaciones( $aprobacion, $resultado[$i]['idsolicitud'], $resultado[$i]['idetapa'], $resultado[$i]['idusuario'], $resultado[$i]['visto'], $resultado[$i]['prioridad'], $resultado[$i]['idAutoriza'], $resultado[$i]['nomdepto']);


                if($resultado[$i]['idetapa'] == 13){
                    $resultado[$i]['etapa'] = "Devolucion en espera";
                }

                if($resultado[$i]['idetapa'] == 25){
                    $resultado[$i]['etapa'] = "En espera";
                }

                $resultado[$i]['tgasto'] .= $resultado[$i]['prioridad'] == 1 ? "<br/><small class='label pull-center bg-red'>URGENTE</small>" : "";

                /** INICIO 
                 * VALIDAR APROBACION TDC
                 * FECHA: 08-ABRIL-2024
                 * Cambios: Se agrego un array en donde solo se insertaran los registros que cumplan ciertas condiciones
                 *  1. Si el usuario logueado es un DA, este solo podra ver las solicitudes en donde este sea titular de la tdc.
                 *  2. Solo los usuarios que sean titulares a una tdc podran aprobar la solicitud.
                 * @author Angel Victoriano <programador.analista30@ciudadmaderas.com>
                **/
                if ($resultado[$i]['caja_chica'] != 2) {
                    array_push($nuevoArray, $resultado[$i]);
                }
                
                if($resultado[$i]['caja_chica'] == 2 && $resultado[$i]['idtitular'] == $this->session->userdata("inicio_sesion")['id'] && $resultado[$i]['nomdepto'] == 'COMERCIALIZACION'){
                    array_push($nuevoArray, $resultado[$i]);
                }

                if($resultado[$i]['caja_chica'] == 2 && $resultado[$i]['nomdepto'] != 'COMERCIALIZACION' && $this->session->userdata("inicio_sesion")['rol'] == "DA"){
                    array_push($nuevoArray, $resultado[$i]);
                }
                /** FIN **/
            }

        }else{
            // $nuevoArray = array();/** VALIDAR APROBACION TDC | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> | FECHA: 08-ABRIL-2024 **/
            $resultado = array();
        }
        // echo json_encode( array( "data" => $nuevoArray, "por_autorizar" => $numero_solicitudes ));/** VALIDAR APROBACION TDC | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> | FECHA: 08-ABRIL-2024 **/
        echo json_encode( array( "data" => $resultado, "por_autorizar" => $numero_solicitudes ));
    }

    /**---------------------------------------------------------INICIO-------------------------------------------------**/
    /**FUNCION QUE LLAMA LAS FACTURAS AUTORIZADAS O BORRADORS PARA SEGUIR EDITAR EN LA PARTEDE LOS DIRECTORORES DE AREA**/
    /**LA FUNCION DE ABAJO HACE EL TRABAJO DE TRAER TODAS LAS OPCIONES PARA QUE LOS DEL AREA SOLICITANTE PUEDAN TRABAJAR*/
    public function tabla_autorizaciones_otros_gastos(){
        $resultado = $this->Solicitudes_solicitante->otros_gastos();
        $numero_solicitudes = $this->Solicitudes_solicitante->solicitudes_formato_otros_gastos()->num_rows();
        if( $resultado->num_rows() > 0 ){
            $resultado = $resultado->result_array();
            for( $i = 0; $i < count( $resultado ); $i++ ){
                $resultado[$i]['opciones'] = $this->opciones_autorizaciones('', $resultado[$i]['idsolicitud'], $resultado[$i]['idetapa'], $resultado[$i]['idusuario'], $resultado[$i]['visto'], $resultado[$i]['prioridad'], $resultado[$i]['idAutoriza'], $resultado[$i]['nomdepto'] );

                /**TODAS LAS SOLICITUDES QUE SON CAJA CHICA SALTAN A DG Y SE CREA LA PROPIA ETAPA QUE ES GASTO CAJA CHICA PARA SEGUIMIENTO**/
                if( $resultado[$i]['idetapa'] == 3 && $resultado[$i]['servicio'] == 1 ){
                    $resultado[$i]['etapa'] = "Pago de Servicio";
                }

                if( $resultado[$i]['idetapa'] == 3 && $resultado[$i]['servicio'] == 2 ){
                    $resultado[$i]['etapa'] = "Devolución";
                }

                if( $resultado[$i]['idetapa'] == 3 && $resultado[$i]['servicio'] == 3 ){
                    $resultado[$i]['etapa'] = "Nomina";
                }

                if( $resultado[$i]['idetapa'] == 3 && $resultado[$i]['servicio'] == 4 ){
                    $resultado[$i]['etapa'] = "Baja";
                }

                if( $resultado[$i]['idetapa'] == 3 && $resultado[$i]['servicio'] == 5 ){
                    $resultado[$i]['etapa'] = "Baja";
                }

                if( $resultado[$i]['idetapa'] == 3 && $resultado[$i]['servicio'] == 6 ){
                    $resultado[$i]['etapa'] = "Traspaso";
                }

            }
        }else{
            $resultado = array();
        }


        echo json_encode( array( "data" => $resultado, "por_autorizar" => $numero_solicitudes ));
    }

    public function tabla_autorizaciones_nominas_gastos(){
        $resultado = $this->Solicitudes_solicitante->otros_gastos_capital_humano( $this->input->post("opcion") );
        if( $resultado->num_rows() > 0 ){
            $resultado = $resultado->result_array();
            $nuevoArray = array(); /** VALIDAR APROBACION TDC | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> | FECHA: 08-ABRIL-2024 **/
            for( $i = 0; $i < count( $resultado ); $i++ ){
                /** INICIO 
                 * VALIDAR APROBACION TDC
                 * FECHA: 08-ABRIL-2024
                 * Cambios: Se agrego un array en donde solo se insertaran los registros que cumplan ciertas condiciones
                 *  1. Si el usuario logueado es un DA, este solo podra ver las solicitudes en donde este sea titular de la tdc.
                 *  2. Solo los usuarios que sean titulares a una tdc podran aprobar la solicitud.
                 * @author Angel Victoriano <programador.analista30@ciudadmaderas.com>
                **/
                $aprobacion = false;
                if( $resultado[$i]['caja_chica'] == 1 ){
                    
                }elseif( $resultado[$i]['caja_chica'] == 2 ){
                    if ($resultado[$i]['idtitular'] === $this->session->userdata("inicio_sesion")['id']) {
                        $aprobacion = true;
                    }  
                }elseif( $resultado[$i]['servicio'] == 1 && $resultado[$i]['caja_chica'] == 0 ){
                    
                }elseif( $resultado[$i]['caja_chica'] == 3 ){
                    
                }elseif( $resultado[$i]['caja_chica'] == 4 ){
                    
                }	else{
                    
                }
                $resultado[$i]['opciones'] = $this->opciones_autorizaciones($aprobacion, $resultado[$i]['idsolicitud'], $resultado[$i]['idetapa'], $resultado[$i]['idusuario'], $resultado[$i]['visto'], $resultado[$i]['prioridad'], $resultado[$i]['idAutoriza'], $resultado[$i]['nomdepto'] );
            
                $resultado[$i]['caja_chica'];

                if ($resultado[$i]['caja_chica'] != 2 ) {
                    array_push($nuevoArray, $resultado[$i]);
                }elseif($resultado[$i]['caja_chica'] == 2 && $resultado[$i]['idtitular'] == $this->session->userdata("inicio_sesion")['id'] && $resultado[$i]['nomdepto'] == 'COMERCIALIZACION'){
                    array_push($nuevoArray, $resultado[$i]);
                }elseif($resultado[$i]['caja_chica'] == 2 && $resultado[$i]['nomdepto'] != 'COMERCIALIZACION' && $this->session->userdata("inicio_sesion")['rol'] == "DA"){
                    array_push($nuevoArray, $resultado[$i]);
                }
                /** FIN **/
            }
        }else{
            $nuevoArray = array(); /** VALIDAR APROBACION TDC | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> | FECHA: 08-ABRIL-2024 **/
            // $resultado = array();
        }
        // echo json_encode( array( "data" => $resultado, "por_autorizar" => 0 ));
        echo json_encode( array( "data" => $nuevoArray, "por_autorizar" => 0 )); /** VALIDAR APROBACION TDC | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> | FECHA: 08-ABRIL-2024 **/
    }

    public function otros_gastos_capital_humano_encurso(){
        echo json_encode( array( "data" => $this->Solicitudes_solicitante->otros_gastos_capital_humano_encurso( $this->input->post("opcion") )->result_array(), "idloguin_usuario" => $this->session->userdata("inicio_sesion")['id'] ));
    }



    /**NO SEPARAR ESTA FUNCION CON LA DE ARRIBA ESTAS HACEN EL TRABAJO DE COLOCAR LAS OPCIONES*/
    public function opciones_autorizaciones($responsable, $idsolicitud, $estatus, $autor, $visto, $prioridad, $dg, $departamento){

        $opciones = '<div class="btn-group-vertical">';

        $opciones .= '<button  type="button" class="btn btn-sm btn-primary consultar_modal notification" value="'.$idsolicitud.'" data-value="BAS" title="VER SOLICITUD"><i class="fas fa-eye"></i>'.( $visto == 0 ? '</i><span class="badge">!</span>' : '').'</button>';

        switch( $estatus ){
            case 52:
                if( $this->session->userdata('inicio_sesion')["im"] && in_array( $this->session->userdata("inicio_sesion")['id'], [ 2, 1967, 2187 ] ) ){
                    $opciones .= '<button  type="button" class="btn btn-md btn-success enviar_a_dg" value="'.$idsolicitud.'" title="ENVIAR SOLICITUD"><i class="fas fa-share-square"></i></button>';
                    $opciones .= '<button  type="button" class="btn btn-sm btn-danger rechazada_da" value="'.$idsolicitud.'" title="SOLICITUD RECHAZADA"><i class="fas fa-ban"></i></button>';
                }
                break;
            case 1:
            case 6:
                if( $autor == $this->session->userdata("inicio_sesion")['id'] ){
                    if( ( in_array( $departamento, array( 'DEVOLUCIONES', 'DEVOLUCION' )) || in_array( $this->session->userdata("inicio_sesion")['rol'], array( 'CC', 'CX' ) ) && $estatus == 1 ) || !in_array( $departamento, array( 'DEVOLUCIONES', 'DEVOLUCION' ) ) ){
                        $opciones .= '<button  type="button" class="btn btn-sm btn-warning editar_factura" value="'.$idsolicitud.'" title="EDITAR SOLICITUD"><i class="fas fa-pencil-alt"></i></button>';
                    }
                    
                    $opciones .= '<button  type="button" class="btn btn-sm btn-success enviar_a_dg" value="'.$idsolicitud.'" title="ENVIAR SOLICITUD"><i class="fas fa-share-square"></i></button>';
                    $opciones .= '<button  type="button" class="btn btn-sm btn-danger borrar_solicitud" value="'.$idsolicitud.'" title="BORRAR SOLICITUD"><i class="fas fa-trash"></i></button>';
                }elseif($this->session->userdata("inicio_sesion")['im'] == 5){
                    $opciones .= '<button  type="button" class="btn btn-sm btn-warning editar_factura" value="'.$idsolicitud.'" title="EDITAR SOLICITUD"><i class="fas fa-pencil-alt"></i></button>';
                    $opciones .= '<button  type="button" class="btn btn-sm btn-success enviar_a_dg" value="'.$idsolicitud.'" title="ENVIAR SOLICITUD"><i class="fas fa-share-square"></i></button>';
                    $opciones .= '<button  type="button" class="btn btn-sm btn-danger borrar_solicitud" value="'.$idsolicitud.'" title="BORRAR SOLICITUD"><i class="fas fa-trash"></i></button>';
                }
                break;
            case 2:
                if( $this->session->userdata("inicio_sesion")['rol'] == 'AS' && in_array( $this->session->userdata("inicio_sesion")['id'], array( '45', '51' ) ) ){
                    $opciones .= '<button  type="button" class="btn btn-sm btn-danger rechazada_da" value="'.$idsolicitud.'" title="SOLICITUD RECHAZADA"><i class="fas fa-ban"></i></button>';
                    $opciones .= '<button  type="button" class="btn btn-sm btn-danger borrar_solicitud" value="'.$idsolicitud.'" title="BORRAR SOLICITUD"><i class="fa fa-trash"></i></button>';
                }
                break;
            case 3:
                if ($responsable) {/** VALIDAR APROBACION TDC | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> | FECHA: 08-ABRIL-2024 **/
                    $opciones .= '<button  type="button" class="btn btn-sm btn-success aprobada_da" id="opEv'.$idsolicitud.'" value="'.$idsolicitud.'" data-value="'.$responsable.'" title="APROBAR"><i class="fas fa-thumbs-up"></i></button>';
                    $opciones .= '<button  type="button" class="btn btn-sm btn-danger rechazada_da" value="'.$idsolicitud.'" title="SOLICITUD RECHAZADA"><i class="fas fa-ban"></i></button>';
                    break;
                }

                if( $this->session->userdata("inicio_sesion")['rol'] == 'DA' || $this->session->userdata("inicio_sesion")['rol'] == 'CC' || $this->session->userdata("inicio_sesion")['rol'] == 'SU' ){
                    $opciones .= '<button  type="button" class="btn btn-sm btn-success aprobada_da" id="opEv'.$idsolicitud.'" value="'.$idsolicitud.'" title="APROBAR"><i class="fas fa-thumbs-up"></i></button>';
                    $opciones .= '<button  type="button" class="btn btn-sm btn-danger rechazada_da" value="'.$idsolicitud.'" title="SOLICITUD RECHAZADA"><i class="fas fa-ban"></i></button>';
                    $opciones .= '<button type="button" class="btn btn-sm btn-default congelar_solicitud" value="'.$idsolicitud.'" title="SOLICITUD STAND BYE"><i class="fas fa-power-off"></i></button>';
                }

                if( $this->session->userdata("inicio_sesion")['rol'] == 'AS' ){
                    $opciones .= '<button  type="button" class="btn btn-sm btn-danger rechazada_da" value="'.$idsolicitud.'" title="SOLICITUD RECHAZADA"><i class="fas fa-ban"></i></button>';
                    $opciones .= '<button  type="button" class="btn btn-sm btn-danger borrar_solicitud" value="'.$idsolicitud.'" title="BORRAR SOLICITUD"><i class="fa fa-trash"></i></button>';
                }

                if( $this->session->userdata("inicio_sesion")['rol'] == 'CP' && ( $this->session->userdata("inicio_sesion")['id'] == $this->session->userdata("inicio_sesion")['da']) ){
                    $opciones .= '<button  type="button" class="btn btn-sm btn-success aprobada_da" value="'.$idsolicitud.'" title="APROBAR"><i class="fas fa-thumbs-up"></i></button>';
                    $opciones .= '<button  type="button" class="btn btn-sm btn-danger rechazada_da" value="'.$idsolicitud.'" title="SOLICITUD RECHAZADA"><i class="fas fa-ban"></i></button>';
                    $opciones .= '<button type="button" class="btn btn-sm btn-default congelar_solicitud" value="'.$idsolicitud.'" title="SOLICITUD STAND BYE"><i class="fas fa-power-off"></i></button>';
                }
                if( $this->session->userdata('inicio_sesion')["im"] == 2 ){
                    $opciones .= '<button  type="button" class="btn btn-sm btn-warning editar_factura" value="'.$idsolicitud.'" title="EDITAR SOLICITUD"><i class="fas fa-pencil-alt"></i></button>';
                    $opciones .= '<button  type="button" class="btn btn-sm btn-danger borrar_solicitud" value="'.$idsolicitud.'" title="BORRAR SOLICITUD"><i class="fas fa-trash"></i></button>';
                }
                if( in_array( $this->session->userdata('inicio_sesion')["id"], array('2187', '2727') ) && $this->session->userdata("inicio_sesion")['rol'] == 'FP' && $this->session->userdata('inicio_sesion')["depto"] == 'CONTABILIDAD'){
                    $opciones .= '<button  type="button" class="btn btn-sm btn-warning editar_factura" value="'.$idsolicitud.'" title="EDITAR SOLICITUD"><i class="fas fa-pencil-alt"></i></button>';
                }
                break;
            case 4:
                if( ($this->session->userdata("inicio_sesion")['rol'] == 'AS' &&  $this->session->userdata("inicio_sesion")['depto']=="CONSTRUCCION") || $this->session->userdata("inicio_sesion")['id']==45){
                    $opciones .= '<button  type="button" class="btn btn-sm btn-warning editar_factura" value="'.$idsolicitud.'" title="EDITAR SOLICITUD"><i class="fas fa-pencil-alt"></i></button>';
                    $opciones .= '<button  type="button" class="btn btn-sm btn-success enviar_a_dg" value="'.$idsolicitud.'" title="ENVIAR SOLICITUD"><i class="fas fa-share-square"></i></button>';
                }
                $opciones .= '<button  type="button" class="btn btn-sm btn-danger borrar_solicitud" value="'.$idsolicitud.'" title="BORRAR SOLICITUD"><i class="fa fa-trash"></i></button>';
                break;
            case 13:
                if( $this->session->userdata("inicio_sesion")['rol'] == 'CC' ){
                    $opciones .= '<button type="button" class="btn btn-sm btn-success liberar_solicitud" value="'.$idsolicitud.'" title="LIBERAR PAGO SOLICITUD"><i class="fas fa-power-off"></i></button>';
                }
                break;
            case 8:
            case 21:
            case 30:
                if( $autor == $this->session->userdata("inicio_sesion")['id'] ){
                    if ($this->session->userdata("inicio_sesion")['depto'] != 'DEVOLUCIONES' ){
                        $opciones .= '<button  type="button" class="btn btn-sm btn-warning editar_factura" value="'.$idsolicitud.'" title="EDITAR SOLICITUD"><i class="fas fa-pencil-alt"></i></button>';
                    }
                    //$opciones .= '<button type="button" class="btn btn-danger editar_factura" value="'.$idsolicitud.'"><i class="fas fa-pencil-alt"></i></button>';
                    $opciones .= '<button  type="button" class="btn btn-sm btn-success reenviar_factura" value="'.$idsolicitud.'" title="APROBAR"><i class="fas fa-thumbs-up"></i></button>';
                    $opciones .= '<button  type="button" class="btn btn-sm btn-danger borrar_solicitud" value="'.$idsolicitud.'" title="BORRAR SOLICITUD"><i class="fa fa-trash"></i></button>';
                }elseif($this->session->userdata("inicio_sesion")['im'] == 5){
                    $opciones .= '<button  type="button" class="btn btn-sm btn-warning editar_factura" value="'.$idsolicitud.'" title="EDITAR SOLICITUD"><i class="fas fa-pencil-alt"></i></button>';
                    $opciones .= '<button  type="button" class="btn btn-sm btn-success reenviar_factura" value="'.$idsolicitud.'" title="APROBAR"><i class="fas fa-thumbs-up"></i></button>';
                    $opciones .= '<button  type="button" class="btn btn-sm btn-danger borrar_solicitud" value="'.$idsolicitud.'" title="BORRAR SOLICITUD"><i class="fa fa-trash"></i></button>';
                }
                break;
            case 25:
                if( $this->session->userdata("inicio_sesion")['rol'] == 'DA' || $this->session->userdata("inicio_sesion")['rol'] == 'CC' || $this->session->userdata("inicio_sesion")['rol'] == 'SU' ){
                    $opciones .= '<button type="button" class="btn btn-sm btn-success liberar_solicitud" value="'.$idsolicitud.'"><i class="fas fa-power-off"></i></button>';
                }
                break;
        }

        return $opciones."</div>";
    }
    /**---------------------------------------------------------FIN-------------------------------------------------**/

    public function tabla_facturas_finaliazadas(){
        echo json_encode( array( "data" => $this->Solicitudes_solicitante->get_solicitudes_pagadas_area()->result_array() ));
    }

    public function tabla_pagos_sin_factura(){
        echo json_encode( array( "data" => $this->Solicitudes_solicitante->getPagossfactura()->result_array() ));
    }

    public function tabla_pagos_sin_complemento(){
        echo json_encode( array( "data" => $this->Solicitudes_solicitante->getPagossProvision()->result_array() ));
    }

    public function tabla_facturas_encurso(){
        $tipo_reporte = $this->input->post("tipo_reporte") ? $this->input->post("tipo_reporte") : NULL;
        $fechaInicial = $this->input->post("fechaInicial") 
            ? ( DateTime::createFromFormat('d/m/Y', $this->input->post("fechaInicial")) )->format('Y-m-d') 
            : date('Y').'01-01';
        $fechaFinal = $this->input->post("fechaFinal")
            ? ( DateTime::createFromFormat('d/m/Y', $this->input->post("fechaFinal")) )->format('Y-m-d')
            : date('Y-m-d');
        $data = $this->Solicitudes_solicitante->get_solicitudes_en_curso( $tipo_reporte, $fechaInicial, $fechaFinal )->result_array();
        echo json_encode( 
            array( "data" => $data, 
                   "idloguin_usuario" => $this->session->userdata("inicio_sesion")['id'],
                   "tipo_registros" => $this->input->post("tipo_reporte")
            )
        );
    }

    public function tabla_facturas_encurso_otros_gastos(){
        echo json_encode( array( "data" => $this->Solicitudes_solicitante->otros_gastos_curso()->result_array() ));
    }

     public function tabla_facturas_encurso_nominas_gastos(){
        echo json_encode( array( "data" => $this->Solicitudes_solicitante->otros_gastos_curso_nominas()->result_array() ));
    }


    public function tabla_facturas_encurso_nominas(){
        echo json_encode( array( "data" => $this->Solicitudes_solicitante->otros_gastos_nomina()->result_array() ));
    }


    public function enviar_nomina(){
        $respuesta = array( "resultado" => FALSE );

        if( $this->input->post("idsolicitud") ){
            //$respuesta['resultado'] = $this->db->query("UPDATE solpagos SET solpagos.idetapa = 11, solpagos.fecha_autorizacion = '".date("Y-m-d H:i:s")."', solpagos.idAutoriza = '".$this->session->userdata("inicio_sesion")['id']."'  WHERE solpagos.idetapa = 1 AND idsolicitud = '".$this->input->post("idsolicitud")."'");
            $respuesta['resultado'] = $this->db->query("CALL insertar_pago_nomina( ?, ?, ?)", array(
                "fecha " => date("Y-m-d H:i:s"),
                "idsolicitud " => $this->input->post("idsolicitud"),
                "idautor " => $this->session->userdata("inicio_sesion")['id']
            )) ? TRUE : FALSE;
            
            log_sistema($this->session->userdata("inicio_sesion")['id'], $this->input->post("idsolicitud"), "SE HA ENVIADO NOMINA");
        }

        echo json_encode( $respuesta );
    }

    public function cargaxml(){
        
        $respuesta = array( "respuesta" => array( FALSE, "HA OCURRIDO UN ERROR") );
        if( isset( $_FILES ) && !empty($_FILES) ){

            $config['upload_path'] = './UPLOADS/XMLS/';
            $config['allowed_types'] = '*';
            
            //CARGAMOS LA LIBRERIA CON LAS CONFIGURACIONES PREVIAS -----$this->upload->display_errors()
            $this->load->library('upload', $config);

            if( $this->upload->do_upload("xmlfile") ){

                $xml_subido = $this->upload->data()['full_path'];

                $datos_xml = $this->Complemento_cxpModel->leerxml( $xml_subido, TRUE );
                $datos_xml['cch'] = FALSE;


                $this->load->model("Provedores_model");
                $informacion_proveedor = $this->Solicitudes_solicitante->verificar_proveedor( $datos_xml['rfcemisor'], in_array( $this->input->post("cajachica"), array( "9", "11", "12", "13" ) ));
                $respuesta['proveedorcc'] = $informacion_proveedor->result_array();
                //VERIFICAMOS SI EL PROVEEDOR COMO CAJA EXISTE PARA PROCEDER A DARLO DE ALTA
                if( in_array( $this->input->post("cajachica"), array( "9", "11", "12", "13" ) ) ){
                    
                    if( $informacion_proveedor->num_rows() == 0 ){
                        $data = array(
                            "rfc" => $datos_xml['rfcemisor'],
                            "nombre" => ( $datos_xml['nomemi'] ? $datos_xml['nomemi'] : $datos_xml['rfcemisor'] ),
                            "cuenta" => NULL,
                            "idbanco" => 6,
                            "tipocta" => 40,
                            "tipo_prov" => 0,
                            "estatus" => 4,
                            "fecadd" => date("Y-m-d H:i:s"),
                            "idby" => $this->session->userdata("inicio_sesion")['id'],
                            "rf_proveedor"=> $datos_xml['regfisemisor'],
                            "cp_proveedor" =>$datos_xml['LugarExpedicion'],
                        );

                        $this->Provedores_model->insertar_nuevo( $data );
                        $data[0]['idproveedor'] = $this->db->insert_id();
                        $data[0]['nom_banco'] = "SIN DEFINIR";
                        $respuesta['proveedorcc']  = $data;
                        $datos_xml['cch'] = 1;
                    }else{
                        $datos_xml['cch'] = $informacion_proveedor->row()->tinsumo ? $informacion_proveedor->row()->tinsumo : 1;
                    }
                }

                if( $datos_xml['version'] >= 4.0){
					if($datos_xml['TipoDeComprobante']== 'E'){
						$respuesta['respuesta'] = array( FALSE, "NO PUEDES SUBIR FACTURA CON TIPO DE COMPROBANTE EGRESO");
					}else{
						if( !$datos_xml["TipoRelacion"] || $datos_xml["TipoRelacion"][0] == "04" ){

							if(!$datos_xml['uuid']){
                                $respuesta = array( "respuesta" => array( FALSE, "XML NO CONTIENE UUID.") );
                                echo json_encode($respuesta);
                                exit;
                            }
                            
                            $responsable_factura = $this->Solicitudes_solicitante->verificar_uuid( $datos_xml['uuid'] );

							if( isset( $datos_xml['formpago'][0] ) && in_array( $datos_xml['formpago'][0] , array( 1, 3, 4, 5, 6,17, 28, 31, 99, 29 ) ) ){

								$es_caja_chica = ($this->input->post("cajachica") == 9 || $this->input->post("cajachica") == 12 || $this->input->post("cajachica") == 13) && ( ( in_array( $datos_xml['formpago'][0] , array( 1, 3, 4, 5, 6, 28, 31 ) ) && $datos_xml['MetodoPago'][0] == "PUE" ) || ( $informacion_proveedor->num_rows() > 0 && $informacion_proveedor->row()->tinsumo) );
								$es_pago_proveedor = in_array( $this->input->post("cajachica"), array( 9, 10, 11 ) ) === FALSE && ( ( $datos_xml['formpago'][0] == "99" && $datos_xml['MetodoPago'][0] == "PPD" ) || ( $informacion_proveedor->num_rows() > 0 && $informacion_proveedor->row()->tinsumo ) );
								$es_intercambio =  in_array( $datos_xml['formpago'][0], [17] ) && in_array($datos_xml["usocfdi"],["G01","G03"]) && $datos_xml['MetodoPago'][0] == "PUE" && $this->input->post("cajachica") == 10;
								$es_pago_credito = in_array( $datos_xml['formpago'][0] , [04, 29] ) /* && in_array($datos_xml["usocfdi"],["G01","G03"])*/ && $datos_xml['MetodoPago'][0] == "PUE" && $this->input->post("cajachica") == 11;
								$es_viatico = $this->input->post("cajachica") == 13 && ( ( in_array( $datos_xml['formpago'][0] , array( 1, 3, 4, 5, 6, 28, 31 ) ) && $datos_xml['MetodoPago'][0] == "PUE" ) || ( $informacion_proveedor->num_rows() > 0 && $informacion_proveedor->row()->tinsumo) );
								$es_reembolso = $this->input->post("cajachica") == 12 && ( ( in_array( $datos_xml['formpago'][0] , array( 1, 3, 4, 5, 6, 28, 31 ) ) && $datos_xml['MetodoPago'][0] == "PUE" ) || ( $informacion_proveedor->num_rows() > 0 && $informacion_proveedor->row()->tinsumo) );



								if( $es_caja_chica || $es_pago_proveedor || $es_intercambio || $es_pago_credito ){
									if( $responsable_factura->num_rows() === 0 ){
										//
										$consultaEmpresa = $this->Complemento_cxpModel->verificar_empresa( $datos_xml['rfcrecep'] );//
										if( $consultaEmpresa ->num_rows() >= 1  || $this->input->post("cajachica") == 1){

											if( $this->Solicitudes_solicitante->verificar_proveedor69b( $datos_xml['rfcemisor'], $datos_xml['nomemi'] )->num_rows() == 0 ){
												if( $informacion_proveedor->num_rows() >= 1 || ( in_array( $this->input->post("cajachica"), array( "9", "11", "13" ) ) ) ){
													//
													$consultaProveedor = $this->Complemento_cxpModel->verifica_cat_prov( $datos_xml['rfcemisor'] ) ;
													if( ( /*$consultaProveedor->num_rows()  > 0  && */$this->Solicitudes_solicitante->verificar_proveedor_permitido( $datos_xml['rfcemisor'] )->num_rows() === 0 ) || in_array( $this->input->post("cajachica"), array( "9", "11", "13" ) ) ){   

														if( ( in_array( $this->input->post("cajachica"), array( "9", "11", "12", "13" ) ) ) || in_array( $informacion_proveedor->row()->eactual, array( "1", "2", "5" ) ) ){

															/*
															$poste_anterior = date("Y-m", strtotime(date("Y-m").' -1 month'))."-01";
															$poste_superior = date('Y-m')."-10";

															if( $poste_anterior <= $fecha_actual && $fecha_actual <= $poste_superior ){
																$limite_inferior = $poste_anterior;
																$limite_superior = $poste_superior;            
															}else{
																$limite_superior = date('Y-m', strtotime(date("Y-m").' +1 month'))."-10";
																$limite_inferior = date('Y-m')."-01";
															}
															//$validar_fecha = $fechaxml >= $limite_inferior && $fechaxml <= $limite_superior;
															*/

															//$validar_fecha = $fechaxml >= date("Y")."2019-01-01" && $fechaxml <= date("Y")."2020-12-31";
															//$validar_fecha = $fechaxml >= "2019-01-01" && $fechaxml <= "2020-12-31";
															//VERIFICAMOS SI LA FECHA DEL XML ES MAYOR AL 12 DE ENERO 2021

															//$validar_fecha =( ( date("Y-m-d") <= '2021-01-18' ) || ( $fechaxml >= date("Y")."-01-01" ) );
															//  $validar_fecha = ( date( "Y-m-d" ) <= "2022-01-09" && $fechaxml >= "2021-01-01" && $fechaxml <= "2022-01-09" ) || $fechaxml >= date("Y")."-01-01";
															$fechaxml =  date('Y-m', strtotime( substr( $datos_xml['fecha'], 0, 10) ));
															$fecha_actual = date("Y-m-d");
															$validar_fecha = (($fechaxml == date("Y-m") ) || (date("Y-m",strtotime($fecha_actual."- 1 month")) == $fechaxml && ($fecha_actual <= date("Y-m")."-06") ));

															if( $validar_fecha || (in_array($this->session->userdata("inicio_sesion")['depto'], ['ADMINISTRACION']) && $this->session->userdata("inicio_sesion")['rol'] =='CP') ) {

																switch( $this->session->userdata("inicio_sesion")['depto'] ){
																	/*
																	case 'CONSTRUCCION':
																		$respuesta['limite'] =  20000.00;
																		$limite_inferior = 20000.00;
																		$limite_superior = 20000.00;
																		break;
																	case 'JARDINERIA':
																		$respuesta['limite'] =  10000.00;
																		$limite_inferior = 10000.00;
																		$limite_superior = 10000.00;
																		break;
																	case 'SUB DIRECCION':
																		$respuesta['limite'] =  10000.00;
																		$limite_inferior = 10000.00;
																		$limite_superior = 10000.00;
																		break;
																	case 'DIRECCION GENERAL':
																		$respuesta['limite'] =  10000.00;
																		$limite_inferior = 10000.00;
																		$limite_superior = 10000.00;
																		break;
																	case 'ADMINISTRACION':
																		$respuesta['limite'] =  10000.00;
																		$limite_inferior = 10000.00;
																		$limite_superior = 10000.00;
																		break;
																	case 'ADMON OFICINA':
																		$respuesta['limite'] =  20000.00;
																		$limite_inferior = 20000.00;
																		$limite_superior = 20000.00;
																		break;
																	*/
																	default:
																		$respuesta['limite'] = 20000.00;
																		$limite_inferior = 20000.00;
																		$limite_superior = 20000.00;
																		break;
																}

																$es_caja_chica = ( $this->input->post("cajachica") == 9 && (
																( $datos_xml['formpago'][0] == 1 && $datos_xml['Total'][0] <= $limite_inferior  ) || 
																( in_array( $datos_xml['formpago'][0] , array( 3, 4, 5, 6, 28, 31 ) ) && $datos_xml['Total'][0] >= 0 && $datos_xml['Total'][0] <= $limite_superior ) ||
																$informacion_proveedor->row()->tinsumo ) );

																if( $es_caja_chica || ( $es_pago_proveedor || $es_intercambio || $es_intercambio || $es_pago_credito || $es_viatico || $es_reembolso) ) {
																	$datos_xml["folio_fiscal"] = substr( $datos_xml['uuid'], -5);
																	// Datos para la facturacion 4.0  date( "Y-m-d" )
																	if($fechaxml <="2023-04-01"){
																		// antes del plazo puede subir la factura
																		 $respuesta['respuesta'] = array( TRUE, "RECUERDA LOS SIGUIENTES CAMPOS SON OBLIGATORIOS PARA FACTURAS \n EMITIDAS A PARTIR DE ENERO 2023: \n --- RÉGIMEN FISCAL \n --- CÓDIGO POSTAL  \n DE EMISOR Y RECEPTOR");
																		 $respuesta['datos_xml'] = $datos_xml;
																	}else{
																		if( $es_caja_chica || in_array( $this->input->post("cajachica"), array( "9", "11", "12", "13" ) ) || ( $datos_xml["regfisemisor"] && $consultaProveedor->num_rows() > 0 && in_array($datos_xml["regfisemisor"],[$consultaProveedor->row()->rf_proveedor])  && $datos_xml["regfisrecep"] && ( ($consultaEmpresa->num_rows() > 0 && $this->input->post("cajachica") != 1) ? in_array($datos_xml["regfisrecep"],[$consultaEmpresa->row()->regf_recep]) : 1 ) )){
																			$consultaEmpresa->num_rows() <= 0 && $this->input->post("cajachica") == 1
																				? $respuesta['servicio_ecm'] = true
																				: $respuesta['servicio_ecm'] = false;
																			$respuesta['respuesta'] = array( TRUE);
																			$respuesta['datos_xml'] = $datos_xml;
																		}else{

																			if( $consultaProveedor->num_rows() > 0 ){

																				$respuesta['respuesta'] = array( FALSE, "La factura no cuenta con el campo de régimen fiscal del emisor y a partir de la versión 4.0 es un dato requerido, es necesario que se solicite la refacturación al proveedor." );

																				if( !in_array($datos_xml["regfisemisor"],[$consultaProveedor->row()->rf_proveedor]) ){
																					$respuesta['respuesta'] = array( FALSE, "El régimen fiscal del proveedor, no coincide con el dado de alta previamente en el sistema, favor de enviar la CSF actualizada a su ejecutivo de CXP para realizar la actualización de datos." );
																				}

																				if( !in_array($datos_xml["regfisrecep"],[$consultaEmpresa->row()->regf_recep]) ){
																					$respuesta['respuesta'] = array( FALSE, "EL RÉGIMEN FISCAL DEL RECEPTOR (EMPRESA) NO COINCIDE CON EL DADO DE ALTA EN NUESTRA BD. FAVOR DE REPORTAR CON EJECUTIVO DE CXP." );
																				}

																			}else{
																				$respuesta['respuesta'] = array( FALSE, "El régimen fiscal del proveedor, no coincide con el dado de alta previamente en el sistema, favor de enviar la CSF actualizada a su ejecutivo de CXP para realizar la actualización de datos." );
																			}
																		}
																	}
																	// termina Facturacion 4.0
																}else{
																	$respuesta['respuesta'] = array( FALSE, "EL TOTAL DE ESTA FACTURA ES MAYOR AL PERMITIDO, DEBIDO A LA FORMA DE PAGO. EFECTIVO DEBE SER MENOR A $ 2,000.00. TRANSFERENCIA ELECTRONICA, TARJETA DE CREDITO O DEBITO Y/O MONEDERO ELECTRONICO MAYOR A $ 2,000.00 Y MENOR A $ 5,000.00." );
																}
															}else{
																$respuesta['respuesta'] = array( FALSE, "LAS FACTURAS DEL MES ANTERIOR AL ACTUAL SOLO SE PUEDEN CARGAR HASTA EL DIA 5" );
															}
														}else{
															if( $informacion_proveedor->row()->eactual == 9 )
																$respuesta['respuesta'] = array( FALSE, "AÚN NO SE HA VALIDADO LA DOCUMENTACIÓN DE ESTE PROVEEDOR.");
															else
																$respuesta['respuesta'] = array( FALSE, "ESTE PROVEEDOR HA SIDO BLOQUEADO POR EL ÁREA DE CUENTAS POR PAGAR. ".strtoupper($informacion_proveedor->row()->observaciones));
														}
													}else{
														$respuesta['respuesta'] = array( FALSE, "EL PROVEEDOR SE ENCUENTRA BLOQUEADO ".$datos_xml['rfcemisor']." HAY GASTOS PENDIENTES POR COMPROBAR." );
													}
												}else{
													$respuesta['respuesta'] = array( FALSE, "EL PROVEEDOR NO HA SIDO DADO DE ALTA");
												}
											}else{
												$respuesta['respuesta'] = array( FALSE, "NO ESTÁ PERMITIDO RECIBIR FACTURAS DE ESTE PROVEEDOR POR QUE INFRINGE EL CÓDIGO FISCAL DE LA FEDERACIÓN EN SU ARTÍCULO 69-B.");
											}
										}else{
											$respuesta['respuesta'] = array( FALSE, "EMPRESA RECEPTORA ERRONEA SOLICITE UNA REFACTURACIÓN");
										}
									}else{
										$responsable_factura = $responsable_factura->row();
										$respuesta['respuesta'] = array( FALSE, "¡FACTURA YA EXISTENTE EN EL SISTEMA!\nSE HA CARGADO POR $responsable_factura->nombre_completo.\nEL $responsable_factura->feccrea.\nEN EL REGISTRO #$responsable_factura->idsolicitud.");
									}
								}
								else{

									if( $this->input->post("cajachica") == 9 && !$es_caja_chica )
										$respuesta['respuesta'] = array( FALSE, "LA FACTURA NO CUMPLE CON LAS CARACTERÍSTICAS PARA SER CONSIDERADA COMO UN GASTO DE CAJA CHICA. VERIFIQUE EL METODO Y/O LA FORMA DE PAGO.");

									if( in_array( $this->input->post("cajachica"), array( 9, 10, 11 ) ) === FALSE && !$es_pago_proveedor )
										$respuesta['respuesta'] = array( FALSE, "LA FACTURA NO CUMPLE CON LAS CARACTERÍSTICAS PARA SER CONSIDERADA COMO UN GASTO DE PROVEEDOR. VERIFIQUE EL METODO Y LA FORMA DE PAGO.");

									if( $this->input->post("cajachica") == 10 && !$es_intercambio )
										$respuesta['respuesta'] = array( FALSE, "LA FACTURA NO CUMPLE CON LAS CARACTERÍSTICAS PARA SER CONSIDERADA COMO UN GASTO DE INTERCAMBIO. VERIFIQUE EL METODO Y/O LA FORMA DE PAGO.");

									if( $this->input->post("cajachica") == 11 && !$es_pago_credito )
										$respuesta['respuesta'] = array( FALSE, "LA FACTURA NO CUMPLE CON LAS CARACTERÍSTICAS PARA SER CONSIDERADA COMO UN GASTO DE COMPROBANTE DE T CREDITO. VERIFIQUE EL MÉTODO Y/O LA FORMA DE PAGO.");

									if( $this->input->post("cajachica") == 12 && !$es_reembolso )
										$respuesta['respuesta'] = array( FALSE, "LA FACTURA NO CUMPLE CON LAS CARACTERÍSTICAS PARA SER CONSIDERADA COMO UN GASTO DE COMPROBANTE DE REEMBOLSO. VERIFIQUE EL MÉTODO Y/O LA FORMA DE PAGO.");

								}
							}else{
								$respuesta['respuesta'] = array( FALSE, "LA FORMA DE PAGO NO ESTA PERMITIDO");
							}
						}else{
							$respuesta['respuesta'] = array( FALSE, "ESTA NO ES UNA FACTURA DE TIPO DE GASTOS");
						}
					}
                }
                else{
                    $respuesta['respuesta'] = array( FALSE, "LA VERSION DE LA FACTURA ES INFERIOR A LA 4.0, SOLICITE UNA REFACTURACIÓN");
                }

                unlink( $xml_subido );

            }else{
                $respuesta['respuesta'] = array( FALSE, $this->upload->display_errors() );
            }

        }
        
        echo json_encode( $respuesta );
    }

    public function guardar_solicitud(){
        $resultado = array("resultado" => TRUE);
        // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);

        if( (isset($_POST) && !empty($_POST)) || ( isset( $_FILES ) && !empty($_FILES) ) ){
            $this->db->trans_begin();
            $mensaje = "";
            $resp_data = "";
            if( $this->input->post('responsable_cc') ){
                $responsable = $this->input->post('responsable_cc');
            }else{
                if( $this->session->userdata("inicio_sesion")['rol'] == 'DA' ){
                    $responsable = $this->session->userdata("inicio_sesion")['id'];
                }else{
                    $responsable = $this->session->userdata("inicio_sesion")['da'];
                }
            }

            if( $this->input->post('servicio1') == 11 ){
                $departamento = $this->db->query("SELECT usuarios.depto FROM usuarios JOIN ( SELECT idresponsable FROM tcredito WHERE idtcredito = ? ) tc ON usuarios.idusuario = tc.idresponsable", [ $this->input->post('responsable_cc') ])->row()->depto;
            }elseif( $this->input->post('responsable_cc') && ( $this->input->post('servicio') == 9 && $this->input->post('servicio1') == 9 ) ){
                $departamento = $this->db->query("SELECT usuarios.depto FROM usuarios WHERE usuarios.idusuario = '".$this->input->post('responsable_cc')."'")->row()->depto;
            }else{
                $departamento = $this->session->userdata("inicio_sesion")['depto'];
            }

            //TIPO DE CONSUMO / SERVICIO
            if( $this->input->post('servicio1') == 11 ){
                $caja_chica = 2;
            // }elseif( $this->input->post('servicio') == 9 || $this->input->post('servicio1') == 9 ){
            }elseif( $this->input->post('servicio1') == 9 ){ /** FECHA: 08-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                $caja_chica = 1;
            }elseif( $this->input->post('servicio1') == 12){
				$caja_chica = 3;
			}elseif( $this->input->post('servicio1') == 13){
				$caja_chica = 4;//viaticos
			}
            else{
                $caja_chica = 0;
            }
            
            $idinsumo = 0;
            if($this->input->post('newInsumo')!='' || $this->input->post('newInsumo') != null) {
                $nuevo_insumo =  $this->input->post('newInsumo').strtoupper(limpiar_dato($this->input->post('textinsumo')));
                $row_insumo = $this->db->query("SELECT * FROM insumos WHERE insumo = ? ",[$nuevo_insumo]);
                if($row_insumo->num_rows() == 0){
                    // insertar insumo
                    $this->db->query("INSERT INTO insumos(insumo, estatus) VALUES('".$nuevo_insumo."',1);");
                    $idinsumo = $this->db->insert_id();
                    $devInsumo=["idinsumo"=>$idinsumo, "insumo"=>$nuevo_insumo];
                }else{
                    $idinsumo = $row_insumo->row()->idinsumo;
                }
            }else{
                $idinsumo = $this->input->post('insumo');
            } 

            if ($caja_chica == 4) { /** INICIO FECHA: 08-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                $servicio = 41;
            } elseif (in_array($caja_chica, array(1, 4))) {
                $servicio = $idinsumo;
            } else {
                $servicio = $this->input->post('servicio1');
            } /** FIN FECHA: 08-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
            
            $pais_gasto = '';
            if($caja_chica == 4 && $this->input->post('paisRM') != null ){
                if($this->input->post('paisRM') != '6'){
                    $pais_gasto = 'MX';
                }else{
                    $pais_gasto = 'EU';
                }
            }else{
                $pais_gasto = $this->input->post('paisRM');
            }
            
            $regex = "/^[0-9]+$/";
            
            $data = array(
                "proyecto" => preg_match($regex, $this->input->post('proyecto')) ? null : $this->input->post('proyecto'),
                "homoclave" => limpiar_dato($this->input->post('homoclave')),
                "etapa" => limpiar_dato($this->input->post('etapa')),
                "condominio" => limpiar_dato($this->input->post('condominio')),
                "folio" => ($this->input->post('folio') ? limpiar_dato( $this->input->post('folio') ) : "NA"),
                "idEmpresa" => $this->input->post('empresa'),
                "idResponsable" => $responsable,
                "idusuario" => $this->session->userdata("inicio_sesion")['id'],
                "nomdepto" => ($this->session->userdata("inicio_sesion")["rol"] == "CP" ? 'ADMINISTRACION' : ( in_array( $this->session->userdata("inicio_sesion")["rol"], array( 'CE', 'CX' ) ) ? 'DEVOLUCIONES' : $departamento = $this->session->userdata("inicio_sesion")['depto'] == 'ADMON MERCADOTECNIA' ? 'ADMON MERCADOTECNIA' : $departamento )),
                "idProveedor" => $this->input->post('idproveedor'),
                "tendrafac" => $this->input->post('tentra_factura') ? $this->input->post('tentra_factura') : NULL,
                "ref_bancaria" => $this->input->post('referencia_pago'),
                "caja_chica" => $caja_chica,
                "prioridad" => $this->input->post('prioridad'),
                "programado" =>  $this->input->post('metodo_pago'),
                "fecha_fin" => $this->input->post('fecha_final') ? $this->input->post('fecha_final') : null,
                "cantidad" => $this->input->post('total'),
                "moneda" => $this->input->post('moneda'),
                "metoPago" => $this->input->post('forma_pago'),
                "justificacion" => $this->input->post('solobs'),
                "servicio" => $servicio, /** FECHA: 08-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                "fecelab" => $this->input->post('fecha'),
                "idetapa" => 1,
                "orden_compra" => $this->input->post('orden_compra') ? $this->input->post('orden_compra') : null,
                "intereses" => $this->input->post('interes_dinamico') ? 1 : null,
                "crecibo" => $this->input->post('crecibo') ? $this->input->post('crecibo') : null,
                //"rcompra"=> $rcompra,
                "requisicion"=> $this->input->post('requisicion') ? $this->input->post('requisicion') : null,
                "pais_gasto"=> $pais_gasto ? $pais_gasto  : null,
                "colabs"=> $this->input->post('nColaboradoresRM') ? $this->input->post('nColaboradoresRM') : null,
                "tipo_insumo"=> $this->input->post('insumoRM') ? $this->input->post('insumoRM') : null,
            );

            $data["idsolicitud"] = $this->Complemento_cxpModel->insertar_solicitud( $data );
            if($data["idsolicitud"] && $caja_chica == 4){
                $insert = array(
                    "id_estado" => $this->input->post('estadoRM'),
                    "idsolicitud" => $data["idsolicitud"],
                    "diasDesayuno" => $this->input->post('diasDesayuno') ? $this->input->post('diasDesayuno') : null,
                    "diasComida" => $this->input->post('diasComida') ? $this->input->post('diasComida') : null, 
                    "diasCena" => $this->input->post('diasCena') ? $this->input->post('diasCena') : null, 
                );
                $this->Complemento_cxpModel->insertar_solicitud_estado( $insert );
            }

            if($data["idsolicitud"] && $this->input->post('oficina')){
                $insert = array(
                    "idOficina" => $this->input->post('oficina'),
                    "idProyectos" => $this->input->post('proyecto'),
                    "idTipoServicioPartida" => $this->input->post('tServicio_partida'),
                    "idsolicitud" => $data["idsolicitud"]
                );
                $this->Complemento_cxpModel->insertar_solicitud_proyecto_oficina( $insert );
            }

            //CONSTRUCCION
            /**GUARDAMOS LOS CONTRATOS SELECCIONADOS PARA ESTE PROVEEDOR SOLO APLICA PARA CONSTRUCCION**/
            /**
             * POR REQUISICIÓN, SE QUITA FILTRO DE SOLO DEPARTAMENTO DE CONSTRUCCIÓN.
             */
            if( $this->input->post("cproveedor") && $this->input->post("cproveedor") !== 'N/A' ){
                $this->Solicitudes_solicitante->guardar_solicitud_contrato( 
                    array( 
                        "idsolicitud" => $data["idsolicitud"], 
                        "idcontrato" =>  $this->input->post("cproveedor"),
                        "idcrea" => $this->session->userdata("inicio_sesion")['id'],
                        "fecha_creacion" => date("Y-m-d H:i:s"),
                    ) 
                );
            }
            /******************************************************************************************/

            $data["observaciones"] = $this->input->post('obse');
            $data['descr'] = $this->input->post('descr');

            $resultado = TRUE;

            //PARA LA INSERCION DE LAS OBSERVACIONES DE LAS SOLICITUDES
            log_sistema($this->session->userdata("inicio_sesion")['id'], $data["idsolicitud"], "SE HA CREADO UNA NUEVA SOLICITUD");

            if( isset( $_FILES ) && !empty($_FILES) ){

                $config['upload_path'] = './UPLOADS/';
                $config['allowed_types'] = 'xml|pdf';
                //CARGAMOS LA LIBRERIA CON LAS CONFIGURACIONES PREVIAS -----$this->upload->display_errors()
                $this->load->library('upload', $config);
            	if((isset($_FILES['xmlfile'])) && array_key_exists('xmlfile', $_FILES)){
					$xmlfileName = $_FILES['xmlfile']['name'];
					$extensionXML = pathinfo($xmlfileName, PATHINFO_EXTENSION);
					if(strtoupper($extensionXML) == 'XML'){

						$resultado = $this->upload->do_upload("xmlfile");

						if( $resultado ){

							$xml_subido = $this->upload->data();

							$datos_xml = $this->Complemento_cxpModel->leerxml( $xml_subido['full_path'], TRUE );
                            
							$this->load->model("Provedores_model");
							$resultado = $caja_chica == 4 
                                            ? true 
                                            : $this->input->post('total') == $datos_xml["Total"] && $datos_xml["rfcemisor"] == $this->Provedores_model->getProveedor( $this->input->post('idproveedor') )->result_array()[0]["rfc"];

							if( $resultado ){

								//PARA EL CASO DE CFE SU UUID ES LA MISMA PALABRA
								$datos_xml["uuid"] = strpos( strtoupper($datos_xml["uuid"]), 'UUID' ) ? $datos_xml["uuid"].date("Ymdhis") : $datos_xml["uuid"];

								//************GENERA EL NUEVO NOMBRE DEL DOCUMENTO XML SUBIDO AL SISTEMA*********/
								// $nuevo_nombre = "CXP_";
								// $nuevo_nombre .= str_pad((count(glob('./UPLOADS/XMLS/CXP_*')) + 1), 5, "0", STR_PAD_LEFT)."_";
								$nuevo_nombre = date("my")."_";
								$nuevo_nombre .= str_replace( array(",", ".", '"'), "", str_replace( array(" ", "/"), "_", limpiar_dato($datos_xml["nomemi"]) ))."_";
								$nuevo_nombre .= date("His")."_";
								$nuevo_nombre .= substr($datos_xml["uuid"], -5).".xml";

								rename( $xml_subido['full_path'], "./UPLOADS/XMLS/".$nuevo_nombre );
								//**************AQUI ES DONDE SE TERMINA EL RENOMBRAMIENTO AL ARCHIVO*****************/

								//LINEA ORIGINAL
								$datos_xml['nombre_xml'] = $nuevo_nombre;
								$datos_xml['tipo_factura'] = $datos_xml['MetodoPago'][0] != 'PPD' ? 3 : 1;

								//TODO LO RELACIONADO CON LOS IMPUESTOS
								$datos_xml['impuestot'] = (isset($datos_xml) && $datos_xml['impuestos_T'] ? json_encode($datos_xml['impuestos_T']) :  0.00 );
								$datos_xml['impuestor'] = (isset($datos_xml) && $datos_xml['impuestos_R'] ? json_encode($datos_xml['impuestos_R']) : 0.00 );
								/*****************************/

								$this->Complemento_cxpModel->insertar_factura( $data, $datos_xml );
								$this->Complemento_cxpModel->guardar_xml( $this->db->insert_id(), $datos_xml["textoxml"] );

								if( $datos_xml['Descuento'] && $datos_xml['Descuento'] > 0.00 ){
									$this->db->update("solpagos", array( "descuento" => $datos_xml['Descuento'] ), "idsolicitud = '".$data["idsolicitud"]."'");
								}
							}else{
								$mensaje = "Se ha detectado una incongruencia en el registro. Capture el registro nuevamente.";
							}
						}else{
							$mensaje = $this->upload->display_errors();
						}
					}
				}

                if((isset($_FILES['pdffile'])) && array_key_exists('pdffile', $_FILES)){
                    $resultado = $this->upload->do_upload('pdffile', $config);
                    if( $resultado ){
                        $pdf_subido = $this->upload->data();
                        $ruta_pdf = './UPLOADS/PDFS/';
                        $nuevo_nombre_pdf = $data["idsolicitud"].'_CCGM_'.   str_replace(' ', '_', $this->input->post('nombreProyecto')).'_'.date('Ymd', strtotime($data['fecelab']));
                        $nueva_ruta = $ruta_pdf.$nuevo_nombre_pdf.'.pdf';
                        if(rename($pdf_subido['full_path'], $nueva_ruta)){
                            $arrayPdf = array(
                                "movimiento" => 'Se agregó archivo PDF (Gasto Chaja chica mayor a $5,000)',
                                "expediente" => $nuevo_nombre_pdf.'.pdf',
                                "modificado" => date('Y-m-d H:i:s'),
                                "estatus" => 1,
                                "idSolicitud"  => $data["idsolicitud"],
                                "idUsuario" => $this->session->userdata("inicio_sesion")['id'],
                                "tipo_doc" => 2 // Tipo de documento por medio de gasto caja chica a proveedor por mas de $5,000.
                            );
                            $this->Solicitudes_solicitante->insertPdfSol($arrayPdf);
                        }
                    }else{
                        $mensaje = $this->upload->display_errors();
                    }
                }

            	if($this->input->post('pdfFile') != 'null'){
					if($this->session->userdata("inicio_sesion")["depto"] == 'COMERCIALIZACION'){
                        $extensionPDF = pathinfo($_FILES['pdfFile']['name'], PATHINFO_EXTENSION);
                        if ($extensionPDF == 'pdf') {
                            $resultado = $this->upload->do_upload('pdfFile', $config);
                            if( $resultado ){
                                $pdf_subido = $this->upload->data();
                                $ruta_pdf = './UPLOADS/PDFS/';
                                $nuevo_nombre_pdf = $data["idsolicitud"].'_COME_'.   str_replace(' ', '_', $this->input->post('nombreProyecto')).'_'.date('Ymd', strtotime($data['fecelab'])).'.pdf';
                                $nueva_ruta = $ruta_pdf.$nuevo_nombre_pdf;
                                rename($pdf_subido['full_path'], $nueva_ruta);
                                //logica para el archivo pdf
                                //se arma la inserción del archivo
                                if($resultado){
                                    $arrayPdf = array(
                                        "movimiento" => 'Se agregó archivo PDF',
                                        "expediente" => str_replace(' ','_',$nuevo_nombre_pdf),
                                        "modificado" => date('Y-m-d H:i:s'),
                                        "estatus" => 1,
                                        "idSolicitud"  => $data["idsolicitud"],
                                        "idUsuario" => $this->session->userdata("inicio_sesion")['id'],
                                        "tipo_doc" => 1 // Tipo de documento por medio de departamento de comercializacion.
                                    );
                                    $this->Solicitudes_solicitante->insertPdfSol($arrayPdf);
                                }
                            }else{
                                $mensaje = $this->upload->display_errors();
                            }
                        }
					}
				}
				if((isset($_FILES['pdfFileAut'])) && array_key_exists('pdfFileAut', $_FILES)){
                    $autfileName = $_FILES['pdfFileAut']['name'];
                    $extensionPDF = pathinfo($autfileName, PATHINFO_EXTENSION);
                    if($extensionPDF == 'pdf'){
                        $configPDFAut['upload_path'] = './UPLOADS/AUTSVIATICOS/';
                        $configPDFAut['allowed_types'] = 'pdf';
                        $configPDFAut['overwrite'] = true;
                        //CARGAMOS LA LIBRERIA CON LAS CONFIGURACIONES PREVIAS -----$this->upload->display_errors()
                        $this->load->library('upload', $configPDFAut);
                        $this->upload->initialize($configPDFAut);
                        $resultado = $this->upload->do_upload('pdfFileAut');
                        if($resultado){
                            $pdf_subido_aut = $this->upload->data();
                            $ruta_pdfAut = './UPLOADS/AUTSVIATICOS/';
                            $nuevo_nombre_pdf_aut = $data["idsolicitud"].'_AUTV_'.   str_replace(' ', '_', $this->input->post('nombreProyecto')).'_'.date('Ymd', strtotime($data['fecelab'])).'.pdf';
                            $nueva_ruta = $ruta_pdfAut.$nuevo_nombre_pdf_aut;
                            rename($pdf_subido_aut['full_path'], $nueva_ruta);
                            $arrayPdf = array(
                                "movimiento" => 'se agregó una autorización para viaticos',
                                "expediente" => str_replace(' ','_',$nuevo_nombre_pdf_aut),
                                "modificado" => date('Y-m-d H:i:s'),
                                "estatus" => 1,
                                "idSolicitud"  => $data["idsolicitud"],
                                "idUsuario" => $this->session->userdata("inicio_sesion")['id'],
                                "tipo_doc" => 3
                            );
                            $this->Solicitudes_solicitante->insertPdfSol($arrayPdf);
                        }else{
                            $mensaje = $this->upload->display_errors();
                        }
					}
				}

				//archivo de autorizacion de reembolsos
				if((isset($_FILES['pdfFileAutR'])) && array_key_exists('pdfFileAutR', $_FILES)){
//					$resultado = $this->upload->do_upload('pdfFileAutR', $config);
//					if( $resultado ){
//						$pdf_subido_autR = $this->upload->data();
//						$ruta_pdfAutR = './UPLOADS/AUTSREEMBOLSOS/';
//						$nuevo_nombre_pdf_aut = $data["idsolicitud"].'_'.   str_replace(' ', '_', $data['proyecto']).'_'.date('Ymd', strtotime($data['fecelab']));
//						$nueva_rutaAutR = $ruta_pdfAutR.$nuevo_nombre_pdf_aut.'.pdf';
//						rename($pdf_subido_autR['full_path'], $nueva_rutaAutR);
//					}else{
//						$mensaje = $this->upload->display_errors();
//					}
					if($this->input->post('pdfFileAutR') != 'null'){
						if($this->session->userdata("inicio_sesion")["rol"] == 'CA'){
							$autfileNameAutR = $_FILES['pdfFileAutR']['name'];
							$extensionPDFAutR = pathinfo($autfileNameAutR, PATHINFO_EXTENSION);
							if($extensionPDFAutR == 'pdf'){

								$configPDFAutR['upload_path'] = './UPLOADS/AUTSREEMBOLSOS/';
								$configPDFAutR['allowed_types'] = 'pdf';
								$configPDFAutR['overwrite'] = true;
								$ruta_pdfAutR = './UPLOADS/AUTSREEMBOLSOS/';

								//CARGAMOS LA LIBRERIA CON LAS CONFIGURACIONES PREVIAS -----$this->upload->display_errors()
								$this->load->library('upload', $configPDFAutR);
								$this->upload->initialize($configPDFAutR);
								$resultado = $this->upload->do_upload('pdfFileAutR');
								$pdf_subido_autR = $this->upload->data();

								//logica para el archivo pdf
								//se arma la inserción del archivo
								$nuevo_nombre_pdf_aut = $data["idsolicitud"].'_'.   str_replace(' ', '_', $this->input->post('nombreProyecto')).'_'.date('Ymd', strtotime($data['fecelab']));
								$nueva_rutaAutR = $ruta_pdfAutR.$nuevo_nombre_pdf_aut.'.pdf';
								$nombreArchivoReemAut = $nuevo_nombre_pdf_aut.'.pdf';
								rename($pdf_subido_autR['full_path'], $nueva_rutaAutR);
								if($resultado){
									$arrayPdf = array(
										"movimiento" => 'se agregó una autorización para reembolsos',
										"expediente" => str_replace(' ','_',$nombreArchivoReemAut),
										"modificado" => date('Y-m-d H:i:s'),
										"estatus" => 1,
										"idSolicitud"  => $data["idsolicitud"],
										"idUsuario" => $this->session->userdata("inicio_sesion")['id'],
										"tipo_doc" => 4
									);
									$resultado = $insertDocumnt = $this->Solicitudes_solicitante->insertPdfSol($arrayPdf);
								}else{
									$mensaje = $this->upload->display_errors();
								}
							}
						}
					}
				}
            }
            if ( $resultado === FALSE || $this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                $resp_data = array("resultado" => FALSE, "mensaje" => $mensaje );
            }else{
                $this->db->trans_commit();
                if(isset($devInsumo) && isset($_FILES['xmlfile']) && array_key_exists('xmlfile', $_FILES))
                    $resp_data = array("resultado" => TRUE, "insumo" => $devInsumo);
                else
                    $resp_data = array("resultado" => TRUE);
            }
        }
        echo json_encode( $resp_data );   
    }

    public function guardar_solicitud_devolucion(){
        // FECHA : 01-JULIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
        $regex = "/^[0-9]+$/";
        $resultado = array("resultado" => TRUE);
        $tendrafac=null;
        if( (isset($_POST) && !empty($_POST)) ){

            if( !$this->input->post('idproveedor') ){
                $informacion = array(
                    "nombre" => limpiar_dato($this->input->post('nombreproveedor')),
                    "tipo_prov" => 0,
                    "estatus" => 2,
                    "idby" => $this->session->userdata("inicio_sesion")['id']
                );

                if($this->input->post('forma_pago') == "TEA" ){
                    $informacion["idbanco"] = $this->input->post('idbanco');
                    $informacion["tipocta"] = $this->input->post('tipocta');
                    $informacion["cuenta"] = $this->input->post('cuenta');
                }

                $this->db->insert( "proveedores", $informacion);
    
                $idproveedor = $this->db->insert_id();
                $this->db->update("proveedores", array("alias" => substr( limpiar_dato($this->input->post("nombreproveedor")), 0, 5).$idproveedor), "idproveedor = '".$idproveedor."'");
            }else{
                $idproveedor = $this->input->post('idproveedor');
            }
            
            $this->db->trans_begin();
            
            $data = array(
                "proyecto" => preg_match($regex, $this->input->post('proyecto')) ? null : $this->input->post('proyecto'),
                "homoclave" => limpiar_dato($this->input->post('homoclave')),
                "etapa" => limpiar_dato($this->input->post('etapa')),
                "condominio" => limpiar_dato($this->input->post('condominio')),
                "folio" => ($this->input->post('folio') ? limpiar_dato( $this->input->post('folio') ) : "NA"),
                "idEmpresa" => $this->input->post('empresa'),
                "idResponsable" => $this->session->userdata("inicio_sesion")['da'],
                "idusuario" => $this->session->userdata("inicio_sesion")['id'],
                "nomdepto" => ($this->input->post('operacion') ? $this->input->post('operacion') : $this->session->userdata("inicio_sesion")['depto']),
                "idProveedor" => $idproveedor,
                "ref_bancaria" => $this->input->post('referencia_pago'),
                "prioridad" => $this->input->post('prioridad'),
                "cantidad" => $this->input->post('total'),
                "moneda" => $this->input->post('moneda'),
                "justificacion" => $this->input->post('solobs'),
                "servicio" => $this->input->post('servicio'),
                "fecelab" => $this->input->post('fecha'),
                "metoPago" => $this->input->post('forma_pago'),
                "idetapa" => 1,
                "tendrafac"=> $this->input->post("tendra_fac")
            );

            $resultado = $this->Solicitudes_solicitante->insertar_solicitud( $data );

            if($resultado && $this->input->post('oficina')){
                $insert = array(
                    "idOficina" => $this->input->post('oficina'),
                    "idProyectos" => $this->input->post('proyecto'),
                    "idTipoServicioPartida" => $this->input->post('tServicio_partida'),
                    "idsolicitud" => $resultado
                );
                $this->Complemento_cxpModel->insertar_solicitud_proyecto_oficina( $insert );
            }

            //CARGA INFORMACION EN EL LOG PARA EL HISTORIAL
            log_sistema($this->session->userdata("inicio_sesion")['id'], $resultado, "SE HA CREADO UNA NUEVA SOLICITUD");

            //PARA LA INSERCION DE LAS OBSERVACIONES DE LAS SOLICITUDES
            //$this->Solicitudes_solicitante->insertar_observaciones_solicitante($data["idsolicitud"], "Se solicita el siguiente gasto. ".$this->input->post("solobs") );

            if ( $this->db->trans_status() === FALSE ){
                $this->db->trans_rollback();
                $resultado = array("resultado" => FALSE);
            }else{
                $this->db->trans_commit();
                $resultado = array("resultado" => TRUE);
            }
        }

        echo json_encode( $resultado );
    }

    public function guardar_solicitud_nominas(){

        $resultado = array("resultado" => TRUE);

        if( (isset($_POST) && !empty($_POST)) || ( isset( $_FILES ) && !empty($_FILES) ) ){

            $this->db->trans_begin();

            if( $this->input->post('responsable_cc') ){
                $responsable = $this->input->post('responsable_cc');
            }else{
                if( $this->session->userdata("inicio_sesion")['rol'] == 'DA' ){
                    $responsable = $this->session->userdata("inicio_sesion")['id'];
                }else{
                    $responsable = $this->session->userdata("inicio_sesion")['da'];
                }
            }
            // $this->input->post('servicio') == '9' es caja chica
            $data = array(
                "proyecto" => "SUELDOS",
                "homoclave" => 'N/A',
                "folio" => ($this->input->post('folio') ? limpiar_dato( $this->input->post('folio') ) : "NA"),
                "idEmpresa" => $this->input->post('empresa'),
                "idResponsable" => $responsable,
                "idusuario" => $this->session->userdata("inicio_sesion")['id'],
                "nomdepto" => 'NOMINAS',
                "idProveedor" => $this->input->post('idproveedor'),
                "tendrafac" => $this->input->post('tentra_factura') ? $this->input->post('tentra_factura') : NULL,
                "ref_bancaria" => $this->input->post('referencia_pago'),
                "caja_chica" => ( $this->input->post('servicio') == 9 || $this->input->post('servicio1') == 9 ) ? 1 : null ,
                "prioridad" => $this->input->post('prioridad'),
                 "fecha_fin" => $this->input->post('fecha_final'),
                "cantidad" => $this->input->post('total'),
                "moneda" => $this->input->post('moneda'),
                "metoPago" => $this->input->post('forma_pago'),
                "justificacion" => $this->input->post('solobs'),
                "servicio" => ( $this->input->post('servicio') == 9 || $this->input->post('servicio1') == 9 ) ? null :$this->input->post('servicio1'),
                "fecelab" => $this->input->post('fecha'),
                "idetapa" => 1
            );

            $data["idsolicitud"] = $this->Solicitudes_solicitante->insertar_solicitud( $data );
            $data["observaciones"] = $this->input->post('obse');
            $data['descr'] = $this->input->post('descr');

            $resultado = TRUE;

            if( isset( $_FILES ) && !empty($_FILES) ){
                
                $config['upload_path'] = './UPLOADS/XMLS/';
                $config['allowed_types'] = 'xml';
                
                //CARGAMOS LA LIBRERIA CON LAS CONFIGURACIONES PREVIAS -----$this->upload->display_errors()
                $this->load->library('upload', $config);

                $resultado = $this->upload->do_upload("xmlfile");

                if( $resultado ){

                    $xml_subido = $this->upload->data();

                    $datos_xml = $this->Complemento_cxpModel->leerxml( $xml_subido['full_path'], TRUE );

                    //************GENERA EL NUEVO NOMBRE DEL DOCUMENTO XML SUBIDO AL SISTEMA*********/
                    // $nuevo_nombre = "CXP_";
                    // $nuevo_nombre .= str_pad((count(glob('./UPLOADS/XMLS/CXP_*')) + 1), 5, "0", STR_PAD_LEFT)."_";
                    $nuevo_nombre = date("my")."_";
                    $nuevo_nombre .= str_replace( array(",", ".", '"'), "", str_replace( array(" ", "/"), "_", limpiar_dato($datos_xml["nomemi"]) ))."_";
                    $nuevo_nombre .= date("His")."_";
                    $nuevo_nombre .= substr($datos_xml["uuid"], -5).".xml";

                    rename( $xml_subido['full_path'], "./UPLOADS/XMLS/".$nuevo_nombre );
                    //**************AQUI ES DONDE SE TERMINA EL RENOMBRAMIENTO AL ARCHIVO*****************/

                    //LINEA ORIGINAL
                    //$datos_xml['nombre_xml'] = $xml_subido['file_name'];
                    $datos_xml['nombre_xml'] = $nuevo_nombre;
                    $datos_xml['tipo_factura'] = $datos_xml['formpago'][0] != 99 ? 3 : 1;
                    $this->Complemento_cxpModel->insertar_factura( $data, $datos_xml );

                }else{
                    $resultado["mensaje"] = $this->upload->display_errors();
                }
            }
            
            //PARA LA INSERCION DE LAS OBSERVACIONES DE LAS SOLICITUDES
            //$this->Solicitudes_solicitante->insertar_observaciones_solicitante($data["idsolicitud"], "Se solicita el siguiente gasto. ".$this->input->post("solobs") );

            if ( $resultado === FALSE || $this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                $resultado = array("resultado" => FALSE);
            }else{
                $this->db->trans_commit();
                $resultado = array("resultado" => TRUE);
            }
        }

        echo json_encode( $resultado );
    }



    //EDICION GENERAL DE TODOS LOS CAPTURISTAS EN EL SISTEMA
    public function editar_solicitud(){
        $resultado = TRUE;
        $mensaje = "";
        $regex = "/^[0-9]+$/";
        // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
        
        if( (isset($_POST) && !empty($_POST)) || ( isset( $_FILES ) && !empty($_FILES) ) ){

            $this->db->trans_begin();
            
            if((isset($_FILES['xmlfile'])) && (array_key_exists('xmlfile', $_FILES) )){
                
                $config['upload_path'] = './UPLOADS/XMLS';
                $config['allowed_types'] = 'xml';
                //CARGAMOS LA LIBRERIA CON LAS CONFIGURACIONES PREVIAS -----$this->upload->display_errors()
                $this->load->library('upload', $config);

                if( $this->input->post('proyecto') ){
                    $data["proyecto"] = limpiar_dato($this->input->post('proyecto'));
                }

                //TIPO DE CONSUMO / SERVICIO
                // if( $this->input->post('servicio') == 9 || $this->input->post('servicio1') == 9 ){
                if( $this->input->post('servicio1') == 9 ){
                    $caja_chica = 1;
                }elseif( $this->input->post('servicio1') == 11 ){
                    $caja_chica = 2;
                }elseif( $this->input->post('servicio1') == 12 ){
                    $caja_chica = 3; //Reembolso
                }elseif( $this->input->post('servicio1') == 13){
                    $caja_chica = 4;//viaticos
                }
                else{
                    $caja_chica = 0;
                }
                
                $idinsumo = 0;
                if($this->input->post('newInsumo')!='' || $this->input->post('newInsumo') != null) {
                    $nuevo_insumo =  $this->input->post('newInsumo').strtoupper(limpiar_dato($this->input->post('textinsumo')));
                    $row_insumo = $this->db->query("SELECT * FROM insumos WHERE insumo = ? ",[$nuevo_insumo]);
                    if($row_insumo->num_rows() == 0){
                        // insertar insumo
                        $this->db->query("INSERT INTO insumos(insumo, estatus) VALUES('".$nuevo_insumo."',1);");
                        $idinsumo = $this->db->insert_id();
                        $devInsumo=["idinsumo"=>$idinsumo, "insumo"=>$nuevo_insumo];
                    }else{
                        $idinsumo = $row_insumo->row()->idinsumo;
                    }
                }else{
                    $idinsumo = $this->input->post('insumo');
                }

                if($caja_chica == 4)
                    $idinsumo = 41;
            
                $pais_gasto = '';
                if($caja_chica == 4 && $this->input->post('paisRM') != null ){
                    if($this->input->post('paisRM') != '6'){
                        $pais_gasto = 'MX';
                    }else{
                        $pais_gasto = 'EU';
                    }
                }else{
                    $pais_gasto = $this->input->post('paisRM');
                }


                $data = array(
                    "proyecto" => preg_match($regex, $this->input->post('proyecto')) ? null : $this->input->post('proyecto'),
                    "homoclave" => limpiar_dato($this->input->post('homoclave')),
                    "etapa" => limpiar_dato($this->input->post('etapa')),
                    "condominio" => limpiar_dato($this->input->post('condominio')),
                    "folio" => ($this->input->post('folio') ? limpiar_dato( $this->input->post('folio') ) : "NA"),
                    "idEmpresa" => $this->input->post('empresa'),
                    "idProveedor" => $this->input->post('idproveedor'),
                    "tendrafac" => $this->input->post('tentra_factura') ? $this->input->post('tentra_factura') : NULL,
                    "caja_chica" => $caja_chica,
                    "prioridad" => $this->input->post('prioridad'),
                    "cantidad" => $this->input->post('total'),
                    "ref_bancaria" => $this->input->post('referencia_pago'),
                    "metoPago" => $this->input->post('forma_pago'),
                    "moneda" => $this->input->post('moneda'),
                    "justificacion" => $this->input->post('solobs'),
                    "servicio" => ( in_array($caja_chica, array( 1, 4 ))) ?  $idinsumo : $this->input->post('servicio1'),"orden_compra" => $this->input->post('orden_compra') ? $this->input->post('orden_compra') : NULL,
                    "crecibo" => $this->input->post('crecibo') ? $this->input->post('crecibo') : NULL,
                    "fecelab" => $this->input->post('fecha'),
                    "requisicion"=> $this->input->post('requisicion') ? $this->input->post('requisicion') : null,
                    "pais_gasto"=> $pais_gasto ? $pais_gasto  : null,
                    "colabs"=> $this->input->post('nColaboradoresRM') ? $this->input->post('nColaboradoresRM') : null,
                    "tipo_insumo"=> $this->input->post('insumoRM') ? $this->input->post('insumoRM') : null
                );

                if( $this->input->post('responsable_cc') &&  in_array( $this->input->post('servicio'), array( "9", "11", "13" ) ) ) {
                    $data['idResponsable'] = $this->input->post('responsable_cc');
                }

                $proyectoOficina = array(
                    "idProyectos" => $this->input->post('proyecto'),
                    "idOficina" => $this->input->post('oficina'),
                    "idTipoServicioPartida" => $this->input->post('tServicio_partida')
                );
    
                $this->Solicitudes_solicitante->update_solicitud( $data, $this->input->post("idsolicitud") );
                if($this->input->post("idsolicitud") && $this->input->post('oficina')){
                    $existeRegistro = $this->Solicitudes_solicitante->selectProyectoOficina($this->input->post("idsolicitud"))->result_array();
                    if(count($existeRegistro)>0)
                        $this->Solicitudes_solicitante->updateProyectoOficina( $proyectoOficina, $this->input->post("idsolicitud") );
                    else{
                        $insert = array(
                            "idOficina" => $this->input->post('oficina'),
                            "idProyectos" => $this->input->post('proyecto'),
                            "idTipoServicioPartida" => $this->input->post('tServicio_partida'),
                            "idsolicitud" => $this->input->post("idsolicitud")
                        );
                        $this->Complemento_cxpModel->insertar_solicitud_proyecto_oficina( $insert );
                    }
                }

                $estados = array(
                    "id_estado" => $this->input->post('estadoRM'),
                    "diasDesayuno" => $this->input->post('diasDesayuno') ? $this->input->post('diasDesayuno') : null,
                    "diasComida" => $this->input->post('diasComida') ? $this->input->post('diasComida') : null, 
                    "diasCena" => $this->input->post('diasCena') ? $this->input->post('diasCena') : null, 

                );

                if($caja_chica == 4){
                    $existeRegistro = $this->Solicitudes_solicitante->selectSolicitudEstados($this->input->post("idsolicitud"))->result_array();
                    if(count($existeRegistro)>0) $this->Solicitudes_solicitante->updateSolicitudEstados( $estados, $this->input->post("idsolicitud") );
                    else{                   
                        $insert = array(
                            "id_estado" => $this->input->post('estadoRM'),
                            "idsolicitud" => $this->input->post("idsolicitud"),
                            "diasDesayuno" => $this->input->post('diasDesayuno') ? $this->input->post('diasDesayuno') : null,
                            "diasComida" => $this->input->post('diasComida') ? $this->input->post('diasComida') : null, 
                            "diasCena" => $this->input->post('diasCena') ? $this->input->post('diasCena') : null, 
                        );
                        $this->Complemento_cxpModel->insertar_solicitud_estado( $insert );
                    
                    }
                }
    
                $data["idsolicitud"] = $this->input->post("idsolicitud");
                $data["observaciones"] = $this->input->post('obse');
                $data['descr'] = $this->input->post('descr');
                
                $this->Solicitudes_solicitante->bloquear_factura( $this->input->post("idsolicitud") );

                $resultado_xml = $this->upload->do_upload("xmlfile");

                if( $resultado_xml ){

                    $xml_subido = $this->upload->data();

                    $datos_xml = $this->Complemento_cxpModel->leerxml( $xml_subido['full_path'], TRUE );

                    //************GENERA EL NUEVO NOMBRE DEL DOCUMENTO XML SUBIDO AL SISTEMA*********/
                    $nuevo_nombre = date("my")."_";
                    $nuevo_nombre .= str_replace( array(",", ".", '"'), "", str_replace( array(" ", "/"), "_", limpiar_dato($datos_xml["nomemi"]) ))."_";
                    $nuevo_nombre .= date("His")."_";
                    $nuevo_nombre .= substr($datos_xml["uuid"], -5).".xml";

                    rename( $xml_subido['full_path'], "./UPLOADS/XMLS/".$nuevo_nombre );
                    //**************AQUI ES DONDE SE TERMINA EL RENOMBRAMIENTO AL ARCHIVO*****************/
                    //LINEA ORIGINAL
                    $datos_xml['nombre_xml'] = $nuevo_nombre;
                    $datos_xml['tipo_factura'] = $datos_xml['formpago'][0] != 99 ? 3 : 1;

                    //TODO LO RELACIONADO CON LOS IMPUESTOS
                    $datos_xml['impuestot'] = (isset($datos_xml) && $datos_xml['impuestos_T'] ? json_encode($datos_xml['impuestos_T']) : 0.00 );
                    $datos_xml['impuestor'] = (isset($datos_xml) && $datos_xml['impuestos_R'] ? json_encode($datos_xml['impuestos_R']) : 0.00 );
                    /*****************************/

                    $this->Complemento_cxpModel->insertar_factura( $data, $datos_xml);
                    $this->Complemento_cxpModel->guardar_xml( $this->db->insert_id(), $datos_xml["textoxml"] );
                    $resultado = TRUE;
                }else{
                    $mensaje = $this->upload->display_errors();
                    $resultado = FALSE;
                }
            }else{
                if( $this->input->post('proyecto') ){
                    $data["proyecto"] = limpiar_dato($this->input->post('proyecto'));
                }

                //TIPO DE CONSUMO / SERVICIO
                if( $this->input->post('servicio') == 9 || $this->input->post('servicio1') == 9 ){
                    $caja_chica = 1;
                }elseif( $this->input->post('servicio') == 11 || $this->input->post('servicio1') == 11 ){
                    $caja_chica = 2;
                }elseif( $this->input->post('servicio1') == 13 || $this->input->post('servicio') == 13){
                    $caja_chica = 4;//viaticos
                }
                else{
                    $caja_chica = 0;
                }

                $idinsumo = 0;
                if($this->input->post('newInsumo')!='' || $this->input->post('newInsumo') != null) {
                    $nuevo_insumo =  $this->input->post('newInsumo').strtoupper(limpiar_dato($this->input->post('textinsumo')));
                    $row_insumo = $this->db->query("SELECT * FROM insumos WHERE insumo = ? ",[$nuevo_insumo]);
                    if($row_insumo->num_rows() == 0){
                        // insertar insumo
                        $this->db->query("INSERT INTO insumos(insumo, estatus) VALUES('".$nuevo_insumo."',1);");
                        $idinsumo = $this->db->insert_id();
                        $devInsumo=["idinsumo"=>$idinsumo, "insumo"=>$nuevo_insumo];
                    }else{
                        $idinsumo = $row_insumo->row()->idinsumo;
                    }
                }else{
                    $idinsumo = $this->input->post('insumo');
                }

                if($caja_chica == 4)
                   $idinsumo = 41;
            
                $pais_gasto = '';
                if($caja_chica == 4 && $this->input->post('paisRM') != null ){
                    if($this->input->post('paisRM') != '6'){
                        $pais_gasto = 'MX';
                    }else{
                        $pais_gasto = 'EU';
                    }
                }else{
                    $pais_gasto = $this->input->post('paisRM');
                }

                $data = array(
                    "proyecto" => preg_match($regex, $this->input->post('proyecto')) ? null : $this->input->post('proyecto'),
                    "homoclave" => limpiar_dato($this->input->post('homoclave')),
                    "etapa" => limpiar_dato($this->input->post('etapa')),
                    "condominio" => limpiar_dato($this->input->post('condominio')),
                    "caja_chica" => $caja_chica,
                    "prioridad" => $this->input->post('prioridad'),
                    "programado" =>  $this->input->post('metodo_pago'),
                    "ref_bancaria" => $this->input->post('referencia_pago'),
                    "metoPago" => $this->input->post('forma_pago'),
                    "moneda" => $this->input->post('moneda'),
                    "justificacion" => $this->input->post('solobs'),
                    "orden_compra" => $this->input->post('orden_compra') ? $this->input->post('orden_compra') : NULL,
                    "requisicion"=> $this->input->post('requisicion') ? $this->input->post('requisicion') : null,
                    "crecibo" => $this->input->post('crecibo') ? $this->input->post('crecibo') : NULL,
                    "servicio" => ( in_array($caja_chica, array( 1, 4 ))) ?  $idinsumo : $this->input->post('servicio1'),
                    "pais_gasto"=> $pais_gasto ? $pais_gasto  : null,
                    "colabs"=> $this->input->post('nColaboradoresRM') ? $this->input->post('nColaboradoresRM') : null,
                    "tipo_insumo"=> $this->input->post('insumoRM') ? $this->input->post('insumoRM') : null
                );

                if( $this->input->post('responsable_cc') &&  in_array( $this->input->post('servicio'), array( "9", "11" ) ) ) {
                    $data['idResponsable'] = $this->input->post('responsable_cc');
                }
                
                if( $this->input->post('tentra_factura') ){
                    $data['tendrafac'] = $this->input->post('tentra_factura');
                }

                if( $this->input->post('folio') ){
                    $data['folio'] = ($this->input->post('folio') ? limpiar_dato( $this->input->post('folio') ) : "NA");
                }

                if( $this->input->post('total') ){
                    $data['cantidad'] = $this->input->post('total');
                }

                if( $this->input->post('empresa') ){
                    $data['idEmpresa'] = $this->input->post('empresa');
                }

                if( $this->input->post('idproveedor') ){
                    $data['idProveedor'] = $this->input->post('idproveedor');
                }

                if( $this->input->post('fecha') ){
                    $data['fecelab'] = $this->input->post('fecha');
                }

                if( $this->input->post('fecha_final') ){
                    $data['fecha_fin'] = $this->input->post('fecha_final');
                }

                if( $this->input->post('homoclave') ){
                    $data['homoclave'] = $this->input->post('homoclave');
                }

                if( $this->input->post('responsable_cc') && ( in_array($this->input->post('servicio'), array('9', '13')) || in_array($this->input->post('servicio1'), array('9', '13')) ) ){
                    $data['idResponsable'] = $this->input->post('responsable_cc');
                }
                $proyectoOficina = array(
                    "idProyectos" => $this->input->post('proyecto'),
                    "idOficina" => $this->input->post('oficina'),
                    "idTipoServicioPartida" => $this->input->post('tServicio_partida')
                );
    
                $this->Solicitudes_solicitante->update_solicitud( $data, $this->input->post("idsolicitud") );
                
                if($this->input->post("idsolicitud") && $this->input->post('oficina')){
                    $existeRegistro = $this->Solicitudes_solicitante->selectProyectoOficina($this->input->post("idsolicitud"))->result_array();
                    if(count($existeRegistro)>0)
                        $this->Solicitudes_solicitante->updateProyectoOficina( $proyectoOficina, $this->input->post("idsolicitud") );
                    else{
                        $insert = array(
                            "idOficina" => $this->input->post('oficina'),
                            "idProyectos" => $this->input->post('proyecto'),
                            "idTipoServicioPartida" => $this->input->post('tServicio_partida'),
                            "idsolicitud" => $this->input->post("idsolicitud")
                        );
                        $this->Complemento_cxpModel->insertar_solicitud_proyecto_oficina( $insert );
                    }
                }

                $estados = array(
                    "id_estado" => $this->input->post('estadoRM'),
                    "diasDesayuno" => $this->input->post('diasDesayuno') ? $this->input->post('diasDesayuno') : null,
                    "diasComida" => $this->input->post('diasComida') ? $this->input->post('diasComida') : null, 
                    "diasCena" => $this->input->post('diasCena') ? $this->input->post('diasCena') : null
                );

                if($caja_chica == 4){
                    $existeRegistro = $this->Solicitudes_solicitante->selectSolicitudEstados($this->input->post("idsolicitud"))->result_array();
                    if(count($existeRegistro)>0) $this->Solicitudes_solicitante->updateSolicitudEstados( $estados, $this->input->post("idsolicitud") );
                    else{                   
                        $insert = array(
                            "id_estado" => $this->input->post('estadoRM'),
                            "idsolicitud" => $this->input->post("idsolicitud"),
                            "diasDesayuno" => $this->input->post('diasDesayuno') ? $this->input->post('diasDesayuno') : null,
                            "diasComida" => $this->input->post('diasComida') ? $this->input->post('diasComida') : null, 
                            "diasCena" => $this->input->post('diasCena') ? $this->input->post('diasCena') : null, 
                        );
                        $this->Complemento_cxpModel->insertar_solicitud_estado( $insert );
                    
                    }
                }

                $resultado = $this->db->trans_status();
            }
            $errores_archivo_pdf = array(
                    0 => 'No hay ningún error, el archivo se cargó con éxito',
                    1 => 'El archivo cargado excede la directiva upload_max_filesize en php.ini',
                    2 => 'El archivo cargado excede la directiva MAX_FILE_SIZE especificada en el formulario HTML',
                    3 => 'El archivo cargado se cargó sólo parcialmente',
                    4 => 'No se cargó ningún archivo',
                    6 => 'Falta una carpeta temporal',
                    7 => 'Error al escribir el archivo en el disco.',
                    8 => 'Una extensión PHP detuvo la carga del archivo.');

            if((isset($_FILES['pdffile'])) && array_key_exists('pdffile', $_FILES) && $_FILES['pdffile']['error'] == 0){

                $config['upload_path'] = './UPLOADS/PDFS';
                $config['allowed_types'] = 'pdf';
                //CARGAMOS LA LIBRERIA CON LAS CONFIGURACIONES PREVIAS -----$this->upload->display_errors()
                $this->load->library('upload', $config);

                $info_solicitud_pdf_tipo2 = $this->Solicitudes_solicitante->getInfoRequestSol($this->input->post("idsolicitud"));
                $ruta_pdf = './UPLOADS/PDFS/';
                $nuevo_nombre_pdf = !empty($info_solicitud_pdf_tipo2->result_array())
                    ? $info_solicitud_pdf_tipo2->result_array()[0]['expediente']
                    : $this->input->post("idsolicitud").'_'.   str_replace(' ', '_', $this->input->post('proyecto')).'_'.date('Ymd', strtotime($this->input->post('fecha'))).'.pdf';
                $nueva_ruta = $ruta_pdf.$nuevo_nombre_pdf;
                
                if (file_exists($ruta_pdf.$nuevo_nombre_pdf))
                    unlink($ruta_pdf.$nuevo_nombre_pdf);
                
                $resultado_pdf_cch = $this->upload->do_upload('pdffile', $config);
                
                if( $resultado_pdf_cch ){

                    $pdf_subido = $this->upload->data();
                    if(rename($pdf_subido['full_path'], $nueva_ruta)){
                        $arrayPdf = array(
                            "movimiento" => 'Se modificó archivo PDF (Gasto Chaja chica mayor a $5,000) mediante la edición de solicitud',
                            "expediente" => $nuevo_nombre_pdf,
                            "modificado" => date('Y-m-d H:i:s'),
                            "idUsuario" => $this->session->userdata("inicio_sesion")['id'],
                            "tipo_doc" => 2 // Tipo de documento por medio de gasto caja chica a proveedor por mas de $5,000.
                        );
                        $this->Solicitudes_solicitante->updateHistorialDocumento($arrayPdf, $this->input->post("idsolicitud"), 2);
                        
                        $mensaje = 'PDF guardado correctamente';
                    }else{
                        $mensaje = 'Error al querer subir archivo PDF';
                    }
                }else{
                    $mensaje = $this->upload->display_errors();
                }
            }

            if((isset($_FILES['pdfFile'])) && array_key_exists('pdfFile', $_FILES)){
                $config['upload_path'] = './UPLOADS/PDFS';
                $config['allowed_types'] = 'pdf';
                //CARGAMOS LA LIBRERIA CON LAS CONFIGURACIONES PREVIAS -----$this->upload->display_errors()
                $this->load->library('upload', $config);

                if($this->session->userdata("inicio_sesion")["depto"] == 'COMERCIALIZACION'){
                    $extensionPDF = pathinfo($_FILES['pdfFile']['name'], PATHINFO_EXTENSION);
                    $info_solicitud_pdf = $this->Solicitudes_solicitante->getInfoRequestSol($this->input->post("idsolicitud"))->result_array();
                    if ($extensionPDF == 'pdf') {

                        $nuevo_nombre_pdf = $info_solicitud_pdf[0]['expediente'];
                        $ruta_pdf = './UPLOADS/PDFS/';
                        $nueva_ruta = $ruta_pdf.$nuevo_nombre_pdf;

                        if (file_exists($ruta_pdf.$nuevo_nombre_pdf)) {
                            unlink($ruta_pdf.$nuevo_nombre_pdf);
                        }else {
                            $mensaje = 'Archivo no se encontró';
                        }

                        $resultado_pdf = $this->upload->do_upload('pdfFile', $config);
                        if( $resultado_pdf ){
                            $pdf_subido = $this->upload->data();
                            rename($pdf_subido['full_path'], $nueva_ruta);
                            //logica para el archivo pdf
                            //se arma la inserción del archivo
                            $arrayPdf = array(
                                "movimiento" => 'Se modificó archivo PDF mediante la edición de solicitud',
                                "expediente" => str_replace(' ','_',$nuevo_nombre_pdf),
                                "modificado" => date('Y-m-d H:i:s'),
                                "estatus" => 1,
                                "idUsuario" => $this->session->userdata("inicio_sesion")['id'],
                                "tipo_doc" => 1 // Tipo de documento generado por tipo de rol comercializacion.
                            );
                            $this->Solicitudes_solicitante->updateHistorialDocumento($arrayPdf, $this->input->post("idsolicitud"), 1);
                        }else{
                            $mensaje = $this->upload->display_errors();
                        }
                    }
                }
            }
            if((isset($_FILES['pdfFileAut'])) && array_key_exists('pdfFileAut', $_FILES)){
                $config['upload_path'] = './UPLOADS/AUTSVIATICOS/';
                $config['allowed_types'] = 'pdf';
                $this->load->library('upload', $config);
                $autfileName = $_FILES['pdfFileAut']['name'];
                $extensionPDF = pathinfo($autfileName, PATHINFO_EXTENSION);
                if($extensionPDF == 'pdf'){
                    $info_solicitud_pdf = $this->Solicitudes_solicitante->getInfoRequestSol($this->input->post("idsolicitud"))->result_array();
                    $nuevo_nombre_pdf = $info_solicitud_pdf[0]['expediente'];
                    $ruta_pdf = './UPLOADS/AUTSVIATICOS/';
                    $nueva_ruta = $ruta_pdf.$nuevo_nombre_pdf;

                    if (file_exists($ruta_pdf.$nuevo_nombre_pdf)) {
                        unlink($ruta_pdf.$nuevo_nombre_pdf);
                    }else {
                        $mensaje = 'Archivo no se encontró';
                    }
                    $configPDFAut['upload_path'] = './UPLOADS/AUTSVIATICOS/';
                    $configPDFAut['allowed_types'] = 'pdf';
                    $configPDFAut['overwrite'] = true;
                    //CARGAMOS LA LIBRERIA CON LAS CONFIGURACIONES PREVIAS -----$this->upload->display_errors()
                    $this->load->library('upload', $configPDFAut);
                    $this->upload->initialize($configPDFAut);
                    $resultado = $this->upload->do_upload('pdfFileAut');
                    if($resultado){
                        $pdf_subido_aut = $this->upload->data();
                        $ruta_pdfAut = './UPLOADS/AUTSVIATICOS/';
                        $nuevo_nombre_pdf_aut = $this->input->post("idsolicitud").'_AUTV_'.   str_replace(' ', '_', $this->input->post('nombreProyecto')).'_'.date('Ymd', strtotime($this->input->post('fecha'))).'.pdf';
                        $nueva_ruta = $ruta_pdfAut.$nuevo_nombre_pdf_aut;
                        rename($pdf_subido_aut['full_path'], $nueva_ruta);
                        $arrayPdf = array(
                            "movimiento" => 'se agregó una autorización para viaticos',
                            "expediente" => str_replace(' ','_',$nuevo_nombre_pdf_aut),
                            "modificado" => date('Y-m-d H:i:s'),
                            "estatus" => 1,
                            "idSolicitud"  => $this->input->post("idsolicitud"),
                            "idUsuario" => $this->session->userdata("inicio_sesion")['id'],
                            "tipo_doc" => 3
                        );
                        $this->Solicitudes_solicitante->insertPdfSol($arrayPdf);
                    }else{
                        $mensaje = $this->upload->display_errors();
                    }
                }
            }

            //ACTUALIZAR EL CONTRATO DE LA SOLICITUD
            /**SOLO APLICA PARA CONSTRUCCION */
            if( $this->input->post('cproveedor') ){
                $this->Solicitudes_solicitante->update_contrato_solicitud( array( "idcontrato" => $this->input->post('cproveedor') ), $this->input->post("idsolicitud") );
            }
            /********************************/

            // $resultado = TRUE;
            
            //PARA LA INSERCION DE LAS OBSERVACIONES DE LAS SOLICITUDES
            log_sistema($this->session->userdata("inicio_sesion")['id'], $this->input->post("idsolicitud"), "SE EDITÓ LA SOLICITUD");

            if ( $resultado === FALSE || $this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                $resultado = array("resultado" => FALSE, "mensaje" => $mensaje );
            }else{
                $this->db->trans_commit();
                if(isset($devInsumo))
                    $resultado = array("resultado" => TRUE, "insumo" => $devInsumo, "mensaje" => $mensaje);
                else
                    $resultado = array("resultado" => TRUE, "mensaje" => $mensaje);
            }
        }else {
            $resultado = array("resultado" => FALSE, "mensaje" => 'No se encontraron datos');
        }
        echo json_encode( $resultado );
    }
    
    public function editar_solicitud_recursos_humanos(){
        // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
        $regex = "/^[0-9]+$/";
        $resultado = array("resultado" => TRUE);

        if( (isset($_POST) && !empty($_POST)) || ( isset( $_FILES ) && !empty($_FILES) ) ){

            if( !$this->input->post('idproveedor') ){
                $informacion = array(
                    "nombre" => limpiar_dato($this->input->post('nombreproveedor')),
                    "tipo_prov" => 0,
                    "estatus" => 2,
                    "idby" => $this->session->userdata("inicio_sesion")['id']
                );
                
                if($this->input->post('forma_pago') == "TEA" ){
                    $informacion["idbanco"] = $this->input->post('idbanco');
                    $informacion["tipocta"] = $this->input->post('tipocta');
                    $informacion["cuenta"] = $this->input->post('cuenta');
                }

                $this->db->insert( "proveedores", $informacion);
    
                $idproveedor = $this->db->insert_id();
                $this->db->update("proveedores", array("alias" => substr( limpiar_dato($this->input->post("nombreproveedor")), 0, 5).$idproveedor), "idproveedor = '".$idproveedor."'");
            }else{
                $idproveedor = $this->input->post('idproveedor');
            }
            $tendrafac=null;
            if($this->input->post("tendra_fac"))
                $tendrafac=$this->input->post("tendra_fac");
            
            
            $this->db->trans_begin();

            $data = array(
                "proyecto" => preg_match($regex, $this->input->post('proyecto')) ? null : limpiar_dato($this->input->post('proyecto')),
                "etapa" => limpiar_dato($this->input->post('etapa')),
                "condominio" => limpiar_dato($this->input->post('condominio')),
                "idEmpresa" => $this->input->post('empresa'),
                "idProveedor" => $idproveedor,
                "ref_bancaria" => $this->input->post('referencia_pago'),
                "prioridad" => $this->input->post('prioridad'),
                "cantidad" => $this->input->post('total'),
                "metoPago" => $this->input->post('forma_pago'),
                "justificacion" => $this->input->post('solobs'),
                "servicio" => $this->input->post('servicio'),
                "moneda" => $this->input->post('moneda'),
                "fecelab" => $this->input->post("fecha"),
                "tendrafac"=> $tendrafac
            );

            $data['nomdepto'] = $this->input->post('operacion') ? $this->input->post('operacion') : $this->session->userdata("inicio_sesion")['depto'];

            $this->Solicitudes_solicitante->update_solicitud( $data, $this->input->post("idsolicitud") );

            $proyectoOficina = array(
                "idProyectos" => $this->input->post('proyecto'),
                "idOficina" => $this->input->post('oficina'),
                "idTipoServicioPartida" => $this->input->post('tServicio_partida')
            );

            $this->Solicitudes_solicitante->update_solicitud( $data, $this->input->post("idsolicitud") );
            if($this->input->post("idsolicitud") && $this->input->post('oficina')){
                $existeRegistro = $this->Solicitudes_solicitante->selectProyectoOficina($this->input->post("idsolicitud"))->result_array();
                if(count($existeRegistro)>0)
                    $this->Solicitudes_solicitante->updateProyectoOficina( $proyectoOficina, $this->input->post("idsolicitud") );
                else{
                    $insert = array(
                        "idOficina" => $this->input->post('oficina'),
                        "idProyectos" => $this->input->post('proyecto'),
                        "idTipoServicioPartida" => $this->input->post('tServicio_partida'),
                        "idsolicitud" => $this->input->post("idsolicitud")
                    );
                    $this->Complemento_cxpModel->insertar_solicitud_proyecto_oficina( $insert );
                }
            }

            log_sistema($this->session->userdata("inicio_sesion")['id'], $this->input->post("idsolicitud"), "SE EDITÓ LA SOLICITUD");

            $resultado = TRUE;
            
            if ( $resultado === FALSE || $this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                $resultado = array("resultado" => FALSE);
            }else{
                $this->db->trans_commit();
                $resultado = array("resultado" => TRUE);
            }
        }

        echo json_encode( $resultado );
    }
    
    public function editar_solicitud_contabilidad(){

        // FECHA : 01-JULIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
        $resultado = array("resultado" => TRUE);

        if( (isset($_POST) && !empty($_POST)) || ( isset( $_FILES ) && !empty($_FILES) ) ){

            if( !$this->input->post('idproveedor') ){
                $this->db->insert( "proveedores", 
                array(
                    "nombre" => limpiar_dato($this->input->post('nombreproveedor')),
                    "tipo_prov" => 0,
                    "estatus" => 2,
                    "idby" => $this->session->userdata("inicio_sesion")['id']
                ));
                $idproveedor = $this->db->insert_id();
                $this->db->update("proveedores", array("alias" => substr( str_replace(" ","", $this->input->post("nombreproveedor") ), 0, 5).$idproveedor), "idproveedor = '".$idproveedor."'");
    
            }else{
                $idproveedor = $this->input->post('idproveedor');
                if($this->input->post('tipoproveedor') != 2){
                    $this->db->update( "proveedores", array(
                        "nombre" => limpiar_dato($this->input->post('nombreproveedor')),
                        "alias" => (substr( limpiar_dato($this->input->post('nombreproveedor')), 0, 5).$idproveedor),
                        "idupdate" => $this->session->userdata("inicio_sesion")['id'],
                        "fecha_update" => date("Y-m-d H:i:s")
                    ), "idProveedor = '".$this->input->post('idproveedor')."'");
                }
                
            }
            

            $this->db->trans_begin();

            $data = array(
                "proyecto" => limpiar_dato($this->input->post('proyecto')),
                "etapa" => limpiar_dato($this->input->post('etapa')),
                "condominio" => limpiar_dato($this->input->post('condominio')),
                "fecelab" => $this->input->post('fecha'),
                "idEmpresa" => $this->input->post('empresa'),
                "idProveedor" => $idproveedor,
                "ref_bancaria" => $this->input->post('referencia_pago'),
                "prioridad" => $this->input->post('prioridad'),
                "cantidad" => $this->input->post('total'),
                "metoPago" => $this->input->post('forma_pago'),
                "justificacion" => $this->input->post('solobs'),
                "servicio" => $this->input->post('servicio'),
                "moneda" => $this->input->post('moneda')
            );

            $data['nomdepto'] = $this->input->post('operacion') ? $this->input->post('operacion') : $this->session->userdata("inicio_sesion")['depto'];

            $this->Solicitudes_solicitante->update_solicitud( $data, $this->input->post("idsolicitud") );
            log_sistema($this->session->userdata("inicio_sesion")['id'], $this->input->post("idsolicitud"), "SE EDITÓ LA SOLICITUD");

            $resultado = TRUE;
            
            if ( $resultado === FALSE || $this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                $resultado = array("resultado" => FALSE);
            }else{
                $this->db->trans_commit();
                $resultado = array("resultado" => TRUE);
            }
        }

        echo json_encode( $resultado );
    }

    public function informacion_solicitud(){
        $respuesta = array( "resultado" => FALSE );
        if( $this->input->post("idsolicitud") ){
            $respuesta['info_solicitud'] = $this->Solicitudes_solicitante->getSolicitud( $this->input->post("idsolicitud") )->result_array();
            
            $informacion_factura = $this->Solicitudes_solicitante->getFacturabySolicitud( $this->input->post("idsolicitud") );
            if( $informacion_factura->num_rows() > 0 ){
                $respuesta['xml'] = $this->Complemento_cxpModel->leerxml( base_url( "UPLOADS/XMLS/".$informacion_factura->row()->nombre_archivo ), FALSE );
                $respuesta['proveedores_todos'] = $this->Lista_dinamicas->get_proveedores_lista_total_factura( $respuesta['info_solicitud'][0]['idProveedor'] )->result_array();
            }else{
                switch( $this->input->post("depto") ){
                    case 'nominas':
                        $respuesta['proveedores_todos'] = $this->Lista_dinamicas->get_colaboradores()->result_array();
                        break;
                    default:
                        $respuesta['proveedores_todos'] = $this->Lista_dinamicas->get_proveedores_lista_total_editar( $respuesta['info_solicitud'][0]["idProveedor"] )->result_array();
                        break;
                }
                
                $respuesta['xml'] = null;
            }

            $respuesta['info_contrato'] = $this->Solicitudes_solicitante->get_sol_contrato( $this->input->post("idsolicitud") )->result_array();
            $respuesta['contratos_prov'] = $this->Solicitudes_solicitante->get_contrato_by_proveedor( $respuesta['info_solicitud'][0]['idProveedor'] )->result_array();
            $respuesta['resultado'] = true;
            $respuesta["empresas"] = $this->Lista_dinamicas->get_empresas_lista()->result_array();

            $respuesta["listado_responsable"] =  $respuesta['info_solicitud'][0]["caja_chica"] == 2 ? $this->Lista_dinamicas->getRestcredito( $this->session->userdata("inicio_sesion")['id'] )->result_array() : $this->Lista_dinamicas->getResponsables( $this->session->userdata("inicio_sesion")['id'] )->result_array();
            
        }

        echo json_encode( $respuesta );
    }
    
    /*
    public function editar_factura(){
        $respuesta = array( "resultado" => FALSE );

        if( $this->input->post("idsolicitud") ){
            $respuesta['resultado'] = $this->Solicitudes_solicitante->editar_factura( $this->input->post("idsolicitud") );
        }

        echo json_encode( $respuesta );
    }
    */
    public function reenviar_factura(){
        $respuesta = array( "resultado" => FALSE );

        if( $this->input->post("idsolicitud") ){
            $respuesta['resultado'] = $this->Solicitudes_solicitante->reenviar_factura( $this->input->post("idsolicitud") );
            log_sistema($this->session->userdata("inicio_sesion")['id'], $this->input->post("idsolicitud"), "SE HA CORREGIDO Y ENVIADO A OTRA ÁREA");
        }

        echo json_encode( $respuesta );
    }

    public function borrar_solicitud(){
        $respuesta = array( "resultado" => true );

        if( $this->input->post("idsolicitud") ){
            $res = $this->Solicitudes_solicitante->borrar_solicitud( $this->input->post("idsolicitud") );
            $respuesta ["resultado"]   = $res["respuesta"];
            log_sistema($this->session->userdata("inicio_sesion")['id'], $this->input->post("idsolicitud"), "HA ELIMINADO LA SOLICITUD DEL SISTEMA ¬¬");

            ///////////////////////PETICION CURL A CONSTRUCCION///////////////////////
           // count($res["consulta"])>0
             if(count($res["consulta"])>0){

                $this->serv_eliminar_construccion($res["consulta"]);
            }
            
        }
        echo json_encode( $respuesta );
       
    }

    function serv_eliminar_construccion($data){
        $fields_string = json_encode($data);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://construccion.gphsis.com/BACK/index.php/OrdenCompraSer/DeleteCxpV2Rel");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string );
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' ) );
        $data = curl_exec($ch);
        //array_push($respuesta,json_decode( curl_exec($ch)) );
        curl_close($ch);
    }

    public function congelar_solicitud(){
        $respuesta = array( "resultado" => FALSE );

        if( $this->input->post("idsolicitud") ){
            $respuesta['resultado'] = $this->Solicitudes_solicitante->congelar_solicitud( $this->input->post("idsolicitud") );
            log_sistema($this->session->userdata("inicio_sesion")['id'], $this->input->post("idsolicitud"), "HAN PAUSADO EL FLUJO DE LA SOLICITUD");
        }

        echo json_encode( $respuesta );
    }

    public function liberar_solicitud(){
        $respuesta = array( "resultado" => FALSE );

        if( $this->input->post("idsolicitud") ){
            $respuesta['resultado'] = $this->Solicitudes_solicitante->liberar_solicitud( $this->input->post("idsolicitud") );
            log_sistema($this->session->userdata("inicio_sesion")['id'], $this->input->post("idsolicitud"), "SE HA LIBERADO LA SOLICITUD, PARA SER PROCESADA");
        }

        echo json_encode( $respuesta );
    }

    public function enviar_a_dg(){
        $respuesta = array( "resultado" => FALSE );

        if( $this->input->post("idsolicitud") ){
            $respuesta['resultado'] = $this->Solicitudes_solicitante->enviar_a_dg( $this->input->post("idsolicitud"), $this->input->post("departamento") );
            $respuesta['solicitudes_proceso'] = $this->Solicitudes_solicitante->getSolicitud( $this->input->post("idsolicitud") )->result_array()[0];

            /** VALIDAR APROBACION TDC | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> | FECHA: 08-ABRIL-2024 **/
            //Revisar si el titular de  tdc == usuario autenticado
            $resultado['aprobacion'] = false;
            if ($respuesta['solicitudes_proceso']['idtitular'] === $this->session->userdata("inicio_sesion")['id']) {
                $resultado['aprobacion'] = true;
            } /** FIN VALIDAR APROBACION TDC | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> | FECHA: 08-ABRIL-2024 **/

            $respuesta['solicitudes_proceso']['opciones'] = $this->opciones_autorizaciones(
                $resultado['aprobacion'],
                $this->input->post("idsolicitud"),
				$respuesta['solicitudes_proceso']['idetapa'],
				$respuesta['solicitudes_proceso']['idusuario'],
				1,
				$respuesta['solicitudes_proceso']['prioridad'],
				$respuesta['solicitudes_proceso']['idAutoriza'],
				$respuesta['solicitudes_proceso']['nomdepto']  );

            log_sistema($this->session->userdata("inicio_sesion")['id'], $this->input->post("idsolicitud"), "SE HA ENVIADO PARA AUTORIZACIÓN");
        }

        echo json_encode( $respuesta );
    }

    public function aprobada_da(){
        $mensaje = $this->input->post("idtitular") == $this->session->userdata("inicio_sesion")['id'] 
            ? 'EL TITULAR DE LA TARJETA HA APROBADO LA SOLICITUD.'
            : 'EL DIRECTOR HA APROBADO LA SOLICITUD';
        if( $this->input->post("idsolicitud") ){
            $respuesta['resultado'] = $this->Solicitudes_solicitante->aprobada_da( $this->input->post("idsolicitud"), $this->input->post("fecelab"), $this->input->post("caja_chica"), $this->input->post("departamento") );
            if( $respuesta['resultado'] ){
                log_sistema($this->session->userdata("inicio_sesion")['id'], $this->input->post("idsolicitud"), $mensaje);
				$respuesta['mensaje'] = 'Se ha realizado el movimiento correctamente';
            } else { /** FECHA: 23-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                $respuesta['mensaje'] = 'La fecha de facturación esta fuera del rango permitido. No es posible enviarla.';
            } /** FECHA: 23-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
        }else{
			$respuesta = array( "resultado" => FALSE, "mensaje" => "NO FUE POSIBLE PROCESAR SU SOLICITUD" );
		}

        echo json_encode( $respuesta );
    }

    public function aprobada_da_multiple(){
    	$arrayData = json_decode($this->input->post('data'));
    	$id_current_user = $this->session->userdata("inicio_sesion")['id'];

    	$arrayResultado = array();
    	foreach ($arrayData as $index =>$element){
			$idsolicitud = $element->idsolicitud;
			$fecelab = $element->fecelab;
			$caja_chica = $element->caja_chica;
			$departamento = $element->departamento;
			$arrayResultado['data'][$index]['resultado'] = $this->Solicitudes_solicitante->aprobada_da( $idsolicitud, $fecelab, $caja_chica, $departamento );
			$arrayResultado['data'][$index]['infoSolicitud'] = $idsolicitud;
			if($arrayResultado['data'][$index]['resultado']){
				log_sistema($id_current_user, $idsolicitud, "EL DIRECTOR HA APROBADO LA SOLICITUD");
				$arrayResultado['data'][$index]['mensaje'] = 'Se ha realizado el movimiento correctamente';
			}else{
				$arrayResultado['data'][$index]['mensaje'] = 'La fecha de facturación esta fuera del rango permitido. No es posible enviarla.';
			}
			$arrayResultado['status'] = 'OK';
		}

    	print_r(json_encode($arrayResultado));

	}



    public function rechazada_da(){
        $respuesta = array( "resultado" => FALSE );

        if( $this->input->post("idsolicitud") ){
            $respuesta['resultado'] = $this->Solicitudes_solicitante->rechazada_da( $this->input->post("idsolicitud") );
            if($respuesta["resultado"]){
                log_sistema($this->session->userdata("inicio_sesion")['id'], $this->input->post("idsolicitud"), "HAN RECHAZADO LA SOLICITUD EN EL DEPARTAMENTO");
                if($this->input->post("comentario"))
                    chat( $this->input->post("idsolicitud"), $this->input->post("comentario"), $this->session->userdata("inicio_sesion")['id'] );
            }
        }

        echo json_encode( $respuesta );
    }

    public function solicitud_refactura(){
        $respuesta = array( "resultado" => FALSE );

        if( $this->input->post("idsolicitud") ){
            $respuesta['resultado'] = $this->Solicitudes_solicitante->solicitud_refactura( $this->input->post("idsolicitud") );
            log_sistema($this->session->userdata("inicio_sesion")['id'], $this->input->post("idsolicitud"), "SE SOLICITA REFACTURACION");
        }

        echo json_encode( $respuesta );
    }

    public function invitacion_proveedor(){

        $respuesta = array( FALSE );
        
        if( $this->input->post("correo_invitacion") ){

            $this->load->library('email');
            $this->load->model("Token");

            $mailpr = strtolower($this->input->post("correo_invitacion"));
            $tkn = md5($mailpr.date("Ymdhis"));
            
            if( $this->Token->savtkn( $this->input->post("correo_invitacion"), array("correo" => $mailpr, "token"=> $tkn, "creadopor" => $this->session->userdata("inicio_sesion")['id'], "activo" => 1 ) ) ){
                $this->email->from('noreplay@ciudadmaderas.com', 'No responder');
                $this->email->to( $mailpr );
                $this->email->subject('Alta de proveedor Ciudad Maderas');
                $this->email->message('<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta http-equiv="X-UA-Compatible" content="ie=edge">
            <title>Document</title>
            <style>
                body{
                    width: 100%;
                    height: 100vh;
                    top: 0;
                    left: 0;
                    margin: 0;
                    padding: 0;
                    font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, Oxygen, Ubuntu, Cantarell, \'Open Sans\', \'Helvetica Neue\', sans-serif;
                }
                img{
                    width:50%;
                    height: auto;
                    margin: 1em 25%;
                    padding: 0;
                }
                p{
                    width: 50%;
                    height: auto;
                    margin: 2em 25%;
                    padding: 0;
                    text-align: justify;
                }
                @media only screen and (max-width: 1024px) {
                    img{
                        width:75%;
                        height: auto;
                        margin: 1em 12.5%;
                        padding: 0;
                    }
                    p{
                        width: 75%;
                        height: auto;
                        margin: 2em 12.5%;
                        padding: 0;
                        text-align: justify;
                    }
                }
            </style>
        </head>
        <body>
            <img src="https://www.ciudadmaderas.com/queretaro/assets/img/log_sistemao.png" alt="Ciudad Maderas" title="Ciudad Maderas">
            <p>En Ciudad Maderas nos interesa que formes parte de nuestros proveedores, por ello te pedimos entres al siguiente enlace en el cual podrás ingresar tus datos.</p>
            <p><a href="'.base_url( "index.php/Invitacion/Nuevo_proveedor/".$tkn ).'">Registro</a></p>
            <p>Muchas gracias por formar parte de nuestros proveedores. ¡Te deseamos éxito!</p>
            <p>El equipo de Ciudad Maderas.</p>
            <p style="font-size:10px;">Este correo fue generado de manera automática, te pedimos no respondas este correo, para cualquier duda o aclaración envía un correo a soporte@ciudadmaderas.com</p>
            <p style="font-size:10px;">Al ingresar tus datos aceptas la política de privacidad, términos y condiciones las cuales pueden ser consultadas en nuestro sitio www.ciudadmaderas.com/legal</p>
        </body>
        </html>');
                if( $this->email->send() ){
                    $respuesta = [ true ];
                }else{
                    $respuesta = [ false, $this->email->print_debugger() ];
                }
            }
        }

        echo json_encode( $respuesta );
    }
    
    function check_correo_invitacion(){

        $respuesta['resultado'] = FALSE;

        if( $this->input->post("correo_invitacion") ){
            $this->load->model("Token");
            $respuesta['resultado'] = $this->Token->invitacion( md5( strtolower($this->input->post("correo_invitacion")) ) )->num_rows() > 0 ? TRUE : FALSE;
        }
        
        echo json_encode( $respuesta );
    }

    function comentario_especial(){

        $respuesta['resultado'] = FALSE;
        
        if( $this->input->post("idsolicitud") ){
            $this->load->model("Consulta");

            $respuesta['resultado'] = $this->Consulta->agregar_comentario_especial( 
            
                array(
                    "idsolicitud" => $this->input->post("idsolicitud"),
                    "tipo_comentario" => $this->input->post("rubro_especial"),
                    "observacion" => $this->input->post("comentario_especial"),
                    "idusario" => $this->session->userdata("inicio_sesion")['id'],
                    "fecha" => date("Y-m-d H:i:s")
                )
            
            );
        }

        echo json_encode( $respuesta );

    }

    function factoraje_enviar(){
        // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
        $tipo_reporte = $this->input->post("tipo_reporte") ? $this->input->post("tipo_reporte") : NULL;
        $respuesta = array("resultado" => FALSE, "mensaje" => "No se ha podido completar la solicitud.", "data" => $this->Solicitudes_solicitante->get_solicitudes_en_curso( $tipo_reporte )->result_array() ) ;
        
        if( $this->input->post("idsolicitud") ){
            if(
                /*
                $this->db->update( "solpagos", array(
                    "metoPago" => $this->input->post("tipo_factoraje"),
                    "dfacturaje" => $this->input->post("dias_factoraje"),
                    "idetapa" => 5,
                ), "idsolicitud = '".$this->input->post("idsolicitud")."' AND idetapa NOT IN ( 9, 10, 11 )" ) && $this->db->affected_rows() > 0 
                */
                $this->db->query( "UPDATE solpagos s INNER JOIN ( SELECT solpagos.idsolicitud, solpagos.fecelab FROM solpagos WHERE solpagos.idsolicitud = '".$this->input->post("idsolicitud")."' ) sol ON s.idsolicitud = sol.idsolicitud SET
                metoPago = '".$this->input->post("tipo_factoraje")."',
                fecha_fin = '".$this->input->post("fecha_publicacion")."',
                dfacturaje = DATEDIFF( '".$this->input->post("dias_factoraje")."', '".$this->input->post("fecha_publicacion")."' ),
                idetapa = '5' WHERE s.idsolicitud = '".$this->input->post("idsolicitud")."' AND idetapa NOT IN ( 9, 10, 11 )" )
            ){

                if( $this->input->post("comentario_especial") )
                    chat( $this->input->post("idsolicitud"), $this->input->post("comentario_especial"), $this->session->userdata("inicio_sesion")['id'] );

                log_sistema($this->session->userdata("inicio_sesion")['id'], $this->input->post("idsolicitud"), "SE HA MODIFICADO LA FORMA DE PAGO DE ESTA SOLICITUD");

                $respuesta["resultado"] = TRUE;
                $respuesta["mensaje"] = "Se ha realizado con exito la operación.";
            
            }else{

                $respuesta["resultado"] = FALSE;
                $respuesta["mensaje"] = "No se ha podido completar la solicitud.";

            }
        }

        echo json_encode( $respuesta );
    }

    //CAMBIAR EL TIPO DE DEPARTAMENTO DEL GASTO
    function nuevo_tipogasto(){
        $respuesta = array("resultado" => FALSE, "mensaje" => "No se ha podido completar la solicitud.") ;
        
        if( $this->input->post("idsolicitud") ){
            $respuesta["resultado"] = $this->Solicitudes_solicitante->cambiar_departamento( $this->input->post("idsolicitud"), $this->input->post("ndepto"), $this->input->post("fecha_publicacion") );
            $respuesta["mensaje"] = "Se ha realizado con exito la operación.";
            log_sistema($this->session->userdata("inicio_sesion")['id'], $this->input->post("idsolicitud"), "SE HA CAMBIADO EL CONCEPTO DEL GASTO A ".$this->input->post("ndepto").".");
        }
        
        echo json_encode( $respuesta );
    }

    function proyecto_deproveedor(){
        if( $this->input->post('rfc_proveedor') )
            $respuesta = $this->Solicitudes_solicitante->get_proyecto_by_proveedor( $this->input->post('rfc_proveedor') )->result_array();
        else
            $respuesta = $this->Solicitudes_solicitante->get_contrato_by_idProv( $this->input->post('id_proveedor') )->result_array();

        $contratos = $this->Solicitudes_solicitante->get_exist_sol_contrato($_POST['idsolicitud'])->result_array();
        echo json_encode(array("data"=>$respuesta,"contratos_asig"=> $contratos));
    }

    function asignar_proveedor_contrato(){
        $respuesta = array("mensaje" => '',"resultado"=>false);
        $exist = $this->Solicitudes_solicitante->get_exist_sol_contrato($_POST['idsolicitud'])->result_array();
        $residuo = $this->Solicitudes_solicitante->get_residuo_contrato($_POST['idcontrato'])->result_array();
        $respuesta = array("e" => $exist);
        $data = array(
            'idsolicitud' => $_POST['idsolicitud'],
            'idcontrato' => $_POST['idcontrato'],
            'idcrea' => $this->session->userdata("inicio_sesion")['id'],
            "fecha_creacion" => date("Y-m-d H:i:s"),
        );

        if(count($exist) > 0){
            if ( $residuo[0]['residuo'] <= (double)$_POST['cant_solicitud'] ) {
                $respuesta['mensaje'] = 'La solicitud supera el presupuesto del contrato.';
            }else{
                $respuesta['resultado'] = $this->Solicitudes_solicitante->actualiza_solicitud_contrato( $data );
                if($respuesta["resultado"]){
                    $respuesta['mensaje'] = 'Se le ha reasignado un contrato a la solicitud #'.$_POST['idsolicitud'].'.';
                    log_sistema($this->session->userdata("inicio_sesion")['id'], $_POST['idsolicitud'], "REASIGNÓ EL CONTRATO '".$residuo[0]["contrato"]."' AL REGISTRO");
                }
            }    
        }elseif ( $residuo[0]['residuo'] <= (double)$_POST['cant_solicitud'] ) {
            $respuesta['mensaje'] = 'La solicitud supera el presupuesto del contrato.';
        }else{
            $respuesta['resultado'] = $this->Solicitudes_solicitante->guardar_solicitud_contrato( $data );
            log_sistema($this->session->userdata("inicio_sesion")['id'], $_POST['idsolicitud'], "ASIGNÓ EL CONTRATO '".$residuo[0]["contrato"]."' AL REGISTRO");
            $respuesta['mensaje'] = 'El contrato se ha asignado con éxito a la solicitud #'.$_POST['idsolicitud'].'.';
        }

        // echo json_encode( $respuesta );
        echo json_encode( $respuesta );
    }
    
    function asignar_financiamiento(){
        $respuesta = array("mensaje" => '',"resultado"=>false);
        
        $idsol=  $_POST['idsolicitud'];
        $financ = $_POST['financ'];
      
        $respuesta['resultado'] = $this->Solicitudes_solicitante->actualiza_financiamiento( $idsol, $financ );
        if($respuesta["resultado"]){
            if($financ > 0){
                $respuesta=array('mensaje'=>'REGISTRO ACTUALIZADO CON ÉXITO', 'financ'=>$financ);
                
                log_sistema($this->session->userdata("inicio_sesion")['id'], $_POST['idsolicitud'], "ACTUALIZADO, APLICA FINANCIAMIENTO");
            }else{
                $respuesta['mensaje'] = 'REGISTRO ACTUALIZADO CON ÉXITO';
                log_sistema($this->session->userdata("inicio_sesion")['id'], $_POST['idsolicitud'], "ACTUALIZADO A, NO APLICA FINANCIAMIENTO");
            }         
        }

        echo json_encode( $respuesta );
    }

    function reporteFinanzas(){
		$this->load->view("v_reporte_cxp");
	}
	function getReporteFinanzas(){
    	$data = $this->Solicitudes_solicitante->getReporteFinanzas();
    	print_r(json_encode(array(
    		"data" => $data
		)));
	}
}
