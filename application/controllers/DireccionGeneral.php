<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*Validaciones que controlan el flujo de la gestion
 *  de las facturas que puede controlar Dirreccion General*/

class DireccionGeneral extends CI_Controller {
    
    function __construct(){
        parent::__construct();
        if( $this->session->userdata("inicio_sesion")  ){
            $this->load->model("M_DireccionGeneral");
            $this->load->model("MTipoCambio");
        }else        
            redirect("Login", "refresh");
    }
    
    function index() {
        if($this->session->userdata("inicio_sesion") && in_array( $this->session->userdata("inicio_sesion")['rol'], array( 'DG', 'DP', 'SB', 'SU' )) || in_array( $this->session->userdata("inicio_sesion")['id'], array( 2636 )) ) /** FECHA: 17-DICIEMBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
            $this->load->view("v_DireccionGeneral");
        else
            redirect("Login", "refresh");
    }

    function index_p() {
        if($this->session->userdata("inicio_sesion") && in_array( $this->session->userdata("inicio_sesion")['rol'], array( 'DG', 'DP', 'SB', 'SU' )) || in_array( $this->session->userdata("inicio_sesion")['id'], array( 25, 2636 )) ) /** FECHA: 17-DICIEMBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
            $this->load->view("v_DireccionGeneral_pagos");
        else
            redirect("Login", "refresh");
    }

    function OtrosGastos() {
        if($this->session->userdata("inicio_sesion") && in_array( $this->session->userdata("inicio_sesion")['rol'], array( 'DG', 'DP', 'SB', 'SU' )) || in_array( $this->session->userdata("inicio_sesion")['id'], array( 2636 )) ) /** FECHA: 17-DICIEMBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
            $this->load->view("v_facturasPendientes");
        else
            redirect("Login", "refresh");
    }

    function CajaChica() {
        if($this->session->userdata("inicio_sesion") && in_array( $this->session->userdata("inicio_sesion")['rol'], array( 'DG', 'DP', 'SB', 'SU' )) || in_array( $this->session->userdata("inicio_sesion")['id'], array( 2636 )) ) /** FECHA: 17-DICIEMBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
            $this->load->view("v_caja_chica");
        else
            redirect("Login", "refresh");
    }

	function Reembolsos() {
		if($this->session->userdata("inicio_sesion") && in_array( $this->session->userdata("inicio_sesion")['rol'], array( 'DG', 'DP', 'SB', 'SU' )) || in_array( $this->session->userdata("inicio_sesion")['id'], array( 2636 )) ) /** FECHA: 17-DICIEMBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
			$this->load->view("v_reembolsos");
		else
			redirect("Login", "refresh");
	}

	function viaticos() {
		if($this->session->userdata("inicio_sesion") && in_array( $this->session->userdata("inicio_sesion")['rol'], array( 'DG', 'DP', 'SB', 'SU' )) || in_array( $this->session->userdata("inicio_sesion")['id'], array( 2636 )) ) /** FECHA: 17-DICIEMBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
			$this->load->view("v_viaticos");
		else
			redirect("Login", "refresh");
	}
    function tarjetas_credito() {
        if($this->session->userdata("inicio_sesion") && in_array( $this->session->userdata("inicio_sesion")['rol'], array( 'DG', 'DP', 'SB', 'SU' )) || in_array( $this->session->userdata("inicio_sesion")['id'], array( 2636 )) ) /** FECHA: 17-DICIEMBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
            $this->load->view("v_tdc_direccion_general");
        else
            redirect("Login", "refresh");
    }

    function Compras() {
        if( in_array( $this->session->userdata("inicio_sesion")['id'], array( 177, 1844, 1855 ) ) && $this->session->userdata("inicio_sesion")['depto']=="COMPRAS")
            $this->load->view("v_DireccionGeneral");
        else
            redirect("Login", "refresh");
    }

    function Compras_autorizadas() {
        if( in_array( $this->session->userdata("inicio_sesion")['id'], array( 177, 1844, 1855 ) ) && $this->session->userdata("inicio_sesion")['depto']=="COMPRAS")
            $this->load->view("v_compras_autorizadas");
        else
            redirect("Login", "refresh");
    }

    function tablaSolC(){
        echo json_encode( array( "data" => $this->M_DireccionGeneral->obtenerSolCompra()));
    }
    
    //PAGOS PENDIENTES HACIA LOS PROVEEDORES ( NO FINIQUITOS PRESTAMOS DEVOLUCIONES )
    function tablaSolPendientes(){
        //SACA TODA LA INFORMACION DE LOS PROVEEDORES ADEUDORES
        $data = $this->M_DireccionGeneral->obtenerSolPendientes()->result_array();
        $solicitudes = $this->M_DireccionGeneral->obtenerSolDiferida()->result_array();
        $divisas=$this->MTipoCambio->getdataM()->result_array();
        $series="";
        $i=0;
        foreach ($divisas as $row) {/*Consulta las divisas registradas con serie de consulta para banxico en la bd de cxp*/
            $series.=$row["serie"];/*Convierte los registros de serie en params delimitados por coma para enviarlos por GET*/
            $i++;
            if($i< count($divisas))
                $series.='%2C';
        }
        
