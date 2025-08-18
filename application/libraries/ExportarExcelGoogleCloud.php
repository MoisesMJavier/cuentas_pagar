<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class ExportarExcelGoogleCloud
{
    public function __construct()
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: Content-Type,Origin, authorization, X-API-KEY');
        date_default_timezone_set('America/Mexico_City');
    }

    public function procesoArchivoExcel($datosEnviarGoogle, $nomArchivoTmp) {
        // Ajuste de tiempo máximo de ejecución
        ini_set('max_execution_time', 0);
        set_time_limit(0);

        // Ajuste de límite de memoria
        ini_set('memory_limit', '-1');

        // Nombre único para el archivo temporal NDJSON comprimido
        $nomArchivoTmp = 'reporte_' . $nomArchivoTmp . '_' . uniqid() .'.ndjson.gz';
        $rutaTmp = FCPATH.'UPLOADS/TEMP/REPORTES_CXP/'.$nomArchivoTmp;

        // URL pública temporal para acceder al archivo generado -> esto para local y utilizando ngrok, se tendria que cambiar para pase a pruebas y/o prod
        $urlPublica = base_url("/UPLOADS/TEMP/REPORTES_CXP/{$nomArchivoTmp}");

        // Cancelar ejecución si el cliente interrumpe la conexión
        ignore_user_abort(false);

        // Abrir archivo comprimido se comprime lo maximo posible con w9
        $gz = gzopen($rutaTmp, 'w9');
        if (!$gz) {
            exit(json_encode(["error" => "No se pudo crear el archivo temporal."]));
        }
        
        // Escribir encabezados y nombre del archivo
        gzwrite($gz, json_encode($datosEnviarGoogle[0], JSON_UNESCAPED_UNICODE) . "\n");
        gzwrite($gz, json_encode($datosEnviarGoogle[1], JSON_UNESCAPED_UNICODE) . "\n");

        if (array_key_exists(2, $datosEnviarGoogle)) {
            gzwrite($gz, json_encode($datosEnviarGoogle[2], JSON_UNESCAPED_UNICODE) . "\n");
        }

        // Control de tiempo máximo para evitar bloqueos prolongados, esto a razon del error 502 por exeso de tiempo de respuesta
        $timeoutSegundos = 60;
        $inicio = time();

        // Escribir cada solicitud en formato NDJSON como línea separada
        foreach ($datosEnviarGoogle['datos'] as $i => $registro) {
            // Cortamos si tarda mucho
            if (time() - $inicio > $timeoutSegundos) {
                fclose($gz);
                unlink($rutaTmp); // Eliminar archivo incompleto, en caso de agotarse el tiempo
                exit(json_encode(["error" => "Tiempo excedido al generar JSON"]));
            }

            // Escribimos linea en JSON sobre el archivo NDJSON dando por ultimo un salto de linea.
            gzwrite($gz, json_encode($registro, JSON_UNESCAPED_UNICODE) . "\n");
        }
        // cerramos el archivo comprimido 
        gzclose($gz);

        // Asignamos permisos full al archivo
        chmod($rutaTmp, 0777);

        // Preparar respuesta final para el frontend o cliente
        $respuesta = [
            "datosEnviarGoogle" =>  $urlPublica,
            "urlGoogleCloud"    =>  "https://generar-excel-dinamico-634286126485.us-west1.run.app",
            "nombreArchivo"     =>  $datosEnviarGoogle[1]['nombreArchivo']
        ];

        return $respuesta;
    } 
}