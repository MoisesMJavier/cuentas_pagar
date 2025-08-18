<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//add library
require_once APPPATH.'/third_party/spout/src/Spout/Autoloader/autoload.php';

//lets Use the Spout Namespaces
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Common\Entity\Row;

use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Box\Spout\Common\Entity\Style\Color;

class Complementos_cxp extends CI_Controller {

    function __construct(){
        parent::__construct();
        if( $this->session->userdata("inicio_sesion") )
            $this->load->model(array('Complemento_cxpModel', 'Consulta', 'Lista_dinamicas', 'Solicitudes_solicitante' ));    
        else
            redirect("Login", "refresh");
    }

    public function index(){
        $this->load->view("v_comple_cxp");
    }

    public function etiquetas_facturas(){
        $this->load->view("v_clasifica_facturas");
    }

    public function relacionar_nsol(){
        // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
        $data =  base64_decode(file_get_contents('php://input'));
        $resultado = array( "resultado" => FALSE );
        if( $data && $data !== FALSE ){

            $data = json_decode( $data );

            $idsolicitudes = explode( ",", $data->numsols );
            if( $idsolicitudes !== FALSE ){
                
                $this->db->trans_begin();

                $datos = [];
                $this->db->delete("sol_factura", "idfactura = ".$data->idfactura );
                
                for( $i=0; $i < count($idsolicitudes); $i++ ){
                    $datos[] = [
                        "idsolicitud" => $idsolicitudes[$i],
                        "idfactura" => $data->idfactura
                    ];
                }
                $this->db->insert_batch( "sol_factura", $datos );
        
                $this->db->update("facturas", [
                    "idsolicitudr" => $data->numsols
                    ], "idfactura = $data->idfactura" );
                
                if( $data->completadas ){
                    $this->db->query("UPDATE solpagos SET tendrafac = 1, idetapa = 11 WHERE idsolicitud IN ( $data->numsols ) AND idetapa IN ( 10 )");
                    $this->Complemento_cxpModel->multiLogs( "HA CARGADO UNA FACTURA AL SISTEMA", $data->numsols );
                }
        
                if ($this->db->trans_status() === FALSE){
                    $this->db->trans_rollback();
                    $resultado = array( "resultado" => FALSE );
                }else{
                    $this->db->trans_commit();
                    $resultado = array( "resultado" => TRUE );
                }
            }
        }

        echo json_encode( $resultado );
    }

    public function gastos_comprobados(){
        //$this->load->view("v_comple_cxp_comprobados");
        $this->load->view("v_listado_facturas");
    }

    public function tabla_pagos_sin_factura(){
        echo json_encode( array( "data" => $this->Complemento_cxpModel->getPagossfactura()->result_array() ));
    }

    public function tabla_pagos_comprobantes(){
        echo json_encode( array( "data" => $this->Complemento_cxpModel->getComprobantes()->result_array() ));
    }

    public function tabla_pagos_sin_complemento(){
        echo json_encode( array( "data" => $this->Complemento_cxpModel->getPagossProvision()->result_array() ));
    }
    public function tabla_facturas_etiqueta(){
        $data = $_POST["etiqueta"] == 0 ? $this->Complemento_cxpModel->getfacturaxetiquetar()->result_array() 
                                        : $this->Complemento_cxpModel->getfactura_etquetada()->result_array() ;
        echo json_encode( array( "data" => $data ));
    }
    public function tabla_pagos_complemento(){
        echo json_encode( array( "data" => $this->Complemento_cxpModel->getGastosComprobados( $this->input->post("opcion") )->result_array(), "permisos" => in_array($this->session->userdata("inicio_sesion")['rol'], array('CP')) ));
    }
    public function getEtiquetas(){
        echo json_encode( array("data"=> $this->db->query("SELECT * FROM etiquetas")->result_array()));
    }
    public function add_etiqueta(){

        $respuesta = array("res" => FALSE , "mensaje" => "FALLO La transaccion" );

        if( isset( $_POST ) && !empty( $_POST ) ){
            $log = [
                "idusuario"    =>  $this->session->userdata("inicio_sesion")['id'],
                "idsolicitud"  =>  $_POST["idsolicitud"],
                "tipomov"      =>  "SE AGREGO LA ETIQUETA: ".$_POST["etiqueta"],
                "fecha"        =>   date("Y-m-d H:i:s")
            ];
             
            // insert abssols
            $obs = [
                "idsolicitud"   =>  $_POST["idsolicitud"],
                "idusuario"     =>  $this->session->userdata("inicio_sesion")['id'],
                "obervacion"   =>  $_POST["observacion"],
                "fecreg"         =>  date("Y-m-d H:i:s")
            ];
    
            $this->db->db_debug = FALSE; //disable debugging for queries
            $this->db->trans_begin();

            $data = $this->Complemento_cxpModel->validarEtiqueta( $_POST["idsolicitud"], $_POST["idetiqueta"] );
            $log["etapa"] = $data["etapa"];
            $this->db->insert("logs", $log);
            $this->db->insert("obssols", $obs);



            $this->db->insert("etiqueta_sol", ["idsolicitud" => $_POST["idsolicitud"], "idetiqueta" => $_POST["idetiqueta"] ]);  
            
            if ($this->db->trans_status() === FALSE ){ 
                $this->db->trans_rollback();
                $respuesta = array("res" => FALSE , "mensaje" => "FALLO La transaccion" );
            }else{
                $this->db->trans_commit();
                $respuesta = array("res" => TRUE , "mensaje" => "Todo Bien :)" );
                
            } 
        }

        echo json_encode($respuesta);

    }

