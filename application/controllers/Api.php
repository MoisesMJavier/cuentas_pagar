<?php

date_default_timezone_set('America/Mexico_City');

if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once './application/libraries/JWT/src/JWT.php';
require_once './application/libraries/JWT/src/Key.php';

use \Firebase\JWT\JWT;
//libreria para la creación de excel.
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use \Firebase\JWT\Key;

class Api extends CI_Controller
{
    private $clave_secreta = '';
    // Se creará un array para identificar cada uno de los tokens generados por el mismo usuario pero que correspondan a diferentes catálogos. Por ejemplo, un mismo usuario puede tener acceso a distintos catálogos, como Catálogo 1, Catálogo 2, etc. Este array permitirá identificar claramente a qué catálogo se refiere según el acceso indicado en las cabeceras.
    private $identificadores = ['cat_pro_ofi_serv' => ""];

    private $urlXML = 'https://cuentas.gphsis.com/UPLOADS/XMLS/';

    private $nodosPadre = [
        "cfdi:Comprobante",
        "cfdi:Emisor",
        "cfdi:Receptor",
        "cfdi:CfdiRelacionados",
        "cfdi:Conceptos",
        "cfdi:Complemento"
        // "cfdi:InformacionGlobal",
        // "cfdi:Impuestos"
    ];
    
    private $nodosCfdiBD = [
        "cfdi:Comprobante"      =>  ["Version"				=>	"version_xml",
                                     "Serie"				=>	"serie_xml",
                                     "Folio"				=>	"folio_xml",
                                     "Fecha"				=>	"fecha_emision_xml",
                                     "FormaPago"			=>	"forma_pago_xml",
                                     "CondicionesDePago"	=>	"condiciones_pago_xml", // ESTE DATO PUDIERA O NO ESTAR
                                     "SubTotal"				=>	"subtotal_xml",
                                     "Descuento"			=>	"descuento_xml", // ESTE DATO PUDIERA O NO ESTAR
                                     "Moneda"				=>	"moneda_xml",
                                     "TipoCambio"			=>	"tipo_cambio_xml",
                                     "Total"				=>	"total_xml",
                                     "TipoDeComprobante"	=>  "tipo_comprobante_xml",
                                     "Exportacion"			=>	"exportacion_xml",
                                     "MetodoPago"			=>	"metodo_pago_xml"],

        "cfdi:Emisor"           =>  ["Rfc"				=>	"rfc_emisor_xml",
                                     "Nombre"			=>	"nombre_emisor_xml",
                                     "RegimenFiscal"	=>	"regimen_fiscal_emisor_xml"],

        "cfdi:Receptor"         =>  ["Rfc"						=>	"rfc_receptor_xml",
                                     "Nombre"					=>	"nombre_receptor_xml",
                                     "UsoCFDI"					=>	"uso_cfdi_receptor_xml",
                                     "DomicilioFiscalReceptor"	=>	"domicilio_fiscal_receptor_xml",
                                     "RegimenFiscalReceptor"	=>	"regimen_fiscal_receptor_xml"],

        "cfdi:Concepto"     	=>  ["ClaveProdServ"		=>	"clave_prod_serv_conceptos_xml",
                                 	 "NoIdentificacion"		=>	"no_identificacion_conceptos_xml",
                                 	 "Cantidad"				=>	"cantidad_conceptos_xml",
                                	 "ClaveUnidad"			=>	"clave_unidad_conceptos_xml",
                                 	 "Unidad"				=>	"unidad_conceptos_xml",
                                 	 "Descripcion"			=>	"descripcion_conceptos_xml",
                                 	 "ValorUnitario"		=>	"valor_unitario_conceptos_xml",
                                 	 "Importe"				=>	"importe_conceptos_xml",
                                 	 "Descuento"			=>	"descuento_concepto_xml",
                                 	 "ObjetoImp"			=>	"objeto_imp_conceptos_xml"],

        "cfdi:CfdiRelacionado"	=>	["UUID"			=>	"uuid_cfdi_relacionado_xml",
                                     "TipoRelacion"	=>	"tipo_relacion_cfdi_relacionado_xml"],

        "tfd:TimbreFiscalDigital"	=>	["UUID"				=>	"uuid_xml",
										 "FechaTimbrado"	=>	"fecha_timbre_xml"]
        ];

    private $nodosEspecialesCfdiBD = [
        "cfdi:Traslado"         =>	["Base"			=>	"base_concepto_impuestos_traslado",
									 "Impuesto"		=>	"impuesto_concepto_impuestos_traslado",
									 "TipoFactor"	=>	"tipo_factor_concepto_impuestos_traslado",
									 "TasaOCuota"	=>	"tasa_o_cuota_concepto_impuestos_traslado",
									 "Importe"		=>	"importe_concepto_impuestos_traslado"],

		"cfdi:Retencion"	    =>	["Base"			=>	"base_concepto_impuestos_retenciones",
									 "Impuesto"		=>	"impuesto_concepto_impuestos_retenciones",
									 "TipoFactor"	=>	"tipo_factor_concepto_impuestos_retenciones",
									 "TasaOCuota"	=>	"tasa_o_cuota_concepto_impuestos_retenciones",
									 "Importe"		=>	"importe_concepto_impuestos_retenciones"]
    ];
    private $datosXml = [];

    private $urlCloudFuncionXlsx = "https://us-central1-cuentas-x-pagar-431918.cloudfunctions.net/Funcion-Prueba";
    
    public function __construct(){
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: Content-Type,Origin, authorization, X-API-KEY');
        parent::__construct();
        date_default_timezone_set('America/Mexico_City');
        $this->load->model(array('Api_model'));
        $this->load->library('ExtraerNodoXml');
        $this->ExtraerNodoXml = new ExtraerNodoXml();
    }
    
    /*function enviarPreSolicitud() {
        $data = json_decode(file_get_contents("php://input"));
        if($data === null){
            echo json_encode(array("status" => 400, "message" => "Los parámetros no han sido definidos y/o declarados."), JSON_UNESCAPED_UNICODE);
        }else{
            $paramObli = ["USUARIO", "IDEMPRESA", "TOTAL", "FORMAPAGO", "REFERENCIA", "JUSTIFICACION", "FECHAPAGO", "IDBANCO", "IDPROV", "CLABE", "RFC", "EMAIL"];
            $paramFaltantes = [];
            foreach($paramObli as $parametro){
                if(!property_exists($data, $parametro) || empty($data->$parametro) || $data->$parametro == ''){
                    $paramFaltantes[] = $parametro;
                }
            }
            if(!empty($paramFaltantes)){
                $msg = "Los siguientes parámetros no han sido declarados, definidos y/o no tienen un valor especificado. Por favor, verifica que los parámetros: ".implode(', ', $paramFaltantes)." contengan un valor específico o estén correctamente definidos.";
                echo json_encode(array("status" => 400, "message" => $msg), JSON_UNESCAPED_UNICODE);
            }
            else{
                $datosUsuario = $this->Api_model->obtenerInfoUsuario($data->USUARIO)->row();
                $caja_chica = 0; // HAY QUE DEFINIR SI SE ESTARA RECIBIENDO POR SERVICIO
                if ($caja_chica == 0) {
                    $idResponsable = $datosUsuario->rol == 'DA' 
                        ? $datosUsuario->idusuario
                        : $datosUsuario->da;
                    $nomdepto = $datosUsuario->depto;
                    $rolUsuario = $datosUsuario->rol;
                }
                
                $data = array(
                    "idEmpresa" => $data->IDEMPRESA,
                    "idResponsable" => $idResponsable, // Si no es Solicitud de Caja Chica pondremos el ID del DA del usuario y en caso de que el ID de usuario que da de alta se DA se plasmara su mismo ID de usuario en este campo
                    "idusuario" => $data->USUARIO, // Falta por definir en la definicion y en datos que mandara Desarrollo 3
                    "nomdepto" => ($rolUsuario == "CP" ? 'ADMINISTRACION' : ( in_array( $rolUsuario, array( 'CE', 'CX' ) ) ? 'DEVOLUCIONES' : $nomdepto )), // Mediante a condiciones se puede extraer los datos del usuario logeado y del tipo de solicitud
                    "idProveedor" => $data->IDPROV, // Se tendra que sacar mediante consulta de acuerdo a los datos que nos proporcionan (CLABE, NOMBRE PROVEEDOR, ETC.)
                    // "tendrafac" => "1",// SE TENDRA QUE DETERMINAR DE ACUERDO AL PROVEEDOR Y DEPARTAMENTO DEL USUARIO QUE VAYA A HACER EL TRAMITE. DE LO CONTRARIO TENDRA QUE QUEDAR NULL
                    "ref_bancaria" => $data->REFERENCIA,
                    "caja_chica" => "0", // DEFINIR CON DESARROLLO 3 POR DEFAULT SE DEJA COMO 0 (PAGO A PROVEEDOR)
                    // "prioridad" => NULL, //NO ES NECESARIO PONER ESTE CAMPO
                    // "programado" => NULL, // NO NECESARIO
                    "fecha_fin" => $data->FECHAPAGO,
                    "cantidad" => $data->TOTAL,
                    // "moneda" => "MXN", // PREGUNTAR A DESARROLLO 3 SI SOLO SERA PAGO EN MONEDA NACIONA MXN
                    "metoPago" => $data->FORMAPAGO,
                    "justificacion" => $data->JUSTIFICACION,
                    // "servicio" => "0", SE TENDRA QUE REVISAR YA QUE EN BASE DE DATOS CUANDO EL CAMPO DE CAJA_CHICA ES 0 ESTE VALOR ES NULL O 0
                    "fecelab" => $data->FECHAPAGO,
                    "idetapa" => "1", // SE DEJARA POR DEFAULT YA QUE ES PARA UNA ETAPA BORRADOR
                    // "orden_compra" => NULL, // revisar ya que este campo es solo para comer y jardineria
                    // "intereses" => NULL //Revisar ya que este es solo para pagos programados.
                    // "crecibo" => NULL, // revisar ya que este campo es solo para comer y jardineria
                    // "requisicion"=> NULL // revisar ya que este campo es solo para comer y jardineria
                );
                
                $dbTransaction = $this->Api_model->insertTable("solpagos", $data);
                if ($dbTransaction) // SUCCESS TRANSACTION
                    echo json_encode(array("status" => 201, "message" => "Se ha guardado con éxito el registro de la solicitud con ID: ".$dbTransaction, "resultado" => $dbTransaction ? true : false), JSON_UNESCAPED_UNICODE);
                else // ERROR TRANSACTION
                    echo json_encode(array("status" => 503, "message" => "Servicio no disponible. El servidor no está listo para manejar la solicitud. Por favor, inténtelo de nuevo más tarde."), JSON_UNESCAPED_UNICODE);
            }
        }
        
    }

    function infoPreCargada() {
        if(@file_get_contents("php://input") == false){
            echo json_encode(array("status" => 503, "message" => "Servicio no disponible. El servidor no está listo para manejar la solicitud. Por favor, inténtelo de nuevo más tarde."), JSON_UNESCAPED_UNICODE);
        }else{
            $data = json_decode(file_get_contents("php://input"));
            if( !isset($data->IDUSUARIO)){
                $msg = "IDUSUARIO";
                echo json_encode(array("status" => 404, "message" => "Se informa que no se encontró declarado alguno de los parámetros solicitados. ".$msg), JSON_UNESCAPED_UNICODE);
            }else {
                if ($data->IDUSUARIO == '' || $data->IDUSUARIO <= 0) {
                    echo json_encode(array("status" => 400, "message" => "Algún parámetro no tiene un valor especificado. Verifique que todos los parámetros contengan un valor especificado."), JSON_UNESCAPED_UNICODE);
                }else{
                    $result = $this->Api_model->getCatPreSol($data->IDUSUARIO);
                    if($result["estatus"]){
                        if (count($result["proveedores"]) == 0) {
                            $status = 404;
                            $msg = "Verifique el ID de usuario, ya que no se encontraron registros relacionados con los datos proporcionados.";
                            echo json_encode(array("status" => $status, "message" => $msg));
                        }else{
                            $status = 200;
                            $msg = "La consulta se ha realizado con éxito.";
                            echo json_encode(array("status" => $status, "message" => $msg, "data" => $result));
                        }
                    }else {
                        echo json_encode(array("status" => 404, "message" => $result["msg"]), JSON_UNESCAPED_UNICODE);
                    }
                    
                }
            }
        }
    }*/

    // function obtenerCatProyectosOficinaServicios() {
    //     /**
    //      * @author Dante Aldair Guerrero Aldana <coordinador6.desarrollo@ciudadmaderas.com>
    //      * Funcion para el envio de catalogo referente a los proyectos/departamentos, Oficinas/sedes y Tipo servicio partida.
    //      */
    //     // usuario:contraseña:acceso codificada en base64: ZDM1NHJyMDExMDE0cHVkOmQvYlBxeG5OM3hKdzltSVhHeEdlNVlEWDBZTnNIMERyR21XVEYxV0EwYXM9OmNhdF9wcm9fb2ZpX3NlcnY=
    //     $cabeceras = $this->input->request_headers();
    //     if (!isset($cabeceras['Authorization'])) {
    //         http_response_code(401);
    //         echo json_encode(array("status" => 401, "message" => "Acceso no autorizado. Por favor, proporcione las credenciales para autenticación."), JSON_UNESCAPED_UNICODE);
    //         exit;
    //     }

    //     $autenticacion = $cabeceras['Authorization'];

    //     $usuario = isset(explode(':', base64_decode($autenticacion))[0])
    //         ? explode(':', base64_decode($autenticacion))[0]
    //         : null;

    //     $contraseña = isset(explode(':', base64_decode($autenticacion))[1])
    //         ? explode(':', base64_decode($autenticacion))[1]
    //         : null;

    //     $acceso = isset(explode(':', base64_decode($autenticacion))[2])
    //         ? explode(':', base64_decode($autenticacion))[2]
    //         : null;

