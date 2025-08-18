<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ArchivoBan extends CI_Model {

    function __construct(){
        parent::__construct();
    }

    function pabanco($emp, $filtro){
        /*
        switch( $filtro ){
            case 1:
                return $this->db->query("SELECT solpagos.intereses, solpagos.ref_bancaria, solpagos.moneda, autpagos.tipoCambio, autpagos.interes, autpagos.idpago, empresas.idempresa, solpagos.idsolicitud,solpagos.idProveedor,solpagos.idproveedor, proveedores.email,proveedores.rfc, proveedores.tipocta, proveedores.cuenta, proveedores.nombre as prov, bancos.clvbanco,bancos.nombre,  solpagos.folio AS descri, solpagos.cantidad as total, proveedores.alias, autpagos.cantidad, empresas.abrev FROM solpagos INNER JOIN empresas ON empresas.idempresa=solpagos.idEmpresa INNER JOIN proveedores ON proveedores.idproveedor=solpagos.idProveedor LEFT JOIN bancos ON bancos.idbanco=proveedores.idbanco INNER JOIN autpagos ON autpagos.idsolicitud=solpagos.idsolicitud INNER JOIN usuarios ON solpagos.idusuario=usuarios.idusuario WHERE  proveedores.cuenta is not null AND solpagos.programado IS NULL AND autpagos.estatus IN (0,11) AND IFNULL( autpagos.tipoPago, solpagos.metoPago ) LIKE 'TEA' AND ((autpagos.tipoCambio <> '' AND solpagos.moneda != 'MXN') OR (solpagos.moneda = 'MXN' AND solpagos.intereses IN (1) AND autpagos.interes>0 ) OR (solpagos.moneda = 'MXN' AND solpagos.intereses is null OR solpagos.intereses!='1')) AND  solpagos.idempresa = $emp AND autpagos.fecha_pago IS NULL AND solpagos.nomdepto='CONSTRUCCION'");
                break;
            case 2:
                return $this->db->query("SELECT solpagos.intereses, solpagos.ref_bancaria, solpagos.moneda, autpagos.tipoCambio, autpagos.interes, autpagos.idpago, empresas.idempresa, solpagos.idsolicitud,solpagos.idProveedor,solpagos.idproveedor, proveedores.email,proveedores.rfc, proveedores.tipocta, proveedores.cuenta, proveedores.nombre as prov, bancos.clvbanco,bancos.nombre,  solpagos.folio AS descri, solpagos.cantidad as total, proveedores.alias, autpagos.cantidad, empresas.abrev FROM solpagos INNER JOIN empresas ON empresas.idempresa=solpagos.idEmpresa INNER JOIN proveedores ON proveedores.idproveedor=solpagos.idProveedor LEFT JOIN bancos ON bancos.idbanco=proveedores.idbanco INNER JOIN autpagos ON autpagos.idsolicitud=solpagos.idsolicitud INNER JOIN usuarios ON solpagos.idusuario=usuarios.idusuario WHERE  proveedores.cuenta is not null AND solpagos.programado IS NULL AND  autpagos.estatus IN (0,11) AND IFNULL( autpagos.tipoPago, solpagos.metoPago ) LIKE 'TEA' AND ((autpagos.tipoCambio <> '' AND solpagos.moneda != 'MXN') OR (solpagos.moneda = 'MXN' AND solpagos.intereses IN (1) AND autpagos.interes>0 ) OR (solpagos.moneda = 'MXN' AND solpagos.intereses is null OR solpagos.intereses!='1')) AND solpagos.idempresa = $emp AND autpagos.fecha_pago IS NULL AND solpagos.nomdepto<>'CONSTRUCCION'");
                break;
            default:
                return $this->db->query("SELECT solpagos.intereses, solpagos.ref_bancaria, solpagos.moneda, autpagos.tipoCambio, autpagos.interes, autpagos.idpago, empresas.idempresa, solpagos.idsolicitud,solpagos.idProveedor,solpagos.idproveedor, proveedores.email,proveedores.rfc, proveedores.tipocta, proveedores.cuenta, proveedores.nombre as prov, bancos.clvbanco,bancos.nombre,  solpagos.folio AS descri, solpagos.cantidad as total, proveedores.alias, autpagos.cantidad, empresas.abrev FROM solpagos INNER JOIN empresas ON empresas.idempresa=solpagos.idEmpresa INNER JOIN proveedores ON proveedores.idproveedor=solpagos.idProveedor LEFT JOIN bancos ON bancos.idbanco=proveedores.idbanco INNER JOIN autpagos ON autpagos.idsolicitud=solpagos.idsolicitud INNER JOIN usuarios ON solpagos.idusuario=usuarios.idusuario WHERE  proveedores.cuenta is not null AND  solpagos.programado IS NULL AND  autpagos.estatus IN (0,11) AND IFNULL( autpagos.tipoPago, solpagos.metoPago ) LIKE 'TEA' AND ((autpagos.tipoCambio <> '' AND solpagos.moneda != 'MXN') OR (solpagos.moneda = 'MXN' AND solpagos.intereses IN (1) AND autpagos.interes>0 ) OR (solpagos.moneda = 'MXN' AND solpagos.intereses is null OR solpagos.intereses!='1')) AND solpagos.idempresa = $emp AND autpagos.fecha_pago IS NULL");
                break;
        }*/

        $filtro_query = "";
        switch ( $filtro ){
            case 1:
                $filtro_query = " AND solpagos.nomdepto = 'CONSTRUCCION'";
                break;
            case 2:
                $filtro_query = " AND solpagos.nomdepto <> 'CONSTRUCCION'";
                break;
        }

        $query = "SELECT 
            solpagos.intereses,
            solpagos.ref_bancaria,
            solpagos.moneda,
            autpagos.tipoCambio,
            autpagos.interes,
            autpagos.idpago,
            empresas.idempresa,
            solpagos.idsolicitud,
            solpagos.idProveedor,
            solpagos.idproveedor,
            proveedores.email,
            proveedores.rfc,
            proveedores.tipocta,
            proveedores.cuenta,
            proveedores.nombre AS prov,
            bancos.clvbanco,
            bancos.nombre,
            solpagos.folio AS descri,
            solpagos.cantidad AS total,
            proveedores.alias,
            autpagos.cantidad,
            empresas.abrev
        FROM solpagos
        LEFT JOIN autpagos ON autpagos.idsolicitud = solpagos.idsolicitud 
        INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor AND proveedores.cuenta IS NOT NULL
        INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa
        LEFT JOIN bancos ON bancos.idbanco = proveedores.idbanco
        INNER JOIN usuarios ON solpagos.idusuario = usuarios.idusuario
        WHERE solpagos.idetapa IN ( 9, 10, 11, 80, 81 ) 
            AND solpagos.idempresa = $emp
            $filtro_query
            AND (autpagos.tipoCambio <> '' AND solpagos.moneda != 'MXN' 
                OR ( solpagos.moneda = 'MXN' AND ( ( solpagos.intereses IN ( 1 ) AND autpagos.interes > 0 ) OR ( solpagos.intereses IS NULL OR solpagos.intereses != 1 ) ) ))
            AND ((solpagos.programado IS NULL 
                    AND IFNULL(autpagos.tipoPago, solpagos.metoPago) LIKE 'TEA' 
                    AND autpagos.estatus IN ( 0, 11 ) 
                    AND autpagos.fecha_pago IS NULL) 
                OR (solpagos.programado IS NOT NULL 
                    AND solpagos.idproceso = 30
                    AND IFNULL(autpagos.tipoPago, solpagos.metoPago) LIKE 'TEA' 
                    AND autpagos.estatus IN ( 0, 11 ) 
                    AND autpagos.fecha_pago IS NULL));";

        return $this->db->query( $query );

    }


    function pabanco_facturaje($emp, $filtro){

        if (($filtro!=""||$filtro!=NULL) && $filtro == 1) {
             return $query = $this->db->query("SELECT  solpagos.ref_bancaria, solpagos.moneda, autpagos.tipoCambio, autpagos.idpago, empresas.idempresa, solpagos.idsolicitud,solpagos.idProveedor,solpagos.idproveedor, proveedores.email,proveedores.rfc, proveedores.tipocta, proveedores.cuenta, proveedores.nombre as prov, bancos.clvbanco,bancos.nombre,  solpagos.folio AS descri, solpagos.cantidad as total, proveedores.alias, autpagos.cantidad, empresas.abrev FROM solpagos INNER JOIN empresas ON empresas.idempresa=solpagos.idEmpresa INNER JOIN proveedores ON proveedores.idproveedor=solpagos.idProveedor INNER JOIN bancos ON bancos.idbanco=proveedores.idbanco INNER JOIN autpagos ON autpagos.idsolicitud=solpagos.idsolicitud INNER JOIN usuarios ON solpagos.idusuario=usuarios.idusuario WHERE  proveedores.cuenta is not null AND  solpagos.programado IS NULL AND  autpagos.estatus IN (0,11)  AND (solpagos.metoPago LIKE 'FACT BAJIO' or solpagos.metoPago LIKE 'FACT BANREGIO') AND ((autpagos.tipoCambio <> '' AND solpagos.moneda != 'MXN') OR (solpagos.moneda = 'MXN')) AND  solpagos.idempresa = ".$emp." AND autpagos.fecha_pago IS NULL AND solpagos.nomdepto='CONSTRUCCION'");
        }

        if (($filtro!=""||$filtro!=NULL) && $filtro == 2) {
             return $query = $this->db->query("SELECT  solpagos.ref_bancaria, solpagos.moneda, autpagos.tipoCambio, autpagos.idpago, empresas.idempresa, solpagos.idsolicitud,solpagos.idProveedor,solpagos.idproveedor, proveedores.email,proveedores.rfc, proveedores.tipocta, proveedores.cuenta, proveedores.nombre as prov, bancos.clvbanco,bancos.nombre,  solpagos.folio AS descri, solpagos.cantidad as total, proveedores.alias, autpagos.cantidad, empresas.abrev FROM solpagos INNER JOIN empresas ON empresas.idempresa=solpagos.idEmpresa INNER JOIN proveedores ON proveedores.idproveedor=solpagos.idProveedor INNER JOIN bancos ON bancos.idbanco=proveedores.idbanco INNER JOIN autpagos ON autpagos.idsolicitud=solpagos.idsolicitud INNER JOIN usuarios ON solpagos.idusuario=usuarios.idusuario WHERE   proveedores.cuenta is not null AND  solpagos.programado IS NULL AND  autpagos.estatus IN (0,11)  AND (solpagos.metoPago LIKE 'FACT BAJIO' or solpagos.metoPago LIKE 'FACT BANREGIO')  AND ((autpagos.tipoCambio <> '' AND solpagos.moneda != 'MXN') OR (solpagos.moneda = 'MXN')) AND solpagos.idempresa = ".$emp." AND autpagos.fecha_pago IS NULL AND solpagos.nomdepto<>'CONSTRUCCION'");
        }

         if (($filtro!=""||$filtro!=NULL) && $filtro == 0) {
            return $query = $this->db->query("SELECT  solpagos.ref_bancaria, solpagos.metoPago, solpagos.moneda, autpagos.tipoCambio, autpagos.idpago, empresas.idempresa, solpagos.idsolicitud,solpagos.idProveedor,solpagos.idproveedor, proveedores.email,proveedores.rfc, proveedores.tipocta, proveedores.cuenta, proveedores.nombre as prov, bancos.clvbanco,bancos.nombre,  solpagos.folio AS descri, solpagos.cantidad as total, proveedores.alias, autpagos.cantidad, empresas.abrev FROM solpagos INNER JOIN empresas ON empresas.idempresa=solpagos.idEmpresa INNER JOIN proveedores ON proveedores.idproveedor=solpagos.idProveedor LEFT JOIN bancos ON bancos.idbanco=proveedores.idbanco INNER JOIN autpagos ON autpagos.idsolicitud=solpagos.idsolicitud INNER JOIN usuarios ON solpagos.idusuario=usuarios.idusuario WHERE  proveedores.cuenta is not null AND  solpagos.programado IS NULL AND  autpagos.estatus IN (0,11)  AND (solpagos.metoPago LIKE 'FACT BAJIO' or solpagos.metoPago LIKE 'FACT BANREGIO')  AND ((autpagos.tipoCambio <> '' AND solpagos.moneda != 'MXN') OR (solpagos.moneda = 'MXN')) AND solpagos.idempresa = ".$emp." AND autpagos.fecha_pago IS NULL ");
        }
          
    }

    //OBTENEMOS LA INFORMACION DE LOS REGISTROS DE CAJA CHICA A PROCESAR
    function pabanco_chica($emp, $metodo, $datos_procesar){
        return $this->db->query("SELECT 
        ap.referencia, 
        IF( ap.nomdepto = 'TARJETA CREDITO', pr.nombre, u.nombre_completo ) usher, 
        ap.idpago, 
        ap.idempresa, 
        'MXN' moneda, 
        '' interes, 
        IF( ap.nomdepto = 'TARJETA CREDITO', 'TARJETA ', 'CAJA CHICA' ) ref_bancaria, 
        ap.idproveedor,
        pr.email,
        pr.rfc, 
        pr.tipocta, 
        pr.nombre as prov, 
        pr.cuenta, 
        pr.alias,  
        b.clvbanco, 
        b.nombre, 
        ap.cantidad,
        em.abrev, 
        ap.tipoPago 
        FROM autpagos_caja_chica AS ap 
        LEFT JOIN listado_usuarios as u ON ap.idResponsable = u.idusuario
        LEFT JOIN proveedores as pr ON ap.idproveedor = pr.idproveedor 
        INNER JOIN empresas AS em ON ap.idEmpresa = em.idempresa 
        LEFT JOIN bancos as b ON b.idbanco = pr.idbanco 
        WHERE ap.estatus IN( 1, 2 ) 
        AND ap.tipoPago = '$metodo' 
        AND ap.idEmpresa = $emp".( $datos_procesar == "pagos_autoriza_dg_tdc" ? " AND ap.nomdepto = 'TARJETA CREDITO'" : " AND ap.nomdepto != 'TARJETA CREDITO'" ) );
    }

    //OBTENEMOS LA INFORMACION DE LOS REGISTROS DE VIATICOS A PROCESAR
    function pabanco_viaticos($emp, $metodo, $datos_procesar){
        return $this->db->query("SELECT 
        ap.referencia, 
        IF( ap.nomdepto = 'TARJETA CREDITO', pr.nombre, u.nombre_completo ) usher, 
        ap.idpago, 
        ap.idempresa, 
        'MXN' moneda, 
        '' interes, 
        'VIATICOS' ref_bancaria, 
        ap.idproveedor,
        pr.email,
        pr.rfc, 
        pr.tipocta, 
        pr.nombre as prov, 
        pr.cuenta, 
        pr.alias,  
        b.clvbanco, 
        b.nombre, 
        ap.cantidad,
        em.abrev, 
        ap.tipoPago 
        FROM autpagos_caja_chica AS ap 
        INNER JOIN solpagos AS sp ON sp.idsolicitud IN (ap.idsolicitud) AND sp.caja_chica = 4
        LEFT JOIN listado_usuarios as u ON ap.idResponsable = u.idusuario
        LEFT JOIN proveedores as pr ON ap.idproveedor = pr.idproveedor 
        INNER JOIN empresas AS em ON ap.idEmpresa = em.idempresa 
        LEFT JOIN bancos as b ON b.idbanco = pr.idbanco 
        WHERE ap.estatus IN( 1, 2 ) 
        AND ap.tipoPago = '$metodo' 
        AND ap.idEmpresa = $emp".( $datos_procesar == "pagos_autoriza_dg_tdc" ? " AND ap.nomdepto = 'TARJETA CREDITO'" : " AND ap.nomdepto != 'TARJETA CREDITO'" ) );
    }

    //MOVEMOS EL REGISTRO DE LA CAJA CHICA AL ESTATUS QUE REQUERIMOS
    //estatus_original = Indicamos desde que estatus debe brincar la caja chica Ejemp de estatus 1 al 2
    function update_estatus_cajachica($estatus, $metodoPago, $empresa, $estatus_original){
        return $this->db->update("autpagos_caja_chica",
        array( "estatus" => $estatus ), "tipoPago IN ( $metodoPago ) AND idEmpresa = '$empresa' AND estatus IN ( $estatus_original )");
    }

    function pabanco_program($emp, $metodo){
        return $this->db->query("SELECT solpagos.intereses, 
                                        solpagos.ref_bancaria,
                                        solpagos.moneda,
                                        autpagos.tipoCambio,
                                        autpagos.interes,
                                        autpagos.idpago,
                                        empresas.idempresa,
                                        solpagos.idsolicitud,
                                        solpagos.idProveedor,
                                        solpagos.idproveedor,
                                        proveedores.email,
                                        proveedores.rfc,
                                        proveedores.tipocta,
                                        proveedores.cuenta,
                                        proveedores.nombre as prov,
                                        bancos.clvbanco,bancos.nombre,
                                        solpagos.folio AS descri,
                                        solpagos.cantidad as total,
                                        proveedores.alias,
                                        autpagos.cantidad,
                                        empresas.abrev 
                                FROM solpagos 
                                INNER JOIN empresas 
                                    ON empresas.idempresa=solpagos.idEmpresa 
                                INNER JOIN proveedores 
                                    ON proveedores.idproveedor=solpagos.idProveedor 
                                LEFT JOIN bancos 
                                    ON bancos.idbanco=proveedores.idbanco 
                                INNER JOIN autpagos 
                                    ON autpagos.idsolicitud=solpagos.idsolicitud
                                INNER JOIN usuarios 
                                    ON solpagos.idusuario=usuarios.idusuario 
                                WHERE   proveedores.cuenta is not null AND
                                        solpagos.programado != '' AND 
                                        autpagos.estatus IN (0,11) AND 
                                        solpagos.metoPago LIKE 'TEA' AND 
                                        solpagos.idproceso IS NULL AND -- @author DANTE ALDAIR GRO. ALDANA | CONDICION PARA NO TRAER SOLICITUDES DE DEVOLUCIONES POR PARCIALIDADES
                                        (   (autpagos.tipoCambio <> '' AND solpagos.moneda != 'MXN') OR 
                                            (solpagos.moneda = 'MXN' AND solpagos.intereses IN (1) AND autpagos.interes>0 ) OR 
                                            (solpagos.moneda = 'MXN' AND solpagos.intereses is null OR solpagos.intereses!='1')) AND 
                                        solpagos.idempresa = ".$emp." AND autpagos.fecha_pago IS NULL");
    }

    function pabanco_PROGRAM_cheques($emp, $metodo){
        return $this->db->query("SELECT s.folio as descri,
                                        ap.interes,
										s.ref_bancaria,
										s.intereses,
										s.moneda,
										s.metoPago,
										ap.referencia,
										ap.tipoCambio,
										ap.cantidad,
										em.abrev
                                FROM autpagos AS ap 
                                INNER JOIN solpagos AS s 
                                    ON s.idsolicitud = ap.idsolicitud
                                INNER JOIN empresas AS em
                                    ON em.idempresa = s.idEmpresa
                                WHERE   s.programado != '' AND 
                                        ap.estatus IN ( 0, 11, 13 ) AND
                                        ap.tipoPago IN ( 'ECHQ', 'EFEC' ) AND
                                        ap.referencia!='' AND
                                        s.idEmpresa = ".$emp);
    }

    function pabanco_chica_cheques($emp, $metodo, $datos_procesar){
        return $this->db->query( "SELECT ap.referencia, ap.cantidad, em.abrev, 'MXN' moneda, '' interes, ap.referencia ref_bancaria FROM autpagos_caja_chica AS ap INNER JOIN empresas AS em ON ap.idEmpresa=em.idempresa 
        WHERE ap.estatus IN ( 1, 2 ) AND ( ap.tipoPago IN ( 'ECHQ', 'EFEC' ) ) AND ap.idempresa = $emp".( $datos_procesar == "pagos_autoriza_dg_tdc" ? " AND ap.nomdepto = 'TARJETA CREDITO'" : " AND ap.nomdepto != 'TARJETA CREDITO'" ));
    }

    function pabanco_otros_cheques( $emp ){
        //return $this->db->query("SELECT ap.interes, s.intereses, s.moneda, s.metoPago, ap.referencia, ap.tipoCambio, ap.cantidad, em.abrev FROM autpagos AS ap INNER JOIN solpagos AS s ON s.idsolicitud = ap.idsolicitud INNER JOIN empresas AS em ON em.idempresa = s.idEmpresa WHERE s.programado IS NULL AND ap.estatus IN( 11, 13 ) AND ap.tipoPago IN ( 'ECHQ', 'EFEC' ) AND ap.referencia !='' AND s.idEmpresa = $emp");
        return $this->db->query("SELECT 
                ap.interes,
                s.intereses,
                s.moneda,
                s.metoPago,
                ap.referencia,
                ap.tipoCambio,
                ap.cantidad,
                em.abrev
            FROM autpagos ap
            INNER JOIN solpagos s on s.idsolicitud = ap.idsolicitud
            INNER JOIN empresas AS em ON em.idempresa = s.idEmpresa
            WHERE ( (s.programado IN ( '', 0 ) 
                OR s.programado IS NULL )
                OR (s.programado is not null 
                AND s.idproceso = 30 ))
                AND	s.idetapa IN ( 9, 10, 11, 80, 81 )
                AND s.idEmpresa = $emp
                AND ap.estatus IN (0, 11 , 13) 
                AND (ap.tipoPago IN ('ECHQ' , 'EFEC', 'MAN') 
                    OR ap.formaPago IN ('ECHQ' , 'EFEC', 'MAN'))
                AND ap.referencia != ''");
    }
    
    function pabanco_otros($emp, $filtro){
        return $this->db->query("SELECT  solpagos.ref_bancaria, autpagos.idpago, autpagos.tipoCambio, solpagos.moneda, solpagos.idEmpresa, solpagos.idsolicitud,solpagos.idProveedor,solpagos.idproveedor, proveedores.email,proveedores.rfc, proveedores.tipocta, proveedores.nombre as prov, proveedores.cuenta,  facturas.foliofac  AS descri, facturas.total, autpagos.cantidad, empresas.abrev FROM solpagos inner JOIN empresas ON empresas.idempresa=solpagos.idEmpresa inner JOIN proveedores ON proveedores.idproveedor=solpagos.idProveedor LEFT JOIN (select *, MIN(facturas.feccrea) from facturas where facturas.tipo_factura in (1, 3) GROUP BY facturas.idsolicitud) as facturas ON solpagos.idsolicitud = facturas.idsolicitud INNER JOIN autpagos ON autpagos.idsolicitud=solpagos.idsolicitud WHERE solpagos.programado IS NULL AND solpagos.idEmpresa = ".$emp." AND (autpagos.estatus IN (0, 11) AND IFNULL( autpagos.tipoPago, solpagos.metoPago ) IN ('MAN', 'DOMIC', 'EFEC') OR autpagos.estatus IN ( 11, 13 ) AND IFNULL( autpagos.tipoPago, solpagos.metoPago ) = 'ECHQ') AND(solpagos.moneda = 'MXN' OR solpagos.moneda<>'MXN' AND autpagos.tipoCambio IS NOT NULL)");
    }

 function updatePagosTxtFact($emp, $cta,$filtro){
     // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
     $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);

     if (($filtro!=""||$filtro!=NULL) && $filtro == 1) {
        return $queryupdate = $this->db->query("UPDATE autpagos AP 
            INNER JOIN solpagos SP ON AP.idsolicitud = SP.idsolicitud  
            INNER JOIN empresas EMP ON EMP.idempresa=SP.idEmpresa 
            INNER JOIN proveedores PV  ON SP.idProveedor = PV.idproveedor
            INNER JOIN bancos BC  ON PV.idbanco = BC.idbanco
            INNER JOIN usuarios US ON SP.idusuario=US.idusuario 
            SET AP.estatus = 11, AP.descarga = 1 , AP.fechDesc = date('Y-m-d H:i:s') 
            WHERE  AP.estatus=0 
            AND PV.cuenta is not null
            AND SP.programado IS NULL
            AND (SP.metoPago LIKE 'FACT BAJIO' OR SP.metoPago LIKE 'FACT BANREGIO')
            AND ((AP.tipoCambio <> '' AND ( SP.moneda IN ( 'EUR', 'USD', 'CAD', 'MXV' ) ) ) OR (SP.moneda = 'MXN')) 
            AND PV.idbanco IS NOT NULL
            AND  SP.idEmpresa = ".$emp."
            AND AP.fecha_pago IS NULL
            AND SP.nomdepto='CONSTRUCCION'");
            
        }

        if (($filtro!=""||$filtro!=NULL) && $filtro == 2) {
            return $queryupdate = $this->db->query("UPDATE autpagos AP 
            INNER JOIN solpagos SP ON AP.idsolicitud = SP.idsolicitud  
            INNER JOIN empresas EMP ON EMP.idempresa=SP.idEmpresa 
            INNER JOIN proveedores PV  ON SP.idProveedor = PV.idproveedor
            INNER JOIN bancos BC  ON PV.idbanco = BC.idbanco
            INNER JOIN usuarios US ON SP.idusuario=US.idusuario 
            SET AP.estatus = 11, AP.descarga = 1 , AP.fechDesc = date('Y-m-d H:i:s') 
            WHERE  AP.estatus=0 
            AND PV.cuenta is not null
            AND SP.programado IS NULL
            AND (SP.metoPago LIKE 'FACT BAJIO' OR SP.metoPago LIKE 'FACT BANREGIO')
            AND ( ( AP.tipoCambio <> '' AND  ( SP.moneda IN ( 'EUR', 'USD', 'CAD', 'MXV' ) ) ) OR (SP.moneda = 'MXN')) 
            AND PV.idbanco IS NOT NULL
            AND  SP.idEmpresa = ".$emp."
            AND AP.fecha_pago IS NULL
            AND SP.nomdepto<>'CONSTRUCCION'");
          
        }


         if (($filtro!=""||$filtro!=NULL) && $filtro == 0) {

            return $queryupdate = $this->db->query("UPDATE autpagos AP 
            INNER JOIN solpagos SP ON AP.idsolicitud = SP.idsolicitud  
            INNER JOIN empresas EMP ON EMP.idempresa=SP.idEmpresa 
            INNER JOIN proveedores PV  ON SP.idProveedor = PV.idproveedor
            INNER JOIN bancos BC  ON PV.idbanco = BC.idbanco
            INNER JOIN usuarios US ON SP.idusuario=US.idusuario 
            SET AP.estatus = 11, AP.descarga = 1 , AP.fechDesc = date('Y-m-d H:i:s') 
            WHERE  AP.estatus=0 
            AND PV.cuenta is not null
            AND SP.programado IS NULL
            AND (SP.metoPago LIKE 'FACT BAJIO' OR SP.metoPago LIKE 'FACT BANREGIO')
            AND ((AP.tipoCambio <> '' AND  ( SP.moneda IN ( 'EUR', 'USD', 'CAD', 'MXV' ) ) ) OR (SP.moneda = 'MXN')) 
            AND PV.idbanco IS NOT NULL
            AND  SP.idEmpresa = ".$emp."
            AND AP.fecha_pago IS NULL");
     

        }
 
    }



    function updatePagosTxt($emp, $cta,$filtro){
        // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);

        if (($filtro!=""||$filtro!=NULL) && $filtro == 1) {
            return $this->db->query("UPDATE autpagos AP 
            INNER JOIN solpagos SP ON AP.idsolicitud = SP.idsolicitud  
            INNER JOIN empresas EMP ON EMP.idempresa=SP.idEmpresa 
            INNER JOIN proveedores PV  ON SP.idProveedor = PV.idproveedor
            INNER JOIN bancos BC  ON PV.idbanco = BC.idbanco
            INNER JOIN usuarios US ON SP.idusuario=US.idusuario 
            SET AP.estatus = 11, AP.descarga = 1 , AP.fechDesc = date('Y-m-d H:i:s') 
            WHERE AP.estatus IN (0,11)
            AND PV.cuenta is not null
            AND ( SP.programado IS NULL
                OR (SP.programado is not null 
                AND SP.idproceso = 30 ))
            AND IFNULL(AP.tipoPago, SP.metoPago) LIKE 'TEA'  
            AND ((AP.tipoCambio <> '' AND SP.moneda != 'MXN') OR 
            (SP.moneda = 'MXN' AND SP.intereses IN (1) AND AP.interes>0 )  OR 
            (SP.moneda = 'MXN' AND SP.intereses is null OR SP.intereses!='1'))
            AND SP.idempresa = ".$emp." 
            AND AP.fecha_pago IS NULL
            AND SP.nomdepto='CONSTRUCCION'");
            
        }

        if (($filtro!=""||$filtro!=NULL) && $filtro == 2) {
            return $this->db->query("UPDATE autpagos AP 
            INNER JOIN solpagos SP ON AP.idsolicitud = SP.idsolicitud  
            INNER JOIN empresas EMP ON EMP.idempresa=SP.idEmpresa 
            INNER JOIN proveedores PV  ON SP.idProveedor = PV.idproveedor
            INNER JOIN bancos BC  ON PV.idbanco = BC.idbanco
            INNER JOIN usuarios US ON SP.idusuario=US.idusuario 
            SET AP.estatus = 11, AP.descarga = 1 , AP.fechDesc = date('Y-m-d H:i:s') 
            WHERE AP.estatus IN (0,11)
            AND PV.cuenta is not null
            AND ( SP.programado IS NULL
                OR (SP.programado is not null 
                AND SP.idproceso = 30 ))
            AND IFNULL(AP.tipoPago, SP.metoPago) LIKE 'TEA'  
            AND ((AP.tipoCambio <> '' AND SP.moneda != 'MXN') OR 
            (SP.moneda = 'MXN' AND SP.intereses IN (1) AND AP.interes>0 )  OR 
            (SP.moneda = 'MXN' AND SP.intereses is null OR SP.intereses!='1'))
            AND SP.idempresa = ".$emp." 
            AND AP.fecha_pago IS NULL
            AND SP.nomdepto<>'CONSTRUCCION'");
        
        }

        if (($filtro!=""||$filtro!=NULL) && $filtro == 0) {

            return $this->db->query("UPDATE autpagos AP 
            INNER JOIN solpagos SP ON AP.idsolicitud = SP.idsolicitud  
            INNER JOIN empresas EMP ON EMP.idempresa=SP.idEmpresa 
            INNER JOIN proveedores PV  ON SP.idProveedor = PV.idproveedor
            INNER JOIN bancos BC  ON PV.idbanco = BC.idbanco
            INNER JOIN usuarios US ON SP.idusuario=US.idusuario 
            SET AP.estatus = 11, AP.descarga = 1 , AP.fechDesc = date('Y-m-d H:i:s') 
            WHERE AP.estatus IN (0,11)
            AND PV.cuenta is not null
            AND ( SP.programado IS NULL
                OR (SP.programado is not null 
                AND SP.idproceso = 30 ))
            AND IFNULL(AP.tipoPago, SP.metoPago) LIKE 'TEA'  
            AND ((AP.tipoCambio <> '' AND SP.moneda != 'MXN') OR 
            (SP.moneda = 'MXN' AND SP.intereses IN (1) AND AP.interes>0 )  OR 
            (SP.moneda = 'MXN' AND SP.intereses is null OR SP.intereses!='1'))
            AND SP.idempresa = ".$emp." 
            AND AP.fecha_pago IS NULL");

        }
    
    }


    function updatePagosTxtpg($emp, $cta){
        // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
        return $this->db->query("UPDATE autpagos AP 
            INNER JOIN solpagos SP ON AP.idsolicitud = SP.idsolicitud  
            INNER JOIN empresas EMP ON EMP.idempresa=SP.idEmpresa 
            INNER JOIN proveedores PV  ON SP.idProveedor = PV.idproveedor
            INNER JOIN bancos BC  ON PV.idbanco = BC.idbanco
            INNER JOIN usuarios US ON SP.idusuario=US.idusuario 
            SET AP.estatus = 11, AP.descarga = 1 , AP.fechDesc = date('Y-m-d H:i:s') 
              WHERE PV.cuenta is not null 
            AND SP.programado != '' 
            AND AP.estatus IN (0,11) 
            AND SP.metoPago LIKE 'TEA' 
            AND ((AP.tipoCambio <> '' AND SP.moneda != 'MXN') OR (SP.moneda = 'MXN' AND SP.intereses IN (1) AND AP.interes>0 ) 
            OR (SP.moneda = 'MXN' AND SP.intereses is null OR SP.intereses!='1')) 
            AND SP.idempresa = ".$emp." AND AP.fecha_pago IS NULL");
    }


    function updatePagosTxtpg2( $emp ){
        // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
        $this->db->query("UPDATE autpagos as ap INNER JOIN solpagos AS sp ON sp.idsolicitud = ap.idsolicitud SET ap.estatus = 11 WHERE sp.programado IS NOT NULL AND sp.idEmpresa = '$emp' AND ap.estatus IN( 0 ) AND ( ap.tipoPago NOT IN ( 'TEA' ) OR ap.tipoPago IS NULL )");   
    }

    function updatePagosTxt_otros($emp){
        // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
        return $this->db->query("UPDATE autpagos AP 
        INNER JOIN solpagos SP ON AP.idsolicitud = SP.idsolicitud AND SP.programado IS NULL AND SP.idetapa IN ( 9, 10, 11 ) AND AP.estatus IN ( 0, 11 ) AND SP.idempresa = $emp AND AP.fecha_pago IS NULL
        SET AP.estatus = 11 
        WHERE
        ( IFNULL( AP.tipoPago, SP.metoPago ) NOT IN ( 'ECHQ', 'TEA' ) AND SP.metoPago NOT LIKE 'FACT%' ) 
        AND ( 
                ( AP.tipoCambio <> '' AND SP.moneda != 'MXN' ) 
                OR ( SP.moneda = 'MXN' AND SP.intereses IN (1) AND AP.interes > 0 ) 
                OR ( SP.moneda = 'MXN' AND SP.intereses is null OR SP.intereses != '1' )
        )");
    }

    function updatePagosTxt_otros_DOS($emp){
        // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
        //return $this->db->query("UPDATE autpagos AP INNER JOIN solpagos SP ON AP.idsolicitud = SP.idsolicitud AND SP.idetapa IN ( 9, 10, 11 ) INNER JOIN empresas EMP ON EMP.idempresa=SP.idEmpresa SET AP.estatus = 11 WHERE SP.programado IS NULL AND  AP.estatus IN (0,11,13) AND (SP.metoPago NOT LIKE 'TEA' AND SP.metoPago NOT LIKE 'FACT%' ) AND ((AP.tipoCambio <> '' AND SP.moneda != 'MXN') OR (SP.moneda = 'MXN' AND SP.intereses IN (1) AND AP.interes>0 ) OR (SP.moneda = 'MXN' AND SP.intereses is null OR SP.intereses!='1')) AND SP.idempresa = ".$emp." AND AP.fecha_pago IS NULL");
        return $this->db->query("UPDATE 
            autpagos AP
        INNER JOIN solpagos SP ON AP.idsolicitud = SP.idsolicitud 
            SET	AP.estatus = 11
        WHERE (( AP.tipoCambio <> '' AND SP.moneda != 'MXN' )
            OR ( SP.moneda = 'MXN' AND SP.intereses IN (1) AND AP.interes > 0 )
            OR ( SP.moneda = 'MXN' AND SP.intereses IS NULL OR SP.intereses != '1' ))
            AND SP.idetapa IN (9 , 10, 11, 80, 81) 
            AND ( (SP.programado IN ( '', 0 ) 
                OR SP.programado IS NULL )
                OR (SP.programado is not null 
                AND SP.idproceso = 30 ))
            AND AP.estatus IN (0 , 11, 13) 
            AND (SP.metoPago NOT LIKE 'TEA' 
            AND SP.metoPago NOT LIKE 'FACT%') 
            AND AP.fecha_pago IS NULL 
            AND SP.idempresa = $emp;");
    }

     function updatePagosTxt_CHICA($empresa){
        return $this->db->query("UPDATE autpagos_caja_chica SET estatus =2, descarga = 1, fechDesc = date('Y-m-d H:i:s') WHERE (estatus = 1 or estatus = 0 and formaPago like '%EFEC%') AND idEmpresa = ".$empresa);
    }

    function PDF_UPDATETxt($data){
        // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
        return $this->db->query("UPDATE solpagos SP
            inner JOIN empresas EM ON EM.idempresa=SP.idEmpresa 
            inner JOIN proveedores PV ON PV.idproveedor=SP.idProveedor 
            INNER JOIN autpagos AP ON AP.idsolicitud=SP.idsolicitud 
            SET AP.estatus = 11, AP.descarga = 1 , AP.fechDesc = date('Y-m-d H:i:s') 
            WHERE SP.programado IS NULL AND  SP.idEmpresa = ".$data." AND (AP.estatus IN (0, 11) AND SP.metoPago IN ('MAN', 'DOMIC', 'EFEC','ECHQ') OR AP.estatus IN ( 11, 13 ) AND SP.metoPago = 'ECHQ') AND(SP.moneda = 'MXN' OR SP.moneda<>'MXN' AND AP.tipoCambio IS NOT NULL)");
    }



    function updatePagosTxt_ind($empresa, $val){
        // FECHA : 10-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Seteo del usuario que realiza la actualizacion de la tabla para guardarlo en logs y auditoria.
        $this->db->query("SET @actualizada_por = " . (int)$this->session->userdata('inicio_sesion')['id']);
        foreach( $empresa->result() as $row ){
            $this->db->query('UPDATE autpagos SET autpagos.fechDesc=CURRENT_TIME(), autpagos.descarga='.$val.' WHERE autpagos.idpago = '.$row->idpago.'');
        }
    }

    function pabanco_individual($empresa, $idpago){
        return $this->db->query("SELECT solpagos.intereses, 
                                        solpagos.ref_bancaria,
                                        solpagos.moneda,
                                        autpagos.tipoCambio,
                                        autpagos.interes,
                                        autpagos.idpago,
                                        empresas.idempresa,
                                        solpagos.idsolicitud,
                                        solpagos.idProveedor,
                                        solpagos.idproveedor,
                                        proveedores.email,
                                        proveedores.rfc,
                                        proveedores.tipocta,
                                        proveedores.cuenta,
                                        proveedores.nombre as prov,
                                        bancos.clvbanco,
                                        bancos.nombre,
                                        solpagos.folio AS descri,
                                        solpagos.cantidad as total,
                                        proveedores.alias,
                                        autpagos.cantidad,
                                        empresas.abrev 
                                FROM solpagos 
                                INNER JOIN empresas 
                                    ON empresas.idempresa=solpagos.idEmpresa 
                                INNER JOIN proveedores
                                    ON proveedores.idproveedor=solpagos.idProveedor 
                                INNER JOIN bancos
                                    ON bancos.idbanco=proveedores.idbanco
                                INNER JOIN autpagos
                                    ON autpagos.idsolicitud = solpagos.idsolicitud
                                WHERE autpagos.estatus = 11 AND 
                                     ( solpagos.metoPago LIKE 'TEA' OR solpagos.metoPago LIKE 'FACT%' ) AND ((autpagos.tipoCambio <> '' AND (solpagos.moneda IN ( 'EUR', 'USD', 'CAD', 'MXV' ) ) ) OR (solpagos.moneda = 'MXN'))AND solpagos.idEmpresa = '$empresa' AND autpagos.idpago = $idpago");
    }
 
    function get_cuenta_empresa($emp, $cuenta){
        return $this->db->query("SELECT * FROM cuentas INNER JOIN empresas ON cuentas.idempresa=empresas.idempresa WHERE cuentas.idempresa = ".$emp." AND idcuenta = ".$cuenta);
    }

    function get_cuenta_empresas( $empresa){
        return $this->db->query('SELECT * FROM cuentas INNER JOIN empresas ON cuentas.idempresa=empresas.idempresa WHERE cuentas.idempresa = '.$empresa.'');
    }
}

 