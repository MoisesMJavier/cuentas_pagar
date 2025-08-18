<?php

    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, authorization");
    header("Access-Control-Allow-Methods: GET, POST, HEAD");
    defined('BASEPATH') OR exit('No direct script access allowed');

class WS extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model(array("Provedores_model", "Lista_dinamicas","Solicitudes_solicitante","Complemento_cxpModel"));
    }

    public function bancos(){
        $respuesta = array( FALSE );
        
        if( isset( $_POST ) ){
            $respuesta = $this->Provedores_model->getbancos( NULL )->result_array();
        }
        echo json_encode( $respuesta );
    }

    public function etapas_construccion(){
        $respuesta = array( FALSE );
        
        if( isset( $_POST ) ){
            $respuesta = $this->Lista_dinamicas->get_etapas()->result_array();
        }
        echo json_encode( $respuesta );
    }

    public function condominios_construccion(){
        $respuesta = array( FALSE );
        
        if( isset( $_POST ) ){
            $respuesta = $this->Lista_dinamicas->get_condominios()->result_array();
        }
        echo json_encode( $respuesta );
    }
    
    public function AgregarGasto(){
        $json = file_get_contents('php://input');  

        if( $_SERVER['REQUEST_METHOD'] === 'POST' && verificar_token( $json, $this->input->request_headers()['authorization']) ){
            $json =  base64_decode($json);    
            if( $json && $json !== FALSE ){

                $this->load->model(array('Solicitudes_solicitante'));
                $json = json_decode( $json );

                $data = array(
                    "proyecto" => $json->proyecto,
                    "idEmpresa" => $json->empresa,
                    "idResponsable" => $json->responsable,
                    "idusuario" => $json->capturista,
                    "nomdepto" => $json->tmovimiento,
                    "idProveedor" => $json->cuenta,
                    "tendrafac" => $json->factura,
                    "caja_chica" => $json->tgasto,
                    "prioridad" => $json->urgente,
                    "cantidad" => $json->cantidad,
                    "moneda" => $json->moneda,
                    "metoPago" => $json->fpago,
                    "justificacion" => $json->comentarios,
                    "fecelab" => $json->fcorte,
                    "idetapa" => $json->estatus
                );

                $this->db->trans_begin();
                $idsolicitud = $this->Solicitudes_solicitante->insertar_solicitud( $data );
                if ($this->db->trans_status() === FALSE){
                    $this->db->trans_rollback();
                    echo base64_encode( json_encode( array( "resultado" => FALSE ) ) );
                }else{
                    $this->db->trans_commit();
                    echo base64_encode( json_encode( array( "resultado" => TRUE, "solicitud" => $idsolicitud ) ) );
                }
            }else{
                echo http_response_code(403);
                die();
            }
        }else{
            echo http_response_code(403);
            die();
        }
    }

    public function ejemplo(){
        //ES NECESARIO ENVIARLOS POR METODO POST Y UNA CABECERA DE NOMBRE "authorization" CON VALOR DEL TOKEN QUE SE GENERARA

        //ESTRUCTURA REQUERIDA NO MOVER 
        $cabecera = base64_encode(json_encode(array( "cod" => "sha256", "cxp" => "JWT" )));
        $cuerpo = base64_encode(json_encode(array(
            //DATO REQUERIDO CON ESTA INFORMACION.
            "proyecto" => "COMISIONES DE VENTAS",
            //DATO REQUERIDO CON ESTA INFORMACION.
            "empresa" => "19",
            //DATO REQUERIDO CON ESTA INFORMACION.
            "responsable" => "77",
            //DATO REQUERIDO CON ESTA INFORMACION.
            "capturista" => "1963",
            //DATO REQUERIDO CON ESTA INFORMACION.
            "tmovimiento" => "EMBAJADORES MADERAS",
            //DATO REQUERIDO CON ESTA INFORMACION.
            "cuenta" => "28410",
            //DATO REQUERIDO CON ESTA INFORMACION.
            "factura" => 0,
            //DATO REQUERIDO CON ESTA INFORMACION.
            "tgasto" => 0,
            //DATO REQUERIDO CON ESTA INFORMACION.
            "urgente" => 1,
            "cantidad" => 1.00,//CANTIDAD A ENVIAR SIN FORMATO Y SOLO 2 DECIMALES
            //DATO REQUERIDO CON ESTA INFORMACION.
            "moneda" => "MXN",
            //DATO REQUERIDO CON ESTA INFORMACION.
            "fpago" => "EFEC",
            "comentarios" => "PAGO DE COMISIONES",//AQUI EN LA JUSTIFICACIONES SE PUEDE ANEXAR LA SIGUIENTE INFORMACION=> PAGO DE COMISONES PARA LA OFICINA DE BQ1.
            "fcorte" => "2021-09-14",//AGREGAR LA FECHA DE CORTE PARA LAS COMISIONES. FORMATO "2021-01-01".
            //DATO REQUERIDO CON ESTA INFORMACION.
            "estatus" => 7
        )));
        
        //ENVIAR ESTE TOKEN EN LA CABECERA authorization
        echo "TOKEN ACCESO: ".base64_encode(hash_hmac('sha256', $cabecera.".".$cuerpo, date("Ymd")))."<BR/>";
        //ENVIAR ESTE INFORMACION EN EL CUERPO DEL PETICION POST
        echo "INFORMACION A ENVIAR ".$cuerpo;
    }

    public function provedor_banco(){
        $datos_prov['provBanco'] = $this->Provedores_model->getProvBanco($this->input->post("rfc") )->result_array();
        $datos_prov['proyectos'] = $this->Lista_dinamicas->lista_proyectos_depto()->result_array();
        $datos_prov['empresas'] = $this->Lista_dinamicas-> get_empresas_lista()->result_array();
        $datos_prov['homoclaves'] = $this->Lista_dinamicas->lista_homoclaves()->result_array();
        $datos_prov['contratos'] = $this->Solicitudes_solicitante->get_proyecto_by_proveedor( $this->input->post("rfc") )->result_array();
        echo json_encode($datos_prov);
    }

    public function guardar_solxml(){
        $idUsuario = $this->validar_usuario($this->input->post("usuario"),$this->input->post("pass"));
        // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
        if( $this->input->post("usuario") !=null && $this->input->post("pass") !=null && $idUsuario !== FALSE ){
            
            if( (isset($_POST) && !empty($_POST)) && ( isset( $_FILES ) && !empty($_FILES) ) ){
                
                $this->db->db_debug = FALSE; //disable debugging for queries
                $this->db->trans_begin();
                $mensaje = "";
                $resp ="";
                $solpago = array(
                    "proyecto" => null,
                    "homoclave" => limpiar_dato($this->input->post("homoclave")),
                    "etapa" => limpiar_dato($this->input->post("etapa")),
                    "condominio" => limpiar_dato($this->input->post("condominio")),
                    "idEmpresa" => $this->input->post("empresa"),
                    "idResponsable" => "75",
                    "idusuario" =>$idUsuario["idusuario"],
                    "nomdepto" => $idUsuario["depto"], 
                    "idProveedor" => $this->input->post("idproveedor"),
                    "ref_bancaria" => $this->input->post("referencia"),
                    "caja_chica" => 0,
                    "justificacion" => $this->input->post("justificacion"),
                    "fecelab" => date("Y-m-d H:i:s"),
                    "idetapa" => 1, 
                    "tendrafac" => 1,
                    "orden_compra" => $this->input->post("folioOC"),
                    "requisicion"=> $this->input->post("requisicion"),
                    "metoPago"=>$this->input->post("FormaPago"),
                    "Api" => 1,
                    "financiamiento" => ($this->input->post("financiamiento") != null ? $this->input->post("financiamiento") : 0)
                );
                
                $resultado = TRUE;

                if( isset( $_FILES ) && !empty($_FILES) ){

                    $idUsuario = $idUsuario["idusuario"];

                    $config["upload_path"] = "./UPLOADS/XMLS/";
                    $config["allowed_types"] = "xml";
                    $this->load->library("upload", $config);
                    $resultado = $this->upload->do_upload("xmlfile");

                    if( $resultado ){

                        $xml_subido = $this->upload->data();
                        
                        $descripcion="";
                        $datos_xml = $this->Complemento_cxpModel->leerxml( $xml_subido["full_path"], TRUE );
                        $solpago["folio"] = $datos_xml["folio"]."";
                        $solpago["cantidad"] = $datos_xml["Total"];
                        $solpago["fecelab"] = $datos_xml["fecha"];
                        $solpago["moneda"] = $datos_xml["Moneda"]."";
                        $i=0;
                        foreach($datos_xml["conceptos"] as $conceptos){
                            $descripcion .=  "  ".($i+=1).")".$conceptos['Descripcion']." - Importe: $".$conceptos['Importe']." \n";  
                        }
                        
                        if( $datos_xml['version'] >= 4.0){

                            if( $datos_xml["tipocomp"] == "I" ){

                                if( !$datos_xml["TipoRelacion"] || $datos_xml["TipoRelacion"][0] == "04" ){
            
                                    if( isset( $datos_xml['formpago'][0] ) && in_array( $datos_xml['formpago'][0] , array( 1, 3, 4, 5, 6,17, 28, 31, 99, 29 ) ) ){
            
                                        $es_pago_proveedor =  $datos_xml['formpago'][0] == "99" && $datos_xml['MetodoPago'][0] == "PPD";
                                        if( $es_pago_proveedor ){
                                            
                                            $getProveedor = $this->Provedores_model->getProveedor( $this->input->post("idproveedor") )->result_array()[0];
                                            $resultado = (($datos_xml["Total"]+$this->input->post("totalFacturado")) <= $this->input->post("total")) && $datos_xml["rfcemisor"] == $getProveedor["rfc"]  ;
                                            $verrif_uuid = $this->Complemento_cxpModel->verificaruuid( $datos_xml["uuid"] );
                                            $consultaEmpresa = $this->Complemento_cxpModel->verificar_empresa( $datos_xml["rfcrecep"] ); // Consulta de infromacion a la empresa plasmada en el XML

                                            if( ($resultado) && ($verrif_uuid->num_rows() === 0 ) && ($consultaEmpresa->row()->idempresa == $this->input->post("empresa")) ){

                                                if( $this->input->post("cproveedor") == 0 || $this->input->post('residuo') >= $datos_xml["Total"] ){
                                                
                                                    if( $consultaEmpresa ->num_rows() >= 1  ){
                                                        
                                                        if( $this->Solicitudes_solicitante->verificar_proveedor69b( $datos_xml["rfcemisor"], $datos_xml["nomemi"] )->num_rows() == 0 ){
                                                            
                                                            $informacion_proveedor = $this->Solicitudes_solicitante->verificar_proveedor( $datos_xml["rfcemisor"], FALSE );
                                                            if( $informacion_proveedor->num_rows() >= 1  ){
                                                                //
                                                                $consultaProveedor = $this->Complemento_cxpModel->verifica_cat_prov( $datos_xml["rfcemisor"] ) ;
                                                                if( ($consultaProveedor->num_rows()  > 0 ) || ($this->Solicitudes_solicitante->verificar_proveedor_permitido( $datos_xml["rfcemisor"] )->num_rows() === 0 ) ){   
                                                                    
                                                                    if( in_array( $informacion_proveedor->row()->eactual, array( "1", "2", "5" ) ) ){
                                                                        
                                                                        $fechaxml =  date("Y-m", strtotime( substr( $datos_xml["fecha"], 0, 10) ));
                                                                        $fecha_actual = date("Y-m-d"); 
                                                                        $validar_fecha = (($fechaxml == date("Y-m") ) || (date("Y-m",strtotime($fecha_actual."- 1 month")) == $fechaxml && ($fecha_actual <= date("Y-m")."-05") ));
                                                                        
                                                                        if( $validar_fecha ) {
                                                                            $datos_xml["folio_fiscal"] = substr( $datos_xml["uuid"], -5);
                                                                                // Datos para la facturacion 4.0  date( "Y-m-d" )
                                                                                if($fechaxml <="2023-04-01"){
                                                                                    
                                                                                    $data["uuid"]= $datos_xml["uuid"];    
                                                                                    $data["totalfacturado"] = $datos_xml["Total"];
                                                                                    $data["idSolpago"] = $this->Complemento_cxpModel->insertar_solicitud( $solpago );
                                                                                    
                                                                                    if($data["idSolpago"]){
                                                                                        $insert = array(
                                                                                            "idOficina" => $this->input->post('idOficinaid'),
                                                                                            "idProyectos" => $this->input->post('idProyectos'),
                                                                                            "idTipoServicioPartida" => $this->input->post('TipoServicioPartida'),
                                                                                            "idsolicitud" => $data["idSolpago"]
                                                                                        );
                                                                                        $this->Complemento_cxpModel->insertar_solicitud_proyecto_oficina( $insert );
                                                                                    }

                                                                                    $errorSol = ($data["idSolpago"]==null)?"NO FUE POSIBLE GUARDAR LA SOLICITUD,":"";
                                                                                    log_sistema($idUsuario, $data["idSolpago"], "SE HA CREADO UNA NUEVA SOLICITUD");
                                                                                $errorCont="";
                                                                                    if($this->input->post("cproveedor") && $this->input->post("cproveedor") > 0 && $this->input->post('residuo')>=$datos_xml["Total"] ){
                                                                                        $errorCont = $this->Solicitudes_solicitante->guardar_solicitud_contrato( 
                                                                                            array( 
                                                                                                "idsolicitud" => $data["idSolpago"], 
                                                                                                "idcontrato" =>  $this->input->post("cproveedor"),
                                                                                                "idcrea" => $idUsuario,//"75",
                                                                                                "fecha_creacion" => date("Y-m-d H:i:s"),
                                                                                                ));
                                                                                        $errorCont = ($errorCont==1)?" ":" OCURRIO UN ERROR AL GUARDAR CONTRATO, ";
                                                                                    }
                                                                                    $datos_xml["uuid"] = strpos( strtoupper($datos_xml["uuid"]), "UUID" ) ? $datos_xml["uuid"].date("Ymdhis") : $datos_xml["uuid"];
                                                                                    $nuevo_nombre = date("my")."_";
                                                                                    $nuevo_nombre .= str_replace( array(",", ".", '"'), "", str_replace( array(" ", "/"), "_", limpiar_dato($datos_xml["nomemi"]) ))."_";
                                                                                    $nuevo_nombre .= date("His")."_";
                                                                                    $nuevo_nombre .= substr($datos_xml["uuid"], -5).".xml";
                                                                                    rename( $xml_subido["full_path"], "./UPLOADS/XMLS/".$nuevo_nombre );
                                                                                    $datos_xml["nombre_xml"] = $nuevo_nombre;
                                                                                    $datos_xml["tipo_factura"] = $datos_xml["MetodoPago"][0] != "PPD" ? 3 : 1;
                                                            
                                                                                    //TODO LO RELACIONADO CON LOS IMPUESTOS
                                                                                    $datos_xml["impuestot"] = (isset($datos_xml) && $datos_xml["impuestos_T"]) ? json_encode($datos_xml["impuestos_T"]) :  0.00 ;
                                                                                    $datos_xml["impuestor"] = (isset($datos_xml) && $datos_xml["impuestos_R"]) ? json_encode($datos_xml["impuestos_R"]) : 0.00 ;
                                                                                    $datos_xml["subtotal"] = $datos_xml["SubTotal"][0];
                                                                                    $datos_xml["descuento"] = (isset($datos_xml) && $datos_xml["Descuento"] ? $datos_xml["Descuento"][0] : NULL );
                                                                                
                                                                                    $solpago["idsolicitud"] = $data["idSolpago"];
                                                                                    $solpago["descr"] = $descripcion;
                                                                                    $solpago["observaciones"] ="";
                                                                                    $errorFact = $this->Complemento_cxpModel->insertar_factura( $solpago, $datos_xml );
                                                                                    $errorXml = $this->Complemento_cxpModel->guardar_xml($this->db->insert_id() , $datos_xml["textoxml"] );
                                                                                    
                                                                                    if( $datos_xml["Descuento"] && $datos_xml["Descuento"] > 0.00 ){
                                                                                        $this->db->update("solpagos", array( "descuento" => $datos_xml['Descuento'] ), "idsolicitud = '".$data["idSolpago"]."'");
                                                                                    }
            
                                                                                    $mensaje .= "RECUERDA LOS SIGUIENTES CAMPOS SON OBLIGATORIOS PARA FACTURAS \n EMITIDAS A PARTIR DE JULIO 2022: \n --- RÉGIMEN FISCAL \n --- CÓDIGO POSTAL  \n DE EMISOR Y RECEPTOR ";
                                                                                    $resp = TRUE;
                                                                                    //"La informacion fue guardada con exito. EL NUMERO DE SOLICITUD ES # ".$data["idsolicitud"]." ";
                                                                                }else{
                                                                                    
                                                                                    if( $consultaProveedor->num_rows() > 0 && $datos_xml["regfisemisor"] && in_array($datos_xml["regfisemisor"],[$consultaProveedor->row()->rf_proveedor]) && $datos_xml["regfisrecep"] && in_array($datos_xml["regfisrecep"],[$consultaEmpresa->row()->regf_recep]) ){
                                                                                    
                                                                                        $data["uuid"]= $datos_xml["uuid"];    
                                                                                        $data["totalfacturado"] = $datos_xml["Total"];
                                                                                        $data["idSolpago"] = $this->Complemento_cxpModel->insertar_solicitud( $solpago );
                                                                                        if($data["idSolpago"]){
                                                                                            $insert = array(
                                                                                                "idOficina" => $this->input->post('idOficinaid'),
                                                                                                "idProyectos" => $this->input->post('idProyectos'),
                                                                                                "idTipoServicioPartida" => $this->input->post('TipoServicioPartida'),
                                                                                                "idsolicitud" => $data["idSolpago"]
                                                                                            );
                                                                                            $this->Complemento_cxpModel->insertar_solicitud_proyecto_oficina( $insert );
                                                                                        }
                                                                                        $errorSol = ($data["idSolpago"]==null) ? "NO FUE POSIBLE GUARDAR LA SOLICITUD,":"";
                                                                                        log_sistema($idUsuario, $data["idSolpago"], "SE HA CREADO UNA NUEVA SOLICITUD");
                                                                                        $errorCont="";
                                                                                        if( $this->input->post("cproveedor") && $this->input->post("cproveedor") > 0 && $this->input->post('residuo')>=$datos_xml["Total"] ){
                                                                                            $errorCont = $this->Solicitudes_solicitante->guardar_solicitud_contrato( 
                                                                                                array( 
                                                                                                    "idsolicitud" => $data["idSolpago"], 
                                                                                                    "idcontrato" =>  $this->input->post("cproveedor"),
                                                                                                    "idcrea" => $idUsuario,//"75",
                                                                                                    "fecha_creacion" => date("Y-m-d H:i:s"),
                                                                                                    ));
                                                                                            $errorCont = ($errorCont==1)?" ":" OCURRIO UN ERROR AL GUARDAR CONTRATO, ";
                                                                                        }
                                                                                        $datos_xml["uuid"] = strpos( strtoupper($datos_xml["uuid"]), "UUID" ) ? $datos_xml["uuid"].date("Ymdhis") : $datos_xml["uuid"];
                                                                                        $nuevo_nombre = date("my")."_";
                                                                                        $nuevo_nombre .= str_replace( array(",", ".", '"'), "", str_replace( array(" ", "/"), "_", limpiar_dato($datos_xml["nomemi"]) ))."_";
                                                                                        $nuevo_nombre .= date("His")."_";
                                                                                        $nuevo_nombre .= substr($datos_xml["uuid"], -5).".xml";
                                                                                        rename( $xml_subido["full_path"], "./UPLOADS/XMLS/".$nuevo_nombre );
                                                                                        $datos_xml["nombre_xml"] = $nuevo_nombre;
                                                                                        $datos_xml["tipo_factura"] = $datos_xml["MetodoPago"][0] != "PPD" ? 3 : 1;
                                                                
                                                                                        //TODO LO RELACIONADO CON LOS IMPUESTOS
                                                                                        $datos_xml["impuestot"] = (isset($datos_xml) && $datos_xml["impuestos_T"]) ? json_encode($datos_xml["impuestos_T"]) :  0.00 ;
                                                                                        $datos_xml["impuestor"] = (isset($datos_xml) && $datos_xml["impuestos_R"]) ? json_encode($datos_xml["impuestos_R"]) : 0.00 ;
                                                                                        $datos_xml["subtotal"] = $datos_xml["SubTotal"][0];
                                                                                        $datos_xml["descuento"] = (isset($datos_xml) && $datos_xml["Descuento"] ? $datos_xml["Descuento"][0] : NULL );
                                                                                    
                                                                                        $solpago["idsolicitud"] = $data["idSolpago"];
                                                                                        $solpago["descr"] = $descripcion;
                                                                                        $solpago["observaciones"] ="";
                                                                                        
                                                                                        $errorFact = $this->Complemento_cxpModel->insertar_factura( $solpago, $datos_xml );
                                                                                        $errorXml = $this->Complemento_cxpModel->guardar_xml( $this->db->insert_id(), $datos_xml["textoxml"] );
                                                                
                                                                                        if( $datos_xml["Descuento"] && $datos_xml["Descuento"][0] > 0.00 ){
                                                                                            $this->db->update("solpagos", array( "descuento" => $datos_xml["Descuento"][0] ), "idsolicitud = '".$solpago["idsolicitud"]."'");
                                                                                        }
                                                                                        $mensaje .= "La informacion fue guardada con exito. EL NUMERO DE SOLICITUD ES # ".$solpago["idsolicitud"]." ";
                                                                                        $resp = TRUE;
                                                                                    }else{

                                                                                        if( $consultaProveedor->num_rows() > 0 ){

                                                                                            $mensaje = ($datos_xml["regfisemisor"] && $datos_xml["regfisrecep"])
                                                                                            ? "EL REGIMEN FISCAL \n ".(in_array($datos_xml["regfisemisor"],[$consultaProveedor->row()->rf_proveedor]) ? " ":"- EMISOR NO ES CORRECTO \n").(in_array($datos_xml["regfisrecep"],[$consultaEmpresa->row()->regf_recep]) ? " ":"- RECEPTOR NO ES CORRECTO \n  ")."SOLICITE UNA REFACTURACIÓN "
                                                                                            : "LA FACTURA NO CUMPLE CON LOS CAMPOS: \n ".($datos_xml["regfisemisor"] ? " ":"- REGIMEN FISCAL DEL EMISOR \n").($datos_xml["regfisrecep"] ? " ":"- REGIMEN FISCAL DEL RECEPTOR \n ")."SOLICITE UNA REFACTURACIÓN ";
                                                                                            $resp = FALSE;

                                                                                        }else{
                                                                                            $respuesta['respuesta'] = array( FALSE, "EL RÉGIMEN FISCAL DEL PROVEEDOR NO COINCIDE CON EL DADO DE ALTA." );
                                                                                        }
                                                                                    }
                                                                                }
                                                                                // termina Facturacion 4.0
                                                                        }else{
                                                                            
                                                                            $mensaje = "LAS FACTURAS DEL MES ANTERIOR AL ACTUAL SOLO SE PUEDEN CARGAR HASTA EL DIA 5" ;
                                                                            $resp = FALSE;
                                                                        }
                                                                    }else{
                                                                        if( $informacion_proveedor->row()->eactual == 9 ){
                                                                            $mensaje = "AÚN NO SE HA VALIDADO LA DOCUMENTACIÓN DE ESTE PROVEEDOR.";
                                                                            $resp = FALSE;
                                                                        }else{
                                                                            $mensaje = "ESTE PROVEEDOR HA SIDO BLOQUEADO POR EL ÁREA DE CUENTAS POR PAGAR. ".strtoupper($informacion_proveedor->row()->observaciones);
                                                                            $resp = FALSE;
                                                                        }
                                                                    }
                                                                }else{
                                                                    $mensaje = "EL PROVEEDOR SE ENCUENTRA BLOQUEADO ".$datos_xml["rfcemisor"]." HAY GASTOS PENDIENTES POR COMPROBAR.";
                                                                    $resp = FALSE;
                                                                }
                                                            }else{
                                                                $mensaje = "EL PROVEEDOR NO HA SIDO DADO DE ALTA";
                                                                $resp = FALSE;
                                                            }
                                                        }else{
                                                            $mensaje =  "NO ESTÁ PERMITIDO RECIBIR FACTURAS DE ESTE PROVEEDOR POR QUE INFRINGE EL CÓDIGO FISCAL DE LA FEDERACIÓN EN SU ARTÍCULO 69-B.";                                               
                                                            $resp = FALSE;
                                                        }
                                                    }else{
                                                        $mensaje = "EMPRESA RECEPTORA ERRONEA SOLICITE UNA REFACTURACIÓN";
                                                        $resp = FALSE;
                                                    }

                                                }else{
                                                    $mensaje = "EL MONTO DE LA FACTURA EXCEDE EL RESTANTE DEL CONTRATO";
                                                    $resp = FALSE;
                                                }

                                            }else{
                                                if( ($consultaEmpresa->row()->idempresa != $this->input->post("empresa")) ){
                                                    $consultaEmpresaOc = $this->Complemento_cxpModel->verificarEmpresaPorId( $this->input->post("empresa") ); // Consulta de infromacion a la empresa recibida desde el sistema de OC
                                                    $mensaje = "La razón social del cliente no concuerda con la razón social de la OC. RFC OC: ".$consultaEmpresaOc->row()->rfc." RFC factura: ".$datos_xml["rfcrecep"];
                                                }elseif ($verrif_uuid->num_rows() === 0 && (($datos_xml["Total"] + $this->input->post("totalFacturado")) <= $this->input->post("total")) ) {
                                                    $mensaje = "El Emisor de la Factura no concuerda con el Provedoor de la OC.  RFC emisor ".$datos_xml["rfcemisor"]." RFC Proveedor ".$getProveedor["rfc"];
                                                }elseif ($verrif_uuid->num_rows() === 0 && (($datos_xml["Total"] + $this->input->post("totalFacturado")) > $this->input->post("total"))) {
                                                    $mensaje = "El total de la OC: $".$this->input->post("total")." es menor a la sumatoria de la factura actual $".$datos_xml["Total"]." mas el monto facturado $".$this->input->post("totalFacturado")." Total: $".($datos_xml["Total"]+$this->input->post("totalFacturado"));
                                                }else {
                                                    $mensaje = "¡FACTURA YA EXISTENTE EN EL SISTEMA!  \n SE HA CARGADO EL ". $verrif_uuid->row()->feccrea."\n  EL NUMERO DE SOLICITUD ES # ".$verrif_uuid->row()->idsolicitud ;
                                                }
                                                $resp = FALSE;
                                            }
                                        }else{  
                                                $mensaje = "LA FACTURA NO CUMPLE CON LAS CARACTERÍSTICAS PARA SER CONSIDERADA COMO UN GASTO DE PROVEEDOR. VERIFIQUE EL METODO Y LA FORMA DE PAGO." ;
                                                $resp = FALSE;
                                        }
                                    }else{
                                        $mensaje = "LA FORMA DE PAGO NO ESTA PERMITIDO";
                                        $resp = FALSE;
                                    }
                                }else{                           
                                    $mensaje =  "ESTA NO ES UNA FACTURA DE TIPO DE GASTOS.";
                                    $resp = FALSE;
                                }
                            }else{                           
                                $mensaje =  "EL TIPO DE COMPROBANTE NO ES EL CORRECTO, DEBE SER DE TIPO INGRESOS (I).";
                                $resp = FALSE;
                            }
                        }else{           
                            $mensaje = "LA VERSION DE LA FACTURA ES INFERIOR A LA 4.0, SOLICITE UNA REFACTURACIÓN";
                            $resp = FALSE;
                        }
                    }else{
                        $mensaje = $this->upload->display_errors();
                        $resp = FALSE;
                    }
                }
        
                if ($this->db->trans_status() === FALSE ){ 
                    $this->db->trans_rollback();
                    $data = array("resultado" => FALSE,
                                  "mensaje" =>  ( ($errorSol != "") ? $errorSol : "" ).
                                                $errorCont.
                                                ( ($errorFact == 1) ? "" : "NO FUE POSIBLE GUARDAR LA FACTURA, " ).
                                                ( ($errorXml == 1) ? "" : "NO FUE POSIBLE GUARDAR EL XML, " ).
                                                " CONTACTE AL ADMINISTRADOR ");
                }else{
                    $this->db->trans_commit();
                    $data["resultado"] =$resp;
                    $data["mensaje"] = $mensaje;
                }      
            }
            else{
                $data = array("resultado" => FALSE,"mensaje" => "No se cargo ningun archivo");  
                  
            }
        }else{
            $data = array("resultado" => FALSE,"mensaje" => "Usuario invalido");
        }
        echo json_encode($data);
    }

    public function consultaOcFact(){
        $info = $this->Solicitudes_solicitante->getInfoOC($_POST["folioOc"])->result_array();;
        echo json_encode($info);
    }

    public function borrarSolpago(){
        $idUsuario = $this->validar_usuario($this->input->post("usuario"),$this->input->post("pass")); 
        // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']); 
        if($this->input->post("usuario")!=null && $this->input->post("pass")!=null && $idUsuario !== FALSE ){

            $idUsuario = $idUsuario["idusuario"];

            if($this->input->post("idsolicitud")!= null ){  
                $sol = $this->db->query("SELECT * FROM solpagos WHERE idetapa !=0 AND idsolicitud = '".$this->input->post("idsolicitud")."';");
               if($sol->num_rows() > 0){
                $respuesta['resultado'] = $this->Solicitudes_solicitante->borrar_solicitud( $this->input->post("idsolicitud") )["respuesta"];
                $respuesta['mensaje'] = ($respuesta['resultado'])
                ? "SE HA ELIMINADO LA SOLICITUD DEL SISTEMA"
                : "NO FUE POSIBLE ELIMINAR LA SOLICITUD" ;
                log_sistema($idUsuario, $this->input->post("idsolicitud"), "HA ELIMINADO LA SOLICITUD DEL SISTEMA");
               }else{
                $sol_del = $this->db->query("SELECT * FROM solpagos WHERE idetapa = 0 AND idsolicitud = '".$this->input->post("idsolicitud")."';");
                $respuesta =($sol_del->num_rows() > 0)
                ? array( "resultado" => TRUE, "mensaje" => "LA SOLICITUD YA FUE ELIMINADA DE CXP" )
                : array( "resultado" => FALSE, "mensaje" => "IDSOLICITUD NO EXISTE" );
               }
            }else{
                $respuesta = array( "resultado" => FALSE, "mensaje" => "ID SOLICITUD REQUERIDO" );
            }
        }else{
            $respuesta = array( "resultado" => FALSE, "mensaje" => "Usuario invalido" );
        }
        echo json_encode( $respuesta );
    }

    function validar_usuario($nickname, $pws){
            /*
            $consulta = $this->db->query("SELECT * FROM usuarios WHERE nickname='$nickname'");
            return ($consulta->num_rows()>0)
            ?  (desencriptar($consulta->row()->pass) == base64_decode($pws) ) ? $consulta->row()->idusuario : 0
            :  0;
            */
            $usuario = $this->db->query("SELECT idusuario, nickname, pass, depto FROM usuarios WHERE nickname='$nickname' AND estatus = 1");
            if( $usuario->num_rows()>0 ){
                $usuario = $usuario->result_array()[0];
                if( desencriptar($usuario["pass"]) == base64_decode($pws) ){
                    return $usuario;
                }
                return FALSE;
            }
            return FALSE;
    }


        
    public function notas_credito(){
        if($this->input->post("usuario")!=null && $this->input->post("pass")!=null && $this->validar_usuario($this->input->post("usuario"),$this->input->post("pass")) !== FALSE ){
            $respuesta = array( "resultado" => FALSE, "mensaje" => "HA OCURRIDO UN ERROR" );
            if( isset( $_FILES ) && !empty($_FILES) ){
                $config['upload_path'] = './UPLOADS/XMLS/';
                $config['allowed_types'] = 'xml';
                $this->load->library('upload', $config);
                if( $this->upload->do_upload("xmlfile") ){
                    $respuesta = array( "resultado" => FALSE, "mensaje" => "NO CUMPLE LAS CARACTERISTICAS PARA SER UNA FACTURA COMPLEMENTO Y/O VALIDA" );
                    $xml_subido = $this->upload->data();
                    $datos_xml = $this->Complemento_cxpModel->leerxml( $xml_subido['full_path'] );
                
                    // solo se queda 01	Nota de crédito de los documentos relacionados
                    if( $datos_xml['TipoRelacion'] && in_array( $datos_xml['TipoRelacion'][0], array(  "01", "03", "07"  ) ) ){
                        // valida que el xml contenga el uuidR y solo sea 1
                        if( $datos_xml['uuidR'] && count( $datos_xml['uuidR'] ) == 1 ){
                            
                            if( $this->Complemento_cxpModel->facturas_relacionadas( "'".$datos_xml["uuidR"][0]["UUID"]."'"  )->num_rows() > 0 ){
                                    $respuesta= array( "resultado" => TRUE);  
                                    $datos_xml['textoxml'] = "";
                                    $respuesta['datos_xml'] = $datos_xml;
                            }else{
                                $respuesta = array( "resultado" => FALSE, "mensaje" => "NO HAY FACTURA RELACIONADA. VERIFIQUE EL FOLIO FISCAL DE LA FACTURA ORIGINAL CON LA RELACIONADA: </br>".$datos_xml['uuidR'][0]['UUID'] ); 
                            }   
                        }else{
                            $respuesta = array( "resultado" => FALSE, "mensaje" => "NO CONTIENE INDICADA UNA RELACION VALIDA. VERIFIQUÉ QUE SOLO SEA 1 FOLIO FISCAL O QUE SEA CORRECTO EL TIPO DE RELACION ( 01, 03, 07 )");
                        }
                    }else{
                        $respuesta = array(  "resultado" => FALSE, "mensaje" => "TIPO DE RELACION INVALIDO PARA SER UNA NOTA DE CREDITO, VERIFIQUE EL TIPO DE RELACIÓN.");
                    }
                    if( !$respuesta["resultado"]){
                        unlink( $xml_subido["full_path"] );
                    }
                }else{
                    $respuesta = array( "resultado" => FALSE, "mensaje" => $this->upload->display_errors() );
                }
            }
        }else{
            $respuesta = array( "resultado" => FALSE, "mensaje" => "Usuario invalido" );
        } 
        echo json_encode( $respuesta );
    }

    ////   SOCIO MADERAS  ///
    public function catalogo_socio(){
        // empresas 
        // clientes
        $datos_prov['empresas'] = $this->Lista_dinamicas->get_empresas_socio()->result_array();
        $datos_prov['inversionista'] = $this->Lista_dinamicas-> get_invercionistas_socio()->result_array();
        $datos_prov['empresa_destino'] = $this->Lista_dinamicas-> empresas_socio()->result_array();
        $datos_prov['bancos'] = $this->db->query("SELECT idbanco, nombre FROM bancos where estatus = 1")->result_array();
        $datos_prov['forma_pago'] = array("TEA" => "Transferencia electrónica", "MAN"=> "Manual");
       
        echo json_encode($datos_prov);
    }

    public function solpago_socio(){

        $idProveedor=0;
        if($_POST["rfc"] != null && $_POST["clabe"]!=null ){
            $this->load->model(array('Provedores_model'));
            $this->db->trans_begin();
            $prov = $this->db->query("SELECT * FROM proveedores where rfc= '".$_POST["rfc"]."' and cuenta = '".$_POST["clabe"]."'");

            if( $prov->num_rows()>0 ){
                $idProveedor = $prov->row()->idproveedor;
            }else{
                // insert proveedor
                $insertProv = array(
                    "nombre" => $this->input->post("nombre_prov"),
                    "idbanco" => $this->input->post("idBanco") ,
                    "tipocta" => 40, // para CLABE
                    "cuenta" =>$this->input->post("clabe") , // CLABE
                    "rfc" => $this->input->post("rfc") ,
                    "estatus" => 1,
                    "email" => $this->input->post("email"),
                    "tipo_prov" => 0,
                    "fecadd" => date("Y-m-d H:i:s"),
                    "excp"=> 0,
                    "rf_proveedor"=>616,
                    "cp_proveedor"=>00000
                );
                $this->Provedores_model->insertar_nuevo( $insertProv );
                $idProveedor = $this->db->insert_id();
            }
        }
            
        if($_POST['idEmpresa'] !=null && $_POST["cantidad"] !=null && $_POST["formaPago"] !=null && $_POST["fechaPago"] !=null && $_POST["referencia"] !=null && $_POST["justificacion"] != null) {
                
            $idUsuario = 2058;
            // && $_POST["idProveedor"]!=null
                $solpago = array(
                    "folio" => 'N/A',
                    "proyecto"=>"PAGO SOCIO MADERAS",
                    "idEmpresa" => $this->input->post("idEmpresa"),
                    "idProveedor" => $idProveedor,
                    "idusuario" => $idUsuario ,
                    "idResponsable" => "77",
                    "cantidad"=> $this->input->post("cantidad"),
                    "metoPago"=>$this->input->post("formaPago"),
                    "fecelab" => $this->input->post("fechaPago"),
                    "idetapa" => 7,
                    "ref_bancaria" => $this->input->post("referencia")." ".$this->input->post("fechaPago"),
                    "justificacion" => $this->input->post("justificacion"),
                    "nomdepto" => "ADMINISTRACION", 
                    "caja_chica" => 0,
                    "moneda"=>"MXN",
                    "Api" => 1,
                    "homoclave" => 'N/A',
                    "etapa" => 'N/A',
                    "condominio" => 'N/A',    
                );

            $idsolicitud = $this->Complemento_cxpModel->insertar_solicitud( $solpago );
        
            log_sistema($idUsuario, $idsolicitud , "SE HA CREADO UNA NUEVA SOLICITUD");

            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
            //echo base64_encode( json_encode( array( "resultado" => FALSE ) ) );
            echo  json_encode( array( "resultado" => FALSE ) );
            }else{
                $this->db->trans_commit();
            // echo base64_encode( json_encode( array( "resultado" => TRUE, "solicitud" => $idsolicitud ) ) );
            echo json_encode( array( "resultado" => TRUE, "solicitud" => $idsolicitud ) );
            }

        }else{
            echo  json_encode( array( "resultado" => FALSE , "mensaje"=>"Campos requeridos faltantes") );
        }
    }

    public function obtener_proveedores() {
        if (isset($_POST) && !empty($_POST)) {
            $usuario = $this->validar_usuario($this->input->post("usuario"),$this->input->post("pass"));
            if($usuario && count($usuario) > 0){
                $datos = $this->proveedores_departamento($usuario['depto']);
                echo json_encode( array( "resultado" => TRUE, "listado_proveedores" => $datos->result_array() ) );
            }else {
                echo json_encode( array( "resultado" => FALSE, "mensaje" => 'Usuario y/o contraseña no válidos o dados de baja. Por favor, contacte al administrador' ) );
            }
        }else {
            echo json_encode( array( "resultado" => FALSE, "mensaje" => 'Usuario y/o contraseña no ingresados. Por favor, intente nuevamente o contacte al administrador.' ) );        
        }
    }

    function proveedores_departamento($nom_depto) {
        return $this->db->query("SELECT * 
                                FROM proveedores 
                                INNER JOIN (SELECT bancos.idbanco, bancos.nombre AS nom_banco 
                                            FROM bancos ) AS bancos 
                                    ON proveedores.idbanco = bancos.idbanco 
                                WHERE proveedores.idproveedor NOT IN ( SELECT idproveedor 
                                                                       FROM provedores_bloqueados 
                                                                       WHERE nomdepto = ? ) AND 
                                    proveedores.estatus = 1 AND 
                                    proveedores.excp IN ( 1, 2 )", [$nom_depto]);
    }
}