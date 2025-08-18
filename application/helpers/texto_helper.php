<?php

    function limpiar_dato($dato){
        $limpia = "";

        if($dato && $dato != " "){
            
            //DIVIDO LA CADENA POR LOS ESPACIOS QUE TENGA
            $parts = preg_split("/[\s*]+/",$dato);
            
            $palabras = 0;
            
            foreach($parts as $subcadena){ 
                //ELIMINO LOS ESPACIOS QUE TENGA
                $subcadena = trim($subcadena); 
                //LUEGO LOS VUELVO A UNIR OMITO SOLO LOS QUE UNICAMENTE SEAN ESPACIOS
                if($subcadena != "" && $subcadena != " "){
                    $limpia .= $subcadena;
                }
                
                $palabras++;
                if($palabras < count($parts) && $parts[$palabras] != " " && $parts[$palabras] != "")
                    $limpia .= " ";
            }
            
            $limpia = strtoupper(str_replace(array('á', 'é', 'í', 'ó', 'ú', 'Á',  'É',  'Í',  'Ó',  'Ú', 'Ñ', 'ñ', ''), array('a', 'e', 'i', 'o', 'u', 'A',  'E',  'I',  'O',  'U', 'N', 'n', '.'), $limpia));
			$limpia = strtoupper( $limpia );
		}

        return $limpia ? $limpia : null ;
	}

    function minuscula_acentos($dato){
        $limpia = "";

        if($dato && $dato != " "){
            
            //DIVIDO LA CADENA POR LOS ESPACIOS QUE TENGA
            $parts = preg_split("/[\s*]+/",$dato);
            
            $palabras = 0;
            
            foreach($parts as $subcadena){ 
                //ELIMINO LOS ESPACIOS QUE TENGA
                $subcadena = trim($subcadena); 
                //LUEGO LOS VUELVO A UNIR OMITO SOLO LOS QUE UNICAMENTE SEAN ESPACIOS
                if($subcadena != "" && $subcadena != " "){
                    $limpia .= $subcadena;
                }
                
                $palabras++;
                if($palabras < count($parts) && $parts[$palabras] != " " && $parts[$palabras] != "")
                    $limpia .= " ";
            }
            
            $limpia = strtoupper(str_replace(array('á', 'é', 'í', 'ó', 'ú', 'Á',  'É',  'Í',  'Ó',  'Ú'), array('a', 'e', 'i', 'o', 'u', 'A',  'E',  'I',  'O',  'U'), $limpia));
			$limpia = strtoupper( $limpia );
		}

        return $limpia ? $limpia : null ;
	}
	
	function chat( $idsolicitud, $mensaje, $usuario ){
		$CI = get_instance();
	    $CI->load->model('Consulta');
		$CI->Consulta->insertar_observacion( $idsolicitud, $mensaje, $usuario );
	}

    function log_sistema( $idusuario, $idsolicitud, $movimiento ){
		$CI = get_instance();
        $CI->load->model('Consulta');
        
        $data = array(
            "idusuario" => $idusuario,
            "idsolicitud" => $idsolicitud,
            "tipomov" => $movimiento,
            "fecha" => date("Y-m-d H:i:s")
        );

		$CI->Consulta->insertar_log( $data );
    }

	//LOG DESTINADO PARA LAS DEVOLUCIONES Y TRASPASOS
    function log_sistema_dev_trasp( $idusuario, $idsolicitud, $movimiento,$etapa ){
		$CI = get_instance();
        $CI->load->model('Consulta');
        
        $data = array(
            "idusuario" => $idusuario,
            "idsolicitud" => $idsolicitud,
            "tipomov" => $movimiento,
            "etapa" => $etapa,
            "fecha" => date("Y-m-d H:i:s")
        );

		$CI->Consulta->insertar_log( $data );
    }



	function encriptar($texto_encriptar){
        return openssl_encrypt($texto_encriptar, 'AES-128-CBC', 'S1ST3MA_6E5T0R_RH_C1UD4D_MAD3RA5', 0, '8102cdmqsd0912vs');
        // return openssl_encrypt($texto_encriptar, 'AES-128-CBC', 'S1ST3MA_b0D1_3FFEC7-9102', 0, '8102dyeCDM0912vs');
	}
	
	function desencriptar($texto_desencriptar){
		return openssl_decrypt($texto_desencriptar, 'AES-128-CBC', 'S1ST3MA_6E5T0R_RH_C1UD4D_MAD3RA5', 0, '8102cdmqsd0912vs');
	}
    function unidad($numuero){
		switch ($numuero)
		{
		case 9:
		{
		$numu = "NUEVE";
		break;
		}
		case 8:
		{
		$numu = "OCHO";
		break;
		}
		case 7:
		{
		$numu = "SIETE";
		break;
		}
		case 6:
		{
		$numu = "SEIS";
		break;
		}
		case 5:
		{
		$numu = "CINCO";
		break;
		}
		case 4:
		{
		$numu = "CUATRO";
		break;
		}
		case 3:
		{
		$numu = "TRES";
		break;
		}
		case 2:
		{
		$numu = "DOS";
		break;
		}
		case 1:
		{
		$numu = "UNO";
		break;
		}
		case 0:
		{
		$numu = "";
		break;
		}
		}
		return $numu;
		}
		
		function decena($numdero){
		
		if ($numdero >= 90 && $numdero <= 99)
		{
		$numd = "NOVENTA ";
		if ($numdero > 90)
		$numd = $numd."Y ".(unidad($numdero - 90));
		}
		else if ($numdero >= 80 && $numdero <= 89)
		{
		$numd = "OCHENTA ";
		if ($numdero > 80)
		$numd = $numd."Y ".(unidad($numdero - 80));
		}
		else if ($numdero >= 70 && $numdero <= 79)
		{
		$numd = "SETENTA ";
		if ($numdero > 70)
		$numd = $numd."Y ".(unidad($numdero - 70));
		}
		else if ($numdero >= 60 && $numdero <= 69)
		{
		$numd = "SESENTA ";
		if ($numdero > 60)
		$numd = $numd."Y ".(unidad($numdero - 60));
		}
		else if ($numdero >= 50 && $numdero <= 59)
		{
		$numd = "CINCUENTA ";
		if ($numdero > 50)
		$numd = $numd."Y ".(unidad($numdero - 50));
		}
		else if ($numdero >= 40 && $numdero <= 49)
		{
		$numd = "CUARENTA ";
		if ($numdero > 40)
		$numd = $numd."Y ".(unidad($numdero - 40));
		}
		else if ($numdero >= 30 && $numdero <= 39)
		{
		$numd = "TREINTA ";
		if ($numdero > 30)
		$numd = $numd."Y ".(unidad($numdero - 30));
		}
		else if ($numdero >= 20 && $numdero <= 29)
		{
		if ($numdero == 20)
		$numd = "VEINTE ";
		else
		$numd = "VEINTI".(unidad($numdero - 20));
		}
		else if ($numdero >= 10 && $numdero <= 19)
		{
		switch ($numdero){
		case 10:
		{
		$numd = "DIEZ ";
		break;
		}
		case 11:
		{
		$numd = "ONCE ";
		break;
		}
		case 12:
		{
		$numd = "DOCE ";
		break;
		}
		case 13:
		{
		$numd = "TRECE ";
		break;
		}
		case 14:
		{
		$numd = "CATORCE ";
		break;
		}
		case 15:
		{
		$numd = "QUINCE ";
		break;
		}
		case 16:
		{
		$numd = "DIECISEIS ";
		break;
		}
		case 17:
		{
		$numd = "DIECISIETE ";
		break;
		}
		case 18:
		{
		$numd = "DIECIOCHO ";
		break;
		}
		case 19:
		{
		$numd = "DIECINUEVE ";
		break;
		}
		}
		}
		else
		$numd = unidad($numdero);
		return $numd;
		}
		
		function centena($numc){
		if ($numc >= 100)
		{
		if ($numc >= 900 && $numc <= 999)
		{
		$numce = "NOVECIENTOS ";
		if ($numc > 900)
		$numce = $numce.(decena($numc - 900));
		}
		else if ($numc >= 800 && $numc <= 899)
		{
		$numce = "OCHOCIENTOS ";
		if ($numc > 800)
		$numce = $numce.(decena($numc - 800));
		}
		else if ($numc >= 700 && $numc <= 799)
		{
		$numce = "SETECIENTOS ";
		if ($numc > 700)
		$numce = $numce.(decena($numc - 700));
		}
		else if ($numc >= 600 && $numc <= 699)
		{
		$numce = "SEISCIENTOS ";
		if ($numc > 600)
		$numce = $numce.(decena($numc - 600));
		}
		else if ($numc >= 500 && $numc <= 599)
		{
		$numce = "QUINIENTOS ";
		if ($numc > 500)
		$numce = $numce.(decena($numc - 500));
		}
		else if ($numc >= 400 && $numc <= 499)
		{
		$numce = "CUATROCIENTOS ";
		if ($numc > 400)
		$numce = $numce.(decena($numc - 400));
		}
		else if ($numc >= 300 && $numc <= 399)
		{
		$numce = "TRESCIENTOS ";
		if ($numc > 300)
		$numce = $numce.(decena($numc - 300));
		}
		else if ($numc >= 200 && $numc <= 299)
		{
		$numce = "DOSCIENTOS ";
		if ($numc > 200)
		$numce = $numce.(decena($numc - 200));
		}
		else if ($numc >= 100 && $numc <= 199)
		{
		if ($numc == 100)
		$numce = "CIEN ";
		else
		$numce = "CIENTO ".(decena($numc - 100));
		}
		}
		else
		$numce = decena($numc);
		
		return $numce;
		}
		
		function miles($nummero){
		if ($nummero >= 1000 && $nummero < 2000){
		$numm = "MIL ".(centena($nummero%1000));
		}
		if ($nummero >= 2000 && $nummero <10000){
		$numm = unidad(Floor($nummero/1000))." MIL ".(centena($nummero%1000));
		}
		if ($nummero < 1000)
		$numm = centena($nummero);
		
		return $numm;
		}
		
		function decmiles($numdmero){
		if ($numdmero == 10000)
		$numde = "DIEZ MIL";
		if ($numdmero > 10000 && $numdmero <20000){
		$numde = decena(Floor($numdmero/1000))."MIL ".(centena($numdmero%1000));
		}
		if ($numdmero >= 20000 && $numdmero <100000){
		$numde = decena(Floor($numdmero/1000))." MIL ".(miles($numdmero%1000));
		}
		if ($numdmero < 10000)
		$numde = miles($numdmero);
		
		return $numde;
		}
		
		function cienmiles($numcmero){
		if ($numcmero == 100000)
		$num_letracm = "CIEN MIL";
		if ($numcmero >= 100000 && $numcmero <1000000){
		$num_letracm = centena(Floor($numcmero/1000))." MIL ".(centena($numcmero%1000));
		}
		if ($numcmero < 100000)
		$num_letracm = decmiles($numcmero);
		return $num_letracm;
		}
		
		function millon($nummiero){
		if ($nummiero >= 1000000 && $nummiero <2000000){
		$num_letramm = "UN MILLON ".(cienmiles($nummiero%1000000));
		}
		if ($nummiero >= 2000000 && $nummiero <10000000){
		$num_letramm = unidad(Floor($nummiero/1000000))." MILLONES ".(cienmiles($nummiero%1000000));
		}
		if ($nummiero < 1000000)
		$num_letramm = cienmiles($nummiero);
		
		return $num_letramm;
		}
		
		function decmillon($numerodm){
		if ($numerodm == 10000000)
		$num_letradmm = "DIEZ MILLONES";
		if ($numerodm > 10000000 && $numerodm <20000000){
		$num_letradmm = decena(Floor($numerodm/1000000))."MILLONES ".(cienmiles($numerodm%1000000));
		}
		if ($numerodm >= 20000000 && $numerodm <100000000){
		$num_letradmm = decena(Floor($numerodm/1000000))." MILLONES ".(millon($numerodm%1000000));
		}
		if ($numerodm < 10000000)
		$num_letradmm = millon($numerodm);
		
		return $num_letradmm;
		}
		
		function cienmillon($numcmeros){
		if ($numcmeros == 100000000)
		$num_letracms = "CIEN MILLONES";
		if ($numcmeros >= 100000000 && $numcmeros <1000000000){
		$num_letracms = centena(Floor($numcmeros/1000000))." MILLONES ".(millon($numcmeros%1000000));
		}
		if ($numcmeros < 100000000)
		$num_letracms = decmillon($numcmeros);
		return $num_letracms;
		}
		
		function milmillon($nummierod){
		if ($nummierod >= 1000000000 && $nummierod <2000000000){
		$num_letrammd = "MIL ".(cienmillon($nummierod%1000000000));
		}
		if ($nummierod >= 2000000000 && $nummierod <10000000000){
		$num_letrammd = unidad(Floor($nummierod/1000000000))." MIL ".(cienmillon($nummierod%1000000000));
		}
		if ($nummierod < 1000000000)
		$num_letrammd = cienmillon($nummierod);
		
		return $num_letrammd;
		}
        
		function convertir($numero){
            $num = str_replace(",","",$numero);
            $num = number_format($num,2,'.','');
            $cents = substr($num,strlen($num)-2,strlen($num)-1);
            $num = (int)$num;
            
            $numf = milmillon($num);
    
            if($numf){
                return $numf." PESOS CON ".$cents."/100 MN";
            }
            else{
                return "CERO PESOS CON ".$cents."/100 MN";
            }
        }

    function verificar_token( $informacion_tratar, $token ){
        $header = base64_encode(json_encode(array( "cod" => "sha256", "cxp" => "JWT" )));
        $body = $informacion_tratar;
        $firma = hash_hmac('sha256', $header.".".$informacion_tratar, date("Ymd"));

        return $token === base64_encode($header.".".$body.".".$firma);
    }

    // Convierte una fecha dd/MES/aaaa a aaaa-mm-dd
    function fechaSinFormato($fecha){
        /*
        $dias = array(
            'Ene' => '01', 'Feb' => '02', 'Mar' => '03', 'Abr' => '04', 'May' => '05', 'Jun' => '06', 'Jul' => '07', 'Ago' => '08', 'Sep' => '09', 'Oct' => '10', 'Nov' => '11', 'Dic' => '12',
            'ene' => '01', 'feb' => '02', 'mar' => '03', 'abr' => '04', 'may' => '05', 'jun' => '06', 'jul' => '07', 'ago' => '08', 'sep' => '09', 'oct' => '10', 'nov' => '11', 'dic' => '12'
        );
        */

        $meses = array( 'ene', 'feb', 'mar', 'abr', 'may', 'jun', 'jul', 'ago', 'sep', 'oct', 'nov', 'dic' );

        $newFecha = explode('/', $fecha);

        //VERIFICAMOS SI ES UN ARREGLO PARA TRATAR LA FECHA
        if( count( $newFecha ) > 0 ){
            $encontrado = FALSE;
            $i = 0;
            //RECORREMOS EL ARRGLE TIPO FECHA PARA VERIFICAR EN QUE POSICION ESTA LA FECHA DEL MES
            do{ 
                $c = 0;
                //UBICAMOS QUE MES ES PARA OBTENER SU NUMERO
                do{
                    if( $newFecha[$i] && $newFecha[$i] != "" && strpos($meses[$c], strtolower($newFecha[$i])) !== FALSE ){
                        $newFecha[$i] = str_pad($c+1, 2, "0", STR_PAD_LEFT);
                        $encontrado = TRUE;
                    }
                    $c++;
                }while( $encontrado == FALSE && $c < count( $meses ) );
                $i++;
            }while( $encontrado == FALSE && $i < count( $newFecha ) );

            return implode('-', array_reverse($newFecha));
        }else{
            return '';
        }
    }
    
?>