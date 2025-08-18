<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Catalogos extends CI_Controller {

    public function __construct(){
        parent::__construct();
        //array( 'CX', 'CT', 'CE', 'CX', 'CC' )
        //if( !$this->session->userdata("inicio_sesion") && in_array($this->session->userdata("inicio_sesion")['rol'], array( 'CC' ) )  )
        if( !in_array( $this->session->userdata("inicio_sesion")['id'], [ 94, 93, 1853 ]  ) )
          redirect("Login", "refresh");
        else
          $this->load->model( [ 'Mproyectos', 'Provedores_model', 'Bancos_model' ] );
    }

    public function index(){
      $this->load->view("v_Catalogos");
    }

    /*PROYECTO*/
    public function Proyectos(){
      $this->load->view("head");
      $this->load->view("menu_navegador");
      $this->load->view("Catalogos/lista_proyectos");
      $this->load->view("footer");
    }

    public function TablaProyectos(){
      echo json_encode( [ 
        "data" => $this->Mproyectos->getProyectoAdministrar()->result_array(), 
        "empresas" => $this->Bancos_model->get_empresas()->result_array(),
        "opciones" => $this->permisos( $this->session->userdata("inicio_sesion") ),
        "rol" => $this->session->userdata("inicio_sesion")['rol'],
        "empresas" => $this->Bancos_model->get_empresas()->result_array()
      ]);
    }

    public function Proyecto_Empresa(){
      $respuesta = [ "resultado" => FALSE ];
      $data = json_decode(base64_decode(file_get_contents('php://input')));
      if( $data != FALSE ){
        $respuesta = [ 
          "resultado" => FALSE, 
          "proy_emp" => $this->Mproyectos->getProyectoEmpresa( $data->concepto )->result_array() 
        ];
      }

      echo (json_encode( $respuesta ));
    }
    
    public function empresas(){
      echo json_encode($this->Mproyectos->getEmpresas()->result_array());
    }

    public function insertProyEmp(){
      $respuesta = [ "resultado" => FALSE ];

      if( isset( $_POST ) && !empty( $_POST ) ){
        $respuesta = [
          "resultado" => $this->Mproyectos->insertProyEmp( $this->input->post("proyecto"), $this->input->post("concepto_proy"), $this->input->post("empresa_proy") )
        ];
      }
      echo (json_encode( $respuesta ));
    }

    public function cambiarEstatus(){

      $respuesta = [ "resultado" => FALSE ];
      $data = json_decode(base64_decode(file_get_contents('php://input')));

      if( $data != FALSE ){
        $proyecto = $data->nproyecto_neo;
        $datos = [
          "idestatus" => $data->nuevo_estatus
        ];
        $respuesta["resultado"] = $this->Mproyectos->uProyecto( $datos, $proyecto );
      }

      echo (json_encode( $respuesta ));
    }

    public function uExcel(){
      $respuesta = ["resultado" => FALSE];

      if(isset($_FILES['excel_importador']) && $_FILES['excel_importador']['tmp_name']){
          $data = array();
          /**CON ESTO SE CARGARAN LOS ARCHIVOS DE EXCEL**/
          $reader = PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
          $reader->setReadDataOnly(true);
          $spreadsheet = $reader->load($_FILES['excel_importador']['tmp_name']);

          if( $spreadsheet->getActiveSheet()->getHighestRow() >= 2 ){ 
              $this->db->trans_start();

              $this->db->truncate("temp_proy_prov");

              for($i = 2; $i <= $spreadsheet->getActiveSheet()->getHighestRow(); $i++){

                $dato = $spreadsheet->getActiveSheet()->getCell('A'.$i)->getValue() != '';
                $empresa = $spreadsheet->getActiveSheet()->getCell('B'.$i)->getValue() != '';
                $concepto_neo = $spreadsheet->getActiveSheet()->getCell('C'.$i)->getValue() != ''; 
                $moneda_sat = $spreadsheet->getActiveSheet()->getCell('D'.$i)->getValue() != '';
                $tasa = $spreadsheet->getActiveSheet()->getCell('E'.$i)->getValue() != '';

                if( $this->input->post("tipo_dato") == 0 && $concepto_neo ){
                  $data[] = array(
                    'd1' => $spreadsheet->getActiveSheet()->getCell('A'.$i)->getValue(),
                    'empresa'  => $spreadsheet->getActiveSheet()->getCell('B'.$i)->getValue(),
                    'concepto_neo'  => $spreadsheet->getActiveSheet()->getCell('C'.$i)->getValue(),
                    'tipo'  => 0,
                    'idcrea' => $this->session->userdata("inicio_sesion")['id'],
                    'fcreacion' => date("Y-m-d H:i:s")
                  );
                }

                if( $this->input->post("tipo_dato") == 1 && $dato && $empresa && $concepto_neo && $moneda_sat){
                  $data[] = array(
                    'd1' => $spreadsheet->getActiveSheet()->getCell('A'.$i)->getValue(),
                    'empresa'  => $spreadsheet->getActiveSheet()->getCell('B'.$i)->getValue(),
                    'concepto_neo'  => $spreadsheet->getActiveSheet()->getCell('C'.$i)->getValue(),
                    'moneda_sat' => $spreadsheet->getActiveSheet()->getCell('D'.$i)->getValue(),
                    'tasa' => $spreadsheet->getActiveSheet()->getCell('E'.$i)->getValue(),
                    'tipo'  => 1,
                    'idcrea' => $this->session->userdata("inicio_sesion")['id'],
                    'fcreacion' => date("Y-m-d H:i:s")
                  );
                }
                  
              }
              
              if( count($data) > 0 ){
                  $this->db->insert_batch('temp_proy_prov', $data);
                  if( $this->input->post("tipo_dato") == 0 )
                    $this->db->query("CALL importador_catalogo_proyecto()");
                  else
                    $this->db->query("CALL importador_catalogo_proveedor()");
                  $respuesta["mensaje"] = "¡Se ha actualizado exitosamente la información compartida!";
              }else{
                  $respuesta["mensaje"] = "¡Corrobore el documento excel!";
              }
              $this->db->trans_complete();
              $respuesta = ["resultado" => TRUE, "mensaje" => "¡Se realizo exitosamente la accion!" ];
          }else{
              $respuesta["mensaje"] = "¡El documento esta vacío!";
          }
      }

      echo json_encode( $respuesta );
    }
    
    /****/
    public function Proveedores(){
      $this->load->view("head");
      $this->load->view("menu_navegador");
      $this->load->view("Catalogos/lista_proveedores");
      $this->load->view("footer");
    }

    public function datosfiscales_prov(){
      $this->load->view("head");
      $this->load->view("menu_navegador");
      $this->load->view("Catalogos/datosfiscales_prov");
      $this->load->view("footer");
    }

    public function TablaProveedores(){
      echo json_encode( [ 
        "data" => $this->Provedores_model->getProveedoresConta()->result_array(), 
        "empresas" => $this->Bancos_model->get_empresas()->result_array(),
        "opciones" => $this->permisosProveedores( $this->session->userdata("inicio_sesion") ),
        "rol" => $this->session->userdata("inicio_sesion")['rol'],
        "empresas" => $this->Bancos_model->get_empresas()->result_array()
      ]);
    }

    public function Proveedor_Empresa(){
      $respuesta = [ "resultado" => FALSE ];
      $data = json_decode(base64_decode(file_get_contents('php://input')));
      if( $data != FALSE ){
        $respuesta = [ 
          "resultado" => FALSE, 
          "proy_emp" => $this->Provedores_model->getRFCEmpresa( $data->concepto )->result_array() 
        ];
      }

      echo (json_encode( $respuesta ));
    }

    public function insertRFCEmp(){
      $respuesta = [ "resultado" => FALSE ];

      if( isset( $_POST ) && !empty( $_POST ) ){
        $respuesta = $this->Provedores_model->insertRFCEmp( $this->input->post("rfc"), $this->input->post("concepto_proy"), $this->input->post("empresa_proy"), $this->input->post("moneda_sat"), $this->input->post("tasa") );
      }
      echo (json_encode( $respuesta ));
    }

    function permisos( $permiso ){
      $opciones = '<div class="btn-group">';
      if( in_array($this->session->userdata("inicio_sesion")['rol'], array( 'CT', 'CE', 'CX', 'CC' ) ) ){
        $opciones .= '<button type="button" class="btn btn-warning btn-sm editar_proyecto"><i class="fas fa-edit"></i></button>';
        $opciones .= '<button type="button" class="btn btn-default btn-sm inactivar_proyecto"><i class="fas fa-power-off"></i></button>';
        $opciones .= '<button type="button" class="btn btn-danger btn-sm borrar_proyecto" data-estatus="3"><i class="fas fa-trash-alt"></i></button>';
      }

      /*
      if( in_array($this->session->userdata("inicio_sesion")['rol'], array( 'CT', 'CE', 'CX', 'CC' ) ) ){
        $opciones .= '<button type="button" class="btn btn-default btn-sm relacion_empresa"><i class="fas fa-handshake"></i></button>';
      }
      */
      return $opciones.'</div>';
    }

    function permisosProveedores( $permiso ){
      $opciones = '<div class="btn-group">';
      
      if( in_array($this->session->userdata("inicio_sesion")['rol'], array( 'CT', 'CE', 'CX', 'CC','CP' ) ) ){
        $opciones .= '<button type="button" class="btn btn-default btn-sm relacion_empresa"><i class="fas fa-handshake"></i></button>';
      }
      
      return $opciones.'</div>';
    }

    function InsertNuevoRegistro(){

      $respuesta = [ "resultado" => FALSE ];

      if( isset( $_POST ) && !empty( $_POST ) ){
        $bdtabla = $this->input->post("bdtabla");
  
        unset($_POST["bdtabla"]);
  
        $data = [];
        $data["idestatus"]=$this->input->post("idestatus");
        while( current($_POST) ) {
            $data[ key( $_POST) ] = $_POST[ key($_POST) ];
            next($_POST);
        }

        $respuesta["resultado"] = $this->Mproyectos->insertProyecto( $bdtabla, $data );

        if( $bdtabla == "cat_proyecto_empresa" ){
          $respuesta["data"] = $this->Mproyectos->getProyectoAdministrar()->result_array();
        }
      }

      echo json_encode( $respuesta );
    }

    function updateRegistro(){

      $respuesta = [ "resultado" => FALSE ];

      if( isset( $_POST ) && !empty( $_POST ) ){

        $bdtabla = $this->input->post("bdtabla");
        $idproyecto = $this->input->post("nproyecto_neo");
  
        unset($_POST["bdtabla"]);
        unset($_POST["idproyecto"]);
  
        $data = [];
  
        while( current($_POST) ) {
            $data[ key( $_POST) ] = $_POST[ key($_POST) ];
            next($_POST);
        }
  
        $respuesta["resultado"] = $this->Mproyectos->updateProyecto( $bdtabla, $data, $idproyecto );
      }

      echo json_encode( $respuesta );
    }
}