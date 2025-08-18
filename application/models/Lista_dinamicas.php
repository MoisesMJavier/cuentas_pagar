<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lista_dinamicas extends CI_Model {

    function __construct(){
        parent::__construct();
    }

    //RECUPERAMOS LOS ID DE LOS RESPONSABLES DE TARJETA DE CREDITO 
    function getRestcredito( $departamento ){
        return $this->db->query("SELECT tc.idtcredito idusuario, CONCAT(nombres, ' ',apellidos, ' - ', tc.ntarjeta ) nresponsable, tc.idempresa, ep.rfc 
        FROM ( SELECT * FROM tcredito WHERE tdcestatus = 1 ) tc
        INNER JOIN (SELECT usuarios.*
                   FROM usuarios
                   JOIN departament_usuario dp 
                       ON dp.departamento = usuarios.depto AND dp.idusuario = ? AND usuarios.estatus = 1) AS us 
           ON us.idusuario = tc.idresponsable
        INNER JOIN empresas ep ON ep.idempresa = tc.idempresa", array( $departamento ) );
    }

    //RECUPERAMOS LOS ID DE LOS RESPONSABLES DE TARJETA DE CREDITO SIN DEPARTAMENTO
    function getRestcreditoSinDepto(){
        return $this->db->query("SELECT tc.idtcredito idusuario, CONCAT(nombres, ' ',apellidos, ' - ', tc.ntarjeta ) nresponsable, tc.idempresa, ep.rfc FROM tcredito tc
        INNER JOIN usuarios us ON us.idusuario = tc.idresponsable
        INNER JOIN empresas ep ON ep.idempresa = tc.idempresa
        WHERE us.estatus = 1 AND tc.tdcestatus = 1 ORDER BY nresponsable");
    }
    
    //RECUPERAMOS LOS ID DE LOS RESPONSABLES DE TARJETA DE CREDITO 
    function getUsuarios(){
        return $this->db->query("SELECT idusuario, CONCAT( nombres, ' ', apellidos ) nombre_completo FROM usuarios WHERE estatus = 1 AND rol IN ( 'DA', 'AS', 'CJ', 'CA', 'SU' ) ORDER BY nombre_completo");
    }

    function get_proveedores_lista(){
        return $this->db->query("SELECT * FROM proveedores INNER JOIN (SELECT bancos.idbanco, bancos.nombre AS nom_banco FROM bancos ) AS bancos ON proveedores.idbanco = bancos.idbanco WHERE proveedores.estatus = 1 AND proveedores.excp IN ( 1, 2 ) ORDER BY proveedores.nombre");
    }
    function get_procesos(){
        return $this->db->query("SELECT * FROM proceso WHERE estatus = 1");
    }
    function get_proceso_proyecto($idproceso){
        return $this->db->query("SELECT * 
        FROM proceso_proyecto 
        INNER JOIN catalogo_proyecto ON proceso_proyecto.idproceso = ? AND proceso_proyecto.estatus = 1 AND proceso_proyecto.idproyecto = catalogo_proyecto.idproyecto", 
        [ $idproceso ]);
    }

    function get_cat_regimenfiscal(){
        return $this->db->query("SELECT * FROM cat_regimen_fiscal ");
    }

    function get_proveedoresAll(){
        return $this->db->query("SELECT * 
        FROM ( SELECT * FROM proveedores WHERE proveedores.estatus NOT IN ( 3, 5 ) AND proveedores.nombre IS NOT NULL AND proveedores.alias IS NOT NULL  ) proveedores
        INNER JOIN (SELECT bancos.idbanco, bancos.nombre AS nom_banco FROM bancos ) AS bancos ON proveedores.idbanco = bancos.idbanco ORDER BY proveedores.nombre");
    }

    function get_proveedores_lista_total(){
        return $this->db->query("SELECT *, ifnull(nombre,'') as nombrep, IFNULL(bancos.nom_banco, 'SIN DEFINIR') AS nom_banco FROM proveedores LEFT JOIN (SELECT bancos.idbanco, bancos.nombre AS nom_banco FROM bancos ) AS bancos ON proveedores.idbanco = bancos.idbanco ORDER BY proveedores.nombre");
    }

    function get_proveedores_lista_total_est2($estatus){
        $filtro =($estatus!=null)?" and p.estatus=$estatus ":"";
        return $this->db->query("SELECT idproveedor, IFNULL(b.nombre, 'SIN DEFINIR') AS nom_banco,b.clvbanco,p.nombre AS nombrep,p.* FROM proveedores p left outer join bancos b ON b.idbanco=p.idbanco where p.estatus=2 $filtro order by p.nombre;");
    }

    //OBTENEMOS TODOS LOS CLIENTES QUE HAYAN ESTADO EN UNA DEVOLUCIONES
    function getClienteDevoluciones(){
        return $this->db->query("SELECT MIN(p.idproveedor) AS idproveedor, p.nombre AS nproveedor, p.idbanco, p.cuenta, p.tipocta 
                                FROM proveedores p
                                WHERE (p.estatus = 2 OR 
                                    p.idproveedor IN (SELECT id_parametro
                                                      FROM auditoria
                                                      WHERE id_auditoria IN (SELECT MAX(id_auditoria) AS id_auditoria 
                                                                             FROM auditoria 
                                                                             WHERE  tabla = 'proveedores' AND 
                                                                                    col_afect = 'estatus' AND 
                                                                                    anterior = 2 AND 
                                                                                    nuevo = 3 
                                                                             GROUP BY id_parametro))) AND p.nombre is not null
                                GROUP BY p.cuenta, p.nombre
                                ORDER BY p.nombre;");
    }

    //LISTADO DE PROVEEDORES POR MEDIO DE ID PARA EDITAR SOLICITUD
    function get_proveedores_lista_total_factura( $idproveedor ){
        return $this->db->query("SELECT p.idproveedor, p.excp, IFNULL( p.nombre, '' ) nombrep, IFNULL(b.nom_banco, 'SIN DEFINIR') nom_banco, p.rfc, p.tinsumo  
        FROM ( SELECT * FROM proveedores WHERE proveedores.idproveedor = '$idproveedor' ) p 
        LEFT JOIN (SELECT bancos.idbanco, bancos.nombre AS nom_banco FROM bancos ) b ON p.idbanco = b.idbanco ORDER BY p.nombre");
    }

    //BUSQUEDA DE LISTADO DE PROVEEDORES DISPONIBLES PARA EDITAR
    function get_proveedores_lista_total_editar(){
        return $this->db->query("SELECT p.idproveedor, p.excp, IFNULL( p.nombre, '' ) nombrep, IFNULL(b.nom_banco, 'SIN DEFINIR') nom_banco, p.rfc, p.tinsumo, p.cuenta  
        FROM proveedores p 
        LEFT JOIN (SELECT bancos.idbanco, bancos.nombre AS nom_banco FROM bancos ) b ON p.idbanco = b.idbanco 
        WHERE p.excp IN ( 1, 2 ) ORDER BY p.nombre ASC");
    }

    function getRolesCXP( $rol = FALSE ){

        $filtro = "";

        if( $rol != FALSE ){
            if( $rol == 'CP' )
                $filtro = "AND idrol IN ('DA', 'AS', 'CA')";
        }

        return $this->db->query("SELECT idrol, descripcion 
            FROM roles WHERE estatus IN ( 1 ) $filtro ORDER BY roles.descripcion");
    }

    function get_empresas_lista(){
        /**
         * INICIO 
         * FECHA : 27-06-2025 | @author Efrain Martinez Muñoz programador.analista38@ciudadmaderas.com
         * SE EXCLUYE LA EMPRESA GPH SERVICIOS CONDOMINALES S.C. DEL SELECT PARA ADMINISTRACION CUANDO EL USUARIO NO SEA 1835 y 2834
         */
        $excluirEmpresa = '';
        $rolAdmin = $this->session->userdata("inicio_sesion")['rol'];
        $idAdmin = $this->session->userdata("inicio_sesion")['id'];
        
        if (in_array ($rolAdmin, ['AD', 'CAD', 'GAD']) && !in_array ($idAdmin,[1835,2834])){
            $excluirEmpresa = 'WHERE idempresa <> 6';
        }
        return $this->db->query("SELECT * 
                                    FROM empresas 
                                    $excluirEmpresa
                                    ORDER BY empresas.nombre");
        /**
         * FIN
         * FECHA :27-06-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com
         */
    } 
    function get_empresas_socio(){
        return $this->db->query("SELECT idempresa,nombre,rfc,abrev FROM empresas WHERE idempresa in (1,2,5,10,16,29,31) ORDER BY empresas.nombre");
        //return $this->db->query("SELECT idproveedor AS idEmpresa, nombre, cuenta, rfc FROM proveedores where cuenta IN(030680084497701014,030680066246002018,030680021624201010,030680009823601022,030680227532902014) ORDER BY nombre");
    }
    function empresas_socio(){
        //return $this->db->query("SELECT idempresa,nombre,rfc,abrev FROM cuentaspp.empresas WHERE idempresa in (1,2,5,10,16,29,31) ORDER BY empresas.nombre");
        return $this->db->query("SELECT idproveedor, nombre, cuenta, rfc FROM proveedores where cuenta IN(030680900028792644,030680084497701014,030680066246002018,030680021624201010,030680009823601022,030680227532902014) ORDER BY nombre");
    }
    function get_invercionistas_socio(){
        return $this->db->query("SELECT idproveedor , nombre, cuenta, rfc FROM proveedores where cuenta IN(012180012251263550,012680015369520695,030680900029118243,012680004577257017,030680900028558040)");
    }

    function get_proveedor_xml($idsolicitud){
        return $this->db->query("SELECT fac.idsolicitud, prvd.idproveedor, fac.rfc_emisor, prvd.nombre AS nombreproveedor, bn.nombre AS nombrebanco , IFNULL(prvd.cuenta,'SIN DEFINIR') cuenta
        FROM ( 
            SELECT idsolicitud, rfc_emisor FROM facturas WHERE idsolicitud = ? AND tipo_factura IN ( 1, 3 ) 
            UNION
            SELECT facturas.idsolicitud, facturas.rfc_emisor FROM facturas 
            JOIN ( SELECT * FROM sol_factura WHERE idsolicitud = ? ) sol_factura ON sol_factura.idfactura = facturas.idfactura
            WHERE tipo_factura IN ( 1, 3 )
        ) fac
        CROSS JOIN ( SELECT idproveedor, rfc, nombre, idbanco, cuenta FROM proveedores ) prvd ON fac.rfc_emisor = prvd.rfc
        CROSS JOIN ( SELECT idbanco, nombre FROM bancos ) bn ON bn.idbanco = prvd.idbanco", [ $idsolicitud, $idsolicitud ]);
    }

    function get_proyectos_lista(){
        return $this->db->query("SELECT catalogo_proyecto.concepto proyecto FROM catalogo_proyecto ORDER BY catalogo_proyecto.concepto");
    }

    function get_Cuentas_empresas($dato){
        return $this->db->query('SELECT bancos.nombre as nombanco, cuentas.idcuenta, cuentas.nombre, cuentas.nodecta 
            FROM cuentas 
            INNER JOIN bancos 
                ON bancos.idbanco = cuentas.idbanco 
            WHERE idempresa ='.$dato.'');
    }

    function get_lista_departamento(){
        return $this->db->query("SELECT iddepartamentos, departamento FROM departamentos WHERE estatus = 1 ORDER BY departamento");
    }

    function get_pago($dato){
         $query1 =  $this->db->query('SELECT idsolicitud FROM autpagos WHERE idpago ='.$dato.'');

         return $this->db->query('SELECT proveedores.nombre, autpagos.cantidad, empresas.nombre AS nomempresa FROM solpagos INNER JOIN proveedores ON proveedores.idproveedor = solpagos.idProveedor  INNER JOIN empresas ON empresas.idempresa = solpagos.idEmpresa INNER JOIN autpagos ON autpagos.idsolicitud = solpagos.idsolicitud  WHERE solpagos.idsolicitud ='.$query1->row()->idsolicitud.'');
    }

    function get_Cuentas_empresas2($valor){
        return $this->db->query('SELECT bancos.nombre as nombanco, cuentas.idcuenta, cuentas.nombre, cuentas.nodecta FROM cuentas INNER JOIN bancos ON bancos.idbanco = cuentas.idbanco WHERE idempresa ='.$valor.'');  
    }
 
    function lista_proyectos_depto(){
        return $this->db->query("SELECT concepto FROM catalogo_proyecto WHERE estatus = 1 ORDER BY catalogo_proyecto.concepto");
    }

    function lista_proyectos_departamento(){
        return $this->db->query("SELECT 
            idProyectos, 
            nombre concepto 
        FROM proyectos_departamentos 
        WHERE estatus = 1 
        ORDER BY proyectos_departamentos.idProyectos");
    }
    function lista_oficinas_sedes($idProyecto){
        return $this->db->query("SELECT 
            os.idOficina, 
            os.nombre oficina 
        FROM oficina_sede os
        INNER JOIN proyectos_departamentos_oficina_sede  pdos ON os.idOficina =  pdos.idOficina
        WHERE pdos.idProyectos = ".($idProyecto ? $idProyecto : 0)." 
        AND estatus = 1 
        ORDER BY os.nombre");
    }
    function lista_proyectos_homoclaves(){
        return $this->db->query("SELECT * FROM homoclaves ORDER BY homoclaves.concepto");
    }
    function lista_homoclaves(){
        return $this->db->query("SELECT idclave, concepto FROM homoclaves ORDER BY homoclaves.concepto");
    }

    //LISTADO DE PROVEEDORES
    function get_proveedores_libres(){
        //CONSULTA HASTA EL 29 DE MAYO 2020
        //return $this->db->query("SELECT * FROM proveedores INNER JOIN (SELECT bancos.idbanco, bancos.nombre AS nom_banco FROM bancos ) AS bancos ON proveedores.idbanco = bancos.idbanco WHERE proveedores.idproveedor NOT IN ( SELECT solpagos.idProveedor FROM solpagos LEFT JOIN (SELECT *, MIN(facturas.feccrea) FROM facturas WHERE facturas.tipo_factura IN ( 1, 3 ) GROUP BY facturas.idsolicitud) AS facturas ON facturas.idsolicitud = solpagos.idsolicitud WHERE solpagos.nomdepto = '".$this->session->userdata("inicio_sesion")['depto']."' AND solpagos.idetapa = 10 AND ( solpagos.caja_chica != 1 OR solpagos.caja_chica IS NULL ) AND ( solpagos.servicio IS NULL OR solpagos.servicio = 0 ) AND (( solpagos.tendrafac = 1 AND facturas.tipo_factura IS NULL AND TIMESTAMPDIFF(DAY, solpagos.fechaCreacion, NOW()) >= 365 )) ) AND proveedores.estatus = 1 AND proveedores.excp IN ( 1, 2 ) ORDER BY proveedores.nombre");
        //VEASE LA VISTA provedores_bloqueados
        return $this->db->query("SELECT *
                                 FROM proveedores 
                                 INNER JOIN (SELECT bancos.idbanco, bancos.nombre AS nom_banco FROM bancos ) AS bancos 
                                    ON proveedores.idbanco = bancos.idbanco
                                 WHERE proveedores.idproveedor NOT IN ( SELECT idproveedor FROM provedores_bloqueados WHERE nomdepto = ? ) AND proveedores.estatus = 1 AND proveedores.excp IN ( 1, 2 )
                                 ORDER BY proveedores.nombre ASC;", array ( $this->session->userdata("inicio_sesion")['depto'] ) );
    }

    //LISTADO DE PROVEEDORES PARA TARJETAS DE CREDITO
    function get_proveedores_tdc(){
        return $this->db->query("SELECT proveedores.idproveedor, proveedores.nombre nproveedor FROM proveedores INNER JOIN (SELECT bancos.idbanco, bancos.nombre AS nom_banco FROM bancos ) AS bancos ON proveedores.idbanco = bancos.idbanco WHERE proveedores.estatus = 1 AND proveedores.tdc IN ( 1 ) ORDER BY proveedores.nombre");
    }

    //COLABORADORES DE CIUDAD MADERAS
    function get_colaboradores(){
        return $this->db->query("SELECT *, tinsumo, DATE_FORMAT(fecadd,'%d/%m/%Y') AS fecha FROM proveedores LEFT JOIN ( SELECT bancos.idbanco, bancos.nombre AS nomba FROM bancos ) AS bancos ON proveedores.idbanco=bancos.idbanco LEFT JOIN estados ON proveedores.sucursal = estados.id_estado WHERE ( proveedores.idproveedor IN ( SELECT solpagos.idProveedor FROM solpagos WHERE solpagos.nomdepto IN ( 'FINIQUITO', 'NOMINAS', 'FINIQUITO POR PARCIALIDAD', 'FINIQUITO POR RENUNCIA', 'PRESTAMO', 'PRESTAMO POR SUSTITUCION PATRONAL', 'PRESTAMO POR ADEUDO', 'BONO' ) ) AND proveedores.estatus IN ( 1, 2 ) ) OR proveedores.estatus IN ( 5 ) ORDER BY proveedores.nombre ASC");
    }

    function get_proveedores_nomina(){
        return $this->db->query("SELECT idproveedor, excp, nombre, nom_banco, rfc FROM proveedores LEFT JOIN (SELECT bancos.idbanco, bancos.nombre AS nom_banco FROM bancos ) AS bancos ON proveedores.idbanco = bancos.idbanco WHERE proveedores.idproveedor NOT IN ( SELECT solpagos.idProveedor FROM solpagos LEFT JOIN (SELECT *, MIN(facturas.feccrea) FROM facturas WHERE facturas.tipo_factura IN ( 1, 3 ) GROUP BY facturas.idsolicitud) AS facturas ON facturas.idsolicitud = solpagos.idsolicitud WHERE solpagos.nomdepto = '".$this->session->userdata("inicio_sesion")['depto']."' AND solpagos.idetapa = 10 AND ( solpagos.caja_chica != 1 OR solpagos.caja_chica IS NULL ) AND ( solpagos.servicio IS NULL OR solpagos.servicio = 0 ) AND (( solpagos.tendrafac = 1 AND facturas.tipo_factura IS NULL AND TIMESTAMPDIFF(DAY, solpagos.fechaCreacion, NOW()) >= 365 )) ) AND proveedores.estatus = 1 AND proveedores.excp IN ( 2 ) AND proveedores.tipo_prov IN ( 1 ) ORDER BY proveedores.nombre");
    }

    function get_proveedores_bloqueados() {
        //CONSULTA PARA SACAR TODOS LOS PROVEEDORES BLOQUEADOS HASTA EL 10022020
        //return $this->db->query("SELECT * FROM proveedores WHERE proveedores.idproveedor IN ( SELECT solpagos.idProveedor FROM solpagos LEFT JOIN (SELECT *, MIN(facturas.feccrea) FROM facturas WHERE facturas.tipo_factura IN ( 1, 3 ) GROUP BY facturas.idsolicitud) AS facturas ON facturas.idsolicitud = solpagos.idsolicitud WHERE solpagos.nomdepto = '".$this->session->userdata("inicio_sesion")['depto']."' AND solpagos.idetapa = 10 AND ( solpagos.caja_chica != 1 OR solpagos.caja_chica IS NULL ) AND ( solpagos.servicio IS NULL OR solpagos.servicio = 0 ) AND (( solpagos.tendrafac = 1 AND facturas.tipo_factura IS NULL AND TIMESTAMPDIFF(DAY, solpagos.fechaCreacion, NOW()) >= 365 )) ) ORDER BY proveedores.nombre");
        //NUEVA CONSULTA PARA SACAR EL LISTADO DE PROVEEDORES BLOQUEADOS.
        //VEASE LA VISTA provedores_bloqueados
        return $this->db->query("SELECT nombre FROM proveedores NATURAL JOIN provedores_bloqueados WHERE nomdepto = ? ORDER BY proveedores.nombre",  array( $this->session->userdata("inicio_sesion")['depto'] ) );
    }

    function get_Directoresarea(){
        return $this->db->query("SELECT us.idusuario, us.nombres, us.apellidos, ld.adeptos FROM usuarios us
        INNER JOIN listado_dadeptos ld ON ld.idusuario = us.idusuario
         WHERE us.rol IN ('DA', 'SU') AND us.estatus = 1 ORDER BY us.nombres, us.apellidos");
    }

    function get_DirectoresAdmonM(){
        return $this->db->query("SELECT idusuario, nombres, apellidos FROM usuarios WHERE usuarios.rol IN ('AD') AND usuarios.estatus = 1 ORDER BY usuarios.nombres, usuarios.apellidos");
    }

    function getResponsables( $idusuario ){
        /*
        switch( $this->session->userdata("inicio_sesion")['rol'] ){
            case 'DA':
            case 'SU':
                return $this->db->query("SELECT usuarios.idusuario, apellidos, nombres, correo, depto, da FROM usuarios JOIN ( SELECT idusuario FROM cajas_ch WHERE estatus = 1 ) cch ON usuarios.idusuario = cch.idusuario WHERE usuarios.estatus = 1 AND ( usuarios.depto = '".$this->session->userdata("inicio_sesion")['depto']."' OR usuarios.da = '".$this->session->userdata("inicio_sesion")['da']."' ) ORDER BY usuarios.nombres, usuarios.apellidos");
                break;
            case 'CE':
                return $this->db->query("SELECT usuarios.idusuario, apellidos, nombres, correo, depto, da FROM usuarios JOIN ( SELECT idusuario FROM cajas_ch WHERE estatus = 1 ) cch ON usuarios.idusuario = cch.idusuario WHERE ( usuarios.depto = 'DEVOLUCIONES' OR usuarios.da = '".$this->session->userdata("inicio_sesion")['da']."' ) AND usuarios.estatus = 1 ORDER BY FIELD(usuarios.rol, 'SU', 'DA', 'CJ'), usuarios.nombres, usuarios.apellidos");
                break;
            case 'CP':
                return $this->db->query("SELECT usuarios.idusuario, apellidos, nombres, correo, depto, da FROM usuarios JOIN ( SELECT idusuario FROM cajas_ch WHERE estatus = 1 ) cch ON usuarios.idusuario = cch.idusuario WHERE ( usuarios.depto = 'ADMINISTRACION' OR usuarios.da = '".$this->session->userdata("inicio_sesion")['da']."' ) AND usuarios.estatus = 1 ORDER BY FIELD(usuarios.rol, 'SU', 'DA', 'CJ'), usuarios.nombres, usuarios.apellidos;"); // consulta sin resultados apropósito
                break;
            case 'AS':
            case 'CJ':
            case 'CA':
            case 'FP':
                return $this->db->query("SELECT usuarios.idusuario, apellidos, nombres, correo, depto, da FROM usuarios JOIN ( SELECT idusuario FROM cajas_ch WHERE estatus = 1 ) cch ON usuarios.idusuario = cch.idusuario WHERE ( usuarios.depto = ? OR usuarios.da = '".$this->session->userdata("inicio_sesion")['da']."' ) AND usuarios.estatus = 1 ORDER BY FIELD(usuarios.rol, 'SU', 'DA', 'CJ'), usuarios.nombres, usuarios.apellidos", [ $this->session->userdata("inicio_sesion")['depto'] ]);
                break; 
            default:
                return $this->db->query("SELECT usuarios.idusuario, apellidos, nombres, correo, depto, da FROM usuarios JOIN ( SELECT idusuario FROM cajas_ch WHERE estatus = 1 ) cch ON usuarios.idusuario = cch.idusuario WHERE usuarios.estatus = 1 ORDER BY FIELD(usuarios.rol, 'SU', 'DA', 'CJ'), usuarios.nombres, usuarios.apellidos");
                break;
        }
        */
        return $this->db->query("SELECT usuarios.idusuario, 
                                        apellidos, 
                                        nombres, 
                                        correo, 
                                        depto, 
                                        da 
                                FROM (SELECT usuarios.*
                                    FROM usuarios
                                    JOIN departament_usuario dp ON dp.departamento = usuarios.depto AND dp.idusuario = ? AND usuarios.estatus = 1) usuarios
                                JOIN ( SELECT idusuario FROM cajas_ch WHERE estatus = 1 ) cch ON usuarios.idusuario = cch.idusuario 
                                ORDER BY usuarios.nombres, usuarios.apellidos", [ $idusuario ]);

    }

    //CODIGO DE MARCOS I PARA LAS LISTA DE AUTOCOMPLETAR
    function get_proveedores_lista_autocompletable(){
        return $this->db->query("SELECT nombre as label, idproveedor as value, estatus, tipo_prov as tipo FROM proveedores WHERE tipo_prov LIKE '2' AND proveedores.estatus NOT IN ( 0, 3 ) ORDER BY nombre");
    }

    //CONSULTA PARA SACAR LOS PROVEEDORES PARA IMPUESTOS
    function get_proveedores_lista_impuesto(){
        return $this->db->query("SELECT nombre as label, idproveedor as value, estatus, tipo_prov as tipo FROM proveedores WHERE tipo_prov LIKE '3' AND proveedores.estatus NOT IN ( 0, 3 ) ORDER BY nombre");
    }

    function proveedores_rrhh(){
        if($this->session->userdata("inicio_sesion")['rol'] ){
            return $this->db->query("SELECT * FROM proveedores WHERE idby LIKE '".$this->session->userdata("inicio_sesion")['id']."' ORDER BY nombre;")->result_array();
        }else{
            return null;
        }
    }

    function get_proveedoresActivos(){
       // 
        //return $this->db->query("SELECT * FROM proveedores INNER JOIN (SELECT bancos.idbanco, bancos.nombre AS nom_banco FROM bancos ) AS bancos ON proveedores.idbanco = bancos.idbanco WHERE proveedores.estatus = 1 ORDER BY proveedores.nombre");
        return $this->db->query("SELECT rs_proveedor, rfc_proveedor, porcentaje FROM cat_proveedor ORDER BY rs_proveedor");
    }

    function listado_contratos_activos(){
        return $this->db->query("SELECT
                contratos.idcontrato, 
                contratos.rfc_proveedor as rfcProveedor,
                null idProveedor,
                contratos.nombre ncontrato,
                proveedores.rs_proveedor nproveedor, 
                contratos.cantidad, 
                IFNULL(scontrato.consumido, 0) consumido
            FROM contratos
            /*INNER JOIN ( SELECT proveedores.idproveedor, proveedores.nombre FROM proveedores ) proveedores ON proveedores.idproveedor = contratos.idproveedor*/
            INNER JOIN ( SELECT rfc_proveedor, rs_proveedor FROM cat_proveedor ) proveedores ON proveedores.rfc_proveedor = contratos.rfc_proveedor
            LEFT JOIN ( SELECT idcontrato, SUM(solpagos.cantidad) consumido FROM solpagos
            INNER JOIN sol_contrato ON sol_contrato.idsolicitud = solpagos.idsolicitud
            WHERE solpagos.idetapa NOT IN (0, 30)
            GROUP BY idcontrato ) scontrato ON scontrato.idcontrato = contratos.idcontrato WHERE contratos.estatus = 1 ORDER BY nproveedor, ncontrato");
    }

    public function lista_insumos(){
        return $this->db->query("SELECT * FROM insumos WHERE estatus = 1 ORDER BY insumo");
    }

    public function get_condominios(){
        return $this->db->query("SELECT * FROM condominios order by condominios.nombre ASC");
    }

    public function get_etapas(){
        return $this->db->query("SELECT * FROM etapas_construccion order by etapas_construccion.nombre ASC");
    }

    public function listado_proveedores_empresas(){
        return $this->db->query("SELECT idproveedor, nombre, alias, idbanco, tipocta, cuenta FROM proveedores where tipo_prov = 2 AND estatus IN ( 1 ) ORDER BY nombre");
    }
    public function Lista_procesos(){
        return $this->db->query("SELECT * FROM proceso where estatus = 1  ORDER BY nombre");
    }
    // public function Lista_proyectos($proceso){
    //     return $this->db->query("SELECT * FROM proceso where estatus = 1 AND  idproceso = '$proceso' ORDER BY nombre");
    // }
    function Lista_proyectos($proyecto){
        // return $this->db->query("SELECT * FROM proceso_proyecto INNER JOIN catalogo_proyecto ON proceso_proyecto.idproyecto = catalogo_proyecto.idproyecto WHERE concepto = '$proyecto'");
        return $this->db->query("SELECT * FROM proceso_proyecto INNER JOIN catalogo_proyecto ON proceso_proyecto.idproyecto = catalogo_proyecto.idproyecto WHERE concepto = '$proyecto'");
    }

    public function get_procesos_by_rol($idRol, $idUsuario){
        //return $this->db->query("SELECT * FROM proceso WHERE grupo = (SELECT grupo FROM rol_grupo WHERE rol = '$idrol') AND estatus = 1 ORDER BY nombre");
        /**
         * FECHA : 27-06-2025 | @author Efrain Martinez Muñoz programador.analista38@ciudadmaderas.com
         * Se agrega excluyen los procesos con id (1,2,3,4,7). 
         */
        return $this->db->query("SELECT *
                                FROM proceso
                                WHERE idproceso IN (SELECT pe.idproceso
                                                    FROM proceso_etapa AS pe
                                                    WHERE '$idRol' != 'DA' AND FIND_IN_SET( '$idRol', pe.idrol ) AND pe.orden = 1 AND (idnopermitido IS NULL OR NOT FIND_IN_SET('$idUsuario', idnopermitido))
                                                    UNION
                                                    SELECT idproceso FROM proceso_etapa WHERE FIND_IN_SET('$idUsuario', idpermitido) AND orden = 1 )
                                    AND proceso.estatus = 1
                                    AND idproceso NOT IN (1,2,3,4,5,7,8,9,10,11,13,14,21,22,25) 
                                ORDER BY idproceso;");
    }

    public function get_TipoServicioPartidas(){
        return $this->db->query("SELECT * FROM tipo_servicio_partidas WHERE estatus = 1 ORDER BY nombre ASC");
    }

    public function lista_zonas(){
        return $this->db->query("SELECT *, nombre zona FROM zonas where estatus = 1  ORDER BY nombre");
    }

    function lista_estados($idZonas = NULL){
        $where = $idZonas ? 'WHERE e.idZonas = ' . $idZonas : '';
    
        if ($where) {
            $where .= ' AND estatus = 1';
        } else {
            $where = 'WHERE estatus = 1';
        }
    
        return $this->db->query("SELECT 
                    e.id_estado, 
                    e.estado 
                FROM estados e
                $where
                ORDER BY e.estado");
    }

    public function get_UsuariosActivos(){
        return $this->db->query("SELECT CONCAT( nombres, ' ', apellidos ) as nombre_completo, idusuario FROM usuarios WHERE estatus = 1 ORDER BY nombre_completo ASC");
    }

    function lista_oficinas(){
        return $this->db->query("SELECT 
                os.idOficina, 
                os.nombre oficina 
            FROM oficina_sede os
            WHERE os.estatus = 1 
            ORDER BY os.nombre");
    }
    function obtenerProveedoresCliente($nombreCliente = null, $tipoCuenta = null, $idBanco = null){
        //$proveedor = str_replace(' ', '%', trim($nombreProveedor));
        $where = ($nombreCliente == null || $nombreCliente == '' || !$nombreCliente ) ? '' : " AND p.nombre LIKE '%$nombreCliente%' AND p.tipocta = $tipoCuenta AND p.idbanco = $idBanco ";
        return $this->db->query("SELECT 
            p.idproveedor,
            p.nombre AS nombreProveedor,
            p.alias,
            p.tipocta,
            p.cuenta,
            p.estatus,
            CASE
                WHEN p.tipocta = 1 THEN 'Cuenta en Banco del Bajio' 
                WHEN p.tipocta = 3 THEN 'Tarjeta de débito / crédito' 
                WHEN p.tipocta = 40 THEN 'CLABE' 
            end tipoCuenta,
            p.idbanco,
            b.nombre banco,
            CASE
                WHEN p.estatus = 1 THEN 'ACTIVO PROVEEDOR'
                WHEN p.estatus = 2 THEN 'ACTIVO CLIENTE'
                WHEN p.estatus = 3 THEN 'BLOQUEADO'
                WHEN p.estatus = 4 THEN 'PROVEEDOR FACTURA'
                WHEN p.estatus = 5 THEN 'COLABORADOR'
            END estatus
            FROM proveedores p
            LEFT JOIN bancos b on p.idbanco = b.idbanco
            WHERE (p.estatus = 2 OR 
                p.idproveedor IN (SELECT id_parametro
                                  FROM auditoria
                                  WHERE id_auditoria IN (SELECT MAX(id_auditoria) AS id_auditoria 
                                                         FROM auditoria 
                                                         WHERE  tabla = 'proveedores' AND 
                                                                col_afect = 'estatus' AND 
                                                                anterior = 2 AND 
                                                                nuevo = 3 
                                                         GROUP BY id_parametro))) AND
               p.nombre is not null $where
            ORDER BY p.nombre;");
    }
}