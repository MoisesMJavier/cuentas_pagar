<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*Importador Pagos*/

class I extends CI_Controller {
    
    public function array_json(){
        $conceptos = [];
        
        //$conceptos = "{".implode( ",", $this->db->query("SELECT CONCAT( \"'\",claveProdServ,\"': '\",Descripcion,\"'\" ) FROM productos")->result_array() )."}";
        //$conceptos = json_decode($conceptos);

        //echo preg_replace('/[\x00-\x1F\x80-\xFF]/', "", "{".implode( ",", array_column($this->db->query("SELECT CONCAT( '\"', claveProdServ,'\" : \"',Descripcion,'\"' ) catalogo FROM productos")->result_array(), "catalogo"))."}");
        var_dump( $conceptos );

        switch(json_last_error()) {
            case JSON_ERROR_NONE:
                echo ' - Sin errores';
            break;
            case JSON_ERROR_DEPTH:
                echo ' - Excedido tamaño máximo de la pila';
            break;
            case JSON_ERROR_STATE_MISMATCH:
                echo ' - Desbordamiento de buffer o los modos no coinciden';
            break;
            case JSON_ERROR_CTRL_CHAR:
                echo ' - Encontrado carácter de control no esperado';
            break;
            case JSON_ERROR_SYNTAX:
                echo ' - Error de sintaxis, JSON mal formado';
            break;
            case JSON_ERROR_UTF8:
                echo ' - Caracteres UTF-8 malformados, posiblemente codificados de forma incorrecta';
            break;
            default:
                echo ' - Error desconocido';
            break;
        }
    
        echo PHP_EOL;
    }

    function descargar_facturas(){

        ini_set('max_execution_time', '0');

        $query = $this->db->query("SELECT * FROM facturas WHERE regf_recep = 0 and ver = 4.0 ORDER BY idfactura DESC");

        
        foreach( $query->result() as $row ){
            $url = "https://cuentas.gphsis.com/UPLOADS/XMLS/".$row->nombre_archivo;
          
            $file_name = "UPLOADS/XMLS/".$row->nombre_archivo;
            
            if( !file_exists( $file_name ) )
                if (!file_put_contents($file_name, file_get_contents($url)))
                {
                    echo "File downloading failed. ".$url;
                }
        }
        
    }

    function actualizar_facturas(){
        set_time_limit(0);

        $query = $this->db->query("SELECT * FROM facturas WHERE regf_recep = 0 and ver = 4.0 ORDER BY idfactura DESC");
        //$query = $this->db->query("SELECT * FROM facturas WHERE tipo_factura IN ( 1, 3 ) AND tasacuotat != 0 AND feccrea between '2021-01-01 00:00:00' and '2021-12-31 23:59:59'");
        $this->load->model("Complemento_cxpModel");

        foreach( $query->result() as $row ){

            if( file_exists( "UPLOADS/XMLS/".$row->nombre_archivo ) ){
                $datos_xml = $this->Complemento_cxpModel->leerxml( "UPLOADS/XMLS/".$row->nombre_archivo );

                if( $datos_xml !== FALSE )
                    $this->db->update("facturas", [
                        "ISRtras" => $datos_xml['ISRtras'],
                        "IEPStras" => $datos_xml['IEPStras'],
                        "tasatras16" => $datos_xml['tasatras16'],
                        "tasatras8" => $datos_xml['tasatras8'],
                        "tasatras0" => $datos_xml['tasatras0'],
                        "tasatrasExp" => $datos_xml['tasatrasExp'],
                        "rettras" => $datos_xml["rettras"],
                        "regf_emisor" => $datos_xml["regfisemisor"],
                        "regf_recep" => $datos_xml["regfisrecep"],
                        "domf_recep" => $datos_xml["cpfisrecep"],
                        "lugarexp" => $datos_xml["LugarExpedicion"]
                    ], "idfactura = ".$row->idfactura);
            }
        }
    }

    function index(){
         $this->load->view("v_importador");
    }

