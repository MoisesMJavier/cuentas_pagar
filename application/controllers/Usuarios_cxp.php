<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuarios_cxp extends CI_Controller {

    function __construct(){
        parent::__construct();
        if( $this->session->userdata("inicio_sesion") && ( in_array( $this->session->userdata("inicio_sesion")['rol'], array( 'DA', 'SO', 'CP' ) ) || ( $this->session->userdata("inicio_sesion")['rol'] == 'DA' && $this->session->userdata("inicio_sesion")['depto'] == 'SISTEMAS' ) ) )
            $this->load->model('Usuarios_model');
        else
            redirect("Login", "refresh");
    }

    public function index(){
        $this->load->view("vista_users");
    }

    public function validar_correo_user(){
        if( $this->input->post("correo") ){
            $correo = $this->input->post("correo");
            $consulta=$this->db->query("SELECT * FROM usuarios WHERE correo='$correo'"); 
            echo json_encode( array( $consulta->num_rows() ) );
        }
    }
    
    public function validar_nickname_user(){
        if( $this->input->post("nickname") ){
            $nickname = $this->input->post("nickname");
            $consulta=$this->db->query("SELECT * FROM usuarios WHERE nickname='$nickname'"); 
            echo json_encode(array($consulta->num_rows()));
        }
    }

    public function tabla_autorizaciones(){
        $query =  $this->Usuarios_model->get_users( $this->session->userdata("inicio_sesion")['rol'] )->result_array();
        $this->load->model("Lista_dinamicas");

        if( count($query) > 0 ){
            for( $i = 0; $i < count( $query ); $i++ ){
                $query[$i]['pass'] = desencriptar( $query[$i]['pass'] );
            }
        }

        $resultado["data"] = $query;
        $resultado["rol"] = $this->session->userdata("inicio_sesion")['rol'];
        $resultado["idusuario"] = $this->session->userdata("inicio_sesion")['id'];
        $resultado["directores"] = $this->Lista_dinamicas->get_Directoresarea()->result_array();

        echo json_encode( $resultado );
    }

    public function verbancos(){
        $this->load->model('Usuarios_model');

        $datos = $this->Usuarios_model->getbancos(NULL);
        $respuesta['data'] = array();

        if($datos->num_rows()>0){
            foreach($datos->result() as $row){
                $respuesta['data'][] = array( "idbanco"=>$row->idbanco, "nombre"=>$row->nombre );
            }
        }

        echo json_encode($respuesta);
    }



    public function registrar_nuevo_usuario(){
        
        $respuesta = array( false );
        
        if( isset($_POST) && !empty($_POST) ){
            
            $this->db->trans_begin(); 

            $depto = is_array( $this->input->post("depto") ) ? implode(",",$this->input->post("depto")) : $this->input->post("depto");

            $data = array(  
                "nombres" => limpiar_dato($this->input->post("nombre")),
                "apellidos" => limpiar_dato($this->input->post("apellidos")),
                "correo" => strtolower($this->input->post("correo")),
                "depto" =>  $depto,
                "estatus" => 1,
                "rol" => $this->input->post("rol"),
                "nickname" => $this->input->post("usuario"),
                "pass" => encriptar($this->input->post("password")),
                "feccrea"=>date('Y-m-d H:i:s'),
                "da" =>  $this->input->post("director_area"),
                "creadopor" => $this->session->userdata("inicio_sesion")["id"]
            );
    
            $respuesta = array( $this->Usuarios_model->insertar_nuevo( $data ) );
                
            if ( $respuesta[0] === FALSE || $this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                $respuesta = array( FALSE );
            }else{
                $this->db->trans_commit();
                $respuesta = array( TRUE );
            }          
        }

        echo json_encode($respuesta);        
    }



    public function ver_datosprovs(){
        $this->load->model("Usuarios_model");
        $dat =  $this->Usuarios_model->get_users()->result_array();
        echo json_encode( array( "data" => $dat ));
    }


    public function ver_datosprovs2($idproveedor){
        $this->load->model("Usuarios_model");
        $dat =  $this->Usuarios_model->get_provs2($idproveedor)->result_array();
        echo json_encode($dat);
    }
    
    
    function updateUsuarios(){
        $respuesta = array( false );
        if( isset($_POST) && !empty( $_POST ) ){

        $this->db->trans_begin(); 

        $depto = is_array( $this->input->post("depto") ) ? implode(",",$this->input->post("depto")) : $this->input->post("depto");
        
        $data = array(
            "nombres"=>limpiar_dato($this->input->post("nombre")),
            "apellidos" => limpiar_dato($this->input->post("apellidos")),
            "nickname" => $this->input->post("usuario"),
            "correo" => strtolower($this->input->post("correo")),
            "depto" => $depto,
            "estatus" => $this->input->post("estatus"),
            "rol" => $this->input->post("rol"),
            "da" =>  $this->input->post("director_area"),
            "pass" => encriptar($this->input->post("password")),
            "idmodifica" => $this->session->userdata("inicio_sesion")["id"],
            "fecha_mod" => date('Y-m-d H:i:a'),
        );
       
        $respuesta = $this->Usuarios_model->edita_usuario( $data, $this->input->post('idusuario') );

        if ( $respuesta === FALSE || $this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            $respuesta = array( FALSE );
        }else{
            $this->db->trans_commit();
            $respuesta = array( TRUE );
        }

       }
       echo json_encode( array($respuesta) );
    }

    function borrarUsuario(){
        //MAR 
        $respuesta = array("resultado" => FALSE);
        if ($this->input->post("idusuario")) {
            $respuesta['resultado'] = $this->Usuarios_model->borra_usuario($this->input->post('idusuario'));    
        }
        echo json_encode($respuesta);
    }
 
    function opciones_especiales(){
        $data =  base64_decode(file_get_contents('php://input'));
        $resultado = array( "resultado" => FALSE );

        if( $data && $data !== FALSE ){
            $data = json_decode( $data );

            $this->db->trans_begin();

            if( $data->supervisar ){
                $supervisar = implode(",",$data->supervisar);
                $update_user = array(
                    "sup"=> $supervisar,
                    "idmodifica" => $this->session->userdata("inicio_sesion")["id"],
                    "fecha_mod" => date('Y-m-d H:ia')
                );
                
                $editar_user = $this->Usuarios_model->edita_usuario( $update_user, $data->idusuario );
            }else{
                $this->Usuarios_model->updateSup($data->idusuario);
            }

            if( $data->departamentos ){
                $departamentos = $data->departamentos;
                $insert_detp = [];
                foreach( $departamentos as $row ){
                    $insert_detp[] = array(
                        "idusuario"=> $data->idusuario,
                        "iddepartamento" => $row,
                        "fecha_creacion" => date( "Y-m-d H:i:s" )
                    );
                }
                $editar_deptos = $this->Usuarios_model->insert_user_depto( $insert_detp, $data->idusuario );
            }else{
                $this->Usuarios_model->del_depto($data->idusuario);
            }
            

            if ( $this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                $resultado = array( "resultado" => FALSE );
            }else{
                $this->db->trans_commit();
                $resultado = array( "resultado" => TRUE );
            }
           
        }
        echo json_encode($resultado); /// ["res"=>$resultado , "data"=>$data] ["res"=>$resultado , "data"=>$data, "respuest"=>$res]
    }
    
    function usuario_aut_gastos(){
        $permisos = '';
        $resultado = array( "resultado" => FALSE );

        if( $_POST && $_POST !== FALSE ){
            $this->db->trans_begin();
            // elimina depto
            $ants = $this->db->query("SELECT idautpermiso, iddepartamento FROM usuario_aut_gastos WHERE idusuario =? AND estatus=1 ",[$_POST["idusuario"]]);
            $news = $_POST["dept"];
            $depDel = [];
            if($ants->num_rows() > 0){
                $ants = $ants->result_array();
                foreach($ants as $ant){
                    $val = 0;
                    foreach($news as $nw){
                        if($ant['iddepartamento'] == $nw)
                            $val +=1;
                    }
                    if($val==0)
                        array_push($depDel, $ant);
                }
                $otr=[];
                if(!empty($depDel)){
                    foreach($depDel as $dl){
                        $this->db->update('usuario_aut_gastos',["estatus"=>0], "idautpermiso ='".$dl['idautpermiso']."'");
                    }
                }
            }
            /***    ****/
            foreach($_POST["gasto"] as $gs){
                $permisos .= $gs.",";
            }
            $permisos = rtrim($permisos, ',');
            $arr = [];
            // update o insert permisos
            foreach($_POST["dept"] as $dp){
                $busc = $this->db->query("SELECT idautpermiso FROM usuario_aut_gastos WHERE idusuario =? AND iddepartamento= ?",[$_POST["idusuario"], $dp]);
                if($busc->num_rows() > 0){
                    // actualiza
                    $busc = $busc->row()->idautpermiso;
                    $this->db->update('usuario_aut_gastos',["idusuario" => $_POST["idusuario"], "iddepartamento" => $dp,"permiso"=> $permisos,"estatus" => 1],"idautpermiso ='".$busc."'");
                }else{
                    // inserta
                    $this->db->insert('usuario_aut_gastos',["idusuario" => $_POST["idusuario"], "iddepartamento" => $dp,"permiso"=> $permisos,"estatus" => 1]);
                } 
            }
        }
        if ( $this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            $resultado = array( "resultado" => FALSE );
        }else{
            $this->db->trans_commit();
            $resultado = array( "resultado" => TRUE );
        }
        echo json_encode($resultado);
        //echo json_encode();
    }
    
}