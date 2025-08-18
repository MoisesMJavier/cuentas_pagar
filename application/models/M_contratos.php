<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_contratos extends CI_Model {

    function __construct(){
        parent::__construct();
    }

    function insertar_contrato( $data ){
        return $this->db->insert("contratos", $data);
    }

    function get_contratos( ){//proveedores p ON p.idproveedor = c.idproveedor
        return $this->db->query("SELECT 
            c.idcontrato,
            nombre_completo creador_por,
            c.idproveedor,
            p.rs_proveedor AS proveedor,
            c.nombre,
            c.cantidad,
            c.estatus,
            c.fecha_creacion,
            c.rfc_proveedor,
            c.p_intercambio AS porcentaje,
            IFNULL(sc.consumido, 0) consumido
            FROM contratos c
            CROSS JOIN cat_proveedor p 
                ON p.rfc_proveedor = c.rfc_proveedor
            CROSS JOIN listado_usuarios u 
                ON u.idusuario = c.idcrea
            LEFT JOIN ( SELECT sc.idcontrato, SUM(s.cantidad) AS consumido
                        FROM solpagos s
                        INNER JOIN sol_contrato sc 
                            ON s.idsolicitud = sc.idsolicitud
                        GROUP BY sc.idcontrato) sc
                ON sc.idcontrato = c.idcontrato
        ORDER BY c.nombre");
    }

    function updateContrato( $data, $idcontrato){
        return $this->db->update("contratos", $data, "idcontrato = '$idcontrato'");
    }
}