    public function edit_etiqueta(){

        $respuesta = array("res" => FALSE , "mensaje" => "FALLO La transaccion" );

        if( isset( $_POST ) && !empty( $_POST ) ){

            $log = [
                "idusuario"    =>  $this->session->userdata("inicio_sesion")['id'],
                "idsolicitud"  =>  $_POST["idsolicitud"],
                "tipomov"      =>  "SE ACTUALIZÓ LA ETIQUETA A: ".$_POST["etiqueta"],
                "fecha"        =>   date("Y-m-d H:i:s")
            ];
            $obs = [
                "idsolicitud"   =>  $_POST["idsolicitud"],
                "idusuario"     =>  $this->session->userdata("inicio_sesion")['id'],
                "obervacion"   =>  $_POST["observacion"],
                "fecreg"         =>  date("Y-m-d H:i:s")
            ];

            $this->db->db_debug = FALSE;
            $this->db->trans_begin();

            $data = $this->Complemento_cxpModel->validarEtiqueta( $_POST["idsolicitud"], $_POST["idetiqueta"] );
            $log["etapa"] = $data["etapa"];

            $this->db->insert("logs", $log); 
            $this->db->insert("obssols", $obs);  
            $this->db->update("etiqueta_sol", ["idetiqueta" => $_POST["idetiqueta"]], "idsolicitud = ".$_POST["idsolicitud"]);  

            if ($this->db->trans_status() === FALSE ){ 
                $this->db->trans_rollback();
                $respuesta = array("res" => FALSE , "mensaje" => "FALLO La transaccion" );
            }else{
                $this->db->trans_commit();
                $respuesta = array("res" => TRUE , "mensaje" => "Todo Bien :)" );
                
            }     
        }

        echo json_encode($respuesta);

    }
    public function cargaxml_complemento(){
        
        // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);

        $validar_xml = $this->input->post( 'validarxml' );
        $respuesta = array( "respuesta" => array( FALSE, "HA OCURRIDO UN ERROR") );
        if( isset( $_FILES ) && !empty($_FILES) ){

            $config['upload_path'] = './UPLOADS/XMLS/';
            $config['allowed_types'] = 'xml';
            
            //CARGAMOS LA LIBRERIA CON LAS CONFIGURACIONES PREVIAS -----$this->upload->display_errors()
            $this->load->library('upload', $config);
            
            if( $this->upload->do_upload("xmlfile") ){
                $this->db->trans_begin();


                if(  in_array($this->session->userdata("inicio_sesion")['rol'], array('CP')) && $this->input->post("cancelarxml") == 1 ){
                    $this->Complemento_cxpModel->update_facturabysol( $this->input->post("id"), 0 );
                }

                $respuesta = array( "respuesta" => array( FALSE, "NO CUMPLE LAS CARACTERISTICAS PARA SER UNA FACTURA COMPLEMENTO Y/O VALIDA ") );

                $xml_subido = $this->upload->data();
                $datos_xml = $this->Complemento_cxpModel->leerxml( $xml_subido['full_path'] );
                $informacion_solicitud = $this->Complemento_cxpModel->getSolicitud( $this->input->post("id") )->row();
                $notificacion ="";

                /*
                //VALIDAMOS LOS DATOS EMPRESA
                $consultaEmpresa = $this->Complemento_cxpModel->verificar_empresa( $datos_xml['rfcrecep'] );
                if( $consultaEmpresa->num_rows() == 0 ){
                    $consultaEmpresa = $this->Complemento_cxpModel->verificar_empresa( $datos_xml['rfcemisor'] )->num_rows() > 0;
                }else{
                    $regimen_receptor = in_array( $datos_xml["regfisrecep"],[$consultaEmpresa->row()->regf_recep]);
                }

                //VALIDAMOS LOS DATOS PARA PROVEEDORES
                $consultaProveedor =  $this->Complemento_cxpModel->verifica_cat_prov( $datos_xml['rfcemisor'] );
                if( $consultaProveedor->num_rows() == 0 ){
                    $consultaProveedor =  $this->Complemento_cxpModel->verifica_cat_prov( $datos_xml['rfcrecep'] )->num_rows() > 0;
                }else{
                    $regimen_emisor = in_array( $datos_xml["regfisemisor"], isset($consultaProveedor->row()->regf_emisor) ? [$consultaProveedor->row()->regf_emisor] : ["Sin registro"] );
                }

                if((date( "Y-m-d" )<="2022-05-01")){
                    //NOTIFICAR
                    $notificacion = "CAMPOS OBLIGATORIOS A PARTIR DE MAYO 2022:\n - REGIMEN FISCAL EMISOR: \n • • En XML : ".($datos_xml["regfisemisor"] ? $datos_xml["regfisemisor"]."\n":"Sin registro\n")."• • Debería ser : ".(isset($consultaProveedor->row()->regf_emisor)?$consultaProveedor->row()->regf_emisor:"Sin registro")."\n"." - REGIMEN FISCAL RECEPTOR: \n • • En XML : ".($datos_xml["regfisrecep"] ? $datos_xml["regfisrecep"]."\n":" Sin registro\n")."• • Debería ser : ".($consultaEmpresa->row()->regf_recep)."\n"." - CODIGO POSTAL DEL RECEPTOR: \n • • En XML : ".($datos_xml["cpfisrecep"] ? $datos_xml["cpfisrecep"]."\n":"Sin registro\n")."• • Debería ser : ".($consultaEmpresa->row()->domf_recep)."\n"."LOS DATOS DEBERAN COINCIDIR CON LOS REGISTROS";
                    $validacionregimen = true;
                }else{
                    $validacionregimen = ($datos_xml["regfisemisor"] && $regimen_emisor && $datos_xml["regfisrecep"] && $regimen_receptor )
                    ? true
                    : false;
                }
                */

                if( $datos_xml['version'] >= 4.0 || (in_array($this->session->userdata("inicio_sesion")['rol'], array('CP', 'SO', 'FP')) || $validar_xml )){
                    
                    if( $datos_xml['uuid'] !== FALSE )
                        $uuid = count( $datos_xml['uuid'] ) > 0 ? $datos_xml['uuid'][ count( $datos_xml['uuid'] ) - 1 ] : $datos_xml['uuid'];
                    else{
                        $uuid = '';
                    }
                        
                    $factura_duplicada = $this->Complemento_cxpModel->verificar_uuid( $uuid );
                    if( $factura_duplicada->num_rows() === 0){
                        if( ( $datos_xml['uuid'] || (in_array($this->session->userdata("inicio_sesion")['rol'], array('CP', 'SO')) ) && $validar_xml )){
                            if( !$datos_xml['TipoRelacion'] || !in_array( $datos_xml['TipoRelacion'][0], array( "01", "03" ) ) || (in_array($this->session->userdata("inicio_sesion")['rol'], array('CP', 'SO')) && $validar_xml ) ){
                                // Facturacion 4.0 
                                if(/*$validacionregimen*/TRUE){
                                if( $datos_xml['idComplementos'] && COUNT( $datos_xml['idComplementos'] ) > 0 ){
                                    if( TRUE/*strtoupper($informacion_solicitud->uuid) == strtoupper($datos_xml['idComplementos'][0]['IdDocumento'])*/ ){
                                        
                                        //************GENERA EL NUEVO NOMBRE DEL DOCUMENTO XML SUBIDO AL SISTEMA*********/
                                        // $nuevo_nombre = "CXP_";
                                        // $nuevo_nombre .= str_pad((count(glob('./UPLOADS/XMLS/CXP_*')) + 1), 5, "0", STR_PAD_LEFT)."_";
                                        $nuevo_nombre = date("my")."_";
                                        $nuevo_nombre .= str_replace( array(",", ".", '"'), "", str_replace( array(" ", "/"), "_", limpiar_dato($datos_xml["nomemi"]) ))."_";
                                        $nuevo_nombre .= date("Hms")."_";
                                        $nuevo_nombre .= substr( $datos_xml['uuid'][0], -5).".xml";

                                        rename( $xml_subido['full_path'], "./UPLOADS/XMLS/".$nuevo_nombre );
                                        //**************AQUI ES DONDE SE TERMINA EL RENOMBRAMIENTO AL ARCHIVO*****************/
                                        //LINEA ORIGINAL
                                        //$datos_xml['nombre_xml'] = $xml_subido['file_name'];
                                        $datos_xml['nombre_xml'] = $nuevo_nombre;
                                        
                                        /**VARIAS FACTURAS EN UN SOLO COMPLEMENTO**/
                                        $datos_xml['tipo_factura'] = 2;
                                        /**/
                                            
                                        $data["idsolicitud"] = $this->input->post("id");
                                        $datos_xml['uuid'] = strtoupper($datos_xml['uuid'][0]);     
                                        $data["observaciones"] = $datos_xml['condpago'] ? $datos_xml['condpago'][0] : 'NA';
                                        $data["descr"] = '';

                                        if( $datos_xml['conceptos'] ){
                                            foreach( $datos_xml['conceptos'] as $row ){
                                                $data['descr'] .= $row["Descripcion"];
                                            }
                                        }
                                        
                                        //GUARDAMOS LA FACTURA EN LA TABLA DE FACTURAS
                                        $datos_xml['idComplementos'] = json_encode($datos_xml['idComplementos']);    
                                        $datos_xml['idComplementos'] = json_decode($datos_xml['idComplementos'], TRUE);

                                        $documentos_relacionados = array();
                                        foreach( $datos_xml['idComplementos'] as $resultado ){
                                            $documentos_relacionados[] = $resultado["@attributes"];
                                        }

                                        //GUARDAMOS LA FACTURA EN LA TABLA DE FACTURAS
                                        $datos_xml['pagos'] = json_encode($datos_xml['pagos']);    
                                        $datos_xml['pagos'] = json_decode($datos_xml['pagos'], TRUE);
                                        $pagos = array();
                                        foreach( $datos_xml['pagos'] as $resultado ){
                                            $pagos[] = $resultado["@attributes"]["Monto"];
                                        }

                                        usort( $documentos_relacionados, function ( $a, $b ){
                                            $a = count( $a );
                                            $b = count( $b );

                                            return $a == $b ? 0 : ( $a > $b ? -1 : 1);
                                        });

                                        $keys_array = array_keys($documentos_relacionados[0]);

                                        for( $i = 0; $i < count( $documentos_relacionados ); $i++ ){
                                            if( count( $documentos_relacionados[$i] ) < count( $keys_array ) ){
                                                foreach( $keys_array as $key ){
                                                    if( !array_key_exists( $key, $documentos_relacionados[$i] ) ){
                                                        $documentos_relacionados[$i][$key] = null;
                                                    }
                                                }
                                            }

                                            if( !isset( $documentos_relacionados[$i]["ImpPagado"] ) ){
                                                $documentos_relacionados[$i]["ImpPagado"] = $pagos[$i];
                                            } 
                                        }

                                        
                                        $this->Complemento_cxpModel->insert_temporal( $documentos_relacionados );
                                        $this->Complemento_cxpModel->insertar_factura( $data, $datos_xml );
                                        $idfactura = $this->db->insert_id();
                                        $this->Complemento_cxpModel->guardar_xml( $idfactura, $datos_xml["textoxml"] );
                                        $this->Complemento_cxpModel->liberar_pendientes( $idfactura );
                                        
                                        ///////// Id de solicitud y folio fiscal para mostrar en el front-end //////////
                                        $uuids = [];
                                        foreach($documentos_relacionados as $documento){
                                            $uuids[] = "'".$documento['IdDocumento']."'";
                                        }

                                        $facturas = $this->Complemento_cxpModel->get_facturas_uuid($uuids, $datos_xml['uuid'])->result_array();
                                        $respuesta['uuids'] = $facturas;
                                        ///////// Se envió el arreglo de folios fiscales y solicitudes para mostrar en el front-end //////
                                        
                                        //log_sistema($this->session->userdata("inicio_sesion")['id'], $this->input->post("id"), "HA CARGADO UN COMPLEMENTO A LA SOLICITUD");
                                        
                                        if( count( $facturas ) > 0/*$this->db->query("SELECT idsolicitud FROM autpagos WHERE idfactura = ?", [ $idfactura ])->num_rows() > 0*/ || (in_array($this->session->userdata("inicio_sesion")['rol'], array('CP')) && $validar_xml ) ){
                                            $respuesta['respuesta'] = array( TRUE , $notificacion);
                                        }else{
                                            $respuesta['respuesta'] = array( FALSE, "NO EXISTE ALGUNA FACTURA QUE ESTÉ RELACIONADA CON ESTE COMPLEMENTO, VERIFIQUE EL FOLIO FISCAL RELACIONADO EN EL COMPLEMENTO, CON EL FOLIO FISCAL QUE DESEA COMPROBAR.");
                                        }

                                    }else{
                                        $respuesta['respuesta'] = array( FALSE, "FACTURA NO CORRESPONDE A ESTA SOLICITUD. VERIFIQUE EL FOLIO FISCAL DE LA FACTURA ORIGINAL CON LA RELACIONADA EN EL COMPLEMENTO.");
                                        
                                    }
                                }
                                elseif( isset( $datos_xml['MetodoPago'] ) && $datos_xml['moneda'][0] != "XXX" || (in_array($this->session->userdata("inicio_sesion")['rol'], array('CP', 'SO')) && $validar_xml )){

                                    if( $datos_xml['rfcemisor'] == $informacion_solicitud->rfc_proveedores || $datos_xml['rfcemisor'] == $informacion_solicitud->rfc_empresas/*|| in_array( $this->session->userdata("inicio_sesion")['rol'], array( 'CP' ))*/
                                    || (in_array($this->session->userdata("inicio_sesion")['rol'], array('CP', 'SO')) && $validar_xml ) )
                                    {

                                        if( $datos_xml['rfcrecep'] == $informacion_solicitud->rfc_empresas || $datos_xml['rfcrecep'] == $informacion_solicitud->rfc_proveedores/*in_array( $this->session->userdata("inicio_sesion")['rol'], array( 'CP' ))*/ 
                                        || (in_array($this->session->userdata("inicio_sesion")['rol'], array('CP', 'SO')) && $validar_xml ))
                                        {
                                            //NO ESTA SUMANDO LOS 0 Y TRUNCA LOS DECIMALES
                                            $total = (FLOAT)$this->Complemento_cxpModel->getTotalPagado( $this->input->post("id") );
                                            if($total < 0){
                                                $total = $datos_xml['Total'];
                                            }else{
                                                $total = (FLOAT)$total + (FLOAT)$datos_xml['Total'];
                                            }
                                            
                                            //$total = $datos_xml['Total'];
                                            if(/* $datos_xml['Total'] >= ( number_format( $informacion_solicitud->cantidad, "2", '.', '' ) - .05 ) 
                                                &&*/ $total <= ( (FLOAT)number_format( $informacion_solicitud->cantidad, "2", '.', '' ) + (FLOAT)1.05 )
                                                || in_array( $this->session->userdata("inicio_sesion")['rol'], array( 'CP', 'SO', 'SU', 'FP' ) )
                                                || (in_array($this->session->userdata("inicio_sesion")['rol'], array('CP', 'SO')) && $validar_xml )
                                            ) 
                                            {
                                                $fechaxml =  date('Y-m-d', strtotime( substr( $datos_xml['fecha'], 0, 10) ));
                                                
                                                $fecha_actual = date("Y-m-d");
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
                                                */
                                                //$validar_fecha = $fechaxml >= $limite_inferior && $fechaxml <= $limite_superior;
                                                //$validar_fecha = $fechaxml >= date("Y")."-01-01" && $fechaxml <= date("Y")."-12-31";
                                                //$validar_fecha = $fechaxml >= "2019-01-01" && $fechaxml <= date("Y")."-12-31";

                                                //VERIFICAMOS SI LA FECHA DEL XML ES MAYOR AL 12 DE ENERO 2021
                                                //$validar_fecha =( ( date("Y-m-d") <= '2021-01-18' ) || ( $fechaxml >= date("Y")."-01-01" ) );     
                                                //$validar_fecha = ( date( "Y-m-d" ) <= "2022-01-09" && $fechaxml >= "2021-01-01" && $fechaxml <= "2022-01-09" ) || $fechaxml >= date("Y")."-01-01";           
                                
                                                $fechaxml =  date('Y-m', strtotime( substr( $datos_xml['fecha'], 0, 10) ));
                                                $fecha_actual = date("Y-m-d");
                                                $validar_fecha = (($fechaxml == date("Y-m") ) || (date("Y-m",strtotime($fecha_actual."- 1 month")) == $fechaxml && ($fecha_actual <= date("Y-m")."-05") ));

                                                if( $validar_fecha == FALSE )
                                                    $validar_fecha = in_array( $this->session->userdata("inicio_sesion")['rol'], array( 'CP', 'SU', 'FP'));

                                                if( $validar_fecha || (in_array($this->session->userdata("inicio_sesion")['rol'], array('CP', 'SO')) && $validar_xml ) ){
                                                    if( ( ( $datos_xml['tipocomp'][0] == "N" || in_array( $datos_xml['formpago'][0], array( 1, 3, 2, 4, 28, 31  ) ) )  && $datos_xml['MetodoPago'][0] == "PUE") || ( in_array($this->session->userdata("inicio_sesion")['rol'], array('CP', 'SO') )  && $validar_xml ) ){
                                                        //************GENERA EL NUEVO NOMBRE DEL DOCUMENTO XML SUBIDO AL SISTEMA*********/
                                                        // $nuevo_nombre = "CXP_";
                                                        // $nuevo_nombre .= str_pad((count(glob('./UPLOADS/XMLS/CXP_*')) + 1), 5, "0", STR_PAD_LEFT)."_";
                                                        $nuevo_nombre = date("my")."_";
                                                        $nuevo_nombre .= str_replace( array(",", ".", '"'), "", str_replace( array(" ", "/"), "_", limpiar_dato($datos_xml["nomemi"]) ))."_";
                                                        $nuevo_nombre .= date("Hms")."_";
                                                        $nuevo_nombre .= substr($datos_xml["uuid"][0], -5).".xml";

                                                        rename( $xml_subido['full_path'], "./UPLOADS/XMLS/".$nuevo_nombre );
                                                        //**************AQUI ES DONDE SE TERMINA EL RENOMBRAMIENTO AL ARCHIVO*****************/
                                                        //LINEA ORIGINAL
                                                        //$datos_xml['nombre_xml'] = $xml_subido['file_name'];
                                                        $datos_xml['nombre_xml'] = $nuevo_nombre;
                                                        $datos_xml['tipo_factura'] = $datos_xml['MetodoPago'][0] != 'PPD' ? 3 : 1;

                                                        //TODO LO RELACIONADO CON LOS IMPUESTOS
                                                        $datos_xml['subtotal'] = (isset($datos_xml) ? $datos_xml['SubTotal'][0] : NULL );
                                                        $datos_xml['descuento'] = (isset($datos_xml) && $datos_xml['Descuento'] ? $datos_xml['Descuento'][0] : NULL );
                                                        $datos_xml['impuestot'] = (isset($datos_xml) && $datos_xml['impuestos_T'] ? json_encode($datos_xml['impuestos_T']) :  NULL );
                                                        //$datos_xml['tasacuotat'] = (isset($datos_xml) && $datos_xml['impuestos_T'] ? $datos_xml['impuestos_T'][0]['TasaOCuota'] : NULL );
                                                        //$datos_xml['importet'] = (isset($datos_xml) && $datos_xml['impuestos_T'] ? $datos_xml['impuestos_T'][0]['Importe'] : NULL );
                                                        $datos_xml['impuestor'] = (isset($datos_xml) && $datos_xml['impuestos_R'] ? json_encode($datos_xml['impuestos_R']) : NULL );
                                                        //$datos_xml['tasacuotar'] = (isset($datos_xml) && $datos_xml['impuestos_R'] ? $datos_xml['impuestos_R'][0]['TasaOCuota'] : NULL);
                                                        //$datos_xml['importer'] = (isset($datos_xml) && $datos_xml['impuestos_R'] ? $datos_xml['impuestos_R'][0]['Importe'] : NULL );
                                                        /*****************************/
                                                        
                                                        $data["idsolicitud"] = $this->input->post("id");
                                                        
                                                        $data["observaciones"] = $datos_xml['condpago'] ? $datos_xml['condpago'][0] : 'NA';

                                                        $data["descr"] = '';
                                                        if( $datos_xml['conceptos'] ){
                                                            foreach( $datos_xml['conceptos'] as $row ){
                                                                $data['descr'] .= $row["Descripcion"];
                                                            }
                                                        }

                                                        $datos_xml['uuid'] = $datos_xml['uuid'][ count( $datos_xml['uuid'] ) - 1 ];
                                                        $data["idsolicitudr"] = null;

                                                        if( $datos_xml['tipo_factura'] == 1 ){
                                                            $this->Solicitudes_solicitante->bloquear_factura( $this->input->post("id") );
                                                        }

                                                        $this->Complemento_cxpModel->insertar_factura( $data, $datos_xml );
                                                        $this->Complemento_cxpModel->guardar_xml( $this->db->insert_id(), $datos_xml["textoxml"] );
                                                        log_sistema($this->session->userdata("inicio_sesion")['id'], $this->input->post("id"), "HA CARGADO UNA FACTURA AL SISTEMA");

                                                        if( $datos_xml['tipo_factura'] == 3 && ($informacion_solicitud->cantidad - $total) <= 1.05 ){
                                                            $this->Solicitudes_solicitante->completar_solicitud( $this->input->post("id") );
                                                        }
                                                        if( !(in_array($this->session->userdata("inicio_sesion")['rol'], array('CP', 'SO')) && $validar_xml ) && ( $datos_xml['tipo_factura'] == 1 && ($informacion_solicitud->cantidad - $total) > 1.05 ) ){
                                                            $respuesta['respuesta'] = array( FALSE, "LA CANTIDAD PAGADA ES MENOR A LA AUTORIZADA"); 
                                                            echo json_encode( $respuesta );
                                                            return false;
                                                        }

                                                        $respuesta['respuesta'] = array( TRUE , $notificacion);
                                                        $respuesta['deuda'] = $informacion_solicitud->cantidad - $total;
                                                        $respuesta['mensaje'] = array( 'RECUERDA QUE FALTA POR COMPROBAR $ '. number_format( ($informacion_solicitud->cantidad - $total), 2, ".", ","  ) );
                                                        $respuesta['folioupdate'] = $this->Solicitudes_solicitante->actualizar_folio_fiscal($this->input->post("id"), $datos_xml['folio']);
                                                    }else{
                                                        $respuesta['respuesta'] = array( FALSE, "LA FORMA DE PAGO Y/O METODO DE PAGO NO ES EL PERMITIDO. LA FORMA DE PAGO DEBE SER TRANSFERENCIA ELECTRONICA O CHEQUE Y EL METODO DE PAGO DEBE SER PUE" );
                                                        //
                                                    }
                                                }else{
                                                    $respuesta['respuesta'] = array( FALSE, "LA FACTURA ES MUY ANTIGUA SOLICITE UNA REFACTURACIÓN" );
                                                    //
                                                }
                                            }else{
                                                $respuesta['respuesta'] = array( FALSE, "LA CANTIDAD PAGADA ES MAYOR A LA AUTORIZADA");
                                                //
                                            }
                                        }else{
                                            $respuesta['respuesta'] = array( FALSE, "LA EMPRESA RECEPTORA NO COINCIDE CON LA SOLICITUD");
                                            //
                                        }
                                    }else{
                                        $respuesta['respuesta'] = array( FALSE, "EL PROVEEDOR NO COINCIDE CON EL DE LA SOLICITUD.");
                                        //
                                    }
                                }else{
                                    $respuesta['respuesta'] = array( FALSE, "NO CUENTA CON LAS CARACTERISTICAS NECESARIAS PARA SER UN COMPLEMENTO, VERIFIQUE EN LA SECCIÓN DE PAGO SE INDIQUE LA RELACION CON LA FACTURA GLOBAL.");
                                    //
                                }
                                        }else{
                                            /*
                                            $respuesta['respuesta'] = ($datos_xml["regfisemisor"] && $datos_xml["regfisrecep"])
                                             ?  array( FALSE, "EL REGIMEN FISCAL \n ".(in_array($datos_xml["regfisemisor"],[$consultaProveedor->row()->regf_emisor]) ? " ":"- EMISOR NO ES CORRECTO \n").(in_array($datos_xml["regfisrecep"],[$consultaEmpresa->row()->regf_recep]) ? " ":"- RECEPTOR NO ES CORRECTO \n  ")."SOLICITE UNA REFACTURACIÓN " )
                                             :  array( FALSE, "LA FACTURA NO CUMPLE CON \n ".($datos_xml["regfisemisor"] ? " ":"- REGIMEN FISCAL DEL EMISOR \n").($datos_xml["regfisrecep"] ? " ":"- REGIMEN FISCAL DEL RECEPTOR \n ")."SOLICITE UNA REFACTURACIÓN " );
                                            */
                                        }

                                // Termina Facturacion 4.0
                            }else{
                                $respuesta['respuesta'] = array( FALSE, "LA FACTURA CONTIENE UN TIPO INVALIDO DE RELACIÓN. VERIFIQUE.");
                                //
                            }
                        }else{
                            $respuesta['respuesta'] = array( FALSE, "ESTE DOCUMENTO NO CUENTA CON FOLIO FISCAL, COMUNICARSE CON EL PROVEEDOR PARA REALIZAR UNA REFACTURACIÓN"); 
                            //   
                        }
                    }else{
                        $factura_duplicada = $factura_duplicada->row();
                        $respuesta['respuesta'] = array( FALSE, "¡FACTURA YA EXISTENTE EN EL SISTEMA!\nSE HA CARGADO POR $factura_duplicada->nombre_completo.\nEL $factura_duplicada->feccrea.\nEN EL REGISTRO #$factura_duplicada->idsolicitud", $informacion_solicitud->cantidad);
                    }
                }else{
                    $respuesta['respuesta'] = array( FALSE, "LA VERSION DE LA FACTURA ES INFERIOR A LA 4.0, SOLICITE UNA REFACTURACIÓN");
                    //
                }

                if( !$respuesta['respuesta'][0] && file_exists( $xml_subido['full_path'] ) ){
                    unlink( $xml_subido['full_path'] );
                }

                
                if ( $respuesta['respuesta'][0] === FALSE || $this->db->trans_status() === FALSE){
                    $this->db->trans_rollback();
                }else{
                    $this->db->trans_commit();
                }

            }else{
                $respuesta['respuesta'] = array( FALSE, $this->upload->display_errors() );
            }
        }

        echo json_encode( $respuesta );
    }
    
