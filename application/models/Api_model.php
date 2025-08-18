<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 *
 */
class Api_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    /**
     * Funciones correspondientes a la API para desarrollo 3 en cuestion a la creacion de solicitudes de pago CXP 
     */
    function obtenerInfoUsuario($idusuario){
        return $this->db->query("SELECT idusuario, rol, depto, da, estatus  FROM usuarios WHERE idusuario = ? AND estatus = 1",[$idusuario]);
    }

    function obtenerIdProveedor($nomProvider, $rfc, $idBanco, $clabe, $email) {
        return $this->db->query("SELECT * FROM proveedores WHERE (nombre = ? OR alias = ?) AND rfc = ? AND idbanco = ? AND cuenta = ? AND email = ?;", [$nomProvider, $nomProvider, $rfc, $idBanco, $clabe, $email]);
    }

    function insertTable($tabla, $datos) {
        $this->db->insert( $tabla, $datos);
        return $this->db->insert_id();
    }

    public function getCatPreSol($idUsuario) {
        $datsoUsuario = $this->db->query("SELECT depto FROM usuarios WHERE idusuario = ? AND estatus = 1", [$idUsuario]);

        if($datsoUsuario->num_rows() == 0){
            return(array("msg" => "Usuario dado de baja y/o inexistente. Por favor, verificar ID del usuario ingresado.", "estatus" => false));
        }else{
            $formaPago = array(
                            array("descripcion" =>  "Cheque",
                                "valor"  =>  "ECHQ"),
                            array("descripcion" =>  "Transferencia electrónica",
                                "valor"  =>  "TEA"),
                            array("descripcion" =>  "Manual",
                                "valor"  =>  "MAN"),
                            array("descripcion" =>  "Domiciliario",
                                "valor"  =>  "DOMIC"),
                            array("descripcion" =>  "Efectivo",
                                "valor"  =>  "EFEC")
            );
            
            $this->db->query("SELECT depto FROM usuarios WHERE idusuario = ? AND estatus = 1", [$idUsuario])->num_rows() > 0
                ? $proveedores = $this->db->query("SELECT proveedores.idproveedor, proveedores.nombre, proveedores.alias, CONCAT(proveedores.nombre, ' - ', bancos.nom_banco, ' - ', proveedores.cuenta) AS provBancoClabe, proveedores.email, proveedores.rfc, bancos.idbanco, bancos.nom_banco AS nomBanco, proveedores.cuenta
                    FROM proveedores 
                    INNER JOIN (SELECT bancos.idbanco, bancos.nombre AS nom_banco FROM bancos ) AS bancos 
                        ON proveedores.idbanco = bancos.idbanco 
                    WHERE proveedores.idproveedor NOT IN (  SELECT idproveedor 
                                                            FROM provedores_bloqueados 
                                                            WHERE nomdepto = ( SELECT depto FROM usuarios WHERE idusuario = ? ) ) AND 
                        proveedores.estatus = 1 AND proveedores.excp IN ( 1, 2 );", array ( $idUsuario ))->result_array()
                : $proveedores = [];

            $empresas = $this->db->query("SELECT idempresa, nombre, abrev FROM empresas ORDER BY empresas.nombre;")->result_array();        

            return array("formaPago"   =>  $formaPago, "proveedores"   =>  $proveedores, "empresas"    =>  $empresas, "estatus" => true);
        }
    }

    function obtenerUsuarioContraseñaToken($usuario, $contraseña, $acceso) {
        // pass: ^MRHR8yoSF&%fv6#duwEMeN9t
        return $this->db->query("SELECT id_token, usuario, contraseña, token, token_expiracion, llave_token, acceso_informacion
                                 FROM token_acceso_api 
                                 WHERE usuario = ? AND contraseña = ? AND FIND_IN_SET(?, acceso_informacion)", [$usuario, $contraseña, $acceso]);
    }

    function actualizarRegToken($id_token, $token) {
        $token_expiracion = date('Y-m-d H:i:s', $token['tipo_expiracion']);
        $token = $token['id_token'];
        return $this->db->query("UPDATE token_acceso_api SET token = ?, token_expiracion = ? WHERE id_token = ?", array($token, $token_expiracion, $id_token));
    }

    function obtenerCatalogoProDepto() {
        return $this->db->query("SELECT pdos.idProyectos, pd.nombre AS nombreProyecto, pdos.idOficina, os.nombre AS nombreOficina, UPPER(edo.estado) AS nombreEstado
                                FROM proyectos_departamentos_oficina_sede AS pdos
                                INNER JOIN proyectos_departamentos AS pd
                                    ON pdos.idProyectos = pd.idProyectos AND pd.estatus = 1
                                INNER JOIN oficina_sede AS os
                                    ON pdos.idOficina = os.idOficina AND os.estatus = 1
                                LEFT JOIN estados AS edo
                                    ON os.id_estado = edo.id_estado
                                ORDER BY pdos.idProyectos, os.idOficina");
        
    }

    function obtenerCatalogoTipoServPart() {
        return $this->db->query("SELECT idTipoServicioPartida, nombre FROM tipo_servicio_partidas WHERE estatus = 1");
    }

    function validar_usuario($nickname, $pws) {
        $usuario = $this->db->query("SELECT idusuario, nickname, pass, depto FROM usuarios WHERE nickname='$nickname' AND estatus = 1");
        if($usuario->num_rows() > 0) {
            $usuario = $usuario->result_array()[0];
            if( desencriptar($usuario["pass"]) == base64_decode($pws) ){
                return $usuario;
            }
            return FALSE;
        }
        return FALSE;
    }
    public function obtener_prov_pre_def() {
        /*consulta que permite acceder a los registros de los proveedores que su situacion se encuentre en Definitivo y Presunto, tambien filtra los 
        proveedores por la fecha mas reciente en la que se encuentra el RFC del proveedor en caso que exista mas de un registro con el mismo rfc*/ 
        return $this->db->query("SELECT 
                                    t.Numero,
                                    t.Situacion_contribuyente,
                                    t.RFC
                                FROM 
                                    proveedores_articulo_69 t
                                    JOIN (
                                        SELECT 
                                            RFC,
                                            GREATEST(
                                                MAX(Publicacion_DOF_Definitivos),
                                                MAX(Publicacion_DOF_Sentencia_Favorable),
                                                MAX(Publicacion_DOF_Presuntos),
                                                MAX(Publicacion_DOF_Desvirtuados)
                                            ) AS max_fecha
                                        FROM 
                                            proveedores_articulo_69
                                        GROUP BY 
                                            RFC
                                    ) sub
                                    ON t.RFC = sub.RFC
                                WHERE  
                                    (
                                        t.Publicacion_DOF_Definitivos = sub.max_fecha
                                        OR t.Publicacion_DOF_Sentencia_Favorable = sub.max_fecha
                                        OR t.Publicacion_DOF_Presuntos = sub.max_fecha
                                        OR t.Publicacion_DOF_Desvirtuados = sub.max_fecha
                                    )
                                    AND t.RFC != 'XXXXXXXXXXXX'
                                    AND t.Situacion_contribuyente IN ('Definitivo', 'Presunto') 
                                ORDER BY 
                                    t.Numero ASC;");
    }

    public function obtener_prov_lista_negra($idProveedoresSAT){
         /*Consulta que permite acceder a los registros de los proveedores que su situacion se encuentre de Definitivo y Presunto y en la tabla de 
        proveedores el estatus sea 1,2,4,5 tambien los filtra por la fecha mas reciente en la que se encuentre un RFC en caso de que se encuentre
        mas de un registro con el mismo RFC*/

        return $this->db->query("SELECT 
        p.idproveedor,
        p.nombre,
        pro.* 
FROM   proveedores p
JOIN proveedores_articulo_69 pro 
        ON p.rfc = pro.RFC
JOIN (SELECT 
            Numero,
            RFC,
            Situacion_contribuyente,
            GREATEST(
                MAX(Publicacion_DOF_Definitivos),
                MAX(Publicacion_DOF_Sentencia_Favorable),
                MAX(Publicacion_DOF_Presuntos),
                MAX(Publicacion_DOF_Desvirtuados)
            ) AS max_fecha
        FROM 
            proveedores_articulo_69
        GROUP BY RFC) sub
ON pro.RFC = sub.RFC       
WHERE  
        p.idproveedor IN($idProveedoresSAT)
        AND 
        (
            pro.Publicacion_DOF_Definitivos = sub.max_fecha
            OR pro.Publicacion_DOF_Sentencia_Favorable = sub.max_fecha
            OR pro.Publicacion_DOF_Presuntos = sub.max_fecha
            OR pro.Publicacion_DOF_Desvirtuados = sub.max_fecha
        )
    AND p.rfc != 'XXXXXXXXXXXX'
    AND p.estatus IN( 1, 2, 4, 5 )
    AND ( pro.Situacion_contribuyente = 'Definitivo'
            OR pro.Situacion_contribuyente = 'Presunto' ) ORDER BY p.idproveedor ASC");
    }
    public function prov_bloqueados ($idProveedoresSAT){
        /*Consulta que permite obtener los registros de los proveedores que anteriormente fueron bloqueados por que encontraban como Presuntos o Definitivos y al momento de realizar la validacion de la situacion de este proveedor ya habia cambiado a Sentencia_favorable o Desvirtuado,
          esta consulta se utiliza para actualizar el estatus de los proveedores con el que tenia antes de que se bloqueara el proveedor.
         */
        return $this->db->query("SELECT 
                                p.idproveedor,
                                p.nombre,
                                au.anterior,
                                pro.*
                            FROM proveedores p 
                            JOIN proveedores_articulo_69 pro
                                ON p.rfc = pro.RFC 
                            JOIN (SELECT 
                                    Numero,
                                    RFC,
                                    Situacion_contribuyente,
                                    GREATEST(
                                        MAX(Publicacion_DOF_Definitivos),
                                        MAX(Publicacion_DOF_Sentencia_Favorable),
                                        MAX(Publicacion_DOF_Presuntos),
                                        MAX(Publicacion_DOF_Desvirtuados)
                                    ) AS max_fecha
                                FROM 
                                    proveedores_articulo_69
                                GROUP BY RFC) sub
                            ON pro.RFC = sub.RFC 
                            JOIN auditoria au ON au.id_parametro = p.idproveedor
                            JOIN (SELECT 
                                            *,
                                            max(fecha_creacion) as max_fecha_creacion,
                                            max(id_auditoria) as max_id_auditoria 
                                            FROM auditoria 
                                                WHERE col_afect = 'estatus'
                                                GROUP BY id_parametro) aud
                                    ON aud.id_parametro = p.idproveedor
                            WHERE 
                                    p.idproveedor IN ($idProveedoresSAT)
                                    AND
                                    (
                                        pro.Publicacion_DOF_Definitivos = sub.max_fecha
                                        OR pro.Publicacion_DOF_Sentencia_Favorable = sub.max_fecha
                                        OR pro.Publicacion_DOF_Presuntos = sub.max_fecha
                                        OR pro.Publicacion_DOF_Desvirtuados = sub.max_fecha
                                    )
                            AND p.estatus = 0 
                            AND p.observaciones 
                            LIKE '%PROVEEDOR BLOQUEADO POR EL ARTICULO 69B, SAT%' 
                            AND pro.Situacion_contribuyente IN ('Desvirtuado', 'Sentencia Favorable')
                            AND au.fecha_creacion = aud.max_fecha_creacion
                            AND au.id_auditoria = aud.max_id_auditoria
                            AND au.col_afect = 'estatus';");
    }
    //Función que permite obtener los datos de los proveedores que se encuentran en el listado 69-B dea SAT y en el de sistema de CXP.
    public function obtenerProv69b_cpp(){
        ini_set('memory_limit','-1');
        set_time_limit (0);
        return $this->db->query("SELECT 
                                        p.idproveedor,
                                        p.nombre,
                                        IF (p.estatus IN (0,3), 'BLOQUEADO','ACTIVO') AS estatusActual,
                                            p.observaciones AS observacionAct,
                                            IF (pa69.Situacion_contribuyente IN ('Desvirtuado','Sentencia favorable'), 'ACTIVO', 'BLOQUEADO') AS estatusNuevo,
                                            IF (
                                                (IF (pa69.Situacion_contribuyente IN ('Desvirtuado','Sentencia favorable'), 'ACTIVO', 'BLOQUEADO')) 
                                                != 
                                                (IF (p.estatus IN (0,3), 'BLOQUEADO', 'ACTIVO'))
                                                , 
                                                IF (pa69.Situacion_contribuyente IN ('Desvirtuado','Sentencia favorable'), 
                                                'PROVEEDOR DESBLOQUEADO, YA QUE LA RESOLUCIÓN DEL SAT DE ACUERDO A SU SITUACIÓN FISCAL FUE (DESVIRTUADO O SENTENCIA FAVORABLE), ESTO AL VALIDARLO EN LA TAREA PROGRAMADA MENSUALMENTE', 
                                                'PROVEEDOR BLOQUEADO POR EL ARTÍCULO 69B, SAT, AL VALIDARLO EN LA TAREA PROGRAMADA MENSUALMENTE'
                                                ),
                                                p.observaciones 
                                            ) AS observacionNueva,
                                        pa69.*
                                    FROM (
                                        SELECT 
                                            idproveedor,
                                            nombre,
                                            estatus,
                                            observaciones,
                                            rfc,
                                            MAX(
                                                CASE 
                                                    WHEN fecha_update IS NOT NULL
                                                    THEN fecha_update 
                                                    ELSE fecadd 
                                                END
                                            ) AS ultimo
                                        FROM proveedores
                                        WHERE ((rfc IS NOT NULL AND rfc <> '') AND rfc <> 'XXXXXXXXXXXX')
                                        GROUP BY rfc
                                    ) p
                                    INNER JOIN (
                                        SELECT 
                                            *,
                                            GREATEST(
                                                MAX(Publicacion_DOF_Definitivos),
                                                MAX(Publicacion_DOF_Sentencia_Favorable),
                                                MAX(Publicacion_DOF_Presuntos),
                                                MAX(Publicacion_DOF_Desvirtuados)
                                            ) AS fecha_maxima
                                        FROM proveedores_articulo_69
                                        GROUP BY RFC
                                    ) pa69
                                    ON p.rfc = pa69.RFC;");
    }
    //Funcion que permite obtener los datos de las solicitudes que pertenecen a los proveedores que sufrieron cambios en el mes anterior.
    public function solRelacionadasProvS($idproveedoresDes_Blo){
        return $this->db->query("SELECT 
                                        s.idsolicitud,
                                        s.proyecto,
                                        s.condominio,
                                        s.folio,
                                        s.nomdepto,
                                        s.justificacion,
                                        s.cantidad,
                                        s.descuento,
                                        s.moneda,
                                        s.metoPago,
                                        s.fechaCreacion,
                                        s.fecha_autorizacion,
                                        ref_bancaria,
                                        e.abrev,
                                        p.rfc
                                    FROM solpagos s
                                        JOIN empresas e ON s.idempresa = e.idempresa
                                        JOIN proveedores p ON s.idProveedor = p.idproveedor 
                                    WHERE s.idproveedor IN ($idproveedoresDes_Blo)");
    }
    public function truncarTablas() {
        $this->db->trans_start();
        $this->db->query('SET foreign_key_checks = 0;');
        $this->db->query('TRUNCATE TABLE complemento_69b;');
        $this->db->query('TRUNCATE TABLE proveedores_articulo_69;');
        $this->db->query('SET foreign_key_checks = 1;');
        $this->db->trans_complete();
    
        return $this->db->trans_status(); // Retorna true si fue exitoso, false si falló
    }
    

    /**
     * Inserta un nuevo registro en cualquier tabla y devuelve el ID.
     * 
     * @param string $nombreTabla Nombre de la tabla donde se insertará el registro.
     * @param array $datosInsertar Los datos a insertar si no existe el registro.
     * @return int El ID del registro si existe o se insertó, o false si ocurrió un error.
     */
    function insertarDatosXMLTablaEspecifica(string $nombreTabla, array $datosInsertar) : int {
        // Inicia una transaccion.

        $this->db->trans_begin();

        try {
            // Usa insert_batch para insertar multiples filas.
            $this->db->insert_batch($nombreTabla, $datosInsertar);
            $idRegistro = $this->db->insert_id();

            // Verificar si hubo algun error.
            if($this->db->trans_status() === FALSE){
                throw new Exception("No se pudieron insertar los registros.");
            }

            // Confirma la transaccóion.
            $this->db->trans_commit();
            return $idRegistro;

        } catch (Exception $error) {
            // Si ocurre un error se revierte la transaccion.
            $this->db->trans_rollback();
            return false;
        }
    }

    /**
     * Validar datos entrantes en cuestion de la existencia de nuestra base de datos.
     *
     * Este método validará la existencia de cierto tipo de datos en la base de datos.
     * De no existir o no encontrar una coincidencia exacta, procederá con el alta del registro;
     * de lo contrario, simplemente se extraerá cierta información dependiendo del caso.
     *
     * @author Dante Aldair Guerrero Aldana <coordinador6.desarrollo@ciudadmaderas.com>
     * @param string $nombreTabla Tabla en la cual estaríamos buscando los datos entrantes.
     * @param array $datosFiltro Array que contendrá los datos de entrada a buscar en la tabla.
     * @return array Array que proporcionará como resultado la búsqueda de los datos a filtrar.
     * 
    */
    function verificarExistenciaDatos( string $tabla, $datosCondiciones){
        if ($datosCondiciones == null) {
            return false;
        }
        // Aplicar las condiciones al WHERE de la consulta.
        $this->db->where($datosCondiciones);

        // Ejecutamos la consulta del SELECT
        $datos = $this->db->get($tabla);

        // Validamos si existe un registro en la base de datos
        if ($datos->num_rows() > 0) {

            // Guardamos los datos encontrados en la BD.
            $registroEncontrado = $datos->row();

            // Retornamos un array con la informacion extraida.
            return $registroEncontrado;
        }

        // Si no se encuentra ningun MATCH o un registro que coincida con lo condicionado se retorna un false
        return false;
    }

    function obtenerfacturasPrueba() {
        return $this->db->query("SELECT * 
                                 FROM facturas
                                 WHERE  uuid NOT IN(SELECT uuid_xml FROM xml_nodo_comprobante) AND 
                                        tipo_factura IN (1, 4) AND
                                        YEAR(feccrea) = 2025 
                                 ORDER BY idfactura DESC");
    }

    function obtenerFacturaCajaChica($idSolicitud) {
        return $this->db->query("SELECT * FROM xmls WHERE idfactura = ?", [$idSolicitud]);
    }
     /**
     * 
     * FECHA : 16-05-2025 || @author Efrain Martinez programador.analista38@ciudadmaderas.com
     * Consulta que obtiene datos de las solicitudes de devolución que se encuentran en el sistema de CXP y los regresa al servicio.
     */
    public function getDatosLoteDevolucion($fechaIni, $fechaFin, $lote){
        // Se valida si las variables tienen datos para poder aplicar los filtros correspondientes de fechas y condominio
        // Fecha : 28-MAYO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com
        if ($lote){
            $lote= "'" . implode("', '", $lote) . "'";
            $condominio = 'AND s.condominio IN ('.$lote.')';
        } else{
           $condominio = '';
        }
        if($fechaIni && $fechaFin){
            $fecha = 'AND (
                            CASE 
                                WHEN STR_TO_DATE(a.fecha_cobro, "%Y-%m-%d") IS NOT NULL 
                                    AND a.fecha_cobro > "1900-01-01" 
                                THEN a.fecha_cobro 
                                ELSE a.fechaDis 
                            END
                        ) BETWEEN "'.$fechaIni.'" AND "'.$fechaFin.'"
                        ';
        }else{
            $fecha = '';
        }



        $resultado = $this->db->query("SELECT 
                                            s.idsolicitud AS NUM_SOLICITUD,
                                            p.rfc AS RFC,
                                            p.nombre AS CLIENTE,
                                            IF(
                                                INSTR(s.condominio, '-') > 0,
                                                CONCAT(
                                                    SUBSTRING_INDEX(SUBSTRING_INDEX(s.condominio, '-', -2), '-', 1),
                                                        '-',
                                                    LPAD(TRIM(SUBSTRING_INDEX(s.condominio, '-', -1)), 3, '0')          -- última parte con ceros
                                                ),
                                                ''
                                            ) AS LOTE,
                                            IF(
                                                INSTR(s.condominio, '-') > 0,
                                                SUBSTRING_INDEX(s.condominio, '-', 1),
                                                s.condominio
                                            ) AS PROYECTO,
                                            ms.preciom AS PRECIO_POR_MT2,
                                            ms.superficie AS SUPERFICIE,
                                            s.cantidad AS PRECIO_TOTAL_SOLICITUD,
                                            IF (STR_TO_DATE(a.fecha_cobro, '%Y-%m-%d') IS NOT NULL AND a.fecha_cobro > '1900-01-01', 
                                            a.fecha_cobro, a.fechaDis ) AS FECHA_DE_RESCISIÓN,
                                            ms.penalizacion AS PENALIZACIÓN,
                                            ms.predial AS PREDIAL,
                                            ms.mantenimiento AS MANTENIMIENTO,
                                            IF(a.tipoPago IS NULL,a.formaPago, a.tipoPago) AS PAGO_CH_TEA,
                                            s.fechaCreacion AS FECHA_CREACIÓN
                                        FROM solpagos s
                                            JOIN proveedores p ON p.idproveedor = s.idproveedor
                                            JOIN autpagos a ON s.idsolicitud = a.idsolicitud
                                            LEFT JOIN motivo_sol_dev ms ON ms.idsolicitud = s.idsolicitud
                                        WHERE a.estatus = 16
                                            AND a.fecha_pago IS NOT NULL
                                            AND a.fechadis IS NOT NULL
                                            AND a.fecha_cobro IS NOT NULL
                                            AND s.idproceso IS NOT NULL
                                            AND s.idetapa NOT IN (0,30)
                                            AND s.idsolicitud NOT IN(	SELECT s.idsolicitud
                                                                        FROM solpagos s
                                                                            JOIN autpagos a ON s.idsolicitud = a.idsolicitud
                                                                            JOIN solicitudesParcialidades sp ON s.idsolicitud = sp.idsolicitud
                                                                        WHERE s.idproceso = 30
                                                                        GROUP BY a.idsolicitud, numeroParcialidades
                                                                        HAVING COUNT(a.idsolicitud) != numeroParcialidades)
                                            $condominio
                                            $fecha ")->result_array(); 
       
       return array("resultado"    =>  $resultado, "estatus" => true); 
       
    }
    /**
     * FIN FECHA : 16-05-2025 || @author Efrain Martinez programador.analista38@ciudadmaderas.com
     */
    
}