    function renombrar_complementos(){
        $this->load->model("Solicitudes_solicitante");
        $dirs = array_filter(glob('./UPLOADS/RENOMBRAR/*'), 'is_dir');

        foreach( $dirs as $row ){
            $subdirectorio = array_filter(glob( $row."/Recibidas/2019/*" ), 'is_dir');
            foreach( $subdirectorio as $row_sub ){
                $xmls = glob( $row_sub."/*.xml" );

                foreach( $xmls as $xml ){

                    $datos_xml = $this->Solicitudes_solicitante->leerxml( $xml );

                    $nuevo_nombre = date("my", strtotime( $datos_xml['fecha'] ) )."_";
                    $nuevo_nombre .= str_replace( array(",", ".", '"'), "", str_replace(array(" ", "/"), "_", limpiar_dato($datos_xml["nomemi"]) ))."_";
                    $nuevo_nombre .= date("Hms");

                    if( $datos_xml["iddocumento"] ){
                        foreach( $datos_xml["iddocumento"] as $uuid ){
                            $nuevo_nombre .= "_".substr($uuid, -5);
                        }
                    }
                    
                    $nuevo_nombre .= ".xml";

                    rename( $xml, $row_sub."/".$nuevo_nombre );
                }
            }
        }

    }

    public function forma_ruda_rescate(){
        $xmls_disponibles = glob( "./UPLOADS/PARACHAMBEAR/*.xml" );

        for( $i = 0; $i < count( $xmls_disponibles ); $i++ ){

            rename( $xmls_disponibles[$i], "./UPLOADS/PARACHAMBEAR/documento_temporal.txt" );
            $str = file_get_contents( "./UPLOADS/PARACHAMBEAR/documento_temporal.txt" );

            $resultado = explode( "                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     ", $str );

            for( $c = 0; $i < count( $resultado ); $c++ ){
                $nombre = $i.$c;

                $myfile = fopen( "./UPLOADS/PARACHAMBEAR/".$nombre.".txt", "w");
                fwrite( $myfile, $resultado[$c] );
                fclose( $myfile );
            }
        }
    }

    public function forma_ruda(){
        $this->load->model("Solicitudes_solicitante");
        $xmls_disponibles = glob( "./UPLOADS/PARACHAMBEAR/*.xml" );

        for( $i = 0; $i < count( $xmls_disponibles ); $i++ ){

            $datos_xml = $this->Solicitudes_solicitante->leerxml( $xmls_disponibles[$i] );

            $query = $this->db->query("SELECT * FROM facturas WHERE facturas.uuid = '".( $datos_xml['uuidR'] ? $datos_xml['uuidR'][0] : $datos_xml['uuid'] )."'");

            if( $query->num_rows() > 0 ){
                rename( $xmls_disponibles[$i], "./UPLOADS/PARACHAMBEAR/".$query->row()->nombre_archivo );
            }else{
                unlink( $xmls_disponibles[$i] );
            }
        }
    }

    public function renombra(){

        $xmlbuscar = $this->db->query("SELECT empresas.nombre AS empresas, empresas.rfc, facturas.uuid, facturas.nombre_archivo, proveedores.rfc AS rfc_prov FROM solpagos INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor inner JOIN empresas ON empresas.idempresa = solpagos.idEmpresa INNER JOIN facturas ON facturas.idsolicitud = solpagos.idsolicitud");

        $resultado = "<table>";

        foreach( $xmlbuscar->result() as $row ){
            if( !file_exists( "./UPLOADS/XMLS/".$row->nombre_archivo ) ){
                $resultado .= "<tr><td>".$row->empresas."</td><td>".$row->rfc."</td><td>".$row->rfc_prov."</td><td>".$row->uuid."</td><td>".$row->nombre_archivo."</td></tr>";
            }
        }
        echo $resultado."</table>";
    }

