<?php

use phpDocumentor\Reflection\DocBlock\Tags\Var_;

error_reporting(-1);
ini_set('display_errors', 1);
defined('BASEPATH') or exit('No direct script access allowed');

class Solicitudes_autorizacion extends CI_Controller
{

	function __construct()
	{
		parent::__construct();

		// if( !$this->session->userdata("inicio_sesion")/* || in_array( $this->session->userdata("inicio_sesion")['rol'], array( 'FP' ))*/ )
		// 	redirect("Login", "refresh");
		// // else
		$this->load->model(array('M_Solicitudes_autorizacion', 'MGCS'));
	}

	public function index()
	{

		// if( in_array( $this->session->userdata("inicio_sesion")['rol'], array('DA', 'AS', 'CA', 'CJ' )) ){
		//     $this->load->view("v_historial_departamento");
		// }else if( in_array( $this->session->userdata("inicio_sesion")['rol'], array('CT', 'CC', "CX")) ){
		//     $this->load->view("v_historial_contabilidad_egresos");
		// }else if( in_array( $this->session->userdata("inicio_sesion")['rol'], array('AD','PV','SPV','CAD','CPV','GAD','CE', 'CC', 'CI','DIO', 'SAC', 'IOO', 'COO', 'AOO','PVM')) ){
		//     $this->load->view("v_historial_admon", [ "consulta" => "DEV" ]);
		// }else{
		//    if( in_array( $this->session->userdata("inicio_sesion")['rol'], array( 'CP', 'SO' )) || $this->session->userdata('inicio_sesion')["id"] == '257' ){
		//         $this->load->view("v_historial_cp");
		//     }else{
		//         $this->load->view("v_historial");
		//     }
		// }

	}

	/*
		public function devolucionesytraspasos(){
			if( in_array( $this->session->userdata("inicio_sesion")['rol'], array('AD','PV','CE', 'CC', "CX")) ){
				$this->load->view("v_historial_admon");
			}else{
				redirect("Login", "refresh");
			}
		}
		*/
	/**HISTORIAL DE FACTORAJEES */

	public function historial_pagos()
	{
		// if( in_array( $this->session->userdata("inicio_sesion")['rol'], array('SU', 'DG', 'DP')) )
		$this->load->view("v_Solicitudes_autorizacion");

		$variables = $this->db->query("SELECT * FROM solicitudes_autorizacion");

		// foreach ($variables as $array => $row)
		// {

		// print_r($variables);

		// }

		// echo $variables;

		// elseif( in_array( $this->session->userdata("inicio_sesion")['rol'], array('FP')) )
		//     $this->load->view("v_historial_transferencias_fp");
		// else
		//     $this->load->view("v_historial_transferencias");
	}

	public function getAutorizaciones()
	{
		$resultado = $this->M_Solicitudes_autorizacion->getAutorizaciones();



		if ($resultado->num_rows() > 0) {
			$resultado = $resultado->result_array();
		} else {
			$resultado = array();
		}
		
		echo json_encode(array("data" => $resultado));
		
	}

	public function getInvolucradosByAut()
	{
		$idAutorizacion = $this->input->post("idSolicitud");

		$resultado = $this->M_Solicitudes_autorizacion->getInvolucradosByAut($idAutorizacion);
		if ($resultado->num_rows() > 0) {
			$resultado = $resultado->result_array();
		} else {
			$resultado = array();
		}
		echo json_encode(array("data" => $resultado));
	}

	public function getEmailsByAut()
	{
		$idAutorizacion = $this->input->post("idSolicitud");

		$resultado = $this->M_Solicitudes_autorizacion->getEmailsByAut($idAutorizacion);
		
		if ($resultado->num_rows() > 0) {
			$resultado = $resultado->result_array();
		} else {
			$resultado = array();
		}
		echo json_encode(array("data" => $resultado));
	}

	public function getDocumentsByAut()
	{
		$idAutorizacion = $this->input->post("idSolicitud");

		$resultado = $this->M_Solicitudes_autorizacion->getDocumentsByAut($idAutorizacion);
		
		if ($resultado->num_rows() > 0) {
			
			$resultado = $resultado->result_array();
		
		} else {
			$resultado = array();
		}

		echo json_encode(array("data" => $resultado));
	}

	public function getCommentsByAut()
	{
		$idAutorizacion = $this->input->post("idSolicitud");

		$resultado = $this->M_Solicitudes_autorizacion->getCommentsByAut($idAutorizacion);
		
		if ($resultado->num_rows() > 0) {
			
			$resultado = $resultado->result_array();
		
		} else {
			$resultado = array();
		}

		echo json_encode(array("data" => $resultado));
	}

	public function getAutorizacionById()
	{
		$resultado = $this->M_Solicitudes_autorizacion->getAutorizacionById();

		if ($resultado->num_rows() > 0) {

			$resultado = $resultado->result_array();

		} else {

			$resultado = array();
			
		}

		echo json_encode(array("data" => $resultado));
	}