    //PERMITE CARGAR UNA FACTURA QUE NO SE HAYA ANEXADO EN SU MOMENTO
    /*
    Esto no validad ningun caso especifico.
    Solo es para la carga a registros de caja chica.
    */
    public function cargarxml_cajachica(){
        //$validar_xml = $this->input->post( 'validarxml' );
        $respuesta = array( "respuesta" => array( FALSE, "HA OCURRIDO UN ERROR") );
        if( isset( $_FILES ) && !empty($_FILES) ){

            $config['upload_path'] = './UPLOADS/XMLS/';
            $config['allowed_types'] = 'xml';
            
            //CARGAMOS LA LIBRERIA CON LAS CONFIGURACIONES PREVIAS -----$this->upload->display_errors()
            $this->load->library('upload', $config);
            
            if( $this->upload->do_upload("xmlfile") ){

                $respuesta = array( "respuesta" => array( FALSE, "NO CUMPLE LAS CARACTERISTICAS PARA SER UNA FACTURA COMPLEMENTO Y/O VALIDA ") );

                $xml_subido = $this->upload->data();

                $datos_xml = $this->Complemento_cxpModel->leerxml( $xml_subido['full_path'] );

                $informacion_solicitud = $this->Complemento_cxpModel->getSolicitud( $this->input->post("id") )->row();
                
                if( $datos_xml['version'] >= 4.0 || (in_array($this->session->userdata("inicio_sesion")['rol'], array('CP')) )){
                    if( $this->Solicitudes_solicitante->verificar_uuid( $datos_xml['uuid'][ count( $datos_xml['uuid'] ) - 1 ] )->num_rows() === 0){
                        
                        //NO ESTA SUMANDO LOS 0 Y TRUNCA LOS DECIMALES
                        $total = (FLOAT)$this->Complemento_cxpModel->getTotalPagado( $this->input->post("id") );
                        if($total < 0){
                            $total = $datos_xml['Total'];
                        }else{
                            $total = (FLOAT)$total + (FLOAT)$datos_xml['Total'];
                        }
                        
                        //************GENERA EL NUEVO NOMBRE DEL DOCUMENTO XML SUBIDO AL SISTEMA*********/
                        // $nuevo_nombre = "CXP_";
                        // $nuevo_nombre .= str_pad((count(glob('./UPLOADS/XMLS/CXP_*')) + 1), 5, "0", STR_PAD_LEFT)."_";
                        $nuevo_nombre = date("my")."_";
                        $nuevo_nombre .= str_replace( array(",", ".", '"'), "", str_replace( array(" ", "/"), "_", limpiar_dato($datos_xml["nomemi"]) ))."_";
                        $nuevo_nombre .= date("Hms")."_";
                        $nuevo_nombre .= substr($datos_xml["uuid"][0], -5).".xml";

                        rename( $xml_subido['full_path'], "./UPLOADS/XMLS/".$nuevo_nombre );
                        //**************AQUI ES DONDE SE TERMINA EL RENOMBRAMIENTO AL ARCHIVO*****************/
                        //LINEA ORIGINAL
                        //$datos_xml['nombre_xml'] = $xml_subido['file_name'];
                        $datos_xml['nombre_xml'] = $nuevo_nombre;
                        $datos_xml['tipo_factura'] = $datos_xml['MetodoPago'][0] != 'PPD' ? 3 : 1;

                        //TODO LO RELACIONADO CON LOS IMPUESTOS
                        $datos_xml['subtotal'] = (isset($datos_xml) ? $datos_xml['SubTotal'][0] : NULL );
                        $datos_xml['descuento'] = (isset($datos_xml) && $datos_xml['Descuento'] ? $datos_xml['Descuento'][0] : NULL );
                        $datos_xml['impuestot'] = (isset($datos_xml) && $datos_xml['impuestos_T'] ? json_encode($datos_xml['impuestos_T']) :  NULL );
                        //$datos_xml['tasacuotat'] = (isset($datos_xml) && $datos_xml['impuestos_T'] ? $datos_xml['impuestos_T'][0]['TasaOCuota'] : NULL );
                        //$datos_xml['importet'] = (isset($datos_xml) && $datos_xml['impuestos_T'] ? $datos_xml['impuestos_T'][0]['Importe'] : NULL );
                        $datos_xml['impuestor'] = (isset($datos_xml) && $datos_xml['impuestos_R'] ? json_encode($datos_xml['impuestos_R']) : NULL );
                        //$datos_xml['tasacuotar'] = (isset($datos_xml) && $datos_xml['impuestos_R'] ? $datos_xml['impuestos_R'][0]['TasaOCuota'] : NULL);
                        //$datos_xml['importer'] = (isset($datos_xml) && $datos_xml['impuestos_R'] ? $datos_xml['impuestos_R'][0]['Importe'] : NULL );
                        /*****************************/

                        $data["idsolicitud"] = $this->input->post("id");

                        $data["observaciones"] = $datos_xml['condpago'] ? $datos_xml['condpago'][0] : 'NA';

                        $data["descr"] = '';
                        if( $datos_xml['conceptos'] ){
                            foreach( $datos_xml['conceptos'] as $row ){
                                $data['descr'] .= $row["Descripcion"];
                            }
                        }

                        $datos_xml['uuid'] = $datos_xml['uuid'][ count( $datos_xml['uuid'] ) - 1 ];
                        $data["idsolicitudr"] = null;

                        if( $datos_xml['tipo_factura'] == 1 ){
                            $this->Solicitudes_solicitante->bloquear_factura( $this->input->post("id") );
                        }

                        $this->Complemento_cxpModel->insertar_factura( $data, $datos_xml );
                        $this->Complemento_cxpModel->guardar_xml( $this->db->insert_id(), $datos_xml["textoxml"] );
                        log_sistema($this->session->userdata("inicio_sesion")['id'], $this->input->post("id"), "HA CARGADO UNA FACTURA AL SISTEMA");

                        $respuesta['respuesta'] = array( TRUE );
                        $respuesta['deuda'] = $informacion_solicitud->cantidad - $total;
                        $respuesta['mensaje'] = array( 'RECUERDA QUE FALTA POR COMPROBAR $ '. number_format( ($informacion_solicitud->cantidad - $total), 2, ".", ","  ) );
                        $respuesta['folioupdate'] = $this->Solicitudes_solicitante->actualizar_folio_fiscal($this->input->post("id"), $datos_xml['folio']);
                    }else{
                        $respuesta['respuesta'] = array( FALSE, "FACTURA YA EXISTENTE EN EL SISTEMA", $informacion_solicitud->cantidad);
                    }
                }else{
                    $respuesta['respuesta'] = array( FALSE, "LA VERSION DE LA FACTURA ES INFERIOR A LA 4.0, SOLICITE UNA REFACTURACIÓN");
                }
            }else{
                $respuesta['respuesta'] = array( FALSE, $this->upload->display_errors() );
            }
        }
        echo json_encode( $respuesta );
    }

