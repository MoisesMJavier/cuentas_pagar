<?php
defined('BASEPATH') or exit('No direct script access allowed');
//add library
require_once APPPATH . '/third_party/spout/src/Spout/Autoloader/autoload.php';
//lets Use the Spout Namespaces
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Common\Entity\Row;

use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Box\Spout\Common\Entity\Style\Color;

use PhpOffice\PhpSpreadsheet\Spreadsheet; //class responsible to change the spreadsheet
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx; //class that write the table in .xlsx

class Reportes extends CI_Controller
{

    function __construct()
    {
        parent::__construct();

        if( !$this->session->userdata("inicio_sesion") ){
            redirect("Login", "refresh");
        }else{
            $this->load->model("Model_Reportes");
            $this->load->library('ExportarExcelGoogleCloud');
        }
    }

    // Reporte de historial Activas pago proveedor
    public function reporte_historial_solicitudes(){
        $filtro_cabecera = 'F_DISPERSADO';
         // Ajuste de tiempo máximo de ejecución
        ini_set('max_execution_time', 0);
        set_time_limit(0);

        // Ajuste de límite de memoria
        ini_set('memory_limit', '-1');

        $inicio_semana = $this->input->post("fechaInicio");
        $fin_semana = $this->input->post("fechaFinal");

        if( $this->session->userdata("inicio_sesion")['rol'] == 'CP' ){
            $fecha_filtro = 'fecelab';
        }else{
            $fecha_filtro = 'fecelab';
            $inicio_semana = $inicio_semana .' 00:00:00';
            $fin_semana = $fin_semana .' 23:59:59';
        }
        
        $etiqueta = ""; //Solo es visible para rol CP /** INICIO FECHA: 10-FEBRERO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
        $leftEtiqueta = ""; //Solo es visible para rol CP /** INICIO FECHA: 10-FEBRERO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/

        /** @author DANTE ALDAIR GUERRERO ALDANA <coordinador6.desarrollo@ciudadmaderas.com> | FECHA: 11-06-2025 | MODIFICACION AL GENERAR EXCEL*/
        $whereIntercambio = '';
        $etapaIntercambio = '';
        if (in_array($this->session->userdata("inicio_sesion")['id'], ['255', '320'])) {
            $whereIntercambio = "UNION ";
            $whereIntercambio .= "SELECT solpagos.*, 
                                         NULL tpagos,
                                         NULL fechaDis,
                                         NULL pagado,
                                         NULL estatus_pago,
                                         NULL upago,
                                         NULL fecha_cobro,
                                         NULL etapa_pago,
                                         NULL insumo
                                  FROM solpagos 
                                  WHERE solpagos.metoPago = 'INTERCAMBIO' AND solpagos.idetapa IN (12) AND solpagos.$fecha_filtro BETWEEN '$inicio_semana' AND '$fin_semana'";
            $etapaIntercambio = '';
            $etapaIntercambio .= ', 12';
        }
        
        //DATOS DE LA CABECERA DE LA TABLA
        $idsolicitud = $this->input->post("#") ? $this->input->post("#") : '';
        $proyecto = $this->input->post("PROYECTO") ? $this->input->post("PROYECTO") : '';
        $empresa = $this->input->post("EMPRESA") ? $this->input->post("EMPRESA") : '';
        $folio = $this->input->post("FOLIO") ? $this->input->post("FOLIO") : '';
        $fec_captura = $this->input->post("FECHA_CAPTURA") ? fechaSinFormato($this->input->post("FECHA_CAPTURA")) : '';
        $fec_pago = $this->input->post("FECHA_PAGO") ? fechaSinFormato($this->input->post("FECHA_PAGO")) : '';
        $fec_autorizacion = $this->input->post("FECHA_AUTORIZACIÓN") ? fechaSinFormato($this->input->post("FECHA_AUTORIZACIÓN")) : '';
        $fec_factura = $this->input->post("FECHA_FACT") ? fechaSinFormato($this->input->post("FECHA_FACT")) : '';
        $proveedor = $this->input->post("PROVEEDOR") ? $this->input->post("PROVEEDOR") : '';
        $departamento = $this->input->post("DEPARTAMENTO") ? $this->input->post("DEPARTAMENTO") : '';
        $cantidad = $this->input->post("CANTIDAD") ? str_replace(',', '', $this->input->post("CANTIDAD")) : '';
        $pagado = $this->input->post("PAGADO") ? str_replace(',', '', $this->input->post("PAGADO")) : '';
        $saldo = $this->input->post("SALDO") ? str_replace(',', '', $this->input->post("SALDO")) : '';
        $gasto = $this->input->post("GASTO") ? $this->input->post("GASTO") : '';
        $forma_pago = $this->input->post("FORMA_PAGO") ? $this->input->post("FORMA_PAGO") : '';
        $capturista = $this->input->post("CAPTURISTA") ? $this->input->post("CAPTURISTA") : '';                                                       
        $fecha_recordatorio = $this->input->post("RECORDATORIO") ? fechaSinFormato($this->input->post("RECORDATORIO")) : ''; /** FECHA: 05-SEPTIEMBRE-2024 | CAMBIO RECORDATORIO | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/

        // Seleccion de consulta para el reporte
        $tipo_reporte = implode("-", array_reverse(explode("/", $this->input->post("tipo_reporte"))));

        $left = "";
        $find = "";
        if(in_array($this->session->userdata("inicio_sesion")['id'], array(2767, 104))){// ASISTENCIA OOAM (REPORTES)
            $left = "LEFT JOIN usuarios ON solpagos.idusuario  = usuarios.idusuario";
            $find = "OR FIND_IN_SET( '".$this->session->userdata("inicio_sesion")['id']."', usuarios.sup )";
        }

        $etapas = "1, 25, 30, 42, 45, 47, 49";
        if( $departamento != '' ){
            $departamento = "AND ( solpagos.nomdepto LIKE '%$departamento%' )";
            // $departamento = "AND ( solpagos.nomdepto LIKE '%$departamento%' )".( $tipo_reporte == '#historial_activas_prov' ? "AND ( caja_chica = 0 OR caja_chica IS NULL )" : ( $tipo_reporte == '#historial_activas_cch' ?  " AND ( caja_chica = 1 )" : " AND ( caja_chica = 2 )" ) );
            // $departamento = "AND ( solpagos.nomdepto LIKE '%$departamento%' OR '$departamento' = '')";
        }else{
            switch ($this->session->userdata("inicio_sesion")['rol']) {
                case 'DA':
                    $departamento = "AND solpagos.nomdepto IN ( SELECT DISTINCT( departamento ) departamento FROM departament_usuario WHERE idusuario = '".$this->session->userdata("inicio_sesion")['id']."' )";
                    if( $this->session->userdata("inicio_sesion")['depto'] == "CONSTRUCCION" ){
                        $departamento = "AND solpagos.nomdepto in ('CONSTRUCCION', 'NOMINA DESTAJO', 'JARDINERIA')";
                    }elseif( $this->session->userdata("inicio_sesion")['depto'] == "ADMON OFICINA" ){
                        $departamento = "AND solpagos.nomdepto in ('ADMON OFICINA', 'RECEPCION')";
                    }elseif( $this->session->userdata("inicio_sesion")['depto'] == "OOAM" ){
                        $departamento = "AND ( solpagos.nomdepto LIKE '%OOAM%' OR proyecto IN ( 'GASTOS OOAM', 'GASTOS DE OFICINA OOAM' ) )";
                    }
                    $etapas = "1, 3, 4, 6, 8, 21, 25, 30";
                    break;
                case 'AS':
                    if( $this->session->userdata("inicio_sesion")['depto'] == "CONSTRUCCION" ){
                        // $departamento = "AND solpagos.nomdepto in ('CONSTRUCCION', 'NOMINA DESTAJO', 'JARDINERIA')"; CAMBIO DANTE ALDAIR 13-11-2024 SE DEJA POR CUESTION DE RESPALDO.
                        $departamento = "AND (solpagos.nomdepto in ('CONSTRUCCION', 'NOMINA DESTAJO', 'JARDINERIA') OR solpagos.nomdepto IN (SELECT departamento FROM departament_usuario WHERE idusuario = '".$this->session->userdata("inicio_sesion")['id']."') )";
                    }elseif( $this->session->userdata("inicio_sesion")['depto'] == "OOAM" ){
                        $departamento = "AND ( solpagos.nomdepto LIKE '%OOAM%' OR proyecto IN ( 'GASTOS OOAM', 'GASTOS DE OFICINA OOAM' ) )";
                    }else{
                        $filtro_cabecera = 'FEC_PAGO';
                        $departamento = "AND solpagos.nomdepto IN ( SELECT DISTINCT( departamento ) departamento FROM departament_usuario WHERE idusuario = '".$this->session->userdata("inicio_sesion")['id']."' )";
                    }
                    // Mandamos una condicion más que represente las solicitudes de los usuarios los cuales tiene como supervisados. De momento se cierra al usuario de Ma. Guadalupe Juarez Percatre de Jardineria
                    if ($this->session->userdata("inicio_sesion")['depto'] == "JARDINERIA" && $this->session->userdata("inicio_sesion")['id'] == "2625") {
                        $departamento = "AND (solpagos.nomdepto IN ( SELECT DISTINCT( departamento ) departamento FROM departament_usuario WHERE idusuario = '".$this->session->userdata("inicio_sesion")['id']."' ) OR solpagos.idusuario IN (SELECT usuarios.idusuario FROM usuarios WHERE FIND_IN_SET(".$this->session->userdata("inicio_sesion")['id'].", usuarios.sup )))";
                    }
                    $etapas = "1, 3, 4, 6, 8, 21, 25, 30";
                    break;
                case 'CA':
                case 'CJ':
                    $departamento = "AND (solpagos.idusuario = '".$this->session->userdata("inicio_sesion")['id']."' $find)";
                case 'CS':
                    $etapas = "1, 3, 4, 6, 8, 21, 25, 30";
                    break;
            }
        }

        if ($this->session->userdata("inicio_sesion")['rol'] && $this->session->userdata("inicio_sesion")['depto'] == 'CI-COMPRAS') {
            $etapas = '1, 25, 30';
        }
        
        if( $tipo_reporte == '#historial_activas_prov' ){
            $nombre_excel = "REPORTE_SOLICITUDES_ACTIVAS_PAGO_PROVEEDOR";

            $solpagos = "SELECT solpagos.*, lcontratos.nombre_contrato
                         FROM ( SELECT  solpagos.*,
                                        NULL tpagos,
                                        NULL fechaDis,
                                        NULL pagado,
                                        NULL estatus_pago,
                                        NULL upago,
                                        NULL fecha_cobro,
                                        NULL etapa_pago,
                                        NULL insumo
                                FROM solpagos
                                $left
                                WHERE idetapa NOT IN (0 , 30, 31, 9, 10, 11 $etapaIntercambio) AND ( caja_chica IS NULL OR caja_chica = 0 ) $departamento
                                UNION
                                SELECT  solpagos.*,
                                        cant_pag.tpagos,
                                        cant_pag.fechaDis,
                                        cant_pag.pagado,
                                        cant_pag.estatus_pago,
                                        cant_pag.upago,
                                        cant_pag.fecha_cobro,
                                        cant_pag.etapa_pago,
                                        NULL insumo
                                FROM solpagos
                                LEFT JOIN (SELECT * FROM vw_autpagos) cant_pag 
                                    ON cant_pag.idsolicitud = solpagos.idsolicitud
                                $left
                                WHERE   solpagos.idetapa IN (9, 10, 11, 5 $etapaIntercambio) AND
                                      ( solpagos.caja_chica IS NULL OR solpagos.caja_chica = 0 ) AND 
                                      (solpagos.$fecha_filtro BETWEEN '$inicio_semana' AND '$fin_semana')
                                      $departamento 
                            $whereIntercambio) solpagos 
                         LEFT JOIN lcontratos 
                             ON solpagos.idsolicitud = lcontratos.idsolicitud AND solpagos.nomdepto IN ( 'CONSTRUCCION', 'JARDINERIA' )";
        }

        if( $tipo_reporte == '#historial_activas_cch' ){
            $nombre_excel = "REPORTE_SOLICITUDES_ACTIVAS_PAGO_CAJA_CHICA";

            $solpagos = "SELECT solpagos.*, insumos.insumo
                         FROM ( SELECT  solpagos.*,
                                        NULL tpagos,
                                        NULL fechaDis,
                                        NULL pagado,
                                        NULL estatus_pago,
                                        NULL upago,
                                        NULL fecha_cobro,
                                        NULL etapa_pago,
                                        NULL nombre_contrato
                                FROM solpagos
                                $left
                                WHERE idetapa NOT IN (0 , 30, 31, 9, 10, 11) AND caja_chica = 1 $departamento
                                UNION
                                SELECT  solpagos.*,
                                        cant_pag.tpagos,
                                        cant_pag.fechaDis,
                                        solpagos.cantidad pagado,
                                        cant_pag.estatus_pago,
                                        cant_pag.upago,
                                        cant_pag.fecha_cobro,
                                        cant_pag.etapa_pago,
                                        NULL nombre_contrato
                                FROM solpagos
                                LEFT JOIN (SELECT * FROM vw_autpagos_caja_chica) cant_pag
                                    ON cant_pag.idsolicitud = solpagos.idsolicitud
                                $left
                                WHERE solpagos.idetapa IN (9, 10, 11) AND 
                                    solpagos.caja_chica = 1 AND 
                                    (solpagos.$fecha_filtro BETWEEN '$inicio_semana' AND '$fin_semana')
                                    $departamento) solpagos
                        CROSS JOIN insumos 
                            ON insumos.idinsumo = solpagos.servicio";

        }

        if( $tipo_reporte == '#historial_activas_viaticos' ){
            $nombre_excel = "REPORTE_SOLICITUDES_ACTIVAS_PAGO_VIÁTICOS";

            $solpagos = "SELECT solpagos.*, ifnull(insumos.insumo, 'VIATICOS') insumo
                         FROM ( SELECT  *,
                                        NULL tpagos,
                                        NULL fechaDis,
                                        NULL pagado,
                                        NULL estatus_pago,
                                        NULL upago,
                                        NULL fecha_cobro,
                                        NULL etapa_pago,
                                        NULL nombre_contrato
                                FROM solpagos
                                WHERE idetapa NOT IN (0 , 30, 31, 9, 10, 11) AND caja_chica = 4 $departamento
                                UNION
                                SELECT solpagos.*,
                                        cant_pag.tpagos,
                                        cant_pag.fechaDis,
                                        solpagos.cantidad pagado,
                                        cant_pag.estatus_pago,
                                        cant_pag.upago,
                                        cant_pag.fecha_cobro,
                                        cant_pag.etapa_pago,
                                        NULL nombre_contrato
                                FROM solpagos
                                LEFT JOIN (SELECT * FROM vw_autpagos_caja_chica) cant_pag
                                    ON cant_pag.idsolicitud = solpagos.idsolicitud
                                WHERE  idetapa IN (9, 10, 11) AND 
                                        caja_chica = 4 AND 
                                        (solpagos.$fecha_filtro BETWEEN '$inicio_semana' AND '$fin_semana')
                                        $departamento) AS solpagos
                         LEFT JOIN insumos 
                            ON insumos.idinsumo = solpagos.servicio";

        }
        if( $tipo_reporte == '#historial_activas_tdc' ){
            $nombre_excel = "REPORTE_SOLICITUDES_ACTIVAS_PAGO_TDC";

            $solpagos = "SELECT solpagos.*,
                                NULL tpagos,
                                NULL fechaDis,
                                NULL pagado,
                                NULL estatus_pago,
                                NULL upago,
                                NULL fecha_cobro,
                                NULL etapa_pago,
                                NULL insumo,
                                NULL nombre_contrato
                        FROM solpagos
                        $left
                        WHERE idetapa NOT IN (0 , 30, 31, 9, 10, 11) AND caja_chica = 2 $departamento
                        UNION
                        SELECT  solpagos.*,
                                cant_pag.tpagos,
                                cant_pag.fechaDis,
                                solpagos.cantidad pagado,
                                cant_pag.estatus_pago,
                                cant_pag.upago,
                                cant_pag.fecha_cobro,
                                cant_pag.etapa_pago,
                                NULL insumo,
                                NULL nombre_contrat
                        FROM solpagos
                        LEFT JOIN (SELECT * FROM vw_autpagos_caja_chica) cant_pag 
                            ON cant_pag.idsolicitud = solpagos.idsolicitud
                        $left
                        WHERE   idetapa IN (9, 10, 11) AND
                                caja_chica = 2 AND 
                                (solpagos.$fecha_filtro BETWEEN '$inicio_semana' AND '$fin_semana')
                                $departamento";
        }

        //BUSCAMOS TODOS LOS ESTATUS DISPONIBLES EN EL SISTEMA
        /*
        Debido a los acentos en el sistema no los lo
        */
        $estatus = '';
        $estatus_pago = '';

        if( $this->input->post("ESTATUS") ){
            $estatus_sistema = $this->db->query("SELECT GROUP_CONCAT(idetapa) AS idetapa, GROUP_CONCAT(nombre) AS nombre  FROM etapas WHERE nombre LIKE '%".$this->input->post("ESTATUS")."%'");
            if( $estatus_sistema->num_rows() > 0 ){
                $estatus = $estatus_sistema->row()->idetapa;
            }else{
                $estatus_pago = $this->input->post("ESTATUS");
            }
        }
        
        $nuevosCampos = '';

        /** INICIO FECHA: 05-SEPTIEMBRE-2024 | CAMBIO RECORDATORIO | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
        if($this->session->userdata("inicio_sesion")['depto'] == 'ADMINISTRACION' && $this->session->userdata("inicio_sesion")['rol'] == 'CP' && $tipo_reporte == '#historial_activas_prov'){
            $nuevosCampos .= "IFNULL( solpagos.fecha_recordatorio, 'NA' ) AS 'FECHA RECORDATORIO',";
        }

        $agregar_campos = "";
        $agregar_left = "";
        if( $tipo_reporte == '#historial_activas_cch' && $this->session->userdata("inicio_sesion")['rol'] == 'CP'){
            $agregar_campos .= "IFNULL(responsable_cch.nombre_reembolso_cch, 'NA') AS 'REEMBOLSAR A', ";
            $agregar_left .= " LEFT JOIN (
                    SELECT
                        cajas_ch.idusuario
                        , GROUP_CONCAT(cajas_ch.nombre_reembolso_ch SEPARATOR ', ') nombre_reembolso_cch
                    FROM cajas_ch
                    GROUP BY cajas_ch.idusuario
                ) AS responsable_cch ON responsable_cch.idusuario = solpagos.idResponsable";
        }

        if( $tipo_reporte == '#historial_activas_tdc' && $this->session->userdata("inicio_sesion")['rol'] == 'CP'){
            $agregar_campos .= "IFNULL(tarjeta.titular_nombre, 'NA') as 'TITULAR TARJETA',";
            $agregar_left .= " LEFT JOIN tcredito tdc ON solpagos.idResponsable = tdc.idtcredito
                LEFT JOIN ( 
                    SELECT 
                        usuarios.idusuario, CONCAT( usuarios.nombres,' ', usuarios.apellidos ) AS titular_nombre
                    FROM usuarios 
                ) AS tarjeta ON tarjeta.idusuario = tdc.idtitular";
        }
        /** FIN FECHA: 05-SEPTIEMBRE-2024 | CAMBIO RECORDATORIO | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/

        $sql = "SELECT  solpagos.idsolicitud AS '# SOLICITUD',
                        empresas.abrev EMPRESA,
                        IFNULL(solpagos.proyecto, IFNULL(pd.nombre, 'NA')) AS PROYECTO, /** FECHA: 10-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                        IFNULL(os.nombre, 'NA') AS 'OFICINA/SEDE', /** FECHA: 10-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                        IFNULL(tsp.nombre, 'NA') AS 'SERVICIO/PARTIDA', /** FECHA: 10-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                        solpagos.etapa ETAPA,
                        solpagos.condominio CONDOMINIO,
                        capturista.nombre_completo 'USUARIO (CAPTURISTA)', /** FECHA: 05-SEPTIEMBRE-2024 | CAMBIO RECORDATORIO | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                        usuarios.nombre_completo RESPONSABLE, /** FECHA: 05-SEPTIEMBRE-2024 | CAMBIO RECORDATORIO | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                        $agregar_campos /** FECHA: 05-SEPTIEMBRE-2024 | CAMBIO RECORDATORIO | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                        IFNULL(SUBSTRING(facturas.uuid, - 5), 'SF') FOLIO_FISCAL,
                        solpagos.folio FOLIO,
                        solpagos.homoclave HOMOCLAVE,
                        solpagos.insumo INSUMO,
                        solpagos.fechaCreacion F_CAPTURA,
                        solpagos.fecha_autorizacion F_VoBo_DA,
                        solpagos.fecelab F_FACTURA,
                        solpagos.upago F_AUT_PAGO,
                        solpagos.fechaDis AS $filtro_cabecera,
                        proveedores.nombre PROVEEDOR,
                        solpagos.orden_compra ORDEN_DE_COMPRA,
                        solpagos.nombre_contrato CONTRATO,
                        solpagos.nomdepto DEPARTAMENTO,
                        IF(solpagos.programado IS NOT NULL,
                            IF(solpagos.idetapa NOT IN (11 , 30, 0),
                                solpagos.cantidad,
                                0) + IFNULL(solpagos.pagado, 0),
                            solpagos.cantidad) CANTIDAD,
                        solpagos.moneda MONEDA,
                        IFNULL(solpagos.pagado, 0) PAGADO,
                        solpagos.descuento DESCUENTO,
                        solpagos.cantidad - IFNULL(solpagos.pagado, 0) SALDO,
                        IF(solpagos.estatus_pago IS NULL OR solpagos.estatus_pago = 16, etapas.nombre, solpagos.etapa_pago) ESTATUS,
                        CASE 
                            WHEN solpagos.caja_chica = 1 THEN 
                                'CAJA CHICA'
                            WHEN solpagos.caja_chica = 2 THEN
                                'PAGO TDC'
                            WHEN solpagos.caja_chica = 3 THEN /** FECHA: 10-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                                'REEMBOLSO' /** FECHA: 10-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                            WHEN solpagos.caja_chica = 4 THEN
                                'VIÁTICOS'
                            ELSE
                                'PAGO PROVEEDOR'
                        END AS GASTO_TPAGO,
                        solpagos.metoPago FORMA_DE_PAGO,
                        facturas.descripcion DESCRIPCION_CFDi,
                        solpagos.justificacion JUSTIFICACIÓN,
                        IFNULL(facturas.uuid, 'SF') UUID,
                        solpagos.crecibo CONTRA_RECIBO, /** FECHA: 10-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                        $nuevosCampos /** FECHA: 10-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                        etapas.nombre NOMBRE_ETAPA /** FECHA: 10-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                FROM (SELECT *
                      FROM ( $solpagos ) solpagos) solpagos
                LEFT JOIN ( SELECT idsolicitud, uuid, descripcion FROM factura_registro ) facturas ON solpagos.idsolicitud = facturas.idsolicitud AND CONCAT( solpagos.folio,'/',IFNULL( facturas.uuid, '' ) ) LIKE '%$folio%'
                INNER JOIN listado_usuarios capturista ON capturista.idusuario = solpagos.idusuario AND capturista.nombre_completo LIKE '%$capturista%'
                INNER JOIN ( SELECT *, 0 tresp 
                             FROM listado_usuarios
                            UNION
                             SELECT *, 1 tresp
                             FROM lista_rtdc ) usuarios 
                    ON usuarios.idusuario = solpagos.idResponsable AND 
                       ( ( ( solpagos.caja_chica IN (2, 4) AND usuarios.tresp = 1 ) OR (solpagos.caja_chica IN (4) AND usuarios.tresp = 0) ) OR 
                         ( ( solpagos.caja_chica IN ( 0, 1 ) OR solpagos.caja_chica IS NULL ) AND usuarios.tresp = 0 ) )
                INNER JOIN ( SELECT idempresa, abrev FROM empresas ) empresas ON solpagos.idEmpresa = empresas.idempresa AND empresas.abrev  LIKE '%$empresa%'
                INNER JOIN ( SELECT idProveedor, nombre FROM proveedores ) proveedores ON proveedores.idproveedor = solpagos.idProveedor AND proveedores.nombre LIKE '%$proveedor%'
                INNER JOIN ( SELECT idetapa, nombre FROM etapas ) etapas ON etapas.idetapa = solpagos.idetapa
                LEFT JOIN solicitud_proyecto_oficina spo on solpagos.idsolicitud =  spo.idsolicitud
                LEFT JOIN proyectos_departamentos pd on spo.idProyectos = pd.idProyectos
                LEFT JOIN oficina_sede os on spo.idOficina = os.idOficina
                LEFT JOIN tipo_servicio_partidas tsp on spo.idTipoServicioPartida = tsp.idTipoServicioPartida
                $agregar_left /**FECHA: 05-SEPTIEMBRE-2024 | CAMBIO RECORDATORIO | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                WHERE  solpagos.idetapa NOT IN ( $etapas )
                    AND (solpagos.idsolicitud LIKE '%$idsolicitud%')
                    AND (IFNULL(solpagos.proyecto, IFNULL(pd.nombre, 'NA')) LIKE '%$proyecto%')
                    AND (solpagos.cantidad LIKE '%$cantidad%')
                    AND (solpagos.fechaCreacion  LIKE '%$fec_captura%')
                    AND (IFNULL(solpagos.fecha_autorizacion, '') LIKE '%$fec_autorizacion%')
                    AND (solpagos.fecelab LIKE '%$fec_factura%')
                    AND (solpagos.metoPago LIKE '%$forma_pago%')
                    AND ".( $estatus != '' ? "solpagos.idetapa IN ($estatus)" : "IFNULL(solpagos.etapa_pago, '' ) LIKE '%$estatus_pago%'")."
                    AND IFNULL(solpagos.upago, '') LIKE '%$fec_pago%'
                    AND IFNULL(solpagos.fecha_recordatorio, '') LIKE '%$fecha_recordatorio%' /** INICIO FECHA: 05-SEPTIEMBRE-2024 | CAMBIO RECORDATORIO | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                ORDER BY solpagos.idsolicitud DESC, solpagos.fechaCreacion";
        $this->load->dbutil();
        
        header("Content-type: text/csv;charset=UTF-8"); 
        header('Content-Disposition: attachment;filename="'.$nombre_excel . "_" .date("d-m-Y"). "_.csv".'"');
        header("Pragma: no-cache");

        //echo $this->dbutil->csv_from_result($this->db->query($sql));
        echo chr(239).chr(187).chr(191).$this->dbutil->csv_from_result($this->db->query($sql), ",", "\r\n");
        exit;
    }

    // Reporte de historial de pagos
    public function reporte_historial_pagos(){

        // Obtener la fecha actual
        $hoy = getdate();

        // Variable para la consulta
        $query = '';

        if($this->session->userdata("inicio_sesion")['depto'] == 'ADMINISTRACION' && $this->session->userdata("inicio_sesion")['rol'] == 'CP'){
            $inicio_semana = $this->input->post("fechaInicial");
            $fin_semana = $this->input->post("fechaFinal");
        }else{
            $inicio_semana = $this->input->post("fechaInicial") ? implode("-", array_reverse(explode("/", $this->input->post("fechaInicial")))) : '1990-01-01';
            $fin_semana = $this->input->post("fechaFinal") ? implode("-", array_reverse(explode("/", $this->input->post("fechaFinal")))) : ($hoy['year'] + 1). "-" . $hoy['mon'] . "-" . ($hoy['mday'] + 1);
        }

        // Encabezados de las tablas para los filtros del reporte
        $idsolicitud = $this->input->post("#") ? $this->input->post("#") : '';
        $folio = $this->input->post("FOLIO") ? $this->input->post("FOLIO") : '';
        $folio_fiscal = $this->input->post("FOLIO_FISCAL") ? $this->input->post("FOLIO_FISCAL") : '';
        $proveedor = $this->input->post("PROVEEDOR") ? $this->input->post("PROVEEDOR") : '';
        $fec_dispersion = $this->input->post("F_DISPERSIÓN") ? fechaSinFormato($this->input->post("F_DISPERSIÓN")) : '';
        $fec_autorizado = $this->input->post("F_AUTORIZADO") ? fechaSinFormato($this->input->post("F_AUTORIZADO")) : '';
        $empresa = $this->input->post("EMPRESA") ? $this->input->post("EMPRESA") : '';
        $departamento = $this->input->post("DEPARTAMENTO") ? $this->input->post("DEPARTAMENTO") : '';
        $cantidad = $this->input->post("CANTIDAD") ? str_replace(',', '', $this->input->post("CANTIDAD")) : '';
        $moneda = $this->input->post("MONEDA") ? $this->input->post("MONEDA") : ''; // FECHA: 26-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com>
        $meto_pago = $this->input->post("MÉTODO_PAGO") ? $this->input->post("MÉTODO_PAGO") : '';
        $estatus = $this->input->post("ESTATUS") ? $this->input->post("ESTATUS") : '';


        set_time_limit(0);
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '-1');

        switch ($this->session->userdata("inicio_sesion")['rol']) {
            case 'DA':
            case 'AS':

                if($this->session->userdata("inicio_sesion")['depto'] == 'CONSTRUCCION'){
                    $departamentos = "'NOMINA DESTAJO', 'CONSTRUCCION'";
                }elseif($this->session->userdata("inicio_sesion")['depto'] == 'TECNOLOGIA DE LA INFORMACION'){
                    $departamentos = "SELECT depto FROM departamento WHERE idusuario IN (" . $this->session->userdata("inicio_sesion")['id'] .")";
                }else{
                    $departamentos = "'".$this->session->userdata("inicio_sesion")['depto']."'";
                }

                $query = $this->db->query(" SELECT  solpagos.idsolicitud, 
                                                    solpagos.folio, 
                                                    IFNULL(SUBSTR(facturas.uuid, -1, 5), 'SF') AS uuid, 
                                                    IFNULL(autpagos.fechaDis,'') AS fecha_dispersion, 
                                                    IFNULL(autpagos.fecreg,'') AS fecreg_2,
                                                    autpagos.cantidad, 
                                                    IFNULL(solpagos.metoPago, autpagos.formaPago) AS formaPago, 
                                                    autpagos.estatus,
                                                    solpagos.justificacion, 
                                                    polizas_provision.numpoliza, 
                                                    capturista.nombre_completo nombre_capturista, 
                                                    responsable.nombre_completo nombre_responsable, 
                                                    solpagos.fecelab AS fecha_factura, 
                                                    solpagos.fechaCreacion AS fecha_captura, 
                                                    solpagos.moneda, 
                                                    proveedores.nombre, 
                                                    solpagos.nomdepto, 
                                                    IFNULL(autpagos.fechaOpe, autpagos.fecha_cobro) fechaOpe, 
                                                    empresas.abrev, 
                                                    IFNULL(autpagos.referencia, '') AS referencia, 
                                                    solpagos.cantidad AS solicitado, 
                                                    solpagos.proyecto,
                                                    solpagos.etapa,
                                                    solpagos.condominio,
                                                    IFNULL(solpagos.proyecto, pd.nombre) AS proyecto,
                                                    os.nombre oficina,
                                                    tsp.nombre servicioPartida
                                            FROM autpagos 
                                            INNER JOIN pagos_proveedor solpagos ON autpagos.idsolicitud = solpagos.idsolicitud 
                                            INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor 
                                            INNER JOIN listado_usuarios capturista ON capturista.idusuario = solpagos.idusuario 
                                            INNER JOIN listado_usuarios responsable ON responsable.idusuario = solpagos.idResponsable 
                                            LEFT JOIN factura_registro facturas ON facturas.idsolicitud = solpagos.idsolicitud 
                                            INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa 
                                            LEFT JOIN poliza_solicitud polizas_provision ON polizas_provision.idsolicitud = solpagos.idsolicitud 
                                            LEFT JOIN solicitud_proyecto_oficina spo  on spo.idsolicitud = solpagos.idsolicitud 
                                            LEFT JOIN proyectos_departamentos pd  on spo.idProyectos = pd.idProyectos
                                            LEFT JOIN oficina_sede os on spo.idOficina = os.idOficina
                                            left join tipo_servicio_partidas tsp on tsp.idTipoServicioPartida = spo.idTipoServicioPartida 
                                            WHERE (solpagos.idsolicitud LIKE '%$idsolicitud%' OR '$idsolicitud' = '')
                                                  AND (solpagos.folio LIKE '%$folio%' OR '$folio' = '')
                                                  AND (IFNULL(SUBSTR(facturas.uuid, -1, 5), 'SF') LIKE '%$folio_fiscal%' OR '$folio_fiscal' = '')
                                                  AND (proveedores.nombre LIKE '%$proveedor%' OR '$proveedor' = '')
                                                  AND (DATE(autpagos.fechaDis) LIKE '%$fec_dispersion%' OR '$fec_dispersion' = '')
                                                  AND (DATE(autpagos.fecreg) LIKE '%$fec_autorizado%' OR '$fec_autorizado' = '')
                                                  AND (empresas.abrev LIKE '%$empresa%' OR '$empresa' = '')
                                                  AND (solpagos.nomdepto LIKE '%$departamento%' OR '$departamento' = '')
                                                  AND (autpagos.cantidad  LIKE '%$cantidad%' OR '$cantidad' = '')
                                                  AND ( CONCAT(solpagos.metoPago,' ', IFNULL(autpagos.referencia, '')) LIKE '%$meto_pago%' OR '$meto_pago' = '')
                                                  AND solpagos.nomdepto IN ( $departamentos ) 
                                                  ".( $this->input->post("fechaInicial") && $this->input->post("fechaFinal") ? "AND (DATE(autpagos.fechaDis) BETWEEN '$inicio_semana' AND '$fin_semana' )" : "" )."
                                            ORDER BY autpagos.fechaDis DESC");
            break;
            case 'CJ':
            case 'CA':
            case 'FP':
                $query = $this->db->query(" SELECT  solpagos.justificacion, 
                                                    polizas_provision.numpoliza, 
                                                    solpagos.idsolicitud, 
                                                    capturista.nombre_completo nombre_capturista, 
                                                    responsable.nombre_completo nombre_responsable, 
                                                    solpagos.etapa, 
                                                    solpagos.condominio, 
                                                    solpagos.fecelab AS fecha_factura, 
                                                    solpagos.fechaCreacion AS fecha_captura,  
                                                    autpagos.estatus, 
                                                    solpagos.folio, 
                                                    solpagos.moneda, 
                                                    proveedores.nombre, 
                                                    solpagos.nomdepto, 
                                                    autpagos.cantidad, 
                                                    IFNULL(autpagos.fechaOpe, autpagos.fecha_cobro) fechaOpe,  
                                                    IFNULL(solpagos.metoPago, autpagos.formaPago) AS formaPago, 
                                                    empresas.abrev, 
                                                    IFNULL(autpagos.referencia, '') AS referencia, 
                                                    solpagos.cantidad AS solicitado, 
                                                    IFNULL(SUBSTR(facturas.uuid, -1, 5), 'SF') AS uuid, 
                                                    IFNULL(autpagos.fechaDis, '') AS fecha_dispersion, 
                                                    IFNULL(autpagos.fecreg, '') AS fecreg_2,
                                                    IFNULL(solpagos.proyecto, pd.nombre) AS proyecto,
                                                    os.nombre oficina,
                                                    tsp.nombre servicioPartida
                                            FROM autpagos 
                                            INNER JOIN pagos_proveedor solpagos ON autpagos.idsolicitud = solpagos.idsolicitud 
                                            INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor 
                                            INNER JOIN listado_usuarios capturista ON capturista.idusuario = solpagos.idusuario 
                                            INNER JOIN listado_usuarios responsable ON responsable.idusuario = solpagos.idResponsable 
                                            LEFT JOIN factura_registro facturas ON facturas.idsolicitud = solpagos.idsolicitud 
                                            INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa 
                                            LEFT JOIN poliza_solicitud polizas_provision ON polizas_provision.idsolicitud = solpagos.idsolicitud 
                                            LEFT JOIN solicitud_proyecto_oficina spo  on spo.idsolicitud = solpagos.idsolicitud 
                                            LEFT JOIN proyectos_departamentos pd  on spo.idProyectos = pd.idProyectos
                                            LEFT JOIN oficina_sede os on spo.idOficina = os.idOficina
                                            left join tipo_servicio_partidas tsp on tsp.idTipoServicioPartida = spo.idTipoServicioPartida 
                                            WHERE (solpagos.idsolicitud LIKE '%$idsolicitud%' OR '$idsolicitud' = '')
                                                  AND (solpagos.folio LIKE '%$folio%' OR '$folio' = '')
                                                  AND (IFNULL(SUBSTR(facturas.uuid, -1, 5), 'SF') LIKE '%$folio_fiscal%' OR '$folio_fiscal' = '')
                                                  AND (proveedores.nombre LIKE '%$proveedor%' OR '$proveedor' = '')
                                                  AND (DATE(autpagos.fechaDis) LIKE '%$fec_dispersion%' OR '$fec_dispersion' = '')
                                                  AND (DATE(autpagos.fecreg) LIKE '%$fec_autorizado%' OR '$fec_autorizado' = '')
                                                  AND (empresas.abrev LIKE '%$empresa%' OR '$empresa' = '')
                                                  AND (solpagos.nomdepto LIKE '%$departamento%' OR '$departamento' = '')
                                                  AND (autpagos.cantidad  LIKE '%$cantidad%' OR '$cantidad' = '')
                                                  AND ( CONCAT(solpagos.metoPago,' ', IFNULL(autpagos.referencia, '')) LIKE '%$meto_pago%' OR '$meto_pago' = '')
                                                  AND solpagos.idusuario = '" . $this->session->userdata("inicio_sesion")['id'] . "' 
                                                  ".( $this->input->post("fechaInicial") && $this->input->post("fechaFinal") ? "AND (DATE(autpagos.fechaDis) BETWEEN '$inicio_semana' AND '$fin_semana' )" : "" )."
                                            ORDER BY autpagos.fechaDis DESC");
            break;
            case 'CT':
            case 'CX':
                $query = $this->db->query(" SELECT  solpagos.justificacion, 
                                                    polizas_provision.numpoliza, 
                                                    solpagos.idsolicitud, 
                                                    capturista.nombre_completo nombre_capturista, 
                                                    responsable.nombre_completo nombre_responsable, 
                                                    solpagos.etapa, 
                                                    solpagos.condominio, 
                                                    solpagos.fecelab AS fecha_factura, 
                                                    solpagos.fechaCreacion AS fecha_captura,  
                                                    autpagos.estatus, 
                                                    solpagos.folio, -- FECHA: 26-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com>
                                                    solpagos.cantidad AS solicitado,  -- FECHA: 26-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com>
                                                    autpagos.cantidad, -- FECHA: 26-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com>
                                                    solpagos.moneda, -- FECHA: 26-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com>
                                                    IF(autpagos.tipoCambio IS NULL AND solpagos.moneda = 'MXN', autpagos.cantidad, autpagos.tipoCambio * autpagos.cantidad) AS conversion, -- FECHA: 26-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com>
                                                    proveedores.nombre, 
                                                    solpagos.nomdepto, 
                                                    IFNULL(autpagos.fechaOpe, autpagos.fecha_cobro) fechaOpe,  
                                                    IFNULL(solpagos.metoPago, autpagos.formaPago) AS formaPago, 
                                                    empresas.abrev, 
                                                    IFNULL(autpagos.referencia, '') AS referencia, 
                                                    solpagos.proyecto, 
                                                    IFNULL(SUBSTR(facturas.uuid, -1, 5), 'SF') AS uuid, 
                                                    IFNULL(autpagos.fechaDis,'') AS fecha_dispersion, 
                                                    IFNULL(autpagos.fecreg,'') AS fecreg_2,
                                                    IFNULL(solpagos.proyecto, pd.nombre) AS proyecto,
                                                    os.nombre oficina,
                                                    tsp.nombre servicioPartida
                                            FROM autpagos 
                                            INNER JOIN pagos_proveedor solpagos ON autpagos.idsolicitud = solpagos.idsolicitud 
                                            INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor 
                                            INNER JOIN listado_usuarios capturista ON capturista.idusuario = solpagos.idusuario 
                                            INNER JOIN listado_usuarios responsable ON responsable.idusuario = solpagos.idResponsable 
                                            LEFT JOIN factura_registro facturas ON facturas.idsolicitud = solpagos.idsolicitud 
                                            INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa 
                                            LEFT JOIN poliza_solicitud polizas_provision ON polizas_provision.idsolicitud = solpagos.idsolicitud
                                            LEFT JOIN solicitud_proyecto_oficina spo  on spo.idsolicitud = solpagos.idsolicitud 
                                            LEFT JOIN proyectos_departamentos pd  on spo.idProyectos = pd.idProyectos
                                            LEFT JOIN oficina_sede os on spo.idOficina = os.idOficina
                                            LEFT JOIN tipo_servicio_partidas tsp on tsp.idTipoServicioPartida = spo.idTipoServicioPartida  
                                            WHERE (solpagos.idsolicitud LIKE '%$idsolicitud%' OR '$idsolicitud' = '')
                                                  AND (solpagos.folio LIKE '%$folio%' OR '$folio' = '')
                                                  AND (IFNULL(SUBSTR(facturas.uuid, -1, 5), 'SF') LIKE '%$folio_fiscal%' OR '$folio_fiscal' = '')
                                                  AND (proveedores.nombre LIKE '%$proveedor%' OR '$proveedor' = '')
                                                  AND (DATE(autpagos.fechaDis) LIKE '%$fec_dispersion%' OR '$fec_dispersion' = '')
                                                  AND (DATE(autpagos.fecreg) LIKE '%$fec_autorizado%' OR '$fec_autorizado' = '')
                                                  AND (empresas.abrev LIKE '%$empresa%' OR '$empresa' = '')
                                                  AND (solpagos.nomdepto LIKE '%$departamento%' OR '$departamento' = '')
                                                  AND (autpagos.cantidad  LIKE '%$cantidad%' OR '$cantidad' = '')
                                                  AND (solpagos.moneda  LIKE '%$moneda%' OR '$moneda' = '') -- FECHA: 26-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com>
                                                  AND ( CONCAT(solpagos.metoPago,' ', IFNULL(autpagos.referencia, '')) LIKE '%$meto_pago%' OR '$meto_pago' = '')
                                                  AND ( solpagos.idusuario = '" . $this->session->userdata("inicio_sesion")['id'] . "' OR FIND_IN_SET( solpagos.idEmpresa, '" . $this->session->userdata("inicio_sesion")['depto'] . "' ) ) 
                                                  ".( $this->input->post("fechaInicial") && $this->input->post("fechaFinal") ? "AND (DATE(autpagos.fechaDis) BETWEEN '$inicio_semana' AND '$fin_semana' )" : "" )."
                                            ORDER BY autpagos.fechaDis DESC");
            break;
            case 'AD':
            case 'PV':
            case 'SPV':
            case 'CAD':
            case 'CPV':
            case 'GAD':
            case 'CE':
            case 'CC':
            case 'CI':
            case 'DIO':
            case 'SAC':
            case 'IOO':
            case 'COO':
            case 'AOO':
                $query = $this->db->query(" SELECT  solpagos.justificacion, 
                                                    NULL numpoliza, 
                                                    solpagos.idsolicitud, 
                                                    capturista.nombre_completo nombre_capturista, 
                                                    responsable.nombre_completo nombre_responsable, 
                                                    solpagos.etapa, 
                                                    solpagos.condominio, 
                                                    solpagos.fecelab AS fecha_factura, 
                                                    solpagos.fechaCreacion AS fecha_captura,  
                                                    autpagos.estatus, 
                                                    solpagos.folio, 
                                                    proveedores.nombre, 
                                                    solpagos.nomdepto, 
                                                    autpagos.cantidad, 
                                                    solpagos.cantidad AS solicitado, -- FECHA: 26-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com>
                                                    solpagos.moneda, -- FECHA: 26-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com>
                                                    IF(autpagos.tipoCambio IS NULL AND solpagos.moneda = 'MXN', autpagos.cantidad, autpagos.tipoCambio * autpagos.cantidad) AS conversion, -- FECHA: 26-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com>
                                                    IFNULL(autpagos.fechaOpe, autpagos.fecha_cobro) fechaOpe,  
                                                    IFNULL(solpagos.metoPago, autpagos.formaPago) AS formaPago, 
                                                    empresas.abrev, 
                                                    IFNULL(autpagos.referencia, '') AS referencia, 
                                                    solpagos.proyecto, 
                                                    NULL uuid, 
                                                    IFNULL(autpagos.fechaDis,'') AS fecha_dispersion, 
                                                    IFNULL(autpagos.fecreg,'') AS fecreg_2,
                                                    IFNULL(solpagos.proyecto, pd.nombre) AS proyecto,
                                                    os.nombre oficina,
                                                    tsp.nombre servicioPartida
                                            FROM (
                                                SELECT * FROM autpagos
                                                WHERE DATE(autpagos.fechaDis) BETWEEN '$inicio_semana' AND '$fin_semana' 
                                                AND (autpagos.cantidad  LIKE '%$cantidad%') 
                                                AND (DATE(autpagos.fechaDis) LIKE '%$fec_dispersion%')
                                                AND (DATE(autpagos.fecreg) LIKE '%$fec_autorizado%')
                                            ) autpagos 
                                            INNER JOIN (
                                                SELECT * FROM pagos_proveedor solpagos
                                                JOIN ( SELECT departamento depto FROM departament_usuario WHERE idusuario = ? ) departamento ON departamento.depto = solpagos.nomdepto
                                                WHERE (solpagos.idsolicitud LIKE '%$idsolicitud%')
                                                AND solpagos.nomdepto != ?
                                                AND (solpagos.nomdepto LIKE '%$departamento%')
                                                AND (solpagos.moneda  LIKE '%$moneda%') -- FECHA: 26-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com>
                                            ) solpagos ON autpagos.idsolicitud = solpagos.idsolicitud 
                                            INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor 
                                            INNER JOIN listado_usuarios capturista ON capturista.idusuario = solpagos.idusuario 
                                            INNER JOIN listado_usuarios responsable ON responsable.idusuario = solpagos.idResponsable 
                                            INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa 
                                            LEFT JOIN solicitud_proyecto_oficina spo  on spo.idsolicitud = solpagos.idsolicitud 
                                            LEFT JOIN proyectos_departamentos pd  on spo.idProyectos = pd.idProyectos
                                            LEFT JOIN oficina_sede os on spo.idOficina = os.idOficina
                                            left join tipo_servicio_partidas tsp on tsp.idTipoServicioPartida = spo.idTipoServicioPartida 
                                            WHERE (proveedores.nombre LIKE '%$proveedor%')
                                                  AND (empresas.abrev LIKE '%$empresa%')
                                                  AND ( CONCAT(solpagos.metoPago,' ', IFNULL(autpagos.referencia, '')) LIKE '%$meto_pago%')
                                            ORDER BY autpagos.fechaDis DESC", 
                                            [$this->session->userdata("inicio_sesion")['id'],$this->session->userdata("inicio_sesion")['depto']]);
                break;
            default:
                $filtroAutPagos = '';
                $columnasSelect = '';
                $filtroAutPagos .= $this->input->post("CANTIDAD") ? " AND (autpagos.cantidad  LIKE '%$cantidad%' OR '$cantidad' = '') " : '';
                $filtroAutPagos .= $this->input->post("F_DISPERSIÓN") ? " AND (autpagos.fechaDis LIKE '%$fec_dispersion%')" : '';
                $filtroAutPagos .= $this->input->post("F_AUTORIZADO") ? " AND (autpagos.fecreg LIKE '%$fec_autorizado%')" : '';
                if ($this->session->userdata("inicio_sesion")['rol'] == 'CP' && $this->session->userdata("inicio_sesion")['depto'] == 'ADMINISTRACION') {
                    $columnasSelect = "solpagos.idsolicitud,
                                       IFNULL(solpagos.proyecto, pd.nombre) AS proyecto,
                                       os.nombre oficina,
                                       tsp.nombre servicioPartida,
                                       solpagos.etapa,
                                       solpagos.condominio,
                                       solpagos.folio,
                                       IFNULL(SUBSTR(facturas.uuid, -1, 5), 'SF') AS uuid,
                                       IFNULL(facturas.metodoPago, '') AS metodo_pago,
                                       IFNULL(facturas.uuid, 'SIN FACTURA XML') AS uuid_xml,
                                       IFNULL(fac.uuid, 'SIN COMPLEMENTO') AS uuid_complemento,
                                       CASE
                                           WHEN proveedores.excp = 0 THEN 'OBLIGATORIO CARGAR XML'
                                           WHEN proveedores.excp = 1 THEN 'XML POSTERIOR AL PAGO'
                                           WHEN proveedores.excp = 2 THEN 'NUNCA SE RECIBIRA FACTURA'
                                           WHEN proveedores.excp = 3 THEN 'INVOICE'
                                           ELSE 'SIN ESPECIFICAR'
                                       END AS facturacion,
                                       proveedores.nombre,
                                       IF(autpagos.fecreg = '0000-00-00 00:00:00' OR autpagos.fecreg IS NULL, '' , DATE_FORMAT(solpagos.fecelab, '%d/%m/%Y')) AS fecha_factura,
                                       responsable.nombre_completo AS nombre_responsable,
                                       IF(autpagos.fecreg IS NULL,'', DATE_FORMAT(autpagos.fecreg, '%d/%m/%Y')) AS fecreg_2,
                                       IF(autpagos.fechaDis IS NULL,'', DATE_FORMAT(autpagos.fechaDis, '%d/%m/%Y')) AS fecha_dispersion,
                                       IF(autpagos.fechaDis IS NULL,'', DATE_FORMAT(autpagos.fechaDis, '%d/%m/%Y')) AS fecha_dispersion,
                                       IF(autpagos.fechaOpe IS NULL,  DATE_FORMAT(autpagos.fecha_cobro, '%d/%m/%Y'), DATE_FORMAT(autpagos.fechaOpe, '%d/%m/%Y') ) fechaOpe,
                                       empresas.abrev,
                                       solpagos.nomdepto,
                                       IF( autpagos.tipoCambio IS NULL, autpagos.cantidad, autpagos.tipoCambio * autpagos.cantidad ) AS cantidad,
                                       IFNULL(solpagos.metoPago, autpagos.formaPago) AS formaPago,
                                       IFNULL(autpagos.referencia, '') AS referencia,
                                       CASE autpagos.estatus
                                            WHEN '0' THEN 'AUTORIZADO POR DG'
                                            WHEN '5' THEN 'DISPERSANDO'
                                            WHEN '11' THEN 'EN ESPERA PARA ENVIAR A DISPERCION'
                                            WHEN '12' THEN 'PAGO DETENIDO'
                                            WHEN '15' THEN 'POR CONFIRMAR PAGO'
                                            WHEN '16' THEN 'PAGO COMPLETO'
                                            ELSE 'PROCESANDO PAGO CXP'
                                        END AS estatus_text,
                                        solpagos.justificacion";

                }else{
                    $columnasSelect = "solpagos.idsolicitud, 
                                       solpagos.folio, 
                                       IFNULL(SUBSTR(facturas.uuid, -1, 5), 'SF') AS uuid,
                                       IFNULL(facturas.metodoPago, '') AS metodo_pago, -- FECHA: 28-AGOSTO-2024 | @author Dante Aldair Guerrero <coordinador6.desarrollo@ciudadmaderas.com>
                                       IFNULL(facturas.uuid, 'SIN FACTURA XML') AS uuid_xml, -- FECHA: 22-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com>
                                       IFNULL(fac.uuid, 'SIN COMPLEMENTO') AS uuid_complemento,-- FECHA: 28-AGOSTO-2024 | @author Dante Aldair Guerrero <coordinador6.desarrollo@ciudadmaderas.com>
                                       CASE -- INICIO FECHA: 22-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com>
                                           WHEN proveedores.excp = 0 THEN 'OBLIGATORIO CARGAR XML'
                                           WHEN proveedores.excp = 1 THEN 'XML POSTERIOR AL PAGO'
                                           WHEN proveedores.excp = 2 THEN 'NUNCA SE RECIBIRA FACTURA'
                                           WHEN proveedores.excp = 3 THEN 'INVOICE'
                                           ELSE 'SIN ESPECIFICAR'
                                       END AS facturacion, -- FIN FECHA: 22-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com>
                                       proveedores.nombre, 
                                       responsable.nombre_completo nombre_responsable, 
                                       IFNULL(autpagos.fechaDis,'') AS fecha_dispersion,
                                       IFNULL(autpagos.fecreg,'') AS fecreg_2,
                                       empresas.abrev,
                                       solpagos.nomdepto,
                                       IF( autpagos.tipoCambio IS NULL, autpagos.cantidad, autpagos.tipoCambio * autpagos.cantidad ) cantidad,
                                       autpagos.tipoCambio, 
                                       IFNULL(solpagos.metoPago, autpagos.formaPago) AS formaPago, 
                                       solpagos.metoPago,
                                       autpagos.estatus,
                                       solpagos.justificacion,
                                       polizas_provision.numpoliza, 
                                       capturista.nombre_completo nombre_capturista, 
                                       solpagos.fecelab AS fecha_factura,
                                       solpagos.fechaCreacion AS fecha_captura,  
                                       'MXN' moneda, 
                                       IFNULL(autpagos.fechaOpe, autpagos.fecha_cobro) fechaOpe, 
                                       IFNULL(autpagos.referencia, '') AS referencia,
                                       solpagos.proyecto, 
                                       solpagos.cantidad AS solicitado,
                                       solpagos.etapa,
                                       solpagos.condominio,
                                       IFNULL(solpagos.proyecto, pd.nombre) AS proyecto,
                                       os.nombre oficina,
                                       tsp.nombre servicioPartida";
                }            
                $query = $this->db->query(" SELECT $columnasSelect
                                            FROM (SELECT * 
                                                  FROM autpagos
                                                  WHERE autpagos.fecreg BETWEEN '$inicio_semana 00:00:00' AND '$fin_semana 23:59:59' $filtroAutPagos) AS autpagos
                                            INNER JOIN (SELECT * 
                                                        FROM pagos_proveedor solpagos 
                                                        WHERE (solpagos.idsolicitud LIKE '%$idsolicitud%')
                                                              AND (solpagos.nomdepto LIKE '%$departamento%') ) AS solpagos 
                                                ON autpagos.idsolicitud = solpagos.idsolicitud 
                                            INNER JOIN proveedores
                                                ON proveedores.idproveedor = solpagos.idProveedor 
                                            INNER JOIN listado_usuarios capturista
                                                ON capturista.idusuario = solpagos.idusuario 
                                            INNER JOIN listado_usuarios responsable
                                                ON responsable.idusuario = solpagos.idResponsable 
                                            LEFT JOIN factura_registro facturas
                                                ON facturas.idsolicitud = solpagos.idsolicitud 
                                            INNER JOIN empresas
                                                ON empresas.idempresa = solpagos.idEmpresa 
                                            LEFT JOIN poliza_solicitud polizas_provision
                                                ON polizas_provision.idsolicitud = solpagos.idsolicitud 
                                            LEFT JOIN solicitud_proyecto_oficina spo
                                                ON spo.idsolicitud = solpagos.idsolicitud 
                                            LEFT JOIN proyectos_departamentos pd
                                                ON spo.idProyectos = pd.idProyectos
                                            LEFT JOIN oficina_sede os
                                                ON spo.idOficina = os.idOficina
                                            LEFT JOIN tipo_servicio_partidas tsp 
                                                ON tsp.idTipoServicioPartida = spo.idTipoServicioPartida
                                            LEFT JOIN facturas AS fac 
                                                ON autpagos.idfactura = fac.idfactura AND fac.tipo_factura = 2 -- FECHA: 28-AGOSTO-2024 | @author Dante Aldair Guerrero <coordinador6.desarrollo@ciudadmaderas.com>
                                            WHERE ( CONCAT(solpagos.folio,'/',SUBSTR(IFNULL(facturas.uuid,''), -1, 5)) LIKE '%$folio%')
                                                  AND (proveedores.nombre LIKE '%$proveedor%')
                                                  AND (empresas.abrev LIKE '%$empresa%')
                                                  AND ( CONCAT(solpagos.metoPago,' ', IFNULL(autpagos.referencia, '')) LIKE '%$meto_pago%')
                                            ORDER BY autpagos.fecreg DESC");
            break;
        }

        $departamentos = in_array($this->session->userdata("inicio_sesion")['depto'], array('CONSTRUCCION', 'JARDINERIA', 'OOAM', 'OOAM TECNICO', 'OOAM ADMINISTRATIVO'));
        $adminCP = in_array($this->session->userdata("inicio_sesion")['depto'], array('ADMINISTRACION')) && in_array($this->session->userdata("inicio_sesion")['rol'], array('CP')); /** FECHA: 22-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
        $rolContabilidad = in_array($this->session->userdata("inicio_sesion")['rol'], array('CC', 'CE', 'CT', 'CX')); /** FECHA: 26-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */

        $fileName = "REPORTE_PAGOS_A_PROV_DEVOL_TRASPASOS_" . $hoy['mday'] . "_" . $hoy['mon'] . "_" . $hoy['year'] . "_.xlsx";

        $encab = $departamentos 
            ? array('# SOLICITUD',
                    'PROYECTO',
                    'ETAPA',
                    'CONDOMINIO',
                    'FOLIO',
                    'FOLIO FISCAL',
                    'PROVEEDOR',
                    'F FACTURA',
                    'RESPONSABLE',
                    'F AUTORIZACIÓN',
                    'F DISPERSIÓN',
                    'F COBRO',
                    'EMPRESA',
                    'DEPARTAMENTO',
                    'CANTIDAD',
                    'METODO DE PAGO',
                    'REFERENCIA',
                    'ESTATUS',
                    'JUSTIFICACIÓN')
            : array('# SOLICITUD',
                    'PROYECTO',
                    'OFICINA',
                    'TIPO SERVICIO PARTIDA',
                    'ETAPA',
                    'CONDOMINIO',
                    'FOLIO',
                    'FOLIO FISCAL',
                    'PROVEEDOR',
                    'F FACTURA',
                    'RESPONSABLE',
                    'F AUTORIZACIÓN',
                    'F DISPERSIÓN',
                    'F COBRO',
                    'EMPRESA',
                    'DEPARTAMENTO',
                    'CANTIDAD',
                    'METODO DE PAGO',
                    'REFERENCIA',
                    'ESTATUS',
                    'JUSTIFICACIÓN');
            
        if ($adminCP && !$departamentos) { /** INICIO FECHA: 22-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
            $extra_encab = [
                'METODO PAGO',
                'UUID',
                'UUID COMPLEMENTO',
                'FACTURACIÓN'
            ];
            array_splice($encab, 8, 0, $extra_encab);
        } /** FIN FECHA: 22-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */

        if (!$departamento && $rolContabilidad) { /** INICIO FECHA: 26-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
            $extra_encab = [
                'MONEDA',
                'CONVERSIÓN (MXN)',
            ];
            array_splice($encab, 17, 0, $extra_encab);
        } /** INICIO FECHA: 26-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */

        if (in_array($this->session->userdata("inicio_sesion")['rol'], array('CP')) && $this->session->userdata("inicio_sesion")['depto'] == 'ADMINISTRACION' ) {
            $datosGoogle = [];
            $datosGoogle[]['encabezados'] = $encab;
            $datosGoogle[]['nombreArchivo'] = $fileName;

            // Estilo para cabecera y cuerpo
            $datosGoogle[]['estilosExcel'] = $this->session->userdata("inicio_sesion")['depto'] . '-' . $this->session->userdata("inicio_sesion")['rol'];

            $datosGoogle['datos'] = $query->result_array();

            $resultado = $this->exportarexcelgooglecloud->procesoArchivoExcel($datosGoogle, 'historial_pagos');

            // Enviar respuesta JSON y finalizar ejecución
            header('Content-Type: application/json');
            echo json_encode($resultado);
            exit;
        }else{
            $writer = WriterEntityFactory::createXLSXWriter();

            $writer->openToBrowser($fileName); // write data to a file or to a PHP stream
            // $writer->openToBrowser($fileName); // stream data directly to the browser

            $style = (new StyleBuilder())
                ->setFontBold()
                ->setFontSize(12)
                ->build();
            /** add a row at a time */
            $singleRow = WriterEntityFactory::createRowFromArray($encab, $style);
            $writer->addRow($singleRow);

            $estatus_text = '';

            if ($query->num_rows() > 0) {
                foreach ($query->result() as $row) {
                    switch ($row->estatus) {
                        case '0':
                            $estatus_text = 'AUTORIZADO POR DG';
                            break;
                        case '1':
                            break;
                        case '5':
                            $estatus_text = 'DISPERSANDO';
                            break;
                        case '11':
                            $estatus_text = 'EN ESPERA PARA ENVIAR A DISPERCION';
                            break;
                        case '12':
                            $estatus_text = 'PAGO DETENIDO';
                            break;
                        case '15':
                            $estatus_text = 'POR CONFIRMAR PAGO';
                            break;
                        case '16':
                            $estatus_text = 'PAGO COMPLETO';
                            break;
                        default:
                            $estatus_text = 'PROCESANDO PAGO CXP';
                            break;
                    }                
                    $cells =  $departamentos ? array(
                        $row->idsolicitud,
                        $row->proyecto,
                        $row->etapa,
                        $row->condominio,
                        $row->folio,
                        $row->uuid,
                        $row->nombre,
                        ( $row->fecreg_2 ? date("d/m/Y", strtotime($row->fecha_factura)) : ""),
                        $row->nombre_responsable,
                        ( $row->fecreg_2 ? date("d/m/Y", strtotime($row->fecreg_2)) : ""),
                        ( $row->fecha_dispersion ? date("d/m/Y", strtotime($row->fecha_dispersion)) : ""),
                        ( $row->fechaOpe ? date("d/m/Y", strtotime($row->fechaOpe)) : ""),
                        $row->abrev,
                        $row->nomdepto,
                        number_format($row->cantidad, 2, ".", ""),
                        $row->formaPago,
                        $row->referencia,
                        $estatus_text,
                        $row->justificacion) :
                            array(
                                $row->idsolicitud,
                                $row->proyecto,
                                $row->oficina,
                                $row->servicioPartida,
                                $row->etapa,
                                $row->condominio,
                                $row->folio,
                                $row->uuid,
                                $row->nombre,
                                ( $row->fecreg_2 ? date("d/m/Y", strtotime($row->fecha_factura)) : ""),
                                $row->nombre_responsable,
                                ( $row->fecreg_2 ? date("d/m/Y", strtotime($row->fecreg_2)) : ""),
                                ( $row->fecha_dispersion ? date("d/m/Y", strtotime($row->fecha_dispersion)) : ""),
                                ( $row->fechaOpe ? date("d/m/Y", strtotime($row->fechaOpe)) : ""),
                                $row->abrev,
                                $row->nomdepto,
                                number_format($row->cantidad, 2, ".", ""),
                                $row->formaPago,
                                $row->referencia,
                                $estatus_text,
                                $row->justificacion
                            );

                        if ($adminCP && !$departamentos) { /** INICIO FECHA: 22-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                            $extra_cells = [
                                $row->metodo_pago,
                                $row->uuid_xml,
                                $row->uuid_complemento,
                                $row->facturacion
                            ];
                            array_splice($cells, 8, 0, $extra_cells);
                        } /** FIN FECHA: 22-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */

                        if (!$departamento && $rolContabilidad) { /** INICIO FECHA: 26-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                            $extra_cells = [
                                $row->moneda,
                                number_format($row->conversion, 2, ".", ""),
                            ];
                            array_splice($cells, 17, 0, $extra_cells);
                        } /** FIN FECHA: 26-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                    $singleRow = WriterEntityFactory::createRowFromArray($cells);
                    $writer->addRow($singleRow);

                }
            }
            $writer->close();
        }

    }

    // REPORTE DE SOLICITUDES ACTIVAS Y PAGADAS DA, CJ, AS, CA, CP
    public function solicitante_solPago_solActivas(){
        //FILTRO POR ETAPAS SEGUN USUARIO DE ASISTENTE DE CONSTRUCCION
        // print_r($this->session->userdata("inicio_sesion"));
        // exit;
        $filtro_etapas = '';
        if($this->session->userdata("inicio_sesion")['rol'] == 'AS' && $this->session->userdata("inicio_sesion")['depto'] == "CONSTRUCCION"){
            $filtro_etapas = 'idetapa NOT IN ( 0, 1, 3, 4, 6, 8, 21, 25, 30, 31, 11, 10, 9)';
        }
        // Obtener la fecha actual
        $hoy = getdate();

        // Variable para la consulta
        $query = '';
        $filtro_fechas = false;

        $inicio_semana = '2000-01-01';
        $fin_semana = ($hoy['year'] + 1). "-" . $hoy['mon'] . "-" . ($hoy['mday'] + 1);

        if ($this->input->post("fechaInicial") && $this->input->post("fechaFinal")) {
            //FECHAS PARA EL RANGO
            $filtro_fechas = true;
            $inicio_semana = implode("-", array_reverse(explode("/", $this->input->post("fechaInicial"))));
            $fin_semana = implode("-", array_reverse(explode("/", $this->input->post("fechaFinal"))));
        }
        $filtro_fechas = ($filtro_fechas?"true":"false");

        // Encabezados de las tablas para los filtros del reporte
        $idsolicitud = $this->input->post("#") ? $this->input->post("#") : '';
        $empresa = $this->input->post("EMPRESA") ? $this->input->post("EMPRESA") : '';
        $proyecto = $this->input->post("PROYECTO") ? $this->input->post("PROYECTO") : '';
        $hclave = $this->input->post("HCLAVE") ? $this->input->post("HCLAVE") : '';
        $folio = $this->input->post("FOLIO") ? $this->input->post("FOLIO") : '';
        $proveedor = $this->input->post("PROVEEDOR") ? $this->input->post("PROVEEDOR") : '';
        $fec_factura = $this->input->post("FEC_FAC") ? fechaSinFormato($this->input->post("FEC_FAC")) : '';
        $captura = $this->input->post("CAPTURA") ? $this->input->post("CAPTURA") : '';
        $cantidad = $this->input->post("CANTIDAD") ?  str_replace(',', '', $this->input->post("CANTIDAD")) : '';
        $pagado = $this->input->post("PAGADO") ?  str_replace(',', '', $this->input->post("PAGADO")) : '';
        $restante = $this->input->post("RESTANTE") ?  str_replace(',', '', $this->input->post("RESTANTE")) : '';
        $estatus = $this->input->post("ESTATUS") ? $this->input->post("ESTATUS") : '';
        $metopago= $this->input->post("FORMA_PAGO");
        $reporte_solicitado = $this->input->post("tipo_reporte");
        $departamentosExcepcion = in_array($this->session->userdata("inicio_sesion")['depto'], array('CONSTRUCCION', 'JARDINERIA', 'OOAM', 'OOAM TECNICO', 'OOAM ADMINISTRATIVO'));

        ini_set('memory_limit', '-1');
        set_time_limit(0);

        if( $reporte_solicitado && in_array( $reporte_solicitado, ["#historial_activas_prov", "#historial_activas_cch", "#historial_activas_tdc", "#historial_activas_viaticos"] ) ){
            
            $pagos_proveedor = "";

            if( in_array( $reporte_solicitado, ["#historial_activas_prov"] )){
                $filtros_reporte = "LEFT JOIN ( SELECT idsolicitud, nombre_contrato, NULL insumo FROM lcontratos ) especial ON especial.idsolicitud = solpagos.idsolicitud";
                $tipo_gasto = "AND solpagos.caja_chica = 0";
                $pagos_proveedor = "SELECT idsolicitud, tpagos, fechaDis, pagado, estatus_pago, upago, fecha_cobro, etapa_pago FROM vw_autpagos";
                $responsable = "CROSS JOIN ( SELECT idusuario, nombre_completo nresponsable from listado_usuarios ) responsable ON responsable.idusuario = solpagos.idResponsable";
            }

            if( in_array( $reporte_solicitado, ["#historial_activas_cch"] )){
                $filtros_reporte = "LEFT JOIN ( SELECT idinsumo, NULL nombre_contrato, insumo FROM insumos ) especial ON especial.idinsumo = solpagos.servicio";
                $tipo_gasto = "AND solpagos.caja_chica IN (1,3)"; /** FECHA: 02-ENERO-2025 @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                $pagos_proveedor = "SELECT idsolicitud, tpagos, fechaDis, cantidad pagado, estatus_pago, upago, fecha_cobro, etapa_pago FROM vw_autpagos_caja_chica";
                $responsable = "CROSS JOIN ( SELECT idusuario, nombre_completo nresponsable from listado_usuarios ) responsable ON responsable.idusuario = solpagos.idResponsable";
            }

            if( in_array( $reporte_solicitado, ["#historial_activas_tdc"] )){
                $filtros_reporte = "LEFT JOIN ( SELECT idinsumo, NULL nombre_contrato, insumo FROM insumos ) especial ON especial.idinsumo = solpagos.servicio";
                $tipo_gasto = "AND solpagos.caja_chica = 2";
                $pagos_proveedor = "SELECT idsolicitud, tpagos, fechaDis, cantidad pagado, estatus_pago, upago, fecha_cobro, etapa_pago FROM  vw_autpagos_caja_chica";
                $responsable = "CROSS JOIN ( SELECT idtcredito idusuario, nombre_completo nresponsable from lista_rtdc ) responsable ON responsable.idusuario = solpagos.idResponsable";
            }
            if( in_array( $reporte_solicitado, ["#historial_activas_viaticos"] )){
                $filtros_reporte = "LEFT JOIN ( SELECT idinsumo, NULL nombre_contrato, insumo FROM insumos ) especial ON especial.idinsumo = solpagos.servicio";
                $tipo_gasto = "AND solpagos.caja_chica = 4";
                $pagos_proveedor = "SELECT idsolicitud, tpagos, fechaDis, cantidad pagado, estatus_pago, upago, fecha_cobro, etapa_pago FROM vw_autpagos_caja_chica";
                $responsable = "CROSS JOIN ( SELECT idusuario, nombre_completo nresponsable from listado_usuarios ) responsable ON responsable.idusuario = solpagos.idResponsable";
            }

        }else{
            $filtros_reporte = "";
            $tipo_gasto = "";
            $pagos_proveedor = "SELECT idsolicitud, tpagos, fechaDis, pagado, estatus_pago, upago, fecha_cobro, etapa_pago FROM vw_autpagos
            UNION
            SELECT idsolicitud, tpagos, fechaDis, cantidad pagado, estatus_pago, upago, fecha_cobro, etapa_pago FROM vw_autpagos_caja_chica";
            $responsable = "CROSS JOIN 
            (
                SELECT *, 0 tresp from listado_usuarios
                UNION
                SELECT *, 1 tresp from lista_rtdc
            ) responsable ON responsable.idusuario = solpagos.idResponsable AND ( ( solpagos.caja_chica = 2 AND responsable.tresp = 1 ) OR ( ( solpagos.caja_chica IN ( 0, 1 ) OR solpagos.caja_chica IS NULL  ) AND responsable.tresp = 0 ) )";
        }

        switch ($this->session->userdata("inicio_sesion")['rol']) {
            case 'DA':
            case 'AS':
                $filtro_etapas != '' ? $filtro_etapas : $filtro_etapas = 'idetapa NOT IN ( 0, 1, 12, 3, 4, 6, 8, 21, 25, 30, 9, 49, 11, 10 )';
                $filtro_etapas != '' ? $filtro_etapas2 = 'idetapa NOT IN ( 0, 1, 2, 3, 4, 5, 7, 47, 6, 8, 21, 25, 30, 31 )' : $filtro_etapas2 = 'idetapa NOT IN ( 0, 1, 12, 3, 4, 6, 8, 21, 25, 30, 9, 49, 11, 10 )';
                $query = "SELECT solpagos.*, facturas.uuid, NULL tpagos, NULL fechaDis, NULL pagado, NULL estatus_pago, NULL upago, NULL fecha_cobro, especial.insumo, especial.nombre_contrato FROM (
                    SELECT * 
                    FROM solpagos
                    WHERE $filtro_etapas
                    AND solpagos.programado IS NULL
                    AND (solpagos.idsolicitud LIKE '%$idsolicitud%') 
                    AND (solpagos.fecelab LIKE '%$fec_factura%')
                    AND solpagos.metoPago like '%$metopago%' 
                    AND solpagos.fechaCreacion BETWEEN '$inicio_semana' AND '$fin_semana'
                    $tipo_gasto
                ) solpagos
                JOIN ( SELECT departamento depto FROM departament_usuario WHERE idusuario = ? ) depto ON solpagos.nomdepto = depto.depto
                LEFT JOIN ( SELECT idsolicitud, uuid FROM factura_registro ) facturas ON facturas.idsolicitud = solpagos.idsolicitud 
                $filtros_reporte
                UNION
                SELECT solpagos.*, facturas.uuid, cant_pag.tpagos, cant_pag.fechaDis, cant_pag.pagado, cant_pag.estatus_pago, cant_pag.upago, cant_pag.fecha_cobro, especial.insumo, especial.nombre_contrato FROM (
                    SELECT * 
                    FROM solpagos
                    WHERE $filtro_etapas2
                    AND solpagos.programado IS NULL
                    AND (solpagos.idsolicitud LIKE '%$idsolicitud%') 
                    AND (solpagos.fecelab LIKE '%$fec_factura%')
                    AND solpagos.metoPago like '%$metopago%' 
                    AND solpagos.fechaCreacion BETWEEN '$inicio_semana' AND '$fin_semana'
                    $tipo_gasto
                ) solpagos 
                JOIN ( SELECT departamento depto FROM departament_usuario WHERE idusuario = ? ) depto ON solpagos.nomdepto = depto.depto
                LEFT JOIN ( SELECT idsolicitud, uuid FROM factura_registro ) facturas ON facturas.idsolicitud = solpagos.idsolicitud 
                $filtros_reporte
                CROSS JOIN ( $pagos_proveedor ) cant_pag ON cant_pag.idsolicitud = solpagos.idsolicitud                         
                UNION
                SELECT solpagos.*, facturas.uuid, NULL tpagos, NULL fechaDis, NULL pagado, NULL estatus_pago, NULL upago, NULL fecha_cobro, especial.insumo, especial.nombre_contrato  FROM (
                    SELECT * 
                    FROM solpagos
                    WHERE $filtro_etapas
                    AND solpagos.programado IS NULL
                    AND (solpagos.idsolicitud LIKE '%$idsolicitud%') 
                    AND (solpagos.fecelab LIKE '%$fec_factura%')
                    AND solpagos.metoPago like '%$metopago%' 
                    AND solpagos.fechaCreacion BETWEEN '$inicio_semana' AND '$fin_semana'
                    $tipo_gasto
                ) solpagos 
                JOIN proveedores_usuario p ON p.idproveedor = solpagos.idproveedor AND solpagos.nomdepto = p.nomdepto AND p.idusuario = ?
                LEFT JOIN ( SELECT idsolicitud, uuid FROM factura_registro ) facturas ON facturas.idsolicitud = solpagos.idsolicitud 
                $filtros_reporte
                UNION
                SELECT solpagos.*, facturas.uuid, cant_pag.tpagos, cant_pag.fechaDis, cant_pag.pagado, cant_pag.estatus_pago, cant_pag.upago, cant_pag.fecha_cobro, especial.insumo, especial.nombre_contrato  FROM (
                    SELECT * 
                    FROM solpagos
                    WHERE $filtro_etapas2
                    AND solpagos.programado IS NULL
                    AND (solpagos.idsolicitud LIKE '%$idsolicitud%') 
                    AND (solpagos.fecelab LIKE '%$fec_factura%')
                    AND solpagos.metoPago like '%$metopago%' 
                    AND solpagos.fechaCreacion BETWEEN '$inicio_semana' AND '$fin_semana'
                    $tipo_gasto
                ) solpagos 
                JOIN proveedores_usuario p ON p.idproveedor = solpagos.idproveedor AND solpagos.nomdepto = p.nomdepto AND p.idusuario = ?
                LEFT JOIN ( SELECT idsolicitud, uuid FROM factura_registro ) facturas ON facturas.idsolicitud = solpagos.idsolicitud 
                $filtros_reporte
                CROSS JOIN ( $pagos_proveedor ) cant_pag ON cant_pag.idsolicitud = solpagos.idsolicitud
                UNION
                SELECT solpagos.*, facturas.uuid, NULL tpagos, NULL fechaDis, NULL pagado, NULL estatus_pago, NULL upago, NULL fecha_cobro, especial.insumo, especial.nombre_contrato FROM (
                    SELECT * 
                    FROM solpagos
                    WHERE $filtro_etapas
                    AND solpagos.programado IS NOT NULL
                    AND (solpagos.idsolicitud LIKE '%$idsolicitud%') 
                    AND (solpagos.fecelab LIKE '%$fec_factura%')
                    AND solpagos.metoPago like '%$metopago%' 
                    AND solpagos.fechaCreacion BETWEEN '$inicio_semana' AND '$fin_semana'
                    $tipo_gasto
                ) solpagos
                JOIN ( SELECT departamento depto FROM departament_usuario WHERE idusuario = ? ) depto ON solpagos.nomdepto = depto.depto
                LEFT JOIN ( SELECT idsolicitud, uuid FROM factura_registro ) facturas ON facturas.idsolicitud = solpagos.idsolicitud 
                $filtros_reporte
                UNION
                SELECT solpagos.*, facturas.uuid, cant_pag.tpagos, cant_pag.fechaDis, cant_pag.pagado, cant_pag.estatus_pago, cant_pag.upago, cant_pag.fecha_cobro, especial.insumo, especial.nombre_contrato FROM (
                    SELECT * 
                    FROM solpagos
                    WHERE $filtro_etapas2
                    AND solpagos.programado IS NOT NULL
                    AND (solpagos.idsolicitud LIKE '%$idsolicitud%') 
                    AND (solpagos.fecelab LIKE '%$fec_factura%')
                    AND solpagos.metoPago like '%$metopago%' 
                    AND solpagos.fechaCreacion BETWEEN '$inicio_semana' AND '$fin_semana'
                    $tipo_gasto
                ) solpagos 
                JOIN ( SELECT departamento depto FROM departament_usuario WHERE idusuario = ? ) depto ON solpagos.nomdepto = depto.depto
                LEFT JOIN ( SELECT idsolicitud, uuid FROM factura_registro ) facturas ON facturas.idsolicitud = solpagos.idsolicitud 
                $filtros_reporte
                CROSS JOIN ( $pagos_proveedor ) cant_pag ON cant_pag.idsolicitud = solpagos.idsolicitud";
                $variables = [
                    $this->session->userdata("inicio_sesion")['id'], 
                    $this->session->userdata("inicio_sesion")['id'], 
                    $this->session->userdata("inicio_sesion")['id'], 
                    $this->session->userdata("inicio_sesion")['id'], 
                    $this->session->userdata("inicio_sesion")['id'], 
                    $this->session->userdata("inicio_sesion")['id']
                ];

                //SOLO APLICA PARA OOAM ADMINISTRATIVO TODOS LOS GASTOS QUE LOS OTROS DEPARTAMENTOS HACEN PARA OOAM
                if( $this->session->userdata("inicio_sesion")['depto'] == 'OOAM ADMINISTRATIVO' ){
                    $query .= " UNION SELECT solpagos.*, facturas.uuid, NULL tpagos, NULL fechaDis, NULL pagado, NULL estatus_pago, NULL upago, NULL fecha_cobro, especial.insumo, especial.nombre_contrato
                    FROM ( 
                        SELECT * 
                        FROM solpagos 
                        WHERE idetapa NOT IN ( 0, 1, 12, 3, 4, 6, 8, 21, 25, 30, 9, 49, 11, 10 )
                        AND solpagos.programado IS NULL
                        AND proyecto LIKE '%OOAM%' AND nomdepto NOT LIKE '%OOAM%'
                        AND solpagos.fechaCreacion BETWEEN '$inicio_semana' AND '$fin_semana'
                        $tipo_gasto
                    ) solpagos
                    LEFT JOIN ( SELECT idsolicitud, uuid FROM factura_registro ) facturas ON facturas.idsolicitud = solpagos.idsolicitud
                    $filtros_reporte
                    UNION SELECT solpagos.*, facturas.uuid, cant_pag.tpagos, cant_pag.fechaDis, cant_pag.pagado, cant_pag.estatus_pago, cant_pag.upago, cant_pag.fecha_cobro, especial.insumo, especial.nombre_contrato
                    FROM ( 
                        SELECT * 
                        FROM solpagos 
                        WHERE idetapa NOT IN ( 0, 1, 12, 2, 3, 4, 5, 7, 47, 6, 8, 21, 25, 30, 31 )
                        AND solpagos.programado IS NULL
                        AND proyecto LIKE '%OOAM%' AND nomdepto NOT LIKE '%OOAM%'
                        AND solpagos.fechaCreacion BETWEEN '$inicio_semana' AND '$fin_semana'
                        $tipo_gasto
                    ) solpagos
                    LEFT JOIN ( SELECT idsolicitud, uuid FROM factura_registro ) facturas ON facturas.idsolicitud = solpagos.idsolicitud
                    CROSS JOIN ( $pagos_proveedor ) cant_pag ON cant_pag.idsolicitud = solpagos.idsolicitud
                    $filtros_reporte";
                }
                break;
            case 'CP':
            case 'CA':
                $query = "SELECT solpagos.*, facturas.uuid, NULL tpagos, NULL fechaDis, NULL pagado, NULL estatus_pago, NULL upago, NULL fecha_cobro, especial.insumo, especial.nombre_contrato FROM (
                    SELECT * 
                    FROM solpagos
                    WHERE idetapa NOT IN ( 0, 1, 12, 3, 4, 6, 8, 21, 25, 30, 9, 49, 11, 10 )
                    AND solpagos.idusuario != ?
                    AND (solpagos.idsolicitud LIKE '%$idsolicitud%') 
                    AND (solpagos.fecelab LIKE '%$fec_factura%')
                    AND solpagos.metoPago like '%$metopago%' 
                    AND solpagos.fechaCreacion BETWEEN '$inicio_semana' AND '$fin_semana'
                    $tipo_gasto
                ) solpagos
                JOIN usuarios ON solpagos.idusuario = usuarios.idusuario AND NOT FIND_IN_SET( ?, usuarios.sup ) 
                JOIN ( SELECT departamento depto FROM departament_usuario_2 WHERE idusuario = ? ) depto ON solpagos.nomdepto = depto.depto
                LEFT JOIN ( SELECT idsolicitud, uuid FROM factura_registro ) facturas ON facturas.idsolicitud = solpagos.idsolicitud 
                $filtros_reporte
                UNION
                SELECT solpagos.*, facturas.uuid, cant_pag.tpagos, cant_pag.fechaDis, cant_pag.pagado, cant_pag.estatus_pago, cant_pag.upago, cant_pag.fecha_cobro, especial.insumo, especial.nombre_contrato FROM (
                    SELECT * 
                    FROM solpagos
                    WHERE idetapa NOT IN ( 0, 1, 12, 3, 2, 4, 5, 7, 47, 6, 8, 21, 25, 30, 31 )
                    AND solpagos.idusuario != ?
                    AND (solpagos.idsolicitud LIKE '%$idsolicitud%') 
                    AND (solpagos.fecelab LIKE '%$fec_factura%')
                    AND solpagos.metoPago like '%$metopago%' 
                    AND solpagos.fechaCreacion BETWEEN '$inicio_semana' AND '$fin_semana'
                    $tipo_gasto
                ) solpagos 
                JOIN usuarios ON solpagos.idusuario = usuarios.idusuario AND NOT FIND_IN_SET( ?, usuarios.sup ) 
                JOIN ( SELECT departamento depto FROM departament_usuario_2 WHERE idusuario = ? ) depto ON solpagos.nomdepto = depto.depto
                LEFT JOIN ( SELECT idsolicitud, uuid FROM factura_registro ) facturas ON facturas.idsolicitud = solpagos.idsolicitud 
                $filtros_reporte
                CROSS JOIN ( $pagos_proveedor ) cant_pag ON cant_pag.idsolicitud = solpagos.idsolicitud
                UNION
                SELECT solpagos.*, facturas.uuid, NULL tpagos, NULL fechaDis, NULL pagado, NULL estatus_pago, NULL upago, NULL fecha_cobro, especial.insumo, especial.nombre_contrato FROM (
                    SELECT * 
                    FROM solpagos
                    WHERE idetapa NOT IN ( 0, 1, 12, 3, 4, 6, 8, 21, 25, 30, 9, 49, 11, 10 )
                    AND solpagos.idusuario = ?
                    AND (solpagos.idsolicitud LIKE '%$idsolicitud%') 
                    AND (solpagos.fecelab LIKE '%$fec_factura%')
                    AND solpagos.metoPago like '%$metopago%' 
                    AND solpagos.fechaCreacion BETWEEN '$inicio_semana' AND '$fin_semana'
                    $tipo_gasto
                ) solpagos
                LEFT JOIN ( SELECT idsolicitud, uuid FROM factura_registro ) facturas ON facturas.idsolicitud = solpagos.idsolicitud 
                $filtros_reporte
                UNION
                SELECT solpagos.*, facturas.uuid, cant_pag.tpagos, cant_pag.fechaDis, cant_pag.pagado, cant_pag.estatus_pago, cant_pag.upago, cant_pag.fecha_cobro, especial.insumo, especial.nombre_contrato FROM (
                    SELECT * 
                    FROM solpagos
                    WHERE idetapa NOT IN ( 0, 1, 12, 3, 2, 4, 5, 7, 47, 6, 8, 21, 25, 30, 31 )
                    AND solpagos.idusuario = ?
                    AND (solpagos.idsolicitud LIKE '%$idsolicitud%') 
                    AND (solpagos.fecelab LIKE '%$fec_factura%')
                    AND solpagos.metoPago like '%$metopago%' 
                    AND solpagos.fechaCreacion BETWEEN '$inicio_semana' AND '$fin_semana'
                    $tipo_gasto
                ) solpagos 
                LEFT JOIN ( SELECT idsolicitud, uuid FROM factura_registro ) facturas ON facturas.idsolicitud = solpagos.idsolicitud 
                $filtros_reporte
                CROSS JOIN ( $pagos_proveedor ) cant_pag ON cant_pag.idsolicitud = solpagos.idsolicitud
                UNION
                SELECT solpagos.*, facturas.uuid, NULL tpagos, NULL fechaDis, NULL pagado, NULL estatus_pago, NULL upago, NULL fecha_cobro, especial.insumo, especial.nombre_contrato FROM (
                    SELECT solpagos.* 
                    FROM solpagos
                    JOIN usuarios ON solpagos.idusuario = usuarios.idusuario AND FIND_IN_SET( ?, usuarios.sup ) 
                    WHERE idetapa NOT IN ( 0, 1, 12, 3, 4, 6, 8, 21, 25, 30, 9, 49, 11, 10 )
                    AND (solpagos.idsolicitud LIKE '%$idsolicitud%') 
                    AND (solpagos.fecelab LIKE '%$fec_factura%')
                    AND solpagos.metoPago like '%$metopago%' 
                    AND solpagos.fechaCreacion BETWEEN '$inicio_semana' AND '$fin_semana'
                    $tipo_gasto
                ) solpagos
                LEFT JOIN ( SELECT idsolicitud, uuid FROM factura_registro ) facturas ON facturas.idsolicitud = solpagos.idsolicitud 
                $filtros_reporte
                UNION
                SELECT solpagos.*, facturas.uuid, cant_pag.tpagos, cant_pag.fechaDis, cant_pag.pagado, cant_pag.estatus_pago, cant_pag.upago, cant_pag.fecha_cobro, especial.insumo, especial.nombre_contrato FROM (
                    SELECT solpagos.* 
                    FROM solpagos
                    JOIN usuarios ON solpagos.idusuario = usuarios.idusuario AND FIND_IN_SET( ?, usuarios.sup ) 
                    WHERE idetapa NOT IN ( 0, 1, 12, 3, 2, 4, 5, 7, 47, 6, 8, 21, 25, 30, 31 )
                    AND (solpagos.idsolicitud LIKE '%$idsolicitud%') 
                    AND (solpagos.fecelab LIKE '%$fec_factura%')
                    AND solpagos.metoPago like '%$metopago%' 
                    AND solpagos.fechaCreacion BETWEEN '$inicio_semana' AND '$fin_semana'
                    $tipo_gasto
                ) solpagos 
                LEFT JOIN ( SELECT idsolicitud, uuid FROM factura_registro ) facturas ON facturas.idsolicitud = solpagos.idsolicitud 
                $filtros_reporte
                CROSS JOIN ( $pagos_proveedor ) cant_pag ON cant_pag.idsolicitud = solpagos.idsolicitud";
                $variables = [
                    $this->session->userdata("inicio_sesion")['id'],
                    $this->session->userdata("inicio_sesion")['id'],
                    $this->session->userdata("inicio_sesion")['id'],
                    $this->session->userdata("inicio_sesion")['id'],
                    $this->session->userdata("inicio_sesion")['id'],
                    $this->session->userdata("inicio_sesion")['id'],
                    $this->session->userdata("inicio_sesion")['id'],
                    $this->session->userdata("inicio_sesion")['id'],
                    $this->session->userdata("inicio_sesion")['id'],
                    $this->session->userdata("inicio_sesion")['id']
                ];
                break;
            default:
                die("HA OCURRIDO UN ERROR");
                break;
        }

        if( in_array( $this->session->userdata("inicio_sesion")['rol'], [ 'DA', 'AS', 'CA', 'CP' ] ) ){
            $query = $this->db->query("SELECT
                solpagos.idsolicitud,
                empresas.abrev,
                ifnull(solpagos.proyecto, pd.nombre) as proyecto,
                solpagos.etapa soletapa,
                solpagos.condominio,
                solpagos.homoclave,
                solpagos.folio,
                IFNULL(SUBSTRING(solpagos.uuid, - 5), 'SF') foliofisc,
                CASE 
                    WHEN solpagos.caja_chica = 1 THEN 'CAJA CHICA' 
                    WHEN solpagos.caja_chica = 2 THEN 'PAGO TDC'
                    WHEN solpagos.caja_chica = 3 THEN 'REEMBOLSO' /** FECHA: 02-ENERO-2025 @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                    WHEN solpagos.caja_chica = 4 THEN 'VIÁTICOS'
                    ELSE 'PAGO PROVEEDOR' 
                END tpago,
                solpagos.insumo,
                solpagos.nomdepto,
                proveedores.nombre,
                solpagos.orden_compra,
                solpagos.nombre_contrato,
                solpagos.justificacion,
                solpagos.fechaCreacion,
                solpagos.fecelab,
                solpagos.fecha_autorizacion,
                capturista.nombre_completo,
                responsable.nresponsable,
                IF(solpagos.programado IS NOT NULL,
                    IF(solpagos.idetapa NOT IN (11 , 30, 0),
                        solpagos.cantidad,
                        0) + IFNULL(solpagos.pagado, 0),
                    solpagos.cantidad) cantidad,
                solpagos.moneda,
                solpagos.metoPago,
                solpagos.descuento,
                IFNULL(solpagos.pagado, 0) pagado,
                solpagos.fechaDis fechaDis,
                etapas.nombre etapa,
                IFNULL(solpagos.uuid, 'SF') uuid,
                solpagos.crecibo,
                IF(solpagos.financiamiento = 0, 'NA', 'SI' ) financiamiento,
                os.nombre oficina,
                tsp.nombre servicioPartida, -- INICIO FECHA: 25-SEPTIEMBRE-2024 @author Angel Victoriano <programador.analista30@ciudadmaderas.com>
                IFNULL(responsable_cch.nombre_reembolso_cch, 'NA') nombre_reembolso_cch, -- INICIO FECHA: 25-SEPTIEMBRE-2024 @author Angel Victoriano <programador.analista30@ciudadmaderas.com>
                IFNULL(tarjeta.titular_nombre, 'NA') as titular_nombre -- INICIO FECHA: 25-SEPTIEMBRE-2024 @author Angel Victoriano <programador.analista30@ciudadmaderas.com>
            FROM
                ( 
                    SELECT 
                        solpagos.idsolicitud, 
                        solpagos.proyecto, 
                        solpagos.etapa, 
                        solpagos.condominio, 
                        solpagos.folio, 
                        solpagos.homoclave, 
                        solpagos.fechaCreacion, 
                        solpagos.fecha_autorizacion, 
                        solpagos.fecelab, 
                        solpagos.orden_compra, 
                        solpagos.nomdepto, 
                        solpagos.programado, 
                        solpagos.idetapa, 
                        solpagos.cantidad, 
                        solpagos.moneda, 
                        solpagos.descuento, 
                        solpagos.caja_chica, 
                        solpagos.metoPago, 
                        solpagos.justificacion, 
                        solpagos.crecibo, 
                        solpagos.idusuario, 
                        solpagos.idResponsable, 
                        solpagos.idEmpresa, 
                        solpagos.idProveedor, 
                        solpagos.financiamiento, 
                        solpagos.uuid, 
                        solpagos.tpagos, 
                        solpagos.fechaDis, 
                        solpagos.pagado, 
                        solpagos.estatus_pago, 
                        solpagos.upago, 
                        solpagos.fecha_cobro, 
                        solpagos.insumo, 
                        solpagos.nombre_contrato
                    FROM (
                        $query
                    ) solpagos
                ) solpagos
                CROSS JOIN listado_usuarios capturista ON capturista.idusuario = solpagos.idusuario
                $responsable
                CROSS JOIN ( SELECT idempresa, abrev FROM empresas WHERE empresas.abrev LIKE '%$empresa%' ) empresas ON solpagos.idEmpresa = empresas.idempresa
                CROSS JOIN ( SELECT idProveedor, nombre FROM proveedores WHERE proveedores.nombre LIKE '%$proveedor%' ) proveedores ON proveedores.idproveedor = solpagos.idProveedor
                CROSS JOIN ( SELECT idetapa, nombre FROM etapas ) etapas ON etapas.idetapa = solpagos.idetapa
                LEFT JOIN solicitud_proyecto_oficina spo on spo.idsolicitud = solpagos.idsolicitud
            	LEFT JOIN proyectos_departamentos pd on spo.idProyectos = pd.idProyectos 
                LEFT JOIN oficina_sede os on spo.idOficina = os.idOficina
                LEFT JOIN tipo_servicio_partidas tsp on tsp.idTipoServicioPartida = spo.idTipoServicioPartida -- INICIO FECHA: 25-SEPTIEMBRE-2024 @author Angel Victoriano <programador.analista30@ciudadmaderas.com>
                LEFT JOIN tcredito tdc ON solpagos.idResponsable = tdc.idtcredito AND solpagos.caja_chica = 2 -- FECHA: 25-SEPTIEMBRE-2024 @author Angel Victoriano <programador.analista30@ciudadmaderas.com>
                LEFT JOIN ( SELECT usuarios.idusuario, CONCAT( usuarios.nombres,' ', usuarios.apellidos ) AS titular_nombre FROM usuarios ) AS tarjeta ON tarjeta.idusuario = tdc.idtitular AND solpagos.caja_chica = 2 -- INICIO FECHA: 25-SEPTIEMBRE-2024 @author Angel Victoriano <programador.analista30@ciudadmaderas.com>
                LEFT JOIN (
                        SELECT
                            cajas_ch.idusuario
                            , GROUP_CONCAT(cajas_ch.nombre_reembolso_ch SEPARATOR ', ') nombre_reembolso_cch
                        FROM cajas_ch
                        GROUP BY cajas_ch.idusuario
                ) AS responsable_cch ON responsable_cch.idusuario = solpagos.idResponsable AND (solpagos.caja_chica = 1 OR solpagos.caja_chica = 4) -- FIN FECHA: 25-SEPTIEMBRE-2024 @author Angel Victoriano <programador.analista30@ciudadmaderas.com>
                ORDER BY solpagos.fecelab ASC, fechaCreacion", $variables );
        }

        $writer = WriterEntityFactory::createXLSXWriter();

        $fileName = "REPORTE_PAGO_FACTURAS_ACTIVAS_" . $hoy['mday'] . "_" . $hoy['mon'] . "_" . $hoy['year'] . "_.xlsx";
        $writer->openToBrowser($fileName);

        $style = (new StyleBuilder())
            ->setFontBold()
            ->setFontSize(12)
            ->build();

        $encab = $departamentosExcepcion ? array(
            '#',
            'EMPRESA',
            'PROYECTO',
            'OFICINA',
            'SERVICIO PARTIDA',
            'ETAPA',
            'CONDOMINIO',
            'HOMOCLAVE',
            'FOLIO',
            'TIPO DE GASTO',
            'INSUMO',
            'DEPARTAMENTO',
            'USUARIO (CAPTURISTA)',
            'RESPONSABLE',
            'PROVEEDOR',
            'ORDEN DE COMPRA',
            'CONTRATO',
            'JUSTIFICACIÓN',
            'FEC CAPTURA',
            'FEC FACTURA',
            'AUTORIZACIÓN',
            'CANTIDAD',
            'MONEDA',
            'FORMA DE PAGO',
            'INTERCAMBIO',
            'PAGADO',
            'FEC PAGO',
            'RESTANTE',
            'ESTATUS',
            'UUID',
            'CONTRA RECIBO',
            'FINANCIAMIENTO')
        : array('#',
            'EMPRESA',
            'PROYECTO',
            'OFICINA',
            'SERVICIO PARTIDA',
            'ETAPA',
            'CONDOMINIO',
            'HOMOCLAVE',
            'FOLIO',
            'TIPO DE GASTO',
            'INSUMO',
            'DEPARTAMENTO',
            'CAPTURISTA',
            'RESPONSABLE',
            'PROVEEDOR',
            'ORDEN DE COMPRA',
            'CONTRATO',
            'JUSTIFICACIÓN',
            'FEC CAPTURA',
            'FEC FACTURA',
            'AUTORIZACIÓN',
            'CANTIDAD',
            'MONEDA',
            'FORMA DE PAGO',
            'INTERCAMBIO',
            'PAGADO',
            'FEC PAGO',
            'RESTANTE',
            'ESTATUS',
            'UUID',
            'CONTRA RECIBO',
            'FINANCIAMIENTO');

        if ($this->session->userdata("inicio_sesion")['rol'] == 'CP' && $this->session->userdata("inicio_sesion")['depto'] == 'CI-COMPRAS') {
            $indice = array_search('CAPTURISTA', $encab); // Busca el índice de "CAPTURISTA"
            if ($indice !== false) {
                $encab[$indice] = 'USUARIO'; // Reemplaza "CAPTURISTA" por "USUARIO"
            }
        }
        
        if (($this->session->userdata("inicio_sesion")['rol'] == 'CP' && $this->session->userdata("inicio_sesion")['depto'] == 'CI-COMPRAS') && in_array( $reporte_solicitado, ["#historial_activas_cch", "#historial_activas_viaticos"] )) {
            $extra_encab = [
                'RESPONSABLE REEMBOLSO'
            ];

            if($departamentosExcepcion){
                array_splice($encab, 12, 0, $extra_encab);
            }else{
                array_splice($encab, 14, 0, $extra_encab);
            }
        }

        if (in_array( $reporte_solicitado, ["#historial_activas_tdc"] )) {
            $extra_encab = [
                'TITULAR TARJETA'
            ];

            if($departamentosExcepcion){
                array_splice($encab, 12, 0, $extra_encab);
            }else{
                array_splice($encab, 14, 0, $extra_encab);
            }
        }

        $singleRow = WriterEntityFactory::createRowFromArray($encab, $style);
        $writer->addRow($singleRow);

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $cells = $departamentosExcepcion ? array(
                    ($row->idsolicitud),
                    $row->abrev,
                    $row->proyecto,
                    $row->oficina,
                    $row->servicioPartida,
                    $row->soletapa,
                    $row->condominio,
                    $row->homoclave,
                    $row->folio.'/'.$row->foliofisc,
                    $row->tpago,
                    $row->insumo,
                    $row->nomdepto,
                    $row->nombre_completo, //Usuario (capturista)
                    $row->nresponsable,
                    $row->nombre,
                    $row->orden_compra,
                    $row->nombre_contrato,
                    trim($row->justificacion),
                    $row->fechaCreacion,
                    $row->fecelab,
                    $row->fecha_autorizacion,
                    $row->cantidad,
                    $row->moneda,
                    $row->metoPago,
                    $row->descuento,
                    $row->pagado,
                    $row->fechaDis,
                    ($row->cantidad - $row->pagado),
                    $row->etapa,
                    $row->uuid,
                    $row->crecibo,
                    $row->financiamiento)
                : array($row->idsolicitud,
                    $row->abrev,
                    $row->proyecto,
                    $row->oficina,
                    $row->servicioPartida,
                    $row->soletapa,
                    $row->condominio,
                    $row->homoclave,
                    $row->folio.'/'.$row->foliofisc,
                    $row->tpago,
                    $row->insumo,
                    $row->nomdepto,
                    $row->nombre_completo, //Usuario (capturista)
                    $row->nresponsable,
                    $row->nombre,
                    $row->orden_compra,
                    $row->nombre_contrato,
                    trim($row->justificacion),
                    $row->fechaCreacion,
                    $row->fecelab,
                    $row->fecha_autorizacion,
                    $row->cantidad,
                    $row->moneda,
                    $row->metoPago,
                    $row->descuento,
                    $row->pagado,
                    $row->fechaDis,
                    ($row->cantidad - $row->pagado),
                    $row->etapa,
                    $row->uuid,
                    $row->crecibo,
                    $row->financiamiento);
                    
                if (($this->session->userdata("inicio_sesion")['rol'] == 'CP' && $this->session->userdata("inicio_sesion")['depto'] == 'CI-COMPRAS') && in_array( $reporte_solicitado, ["#historial_activas_cch", "#historial_activas_viaticos"] )) {
                    $extra_cells = [
                        $row->nombre_reembolso_cch
                    ];
                    if($departamentosExcepcion){
                        array_splice($cells, 12, 0, $extra_cells);
                    }else{
                        array_splice($cells, 14, 0, $extra_cells);
                    }
                }

                if (in_array( $reporte_solicitado, ["#historial_activas_tdc"] )) {
                    $extra_cells = [
                        $row->titular_nombre
                    ];
                    if($departamentosExcepcion){
                        array_splice($cells, 12, 0, $extra_cells);
                    }else{
                        array_splice($cells, 14, 0, $extra_cells);
                    }
                }

                $singleRow = WriterEntityFactory::createRowFromArray($cells);
                $writer->addRow($singleRow);
            }
        }

        $writer->close();
    }

    // Reporte de Lista de pagos autorizados por Dirección General (CHEQUES)
    public function reporte_pagos_auth_DG()
    {
        // Obtener la fecha actual
        $hoy = getdate();

        // Variable para la consulta
        $query = '';
        $filtro_fechas = false;

        $inicio_semana = '1960-01-01';
        $fin_semana = ($hoy['year'] + 1). "-" . $hoy['mon'] . "-" . ($hoy['mday']);

        if ($this->input->post("fechaInicial") && $this->input->post("fechaFinal")) {
            //FECHAS PARA EL RANGO
            $inicio_semana = implode("-", array_reverse(explode("/", $this->input->post("fechaInicial"))));
            $fin_semana = implode("-", array_reverse(explode("/", $this->input->post("fechaFinal"))));
            $filtro_fechas = true;
        }

        ini_set('memory_limit', '-1');
        set_time_limit(0);

        // Encabezados de las tablas para los filtros del reporte
        $idsolicitud = $this->input->post("#_SOLICITUD") ? $this->input->post("#_SOLICITUD") : '';
        $responsable = $this->input->post("RESPONSABLE") ? $this->input->post("RESPONSABLE") : '';
        $empresa = $this->input->post("EMPRESA") ? $this->input->post("EMPRESA") : '';
        $fec_operacion = $this->input->post("FECHA_OPERACIÓN") ? $this->input->post("FECHA_OPERACIÓN") : '';
        $cantidad = $this->input->post("CANTIDAD") ? $this->input->post("CANTIDAD") : '';
        $referencia = $this->input->post("REFERENCIA") ? $this->input->post("REFERENCIA") : '';
        $estatus = $this->input->post("ESTATUS") ? $this->input->post("ESTATUS") : '';
        $pago = $this->input->post("TIPO_PAGO") ? $this->input->post("TIPO_PAGO") : '';

        // Cheques cancelados
        $fec_cancelado = $this->input->post("FECHA_CANC") ? $this->input->post("FECHA_CANC") : '';
        $folio_cancelado = $this->input->post("FOLIO_CANCELADO") ? $this->input->post("FOLIO_CANCELADO") : '';
        $folio_reemplazo = $this->input->post("FOLIO_REMPLAZO") ? $this->input->post("FOLIO_REMPLAZO") : '';
        $motivo = $this->input->post("MOTIVO") ? $this->input->post("MOTIVO") : '';

        // Cheques cobrados
        $fec_cobro = $this->input->post("FECHA_COBRO") ? $this->input->post("FECHA_COBRO") : '';

        $tipo_reporte = $this->input->post('tipo_reporte');

        switch ($tipo_reporte) {
            case 'listado_solicitudes-tab':
                $query = $this->db->query("SELECT * FROM ((SELECT 
                        solpagos.nomdepto AS departamento,
                        solpagos.programado, 
                        solpagos.intereses, 
                        autpagos.idpago,
                        autpagos.fechaDis,
                        proveedores.nombre AS responsable,
                        IFNULL(autpagos.fechaOpe, autpagos.fechaDis) AS fecha_operacion,
                        autpagos.cantidad,
                        autpagos.referencia,
                        '1' AS bd,
                        empresas.abrev,
                        autpagos.idsolicitud,
                        autpagos.estatus,
                        solpagos.proyecto,
                        solpagos.fecelab AS fecha_captura,
                        solpagos.justificacion
                    FROM
                        (
                            SELECT * FROM autpagos
                            WHERE autpagos.estatus IN (14, 15)
                            GROUP BY idpago
                        ) autpagos
                            INNER JOIN
                        (SELECT 
                            solpagos.idsolicitud, proveedores.nombre
                        FROM
                            proveedores
                        INNER JOIN solpagos ON solpagos.idProveedor = proveedores.idproveedor) AS proveedores ON proveedores.idsolicitud = autpagos.idsolicitud
                            INNER JOIN
                        (SELECT 
                            solpagos.idsolicitud, empresas.abrev
                        FROM
                            empresas
                        INNER JOIN solpagos ON solpagos.idEmpresa = empresas.idempresa) AS empresas ON autpagos.idsolicitud = empresas.idsolicitud
                            INNER JOIN
                        solpagos ON solpagos.idsolicitud = autpagos.idsolicitud
                        WHERE
                            ( autpagos.tipoPago IN ('ECHQ', 'EFEC') OR solpagos.metoPago IN ('ECHQ', 'EFEC') )
                    ORDER BY fechaDis DESC) 
                    UNION 
                    (SELECT 
                        autpagos_caja_chica.nomdepto AS departamento,
                        0 as programado, 
                        0 as intereses,
                        autpagos_caja_chica.idpago,
                        autpagos_caja_chica.fechaDis,
                        responsable.responsable,
                        IFNULL(autpagos_caja_chica.fechaOpe, autpagos_caja_chica.fechaDis) AS fecha_operacion,
                        autpagos_caja_chica.cantidad,
                        autpagos_caja_chica.referencia,
                        '2' AS bd,
                        empresas.abrev,
                        autpagos_caja_chica.idsolicitud,
                        autpagos_caja_chica.estatus,
                        'N/A' AS proyecto,
                        'N/A' AS fecha_captura,
                        'N/A' AS justificacion
                    FROM
                        ( 
                            SELECT * FROM autpagos_caja_chica
                            WHERE
                                autpagos_caja_chica.tipoPago = 'ECHQ'
                                AND autpagos_caja_chica.estatus IN (13, 14, 15)
                            GROUP BY idpago
                        ) autpagos_caja_chica
                            INNER JOIN
                        (SELECT 
                            usuarios.idusuario,
                                CONCAT(usuarios.nombres, ' ', usuarios.apellidos) AS responsable
                        FROM
                            usuarios) AS responsable ON responsable.idusuario = autpagos_caja_chica.idResponsable
                            INNER JOIN
                        empresas ON autpagos_caja_chica.idEmpresa = empresas.idempresa
                    ORDER BY fechaDis DESC)) 
                    cheques_activos
                    NATURAL JOIN etapas_pago
                    WHERE DATE( cheques_activos.fecha_operacion ) BETWEEN '$inicio_semana' AND '$fin_semana' 
                    AND (IF(cheques_activos.bd = 1, cheques_activos.idsolicitud, 'NA') LIKE '%$idsolicitud%')
                    AND (cheques_activos.responsable LIKE '%$responsable%')
                    AND (cheques_activos.abrev LIKE '%$empresa%')
                    AND (IFNULL(cheques_activos.referencia,'SIN DEFINIR') LIKE '%$referencia%')
                    AND (IF(cheques_activos.estatus = 14, 'ENTREGADO', 'POR ENTREGAR') LIKE '%$estatus%')
                    AND (IF(cheques_activos.bd = 1, 'PROVEEDOR', 'CAJA CHICA') LIKE '%$pago%')
                    ORDER BY cheques_activos.fecha_operacion DESC");

                $writer = WriterEntityFactory::createXLSXWriter();
                // $writer = WriterEntityFactory::createODSWriter();
                // $writer = WriterEntityFactory::createCSVWriter();

                $fileName = "REPORTE_HISTORIAL_CHEQUES_ACTIVOS_". $hoy['mday'] . "_" . $hoy['mon'] . "_" . $hoy['year'] . "_.xlsx";
                $writer->openToBrowser($fileName); // write data to a file or to a PHP stream
                //$writer->openToBrowser($fileName); // stream data directly to the browser

                $style = (new StyleBuilder())
                    ->setFontBold()
                    ->setFontSize(12)
                    ->build();

                $encab = [
                    '# SOLICITUD',
                    'RESPONSABLE',
                    'PROYECTO',
                    'DEPARTAMENTO',
                    'EMPRESA',
                    'DEPARTAMENTO',
                    'FECHA AUTORIZACIÓN',
                    'FECHA CAPTURA',
                    'FECHA OPERACIÓN',
                    'CANTIDAD/INTERES',
                    'REFERENCIA',
                    'ESTATUS',
                    'PAGO',
                    'JUSTIFICACIÓN'
                ];
                /** add a row at a time */
                $singleRow = WriterEntityFactory::createRowFromArray($encab, $style);
                $writer->addRow($singleRow);

                if ($query->num_rows() > 0) {
                    foreach ($query->result() as $row) {
                        $cells = [
                            ($row->bd == 1) ? $row->idsolicitud : 'NA',
                            ($row->programado >= 1) ? $row->responsable . ' / PROGRAMADO' : $row->responsable,
                            $row->proyecto,
                            $row->departamento,
                            $row->abrev,
                            $row->departamento,
                            $row->fechaDis,
                            $row->fecha_captura,
                            $row->fecha_operacion,
                            ($row->intereses == '1') ? number_format($row->cantidad, 2, '.', ',') . ' / ' . number_format($row->intereses, 2, '.', ',') : number_format($row->cantidad, 2, '.', ','),
                            $row->referencia,
                            $row->etapa_pago,
                            $row->bd == 1 ? "PROVEEDOR" : "CAJA CHICA",
                            $row->justificacion

                        ];
                        $singleRow = WriterEntityFactory::createRowFromArray($cells);
                        $writer->addRow($singleRow);
                    }
                }
                $writer->close();

                break;
            case 'listado_pagos-tab':
                $query = $this->db->query("SELECT * FROM (( SELECT  DISTINCT
                autpagos.idpago,
                solpagos.idsolicitud,
                historial_cheques.numCan,
                historial_cheques.numRem,
                IFNULL(solpagos.nom_prov,
                        responsable.nom_responsable) AS nombre,
                IFNULL(autpagos.cantidad,
                        autpagos_caja_chica.cantidad) AS cantidad,
                DATE(historial_cheques.fecha_creacion) AS fecha_cancelacion_f,
                IFNULL(empresas.abrev, empresascc.abrev) AS abrev,
                historial_cheques.observaciones,
                autpagos.referencia,
                autpagos.estatus,
                historial_cheques.tipo
            FROM
                ( SELECT * FROM historial_cheques WHERE historial_cheques.idautpago IS NOT NULL ) historial_cheques
                    LEFT JOIN
                autpagos ON historial_cheques.idautpago = autpagos.idpago
                    AND historial_cheques.tipo = 1
                    LEFT JOIN
                autpagos_caja_chica ON autpagos_caja_chica.idpago = historial_cheques.idautpago
                    AND historial_cheques.tipo = 2
                    LEFT JOIN
                (SELECT 
                    solpagos.idsolicitud,
                        solpagos.nomdepto,
                        proveedores.nombre AS nom_prov
                FROM
                    solpagos
                INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor) AS solpagos ON solpagos.idsolicitud = autpagos.idsolicitud
                    LEFT JOIN
                (SELECT 
                    usuarios.idusuario,
                        CONCAT(usuarios.nombres, ' ', usuarios.apellidos) AS nom_responsable
                FROM
                    usuarios) AS responsable ON autpagos_caja_chica.idResponsable = responsable.idusuario
                    LEFT JOIN
                (SELECT 
                    solpagos.idsolicitud, empresas.abrev
                FROM
                    empresas
                INNER JOIN solpagos ON solpagos.idEmpresa = empresas.idempresa) AS empresas ON solpagos.idsolicitud = empresas.idsolicitud
                    LEFT JOIN
                (SELECT 
                    empresas.idempresa, empresas.abrev
                FROM
                    empresas) AS empresascc ON autpagos_caja_chica.idEmpresa = empresascc.idempresa 
                  
                  ) UNION (
                      SELECT 
                      'NA' idpago, 
                      'NA' idsolicitud, 
                      numCan, 
                      numRem, 
                      'NA' nombre,
                      cantidad, 
                      DATE(historial_cheques.fecha_creacion) AS fecha_cancelacion_f, 
                      empresas.abrev, 
                      historial_cheques.observaciones, 
                      'NA' referencia,
                      NULL estatus, 
                      historial_cheques.tipo
                      FROM historial_cheques INNER JOIN empresas ON empresas.idempresa = historial_cheques.idempresa WHERE idautpago IS NULL
                  )) 
                cheques_cancelados
                WHERE DATE(cheques_cancelados.fecha_cancelacion_f) BETWEEN '$inicio_semana' AND '$fin_semana'
                AND (IF(cheques_cancelados.tipo = 1, cheques_cancelados.idsolicitud, 'NA') LIKE '%$idsolicitud%' OR '$idsolicitud' = '')
                AND (cheques_cancelados.nombre LIKE '%$responsable%' OR '$responsable' = '')
                AND (cheques_cancelados.abrev LIKE '%$empresa%' OR '$empresa' = '')
                AND (cheques_cancelados.numCan LIKE '%$folio_cancelado%' OR  '$folio_cancelado' = '')
                AND (cheques_cancelados.numRem LIKE '%$folio_reemplazo%' OR  '$folio_reemplazo' = '')
                AND (IF(cheques_cancelados.tipo = 1, 'PROVEEDOR', 'CAJA CHICA') LIKE '%$pago%' OR '$pago' = '')");

                $writer = WriterEntityFactory::createXLSXWriter();
                // $writer = WriterEntityFactory::createODSWriter();
                // $writer = WriterEntityFactory::createCSVWriter();

                $fileName = "REPORTE_HISTORIAL_CHEQUES_CANCELADOS_". $hoy['mday'] . "_" . $hoy['mon'] . "_" . $hoy['year'] . "_.xlsx";
                $writer->openToBrowser($fileName); // write data to a file or to a PHP stream
                //$writer->openToBrowser($fileName); // stream data directly to the browser

                $style = (new StyleBuilder())
                    ->setFontBold()
                    ->setFontSize(12)
                    ->build();

                $encab = [
                '# SOLICITUD',
                'RESPONSABLE',
                'EMPRESA',
                'FECHA CANCELACIÓN',
                'FOLIO CANCELADO',
                'FOLIO REEMPLAZADO',
                'CANTIDAD',
                'TIPO PAGO',
                'MOTIVO'
                ];
                /** add a row at a time */
                $singleRow = WriterEntityFactory::createRowFromArray($encab, $style);
                $writer->addRow($singleRow);

                if ($query->num_rows() > 0) {
                    foreach ($query->result() as $row) {
                        $cells = [
                            ($row->tipo == 1) ? $row->idsolicitud : 'NA',
                            $row->nombre,
                            $row->abrev,
                            $row->fecha_cancelacion_f,
                            $row->numCan,
                            $row->numRem,
                            $row->cantidad,
                            ($row->tipo == 1) ? 'PROVEEDOR' : 'CAJA CHICA',
                            $row->observaciones

                        ];
                        $singleRow = WriterEntityFactory::createRowFromArray($cells);
                        $writer->addRow($singleRow);
                    }
                }
                $writer->close();

                break;
            case 'listado_cheques-tab':
                $query = $this->db->query("SELECT * FROM (
                            (SELECT	autpagos.idsolicitud,
                                    proveedores.nombre AS responsable, 
                                    autpagos.fecha_cobro AS fecha, 
                                    IFNULL((autpagos.fechaOpe), '---') AS fecha_operacion, 
                                    autpagos.cantidad, 
                                    autpagos.referencia, 
                                    '1' AS bd, 
                                    empresas.abrev, 
                                    IFNULL(autpagos.fecha_cobro, '---') AS fecha_cobro, 
                                    solpagos.programado, 
                                    solpagos.intereses,
                                    solpagos.folio, 
                                    fr.uuid, 
                                    autpagos.fechaDis AS fecha_dispersion, 
                                    autpagos.fecreg AS fechaaut, 
                                    solpagos.nomdepto,
                                    
                                    solpagos.proyecto,
                                    etapas.nombre AS etapa,
                                    solpagos.condominio,
                                    CONCAT(us.nombres, ' ', us.apellidos) AS responsableDA,
                                    solpagos.justificacion,
                                    solpagos.metoPago,
                                    fr.idfactura,
                                    fr.descripcion AS descFac,
                                    CONCAT(usc.nombres, ' ', usc.apellidos) AS usuarioAlta
                            FROM (	SELECT * 
                                    FROM autpagos 
                                    WHERE idpago NOT IN ( SELECT idpago FROM autpagos WHERE autpagos.formaPago IN ( 'ECHQ', 'EFEC' ) GROUP BY autpagos.referencia HAVING COUNT( referencia ) > 1 ) ) autpagos 
                            INNER JOIN (SELECT solpagos.idsolicitud, proveedores.nombre, solpagos.metoPago, solpagos.programado, solpagos.intereses
                                        FROM proveedores 
                                        INNER JOIN solpagos 
                                            ON solpagos.idProveedor = proveedores.idproveedor) AS proveedores 
                                ON proveedores.idsolicitud = autpagos.idsolicitud 
                            INNER JOIN (SELECT solpagos.idsolicitud, empresas.abrev FROM empresas INNER JOIN solpagos ON solpagos.idEmpresa = empresas.idempresa) AS empresas 
                                ON autpagos.idsolicitud = empresas.idsolicitud 
                            INNER JOIN solpagos 
                                ON autpagos.idsolicitud  = solpagos.idsolicitud
                            LEFT JOIN factura_registro fr 
                                ON fr.idsolicitud = solpagos.idsolicitud -- de aquí saco UUID
                            LEFT JOIN etapas 
                                ON solpagos.idetapa = etapas.idetapa
                            LEFT JOIN usuarios AS us
                                ON solpagos.idResponsable = us.idusuario
                            LEFT JOIN usuarios AS usc
                                ON solpagos.idusuario = usc.idusuario
                            WHERE ( autpagos.formaPago IN ( 'ECHQ', 'EFEC' ) OR proveedores.metoPago IN ( 'EFEC', 'ECHQ' ) ) AND autpagos.estatus = 16)
                            UNION (
                            SELECT
                                autpagos_caja_chica.idpago,
                                responsable.responsable AS responsableDA,
                                autpagos_caja_chica.fecha_cobro AS fecha,
                                IFNULL(autpagos_caja_chica.fechaDis, '---') AS fecha_operacion,
                                autpagos_caja_chica.cantidad,
                                autpagos_caja_chica.referencia,
                                '2' AS bd,
                                empresas.abrev,
                                autpagos_caja_chica.fecha_cobro fecha_cobro,
                                0 as programado, 
                                0 as intereses,
                                'NA' folio,
                                'NA' uuid,
                                autpagos_caja_chica.fechaDis AS fecha_dispersion,
                                autpagos_caja_chica.fecreg AS fechaaut,
                                'NA' nomdepto,

                                'NA' AS proyecto,
                                'NA' AS etapa,
                                'NA' AS condominio,
                                pro.nombre AS proveedor,
                                'NA' AS justificacion,
                                'NA' AS metoPago,
                                'NA' AS idfactura,
                                'NA' AS  descFac,
                                CONCAT(usc.nombres, ' ', usc.apellidos) AS usuarioAlta
                            FROM autpagos_caja_chica
                            INNER JOIN (SELECT usuarios.idusuario, CONCAT(usuarios.nombres, ' ', usuarios.apellidos) AS responsable FROM usuarios) AS responsable 
                                ON responsable.idusuario = autpagos_caja_chica.idResponsable
                            LEFT JOIN usuarios AS usc
                                ON autpagos_caja_chica.idrealiza = usc.idusuario
                            INNER JOIN empresas 
                                ON autpagos_caja_chica.idEmpresa = empresas.idempresa
                            LEFT JOIN proveedores AS pro
                                ON autpagos_caja_chica.idProveedor = pro.idproveedor
                            WHERE(	autpagos_caja_chica.formaPago IN ( 'ECHQ', 'EFEC' ) OR 
                                    autpagos_caja_chica.tipoPago IN ( 'ECHQ', 'EFEC' ) ) AND 
                                    autpagos_caja_chica.estatus = 16 )
                            UNION (
                            SELECT 
                                'NA' idpago,
                                'MULTIPLES PAGOS EN EFEC' responsable,
                                IFNULL(MAX(autpagos.fecha_cobro), MAX(autpagos.fechaOpe)) fecha,
                                MAX(autpagos.fechaDis) fecha_operacion,
                                SUM(solpagos.cantidad) cantidad, 
                                autpagos.referencia ,
                                '1' bd,
                                empresas.abrev, 
                                IFNULL(MAX(autpagos.fecha_cobro), MAX(autpagos.fechaOpe) ) fecha_cobro,
                                0 programado,
                                0 intereses,
                                solpagos.folio, 
                                fr.uuid, 
                                'NA' fecha_dispersion, 
                                'NA' fechaaut, 
                                solpagos.nomdepto,
                                solpagos.proyecto,
                                etapas.nombre AS etapa,
                                solpagos.condominio,
                                CONCAT(us.nombres, ' ', us.apellidos) AS responsableDA,
                                solpagos.justificacion,
                                solpagos.metoPago,
                                fr.idfactura,
                                fr.descripcion AS descFac,
                                CONCAT(usc.nombres, ' ', usc.apellidos) AS usuarioAlta
                            FROM solpagos 
                            INNER JOIN empresas 
                                ON empresas.idempresa = solpagos.idempresa
                            INNER JOIN (SELECT * 
                                        FROM autpagos 
                                        WHERE idpago IN (SELECT idpago 
                                                        FROM autpagos
                                                        WHERE	autpagos.referencia IS NOT NULL AND  
                                                                autpagos.formaPago IN ( 'ECHQ', 'EFEC' )
                                                        GROUP BY autpagos.referencia HAVING COUNT( referencia ) > 1 ) ) autpagos 
                                ON autpagos.idsolicitud = solpagos.idsolicitud 
                            LEFT JOIN factura_registro fr 
                                ON fr.idsolicitud = solpagos.idsolicitud -- de aquí saco UUID
                            LEFT JOIN etapas 
                                ON solpagos.idetapa = etapas.idetapa
                            LEFT JOIN usuarios AS us
                                ON solpagos.idResponsable = us.idusuario
                            LEFT JOIN usuarios AS usc
                                ON solpagos.idusuario = usc.idusuario
                            WHERE	solpagos.metoPago = 'EFEC' AND 
                                    autpagos.referencia IS NOT NULL AND 
                                    concat('', autpagos.referencia * 1) = autpagos.referencia AND 
                                    autpagos.estatus = 16
                            GROUP BY solpagos.idEmpresa, autpagos.referencia)
                            )
                            cheques_cobrados
                            WHERE DATE(cheques_cobrados.fecha_cobro) BETWEEN '$inicio_semana' AND '$fin_semana'
                            AND (IF(cheques_cobrados.bd = 1, cheques_cobrados.idsolicitud, 'NA') LIKE '%$idsolicitud%' OR '$idsolicitud' = '')
                            AND (cheques_cobrados.responsable LIKE '%$responsable%' OR '$responsable' = '')
                            AND (cheques_cobrados.abrev LIKE '%$empresa%' OR '$empresa' = '')
                            AND (cheques_cobrados.referencia LIKE '%$referencia%' OR '$referencia' = '')
                            AND (IF(cheques_cobrados.bd = 1, 'PROVEEDOR', 'CAJA CHICA') LIKE '%$pago%' OR '$pago' = '')
                            ORDER BY fecha_cobro DESC");
                $writer = WriterEntityFactory::createXLSXWriter();
                // $writer = WriterEntityFactory::createODSWriter();
                // $writer = WriterEntityFactory::createCSVWriter();

                $fileName = "REPORTE_HISTORIAL_CHEQUES_COBRADOS_". $hoy['mday'] . "_" . $hoy['mon'] . "_" . $hoy['year']. "_.xlsx";
                $writer->openToBrowser($fileName); // write data to a file or to a PHP stream
                //$writer->openToBrowser($fileName); // stream data directly to the browser

                $style = (new StyleBuilder())
                    ->setFontBold()
                    ->setFontSize(12)
                    ->build();

                $encab = [
                    '# SOLICITUD',
                    'PROVEEDOR',
                    'RESPONSABLE',
                    'FECHA COBRO',
                    'USUARIO ALTA',
                    'EMPRESA',
                    'CANTIDAD',
                    'REFERENCIA',
                    'TIPO PAGO',
                    'FOLIO',
                    'FECHA DISPERSIÓN',
                    'FECHA AUTORIZADO',
                    'DEPARTAMENTO',
                    'PROYECTO',
                    'ETAPA',
                    'CONDOMINIO',
                    'JUSTIFICACION',
                    'METODO PAGO',
                    'FACTURA',
                    'DESCRIPCION DE FACTURA'
                ];
                /** add a row at a time */
                $singleRow = WriterEntityFactory::createRowFromArray($encab, $style);
                $writer->addRow($singleRow);

                if ($query->num_rows() > 0) {
                    foreach ($query->result() as $row) {
                        $cells = [
                            ($row->bd == 1) ? $row->idsolicitud : 'NA',
                            ($row->programado >= 1) ? $row->responsable . ' / PROGRAMADO' : $row->responsable,
                            $row->responsableDA,
                            $row->fecha_cobro,
                            $row->usuarioAlta,
                            $row->abrev,
                            $row->intereses == 1 ? $row->cantidad . ' / CREDITO / INTERES: ' . $row->intereses : $row->cantidad,
                            $row->referencia,
                            ($row->bd == 1) ? 'PROVEEDOR' : 'CAJA CHICA',
                            $row->folio,
                            $row->fecha_dispersion,
                            $row->fechaaut,
                            $row->nomdepto,
                            $row->proyecto,
                            $row->etapa,
                            $row->condominio,
                            $row->justificacion,
                            $row->metoPago,
                            $row->idfactura,
                            $row->descFac
                        ];
                        $singleRow = WriterEntityFactory::createRowFromArray($cells);
                        $writer->addRow($singleRow);
                    }
                }

                $writer->close();
                break;
            default:
                $query = $this->db->query("SELECT * FROM ( (SELECT 
                    solpagos.nomdepto AS departamento,
                    solpagos.programado, 
                    solpagos.intereses, 
                    autpagos.idsolicitud idpago, 
                    proveedores.nombre AS responsable, 
                    autpagos.cantidad, 
                    IFNULL(autpagos.fecha_cobro, 'SIN COBRAR') AS fecha_operacion, 
                    IFNULL(autpagos.fecha_cobro, 'SIN COBRAR') AS fecha_filtro, 
                    IFNULL(autpagos.referencia, 'NA') AS referencia, 
                    empresas.abrev, '1' AS bd, 
                    ( CASE WHEN autpagos.estatus = '16' THEN 'COBRADO' WHEN autpagos.estatus = '14' THEN 'ENTREGADO' ELSE 'POR ENTREGAR' END ) AS estatus, 
                    autpagos.fecha_cobro AS orderby,
                    solpagos.proyecto,
                    solpagos.fecelab AS fecha_captura,
                    solpagos.justificacion
                    FROM ( SELECT * FROM autpagos WHERE idpago NOT IN ( SELECT idpago FROM autpagos WHERE autpagos.formaPago IN ( 'ECHQ', 'EFEC' ) GROUP BY autpagos.referencia HAVING COUNT( referencia ) > 1 ) ) autpagos 
                    INNER JOIN (SELECT solpagos.idsolicitud, proveedores.nombre, solpagos.metoPago FROM proveedores INNER JOIN solpagos ON solpagos.idProveedor = proveedores.idproveedor WHERE solpagos.metoPago = 'ECHQ') AS proveedores ON proveedores.idsolicitud = autpagos.idsolicitud 
                    INNER JOIN (SELECT solpagos.idsolicitud, empresas.abrev FROM empresas INNER JOIN solpagos ON solpagos.idEmpresa = empresas.idempresa) AS empresas ON autpagos.idsolicitud = empresas.idsolicitud  
                    INNER JOIN solpagos ON solpagos.idsolicitud = autpagos.idsolicitud 
                    WHERE (autpagos.formaPago IN ( 'ECHQ', 'EFEC' ) OR proveedores.metoPago IN ( 'ECHQ', 'EFEC' )) 
                    AND autpagos.estatus IN (13, 14, 15, 16)
                ) UNION (
                    SELECT 
                    solpagos.nomdepto AS departamento,
                    0 AS programado,  
                    0 AS intereses, 
                    IFNULL( autpagos.idsolicitud, 'NA' ) AS idpago, 
                    IFNULL(solpagos.nom_prov, responsable.nom_responsable) AS responsable, 
                    IFNULL(autpagos.cantidad, 0) AS cantidad, 
                    historial_cheques.fecha_creacion AS fecha_operacion, 
                    historial_cheques.fecha_creacion AS fecha_filtro, 
                    IFNULL(historial_cheques.numCan, 'NA') AS referencia, 
                    empresas.abrev, 
                    '1' AS bd, 
                    'CANCELADO' AS estatus, 
                    historial_cheques.fecha_creacion AS orderby,
                    'N/A' AS proyecto,
                    'N/A' AS fecha_captura,
                    'N/A' AS justificacion
                    FROM historial_cheques 
                    LEFT JOIN autpagos ON historial_cheques.idautpago = autpagos.idpago AND historial_cheques.tipo = 1 
                    LEFT JOIN (SELECT solpagos.idsolicitud, solpagos.nomdepto, proveedores.nombre AS nom_prov FROM solpagos INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor) AS solpagos ON solpagos.idsolicitud = autpagos.idsolicitud 
                    LEFT JOIN (SELECT usuarios.idusuario, CONCAT(usuarios.nombres, ' ', usuarios.apellidos) AS nom_responsable FROM usuarios) AS responsable ON autpagos.idrealiza = responsable.idusuario 
                    LEFT JOIN (SELECT solpagos.idsolicitud, empresas.abrev FROM empresas INNER JOIN solpagos ON solpagos.idEmpresa = empresas.idempresa) AS empresas ON solpagos.idsolicitud = empresas.idsolicitud 
                    WHERE autpagos.cantidad > 0 
                    AND idautpago IS NOT NULL
                ) UNION (
                    SELECT 
                    autpagos_caja_chica.nomdepto AS departamento,
                    0 AS programado, 
                    0 AS intereses, 
                    autpagos_caja_chica.idpago, 
                    responsable.responsable, 
                    autpagos_caja_chica.cantidad, 
                    IF(autpagos_caja_chica.fecha_cobro != '0000-00-00' AND autpagos_caja_chica.fecha_cobro IS NOT NULL, autpagos_caja_chica.fecha_cobro, 'SIN COBRAR') AS fecha_operacion, 
                    IF(autpagos_caja_chica.fecha_cobro != '0000-00-00' AND autpagos_caja_chica.fecha_cobro IS NOT NULL, autpagos_caja_chica.fecha_cobro, 'SIN COBRAR') AS fecha_filtro, 
                    IFNULL(autpagos_caja_chica.referencia,'NA') referencia, 
                    empresas.abrev, 
                    '2' AS bd, 
                    IF(autpagos_caja_chica.estatus = '16', 'COBRADO', 'ACTIVO') AS estatus, 
                    fecha_cobro AS orderby, 
                    'N/A' AS proyecto,
                    'N/A' AS fecha_captura,
                    'N/A' AS justificacion
                    FROM autpagos_caja_chica 
                    INNER JOIN (SELECT usuarios.idusuario, CONCAT(usuarios.nombres, ' ', usuarios.apellidos) AS responsable FROM usuarios) AS responsable ON responsable.idusuario = autpagos_caja_chica.idResponsable 
                    INNER JOIN empresas ON autpagos_caja_chica.idEmpresa = empresas.idempresa 
                    WHERE ( autpagos_caja_chica.tipoPago IN ( 'ECHQ' )
                    AND autpagos_caja_chica.estatus IN ( 13, 15, 16 ) ) 
                    OR (  autpagos_caja_chica.tipoPago IN ( 'EFEC' ) 
                    AND autpagos_caja_chica.estatus IN ( 16 ) )
                )UNION (
                    SELECT 
                    'NA' departamento,
                    0 programado,
                    0 intereses,
                    'NA' idpago, 
                    'NA' responsable, 
                    cantidad, 
                    historial_cheques.fecha_creacion fecha_operacion,
                    historial_cheques.fecha_creacion AS fecha_filtro,
                    IFNULL(numCan, 'NA') referencia, 
                    empresas.abrev, 
                    '1' AS bd, 
                    'CANCELADO' estatus,
                    historial_cheques.fecha_creacion AS orderby,
                    'N/A' AS proyecto,
                    'N/A' AS fecha_captura,
                    'N/A' AS justificacion
                    FROM historial_cheques 
                    INNER JOIN empresas ON empresas.idempresa = historial_cheques.idempresa 
                    WHERE idautpago IS NULL
                ) UNION (
                    SELECT 
                    solpagos.nomdepto AS departamento,
                    0 programado,
                    0 intereses,
                    'EFEC' idpago,
                    'MULTIPLES PAGOS EN EFEC' responsable,
                    SUM(solpagos.cantidad) cantidad, 
                    IFNULL(MAX(autpagos.fecha_cobro), MAX(autpagos.fechaOpe)) fecha_operacion,
                    IFNULL(MAX(autpagos.fecha_cobro), MAX(autpagos.fechaOpe)) AS fecha_filtro,
                    IFNULL(autpagos.referencia, 'NA') referencia,
                    empresas.abrev, 
                    '1' AS bd,
                    'COBRADO' estatus,
                    autpagos.fecreg AS orderby,
                    solpagos.proyecto,
                    solpagos.fecelab AS fecha_captura,
                    solpagos.justificacion
                    FROM solpagos 
                    INNER JOIN empresas ON empresas.idempresa = solpagos.idempresa
                    INNER JOIN ( SELECT * FROM autpagos WHERE idpago IN ( SELECT idpago FROM autpagos WHERE autpagos.formaPago IN ( 'ECHQ', 'EFEC' ) GROUP BY autpagos.referencia HAVING COUNT( referencia ) > 1 ) ) autpagos ON autpagos.idsolicitud = solpagos.idsolicitud 
                    WHERE solpagos.metoPago = 'EFEC' AND autpagos.referencia IS NOT NULL AND concat('', autpagos.referencia * 1) = autpagos.referencia AND autpagos.estatus = 16
                    GROUP BY solpagos.idEmpresa, autpagos.referencia) ) 
                    todos_cheques 
                    WHERE DATE(todos_cheques.fecha_operacion) BETWEEN '$inicio_semana' AND '$fin_semana'
                    AND (todos_cheques.responsable LIKE '%$responsable%' OR '$responsable' = '')
                    AND (todos_cheques.abrev LIKE '%$empresa%' OR '$empresa' = '')
                    AND (todos_cheques.referencia LIKE '%$referencia%' OR '$referencia' = '')
                    AND (todos_cheques.estatus LIKE '%$estatus%' OR '$estatus' = '')
                    AND (IF(todos_cheques.bd = 1, 'PROVEEDOR', 'CAJA CHICA') LIKE '%$pago%' OR '$pago' = '')
                    ORDER BY todos_cheques.orderby DESC");

                $writer = WriterEntityFactory::createXLSXWriter();
                // $writer = WriterEntityFactory::createODSWriter();
                // $writer = WriterEntityFactory::createCSVWriter();

                $fileName = "REPORTE_HISTORIAL_TODOS_LOS_CHEQUES_". $hoy['mday'] . "_" . $hoy['mon'] . "_" . $hoy['year'] . "_.xlsx";
                $writer->openToBrowser($fileName); // write data to a file or to a PHP stream
                //$writer->openToBrowser($fileName); // stream data directly to the browser

                $style = (new StyleBuilder())
                    ->setFontBold()
                    ->setFontSize(12)
                    ->build();

                $encab = [
                '# SOLICITUD',
                'RESPONSABLE',
                'PROYECTO',
                'DEPARTAMENTO',
                'EMPRESA',
                'DEPARTAMENTO',
                'FECHA CAPTURA',
                'FECHA OPERACIÓN',
                'CANTIDAD/INTERES',
                'REFERENCIA',
                'ESTATUS',
                'PAGO',
                'JUSTIFICACIÓN'

                ];
                /** add a row at a time */
                $singleRow = WriterEntityFactory::createRowFromArray($encab, $style);
                $writer->addRow($singleRow);

                if ($query->num_rows() > 0) {
                    foreach ($query->result() as $row) {
                        $cells = [
                            ($row->bd == 1) ? $row->idpago : 'NA',
                            ($row->programado >= 1) ? $row->responsable . ' / PROGRAMADO' : $row->responsable,
                            $row->proyecto,
                            $row->departamento,
                            $row->abrev,
                            $row->departamento,
                            $row->fecha_captura,
                            $row->fecha_operacion,
                            ($row->intereses == '1') ? number_format($row->cantidad, 2, '.', ',') . ' / ' . number_format($row->intereses, 2, '.', ',') : number_format($row->cantidad, 2, '.', ','),
                            $row->referencia,
                            $row->estatus,
                            $row->bd == 1 ? "PROVEEDOR" : "CAJA CHICA",
                            $row->justificacion
                        ];
                        $singleRow = WriterEntityFactory::createRowFromArray($cells);
                        $writer->addRow($singleRow);
                    }
                }

                $writer->close();
                break;
        }
    }

    public function reporte_trapasosYdevoluciones() {
        $this->load->model( array('M_historial') );
        
        $filtro='';
        if($this->input->post('idsol')!='')
            $filtro.=" and solpagos.idsolicitud like '%".$this->input->post('idsol')."%'";
        if($this->input->post('empresa')!='')
            $filtro.=" and empresa.abrev LIKE '%".$this->input->post('empresa')."%'";
        if($this->input->post('proyecto')!='')
            $filtro.=" and solpagos.proyecto LIKE '%".$this->input->post('proyecto')."%'";
        if($this->input->post('folio')!='')
            $filtro.=" and facturas.foliofac LIKE '%".$this->input->post('folio')."%'";
        if($this->input->post('proveedor')!='')
            $filtro.=" and proveedores.nombre LIKE '%".$this->input->post('proveedor')."%'";
        if($this->input->post('depto')!='')
            $filtro.=" and solpagos.nomdepto LIKE '%".$this->input->post('depto')."%'";
        if($this->input->post('fechaini')!=''){
            $date = DateTime::createFromFormat('d/m/Y', $this->input->post('fechaini'));
            $date=$date->format('Y-m-d');
            $filtro.=" and solpagos.fechaCreacion >= '".$date." 00:00:00'";
        }
        if($this->input->post('fechafin')!=''){
            $date = DateTime::createFromFormat('d/m/Y', $this->input->post('fechafin'));
            $date=$date->format('Y-m-d');
            $filtro.=" and solpagos.fechaCreacion <= '".$date." 23:59:59'";
        }
        
        $query= $this->M_historial->getHistorialTablaSolOri_filtrado($filtro); 
        
        $writer = WriterEntityFactory::createXLSXWriter();
        // $writer = WriterEntityFactory::createODSWriter();
        // $writer = WriterEntityFactory::createCSVWriter();
        $hoy = getdate();
        $nombre_excel="Devoluciones Y Traspasos";
        $fileName = $nombre_excel . "_" . $hoy['mday'] . "_" . $hoy['mon'] . "_" . $hoy['year'] . "_.xlsx";
        $writer->openToBrowser($fileName); // write data to a file or to a PHP stream
        //$writer->openToBrowser($fileName); // stream data directly to the browser

        $style = (new StyleBuilder())
            ->setFontBold()
            ->setFontSize(12)
            ->build();

        $encab = [
            '#',
            'EMPRESA',
            'PROYECTO',
            'FOLIO',
            'FECHA CAPTURA',
            'FECHA AUTORIZACIÓN',
            'FECHA PAGO',
            'PROVEEDOR',
            'DEPARTAMENTO',
            'CANTIDAD',
            'JUSTIFICACIÓN',
            'FECHA DISPERSIÓN',
            'PAGADO',
            'SALDO',
            'ESTADO',
            'TIPO',
            'FORMA PAGO',
            'RESPONSABLE',
            'CAPTURISTA',
            'FACTURA',
        ];
        /** add a row at a time */
        $singleRow = WriterEntityFactory::createRowFromArray($encab, $style);
        $writer->addRow($singleRow);
        
       
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $cells = [
                    $row->idsolicitud,
                    $row->abrev,
                    $row->proyecto,
                    $row->folio,
                    $row->feccrea,
                    $row->fautorizado,
                    $row->fechapago,
                    $row->nombre,
                    $row->nomdepto,
                    $row->mpagar,
                    $row->justificacion,
                    $row->fechaDis2,
                    $row->pagado,
                    ($row->mpagar - $row->pagado),
                    estatus_pagotext($row),
                    $row->caja_chica,
                    $row->metoPago,
                    $row->nombredir,
                    $row->nombre_completo,
                    $row->tienefac
                ];
                $singleRow = WriterEntityFactory::createRowFromArray($cells);
                $writer->addRow($singleRow);
            }
        }

        $writer->close();

    }

    function reporte_desglosado_CT(){

        if( isset( $_POST ) && !empty( $_POST ) && in_array( $this->session->userdata("inicio_sesion")['rol'], array( 'CC' ) ) ){
            
            $idpago_cch = $this->input->post("reeb_cch");
            $proyecto_cch = $this->input->post("proy_cch");

            $sql = "SELECT 
                '$proyecto_cch' PROYECTO, 
                IFNULL(CONCAT( sol.foliofac, '/', SUBSTRING(sol.uuid, -5, 5 )), '') FACTURA,
                IFNULL(sol.rfc_emisor, 'GASTO NO DEDUCIBLE') RFC_PROVEEDOR,
                IFNULL(neoprov.clvneo, 'SIN DEFINIR') PROVEEDOR,
                IFNULL(sol.uuid, '') UUID,
                i.descripcion_insumo INSUMO,
                IFNULL(i.descripcion_insumo, i.insumo) DESCRIPCION,
                sol.subtotal CANTIDAD,
                1 COSTO,
                sol.subtotal IMPORTE,
                CONCAT(IF(sol.tasacuotat IS NOT NULL AND sol.tasacuotat = 'NA', sol.tasacuotat, 0 ),'%') IVA_PORC,
                'VERDADERO' P,
                IFNULL(sol.importet, 0 ) IVA,
                IF( sol.importet IS NOT NULL, '610000050001', '' ) CUENTA_IVA,
                '' CUENTA_RET_IVA,
                sol.IVAtras MONTO_RET_IVA,
                '' CUENTA_RET_ISR,
                sol.ISRtras MONTO_RET_ISR,
                sol.total TOTAL,
                'VERDADERO' DIOT,
                sol.moneda MONEDA_SAT,
                SUBSTRING(sol.justificacion, 1, 40) OBSERVACIONES
            FROM
            ( 
                /*REGISTROS TASA 16*/
                SELECT 
                    solpagos.idsolicitud, 
                    solpagos.justificacion, 
                    solpagos.moneda, 
                    solpagos.servicio, 
                    solpagos.idEmpresa, 
                    solpagos.proyecto, 
                    f.uuid, 
                    f.foliofac, 
                    f.rfc_emisor, 
                    f.total, 
                    ( f.subtotal + IEPStras ) subtotal, 
                    16 tasacuotat, 
                    f.tasatras16 importet,
                    ISRtras,  
                    rettras IVAtras
                FROM ( SELECT idsolicitud FROM autpagos_caja_chica WHERE idpago = $idpago_cch ) sp 
                CROSS JOIN ( SELECT * FROM solpagos WHERE solpagos.caja_chica = 1 AND tendrafac IS NOT NULL AND solpagos.idetapa IN ( 11 )  ) solpagos ON CONCAT( ',',sp.idsolicitud,',' ) LIKE CONCAT( '%,',solpagos.idsolicitud,',%' )
                CROSS JOIN ( SELECT idsolicitud, uuid, foliofac, rfc_emisor, total, subtotal, .16 tasacuotat, tasatras16, IEPStras, ISRtras, rettras FROM facturas WHERE tipo_factura IN ( 3 ) AND tasatras16 IS NOT NULL AND tasatras16 > 0 ) f 
                ON f.idsolicitud = solpagos.idsolicitud
                UNION
                /*REGISTROS TASA 8*/
                SELECT 
                    solpagos.idsolicitud, 
                    solpagos.justificacion, 
                    solpagos.moneda, 
                    solpagos.servicio, 
                    solpagos.idEmpresa, 
                    solpagos.proyecto, 
                    f.uuid, 
                    f.foliofac, 
                    f.rfc_emisor, 
                    f.total, 
                    ( f.subtotal + IEPStras ) subtotal, 
                    8 tasacuotat, 
                    f.tasatras8 importet,
                    ISRtras,  
                    rettras IVAtras
                FROM ( SELECT idsolicitud FROM autpagos_caja_chica WHERE idpago = $idpago_cch ) sp 
                CROSS JOIN ( SELECT * FROM solpagos WHERE solpagos.caja_chica = 1 AND tendrafac IS NOT NULL AND solpagos.idetapa IN ( 11 )  ) solpagos ON CONCAT( ',',sp.idsolicitud,',' ) LIKE CONCAT( '%,',solpagos.idsolicitud,',%' )
                CROSS JOIN ( SELECT idsolicitud, uuid, foliofac, rfc_emisor, total, subtotal, .08 tasacuotat, tasatras8, IEPStras, ISRtras, rettras FROM facturas WHERE tipo_factura IN ( 3 ) AND tasatras8 IS NOT NULL AND tasatras8 > 0 ) f 
                ON f.idsolicitud = solpagos.idsolicitud
                UNION
                /*REGISTROS TASA 0*/
                SELECT 
                    solpagos.idsolicitud, 
                    solpagos.justificacion, 
                    solpagos.moneda, 
                    solpagos.servicio, 
                    solpagos.idEmpresa, 
                    solpagos.proyecto, 
                    f.uuid, 
                    f.foliofac, 
                    f.rfc_emisor, 
                    f.total, 
                    ( f.subtotal + IEPStras ) subtotal, 
                    0 tasacuotat, 
                    f.tasatras0 importet,
                    ISRtras,  
                    rettras IVAtras
                FROM ( SELECT idsolicitud FROM autpagos_caja_chica WHERE idpago = $idpago_cch ) sp 
                CROSS JOIN ( SELECT * FROM solpagos WHERE solpagos.caja_chica = 1 AND tendrafac IS NOT NULL AND solpagos.idetapa IN ( 11 )  ) solpagos ON CONCAT( ',',sp.idsolicitud,',' ) LIKE CONCAT( '%,',solpagos.idsolicitud,',%' )
                CROSS JOIN ( SELECT idsolicitud, uuid, foliofac, rfc_emisor, total, subtotal, 0.00 tasacuotat, tasatras0, IEPStras, ISRtras, rettras FROM facturas WHERE tipo_factura IN ( 3 ) AND tasatras0 IS NOT NULL AND tasatras0 > 0 ) f 
                ON f.idsolicitud = solpagos.idsolicitud
                UNION
                /*REGISTROS TASA EXCENTO*/
                SELECT 
                    solpagos.idsolicitud, 
                    solpagos.justificacion, 
                    solpagos.moneda, 
                    solpagos.servicio, 
                    solpagos.idEmpresa, 
                    solpagos.proyecto, 
                    f.uuid, 
                    f.foliofac, 
                    f.rfc_emisor, 
                    f.total, 
                    ( f.subtotal + IEPStras ) subtotal, 
                    'NA' tasacuotat, 
                    IF(f.tasatrasExp < 0, f.tasatrasExp * -1, f.tasatrasExp)  importet,
                    ISRtras,  
                    rettras IVAtras
                FROM ( SELECT idsolicitud FROM autpagos_caja_chica WHERE idpago = $idpago_cch ) sp 
                CROSS JOIN ( SELECT * FROM solpagos WHERE solpagos.caja_chica = 1 AND tendrafac IS NOT NULL AND solpagos.idetapa IN ( 11 )  ) solpagos ON CONCAT( ',',sp.idsolicitud,',' ) LIKE CONCAT( '%,',solpagos.idsolicitud,',%' )
                CROSS JOIN ( SELECT idsolicitud, uuid, foliofac, rfc_emisor, total, subtotal, 0.00 tasacuotat, tasatrasExp, IEPStras, ISRtras, rettras FROM facturas WHERE tipo_factura IN ( 3 ) AND tasatrasExp IS NOT NULL AND tasatrasExp != 0 ) f 
                ON f.idsolicitud = solpagos.idsolicitud
                UNION
                /*REGISTROS SIN FACTURA*/
                SELECT 
                    solpagos.idsolicitud, 
                    solpagos.justificacion, 
                    solpagos.moneda, 
                    solpagos.servicio, 
                    solpagos.idEmpresa, 
                    solpagos.proyecto, 
                    NULL uuid, 
                    NULL foliofac, 
                    NULL rfc_emisor, 
                    NULL total, 
                    NULL subtotal, 
                    NULL tasacuotat, 
                    NULL importet,
                    0 ISRtras,  
                    0 IVAtras
                FROM ( SELECT idsolicitud FROM autpagos_caja_chica WHERE idpago = $idpago_cch ) sp
                INNER JOIN ( SELECT * FROM solpagos WHERE solpagos.caja_chica = 1 AND tendrafac IS NULL AND solpagos.idetapa IN ( 11 )  ) solpagos ON CONCAT( ',',sp.idsolicitud,',' ) LIKE CONCAT( '%,',solpagos.idsolicitud,',%' )   
            ) sol
            LEFT JOIN ( SELECT idinsumo, insumo, descripcion_insumo FROM insumos ) i ON sol.servicio = i.idinsumo
            LEFT JOIN ( SELECT rfc_prov, idempresa, clvneo, moneda_sat, tasa FROM prov_empresa_clvneo ) neoprov ON neoprov.rfc_prov = sol.rfc_emisor AND neoprov.idempresa = sol.idempresa AND sol.moneda = neoprov.moneda_sat AND sol.tasacuotat = neoprov.tasa
            ORDER BY FACTURA";
        
            $this->load->dbutil();

            header("Content-type: text/csv;charset=UTF-8"); 
            header('Content-Disposition: attachment;filename="REPORTE_DESGLOSADO_CAJA_CHICA_'.$idpago_cch. "_.csv".'"');
            header("Pragma: no-cache");

            echo chr(239).chr(187).chr(191).$this->dbutil->csv_from_result($this->db->query($sql), ",", "\r\n");
        }
    }

    /** MEJORA INICIO FECHA: 29-ABRIL-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
    //REPORTE PARA DESCARGAR LAS SOLICITUDES TRASPASOS Y DEVOLUCIONES EN CURSO | CONTRALORIA
    /**
     * INICIO FECHA 16-04-2025 | @uthor Efrain Martinez Muñoz <programador.analista38@ciudadmaderas.com>
     * Se agrego la columna ESTATUS LOTE que muestra si el estatus de lote es CON CONSTRUCCIÓN O BALDÍO
     * */
    public function reporteSolicitudDevolucionesTraspasosEnCurso(){
        // Ajuste de tiempo máximo de ejecución
        ini_set('max_execution_time', 0);
        set_time_limit(0);

        // Ajuste de límite de memoria
        ini_set('memory_limit', '-1');

        $finicio   = $this->input->post("fechaInicial") ? date("Y-m-d", strtotime(str_replace("/", "-", $this->input->post("fechaInicial")))).' 00:00:00' : ''; // Rango de fecha inicial
        $ffin      = $this->input->post("fechaFinal") ? date("Y-m-d", strtotime(str_replace("/", "-", $this->input->post("fechaFinal")))).' 23:59:59' : ''; // Rango de fecha final
        $idUsuario = $this->session->userdata("inicio_sesion")['id'];

        $filtros = [
            'idsolicitud'  => $this->input->post("#")
            , 'empresa'    => $this->input->post("EMPRESA")
            , 'cliente'    => $this->input->post("CLIENTE")
            , 'lote'       => $this->input->post("LOTE")
            , 'fEntregaPV' => $this->input->post("F_ENTREGA_PV")
            , 'proceso'    => $this->input->post("PROCESO")
            , 'fechVoBo'   => $this->input->post("FECHA_VOBO")
            , 'restante '  => $this->input->post("RESTANTE")
            , 'diasT'      => $this->input->post("DIAS_T")
            , 'rechazos'   => $this->input->post("RECHAZOS")
            , 'estatus'    => $this->input->post("ESTATUS")
            , 'fCaptura'   => $this->input->post("F_CAPTURA")
        ];

        $start_time = microtime(true);
        $offset = 0;
        $limit = 1000;

        // Crear el objeto Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Titulo de la hoja
        $sheet->setTitle('REPORTE DEVOLUCIONES EN CURSO');

        // Función para obtener las letras de las columnas, desde "A" hasta "AG"
        function getColumnLetters($start, $end) {
            $columnas = [];
            for ($i = $start; $i <= $end; $i++) {
                $columnas[] = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i);
            }
            return $columnas;
        }

        // Generar el rango de columnas desde la columna 1 (A) hasta la columna 35 (AI)
        $columnas = getColumnLetters(1, 35); // 1 es A, 35 es AI

        // Combinar celdas de A1 a AI1 y agregar "CUENTAS POR PAGAR"
        $sheet->mergeCells('A1:AI1');
        $sheet->setCellValue('A1', 'CUENTAS POR PAGAR');
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14); // Negritas y tamaño de fuente más grande

        // Combinar celdas de A2 a AI2 y agregar "DEVOLUCIONES EN CURSO"
        $sheet->mergeCells('A2:AI2');
        $sheet->setCellValue('A2', 'REPORTE DEVOLUCIONES EN CURSO ' . date("Y-m-d H:i:s"));
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A2')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(12); // Negritas y tamaño de fuente


        // Aplicar negrita a los encabezados
        $sheet->getStyle('A3:AI3')->getFont()->setBold(true);
        // Aplicar fondo de color #D4AD8F a los encabezados
        $sheet->getStyle('A3:AE3')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('D4AD8F');
        // Aplicar fondo de color #C69570 a los encabezados
        $sheet->getStyle('AF3:AI3')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('C69570');
        // Aplicar bordes a los encabezados
        $sheet->getStyle('A3:AI3')->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN, // Línea delgada
                    'color' => ['rgb' => '000000'] // Color negro
                ]
            ]
        ]);

        // Encabezados
        $headers = [
            '#' , 'EMPRESA' , 'CLIENTE' , 'LOTE', 'REF. LOTE', 'ESTATUS LOTE', 'ETAPA' , 'F ENTREGA PV'
            , 'PROCESO', 'JUSTIFICACION', 'SOLICITANTE', 'CUENTA CONTABLE', 'CUENTA ORDEN'
            , 'COSTO LOTE', 'SUPERFICIE', 'PRECIO M2', 'PREDIAL', 'PENALIZACIÓN', 'IMPORTE APORTADO'
            , 'MANTENIMIENTO', 'MOTIVO', 'DETALLE MOTIVO', 'ULT VOBO', 'FECHA VOBO', 'CANTIDAD'
            , 'PAGADO', 'RESTANTE', 'DIAS T', 'RECHAZOS', 'ESTATUS', 'F CAPTURA'
            , 'HISTORIAL OBS.', 'MOVIMIENTO', 'USUARIO', 'DEPARTAMENTO'
        ];

        // Insertar encabezados en la hoja activa a partir de la celda A3
        $sheet->fromArray($headers, NULL, 'A3');

        // Ajustar automáticamente el tamaño de las columnas
        foreach (range('A', 'AI') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $fila = 4; // Comienza en la fila 4

        // Estilo de borde solo en el contorno externo
        $borderStyle = [
            'borders' => [
                'outline' => [ // Aplica el borde solo al contorno
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'], // Color negro
                ],
            ],
        ];

        // Ahora vamos a agregar los datos de los padres e hijos
        $lote_start_time = microtime(true);  // Tiempo de inicio para este lote

        $tiempo_lote = '';
        do {
            // Reiniciar el límite de tiempo para cada lote
            set_time_limit(0);

            $registros = $this->Model_Reportes->reporteSolicitudDevolucionesTraspasosEnCursoModel($finicio, $ffin, $idUsuario, $filtros, $limit, $offset);

            $datosPadres = $registros[0];
            $datosHijos = $registros[1];

            if (empty($datosPadres)) break;

            // 1. Agrupar los hijos por idsolicitud, solo si hay hijos
            $hijosAgrupados = [];
            if (!empty($datosHijos)) {
                foreach ($datosHijos as $hijo) {
                    $hijosAgrupados[$hijo['idsolicitud']][] = $hijo;
                }
            }

            $fila = 4; // Iniciamos en la primera fila para los registros en Excel

            // Ajustar texto en las columnas AD
            $sheet->getStyle('AF:AE')->getAlignment()->setWrapText(true);  // Columna AD
            // Centrar el texto verticalmente en las columnas AC, AD, AE, y AF
            $sheet->getStyle('AF:AI')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

            // 2. Recorremos los datos del padre
            foreach ($datosPadres as $padre) {
                $idsolicitudPadre = $padre['idsolicitud'];

                // Verificamos si el padre tiene hijos
                $tieneHijos = isset($hijosAgrupados[$idsolicitudPadre]) && count($hijosAgrupados[$idsolicitudPadre]) > 0;
                $color = $tieneHijos ? 'EBF1DE' : 'F2DCDB'; // Verde si tiene hijos, rojo si no

                // Agregar datos adicionales al padre
                $padre['historial'] = $tieneHijos ? "+ Expandir" : "Sin Observaciones";
                $padre['observacion'] = '';
                $padre['usuario'] = '';
                $padre['departamento'] = '';

                // Escribir la fila del padre en Excel antes de formatear la celda de historial
                $sheet->fromArray($padre, NULL, "A{$fila}");

                // Establecer el color y el texto en la celda de historial
                $historialCelda = "AF{$fila}:AI{$fila}";
                $sheet->mergeCells($historialCelda);
                $sheet->setCellValue("AF{$fila}", $padre['historial']);
                $sheet->getStyle($historialCelda)->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setARGB($color);
                
                // Escribir la fila del padre en Excel
                $sheet->fromArray($padre, NULL, "A{$fila}");

                // Aplicar el borde a la fila del padre
                $sheet->getStyle("A{$fila}:AI{$fila}")->applyFromArray($borderStyle);

                // Mantener la fila del padre visible siempre
                $sheet->getRowDimension($fila)->setOutlineLevel(0)->setVisible(true);

                // 3. Procesar los hijos si existen
                if ($tieneHijos) {
                    $filaActual = $fila + 1;
                    foreach ($hijosAgrupados[$idsolicitudPadre] as $hijo) {
                        unset($hijo['idsolicitud']); // Remover el idsolicitud del hijo

                        $colIndex = 0;
                        $columnasHijo = ["AF", "AG", "AH", "AI"]; // Columnas para los hijos

                        foreach ($hijo as $valor) {
                            if ($colIndex < count($columnasHijo)) {
                                $sheet->setCellValue($columnasHijo[$colIndex] . $filaActual, $valor);
                                $colIndex++;
                            }
                        }

                        // Ocultar fila hijo y agruparla bajo el padre
                        $sheet->getRowDimension($filaActual)->setVisible(false);
                        $sheet->getRowDimension($filaActual)->setOutlineLevel(1);

                        $filaActual++;
                    }

                    // Colapsar las filas de los hijos
                    for ($i = $fila + 1; $i < $filaActual; $i++) {
                        $sheet->getRowDimension($i)->setOutlineLevel(1)->setVisible(false);
                    }
                    
                    $fila = $filaActual; // Avanzamos la fila después de los hijos
                } else {
                    $fila++; // Avanzamos solo una fila si no tiene hijos
                }
            }

            // Ajustar el tamaño de las columnas
            $columnasConAnchoFijo = ['C', 'J', 'V', 'AG'];  // Define las columnas con un ancho fijo

            foreach ($columnas as $columna) {
                if (in_array($columna, $columnasConAnchoFijo)) {
                    $sheet->getColumnDimension($columna)->setWidth(30);  // Establecer un ancho fijo para las columnas
                } else {
                    $sheet->getColumnDimension($columna)->setAutoSize(true);  // Autoajustar el tamaño para las demás columnas
                }
            }

            $lote_end_time = microtime(true);  // Tiempo de finalización para este lote
            $lote_execution_time = floor($lote_end_time - $lote_start_time);  // Tiempo que tardó este lote

            // Guardamos el tiempo del lote en el log
            $tiempo_lote .= "Offset: " . $offset . " + " . $limit . " - Tiempo de ejecución del lote: " . $lote_execution_time . " Segundos\n";

            // // Escribimos en el archivo de log
            file_put_contents(FCPATH . 'UPLOADS/XLSX/temp/error_log_lote.txt', "TIEMPO DE RESPUESTA LOTE: " . $tiempo_lote . "\n", LOCK_EX);

            // Actualizamos el offset
            $offset += $limit;
        } while (count($registros) > 0);

        // Habilitar la funcionalidad de expandir/colapsar filas
        $sheet->setShowSummaryBelow(false);

        // Aplicar filtro automaticamente
        $sheet->setAutoFilter('A3:AI3');

        // Establecer la congelación de la fila 3 y la columna A
        $sheet->freezePane('B4');  // Fija la fila 3 y la columna A (todo antes de la celda B4)

        // Nombre del archivo a descargar
        $filename = 'REPORTE_SOLICITUDES_DEVOLUCIONES_EN_CURSO_' . date("Y.m.d_H.i.s") . '.xlsx';

        try {
            // Iniciar el buffer de salida para capturar el archivo
            ob_start();

            // Crear el escritor de Excel
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

            // Guardar en el buffer en lugar de un archivo
            $writer->save('php://output');

            // Capturar el contenido del buffer
            $file_content = ob_get_contents();
            ob_end_clean();

            // Calcular el tiempo de ejecución
            $end_time = microtime(true);
            $execution_time = round($end_time - $start_time, 3);
            file_put_contents(FCPATH . 'UPLOADS/XLSX/temp/error_log.txt', "TIEMPO DE RESPUESTA FINAL: " . $execution_time . " segundos\n", LOCK_EX);

            // Enviar el tiempo de ejecución en el encabezado "Server-Timing"
            header("Server-Timing: executionTime;dur=$execution_time");

            // Codificar el archivo en base64 para enviarlo como parte de la respuesta JSON
            $base64_file = base64_encode($file_content);

            // Preparar la respuesta JSON
            $response = [
                'status' => 'success',
                'filename' => $filename,
                'execution_time' => $execution_time,
                'file_base64' => $base64_file, // Archivo en base64
            ];

            // Enviar la respuesta JSON
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;

        } catch (Exception $e) {
            // Capturar cualquier error y enviar respuesta JSON
            $response = [
                'status' => 'error',
                'message' => $e->getMessage(),
            ];
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }
    } /** FIN FECHA: 29-ABRIL-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/

    /** MEJORA: INICIO FECHA: 03-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
    // Reporte para descargar el HISTORIAL TRASPASOS Y DEVOLUCIONES | CONTRALORIA
    /**
     * INICIO FECHA 16-04-2025 | @uthor Efrain Martinez Muñoz <programador.analista38@ciudadmaderas.com>
     * Se agrego la columna ESTATUS_LOTE que muestra si el estatus de lote es CON CONSTRUCCIÓN O BALDÍO
     **/
    /** Mejora version 1.2 
     * FECHA: 21-MAYO-2025 | @author DANTE ALDAIR GUERRERO ALDANA <coordinador6.desarrollo@ciudadmaderas.com> 
    **/ 
    
    /**
     * Genera un reporte detallado del historial de traspasos y devoluciones,
     * aplicando múltiples filtros desde una solicitud POST y exportando los datos
     * en formato NDJSON comprimido (.gz) para su posterior procesamiento en la nube.
     *
     * Este reporte incluye solicitudes principales y sus nodos hijos relacionados,
     * además de controlar tiempos de ejecución y uso de memoria para grandes volúmenes de datos.
     *
     * @author      DANTE ALDAIR GUERRERO ALDANA <coordinador6.desarrollo@ciudadmaderas.com> 
     * @copyright   Desarrollo 2 TI CXP
     * @version     1.2
     * @since       16-04-2025 - BY EFRAIN MARTINEZ MUÑOZ <programador.analista38@ciudadmaderas.com>
     * 
     * @access      public
     * @return      void  Imprime un JSON con la URL pública del archivo generado, el endpoint de procesamiento y el nombre del archivo.
    */
    
    public function reporteHistorialTraspasosDevoluciones(){
        // Ajuste de tiempo máximo de ejecución
        ini_set('max_execution_time', 0);
        set_time_limit(0);

        // Ajuste de límite de memoria
        ini_set('memory_limit', '-1');

        // Determinar arreglo de estados según la opción de "activos"
        $activos          = $this->input->post("activos") > 0 ? array(3, 7, 50, 51, 52, 53, 11) : array(30, 54, 6, 21, 8, 0); // Opcion Seleccionada

        // Obtener fechas de inicio y fin, transformando el formato recibido (dd/mm/yyyy a yyyy-mm-dd)
        $finicio          = $this->input->post("finicio") ? date("Y-m-d", strtotime(str_replace("/", "-", $this->input->post("finicio")))).' 00:00:00' : ''; // Rango de fecha inicial
        $ffin             = $this->input->post("ffin") ? date("Y-m-d", strtotime(str_replace("/", "-", $this->input->post("ffin")))).' 23:59:59' : ''; // Rango de fecha final

        // Obtener el ID del usuario de sesión actual
        $idUsuario        = $this->session->userdata("inicio_sesion")['id'];

        // Inicializar filtros dinámicos y arreglo para datos de salida
        $filtros = '';
        $datosEnviarGoogle = [];

        // Aplicar filtros dinámicos según los valores recibidos por POST
        $filtros .= $this->input->post("#")                ? " AND (sp.idsolicitud LIKE '%".$this->input->post("#")."%')" : '';
        $filtros .= $this->input->post("EMPRESA")          ? " AND (emp.abrev LIKE '%".$this->input->post("EMPRESA")."%')" : '';
        $filtros .= $this->input->post("PROYECTO")         ? " AND (IF(spo.idProyectos IS NULL, sp.proyecto, pd.nombre) LIKE '%".$this->input->post("PROYECTO")."%')" : '';
        $filtros .= $this->input->post("SERVICIO/PARTIDA") ? " AND (IFNULL(tsp.nombre, 'NA') LIKE '%".$this->input->post("SERVICIO/PARTIDA")."%')" : '';
        $filtros .= $this->input->post("OFICINA/SEDE")     ? " AND (IFNULL(os.nombre, 'NA') LIKE '%".$this->input->post("OFICINA/SEDE")."%')" : '';
        $filtros .= $this->input->post("FOLIO")            ? " AND (IFNULL(sp.folio, 'NA') LIKE '%".$this->input->post("FOLIO")."%')" : '';
        $filtros .= $this->input->post("F_CAPTURA")        ? " AND (DATE(sp.fechaCreacion) LIKE '%".fechaSinFormato($this->input->post("F_CAPTURA"))."%')" : '';
        $filtros .= $this->input->post("F_VoBo_CONT")      ? " AND (DATE(sp.fecha_autorizacion) LIKE '%".fechaSinFormato($this->input->post("F_VoBo_CONT"))."%')" : '';
        $filtros .= $this->input->post("CAPTURISTA")       ? " AND (capturista.nombre_completo LIKE '%".$this->input->post("CAPTURISTA")."%')" : '';
        $filtros .= $this->input->post("PROVEEDOR")        ? " AND (prov.nombre LIKE '%".$this->input->post("PROVEEDOR")."%')" : '';
        $filtros .= $this->input->post("LOTE")             ? " AND (sp.condominio LIKE '%".$this->input->post("LOTE")."%')" : '';
        $filtros .= $this->input->post("MOVIMIENTO")       ? " AND (sp.nomdepto LIKE '%".$this->input->post("MOVIMIENTO")."%')" : '';
        $filtros .= $this->input->post("FORMA_PAGO")       ? " AND (sp.metoPago LIKE '%".$this->input->post("FORMA_PAGO")."%')" : '';
        $filtros .= $this->input->post("CANTIDAD")         ? " AND (sp.cantidad LIKE '%".$this->input->post("CANTIDAD")."%')" : '';
        $filtros .= $this->input->post("DIAS_T")           ? " AND (IFNULL(log_efi.diasTrans, 0) LIKE '%".$this->input->post("DIAS_T")."%')" : '';
        $filtros .= $this->input->post("RECHAZOS")         ? " AND (IFNULL(log_efi.numCancelados, 0) LIKE '%".$this->input->post("RECHAZOS")."%')" : '';

        // Filtro para determinar qué proceso inició la solicitud
        $filtros .= $this->input->post("INICIADO_POR")   
            ? "AND (CASE 
                        WHEN sp.idproceso IN (1, 2, 3, 4, 7, 31, 32, 33, 35) THEN 'ADMINISTRACIÓN'
                        WHEN sp.idproceso IN (5, 6, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 25, 26, 27, 28, 29, 30, 34) THEN 'POSTVENTA'
                        ELSE NULL 
                    END LIKE '%".$this->input->post("INICIADO_POR")."%')" : '';

        // Filtro por estatus de la solicitud
        $filtros .= $this->input->post("ESTATUS")
            ? "AND (CASE
                        WHEN cant_pag.estatus_pago = 15 AND sp.metoPago = 'TEA' THEN 'POR CONFIRMAR PAGO'
                        WHEN cant_pag.estatus_pago = 15 AND sp.metoPago = 'ECHQ' THEN 'POR ENTREGAR ECHQ'
                        WHEN cant_pag.estatus_pago = 0 AND sp.idetapa IN (6, 8, 21, 54, 30) THEN et.nombre
                        ELSE cant_pag.etapa_pago
                    END LIKE '%".$this->input->post("ESTATUS")."%')" :'';

        // Encabezados para el reporte
        $datosEnviarGoogle[]['encabezados'] = [
            "# SOLICITUD", "EMPRESA", "PROYECTO", "SERVICIO/PARTIDA", "OFICINA/SEDE", "FOLIO", "F CAPTURA", "F VoBo CONT",
            "F FACT", "CAPTURISTA", "PROVEEDOR", "LOTE", "REF. LOTE", "ESTATUS LOTE", "MANTENIMIENTO", "PREDIAL", "MOTIVO", "DETALLE MOTIVO",
            "MOVIMIENTO", "FORMA PAGO", "CANTIDAD", "JUSTIFICACION", "F AUT PAGO", "F DISPERSION", "F ENTREGA", "F COBRO", 
            "DIAS T", "RECHAZOS", "INICIADO POR", "ESTATUS", "HISTORIAL OBS.", "MOVIMIENTO", "USUARIO", "DEPARTAMENTO"
        ];
        
        // Obtener datos principales desde el modelo
        $principales = $this->Model_Reportes->reporteHistorialTraspasosDevolucionesModel($activos, $finicio, $ffin, $idUsuario, $filtros)->result_array();

        // Obtener las solicitudes secundarias agrupadas por solicitud principal
        $idSolicitudes = implode(',', array_column($principales, 'idsolicitud'));
        $secundariosPorPrincipal = $this->Model_Reportes->getSecundariosPorPrincipales($idSolicitudes);

        // Agrupar los hijos por ID de solicitud
        $hijosPorSolicitud = [];
        foreach ($secundariosPorPrincipal as $hijo) {
            $hijosPorSolicitud[$hijo['idsolicitud']][] = $hijo;
        }

        // Asociar hijos con su solicitud principal correspondiente
        foreach ($principales as &$principal) {
            $principal['nodoHijo'] = $hijosPorSolicitud[$principal['idsolicitud']] ?? [];
        }
        unset($principal); // Romper la referencia después del foreach (buena práctica)

        // Generar nombre del archivo de salida
        $datosEnviarGoogle[]['nombreArchivo'] = 'REPORTE_HISTORIAL_TRASPASOS_DEVOLUCIONES_' . date('Ymd_His') . '.xlsx';

        // Asignar los datos finales al arreglo de salida
        $datosEnviarGoogle['datos'] = $principales;

        // Nombre único para el archivo temporal NDJSON comprimido
        $nomArchivoTmp = 'reporte_'. uniqid() .'.ndjson.gz';
        $rutaTmp = FCPATH.'UPLOADS/TEMP/REPORTES_CONTRALORIA/'.$nomArchivoTmp;
        
        // URL pública temporal para acceder al archivo generado -> esto para local y utilizando ngrok, se tendria que cambiar para pase a pruebas y/o prod
        $urlPublica = base_url("/UPLOADS/TEMP/REPORTES_CONTRALORIA/{$nomArchivoTmp}");

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

        // Control de tiempo máximo para evitar bloqueos prolongados, esto a razon del error 502 por exeso de tiempo de respuesta
        $timeoutSegundos = 60;
        $inicio = time();

        // Escribir cada solicitud en formato NDJSON como línea separada
        foreach ($principales as $i => $registro) {
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

        // Enviar respuesta JSON y finalizar ejecución
        header('Content-Type: application/json');
        echo json_encode($respuesta);
        exit;
    } /** MEJORA: FIN FECHA: 03-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
    /** FIN FECHA: 21-MAYO-2025 | @author DANTE ALDAIR GUERRERO ALDANA <coordinador6.desarrollo@ciudadmaderas.com> **/

    function estatus_pagotext($row){
        switch($row->estatus_pago){
            case '0':
                return 'PAGO AUTORIZADO DG';
                break;
            case '1':
                return 'POR DISPERSAR';
                break;
            case '2':
                return 'SE HA PAUSADO EL PROCESO DE ESTE PAGO';
                break;
            case '5':
                return 'POR DISPERSAR';
                break;
            case '12':
                return 'PAGO DETENIDO';
                break;
            case '15':
                return ''.($row->metoPago=='ECHQ'?'CHEQUE SIN COBRAR':'POR CONFIRMAR PAGO CXP').'';
                break;
            case '16':
                if($row->estatus_pago && $row->estatus_pago == '16' && ($row->etapa=='Pago Aut. por DG, Factura Pendiente'||$row->etapa=='Pagado')){ 
                    return 'PAGO COMPLETO' ;
                }
                else{
                    return ''.$row->etapa.'';
                }
                break;
            default:
                if( $row->etapa == 'Revisión Cuentas Por Pagar'){
                    $fec = date("Y-m-d").'';//new Date(fec[1]+'/'+fec[0]+"/"+fec[2]);
                    $day = date("d");
                    $dias = 2-$day;
                    if ($dias > 0){
                        $fec=date('Y-m-d', strtotime($fec. ' + '.$dias.' days'));
                    }else if ($dias < 0){
                        $fec=date('Y-m-d', strtotime($fec. ' + '.($dias+7).' days'));
                    }else if( $dias == 0){                                
                        if($row->fecha_autorizacion == strtotime($fec)){
                            $fec=date('Y-m-d', strtotime($fec. ' + 7 days'));
                        }
                    }
                    return 'Próxima revisión de Cuentas por Pagar '.($fec);
                }else{

                    if( $row->idetapa > 9 && $row->idetapa < 20 && $row->idprovision == null && ( $row->caja_chica == 0 || $row->caja_chica == null ) && $row->tipo_factura == 1 ){
                        return 'FACTURA PAGADA, EN ESPERA DE PROVISIÓN'; 
                    }else{
                        return $row->etapa;
                    }

                }
            break;
        }
    }
    /**
     * @author DANTE ALDAIR GUERRERO ALDANA <programador.analista18@ciudadmaderas.com>
     * 
     * FECHA CREACION: 30/05/2024.
     * 
     * REQUERIMIENTO:
     * SE CREA FUNCION PARA CREACION DE ARCHIVO DE EXCEL PARA REQUERIMIENTO DEL DEPARTAMENTO DE CONTABILIDAD.
     * ESTO SE REALIZA CON EL PROPÓSITO DE MANTENER UNA BITÁCORA DE MOVIMIENTOS Y REPORTES NECESARIOS PARA 
     * MEDIR EL TIEMPO DE RESPUESTA AL CLIENTE.
     *
    */
    
     function descarga_bitacora_xlsx() {
        // Ajuste de tiempo máximo de ejecución
        ini_set('max_execution_time', 0);
        set_time_limit(0);

        // Ajuste de límite de memoria
        ini_set('memory_limit', '-1');

        $fecha_inicio = $this->input->post('fecha_inicio');
        $fecha_fin = $this->input->post('fecha_fin');

        $start_time = microtime(true);
        $offset = 0;
        $limit = 15000;

        // $datos = $this->Model_Reportes->get_history_observations($fecha_inicio, $fecha_fin)->result_array();
        
        // if (empty($datos)) {
        //     file_put_contents(FCPATH . 'UPLOADS/XLSX/error_log.txt', "No data available for the given date range.\n", FILE_APPEND);
        //     return;
        // }

        $temp_file = tempnam(sys_get_temp_dir(), 'bitacora_') . '.xlsx';

        $excel = new Spreadsheet();
        $hojaActv = $excel->getActiveSheet();
        $hojaActv->setTitle("ARCHIVO BITACORA");
        
        $styleTitulos = [
            'font'  =>  [
                'bold'  => true
            ],
            'alignment'  =>  [
                'horizontal'    =>  Alignment::HORIZONTAL_CENTER,
                'vertical'      =>  Alignment::VERTICAL_CENTER,
            ],
            'borders'    =>  [
                'bottom'    =>  [
                    'borderStyle'  =>  Border::BORDER_THICK,
                ],
            ]
        ];

        $estiloCeldasCombinadas = [
            'alignment'  =>  [
                'horizontal'    =>  Alignment::HORIZONTAL_CENTER,
                'vertical'      =>  Alignment::VERTICAL_CENTER,
            ],
        ];

        $estiloCuerpoXls = [
            0   =>  ['borders' => [
                        'allBorders' => [
                            'borderStyle'   =>  Border::BORDER_DOUBLE,
                            'color'         =>  ['argb' => '18345C'],
                        ],
                    ],
                    'fill'  =>  [
                        'fillType'  =>  Fill::FILL_SOLID,
                        'startColor'    =>  ['argb' =>  'E4E4E4']
                    ]
            ],
            1   =>  ['borders' => [
                        'allBorders' => [
                            'borderStyle'   =>  Border::BORDER_DOUBLE,
                            'color'         =>  ['argb' => '18345C'],
                        ],
                    ],
                    'fill'  =>  [
                        'fillType'  =>  Fill::FILL_SOLID,
                        'startColor'    =>  ['argb' =>  'FFFFFF']
                    ]
            ]
        ];
        
        $hojaActv->setCellValue("A1", "REPORTE SISTEMA CUENTAS POR PAGAR");
        $hojaActv->mergeCells("A1:F1");
        $hojaActv->getStyle("A1:F2")->applyFromArray($styleTitulos);

        // ENCABEZADOS
        $headers = ["ID SOLICITUD", "NOMBRE USUARIO", "DEPARTAMENTO", "HISTORIAL", "OBSERVACION", "FECHA DE MOVIMIENTO"];
        $hojaActv->fromArray($headers, NULL, 'A2');

        foreach (range('A', 'F') as $columnID) {
            $hojaActv->getColumnDimension($columnID)->setAutoSize(true);
        }
        
        $hojaActv->mergeCells("A3:F3");
        $hojaActv->getRowDimension(3)->setRowHeight(4, 'px');

        $filaInfo = 4;

        do {
            $datos = $this->Model_Reportes->get_history_observations($fecha_inicio, $fecha_fin, $limit, $offset)->result_array();
            if (empty($datos)) {
                break;
            }
            
            foreach ($datos as $dato) {

                $hojaActv->fromArray([
                    $dato['idsolicitud'],
                    $dato['nom_usuario'],
                    $dato['depto'],
                    $dato['msj_historial'],
                    $dato['msj_obs'],
                    $dato['fecha']
                ], NULL, 'A' . $filaInfo);                
                
                $filaInfo++;
            }
            $offset += $limit;
        } while (count($datos) > 0);
        $end_time = microtime(true);
        $execution_time = $end_time - $start_time;
        file_put_contents(FCPATH . 'UPLOADS/XLSX/error_log.txt', "TIEMPO DE RESPUESTA:" . $execution_time . "\n", FILE_APPEND);

        $writer = IOFactory::createWriter($excel, 'Xlsx');
        $writer->save($temp_file);
        
        // ENVIAR ENCABEZADOS.
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="BITACORA.xlsx"');
        header('Cache-Control: max-age=0');
        readfile($temp_file);
        unlink($temp_file);
        exit;
    }

    function reporteFacturasProvisiones(){
        ini_set('memory_limit', '-1'); // O más, dependiendo de la cantidad de datos
        set_time_limit(0); // Sin límite de tiempo de ejecución
        ini_set('max_execution_time', 0);

        // Aumentar el tamaño máximo para archivos subidos y datos POST
        ini_set('upload_max_filesize', '2048M'); // 100MB
        ini_set('post_max_size', '2048M');       // 100MB

        // Aumentar el tiempo máximo de ejecución del script
        ini_set('max_execution_time', 600);    // 300 segundos = 5 minutos
        
        $url = 'https://us-central1-cuentas-x-pagar-431918.cloudfunctions.net/generateExcel';
        $registros = $this->Model_Reportes->getRequestWithInvoices($this->input->post('fecha_inicio'), $this->input->post('fecha_fin'))->result_array();
        
        // Usar cURL para enviar la solicitud POST
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($registros));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
        ]);

        // Ejecutar la solicitud
        $response = curl_exec($ch);
        curl_close($ch);

        // Si la solicitud fue exitosa, cURL devolverá el archivo Excel
        if ($response) {
            $filePath = './UPLOADS/XLSX/polizas_facturas.xlsx';
            // Guardar el archivo Excel en el directorio actual
            file_put_contents('./UPLOADS/XLSX/polizas_facturas.xlsx', $response);
            // Ahora vamos a ofrecer el archivo para que el usuario lo descargue
            $this->descargarArchivo($filePath);  // Llamada a la función para descargar el archivo
        } else {
            echo "Error al generar el archivo Excel.";
        }

    }

    function descargarArchivo($filePath){
        // Verificar si el archivo existe
        if (file_exists($filePath)) {
            // Establecer los encabezados para forzar la descarga
            header('Content-Description: File Transfer');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');  // Tipo MIME para Excel
            header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
            header('Content-Length: ' . filesize($filePath));  // Longitud del archivo
            header('Cache-Control: no-cache, no-store, must-revalidate');  // Evitar cachés
            header('Pragma: no-cache');
            header('Expires: 0');
            
            // Leer el archivo y enviarlo al navegador
            readfile($filePath);

            // Opcional: Eliminar el archivo del servidor después de la descarga
            unlink($filePath);  // Si no quieres mantener el archivo después de la descarga
            exit;  // Termina el script para evitar que se ejecute más código después de la descarga
        } else {
            echo "El archivo no existe.";
        }
    }

    /**
     * Elimina un archivo en el servidor con base en una ruta relativa recibida por POST.
     * Solo permite eliminar archivos dentro de una carpeta segura.
     */
    public function eliminarArchivo() {
        // Obtenemos el nombre del archivo
        $nomArchivoNdJson = $this->input->post('nomArchivoNdJson');
        
        // ruta del archivo de excel segun sea el caso
        $rutaArchivo = $this->input->post('rutaArchivo') ?? '';
        
        // Definimos la ruta del archivo.
        if ($rutaArchivo != '' || $rutaArchivo) {
            $rutaArchivo = FCPATH . 'UPLOADS/TEMP/' . $rutaArchivo . '/' . $nomArchivoNdJson;
        }else{
            $rutaArchivo = FCPATH . 'UPLOADS/TEMP/REPORTES_CONTRALORIA/' . $nomArchivoNdJson;
        }

        // Validamos si es que existe en el servidor
        if (file_exists($rutaArchivo)) {
            // Eliminamos archivo y validamos que se haya eliminado correctamente
            if (unlink($rutaArchivo)) {
                echo json_encode(array("estatus" => "ok"));
            }else {
                echo json_encode(array("estatus" => "error", "mensaje" => "No fue posible eliminar el archivo. Por favor, contacte al administrador del sistema."));
            }
        }else {
            // El archivo no existe o posiblemente este mal el nombre o ruta.
            echo json_encode(array("estatus" => "error", "mensaje" => "Archivo no encontrado en el servidor o nombre erroneo. Por favor, contacte al administrador del sistema."));
        }

    }
}
