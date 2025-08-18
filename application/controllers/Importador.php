<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*Importador Pagos*/

class Importador extends CI_Controller {
    
    function index(){
         $this->load->view("v_importador");
    }
    
    function ipess()
	{
		//var_dump($_SERVER);
		echo $this->input->ip_address();
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
        if(isset($_FILES['file']) && $_FILES['file']['tmp_name']){

            /**CON ESTO SE CARGARAN LOS ARCHIVOS DE EXCEL**/
            $reader = PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($_FILES['file']['tmp_name']);

            if( $spreadsheet->getActiveSheet()->getHighestRow() >= 2 ){
                echo $spreadsheet->getActiveSheet()->getHighestRow();
                
                $this->db->trans_start();
                for($i = 2; $i <= $spreadsheet->getActiveSheet()->getHighestRow(); $i++){
                    if( !empty($spreadsheet->getActiveSheet()->getCell('A'.$i)) && !empty($spreadsheet->getActiveSheet()->getCell('B'.$i)) ){
        
                        $data = array(
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

                        echo $i."<br/>";

                        $this->db->insert('solpagos', $data);
                    }
                }
                $this->db->trans_complete();
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
    
}
