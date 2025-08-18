<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Historial extends CI_Controller {

    function __construct(){
        parent::__construct();
        if( !$this->session->userdata("inicio_sesion")/* || in_array( $this->session->userdata("inicio_sesion")['rol'], array( 'FP' ))*/ ){
            redirect("Login", "refresh");
        }
        else{
            $this->load->model(array('M_historial', 'Consulta', 'Solicitudes_solicitante', 'Lista_dinamicas'));
            $this->load->library('ExtraerNodoXml');
            $this->ExtraerNodoXml = new ExtraerNodoXml();
        }
    }

    public function index(){
        if( in_array( $this->session->userdata("inicio_sesion")['rol'], array('DA', 'AS', 'CA', 'CJ' )) || (in_array($this->session->userdata('inicio_sesion')["id"], array('2668', '2707'))) ){
            $this->load->view("v_historial_departamento");
        }else if( in_array( $this->session->userdata("inicio_sesion")['rol'], array('CT', 'CC', "CX")) ){
            $this->load->view("v_historial_contabilidad_egresos");
        }else if( in_array( $this->session->userdata("inicio_sesion")['rol'], array('AD','PV','SPV','CAD','CPV','GAD','CE', 'CC', 'CI','DIO', 'SAC', 'IOO', 'COO', 'AOO','PVM')) ){
            $this->load->view("v_historial_admon", [ "consulta" => "DEV" ]);
        }else{
           if( in_array( $this->session->userdata("inicio_sesion")['rol'], array( 'CP', 'SO' )) || $this->session->userdata('inicio_sesion')["id"] == '257' ){
                $this->load->view("v_historial_cp");
            }else{
                $this->load->view("v_historial");
            }
        }
    }

    /*
    public function devolucionesytraspasos(){
        if( in_array( $this->session->userdata("inicio_sesion")['rol'], array('AD','PV','CE', 'CC', "CX")) ){
            $this->load->view("v_historial_admon");
        }else{
            redirect("Login", "refresh");
        }
    }
    */
    /**HISTORIAL DE FACTORAJEES */
    public function factorajes(){
        if( in_array( $this->session->userdata("inicio_sesion")['rol'], array( 'SU', 'CP' ) ) ){
            $this->load->view("v_historial_factorajes");
        }else{
            redirect("Login", "refresh");
        }
    }

    public function tabla_factorajes(){
        if( in_array( $this->session->userdata("inicio_sesion")['rol'], array( 'SU', 'CP', 'CS' ) ) ){
            echo json_encode( array( "data" => $this->M_historial->getHSolFactorajes()->result_array() ) );
        }
    }

    public function reporte_factoraje(){

        if( isset( $_POST ) && !empty( $_POST ) ){
            
            var_dump( $_POST );
            $meses = array( 
                "Ene" => "01", "Feb" => "02", "Mar" => "03", "Abr" => "04", "May" => "05", "Jun" => "06", "Jul" => "07", 
                "Ago" => "08", "Sep" => "09", "Oct" => "10", "Nov" => "11", "Dic" => "12"
            );

            if( $this->input->post("finicio") ){
                $finicio = explode("/", $this->input->post("finicio") );
                $finicio = implode("-", array_reverse( $finicio ));
            }else{
                $finicio = '2019-01-01';
            }
            
            if( $this->input->post("ffinal") ){
                $ffinal = explode("/", $this->input->post("ffinal") );
                $ffinal = implode("-", array_reverse( $ffinal ));
            }else{
                $ffinal = date("Y-m-d");
            }
            
            /*
            if( $this->input->post("F FACTURA") ){
                $ffactura = explode("/", $this->input->post("F FACTURA"));
                $ffactura[1] =  $meses[ $ffactura[1] ];
                $ffactura = implode("-", array_reverse( $ffactura ));
            }else{
                $ffactura = '';
            }
            
            
            if( $this->input->post("VENCIMIENTO") ){
                $vencimiento = explode("/", $this->input->post("VENCIMIENTO"));
                $vencimiento[1] =  $meses[ $vencimiento[1] ];
                $vencimiento = implode("-", array_reverse( $vencimiento ));
            }else{
                $vencimiento = '';
            }
            */

            $idregistro = $this->input->post("#") ? $this->input->post("#") : '';
            $proyecto = $this->input->post("PROYECTO") ? $this->input->post("PROYECTO") : '';
            $proveedor = $this->input->post("PROVEEDOR") ? $this->input->post("PROVEEDOR") : '';
            $empresa = $this->input->post("EMPRESA") ? $this->input->post("EMPRESA") : '';
            $mpago = $this->input->post("F_PAGO") ? $this->input->post("F_PAGO") : '';
            $foliofiscal = $this->input->post("FOLIO/FISCAL") ? $this->input->post("FOLIO/FISCAL") : '';
            $departamento = $this->input->post("DEPARTAMENTO") ? $this->input->post("DEPARTAMENTO") : '';
            $fdispercion = $this->input->post("FECHA_DISPERSION") ? $this->input->post("FECHA_DISPERSION") : '';
            $cantidad = $this->input->post("CANTIDAD") ? str_replace(array( ',' ), '', $this->input->post("CANTIDAD") ) : '';
            $pagado = $this->input->post("PAGADO") ? str_replace(array( ',' ), '', $this->input->post("PAGADO")) : '';

            /*
            ( '$vencimiento' = '' OR fvencimiento LIKE '%$vencimiento%' ) AND
            ( '$fdispercion' = '' OR fechaDis LIKE '%$fdispercion%' ) AND
            */

            $reporte = $this->db->query("SELECT * FROM listado_factoraje WHERE 
            ( '$idregistro' = '' OR idsolicitud LIKE '%$idregistro%' ) AND
            ( '$proyecto' = '' OR proyecto LIKE '%$proyecto%' ) AND
            ( '$proveedor' = '' OR nombre LIKE '%$proveedor%' ) AND
            ( '$empresa' = '' OR abrev LIKE '%$empresa%' ) AND
            ( '$mpago' = '' OR metoPago LIKE '%$mpago%' ) AND
            ( '$foliofiscal' = '' OR CONCAT( folio,'/', ffiscal ) LIKE '%$foliofiscal%' ) AND 
            ( '$departamento' = '' OR nomdepto LIKE '%$departamento%' ) AND
            ( '$cantidad' = '' OR cantidad LIKE '%$cantidad%' ) AND
            ( '$pagado' = '' OR pagado LIKE '%$pagado%' ) AND
            fecelab BETWEEN '$finicio' AND '$ffinal'");

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

            $i = 1;

            $reader->getActiveSheet()->setCellValue('A'.$i, '# SOLICITUD');
            $reader->getActiveSheet()->setCellValue('B'.$i, 'ETAPA');
            $reader->getActiveSheet()->setCellValue('C'.$i, 'CONDOMINIO');
            $reader->getActiveSheet()->setCellValue('D'.$i, 'PROYECTO');
            $reader->getActiveSheet()->setCellValue('E'.$i, 'PROVEEDOR');
            $reader->getActiveSheet()->setCellValue('F'.$i, 'EMPRESA');
            $reader->getActiveSheet()->setCellValue('G'.$i, 'FORMA DE PAGO');
            $reader->getActiveSheet()->setCellValue('H'.$i, 'FOLIO/FISCAL');
            $reader->getActiveSheet()->setCellValue('I'.$i, 'F FACTURA');
            $reader->getActiveSheet()->setCellValue('J'.$i, 'F PUBLICACIÓN');
            $reader->getActiveSheet()->setCellValue('K'.$i, 'VENCIMIENTO');
            $reader->getActiveSheet()->setCellValue('L'.$i, 'DEPARTAMENTO');
            $reader->getActiveSheet()->setCellValue('M'.$i, 'FECHA DISPERSIÓN');
            $reader->getActiveSheet()->setCellValue('N'.$i, 'FECHA COBRO');
            $reader->getActiveSheet()->setCellValue('O'.$i, 'CANTIDAD');
            $reader->getActiveSheet()->setCellValue('P'.$i, 'PAGADO');
            $reader->getActiveSheet()->setCellValue('Q'.$i, 'JUSTIFICACION');
            $reader->getActiveSheet()->setCellValue('R'.$i, 'CAPTURISTA');
            $reader->getActiveSheet()->setCellValue('S'.$i, 'DIRECTOR');
            $reader->getActiveSheet()->setCellValue('T'.$i, 'ESTATUS');

            $reader->getActiveSheet()->getStyle('A1:T1')->applyFromArray($encabezados);

            $reader->getActiveSheet()->getColumnDimension('A')->setWidth(15);
            $reader->getActiveSheet()->getColumnDimension('B')->setWidth(15);
            $reader->getActiveSheet()->getColumnDimension('C')->setWidth(50);
            $reader->getActiveSheet()->getColumnDimension('D')->setWidth(25);
            $reader->getActiveSheet()->getColumnDimension('E')->setWidth(15);
            $reader->getActiveSheet()->getColumnDimension('F')->setWidth(15);
            $reader->getActiveSheet()->getColumnDimension('G')->setWidth(25);
            $reader->getActiveSheet()->getColumnDimension('H')->setWidth(15);
            $reader->getActiveSheet()->getColumnDimension('I')->setWidth(25);
            $reader->getActiveSheet()->getColumnDimension('J')->setWidth(20);
            $reader->getActiveSheet()->getColumnDimension('K')->setWidth(50);
            $reader->getActiveSheet()->getColumnDimension('L')->setWidth(20);
            $reader->getActiveSheet()->getColumnDimension('M')->setWidth(20);
            $reader->getActiveSheet()->getColumnDimension('N')->setWidth(20);
            $reader->getActiveSheet()->getColumnDimension('O')->setWidth(20);
            $reader->getActiveSheet()->getColumnDimension('P')->setWidth(20);
            $reader->getActiveSheet()->getColumnDimension('Q')->setWidth(20);
            $reader->getActiveSheet()->getColumnDimension('R')->setWidth(20);
            $reader->getActiveSheet()->getColumnDimension('T')->setWidth(20);

            $i+=1;
            if( $reporte->num_rows() > 0  ){

                foreach( $reporte->result() as $row ){
                    $reader->getActiveSheet()->setCellValue('A'.$i, $row->idsolicitud);
                    $reader->getActiveSheet()->setCellValue('B'.$i, $row->setapa);
                    $reader->getActiveSheet()->setCellValue('C'.$i, $row->condominio);
                    $reader->getActiveSheet()->setCellValue('D'.$i, $row->proyecto);
                    $reader->getActiveSheet()->setCellValue('E'.$i, $row->nombre);
                    $reader->getActiveSheet()->setCellValue('F'.$i, $row->abrev);
                    $reader->getActiveSheet()->setCellValue('G'.$i, $row->metoPago);
                    $reader->getActiveSheet()->setCellValue('H'.$i, $row->folio."/".$row->ffiscal);
                    $reader->getActiveSheet()->setCellValue('I'.$i, $row->fecelab);
                    $reader->getActiveSheet()->setCellValue('J'.$i, $row->fpublicacion); 
                    $reader->getActiveSheet()->setCellValue('K'.$i, $row->fvencimiento); 
                    $reader->getActiveSheet()->setCellValue('L'.$i, $row->nomdepto);
                    $reader->getActiveSheet()->setCellValue('M'.$i, $row->fechaDis? $row->fechaDis : ''); 
                    $reader->getActiveSheet()->setCellValue('N'.$i, $row->ucobro? $row->ucobro : '');
                    $reader->getActiveSheet()->setCellValue('O'.$i, $row->cantidad);
                    $reader->getActiveSheet()->setCellValue('P'.$i, $row->pagado);
                    $reader->getActiveSheet()->setCellValue('Q'.$i, $row->justificacion);
                    $reader->getActiveSheet()->setCellValue('R'.$i, $row->ncap);
                    $reader->getActiveSheet()->setCellValue('S'.$i, $row->ndir);
                    $reader->getActiveSheet()->setCellValue('T'.$i, $row->etapa);

                    $reader->getActiveSheet()->getStyle("A$i:R$i")->applyFromArray($informacion);
                    $i+=1;

                }
            }

            $writer =  \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($reader, 'Xls');
            ob_end_clean();

            $temp_file = tempnam(sys_get_temp_dir(), 'PHPExcel');
            $writer->save($temp_file);
            header("Content-Disposition: attachment; filename=REPORTE_FACTORAJES_BANREGIO_BANBAJIO.xls");
 
            readfile($temp_file); 
            unlink($temp_file);
        
        }
    }

    /**************************/

    public function p_programados(){
        if( (in_array( $this->session->userdata("inicio_sesion")['rol'], array('CP', 'DP', 'DG', 'SU')) || ($this->session->userdata("inicio_sesion")['rol'] == 'DA' && in_array($this->session->userdata("inicio_sesion")['id'], array(1868, 2665)))) || (in_array( $this->session->userdata("inicio_sesion")['id'], [ 2685, 2681, 2409 ] )) )
            $this->load->view("vista_programados_cxp");
        else
            redirect("Login", "refresh");
    }
	public function historialreembolsos(){
		if( in_array( $this->session->userdata("inicio_sesion")['rol'], array( 'CP', 'DP', 'DG', 'SU' ) ) )
			$this->load->view("vista_reembolsos_cxp");
		else
			redirect("Login", "refresh");
	}
	public function historialViaticos(){
		if( in_array( $this->session->userdata("inicio_sesion")['rol'], array( 'CP', 'DP', 'DG', 'SU', 'CT', 'CX', 'CE', 'CC' )) )
			$this->load->view("vista_viaticos_cxp");
		else
			redirect("Login", "refresh");
	}


    public function agregar_abono($saldo, $justificacion, $solicitud){
        // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
        if( in_array( $this->session->userdata("inicio_sesion")['rol'], array( 'CP' )) ){
            $cons_est = $this->db->query("SELECT  solpagos.cantidad, IFNULL(liquidado.pagado, 0) AS pagado, solpagos.metoPago, solpagos.idetapa  FROM solpagos LEFT JOIN ( SELECT *, autpagos.estatus AS estatus_pago, MAX(autpagos.fecreg) FROM autpagos GROUP BY autpagos.idsolicitud ) AS pago_generado ON pago_generado.idsolicitud = solpagos.idsolicitud INNER JOIN ( SELECT usuarios.idusuario, CONCAT( usuarios.nombres,' ', usuarios.apellidos ) AS nombre_completo FROM usuarios ) AS capturista ON capturista.idusuario = solpagos.idusuario LEFT JOIN (SELECT autpagos.idsolicitud, SUM( autpagos.cantidad ) AS pagado FROM autpagos GROUP BY autpagos.idsolicitud) AS liquidado ON solpagos.idsolicitud = liquidado.idsolicitud where solpagos.idsolicitud IN (".$solicitud.")"); 
    
            $new_justificacion = str_replace(array("/","-",".",",","_","","%20")," ",(TRIM($justificacion)));

            $met_pago = $cons_est->row()->metoPago;
            $estatus_sol = $cons_est->row()->idetapa;
            $saldo_total = $cons_est->row()->cantidad-$cons_est->row()->pagado;
            $saldo_total_2 =  round($saldo_total,3);
        
            $this->db->query("INSERT INTO autpagos (idsolicitud, idfactura, idrealiza, cantidad, fecreg, fechacp, estatus, estatus_factura, reg_factura, tipoPago, formaPago, referencia, fechaOpe, tipoCambio, fecha_pago, descarga, fechDesc, fechaDis, motivoEspera, fecha_cobro) VALUES (".$solicitud.", NULL, ".$this->session->userdata("inicio_sesion")['id'].", ".$saldo.", '0000-00-00 00:00:00', NULL, 16, NULL, NULL, NULL,  '".$met_pago."', 'ABONO', '0000-00-00', NULL, '0000-00-00', NULL, NULL, NULL, '', NULL)");

            if($saldo_total_2>$saldo){
                $this->db->query("UPDATE solpagos SET idetapa  = 9 where idsolicitud IN (".$solicitud.")");
            }

            if($saldo_total_2 == $saldo){
                $this->db->query("UPDATE solpagos INNER JOIN ( SELECT autpagos.idsolicitud, SUM( autpagos.cantidad ) AS pagado FROM autpagos GROUP BY autpagos.idsolicitud ) AS pagado ON pagado.idsolicitud = solpagos.idsolicitud SET solpagos.idetapa = CASE WHEN solpagos.cantidad - pagado.pagado > 0 THEN 9 WHEN ( solpagos.idsolicitud NOT IN (SELECT idsolicitud FROM facturas WHERE tipo_factura = 3) AND solpagos.idsolicitud IN (SELECT idsolicitud FROM facturas WHERE tipo_factura = 1) ) OR ( solpagos.tendrafac = 1 AND solpagos.idsolicitud NOT IN (SELECT idsolicitud FROM facturas ) )THEN 10 ELSE 11 END WHERE solpagos.idsolicitud IN (".$solicitud.")");            
            }

            chat( $solicitud, $new_justificacion, $this->session->userdata("inicio_sesion")['id'] );
            log_sistema($this->session->userdata("inicio_sesion")['id'], $solicitud, "SE AGREGO ABONO DESDE HISTORIAL, $ ".number_format( $saldo, 2, ".", "," ) );
        }
    } 

    public function historial_pagos(){
        if( in_array( $this->session->userdata("inicio_sesion")['rol'], array('SU', 'DG', 'DP')) )
            $this->load->view("v_historial_transferencias_ori");
        elseif( in_array( $this->session->userdata("inicio_sesion")['rol'], array('FP')) )
            $this->load->view("v_historial_transferencias_fp");
        else
            $this->load->view("v_historial_transferencias");
    }

    /*INFORMACIO DE LAS TABLAS SEGUN LOS ROLES QUE SE SOLICITAN*/
    //ADMINISTRATIVO
    public function TablaHPagosAdministrativo(){
        ini_set('memory_limit','-1');
        echo json_encode( array( "data" => $this->M_historial->getHistorialPagosAdm($this->input->post("finicial"), $this->input->post("ffinal"))->result_array() ) );
    }
    //DEPARTAMENTOS
    public function TablaHPagosDepartmento(){
        ini_set('memory_limit','-1');
        $data = $this->M_historial->getHistorialPagosDepto()->result_array();
        echo json_encode( array( "data" => $data ) );
    }
    //DIRECTIVOS
    public function TablaHPagosDirectivos(){
        ini_set('memory_limit','-1');
        echo json_encode( array( "data" => $this->M_historial->getHistorialPagosDir($this->input->post())->result_array() ) );
    }
    /*************************************/

    public function ver_programados(){
        $this->load->model("M_historial");
        $dat =  $this->M_historial->get_solicitudes_programadas( $this->input->post("etapas") ? $this->input->post("etapas") : "0, 11, 30, 2" )->result_array();
        echo json_encode( array( "data" => $dat ));
    }

    public function obtener_cantidad($idsolicitud){
        echo json_encode( array( "data" => $this->M_historial->getDatosSolicitud($idsolicitud)->result_array() ) );
    }

 
    public function TablaHistorialSolicitudes(){
         /** @author DANTE ALDAIR GUERRERO ALDANA <coordinador6.desarrollo@ciudadmaderas.com> | FECHA: 04-06-2025 | MODIFICACION AL CARGAR DATATABLE*/
        $fechaInicio = $this->input->post('fechaInicio');
        $fechaFin = $this->input->post('fechaFinal');
        $datos = $this->M_historial->getHistorialTablaSol($fechaInicio, $fechaFin)->result_array();
        echo json_encode( array( "data" => $datos, "rol" => $this->session->userdata("inicio_sesion")['rol'] ) );
    }

    //HISTORAL DISEÑADO PARA CONTABILIDAD PROVEEDORES Y CAJAS CHICAS
    public function ThistorialContabilidad(){
        $finicio = $ffinal = "";
        
        if( $this->input->post("fechaInicio") ){
            $finicio = $this->input->post("fechaInicio");
        }

        if( $this->input->post("fechaFin") ){
            $ffinal = $this->input->post("fechaFin");
        }
        $data = $this->M_historial->getTHistorialConta( $this->input->post("tipo_reporte"), $finicio, $ffinal )->result_array();
        echo json_encode( array( "data" => $data, $finicio, $ffinal ) );
    }

    public function TablaHistorialSolicitudesOri(){
        echo json_encode( array( "data" => $this->M_historial->getHistorialTablaSolOri($this->input->post() )->result_array(), "rol" => $this->session->userdata("inicio_sesion")['rol'] ) );
    }
    

    //HISTORIAL DE DE SOLICITUDES PARA CUENTAS POR PAGAR Y SU - PAGO A PROVEEDOR;
    public function TablaHistorialSolicitudesA(){
        $data = $this->M_historial->getHistorialTablaSolA( $this->input->post("finicial"), $this->input->post("ffinal") )->result_array();
        echo json_encode( array( "data" => $data ) );
    }

    //HISTORIAL DE DE SOLICITUDES PARA CUENTAS POR PAGAR Y SU - PAGO A PROVEEDOR;
    public function TablaHistorialSolicitudesCompleta(){
        echo json_encode( array( "data" => $this->M_historial->getHistorialTablaSolCxp()->result_array() ) );
    }

    //HISTORIAL DE DE SOLICITUDES PARA CUENTAS POR PAGAR - PAGO CAJA CHICA Y SU;//
    public function TablaHistorialSolicitudesB(){
        echo json_encode( array( "data" => $this->M_historial->getHistorialTablaSolCajachica($this->input->post("finicial"),$this->input->post("ffinal"))->result_array() ) );
    }
    
    //HISTORIAL DE DE SOLICITUDES PARA CUENTAS POR PAGAR - TDC;
    public function TablaHistorialSolicitudesTDC(){
        echo json_encode( array( "data" => $this->M_historial->getHistorialTablaSolTDC($this->input->post("finicial"),$this->input->post("ffinal"))->result_array() ) );
    }

    public function TablaHistorialSolicitudesC(){
        echo json_encode( array( "data" => $this->M_historial->getHistorialTablaSolC()->result_array() ) );
    }

    public function TablaHistorialSolicitudesP(){
        echo json_encode( array( "data" => $this->M_historial->getHistorialTablaSolP()->result_array() ) );
    }

    public function TablaHistorialSolicitudesPV(){
        /** Ticket #76788 Folios en reporte de trámites PV | FECHA: 18-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
        $meses = $this->input->post("mesesSeleccionados");
        $anio = $this->input->post("anio");
        /** FIN Ticket #76788 Folios en reporte de trámites PV | FECHA: 18-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
        echo json_encode( ["data" => $this->M_historial->getHistorialTablaSolPV($meses, $anio)->result_array()]) ;
    }


    public function historialcch(){
        $this->load->view("vista_historial_cch");
    }

    public function historialtdc(){
        $this->load->view("vista_historial_tdc");
    }

     public function historial_autorizacion(){
        if( in_array( $this->session->userdata("inicio_sesion")['rol'], array('DG', 'SU', 'CP', 'DP')) ){
            $this->load->view("v_historial_autorizaciones");
        }
    }
    /***PARA LOS HISTORIALES DE CAJA CHICAS**/
    /***LO PAGADO**/
    public function tabla_cajas_chicas(){
        $this->load->model(["Mproyectos"]);
        $tipo_rembolso = $this->input->post("TIPO_REMBOLSO") ? $this->input->post("TIPO_REMBOLSO") : NULL;
        echo json_encode( array( 
            "data" => $this->M_historial->cajas_chicas_pagadas( $tipo_rembolso )->result_array(),
            "permiso_desglose" => in_array( $this->session->userdata("inicio_sesion")['id'], [ 94, 93 ]  ),
            "proyectos" => $this->Mproyectos->getProyectoAdministrarActivo()->result_array()
        ));
    }

    /***EN CERRADAS**/
    public function tabla_cajas_chicas_cerradas(){
        $data = $this->M_historial->caja_chicas_cerradas($this->input->post())->result_array();
        echo json_encode( array( "data" => $data ));
    }

    /***EN TRANSITO**/
    public function tabla_cajas_chicas_transito(){
        echo json_encode( array( "data" => $this->M_historial->cajas_chicas_transito($this->input->post())->result_array() ));
    }
    /************************************/

        /***PARA LOS HISTORIALES DE TDC**/
    /***LO PAGADO**/
    public function tabla_tdc(){
        $data = $this->M_historial->tdc_pagadas()->result_array();
        echo json_encode( array( "data" => $data ));
    }
    /***EN TRANSITO**/
    public function tabla_tdc_transito(){
        echo json_encode( array( "data" => $this->M_historial->tdc_transito()->result_array() ));
    }
    /************************************/

    public function carga_cajas_chicas(){
        echo json_encode( $this->M_historial->get_solicitudes_nuevas_caja_chica_propuesta( $this->input->post("idcajachicas") ) );
    }
 

    function tabla_cajas_porpagar(){
        
        $data = $this->M_historial->obtenerSolCaja();
        
        if( !empty($data) ){
            
            $data = $data->result_array();
        
            for( $i = 0; $i < count($data); $i++ ){
                $data[$i]['pa'] = 0;
                $data[$i]['solicitudes'] = $this->M_historial->getSolicitudesCCH( $data[$i]['ID']);
            }
        }
        
        echo json_encode( array( "data" => $data ));
    }





    function tabla_cajas_cerradas(){
        
        $data = $this->M_historial->obtenerSolCerradas();
        
        if( !empty($data) ){
            
            $data = $data->result_array();
        
            for( $i = 0; $i < count($data); $i++ ){
                $data[$i]['pa'] = 0;
                $data[$i]['solicitudes'] = $this->M_historial->getSolicitudesCerCH( $data[$i]['ID']);
            }
        }
        
        echo json_encode( array( "data" => $data ));
    }


    function tabla_historial_clientes(){
        
    }
    


    public function cerrar_caja(){
        // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
        $json['resultado'] = FALSE;
        if( $this->input->post("id_solicitudes") ){
            $this->load->model("Solicitudes_cxp");
            
            $id_solicitudes = $this->input->post("id_solicitudes");
        

            $this->db->query('UPDATE solpagos SET idetapa = 31 WHERE idsolicitud in('.$id_solicitudes.')');


            $json['resultado'] = TRUE;

        }

        echo json_encode( $json );
    }

    public function tabla_autorizaciones(){
        if( in_array( $this->session->userdata("inicio_sesion")['rol'], array( 'DG', 'SU', 'CP', 'DP' )) ){
            $data = $this->M_historial->personal_autorizaciones()->result_array();
            echo json_encode( array( "data" => $data ));
        }
    }

    public function descargarExcel($valor1, $valor2){

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
        
        //CONSULTA ORIGINAL
        //$reporte = $this->db->query("SELECT solpagos.idsolicitud, empresas.abrev, proveedores.nombre, solpagos.nomdepto, solpagos.folio, solpagos.justificacion, autpagos.cantidad FROM autpagos INNER JOIN solpagos ON solpagos.idsolicitud = autpagos.idsolicitud INNER JOIN usuarios ON usuarios.idusuario = autpagos.idrealiza INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa inner join proveedores on proveedores.idproveedor = solpagos.idProveedor WHERE autpagos.idrealiza='".$valor1."' and autpagos.fecreg like '".$valor2."%' ORDER BY empresas.abrev" );
        //CONSULTA PARA GENERACION REPORTE PARA EN BASE AUTOPAGOS Y AUTOPAGOS DE CAJA CHICA
        //CONSULTA ORIGINAL USADA HASTA EL 9 DE OCT 2019
        $reporte = $this->db->query("SELECT 
            *,
            CONCAT(usuarios.nombres, ' ', usuarios.apellidos) AS autorizacion 
            FROM ( 
            ( SELECT 
                IF(solpagos.programado is not null 
                    OR solpagos.programado >= 0 
                    OR solpagos.programado ='', 'PROGRAMADO', '') AS progr, 
                ifnull(solpagos.proyecto, prdp.nombre) proyecto,
                ofse.nombre oficina,
                tsp.nombre servicioPartida,
                solpagos.metoPago, 
                solpagos.idsolicitud, 
                empresas.abrev, 
                proveedores.nombre, 
                solpagos.nomdepto, 
                solpagos.folio, 
                solpagos.justificacion, 
                autpagos.cantidad, 
                autpagos.fecreg 
            FROM autpagos 
            INNER JOIN solpagos ON solpagos.idsolicitud = autpagos.idsolicitud 
            INNER JOIN usuarios ON usuarios.idusuario = autpagos.idrealiza 
            INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa 
            inner join proveedores on proveedores.idproveedor = solpagos.idProveedor 
            LEFT JOIN solicitud_proyecto_oficina spo ON solpagos.idsolicitud = spo.idsolicitud
            LEFT JOIN oficina_sede ofse ON spo.idOficina = ofse.idOficina AND ofse.estatus = 1
            LEFT JOIN proyectos_departamentos prdp ON spo.idProyectos = prdp.idProyectos AND prdp.estatus = 1
            LEFT JOIN tipo_servicio_partidas tsp ON spo.idTipoServicioPartida = tsp.idTipoServicioPartida AND tsp.estatus = 1
            WHERE autpagos.idrealiza='$valor1' and autpagos.fecreg like '$valor2%' ) 
        UNION 
        ( SELECT 
            '-' AS progr, 
            'CAJA CHICA' proyecto,
            'N/A' oficina,
            'N/A' servicioPartida, 
            autpagos_caja_chica.tipoPago metoPago,
             'NA' AS idsolicitud, 
             empresas.abrev, 
             CONCAT(usuarios.nombres, ' ', usuarios.apellidos) AS nombre, 
             autpagos_caja_chica.nomdepto, 'NA' AS folio, 
             'PAGO DE CAJA CHICA' AS justificacion, 
             autpagos_caja_chica.cantidad, 
             autpagos_caja_chica.fecreg 
            FROM autpagos_caja_chica INNER JOIN usuarios ON usuarios.idusuario = autpagos_caja_chica.idResponsable INNER JOIN empresas ON empresas.idempresa = autpagos_caja_chica.idEmpresa WHERE autpagos_caja_chica.nomdepto != 'TARJETA CREDITO' AND autpagos_caja_chica.idrealiza = '$valor1' and autpagos_caja_chica.fecreg like '$valor2%' )
        UNION
        (
            SELECT 
                '-' AS progr,
                'TDC' proyecto,
                'N/A' oficina,
                'N/A' servicioPartida,  
                autpagos_caja_chica.tipoPago metoPago,
                'NA' AS idsolicitud, 
                empresas.abrev,
                p.nombre nombre,
                autpagos_caja_chica.nomdepto,
                'NA' AS folio, 
                'PAGO DE TARJETA DE CREDITO' AS justificacion,
                autpagos_caja_chica.cantidad, 
                autpagos_caja_chica.fecreg 
            FROM autpagos_caja_chica 
            INNER JOIN empresas ON autpagos_caja_chica.idEmpresa = empresas.idempresa 
            INNER JOIN proveedores p ON p.idproveedor = autpagos_caja_chica.idResponsable 
            WHERE autpagos_caja_chica.nomdepto = 'TARJETA CREDITO' AND autpagos_caja_chica.idrealiza = '$valor1' and autpagos_caja_chica.fecreg like '$valor2%'
        ) ) AS autpagos, ( SELECT usuarios.nombres, usuarios.apellidos FROM usuarios WHERE usuarios.idusuario = '$valor1' ) usuarios ORDER BY abrev, fecreg" );
        //CONSULTA PARA LA GENERACION DE REPORTE AUOTIZADON DE LAS 12 PM A LAS 11:59:59 AM
        //$reporte = $this->db->query("SELECT * FROM ( ( SELECT solpagos.idsolicitud, empresas.abrev, proveedores.nombre, solpagos.nomdepto, solpagos.folio, solpagos.justificacion, autpagos.cantidad, autpagos.fecreg FROM autpagos INNER JOIN solpagos ON solpagos.idsolicitud = autpagos.idsolicitud INNER JOIN usuarios ON usuarios.idusuario = autpagos.idrealiza INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa inner join proveedores on proveedores.idproveedor = solpagos.idProveedor WHERE autpagos.idrealiza='$valor1' and DATE_ADD( autpagos.fecreg, INTERVAL 12 HOUR) like '$valor2%' ) UNION ( SELECT 'NA' AS idsolicitud, empresas.abrev, CONCAT(usuarios.nombres, ' ', usuarios.apellidos) AS nombre, autpagos_caja_chica.nomdepto, 'NA' AS folio, 'PAGO DE CAJA CHICA' AS justificacion, autpagos_caja_chica.cantidad, autpagos_caja_chica.fecreg FROM autpagos_caja_chica INNER JOIN usuarios ON usuarios.idusuario = autpagos_caja_chica.idResponsable INNER JOIN empresas ON empresas.idempresa = autpagos_caja_chica.idEmpresa WHERE autpagos_caja_chica.idrealiza='$valor1' and  DATE_ADD( autpagos_caja_chica.fecreg, INTERVAL 12 HOUR) like '$valor2%' ) ) AS autpagos ORDER BY abrev, fecreg" );

       $departamentos = in_array($this->session->userdata("inicio_sesion")['depto'], array('CONSTRUCCION', 'JARDINERIA', 'OOAM', 'OOAM TECNICO', 'OOAM ADMINISTRATIVO'));
       $usuario_name =  $this->db->query("SELECT nombres FROM usuarios WHERE idusuario='".$valor1."'" );

 
                $i = 1;
 
                $reader->getActiveSheet()->setCellValue('A'.$i, 'ID SOLICITUD');
                $reader->getActiveSheet()->setCellValue('B'.$i, 'EMPRESA');
                $reader->getActiveSheet()->setCellValue('C'.$i, 'PROVEEDOR');
                $reader->getActiveSheet()->setCellValue('D'.$i, 'DEPARTAMENTO');
                $reader->getActiveSheet()->setCellValue('E'.$i, 'PROYECTO');
                if(!$departamentos ){
                    $reader->getActiveSheet()->setCellValue('F'.$i, 'OFICINA');
                    $reader->getActiveSheet()->setCellValue('G'.$i, 'SERVICIO PARTIDA');
                }
                $reader->getActiveSheet()->setCellValue(($departamentos ? 'F' : 'H').$i, 'METDO PAGO');
                $reader->getActiveSheet()->setCellValue(($departamentos ? 'G' : 'I').$i, '#FACTURA');
                $reader->getActiveSheet()->setCellValue(($departamentos ? 'H' : 'J').$i, 'CANTIDAD');
                $reader->getActiveSheet()->setCellValue(($departamentos ? 'I' : 'K').$i, 'REGISTRO');
                $reader->getActiveSheet()->setCellValue(($departamentos ? 'J' : 'L').$i, 'PROGRAMADO');
                $reader->getActiveSheet()->setCellValue(($departamentos ? 'K' : 'M').$i, 'JUSTIFICACIÓN');
                $reader->getActiveSheet()->setCellValue(($departamentos ? 'L' : 'N').$i, 'AUTORIZADO POR');
 
                $reader->getActiveSheet()->getStyle(($departamentos ? 'A1:K1' : 'A1:N1'))->applyFromArray($encabezados);
                // $reader->getActiveSheet()->getColumnDimension('A1')->setAutoSize(true);
                $reader->getActiveSheet()->getColumnDimension('A')->setWidth(15);
                $reader->getActiveSheet()->getColumnDimension('B')->setWidth(15);
                $reader->getActiveSheet()->getColumnDimension('C')->setWidth(50);
                $reader->getActiveSheet()->getColumnDimension('D')->setWidth(25);
                $reader->getActiveSheet()->getColumnDimension('E')->setWidth(15);
                $reader->getActiveSheet()->getColumnDimension('F')->setWidth(15);
                $reader->getActiveSheet()->getColumnDimension('G')->setWidth(25);
                $reader->getActiveSheet()->getColumnDimension('H')->setWidth(15);
                $reader->getActiveSheet()->getColumnDimension('I')->setWidth(25);
                $reader->getActiveSheet()->getColumnDimension('J')->setWidth(20);
                $reader->getActiveSheet()->getColumnDimension('K')->setWidth(50);
                $reader->getActiveSheet()->getColumnDimension('L')->setWidth(20);
 
                $i+=1;
                    if( $reporte->num_rows() > 0  ){

                        foreach( $reporte->result() as $row ){
                            $reader->getActiveSheet()->setCellValue('A'.$i, $row->idsolicitud);
                            $reader->getActiveSheet()->setCellValue('B'.$i, $row->abrev);
                            $reader->getActiveSheet()->setCellValue('C'.$i, $row->nombre);
                            $reader->getActiveSheet()->setCellValue('D'.$i, $row->nomdepto);
                            $reader->getActiveSheet()->setCellValue('E'.$i, $this->session->userdata("inicio_sesion")['rol'] == 'CP' && !$row->oficina ? '' : $row->proyecto);
                            if(!$departamentos ){
                                $reader->getActiveSheet()->setCellValue('F'.$i, $row->oficina);
                                $reader->getActiveSheet()->setCellValue('G'.$i, $this->session->userdata("inicio_sesion")['rol'] == 'CP' && !$row->oficina ? $row->proyecto : $row->servicioPartida ); 
                            }   
                            $reader->getActiveSheet()->setCellValue(($departamentos ? 'F' : 'H').$i, $row->metoPago);
                            $reader->getActiveSheet()->setCellValue(($departamentos ? 'G' : 'I').$i, $row->folio); 
                            $reader->getActiveSheet()->setCellValue(($departamentos ? 'H' : 'J').$i, number_format($row->cantidad, 2, '.', ''));
                            $reader->getActiveSheet()->setCellValue(($departamentos ? 'I' : 'K').$i, $row->fecreg); 
                            $reader->getActiveSheet()->setCellValue(($departamentos ? 'J' : 'L').$i, $row->progr);
                            $reader->getActiveSheet()->setCellValue(($departamentos ? 'K' : 'M').$i, $row->justificacion);
                            $reader->getActiveSheet()->setCellValue(($departamentos ? 'F' : 'N').$i, $row->autorizacion);
                            $reader->getActiveSheet()->getStyle(($departamentos ? "A$i:L$i" : "A$i:N$i"))->applyFromArray($informacion);
                            $i+=1;

                        }
                    }

                    $writer =  \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($reader, 'Xls');
                ob_end_clean();

                $temp_file = tempnam(sys_get_temp_dir(), 'PHPExcel');
                $writer->save($temp_file);
                header("Content-Disposition: attachment; filename=REPORTE_".$usuario_name->row()->nombres."_".$valor2.".xls");
 
                readfile($temp_file); 
                unlink($temp_file);
    }


    public function generar_documento_relacion(){
        if( in_array( $this->session->userdata("inicio_sesion")['rol'], array( 'CP','AD','PV' ,'SPV' ,'CAD','CPV','GAD')) ){
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

            /** Cambios TDC Reportes | FECHA: 17-Abril-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com>  **/
            $reporte_solicitudes = $this->db->query("SELECT 
                    IF(facturas.uuid IS null, 'NO', 'SI') AS tienefac
                    , solpagos.metoPago
                    , pago_generado.estatus_pago
                    , solpagos.folio, solpagos.moneda
                    , SUBSTRING(facturas.uuid, -5, 5) AS folifis
                    , solpagos.metoPago
                    , director.nombredir
                    , capturista.nombre_completo
                    , solpagos.caja_chica
                    , solpagos.idsolicitud
                    , solpagos.justificacion
                    , solpagos.nomdepto
                    , proveedores.nombre
                    , solpagos.cantidad
                    , empresas.abrev
                    , DATE_FORMAT(solpagos.fechaCreacion, '%d/%m/%Y') AS feccrea
                    , DATE_FORMAT(solpagos.fecha_autorizacion, '%d/%b/%Y') AS fecha_autorizacion
                    , DATE_FORMAT(solpagos.fecelab, '%d/%m/%Y') AS fecelab, etapas.nombre AS etapa
                    , IFNULL(pago_aut.autorizado, 0) AS autorizado
                    , IFNULL(liquidado.pagado, 0) AS pagado
                    , DATE_FORMAT(autpagos.fechaDis, '%d/%b/%Y') as fechaDis2
                    , DATE_FORMAT(autpagos.fecreg, '%d/%b/%Y') as fautorizado
                    , DATE_FORMAT(autpagos.fecha_cobro, '%d/%b/%Y') as fechapago 
                    , DATE_FORMAT(solpagos.fecha_autorizacion, '%d/%b/%Y') AS fech_auto
                    , IFNULL(tarjeta.titular_nombre, 'NA') as titular_nombre  
                FROM solpagos 
                    LEFT JOIN ( SELECT *, autpagos.estatus AS estatus_pago, MAX(autpagos.fecreg),MAX(autpagos.fecha_cobro) 
                        FROM autpagos GROUP BY autpagos.idsolicitud ) AS pago_generado ON pago_generado.idsolicitud = solpagos.idsolicitud 
                    INNER JOIN ( SELECT usuarios.idusuario, CONCAT( usuarios.nombres,' ', usuarios.apellidos ) AS nombre_completo FROM usuarios ) AS capturista ON capturista.idusuario = solpagos.idusuario 
                    LEFT JOIN (SELECT autpagos.idsolicitud, SUM( autpagos.cantidad ) AS pagado FROM autpagos 
                        GROUP BY autpagos.idsolicitud) AS liquidado 
                        ON solpagos.idsolicitud = liquidado.idsolicitud 
                    LEFT JOIN ( SELECT CONCAT( usuarios.nombres,' ',usuarios.apellidos ) AS nombredir, usuarios.idusuario
                        FROM usuarios ) AS director 
                        ON director.idusuario = solpagos.idResponsable 
                    INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor INNER JOIN etapas ON etapas.idetapa = solpagos.idetapa 
                    LEFT JOIN (SELECT *, MIN(facturas.feccrea) FROM facturas 
                        WHERE facturas.tipo_factura IN ( 1, 3 ) 
                        GROUP BY facturas.idsolicitud) AS facturas 
                        ON facturas.idsolicitud = solpagos.idsolicitud 
                    LEFT JOIN ( SELECT autpagos.idsolicitud, SUM(autpagos.cantidad) AS autorizado FROM autpagos 
                        WHERE autpagos.estatus NOT IN (2) 
                        GROUP BY autpagos.idsolicitud ) AS pago_aut 
                        ON pago_aut.idsolicitud = solpagos.idsolicitud
                    INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa 
                    LEFT JOIN ( SELECT *, MAX(autpagos.fecreg),MAX(autpagos.fecha_cobro) FROM autpagos 
                        GROUP BY autpagos.idsolicitud ) AS autpagos 
                            ON autpagos.idsolicitud = solpagos.idsolicitud
                    LEFT JOIN tcredito tdc ON solpagos.idResponsable = tdc.idtcredito
                    LEFT JOIN ( SELECT usuarios.idusuario, CONCAT( usuarios.nombres,' ', usuarios.apellidos ) AS titular_nombre FROM usuarios ) AS tarjeta ON tarjeta.idusuario = tdc.idtitular 
                WHERE solpagos.idetapa IN ( 20, 7, 9 ) 
                ORDER BY nombre, solpagos.fecelab
            ");/** FIN Cambios TDC Reportes | FECHA: 17-Abril-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com>  **/


            $i = 1;
 
            $reader->getActiveSheet()->setCellValue('A'.$i, '#');
            $reader->getActiveSheet()->setCellValue('B'.$i, 'EMPRESA');
            $reader->getActiveSheet()->setCellValue('C'.$i, 'FOLIO');
            $reader->getActiveSheet()->setCellValue('D'.$i, 'FECHA CAPTURA');
            $reader->getActiveSheet()->setCellValue('E'.$i, 'FECHA AUTORIZACION');
            $reader->getActiveSheet()->setCellValue('F'.$i, 'FECHA PAGO');
            $reader->getActiveSheet()->setCellValue('G'.$i, 'PROVEEDOR');
            $reader->getActiveSheet()->setCellValue('H'.$i, 'METODO DE PAGO');
            $reader->getActiveSheet()->setCellValue('I'.$i, 'DEPARTAMENTO');
            $reader->getActiveSheet()->setCellValue('J'.$i, 'CANTIDAD');
            $reader->getActiveSheet()->setCellValue('K'.$i, 'JUSTIFICACION');
            $reader->getActiveSheet()->setCellValue('L'.$i, 'PAGADO');
            $reader->getActiveSheet()->setCellValue('M'.$i, 'SALDO');
            $reader->getActiveSheet()->setCellValue('N'.$i, 'MONEDA');
            $reader->getActiveSheet()->setCellValue('O'.$i, 'TIPO GASTO');
            $reader->getActiveSheet()->setCellValue('P'.$i, 'FORMA PAGO');
            $reader->getActiveSheet()->setCellValue('Q'.$i, 'CON FACTURA'); /** Cambios TDC Reportes | FECHA: 17-Abril-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com>  **/
            $reader->getActiveSheet()->setCellValue('R'.$i, 'RESPONSABLE');
            $reader->getActiveSheet()->setCellValue('S'.$i, 'CAPTURISTA');
            $reader->getActiveSheet()->setCellValue('T'.$i, 'TITULAR TARJETA'); /** FIN Cambios TDC Reportes | FECHA: 17-Abril-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com>  **/
 
            $reader->getActiveSheet()->getStyle('A1:T1')->applyFromArray($encabezados); /** Cambios TDC Reportes | FECHA: 17-Abril-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com>  **/

            $i++;
            if( $reporte_solicitudes->num_rows() > 0  ){

                foreach( $reporte_solicitudes->result() as $row ){

                    $reader->getActiveSheet()->setCellValue('A'.$i, $row->idsolicitud);
                    $reader->getActiveSheet()->setCellValue('B'.$i, $row->abrev);
                    $reader->getActiveSheet()->setCellValue('C'.$i, ( $row->folio ? $row->folio : "SF" ));
                    $reader->getActiveSheet()->setCellValue('D'.$i, $row->feccrea);
                    $reader->getActiveSheet()->setCellValue('E'.$i, ( $row->fautorizado ? $row->fautorizado : "-" ));
                    $reader->getActiveSheet()->setCellValue('F'.$i, $row->fechapago);
                    $reader->getActiveSheet()->setCellValue('G'.$i, $row->nombre);
                    $reader->getActiveSheet()->setCellValue('H'.$i, $row->metoPago);
                    $reader->getActiveSheet()->setCellValue('I'.$i, $row->nomdepto);
                    $reader->getActiveSheet()->setCellValue('J'.$i, number_format($row->cantidad, 2, '.', ''));
                    $reader->getActiveSheet()->setCellValue('K'.$i, $row->justificacion);
                    $reader->getActiveSheet()->setCellValue('L'.$i, number_format($row->pagado, 2, '.', ''));
                    $reader->getActiveSheet()->setCellValue('M'.$i, number_format( $row->cantidad - $row->pagado, 2, '.', ''));
                    $reader->getActiveSheet()->setCellValue('N'.$i, $row->moneda);
                    $reader->getActiveSheet()->setCellValue('O'.$i, ( $row->caja_chica ? "CAJA CHICA" : "PAGO PROVEEDOR" ));
                    $reader->getActiveSheet()->setCellValue('P'.$i, $row->metoPago);
                    $reader->getActiveSheet()->setCellValue('Q'.$i, $row->tienefac); /** Cambios TDC Reportes | FECHA: 17-Abril-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com>  **/
                    $reader->getActiveSheet()->setCellValue('R'.$i, $row->nombredir);
                    $reader->getActiveSheet()->setCellValue('S'.$i, $row->nombre_completo);
                    $reader->getActiveSheet()->setCellValue('T'.$i, $row->titular_nombre); /** FIN Cambios TDC Reportes | FECHA: 17-Abril-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com>  **/

                    $i+=1;
                }
            }

            $writer =  \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($reader, 'Xls');
            ob_end_clean();

            $temp_file = tempnam(sys_get_temp_dir(), 'PHPExcel');
            $writer->save($temp_file);
            header("Content-Disposition: attachment; filename=REPORTE_AUXILIAR_SOLICITUDES_EN_SISTEMA.xls");
 
            readfile($temp_file); 
            unlink($temp_file);
        }
    }

    public function generar_documento_provision(){
        if( $this->input->post("fecha_mes_provision") ){
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
        
            $fecha_ahora = $this->input->post("fecha_mes_provision")."-01";
            $fecha_pasada = date('Y-m-01',strtotime( $fecha_ahora." + 1 month")); 

            $reporte = $this->db->query("SELECT s.idsolicitud, s.folio, s.metoPago, CONCAT(uC.nombres,' ',uC.apellidos) AS usuar, CONCAT(uR.nombres,' ',uR.apellidos) AS Responsable, s.justificacion, s.nomdepto, p.nombre as nomprov, e.abrev as nempr, s.cantidad, s.moneda, s.fechaCreacion, s.fecelab, IF( s.tendrafac=1, 'SI', 'NO') AS tendrafac, f.uuid, IF( s.caja_chica = 1, 'CAJA CHICA', 'PAGO PROVEEDOR' ) AS tipo_pago, etapas.nombre AS nom_etapa FROM solpagos AS s INNER JOIN facturas AS f ON f.idsolicitud = s.idsolicitud INNER JOIN usuarios AS uC ON uC.idusuario = s.idusuario INNER JOIN usuarios AS uR ON uR.idusuario = s.idResponsable INNER JOIN empresas AS e ON e.idempresa = s.idEmpresa INNER JOIN proveedores AS p ON p.idproveedor = s.idProveedor INNER JOIN etapas ON s.idetapa = etapas.idetapa WHERE f.tipo_factura = 1 and s.idsolicitud not in (SELECT idsolicitud from polizas_provision) AND s.fechaCreacion BETWEEN '".$fecha_ahora."' AND '".$fecha_pasada."'");

            $abono =  $this->db->query("SELECT nombres FROM usuarios WHERE idusuario='3'" );
            $pendiente =  $this->db->query("SELECT nombres FROM usuarios WHERE idusuario='3'" );

    
            $i = 1;
    
            $reader->getActiveSheet()->setCellValue('A'.$i, 'ID SOLICITUD');
            $reader->getActiveSheet()->setCellValue('B'.$i, 'FOLIO');
            $reader->getActiveSheet()->setCellValue('C'.$i, 'FORMA PAGO');
            $reader->getActiveSheet()->setCellValue('D'.$i, 'RESPONSABLE');
            $reader->getActiveSheet()->setCellValue('E'.$i, 'CAPTURISTA');
            $reader->getActiveSheet()->setCellValue('F'.$i, 'JUSTIFICACIÓN');
            $reader->getActiveSheet()->setCellValue('G'.$i, 'DEPARTAMENTO');
            $reader->getActiveSheet()->setCellValue('H'.$i, 'PROVEEDOR');
            $reader->getActiveSheet()->setCellValue('I'.$i, 'EMPRESA');
            $reader->getActiveSheet()->setCellValue('J'.$i, 'CANTIDAD');
            $reader->getActiveSheet()->setCellValue('K'.$i, 'MONEDA');
            $reader->getActiveSheet()->setCellValue('L'.$i, 'FECHA CAPTURA');
            $reader->getActiveSheet()->setCellValue('M'.$i, 'FECHA FACTURA');
            $reader->getActiveSheet()->setCellValue('N'.$i, 'TIPO PAGO');
            $reader->getActiveSheet()->setCellValue('O'.$i, 'ETAPA');
            $reader->getActiveSheet()->setCellValue('P'.$i, 'FOLIO FISCAL');
    
            $reader->getActiveSheet()->getStyle('A1:P1')->applyFromArray($encabezados);

            $reader->getActiveSheet()->getColumnDimension('A')->setWidth(15);
            $reader->getActiveSheet()->getColumnDimension('B')->setWidth(15);
            $reader->getActiveSheet()->getColumnDimension('C')->setWidth(15);
            $reader->getActiveSheet()->getColumnDimension('D')->setWidth(30);
            $reader->getActiveSheet()->getColumnDimension('E')->setWidth(30);
            $reader->getActiveSheet()->getColumnDimension('F')->setWidth(50);
            $reader->getActiveSheet()->getColumnDimension('G')->setWidth(25);
            $reader->getActiveSheet()->getColumnDimension('H')->setWidth(30);
            $reader->getActiveSheet()->getColumnDimension('I')->setWidth(15);
            $reader->getActiveSheet()->getColumnDimension('J')->setWidth(15);
            $reader->getActiveSheet()->getColumnDimension('K')->setWidth(15);
            $reader->getActiveSheet()->getColumnDimension('L')->setWidth(20);
            $reader->getActiveSheet()->getColumnDimension('M')->setWidth(15);
            $reader->getActiveSheet()->getColumnDimension('N')->setWidth(15);
            $reader->getActiveSheet()->getColumnDimension('O')->setWidth(15);
            $reader->getActiveSheet()->getColumnDimension('P')->setWidth(18);
                    
    
            $i+=1;
            if( $reporte->num_rows() > 0  ){

                foreach( $reporte->result() as $row ){
                    $reader->getActiveSheet()->setCellValue('A'.$i, $row->idsolicitud);
                    $reader->getActiveSheet()->setCellValue('B'.$i, $row->folio);
                    $reader->getActiveSheet()->setCellValue('C'.$i, $row->metoPago);
                    $reader->getActiveSheet()->setCellValue('D'.$i, $row->Responsable);
                    $reader->getActiveSheet()->setCellValue('E'.$i, $row->usuar);
                    $reader->getActiveSheet()->setCellValue('F'.$i, $row->justificacion);
                    $reader->getActiveSheet()->setCellValue('G'.$i, $row->nomdepto); 
                    $reader->getActiveSheet()->setCellValue('H'.$i, $row->nomprov);
                    $reader->getActiveSheet()->setCellValue('I'.$i, $row->nempr);
                    $reader->getActiveSheet()->setCellValue('J'.$i, number_format($row->cantidad, 2, '.', '')); 
                    $reader->getActiveSheet()->setCellValue('K'.$i, $row->moneda);
                    $reader->getActiveSheet()->setCellValue('L'.$i, $row->fechaCreacion);
                    $reader->getActiveSheet()->setCellValue('M'.$i, $row->fecelab);
                    $reader->getActiveSheet()->setCellValue('N'.$i, $row->tipo_pago);
                    $reader->getActiveSheet()->setCellValue('O'.$i, $row->nom_etapa);
                    $reader->getActiveSheet()->setCellValue('P'.$i, $row->uuid); 

                    $reader->getActiveSheet()->getStyle("A$i:P$i")->applyFromArray($informacion);
                    $i+=1;

                }
            }

            $writer =  \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($reader, 'Xls');
            ob_end_clean();
            
            $temp_file = tempnam(sys_get_temp_dir(), 'PHPExcel');
            $writer->save($temp_file);
            header("Content-Disposition: attachment; filename=REPORTE_AUXILIAR_SOLICITUDES_EN_SISTEMA.xls");
            
            readfile($temp_file); 
            unlink($temp_file);
        }
    }

    public function cancelar_historial($index_c){
        // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
        if( in_array( $this->session->userdata("inicio_sesion")['rol'], array( 'CP' )) ){
            $this->db->query("UPDATE solpagos SET idetapa = 30 WHERE idsolicitud = ".$index_c);
            log_sistema($this->session->userdata("inicio_sesion")['id'], $index_c, "SE CANCELÓ DESDE HISTORIAL");
        }
    }

    public function pausar_historial($index_c){
        // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
        if( in_array( $this->session->userdata("inicio_sesion")['rol'], array( 'CP' )) ){
            $query_001 = $this->db->query("SELECT idetapa FROM solpagos WHERE idsolicitud = '$index_c'");
        
            switch ($query_001->row()->idetapa) {
                case '2':
                    log_sistema($this->session->userdata("inicio_sesion")['id'], $index_c, "SE PAUSÓ EN HISTORIAL, DESDE AUT COMPRA");
                    $this->db->query("UPDATE solpagos SET idetapa = 42 WHERE idsolicitud = $index_c");
                    break;
                case '5':
                    log_sistema($this->session->userdata("inicio_sesion")['id'], $index_c, "SE PAUSÓ EN HISTORIAL, DESDE CXP");
                    $this->db->query("UPDATE solpagos SET idetapa = 45 WHERE idsolicitud = $index_c");
                    break;
                case '7':
                    log_sistema($this->session->userdata("inicio_sesion")['id'], $index_c, "SE PAUSÓ EN HISTORIAL, DESDE AUT PAGO");
                    $this->db->query("UPDATE solpagos SET idetapa = 47 WHERE idsolicitud = $index_c");
                    break;
                case '9':
                    log_sistema($this->session->userdata("inicio_sesion")['id'], $index_c, "SE PAUSÓ EN HISTORIAL, DESDE PAGO EN PARCIALIDADES");
                    $this->db->query("UPDATE solpagos SET idetapa = 49 WHERE idsolicitud = $index_c");
                    break;
                default:
                    log_sistema($this->session->userdata("inicio_sesion")['id'], $index_c, "NO SE REALIZÓ LA OPERACIÓN DE PAUSARSE");
                    break;
            }
        }
    }

    public function reporte_cajachica(){

        if( $this->input->post("fechaInicial") && $this->input->post("fechaFinal") ){
            
            //FECHAS PARA EL RANGO
            $inicio_semana = implode("-", array_reverse(explode("/", $this->input->post("fechaInicial"))));
            $fin_semana = implode("-", array_reverse(explode("/",$this->input->post("fechaFinal"))));

            //DATOS DE LA CABECERA DE LA TABLA

            $numpago = $this->input->post("#_PAGO") ? $this->input->post("#_PAGO") : '';
            $responsable = $this->input->post("RESPONSABLE") ? $this->input->post("RESPONSABLE") : '';
            $empresa = $this->input->post("EMPRESA") ? $this->input->post("EMPRESA") : '';
            $fecha = $this->input->post("FECHA") ? fechaSinFormato($this->input->post("FECHA")) : '';
            $total = $this->input->post("TOTAL") ? str_replace(["$",","],"",$this->input->post("TOTAL")) : '';
            $metopago = $this->input->post("MÉTODO_DE_PAGO") ? $this->input->post("MÉTODO_DE_PAGO") : '';
            $autorizacion = $this->input->post("FECHA_AUT.") ? fechaSinFormato($this->input->post("FECHA_AUT.")) : '';
            $departamento = $this->input->post("DEPARTAMENTO") ? $this->input->post("DEPARTAMENTO") : '';

            $departamentos = in_array($this->session->userdata("inicio_sesion")['depto'], array('CONSTRUCCION', 'JARDINERIA', 'OOAM', 'OOAM TECNICO', 'OOAM ADMINISTRATIVO'));

            ini_set('memory_limit','-1');
            set_time_limit (0);
        
            /**CON ESTO SE CARGARAN LOS ARCHIVOS DE EXCEL**/
            
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

                $TIPO_REPORTE = "CAJA_CHICA";
                switch( $this->input->post("tipo_reporte") ){
                    case 'pagadas':
                        $TIPO_REPORTE = "CAJA_CHICA";
                        $cajas_chicas = $this->db->query("SELECT 
                            * 
                        FROM listado_cchipagado 
                        WHERE IFNULL( DATE_FORMAT( listado_cchipagado.fechaDis , '%d/%b/%Y'),'') LIKE '%$autorizacion%'
                        AND ( listado_cchipagado.idpago LIKE '%$numpago%') 
                        AND ( listado_cchipagado.fecreg LIKE '%$fecha%') 
                        AND ( CONCAT( listado_cchipagado.tipoPago, ' ', listado_cchipagado.referencia ) LIKE '%$metopago%') 
                        AND ( listado_cchipagado.nomdepto LIKE '%$departamento%') 
                        AND ( listado_cchipagado.responsable LIKE '%$responsable%') 
                        AND ( listado_cchipagado.abrev LIKE '%$empresa%') 
                        AND ( listado_cchipagado.cantidad_cch LIKE '%$total%') 
                        AND STR_TO_DATE(listado_cchipagado.fecreg, '%Y-%m-%d') 
                        BETWEEN '$inicio_semana' AND '$fin_semana'");
                        break;
                    case 'pagadas_tdc':
                        $TIPO_REPORTE = "TARJETAS_CREDITO";
                        $cajas_chicas = $this->db->query("SELECT * FROM listado_tdcpagado 
                        WHERE 
                        IFNULL( DATE_FORMAT( listado_tdcpagado.fechaDis , '%d/%b/%Y'),'') LIKE '%$autorizacion%'
                        AND ( listado_tdcpagado.idpago LIKE '%$numpago%') 
                        AND ( listado_tdcpagado.fecreg LIKE '%$fecha%') 
                        AND ( CONCAT( listado_tdcpagado.tipoPago, ' ', listado_tdcpagado.referencia ) LIKE '%$metopago%') 
                        AND ( listado_tdcpagado.nomdepto LIKE '%$departamento%') 
                        AND ( listado_tdcpagado.responsable LIKE '%$responsable%') 
                        AND ( listado_tdcpagado.abrev LIKE '%$empresa%') 
                        AND ( listado_tdcpagado.cantidad_cch LIKE '%$total%') 
                        AND STR_TO_DATE(listado_tdcpagado.fecreg, '%Y-%m-%d') 
                        BETWEEN '$inicio_semana' AND '$fin_semana' ORDER BY listado_tdcpagado.idpago DESC");
                        break;
                    case 'en_transito_tdc':
                        $TIPO_REPORTE = "TARJETAS_CREDITO";
                        $cajas_chicas = $this->db->query("SELECT * FROM listado_tdctransito 
                        WHERE 
                        IFNULL( DATE_FORMAT( listado_tdctransito.fechaDis , '%d/%b/%Y'),'') LIKE '%$autorizacion%'
                        AND ( listado_tdctransito.idpago LIKE '%$numpago%') 
                        AND ( listado_tdctransito.fecreg LIKE '%$fecha%') 
                        AND ( CONCAT( listado_tdctransito.tipoPago, ' ', listado_tdctransito.referencia ) LIKE '%$metopago%') 
                        AND ( listado_tdctransito.nomdepto LIKE '%$departamento%') 
                        AND ( listado_tdctransito.responsable LIKE '%$responsable%') 
                        AND ( listado_tdctransito.abrev LIKE '%$empresa%') 
                        AND ( listado_tdctransito.cantidad LIKE '%$total%') 
                        AND STR_TO_DATE(listado_tdctransito.fecreg, '%Y-%m-%d') 
                        BETWEEN '$inicio_semana' AND '$fin_semana' ORDER BY listado_tdctransito.fecreg DESC"); /** @uthor Efrain Martinez Muñoz 08/08/2024 | ACTUALIZACION 25-SEPTIEMBRE-2024 @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                        break;
                    case 'cch_cerradas':
                        $TIPO_REPORTE = "CAJA_CHICA";
                        $cajas_chicas = $this->db->query("SELECT * FROM listado_cchicerradas 
                        WHERE 
                        IFNULL( DATE_FORMAT( listado_cchicerradas.fechaDis , '%d/%b/%Y'), '') LIKE '%$autorizacion%' 
                        AND ( listado_cchicerradas.idpago LIKE '%$numpago%') 
                        AND ( DATE_FORMAT( listado_cchicerradas.fecreg ,'%d/%b/%Y') LIKE '%$fecha%') 
                        AND ( CONCAT( listado_cchicerradas.tipoPago, ' ', listado_cchicerradas.referencia ) LIKE '%$metopago%') 
                        AND ( listado_cchicerradas.nomdepto LIKE '%$departamento%') 
                        AND ( listado_cchicerradas.responsable LIKE '%$responsable%') 
                        AND ( listado_cchicerradas.abrev LIKE '%$empresa%') 
                        AND ( listado_cchicerradas.cantidad LIKE '%$total%') 
                        AND listado_cchicerradas.fecreg BETWEEN '$inicio_semana' AND '$fin_semana' 
                        ORDER BY listado_cchicerradas.fecreg DESC");
                        break; 
                    default:
                        $TIPO_REPORTE = "CAJA_CHICA";

                        $filtro = ""; /** FECHA: 25-SEPTIEMBRE-2024 @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */

                        if( !in_array($this->session->userdata("inicio_sesion")['rol'], ['CP', 'CC', 'CX', 'CT']) && !in_array($this->session->userdata("inicio_sesion")['depto'], ['CI-COMPRAS', 'CONTABILIDAD']) ){ /** INICIO FECHA: 25-SEPTIEMBRE-2024 @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                            $filtro = " listado_cchitansito.caja_chica = 'CAJA CHICA' AND "; /** FECHA: 07-ENERO-2025 @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                        } /** FIN FECHA: 25-SEPTIEMBRE-2024 @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */

                        $cajas_chicas = $this->db->query("SELECT * FROM listado_cchitansito
                        WHERE 
                        $filtro /* FECHA: 25-SEPTIEMBRE-2024 @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                        IFNULL(DATE_FORMAT(listado_cchitansito.fechaDis, '%d/%b/%Y'),'') LIKE '%%'
                        AND ( listado_cchitansito.idpago LIKE '%$numpago%') 
                        AND ( DATE_FORMAT( listado_cchitansito.fecreg ,'%d/%b/%Y') LIKE '%$fecha%') 
                        AND ( CONCAT( listado_cchitansito.tipoPago, ' ', listado_cchitansito.referencia ) LIKE '%$metopago%') 
                        AND ( listado_cchitansito.nomdepto LIKE '%$departamento%') 
                        AND ( listado_cchitansito.responsable LIKE '%$responsable%') 
                        AND ( listado_cchitansito.abrev LIKE '%$empresa%') 
                        AND ( listado_cchitansito.cantidad LIKE '%$total%') 
                        AND listado_cchitansito.fecreg BETWEEN '$inicio_semana' AND '$fin_semana'
                        ORDER BY listado_cchitansito.fecreg DESC");
                        break;
                }
                $reporteVarios = $cajas_chicas->result_array();
                $reporteVarios = $this->ExtraerNodoXml->procesoDatosReporteCCH($reporteVarios);

                $i = 1;
                //Inicio 
                $reader->getActiveSheet()->setCellValue('A'.$i, '# PAGO');
                $reader->getActiveSheet()->setCellValue('B'.$i, '# SOLICITUD');
                if(!$departamentos){
                    $reader->getActiveSheet()->setCellValue('C'.$i, 'PROYECTO');
                    $reader->getActiveSheet()->setCellValue('D'.$i, 'OFICINA');
                    $reader->getActiveSheet()->setCellValue('E'.$i, 'SERVICIO PARTIDA');
                }
                $reader->getActiveSheet()->setCellValue(($departamentos ? 'C' : 'F').$i, 'RESPONSABLE');
                $reader->getActiveSheet()->setCellValue(($departamentos ? 'D' : 'G').$i, 'EMPRESA');

                $reader->getActiveSheet()->setCellValue(($departamentos ? 'E' : 'H').$i, 'FECHA FACTURA');
                
                $reader->getActiveSheet()->setCellValue(($departamentos ? 'F' : 'I').$i, 'FOLIO');
                $reader->getActiveSheet()->setCellValue(($departamentos ? 'G' : 'J').$i, 'PROVEEDOR');
                $reader->getActiveSheet()->setCellValue(($departamentos ? 'H' : 'K').$i, 'RFC PROVEEDOR');
                $reader->getActiveSheet()->setCellValue(($departamentos ? 'I' : 'L').$i, 'TOTAL');
                $reader->getActiveSheet()->setCellValue(($departamentos ? 'J' : 'M').$i, 'JUSTIFICACION');
                $reader->getActiveSheet()->setCellValue(($departamentos ? 'K' : 'N').$i, 'FORMA DE PAGO');
                $reader->getActiveSheet()->setCellValue(($departamentos ? 'L' : 'O').$i, 'REFERENCIA');
                $reader->getActiveSheet()->setCellValue(($departamentos ? 'M' : 'P').$i, 'TOTAL CAJA');
                $reader->getActiveSheet()->setCellValue(($departamentos ? 'N' : 'Q').$i, 'DEPARTAMENTO');
                $reader->getActiveSheet()->setCellValue(($departamentos ? 'O' : 'R').$i, 'TIPO INSUMO');
                
                $reader->getActiveSheet()->setCellValue(($departamentos ? 'P' : 'S').$i, 'FECHA AUTORIZACIÓN');
                $reader->getActiveSheet()->setCellValue(($departamentos ? 'Q' : 'T').$i, 'FECHA DISPERSIÓN');
                $reader->getActiveSheet()->setCellValue(($departamentos ? 'R' : 'U').$i, 'FECHA COBRO');

                $reader->getActiveSheet()->setCellValue(($departamentos ? 'S' : 'V').$i, 'UUID');
                
                $reader->getActiveSheet()->setCellValue(($departamentos ? 'T' : 'W').$i, 'SUBTOTAL');
                $reader->getActiveSheet()->setCellValue(($departamentos ? 'U' : 'X').$i, 'DESCUENTO');

                $reader->getActiveSheet()->setCellValue(($departamentos ? 'V' : 'Y').$i, 'IMPUESTO IVA CERO');
                $reader->getActiveSheet()->setCellValue(($departamentos ? 'W' : 'Z').$i, 'TASA TRASLADO IVA CERO');
                $reader->getActiveSheet()->setCellValue(($departamentos ? 'X' : 'AA').$i, 'IMPORTE IVA CERO');

                $reader->getActiveSheet()->setCellValue(($departamentos ? 'Y' : 'AB').$i, 'IMPUESTO IVA');
                $reader->getActiveSheet()->setCellValue(($departamentos ? 'Z' : 'AC').$i, 'TASA TRASLADO IVA');
                $reader->getActiveSheet()->setCellValue(($departamentos ? 'AA' : 'AD').$i, 'IMPORTE IVA');

                $reader->getActiveSheet()->setCellValue(($departamentos ? 'AB' : 'AE').$i, 'IMPUESTO IEPS CERO');
                $reader->getActiveSheet()->setCellValue(($departamentos ? 'AC' : 'AF').$i, 'TASA TRASLADO IEPS CERO');
                $reader->getActiveSheet()->setCellValue(($departamentos ? 'AD' : 'AG').$i, 'IMPORTE IEPS CERO');

                $reader->getActiveSheet()->setCellValue(($departamentos ? 'AE' : 'AH').$i, 'IMPUESTO IEPS');
                $reader->getActiveSheet()->setCellValue(($departamentos ? 'AF' : 'AI').$i, 'TASA TRASLADO IEPS');
                $reader->getActiveSheet()->setCellValue(($departamentos ? 'AG' : 'AJ').$i, 'IMPORTE IEPS');
                $reader->getActiveSheet()->setCellValue(($departamentos ? 'AH' : 'AK').$i, 'DESGLOSE PROYECTO');

                if( !in_array($this->input->post("tipo_reporte"), ['cch_cerradas', 'en_transito_tdc', 'pagadas_tdc', 'pagadas']) && $this->session->userdata("inicio_sesion")['rol'] == 'CP' && $this->session->userdata("inicio_sesion")['depto'] == 'CI-COMPRAS' && !$departamentos){ /** INICIO FECHA: 25-SEPTIEMBRE-2024 @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                    $reader->getActiveSheet()->setCellValue('AL'.$i, 'TIPO SERVICIO');
                }/** FIN FECHA: 25-SEPTIEMBRE-2024 @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                
                if(in_array($this->input->post("tipo_reporte"), ['en_transito_tdc']) && $this->session->userdata("inicio_sesion")['rol'] == 'CP' && $this->session->userdata("inicio_sesion")['depto'] == 'ADMINISTRACION' && !$departamentos){
                    $reader->getActiveSheet()->setCellValue('AL'.$i, 'TARJETA');
                }

                // $reader->getActiveSheet()->setCellValue('AH'.$i, 'PROYECTO DESGLOSE');
               
                // $reader->getActiveSheet()->setCellValue('AH'.$i, 'PROYECTO DESGLOSE');
                
                if( $this->input->post("tipo_reporte") != 'pagadas_tdc' ){
                    if($departamentos) {
                        $reader->getActiveSheet()->getStyle('A1:AH1')->applyFromArray($encabezados);
                    } else {
                        if($this->session->userdata("inicio_sesion")['rol'] == 'CP' && $this->session->userdata("inicio_sesion")['depto'] == 'CI-COMPRAS'){ /** INICIO FECHA: 25-SEPTIEMBRE-2024 @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                            $reader->getActiveSheet()->getStyle('A1:AL1')->applyFromArray($encabezados);
                        }elseif ( $this->input->post("tipo_reporte") == 'en_transito_tdc' && $this->session->userdata("inicio_sesion")['rol'] == 'CP' && $this->session->userdata("inicio_sesion")['depto'] == 'ADMINISTRACION') {
                            $reader->getActiveSheet()->getStyle('A1:AL1')->applyFromArray($encabezados);
                        }else{
                            $reader->getActiveSheet()->getStyle('A1:AK1')->applyFromArray($encabezados);
                        } /** FIN FECHA: 25-SEPTIEMBRE-2024 @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                    }
                }
                else{
                    if($departamentos){
                        $reader->getActiveSheet()->setCellValue('AI'.$i, 'F CAPTURA');
                        $reader->getActiveSheet()->setCellValue('AJ'.$i, 'F AUT DA');
                        $reader->getActiveSheet()->setCellValue('AK'.$i, 'RESPONSABLE');
    
                        $reader->getActiveSheet()->getStyle('A1:AG1')->applyFromArray($encabezados);
                    }
                    else{
                        $reader->getActiveSheet()->setCellValue('AL'.$i, 'F CAPTURA');
                        $reader->getActiveSheet()->setCellValue('AM'.$i, 'F AUT DA');
                        $reader->getActiveSheet()->setCellValue('AN'.$i, 'RESPONSABLE');
    
                        $reader->getActiveSheet()->getStyle('A1:AN1')->applyFromArray($encabezados);
                    }
                }

                $i+=1;

                if( $cajas_chicas->num_rows() > 0  ){

                    foreach( $cajas_chicas->result() as $row ){
                        $reader->getActiveSheet()->setCellValue('A'.$i, $row->idpago);
                        $reader->getActiveSheet()->setCellValue('B'.$i, $row->idsolicitud);
                        if(!$departamentos){
                            $reader->getActiveSheet()->setCellValue('C'.$i, $this->session->userdata("inicio_sesion")['rol'] == 'CP' && !$row->oficina ? '' : $row->proyecto);
                            $reader->getActiveSheet()->setCellValue('D'.$i, $row->oficina);
                            $reader->getActiveSheet()->setCellValue('E'.$i, $this->session->userdata("inicio_sesion")['rol'] == 'CP' && !$row->oficina ? $row->proyecto : $row->servicioPartida);
                        }
                        $reader->getActiveSheet()->setCellValue(($departamentos ? 'C' : 'F').$i, $row->responsable);
                        $reader->getActiveSheet()->setCellValue(($departamentos ? 'D' : 'G').$i, $row->abrev);

                        $reader->getActiveSheet()->setCellValue(($departamentos ? 'E' : 'H').$i, $row->fecelab);
                        $reader->getActiveSheet()->setCellValue(($departamentos ? 'F' : 'I').$i, $row->folio);
                        $reader->getActiveSheet()->setCellValue(($departamentos ? 'G' : 'J').$i, $row->nproveedor);
                        $reader->getActiveSheet()->setCellValue(($departamentos ? 'H' : 'K').$i, $row->rfc);
                        $reader->getActiveSheet()->setCellValue(($departamentos ? 'I' : 'L').$i, number_format($row->cantidad, 2, '.', ''));
                        $reader->getActiveSheet()->setCellValue(($departamentos ? 'J' : 'M').$i, $row->justificacion); 
                        $reader->getActiveSheet()->setCellValue(($departamentos ? 'K' : 'N').$i, $row->tipoPago);
                        $reader->getActiveSheet()->setCellValue(($departamentos ? 'L' : 'O').$i, $row->referencia ? $row->referencia : '' );
                        $reader->getActiveSheet()->setCellValue(($departamentos ? 'M' : 'P').$i, is_numeric( $row->tcaja ) ? number_format($row->tcaja, 2, '.', '') : $row->tcaja );
                        $reader->getActiveSheet()->setCellValue(($departamentos ? 'N' : 'Q').$i, $row->nomdepto);
                        $reader->getActiveSheet()->setCellValue(($departamentos ? 'O' : 'R').$i, $row->insumo ? $row->insumo : '');
                        
                        $reader->getActiveSheet()->setCellValue(($departamentos ? 'P' : 'S').$i, $row->fecreg ? $row->fecreg : '');
                        $reader->getActiveSheet()->setCellValue(($departamentos ? 'Q' : 'T').$i, $row->fechaDis ? $row->fechaDis : '');
                        $reader->getActiveSheet()->setCellValue(($departamentos ? 'R' : 'U').$i, $row->fecha_cobro ? $row->fecha_cobro : '');

                        $reader->getActiveSheet()->setCellValue(($departamentos ? 'S' : 'V').$i, $row->uuid ? $row->uuid : 'N/A' );
                            
                        $reader->getActiveSheet()->setCellValue(($departamentos ? 'T' : 'W').$i, $row->subtotal ? $row->subtotal : '0');
                        $reader->getActiveSheet()->setCellValue(($departamentos ? 'U' : 'X').$i, $row->descuento ? $row->descuento : '0');
                        
                        /***IMPUESTOS TRASLADO**/
                        $reader->getActiveSheet()->setCellValue(($departamentos ? 'V' : 'Y').$i, 0 );
                        $reader->getActiveSheet()->setCellValue(($departamentos ? 'W' : 'Z').$i, 0);
                        $reader->getActiveSheet()->setCellValue(($departamentos ? 'X' : 'AA').$i, 0);

                        $reader->getActiveSheet()->setCellValue(($departamentos ? 'Y' : 'AB').$i, 0 );
                        $reader->getActiveSheet()->setCellValue(($departamentos ? 'Z' : 'AC').$i, 0);
                        $reader->getActiveSheet()->setCellValue(($departamentos ? 'AA' : 'AD').$i, 0);

                        $reader->getActiveSheet()->setCellValue(($departamentos ? 'AB' : 'AE').$i, 0 );
                        $reader->getActiveSheet()->setCellValue(($departamentos ? 'AC' : 'AF').$i, 0);
                        $reader->getActiveSheet()->setCellValue(($departamentos ? 'AD' : 'AG').$i, 0);

                        $reader->getActiveSheet()->setCellValue(($departamentos ? 'AE' : 'AH').$i, 0 );
                        $reader->getActiveSheet()->setCellValue(($departamentos ? 'AF' : 'AI').$i, 0);
                        $reader->getActiveSheet()->setCellValue(($departamentos ? 'AG' : 'AJ').$i, 0);

                        if( $this->input->post("tipo_reporte") == 'pagadas_tdc' ){
                            if($departamentos){
                                $reader->getActiveSheet()->setCellValue('AI'.$i, $row->fcrea);
                                $reader->getActiveSheet()->setCellValue('AJ'.$i, $row->fautda);
                                $reader->getActiveSheet()->setCellValue('AK'.$i, $row->capturista);
                            }
                            else{
                                $reader->getActiveSheet()->setCellValue('AL'.$i, $row->fcrea);
                                $reader->getActiveSheet()->setCellValue('AM'.$i, $row->fautda);
                                $reader->getActiveSheet()->setCellValue('AN'.$i, $row->capturista);
                            }
                            
                        }
                        
                        if( (!in_array($this->input->post("tipo_reporte"), ['cch_cerradas', 'en_transito_tdc', 'pagadas_tdc', 'pagadas']) && !$departamentos) && ($this->session->userdata("inicio_sesion")['rol'] == 'CP' && $this->session->userdata("inicio_sesion")['depto'] == 'CI-COMPRAS') ){ /** INICIO FECHA: 25-SEPTIEMBRE-2024 @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                            $caja_chica = $row->caja_chica ? $row->caja_chica : null;
                            $reader->getActiveSheet()->setCellValue('AL'.$i, $caja_chica);/** FIN FECHA: 25-SEPTIEMBRE-2024 @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                        }elseif ( (in_array($this->input->post("tipo_reporte"), ['en_transito_tdc']) && !$departamentos) && ($this->session->userdata("inicio_sesion")['rol'] == 'CP' && $this->session->userdata("inicio_sesion")['depto'] == 'ADMINISTRACION') ){
                            $nomTDC = $row->ntarjeta ? $row->ntarjeta : null;
                            $reader->getActiveSheet()->setCellValue('AL'.$i, $nomTDC);
                        } 

                        if( $row->impuestot ){
                            $string = json_decode( $row->impuestot, true );
                            if( is_array( $string ) ){
                                /****RETENCIONES IVA****/
                                //TASA 0
                                for( $c = 0; $c < count( $string ); $c++ ){
                                    if( $string[$c]["@attributes"]["Impuesto"] == "002" && isset( $string[$c]["@attributes"]["TasaOCuota"] ) && $string[$c]["@attributes"]["TasaOCuota"] == 0){
                                        $reader->getActiveSheet()->setCellValue(($departamentos ? 'V' : 'Y').$i, $string[$c]["@attributes"]["Impuesto"] );
                                        $reader->getActiveSheet()->setCellValue(($departamentos ? 'W' : 'Z').$i, $string[$c]["@attributes"]["TasaOCuota"]);
                                        $reader->getActiveSheet()->setCellValue(($departamentos ? 'X' : 'AA').$i, $string[$c]["@attributes"]["Importe"]);
                                        break;
                                    }
                                }
                                //TASA MAYOR A 0
                                for( $c = 0; $c < count( $string ); $c++ ){
                                    if( $string[$c]["@attributes"]["Impuesto"] == "002" && isset( $string[$c]["@attributes"]["TasaOCuota"] ) && $string[$c]["@attributes"]["TasaOCuota"] > 0 ){
                                        $reader->getActiveSheet()->setCellValue(($departamentos ? 'Y' : 'AB').$i, $string[$c]["@attributes"]["Impuesto"] );
                                        $reader->getActiveSheet()->setCellValue(($departamentos ? 'Z' : 'AC').$i, $string[$c]["@attributes"]["TasaOCuota"]);
                                        $reader->getActiveSheet()->setCellValue(($departamentos ? 'AA' : 'AD').$i, $string[$c]["@attributes"]["Importe"]);
                                        break;
                                    }
                                }
                                /********/

                                /****RETENCIONES TRASLADO IEPS****/
                                //TASA 0
                                for( $c = 0; $c < count( $string ); $c++ ){
                                    if( $string[$c]["@attributes"]["Impuesto"] == "003" && isset( $string[$c]["@attributes"]["TasaOCuota"] ) && $string[$c]["@attributes"]["TasaOCuota"] == 0){
                                        $reader->getActiveSheet()->setCellValue(($departamentos ? 'AB' : 'AE').$i, $string[$c]["@attributes"]["Impuesto"] );
                                        $reader->getActiveSheet()->setCellValue(($departamentos ? 'AC' : 'AF').$i, $string[$c]["@attributes"]["TasaOCuota"]);
                                        $reader->getActiveSheet()->setCellValue(($departamentos ? 'AD' : 'AG').$i, $string[$c]["@attributes"]["Importe"]);
                                        break;
                                    }
                                }
                                //TASA MAYOR A 0
                                for( $c = 0; $c < count( $string ); $c++ ){
                                    if( $string[$c]["@attributes"]["Impuesto"] == "003" && isset( $string[$c]["@attributes"]["TasaOCuota"] ) &&  $string[$c]["@attributes"]["TasaOCuota"] > 0 ){
                                        $reader->getActiveSheet()->setCellValue('AE'.$i, $string[$c]["@attributes"]["Impuesto"] );
                                        $reader->getActiveSheet()->setCellValue('AF'.$i, $string[$c]["@attributes"]["TasaOCuota"]);
                                        $reader->getActiveSheet()->setCellValue('AG'.$i, $string[$c]["@attributes"]["Importe"]);
                                        break;
                                    }
                                }
                                /********/
                            }
                            
                        }
                        /********/
                        $reader->getActiveSheet()->setCellValue(($departamentos ?'AH' : 'AK').$i, strpos($row->proyecto, ' OOAM') ? '1190-0003' : '6100-0005-0001');
                        /*Fin
                        *@uthor Efrain Martinez Muñoz --Creación de una nueva columna llamada (# Solicitud) la cual se mostrará al descaragar el excel desde el boton de DESGLOSADO
                        */ 
                        //$reader->getActiveSheet()->getStyle("A$i:S$i")->applyFromArray($informacion1);
                        $i+=1;
                    }
                    /**********************/
                }
                
                $writer =  \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($reader, 'Xls');
                ob_end_clean();

                $temp_file = tempnam(sys_get_temp_dir(), 'PHPExcel');
                $writer->save($temp_file);
                header("Content-Disposition: attachment; filename=REPORTE_".$TIPO_REPORTE."_DESGLOSADO_".date("d/m/Y")."_.xls");

                readfile($temp_file); 
                unlink($temp_file);
                
        }else{
            $this->load->view("vista_historial_cch");
        }       
    }

    public function borra_factura() {
        
        // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analaista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);

        // Iniciamos bloque para cachar algun error en el proceso
        try {
            // Definimos una respuesta al inicio teniendo en cuenta que no se pudo modificar el registro.
            $response = [
                "resultado" => FALSE,
                "msj" => "No ha sido posible realizar el movimiento, intente mas tarde.",
            ];
            // Validamos que existan datos a procesar.
            if( $_POST["idsol"] ){
                // Almacenamos el valor o valores en una variable.
                $idsol = $_POST["idsol"];
                // Hacemos una consulta para traer la informacion de la solicitud dentro de la tabla de facturas.
                $facturas = $this->M_historial->obtenerInfoFacturaSolicitud($idsol);
                // Condicionamos que realmente exista informacion o facturas relacionadas a la solicitud.
                if ($facturas == NULL || !$facturas) {
                    // En caso de no existir informacion mandamos una respuesta con un false y mensaje.
                    $response = [
                        "resultado" => FALSE,
                        "msj" => "No se pudo obtener el registro de la factura asociado a la solicitud. Por favor, póngase en contacto con el administrador para mayor asistencia.",
                    ];
                }elseif ($facturas->num_rows() >= 1) {
                    // Variable para el almacenamiento de tipo de facturas.
                    $facturaComprobanteComplementos = [];
                    // Guardamos la informacion de la consulta obteniendo informacion del registro dentro de la tabla de facturas.
                    $facturasInformacion = $facturas->result_array();
                    // Recorrido en los registros traidos segun el id de la solicitud
                    foreach ($facturasInformacion as $valorFactura) {
                        // Consideramos el tipo de factura si esta consiste en una factura tipo 1 (PPD) o tipo 2 (complemento de pago), haremos el siguiente proceso.
                        if ($valorFactura["tipo_factura"] == 1) {
                            // Si no existe el indice comprobante en la variable que almacenara la informacion que se mandara al modelo se hara lo siguiente
                            if(!array_key_exists("comprobante", $facturaComprobanteComplementos)){
                                // Si la variable $facturaComprobanteComplementos no contiene informacion, tal cual guardara la informacion en forma de array, de lo contrario concatenara la infromacion en el indice del array indicado.
                                count($facturaComprobanteComplementos) == 0 
                                    ? $facturaComprobanteComplementos = [
                                        "comprobante"   =>  [["idSolicitud"  =>  $valorFactura["idsolicitud"],
                                                            "idFactura"     =>  $valorFactura["idfactura"],
                                                            "tipoFactura"   =>  $valorFactura["tipo_factura"]]
                                        ]]
                                    : $facturaComprobanteComplementos += [
                                        "comprobante"   =>  [["idSolicitud"  =>  $valorFactura["idsolicitud"],
                                                            "idFactura"     =>  $valorFactura["idfactura"],
                                                            "tipoFactura"   =>  $valorFactura["tipo_factura"]]
                                        ]];
                            }else {
                                // Si existe el indice comprobante en el array de control solo concatenamos o agregamos la infornacion con indice incrementables,
                                array_push($facturaComprobanteComplementos["comprobante"], [
                                    "idSolicitud"   =>  $valorFactura["idsolicitud"],
                                    "idFactura"     =>  $valorFactura["idfactura"],
                                    "tipoFactura"   =>  $valorFactura["tipo_factura"]]);
                            }
                        }
                        // Consideramos el tipo de factura si esta consiste en una factura tipo 1 (PPD) o tipo 2 (complemento de pago), haremos el siguiente proceso.
                        elseif($valorFactura["tipo_factura"] == 2){

                            // Si no existe el indice complemento en la variable que almacenara la informacion que se mandara al modelo se hara lo siguiente
                            if(!array_key_exists('complementos', $facturaComprobanteComplementos)){

                                // Si la variable $facturaComprobanteComplementos no contiene informacion, tal cual guardara la informacion en forma de array, de lo contrario concatenara la infromacion en el indice del array indicado.
                                count($facturaComprobanteComplementos) == 0
                                    ? $facturaComprobanteComplementos = [
                                        "complementos"  =>  [["idSolicitud"  =>  $valorFactura["idsolicitud"],
                                                                "idFactura"     =>  $valorFactura["idfactura"],
                                                                "tipoFactura"   =>  $valorFactura["tipo_factura"]]
                                        ]]
                                    : $facturaComprobanteComplementos += [
                                        "complementos"  =>  [["idSolicitud"  =>  $valorFactura["idsolicitud"],
                                                                "idFactura"     =>  $valorFactura["idfactura"],
                                                                "tipoFactura"   =>  $valorFactura["tipo_factura"]]
                                        ]];
                            }else {
                                // Si existe el indice comprobante en el array de control solo concatenamos o agregamos la infornacion con indice incrementables,
                                array_push($facturaComprobanteComplementos["complementos"], [
                                    "idSolicitud"   =>  $valorFactura["idsolicitud"],
                                    "idFactura"     =>  $valorFactura["idfactura"],
                                    "tipoFactura"   =>  $valorFactura["tipo_factura"]]);
                            }
                        }elseif($valorFactura["tipo_factura"] == 3){
                            
                            // Si no existe el indice complemento en la variable que almacenara la informacion que se mandara al modelo se hara lo siguiente
                            if(!array_key_exists('pagoUnicaExibision', $facturaComprobanteComplementos)){

                                // Si la variable $facturaComprobanteComplementos no contiene informacion, tal cual guardara la informacion en forma de array, de lo contrario concatenara la infromacion en el indice del array indicado.
                                count($facturaComprobanteComplementos) == 0
                                    ? $facturaComprobanteComplementos = [
                                        "pagoUnicaExibision"  =>  [["idSolicitud"  =>  $valorFactura["idsolicitud"],
                                                                "idFactura"     =>  $valorFactura["idfactura"],
                                                                "tipoFactura"   =>  $valorFactura["tipo_factura"]]
                                        ]]
                                    : $facturaComprobanteComplementos += [
                                        "pagoUnicaExibision"  =>  [["idSolicitud"  =>  $valorFactura["idsolicitud"],
                                                                "idFactura"     =>  $valorFactura["idfactura"],
                                                                "tipoFactura"   =>  $valorFactura["tipo_factura"]]
                                        ]];
                            }else {
                                // Si existe el indice comprobante en el array de control solo concatenamos o agregamos la infornacion con indice incrementables,
                                array_push($facturaComprobanteComplementos["pagoUnicaExibision"], [
                                    "idSolicitud"   =>  $valorFactura["idsolicitud"],
                                    "idFactura"     =>  $valorFactura["idfactura"],
                                    "tipoFactura"   =>  $valorFactura["tipo_factura"]]);
                            }

                        }elseif($valorFactura["tipo_factura"] == 4){
                            
                            // Si no existe el indice complemento en la variable que almacenara la informacion que se mandara al modelo se hara lo siguiente
                            if(!array_key_exists('notaCredito', $facturaComprobanteComplementos)){

                                // Si la variable $facturaComprobanteComplementos no contiene informacion, tal cual guardara la informacion en forma de array, de lo contrario concatenara la infromacion en el indice del array indicado.
                                count($facturaComprobanteComplementos) == 0
                                    ? $facturaComprobanteComplementos = [
                                        "notaCredito"  =>  [["idSolicitud"  =>  $valorFactura["idsolicitud"],
                                                                "idFactura"     =>  $valorFactura["idfactura"],
                                                                "tipoFactura"   =>  $valorFactura["tipo_factura"]]
                                        ]]
                                    : $facturaComprobanteComplementos += [
                                        "notaCredito"  =>  [["idSolicitud"  =>  $valorFactura["idsolicitud"],
                                                                "idFactura"     =>  $valorFactura["idfactura"],
                                                                "tipoFactura"   =>  $valorFactura["tipo_factura"]]
                                        ]];
                            }else {
                                // Si existe el indice comprobante en el array de control solo concatenamos o agregamos la infornacion con indice incrementables,
                                array_push($facturaComprobanteComplementos["notaCredito"], [
                                    "idSolicitud"   =>  $valorFactura["idsolicitud"],
                                    "idFactura"     =>  $valorFactura["idfactura"],
                                    "tipoFactura"   =>  $valorFactura["tipo_factura"]]);
                            }

                        }
                    }

                }
                // Procesamos las solicitudes ya sean comprobantes o PPD
                $resultado = $this->M_historial->eliminarFacturaSolicitud($facturaComprobanteComplementos, $idsol);
                if ($resultado["estatus"] != 200) {
                    // En caso de obtener algun problema al momento del proceso en la BD mandamos una excepcion
                    throw new Exception("Error: ". $resultado["mensaje"]);
                }
                // Si el proceso salio bien mandamos una respuesta correcta.
                $response = [
                    "resultado" => TRUE,
                    "msj" => $resultado["mensaje"]
                ];
            }

            echo json_encode($response);
        } catch (Exception $error) {
            log_message('error', 'Error al agregar usuario: ' . $error->getMessage());
            echo json_encode($response);
        }
    }
    public function cambiafecha_auto() {
        // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
        $lunes = date( 'Y-m-d', strtotime( 'monday this week' ) );
        $response = array();
        $resp=false;
        $idsol=$_POST["idsol"];

        if( $this->db->update("solpagos", [ "fecha_autorizacion" => $lunes ], "idsolicitud = $idsol") ){
            $resp=true;
            log_sistema($this->session->userdata("inicio_sesion")['id'], $idsol, "SE ADELANTÓ LA REVISIÓN");
            $msj="Se ha adelantado la revisión de la solicitud #$idsol correctamente";
        }

        $response["resultado"]=$resp;
        $response["msj"]=$msj;

        echo json_encode($response);
    }

    public function abonos_sol(){
        $json=array("respuesta"=>false,"msj"=>"Ha ocurrido un error en el proceso","data"=>[]);
        $this->load->model("M_historial");
        $json["data"]=$this->M_historial->abonos_solM( $this->input->get("ids") )->result_array();
        echo json_encode($json);
    }

    public function accion_abonos(){
        $json=array("respuesta"=>false,"msj"=>"Ha ocurrido un error en el proceso","data"=>[]);
        $this->load->model("M_historial");
        if($this->input->post("accion")!='eli'){
            $json["data"]=$this->M_historial->regmod_abonoM($this->input->post());
            $json["msj"]="Se ha agregado un abono correctamente";
            $json["respuesta"]=true;
        }else{
            $json["data"]=$this->M_historial->elimina_abonoM($this->input->post());
            $json["msj"]="Se ha agregado un abono correctamente";
            $json["respuesta"]=true;
        }
        echo json_encode($json);
    }

    public function historialDATI() {
        $this->load->view("v_historial");
    }

	/***PARA LOS HISTORIALES DE REEMBOLSOS**/
	/***LO PAGADO**/
	public function tabla_reembolsos(){
		$this->load->model(["Mproyectos"]);
		$tipo_rembolso = $this->input->post("TIPO_REMBOLSO") ? $this->input->post("TIPO_REMBOLSO") : NULL;
		echo json_encode( array(
			"data" => $this->M_historial->reembolsos_pagados( $tipo_rembolso )->result_array(),
			"permiso_desglose" => in_array( $this->session->userdata("inicio_sesion")['id'], [ 94, 93 ]  ),
			"proyectos" => $this->Mproyectos->getProyectoAdministrarActivo()->result_array()
		));
	}

	public function reembolsos_cerrados(){
		$data = $this->M_historial->reembolsos_cerrados()->result_array();
		echo json_encode( array( "data" => $data ));
	}

	/***EN TRANSITO REEMBOLSOS**/
	public function tabla_reembolsos_transito(){
		echo json_encode( array( "data" => $this->M_historial->reembolsos_transito()->result_array() ));
	}


	/***PARA LOS HISTORIALES DE VIATICOS**/
	public function tabla_viaticos(){
		$this->load->model(array("Mproyectos"));
		$tipo_rembolso = $this->input->post("TIPO_REMBOLSO") ? $this->input->post("TIPO_REMBOLSO") : NULL;
		echo json_encode( array(
			"data" => $this->M_historial->viaticos_pagados( $tipo_rembolso )->result_array(),
			"permiso_desglose" => in_array( $this->session->userdata("inicio_sesion")['id'], [ 94, 93 ]  ),
			"proyectos" => $this->Mproyectos->getProyectoAdministrarActivo()->result_array()
		));
	}
	/***EN TRANSITO VIÁTICOS**/
	public function tabla_viaticos_transito(){
		echo json_encode( array( "data" => $this->M_historial->viaticos_transito()->result_array() ));
	}

	public function viaticos_cerrados(){
		$data = $this->M_historial->viaticos_cerrados()->result_array();
		echo json_encode( array( "data" => $data ));
	}

	public function reporte_viaticos(){

		if( $this->input->post("fechaInicial") && $this->input->post("fechaFinal") ){

			//FECHAS PARA EL RANGO
			$inicio_semana = implode("-", array_reverse(explode("/", $this->input->post("fechaInicial"))));
			$fin_semana = implode("-", array_reverse(explode("/",$this->input->post("fechaFinal"))));

			//DATOS DE LA CABECERA DE LA TABLA

			$numpago = $this->input->post("#_PAGO") ? $this->input->post("#_PAGO") : '';
            $responsable = $this->input->post("RESPONSABLE") ? str_replace(' ', '%', trim($this->input->post("RESPONSABLE"))) : '';
			$empresa = $this->input->post("EMPRESA") ? $this->input->post("EMPRESA") : '';
			$fecha = $this->input->post("FECHA") ? fechaSinFormato($this->input->post("FECHA")) : '';
			$total = $this->input->post("TOTAL") ? str_replace(["$",","],"",$this->input->post("TOTAL")) : '';
			$metopago = $this->input->post("MÉTODO_DE_PAGO") ? $this->input->post("MÉTODO_DE_PAGO") : '';
			$autorizacion = $this->input->post("FECHA_AUT.") ? fechaSinFormato($this->input->post("FECHA_AUT.")) : '';
			$departamento = $this->input->post("DEPARTAMENTO") ? $this->input->post("DEPARTAMENTO") : '';

			ini_set('memory_limit','-1');
			set_time_limit (0);

			/**CON ESTO SE CARGARAN LOS ARCHIVOS DE EXCEL**/

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

			$TIPO_REPORTE = "VIATICOS";
			switch( $this->input->post("tipo_reporte") ){
				case 'viaticos_pagadas':
					$TIPO_REPORTE = "VIATICOS";
					$cajas_chicas = $this->db->query("SELECT * FROM listado_viaticopagado AS lvp
                        WHERE 
                        IFNULL( DATE_FORMAT( lvp.fechaDis , '%d/%b/%Y'),'') LIKE '%$autorizacion%'
                        AND ( lvp.idpago LIKE '%$numpago%') 
                        AND ( lvp.fecreg LIKE '%$fecha%') 
                        AND ( CONCAT( lvp.tipoPago, ' ', lvp.referencia ) LIKE '%$metopago%') 
                        AND ( lvp.nomdepto LIKE '%$departamento%') 
                        AND ( lvp.responsable LIKE '%$responsable%') 
                        AND ( lvp.abrev LIKE '%$empresa%') 
                        AND ( lvp.cantidad_cch LIKE '%$total%') 
                        AND STR_TO_DATE(lvp.fecreg, '%Y-%m-%d') 
                        BETWEEN '$inicio_semana' AND '$fin_semana' ORDER BY lvp.idpago DESC");
					break;
				case 'viaticos_en_transito':
					$TIPO_REPORTE = "VIATICOS";
					$cajas_chicas = $this->db->query("SELECT * FROM listado_viaticotransito AS lvc 
                        WHERE 
                        IFNULL( DATE_FORMAT( lvc.fechaDis , '%d/%b/%Y'), '') LIKE '%$autorizacion%' 
                        AND ( lvc.idpago LIKE '%$numpago%') 
                        AND ( DATE_FORMAT( lvc.fecreg ,'%d/%b/%Y') LIKE '%$fecha%') 
                        AND ( CONCAT( lvc.tipoPago, ' ', lvc.referencia ) LIKE '%$metopago%') 
                        AND ( lvc.nomdepto LIKE '%$departamento%') 
                        AND ( lvc.responsable LIKE '%$responsable%') 
                        AND ( lvc.abrev LIKE '%$empresa%') 
                        AND ( lvc.cantidad LIKE '%$total%') 
                        AND STR_TO_DATE(lvc.fecreg, '%Y-%m-%d') 
                        BETWEEN '$inicio_semana' AND '$fin_semana' ORDER BY lvc.fecreg DESC");
					break;
                case 'viaticos_cerrados':
					$TIPO_REPORTE = "VIATICOS";
					$cajas_chicas = $this->db->query("SELECT * FROM listado_viaticos_cerradas AS lvc 
                        WHERE 
                        IFNULL( DATE_FORMAT( lvc.fechaDis , '%d/%b/%Y'), '') LIKE '%$autorizacion%' 
                        AND ( lvc.idpago LIKE '%$numpago%') 
                        AND ( DATE_FORMAT( lvc.fecreg ,'%d/%b/%Y') LIKE '%$fecha%') 
                        AND ( CONCAT( lvc.tipoPago, ' ', lvc.referencia ) LIKE '%$metopago%') 
                        AND ( lvc.nomdepto LIKE '%$departamento%') 
                        AND ( lvc.responsable LIKE '%$responsable%') 
                        AND ( lvc.abrev LIKE '%$empresa%') 
                        AND ( lvc.cantidad LIKE '%$total%') 
                        AND STR_TO_DATE(lvc.fecreg, '%Y-%m-%d') 
                        BETWEEN '$inicio_semana' AND '$fin_semana' ORDER BY lvc.fecreg DESC");
					break;
				default:
					null;
					break;
			}

			$i = 1;

			$reader->getActiveSheet()->setCellValue('A'.$i, '# PAGO');
            $reader->getActiveSheet()->setCellValue('B'.$i, '# SOLICITUD');
			$reader->getActiveSheet()->setCellValue('C'.$i, 'RESPONSABLE');

            $reader->getActiveSheet()->setCellValue('D'.$i, 'PROYECTO');
            $reader->getActiveSheet()->setCellValue('E'.$i, 'OFICINA');
            $reader->getActiveSheet()->setCellValue('F'.$i, 'SERVICIO/PARTIDA');

			$reader->getActiveSheet()->setCellValue('G'.$i, 'EMPRESA');

			$reader->getActiveSheet()->setCellValue('H'.$i, 'FECHA FACTURA');

			$reader->getActiveSheet()->setCellValue('I'.$i, 'FOLIO');
			$reader->getActiveSheet()->setCellValue('J'.$i, 'PROVEEDOR');
			$reader->getActiveSheet()->setCellValue('K'.$i, 'RFC PROVEEDOR');
			$reader->getActiveSheet()->setCellValue('L'.$i, 'TOTAL');
			$reader->getActiveSheet()->setCellValue('M'.$i, 'JUSTIFICACION');
			$reader->getActiveSheet()->setCellValue('N'.$i, 'FORMA DE PAGO');
			$reader->getActiveSheet()->setCellValue('O'.$i, 'REFERENCIA');
			$reader->getActiveSheet()->setCellValue('P'.$i, 'TOTAL');
			$reader->getActiveSheet()->setCellValue('Q'.$i, 'DEPARTAMENTO');
			$reader->getActiveSheet()->setCellValue('R'.$i, 'TIPO INSUMO');

			$reader->getActiveSheet()->setCellValue('S'.$i, 'FECHA AUTORIZACIÓN');
			$reader->getActiveSheet()->setCellValue('T'.$i, 'FECHA DISPERSIÓN');
			$reader->getActiveSheet()->setCellValue('U'.$i, 'FECHA COBRO');

			$reader->getActiveSheet()->setCellValue('V'.$i, 'UUID');

			$reader->getActiveSheet()->setCellValue('W'.$i, 'SUBTOTAL');
			$reader->getActiveSheet()->setCellValue('X'.$i, 'DESCUENTO');

			$reader->getActiveSheet()->setCellValue('Y'.$i, 'IMPUESTO IVA CERO');
			$reader->getActiveSheet()->setCellValue('Z'.$i, 'TASA TRASLADO IVA CERO');
			$reader->getActiveSheet()->setCellValue('AA'.$i, 'IMPORTE IVA CERO');

			$reader->getActiveSheet()->setCellValue('AB'.$i, 'IMPUESTO IVA');
			$reader->getActiveSheet()->setCellValue('AC'.$i, 'TASA TRASLADO IVA');
			$reader->getActiveSheet()->setCellValue('AD'.$i, 'IMPORTE IVA');

			$reader->getActiveSheet()->setCellValue('AE'.$i, 'IMPUESTO IEPS CERO');
			$reader->getActiveSheet()->setCellValue('AF'.$i, 'TASA TRASLADO IEPS CERO');
			$reader->getActiveSheet()->setCellValue('AG'.$i, 'IMPORTE IEPS CERO');

			$reader->getActiveSheet()->setCellValue('AH'.$i, 'IMPUESTO IEPS');
			$reader->getActiveSheet()->setCellValue('AI'.$i, 'TASA TRASLADO IEPS');
			$reader->getActiveSheet()->setCellValue('AJ'.$i, 'IMPORTE IEPS');

			$reader->getActiveSheet()->setCellValue('AK'.$i, 'PROYECTO DESGLOSE');
            $reader->getActiveSheet()->setCellValue('AL'.$i, 'ETAPA');
            $reader->getActiveSheet()->setCellValue('AM'.$i, 'PERTENECE REEMBOLSO');

			if( $this->input->post("tipo_reporte") != 'pagadas_tdc' )
				$reader->getActiveSheet()->getStyle('A1:AM1')->applyFromArray($encabezados);
			else{
				$reader->getActiveSheet()->setCellValue('AM'.$i, 'F CAPTURA');
				$reader->getActiveSheet()->setCellValue('AO'.$i, 'F AUT DA');
				$reader->getActiveSheet()->setCellValue('AP'.$i, 'RESPONSABLE');

				$reader->getActiveSheet()->getStyle('A1:AP1')->applyFromArray($encabezados);
			}

			$i+=1;

			if( $cajas_chicas->num_rows() > 0  ){

				foreach( $cajas_chicas->result() as $row ){
					$reader->getActiveSheet()->setCellValue('A'.$i, $row->idpago);
                    $reader->getActiveSheet()->setCellValue('B'.$i, $row->idsolicitud);
					$reader->getActiveSheet()->setCellValue('C'.$i, $row->responsable);

                    // NUEVAS COLUMNAS
                        $reader->getActiveSheet()->setCellValue('D'.$i, $row->proyectoNuevo);
                        $reader->getActiveSheet()->setCellValue('E'.$i, $row->oficina);
                        $reader->getActiveSheet()->setCellValue('F'.$i, $row->servicioPartida);
                    // FIN NUEVAS COLUMNAS

					$reader->getActiveSheet()->setCellValue('G'.$i, $row->abrev);

					$reader->getActiveSheet()->setCellValue('H'.$i, $row->fecelab);
					$reader->getActiveSheet()->setCellValue('I'.$i, $row->folio);
					$reader->getActiveSheet()->setCellValue('J'.$i, $row->nproveedor);
					$reader->getActiveSheet()->setCellValue('K'.$i, $row->rfc);
					$reader->getActiveSheet()->setCellValue('L'.$i, number_format($row->cantidad, 2, '.', ''));
					$reader->getActiveSheet()->setCellValue('M'.$i, $row->justificacion);
					$reader->getActiveSheet()->setCellValue('N'.$i, $row->tipoPago);
					$reader->getActiveSheet()->setCellValue('O'.$i, $row->referencia ? $row->referencia : '' );
					$reader->getActiveSheet()->setCellValue('P'.$i, is_numeric( $row->tcaja ) ? number_format($row->tcaja, 2, '.', '') : $row->tcaja );
					$reader->getActiveSheet()->setCellValue('Q'.$i, $row->nomdepto);
					$reader->getActiveSheet()->setCellValue('R'.$i, $row->insumo ? $row->insumo : '');

					$reader->getActiveSheet()->setCellValue('S'.$i, $row->fecreg ? $row->fecreg : '');
					$reader->getActiveSheet()->setCellValue('T'.$i, $row->fechaDis ? $row->fechaDis : '');
					$reader->getActiveSheet()->setCellValue('U'.$i, $row->fecha_cobro ? $row->fecha_cobro : '');

					$reader->getActiveSheet()->setCellValue('V'.$i, $row->uuid ? $row->uuid : 'N/A' );

					$reader->getActiveSheet()->setCellValue('W'.$i, $row->subtotal ? $row->subtotal : '0');
					$reader->getActiveSheet()->setCellValue('X'.$i, $row->descuento ? $row->descuento : '0');

					/***IMPUESTOS TRASLADO**/
					/*$reader->getActiveSheet()->setCellValue('V'.$i, 0 );
					$reader->getActiveSheet()->setCellValue('W'.$i, 0);
					$reader->getActiveSheet()->setCellValue('X'.$i, 0);*/

					$reader->getActiveSheet()->setCellValue('Y'.$i, 0 );
					$reader->getActiveSheet()->setCellValue('Z'.$i, 0);
					$reader->getActiveSheet()->setCellValue('AA'.$i, 0);

					$reader->getActiveSheet()->setCellValue('AB'.$i, 0 );
					$reader->getActiveSheet()->setCellValue('AC'.$i, 0);
					$reader->getActiveSheet()->setCellValue('AD'.$i, 0);

					$reader->getActiveSheet()->setCellValue('AE'.$i, 0 );
					$reader->getActiveSheet()->setCellValue('AF'.$i, 0);
					$reader->getActiveSheet()->setCellValue('AG'.$i, 0);

					$reader->getActiveSheet()->setCellValue('AH'.$i, 0 );
					$reader->getActiveSheet()->setCellValue('AI'.$i, 0);
                    
                    $reader->getActiveSheet()->setCellValue('AK'.$i, strpos($row->proyectoNuevo, ' OOAM') ? '1190-0003' : '6100-0005-0001');
                    $reader->getActiveSheet()->setCellValue('AL'.$i, $row->etapa);
                    $reader->getActiveSheet()->setCellValue('AM'.$i, $row->nombre_reembolso_cch ? $row->nombre_reembolso_cch : 'NA');

					if( $this->input->post("tipo_reporte") == 'pagadas_tdc' ){

						$reader->getActiveSheet()->setCellValue('AM'.$i, $row->fcrea);
						$reader->getActiveSheet()->setCellValue('AO'.$i, $row->fautda);
						$reader->getActiveSheet()->setCellValue('AP'.$i, $row->capturista);

					}

					if( $row->impuestot ){
						$string = json_decode( $row->impuestot, true );
						if( is_array( $string ) ){
							/****RETENCIONES IVA****/
							//TASA 0
							for( $c = 0; $c < count( $string ); $c++ ){
								if( $string[$c]["@attributes"]["Impuesto"] == "002" && isset( $string[$c]["@attributes"]["TasaOCuota"] ) && $string[$c]["@attributes"]["TasaOCuota"] == 0){
									$reader->getActiveSheet()->setCellValue('Y'.$i, $string[$c]["@attributes"]["Impuesto"] );
									$reader->getActiveSheet()->setCellValue('Z'.$i, $string[$c]["@attributes"]["TasaOCuota"]);
									$reader->getActiveSheet()->setCellValue('AA'.$i, $string[$c]["@attributes"]["Importe"]);
									break;
								}
							}
							//TASA MAYOR A 0
							for( $c = 0; $c < count( $string ); $c++ ){
								if( $string[$c]["@attributes"]["Impuesto"] == "002" && isset( $string[$c]["@attributes"]["TasaOCuota"] ) && $string[$c]["@attributes"]["TasaOCuota"] > 0 ){
									$reader->getActiveSheet()->setCellValue('AB'.$i, $string[$c]["@attributes"]["Impuesto"] );
									$reader->getActiveSheet()->setCellValue('AC'.$i, $string[$c]["@attributes"]["TasaOCuota"]);
									$reader->getActiveSheet()->setCellValue('AD'.$i, $string[$c]["@attributes"]["Importe"]);
									break;
								}
							}
							/********/

							/****RETENCIONES TRASLADO IEPS****/
							//TASA 0
							for( $c = 0; $c < count( $string ); $c++ ){
								if( $string[$c]["@attributes"]["Impuesto"] == "003" && isset( $string[$c]["@attributes"]["TasaOCuota"] ) && $string[$c]["@attributes"]["TasaOCuota"] == 0){
									$reader->getActiveSheet()->setCellValue('AE'.$i, $string[$c]["@attributes"]["Impuesto"] );
									$reader->getActiveSheet()->setCellValue('AF'.$i, $string[$c]["@attributes"]["TasaOCuota"]);
									$reader->getActiveSheet()->setCellValue('AG'.$i, $string[$c]["@attributes"]["Importe"]);
									break;
								}
							}
							//TASA MAYOR A 0
							for( $c = 0; $c < count( $string ); $c++ ){
								if( $string[$c]["@attributes"]["Impuesto"] == "003" && isset( $string[$c]["@attributes"]["TasaOCuota"] ) &&  $string[$c]["@attributes"]["TasaOCuota"] > 0 ){
									$reader->getActiveSheet()->setCellValue('AH'.$i, $string[$c]["@attributes"]["Impuesto"] );
									$reader->getActiveSheet()->setCellValue('AI'.$i, $string[$c]["@attributes"]["TasaOCuota"]);
									$reader->getActiveSheet()->setCellValue('AJ'.$i, $string[$c]["@attributes"]["Importe"]);
									break;
								}
							}
							/********/
						}

					}
					/********/
					// $reader->getActiveSheet()->setCellValue('AH'.$i, strpos($row->proyecto, ' OOAM') ? '1190-0003' : '6100-0005-0001');
					//$reader->getActiveSheet()->getStyle("A$i:S$i")->applyFromArray($informacion1);
					$i+=1;
				}
				/**********************/
			}

			$writer =  \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($reader, 'Xls');
			ob_end_clean();

			$temp_file = tempnam(sys_get_temp_dir(), 'PHPExcel');
			$writer->save($temp_file);
			header("Content-Disposition: attachment; filename=REPORTE_".$TIPO_REPORTE."_DESGLOSADO_".date("d/m/Y")."_.xls");

			readfile($temp_file);
			unlink($temp_file);

		}else{
			$this->load->view("vista_historial_cch");
		}
	}

    public function autorizacion_yola(){
        echo json_encode( array( "data" => $this->M_historial->autorizacion_yola()->result_array() ));
    }

    function TablaHistorialSolicitudesV() {
        $data = $this->M_historial->getHistorialTablaSolViaticos( $this->input->post("finicial"), $this->input->post("ffinal") )->result_array();
        echo json_encode( array( "data" => $data ) );
    }
    public function reporte_pv(){
        echo json_encode( array( "data" => $this->M_historial->reporte_pv()->result_array() ));
    }

    public function historial_pagos_parcialidades(){ /**  Obtención del lisado de las devoluciones por parcialidades para el historial| FECHA: 16-Mayo-2024 | @author Lalo Silva <programador.analista38@ciudadmaderas.com>  **/
        //if( in_array( $this->session->userdata("inicio_sesion")['rol'], array('GAD','AD', 'CAD', 'CC', 'CE', 'CI', 'CP', 'DA', 'PV', 'SU')) )
            $this->load->view("v_historial_parcialidades"); /**  FIN Obtención del lisado de las devoluciones por parcialidades para el historial| FECHA: 16-Mayo-2024 | @author Lalo Silva <programador.analista38@ciudadmaderas.com>  **/
    }

}
