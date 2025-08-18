<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class ExtraerNodoXml{
    public function __construct()
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: Content-Type,Origin, authorization, X-API-KEY');
        date_default_timezone_set('America/Mexico_City');
    }

    /**
     * @var $parametros
     * Estructura que se espera:
     * ["nivel_global"  =>  booleano,
     *  "nodos"         =>  string(separaado por comas ',')];
    */
    function obtenerNodosXML($xmlTexto) {
        $reader = new XMLReader();
        
        // Intenta cargar el XML
        if (!$reader->xml($xmlTexto)) {
            // Si no se puede cargar el XML, mostramos los errores de libxml
            $errors = libxml_get_errors();
            foreach ($errors as $error) {
                echo "Error: {$error->message} en la línea {$error->line}\n";
            }
            return ["error" => "Existe un error al cargar el XML"];
        }

        if (!$xmlTexto) {
            return ["error" => "El contenido XML está vacío."];
        }
        
        // Verifica si el XML es válido utilizando simplexml
        libxml_use_internal_errors(true);
        $simplexml = simplexml_load_string($xmlTexto);
        
        if ($simplexml === false) {
            $errors = libxml_get_errors();
            $errorMessage = "Errores de XML:\n";
            foreach ($errors as $error) {
                $errorMessage .= "Line {$error->line}: {$error->message}\n";
            }
            return ["error" => $errorMessage];
        }

        $resultados = [
            'impuestos_traslados' => [],
            'impuestos_concepto_retenciones' => [],
            'impuestos_concepto_traslados' => [],
            'impuestos_locales' => []
        ];
        
        // Procesar el XML
        while ($reader->read()) {
            // 1. Extraer impuestos trasladados a nivel comprobante
            if ($reader->nodeType == XMLReader::ELEMENT && $reader->name == 'cfdi:Traslado' && $reader->depth == 3) {
                switch ($reader->getAttribute('Impuesto')) {
                    case '001':
                        $impuestoTraslado = 'ISR';
                        break;
                    case '002':
                        $impuestoTraslado = 'IVA';
                        break;
                    case '003':
                        $impuestoTraslado = 'IEPS';
                        break;
                    default:
                        # code...
                        break;
                }
                $resultados['impuestos_traslados'][] = [
                    'nombreImpuesto' => $impuestoTraslado,
                    'Impuesto'  =>  $reader->getAttribute('Impuesto'),
                    'TipoFactor' => $reader->getAttribute('TipoFactor'),
                    'TasaOCuota' => $reader->getAttribute('TasaOCuota'),
                    'Importe' => $reader->getAttribute('Importe'),
                    'Base' => $reader->getAttribute('Base')
                ];
            }
            
            // 2. Extraer retenciones dentro de conceptos
            if ($reader->nodeType == XMLReader::ELEMENT && $reader->name == 'cfdi:Retencion' && $reader->depth != 3) {
                switch ($reader->getAttribute('Impuesto')) {
                    case '001':
                        $impuestoRetenciones = 'ISR';
                        break;
                    case '002':
                        $impuestoRetenciones = 'IVA';
                        break;
                    case '003':
                        $impuestoRetenciones = 'IEPS';
                        break;
                    default:
                        # code...
                        break;
                }
                $resultados['impuestos_concepto_retenciones'][] = [
                    'nombreImpuesto' => $impuestoRetenciones,
                    'Impuesto'  =>  $reader->getAttribute('Impuesto'),
                    'TipoFactor' => $reader->getAttribute('TipoFactor'),
                    'TasaOCuota' => $reader->getAttribute('TasaOCuota'),
                    'Importe' => $reader->getAttribute('Importe'),
                    'Base' => $reader->getAttribute('Base')
                ];
            }
             // 3. Extraer Taslados dentro de conceptos
            if ($reader->nodeType == XMLReader::ELEMENT && $reader->name == 'cfdi:Traslado' && $reader->depth != 3) {
                switch ($reader->getAttribute('Impuesto')) {
                    case '001':
                        $impuestoRetenciones = 'ISR';
                        break;
                    case '002':
                        $impuestoRetenciones = 'IVA';
                        break;
                    case '003':
                        $impuestoRetenciones = 'IEPS';
                        break;
                    default:
                        # code...
                        break;
                }
                $resultados['impuestos_concepto_traslados'][] = [
                    'nombreImpuesto' => $impuestoRetenciones,
                    'Impuesto'  =>  $reader->getAttribute('Impuesto'),
                    'TipoFactor' => $reader->getAttribute('TipoFactor'),
                    'TasaOCuota' => $reader->getAttribute('TasaOCuota'),
                    'Importe' => $reader->getAttribute('Importe'),
                    'Base' => $reader->getAttribute('Base')
                ];
            }
            
            // 4. Extraer impuestos locales
            if ($reader->nodeType == XMLReader::ELEMENT && $reader->name == 'implocal:TrasladosLocales') {
                $resultados['impuestos_locales'][] = [
                    'ImpLocTrasladado' => $reader->getAttribute('ImpLocTrasladado'),
                    'TasadeTraslado' => $reader->getAttribute('TasadeTraslado'),
                    'Importe' => $reader->getAttribute('Importe')
                ];
            }
        }
        $reader->close();
        return $resultados;
        // }

    }

    function procesoDatosReporteCCH(&$datosReporte) {

        // Procesar el array en "lotes" para evitar el uso excesivo de memoria
        $tamañoBloque = 50;  // Tamaño del lote a procesar
        $totalElementos = count($datosReporte); 
        $elementosProcesados = 0;
        for ($indice = 0; $indice < $totalElementos; $indice += $tamañoBloque) {

            // Determinamos el tamaño del bloque actual
            $tamañoLoteActual = min($tamañoBloque, $totalElementos - $indice); // Aseguramos no pasar el límite
            // Obtener el lote de elementos a procesar
            $loteActual = array_slice($datosReporte, $indice, ($tamañoBloque == $tamañoLoteActual ? $tamañoBloque : $tamañoLoteActual));
        
            // Procesar cada elemento del lote
            foreach ($loteActual as &$elementoLoteActual) {
                // Verificamos si el campo 'informacion' existe y no está vacío
                if (isset($elementoLoteActual['informacion']) && !empty($elementoLoteActual['informacion'])) {

                    // Procesar XML y extraer datos
                    $impuestosXml = $this->obtenerNodosXML($elementoLoteActual['informacion']);
                    
                    // Modificar el array con los datos extraídos
                    $elementoLoteActual['impuestosXml'] = $impuestosXml ?? null;

                    // Liberar recursos después de cada modificación (opcional)
                    unset($impuestosXml);

                    // Eliminamos el elemento xml
                    unset($elementoLoteActual['informacion']);
                }
            }
                
            // Actualizamos el bloque procesado en el array original
            array_splice($datosReporte, $indice, $tamañoLoteActual, $loteActual);

             // Liberar memoria
             unset($loteActual);
             gc_collect_cycles();  // Forzar recolección de basura en PHP
        }
        return($datosReporte);
    }
}