	public function listadoTiposAutorizaciones()
	{
		$data["listadoTiposAutorizaciones"] = $this->M_Solicitudes_autorizacion->listadoTiposAutorizaciones()->result_array();
		$data["listadoInvolucrados"] = $this->M_Solicitudes_autorizacion->listadoInvolucrados()->result_array();
		echo json_encode($data);

	}

	public function guardarSolicitud()
	{


		$usuario = $this->input->post('usuario');
		$titulo = $this->input->post('titulo');
		$tipo = $this->input->post('tipo');
		$tipoAut = $this->input->post('tipoAut');
		$descripcion = $this->input->post('descr');
		$archivo = $this->input->post('dropedFilesInput');
		$direcciones = $this->input->post('example_emailBS');
		$requiereda = $this->input->post('darequired');
		$requieredg = $this->input->post('dgrequired');
		$requierecxp = $this->input->post('cxprequired');

		if($requierecxp=== "on"){
			$cxpSol = array(
				"idProveedor"=> $this->input->post('proveedorA'),
				"idPago"=> $this->input->post('tipoFCXP'),
				"total"=> $this->input->post('cantidad'),
				"idEmpresa"=> $this->input->post('idEmpresaA'),
				"fechaCrea" => date("Y-m-d\TH:i:s"),
				"idUsuario" => $this->session->userdata('inicio_sesion')['id'],
			);
			
		}

		if($requierecxp=== "on"){
			$cxpSol = array(
				"idProveedor"=> $this->input->post('proveedorA'),
				"idPago"=> $this->input->post('tipoFCXP'),
				"total"=> $this->input->post('cantidad'),
				"idEmpresa"=> $this->input->post('idEmpresaA'),
				"idBanco"=> $this->input->post('idbanco'),
				"rfc"=> $this->input->post('rfc'),
				"clabe"=> $this->input->post('clabe'),
				"referencia"=> '12345',
				"fechaCrea" => date("Y-m-d\TH:i:s"),
				"idUsuario" => $this->session->userdata('inicio_sesion')['id'],
			);
		}

		$insertSolicitud = array(
			"titulo" => $titulo,
			"idTipo" => 1,
			"idEstatus" => 1,
			"requiereDA" => $requiereda === "on" ? 1 : 0,
			"requiereDG" => $requieredg === "on" ? 1 : 0,
			"requiereSolicitudCXP" => $requierecxp === "on" ? 1 : 0,
			"idEstatus" => 1,
			"descripcion" => $descripcion,
			"recomendaciones" => $descripcion,
			"fechaCrea" => date("Y-m-d\TH:i:s"),
		);

		// print json_encode($insertSolicitud);


		$res = $this->M_Solicitudes_autorizacion->guardarSolicitud($insertSolicitud, $usuario, $tipo, $tipoAut, $_FILES, $direcciones, $requiereda, $requieredg,$cxpSol);
		echo json_encode($res);
		
	}

	public function actualizarSolicitud()
	{
		$usuario = $this->input->post('usuarioVisor');
		$usuarioAut = $this->input->post('usuarioAutVisor');
		$titulo = $this->input->post('tituloVisor');
		$tipo = $this->input->post('tipoVisor');
		$tipoAut = $this->input->post('tipoAutVisor');
		$descripcion = $this->input->post('descrVisor');
		$archivo = $this->input->post('dropedFilesInputVisor');
		$direcciones = $this->input->post('example_emailBSVisor');
		$requiereda = $this->input->post('darequiredVisor');
		$requieredg = $this->input->post('dgrequiredVisor');
		$requierecxp = $this->input->post('cxprequiredVisor');
		$comment = $this->input->post('comment');
		$idAutorizacion = $this->input->post('idAutorizacion');
		
		if($requierecxp=== "on"){
			$cxpSol = array(
				"idProveedor"=> $this->input->post('proveedorAV'),
				"idPago"=> $this->input->post('tipoFCXPV'),
				"total"=> $this->input->post('cantidadV'),
				"idEmpresa"=> $this->input->post('idEmpresaV'),
				"idBanco"=> $this->input->post('idbancoV'),
				"rfc"=> $this->input->post('rfcV'),
				"clabe"=> $this->input->post('clabeV'),
				"referencia"=> '12345',
				"fechaCrea" => date("Y-m-d\TH:i:s"),
				"idUsuario" => $this->session->userdata('inicio_sesion')['id'],
			);
		}


		$insertSolicitud = array(
			"titulo" => $titulo,
			"idTipo" => 1,
			"idEstatus" => 2,
			"requiereDA" => $requiereda === "on" ? 1 : 0,
			"requiereDG" => $requieredg === "on" ? 1 : 0,
			"requiereSolicitudCXP" => $requierecxp === "on" ? 1 : 0,
			"descripcion" => $descripcion,
			"recomendaciones" => $descripcion,
			"fechaModificacion" => date("Y-m-d\TH:i:s"),
		);

		

		 $res = $this->M_Solicitudes_autorizacion->actualizarSolicitud($idAutorizacion,$comment, $insertSolicitud, $usuario, $usuarioAut, $tipo, $tipoAut, $_FILES, $direcciones, $requiereda, $requieredg,$cxpSol);

		 echo json_encode($res);
		
	}