    public function import(){
        if(isset($_FILES['documento_xls']) && $_FILES['documento_xls']['tmp_name']){

            /**CON ESTO SE CARGARAN LOS ARCHIVOS DE EXCEL**/
            $reader = PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($_FILES['documento_xls']['tmp_name']);
            if( $spreadsheet && $spreadsheet->getActiveSheet()->getHighestRow() >= 2 ){
                
                $data = array();

                //$this->db->trans_start();
                for($i = 2; $i <= $spreadsheet->getActiveSheet()->getHighestRow(); $i++){
                    
                    if( $spreadsheet->getActiveSheet()->getCell('C'.$i)->getValue() != "" && $spreadsheet->getActiveSheet()->getCell('D'.$i)->getValue() != "" ){

                        /*
                        $data[] = array(
                            "proyecto" => $spreadsheet->getActiveSheet()->getCell('A'.$i),
                            "folio" => $spreadsheet->getActiveSheet()->getCell('B'.$i),
                            "idEmpresa" => $spreadsheet->getActiveSheet()->getCell('C'.$i),
                            "idResponsable" => $spreadsheet->getActiveSheet()->getCell('D'.$i),
                            "idProveedor" => $spreadsheet->getActiveSheet()->getCell('E'.$i),
                            "idusuario" => $spreadsheet->getActiveSheet()->getCell('F'.$i),
                            "nomdepto" => $spreadsheet->getActiveSheet()->getCell('G'.$i),
                            "caja_chica" => $spreadsheet->getActiveSheet()->getCell('H'.$i),
                            "justificacion" => $spreadsheet->getActiveSheet()->getCell('I'.$i),
                            "cantidad" => $spreadsheet->getActiveSheet()->getCell('J'.$i),
                            "moneda" => $spreadsheet->getActiveSheet()->getCell('K'.$i),
                            "metoPago" => $spreadsheet->getActiveSheet()->getCell('L'.$i),
                            "fecelab" => $spreadsheet->getActiveSheet()->getCell('M'.$i),
                            "idetapa" => $spreadsheet->getActiveSheet()->getCell('N'.$i),
                            "caja_chica" => NULL,
                            "fechaCreacion" => "0000-00-00 00:00:00"
                        );
                        */
                        //$this->db->insert('solpagos', $data);
                        $data = array(
                            "proyecto" => $spreadsheet->getActiveSheet()->getCell('A'.$i)->getValue(),
                            "folio" => $spreadsheet->getActiveSheet()->getCell('B'.$i)->getValue(),
                            "idEmpresa" => $spreadsheet->getActiveSheet()->getCell('C'.$i)->getValue(),
                            "idResponsable" => 77,
                            "idProveedor" => $spreadsheet->getActiveSheet()->getCell('D'.$i)->getValue(),
                            "idusuario" => 107,
                            "nomdepto" => "ADMINSTRACION",
                            "caja_chica" => NULL,
                            "justificacion" => $spreadsheet->getActiveSheet()->getCell('E'.$i)->getValue(),
                            "cantidad" => $spreadsheet->getActiveSheet()->getCell('F'.$i)->getValue(),
                            "moneda" => "MXN",
                            "metoPago" => $spreadsheet->getActiveSheet()->getCell('H'.$i)->getValue(),
                            "fecelab" => $spreadsheet->getActiveSheet()->getCell('I'.$i)->getValue(),
                            "fecha_fin" => $spreadsheet->getActiveSheet()->getCell('J'.$i)->getValue() != '' ? $spreadsheet->getActiveSheet()->getCell('J'.$i)->getValue() : NULL,
                            "programado" => $spreadsheet->getActiveSheet()->getCell('K'.$i)->getValue(),
                            "idetapa" => 5,
                            "fechaCreacion" => "0000-00-00 00:00:00"
                        );

                        $this->db->insert('solpagos', $data);

                        $fecha_pago = $spreadsheet->getActiveSheet()->getCell('I'.$i)->getValue();
                        $idsolicitud = $this->db->insert_id();
                        $data = array();
                        
                        while( strtotime($fecha_pago) <= strtotime(date("Y-m-d")) ){
                            
                            $data[] = array(
                                "idsolicitud" => $idsolicitud,
                                "cantidad" => $spreadsheet->getActiveSheet()->getCell('F'.$i),
                                "referencia" => "IMPORTACION",
                                "idrealiza" => 0,
                                "estatus" => 16
                            );
                            
                            if( $spreadsheet->getActiveSheet()->getCell('K'.$i)->getValue() < 7 )
                                $fecha_pago = date('Y-m-d',strtotime ( "+".$spreadsheet->getActiveSheet()->getCell('K'.$i)->getValue()." month" , strtotime ( $fecha_pago ) ) );
                            else
                                $fecha_pago = date('Y-m-d', strtotime ( "+1 week" , strtotime ( $fecha_pago ) ) );
                            
                            echo $fecha_pago."<br/>";
                        }

                        if( count( $data ) > 0 ){
                            $this->db->insert_batch( 'autpagos', $data );
                        }
                        
                        
                    }
                }
                //
                //$this->db->trans_complete();
            }else{
                echo "algo paso";
            }
        }
    }

