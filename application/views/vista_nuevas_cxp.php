<?php
    require("head.php");
    require("menu_navegador.php");
?> 
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <div class="box">
                    <div class="box-header with-border">
                        <h3>SOLICITUDES NUEVAS </h3>
                    </div>
                    <div class="box-body">
                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                                <?php if (!in_array($this->session->userdata("inicio_sesion")['id'], [2681, 2409])): ?><!-- INICIO FECHA: 17-JUNIO-2025 | PANEL CP A USUARIOS ESPECIFICOS | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                                    <li class="active"><a id="autorizaciones-tab" data-toggle="tab" data-value="pagos_programados" href="#facturas_autorizar" role="tab" aria-controls="#facturas_autorizar" aria-selected="true">SOLICITUDES PROVEEDOR</a></li>
                                    <li><a id="profile-tab" data-toggle="tab" data-value="pagos_autoriza_dg_trans" href="#pagos_autoriza_dg_trans" role="tab" aria-controls="pagos_autoriza_dg_trans" aria-selected="false">SOLICITUDES CAJA CHICA</a></li>
                                    <li><a id="profile-tab" data-toggle="tab" data-value="pagos_autoriza_dg_fact" href="#pagos_autoriza_dg_fact" role="tab" aria-controls="pagos_autoriza_dg_fact" aria-selected="false">SOLICITUDES FACTORAJE</a></li>
                                <?php endif; ?><!-- FIN FECHA: 17-JUNIO-2025 | PANEL CP A USUARIOS ESPECIFICOS | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->                              

                                    <li><a id="profile-tab" data-toggle="tab" data-value="pagos_programados" href="#pagos_programados" role="tab" aria-controls="pagos_programados" aria-selected="false">PAGOS PROGRAMADOS</a></li>

                                <?php if (!in_array($this->session->userdata("inicio_sesion")['id'], [2681, 2409])): ?><!-- INICIO FECHA: 17-JUNIO-2025 | PANEL CP A USUARIOS ESPECIFICOS | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                                    <li><a id="profile-tab" data-toggle="tab" data-value="pagos_tdc" href="#pagos_autoriza_dg_trans" role="tab" aria-controls="pagos_autoriza_dg_trans" aria-selected="false">TARJETAS DE CREDITO</a></li>
                                    <li><a id="profile-tab" data-toggle="tab" data-value="pagos_reembolsos" href="#pagos_autoriza_dg_reembolsos" role="tab" aria-controls="pagos_autoriza_dg_reembolsos" aria-selected="false">SOLICITUDES REEMBOLSOS</a></li>
                                    <li><a id="profile-tab" data-toggle="tab" data-value="pagos_autoriza_dg_chNoddls" href="#pagos_autoriza_dg_chNoddls" role="tab" aria-controls="pagos_autoriza_dg_chNoddls" aria-selected="false">SOLICITUDES CAJA CHICA NO DEDUCIBLES</a></li>
                                    <li><a id="profile-tab" data-toggle="tab" data-value="pagos_viaticos" href="#pagos__autoriza_dg_viaticos" role="tab" aria-controls="pagos_autoriza_dg_viaticos" aria-selected="false">SOLICITUDES VIÁTICOS</a></li>
                                <?php endif; ?><!-- FIN FECHA: 17-JUNIO-2025 | PANEL CP A USUARIOS ESPECIFICOS | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                            </ul>
                        </div>
                        <div class="tab-content">
                            <div class="active tab-pane" id="facturas_autorizar">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <h4>&nbsp;SOLICITUDES NUEVAS PROVEEDOR<i class="far fa-question-circle faq" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" title="Autorizaciones" data-content="Estas son todas las solicitudes de pago a proveedores que han pasado por la primer validación de Dirección General." data-placement="right"></i><label>&nbsp;Total: $<input style="border-bottom: none; border-top: none; border-right: none;  border-left: none; background: white;" disabled="disabled" readonly="readonly" type="text"  name="myText_1" id="myText_1"></label></h4>
                                        <table class="table table-responsive table-bordered table-striped table-hover" id="tabla_autorizaciones_proveedores" name="tabla_autorizaciones_proveedores">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th style="font-size: .8em"></th>
                                                    <th style="font-size: .8em">#</th>
                                                    <th style="font-size: .8em">FOLIO</th>
                                                    <th style="font-size: .8em">PROVEEDOR</th>
                                                    <th style="font-size: .8em">RESPONSABLE</th>
                                                    <th style="font-size: .8em">CANTIDAD</th>
                                                    <th style="font-size: .8em">FECHA</th>
                                                    <th style="font-size: .8em">DEPTO</th>
                                                    <th style="font-size: .8em">EMPRESA</th>
                                                    <th style="font-size: .8em">FECHA CAP</th>
                                                    <th style="font-size: .8em">FACT</th>
                                                    <th style="font-size: .8em"></th> 
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="pagos_autoriza_dg_trans">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <h4>PENDIENTE POR AUTORIZAR<i class="far fa-question-circle faq" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" title="Autorizaciones" data-placement="right"></i><label>&nbsp;Total: $<input style="border-bottom: none; border-top: none; border-right: none;  border-left: none; background: white;" disabled="disabled" readonly="readonly" type="text"  name="myText_2" id="myText_2"></label></h4></br-->
                                        <table class="table table-responsive table-bordered table-striped table-hover" id="tabla_autorizaciones_caja_chica" name="tabla_autorizaciones_caja_chica">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th style="font-size: .8em"></th>                      <!-- COLUMNA[ 0 ] TABLE, EXPORTAR -->
                                                    <th style="font-size: .8em">RESPONSABLE</th>           <!-- COLUMNA[ 1 ] TABLE, EXPORTAR -->
                                                    <th style="font-size: .8em">PERTENECE REEMBOLSO</th>   <!-- COLUMNA[ 2 ] EXPORTAR CCH    --> <!-- INICIO FECHA: 29-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                                                    <th style="font-size: .8em">EMPRESA</th>               <!-- COLUMNA[ 3 ] TABLE, EXPORTAR -->
                                                    <th style="font-size: .8em">FECHA</th>                 <!-- COLUMNA[ 4 ] TABLE, EXPORTAR -->
                                                    <th style="font-size: .8em">TOTAL</th>                 <!-- COLUMNA[ 5 ] TABLE, EXPORTAR -->
                                                    <th style="font-size: .8em">DEPARTAMENTO</th>          <!-- COLUMNA[ 6 ] TABLE, EXPORTAR -->
                                                    <th style="font-size: .8em">MONEDA</th>                <!-- COLUMNA[ 7 ] TABLE           -->
                                                    <th style="font-size: .8em">TITULAR TARJETA</th>       <!-- COLUMNA[ 8 ] EXPORTAR TDC    --><!-- Cambios TDC Reportes | FECHA: 17-Abril-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div><!--End solicitudes finalizadas-->  
							<div class="tab-pane fade" id="pagos_autoriza_dg_fact">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <h4>SOLICITUDES NUEVAS FACTORAJE<i class="far fa-question-circle faq" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" title="Autorizaciones" data-placement="right"></i><label>&nbsp;Total: $<input style="border-bottom: none; border-top: none; border-right: none;  border-left: none; background: white;" disabled="disabled" readonly="readonly" type="text"  name="myText_3" id="myText_3"></label></h4><BR>
                                        <table class="table table-responsive table-bordered table-striped table-hover" id="tabla_autorizaciones_fact" name="tabla_autorizaciones_fact">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th style="font-size: .8em"></th>
                                                    <th style="font-size: .8em"># SOLICITUD</th>
                                                    <th style="font-size: .8em">FOLIO</th>
                                                    <th style="font-size: .8em">PROVEEDOR</th>
                                                    <th style="font-size: .8em">RESPONSABLE</th>
                                                    <th style="font-size: .8em">CANTIDAD</th>
                                                    <th style="font-size: .8em">MÉTODO</th>
                                                    <th style="font-size: .8em">FECHA</th>
                                                    <th style="font-size: .8em">DEPTO</th>
                                                    <th style="font-size: .8em">EMPRESA</th>
                                                    <th style="font-size: .8em">FECHA CAP</th>
                                                    <th style="font-size: .8em"></th> 
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div><!--End solicitudes finalizadas-->
                            <div class="tab-pane fade" id="pagos_programados">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <h4>PAGOS PROGRAMADOS <i class="far fa-question-circle faq" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" title="Autorizaciones" data-content="Solicitudes que involucran un fecha especifica de pago. Pueden tener una fecha inicial o son indeterminados." data-placement="right"></i><label>&nbsp;| TOTAL: $<input style="border-bottom: none; border-top: none; border-right: none;  border-left: none; background: white;" disabled="disabled" readonly="readonly" type="text" id="tprogramado"></label></h4> <!-- FECHA: 17-JUNIO-2025 | PANEL CP A USUARIOS ESPECIFICOS | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                                        <table class="table table-responsive table-bordered table-striped table-hover" id="tabla_pagos_programados">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th style="font-size: .8em"></th>
                                                    <th style="font-size: .8em">#</th>
                                                    <th style="font-size: .8em">PERIODOS</th>
                                                    <th style="font-size: .8em">PROVEEDOR</th>
                                                    <th style="font-size: .8em">TOTAL CUOTA</th>
                                                    <th style="font-size: .8em">FORMA PAGO</th>
                                                    <th style="font-size: .8em">CUOTA</th>
                                                    <th style="font-size: .8em">TOTAL PARCIAL</th>
                                                    <th style="font-size: .8em">INTERÉS TOTAL</th>
                                                    <th style="font-size: .8em">TOTAL PAGADO</th>
                                                    <th style="font-size: .8em"># PAGO</th>
                                                    <th style="font-size: .8em">FECHA CAPTURA</th>
                                                    <th style="font-size: .8em">FEC TERMINO</th>
                                                    <th style="font-size: .8em">PRX FECHA</th>
                                                    <th style="font-size: .8em">EMPRESA</th> 
                                                    <th style="font-size: .8em">ESTATUS</th> 
                                                    <th style="font-size: .8em"></th> 
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div><!--End solicitudes finalizadas--> 
							<div class="tab-pane fade" id="pagos_autoriza_dg_chNoddls">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <h4>PENDIENTE POR AUTORIZAR<i class="far fa-question-circle faq" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" title="Autorizaciones" data-placement="right"></i><label>&nbsp;Total: $<input style="border-bottom: none; border-top: none; border-right: none;  border-left: none; background: white;" disabled="disabled" readonly="readonly" type="text"  name="reembolsoTxt" id="cajaChicaNDDLSTxt"></label></h4></br-->
                                        <table class="table table-responsive table-bordered table-striped table-hover" id="tabla_autorizaciones_cajaChicaNoddls" name="tabla_autorizaciones_cajaChicaNoddls">
                                            <thead class="thead-dark">
                                            <tr>
                                                <th style="font-size: .8em"></th>
                                                <th style="font-size: .8em">RESPONSABLE</th>
                                                <th style="font-size: .8em">EMPRESA</th>
                                                <th style="font-size: .8em">FECHA</th>
                                                <th style="font-size: .8em">TOTAL</th>
                                                <th style="font-size: .8em">DEPARTAMENTO</th>

                                            </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div><!--End solicitudes finalizadas-->
                            <div class="tab-pane fade" id="pagos_autoriza_dg_reembolsos">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <h4>PENDIENTE POR AUTORIZAR<i class="far fa-question-circle faq" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" title="Autorizaciones" data-placement="right"></i><label>&nbsp;Total: $<input style="border-bottom: none; border-top: none; border-right: none;  border-left: none; background: white;" disabled="disabled" readonly="readonly" type="text"  name="reembolsoTxt" id="reembolsoTxt"></label></h4></br-->
                                        <table class="table table-responsive table-bordered table-striped table-hover" id="tabla_autorizaciones_reembolsos" name="tabla_autorizaciones_reembolsos">
                                            <thead class="thead-dark">
                                            <tr>
                                                <th style="font-size: .8em"></th>
                                                <th style="font-size: .8em">RESPONSABLE</th>
                                                <th style="font-size: .8em">EMPRESA</th>
                                                <th style="font-size: .8em">FECHA</th>
                                                <th style="font-size: .8em">TOTAL</th>
                                                <th style="font-size: .8em">DEPARTAMENTO</th>

											</tr>
											</thead>
										</table>
									</div>
								</div>
							</div><!--End solicitudes finalizadas-->
							<div class="tab-pane fade" id="pagos__autoriza_dg_viaticos">
								<div class="row">
									<div class="col-lg-12">
										<h4>PENDIENTE POR AUTORIZAR<i class="far fa-question-circle faq" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" title="Autorizaciones" data-placement="right"></i><label>&nbsp;Total: $<input style="border-bottom: none; border-top: none; border-right: none;  border-left: none; background: white;" disabled="disabled" readonly="readonly" type="text"  name="txtViaticos" id="txtViaticos"></label></h4></br>
										<table class="table table-responsive table-bordered table-striped table-hover" id="tabla_autorizaciones_viaticos" name="tabla_autorizaciones_viaticos">
											<thead class="thead-dark">
											<tr>
												<th style="font-size: .8em"></th>
												<th style="font-size: .8em">RESPONSABLE</th>
                                                <th style="font-size: .8em">PERTENECE REEMBOLSO</th>   <!-- COLUMNA[ 2 ] --> <!-- INICIO FECHA: 12-JUNIO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
												<th style="font-size: .8em">EMPRESA</th>
												<th style="font-size: .8em">FECHA</th>
												<th style="font-size: .8em">TOTAL</th>
												<th style="font-size: .8em">DEPARTAMENTO</th>

											</tr>
											</thead>
										</table>
									</div>
								</div>
                            </div>
                            <!--End solicitudes finalizadas-->
						</div>
                    </div><!--End tab content-->
                </div><!--end box-->
            </div>
        </div>
    </div>
</div>

<div class="modal fade modal-alertas" id="myModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: #00A65A; color: #fff">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">CONFIRMAR SOLICITUD</h4>
            </div>  
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <h5>¿Esta seguro de aceptar la solicitud?</h5>        
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <button type='button' class='btn btn-success' id="aceptar_solicitud">ACEPTAR</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">CANCELAR</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade modal-alertas" id="aceptarSolReembolso" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header" style="background: #00A65A; color: #fff">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">CONFIRMAR SOLICITUD</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-lg-12">
						<h5>¿Esta seguro de aceptar la solicitud?</h5>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-12 text-center">
						<button type='button' class='btn btn-success' id="aceptar_solicitud_reembolso">ACEPTAR</button>
						<button type="button" class="btn btn-danger" data-dismiss="modal">CANCELAR</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade modal-alertas" id="aceptarSolViatico" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header" style="background: #00A65A; color: #fff">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">CONFIRMAR SOLICITUD</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-lg-12">
						<h5>¿Esta seguro de aceptar la solicitud?</h5>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-12 text-center">
						<button type='button' class='btn btn-success' id="aceptar_solicitud_viaticos">ACEPTAR</button>
						<button type="button" class="btn btn-danger" data-dismiss="modal">CANCELAR</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="gastoNodeducible" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: #00A65A; color: #fff">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">AGREGAR GASTO NO DEDUCIBLE</h4>
            </div>  
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <form id="gNodeducible" action="#">
                            <div class="col-lg-4 form-group">
                                <label for="proyecto">PROYECTO</label>
                                <select class="listado_proyectos form-control select2" name="proyecto" style="width: 100%;" id="proyecto" required></select>
                            </div>
                            <div class="col-lg-4 form-group">
                                <label for="total">TOTAL<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="total" name="total" placeholder="Total" value="" required>
                            </div>

                            <div class="col-lg-4 form-group">
                                <label for="total">MONEDA<span class="text-danger">*</span></label>
                                <select class="form-control" id="moneda" name="moneda" required>
                                    <option value="MXN" data-value="MXN">MXN</option>
                                    <option value="USD" data-value="USD">USD</option>
                                    <option value="CAD" data-value="CAD">CAD</option>
                                    <option value="EUR" data-value="EUR">EUR</option>
                                    <option value="UDIS" data-value="UDIS">UDIS</option>
                                </select>
                            </div>

                            <div class="col-lg-4 form-group">
                                <label for="fecha">FECHA<span class="text-danger">*</span></label>
                                <input type="date" class="form-control" min='2019-04-01' id="fecha" name="fecha" placeholder="Fecha" value="" required>
                            </div>
                            <div class="col-lg-12 form-group">
                                <label for="solobs">JUSTIFICACIÓN<span class="text-danger">*</span> <i class="far fa-question-circle faq" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" title="Observaciones de la solicitud" data-content="En caso de ser necesario, se podrán especificar datos, comentarios u observaciones para cuentas por pagar, DG, DP o para la dirección del departamento solicitante." data-placement="right"></i></label>
                                <textarea class="form-control" id="solobs" name="solobs" placeholder="Observaciones de la solicitud" required></textarea>
                            </div>
                            <div class="col-lg-12 form-group"> 
                                <label for="metpag">RESPONSABLE</label>
                                    <select class="form-control select2" id="responsable_cc" name="responsable_cc" required></select>
                            </div>
                            <div class="col-lg-4 col-lg-offset-4">
                                <button type="submit" class="btn btn-success btn-block">GUARDAR</button>
                            </div>
                        </form>    
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade modal-alertas" id="modal_provisionar" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: #00A65A; color: #fff">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">PROVISIÓN</h4>
            </div>  
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <h5>¿Desea enviar esta solicitud a provisión?</h5>        
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <button type='button' class='btn btn-success' id="provisionar">Provisionar</button>
                        <button type="button" class="btn btn-danger" id="sin_provisionar">No provisionar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade modal-alertas" id="myModalcomentario1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"  style="background: #DD4B39; color: #fff;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">MOTIVO DE RECHAZO</h4>
            </div>
            <form method="post" id="infosol1">
                <div class="modal-body">
                    <div class="row">
                        <div class='form-group col-lg-12'>
                            <input type='text' class='form-control' placeholder='Describe motivo de rechazo.' name='observacion' id='observacion' required>
                        </div>
                    </div>
                    <div class="row">
                        <div class='form-group col-lg-12 text-center'>
                            <div class='btn-group'>
                                <button type='submit' class='btn btn-success'>ACEPTAR</button>
                                <button type="button" class="btn btn-danger" data-dismiss="modal">CANCELAR</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade modal-alertas" id="modalReembolso" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header"  style="background: #DD4B39; color: #fff;">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">MOTIVO DE RECHAZO R</h4>
			</div>
			<form method="post" id="infoReemForm">
				<div class="modal-body">
					<div class="row">
						<div class='form-group col-lg-12'>
							<input type='text' class='form-control' placeholder='Describe motivo de rechazo.' name='observacion' id='observacionReem' required>
						</div>
					</div>

					<div class="row">
						<div class='form-group col-lg-12 text-center'>
							<div class='btn-group'>
								<button type='submit' class='btn btn-success'>ACEPTAR</button>
								<button type="button" class="btn btn-danger" data-dismiss="modal">CANCELAR</button>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="modal fade modal-alertas" id="modalNodeducible" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header"  style="background: #DD4B39; color: #fff;">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">MOTIVO DE RECHAZO R</h4>
			</div>
			<form method="post" id="infoNoDeducibleForm">
				<div class="modal-body">
					<div class="row">
						<div class='form-group col-lg-12'>
							<input type='text' class='form-control' placeholder='Describe motivo de rechazo.' name='observacion' id='observacionNoDeducible' required>
						</div>
					</div>

					<div class="row">
						<div class='form-group col-lg-12 text-center'>
							<div class='btn-group'>
								<button type='submit' class='btn btn-success'>ACEPTAR</button>
								<button type="button" class="btn btn-danger" data-dismiss="modal">CANCELAR</button>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="modal fade modal-alertas" id="aceptarSolNoDeducible" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header" style="background: #00A65A; color: #fff">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">CONFIRMAR SOLICITUD</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-lg-12">
						<h5>¿Esta seguro de aceptar la solicitud?</h5>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-12 text-center">
						<button type='button' class='btn btn-success' id="aceptar_solicitud_nodeducible">ACEPTAR</button>
						<button type="button" class="btn btn-danger" data-dismiss="modal">CANCELAR</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade modal-alertas" id="modalViaticos" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header"  style="background: #DD4B39; color: #fff;">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">MOTIVO DE RECHAZO VIÁTICO</h4>
			</div>
			<form method="post" id="infoViaticoForm">
				<div class="modal-body">
					<div class="row">
						<div class='form-group col-lg-12'>
							<input type='text' class='form-control' placeholder='Describe motivo de rechazo.' name='observacion' id='observacionViatico' required>
						</div>
					</div>

					<div class="row">
						<div class='form-group col-lg-12 text-center'>
							<div class='btn-group'>
								<button type='submit' class='btn btn-success'>ACEPTAR</button>
								<button type="button" class="btn btn-danger" data-dismiss="modal">CANCELAR</button>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>