    //PARA AGREGAR NOTAS DE CREDITO A LAS SOLICITUDES
    public function notas_credito(){
        $respuesta = array( "respuesta" => array( FALSE, "HA OCURRIDO UN ERROR") );
        if( isset( $_FILES ) && !empty($_FILES) ){

            $config['upload_path'] = './UPLOADS/XMLS/';
            $config['allowed_types'] = 'xml';
            
            //CARGAMOS LA LIBRERIA CON LAS CONFIGURACIONES PREVIAS -----$this->upload->display_errors()
            $this->load->library('upload', $config);
            
            if( $this->upload->do_upload("xmlfile") ){

                $respuesta = array( "respuesta" => array( FALSE, "NO CUMPLE LAS CARACTERISTICAS PARA SER UNA FACTURA COMPLEMENTO Y/O VALIDA ") );

                $xml_subido = $this->upload->data();

                $datos_xml = $this->Complemento_cxpModel->leerxml( $xml_subido['full_path'] );

                $informacion_solicitud = $this->Complemento_cxpModel->getSolicitud( $this->input->post("id") )->row();
                $informacion_solicitud->cantidad = $informacion_solicitud->cantidad * $this->input->post("tpocam");

                if( $datos_xml['TipoRelacion'] && in_array( $datos_xml['TipoRelacion'][0], array( "01", "03", "07" ) ) ){
                    if( $datos_xml['uuidR'] && count( $datos_xml['uuidR'] ) == 1 ){
                        if($this->Complemento_cxpModel->verificar_uuid( $datos_xml['uuid'][0] )->num_rows() === 0){
                            if( $this->Complemento_cxpModel->facturas_relacionadas( "'".$datos_xml['uuidR'][0]['UUID']."'"  )->num_rows() > 0 ){
                                if( ( $informacion_solicitud->uuid && $datos_xml['uuidR'] && strtoupper($informacion_solicitud->uuid) == strtoupper($datos_xml['uuidR'][0]['UUID']) ) || ( $informacion_solicitud->uuid && $datos_xml['uuidR'] && in_array( strtoupper($informacion_solicitud->uuid), $datos_xml['uuidR'] ) ) ){
                                    $respuesta['respuesta'] = array( TRUE );
                                    
                                    //************GENERA EL NUEVO NOMBRE DEL DOCUMENTO XML SUBIDO AL SISTEMA*********/
                                    // $nuevo_nombre = "CXP_";
                                    // $nuevo_nombre .= str_pad((count(glob('./UPLOADS/XMLS/CXP_*')) + 1), 5, "0", STR_PAD_LEFT)."_";
                                    $nuevo_nombre = date("my")."_";
                                    $nuevo_nombre .= str_replace( array(",", ".", '"'), "", str_replace( array(" ", "/"), "_", limpiar_dato($datos_xml["nomemi"]) ))."_";
                                    $nuevo_nombre .= date("Hms")."_";
                                    $nuevo_nombre .= substr( ( $datos_xml['uuid'][0] ), -5).".xml";

                                    rename( $xml_subido['full_path'], "./UPLOADS/XMLS/".$nuevo_nombre );
                                    //**************AQUI ES DONDE SE TERMINA EL RENOMBRAMIENTO AL ARCHIVO*****************/
                                    //LINEA ORIGINAL
                                    //$datos_xml['nombre_xml'] = $xml_subido['file_name'];
                                    $datos_xml['nombre_xml'] = $nuevo_nombre;
                                    
                                    //APLICAMOS UNA NOTA DE CREDITO
                                    $this->Complemento_cxpModel->aplicar_nota_credito( $this->input->post("id"), $datos_xml['Total'][0] );
                                    //GUARDADO DE MOVIMIENTO EN HISTORIAL
                                    log_sistema($this->session->userdata("inicio_sesion")['id'], $this->input->post("id"), "SE HA APLICADO UNA NOTA DE CREDITO POR $ ".number_format( (FLOAT)$datos_xml['Total'][0], 2, ".", "," ));
                                    
                                    $data["idsolicitud"] = $this->input->post("id");
                                    $datos_xml['uuid'] = strtoupper( $datos_xml['uuid'][0] );
                                    $datos_xml['tipo_factura'] = 4;
                                    $data["idsolicitudr"] = null;     
                                    $data["observaciones"] = $datos_xml['condpago'] ? $datos_xml['condpago'][0] : 'NA';
                                    $data["descr"] = '';

                                    //GUARDAMOS LA FACTURA EN LA TABLA DE FACTURAS
                                    $this->Complemento_cxpModel->insertar_factura( $data, $datos_xml );
                                    $this->Complemento_cxpModel->guardar_xml( $this->db->insert_id(), $datos_xml["textoxml"] );

                                    //CANTIDAD APLICADA POR LA NOTA DE CREDITO
                                    $respuesta['monto_aplicado'] = (FLOAT)$datos_xml['Total'][0];
                                    
                                }else{
                                    $respuesta['respuesta'] = array( FALSE, "FACTURA NO CORRESPONDE A ESTA SOLICITUD. VERIFIQUE EL FOLIO FISCAL DE LA FACTURA ORIGINAL CON LA RELACIONADA EN EL COMPLEMENTO.");
                                }
                            }else{
                                $respuesta['respuesta'] = array( FALSE, "NO HAY FACTURA RELACIONADA. VERIFIQUE EL FOLIO FISCAL DE LA FACTURA ORIGINAL CON LA RELACIONADA: ".$datos_xml['uuidR'][0]['UUID']);
                            }
                        }else{
                            $respuesta['respuesta'] = array( FALSE, "FOLIO FISCAL YA EXISTENTE");    
                        }
                    }else{
                        $respuesta['respuesta'] = array( FALSE, "NO CONTIENE INDICADA UNA RELACION VALIDA. VERIFIQUÉ QUE SOLO SEA 1 FOLIO FISCAL O QUE SEA CORRECTO EL TIPO DE RELACION ( 01, 03, 07 )");
                    }
                }else{
                    $respuesta['respuesta'] = array( FALSE, "TIPO DE RELACION INVALIDO PARA SER UNA NOTA DE CREDITO, VERIFIQUE EL TIPO DE RELACIÓN. VERIFIQUÉ ");
                }
                if( !$respuesta['respuesta'][0] ){
                    unlink( $xml_subido['full_path'] );
                }

            }else{
                $respuesta['respuesta'] = array( FALSE, $this->upload->display_errors() );
            }
        }
        
        echo json_encode( $respuesta );
    }