    public function import_complemento(){
        if(isset($_FILES['file']) && $_FILES['file']['tmp_name']){

            /**CON ESTO SE CARGARAN LOS ARCHIVOS DE EXCEL**/
            $reader = PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($_FILES['file']['tmp_name']);

            if( $spreadsheet->getActiveSheet()->getHighestRow() >= 2 ){
                
                $this->db->truncate("complemento_69b");
                $this->db->trans_start();
                
                for($i = 2; $i <= $spreadsheet->getActiveSheet()->getHighestRow(); $i++){
                    if( !empty($spreadsheet->getActiveSheet()->getCell('A'.$i)) && !empty($spreadsheet->getActiveSheet()->getCell('B'.$i)) ){
        
                        $data[] = array(
                            "rfccomplemento" => $spreadsheet->getActiveSheet()->getCell('A'.$i),
                            "razonsocial" => $spreadsheet->getActiveSheet()->getCell('B'.$i),
                        );

                        
                    }
                }
                $this->db->insert_batch('complemento_69b', $data);
                $this->db->trans_complete();
            }
        }
    }
    
    public function correcto(){
        $data = array();
        $fecha_pago = "2019-07-02";
        while( strtotime($fecha_pago) <= strtotime(date("Y-m-d")) ){
                            
            $data[] = array(
                "idsolicitud" => 67531,
                "cantidad" => 30277.71,
                "referencia" => "IMPORTACION",
                "idrealiza" => 0,
                "estatus" => 16
            );
            
            if( 1 < 7 )
                $fecha_pago = date('Y-m-d',strtotime ( "+1 month" , strtotime ( $fecha_pago ) ) );
            else
                $fecha_pago = date('Y-m-d', strtotime ( "+1 week" , strtotime ( $fecha_pago ) ) );
            
            echo $fecha_pago."<br/>";
        }

        $this->db->insert_batch( 'autpagos', $data );
    }

    public function liberar_complemento_faltantes(){
        $this->load->model(array('Complemento_cxpModel' ));  
        set_time_limit ( 0 );
        $complementos = $this->db->query( "SELECT * FROM facturas WHERE facturas.idsolicitud IN ( SELECT facturas.idsolicitud FROM facturas where facturas.tipo_factura = 1 ) AND tipo_factura = 3 AND idsolicitudr IS NULL" );

        foreach( $complementos->result() as $row ){
            if( file_exists( "./UPLOADS/XMLS/".$row->nombre_archivo ) ){
                $datos_xml = $this->Complemento_cxpModel->leerxml( "./UPLOADS/XMLS/".$row->nombre_archivo );
                $uuids = array();
                if( $datos_xml['uuidR2'] ){
                    for( $i = 0; $i < count(  $datos_xml['uuidR2'] ) - 1; $i++ ){
                        if( empty($datos_xml['ImpSaldoInsoluto']) || ($datos_xml['ImpSaldoInsoluto'][$i] == 0 || $datos_xml['ImpSaldoInsoluto'][$i] == "0") ){
                            $uuids[] = $datos_xml['uuidR2'][$i];
                        }
                    }
                    $idsolicitudr = $this->Complemento_cxpModel->completar_solicitud_complemento( "'".implode ( "', '", $uuids )."'" )->idsolicituds;

                    $this->db->update("facturas", array("idsolicitudr" => $idsolicitudr ), "  idfactura ='".$row->idfactura."'");

                } 
            }
        }
         
    }

    public function registrar_descuento(){
        $this->load->model(array('Complemento_cxpModel' ));
        set_time_limit ( 0 );
        $query = $this->db->query("SELECT facturas.idsolicitud, facturas.nombre_archivo, facturas.tipo_factura FROM facturas GROUP BY facturas.idsolicitud HAVING facturas.tipo_factura = 1");
        
        foreach( $query->result() as $row ){
            if( file_exists( "./UPLOADS/XMLS/".$row->nombre_archivo ) ){
                $datos_xml = $this->Complemento_cxpModel->leerxml( "./UPLOADS/XMLS/".$row->nombre_archivo );

                if(  $datos_xml['Descuento'] && $datos_xml['Descuento'][0] > 0.00 ){

                    $this->db->update("solpagos", array("descuento" => $datos_xml['Descuento'][0] ), "  idsolicitud ='".$row->idsolicitud."'");

                } 
            }
        }
    }