	public function enviarAutStep()
	{
		$enviarA = $this->input->post('enviarA');
		$idSolicitud = $this->input->post("idAutorizacion");
		$insertStep = array(
			"idAutorizacion" => $idSolicitud,
			"idUsuario" => $this->session->userdata('inicio_sesion')['id'],
			"tipo" => 1,
			"fechaCrea" => date("Y-m-d\TH:i:s"),
			"estatus" => 5,

		);
		$res = $this->M_Solicitudes_autorizacion->enviarAutStep($idSolicitud, $enviarA, $insertStep);
		echo json_encode($res);
	}

	public function autorizarSolicitud()
	{
		$idSolicitud = $this->input->post("idAutorizacion");
		$estatus = $this->input->post("idTipo");

		$insertSol = array(
			"idEstatus" => 2,// autorizacion
			"fechaModificacion" => date("Y-m-d\TH:i:s")
			// "idModifica" => $this->session->userdata('inicio_sesion')['id']
		);
	
		$insertLog = array(
			"idSolicitud" => $idSolicitud,
			"fechaCrea" => date("Y-m-d"),
			"idUsuario" => $this->session->userdata('inicio_sesion')['id'],
			"tipo" => 1//tipo 1 autorizacion
		);

		$res = $this->M_Solicitudes_autorizacion->autorizarSolicitud($idSolicitud, $insertSol, $insertLog,$estatus);
		echo json_encode($res);
	}
	public function cancelarSolicitud()
	{
		$idSolicitud = $this->input->post("idAutorizacion");

		$insertCancelacion = array(
			"idEstatus" => 4,
			"fechaModificacion" => date("Y-m-d\TH:i:s")
			// "idModifica" => $this->session->userdata('inicio_sesion')['id']
		);
		// echo json_encode($idSolicitud);
		// echo json_encode($insertCancelacion);
		$insertLog = array(
			"idSolicitud" => $idSolicitud,
			"fechaCrea" => date("Y-m-d"),
			"idUsuario" => $this->session->userdata('inicio_sesion')['id'],
			"tipo" => 2//tipo 2 cancelacion
		);

		

		$res = $this->M_Solicitudes_autorizacion->cancelarSolicitud($idSolicitud, $insertCancelacion, $insertLog);
		echo json_encode($res);
	}

	public function rechazarSolicitud()
	{
		$idSolicitud = $this->input->post("idAutorizacion");

		$insertCancelacion = array(
			"idEstatus" => 3,
			"fechaModificacion" => date("Y-m-d\TH:i:s")
		);

		$insertLog = array(
			"idSolicitud" => $idSolicitud,
			"fechaCrea" => date("Y-m-d"),
			"idUsuario" => $this->session->userdata('inicio_sesion')['id'],
			"tipo" => 3//tipo 3 rechazo
		);

		

		$res = $this->M_Solicitudes_autorizacion->rechazarSolicitud($idSolicitud, $insertCancelacion, $insertLog);
		echo json_encode($res);
	}

	public function autorizacionDG()
	{
		
		$idSolicitud = $this->input->post("idAutorizacion");
		
		$insertStep = array(
			"idSolicitud" => $idSolicitud,
			"idUsuario" => $this->session->userdata('inicio_sesion')['id'],
			"tipo" => 1,
			"estatus" => 2,
			"fechaCrea" => datedate("Y-m-d\TH:i:s"),
		);
		
		$res = $this->M_Solicitudes_autorizacion->autorizacionDG($idSolicitud, $insertStep)->result_array();
		
		echo json_encode($res);
	}
	// public function traerArchivo($idSolicitud)
	public function traerArchivo()
	{
		$idSolicitud = $this->input->post("idSolicitud");
		
		$res = $this->M_Solicitudes_autorizacion->getArchivo($idSolicitud);
		echo json_encode($res);
	}
	public function getInformacion()
	{
		$url = 'https://prueba.gphsis.com/CXP/index.php/Api/infoPreCargada';

			// Datos que se enviarán (pueden ser datos de formulario, por ejemplo)
			$data =  json_encode(array(
				'IDUSUARIO' => $this->session->userdata('inicio_sesion')['id']
				)
			);

			// Inicializar sesión cURL
			$ch = curl_init($url);

			// Configurar opciones cURL
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Devolver la respuesta en lugar de imprimir directamente

			// Configurar el tipo de solicitud a POST
			curl_setopt($ch, CURLOPT_POST, true);

			// Adjuntar los datos a la solicitud
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

			// Ejecutar la solicitud y obtener la respuesta
			$response = curl_exec($ch);

			// Verificar si hay errores
			if (curl_errno($ch)) {
				echo 'Error en la solicitud cURL: ' . curl_error($ch);
			}

			// Cerrar la sesión cURL
			curl_close($ch);

			// Procesar la respuesta
			$data= json_decode($response);
			echo $response;
	}
	
}