    public function cargaxml_pagos(){
        
        $respuesta = array( "respuesta" => array( FALSE, "HA OCURRIDO UN ERROR") );
        if( isset( $_FILES ) && !empty($_FILES) ){

            $config['upload_path'] = './UPLOADS/XMLS/';
            $config['allowed_types'] = 'xml';
            
            //CARGAMOS LA LIBRERIA CON LAS CONFIGURACIONES PREVIAS -----$this->upload->display_errors()
            $this->load->library('upload', $config);

            if( $this->upload->do_upload("xmlfile") ){

                $xml_subido = $this->upload->data();

                $datos_xml = $this->Complemento_cxpModel->leerxml( $xml_subido['full_path'] );
                $informacion_solicitud = $this->Complemento_cxpModel->getPagosSolicitud( $this->input->post("id") )->row();

               if( $datos_xml['TipoRelacion'] == 8){
                    
                    if($this->Complemento_cxpModel->verificar_uuid( $datos_xml['uuid'] )->num_rows() === 0){
                      
                      if( $this->Complemento_cxpModel->verificar_uuid( $datos_xml['uuidR'] )->num_rows() > 0 ){
                        
                        
                        if($informacion_solicitud->uuid == $datos_xml['uuidR'] ){
                            $respuesta['respuesta'] = array( TRUE );
                            $datos_xml['nombre_xml'] = $xml_subido['file_name'];
                                        $datos_xml['tipo_factura'] = 2;
                                        
                                        $data["idsolicitud"] = $this->input->post("id");
                                        
                                        $data["observaciones"] = $datos_xml['condpago'] ? $datos_xml['condpago'][0] : 'NA';

                                        $data["descr"] = '';
                                        if( $datos_xml['conceptos'] ){
                                            foreach( $datos_xml['conceptos'] as $row ){
                                                $data['descr'] .= $row["Descripcion"];
                                            }
                                        }
                            $this->Complemento_cxpModel->insertar_factura( $data, $datos_xml );
                            $this->Complemento_cxpModel->insert_factura_pago( $this->input->post("id") );
                            
                        }else{
                            
                            $respuesta['respuesta'] = array( FALSE, " FACTURA NO CORRESPONDE A ESTA SOLICITUD ");
                       }

                         }else{
                            
                            $respuesta['respuesta'] = array( FALSE, " NO HAY FACTURA RELACIONADA ");
                       }
                    }else{
                        $respuesta['respuesta'] = array( FALSE, "FOLIO FISCAL YA EXISTENTE ");
                         
                    }
                }else{
                    $respuesta['respuesta'] = array( FALSE, "LA VERSIÓN DEL COMPLEMENTO ES ERRONEA ");
                }
                
                if( !$respuesta['respuesta'][0] ){
                    unlink( $xml_subido['full_path'] );
                }

            }else{
                $respuesta['respuesta'] = array( FALSE, $this->upload->display_errors() );
            }
            
        }
        
        echo json_encode( $respuesta );
    
    
       } 