        $url = "https://www.banxico.org.mx/SieAPIRest/service/v1/series/$series/datos/oportuno";
        
        $ch = curl_init();/*Petición GET tipo ajax para php*/
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_HTTPGET, true);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Accept: application/json',
            'Bmx-Token: b50787ae17bba0ecf2a7b0acda1011882f30dc75bde4fe04a78f6645433a6169'/*Token generado en la pag de banxico exclusivo para CD Maderas*/
        ));
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.A.B.C Safari/525.13");
        $result = curl_exec($ch);
        //print_r($result);
        if (curl_errno($ch)) {
            $result= array();
        }else{
            if($this->isJson($result)){
                $result= json_decode ($result,true);
                if(array_key_exists("bmx", $result)){
                    $result = $result["bmx"];
                    if(array_key_exists("series", $result)){
                        $result= $result["series"];
                    }else 
                        $result=array();
                }else 
                    $result=array();
            }else
                $result=array();
        }
        curl_close($ch);
        
        //print_r($result);
        
        $valores=array();
        foreach($result as $seriebmx){/*Crea el arreglo que relaciona la clave de la moneda con su valor por pesos mexicanos*/
            foreach ($divisas as $seriedb){
                if($seriebmx["idSerie"]==$seriedb["serie"]){
                    if(!in_array( $seriebmx["idSerie"],$valores) && is_numeric( $seriebmx["datos"][0]["dato"] ) )
                        $valores["$seriedb[moneda]"]= round($seriebmx["datos"][0]["dato"]+0,2);
                        //array_push($valores, array("$seriedb[moneda]"=>$seriebmx->datos[0]->dato+0));
                }
            }
        }
        //print_r($valores);
        //echo json_encode( $valores);
        
        //CABECERAS
        for( $i = 0; $i < count($data); $i++ ){

            $fecha = date("d", strtotime( $data[$i]['FECHAFACP'] ));
            $fecha .= "/".array("Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic")[date("m", strtotime( $data[$i]['FECHAFACP'] )) - 1];
            $fecha .= "/".date("Y", strtotime( $data[$i]['FECHAFACP'] ));

            $data[$i]['FECHAFACP'] = $fecha;

            $data[$i]['pa'] = 0;

            $array = array();
            $idprov_sep= explode(",", $data[$i]['ids']);

            //REGISTROS INDIVIDUALES
            $c = 0;
            do{
                if( in_array( $solicitudes[$c]['idproveedor'], $idprov_sep ) ){
                    $moneda=trim($solicitudes[$c]["moneda"])."";
                    if(array_key_exists(strval($moneda), $valores)){
                        $solicitudes[$c]['CantidadOriginal']=$solicitudes[$c]['Cantidad'];
                        $solicitudes[$c]['Cantidad']= round($solicitudes[$c]['Cantidad']*$valores[$moneda],4);
                        $solicitudes[$c]['AutorizadoOriginal']=$solicitudes[$c]['Autorizado'];
                        $solicitudes[$c]['Autorizado']= round($solicitudes[$c]['Autorizado']*$valores[$moneda],4);
                        $solicitudes[$c]["moneda"]="MXN";
                        $solicitudes[$c]["divisa"]=$moneda;
                        $solicitudes[$c]["vdivisa"]=$valores[$moneda]+0;
                    } else {
                        $solicitudes[$c]['CantidadOriginal']=0;
                        $solicitudes[$c]['AutorizadoOriginal']=0;
                        $solicitudes[$c]["vdivisa"]=1;
                        $solicitudes[$c]["divisa"]=$solicitudes[$c]["moneda"];
                    }

                    $array[] = $solicitudes[$c];
                    $data[$i]['Cantidad'] += $solicitudes[$c]["moneda"] == 'MXN' ? $solicitudes[$c]['Cantidad'] - $solicitudes[$c]['Autorizado'] : 0;
                    array_splice($solicitudes, $c, 1);

                }else{
                    $c++;
                }
                
            }while( isset($solicitudes[$c]) && $c < count($solicitudes ) );
            /*
            for( $c = 0; $c < count( $solicitudes ); $c++ ){
                //REVISA SI LOS REGISTROS INDIDUALES PERTENECEN AL LISTADO DE PROVEEDORES EN LA LISTA
                if( in_array( $solicitudes[$c]['idproveedor'], $idprov_sep ) ){
                    $moneda=trim($solicitudes[$c]["moneda"])."";
                    if(array_key_exists(strval($moneda), $valores)){
                        $solicitudes[$c]['CantidadOriginal']=$solicitudes[$c]['Cantidad'];
                        $solicitudes[$c]['Cantidad']= round($solicitudes[$c]['Cantidad']*$valores[$moneda],4);
                        $solicitudes[$c]['AutorizadoOriginal']=$solicitudes[$c]['Autorizado'];
                        $solicitudes[$c]['Autorizado']= round($solicitudes[$c]['Autorizado']*$valores[$moneda],4);
                        $solicitudes[$c]["moneda"]="MXN";
                        $solicitudes[$c]["divisa"]=$moneda;
                        $solicitudes[$c]["vdivisa"]=$valores[$moneda]+0;
                    } else {
                        $solicitudes[$c]['CantidadOriginal']=0;
                        $solicitudes[$c]['AutorizadoOriginal']=0;
                        $solicitudes[$c]["vdivisa"]=1;
                        $solicitudes[$c]["divisa"]="MXN";
                    }

                    $array[] = $solicitudes[$c];
                    
                    $data[$i]['Cantidad'] += $solicitudes[$c]['Cantidad'] - $solicitudes[$c]['Autorizado'];

                }
            }
            */
            $data[$i]['solicitudes'] = $array;
            
        }
        
        echo json_encode( array( "data" => $data ));
    }

    function tablaDGotrosgastos(){

        $data = $this->M_DireccionGeneral->obtenerSolPendientesOtros()->result_array();

        for( $i = 0; $i < count($data); $i++ ){

            $fecha = date("d", strtotime( $data[$i]['FECHAFACP'] ));
            $fecha .= "/".array("Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic")[date("m", strtotime( $data[$i]['FECHAFACP'] )) - 1];
            $fecha .= "/".date("Y", strtotime( $data[$i]['FECHAFACP'] ));

            $data[$i]['FECHAFACP'] = $fecha;
            $data[$i]['pa'] = 0;
        }

        echo json_encode( array( "data" => $data ));
    }

    function tablaFacturaje(){

        $data = $this->M_DireccionGeneral->obtenerSolPendientesFactoraje()->result_array();

        for( $i = 0; $i < count($data); $i++ ){

            $fecha = date("d", strtotime( $data[$i]['FECHAFACP'] ));
            $fecha .= "/".array("Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic")[date("m", strtotime( $data[$i]['FECHAFACP'] )) - 1];
            $fecha .= "/".date("Y", strtotime( $data[$i]['FECHAFACP'] ));

            $data[$i]['FECHAFACP'] = $fecha;
            $data[$i]['pa'] = 0;
        }

        echo json_encode( array( "data" => $data ));
    }

    /*****************************************/
    function tablaSolCaja(){
        
        $data = $this->M_DireccionGeneral->obtenerSolCaja();
        
        if( !empty($data) ){
            $data = $data->result_array();
        }
        
        echo json_encode( array( "data" => $data ));
    }

	function tablaSolReemb(){

		$data = $this->M_DireccionGeneral->obtenerSolReembolsos();

		if( !empty($data) ){
			$data = $data->result_array();
		}

		echo json_encode( array( "data" => $data ));
	}
	function tablaSolViaticos(){

		$data = $this->M_DireccionGeneral->obtenerSolViaticos();

		if( !empty($data) ){
			$data = $data->result_array();
		}

		echo json_encode( array( "data" => $data ));
	}

	function tablaSolTDC(){
        
        $data = $this->M_DireccionGeneral->obtenersolTDC();
        
        if( !empty($data) ){
            $data = $data->result_array();
        }
        
        echo json_encode( array( "data" => $data ));
    }
    

    function registros_cajaschicas(){
        $resultado = array( "resultado" => TRUE );

        if( isset( $_POST ) && !empty( $_POST ) ){
            $resultado = array( "resultado" => TRUE, "solicitudes" => $this->M_DireccionGeneral->getSolicitudesCCH( $this->input->post("ID") )->result_array() );
        }

        echo json_encode( $resultado );
    }
    /*****************************************/
      
    function totalPagar(){
        $pagar =  $this->input->post('jsonApagar');
        $pagarT = json_decode($pagar);
    }
      

    public function AutorizarSolicitud() {
        $id = $this->input->post('id_sol');
        log_sistema( $this->session->userdata('inicio_sesion')['id'], $id, "SE AUTORIZÓ EL GASTO SOLICITADO" );
        echo json_encode($this->M_DireccionGeneral->autSolicitud($id));
    }

    function DeclinarSolicitud() {
       $id = $this->input->post('id_sol');
       $obs = $this->input->post('Obervacion');
       $query=$this->M_DireccionGeneral->declSolicitud($id); 
       //chat($id,"SE DECLINÓ ESTA SOLICITUD POR DG",$this->session->userdata('inicio_sesion')['id']);
       if($query){
        log_sistema( $this->session->userdata('inicio_sesion')['id'], $id, "SE DECLINO LA SOLICITUD" );
        chat($id,$obs,$this->session->userdata('inicio_sesion')['id']);
       }
       echo json_encode(["respuesta"=>$query]);
    }
     
 
    
    function PagarTodo(){
        // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
        
        $resultado = false;

//        print_r($this->input->post('jsonApagar'));
//        exit;
        if( $this->input->post('jsonApagar') ){
            $json = json_decode($this->input->post('jsonApagar'));
            $this->db->trans_start();

            $solicitudes = array();
            $comentarios = array();
            $idsolicitudes = array();
            $hora_autorizacion = date("Y-m-d H:i:s");

            foreach ($json as $row){

                $idsolicitudes[] = $row[0];
                

                $solicitudes[] = array(
                    "idsolicitud" => $row[0],
                    "cantidad" => $row[1]/$row[2],
                    "idrealiza" => $this->session->userdata('inicio_sesion')['id'],
                    "fecreg" => $hora_autorizacion
                );

                $comentarios[] = array(
                    "idsolicitud" => $row[0],
                    "tipomov" => "SE HA AUTORIZADO UN PAGO A LA SOLICITUD. $ ".number_format( ($row[1]/$row[2]), 2, ".", "," ),
                    "idusuario" => $this->session->userdata('inicio_sesion')['id'],
                    "fecha" => $hora_autorizacion
                );

            }
            $resultados = count( $idsolicitudes );
            $idsolicitudes = implode( ",", $idsolicitudes);
            $this->db->insert_batch( 'autpagos', $solicitudes );
            /********************************************************************************************************************/
            //ESTA LINEA ELIMINA LOS PAGOS RECIEN INSERTADOS QUE PUEDAN PROVOCAR UN PAGO DOBLE A LA BD Y EVITA EL PAGOS DOBLES.
            /***********PAGOS NO PROGRAMADOS***********/
            // $this->db->query("DELETE FROM autpagos 
            // WHERE autpagos.idpago IN 
            // (
            //     SELECT idpago FROM solpagos 
            //     INNER JOIN ( SELECT autpagos.idsolicitud FROM autpagos WHERE autpagos.estatus = 0 ) autpagos ON autpagos.idsolicitud = solpagos.idsolicitud
            //     INNER JOIN ( 
            //         SELECT 
            //             MAX(autpagos.idpago) AS idpago, 
            //             autpagos.idsolicitud, 
            //             SUM(autpagos.cantidad) total 
            //         FROM autpagos 
            //         GROUP BY autpagos.idsolicitud
            //     ) autpagos1 ON autpagos1.idsolicitud = solpagos.idsolicitud
            //     WHERE autpagos1.total > solpagos.cantidad AND solpagos.programado IS NULL 
            // )
            // AND autpagos.estatus = 0");
            /***********PAGOS PROGRAMADOS*************/
            $this->db->query("DELETE FROM autpagos 
            WHERE autpagos.idpago IN 
            (
                SELECT idpago FROM ( SELECT * FROM solpagos WHERE solpagos.programado IS NOT NULL ) solpagos
                INNER JOIN ( SELECT autpagos.idsolicitud, idpago FROM autpagos WHERE autpagos.estatus = 0 ) autpagos ON autpagos.idsolicitud = solpagos.idsolicitud
                INNER JOIN ( 
                    SELECT 
                        COUNT(autpagos.idpago) nidpago, 
                        autpagos.idsolicitud
                    FROM autpagos 
                    GROUP BY autpagos.idsolicitud
                ) autpagos1 ON autpagos1.idsolicitud = solpagos.idsolicitud
                WHERE autpagos1.nidpago > IF( solpagos.programado < 7, ROUND( TIMESTAMPDIFF(MONTH, CONCAT( YEAR(solpagos.fecelab),'-',MONTH(solpagos.fecelab),'-', DAY(solpagos.fecha_fin) ), solpagos.fecha_fin) / solpagos.programado ), TIMESTAMPDIFF(WEEK, solpagos.fecelab, solpagos.fecha_fin) ) + 1
            )
            AND autpagos.estatus = 0");
            /********************************************************************************************************************/
            /*$this->db->query("DELETE FROM autpagos WHERE autpagos.idpago IN 
            (SELECT idpago FROM solpagos 
            INNER JOIN ( SELECT MAX(autpagos.idpago) AS idpago, autpagos.idsolicitud, SUM(autpagos.cantidad) total FROM autpagos GROUP BY autpagos.idsolicitud ) autpagos ON autpagos.idsolicitud = solpagos.idsolicitud
             WHERE total > IF(solpagos.programado IS NOT NULL, solpagos.cantidad * ( IF( solpagos.programado < 7, ROUND( TIMESTAMPDIFF(MONTH, CONCAT( YEAR(solpagos.fecelab),'-',MONTH(solpagos.fecelab),'-', DAY(solpagos.fecha_fin) ), solpagos.fecha_fin) / solpagos.programado ), TIMESTAMPDIFF(WEEK, solpagos.fecelab, solpagos.fecha_fin) ) + 1 ), solpagos.cantidad ) ) AND autpagos.estatus = 0 ");
            */



            $query="UPDATE solpagos 
                        INNER JOIN ( SELECT autpagos.idsolicitud, COUNT(autpagos.idpago) prealizados, SUM( autpagos.cantidad ) AS pagado 
                                    FROM autpagos 
                                    GROUP BY autpagos.idsolicitud ) AS pagado
                            ON pagado.idsolicitud = solpagos.idsolicitud
                        LEFT JOIN ( SELECT idfactura, idsolicitud, tipo_factura FROM facturas WHERE tipo_factura IN ( 1, 3 ) GROUP BY idsolicitud ) AS ft
                            ON ft.idsolicitud=solpagos.idsolicitud 
                    SET solpagos.idetapa = 
                        CASE 
                            WHEN solpagos.cantidad - pagado.pagado > 0 AND solpagos.programado IS NULL THEN
                                9
                            WHEN solpagos.programado IS NULL AND solpagos.tendrafac = 1 AND ( ft.idfactura IS NULL OR ft.tipo_factura = 1 ) THEN
                                10
                            WHEN solpagos.programado IS NOT NULL AND 
                                 solpagos.fecha_fin IS NOT NULL AND 
                                 prealizados < IF(  solpagos.programado < 7, 
                                                    ROUND( TIMESTAMPDIFF(MONTH, CONCAT( YEAR(solpagos.fecelab),'-',MONTH(solpagos.fecelab),'-', DAY(solpagos.fecelab) ),solpagos.fecha_fin) / solpagos.programado ), 
                                                    TIMESTAMPDIFF(WEEK, solpagos.fecelab, solpagos.fecha_fin) ) + 1 THEN
                                5
                            WHEN solpagos.programado IS NOT NULL AND solpagos.fecha_fin IS NULL THEN 
                                5 
                            ELSE 
                                11 
                        END 
                    WHERE solpagos.idsolicitud IN ( $idsolicitudes ) ;";
            $this->db->query($query);
            $this->db->insert_batch( 'logs', $comentarios );
            $this->db->trans_complete();
            $resultado = $this->db->trans_status();
        }
        
        echo json_encode( array( $resultado, $resultados.$idsolicitudes ) );
    }

    function PagoDevoluciones(){
        // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
        $resultado = false;

        if( $this->input->post('jsonApagar') ){
            $json = json_decode($this->input->post('jsonApagar'));
            $this->db->trans_start();

            $solicitudes = array();
            $comentarios = array();
            $idsolicitudes = array();
            
            foreach ($json as $row){

                $idsolicitudes[] = $row[0];

                $solicitudes[] = array(
                    "idsolicitud" => $row[0],
                    "cantidad" => $row[1],
                    "idrealiza" => $this->session->userdata('inicio_sesion')['id'],
                    "estatus" => 17
                );

                $comentarios[] = array(
                    "idsolicitud" => $row[0],
                    "tipomov" => "SE HA AUTORIZADO UN PAGO A LA SOLICITUD. $ ".number_format( $row[1], 2, ".", "," ),
                    "idusuario" => $this->session->userdata('inicio_sesion')['id'],
                    "fecha" => date("Y-m-d H:i:s")
                );

            }

            $resultados = count( $idsolicitudes );
            $idsolicitudes = implode( ",", $idsolicitudes);

            $this->db->insert_batch( 'autpagos', $solicitudes );
            //ESTA LINEA ELIMINA LOS PAGOS RECIEN INSERTADOS QUE PUEDAN PROVOCAR UN PAGO DOBLE A LA BD Y EVITA EL PAGOS DOBLES.
            $this->db->query("DELETE FROM autpagos WHERE autpagos.idpago IN (SELECT idpago FROM solpagos INNER JOIN ( SELECT MAX(autpagos.idpago) AS idpago, autpagos.idsolicitud, SUM(autpagos.cantidad) total FROM autpagos WHERE autpagos.estatus = 0 GROUP BY autpagos.idsolicitud ) autpagos ON autpagos.idsolicitud = solpagos.idsolicitud WHERE total > solpagos.cantidad)");
            $this->db->query("UPDATE solpagos set idetapa = CASE 
            WHEN solpagos.proyecto = 'DEVOLUCION - TRASPASO INTERNO' THEN 14 
            WHEN ( solpagos.nomdepto LIKE 'DEVOLUCION%' AND solpagos.proyecto NOT IN ( 'DEVOLUCION DOMICILIACION', 'DEVOLUCION GPH', 'DEVOLUCION OOAM' ) ) OR solpagos.proyecto = 'TRASPASO - DEVOLUCION' THEN 13 
            WHEN solpagos.nomdepto LIKE 'NOMINA%' THEN 10 ELSE 11 END WHERE idsolicitud IN ( $idsolicitudes ) AND idetapa IN ( 7 )");
            $this->db->update("autpagos", array( "estatus" => 0 ), "autpagos.idsolicitud NOT IN ( SELECT solpagos.idsolicitud FROM solpagos WHERE solpagos.idetapa = 13 ) AND autpagos.estatus = 17");
            $this->db->insert_batch( 'logs', $comentarios );
            $this->db->trans_complete();
            $resultado = $this->db->trans_status();
        }
        
        echo json_encode( array( $resultado, $resultados ) );
    }
    
    function PagarTotalCajaChica(){

        $respuesta = array( "resultado" => FALSE );

        if( $this->input->post('jsonApagar') ){
            $json = json_decode( $this->input->post('jsonApagar') );

            $comentarios = array();
            $solicitudes = array();
            $idsolicitudes = array();
            
            $query = "";

            foreach ( $json as $row ){
                
                $query .= $query == "" ? "" : ",";
                
                $idsolicitudes[] = $row->idsolicitud;
    
                foreach( explode( ",", $row->idsolicitud ) AS $cch ){
                    $comentarios[] = array(
                        "idsolicitud" => $cch,
                        "tipomov" => "SE AUTORIZÓ REPOSICION DE ESTA SOLICITUD",
                        "idusuario" => $this->session->userdata('inicio_sesion')['id'],
                        "fecha" => date("Y-m-d H:i:s")
                    );
                }

                $query .= "(
                    '".$row->idsolicitud."',
                    total_caja_chica( '".$row->idsolicitud."' ),
                    ".$this->session->userdata('inicio_sesion')['id'].",
                    ".$row->idempresa.",
                    ".$row->idresponsable.",
                    ".( $row ->nomdepto == 'TARJETA CREDITO' ? $row->idresponsable : 0).",
                    '".$row ->nomdepto."'
                )";

                /*
                $solicitudes[] = array(
                    "idsolicitud" => $row->idsolicitud,
                    "cantidad" => $row->totalpagar,
                    "idrealiza" => $this->session->userdata('inicio_sesion')['id'],
                    "idEmpresa" => $row->idempresa,
                    "idResponsable" => $row->idresponsable,
                    "idproveedor" => ( $row ->nomdepto == 'TARJETA CREDITO' ? $row->idresponsable : 0),
                    "nomdepto" => $row ->nomdepto
                );
                */
                //log_sistema( $this->session->userdata('inicio_sesion')['id'], $row->idsolicitud, "SE HA AUTORIZADO EL PAGO" );
            }
    
            $respuesta["resultado"] = $this->M_DireccionGeneral->autorizarPagoC( $query,  $comentarios,  implode( ",", $idsolicitudes) );
            
            $respuesta["data"] = $this->M_DireccionGeneral->obtenerSolCaja()->result_array();
        }

        echo json_encode( $respuesta );
   }

	function PagarTotalReembolsos(){

		$respuesta = array( "resultado" => FALSE );

		if( $this->input->post('jsonApagar') ){
			$json = json_decode( $this->input->post('jsonApagar') );

			$comentarios = array();
			$solicitudes = array();
			$idsolicitudes = array();

			$query = "";

			foreach ( $json as $row ){

				$query .= $query == "" ? "" : ",";

				$idsolicitudes[] = $row->idsolicitud;

				foreach( explode( ",", $row->idsolicitud ) AS $cch ){
					$comentarios[] = array(
						"idsolicitud" => $cch,
						"tipomov" => "SE AUTORIZÓ REPOSICION DE ESTA SOLICITUD",
						"idusuario" => $this->session->userdata('inicio_sesion')['id'],
						"fecha" => date("Y-m-d H:i:s")
					);
				}

				$query .= "(
                    '".$row->idsolicitud."',
                    total_caja_chica( '".$row->idsolicitud."' ),
                    ".$this->session->userdata('inicio_sesion')['id'].",
                    ".$row->idempresa.",
                    ".$row->idresponsable.",
                    ".( $row ->nomdepto == 'TARJETA CREDITO' ? $row->idresponsable : 0).",
                    '".$row ->nomdepto."'
                )";

				/*
				$solicitudes[] = array(
					"idsolicitud" => $row->idsolicitud,
					"cantidad" => $row->totalpagar,
					"idrealiza" => $this->session->userdata('inicio_sesion')['id'],
					"idEmpresa" => $row->idempresa,
					"idResponsable" => $row->idresponsable,
					"idproveedor" => ( $row ->nomdepto == 'TARJETA CREDITO' ? $row->idresponsable : 0),
					"nomdepto" => $row ->nomdepto
				);
				*/
				//log_sistema( $this->session->userdata('inicio_sesion')['id'], $row->idsolicitud, "SE HA AUTORIZADO EL PAGO" );
			}

			$respuesta["resultado"] = $this->M_DireccionGeneral->autorizarPagoC( $query,  $comentarios,  implode( ",", $idsolicitudes) );

			$respuesta["data"] = $this->M_DireccionGeneral->obtenerSolReembolsos()->result_array();
		}

		echo json_encode( $respuesta );
	}


    function PagoDeclinado(){
       $id = $this->input->post('id_sol');
       $obs = $this->input->post('Obervacion');
       echo json_encode($this->M_DireccionGeneral->declinarPago($id)); 
       chat($id,$obs,$this->session->userdata('inicio_sesion')['id']);
    }
    
   function PagoDeclinadoCH(){
       $id = $this->input->post('id_sol');
       $obs = $this->input->post('Obervacion');
       echo json_encode($this->M_DireccionGeneral->declinarPagoCH($id)); 
       chat($id,$obs,$this->session->userdata('inicio_sesion')['id']);
    }
    
    function pdf_recibo_pago( $numero_registros ){ //0 idsolicitud 1 cantidad
      
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
        $pdf->SetFont('Helvetica', '', 8, '', true);
        $pdf->SetMargins(20, 7, 15, true);

        $pdf->AddPage('L', 'LETTER LANDSCAPE');

        $totales = $this->db->query("SELECT empresas.abrev, SUM(autpagos.cantidad) AS sumatoria FROM solpagos INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa INNER JOIN (SELECT autpagos.idsolicitud, autpagos.cantidad FROM autpagos WHERE autpagos.idrealiza = '".$this->session->userdata('inicio_sesion')['id']."' ORDER BY autpagos.idpago DESC LIMIT $numero_registros) AS autpagos ON autpagos.idsolicitud = solpagos.idsolicitud GROUP BY empresas.idempresa");

        $facturas = $this->db->query("SELECT solpagos.folio,nomdepto, empresas.abrev, proveedores.nombre AS nombre_proveedor, solpagos.justificacion, autpagos.cantidad FROM autpagos
        INNER JOIN solpagos ON solpagos.idsolicitud = autpagos.idsolicitud
        INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa
        INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor        
        WHERE autpagos.idrealiza = '".$this->session->userdata('inicio_sesion')['id']."'
        ORDER BY empresas.abrev ASC, nombre_proveedor LIMIT $numero_registros");
        
 
        if( $facturas->num_rows() > 0 ){

            $pdf->Cell(0, 0, 'SISTEMA DE CUENTAS POR PAGAR - '.date("d/m/Y H:i:s"), 0, false, 'C', 0, '', 0, false, 'D', 'T');
            
            $total = 0;
            $linea_empresas = '<tr>';
            
            foreach( $totales->result() as $row ){
                $linea_empresas .= '<td>'.$row->abrev.': $ '.number_format( $row->sumatoria, 2, ".", "," ).'</td>';
                $total += $row->sumatoria;
            }

            $total = 0;
            $linea_empresas = '<tr>';
            
            foreach( $totales->result() as $row ){
                $linea_empresas .= '<td>'.$row->abrev.': $ '.number_format( $row->sumatoria, 2, ".", "," ).'</td>';
                $total += $row->sumatoria;
            }

            $linea_empresas .= '</tr>';

            $html ='
                    <table style="padding: 10px;witdh: 100%;" border="1">
                        <tr>
                            <td colspan="'.$totales->num_rows().'"><h1>AUTORIZACIONES DE PAGO</h1></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="'.$totales->num_rows().'">FECHA: '.date("d/m/Y").' '.date("H:i:s").'</td>
                        </tr>
                        <tr>
                            <td colspan="'.$totales->num_rows().'">AUTORIZADO POR: <b>'.$this->session->userdata("inicio_sesion")['nombres'] . ' ' . $this->session->userdata("inicio_sesion")['apellidos'].'</b></td>
                        </tr>
                        <tr>
                            <td colspan="'.$totales->num_rows().'">CANTIDAD TOTAL AUTORIZADA: $'.number_format( $total, 2, ".", "," ).'</td>
                        </tr>
                        '.$linea_empresas.'
                    </tale>
                    <table  border="1" style="padding:5px">
                        <tr>
                            <th style="width:7%; text-align:center;">EMPRESA</th>
                            <th style="width:28%; text-align:center;">PROVEEDOR A</th>
                            <th style="width:12%; text-align:center;">DEPARTAMENTO</th>
                            <th style="width:10%; text-align:center;">#FACTURA</th>
                            <th style="width:35%; text-align:center;">JUSTIFICACION</th>
                            <th style="width:10%; text-align:center;">CANTIDAD</th>
                        </tr>';
            $html2 = '';
            $i = 1;
            foreach( $facturas->result()  as $row ){
                $html2 .= '
                        <tr nobr="true">
                            <td>'.$row->abrev.'</td>
                            <td>'.$row->nombre_proveedor.'</td>
                            <td>'.$row->nomdepto.'</td>
                            <td style="text-align: right;">'.$row->folio.'</td>
                            <td style="font-size: 8px;">'.$row->justificacion.'</td>
                            <td style="text-align: right;">$'.number_format( $row->cantidad, 2, '.', ',' ).'</td>
                        </tr>';
                
                $i += 2;
            }
            $html = $html.$html2.'</table>';
            $pdf->writeHTMLCell(0, 0, $x = '', $y = '10', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
            $filename = "RELACION_PAGOS_AUTORIZADOS".date("hmsdym").".pdf";
            
            $pdf->Output(utf8_decode($filename), 'D');
            //return utf8_decode($filename);*/
        }
    }

    function datos_pdf($numero_registros){
        
        $facturas = $this->db->query("SELECT solpagos.folio,nomdepto, empresas.abrev, proveedores.nombre AS nombre_proveedor, solpagos.justificacion, autpagos.cantidad FROM autpagos
        INNER JOIN solpagos ON solpagos.idsolicitud = autpagos.idsolicitud
        INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa
        INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor        
        WHERE autpagos.idrealiza = '".$this->session->userdata('inicio_sesion')['id']."'
        ORDER BY empresas.abrev ASC, nombre_proveedor LIMIT $numero_registros");
        echo json_encode( array( "data" => $facturas->result_array() ));
        //echo json_encode( array( $facturas->result_array() ) );
    }

    function totales_pdf($numero_registros){
       
        $totales = $this->db->query("SELECT empresas.abrev, SUM(autpagos.cantidad) AS sumatoria FROM solpagos INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa INNER JOIN (SELECT autpagos.idsolicitud, autpagos.cantidad FROM autpagos WHERE autpagos.idrealiza = '".$this->session->userdata('inicio_sesion')['id']."' ORDER BY autpagos.idpago DESC LIMIT $numero_registros) AS autpagos ON autpagos.idsolicitud = solpagos.idsolicitud GROUP BY empresas.idempresa");
        
        echo json_encode( array( "data" => $totales->result_array() ));
        //echo json_encode( array( $totales->result_array() ) );
    }
    
    function isJson($string) {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    function obtenerSolCompras_Aut(){
        $resultado = array( "resultado" => TRUE, "data" => $this->M_DireccionGeneral->obtenerSolCompra_AutM( $this->input->post("fechaini"), $this->input->post("fechafin") )->result_array() );
        echo json_encode( $resultado );
    }

    function consultar_proveedor(){
        $data = json_decode( base64_decode( file_get_contents('php://input') ) );
        $resultado = [ "estatus" => 0, "mjs" => "No fue posible realizar movimiento solicitado."];
        
        if( $data ){
            $resultado = $this->M_DireccionGeneral->getTotalPagadoProv( $data->idproveedor );
        }

        echo base64_encode( json_encode( $resultado ) ); 
    }
}
