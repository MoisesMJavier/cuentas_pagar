<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ArchivoBanco extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('ArchivoBan');
    }

    public function index(){
        $this->load->view("vista_cxp");
    }


    public function frmbanco(){
        $this->load->view('frmpban');
    }

    public function actualizar_pagos_otros($empresa){
        // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
        $json['resultado'] = FALSE;
        if($empresa){
            $this->load->model("Solicitudes_cxp");
            $this->db->query("UPDATE autpagos as ap INNER JOIN solpagos AS sp ON sp.idsolicitud = ap.idsolicitud SET estatus = 11 WHERE sp.programados IS NULL AND sp.idEmpresa = ".$empresa." AND (ap.estatus IN(0) AND sp.metoPago != 'TEA' AND sp.metoPago != 'ECHQ') OR (ap.estatus IN(13) AND (ap.tipoPago != 'TEA' OR ap.formaPago != 'TEA'))");
            $json['resultado'] = TRUE;
        }

        echo json_encode( $json );
    }
 
    public function genpbanc($emp, $cuenta, $filtro){
        // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
        $this->load->model( array("ArchivoBan", "Solicitudes_cxp") );
        $datos = $this->ArchivoBan->pabanco( $emp, $filtro );
        $cuenta_bancaria = $this->ArchivoBan->get_cuenta_empresa($emp, $cuenta);
 
        if( $datos->num_rows() > 0 && $cuenta_bancaria->num_rows() > 0 ){
            $response = $this->generar_txt_banco_TEA( $datos, $cuenta_bancaria, $emp );
            $response['resultado'] = FALSE;
        }
        else{
            $response['resultado'] = TRUE;
            $response['mensaje'] = $datos->num_rows() === 0 ? '<h5 class="text-center text-warning">Sin pagos en esta Empresa.</h5>' : "";
            // $response['mensaje'] .=$cuenta_bancaria->num_rows() === 0 ? '<h5 class="text-center text-danger">Aún no se ha asignado cuenta para esta empresa, favor de asignar.</h5>' : "";    
        }

        //ACTUALIZAMOS LOS PAGOS PARA SER PROCESADOS
        $this->ArchivoBan->updatePagosTxt($emp, $cuenta, $filtro);
        
        //OBTENEMOS LA INFORMACION PARA RECONSTRUIR LA TABLA DE TRANSFERENCIAS
        $response['transferencias'] = $this->Solicitudes_cxp->get_solicitudes_historial_area()->result_array();
        // $response['programados'] = $this->Solicitudes_cxp->get_pagos_aut_prog()->result_array();
        
        echo json_encode($response);
    }

    public function datos_otros($emp_2){
        $this->load->model("ArchivoBan");

        $datos2 = $this->ArchivoBan->pabanco_otros( $emp_2, NULL );

        if($datos2->num_rows() > 0 ){
             $response = array();
             $response['resultado'] = FALSE;
             $response['empresa_valor'] = $emp_2;
             $totpagar = NULL;
             $i=2;


             foreach($datos2->result() as $row){
                if( $row->moneda!='MXN' && $row->tipoCambio !=''){
                    $new_valor = $row->cantidad*$row->tipoCambio;
                    $new_cantidad =  number_format(str_replace(array(".", ","), array(",", "."), $new_valor),2, ".", "");
  
                }
                else{
                    $new_valor2 = $row->cantidad;
                    $new_cantidad =  number_format(str_replace(array(".", ","), array(",", "."), $new_valor2),2, ".", "");
                }

                 
                
                $totpagar=$totpagar+$new_cantidad;
 
               
            }
             
             // foreach($datos2->result() as $row){
             //    $totpagar=$totpagar+$row->cantidad;
             // }

             $response['totpag'] = $totpagar;
             // $this->ArchivoBan->PDF_UPDATETxt($datos2);
         }
         
        else{
            $response['resultado'] = TRUE;
            $response['mensaje'] = $datos2->num_rows() === 0 ? '<h5 class="text-center text-warning">Sin pagos en esta Empresa.</h5>' : "";
              
        }
        echo json_encode($response);
    }  

    public function genpbanc_caja_chica( $empres, $cuenta, $metodo, $datos_procesar ){

        $this->load->model("ArchivoBan");

        switch( $metodo ) {
            case 'TEA':
                $datos = $this->ArchivoBan->pabanco_chica($empres, $metodo, $datos_procesar );
                $cuenta_bancaria = $this->ArchivoBan->get_cuenta_empresa($empres,$cuenta);
        
                if( $datos->num_rows() > 0 && $cuenta_bancaria->num_rows() > 0){
                    $response = $this->generar_txt_banco_TEA( $datos, $cuenta_bancaria, $empres );
                    $metodo = "'TEA'";
                }
                break;
            case 'ECHQ':
                $datos = $this->ArchivoBan->pabanco_chica_cheques( $empres, $metodo, $datos_procesar );
                
                if($datos->num_rows() > 0){
                    $response = $this->generar_txt_banco_ECHQ( $datos );
                    $metodo = "'ECHQ', 'EFEC'";
                }
                break;
            case 'MAN':
                $datos = $this->ArchivoBan->pabanco_chica($empres, $metodo, $datos_procesar);

                if($datos->num_rows() > 0){
                    $response['resultado'] = TRUE;
                    $response['totpag'] = 0.00;
                    $metodo = "'MAN'";
                }
                break;
        }

        if( $datos->num_rows() === 0 ){
            $response['resultado'] = FALSE;
            $response['mensaje'] = '<h5 class="text-center text-warning">No se encontró ninguna transacción.</h5>';
        }else{
            $this->ArchivoBan->update_estatus_cajachica( 2, $metodo, $empres, '1, 2' );
            $this->log_solpagos( $empres, $metodo);
        }

        echo json_encode($response);
    }  

    //PROCESAR PAGOS PROGRAMADOS
    public function genpbanc_caja_programados( $empres, $cuenta ){

        $response['resultado'] = FALSE;

        if( !empty( $_POST ) && isset( $_POST ) ){
            
            $this->load->model( array("ArchivoBan", "Solicitudes_cxp") );
            
            $response = array();
            $metodo = $this->input->post("valor_metodo");
            $filtro = 0;

            switch ($metodo) {
                case 'TEA':
                    $response['resultado'] = TRUE;

                    $datos = $this->ArchivoBan->pabanco_program($empres, $metodo);
                    $cuenta_bancaria = $this->ArchivoBan->get_cuenta_empresa( $empres, $cuenta );
    
                    if( $datos->num_rows() > 0 && $cuenta_bancaria->num_rows() > 0 ){
                        $response = $this->generar_txt_banco_TEA( $datos, $cuenta_bancaria, $empres );            
                    }
                    else{
                        $response['mensaje'] = $datos->num_rows() === 0 ? '<h5 class="text-center text-warning">Sin pagos de tranferencia en esta empresa.</h5>' : "";
                    }
                    //ACTUALIZAMOS TODOS LOS PAGOS PROGRAMADOS TEA CON LAS CARACTERISTICAS SOLICITADAS
                    $this->ArchivoBan->updatePagosTxtpg($empres, $cuenta, $filtro);            
                    break;
                case 'DOMIC':
                    $response['resultado'] = TRUE;
                    $this->ArchivoBan->updatePagosTxtpg2( $empres );
                    break;
                default:
                    $datos = $this->ArchivoBan->pabanco_PROGRAM_cheques( $empres, $metodo );
                    if($datos->num_rows() > 0){
                        $response = $this->generar_txt_banco_ECHQ( $datos );
                        $this->ArchivoBan->updatePagosTxtpg2( $empres );
                    }else{
                        $response['resultado'] = TRUE;
                        $response['mensaje'] = $datos->num_rows() === 0 ? '<h5 class="text-center text-warning">Sin pagos de tranferencia en esta empresa.</h5>' : "";
                    }
            }

            $response["data"] = $this->Solicitudes_cxp->get_pagos_aut_prog()->result_array();

        }
        echo json_encode( $response );
    }

    public function genpbanc_otros( $empres ){

        $this->load->model("ArchivoBan");
 
        $datos = $this->ArchivoBan->pabanco_otros_cheques( $empres );
 
        if($datos->num_rows() > 0){
 
            $response = array();
            $response['resultado'] = FALSE;
            $mensaje = NULL;
            $lineas = NULL;
            $regist = NULL;
            $totpagar_ch = NULL;
            $carperta = "UPLOADS/txtbancos/";

            $i=2;
            foreach($datos->result() as $row){
 
                $cantidad_nueva = str_replace(" ",",",($row->cantidad));
 
                $empr = $row->abrev;
                $totpagar_ch=$totpagar_ch+$row->cantidad;
                $mensaje .= str_replace(" ",",",($row->referencia.','.$cantidad_nueva));
 
                if($i-2<$datos->num_rows()-1){
                     $mensaje .= chr(13);
                }
 
                $i++;
                $lineas=$i;
                $regist=$i-2;
             }
 
            $nombre = "ECHQ".date("dmY").".txt";
            $nombre_archivo = $carperta.$nombre;
            $response['totpag'] = $totpagar_ch;
 
            if(file_exists($nombre_archivo)){
                unlink($nombre_archivo);
            }

            if($archivo = fopen($nombre_archivo,"a")){
                $response['arch']=fwrite($archivo,$mensaje)?"ok":"error";
                fclose($archivo);
            }

            $response['file']=base_url("UPLOADS/txtbancos/".$nombre);
            $this->ArchivoBan->updatePagosTxt_otros_DOS($empres);
 
        }
        else{
            $this->ArchivoBan->updatePagosTxt_otros($empres);
            $response['resultado'] = TRUE;
            $response['mensaje'] = $datos->num_rows() === 0 ? '<h5 class="text-center text-warning">Se han actualizado los pagos que no requieren TXT, por el momento no hay txt disponibles para esta empresa.</h5>' : "";
            
        }


       echo json_encode($response);
 
    
    }  


    public function genpbanc_individual( $idpago, $cuenta ){
        $this->load->model("ArchivoBan");
        $consulta = $this->db->query("SELECT autpagos.descarga, empresas.idempresa FROM  autpagos INNER JOIN solpagos ON autpagos.idsolicitud=solpagos.idsolicitud inner join empresas on solpagos.idEmpresa = empresas.idempresa  where idpago = $idpago")->row(); 

        $valor_empresa = $consulta->idempresa;
        $valor_descarga = $consulta->descarga+1;

        $datos = $this->ArchivoBan->pabanco_individual( $valor_empresa, $idpago );
        $cuenta_bancaria = $this->ArchivoBan->get_cuenta_empresa( $valor_empresa, $cuenta);

        if( $datos->num_rows() > 0 && $cuenta_bancaria->num_rows() > 0 ){
            //GENERAR DOCUMENTOS TXT PARA CARGA DE ARCHIVOS AL BANCO
            //INFORMACION, CUENTA BANCARIA, EMPRESA
            $response = $this->generar_txt_banco_TEA( $datos, $cuenta_bancaria, $valor_empresa );

        }else{
            $response['resultado'] = FALSE;
            $response['mensaje'] = $datos->num_rows() === 0 ? '<h5 class="text-center text-warning">Dato no encontrado</h5>' : "";
              
        }

        echo json_encode($response);
    }  
    // GENERACION DE TXT DE VIATICOS
    public function genpbanc_viaticos( $empres, $cuenta, $metodo, $datos_procesar ){

        $this->load->model("ArchivoBan");

        switch( $metodo ) {
            case 'TEA':
                $datos = $this->ArchivoBan->pabanco_viaticos($empres, $metodo, $datos_procesar );
                $cuenta_bancaria = $this->ArchivoBan->get_cuenta_empresa($empres,$cuenta);
        
                if( $datos->num_rows() > 0 && $cuenta_bancaria->num_rows() > 0){
                    $response = $this->generar_txt_banco_TEA( $datos, $cuenta_bancaria, $empres );
                    $metodo = "'TEA'";
                }
                break;
            case 'ECHQ':
                $datos = $this->ArchivoBan->pabanco_chica_cheques( $empres, $metodo, $datos_procesar );
                
                if($datos->num_rows() > 0){
                    $response = $this->generar_txt_banco_ECHQ( $datos );
                    $metodo = "'ECHQ', 'EFEC'";
                }
                break;
            case 'MAN':
                $datos = $this->ArchivoBan->pabanco_chica($empres, $metodo, $datos_procesar);

                if($datos->num_rows() > 0){
                    $response['resultado'] = TRUE;
                    $response['totpag'] = 0.00;
                    $metodo = "'MAN'";
                }
                break;
        }

        if( $datos->num_rows() === 0 ){
            $response['resultado'] = FALSE;
            $response['mensaje'] = '<h5 class="text-center text-warning">No se encontró ninguna transacción.</h5>';
        }else{
            $this->ArchivoBan->update_estatus_cajachica( 2, $metodo, $empres, '1, 2' );
            $this->log_solpagos( $empres, $metodo);
        }

        echo json_encode($response);
    }  
    //GENERAR DOCUMENTOS TXT PARA CARGA DE ARCHIVOS AL BANCO
    //INFORMACION, CUENTA BANCARIA, EMPRESA
    /**  FECHA : 21-Agosto-2025 | @author Mahonri Javier programador.analista63@ciudadmaderas.com | Se agrega idproceso al txt generado para movimiento bancario */

    function generar_txt_banco_TEA( $datos, $cuenta_bancaria, $empresa ){

        $consulta_noArchivo = $this->db->query("SELECT noArchivo FROM empresas where idempresa = ".$empresa);

        $cuenta_bancaria = $cuenta_bancaria->row();
        $totpagar_ch = NULL;
        $noarchivo = $consulta_noArchivo->row()->noArchivo;
        
        $encabezado = "010000001".date("Ymd").str_pad($noarchivo, 3, "0",STR_PAD_LEFT).chr(13).chr(10);
        $ctaemi = str_pad( $cuenta_bancaria->nodecta, 20, "0",STR_PAD_LEFT);
        $ctatip = str_pad( $cuenta_bancaria->tipocta, 2, "0",STR_PAD_LEFT);
        $mensaje = $encabezado;
        $lineas = NULL;
        $regist = NULL;
        $carperta = "UPLOADS/txtbancos/";
        $new_cantidad = 0;

        $i=2;

        foreach($datos->result() as $row){

            $new_valor = $row->cantidad;

            if( $row->moneda!='MXN' && $row->tipoCambio !=''){
                $new_valor = $row->cantidad*$row->tipoCambio;
            }

            if( $row->interes!='' && $row->interes !=0 && $row->moneda=='MXN' ){
                $new_valor = $row->cantidad+$row->interes;
            }

            $new_cantidad =  number_format(str_replace(array(".", ","), array(",", "."), $new_valor),2, ".", "");
            
            // $ref_banc= strtoupper($row->ref_bancaria); 
            // $idSol= ("SOLICITUD".$Row->idsolicitud)

            //AGREGAMOS LA REFERENCIA BANCARIA EN CASO QUE NO CUENTE LA SOLICITUD O TENGA CARACTERISTERES ESPECIALES SE AGREGAR EL FOLIO DE LAFACTURA
            if( $row->ref_bancaria == null || preg_replace('([^A-Za-z0-9])', '', $row->ref_bancaria ) == "" ){
                $new_descripcion = str_replace(array(".", ",", "/"), "", "FOLIO FACT ".$row->descri);                }
            else{
                $new_descripcion = str_replace(array(".", ",", "/"), "", $row->ref_bancaria );
            }
            $new_descripcion = str_replace( ["+", "%20"], [" ", " "], limpiar_dato( $new_descripcion ) );
            
            $spi = $row->clvbanco == "40030" ? "BCO" : "SPI";
            $totpagar_ch = $totpagar_ch + $new_cantidad;

            // RETIRAR PARA CONSIDERAR LA LONGITUD DE LA REFERENCIA BANCARIA Y EL IDPROCESO PARA DEJAR ESPACIOS EN BLANCO
            // $maxLongitud= 40 - (strlen($row->idsolicitud)+2);

            if(strtoupper($row->ref_bancaria) === "SOLICITUD".$row->idsolicitud ){
                $mensaje .= "02".str_pad($i, 7, "0",STR_PAD_LEFT).$ctatip.$ctaemi."01".$row->clvbanco.str_pad(str_replace(".","",($new_cantidad*100)),15,"0",STR_PAD_LEFT).date("Ymd").$spi.str_pad($row->tipocta,2,"0",STR_PAD_LEFT).str_pad($row->cuenta,20,"0",STR_PAD_LEFT)."000000000".str_pad($row->alias,15," ",STR_PAD_RIGHT)."000000000000000".str_pad( $new_descripcion, 40, " ",STR_PAD_RIGHT).chr(13).chr(10);
            }else{
                // RETIRAR PARA CONSIDERAR LA LONGITUD DE LA REFERENCIA BANCARIA Y EL IDPROCESO PARA DEJAR ESPACIOS EN BLANCO
                // if( strlen($new_descripcion) <= $maxLongitud){
                //     $mensaje .= "02".str_pad($i, 7, "0",STR_PAD_LEFT).$ctatip.$ctaemi."01".$row->clvbanco.str_pad(str_replace(".","",($new_cantidad*100)),15,"0",STR_PAD_LEFT).date("Ymd").$spi.str_pad($row->tipocta,2,"0",STR_PAD_LEFT).str_pad($row->cuenta,20,"0",STR_PAD_LEFT)."000000000".str_pad($row->alias,15," ",STR_PAD_RIGHT)."000000000000000".str_pad( ($new_descripcion." #".$row->idsolicitud), 40, " ",STR_PAD_RIGHT).chr(13).chr(10);
                // }
                if( strlen($new_descripcion) <= 40){
                    $mensaje .= "02".str_pad($i, 7, "0",STR_PAD_LEFT).$ctatip.$ctaemi."01".$row->clvbanco.str_pad(str_replace(".","",($new_cantidad*100)),15,"0",STR_PAD_LEFT).date("Ymd").$spi.str_pad($row->tipocta,2,"0",STR_PAD_LEFT).str_pad($row->cuenta,20,"0",STR_PAD_LEFT)."000000000".str_pad($row->alias,15," ",STR_PAD_RIGHT)."000000000000000".str_pad( ($new_descripcion." #".$row->idsolicitud), 40, " ",STR_PAD_RIGHT).chr(13).chr(10);
                }else{
                    $mensaje .= "02".str_pad($i, 7, "0",STR_PAD_LEFT).$ctatip.$ctaemi."01".$row->clvbanco.str_pad(str_replace(".","",($new_cantidad*100)),15,"0",STR_PAD_LEFT).date("Ymd").$spi.str_pad($row->tipocta,2,"0",STR_PAD_LEFT).str_pad($row->cuenta,20,"0",STR_PAD_LEFT)."000000000".str_pad($row->alias,15," ",STR_PAD_RIGHT)."000000000000000".str_pad( $new_descripcion, 40, " ",STR_PAD_RIGHT)." #".str_pad( $row->idsolicitud, 8, " ",STR_PAD_RIGHT).chr(13).chr(10);
                }
            }

            $i++;
            $lineas=$i;
            $regist=$i-2;
        }


        $nombre = "PAGOS_".$cuenta_bancaria->abrev."_".date("d_m_Y_H_i").".txt";
        $nombre_archivo = $carperta.$nombre;
        $mensaje .= "09".str_pad($lineas,7,"0",STR_PAD_LEFT).str_pad($regist,7,"0",STR_PAD_LEFT).str_pad(str_replace(".","",($totpagar_ch*100)),18,"0",STR_PAD_LEFT).chr(13).chr(10);

        if(file_exists($nombre_archivo)){
            unlink($nombre_archivo);
        }

        if($archivo = fopen($nombre_archivo,"a")){
            fwrite($archivo,$mensaje)?"ok":"error";
            fclose($archivo);
        }
        
        return array( "resultado" => TRUE, "file" => base_url( "UPLOADS/txtbancos/".$nombre ), "totpag" => $totpagar_ch );
    }

    //GENERAMOS TXT PARA CHEQUES
    //ENVIAMOS DATOS OBTENIDOS
    function generar_txt_banco_ECHQ( $datos ){

        $mensaje = NULL;
        $totpagar_ch = NULL;
        $carperta = "UPLOADS/txtbancos/";

        $i=2;

        foreach( $datos->result() as $row ){

            if( $row->moneda!='MXN' || $row->interes != '' ){

                if( $row->moneda!='MXN' && $row->tipoCambio !=''){
                    $new_valor = $row->cantidad*$row->tipoCambio;
                    $new_cantidad =  number_format(str_replace(array(".", ","), array(",", "."), $new_valor),2, ".", "");
                }

                if( $row->interes!='' && $row->interes !=0 && $row->moneda=='MXN' ){
                    $new_valor = $row->cantidad+$row->interes;
                    $new_cantidad =  number_format(str_replace(array(".", ","), array(",", "."), $new_valor),2, ".", "");
                }

            }else{
                $new_valor2 = $row->cantidad;
                $new_cantidad =  number_format(str_replace(array(".", ","), array(",", "."), $new_valor2),2, ".", "");
            }

            $totpagar_ch=$totpagar_ch+$new_cantidad;
            $mensaje.= str_replace(" ",",",($row->referencia.','.$new_cantidad));
            if( $i-2 < $datos->num_rows()-1){
                $mensaje .= chr(13);
            }

            $i++;
        }


        $nombre = "ECHQ".date("dmY").".txt";
        $nombre_archivo = $carperta.$nombre;

        if(file_exists($nombre_archivo)){
            unlink($nombre_archivo);
        }

        if($archivo = fopen($nombre_archivo,"a")){
            fwrite($archivo,$mensaje)?"ok":"error";
            fclose($archivo);
        }

        return array( "resultado" => TRUE, "file" => base_url("UPLOADS/txtbancos/".$nombre), "totpag" => $totpagar_ch );
    }
    
    function log_solpagos( $idempresa, $fpago ){
        $query= $this->db->query("select GROUP_CONCAT(idsolicitud) as idsolicitud from autpagos_caja_chica "
                . "where idEmpresa = $idempresa AND estatus = 2 AND tipoPago IN ( $fpago ) group by idEmpresa;");
        
        $ids_sol= explode (",", $query->row()->idsolicitud);
        
        $comentarios=array();
        foreach ($ids_sol as $id){
            array_push($comentarios, array(
                  "idsolicitud" => $id,
                  "tipomov" => "SE HA CARGADO EL REEMBOLSO AL BANCO, QUEDA A LA ESPERA DE DISPERSIÓN",
                  "idusuario" => $this->session->userdata('inicio_sesion')['id'],
                  "fecha" => date("Y-m-d H:i:s")
              ));
        }
        $resultado=$this->db->insert_batch( 'logs', $comentarios )>0;
        return $resultado;
    }
}



 