<div id="factura_complemento" class="modal fade modal-alertas" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background-color: black; color: white;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">COMPLEMENTO XML</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <form id="complemento_facturas">
                        <div class="col-lg-12 form-group">
                            <label>DOCUMENTO XML</label>                            
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="input-group">
                                        <input type="file" class="form-control" id="complemento" accept="text/xml" required>                                
                                        <div class="input-group-btn">
                                            <button type="submit" class="btn btn-danger cargar_complemento"><i class="fas fa-upload"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>                          
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade modal-alertas" id="modal_opciones" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-maroon">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">SELECCIONA OPCIÓN</h4>
            </div>  
            <!-- <form method="post" id="formulario_opciones"> </form> -->
                <div class="modal-body"></div>
           
            <div class="modal-footer"></div>
        </div>
    </div>
</div>

<div id="modal_cant_pprog" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header" style="background-color: green; color: white;">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">MODIFICA LOS DATOS DEL PAGO PROGRAMADO # <b></b></h4>
        </div>
        <form id="form_cant_pprog">
            <input type="hidden" name="idsolicitud" id="idsol_pprog">
            <div class="modal-body">
            <div class="row">
                <div class="col-md-6">
                <label for="fechaini_mod">Fecha inicio pago</label>
                <input type='text' class='form-control' name='fechaini' id='fechaini_mod' required autocomplete="off">
                </div>
                <div class="col-md-6">
                <label for="fechafin_mod">Fecha termino pago</label>
                <input type='text' class='form-control' name='fechafin' id='fechafin_mod' autocomplete="off">
                </div>
                <div class="col-md-6">
                <label for="cant_mod">Cantidad</label>
                <input type='text' class='form-control dinero' name='cantidad' id='cant_mod' required>
                </div>
                <div class="col-md-6 form-group">
                    <label for="per_pago">Periódo de pagos<span class="text-danger">*</span></label>
                    <select class="form-control" id="per_pago" name="per_pago" required="">
                        <option value="">Seleccione un opción</option>
                        <option value="7">SEMANAL</option>
                        <option value="1">MENSUALMENTE</option>
                        <option value="2">BIMESTRAL</option>
                        <option value="3">TRIMESTRAL</option>
                        <option value="4">CUATRIMESTRAL</option>
                        <option value="6">SEMESTRAL</option>
                    </select>
                </div>
                <div class="col-md-12">
                <label for="observaciones_mod">Observaciones</label>
                <textarea class="form-control" name="observaciones" id="observaciones_mod" rows="3" required style="resize:none"></textarea>
                </div>
            </div>
            </div>
            <div class="modal-footer">
                <div class="btn-group">
                <button type="submit" class="btn btn-success cargar_complemento">Actualizar</button>
                </div>
            </div>
        </form>
    </div>
  </div>
</div>
<!-- 
INICIO
Fecha : 12-Agosto-2025 @author Efrain Martinez Muñoz <programador.analista38@ciudadmaderas.com>
Se crean los modales para calcelar la solicitud  de proveedores, solicitudes caja chica, pagos programados, tarjetas de credito reembolso, caja chicano deducible y viáticos
-->
<div class="modal fade modal-alertas" id="myModalcomentarioCancelar" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"  style="background: #DD4B39; color: #fff;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">MOTIVO DE LA CANCELACIÓN</h4>
            </div>
            <form method="post" id="infosolCancelar">
                <div class="modal-body">
                    <div class="row">
                        <div class='form-group col-lg-12'>
                            <input type='text' class='form-control' placeholder='Describe motivo la cancelación.' name='observacionC' id='observacionC' required>
                        </div>
                    </div>
                    <div class="row">
                        <div class='form-group col-lg-12 text-center'>
                            <div class='btn-group'>
                                <button type='submit' class='btn btn-success'>ACEPTAR</button>
                                <button type="button" class="btn btn-danger" data-dismiss="modal">CANCELAR</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal para calcelar la solicitudes que se encuentran en la pestaña de solicitudes reembolsos-->
<div class="modal fade modal-alertas" id="modalReembolsoCancelar" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header"  style="background: #DD4B39; color: #fff;">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">MOTIVO DE LA CANCELACIÓN R</h4>
			</div>
			<form method="post" id="infoReemFormCancelar">
				<div class="modal-body">
					<div class="row">
						<div class='form-group col-lg-12'>
							<input type='text' class='form-control' placeholder='Describe motivo la cancelación.' name='observacionC' id='observacionReemC' required>
						</div>
					</div>

					<div class="row">
						<div class='form-group col-lg-12 text-center'>
							<div class='btn-group'>
								<button type='submit' class='btn btn-success'>ACEPTAR</button>
								<button type="button" class="btn btn-danger" data-dismiss="modal">CANCELAR</button>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- Modal para calcelar la solicitudes que se encuentran en la pestaña de solicitudes caja chica no deducible-->
<div class="modal fade modal-alertas" id="modalNodeducibleCancelar" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header"  style="background: #DD4B39; color: #fff;">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">MOTIVO DE LA CANCELACIÓN R</h4>
			</div>
			<form method="post" id="infoNoDeducibleFormCancelar">
				<div class="modal-body">
					<div class="row">
						<div class='form-group col-lg-12'>
							<input type='text' class='form-control' placeholder='Describe motivo la cancelación.' name='observacionC' id='observacionNoDeducibleC' required>
						</div>
					</div>

					<div class="row">
						<div class='form-group col-lg-12 text-center'>
							<div class='btn-group'>
								<button type='submit' class='btn btn-success'>ACEPTAR</button>
								<button type="button" class="btn btn-danger" data-dismiss="modal">CANCELAR</button>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- Modal para calcelar la solicitudes que se encuentran en la pestaña de solicitudes viaticos-->
<div class="modal fade modal-alertas" id="modalViaticosCancelar" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header"  style="background: #DD4B39; color: #fff;">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">MOTIVO DE LA CANCELACIÓN DEL VIÁTICO</h4>
			</div>
			<form method="post" id="infoViaticoFormCancelar">
				<div class="modal-body">
					<div class="row">
						<div class='form-group col-lg-12'>
							<input type='text' class='form-control' placeholder='Describe motivo la cancelación.' name='observacionC' id='observacionViaticoC' required>
						</div>
					</div>

					<div class="row">
						<div class='form-group col-lg-12 text-center'>
							<div class='btn-group'>
								<button type='submit' class='btn btn-success'>ACEPTAR</button>
								<button type="button" class="btn btn-danger" data-dismiss="modal">CANCELAR</button>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- 
FIN
Fecha : 12-Agosto-2025 @author Efrain Martinez Muñoz <programador.analista38@ciudadmaderas.com>
-->

