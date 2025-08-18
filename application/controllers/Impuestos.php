<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*Validaciones que controlan el flujo de la gestion
 *  de las facturas que puede controlar Dirreccion General*/

class Impuestos extends CI_Controller {
    
    function __construct(){
        parent::__construct();
        
        if( $this->session->userdata("inicio_sesion") && $this->session->userdata("inicio_sesion")['im'] )
            $this->load->model(array("Impuesto", "Solicitudes_solicitante", 'Complemento_cxpModel')); /**  Cambio se agrega el modelo Complemento_cxpModel | FECHA: 05-Junio-2024 | @author Lalo Silva <programador.analista38@ciudadmaderas.com>  **/
        else        
            redirect("Login", "refresh");
        
        $this->load->model("Impuesto");
    }

    public function index(){
        $this->load->view("vista_solicitante_impuesto");
    }

    
    function tablaDGimpuestos(){

        $data = $this->Impuesto->obtenerSolPendientesImpuestos()->result_array();

        for( $i = 0; $i < count($data); $i++ ){

            $fecha = date("d", strtotime( $data[$i]['FECHAFACP'] ));
            $fecha .= "/".array("Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic")[date("m", strtotime( $data[$i]['FECHAFACP'] )) - 1];
            $fecha .= "/".date("Y", strtotime( $data[$i]['FECHAFACP'] ));

            $data[$i]['FECHAFACP'] = $fecha;
            $data[$i]['pa'] = 0;
        }

        echo json_encode( array( "data" => $data ));
    }
    
    /**---------------------------------------------------------INICIO-------------------------------------------------**/
    /**FUNCION QUE LLAMA LAS FACTURAS AUTORIZADAS O BORRADORS PARA SEGUIR EDITAR EN LA PARTEDE LOS DIRECTORORES DE AREA**/
    /**LA FUNCION DE ABAJO HACE EL TRABAJO DE TRAER TODAS LAS OPCIONES PARA QUE LOS DEL AREA SOLICITANTE PUEDAN TRABAJAR*/
    public function tabla_autorizaciones_impuestos(){
        $resultado = $this->Impuesto->solicitudes_impuestos();
        //$numero_solicitudes = $this->Solicitudes_solicitante->solicitudes_formato_impuestos()->num_rows();
        if( $resultado->num_rows() > 0 ){
            $resultado = $resultado->result_array();
            for( $i = 0; $i < count( $resultado ); $i++ ){
                $resultado[$i]['opciones'] = $this->opciones_autorizaciones( $resultado[$i]['idsolicitud'], $resultado[$i]['idetapa'], $resultado[$i]['idusuario'], $resultado[$i]['visto'], $resultado[$i]['prioridad'], $resultado[$i]['idAutoriza'], $resultado[$i]['nomdepto'] );
                $resultado[$i]['etapa'] = "Impuesto";
            }
        }else{
            $resultado = array();
        }

    echo json_encode( array( "data" => $resultado /*, "por_autorizar" => $numero_solicitudes*/ ));
    }

    /**NO SEPARAR ESTA FUNCION CON LA DE ARRIBA ESTAS HACEN EL TRABAJO DE COLOCAR LAS OPCIONES*/
    public function opciones_autorizaciones( $idsolicitud, $estatus, $autor, $visto, $prioridad, $dg, $departamento ){

        $opciones = '<div class="btn-group-vertical">';

        $opciones .= '<button style="margin:2px;" type="button" class="btn btn-sm btn-primary consultar_modal notification" value="'.$idsolicitud.'" data-value="BAS" title="VER SOLICITUD"><i class="fas fa-eye"></i>'.( $visto == 0 ? '</i><span class="badge">!</span>' : '').'</button>';

        switch( $estatus ){
            case 1:
                $opciones .= '<button style="margin:2px;" type="button" class="btn btn-sm btn-success enviar_a_dg" value="'.$idsolicitud.'" title="ENVIAR SOLICITUD"><i class="fas fa-share-square"></i></button>';
                $opciones .= '<button style="margin:2px;" type="button" class="btn btn-sm btn-warning editar_factura" value="'.$idsolicitud.'" title="EDITAR SOLICITUD"><i class="fas fa-pencil-alt"></i></button>';
                $opciones .= '<button style="margin:2px;" type="button" class="btn btn-sm btn-danger borrar_solicitud" value="'.$idsolicitud.'" title="BORRAR SOLICITUD"><i class="fas fa-trash"></i></button>';
                break;
        }

        return $opciones."</div>";
    }

