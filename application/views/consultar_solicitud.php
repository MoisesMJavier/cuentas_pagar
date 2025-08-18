<style>
    #pdf-container {
        width: 100%;
        height: 0;
        padding-bottom: 100%; /* Relación de aspecto 1:1 (cuadrado) */
        position: relative;
        overflow: hidden;
        padding-right: 0px;
        padding-left: 0px;
        padding-top: 15px;
    }

    #pdf-container embed {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }
</style>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">INFORMACIÓN DE LA SOLICITUD #<?php echo $idsolicitud;?></h4>
    </div>
    <div class="modal-body">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li <?= $notificacion == 0 ? 'class="active"' : '' ?>><a data-toggle="tab" href="#info">INFORMACIÓN</a></li>
                <li><a data-toggle="tab" href="#infoImpuestos">CONCEPTO</a></li>
                <li <?= $notificacion > 0 ? 'class="active"' : '' ?>><a data-toggle="tab" href="#obser">OBSERVACIONES</a></li>
                <?php if ($this->session->userdata("inicio_sesion")["depto"] == 'CONTRALORIA'){ ?> <!-- INICIO FECHA: 27-FEBRERO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                    <li><a data-toggle="tab" href="#obserDep">OBSERVACIONES POR DEPARTAMENTO</a></li>
                <?php } ?> <!-- FIN FECHA: 27-FEBRERO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                <li><a data-toggle="tab" href="#historial">HISTORIAL</a></li>
                <li><a href="https://verificacfdi.facturaelectronica.sat.gob.mx/" target="_blank">SAT</a></li>
                <li><a data-toggle="tab" href="#docs" id="tabDocs">DOCUMENTOS</a></li>
            </ul>
        </div>
        <div class="tab-content">
            <div id="info" class="tab-pane fade <?= $notificacion == 0 ? 'in active' : '' ?>">
                <div class="row">
                    <div class="col-lg-12">
                        <h5 id="version_fac">VERSION: <b><?= ( isset($datos_factura) ? $datos_factura['version'] : "---" ) ?></b></h5>
                    </div>
                    <div class="col-lg-6">
                        <h4>RECEPTOR</h4>
                        <h5 id="rfcrec_fac">RFC: <b><?= $datos_solicitud->rfc_receptor ?></b></h5>
                        <h5 id="nombrerec_fac">NOMBRE: <b><?= $datos_solicitud->n_receptor ?></b></h5>
                        <h5 id="rf_proveedor">RÉGIMEN FISCAL: <b><?= $datos_solicitud->rf_empresa ?></b></h5>
                        <h5 id="cp_proveedor">CÓDIGO POSTAL: <b><?= $datos_solicitud->cp_empresa ?></b></h5>
                    </div>
                    <div class="col-lg-6">
                        <h4>EMISOR</h4>
                        <h5 id="rfcemi_fac">RFC: <b><?= $datos_solicitud->rfc ?></b></h5>
                        <h5 id="nombreemi_fac">NOMBRE <b><?= $datos_solicitud->nombre ?></b></h5>
                        <h5 id="rf_empresa">RÉGIMEN FISCAL <b><?= $datos_solicitud->rf_proveedor?></b></h5>
                        <h5 id="cp_empresa">CÓDIGO POSTAL <b><?= $datos_solicitud->cp_proveedor ?></b></h5>..
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-7">
                        <h5>PROYECTO/DEPARTAMENTO: <b><?= preg_match('/^[0-9]+$/', $datos_solicitud->proyecto) ?  $datos_solicitud->proyectoNuevo: $datos_solicitud->proyecto ?></b></h5>
                        <h5>OFICINA/SEDE: <b><?= isset($datos_solicitud->oficina) ? $datos_solicitud->oficina : 'N/A' ?></b></h5>  
                        <h5>CONDOMINIO: <b><?= $datos_solicitud->condominio ?></b></h5>
                        <h5>TIPO SERVICIO/PARTIDA: <b><?= isset($datos_solicitud->tServicioPartida) ? $datos_solicitud->tServicioPartida : 'N/A' ?></b></h5>
                        <h5>TIPO INSUMO: <b><?= $datos_solicitud->caja_chica && $datos_solicitud->insumo ? $datos_solicitud->insumo : '-' ?></b></h5>
                    </div>
                    <div class="col-lg-5">
                        <h5>ETAPA: <b><?=  $datos_solicitud->etapa ?></b></h5>
                        <h5>HOMOCLAVE <b><?= $datos_solicitud->homoclave ?></b></h5>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <h5>ESTATUS DE SOLICITUD:  <b><?= $datos_solicitud->nom_etapa ?></b></h5>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> <!-- INICIO FECHA: 26-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                        <?php 
                            $uuidExist  = array_filter($datos_solicitud_array->result_array(), function ($elemento) {
                                return !empty($elemento['uuid']);
                            });

                            if (count($uuidExist) > 1){
                                echo '<div class="col-lg-10 col-md-10 col-sm-10 col-xs-10" style="padding-right: 0px; padding-left:0px;">
                                    <p for="sel_factura">SELECCIONE UNA FACTURA:</p>
                                </div>';
                            }
                        ?>
                        <?php if((count($datos_solicitud_array->result()) > 1 && count($datos_solicitud_array->result()) <= 10) && in_array( $this->session->userdata("inicio_sesion")['rol'], array( 'CC', 'CE', 'CT', 'CX' ))){
                                echo '<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2" style="padding: 0 0 5px 0; display: flex; flex-direction: column;">
                                    <button class="btn btn-primary btn-sm descarga_multiple" data-value="'.$idsolicitud.'" data-toggle="tooltip" data-placement="top" title="Descargar todos los archivos XML" style="margin-top: auto; display: block; align-self: flex-end;">
                                        <i class="fa fa-download" aria-hidden="true"></i>
                                    </button>
                                </div>';
                            }else if (count($datos_solicitud_array->result()) > 10 && in_array( $this->session->userdata("inicio_sesion")['rol'], array( 'CC', 'CE', 'CT', 'CX' ))) {
                                echo '<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2" style="padding: 0 0 5px 0; display: flex; flex-direction: column;">
                                        <button type="button" class="btn btn-warning text-black btn-sm descargar_zip" data-value="'.$idsolicitud.'" data-toggle="tooltip" data-placement="top" title="Descargar .zip XMLS" style="margin-top: auto; display: block; align-self: flex-end;">
                                            <i class="fa fa-file-archive-o" aria-hidden="true"></i>
                                        </button>
                                    </div>';
                            }
                        ?>
                        <select class="form-control" id="sel_factura" onchange="cambiar_factura(this.value)">
                            <?php 
                                $i=0;
                                $facturas_xml=array();
                                if($datos_solicitud_array!=null ){
                                    foreach ($datos_solicitud_array->result() as $row){
                                        echo '<option value="'.$i.'">Factura: '.($row->nombre_archivo).'</option>';
                                        if( $row->nombre_archivo && file_exists ( "./UPLOADS/XMLS/".$row->nombre_archivo )  ){
                                            $facturas_xml[$i] = $this->Complemento_cxpModel->leerxml( "./UPLOADS/XMLS/".$row->nombre_archivo, FALSE );
                                        }
                                        $i++;
                                    }
                                }
                            ?>
                        </select>
                    </div>
                </div> <!-- FIN FECHA: 26-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->

                <div class="row"> <!-- FECHA: 26-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> <!-- FECHA: 26-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                        <h5 id="facturaarch_fac" style="padding: 0px;">FACTURA: <b> <!-- FECHA: 26-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                            <?php
                                if($datos_solicitud->existePdfGPND>0){
                                    $pdfLabelGPND ="<label class='label pull-center bg-purple-active'><a href= ".base_url()."UPLOADS/PDFS/".$datos_solicitud->pdfFileGPND." download style='color: inherit; text-decoration: none;'>PDF GASTO PROVEEDOR NO DEDUCIBLE</a></label>";
                                }else {
                                    $pdfLabelGPND = '';
                                }
                                $documentoPDF = $datos_solicitud->nomdepto === 'COMERCIALIZACION'
                                    ? ($this->session->userdata("inicio_sesion")['depto'] !== 'COMERCIALIZACION' || in_array($this->session->userdata("inicio_sesion")['rol'], array('CP', 'SU', 'DG', 'DP'))
                                        ? ($datos_solicitud->existePdf > 0 
                                            ? $pdfLabel = "<label class='label pull-center bg-primary'><a href='".base_url()."UPLOADS/PDFS/".$datos_solicitud->pdfFile."' download style='color: inherit; text-decoration: none;'>PDF DISPONIBLE</a></label>" // FECHA: 26-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com>
                                            : '')
                                        : '')
                                    : '';
                                if($datos_solicitud->nombre_archivo) 
                                    echo "<a href=".base_url()."UPLOADS/XMLS/".$datos_solicitud->nombre_archivo." target='_blank' download>".$datos_solicitud->nombre_archivo."</a>"." ".$documentoPDF; 
                                else 
                                    echo "<span class='text-danger'>AUN NO SE HA AGREGADO</span>".$documentoPDF." ".$pdfLabelGPND;  
                            ?>
                            </b>
                        </h5>
                    </div>
                    <div class="col-lg-12">
                        <h5>CANTIDAD SOLICITADA: <b>$ <?= number_format($datos_solicitud->cantidad, 2, ".", ",") ?></b>    <?= isset($datos_solicitud->intereses) ? "INTERES AGREGADO: <b>".number_format($datos_solicitud->cantidad, 2, ".", ",")."</b>" : "" ?></h5>
                    </div>
                </div>
                <?php if($datos_solicitud->caja_chica==4){ ?>
					<div class="row">
						<hr>
						<div class="col-lg-12">
							<h4>VIÁTICOS</h4>
							<?php
							if($datos_solicitud->pais_gasto =='MX'){
								$pais_gasto = 'México';
							}elseif($datos_solicitud->pais_gasto =='EU'){
								$pais_gasto = 'Estados Unidos';
							}else{
								$pais_gasto = 'N/A';
							}

							switch ($datos_solicitud->tipo_insumo) {
								case 1:
									$tipo_insumo = isset($datos_solicitud->diasDesayuno) ? 'DESAYUNO ('.$datos_solicitud->diasDesayuno.' días)' : 'DESAYUNO';
									break;
								case 2:
									$tipo_insumo = isset($datos_solicitud->diasComida) ? 'COMIDA ('.$datos_solicitud->diasComida.' días)' : 'COMIDA';
									break;
								case 3:
									$tipo_insumo = isset($datos_solicitud->diasCena) ? 'CENA ('.$datos_solicitud->diasCena.' días)' : 'CENA';
									break;
                                case 4:
									$tipo_insumo = isset($datos_solicitud->diasDesayuno) ? 'DESAYUNO ('.$datos_solicitud->diasDesayuno.' días)' : 'DESAYUNO';
                                    $tipo_insumo .= isset($datos_solicitud->diasComida) ? ', COMIDA ('.$datos_solicitud->diasComida.' días)' : ', COMIDA';
									break;
                                case 5:
									$tipo_insumo = isset($datos_solicitud->diasDesayuno) ? 'DESAYUNO ('.$datos_solicitud->diasDesayuno.' días)' : 'DESAYUNO';
                                    $tipo_insumo .= isset($datos_solicitud->diasCena) ? ', CENA ('.$datos_solicitud->diasCena.' días)' : ', CENA';
									break;
                                case 6:
									$tipo_insumo = isset($datos_solicitud->diasComida) ? 'COMIDA ('.$datos_solicitud->diasComida.' días)' : 'COMIDA';
                                    $tipo_insumo .= isset($datos_solicitud->diasCena) ? ', CENA ('.$datos_solicitud->diasCena.' días)' : ', CENA';
									break;
                                case 7:
									$tipo_insumo =  isset($datos_solicitud->diasDesayuno) ? 'DESAYUNO ('.$datos_solicitud->diasDesayuno.' días)' : 'DESAYUNO';
                                    $tipo_insumo .= isset($datos_solicitud->diasComida) ? ', COMIDA ('.$datos_solicitud->diasComida.' días)' : ', COMIDA';
                                    $tipo_insumo .= isset($datos_solicitud->diasCena) ? ', CENA ('.$datos_solicitud->diasCena.' días)' : ', CENA';
									break;
								default:
									$tipo_insumo = 'N/A';
									break;
							}
							?>
							<label>PAÍS: </label>  <?=$pais_gasto?><br>
                            <label>ZONA: </label> <?php echo strtoupper($datos_solicitud->zona); ?><br>
                            <label>ESTADO: </label> <?php echo $datos_solicitud->estado; ?><br>
							<label>NÚMERO DE COLABORADORES: </label>  <?php echo $datos_solicitud->colabs; ?><br>
							<label>TIPO INSUMO: </label>  <?=$tipo_insumo?><br>
							<?php if($datos_solicitud->existePdfAut == 1){?>
								<label>AUTORIZACIÓN: </label> <a href="<?=base_url()?>UPLOADS/AUTSVIATICOS/<?=$datos_solicitud->pdfFileAut?>" download><label class='label pull-center bg-blue-active'>ARCHIVO AUT VIÁTICOS</label></a>
							<?php }?>
						</div>
					</div>
				<?php }?>
                <div class="row">
                    <hr>
                    <?php
                        if( $datos_solicitud->descripcion != NULL )
                            echo '<div class="col-lg-6">
                                <p class="text-justify"><b>JUSTIFICACIÓN: </b><br/>'.$datos_solicitud->justificacion.'</p>
                            </div>'.
                            '<div class="col-lg-6">
                                <p class="text-justify"><b>CONCEPTO(S) CFDI: </b><br/>'.$datos_solicitud->descripcion.'</p>
                            </div>';
                        else
                            echo '<div class="col-lg-12">
                                <p class="text-justify"><b>JUSTIFICACIÓN: </b><br/>'.$datos_solicitud->justificacion.'</p>
                            </div>';
                    ?>
                    <hr/>
                </div>
                <?php if($datos_solicitud->caja_chica==4){ ?>
					<div class="row">
						<hr>
						<div class="col-lg-12">
							<h4>VIÁTICOS</h4>
							<?php
							if($datos_solicitud->pais_gasto =='MX'){
								$pais_gasto = 'México';
							}elseif($datos_solicitud->pais_gasto =='EU'){
								$pais_gasto = 'Estados Unidos';
							}else{
								$pais_gasto = 'N/A';
							}

							switch ($datos_solicitud->tipo_insumo) {
								case 1:
									$tipo_insumo = isset($datos_solicitud->diasDesayuno) ? 'DESAYUNO ('.$datos_solicitud->diasDesayuno.' días)' : 'DESAYUNO';
									break;
								case 2:
									$tipo_insumo = isset($datos_solicitud->diasComida) ? 'COMIDA ('.$datos_solicitud->diasComida.' días)' : 'COMIDA';
									break;
								case 3:
									$tipo_insumo = isset($datos_solicitud->diasCena) ? 'CENA ('.$datos_solicitud->diasCena.' días)' : 'CENA';
									break;
                                case 4:
									$tipo_insumo = isset($datos_solicitud->diasDesayuno) ? 'DESAYUNO ('.$datos_solicitud->diasDesayuno.' días)' : 'DESAYUNO';
                                    $tipo_insumo .= isset($datos_solicitud->diasComida) ? ', COMIDA ('.$datos_solicitud->diasComida.' días)' : ', COMIDA';
									break;
                                case 5:
									$tipo_insumo = isset($datos_solicitud->diasDesayuno) ? 'DESAYUNO ('.$datos_solicitud->diasDesayuno.' días)' : 'DESAYUNO';
                                    $tipo_insumo .= isset($datos_solicitud->diasCena) ? ', CENA ('.$datos_solicitud->diasCena.' días)' : ', CENA';
									break;
                                case 6:
									$tipo_insumo = isset($datos_solicitud->diasComida) ? 'COMIDA ('.$datos_solicitud->diasComida.' días)' : 'COMIDA';
                                    $tipo_insumo .= isset($datos_solicitud->diasCena) ? ', CENA ('.$datos_solicitud->diasCena.' días)' : ', CENA';
									break;
                                case 7:
									$tipo_insumo =  isset($datos_solicitud->diasDesayuno) ? 'DESAYUNO ('.$datos_solicitud->diasDesayuno.' días)' : 'DESAYUNO';
                                    $tipo_insumo .= isset($datos_solicitud->diasComida) ? ', COMIDA ('.$datos_solicitud->diasComida.' días)' : ', COMIDA';
                                    $tipo_insumo .= isset($datos_solicitud->diasCena) ? ', CENA ('.$datos_solicitud->diasCena.' días)' : ', CENA';
									break;
								default:
									$tipo_insumo = 'N/A';
									break;
							}
							?>
							<label>PAÍS: </label>  <?=$pais_gasto?><br>
                            <label>ZONA: </label> <?php echo strtoupper($datos_solicitud->zona); ?><br>
                            <label>ESTADO: </label> <?php echo $datos_solicitud->estado; ?><br>
							<label>NÚMERO DE COLABORADORES: </label>  <?php echo $datos_solicitud->colabs; ?><br>
							<label>TIPO INSUMO: </label>  <?=$tipo_insumo?><br>
							<?php if($datos_solicitud->existePdfAut == 1){?>
								<label>AUTORIZACIÓN: </label> <a href="<?=base_url()?>UPLOADS/AUTSVIATICOS/<?=$datos_solicitud->pdfFileAut?>" download><label class='label pull-center bg-blue-active'>ARCHIVO AUT VIÁTICOS</label></a>
							<?php }?>
						</div>
					</div>
				<?php }?>
                <div class="row">
                <hr/>
                    <div class="col-lg-6">
                        <h5>ESTATUS: <b><?= $datos_solicitud->prioridad == 1 ? "<small class='label pull-center bg-red'>URGENTE</small>" : "NORMAL" ?></b></h5>
                        <h5>REFERENCIA BANCARIA: <b><?= $datos_solicitud->ref_bancaria != '' ? "<small class='label pull-center bg-aqua'>".$datos_solicitud->ref_bancaria."</small>" : "NA" ?></b></h5>
                        <h5>TIPO DE GASTO: <b><?= $datos_solicitud->caja_chica == 1 ? "Caja Chica" : "Otro" ?></b></h5>
                        <h5 id="folio_fac">FOLIO: <b><?= $datos_solicitud->folio ?></b></h5>
                        <h5 id="tipo_comprobante_fac" class="nodev">TIPO COMPROBANTE: <b><?= isset($datos_factura) && isset($datos_factura['tipocomp']) ? $datos_factura['tipocomp'][0] : "---" ?></b></h5>
                        <h5 id="met_pago_fac" class="nodev">MÉTODO DE PAGO: <b><?= $datos_solicitud->metodo_pago ?></b></h5>
                        <h5 id="fpago_fac" class="nodev">FORMA DE PAGO: <b><?= isset($datos_factura) && isset($datos_factura['formpago']) ? $datos_factura['formpago'][0] : "---" ?></b></h5>
                        <h5 id="condi_pago_fac" class="nodev">CONDICIONES DE PAGO: <b><?= isset($datos_factura) && isset($datos_factura['condpago']) ? $datos_factura['condpago'][0] : "---" ?></b></h5>
                        
                    </div>
                    <div class="col-lg-6">
                        <h5 id="modopagoprov_fac">MODO DE PAGO A PROVEEDOR: <b><?= $datos_solicitud->forma_pago?></b></h5>

                        <h5 id="tipocta" class="sidev">TIPO CUENTA: <b><?= isset($datos_solicitud->tipocta) ? $datos_solicitud->tipocta : "---" ?></b></h5>
                        <h5 id="numcta" class="sidev">NUM. CUENTA: <b><?= isset($datos_solicitud->cuenta) ? $datos_solicitud->cuenta : "---" ?></b></h5>
                        <h5 id="bancocta" class="sidev">BANCO: <b><?= isset($datos_solicitud->banco) ? $datos_solicitud->banco : "---" ?></b></h5>

                        <h5 id="fecha_fac" class="nodev">FECHA FACTURA: <b><?= isset($datos_factura) ? $datos_factura['fecha'][0] : "---" ?></b></h5>
                        <!--<h5 id="fpago_fac">FORMA DE PAGO: <b><?= isset($datos_factura) && isset($datos_factura['formpago'][0]) ? $datos_factura['formpago'][0] : "---" ?></b></h5>-->
                        <h5 id="cfdi" class="nodev">USO CFDI: <b><?= isset($datos_factura) ? $datos_factura['usocfdi'][0] : "---" ?></b></h5>
                        <h5 id="tipocambio_fac" class="nodev">TIPO CAMBIO : <b><?= isset($datos_factura) && isset( $datos_factura['TipoCambio'] ) ? $datos_factura['TipoCambio'][0] : "---" ?></b></h5>
                        <h5 id="tipo_comprobante_fac2" class="nodev">TIPO COMPROBANTE : <b><?= isset($datos_factura) ? $datos_factura['TipoDeComprobante'][0] : "---" ?></b></h5>
                        <h5 id="importeret_fac" class="nodev">IMPORTE DE RETENCIÓN: <b><?= isset($datos_factura) && $datos_factura['TotImpRet'][0] ? $datos_factura['TotImpRet'][0] : "---" ?></b></h5>                    
                        <h5 id="importetras_fac" class="nodev">IMPORTE DE TRASLADO: <b><?= isset($datos_factura) && $datos_factura['TotImpTras'][0] ? $datos_factura['TotImpTras'][0]: "---" ?></b></h5>
                        <h5 id="subtotal_fac" class="nodev">SUBTOTAL : <b><?= isset($datos_factura) ? $datos_factura['SubTotal'][0] : "---" ?></b></h5>
                        <h5 id="descuento_fac" class="nodev">DESCUENTO: <b><?= isset($datos_factura) && $datos_factura['Descuento'] ? $datos_factura['Descuento'][0]: "---" ?></b></h5>
                        <h5 id="total_fac" class="nodev">TOTAL EN FACTURA: <b><?= $datos_solicitud->total ? "$ ".number_format( $datos_solicitud->total, 2, ".", "," ) : "---" ?></b></h5>
                    </div>
                    <div class="col-lg-12 nodev">
                        <h5 id="uuid_fac">UUID: <b><?= $datos_solicitud->uuid ?></b></h5>
                    </div>
                    <div class="col-lg-12 nodev">
                        <h5 id="uuidr_fac">UUID RELACIONADOS: <b></b></h5>
                        <?php
                            if( isset($datos_factura) ){
                                foreach( $datos_factura["uuidR"] as $row ){
                                    echo "<p><b>".$row->attributes()["UUID"]."</b></p>";
                                }
                            }else{
                                echo "---";
                            }
                        ?>
                    </div>
                </div>
            </div>
            <div id="infoImpuestos" class="tab-pane fade" >
                <div class="row">
                  
                    <div class="col-lg-12 chat">
                         <?php
                         
                        $j=0;

                        if(count($facturas_xml) > 0 ){ // FECHA: 26-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com>

                            $conceptos = [];
                            $conceptos = json_decode( preg_replace('/[\x00-\x1F\x80-\xFF]/', "", "{".implode( ",", array_column($this->db->query("SELECT CONCAT( '\"', claveProdServ,'\" : \"',REPLACE(Descripcion, '\"', ''),'\"' ) catalogo FROM productos")->result_array(), "catalogo"))."}"), true);
                            $conceptos = $conceptos ? $conceptos : [];
                            foreach( $facturas_xml as $datos_factura){
                                
                                $concepto = $datos_factura["conceptos"];
                                $traslados = $datos_factura["impuestos_traslados"];
                                $retenciones =  $datos_factura["impuestos_retenciones"];
                                if(isset($concepto) && isset($concepto)){

                                    $i = 0;

                                    foreach( $concepto as $general){

                                        echo '<div class="col-lg-12 conceptos">';
                                        echo '<h5>CANTIDAD: <b>'.$general['Cantidad'].'</b></h5>';
                                        echo '<h5>CLAVE PRODUCTO : <b>'.$general['ClaveProdServ'].''
                                        . '<i class="far fa-question-circle faq" tabindex="0" '
                                        . 'data-container="body" data-trigger="focus" data-toggle="popover" '
                                        . 'title="Clave Producto" '
                                        . 'data-content="'.( isset($conceptos[ (string)$general['ClaveProdServ'] ] ) ? $conceptos[ (string)$general['ClaveProdServ'] ] :  'NO SE ENCONTRO ESTE PRODUCTO. FAVOR DE SOLICITAR AL ADMINISTRADOR ACTUALIZAR EL CATALOGO').'" data-placement="right"></i></b></h5>';
                                        echo '<h5>CLAVE UNIDAD :<b>'.$general['ClaveUnidad'].'</b></h5>';
                                        echo '<h5>VALOR UNITARIO :<b>'.$general['ValorUnitario'].'</b></h5>';
                                        echo '<h5>DESCRIPCIÓN : <b>'.$general['Descripcion'].'</b></h5>';

                                        if( $i < count($traslados) && isset( $traslados[$i]) && !empty( $traslados[$i] ) ){
                                        echo '<div class="col-md-6"><h4>TRASLADOS</h4>';
                                        echo '<h5>IMPUESTO: <b>'. $traslados[$i]["Impuesto"].'</b></h5>';
                                        echo '<h5>TASA CUOTA: <b>'.$traslados[$i]["TasaOCuota"].'</b></h5>';
                                        echo '<h5>IMPORTE: <b>'.$traslados[$i]["Importe"].'</b></h5></div>';
                                        }else{
                                        echo '<div class="col-md-6"><h4>TRASLADOS</h4>';
                                        echo '<h5>IMPUESTO: <b> --- </b></h5>';
                                        echo '<h5>TASA CUOTA: <b>  ---  </b></h5>';
                                        echo '<h5>IMPORTE: <b>  ---  </b></h5></div>';
                                        }

                                        if( $i < count($retenciones) && isset( $retenciones[$i]) && !empty( $retenciones[$i] ) ){
                                        echo '<div class="col-md-6"><h4>RETENCIONES</h4>';
                                        echo '<h5>IMPUESTO: <b>'.$retenciones[$i]['Impuesto'].'</b></h5>';
                                        echo '<h5>TASA CUOTA: <b>'.$retenciones[$i]['TasaOCuota'].'</b></h5>';
                                        echo '<h5>IMPORTE: <b>'.$retenciones[$i]['Importe'].'</b></h5></div>';
                                        echo '<hr/>';
                                        }else{

                                        echo '<div class="col-md-6"><h4>RETENCIONES</h4>';
                                        echo '<h5>IMPUESTO: <b> --- </b></h5>';
                                        echo '<h5>TASA CUOTA: <b>  ---  </b></h5>';
                                        echo '<h5>IMPORTE: <b>  ---  </b></h5></div>';
                                        }
                                        $i++;
                                        echo '</div>';
                                    }  
                                }
                                $j++;
                            }
                        }
                         if ($j==0){
                                echo '<h5>CANTIDAD: <b> --- </b></h5>';
                                echo '<h5>CLAVE PRODUCTO: <b>  ---  </b></h5>';
                                echo '<h5>CLAVE UNIDAD: <b>  ---  </b></h5>';
                                echo '<h5>DESCRIPCIÓN: <b>  ---  </b></h5>';
                                echo '<hr/>';
                         }
                         
                         
                        ?>
                    </div>
                </div>
               
            </div>
            <div id="obser" class="tab-pane fade <?= $notificacion > 0 ? 'in active' : '' ?>">
                <div class="row">
                    <div class="col-lg-12">
                        <div id="chat" class="form-control chat"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="input-group">
                            <input type="text" class="form-control" id="comentario" required> <!-- FECHA: 27-FEBRERO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                            <div class="input-group-btn">
                                <button type="button" class="btn btn-primary" id="agregar_comentario"><i class="fas fa-comments"></i> COMENTAR</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="obserDep" class="tab-pane fade"> <!-- INICIO FECHA: 27-FEBRERO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                <div class="row">
                    <div class="col-lg-12">
                        <div id="chatDep"></div>
                    </div>
                </div>
            </div> <!-- FIN FECHA: 27-FEBRERO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
            <div id="historial" class="tab-pane fade">
                <div class="row">
                    <div class="col-lg-6">
                        <h4>HISTORIAL DE MOVIMIENTOS</h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div id="chat" class="form-control chat">
                            <?php /** INICIO FECHA: 14-MARZO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                                echo $movimientos;
                            ?> <!-- FIN FECHA: 14-MARZO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                        </div>
                    </div>
                </div>
            </div>
            <div id="docs" class="tab-pane fade">
                <input type="hidden" id="idsolicitud" name="idsolicitud" value='<?php echo $idsolicitud; ?>'>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group s_file">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <h5><b>DOCUMENTOS CARGADOS</b></h5>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12" id="detalle">
                        
                    </div>
                </div>
                <div class="row" hidden id="pdfGPND" >
					<hr>
					<div class="row" style="margin-left: 0px; margin-right: 0px;">
						<div class="col-lg-12">
							<h5><b>PDF GASTO PROVEEDOR NO DEDUCIBLE</b></h5>
						</div>
                        <div class="pdfArea_6">
                        </div>
					</div>
				</div>
                <div class="row" <?php if($datos_solicitud->existePdf==0 || $datos_solicitud->nomdepto !== 'COMERCIALIZACION' ||
                                          ($this->session->userdata("inicio_sesion")['depto'] !== 'COMERCIALIZACION' && 
                                            !in_array($this->session->userdata("inicio_sesion")['rol'], array('CP', 'SU', 'DG', 'DP')) )
                                        ){?> hidden <?php }?> id="pdfComer">
					<hr>
					<div class="row" style="margin-left: 0px; margin-right: 0px;">
						<div class="col-lg-12">
							<h5><b>PDF COMERCIALIZACIÓN</b></h5>
						</div>
						<?php if ($datos_solicitud->pdfFile) {?>
                            <div class='col-lg-9'>
                                <h5> <?=$datos_solicitud->pdfFile?> </h5> <!-- FECHA: 26-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                            </div>
                            <div class='col-lg-3'>
                                <div class='btn-group' role='group' aria-label='Basic example'>
                                    <a  href='<?=base_url()?>UPLOADS/PDFS/<?=$datos_solicitud->pdfFile?>' 
                                        target='_blank' 
                                        class='btn btn-sm btn-primary archivos'> <i class='fas fa-download'> </i>
                                    </a>

                                <?php if( ($this->session->userdata('inicio_sesion')["id"] == $datos_solicitud->usuarioSubioPdf) || ($this->session->userdata("inicio_sesion")['rol'] == 'CP') ){?>
                                    <button title='Borrar documento'
                                            name= '" + data.iddocumentos_solicitud + "'
                                            onclick='borrar_doc(<?=$datos_solicitud->idDocumentoPdf?> , <?=$idsolicitud?> )' 
                                            class='btn btn-sm btn-danger' >
                                        <i class='fas fa-trash-alt'></i>
                                    </button>
                                <?php }?>

                                </div>
                            </div>
                        <?php }?>
					</div>
				</div>
                <div class="row" id="gastoMayor" hidden>
                    <div class="row" style="margin-left: 0px; margin-right: 0px;">
                        <div class="col-lg-12">
                            <h5><b>DOCUMENTOS CARGADOS PROVEEDOR - GASTO MAYOR A $5,000</b></h5>
                        </div>
                    </div>
                    <div class="pdfArea_2">
                        <div class="row" style="margin-left: 0px; margin-right: 0px;">
                            <div class="col-lg-12" id="detalleProveedor"></div>
                        </div>
                    </div>
                </div>
                <div class="row" id="pdfAutViaticos" hidden>
                    <div class="row" style="margin-left: 0px; margin-right: 0px;">
                        <div class="col-lg-12">
                            <h5><b>PDF AUTORIZACIÓN REEMBOLSO</b></h5>
                        </div>
                    </div>
                    <div class="pdfArea_3">
                        <div class="row" style="margin-left: 0px; margin-right: 0px;">
                            <div class="col-lg-12" id="detalleProveedor"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Sustituir Documento Comer-->
    <div class="modal fade" id="modalDoc" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm" role="document" id="modal-del-doc">
            <div class="modal-content text-center">
                <div style="padding: 5px;" class="modal-header bg-red">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title" id="exampleModalCenterTitle">¡AVISO!</h3>
                </div>
                <div class="modal-body" >
                    <h5> ¿Deseas sustituir el documento de esta solicitud? </h5>
                    <input class="datos_modal_alert" type="hidden" value=""/>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" data-dismiss="modal">CANCELAR</button>
                    <button type="button" class="btn btn-danger borrar_solicitud_modal">ELIMINAR</button>
                </div>
            </div>
        </div>
    </div>
    <script>

        var num_comentarios = 0;
        var facturas = (
            <?php 
            echo json_encode(($datos_solicitud_array!=null)?$datos_solicitud_array->result():"{}");
            ?>
                );
                
        var facturas_xml = (
            <?php 
            echo json_encode($facturas_xml);
            ?>
                );
        $('#consultar_modal').on('show.bs.modal', function(){
            documentos_pdf($('#idsolicitud').val());
            documentos($('#idsolicitud').val());
            recargar_conversacion($('#idsolicitud').val());
            $('[data-toggle="popover"]').popover();
            $('[data-toggle="tooltip"]').tooltip(); 
            cargarConversacionDepartamento(); /** FECHA: 27-FEBRERO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
        });
        $(document).ready( function(){
            if(facturas_xml.length>0)
                $("#sel_factura").change();
            else
                $("#sel_factura").hide();

            <?php
            if( strpos($datos_solicitud->nomdepto, "DEVOLUCION") !== false || strpos($datos_solicitud->nomdepto, "TRASPASO") !== false)
                echo '$(".nodev").remove();';
            else
                echo '$(".sidev").remove();';
            ?>
        });

        function recargar_conversacion(idsolicitud){
            num_comentarios = 0;
            $.post( url + "Consultar/getConversacion", { idsolicitud : idsolicitud } ).done(function( data ){
                data = JSON.parse(data);
                if( data.comentarios > num_comentarios ){
                    $.each( data.observaciones, function( i, v ){
                        $('#chat').append( v );
                    });
                    num_comentarios = data.comentarios;
                }else{
                    for( var i = num_comentarios + 1; i < data.comentarios; i++ ){
                        $('#chat').append( data.observaciones[i] );
                    }
                }
                $("#chat").scrollTop($("#chat")[0].scrollHeight);
            });
        }

        function cargarConversacionDepartamento() { /** INICIO FECHA: 27-FEBRERO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
            $.post(url + "Consultar/getConversacionDepartamento", {
                idsolicitud: <?= $idsolicitud ?>
            }).done(function(data) {
                data = JSON.parse(data);
                
                // Limpiar el chat antes de agregar nuevos mensajes
                $('#chatDep').empty();

                $.each(data.observaciones, function(i, v) {
                    $('#chatDep').append(v);
                });

                $("#chatDep").scrollTop($("#chatDep")[0].scrollHeight);
            });
        } /** FIN FECHA: 27-FEBRERO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/

        function cambiar_factura(pos){
            $("#met_pago_fac b").text(facturas[pos].metodo_pago);
            $("#folio_fac b").text(facturas[pos].folio);
            $("#uuid_fac b").text(facturas[pos].uuid);
            $("#descripcion_fac").html("<b>DESCRIPCIÓN: </b>"+(facturas[pos].descripcion!=''?facturas[pos].descripcion:"---"));
            $("#observaciones_fac").html("<b>OBSERVACIONES: </b>"+(facturas[pos].observaciones!=''?facturas[pos].observaciones:"---"));
            $("#total_fac b").text("$"+formatMoney(facturas[pos].total));
            
            $("#version_fac b").text(facturas_xml[pos].version!=null?facturas_xml[pos].version[0]:"---");
            $("#rfcrec_fac b").text(facturas_xml[pos].rfcrecep!=null?facturas_xml[pos].rfcrecep[0]:"---");
            $("#rfcemi_fac b").text(facturas_xml[pos].rfcemisor!=null?facturas_xml[pos].rfcemisor[0]:"---");
            $("#nombrerec_fac b").text(facturas_xml[pos].nomrec!=null?facturas_xml[pos].nomrec[0]:"---");
            $("#nombreemi_fac b").text(facturas_xml[pos].nomemi!=null?facturas_xml[pos].nomemi[0]:"---");
            $("#tipo_comprobante_fac b").text(facturas_xml[pos].TipoDeComprobante=!null? facturas_xml[pos].TipoDeComprobante[0]: "---");
            $("#condi_pago_fac b").text((facturas_xml[pos].condpago!=null)?facturas_xml[pos].condpago[0]:"---" );
            $("#fecha_fac b").text(facturas_xml[pos].fecha!=null?facturas_xml[pos].fecha[0]:"---");
            $("#fpago_fac b").text(facturas_xml[pos].formpago!=null?facturas_xml[pos].formpago[0]:"---");
            $("#modopagoprov_fac b").text(facturas[pos].formapago);
            $("#cfdi_fac b").text(facturas_xml[pos].usocfdi!=null?facturas_xml[pos].usocfdi[0]:"---");
            $("#tipocambio_fac b").text(facturas_xml[pos].TipoCambio!=null?facturas_xml[pos].TipoCambio[0]:"---");
            $("#tipo_comprobante_fac2 b").text(facturas_xml[pos].TipoDeComprobante!=null?facturas_xml[pos].TipoDeComprobante[0]:"---");
            $("#importeret_fac b").text("$"+(facturas_xml[pos].TotImpRet!=null?formatMoney(facturas_xml[pos].TotImpRet[0]):"0.00"));
            $("#importetras_fac b").text("$"+(facturas_xml[pos].TotImpTras!=null?formatMoney(facturas_xml[pos].TotImpTras[0]):"0.00"));
            $("#subtotal_fac b").text("$"+(facturas_xml[pos].SubTotal!=null?formatMoney(facturas_xml[pos].SubTotal[0]):formatMoney(0) ));
            $("#descuento_fac b").text("$"+( facturas_xml[pos].Descuento!=null?formatMoney(facturas_xml[pos].Descuento[0]):formatMoney(0) ));
            
            $("#uuidr_fac b").html("");
            for(var j=0;j<facturas_xml[pos].uuidR.length;j++){
                var js = (facturas_xml[pos].uuidR[0]);
                $("#uuidr_fac b").append(js["@attributes"].UUID+" ");
            }
            
            $("#facturaarch_fac b").html('<a href="../../UPLOADS/XMLS/'+facturas[$("#sel_factura").val()].nombre_archivo+'" target="_blank" download="">'+facturas[$("#sel_factura").val()].nombre_archivo+'</a>');
            $(".conceptos").show();
        }

        $("#agregar_comentario").click(function () { /** INICIO FECHA: 27-FEBRERO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
            let comentario = $("#comentario").val().trim(); // Eliminamos espacios en blanco

            // Verificamos si el comentario está vacío
            if (comentario === "") {
                // Mostramos el mensaje de error debajo del input
                if ($("#error-comentario").length === 0) {
                    $("#comentario").after('<small id="error-comentario" style="color: red;">Por favor, ingrese un comentario.</small>');
                }
                return; // Evita que se continúe con la petición AJAX
            } else {
                $("#error-comentario").remove(); // Eliminamos el mensaje de error si ya escribió algo
            }

            $.post( url + "Consultar/agregar_comentario", { 
                idsolicitud : <?= $idsolicitud ?>
                , comentario : comentario
            }).done( function( data ){
                data = JSON.parse( data );
                $("#comentario").val("");
                $('#chat').append(data.mensaje);
                $("#chat").scrollTop($("#chat")[0].scrollHeight);
                num_comentarios += num_comentarios;
            }).then(function() {
                cargarConversacionDepartamento(<?= $idsolicitud ?>); // Asegura que se ejecuta después del comentario
            });
        }); /** FIN FECHA: 27-FEBRERO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/

        function documentos(idsolicitud) {
            $.post(url + "Soporte/get_docSoporte", {
                idsolicitud: idsolicitud
            }).done(function(data) {
                data = JSON.parse(data);
                if(data.doc){
                    $(".s_file").hide();
                    $("#detalle").text('')
                    $("#detalle").append(agregarBotones({
                                tipoDocumento: 0, 
                                idSolicitud: data.doc.idsolicitud,
                                idDocumento: data.doc.iddocumentos_solicitud,
                                nombreArchivo: 'Test',
                                rutaArchivo: data.doc.ruta,
                                nombreHeader: "- " + data.doc.documento,
                                mostrarModalEliminacion: data.mostrarModalEliminacion === 'S',
                                agregarBotonEliminar: data.mostrarBotonEliminacion === 'S'
                            }))

                }else{
                    if(data.mostrarBotonEliminacion === 'S'){
                        $(".s_file").text('')
                        $(".s_file").append(agregarUpload({
                            tipoDocumento: 0,
                            idSolicitud: idsolicitud,
                            proyecto: 'Test',
                            mostrarModalEliminacion: data.mostrarModalEliminacion === 'S',
                            agregarBotonEliminar: data.mostrarBotonEliminacion === 'S'
                        }))
                    }else{
                       $(".s_file").hide();
                       $("#detalle").append("<div class='col-lg-12'><h5>SIN DOCUMENTOS </h5></div>");
                    }
                }
            });
        }
    

       

        function documentos_pdf(idsolicitud) {
            $.post(url + "Soporte/get_docPDF", {
                idsolicitud: idsolicitud
            }).done(function(data) {
                data = JSON.parse(data);

                if(data.respuesta === 'Error, datos no encontrados según el ID Solicitud.') return;

                $("#gastoMayor").hide();
                $('#pdfGPND').hide();
                $('#pdfComer').hide();
                $('#pdfAutViaticos').hide();
                
                if(data[0].documentosRequired.mostrarBotonEliminacion === 'S'){
                    if(data[0].documentosRequired.archivoCajaChica === 'S') {
                        $("#gastoMayor").show();
                        $(`.pdfArea_2`).html("");
                        $(`.pdfArea_2`).append(agregarUpload({
                                tipoDocumento: 2,
                                idSolicitud: idsolicitud,
                                proyecto: data[0].documentosRequired.proyecto,
                                mostrarModalEliminacion: data[0].documentosRequired.mostrarModalEliminacion === 'S',
                                agregarBotonEliminar: data[0].documentosRequired.mostrarBotonEliminacion === 'S'
                            }))
                    }
                    if(data[0].documentosRequired.archivoProveedor === 'S') {
                        $('#pdfGPND').show()
                        $(`.pdfArea_6`).html("");
                        $(`.pdfArea_6`).append(agregarUpload({
                                tipoDocumento: 6,
                                idSolicitud: idsolicitud,
                                proyecto: data[0].documentosRequired.proyecto,
                                mostrarModalEliminacion: data[0].documentosRequired.mostrarModalEliminacion === 'S',
                                agregarBotonEliminar: data[0].documentosRequired.mostrarBotonEliminacion === 'S'
                            }))
                    }
                    if(data[0].documentosRequired.archivoComercializacion === 'S') {
                        $('#pdfComer').show()
                        $(`.pdfArea_1`).html("");
                        $(`.pdfArea_1`).append(agregarUpload({
                                tipoDocumento: 1,
                                idSolicitud: idsolicitud,
                                proyecto: data[0].documentosRequired.proyecto,
                                mostrarModalEliminacion: data[0].documentosRequired.mostrarModalEliminacion === 'S',
                                agregarBotonEliminar: data[0].documentosRequired.mostrarBotonEliminacion === 'S'
                            }))
                    }
                    if(data[0].documentosRequired.archivoViaticos === 'S') {
                        $("#pdfAutViaticos").show();
                        $(`.pdfArea_3`).html("");
                        $(`.pdfArea_3`).append(agregarUpload({
                                tipoDocumento: 3,
                                idSolicitud: idsolicitud,
                                proyecto: data[0].documentosRequired.proyecto,
                                mostrarModalEliminacion: data[0].documentosRequired.mostrarModalEliminacion === 'S',
                                agregarBotonEliminar: data[0].documentosRequired.mostrarBotonEliminacion === 'S'
                            }))
                    }
                }

                data[0].archivosInfo.forEach((file) =>{
                    
                    user1 = file.iduser_act;
                    user2 = file.iduser_crea;
                    fileName = file.datos.tipo_doc === "2" ? '- Autorización DG' : file.nom_archivo;
                    $(`.pdfArea_${file.datos.tipo_doc}`).html("");
                    


                    if(file.datos){
                        if(file.datos.tipo_doc === "2") $("#gastoMayor").show();
                        if(file.datos.tipo_doc === "1") $('#pdfComer').show();
                        if(file.datos.tipo_doc === "6") $('#pdfGPND').show();
                        if(file.datos.tipo_doc === "3") $('#pdfAutViaticos').show()

                        $(`.pdfArea_${file.datos.tipo_doc}`)
                            .append(
                                agregarBotones({
                                tipoDocumento: file.datos.tipo_doc,
                                idSolicitud: file.datos.idSolicitud,
                                idDocumento: file.datos.idDocumento,
                                nombreHeader: fileName,
                                nombreArchivo: file.nom_archivo,
                                rutaArchivo: file.ruta,
                                mostrarModalEliminacion: data[0].documentosRequired.mostrarModalEliminacion === 'S',
                                agregarBotonEliminar:   data[0].documentosRequired.mostrarBotonEliminacion === 'S'
                            })
                        )

                    }
                });
            });
        }

        function borrar_doc(idDoc, idSol, tipoDoc = 0, rutaArchivo, nombreArchivo, mostrarModalEliminacion, agregarBotonEliminar, boton){
            /**
             * Se comenta lo siguiente ya que por tiempos no se pudo implementar el modal de eliminacion
             *  $('#modal-alert').modal('toggle');
                document.getElementById('modal-del-doc').style.marginBottom = '0';
                document.getElementById('modal-del-doc').style.marginTop = '0';
            */
            var proyecto = "<?= $datos_solicitud->proyecto ?>";
            proyecto = proyecto.toString();
            var myModal = $(`<div class="modal fade modal-alertas" id="modal_justificacion" role="dialog" style="display: none;">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header" style="background: #E85332; color: #fff;">
                                            <button type="button" class="close" data-dismiss="modal">×</button>
                                            <h4 class="modal-title">ELIMINAR ARCHIVO</h4>
                                        </div>
                                        <div class="modal-body">
                                            <form id="form_acciones_sol" action="#" method="post">
                                                <div class="inputs_hidden">

                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-12 form-group"> 
                                                        <label>COMENTARIO<span class="text-danger">*</span>:</label>
                                                        <textarea class="form-control" name="comentario"></textarea>
                                                        <label></label>
                                                    </div>
                                                </div>
                                                <div class="row ">
                                                    <div class="col-lg-12 col-lg-offset-2 btn-group">
                                                        <button type="submit" class="btn btn-light" data-dismiss="modal">CANCELAR</button>
                                                        <button type="submit" class="btn btn-danger">ELIMINAR</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>`);

            if(mostrarModalEliminacion){
                button = myModal.children('div').eq(0).children('div').eq(0).children('div').eq(1).children('form').eq(0).children('div').eq(2).children('div').eq(0).children('button').eq(1)
                $('body').append(myModal)
                myModal.modal('toggle') 
                textArea = myModal.children('div').eq(0).children('div').eq(0).children('div').eq(1).children('form').eq(0).children('div').eq(1).children('div').eq(0).children('textarea').eq(0)
                button.click((e)=>{
                    e.preventDefault()
                    if(!textArea.val()) {
                        textArea.parent().css('color', 'red')
                        textArea.parent().children('label').eq(1).text('')
                        textArea.parent().children('label').eq(1).text('REQUERIDO')
                    }
                    else{
                        button.attr('data-dismiss', 'modal');
                        
                        $.post(url + "Soporte/borrar_doc", {
                            idsol: idSol,
                            iddoc: idDoc,
                            tipodoc: tipoDoc,
                            justificacion: textArea.val(),
                            nombreArchivo: nombreArchivo
                        }).done(function(data) {
                            if (data) {
                                if(tipoDoc == 0){
                                    $(".s_file").text('');
                                    $(".s_file").append(agregarUpload({
                                        tipoDocumento: tipoDoc,
                                        idSolicitud: idSol,
                                        proyecto: proyecto,
                                        mostrarModalEliminacion: mostrarModalEliminacion,
                                        agregarBotonEliminar: agregarBotonEliminar
                                    }));
                                    $(".s_file").show();
                                    $("#detalle").text('');
                                }
                                else{
                                    $(`.pdfArea_${tipoDoc}`).text('')
                                    $(`.pdfArea_${tipoDoc}`).append(agregarUpload({
                                        tipoDocumento: tipoDoc,
                                        idSolicitud: idSol,
                                        proyecto: proyecto,
                                        mostrarModalEliminacion: mostrarModalEliminacion,
                                        agregarBotonEliminar: agregarBotonEliminar
                                    }))

                                }

                            }
                        });
                    }
                });
            }
            else{
                $.post(url + "Soporte/borrar_doc", {
                    idsol: idSol,
                    iddoc: idDoc,
                    tipodoc: tipoDoc,
                    justificacion: '',
                    nombreArchivo: nombreArchivo
                }).done(function(data) {
                    if (data) {
                        if(tipoDoc == 0){
                            $(".s_file").text('');
                            $(".s_file").append(agregarUpload({
                                tipoDocumento: tipoDoc,
                                idSolicitud: idSol,
                                proyecto: proyecto,
                                mostrarModalEliminacion: mostrarModalEliminacion,
                                agregarBotonEliminar: agregarBotonEliminar
                            }));
                            $(".s_file").show();
                            $("#detalle").text('');
                        }
                        else{
                            $(`.pdfArea_${tipoDoc}`).text('')
                            $(`.pdfArea_${tipoDoc}`).append(agregarUpload({
                                tipoDocumento: tipoDoc,
                                idSolicitud: idSol,
                                proyecto: proyecto,
                                mostrarModalEliminacion: mostrarModalEliminacion,
                                agregarBotonEliminar: agregarBotonEliminar
                            }))

                        }

                    }
                });
            }
        }

        $("#soportefile").change(function(e) {   
            var file;
        if ((file = this.files[0])) {
            var sizeByte = this.files[0].size;
            var sizekiloBytes = parseInt(sizeByte / 1024);
                if(sizekiloBytes > $('#soportefile').attr('size')){
                    alert('El tamaño del archivo supera 10MB!');
                    $(this).val('');
                }
            }
        });

        function agregarBotones(params){
            if($.type(params.tipoDocumento) !== 'string' && $.type(params.tipoDocumento) !== 'number') return;
            if($.type(params.idSolicitud) !== 'string' && $.type(params.idSolicitud) !== 'number') return;
            if($.type(params.idDocumento) !== 'string' && $.type(params.idDocumento) !== 'number') return;
            if($.type(params.nombreArchivo) !== 'string') return;
            if($.type(params.rutaArchivo) !== 'string') return;
            if($.type(params.nombreHeader) !== 'string' && !params.nombreHeader ) params.nombreHeader = params.nombreArchivo;
            if($.type(params.mostrarModalEliminacion) !== 'boolean') params.mostrarModalEliminacion = false;
            if($.type(params.agregarBotonEliminar) !== 'boolean') params.agregarBotonEliminar = false;

            button = params.agregarBotonEliminar ? $(`<button title='Borrar documento'
                                                        name= ${params.idDocumento}
                                                        class='btn btn-sm btn-danger' >
                                                            <i class='fas fa-trash-alt'></i>
                                                    </button>`):"";
            if(button instanceof $)
                button.click(function(e) {
                    pdf = $(this).parent().parent().parent().children('div').eq(2).children('div').eq(0)
                    a = $(this).parent().children('a').eq(1)
                    etq = $(this).parent().children('a').eq(1).children('i').eq(0)
                    
                    if(!pdf.is('[hidden]')){
                        etq.removeClass()
                        etq.addClass('fas fa-eye')
                        pdf.attr("hidden", true);
                        a.removeClass()
                        a.addClass('btn btn-sm btn-success')
                    }

                    borrar_doc(params.idDocumento, params.idSolicitud, params.tipoDocumento, params.rutaArchivo, params.nombreArchivo, params.mostrarModalEliminacion, params.agregarBotonEliminar, $(this))
                })
            
            $descargarArchivo =  params.tipoDocumento == 0 ? `<a href='${params.rutaArchivo}' target='_blank' class='btn btn-sm btn-primary archivos'>` : `<a href='#' class='btn btn-sm btn-primary' id="descargarpdf" data-nom-arch = ${params.nombreArchivo}>`;
 
            docContainer = $(`<div>
                                <div class='col-lg-9'>
                                <h5> ${params.nombreHeader} </h5>
                                </div>
                                <div class='col-lg-3'>
                                    <div class='btn-group' role='group' aria-label='Basic example'>
                                        ${$descargarArchivo}
                                            <i class='fas fa-download'></i>
                                        </a>
                                        <a href='#' class='btn btn-sm btn-success view_button_${params.tipoDocumento}' id="viewpdf" data-nom-arch = ${params.nombreArchivo}>
                                            <i class='fas fa-eye'></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="container-fluid">
                                    <div class="pdf-container_${params.tipoDocumento}" id="pdf-container" hidden></div>
                                </div>
                            </div>`);
            if(params.tipoDocumento == 0) $("#detalle").text('')
            if(params.tipoDocumento != 0)
                docContainer.children('div').eq(1).children('div').eq(0).children('a').eq(0).click(function (e){
                   e.preventDefault();
                   var path = window.location.pathname.split('/');
                   path = path.filter(function (elemento) {
                        return elemento !== '';
                    });
                   var index = path.indexOf('index.php') + 1;
                   var dots = ''
                   var data = {
                    nombreArchivo: params.nombreArchivo,
                    tipoDocumento: params.tipoDocumento
                   }
                   var url = 'Soporte/descargaPDF/' + JSON.stringify(data);
                   if (index !== 0) {
                       dots = '../'.repeat((path.length - 1) - index);    
                   }
                   window.open((dots ? dots : '') + url, '_blank');
                })

            docContainer.children('div').eq(1).children('div').eq(0).append(button);

            docContainer.find(`#viewpdf`).click(function(e){
                e.preventDefault();
                etqView = $(this).children('i').eq(0)
                pdfContainer = $(this).parent().parent().parent().children('div').eq(2).children('div').eq(0)
                if (pdfContainer.is('[hidden]')) {
                    etqView.removeClass()
                    etqView.addClass('fa fa-eye-slash')
                    pdfContainer.removeAttr('hidden')
                    $(this).removeClass();
                    $(this).addClass('btn btn-sm btn btn btn-danger');
                }else{
                    etqView.removeClass()
                    etqView.addClass('fas fa-eye')
                    pdfContainer.attr("hidden", true);
                    $(this).removeClass()
                    $(this).addClass('btn btn-sm btn-success')
                }
                
                let embedElement = $('<embed />');
                
                if(params.tipoDocumento == 0)
                    fetch(params.rutaArchivo)
                        .then(response => {
                            if (!response.ok) {
                            throw new Error(`Error al descargar el archivo: ${response.statusText}`);
                            }

                            response.headers['Content-Disposition'] = "inline; filename=name.extension";
                            return response.blob();
                        })
                        .then(blob => {
                            embedElement.attr({
                                "src": URL.createObjectURL(blob),
                                "type": 'application/pdf',
                                "width": '100%',
                                "height": '100%',
                                "innerHTML": ''
                                });
                            pdfContainer.append(embedElement)

                        })
                        .catch(error => {
                            console.error(error);
                        });
                else{
                    let pdfUrl = (window.location.host == 'localhost' ? 'http://' : 'https://') + window.location.host + '/' + params.rutaArchivo + params.nombreArchivo;
                    embedElement.attr({
                        "src": pdfUrl,
                        "type": 'application/pdf',
                        "width": '100%',
                        "height": '100%',
                        "innerHTML": ''
                        });
                    pdfContainer.append(embedElement)
                }
            })
            
            return docContainer;

        }


        function subir(params){
            if($.type(params.tipoDocumento) !== 'string' && $.type(params.tipoDocumento) !== 'number') return;
            if($.type(params.idSolicitud) !== 'string' && $.type(params.idSolicitud) !== 'number') return;
            if($.type(params.nomenclatura) !== 'string') return;
            if($.type(params.proyecto) !== 'string') return;
            if($.type(params.movimiento) !== 'string') return;
            if($.type(params.mostrarModalEliminacion) === 'bool') return;
            if($.type(params.agregarBotonEliminar) === 'bool') return;
            if(!params.boton) return;

            var input = params.boton.parent().parent().children('input').eq(0)
            input.parent().parent().children('label').eq(0).remove()
            if(!input.val()){
                input.parent().parent().append('<label for="proyecto"><b>AGREGA UN ARCHIVO</b><span class="text-danger">*</span></label>')
                return;
            }

            var data = new FormData();
            data.append('tipoDocumento', params.tipoDocumento)
            data.append('idSolicitud', params.idSolicitud)
            data.append('nomenclatura', params.nomenclatura)
            data.append('proyecto', params.proyecto)
            data.append('pdfFile', input[0].files[0])
            data.append('movimiento', params.movimiento)
            $.ajax({
                url: url + 'Soporte/subir_archivos_otros', // Ruta al servidor o servicio para obtener datos
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                method: 'POST',
                type: 'POST',
                data: data, // Enviar la nueva fecha al servidor
                success: function(result) {
                    fileName = params.tipoDocumento == "2" ? '- Autorización DG' : result.pdfFileGPND;

                    $(`.pdfArea_${params.tipoDocumento}`).text('')
                    $(`.pdfArea_${params.tipoDocumento}`)
                        .append(
                            agregarBotones({
                            tipoDocumento: params.tipoDocumento,
                            idSolicitud: params.idSolicitud,
                            idDocumento: result.idDocumentoPdfGPND,
                            nombreHeader: fileName,
                            nombreArchivo: result.pdfFileGPND,
                            rutaArchivo: result.ruta,
                            mostrarModalEliminacion: params.mostrarModalEliminacion,
                            agregarBotonEliminar: params.agregarBotonEliminar
                            })
                    )
                }
            });

        }

        function agregarUpload(params){
            if($.type(params.tipoDocumento) !== 'string' && $.type(params.tipoDocumento) !== 'number') return;
            if($.type(params.idSolicitud) !== 'string' && $.type(params.idSolicitud) !== 'number') return;
            if($.type(params.proyecto) !== 'string') return;
            if($.type(params.mostrarModalEliminacion) === 'bool') return;
            if($.type(params.agregarBotonEliminar) === 'bool') return;


            var dataTipoDocumento = {
                1:{
                    nomenclatura: 'COME',
                    movimiento: 'Se agregó archivo PDF'
                },
                6:{
                    nomenclatura: 'GPND',
                    movimiento: 'Se agregó archivo PDF (Gasto a Proveedor No Deducible)'
                },
                2:{
                    nomenclatura: 'CCGM',
                    movimiento: 'Se agregó archivo Caja Chica (Gasto mayor a $5000)'
                },
                3:{
                    nomenclatura: 'AUTV',
                    movimiento: 'Se agregó archivo autorización de viáticos'
                }
            }
            


            var form = params.tipoDocumento != 0 ? $(`<div class="col-lg-12">
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="file" name="pdfFile" id="pdfFile_${params.tipoDocumento}" class="form-control" size="9216" required>
                                    <div class="input-group-btn">
                                        <button class="btn btn-success subir_archivos " type="button"><i class="fas fa-upload"></i>SUBIR</button>
                                    </div>
                                </div>
                            </div>
                        </div>`) : $(`<div>
                        <h5><b>CARGAR DOCUMENTO</b></h5>
                                <div class="input-group">
                                    <input type="file" name="soportefile"  id="soportefile" class="form-control">
                                    <div class="input-group-btn">
                                        <button class="btn btn-success" type="submit" ><i class="fas fa-upload"></i>SUBIR</button>
                                    </div>
                                </div>
                        </div>`) 


            
            form.find('.subir_archivos').click(function (e){
                e.preventDefault();
                subir({
                    tipoDocumento: params.tipoDocumento,
                    idSolicitud: params.idSolicitud,
                    nomenclatura: dataTipoDocumento[params.tipoDocumento].nomenclatura,
                    proyecto: params.proyecto,
                    movimiento: dataTipoDocumento[params.tipoDocumento].movimiento,
                    boton: $(this),
                    mostrarModalEliminacion: params.mostrarModalEliminacion,
                    agregarBotonEliminar: params.agregarBotonEliminar
                })
            })

            if(params.tipoDocumento == 0){
                boton = form.children('div').eq(0).children('div').eq(0).children('button')
                input = form.children('div').eq(0).children('input').eq(0)
                boton.click(function(e) {
                    e.preventDefault();
                    var data = new FormData();
                    data.append('idsolicitud', params.idSolicitud)
                    data.append('soportefile', input[0].files[0])
                    if(!input.val()){
                        input.parent().parent().append('<label for="proyecto"><b>AGREGA UN ARCHIVO</b><span class="text-danger">*</span></label>')
                        return;
                    }
                    $.ajax({
                        url: url + 'Soporte/subirarchivo_s',
                        data: data,
                        cache: false,
                        contentType: false,
                        processData: false,
                        dataType: 'json',
                        method: 'POST',
                        type: 'POST', 
                        success: function(data){
                            if(data.resultado){
                                $(".s_file").hide();
                                $("#soportefile").val('');
                                $("#detalle").append(agregarBotones({
                                    tipoDocumento: 0, 
                                    idSolicitud: data.idsolicitud,
                                    idDocumento: data.iddocumentos_solicitud,
                                    nombreArchivo: 'Test',
                                    rutaArchivo: data.ruta,
                                    nombreHeader: ' - Soporte ',
                                    mostrarModalEliminacion: data.mostrarModalEliminacion === 'S',
                                    agregarBotonEliminar: data.mostrarBotonEliminacion === 'S'
                                }))
                            }
                        },error: function( data ){
                            alert("¡No fue posible cargar el documento!");
                        }
                    });
                })
            }
            
            return form;
            
        }
        $(document).on("click", ".descargar_zip", function(){
            var idsolicitud = $(this).data('value');
            $('#titulo_archivos_descargados').empty();
            $('#contenido-archivos').empty();

            $.ajax({
                url: url + 'Consultar/generarZip',
                type: 'POST',
                data: { idsolicitud: idsolicitud },
                success: function(response) {
                    var data = JSON.parse(response);
                    if (data.status === 200) {
                        $("#titulo_archivos_descargados").append(`ARCHIVOS DE LA SOLICITUD <b>#${idsolicitud}</b>`);

                        const tablaContenido = data.existentes.map(file => `
                             <tr>
                                <td><p>${file.archivo}</p></td>
                                <td><p><i class="fa ${file.estatus === 200 
                                                        ? 'fa-check-circle text-success' 
                                                        : 'fa-times-circle text-danger'}" aria-hidden="true" style="padding-right: 5px;"></i></p></td>
                            </tr>
                        `).join('');

                        $("#contenido-archivos").append(`
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>NOMBRE</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="archivos_xmls">${tablaContenido}</tbody>
                            </table>
                        `);

                        const descargaLink = document.createElement('a');
                        descargaLink.href = data.zip_data;
                        descargaLink.download = `xmls_${idsolicitud}_${data.date}.zip`;
                        descargaLink.click();

                        $("#archivos_descargados").modal();
                    } else {
                        console.error(`Error: ${data.mensaje || 'Desconocido'}`);
                    }
                },
                error: function(xhr, status, error) {
                    console.error(`Error en la solicitud AJAX: ${error}`);
                }
            });
        });

        
        $(document).on("click", ".descarga_multiple", function(){
            const idsolicitud = $(this).data('value');
            $.ajax({
                url: url + 'Consultar/generarUrlXMLS',
                type: 'POST',
                data: { idsolicitud: idsolicitud },
                success: function(response) {
                    const data = JSON.parse(response);

                    if (data.status === 200 && data.urls.length > 0) {
                        data.urls.forEach(file=>descargarArchivo(file.url));
                    }else{
                        console.error(data.mensaje || "NO SE ENCONTRARON URLS");
                        alert(data.mensaje || "NO SE ENCONTRARON URLS");
                    }
                },
                error: function (xhr, status, error) {
                    console.error(`Error en la solicitud AJAX: ${error}`);
                }
            });
        });

        function descargarArchivo(url) {
            const archivo = document.createElement('a');
            archivo.href = url;
            archivo.target = '_blank';  //  Abre en una pestaña
            archivo.download = url.substring(url.lastIndexOf('/') + 1);
            archivo.click();
        }
        $(document).on("click", "#modaleditDoc", function(){
            $('#modalDoc').modal('show');
            $('#modalDoc').css('overflow', 'hidden');
            $('#modalCentral').css('width', '100%');
        });
    </script>
    <script>
        $(document).ready(function(){
            $('[data-toggle="popover"]').popover(); 
        });
    </script>