    //     if(!isset($usuario) || !isset($contraseña) || !isset($acceso)){
    //         http_response_code(400);
    //         echo json_encode(array("status" => 400, "message" => "La solicitud no es válida. Por favor, revise los datos enviados."), JSON_UNESCAPED_UNICODE);
    //         exit;
    //     }

    //     $autenticarUsuario = $this->autenticarUsuario($usuario, $contraseña, $acceso);
        
    //     $this->clave_secreta = $autenticarUsuario->llave_token;
    //     $this->identificadores['cat_pro_ofi_serv'] = new Key($this->clave_secreta, 'HS256');

    //     $tokenDecodificado = $this->validarToken($autenticarUsuario->token);

    //     if($tokenDecodificado){
    //         $datosToken = $tokenDecodificado->data;
    //         if( property_exists($datosToken, 'username') && property_exists($datosToken, 'password') && property_exists($datosToken, 'acceso') ){
    //             if($datosToken->username === $autenticarUsuario->usuario && $datosToken->password === $autenticarUsuario->contraseña){
    //                 $respuesta = $this->obtenerCatalogo(); // Una vez generado y actualizado el registro generamos el catalogo ya que en este punto apenas se genero el token y como candado o condicion es que exista el usuario, contraseña y acceso en la tabla correspondiente
    //                 http_response_code(200); // Asignamos un estatus para la respuesta de la pagina 
    //                 echo json_encode(array("status" => 200, "catalogos" => $respuesta), JSON_UNESCAPED_UNICODE); // Imprimimos los resultados del catalogo.
    //                 exit;
    //             }else{
    //                 http_response_code(403);
    //                 echo json_encode(array("status" => 403, "menssage" => "No tienes permiso para acceder a este recurso."), JSON_UNESCAPED_UNICODE);
    //                 exit;
    //             }
    //         }else {
    //             http_response_code(401);
    //             echo json_encode(array("status" => 401, "message" => "Acceso no autorizado. Por favor, proporcione las credenciales para autenticación."), JSON_UNESCAPED_UNICODE);
    //             exit;
    //         }
    //     }else {
    //         if(is_null($autenticarUsuario->token) || is_null($autenticarUsuario->token_expiracion)){
            
    //             $token = $this->generarToken($usuario, $contraseña, $acceso); // Generamos un token nuevo en caso de que no exista alguno o algun tiempo de expiracion
    //             $this->actualizarTokenBD($autenticarUsuario->id_token, $token); // Actualizamos estos datos en la base de datos.
    //             $respuesta = $this->obtenerCatalogo(); // Una vez generado y actualizado el registro generamos el catalogo ya que en este punto apenas se genero el token y como candado o condicion es que exista el usuario, contraseña y acceso en la tabla correspondiente
    //             http_response_code(200); // Asignamos un estatus para la respuesta de la pagina 
    //             echo json_encode(array("status" => 200, "catalogos" => $respuesta), JSON_UNESCAPED_UNICODE); // Imprimimos los resultados del catalogo.
    //             exit;
    //         }
            
    //         $expiracionToken = isset($autenticarUsuario->token_expiracion) || !is_null($autenticarUsuario->token_expiracion)
    //             ? $autenticarUsuario->token_expiracion
    //             : null;
    
    //         if ( is_null($expiracionToken) || $this->verificarExpiracionToken($expiracionToken)) {
    //             $token = $this->generarToken($usuario, $contraseña, $acceso);
    //             $this->actualizarTokenBD($autenticarUsuario->id_token, $token); // Actualizamos estos datos en la base de datos.
    //             $respuesta = $this->obtenerCatalogo(); // Una vez generado y actualizado el registro generamos el catalogo ya que en este punto apenas se genero el token y como candado o condicion es que exista el usuario, contraseña y acceso en la tabla correspondiente
    //             http_response_code(200); // Asignamos un estatus para la respuesta de la pagina 
    //             echo json_encode(array("status" => 200, "catalogos" => $respuesta), JSON_UNESCAPED_UNICODE); // Imprimimos los resultados del catalogo.
    //             exit;
    //         }
    //     }

    // }

    public function obtenerCatProyectosOficinaServicios() {
        $idUsuario = $this->Api_model->validar_usuario($this->input->post("usuario"), $this->input->post("pass"));
        if ($this->input->post("usuario") != null && $this->input->post("pass") != null && $idUsuario !== FALSE) {
            $respuesta = $this->obtenerCatalogo(); // Una vez generado y actualizado el registro generamos el catalogo ya que en este punto apenas se genero el token y como candado o condicion es que exista el usuario, contraseña y acceso en la tabla correspondiente
            http_response_code(200); // Asignamos un estatus para la respuesta de la pagina 
            echo json_encode(array("status" => 200, "catalogos" => $respuesta), JSON_UNESCAPED_UNICODE); // Imprimimos los resultados del catalogo.
            exit;
        } else {
            http_response_code(401);
            echo json_encode(array("status" => 401, "message" => "Acceso no autorizado. Por favor, proporcione las credenciales para autenticación."), JSON_UNESCAPED_UNICODE);
            exit;
        }
    }

    private function autenticarUsuario($usuario, $contraseña, $acceso) {
        $datos_token_bd = $this->Api_model->obtenerUsuarioContraseñaToken($usuario, encriptar($contraseña), $acceso)->row();
        if(!isset($datos_token_bd)){
            http_response_code(403);
            echo json_encode(array("status" => 403, "menssage" => "No tienes permiso para acceder a este recurso."), JSON_UNESCAPED_UNICODE);
            exit;
        }
        return $datos_token_bd;
    }

    private function verificarExpiracionToken($tokenExpiracion) {
        return strtotime($tokenExpiracion) < (date('I', time()) ? time() - 3600 : time());
    }

    private function actualizarTokenBD($id_token, $token) {
        return $this->Api_model->actualizarRegToken($id_token, $token);
    }

    private function validarToken($token) {
        if ($token == null) {
            return false;
        }
        try {
            $decodificado = JWT::decode($token, $this->identificadores);
            return $decodificado;
        } catch (Exception $e) {
            return false;
        }
    }

    private function generarToken($usuario, $contraseña, $acceso){
        $tiempo = date('I', time()) ? time() - 3600 : time(); // Quitamos hora de mas si es que es horario de verano
        $tiempoExp = $tiempo + (24 * 60 * 60); // Asignamos un tiempo de expiracion
        $JwtSecretKey = $this->clave_secreta; // Asignamos la llave o clave secreta que obtenemos de la BD
        $data = array(
            "iat" => $tiempo, // Tiempo en que inició el token
            "exp" => $tiempoExp, // Tiempo en el que expirará el token (24 horas)echo json_encode(array("id_token" => $token));
            "data" => array(
                "username" => $usuario, 
                "password" => encriptar($contraseña),
                "acceso"  =>  $acceso)
        );
        $token = JWT::encode($data, $JwtSecretKey, 'HS256', $acceso);
        return array("id_token" => $token, "tipo_expiracion" => $tiempoExp);
    }

    private function obtenerCatalogo() {
        $catProyectosDepartamentos = $this->Api_model->obtenerCatalogoProDepto()->result_array();
        $catTipoServiciopartida = $this->Api_model->obtenerCatalogoTipoServPart()->result_array();
        $catalogos["servicio_partida"] = $catTipoServiciopartida;
        $proyectos = [];
        $oficinas = [];
        
        foreach ($catProyectosDepartamentos as $valor) {

            if (!array_key_exists($valor["idProyectos"]-1, $proyectos)) {
                $oficinas = [];
             }
            
            $oficinas[] = array("idOficina"     =>  $valor["idOficina"],
                                "nombreOficina" =>  $valor["nombreOficina"],
                                "nombreEstado"  =>  $valor["nombreEstado"]);
            $proyectos[$valor["idProyectos"]-1] = array(  "idProyectos"      =>  $valor["idProyectos"],
                                                        "nombreProyecto"   =>  $valor["nombreProyecto"],
                                                        "num_Office"       =>  count($oficinas),
                                                        "oficinas"         =>  $oficinas);
        }
        
        $catalogos["proyectos_deptos"] = array_values($proyectos);
        return $catalogos;
    }
    