    public function registrar_xmls(){
        $this->load->model(array('Complemento_cxpModel', 'Solicitudes_solicitante' ));
        set_time_limit ( 0 );
        $query = $this->db->query("SELECT * FROM facturas WHERE facturas.idfactura NOT IN ( SELECT xmls.idfactura FROM xmls ) AND facturas.idfactura NOT IN ( 229, 5183, 9125, 13588, 18382 ) AND idsolicitud NOT IN ( SELECT solpagos.idsolicitud FROM solpagos WHERE idProveedor = 312 ) ");
        
        foreach( $query->result() as $row ){
            if( file_exists( "./UPLOADS/XMLS/".$row->nombre_archivo ) ){
                $datos_xml = $this->Complemento_cxpModel->leerxml( "./UPLOADS/XMLS/".$row->nombre_archivo );
                $this->Solicitudes_solicitante->guardar_xml( $row->idfactura, $datos_xml["textoxml"] );
            }
        }
    }

    public function cargar_xml_sin_idsolicitud(){
        $xmls_sin_procesar = glob("./UPLOADS/OTROS/SIN_PROCESAR/*.xml");
        $this->load->model(array('Complemento_cxpModel'));   
        if( count( $xmls_sin_procesar ) > 0 ){

            $xml_procesados = [];
            $c = 0;
            $data = [];

            while( $c < count( $xmls_sin_procesar ) ){

                if( file_exists( $xmls_sin_procesar[$c] ) ){
                    $datos_xml = $this->Complemento_cxpModel->leerxml( $xmls_sin_procesar[$c] , TRUE );

                    $datos_xml["uuid"] = strpos( strtoupper($datos_xml["uuid"]), 'UUID' ) ? $datos_xml["uuid"].date("Ymdhis") : $datos_xml["uuid"];

                    //************GENERA EL NUEVO NOMBRE DEL DOCUMENTO XML SUBIDO AL SISTEMA*********/
                    $nuevo_nombre = date("my")."_";
                    $nuevo_nombre .= str_replace( array(",", ".", '"'), "", str_replace( array(" ", "/"), "_", limpiar_dato($datos_xml["nomemi"]) ))."_";
                    $nuevo_nombre .= date("Hms")."_";
                    $nuevo_nombre .= substr($datos_xml["uuid"], -5).".xml";

                    copy( $xmls_sin_procesar[$c], "./UPLOADS/OTROS/PROCESADOS/".$nuevo_nombre );
                    unlink( $xmls_sin_procesar[$c] );
                    $xml_procesados[] = $nuevo_nombre;
                    //**************AQUI ES DONDE SE TERMINA EL RENOMBRAMIENTO AL ARCHIVO*****************/
                    if( $datos_xml['uuid'] && $this->db->query("SELECT f.uuid FROM facturas f WHERE f.uuid = ?", [ $datos_xml['uuid'] ] )->num_rows() === 0 ){
                        //LINEA ORIGINAL
                        $datos_xml['nombre_xml'] = $nuevo_nombre;
                        $datos_xml['tipo_factura'] = $datos_xml['MetodoPago'][0] != 'PPD' ? 3 : 1;

                        //TODO LO RELACIONADO CON LOS IMPUESTOS
                        $datos_xml['impuestot'] = (isset($datos_xml) && $datos_xml['impuestos_T'] ? json_encode($datos_xml['impuestos_T']) :  0.00 );
                        $datos_xml['impuestor'] = (isset($datos_xml) && $datos_xml['impuestos_R'] ? json_encode($datos_xml['impuestos_R']) : 0.00 );
                        /*****************************/

                        $descripcion = "";
                        foreach( $datos_xml["conceptos"] as $concepto ){
                            $descripcion .= $concepto["ClaveProdServ"].") ".$concepto['Descripcion']." - Importe: $".$concepto['Importe']." \n";
                        };

                        $data[] = array(
                            "fecfac"  => $datos_xml['fecha'],
                            "foliofac"  => $datos_xml['folio'],
                            "rfc_emisor" => $datos_xml['rfcemisor'],
                            "rfc_receptor" => $datos_xml['rfcrecep'],
                            "descripcion" => $descripcion,
                            "total"  => $datos_xml['Total'],
                            "metpago"  => $datos_xml['MetodoPago'],
                            "observaciones"  => $datos_xml['condpago'],
                            "idsolicitud" => 0,
                            "uuid" => $datos_xml['uuid'],
                            "nombre_archivo" => $datos_xml['nombre_xml'],
                            "tipo_factura" => $datos_xml['tipo_factura'],
                            "subtotal" => $datos_xml['SubTotal'],
                            "descuento" => $datos_xml['Descuento'],
                            "impuestot" => $datos_xml['impuestot'],
                            "impuestor" => $datos_xml['impuestor'],
                            "idusuario" => 84,
                            /***************************/
                            "ISRtras" => $datos_xml['ISRtras'],
                            "IEPStras" => $datos_xml['IEPStras'],
                            "tasatras16" => $datos_xml['tasatras16'],
                            "tasatras8" => $datos_xml['tasatras8'],
                            "tasatras0" => $datos_xml['tasatras0'],
                            "tasatrasExp" => $datos_xml['tasatrasExp'],
                            "rettras" => $datos_xml["rettras"],
                            /***************************/
                            "md5_hash" => $datos_xml['md5_hash'],
                            /***************************/
                            "regf_emisor"=>$datos_xml['regfisemisor'],
                            "regf_recep"=>$datos_xml['regfisrecep'],
                            "domf_recep"=>$datos_xml['cpfisrecep']
                        );
                    }
                    $c++;
                }
            }
            echo "INSERTA DENTRO LA TABLA DE FACTURAS ".count( $data );
            if( count( $data ) > 0 ){
                $this->db->insert_batch( "facturas", $data );
            }   
        }       
    }

