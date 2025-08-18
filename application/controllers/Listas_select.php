<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Listas_select extends CI_Controller {

    public function __construct(){
        parent::__construct();
        if( !$this->session->userdata("inicio_sesion") )
            redirect("Login", "refresh");
        else
            $this->load->model("Lista_dinamicas");
    }

    public function lista_provedores(){
        //if(in_array( $depto, array( 'CPP' ) )){
            echo json_encode(  $this->Lista_dinamicas->get_proveedores_lista()->result_array() );
        //} 
    }
    
    public function cat_regimenfiscal(){
        echo json_encode(  $this->Lista_dinamicas->get_cat_regimenfiscal()->result_array() );
    }

    public function pconstruccion(){
        echo json_encode( $this->Lista_dinamicas->get_proveedoresActivos()->result_array() );
    }

    //OBTENEMOS TODOS LOS PROVEEDORES DISPONIBLES
    /*
    OPCION PARA SACAR EL LISTADO DE LOS PROVEEDORES PARA HACER EL CAMBIO DE ALIAS;
    NO SON CONSIDERADOS LOS COLABORADORES ESTATUS 5.
    NO SON CONSIDERADOS LAS CAJAS CHICAS ESTATUS 4.
    NO SON CONSIDERADOS LOS ELIMINADOS ESTATUS 3.
    */
    public function Allproveedores(){
        echo json_encode(  $this->Lista_dinamicas->get_proveedoresAll()->result_array() );
    }

    public function Allproveedores_sinbanco(){
        echo json_encode(  $this->Lista_dinamicas->get_proveedores_lista_total_est2( $this->input->get("estatus") )->result_array() );
    }

    //LISTADO DE OPCIONES PARA LOS TRASPASOS
    public function listado_devoluciones_traspasos(){

        $this->load->model('Provedores_model');

        echo json_encode( 
            array(
                "bancos" => $this->Provedores_model->getbancos( NULL )->result_array(),
                "lista_proveedores_devoluciones" => $this->Lista_dinamicas->getClienteDevoluciones()->result_array(),
                "lista_proyectos_depto" => $this->Lista_dinamicas->lista_proyectos_depto()->result_array(),
                "listado_proveedores_empresas" => $this->Lista_dinamicas->listado_proveedores_empresas()->result_array(),
                "empresas" => $this->Lista_dinamicas->get_empresas_lista()->result_array(),
                "lista_procesos" => $this->Lista_dinamicas->get_procesos_by_rol($this->session->userdata("inicio_sesion")['rol'], $this->session->userdata("inicio_sesion")['id'])->result_array(),
                "clientes" => $this->Lista_dinamicas->obtenerProveedoresCliente()->result_array()
            )
        );
    }

    public function proceso_proyectos(){
        $idproceso = $this->input->post('opcion');

        $this->load->model('M_Devolucion_Traspaso');
          
        $respuesta["lista_procesos_proyectos"] = $this->Lista_dinamicas->get_proceso_proyecto($idproceso)->result_array();
        $respuesta["info_proceso"] = $this->M_Devolucion_Traspaso->get_info_proceso($idproceso)->row();
    
        echo json_encode( $respuesta);

    }

    public function lista_empresas(){
        //if(in_array( $depto, array( 'CPP' ) )){
            echo json_encode(  $this->Lista_dinamicas->get_empresas_lista()->result_array() );
        //}   
    }

    public function lista_proveedor_xml(){
        echo json_encode(  $this->Lista_dinamicas->get_proveedor_xml($this->input->post('idsolicitud'))->result_array() );
    }

    public function lista_proyectos(){
        //if(in_array( $depto, array( 'CPP' ) )){
            echo json_encode(  $this->Lista_dinamicas->get_proyectos_lista()->result_array() );
        //}   
    }

    public function listado_pyempro(){
        echo json_encode(  
            array(
                "proveedores" => $this->Lista_dinamicas->get_proveedores_lista()->result_array(),
                "empresas" => $this->Lista_dinamicas->get_empresas_lista()->result_array(),
                "proyecto" => $this->Lista_dinamicas->get_proyectos_lista()->result_array()
            )
        );
    }

    public function lista_user_page(){
        $resultado = array( "respuesta" => FALSE );

        if( TRUE ){
            $resultado["departamento"] = $this->Lista_dinamicas->get_lista_departamento()->result_array();
            $resultado["empresa"] = $this->Lista_dinamicas->get_empresas_lista()->result_array();
            $resultado["roles"] = $this->Lista_dinamicas->getRolesCXP( $this->session->userdata("inicio_sesion")['rol'] )->result_array();
            $resultado["respuesta"] = TRUE;
        }
        echo json_encode( $resultado );      
    }

    public function lista_directores_admon(){
        echo json_encode(  $this->Lista_dinamicas->get_DirectoresAdmonM()->result_array() );
    }


        public function lista_cuentas($valor){
        //if(in_array( $depto, array( 'CPP' ) )){
            echo json_encode(  $this->Lista_dinamicas->get_Cuentas_empresas($valor)->result_array() );
        //}   
    }

    public function datos_pago($pago){
        //if(in_array( $depto, array( 'CPP' ) )){
            echo json_encode($this->Lista_dinamicas->get_pago($pago)->result_array());
        //}   
    }

    public function lista_cuentas2($valor){
        //if(in_array( $depto, array( 'CPP' ) )){
            echo json_encode(  $this->Lista_dinamicas->get_Cuentas_empresas2($valor)->result_array() );
        //}   
    }

    public function lista_proveedores_libres(){
        //if(in_array( $depto, array( 'CPP' ) )){
            $depto = '';
            switch($this->session->userdata("inicio_sesion")['rol']){
                case 'CT':
                    $depto = 'CONTABILIDAD';
                break;
                case 'CE':
                    $depto = 'DEVOLUCIONES';
                break;
                case 'CP':
                    $depto = 'ADMINISTRACION';
                break;
                default:
                    $depto = $this->session->userdata("inicio_sesion")['depto'];
                    /**
                     * POR REQUISICION SE MOSTRARAN LOS CONTRATOS POR PROVEEDOR A TODOS LOS USUARIOS CON OPCION DE SOLICITUDES DE PAGO.
                    */
                break;
            }
            $data["contratos"] = $this->Lista_dinamicas->listado_contratos_activos()->result_array();
            $data["listado_disponibles"] = $this->Lista_dinamicas->get_proveedores_libres()->result_array();
            $data["listado_bloqueados"] = $this->Lista_dinamicas->get_proveedores_bloqueados()->result_array();
            $data["listado_responsable"] = $this->Lista_dinamicas->getResponsables( $this->session->userdata("inicio_sesion")['id'] )->result_array();
            $data["lista_proyectos_depto"] = $this->Lista_dinamicas->lista_proyectos_depto()->result_array(); // Catalogo viejo
            $data["lista_proyectos_homoclaves"] = $this->Lista_dinamicas->lista_proyectos_homoclaves()->result_array();
            $data["listado_condominios"] = $this->Lista_dinamicas->get_condominios()->result_array();
            $data["listado_etapas"] = $this->Lista_dinamicas->get_etapas()->result_array();
            $data["empresas"] = $this->Lista_dinamicas->get_empresas_lista()->result_array();
            $data["rtcredito"] = $this->Lista_dinamicas->getRestcredito( $this->session->userdata("inicio_sesion")['id'] )->result_array();
            $data["listado_insumos"] = $this->Lista_dinamicas->lista_insumos()->result_array();
            $data["lista_proyectos_departamento"] = $this->Lista_dinamicas->lista_proyectos_departamento()->result_array(); // Catalogo nuevo
            $data["lista_zonas"] = $this->Lista_dinamicas->lista_zonas()->result_array();
            $data["lista_tipo_servicio_partida"] = $this->Lista_dinamicas->get_TipoServicioPartidas()->result_array();
            $data["departamento"] = $depto;
            
            echo json_encode( $data );
        //}   
    }

    //INFORMACION PARA EL LLENADO DE FORMULARIO DE TARJETAS
    public function listas_td_creditos(){

        $data["responsables"] = $this->Lista_dinamicas->getUsuarios()->result_array();
        $data["empresas"] = $this->Lista_dinamicas->get_empresas_lista()->result_array();
        $data["proveedores"] = $this->Lista_dinamicas->get_proveedores_tdc()->result_array();
        $data["usuarios"] = $this->Lista_dinamicas->get_UsuariosActivos()->result_array();

        echo json_encode( $data );
    }

    public function lista_recursos_humanos(){

        $this->load->model('Provedores_model');

        $data["listado_disponibles"] = $this->Lista_dinamicas->get_colaboradores()->result_array();
        $data['bancos'] = $this->Provedores_model->getbancos( NULL )->result_array();
        $data["lista_proyectos_depto"] = $this->Lista_dinamicas->lista_proyectos_depto()->result_array();
        $data["empresas"] = $this->Lista_dinamicas->get_empresas_lista()->result_array();
        $data["departamento"] = $this->session->userdata("inicio_sesion")['depto'];
        $data["lista_proyectos_departamento"] = $this->Lista_dinamicas->lista_proyectos_departamento()->result_array();
        $data["lista_servicio_partida"] = $this->Lista_dinamicas->get_TipoServicioPartidas()->result_array();

        echo json_encode( $data );
    }

    public function lista_proveedores_nomina(){
        $data["provedores_disponibles"] = $this->Lista_dinamicas->get_proveedores_nomina()->result_array();
        $data["empresas"] = $this->Lista_dinamicas->get_empresas_lista()->result_array();

        echo json_encode( $data );
    }

    public function lista_proveedores_edicion(){
        $this->load->model('Provedores_model');

        $respuesta['bancos'] = $this->Provedores_model->getbancos( NULL )->result_array();
        $respuesta['sucursales'] = $this->Provedores_model->getsucursal( NULL )->result_array();
        $respuesta['usuarios'] = $this->Provedores_model->getuserscc( NULL )->result_array();
        $respuesta['proveedores'] = $this->Provedores_model->getprovscc( NULL )->result_array();

        echo json_encode($respuesta);
    }

    public function lista_provedores_autocomplemento(){
        //if(in_array( $depto, array( 'CPP' ) )){
            echo json_encode(  $this->Lista_dinamicas->get_proveedores_lista_autocompletable()->result_array() );
        //}
    }

    public function lista_departamento(){
        echo json_encode(  $this->Lista_dinamicas->get_lista_departamento()->result_array() );
    }

    public function listado_proveedores_impuesto(){
        $data["cuentas_internas"] = $this->Lista_dinamicas->get_proveedores_lista_impuesto()->result_array();
        $data["empresas"] = $this->Lista_dinamicas->get_empresas_lista()->result_array();
        $data["proyectosDepartamentos"] = $this->Lista_dinamicas->lista_proyectos_departamento()->result_array(); /** Se obtienen los listados de proyectos y servicios partida | FECHA: 05-Junio-2024 | @author Lalo Silva <programador.analista38@ciudadmaderas.com> **/
        $data["lista_servicio_partida"] = $this->Lista_dinamicas->get_TipoServicioPartidas()->result_array();     /** FIN Se obtienen los listados de proyectos y servicios partida | FECHA: 05-Junio-2024 | @author Lalo Silva <programador.analista38@ciudadmaderas.com> **/
        echo json_encode(  $data );
    }

    public function NodeduciblesTDC(){

        $data = array();

        $data["lista_proyectos_depto"] = $this->Lista_dinamicas->lista_proyectos_depto()->result_array();
        $data["rtcredito"] = $this->Lista_dinamicas->getRestcreditoSinDepto()->result_array();
            
        echo json_encode( $data );
    
    }

    public function Lista_oficinas_sedes(){
        $data["lista_oficinas_sedes"] = $this->Lista_dinamicas->lista_oficinas_sedes($this->input->post('idProyecto'))->result_array();
        echo json_encode( $data );
    }

    public function Lista_TipoServicioPartidas(){
        echo json_encode(  $this->Lista_dinamicas->get_TipoServicioPartidas()->result_array() );
    }

    public function Lista_estados(){
        $data["lista_estados"] = $this->Lista_dinamicas->lista_estados($this->input->post('idZonas'))->result_array();
        echo json_encode( $data );
    }

    public function obtenerOficinas() {
        $idProyecto = $this->input->post('idProyecto');
        
        $oficinasProyecto = $this->Lista_dinamicas->lista_oficinas_sedes($idProyecto)->result_array();
        $todasLasOficinas = $this->Lista_dinamicas->lista_oficinas()->result_array();

        $data['oficinasProyecto'] = $oficinasProyecto;
        $data['todasLasOficinas'] = $todasLasOficinas;

        echo json_encode($data);
    }
}