     /**
     * @author EFRAIN MARTINEZ MUÑOZ | FECHA: 19/DIC/2024 <programador.analista38@ciudadmaderas.com>
     * Se creo la función procesar_csv dentro de la cual se descarga en archivo CSV que contiene los datos de los contribuyentes que se encuentran
     * en la lista negra del SAT y estos registros se guardan en la tabla proveedores_articulo_69, ademas se actualiza la tabla complemento_69b
     * en la cual contiene los datos de los contribuyentes que su situacion sea presunto o definitivo, por otra parte se realizan dos consultas,
     * una para saber si los proveedores que se encuentran activos estan en alguna se encuentran en la lista negra y la situacion en la que se encuentran,
     * y la otra para saber si existen proveedores bloqueados que en la lista su situacion se encuentra en desvirtuados o sentencia favorable.
     * Ademas se trata cada una de la exceptiones o errores que puedan surgir en cada uno de los procesos de la función.
     */
    public function procesar_csv_lista69b(){
        try {
             //Se extrae la configuracion inicial del debug y se guarda
             $original_db_debug = $this->db->db_debug;
            // URL del Listado completo de contribuyentes 
            $url = 'http://omawww.sat.gob.mx/cifras_sat/Documents/Listado_Completo_69-B.csv';
            $csv_data = file_get_contents($url); // Descargar el archivo CSV
            if(!$csv_data){
                throw new Exception('Error al descargar el archivo CSV desde la dirección proporcionada del SAT.');
             }
            // Ruta donde se guardará el archivo CSV para posteriormente insertar estos registros en la base de datos
            $file_path = FCPATH . 'UPLOADS/TEMP/Listado_Completo_69-B.csv';
            $guardar_archivo = file_put_contents($file_path, $csv_data); // Guardar el archivo en la ruta especificada
            if($guardar_archivo === false){
                throw new Exception ('Error al guardar el archivo CSV descargado');
            }
             // Limpiar las tablas antes de insertar nuevos registros 
             $rTruncarTablas = $this->Api_model->truncarTablas();
                
             if($rTruncarTablas === FALSE){
                 throw new Exception('Error al truncar las tablas');
             }

            if (($archivo = fopen($file_path, 'r')) === FALSE) {
                throw new Exception ('Error al abrir el archivo');
            }
            
            else{
                // Desactivar db_debug temporalmente
                $this->db->db_debug = false;
                
                
                // Saltar las primeras tres filas de encabezado
                fgetcsv($archivo);
                fgetcsv($archivo);
                fgetcsv($archivo);
                $insertar_datos_sat = [];
                $tamaño_lote = 500;

                while (($data = fgetcsv($archivo, 2000, ',')) !== FALSE) {
                    //Decodificar los datos del excel a UTF-8,ISO-8859-1
                    $data = array_map(function ($campo) {
                        $encoding = mb_detect_encoding($campo, ['UTF-8', 'ISO-8859-1'], true);
                        return $encoding ? mb_convert_encoding($campo, 'UTF-8', $encoding) : utf8_encode($campo);
                    }, $data);
                    //Convierte las fechas a date antes de realizar la insercion en la base de datos
                    $fechaPre = $data[7]; // Fecha en formato DD/MM/YYYY
                    $fechaDes = $data[11];
                    $fechaDef = $data[15];
                    $fechaSen = $data[19];
                    // Intentar convertir la fecha
                    $fechaPreDate = DateTime::createFromFormat('d/m/Y', $fechaPre);
                    $fechaDesDate = DateTime::createFromFormat('d/m/Y', $fechaDes);
                    $fechaDefDate = DateTime::createFromFormat('d/m/Y', $fechaDef);
                    $fechaSenDate = DateTime::createFromFormat('d/m/Y', $fechaSen);
                
                    $formatDate = function ($date) {
                        return $date ? $date->format('Y-m-d') : '1900-01-01';
                    };
                    
                    $fechaPreCon = $formatDate($fechaPreDate);
                    $fechaDesCon = $formatDate($fechaDesDate);
                    $fechaDefCon = $formatDate($fechaDefDate);
                    $fechaSenCon = $formatDate($fechaSenDate);

                    if (count($insertar_datos_sat) <= 0) {
                        $insertar_datos_sat = [
                            array(
                            'RFC' => $data[1],
                            'Nombre_Contribuyente' => $data[2],
                            'Situacion_contribuyente' => $data[3],
                            'Numero_Fecha_Oficio_Presuncion_SAT' => $data[4],
                            'Publicacion_Pagina_SAT_Presuntos' => $data[5],
                            'Numero_Fecha_Oficio_Presuncion_DOF' => $data[6],
                            'Publicacion_DOF_Presuntos' => $fechaPreCon,
                            'Numero_Fecha_Oficio_Contribuyentes_Desvirtuaron_SAT' => $data[8],
                            'Publicacion_Pagina_SAT_Desvirtuados' => $data[9],
                            'Numero_Fecha_Oficio_Contribuyentes_Desvirtuaron_DOF' => $data[10],
                            'Publicacion_DOF_Desvirtuados' => $fechaDesCon,
                            'Numero_Fecha_Oficio_Definitivos_SAT' => $data[12],
                            'Publicacion_Pagina_SAT_Definitivos' => $data[13],
                            'Numero_Fecha_Oficio_Definitivos_DOF' => $data[14],
                            'Publicacion_DOF_Definitivos' => $fechaDefCon,
                            'Numero_Fecha_Oficio_Sentencia_Favorable_SAT' => $data[16],
                            'Publicacion_Pagina_SAT_Sentencia_Favorable' => $data[17],
                            'Numero_Fecha_Oficio_Sentencia_Favorable_DOF' => $data[18],
                            'Publicacion_DOF_Sentencia_Favorable' => $fechaSenCon
                        )];
                    }else{
                        array_push($insertar_datos_sat, array(
            
                            'RFC' => $data[1],
                            'Nombre_Contribuyente' => $data[2],
                            'Situacion_contribuyente' => $data[3],
                            'Numero_Fecha_Oficio_Presuncion_SAT' => $data[4],
                            'Publicacion_Pagina_SAT_Presuntos' => $data[5],
                            'Numero_Fecha_Oficio_Presuncion_DOF' => $data[6],
                            'Publicacion_DOF_Presuntos' => $fechaPreCon,
                            'Numero_Fecha_Oficio_Contribuyentes_Desvirtuaron_SAT' => $data[8],
                            'Publicacion_Pagina_SAT_Desvirtuados' => $data[9],
                            'Numero_Fecha_Oficio_Contribuyentes_Desvirtuaron_DOF' => $data[10],
                            'Publicacion_DOF_Desvirtuados' => $fechaDesCon,
                            'Numero_Fecha_Oficio_Definitivos_SAT' => $data[12],
                            'Publicacion_Pagina_SAT_Definitivos' => $data[13],
                            'Numero_Fecha_Oficio_Definitivos_DOF' => $data[14],
                            'Publicacion_DOF_Definitivos' => $fechaDefCon,
                            'Numero_Fecha_Oficio_Sentencia_Favorable_SAT' => $data[16],
                            'Publicacion_Pagina_SAT_Sentencia_Favorable' => $data[17],
                            'Numero_Fecha_Oficio_Sentencia_Favorable_DOF' => $data[18],
                            'Publicacion_DOF_Sentencia_Favorable' => $fechaSenCon
                        ));
                    }
                    // Insertar los datos en bloques de $tamaño_lote
                    if (count($insertar_datos_sat) >= $tamaño_lote) {
                        // Realizar la inserción en la base de datos
                        $this->db->insert_batch('proveedores_articulo_69', $insertar_datos_sat);
        
                        $db_error = $this->db->error();
                        if ($db_error['code'] != 0) { // Si existe una error en la consulta anterior se creara una exception y se mandará un correo
                            throw new Exception('Error al insertar: ' . $db_error['code'] . ' ' . $db_error['message']);
                        } 
                        // Vaciar el arreglo para el siguiente lote
                        $insertar_datos_sat = [];
                    }
                }

                // Insertar los registros restantes que no completaron un lote
                if (count($insertar_datos_sat) > 0) {
                    $this->db->insert_batch('proveedores_articulo_69', $insertar_datos_sat);
                    $db_error = $this->db->error();
                    if ($db_error['code'] != 0) { // Si existe un error en la consulta anterior se creara una exception y se mandará un correo
                        throw new Exception('Error al insertar: ' . $db_error['code'] . ' ' . $db_error['message']);
                    }
                
                }
                // Cerrar el archivo CSV que se descargo del repositorio del SAT
                fclose($archivo); 
                // Se elimina el archivo CSV para evitar generar archivos basura dentro del proyecto
                unlink($file_path); 
            }
            
            $act_complemento_69b = $this->Api_model->obtener_prov_pre_def()->result();
            // Inserción de datos en la tabla complemento_69b
            $tam_lote = 500; // Tamaño del lote
            $insertar_datos = []; // Inicializar el arreglo para los datos

            foreach ($act_complemento_69b as $fila) {
                $insertar_datos[] = [
                    'rfccomplemento' => $fila->RFC,
                    'razonsocial' => 'RZSCND',
                ];

                // Insertar datos en la base de datos en bloques de $tam_lote
                if (count($insertar_datos) >= $tam_lote) {
                    $this->db->insert_batch('complemento_69b', $insertar_datos);
                    $db_error = $this->db->error();
                    if ($db_error['code'] != 0) { // Si existe un error en la consulta anterior se creara una exception y se mandará un correo
                        throw new Exception('Error al insertar: ' . $db_error['code'] . ' ' . $db_error['message']);
                    }
                    $insertar_datos = []; // Vaciar el arreglo para el siguiente lote
                }
            }

            // Insertar los registros restantes que no completaron un lote
            if (!empty($insertar_datos)) {
                $this->db->insert_batch('complemento_69b', $insertar_datos);
                $db_error = $this->db->error();
                if ($db_error['code'] != 0) { // Si existe un error en la consulta anterior se creara una exception y se mandará un correo
                    throw new Exception('Error al insertar: ' . $db_error['code'] . ' ' . $db_error['message']);
                }
            }
            // Consulta de los proveedores que se encuentran en la lista negra del SAT y en el sistema de CPP.
            $obtenerProv69b_cpp = $this->Api_model->obtenerProv69b_cpp()->result();


            foreach($obtenerProv69b_cpp as $proveedores){
                $idProveedoresSAT [] = $proveedores-> idproveedor;

            }
            $idProveedoresSAT = implode(',', $idProveedoresSAT);

            //Consulta de los proveedores que se encuentran en la lista negra del sat y activos en el sistema
            $prov_lista_negra = $this->Api_model->obtener_prov_lista_negra($idProveedoresSAT)->result();           
            $idProveedorArray = [];
            $idProveedorB = [];
            foreach ($prov_lista_negra as $proveedor) {
                $idProveedorB[] = $proveedor->idproveedor;
                $idProveedorArray[] = [
                    'idproveedor' => $proveedor->idproveedor,
                    'idupdate' => 98,
                    'fecha_update' => date('Y-m-d H:i:s'),
                    'observaciones' => 'PROVEEDOR BLOQUEADO POR EL ARTÍCULO 69B, SAT, AL VALIDARLO EN LA TAREA PROGRAMADA MENSUALMENTE',
                    'estatus' => 0,
                ];
            }
            //Actualización del estatus de los proveedores que se encuentran en la lista negra del sat y activos en el sistema
            if ($idProveedorArray) {
                $this->db->update_batch('proveedores', $idProveedorArray, 'idproveedor');
                $db_error = $this->db->error();
                if ($db_error['code'] != 0) { // Si existe un error en la consulta anterior se creara una exception y se mandará un correo
                    throw new Exception('Error al insertar: ' . $db_error['code'] . ' ' . $db_error['message']);
                }
            }

            //Consulta de los proveedores que fueron bloqueados anteriormente por estar en la lista negra del SAT, pero en la última actualización de la 
            //base de datos se encontraron desbloqueados.
            
            $prov_sef_desv = $this->Api_model->prov_bloqueados($idProveedoresSAT)->result();

            $idpro_des_sef_arr = [];
            $idProveedorD = [];
            foreach ($prov_sef_desv as $prov_def) {
                $idProveedorD[] = $prov_def->idproveedor;
                $idpro_des_sef_arr[] = [
                    'idproveedor' => $prov_def->idproveedor,
                    'idupdate' => 98,
                    'fecha_update' => date('Y-m-d H:i:s'),
                    'observaciones' => 'PROVEEDOR DESBLOQUEADO, YA QUE LA RESOLUCIÓN DEL SAT DE ACUERDO A SU SITUACIÓN FISCAL FUE (DESVIRTUADO O SENTENCIA FAVORABLE), ESTO AL VALIDARLO EN LA TAREA PROGRAMADA MENSUALMENTE',
                    'estatus' => $prov_def->anterior,
                ];
            }
            //Actualización del estatus de los proveedores que se encontraron en la consulta anterior.
            if ($idpro_des_sef_arr) {
                $this->db->update_batch('proveedores', $idpro_des_sef_arr, 'idproveedor');
                // Verifica si ocurrió un error
                $db_error = $this->db->error();
                if ($db_error['code'] != 0) { // Si existe un error en la consulta anterior se creara un exception y mandará un correo
                    throw new Exception('Error al insertar: ' . $db_error['code'] . ' ' . $db_error['message']);
                }
            }
     
            $idProveedoresDes_Blo = array_merge($idProveedorB, $idProveedorD);
            $idProveedoresDes_Blo = implode(',', $idProveedoresDes_Blo);
        
            $solRelacionadasProvS = [];
            
            if($idProveedoresDes_Blo){
            $solRelacionadasProvS = $this->Api_model->solRelacionadasProvS($idProveedoresDes_Blo)->result();
            }
            $solicitudesProveedor = [];
            //En esta ciclo se crean los grupos de las solicitudes que pertenecen a cada uno de los proveedores
            foreach ($solRelacionadasProvS as $proveedorSol) {
                $idProveedorS = $proveedorSol->rfc; // Asumiendo que el tipo está en el índice 1

                // Si no existe el tipo en el arreglo, lo creamos
                if (!isset($solicitudesProveedor[$idProveedorS])) {
                    $solicitudesProveedor[$idProveedorS] = [];
                }

                // Agregamos la proveedorSol$proveedorSol al grupo correspondiente
                $solicitudesProveedor[$idProveedorS][] = $proveedorSol;
            }
            $proveedorCambios = array_merge($prov_lista_negra, $prov_sef_desv);
            
            $rutaExcel = $this->generarExcel($proveedorCambios, $obtenerProv69b_cpp, $solicitudesProveedor);
            $resultadoCorreo = $this->enviarCorreoContador($proveedorCambios, $rutaExcel);
            
            $this->db->db_debug = $original_db_debug;

            if ($rutaExcel && $resultadoCorreo) {
                exit;
            } else {
                throw new Exception('Error al generar el excel o al enviar el correo: ' . $db_error['code'] . ' ' . $db_error['message']);
            }

            exit();
        } catch (Exception $e) {
            // Manejo del error

            $fechaTxt = date('Ymd_His');
            $errorLog = FCPATH . 'UPLOADS/TEMP/error_log_' .  $fechaTxt  . '.txt';
            $errorMessage = " Error ocurrido:\n" . $e->getMessage() . "\nEn archivo: " . $e->getFile() . "\nLínea: " . $e->getLine() . "\nFecha: " . date("Y-m-d H:i:s") . "\n";
            file_put_contents($errorLog, $errorMessage);
            // Enviar correo con el archivo de error
            $this->enviarCorreoError($errorLog);
            echo $errorMessage;
        }
    } 

