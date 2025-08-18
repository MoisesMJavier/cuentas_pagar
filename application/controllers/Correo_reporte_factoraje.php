<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Correo_reporte_factoraje extends CI_Controller
{

    public function index()
    {

        $this->CargaEdoCuentaBB();
        $this->recordatorio_pago_programado();
        $this->recordatorio_cumples();
        //$this->xml_honorarios();

        if ($this->db->query("SELECT * from solpagos as s WHERE s.idetapa NOT IN ( 10, 11 ) AND s.metoPago LIKE 'FACT%' AND ( DATEDIFF(CURDATE(), IFNULL( s.fecha_fin, s.fecelab) ) = ( IFNULL( s.dfacturaje, 180) - 5 ) OR DATEDIFF( CURDATE(), IFNULL( s.fecha_fin, s.fecelab) ) = ( IFNULL( s.dfacturaje, 180) - 1 ) )")->num_rows() > 0) {

            $reader = new PhpOffice\PhpSpreadsheet\Spreadsheet();
            $encabezados = [
                'font' => [
                    'color' => ['argb' => '00000000'],
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

            $reporte = $this->db->query("SELECT s.idsolicitud, s.folio, s.metoPago, s.fecha_fin, s.dfacturaje, CONCAT(uC.nombres,' ',uC.apellidos) AS usuar, CONCAT(uR.nombres,' ',uR.apellidos) AS Responsable, s.justificacion, s.nomdepto, p.nombre as nomprov, e.abrev as nempr, s.cantidad, s.moneda, s.fechaCreacion, s.fecelab FROM solpagos s  INNER JOIN usuarios AS uC ON uC.idusuario = s.idusuario INNER JOIN usuarios AS uR ON uR.idusuario = s.idResponsable INNER JOIN empresas AS e ON e.idempresa = s.idEmpresa INNER JOIN proveedores AS p ON p.idproveedor = s.idProveedor WHERE s.idetapa NOT IN ( 10, 11 ) AND s.metoPago LIKE 'FACT%' AND ( DATEDIFF(CURDATE(), IFNULL( s.fecha_fin, s.fecelab) ) = ( IFNULL( s.dfacturaje, 180) - 5 ) OR DATEDIFF( CURDATE(), IFNULL( s.fecha_fin, s.fecelab) ) = ( IFNULL( s.dfacturaje, 180) - 1 ) )");

            $i = 1;

            $reader->getActiveSheet()->setCellValue('A' . $i, 'ID SOLICITUD');
            $reader->getActiveSheet()->setCellValue('B' . $i, 'FOLIO');
            $reader->getActiveSheet()->setCellValue('C' . $i, 'FORMA PAGO');
            $reader->getActiveSheet()->setCellValue('D' . $i, 'RESPONSABLE');
            $reader->getActiveSheet()->setCellValue('E' . $i, 'CAPTURISTA');
            $reader->getActiveSheet()->setCellValue('F' . $i, 'JUSTIFICACIÓN');
            $reader->getActiveSheet()->setCellValue('G' . $i, 'DEPARTAMENTO');
            $reader->getActiveSheet()->setCellValue('H' . $i, 'PROVEEDOR');
            $reader->getActiveSheet()->setCellValue('I' . $i, 'EMPRESA');
            $reader->getActiveSheet()->setCellValue('J' . $i, 'CANTIDAD');
            $reader->getActiveSheet()->setCellValue('K' . $i, 'FECHA FACTURA');
            $reader->getActiveSheet()->setCellValue('L' . $i, 'FECHA PUBLICACION');
            $reader->getActiveSheet()->setCellValue('M' . $i, 'DIAS FACTORAJE');
            $reader->getActiveSheet()->setCellValue('N' . $i, 'FECHA DE VENCIMIENTO');

            $reader->getActiveSheet()->getStyle('A1:K1')->applyFromArray($encabezados);

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
            $reader->getActiveSheet()->getColumnDimension('L')->setWidth(15);
            $reader->getActiveSheet()->getColumnDimension('M')->setWidth(15);

            $i += 1;
            if ($reporte->num_rows() > 0) {

                foreach ($reporte->result() as $row) {
                    $reader->getActiveSheet()->setCellValue('A' . $i, $row->idsolicitud);
                    $reader->getActiveSheet()->setCellValue('B' . $i, $row->folio);
                    $reader->getActiveSheet()->setCellValue('C' . $i, $row->metoPago);
                    $reader->getActiveSheet()->setCellValue('D' . $i, $row->Responsable);
                    $reader->getActiveSheet()->setCellValue('E' . $i, $row->usuar);
                    $reader->getActiveSheet()->setCellValue('F' . $i, $row->justificacion);
                    $reader->getActiveSheet()->setCellValue('G' . $i, $row->nomdepto);
                    $reader->getActiveSheet()->setCellValue('H' . $i, $row->nomprov);
                    $reader->getActiveSheet()->setCellValue('I' . $i, $row->nempr);
                    $reader->getActiveSheet()->setCellValue('J' . $i, number_format($row->cantidad, 2, '.', ''));
                    $reader->getActiveSheet()->setCellValue('K' . $i, $row->fecelab);
                    $reader->getActiveSheet()->setCellValue('L' . $i, $row->fecha_fin ? $row->fecha_fin : "NO DEFINIDA");
                    $reader->getActiveSheet()->setCellValue('M' . $i, $row->dfacturaje);
                    $reader->getActiveSheet()->setCellValue('N' . $i, date("d-m-Y", strtotime(($row->fecha_fin ? $row->fecha_fin : $row->fecelab) . "+ " . $row->dfacturaje . " days")));


                    $reader->getActiveSheet()->getStyle("A$i:K$i")->applyFromArray($informacion);
                    $i += 1;
                }
            }

            $writer =  \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($reader, 'Xls');
            ob_end_clean();

            //$temp_file = tempnam(sys_get_temp_dir(), 'PHPExcel');
            $temp_file = "REPORTES_EXCEL_FACTORAJE_" . date("dmY") . ".xls";
            header("Content-Disposition: attachment; filename=$temp_file");

            $writer->save($temp_file);

            //readfile($temp_file);
            if ($this->recordatorio_automatico_FACTORAJE($temp_file))
                unlink($temp_file);
        }


        if (date("l") == 'Monday') {

            $this->apoyemosamexico = $this->load->database('default1', true);

            $reader = new PhpOffice\PhpSpreadsheet\Spreadsheet();
            $encabezados = [
                'font' => [
                    'color' => ['argb' => '00000000'],
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
            $finicio = date("Y-m-d", strtotime("-1 day", strtotime(date("Y-m-d"))));
            $ffin = date("Y-m-d", strtotime("-7 day", strtotime($finicio)));

            echo "SELECT * FROM apoyemosmx.registros WHERE STR_TO_DATE(fr, '%Y-%m-%d') BETWEEN '$ffin' AND '$finicio'";
            $resultado = $this->apoyemosamexico->query("SELECT * FROM apoyemosmx.registros WHERE STR_TO_DATE(fr, '%Y-%m-%d') BETWEEN '$ffin' AND '$finicio'");

            var_dump($resultado->result_array());

            $i = 1;

            $reader->getActiveSheet()->setCellValue('A' . $i, 'NOMBRE(S)');
            $reader->getActiveSheet()->setCellValue('B' . $i, 'APELLIDOS');
            $reader->getActiveSheet()->setCellValue('C' . $i, 'REFERENCIA');
            $reader->getActiveSheet()->setCellValue('D' . $i, 'MES');
            $reader->getActiveSheet()->setCellValue('E' . $i, 'FECHA REGISTRO');
            $reader->getActiveSheet()->setCellValue('F' . $i, 'VIDEO');
            $reader->getActiveSheet()->setCellValue('G' . $i, 'LIGA');
            $reader->getActiveSheet()->getStyle('A1:G1')->applyFromArray($encabezados);
            $i += 1;
            if ($resultado->num_rows() > 0) {

                foreach ($resultado->result() as $row) {
                    $reader->getActiveSheet()->setCellValue('A' . $i, $row->nombre_cliente);
                    $reader->getActiveSheet()->setCellValue('B' . $i, $row->apellido_cliente);
                    $reader->getActiveSheet()->setCellValue('C' . $i, $row->referencia);
                    $reader->getActiveSheet()->setCellValue('D' . $i, $row->mes);
                    $reader->getActiveSheet()->setCellValue('E' . $i, $row->fr);
                    $reader->getActiveSheet()->setCellValue('F' . $i, $row->nombre_video);
                    $reader->getActiveSheet()->setCellValue('G' . $i, "https://drive.google.com/open?id=" . $row->liga_video);

                    $reader->getActiveSheet()->getStyle("A$i:G$i")->applyFromArray($informacion);
                    $i += 1;
                }

                $this->load->library('email');
                $this->email->from('noreplay@ciudadmaderas.com', 'APOYEMOS A MEXICO');
                //$this->email->to(array('programador.analista6@ciudadmaderas.com'));
                $this->email->to('ayudemosamexico@ciudadmaderas.com, coord.cobranza@ciudadmaderas.com');
                $this->email->subject('REPORTE APOYEMOS A MEXICO');
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
                <p style="font-size:10px;">Este correo fue generado de manera automática, te pedimos no respondas este correo, para cualquier duda o aclaración envía un correo a soporte@ciudadmaderas.com</p>     
            </body>
            </html>');

                $writer =  \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($reader, 'Xls');
                ob_end_clean();

                $apoyemos_mexico = "REPORTE_APOYEMOS_MEXICO_" . $finicio . $ffin . ".xls";
                header("Content-Disposition: attachment; filename=$apoyemos_mexico");

                $writer->save($apoyemos_mexico);
                $this->email->attach($apoyemos_mexico);

                if ($this->email->send())
                    $this->email->clear(TRUE);
                unlink($apoyemos_mexico);
            }
        }
    }

    //RECORDATORIO PARA LOS PAGOS PROGRAMADOS
    public function recordatorio_pago_programado()
    {

        $pagos_programados_pendientes = 
            $this->db->query("SELECT IFNULL(ptotales, 0) AS ptotales, 
                                     tparcial,
                                     IF(solpagos.fecha_fin IS NULL, 'SIN DEFINIR', ROUND( CASE WHEN solpagos.programado < 7 THEN TIMESTAMPDIFF(MONTH, CONCAT( YEAR(solpagos.fecelab),'-',MONTH(solpagos.fecelab),'-', DAY(solpagos.fecha_fin) ), solpagos.fecha_fin) / solpagos.programado ELSE TIMESTAMPDIFF(WEEK, solpagos.fecelab, solpagos.fecha_fin) END ) ) ppago,
                                     (CASE 
                                        WHEN solpagos.programado < 7 THEN 
                                            DATE_ADD( solpagos.fecelab, INTERVAL ( IFNULL(ptotales, 0) / solpagos.programado ) MONTH ) 
                                        ELSE DATE_ADD( solpagos.fecelab, INTERVAL IFNULL(ptotales, 0) WEEK ) 
                                     END ) AS proximo_pago,
                                     solpagos.programado,
                                     solpagos.idsolicitud,
                                     solpagos.justificacion,
                                     solpagos.metoPago,
                                     solpagos.fecha_fin,
                                     proveedores.nombre,
                                     solpagos.intereses,
                                     solpagos.cantidad,
                                     (autpagos.cantidad + autpagos.interes) AS interes_agregado,
                                     autpagos.interes,
                                     solpagos.fecelab fecreg,
                                     solpagos.nomdepto,
                                     empresas.abrev AS nemp,
                                     nombre_capturista,
                                     estatus_ultimo_pago
                            FROM solpagos
                            INNER JOIN proveedores 
                                ON proveedores.idproveedor = solpagos.idProveedor
                            INNER JOIN empresas
                                ON solpagos.idempresa = empresas.idempresa
                            LEFT JOIN ( SELECT  autpagos.idsolicitud, 
                                                COUNT(autpagos.idpago) AS ptotales, 
                                                SUM(autpagos.cantidad) AS tparcial,
                                                SUM( IF(estatus IN ( 14, 16), autpagos.cantidad,0) ) AS cantidad, 
                                                SUM( IF(estatus IN ( 14, 16), autpagos.interes, 0) ) AS interes, 
                                                MAX( autpagos.fecreg ) AS ultimo_pago 
                                        FROM autpagos 
                                        GROUP BY autpagos.idsolicitud ) AS autpagos
                                ON solpagos.idsolicitud = autpagos.idsolicitud
                            LEFT JOIN ( SELECT autpagos.idsolicitud, autpagos.estatus estatus_ultimo_pago 
                                        FROM autpagos 
                                        WHERE autpagos.estatus NOT IN ( 14, 16) 
                                        GROUP BY autpagos.idsolicitud 
                                        HAVING MAX(autpagos.idpago) ) AS estatus_pago
                                ON estatus_pago.idsolicitud = solpagos.idsolicitud
                            INNER JOIN ( SELECT usuarios.idusuario, CONCAT(usuarios.nombres,' ', usuarios.apellidos) AS nombre_capturista FROM usuarios ) AS usuarios 
                                ON usuarios.idusuario = solpagos.idusuario
                        WHERE solpagos.programado IS NOT NULL AND 
                              solpagos.idetapa NOT IN ( 11, 30, 2 ) AND 
                              ( DATEDIFF( ( CASE 
                                                WHEN solpagos.programado < 7 THEN 
                                                    DATE_ADD( solpagos.fecelab, INTERVAL ( IFNULL(ptotales, 0) / solpagos.programado ) MONTH )
                                                ELSE DATE_ADD( solpagos.fecelab, INTERVAL IFNULL(ptotales, 0) WEEK )
                                            END ) , NOW()) <= 2 )
                        ORDER BY proveedores.nombre ASC");
        if ($pagos_programados_pendientes->num_rows() > 0) {

            $reader = new PhpOffice\PhpSpreadsheet\Spreadsheet();
            $encabezados = [
                'font' => [
                    'color' => ['argb' => '00000000'],
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

            $reader->getActiveSheet()->setCellValue('A' . $i, 'PROVEEDOR');
            $reader->getActiveSheet()->setCellValue('B' . $i, 'PAGOS HECHOS');
            $reader->getActiveSheet()->setCellValue('C' . $i, 'TOTAL PAGADO');
            $reader->getActiveSheet()->setCellValue('D' . $i, 'PROX PAGO');
            $reader->getActiveSheet()->setCellValue('E' . $i, 'CANTIDAD');


            $reader->getActiveSheet()->getStyle('A1:E1')->applyFromArray($encabezados);

            $i += 1;
            foreach ($pagos_programados_pendientes->result() as $row) {
                $reader->getActiveSheet()->setCellValue('A' . $i, $row->nombre);
                $reader->getActiveSheet()->setCellValue('B' . $i, $row->ptotales);
                $reader->getActiveSheet()->setCellValue('C' . $i, $row->tparcial);
                $reader->getActiveSheet()->setCellValue('D' . $i, $row->proximo_pago);
                $reader->getActiveSheet()->setCellValue('E' . $i, $row->cantidad);


                $reader->getActiveSheet()->getStyle("A$i:E$i")->applyFromArray($informacion);
                $i += 1;
            }

            $this->load->library('email');
            $this->email->from('noreplay@ciudadmaderas.com', 'REPORTE PAGOS DE PROGRAMADOS');
            //$this->email->to(array('programador.analista6@ciudadmaderas.com'));
            $this->email->to('maricela.velazquez@ciudadmaderas.com, coord.cuentasporpagar@ciudadmaderas.com');
            $this->email->subject('REPORTE DE PAGOS PROGRAMADOS A VENCER');
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
                Se ha programado un reporte de los pagos ha vencer y que estan a poco tiempo de ser atendidos de acuerdo a la fecha de pago, se anexa archivo en excel.</b>. 
                </p><p>Sistema de cuentas por pagar.</p>
                <p style="font-size:10px;">Este correo fue generado de manera automática, te pedimos no respondas este correo, para cualquier duda o aclaración envía un correo a soporte@ciudadmaderas.com</p>     
            </body>
            </html>');

            $writer =  \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($reader, 'Xls');
            ob_end_clean();

            $temp_file = "REPORTES_EXCEL_PAGOS_PROGRAMADOS_" . date("dmY") . ".xls";
            header("Content-Disposition: attachment; filename=$temp_file");

            $writer->save($temp_file);
            $this->email->attach($temp_file);

            if ($this->email->send())
                $this->email->clear(TRUE);
            unlink($temp_file);
        }
    }

    public function recordatorio_automatico_FACTORAJE($temp_file){
        $this->load->library('email');

        // $fecha_ahora = date('Y-m-d');
        // $fecha_pasada = date('Y-m-01',strtotime($fecha_ahora."- 1 month")); 

        $this->email->from('noreplay@ciudadmaderas.com', 'REPORTE PAGOS DE FACTORAJE');
        //$this->email->to(array('programador.analista6@ciudadmaderas.com'));
        $this->email->to('coord.desarrollo@ciudadmaderas.com, maricela.velazquez@ciudadmaderas.com, coord.cuentasporpagar@ciudadmaderas.com');
        $this->email->subject('REPORTE DE PAGOS DE HA VENCER');
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
            Se ha programado un reporte de los pagos ha vencer y que estan a poco tiempo de ser atendidos de acuerdo a la fecha de factura, se anexa archivo en excel.</b>. 
            </p><p>Sistema de cuentas por pagar.</p>
            <p style="font-size:10px;">Este correo fue generado de manera automática, te pedimos no respondas este correo, para cualquier duda o aclaración envía un correo a soporte@ciudadmaderas.com</p>     
        </body>
        </html>');


        $this->email->attach($temp_file);
        if ($this->email->send()) {
            $this->email->clear(TRUE);
            unlink($temp_file);
            return true;
        } else {
            return false;
        }
    }

    public function recordatorio_cumples()
    {
        $listado_festejados = $this->db->query("SELECT 
            pe.idpersona,
            CONCAT( pe.nombre_persona,' ',pe.apellido_paterno_persona ) npersona,
            pe.apellido_materno_persona,
            pe.fecha_cumple,
            pe.dia,
            p.nom_puesto,
            a.nom_area
        FROM (
            SELECT *, 0 dia FROM recursos_humanos.persona p WHERE DATE_SUB(STR_TO_DATE(CONCAT(SUBSTRING(p.fecha_cumple,1,6),YEAR(CURDATE())), '%d/%m/%Y'), INTERVAL 1 DAY) = CURDATE()
            UNION
            SELECT *, 1 dia FROM recursos_humanos.persona p WHERE STR_TO_DATE(CONCAT(SUBSTRING(p.fecha_cumple,1,6),YEAR(CURDATE())), '%d/%m/%Y') = CURDATE()
        ) pe
        CROSS JOIN ( SELECT * FROM recursos_humanos.contrato WHERE estatus_vacante = 1 ) c ON c.idpersona = pe.idpersona
        CROSS JOIN ( SELECT idarea, nom_area FROM recursos_humanos.areas ) a ON a.idarea = c.departamento
        CROSS JOIN ( SELECT idpuesto, nom_puesto FROM recursos_humanos.puesto WHERE nhp = 1 ) p ON p.idpuesto = c.puesto 
        ORDER BY pe.dia, npersona, pe.fecha_cumple ASC");

        if ($listado_festejados->num_rows() > 0) {
            $this->load->library('email');

            $festejados = '';
            $prox_festejados = '';

            foreach ($listado_festejados->result() as $row) {
                if ($row->dia == 1) {
                    if ($festejados == '') {
                        $festejados = '<table border="0" width="100%" cellpadding="0" cellspacing="0" bgcolor="ffffff" class="bg_color">
                            <tr>
                                <td align="center">
                                    <table border="0" align="center" width="590" cellpadding="0" cellspacing="0" class="container590">

                                        <tr>
                                            <td align="center" style="color: #343434; font-size: 24px; font-family: Quicksand, Calibri, sans-serif; font-weight:700;letter-spacing: 3px; line-height: 35px;" class="main-header">
                                                <!-- ======= section text ====== -->
                                                <div style="line-height: 35px">
                                                    MUCHAS FELICIDADES A NUESTROS COMPAÑEROS
                                                </div>
                                            </td>
                                        </tr>

                                        <tr class="hide">
                                            <td height="15" style="font-size: 15px; line-height: 15px;">&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td height="25" style="font-size: 25px; line-height: 25px;">&nbsp;</td>
                                        </tr>

                                        <tr>
                                            <td>';
                    }

                    $festejados .= '
                        <table border="0" width="40" align="left" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="container590">
                            <tr>
                                <td width="40" height="50" style="font-size: 50px; line-height: 50px;">
                                    <a href="" style=" border-style: none !important; display: block; border: 0 !important;"><img src="https://rh.gphsis.com/FOTOS_COLABORADORES/FOTO' . $row->idpersona . '.jpg?=' . random_int(0, 10) . '" style="display: block; width: 170PX;" width="170" border="0" alt="" /></a>
                                </td>
                            </tr>
                        </table>
                        <table border="0" width="380" align="left" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="container590">
                        <tr>
                            <td height="15" style="font-size: 15px; line-height: 15px;">&nbsp;</td>
                        </tr>

                        <tr>
                            <td align="center" style="color: #333333; font-size: 16px; font-family: "Work Sans", Calibri, sans-serif; line-height: 20px;">
                                <!-- ======= section text ====== -->
                                <div style="line-height: 20px">
                                    <h3 style="margin-top: 0;">' . $row->npersona . ' ' . $row->apellido_materno_persona . '</h3>
                                    <h2>' . $this->calcular_edad($row->fecha_cumple) . ' años<h2>
                                    <h3>' . $row->fecha_cumple . '</h3>
                                    <h3>' . $row->nom_puesto . '</h3>
                                    <h3><small>' . $row->nom_area . '</small></h3>
                                    <hr/>
                                </div>
                            </td>
                        </tr>

                    </table>';
                }

                if ($row->dia == 0) {
                    if ($prox_festejados == '') {
                        $prox_festejados = '<table border="0" width="100%" cellpadding="0" cellspacing="0" bgcolor="ffffff" class="bg_color">
                            <tr class="hide">
                                <td height="25" style="font-size: 25px; line-height: 25px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td height="15" style="color: #333333; font-size: 18px; font-family: "Work Sans", Calibri, sans-serif; line-height: 20px;">
                                    <div style="line-height: 20px; text-align: center;">
                                        <p>¡PROXIMOS CUMPLEAÑOS!</p>
                                    </div>
                                </td>
                            </tr>
                            <tr>';
                    }

                    $prox_festejados .= '
                        <table border="0" width="170" height="100" align="left" cellpadding="0" cellspacing="0" style="margin-left: 5%; margin-rigth: 5%; border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="container590">
                        <tr>
                            <!-- ======= image ======= -->
                            <td align="center">
                                <a href="" style=" border-style: none !important; display: block; border: 0 !important;"><img src="https://rh.gphsis.com/FOTOS_COLABORADORES/FOTO' . $row->idpersona . '.jpg?=' . random_int(0, 10) . '" style="display: block; width: 170PX;" width="170" border="0" alt="" /></a>
                            </td>
                        </tr>
                        <tr>
                            <td height="15" style="font-size: 15px; line-height: 15px;">&nbsp;</td>
                        </tr>
                        <tr>
                        <td align="center" style="color: #333333; font-size: 14px; font-family: "Work Sans", Calibri, sans-serif; font-weight: 500; line-height: 20px;">
                            <div style="line-height: 20px">
                                <h3 style="margin-top: 0;">' . $row->npersona . ' ' . $row->apellido_materno_persona . '</h3>
                                <h3>' . $row->fecha_cumple . '</h3>
                                <h3>' . $row->nom_puesto . '</h3>
                            </div>
                        </td>
                        </tr>
                    </table>';
                }
            }

            if ($festejados != '') {
                $festejados .=  '</td>
                                </tr>
                                <tr class="hide">
                                    <td height="15" style="font-size: 15px; line-height: 15px;">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td height="25" style="font-size: 25px; line-height: 25px;">&nbsp;</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>';
            }

            if ($prox_festejados != '') {
                $prox_festejados .= '</tr><tr>
                            <td height="60" style="border-top: 1px solid #e0e0e0;font-size: 60px; line-height: 60px;">&nbsp;</td>
                        </tr>
                    </table>';
            }


            $this->email->from('noreply@ciudadmaderas.com', 'NO REPLY');
            $this->email->to('perla.mascareno@ciudadmaderas.com,coord.desarrollo@ciudadmaderas.com');
            $this->email->subject('¡FESTEJADOS CIUDAD MADERAS!');
            $this->email->message('<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
            <html xmlns:v="urn:schemas-microsoft-com:vml">
            
            <head>
                <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;" />
                <!--[if !mso]--><!-- -->
                <link href="https://fonts.googleapis.com/css?family=Work+Sans:300,400,500,600,700" rel="stylesheet">
                <link href="https://fonts.googleapis.com/css?family=Quicksand:300,400,700" rel="stylesheet">
                <!-- <![endif]-->
            
                <title>¡MUCHAS FELICIDADES!</title>
            
                <style type="text/css">
                    body {
                        width: 100%;
                        background-color: #ffffff;
                        margin: 0;
                        padding: 0;
                        -webkit-font-smoothing: antialiased;
                        mso-margin-top-alt: 0px;
                        mso-margin-bottom-alt: 0px;
                        mso-padding-alt: 0px 0px 0px 0px;
                    }
                    /*
                    p,
                    h1,
                    h2,
                    h3,
                    h4 {
                        margin-top: 0;
                        margin-bottom: 0;
                        padding-top: 0;
                        padding-bottom: 0;
                    }
                    */
                    span.preheader {
                        display: none;
                        font-size: 1px;
                    }
                    
                    html {
                        width: 100%;
                    }
                    
                    table {
                        font-size: 14px;
                        border: 0;
                    }
                    /* ----------- responsivity ----------- */
                    
                    @media only screen and (max-width: 640px) {
                        /*------ top header ------ */
                        .main-header {
                            font-size: 20px !important;
                        }
                        .main-section-header {
                            font-size: 28px !important;
                        }
                        .show {
                            display: block !important;
                        }
                        .hide {
                            display: none !important;
                        }
                        .align-center {
                            text-align: center !important;
                        }
                        .no-bg {
                            background: none !important;
                        }
                        /*----- main image -------*/
                        .main-image img {
                            width: 440px !important;
                            height: auto !important;
                        }
                        /* ====== divider ====== */
                        .divider img {
                            width: 440px !important;
                        }
                        /*-------- container --------*/
                        .container590 {
                            width: 440px !important;
                        }
                        .container580 {
                            width: 400px !important;
                        }
                        .main-button {
                            width: 220px !important;
                        }
                        /*-------- secions ----------*/
                        .section-img img {
                            width: 320px !important;
                            height: auto !important;
                        }
                        .team-img img {
                            width: 100% !important;
                            height: auto !important;
                        }
                    }
                    
                    @media only screen and (max-width: 479px) {
                        /*------ top header ------ */
                        .main-header {
                            font-size: 18px !important;
                        }
                        .main-section-header {
                            font-size: 26px !important;
                        }
                        /* ====== divider ====== */
                        .divider img {
                            width: 280px !important;
                        }
                        /*-------- container --------*/
                        .container590 {
                            width: 280px !important;
                        }
                        .container590 {
                            width: 280px !important;
                        }
                        .container580 {
                            width: 260px !important;
                        }
                        /*-------- secions ----------*/
                        .section-img img {
                            width: 280px !important;
                            height: auto !important;
                        }
                    }
                </style>
                <!-- [if gte mso 9]><style type=”text/css”>
                    body {
                    font-family: arial, sans-serif!important;
                    }
                    </style>
                <![endif]-->
            </head>
            
            
            <body class="respond" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
                <!-- pre-header -->
                <table style="display:none!important;">
                    <tr>
                        <td>
                            <div style="overflow:hidden;display:none;font-size:1px;color:#ffffff;line-height:1px;font-family:Arial;maxheight:0px;max-width:0px;opacity:0;">
                                Pre-header for the newsletter template
                            </div>
                        </td>
                    </tr>
                </table>
                <!-- pre-header end -->
                <!-- header -->
                <table border="0" width="100%" cellpadding="0" cellspacing="0" bgcolor="ffffff">
            
                    <tr>
                        <td align="center">
                            <table border="0" align="center" width="590" cellpadding="0" cellspacing="0" class="container590">
            
                                <tr>
                                    <td height="25" style="font-size: 25px; line-height: 25px;">&nbsp;</td>
                                </tr>
            
                                <tr>
                                    <td align="center">
                                        <table border="0" align="center" width="590" cellpadding="0" cellspacing="0" class="container590">
                                            <tr>
                                                <td align="center" height="70" style="height:70px;">
                                                    <img src="https://cuentas.gphsis.com/img/hbdemail.jpg" width="50%">
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
            
                                <tr>
                                    <td height="25" style="font-size: 25px; line-height: 25px;">&nbsp;</td>
                                </tr>
            
                            </table>
                        </td>
                    </tr>
                </table>
                ' . $festejados . '
                ' . $prox_festejados . '
                <table border="0" width="100%" cellpadding="0" cellspacing="0" bgcolor="f4f4f4">
                    <tr>
                        <td height="25" style="font-size: 25px; line-height: 25px;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td align="center">
            
                            <table border="0" align="center" width="590" cellpadding="0" cellspacing="0" class="container590">
            
                                <tr>
                                    <td>
                                        <table border="0" align="left" width="5" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="container590">
                                            <tr>
                                                <td height="20" width="5" style="font-size: 20px; line-height: 20px;">&nbsp;</td>
                                            </tr>
                                        </table>
            
                                        <table border="0" align="right" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="container590">
            
                                            <tr>
                                                <td align="center">
                                                    <table align="center" border="0" cellpadding="0" cellspacing="0">
                                                        <tr>
                                                            <td align="center">
                                                                <p style="font-size: 14px; font-family: "Work Sans", Calibri, sans-serif; line-height: 24px;color: #333333; text-decoration: none;font-weight:bold;">DESARROLLO, MKTD</p>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
            
                            </table>
                        </td>
                    </tr>
                    <tr><td height="25" style="font-size: 25px; line-height: 25px;">&nbsp;</td></tr>
                </table></body></html>');
            if ($this->email->send()) {
                $this->email->clear(TRUE);
                return true;
            } else {
                return false;
            }
        }
    }

    function xml_honorarios()
    {
        $this->load->model(["Solicitudes_solicitante", "Complemento_cxpModel"]);

        /*************************************************/
        /***********************SFTP**********************/
        //CLAVES DE ACCESO PARA EL SFTP
        $server = '189.198.132.118';
        $port = '2222';
        $user =  'Desarrollo01';
        $pwd = 'Dic2020@D01';

        //ESTABLECEMOS CONECCION
        $connection = ssh2_connect($server, $port);
        ssh2_auth_password($connection, $user, $pwd);

        $sftp = ssh2_sftp($connection);
        $sftp_fd = intval($sftp);

        //VARIABLES PARA NAVEGAR EN EL 
        $ruta = "ssh2.sftp://$sftp_fd/SYNCNOM_XML_PDF/XML-HONORARIOS/";
        $mas_directorios = TRUE;

        $directorios = [];
        $c = 0;

        do {

            $directorio = opendir($ruta);

            while (false != ($carpeta = readdir($directorio))) {
                if ($carpeta != "." && $carpeta != "..") {
                    if (is_dir($ruta . $carpeta)) {
                        $directorios[] = $ruta . $carpeta . "/";
                    } else {

                        $xml_remoto = fopen($ruta . $carpeta, 'r');
                        $xml_local = fopen("./XML_HONORARIOS/SIN_PROCESAR/$carpeta", 'w');

                        while ($chunk = fread($xml_remoto, 8192)) {
                            fwrite($xml_local, $chunk);
                        }

                        fclose($xml_remoto);
                        fclose($xml_local);
                    }
                }
            }

            closedir($directorio);

            if (count($directorios) > 0 && $c < count($directorios)) {
                $ruta = $directorios[$c];
                $mas_directorios = TRUE;
                $c++;
            } else {
                $mas_directorios = FALSE;
            }
        } while ($mas_directorios);

        /*************************************************/
        $xmls_sin_procesar = glob("./XML_HONORARIOS/SIN_PROCESAR/*.xml");
        if (count($xmls_sin_procesar) > 0) {

            $xml_procesados = [];
            $c = 0;
            $data = [];

            while ($c < count($xmls_sin_procesar)) {

                if (file_exists($xmls_sin_procesar[$c])) {
                    $datos_xml = $this->Complemento_cxpModel->leerxml($xmls_sin_procesar[$c], TRUE);

                    $datos_xml["uuid"] = strpos(strtoupper($datos_xml["uuid"]), 'UUID') ? $datos_xml["uuid"] . date("Ymdhis") : $datos_xml["uuid"];

                    //************GENERA EL NUEVO NOMBRE DEL DOCUMENTO XML SUBIDO AL SISTEMA*********/
                    $nuevo_nombre = date("my", strtotime($datos_xml['fecha'])) . "_";
                    $nuevo_nombre .= str_replace(array(",", ".", '"'), "", str_replace(array(" ", "/"), "_", limpiar_dato($datos_xml["nomrec"] . "_" . $datos_xml["rfcrecep"]))) . "_";
                    $nuevo_nombre .= $datos_xml['rfcemisor'] . "_" . $datos_xml['rfcrecep'] . "_";
                    $nuevo_nombre .= substr($datos_xml["uuid"], -5) . ".xml";

                    copy($xmls_sin_procesar[$c], "./XML_HONORARIOS/PROCESADOS/" . $nuevo_nombre);
                    copy($xmls_sin_procesar[$c], "./UPLOADS/XMLS/" . $nuevo_nombre);
                    unlink($xmls_sin_procesar[$c]);
                    $xml_procesados[] = $nuevo_nombre;
                    //**************AQUI ES DONDE SE TERMINA EL RENOMBRAMIENTO AL ARCHIVO*****************/
                    if ($datos_xml['uuid'] && $this->db->query("SELECT f.uuid FROM facturas f WHERE f.uuid = ?", [$datos_xml['uuid']])->num_rows() === 0) {
                        //LINEA ORIGINAL
                        $datos_xml['nombre_xml'] = $nuevo_nombre;
                        $datos_xml['tipo_factura'] = $datos_xml['MetodoPago'][0] != 'PPD' ? 3 : 1;

                        //TODO LO RELACIONADO CON LOS IMPUESTOS
                        $datos_xml['impuestot'] = (isset($datos_xml) && $datos_xml['impuestos_T'] ? json_encode($datos_xml['impuestos_T']) :  0.00);
                        $datos_xml['impuestor'] = (isset($datos_xml) && $datos_xml['impuestos_R'] ? json_encode($datos_xml['impuestos_R']) : 0.00);
                        /*****************************/

                        $descripcion = "";
                        foreach ($datos_xml["conceptos"] as $concepto) {
                            $descripcion .= $concepto["ClaveProdServ"] . ") " . $concepto['Descripcion'] . " - Importe: $" . $concepto['Importe'] . " \n";
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
                            "idsolicitud" => NULL,
                            "uuid" => $datos_xml['uuid'],
                            "nombre_archivo" => $datos_xml['nombre_xml'],
                            "tipo_factura" => $datos_xml['tipo_factura'],
                            "subtotal" => $datos_xml['SubTotal'],
                            "descuento" => $datos_xml['Descuento'],
                            "impuestot" => $datos_xml['impuestot'],
                            "impuestor" => $datos_xml['impuestor'],
                            "idusuario" => 2
                        );
                    }
                    $c++;
                }
            }
            echo "INSERTA DENTRO LA TABLA DE FACTURAS " . count($data);
            if (count($data) > 0) {
                $this->db->insert_batch("facturas", $data);
                $this->db->query("CALL cargar_xml_honorarios");

                foreach ($xml_procesados as $xml) {

                    $resFile = fopen("ssh2.sftp://$sftp_fd/SYNCNOM_XML_PDF/XML-HONORARIOS_COMPROBADOS/$xml", 'w');
                    $srcFile = fopen("./XML_HONORARIOS/PROCESADOS/$xml", 'r');

                    $writtenBytes = stream_copy_to_stream($srcFile, $resFile);
                    fclose($resFile);
                    fclose($srcFile);
                }

                if (count($directorios) > 0) {
                    foreach ($directorios as $directorio) {
                        $resultado = @opendir($directorio);
                        if ($resultado !== FALSE) {
                            closedir($resultado);
                            $ruta = str_replace("ssh2.sftp://$sftp_fd", "", $directorio);
                            ssh2_sftp_chmod($sftp, $ruta, 0755);
                            ssh2_sftp_rmdir($sftp, $ruta);
                        }
                    }
                }
            }
        }
    }

    function calcular_edad($fecha_nacimiento)
    {
        list($d, $m, $Y) = explode("/", $fecha_nacimiento);
        return (date("md") < $m . $d ? date("Y") - $Y - 1 : date("Y") - $Y);
    }

    public function CargaEdoCuentaBB()
    {
        $this->load->model("MBanbajio");
        ini_set('max_execution_time', 0);

        $fecha_inicio = date("Ymd");
        if ($this->db->query("SELECT * FROM bb_cosmov_fechas WHERE fcosmov = '" . date("Ymd") . "'")->num_rows() == 0)
            $this->db->insert("bb_cosmov_fechas", ["fcosmov" => $fecha_inicio]);

        $fechas = $this->db->query("SELECT fcosmov FROM bb_cosmov_fechas WHERE estatus = 1");

        if ($fechas->num_rows() > 0) {
            foreach ($fechas->result() as $fecha) {

                if ($this->MBanbajio->lectura_cosmov2(date('Ymd', strtotime('-1 day', strtotime(substr($fecha->fcosmov, 0, 4) . '-' . substr($fecha->fcosmov, 4, 2) . '-' . substr($fecha->fcosmov, 6, 2))))) && $this->MBanbajio->lectura_cosmov($fecha->fcosmov)) {
                    $this->db->update("bb_cosmov_fechas", ["estatus" => 0], "fcosmov = $fecha->fcosmov");
                }
            }
        }
    }

    /*
    public function test_lectura_cosmov(){
        $this->load->model("MBanbajio");

        //$this->MBanbajio->lectura_cosmov(true);
        $this->MBanbajio->lectura_cosmov2(true);
    }
    */

    public function solicitar_concentrado()
    {
        try {
            // FTP server details
            $ftp_server    = '38.49.137.106';
            $ftp_username = 'Administrator';
            $ftp_password = 'CDm2024@A';

            $fecha = date("Ymd");

            //print_r($fecha);
            //exit;

            // $connection = ssh2_connect('38.49.137.106', 21);
            // ssh2_auth_password($connection, 'Administrator', 'CDm2024@A');

            $ftp_conn  = ftp_connect($ftp_server, 21) or die("Couldn't connect to $ftp_server");
            $ftp_login = ftp_login($ftp_conn, $ftp_username, $ftp_password) or die("Couldn't login to $ftp_server");
            ftp_pasv($ftp_conn, true) or die("Unable switch to passive mode");

            //print_r($ftp_login);
            //exit;

            // $sftp = ssh2_sftp($connection);
            // $sftp_fd = intval($sftp);
            //$directorio = "ssh2.sftp://$sftp_fd/Envio";
            $documento = $fecha . "-001cmov.txt";

            $directorio = "Envio";

            //$cosmov_concentrado = fopen( $directorio."/".$documento, 'w') or die("No fue posible generar el documento.");
            //$txt = "00";
            //fwrite($cosmov_concentrado, $txt);
            //fclose($cosmov_concentrado);

            //$localFilePath = sys_get_temp_dir() . '/' . $documento;

            //print_r($localFilePath);
            //exit;

            $tmp = tmpfile();
            fwrite($tmp, '00');
            rewind($tmp);

            $remote_file = $directorio . "/" . $documento;

            //print_r($remote_file);
            //exit;

            if (ftp_fput($ftp_conn, $remote_file, $tmp, FTP_ASCII, 0)) {
                echo "File transfer successful";
            } else {
                echo "There was an error while uploading";
            }

            fclose($tmp);
            ftp_close($ftp_conn);
        } catch (\Exception $e) {
            $this->db->trans_rollback();
            echo json_encode(["status" => -1, "msj" => $e->getMessage()]);
        }
    }
}