    public function adjuntar_pagos_facturas(){
        $this->load->model(array('Complemento_cxpModel'));
        foreach( $this->db->query("SELECT * FROM facturas where tipo_factura = 2 AND idsolicitudr is null AND idfactura not in ( SELECT DISTINCT(idfactura) idfactura FROM autpagos WHERE idfactura IS NOT NULL )")->result() as $row ){

            if( file_exists( './UPLOADS/XMLS/'.$row->nombre_archivo ) ){
                $datos_xml = $this->Complemento_cxpModel->leerxml( './UPLOADS/XMLS/'.$row->nombre_archivo );

                //GUARDAMOS LA FACTURA EN LA TABLA DE FACTURAS
                $datos_xml['idComplementos'] = json_encode($datos_xml['idComplementos']); 
                $datos_xml['idComplementos'] = json_decode($datos_xml['idComplementos'], TRUE);

                
                if( is_array( $datos_xml['idComplementos'] ) && count( $datos_xml['idComplementos'] ) > 0 ){

                    $documentos_relacionados = array();
                    foreach( $datos_xml['idComplementos'] as $resultado ){
                        $documentos_relacionados[] = $resultado["@attributes"];
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
                    }

                    $this->Complemento_cxpModel->insert_temporal( $documentos_relacionados );
                    $this->Complemento_cxpModel->liberar_pendientes( $row->idfactura );
                }
            }
        }
    }

    public function impuestos(){

        $string = '[
            {"@attributes":{"Impuesto":"003","TipoFactor":"Tasa","TasaOCuota":"0.000000","Importe":"0.00"}},
            {"@attributes":{"Impuesto":"002","TipoFactor":"Tasa","TasaOCuota":"0.160000","Importe":"12040.94"}}
            ]';

        $string = json_decode( $string, true );
        var_dump( $string[0]["@attributes"]["TasaOCuota"] );

        /****RETENCIONES IVA****/
        //TASA 0
        for( $i = 0; $i < count( $string ); $i++ ){
            if( $string[$i]["@attributes"]["Impuesto"] == "002" && $string[$i]["@attributes"]["TasaOCuota"] == 0){
                var_dump( $string[$i] );
                break;
            }
        }
        //TASA MAYOR A 0
        for( $i = 0; $i <= count( $string ); $i++ ){
            if( $string[$i]["@attributes"]["Impuesto"] == "002" &&  $string[$i]["@attributes"]["TasaOCuota"] > 0 ){
                var_dump( $string[$i] );
                break;
            }
        }
        /********/

        /****RETENCIONES TRASLADO IEPS****/
        //TASA 0
        for( $i = 0; $i < count( $string ); $i++ ){
            if( $string[$i]["@attributes"]["Impuesto"] == "003" && $string[$i]["@attributes"]["TasaOCuota"] == 0){
                var_dump( $string[$i] );
                break;
            }
        }
        //TASA MAYOR A 0
        for( $i = 0; $i <= count( $string ); $i++ ){
            if( $string[$i]["@attributes"]["Impuesto"] == "003" &&  $string[$i]["@attributes"]["TasaOCuota"] > 0 ){
                var_dump( $string[$i] );
                break;
            }
        }
        /********/
    }
}