    /**
     * @author EFRAIN MARTINEZ MUÑOZ | FECHA: 19/DIC/2024 <programador.analista38@ciudadmaderas.com>
     * Se crea la función enviarCorreoError la cual permite enviar un correo electronico a los desarrolladores indicando que se la actualización 
     * de los datos no se completo de manera exitosa y dentro del correo se adjunta un archivo txt que describe el error de manera detallada.
     * 
    */
    public function enviarCorreoError($errorLog) {

        $fecha = date('Y');
        // Cargar librería de correo e inicializar configuración
        $this->load->library('email');


        // Configuración del correo electrónico
        $this->email->set_newline("\r\n");
        $this->email->from('noreplay@ciudadmaderas.com', 'SISTEMA CXP');
        $this->email->to('programador.analista38@ciudadmaderas.com, programador.analista18@ciudadmaderas.com');
        $this->email->subject('¡Error al realizar la tarea programada mensualmente!');
        $this->email->message('<!DOCTYPE html>
                                    <html lang="en">
                                    <head>
                                        <meta charset="UTF-8">
                                        <meta name="viewport" content="width=device-width, initial-scale=1.0">
                                        <style>
                                            body {
                                                font-family: Arial, sans-serif;
                                                margin: 0;
                                                padding: 0;
                                                background-color: #f4f4f4;
                                            }
                                            .email-container {
                                                max-width: 600px;
                                                margin: 20px auto;
                                                background-color: #ffffff;
                                                border: 1px solid #dddddd;
                                                border-radius: 8px;
                                                overflow: hidden;
                                                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                                            }
                                            .header {
                                                background-color: #ee4141;
                                                color: #ffffff;
                                                text-align: center;
                                                padding: 20px;
                                            }
                                            .header h1 {
                                                margin: 0;
                                                font-size: 24px;
                                            }
                                            .content {
                                                padding: 20px;
                                                color: #333333;
                                            }
                                            .content p {
                                                margin: 0 0 15px;
                                                line-height: 1.6;
                                            }
                                            .footer {
                                                text-align: center;
                                                background-color: #f4f4f4;
                                                padding: 15px;
                                                font-size: 12px;
                                                color: #888888;
                                            }
                                        </style>
                                    </head>
                                    <body>
                                        <div class="email-container">
                                            <img src="https://www.ciudadmaderas.com/assets/img/logo.png" alt="Ciudad Maderas" title="Ciudad Maderas" style="max-width: 100%; height: auto;">
                                            <!-- Header -->
                                            <div class="header">
                                                <h1>Alerta</h1>
                                            </div>

                                            <!-- Content -->
                                            <div class="content">
                                                <p>Estimado programador:</p>
                                                <p>La tarea programada mensualmente para actualizar los datos de los contribuyentes que se encuentran en la lista negra de SAT no se ejecutó correctamente.</p>
                                                <p>Se adjunta un archivo .txt con los datos específicos del error.</p>
                                            </div>

                                            <!-- Footer -->
                                            <div class="footer">
                                                <p>© ' . $fecha . ' Ciudad Maderas, Departamento de TI | Cuentas por pagar</p>
                                            </div>
                                        </div>
                                    </body>
                                    </html>
                                    ');

        // Se crea una ruta absoluta en la cual se encuentra el archivo .txt que contiene el mensaje y los datos del tipo de error por el cual se genero la exception 
        $ruta_absoluta = realpath($errorLog);
        if ($ruta_absoluta) {
            $this->email->attach($ruta_absoluta);
        }else {
            echo 'No se encontro el archivo en la ruta especificada';
        }
        // Enviar correo
        if ($this->email->send()) {
            echo 'Correo enviado exitosamente';
        } else {
            // Mostrar error si no se envía
            echo $this->email->print_debugger();
        }
        unlink($ruta_absoluta);
    }

    public function generarExcel($proveedorCambios, $obtenerProv69b_cpp, $solicitudesProveedor){

        $spreadsheet = new Spreadsheet();
        //Se crea los estilos del encabezado de las tablas del excel.
        $encabezados = [
          'font' => [
              'color' => ['argb' => 'FFFFFFFF'],
              'bold' => true,
              'size' => 12,
          ],
          'alignment' => [
              'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
          ],
          'borders' => [
              'top' => [
                  'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                  'color' => ['argb' => '000000'],
              ],
              'bottom' => [
                  'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                  'color' => ['argb' => '000000'],
              ],
              'left' => [
                  'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                  'color' => ['argb' => '000000'],
              ],
              'right' => [
                  'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                  'color' => ['argb' => '000000'],
              ],
          ],
          'fill' => [
              'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
              'startColor' => ['argb' => 'FF004488'],
          ],
        ];
        $borde = [
            'borders' => [
                'outline' => [ // SOLO el contorno de la tabla
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, // Borde grueso
                    'color' => ['rgb' => '000000'], // Color negro
                ],
            ],
        ];
        $bordeColumna = [
            'borders' => [
                'left' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
                'right' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ];
      
        $i = 1;
        // Se crea una hoja de excel que contendra los datos de los proveedore que estan en la lista 69-B SAT y se encuentran dados de alta en el sistema de CXP.
        $sheet1 = $spreadsheet->getActiveSheet();
        $sheet1->setTitle('Total proveedores CPP en 69-B'); 
        $sheet1->setCellValue('A'.$i, 'NO');
        $sheet1->setCellValue('B'.$i, 'RFC');
        $sheet1->setCellValue('C'.$i, 'NOMBRE DEL CONTRIBUYENTE');
        $sheet1->setCellValue('D'.$i, 'ESTATUS ACTUAL CPP');
        $sheet1->setCellValue('E'.$i, 'NUEVO ESTATUS CPP');
        $sheet1->setCellValue('F'.$i, 'OBSERVACIONES ACTUALES CPP');
        $sheet1->setCellValue('G'.$i, 'OBSERVACIONES NUEVAS CPP');
        $sheet1->setCellValue('H'.$i, 'SITUACIÓN DEL CONTRIBUYENTE');
        $sheet1->setCellValue('I'.$i, 'NÚMERO Y FECHA DE OFICIO GLOBAL DE PRESUNCIÓN SAT');
        $sheet1->setCellValue('J'.$i, 'PUBLICACIÓN PÁGINA SAT PRESUNTOS');
        $sheet1->setCellValue('K'.$i, 'NÚMERO Y FECHA DE OFICIO GLOBAL DE PRESUNCIÓN DOF');
        $sheet1->setCellValue('L'.$i, 'PUBLICACIÓN DOF PRESUNTOS');
        $sheet1->setCellValue('M'.$i, 'NÚMERO Y FECHA DE OFICIO GLOBAL DE CONTRIBUYENTES QUE DESVIRTUARON SAT');
        $sheet1->setCellValue('N'.$i, 'PUBLICACIÓN PÁGINA SAT DESVIRTUADOS');
        $sheet1->setCellValue('O'.$i, 'NÚMERO Y FECHA DE OFICIO GLOBAL DE CONTRIBUYENTES QUE DESVIRTUARON DOF');
        $sheet1->setCellValue('P'.$i, 'PUBLICACIÓN DOF DESVIRTUADOS');
        $sheet1->setCellValue('Q'.$i, 'NÚMERO Y FECHA DE OFICIO GLOBAL DE DEFINITIVOS SAT');
        $sheet1->setCellValue('R'.$i, 'PUBLICACIÓN PAGINA SAT DEFINITIVOS');
        $sheet1->setCellValue('S'.$i, 'NÚMERO Y FECHA DE OFICIO GLOBAL DE DEFINITIVOS DOF');
        $sheet1->setCellValue('T'.$i, 'PUBLICACIÓN DOF DEFINIFITVOS');
        $sheet1->setCellValue('U'.$i, 'NÚMERO Y FECHA DE OFICIO GLOBAL DE SENTENCIA FAVORABLE SAT');
        $sheet1->setCellValue('V'.$i, 'PUBLICACIÓN PÁGINA SAT SENTENCIA FAVORABLE');
        $sheet1->setCellValue('W'.$i, 'NÚMERO Y FECHA DE OFICIO GOLBAL DE SENTENCIA FAVORABLE');
        $sheet1->setCellValue('X'.$i, 'PUBLICACIÓN DOF SENTENCIA FAVORABLE');
        
        //Se aplican los estilos a el encabezado de la tabla.
        $sheet1->getStyle('A1:X1')->applyFromArray($encabezados);
        //Auto filtrado por columna.
        $sheet1->setAutoFilter('A1:X1');
        //Congela el encabezado de la tabla.
        $sheet1->freezePane('A2');
        $i+=1;
        if(count($obtenerProv69b_cpp) > 0){
            foreach ($obtenerProv69b_cpp as $proveedorSATCPP){
                    $sheet1->setCellValue('A' . $i, $proveedorSATCPP->Numero);
                    $sheet1->setCellValue('B' . $i, $proveedorSATCPP->RFC);
                    $sheet1->setCellValue('C' . $i, $proveedorSATCPP->nombre);
                    $sheet1->setCellValue('D' . $i, $proveedorSATCPP->estatusActual);
                    $sheet1->setCellValue('E' . $i, $proveedorSATCPP->estatusNuevo);
                    $sheet1->setCellValue('F' . $i, $proveedorSATCPP->observacionAct);
                    $sheet1->setCellValue('G' . $i, $proveedorSATCPP->observacionNueva);
                    $sheet1->setCellValue('H' . $i, $proveedorSATCPP->Situacion_contribuyente);
                    $sheet1->setCellValue('I' . $i, $proveedorSATCPP->Numero_Fecha_Oficio_Presuncion_SAT);
                    $sheet1->setCellValue('J' . $i, $proveedorSATCPP->Publicacion_Pagina_SAT_Presuntos);
                    $sheet1->setCellValue('K' . $i, $proveedorSATCPP->Numero_Fecha_Oficio_Presuncion_DOF);
                    $sheet1->setCellValue('L' . $i, $proveedorSATCPP->Publicacion_DOF_Presuntos);
                    $sheet1->setCellValue('M' . $i, $proveedorSATCPP->Numero_Fecha_Oficio_Contribuyentes_Desvirtuaron_SAT);
                    $sheet1->setCellValue('N' . $i, $proveedorSATCPP->Publicacion_Pagina_SAT_Desvirtuados);
                    $sheet1->setCellValue('O' . $i, $proveedorSATCPP->Numero_Fecha_Oficio_Contribuyentes_Desvirtuaron_DOF); 
                    $sheet1->setCellValue('P' . $i, $proveedorSATCPP->Publicacion_DOF_Desvirtuados);
                    $sheet1->setCellValue('Q' . $i, $proveedorSATCPP->Numero_Fecha_Oficio_Definitivos_SAT);
                    $sheet1->setCellValue('R' . $i, $proveedorSATCPP->Publicacion_Pagina_SAT_Definitivos);
                    $sheet1->setCellValue('S' . $i, $proveedorSATCPP->Numero_Fecha_Oficio_Definitivos_DOF);
                    $sheet1->setCellValue('T' . $i, $proveedorSATCPP->Publicacion_DOF_Definitivos);
                    $sheet1->setCellValue('U' . $i, $proveedorSATCPP->Numero_Fecha_Oficio_Sentencia_Favorable_SAT);
                    $sheet1->setCellValue('V' . $i, $proveedorSATCPP->Publicacion_Pagina_SAT_Sentencia_Favorable);
                    $sheet1->setCellValue('W' . $i, $proveedorSATCPP->Numero_Fecha_Oficio_Sentencia_Favorable_DOF);
                    $sheet1->setCellValue('X' . $i, $proveedorSATCPP->Publicacion_DOF_Sentencia_Favorable);
                    
                    //Genera el tamaño de la celda automaticamente y lo ajusta en dependiendo del tamaño del texto que hay dentro de cada una de ellas.
                    foreach (range('A'.$i, 'X'.$i) as $columna) {
                    $sheet1->getColumnDimension($columna)->setAutoSize(true);
                    }
                $i+=1;
            }
            //Genera los bordes exteriores de la tabla.
            $ultimaFila = $sheet1->getHighestRow();       
            $ultimaColumna = $sheet1->getHighestColumn(); 
            $rango = "A1:{$ultimaColumna}{$ultimaFila}";
            $sheet1->getStyle($rango)->applyFromArray($borde);
            //Agrega la bode a cad auna de las columnas.
            for ($col = 'A'; $col <= $ultimaColumna; $col++) {
              $rangoCol = "{$col}1:{$col}{$ultimaFila}"; // Selecciona toda la columna
              $sheet1->getStyle($rangoCol)->applyFromArray($bordeColumna);
            }
  
        }
        if(count($proveedorCambios) > 0){
          $i=1;
          // Se crea una nueva hoja de excel que contendra los datos de los proveedores que sufrieron algun cambio en el listado 69-B del SAT y en el sistema de CXP.
          $sheet2 = $spreadsheet->createSheet();
          $sheet2->setTitle('Listado 69-B CPP');
          $sheet2->setCellValue('A'.$i, 'NO');
          $sheet2->setCellValue('B'.$i, 'RFC');
          $sheet2->setCellValue('C'.$i, 'NOMBRE DEL CONTRIBUYENTE');
          $sheet2->setCellValue('D'.$i, 'SITUACIÓN DEL CONTRIBUYENTE');
          $sheet2->setCellValue('E'.$i, 'NÚMERO Y FECHA DE OFICIO GLOBAL DE PRESUNCIÓN SAT');
          $sheet2->setCellValue('F'.$i, 'PUBLICACIÓN PÁGINA SAT PRESUNTOS');
          $sheet2->setCellValue('G'.$i, 'NÚMERO Y FECHA DE OFICIO GLOBAL DE PRESUNCIÓN DOF');
          $sheet2->setCellValue('H'.$i, 'PUBLICACIÓN DOF PRESUNTOS');
          $sheet2->setCellValue('I'.$i, 'NÚMERO Y FECHA DE OFICIO GLOBAL DE CONTRIBUYENTES QUE DESVIRTUARON SAT');
          $sheet2->setCellValue('J'.$i, 'PUBLICACIÓN PÁGINA SAT DESVIRTUADOS');
          $sheet2->setCellValue('K'.$i, 'NÚMERO Y FECHA DE OFICIO GLOBAL DE CONTRIBUYENTES QUE DESVIRTUARON DOF');
          $sheet2->setCellValue('L'.$i, 'PUBLICACIÓN DOF DESVIRTUADOS');
          $sheet2->setCellValue('M'.$i, 'NÚMERO Y FECHA DE OFICIO GLOBAL DE DEFINITIVOS SAT');
          $sheet2->setCellValue('N'.$i, 'PUBLICACIÓN PAGINA SAT DEFINITIVOS');
          $sheet2->setCellValue('O'.$i, 'NÚMERO Y FECHA DE OFICIO GLOBAL DE DEFINITIVOS DOF');
          $sheet2->setCellValue('P'.$i, 'PUBLICACIÓN DOF DEFINIFITVOS');
          $sheet2->setCellValue('Q'.$i, 'NÚMERO Y FECHA DE OFICIO GLOBAL DE SENTENCIA FAVORABLE SAT');
          $sheet2->setCellValue('R'.$i, 'PUBLICACIÓN PÁGINA SAT SENTENCIA FAVORABLE');
          $sheet2->setCellValue('S'.$i, 'NÚMERO Y FECHA DE OFICIO GOLBAL DE SENTENCIA FAVORABLE');
          $sheet2->setCellValue('T'.$i, 'PUBLICACIÓN DOF SENTENCIA FAVORABLE');

          //Se aplican los estilos a el encabezado de la tabla.
          $sheet2->getStyle('A1:T1')->applyFromArray($encabezados);
          //Auto filtrado por columna.
          $sheet2->setAutoFilter('A1:T1');
          //Congela el encabezado de la tabla.
          $sheet2->freezePane('A2');
          $i+=1;
      
          foreach ($proveedorCambios as $proveedorCamb){
            $sheet2->setCellValue('A' . $i, $proveedorCamb->Numero);
            $sheet2->setCellValue('B' . $i, $proveedorCamb->RFC);
            $sheet2->setCellValue('C' . $i, $proveedorCamb->nombre);
            $sheet2->setCellValue('D' . $i, $proveedorCamb->Situacion_contribuyente);
            $sheet2->setCellValue('E' . $i, $proveedorCamb->Numero_Fecha_Oficio_Presuncion_SAT);
            $sheet2->setCellValue('F' . $i, $proveedorCamb->Publicacion_Pagina_SAT_Presuntos);
            $sheet2->setCellValue('G' . $i, $proveedorCamb->Numero_Fecha_Oficio_Presuncion_DOF);
            $sheet2->setCellValue('H' . $i, $proveedorCamb->Publicacion_DOF_Presuntos);
            $sheet2->setCellValue('I' . $i, $proveedorCamb->Numero_Fecha_Oficio_Contribuyentes_Desvirtuaron_SAT);
            $sheet2->setCellValue('J' . $i, $proveedorCamb->Publicacion_Pagina_SAT_Desvirtuados);
            $sheet2->setCellValue('K' . $i, $proveedorCamb->Numero_Fecha_Oficio_Contribuyentes_Desvirtuaron_DOF); 
            $sheet2->setCellValue('L' . $i, $proveedorCamb->Publicacion_DOF_Desvirtuados);
            $sheet2->setCellValue('M' . $i, $proveedorCamb->Numero_Fecha_Oficio_Definitivos_SAT);
            $sheet2->setCellValue('N' . $i, $proveedorCamb->Publicacion_Pagina_SAT_Definitivos);
            $sheet2->setCellValue('O' . $i, $proveedorCamb->Numero_Fecha_Oficio_Definitivos_DOF);
            $sheet2->setCellValue('P' . $i, $proveedorCamb->Publicacion_DOF_Definitivos);
            $sheet2->setCellValue('Q' . $i, $proveedorCamb->Numero_Fecha_Oficio_Sentencia_Favorable_SAT);
            $sheet2->setCellValue('R' . $i, $proveedorCamb->Publicacion_Pagina_SAT_Sentencia_Favorable);
            $sheet2->setCellValue('S' . $i, $proveedorCamb->Numero_Fecha_Oficio_Sentencia_Favorable_DOF);
            $sheet2->setCellValue('T' . $i, $proveedorCamb->Publicacion_DOF_Sentencia_Favorable);
            
            //Genera el tamaño de la celda automaticamente
            foreach (range('A'.$i, 'T'.$i) as $columna) {
              $sheet2->getColumnDimension($columna)->setAutoSize(true);
            }
            $i+=1;
          }
          //Agrega borde exterior a toda la tabla.
          $ultimaFila = $sheet2->getHighestRow();       
          $ultimaColumna = $sheet2->getHighestColumn(); 
          $rango = "A1:{$ultimaColumna}{$ultimaFila}";
          $sheet2->getStyle($rango)->applyFromArray($borde);
          //Agrega la borde a cada una de las columnas.
          for ($col = 'A'; $col <= $ultimaColumna; $col++) {
            $rangoCol = "{$col}1:{$col}{$ultimaFila}"; // Selecciona toda la columna
            $sheet2->getStyle($rangoCol)->applyFromArray($bordeColumna);
          }
        }
        // Se crea una hoja de excel por cada proveedor que sufrio cambios en el sistema y tiene solictudes dadas de alta.
        if(count($solicitudesProveedor)>0){
          foreach ($solicitudesProveedor as $rfc => $solicitudes) {
            $sheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, $rfc);
            $spreadsheet->addSheet($sheet);
            $sheet->setTitle(substr('SOLICITUD ' . $rfc, 0, 31));

            $sheet->setCellValue('A1', '# IDSOLICITUD');
            $sheet->setCellValue('B1', 'PROYECTO');
            $sheet->setCellValue('C1', 'CONDOMINIO');
            $sheet->setCellValue('D1', 'FOLIO');
            $sheet->setCellValue('E1', 'NOMBRE_DEPARTAMENTO');
            $sheet->setCellValue('F1', 'EMPRESA');
            $sheet->setCellValue('G1', 'JUSTIFICACIÓN');
            $sheet->setCellValue('H1', 'CANTIDAD');
            $sheet->setCellValue('I1', 'DESCUENTO');
            $sheet->setCellValue('J1', 'MONEDA');
            $sheet->setCellValue('K1', 'METO_PAGO');
            $sheet->setCellValue('L1', 'FECHA_CREACIÓN');
            $sheet->setCellValue('M1', 'FECHA_AUTORIZACIÓN');
            $sheet->setCellValue('N1', 'REF_BANCARIA');

            //Se aplican los estilos a el encabezado de la tabla.
            $sheet->getStyle('A1:N1')->applyFromArray($encabezados);
            //Auto filtrado por columna.
            $sheet->setAutoFilter('A1:N1');
            //Congela el encabezado de la tabla.
            $sheet->freezePane('A2');

            $i =2;
            foreach ($solicitudes as $solicitud) {
              $sheet->setCellValue('A' . $i, $solicitud->idsolicitud);
              $sheet->setCellValue('B' . $i, $solicitud->proyecto);
              $sheet->setCellValue('C' . $i, $solicitud->condominio);
              $sheet->setCellValue('D' . $i, $solicitud->folio);
              $sheet->setCellValue('E' . $i, $solicitud->nomdepto);
              $sheet->setCellValue('F' . $i, $solicitud->abrev);
              $sheet->setCellValue('G' . $i, $solicitud->justificacion);
              $sheet->setCellValue('H' . $i, $solicitud->cantidad);
              $sheet->setCellValue('I' . $i, $solicitud->descuento);
              $sheet->setCellValue('J' . $i, $solicitud->moneda);
              $sheet->setCellValue('K' . $i, $solicitud->metoPago);
              $sheet->setCellValue('L' . $i, $solicitud->fechaCreacion);
              $sheet->setCellValue('M' . $i, $solicitud->fecha_autorizacion);
              $sheet->setCellValue('N' . $i, $solicitud->ref_bancaria);

              //Genera el tamaño de la celda automaticamente
              foreach (range('A'.$i, 'N'.$i) as $columna) {

                $sheet->getColumnDimension($columna)->setAutoSize(true);
              }
              $i++;
            }
            //Agrega borde exterior a toda la tabla.
            $ultimaFila = $sheet->getHighestRow();       
            $ultimaColumna = $sheet->getHighestColumn(); 
            $rango = "A1:{$ultimaColumna}{$ultimaFila}";
            $sheet->getStyle($rango)->applyFromArray($borde);
            //Agrega la borde a cada una de las columnas.
            for ($col = 'A'; $col <= $ultimaColumna; $col++) {
              $rangoCol = "{$col}1:{$col}{$ultimaFila}";
              $sheet->getStyle($rangoCol)->applyFromArray($bordeColumna);
            }
          }    
        }
        
        

        $writer =  \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xls');
        ob_end_clean();
        $fechaExcel = date('Ymd_His');
        //ruta donde se guarda el archivo de axcel 
        $rutaArchivo = FCPATH . 'UPLOADS/TEMP/REPORTE LISTA 69_B_' . $fechaExcel . '.xls';
        //se guarda el archivo en la ruta especificada.
        $writer->save($rutaArchivo);

        return $rutaArchivo;
    }
    public function enviarCorreoContador($proveedorCambios, $rutaExcel){
        try {
            
            // Validar que la ruta del archivo de excel existe antes de continuar
            if (!file_exists($rutaExcel)) {
                log_message('error', "Error: El archivo Excel no existe en la ruta especificada: $rutaExcel");
                return false;
            }
            //ruta donde se encuentra el archivo de excel.
            $rutaArchivo = $rutaExcel;
            //convierte los datos de ingles a español.
            setlocale(LC_TIME, 'es_ES.UTF-8', 'Spanish_Spain', 'es_MX.UTF-8'); 
            //obtiene el año actual.
            $year = date('Y');
            //obtiene el mes anterior del mes actual en letras.
            $mes = strftime('%B', strtotime('first day of last month'));
            //convierte las letras en mayusculas el mes anterior
            $mes= strtoupper($mes);
            // Se valida si el arreglo $proveedorCambios tiene datos.
            if($proveedorCambios){
                //Si tiene datos pinta una tabla que contendra los datos de los proveedores que sufrieron algun cambio.
                $existenciaProveedores = ", los siguientes proveedores sufrieron cambios en el sistema de cuentas por pagar:";
                $tablaProveedores = "<tr>
                                        <th style='border: 1px solid #ddd; padding: 8px; background-color: #f2f2f2;'>Nombre</th>
                                        <th style='border: 1px solid #ddd; padding: 8px; background-color: #f2f2f2;'>RFC</th>
                                    </tr>";
                foreach ($proveedorCambios as $proveedor) {
                    $tablaProveedores .= "
                    <tr>
                        <td style='border: 1px solid #ddd; padding: 8px;'>{$proveedor->nombre}</td>
                        <td style='border: 1px solid #ddd; padding: 8px;'>{$proveedor->RFC}</td>
                    </tr>";
                }
            }else{
                //en caso de que la variable no tenga datos no pintara la tabla y mostrara el mensaje de que no hay proveedores con cambios.
                $existenciaProveedores = ", no hay proveedores que se actualizaron en el sistema de cuentas por pagar durante este mes.";
                $tablaProveedores = "";
            }
        
        
            // Cargar librería de correo e inicializar configuración
            $this->load->library('email');

            /**
             * subdirector.contabilidad@ciudadmaderas.com
             * coord.contabilidad1@ciudadmaderas.com
             * supervisor.egresos@ciudadmaderas.com
             * asistente.contabilidad@ciudadmaderas.com
             */
            // Configuración del correo electrónico
            $this->email->set_newline("\r\n");
            $this->email->from('noreplay@ciudadmaderas.com', 'SISTEMA CXP');
            $this->email->to('programador.analista38@ciudadmaderas.com,
                            programador.analista18@ciudadmaderas.com, 
                            diego.alvarado@ciudadmaderas.com,
                            subdirector.contabilidad@ciudadmaderas.com, 
                            coord.contabilidad1@ciudadmaderas.com, 
                            supervisor.egresos@ciudadmaderas.com, 
                            asistente.contabilidad@ciudadmaderas.com');
            $this->email->subject('ACTUALIZACIÓN DE LISTADO 69-B DEL MES DE '.$mes);
            $this->email->message('<!DOCTYPE html>
                                        <html lang="es">
                                            <head>
                                                <meta charset="UTF-8">
                                                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                                                <title>Actualización de lisatado 69-B</title>
                                            </head>
                                            <body style="font-family: Times New Roman, Times, serif; background-color: #01263B; color: #333; margin: 0; padding: 0;">
                                                <div style="max-width: 600px; margin: 0 auto; padding: 20px; background-color: #01263B; text-align: center;">
                                                    <img src="https://cuentas.gphsis.com/img/logo_blanco_cdm.png" alt="Logo Ciudad Maderas" style="max-width: 100%; height: auto; display: block; margin: 0 auto;">
                                                    <label for="txt-titulo-proyecto" style="color: #fff; font-size: 18px;">CUENTAS POR PAGAR</label>
                                                </div>
                                                <div style="max-width: 600px; margin: 0 auto; padding: 20px; background-color: #ffffff; text-align: center; border-radius: 8px;">
                                                    <p style="font-size: 18px; color: #333; font-weight: bold;">Estimado contador</p>
                                                    <p style="font-size: 16px; color: #333;">Se actualizó el listado 69-B del SAT'.$existenciaProveedores.'</p>
                                                    <table style="width: 100%; margin-top: 20px; border-collapse: collapse;">
                                                    '.$tablaProveedores.' 
                                                    </table>
                                                    <p style="color: #333; margin-top: 20px;">
                                                        Se adjunta un archivo de excel con los datos de los proveedores que se  encuentran en la lista 69-B del SAT.
                                                    </p>
                                                </div>
                                                <div style="max-width: 600px; margin: 0 auto; background-color: transparent; text-align: center; border-radius: 8px; font-size: 10px; color: #bababa; margin-top: 10px;">
                                                    <p style="margin: 0;">¡Saludos!</p>
                                                    <p style="margin: 5px 0;">
                                                        Este correo fue generado de manera automática, te pedimos no responder directamente. 
                                                        Para cualquier duda o aclaración, por favor envía un correo a 
                                                        <a href="mailto:soporte@ciudadmaderas.com" style="color: #b8a878; text-decoration: none;">soporte@ciudadmaderas.com</a>.
                                                    </p>
                                                    <p style="margin: 5px 0; font-weight: bold;">
                                                        © Copyright Ciudad Maderas ' . $year . ' | Departamento TI
                                                    </p>
                                                </div>
                                            </body>
                                        </html>');

            // Se crea una ruta absoluta en la cual se encuentra el archivo de excel que se genero anteriormente. 
            $ruta_absoluta = realpath($rutaArchivo);
            if ($ruta_absoluta) {
                $this->email->attach($ruta_absoluta);
            } else {
                log_message('error', "Error: No se encontró el archivo en la ruta especificada: $rutaArchivo");
                return false;
            }
            // Enviar correo
            if ($this->email->send()) {
                unlink($rutaArchivo);
                return true;
            } else {
                // Mostrar error si no se envía
                log_message('error', "Error al enviar el correo: " . $this->email->print_debugger());
                return false;
            }
            
        } catch (Exception $e){
            log_message('error', "Excepción capturada en enviarCorreoContador: " . $e->getMessage());
            return false;
        }
    }
  

    public function procesoXML() {
        ini_set('memory_limit', '-1');
        set_time_limit(0); // Eliminar límite de tiempo

        // Ignorar si el cliente se desconecta
        ignore_user_abort(true);  // Esto hace que el script siga ejecutándose incluso si el cliente se desconecta

        // Habilita el manejo interno de errores para DOMDocument
        libxml_use_internal_errors(true); 

        $documento = new DOMDocument();
        $archivo_XSD = "./application/helpers/XDS-CDFIV4.0/Esquema40.xsd";

        try {
            $facturas = $this->Api_model->obtenerfacturasPrueba()->result_array();

            foreach ($facturas as $indice => $datosFactura) {
                try {
                    $this->datosXml = [];
                    $archivo_XML = './UPLOADS/XMLS/'.$datosFactura['nombre_archivo'];

                    // Ruta y archivo para pruebas con errores manuales
                    // $archivo_XML = './UPLOADS/XMLS/1224_RENE_VARGAS_CORTES_111220_DD2BF.xml';

                    // $archivo_XML = $this->urlXML.'0125_MA_DE_LA_LUZ_DAVILA_MORENO_170155_04016.xml';
                    // if( @get_headers($archivo_XML) && strpos(@get_headers($archivo_XML)[0], '200') === FALSE ){
                    //     throw new Exception(date('d-m-yy H:i:s')."\nEL ARCHIVO XML NO SE ENCUENTRA EN SERVIDOR: $archivo_XML \n");
                    // }
                    if( !file_exists($archivo_XML) ){
                        throw new Exception(date('d-m-yy H:i:s')."\nEL ARCHIVO XML NO SE ENCUENTRA EN SERVIDOR: $archivo_XML \n");
                    }

                    if (!file_exists($archivo_XSD)) {
                        throw new Exception(date('d-m-yy H:i:s')."\nEL ARCHIVO XSD NO SE ENCUENTRA EN SERVIDOR, FAVOR DE CONTACTAR CON EL ADMINISTRADOR \n");
                    }

                    if (!$documento->load($archivo_XML)) {
                        // CAPTURA DE ERRORES AL CARGAR EL XML
                        $errores = libxml_get_errors();
                        $mensajeErrores = array_map(function($error){
                            return trim($error->message).' en linea #'.trim($error->line);
                        }, $errores);
                        libxml_clear_errors(); // SE LIMPIAN LOS ERRORES O ERROR
                        throw new Exception(date('d-m-yy H:i:s')."\nERROR AL CARGAR EL ARCHIVO XML: " . $datosFactura['nombre_archivo'] . ": ".implode(", ", $mensajeErrores) . "\n");
                    }

                    if (!$documento->schemaValidate($archivo_XSD)) {
                        // CAPTURAMOS ERRORES QUE TENGA DE SINTAXIS EL XML DE ACUERDO AL ARCHIVO XSD QUE SIRVE COMO VOCETO O INSTRUCTIVO DE COMO TIENE QUE ESTAR CONSTITUIDO
                        $errores = libxml_get_errors();
                        $mensajeErrores = array_map(function($error){
                            return trim($error->message).' en linea #'.trim($error->line);
                        }, $errores);
                        libxml_clear_errors(); // LIMPIAMOS LOS ERRORES O ERROR
                        throw new Exception(date('d-m-yy H:i:s')."\nERROR DE VALIDACION EN ARCHIVO: ". $datosFactura['nombre_archivo'] ." DE ACUERDO AL ARCHIVO XSD, REVISAR XML INTRODUCIDO: ".implode(", ", $mensajeErrores) . "\n");
                    }
                    // PROCESAMOS EL XML NORMALMENTE
                    $elementXML = $documento->documentElement;
                    
                    // Procesamos el primer nodo que es el cfdi:Comprobante para tener el nodo padre primeramente.
                    $this->extraeInfoXml($elementXML,  $this->nodosCfdiBD[$elementXML->nodeName]);
                    
                    //Recorremos los nodos hijos del nodo cfdi:Comprobante para su repectiva captura en el array.
                    foreach ($elementXML->childNodes as $nodosXML) {    
                        // Validamos si el nodo se encuentra dentro de los nodos padres establecidos como algo fijo y a buscar.
                        if( in_array($nodosXML->nodeName, $this->nodosPadre) ){ // Regresa true si el nodo recorrido dentro del XML se encuentra en array establecido.

                            // Mandamos el nodo a procesar.
                            $this->procesaNodosXML($nodosXML);
                        }
                    }
                    // Movemos los nodosatributos del nodo tfd:TimbreFiscalDigital al nodo principal "cfdi:Comprobante"
                    $this->datosXml["Comprobante"] = array_merge($this->datosXml["Comprobante"], $this->datosXml["tfd:TimbreFiscalDigital"]);
                    
                    // Limpiamos y eliminamos el indice tfd:TimbreFiscalDigital del array $this->datosXml.
                    unset($this->datosXml["tfd:TimbreFiscalDigital"]);
                    
                    // Procesamos la informacion del xml a base de datos.
                    $this->procesarInfoXmlABD($this->datosXml);
                    
                } catch (Exception $e) {
                    // CACHAMOS LOS ERRORES O ERROR, ESTO PARA NO DETENER EL PROCESO - OPCIONAL: REGISTRAR EN UN ARCHIVO DE LOG
                    file_put_contents('./application/logs/XMLS/bitacora_errores.log', $e->getMessage() . "\n", FILE_APPEND);
                }
            }
            unset($documento);
        } catch (Exception $e) {
            // CACHAMOS LOS ERRORES O ERROR, ESTO PARA NO DETENER EL PROCESO - OPCIONAL: REGISTRAR EN UN ARCHIVO DE LOG
            file_put_contents('./application/logs/XMLS/bitacora_errores.log', $e->getMessage() . "\n", FILE_APPEND);
        } finally{
            // LIMPIAR ERRORES DE libxml PARA UNA SIGUIENTE EJECUCION
            libxml_clear_errors();
        }
        
    }

    /**
     *
     * @param [xml->nodo] $nodo
     * @return void
     * 
     * @author Dante Aldair Guerrero Aldair <coordinador6.desarrollo@ciudadmaderas.com>
     * 
     * Función que realizará el recorrido del XML enviado mediante un parámetro, siendo este el elemento raíz, en este caso el elemento comprobante.
     * Se extraerán los nodos hijos mediante una función recursiva, la cual al mismo tiempo obtendrá el valor de los atributos de cada uno de los nodos 
     * y el valor de los mismos. Esto estará ligado a cada uno de los campos de nuestra base de datos para el llenado correspondiente de la información 
     * correspondiente.
     */

    private function procesaNodosXML($nodo) {
        /**
         * Mediante la matriz declarada como "$this->nodosCfdiBD", se busca el nodo 
         * de interés para adquirir o extraer la información del archivo XML.
         * Esto se extrae en función del nodo recibido como parámetro en la función.
        */
        if(isset($this->nodosCfdiBD[$nodo->nodeName])){
            /**
             * Si el nombre del nodo que se está procesando actualmente existe en el array, se procede a llamar a la siguiente función.
             * Parametros:
             * $nodo = el nodo tal cual viene en el xml, 
             *   ejemplo: <cfdi:Emisor RegimenFiscal="612" Rfc="AAGL800214H78" Nombre="LAURA SILVIA ARANDA GONZALEZ"/>
             * $this->nodosCfdiBD[$nodo->nodeName] = Envía los valores esperados que corresponden a los atributos 
             *   del nodo mencionado anteriormente.
             * $nodo->nodeName = nombre del nodo tal cual ejemplo: "cfdi:Emisor"
             */
            
             $this->extraeInfoXml($nodo, $this->nodosCfdiBD[$nodo->nodeName]);
        }
        /**
         * Mediante un bucle "for" recorremos cada uno de los nodos del XML. Es importante tener en cuenta 
         * que puede haber nodos "padre" que contengan nodos "hijo", y a su vez, estos nodos "hijo" pueden 
         * tener más nodos "hijo", lo que los convierte en nuevos nodos "padre". Este escenario se maneja 
         * mediante una función recursiva, que analiza cada uno de los nodos que cumplan con esta 
         * condición: nodos "padres" que tienen "hijos", y estos "hijos" pueden tener más "hijos", transformándose 
         * a su vez en "padres". Así, se evalúan todos los nodos de manera jerárquica y recursiva.
         */
        foreach ($nodo->childNodes as $nodoHijo) {
            // Condicionamos el análisis del nodo mediante la biblioteca, identificando el tipo de nodo que se está evaluando. 
            if ($nodoHijo->nodeType == XML_ELEMENT_NODE) {
                // Si el tipo de nodo es identificado como un "Padre", la misma función se invoca de nuevo de manera recursiva.
                $this->procesaNodosXML($nodoHijo);
            }
        }
    }

    /**
     * Undocumented function
     *
     * @param [nodo XML] $cfdiNodo => ES EL NODO DEL XML QUE SE ESTARIA RECORRIENDO Y QUE SE ESTA RECIBIENDO.
     * @param [array] $atributosNodoBD -> antes: $columnasCfdi  =>  ESTA ES EL ARRAY QUE ME IDENTIFICA CON QUE NOMBRE VA RELACIONADO A MI TABLA DE BASE DE DATOS.
     * @return void
     * 
     * Función que servirá para extraer los atributos de cada uno de los nodos que se irán recorriendo en el XML.
     */
    private function extraeInfoXml($cfdiNodo, $atributosNodoBD) {
        /**
         * De acuerdo con el nombre del nodo que se está recorriendo, por ejemplo: "cfdi", se sustituye la palabra "cfdi:". 
         * No obstante, existe una condición: si el nombre del nodo que se está analizando pertenece a ciertos valores 
         * específicos, se reemplaza directamente por "UUIDRelacionados". De lo contrario, solo se extrae la palabra "cfdi:".
        */
        
        // Guardaremos el nodo padre del nodo que se estara recorriendo.
        $nodoActualPadre = $cfdiNodo->parentNode;
        
        $indiceTablaBD = '';
        
        // Almacenaremos en un array los atributos de que se estarian recorriendo y posteriormente esto se agrgearia al array que tendra todos los datgos.
        $datosNodoActualAttr = [];

        // Se almacenaran los atributos de los nodos especiales. Tomase en cuenta este tipo de nodos los cuales son o tienen un proceso distinto de como almacenar su informacion
        $attrNodosEspeciales = [];
        
        // Removemos la palabra "cfdi:" de cada nodo.
        $indiceResultado = str_replace('cfdi:', '', $cfdiNodo->nodeName);
        // Condicionamos el nodo de conceptos el cual puete tener multiples nodos hijos de traslado y retenciones -- esta condicion se puede optimizar con tiempo esto es un "bomberazo"
        if($indiceResultado === "Concepto"){
            // Mandamos llamar una funcion recursiva la cual nos traera como resultado un array acomodado con sus respectivos elementos de retenciones y traslados
            $attrNodosEspeciales = $this->recorrerNodosHijos($cfdiNodo, $cfdiNodo->getElementsByTagName('*')->length );
            // Traemos el numero de nodos Concepto, esto para poder saber si hay mas de un concepto en el XML
            $cantidadNodosConcepto = $nodoActualPadre->getElementsByTagName('Concepto')->length;
        }
        // Se define si el nodo que se esta recorriendo tiene atributos detro de.
        if ($cfdiNodo->hasAttributes()) {
            // Se hace el recorrido al nodo buscando y extrayendo la informacion de cada atributo que nos importa.
            foreach ($cfdiNodo->attributes as $atributoNodo) {
                // Validamos que exista el nombre del atributo en el array donde tenemos los atributos que nos interesan
                if ( isset($atributosNodoBD[$atributoNodo->nodeName]) ){
                    
                    // Traemos el nombre que esta esperando la tabla en base de datos (se encuentra en array "nodosCfdiBD")
                    $indiceTablaBD = $atributosNodoBD[$atributoNodo->nodeName];

                    // Validamos si existe el nodo y/o atributo recorrido en el array establecido como formato de datos a recolectar.
                    if (!isset($datosNodoActualAttr[$indiceResultado])) { // retorna true si la variable no esta definida, no existe o es NULL.
                        
                        // Caso especial con el nodo de cfdirelacionado, ocupamos el atributo de su nodo padre, esto se cambiaria de acuerdo al cambio de la facturacion del sat.
                        if($indiceResultado === 'CfdiRelacionado'){
                            
                            // Creamos un indice en el array $datosNodoActualAttr donde el indice tendra el nombre del nombre del atributo definido en el array $nodosCfdiBD teniendo como valor otro array con indice el nombre del atributo del nodo recorrido y con valor el value del atributo.
                            $datosNodoActualAttr[$indiceResultado] = [$indiceTablaBD =>  $atributoNodo->nodeValue];

                            // Dentro del array y dentro del mismo indice correspondiente a la linea anterior se agrega otro array con un indice nombrado como el atributo del nodo padre y con su respectivo valor del atributo del padre.
                            $datosNodoActualAttr[$indiceResultado] += [$atributosNodoBD[$nodoActualPadre->attributes->item(0)->nodeName] => $nodoActualPadre->attributes->item(0)->nodeValue];
                        
                        // Si el valor nombre del nodo recorrido y que se encuentra en el array definido para valores en especifico no es "CfdiRelacionado" entra a esta condicion.
                        }else{
                            // Se crea un indice en el array con el nombre del nodo recorrido del xml, guardando un array con un indice el cual sera con el valor que se establece en el array definido con los nodos que se tendran que recorrer $nodosCfdiBD con el valor dentro del xml correspondiente al atributo que se esta recorriendo.
                            $datosNodoActualAttr[$indiceResultado] = [$indiceTablaBD =>  $atributoNodo->nodeValue];
                        }
                    // En caso de que el nombre del atributo del nodo recorrido ya se encuentre en el array $datosNodoActualAttr este simplemente concatenara o sumara los valores o atributos 
                    }else {
                            $datosNodoActualAttr[$indiceResultado] += [$indiceTablaBD =>  $atributoNodo->nodeValue];
                    }
                }
            }

            // En caso de que exista un caso especial (en la actualida son traslados y retenciones) hacemos un proceso distinto para la captura de informacion
            if (count($attrNodosEspeciales)>0) {
                // Hacemos una union de los arreglos, tanto de los nodos especiales, los cuales tienen atributos de retenciones y traslados con la informacion del nodo de concepto
                $datosNodoActualAttr[$indiceResultado] = array_merge_recursive($datosNodoActualAttr[$indiceResultado], $attrNodosEspeciales);
            }
            // Tenemos una condicion especial con el nodo de concepto, esto se puedo optimizar pero por tiempo se queda estatico
            if($indiceResultado == "Concepto"){
                // Verificamos que el arreglo principal no cuente con el elemento con el nombre del indice actual recorrido
                if(!array_key_exists($indiceResultado, $this->datosXml)){
                    // En este caso si hay mas de un nodo de condepto entra a esta validacion
                    if($cantidadNodosConcepto > 1){
                        // Se almacena un array dentro del array con nombre en el elemento concepto
                        $this->datosXml[$indiceResultado] = [$datosNodoActualAttr[$indiceResultado]];
                    }elseif ($cantidadNodosConcepto == 1) {
                        // En caso de que solo exista un nodo de concepto este solo creara un elemento dentro de estearray que contiene infromacion del concepto del XML
                        $this->datosXml[$indiceResultado] = $datosNodoActualAttr[$indiceResultado];
                    }
                }elseif (array_key_exists($indiceResultado, $this->datosXml)) {
                    // En caso de que ya exista infromacion en el nodo de Concepto este solo hara un push o agregara un nuevo nodo teniendo en cuenta que estos tendran indices con los numero 0 ... N
                    array_push($this->datosXml[$indiceResultado], $datosNodoActualAttr[$indiceResultado]);
                }
            }else{
                if(!array_key_exists($indiceResultado, $this->datosXml)){
                    $this->datosXml[$indiceResultado] = $datosNodoActualAttr[$indiceResultado];
                }else if(array_key_exists($indiceResultado, $this->datosXml) && count(array_filter($this->datosXml[$indiceResultado], 'is_array')) <= 0 ){
                    $this->datosXml[$indiceResultado] = [$this->datosXml[$indiceResultado]];
                    array_push($this->datosXml[$indiceResultado], $datosNodoActualAttr[$indiceResultado]);
                }else if(array_key_exists($indiceResultado, $this->datosXml) && count(array_filter($this->datosXml[$indiceResultado], 'is_array')) >= 1 ){
                    array_push($this->datosXml[$indiceResultado], $datosNodoActualAttr[$indiceResultado]);
                }
            }
        }
        unset($datosNodoActualAttr);
        unset($idCfdiNodoTemp);
    }

    /**
     * Recorrido recursivo de nodos hijos de un nodo XML específico.
     * 
     * Esta función recursiva recorre los nodos hijos de un nodo XML, buscando nodos 
     * especiales definidos en el arreglo `$nodosEspecialesCfdiBD`. Para cada nodo especial 
     * encontrado, se extraen sus atributos y se almacenan en el arreglo `$resultadoAtributos`.
     * La función también gestiona nodos anidados y agrega los atributos a la estructura de datos 
     * para su posterior uso.
     *
     * @param \DOMNode $nodo Nodo XML sobre el que se realizará el recorrido.
     * @param int $contador Contador de profundidad de los nodos, usado para detener el recorrido.
     * @param array $resultadoAtributos Referencia al arreglo que almacenará los atributos extraídos. 
     *        (Por defecto, es un arreglo vacío).
     * 
     * @return array El arreglo de atributos extraídos de los nodos especiales encontrados. 
     *         Si no se encuentra ningún nodo especial o se supera el contador, se retorna el 
     *         arreglo vacío o actualizado.
     * 
     * @author Dante Aldair Guerrero Aldana <coordinador6.desarrollo@ciudadmaderas.com>
     * @since [1.0] [06-Enero-2025]
     * @version 1.0
    */
    private function recorrerNodosHijos($nodo, &$contador, &$resultadoAtributos = []) {
        $attrNodos = $resultadoAtributos;
        // Se realiza un bucle para recorrer cada uno de los nodos de la etiqueta COncepto, aquí es donde surgue toda la magia y proceso de los nodos deseados.
        foreach ($nodo->childNodes as $nodoHijo) {
            /** Descripcion de condicion
             * Condicionamos lo siguiente:
             * El nodo recorrido sea un nodo valido y no un salto de linea o cualquier otra cosa que pudiera considerarse como nodo dentro de la libreria.
             * Si el nombre del nodo que se esta recorriendo no es el deseado volvemos a llamar a la funcion recursiva para el analicis de los nodos hijos hasta encontrar el nodo deseado.
            */
            if ($nodoHijo->nodeType == XML_ELEMENT_NODE){
                $contador--;
                if(array_key_exists($nodoHijo->nodeName, $this->nodosEspecialesCfdiBD)) {
                    $atributosNodoTemporal = [];
                    // Removemos la cadena cfdi: para poder nombrar el indice del array el cual contendra los datos de los atributos.
                    $indiceNodoHijo = str_replace('cfdi:', '', $nodoHijo->nodeName);
                    // Del array con los nodos especiales traemos solo el elemento correspondiente al nodo actual.
                    $atributosNodosEspecialesBD = $this->nodosEspecialesCfdiBD[$nodoHijo->nodeName];
                    // hacemos el recorrido del nodo correspondiente para extraer sus atributos.
                    foreach ($nodoHijo->attributes as $nombreAttr => $attributoNodoHijo) {
                        // La condicion establece que si el indice corresponde al nombre del atributo del nodo este continuara con el proceso normal (captura de datos).
                        if(isset($atributosNodosEspecialesBD[$nombreAttr])){
                            // Capturamos en variable el nombre de la columna en la base de datos correspondiente al atributo del nodo.
                            $indiceAttributoBD = $atributosNodosEspecialesBD[$nombreAttr];
                            if (!array_key_exists($indiceNodoHijo, $atributosNodoTemporal)) {
                                $atributosNodoTemporal[$indiceNodoHijo] = [$indiceAttributoBD => $attributoNodoHijo->nodeValue];
                            }elseif (array_key_exists($indiceNodoHijo, $atributosNodoTemporal) ) {
                                $atributosNodoTemporal[$indiceNodoHijo] += [$indiceAttributoBD => $attributoNodoHijo->nodeValue];
                            }
                        }
                    }
                    // Si el nodo hijo aún no existe en $attrNodos, lo agregamos
                    if(!array_key_exists($indiceNodoHijo, $attrNodos)){
                        $attrNodos[$indiceNodoHijo] = [$atributosNodoTemporal[$indiceNodoHijo]];
                    }elseif (array_key_exists($indiceNodoHijo, $attrNodos)) {
                        // Si ya existe, simplemente agregamos el nuevo dato
                        array_push($attrNodos[$indiceNodoHijo], $atributosNodoTemporal[$indiceNodoHijo]);
                    }
                    unset($atributosNodoTemporal);
                }else{
                    $this->recorrerNodosHijos($nodoHijo, $contador, $attrNodos);
                }
            }
        }
        $resultadoAtributos = $attrNodos;
        if ($contador <= 0) {
            return $resultadoAtributos;
        }
    }

    private function procesarInfoXmlABD($datosXMLComprobante) {
        try {
            // Captura del ID del Emisor
            $idEmisorBD = $this->procesarTablaLlaveForanea($datosXMLComprobante['Emisor'], 'Emisor');

            // Captura del ID del Receptor.
            $idReceptorBD = $this->procesarTablaLlaveForanea($datosXMLComprobante['Receptor'], 'Receptor');

            if (($idEmisorBD && $idReceptorBD) && (($idEmisorBD > 0 && $idReceptorBD  > 0))) {
                
                // Agregamos un elemento nuevo al arreglo con prefijo Comprobante donde se almacenara el ID del emisor.
                $datosXMLComprobante['Comprobante'] += ['id_emisor_xml'     =>  $idEmisorBD];
                
                // Agregamos un elemento nuevo al arreglo con prefijo Comprobante donde se almacenara el ID del Receptor.
                $datosXMLComprobante['Comprobante'] += ['id_receptor_xml'   =>  $idReceptorBD];
                
                // Damos formato a los datos de tipo fecha removiendo el caracter "T"
                $datosXMLComprobante['Comprobante']['fecha_emision_xml'] = str_replace('T', ' ', $datosXMLComprobante['Comprobante']['fecha_emision_xml']);
                $datosXMLComprobante['Comprobante']['fecha_timbre_xml'] = str_replace('T', ' ', $datosXMLComprobante['Comprobante']['fecha_timbre_xml']);
                
                /** NOTA: validar o tomar en cuenta que si ya existe un comprobante ya no deberia de seguir los procesos ya que se supondria que todo existe. **/
                // Mandamos a procesar los datos del elemento Comprobante a la funcion procesarTablaLlaveForanea retornando el ID asignado en la base de datos del registro del comprobante o en su defecto si ya existe un registro con el mismo UUID retornara el ID del registro
                $idComprobanteBD = $this->procesarTablaLlaveForanea($datosXMLComprobante['Comprobante'], 'Comprobante');
            }else {
                // En caso de que no exista aun el ID del Receptor y Emisor mandara una Exception con un rollBack en las consultas ya antes realziadas.
                $this->db->trans_rollback();
                throw new Exception("No se encontraron datos del emisor ni del receptor para continuar con la operación. Por favor, comuníquese con el administrador.");
            }

            // Validamos si en los datos a procesar del XML existen datos del nodo CfdiRelacionado
            if(array_key_exists('CfdiRelacionado', $datosXMLComprobante)){
                
                // Verificamos si hay mas de un dato correspondiente a este nodo, en este caso si hay o existe mas de un CFDI Relacionado
                if (count($datosXMLComprobante['CfdiRelacionado']) === count($datosXMLComprobante['CfdiRelacionado'], COUNT_RECURSIVE)) {

                    // Si solo existe un dato de este nodo agregamos un elemento nuevo al arreglo de CfdiRelacionado teniendo como indice el nombre de la columna en la BD y asociamos el resultado del ID del comprobante en BD
                    $datosXMLComprobante['CfdiRelacionado'] += ['id_nodo_comprobante_xml'   =>  $idComprobanteBD];

                    // Damos el formato al array para el procesamiento del un insertBatch
                    $insertarDatosFormat = $this->convertirArrayAsocMulti($datosXMLComprobante['CfdiRelacionado']);
                    
                    // Insertamos los datos correspondientes a los CFDI'S RELACIONADOS.
                    $this->Api_model->insertarDatosXMLTablaEspecifica('xml_nodo_cfdi_relacionado', $insertarDatosFormat);
                }else {
                    // Si existe mas de un datos o nodo de CFDI'S RELACIONADOS mediante un bucle agregaremos el dato del ID comprobante a cada uno de los elementos que existan.
                    foreach ($datosXMLComprobante['CfdiRelacionado'] as &$cfdiRelacionado) {
                        // Agregamos un nuevo elemento al arreglo de CfdiRelacionado
                        $cfdiRelacionado['id_nodo_comprobante_xml'] = $idComprobanteBD;
                    }
                    // Hacemos el insert a la tabla correspondiente.
                    $this->Api_model->insertarDatosXMLTablaEspecifica('xml_nodo_cfdi_relacionado', $datosXMLComprobante['CfdiRelacionado']);
                }
            }

            // Validamos que el arreglo con la infromacion del XMl contenga el elemento o nodo Concepto
            if(array_key_exists('Concepto', $datosXMLComprobante)){

                // Validamos que el elemento de Concepto tenga solo un valor (un solo nodo de CONCEPTO en terminos del XML) para su procesamiento simple.
                if(!$this->array_is_list($datosXMLComprobante['Concepto'])){
                    // En caso que exista un solo nodo de concepto en el XML este lo convertimos en un array con indice 0
                    $datosXMLComprobante['Concepto'] = [$datosXMLComprobante['Concepto']];
                }

                foreach ($datosXMLComprobante['Concepto'] as $indice => &$conceptoXml) {
                    // Agregamos el id del registro de comprobante el cual servira de llave foranea.
                    $conceptoXml += ['id_nodo_comprobante_xml' =>  $idComprobanteBD];

                    // Mandamos a procesar los datos del elemento Concepto a la funcion procesarTablaLlaveForanea retornando el ID asignado en la base de datos del registro del Concepto.
                    $idConceptoBD = $this->procesarTablaLlaveForanea($conceptoXml, 'Concepto');

                    if (array_key_exists('Traslado', $conceptoXml)) {
                        // De existir un elemento con nombre Traslado extraemos la informacion de dicho elemento y lo almacenamos en una variable para su procesamiento.
                        $nodoTrasladoXml = $conceptoXml['Traslado'];
                        
                        // Realizamos un recorrido al elemento dentro de Concepto que se llame Traslado, esto se hace asi ya que puede existir mas de un elemento Traslado en un solo elemento Concepto
                        foreach ($nodoTrasladoXml as &$trasladoXml) {
                            // Agregamos un nuevo elemento al arreglo de CfdiRelacionado
                            $trasladoXml['id_nodo_conceptos_xml'] = $idConceptoBD;
                            $trasladoXml = array_merge(
                                array_fill_keys(
                                    array_diff( $this->nodosEspecialesCfdiBD['cfdi:Traslado'], array_keys($trasladoXml) ), null
                                ), $trasladoXml
                            );
                        }
                        // Hacemos el insert a la tabla correspondiente.
                        $this->Api_model->insertarDatosXMLTablaEspecifica('xml_nodo_concepto_impuestos_traslado', $nodoTrasladoXml);
                    }
                    
                    // Verificamos que dentro del elemento de Concepto exista un elemento con nombre Retencion.
                    if (array_key_exists('Retencion', $conceptoXml)) {
                        // De existir un elemento con nombre Traslado extraemos la informacion de dicho elemento y lo almacenamos en una variable para su procesamiento.
                        $nodoRetencionXml = $conceptoXml['Retencion'];

                        // Realizamos un recorrido al elemento dentro de Concepto que se llame Retencion, esto se hace asi ya que puede existir mas de un elemento Retencion en un solo elemento Concepto
                        foreach ($nodoRetencionXml as &$retencionXml) {
                            // Agregamos un nuevo elemento al arreglo de CfdiRelacionado
                            $retencionXml['id_nodo_conceptos_xml'] = $idConceptoBD;
                            $retencionXml = array_merge(
                                array_fill_keys( array_diff( $this->nodosEspecialesCfdiBD['cfdi:Retencion'], array_keys($retencionXml) ), null
                                ), $retencionXml
                            );
                        }

                        // Hacemos el insert a la tabla correspondiente.
                        $this->Api_model->insertarDatosXMLTablaEspecifica('xml_nodo_concepto_impuestos_retenciones', $nodoRetencionXml);
                    }
                }
            }
            $this->db->trans_commit();
        } catch (Exception $error) {
            $this->db->trans_rollback();
            return array("estatus"  =>  400, "mensaje"  =>  $error->getMessage());
        }

    }

    /**
     * Procesa los datos de una tabla, verificando si el registro ya existe en la base de datos.
     * Si el registro no existe, se inserta en la tabla correspondiente; si ya existe, se retorna el ID del registro existente.
     *
     * Esta función está diseñada para ser utilizada con datos provenientes de un XML, y se encarga de interactuar con las tablas 
     * de emisores, receptores y comprobantes, determinando la tabla correspondiente a partir del índice pasado como parámetro.
     *
     * @param array $datosTablaForanea Datos de la tabla foránea que se quieren procesar, dependiendo de la llave foránea.
     *                                  El arreglo debe contener los datos de la tabla como claves y valores, por ejemplo:
     *                                  - 'rfc_emisor_xml' para emisores.
     *                                  - 'rfc_receptor_xml' para receptores.
     *                                  - 'uuid_xml' para comprobantes.
     * 
     * @param string $indiceTablaForanea Indica el tipo de tabla que se debe consultar. Puede tener uno de los siguientes valores:
     *                                   - 'Emisor' para la tabla de emisores (`xml_nodo_emisor`).
     *                                   - 'Receptor' para la tabla de receptores (`xml_nodo_receptor`).
     *                                   - 'Comprobante' para la tabla de comprobantes (`xml_nodo_comprobante`).
     * 
     * @return mixed Retorna el ID del registro procesado, ya sea insertado o existente, o un arreglo con el estatus y mensaje 
     *               en caso de error. El ID es el valor correspondiente a la columna de la tabla específica que se haya procesado.
     *               En caso de error, se retorna un arreglo con las claves 'estatus' (400) y 'mensaje' (detalles del error).
     * 
     * @throws Exception En caso de que ocurra algún error en el proceso (por ejemplo, al interactuar con la base de datos), 
     *                   se lanzará una excepción con el mensaje de error correspondiente.
    */
    function procesarTablaLlaveForanea($datosTablaForanea, $indiceTablaForanea) {
        
        // Variable para almacenar el nombre de la tabla en la base de datos
        $nombreTablaBD = "";
        try {
            // Determinamos la tabla y los datos condicionados según el tipo de tabla.
            switch ($indiceTablaForanea) {
                case 'Emisor':
                    // Definimos la tabla la cual estara consultando o insertando.
                    $nombreTablaBD = 'xml_nodo_emisor';

                    // Damos formato a los datos que usaran como condicionantes.
                    $datosCondicionados = array_intersect_key($datosTablaForanea, array_flip(["rfc_emisor_xml"]));

                    // Establecemos el nombre de la columna en BD del ID para su extraccion en caso de que ya exista dicho registro
                    $indiceColumnaBD = 'id_emisor_xml';
                    break;
                case 'Receptor':
                    // Definimos la tabla la cual estara consultando o insertando.
                    $nombreTablaBD = 'xml_nodo_receptor';

                    // Damos formato a los datos que usaran como condicionantes.
                    $datosCondicionados = array_intersect_key($datosTablaForanea, array_flip(["rfc_receptor_xml"]));
                    
                    // Establecemos el nombre de la columna en BD del ID para su extraccion en caso de que ya exista dicho registro
                    $indiceColumnaBD = 'id_receptor_xml';
                    break;
                case 'Comprobante':
                    // Definimos la tabla la cual estara consultando o insertando.
                    $nombreTablaBD = 'xml_nodo_comprobante';

                    // Damos formato a los datos que usaran como condicionantes.
                    $datosCondicionados = array_intersect_key($datosTablaForanea, array_flip(["uuid_xml"]));
                    
                    // Establecemos el nombre de la columna en BD del ID para su extraccion en caso de que ya exista dicho registro
                    $indiceColumnaBD = 'id_nodo_comprobante';
                    break;
                case 'Concepto':

                    if (array_key_exists('Traslado', $datosTablaForanea)) {
                        // Eliminamos este elemento Traslado del elemento Concepto para evitar la duplicidad de informacion
                        unset($datosTablaForanea['Traslado']);
                    }

                    if (array_key_exists('Retencion', $datosTablaForanea)) {
                        // Eliminamos este elemento Retencion del elemento Concepto para evitar la duplicidad de informacion
                        unset($datosTablaForanea['Retencion']);
                    }

                    // Definimos la tabla la cual estara consultando o insertando.
                    $nombreTablaBD = 'xml_nodo_conceptos';

                    // Mandamos un null para que este haga un insert ya que este dato no tiene como tal un dato al cual podamos tomar como dato unico
                    $datosCondicionados = null;
                    
                    // Establecemos el nombre de la columna en BD del ID para su extraccion en caso de que ya exista dicho registro
                    $indiceColumnaBD = 'id_nodo_conceptos_xml';
                    break;
                default:
                    break;
            }
            // Validamos si el registro entrante ya existe en BD
            $validarDato = $this->Api_model->verificarExistenciaDatos($nombreTablaBD, $datosCondicionados);

            // Si no existe el registro en la BD se inserta o se da de alta.
            if( !$validarDato && !is_array($validarDato) ){
                // Damos un formato al array de tal forma que sea compatible con un insert_batch en modelo
                $insertarDatosFormat = $this->convertirArrayAsocMulti($datosTablaForanea);

                // Realizamos la insercion a la base de datos trayendo el ID del registro insertado.
                $idRegistro = $this->Api_model->insertarDatosXMLTablaEspecifica($nombreTablaBD, $insertarDatosFormat);
            }else{
                // En caso de que el registro ya exista solo extraemos el ID del registro.
                $validarDato = (array) $validarDato; 

                // Retornamos el ID del registro ya existente
                $idRegistro = $validarDato[$indiceColumnaBD];
            } 
            // $this->db->trans_commit();
            // Retornamos el valor del ID
            return $idRegistro;
        } catch (Exception $error) {
            $this->db->trans_rollback();
            return array("estatus"  =>  400, "mensaje"  =>  $error->getMessage());
        }
    }

    /**
     * Transforma un array asociativo plano en un array multidimensional.
     *
     * Este método convierte un array asociativo plano (clave-valor) en un array multidimensional,
     * añadiendo el array plano como un único elemento de un nuevo array.
     *
     * @author Dante Aldair Guerrero Aldana <coordinador6.desarrollo@ciudadmaderas.com>
     * @param array $arrayNodoXML El array asociativo plano que se desea transformar.
     * @return array El array multidimensional resultante.
     * @throws InvalidArgumentException Si el parámetro no es un array asociativo plano.
    */
    function convertirArrayAsocMulti(array $arrayNodoXML) : array {
        // Validacion: Verificar que el array sea plano (las claves o indices del array deben de contener un array de lo contrario se considera plano)
        foreach ($arrayNodoXML as $indice => $valor) {
            if(is_array($valor)){
                throw new InvalidArgumentException("El array proporcinado es MultiDimencional: el indice $indice contiene un array");
            }
        }
        // Transformamos el array asociativo plano en MultiDimensional.
        return [$arrayNodoXML];
    }

    function array_is_list($array) {
        if (!is_array($array)) {
            return false;
        }
        // Verificamos si el índice 0 está presente y si los índices son secuenciales
        $keys = array_keys($array);
        return $keys === range(0, count($array) - 1);
    }

    function extraerNodoCajaChica(){
        $infoXml = $this->Api_model->obtenerFacturaCajaChica(652319)->row();
        $this->ExtraerNodoXml->obtenerNodosXML($infoXml->informacion);
        print_r($infoXml->informacion);
        exit;
    }
    
    /**
     * FECHA : 16-05-2025 || @author Efrain Martinez programador.analista38@ciudadmaderas.com
     * Servicio que obtiene datos de las solicitudes de devolución que se encuentran en el sistema de CXP.
     */
    function datosLoteDevolucion (){

        // Verifica si el cuerpo de la solicitud HTTP (php://input) está vacío.
        if (@file_get_contents("php://input") == false) {
            // Si no se recibe nada, se establecen fechas por defecto.
            $fechaIni = ''; // Fecha inicial vacia
            $fechaFin = ''; // Fecha vacia
            $lote =[];
        } else {
            // Si se recibe contenido en la solicitud, se decodifica el JSON recibido.
            $data = json_decode(file_get_contents("php://input"));
            $parametros = count((array)$data);
            if (isset($data)){
                switch ($parametros){

                    case 0:
                            
                        $fechaIni = ''; // Fecha inicial vacia
                        $fechaFin = ''; // Fecha vacia
                        $lote =[];
                    break;
                    case 1:
                            
                        if ( empty($data->lote)){
                            echo json_encode(array(
                                "estatus" => 400,
                                "message" => "El parámetro lote no ha sido especificado o su valor está vacío. Por favor, verifique que esté presente y contenga un valor válido."
                            ), JSON_UNESCAPED_UNICODE);
                            exit;
                        }
                    break;
                    case 2:
                            
                        if ( empty($data->fechaIni) || empty($data->fechaFin)){
                        echo json_encode(array(
                                "estatus" => 400,
                                "message" => "Uno o ambos parámetros de fecha (fechaIni o fechaFin) no han sido especificados o están vacíos. Por favor, asegúrese de que ambos parámetros contengan valores válidos"
                            ), JSON_UNESCAPED_UNICODE);
                            exit;
                        }
                    break;
                    case 3:
                            
                        if (empty($data->lote) || empty($data->fechaIni) || empty($data->fechaFin)){
                            echo json_encode(array(
                                "estatus" => 400,
                                "message" => "Uno o más parámetros están ausentes o vacíos. Asegúrese de que todos los parámetros requeridos estén presentes y contengan valores válidos."
                            ), JSON_UNESCAPED_UNICODE);
                            exit;
                        }
                    break;
                    default:
                        echo json_encode(array(
                            "estatus" => 400,
                            "message" => "El número máximo de parámetros permitidos es 3. La solicitud excede este límite."
                        ), JSON_UNESCAPED_UNICODE);
                        exit;

                }
                
                if (empty($data->lote)) {
                    $lote =[];
                } else {
                    if(!is_array($data->lote)){
                        echo json_encode(array(
                            "estatus" => 400,
                            "message" => "El parametro lote no tiene el formato correcto."
                        ), JSON_UNESCAPED_UNICODE);
                        exit;
                    } else {
                        $lote = $data->lote;
                    }
                }

                // Verifica si alguno de los parámetros de fecha están vacíos
                if (empty($data->fechaIni) || empty($data->fechaFin)) {

                    if (empty($data->fechaIni) && empty($data->fechaFin)) {
                        $fechaIni = '';
                        $fechaFin = '';
                    }else{
                    // Si falta algún parámetro, se responde con error 400
                    echo json_encode(array(
                        "estatus" => 400,
                        "message" => "Algún parámetro no tiene un valor especificado. Verifique que todos los parámetros contengan un valor especificado."
                    ), JSON_UNESCAPED_UNICODE);
                    exit;
                    }
                } else {
                    // Expresión regular para validar el formato de fecha (YYYY-MM-DD o YYYY/MM/DD)
                    $regex = '/^\d{4}[-\/](0[1-9]|1[0-2])[-\/](0[1-9]|[12][0-9]|3[01])$/';

                    // Valida que ambas fechas coincidan con el formato
                    if (preg_match($regex, $data->fechaIni) && preg_match($regex, $data->fechaFin)) {
                        // Reemplaza '/' por '-' en caso de que se haya usado ese separador
                        $fechaIniconvertida = str_replace('/', '-', $data->fechaIni);
                        $fechaFinconvertida = str_replace('/', '-', $data->fechaFin);

                        // Verifica que la fecha final sea posterior a la inicial
                        if ($fechaFinconvertida >= $fechaIniconvertida) {
                            // Convierte las fechas a objetos DateTime y valida si son fechas reales
                            $fechaIniVal = DateTime::createFromFormat('Y-m-d', $fechaIniconvertida);
                            $erroresIni = DateTime::getLastErrors();

                            $fechaFinVal = DateTime::createFromFormat('Y-m-d', $fechaFinconvertida);
                            $erroresFin = DateTime::getLastErrors();

                            // Verifica que no haya errores en el parseo de las fechas
                            if ($fechaIniVal && $erroresIni['warning_count'] == 0 && $erroresIni['error_count'] == 0 &&
                                $fechaFinVal && $erroresFin['warning_count'] == 0 && $erroresFin['error_count'] == 0) {

                                // Si las fechas son válidas, se asignan a las variables finales
                                $fechaIni = $fechaIniconvertida;
                                $fechaFin = $fechaFinconvertida;
                            } else {
                                // Si alguna de las fechas no existe (como 2024-02-30), se retorna error
                                echo json_encode(array(
                                    "estatus" => 400,
                                    "message" => "Una de las fechas que proporcionaste no existe. Verifica tus datos e inténtalo nuevamente."
                                ), JSON_UNESCAPED_UNICODE);
                                exit;
                            }

                        } else {
                            // Si la fecha inicial es mayor que la final, se retorna error
                            echo json_encode(array(
                                "estatus" => 400,
                                "message" => "La fecha inicial debe ser menor que la fecha final. Verifica tus datos e intentalo nuevamente."
                            ), JSON_UNESCAPED_UNICODE);
                            exit;    
                        }
                    } else {
                        // Si las fechas no tienen el formato correcto, se retorna error
                        echo json_encode(array(
                            "estatus" => 400,
                            "message" => "Los parámetros ingresados no son correctos, verifícalos e intenta nuevamente."
                        ), JSON_UNESCAPED_UNICODE);
                        exit;
                    }
                }
                if (empty($data->fechaIni) && empty($data->fechaFin)) {
                    $fechaIni = '';
                    $fechaFin = '';

                }

            }else{
                 echo json_encode(array(
                        "estatus" => 400,
                        "message" => "La estructura del bloque de parámetros no es reconocida correctamente. Verifica que los paréntesis, comillas, comas y funciones estén bien definidos."
                    ), JSON_UNESCAPED_UNICODE);
                    exit;
            }

            
        }

        // Llama al modelo para obtener los datos con las fechas válidas
        $result = $this->Api_model->getDatosLoteDevolucion($fechaIni, $fechaFin,$lote);

        // Verifica si la consulta fue exitosa
        if ($result["estatus"]) {
            $status = 200;
            $msg = "La consulta se ha realizado con éxito.";
            // Retorna los datos obtenidos
            echo json_encode(array(
                "status" => $status,
                "message" => $msg,
                "data" => $result["resultado"]
            ));
        } else {
            // Si no hubo resultados o hubo un error, retorna un mensaje de error
            $status = 404;
            $msg = "Falló la consulta, inténtelo más tarde.";
            echo json_encode(array(
                "status" => $status,
                "message" => $msg
            ));
        }
    }
    /**
     * FIN FECHA : 16-05-2025 || @author Efrain Martinez programador.analista38@ciudadmaderas.com
     */

}