        /***PARA GUARDAR Y EDITAR TODO LO CORRESPONDIENTE CON LOS PAGOS PARA IMPUESTOS ***/
        function guardar_impuesto(){
            $regex = "/^[0-9]+$/";      /**  Modificación de la función para guardar el impuesto para agregar los campos Proyecto, Oficina, Servicio Partida | FECHA: 05-Junio-2024 | @author Lalo Silva <programador.analista38@ciudadmaderas.com>  **/
            $resultado = array("resultado" => FALSE);
            $revisar = $this->Impuesto->verficar_duplicidad( '', $this->input->post("referencia") );
            if( $revisar ->num_rows() === 0 ){
                if( isset($_POST) && !empty($_POST) ){
                    $this->db->trans_begin();
                        
                    $data = array(
                        "proyecto" => preg_match($regex, $this->input->post('proyecto')) ? null : "IMPUESTO",
                        "folio" => $this->input->post("referencia"),
                        "ref_bancaria" => $this->input->post("referencia"),
                        "idEmpresa" => $this->input->post('empresa'),
                        "idResponsable" => $this->session->userdata("inicio_sesion")['id'],
                        "idusuario" => $this->session->userdata("inicio_sesion")['id'],
                        "nomdepto" => "IMPUESTOS",
                        "idProveedor" => $this->input->post('proveedor'),
                        "cantidad" => $this->input->post('total'),
                        "moneda" => "MXN",
                        "justificacion" => $this->input->post('solobs'),
                        "servicio" => 7,
                        "fecelab" => $this->input->post('fecha'),
                        "metoPago" => $this->input->post('forma_pago'),
                        "idetapa" => 1
                    );
        
                    $resultado = $this->Impuesto->insertar_solicitud( $data );
        
                    if($resultado && $this->input->post('oficina')){
                        $insert = array(
                            "idOficina" => $this->input->post('oficina'),
                            "idProyectos" => $this->input->post('proyecto'),
                            "idTipoServicioPartida" => $this->input->post('tServicio_partida'),
                            "idsolicitud" => $resultado
                        );
                        $this->Complemento_cxpModel->insertar_solicitud_proyecto_oficina( $insert );
                    }   /**  FIN  Modificación de la función para guardar el impuesto para agregar los campos Proyecto, Oficina, Servicio Partida | FECHA: 05-Junio-2024 | @author Lalo Silva <programador.analista38@ciudadmaderas.com>  **/
                    
                    //CARGA INFORMACION EN EL LOG PARA EL HISTORIAL
                    log_sistema($this->session->userdata("inicio_sesion")['id'], $resultado, "SE HA CREADO UNA NUEVA SOLICITUD");
        
                    //PARA LA INSERCION DE LAS OBSERVACIONES DE LAS SOLICITUDES
                    //$this->Solicitudes_solicitante->insertar_observaciones_solicitante($data["idsolicitud"], "Se solicita el siguiente gasto. ".$this->input->post("solobs") );
        
                    if ( $this->db->trans_status() === FALSE ){
                        $this->db->trans_rollback();
                        $resultado = array("resultado" => FALSE);
                    }else{
                        $this->db->trans_commit();
                        $resultado = array("resultado" => TRUE);
                    }
                }
            }else{
                $resultado["mensaje"] = strtoupper( "¡Línea de captura repetida! Verifique la información capturada. #".$revisar->row()->idsolicitud );
            }
    
            echo json_encode( $resultado );
        }

        
    public function enviar_impuestos(){
        $respuesta = array( "resultado" => FALSE );

        if( $this->input->post("idsolicitud") ){
            
            /*ANTIGUO PROCEDIMIENTO PARA ENVIAR LOS PAGOS DE IMPUESTOS*/ 
            /*
            $respuesta['resultado'] = $this->db->query("CALL insertar_pago( ?, ?, ?)", array(
                "fecha " => date("Y-m-d H:i:s"),
                "idsolicitud " => $this->input->post("idsolicitud"),
                "idautor " => $this->session->userdata("inicio_sesion")['id']
            )) ? TRUE : FALSE;
            
            log_sistema($this->session->userdata("inicio_sesion")['id'], $this->input->post("idsolicitud"), "SE HA ENVIADO PARA SU PAGO");
            */
            $respuesta['resultado'] = $this->Solicitudes_solicitante->enviar_a_dg( $this->input->post("idsolicitud"), $this->input->post("departamento") );
            log_sistema($this->session->userdata("inicio_sesion")['id'], $this->input->post("idsolicitud"), "SE HA ENVIADO PARA AUTORIZACIÓN");
        }

        echo json_encode( $respuesta );
    }

