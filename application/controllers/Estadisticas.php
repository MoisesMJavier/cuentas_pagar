<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Estadisticas extends CI_Controller {

    public function __construct(){
        parent::__construct();
        if( !$this->session->userdata("inicio_sesion") )
          redirect("Login", "refresh");
        else
          $this->load->model('Model_Estadisticas');
    }

    public function index(){
        if($this->session->userdata("inicio_sesion")["id_rol"] == '7' || $this->session->userdata("inicio_sesion")["id_rol"] == '8'){
            $this->load->view("vista_estadisticas");
        }
        elseif ($this->session->userdata("inicio_sesion")["id_rol"] == '3' || $this->session->userdata("inicio_sesion")["id_rol"] == '6') {
          $this->load->view("vista_estadisticas_gerente");
        }
        else{
            $this->load->view("vista_estadisticas_especial");
        }  

        
    }


    public function prueba_nombre(){
        $this->load->model("Model_Estadisticas");
        //$this->load->view($vista_navegar, array("informacion_trabajador" => $informacion_trabajador->row(), "antiguedad" => $antiguedad, "dias_tomados" => ($dias_tomados->num_rows() > 0 ? $dias_tomados->row()->dias_tomados : 0), "vacaciones_permitidas" => $vacaciones_permitidas, "periodo" => intervalo_fechas( $informacion_trabajador->row()->fecha_ingreso, $antiguedad ), "foto_perfil" => foto_perfil( $informacion_trabajador->row()->idpersona ), "solicitar_convenio" => $this->Contrato->get_convenio_registro($this->uri->segment(3))->num_rows(), "numero_contratos" => $numero_contratos ));
        $nombrecompleto = $this->Model_Estadisticas->get_name();
        $json_entregar = array();
          if($nombrecompleto->num_rows() > 0){
            foreach($nombrecompleto->result() as $row_nombrecompleto){
                $json_entregar[] = array("nombre" => $row_nombrecompleto->nombre, "apellido_paterno" => $row_nombrecompleto->apellido_paterno);
            }
          }
        echo json_encode($json_entregar);
      }
  
      public function get_ventas(){
        $this->load->model("Model_Estadisticas");
        $año = '2018';
        $ventas = $this->Model_Estadisticas->get_ventas($año);
        $json_entregar = array();
          if($ventas->num_rows() > 0){
            foreach($ventas->result() as $row_ventas){
                $json_entregar[] = array("ventas" => $row_ventas->ventas, "mes" => $row_ventas->mes);
            }
          }
        echo json_encode($json_entregar);
      }
  
      public function get_ventas_compar(){
        $this->load->model("Model_Estadisticas");
        $ventas = $this->Model_Estadisticas->get_ventas_compar();
        $json_entregar = array();
          if($ventas->num_rows() > 0){
            foreach($ventas->result() as $row_ventas){
                $json_entregar[] = array("ventas1" => $row_ventas->ventas1, "ventas2" => $row_ventas->ventas2, "mes" => $row_ventas->mes);
            }
          }
  
        echo json_encode($json_entregar);
      }

      public function get_managers(){
        $user=$this->session->userdata("inicio_sesion")["id"];
        $this->load->model("Model_Estadisticas");

        if($this->session->userdata("inicio_sesion")["id_rol"] == '1' || $this->session->userdata("inicio_sesion")["id_rol"] == '4'){
          $managers = $this->Model_Estadisticas->get_managers();
        }
        elseif ($this->session->userdata("inicio_sesion")["id_rol"] == '2') {
          $managers = $this->Model_Estadisticas->get_managers_by_sub($user);
        }
        elseif ($this->session->userdata("inicio_sesion")["id_rol"] == '5') {
          $managers = $this->Model_Estadisticas->get_managers_by_asis($user);
        }
        else{
          $managers = $this->Model_Estadisticas->get_managers();
        }  


        
        $json_entregar = array();
          if($managers->num_rows() > 0){
            foreach($managers->result() as $row_managers){
                $json_entregar[] = array("id_managers" => $row_managers->id_usuario, "nombre_managers" => $row_managers->nombrecompleto);
            }
          }
  
        echo json_encode($json_entregar);
      }

      public function get_asesores_bygerente(){
        $user = $this->session->userdata("inicio_sesion")["id"];
        $this->load->model("Model_Estadisticas");
        if($this->session->userdata("inicio_sesion")["id_rol"] == '3'){
          $asesores = $this->Model_Estadisticas->get_asesores($user)->result();
        }
        else{
          $asesores = $this->Model_Estadisticas->get_asesores_asis($user)->result();
        }  
        echo json_encode($asesores);
      }

      public function get_asesores(){
      $user = $this->session->userdata("inicio_sesion")["id"];
      $this->load->model("Model_Estadisticas");
      $asesores = $this->Model_Estadisticas->get_asesores($user)->result();
      /*$json_entregar = array();
        if($asesores->num_rows() > 0){
          foreach($asesores->result() as $row_asesores){
              $json_entregar[] = array("id_asesores" => $row_asesores->id_usuario, "nombre_asesores" => $row_asesores->nombrecompleto);
          }
        }*/

      echo json_encode($asesores);
    }

    public function get_clientes(){
      $this->load->model("Model_Estadisticas");
      $user = $this->session->userdata("inicio_sesion")["id"];
      $tipo = 1;
      $clientes = $this->Model_Estadisticas->get_clientes($user, $tipo)->result();
      /*$json_entregar = array();
        if($clientes->num_rows() > 0){
          foreach($clientes->result() as $row_clientes){
              $json_entregar[] = array("clientes" => $row_clientes->clientes, "mes" => $row_clientes->mes);
          }
        }*/
      echo json_encode($clientes);
    }

    public function get_chart(){
      $this->load->model("Model_Estadisticas");
      $request = json_decode(file_get_contents("php://input"));
      $tipo = $request->tipo;
      $fecha_ini = date("Y/m/d",strtotime($request->fecha_ini));
      $fecha_fin = date("Y/m/d",strtotime($request->fecha_fin));
      $user = $this->session->userdata("inicio_sesion")["id"];

      $clientes = $this->Model_Estadisticas->get_chart($user, $tipo, $fecha_ini, $fecha_fin)->result();
      echo json_encode($clientes);
      
    }

    public function get_total_gerente(){
      $this->load->model("Model_Estadisticas");
      $tipo = 1;
      $user = $this->session->userdata("inicio_sesion")["id"];

      
      if($this->session->userdata("inicio_sesion")["id_rol"] == '3'){
        $clientes = $this->Model_Estadisticas->get_total_gerente($user, $tipo)->result();
      }
      else{
        $clientes = $this->Model_Estadisticas->get_total_gerente_asis($user, $tipo)->result();
      } 
      echo json_encode($clientes);
      
    }

    public function get_chart_gerente(){
      $this->load->model("Model_Estadisticas");
      $request = json_decode(file_get_contents("php://input"));
      $tipo = $request->tipo;
      $fecha_ini = date("Y/m/d",strtotime($request->fecha_ini));
      $fecha_fin = date("Y/m/d",strtotime($request->fecha_fin));
      $user = $request->asesor;

      $clientes = $this->Model_Estadisticas->get_chart($user, $tipo, $fecha_ini, $fecha_fin)->result();
      echo json_encode($clientes);
      
    }

    public function get_total_director(){
      $this->load->model("Model_Estadisticas");
      $user = $this->session->userdata("inicio_sesion")["id"]; 

      if($this->session->userdata("inicio_sesion")["id_rol"] == '1' || $this->session->userdata("inicio_sesion")["id_rol"] == '4'){
        $clientes = $this->Model_Estadisticas->get_total_director()->result();
      }
      elseif($this->session->userdata("inicio_sesion")["id_rol"] == '2'){
        $clientes = $this->Model_Estadisticas->get_total_subdir($user)->result();
      } 
      else{
        $clientes = $this->Model_Estadisticas->get_total_subdir_byasis($user)->result();
      }  

      echo json_encode($clientes);
      
    }

    public function get_asesores_gerentes(){
      $this->load->model("Model_Estadisticas");
      $request = json_decode(file_get_contents("php://input"));
      $user = $request->gerente;
      $asesores = $this->Model_Estadisticas->get_asesores($user)->result();
      /*$json_entregar = array();
        if($asesores->num_rows() > 0){
          foreach($asesores->result() as $row_asesores){
              $json_entregar[] = array("id_asesores" => $row_asesores->id_usuario, "nombre_asesores" => $row_asesores->nombrecompleto);
          }
        }*/

      echo json_encode($asesores);
    }

    
    public function generar_reporte(){

      $this->load->model("Model_Estadisticas");
      $request = json_decode(file_get_contents("php://input"));
      $tipo = $request->tipo;
      $fecha_ini = date("Y/m/d",strtotime($request->fecha_ini));
      $fecha_fin = date("Y/m/d",strtotime($request->fecha_fin));
      $user = $this->session->userdata("inicio_sesion")["id"]; 

          $reader = new PhpOffice\PhpSpreadsheet\Spreadsheet();
          $encabezados = [
              'font' => [
                  'color' => [
                      'argb' => 'FFFFFFFF',
                  ]
              ],
              'alignment' => [
                  'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
              ],
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
              ],
              'fill' => [
                  'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                  'startColor' => [
                      'argb' => '00000000',
                  ],
                  'endColor' => [
                      'argb' => '00000000',
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

                  $reporte = $this->db->query("SELECT MONTHNAME(fecha_creacion) AS mes, COUNT(id_cliente) AS clientes FROM sisgphco_crm.clientes
                  WHERE (tipo = '$tipo' AND id_asesor = '$user') AND fecha_creacion BETWEEN '$fecha_ini' AND '$fecha_fin' GROUP BY mes 
                  ORDER BY MONTH(fecha_creacion)");
                  
                  //Inicio de Reporte 
                  $i = 1;
                  $reader->getActiveSheet()->setCellValue('A'.$i, 'MES');
                  $reader->getActiveSheet()->setCellValue('B'.$i, '# Clientes');
      
                  $reader->getActiveSheet()->getStyle("A$i:B$i")->applyFromArray($encabezados);
                  $i+=1;

                  if( $reporte->num_rows() > 0  ){
      
                      foreach( $reporte->result() as $row ){
      
                          $reader->getActiveSheet()->setCellValue('A'.$i, $row->mes);
                          $reader->getActiveSheet()->setCellValue('B'.$i, $row->clientes);


                          $reader->getActiveSheet()->getStyle("A$i:B$i")->applyFromArray($informacion);
                          $i+=1;

                      }
                  }

              $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($reader, "Xlsx");

              $temp_file = tempnam(sys_get_temp_dir(), 'PHPExcel');
              $writer->save($temp_file);

              header("Content-Disposition: attachment; filename=REPORTE.xlsx");
              readfile($temp_file); // or echo file_get_contents($temp_file);
              unlink($temp_file);


      
    }

    public function get_repo_asesor(){
      $this->load->model("Model_Estadisticas");
      $request = json_decode(file_get_contents("php://input"));
      $fecha_ini = date("Y/m/d",strtotime($request->fecha_ini));
      $fecha_fin = date("Y/m/d",strtotime($request->fecha_fin));
      $tipo = $request->tipo;
      $user = $this->session->userdata("inicio_sesion")["id"];

      $clientes = $this->Model_Estadisticas->get_reporte_asesor($user, $fecha_ini, $fecha_fin, $tipo)->result();
      echo json_encode($clientes);
      
    }

    public function get_repo_gerente(){
      $this->load->model("Model_Estadisticas");
      $user = $this->session->userdata("inicio_sesion")["id"];

      if($this->session->userdata("inicio_sesion")["id_rol"] == '3'){
        $clientes = $this->Model_Estadisticas->get_reporte_gerente($user)->result();
      }
      else{
        $clientes = $this->Model_Estadisticas->get_reporte_asisgerente($user)->result();
      }  

      echo json_encode($clientes);
      
    }

    public function get_repo_gerente1(){
      $this->load->model("Model_Estadisticas");
      $request = json_decode(file_get_contents("php://input"));
      $tipo = $request->tipo;
      $asesor = $request->asesor;
      $fecha_ini = date("Y/m/d",strtotime($request->fecha_ini));
      $fecha_fin = date("Y/m/d",strtotime($request->fecha_fin));
      $user = $this->session->userdata("inicio_sesion")["id"];

      if($this->session->userdata("inicio_sesion")["id_rol"] == '3'){
        $clientes = $this->Model_Estadisticas->get_reporte_gerente1($user, $fecha_ini, $fecha_fin, $tipo, $asesor)->result();
      }
      else{
        $clientes = $this->Model_Estadisticas->get_reporte_asisgerente1($user, $fecha_ini, $fecha_fin, $tipo, $asesor)->result();
      }  

      echo json_encode($clientes);
      
    }

    public function get_repo_dir(){
      $this->load->model("Model_Estadisticas");

      $user = $this->session->userdata("inicio_sesion")["id"];

      if($this->session->userdata("inicio_sesion")["id_rol"] == '1' || $this->session->userdata("inicio_sesion")["id_rol"] == '4'){
        $clientes = $this->Model_Estadisticas->get_reporte_dir()->result();
      }
      elseif($this->session->userdata("inicio_sesion")["id_rol"] == '2'){
        $clientes = $this->Model_Estadisticas->get_reporte_subdir($user)->result();
      }
      else{
        $clientes = $this->Model_Estadisticas->get_reporte_subdir_byasis($user)->result();
      }
        
  
      echo json_encode($clientes);
      
    }

    public function get_repo_dir1(){
      $this->load->model("Model_Estadisticas");
      $request = json_decode(file_get_contents("php://input"));
      $fecha_ini = date("Y/m/d",strtotime($request->fecha_ini));
      $fecha_fin = date("Y/m/d",strtotime($request->fecha_fin));
      $tipo = $request->tipo;
      $gerente = $request->gerente;
      $asesor = $request->asesor;
      $user = $this->session->userdata("inicio_sesion")["id"];

      if($this->session->userdata("inicio_sesion")["id_rol"] == '1' || $this->session->userdata("inicio_sesion")["id_rol"] == '4'){
        $clientes = $this->Model_Estadisticas->get_reporte_dir1($fecha_ini, $fecha_fin, $tipo, $asesor)->result();
      }
      elseif($this->session->userdata("inicio_sesion")["id_rol"] == '2'){
        $clientes = $this->Model_Estadisticas->get_reporte_subdir1($user, $fecha_ini, $fecha_fin, $tipo, $asesor)->result();
      }
      else{
        $clientes = $this->Model_Estadisticas->get_reporte_subdir_byasis1($user, $fecha_ini, $fecha_fin, $tipo, $asesor)->result();
      }
        
  
      echo json_encode($clientes);
      
    }

    

      
}