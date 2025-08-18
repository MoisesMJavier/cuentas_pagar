
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MBanbajio extends CI_Model {

    function saldosBB( $mes_consulta, $year_consulta ){

        $data = [];
        $fecha_consulta = $mes_consulta.$year_consulta;

        /*
        $data["edo_cuenta"] = $this->db->query("SELECT * FROM bb_estado_cuenta
        WHERE foperacion LIKE '%$fecha_consulta'")->result_array();
        */
        $data["edo_cuenta"] = $this->db->query("SELECT 
            bb_estado_cuentav2.*, 
            bb_estado_cuentav2des.descripcion 
        FROM bb_estado_cuentav2
        JOIN bb_estado_cuentav2des ON bb_estado_cuentav2.idcmov = bb_estado_cuentav2des.idcmov AND bb_estado_cuentav2.renglon = bb_estado_cuentav2des.renglon
        WHERE fecha LIKE '$year_consulta$mes_consulta%'")->result_array();

        /*TOTAL DE INGRESOS Y EGRESOS POR MES*/
        $data["totales"] = $this->db->query("SELECT 
            IF( signo_transacion LIKE '%+%', 'INGRESO', 'EGRESO' ) movimiento,
            signo_transacion,
            SUM( importe ) saldo 
        FROM bb_estado_cuenta 
        WHERE foperacion LIKE '%$fecha_consulta'
        GROUP BY signo_transacion;")->result_array();

        /*TOTAL DE INGRESOS Y EGRESOS POR SUBTIPO DE MOVIMIENTO*/
        $data["clsf_mov"] = $this->db->query("SELECT *
        FROM (
            SELECT
                tmov.movimiento,
                saldos.saldo,
                tmov.signo_operacion
            FROM (
            SELECT 
                idstmov,
                SUM( importe ) saldo
            FROM bb_estado_cuenta ec
            WHERE foperacion LIKE '%$fecha_consulta'
            AND idstmov IS NOT NULL
            GROUP BY idstmov
            ) saldos
            JOIN (
            SELECT 
                sm.idstmov,
                CONCAT( sm.tmovimiento ) movimiento,
                signo signo_operacion
            FROM bb_tipo_movimiento tm 
            JOIN bb_subtipo_movimiento sm ON tm.idtimov = sm.idtmov
            ) tmov ON saldos.idstmov = tmov.idstmov
            /*
            UNION
            SELECT 
            IF( signo_transacion LIKE '%+%', 'INGRESO OTROS', 'EGRESO OTROS' ) movimiento,
            SUM( importe ) saldo 
            FROM bb_estado_cuenta 
            WHERE idstmov IS NULL
            AND foperacion LIKE '%$fecha_consulta'
            GROUP BY signo_transacion 
            */
        ) m 
        ORDER BY movimiento")->result_array();

        /*TOTAL POR EMPRESAS*/
        $fecha_consulta = $year_consulta.$mes_consulta;
        $data["clsf_emp"] = $this->db->query("SELECT *
        FROM (
            SELECT
                CONCAT( movimientos.movimiento, ' ', emp.abrev ) movimiento,
                movimientos.saldo,
                movimientos.signo_operacion
            FROM (
                SELECT 
                    'TRASPASO INTERNO' movimiento,
                    idempresa,
                    SUM( monto ) saldo,
                    '-' signo_operacion
                FROM (
                    SELECT 
                        idempresa,
                        CAST( REPLACE( bb.monto, ',', '' ) AS DECIMAL( 10, 2 ) ) monto
                    FROM bb_estado_cuentav2 bb
                    WHERE idempresa IS NOT NULL
                    AND fecha LIKE '$fecha_consulta%'
                ) tempresa
                WHERE tempresa.monto < 0
                GROUP BY idempresa
                UNION
                SELECT 
                    'TRASPASO INTERNO' movimiento,
                    idempresa,
                    SUM( monto ) saldo,
                    '+' signo_operacion
                FROM (
                    SELECT 
                        idempresa,
                        CAST( REPLACE( bb.monto, ',', '' ) AS DECIMAL( 10, 2 ) ) monto
                    FROM bb_estado_cuentav2 bb
                    WHERE idempresa IS NOT NULL
                    AND fecha LIKE '$fecha_consulta%'
                ) tempresa
                WHERE tempresa.monto >= 0
                GROUP BY idempresa
            ) movimientos
            JOIN (
                SELECT 
                idempresa, 
                abrev 
                FROM empresas 
            ) emp ON emp.idempresa = movimientos.idempresa
        ) m 
        ORDER BY movimiento")->result_array();

        return $data;
    }

    function lectura_cosmov( $fecha = TRUE ){
        if( $fecha === TRUE )
            $fecha = date("Ymd");
        /*
        $documentos = array_diff (scandir($directorio, SCANDIR_SORT_DESCENDING), array('.', '..') );
        */

        try{
            $ftp_server    = '38.49.137.106';
            $ftp_username = 'Administrator';
            $ftp_password = 'CDm2024@A';

            //$connection = ssh2_connect('38.49.137.106', 21);
            $ftp_conn  = ftp_connect($ftp_server, 21) or die("Couldn't connect to $ftp_server");

            //ssh2_auth_password($connection, 'Administrator', 'CDm2024@A');
            $ftp_login = ftp_login($ftp_conn , $ftp_username, $ftp_password) or die("Couldn't login to $ftp_server");

            //$sftp = ssh2_sftp($connection);
            ftp_pasv($ftp_conn, true) or die("Unable switch to passive mode");
            
            //$sftp_fd = intval($sftp);
            //$directorio = "ssh2.sftp://$sftp_fd/Respuesta";
            $directorio = "/Respuesta";

            //$documentos = array_diff (scandir($directorio, SCANDIR_SORT_DESCENDING), array('.', '..') );
            $documentos = ftp_nlist($ftp_conn, $directorio);

            $c = 0;
            do{
                if( strpos( $documentos[$c], $fecha."-"  ) !== FALSE && strpos( $documentos[$c], "consm_" ) !== FALSE && strpos( $documentos[$c], ".resp" ) !== FALSE )
                    $c++;
                else
                    array_splice($documentos, $c, 1);
            }while( isset($documentos[$c]) && $c < count($documentos ) );

            //print_r($documentos);
            //exit;

            if( count( $documentos ) ){
                $this->db->trans_start();
                foreach( $documentos as $documento ){
                    if( strpos( $documento, $fecha."-"  ) !== FALSE && strpos( $documento, "consm_" ) !== FALSE && strpos( $documento, ".resp" ) !== FALSE ){
                        if( $this->db->query("SELECT * FROM bb_cosmov WHERE ndocumento = ?", [ $documento ])->num_rows() == 0 ){
                            //$lines = file( $directorio."/".$documento );
                            $file = sys_get_temp_dir() . '/file.tmp';

                            ftp_get($ftp_conn, $file, $documento, FTP_ASCII);
                            $lines = file($file);
                        
                            $cosmov = [
                                "ndocumento" => $documento,
                                "fecha_registro" => date("Y-m-d H:i:s")
                            ];

                            $this->db->insert( "bb_cosmov", $cosmov );
                            $iddocumento = $this->db->insert_id();

                            $edo_cuenta = [];
                            $renglon = 1;

                            foreach($lines as $line) {
                                if( strpos( $line, 'Sin datos' ) === FALSE ){
                                    $informacion = explode( "|", $line );

                                    if( count( $informacion ) == 15 )
                                        $edo_cuenta[] = [
                                            "idcmov" => $iddocumento,
                                            "renglon" => $renglon,
                                            "ncuenta" => str_replace( " ", "", $informacion[1] ),
                                            "foperacion" => str_replace( " ", "", $informacion[2] ),
                                            "htransaccion" => str_replace( " ", "", $informacion[3] ),
                                            "sucursal" => str_replace( " ", "", $informacion[4] ),
                                            "clave_transaccion" => str_replace( " ", "", $informacion[5] ),
                                            "referencia" => str_replace( " ", "", $informacion[6] ),
                                            "signo_transacion" => $informacion[7],
                                            "importe" => str_replace( " ", "", $informacion[8] ),
                                            "signo_saldo" => str_replace( [ "+", " "], ["", ""], $informacion[9] ),
                                            "referencia_num" => str_replace( [ "+", " "], ["", ""], $informacion[10] ),
                                            "descripcion" => $informacion[11],
                                            "leyenda" => $informacion[12],
                                            "refnum" => $informacion[13]
                                        ];

                                    $renglon++;
                                }
                            }

                            if( count( $edo_cuenta ) ){
                                $this->db->insert_batch( "bb_estado_cuenta", $edo_cuenta );
                            }
                        }
                    }
                }
                $this->db->query("CALL 002_BB_clasificar_pagos()");
                $this->db->trans_complete();
                return $this->db->trans_status();
            }else{
                return true;
            }
        }catch (\Exception $e){
            $this->db->trans_rollback();
            echo json_encode(["status"=>-1,"msj"=> $e->getMessage() ]);
            return false;
        }
    }

    public function lectura_cosmov2( $fecha = TRUE ){

        if( $fecha === TRUE )
            $fecha = date("Ymd");

        try{
            $ftp_server    = '38.49.137.106';
            $ftp_username = 'Administrator';
            $ftp_password = 'CDm2024@A';

            //$connection = ssh2_connect('38.49.137.106', 21);
            $ftp_conn  = ftp_connect($ftp_server, 21) or die("Couldn't connect to $ftp_server");

            //ssh2_auth_password($connection, 'Administrator', 'CDm2024@A');
            $ftp_login = ftp_login($ftp_conn , $ftp_username, $ftp_password) or die("Couldn't login to $ftp_server");

            //$sftp = ssh2_sftp($connection);
            ftp_pasv($ftp_conn, true) or die("Unable switch to passive mode");

            //$sftp_fd = intval($sftp);
            //$directorio = "ssh2.sftp://$sftp_fd/Respuesta";
            $directorio = "/Respuesta/";

            $documento = $fecha."-001cmov.resp";

            if( $this->db->query("SELECT * FROM bb_cosmov WHERE ndocumento = ?", [ $documento ])->num_rows() == 0 ){

                $this->db->trans_start();
                $edo_cuenta = [];

                //$lines = file( $directorio."/".$documento );
                $file = sys_get_temp_dir() . '/file.tmp';

                ftp_get($ftp_conn, $file, $directorio . $documento, FTP_ASCII);
                $lines = file($file);

                if( $lines !== FALSE ){
    
                    $cosmov = [
                        "ndocumento" => $documento,
                        "fecha_registro" => date("Y-m-d H:i:s")
                    ];

                    $this->db->insert( "bb_cosmov", $cosmov );
                    $iddocumento = $this->db->insert_id();
                    $renglon = 1;

                    foreach($lines as $line) {
                        if( strpos( $line, 'Error: Archivo enviado fuera de horario de Enlace Empresarial BanBajio' ) === FALSE ){
                            $informacion = explode( ";", $line );

                            if( count( $informacion ) == 12 ){
                                $edo_cuenta[] = [
                                    "idcmov" => $iddocumento,
                                    "renglon" => $renglon,
                                    "cliente" => $informacion[0],
                                    "vista_inversion" => $informacion[1],
                                    "cod_prod" => $informacion[2],
                                    "fecha" => $informacion[3],
                                    "recibo_docto" => $informacion[4],
                                    "comision" => $informacion[6],
                                    "iva" => $informacion[7],
                                    "monto" => $informacion[8],
                                    "saldo_disponible" => $informacion[9],
                                    "moneda" => $informacion[10]
                                ];

                                $descripcion[] = [
                                    "idcmov" => $iddocumento,
                                    "renglon" => $renglon,
                                    "descripcion" => $informacion[5],
                                ];
                            }
                            $renglon++;
                        }
                    }
                }
                
                if( count( $edo_cuenta ) ){
                    $this->db->insert_batch( "bb_estado_cuentav2", $edo_cuenta );
                    $this->db->insert_batch( "bb_estado_cuentav2des", $descripcion );
                }

                $this->db->trans_complete();
                return $this->db->trans_status();
            }else{
                return true;
            }
        }catch (\Exception $e){
            $this->db->trans_rollback();
            echo json_encode(["status"=>-1,"msj"=> $e->getMessage() ]);
            return false;
        }
    }

}