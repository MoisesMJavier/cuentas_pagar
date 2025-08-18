<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH . "vendor/dropbox/vendor/autoload.php";

use  Kunnu\Dropbox\Dropbox;
use  Kunnu\Dropbox\DropboxApp;
//INICIO | FECHA: 16-ABRIL-2025 | @uthor Efrain Martinez Muñoz <programador.analista38@ciudadmaderas.com> | SE INTEGRAN ESTAS LIBRERIAS QUE SE ULTIZAN PARA CONVERTIR LOS ARCHIVOS .xlsx y .docs A pdf
// Y ASI PODER MOSTRARLOS EN EL IFRAME.
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\IOFactory as WordIOFactory;
use PhpOffice\PhpSpreadsheet\IOFactory as ExcelIOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Tcpdf as ExcelPdfWriter;
//FIN  | FECHA: 16-ABRIL-2025 | @uthor Efrain Martinez Muñoz <programador.analista38@ciudadmaderas.com>



class Devoluciones_Traspasos extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        if ($this->session->userdata("inicio_sesion") && in_array($this->session->userdata("inicio_sesion")['rol'], array('PV','SPV', 'DA', 'FP', 'CA', 'SU', 'SB', 'CJ', 'AS', 'CE', 'CX', 'CC', 'CT', 'CP', 'AD', 'CAD', 'CPV', 'GAD', 'CI','AOO','COO','IOO','SAC','DIO','PVM','FAD', 'AS', 'SO'))) /** INICIO FECHA: 26-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
            $this->load->model(array('M_Devolucion_Traspaso', 'Solicitudes_solicitante', 'Consulta', 'Lista_dinamicas', 'M_historial'));
        else
            redirect("Login", "refresh");
    }

    public function Devolucion(){
        if (in_array($this->session->userdata("inicio_sesion")['rol'], array("AD", "FP", "PV",'SPV', 'CAD', 'CPV', 'GAD', 'CC', 'CX', 'CE', 'CI', 'DA', 'CP','AOO','COO','IOO','SAC','DIO','PVM','AS'))) {
            $this->load->view("vista_solicitante_devoluciones_new");
        } else {
            redirect("Login", "refresh");
        }
    }

    public function Histdevolucionesytraspasos( $consulta = "DEV" )
    {   $data['consulta']=$consulta;
        if (in_array($this->session->userdata("inicio_sesion")['rol'], array("AD", "FP", "PV",'SPV', 'CAD', 'CPV', 'GAD', 'CC', 'CX', 'CE', 'CI', 'DA', 'CP','AOO','COO','IOO','SAC','DIO','PVM','FAD','AS'))) {
            $this->load->view("v_historial_admon",$data);
        } else {
            redirect("Login", "refresh");
        }
    }

    /**---------------------------------------------------------INICIO-------------------------------------------------**/
    /**FUNCION QUE LLAMA LAS FACTURAS AUTORIZADAS O BORRADORS PARA SEGUIR EDITAR EN LA PARTEDE LOS DIRECTORORES DE AREA**/
    /**LA FUNCION DE ABAJO HACE EL TRABAJO DE TRAER TODAS LAS OPCIONES PARA QUE LOS DEL AREA SOLICITANTE PUEDAN TRABAJAR*/
    public function tabla_autorizaciones()
    {
        $resultado = $this->M_Devolucion_Traspaso->get_solicitudes_autorizadas_area();

        /*log_message("debug", $this->db->last_query() . "");*/
        // $numero_solicitudes = $this->M_Devolucion_Traspaso->solicitudes_formato()->num_rows();
        if ($resultado->num_rows() > 0) {
            $resultado = $resultado->result_array();

            for ($i = 0; $i < count($resultado); $i++) {

                //$d = 1;
                $d = $resultado[$i]['idetapa'] == 1 || $this->M_Devolucion_Traspaso->docs_proceso_sol_avanzar($resultado[$i]['idsolicitud'], $this->session->userdata("inicio_sesion")['rol'])->row()->requerido == 0 ? 1 : 0 ;
                
                $regr = ($resultado[$i]['idetapa'] == 1  ? 1 : 0); //0  NO avanza, 1 si avanza
                                
                $esUsuarioPermitido = $this->M_Devolucion_Traspaso->validarUsuarioPermitido($resultado[$i]['idproceso'], $resultado[$i]['idetapa'])->row()->idpermitido ?? null;
                $esUsuarioPermitido = in_array($this->session->userdata("inicio_sesion")['id'], explode(',', $esUsuarioPermitido));

                $esUsuarioSupervisor = $this->M_Devolucion_Traspaso->obtenerUsuarioSupervisado($this->session->userdata("inicio_sesion")['id'])->row()->idpermitido ?? null;
                $esUsuarioSupervisor = in_array($resultado[$i]['idusuario'], explode(',', $esUsuarioSupervisor));
                
                //MAR IF ROL SPV
              /** INICIO
                 * @uthor Efrain Martinez Muñoz | Fecha: 16/04/2025
                 * Se agregaron las variables ($resultado[$i]['estatusLote'] y $resultado[$i]['idproceso']) a la coleccion de las variable que se pasan a la función opciones_autorizaciones()
                 * la cual maneja la manera enl que se muestran los botones de las solicitudes.
                 */
                $resultado[$i]['opciones'] = $this->opciones_autorizaciones($resultado[$i]['idsolicitud'], $resultado[$i]['idetapa'], $resultado[$i]['idusuario'], $resultado[$i]['visto'], $resultado[$i]['prioridad'], $resultado[$i]['idAutoriza'], $resultado[$i]['nomdepto'], $resultado[$i]['tipo_prov'], $resultado[$i]['estatusLote'], $resultado[$i]['idproceso'], $d, $regr, $esUsuarioPermitido, $esUsuarioSupervisor);
                /**
                 * FIN | @uthor Efrain Martinez Muñoz | Fecha: 16/04/2025
                 */
                $resultado[$i]['etapa'] .= $resultado[$i]['prioridad'] ? "<br/><small class='label pull-center bg-red'>RECHAZADA</small>" : "";
            }
        } else {
            $resultado = array();
        }


        echo json_encode(array("data" => $resultado, "por_autorizar" => ''));
    }

    public function tabla_devoluciones(){
        echo json_encode(array("data" =>$this->M_Devolucion_Traspaso->get_devoluciones_parcialidad()->result()));
    }

    public function tabla_facturas_encurso() {
        echo json_encode(array("data" => $this->M_Devolucion_Traspaso->get_solicitudes_en_curso($this->input->post("finicial").' 00:00:00',$this->input->post("ffinal").' 23:59:59')->result_array(), "idloguin_usuario" => $this->session->userdata("inicio_sesion")['id']));
    }

    public function tablaHistorialDevTrap()
    /*
    *@uthor Efraín Martinez Muñoz se agregaron las otras etapas (6, 21, 8) en la que un solicitud puede rechazada.
    */ 
    {
        ini_set('memory_limit', '1024M');
        $data = $this->M_historial->getHistorialDevTrap(
            $this->input->post("activo") > 0 
                ? array(3, 7, 50, 51, 52, 53, 11) 
                : array(30, 54, 6, 21, 8, 0),
            $this->input->post("finicial").' 00:00:00',
            $this->input->post("ffinal").' 23:59:59')->result_array();
        echo json_encode(array("data" => $data, "rol" => $this->session->userdata("inicio_sesion")['rol']));
    }

    public function guardar_solicitud_devolucion(){
        $resultado = array("resultado" => TRUE);
        // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
        if ((isset($_POST) && !empty($_POST))) {
            $this->db->trans_begin();

            //EDITAR O ACTUALIZAR UN PROVEEDOR SELECCIONADO
            if( $this->input->post("operacion") === "TRASPASO" || $this->input->post("operacion") === "TRASPASO OOAM"){
                $idproveedor = $this->input->post("idproveedor_ad");
            }else{

                $nombre_cliente = limpiar_dato($this->input->post("nombreproveedor"));
                if( $this->input->post('forma_pago') == 'MAN' ){// transferencia al extranjero
                    $cuenta = $this->input->post("cuenta_extr");
                    $tipo_cuenta = "40";

                    $nbanco = limpiar_dato($this->input->post("banco_ext"));
                    $aba_swift = $this->input->post("aba");

                    $revisar_banco = $this->db->query("SELECT * FROM bancos WHERE nombre = ? AND clvbanco = ? AND estatus = 2", [ $nbanco, $aba_swift ]);
                    if( $revisar_banco->num_rows() > 0 && $this->input->post("idbanco_MAN") == $revisar_banco->row()->idbanco ){
                        $banco = $revisar_banco->row()->idbanco;
                    }else{
                        $this->db->insert("bancos", [
                            "nombre" => $nbanco,
                            "clvbanco" => $aba_swift,
                            "estatus" => 2
                        ]);
                        $banco = $this->db->insert_id();
                    }
                }else{
                    $banco = $this->input->post("idbanco") ? $this->input->post("idbanco") : 6;
                    $cuenta = $this->input->post("cuenta") ? $this->input->post("cuenta") : 0;
                    $tipo_cuenta = $this->input->post("tipocta") ? $this->input->post("tipocta") : 1;
                }

                $idproveedor=0;
                $rev_cuent_prov = $this->db->query("SELECT * FROM proveedores WHERE nombre = ? ORDER BY cuenta DESC", [ $nombre_cliente ]);

                if( $rev_cuent_prov->num_rows() > 0 ){
                    if( in_array( $this->input->post('forma_pago'), [ 'TEA', 'MAN' ] ) ){
                        $array_prov = [];
                        foreach( $rev_cuent_prov->result() as  $prov ){
                            if($prov->cuenta == $cuenta && $prov->idbanco == $banco && $prov->tipocta == $tipo_cuenta ) {
                                $array_prov["idproveedor"] = $prov->idproveedor;
                                $array_prov["estatus"] =  $prov->estatus;
                            }
                        }
                        if(empty( $array_prov)){
                            foreach( $rev_cuent_prov->result() as  $prov ){
                                if($prov->cuenta == 0){
                                    $array_prov["idproveedor"] = $prov->idproveedor;
                                    $array_prov["estatus"] = $prov->estatus;
                                }
                            }
                            
                            if(!empty($array_prov)){
                                //actualiza registro si tipo cuenta == 0
                                $idproveedor = $array_prov["idproveedor"];
                                /**
                                 * FECHA : 01-JULIO-2025 | @author Efrain Martinez progamador.analista38@ciudadmaderas.com | SE AGREGA EL ID DEL USUARIO QUE REALIZO LA ACTUALIZACION
                                 */
                                $this->db->query("UPDATE proveedores SET cuenta = ?, idbanco = ?, tipocta = ?, idupdate = ?, fecha_update = ? WHERE idproveedor = ? ",[$cuenta,$banco,$tipo_cuenta,$this->session->userdata('inicio_sesion')['id'],date("Y-m-d H:i:s"),$idproveedor]);
                                if($array_prov["estatus"] == 3)
                                $this->M_Devolucion_Traspaso->reactivar_prov($idproveedor);
                            }
                            
                        }else{
                            $idproveedor = $array_prov["idproveedor"];
                            if($array_prov["estatus"] == 3)
                            $this->M_Devolucion_Traspaso->reactivar_prov( $array_prov["idproveedor"]);
                        }
                    }else{
                        if($rev_cuent_prov->row()->estatus == 3)
                        $this->M_Devolucion_Traspaso->reactivar_prov($rev_cuent_prov->row()->idproveedor);
                        $idproveedor = $rev_cuent_prov->row()->idproveedor;
                    }
                }

                if($idproveedor == 0){
                    $this->db->insert("proveedores", [
                        "nombre" => $nombre_cliente,
                        "alias" => substr(str_replace(" ", "", $nombre_cliente), 0, 10).str_pad(rand(0,999), 3, "0", STR_PAD_LEFT),
                        "idbanco" => $banco ,
                        "tipocta" => $tipo_cuenta,
                        "cuenta" => $cuenta,
                        "rfc" => "CLIENTE",
                        "email" => "CLIENTE@CLIENTE.COM",
                        "sucursal" => 1,
                        "fecadd" => date("Y-m-d H:i:s"),
                        "idby" => $this->session->userdata("inicio_sesion")['id'],
                        "estatus" => 2
                    ]);
                    $idproveedor = $this->db->insert_id();
                }
                /*******************************************************************/
            }

            /** INICIO FECHA: 29-ABRIL-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
            $condominio = $_POST["lote"] ? $_POST["lote"] : 'SIN ASIGNAR';
            /** FIN FECHA: 29-ABRIL-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/

            $data = array(
                "proyecto" => ($this->input->post('proyecto')),
                "homoclave" => limpiar_dato($this->input->post('homoclave')),
                "etapa" => limpiar_dato($this->input->post('etapa')),
                "condominio" => $condominio,
                "folio" => "NA",
                "idEmpresa" => $this->input->post('empresa'),
                "idResponsable" => $this->session->userdata("inicio_sesion")['da'],
                "idusuario" => $this->session->userdata("inicio_sesion")['id'],
                "nomdepto" => ($this->input->post('operacion') ? $this->input->post('operacion') : $this->session->userdata("inicio_sesion")['depto']),
                "idProveedor" => $idproveedor,
                "prioridad" => $this->input->post('prioridad'),
                "cantidad" => $this->input->post('proceso') == 30 ? preg_replace("/[^0-9.]/", "", $this->input->post('montoTotal')) : preg_replace("/[^0-9.]/", "", $this->input->post('total')),
                "moneda" => $this->input->post('moneda'),
                "justificacion" => $this->input->post('solobs'),
                "servicio" => $this->input->post('servicio'),
                "fecelab" => $this->input->post('fecha') ? $this->input->post('fecha') : $this->input->post('fecha_Inicio'),
                "metoPago" => $this->input->post('forma_pago'),
                "idproceso" => $this->input->post('proceso'),
                "requisicion" => $this->input->post('titular'),
                "idetapa" => 1
            );

            if($this->input->post("fecha_final"))
                $data["fecha_fin"] = $this->input->post("fecha_final");
            if($this->input->post("programado"))
                $data["programado"] = $this->input->post("programado");
            if( $this->input->post("frmnewsol_cuenta_contable") )
                $data["homoclave"] = $this->input->post("frmnewsol_cuenta_contable");
            if( $this->input->post("frmnewsol_cuenta_orden") )
                $data["orden_compra"] = $this->input->post("frmnewsol_cuenta_orden");

            $resultado = $this->Solicitudes_solicitante->insertar_solicitud($data);

            /** MEJORA FECHA: 08-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
            $idDesarrollo = !empty($_POST["inputManualDesarrollo"]) ? $_POST["inputManualDesarrollo"] : $_POST["desarrollo"];
            $isDesarrolloManual = $_POST["isDesarrolloManual"];

            $idCondominio = !empty($_POST["inputManualCondominio"]) ? $_POST["inputManualCondominio"] : $_POST["condominios"];
            $isCondominioManual = $_POST["isCondominioManual"];

            $idLote = !empty($_POST["inputManualLote"]) ? $_POST["inputManualLote"] : $_POST["idLoteSeleccionado"];
            $isLoteManual = $_POST["isLoteManual"];

            $referencia = !empty($_POST['referencia']) ? $_POST['referencia'] : 'NA';
            $isReferenciaManual = $_POST['isReferenciaManual'];

            $solicitud_drl = $this->db->query("INSERT INTO solicitud_drl (idsolicitud, iddesarrollo, isDesarrolloManual, idcondominio, isCondominioManual, idlote, isLoteManual, referencia, isReferenciaManual)
                                   VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)", array(
                                       $resultado, 
                                       $idDesarrollo,
                                       $isDesarrolloManual,
                                       $idCondominio,
                                       $isCondominioManual,
                                       $idLote,
                                       $isLoteManual,
                                       $referencia,
                                       $isReferenciaManual
                                   ));
            /** FIN MEJORA FECHA: 08-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/

            if($this->input->post('proceso') == 30){
                $parcialidades = array(
                    "idsolicitud" => $resultado,
                    "tipoParcialidad" => $this->input->post("tipoResicion"),
                    "montoParcialidad" => $this->input->post("montoParcialidad"),
                    "numeroParcialidades" => $this->input->post("numeroPagos")
                );

                $this->M_Devolucion_Traspaso->guardarSolicitudesParcialidades($parcialidades);
            }

            if($this->input->post('costo_lote')){
                $dataad['costo_lote'] = $this->input->post('costo_lote');
            }
            if($this->input->post('superficie')){
                $dataad['superficie'] = $this->input->post('superficie');
            }
            if($this->input->post('preciom')){
                $dataad['preciom'] = $this->input->post('preciom');
            }
            if($this->input->post('predial')){
                $dataad['predial'] =$this->input->post('predial');
            }
            if($this->input->post('por_penalizacion')){
                $dataad['por_penalizacion'] = $this->input->post('por_penalizacion');
            }
            if($this->input->post('penalizacion')){
                $dataad['penalizacion'] = $this->input->post('penalizacion');
            }
            if($this->input->post('importe_aportado')){
                $dataad['importe_aportado'] = $this->input->post('importe_aportado');
            }
            if($this->input->post('mantenimiento')){
                $dataad['mantenimiento'] = $this->input->post('mantenimiento');
            }
            if ($this->input->post('proceso_motivo') == '1') { /** FECHA: 20-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                $dataad['motivo'] =  $this->input->post('tipo_motivo'); /** FECHA: 20-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                $dataad['detalle_motivo'] =  $this->input->post('detalle_motivo'); /** FECHA: 20-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
            }else{
                $dataad['motivo'] =  $this->input->post('tipo_motivo'); /** FECHA: 20-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                $dataad['detalle_motivo'] =  NULL; /** FECHA: 20-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
            }

            $dataad['idsolicitud'] = $resultado;
            //DELETE
            // $this->db->query( "DELETE FROM motivo_sol_dev  WHERE idsolicitud = '$resultado'");
            // INSERT
            $this->M_Devolucion_Traspaso->mas_pv($dataad);
            //UPDATE PARA referencia
            $this->db->update("solpagos", array("ref_bancaria" => "Solicitud" . $resultado), "idsolicitud = '" . $resultado . "'");
            //CARGA INFORMACION EN EL LOG PARA EL HISTORIAL
            log_sistema_dev_trasp($this->session->userdata("inicio_sesion")['id'], $resultado, "SE HA CREADO UNA NUEVA SOLICITUD", 1);

            // if( $this->input->post("operacion") !== "TRASPASO" && $this->input->post("operacion") !== "TRASPASO OOAM" && $rev_cuent_prov->num_rows() > 0 ){
            //     //Metodo para buscar si el proveedor esta vinculado a otra sol activa de no ser asi desactivar prov pasar a estatus 3
            //     foreach( $rev_cuent_prov->result() as  $prov ){
            //         $this->baja_prov($prov->idproveedor,$prov->estatus);                              
            //     }
            // }
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $resultado = array("resultado" => FALSE, "msj" => "Ha ocurrido un error en el proceso");
            } else {
                $this->db->trans_commit();
                $resultado = array("resultado" => TRUE, "msj" => "Se ha registrado la solicitud correctamente", "clientes" => $this->Lista_dinamicas->obtenerProveedoresCliente()->result_array());
            }
        }
        echo json_encode($resultado);
    }

    function baja_prov($proveedor,$status){
        // FECHA : 01-JULIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
           $prov = $this->db->query("SELECT * FROM solpagos WHERE idetapa NOT IN (0,30,11) AND idProveedor = ? ",[$proveedor]);
            if($prov->num_rows() == 0 && $status ==2 ){
            //Update 
            /**
             * FECHA : 01-JULIO-2025 | @author Efrain Martinez progamador.analista38@ciudadmaderas.com | SE AGREGA EL ID DEL USUARIO QUE REALIZO LA ACTUALIZACION
             */
            $this->db->query("UPDATE proveedores SET estatus = 3, idupdate = ?, fecha_update = now() WHERE idproveedor = ?",[$this->session->userdata('inicio_sesion')['id'],$proveedor]);
            }
    }

    

    public function editar_solicitud_contabilidad(){
        $resultado = array("resultado" => TRUE);
        // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
        if ((isset($_POST) && !empty($_POST))) {
            $this->db->trans_begin();

            //EDITAR O ACTUALIZAR UN PROVEEDOR SELECCIONADO
            if( $this->input->post("operacion") === "TRASPASO" || $this->input->post("operacion") === "TRASPASO OOAM" ){
                $idproveedor = $this->input->post("idproveedor_ad");
            }else{
                $nombre_cliente = limpiar_dato($this->input->post("nombreproveedor"));
                if( $this->input->post('forma_pago') == 'MAN' ){
                    $cuenta = $this->input->post("cuenta_extr");
                    $tipo_cuenta = "40";

                    $nbanco = limpiar_dato($this->input->post("banco_ext"));
                    $aba_swift = $this->input->post("aba");

                    $revisar_banco = $this->db->query("SELECT * FROM bancos WHERE nombre = ? AND clvbanco = ? AND estatus = 2", [ $nbanco, $aba_swift ]);
                    if( $revisar_banco->num_rows() > 0 ){
                        $banco = $revisar_banco->row()->idbanco;
                    }else{
                        $this->db->insert("bancos", [
                            "nombre" => $nbanco,
                            "clvbanco" => $aba_swift,
                            "estatus" => 2
                        ]);
                        $banco = $this->db->insert_id();
                    }
                }else{
                    $banco = $this->input->post("idbanco") ? $this->input->post("idbanco") : 6;
                    $cuenta = $this->input->post("cuenta") ? $this->input->post("cuenta") : 0;
                    $tipo_cuenta = $this->input->post("tipocta") ? $this->input->post("tipocta") : 1;
                }
                $idproveedor=0;
                $rev_cuent_prov = $this->db->query("SELECT * FROM proveedores WHERE nombre = ? ORDER BY cuenta DESC", [ $nombre_cliente ]);

                if( $rev_cuent_prov->num_rows() > 0 ){
                    if( in_array( $this->input->post('forma_pago'), [ 'TEA', 'MAN' ] ) ){
                        $array_prov = [];
                        foreach( $rev_cuent_prov->result() as  $prov ){
                            if($prov->cuenta == $cuenta && $prov->idbanco == $banco && $prov->tipocta == $tipo_cuenta ) {
                                $array_prov["idproveedor"] = $prov->idproveedor;
                                $array_prov["estatus"] =  $prov->estatus;
                            }
                        }

                        if(empty( $array_prov)){

                            foreach( $rev_cuent_prov->result() as  $prov ){
                                if($prov->cuenta == 0){
                                    $array_prov["idproveedor"] = $prov->idproveedor;
                                    $array_prov["estatus"] = $prov->estatus;
                                }
                            }

                            if(!empty($array_prov)){
                                //actualiza registro si tipo cuenta == 0
                                $idproveedor = $array_prov["idproveedor"];
                                /**
                                 * FECHA : 01-JULIO-2025 | @author Efrain Martinez progamador.analista38@ciudadmaderas.com | SE AGREGA EL ID DEL USUARIO QUE REALIZO LA ACTUALIZACION
                                 */
                                $this->db->query("UPDATE proveedores SET cuenta = ?, idbanco = ?, tipocta = ?, idupdate = ?, fecha_update = ?, idusuario = ? WHERE idproveedor = ? ",[$cuenta,$banco,$tipo_cuenta,$this->session->userdata("inicio_sesion")['id'],date("Y-m-d H:i:s"),$this->session->userdata("inicio_sesion")['id'],$idproveedor]);
                                if($array_prov["estatus"] == 3)
                                $this->M_Devolucion_Traspaso->reactivar_prov($idproveedor);
                            }
                            
                        }else{
                            $idproveedor = $array_prov["idproveedor"];
                            if($array_prov["estatus"] == 3)
                            $this->M_Devolucion_Traspaso->reactivar_prov( $array_prov["idproveedor"]);
                        }

                    }else{
                        $idproveedor = $rev_cuent_prov->row()->idproveedor;
                        if($rev_cuent_prov->row()->estatus == 3)
                            $this->M_Devolucion_Traspaso->reactivar_prov($rev_cuent_prov->row()->idproveedor);
                    }
                }


                if($idproveedor == 0){
                    $this->db->insert("proveedores", [
                        "nombre" => $nombre_cliente,
                        "alias" => substr(str_replace(" ", "", $nombre_cliente), 0, 10).str_pad(rand(0,999), 3, "0", STR_PAD_LEFT),
                        "idbanco" => $banco ,
                        "tipocta" => $tipo_cuenta,
                        "cuenta" => $cuenta,
                        "rfc" => "CLIENTE",
                        "email" => "CLIENTE@CLIENTE.COM",
                        "sucursal" => 1,
                        "fecadd" => date("Y-m-d H:i:s"),
                        "idby" => $this->session->userdata("inicio_sesion")['id'],
                        "estatus" => 2
                    ]);
                    $idproveedor = $this->db->insert_id();
                }
                /*******************************************************************/
            }

            /** INICIO FECHA: 29-ABRIL-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
            $condominio = $_POST["lote"] ? $_POST["lote"] : 'SIN ASIGNAR';
            /** FIN FECHA: 29-ABRIL-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/

            $data = array(
                "proyecto" => ($this->input->post('proyecto')),
                "homoclave" => limpiar_dato($this->input->post('homoclave')),
                "etapa" => limpiar_dato($this->input->post('etapa')),
                "condominio" => $condominio,
                "folio" => "NA",
                "idEmpresa" => $this->input->post('empresa'),
                "idResponsable" => $this->session->userdata("inicio_sesion")['da'],
                "idusuario" => (in_array( $this->session->userdata("inicio_sesion")['rol'], ['SPV'] )) ? $this->input->post('idautor')  : $this->session->userdata("inicio_sesion")['id']  , //MAR
                "nomdepto" => ($this->input->post('operacion') ? $this->input->post('operacion') : $this->session->userdata("inicio_sesion")['depto']),
                "idProveedor" => $idproveedor,
                "cantidad" => $this->input->post('proceso') == 30 ? preg_replace("/[^0-9.]/", "", $this->input->post('montoTotal')) : preg_replace("/[^0-9.]/", "", $this->input->post('total')),
                "moneda" => $this->input->post('moneda'),
                "justificacion" => $this->input->post('solobs'),
                "fecelab" => $this->input->post('fecha') ? $this->input->post('fecha') : $this->input->post('fecha_Inicio'),
                "metoPago" => $this->input->post('forma_pago'),
                "idproceso" => $this->input->post('proceso'),
                "requisicion" => $this->input->post('titular'),
            );

            /** INICIO MEJORA FECHA: 08-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/

                // Variables de entrada
                $idsolicitud = $this->input->post("idsolicitud");
                $idDesarrollo = !empty($_POST["inputManualDesarrollo"]) ? $_POST["inputManualDesarrollo"] : $_POST["desarrollo"];
                $isDesarrolloManual = $_POST["isDesarrolloManual"];

                $idCondominio = !empty($_POST["inputManualCondominio"]) ? $_POST["inputManualCondominio"] : $_POST["condominios"];
                $isCondominioManual = $_POST["isCondominioManual"];

                $idLote = !empty($_POST["inputManualLote"]) ? $_POST["inputManualLote"] : $_POST["idLoteSeleccionado"];
                $isLoteManual = $_POST["isLoteManual"];

                $referencia = !empty($_POST['referencia']) ? $_POST['referencia'] : 'NA';
                $isReferenciaManual = $_POST['isReferenciaManual'];

                // Verificar si ya existe la solicitud
                $consultaExistente = $this->db->query("SELECT idsolicitud FROM solicitud_drl 
                                                    WHERE idsolicitud = ?", 
                                                    array($idsolicitud));

                // Si existe, hacer un update; si no, insertar un nuevo registro
                if ($consultaExistente->num_rows() > 0) {
                    // Hacer un update usando el ID existente
                    $this->db->query("UPDATE solicitud_drl 
                                        SET iddesarrollo = ?, isDesarrolloManual = ?
                                            , idcondominio = ?, isCondominioManual = ?
                                            , idlote = ?, isLoteManual = ?
                                            , referencia = ?, isReferenciaManual = ?
                                        WHERE idsolicitud = ?
                                    ", array( $idDesarrollo, $isDesarrolloManual
                                            , $idCondominio , $isCondominioManual
                                            , $idLote , $isLoteManual
                                            , $referencia, $isReferenciaManual
                                            , $idsolicitud 
                                        )
                                );

                }
            /** FIN MEJORA FECHA: 08-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/

            if($this->input->post("fecha_final"))
                $data["fecha_fin"] = $this->input->post("fecha_final");
            if($this->input->post("programado"))
                $data["programado"] = $this->input->post("programado");
            if( $this->input->post("frmnewsol_cuenta_contable") )
                $data["homoclave"] = $this->input->post("frmnewsol_cuenta_contable");

            if( $this->input->post("frmnewsol_cuenta_orden") )
                $data["orden_compra"] = $this->input->post("frmnewsol_cuenta_orden");
            // HAY QUE DESCOMENTAR ESTO--->
            /*
            * INICIO FECHA : 26-MAYO-2025 | @uthor Efrain Martinez programador.analista38@ciudadmaderas.com
            * Se mueve la insercion de la tabla de logs antes de que se realizae el update de solpagos ya que se necesita verificar saber el idusuario de la persona que esta realizando el update.
            */
            // log_sistema_dev_trasp($this->session->userdata("inicio_sesion")['id'], $this->input->post("idsolicitud"), "SE HA EDITADO LA SOLICITUD", 1);
            $this->Solicitudes_solicitante->update_solicitud($data, $this->input->post("idsolicitud"));

            $registroParcialidades = $this->M_Devolucion_Traspaso->obtenerRegistroParcialidades($this->input->post("idsolicitud"))->result_array();

            if($this->input->post('proceso') == 30){
                $parcialidades = array(
                    "tipoParcialidad" => $this->input->post("tipoResicion"),
                    "montoParcialidad" => $this->input->post("montoParcialidad"),
                    "numeroParcialidades" => $this->input->post("numeroPagos")
                );
                if(count($registroParcialidades) == 0){
                    $parcialidades["idsolicitud"] = $this->input->post("idsolicitud");
                    $this->M_Devolucion_Traspaso->guardarSolicitudesParcialidades($parcialidades);
                }
                else $this->M_Devolucion_Traspaso->actualizarRegistroParcialidades($parcialidades, $this->input->post("idsolicitud"));
            }
            if($this->input->post('proceso') != 30 && count($registroParcialidades)>0) $this->M_Devolucion_Traspaso->eliminacionRegistroParcialidades($this->input->post("idsolicitud"));
/**
             * INICIO | FECHA : 26-MAYO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com 
             * SE MODIFICÓ LA FORMA EN LA QUE SE LLENA EL ARREGLO PARA LA ACTUALIZACION DE LA TABLA motivo_sol_dev CON LA FINALIDAD DE QUE SE QUEDE REGISTRADO CUANDO EL USUARIO REALIZE ALGUNA MODIFICACION EN CUALQUIERA DE ESTOS CAMPOS.
             * ADEMAS SE VALIDAN TAMBIEN LOS CAMPOS DE MOTIVO Y DETALLE MOTIVO EN CASO DE QUE ESOTS CAMPOS SEAN LLENADOS.
            */
            $dataad = array(
                            "costo_lote" => $this->input->post('costo_lote'),
                            "superficie" => $this->input->post('superficie'),
                            "preciom" => $this->input->post('preciom'),
                            "predial" => $this->input->post('predial'),
                            "por_penalizacion" => $this->input->post('por_penalizacion'),
                            "penalizacion" => $this->input->post('penalizacion'),
                            "importe_aportado" => $this->input->post('importe_aportado'),
                            "mantenimiento" => $this->input->post('mantenimiento'),
                        );

                        
            if ($this->input->post('proceso_motivo') == '1') { /** FECHA: 25-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                $dataad['motivo'] =  $this->input->post('tipo_motivo'); /** FECHA: 25-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                $dataad['detalle_motivo'] =  $this->input->post('detalle_motivo'); /** FECHA: 25-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
            }else{
                $dataad['motivo'] = null;
                $dataad['detalle_motivo'] = null; 
            }

            // $dataad['idsolicitud'] = $this->input->post("idsolicitud");
            // $this->db->query( "DELETE FROM motivo_sol_dev  WHERE idsolicitud = '".$this->input->post("idsolicitud")."'");
            
            $this->M_Devolucion_Traspaso->update_mas_pv($dataad,$this->input->post("idsolicitud"));
             /**
             * FIN | FECHA 26-MAYO-2025 @author Efrain Martinez programador.analista38@ciudadmaderas.com
             */
            //CARGA INFORMACION EN EL LOG PARA EL HISTORIAL
            log_sistema_dev_trasp($this->session->userdata("inicio_sesion")['id'], $this->input->post("idsolicitud"), "SE HA EDITADO LA SOLICITUD", 1);

            // if( $this->input->post("operacion") !== "TRASPASO" && $this->input->post("operacion") !== "TRASPASO OOAM" && $rev_cuent_prov->num_rows() > 0){
            //     //Metodo para buscar si el proveedor esta vinculado a otra sol activa de no ser asi desactivar prov pasar a estatus 3
            //     foreach( $rev_cuent_prov->result() as  $prov ){
            //         $this->baja_prov($prov->idproveedor,$prov->estatus);                              
            //     }
            // }

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $resultado = array("resultado" => FALSE, "msj" => "Ha ocurrido un error en el proceso");
            } else {
                $this->db->trans_commit();
                $resultado = array("resultado" => TRUE, "msj" => "SE HA ACTUALIZADO EL REGISTRO CORRECTAMENTE", "clientes" => $this->Lista_dinamicas->obtenerProveedoresCliente()->result_array());
            }
        }

        echo json_encode($resultado);
    }

    public function informacion_solicitud(){
        $respuesta = array("resultado" => FALSE);
        if ($this->input->post("idsolicitud")) {
            $respuesta['info_solicitud'] = $this->M_Devolucion_Traspaso->getSolicitudadm($this->input->post("idsolicitud"))->result_array();
            $respuesta['resultado'] = true;
        }
        echo json_encode($respuesta);
    }

    public function informacion_solicitud_adm(){
        $respuesta = array("resultado" => FALSE);
        if ($this->input->post("idsolicitud")) {

            $respuesta['info_solicitud'] = $this->M_Devolucion_Traspaso->getSolicitudadm($this->input->post("idsolicitud"))->result_array();
            $respuesta["data"] = FALSE;

            if( $respuesta['info_solicitud'][0]["prioridad"] ){
                $orden = $this->M_Devolucion_Traspaso->proceso_etapas( $this->input->post("idsolicitud") );
                if ($orden->num_rows() > 0) {
                    $respuesta["data"] = array();
                    foreach ( $orden->result() as  $value ) {
                        $respuesta["data"][] = array(
                            "nombre" => $value->etapa_regresar,
                            "solicitud" => $value->idsolicitud,
                            "idrol" => $value->idrol,
                            "orden" => $value->orden,
                            "idetapa" => $value->idetapa
                        );
                    }
                }
            }

            $respuesta['resultado'] = true;
        }
        echo json_encode($respuesta);
    }

    public function borrar_solicitud()
    {
        $respuesta = array("resultado" => FALSE);

        if ($this->input->post("idsolicitud")) {
            $respuesta['resultado'] = $this->Solicitudes_solicitante->borrar_solicitud($this->input->post("idsolicitud"))["respuesta"];
            log_sistema($this->session->userdata("inicio_sesion")['id'], $this->input->post("idsolicitud"), "HA ELIMINADO LA SOLICITUD DEL SISTEMA ¬¬");
        }

        echo json_encode($respuesta);
    }

    public function enviar_a_dg()
    {
        $respuesta = array("resultado" => FALSE);

        if ($this->input->post("idsolicitud")) {
            $respuesta['resultado'] = $this->Solicitudes_solicitante->enviar_a_dg($this->input->post("idsolicitud"), $this->input->post("departamento"));
            log_sistema($this->session->userdata("inicio_sesion")['id'], $this->input->post("idsolicitud"), "SE HA ENVIADO PARA AUTORIZACIÓN");
        }

        echo json_encode($respuesta);
    }

    public function reenviar_factura()
    {
        $respuesta = array("resultado" => FALSE);

        if ($this->input->post("idsolicitud")) {
            $respuesta['resultado'] = $this->Solicitudes_solicitante->reenviar_factura($this->input->post("idsolicitud"));
            log_sistema($this->session->userdata("inicio_sesion")['id'], $this->input->post("idsolicitud"), "SE HA CORREGIDO Y ENVIADO A OTRA ÁREA");
        }

        echo json_encode($respuesta);
    }

    public function aprobada_da()
    {
        $respuesta = array("resultado" => FALSE, "mensaje" => "NO FUE POSIBLE PROCESAR SU SOLICITUD");

        if ($this->input->post("idsolicitud")) {
            $respuesta['resultado'] = $this->Solicitudes_solicitante->aprobada_da($this->input->post("idsolicitud"), $this->input->post("fecelab"), $this->input->post("caja_chica"), $this->input->post("departamento"));
            if ($respuesta['resultado'])
                log_sistema($this->session->userdata("inicio_sesion")['id'], $this->input->post("idsolicitud"), "EL DIRECTOR HA APROBADO LA SOLICITUD");
            else
                $respuesta['mensaje'] = 'La fecha de facturación esta fuera del rango permitido. No es posible enviarla.';
        }

        echo json_encode($respuesta);
    }

    public function rechazada_da()
    {
        $respuesta = array("resultado" => FALSE);

        if ($this->input->post("idsolicitud")) {//
            $respuesta['resultado'] = $this->Solicitudes_solicitante->rechazada_da($this->input->post("idsolicitud"));
            log_sistema($this->session->userdata("inicio_sesion")['id'], $this->input->post("idsolicitud"), "SE HA RECHAZADO LA SOLICITUD EN EL DEPARTAMENTO");
        }

        echo json_encode($respuesta);
    }

    public function congelar_solicitud()
    {
        $respuesta = array("resultado" => FALSE);

        if ($this->input->post("idsolicitud")) {
            $respuesta['resultado'] = $this->Solicitudes_solicitante->congelar_solicitud($this->input->post("idsolicitud"));
            log_sistema($this->session->userdata("inicio_sesion")['id'], $this->input->post("idsolicitud"), "HAN PAUSADO EL FLUJO DE LA SOLICITUD");
        }

        echo json_encode($respuesta);
    }

    public function liberar_solicitud()
    {
        $respuesta = array("resultado" => FALSE);

        if ($this->input->post("idsolicitud")) {
            $respuesta['resultado'] = $this->Solicitudes_solicitante->liberar_solicitud($this->input->post("idsolicitud"));
            log_sistema($this->session->userdata("inicio_sesion")['id'], $this->input->post("idsolicitud"), "SE HA LIBERADO LA SOLICITUD, PARA SER PROCESADA");
        }

        echo json_encode($respuesta);
    }

    /**NO SEPARAR ESTA FUNCION CON LA DE ARRIBA ESTAS HACEN EL TRABAJO DE COLOCAR LAS OPCIONES*/
    /**
     * @uthor Efrain Martinez Muñoz | Fecha: 16/04/2025
     * Se recibe las variables enviadas en la función anterior.
     */
    public function opciones_autorizaciones($idsolicitud, $estatus, $autor, $visto, $prioridad, $dg, $departamento, $tipo_prov,$estatusLote, $idproceso, $docs, $regre, $esUsuarioPermitido, $esUsuarioSupervisor)
    {

        // var_dump($estatus);

        $opciones = '<div class="btn-group-vertical">';
        //post venta
        //Se agrego margin-buttom a los botones con la fianlidad de que se muestre una separación entre ellos.
        /** FECHA: 17-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
        $opciones .= '<button style="margin:2px;" type="button" class="btn btn-sm btn-primary consultar_modal notification" value="' . $idsolicitud . '" data-value="DEV" title="VER SOLICITUD"><i class="fas fa-eye"></i>' . ($visto == 0 ? '</i><span class="badge">!</span>' : '') . '</button> ';

        // $opciones .= '<button style="margin:2px;" type="button" class="btn btn-sm btn-primary consultar_modal notification" value="' . $idsolicitud . '" data-value="DEV" title="VER SOLICITUD"><i class="fas fa-eye"></i>' . ($visto == 0 ? '</i><span class="badge">!</span>' : '') . '</button> ';

        if ( $this->session->userdata("inicio_sesion")['im'] == 5 && $esUsuarioSupervisor ) {
            if ( in_array( $this->session->userdata("inicio_sesion")['rol'], ['GAD','AD', 'AOO'] ) && $autor != $this->session->userdata("inicio_sesion")['id']) {
                $opciones .= '<button style="margin:2px;" type="button" class="btn btn-sm btn-success avanzar_contable" value="' . $idsolicitud . '" title="ENVIAR SOLICITUD"><i class="fas fa-share-square"></i></button> ';
            }else{
                if ( in_array($prioridad, [1, 2]) ) {
                    $opciones .= '<button style="margin:2px;" type="button" class="btn btn-sm btn-success avanzar_lista" value="' . $idsolicitud . '" title="ENVIAR SOLICITUD"><i class="fas fa-share-square"></i></button> ';
                } else if ($prioridad == 0 || $prioridad == NULL ) {
                    $opciones .= '<button style="margin:2px;" type="button" class="btn btn-sm btn-success avanzar" value="' . $idsolicitud . '" title="ENVIAR SOLICITUD"><i class="fas fa-share-square"></i></button> ';
                }
            }

            if( in_array( $estatus, [ 1 ] ) ){
                $opciones .= '<input type="hidden" id="idautor" value="'.$autor.'" />';
                $opciones .= '<button style="margin:2px;" type="button" class="btn btn-sm btn-warning editar_factura" value="' . $idsolicitud . '" title="EDITAR SOLICITUD"><i class="fas fa-pencil-alt"></i></button> ';
                $opciones .= '<button  style="margin:2px;" type="button" class="btn btn-sm btn-danger borrar_solicitud" value="' . $idsolicitud . '" title="BORRAR SOLICITUD"><i class="fas fa-trash"></i></button>';
            }
            if ($regre != 1 && !in_array( $prioridad, [ 2 ] ) ) {
                $opciones .= '<button style="margin:2px;" type="button" class="btn btn-sm btn-danger cancelar_sol" value="' . $idsolicitud . '" title="CANCELAR SOL"><i class="fas fa-undo-alt"></i></button> ';
            }
        }elseif($esUsuarioPermitido && ($this->session->userdata("inicio_sesion")['im'] != 5 && $estatus > 1)){
            if ( in_array( $this->session->userdata("inicio_sesion")['rol'], ['GAD','AD', 'AOO'] ) && $autor != $this->session->userdata("inicio_sesion")['id']) {
                $opciones .= '<button style="margin:2px;" type="button" class="btn btn-sm btn-success avanzar_contable" value="' . $idsolicitud . '" title="ENVIAR SOLICITUD"><i class="fas fa-share-square"></i></button> ';
            }else{
                if ( in_array($prioridad, [1, 2]) && $estatus > 1 ) {
                    $opciones .= '<button style="margin:2px;" type="button" class="btn btn-sm btn-success avanzar_lista" value="' . $idsolicitud . '" title="ENVIAR SOLICITUD"><i class="fas fa-share-square"></i></button> ';
                } else if (($prioridad == 0 || $prioridad == NULL) && $estatus > 1 ) {
                    $opciones .= '<button style="margin:2px;" type="button" class="btn btn-sm btn-success avanzar" value="' . $idsolicitud . '" title="ENVIAR SOLICITUD"><i class="fas fa-share-square"></i></button> ';
                }
            }
            if ($regre != 1 && !in_array( $prioridad, [ 2 ] ) ) {
                $opciones .= '<button style="margin:2px;" type="button" class="btn btn-sm btn-danger cancelar_sol" value="' . $idsolicitud . '" title="CANCELAR SOL"><i class="fas fa-undo-alt"></i></button> ';
            }
        }else{
            if ( in_array( $this->session->userdata("inicio_sesion")['rol'], ['GAD','AD', 'AOO'] ) && $autor != $this->session->userdata("inicio_sesion")['id']) {
                $opciones .= '<button style="margin:2px;" type="button" class="btn btn-sm btn-success avanzar_contable" value="' . $idsolicitud . '" title="ENVIAR SOLICITUD"><i class="fas fa-share-square"></i></button> ';
            /*
            } else if ($this->session->userdata("inicio_sesion")['rol'] == 'AD' && $prioridad == 1) {
                $opciones .= '<button style="margin:2px;" type="button" class="btn btn-sm btn-success avanzar_contable_lista" value="' . $idsolicitud . '" title="ENVIAR SOLICITUD"><i class="fas fa-share-square"></i></button> ';
            */
            } else {
                if ( in_array($prioridad, [1, 2]) ) {
                    $opciones .= '<button style="margin:2px;" type="button" class="btn btn-sm btn-success avanzar_lista" value="' . $idsolicitud . '" title="ENVIAR SOLICITUD"><i class="fas fa-share-square"></i></button>';
                } else if (($prioridad == 0 || $prioridad == NULL) ) {
                   /**
                     * @uthor Efrain Martinez Muñoz | Fecha: 16/04/2025
                     * En esta validación se verifica si el usuario logueado es de rol PV y estatusLote es diferente de NULL y diferente de ' ' o si el rol del usuario es diferente de PV 
                     * o el idproceso de la solicitud se encuentra dentro de la siguiente colección de datos [6,15,16,17,18,19,20] a entonces se muestra el boton de avanzar solictud 
                     */
                    if((in_array( $this->session->userdata("inicio_sesion")['rol'], ['PV', 'PVM']) && ($estatusLote != NULL || $estatusLote != '')) || (!in_array( $this->session->userdata("inicio_sesion")['rol'], ['PV', 'PVM'])) || (in_array($idproceso, [6,15,16,17,18,19,20]))){
                    $opciones .= '<button style="margin:2px;" type="button" class="btn btn-sm btn-success avanzar" value="' . $idsolicitud . '" title="ENVIAR SOLICITUD"><i class="fas fa-share-square"></i></button> ';
                    }
                }
            }

            //MAR
            if ($autor  == $this->session->userdata("inicio_sesion")['id'] || in_array( $this->session->userdata("inicio_sesion")['rol'], ['SPV'] )) {

                $opciones .= '<input type="hidden" id="idautor" value="'.$autor.'" />';
                $opciones .= '<button style="margin:2px;" type="button" class="btn btn-sm btn-warning editar_factura" value="' . $idsolicitud . '" title="EDITAR SOLICITUD"><i class="fas fa-pencil-alt"></i></button> ';

                /**
                 * INICIO
                 * Efrain Martinez Muñoz | Fecha: 16/04/2025
                 * Se valida si el usuario logueado es el author de la solicitud y si estaus lote es NULL o '' y si el rol del usuario logueado es igual a PV y si el id proceso de la solicitud 
                 * no se encuentra dentro de la siguiente colección de datos [6,15,16,17,18,19,20] se muestra el botón de cargar imagen para que el usuario
                 * pueda ingresar el estatus del lote de la solicitud.
                 */
                if ($autor  == $this->session->userdata("inicio_sesion")['id'] && in_array( $this->session->userdata("inicio_sesion")['rol'], ['PV', 'PVM']) && ($estatusLote == NULL || $estatusLote == '') && (!in_array($idproceso, [6,15,16,17,18,19,20]))) {
                    $opciones .= '<button style="margin:2px;" type="button" class="btn btn-sm btn-success cargar_imagen" value="' . $idsolicitud . '" title="ESTATUS DEL LOTE"><i class="fas fa-regular fa-image"></i></button> ';
                }
                /**
                 * FIN FECHA : 16-ABRIL-2025 | @author Efrain Martinez Muñoz <programador.analista38@ciudadmaderas.com>
                 */
                if( in_array( $estatus, [ 1 ] ) )
                    $opciones .= '<button  style="margin:2px;" type="button" class="btn btn-sm btn-danger borrar_solicitud" value="' . $idsolicitud . '" title="BORRAR SOLICITUD"><i class="fas fa-trash"></i></button>';
        
            }

            if (($regre != 1 && !in_array( $prioridad, [ 2 ] ))) {
                $opciones .= '<button style="margin:2px;" type="button" class="btn btn-sm btn-danger cancelar_sol" value="' . $idsolicitud . '" title="CANCELAR SOL"><i class="fas fa-undo-alt"></i></button> ';
            }
        }
        return $opciones . "</div>";
    }

    function agregar_documentos(){
        // print_r($_POST);
        // $app = new DropboxApp("5plsp8ptz8vqf69", "9z1gmsrap71gnyh", "G4D6KrnTp6UAAAAAAAAAAZFl3KSfZocshcbv_gXhZOJad8LoGploWBfe2dquVFRv");
        // $dropbox = new Dropbox($app);
        $app = new DropboxApp("9vkalyc8qnkdyu4", "9z1gmsrap71gnyh", "DHBVzF2_RoQAAAAAAAAAAQTFz_Gj51gd1_aezVei-CJ4qHIVMbHZqFkoAtxObVpK");
        $dropbox = new Dropbox($app);
        $respuesta = ["resultado" => false];
        $idsolicitud = $this->input->post('idsolicitud');
        $documentos = $this->M_Devolucion_Traspaso->get_info_docs($idsolicitud);

        if( $this->M_Devolucion_Traspaso->validar_solpagos_estatus( $this->session->userdata("inicio_sesion")['rol'], $idsolicitud )->num_rows() > 0 ){ 
            if( $documentos->num_rows() > 0) {
                $this->db->trans_begin();
                foreach ($documentos->result() as $row) {
                    $id = $row->iddocumentos;
                    $nombre_doc = $row->ndocumento;
                    if (isset($_FILES[$id]['tmp_name']) && $_FILES[$id]['tmp_name'] != "") {

                        /*
                        $idmax = $this->M_Devolucion_Traspaso->ultimo_regis();
                        if ($idmax->num_rows() > 0) {
                            $iddoc = $idmax->row()->id + 1;
                        } else {
                            $iddoc = 1;
                        }
                        */
                        $tempfile = $_FILES[$id]['tmp_name'];
                        $ext = explode(".", $_FILES[$id]['name']);
                        $ext = end($ext);
                        $nombredropbox = "/DEVOLUCIONES_TRASPASOS/$idsolicitud/" . date('Y') . "_" . date('m') . '_' . date('d') . "_" . date('H') . "_" . date('i') . "_" . date('s') . "_" . $idsolicitud . "_" . str_replace([ ' ', '.','/' ], [ '_', '_', '_' ],$row->ndocumento . "_" . $row->nombre) . "." . $ext;
                        try {

                            $file = $dropbox->simpleUpload($tempfile, $nombredropbox, ['autorename' => true]);
                            $this->M_Devolucion_Traspaso->insert_documento($id, $idsolicitud, $nombredropbox);
                            $tipo_mov = 'SE HA AGREGADO ' . $nombre_doc;
                            log_sistema($this->session->userdata("inicio_sesion")['id'], $idsolicitud, $tipo_mov);
                            $respuesta = ["resultado" => true];

                        } catch (Exception $e) {
                            $respuesta["mensaje"] = $e;
                            break;
                        }

                    }
                }
                if ( $respuesta["resultado"] === FALSE || $this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                } else {
                    $this->db->trans_commit();
                }
            }
        }else{
            $respuesta = ["resultado" => false, "msj" => "La solicitud ya sido avanzada, no es posible adjuntar mas documentos"];
        }

        echo json_encode($respuesta);
    }
    /**
     * INICIO 
     * @uthor Efrain Martinez Muñoz | Fecha : 16-04-2025 <programador.analista38@ciudadmaderas.com>
     * Se actualizo la función descargar_docs con la finalidad de descargar los archivos y guardarlos en una carpeta temporal que se eliminara al cerrar el modal
     * 
     */
    public function descargar_docs(){
        $idsolicitud = $this->input->post('idsolicitud');
        $app = new DropboxApp("9vkalyc8qnkdyu4", "9z1gmsrap71gnyh", "DHBVzF2_RoQAAAAAAAAAAQTFz_Gj51gd1_aezVei-CJ4qHIVMbHZqFkoAtxObVpK");
        $dropbox = new Dropbox($app);
        $docs = $this->M_Devolucion_Traspaso->ruta_doc_dropbox($idsolicitud);
        $json["data"] = array();
        if ($docs->num_rows() > 0) {
            foreach ($docs->result() as $row) {
                $rutaBase = $row->ruta;
                /**
                 * INICIO FECHA: 07-mayo-2025 ||@author Efrain Martinez Muñoz <programador.analista38@ciudadmaderas.com>
                 * Mejora que permite generar una ruta unica de los archivos cargados de cada una de las solicitudes y poder visualizarlos antes de descargarlos. 
                 */

                // Se crean variables que permiten validar que las rutas ya se encuentren en la base de datos ya no las genera.
                $idDocumentosSolicitud = $row->iddocumentos_solicitud;
                $rutaDescarga = $row-> rutaDescarga;
                $rutaVista = $row -> rutaVista;
                $links = $dropbox->getTemporaryLink($rutaBase);
                $rutaDescargaMasiva = $links->getLink();
                //Si las rutas ya existe en la base de datos entra en esta validación y regresa los datos que se encuentran en la base de datos. 
                if($rutaVista && $rutaDescarga){
                    $json["data"][] = array(
                        "iddocumentos_solicitud" => $row->iddocumentos_solicitud,
                        "iddocumento" => $row->iddocumento,
                        "documento" => $row->ndocumento,
                        "ruta" => $rutaDescarga,
                        "rutaLocal" => $rutaVista,
                        "rutaBase" => $rutaBase,
                        "idsolicitud" => $idsolicitud,
                        "rutaMasiva" => $rutaDescargaMasiva
                    );

                }else
                // En caso de que no existan las rutas en la base de datos las genera y las guarda para que la siguiente vez que consulten la solictud ya no las genere. 
                {
                    try {

                        $url = '';
                        // Intentar crear un Shared Link (en caso de que sea la primera vez).
                        $sharedLink = $dropbox->postToAPI("/sharing/create_shared_link_with_settings", [
                            "path" => $rutaBase
                        ]);
                        $body = $sharedLink->getDecodedBody();
                        $url = $body['url'];
                        
                    }catch (\Kunnu\Dropbox\Exceptions\DropboxClientException $e) { // Si ya existe uno, entonces te manda un error
                        // Si ya existe, sacar el URL desde el error
                        $body = json_decode($e->getMessage(), true);
                        if (isset($body['error']['shared_link_already_exists']['metadata']['url'])) {
                            $url = $body['error']['shared_link_already_exists']['metadata']['url'];
                        } else {
                            $respuesta['respuestas'] = false;
                        }
                        
                    }
    
                    // Cambiar dl=0 a raw=1 para mostrar la imagen directamente
                    $urlMostrar = str_replace("dl=0", "raw=1", $url);
                    $urlDescarga = str_replace("dl=0", "dl=1", $url);

                    //Se guardan las rutas generadas en la base de datos.
                    $datos = array(
                        'rutaDescarga' => $urlDescarga,
                        'rutaVista' => $urlMostrar
                    );
                    $this->db->where('iddocumentos_solicitud', $idDocumentosSolicitud);
                    $this->db->update ('documentos_solicitud', $datos);

                    // Se agrega la ruta del archivo temporal a la respuesta.
                    $json["data"][] = array(
                        "iddocumentos_solicitud" => $row->iddocumentos_solicitud,
                        "iddocumento" => $row->iddocumento,
                        "documento" => $row->ndocumento,
                        "ruta" => $urlDescarga,
                        "rutaLocal" => $urlMostrar,
                        "rutaBase" => $rutaBase,
                        "idsolicitud" => $idsolicitud,
                        "rutaMasiva" => $rutaDescargaMasiva
                    );
                }
                /**
                 * FIN FECHA: 07-MAYO-2025 || @author Efrain Martinez Muñoz <programador.analista38@ciudadmaderas.com>
                 */
            }
        }

        // $respuesta['documentos'] = $data;
        echo json_encode($json["data"]);
    }
    /**
     * FIN 
     * @author Efrain Martinez Muñoz <programador.analista38@ciudadmaderas.com> | Fecha : 16-04-2025 
     *
     */
    function devolucion_sigetapa(){
        $respuesta = FALSE;
        if (!empty($_POST)) {
            $this->db->trans_begin();
            $idsolicitud = $this->input->post('id_sol_avancom1');
            $comentario = $this->input->post('text_comentario_ava');

            $data = [];
            if( $this->input->post("cuenta_contable") )
                $data["homoclave"] = $this->input->post("cuenta_contable");

            if( $this->input->post("cuenta_orden") )
                $data["orden_compra"] = $this->input->post("cuenta_orden");

            // HAY QUE DESCOMENTAR ESTO--->
            if( count($data) > 0 )
                $this->Solicitudes_solicitante->update_solicitud($data, $idsolicitud);
            //@uthor Efraín Martinez Muñoz || Fecha: 31/01/2024 || Se agrego la variable $info_sol_autpagos que obtiene el estatus del ultimo registro
            //que se tiene de la solicitud en autpagos el cual se utilizara en el para validar si se debe de insertar otro registro en la tabla de autpagos.
            $info_sol = $this->M_Devolucion_Traspaso->info_sol($idsolicitud);
            $info_sol_autpagos = $this->M_Devolucion_Traspaso->info_autpagos($idsolicitud);
            
            if($info_sol->row()->idetapa == 78 && $info_sol->row()->idproceso == 30 && $info_sol_autpagos->row()->estatus != 0){

                $cantidad = $this->M_Devolucion_Traspaso->obtenerParcialidad($this->input->post('id_sol_avancom1'));
                $pago = array(
                    "idsolicitud" => $this->input->post('id_sol_avancom1'),
                    "cantidad" => $cantidad->row()->parcialidad,
                    "idrealiza" => 0,
                    "estatus" => 0,
                    "fecreg" =>  '0000-00-00 00:00:00.000',
                    "formaPago" => $info_sol->row()->metoPago,
                    "referencia" => $info_sol->row()->ref_bancaria
                );

                $this->M_Devolucion_Traspaso->insertAutpagosDevolucion($pago);
            }

            if($info_sol->row()->idetapa == 70){
                // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
                $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
                $idpago = $this->M_Devolucion_Traspaso->updateAutpagoDevolucionParcialidad($idsolicitud, $info_sol->row()->metoPago);
                
            }

            if ($info_sol->num_rows() > 0) {
                if($info_sol->row()->idetapa == 7 && $info_sol->row()->idproceso == 30 && $info_sol->row()->metoPago == 'ECHQ'){
                    $this->M_Devolucion_Traspaso->updateEtapaSolpagos($idsolicitud, 70);
                }
                else{
                    $this->M_Devolucion_Traspaso->avanzar2_0($idsolicitud);
                }
                if( $this->db->affected_rows() > 0 ){
                    $this->M_Devolucion_Traspaso->observaciones_doc_descartado($idsolicitud, strtoupper( $comentario ) );

                }

                /*
                if( $this->M_Devolucion_Traspaso->info_sol($idsolicitud)->result_array()->idetapa == 11 && $this->M_Devolucion_Traspaso->info_autpago($idsolicitud)->num_rows() > 0 ){
                    $this->M_Devolucion_Traspaso->updateAutpago($idsolicitud);
                }
                */
                $this->M_Devolucion_Traspaso->updateAutpago($idsolicitud);
                $respuesta = TRUE;
            }

            if ($respuesta === FALSE || $this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $respuesta = FALSE;
            } else {
                $this->db->trans_commit();
                $respuesta = TRUE;
            }
            echo json_encode( $respuesta );
        }
    }

    function rechazar_doc()
    {
        $rechazo = '';

        if (!empty($_POST)) {
            $info = $this->input->post('valordoc');
            $idsolicitud = $this->input->post('idsolicitud');
            $iddoc = $this->input->post('id');
            $info_sol = $this->M_Devolucion_Traspaso->info_sol($idsolicitud);
            $etapa = $info_sol->row()->idetapa;
            $idproceso = $info_sol->row()->idproceso;
            $orden = $this->M_Devolucion_Traspaso->get_orden_sol($etapa, $idproceso)->row()->orden;
            $info_doc = $this->M_Devolucion_Traspaso->info_doc($iddoc);
            $documento = 1;
            ///duda con el rol
            if ($info_doc->num_rows() > 0) {
                $documento = $info_doc->row()->iddocumento;
                $nombre_doc = $info_doc->row()->ndocumento;
                $rol = $this->M_Devolucion_Traspaso->consulta1($documento, $idproceso)->row()->rol;
                $consulta2 = $this->M_Devolucion_Traspaso->consulta($rol, $idproceso, $orden);
                if ($consulta2->num_rows() > 0) {
                    $this->M_Devolucion_Traspaso->regrezar_proceso($consulta2->row()->idetapa, $idsolicitud);
                }
                $rechazo = $this->M_Devolucion_Traspaso->insertar_comentario_rechazado('RECHAZO DE ' . strtoupper($nombre_doc . ': ' . $info), $iddoc);
                $this->M_Devolucion_Traspaso->observaciones_doc_descartado($idsolicitud, 'RECHAZO DE ' . strtoupper($nombre_doc . ': ' . $info));
                // print_r($consulta2);
            }
        }

        echo ($rechazo);
    }
    function generar_caratula()
    {
        if (!empty($this->uri->segment(3))) {
            $idsolicitud = $this->uri->segment(3);
            $informacion_sol = $this->M_Devolucion_Traspaso->getSolicitudadm($idsolicitud)->row();

            $info_caratula_dev_parcialidades = '';
            $info_caratula_dev_fec_entrega = '';
            $info_caratula_dev_fec_fin = '';
            $info_caratula_num_parcialidades = '';
            $info_caratula_num_parcialidades_actual = '';
            $info_caratula_tipo_rescision = '';

            if($informacion_sol->idproceso == 30){
                $monto_parcialidad = isset($informacion_sol->montoParcialidadCaratula) && $informacion_sol->montoParcialidadCaratula != null 
                    ? number_format($informacion_sol->montoParcialidadCaratula, 2) . ' (' . strtolower(convertir(number_format($informacion_sol->montoParcialidadCaratula, 2))) . ' )' 
                    : '--';
                $info_caratula_dev_parcialidades = '<tr> <td colspan="2" align="left"><h4>CANTIDAD PARCIALIDAD: </h4></td><td colspan="4" ><p>'.$monto_parcialidad.'</p></td></tr>';

                $info_caratula_dev_fec_entrega = '<tr> <td colspan="2" align="left"><h4>FECHA ENTREGA: </h4></td><td colspan="4" ><p>'.($informacion_sol->fecelab != null ? $informacion_sol->fecelab : 'SIN DEFINR') .'</p></td></tr>';

                $info_caratula_dev_fec_fin = '<tr> <td colspan="2" align="left"><h4>FECHA FINAL: </h4></td><td colspan="4" ><p>'.($informacion_sol->fecha_fin != null ? $informacion_sol->fecha_fin : 'SIN DEFINR') .'</p></td></tr>';

                $info_caratula_num_parcialidades = '<tr> <td colspan="2" align="left"><h4>TOTAL PARCIALIDADES: </h4></td><td colspan="4" ><p>'.($informacion_sol->numeroPagos != null ? $informacion_sol->numeroPagos : '').'</p></td></tr>';

                $info_caratula_num_parcialidades_actual = '<tr> <td colspan="2" align="left"><h4>PARCIALIDAD ACTUAL: </h4></td><td colspan="4" ><p>'.($informacion_sol->pagoActual != null ? $informacion_sol->pagoActual : '').'</p></td></tr>';

            }
            $this->load->library('Pdf');
            $pdf = new TCPDF('P', 'mm', 'LETTER', 'UTF-8', false);

            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetAuthor('Sistemas Victor Manuel Sanchez Ramirez');
            $pdf->SetTitle('DOCUMENTO RELACIÓN GASTOS');
            $pdf->SetSubject('DOCUMENTO RELACIÓN GASTOS POR ÁREA');
            $pdf->SetKeywords('DOCUMENTO, CIUDAD MADERAS, ÁREA, EMPRESA, GASTOS');
            // se pueden modificar en el archivo tcpdf_config.php de libraries/config
            $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

            // se pueden modificar en el archivo tcpdf_config.php de libraries/config
            $pdf->SetAutoPageBreak(TRUE, 0);

            //relación utilizada para ajustar la conversión de los píxeles
            $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);

            $pdf->setFontSubsetting(true);
            $pdf->SetFont('Helvetica', '', 11, '', true);
            $pdf->SetMargins(20, 7, 15, true);


            $datos_bancarios = '';

            if( isset($informacion_sol->metoPago) && $informacion_sol->metoPago == 'TEA' ){
                $datos_bancarios = '<tr>
                    <td></td>
                    <td colspan="3" style="border: 1px solid black; "><b>BANCO:</b></td>
                    <td colspan="3" style="border: 1px solid black; ">' . (isset($informacion_sol->nomban) ? $informacion_sol->nomban : "---") . '</td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="3" style="border: 1px solid black;"><b>No. CUENTA/CLABE INTERBANCARIA</b></td>
                    <td colspan="3" style="border: 1px solid black;">' . (isset($informacion_sol->cuenta) ? $informacion_sol->cuenta : "---") . '</td>
                    <td></td>
                </tr>';
            }

            if( isset($informacion_sol->metoPago) && $informacion_sol->metoPago == 'MAN' ){
                $datos_bancarios = '<tr>
                    <td></td>
                    <td colspan="3" style="border: 1px solid black; "><b>BANCO:</b></td>
                    <td colspan="3" style="border: 1px solid black; ">' . (isset($informacion_sol->nomban) ? $informacion_sol->nomban : "---") . '</td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="3" style="border: 1px solid black;"><b>No. CUENTA/CLABE INTERBANCARIA</b></td>
                    <td colspan="3" style="border: 1px solid black;">' . (isset($informacion_sol->cuenta) ? $informacion_sol->cuenta : "---") . '</td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td style="border: 1px solid black;"><b>CODIGO ABA</b></td>
                    <td colspan="2" style="border: 1px solid black;">' . (isset($informacion_sol->clvbanco) ? $informacion_sol->clvbanco : "---") . '</td>
                    <td style="border: 1px solid black;"><b>SWIFT</b></td>
                    <td colspan="2" style="border: 1px solid black;">' . (isset($informacion_sol->clvbanco) ? $informacion_sol->clvbanco : "---") . '</td>
                    <td></td>
                </tr>';
            }

            $pdf->AddPage('P', 'LETTER');
            $style = "<style>
             .mas_espacio{
                 height: 120px;
             }
             .justificacion{
                 height: 200px;
             }
             .firmas{
                 text-align: center;
                 height: 100px;
             }
             .contornos {
                 border-radius: 3px;
                 border: .5px solid black;
             }
             span{
                 border-radius: 2px 0px 0px 0px;
                 background-color: #234e7f;
                 color: #fff;
             }

             .subtabla{
                 width: 35%;
             }
             .subtabla_menos{
                 width: 15%;
             }

             table{
                font-size: 11px;
            }

             h3{
                 color: #00548B
                 }

             .piepagina{
                 background-color: #234e7f;
                 color: #fff;
             }
             .titulo{
                 background-color: #00548B;
                 color: #fff;

             }

         </style>";

            $html = $style . '
            <table cellspacing="9">
                  <tr>
                    <td colspan="2" ><img width="150px" src="' . base_url("img/logo_cd_nuevo_xl.png") . '"></td>
                    <td colspan="3" align="right"><h2>' . strtoupper($informacion_sol->nombre_proceso) . ' </h2>
                        <p>'.strtoupper($informacion_sol->proyecto).'</p>
                    </td>
                  </tr>
                  <tr>
                    <td  colspan="5" align="right"><p><b>Fecha de solicitud:</b> '. date_format(date_create($informacion_sol->fechaCreacion), 'd/m/Y') . '</p></td>
                </tr>
            </table>
            <br><br><br><br>
            <table  cellspacing="9" cellpadding="4">
            <tr>
                <td colspan="2" align="left"><h4>No SOLICITUD: </h4></td>
                <td><p>' . strtoupper($informacion_sol->idsolicitud) . '</p> </td>
                <td colspan="2" align="left"><h4>FECHA OPERACIÓN: </h4></td>
                <td><p>' .date_format(date_create($informacion_sol->fecelab), 'd/m/Y'). '</p> </td>
            </tr>
            <tr>
                <td colspan="2" align="left"><h4>NOMBRE TITULAR: </h4></td>
                <td colspan="4" ><p>' . (isset($informacion_sol->requisicion) && $informacion_sol->requisicion != null ? strtoupper($informacion_sol->requisicion) : '---')   . '.</p> </td>
            </tr>
            <tr>
                <td colspan="2" align="left"><h4>LOTE: </h4></td>
                <td colspan="4" ><p>' . strtoupper($informacion_sol->condominio) . '</p> </td>
            </tr>
            <tr> <!-- FECHA: 29-ABRIL-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                <td colspan="2" align="left"><h4>REF. LOTE: </h4></td>
                <td colspan="4"><p>' . (isset($informacion_sol->referencia) ? $informacion_sol->referencia : "---") . '</p> </td>
            </tr> <!-- FECHA: 29-ABRIL-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
            <!--Inicio @uthor Efrain Martinez Muñoz <programador.analista38@ciudadmaderas>| Fecha : 16-04-2025 
                Se agrega el estatus del lote en el pdf de la caratula que se descarga -->
            <tr>
                <td colspan="2" align="left"><h4>ESTATUS LOTE: </h4></td>
                <td colspan="4" ><p>' . (isset($informacion_sol->estatusLote) ? strtoupper($informacion_sol->estatusLote) : "N/A") . '</p> </td>
            </tr>
            <!--FIN -->
            <tr>
                <td colspan="2" align="left"><h4>ETAPA: </h4></td>
                <td colspan="4" ><p>' . (isset($informacion_sol->etapa) ? strtoupper($informacion_sol->etapa) : "---") . '  </p> </td>
            </tr>
            <tr>
                <td colspan="2" align="left"><h4>CUENTA CONTABLE: </h4></td>
                <td colspan="4" ><p> ' .  (isset($informacion_sol->homoclave) ? $informacion_sol->homoclave : "---") . '</p> </td>
            </tr>
            <tr>
                <td colspan="2" align="left"><h4>CUENTA DE ORDEN: </h4></td>
                <td colspan="4" ><p> ' .  (isset($informacion_sol->orden_compra) ? $informacion_sol->orden_compra : "---") . '</p> </td>
            </tr>
            <tr>
                <td colspan="2" align="left"><h4>EMPRESA: </h4></td>
                <td colspan="4" ><p> ' .  (isset($informacion_sol->nempresa) ? $informacion_sol->nempresa : "---") . '</p> </td>
            </tr>
            <tr>
                <td colspan="2" align="left"><h4>CANTIDAD '.($informacion_sol->idproceso == 30 ? '(TOTAL/PARCIALIDAD)' : '' ) . ': </h4></td>  './**  Cambio de reporte PDF para parcialidades en el modal de consulta| FECHA: 16-Mayo-2024 | @author Lalo Silva <programador.analista38@ciudadmaderas.com>  **/
                '<td colspan="4" ><p>$' . ($informacion_sol->idproceso == 30 ? number_format($informacion_sol->cantidad, 2) . " / " . number_format($informacion_sol->montoParcialidad, 2) : number_format($informacion_sol->cantidad, 2)) . ' (' .strtoupper(strtolower(convertir(number_format($informacion_sol->cantidad, 2)))) . ')</p> </td>'. /** FIN cambio de reporte PDF para parcialidades en el modal de consulta| FECHA: 16-Mayo-2024 | @author Lalo Silva <programador.analista38@ciudadmaderas.com>  **/
            '</tr>
            '.$info_caratula_dev_parcialidades . $info_caratula_dev_fec_entrega . $info_caratula_dev_fec_fin . $info_caratula_num_parcialidades . $info_caratula_num_parcialidades_actual .'
            <tr>
                <td colspan="6" align="left"><p style="text-align:justify;"><b>COMENTARIOS ADICIONALES</b>: ' .$informacion_sol->justificacion. '</p></td>
            </tr>
            </table>
            <br>
            <table style="border-bottom: 1px solid black; border-top: 1px solid black;" cellspacing="9" cellpadding="4">
                <tr>
                    <td align="left"><h4>PRECIO M2:</h4></td>
                    <td><p>' .(isset($informacion_sol->preciom) ? $informacion_sol->preciom : "---") . '</p> </td>
                    <td align="left"><h4>SUPERFICIE M2:</h4></td>
                    <td><p>' .(isset($informacion_sol->superficie) ? $informacion_sol->superficie : "---") . '</p> </td>
                </tr>
                <tr>
                    <td align="left"><h4>COSTO DE LOTE:</h4></td>
                    <td><p>' .(isset($informacion_sol->costo_lote) ? $informacion_sol->costo_lote : "---") . '</p> </td>
                    <td align="left"><h4>PREDIAL:</h4></td>
                    <td><p>' .(isset($informacion_sol->predial) ? $informacion_sol->predial : "---") . '</p> </td>
                </tr>
                <tr>
                    <td align="left"><h4>PENALIZACIÓN %:</h4></td>
                    <td><p>' .(isset($informacion_sol->por_penalizacion) ? $informacion_sol->por_penalizacion : "---") . '</p> </td>
                    <td align="left"><h4>PENALIZACIÓN $:</h4></td>
                    <td><p>' .(isset($informacion_sol->penalizacion) ? $informacion_sol->penalizacion : "---") . '</p> </td>
                </tr>
                <tr>
                    <td align="left"><h4>IMPORTE APORTADO:</h4></td>
                    <td><p>' .(isset($informacion_sol->importe_aportado) ? $informacion_sol->importe_aportado : "---") . '</p> </td>
                    <td align="left"><h4>MANTENIMIENTO:</h4></td>
                    <td><p>' .(isset($informacion_sol->mantenimiento) ? $informacion_sol->mantenimiento : "---") . '</p> </td>
                </tr>
            </table>
            <br>
            <br>
            <table cellpadding="3">
                <tr>
                    <td></td>
                    <td colspan="3" style=" border: 1px solid black; "><b>FORMA DE PAGO:</b></td>
                    <td colspan="3" style=" border: 1px solid black; ">' . (isset($informacion_sol->metoPago) ? [ 'TEA' => 'TRANSFERENCIA ELECTRÓNICA', 'ECHQ' => 'CHEQUE', 'MAN' => 'TRANSFERENCIA INTERNACIONAL', 'NA' => 'NO APLICA' ][$informacion_sol->metoPago] : "---") . '</td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="3" style=" border: 1px solid black; " ><b>NOMBRE BENEFICIARIO:</b></td>
                    <td colspan="3" style=" border: 1px solid black; ">'.$informacion_sol->nomprove.'</td>
                    <td></td>
                </tr>
                '.$datos_bancarios.'
            </table>
            <br>
            <br>
             <table cellspacing="9" cellpadding="4">
                    <tr align="center">
                    <td><br/><br/><br/><br/><br/><br/><b></b></td>
                    <td><br/><br/><br/><br/>_______________________<br/><br/><b>' . strtoupper($informacion_sol->nomprove) . '</b><br/>' . date_format(date_create($informacion_sol->fecelab), 'd/m/Y'). '</td>
                    <td><br/><br/><br/><br/><br/><br/><b></b></td>
                </tr>

            </table>

            ';
            $pdf->writeHTMLCell(0, 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
            $img_file = base_url("img/logo_cd_arbol_xl.png");
            $pdf->Image($img_file, 0, 0, 223, 280, '', '', '', false, 300, '', false, false, 0);




            $pdf->Output(utf8_decode("REGISTRO_PROYECTO_MEJORA" . $informacion_sol->ref_bancaria . '_' . date("dmy") . ".pdf"), 'D');
        }
    }
    function conusltar_areas_proceso(){
        if (!empty($_POST)) {

            $idsolicitud = $this->input->post('idsolicitud');
            $orden = $this->M_Devolucion_Traspaso->proceso_etapas( $idsolicitud );
            $data_array["data"] = array();
            if ($orden->num_rows() > 0) {
                foreach ( $orden->result() as  $value ) {
                    $data_array["data"][] = array(
                        "nombre" => $value->etapa_regresar,
                        "solicitud" => $idsolicitud,
                        "idrol" => $value->idrol,
                        "orden" => $value->orden,
                        "idetapa" => $value->idetapa
                    );
                }
            }else{
                $data_array["data"] = FALSE;
            }

            $data_array["info"] = $this->M_Devolucion_Traspaso->getSolicitudadm( $idsolicitud )->result_array();
            echo json_encode( $data_array );
        }
    }


    function conusltar_areas_proceso_menor()
    {
        if (!empty($_POST)) {
            $idsolicitud = $this->input->post('idsolicitud');
            $info_sol = $this->M_Devolucion_Traspaso->info_sol($idsolicitud);
            $etapa = $info_sol->row()->idetapa;
            $idproceso = $info_sol->row()->idproceso;
            $orden = $this->M_Devolucion_Traspaso->proceso_etapas_menor($idproceso, $etapa);
            $data_array["data"] = array();
            if ($orden->num_rows() > 0) {
                foreach ($orden->result() as  $value) {
                    $data_array["data"][] = array(
                        "nombre" => $value->etapa_regresar,
                        "solicitud" => $idsolicitud,
                        "idrol" => $value->idrol,
                        "orden" => $value->orden,
                        "idetapa" => $value->idetapa
                    );

                    if ($info_sol->row()->idproceso == 30 && trim($info_sol->row()->proyecto) == 'RESCISIÓN PARCIALIDAD' && $value->idetapa == '75') {
                        array_pop($data_array["data"]);
                    }
                }
            }
            echo json_encode($data_array);
        }
    }
    // @uthor Efraín Martinez Muñoz || Fecha 31/01/2024 || Se creo la función consultar_areas_proceso_mayor en la cual se extraen las etapas a las que
    // la solicitud se puede avanzar en caso de que se hayan regresado de una etapa superior.
    function consultar_areas_proceso_mayor()
    {
        if (!empty($_POST)) {
            $idsolicitud = $this->input->post('idsolicitud');
            $info_sol = $this->M_Devolucion_Traspaso->info_sol($idsolicitud);
            $etapa = $info_sol->row()->idetapa;
            $idproceso = $info_sol->row()->idproceso;
            $orden = $this->M_Devolucion_Traspaso->proceso_etapas_mayor($idproceso, $etapa, $idsolicitud);
            $data_array["data"] = array();

            
            if ($orden->num_rows() > 0) {
                foreach ($orden->result() as  $value) {
                    $data_array["data"][] = array(
                        "nombre" => $value->etapa_regresar,
                        "solicitud" => $idsolicitud,
                        "idrol" => $value->idrol,
                        "orden" => $value->orden,
                        "idetapa" => $value->idetapa
                    );
                }
            }
            echo json_encode($data_array);
        }
    }

    function regresar_sol_area(){
        $respuesta = FALSE;
        if (!empty($_POST)) {

            $this->db->trans_begin();

            $orden = $this->input->post('radios');
            $text_comentario = $this->input->post('text_comentario');
            $idsolicitud = $this->input->post('solicitud_fom');

            $info_sol = $this->M_Devolucion_Traspaso->info_sol($idsolicitud);

            if ($info_sol->num_rows() > 0) {

                $proceso_info = $this->M_Devolucion_Traspaso->proceso_info($info_sol->row()->idproceso, $orden);
                $this->M_Devolucion_Traspaso->actualizar_etapa($proceso_info->row()->idetapa, $idsolicitud, 1);
                //$this->M_Devolucion_Traspaso->notificacion($idsolicitud);

                if (!empty($this->input->post('iddocumento'))) {

                    $iddoc = $this->input->post('iddocumento');
                    $info_doc = $this->M_Devolucion_Traspaso->info_doc($this->input->post('iddocumento'));

                    if ($info_doc->num_rows() > 0) {
                        $nombre_doc = $info_doc->row()->ndocumento;
                        $this->M_Devolucion_Traspaso->insertar_comentario_rechazado('RECHAZO DE ' . $nombre_doc . ': ' . $text_comentario, $iddoc);
                        $this->M_Devolucion_Traspaso->observaciones_doc_descartado($idsolicitud, 'RECHAZO DE ' . $nombre_doc . ': ' . $text_comentario);
                    }
                } else {
                    $this->M_Devolucion_Traspaso->observaciones_doc_descartado($idsolicitud, 'SE RECHAZA POR: ' . strtoupper($text_comentario));
                }

                $respuesta = TRUE;
            }

            if ($respuesta === FALSE || $this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $respuesta = FALSE;
            } else {
                $this->db->trans_commit();
                $respuesta = TRUE;
            }
        }

        echo json_encode($respuesta);
    }

    //CANCELADAS/RECHADAS O AVANCE POR PARTE DE ADMON
    function avanzar_sol_ad(){
        $resultado = false;

        // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
        if (isset($_POST) && !empty($_POST)) {
            $info_sol = $this->M_Devolucion_Traspaso->info_sol($this->input->post('idsol_ad'));

            if ($info_sol->num_rows() > 0) {
                $data = array();
                $dataad = array();

                if( $this->input->post('nforma_pago') ){
                    $data["metoPago"] = $this->input->post('nforma_pago');
                }
                if ($this->input->post('cuenta_contable')) {
                    $data["homoclave"] = $this->input->post('cuenta_contable');
                }

                if ($this->input->post('empresa_ad')) {
                    $data["idEmpresa"] = $this->input->post('empresa_ad');
                }

                if ($this->input->post('etapa_conta')) {
                    $data["etapa"] = $this->input->post('etapa_conta');
                }

                if ($this->input->post('cuenta_orden')) {
                    $data["orden_compra"] = $this->input->post('cuenta_orden');
                }

                if ($this->input->post('copropiedad')) {
                    $data["rcompra"] = $this->input->post('nom_copropiedad');
                }

                if ($this->input->post('solobs_ad')) {
                    $data["justificacion"] = $this->input->post('solobs_ad');
                }

                //////AQUI SE INSERTA MOTIVO LOTE, SUPERFICIE
                $idsol = $this->input->post('idsol_ad');
                
                //AQUI SE ACTUALIZA LA REFERENCIA DEL LOTE EN solicitud_drl /** INICIO FECHA: 29-ABRIL-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                if($this->input->post('referenciaAd')){
                    $referenciaPost = $this->input->post('referenciaAd');

                    $solicitud_drl = $this->db->query("UPDATE solicitud_drl SET referencia = ? WHERE idsolicitud = ?", array($referenciaPost, $this->input->post('idsol_ad')));
                }/** FIN FECHA: 29-ABRIL-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                
                //EDITAR O ACTUALIZAR UN PROVEEDOR SELECCIONADO
                $nombre_cliente = limpiar_dato($this->input->post("nombreproveedor_new"));
                if( $this->input->post('nforma_pago') == 'MAN' ){
                    $cuenta = $this->input->post("cuenta_new_MAN");
                    $tipo_cuenta = "40";

                    $nbanco = limpiar_dato($this->input->post("banco_ext_new"));
                    $aba_swift = $this->input->post("aba_new");

                    $revisar_banco = $this->db->query("SELECT * FROM bancos WHERE nombre = ? AND clvbanco = ? AND estatus = 2", [ $nbanco, $aba_swift ]);
                    if( $revisar_banco->num_rows() > 0 && $this->input->post("idbanco_MAN") == $revisar_banco->row()->idbanco ){
                        $banco = $revisar_banco->row()->idbanco;
                    }else{
                        $this->db->insert("bancos", [
                            "nombre" => $nbanco,
                            "clvbanco" => $aba_swift,
                            "estatus" => 2
                        ]);

                        $banco = $this->db->insert_id();
                    }
                }else{
                    $banco = $this->input->post("idbanco_TEA") ? $this->input->post("idbanco_TEA") : 6;
                    $cuenta = $this->input->post("cuenta_new_TEA") ? $this->input->post("cuenta_new_TEA") : 0;
                    $tipo_cuenta = $this->input->post("tipocta_new") ? $this->input->post("tipocta_new") : 1;
                }

                if( in_array( $this->input->post('nforma_pago'), [ 'TEA', 'MAN' ] ) ){
                    $revisar_cuenta_proveedor = $this->db->query("SELECT * FROM proveedores WHERE nombre = ? AND cuenta = ? AND idbanco = ? AND tipocta = ? ORDER BY cuenta DESC", [ $nombre_cliente, $cuenta, $banco, $tipo_cuenta ]);
                }else{
                    $revisar_cuenta_proveedor = $this->db->query("SELECT * FROM proveedores WHERE nombre = ? ORDER BY cuenta DESC", [ $nombre_cliente ]);
                }

                if( $revisar_cuenta_proveedor->num_rows() > 0 ){
                    $data["idProveedor"] = $revisar_cuenta_proveedor->row()->idproveedor;
                }else{
                    $this->db->insert("proveedores", [
                        "nombre" => $nombre_cliente,
                        "alias" => substr(str_replace(" ", "", $nombre_cliente), 0, 10).str_pad(rand(0,999), 3, "0", STR_PAD_LEFT),
                        "idbanco" => $banco ,
                        "tipocta" => $tipo_cuenta,
                        "cuenta" => $cuenta,
                        "rfc" => "CLIENTE",
                        "email" => "CLIENTE@CLIENTE.COM",
                        "sucursal" => 1,
                        "fecadd" => date("Y-m-d H:i:s"),
                        "idby" => $this->session->userdata("inicio_sesion")['id'],
                        "estatus" => 2
                    ]);
                    $data["idProveedor"] = $this->db->insert_id();
                }
                /*******************************************************************/

                //DATOS DE LA SOLICITUD
                if( $this->input->post('areas_avanzar_new') ){
                    $data["idetapa"] = $this->input->post('areas_avanzar_new');
                }else{
                    $proxima_etapa = $this->M_Devolucion_Traspaso->getProxEtapa($this->input->post('idsol_ad'));
                    $data["idetapa"] = $proxima_etapa->num_rows() > 0 ? $proxima_etapa->row()->sig_etapa : $info_sol->row()->idetapa;
                }
                
                $data["idAutoriza"] = $this->session->userdata("inicio_sesion")['id'];
                $data["fecha_autorizacion"] = date("Y-m-d H:i:s");
                $data["prioridad"] = NULL;
                //ACTUALIZAMOS LA SOLICITUD
                $respuesta = $this->M_Devolucion_Traspaso->updateDevTras($data, $this->input->post('idsol_ad'), $info_sol->row()->idetapa );
                //$this->M_Devolucion_Traspaso->avanzar2_0($this->input->post('idsol_ad'));
                /*
                 * INICIO FECHA : 26-MAYO-2025 | @uthor Efrain Martinez programador.analista38@ciudadmaderas.com
                 * Se mueve la insercion de la tabla de logs de importe aportado y manetnimiento a despues de que se realize el update de solpagos ya que se necesita saber el idusuario de la persona que esta avanzando la solicitud.
                */
                $dataad = array(
                            "costo_lote" => $this->input->post('costo_lote_new'),
                            "superficie" => $this->input->post('superficie_new'),
                            "preciom" => $this->input->post('preciom_new'),
                            "predial" => $this->input->post('predial_new'),
                            "por_penalizacion" => $this->input->post('por_penalizacion_new'),
                            "penalizacion" => $this->input->post('penalizacion_new'),
                            "importe_aportado" => $this->input->post('importe_aportado_new'),
                            "mantenimiento" => $this->input->post('mantenimiento_new'),
                        );
                $this->M_Devolucion_Traspaso->update_mas_pv($dataad,$idsol);
                /**
                 * FIN | FECHA 26-MAYO-2025 @author Efrain Martinez programador.analista38@ciudadmaderas.com
                */

                if ($this->db->trans_status() === FALSE && !$respuesta) {
                    $this->db->trans_rollback();
                    $resultado = array("resultado" => FALSE);
                } else {
                    $this->db->trans_commit();
                    $resultado = array("resultado" => TRUE);
                }
            }
        }
        echo json_encode($resultado);
    }


    function avanzar_area_r()
    {
        $respuesta = false;
        if (!empty($_POST)) {
            $orden =  $this->input->post('areas_avanzar');
            $idsolicitud =  $this->input->post('idsol_area');
            $info_sol = $this->M_Devolucion_Traspaso->info_sol($idsolicitud);
            if ($info_sol->num_rows() > 0) {
                $proceso = $info_sol->row()->idproceso;
                $proceso_info = $this->M_Devolucion_Traspaso->proceso_info($proceso, $orden);
                if ($proceso_info->num_rows() > 0) {
                    $nueva_etapa = $proceso_info->row()->idetapa;
                    $prioridad = NULL;
                    $this->M_Devolucion_Traspaso->actualizar_etapa($nueva_etapa, $idsolicitud, $prioridad);
                    $comentari = 'SE AUTORIZA';
                    $this->M_Devolucion_Traspaso->observaciones_doc_descartado($idsolicitud, $comentari);
                    $respuesta = TRUE;
                }
            }
        }
        echo json_encode($respuesta);
    }

    ///ACA COMINEZA SOF
    function Procesos()
    {
        if ($this->session->userdata("inicio_sesion") && in_array($this->session->userdata("inicio_sesion")['rol'], array('DG', 'DP', 'SB', 'SU', 'DIO')) || in_array($this->session->userdata("inicio_sesion")['id'], array('1835')))
            $this->load->view("v_facturasProcesos");
        else
            redirect("Login", "refresh");
    }

    function tablaDGprocesos()
    {

        $data = $this->M_Devolucion_Traspaso->obtenerSolPendientesProcesos()->result_array();

        for ($i = 0; $i < count($data); $i++) {

            $fecha = date("d", strtotime($data[$i]['FECHAFACP']));
            $fecha .= "/" . array("Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic")[date("m", strtotime($data[$i]['FECHAFACP'])) - 1];
            $fecha .= "/" . date("Y", strtotime($data[$i]['FECHAFACP']));

            $data[$i]['FECHAFACP'] = $fecha;
            $data[$i]['pa'] = 0;
        }

        echo json_encode(array("data" => $data));
    }

    function PagoProcesos()
    {
        // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
        $resultado = false;

        if ($this->input->post('jsonApagar')) {
            $json = json_decode($this->input->post('jsonApagar'));
            $this->db->trans_start();

            $solicitudes = array();
            $comentarios = array();
            $idsolicitudes = array();
            $fecha = date("Y-m-d H:i:s");
            foreach ($json as $row) {

                $idsolicitudes[] = $row[0];

                if( $row[1] > 0){

                    $solicitudes[] = array(
                        "idsolicitud" => $row[0],
                        "cantidad" => $row[1],
                        "idrealiza" => $this->session->userdata('inicio_sesion')['id'],
                        "estatus" => 0,
                        "fecreg" => $fecha
                    );

                    $comentarios[] = array(
                        "idsolicitud" => $row[0],
                        "tipomov" => "SE HA AUTORIZADO UN PAGO A LA SOLICITUD. $ " . number_format($row[1], 2, ".", ","),
                        "idusuario" => $this->session->userdata('inicio_sesion')['id'],
                        "fecha" => $fecha
                    );
                }
            }

            $resultados = count($idsolicitudes);
            $idsolicitudes = implode(",", $idsolicitudes);

            $this->db->query("UPDATE solpagos sp 
            INNER JOIN proceso_etapa pe ON pe.idproceso = sp.idproceso AND sp.idetapa = pe.idetapa
            LEFT JOIN proceso_etapa siguiente ON (siguiente.idproceso = sp.idproceso AND siguiente.orden = pe.orden +1)
            SET sp.idetapa = CASE
                WHEN sp.proyecto = 'RESCISIÓN PARCIALIDAD' AND sp.idproceso = 30 THEN 70
                ELSE siguiente.idetapa
            END,
            sp.idAutoriza = ?, sp.fecha_autorizacion = NOW() WHERE sp.idsolicitud
            IN ($idsolicitudes) AND sp.idproceso IS NOT NULL AND siguiente.idetapa IS NOT NULL ", [ $this->session->userdata('inicio_sesion')['id'] ]);

            if( count( $solicitudes ) > 0 ){
                $this->db->insert_batch('autpagos', $solicitudes);
                $this->db->query("DELETE FROM autpagos 
                                  WHERE autpagos.idpago IN (SELECT idpago 
                                                            FROM solpagos 
                                                            INNER JOIN (SELECT MAX(autpagos.idpago) AS idpago, autpagos.idsolicitud, SUM(autpagos.cantidad) total
                                                                        FROM autpagos 
                                                                        WHERE autpagos.estatus = 0
                                                                        GROUP BY autpagos.idsolicitud ) autpagos 
                                                                ON autpagos.idsolicitud = solpagos.idsolicitud 
                                                            WHERE total > solpagos.cantidad)");
                $this->db->query("UPDATE autpagos p JOIN (
                    SELECT idsolicitud,  idetapa FROM solpagos WHERE idsolicitud IN ( $idsolicitudes )
                ) s ON s.idsolicitud = p.idsolicitud
                SET fecreg = '0000-00-00 00:00:00', idrealiza = 0
                WHERE p.estatus = 0 AND idrealiza = '".$this->session->userdata('inicio_sesion')['id']."' AND fecreg = '$fecha' AND s.idetapa NOT IN (11, 81, 80)");
                $this->db->insert_batch('logs', $comentarios);
            }
            
            $this->db->trans_complete();
            $resultado = $this->db->trans_status();
        }

        echo json_encode(array($resultado, $resultados));
    }

    public function nuevos_proveedores($datos_post)
    {
        // FECHA : 01-JULIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
        // if ($this->input->post("idbanco") && $datos_post["forma_pago"] == 'MAN') {
        if ($datos_post["forma_pago"] == 'MAN') {
            $idbanco = $this->M_Devolucion_Traspaso->Nuevo_banco_extra($this->input->post('banco_ext'), $this->input->post('aba'));

            $data = array(
                "nombre" => limpiar_dato($this->input->post('nombreproveedor')),
                "tipo_prov" => 0,
                "sucursal" => $this->input->post('sucursal_ext'),
                "cuenta" => $this->input->post('cuenta_extr'),
                "idbanco" => $idbanco,
                "estatus" => 2,
                "idby" => $this->session->userdata("inicio_sesion")['id']
            );
        } else {
            $idbanco = $this->input->post("idbanco") ? $this->input->post("idbanco") : 6;
            $data = array(
                "nombre" => limpiar_dato($this->input->post('nombreproveedor')),
                "tipocta" => $this->input->post('tipocta'),
                "cuenta" => $this->input->post('cuenta'),
                "idbanco" => $idbanco,
                "tipo_prov" => 0,
                "estatus" => 2,
                "idby" => $this->session->userdata("inicio_sesion")['id']
            );
        }

        $this->db->insert("proveedores", $data);
        $idproveedor = $this->db->insert_id();
        $this->db->update("proveedores", array("alias" => substr(str_replace(" ", "", $this->input->post("nombreproveedor")), 0, 5) . $idproveedor), "idproveedor = '" . $idproveedor . "'");

        return $idproveedor;
    }
    public function nuevos_proveedores_new($datos_post)
    {
        // FECHA : 01-JULIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
        //esta es transferencia MAN
        if ($this->input->post("sucursal_ext_new") &&  $this->input->post("cuenta_extr_new")) {
            $idbanco = $this->M_Devolucion_Traspaso->Nuevo_banco_extra($this->input->post('banco_ext_new'), $this->input->post('aba_new'));

            $data = array(
                "nombre" => limpiar_dato($this->input->post('nombreproveedor_new')),
                "tipo_prov" => 0,
                "sucursal" => $this->input->post('sucursal_ext_new'),
                "cuenta" => $this->input->post('cuenta_extr_new'),
                "idbanco" => $idbanco,
                "estatus" => 2,
                "idby" => $this->session->userdata("inicio_sesion")['id']
            );
        } else  {
            $idbanco = $this->input->post("idbanco_new") ? $this->input->post("idbanco_new") : 6;
            $data = array(
                "nombre" => limpiar_dato($this->input->post('nombreproveedor_new')),
                "tipocta" => $this->input->post('tipocta_new'),
                "cuenta" => $this->input->post('cuenta_new'),
                "idbanco" => $idbanco,
                "tipo_prov" => 0,
                "estatus" => 2,
                "idby" => $this->session->userdata("inicio_sesion")['id']
            );
        }

        $this->db->insert("proveedores", $data);
        $idproveedor = $this->db->insert_id();
        $this->db->update("proveedores", array("alias" => substr(str_replace(" ", "", $this->input->post("nombreproveedor")), 0, 5) . $idproveedor), "idproveedor = '" . $idproveedor . "'");

        return $idproveedor;
    }
    function borrar_documento()
    {
        $resultado = FALSE;

        if (isset($_POST) && !empty($_POST)) {
            $iddocumento = $this->input->post('iddocumento');
            $comentario = 'SE BORRA DOCUMENTO POR ERROR.';

            $this->M_Devolucion_Traspaso->insertar_comentario_rechazado($comentario, $iddocumento);

            if ($this->db->trans_status() === FALSE && !$resultado) {
                $this->db->trans_rollback();
                $resultado = array("resultado" => FALSE);
            } else {
                $this->db->trans_commit();
                $resultado = array("resultado" => TRUE);
            }
        }
        echo json_encode($resultado);
    }


    public function obtenerHistrialParcialidades(){
        echo json_encode(array("data" =>$this->M_Devolucion_Traspaso->obtenerHistrialParcialidades($this->input->post("fechaInicial"), $this->input->post("fechaFinal"), $this->input->post("tipoReporte"))->result_array()));
    }

    public function obtenerHistrialParcialidadesDesglosado(){
        echo json_encode(array("data" =>$this->M_Devolucion_Traspaso->obtenerHistrialParcialidadesDesglosado($this->input->post("fechaInicial"), $this->input->post("fechaFinal"), $this->input->post("tipoReporte"))->result_array()));
    }

    public function obtenerHistorialPagos(){
        echo json_encode(array("data" =>$this->M_Devolucion_Traspaso->obtenerHistrialParcialidadesPagos($this->input->post("idsolicitud"))->result_array()));
    }

    public function editarFormaPago(){
        // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
        if (!isset($_POST) && empty($_POST))  return;
        
        $infoProveedor = $this->Lista_dinamicas->obtenerProveedoresCliente($this->input->post("nombre"), $this->input->post("tipocta_new"), $this->input->post("idbanco"))->result_array();

        if(count($infoProveedor) >= 1){
            if($this->input->post('nforma_pago') == 'ECHQ'){
                $this->M_Devolucion_Traspaso->updateFormaPago('solpagos', array("metoPago" => 'ECHQ'), 'idsolicitud = '.$this->input->post('idsolicitud'));
            }
            /**
             * FECHA : 01-JULIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | SE AGREGA EL PARAMETRO DE idupdate AL ARREGLO
             * PARA QUE TAMBIEN SE ACTUALIZE ESTA COLUMNA AL EDITAR EL METO DE PAGO DESDE EL PANEL DE PARCIALIDADES.
             */
            if($this->input->post('nforma_pago') == 'TEA'){
                $this->M_Devolucion_Traspaso->updateFormaPago('solpagos', array("metoPago" => 'TEA'), 'idsolicitud = '.$this->input->post('idsolicitud'));
                $this->M_Devolucion_Traspaso->updateFormaPago('proveedores', 
                    array("idbanco" => $this->input->post('idbanco'),
                        "cuenta" => $this->input->post('cuenta_new_TEA'),
                        "tipocta"=> $this->input->post('tipocta_new'),
                        "idupdate"=> $this->session->userdata('inicio_sesion')['id'],
                        "fecha_update" => date('Y-m-d H:i:s')
                ), 'idproveedor = '.$this->input->post('idProveedor'));
            }
    
            if($this->input->post('nforma_pago') == 'MAN'){
                $this->M_Devolucion_Traspaso->updateFormaPago('solpagos', array("metoPago" => 'MAN',
                                                                            "ref_bancaria" => $this->input->post('referencia_ext_new')
                                                                        ), 'idsolicitud = '.$this->input->post('idsolicitud'));
                $idBanco = $this->M_Devolucion_Traspaso->Nuevo_banco_extra($this->input->post('banco_ext_new'), $this->input->post('aba_new'));
                /**
                 * FECHA : 01-JULIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | SE AGREGA EL PARAMETRO DE idupdate AL ARREGLO
                 * PARA QUE TAMBIEN SE ACTUALIZE ESTA COLUMNA AL EDITAR EL METO DE PAGO DESDE EL PANEL DE PARCIALIDADES.
                 */
                $this->M_Devolucion_Traspaso->updateFormaPago('proveedores', array("
                    idbanco" => $idBanco,
                    "cuenta" => $this->input->post('cuenta_new_MAN'),
                    "idupdate"=> $this->session->userdata('inicio_sesion')['id'],
                    "fecha_update" => date('Y-m-d H:i:s')
                ), 'idproveedor = '.$this->input->post('idProveedor'));
            }
        }

        /** INICIO FECHA: 04-MARZO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
        if($this->input->post('justificacion_solicitud')){
            $idsolicitud = $this->input->post('idsolicitud');
            $justificacionNueva = $this->input->post('justificacion_solicitud');

            // Obtener la justificación actual de la base de datos
            $solicitud = $this->db->select('justificacion')
                                ->from('solpagos')
                                ->where('idsolicitud', $idsolicitud)
                                ->get()
                                ->row();

            $justificacionActual = $solicitud ? $solicitud->justificacion : '';

            // Verificamos si la nueva justificación es diferente de la actual
            if ($justificacionActual !== $justificacionNueva) {
                $data = array('justificacion' => $justificacionNueva);

                $this->db->where('idsolicitud', $idsolicitud);
                $this->db->update('solpagos', $data);

                if ($this->db->affected_rows() > 0) {
                    // Hacer la inserción en Tbla: "auditoria"
                    $datosInsert = array(
                        'id_parametro' => $idsolicitud,
                        'tipo' => 'UPDATE',
                        'anterior' => $justificacionActual,
                        'nuevo' => $justificacionNueva,
                        'col_afect' => 'justificacion',
                        'tabla' => 'solpagos',
                        'fecha_creacion' => date('Y-m-d H:i:s'),
                        'creado_por' => $this->session->userdata("inicio_sesion")['id'] // ID de usuario logueado
                    );
                    $this->db->insert('auditoria', $datosInsert);
                    log_sistema($this->session->userdata("inicio_sesion")['id'], $this->input->post("idsolicitud"), "SE EDITÓ LA JUSTIFICACIÓN DE LA SOLICITUD");
                }
            }
        }
        /** FIN FECHA: 04-MARZO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
       
        echo json_encode(array("data" => 'OK'));
    }

    public function obtenerProveedoresNombre(){
        if (isset($_POST) && !empty($_POST)){
            $data = $this->M_Devolucion_Traspaso->obtenerProveedoresPorNombre($this->input->post('nombreProveedor'))->result_array();

            echo json_encode(array("data" => $data));
        };
    }

    public function historialParcialidadesDesglosado($data){
        
        $docInfo = json_decode(urldecode($data));
        $reader = new PhpOffice\PhpSpreadsheet\Spreadsheet();
        $encabezados = [
            'font' => [
                'color' => [ 'argb' => '00000000' ],
                'bold'  => true,
                'size'  => 13,
            ],

            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,],
            'borders' => [
                'top' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
                'bottom' => [
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

        $encabezadosPagos = [
            'font' => [
                'color' => [ 'argb' => '00000000' ],
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
                    'bottom' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                ],
            'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => [
                'rgb' => 'F5FFFA'
            ]
            ],
            ];

        $informacion = [
            'borders' => [
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],               
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'F0F8FF'
                ]
            ],
        ];
        
        $informacionPagos = [
            'borders' => [
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ]
        ];
        
        
        $solicitudes = $this->M_Devolucion_Traspaso->obtenerHistrialParcialidades($docInfo->fechaInicial, $docInfo->fechaFinal, $docInfo->tipoReporte);
        $ids = [];

        foreach ($solicitudes->result() as $solicitud) {
            $ids[] = $solicitud->idsolicitud;
        }

        $pagos = $this->M_Devolucion_Traspaso->obtenerHistrialParcialidadesDesglosado($ids);

        $i = 1;
        
        $reader->getActiveSheet()->setCellValue('A'.$i, 'SOLICITUD');
        $reader->getActiveSheet()->setCellValue('B'.$i, 'PERIODO');
        $reader->getActiveSheet()->setCellValue('C'.$i, 'PROVEEDOR');
        $reader->getActiveSheet()->setCellValue('D'.$i, 'LOTE');
        $reader->getActiveSheet()->setCellValue('E'.$i, 'TOTAL');
        $reader->getActiveSheet()->setCellValue('F'.$i, 'FECHA AUT.');
        $reader->getActiveSheet()->setCellValue('G'.$i, 'PARCIALIDAD');
        $reader->getActiveSheet()->setCellValue('H'.$i, 'PAGADO');
        $reader->getActiveSheet()->setCellValue('I'.$i, 'PAGOS');
        $reader->getActiveSheet()->setCellValue('J'.$i, 'FECHA INICIO');
        $reader->getActiveSheet()->setCellValue('K'.$i, 'FECHA FINAL');
        $reader->getActiveSheet()->setCellValue('L'.$i, 'PROXIMO PAGO');
        $reader->getActiveSheet()->setCellValue('M'.$i, 'ESTATUS');

        $reader->getActiveSheet()->getStyle('A1:M1')->applyFromArray($encabezados);
        $reader->getActiveSheet()->getDefaultColumnDimension()->setWidth(18);
        $reader->getActiveSheet()->getColumnDimension('C')->setWidth(30);
        $reader->getActiveSheet()->getColumnDimension('D')->setWidth(30);
        

        $i+=1;
        
        if( $solicitudes->num_rows() > 0  ){
            foreach( $solicitudes->result() as $row ){
                $reader->getActiveSheet()->setCellValue('A'.$i, $row->idsolicitud);
                $reader->getActiveSheet()->setCellValue('B'.$i, $row->periodo);
                $reader->getActiveSheet()->setCellValue('C'.$i, $row->proveedor);
                $reader->getActiveSheet()->setCellValue('D'.$i, $row->lote);
                $reader->getActiveSheet()->setCellValue('E'.$i, $row->cantidad);
                $reader->getActiveSheet()->setCellValue('F'.$i, $row->fecha_autorizacion);
                $reader->getActiveSheet()->setCellValue('G'.$i, $row->parcialidad );    
                $reader->getActiveSheet()->setCellValue('H'.$i, $row->pagado);
                $reader->getActiveSheet()->setCellValue('I'.$i, $row->pagos.'/'.$row->totalPagos); 
                $reader->getActiveSheet()->setCellValue('J'.$i, $row->fechaInicio);
                $reader->getActiveSheet()->setCellValue('K'.$i, $row->fechaFin); 
                $reader->getActiveSheet()->setCellValue('L'.$i, $row->proximo_pago);
                $reader->getActiveSheet()->setCellValue('M'.$i, $row->etapa);
                $reader->getActiveSheet()->getStyle('A'.$i.':M'.$i.'')->applyFromArray($informacion);

                $i+=1;

                $idsolicitud = $row->idsolicitud;

                $pagosSolicitud = array_filter($pagos->result(), function($item) use ($idsolicitud) {
                    return $item->idsolicitud == $idsolicitud;
                });

                
                if($pagos->num_rows() > 0){
                    $reader->getActiveSheet()->setCellValue('B'.$i, 'PAGO');
                    $reader->getActiveSheet()->setCellValue('C'.$i, 'AUXILIAR PV');
                    $reader->getActiveSheet()->setCellValue('D'.$i, 'METODO PAGO');
                    $reader->getActiveSheet()->setCellValue('E'.$i, 'CANTIDAD');
                    $reader->getActiveSheet()->setCellValue('F'.$i, 'FECHA PV');
                    $reader->getActiveSheet()->setCellValue('G'.$i, 'FECHA DISPERCIÓN');
                    $reader->getActiveSheet()->setCellValue('H'.$i, 'FECHA COBRO.');
                    $reader->getActiveSheet()->setCellValue('I'.$i, 'ESTATUS');
                    $reader->getActiveSheet()->getStyle('B'.$i.':I'.$i.'')->applyFromArray($encabezadosPagos);
                    $i+=1;
                    $cont = 0;
                    foreach( $pagosSolicitud as $row ){
                        $reader->getActiveSheet()->setCellValue('B'.$i, $row->idpago);
                        $reader->getActiveSheet()->setCellValue('C'.$i, $cont == 0 ? $row->usuarioSP : $row->usuarioPV);
                        $reader->getActiveSheet()->setCellValue('D'.$i, $row->tipoPago);
                        $reader->getActiveSheet()->setCellValue('E'.$i, number_format($row->cantidad, 2, '.', ''));
                        $reader->getActiveSheet()->setCellValue('F'.$i, $row->fechaPV);
                        $reader->getActiveSheet()->setCellValue('G'.$i, $row->fechaDis);
                        $reader->getActiveSheet()->setCellValue('H'.$i, $row->fecha_cobro);
                        $reader->getActiveSheet()->setCellValue('I'.$i, $row->etapa_pago );    

                        $reader->getActiveSheet()->getStyle('B'.$i.':I'.$i.'')->applyFromArray($informacionPagos);
                        $i+=1;
                        $cont++;
                    }
                }


            }
        }

        $writer =  \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($reader, 'Xls');
        ob_end_clean();

        $temp_file = tempnam(sys_get_temp_dir(), 'PHPExcel');
        $writer->save($temp_file);
        header("Content-Disposition: attachment; filename=REPORTE_HISTORIAL_PARCIALIDADES_".date("d-m-y").".xls");

        readfile($temp_file); 
        unlink($temp_file);
    }

    public function historialParcialidadesDesglosadoPagos($data){
        
        $docInfo = json_decode(urldecode($data));
        $reader = new PhpOffice\PhpSpreadsheet\Spreadsheet();
        $encabezados = [
            'font' => [
                'color' => [ 'argb' => '00000000' ],
                'bold'  => true,
                'size'  => 13,
            ],

            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,],
            'borders' => [
                'top' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
                'bottom' => [
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

        $encabezadosPagos = [
            'font' => [
                'color' => [ 'argb' => '00000000' ],
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
                    'bottom' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                ],
            'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => [
                'rgb' => 'F5FFFA'
            ]
            ],
            ];

        $informacion = [
            'borders' => [
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],               
            ]
        ];
        
        $informacionPagos = [
            'borders' => [
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ]
        ];
        
        
        $solicitudes = $this->M_Devolucion_Traspaso->obtenerHistrialParcialidadesPagosDesglosado($docInfo->fechaInicial, $docInfo->fechaFinal, $docInfo->tipoReporte);
        
        $i = 1;

        $reader->getActiveSheet()->setCellValue('A'.$i, '# PAGO');
        $reader->getActiveSheet()->setCellValue('B'.$i, 'SOLICITUD');
        $reader->getActiveSheet()->setCellValue('C'.$i, 'PERIODO');
        $reader->getActiveSheet()->setCellValue('D'.$i, 'PROVEEDOR');
        $reader->getActiveSheet()->setCellValue('E'.$i, 'LOTE');
        $reader->getActiveSheet()->setCellValue('F'.$i, 'TOTAL');
        $reader->getActiveSheet()->setCellValue('G'.$i, 'METODO PAGO');
        $reader->getActiveSheet()->setCellValue('H'.$i, 'FECHA AUT.');
        $reader->getActiveSheet()->setCellValue('I'.$i, 'PARCIALIDAD');
        $reader->getActiveSheet()->setCellValue('J'.$i, 'FECHA PV');
        $reader->getActiveSheet()->setCellValue('K'.$i, 'FECHA DIS');
        $reader->getActiveSheet()->setCellValue('L'.$i, 'FECHA COBRO');
        $reader->getActiveSheet()->setCellValue('M'.$i, 'ESTATUS');
        
        $reader->getActiveSheet()->getStyle('A1:M1')->applyFromArray($encabezados);
        
        $i+=1;
        
        if( $solicitudes->num_rows() > 0  ){
            foreach( $solicitudes->result() as $row ){
                $reader->getActiveSheet()->setCellValue('A'.$i, $row->idpago);
                $reader->getActiveSheet()->setCellValue('B'.$i, $row->idsolicitud);
                $reader->getActiveSheet()->setCellValue('C'.$i, $row->periodo);
                $reader->getActiveSheet()->setCellValue('D'.$i, $row->proveedor);
                $reader->getActiveSheet()->setCellValue('E'.$i, $row->lote);
                $reader->getActiveSheet()->setCellValue('F'.$i, $row->cantidad);
                $reader->getActiveSheet()->setCellValue('G'.$i, $row->tipoPago);
                $reader->getActiveSheet()->setCellValue('H'.$i, $row->fecha_autorizacion);
                $reader->getActiveSheet()->setCellValue('I'.$i, $row->parcialidad );  
                $reader->getActiveSheet()->setCellValue('J'.$i, $row->fechaPV );  
                $reader->getActiveSheet()->setCellValue('K'.$i, $row->fechaDis);
                $reader->getActiveSheet()->setCellValue('L'.$i, $row->fecha_cobro); 
                $reader->getActiveSheet()->setCellValue('M'.$i, $row->etapa_pago);
                $reader->getActiveSheet()->getStyle('A'.$i.':M'.$i.'')->applyFromArray($informacion);

                $i+=1;

            }
        }

        $writer =  \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($reader, 'Xls');
        ob_end_clean();

        $temp_file = tempnam(sys_get_temp_dir(), 'PHPExcel');
        $writer->save($temp_file);
        header("Content-Disposition: attachment; filename=REPORTE_HISTORIAL_PARCIALIDADES_".date("d-m-y").".xls");

        readfile($temp_file); 
        unlink($temp_file);
    }
    /**
     * @uthor Efrain Martinez Muñoz | Fecha : 27/03/2025
     * Función que permite cargar la imagen del estatus del lote en caso de que el usuario seleccione el check en construcción,
     * esta función se manda llamar de diferentes vistas en las cuales el usuario va a poder cargar la imagen, en primera instancia el usuario debera seleccionar
     * el estatus de lote, es decir si es lote baldío o con construcción a partir de esta seleccion se guradas los datos ingresados en la base de datos,
     * ya sea que solo se haya cargado la imagen, que solo se haya seleccionado el estatus de lote o las dos al mismo tiempo.
     */
    public function guardarImagen() {
      
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            // Se valida que se haya seleccionado un check en la vista
            if(isset($_POST["estatus_lote"])){
                $estatusLote = $_POST["estatus_lote"];
            }
            // Se cachan las variables enviadas al controlador.
            $idSolicitud = $_POST["id_solicitud"];
            $idUsuarioSol = $_POST["idUsuario"];
            $idUsuarioSes = $this->session->userdata("inicio_sesion")['id'];            

            // Crear la carpeta destino si no existe
            $rutaImagen = FCPATH . 'UPLOADS/TEMP/DEVOLUCIONES_TRASPASOS/LOTE/IdUsuario_' . $idUsuarioSol . '/';
            if (!is_dir($rutaImagen)) {
                mkdir($rutaImagen, 0777, true);
            }

            // Configuración de carga de archivos directamente en la carpeta del usuario
            $config = [
                'upload_path'   => $rutaImagen,
                'allowed_types' => 'jpg|png',
                'overwrite'     => FALSE,
            ];

            // Cargar la librería de subida con la nueva ruta
            $this->load->library('upload', $config);

            // Validar si se subió la imagen
            if (!empty($_FILES["subir_imagen"]["name"]) && $_FILES["subir_imagen"]["error"] == UPLOAD_ERR_OK) {

                if (!$this->upload->do_upload('subir_imagen')) {
                    echo json_encode('fallo');
                    exit;
                }

                // Extraer la información de la imagen subida
                $imagen_subida = $this->upload->data();
                $extension = strtolower(pathinfo($imagen_subida["file_name"], PATHINFO_EXTENSION));
                
                // Generar nuevo nombre
                $nuevoNombre = "img_lote_solicitud_" . $idSolicitud . ".jpg"; // Siempre .jpg
                $nuevaRuta = $rutaImagen . $nuevoNombre;

                // Procesar si es PNG (convertir a JPG)
                if ($extension == 'png') {
                    $imagen = imagecreatefrompng($imagen_subida['full_path']);
                    if ($imagen) {
                        imagejpeg($imagen, $nuevaRuta, 90); // Guardar como JPG con calidad 90
                        imagedestroy($imagen);
                        unlink($imagen_subida['full_path']); // Eliminar el PNG original
                        chmod($nuevaRuta, 0644);
                    } else {
                        echo json_encode(["status" => "error", "message" => "Error al convertir la imagen."]);
                        exit;
                    }
                } else {
                    // Si ya es JPG, simplemente renombrar
                    if (!rename($imagen_subida['full_path'], $nuevaRuta)) { 
                        if (!copy($imagen_subida['full_path'], $nuevaRuta) || !unlink($imagen_subida['full_path'])) {
                            echo json_encode(["status" => "error", "message" => "Error al subir la imagen."]);
                            exit;
                        }
                        chmod($nuevaRuta, 0644);
                    }
                }

                // Verificar que el archivo final exista
                if (!file_exists($nuevaRuta)) {
                    echo json_encode('Sin imagen');
                    exit;
                }

                // Insertar en historial_documento
                $insertDc = [
                    "movimiento" => 'Imagen de estatus de lote.',
                    "expediente" => $nuevoNombre,
                    "modificado" => null,
                    "idmodificado" => null,
                    "estatus" => 1,
                    "idSolicitud" => $idSolicitud, 
                    "idUsuario" => $idUsuarioSol,
                    "tipo_doc" => 9
                ];
                $this->db->insert("historial_documento", $insertDc);

                // Insertar en logs
                $insertLogsEs = [
                    "idsolicitud" => $idSolicitud,
                    "tipomov" => "SE CARGO LA IMAGEN DEL ESTATUS DEL LOTE.",
                    "idusuario" => $idUsuarioSes,
                    "fecha" => date("Y-m-d H:i:s")
                ];
                $this->db->insert("logs", $insertLogsEs);

                // Insertar en etiqueta_sol si aplica
                if (isset($estatusLote)) {
                    $insertEtiSol = [
                        "idetiqueta" => $estatusLote,
                        "idsolicitud" => $idSolicitud,
                        "rol" => 'PV'
                    ];
                    $this->db->insert("etiqueta_sol", $insertEtiSol);

                    $insertLogsEs = [
                        "idsolicitud" => $idSolicitud,
                        "tipomov" => "SE CARGO EL ESTATUS DEL LOTE.",
                        "idusuario" => $idUsuarioSes,
                        "fecha" => date("Y-m-d H:i:s")
                    ];
                    $this->db->insert("logs", $insertLogsEs);
                }

                echo json_encode(["status" => "success"]);
            }

            // Se valida si la variable $estatusLote existe se inserta un registro en la tabla etiqueta_sol, de igual manera se realiza un registro en el historial
            // de la solicitud con el registro del movimiento realizado. 
            else if (isset($estatusLote)) { 
                $insertEtiSol = [
                    "idetiqueta" => $estatusLote,
                    "idsolicitud" => $idSolicitud,
                    "rol" => 'PV'
                ];
                $this->db->insert("etiqueta_sol", $insertEtiSol);
                $insertLogsEs = [
                    "idsolicitud" => $idSolicitud,
                    "tipomov" => "SE CARGO EL ESTATUS DEL LOTE.",
                    "idusuario" => $idUsuarioSes,
                    "fecha" => date("Y-m-d H:i:s")
                ];
                $this->db->insert("logs", $insertLogsEs);

                echo json_encode(["status" => "success"]);
            }
        }
    }

     /**
     * @uthor Efrain Martinez Muñoz | Fecha : 27/03/2025
     * Función que permite actualizar la imagen, la cual se llama de diferentes vistas
     */
    public function actualizarImagen(){
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            // Se capturan los datos enviados a función.
            $idSolicitud = $_POST["idsolicitud"];
            $idDocumento = $_POST["idDocumento"];
            $idUsuario = $_POST["idUsuario"];            
        
            // Configuración de carga de archivos
            $config = [
                'upload_path'   => FCPATH . 'UPLOADS/TEMP/DEVOLUCIONES_TRASPASOS/LOTE/',
                'allowed_types' => 'jpg|png',
                'overwrite'     => FALSE,
            ];
            // Se carga la libreria para subir el archivo a la ruta creada anteriormente
            $this->load->library('upload', $config);
        
            // Se valida si existe una imagen y si se subio correctamente
            if (!empty($_FILES["imagen"]["name"]) && $_FILES["imagen"]["error"] == UPLOAD_ERR_OK) {
                $archivoOriginal = $_FILES['imagen']['tmp_name'];
                $info = getimagesize($archivoOriginal);
            
                // Definir la ruta donde se guardará la imagen
                $rutaImagen = FCPATH . 'UPLOADS/TEMP/DEVOLUCIONES_TRASPASOS/LOTE/IdUsuario_' . $idUsuario . '/';
            
                // Forzar la extensión a ".jpg"
                $nuevoNombre = "img_lote_solicitud_" . $idSolicitud . ".jpg";
                $nueva_ruta = $rutaImagen . $nuevoNombre;
                // Si el archivo es PNG, convertirlo a JPG antes de subirlo
                if ($info['mime'] == 'image/png') {
                    $imagen = imagecreatefrompng($archivoOriginal);
                    imagejpeg($imagen, $nueva_ruta, 90); // Guardar como JPG con calidad 90%
                    imagedestroy($imagen);
                    $archivoOriginal = $nueva_ruta; // Ahora usamos la imagen convertida como archivo final
                } else {
                    // Si no es PNG, solo mover el archivo original a la nueva ruta
                    if (!move_uploaded_file($archivoOriginal, $nueva_ruta)) {
                        echo json_encode(["status" => "error", "message" => "Error al guardar la imagen."]);
                        exit;
                    }
                }
            
                // Actualizar en la base de datos
                $this->db->update("historial_documento", [
                    "modificado" => date("Y-m-d H:i:s"),
                    "idmodificado" => $idUsuario,
                    "expediente" => $nuevoNombre
                ], "idDocumento = $idDocumento");
            
                // Registrar en logs
                $insertLogsEs = [
                    "idsolicitud" => $idSolicitud,
                    "tipomov" => "SE ACTUALIZÓ LA IMAGEN DEL ESTATUS DEL LOTE.",
                    "idusuario" => $idUsuario,
                    "fecha" => date("Y-m-d H:i:s")
                ];
                $this->db->insert("logs", $insertLogsEs);
            
                echo json_encode(["status" => "success"]);
            }
             
        }
    }

    // Función que permite elimiar los archivos temporales de la solictud al cerrar el modal 
    public function eliminarDirectorio() {
        // Se obtiene el id de la solicitud enviada en el metodo get.
        $idsolicitud = $this->input->get('idsolicitud');
        // Se crea la ruta local de la carpeta donde se encuentran los archivos de la solicitud.
        $rutaLocal = FCPATH . 'UPLOADS/TEMP/' . $idsolicitud;
        
        if (file_exists($rutaLocal)) {
            // Llamada a la función recursiva para eliminar los archivos y subdirectorios dentro del directorio
            $this->eliminarArchivos($rutaLocal);
    
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Archivo no encontrado"]);
        }
    }
    
    // Función recursiva para eliminar directorios y su contenido
    private function eliminarArchivos($dirPath) {
        if (!is_dir($dirPath)) {
            return false;  // No es un directorio
        }
        // Obtener todos los archivos y subdirectorios dentro del directorio
        $files = array_diff(scandir($dirPath), array('.', '..'));
    
        foreach ($files as $file) {
            $filePath = $dirPath . DIRECTORY_SEPARATOR . $file;
            // Llamar recursivamente para eliminar los archivos o subdirectorios
            is_dir($filePath) ? $this->eliminarArchivos($filePath) : unlink($filePath);
        }
        // Eliminar el directorio vacío
        return rmdir($dirPath);
    }
    
}
