<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_cajach extends CI_Model {

    function __construct(){
        parent::__construct();
    }
    //Tablas al crear caja chica
    function insert_caja($data){
        return $this->db->insert("cajas_ch", $data);
    }

    function insert_cajaempresa($data){
        return $this->db->insert("caja_empresa", $data);
    }
    function insert_cajadepto($data){
        return $this->db->insert("caja_depto", $data);
    }
    function insert_logCaja($idcaja, $tipo, $ant, $new, $campo, $observacion){
        
        return $this->db->insert("log_cajasch", [   "idcaja"=>$idcaja,
                                                    "tipo"  =>$tipo,
                                                    "anterior"=>$ant,
                                                    "nuevo"=> $new,
                                                    "campo"=> $campo,
                                                    "fmovimiento"=> date("Y-m-d H:i:s"),
                                                    "idusuario"=>  $this->session->userdata("inicio_sesion")['id'],
                                                    "observacion"=>  $observacion

                                                ]);
    }
    function insert_documento($data){
        $this->db->insert("cajach_documentos", $data);
        return $this->db->insert_id();
    }
    function getInfo(){
        return $this->db->query("SELECT 
                cch.idusuario AS responsable, 
                cch.idcaja,
                IFNULL(r.nombre_completo, 'SIN DEFINIR' ) rembolsar,
                cch.monto as monto, 
                cch.nombre, 
                cch.idcontrato, 
                cch.estatus,
                emp.idsempresa,
                emp.empresas,
                dep.iddeptos,
                dep.deptos,
                COALESCE(doc.docs, 0) as docs, 
                COALESCE(doc.docs_r, 0) as docs_r,
                inc_dec.aumento AS aumento,
                inc.movEmpDoc,
                rembolso.total as rem,
                cierre.total as cierre,
                IFNULL(cch.nombre_reembolso_ch, 'NA') pertenece_a,
                lc.comentario_cierre
        FROM (
            SELECT *
            FROM cajas_ch
            WHERE estatus >= 0
        ) cch
        INNER JOIN (
            SELECT ce.idcaja, GROUP_CONCAT( em.idempresa) AS idsempresa, GROUP_CONCAT(em.nombre) AS empresas
            FROM  caja_empresa ce
            INNER JOIN empresas em ON em.idempresa = ce.idempresa
            WHERE ce.estatus=1
            GROUP BY ce.idcaja 
        ) emp ON emp.idcaja = cch.idcaja
        INNER JOIN (
        SELECT cd.idcaja, GROUP_CONCAT(iddepartamentos) AS iddeptos, GROUP_CONCAT(d.departamento) AS deptos
                FROM caja_depto cd
                INNER JOIN departamentos d ON d.iddepartamentos = cd.iddepto
                WHERE cd.estatus = 1
                GROUP BY cd.idcaja
                ) dep ON dep.idcaja = cch.idcaja
        LEFT JOIN listado_usuarios r ON cch.idusuario = r.idusuario
        LEFT JOIN (
            SELECT 
                idcaja, 
                SUM(docs) as docs,
                SUM(CASE WHEN estatus = 2 OR estatus = 4 THEN docs ELSE 0 END) as docs_r
                FROM (
                        SELECT idcaja, COUNT(*) as docs, estatus
                        FROM cajach_documentos
                        GROUP BY idcaja, estatus
                    UNION ALL
                        SELECT idSolicitud AS idcaja, COUNT(*) as docs, estatus
                        FROM historial_documento
                        WHERE tipo_doc IN (5, 7)
                        GROUP BY idSolicitud, estatus
                ) x
            GROUP BY idcaja
        ) doc ON doc.idcaja = cch.idcaja
        LEFT JOIN ( SELECT idcaja, COUNT(CASE WHEN campo = 'estatus_empresa' OR campo = 'empresa' OR campo = 'documento' THEN 1 END) as movEmpDoc
                    FROM log_cajasch 
                    GROUP BY idcaja ) inc ON inc.idcaja = cch.idcaja
        LEFT JOIN ( SELECT 
                        idcaja,
                         CASE
                             WHEN CAST(anterior AS DECIMAL(10,2)) < CAST(nuevo AS DECIMAL(10,2)) THEN 1
                            WHEN CAST(anterior AS DECIMAL(10,2)) > CAST(nuevo AS DECIMAL(10,2)) THEN -1
                            ELSE 0
                        END AS aumento
                    FROM log_cajasch 
                    WHERE campo = 'monto'
                    ORDER BY idlog DESC 
                    LIMIT 1) inc_dec ON inc_dec.idcaja = cch.idcaja
        LEFT JOIN (
                SELECT idcaja, CASE WHEN tipo = 'close' THEN observacion END AS comentario_cierre
                FROM log_cajasch
                WHERE tipo = 'close'
                GROUP BY idcaja
            ) lc ON lc.idcaja = cch.idcaja
        LEFT JOIN (
                select SUM(rem.cantidad) as total, rem.idcajach
                FROM(select chsol.idpago, chsol.idcajach, pgch.cantidad from autpagos_cchsol chsol
                inner join autpagos_caja_chica pgch ON pgch.idpago = chsol.idpago
                where idcajach IS NOT NULL group by idpago ) rem group by rem.idcajach ) rembolso ON rembolso.idcajach = cch.idcaja
        LEFT JOIN (
            SELECT c.idcajach, SUM(c.cantidad) total
            FROM(
            SELECT csol.idsolicitud, csol.idcajach, sol.cantidad FROM cajach_solcierre csol
            INNER JOIN solpagos sol ON sol.idsolicitud = csol.idsolicitud
            WHERE idcajach IS NOT NULL) c
            GROUP BY c.idcajach
        ) cierre ON cierre.idcajach = cch.idcaja
        WHERE cch.estatus NOT IN (2) and (cch.idusuario <> 77 or cch.idusuario IS NULL)"); //Ajuste en la consulta  /** FECHA: 29-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
    }

    function busca_emp($idcontrato){
        return $this->db->query("SELECT cem.idempresa
                                    FROM cajas_ch cch
                                    JOIN caja_empresa cem ON cch.idcaja = cem.idcaja
                                    WHERE cch.idcontrato = ? AND cch.estatus = 1  AND cem.estatus = 1",[$idcontrato]);

    }

    function insertPdfSol($data)
	{
		return $this->db->insert("historial_documento", $data);
	}
    
    /**
     * INICIO | FECHA : 25-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Se crea esta funcion que inserta registros en la tabla 
     * auditoria al momento de actualizar los campos nombre_reembolso_ch y idreembolso_ch de la tabla cajas_ch
     */
    function insertAuditoria($idcaja, $ant, $new, $campo){
        $respuesta= $this->db->insert("auditoria", [   "id_parametro"=>$idcaja,
                                                    "tipo"  =>'UPDATE',
                                                    "anterior"=>$ant,
                                                    "nuevo"=> $new,
                                                    "col_afect"=> $campo,
                                                    "tabla"=> "cajas_ch",
                                                    "fecha_creacion"=> date("Y-m-d H:i:s"),
                                                    "creado_por"=>  $this->session->userdata("inicio_sesion")['id']

                                                ]);
    }
    /**
     * FIN | FECHA : 25-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com
     */
}