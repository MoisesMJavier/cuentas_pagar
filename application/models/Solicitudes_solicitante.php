<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Solicitudes_solicitante extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function leerxml($xml_leer, $cargar_xml = TRUE)
    {

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

        /**PARA IGNORAR LOS DOCUMENTOS POR URL**/
        if ($xml !== FALSE) {
            $datosxml = $xml->getNamespaces(true);
            $xml->registerXPathNamespace('t', $datosxml['tfd']);

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
                "rfcrecep" => $xml->xpath('//cfdi:Receptor')[0]['Rfc'],
                "nomrec" => $xml->xpath('//cfdi:Receptor')[0]['Nombre'],
                "regfisrecep" => isset($xml->xpath('//cdfi:Receptor')[0]['RegimenFiscalReceptor']) ? $xml->xpath('//cdfi:Receptor')[0]['RegimenFiscalReceptor'] : false,
                "cpfisrecep" =>  isset($xml->xpath('//cdfi:Receptor')[0]['DomicilioFiscalReceptor']) ? $xml->xpath('//cdfi:Receptor')[0]['DomicilioFiscalReceptor'] : false,
                "usocfdi" => $xml->xpath('//cfdi:Receptor')[0]['UsoCFDI'],
                "Descuento" => (isset($xml->xpath('//cfdi:Comprobante')[0]['Descuento']) ? $xml->xpath('//cfdi:Comprobante')[0]['Descuento'] : 0.00),
                "conceptos" => $xml->xpath('//cfdi:Concepto'),
                "moneda" => $xml->xpath('//@Moneda')[0],
                "uuid" => $xml->xpath('//t:TimbreFiscalDigital')[0]["UUID"],
                //"uuid" => ( $xml->xpath('//cfdi:CfdiRelacionados/cfdi:CfdiRelacionado') ? $xml->xpath('//@UUID')[ count( $xml->xpath('//@UUID') ) - 1 ] : $xml->xpath('//@UUID')[0] ),
                "uuidR" => $xml->xpath('//cfdi:CfdiRelacionados/cfdi:CfdiRelacionado'),
                "iddocumento" => $xml->xpath('//@IdDocumento'),
                "impuestos_traslados" => $xml->xpath('//cfdi:Conceptos/cfdi:Concepto/cfdi:Impuestos/cfdi:Traslados/cfdi:Traslado'),
                "impuestos_retenciones" => $xml->xpath('//cfdi:Conceptos/cfdi:Concepto/cfdi:Impuestos/cfdi:Retenciones/cfdi:Retencion'),
                "TipoCambio" => $xml->xpath('//cfdi:Comprobante')[0]['TipoCambio'],
                "TipoDeComprobante" => $xml->xpath('//cfdi:Comprobante')[0]['TipoDeComprobante'],
                "impuestos_R" => $xml->xpath('//cfdi:Comprobante/cfdi:Impuestos/cfdi:Retenciones/cfdi:Retencion'),
                "impuestos_T" => $xml->xpath('//cfdi:Comprobante/cfdi:Impuestos/cfdi:Traslados/cfdi:Traslado'),
                "TotImpRet" => isset($xml->xpath('//cfdi:Comprobante/cfdi:Impuestos')[0]['TotalImpuestosRetenidos']) ? $xml->xpath('//cfdi:Comprobante/cfdi:Impuestos')[0]['TotalImpuestosRetenidos'] : '---',
                "TotImpTras" => isset($xml->xpath('//cfdi:Comprobante/cfdi:Impuestos')[0]['TotalImpuestosTrasladados']) ? $xml->xpath('//cfdi:Comprobante/cfdi:Impuestos')[0]['TotalImpuestosTrasladados'] : '---'
            );

            $datosxml["impuestot"] = json_encode($datosxml['impuestos_T']);

            $datosxml["tasacuotat"] = null;
            $datosxml["importet"] = null;

            $datosxml["tasatras16"] = 0;
            $datosxml["tasatras8"] = 0;
            $datosxml["tasatras0"] = 0;
            $datosxml["ISRtras"] = 0;
            $datosxml["IEPStras"] = 0;
            $datosxml["rettras"] = 0;

            for ($c = 0; $c < count($datosxml['impuestos_T']); $c++) {
                if (isset($datosxml) && $datosxml['impuestos_T'] && $datosxml['impuestos_T'][$c]->attributes()->Impuesto == "002" && $datosxml['impuestos_T'][$c]->attributes()->TasaOCuota > 0) {
                    $datosxml["tasacuotat"] = $datosxml['impuestos_T'][$c]->attributes()->TasaOCuota;
                    $datosxml["importet"] = $datosxml['impuestos_T'][$c]->attributes()->Importe;
                }

                if (isset($datosxml) && $datosxml['impuestos_T'] && $datosxml['impuestos_T'][$c]->attributes()->Impuesto == "001") {
                    $datosxml["ISRtras"] += $datosxml["ISRtras"];
                }

                if (isset($datosxml) && $datosxml['impuestos_T'] && $datosxml['impuestos_T'][$c]->attributes()->Impuesto == "002") {

                    if (isset($datosxml['impuestos_T'][$c]->attributes()->TasaOCuota) && $datosxml['impuestos_T'][$c]->attributes()->TasaOCuota == .16) {
                        $datosxml["tasatras16"] = $datosxml['impuestos_T'][$c]->attributes()->Importe;
                    }

                    if (isset($datosxml['impuestos_T'][$c]->attributes()->TasaOCuota) && $datosxml['impuestos_T'][$c]->attributes()->TasaOCuota == .08) {
                        $datosxml["tasatras8"] = $datosxml['impuestos_T'][$c]->attributes()->Importe;
                    }

                    if (($datosxml['impuestos_T'][$c]->attributes()->TasaOCuota == FALSE || $datosxml['impuestos_T'][$c]->attributes()->TasaOCuota == 0) && $datosxml["tasatras0"] = $datosxml['impuestos_T'][$c]->attributes()->Importe) {
                        $datosxml["tasatras0"] = $datosxml['impuestos_T'][$c]->attributes()->Importe;
                    }
                }

                if (isset($datosxml) && $datosxml['impuestos_T'] && $datosxml['impuestos_T'][$c]->attributes()->Impuesto == "003") {
                    $datosxml["IEPStras"] += $datosxml['impuestos_T'][$c]->attributes()->Importe;
                }
            }

            $datosxml["impuestor"] = json_encode($datosxml['impuestos_R']);
            $datosxml["tasacuotar"] = null;
            $datosxml["importer"] = null;

            for ($c = 0; $c < count($datosxml['impuestos_R']); $c++) {
                if (isset($datosxml) && $datosxml['impuestos_R'] && $datosxml['impuestos_R'][$c]->attributes()->Impuesto == "003" && $datosxml['impuestos_R'][$c]->attributes()->TasaOCuota > 0) {
                    $datosxml["tasacuotar"] = $datosxml['impuestos_R'][$c]->attributes()->TasaOCuota;
                    $datosxml["importer"] = $datosxml['impuestos_R'][$c]->attributes()->Importe;
                }

                if (isset($datosxml) && $datosxml['impuestos_R'] && $datosxml['impuestos_R'][$c]->attributes()->Impuesto == "002") {
                    $datosxml["rettras"] += $datosxml['impuestos_R'][$c]->attributes()->Importe;
                }

                if (isset($datosxml) && $datosxml['impuestos_R'] && $datosxml['impuestos_R'][$c]->attributes()->Impuesto == "001") {
                    $datosxml["ISRtras"] += $datosxml['impuestos_R'][$c]->attributes()->Importe;
                }
            }

            $datosxml["tasatrasExp"] = $datosxml["SubTotal"] - $datosxml["Total"] - $datosxml["Descuento"] + $datosxml["tasatras16"] + $datosxml["tasatras8"] + $datosxml["tasatras0"] + $datosxml["IEPStras"] + $datosxml["ISRtras"] - $datosxml["rettras"] - $datosxml["importer"];

            $datosxml["md5_hash"] = md5_file($xml_leer);

            if ($cargar_xml)
                $datosxml["textoxml"] = $str;

            return $datosxml;
        } else {
            return FALSE;
        }
    }

    function verificar_uuid($uuid)
    {
        return $this->db->query("SELECT f.idsolicitud, f.idusuario, sl.nomdepto, f.feccrea, lu.nombre_completo FROM facturas f
        INNER JOIN solpagos sl ON sl.idsolicitud =  f.idsolicitud
        INNER JOIN listado_usuarios lu ON lu.idusuario = sl.idusuario
        WHERE f.uuid = ?", array($uuid));
    }

    function verificar_empresa($rfcempresa)
    {
        return $this->db->query("SELECT *,IFNULL(regf_recep, 'Sin registro') regf_recep, IFNULL(domf_recep, 'Sin registro') domf_recep  FROM empresas WHERE empresas.rfc = '$rfcempresa'");
    }

    function verificar_proveedor($rfcproveedor, $tipo_proveedor)
    {
        if ($tipo_proveedor)
            return $this->db->query("SELECT *,IFNULL(rf_proveedor, 'Sin registro') regf_emisor, IFNULL(bancos.nom_banco, 'SIN DEFINIR') nom_banco, proveedores.estatus AS eactual
                                     FROM proveedores
                                     LEFT JOIN ( SELECT bancos.idbanco, bancos.nombre nom_banco FROM bancos ) bancos 
                                        ON bancos.idbanco = proveedores.idbanco 
                                     WHERE proveedores.rfc = '$rfcproveedor' AND ( proveedores.estatus IN ( 4 ) OR ( tinsumo IS NOT NULL OR tinsumo != 0 ) )");
        else
            return $this->db->query("SELECT *,IFNULL(rf_proveedor, 'Sin registro') regf_emisor, IFNULL(bancos.nom_banco, 'SIN DEFINIR') nom_banco 
                                     FROM proveedores 
                                     LEFT JOIN ( SELECT bancos.idbanco, bancos.nombre nom_banco FROM bancos ) bancos 
                                        ON bancos.idbanco = proveedores.idbanco 
                                     INNER JOIN (SELECT proveedores.rfc, MAX(estatus) eactual 
                                                 FROM proveedores 
                                                 WHERE ( rfc IS NOT NULL OR rfc != '' ) AND proveedores.estatus NOT IN ( 0, 3, 4, 9 )
                                                 GROUP BY proveedores.rfc ) eactual 
                                        ON proveedores.rfc = eactual.rfc 
                                     WHERE proveedores.rfc = '$rfcproveedor' AND proveedores.estatus NOT IN ( 0, 3, 4, 9 )");
        //return $this->db->query("SELECT * FROM proveedores WHERE proveedores.rfc = '$rfcproveedor' AND ( proveedores.idproveedor NOT IN ( SELECT solpagos.idProveedor FROM solpagos WHERE solpagos.nomdepto = '".$this->session->userdata("inicio_sesion")['depto']."' AND solpagos.idsolicitud NOT IN ( SELECT facturas.idsolicitud FROM facturas WHERE facturas.tipo_factura = 3 )) AND proveedores.idproveedor NOT IN ( SELECT solpagos.idProveedor FROM solpagos WHERE solpagos.nomdepto = '".$this->session->userdata("inicio_sesion")['depto']."' AND solpagos.idsolicitud IN ( SELECT autpagos.idsolicitud FROM autpagos HAVING SUM( IF( autpagos.idfactura IS NULL, 1 , 0) ) > 1 ) ) )");
    }

    function insertar_solicitud($data)
    {
        $this->db->insert("solpagos", $data);
        return $this->db->insert_id();
    }

    function update_solicitud($data, $idsolicitud)
    {
        // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
        $this->db->update("solpagos", $data, "idsolicitud = '$idsolicitud'");
    }

    function update_facturabysol($idsolicitud, $tipo_factura)
    {
        // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
        $this->db->update("facturas", array("tipo_factura" => $tipo_factura), "idsolicitud = '$idsolicitud'");
    }

    function reenviar_factura($idsolicitud)
    {
        // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
        return $this->db->query("UPDATE solpagos SET solpagos.idetapa = CASE WHEN solpagos.idetapa = 8 THEN 5 WHEN solpagos.idetapa = 21 THEN 20 WHEN solpagos.idetapa = 30 THEN 7 END, solpagos.fecha_autorizacion = CASE WHEN solpagos.idetapa = 8 THEN '" . date("Y-m-d H:i:s") . "' ELSE solpagos.fecha_autorizacion END  WHERE idsolicitud = '$idsolicitud' AND solpagos.idetapa IN (4, 8, 21)");
    }

    function insertar_factura($datos_solicitud, $datos_factura)
    {

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
            "subtotal" => $datos_factura['SubTotal'],
            "descuento" => $datos_factura['Descuento'],
            "impuestot" => $datos_factura['impuestot'],
            "tasacuotat" => $datos_factura['tasacuotat'],
            "importet" => $datos_factura['importet'],
            "impuestor" => $datos_factura['impuestor'],
            "tasacuotar" => $datos_factura['tasacuotar'],
            "importer" => $datos_factura['importer'],
            /***************************/
            "ISRtras" => $datos_factura['ISRtras'],
            "IEPStras" => $datos_factura['IEPStras'],
            "tasatras16" => $datos_factura['tasatras16'],
            "tasatras8" => $datos_factura['tasatras8'],
            "tasatras0" => $datos_factura['tasatras0'],
            "tasatrasExp" => $datos_factura['tasatrasExp'],
            "rettras" => $datos_factura["rettras"],
            /***************************/
            "regf_emisor" => $datos_factura['regfisemisor'],
            "regf_recep" => $datos_factura['regfisrecep'],
            "domf_recep" => $datos_factura['cpfisrecep'],
            "md5_hash" => $datos_factura['md5_hash'],
            "idusuario" => $this->session->userdata("inicio_sesion")['id']
        );

        return $this->db->insert("facturas", $data);
    }

    //GUARDAMOS EL CONTENIDO DEL XML EN LA BD
    function guardar_xml($idfactura, $contenido)
    {
        $this->db->insert("xmls", array(
            "idfactura " => $idfactura,
            "informacion" => $contenido
        ));
    }

    function insert_factura_pago($idpago)
    {
        // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
        $this->db->update("autpagos", array("	idfactura" => $this->db->insert_id()), "idpago = '$idpago'");
    }

    function solicitudes_formato()
    {
        switch ($this->session->userdata("inicio_sesion")['rol']) {
            case 'DA':
            case 'CI':

            case 'CP':
                return $this->db->query("SELECT solpagos.idsolicitud FROM solpagos WHERE solpagos.idusuario = '" . $this->session->userdata("inicio_sesion")['id'] . "' AND solpagos.idetapa IN ( 1, 3 )");
                break;
            case 'PV':
            case 'AD':
            case 'CAD':
            case 'GAD':
            case 'CPV':
                return $this->db->query("SELECT solpagos.idsolicitud FROM solpagos WHERE solpagos.idusuario = '" . $this->session->userdata("inicio_sesion")['id'] . "' AND solpagos.idetapa = 1");
                break;
            case 'SU':
                return $this->db->query("SELECT solpagos.idsolicitud FROM solpagos WHERE solpagos.nomdepto = ? AND solpagos.idetapa IN ( 1, 3 )", array($this->session->userdata("inicio_sesion")['depto']));
                break;
            case 'SB':
            case 'CC':
                return $this->db->query("SELECT solpagos.idsolicitud FROM solpagos WHERE solpagos.nomdepto = '" . $this->session->userdata("inicio_sesion")['depto'] . "' AND solpagos.idetapa = 3");
                break;
            case 'AS':
            case 'CA':
            case 'FP':
                return $this->db->query("SELECT solpagos.idsolicitud FROM solpagos WHERE solpagos.idusuario = '" . $this->session->userdata("inicio_sesion")['id'] . "' AND solpagos.idetapa IN ( 1, 3 )");
                break;
            case 'CJ':
                return $this->db->query("SELECT solpagos.idsolicitud FROM solpagos WHERE ( ( solpagos.idResponsable = '" . $this->session->userdata("inicio_sesion")['id'] . "' AND solpagos.caja_chica = 1 ) OR solpagos.idusuario = '" . $this->session->userdata("inicio_sesion")['id'] . "' ) AND solpagos.idetapa IN ( 1, 3 )");
                break;
            case 'CE':
            case 'CX':
                return $this->db->query("SELECT solpagos.idsolicitud FROM solpagos WHERE ( solpagos.idAutoriza = '" . $this->session->userdata("inicio_sesion")['id'] . "' ) AND solpagos.idetapa IN ( 3 )");
                break;
        }
    }

    function solicitudes_formato_otros_gastos()
    {
        switch ($this->session->userdata("inicio_sesion")['rol']) {
            case 'DA':
            case 'CI':
                return $this->db->query("SELECT * FROM solpagos WHERE solpagos.idusuario IN ( SELECT usuarios.idusuario FROM usuarios  WHERE usuarios.depto = '" . $this->session->userdata("inicio_sesion")['depto'] . "' ) AND solpagos.nomdepto != '" . $this->session->userdata("inicio_sesion")['depto'] . "' AND solpagos.idetapa IN ( 1, 3 ) AND SUBDATE( solpagos.fecelab, 7) <= NOW()");
                break;
            default:
                /** INICIO FECHA: 31-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                return $this->db->query("SELECT 
                        * 
                    FROM solpagos 
                    WHERE solpagos.idusuario = '" . $this->session->userdata("inicio_sesion")['id'] . "' 
                    AND solpagos.nomdepto != '" . $this->session->userdata("inicio_sesion")['depto'] . "' 
                    AND solpagos.idetapa IN ( 1, 3, 52 )");
                break;
                /** FIN FECHA: 31-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
        }
    }

    function bloquear_factura($idsolicitud)
    {
        // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
        return $this->db->update("facturas", array("tipo_factura" => 0), "idsolicitud = '$idsolicitud' AND tipo_factura IN (1, 3)");
    }

    //LISTADO DE TODAS LAS SOLICITUDES QUE HAN SIDO PAGAS DIRECTAS A PROVEEDOR
    function get_solicitudes_pagadas_area()
    {
        return $this->db->query("SELECT solpagos.idsolicitud, solpagos.justificacion, proveedores.nombre, solpagos.cantidad, DATE_FORMAT(solpagos.fecelab, '%d/%m/%Y') AS fecelab, autpagos.fecha_pago, IFNULL(notifi.visto, 1) AS visto, solpagos.moneda, solpagos.moneda, solpagos.moneda FROM solpagos INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor INNER JOIN ( SELECT autpagos.idsolicitud, DATE_FORMAT(MAX(autpagos.fecha_pago), '%d/%m/%Y') AS fecha_pago FROM autpagos GROUP BY autpagos.idsolicitud) AS autpagos ON autpagos.idsolicitud = solpagos.idsolicitud LEFT JOIN ( SELECT notificaciones.idsolicitud, notificaciones.visto FROM notificaciones WHERE notificaciones.idusuario = '" . $this->session->userdata("inicio_sesion")['id'] . "' ) AS notifi ON notifi.idsolicitud = solpagos.idsolicitud WHERE solpagos.caja_chica != 1 AND solpagos.idetapa = 11 ORDER BY FIELD(solpagos.idetapa, '1, 8, 21, 3'), solpagos.fechaCreacion");
    }

    //LLAMA TODAS LAS FACTURAS QUE ESTEN EN BORRADOR O LISTAS PARA SER AUTORIZADAS POR DG
    function get_solicitudes_autorizadas_area()
    {

        $solpagos = "";
        $variables = [];
        switch ($this->session->userdata("inicio_sesion")['rol']) {
            case 'DA':
            case 'CI':
            case 'SU':
                if ($this->session->userdata('inicio_sesion')["id"] == '257') {
                    return $this->db->query("SELECT 
                        solpagos.folio, 
                        solpagos.etapa netapa,
                        solpagos.condominio, 
                        solpagos.crecibo, 
                        solpagos.requisicion, 
                        solpagos.orden_compra, 
                        solpagos.homoclave,
                        solpagos.metoPago, 
                        solpagos.prioridad, 
                        solpagos.nomdepto, 
                        solpagos.prioridad, 
                        solpagos.idAutoriza, 
                        ifnull(solpagos.proyecto, pd.nombre) proyecto, 
                        solpagos.idsolicitud, 
                        solpagos.justificacion, 
                        proveedores.nombre, 
                        proveedores.tipo_prov,
                        solpagos.cantidad, 
                        DATE_FORMAT(solpagos.fecelab, '%d/%m/%Y') AS fecelab, 
                        solpagos.idetapa, etapas.nombre AS etapa, 
                        capturista.nombre_completo, 
                        solpagos.idusuario, 
                        solpagos.caja_chica, 
                        solpagos.servicio, 
                        IFNULL(notifi.visto, 1) AS visto, 
                        solpagos.moneda, 
                        IFNULL(facturas.uuid, 'SF') AS uuid, 
                        empresas.abrev AS nempresa, 
                        contrato.nombre_contrato,
                        os.idOficina,
                        os.nombre oficina,
                        pd.idProyectos,
                        pd.nombre proyectoNuevo,
                        solpagos.idResponsable,
                        tsp.nombre servicioPartida,
                        CASE 
                            WHEN solpagos.caja_chica = 2 AND spo.idTipoServicioPartida = 7 THEN 
                                tdc.idtitular
                            WHEN solpagos.caja_chica = 2 AND spo.idTipoServicioPartida <> 7 THEN
                                tarjeta.da
                            ELSE 
                                null
                        END as idtitular,
                        tarjeta.nombre_tarjeta, -- FECHA: 06-08-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com>
                        responsable_cch.nombre_reembolso_cch, -- FECHA: 06-08-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com>
                        solpagos.ref_bancaria /** FECHA: 23-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                      FROM solpagos
                        INNER JOIN (
                            SELECT usuarios.idusuario
                                ,CONCAT ( usuarios.nombres,' ',usuarios.apellidos) AS nombre_completo
                            FROM usuarios
                            ) AS capturista ON capturista.idusuario = solpagos.idusuario
                        INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa
                        LEFT JOIN (
                            SELECT *,
                                MIN(facturas.feccrea)
                            FROM facturas
                            WHERE facturas.tipo_factura IN ( 1, 3 )
                            GROUP BY facturas.idsolicitud
                            ) AS facturas ON facturas.idsolicitud = solpagos.idsolicitud
                        INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor
                        LEFT JOIN etapas ON etapas.idetapa = solpagos.idetapa
                        LEFT JOIN (
                            SELECT notificaciones.idsolicitud
                                ,notificaciones.visto
                            FROM notificaciones
                            WHERE notificaciones.idusuario = ?
                            GROUP BY notificaciones.idsolicitud
                                ,notificaciones.idusuario
                            ) AS notifi ON notifi.idsolicitud = solpagos.idsolicitud
                        LEFT JOIN (
                            SELECT idsolicitud
                                ,nombre nombre_contrato
                            FROM contratos
                            INNER JOIN sol_contrato ON sol_contrato.idcontrato = contratos.idcontrato
                            ) contrato ON contrato.idsolicitud = solpagos.idsolicitud
                        LEFT JOIN solicitud_proyecto_oficina spo ON spo.idsolicitud = solpagos.idsolicitud
                        LEFT JOIN oficina_sede os ON spo.idoficina = os.idoficina
                        LEFT JOIN proyectos_departamentos pd ON spo.idProyectos = pd.idProyectos
                        LEFT JOIN tcredito tdc ON solpagos.idResponsable = tdc.idtcredito
                        LEFT JOIN ( SELECT usuarios.idusuario, CONCAT( usuarios.nombres,' ', usuarios.apellidos ) AS nombre_tarjeta, usuarios.da FROM usuarios ) AS tarjeta ON tarjeta.idusuario = tdc.idtitular
                        LEFT JOIN tipo_servicio_partidas tsp ON spo.idTipoServicioPartida = tsp.idTipoServicioPartida
                        INNER JOIN ( SELECT  uag.idusuario, 
                                            GROUP_CONCAT(DISTINCT uag.permiso ORDER BY uag.permiso SEPARATOR ',') AS permisos,
                                            GROUP_CONCAT(dt.departamento) AS deptos
                                    FROM usuario_aut_gastos AS uag
                                    INNER JOIN departamentos AS dt
                                        ON uag.iddepartamento = dt.iddepartamentos
                                    WHERE dt.estatus = 1 AND uag.estatus = 1 AND uag.idusuario = ?
                                    GROUP BY uag.idusuario, uag.permiso) AS pud
                            ON FIND_IN_SET(solpagos.caja_chica, pud.permisos) -- @author DANTE ALDAIR GUERRERO ALDANA | FECHA DE CAMBIOS: 17-07-2024 | FAVOR DE NO REMOVER ESTA SECCION DE LA QUERY
                        LEFT JOIN ( -- INICIO FECHA: 06-08-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com>
                            SELECT
                                cajas_ch.idusuario
                                , GROUP_CONCAT(cajas_ch.nombre_reembolso_ch SEPARATOR ', ') nombre_reembolso_cch
                            FROM cajas_ch
                            GROUP BY cajas_ch.idusuario
                        ) AS responsable_cch ON responsable_cch.idusuario = solpagos.idResponsable AND (solpagos.caja_chica = 1 OR solpagos.caja_chica = 4) -- FIN FECHA: 06-08-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com>
                        WHERE (solpagos.nomdepto IN ('ADMINISTRACION') OR FIND_IN_SET(solpagos.nomdepto, pud.deptos) )
                            AND ( solpagos.idetapa IN ( 12, 3, 25)
                                OR ( solpagos.idusuario = ?
                                AND solpagos.idetapa IN (1, 4, 6, 8, 21)
                                )
                            )
                        ORDER BY notifi.visto DESC
                            ,FIELD(solpagos.idetapa, 1, 12, 3, 8, 21, 30)
                            ,solpagos.fechaCreacion", [
                        $this->session->userdata("inicio_sesion")['id'],
                        $this->session->userdata("inicio_sesion")['id'],
                        $this->session->userdata("inicio_sesion")['id']
                    ]);
                } else {
                    return $this->db->query("SELECT solpagos.etapa netapa, solpagos.ref_bancaria, /** FECHA: 23-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                        solpagos.condominio, solpagos.folio, solpagos.crecibo, solpagos.requisicion, solpagos.orden_compra, 
                        solpagos.homoclave, solpagos.metoPago, solpagos.prioridad, solpagos.nomdepto, 
                        solpagos.prioridad, solpagos.idAutoriza, solpagos.proyecto, solpagos.idsolicitud, 
                        solpagos.justificacion, proveedores.nombre,proveedores.tipo_prov, solpagos.cantidad, 
                        DATE_FORMAT(solpagos.fecelab, '%d/%m/%Y') AS fecelab, solpagos.idetapa, etapas.nombre AS etapa, 
                        capturista.nombre_completo, solpagos.idusuario, solpagos.caja_chica, solpagos.servicio, 1 visto, 
                        solpagos.moneda, IFNULL(facturas.uuid, 'SF') AS uuid, empresas.abrev AS nempresa, 
                        contrato.nombre_contrato,
                        os.idOficina,
                        os.nombre oficina,
                        pd.idProyectos,
                        pd.nombre proyectoNuevo,
                        solpagos.idResponsable,
                        CASE 
                            WHEN solpagos.caja_chica = 2 AND spo.idTipoServicioPartida = 7 THEN 
                                tdc.idtitular
                            WHEN solpagos.caja_chica = 2 AND spo.idTipoServicioPartida <> 7 THEN
                                capturista.da
                            ELSE 
                                null
                        END as idtitular,
                        tarjeta.nombre_tarjeta,
                        tsp.nombre servicioPartida, --  FECHA: 06-08-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com>
                        responsable_cch.nombre_reembolso_cch --  FECHA: 06-08-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com>

                        FROM ( 
                            SELECT * FROM solpagos sol WHERE sol.nomdepto = ? AND sol.idetapa IN ( 3, 25 )
                            UNION
                            SELECT sol.* FROM solpagos sol 
                            JOIN ( SELECT usuarios.idusuario FROM usuarios WHERE FIND_IN_SET( ?, usuarios.sup ) ) usuarios ON usuarios.idusuario = sol.idusuario
                            WHERE sol.idetapa IN ( 3, 25 )
                            UNION
                            SELECT * FROM solpagos sol WHERE sol.idusuario = ? AND sol.idetapa IN ( 1, 4, 6, 8, 21 ) 
                            UNION
                            SELECT 
                                sol.*
                            FROM ( SELECT * FROM solpagos WHERE idetapa IN ( 3, 25 ) ) sol
                            INNER JOIN (
                                SELECT 
                                    uag.permiso,
                                    d.departamento
                                FROM ( SELECT iddepartamento, permiso FROM usuario_aut_gastos WHERE estatus = 1 AND idusuario = ? ) uag
                                JOIN departamentos d ON d.iddepartamentos = uag.iddepartamento
                            ) uag ON sol.nomdepto = uag.departamento AND FIND_IN_SET( sol.caja_chica, uag.permiso )
                        ) solpagos 
                        INNER JOIN ( SELECT usuarios.idusuario, CONCAT( usuarios.nombres,' ', usuarios.apellidos ) AS nombre_completo, usuarios.da FROM usuarios ) AS capturista ON capturista.idusuario = solpagos.idusuario
                        INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa 
                        LEFT JOIN (SELECT *, MIN(facturas.feccrea) FROM facturas WHERE facturas.tipo_factura IN ( 1, 3 ) 
                                    GROUP BY facturas.idsolicitud) AS facturas ON facturas.idsolicitud = solpagos.idsolicitud 
                        INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor 
                        LEFT JOIN etapas ON etapas.idetapa = solpagos.idetapa
                        LEFT JOIN solicitud_proyecto_oficina spo ON spo.idsolicitud = solpagos.idsolicitud 
                        LEFT JOIN oficina_sede os ON spo.idoficina = os.idoficina
                        LEFT JOIN proyectos_departamentos pd ON spo.idProyectos = pd.idProyectos
                        LEFT JOIN ( SELECT idsolicitud, nombre nombre_contrato FROM contratos 
                                    INNER JOIN sol_contrato ON sol_contrato.idcontrato = contratos.idcontrato ) contrato ON contrato.idsolicitud = solpagos.idsolicitud 
                        LEFT JOIN tcredito tdc ON solpagos.idResponsable = tdc.idtcredito
                        LEFT JOIN tipo_servicio_partidas tsp ON spo.idTipoServicioPartida = tsp.idTipoServicioPartida
                        LEFT JOIN ( SELECT usuarios.idusuario, CONCAT( usuarios.nombres,' ', usuarios.apellidos ) AS nombre_tarjeta FROM usuarios ) AS tarjeta ON tarjeta.idusuario = tdc.idtitular 
                        LEFT JOIN ( -- INICIO FECHA: 06-08-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com>
                            SELECT
                                cajas_ch.idusuario
                                , GROUP_CONCAT(cajas_ch.nombre_reembolso_ch SEPARATOR ', ') nombre_reembolso_cch
                            FROM cajas_ch
                            GROUP BY cajas_ch.idusuario
                        ) AS responsable_cch ON responsable_cch.idusuario = solpagos.idResponsable AND (solpagos.caja_chica = 1 OR solpagos.caja_chica = 4) -- FIN FECHA: 06-08-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com>
                        ORDER BY FIELD (solpagos.idetapa, 3, 1, 12, 8, 21, 30), solpagos.fechaCreacion", [
                        $this->session->userdata("inicio_sesion")['depto'],
                        $this->session->userdata("inicio_sesion")['id'],
                        $this->session->userdata("inicio_sesion")['id'],
                        $this->session->userdata("inicio_sesion")['id']
                    ]);
                }
                break;
            case 'AS':
                //SOLUCION TEMPORAL POR PARTE DE LAS NOTIFIACIONES.
                //Fecha : 12-Agosto-2025 @author Efrain Martinez programador.analista38@ciudadmaderas.com
                //Se agrega la etapa 30 (cancelada) al select para que cuando el usuario sea el dueño de la solicitud le aparezca en su panel y despues de ver
                //la solicitud se y recargue su pagina no le aparezcan estas solicitudes canceladas.
                $solpagos = "SELECT solpagos.*
                             FROM solpagos 
                             JOIN ( SELECT * FROM departamento WHERE idusuario = ? ) departamento 
                                ON solpagos.nomdepto = departamento.depto
                             WHERE solpagos.idetapa IN (1, 3, 4, 6, 8, 21, 25) AND solpagos.idproceso IS NULL
                            UNION
                             SELECT solpagos.*
                             FROM solpagos 
                             JOIN ( SELECT usuarios.idusuario FROM usuarios WHERE FIND_IN_SET( ?, usuarios.sup ) ) usuarios 
                                ON solpagos.idusuario = usuarios.idusuario
                             WHERE solpagos.idetapa IN (1, 3, 4, 6, 8, 21, 25) AND solpagos.idproceso IS NULL
                            UNION
                             SELECT *
                             FROM solpagos 
                             WHERE solpagos.idetapa IN (1, 3, 4, 6, 8, 21, 25) AND solpagos.idproceso IS NULL AND solpagos.idusuario = ?";

                $variables = [
                    $this->session->userdata("inicio_sesion")['id'],
                    $this->session->userdata("inicio_sesion")['id'],
                    $this->session->userdata("inicio_sesion")['id'],
                    $this->session->userdata("inicio_sesion")['id']
                ];
                break;
            case 'CP':

                if ($this->session->userdata("inicio_sesion")['id'] == $this->session->userdata("inicio_sesion")['da']) {
                    return $this->db->query("SELECT solpagos.etapa netapa,
                                                    solpagos.condominio, 
                                                    solpagos.folio, 
                                                    solpagos.crecibo, 
                                                    solpagos.requisicion, 
                                                    solpagos.orden_compra, 
                                                    solpagos.homoclave, 
                                                    solpagos.metoPago, 
                                                    solpagos.prioridad, 
                                                    solpagos.nomdepto, 
                                                    solpagos.prioridad, 
                                                    solpagos.idAutoriza, 
                                                    ifnull(solpagos.proyecto, pd.nombre) as proyecto, 
                                                    solpagos.idsolicitud, 
                                                    solpagos.justificacion, 
                                                    proveedores.nombre,
                                                    proveedores.tipo_prov, 
                                                    solpagos.cantidad, 
                                                    DATE_FORMAT(solpagos.fecelab, '%d/%m/%Y') AS fecelab, 
                                                    solpagos.idetapa, 
                                                    etapas.nombre AS etapa, 
                                                    capturista.nombre_completo, 
                                                    solpagos.idusuario, 
                                                    solpagos.caja_chica, 
                                                    solpagos.servicio, 
                                                    IFNULL(notifi.visto, 1) AS visto, 
                                                    solpagos.moneda, 
                                                    IFNULL(facturas.uuid, 'SF') AS uuid, 
                                                    empresas.abrev AS nempresa, 
                                                    contrato.nombre_contrato,
                                                    solpagos.ref_bancaria /** FECHA: 23-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                                            FROM solpagos 
                                            INNER JOIN ( SELECT usuarios.idusuario, CONCAT( usuarios.nombres,' ', usuarios.apellidos ) AS nombre_completo
                                                         FROM usuarios ) AS capturista 
                                                ON capturista.idusuario = solpagos.idusuario 
                                            INNER JOIN empresas 
                                                ON empresas.idempresa = solpagos.idEmpresa 
                                            LEFT JOIN ( SELECT *, MIN(facturas.feccrea) 
                                                        FROM facturas
                                                        WHERE facturas.tipo_factura IN ( 1, 3 )
                                                        GROUP BY facturas.idsolicitud) AS facturas 
                                                ON facturas.idsolicitud = solpagos.idsolicitud 
                                            INNER JOIN proveedores 
                                                ON proveedores.idproveedor = solpagos.idProveedor 
                                            LEFT JOIN etapas 
                                                ON etapas.idetapa = solpagos.idetapa 
                                            LEFT JOIN ( SELECT notificaciones.idsolicitud, notificaciones.visto 
                                                        FROM notificaciones 
                                                        WHERE notificaciones.idusuario = '" . $this->session->userdata("inicio_sesion")['id'] . "' 
                                                        GROUP BY notificaciones.idsolicitud, notificaciones.idusuario ) AS notifi ON notifi.idsolicitud = solpagos.idsolicitud 
                                            LEFT JOIN ( SELECT idsolicitud, nombre nombre_contrato FROM contratos INNER JOIN sol_contrato 
                                                ON sol_contrato.idcontrato = contratos.idcontrato ) contrato ON contrato.idsolicitud = solpagos.idsolicitud 
                                            LEFT JOIN solicitud_proyecto_oficina spo 
                                                ON spo.idsolicitud = solpagos.idsolicitud 
                                            LEFT JOIN proyectos_departamentos pd
                                                ON spo.idProyectos = pd.idProyectos 
                                            WHERE ( solpagos.idResponsable = '" . $this->session->userdata("inicio_sesion")['id'] . "' AND solpagos.caja_chica = 1 ) AND 
                                                  ( ( solpagos.idetapa IN (1, 12, 3, 25) OR ( solpagos.idetapa = 2 AND solpagos.rcompra = 1 ) ) OR ( solpagos.idusuario = '" . $this->session->userdata("inicio_sesion")['id'] . "' AND solpagos.idetapa IN ( 4, 6, 8, 21 ) ) ) 
                                            ORDER BY notifi.visto DESC, FIELD (solpagos.idetapa, 1, 12, 3, 8, 21, 30), solpagos.fechaCreacion");
                    break;
                }
                //pimer select se va, confirmar su quiren ver todas las cajas chicas
                $solpagos = "SELECT solpagos.* 
                             FROM solpagos 
                             WHERE solpagos.idusuario = ? AND
                                   solpagos.nomdepto = ? AND 
                                   solpagos.idetapa IN (1, 3, 4, 6, 8, 21, 25) 
                             UNION
                             SELECT solpagos.* 
                             FROM solpagos 
                             JOIN ( SELECT usuarios.idusuario 
                                    FROM usuarios 
                                    WHERE FIND_IN_SET( ?, usuarios.sup ) ) u 
                                ON solpagos.idetapa IN (1, 3, 4, 6, 8, 21, 25) AND u.idusuario = solpagos.idusuario 
                             UNION
                             SELECT solpagos.* 
                             FROM solpagos -- + where cajachicas = 1
                             JOIN ( SELECT departamento
                                    FROM usuario_depto up
                                    JOIN departamentos dp 
                                        ON up.iddepartamento = dp.iddepartamentos AND up.idusuario = ? ) d 
                                ON solpagos.idetapa IN (1, 3, 4, 6, 8, 21, 25) AND d.departamento = solpagos.nomdepto AND solpagos.idusuario != ?";
                //si son todos los registros este join no va
                $variables = [
                    $this->session->userdata("inicio_sesion")['id'],
                    $this->session->userdata("inicio_sesion")['depto'] == 'CI-COMPRAS' ? 'ADMINISTRACION' : $this->session->userdata("inicio_sesion")['depto'],
                    $this->session->userdata("inicio_sesion")['id'],
                    $this->session->userdata("inicio_sesion")['id'],
                    $this->session->userdata("inicio_sesion")['id'],
                    $this->session->userdata("inicio_sesion")['id']
                ];

                break;
            default:
                if ($this->session->userdata("inicio_sesion")['depto'] == 'COMERCIALIZACION' && in_array($this->session->userdata("inicio_sesion")['id'], [69, 145, 148, 149, 151, 155, 156, 184, 185, 1850, 2742, 2798, 2800, 2801, 2802, 2803, 2804, 2806, 2807, 2808, 3211, 3213, 3214, 3217, 3228])) {
                    $solpagos = "SELECT solpagos.*, null AS idtitular, null AS nombre_tarjeta FROM solpagos WHERE solpagos.idusuario = ? AND solpagos.idetapa IN (1, 3, 4, 6, 8, 21, 25) 
                                UNION
                                SELECT solpagos.*, null AS idtitular, null AS nombre_tarjeta FROM solpagos 
                                JOIN ( SELECT usuarios.idusuario FROM usuarios WHERE FIND_IN_SET( ?, usuarios.sup ) ) u ON solpagos.idetapa IN (1, 3, 4, 6, 8, 21, 25) AND u.idusuario = solpagos.idusuario
                                UNION
                                SELECT sp.*,
                                    CASE 
                                        WHEN sp.caja_chica = 2 AND spo.idTipoServicioPartida = 7 THEN 
                                            tc.idtitular
                                        WHEN sp.caja_chica = 2 AND spo.idTipoServicioPartida <> 7 THEN
                                            us.da
                                        ELSE 
                                            null
                                    END as idtitular,
                                    CONCAT( us.nombres,' ', us.apellidos ) AS nombre_tarjeta
                                FROM solpagos AS sp
                                INNER JOIN tcredito AS tc
                                    ON sp.idResponsable = tc.idtcredito AND tc.idtitular = ?
                                INNER JOIN solicitud_proyecto_oficina AS spo
                                    ON sp.idsolicitud = spo.idsolicitud
                                INNER JOIN usuarios AS us
                                    ON sp.idusuario = us.idusuario
                                INNER JOIN usuarios AS ustc
                                    ON tc.idtitular= ustc.idusuario
                                WHERE sp.idetapa IN (1, 3, 4, 6, 8, 21, 25)";
                    $variables = [
                        $this->session->userdata("inicio_sesion")['id'],
                        $this->session->userdata("inicio_sesion")['id'],
                        $this->session->userdata("inicio_sesion")['id'],
                        $this->session->userdata("inicio_sesion")['id']
                    ];
                    break;
                } else {
                    //Fecha : 12-Agosto-2025 @author Efrain Martinez programador.analista38@ciudadmaderas.com
                    //Se agrega la etapa 30 (cancelada) al select para que cuando el usuario sea el dueño de la solicitud le aparezca en su panel y despues de ver
                    //la solicitud se y recargue su pagina no le aparezcan estas solicitudes canceladas.
                    $solpagos = "SELECT solpagos.* FROM solpagos WHERE solpagos.idusuario = ? AND solpagos.idetapa IN (1, 3, 4, 6, 8, 21, 25) 
                        UNION
                        SELECT solpagos.* FROM solpagos 
                        JOIN ( SELECT usuarios.idusuario FROM usuarios WHERE FIND_IN_SET( ?, usuarios.sup ) ) u ON solpagos.idetapa IN (1, 3, 4, 6, 8, 21, 25) AND u.idusuario = solpagos.idusuario";
                    $variables = [
                        $this->session->userdata("inicio_sesion")['id'],
                        $this->session->userdata("inicio_sesion")['id'],
                        $this->session->userdata("inicio_sesion")['id']
                    ];
                    break;
                }
        }
            //Fecha : 12-Agosto-2025 @author Efrain Martinez programador.analista38@ciudadmaderas.com
            //Se agrega la validacion para que aparezcan las solicitudes que se encuentran en la etapa 30 y el visto = 0

        return $this->db->query("SELECT 
                solpagos.Api, 
                solpagos.financiamiento,
                solpagos.ref_bancaria, 
                solpagos.folio, 
                solpagos.crecibo,
                solpagos.etapa netapa,
                solpagos.condominio,
                solpagos.requisicion, 
                solpagos.orden_compra, 
                solpagos.homoclave, 
                solpagos.metoPago, 
                solpagos.prioridad, 
                solpagos.nomdepto, 
                solpagos.prioridad, 
                solpagos.idAutoriza, 
                ifnull(solpagos.proyecto, pd.nombre) as proyecto,
                solpagos.idsolicitud, 
                solpagos.justificacion, 
                solpagos.idusuario, 
                solpagos.caja_chica, 
                solpagos.servicio, 
                solpagos.moneda, 
                solpagos.cantidad, 
                solpagos.idetapa, 
                solpagos.idResponsable responsable,
                DATE_FORMAT(solpagos.fecelab, '%d/%m/%Y') AS fecelab, 
                proveedores.nombre,
                proveedores.tipo_prov, 
                etapas.nombre AS etapa, 
                capturista.nombre_completo, 
                IFNULL(notifi.visto, 1) AS visto, 
                IFNULL(facturas.uuid, 'SF') AS uuid, 
                IFNULL(facturas.descripcion, 'SF') AS descripcion,
                empresas.abrev AS nempresa, 
                contrato.nombre_contrato,
                solpagos.programado AS isProgramado,
                os.idOficina,
                os.nombre oficina,
                pd.idProyectos,
                pd.nombre proyectoNuevo,
                CASE 
                    WHEN solpagos.caja_chica = 2 AND spo.idTipoServicioPartida = 7 THEN 
                        tdc.idtitular
                    WHEN solpagos.caja_chica = 2 AND spo.idTipoServicioPartida <> 7 THEN
                        capturista.da
                    ELSE 
                        null
                END as idtitular,
                tarjeta.nombre_tarjeta,
                tsp.nombre servicioPartida, -- FECHA: 06-08-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com>
                responsable_cch.nombre_reembolso_cch -- FECHA: 06-08-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com>
            FROM 
            ( 
                $solpagos
            ) solpagos 
            INNER JOIN ( SELECT usuarios.idusuario, CONCAT( usuarios.nombres,' ', usuarios.apellidos ) AS nombre_completo, usuarios.da FROM usuarios ) AS capturista ON capturista.idusuario = solpagos.idusuario
            INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa 
            INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor 
            INNER JOIN etapas ON etapas.idetapa = solpagos.idetapa 
            LEFT JOIN ( SELECT uuid, idsolicitud, MIN(facturas.feccrea),descripcion FROM facturas WHERE facturas.tipo_factura IN ( 1, 3 ) GROUP BY facturas.idsolicitud ) AS facturas ON facturas.idsolicitud = solpagos.idsolicitud 
            LEFT JOIN ( SELECT * FROM notifi WHERE idusuario = ? ) notifi ON notifi.idsolicitud = solpagos.idsolicitud 
            LEFT JOIN ( SELECT idsolicitud, nombre nombre_contrato FROM contratos INNER JOIN sol_contrato ON sol_contrato.idcontrato = contratos.idcontrato ) contrato ON contrato.idsolicitud = solpagos.idsolicitud 
            LEFT JOIN solicitud_proyecto_oficina spo ON spo.idsolicitud = solpagos.idsolicitud 
            LEFT JOIN oficina_sede os ON spo.idoficina = os.idoficina
            LEFT JOIN proyectos_departamentos pd ON spo.idProyectos = pd.idProyectos
            LEFT JOIN tipo_servicio_partidas tsp ON spo.idTipoServicioPartida = tsp.idTipoServicioPartida
            LEFT JOIN tcredito tdc ON solpagos.idResponsable = tdc.idtcredito
            LEFT JOIN ( SELECT usuarios.idusuario, CONCAT( usuarios.nombres,' ', usuarios.apellidos ) AS nombre_tarjeta FROM usuarios ) AS tarjeta ON tarjeta.idusuario = tdc.idtitular
            LEFT JOIN ( -- INICIO FECHA: 06-08-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com>
                SELECT
                    cajas_ch.idusuario
                    , GROUP_CONCAT(cajas_ch.nombre_reembolso_ch SEPARATOR ', ') nombre_reembolso_cch
                FROM cajas_ch
                GROUP BY cajas_ch.idusuario
            ) AS responsable_cch ON responsable_cch.idusuario = solpagos.idResponsable AND (solpagos.caja_chica = 1 OR solpagos.caja_chica = 4)
            WHERE (solpagos.idetapa IN (1, 3, 4, 6, 8, 21, 25))
            ORDER BY CASE WHEN notifi.visto IS NULL THEN 1 WHEN notifi.visto = 1 THEN 1 ELSE 0 END ASC, FIELD(solpagos.idetapa, 1, 2, 13, 8, 21, 3), solpagos.fechaCreacion ", $variables);
    }

    function otros_gastos()
    {

        switch ($this->session->userdata("inicio_sesion")['rol']) {
            case 'DA':
            case 'CI':
                return $this->db->query("SELECT solpagos.folio, empresas.abrev, solpagos.metoPago, solpagos.nomdepto, solpagos.prioridad, solpagos.idAutoriza, IFNULL(solpagos.proyecto, pd.nombre) proyecto, solpagos.idsolicitud, solpagos.justificacion, proveedores.nombre, solpagos.cantidad, DATE_FORMAT(solpagos.fecelab, '%d/%m/%Y') AS fecelab, solpagos.idetapa, etapas.nombre AS etapa, capturista.nombre_completo, solpagos.idusuario, solpagos.caja_chica, solpagos.servicio, IFNULL(notifi.visto, 1) AS visto, solpagos.moneda, IFNULL(facturas.uuid, 'SF') AS uuid FROM solpagos INNER JOIN ( SELECT usuarios.idusuario, CONCAT( usuarios.nombres,' ', usuarios.apellidos ) AS nombre_completo FROM usuarios ) AS capturista ON capturista.idusuario = solpagos.idusuario INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa LEFT JOIN (SELECT *, MIN(facturas.feccrea) FROM facturas WHERE facturas.tipo_factura IN ( 1, 3 ) GROUP BY facturas.idsolicitud) AS facturas ON facturas.idsolicitud = solpagos.idsolicitud INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor LEFT JOIN etapas ON etapas.idetapa = solpagos.idetapa LEFT JOIN ( SELECT notificaciones.idsolicitud, notificaciones.visto FROM notificaciones WHERE notificaciones.idusuario = '" . $this->session->userdata("inicio_sesion")['id'] . "' ) AS notifi ON notifi.idsolicitud = solpagos.idsolicitud left join solicitud_proyecto_oficina spo  on spo.idsolicitud = solpagos.idsolicitud left join proyectos_departamentos pd  on spo.idProyectos = pd.idProyectos  WHERE solpagos.idusuario IN ( SELECT usuarios.idusuario FROM usuarios WHERE usuarios.depto = '" . $this->session->userdata("inicio_sesion")['depto'] . "' ) AND solpagos.nomdepto != '" . $this->session->userdata("inicio_sesion")['depto'] . "' AND ( solpagos.idetapa IN (1, 4, 6, 8, 21, 25, 52 ) OR ( solpagos.idetapa IN ( 3 ) AND SUBDATE( solpagos.fecelab, 7) <= NOW() ) ) ORDER BY FIELD(solpagos.idetapa, '1, 8, 21, 3, 30'), solpagos.fechaCreacion");
                break;
            default:
                return $this->db->query(
                    "SELECT 
                        solpagos.folio, 
                        empresas.abrev, 
                        solpagos.metoPago, 
                        solpagos.nomdepto, 
                        solpagos.prioridad, 
                        solpagos.idAutoriza, 
                        IFNULL(solpagos.proyecto, pd.nombre) proyecto, 
                        solpagos.idsolicitud, 
                        solpagos.justificacion, 
                        proveedores.nombre, 
                        solpagos.cantidad, 
                        DATE_FORMAT(solpagos.fecelab, '%d/%m/%Y') AS fecelab, 
                        solpagos.idetapa, 
                        etapas.nombre AS etapa, 
                        capturista.nombre_completo, 
                        solpagos.idusuario, 
                        solpagos.caja_chica, 
                        solpagos.servicio, 
                        IFNULL(notifi.visto, 1) AS visto, 
                        solpagos.moneda, 
                        IFNULL(facturas.uuid, 'SF') AS uuid,
                        os.idOficina,
                        os.nombre oficina,
                        tsp.nombre servicioPartida 
                    FROM (
                        SELECT solpagos.*
                        FROM 
                            solpagos
                        JOIN ( /** INICIO FECHA: 31-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                            SELECT 
                                DISTINCT( departamento ) departamento 
                            FROM departament_usuario 
                            WHERE idusuario = ? 
                            AND departamento != ? 
                        ) deptos ON solpagos.idetapa IN ( 1, 3, 4, 6, 8, 21, 25, 52 ) AND solpagos.idusuario = ? /** FIN FECHA: 31-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                        UNION
                        SELECT solpagos.*
                        FROM 
                            solpagos
                        JOIN ( SELECT usuarios.idusuario FROM usuarios WHERE FIND_IN_SET( ?, usuarios.sup ) ) vigilados ON solpagos.idetapa IN ( 1, 3, 4, 6, 8, 21, 25, 52 ) AND vigilados.idusuario = solpagos.idusuario
                        JOIN ( SELECT DISTINCT( departamento ) departamento FROM departament_usuario WHERE idusuario = ? AND departamento != ?  ) deptos ON deptos.departamento = solpagos.nomdepto 
                    ) solpagos 
                    CROSS JOIN listado_usuarios capturista ON capturista.idusuario = solpagos.idusuario 
                    CROSS JOIN empresas ON empresas.idempresa = solpagos.idEmpresa 
                    CROSS JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor 
                    LEFT JOIN factura_registro facturas ON facturas.idsolicitud = solpagos.idsolicitud
                    LEFT JOIN etapas ON etapas.idetapa = solpagos.idetapa 
                    LEFT JOIN notifi ON notifi.idsolicitud = solpagos.idsolicitud AND notifi.idusuario = ?
                    left join solicitud_proyecto_oficina spo  on spo.idsolicitud = solpagos.idsolicitud
                    left join proyectos_departamentos pd  on spo.idProyectos = pd.idProyectos
                    LEFT JOIN oficina_sede os ON spo.idoficina = os.idoficina
                    LEFT JOIN tipo_servicio_partidas tsp ON spo.idTipoServicioPartida = tsp.idTipoServicioPartida
                    ORDER BY FIELD(solpagos.idetapa, '1, 8, 21, 52, 3, 30'), solpagos.fechaCreacion",
                    [
                        $this->session->userdata("inicio_sesion")['id'],
                        $this->session->userdata("inicio_sesion")['depto'],
                        $this->session->userdata("inicio_sesion")['id'],
                        $this->session->userdata("inicio_sesion")['id'],
                        $this->session->userdata("inicio_sesion")['id'],
                        $this->session->userdata("inicio_sesion")['depto'],
                        $this->session->userdata("inicio_sesion")['id']
                    ]
                );
                break;
        }
    }

    /***PARA LA CONSULTA DE MULTI DEPARTAMENTOS MEDIANTE UN SELECT**/
    /***SOLICITUDES POR APORBAR**/
    function otros_gastos_capital_humano($opcion)
    {

        switch ($opcion) {
            case '1':
                $departamento = "'CAPITAL HUMANO'";
                break;
            case '10':
                $departamento = "'COMERCIALIZACION', 'COMERCIALIZACION CDMX', 'COMERCIALIZACION LEON', 'COMERCIALIZACION MERIDA', 'COMERCIALIZACION QRO', 'COMERCIALIZACION SLP'";
                break;
            case '3':
                $departamento = "'FINIQUITO', 'FINIQUITO POR RENUNCIA', 'FINIQUITO ASIMILADO', 'FINIQUITO POR PARCIALIDAD', 'PRESTAMO', 'PRESTAMO POR SUSTITUCION PATRONAL', 'PRESTAMO POR ADEUDO', 'BONO'";
                break;
            case '4':
                $departamento = "'NOMINAS', 'NOMINA POR FUERA'";
                break;
            case '13':
                $departamento = "'BODY PERFECT'";
                break;
            case '14':
                $departamento = "'GESTION'";
                break;
            case '12':
                $departamento = "'BONO'";
                break;
            case '5':
                $departamento = "'CONTABILIDAD'";
                break;
            case '6':
                $departamento = "'IMPUESTOS'";
                break;
            case '7':
                $departamento = "'DIRECCION GENERAL'";
                break;
            case '8':
                $departamento = "'GUARDIANES DE LA TIERRA'";
                break;
            case '2':
                $departamento = "'RECEPCION', 'ADMON OFICINA'";
                break;
            case '11':
                $departamento = "'PRESTAMO POR ADEUDO'";
                break;
            case '15':
                $departamento = "'OOAM ADMINISTRATIVO', 'OOAM TECNICO'";
                break;
            case '16':
                $departamento = "'COMPRAS'";
                break;
            case '17':
                $departamento = "'MERCADOTECNIA'";
                break;
            case '18':
                $departamento = "'CI-COMPRAS'";
                break;
            case '19':
                $departamento = "'CONTROL INTERNO'";
                break;
            case '20':
                $departamento = "'JURIDICO'";
                break;
            case '21':
                $departamento = "'JURIDICO INTERNO'";
                break;
            case '22':
                $departamento = "'CONTRATACIÓN Y TITULACIÓN'";
                break;
            case '49':
                $departamento = "'NYSSA'";
                break;
            case '72':
                $departamento = "'ALEBRIJE'";
                break;
            case '73':
                $departamento = "'FUNDACION CIUDAD MADERAS'";
                break;
            case '75':
                $departamento = "'COMUNICACION'";
                break;
            case '9':/** FECHA: 06-JUNIO-2025 | NUEVO CONCEPTO SOLO DEPTO CH | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                $departamento = "'PAGO FUERA DE NOMINA'";
                break;/** FECHA: 06-JUNIO-2025 | NUEVO CONCEPTO SOLO DEPTO CH | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
            default:
                if( $this->session->userdata("inicio_sesion")['depto'] == 'OOAM' )
                    $departamento = "'OOAM ADMINISTRATIVO', 'OOAM TECNICO'";
                elseif ($this->session->userdata("inicio_sesion")['depto'] == 'ADMON OFICINA') {
                    $departamento = "'RECEPCION', 'ADMON OFICINA'";
                } else {
                    $departamento = "'" . $this->session->userdata("inicio_sesion")['depto'] . "'";
                }
                break;
        }

        $validacionUsuario = $this->session->userdata("inicio_sesion")['id'] == 2711 ? "WHERE solpagos.idusuario = 2710" : "";

        return $this->db->query("SELECT 
                solpagos.folio, 
                solpagos.homoclave, 
                solpagos.metoPago, 
                solpagos.prioridad, 
                solpagos.nomdepto, 
                solpagos.prioridad, 
                solpagos.idAutoriza, 
                solpagos.proyecto, 
                solpagos.idsolicitud, 
                solpagos.justificacion, 
                proveedores.nombre, 
                solpagos.cantidad, 
                DATE_FORMAT(solpagos.fecelab, '%d/%m/%Y') AS fecelab, 
                solpagos.idetapa, 
                IF( solpagos.idetapa = 3, solpagos.nomdepto , etapas.nombre ) AS etapa, 
                capturista.nombre_completo, 
                solpagos.idusuario, 
                solpagos.caja_chica, 
                solpagos.servicio, 
                1 visto, solpagos.moneda, 
                IFNULL(facturas.uuid, 'SF') AS uuid, 
                empresas.abrev AS nempresa,
                os.idOficina,
                os.nombre oficina,
                pd.idProyectos,
                pd.nombre proyectoNuevo,
                tsp.idTipoServicioPartida,
                tsp.nombre tServicioPartida,
                CASE 
                    WHEN solpagos.caja_chica = 2 AND tsp.idTipoServicioPartida = 7 THEN 
                        tdc.idtitular
                    WHEN solpagos.caja_chica = 2 AND tsp.idTipoServicioPartida <> 7 THEN
                        us.da
                    ELSE 
                        null
                END as idtitular,
                tarjeta.nombre_tarjeta
            FROM ( 
                SELECT * FROM solpagos WHERE solpagos.nomdepto IN ( $departamento ) AND solpagos.idetapa IN ( 12, 3, 25 )
                UNION
                SELECT * FROM solpagos WHERE solpagos.nomdepto IN ( $departamento ) AND solpagos.idusuario = ? AND solpagos.idetapa IN ( 1, 4, 6, 8, 21 )
            ) solpagos
            INNER JOIN listado_usuarios capturista ON capturista.idusuario = solpagos.idusuario 
            INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa 
            INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor 
            LEFT JOIN ( SELECT *, MIN(facturas.feccrea) FROM facturas WHERE facturas.tipo_factura IN ( 1, 3 ) GROUP BY facturas.idsolicitud) AS facturas ON facturas.idsolicitud = solpagos.idsolicitud 
            LEFT JOIN etapas ON etapas.idetapa = solpagos.idetapa
            LEFT JOIN solicitud_proyecto_oficina spo ON spo.idsolicitud = solpagos.idsolicitud 
            LEFT JOIN oficina_sede os ON spo.idoficina = os.idoficina
            LEFT JOIN proyectos_departamentos pd ON spo.idProyectos = pd.idProyectos
            LEFT JOIN tipo_servicio_partidas tsp ON spo.idTipoServicioPartida = tsp.idTipoServicioPartida
            LEFT JOIN tcredito tdc ON solpagos.idResponsable = tdc.idtcredito
            LEFT JOIN ( SELECT usuarios.idusuario, CONCAT( usuarios.nombres,' ', usuarios.apellidos ) AS nombre_tarjeta FROM usuarios ) AS tarjeta ON tarjeta.idusuario = tdc.idtitular
            INNER JOIN usuarios us ON solpagos.idusuario = us.idusuario
            $validacionUsuario
            ORDER BY FIELD (solpagos.idetapa, 3, 1, 12, 8, 21, 30), solpagos.fechaCreacion", [
            $this->session->userdata("inicio_sesion")['id']
        ]);
    }

    /***SOLICITUDES POR EN CURSO**/
    function otros_gastos_capital_humano_encurso($opcion)
    {

        switch ($opcion) {
            case '1':
                $departamento = "'CAPITAL HUMANO'";
                break;
            case '2':
                $departamento = "'RECEPCION', 'ADMON OFICINA'";
                break;
            case '3':
                $departamento = "'FINIQUITO', 'FINIQUITO POR RENUNCIA', 'FINIQUITO POR PARCIALIDAD', 'FINIQUITO ASIMILADO', 'PRESTAMO', 'PRESTAMO POR ADEUDO', 'BONO'";
                break;
            case '4':
                $departamento = "'NOMINAS'";
                break;
            case '5':
                $departamento = "'CONTABILIDAD'";
                break;
            default:
                if ($this->session->userdata("inicio_sesion")['depto'] == 'ADMON OFICINA') {
                    $departamento = "'RECEPCION', 'ADMON OFICINA'";
                } else {
                    $departamento = "'" . $this->session->userdata("inicio_sesion")['depto'] . "'";
                }
                break;
        }

        return $this->db->query("SELECT solpagos.metoPago, solpagos.nomdepto, solpagos.descuento,
        IF( solpagos.programado IS NOT NULL, CASE WHEN solpagos.programado < 7 THEN TIMESTAMPDIFF(MONTH, CONCAT( YEAR(solpagos.fecelab),'-',MONTH(solpagos.fecelab),'-', DAY(solpagos.fecha_fin) ), IFNULL( solpagos.fecha_fin, DATE_ADD( solpagos.fecelab, INTERVAL IF( solpagos.idetapa NOT IN ( 11, 30 ), tpagos + 1, IFNULL(tpagos, 1) )  MONTH) ) ) ELSE TIMESTAMPDIFF(WEEK, solpagos.fecelab, IFNULL(solpagos.fecha_fin, DATE_ADD( solpagos.fecelab, INTERVAL IFNULL( tpagos, 1 )  WEEK) ) ) END, NULL ) mpagar,
        solpagos.fechaCreacion, solpagos.programado, solpagos.idsolicitud, solpagos.folio, IFNULL(solpagos.proyecto, pd.nombre) proyecto, solpagos.homoclave, solpagos.etapa AS soletapa, solpagos.condominio, solpagos.idsolicitud, solpagos.justificacion, empresas.abrev, proveedores.nombre, solpagos.cantidad, IFNULL(facturas.descripcion, 'SF') AS descripcion, IFNULL(facturas.uuid, 'SF') AS uuid, capturista.nombre_completo, DATE_FORMAT(solpagos.fecelab, '%d/%m/%Y') AS fecelab, etapas.nombre AS etapa, cant_pag.pagado, IFNULL(notifi.visto, 1) AS visto, solpagos.moneda, IFNULL(DATE_FORMAT(solpagos.fecha_autorizacion, '%d/%m/%Y'), '--') AS fecha_autorizacion FROM solpagos LEFT JOIN (SELECT *, MIN(facturas.feccrea) FROM facturas WHERE facturas.tipo_factura IN ( 1, 3 ) GROUP BY facturas.idsolicitud) AS facturas ON facturas.idsolicitud = solpagos.idsolicitud INNER JOIN ( SELECT usuarios.idusuario, CONCAT( usuarios.nombres,' ', usuarios.apellidos ) AS nombre_completo FROM usuarios ) AS capturista ON capturista.idusuario = solpagos.idusuario INNER JOIN empresas ON solpagos.idEmpresa = empresas.idempresa INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor INNER JOIN etapas ON etapas.idetapa = solpagos.idetapa LEFT JOIN ( SELECT autpagos.idsolicitud, COUNT(autpagos.idsolicitud) tpagos, SUM(autpagos.cantidad) AS pagado FROM autpagos GROUP BY autpagos.idsolicitud ) AS cant_pag ON cant_pag.idsolicitud = solpagos.idsolicitud LEFT JOIN ( SELECT notificaciones.idsolicitud, notificaciones.visto FROM notificaciones WHERE notificaciones.idusuario = '" . $this->session->userdata("inicio_sesion")['id'] . "' GROUP BY notificaciones.idsolicitud ) AS notifi ON notifi.idsolicitud = solpagos.idsolicitud left join solicitud_proyecto_oficina spo  on spo.idsolicitud = solpagos.idsolicitud left join proyectos_departamentos pd  on spo.idProyectos = pd.idProyectos WHERE solpagos.nomdepto IN ($departamento) AND solpagos.idetapa NOT IN ( 3, 1, 12, 4, 6, 8, 10, 11, 21, 25, 30, 31) ORDER BY IFNULL(visto, 1) ASC, fechaCreacion DESC");
    }
    /*************************************/


    //LLAMA TODAS LAS FACTURAS QUE ESTEN EN LAS DEMAS AREAS FUERA DEL AREA CORERSPONDIENTE O LISTAS PARA SER AUTORIZADAS POR DG
    function get_solicitudes_en_curso($tipo_reporte, $fechaInicial = '', $fechaFinal = '')
    {
        $filtro = "";
        /** FECHA: 25-SEPTIEMBRE-2024 @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
        if ($tipo_reporte) {
            if ($tipo_reporte == "#historial_activas_prov") {
                $tipo_reporte = '0';
                $filtro .= "AND solpagos.idResponsable = '" . $this->session->userdata("inicio_sesion")['da'] . "'";
                /** FECHA: 25-SEPTIEMBRE-2024 @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
            }

            if ($tipo_reporte == "#historial_activas_cch") {
                $tipo_reporte = '1,3';
                /** FECHA: 02-ENERO-2025 @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
            }

            if ($tipo_reporte == "#historial_activas_tdc") {
                $tipo_reporte = '2';
            }

            if ($tipo_reporte == "#historial_activas_viaticos") {
                $tipo_reporte = '4';
            }
        } else {
            $tipo_reporte = '0,1,2';
        }
        if (($fechaInicial || $fechaInicial !== '') || ($fechaFinal || $fechaFinal !== '')) {
            $filtro_fecha = "WHERE solpagos.fechaCreacion BETWEEN '$fechaInicial 00:00:00' AND '$fechaFinal 23:59:59'";
        } else {
            $filtro_fecha = "";
        }

        switch ($this->session->userdata("inicio_sesion")['rol']) {
            case 'CP':
                $joins = "";
                /** INICIO  FECHA: 25-SEPTIEMBRE-2024 @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                if ($this->session->userdata("inicio_sesion")['depto'] == 'CI-COMPRAS') {
                    $joins = "LEFT JOIN ( 
                                SELECT 
                                    ud.idusuario, d.departamento
                                FROM usuario_depto ud
                                JOIN departamentos d ON d.iddepartamentos = ud.iddepartamento 
                                WHERE ud.idusuario = " . $this->session->userdata("inicio_sesion")['id'] . "
                            ) d ON d.departamento = solpagos.nomdepto
                    ";
                }
                /** FIN  FECHA: 25-SEPTIEMBRE-2024 @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */

                return  $this->db->query(
                    "SELECT
                        solpagos.idproceso,
                        solpagos.metoPago, 
                        solpagos.nomdepto,
                        IF(solpagos.programado IS NOT NULL, 
                        CASE 
                            WHEN solpagos.programado < 7 
                            THEN 
                                TIMESTAMPDIFF(
                                    MONTH, 
                                    CONCAT(YEAR(solpagos.fecelab), '-', MONTH(solpagos.fecelab), '-', DAY(solpagos.fecha_fin)),
                                    IFNULL(solpagos.fecha_fin, DATE_ADD( solpagos.fecelab, INTERVAL IF(solpagos.idetapa NOT IN (11, 30), tpagos + 1, IFNULL(tpagos, 1)) MONTH ))
                                )
                            ELSE 
                                TIMESTAMPDIFF(
                                    WEEK, 
                                    solpagos.fecelab,
                                    IFNULL(solpagos.fecha_fin, DATE_ADD(solpagos.fecelab, INTERVAL IFNULL(tpagos, 1) WEEK))
                                ) 
                            END, 
                            NULL
                        ) AS mpagar,
                        solpagos.fechaCreacion, 
                        solpagos.programado, 
                        solpagos.idsolicitud, 
                        solpagos.folio, 
                        IFNULL(solpagos.proyecto, pd.nombre) proyecto, 
                        solpagos.homoclave, 
                        solpagos.etapa AS soletapa, 
                        solpagos.condominio,
                        solpagos.justificacion, 
                        empresas.abrev, 
                        proveedores.nombre, 
                        solpagos.orden_compra AS orden_compra,
                        especial.nombre_contrato AS contrato,
                        IFNULL(facturas.descripcion, 'SF') AS descripcion,
                        IF(solpagos.programado IS NOT NULL, solpagos.cantidad + IFNULL(cant_pag.pagado, 0),  solpagos.cantidad) AS cantidad, 
                        IFNULL(facturas.uuid, 'SF') AS uuid, 
                        capturista.nombre_completo, 
                        DATE(solpagos.fecelab) AS fecelab, 
                        etapas.nombre AS etapa, 
                        IFNULL(cant_pag.autorizado, 0) AS autorizado, 
                        IFNULL(cant_pag.pagado, 0) AS pagado, 
                        IFNULL(notifi.visto, 1) AS visto, 
                        solpagos.moneda, 
                        IFNULL(DATE(solpagos.fecha_autorizacion), '--') AS fecha_autorizacion,
                        IFNULL(os.nombre, 'NA') AS oficina,
                        IFNULL(tsp.nombre, 'NA') tServicioPartida, /** FECHA: 02-ENERO-2025 @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                        responsable_cch.nombre_reembolso_cch,
                        CASE  /** INICIO FECHA: 02-ENERO-2025 @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                            WHEN solpagos.caja_chica = 1 THEN 'CAJA CHICA'
                            WHEN solpagos.caja_chica = 3 THEN 'REEMBOLSO'
                            ELSE 'NA'
                        END AS tipo_solicitud /** FIN FECHA: 02-ENERO-2025 @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                    FROM ( 
                        SELECT 
                            * 
                        FROM solpagos
                        WHERE ( solpagos.idetapa NOT IN ( 2 ) OR ( solpagos.idetapa IN (2) AND solpagos.rcompra = 0 OR solpagos.rcompra IS NULL ) )
                        AND solpagos.idetapa NOT IN (1, 3, 4, 6, 8, 10, 11, 21, 25, 30, 31)
                    ) solpagos  
                    $joins 
                    LEFT JOIN ( 
                        SELECT 
                            idsolicitud
                            , nombre_contrato
                            , NULL insumo 
                        FROM lcontratos
                    ) especial ON especial.idsolicitud = solpagos.idsolicitud
                    LEFT JOIN (
                        SELECT 
                            *
                            , MIN(facturas.feccrea) 
                        FROM facturas 
                        WHERE facturas.tipo_factura IN ( 1, 3 ) 
                        GROUP BY facturas.idsolicitud
                    ) AS facturas ON facturas.idsolicitud = solpagos.idsolicitud 
                    INNER JOIN ( 
                        SELECT 
                            usuarios.idusuario
                            , CONCAT( usuarios.nombres,' ', usuarios.apellidos ) AS nombre_completo 
                        FROM usuarios 
                    ) AS capturista ON capturista.idusuario = solpagos.idusuario
                    INNER JOIN empresas ON solpagos.idEmpresa = empresas.idempresa 
                    INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor
                    INNER JOIN etapas ON etapas.idetapa = solpagos.idetapa
                    LEFT JOIN ( 
                        SELECT 
                            autpagos.idsolicitud
                            , COUNT(autpagos.idsolicitud) tpagos
                            , SUM(IF( autpagos.fecha_pago IS NOT NULL OR autpagos.referencia = 'ABONO', autpagos.cantidad, 0 )) AS pagado
                            , SUM( IF( autpagos.estatus NOT IN (2), autpagos.cantidad, 0 ) ) AS autorizado 
                        FROM autpagos 
                        GROUP BY autpagos.idsolicitud 
                    ) AS cant_pag ON cant_pag.idsolicitud = solpagos.idsolicitud
                    LEFT JOIN ( 
                        SELECT 
                            notificaciones.idsolicitud
                            , notificaciones.visto 
                        FROM notificaciones 
                        WHERE notificaciones.idusuario = ? /*inicio_sesion[idusuario]*/
                        GROUP BY notificaciones.idsolicitud 
                    ) AS notifi ON notifi.idsolicitud = solpagos.idsolicitud 
                    LEFT JOIN solicitud_proyecto_oficina spo ON spo.idsolicitud = solpagos.idsolicitud 
                    LEFT JOIN proyectos_departamentos pd ON spo.idProyectos = pd.idProyectos
                    LEFT JOIN oficina_sede os ON spo.idoficina = os.idoficina
                    LEFT JOIN tipo_servicio_partidas tsp ON spo.idTipoServicioPartida = tsp.idTipoServicioPartida
                    LEFT JOIN (
                        SELECT
                            cajas_ch.idusuario
                            , GROUP_CONCAT(cajas_ch.nombre_reembolso_ch SEPARATOR ', ') nombre_reembolso_cch
                        FROM cajas_ch
                        GROUP BY cajas_ch.idusuario
                    ) AS responsable_cch ON responsable_cch.idusuario = solpagos.idResponsable AND (solpagos.caja_chica = 1 OR solpagos.caja_chica = 4)
                    WHERE solpagos.caja_chica IN ($tipo_reporte) /** FECHA: 02-ENERO-2025 @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                    AND solpagos.fechaCreacion BETWEEN '$fechaInicial 00:00:00' AND '$fechaFinal 23:59:59'
                    $filtro
                    ORDER BY IFNULL(notifi.visto, 1) ASC, solpagos.fechaCreacion",
                    array($this->session->userdata("inicio_sesion")['id'])
                    /** FECHA: 02-ENERO-2025 @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                );

                break;
            case 'SU':
            case 'DA':
            case 'CI':
            case 'AS':
                // VALIDACIÓN PARA QUE 2711	MAYRA RODRIGUEZ ORTEGA VEA LAS CAPTURAS DE 2710	DIANA ROSARIO SALAZAR RESENDIZ
                if ($this->session->userdata("inicio_sesion")['id'] == 2711 && $filtro_fecha == "")
                    $validacionUsuario = "WHERE solpagos.idusuario = 2710";
                else if ($this->session->userdata("inicio_sesion")['id'] == 2711 && $filtro_fecha != "")
                    $validacionUsuario = "AND solpagos.idusuario = 2710";
                else
                    $validacionUsuario = "";
                return $this->db->query("SELECT 
                    solpagos.idsolicitud,
                    solpagos.idproceso,
                    proveedores.idProveedor,
                    proveedores.rfc,
                    capturista.idusuario,
                    empresas.abrev,
                    IFNULL(solpagos.proyecto, pd.nombre) proyecto,
                    solpagos.folio,
                    proveedores.nombre,
                    solpagos.nomdepto,
                    DATE(solpagos.fecelab) AS fecelab,
                    solpagos.fechaCreacion,
                    capturista.nombre_completo,
                    IF(solpagos.programado IS NOT NULL,
                        solpagos.cantidad + IFNULL(cant_pag.pagado, 0),
                        solpagos.cantidad) AS cantidad,
                    solpagos.moneda,
                    solpagos.orden_compra AS orden_compra,
                    especial.nombre_contrato AS contrato,
                    solpagos.programado,
                    etapas.nombre AS etapa,
                    cant_pag.pagado,
                    solpagos.metoPago,
                    1 visto,
                    solpagos.financiamiento,
                    IFNULL(facturas.descripcion, 'SF') AS descripcion,
                    IFNULL(os.nombre, 'NA') AS oficina,
                    IFNULL(tsp.nombre, 'NA') tServicioPartida,
                    solpagos.homoclave, /** INICIO FECHA: 02-ENERO-2025 @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                    CASE 
                        WHEN solpagos.caja_chica = 1 THEN 'CAJA CHICA'
                        WHEN solpagos.caja_chica = 3 THEN 'REEMBOLSO'
                        ELSE 'NA'
                    END AS tipo_solicitud /** FIN FECHA: 02-ENERO-2025 @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                FROM
                    ( 
                        SELECT 
                            solpagos.*
                        FROM
                            solpagos
                        JOIN ( SELECT departamento depto FROM departament_usuario WHERE idusuario = ? ) depto ON solpagos.nomdepto = depto.depto
                        WHERE
                            solpagos.idetapa NOT IN (1 , 3, 4, 6, 8, 10, 11, 21, 25, 30, 31) AND solpagos.caja_chica IN ( $tipo_reporte )
                             -- (1 , 12, 3, 4, 6, 8, 10, 11, 21, 25, 30, 31) FILTRO ORIGINAL
                        UNION  
                        SELECT 
                            solpagos.*
                        FROM
                            solpagos
                        JOIN ( SELECT usuarios.idusuario FROM usuarios WHERE FIND_IN_SET( ?, usuarios.sup ) ) usuario ON usuario.idusuario = solpagos.idusuario
                        WHERE
                            solpagos.idetapa NOT IN (1 , 3, 4, 6, 8, 10, 11, 21, 25, 30, 31) AND solpagos.caja_chica IN ( $tipo_reporte )
                        UNION
                        SELECT 
                            s.* 
                        FROM 
                            solpagos s 
                        JOIN proveedores_usuario p ON p.idproveedor = s.idproveedor AND s.nomdepto = p.nomdepto AND p.idusuario = ?
                        WHERE
                            s.idetapa NOT IN (1 , 3, 4, 6, 8, 10, 11, 21, 25, 30, 31) AND s.caja_chica IN ( $tipo_reporte )
                    ) solpagos
                    LEFT JOIN ( SELECT idsolicitud, nombre_contrato, NULL insumo FROM lcontratos ) especial ON especial.idsolicitud = solpagos.idsolicitud
                    LEFT JOIN (SELECT *, MIN(facturas.feccrea) FROM facturas WHERE facturas.tipo_factura IN ( 1, 3 ) GROUP BY facturas.idsolicitud) AS facturas ON facturas.idsolicitud = solpagos.idsolicitud 
                    CROSS JOIN  listado_usuarios capturista ON capturista.idusuario = solpagos.idusuario
                    CROSS JOIN ( SELECT idEmpresa, abrev FROM empresas ) empresas ON solpagos.idEmpresa = empresas.idempresa
                    CROSS JOIN ( SELECT idproveedor, nombre, rfc FROM proveedores ) proveedores ON proveedores.idproveedor = solpagos.idProveedor
                    CROSS JOIN etapas ON etapas.idetapa = solpagos.idetapa
                    LEFT JOIN vw_autpagos cant_pag ON cant_pag.idsolicitud = solpagos.idsolicitud
                    left join solicitud_proyecto_oficina spo  on spo.idsolicitud = solpagos.idsolicitud 
                    left join proyectos_departamentos pd  on spo.idProyectos = pd.idProyectos
                    LEFT JOIN oficina_sede os ON spo.idoficina = os.idoficina
                    LEFT JOIN tipo_servicio_partidas tsp ON spo.idTipoServicioPartida = tsp.idTipoServicioPartida
                    $filtro_fecha
                    $validacionUsuario
                ORDER BY fechaCreacion", array($this->session->userdata("inicio_sesion")['id'], $this->session->userdata("inicio_sesion")['id'], $this->session->userdata("inicio_sesion")['id']));
                break;
            case 'CE':
            case 'CX':
            case 'CA':
                return $this->db->query("SELECT 
                    solpagos.idproceso,
                    solpagos.metoPago, 
                    solpagos.nomdepto, 
                    NULL tipo_comentario, 
                    solpagos.descuento,
                    IF( solpagos.programado IS NOT NULL, CASE WHEN solpagos.programado < 7 THEN TIMESTAMPDIFF(MONTH, CONCAT( YEAR(solpagos.fecelab),'-',MONTH(solpagos.fecelab),'-', DAY(solpagos.fecha_fin) ), IFNULL( solpagos.fecha_fin, DATE_ADD( solpagos.fecelab, INTERVAL IF( solpagos.idetapa NOT IN ( 11, 30 ), tpagos + 1, IFNULL(tpagos, 1) )  MONTH) ) ) ELSE TIMESTAMPDIFF(WEEK, solpagos.fecelab, IFNULL(solpagos.fecha_fin, DATE_ADD( solpagos.fecelab, INTERVAL IFNULL( tpagos, 1 )  WEEK) ) ) END, NULL ) mpagar,
                    solpagos.fechaCreacion, 
                    solpagos.programado, 
                    solpagos.idsolicitud, 
                    solpagos.folio, 
                    IFNULL(solpagos.proyecto, pd.nombre) proyecto, 
                    solpagos.homoclave, 
                    solpagos.etapa AS soletapa, 
                    solpagos.condominio, 
                    solpagos.idsolicitud, 
                    solpagos.justificacion, 
                    empresas.abrev, 
                    proveedores.nombre, 
                    IF(solpagos.programado IS NOT NULL, solpagos.cantidad + IFNULL(cant_pag.pagado, 0),  solpagos.cantidad) AS cantidad,  
                    IFNULL(facturas.uuid, 'SF') AS uuid, 
                    capturista.nombre_completo, 
                    DATE(solpagos.fecelab) AS fecelab, 
                    etapas.nombre AS etapa, 
                    IFNULL(cant_pag.autorizado, 0) AS autorizado, 
                    IFNULL(cant_pag.pagado, 0) AS pagado, 
                    IFNULL(notifi.visto, 1) AS visto, 
                    solpagos.moneda, 
                    IFNULL(DATE(solpagos.fecha_autorizacion), '--') AS fecha_autorizacion, 
                    solpagos.orden_compra AS orden_compra,
                    especial.nombre_contrato AS contrato,
                    IFNULL(facturas.descripcion, 'SF') AS descripcion,
                    IFNULL(os.nombre, 'NA') AS oficina,
                    IFNULL(tsp.nombre, 'NA') tServicioPartida, /** INICIO FECHA: 02-ENERO-2025 @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                    CASE 
                        WHEN solpagos.caja_chica = 1 THEN 'CAJA CHICA'
                        WHEN solpagos.caja_chica = 3 THEN 'REEMBOLSO'
                        ELSE 'NA'
                    END AS tipo_solicitud /** FIN FECHA: 02-ENERO-2025 @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                    FROM ( 
                        SELECT 
                            solpagos.*
                        FROM
                            solpagos
                        WHERE solpagos.idusuario = ? AND solpagos.idetapa NOT IN (1 , 12, 3, 4, 6, 8, 10, 11, 21, 25, 30, 31) AND solpagos.caja_chica IN ( $tipo_reporte )
                        UNION  
                        SELECT 
                            solpagos.*
                        FROM
                            solpagos
                        JOIN ( SELECT usuarios.idusuario FROM usuarios WHERE FIND_IN_SET( ?, usuarios.sup ) ) usuario ON usuario.idusuario = solpagos.idusuario
                        WHERE
                            solpagos.idetapa NOT IN (1 , 12, 3, 4, 6, 8, 10, 11, 21, 25, 30, 31) AND solpagos.caja_chica IN ( $tipo_reporte )
                        UNION
                        SELECT 
                            s.* 
                        FROM 
                            solpagos s 
                        JOIN proveedores_usuario p ON p.idproveedor = s.idproveedor AND s.nomdepto = p.nomdepto AND p.idusuario = ?
                        WHERE
                            s.idetapa NOT IN (1 , 12, 3, 4, 6, 8, 10, 11, 21, 25, 30, 31) AND s.caja_chica IN ( $tipo_reporte )
                    ) solpagos 
                    LEFT JOIN ( SELECT idsolicitud, nombre_contrato, NULL insumo FROM lcontratos ) especial ON especial.idsolicitud = solpagos.idsolicitud
                    LEFT JOIN (SELECT *, MIN(facturas.feccrea) FROM facturas WHERE facturas.tipo_factura IN ( 1, 3 ) GROUP BY facturas.idsolicitud) AS facturas ON facturas.idsolicitud = solpagos.idsolicitud 
                    INNER JOIN ( SELECT usuarios.idusuario, CONCAT( usuarios.nombres,' ', usuarios.apellidos ) AS nombre_completo FROM usuarios ) AS capturista ON capturista.idusuario = solpagos.idusuario 
                    INNER JOIN empresas ON solpagos.idEmpresa = empresas.idempresa 
                    INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor 
                    INNER JOIN etapas ON etapas.idetapa = solpagos.idetapa 
                    LEFT JOIN ( SELECT autpagos.idsolicitud, COUNT(autpagos.idsolicitud) tpagos, SUM(IF( autpagos.fecha_pago IS NOT NULL OR autpagos.referencia = 'ABONO', autpagos.cantidad, 0 )) AS pagado, SUM( IF( autpagos.estatus NOT IN (2), autpagos.cantidad, 0 ) ) AS autorizado FROM autpagos GROUP BY autpagos.idsolicitud ) AS cant_pag ON cant_pag.idsolicitud = solpagos.idsolicitud 
                    LEFT JOIN ( SELECT notificaciones.idsolicitud, notificaciones.visto FROM notificaciones WHERE notificaciones.idusuario =  ? ) AS notifi ON notifi.idsolicitud = solpagos.idsolicitud 
                    LEFT JOIN solicitud_proyecto_oficina spo  on spo.idsolicitud = solpagos.idsolicitud 
                    LEFT JOIN proyectos_departamentos pd  on spo.idProyectos = pd.idProyectos
                    LEFT JOIN oficina_sede os ON spo.idoficina = os.idoficina
                    LEFT JOIN tipo_servicio_partidas tsp ON spo.idTipoServicioPartida = tsp.idTipoServicioPartida
                    $filtro_fecha
                    ORDER BY IFNULL(visto, 1) ASC, solpagos.fechaCreacion", [$this->session->userdata("inicio_sesion")['id'], $this->session->userdata("inicio_sesion")['id'], $this->session->userdata("inicio_sesion")['id'], $this->session->userdata("inicio_sesion")['id']]);
                break;
            case 'CJ':
                return $this->db->query("SELECT 
                        solpagos.idproceso,
                        solpagos.metoPago, 
                        solpagos.nomdepto, 
                        NULL tipo_comentario, 
                        solpagos.descuento,
                        IF( solpagos.programado IS NOT NULL, CASE WHEN solpagos.programado < 7 THEN TIMESTAMPDIFF(MONTH, CONCAT( YEAR(solpagos.fecelab),'-',MONTH(solpagos.fecelab),'-', DAY(solpagos.fecha_fin) ), IFNULL( solpagos.fecha_fin, DATE_ADD( solpagos.fecelab, INTERVAL IF( solpagos.idetapa NOT IN ( 11, 30 ), tpagos + 1, IFNULL(tpagos, 1) )  MONTH) ) ) ELSE TIMESTAMPDIFF(WEEK, solpagos.fecelab, IFNULL(solpagos.fecha_fin, DATE_ADD( solpagos.fecelab, INTERVAL IFNULL( tpagos, 1 )  WEEK) ) ) END, NULL ) mpagar,
                        solpagos.fechaCreacion, 
                        solpagos.programado, 
                        solpagos.idsolicitud, 
                        solpagos.folio, 
                        IFNULL(solpagos.proyecto, pd.nombre) proyecto, 
                        solpagos.homoclave, 
                        solpagos.etapa AS soletapa, 
                        solpagos.condominio, 
                        solpagos.idsolicitud, 
                        solpagos.justificacion, 
                        empresas.abrev, 
                        proveedores.nombre, 
                        IF(solpagos.programado IS NOT NULL, solpagos.cantidad + IFNULL(cant_pag.pagado, 0),  solpagos.cantidad) AS cantidad, 
                        IFNULL(facturas.uuid, 'SF') AS uuid, 
                        capturista.nombre_completo, 
                        DATE(solpagos.fecelab) AS fecelab, 
                        etapas.nombre AS etapa, 
                        IFNULL(cant_pag.autorizado, 0) AS autorizado, 
                        IFNULL(cant_pag.pagado, 0) AS pagado, 
                        IFNULL(notifi.visto, 1) AS visto, 
                        solpagos.moneda, 
                        IFNULL(DATE(solpagos.fecha_autorizacion), '--') AS fecha_autorizacion, 
                        solpagos.orden_compra AS orden_compra,
                        especial.nombre_contrato AS contrato,
                        IFNULL(facturas.descripcion, 'SF') AS descripcion,
                        IFNULL(os.nombre, 'NA') AS oficina,
                        IFNULL(tsp.nombre, 'NA') tServicioPartida
                        FROM ( SELECT * FROM solpagos WHERE solpagos.idetapa NOT IN ( 2 ) OR ( solpagos.idetapa IN ( 2 ) AND ( solpagos.rcompra = 0 OR solpagos.rcompra IS NULL) ) ) solpagos 
                        LEFT JOIN ( SELECT idsolicitud, nombre_contrato, NULL insumo FROM lcontratos ) especial ON especial.idsolicitud = solpagos.idsolicitud
                        LEFT JOIN (SELECT *, MIN(facturas.feccrea) FROM facturas WHERE facturas.tipo_factura IN ( 1, 3 ) GROUP BY facturas.idsolicitud) AS facturas ON facturas.idsolicitud = solpagos.idsolicitud 
                        INNER JOIN ( SELECT usuarios.idusuario, CONCAT( usuarios.nombres,' ', usuarios.apellidos ) AS nombre_completo FROM usuarios ) AS capturista ON capturista.idusuario = solpagos.idusuario 
                        INNER JOIN empresas ON solpagos.idEmpresa = empresas.idempresa 
                        INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor 
                        INNER JOIN etapas ON etapas.idetapa = solpagos.idetapa 
                        LEFT JOIN ( SELECT autpagos.idsolicitud, COUNT(autpagos.idsolicitud) tpagos, SUM(IF( autpagos.fecha_pago IS NOT NULL OR autpagos.referencia = 'ABONO', autpagos.cantidad, 0 )) AS pagado, SUM( IF( autpagos.estatus NOT IN (2), autpagos.cantidad, 0 ) ) AS autorizado FROM autpagos GROUP BY autpagos.idsolicitud ) AS cant_pag ON cant_pag.idsolicitud = solpagos.idsolicitud 
                        LEFT JOIN ( SELECT notificaciones.idsolicitud, notificaciones.visto FROM notificaciones WHERE notificaciones.idusuario = '" . $this->session->userdata("inicio_sesion")['id'] . "' GROUP BY notificaciones.idsolicitud ) AS notifi ON notifi.idsolicitud = solpagos.idsolicitud 
                        LEFT JOIN solicitud_proyecto_oficina spo  on spo.idsolicitud = solpagos.idsolicitud 
                        LEFT JOIN proyectos_departamentos pd  on spo.idProyectos = pd.idProyectos
                        LEFT JOIN oficina_sede os ON spo.idoficina = os.idoficina
                        LEFT JOIN tipo_servicio_partidas tsp ON spo.idTipoServicioPartida = tsp.idTipoServicioPartida
                        WHERE ( ( solpagos.idResponsable = '" . $this->session->userdata("inicio_sesion")['id'] . "' AND solpagos.caja_chica = 1 ) OR solpagos.idusuario = '" . $this->session->userdata("inicio_sesion")['id'] . "' OR solpagos.idusuario IN ( SELECT usuarios.idusuario FROM usuarios WHERE FIND_IN_SET('" . $this->session->userdata("inicio_sesion")['id'] . "', usuarios.sup ) ) ) 
                        AND solpagos.idetapa NOT IN (1, 3, 4, 6, 8, 10, 11, 21, 25, 30, 31)
                        ORDER BY IFNULL(visto, 1) ASC, solpagos.fechaCreacion");
                break;
            case 'FP':
                return $this->db->query("SELECT solpagos.metoPago, 
                        solpagos.nomdepto, NULL tipo_comentario, solpagos.descuento,
                        IF( solpagos.programado IS NOT NULL,
                            CASE 
                                WHEN solpagos.programado < 7 THEN 
                                    TIMESTAMPDIFF(MONTH, CONCAT( YEAR(solpagos.fecelab),'-',MONTH(solpagos.fecelab),'-', DAY(solpagos.fecha_fin) ), IFNULL( solpagos.fecha_fin, DATE_ADD( solpagos.fecelab, INTERVAL IF( solpagos.idetapa NOT IN ( 11, 30 ), tpagos + 1, IFNULL(tpagos, 1) )  MONTH) ) ) 
                            ELSE TIMESTAMPDIFF(WEEK, solpagos.fecelab, IFNULL(solpagos.fecha_fin, DATE_ADD( solpagos.fecelab, INTERVAL IFNULL( tpagos, 1 )  WEEK) ) ) END, NULL) AS mpagar,
                        solpagos.fechaCreacion,
                        solpagos.programado,
                        solpagos.idsolicitud,
                        solpagos.folio,
                        IFNULL(solpagos.proyecto, pd.nombre) AS proyecto,
                        solpagos.homoclave,
                        solpagos.etapa AS soletapa,
                        solpagos.condominio,
                        solpagos.idsolicitud,
                        solpagos.justificacion,
                        empresas.abrev,
                        proveedores.nombre,
                        IF(solpagos.programado IS NOT NULL, solpagos.cantidad + IFNULL(cant_pag.pagado, 0),  solpagos.cantidad) AS cantidad,
                        IFNULL(facturas.descripcion, 'SF') AS descripcion, 
                        IFNULL(facturas.uuid, 'SF') AS uuid, 
                        capturista.nombre_completo, 
                        DATE(solpagos.fecelab) AS fecelab,
                        etapas.nombre AS etapa,
                        IFNULL(cant_pag.autorizado, 0) AS autorizado,
                        IFNULL(cant_pag.pagado, 0) AS pagado,
                        IFNULL(notifi.visto, 1) AS visto,
                        solpagos.moneda,
                        IFNULL(DATE(solpagos.fecha_autorizacion), '--') AS fecha_autorizacion, 
                        solpagos.orden_compra AS orden_compra,
                        especial.nombre_contrato AS contrato,
                        IFNULL(facturas.descripcion, 'SF') AS descripcion,
                        IFNULL(os.nombre, 'NA') AS oficina,
                        IFNULL(tsp.nombre, 'NA') tServicioPartida, /** INICIO FECHA: 02-ENERO-2025 @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                        CASE 
                            WHEN solpagos.caja_chica = 1 THEN 'CAJA CHICA'
                            WHEN solpagos.caja_chica = 3 THEN 'REEMBOLSO'
                            ELSE 'NA'
                        END AS tipo_solicitud /** FIN FECHA: 02-ENERO-2025 @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                    FROM solpagos 
                    LEFT JOIN ( SELECT *, MIN(facturas.feccrea) 
                                FROM facturas
                                WHERE facturas.tipo_factura IN ( 1, 3 )
                                GROUP BY facturas.idsolicitud) AS facturas 
                        ON facturas.idsolicitud = solpagos.idsolicitud 
                    LEFT JOIN ( SELECT idsolicitud, nombre_contrato, NULL insumo 
                                FROM lcontratos ) AS especial 
                        ON especial.idsolicitud = solpagos.idsolicitud
                    INNER JOIN ( SELECT usuarios.idusuario, CONCAT( usuarios.nombres,' ', usuarios.apellidos ) AS nombre_completo FROM usuarios ) AS capturista 
                        ON capturista.idusuario = solpagos.idusuario 
                    INNER JOIN empresas 
                        ON solpagos.idEmpresa = empresas.idempresa
                    INNER JOIN proveedores
                        ON proveedores.idproveedor = solpagos.idProveedor 
                    INNER JOIN etapas 
                        ON etapas.idetapa = solpagos.idetapa
                    LEFT JOIN ( SELECT  autpagos.idsolicitud, 
                                        COUNT(autpagos.idsolicitud) tpagos,
                                        SUM(IF( autpagos.fecha_pago IS NOT NULL OR autpagos.referencia = 'ABONO', autpagos.cantidad, 0 )) AS pagado,
                                        SUM( IF( autpagos.estatus NOT IN (2), autpagos.cantidad, 0 ) ) AS autorizado 
                                FROM autpagos 
                                GROUP BY autpagos.idsolicitud ) AS cant_pag
                        ON cant_pag.idsolicitud = solpagos.idsolicitud 
                    LEFT JOIN ( SELECT notificaciones.idsolicitud, notificaciones.visto
                                FROM notificaciones
                                WHERE notificaciones.idusuario = '" . $this->session->userdata("inicio_sesion")['id'] . "' 
                                GROUP BY notificaciones.idsolicitud ) AS notifi 
                        ON notifi.idsolicitud = solpagos.idsolicitud
                    LEFT JOIN solicitud_proyecto_oficina spo
                        ON spo.idsolicitud = solpagos.idsolicitud 
                    LEFT JOIN proyectos_departamentos pd
                        ON spo.idProyectos = pd.idProyectos
                    LEFT JOIN oficina_sede os ON spo.idoficina = os.idoficina
                    LEFT JOIN tipo_servicio_partidas tsp ON spo.idTipoServicioPartida = tsp.idTipoServicioPartida
                    WHERE solpagos.idusuario = '" . $this->session->userdata("inicio_sesion")['id'] . "' AND solpagos.idetapa NOT IN (1, 3, 4, 6, 8, 10, 11, 21, 25, 30, 31)
                    ORDER BY IFNULL(visto, 1) ASC, solpagos.fechaCreacion");
                break;
        }
    }

    //LLAMA TODAS LAS FACTURAS QUE ESTEN EN LAS DEMAS AREAS FUERA DEL AREA CORERSPONDIENTE O LISTAS PARA SER AUTORIZADAS POR DG
    function otros_gastos_curso()
    {
        //return $this->db->query("SELECT solpagos.folio, solpagos.proyecto, solpagos.homoclave, solpagos.etapa AS soletapa, solpagos.condominio, solpagos.idsolicitud, solpagos.justificacion, empresas.abrev, proveedores.nombre, solpagos.cantidad, IFNULL(facturas.descripcion, 'SF') AS descripcion, IFNULL(facturas.uuid, 'SF') AS uuid, capturista.nombre_completo, DATE_FORMAT(solpagos.fecelab, '%d/%m/%Y') AS fecelab, etapas.nombre AS etapa, IFNULL(pago_aut.autorizado, 0) AS autorizado, IFNULL(cant_pag.pagado, 0) AS pagado, IFNULL(notifi.visto, 1) AS visto, solpagos.moneda, IFNULL(DATE_FORMAT(solpagos.fecha_autorizacion, '%d/%m/%Y'), '--') AS fecha_autorizacion FROM solpagos LEFT JOIN (SELECT *, MIN(facturas.feccrea) FROM facturas WHERE facturas.tipo_factura IN ( 1, 3 ) GROUP BY facturas.idsolicitud) AS facturas ON facturas.idsolicitud = solpagos.idsolicitud INNER JOIN ( SELECT usuarios.idusuario, CONCAT( usuarios.nombres,' ', usuarios.apellidos ) AS nombre_completo FROM usuarios ) AS capturista ON capturista.idusuario = solpagos.idusuario INNER JOIN empresas ON solpagos.idEmpresa = empresas.idempresa INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor INNER JOIN etapas ON etapas.idetapa = solpagos.idetapa LEFT JOIN ( SELECT autpagos.idsolicitud, SUM(autpagos.cantidad) AS autorizado FROM autpagos WHERE autpagos.estatus NOT IN (2) GROUP BY autpagos.idsolicitud ) AS pago_aut ON pago_aut.idsolicitud = solpagos.idsolicitud LEFT JOIN ( SELECT autpagos.idsolicitud, SUM(autpagos.cantidad) AS pagado FROM autpagos WHERE autpagos.fecha_pago IS NOT NULL GROUP BY autpagos.idsolicitud ) AS cant_pag ON cant_pag.idsolicitud = solpagos.idsolicitud LEFT JOIN ( SELECT notificaciones.idsolicitud, notificaciones.visto FROM notificaciones WHERE notificaciones.idusuario = '".$this->session->userdata("inicio_sesion")['id']."' ) AS notifi ON notifi.idsolicitud = solpagos.idsolicitud WHERE solpagos.idusuario = '".$this->session->userdata("inicio_sesion")['id']."' AND solpagos.nomdepto != '".$this->session->userdata("inicio_sesion")['depto']."' AND solpagos.idetapa NOT IN (1, 3, 4, 6, 8, 21, 25, 30) ORDER BY solpagos.fechaCreacion DESC");

        switch ($this->session->userdata("inicio_sesion")['rol']) {
            case 'DA':
                return $this->db->query("SELECT 
            IFNULL(solpagos.proyecto, pd.nombre) proyecto,
            solpagos.idsolicitud,
            solpagos.nomdepto,
            solpagos.justificacion,
            empresas.abrev,
            proveedores.nombre,
            solpagos.cantidad,
            capturista.nombre_completo,
            DATE_FORMAT(solpagos.fecelab, '%d/%m/%Y') AS fecelab,
            etapas.nombre AS etapa,
            IFNULL(cant_pag.pagado, 0) AS pagado,
            IFNULL(notifi.visto, 1) AS visto,
            solpagos.moneda,
            IFNULL(DATE_FORMAT(solpagos.fecha_autorizacion, '%d/%m/%Y'),
                    '--') AS fecha_autorizacion,
            os.idOficina,
            os.nombre oficina,
            tsp.nombre servicioPartida 
        FROM
            solpagos
                INNER JOIN
            (SELECT 
                usuarios.idusuario,
                    CONCAT(usuarios.nombres, ' ', usuarios.apellidos) AS nombre_completo
            FROM
                usuarios) AS capturista ON capturista.idusuario = solpagos.idusuario
                INNER JOIN
            empresas ON solpagos.idEmpresa = empresas.idempresa
                INNER JOIN
            proveedores ON proveedores.idproveedor = solpagos.idProveedor
                INNER JOIN
            etapas ON etapas.idetapa = solpagos.idetapa
                LEFT JOIN
            (SELECT 
                autpagos.idsolicitud, SUM(autpagos.cantidad) AS pagado
            FROM
                autpagos
            WHERE
                autpagos.fechaDis IS NOT NULL
            GROUP BY autpagos.idsolicitud) AS cant_pag ON cant_pag.idsolicitud = solpagos.idsolicitud
                LEFT JOIN
            (SELECT 
                notificaciones.idsolicitud, notificaciones.visto
            FROM
                notificaciones
            WHERE
                notificaciones.idusuario = '" . $this->session->userdata("inicio_sesion")['id'] . "' GROUP BY notificaciones.idsolicitud, notificaciones.idusuario) AS notifi ON notifi.idsolicitud = solpagos.idsolicitud
            LEFT JOIN solicitud_proyecto_oficina spo  on spo.idsolicitud = solpagos.idsolicitud 
            LEFT JOIN proyectos_departamentos pd  on spo.idProyectos = pd.idProyectos
            LEFT JOIN oficina_sede os ON spo.idoficina = os.idoficina
            LEFT JOIN tipo_servicio_partidas tsp ON spo.idTipoServicioPartida = tsp.idTipoServicioPartida
        WHERE
            solpagos.idusuario IN ( SELECT usuarios.idusuario FROM usuarios WHERE usuarios.depto = '" . $this->session->userdata("inicio_sesion")['depto'] . "' )
                AND solpagos.nomdepto != '" . $this->session->userdata("inicio_sesion")['depto'] . "'
                AND solpagos.idetapa NOT IN (1 , 3, 4, 6, 8, 21, 25, 30)
        ORDER BY solpagos.idetapa ASC, solpagos.fechaCreacion DESC");
                break;
            default:
                return $this->db->query("SELECT 
                    IFNULL(solpagos.proyecto, pd.nombre) proyecto,
                    solpagos.idsolicitud,
                    solpagos.nomdepto,
                    solpagos.justificacion,
                    empresas.abrev,
                    proveedores.nombre,
                    solpagos.cantidad,
                    capturista.nombre_completo,
                    DATE_FORMAT(solpagos.fecelab, '%d/%m/%Y') AS fecelab,
                    etapas.nombre AS etapa,
                    IFNULL(cant_pag.pagado, 0) AS pagado,
                    IFNULL(notifi.visto, 1) AS visto,
                    solpagos.moneda,
                    IFNULL(DATE_FORMAT(solpagos.fecha_autorizacion, '%d/%m/%Y'), '--') AS fecha_autorizacion,
                    os.idOficina,
                    os.nombre oficina,
                    tsp.nombre servicioPartida 
                FROM
                    ( 
                        SELECT 
                            * 
                        FROM 
                            solpagos 
                        JOIN ( SELECT DISTINCT( departamento ) departamento FROM departament_usuario WHERE idusuario = ? AND departamento != ? ) deptos ON solpagos.idetapa NOT IN ( 1, 3, 4, 6, 8, 10, 11, 21, 25, 30 ) AND solpagos.idusuario = ? AND deptos.departamento = solpagos.nomdepto
                        UNION
                        SELECT 
                            * 
                        FROM 
                            solpagos 
                        JOIN ( SELECT usuarios.idusuario FROM usuarios WHERE FIND_IN_SET( ?, usuarios.sup ) ) vigilados ON solpagos.idetapa NOT IN ( 1, 3, 4, 6, 8, 10, 11, 21, 25, 30 ) AND vigilados.idusuario = solpagos.idusuario AND solpagos.nomdepto != ?
                    ) solpagos
                    CROSS JOIN listado_usuarios capturista ON capturista.idusuario = solpagos.idusuario
                    CROSS JOIN empresas ON solpagos.idEmpresa = empresas.idempresa
                    CROSS JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor
                    CROSS JOIN etapas ON etapas.idetapa = solpagos.idetapa
                    LEFT JOIN vw_autpagos cant_pag ON cant_pag.idsolicitud = solpagos.idsolicitud
                    LEFT JOIN notifi ON notifi.idsolicitud = solpagos.idsolicitud AND notifi.idusuario = ?
                    LEFT JOIN solicitud_proyecto_oficina spo  on spo.idsolicitud = solpagos.idsolicitud 
                    LEFT JOIN proyectos_departamentos pd  on spo.idProyectos = pd.idProyectos
                    LEFT JOIN oficina_sede os ON spo.idoficina = os.idoficina
                    LEFT JOIN tipo_servicio_partidas tsp ON spo.idTipoServicioPartida = tsp.idTipoServicioPartida
                    ORDER BY solpagos.idetapa ASC, solpagos.fechaCreacion DESC", array(
                    $this->session->userdata("inicio_sesion")['id'],
                    $this->session->userdata("inicio_sesion")['depto'],
                    $this->session->userdata("inicio_sesion")['id'],
                    $this->session->userdata("inicio_sesion")['id'],
                    $this->session->userdata("inicio_sesion")['depto'],
                    $this->session->userdata("inicio_sesion")['id']
                ));
                break;
        }
    }





    function otros_gastos_curso_nominas()
    {


        return $this->db->query("SELECT 
            solpagos.proyecto,
            solpagos.fechaCreacion,
            solpagos.idsolicitud,
            solpagos.justificacion,
            empresas.abrev,
            proveedores.nombre,
            solpagos.cantidad,
            capturista.nombre_completo,
            DATE_FORMAT(solpagos.fecelab, '%d/%m/%Y') AS fecelab,
            etapas.nombre AS etapa,
            IFNULL(cant_pag.pagado, 0) AS pagado,
            IFNULL(notifi.visto, 1) AS visto,
            solpagos.moneda,
            IFNULL(DATE_FORMAT(solpagos.fecha_autorizacion, '%d/%m/%Y'),
                    '--') AS fecha_autorizacion
        FROM
            solpagos
                INNER JOIN
            (SELECT 
                usuarios.idusuario,
                    CONCAT(usuarios.nombres, ' ', usuarios.apellidos) AS nombre_completo
            FROM
                usuarios) AS capturista ON capturista.idusuario = solpagos.idusuario
                INNER JOIN
            empresas ON solpagos.idEmpresa = empresas.idempresa
                INNER JOIN
            proveedores ON proveedores.idproveedor = solpagos.idProveedor
                INNER JOIN
            etapas ON etapas.idetapa = solpagos.idetapa
                LEFT JOIN
            (SELECT 
                autpagos.idsolicitud, SUM(autpagos.cantidad) AS pagado
            FROM
                autpagos
            WHERE
                autpagos.fechaDis IS NOT NULL
            GROUP BY autpagos.idsolicitud) AS cant_pag ON cant_pag.idsolicitud = solpagos.idsolicitud
                LEFT JOIN
            (SELECT 
                notificaciones.idsolicitud, notificaciones.visto
            FROM
                notificaciones
            WHERE
                notificaciones.idusuario = '" . $this->session->userdata("inicio_sesion")['id'] . "') AS notifi ON notifi.idsolicitud = solpagos.idsolicitud
        WHERE
            solpagos.idusuario = '" . $this->session->userdata("inicio_sesion")['id'] . "'
                AND solpagos.nomdepto ='NOMINAS'
                AND solpagos.idetapa NOT IN (1, 3, 4, 6, 8, 21, 25, 30)
        ORDER BY solpagos.fechaCreacion DESC");
    }

    function otros_gastos_nomina()
    {
        return $this->db->query("SELECT 
            solpagos.proyecto,
            solpagos.idsolicitud,
            solpagos.justificacion,
            empresas.abrev,
            proveedores.nombre,
            solpagos.cantidad,
            capturista.nombre_completo,
            DATE_FORMAT(solpagos.fecelab, '%d/%m/%Y') AS fecelab,
            etapas.nombre AS etapa,
            IFNULL(cant_pag.pagado, 0) AS pagado,
            IFNULL(notifi.visto, 1) AS visto,
            solpagos.moneda,
            IFNULL(DATE_FORMAT(solpagos.fecha_autorizacion, '%d/%m/%Y'),
                    '--') AS fecha_autorizacion
        FROM
            solpagos
                INNER JOIN
            (SELECT 
                usuarios.idusuario,
                    CONCAT(usuarios.nombres, ' ', usuarios.apellidos) AS nombre_completo
            FROM
                usuarios) AS capturista ON capturista.idusuario = solpagos.idusuario
                INNER JOIN
            empresas ON solpagos.idEmpresa = empresas.idempresa
                INNER JOIN
            proveedores ON proveedores.idproveedor = solpagos.idProveedor
                INNER JOIN
            etapas ON etapas.idetapa = solpagos.idetapa
                LEFT JOIN
            (SELECT 
                autpagos.idsolicitud, SUM(autpagos.cantidad) AS pagado
            FROM
                autpagos
            WHERE
                autpagos.fechaDis IS NOT NULL
            GROUP BY autpagos.idsolicitud) AS cant_pag ON cant_pag.idsolicitud = solpagos.idsolicitud
                LEFT JOIN
            (SELECT 
                notificaciones.idsolicitud, notificaciones.visto
            FROM
                notificaciones
            WHERE
                notificaciones.idusuario = '" . $this->session->userdata("inicio_sesion")['id'] . "') AS notifi ON notifi.idsolicitud = solpagos.idsolicitud
        WHERE
            solpagos.idusuario = '" . $this->session->userdata("inicio_sesion")['id'] . "'
                AND solpagos.nomdepto = 'NOMINAS'
                AND solpagos.idetapa NOT IN (1 , 3, 4, 6, 8, 21, 25, 30)
        ORDER BY solpagos.fechaCreacion DESC");
    }

    function editar_solicitud($idsolicitud, $data)
    {
        // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
        return $this->db->update("solpagos", $data, "idsolicitud = '$idsolicitud'");
    }

    /*
    function editar_factura( $idsolicitud, $data ){
        return $this->db->update("facturas", $data, "idsolicitud = '$idsolicitud'");
    }
    */
    function congelar_solicitud($idsolicitud)
    {
        // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
        return $this->db->update("solpagos", array("idetapa" => 25), "idsolicitud = '$idsolicitud' AND idetapa = 3");
    }

    function liberar_solicitud($idsolicitud)
    {
        // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
        //return $this->db->update("solpagos", array( "idetapa" => 3 ), "idsolicitud = '$idsolicitud' AND idetapa = 25");
        $this->db->trans_begin();
        $this->db->query("UPDATE autpagos set estatus = 0 WHERE idsolicitud IN ( $idsolicitud ) AND estatus IN ( 17 )");
        $this->db->query("UPDATE solpagos set idetapa = CASE WHEN solpagos.idetapa = 13 THEN 11 ELSE 3 END WHERE idsolicitud IN ( $idsolicitud ) AND idetapa IN ( 13, 25 )");

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $this->db->trans_commit();
            return TRUE;
        }
    }

    function borrar_solicitud($idsolicitud)
    {
        // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
        //return $this->db->update("facturas", array("tipo_factura" => 0), "idsolicitud = '$idsolicitud'");
        //if( $this->db->delete("solpagos", "idetapa IN ( 1, 3, 4, 6, 8, 21 ) AND idsolicitud = '$idsolicitud'") ){
        //PARA NO ELIMINAR LA SOLICITUD
        $this->db->trans_begin();
        $isDocPdf = $this->db->query("SELECT * FROM historial_documento WHERE idSolicitud = ?", [$idsolicitud])->result_array();
        if (!empty($isDocPdf)) {
            $carpetaBorrado = '';
            switch ($isDocPdf[0]['tipo_doc']) {
                case 3: //viáticos
                    $carpetaBorrado = 'AUTSVIATICOS/';
                    break;
                case 4; //reembolsos
                    $carpetaBorrado = 'AUTSREEMBOLSOS/';
                    break;
                default:
                    //normal
                    $carpetaBorrado = 'PDFS/';
                    break;
            }
            $ruta_pdf = './UPLOADS/' . $carpetaBorrado . $isDocPdf[0]['expediente'];
            $data = array("modificado" => date('Y-m-d H:i:s'), "estatus" => 0, "movimiento" => "Se elimino documento PDF");
            unlink($ruta_pdf);
            $this->updateHistorialDocumento($data, $idsolicitud, $isDocPdf[0]['tipo_doc']);
        }
        if ($this->db->update("solpagos", array("idetapa" => 0), "idetapa IN ( 1, 3, 4, 6, 8, 21 ) AND idsolicitud = '$idsolicitud'")) {
            $consulta = [];
            $folioOC = $this->db->query("SELECT orden_compra AS folio FROM solpagos WHERE nomdepto IN ( 'CONSTRUCCION', 'JARDINERIA' ) AND idsolicitud ='" . $idsolicitud . "'");
            if ($folioOC->num_rows() > 0) {
                $mmuu = $this->db->query("SELECT total AS monto, uuid as UUID FROM facturas WHERE idsolicitud ='" . $idsolicitud . "'");
                if ($mmuu->num_rows() > 0) {
                    $consulta["folio"] = $folioOC->row()->folio;
                    $consulta["monto"] = $mmuu->row()->monto;
                    $consulta["UUID"] = $mmuu->row()->UUID;
                }
            }
            $this->db->delete("facturas", array('idsolicitud' => $idsolicitud));
            $this->db->delete("sol_contrato", array('idsolicitud' => $idsolicitud));
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return array("respuesta" => FALSE);
        } else {
            $this->db->trans_commit();
            return array("respuesta" => TRUE, "consulta" => $consulta);
        }
    }

    function enviar_a_dg($idsolicitud, $departamento)
    {
        // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
        $dup = $this->db->query("SELECT * FROM notificaciones WHERE idsolicitud = '" . $idsolicitud . "' AND idusuario = '" . $this->session->userdata('inicio_sesion')['id'] . "' ;");
        if ($dup->num_rows() == 0) {
            $this->db->insert("notificaciones", array("fecha" => date("Y-m-d h:i:s"), "visto" => 1, "idsolicitud" => $idsolicitud, "idusuario" => $this->session->userdata("inicio_sesion")['id']));
        } else {
            $this->db->update("notificaciones", array("fecha" => date("Y-m-d h:i:s"), "visto" => 1), "idsolicitud = '$idsolicitud' AND idusuario = '" . $this->session->userdata('inicio_sesion')['id'] . "';");
        }


        $asistentes = $this->db->query("SELECT usuarios.idusuario FROM usuarios WHERE usuarios.depto = '" . $this->session->userdata("inicio_sesion")['depto'] . "' AND usuarios.rol IN ( 'AS', 'DA' ) AND usuarios.idusuario NOT IN ( SELECT notificaciones.idusuario FROM notificaciones WHERE notificaciones.idsolicitud = '$idsolicitud' )");
        if ($asistentes->num_rows() > 0) {
            foreach ($asistentes->result() as $row) {
                $this->db->insert(
                    "notificaciones",
                    array(
                        "fecha" => date("Y-m-d h:i:s"),
                        "visto" => 1,
                        "idsolicitud" => $idsolicitud,
                        "idusuario" => $row->idusuario
                    )
                );
            }
        }

        switch ($departamento) {
            default:
            /**
             * Usuario anterior ENTORNO PRUEBAS, PRODUCCION: 317
             * Usuario actual ENTORNO PRUEBAS, PRODUCCION: 3013
            */
                if ($this->session->userdata("inicio_sesion")['depto'] == 'CAPITAL HUMANO' && $this->session->userdata("inicio_sesion")['rol'] == 'FP') { /** FECHA: 06-JUNIO-2025 | NUEVO CONCEPTO SOLO DEPTO CH | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                    return $this->db->query("UPDATE solpagos set idetapa = CASE 
                    WHEN solpagos.nomdepto IN ( 'NOMINAS', 'FINIQUITO', 'FINIQUITO POR PARCIALIDAD', 'FINIQUITO POR RENUNCIA', 'PRESTAMO', 'BONO', 'PRESTAMO POR ADEUDO', 'PAGO FUERA DE NOMINA') AND solpagos.idetapa = 1 THEN 3
                    WHEN solpagos.nomdepto IN ( 'NOMINAS', 'FINIQUITO', 'FINIQUITO POR PARCIALIDAD', 'FINIQUITO POR RENUNCIA', 'PRESTAMO', 'BONO', 'PRESTAMO POR ADEUDO', 'PAGO FUERA DE NOMINA') AND solpagos.idetapa = 6 THEN 3
                    WHEN ( ( solpagos.nomdepto LIKE 'DEVOLUCION%' OR solpagos.nomdepto LIKE 'TRASPASO%' ) AND solpagos.proyecto NOT IN ( 'TRASPASO INTERNO', 'DEVOLUCION POR DOMICILIACION', 'DEVOLUCION', 'DEVOLUCION POR DEPOSITO EN GARANTIA', 'DEVOLUCION POR MEDIDOR' ) ) THEN 50 ELSE 3 END WHERE idsolicitud = '$idsolicitud' AND idetapa IN ( 1,14,6 )");
                } else {
                    return $this->db->query("UPDATE solpagos set idetapa = CASE 
                    WHEN solpagos.rcompra = 1 AND ( solpagos.caja_chica IS NULL OR solpagos.caja_chica != 1 ) THEN 2 
                    WHEN solpagos.nomdepto IN ( 'NOMINAS', 'FINIQUITO', 'FINIQUITO POR PARCIALIDAD', 'FINIQUITO POR RENUNCIA', 'PRESTAMO', 'BONO', 'PRESTAMO POR ADEUDO' ) AND solpagos.idetapa = 1 THEN 52 
                    WHEN solpagos.nomdepto IN ( 'NOMINAS', 'FINIQUITO', 'FINIQUITO POR PARCIALIDAD', 'FINIQUITO POR RENUNCIA', 'PRESTAMO', 'BONO', 'PRESTAMO POR ADEUDO' ) AND solpagos.idetapa = 6 THEN 52 
                    WHEN solpagos.nomdepto IN ( 'NOMINAS', 'FINIQUITO', 'FINIQUITO POR PARCIALIDAD', 'FINIQUITO POR RENUNCIA', 'PRESTAMO', 'BONO', 'PRESTAMO POR ADEUDO' ) AND solpagos.idetapa = 52 THEN 3
                    WHEN ( ( solpagos.nomdepto LIKE 'DEVOLUCION%' OR solpagos.nomdepto LIKE 'TRASPASO%' ) AND solpagos.proyecto NOT IN ( 'TRASPASO INTERNO', 'DEVOLUCION POR DOMICILIACION', 'DEVOLUCION', 'DEVOLUCION POR DEPOSITO EN GARANTIA', 'DEVOLUCION POR MEDIDOR' ) ) THEN 50 ELSE 3 END WHERE idsolicitud = '$idsolicitud' AND idetapa IN ( 1,4, 6,14, 52 )");
                }
                break;
        }
    }

    function devolucion_sigetapaM($post)
    {
        // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
        $respuesta = false;
        if ($post["etapaold"] == 1 || (in_array($post["etapaold"], [54, 55]) && $_POST["avanza"] == 1)) {

            if (in_array($post["etapaold"], [55]))
                $sql = "update solpagos set idetapa=53 where idsolicitud=$post[idsolicitud] and idetapa=$post[etapaold];";
            else {
                if ($this->session->userdata("inicio_sesion")['sup'] && $this->session->userdata("inicio_sesion")['sup'] != '') {
                    $sql = "update solpagos set idetapa = 55 where idsolicitud=$post[idsolicitud] and idetapa=$post[etapaold];";
                } else {
                    $sql = "update solpagos set idetapa = 53 where idsolicitud=$post[idsolicitud] and idetapa=$post[etapaold];";
                }
            }

            $respuesta = $this->db->query($sql);
            if ($this->db->affected_rows() > 0)
                log_sistema($this->session->userdata("inicio_sesion")['id'], $post["idsolicitud"], is_null($this->session->userdata("inicio_sesion")['sup']) ? "SE HA " . ($post["etapaold"] == 54 ? "RE" : "") . "ENVIADO A REVISIÓN POR CONTABILIDAD" : "SE HA ENVIADO A REVISIÓN POR COORD. DE ADMON.");
        } else if ($post["etapaold"] == 54 && $_POST["avanza"] == 0) {
            $sql = "update solpagos set idetapa=30 where idsolicitud=$post[idsolicitud] and idetapa=$post[etapaold];";
            $respuesta = $this->db->query($sql);
            if ($this->db->affected_rows() > 0)
                log_sistema($this->session->userdata("inicio_sesion")['id'], $post["idsolicitud"], "SE HA CANCELADO LA SOLICITUD POR ADMINISTRACIÓN");
        } else if ($post["etapaold"] == 53 && $post["avanza"] == 1) {
            $sql = "update solpagos set idAutoriza=" . $this->session->userdata("inicio_sesion")['id'] . ", idetapa = CASE "
                . "WHEN ( ( solpagos.nomdepto LIKE 'DEVOLUCION%' OR solpagos.nomdepto LIKE 'TRASPASO%' ) AND solpagos.proyecto NOT IN ( 'TRASPASO INTERNO', 'DEVOLUCION POR DOMICILIACION', 'DEVOLUCION', 'DEVOLUCION POR DEPOSITO EN GARANTIA', 'DEVOLUCION POR MEDIDOR' ) ) THEN 50 ELSE 3 END "
                . "where idsolicitud=$post[idsolicitud] and idetapa=$post[etapaold];";
            $respuesta = $this->db->query($sql);
            if ($this->db->affected_rows() > 0)
                log_sistema($this->session->userdata("inicio_sesion")['id'], $post["idsolicitud"], "SE HA ENVIADO PARA AUTORIZACIÓN");
        } else if (in_array($post["etapaold"], [53, 55]) && $post["avanza"] == 0) {
            $sql = "update solpagos set idetapa = 54 where idsolicitud=$post[idsolicitud] and idetapa=$post[etapaold];";
            $respuesta = $this->db->query($sql);
            if ($this->db->affected_rows() > 0)
                log_sistema($this->session->userdata("inicio_sesion")['id'], $post["idsolicitud"], "SE HA RECHAZADO LA DEVOLUCIÓN");
        }
        return $respuesta;
    }

    function aprobada_da($idsolicitud, $fecha, $caja_chica, $departamento)
    {
        // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
        $idUsuario = $this->session->userdata("inicio_sesion")['id'];

        $fechaxml =  date('Y-m-d', strtotime(str_replace("/", "-", $fecha)));
        $fecha_actual = date("Y-m-d");
        $poste_anterior = date("Y-m", strtotime(date("Y-m") . ' -1 month')) . "-01";
        $poste_superior = date('Y-m') . "-05";

        if ($poste_anterior <= $fecha_actual && $fecha_actual <= $poste_superior) {
            $limite_inferior = $poste_anterior;
            $limite_superior = $poste_superior;
        } else {
            $limite_superior = date('Y-m', strtotime(date("Y-m") . ' +1 month')) . "-10";
            $limite_inferior = date('Y-m') . "-01";
        }

        $validar_fecha = ($fechaxml >= $limite_inferior && $fechaxml <= $limite_superior) || $limite_superior < $fechaxml;
        //$validar_fecha = $fechaxml >= date("Y")."-01-01" && $fechaxml <= date("Y")."-12-31";
        $validar_fecha = TRUE;

        if ($validar_fecha) {
            $departamento = $departamento ? $departamento : $this->session->userdata("inicio_sesion")['depto'];

            switch ($departamento) {
                case 'DEVOLUCIONES':
                case 'PRESTAMO':
                case 'PRESTAMO POR ADEUDO':
                case 'FINIQUITO':
                case 'FINIQUITO POR RENUNCIA':
                case 'FINIQUITO POR PARCIALIDAD':
                    return $this->db->update("solpagos", array("idetapa" => 7, "fecha_autorizacion" => date("Y-m-d"), "idAutoriza" => $idUsuario), "idsolicitud = '$idsolicitud' AND idetapa = 3");
                    break;
                case 'BONO':
                case 'NOMINAS':
                case 'PAGO FUERA DE NOMINA':
                    return $this->db->query("CALL insertar_pago_nomina( ?, ?, ?)", array(
                        "fecha " => date("Y-m-d H:i:s"),
                        "idsolicitud " => $idsolicitud,
                        "idautor " => $this->session->userdata("inicio_sesion")['id']
                    ));
                    break;
                case 'IMPUESTOS':
                    return $this->db->query("CALL insertar_pago( ?, ?, ?)", array(
                        "fecha " => date("Y-m-d H:i:s"),
                        "idsolicitud " => $idsolicitud,
                        "idautor " => $this->session->userdata("inicio_sesion")['id']
                    )) ? TRUE : FALSE;
                    break;
                case 'JARDINERIA':
                case 'CONSTRUCCION':
                case 'ARQUITECTURA DEL PAISAJE':
                    return $this->db->query("UPDATE solpagos 
                                                SET idetapa = 
                                                    CASE 
                                                        WHEN metoPago ='INTERCAMBIO' 
                                                            THEN 20 
                                                        WHEN prioridad = 1 AND ( solpagos.caja_chica IS NULL OR solpagos.caja_chica = 0 ) 
                                                            THEN 7 
                                                        ELSE 5 
                                                    END
                                                , idAutoriza = '$idUsuario' 
                                                , fecha_autorizacion = NOW() 
                                            WHERE idsolicitud = '$idsolicitud' 
                                            AND idetapa = 3");
                    break;
                default:
                    return $this->db->query("UPDATE solpagos 
                                                SET solpagos.idetapa = 
                                                    CASE 
                                                        WHEN ( 
                                                            solpagos.servicio IS NOT NULL 
                                                            AND solpagos.servicio != 0 ) 
                                                            OR ( 
                                                                solpagos.caja_chica IS NOT NULL 
                                                                AND solpagos.caja_chica IN ( 1, 2) ) 
                                                            OR solpagos.nomdepto IN ( 
                                                                'DEVOLUCIONES'
                                                                , 'PRESTAMO'
                                                                , 'FINIQUITO'
                                                                , 'FINIQUITO POR RENUNCIA'
                                                                , 'FINIQUITO ASIMILADO'
                                                                , 'BONO'
                                                                , 'FINIQUITO POR PARCIALIDAD'
                                                                , 'TRASPASO'
                                                                , 'DEVOLUCION' 
                                                            )
                                                        THEN 5 
                                                    ELSE 2 
                                                    END
                                                , solpagos.idAutoriza = '$idUsuario'
                                                , solpagos.fecha_autorizacion = '" . date("Y-m-d H:i:s") . "'
                                            WHERE idsolicitud = '$idsolicitud' 
                                            AND idetapa = 3");
                    break;
            }
        } else
            return false;
        //return $this->db->update("solpagos", array( "idetapa" => 5, "fecha_autorizacion" => date("Y-m-d") ), "idsolicitud = '$idsolicitud' AND idetapa = 3");
    }

    function rechazada_da($idsolicitud) //
    {
        // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
        return $this->db->query(
            "UPDATE solpagos 
            SET idetapa = CASE WHEN solpagos.nomdepto IN ( 'DEVOLUCIONES', 'TRASPASO','DEVOLUCION' ) 
            THEN 54 WHEN idetapa = 2 AND solpagos.nomdepto IN ( 'CONSTRUCCION', 'JARDINERIA' ) 
            THEN 4 ELSE 6 END WHERE idsolicitud = '$idsolicitud' AND idetapa IN ( 2, 3, 12, 52)"
        );
        //return $this->db->update("solpagos", array( "idetapa" => 6 ), "idsolicitud = '$idsolicitud' AND idetapa IN (3, 12, 52)");
    }

    function getSolicitud($idsolicitud)
    {
        //return $this->db->query("SELECT *, sum(total) as pagado FROM solpagos INNER JOIN ( SELECT empresas.rfc AS rfc_empresas, empresas.idempresa FROM empresas ) empresas ON solpagos.idEmpresa = empresas.idempresa INNER JOIN ( SELECT proveedores.nombre AS nombre_proveedor, proveedores.idproveedor, proveedores.rfc AS rfc_proveedores, proveedores.estatus as EstatusProv, proveedores.tipo_prov as TipoProv FROM  proveedores ) proveedores ON proveedores.idproveedor = solpagos.idProveedor LEFT JOIN (SELECT *, MIN(facturas.feccrea) FROM facturas WHERE facturas.tipo_factura IN ( 1, 3 ) GROUP BY facturas.idsolicitud) AS facturas ON facturas.idsolicitud = solpagos.idsolicitud WHERE solpagos.idsolicitud = '$idsolicitud'");
        return $this->db->query("SELECT
                sp.*,
                e.rfc rfc_empresas,
                e.idempresa,
                cantidad pagado,
                p.nombre nombre_proveedor,
                p.idproveedor,
                p.rfc rfc_proveedores,
                p.estatus EstatusProv,
                p.tipo_prov TipoProv,
                et.idetapa, 
                et.nombre netapa,
                f.uuid,
                IF(spo.idProyectos is null, 'N', 'S') esProyectoNuevo,
                os.idOficina,
                os.nombre oficina,
                IF(pd.idProyectos is null, sp.proyecto, pd.idProyectos) idProyectos,
                pd.nombre proyectoNuevo,
                cp.concepto proyectoViejo,
                spo.idTipoServicioPartida,
                ed.idZonas,
                se.id_estado,
                se.diasDesayuno,
                se.diasComida,
                se.diasCena,
                CASE 
                    WHEN sp.caja_chica = 2 AND spo.idTipoServicioPartida = 7 THEN 
                        tdc.idtitular
                    WHEN sp.caja_chica = 2 AND spo.idTipoServicioPartida <> 7 THEN
                        us.da
                    ELSE 
                        null
                END as idtitular
            FROM solpagos sp 
            INNER JOIN empresas e ON sp.idEmpresa = e.idempresa
            INNER JOIN proveedores p ON p.idproveedor = sp.idProveedor
            INNER JOIN etapas et ON et.idetapa = sp.idetapa
            LEFT JOIN solicitud_proyecto_oficina spo ON spo.idsolicitud = sp.idsolicitud 
            LEFT JOIN oficina_sede os ON spo.idoficina = os.idoficina
            LEFT JOIN proyectos_departamentos pd ON spo.idProyectos = pd.idProyectos
            LEFT JOIN catalogo_proyecto cp on cp.concepto = sp.proyecto
            LEFT JOIN solicitud_estados se on se.idsolicitud = sp.idsolicitud
            LEFT JOIN estados ed on se.id_estado = ed.id_estado
            LEFT JOIN ( 
                SELECT 
                    idsolicitud, 
                    uuid 
                FROM facturas 
                WHERE facturas.tipo_factura IN (1 , 3) 
                AND idsolicitud = $idsolicitud
                GROUP BY facturas.idsolicitud ) f ON f.idsolicitud = sp.idsolicitud
            LEFT JOIN tcredito tdc ON sp.idResponsable = tdc.idtcredito
            INNER JOIN usuarios us ON sp.idusuario = us.idusuario
            WHERE sp.idsolicitud = $idsolicitud;");
    }

    function verificar_proveedor_permitido($rfc)
    {
        //NUEVA FORMULA PARA EL BLOQUEO DE PROVEEDORES 10022020
        /*return $this->db->query("SELECT * FROM proveedores INNER JOIN (SELECT bancos.idbanco, bancos.nombre AS nom_banco FROM bancos ) AS bancos ON proveedores.idbanco = bancos.idbanco WHERE ( proveedores.idproveedor NOT IN 
        ( SELECT DISTINCT(solpagos.idProveedor) idProveedor FROM solpagos INNER JOIN ( SELECT autpagos.idsolicitud, fechaDis FROM autpagos WHERE autpagos.idpago IN ( SELECT MAX(idpago) idpago FROM autpagos WHERE estatus = 16 GROUP BY autpagos.idsolicitud ) GROUP BY autpagos.idsolicitud ) autpagos ON autpagos.idsolicitud = solpagos.idsolicitud WHERE solpagos.nomdepto = '".$this->session->userdata("inicio_sesion")['depto']."' AND solpagos.idetapa = 10 AND TIMESTAMPDIFF(DAY, IFNULL(autpagos.fechaDis, NOW()), NOW()) >= 30 AND ( solpagos.tendrafac IS NOT NULL AND solpagos.tendrafac = 1 ) AND ( solpagos.caja_chica IS NULL OR solpagos.caja_chica != 1 ) ) OR proveedores.excp = 2 )
        AND proveedores.estatus IN ( 1, 2, 4 ) AND proveedores.rfc = '$rfc' ORDER BY proveedores.nombre");*/
        //VEASE LA VISTA provedores_bloqueados
        /*
        return $this->db->query("SELECT * FROM proveedores INNER JOIN ( SELECT bancos.idbanco, bancos.nombre AS nom_banco FROM bancos ) AS bancos ON proveedores.idbanco = bancos.idbanco WHERE proveedores.idproveedor NOT IN 
        ( SELECT idproveedor FROM provedores_bloqueados WHERE nomdepto = ? ) AND proveedores.estatus IN ( 1, 2, 4, 9 ) AND proveedores.rfc = ? ORDER BY proveedores.nombre", array($this->session->userdata("inicio_sesion")['depto'], $rfc));
        */
        /*
        return $this->db->query("SELECT proveedores.*, bancos.* FROM proveedores 
        INNER JOIN ( SELECT bancos.idbanco, bancos.nombre AS nom_banco FROM bancos ) AS bancos ON proveedores.idbanco = bancos.idbanco 
        LEFT JOIN ( SELECT idproveedor FROM provedores_bloqueados WHERE nomdepto = ? ) pb ON pb.idproveedor = proveedores.idproveedor
        WHERE proveedores.estatus IN ( 1, 2, 4, 9 ) AND proveedores.rfc = ? AND pb.idproveedor IS NULL ORDER BY proveedores.nombre", );
        */
        return $this->db->query("SELECT proveedores.*, bancos.* FROM proveedores 
        JOIN (
			SELECT DISTINCT p.rfc
			FROM ( SELECT idproveedor FROM provedores_bloqueados WHERE nomdepto = ? ) pb
			JOIN proveedores p ON p.idproveedor = pb.idproveedor
        ) pb ON proveedores.rfc = pb.rfc AND proveedores.rfc = ?
		INNER JOIN ( SELECT bancos.idbanco, bancos.nombre AS nom_banco FROM bancos ) AS bancos ON proveedores.idbanco = bancos.idbanco", [$this->session->userdata("inicio_sesion")['depto'], $rfc]);
    }

    function getFacturabySolicitud($idsolicitud)
    {
        //return $this->db->query("SELECT * FROM facturas WHERE facturas.idsolicitud = '$idsolicitud' AND facturas.tipo_factura IN (1, 3)");
        return $this->db->query("SELECT *, MIN(facturas.feccrea) 
                                 FROM facturas 
                                 WHERE facturas.tipo_factura IN ( 1, 3 ) AND facturas.idsolicitud = '$idsolicitud'
                                 GROUP BY facturas.idsolicitud");
    }

    function getPagossfactura()
    {
        //return $this->db->query("SELECT solpagos.idsolicitud, solpagos.folio, autpagos.idpago, proveedores.nombre, autpagos.cantidad, DATE_FORMAT(autpagos.fechaOpe , '%d/%m/%Y') AS fechaOpe, autpagos.formaPago, total_pagos, IFNULL(notifi.visto, 1) AS visto FROM autpagos INNER JOIN solpagos ON solpagos.idsolicitud = autpagos.idsolicitud INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor INNER JOIN ( SELECT autpagos.idsolicitud, COUNT( autpagos.idpago ) AS total_pagos FROM autpagos GROUP BY autpagos.idsolicitud ) AS pagos_t ON solpagos.idsolicitud = pagos_t.idsolicitud LEFT JOIN ( SELECT notificaciones.idsolicitud, notificaciones.visto FROM notificaciones WHERE notificaciones.idusuario = '".$this->session->userdata("inicio_sesion")['id']."' ) AS notifi ON notifi.idsolicitud = solpagos.idsolicitud WHERE autpagos.idfactura IS NULL AND autpagos.fecha_pago IS NOT NULL AND ((pagos_t.total_pagos > 1 AND solpagos.idetapa IN (9, 10, 11))) AND solpagos.idusuario = '".$this->session->userdata("inicio_sesion")['id']."'");
        return $this->db->query("SELECT autpagos.fechaOpe, solpagos.idusuario, usuarios.nombres, empresas.abrev,  usuarios.apellidos , autpagos.idpago, solpagos.idsolicitud, facturas.foliofac, IFNULL(notifi.visto, 1) AS visto,proveedores.nombre, autpagos.cantidad, solpagos.cantidad as cant_solpagos, solpagos.metoPago from solpagos inner join facturas on facturas.idsolicitud = solpagos.idsolicitud inner join proveedores on proveedores.idproveedor = solpagos.idProveedor inner join autpagos on autpagos.idsolicitud = solpagos.idsolicitud inner join usuarios on solpagos.idusuario = usuarios.idusuario inner join empresas on  solpagos.idEmpresa = empresas.idempresa LEFT JOIN ( SELECT notificaciones.idsolicitud, notificaciones.visto FROM notificaciones WHERE notificaciones.idusuario = '" . $this->session->userdata("inicio_sesion")['id'] . "' ) AS notifi ON notifi.idsolicitud = solpagos.idsolicitud WHERE facturas.tipo_factura=1  AND autpagos.estatus = 15 AND autpagos.idfactura IS NULL AND solpagos.idusuario = '" . $this->session->userdata("inicio_sesion")['id'] . "' ");
    }

    function getPagosSolicitud($idPago)
    {
        return $this->db->query("SELECT *,facturas.uuid,proveedores.rfc AS rfc_prov, empresas.rfc AS rfc_emp, autpagos.cantidad FROM solpagos INNER JOIN empresas ON solpagos.idEmpresa = empresas.idempresa INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor INNER JOIN autpagos ON autpagos.idsolicitud = solpagos.idsolicitud LEFT JOIN (SELECT *, MIN(facturas.feccrea) FROM facturas WHERE facturas.tipo_factura IN ( 1, 3 ) GROUP BY facturas.idsolicitud) AS facturas ON facturas.idsolicitud = solpagos.idsolicitud WHERE autpagos.idpago = '$idPago'");
    }

    function getPagossProvision()
    {
        //return $this->db->query("SELECT solpagos.idsolicitud, solpagos.folio, autpagos.idpago, proveedores.nombre, autpagos.cantidad, DATE_FORMAT(autpagos.fechaOpe , '%d/%m/%Y') AS fechaOpe, autpagos.formaPago, total_pagos, IFNULL(notifi.visto, 1) AS visto FROM autpagos INNER JOIN solpagos ON solpagos.idsolicitud = autpagos.idsolicitud INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor INNER JOIN ( SELECT autpagos.idsolicitud, COUNT( autpagos.idpago ) AS total_pagos FROM autpagos GROUP BY autpagos.idsolicitud ) AS pagos_t ON solpagos.idsolicitud = pagos_t.idsolicitud LEFT JOIN ( SELECT notificaciones.idsolicitud, notificaciones.visto FROM notificaciones WHERE notificaciones.idusuario = '".$this->session->userdata("inicio_sesion")['id']."' ) AS notifi ON notifi.idsolicitud = solpagos.idsolicitud WHERE autpagos.idfactura IS NULL AND autpagos.fecha_pago IS NOT NULL AND pagos_t.total_pagos = 1 AND solpagos.idetapa IN (10) AND solpagos.idsolicitud NOT IN (SELECT facturas.idsolicitud FROM facturas WHERE facturas.tipo_factura IN (3)) AND solpagos.idusuario = '".$this->session->userdata("inicio_sesion")['id']."'");
        //return $this->db->query("SELECT solpagos.idusuario, usuarios.nombres, solpagos.idsolicitud, usuarios.apellidos , facturas.foliofac, proveedores.nombre,  solpagos.cantidad as cant_solpagos, solpagos.metoPago , IFNULL(notifi.visto, 1) AS visto FROM solpagos inner join facturas on facturas.idsolicitud = solpagos.idsolicitud inner join proveedores on proveedores.idproveedor = solpagos.idProveedor inner join usuarios on solpagos.idusuario = usuarios.idusuario LEFT JOIN ( SELECT notificaciones.idsolicitud, notificaciones.visto FROM notificaciones WHERE notificaciones.idusuario = '".$this->session->userdata("inicio_sesion")['id']."' ) AS notifi ON notifi.idsolicitud = solpagos.idsolicitud WHERE facturas.tipo_factura=1 AND solpagos.idsolicitud NOT IN (SELECT facturas.idsolicitud FROM facturas where facturas.tipo_factura = 3) AND solpagos.idetapa = 10 AND solpagos.idusuario = '".$this->session->userdata("inicio_sesion")['id']."'");
        return $this->db->query("SELECT empresas.abrev, solpagos.idusuario, usuarios.nombres, solpagos.idsolicitud, usuarios.apellidos , facturas.foliofac, proveedores.nombre,  solpagos.cantidad as cant_solpagos, (Select sum(if(tipo_factura IN (1,3),total, 0)) FROM facturas where idsolicitud = solpagos.idsolicitud ) as cant_recibido, facturas.uuid as folio_fiscal, solpagos.metoPago , 1 AS visto, autpagos.fechaDis fechaOpe FROM solpagos LEFT JOIN (SELECT *, MIN(facturas.feccrea) FROM facturas WHERE facturas.tipo_factura IN ( 1 ) GROUP BY facturas.idsolicitud) AS facturas ON facturas.idsolicitud = solpagos.idsolicitud inner join proveedores on proveedores.idproveedor = solpagos.idProveedor INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa inner join usuarios on solpagos.idusuario = usuarios.idusuario INNER JOIN ( SELECT autpagos.idsolicitud, autpagos.fechaDis FROM autpagos WHERE autpagos.fechaDis IS NOT NULL GROUP BY autpagos.idsolicitud HAVING MAX( autpagos.fechaDis ) ) autpagos ON autpagos.idsolicitud = solpagos.idsolicitud
        WHERE ( ( facturas.tipo_factura = 1 AND solpagos.idsolicitud NOT IN (SELECT facturas.idsolicitud FROM facturas where facturas.tipo_factura = 3) ) || ( solpagos.tendrafac = 1 AND facturas.tipo_factura IS NULL ) ) AND solpagos.idetapa IN ( 9, 10 ) AND solpagos.idusuario = '" . $this->session->userdata("inicio_sesion")['id'] . "'  ORDER BY visto ASC, idsolicitud");
    }

    function completar_solicitud($idsolicitud)
    {
        // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
        return $this->db->update("solpagos", array("idetapa" => 11), "idsolicitud = '$idsolicitud' AND idetapa = 10");
    }

    function actualizar_folio_fiscal($idsolicitud, $nuevofolio)
    {
        // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
        return $this->db->update("solpagos", array('folio' => $nuevofolio), "idsolicitud = '$idsolicitud'");
    }

    //OBTIENE LOS DATOS DEL PROVEEDOR BLOQUEADOS EN ARTICULO 69B
    function verificar_proveedor69b($razon_social, $rfc_proveedor)
    {
        return $this->db->query('SELECT * FROM complemento_69b WHERE complemento_69b.rfccomplemento = "' . $razon_social . '"');
    }


    function cambiar_departamento($idsolicitud, $departamento, $fecha_pago)
    {
        // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
        /*
        return $this->db->update("solpagos", array( 
            "nomdepto" => $departamento
        ), "idsolicitud = '$idsolicitud' AND idetapa NOT IN ( 9, 10, 11 )");
        */
        $this->db->query("UPDATE solpagos INNER JOIN solpagos S ON solpagos.idsolicitud = S.idsolicitud SET solpagos.fecha_fin = " . ($fecha_pago ? "S.fecelab" : 'NULL') . ", solpagos.fecelab = CASE WHEN S.fecha_fin IS NOT NULL THEN S.fecha_fin ELSE '$fecha_pago' END, S.nomdepto = '$departamento' WHERE solpagos.idsolicitud = '$idsolicitud' AND solpagos.idetapa NOT IN ( 9, 10, 11 )");
        return $this->db->query("SELECT solpagos.fecelab FROM solpagos WHERE solpagos.idsolicitud = '$idsolicitud'")->row()->fecelab;
    }


    /***********************CONTRATOS*********************/
    function guardar_solicitud_contrato($data)
    {
        return $this->db->insert("sol_contrato", $data);
    }

    function actualiza_solicitud_contrato($data)
    {
        return $this->db->query("update sol_contrato set idcontrato=$data[idcontrato] where idsolicitud=$data[idsolicitud];");
    }

    function actualiza_financiamiento($idsolicitud, $financ)
    {
        // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
        return $this->db->update("solpagos", array('financiamiento' => $financ), "idsolicitud = '$idsolicitud'");
    }

    function get_sol_contrato($idsolicitud)
    {
        return $this->db->query("SELECT * FROM sol_contrato WHERE sol_contrato.idsolicitud = '$idsolicitud'");
    }

    function get_contrato_by_proveedor($idproveedor)
    {
        return $this->db->query("SELECT
                contratos.idcontrato, 
                proveedores.idProveedor,
                contratos.nombre ncontrato,
                proveedores.nombre nproveedor, 
                contratos.cantidad, 
                IFNULL(scontrato.consumido, 0) consumido
            FROM contratos
            INNER JOIN ( SELECT proveedores.idproveedor, proveedores.nombre FROM proveedores ) proveedores ON proveedores.idproveedor = contratos.idproveedor
            LEFT JOIN ( SELECT idcontrato, SUM(solpagos.cantidad) consumido FROM solpagos
            INNER JOIN sol_contrato ON sol_contrato.idsolicitud = solpagos.idsolicitud
            WHERE solpagos.idetapa NOT IN (0, 30)
            GROUP BY idcontrato ) scontrato ON scontrato.idcontrato = contratos.idcontrato WHERE contratos.idproveedor = '$idproveedor' ORDER BY nproveedor, ncontrato");
    }

    function update_contrato_solicitud($data, $idsolicitud)
    {
        $this->db->update("sol_contrato", $data, "idsolicitud = '$idsolicitud'");
    }
    /*********************************************/
    function get_proyecto_by_proveedor($rfcProveedor)
    {

        return $this->db->query("SELECT c.idcontrato,c.nombre AS nombre_contrato,
        ( c.cantidad - ifnull(sum(s.cantidad),0) ) AS residuo 
        FROM ( SELECT * FROM contratos WHERE contratos.estatus = 1 ) c 
        LEFT JOIN sol_contrato sc ON sc.idcontrato = c.idcontrato
        LEFT JOIN solpagos s ON s.idsolicitud = sc.idsolicitud 
        WHERE (s.idetapa NOT IN (0, 30) OR idsol_contrato IS NULL ) 
        AND c.rfc_proveedor = '$rfcProveedor' 
        GROUP BY c.idcontrato");
    }

    function get_contrato_by_idProv($idproveedor)
    {
        return $this->db->query("SELECT c.idcontrato,c.nombre AS nombre_contrato,
        ( c.cantidad - ifnull(sum(s.cantidad),0) ) AS residuo 
        FROM contratos c 
        INNER JOIN usuarios u ON u.idusuario = c.idcrea
        LEFT JOIN sol_contrato sc ON sc.idcontrato = c.idcontrato
        LEFT JOIN solpagos s ON s.idsolicitud = sc.idsolicitud 
        WHERE (s.idetapa NOT IN (0, 30) OR idsol_contrato IS NULL ) 
        AND c.rfc_proveedor IN ( SELECT rfc FROM proveedores WHERE idproveedor = '$idproveedor' )
        GROUP BY c.idcontrato");

        /*
        return $this->db->query("SELECT 
        contratos.idcontrato,
        contratos.nombre AS nombre_contrato
        FROM contratos
        WHERE contratos.idproveedor = '$id_proveedor'");
        AND c.idproveedor = $data[id_proveedor]
        */
    }

    function get_exist_sol_contrato($idsolicitud)
    {
        return $this->db->query("SELECT idsol_contrato,idcontrato FROM sol_contrato where idsolicitud = '$idsolicitud'");
    }

    function get_residuo_contrato($idcontrato)
    {
        return $this->db->query("SELECT 
        ( c.cantidad - ifnull(sum(s.cantidad),0) ) AS residuo, c.nombre as contrato  
        FROM contratos c 
        INNER JOIN usuarios u ON u.idusuario = c.idcrea
        LEFT JOIN sol_contrato sc ON sc.idcontrato = c.idcontrato
        LEFT JOIN solpagos s ON s.idsolicitud = sc.idsolicitud 
        WHERE (s.idetapa NOT IN (0, 30) OR idsol_contrato IS NULL ) 
        AND c.idcontrato = $idcontrato
        GROUP BY c.idcontrato");
    }

    function get_fact_actualizar()
    {
        //return $this->db->query("SELECT * FROM solpagos INNER JOIN facturas ON solpagos.idsolicitud = facturas.idsolicitud WHERE (solpagos.caja_chica = 1 OR facturas.tipo_factura = 1) ORDER BY solpagos.fechaCreacion");
        return $this->db->query("SELECT * FROM solpagos INNER JOIN facturas ON solpagos.idsolicitud = facturas.idsolicitud WHERE (solpagos.caja_chica = 1 OR facturas.tipo_factura = 1)
        AND (facturas.subtotal IS NULL AND facturas.descuento IS NULL AND facturas.impuestot IS NULL AND facturas.tasacuotat IS NULL AND facturas.importet IS NULL
        AND facturas.impuestor IS NULL AND facturas.tasacuotar IS NULL AND facturas.importer IS NULL)
         ORDER BY solpagos.fechaCreacion");
    }

    //REVISION EN EL CATALOGO DE PRODUCTOS DE COMPRAS PARA SU VISTO BUENO.
    function pasaxcompras($Productos)
    {
        if ($this->db->query("SELECT * FROM productos_departamento pd INNER JOIN departamentos dp ON pd.iddepartamentos = dp.iddepartamentos WHERE dp.departamento = '" . $this->session->userdata("inicio_sesion")['depto'] . "'")->num_rows() > 0)
            return $this->db->query("SELECT * 
            FROM productosxprov pp
            INNER JOIN productos_departamento pd ON pd.idproducto = pp.id
            INNER JOIN departamentos dp ON pd.iddepartamentos = dp.iddepartamentos
            WHERE dp.departamento = '" . $this->session->userdata("inicio_sesion")['depto'] . "' AND '" . str_replace("'", "", $Productos) . "' LIKE CONCAT('%', producto, '%')")->num_rows() > 0 ? 1 : NULL;
        else
            return $this->db->query("SELECT * FROM productosxprov WHERE '" . str_replace("'", "", $Productos) . "' LIKE CONCAT('%', producto, '%')")->num_rows() > 0 ? 1 : NULL;
    }

    function solamex_tablaM()
    {
        return $this->db->query("SELECT s.idsolicitud,s.proyecto,s.fechaCreacion,s.cantidad,s.fecelab,s.idEmpresa,s.idProveedor,s.idetapa,s.idusuario,s.justificacion,s.metoPago,s.moneda,s.ref_bancaria,
        e.abrev as empresa,e.rfc, p.nombre as proveedor, CONCAT(u.nombres,' ',u.apellidos) as usuario, et.nombre as etapa 
        from solpagos s JOIN empresas e on e.idempresa=s.idEmpresa join proveedores p on p.idproveedor=s.idProveedor join usuarios u on u.idusuario=s.idusuario join etapas et on et.idetapa=s.idetapa 
        where s.idetapa='14' and s.idusuario='" . $this->session->userdata("inicio_sesion")['id'] . "';");
    }
    function reg_solamexM($data)
    {
        return $this->db->insert("solpagos", $data);
    }
    function mod_solamexM($data, $where)
    {
        // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
        return $this->db->update("solpagos", $data, $where);
    }
    function get_facturasM($tipo_fact)
    {
        return $this->db->query("select s.folio,e.abrev AS empresa,p.nombre AS proveedor,f.idfactura,s.fecelab,f.total,s.moneda,s.nomdepto,f.uuid
        from facturas f join solpagos s on s.idsolicitud=f.idsolicitud 
        join empresas e on e.idempresa=s.idEmpresa JOIN proveedores p ON p.idproveedor=s.idProveedor
        where f.tipo_factura='$tipo_fact';");
    }

    function cancel_facturaM($post)
    {
        // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
        $idfactura = $post["idfactura"];
        $tipo_fact = $post["tipo_factura"];
        $idsolicitud = $this->db->query("select idsolicitud from facturas where idfactura='$idfactura' and tipo_factura='$tipo_fact';")->row()->idsolicitud;
        $query = $this->db->query("update facturas set tipo_factura=0 where idfactura='$idfactura' and tipo_factura='$tipo_fact';");
        if ($query && $tipo_fact == 1) {
            log_sistema($this->session->userdata("inicio_sesion")['id'], $idsolicitud, "HA CANCELADO LA FACTURA GLOBAL DE LA SOLICITUD");
        }
        if ($query) {
            if ($tipo_fact == 1) {
                $this->db->query("update autpagos a INNER JOIN facturas f ON f.idfactura = a.idfactura set a.idfactura=null, f.tipo_factura = 0 where a.idsolicitud=$idsolicitud;");
                log_sistema($this->session->userdata("inicio_sesion")['id'], $idsolicitud, "HA CANCELADO LOS COMPLEMENTOS DE LA SOLICITUD");
            } elseif ($tipo_fact == 2) {
                $this->db->query("update autpagos set idfactura=null where idfactura=$idfactura;");
                log_sistema($this->session->userdata("inicio_sesion")['id'], $idsolicitud, "HA CANCELADO UNA FACTURA COMPLEMENTO");
            } else
                log_sistema($this->session->userdata("inicio_sesion")['id'], $idsolicitud, "HA CANCELADO LA FACTURA DE LA SOLICITUD");
            $query = $this->db->query("update solpagos set idetapa=10 where idsolicitud=$idsolicitud and idetapa=11;");
            chat($idsolicitud, $post["justificacion"], $this->session->userdata("inicio_sesion")['id']);
        }

        return $query;
    }

    function getInfoOC($folioOc)
    {
        return $this->db->query("SELECT s.idsolicitud, s.orden_compra, f.uuid, f.total, s.fechaCreacion FROM solpagos s
        INNER JOIN (SELECT * FROM facturas WHERE tipo_factura = 1) f ON f.idsolicitud = s.idsolicitud
        WHERE orden_compra = ?", [$folioOc]);
    }

    function insertPdfSol($data)
    {
        return $this->db->insert("historial_documento", $data);
    }

    function insertPdfSol_otros($data)
    {
        if ($this->db->insert("historial_documento", $data))
            return $this->db->insert_id();
    }

    // function deleteRequestSol($idsolicitud){
    //     return $this->db->query("DELETE FROM historial_documento WHERE idSolicitud = ?", [$idsolicitud]);
    // }

    function updateHistorialDocumento($data, $idsolicitud, $tipo_doc)
    {
        return $this->db->update("historial_documento", $data, "idSolicitud = $idsolicitud AND tipo_doc = $tipo_doc");
    }
    function getInfoRequestSol($idsolicitud, $isInfoDoc = false, $tipoDoc = "")
    {
        if ($isInfoDoc) {
            $select = "hd.*, sp.proyecto, sp.fecelab";
            $innerJoin = "INNER JOIN solpagos as sp ON hd.idSolicitud = sp.idsolicitud";
        } else {
            $select = "hd.*";
            $innerJoin = "";
        }
        return $this->db->query(
            "SELECT $select 
                                 FROM historial_documento AS hd $innerJoin 
                                 WHERE hd.idSolicitud = ? AND  hd.estatus = 1"
                . ($tipoDoc !== '' ? " AND hd.tipo_doc = $tipoDoc" : ""),
            [$idsolicitud]
        );
    }

    function getReporteFinanzas()
    {
        //reporte sólo para Azucena Perez
        $query = $this->db->query("SELECT emp.abrev AS EMPRESA,
                                          IFNULL(sp.proyecto, IFNULL(pd.nombre, 'NA')) AS PROYECTO,
                                          SUBSTRING(fac.uuid, 32, 5)  AS FOLIO_FISCAL, 
                                          sp.folio as FOLIO,
                                          fac.fecfac as FECHAFACTURA,
		                                  pr.nombre AS PROVEEDOR,
                                          fac.subtotal AS SUB_TOTAL,
                                          fac.tasatras16 AS IVA,
                                          fac.total AS PRECIO_TOTAL,
                                          sp.moneda AS MONEDA,
                                          fac.descripcion AS DESCFACTURA,
                                          fac.uuid AS UUID,
                                          sp.justificacion AS JUSTIFICACION
                                    FROM facturas fac
                                    INNER JOIN solpagos sp
                                        ON fac.idsolicitud = sp.idsolicitud
                                    INNER JOIN empresas emp
                                        ON emp.idempresa = sp.idempresa
                                    INNER JOIN proveedores pr
                                        ON pr.idproveedor = sp.idproveedor
                                    LEFT JOIN solicitud_proyecto_oficina spo 
                                        ON sp.idsolicitud =  spo.idsolicitud
                                    LEFT JOIN proyectos_departamentos pd
                                        ON spo.idProyectos = pd.idProyectos
                                    LEFT JOIN oficina_sede os 
                                        ON spo.idOficina = os.idOficina
                                    LEFT JOIN tipo_servicio_partidas tsp
                                        ON spo.idTipoServicioPartida = tsp.idTipoServicioPartida
                                    WHERE sp.idusuario=8 ORDER BY fac.fecfac DESC;");
        /*WHERE fac.fecfac BETWEEN '2023-04-01 00:00:00' AND '2023-09-30 23:59:59' */
        return $query->result_array();
    }
    function getDocsRequestSol($idsolicitud)
    {
        return $this->db->query("SELECT 
            ifnull(sp.proyecto, pd.nombre) proyecto,
            sp.idetapa,
            sp.idusuario,
            -- CASE
            -- 	WHEN  sp.caja_chica = 0 AND  sp.idProveedor = 2936 THEN 'S'
            -- 	ELSE 'N'
            -- END archivoProveedor,
            -- CASE
            --     WHEN  sp.caja_chica = 1 AND sp.cantidad >= 5000 THEN 'S'
            --     ELSE 'N'
            -- END AS archivoCajaChica,
            CASE
                WHEN  sp.nomdepto = 'COMERCIALIZACION' THEN 'S'
                ELSE 'N'
            END AS archivoComercializacion,
            case 
                when sp.caja_chica = 4 then (select obtenerArchivoViaticos($idsolicitud))
            end archivoViaticos
            from solpagos sp
            left join solicitud_proyecto_oficina spo on sp.idsolicitud = spo.idsolicitud 
            left join proyectos_departamentos pd ON spo.idProyectos = pd.idProyectos 
            WHERE sp.idsolicitud = ?", [$idsolicitud]);
    }

    function updateDertamentoSolicitante($data, $idsolicitud)
    {
        $this->db->update("departamentoSolicitante", $data, "idsolicitud = '$idsolicitud'");
    }

    function updateProyectoOficina($data, $idsolicitud)
    {
        // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
        $this->db->update("solicitud_proyecto_oficina", $data, "idsolicitud = '$idsolicitud'");
    }

    function updateSolicitudEstados($data, $idsolicitud)
    {
        $this->db->update("solicitud_estados", $data, "idsolicitud = '$idsolicitud'");
    }

    function selectSolicitudEstados($idsolicitud)
    {
        return $this->db->query("SELECT * from solicitud_estados where idsolicitud = $idsolicitud");
    }
    function selectProyectoOficina($idsolicitud)
    {
        return $this->db->query("SELECT * from solicitud_proyecto_oficina where idsolicitud = $idsolicitud");
    }

    function updateDocumentoAnteriorReemplazar($data, $idSolicitud, $idPDF, $tipoDocumento)
    {
        return $this->db->update("historial_documento", $data, "idSolicitud = $idSolicitud AND idDocumento = $idPDF AND tipo_doc = $tipoDocumento");
    }

    function borrarDocumentoReemplazar($idSolicitud, $tipoDocumento, $idPDF, $nameFileOld)
    {
        $ruta_pdf = './UPLOADS/PDFS/' . $nameFileOld;
        $data = array("modificado" => date('Y-m-d H:i:s'), "estatus" => 0, "movimiento" => "Se elimino documento PDF");

        unlink($ruta_pdf);
        $this->updateDocumentoAnteriorReemplazar($data, $idSolicitud, $idPDF, $tipoDocumento);
    }
}
