<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_Solicitudes_autorizacion extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function getAutorizaciones()
    {
		$idUsuario = $this->session->userdata('inicio_sesion')['id'];

        return $this->db->query("SELECT S.idAutorizacion, S.idEstatus,
		TA.descripcion AS tipoSolicitud, 
		S.titulo, S.requiereSolicitudCXP, 
		S.requiereDA, S.requiereDG, 
		S.idTipo AS tipoSol, US.nombres,
		US.apellidos,US.depto,
		US.correo, S.recomendaciones, S.prioridad, 
		S.fechaCrea AS fecha, 
		S.descripcion AS descripcion_au,
		cxp.*
		FROM solicitudes_autorizacion as S
    	INNER JOIN tipo_autorizaciones TA ON TA.idtipo = S.idTipo
    	INNER JOIN involucrados_autorizacion IA ON IA.idUsuario = $idUsuario AND S.idAutorizacion = IA.idAutorizacion
    	INNER JOIN usuarios US ON US.idusuario = IA.idUsuarioCrea 
		LEFT JOIN cxp_autorizaciones cxp ON  cxp.idSolAutorizaciones = S.idAutorizacion
    	-- WHERE S.idEstatus != 4
		ORDER BY S.idAutorizacion DESC;");
		
    }

	function getEmailsByAut($idAutorizacion)
	{
        
		return $this->db->query("SELECT * FROM emails_autorizaciones WHERE idAutorizacion = $idAutorizacion AND estatus = 1;");
	
	}

	function getDocumentsByAut($idAutorizacion)
	{
        
		return $this->db->query("SELECT * FROM documentos_autorizaciones WHERE idAutorizacion = $idAutorizacion AND estatus = 1;");
	
	}

	function getCommentsByAut($idAutorizacion)
	{
        
		// return $this->db->query("SELECT * FROM comentarios_autorizaciones WHERE idAutorizacion = $idAutorizacion;");

		return $this->db->query("SELECT IF (CA.idSolicitunte = 0, CA.idRevisor,IFNULL(CA.idSolicitunte, CA.idRevisor) ) as idUsuario, CA.idSolicitunte, CA.idRevisor, CA.estatus, CA.fechaCreacion, CA.descripcion, US.Nombres, US.Apellidos, US.depto FROM comentarios_autorizaciones CA
    	INNER JOIN usuarios US ON US.idusuario = IF (CA.idSolicitunte = 0, CA.idRevisor,IFNULL(CA.idSolicitunte, CA.idRevisor) )
		WHERE CA.idAutorizacion = $idAutorizacion;");
	
	}

	function getInvolucradosByAut($idAutorizacion)
    {

        return $this->db->query("SELECT IA.idUsuario, US.nombres, US.apellidos, US.depto, IA.tipo  FROM involucrados_autorizacion AS IA
    	INNER JOIN usuarios US ON US.idusuario = IA.idUsuario 
		WHERE idAutorizacion = $idAutorizacion AND IA.estatus = 1;");

    }

    function listadoTiposAutorizaciones()
    {
        return $this->db->query("SELECT idtipo, descripcion FROM tipo_autorizaciones WHERE estatus=1;");
    }

    function listadoInvolucrados()
    {
        $idUsuario = $this->session->userdata('inicio_sesion')['id'];
		$lider = $this->session->userdata('inicio_sesion')['da'];
        return $this->db->query("SELECT
                usuarios.idusuario, 
                IFNULL(roles.idrol, '-') rol,
                IFNULL(roles.descripcion, '-') rol_descripcion,
                CONCAT(usuarios.nombres,' ',usuarios.apellidos) AS nombreCompleto,
                usuarios.depto
            FROM (
                SELECT * FROM usuarios
                WHERE estatus IN ( 0, 1 ) AND  rol IN ('DA', 'AS', 'DG','SO') AND idusuario NOT IN($idUsuario) AND da IN($lider)  AND estatus=1
				UNION
				SELECT * FROM usuarios WHERE idusuario=31 
            ) usuarios
            LEFT JOIN ( SELECT idrol, descripcion FROM roles WHERE estatus = 1 ) roles ON roles.idrol = usuarios.rol
            LEFT JOIN ( SELECT idusuario, GROUP_CONCAT( usuario_depto.iddepartamento ) iddepto FROM usuario_depto GROUP BY idusuario ) udepto ON udepto.idusuario = usuarios.idusuario
            LEFT JOIN (SELECT idusuario, GROUP_CONCAT(iddepartamento ) deptosAut, permiso FROM usuario_aut_gastos where estatus = 1 GROUP BY idusuario) autUsr ON autUsr.idusuario = usuarios.idusuario
            ORDER BY usuarios.rol, usuarios.nombres
    ");
    }

    function guardarSolicitud($insertSolicitud, $usuario, $tipo, $tipoAut, $archvio, $mails, $requiereda, $requieredg,$cxpSol)
    {
        $this->load->model("UTILS");
		
        $this->UTILS->transaction();

		$this->db->insert("solicitudes_autorizacion", $insertSolicitud);

        $idSolicitud = $this->db->insert_id();
			
		$stringgg = str_replace("\"","",str_replace("]","", str_replace("[","",$_POST['example_emailBS'])     )   ) ;
		$array = explode( ",",$stringgg);

		for ($i = 0; $i < count($array); $i++) {


			$insertMail = array(
				"email" =>  $array[$i],
				"idAutorizacion" => $idSolicitud,
				"estatus" => 1
			);

			$this->db->insert("emails_autorizaciones", $insertMail);
		}


		
		if (!empty($_FILES['dropedFilesInput'])) {

            $carpetaDoc = 'Autorizaciones_cxp/';
            $ubicacionDocumetno = 'solicitud_autorizacion'.random_int(100,999).'.pdf';
            $rutaArchivo = $carpetaDoc . $ubicacionDocumetno;
			
			for ($i = 0; $i < count($_FILES['dropedFilesInput']['tmp_name']); $i++) {

				$this->MGCS->uploadFile(fopen($_FILES['dropedFilesInput']["tmp_name"][$i], 'r'), $rutaArchivo);
			
				$insertDocumento = array(
					"ruta" =>  $rutaArchivo,
					"idAutorizacion" => $idSolicitud,
					"estatus" => 1
				);

				$this->db->insert("documentos_autorizaciones", $insertDocumento);

			}

        }

		$involucrados_default = array(
			"idUsuario" => $this->session->userdata('inicio_sesion')['id'],
			"idAutorizacion" => $idSolicitud,
			"tipo" => 1,
			"estatus" => 1,
			"idUsuarioCrea" => $this->session->userdata('inicio_sesion')['id'],
			"fechaCrea" => date("Y-m-d\TH:i:s"),
		);
		// print json_encode($usuario);

        for ($i = 0; $i < count($usuario); $i++) {

            if ($usuario[$i] != null) {
                $involucrados[] = array(
                    "idUsuario" => $usuario[$i],
                    "idAutorizacion" => $idSolicitud,
                    "tipo" => $tipo[$i],
                    "estatus" => $usuario[$i] == $this->session->userdata('inicio_sesion')['id'] ? 2 : 1,
                    "idUsuarioCrea" => $this->session->userdata('inicio_sesion')['id'],
                    "fechaCrea" => date("Y-m-d\TH:i:s"),

                );
            }
        }

        // for ($i = 0; $i < count($usuarioAut); $i++) {
        //     if ($usuarioAut[$i] != null) {
        //         $autorizadores[] = array(
        //             "idUsuario" => $usuarioAut[$i],
        //             "idAutorizacion" => $idSolicitud,
        //             "tipo" => $tipoAut[$i],
        //             "estatus" => 1,
        //             "idUsuarioCrea" => $this->session->userdata('inicio_sesion')['id'],
        //             "fechaCrea" => date("Y-m-d\TH:i:s"),
        //         );
        //     }
        // }
		array_push($involucrados,$involucrados_default);
        $this->db->insert_batch("involucrados_autorizacion", $involucrados);
        // $this->db->insert_batch("involucrados_autorizacion", $autorizadores);

		if($insertSolicitud['requiereSolicitudCXP']=== 1){
			$cxpSol['idSolAutorizaciones']=$idSolicitud;
			$this->db->insert('cxp_autorizaciones',$cxpSol);
		}
		
		
        return $this->UTILS->transaction('end', ["exito" => 'Se genero con éxito.', "error" => 'No se logro insertar el registro'], $rollback = 0);
    }

	function actualizarSolicitud($idAutorizacion, $comment, $insertSolicitud, $usuario, $usuarioAut, $tipo, $tipoAut, $archvio, $mails, $requiereda, $requieredg,$cxpSol)
    {
		$idUsuarioSesion = $this->session->userdata('inicio_sesion')['id'];

        $this->load->model("UTILS");
		
        $this->UTILS->transaction();

		$this->db->where('idAutorizacion', $idAutorizacion);
		$this->db->update("solicitudes_autorizacion", $insertSolicitud);


		// print json_encode($comment);
		
		// print json_encode($_POST);

		// print json_encode($_POST['usuarioDocToEliminateVisor']);
		// print json_encode($_POST['textoDocToEliminateVisor']);

		// print json_encode("_FILES startstartstartstartstartstartstartstartstartstartstartstartstartstartstartstartstartstartstartstartstartstartstartstartstartstartstartstartstartstartstartstartstartstartstartstartstartstartstartstartstartstart");
		// print json_encode($_FILES);
		// print json_encode("_FILES end");

		$stringgg = str_replace("\"","",str_replace("]","", str_replace("[","",$_POST['example_emailBSVisor'])     )   ) ;
		$array = explode( ",",$stringgg);

		$this->db->where('idAutorizacion', $idAutorizacion);
		$this->db->update("emails_autorizaciones", ["estatus" => 0]);
		
		if (!empty($_POST['usuarioDocToEliminateVisor'])) {

			for ($i = 0; $i < count($_POST['usuarioDocToEliminateVisor']); $i++) {
				$actualId = $_POST['usuarioDocToEliminateVisor'][$i];
			$w = $this->db->query("UPDATE documentos_autorizaciones SET estatus = 0 WHERE idAutorizacion = $idAutorizacion AND idDocumento = $actualId;");

			$y = $this->db->query("SELECT * FROM documentos_autorizaciones WHERE idAutorizacion = $idAutorizacion AND idDocumento = $actualId;")->result_array();
				
			}

		} 
		//insert new files

		if (!empty($_FILES['dropedFilesInputVisor'])) {
			$numFiles = count($_FILES['dropedFilesInputVisor']['tmp_name']);
			if($numFiles > 0){
				for ($i = 0; $i <$numFiles; $i++) {
					if(!empty($_FILES['dropedFilesInputVisor']["tmp_name"][$i])){
					$carpetaDoc = 'Autorizaciones_cxp/';
					$ubicacionDocumetno = $_FILES['dropedFilesInputVisor']['name'][$i]."_DOC".random_int(100,999).'.pdf';
					$rutaArchivo = $carpetaDoc . $ubicacionDocumetno;
						$this->MGCS->uploadFile(fopen($_FILES['dropedFilesInputVisor']["tmp_name"][$i], 'r'), $rutaArchivo);
					
					
					$insertDocumento = array(
						"ruta" =>  $rutaArchivo,
						"idAutorizacion" => $idAutorizacion,
						"estatus" => 1
					);
					$this->db->insert("documentos_autorizaciones", $insertDocumento);
					}
				}
				}
			}

        

		//insert email
		for ($i = 0; $i < count($array); $i++) {

			$insertMail = array(
				"email" =>  $array[$i],
				"idAutorizacion" => $idAutorizacion,
				"estatus" => 1
			);
			

			$q = $this->db->query("SELECT * FROM emails_autorizaciones WHERE idAutorizacion = $idAutorizacion AND email ='$array[$i]';")->result_array();

			if ( !empty($q) )
			{

				$w = $this->db->query("UPDATE emails_autorizaciones SET estatus = 1 WHERE idAutorizacion = $idAutorizacion AND email ='$array[$i]';");
				$y = $this->db->query("SELECT * FROM emails_autorizaciones WHERE idAutorizacion = $idAutorizacion AND email ='$array[$i]';")->result_array();
				

			} else {
				$this->db->insert('emails_autorizaciones',$insertMail);
			}

		}

		$this->db->where('idAutorizacion', $idAutorizacion);
		$this->db->update("involucrados_autorizacion", ["estatus" => 0]);

		//insert involucrados
        for ($i = 0; $i < count($usuario); $i++) {


            if ($usuario[$i] != null) {




				$q = $this->db->query("SELECT * FROM involucrados_autorizacion WHERE idAutorizacion = $idAutorizacion AND idUsuario = $usuario[$i];")->result_array();

				if ( !empty($q) )
				{
					// print json_encode("not empty");
	
					$w = $this->db->query("UPDATE involucrados_autorizacion SET estatus = 1 WHERE idAutorizacion = $idAutorizacion AND idUsuario = $usuario[$i];");
					$y = $this->db->query("SELECT * FROM involucrados_autorizacion WHERE idAutorizacion = $idAutorizacion AND idUsuario = $usuario[$i];")->result_array();
					
					// print json_encode($y);
	
				} else {
	
					// print json_encode("empty");
					$this->db->insert('involucrados_autorizacion',["idUsuario" => $usuario[$i],
					"idAutorizacion" => $idAutorizacion,
					"tipo" => 1,//1 tipo involucrado
					"estatus" => 1,
					"idUsuarioCrea" => $this->session->userdata('inicio_sesion')['id'],
					"fechaCrea" => date("Y-m-d\TH:i:s")]);


				}
            }
        }
		
		// //insert autorizacion
        // for ($i = 0; $i < count($usuarioAut); $i++) {
        //     if ($usuarioAut[$i] != null) {

		// 		$q = $this->db->query("SELECT * FROM involucrados_autorizacion WHERE idAutorizacion = $idAutorizacion AND idUsuario = $usuarioAut[$i];")->result_array();

		// 		if ( !empty($q) )
		// 		{
		// 			// print json_encode("not empty");
	
		// 			$w = $this->db->query("UPDATE involucrados_autorizacion SET estatus = 1 WHERE idAutorizacion = $idAutorizacion AND idUsuario = $usuarioAut[$i];");
		// 			// $y = $this->db->query("SELECT * FROM involucrados_autorizacion WHERE idAutorizacion = $idAutorizacion AND idUsuario = $usuarioAut[$i];")->result_array();
					
		// 			// print json_encode($y);
	
		// 		} else {
	
		// 			// print json_encode("empty");
		// 			$this->db->insert('involucrados_autorizacion',["idUsuario" => $usuarioAut[$i],
		// 			"idAutorizacion" => $idAutorizacion,
		// 			"tipo" => 2,//2 tipo autorizador
		// 			"estatus" => 1,
		// 			"idUsuarioCrea" => $this->session->userdata('inicio_sesion')['id'],
		// 			"fechaCrea" => date("Y-m-d\TH:i:s")]);


		// 		}
        //     }
        // }

		if ($comment == "") {
			# do nothing
		} 
		else 
		{
			
			$queryRes = $this->db->query("SELECT tipo FROM involucrados_autorizacion WHERE idAutorizacion = $idAutorizacion AND idUsuario = $idUsuarioSesion;")->result_array();
	

			if ($queryRes[0]['tipo'] == "1") {
				
				

				$insertComentario = array(
					"descripcion" =>  $comment,
					"idAutorizacion" => $idAutorizacion,
					"estatus" => 1,
					"idSolicitunte"=> $idUsuarioSesion,
					"fechaCreacion" => date("Y-m-d\TH:i:s"));

			} else {

				

				$insertComentario = array(
					"descripcion" =>  $comment,
					"idAutorizacion" => $idAutorizacion,
					"estatus" => 1,
					"idRevisor"=> $idUsuarioSesion,
					"fechaCreacion" => date("Y-m-d\TH:i:s")
					);

			}
				
			

			$this->db->insert("comentarios_autorizaciones", $insertComentario);
		}

		if($insertSolicitud['requiereSolicitudCXP']=== 1){
			$this->db->update('cxp_autorizaciones',$cxpSol, "idSolAutorizaciones= $idAutorizacion");
		}

		// print json_encode($usuario);

		$resultado = $this->UTILS->transaction('end', ["exito" => 'Se genero con éxito.', "error" => 'No se logro insertar el registro'], $rollback = 0);
		
		// print json_encode($resultado);

        return $resultado;
    }

	
    function autorizarSolicitud($idSolicitud, $insertCancelacion, $insertLog,$estatus)
    {
		// print json_encode($idSolicitud);
		// print json_encode($insertCancelacion);
        $this->load->model("UTILS");
        $this->UTILS->transaction();
		// $this->db->where( $idSolicitud);
        // $this->db->update("solicitudes_autorizacion");
		$this->db->insert("log_autorizaciones", $insertLog);
		$fechamodificacion = $insertCancelacion["fechaModificacion"];
		// print json_encode($fechamodificacion);

		$this->db->query("UPDATE solicitudes_autorizacion SET idEstatus = $estatus, fechaModificacion = '$fechamodificacion'  WHERE idAutorizacion = $idSolicitud;");
		$sol = $this->db->query("SELECT * FROM solicitudes_autorizacion S LEFT JOIN cxp_autorizaciones cxp ON  cxp.idSolAutorizaciones = S.idAutorizacion  WHERE idAutorizacion = $idSolicitud;")->row();
		
		if($estatus==4||$estatus==5){
			$datosCXP = array(
				"USUARIO"=>$this->session->userdata('inicio_sesion')['id'],
				"IDEMPRESA"=>$sol->idEmpresa,
				"TOTAL"=>$sol->total,
				"FORMAPAGO"=>$sol->idPago,
				"REFERENCIA"=>'1231455',
				"JUSTIFICACION"=>'Se envia por pruebas',
				"FECHAPAGO"=>date("Y-m-d\TH:i:s"),
				"IDBANCO"=>$sol->idBanco,
				"IDPROV"=>$sol->idProveedor,
				"CLABE"=>$sol->clabe,
				"RFC"=>$sol->rfc,
				"EMAIL"=>$sol->email,
			);
			$cxpres = $this->enviarPreSolicitud($datosCXP);
			// var_dump($cxpres->status);
			if($cxpres->status==201){
				return $this->UTILS->transaction('end', ["exito" => 'Se genero con éxito.', "error" => 'No se logro insertar el registro'], $rollback = 0);
			}else{
				return $this->UTILS->transaction('validate', [ "info" => $cxpres->message]);
			}
			
		}
		
		// $y = $this->db->query("SELECT * FROM solicitudes_autorizacion WHERE idAutorizacion = $idSolicitud;")->result_array();

		// print json_encode($y);
        return $this->UTILS->transaction('end', ["exito" => 'Se genero con éxito.', "error" => 'No se logro insertar el registro'], $rollback = 0);
    }

    function enviarAutStep($idSolicitud, $enviarA, $insertStep)
    {
        $this->load->model("UTILS");
        $this->UTILS->transaction();
        // $this->db->update("solicitudes_autorizacion",$idSolicitud);
        $this->db->insert("log_autorizaciones", $insertStep);
        return $this->UTILS->transaction('end', ["exito" => 'Se genero con éxito.', "error" => 'No se logro insertar el registro'], $rollback = 0);
    }

    function cancelarSolicitud($idSolicitud, $insertCancelacion, $insertLog)
    {
		// print json_encode($idSolicitud);
		// print json_encode($insertCancelacion);
        $this->load->model("UTILS");
        $this->UTILS->transaction();
		// $this->db->where( $idSolicitud);
        // $this->db->update("solicitudes_autorizacion");
		$this->db->insert("log_autorizaciones", $insertLog);
		$fechamodificacion = $insertCancelacion["fechaModificacion"];
		// print json_encode($fechamodificacion);

		$w = $this->db->query("UPDATE solicitudes_autorizacion SET idEstatus = 7, fechaModificacion = '$fechamodificacion'  WHERE idAutorizacion = $idSolicitud;");

		// $y = $this->db->query("SELECT * FROM solicitudes_autorizacion WHERE idAutorizacion = $idSolicitud;")->result_array();

		// print json_encode($y);
        return $this->UTILS->transaction('end', ["exito" => 'Se genero con éxito.', "error" => 'No se logro insertar el registro'], $rollback = 0);
    }

	function rechazarSolicitud($idSolicitud, $insertCancelacion, $insertLog)
    {
		// print json_encode($idSolicitud);
		// print json_encode($insertCancelacion);
        $this->load->model("UTILS");
        $this->UTILS->transaction();
		// $this->db->where( $idSolicitud);
        // $this->db->update("solicitudes_autorizacion");
		$this->db->insert("log_autorizaciones", $insertLog);
		$fechamodificacion = $insertCancelacion["fechaModificacion"];
		// print json_encode($fechamodificacion);

		$w = $this->db->query("UPDATE solicitudes_autorizacion SET idEstatus = 1, fechaModificacion = '$fechamodificacion'  WHERE idAutorizacion = $idSolicitud;");

		// $y = $this->db->query("SELECT * FROM solicitudes_autorizacion WHERE idAutorizacion = $idSolicitud;")->result_array();

		// print json_encode($y);
        return $this->UTILS->transaction('end', ["exito" => 'Se genero con éxito.', "error" => 'No se logro insertar el registro'], $rollback = 0);
    }

    function autorizacionDG($idSolicitud, $insertStep)
    {
        $this->load->model("UTILS");
        $this->UTILS->transaction();
        $this->db->update("solicitudes_autorizacion", $idSolicitud);
        $this->db->insert("log_autorizaciones", $insertStep);
        return $this->UTILS->transaction('end', ["exito" => 'Se genero con éxito.', "error" => 'No se logro insertar el registro'], $rollback = 0);
    }

    function getArchivo($idSolicitud)
    {
        $this->load->model("UTILS");
        $this->UTILS->transaction();
        $nombreArchivo = $this->db->query("SELECT ruta FROM documentos_autorizaciones WHERE idAutorizacion = $idSolicitud AND estatus != 0 ;")->row();
        try {
            $linkArchivo = $this->MGCS->getFile("$nombreArchivo->ruta");
        } catch (\Exception $e) {
            echo json_encode(array());
        }
        return $this->UTILS->transaction('end', ["exito" => 'Se genero con éxito.', "error" => 'No se logro insertar el registro'], $rollback = 0, ['archivo' => $linkArchivo]);
    }
	public function enviarPreSolicitud($datos)
	{
		$url = 'https://prueba.gphsis.com/CXP/index.php/Api/enviarPreSolicitud';

			// Datos que se enviarán (pueden ser datos de formulario, por ejemplo)
			$data =  json_encode($datos);

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
			// $data= ($response);
			$data= json_decode($response);
			// $data= json_encode($response);
			return $data;
			// return $data['status'];
	}
}
