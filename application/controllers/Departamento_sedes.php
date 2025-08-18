<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Departamento_sedes extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->library('email');
        if( in_array( $this->session->userdata("inicio_sesion")['depto'], ['CONTROL INTERNO', 'CI-COMPRAS']) && in_array( $this->session->userdata("inicio_sesion")['id'], [2259])
            || in_array( $this->session->userdata("inicio_sesion")['id'], [2588, 2745]) ){ //Testing
            $this->load->model(array('Solicitudes_cxp'));
        } else {
            redirect("Login", "refresh");
        }
    }

    public function index(){
        $this->load->view("v_departamento_sedes");
    }

    public function tablaProyectoDepartamentos(){
        $json['data'] = $this->Solicitudes_cxp->tabla_proyectos_departamentos()->result_array();
        echo json_encode( $json );
    }

    public function tablaOficinaSedes(){
        $json['data'] = $this->Solicitudes_cxp->tabla_oficinas_sedes()->result_array();
        echo json_encode( $json );
    }

    public function createUpdate_proyecto_departamento(){
        $idproyecto = $this->input->post('idproyecto');
        $oficinas = $this->input->post('oficinas');
        $estatus = $this->input->post('estatus');
        $proyecto_departamento = $this->input->post('proyecto_departamento');

        $data = array();
        $respuesta = FALSE;

        if($idproyecto == null){

            //Agregar nuevo proyecto en la base de datos 
            $this->db->query("INSERT INTO proyectos_departamentos (nombre, estatus) VALUES ('".$proyecto_departamento."',".$estatus.")");
            $proyectoID = $this->db->insert_id();

            $aux = array();
            foreach ($oficinas as $key => $value) {
                $aux['idProyectos'] = $proyectoID;
                $aux['idOficina'] = $value;

                array_push($data, $aux);
            }

            $this->db->insert_batch('proyectos_departamentos_oficina_sede', $data);
            $respuesta = TRUE;

        }else{

            // Obtener los valores actuales
            $proyecto_actual = $this->db->where('idProyectos', $idproyecto)->get('proyectos_departamentos')->row_array();

            // Verificar si los nuevos valores son diferentes de los valores actuales
            if ($proyecto_actual['nombre'] != $proyecto_departamento || $proyecto_actual['estatus'] != $estatus) {
                // Si hay cambios, actualiza los datos
                $data = array(
                    'nombre' => $proyecto_departamento,
                    'estatus' => $estatus
                );
                $this->db->where('idProyectos', $idproyecto)->update('proyectos_departamentos', $data);
                $respuesta = TRUE;
            }

            if (!empty($oficinas)) {
                // Obtener los valores actuales
                $oficinasDB = array_column($this->db->where('idProyectos', $idproyecto)->get('proyectos_departamentos_oficina_sede')->result_array(), 'idOficina');

                $noExisten = array_diff($oficinasDB, $oficinas);
            
                // Eliminar las oficinas que ya no están
                if (!empty($noExisten)) {
                    $this->db->where_in('idOficina', $noExisten)
                            ->where('idProyectos', $idproyecto)
                            ->delete('proyectos_departamentos_oficina_sede');
                    $respuesta = TRUE;
                }
            
                // Agregar las nuevas oficinas que no están en la base de datos
                $nuevas_oficinas = array_diff($oficinas, $oficinasDB);
                if (!empty($nuevas_oficinas)) {
                    $datos_nuevas_oficinas = array();
                    foreach ($nuevas_oficinas as $idOficina) {
                        $datos_nuevas_oficinas[] = array(
                            'idProyectos' => $idproyecto,
                            'idOficina' => $idOficina
                        );
                    }
                    $this->db->insert_batch('proyectos_departamentos_oficina_sede', $datos_nuevas_oficinas);
                    $respuesta = TRUE;
                }
            }
        }

        echo json_encode($respuesta);

    }

    public function createUpdate_oficina_sede(){
        $oficinas_sedes = $this->input->post('oficinas_sedes');

        $idOficina = $this->input->post('idOficina');
        $nombre_oficina = $this->input->post('oficina_sede_0');
        $id_estado = $this->input->post('estados_0');
        $estatus = $this->input->post('estatus');

        $respuesta = FALSE;

        if($idOficina == null){
            if ($oficinas_sedes) {
                $datos_nuevas_oficinas_sedes = array();
                foreach ($oficinas_sedes as $oficina_sede) {
                    $datos_nuevas_oficinas_sedes[] = array(
                        'nombre' => $oficina_sede['nombre'],
                        'id_estado' => $oficina_sede['estado'] ? $oficina_sede['estado'] : NULL,
                        'estatus' => 1
                    );
                }
                
                $this->db->insert_batch('oficina_sede', $datos_nuevas_oficinas_sedes);
                $respuesta = TRUE;
            } else {
                echo "NO SE RECIBIERON DATOS.";
            }
        }else{
            // Obtener los valores actuales
            $oficina_actual = $this->db->where('idOficina', $idOficina)->get('oficina_sede')->row_array();

            // Verificar si los nuevos valores son diferentes de los valores actuales
            if ($oficina_actual['nombre'] != $nombre_oficina || $oficina_actual['id_estado'] != $id_estado || $oficina_actual['estatus'] != $estatus) {
                // Si hay cambios, actualiza los datos
                $data = array(
                    'nombre' => $nombre_oficina,
                    'id_estado' => $id_estado ? $id_estado : NULL,
                    'estatus' => $estatus
                );
                $this->db->where('idOficina', $idOficina)->update('oficina_sede', $data);
                $respuesta = TRUE;
            }
        }

        echo json_encode($respuesta);
    }
}