<script>
    var idUsuario = <?= $this->session->userdata("inicio_sesion")['id'] ?>; /** FECHA: 17-JUNIO-2025 | PANEL CP A USUARIOS ESPECIFICOS | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/

    $(document).ready(function(e){

        $("#fechaini_mod").datepicker({
            todayBtn:  1,autoclose: true,format: 'dd/mm/yyyy'
        }).on('changeDate', function (selected) {
            var minDate = new Date(selected.date.valueOf());
            $('#fechafin_mod').datepicker('setStartDate', minDate);
        });

        $("#fechafin_mod").datepicker({format: 'dd/mm/yyyy',autoclose: true})
        .on('changeDate', function (selected) {
            if(selected.date){
                var minDate = new Date(selected.date.valueOf());
                $('#fechaini_mod').datepicker('setEndDate', minDate);
            }
            
        });

        /*$('#fechaini_mod,#fechafin_mod').datepicker({
          format: 'dd/mm/yyyy'
        });*/
    });

    var tabla_nueva_proveedor;
    var tabla_nueva_caja_chica;
	var tabla_nueva_reembolsos;
	var tabla_nueva_caja_chica_no_deducible;
	var tabla_nueva_viaticos;
    var cerrar_ch = false;
    var columnas_exportar = '';

    var fecha_hoy = new Date();
    var rol = `<?= $this->session->userdata("inicio_sesion")['rol'] ?>`;
    $('.select2').select2();

    $('[data-toggle="tab"]').click( function(e) {
        switch( $(this).data('value') ){
            case 'facturas_autorizar':
                tabla_nueva_proveedor.ajax.url( url + "Cuentasxp/ver_nuevas_proveedor" ).load();
                break;
            case 'pagos_autoriza_dg_trans':
                tabla_nueva_caja_chica.columns(6).visible(false);
                columnas_exportar = tabla_nueva_caja_chica.buttons()[0].inst.c.buttons[0].exportOptions.columns;
                columnas_exportar[columnas_exportar.length-1] = 5;
				tabla_nueva_caja_chica.ajax.url( url + "Cuentasxp/ver_nuevas_caja_chica" ).load();
				tabla_nueva_caja_chica.buttons( '.agregar_no_deducible' ).remove();
				cerrar_ch = true;
                tabla_nueva_caja_chica.column(7).visible(false); // FECHA: 29-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com>
                tabla_nueva_caja_chica.column(8).visible(false); // FECHA: 29-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com>
				break;
			case 'pagos_reembolsos':
				tabla_nueva_reembolsos.ajax.url( url + "Cuentasxp/ver_nuevas_reembolsos" ).load();
				tabla_nueva_reembolsos.buttons( '.agregar_no_deducible' ).remove();
				cerrar_ch = true;
				break;
			case 'pagos_viaticos':
				tabla_nueva_viaticos.ajax.url( url + "Cuentasxp/ver_nuevas_viaticos" ).load();
				tabla_nueva_viaticos.buttons( '.agregar_no_deducible' ).remove();
                cerrar_ch = true;
                break;
            case 'pagos_autoriza_dg_fact':
                tabla_nueva_fact.ajax.url( url + "Cuentasxp/ver_nuevas_fact" ).load();
                break;
            case 'pagos_programados':
                tabla_pagos_programados_nuevo.ajax.url( url +"Cuentasxp/tabla_programados_espera" ).load();
                break;
            case 'pagos_tdc':
                cerrar_ch = false;
                tabla_nueva_caja_chica.columns(6).visible(true);
                tabla_nueva_caja_chica.columns(5).visible(false);
                columnas_exportar = tabla_nueva_caja_chica.buttons()[0].inst.c.buttons[0].exportOptions.columns;
                columnas_exportar[columnas_exportar.length-1] = 6;
                tabla_nueva_caja_chica.buttons( '.agregar_no_deducible' ).remove();
                tabla_nueva_caja_chica.ajax.url( url +"Cuentasxp/ver_nuevas_caja_chica_TDC" ).load();
                tabla_nueva_caja_chica.button().add( 1, {
                    action: function ( e, dt, button, config ) {

                        $("#gastoNodeducible .form-control").val('').change();

                        //VERIFICAMOS SI YA TIENE OPCIONES EL FORMULARIO PARA NO VOLVER HACER EL LLAMADO DEL SERVICIO
                        if( $("#proyecto option").length == 0 )
                            //LLAMADO DEL SERVICIO PARA EL LLENADO DE CATALOGOS
                            $.getJSON( url + "Listas_select/NodeduciblesTDC" ).done( function( data ){

                                $('select2').select2('destroy');

                                $("#gastoNodeducible #proyecto, #gastoNodeducible #responsable_cc")
                                .html("")
                                .append('<option value="">Selecciones una opción</option>');

                                $.each( data.lista_proyectos_depto, function( i, v ){
                                    $("#gastoNodeducible #proyecto").append( '<option value="'+v.concepto+'">'+v.concepto+'</option>' );
                                });

                                $.each( data.rtcredito, function( i, v ){
                                    $("#gastoNodeducible #responsable_cc").append('<option value="'+v.idusuario+'" data-rfcempresa="'+v.rfc+'" data-tempresa="'+v.idempresa+'">'+v.nresponsable+'</option>');
                                });

                                $('.select2').select2();
                            });

                        $("#gastoNodeducible").modal();
                    },
                    text: '<i class="fas fa-plus"></i> GASTOS NO DEDUCIBLES',
                    attr: {
                        class: 'btn btn-warning agregar_no_deducible'       
                    },
                });
                tabla_nueva_caja_chica.column(2).visible(false); // FECHA: 29-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com>
                tabla_nueva_caja_chica.column(7).visible(true); // FECHA: 29-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com>
                tabla_nueva_caja_chica.column(8).visible(true); // FECHA: 29-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com>
                break;
            case 'pagos_autoriza_dg_chNoddls':
                tabla_nueva_caja_chica_no_deducible.ajax.url( url + "Cuentasxp/ver_nuevas_caja_chica_no_deducibles" ).load();
                tabla_nueva_caja_chica_no_deducible.buttons( '.agregar_no_deducible' ).remove();
                cerrar_ch = true;
                break;
        }
    });

    fecha_hoy = fecha_hoy.getFullYear()+"-"+(fecha_hoy.getMonth() +1)+"-"+fecha_hoy.getDate();

    $("#tabla_autorizaciones_proveedores").ready( function(){

        $('#tabla_autorizaciones_proveedores thead tr:eq(0) th').each( function (i) {
            if( i != 0 && i != 12 && i != 11){
                var title = $(this).text();
                $(this).html( '<input type="text" style="width:100%;" placeholder="'+title+'" />' );
                $( 'input', this ).on('keyup change', function () {

                    if (tabla_nueva_proveedor.column(i).search() !== this.value ) {
                        tabla_nueva_proveedor
                        .column(i)
                        .search( this.value )
                        .draw();
                        var total = 0;
                        var index = tabla_nueva_proveedor.rows( { selected: true, search: 'applied' } ).indexes();
                        var data = tabla_nueva_proveedor.rows( index ).data()
                        $.each(data, function(i, v){
                            total += parseFloat(v.cantidad);
                        });
                        var to1 = formatMoney(total);
                        document.getElementById("myText_1").value = to1;
                    }
                });
            }
        });

        $('#tabla_autorizaciones_proveedores').on('xhr.dt', function ( e, settings, json, xhr ) {
            var total = 0;
            $.each( json.data,  function(i, v){
                total += parseFloat(v.cantidad);
            });
            var to = formatMoney(total);
            document.getElementById("myText_1").value = to;
        });

        tabla_nueva_proveedor = $("#tabla_autorizaciones_proveedores").DataTable({
            dom: 'Brtip',
            "buttons": [
                {
                    extend: 'excelHtml5',             
                    text: '<i class="fas fa-file-excel"></i> EXPORTAR EXCEL',
                    messageTop: "Lista de solicitudes por autorizar",
                    attr: {
                        class: 'btn btn-success'       
                },
                exportOptions: {
                    columns: [1, 2, 3, 4, 5, 6, 7, 8, 9],
                    format: {
                        header: function (data, columnIdx) { 
                            data = data.replace( '<input type="text" style="width:100%;" placeholder="', '' );
                            data = data.replace( '">', '' )
                            return data;
                        }
                    }
                }
            }],
            "language":lenguaje,
            "processing": false,
            "pageLength": 10,
            "bAutoWidth": false,
            "bLengthChange": false,
            "ordering" :false,
            "scrollX": true,
            "bInfo": false,
            "searching": true,
            "columns": [
            {
                "className": 'details-control',
                "orderable": false,
                "data" : null,
                "defaultContent": '<i class="fas animacion fa-caret-right"></i>'
            },
            {
                "width": "8%",
                "data" : function( d ){
                    return '<p style="font-size: .7em">'+d.idsolicitud+'</p>'
                }
            },
            {
                "width": "8%",
                "data" : function( d ){
                    return '<p style="font-size: .7em">'+d.folio+'</p>'
                }
            },
            {
                "width": "15%",
                "data": function( d ){
                    return '<p style="font-size: .7em">'+d.nombre+'</p>';
                }
            },
            {
                "width": "15%",
                "data" : function( d ){
                    return '<p style="font-size: .7em">'+d.nombres+'</p>'
                }
            },
            {
                "width": "11%",
                "data" : function( d ){
                    return '<p style="font-size: .7em">$ '+formatMoney( d.cantidad )+" "+d.moneda+'</p>'
                }
            },
            {
                "width": "8%",
                "data" : function( d ){
                    return '<p style="font-size: .7em">'+d.fecelab+'</p>'
                }
            },
            {
                "width": "8%",
                "data" : function( d ){
                    return '<p style="font-size: .7em">'+d.nomdepto+'</p>'
                }
            },
            {
                "width": "8%",
                "data" : function( d ){
                    return '<p style="font-size: .7em">'+d.nemp+'</p>'
                }
            },
            {
                "width": "8%",
                "data": function( d ){
                    return '<p style="font-size: .7em">'+d.fechaCreacion+'</p>'
                }
            },
            {
                "width": "7%",
                "data": function( d ){
                    
                    var texto = "";
                    texto += d.okfactura 
                            ? "<center><i style='color:orange;' class='fas fa-check'></i> <span style='font-size: .7em'>FACTURA</span></center>"
                            : "<center><span style='font-size: .7em'>-</span></center>";
                    

                    if( d.prioridad == 1 ){ 
                        texto +=   "<center><small class='label pull-center bg-red'>URGENTE</small></center>";
                    }

                    return texto;
                }
            },
            {
                "orderable": false,
                "width": "20%",
                "data": function( data ){

                    opciones = '<div class="btn-group-vertical" role="group">';
                    /**
                     * FECHA : 12-Agosto-2025 @author Efrain Martinez <programador.analista38@ciudadmaderas.com>
                     * Se agrega el boton para cancelar la solicitud desde este panel y se oculta el de rechazar solicitud.
                     */
                    opciones += '<button type="button" class="notification btn btn-primary btn-sm consultar_modal" style="" value="'+data.idsolicitud+'" data-value="SOL" title="Ver Solicitud"><i class="far fa-eye"></i>'+(data.visto == 0 ? '</i><span class="badge">!</span>' : '')+'</button>';
                    opciones += '<button type="button" class="btn btn-success btn-sm aceptar_pago" data-value="PROV" title="Aceptar Solicitud"><i class="fas fa-check"></i></button>';
                     opciones += '<button type="button" class="btn btn-danger cancelar_pago btn-sm" data-value="PROV" title="Cancelar solicitud"><i class="fas fa-ban"></i></button>';
                    //opciones += '<button type="button" class="btn btn-danger rechazar_pago btn-sm" data-value="PROV" title="Rechazar Solicitud"><i class="fas fa-close"></i></button>';
                    opciones += '<button type="button" style="margin-bottom: 5px;"class="btn bg-purple btn-sm btn-masopciones" value="'+data.idsolicitud+'" data-table="1" title="Más opciones"><i class="fa fa-plus-circle"></i></button>';
                    
                    return opciones + '</div>';
                } 
            }
        ],
        "ajax": url + "Cuentasxp/ver_nuevas_proveedor"
        });

        $('#tabla_autorizaciones_proveedores tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = tabla_nueva_proveedor.row( tr );

            if ( row.child.isShown() ) {
                row.child.hide();
                tr.removeClass('shown');
                $(this).parent().find('.animacion').removeClass("fa-caret-down").addClass("fa-caret-right");
            }
            else {
                var informacion_adicional = '<div class="col-xs-12 col-md-12" style="text-align: padding:5px; justify; border-left: 1px solid #D2D6DE; border-right: 1px solid #D2D6DE; border-bottom: 1px solid #D2D6DE; border-radius: 0 0 5px 5px;">' +
                        '<p style="font-size: .9em"><b>CAPTURISTA: </b>' + row.data().nombre_capturista + '</p>' +
                        '<p style="font-size: .9em"><b>FORMA DE PAGO: </b>' + row.data().metoPago + '</p>' +
                        '<p style="font-size: .9em"><b>JUSTIFICACIÓN: </b>' + row.data().justificacion + '</p>' +
                    '</div>';

                row.child( informacion_adicional ).show();
                tr.addClass('shown');
                $(this).parent().find('.animacion').removeClass("fa-caret-right").addClass("fa-caret-down");
            }
        });
    });

    /************************************************/
    /************************TABLA FACTORAJE*********/
    var tabla_nueva_fact;
  
    $("#tabla_autorizaciones_fact").ready( function(){

        $('#tabla_autorizaciones_fact thead tr:eq(0) th').each( function (i) {
            if( i!=0 && i!= 11){
                var title = $(this).text();
                $(this).html('<input type="text" style="width:100%;" placeholder="'+title+'"/>' );
                $( 'input', this ).on('keyup change', function () {

                    if (tabla_nueva_fact.column(i).search() !== this.value ) {
                        tabla_nueva_fact
                        .column(i)
                        .search( this.value )
                        .draw();

                        var total = 0;
                        var index = tabla_nueva_fact.rows( { selected: true, search: 'applied' } ).indexes();
                        var data = tabla_nueva_fact.rows( index ).data();

                        $.each(data, function(i, v){
                            total += parseFloat(v.cantidad);
                        });
                        var to1 = formatMoney(total);
                        document.getElementById("myText_3").value = formatMoney(total);
                    }
                } );
            }
        });

        $('#tabla_autorizaciones_fact').on('xhr.dt', function ( e, settings, json, xhr ) {
            var total = 0;
            $.each(json.data, function(i, v){
                total += parseFloat(v.cantidad);
            });
            var to = formatMoney(total);
            document.getElementById("myText_3").value = to;
        });

        tabla_nueva_fact = $("#tabla_autorizaciones_fact").DataTable({
            dom: 'Brtip',
            width: 'auto',
            "buttons": [{
                extend: 'excelHtml5',             
                text: '<i class="fas fa-file-excel"></i> EXPORTAR EXCEL',
                messageTop: "Lista de pagos autorizados por Dirección General (OTROS)",
                attr: {
                    class: 'btn btn-success'  
                },
                exportOptions: {
                    columns: [ 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11 ],
                    format: {
                        header: function (data, columnIdx) { 
                            data = data.replace( '<input type="text" style="width:100%;" placeholder="', '' );
                            data = data.replace( '">', '' )
                            return data;
                        }
                    }
                }
            },
            {
                text: '<i class="fa fa-check"></i> ACEPTAR PAGOS',
                action: function(){
                    if ($('input[name="id[]"]:checked').length > 0) {
                        var idfactorajes = $(tabla_nueva_fact.$('input[name="id[]"]:checked')).map(function () { return this.value; }).get();
                        $.post(url+"Cuentasxp/aceptocpp_fact/", { idfactorajes : idfactorajes } ).done(function ( data ) { 
                            data = JSON.parse( data );
                            if( data.resultado ){
                                tabla_nueva_fact.clear();
                                tabla_nueva_fact.rows.add( data.data ).draw();
                            }
                        });
                    }
                },
                attr: {
                    class: 'btn bg-navy',
                }
            }],
            "language":lenguaje,
            "processing": false,
            "pageLength": 10,
            "bAutoWidth": false,
            "bLengthChange": false,
            "scrollX": true,
            "bInfo": false,
            "searching": true,
            "ordering": false,
            "fixedColumns": true,
            "columns": [
                { "width": "4%" },
                { 
                    "orderable": false,
                    "width": "8%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.idsolicitud+'</p>'
                    },

                },
                {
                    "width": "6%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.folio+'</p>'
                    }
                },
                {
                    "width": "14%",
                    "data" : function( d ){
                        return '<p style="font-size: .7em">'+d.nombre+'</p>'
                    }
                },
                {
                    "width": "10%",
                    "data" : function( d ){
                        return '<p style="font-size: .7em">'+d.nombres+" "+d.apellidos+'</p>'
                    }
                },
                
                {
                    "width": "10%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">$ '+formatMoney( d.cantidad )+" "+d.moneda+'</p>'
                    }
                },
                {
                    "width": "8%",
                    "data" : function( d ){
                        return '<p style="font-size: .7em">'+d.metoPago+'</p>' + ( d.prioridad == 1 ? "<center><small class='label pull-center bg-red'>URGENTE</small></center>" : "" );

                        ;
                    }
                },
                {
                    "width": "8%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.fecelab+'</p>'
                    }
                },
                {
                    "width": "10%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.nomdepto+'</p>' + ( d.okfactura ? "<center><i style='color:orange;' class='fas fa-check'></i> <span style='font-size: .7em'>FACTURA</span></center>" : "" );
                    }
                },
                {
                    "width": "6%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.nemp+'</p>';
                    }
                },
                {
                    "width": "8%",
                    "data": function( d ){
                        return '<p style="font-size: .8em">'+d.fechaCreacion+'</p>';
                    }
                },
                {
                    "orderable": false,
                    "width": "8%",
                    "data": function( data ){
                        opciones = '<div class="btn-group-vertical" role="group">';
                        opciones += '<button type="button" class="notification btn btn-primary btn-sm consultar_modal" style="" value="'+data.idsolicitud+'" data-value="SOL" title="Ver Solicitud"><i class="far fa-eye"></i>'+(data.visto == 0 ? '</i><span class="badge">!</span>' : '')+'</button>';
                        return opciones + '</div>';
                    } 
                }
            ],
            columnDefs: [{
                orderable: false,
                className: 'select-checkbox',
                targets:   0,
                'searchable':false,
                'className': 'dt-body-center',
                'render': function (d, type, full, meta){
                    return '<input type="checkbox" name="id[]" style="width:20px;height:20px;" value="'+full.idsolicitud+'">';     
                },
                select: {
                    style:    'os',
                    selector: 'td:first-child'
                },
            }]
        });
    }); 
    /****************************************/

	$("#tabla_autorizaciones_reembolsos").ready( function () {

		$('#tabla_autorizaciones_reembolsos thead tr:eq(0) th').each( function (i) {
			if( i != 0 ){
				var title = $(this).text();
				$(this).html( '<input type="text" style="width:100%;" placeholder="'+title+'" />' );
				$('input', this).on('keyup change', function() {

					if (tabla_nueva_reembolsos.column(i).search() !== this.value ) {

						tabla_nueva_reembolsos
							.column(i)
							.search( this.value )
							.draw();

						var total = 0;
						var index = tabla_nueva_reembolsos.rows( { selected: true, search: 'applied' } ).indexes();
						var data = tabla_nueva_reembolsos.rows( index ).data();

						$.each(data, function(i, v){
							total += parseFloat(v.Cantidad);
						});
						var to1 = formatMoney(total);
						document.getElementById("reembolsoTxt").value = formatMoney(total);

					}
				});
			}
		});


		$('#tabla_autorizaciones_reembolsos').on('xhr.dt', function ( e, settings, json, xhr ) {
			var total = 0;
			$.each(json.data, function(i, v){
				total += parseFloat(v.Cantidad);
			});
			var to = formatMoney(total);
			document.getElementById("reembolsoTxt").value = to;
			// console.log('recarga tabla ');
			tabla_nueva_reembolsos.column( 5 ).visible( cerrar_ch );
		});

		tabla_nueva_reembolsos = $('#tabla_autorizaciones_reembolsos').DataTable({
			dom: 'Brtip',
			width: 'auto',
			"buttons": [
				{
					extend: 'excelHtml5',
					text: '<i class="fas fa-file-excel"></i> EXPORTAR EXCEL',
					messageTop: "Lista de pagos autorizados por Dirección General (CAJA CHICA)",
					attr: {
						class: 'btn btn-success'
					},
					exportOptions: {
						columns: [1, 2, 3, 4, 5],
						format: {
							header: function (data, columnIdx) {
								data = data.replace( '<input type="text" style="width:100%;" placeholder="', '' );
								data = data.replace( '">', '' )
								return data;
							}
						}
					}
				}
			],
			"language" : lenguaje,
			"processing": false,
			"pageLength": 10,
			"ordering" :false,
			"bAutoWidth": false,
			"bLengthChange": false,
			"bInfo": false,
			"scrollX": true,
			responsive: true,
			"columns": [
				{
					"className": 'details-control',
					"orderable": false,
					"data" : null,
					"defaultContent": '<i class="fas animacion fa-caret-right"></i>'
				},
				{
					"data": function( d ){
						return '<p style="font-size: .9em">'+d.Responsable+'</p>';
					}
				},
				{
					"width": "12%",
					"data": function( d ){
						return '<p style="font-size: .9em">'+d.abrev+'</p>';
					}
				},
				{
					"width": "12%",
					"data": function( d ){
						return '<p style="font-size: .9em">'+d.FECHAFACP+'</p>';
					}
				},
				{
					"data": function( d ){
						return '<p style="font-size: .9em">$ '+formatMoney(d.Cantidad)+' MXN</p>';
					}
				},
				{
					"width": "15%",
					"data": function( d ){
						return '<p style="font-size: .9em">'+d.Departamento+'</p>';
					}
				},
			],
			"columnDefs":[
				{
					"targets": [ 5 ],
					"visible": false,
				},
			]
		});

		$('#tabla_autorizaciones_reembolsos tbody').on('click', 'td.details-control', function () {

			var tr = $(this).closest('tr');
			trsolicitudescch = $(this).closest('tr');
			var row = tabla_nueva_reembolsos.row( tr );

			if ( row.child.isShown() ) {
				row.child.hide();
				tr.removeClass('shown');
				$(this).parent().find('.animacion').removeClass("fa-caret-down").addClass("fa-caret-right");
			}else {
				if( row.data().solicitudes == null || row.data().solicitudes == "null" ){
					$.post( url + "Cuentasxp/carga_cajas_chicas" , { "idcajachicas" : row.data().ID } ).done( function( data ){
						row.data().solicitudes = JSON.parse( data );

						tabla_nueva_reembolsos.row( tr ).data( row.data() );

						row = tabla_nueva_reembolsos.row( tr );

						row.child( detalles_reembolsos( row.data().solicitudes ) ).show();
						tr.addClass('shown');
						$(this).parent().find('.animacion').removeClass("fa-caret-right").addClass("fa-caret-down");
					});
				}else{
					row.child( detalles_reembolsos( row.data().solicitudes ) ).show();
					tr.addClass('shown');
					$(this).parent().find('.animacion').removeClass("fa-caret-right").addClass("fa-caret-down");
				}

			}
		});

		$('#tabla_autorizaciones_reembolsos tbody').on('click','.cerrar_caja', function (){
			var row = tabla_nueva_reembolsos.row( $(this).closest('tr') );
			ids = row.data().ID;
			$("#confirm").modal();
		});

	});

	$("#tabla_autorizaciones_cajaChicaNoddls").ready( function () {

		$('#tabla_autorizaciones_cajaChicaNoddls thead tr:eq(0) th').each( function (i) {
			if( i != 0 ){
				var title = $(this).text();
				$(this).html( '<input type="text" style="width:100%;" placeholder="'+title+'" />' );
				$('input', this).on('keyup change', function() {

					if (tabla_nueva_caja_chica_no_deducible.column(i).search() !== this.value ) {

						tabla_nueva_caja_chica_no_deducible
							.column(i)
							.search( this.value )
							.draw();

						var total = 0;
						var index = tabla_nueva_caja_chica_no_deducible.rows( { selected: true, search: 'applied' } ).indexes();
						var data = tabla_nueva_caja_chica_no_deducible.rows( index ).data();

						$.each(data, function(i, v){
							total += parseFloat(v.Cantidad);
						});
						var to1 = formatMoney(total);
						document.getElementById("cajaChicaNDDLSTxt").value = to1;

					}
				});
			}
		});


        // $('#tabla_autorizaciones_cajaChicaNoddls').on('xhr.dt', function ( e, settings, json, xhr ) {
        //     var total = 0;
        //     $.each(json.data, function(i, v){
        //         total += parseFloat(v.Cantidad);
        //     });
        //     var to = formatMoney(total);
        //     document.getElementById("cajaChicaNDDLSTxt").value = to;
        //     console.log('recarga tabla ');
        //     tabla_nueva_caja_chica_no_deducible.column( 4 ).visible( cerrar_ch );
        // });

		tabla_nueva_caja_chica_no_deducible = $('#tabla_autorizaciones_cajaChicaNoddls').DataTable({
			dom: 'Brtip',
			width: 'auto',
			"buttons": [
				{
					extend: 'excelHtml5',
					text: '<i class="fas fa-file-excel"></i> EXPORTAR EXCEL',
					messageTop: "Lista de pagos autorizados por Dirección General (CAJA CHICA)",
					attr: {
						class: 'btn btn-success'
					},
					exportOptions: {
						columns: [1, 2, 3, 4, 5],
						format: {
							header: function (data, columnIdx) {
								data = data.replace( '<input type="text" style="width:100%;" placeholder="', '' );
								data = data.replace( '">', '' )
								return data;
							}
						}
					}
				}
			],
			"language" : lenguaje,
			"processing": false,
			"pageLength": 10,
			"ordering" :false,
			"bAutoWidth": false,
			"bLengthChange": false,
			"bInfo": false,
			"scrollX": true,
			responsive: true,
			"columns": [
				{
					"className": 'details-control',
					"orderable": false,
					"data" : null,
					"defaultContent": '<i class="fas animacion fa-caret-right"></i>'
				},
				{
					"data": function( d ){
						return '<p style="font-size: .9em">'+d.Responsable+'</p>';
					}
				},
				{
					"width": "12%",
					"data": function( d ){
						return '<p style="font-size: .9em">'+d.abrev+'</p>';
					}
				},
				{
					"width": "12%",
					"data": function( d ){
						return '<p style="font-size: .9em">'+d.FECHAFACP+'</p>';
					}
				},
				{
					"data": function( d ){
						return '<p style="font-size: .9em">$ '+formatMoney(d.Cantidad)+' MXN</p>';
					}
				},
				{
					"width": "15%",
					"data": function( d ){
						return '<p style="font-size: .9em">'+d.Departamento+'</p>';
					}
				},
			],
			"columnDefs":[
				{
					"targets": [ 5 ],
					"visible": false,
				},
			]
		});

		$('#tabla_autorizaciones_cajaChicaNoddls tbody').on('click', 'td.details-control', function () {

			var tr = $(this).closest('tr');
			trsolicitudescch = $(this).closest('tr');
			var row = tabla_nueva_caja_chica_no_deducible.row( tr );

			if ( row.child.isShown() ) {
				row.child.hide();
				tr.removeClass('shown');
				$(this).parent().find('.animacion').removeClass("fa-caret-down").addClass("fa-caret-right");
			}else {
				if( row.data().solicitudes == null || row.data().solicitudes == "null" ){
					$.post( url + "Cuentasxp/carga_cajas_chicas" , { "idcajachicas" : row.data().ID } ).done( function( data ){
						row.data().solicitudes = JSON.parse( data );

						tabla_nueva_caja_chica_no_deducible.row( tr ).data( row.data() );

						row = tabla_nueva_caja_chica_no_deducible.row( tr );

						row.child( detalles_caja_chica_noDeducible( row.data().solicitudes ) ).show();
						tr.addClass('shown');
						$(this).parent().find('.animacion').removeClass("fa-caret-right").addClass("fa-caret-down");
					});
				}else{
					row.child( detalles_caja_chica_noDeducible( row.data().solicitudes ) ).show();
					tr.addClass('shown');
					$(this).parent().find('.animacion').removeClass("fa-caret-right").addClass("fa-caret-down");
				}

			}
		});

		$('#tabla_autorizaciones_cajaChicaNoddls tbody').on('click','.cerrar_caja', function (){
			var row = tabla_nueva_caja_chica_no_deducible.row( $(this).closest('tr') );
			ids = row.data().ID;
			$("#confirm").modal();
		});

	});

    $("#tabla_autorizaciones_caja_chica").ready( function () {

        $('#tabla_autorizaciones_caja_chica thead tr:eq(0) th').each( function (i) {
            if( i != 0 ){
                var title = $(this).text();
                $(this).html( '<input type="text" style="width:100%;" placeholder="'+title+'" />' );
                $('input', this).on('keyup change', function() {

                    if (tabla_nueva_caja_chica.column(i).search() !== this.value ) {

                            tabla_nueva_caja_chica
                            .column(i)
                            .search( this.value )
                            .draw();

                            var total = 0;
                            var index = tabla_nueva_caja_chica.rows( { selected: true, search: 'applied' } ).indexes();
                            var data = tabla_nueva_caja_chica.rows( index ).data();

                            $.each(data, function(i, v){
                                total += parseFloat(v.Cantidad);
                            });
                            var to1 = formatMoney(total);
                            document.getElementById("myText_2").value = formatMoney(total);

                    }
                });
            }
        });


        $('#tabla_autorizaciones_caja_chica').on('xhr.dt', function ( e, settings, json, xhr ) {
            var total = 0;
            $.each(json.data, function(i, v){
                total += parseFloat(v.Cantidad);
            });
            var to = formatMoney(total);
            document.getElementById("myText_2").value = to;
            tabla_nueva_caja_chica.column( 6 ).visible( cerrar_ch ); /** FECHA: 29-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
        });

        tabla_nueva_caja_chica = $('#tabla_autorizaciones_caja_chica').DataTable({
            dom: 'Brtip',
            width: 'auto',
            "buttons": [
                {
                    extend: 'excelHtml5',
                    text: '<i class="fas fa-file-excel"></i> EXPORTAR EXCEL',
                    messageTop: "Lista de pagos autorizados por Dirección General (CAJA CHICA)",
                    attr: {
                        class: 'btn btn-success'
                },
                exportOptions: {
                        columns: function(idx, data, node){ // Cambios TDC Reportes | FECHA: 17-Abril-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com>
                            // console.log($('ul.nav-tabs').children('li.active').eq(0).children('a').data('value'));
                            if ($('ul.nav-tabs').children('li.active').eq(0).children('a').data('value') !== 'pagos_tdc'){
                                if($.inArray(idx, [1, 2, 3, 4, 5, 6]) > -1) return true; /** FECHA: 29-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                            }else{
                                if($.inArray(idx, [1, 3, 4, 5, 7, 8]) > -1) return true; /** FECHA: 29-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                            }
                        }, // FIN Cambios TDC Reportes | FECHA: 17-Abril-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com>
                        format: {
                            header: function (data, columnIdx) {
                                 data = data.replace( '<input type="text" style="width:100%;" placeholder="', '' );
                                data = data.replace( '">', '' )
                                return data;
                            }
                        }
                    }
                }
            ],
            "language" : lenguaje,
            "processing": false,
            "pageLength": 10,
            "ordering" :false,
            "bAutoWidth": false,
            "bLengthChange": false,
            "bInfo": false,
            "scrollX": true,
            responsive: true,
            "columns": [
                {
                    "className": 'details-control',
                    "orderable": false,
                    "data" : null,
                    "defaultContent": '<i class="fas animacion fa-caret-right"></i>'
                },
                {
                    "data": function( d ){
                        return '<p style="font-size: .9em">'+d.Responsable+'</p>';
                    }
                },
                { /** INICIO FECHA: 29-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                    "width": "10%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+(d.nombre_reembolso_cch ? d.nombre_reembolso_cch : 'NA' )+'</p>'
                    }
                }, /** FIN FECHA: 29-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                {
                    "width": "12%",
                    "data": function( d ){
                        return '<p style="font-size: .9em">'+d.abrev+'</p>';
                    }
                },
                {
                    "width": "12%",
                    "data": function( d ){
                        return '<p style="font-size: .9em">'+d.FECHAFACP+'</p>';
                    }
                },
                {
                    "data": function( d ){
                        return '<p style="font-size: .9em">$ '+formatMoney(d.Cantidad)+'</p>'; /** FECHA: 29-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                    }
                },
                {
                    "width": "15%",
                    "data": function( d ){
                        return '<p style="font-size: .9em">'+d.Departamento+'</p>';
                    }
                },
                { /** INICIO FECHA: 29-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                    "data": function( d ){
                        return '<p style="font-size: .9em">'+ d.moneda +'</p>';
                    }
                }, /** FIN FECHA: 29-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                {// Cambios TDC Reportes | FECHA: 17-Abril-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com>
                    "width": "15%",
                    "data": function( d ){
                        return '<p style="font-size: .9em">'+(d.titular_nombre ? d.titular_nombre : 'NA' )+'</p>';
                    }
                }, // FIN Cambios TDC Reportes | FECHA: 17-Abril-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com>
            ],
        });

        /*
        $('#tabla_autorizaciones_caja_chica tbody').on('click', 'td.details-control', function () {

            if( !trsolicitudescch ){
                trsolicitudescch = $(this).closest('tr');
            }

            if( trsolicitudescch.is( $(this).parents('tr') )){
                var row = tabla_nueva_caja_chica.row( trsolicitudescch );
                if ( row.child.isShown() ) {
                    // This row is already open - close it
                    row.child.hide();
                    trsolicitudescch.removeClass('shown');
                }else {
                    if ( tabla_nueva_caja_chica.row( '.shown' ).length ) {
                        $('td.details-control', tabla_nueva_caja_chica.row( '.shown' ).node()).click();
                    }

                    // Open this row
                    row.child( detalles_caja_chica(row.data().solicitudes) ).show();
                    trsolicitudescch.addClass('shown');
                }
            }else{
                var row = tabla_nueva_caja_chica.row( trsolicitudescch );
                if( row.child.isShown() ){

                    row.child.hide();
                    trsolicitudescch.removeClass('shown');
                }

                trsolicitudescch = $(this).parents('tr');
                row = tabla_nueva_caja_chica.row( trsolicitudescch );
                row.child( detalles_caja_chica(row.data().solicitudes) ).show();
                trsolicitudescch.addClass('shown');
            }
        });
        */

        $('#tabla_autorizaciones_caja_chica tbody').on('click', 'td.details-control', function () {

            var tr = $(this).closest('tr');
            trsolicitudescch = $(this).closest('tr');
            var row = tabla_nueva_caja_chica.row( tr );

            if ( row.child.isShown() ) {
                row.child.hide();
                tr.removeClass('shown');
                $(this).parent().find('.animacion').removeClass("fa-caret-down").addClass("fa-caret-right");
            }else {
                if( row.data().solicitudes == null || row.data().solicitudes == "null" ){
                    $.post( url + "Cuentasxp/carga_cajas_chicas" , { "idcajachicas" : row.data().ID } ).done( function( data ){
                        row.data().solicitudes = JSON.parse( data );

                        tabla_nueva_caja_chica.row( tr ).data( row.data() );

                        row = tabla_nueva_caja_chica.row( tr );

                        row.child( detalles_caja_chica( row.data().solicitudes ) ).show();
                        tr.addClass('shown');
                        $(this).parent().find('.animacion').removeClass("fa-caret-right").addClass("fa-caret-down");
                    });
                }else{
                    row.child( detalles_caja_chica( row.data().solicitudes ) ).show();
                    tr.addClass('shown');
                    $(this).parent().find('.animacion').removeClass("fa-caret-right").addClass("fa-caret-down");
                }

            }
        });

        $('#tabla_autorizaciones_caja_chica tbody').on('click','.cerrar_caja', function (){
            var row = tabla_nueva_caja_chica.row( $(this).closest('tr') );
            ids = row.data().ID;
            $("#confirm").modal();
        });

    });

	$("#tabla_autorizaciones_viaticos").ready( function () {

		$('#tabla_autorizaciones_viaticos thead tr:eq(0) th').each( function (i) {
			if( i != 0 ){
				var title = $(this).text();
				$(this).html( '<input type="text" style="width:100%;" placeholder="'+title+'" />' );
				$('input', this).on('keyup change', function() {

					if (tabla_nueva_viaticos.column(i).search() !== this.value ) {

						tabla_nueva_viaticos
							.column(i)
							.search( this.value )
							.draw();

						var total = 0;
						var index = tabla_nueva_viaticos.rows( { selected: true, search: 'applied' } ).indexes();
						var data = tabla_nueva_viaticos.rows( index ).data();

						$.each(data, function(i, v){
							total += parseFloat(v.Cantidad);
						});
						var to1 = formatMoney(total);
						document.getElementById("txtViaticos").value = formatMoney(total);

					}
				});
			}
		});


		$('#tabla_autorizaciones_viaticos').on('xhr.dt', function ( e, settings, json, xhr ) {
			var total = 0;
			$.each(json.data, function(i, v){
				total += parseFloat(v.Cantidad);
			});
			var to = formatMoney(total);
			document.getElementById("txtViaticos").value = to;
			tabla_nueva_viaticos.column( 5 ).visible( cerrar_ch );
		});

		tabla_nueva_viaticos = $('#tabla_autorizaciones_viaticos').DataTable({
			dom: 'Brtip',
			width: 'auto',
			"buttons": [
				{
					extend: 'excelHtml5',
					text: '<i class="fas fa-file-excel"></i> EXPORTAR EXCEL',
					messageTop: "Lista de pagos autorizados por Dirección General (CAJA CHICA)",
					attr: {
						class: 'btn btn-success'
					},
					exportOptions: {
						columns: [1, 2, 3, 4, 5, 6],
						format: {
							header: function (data, columnIdx) {
								data = data.replace( '<input type="text" style="width:100%;" placeholder="', '' );
								data = data.replace( '">', '' )
								return data;
							}
						}
					}
				}
			],
			"language" : lenguaje,
			"processing": false,
			"pageLength": 10,
			"ordering" :false,
			"bAutoWidth": false,
			"bLengthChange": false,
			"bInfo": false,
			"scrollX": true,
			responsive: true,
			"columns": [
				{
					"className": 'details-control',
					"orderable": false,
					"data" : null,
					"defaultContent": '<i class="fas animacion fa-caret-right"></i>'
				},
				{
					"data": function( d ){
						return '<p style="font-size: .9em">'+d.Responsable+'</p>';
					}
				},
				{ /** FECHA: 12-JUNIO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
					"data": function( d ){
						return '<p style="font-size: .9em">'+(d.nombre_reembolso_cch ? d.nombre_reembolso_cch : 'NA')+'</p>';
					}
				}, /** FECHA: 12-JUNIO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
				{
					"width": "12%",
					"data": function( d ){
						return '<p style="font-size: .9em">'+d.abrev+'</p>';
					}
				},
				{
					"width": "12%",
					"data": function( d ){
						return '<p style="font-size: .9em">'+d.FECHAFACP+'</p>';
					}
				},
				{
					"data": function( d ){
						return '<p style="font-size: .9em">$ '+formatMoney(d.Cantidad)+' MXN</p>';
					}
				},
				{
					"width": "15%",
					"data": function( d ){
						return '<p style="font-size: .9em">'+d.Departamento+'</p>';
					}
				},
			],
			"columnDefs":[
				{
					"targets": [ 5 ],
					"visible": false,
				},
			]
		});

		/*
		$('#tabla_autorizaciones_caja_chica tbody').on('click', 'td.details-control', function () {

			if( !trsolicitudescch ){
				trsolicitudescch = $(this).closest('tr');
			}

			if( trsolicitudescch.is( $(this).parents('tr') )){
				var row = tabla_nueva_caja_chica.row( trsolicitudescch );
				if ( row.child.isShown() ) {
					// This row is already open - close it
					row.child.hide();
					trsolicitudescch.removeClass('shown');
				}else {
					if ( tabla_nueva_caja_chica.row( '.shown' ).length ) {
						$('td.details-control', tabla_nueva_caja_chica.row( '.shown' ).node()).click();
					}

					// Open this row
					row.child( detalles_caja_chica(row.data().solicitudes) ).show();
					trsolicitudescch.addClass('shown');
				}
			}else{
				var row = tabla_nueva_caja_chica.row( trsolicitudescch );
				if( row.child.isShown() ){

					row.child.hide();
					trsolicitudescch.removeClass('shown');
				}

				trsolicitudescch = $(this).parents('tr');
				row = tabla_nueva_caja_chica.row( trsolicitudescch );
				row.child( detalles_caja_chica(row.data().solicitudes) ).show();
				trsolicitudescch.addClass('shown');
			}
		});
		*/

		$('#tabla_autorizaciones_viaticos tbody').on('click', 'td.details-control', function () {

			var tr = $(this).closest('tr');
			trsolicitudesviaticos = $(this).closest('tr');
			var row = tabla_nueva_viaticos.row( tr );

			if ( row.child.isShown() ) {
				row.child.hide();
				tr.removeClass('shown');
				$(this).parent().find('.animacion').removeClass("fa-caret-down").addClass("fa-caret-right");
			}else {
				if( row.data().solicitudes == null || row.data().solicitudes == "null" ){
					$.post( url + "Cuentasxp/carga_cajas_chicas" , { "idcajachicas" : row.data().ID } ).done( function( data ){
						row.data().solicitudes = JSON.parse( data );

						tabla_nueva_viaticos.row( tr ).data( row.data() );

						row = tabla_nueva_viaticos.row( tr );

						row.child( detalles_viaticos( row.data().solicitudes ) ).show();
						tr.addClass('shown');
						$(this).parent().find('.animacion').removeClass("fa-caret-right").addClass("fa-caret-down");
					});
				}else{
					row.child( detalles_viaticos( row.data().solicitudes ) ).show();
					tr.addClass('shown');
					$(this).parent().find('.animacion').removeClass("fa-caret-right").addClass("fa-caret-down");
				}

			}
		});

		$('#tabla_autorizaciones_viaticos tbody').on('click','.cerrar_caja', function (){
			var row = tabla_nueva_viaticos.row( $(this).closest('tr') );
			ids = row.data().ID;
			$("#confirm").modal();
		});

	});

    var ids = 0;
    function cerrar_caja(){
        if(ids != 0 ){
            $.post(url+"Cuentasxp/cerrar_caja_sol", { "ids" : ids } ).done( function(data){
                tabla_nueva_caja_chica.ajax.url( url + "Cuentasxp/ver_nuevas_caja_chica" ).load();
                tabla_nueva_caja_chica.buttons( '.agregar_no_deducible' ).remove();
                cerrar_ch = true;
                $("#confirm").modal('toggle');
            });
        }else
            alert("Ocurrio un error intentelo mas tarde");
    }
     /**
     * FECHA : 12-Agosto-2025 @author Efrain Martinez <programador.analista38@ciudadmaderas.com>
     * Se agrega el boton para cancelar la solicitud desde este panel y se oculta el de rechazar solicitud.
     */
    function detalles_viaticos(data) { 
        var solicitudes = '<div style="width: 100%; padding-top: 10px; padding-bottom: 10px; border-left: 1px solid #D2D6DE; border-right: 1px solid #D2D6DE; border-bottom: 1px solid #D2D6DE; border-radius: 0 0 5px 5px;">';
        $.each(data, function(i, v) {
            solicitudes += `
                            <div class="row col-xs-11 col-md-11">
                                <div class="col-12 col-md-4">
                                    <p><b>NO. SOLICITUD:</b> ${v.idsolicitud}</p>
                                    <p><b>PROYECTO:</b> ${v.proyecto}</p>
                                    <p><b>PAÍS:</b> ${v.pais ? v.pais : 'NA'}</p>
                                    <p><b>NÚMERO DE COLABORADORES:</b> ${v.colabs ? v.colabs : 'NA'}</p>
                                </div>
                                <div class="col-12 col-md-4">
                                    <p><b>PROVEEDOR:</b> ${v.nombre_proveedor}</p>
                                    ${parseFloat(v.cantidad) > parseFloat(v.cantidad_limite) 
                                        ? `<label><b>CANTIDAD:</b> ${formatMoney(parseFloat(v.cantidad))} MXN</label>
                                            &nbsp&nbsp
                                           <label style="color: red;"><b>EXCEDENTE:</b> ${formatMoney(parseFloat(v.cantidad_limite) - parseFloat(v.cantidad))} MXN</label>`
                                        : `<p><b>CANTIDAD:</b> ${formatMoney(parseFloat(v.cantidad))} MXN</p>`
                                    }
                                    <p><b>ZONA:</b> ${v.zona ? v.zona : 'NA'}</p>
                                    <p><b>TIPO INSUMO:</b> ${v.tipo_insumo ? v.tipo_insumo : 'NA'}</p>
                                </div>
                                <div class="col-12 col-md-4">
                                    <p><b>TIPO PAGO:</b> ${v.metoPago}</p>
                                    <p><b>FECHA FACT:</b> ${v.fecelab}</p>
                                    <p><b>ESTADO:</b> ${v.estado ? v.estado : 'NA'}</p>
                                </div>
                                <div class="col-12 col-md-12">
                                    <p><b>JUSTIFICACIÓN:</b> ${v.justificacion}</p>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-xs-1 col-md-1" style="text-align: center;">
                                    <div class="btn-group-vertical" role="group">
                                        <button type="button" class="notification btn btn-primary btn-sm consultar_modal mb-1" value="${v.idsolicitud}" data-value="SOL" title="Ver Solicitud">
                                            <i class="far fa-eye"></i>${v.visto == 0 ? '<span class="badge">!</span>' : ''}
                                        </button>
                                        <button type="button" class="btn btn-success btn-sm aceptar_pagoViatico mb-1" data-value="${i}" title="Aceptar Solicitud">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <!-- <button type="button" class="btn btn-danger btn-sm rechazar_pagoViatico mb-1" data-value="${i}" title="Rechazar Solicitud">
                                            <i class="fas fa-times"></i> 
                                        </button> -->
                                        <button type="button" class="btn btn-danger btn-sm cancelarpagoViatico mb-1" data-value="${i}" title="Cancelar Solicitud">
                                            <i class="fas fa-ban"></i> 
                                        </button>
                                        <button type="button" class="btn bg-purple btn-sm btn-masopciones-viaticos" value="${v.idsolicitud}" data-table="2" data-fac="${v.idfactura}" data-solr="${v.idsolicitudr}" title="Más opciones">
                                            <i class="fa fa-plus-circle"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
            `;

            solicitudes += ` ${(i < data.length - 1 ? '<hr style="border-top: 1px solid #BEBEBE; margin-top: 10px; margin-bottom: 10px;">' : '')}`;
        });
        solicitudes += '</div>';
        return solicitudes;
    }
    /**
     * FECHA : 12-Agosto-2025 @author Efrain Martinez <programador.analista38@ciudadmaderas.com>
     * Se agrega el boton para cancelar la solicitud desde este panel y se oculta el de rechazar solicitud.
     */
    function detalles_caja_chica(data) { 
        var solicitudes = '<div style="width: 100%; padding-top: 10px; padding-bottom: 10px; border-left: 1px solid #D2D6DE; border-right: 1px solid #D2D6DE; border-bottom: 1px solid #D2D6DE; border-radius: 0 0 5px 5px;">';
        $.each(data, function(i, v) {
            solicitudes += `
                <div class="row col-xs-11 col-md-11">
                    <table class="table" style="background-color: transparent !important;">
                        <tr>
                            <td>
                                ${v.idsolicitud} - <b>PROYECTO: </b>${v.proyecto}
                            </td>
                            <td>
                                <b>PROVEEDOR: </b>${v.nombre_proveedor}
                            </td>
                            <td>
                                <b>CANTIDAD: </b>${formatMoney(v.cantidad)+' '+v.moneda}
                            </td>
                            <td>
                                <b>TIPO PAGO: </b>${v.metoPago}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <b>FECHA FACT: </b>${v.fecelab}
                            </td>
                            <td colspan="4">
                                <b>JUSTIFICACIÓN: </b>${v.justificacion}
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="row mt-2">
                    <div class="col-xs-1 col-md-1" style="text-align: center;">
                        <div class="btn-group-vertical" role="group">
                            <button type="button" class="notification btn btn-primary btn-sm consultar_modal mb-1" value="${v.idsolicitud}" data-value="SOL" title="Ver Solicitud">
                                <i class="far fa-eye"></i>${v.visto == 0 ? '<span class="badge">!</span>' : ''}
                            </button>
                            <button type="button" class="btn btn-success btn-sm aceptar_pago mb-1" data-value="${i}" title="Aceptar Solicitud">
                                <i class="fas fa-check"></i>
                            </button>
                            <!-- <button type="button" class="btn btn-danger btn-sm rechazar_pago mb-1" data-value="${i}" title="Rechazar Solicitud">
                                <i class="fas fa-times"></i>
                            </button> -->
                             <button type="button" class="btn btn-danger btn-sm cancelar_pago mb-1" data-value="${i}" title="Cancelar Solicitud">
                                <i class="fas fa-ban"></i>
                            </button>
                            <button type="button" class="btn bg-purple btn-sm btn-masopciones" value="${v.idsolicitud}" data-table="2" data-fac="${v.idfactura}" data-solr="${v.idsolicitudr}" title="Más opciones">
                                <i class="fa fa-plus-circle"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
            solicitudes += `${(i < data.length - 1 ? '<hr style="border-top: 1px solid #BEBEBE; margin-top: 10px; margin-bottom: 10px;">' : '')}`;
        });
        solicitudes += '</div>';
        return solicitudes;
    }
    /**
     * FECHA : 12-Agosto-2025 @author Efrain Martinez <programador.analista38@ciudadmaderas.com>
     * Se agrega el boton para cancelar la solicitud desde este panel y se oculta el de rechazar solicitud.
     */
    function detalles_reembolsos(data) {
        var solicitudes = '<div style="width: 100%; padding-top: 10px; padding-bottom: 10px; border-left: 1px solid #D2D6DE; border-right: 1px solid #D2D6DE; border-bottom: 1px solid #D2D6DE; border-radius: 0 0 5px 5px;">';
        $.each(data, function(i, v) {
            solicitudes += `
                <div class="row col-xs-11 col-md-11">
                    <div class="col-12 col-md-4">
                        <p><b>NO. SOLICITUD:</b> ${v.idsolicitud}</p>
                        <p><b>PROYECTO:</b> ${v.proyecto}</p>
                    </div>
                    <div class="col-12 col-md-4">
                        <p><b>PROVEEDOR:</b> ${v.nombre_proveedor}</p>
                        <p><b>CANTIDAD:</b> ${formatMoney(v.cantidad)} MXN</p>
                    </div>
                    <div class="col-12 col-md-4">
                        <p><b>TIPO PAGO:</b> ${v.metoPago}</p>
                        <p><b>FECHA FACT:</b> ${v.fecelab}</p>
                    </div>
                    <div class="col-12 col-md-12">
                        <p><b>JUSTIFICACIÓN:</b> ${v.justificacion}</p>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-xs-1 col-md-1" style="text-align: center;">
                        <div class="btn-group-vertical" role="group">
                            <button type="button" class="notification btn btn-primary btn-sm consultar_modal mb-1" value="${v.idsolicitud}" data-value="SOL" title="Ver Solicitud">
                                <i class="far fa-eye"></i>${v.visto == 0 ? '<span class="badge">!</span>' : ''}
                            </button>
                            <button type="button" class="btn btn-success btn-sm aceptar_pagoReembolsos mb-1" data-value="${i}" title="Aceptar Solicitud">
                                <i class="fas fa-check"></i>
                            </button>
                            <!-- <button type="button" class="btn btn-danger btn-sm rechazar_pagoReembolsos mb-1" data-value="${i}" title="Rechazar Solicitud">
                                <i class="fas fa-times"></i>
                            </button> -->
                            <button type="button" class="btn btn-danger btn-sm CancelarpagoReembolsos mb-1" data-value="${i}" title="Cancelar Solicitud">
                                <i class="fas fa-ban"></i>
                            </button>
                            <button type="button" class="btn bg-purple btn-sm btn-masopciones-reembolsos" value="${v.idsolicitud}" data-table="2" data-fac="${v.idfactura}" data-solr="${v.idsolicitudr}" title="Más opciones">
                                <i class="fa fa-plus-circle"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
            solicitudes += `${(i < data.length - 1 ? '<hr style="border-top: 1px solid #BEBEBE; margin-top: 10px; margin-bottom: 10px;">' : '')}`;
        });
        solicitudes += '</div>';
        return solicitudes;
    }
    /**
     * FECHA : 12-Agosto-2025 @author Efrain Martinez <programador.analista38@ciudadmaderas.com>
     * Se agrega el boton para cancelar la solicitud desde este panel y se oculta el de rechazar solicitud.
     */
    function detalles_caja_chica_noDeducible(data) {
        var solicitudes = '<div style="width: 100%; padding-top: 10px; padding-bottom: 10px; border-left: 1px solid #D2D6DE; border-right: 1px solid #D2D6DE; border-bottom: 1px solid #D2D6DE; border-radius: 0 0 5px 5px;">';
        $.each(data, function(i, v) {
            solicitudes += `
                <div class="row col-xs-11 col-md-11">
                    <div class="col-12 col-md-4">
                        <p><b>NO. SOLICITUD:</b> ${v.idsolicitud}</p>
                        <p><b>PROYECTO:</b> ${v.proyecto}</p>
                    </div>
                    <div class="col-12 col-md-4">
                        <p><b>PROVEEDOR:</b> ${v.nombre_proveedor}</p>
                        <p><b>CANTIDAD:</b> ${formatMoney(v.cantidad)} MXN</p>
                    </div>
                    <div class="col-12 col-md-4">
                        <p><b>TIPO PAGO:</b> ${v.metoPago}</p>
                        <p><b>FECHA FACT:</b> ${v.fecelab}</p>
                    </div>
                    <div class="col-12 col-md-12">
                        <p><b>JUSTIFICACIÓN:</b> ${v.justificacion}</p>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-xs-1 col-md-1" style="text-align: center;">
                        <div class="btn-group-vertical" role="group">
                            <button type="button" class="notification btn btn-primary btn-sm consultar_modal mb-1" value="${v.idsolicitud}" data-value="SOL" title="Ver Solicitud">
                                <i class="far fa-eye"></i>${v.visto == 0 ? '<span class="badge">!</span>' : ''}
                            </button>
                            <button type="button" class="btn btn-success btn-sm aceptar_chNodeducible mb-1" data-value="${i}" title="Aceptar Solicitud">
                                <i class="fas fa-check"></i>
                            </button>
                            <!-- <button type="button" class="btn btn-danger btn-sm rechazar_chNodeducible mb-1" data-value="${i}" title="Rechazar Solicitud">
                                <i class="fas fa-times"></i>
                            </button> -->
                            <button type="button" class="btn btn-danger btn-sm cancelarchNodeducible mb-1" data-value="${i}" title="Cancelar Solicitud">
                                <i class="fas fa-ban"></i>
                            </button>
                            <button type="button" class="btn bg-purple btn-sm btn-masopciones-noDeducibles" value="${v.idsolicitud}" data-table="2" data-fac="${v.idfactura}" data-solr="${v.idsolicitudr}" title="Más opciones">
                                <i class="fa fa-plus-circle"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
            solicitudes += `${(i < data.length - 1 ? '<hr style="border-top: 1px solid #BEBEBE; margin-top: 10px; margin-bottom: 10px;">' : '')}`;
        });
        solicitudes += '</div>';
        return solicitudes;
    }
    function modal_xml(valores){
        id = valores.split('|')[0];

        link_complentos = "Complementos_cxp/cargarxml_cajachica";
        $("#factura_complemento").modal();
    }
     
    $("#complemento_facturas").submit( function(e) {
        e.preventDefault();
    }).validate({
        submitHandler: function( form ) {
            var data = new FormData();
            data.append("id", id);
            data.append("xmlfile", $("#complemento")[0].files[0]);
            data.append("tpocam", $("#tipocam").val());
            //data.append("validarxml", $("#validar_checkbox:checked").val() ? 1 : 0);

            $.ajax({
                url: url + link_complentos,
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                method: 'POST',
                type: 'POST', // For jQuery < 1.9
                success: function(data){
                    if( data.respuesta[0] ){
                        $("#factura_complemento .form-control").val( '');
                        $("#factura_complemento").modal( 'toggle');
                        $("#alert-modal").modal( 'toggle');

                        if( data.deuda > 0.05 ){
                            alert(data.mensaje[0]);
                        }

                        if(data.uuids){
                            $('.modal-data').append(`<tr><th>SOLICITUD</th><th>FOLIO FISCAL</th></tr>`);
                            const uuids_list = data.uuids.map((uuid)=> {
                                return $('.modal-data').append(`<tr><td>${uuid.idsolicitud}</td><td>${uuid.uuid}</td></tr>`);
                            });
                        }
                        //tabla_nueva_caja_chica.ajax.reload();
                    }else{
                        alert( data.respuesta[1] );
                    }
                },error: function( ){
                    
                }
            });
        }
    });

    $( document ).on("click", ".cargar_factura", function(){
        
         // if($("#complemento").val() == ""){

            var data = new FormData();
            data.append("id", id);
            data.append("xmlfile", $("#comp_mento")[0].files[0]);
            data.append("tpocam", $("#tipocam").val());
           
            $.ajax({
                url: url + link_complentos,
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                method: 'POST',
                type: 'POST', // For jQuery < 1.9
                success: function(data){
                    if( data.respuesta[0] ){
                        if( table == 1 ){
                            rowinfo.idfactura = 1;
                            tabla_nueva_proveedor.row( trOpc ).data( rowinfo ).draw();
                        }else{
                            tabla_nueva_caja_chica.ajax.reload();
                        }
                        
                        $("#modal_opciones").modal( 'toggle' );
                    }else{
                        alert( data.respuesta[1] );
                    }
                },error: function( ){
                    
                }
            });
        // }
    });

	$( document ).on("click", ".cargar_factura_reembolsos", function(){

		// if($("#complemento").val() == ""){

		var data = new FormData();
		data.append("id", id);
		data.append("xmlfile", $("#comp_mento")[0].files[0]);
		data.append("tpocam", $("#tipocam").val());

		$.ajax({
			url: url + link_complentos,
			data: data,
			cache: false,
			contentType: false,
			processData: false,
			dataType: 'json',
			method: 'POST',
			type: 'POST', // For jQuery < 1.9
			success: function(data){
				if( data.respuesta[0] ){
					if( table == 1 ){
						rowinfo.idfactura = 1;
						tabla_nueva_reembolsos.row( trOpc ).data( rowinfo ).draw();
					}else{
						tabla_nueva_reembolsos.ajax.reload();
					}

					$("#modal_opciones").modal( 'toggle' );
					alert(data.mensaje);
				}else{
					alert( data.respuesta[1] );
				}
			},error: function( ){

			}
		});
		// }
	});


    $("#gNodeducible").submit( function(e) {
        e.preventDefault();
    }).validate({
        submitHandler: function( form ) {

            var data = new FormData( $(form)[0] );

            $.ajax({
                url: url + "Cuentasxp/gGastoNodeducible",
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                method: 'POST',
                type: 'POST', // For jQuery < 1.9
                success: function(data){
                    if( data.resultado ){
                        $("#gastoNodeducible").modal("toggle");
                        tabla_nueva_caja_chica.ajax.url( url +"Cuentasxp/ver_nuevas_caja_chica_TDC" ).load();
                    }else{
                        alert( "No fue posible completar su transacción. Intente mas tarde." );
                    }
                },error: function( ){
                    
                }
            });
        }
    });
    
    var trsolicitudes;
    var trsolicitudescch;
    var indexcajas_chicas;
    var indexcajas_chicasNODDLS;
    var trsolicitudesviaticos;
    var indexViaticos;

    $( document ).on("click", ".aceptar_pago", function(){
        
        indexcajas_chicas = $(this).attr( "data-value" );
        trsolicitudes = $(this).closest('tr');
        switch( indexcajas_chicas ){
            case 'PROV':
                //EN CASO DE TENER UNA FACTURA PARA PROVISIONAR PASA POR ACA
                var row = tabla_nueva_proveedor.row( trsolicitudes );
                if( row.data().okfactura && row.data().tipo_factura == 1 ){
                    $("#modal_provisionar").modal();
                }else{
                //EN CASO CONTRARIO VA SOLO LA CONFIRMACION DE LA SOLICITUD
                    $("#myModal").modal();
                }
                break;
            case 'PROG':
            default:
                $("#myModal").modal();
                break;
        }
    });

	$( document ).on("click", ".aceptar_pagoViatico", function(){

		indexViaticos = $(this).attr( "data-value" );
		trsolicitudes = $(this).closest('tr');
		switch( indexViaticos ){
			case 'PROV':
				//EN CASO DE TENER UNA FACTURA PARA PROVISIONAR PASA POR ACA
				var row = tabla_nueva_proveedor.row( trsolicitudes );
				if( row.data().okfactura && row.data().tipo_factura == 1 ){
					$("#modal_provisionar").modal();
				}else{
					//EN CASO CONTRARIO VA SOLO LA CONFIRMACION DE LA SOLICITUD
					$("#aceptarSolViatico").modal();
				}
				break;
			case 'PROG':
			default:
				$("#aceptarSolViatico").modal();
				break;
		}
	});

    $( document ).on("click", ".aceptar_pago_fact", function(){    
        indexcajas_fact = $(this).attr( "data-value" );
        
        switch( indexcajas_fact ){
            case 'PROV':
                 trsolicitudes = $(this).closest('tr');
                var row = tabla_nueva_fact.row( trsolicitudes );
 
                    $("#myModalfact").modal();
               
                break;
            default:
                $("#myModalfact").modal();
                break;
        }
    });

	$( document ).on("click", ".aceptar_pagoReembolsos", function(){

		indexreembolsos = $(this).attr( "data-value" );
		trsolicitudes = $(this).closest('tr');

		console.log('indexcajas_chicas', indexreembolsos);
		console.log('trsolicitudes', trsolicitudes);
		switch( indexreembolsos ){
			case 'PROV':
				//EN CASO DE TENER UNA FACTURA PARA PROVISIONAR PASA POR ACA
				var row = tabla_nueva_proveedor.row( trsolicitudes );
				if( row.data().okfactura && row.data().tipo_factura == 1 ){
					$("#modal_provisionar").modal();
				}else{
					//EN CASO CONTRARIO VA SOLO LA CONFIRMACION DE LA SOLICITUD
					$("#aceptarSolReembolso").modal();
				}
				break;
			case 'PROG':
			default:
				$("#aceptarSolReembolso").modal();
				break;
		}
	});

	$( document ).on("click", ".aceptar_chNodeducible", function(){

		indexcajas_chicasNODDLS = $(this).attr( "data-value" );
		trsolicitudesNODDLS = $(this).closest('tr');
		switch( indexcajas_chicasNODDLS ){
			case 'PROV':
				//EN CASO DE TENER UNA FACTURA PARA PROVISIONAR PASA POR ACA
				var row = tabla_nueva_caja_chica_no_deducible.row( trsolicitudesNODDLS );
				if( row.data().okfactura && row.data().tipo_factura == 1 ){
					$("#modal_provisionar").modal();
				}else{
					//EN CASO CONTRARIO VA SOLO LA CONFIRMACION DE LA SOLICITUD
					$("#aceptarSolNoDeducible").modal();
				}
				break;
			case 'PROG':
			default:
				$("#aceptarSolNoDeducible").modal();
				break;
		}
	});

    /////// MAS OPCIONES REEMBOLSOS////////
    var rowinfoND=""; var tableND =""; var trOpcND="";
    $( document ).on("click", ".btn-masopciones-noDeducibles", function(){
        $("#modal_opciones .modal-header").html("");
        $("#modal_opciones .modal-body").html("");
        $("#modal_opciones .modal-footer").html("");

        index_solicitud = $(this).attr("value");
        tableND =  $(this).attr( "data-table" );
        if( tableND == 1 ){
            trOpcND = $(this).closest('tr');
            rowinfo = tabla_nueva_proveedor.row( trOpcND ).data();
        }else{
            rowinfo={idfactura:$(this).attr( "data-fac" ), idsolicitudr:$(this).attr( "data-solr" )};
        }

        $("#modal_opciones .modal-header").append('<button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="modal-title">SELECCIONE ACCIÓN</h4><br><select id="change_options" name="change_options" class="form-control"><option value="">-- Selecciona opción --</option><option value="2">CARGAR FACTURA XML</option>'+(rowinfo.idfactura  > 0 ? '<option value="1">RELACIONAR FACTURA</option><option value="3">CAMBIAR PROVEEDOR DESDE XML</option></select>':''));

        $('#change_options').on('change',function(){
            cuenta = $(this).val();

            $("#modal_opciones .modal-body").html("");
            $("#modal_opciones .modal-footer").html("");

            switch(cuenta){
                case '1':
                    // RELACIONA FACTURA
                    $("#modal_opciones .modal-body").html("");
                    $("#modal_opciones .modal-footer").html("");
                    $("#modal_opciones .modal-body").append("<form><div class='col-lg-12 form-group'><label>#SOLICITUDES RELACIONADAS</label><div class='row'><div class='col-lg-12'><div class='input-group'><input type='text' class='form-control' id='nsolicitudes' placeholder='# Solicitudes sin espacio separadas por una coma'><div class='input-group-btn'><button type='button' class='btn btn-danger nsolicitudes'><i class='fas fa-upload'></i></button></div></div></div></div></div></form>");
                    rowinfo.idsolicitudr.length > 0 && rowinfo.idsolicitudr !='null' ? $("#nsolicitudes").val( rowinfo.idsolicitudr ) : null;

                    break;
                case '2':
                    // CARGAR FACTURA XML
                    id = index_solicitud ;
                    link_complentos = "Complementos_cxp/cargarxml_cajachica";
                    $("#modal_opciones .modal-body").html("");
                    $("#modal_opciones .modal-footer").html("");
                    $("#modal_opciones .modal-body").append("<form><div class='col-lg-12 form-group'><label>DOCUMENTO XML</label><div class='row'><div class='col-lg-12'><div class='input-group'><input type='file' class='form-control' id='comp_mento' accept='text/xml' required><div class='input-group-btn'><button type='button' class='btn btn-danger cargar_factura_reembolsos'><i class='fas fa-upload'></i></button></div></div></div></div></div></form>");
                    //$("#modal_opciones .modal-body").append("<form id='complemento_facturas' action='#'><div class='col-lg-12 form-group'><label>DOCUMENTO XML</label><div class='row'><div class='col-lg-12'><div class='input-group'><input type='file' class='form-control' id='complemento' accept='text/xml' required><div class='input-group-btn'><button type='submit' class='btn btn-danger cargar_complemento'><i class='fas fa-upload'></i></button></div></div></div></div></div></form>");
                    break;
                case '3':
                    // CAMBIAR PROVEEDOR DESDE XML
                    cambiarempresa_xml(trOpc);
                    break;
                default:
                    $("#modal_opciones .modal-body").html("");
                    $("#modal_opciones .modal-footer").html("");
                    break;
            }
        });
        $("#modal_opciones").modal();
    });

   /////// MAS OPCIONES ////////
   var rowinfo=""; var table =""; var trOpc="";
    $( document ).on("click", ".btn-masopciones", function(){
        $("#modal_opciones .modal-header").html("");
        $("#modal_opciones .modal-body").html("");
        $("#modal_opciones .modal-footer").html("");

        index_solicitud = $(this).attr("value");
        table =  $(this).attr( "data-table" );
        if( table == 1 ){
            trOpc = $(this).closest('tr');
            rowinfo = tabla_nueva_proveedor.row( trOpc ).data();
        }else{
            rowinfo={idfactura:$(this).attr( "data-fac" ), idsolicitudr:$(this).attr( "data-solr" )};
        }
        
        $("#modal_opciones .modal-header").append('<button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="modal-title">SELECCIONE ACCIÓN</h4><br><select id="change_options" name="change_options" class="form-control"><option value="">-- Selecciona opción --</option><option value="2">CARGAR FACTURA XML</option>'+(rowinfo.idfactura  > 0 ? '<option value="1">RELACIONAR FACTURA</option><option value="3">CAMBIAR PROVEEDOR DESDE XML</option></select>':''));

        $('#change_options').on('change',function(){
            cuenta = $(this).val();
            
            $("#modal_opciones .modal-body").html("");
            $("#modal_opciones .modal-footer").html("");
            
            switch(cuenta){
                case '1':
                    // RELACIONA FACTURA
                    $("#modal_opciones .modal-body").html("");
                    $("#modal_opciones .modal-footer").html("");
                    $("#modal_opciones .modal-body").append("<form><div class='col-lg-12 form-group'><label>#SOLICITUDES RELACIONADAS</label><div class='row'><div class='col-lg-12'><div class='input-group'><input type='text' class='form-control' id='nsolicitudes' placeholder='# Solicitudes sin espacio separadas por una coma'><div class='input-group-btn'><button type='button' class='btn btn-danger nsolicitudes'><i class='fas fa-upload'></i></button></div></div></div></div></div></form>");
                    rowinfo.idsolicitudr.length > 0 && rowinfo.idsolicitudr !='null' ? $("#nsolicitudes").val( rowinfo.idsolicitudr ) : null;
                    
                    break;
                case '2':
                    // CARGAR FACTURA XML
                    id = index_solicitud ;
                    link_complentos = "Complementos_cxp/cargarxml_cajachica";
                    $("#modal_opciones .modal-body").html("");
                    $("#modal_opciones .modal-footer").html("");
                    $("#modal_opciones .modal-body").append("<form><div class='col-lg-12 form-group'><label>DOCUMENTO XML</label><div class='row'><div class='col-lg-12'><div class='input-group'><input type='file' class='form-control' id='comp_mento' accept='text/xml' required><div class='input-group-btn'><button type='button' class='btn btn-danger cargar_factura'><i class='fas fa-upload'></i></button></div></div></div></div></div></form>");
                    //$("#modal_opciones .modal-body").append("<form id='complemento_facturas' action='#'><div class='col-lg-12 form-group'><label>DOCUMENTO XML</label><div class='row'><div class='col-lg-12'><div class='input-group'><input type='file' class='form-control' id='complemento' accept='text/xml' required><div class='input-group-btn'><button type='submit' class='btn btn-danger cargar_complemento'><i class='fas fa-upload'></i></button></div></div></div></div></div></form>");
                    break;
                case '3':
                    // CAMBIAR PROVEEDOR DESDE XML
                    cambiarempresa_xml(trOpc);
                    break;
                default:
                    $("#modal_opciones .modal-body").html("");
                    $("#modal_opciones .modal-footer").html("");
                    break;
            }
        });
        $("#modal_opciones").modal();
    });

	/////// MAS OPCIONES REEMBOLSOS////////
	var rowinfo=""; var table =""; var trOpc="";
	$( document ).on("click", ".btn-masopciones-reembolsos", function(){
		$("#modal_opciones .modal-header").html("");
		$("#modal_opciones .modal-body").html("");
		$("#modal_opciones .modal-footer").html("");

		index_solicitud = $(this).attr("value");
		table =  $(this).attr( "data-table" );
		if( table == 1 ){
			trOpc = $(this).closest('tr');
			rowinfo = tabla_nueva_proveedor.row( trOpc ).data();
		}else{
			rowinfo={idfactura:$(this).attr( "data-fac" ), idsolicitudr:$(this).attr( "data-solr" )};
		}

		$("#modal_opciones .modal-header").append('<button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="modal-title">SELECCIONE ACCIÓN</h4><br><select id="change_options" name="change_options" class="form-control"><option value="">-- Selecciona opción --</option><option value="2">CARGAR FACTURA XML</option>'+(rowinfo.idfactura  > 0 ? '<option value="1">RELACIONAR FACTURA</option><option value="3">CAMBIAR PROVEEDOR DESDE XML</option></select>':''));

		$('#change_options').on('change',function(){
			cuenta = $(this).val();

			$("#modal_opciones .modal-body").html("");
			$("#modal_opciones .modal-footer").html("");

			switch(cuenta){
				case '1':
					// RELACIONA FACTURA
					$("#modal_opciones .modal-body").html("");
					$("#modal_opciones .modal-footer").html("");
					$("#modal_opciones .modal-body").append("<form><div class='col-lg-12 form-group'><label>#SOLICITUDES RELACIONADAS</label><div class='row'><div class='col-lg-12'><div class='input-group'><input type='text' class='form-control' id='nsolicitudes' placeholder='# Solicitudes sin espacio separadas por una coma'><div class='input-group-btn'><button type='button' class='btn btn-danger nsolicitudes'><i class='fas fa-upload'></i></button></div></div></div></div></div></form>");
					rowinfo.idsolicitudr.length > 0 && rowinfo.idsolicitudr !='null' ? $("#nsolicitudes").val( rowinfo.idsolicitudr ) : null;

					break;
				case '2':
					// CARGAR FACTURA XML
					id = index_solicitud ;
					link_complentos = "Complementos_cxp/cargarxml_cajachica";
					$("#modal_opciones .modal-body").html("");
					$("#modal_opciones .modal-footer").html("");
					$("#modal_opciones .modal-body").append("<form><div class='col-lg-12 form-group'><label>DOCUMENTO XML</label><div class='row'><div class='col-lg-12'><div class='input-group'><input type='file' class='form-control' id='comp_mento' accept='text/xml' required><div class='input-group-btn'><button type='button' class='btn btn-danger cargar_factura_reembolsos'><i class='fas fa-upload'></i></button></div></div></div></div></div></form>");
					//$("#modal_opciones .modal-body").append("<form id='complemento_facturas' action='#'><div class='col-lg-12 form-group'><label>DOCUMENTO XML</label><div class='row'><div class='col-lg-12'><div class='input-group'><input type='file' class='form-control' id='complemento' accept='text/xml' required><div class='input-group-btn'><button type='submit' class='btn btn-danger cargar_complemento'><i class='fas fa-upload'></i></button></div></div></div></div></div></form>");
					break;
				case '3':
					// CAMBIAR PROVEEDOR DESDE XML
					cambiarempresa_xml(trOpc);
					break;
				default:
					$("#modal_opciones .modal-body").html("");
					$("#modal_opciones .modal-footer").html("");
					break;
			}
		});
		$("#modal_opciones").modal();
	});

	/////// MAS OPCIONES VIATICOS////////
	var rowinfo2=""; var table2 =""; var trOpc2="";
	$( document ).on("click", ".btn-masopciones-viaticos", function(){
		$("#modal_opciones .modal-header").html("");
		$("#modal_opciones .modal-body").html("");
		$("#modal_opciones .modal-footer").html("");

		index_solicitud = $(this).attr("value");
		table =  $(this).attr( "data-table" );
		if( table == 1 ){
			trOpc = $(this).closest('tr');
			rowinfo = tabla_nueva_viaticos.row( trOpc ).data();
		}else{
			rowinfo={idfactura:$(this).attr( "data-fac" ), idsolicitudr:$(this).attr( "data-solr" )};
		}

		$("#modal_opciones .modal-header").append('<button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="modal-title">SELECCIONE ACCIÓN</h4><br><select id="change_options" name="change_options" class="form-control"><option value="">-- Selecciona opción --</option><option value="2">CARGAR FACTURA XML</option>'+(rowinfo.idfactura  > 0 ? '<option value="1">RELACIONAR FACTURA</option><option value="3">CAMBIAR PROVEEDOR DESDE XML</option></select>':''));

		$('#change_options').on('change',function(){
			cuenta = $(this).val();

			$("#modal_opciones .modal-body").html("");
			$("#modal_opciones .modal-footer").html("");

			switch(cuenta){
				case '1':
					// RELACIONA FACTURA
					$("#modal_opciones .modal-body").html("");
					$("#modal_opciones .modal-footer").html("");
					$("#modal_opciones .modal-body").append("<form><div class='col-lg-12 form-group'><label>#SOLICITUDES RELACIONADAS</label><div class='row'><div class='col-lg-12'><div class='input-group'><input type='text' class='form-control' id='nsolicitudes' placeholder='# Solicitudes sin espacio separadas por una coma'><div class='input-group-btn'><button type='button' class='btn btn-danger nsolicitudes'><i class='fas fa-upload'></i></button></div></div></div></div></div></form>");
					rowinfo.idsolicitudr.length > 0 && rowinfo.idsolicitudr !='null' ? $("#nsolicitudes").val( rowinfo.idsolicitudr ) : null;

					break;
				case '2':
					// CARGAR FACTURA XML
					id = index_solicitud ;
					link_complentos = "Complementos_cxp/cargarxml_cajachica";
					$("#modal_opciones .modal-body").html("");
					$("#modal_opciones .modal-footer").html("");
					$("#modal_opciones .modal-body").append("<form><div class='col-lg-12 form-group'><label>DOCUMENTO XML</label><div class='row'><div class='col-lg-12'><div class='input-group'><input type='file' class='form-control' id='comp_mento' accept='text/xml' required><div class='input-group-btn'><button type='button' class='btn btn-danger cargar_factura_reembolsos'><i class='fas fa-upload'></i></button></div></div></div></div></div></form>");
					//$("#modal_opciones .modal-body").append("<form id='complemento_facturas' action='#'><div class='col-lg-12 form-group'><label>DOCUMENTO XML</label><div class='row'><div class='col-lg-12'><div class='input-group'><input type='file' class='form-control' id='complemento' accept='text/xml' required><div class='input-group-btn'><button type='submit' class='btn btn-danger cargar_complemento'><i class='fas fa-upload'></i></button></div></div></div></div></div></form>");
					break;
				case '3':
					// CAMBIAR PROVEEDOR DESDE XML
					cambiarempresa_xml(trOpc);
					break;
				default:
					$("#modal_opciones .modal-body").html("");
					$("#modal_opciones .modal-footer").html("");
					break;
			}
		});
		$("#modal_opciones").modal();
	});

    function cambiarempresa_xml(trOpc){
        $.post( url + "Listas_select/lista_proveedor_xml", {"idsolicitud":index_solicitud}).done( function(data){  
            proveedors=JSON.parse( data );
            if(proveedors.length>0){
                $("#modal_opciones .modal-body").html('<form method="post" id="formulario_opciones"><div class="col-lg-12 form-group"><label>PROVEEDOR DESDE XML</label><div class="row"><div class="col-lg-12"><select id="idproveedor" name="idproveedor" class="form-control" required></select><br/><button type="button" class="btn btn-block btn-danger cambio_proveedor">CAMBIAR</button></div></div></div></form>');
                $("#idproveedor").html( "" ).append('<option value="">Seleccione una opción</option>');
                $.each(proveedors, function(i,v){
                    $("#idproveedor").append('<option value="'+v.idproveedor+'">'+ v.nombreproveedor+' - '+v.nombrebanco+' - '+v.cuenta+'</option>');
                });
                $("#idproveedor").next().show();
            }else{
                alert("No hay facturas registradas" );
            }
           
        }); 
    }

    $( document ).on("click", "#formulario_opciones button.cambio_proveedor", function(){
        if( $( "#formulario_opciones" ).valid() ){
            var data = new FormData();
            data.append( "idsolicitud", index_solicitud );
            data.append( "idproveedor", $("#formulario_opciones #idproveedor").val() );
            $.ajax({
            processData: false,
            contentType: false,
            method: 'POST',
            type: "POST",
            url: url + "Cuentasxp/cambiar_proveedor",
            data: data,
            dataType: "json",
            cache: false,
            success: function(data) {
                if( data.resultado ){
                if(table==1){tabla_nueva_proveedor.row( trOpc ).data( data.solicitud ).draw();}
                else{tabla_nueva_caja_chica.ajax.reload();}
                $("#modal_opciones").modal("toggle")
                }},
            error: function() {alert( "No se pudo procesar su solicitud." );}
        });
        }
    });

    $( document ).on("click", ".nsolicitudes", function(){
        nsolicitudes = $("#nsolicitudes").val();
        enviar_post_64( function(data){
            if( data.resultado ){
                rowinfo.idsolicitudr = nsolicitudes;
                if(table==1){tabla_nueva_proveedor.row(trOpc ).data( rowinfo ).draw();}
                else{tabla_nueva_caja_chica.ajax.reload();}
                $("#modal_opciones").modal("toggle")
            }
        }, { idfactura: rowinfo.idfactura, numsols : $("#nsolicitudes").val(), completadas : null }, url+"Complementos_cxp/relacionar_nsol")
    });

    //RECHAZAMOS EL PAGO Y MANDAMOS A ESTATUS DE RECHAZO.
    $( document ).on("click", ".rechazar_pago", function(){
        if( $(this).attr( "data-value" ) == 'PROV' || $(this).attr( "data-value" ) == 'PROG' ){
            trsolicitudes = $(this).closest('tr');    
        } 
        indexcajas_chicas = $(this).attr( "data-value" );
        $("#observacion").val('');
        $("#myModalcomentario1").modal();
    });

	//RECHAZAMOS EL PAGO Y MANDAMOS A ESTATUS DE RECHAZO.
	$( document ).on("click", ".rechazar_pagoReembolsos", function(){
		if( $(this).attr( "data-value" ) == 'PROV' || $(this).attr( "data-value" ) == 'PROG' ){
			trsolicitudes = $(this).closest('tr');
		}
		indexreembolsos = $(this).attr( "data-value" );
		$("#observacionReem").val('');
		$("#modalReembolso").modal();
	});

	//RECHAZAMOS EL PAGO Y MANDAMOS A ESTATUS DE RECHAZO.
	$( document ).on("click", ".rechazar_pagoViatico", function(){
		if( $(this).attr( "data-value" ) == 'PROV' || $(this).attr( "data-value" ) == 'PROG' ){
			trsolicitudesviaticos = $(this).closest('tr');
		}
		indexViaticos = $(this).attr( "data-value" );
		$("#observacionViatico").val('');
		$("#modalViaticos").modal();
	});


	//RECHAZAMOS EL PAGO Y MANDAMOS A ESTATUS DE RECHAZO.
	$( document ).on("click", ".rechazar_chNodeducible", function(){
		if( $(this).attr( "data-value" ) == 'PROV' || $(this).attr( "data-value" ) == 'PROG' ){
			trsolicitudes = $(this).closest('tr');
		}
		indexcajas_chicasNODDLS = $(this).attr( "data-value" );
		$("#observacionNoDeducible").val('');
		$("#modalNodeducible").modal();
	});

    /**
     * INICIO
     * FECHA : 12-Agosto-2025 @author Efrain Martinez <programador.analista38@ciudadmaderas.com>
     * Se crean la funciones onclick de los botones que cancelan las solicitudes dependiendo del tipo de solicitud que se va a cancelar.
     */
    $( document ).on("click", ".cancelar_pago", function(){
        if( $(this).attr( "data-value" ) == 'PROV' || $(this).attr( "data-value" ) == 'PROG' || $(this).attr( "data-value" ) == 'DEV' ){
            trsolicitudes = $(this).closest('tr');    
        } 
        indexcajas_chicas = $(this).attr( "data-value" );
        $("#observacionC").val('');
        $("#myModalcomentarioCancelar").modal();
    });
    
    $( document ).on("click", ".CancelarpagoReembolsos", function(){
		if( $(this).attr( "data-value" ) == 'PROV' || $(this).attr( "data-value" ) == 'PROG' || $(this).attr( "data-value" ) == 'DEV'){
			trsolicitudes = $(this).closest('tr');
		}
		indexreembolsos = $(this).attr( "data-value" );
		$("#observacionReemC").val('');
		$("#modalReembolsoCancelar").modal();
	});

    $( document ).on("click", ".cancelarpagoViatico", function(){
		if( $(this).attr( "data-value" ) == 'PROV' || $(this).attr( "data-value" ) == 'PROG' ){
			trsolicitudesviaticos = $(this).closest('tr');
		}
		indexViaticos = $(this).attr( "data-value" );
		$("#observacionViaticoC").val('');
		$("#modalViaticosCancelar").modal();
	});

    $( document ).on("click", ".cancelarchNodeducible", function(){
		if( $(this).attr( "data-value" ) == 'PROV' || $(this).attr( "data-value" ) == 'PROG' ){
			trsolicitudes = $(this).closest('tr');
		}
		indexcajas_chicasNODDLS = $(this).attr( "data-value" );
		$("#observacionNoDeducibleC").val('');
		$("#modalNodeducibleCancelar").modal();
	});
    /**
     * INICIO
     * FECHA : 12-Agosto-2025 @author Efrain Martinez <programador.analista38@ciudadmaderas.com>
     */

    //ACEPTAMOS LAS CAJAS CHICAS PARA PASAR A OTRO ESTATUS
    $( document ).on("click", "#aceptar_solicitud", function(){
        switch( indexcajas_chicas ){
            case 'PROG':
                var row = tabla_pagos_programados_nuevo.row( trsolicitudes );
                $.post( url + "Cuentasxp/enviarcpp/", { idsolicitud : row.data().idsolicitud } ).done(function ( data ) { 
                    
                    $("#myModal").modal('toggle');
                    tabla_pagos_programados_nuevo.row( trsolicitudes ).remove().draw();
 
                }).fail( function(){
 
                });
                break;
            case 'PROV':
                var row = tabla_nueva_proveedor.row( trsolicitudes );
            
                $.post( url + "Cuentasxp/enviarcpp/", { idsolicitud : row.data().idsolicitud } ).done(function ( data ) { 
 
                    $("#myModal").modal('toggle');
                    tabla_nueva_proveedor.row( trsolicitudes ).remove().draw();
 
                }).fail( function(){
 
                });
                break;
            default:
                var row = tabla_nueva_caja_chica.row( trsolicitudescch );
                $.post( url + "Cuentasxp/enviarcpp/", { idsolicitud : row.data().solicitudes[indexcajas_chicas].idsolicitud } ).done(function ( data ) { 
                    $("#myModal").modal('toggle');
                    row.data().solicitudes.splice( indexcajas_chicas, 1 );
                    
                    if( (row.data().solicitudes).length > 0 ){
                        tabla_nueva_caja_chica.row( trsolicitudescch ).data( row.data() );
                        row.child( detalles_caja_chica( row.data().solicitudes) ).show();
                    }else{
                        tabla_nueva_caja_chica.row( trsolicitudescch ).remove().draw( false );
                    } 
 
                }).fail( function(){
 
                });
                break;
        }
    });

	$( document ).on("click", "#aceptar_solicitud_reembolso", function(){
		switch( indexreembolsos ){
			case 'PROG':
				var row = tabla_nueva_reembolsos.row( trsolicitudes );
				$.post( url + "Cuentasxp/enviarcpp/", { idsolicitud : row.data().idsolicitud } ).done(function ( data ) {

					$("#aceptarSolReembolso").modal('toggle');
					tabla_nueva_reembolsos.row( trsolicitudes ).remove().draw();

				}).fail( function(){

				});
				break;
			case 'PROV':
				var row = tabla_nueva_reembolsos.row( trsolicitudes );

				$.post( url + "Cuentasxp/enviarcpp/", { idsolicitud : row.data().idsolicitud } ).done(function ( data ) {

					$("#aceptarSolReembolso").modal('toggle');
					tabla_nueva_reembolsos.row( trsolicitudes ).remove().draw();

				}).fail( function(){

				});
				break;
			default:
				var row = tabla_nueva_reembolsos.row( trsolicitudescch );
				console.log( 'trsolicitudescch', trsolicitudescch );
				console.log( row.data().solicitudes );
				$.post( url + "Cuentasxp/enviarcpp/", { idsolicitud : row.data().solicitudes[indexreembolsos].idsolicitud } ).done(function ( data ) {
					$("#aceptarSolReembolso").modal('toggle');
					row.data().solicitudes.splice( indexreembolsos, 1 );

					if( (row.data().solicitudes).length > 0 ){
						tabla_nueva_reembolsos.row( trsolicitudescch ).data( row.data() );
						row.child( detalles_reembolsos( row.data().solicitudes) ).show();
					}else{
						tabla_nueva_reembolsos.row( trsolicitudescch ).remove().draw( false );
					}

				}).fail( function(){

				});
				break;
		}
	});

    $( document ).on("click", "#aceptar_solicitud_viaticos", function(){
        switch( indexViaticos ){
            case 'PROG':
                var row = tabla_nueva_viaticos.row( trsolicitudesviaticos );
                $.post( url + "Cuentasxp/enviarcpp/", { idsolicitud : row.data().idsolicitud } ).done(function ( data ) {

                    $("#aceptarSolViatico").modal('toggle');
                    tabla_nueva_viaticos.row( trsolicitudesviaticos ).remove().draw();

                }).fail( function(){

                });
                break;
            case 'PROV':
                var row = tabla_nueva_viaticos.row( trsolicitudesviaticos );

                $.post( url + "Cuentasxp/enviarcpp/", { idsolicitud : row.data().idsolicitud } ).done(function ( data ) {

                    $("#aceptarSolViatico").modal('toggle');
                    tabla_nueva_viaticos.row( trsolicitudes ).remove().draw();

                }).fail( function(){

                });
                break;
            default:
                var row = tabla_nueva_viaticos.row( trsolicitudesviaticos );
                console.log( 'trsolicitudescch', trsolicitudesviaticos );
                console.log( row.data().solicitudes );
                $.post( url + "Cuentasxp/enviarcpp/", { idsolicitud : row.data().solicitudes[indexViaticos].idsolicitud } ).done(function ( data ) {
                    $("#aceptarSolViatico").modal('toggle');
                    row.data().solicitudes.splice( indexViaticos, 1 );

                    if( (row.data().solicitudes).length > 0 ){
                        tabla_nueva_viaticos.row( trsolicitudesviaticos ).data( row.data() );
                        row.child( detalles_viaticos( row.data().solicitudes) ).show();
                    }else{
                        tabla_nueva_viaticos.row( trsolicitudesviaticos ).remove().draw( false );
                    }

                }).fail( function(){

                });
                break;
        }
    });

	$( document ).on("click", "#aceptar_solicitud_nodeducible", function(){
		console.log('indexcajas_chicasNODDLS', indexcajas_chicasNODDLS);
		switch( indexcajas_chicasNODDLS ){
			case 'PROG':
				var row = tabla_nueva_caja_chica_no_deducible.row( trsolicitudesNODDLS );
				$.post( url + "Cuentasxp/enviarcpp/", { idsolicitud : row.data().idsolicitud } ).done(function ( data ) {

					$("#aceptarSolNoDeducible").modal('toggle');
					tabla_nueva_caja_chica_no_deducible.row( trsolicitudes ).remove().draw();

				}).fail( function(){

				});
				break;
			case 'PROV':
				var row = tabla_nueva_caja_chica_no_deducible.row( trsolicitudesNODDLS );

				$.post( url + "Cuentasxp/enviarcpp/", { idsolicitud : row.data().idsolicitud } ).done(function ( data ) {

					$("#aceptarSolNoDeducible").modal('toggle');
					tabla_nueva_caja_chica_no_deducible.row( trsolicitudesNODDLS ).remove().draw();

				}).fail( function(){

				});
				break;
			default:
				var row = tabla_nueva_caja_chica_no_deducible.row( trsolicitudescch );
				$.post( url + "Cuentasxp/enviarcpp/", { idsolicitud : row.data().solicitudes[indexcajas_chicasNODDLS].idsolicitud } ).done(function ( data ) {
					$("#aceptarSolNoDeducible").modal('toggle');
					row.data().solicitudes.splice( indexcajas_chicasNODDLS, 1 );

					if( (row.data().solicitudes).length > 0 ){
						tabla_nueva_caja_chica_no_deducible.row( trsolicitudescch ).data( row.data() );
						row.child( detalles_caja_chica_noDeducible( row.data().solicitudes) ).show();
					}else{
						tabla_nueva_caja_chica_no_deducible.row( trsolicitudescch ).remove().draw( false );
					}

				}).fail( function(){

				});
				break;
		}
	});

    $( document ).on("click", "#sin_provisionar", function(){
        var row = tabla_nueva_proveedor.row( trsolicitudes );
        $.get( url + "Cuentasxp/provisionar_mal/"+row.data().idsolicitud ).done(function (data) {

            $("#modal_provisionar").modal('toggle');
            tabla_nueva_proveedor.row( trsolicitudes ).remove().draw();
 
        }).fail( function(){
 
        });
    });

    $( document ).on("click", "#provisionar", function(){
        var row = tabla_nueva_proveedor.row( trsolicitudes );
        $.get(url+"Cuentasxp/provisionar_ok/"+row.data().idsolicitud).done(function (data) {
           
            $("#modal_provisionar").modal('toggle');
            tabla_nueva_proveedor.row( trsolicitudes ).remove().draw();

        }).fail( function(){

        });
    });

    //FORMAULARIO PARA RECHAZAR LAS SOLICITUDES
    $("#infosol1").submit( function(e) {
        e.preventDefault();
    }).validate({
        submitHandler: function( form ) {

            var data = new FormData( $(form)[0] );
            var idsolrechazo;

            switch( indexcajas_chicas ){
                case 'PROV':
                    var row = tabla_nueva_proveedor.row( trsolicitudes );
                    idsolrechazo = row.data().idsolicitud;
                    break;
                case 'PROG':
                    var row = tabla_pagos_programados_nuevo.row( trsolicitudes );
                    idsolrechazo = row.data().idsolicitud;
                    break;
                default:
                    var row = tabla_nueva_caja_chica.row( trsolicitudescch );
                    idsolrechazo = row.data().solicitudes[indexcajas_chicas].idsolicitud;
                    break;
            }

            data.append("idsolicitud", idsolrechazo);

            $.ajax({
                url: url + "Cuentasxp/datos_para_rechazo1",
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                method: 'POST',
                type: 'POST', // For jQuery < 1.9
                success: function(data){
                    $("#myModalcomentario1").modal("toggle");
                    switch( indexcajas_chicas ){
                        case 'PROV':
                            tabla_nueva_proveedor.row( trsolicitudes ).remove().draw();
                            break;
                        case 'PROG':
                            tabla_pagos_programados_nuevo.row( trsolicitudes ).remove().draw();
                            break;
                        default:
                            row.data().solicitudes.splice( indexcajas_chicas, 1 );
                            if( (row.data().solicitudes).length > 0 ){
                                tabla_nueva_caja_chica.row( trsolicitudescch ).data( row.data() );
                                row.child( detalles_caja_chica( row.data().solicitudes) ).show();
                            }else{
                                tabla_nueva_caja_chica.row( trsolicitudescch ).remove().draw( false );
                            }
                            break;
                    }
                },error: function( ){
                    
                }
            });
        }
    });

	//FORMAULARIO PARA RECHAZAR LAS SOLICITUDES
	$("#infoReemForm").submit( function(e) {
		e.preventDefault();
	}).validate({
		submitHandler: function( form ) {

			var data = new FormData( $(form)[0] );
			var idsolrechazo;

			switch( indexreembolsos ){
				case 'PROV':
					var row = tabla_nueva_proveedor.row( trsolicitudes );
					idsolrechazo = row.data().idsolicitud;
					break;
				case 'PROG':
					var row = tabla_pagos_programados_nuevo.row( trsolicitudes );
					idsolrechazo = row.data().idsolicitud;
					break;
                default:
					var row = tabla_nueva_reembolsos.row( trsolicitudescch );
					idsolrechazo = row.data().solicitudes[indexreembolsos].idsolicitud;
					break;
			}

			data.append("idsolicitud", idsolrechazo);

			$.ajax({
				url: url + "Cuentasxp/datos_para_rechazo1",
				data: data,
				cache: false,
				contentType: false,
				processData: false,
				dataType: 'json',
				method: 'POST',
				type: 'POST', // For jQuery < 1.9
				success: function(data){
					$("#modalReembolso").modal("toggle");
					alert('Proceso realizdo correctamente');
					switch( indexreembolsos ){
						case 'PROV':
							tabla_nueva_proveedor.row( trsolicitudes ).remove().draw();
							break;
						case 'PROG':
							tabla_pagos_programados_nuevo.row( trsolicitudes ).remove().draw();
							break;
						default:
							row.data().solicitudes.splice( indexreembolsos, 1 );
							if( (row.data().solicitudes).length > 0 ){
								tabla_nueva_reembolsos.row( trsolicitudescch ).data( row.data() );
								row.child( detalles_caja_chica( row.data().solicitudes) ).show();
							}else{
								tabla_nueva_reembolsos.row( trsolicitudescch ).remove().draw( false );
							}
							break;
					}
				},error: function( ){

				}
			});
		}
	});

	//FORMAULARIO PARA RECHAZAR LAS SOLICITUDES
	$("#infoViaticoForm").submit( function(e) {
		e.preventDefault();
	}).validate({
		submitHandler: function( form ) {

			var data = new FormData( $(form)[0] );
			var idsolrechazo;

			switch( indexViaticos ){
				case 'PROV':
					var row = tabla_nueva_proveedor.row( trsolicitudes );
					idsolrechazo = row.data().idsolicitud;
					break;
				case 'PROG':
					var row = tabla_pagos_programados_nuevo.row( trsolicitudes );
					idsolrechazo = row.data().idsolicitud;
					break;
				default:
					var row = tabla_nueva_viaticos.row( trsolicitudesviaticos );
					idsolrechazo = row.data().solicitudes[indexViaticos].idsolicitud;
					break;
			}

			data.append("idsolicitud", idsolrechazo);

			$.ajax({
				url: url + "Cuentasxp/datos_para_rechazo1",
				data: data,
				cache: false,
				contentType: false,
				processData: false,
				dataType: 'json',
				method: 'POST',
				type: 'POST', // For jQuery < 1.9
				success: function(data){
					$("#modalViaticos").modal("toggle");
					alert('Proceso realizdo correctamente');
					switch( indexViaticos ){
						case 'PROV':
							tabla_nueva_proveedor.row( trsolicitudes ).remove().draw();
							break;
						case 'PROG':
							tabla_pagos_programados_nuevo.row( trsolicitudes ).remove().draw();
							break;
						default:
							row.data().solicitudes.splice( indexViaticos, 1 );
							if( (row.data().solicitudes).length > 0 ){
								tabla_nueva_viaticos.row( trsolicitudesviaticos ).data( row.data() );
								row.child( detalles_caja_chica( row.data().solicitudes) ).show();
							}else{
								tabla_nueva_viaticos.row( trsolicitudesviaticos ).remove().draw( false );
							}
							break;
					}
				},error: function( ){

				}
			});
		}
	});

	//FORMAULARIO PARA RECHAZAR LAS SOLICITUDES
	$("#infoNoDeducibleForm").submit( function(e) {
		e.preventDefault();
	}).validate({
		submitHandler: function( form ) {

			var data = new FormData( $(form)[0] );
			var idsolrechazo;

			switch( indexcajas_chicasNODDLS ){
				case 'PROV':
					var row = tabla_nueva_proveedor.row( trsolicitudes );
					idsolrechazo = row.data().idsolicitud;
					break;
				case 'PROG':
					var row = tabla_pagos_programados_nuevo.row( trsolicitudes );
					idsolrechazo = row.data().idsolicitud;
					break;
				default:
					console.log('indexcajas_chicasNODDLS', indexcajas_chicasNODDLS);
					console.log('trsolicitudescch', trsolicitudescch);
					var row = tabla_nueva_caja_chica_no_deducible.row( trsolicitudescch );
					idsolrechazo = row.data().solicitudes[indexcajas_chicasNODDLS].idsolicitud;
					break;
			}

			data.append("idsolicitud", idsolrechazo);

			$.ajax({
				url: url + "Cuentasxp/datos_para_rechazo1",
				data: data,
				cache: false,
				contentType: false,
				processData: false,
				dataType: 'json',
				method: 'POST',
				type: 'POST', // For jQuery < 1.9
				success: function(data){
					$("#modalNodeducible").modal("toggle");
					alert('Proceso realizdo correctamente');
					switch( indexcajas_chicasNODDLS ){
						case 'PROV':
							tabla_nueva_proveedor.row( trsolicitudes ).remove().draw();
							break;
						case 'PROG':
							tabla_pagos_programados_nuevo.row( trsolicitudes ).remove().draw();
							break;
						default:
							row.data().solicitudes.splice( indexcajas_chicasNODDLS, 1 );
							if( (row.data().solicitudes).length > 0 ){
								tabla_nueva_caja_chica_no_deducible.row( trsolicitudescch ).data( row.data() );
								row.child( detalles_caja_chica( row.data().solicitudes) ).show();
							}else{
								tabla_nueva_caja_chica_no_deducible.row( trsolicitudescch ).remove().draw( false );
							}
							tabla_nueva_caja_chica_no_deducible.ajax.reload();
							break;
					}
				},error: function( ){

				}
			});
		}
	});
    /**
     * INICIO
     * Fecha : 12-Agosto-2025 @author Efrain Martinez Muñoz <programador.anallista38@ciudadmaderas.com>
     * Formularios para cancelar la solicitud dependiendo del tipo de solicitud quese va a cancelar.
     */
    //FORMAULARIO PARA CANCELAR LAS SOLICITUDES PROVEEDORES, CAJA CHICA, TARJETAS DE CREDITO Y SOLICITUDES VIATICOS
    $("#infosolCancelar").submit( function(e) {
        e.preventDefault();
    }).validate({
        submitHandler: function( form ) {

            var data = new FormData( $(form)[0] );
            var idsolrechazo;

            switch( indexcajas_chicas ){
                case 'PROV':
                    var row = tabla_nueva_proveedor.row( trsolicitudes );
                    idsolrechazo = row.data().idsolicitud;
                    break;
                case 'PROG':
                    var row = tabla_pagos_programados_nuevo.row( trsolicitudes );
                    idsolrechazo = row.data().idsolicitud;
                    break;
                 case 'DEV':
                    var row = tabla_devoluciones_parcialidades.row( trsolicitudes );
                    idsolrechazo = row.data().idsolicitud;
                    break;
                default:
                    var row = tabla_nueva_caja_chica.row( trsolicitudescch );
                    idsolrechazo = row.data().solicitudes[indexcajas_chicas].idsolicitud;
                    break;
            }

            data.append("idsolicitud", idsolrechazo);

            $.ajax({
                url: url + "Cuentasxp/datosParaCancelar",
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                method: 'POST',
                type: 'POST', // For jQuery < 1.9
                success: function(data){
                    $("#myModalcomentarioCancelar").modal("toggle");
                    alert('PROCESO REALIZADO CORRECTAMENTE');
                    switch( indexcajas_chicas ){
                        case 'PROV':
                            tabla_nueva_proveedor.row( trsolicitudes ).remove().draw();
                            break;
                        case 'PROG':
                            tabla_pagos_programados_nuevo.row( trsolicitudes ).remove().draw();
                            break;
                        case 'DEV':
                            tabla_devoluciones_parcialidades.row( trsolicitudes ).remove().draw();
                            break;
                        default:
                            row.data().solicitudes.splice( indexcajas_chicas, 1 );
                            if( (row.data().solicitudes).length > 0 ){
                                tabla_nueva_caja_chica.row( trsolicitudescch ).data( row.data() );
                                row.child( detalles_caja_chica( row.data().solicitudes) ).show();
                            }else{
                                tabla_nueva_caja_chica.row( trsolicitudescch ).remove().draw( false );
                            }
                            break;
                    }
                },error: function(){

                }
            });
        }
    });

	//FORMAULARIO PARA CANCELAR LAS SOLICITUDES DE REEMBOLSO
	$("#infoReemFormCancelar").submit( function(e) {
		e.preventDefault();
	}).validate({
		submitHandler: function( form ) {

			var data = new FormData( $(form)[0] );
			var idsolrechazo;

			switch( indexreembolsos ){
				case 'PROV':
					var row = tabla_nueva_proveedor.row( trsolicitudes );
					idsolrechazo = row.data().idsolicitud;
					break;
				case 'PROG':
					var row = tabla_pagos_programados_nuevo.row( trsolicitudes );
					idsolrechazo = row.data().idsolicitud;
					break;
                case 'DEV':
					var row = tabla_devoluciones_parcialidades.row( trsolicitudes );
					idsolrechazo = row.data().idsolicitud;
					break;
				default:
					var row = tabla_nueva_reembolsos.row( trsolicitudescch );
					idsolrechazo = row.data().solicitudes[indexreembolsos].idsolicitud;
					break;
			}

			data.append("idsolicitud", idsolrechazo);

			$.ajax({
				url: url + "Cuentasxp/datosParaCancelar",
				data: data,
				cache: false,
				contentType: false,
				processData: false,
				dataType: 'json',
				method: 'POST',
				type: 'POST', // For jQuery < 1.9
				success: function(data){
					$("#modalReembolsoCancelar").modal("toggle");
					alert('PROCESO REALIZADO CORRECTAMENTE');
					switch( indexreembolsos ){
						case 'PROV':
							tabla_nueva_proveedor.row( trsolicitudes ).remove().draw();
							break;
						case 'PROG':
							tabla_pagos_programados_nuevo.row( trsolicitudes ).remove().draw();
							break;
                        case 'DEV':
							tabla_devoluciones_parcialidades.row( trsolicitudes ).remove().draw();
							break;
						default:
							row.data().solicitudes.splice( indexreembolsos, 1 );
							if( (row.data().solicitudes).length > 0 ){
								tabla_nueva_reembolsos.row( trsolicitudescch ).data( row.data() );
								row.child( detalles_caja_chica( row.data().solicitudes) ).show();
							}else{
								tabla_nueva_reembolsos.row( trsolicitudescch ).remove().draw( false );
							}
							break;
					}
				},error: function( ){

				}
			});
		}
	});

	//FORMAULARIO PARA CANCELAR LAS SOLICITUDES VIATICOS 
	$("#infoViaticoFormCancelar").submit( function(e) {
		e.preventDefault();
	}).validate({
		submitHandler: function( form ) {

			var data = new FormData( $(form)[0] );
			var idsolrechazo;

			switch( indexViaticos ){
				case 'PROV':
					var row = tabla_nueva_proveedor.row( trsolicitudes );
					idsolrechazo = row.data().idsolicitud;
					break;
				case 'PROG':
					var row = tabla_pagos_programados_nuevo.row( trsolicitudes );
					idsolrechazo = row.data().idsolicitud;
					break;
				default:
					var row = tabla_nueva_viaticos.row( trsolicitudesviaticos );
					idsolrechazo = row.data().solicitudes[indexViaticos].idsolicitud;
					break;
			}

			data.append("idsolicitud", idsolrechazo);

			$.ajax({
				url: url + "Cuentasxp/datosParaCancelar",
				data: data,
				cache: false,
				contentType: false,
				processData: false,
				dataType: 'json',
				method: 'POST',
				type: 'POST', // For jQuery < 1.9
				success: function(data){
					$("#modalViaticosCancelar").modal("toggle");
					alert('PROCESO REALIZADO CORRECTAMENTE');
					switch( indexViaticos ){
						case 'PROV':
							tabla_nueva_proveedor.row( trsolicitudes ).remove().draw();
							break;
						case 'PROG':
							tabla_pagos_programados_nuevo.row( trsolicitudes ).remove().draw();
							break;
						default:
							row.data().solicitudes.splice( indexViaticos, 1 );
							if( (row.data().solicitudes).length > 0 ){
								tabla_nueva_viaticos.row( trsolicitudesviaticos ).data( row.data() );
								row.child( detalles_viaticos( row.data().solicitudes) ).show();
							}else{
								tabla_nueva_viaticos.row( trsolicitudesviaticos ).remove().draw( false );
							}
							break;
					}
				},error: function(){

				}
			});
		}
	});

	//FORMAULARIO PARA CANCELAR LAS SOLICITUDES DE CAJA CHICA NO DEDUCIBLE
	$("#infoNoDeducibleFormCancelar").submit( function(e) {
		e.preventDefault();
	}).validate({
		submitHandler: function( form ) {

			var data = new FormData( $(form)[0] );
			var idsolrechazo;

			switch( indexcajas_chicasNODDLS ){
				case 'PROV':
					var row = tabla_nueva_proveedor.row( trsolicitudes );
					idsolrechazo = row.data().idsolicitud;
					break;
				case 'PROG':
					var row = tabla_pagos_programados_nuevo.row( trsolicitudes );
					idsolrechazo = row.data().idsolicitud;
					break;
				default:
					// console.log('indexcajas_chicasNODDLS', indexcajas_chicasNODDLS);
					// console.log('trsolicitudescch', trsolicitudescch);
					var row = tabla_nueva_caja_chica_no_deducible.row( trsolicitudescch );
					idsolrechazo = row.data().solicitudes[indexcajas_chicasNODDLS].idsolicitud;
					break;
			}

			data.append("idsolicitud", idsolrechazo);

			$.ajax({
				url: url + "Cuentasxp/datosParaCancelar",
				data: data,
				cache: false,
				contentType: false,
				processData: false,
				dataType: 'json',
				method: 'POST',
				type: 'POST', // For jQuery < 1.9
				success: function(data){
					$("#modalNodeducibleCancelar").modal("toggle");
					alert('PROCESO REALIZADO CORRECTAMENTE');
					switch( indexcajas_chicasNODDLS ){
						case 'PROV':
							tabla_nueva_proveedor.row( trsolicitudes ).remove().draw();
							break;
						case 'PROG':
							tabla_pagos_programados_nuevo.row( trsolicitudes ).remove().draw();
							break;
						default:
							row.data().solicitudes.splice( indexcajas_chicasNODDLS, 1 );
							if( (row.data().solicitudes).length > 0 ){
								tabla_nueva_caja_chica_no_deducible.row( trsolicitudescch ).data( row.data() );
								row.child( detalles_caja_chica( row.data().solicitudes) ).show();
							}else{
								tabla_nueva_caja_chica_no_deducible.row( trsolicitudescch ).remove().draw( false );
							}
							tabla_nueva_caja_chica_no_deducible.ajax.reload();
							break;
					}
				},error: function( ){

				}
			});
		}
	});
    /**
     * FIN
     * Fecha : 12-Agosto-2025 @author Efrain Martinez <programador.analista38@ciudadmaderas.com>
     */
	//TABLA DE PAGOS PROGRAMADOS
    var tabla_pagos_programados_nuevo;
    $("#tabla_pagos_programados").ready( function(){

        $('#tabla_pagos_programados thead tr:eq(0) th').each( function (i) {
            if( i > 0 && i < $('#tabla_pagos_programados thead tr:eq(0) th').length - 1 ){
                var title = $(this).text();
                $(this).html( '<input type="text" style="width:100%;" placeholder="'+title+'" />' );
                $( 'input', this ).on('keyup change', function () {

                    if (tabla_pagos_programados_nuevo.column(i).search() !== this.value ) {
                        tabla_pagos_programados_nuevo.column(i).search( this.value ).draw();
                        var total = 0;
                        var index = tabla_pagos_programados_nuevo.rows( { selected: true, search: 'applied' } ).indexes();
                        var data = tabla_pagos_programados_nuevo.rows( index ).data()
                        $.each(data, function(i, v){
                            total += parseFloat(v.cantidad);
                        });
                        var to1 = formatMoney(total);
                        document.getElementById("myText_1").value = to1;
                    }
                });
            }
        });

        $('#tabla_pagos_programados').on('xhr.dt', function ( e, settings, json, xhr ) {
            var total = 0;
            $.each( json.data,  function(i, v){
                total += parseFloat(v.cantidad);
            });
            var to = formatMoney(total);
            document.getElementById("tprogramado").value = to;
        });

        tabla_pagos_programados_nuevo = $("#tabla_pagos_programados").DataTable({
            dom: 'Brtip',
            "buttons": [{
                extend: 'excelHtml5',             
                text: '<i class="fas fa-file-excel"></i> EXPORTAR EXCEL',
                messageTop: "Lista de solicitudes por autorizar",
                attr: {
                    class: 'btn btn-success'       
                },
                exportOptions: {
                    columns: function (idx, data, node) {/** INICIO FECHA: 17-JUNIO-2025 | PANEL CP A USUARIOS ESPECIFICOS | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                        return idx > 0 && idx < $('#tabla_pagos_programados thead tr:eq(0) th').length - 1;
                    },/** FIN FECHA: 17-JUNIO-2025 | PANEL CP A USUARIOS ESPECIFICOS | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                    format: {
                        header: function (data, columnIdx) { 
                            data = data.replace( '<input type="text" style="width:100%;" placeholder="', '' );
                            data = data.replace( '">', '' );
                            return data;
                        }
                    }
                }
            }],
            "language":lenguaje,
            "processing": false,
            "pageLength": 10,
            "bAutoWidth": false,
            "bLengthChange": false,
            "ordering" :false,
            "scrollX": true,
            "bInfo": false,
            "searching": true,
            "columns": [{
                    "className": 'details-control',
                    "orderable": false,
                    "data" : null,
                    "defaultContent": '<i class="fas animacion fa-caret-right"></i>'
                },
                {
                    "width": "6%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.idsolicitud+'</p>'
                    }
                },
                {
                    "width": "8%",
                    "data" : function( d ){
                        p = "" ;

                        switch (d.programado) {
                            case '1':
                                p =  "<small class='label pull-center bg-gray'>MENSUAL</small>";
                                break;
                            case '2':
                                p =  "<small class='label pull-center bg-gray'>BIMESTRAL</small>";
                                break;
                            case '3':
                                p =  "<small class='label pull-center bg-gray'>TRIMESTRAL</small>";
                                break;
                            case '4':
                                p =  "<small class='label pull-center bg-gray'>CUATRIMESTRAL</small>";
                                break;
                            case '6':
                                p =  "<small class='label pull-center bg-gray'>SEMESTRAL</small>";
                                break;
                            case '7':
                                p =  "<small class='label pull-center bg-gray'>SEMANAL</small>";
                                break;
                            case '8':
                                p =  "<small class='label pull-center bg-gray'>QUINCENAL</small>";
                                break;
                        }
                        return p += d.intereses != null ? "<br><small class='label pull-center bg-orange'>CRÉDITO</small>" : "";
                    }
                },
                {
                    "width": "12%",
                    "data": function( d ){
                        return  '<p style="font-size: .7em">'+d.nombre + "</p>";
                    }
                },
                {
                    "width": "8%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">$ '+formatMoney(Number(d.cantidad_confirmada)+(isNaN(d.ppago)?0:Number((d.ppago-d.ptotales)*d.cantidad)) )+'</p>'
                    }
                },
                {
                    "width": "10%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.metoPago+'</p>'
                    }
                },
                {
                    "width": "8%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">$ '+formatMoney( d.cantidad )+'</p>'
                    }
                },
                {
                    "width": "8%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">$ '+formatMoney( ( d.tparcial != d.cantidad_confirmada ? d.cantidad_confirmada : d.tparcial ) )+'</p>'
                    }
                },  
                {
                    "width": "8%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">$ '+formatMoney( d.interes )+'</p>'
                    }
                },
                {
                    "width": "15%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">$ '+formatMoney( ( parseFloat( d.tparcial != d.cantidad_confirmada ? d.cantidad_confirmada : d.tparcial ) + Number(d.interes) ) )+'</p>'
                    }
                },
                {
                    "width": "12%",
                    "data" : function( d ){
                        return '<p style="font-size: .9em">'+d.ptotales+' / <small class="label pull-center bg-orange">'+d.ppago+'</small></p>';
                    }
                },
                {
                    "width": "8%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+formato_fechaymd(d.fecreg)+'</p>'
                    }
                },
                {
                    "width": "8%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+(d.fecha_fin ? formato_fechaymd(d.fecha_fin) : "<small class='label pull-center bg-red'>SIN DEFINIR</small>")+'</p>'
                    }
                },
                {
                    "width": "8%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+formato_fechaymd(d.proximo_pago)+'</p>'
                    }
                },
                {
                    "width": "10%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.nemp+'</p>'
                    }
                },
                {   
                    "width": "8%",
                    "data" : function( d ){
                        if( moment( d.proximo_pago ).diff( moment( fecha_hoy ), 'days' ) < 2 && d.estatus_ultimo_pago == null ){
                            return "<small class='label pull-center bg-red'>VENCIDO POR "+ ( -1*moment( d.proximo_pago ).diff( moment( fecha_hoy ), 'days' ) )+" DIAS</small>";
                        }else if( moment( d.proximo_pago ).diff( moment( fecha_hoy ), 'days' ) < 2 ){
                            switch( d.estatus_ultimo_pago ){
                                case "15":
                                    return "<small class='label pull-center bg-orange'>VENCIDO | POR CONFIRMAR PAGO</small>";
                                    break; 
                                case "1":
                                    return "<small class='label pull-center bg-orange'>VENCIDO | POR DISPERSAR</small>";
                                    break;
                                case "11":
                                case "0":
                                    return "<small class='label pull-center bg-orange'>VENCIDO | SUBIENDO PAGO</small>";
                                    break; 
                                default:
                                    return "<small class='label pull-center bg-orange'>VENCIDO | PAGO DETENIDO</small>";
                                    break;
                            }
                        }else{
                            return "<small class='label pull-center bg-green'>EN TIEMPO</small>"
                        }                        
                    }
                },
                {
                    "data": function( data ){
                        opciones = '<div class="btn-group-vertical" role="group">';
                        /**
                         * FECHA : 12-Agosto-2025 @author Efrain Martinez <programador.analista38@ciudadmaderas.com>
                         * Se agrega el boton para cancelar la solicitud desde este panel y se oculta el de rechazar solicitud.
                         */
                        opciones += '<button type="button" class="notification btn btn-primary btn-sm consultar_modal" style="" value="'+data.idsolicitud+'" data-value="SOL" title="Ver Solicitud"><i class="far fa-eye"></i>'+(data.visto == 0 ? '</i><span class="badge">!</span>' : '')+'</button>';
                        opciones += '<button type="button" class="btn btn-success btn-sm aceptar_pago" data-value="PROG" title="Aceptar Solicitud"><i class="fas fa-check"></i></button>';
                        opciones += '<button type="button" class="btn btn-warning btn-sm modifica_pago" data-value="PROG" value="'+data.idsolicitud+'" data-cantidad="'+data.cantidad+'" data-fecreg="'+data.fecreg+'" data-fecha_fin="'+data.fecha_fin+'" data-programado="'+data.programado+'" title="Modificar cantidad de pago"><i class="fas fa-pencil-alt"></i></button>';
                        // opciones += '<button type="button" class="btn btn-danger rechazar_pago btn-sm" data-value="PROG" title="Rechazar Solicitud"><i class="fas fa-close"></i></button>';
                        opciones += '<button type="button" class="btn btn-danger cancelar_pago btn-sm" data-value="PROG" title="Cancelar Solicitud"><i class="fas fa-ban"></i></button>';

                        return opciones + '</div>';
                    } 
                }]
                /*
                "ajax": {
                    "url": url + "Historial/ver_programados",
                    "type": "POST",
                    cache: false,
                    "data": function( d ){
                        return  $.extend( d, { "nomdepto": $("#cxp1_sel").val(), "idempresa": $("#cxp2_sel").val() } );
                    }
                }
                */
            });

            $('#tabla_pagos_programados tbody').on('click', 'td.details-control', function () {
                var tr = $(this).closest('tr');
                var row = tabla_pagos_programados_nuevo.row( tr );

                if ( row.child.isShown() ) {
                    row.child.hide();
                    tr.removeClass('shown');
                    $(this).parent().find('.animacion').removeClass("fa-caret-down").addClass("fa-caret-right");
                }
                else {
                    var informacion_adicional = '<div class="col-xs-12 col-md-12" style="text-align: padding:5px; justify; border-left: 1px solid #D2D6DE; border-right: 1px solid #D2D6DE; border-bottom: 1px solid #D2D6DE; border-radius: 0 0 5px 5px;">' +
                        '<p style="font-size: .9em"><b>CAPTURISTA: </b>' + row.data().nombre_capturista + '</p>' +
                        '<p style="font-size: .9em"><b>FORMA DE PAGO: </b>' + row.data().metoPago + '</p>' +
                        '<p style="font-size: .9em"><b>JUSTIFICACIÓN: </b>' + row.data().justificacion + '</p>' +
                    '</div>';

                    row.child( informacion_adicional ).show();
                    tr.addClass('shown');
                    $(this).parent().find('.animacion').removeClass("fa-caret-right").addClass("fa-caret-down");
                }
            });
            
            /** INICIO FECHA: 17-JUNIO-2025 | PANEL CP A USUARIOS ESPECIFICOS | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
            if ([2681, 2409].includes(idUsuario)) {
                if (typeof tabla_pagos_programados_nuevo !== 'undefined') {
                    tabla_pagos_programados_nuevo.ajax.url( url +"Cuentasxp/tabla_programados_espera" ).load();
                    $('#profile-tab').tab('show');
                }
            }/** FIN FECHA: 17-JUNIO-2025 | PANEL CP A USUARIOS ESPECIFICOS | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
        });
        $( document ).on("click", ".modifica_pago", function(){
            var fi=$(this).data("fecreg").split("-").reverse().join("\/");
            var ff=$(this).data("fecha_fin")?$(this).data("fecha_fin").split("-").reverse().join("\/"):null;
            $("#modal_cant_pprog .modal-title > b").text($(this).val());
            $("#idsol_pprog").val($(this).val());
            $("#cant_mod").val($(this).data("cantidad")).trigger('mask.maskMoney');

            $("#fechaini_mod").datepicker({
                todayBtn:  1,
                autoclose: true,
                format: 'dd/mm/yyyy'
            }).val( fi );

            $("#fechafin_mod").datepicker({
                todayBtn:  1,
                autoclose: true,
                format: 'dd/mm/yyyy'
            }).val( ff );

            $("#per_pago").val($(this).data("programado"));
            $("#modal_cant_pprog").modal("show");
        });

        $("#form_cant_pprog").submit(function(e) {
            e.preventDefault();
        }).validate({
            submitHandler: function( form ) {
                if (!confirm('¿Estás seguro de modificar el monto a pagar?')) {
                    return;
                }else{
                    var data = new FormData( $(form)[0] );
                    var resultado = enviar_post(data,"cambiar_cant_pprog");
                    if( !resultado.resultado ){
                        alert(resultado.msj);
                    }else{
                        $(form).trigger("reset");
                        alert(resultado.msj);
                        $("#modal_cant_pprog").modal( 'toggle' );
                        tabla_pagos_programados_nuevo.ajax.reload();
                    }
                }
            }
        });
    //FIN PAGOS PROGRAMADOS


    $(window).resize(function(){
        tabla_nueva_proveedor.columns.adjust();
        tabla_nueva_caja_chica.columns.adjust();
        tabla_nueva_proveedor.columns.adjust();
		tabla_nueva_viaticos.columns.adjust();
		tabla_pagos_programados_nuevo.columns.adjust(); /** FECHA: 17-JUNIO-2025 | PANEL CP A USUARIOS ESPECIFICOS | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
    });

    /** FECHA: 17-JUNIO-2025 | PANEL CP A USUARIOS ESPECIFICOS | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
    // Función para ajustar tabla actual
    function ajustarTablasEnContexto(tabla) {
        // Buscar todas las tablas en el tabla actual proporcionado
        $(tabla).find('.table, .table-responsive').each(function() {            
            // Verificar si es una tabla DataTables
            if ($.fn.DataTable && $.fn.DataTable.isDataTable(this)) {
                var tablaDT = $(this).DataTable();
                // 1. Ajustar columnas
                tablaDT.columns.adjust();
                // 2. Actualizar anchos de los encabezados
                $(this).find('thead th').each(function() {
                    $(this).css('width', $(this).width() + 'px');
                });
                // 3. Redibujar (sin resetear paginación)
                tablaDT.draw(false);
            }
        });
    }
    // Evento para el sidebar toggle
    $('.sidebar-toggle').click(function() {
        setTimeout(function() {
            ajustarTablasEnContexto('.tab-pane.active');
        }, 300);
    });
    // Evento para cuando cambia de tab
    $('[data-toggle="tab"]').on('shown.bs.tab', function(e) {
        setTimeout(function() {
            ajustarTablasEnContexto($(e.target).attr('href'));
        }, 50);
    });
    /** FECHA: 17-JUNIO-2025 | PANEL CP A USUARIOS ESPECIFICOS | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/

</script>
<?php
    require("footer.php");
?>
