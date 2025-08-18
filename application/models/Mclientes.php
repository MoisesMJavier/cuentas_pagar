<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mclientes extends CI_Model {

    function __construct(){
        parent::__construct();
    }

    function getClientesSinAsignar(){
        return $this->db->query("SELECT * FROM tbl_info_neodata_cliente");
    }
    function sAssignment( $id ){
        return $this->db->query("SELECT f.Atraso, h.id_usuario, h.asesor, h.id_asignacion, f.id_cobranza, g.id_contrato, h.Adeudo, cliente, g.nombre, g.apellido_paterno, g.apellido_materno, contrato, referencia, vivienda, nivelmoratorio, fechaproximopago
        FROM [cobranza].[dbo].tbl_info_neodata_cliente as g,
        (SELECT SUM(saldo_pendiente_base) as Adeudo, tbl_NPC.id_cobranza, tbl_Asi.id_asignacion, tbl_Asi.id_usuario, (usua.nombre +' '+ usua.apellido_paterno +' '+ usua.apellido_materno) as asesor FROM [cobranza].[dbo].[tbl_info_neodata_plan_cliente] tbl_NPC inner join tbl_asignacion tbl_Asi on tbl_NPC.id_cobranza = tbl_Asi.id_cobranza inner join tbl_usuario usua on tbl_Asi.id_usuario = usua.id_usuario where fechaplan < GETDATE() GROUP BY tbl_NPC.id_cobranza, tbl_Asi.id_asignacion, tbl_Asi.id_usuario, usua.nombre, usua.apellido_paterno, usua.apellido_materno) as h ,
        (SELECT COUNT(tbl_NPC.id_contrato) as Atraso, tbl_NPC.id_cobranza FROM tbl_info_neodata_plan_cliente tbl_NPC inner join tbl_asignacion tbl_Asi on tbl_NPC.id_cobranza = tbl_Asi.id_cobranza where fechaplan < GETDATE() GROUP BY tbl_NPC.id_cobranza) as f
        where g.fechaproximopago < GETDATE() AND g.id_cobranza = h.id_cobranza AND h.id_cobranza = f.id_cobranza AND h.id_usuario = '$id'
        order by id_contrato");
    }
    function getNombre($idcobranza){
        return $this->db->query("SELECT 
            vivienda AS nombre
            from tbl_info_neodata_cliente 
            where id_cobranza=?",$idcobranza);
    }
    //LISTADO DE ASESORES CON SUS CLIENTES
    function getAsignacion(){
        return $this->db->query("SELECT tbl_usuario.id_usuario, tbl_usuario.nombre +' '+ tbl_usuario.apellido_paterno +' '+ tbl_usuario.apellido_materno as nombre, s.Asignacion, d.Adeudo from tbl_usuario, (SELECT COUNT(id_asignacion) as Asignacion, id_usuario FROM tbl_asignacion GROUP BY id_usuario) as s, (SELECT SUM(saldo_pendiente_base) as Adeudo, id_usuario FROM [cobranza].[dbo].[tbl_info_neodata_plan_cliente] inner join tbl_asignacion on tbl_asignacion.id_cobranza = [tbl_info_neodata_plan_cliente].id_cobranza where tbl_info_neodata_plan_cliente.fechaplan < GETDATE() GROUP BY id_usuario) as d where tbl_usuario.id_usuario = s.id_usuario and tbl_usuario.id_usuario = d.id_usuario");
    }


  	function getTablaClientes($filtros,$data){
        $where=$filtros;
        $whereCondominios='';

        if( isset($data->condominios) && count($data->condominios)>0)
        {
            $whereCondominios=' where idproyecto in(';
            for ($i=0; $i <count($data->condominios) ; $i++) { 
                if($i!=0)$whereCondominios.=',';
                $whereCondominios.=$data->condominios[$i]->idCondominio;
            }
            $whereCondominios.=')';
        }

  		 return $this->db->query("SELECT
           DISTINCT inf.id_cobranza,tinpc.fechaplan,
            CONCAT( inf.nombre,' ',ISNULL(inf.apellido_paterno,''),' ',ISNULL(inf.apellido_materno,'') ) AS clienteNombre,
           inf.referencia,
		       SUBSTRING(inf.vivienda, 0, CHARINDEX('-', inf.vivienda)) proyecto,
           inf.vivienda,
		   ISNULL(inf.StatusTerreno, 'N/T') AS statusTerreno,
           IIF(RIGHT(p.id_cobranza,3) != 'GPH' , ISNULL(p.mesesVencidos,0), ISNULL(p.nivelMoroso,0) ) nivelMoroso,
           isNULL(p.adeudo,0) adeudo,
           ISNULL(p.moratorios,0)+ISNULL(p.adeudo,0) AS adeudoM,
           usr.id_usuario AS idAsesor,
           usr.id_usuario,
           IIF(usr. nombre IS NULL,null,CONCAT( usr.nombre,' ',ISNULL(usr.apellido_paterno,''),' ',ISNULL(usr.apellido_materno,'') ) ) asesorNombre,
           fecha_alta,a.deudaIncial as deudaInicial,a.deudaFinal
           FROM  (
			select id_cobranza,
				nombre,
				apellido_paterno,
				apellido_materno,
				referencia,idproyecto,vivienda,StatusTerreno,baseneodata 
				from tbl_info_neodata_cliente ".$whereCondominios."
			) inf
           left join (select DAY(fechaplan) as fechaplan,id_cobranza from tbl_info_neodata_plan_cliente where no_pago=1 group by id_cobranza,DAY(fechaplan)) tinpc on tinpc.id_cobranza=inf.id_cobranza
		   LEFT JOIN tblClientesMorosos p on inf.id_cobranza=p.id_cobranza
           LEFT JOIN (select * from tbl_asignacion ".(isset($data->director)?"":"where fecha_fin is null").") a on a.id_cobranza = inf.id_cobranza
           LEFT JOIN tbl_usuario usr on usr.id_usuario = a.id_usuario 
           left join tblSucursal suc on suc.idSucursal=usr.id_sucursal
           left join tbl_info_neodata_proyecto inp on (inp.IdProyecto=inf.idproyecto and inp.BasedeDatos=inf.baseneodata) 
           ".$where."
           order by inf.id_cobranza");
    }

    function Clientes_estatusM(){
        return $this->db->query("SELECT DISTINCT t.StatusTerreno FROM tbl_info_neodata_cliente t ORDER BY t.StatusTerreno;");
    }

    function getTablaClientesByAsesor(){
  		 return $this->db->query("SELECT A.nombre, A.apellido_paterno, A.apellido_materno, SUBSTRING( A.vivienda, 1, 3) AS proyecto, A.referencia, A.vivienda,
        C.id_usuario, B.mesesVencidos, IIF(B.mesesVencidos>3,IIF(B.mesesVencidos<6,2,3),1) morosidad, B.moratorios, B.adeudo, A.id_cobranza,
        CASE
            WHEN D.id_usuario IS NULL THEN '---'
            ELSE (D.nombre + ' ' + D.apellido_paterno + ' ' + ISNULL(D.apellido_materno, ''))
        END AS asignaci
        FROM [cobranza].[dbo].[tbl_info_neodata_cliente] A
        INNER JOIN tblClientesMorosos B on B.id_cobranza = A.id_cobranza
        INNER JOIN tbl_asignacion C on C.id_cobranza = A.id_cobranza AND C.fecha_fin is null
        INNER JOIN tbl_usuario D on D.id_usuario = C.id_usuario
        Where A.id_cobranza = A.id_cobranza AND A.baseneodata = 'GPH'
        ORDER BY A.id_cobranza");
   }

   function getSedesActivas(){
     return $this->db->query("SELECT id_sede, nombre, abreviacion FROM sedes WHERE estatus = 1");
   }

   function getAsesoresbySede($idSucursal){
     return $this->db->query("SELECT id_usuario,
        (nombre + ' ' + apellido_paterno + ' ' + ISNULL(apellido_materno, '')) AS nombrecompleto
        FROM tbl_usuario WHERE activo = 1 AND id_rol in (3,2) AND id_sucursal = ? ORDER BY nombre, apellido_paterno, apellido_materno",array($idSucursal)
    );
   }

   function getAsesoresbySede_lotes($idSucursal){
    return $this->db->query("SELECT id_usuario,
       (nombre + ' ' + apellido_paterno + ' ' + ISNULL(apellido_materno, '')) AS nombrecompleto
       FROM tbl_usuario WHERE activo = 1 AND id_rol in (15,16,17) AND id_sucursal = ? ORDER BY nombre, apellido_paterno, apellido_materno",array($idSucursal)
   );
  }

   function updateAsignacion( $id, $data ){
       return $this->db->update("tbl_asignacion", $data, "id_asignacion = '$id'" );
   }

   function insertAsignacion( $data ){
       return $this->db->insert("tbl_asignacion", $data );
   }
   //Comprueba si el cliente tiene un asesor activo
   function GetAsesoresPrevio($cadena){
       return $this->db->query("select id_cobranza,id_usuario from tbl_asignacion where fecha_fin is null and id_cobranza in (".$cadena.");");
   }

   function EliminarAsignaciones($data){
       $this->db->trans_begin();
       foreach ($data as $key => $value)
       {
           $this->db->where(array( 'id_usuario'=>$value["id_usuario"],'id_cobranza'=>$value["id_cobranza"],'fecha_fin'=>null ) );
           $this->db->update("tbl_asignacion", $value["newData"] );
       }
       $this->db->trans_complete();
       if ($this->db->trans_status() === FALSE)
           return false;
       else
           return true;
   }

   function insertAsignaciones($data){
       $this->db->trans_begin();
            $where=[];
            foreach ($data as $key => $value)
               $where[]=$value['id_cobranza'];
            $this->db->where_in('id_cobranza',$where);
            $this->db->where(' fecha_fin IS NULL ',null,false);
            $this->db->update('tbl_asignacion',array('fecha_fin'=>date('Y-m-d\TH:i:s') ));
           $this->db->insert_batch("tbl_asignacion", $data );
       $this->db->trans_complete();
       if (!$this->db->trans_status())
        {
            $this->db->trans_rollback();
            return false;
        }
        else
        {
            $this->db->trans_commit();
            return $this->db->last_query();
        }
   }
   
   function getDetalleCliente( $idcobranza ){
       $campo_ref=substr($idcobranza,-3)=="GPH"?"referencia_man":"referencia";
       //CONSULTA ACTUAL PARA EL DETALLE DE LA INFORMACION DE LA PERSONA
       $info = $this->db->query("SELECT [id_contrato],A.[id_cobranza],[idproyecto],A.[idvivienda],[idCliente],[cliente],A.[nombre],A.[apellido_paterno],A.[apellido_materno],
       [calle],A.[sexo],CONVERT(DATE, [fechaNacimiento], 103) [fechaNacimiento],
        A.[cod_post],A.[colonia],[mpio_deleg],[localidad],[contrato],A.[referencia],[vivienda],[baseneodata],[idneodata],A.[correo],[nota],A.[estatus],[StatusTerreno],
        A.[Telefono],[Celular],[mesesVencidos],IIF([mesesVencidos]>0, IIF([mesesVencidos] >=6, 3, IIF([mesesVencidos] >=3, 2,1  ) ), 0) as nivelmoratorio,
        [adeudo],[moratorios],F.id_dpersonal id_dpersonalViv, G.numero viviendaViv, E.referencia_man referencia_man, F.fecha_modifica fecha_modificaViv, F.fechaRegistro fechaRegistroViv,
        F.nombre nombreViv, F.apellido_paterno apellido_paternoViv, F.apellido_materno apellido_maternoViv,
        F.f_nacimiento f_nacimientoViv, F.sexo sexoViv, F.direccion direccionViv, F.numInt numIntViv,
        F.numExt numExtViv, F.colonia coloniaViv, F.municipio municipioViv,
        F.estado estadoViv, F.cod_post cod_postViv, usr.id_usuario AS idAsesor,
        G.idVivienda idViviendaViv,
        asig.fecha_alta,
        CASE WHEN usr. nombre IS NULL THEN NULL 
            ELSE CONCAT( usr.nombre,' ',ISNULL(usr.apellido_paterno,''),' ',ISNULL(usr.apellido_materno,'') ) 
        END nasesor
        
                FROM [cobranza].[dbo].[tbl_info_neodata_cliente] A 
                    LEFT JOIN [tblClientesMorosos] B 
                        on B.id_cobranza = A.id_cobranza 
                    LEFT JOIN [Persona_Vivienda] E on E.$campo_ref = A.referencia AND E.estatus = 1 
                    LEFT JOIN tbl_dPersonales F ON F.id_dpersonal = E.id_dpersonal 
                    LEFT JOIN Viviendas G ON G.idVivienda = E.idVivienda 
                    LEFT JOIN tbl_asignacion asig on asig.id_cobranza = A.id_cobranza AND asig.fecha_fin IS NULL 
                    LEFT JOIN tbl_usuario usr on usr.id_usuario = asig.id_usuario 
                    WHERE A.id_cobranza = '$idcobranza'")->result_array()[0];
        //log_message("debug",$this->db->last_query());
       $infoPostventaConct['ContactPostVenta'] = [];
       $idPersona="";
       if (!empty($info['id_dpersonalViv'])) {
         // echo $idcobranza;
            $idPersona = $info['id_dpersonalViv'];
            $id = $info['id_cobranza'];
            // print_r($infoPostventaConct);
            //return $info = (array_merge($info,$infoPostventaConct));
       } else 
            $id=$idcobranza;
       
        $infoPostventa = $this->db->query("SELECT id_medio, relacion, info, fecha_registro, fecha_modifica, justificacion FROM tbl_contactos WHERE id_dpersonal = '$idPersona' or id_Cobranza = '$id' order by id_medio;")->result_array();
        //$infoCobranza = $this->db->query("SELECT id_medio, relacion, info, fecha_registro, fecha_actualiza, justificacion FROM tbl_contactos WHERE id_Cobranza = '$id'")->result_array();
        $infoPostventaConct['ContactPostVenta'] = $infoPostventa;

       return $info = (array_merge($info,$infoPostventaConct));
   }
   
   function getCuentasBancarias( $id ){
       return $this->db->query("SELECT [tbl1].[IdProyecto],[tbl1].[Proyecto],[tbl1].[Nombre],[tbl1].[Observac],[tbl2].[IdCuentaBancaria],[tbl3].[Cuenta],[tbl3].[Banco],[tbl3].[CuentaCLABE] FROM [cobranza].[dbo].[tbl_info_neodata_proyecto] as [tbl1] INNER JOIN [tbl_info_neodata_proyectos_cuentas] as [tbl2] on [tbl2].[IdProyecto] = [tbl1].[IdProyecto] INNER JOIN [tbl_info_neodata_cuentas_bancarias] as [tbl3] on [tbl3].[IdCuentaBancaria] = [tbl2].[IdCuentaBancaria] WHERE[tbl1].[IdProyecto] = '$id' ");
   }

   function getBitacora(){
       return $this->db->query("SELECT f.Atraso, h.id_usuario, h.asesor, h.id_asignacion, f.id_cobranza, g.id_contrato, h.Adeudo, cliente, g.nombre, g.apellido_paterno, g.apellido_materno, contrato, referencia, vivienda, nivelmoratorio, fechaproximopago
       FROM [cobranza].[dbo].tbl_info_neodata_cliente as g,
       (SELECT SUM(saldo_pendiente_base) as Adeudo, tbl_NPC.id_cobranza, tbl_Asi.id_asignacion, tbl_Asi.id_usuario, (usua.nombre +' '+ usua.apellido_paterno +' '+ usua.apellido_materno) as asesor FROM [cobranza].[dbo].[tbl_info_neodata_plan_cliente] tbl_NPC inner join tbl_asignacion tbl_Asi on tbl_NPC.id_cobranza = tbl_Asi.id_cobranza inner join tbl_usuario usua on tbl_Asi.id_usuario = usua.id_usuario where fechaplan < GETDATE() GROUP BY tbl_NPC.id_cobranza, tbl_Asi.id_asignacion, tbl_Asi.id_usuario, usua.nombre, usua.apellido_paterno, usua.apellido_materno) as h ,
       (SELECT COUNT(tbl_NPC.id_contrato) as Atraso, tbl_NPC.id_cobranza FROM tbl_info_neodata_plan_cliente tbl_NPC inner join tbl_asignacion tbl_Asi on tbl_NPC.id_cobranza = tbl_Asi.id_cobranza where fechaplan < GETDATE() GROUP BY tbl_NPC.id_cobranza) as f
       where g.fechaproximopago < GETDATE() AND g.id_cobranza = h.id_cobranza AND h.id_cobranza = f.id_cobranza order by id_contrato");
   }

   //Get Seguimiento
   function getSeguimiento($idcobranza){
     return $this->db->query("SELECT b.id_bitacora, (u.nombre + ' ' + u.apellido_paterno + ' ' + ISNULL(u.apellido_materno, '')) AS asesor, b.id_cobranza, opc.nombre, b.fecha_operacion, b.respuesta, b.convenio, b.observaciones, b.fecha_registro, b.status FROM tbl_bitacora_s b INNER JOIN tbl_usuario u ON u.id_usuario = b.id_usuario INNER JOIN opcs_x_cats opc ON (opc.id_opcion = b.id_medio AND opc.id_catalogo = 16) WHERE b.id_cobranza = '$idcobranza' AND b.status = 1");
   }

   //Get Actividades Programadas
   function getProgramadas($idcobranza){
     return $this->db->query("SELECT b.id_bitacora, (u.nombre + ' ' + u.apellido_paterno + ' ' + ISNULL(u.apellido_materno, '')) AS asesor, b.id_cobranza, opc.nombre, b.fecha_operacion, b.observaciones, b.status FROM tbl_bitacora_s b INNER JOIN tbl_usuario u ON u.id_usuario = b.id_usuario INNER JOIN opcs_x_cats opc ON (opc.id_opcion = b.id_medio AND opc.id_catalogo = 16) WHERE b.id_cobranza = '$idcobranza' AND b.status = 2;");
   }

   //Get Medios de contacto adicional
   function getMedios($idcobranza){
     return $this->db->query("SELECT c.id_contacto, opc.nombre, c.id_cobranza, c.relacion, c.info, c.status FROM tbl_contactos c INNER JOIN opcs_x_cats opc ON (opc.id_opcion = c.id_medio AND opc.id_catalogo = 15) WHERE c.id_cobranza = '$idcobranza'");
   }

   /*********************************************INICIO FUNCIONES MODULO DETALLE CLIENTES***********************************************/

   //Get Medios de contacto
   function get_All_MediosContact(){
     return $this->db->query("SELECT *  FROM [cobranza].[dbo].[tbl_medios_contacto]");
   }

   function insert_followUp($data){
       return $this->db->insert( "tbl_Agenda", $data )?$this->db->insert_id():-1;
   }

   function insertar_Agenda($data){
       return $this->db->insert( "tbl_bitacora", $data );
   }

   function Sigle_SeguimientoCliente($data){
     return $this->db->query("SELECT * FROM [cobranza].[dbo].[tbl_Agenda] inner join tbl_usuario on tbl_usuario.id_usuario = tbl_Agenda.id_usuario WHERE id_cobranza = '$data->idCobranza' order by tbl_Agenda.id_agenda DESC")->result_array();
   }

   function updateAgenda($data,$id){
        return $this->db->update("tbl_Agenda", $data, "id_agenda = '$id'" );
    }
    function getComentariosByFecha($data){
        $bd=(isset($data->lotes) && $data->lotes==false) ? "cl.baseneodata='GPH'":"cl.baseneodata!='GPH'";
        $sql="SELECT cb.*, CONCAT(u.nombre,' ',u.apellido_paterno,' ',u.apellido_materno) AS usuario, 
        CONCAT(cl.nombre,' ',cl.apellido_paterno,' ',cl.apellido_materno) AS dpersonal, 
        cl.vivienda AS vivienda, cl.id_cobranza
        FROM tblClienteBitacora cb 
            JOIN tbl_usuario u ON u.id_usuario=cb.id_usuario 
            JOIN tbl_info_neodata_cliente cl on  (cb.id_cobranza=cl.id_cobranza and $bd)
        where cb.fecha_creacion between ? AND ?";
        return $this->db->query($sql,[$data->desde.'T00:00:00',$data->hasta.'T23:59:59']);
    }
   /**********************************************FIN FUNCIONES MODULO DETALLE CLIENTES*************************************************/
    function get_clientesByFechaCorte($data,$filtros){
        $where='WHERE ttv.fechacorte=?';
        if(count($filtros)>0)
        {
            $where.=' and ';
            foreach ($filtros as $key => $value) 
                $where.=$key.'=? AND ';
            $where=substr($where,0,strlen($where)-4);
        }
        array_unshift($filtros,$data->diaCorte);
        return $this->db->query("SELECT viv.idVivienda,viv.RefLote as referencia,
                viv.numero,viv.RefLote,RefMantenimiento AS referencia_man, 
                tt.nombretarjeta as nombreCliente,
                inf.id_cobranza,
                pry.descripcion,
                p.id_dpersonal,
                ttv.monto,ttv.monto as monto_ori,ttv.fechacorte,ttv.leyenda,tt.* 
            FROM Viviendas viv
                left JOIN
                    ( SELECT pv.idVivienda, pv.id_dpersonal, pv.referencia, pv.referencia_man,pv.estatus
                        FROM Persona_Vivienda pv
                        WHERE pv.estatus = 1
                    ) pv ON pv.idVivienda = viv.idVivienda
                JOIN Condominio_Proyecto cndP
                ON viv.idProyCond=cndP.idProyCond AND cndP.estatus=1
            JOIN Condominio_Proyecto cp on viv.idProyCond=cp.idProyCond
            JOIN tblProyecto pry on cp.id_proy=pry.id_proy join tbl_proyectosxusuario pu on pry.id_proy=pu.idproyecto 
            left JOIN tbl_dPersonales p ON p.id_dpersonal= pv.id_dpersonal
            JOIN tbl_info_neodata_cliente inf on iif(viv.RefLote='',null,viv.RefLote)=inf.referencia and baseneodata=pry.descripcion
            join tbl_tarjetavivienda ttv on (ttv.idVivienda=viv.idVivienda and ttv.estatus=1)
            join tbl_tarjetas tt on tt.idtarjeta=ttv.idtarjeta
            JOIN (select distinct id_cobranza from tbl_info_neodata_plan_cliente) pln 
                on pln.id_cobranza=inf.id_cobranza $where ORDER BY viv.numero",$filtros);
    }

    function get_proxpagoM($data){
        return $this->db->query("EXEC [dbo].[sp_PaymentPlan_proxpago] @idCobranza = '$data->idcobranza'");
    }

    function get_correosByid_dpersonal($id_personal,$medio=2){
        $whereIn="";
        if(count($id_personal)>0)
        {
            $whereIn="(";
            foreach ($id_personal as $key => $value) {
                $whereIn.="?,";
            }
            $whereIn=substr($whereIn,0,strlen($whereIn)-1).') ';
        }
        return $this->db->query("SELECT pry.nproyecto,cnd.Nombre,vvn.numero,prn.nombre,prn.id_dpersonal,IIF(id_medio=1,CONCAT('52',info),info) as info FROM (select id_dpersonal,CONCAT(nombre,' ',apellido_paterno,' ',ISNULL(apellido_paterno,'')) nombre 
        FROM tbl_dPersonales WHERE id_dpersonal in $whereIn
        )prn 
        JOIN Persona_Vivienda pv on prn.id_dpersonal=pv.id_dpersonal
        JOIN Viviendas vvn on pv.idVivienda=vvn.idVivienda
        JOIN Condominio_Proyecto cp on vvn.idProyCond=cp.idProyCond
        JOIN tblProyecto pry on cp.id_proy=pry.id_proy
        JOIN Condominios cnd on cp.idCondominio=cnd.idCondominio
        LEFT JOIN tbl_contactos cnt on prn.id_dpersonal=cnt.id_dpersonal and id_medio=$medio
        order by numero,prn.nombre",$id_personal);
        
    }

    function NotificacionHistM($data){
        return $this->db->insert("tblNotificaciones_hist",$data);
    }

    function NotificacionHistDetM($data){
        return $this->db->insert_batch("tblNotificaciones_hist_det",$data);
    }
    function rep_histnotificacionesM($data){
        if(isset($data->fechaini))
            $where="WHERE tnhd.fecha_creacion BETWEEN '".$data->fechaini."T00:00:00' AND '".$data->fechafin."T23:59:59.999'";
        return $this->db->query("SELECT tnhd.tiponotificacion,tnhd.destino,tnhd.lote,tnhd.fecha_creacion,tnhd.mensaje,
        concat(tp.nombre,' ',tp.apellido_paterno,' ',tp.apellido_materno) AS cliente,concat(u.nombre,' ',u.apellido_paterno,' ',u.apellido_materno) AS usuario
        FROM tblNotificaciones_hist_det tnhd 
        JOIN tblNotificaciones_hist tnh ON tnh.idNotificacion_hist=tnhd.idNotificacion_hist 
        JOIN tbl_usuario u ON tnh.idusuario=u.id_usuario 
        left JOIN tblNotificaciones tn ON tn.idNotificacion=tnh.idNotificacion
        JOIN tbl_dPersonales tp ON tp.id_dpersonal=tnhd.idcliente $where;");
    }

    function get_clienteXrefM($ref){
        /*return $this->db->query("SELECT top 1 concat('52',tc.info) AS telefono FROM Persona_Vivienda pv JOIN tbl_dPersonales dp ON dp.id_dpersonal=pv.id_dpersonal 
        JOIN Viviendas v ON v.idVivienda=pv.idVivienda JOIN tbl_contactos tc on dp.id_dpersonal=tc.id_dpersonal and id_medio=1
        WHERE v.RefLote='$ref' AND LEN(tc.info)=10");*/
        return $this->db->query("SELECT dp.id_dpersonal,v.numero,v.RefLote,concat('52',tc.info) AS telefono FROM Persona_Vivienda pv JOIN tbl_dPersonales dp ON dp.id_dpersonal=pv.id_dpersonal 
        JOIN Viviendas v ON v.idVivienda=pv.idVivienda JOIN tbl_contactos tc on dp.id_dpersonal=tc.id_dpersonal and id_medio=1
        WHERE v.RefLote in($ref) AND LEN(tc.info)=10");
    }
}
