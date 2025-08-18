<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*Importador Pagos*/

class Importador_prestamos extends CI_Controller {
    
    function __construct(){
        parent::__construct();
        if( $this->session->userdata("inicio_sesion") && in_array( $this->session->userdata("inicio_sesion")['rol'], array( 'CP' )))
            $this->load->model( array('M_buscador') );   
        else
            redirect("Login", "refresh");
    }

    function index(){
         $this->load->view("v_importador");
    }

    function validateDate($date, $format = 'Y-m-d'){
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }

    public function importador(){

        $resultado = array(
            "resultado" => FALSE,
            "correctos" => array(),
            "error" => array()
        );

        if(isset($_FILES['documento_xls']) && $_FILES['documento_xls']['tmp_name']){

            /**CON ESTO SE CARGARAN LOS ARCHIVOS DE EXCEL**/
            $reader = PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($_FILES['documento_xls']['tmp_name']);

            if( $spreadsheet->getActiveSheet()->getHighestRow() >= 2 ){
                
                $this->db->trans_start();
                for($i = 2; $i <= $spreadsheet->getActiveSheet()->getHighestRow(); $i++){

                    if( $spreadsheet->getActiveSheet()->getCell('A'.$i)->getValue() != "" && 
                        $spreadsheet->getActiveSheet()->getCell('B'.$i)->getValue() != "" &&
                        ( $spreadsheet->getActiveSheet()->getCell('C'.$i)->getValue() != "" && $idempresa =  $this->M_buscador->get_empresa_abrev( $spreadsheet->getActiveSheet()->getCell('C'.$i) ) ) && 
                        ( $spreadsheet->getActiveSheet()->getCell('D'.$i)->getValue() != "" && $idproveedor = $this->M_buscador->get_proveedor_nombre( $spreadsheet->getActiveSheet()->getCell('D'.$i) ) )  &&
                        $spreadsheet->getActiveSheet()->getCell('E'.$i)->getValue() != "" && 
                        $spreadsheet->getActiveSheet()->getCell('F'.$i)->getValue() != "" &&
                        $spreadsheet->getActiveSheet()->getCell('G'.$i)->getValue() != "" && 
                        $spreadsheet->getActiveSheet()->getCell('H'.$i)->getValue() != "" &&
                        ( $spreadsheet->getActiveSheet()->getCell('I'.$i)->getValue() != "" && $this->validateDate( $spreadsheet->getActiveSheet()->getCell('I'.$i)->getValue() ) ) &&
                        ( (in_array( $spreadsheet->getActiveSheet()->getCell('K'.$i)->getValue(), array( "SEMANAL", "MENSUAL", "BIMESTRAL", "TRIMESTRAL", "CUATRIMESTRAL", "SEMESTRAL" ) ) && $this->validateDate( $spreadsheet->getActiveSheet()->getCell('J'.$i)->getValue() ) ) || ( $spreadsheet->getActiveSheet()->getCell('J'.$i)->getValue() == "" && $spreadsheet->getActiveSheet()->getCell('K'.$i)->getValue()  == "" ) ) ){
        
                        $data = array(
                            "proyecto" => $spreadsheet->getActiveSheet()->getCell('A'.$i),
                            "folio" => $spreadsheet->getActiveSheet()->getCell('B'.$i),
                            "idEmpresa" => $idempresa,
                            "idResponsable" => $this->session->userdata("inicio_sesion")['da'],
                            "idProveedor" => $idproveedor,
                            "idusuario" => $this->session->userdata("inicio_sesion")['id'],
                            "nomdepto" => "ADMINISTRACION",
                            "justificacion" => $spreadsheet->getActiveSheet()->getCell('E'.$i),
                            "cantidad" => $spreadsheet->getActiveSheet()->getCell('F'.$i),
                            "intereses" => $spreadsheet->getActiveSheet()->getCell('G'.$i),
                            "moneda" => "MXN",
                            "metoPago" => $spreadsheet->getActiveSheet()->getCell('H'.$i),
                            "fecelab" => $spreadsheet->getActiveSheet()->getCell('I'.$i),
                            "fecha_fin" => $spreadsheet->getActiveSheet()->getCell('J'.$i)->getValue() != "" && $spreadsheet->getActiveSheet()->getCell('K'.$i)->getValue() != "" ? $spreadsheet->getActiveSheet()->getCell('J'.$i) : NULL,
                            "tendrafac" => $spreadsheet->getActiveSheet()->getCell('L'.$i)->getValue() != "" ? 1 : NULL,
                            "idetapa" => 1,
                            "programado" => ( $spreadsheet->getActiveSheet()->getCell('J'.$i)->getValue() != "" && $spreadsheet->getActiveSheet()->getCell('K'.$i)->getValue() != "" ? array( "SEMANAL" => 7, "MENSUAL" => 1, "BIMESTRAL" => 2, "TRIMESTRAL" => 3, "CUATRIMESTRAL" => 4, "SEMESTRAL" => 6 )[$spreadsheet->getActiveSheet()->getCell('K'.$i).""] : NULL),
                            "fechaCreacion" => date("Y-m-d H:i:s")
                        );

                        $this->db->insert('solpagos', $data);
                        $resultado["correctos"][] = $i;
                    }else{
                        $resultado["error"][] =  $i." - ".$spreadsheet->getActiveSheet()->getCell('D'.$i);
                    }
                }
                $this->db->trans_complete();
            }

            $resultado["resultado"] = TRUE;

        }

        echo json_encode( $resultado );
    }
    
}
