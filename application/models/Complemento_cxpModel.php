<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Complemento_cxpModel extends CI_Model {

    function __construct(){
        parent::__construct();
    }

    //SE RECIBE EL NOMBRE DE DONDE SE ENCUENTRA EL DOCUMENTO XML A LEER////
    function leerxml($xml_leer, $cargar_xml = TRUE){

        if ($cargar_xml) {
            rename($xml_leer, "./UPLOADS/XMLS/documento_temporal.txt");
            $str = file_get_contents("./UPLOADS/XMLS/documento_temporal.txt");

            if (substr($str, 0, 3) == 'o;?') {

                $str = str_replace("o;?", "", $str);
                file_put_contents('./UPLOADS/XMLS/documento_temporal.txt', $str);
            }
            rename("./UPLOADS/XMLS/documento_temporal.txt", $xml_leer);
        }

        libxml_use_internal_errors(true);
        $xml = simplexml_load_file($xml_leer, null, true);
        $xml->registerXPathNamespace('cfdi', 'http://www.sat.gob.mx/cfd/4');

        /**PARA IGNORAR LOS DOCUMENTOS POR URL**/
        if( $xml !== FALSE ){
            $datosxml = $xml->getNamespaces(true);
            $namespace = TRUE;
            //BUSCA TODOS IDDOCUMENTOS RELACIONADOS
            
            if( isset( $datosxml['tfd'] ) )
                $xml->registerXPathNamespace('t', $datosxml['tfd']);
            else
                $namespace = FALSE;

            if( isset($datosxml['pago10']) )
                $xml->registerXPathNamespace('p', $datosxml['pago10']);
            else if( isset($datosxml['pago20']) )
                $xml->registerXPathNamespace('p', $datosxml['pago20']);
            else if( isset($datosxml['Pago20']) )
                $xml->registerXPathNamespace('p', $datosxml['Pago20']);
            else if( isset($datosxml['Pagos20']) )
                $xml->registerXPathNamespace('p', $datosxml['Pagos20']);
            else if( isset($datosxml['ns3']) )
                $xml->registerXPathNamespace('p', $datosxml['ns3']);

            $datosxml = array(
                "TipoRelacion" => $xml->xpath('//@TipoRelacion'),
                "version" => $xml->xpath('//cfdi:Comprobante')[0]['Version'],
                "serie" => $xml->xpath('//cfdi:Comprobante')[0]['Serie'],
                "tipocomp" => $xml->xpath('//cfdi:Comprobante')[0]['TipoDeComprobante'],
                "folio" => ($xml->xpath('//@Folio') ? $xml->xpath('//@Folio')[0]['Folio'] : 'NA'),
                "fecha" => $xml->xpath('//cfdi:Comprobante')[0]['Fecha'],
                "formpago" => $xml->xpath('//cfdi:Comprobante')[0]['FormaPago'],
                "condpago" => $xml->xpath('//cfdi:Comprobante')[0]['CondicionesDePago'],
                "SubTotal" => (isset($xml->xpath('//cfdi:Comprobante')[0]['SubTotal']) ? $xml->xpath('//cfdi:Comprobante')[0]['SubTotal'] : 0.00),
                "Moneda" => $xml->xpath('//cfdi:Comprobante')[0]['Moneda'],
                "Total" => $xml->xpath('//cfdi:Comprobante')[0]['Total'],
                "MetodoPago" => $xml->xpath('//cfdi:Comprobante')[0]['MetodoPago'],
                "LugarExpedicion" => $xml->xpath('//cfdi:Comprobante')[0]['LugarExpedicion'],
                "rfcemisor" => $xml->xpath('//cfdi:Emisor')[0]['Rfc'],
                "nomemi" => $xml->xpath('//cfdi:Emisor')[0]['Nombre'],
                "regfisemisor" => isset($xml->xpath('//cfdi:Emisor')[0]['RegimenFiscal']) ? $xml->xpath('//cfdi:Emisor')[0]['RegimenFiscal'] : false,
                "rfcrecep" => $xml->xpath('//cfdi:Receptor')[0]['Rfc'],
                "nomrec" => $xml->xpath('//cfdi:Receptor')[0]['Nombre'],
                "regfisrecep" => isset($xml->xpath('//cfdi:Receptor')[0]['RegimenFiscalReceptor'])? $xml->xpath('//cfdi:Receptor')[0]['RegimenFiscalReceptor'] : false,
                "cpfisrecep" =>  isset($xml->xpath('//cfdi:Receptor')[0]['DomicilioFiscalReceptor'])? $xml->xpath('//cfdi:Receptor')[0]['DomicilioFiscalReceptor'] : false,
                "usocfdi" => $xml->xpath('//cfdi:Receptor')[0]['UsoCFDI'],
                "Descuento" => (isset($xml->xpath('//cfdi:Comprobante')[0]['Descuento']) ? $xml->xpath('//cfdi:Comprobante')[0]['Descuento'] : 0.00),
                "conceptos" => $xml->xpath('//cfdi:Concepto'),
                "moneda" => $xml->xpath('//@Moneda')[0],
                //"uuid" => ( $xml->xpath('//cfdi:CfdiRelacionados/cfdi:CfdiRelacionado') ? $xml->xpath('//@UUID')[ count( $xml->xpath('//@UUID') ) - 1 ] : $xml->xpath('//@UUID')[0] ),
                "uuidR" => $xml->xpath('//cfdi:CfdiRelacionados/cfdi:CfdiRelacionado'),
                "iddocumento" => $xml->xpath('//@IdDocumento'),
                "pagos" => $xml-> xpath('//p:Pago'),  
                "idComplementos" => $xml-> xpath('//p:DoctoRelacionado'),
                "impuestos_traslados" => $xml->xpath('//cfdi:Conceptos/cfdi:Concepto/cfdi:Impuestos/cfdi:Traslados/cfdi:Traslado'),
                "impuestos_retenciones" => $xml->xpath('//cfdi:Conceptos/cfdi:Concepto/cfdi:Impuestos/cfdi:Retenciones/cfdi:Retencion'),
                "TipoCambio" => $xml->xpath('//cfdi:Comprobante')[0]['TipoCambio'],
                "TipoDeComprobante" => $xml->xpath('//cfdi:Comprobante')[0]['TipoDeComprobante'],
                "impuestos_R" => $xml->xpath('//cfdi:Comprobante/cfdi:Impuestos/cfdi:Retenciones/cfdi:Retencion'),
                "impuestos_T" => $xml->xpath('//cfdi:Comprobante/cfdi:Impuestos/cfdi:Traslados/cfdi:Traslado'),
                "TotImpRet" => isset($xml->xpath('//cfdi:Comprobante/cfdi:Impuestos')[0]['TotalImpuestosRetenidos']) ? $xml->xpath('//cfdi:Comprobante/cfdi:Impuestos')[0]['TotalImpuestosRetenidos'] : '---',
                "TotImpTras" => isset($xml->xpath('//cfdi:Comprobante/cfdi:Impuestos')[0]['TotalImpuestosTrasladados']) ? $xml->xpath('//cfdi:Comprobante/cfdi:Impuestos')[0]['TotalImpuestosTrasladados'] : '---'
            );

            if( $namespace ){
                $datosxml["uuid"] = $xml->xpath('//t:TimbreFiscalDigital')[0]["UUID"];
            }else{
                $respuesta = $xml->xpath('//TimbreFiscalDigital');
                if( $respuesta ){
                    $datosxml["uuid"] = isset($respuesta[0]["UUID"]) ? $respuesta[0]["UUID"] : FALSE;
                }else{
                    $datosxml["uuid"] = $respuesta;
                }
            }

            $datosxml["impuestot"] = json_encode( $datosxml['impuestos_T'] );

            $datosxml["tasacuotat"] = null;
            $datosxml["importet"] = null;

            $datosxml["tasatras16"] = 0;
            $datosxml["tasatras8"] = 0;
            $datosxml["tasatras0"] = 0;
            $datosxml["ISRtras"] = 0;
            $datosxml["IEPStras"] = 0;
            $datosxml["rettras"] = 0;

            for( $c = 0; $c < count( $datosxml['impuestos_T'] ); $c++ ){
                if( isset($datosxml) && $datosxml['impuestos_T'] && $datosxml['impuestos_T'][$c]->attributes()->Impuesto == "002" && $datosxml['impuestos_T'][$c]->attributes()->TasaOCuota > 0 ){
                    $datosxml["tasacuotat"] = $datosxml['impuestos_T'][$c]->attributes()->TasaOCuota;
                    $datosxml["importet"] = $datosxml['impuestos_T'][$c]->attributes()->Importe;
                }

                if( isset($datosxml) && $datosxml['impuestos_T'] && $datosxml['impuestos_T'][$c]->attributes()->Impuesto == "001" ){
                    $datosxml["ISRtras"] += $datosxml["ISRtras"];
                }

                if( isset($datosxml) && $datosxml['impuestos_T'] && $datosxml['impuestos_T'][$c]->attributes()->Impuesto == "002" ){

                    if( isset( $datosxml['impuestos_T'][$c]->attributes()->TasaOCuota ) && $datosxml['impuestos_T'][$c]->attributes()->TasaOCuota == .16 ){
                        $datosxml["tasatras16"] = $datosxml['impuestos_T'][$c]->attributes()->Importe;
                    }

                    if( isset( $datosxml['impuestos_T'][$c]->attributes()->TasaOCuota ) && $datosxml['impuestos_T'][$c]->attributes()->TasaOCuota == .08 ){
                        $datosxml["tasatras8"] = $datosxml['impuestos_T'][$c]->attributes()->Importe;
                    }

                    if( ( $datosxml['impuestos_T'][$c]->attributes()->TasaOCuota == FALSE || $datosxml['impuestos_T'][$c]->attributes()->TasaOCuota == 0 ) && $datosxml["tasatras0"] = $datosxml['impuestos_T'][$c]->attributes()->Importe ){
                        $datosxml["tasatras0"] = $datosxml['impuestos_T'][$c]->attributes()->Importe;
                    }
                }

                if( isset($datosxml) && $datosxml['impuestos_T'] && $datosxml['impuestos_T'][$c]->attributes()->Impuesto == "003" ){
                    $datosxml["IEPStras"] += $datosxml['impuestos_T'][$c]->attributes()->Importe;
                }
            }

            $datosxml["impuestor"] = json_encode( $datosxml['impuestos_R'] );
            $datosxml["tasacuotar"] = null;
            $datosxml["importer"] = null;

            for( $c = 0; $c < count( $datosxml['impuestos_R'] ); $c++ ){
                if( isset($datosxml) && $datosxml['impuestos_R'] && $datosxml['impuestos_R'][$c]->attributes()->Impuesto == "003" && $datosxml['impuestos_R'][$c]->attributes()->TasaOCuota > 0 ){
                    $datosxml["tasacuotar"] = $datosxml['impuestos_R'][$c]->attributes()->TasaOCuota;
                    $datosxml["importer"] = $datosxml['impuestos_R'][$c]->attributes()->Importe;
                }

                if( isset($datosxml) && $datosxml['impuestos_R'] && $datosxml['impuestos_R'][$c]->attributes()->Impuesto == "002" ){
                    $datosxml["rettras"] += $datosxml['impuestos_R'][$c]->attributes()->Importe;
                }

                if( isset($datosxml) && $datosxml['impuestos_R'] && $datosxml['impuestos_R'][$c]->attributes()->Impuesto == "001" ){
                    $datosxml["ISRtras"] += $datosxml['impuestos_R'][$c]->attributes()->Importe;
                }
            }

            $datosxml["tasatrasExp"] = $datosxml["SubTotal"] - $datosxml["Total"] - $datosxml["Descuento"] + $datosxml["tasatras16"] + $datosxml["tasatras8"] + $datosxml["tasatras0"] + $datosxml["IEPStras"] + $datosxml["ISRtras"] - $datosxml["rettras"] - $datosxml["importer"];

            $datosxml["md5_hash"] = md5_file($xml_leer);

            if ($cargar_xml)
                $datosxml["textoxml"] = $str;
            
            return $datosxml;
        }else{
            return FALSE;
        }
        
    }

    function liberar_pendientes( $idfactura ){
        return $this->db->query("CALL actualizar_complementos( ? )", array( $idfactura ) );
    }

    function verificar_uuid( $uuid ){
        return $this->db->query("SELECT f.idsolicitud, f.idusuario, sl.nomdepto, f.feccrea, lu.nombre_completo 
            FROM facturas f
            LEFT JOIN solpagos sl ON sl.idsolicitud =  f.idsolicitud
            LEFT JOIN listado_usuarios lu ON lu.idusuario = sl.idusuario
            WHERE f.uuid = ?", array( $uuid ));
    }

    function verificaruuid( $uuid ){
        return $this->db->query("SELECT f.idsolicitud, f.idusuario, sl.nomdepto, f.feccrea, lu.nombre_completo FROM facturas f
        LEFT JOIN solpagos sl ON sl.idsolicitud =  f.idsolicitud
        LEFT JOIN listado_usuarios lu ON lu.idusuario = sl.idusuario
        WHERE f.uuid = ?", array( $uuid ));
    }

    function facturas_relacionadas( $uuids ){
        return $this->db->query("SELECT * FROM facturas WHERE facturas.uuid IN ($uuids)");
    }

    function verificar_empresa( $rfcempresa ){
        return $this->db->query("SELECT *, IFNULL(rf_empresa, 'Sin registro') regf_recep, IFNULL(cp_empresa, 'Sin registro') domf_recep FROM empresas WHERE empresas.rfc = '$rfcempresa'");
    }

    function verificar_proveedor( $rfcproveedor ){
        return $this->db->query("SELECT * FROM proveedores WHERE proveedores.rfc = '$rfcproveedor'");
    }

    function verifica_cat_prov( $rfcproveedor ){
        return $this->db->query("SELECT * FROM cat_proveedor WHERE cat_proveedor.rfc_proveedor = '$rfcproveedor'");
    }

    function insertar_solicitud($data ){
        $this->db->insert("solpagos", $data);
        return $this->db->insert_id();
    }

    function insertar_departamentoSolicitante($data ){
        $this->db->insert("departamentoSolicitante", $data);
        return $this->db->insert_id();
    }

    function insertar_solicitud_proyecto_oficina($data ){
        $this->db->insert("solicitud_proyecto_oficina", $data);
        return $this->db->insert_id();
    }

    function update_solicitud( $data, $idsolicitud ){
        // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
        $this->db->update("solpagos", $data, "idsolicitud = '$idsolicitud'");
    }

    function update_facturabysol( $idsolicitud, $tipo_factura ){
        // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
        $this->db->update("facturas", array( "tipo_factura" => $tipo_factura ), "idsolicitud = '$idsolicitud'");
    }

    function reenviar_factura( $idsolicitud ){
        // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
        return $this->db->query("UPDATE solpagos SET solpagos.idetapa = CASE WHEN solpagos.idetapa = 8 THEN 5 WHEN solpagos.idetapa = 21 THEN 20 END WHERE idsolicitud = '$idsolicitud' AND solpagos.idetapa IN (4, 8, 21)");
    }

    function insertar_factura( $datos_solicitud, $datos_factura ){

        $data = array(
            "fecfac"  => $datos_factura['fecha'],
            "foliofac"  => $datos_factura['folio'],
            "rfc_emisor" => $datos_factura['rfcemisor'],
            "rfc_receptor" => $datos_factura['rfcrecep'],
            "descripcion" => $datos_solicitud['descr'],
            "total"  => $datos_factura['Total'],
            "metpago"  => $datos_factura['MetodoPago'],
            "observaciones"  => $datos_solicitud['observaciones'],
            "idsolicitud" => $datos_solicitud['idsolicitud'],
            "uuid" => $datos_factura['uuid'],
            "nombre_archivo" => $datos_factura['nombre_xml'],
            "tipo_factura" => $datos_factura['tipo_factura'],
            //IMPUESTOS
            "subtotal" =>  $datos_factura['SubTotal'] ,
            "descuento" => $datos_factura['Descuento'] ,
            "impuestot" => $datos_factura['impuestot'] ,
            "tasacuotat" => $datos_factura['tasacuotat'],
            "importet" => $datos_factura['importet'],
            "impuestor" => $datos_factura['impuestor'],
            "tasacuotar" => $datos_factura['tasacuotar'],
            "importer" => $datos_factura['importer'],
            /***************************/
            "ISRtras" => $datos_factura['ISRtras'],
            "IEPStras" => $datos_factura['IEPStras'],
            "tasatras16" => $datos_factura['tasatras16'] ? $datos_factura['tasatras16'] : 0,
            "tasatras8" => $datos_factura['tasatras8'] ? $datos_factura['tasatras8'] : 0,
            "tasatras0" => $datos_factura['tasatras0'] ? $datos_factura['tasatras0'] : 0,
            "tasatrasExp" => $datos_factura['tasatrasExp'] ? $datos_factura['tasatrasExp'] : 0,
            "rettras" => $datos_factura["rettras"],
            /***************************/
            "md5_hash" => $datos_factura['md5_hash'],
            "idusuario" => isset($this->session->userdata("inicio_sesion")['id'])? $this->session->userdata("inicio_sesion")['id'] : $datos_solicitud['idusuario'],
            /***************************/
            "regf_emisor"=>$datos_factura['regfisemisor'],
            "regf_recep"=>$datos_factura['regfisrecep'],
            "domf_recep"=>$datos_factura['cpfisrecep'],
            "lugarexp"=>$datos_factura['LugarExpedicion'],
            "ver" => $datos_factura['version']
        );

        return $this->db->insert("facturas", $data);//
    }

    function insert_factura_pago( $idpago ){
        // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
        $this->db->update( "autpagos", array( "	idfactura" => $this->db->insert_id() ), "idpago = '$idpago'" );
    }

    function solicitudes_formato(){
        return $this->db->query("SELECT * FROM solpagos WHERE solpagos.idetapa = 3");
    }

    function bloquear_factura( $idsolicitud ){
        // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analaista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
        return $this->db->update("facturas", array("tipo_factura" => 0), "idsolicitud = '$idsolicitud' AND tipo_factura = 1");
    }

    //LISTADO DE TODAS LAS SOLICITUDES QUE HAN SIDO PAGAS DIRECTAS A PROVEEDOR
    function get_solicitudes_pagadas_area(){
        return $this->db->query("SELECT solpagos.idsolicitud, solpagos.justificacion, proveedores.nombre, solpagos.cantidad, DATE_FORMAT(solpagos.fecelab, '%d/%m/%Y') AS fecelab, autpagos.fecha_pago, IFNULL(notifi.visto, 1) AS visto, solpagos.moneda, solpagos.moneda, solpagos.moneda FROM solpagos INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor INNER JOIN ( SELECT autpagos.idsolicitud, DATE_FORMAT(MAX(autpagos.fecha_pago), '%d/%m/%Y') AS fecha_pago FROM autpagos GROUP BY autpagos.idsolicitud) AS autpagos ON autpagos.idsolicitud = solpagos.idsolicitud LEFT JOIN ( SELECT notificaciones.idsolicitud, notificaciones.visto FROM notificaciones WHERE notificaciones.idusuario = '".$this->session->userdata("inicio_sesion")['id']."' ) AS notifi ON notifi.idsolicitud = solpagos.idsolicitud WHERE solpagos.caja_chica != 1 AND solpagos.idetapa = 11 ORDER BY FIELD(solpagos.idetapa, '1, 8, 21, 3'), solpagos.fechaCreacion");
    }

    //LLAMA TODAS LAS FACTURAS QUE ESTEN EN BORRADOR O LISTAS PARA SER AUTORIZADAS POR DG
    function get_solicitudes_autorizadas_area(){
        return $this->db->query("SELECT solpagos.folio, solpagos.idsolicitud, solpagos.justificacion, proveedores.nombre, solpagos.cantidad, DATE_FORMAT(solpagos.fecelab, '%d/%m/%Y') AS fecelab, solpagos.idetapa, etapas.nombre AS etapa, solpagos.idusuario, solpagos.caja_chica, solpagos.servicio, IFNULL(notifi.visto, 1) AS visto, solpagos.moneda FROM solpagos INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor LEFT JOIN etapas ON etapas.idetapa = solpagos.idetapa LEFT JOIN ( SELECT notificaciones.idsolicitud, notificaciones.visto FROM notificaciones WHERE notificaciones.idusuario = '".$this->session->userdata("inicio_sesion")['id']."' ) AS notifi ON notifi.idsolicitud = solpagos.idsolicitud WHERE solpagos.idusuario = '".$this->session->userdata("inicio_sesion")['id']."' AND solpagos.idetapa IN (1, 3, 4, 8, 21, 25) ORDER BY FIELD(solpagos.idetapa, '1, 8, 21, 3'), solpagos.fechaCreacion");
    }
    
    //LLAMA TODAS LAS FACTURAS QUE ESTEN EN LAS DEMAS AREAS FUERA DEL AREA CORERSPONDIENTE O LISTAS PARA SER AUTORIZADAS POR DG
    function get_solicitudes_en_curso(){
        return $this->db->query("SELECT solpagos.folio, solpagos.idsolicitud, solpagos.justificacion, empresas.abrev, proveedores.nombre, solpagos.cantidad, DATE_FORMAT(solpagos.fecelab, '%d/%m/%Y') AS fecelab, etapas.nombre AS etapa, IFNULL(pago_aut.autorizado, 0) AS autorizado, IFNULL(cant_pag.pagado, 0) AS pagado, IFNULL(notifi.visto, 1) AS visto, solpagos.moneda FROM solpagos INNER JOIN empresas ON solpagos.idEmpresa = empresas.idempresa INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor INNER JOIN etapas ON etapas.idetapa = solpagos.idetapa LEFT JOIN ( SELECT autpagos.idsolicitud, SUM(autpagos.cantidad) AS autorizado FROM autpagos WHERE autpagos.estatus NOT IN (2) GROUP BY autpagos.idsolicitud ) AS pago_aut ON pago_aut.idsolicitud = solpagos.idsolicitud LEFT JOIN ( SELECT autpagos.idsolicitud, SUM(autpagos.cantidad) AS pagado FROM autpagos WHERE autpagos.fecha_pago IS NOT NULL GROUP BY autpagos.idsolicitud ) AS cant_pag ON cant_pag.idsolicitud = solpagos.idsolicitud LEFT JOIN ( SELECT notificaciones.idsolicitud, notificaciones.visto FROM notificaciones WHERE notificaciones.idusuario = '".$this->session->userdata("inicio_sesion")['id']."' ) AS notifi ON notifi.idsolicitud = solpagos.idsolicitud WHERE solpagos.idusuario = '".$this->session->userdata("inicio_sesion")['id']."' AND solpagos.idetapa NOT IN (1, 3, 4, 8, 21, 25) ORDER BY solpagos.fechaCreacion");        
    }

    function editar_solicitud( $idsolicitud, $data ){
        // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
        return $this->db->update("solpagos", $data, "idsolicitud = '$idsolicitud'");
    }

    function rechazada_da( $idsolicitud ){
        // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
        return $this->db->update("solpagos", array( "idetapa" => 6 ), "idsolicitud = '$idsolicitud' AND idetapa = 3");
    }

    function getSolicitud( $idsolicitud ){
        return $this->db->query("SELECT *,facturas.uuid, IF(solpagos.nomdepto != 'NOMINAS', proveedores.rfc, empresas.rfc ) rfc_proveedores, IF(solpagos.nomdepto != 'NOMINAS', empresas.rfc, proveedores.rfc ) rfc_empresas FROM solpagos INNER JOIN empresas ON solpagos.idEmpresa = empresas.idempresa INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor LEFT JOIN (SELECT *, MIN(facturas.feccrea) FROM facturas WHERE facturas.tipo_factura IN ( 1, 3 ) GROUP BY facturas.idsolicitud) facturas ON facturas.idsolicitud = solpagos.idsolicitud WHERE solpagos.idsolicitud = '$idsolicitud'");
    }

    function verificar_proveedor_permitido( $rfc ){
        return $this->db->query("SELECT * FROM proveedores WHERE proveedores.idproveedor IN ( SELECT solpagos.idProveedor FROM solpagos WHERE solpagos.idsolicitud NOT IN ( SELECT facturas.idsolicitud FROM facturas ) ) AND proveedores.rfc = '$rfc'");
    }

    function getFacturabySolicitud( $idsolicitud ){
        return $this->db->query("SELECT * FROM facturas WHERE facturas.idsolicitud = '$idsolicitud' AND facturas.tipo_factura = 1");
    }

    function getPagossfactura(){
        return $this->db->query("SELECT solpagos.idusuario, usuarios.nombres, solpagos.idsolicitud, usuarios.apellidos , facturas.foliofac, proveedores.nombre, solpagos.cantidad as cant_solpagos, solpagos.metoPago from solpagos inner join facturas on facturas.idsolicitud = solpagos.idsolicitud inner join proveedores on proveedores.idproveedor = solpagos.idProveedor inner join usuarios on solpagos.idusuario = usuarios.idusuario where facturas.tipo_factura=1 AND solpagos.idsolicitud NOT IN (SELECT facturas.idsolicitud FROM facturas where facturas.tipo_factura = 3) AND solpagos.idetapa = 10");
    }

    function getPagosSolicitud( $idPago ){
        return $this->db->query("SELECT *, facturas.uuid ,proveedores.rfc AS rfc_prov, empresas.rfc AS rfc_emp, autpagos.cantidad FROM solpagos INNER JOIN empresas ON solpagos.idEmpresa = empresas.idempresa INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor INNER JOIN autpagos ON autpagos.idsolicitud = solpagos.idsolicitud LEFT JOIN facturas ON facturas.idsolicitud = solpagos.idsolicitud WHERE autpagos.idpago = '$idPago'");
    }

    //LISTADO DE FACTURAS PENDIENTES POR PARCIALIDAD
    function getPagossProvision(){

        $where = "";
        $variables = [];
        $autPagos = ' LEFT JOIN autpagos AS ap ON lfacturas_complementos.idsolicitud = ap.idsolicitud AND lfacturas_complementos.fechaDis = ap.fechaDis;';
        $campo = ", ap.fecha_pago"; // UNION DE TABLA DONDE SE SACA LA FECHA CUANDO CXP DIO CONFIRMACION A SOLICITUD DE PAGO

        switch( $this->session->userdata("inicio_sesion")['rol'] ){
            case 'DA':
            case 'AS':
                $where = "WHERE nomdepto IN ( SELECT departamento depto FROM departament_usuario WHERE idusuario = ? ) OR idusuario IN ( SELECT idusuario FROM usuarios WHERE FIND_IN_SET( ?, sup ) )"; 
                $variables = [ $this->session->userdata("inicio_sesion")['id'], $this->session->userdata("inicio_sesion")['id'] ];                 
                break;
            case 'CP':
            case 'SU':
            case 'DP':
            case 'DG':
            case 'CC':
            case 'CE':
            case 'CX':
            case 'CT':
                break;
            default:
                if (in_array($this->session->userdata('inicio_sesion')["id"], ['2668', '2815', '2892'])) {
                    $where = "WHERE nomdepto IN ( SELECT departamento depto FROM departament_usuario WHERE idusuario = ? ) OR idusuario IN ( SELECT idusuario FROM usuarios WHERE FIND_IN_SET( ?, sup ) )";
                }else{
                    $where = "WHERE idusuario = ? OR idusuario IN ( SELECT idusuario FROM usuarios WHERE FIND_IN_SET( ?, sup ) )";
                }
                $variables = [ $this->session->userdata("inicio_sesion")['id'], $this->session->userdata("inicio_sesion")['id'] ];
                break;
        }

        return $this->db->query("SELECT 
                abrev,
                solicitado,
                lfacturas_complementos.cantidad,
                lfacturas_complementos.fechaDis,
                fecha_factura,
                folio_fiscal,
                foliofac,
                justificacion,
                metoPago,
                moneda,
                lfacturas_complementos.idsolicitud,
                nombre,
                nombre_capturista,
                nombre_responsable,
                nomdepto,
                lfacturas_complementos.tcomprobado,
                ccomprobado.ImpPagado impcomp,
                idusuario $campo
            FROM ( SELECT * FROM lfacturas_complementos $where ) lfacturas_complementos
            LEFT JOIN (
                SELECT 
                    IdDocumento, 
                    SUM(ImpPagado) ImpPagado,
                    1 pc
                FROM
                    temp_complementos
                WHERE
                    IdDocumento != '00000000-0000-0000-0000-000000000000'
                GROUP BY IdDocumento
            ) ccomprobado ON lfacturas_complementos.folio_fiscal = ccomprobado.IdDocumento
            $autPagos", $variables );
    }
    /********************************************/
    //LISTADO DE FACTURAS para etiquetar
    function getfacturaxetiquetar(){
        return $this->db->query("SELECT * FROM lfacturas_complementos  lfq
                                    LEFT JOIN (SELECT etqs.idsolicitud as idetqsol, etqs.idetiqueta, et.etiqueta FROM etiqueta_sol etqs JOIN etiquetas et ON etqs.idetiqueta = et.idetiqueta) etq ON etq.idetqsol = lfq.idsolicitud
                                    where etq.idetiqueta IS NULL" );
         
    }
    function getfactura_etquetada(){
        return $this->db->query("SELECT sol.idsolicitud, 
                            sol.cantidad,
                            sol.fecelab AS fecha_factura,
                            sol.folio AS foliofac,
                            sol.idusuario,
                            sol.justificacion,
                            sol.metoPago,
                            sol.moneda,
                            sol.nomdepto,
                            sol.cantidad as solicitado,
                            etqsol.idetiqueta,
                            etq.etiqueta,
                            em.abrev,
                            cp.nombre_completo AS nombre_capturista,
                            rp.nombre_completo AS nombre_responsable,
                            prv.nombre,
                            tt.tcomprobado,
                            tt.uuid AS folio_fiscal,
                            aup.fechaDis
                    FROM solpagos sol
                    INNER JOIN etiqueta_sol etqsol ON etqsol.idsolicitud = sol.idsolicitud
                    JOIN empresas em ON em.idempresa = sol.idEmpresa
                    JOIN etiquetas etq ON etq.idetiqueta = etqsol.idetiqueta
                    JOIN listado_usuarios cp ON cp.idusuario = sol.idusuario
                    JOIN listado_usuarios rp ON rp.idusuario = sol.idResponsable
                    JOIN proveedores prv ON prv.idproveedor = sol.idProveedor 
                    LEFT JOIN ( SELECT  idsolicitud, uuid, SUM(total) AS tcomprobado
                                FROM facturas
                                WHERE tipo_factura = 3 AND total > 0
                                GROUP BY idsolicitud
                                ) tt ON tt.idsolicitud = sol.idsolicitud
                    LEFT JOIN (	SELECT idsolicitud,
                                    idfactura,
                                    MAX(fechaDis) AS fechaDis,
                                    MAX(fecreg) AS fecreg,
                                    SUM((IF((tipoCambio IS NOT NULL), tipoCambio, 1) * cantidad)) AS cantidad
                                FROM autpagos
                                WHERE estatus IN (15 , 14, 16)
                                GROUP BY idsolicitud
                            ) aup ON aup.idsolicitud = sol.idsolicitud
                            ");
    }
    /********************************************/

    function completar_solicitud( $idsolicitud ){
        // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
        return $this->db->update("solpagos", array( "idetapa" => 11 ), "idsolicitud = '$idsolicitud' AND idetapa = 10");
    }

    function completar_solicitud_complemento( $uuids ){
        $query = $this->db->query("SELECT GROUP_CONCAT(facturas.idsolicitud) idsolicituds FROM facturas WHERE facturas.uuid IN ($uuids)")->row();
        //$this->db->query("UPDATE solpagos SET solpagos.idetapa = 11 WHERE FIND_IN_SET( solpagos.idsolicitud, '".$query->idsolicituds."' ) AND solpagos.idetapa = 10");
        //return $this->db->update("solpagos", array( "idetapa" => 11 ), "idsolicitud = '$idsolicitud' AND idetapa = 10");
        return $query;
        }

    function getGastosComprobados( $tipo_factura = 0 ){
        //OPTIMIZACION 27032020
        ini_set('memory_limit','-1');
        return $this->db->query("SELECT 
            f.idfactura,
            s.idsolicitud,
            pr.nproveedor,
            s.nomdepto,
            s.metoPago,
            emp.abrev,
            s.folio,
            s.cantidad,
            s.fechaCreacion,
            f.idsolicitudr,
            f.uuid
        FROM ( SELECT idfactura, idsolicitud, idsolicitudr, uuid FROM factura_registro WHERE tipo_factura IN ( 1, 3 ) )f 
        CROSS JOIN ( SELECT idsolicitud, nomdepto, folio, cantidad, fechaCreacion, metoPago, idproveedor, idempresa FROM solpagos WHERE caja_chica = ? ) s ON f.idsolicitud = s.idsolicitud
        CROSS JOIN ( SELECT idproveedor, nombre nproveedor FROM proveedores ) pr ON pr.idproveedor = s.idproveedor
        CROSS JOIN ( SELECT idempresa, abrev FROM empresas ) emp ON emp.idempresa = s.idempresa
        ORDER BY s.idsolicitud DESC", [ $tipo_factura ]);
    }

    function getTotalPagado( $idsolicitud ){
        $result = $this->db->query("SELECT * FROM facturas WHERE idsolicitud = '$idsolicitud' AND tipo_factura = 1");
        if($result->num_rows() == 0){
            $result = $this->db->query("SELECT cantidad,  IFNULL(SUM(total), 0) as total   FROM solpagos INNER JOIN empresas ON solpagos.idEmpresa = empresas.idempresa INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor LEFT JOIN facturas ON facturas.idsolicitud = solpagos.idsolicitud WHERE solpagos.idsolicitud = '$idsolicitud' AND facturas.tipo_factura =  3;");
            if( $result->num_rows() > 0 ){
                return $result->row()->total;
            }else{
                return 0;
            }
        }else{
            return -1;
        }
    }

    //APLICAMOS UNA NOTA DE CREDITO A LA SOLICITUD
    function aplicar_nota_credito( $idsolicitud, $cantidad ){
        // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = -1");
        $this->db->query("UPDATE solpagos 
            LEFT JOIN ( SELECT autpagos.idsolicitud, SUM(autpagos.cantidad) pagos_hechos FROM autpagos WHERE autpagos.idsolicitud = '$idsolicitud' GROUP BY autpagos.idsolicitud ) autpagos ON  autpagos.idsolicitud = solpagos.idsolicitud 
            SET 
                solpagos.idetapa = CASE WHEN ( solpagos.cantidad - $cantidad - IFNULL(pagos_hechos, 0) ) <= 0 AND solpagos.idetapa IN (7,9,10) THEN 11 ELSE solpagos.idetapa END, solpagos.cantidad = solpagos.cantidad - $cantidad
            WHERE solpagos.idsolicitud = '$idsolicitud'");
    }

    //GUARDAMOS LOS COMPLEMENTOS EN LA BASE TEMPORAL
    function insert_temporal( $complementos ){
        $this->db->insert_batch('temp_complementos', $complementos);
    }

    //GUARDAMOS EL CONTENIDO DEL XML EN LA BD
    function guardar_xml( $idfactura, $contenido ){
        return $this->db->insert( "xmls", array(
            "idfactura " => $idfactura,
            "informacion" => $contenido
        ));
    }

    function solicitud_especifica( $idsolicitud ){
        return $this->db->query("SELECT polizas_provision.idprovision,
            autpagos.estatus_pago,
            solpagos.idetapa,
            solpagos.proyecto,
            solpagos.folio,
            solpagos.moneda,
            solpagos.programado,
            IF( solpagos.programado IS NOT NULL, CASE WHEN solpagos.programado < 7 THEN TIMESTAMPDIFF(MONTH, CONCAT( YEAR(solpagos.fecelab),'-',MONTH(solpagos.fecelab),'-', DAY(solpagos.fecha_fin) ), IFNULL( solpagos.fecha_fin, DATE_ADD( solpagos.fecelab, INTERVAL IF( solpagos.idetapa NOT IN ( 11, 30 ), tpagos + 1, IFNULL(tpagos, 1) )  MONTH) ) ) ELSE TIMESTAMPDIFF(WEEK, solpagos.fecelab, IFNULL(solpagos.fecha_fin, DATE_ADD( solpagos.fecelab, INTERVAL IFNULL( tpagos, 1 )  WEEK) ) ) END, NULL ) mpagar,
            facturas.tipo_factura,
            IFNULL( facturas.uuid, 'NO' ) AS tienefac,
            SUBSTRING(facturas.uuid, - 5, 5) AS folifis,
            solpagos.metoPago,
            director.nombredir,
            capturista.nombre_completo,
            solpagos.caja_chica,
            solpagos.idsolicitud,
            solpagos.justificacion,
            solpagos.nomdepto,
            proveedores.nombre,
            solpagos.cantidad,
            empresas.abrev,
            DATE_FORMAT(solpagos.fechaCreacion, '%d/%m/%Y') AS feccrea,
            DATE_FORMAT(solpagos.fecha_autorizacion, '%d/%b/%Y') AS fecha_autorizacion,
            DATE_FORMAT(solpagos.fecelab, '%d/%m/%Y') AS fecelab,
            etapas.nombre AS etapa,
            IFNULL( autpagos.pagado, 0 ) AS pagado,
            DATE_FORMAT(autpagos.fechaDis, '%d/%b/%Y') AS fechaDis2,
            DATE_FORMAT(solpagos.fecha_autorizacion, '%d/%b/%Y') AS fech_auto
            FROM solpagos
            LEFT JOIN ( SELECT  autpagos.idsolicitud, COUNT(autpagos.idsolicitud) tpagos, MAX( autpagos.fechaDis ) fechaDis, SUM( autpagos.cantidad ) pagado, MIN( autpagos.estatus ) estatus_pago, MAX( autpagos.fecreg ) upago FROM autpagos GROUP BY autpagos.idsolicitud ) AS autpagos ON autpagos.idsolicitud = solpagos.idsolicitud
            INNER JOIN (SELECT  usuarios.idusuario, CONCAT(usuarios.nombres, ' ', usuarios.apellidos) AS nombre_completo FROM usuarios) AS capturista ON capturista.idusuario = solpagos.idusuario
            INNER JOIN ( SELECT CONCAT(usuarios.nombres, ' ', usuarios.apellidos) AS nombredir, usuarios.idusuario FROM usuarios ) AS director ON director.idusuario = solpagos.idResponsable
            INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor
            INNER JOIN etapas ON etapas.idetapa = solpagos.idetapa
            LEFT JOIN ( SELECT  *, MIN(facturas.feccrea) FROM facturas WHERE facturas.tipo_factura IN (1 , 3) GROUP BY facturas.idsolicitud ) AS facturas ON facturas.idsolicitud = solpagos.idsolicitud
            INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa
            LEFT JOIN ( SELECT  polizas_provision.idprovision, polizas_provision.idsolicitud, MIN( polizas_provision.fecreg ) FROM polizas_provision GROUP BY polizas_provision.idsolicitud) AS polizas_provision ON polizas_provision.idsolicitud = solpagos.idsolicitud
        WHERE
            solpagos.idsolicitud = '$idsolicitud'");
    }

    //LOG DE COMPLEMENTOS MASIVOS
    function get_facturas_uuid($uuids_xml, $uuid){
        $this->db->query("INSERT INTO logs ( idusuario, idsolicitud, tipomov, fecha ) SELECT '".$this->session->userdata("inicio_sesion")['id']."', idsolicitud, 'HA CARGADO UN COMPLEMENTO A LA SOLICITUD, CON FOLIO FISCAL ".$uuid."', '".date("Y-m-d H:i:s")."' FROM facturas where uuid IN(".implode(",", $uuids_xml).")");
        return $this->db->query("SELECT idsolicitud, uuid FROM facturas where uuid IN(".implode(",", $uuids_xml).")");
    }

    function multiLogs( $texto, $idsolicitud ){
        return $this->db->query("INSERT INTO logs ( idusuario, idsolicitud, tipomov, fecha ) SELECT '".$this->session->userdata("inicio_sesion")['id']."', idsolicitud, '$texto', '".date("Y-m-d H:i:s")."' FROM solpagos where idsolicitud IN( $idsolicitud )");

    }

    function insert_fac_etiqueta($data){
        return $this->db->insert("etiqueta_sol",$data);
    }

    function validarEtiqueta( $idsolicitud, $idetiqueta ){
        // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
        $idetapa = $this->db->query("SELECT idetapa FROM solpagos WHERE idsolicitud = ?", [ $idsolicitud ])->row()->idetapa;
        $finalizar = $this->db->query("SELECT fin FROM etiquetas WHERE idetiqueta = ?", [ $idetiqueta ])->row()->fin;

        if( $finalizar && in_array( $idetapa, [ 10 ] ) ){
            $this->db->update( "solpagos", [ "idetapa" => 11 ], "idsolicitud = $idsolicitud" );
        }
        
        if( $finalizar && in_array( $idetapa, [ 9 ] ) ){
            $this->db->update( "autpagos", [ "idfactura" => 0 ], "idsolicitud = $idsolicitud" );
        }

        return [ 
            "etapa" => $idetapa,
            "finalizar" => $finalizar
        ];

    }

    function insertar_solicitud_estado($data ){
        $this->db->insert("solicitud_estados", $data);
        return $this->db->insert_id();
   }

   function verificarEmpresaPorId($idempresa){
        return $this->db->query("SELECT *, IFNULL(rf_empresa, 'Sin registro') regf_recep, IFNULL(cp_empresa, 'Sin registro') domf_recep 
                                 FROM empresas
                                 WHERE empresas.idempresa = '$idempresa'");
   }
}