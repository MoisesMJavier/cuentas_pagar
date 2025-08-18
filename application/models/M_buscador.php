<?php

class M_buscador extends CI_Model {

    function get_proveedor_nombre( $nombre ){
        $resultado = $this->db->query("SELECT proveedores.idproveedor FROM proveedores WHERE proveedores.nombre = '$nombre'");

        $resultado = $resultado->num_rows() > 0 ? $resultado->row()->idproveedor : FALSE;

        return $resultado;
    }

    function get_empresa_abrev( $abrev ){
        $resultado = $this->db->query("SELECT empresas.idempresa FROM empresas WHERE empresas.abrev = '$abrev'");

        $resultado = $resultado->num_rows() > 0 ? $resultado->row()->idempresa : FALSE;

        return $resultado;
    }
}
