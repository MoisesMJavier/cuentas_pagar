<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Clientes extends CI_Controller {

    public function __construct(){
        parent::__construct();
        if( !$this->session->userdata("inicio_sesion") )
            redirect("Login", "refresh");
        else
            $this->load->model('Model_Clientes');
    }

    public function index(){
        if(in_array($this->session->userdata('inicio_sesion')["rol"],array("Asesor","Asistente gerente","Gerente"))){
            $this->load->view("v_Clientes");
        }else{
            $this->load->view("v_Clientes_Tablas");
        }
    }

    public function Registro(){
        $resultado = array("resultado" => false, "msj" => 'Antes');
        $request = json_decode(file_get_contents("php://input"));

        if( (isset($request) && !empty($request)) ){

            $this->db->trans_begin();
            $data = array(
                "id_asesor" => $this->session->userdata("inicio_sesion")['id'],
                "id_sede" => $this->session->userdata("inicio_sesion")['sede'],
                "nombre" => limpiar_dato($request->nombre),
                "apellido_paterno" => (isset($request->apellidop ) || is_null($request->apellidop ) ? '' : limpiar_dato($request->apellidop) ), // limpiar_dato($request->apellidop),
                "apellido_materno" => (isset($request->apellidom ) || is_null($request->apellidom) ? '' : limpiar_dato($request->apellidom)), // limpiar_dato($request->apellidom),
                "personalidad_juridica" =>$request->juridica->value,
                "rfc" => (isset($request->rfc) ? $request->rfc : NULL), // $request->rfc,
                "curp" => (isset($request->curp) ? $request->curp : NULL), // $request->curp,
                "correo" =>$request->correo,
                "telefono" => $request->telefono,
                "telefono_2" => (isset($request->telefono2) ? $request->telefono2 : NULL), // $request->telefono2,
                "tipo" => 0,
                "nacionalidad" => $request->nacionalidad->value,
                "observaciones" => (isset($request->observaciones) ? $request->observaciones : NULL), // $request->observaciones,
                "lugar_prospeccion" => $request->lugar->value,
                "territorio_venta" => (isset($request->territorio) ? $request->territorio->value : NULL),
                "zona_venta" => (isset($request->zona) ? $request->zona->value : NULL), // $request->zona->value,
                "facturable" => 1, // $request->facturable,
                "medio_publicitario" => $request->medio->value,
                "estatus" => 1,
                "creado_por" => $this->session->userdata("inicio_sesion")['id'],
                "id_gerente" => $this->session->userdata('inicio_sesion')["rol"] = "Gerente" ? $this->session->userdata("inicio_sesion")['id'] : $this->session->userdata("inicio_sesion")['lider'],
            );

            $usuarios = $this->Model_Clientes->ExisteUsuario($data)->result();
            if(count($usuarios) > 0  && !$request->bandera){
                $resultado = array("resultado" => FALSE,"usuarios" => $usuarios);
            }else{
                $id = $this->Model_Clientes->RegistrarNuevoCliente($data);
                $md = isset($request->observaciones) ? guardar_observacion($this->session->userdata("inicio_sesion")['id'],$id,$request->observaciones) : NULL;

                if ( $this->db->trans_status() === FALSE){
                    $this->db->trans_rollback();
                    $resultado = array("resultado" => FALSE);
                }else{
                    $this->db->trans_commit();
                    $resultado = array("resultado" => TRUE ,"clientes" => $this->Model_Clientes->TablaClientes(1)->result(),"prospectos" => $this->Model_Clientes->TablaClientes(0)->result());
                }
            }
        }
        echo json_encode( $resultado );
    }

    public function Editar(){
      $resultado = array("resultado" => false, "msj" => 'Antes');
      $request = json_decode(file_get_contents("php://input"));

      if( (isset($request) && !empty($request)) ){

          $this->db->trans_begin();
          $data = array(
              //"id_asesor" => $this->session->userdata("inicio_sesion")['id'],
              "id_sede" => $this->session->userdata("inicio_sesion")['sede'],
              //"clave" => $request->clave,
              "nombre" => limpiar_dato($request->nombre),
              "apellido_paterno" => (is_null($request->apellidop) ? '' : limpiar_dato($request->apellidop) ), // limpiar_dato($request->apellidop),
              "apellido_materno" =>  (isset($request->apellidom ) || is_null($request->apellidom) ? '' : limpiar_dato($request->apellidom)), // limpiar_dato($request->apellidom),
              "personalidad_juridica" => $request->juridica->value,
              "rfc" => (isset($request->rfc) ? $request->rfc : NULL), // $request->rfc,
              "curp" => (isset($request->curp) ? $request->curp : NULL), // $request->curp,
              "correo" =>$request->correo,
              "telefono" => $request->telefono,
              "telefono_2" => (isset($request->telefono2) ? $request->telefono2 : NULL), // $request->telefono2,
              "tipo" => $request->tipo->value,
              "observaciones" => (isset($request->observaciones) ? $request->observaciones : NULL), // $request->observaciones,
              "lugar_prospeccion" => $request->lugar->value,
              "territorio_venta" => (isset($request->territorio) ? $request->territorio->value : NULL),
              "zona_venta" => (isset($request->zona) ? $request->zona->value : NULL), // $request->zona->value,
              "facturable" => 1, // $request->facturable,
              "medio_publicitario" => $request->medio->value,
              "estatus" => 1,
              "nacionalidad" => $request->nacionalidad->value,
              "modificado_por" => $this->session->userdata("inicio_sesion")['id'],
          );
//print_r($data);
          $id = $this->Model_Clientes->EditarCliente($data,$request->id);

          if ( $this->db->trans_status() === FALSE){
              $this->db->trans_rollback();
              $resultado = array("resultado" => FALSE);
          }else{
              $this->db->trans_commit();
              $resultado = array("resultado" => TRUE ,"clientes" => $this->Model_Clientes->TablaClientes(1)->result(),"prospectos" => $this->Model_Clientes->TablaClientes(0)->result());
          }
      }
      echo json_encode( $resultado );
  }

    public function InformacionCliente(){
        $request = json_decode(file_get_contents("php://input"));

        if((isset($request) && !empty($request)) ){
            $usuario =$this->Model_Clientes->InformacionCliente($request->id_cliente)->row();
            $usuario->id_cliente = str_pad($request->id_cliente, 11, "0", STR_PAD_LEFT);
          echo json_encode($usuario);
        }
    }

    public function Eliminar(){
        $request = json_decode(file_get_contents("php://input"));
        $resultado = array("resultado" => false, "msj" => 'Antes');

        if((isset($request) && !empty($request)) ){
            $usuario = $this->Model_Clientes->Eliminar($request->id_cliente);
            $resultado = array("resultado" => $usuario,"clientes" => $this->Model_Clientes->TablaClientes(1)->result(),"prospectos" => $this->Model_Clientes->TablaClientes(0)->result());
            echo json_encode($resultado);
        }
    }
    
    public function TablaClientes(){
      echo json_encode( array( "clientes" => $this->Model_Clientes->TablaClientes(1)->result(),"prospectos" => $this->Model_Clientes->TablaClientes(0)->result()));
    }


    public function Comentar(){
        if(isset($_POST) && !empty($_POST)){
            guardar_observacion($this->session->userdata("inicio_sesion")['id'],$this->input->post("id"),$this->input->post("observacion"));
            echo json_encode( array( "observacion" => $this->input->post("observacion"), "creador" => $this->session->userdata("inicio_sesion")['apellidos'].' '.$this->session->userdata("inicio_sesion")['nombres'], "fecha_creacion" => date( "Y-m-d h:i:s" ) ) );
        }
    }

    public function ModalInformacion(){
        $request = json_decode(file_get_contents("php://input"));
        if(isset($request) && !empty($request)){
            $data['informacion']= $this->Model_Clientes->VerInformacionCliente($request->id_cliente)->row();
            $data['comentarios']= $this->Model_Clientes->VerComentarios($request->id_cliente)->result();
            //print_r($data);
            $this->load->view( "Informacion_Cliente",$data);
        }
    }

    public function Observaciones(){
        $resultado = array();
        if( $this->input->post("id_cliente") ){
            //echo $this->input->post("id_cliente");
            $resultado = $this->Model_Clientes->VerComentarios($this->input->post("id_cliente"))->result();
        }
        echo json_encode( $resultado);
    }
}