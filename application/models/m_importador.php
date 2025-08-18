<?php

class M_importador extends CI_Model {

    function insertExcel($data) {
        $this->db->insert('solpagos', $data);
    }

}
