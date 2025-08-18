<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$respuesta = array();
$msj="Ha ocurrido un error, reportarlo";
$resultado = false;
$data="";
$accion = isset($_POST["accion"])?$_POST["accion"]:"";
$idregistro= isset($_POST["idregistro"])?$_POST["idregistro"]:"";

class Provedores_cxp extends CI_Controller {

    function __construct(){
        parent::__construct();
        if( !$this->session->userdata("inicio_sesion") && !in_array( $this->session->userdata("inicio_sesion")['rol'], array( 'CP' ) ) )
            redirect("Login", "refresh");
        else
            $this->load->model('Provedores_model');
    }

    public function index(){//
        $this->load->view("vista_provedores");
    }

    public function reactivar_proveedor(){
        if( $this->input->post("idproveedor") ){
            echo json_encode( $this->Provedores_model->cambiar_estatus_proveedor( $this->input->post("idproveedor"), 1, "" ) );
        }
    }

    public function bloquear_proveedor(){
        if( $this->input->post("idproveedor") ){
            echo json_encode( $this->Provedores_model->cambiar_estatus_proveedor( $this->input->post("idproveedor"), 0, $this->input->post("observacion") ) );
        }
    }

    public function eliminar_proveedor($idproveedor){
        $resultado = $this->Provedores_model->eliminar_proveedor( $idproveedor ); /** FECHA: 28-MAYO-2025 | ELIMINAR PROVEEDOR RECHAZADO | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
        echo json_encode(['success' => $resultado]); /** FECHA: 28-MAYO-2025 | ELIMINAR PROVEEDOR RECHAZADO | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
    }

    public function verbancos(){
        $this->load->model('Provedores_model');

        $datos = $this->Provedores_model->getbancos( NULL );
        $respuesta['data'] = array();

        if($datos->num_rows()>0){
            foreach($datos->result() as $row){
                $respuesta['data'][] = array( "idbanco"=>$row->idbanco, "nombre"=>$row->nombre );
            }
        }

        echo json_encode($respuesta);
    }
    
    public function versucursales(){
        $this->load->model('Provedores_model');

        $datos = $this->Provedores_model->getsucursal( NULL );
        $respuesta['data'] = array();

        if($datos->num_rows()>0){
            foreach($datos->result() as $row){
                $respuesta['data'][] = array( "id_estado"=>$row->id_estado, "estado"=>$row->estado );
            }
        }

        echo json_encode($respuesta);
    }
    
    public function datosfiscales_prov(){
        $this->load->view("head");
        $this->load->view("menu_navegador");
        $this->load->view("Catalogos/datosfiscales_prov");
        $this->load->view("footer");
      }
    
      
    public function TablaProv_Cat(){
        echo json_encode( [ 
          "data" => $this->Provedores_model->getProveedoresConta()->result_array(),
          "opciones" => '<div class="btn-group"><button type="button" class="btn btn-warning btn-sm editar_prov_cat "><i class="fas fa-edit"></i></button></div>',
          "rol" => $this->session->userdata("inicio_sesion")['rol']
        ]);
      }
    
    public function updateprov_cat(){

        $respuesta = array( false );

        if( isset($_POST) && !empty( $_POST ) ){
                $id = $this->input->post("rfc_prov");
                $data = array(  
                    "rs_proveedor"=>$this->input->post("razon_social"),
                    "rf_proveedor"=>empty($this->input->post("rf_prov")) ? "SIN ESPECIFICAR" : $this->input->post("rf_prov") ,
                    "cp_proveedor" =>empty($this->input->post("cp_prov")) ? '0' : $this->input->post("cp_prov")
                );
                $respuesta = $this->Provedores_model->editcat_prov( $data, $id);
        }
        
        

        echo json_encode( array($respuesta) );
    }
    
    
    public function verusers(){
        $this->load->model('Provedores_model');

        $datos = $this->Provedores_model->getusers( NULL );
        $respuesta['data'] = array();

        if($datos->num_rows()>0){
            foreach($datos->result() as $row){
                $respuesta['data'][] = array( "idusuario"=>$row->idusuario, "nombres"=>$row->nombres );
            }
        }

        echo json_encode($respuesta);
    }

    public function validar_rfc_prov(){ 
        $rfc = $this->input->post("rfc");
        $consulta=$this->db->query("SELECT * FROM proveedores WHERE rfc='$rfc'"); 
        echo json_encode(array($consulta->num_rows()));
    }

    public function validar_email_prov(){ 
        $email = $this->input->post("email");
        $consulta=$this->db->query("SELECT * FROM proveedores WHERE email='$email'"); 
        echo json_encode(array($consulta->num_rows()));
    }


    public function validar_clabe_prov(){ 
        $clabe = $this->input->post("clabe");
        $consulta=$this->db->query("SELECT * FROM proveedores WHERE cuenta='$clabe'"); 
        echo json_encode(array($consulta->num_rows()));
    }


    public function validar_cuenta_prov(){ 
        $cuenta = $this->input->post("cuenta");
        $consulta=$this->db->query("SELECT * FROM proveedores WHERE cuenta='$cuenta'"); 
        echo json_encode(array($consulta->num_rows()));
    }

 
    public function registrar_nuevo_proveedor(){
        // FECHA : 01-JULIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
        if( isset($_POST) && !empty( $_POST ) ){
            $this->load->model("Provedores_model");  
            $data = array(  
                "nombre"=>$this->input->post("nombre"),
                "rfc"=>$this->input->post("rfc"),
                "contacto"=>$this->input->post("contacto"),
                "email"=>$this->input->post("email"),
                "tipocta"=>$this->input->post("tipocta"),
                "cuenta"=>$this->input->post("cuenta"),
                "excp"=>$this->input->post("excepcion_factura"),
                "idby" => $this->session->userdata("inicio_sesion")['id'],
                "sucursal"=>$this->input->post("idsucursal"),
                "tipo_prov"=>$this->input->post("tipoprov"),
                "idusuario"=>$this->input->post("idusuario"),   
                "idbanco"=>$this->input->post("idbanco"),
                "fecadd"=>date('Y-m-d g:ia'),
                "estatus" => 9,
                "rf_proveedor" => $this->input->post("rf_proveedor"),
                "cp_proveedor" =>$this->input->post("cp_proveedor"),
                //"prov_serv" => $this->input->post("proveedor_servicio")
            );
            $nom_limpio = preg_replace('/[^a-zA-Z]/', '', iconv('UTF-8', 'ASCII//TRANSLIT', $this->input->post("nombre")));
            $respuesta = $this->Provedores_model->insertar_nuevo( $data ); 
            $this->db->update("proveedores", array("alias" => substr( str_replace(" ","", $nom_limpio ), 0, 5).$this->db->insert_id()), "idproveedor = '".$this->db->insert_id()."'");
        }
        echo json_encode( array($respuesta));
    }

    function updateProveedor(){//
        $respuesta = array( false );

        if( isset($_POST) && !empty( $_POST ) ){
            $data = array(
                "nombre"=>$this->input->post("nombreActual"),
                "rfc" => $this->input->post("rfcA"),
                "contacto" => $this->input->post("contactoA"),
                "email" => $this->input->post("emailA"),
                "tipocta" => $this->input->post("tipoctaA"),
                "cuenta" => $this->input->post("cuentaA"),
                "sucursal" => $this->input->post("idsucursalA"),
                "tipo_prov" => $this->input->post("tipoprovA"),
                "idupdate" => $this->session->userdata("inicio_sesion")['id'],
                "fecha_update" => date("Y-m-d H:i:s"),
                "idbanco" => $this->input->post("idbancoA"),
                "idusuario" => $this->input->post("idusuarioA"),
                "excp"=>$this->input->post("excepcion_factura"),
                "honorarios" => $this->input->post("cuenta_nomina"),
                "estatus" => $this->input->post("clase_prov"),
                //"prov_serv" => $this->input->post("proveedor_servicio_edit"),
                "dias_credito" => $this->input->post("dias_credito"),
                "monto_credito" => $this->input->post("monto_credito")
            );
        
            $respuesta = $this->Provedores_model->edita_proveedor( $data, $this->input->post('idproveedor') );
        
        }

       echo json_encode( array($respuesta) );
    }


    public function ver_datosprovs(){
        echo json_encode( array( "data" => $this->Provedores_model->get_provs()->result_array() ));
    }

    public function ver_datosprovs_temporales(){
        echo json_encode( array( "data" => $this->Provedores_model->get_provs_temp()->result_array() ));
    }

    public function ver_datosprovs_cm(){
        echo json_encode( array( "data" => $this->Provedores_model->get_provs_colaboradores()->result_array() ));
    }
    
    public function ver_datosprovs2($idproveedor){

     $this->load->model("Provedores_model");

    $dat =  $this->Provedores_model->get_provs2($idproveedor)->result_array();
    echo json_encode($dat);
    }


    public function get_provs3($idproveedor){
        $this->load->model("Provedores_model");
        $dat =  $this->Provedores_model->get_provs3($idproveedor)->result_array();
        echo json_encode($dat);
    }

    /**GENERACION DE TXT PARA ALTA DE PROVEEDOR**/
    public function txtProveedores(){
        $response['resultado'] = TRUE;
        if(  $this->input->post("idproveedor") && !empty( json_decode($this->input->post("idproveedor")) )){

            $idproveedores = json_decode($this->input->post("idproveedor"));

            ///ENCABEZADO DEL DOCUMENTO TXT
            /*****************************/
            $noarchivo = $this->input->post("consec");
            $num_encabezado = "01";
            $encabezado = $num_encabezado."0000001".date("Ymd").str_pad($noarchivo, 3, "0",STR_PAD_LEFT).chr(13).chr(10);
            $mensaje = $encabezado;
            $carperta = "UPLOADS/txtbancos/";
            $i=2;
            /****************************/
            foreach( $this->Provedores_model->txt_proveedores( implode(",", $idproveedores) )->result_array() as $datos ){

                $detalle = '02';
                //CLABE DE BANCO SACADO DEL CATALOGO
                $clv_banco =  $datos['banco'];
                //TIPO DE CUENTA DE BANCO
                $tipo_cuenta = str_pad($datos['tipo'], 2, "0", STR_PAD_LEFT);
                //NUMERO DE CUENTA
                $num_cuenta = str_pad($datos['num_cuenta'], 20, "0", STR_PAD_LEFT );
                //ALIAS DEL PROVEEDOR
                $alias_beneficiario = str_pad( $datos['alias'], 15, " ",STR_PAD_RIGHT);
                //NOMBRE DEL PROVEEDOR
                $nombre_beneficiario = str_pad( substr(limpiar_dato($datos['nombre_beneficiario']), 0, 40), 40," ",STR_PAD_RIGHT);
                //RFC DEL PROVEEDOR
                $rfc = ($datos['rfc'] == 'CLIENTE') ? str_repeat(' ', 18) : str_pad($datos['rfc'], 18, " ", STR_PAD_RIGHT); /** FECHA: 05-AGOSTO-2025 | AJUSTE CATALOGO PROVEEDORES | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                // $rfc = str_pad($datos['rfc'], 18, " ", STR_PAD_RIGHT);
                //ESPACIO BLANCO SOLICITADO
                $space = str_pad( '', 205, " ", STR_PAD_RIGHT);
                //EMAIL DEL PROVEEDOR
                $email = str_pad($datos['email'], 80, " ", STR_PAD_RIGHT);

                //LINEA DEL DOCUMENTO TXT
                $descDetalle = $detalle.str_pad($i, 7, "0",STR_PAD_LEFT)."01".$clv_banco.$tipo_cuenta.$num_cuenta.$alias_beneficiario.$nombre_beneficiario.$rfc.$space.$email;

                $mensaje.= $descDetalle.chr(13).chr(10);

                $i++;
                $lineas=$i;
                $regist=$i-2;
            }
    
            $nombre = "PROVEEDOR"."_".date("Ymdhis").".txt";
            $nombre_archivo = $carperta.$nombre;

            $mensaje .= "09".str_pad($lineas,7,"0",STR_PAD_LEFT).str_pad($regist,7,"0",STR_PAD_LEFT).chr(13).chr(10);
            
            if(file_exists($nombre_archivo)){
                unlink($nombre_archivo);
            }

            if($archivo = fopen($nombre_archivo, "a")){
                $response['arch'] = fwrite($archivo, $mensaje) ? "ok" : "error";
                fclose($archivo);
            }

            $response['file'] = base_url( "UPLOADS/txtbancos/".$nombre );
            $response['resultado'] = FALSE;
        }
        else{
            $response['resultado'] = TRUE;
            $response['mensaje'] = 'NO SE SELECCIONÓ NINGÚN PROVEEDOR';
                
        }
        echo json_encode( $response );
    }
    /****/
    
    function desbloquea_prov(){
        // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
        $idusuario = $this->session->userdata('inicio_sesion')['id'];
        $response = array();
        $response['resultado'] = FALSE;
        $idprov=$_POST["idproveedor"];
        /**
         * INICIO
         * FECHA : 01-JULIO-2025 | @author Efrain Martinez progamador.analista38@ciudadmaderas.com
         * SE AGREGA REALIZA UN UPDATE A LA TABLA DE PROVEEDOR CUANDO DE DESBLOQUEA UN PROVEEDOR DESDE CXP EN EL CUAL GUARDA LA OBSERVACION DE LAS SOLICITUDES QUE VAN A SER
         * ACTUALIZADAS.
         */
        $solicitudes_afectadas = $this->db->query("SELECT idsolicitud FROM solpagos WHERE  idProveedor=$idprov and idetapa=10 and tendrafac=1;")->result_array();
        $idSol = array_column($solicitudes_afectadas, 'idsolicitud');
        $idSol = implode(', ', $idSol);
        if ($idSol){
            $this->db->query("UPDATE proveedores 
                                SET idupdate = $idusuario, 
                                    fecha_update = NOW(), 
                                    observaciones = 'EL PROVEEDOR FUE DESBLOQUEADO CORRECTAMENTE. LAS SIGUIENTES SOLICITUDES: $idSol FUERON ACTUALIZADAS, CAMBIANDO EL VALOR DE LA COLUMNA tendrafac DE 1 a NULL' 
                                WHERE idproveedor = $idprov");
        }
        /**
         * FIN
         * FECHA : 01-JULIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com
         */
        $query = $this->db->query("update solpagos set tendrafac=null where idProveedor=$idprov and idetapa=10 and tendrafac=1;");
        $response["resultado"]=$query;
        echo json_encode($response);
    }

    function ProductosXprov(){
        $query2=$this->db->query("select idproveedor,nombre,alias from proveedores;");
        $this->load->view("v_productosxprov",array( "proveedores"=>$query2 ));
    }

    public function getProductosXprov() {
        echo json_encode($this->Provedores_model->getProductosXprovM()->result());
    }
    
    function acciones_ProductosXprov(){
      global $respuesta,$msj,$resultado,$data,$accion,$idregistro;
      if($accion=="registrar"){
          if($this->db->query("select * from productosxprov where producto='$_POST[producto]' and idproveedor=$_POST[proveedor];")->num_rows()==0)
            $resultado= $this->Provedores_model->inserta_prodXprov( $this->input->post() );
          else
              $msj="Ya se ha registrado este producto con este proveedor, verifique sus datos";
      }else if($accion=="editar"){
          $resultado= $this->Provedores_model->edita_prodXprov( $this->input->post() );
      }else if($accion=="des-activar"){
        $resultado= $this->Provedores_model->des_activa_prodXprov( $this->input->post() );
        $msj="El registro ha sido actualizado";
      }
      $respuesta= array("resultado"=>$resultado,"msj"=>$msj,"data"=>$data);
      echo json_encode($respuesta);
    }

    function actualizarProvExcel(){

        // Recibir los datos del frontend
        $excelData = json_decode($_POST['excelData'], true);

        if ($excelData[0][0] != 'CUENTAS POR PAGAR' || $excelData[1][0] != 'Lista de proveedores' || count($excelData[2]) != 15) {
            echo json_encode(array("status" => 400, "msg" => "EL FORMATO DEL ARCHIVO SUBIDO NO ES COMPATIBLE CON EL LAYOUT REQUERIDO"));
            exit;
        }

        $datosActualizar = [];
        unset($excelData[0], $excelData[1], $excelData[2]);

        $batchSize = 5000; // Define el tamaño del lote
        $batch = [];

        try {
            foreach ($excelData as $valorExcel) {
                
                if (count($valorExcel) > 0) {
                    // Reemplaza todo lo que no sea un número o punto decimal
                    $valorLimpio = preg_replace('/[^0-9.]/', '', $valorExcel[13]);
                    
                    // Asegura que solo haya un punto decimal
                    $valorLimpio = preg_replace('/\.(?=.*\.)/', '', $valorLimpio);
                    
                    if ( (is_numeric($valorExcel[12]) && is_numeric($valorLimpio)) || (trim($valorExcel[12]) == "" && trim($valorLimpio) == "") ) {
                        $batch[] = ["dias_credito"  => $valorExcel[12], 
                                    "monto_credito" => $valorLimpio,
                                    "idproveedor"   => $valorExcel[14]];
                    }
                }
                // Insertar en lotes cuando se alcance el tamaño definido
                if (count($batch) >= $batchSize) {
                    $this->Provedores_model->actualizarProveedoresExcel("proveedores", $batch);
                    $batch = []; // Vaciar lote después de insertar
                }
            }
            
            // Insertar los registros restantes
            if (!empty($batch)) {
                $this->Provedores_model->actualizarProveedoresExcel("proveedores", $batch);
            }

            echo json_encode(array("status" => 200, "msg" => "REGISTROS ACTUALIZADOS CORRECTAMENTE"));
        } catch (Exception $erro) {
            echo json_encode(['status' => 'error', 'message' => $erro->getMessage()]);
        }
    }
}


 