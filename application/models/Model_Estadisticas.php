<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_Estadisticas extends CI_Model {

    function __construct(){
        parent::__construct();
    }
    
    function get_name(){
        $id = 26;
        return $this->db->query("SELECT nombre, apellido_paterno FROM usuarios WHERE id_usuario = '$id'");
    }

    function get_ventas($año){
        $id = 26;
        return $this->db->query("SELECT año, mes, ventas FROM datos_prueba WHERE id_usuario = '$id' AND año = '$año'");
    }

    function get_ventas_compar(){
        $id = 26;
        return $this->db->query("SELECT v1.mes, v1.ventas AS ventas1, v2.ventas AS ventas2 FROM ventas2017 v1, ventas2018 v2 WHERE v1.mes = v2.mes AND v1.id_usuario = '$id'");
    }

    function get_year_byuser($user){
        $user = 26;
        return $this->db->query("SELECT DISTINCT año FROM datos_prueba WHERE id_usuario = '$user'");
    }
    
    function get_managers(){
        
        return $this->db->query("SELECT id_usuario, id_rol, CONCAT(nombre, ' ', apellido_paterno, ' ', apellido_materno) 
        AS nombrecompleto FROM usuarios WHERE id_rol = 3");

    }

    function get_managers_by_sub($user){
        
        return $this->db->query("SELECT id_usuario, id_rol, CONCAT(nombre, ' ', apellido_paterno, ' ', apellido_materno) AS nombrecompleto FROM usuarios WHERE id_lider = '$user' AND id_rol = 3");

    }

    function get_managers_by_asis($user){
        
        return $this->db->query("SELECT id_usuario, id_rol, CONCAT(nombre, ' ', apellido_paterno, ' ', apellido_materno) AS nombrecompleto
        FROM usuarios WHERE id_lider IN (SELECT id_lider FROM usuarios WHERE id_usuario = '$user') AND id_rol = 3");

    }

    function get_asesores($user){
        
        return $this->db->query("SELECT id_usuario as id_asesores, id_rol, CONCAT(nombre, ' ', apellido_paterno, ' ', apellido_materno) AS nombre_asesores FROM usuarios WHERE id_lider = '$user' AND id_rol = 7");

    }

    function get_asesores_asis($user){
        
        return $this->db->query("SELECT id_usuario as id_asesores, id_rol, CONCAT(nombre, ' ', apellido_paterno, ' ', apellido_materno) 
        AS nombre_asesores FROM usuarios WHERE id_lider IN
        (SELECT id_lider FROM usuarios WHERE id_usuario = '$user') AND id_rol = 7");

    }

    function get_clientes($user, $tipo ){
       $current_year = date("Y");
        return $this->db->query("SELECT CONCAT(UCASE(LEFT(MONTHNAME(fecha_creacion),1)),SUBSTRING(MONTHNAME(fecha_creacion),2)) 
        AS mes, COUNT(id_cliente) AS clientes FROM sisgphco_crm.clientes WHERE tipo = 1 AND id_asesor = '$user' AND
        (fecha_creacion BETWEEN '$current_year/01/01' AND '$current_year/12/31') 
        GROUP BY mes ORDER BY MONTH(fecha_creacion)");

    }

    function get_chart($user, $tipo, $fecha_ini, $fecha_fin){
         return $this->db->query("SELECT CONCAT(UCASE(LEFT(MONTHNAME(fecha_creacion),1)),SUBSTRING(MONTHNAME(fecha_creacion),2)) AS mes, COUNT(id_cliente) AS clientes FROM sisgphco_crm.clientes WHERE (tipo = '$tipo' AND id_asesor = '$user') AND
         fecha_creacion BETWEEN '$fecha_ini' AND '$fecha_fin'  GROUP BY mes ORDER BY MONTH(fecha_creacion)");
     }

     function get_total_gerente($user, $tipo){
        $current_year = date("Y");
        return $this->db->query("SELECT CONCAT(UCASE(LEFT(MONTHNAME(c.fecha_creacion),1)),SUBSTRING(MONTHNAME(c.fecha_creacion),2)) AS mes, COUNT(c.id_cliente) AS clientes FROM clientes c
        INNER JOIN usuarios s ON c.id_asesor = s.id_usuario
        WHERE (c.tipo = '$tipo' AND s.id_lider = '$user') AND
        c.fecha_creacion BETWEEN '$current_year/01/01' AND '$current_year/12/31'  GROUP BY mes ORDER BY MONTH(c.fecha_creacion)");
    }

    function get_chart_gerente($user, $tipo, $fecha_ini, $fecha_fin){
        return $this->db->query("SELECT CONCAT(UCASE(LEFT(MONTHNAME(fecha_creacion),1)),SUBSTRING(MONTHNAME(fecha_creacion),2)) AS mes, COUNT(id_cliente) AS clientes FROM sisgphco_crm.clientes WHERE (tipo = '$tipo' AND id_asesor = '$user') AND
        fecha_creacion BETWEEN '$fecha_ini' AND '$fecha_fin'  GROUP BY mes ORDER BY MONTH(fecha_creacion)");
    }

    function get_total_director(){
        $current_year = date("Y");
        return $this->db->query("SELECT CONCAT(UCASE(LEFT(MONTHNAME(fecha_creacion),1)),SUBSTRING(MONTHNAME(fecha_creacion),2)) AS mes, COUNT(id_cliente) AS clientes FROM sisgphco_crm.clientes 
        WHERE fecha_creacion BETWEEN '$current_year/01/01' AND '$current_year/12/31'  GROUP BY mes ORDER BY MONTH(fecha_creacion)");
    }

    function get_lider($user){
        return $this->db->query("SELECT id_lider FROM usuarios WHERE id_usuario = '$user'");
    }

    function get_reporte_asesor($user, $fecha_ini, $fecha_fin, $tipo){
        return $this->db->query("SELECT c.id_cliente AS Folio, IF(c.tipo = 0, 'Prospecto', 'Cliente') AS Tipo, YEAR(c.fecha_creacion) AS Año, 
        WEEK(c.fecha_creacion) AS SemanaCalendario, DATEDIFF(CURDATE(), c.fecha_creacion) 
        AS DiasEnProceso, CONCAT(UCASE(LEFT(MONTHNAME(c.fecha_creacion),1)),SUBSTRING(MONTHNAME(c.fecha_creacion),2)) 
        AS Mes,  CONCAT(c.nombre, ' ', c.apellido_paterno, ' ', c.apellido_materno) AS Cliente, 
        IF(oc.nombre IS NULL, 'N/D', oc.nombre  )AS LugarProspeccion, 
        IF(ot.nombre IS NULL, 'N/D', ot.nombre  )AS TerritorioVenta,
        se.nombre AS Sede,
        IF(om.nombre IS NULL, 'N/D', om.nombre  )AS MedioPublicitario 
        FROM clientes c INNER JOIN sedes se ON se.id_sede = c.id_sede 
        LEFT JOIN opcs_x_cats oc ON (c.lugar_prospeccion = oc.id_opcion AND oc.id_catalogo = 9) 
        LEFT JOIN opcs_x_cats ot ON (c.territorio_venta = ot.id_opcion AND ot.id_catalogo = 5)
        LEFT JOIN opcs_x_cats om ON (c.medio_publicitario = om.id_opcion AND om.id_catalogo = 7)
        WHERE (c.id_asesor = '$user' AND c.tipo = '$tipo') AND c.fecha_creacion 
        BETWEEN '$fecha_ini' AND '$fecha_fin'  ORDER BY MONTH(c.fecha_creacion)");
    }

    function get_reporte_gerente($user){
        $current_year = date("Y");
        return $this->db->query("SELECT c.id_cliente AS Folio, IF(c.tipo = 0, 'Prospecto', 'Cliente') AS Tipo, YEAR(c.fecha_creacion) AS Año, 
        WEEK(c.fecha_creacion) AS SemanaCalendario, DATEDIFF(CURDATE(), c.fecha_creacion) 
        AS DiasEnProceso, CONCAT(UCASE(LEFT(MONTHNAME(c.fecha_creacion),1)),SUBSTRING(MONTHNAME(c.fecha_creacion),2)) 
        AS Mes,  CONCAT(c.nombre, ' ', c.apellido_paterno, ' ', c.apellido_materno) AS Cliente, 
        se.nombre AS Sede,
                CONCAT(s.nombre, ' ', s.apellido_paterno, ' ', s.apellido_materno) AS Asesor,
                IF(oc.nombre IS NULL, 'N/D', oc.nombre  )AS LugarProspeccion,
                IF(ot.nombre IS NULL, 'N/D', ot.nombre  )AS TerritorioVenta,
                IF(om.nombre IS NULL, 'N/D', om.nombre  )AS MedioPublicitario,
                c.fecha_creacion AS Fecha
                FROM clientes c INNER JOIN usuarios s ON c.id_asesor = s.id_usuario 
                INNER JOIN sedes se ON se.id_sede = c.id_sede 
                LEFT JOIN opcs_x_cats oc ON (c.lugar_prospeccion = oc.id_opcion AND oc.id_catalogo = 9)
                LEFT JOIN opcs_x_cats ot ON (c.territorio_venta = ot.id_opcion AND ot.id_catalogo = 5)
                LEFT JOIN opcs_x_cats om ON (c.medio_publicitario = om.id_opcion AND om.id_catalogo = 7)
                WHERE (s.id_lider = '$user' ) AND (c.fecha_creacion BETWEEN '$current_year/01/01' AND '$current_year/12/31')
                ORDER BY MONTH(c.fecha_creacion)");
    }

    function get_reporte_gerente1($user, $fecha_ini, $fecha_fin, $tipo, $asesor){
        return $this->db->query("SELECT c.id_cliente AS Folio, IF(c.tipo = 0, 'Prospecto', 'Cliente') AS Tipo, YEAR(c.fecha_creacion) AS Año, 
        WEEK(c.fecha_creacion) AS SemanaCalendario, DATEDIFF(CURDATE(), c.fecha_creacion) 
        AS DiasEnProceso, CONCAT(UCASE(LEFT(MONTHNAME(c.fecha_creacion),1)),SUBSTRING(MONTHNAME(c.fecha_creacion),2)) 
        AS Mes,  CONCAT(c.nombre, ' ', c.apellido_paterno, ' ', c.apellido_materno) AS Cliente, se.nombre AS Sede,
                CONCAT(s.nombre, ' ', s.apellido_paterno, ' ', s.apellido_materno) AS Asesor,
                IF(oc.nombre IS NULL, 'N/D', oc.nombre  )AS LugarProspeccion,
                IF(ot.nombre IS NULL, 'N/D', ot.nombre  )AS TerritorioVenta,
                IF(om.nombre IS NULL, 'N/D', om.nombre  )AS MedioPublicitario,
                c.fecha_creacion AS Fecha
                FROM clientes c INNER JOIN usuarios s ON c.id_asesor = s.id_usuario 
                INNER JOIN sedes se ON se.id_sede = c.id_sede 
                LEFT JOIN opcs_x_cats oc ON (c.lugar_prospeccion = oc.id_opcion AND oc.id_catalogo = 9)
                LEFT JOIN opcs_x_cats ot ON (c.territorio_venta = ot.id_opcion AND ot.id_catalogo = 5)
                LEFT JOIN opcs_x_cats om ON (c.medio_publicitario = om.id_opcion AND om.id_catalogo = 7)
                WHERE (s.id_lider = '$user' AND c.tipo = '$tipo' AND c.id_asesor = '$asesor') AND
                (c.fecha_creacion BETWEEN '$fecha_ini' AND '$fecha_fin') ORDER BY MONTH(c.fecha_creacion)");
    }

    function get_reporte_asisgerente($user){
        $current_year = date("Y");
        return $this->db->query("SELECT c.id_cliente AS Folio, YEAR(c.fecha_creacion) AS Año, 
        WEEK(c.fecha_creacion) AS SemanaCalendario, DATEDIFF(CURDATE(), c.fecha_creacion) 
        AS DiasEnProceso, CONCAT(UCASE(LEFT(MONTHNAME(c.fecha_creacion),1)),SUBSTRING(MONTHNAME(c.fecha_creacion),2)) 
        AS Mes,  CONCAT(c.nombre, ' ', c.apellido_paterno, ' ', c.apellido_materno) AS Cliente, IF(c.tipo = 0, 'Prospecto', 'Cliente') AS Tipo, se.nombre AS Sede, 
                CONCAT(s.nombre, ' ', s.apellido_paterno, ' ', s.apellido_materno) AS Asesor, 
                IF(oc.nombre IS NULL, 'N/D', oc.nombre  )AS LugarProspeccion,
                IF(ot.nombre IS NULL, 'N/D', ot.nombre  )AS TerritorioVenta,
                IF(om.nombre IS NULL, 'N/D', om.nombre  )AS MedioPublicitario,
                c.fecha_creacion as Fecha
                FROM clientes c INNER JOIN usuarios s ON c.id_asesor = s.id_usuario 
                INNER JOIN sedes se ON se.id_sede = c.id_sede 
                LEFT JOIN opcs_x_cats oc ON (c.lugar_prospeccion = oc.id_opcion AND oc.id_catalogo = 9)
                LEFT JOIN opcs_x_cats ot ON (c.territorio_venta = ot.id_opcion AND ot.id_catalogo = 5)
                LEFT JOIN opcs_x_cats om ON (c.medio_publicitario = om.id_opcion AND om.id_catalogo = 7)
                WHERE id_lider IN (SELECT id_lider FROM usuarios WHERE id_usuario = '$user') AND (c.fecha_creacion BETWEEN '$current_year/01/01' AND '$current_year/12/31')
                ORDER BY MONTH(c.fecha_creacion)");
    }

    function get_reporte_asisgerente1($user, $fecha_ini, $fecha_fin, $tipo, $asesor){
        return $this->db->query("SELECT c.id_cliente AS Folio, IF(c.tipo = 0, 'Prospecto', 'Cliente') AS Tipo, YEAR(c.fecha_creacion) AS Año, 
        WEEK(c.fecha_creacion) AS SemanaCalendario, DATEDIFF(CURDATE(), c.fecha_creacion) 
        AS DiasEnProceso, CONCAT(UCASE(LEFT(MONTHNAME(c.fecha_creacion),1)),SUBSTRING(MONTHNAME(c.fecha_creacion),2)) 
        AS Mes,  CONCAT(c.nombre, ' ', c.apellido_paterno, ' ', c.apellido_materno) AS Cliente, se.nombre AS Sede, 
                CONCAT(s.nombre, ' ', s.apellido_paterno, ' ', s.apellido_materno) AS Asesor, 
                IF(oc.nombre IS NULL, 'N/D', oc.nombre  )AS LugarProspeccion,
                IF(ot.nombre IS NULL, 'N/D', ot.nombre  )AS TerritorioVenta,
                IF(om.nombre IS NULL, 'N/D', om.nombre  )AS MedioPublicitario,
                c.fecha_creacion as Fecha
                FROM clientes c INNER JOIN usuarios s ON c.id_asesor = s.id_usuario 
                INNER JOIN sedes se ON se.id_sede = c.id_sede 
                LEFT JOIN opcs_x_cats oc ON (c.lugar_prospeccion = oc.id_opcion AND oc.id_catalogo = 9)
                LEFT JOIN opcs_x_cats ot ON (c.territorio_venta = ot.id_opcion AND ot.id_catalogo = 5)
                LEFT JOIN opcs_x_cats om ON (c.medio_publicitario = om.id_opcion AND om.id_catalogo = 7)
                WHERE id_lider IN (SELECT id_lider FROM usuarios WHERE id_usuario = '$user') AND (c.tipo = '$tipo' AND c.id_asesor = '$asesor')
                AND (c.fecha_creacion BETWEEN '$fecha_ini' AND '$fecha_fin')
                ORDER BY MONTH(c.fecha_creacion)");
    }

    function get_total_gerente_asis($user, $tipo){
        $current_year = date("Y");
        return $this->db->query("SELECT CONCAT(UCASE(LEFT(MONTHNAME(c.fecha_creacion),1)),SUBSTRING(MONTHNAME(c.fecha_creacion),2)) AS mes, COUNT(c.id_cliente) AS clientes FROM clientes c
        INNER JOIN usuarios s ON c.id_asesor = s.id_usuario
        WHERE (c.tipo = '$tipo') AND id_lider IN
        (SELECT id_lider FROM usuarios WHERE id_usuario = '$user') AND
        c.fecha_creacion BETWEEN '$current_year/01/01' AND '$current_year/12/31'  GROUP BY mes ORDER BY MONTH(c.fecha_creacion)");
    }


    function get_reporte_dir(){
        $current_year = date("Y");
        return $this->db->query("SELECT c.id_cliente AS Folio, IF(c.tipo = 0, 'Prospecto', 'Cliente') AS Tipo, YEAR(c.fecha_creacion) AS Año, 
        WEEK(c.fecha_creacion) AS SemanaCalendario, DATEDIFF(CURDATE(), c.fecha_creacion) 
        AS DiasEnProceso, CONCAT(UCASE(LEFT(MONTHNAME(c.fecha_creacion),1)),SUBSTRING(MONTHNAME(c.fecha_creacion),2)) 
        AS Mes,  CONCAT(c.nombre, ' ', c.apellido_paterno, ' ', c.apellido_materno) AS Cliente,
                IF(oc.nombre IS NULL, 'N/D', oc.nombre  )AS LugarProspeccion,
                IF(ot.nombre IS NULL, 'N/D', ot.nombre  )AS TerritorioVenta,
                IF(om.nombre IS NULL, 'N/D', om.nombre  )AS MedioPublicitario,
                CONCAT(t1.nombre, ' ', t1.apellido_paterno, ' ', t1.apellido_materno) as Asesor,
                CONCAT(t2.nombre, ' ', t2.apellido_paterno, ' ', t2.apellido_materno) as Gerencia, se.nombre AS Sede,
                c.fecha_creacion as Fecha
                FROM usuarios t1
                INNER JOIN usuarios t2 ON t1.id_lider = t2.id_usuario
                INNER JOIN clientes c ON t1.id_usuario = c.id_asesor
                INNER JOIN sedes se ON se.id_sede = c.id_sede 
                LEFT JOIN opcs_x_cats oc ON (c.lugar_prospeccion = oc.id_opcion AND oc.id_catalogo = 9)
                LEFT JOIN opcs_x_cats ot ON (c.territorio_venta = ot.id_opcion AND ot.id_catalogo = 5)
                LEFT JOIN opcs_x_cats om ON (c.medio_publicitario = om.id_opcion AND om.id_catalogo = 7)
                WHERE t1.id_rol = 7 AND (c.fecha_creacion BETWEEN '$current_year/01/01' AND '$current_year/12/31')
                ORDER BY MONTH(c.fecha_creacion)");
    }

    function get_reporte_dir1($fecha_ini, $fecha_fin, $tipo, $asesor){
        return $this->db->query("SELECT c.id_cliente AS Folio, IF(c.tipo = 0, 'Prospecto', 'Cliente') AS Tipo, YEAR(c.fecha_creacion) AS Año, 
        WEEK(c.fecha_creacion) AS SemanaCalendario, DATEDIFF(CURDATE(), c.fecha_creacion) 
        AS DiasEnProceso, CONCAT(UCASE(LEFT(MONTHNAME(c.fecha_creacion),1)),SUBSTRING(MONTHNAME(c.fecha_creacion),2)) 
        AS Mes,  CONCAT(c.nombre, ' ', c.apellido_paterno, ' ', c.apellido_materno) AS Cliente,
                IF(oc.nombre IS NULL, 'N/D', oc.nombre  )AS LugarProspeccion,
                IF(ot.nombre IS NULL, 'N/D', ot.nombre  )AS TerritorioVenta,
                IF(om.nombre IS NULL, 'N/D', om.nombre  )AS MedioPublicitario,
                CONCAT(t1.nombre, ' ', t1.apellido_paterno, ' ', t1.apellido_materno) as Asesor,
                CONCAT(t2.nombre, ' ', t2.apellido_paterno, ' ', t2.apellido_materno) as Gerencia, se.nombre AS Sede,
                c.fecha_creacion as Fecha
                FROM usuarios t1
                INNER JOIN usuarios t2 ON t1.id_lider = t2.id_usuario
                INNER JOIN clientes c ON t1.id_usuario = c.id_asesor
                INNER JOIN sedes se ON se.id_sede = c.id_sede 
                LEFT JOIN opcs_x_cats oc ON (c.lugar_prospeccion = oc.id_opcion AND oc.id_catalogo = 9)
                LEFT JOIN opcs_x_cats ot ON (c.territorio_venta = ot.id_opcion AND ot.id_catalogo = 5)
                LEFT JOIN opcs_x_cats om ON (c.medio_publicitario = om.id_opcion AND om.id_catalogo = 7)
                WHERE t1.id_rol = 7 AND (c.fecha_creacion BETWEEN '$fecha_ini' AND '$fecha_fin') AND (c.tipo = '$tipo' AND c.id_asesor = '$asesor')
                ORDER BY MONTH(c.fecha_creacion)");
    }

    function get_total_subdir($user){
        $current_year = date("Y");
        return $this->db->query("SELECT CONCAT(UCASE(LEFT(MONTHNAME(c.fecha_creacion),1)),SUBSTRING(MONTHNAME(c.fecha_creacion),2)) AS mes, COUNT(c.id_cliente) AS clientes FROM clientes c
        INNER JOIN usuarios s ON c.id_asesor = s.id_usuario
        WHERE s.id_lider IN
        (SELECT id_usuario FROM usuarios WHERE id_lider = '$user') AND tipo = 1
        AND (c.fecha_creacion BETWEEN '$current_year/01/01' AND '$current_year/12/31')  GROUP BY mes ORDER BY MONTH(c.fecha_creacion)");
    }

    function get_total_subdir_byasis($user){
        $current_year = date("Y");
        return $this->db->query("SELECT CONCAT(UCASE(LEFT(MONTHNAME(c.fecha_creacion),1)),SUBSTRING(MONTHNAME(c.fecha_creacion),2)) AS mes, COUNT(c.id_cliente) AS clientes FROM clientes c
        INNER JOIN usuarios s ON c.id_asesor = s.id_usuario
        WHERE s.id_lider IN
        (SELECT id_usuario FROM usuarios WHERE id_lider IN (SELECT id_lider FROM sisgphco_crm.usuarios 
        WHERE id_usuario = '$user')) AND
        (c.fecha_creacion BETWEEN '$current_year/01/01' AND '$current_year/12/31') AND tipo = 1  GROUP BY mes ORDER BY MONTH(c.fecha_creacion)");
    }

    function get_reporte_subdir($user){
        $current_year = date("Y");
        return $this->db->query("SELECT c.id_cliente AS Folio, IF(c.tipo = 0, 'Prospecto', 'Cliente') AS Tipo, YEAR(c.fecha_creacion) AS Año, 
        WEEK(c.fecha_creacion) AS SemanaCalendario, DATEDIFF(CURDATE(), c.fecha_creacion) 
        AS DiasEnProceso, CONCAT(UCASE(LEFT(MONTHNAME(c.fecha_creacion),1)),SUBSTRING(MONTHNAME(c.fecha_creacion),2)) 
        AS Mes,  CONCAT(c.nombre, ' ', c.apellido_paterno, ' ', c.apellido_materno) AS Cliente,
                IF(oc.nombre IS NULL, 'N/D', oc.nombre  )AS LugarProspeccion,
                IF(ot.nombre IS NULL, 'N/D', ot.nombre  )AS TerritorioVenta,
                IF(om.nombre IS NULL, 'N/D', om.nombre  )AS MedioPublicitario,
                CONCAT(s.nombre, ' ', s.apellido_paterno, ' ', s.apellido_materno) as Asesor,
                CONCAT(t2.nombre, ' ', t2.apellido_paterno, ' ', t2.apellido_materno) as Gerencia, se.nombre AS Sede,
                c.fecha_creacion as Fecha
                FROM clientes c INNER JOIN usuarios s ON c.id_asesor = s.id_usuario
                INNER JOIN usuarios t2 ON s.id_lider = t2.id_usuario
                INNER JOIN sedes se ON se.id_sede = c.id_sede 
                LEFT JOIN opcs_x_cats oc ON (c.lugar_prospeccion = oc.id_opcion AND oc.id_catalogo = 9)
                LEFT JOIN opcs_x_cats ot ON (c.territorio_venta = ot.id_opcion AND ot.id_catalogo = 5)
                LEFT JOIN opcs_x_cats om ON (c.medio_publicitario = om.id_opcion AND om.id_catalogo = 7)
                WHERE s.id_lider IN (SELECT id_usuario FROM usuarios WHERE id_lider = '$user') 
                AND (c.fecha_creacion BETWEEN '$current_year/01/01' AND '$current_year/12/31')
                ORDER BY MONTH(c.fecha_creacion)");
    }

    function get_reporte_subdir1($user, $fecha_ini, $fecha_fin, $tipo, $asesor){
        return $this->db->query("SELECT c.id_cliente AS Folio, IF(c.tipo = 0, 'Prospecto', 'Cliente') AS Tipo, YEAR(c.fecha_creacion) AS Año, 
        WEEK(c.fecha_creacion) AS SemanaCalendario, DATEDIFF(CURDATE(), c.fecha_creacion) 
        AS DiasEnProceso, CONCAT(UCASE(LEFT(MONTHNAME(c.fecha_creacion),1)),SUBSTRING(MONTHNAME(c.fecha_creacion),2)) 
        AS Mes,  CONCAT(c.nombre, ' ', c.apellido_paterno, ' ', c.apellido_materno) AS Cliente, 
                IF(oc.nombre IS NULL, 'N/D', oc.nombre  )AS LugarProspeccion,
                IF(ot.nombre IS NULL, 'N/D', ot.nombre  )AS TerritorioVenta,
                IF(om.nombre IS NULL, 'N/D', om.nombre  )AS MedioPublicitario,
                CONCAT(s.nombre, ' ', s.apellido_paterno, ' ', s.apellido_materno) as Asesor,
                CONCAT(t2.nombre, ' ', t2.apellido_paterno, ' ', t2.apellido_materno) as Gerencia, se.nombre AS Sede,
                c.fecha_creacion as Fecha
                FROM clientes c INNER JOIN usuarios s ON c.id_asesor = s.id_usuario
                INNER JOIN usuarios t2 ON s.id_lider = t2.id_usuario
                INNER JOIN sedes se ON se.id_sede = c.id_sede 
                LEFT JOIN opcs_x_cats oc ON (c.lugar_prospeccion = oc.id_opcion AND oc.id_catalogo = 9)
                LEFT JOIN opcs_x_cats ot ON (c.territorio_venta = ot.id_opcion AND ot.id_catalogo = 5)
                LEFT JOIN opcs_x_cats om ON (c.medio_publicitario = om.id_opcion AND om.id_catalogo = 7)
                WHERE s.id_lider IN (SELECT id_usuario FROM usuarios WHERE id_lider = '$user') 
                AND (c.fecha_creacion BETWEEN '$fecha_ini' AND '$fecha_fin') AND (c.tipo = '$tipo' AND c.id_asesor = '$asesor') ORDER BY MONTH(c.fecha_creacion)");
    }
    
    function get_reporte_subdir_byasis($user){
        $current_year = date("Y");
        return $this->db->query("SELECT c.id_cliente AS Folio, IF(c.tipo = 0, 'Prospecto', 'Cliente') AS Tipo, YEAR(c.fecha_creacion) AS Año, 
        WEEK(c.fecha_creacion) AS SemanaCalendario, DATEDIFF(CURDATE(), c.fecha_creacion) 
        AS DiasEnProceso, CONCAT(UCASE(LEFT(MONTHNAME(c.fecha_creacion),1)),SUBSTRING(MONTHNAME(c.fecha_creacion),2)) 
        AS Mes,  CONCAT(c.nombre, ' ', c.apellido_paterno, ' ', c.apellido_materno) AS Cliente, 
                IF(oc.nombre IS NULL, 'N/D', oc.nombre  )AS LugarProspeccion,
                IF(ot.nombre IS NULL, 'N/D', ot.nombre  )AS TerritorioVenta,
                IF(om.nombre IS NULL, 'N/D', om.nombre  )AS MedioPublicitario,
                CONCAT(s.nombre, ' ', s.apellido_paterno, ' ', s.apellido_materno) as Asesor,
                CONCAT(t2.nombre, ' ', t2.apellido_paterno, ' ', t2.apellido_materno) as Gerencia, se.nombre AS Sede,
                c.fecha_creacion as Fecha
                FROM clientes c INNER JOIN usuarios s ON c.id_asesor = s.id_usuario
                INNER JOIN usuarios t2 ON s.id_lider = t2.id_usuario
                INNER JOIN sedes se ON se.id_sede = c.id_sede 
                LEFT JOIN opcs_x_cats oc ON (c.lugar_prospeccion = oc.id_opcion AND oc.id_catalogo = 9)
                LEFT JOIN opcs_x_cats ot ON (c.territorio_venta = ot.id_opcion AND ot.id_catalogo = 5)
                LEFT JOIN opcs_x_cats om ON (c.medio_publicitario = om.id_opcion AND om.id_catalogo = 7)
                WHERE s.id_lider IN (SELECT id_usuario FROM usuarios WHERE id_lider IN 
                (SELECT id_lider FROM sisgphco_crm.usuarios WHERE id_usuario = '$user'))
                AND (c.fecha_creacion BETWEEN '$current_year/01/01' AND '$current_year/12/31')
                ORDER BY MONTH(c.fecha_creacion)");
    }

    function get_reporte_subdir_byasis1($user, $fecha_ini, $fecha_fin, $tipo, $asesor){
        return $this->db->query("SELECT c.id_cliente AS Folio, IF(c.tipo = 0, 'Prospecto', 'Cliente') AS Tipo, YEAR(c.fecha_creacion) AS Año,
        WEEK(c.fecha_creacion) AS SemanaCalendario, DATEDIFF(CURDATE(), c.fecha_creacion) 
        AS DiasEnProceso, CONCAT(UCASE(LEFT(MONTHNAME(c.fecha_creacion),1)),SUBSTRING(MONTHNAME(c.fecha_creacion),2)) 
        AS Mes,  CONCAT(c.nombre, ' ', c.apellido_paterno, ' ', c.apellido_materno) AS Cliente,
                IF(oc.nombre IS NULL, 'N/D', oc.nombre  )AS LugarProspeccion,
                IF(ot.nombre IS NULL, 'N/D', ot.nombre  )AS TerritorioVenta,
                IF(om.nombre IS NULL, 'N/D', om.nombre  )AS MedioPublicitario,
                CONCAT(s.nombre, ' ', s.apellido_paterno, ' ', s.apellido_materno) as Asesor,
                CONCAT(t2.nombre, ' ', t2.apellido_paterno, ' ', t2.apellido_materno) as Gerencia, se.nombre AS Sede,
                c.fecha_creacion as Fecha
                FROM clientes c INNER JOIN usuarios s ON c.id_asesor = s.id_usuario
                INNER JOIN usuarios t2 ON s.id_lider = t2.id_usuario
                INNER JOIN sedes se ON se.id_sede = c.id_sede 
                LEFT JOIN opcs_x_cats oc ON (c.lugar_prospeccion = oc.id_opcion AND oc.id_catalogo = 9)
                LEFT JOIN opcs_x_cats ot ON (c.territorio_venta = ot.id_opcion AND ot.id_catalogo = 5)
                LEFT JOIN opcs_x_cats om ON (c.medio_publicitario = om.id_opcion AND om.id_catalogo = 7)
                WHERE s.id_lider IN (SELECT id_usuario FROM usuarios WHERE id_lider IN 
                (SELECT id_lider FROM sisgphco_crm.usuarios WHERE id_usuario = '$user')) 
                AND (c.fecha_creacion BETWEEN '$fecha_ini' AND '$fecha_fin') AND (c.tipo = '$tipo' AND c.id_asesor = '$asesor') ORDER BY MONTH(c.fecha_creacion)");
    }


}