    function editar_impuesto(){

        $resultado = array("resultado" => FALSE);
        
        if( isset($_POST) && !empty($_POST) ){
            $revisar = $this->Impuesto->verficar_duplicidad( $this->input->post('idsolicitud'), $this->input->post("referencia") ); /**   Modificación de la función para edción el impuesto para agregar los campos Proyecto, Oficina, Servicio Partida | FECHA: 05-Junio-2024 | @author Lalo Silva <programador.analista38@ciudadmaderas.com>  **/
            if( $revisar ->num_rows() === 0 ){
                $this->db->trans_begin();

                $this->load->model("Solicitudes_solicitante");

                $data = array(
                    "folio" => $this->input->post("referencia"),
                    "ref_bancaria" => $this->input->post("referencia"),
                    "idEmpresa" => $this->input->post('empresa'),
                    "idProveedor" => $this->input->post('proveedor'),
                    "cantidad" => $this->input->post('total'),
                    "justificacion" => $this->input->post('solobs'),
                    "metoPago" => $this->input->post('forma_pago'),
                    "fecelab" => $this->input->post('fecha')
                );

                $resultado = $this->Solicitudes_solicitante->editar_solicitud( $this->input->post("idsolicitud"), $data );

                $proyectoOficina = array(
                    "idProyectos" => $this->input->post('proyecto'),
                    "idOficina" => $this->input->post('oficina'),
                    "idTipoServicioPartida" => $this->input->post('tServicio_partida')
                );

                if($this->input->post("idsolicitud") && $this->input->post('oficina')){
                    $existeRegistro = $this->Solicitudes_solicitante->selectProyectoOficina($this->input->post("idsolicitud"))->result_array();
                    if(count($existeRegistro)>0)
                        $this->Solicitudes_solicitante->updateProyectoOficina( $proyectoOficina, $this->input->post("idsolicitud") );
                    else{
                        $insert = array(
                            "idOficina" => $this->input->post('oficina'),
                            "idProyectos" => $this->input->post('proyecto'),
                            "idTipoServicioPartida" => $this->input->post('tServicio_partida'),
                            "idsolicitud" => $this->input->post("idsolicitud")
                        );
                        $this->Complemento_cxpModel->insertar_solicitud_proyecto_oficina( $insert );
                    }
                }
                /**  FIN  Modificación de la función para edción el impuesto para agregar los campos Proyecto, Oficina, Servicio Partida | FECHA: 05-Junio-2024 | @author Lalo Silva <programador.analista38@ciudadmaderas.com>  **/
                    //CARGA INFORMACION EN EL LOG PARA EL HISTORIAL
                log_sistema($this->session->userdata("inicio_sesion")['id'], $resultado, "SE HA CREADO UNA NUEVA SOLICITUD");

                //PARA LA INSERCION DE LAS OBSERVACIONES DE LAS SOLICITUDES
                //$this->Solicitudes_solicitante->insertar_observaciones_solicitante($data["idsolicitud"], "Se solicita el siguiente gasto. ".$this->input->post("solobs") );

                if ( $this->db->trans_status() === FALSE ){
                    $this->db->trans_rollback();
                    $resultado = array("resultado" => FALSE);
                }else{
                    $this->db->trans_commit();
                    $resultado = array("resultado" => TRUE);
                }
            }else{
                $resultado["mensaje"] = strtoupper( "¡Línea de captura repetida! Verifique la información capturada. #".$revisar->row()->idsolicitud );
            }
        }

        echo json_encode( $resultado );
    }
}