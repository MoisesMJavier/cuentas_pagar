<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Correo_reporte_provision extends CI_Controller {

 public function index(){

    if( date( "d" ) == 11 ){
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
      
          $fecha_ahora = date('Y-m-d');
         $fecha_pasada = date('Y-m-01',strtotime($fecha_ahora."- 1 month")); 
 

            $reporte = $this->db->query("SELECT s.idsolicitud, s.folio, s.metoPago, CONCAT(uC.nombres,' ',uC.apellidos) AS usuar, CONCAT(uR.nombres,' ',uR.apellidos) AS Responsable, s.justificacion, s.nomdepto, p.nombre as nomprov, e.abrev as nempr, s.cantidad, s.moneda, s.fechaCreacion, s.fecelab, IF( s.tendrafac=1, 'SI', 'NO') AS tendrafac, f.uuid, etapas.nombre AS nom_etapa FROM solpagos AS s INNER JOIN facturas AS f ON f.idsolicitud = s.idsolicitud INNER JOIN usuarios AS uC ON uC.idusuario = s.idusuario INNER JOIN usuarios AS uR ON uR.idusuario = s.idResponsable INNER JOIN empresas AS e ON e.idempresa = s.idEmpresa INNER JOIN proveedores AS p ON p.idproveedor = s.idProveedor INNER JOIN etapas ON s.idetapa = etapas.idetapa WHERE f.tipo_factura = 1 and s.idsolicitud not in (SELECT idsolicitud from polizas_provision) and s.fecelab BETWEEN '".$fecha_pasada."' AND '".$fecha_ahora."'");
 

                //$abono =  $this->db->query("SELECT nombres FROM usuarios WHERE idusuario='3'" );
                //$pendiente =  $this->db->query("SELECT nombres FROM usuarios WHERE idusuario='3'" );

 
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
                $reader->getActiveSheet()->setCellValue('N'.$i, 'ABONADO');
                $reader->getActiveSheet()->setCellValue('O'.$i, 'PENDIENTE');
                $reader->getActiveSheet()->setCellValue('P'.$i, '¿TIENE FACTURA?');
                $reader->getActiveSheet()->setCellValue('Q'.$i, 'ETAPA');
                $reader->getActiveSheet()->setCellValue('R'.$i, 'FOLIO FISCAL');
 
                $reader->getActiveSheet()->getStyle('A1:Q1')->applyFromArray($encabezados);

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
                $reader->getActiveSheet()->getColumnDimension('Q')->setWidth(18);
                $reader->getActiveSheet()->getColumnDimension('R')->setWidth(50);
                 
 
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
                            $reader->getActiveSheet()->setCellValue('N'.$i, number_format($row->cantidad, 2, '.', '')); 
                            $reader->getActiveSheet()->setCellValue('O'.$i, $row->cantidad);
                            $reader->getActiveSheet()->setCellValue('P'.$i, $row->tendrafac);
                            $reader->getActiveSheet()->setCellValue('Q'.$i, $row->nom_etapa);
                            $reader->getActiveSheet()->setCellValue('R'.$i, $row->uuid); 

                            $reader->getActiveSheet()->getStyle("A$i:R$i")->applyFromArray($informacion);
                            $i+=1;

                        }
                    }

                $writer =  \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($reader, 'Xls');
                ob_end_clean();
            
                //$temp_file = tempnam(sys_get_temp_dir(), 'PHPExcel');
                $temp_file = "REPORTES_EXCEL_PROVISION_".date("dmY").".xls";
                header("Content-Disposition: attachment; filename=$temp_file");
                
                $writer->save( $temp_file );

                //readfile($temp_file);
                if( $this->recordatorio_automatico( $temp_file ) )
                    unlink($temp_file);
        }
    }





    public function recordatorio_automatico( $temp_file ){  
        
        $this->load->library('email');
        
        $fecha_ahora = date('Y-m-d');
        $fecha_pasada = date('Y-m-01',strtotime($fecha_ahora."- 1 month")); 
  
        $this->email->from('noreplay@ciudadmaderas.com', 'REPORTE FACTURAS SIN PROVISIÓN');
        //$this->email->to(array('programador.analista6@ciudadmaderas.com'));
        $this->email->to(array('coord.desarrollo@ciudadmaderas.com', 'maricela.velazquez@ciudadmaderas.com', 'coord.cuentasporpagar@ciudadmaderas.com', 'coord.contabilidad1@ciudadmaderas.com'));
        $this->email->subject('REPORTE DE FACTURAS SIN PROVISIONAR '.$fecha_pasada.' / '.$fecha_ahora);
        $this->email->message('<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta http-equiv="X-UA-Compatible" content="ie=edge">
            <title>Document</title>
            <style>
                body{width: 100%;height: 100vh;top: 0;left: 0;margin: 0;padding: 0;font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, Oxygen, Ubuntu, Cantarell, \'Open Sans\', \'Helvetica Neue\', sans-serif;}
                img{width:35%;height: auto;margin: 1em 25%;padding: 0;}
                p{width: 50%;height: auto;margin: 2em 25%;padding: 0;text-align: justify;}
                @media only screen and (max-width: 1024px) {
                    img{width:75%;height: auto;margin: 1em 12.5%;padding: 0;}
                    p{width: 75%;height: auto;margin: 2em 12.5%;padding: 0;text-align: justify;}}
            </style>
        </head>
        <body>
            <img src="https://www.ciudadmaderas.com/assets/img/logo.png" alt="Ciudad Maderas" title="Ciudad Maderas">
            <p>Estimado usuario,<br>
Se ha programado un reporte del registro de facturas no provisionadas, con fecha de ingreso del <b>'.convertDates( date('Y-m-01',strtotime($fecha_ahora."- 1 month")) ).'</b> al <b>'.convertDates( date('Y-m-d') ).'</b>.
 </p><p>Sistema de cuentas por pagar.</p>
       <p style="font-size:10px;">Este correo fue generado de manera automática, te pedimos no respondas este correo, para cualquier duda o aclaración envía un correo a soporte@ciudadmaderas.com</p>     
        </body>
        </html>');

           $this->email->attach( $temp_file );
           return $this->email->send();
      }
}

    function convertDates($fecha1){
        $fecha_ahora = date('Y-m-d');
        $fecha_pasada = date('Y-m-01',strtotime($fecha_ahora."- 1 month")); 
 
        $fecha = substr($fecha1, 0); 
        list($anio, $mes, $dia) = explode("-",$fecha);
        switch ($mes) { 
            case 1:return '<b>'.$dia.' Enero '.$anio.'</b>';break; case 2:return '<b>'.$dia.' Febrero '.$anio.'</b>';break;
            case 3: return '<b>'.$dia.' Marzo '.$anio.'</b>';break;case 4:return '<b>'.$dia.' Abril '.$anio.'</b>';break;
            case 5: return '<b>'.$dia.' Mayo '.$anio.'</b>';break; case 6:return '<b>'.$dia.' Junio '.$anio.'</b>';break;
            case 7:return '<b>'.$dia.' Julio '.$anio.'</b>';break; case 8:return '<b>'.$dia.' Agosto '.$anio.'</b>';break;
            case 9:return '<b>'.$dia.' Septiembre '.$anio.'</b>';break;case 10:return '<b>'.$dia.' Octubre '.$anio.'</b>';break;
            case 11:return '<b>'.$dia.' Noviembre '.$anio.'</b>';break;case 12:return '<b>'.$dia.' Diciembre '.$anio.'</b>';break;
            default: break;
        }
    }

 