    public function reporte_factcomp(){

        $hoy = getdate();
        //FECHAS PARA EL RANGO
        if($this->input->post("finicio") != "" && $this->input->post("finicio") != "ffin"){
            $fini = implode("-", array_reverse(explode("/", $this->input->post("finicio"))));
            $ffin = implode("-", array_reverse(explode("/", $this->input->post("ffin"))));
            $where_fechas = "AND fecelabR BETWEEN '$fini' AND '$ffin' ";
        }else{
            $where_fechas = "";
        }
        
        
        //DATOS DE LA CABECERA DE LA TABLA
        $idsolicitud = $this->input->post("#") ? $this->input->post("#") : '';
        $proveedor = $this->input->post("PROVEEDOR") ? $this->input->post("PROVEEDOR") : '';
        $depto = $this->input->post("DEPARTAMENTO") ? $this->input->post("DEPARTAMENTO") : '';
        $capturista = $this->input->post("CAPTURISTA") ? $this->input->post("CAPTURISTA") : '';
        $forma_pago = $this->input->post("FORMA_PAGO") ? $this->input->post("FORMA_PAGO") : '';
        $empresa = $this->input->post("EMPRESA") ? $this->input->post("EMPRESA") : '';

        $cad = "SELECT idsolicitud, nproveedor, nomdepto, nombre_capturista, metoPago, abrev, folio, IF(nfacturas > 1, 'NA', uuid_c) AS uuid, IF(nfacturas > 1, 'NA', uuid) AS foliofisc, cantidad, ccomp, fecelab, fecha_autorizacion, fechadisp, nfacturas, justificacion FROM solicitudes_comprobadas WHERE idsolicitud LIKE '%$idsolicitud%' AND nproveedor LIKE '%$proveedor%' AND nomdepto LIKE '%$depto%' AND nombre_capturista LIKE '%$capturista%' 
         AND metoPago LIKE '%$forma_pago%' AND abrev LIKE '%$empresa%'" . $where_fechas;

        $query = $this->db->query("$cad");

        ini_set('memory_limit','-1');
        set_time_limit (0);

        /**CON ESTO SE CARGARAN LOS ARCHIVOS DE EXCEL**/
        /*
        $reader = new PhpOffice\PhpSpreadsheet\Spreadsheet();
        $encabezados = [
            'font' => [
                'color' => [ 'argb' => '00000000' ],
                'bold'  => true,
                'size'  => 12,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            ],
                            'borders' => [
                    'top' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                ],
                'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                        'rotation' => 0,
                        'startColor' => [
                            'rgb' => '0000FF'
                        ],
                        'endColor' => [
                            'argb' => '0000FF'
                        ],
                    ],
            ];

            $informacion = [
                'borders' => [
                    'top' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                    'bottom' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                    'left' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                    'right' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                ]
            ];

            $informacion1 = [
                'font' => [
                    'color' => [ 'argb' => '00000000' ],
                    'bold'  => true,
                    'size'  => 12,
                ],
                'borders' => [
                    'top' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                    'bottom' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                    'left' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                    'right' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                ]
            ];

            $style = array(
                'alignment' => array(
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                )
            );

            $reader->getActiveSheet()->getColumnDimension('A')->setWidth(15);
            $reader->getActiveSheet()->getColumnDimension('B')->setWidth(50);
            $reader->getActiveSheet()->getColumnDimension('C')->setWidth(30);
            $reader->getActiveSheet()->getColumnDimension('D')->setWidth(30);
            $reader->getActiveSheet()->getColumnDimension('E')->setWidth(15);
            $reader->getActiveSheet()->getColumnDimension('F')->setWidth(10);
            $reader->getActiveSheet()->getColumnDimension('G')->setWidth(10);
            $reader->getActiveSheet()->getColumnDimension('H')->setWidth(15);
            $reader->getActiveSheet()->getColumnDimension('I')->setWidth(25);
            $reader->getActiveSheet()->getColumnDimension('J')->setWidth(20);
            $reader->getActiveSheet()->getColumnDimension('K')->setWidth(25);
            $reader->getActiveSheet()->getColumnDimension('L')->setWidth(20);
            $reader->getActiveSheet()->getColumnDimension('M')->setWidth(15);
            $reader->getActiveSheet()->getColumnDimension('N')->setWidth(80);

            $i = 1;
                
                $reader->getActiveSheet()->setCellValue('A'.$i, '# SOLICITUD');
                $reader->getActiveSheet()->setCellValue('B'.$i, 'PROVEEDOR');
                $reader->getActiveSheet()->setCellValue('C'.$i, 'DEPARTAMENTO');
                $reader->getActiveSheet()->setCellValue('D'.$i, 'CAPTURISTA');
                $reader->getActiveSheet()->setCellValue('E'.$i, 'FORMA PAGO');
                $reader->getActiveSheet()->setCellValue('F'.$i, 'EMPRESA');
                $reader->getActiveSheet()->setCellValue('G'.$i, 'FOLIO');
                $reader->getActiveSheet()->setCellValue('H'.$i, 'CANTIDAD');
                $reader->getActiveSheet()->setCellValue('I'.$i, 'CANTIDAD COMPROBADA');
                $reader->getActiveSheet()->setCellValue('J'.$i, 'FECHA FACTURA');
                $reader->getActiveSheet()->setCellValue('K'.$i, 'FECHA AUTORIZACIÓN');
                $reader->getActiveSheet()->setCellValue('L'.$i, 'FECHA DISPERSIÓN');
                $reader->getActiveSheet()->setCellValue('M'.$i, '# FACTURAS');
                $reader->getActiveSheet()->setCellValue('N'.$i, 'JUSTIFICACIÓN');


                $reader->getActiveSheet()->getStyle('A1:N1')->applyFromArray($encabezados);

                $i+=1;

                if( $query->num_rows() > 0  ){

                    foreach( $query->result() as $row ){
                        $reader->getActiveSheet()->setCellValue('A'.$i, $row->idsolicitud);
                        $reader->getActiveSheet()->setCellValue('B'.$i, $row->nproveedor);
                        $reader->getActiveSheet()->setCellValue('C'.$i, $row->nomdepto);
                        $reader->getActiveSheet()->setCellValue('D'.$i, $row->nombre_capturista);
                        $reader->getActiveSheet()->setCellValue('E'.$i, $row->metoPago);
                        $reader->getActiveSheet()->setCellValue('F'.$i, $row->abrev);
                        $reader->getActiveSheet()->setCellValue('G'.$i, $row->folio);
                        $reader->getActiveSheet()->setCellValue('H'.$i, number_format($row->cantidad, 2, '.', '') ); 
                        $reader->getActiveSheet()->setCellValue('I'.$i, number_format($row->ccomp, 2, '.', '') );
                        $reader->getActiveSheet()->setCellValue('J'.$i, $row->fecelab );
                        $reader->getActiveSheet()->setCellValue('K'.$i, $row->fecha_autorizacion );
                        $reader->getActiveSheet()->setCellValue('L'.$i, $row->fechadisp );
                        $reader->getActiveSheet()->setCellValue('M'.$i, $row->nfacturas );
                        $reader->getActiveSheet()->setCellValue('N'.$i, $row->justificacion);


                        //$reader->getActiveSheet()->getStyle("A$i:S$i")->applyFromArray($informacion1);
                        $i+=1;
                    }
                }

                $writer =  \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($reader, 'Xls');
                ob_end_clean();

                $temp_file = tempnam(sys_get_temp_dir(), 'PHPExcel');
                $writer->save($temp_file);
                header("Content-Disposition: attachment; filename=REPORTE_FACT_COMP".date("d/m/Y")."_.xls");

                readfile($temp_file); 
                unlink($temp_file);
        */ //antigua libreria
        
        $writer = WriterEntityFactory::createXLSXWriter();
        $fileName = "REPORTE_SOLICITUDES_COMPROBADAS_".$hoy['mday']."_".$hoy['mon']."_".$hoy['year']."_.xlsx";
        $writer->openToBrowser($fileName); // write data to a file or to a PHP stream

        $style = (new StyleBuilder())
        ->setFontBold()
        ->setFontSize(12)
        ->build();

        $encab = [
            '# SOLICITUD',
            'PROVEEDOR',
            'DEPARTAMENTO',
            'CAPTURISTA',
            'FORMA DE PAGO',
            'EMPRESA',
            'FOLIO',
            'FOLIO FISCAL',
            'CANTIDAD',
            'CANTIDAD COMPROBADA',
            'FECHA FACTURA',
            'FECHA AUTORIZACIÓN',
            'FECHA DISPERSIÓN',
            '# FACTURAS',
            'JUSTIFICACIÓN',
            'UUID'
        ];
        /** add a row at a time */
        $singleRow = WriterEntityFactory::createRowFromArray($encab, $style);
        $writer->addRow($singleRow);

        if( $query->num_rows() > 0){
            foreach( $query->result() as $row){
                $cells = [
                    $row->idsolicitud,
                    $row->nproveedor,
                    $row->nomdepto,
                    $row->nombre_capturista,
                    $row->metoPago,
                    $row->abrev,
                    $row->folio,
                    $row->foliofisc,
                    $row->cantidad,
                    $row->ccomp,
                    $row->fecelab,
                    $row->fecha_autorizacion,
                    $row->fechadisp,
                    $row->nfacturas,
                    $row->justificacion,
                    $row->uuid
                ];
                $singleRow = WriterEntityFactory::createRowFromArray($cells);
                $writer->addRow($singleRow);
            }
        }

        $writer->close();
    }
}