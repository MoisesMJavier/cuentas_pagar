<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Generar_PDFile4 extends CI_Controller {

    function __construct(){
        parent::__construct();

        $this->load->model("Solicitudes_cxp");
    }

 
    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
 
 
  


    function documentos_autorizacion($emp, $cta, $filtro){
        $this->load->library('Pdf');
        $pdf = new TCPDF('P', 'mm', 'LETTER', 'UTF-8', false); 

        $pdf->SetCreator(PDF_CREATOR);
        // $pdf->SetAuthor('Sistemas Victor Manuel Sanchez Ramirez');
        $pdf->SetTitle('LISTA DE PAGOS');
        $pdf->SetSubject('Pagos autorizados por Dirección General');
        $pdf->SetKeywords('LISTA, CIUDAD MADERAS, PAGOS, AUTORIZA, DG');
        // se pueden modificar en el archivo tcpdf_config.php de libraries/config
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);    
        // se pueden modificar en el archivo tcpdf_config.php de libraries/config
        $pdf->SetAutoPageBreak(TRUE, 0);
        //relación utilizada para ajustar la conversión de los píxeles
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->setPrintHeader(false);
        // $pdf->setPrintFooter();
        $pdf->setFontSubsetting(true);
        $pdf->SetFont('Helvetica', '', 9, '', true);
        $pdf->SetMargins(20, 20, 15, true);
        $pdf->AddPage('L', 'LETTER LANDSCAPE');
 
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
 
  $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
 

        $facturas = $this->Solicitudes_cxp->soli_F($emp, $cta, $filtro);
 
        $html_facturas = '';
         $totpagar = NULL;

        if( $facturas->num_rows() > 0 ){

            foreach( $facturas->result()  as $row1){

                $totpagar=$totpagar+$row1->cantidad;
            }

           $html = '<table>
            <tr>
                <td rowspan="2">
                    <img width="160px" src="'.base_url("img/logo_inicio.jpg").'"> 
                </td>
                <td colspan="2">
                    <h2><br>Pagos autorizados por Dirección General</h2>

                </td>
                 <td><span><br><B>FACTURAJE</B></span><br>
                    
                    <span><br>Total de registros: <b style="font-size:10px">'.$facturas->num_rows().'</b></span>
                </td>

                <td> 
                   <span><br>Fecha: <b style="font-size:10px">'.date("d-m-Y H:i:s").'</b></span><br>
                    <span style="align:center;"><br>Cantidad Total: '.'<b >$'.number_format($totpagar,2).'</b></span>
                    
                </td>
            </tr>
        </table>';

           $pdf->writeHTMLCell(0, 0, $x = '', $y = '10', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);


            foreach( $facturas->result()  as $row ){

                 // $query =  $this->db->query('SELECT * from usuarios');
                 // var_dump($query);

                $abonos = $this->Solicitudes_cxp->abonos($row->idsolicitud);

                 foreach($abonos->result()  as $row22 ){

                    if ($row22->abono == 0){

                        $obten = 0;

                    }
                    else
                    {
                        $obten =   $row22->abono;
                       
                    }
                  
                 }
 

                $html_facturas .= '
                <tr>
                    <td>
                        '.$row->idsolicitud.' 
                     </td>
                    <td>
                        '.$row->nombres." ".$row->apellidos.'
                    </td>
                    <td>
                         '.$row->proyecto.'
                    </td>
                    <td>
                        '.$row->abrev.'
                    </td>
                    <td>
                        '.$row->nombre.'
                    </td>
                    <td>
                        '.$row->fecfac.'
                    </td>
                    <td>
                        '.$row->foliofac.'
                    </td>
                    <td>
                        '.$row->justificacion.'
                    </td>
                    <td>
                        '.'$'.number_format($row->total,2).'
                    </td>
                    <td>
                        '.$row->metoPago.'
                    </td>
                    <td>
                        '.$row->observaciones.'
                    </td>
                    <td>
                        '.'$'.number_format($obten,2).'
                    </td>
                    <td>
                        '.'$'.number_format($row->cantidad,2).'
                    </td>
                    
                    <td>
                    </td>
                     
                </tr>';
                  
            }
        }

        
        $html = '<table align="center" border=".1">
            <tr style="background-color: #000000; color: #FFF;">
                
                <td width="3%">
                    No. 
                </td>
                
                <td width="8%">
                    RESPONSABLE
                </td>
               
                <td width="5%">
                    PROYECTO
                </td>
                
                <td width="7%">
                    EMPRESA
                </td>
                
                <td width="8%">
                    PROVEEDOR
                </td>

                <td width="6%">
                    FECHA FACT.
                </td>

                <td width="5%">
                    FOLIO FACT.
                </td>
                 
                <td width="10%">
                    DESCRIPCIÓN Ó CONCEPTO
                </td>

                <td width="5%">
                    TOTAL $
                </td>

                <td width="7%">
                    METODO PAGO
                </td>

                <td width="10%">
                    OBSERVACIONES
                </td>

                <td width="8%">
                    ABONOS
                </td>

                <td width="8%">
                    SALDO
                </td>

                <td width="10%">
                    FIRMA AUTORIZADO
                </td>

            </tr>
            <tbody>
            '.$html_facturas.'</tbody>
        </table>';


        $pdf->SetFont('Helvetica', '', 5, '', true);
         $pdf->writeHTMLCell(0, 0, $x = '', $y = '30', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
       
        $this->load->model("ArchivoBan");
       $this->ArchivoBan->updatePagosTxtFact($emp, $cta, $filtro);

        $pdf->Output(utf8_decode("DOCUMENTO_AUT_FACTURAJE.pdf"), 'D');
 


          
     
}

 


}