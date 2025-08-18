<?php
    require("head.php");
    require("menu_navegador.php");
?> 
<style>
    input[type="checkbox"] { /** FECHA: 23-MAYO-2025 | APROBACION MULTIPLE DA | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
        cursor: pointer;
    }
    .justificacion-cell {
        font-size: 0.8em;
        max-height: 120px;
        overflow-y: auto;
        margin: 0;
    } /** FECHA: 23-MAYO-2025 | APROBACION MULTIPLE DA | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
</style>
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <div class="box">
                    <div class="box-header with-border">
                        <h3>SOLICITUD DE PAGOS</h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <h4>
                                    AUTORIZACIONES DE COMPRA <i class="far fa-question-circle faq" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" title="Autorizaciones" data-content="La tabla muestra todas las solicitudes pendientes de autorización para poder realizar una compra o pago a proveedor." data-placement="right"></i>
                                    | TOTAL POR AUTORIZAR: <label id="cantidadSeleccionada"></label><b id="myText_1"></b><!-- FECHA: 23-MAYO-2025 | APROBACION MULTIPLE DA | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                                </h4>
                                <div id="autorizacionDA"></div> <!-- FECHA: 23-MAYO-2025 | APROBACION MULTIPLE DA | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                                <div class="col-lg-4" style="padding: 0px; padding-right: 10px;">
                                    <select class="form-control" id="pbuscar">
                                    </select>
                                </div>
                                <table class="table table-striped" id="tabla_autorizaciones">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th> <!-- INICIO FECHA: 23-MAYO-2025 | APROBACION MULTIPLE DA | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                                                <input type="checkbox" style="width: 1.5em; height: 1.5em;" id="seleccionarTodoCheckbox" title="Seleccionar todas las solicitudes que estén para aprobar en la página actual." />
                                            </th> <!-- FIN FECHA: 23-MAYO-2025 | APROBACION MULTIPLE DA | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                                            <th></th>
                                            <th style="font-size: .8em">#</th>
                                            <th style="font-size: .8em">FOLIOS</th>
                                            <th style="font-size: .8em">PROYECTO / HCLAVE</th>
                                            <th style="font-size: .8em">SERVICIO/PARTIDA</th> <!-- /** FECHA: 08-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/ -->
                                            <th style="font-size: .8em">EMPRESA</th>
                                            <th style="font-size: .8em">FECHA</th>
                                            <th style="font-size: .8em">PROVEEDOR</th>
                                            <th style="font-size: .8em">CAPTURISTA</th>
                                            <th style="font-size: .8em">CANTIDAD</th>
                                            <th style="font-size: .8em">JUSTIFICACIÓN</th>
                                            <th style="font-size: .8em">ESTATUS</th>
                                            <th style="font-size: .8em"></th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-lg-12">
                                <h4>FACTURAS ACTIVAS <i class="far fa-question-circle faq" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" data-placement="right" title="Solicitudes Activas" data-content="Estas son todas las solicitudes de pago a proveedores que se encuentran el proceso de pago, verifica el status de la solicitud para saber en que parte del proceso se encuentra."></i></h4>
                                <form id="formulario_facturas_activas" autocomplete="off" action="<?= site_url("Reportes/solicitante_solPago_solActivas") ?>" method="post">
                                    <div class="col-lg-3">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fas fa-calendar-alt"></i> <b>INICIO</b></span>
                                            <input class="form-control fechas_filtro from" type="text" id="datepicker_from" />
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fas fa-calendar-alt"></i> <b>FIN</b></span>
                                            <input class="form-control fechas_filtro to" type="text" id="datepicker_to" />
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="input-group">
                                            <span class="input-group-addon text-bold">TOTAL: $</span>
                                            <input type="text"  name="myText_2" id="myText_2" class="form-control" style=" font-size: 16px; font-weight: bold;" disabled="disabled" readonly="readonly"> <!-- FECHA: 23-MAYO-2025 | APROBACION MULTIPLE DA | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                                        </div>
                                    </div>
                                    <div id="elementos_hidden"></div>
                                </form>
                                <table class="table table-striped" id="tblsolact">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th style="font-size: .9em">#</th> <!-- COLUMNA 0 -->
                                            <th style="font-size: .9em">EMPRESA</th> <!-- COLUMNA 1 -->
                                            <th style="font-size: .9em">PROYECTO</th> <!-- COLUMNA 2 -->
                                            <th style="font-size: .9em">SERVICIO/PARTIDA</th> <!-- COLUMNA 3 --> <!-- /** INICIO FECHA: 08-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/ -->
                                            <th style="font-size: .9em">FOLIO</th> <!-- COLUMNA 4 -->
                                            <th style="font-size: .9em">PROVEEDOR</th> <!-- COLUMNA 5 -->
                                            <th style="font-size: .9em">FEC FAC</th> <!-- COLUMNA 6 -->
                                            <th style="font-size: .9em">CAPTURA</th> <!-- COLUMNA 7 -->
                                            <th style="font-size: .9em">CANTIDAD</th> <!-- COLUMNA 8 -->
                                            <th style="font-size: .9em">PAGADO</th> <!-- COLUMNA 9 -->
                                            <th style="font-size: .9em">RESTANTE</th> <!-- COLUMNA 10 -->
                                            <th style="font-size: .9em">ESTATUS</th> <!-- COLUMNA 11 -->
                                            <th style="font-size: .9em"></th> <!-- COLUMNA 12 -->
                                            <th style="font-size: .9em">DESCRIPCION</th> <!-- COLUMNA 13 -->
                                            <th style="font-size: .9em">ORDEN DE COMPRA</th> <!-- COLUMNA 14 -->
                                            <th style="font-size: .9em">CONTRATO</th> <!-- COLUMNA 15 -->
                                            <th style="font-size: .9em">OFICINA</th> <!-- COLUMNA 16 --> <!-- /** FIN FECHA: 08-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/ -->
                                            <th></th> <!-- COLUMNA 17 -->
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div><!--end box-->
            </div>
        </div>
    </div>
</div>

<?php if ($this->session->userdata('inicio_sesion')['id'] == 1868) { ?>
    <div id="modal_solicitud_programado" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id="exampleModalLabel">NUEVO PAGO PROGRAMADO</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form id="frmnewsolp" method="post" action="#">
                                <div class="row">
                                    <div class="col-lg-6 form-group">
                                        <label for="proyecto">PROYECTO/DEPARTAMENTO<span class="text-danger">*</span></label>
                                        <select class="listado_proyectos form-control" name="proyecto" id="proyectopr" required></select>
                                    </div>
                                    <div class="col-lg-6 form-group">
                                        <label for="proyecto"><b>OFICINA/SEDE</b><span class="text-danger">*</span></label>
                                        <select class="listado_oficinas form-control select2" name="oficina" style="width: 100%;" id="oficinapr" required></select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 form-group">
                                        <label for="empresa">EMPRESA<span class="text-danger">*</span></label>
                                        <select name="empresa" id="empresapr" class="form-control lista_empresa" required></select>
                                        <input type="hidden" class="form-control" name="empresa" disabled>
                                    </div>
                                    <div class="col-lg-4 form-group">
                                        <label for="empresa">REFERENCIA <i class="far fa-question-circle faq" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" title="Referencia" data-content="En caso de requerir una referencia favor de colocarla." data-placement="right"></i></label>
                                        <input type="text" placeholder="Numeros o letras: FACT445000" class="form-control solo_letras_numeros" maxlength="25" id="referencia_pagopr" name="referencia_pago">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-5 form-group">
                                        <label for="proveedor_programado">PROVEEDOR<span class="text-danger">*</span> <i class="far fa-question-circle faq text-danger" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" title="Proveedores" data-content="A continuación se muestra solo los proveedores que no requieren factura o que se puede agregar posterior al pago." data-placement="right"></i></label>
                                        <select name="proveedor" class="form-control select2 select2-hidden-accessible lista_provedores_libres" style="width: 100%;" id="proveedor_programado" required></select>
                                        <input type="hidden" name="idproveedor" class="form-control" id="idproveedor">
                                    </div>
                                    <div class="col-lg-3 form-group">
                                        <br />
                                        <label for="proveedor">INTERÉS</label>
                                        <input type="checkbox" name="interes_dinamico" id="interes_dinamico" style="width:20px;height:20px;">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 form-group">
                                        <h5>DATOS DEL PAGO <i class="far fa-question-circle faq" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" title="Datos de la factura" data-content="En caso de no tener la factura deberás de llenar el campo de 'descripción', 'SubTtotal','IVA','Total' y 'Método de pago', así mismo, deberás de especificar en observaciones el por qué no tiene factura. Recuerda especificar si te darán factura después." data-placement="right"></i></h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4 form-group">
                                        <label for="fecha">FECHA INICIO<span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" min='2019-04-01' id="fechapr" name="fecha" placeholder="Fecha" value="" required>
                                    </div>
                                    <div class="col-lg-4 form-group">
                                        <label for="folio">FECHA FIN</label>
                                        <input type="date" class="form-control" placeholder="Fecha final" id="fecha_finalpr" name="fecha_final" value="">
                                    </div>
                                    <div class="col-lg-4 form-group">
                                        <label for="metpag">FORMA DE PAGO<span class="text-danger">*</span></label>
                                        <select class="form-control" id="forma_pagopr" name="forma_pago" required>
                                            <option value="">Seleccione una opción</option>
                                            <option value="TEA" data-value="TEA">Transferencia electrónica</option>
                                            <option value="DOMIC" data-value="DOMIC">Domiciliario</option>
                                            <option value="ECHQ">Cheque</option>
                                            <option value="EFEC">Efectivo</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4 form-group">
                                        <label for="total">TOTAL<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="totalpr" name="total" placeholder="Total" value="" required>
                                    </div>
                                    <div class="col-lg-4 form-group">
                                        <label for="total">MONEDA</label>
                                        <select class="form-control" id="monedapr" name="moneda" required>
                                            <option value="MXN" data-value="MXN">MXN</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-4 form-group">
                                        <label for="total">PERIODO DE PAGOS<span class="text-danger">*</span></label>
                                        <select class="form-control" id="metodo_pago" name="metodo_pago" required>
                                            <option value="">Seleccione un opción</option>
                                            <option value="7">SEMANAL</option>
                                            <option value="1">MENSUALMENTE</option>
                                            <option value="2">BIMESTRAL</option>
                                            <option value="3">TRIMESTRAL</option>
                                            <option value="4">CUATRIMESTRAL</option>
                                            <option value="6">SEMESTRAL</option>
                                            <option value="8">QUINCENAL</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 form-group">
                                        <label for="solobs">JUSTIFICACIÓN<span class="text-danger">*</span> <i class="far fa-question-circle faq" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" title="Observaciones de la solicitud" data-content="En caso de ser necesario, se podrán especificar datos, comentarios u observaciones para cuentas por pagar, DG, DP o para la dirección del departamento solicitante." data-placement="right"></i></label>
                                        <textarea class="form-control" id="solobspr" name="solobs" placeholder="Observaciones de la solicitud" required></textarea>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 form-group">
                                        <button type="submit" class="btn btn-success btn-block">GUARDAR</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

<?php } ?>
<div id="modal_formulario_solicitud" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="exampleModalLabel">NUEVA FACTURA - PAGO A PROVEEDOR</h4>
            </div>
            <div class="modal-body">

                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="tab" href="#generar_solicitud" role="tab" aria-controls="generar_solicitud" aria-selected="false">GENERAR SOLICITUD</a></li>
                        <li><a data-toggle="tab" href="#altprov" role="tab" aria-controls="altprov" aria-selected="false">ALTA DE PROVEEDOR</a></li>
                    </ul>
                </div>
                <div class="tab-content">
                    <div class="active tab-pane" id="generar_solicitud">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <h5><b>CARGAR DOCUMENTO XML</b></h5>
                                            <div class="input-group">
                                                <input type="file" name="xmlfile" id="xmlfile" class="form-control" accept="application/xml">
                                                <div class="input-group-btn">
                                                    <button class="btn btn-danger" type="button" id="cargar_xml"><i class="fas fa-upload"></i> CARGAR</button>
                                                    <button class="btn btn-warning" type="button" id="recargar_formulario_solicitud"><i class="fas fa-sync-alt"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <form id="frmnewsol" method="post" action="#">
                                    <div class="row">
                                        <div class="col-lg-12 row_tipo">
                                            <div class="form-group">
                                                <label><b>TIPO DE PAGO</b></label><br>
                                                <label class="radio-inline"><input type="radio" value="0" name="servicio1" class="default"><b>PROVEEDOR</b></label>
                                                <label class="radio-inline"><input type="radio" value="1" name="servicio1"><b>SERVICIO</b></label>
                                                <label class="radio-inline" id="caja_chica_label"><input type="radio" value="9" name="servicio1" class="caja_chica_label"><b>CAJA CHICA</b></label>
                                                <label class="radio-inline"><input id="tcredito" type="radio" value="11" name="servicio1"><b>CRÉDITO</b></label>
                                                <label class="radio-inline"><input id="rbintercambio" type="radio" value="10" name="servicio1"><b>INTERCAMBIO</b></label>
                                                <input type="hidden" name="servicio" class="caja_chica_label">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label><b>OTRAS OPCIONES</b><label>
                                                        <!--<label class="form-check-label" for="fecrecu" id="caja_chica_label"><input type="checkbox" value="1" name="caja_chica" id="caja_chica"> CAJA CHICA</label>-->
                                                        <label class="form-check-label checkbox-inline" for="fecrecu"><input type="checkbox" value="1" name="prioridad"><b> URGENTE</b></label>
                                                        <label class="form-check-label checkbox-inline" for="fecrecu"><input type="checkbox" value="1" name="tentra_factura" id="tentra_factura"><b> CON FACTURA</b></label>
                                                        <input type="hidden" name="tentra_factura" disabled>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 row_tipo">
                                            <div class="form-group">
                                                <label><b>TIPO DE INSUMO</b><span class="text-danger">*</span></label>
                                                <select name="insumo" class="lista_insumos" style="width: 100%;" id="insumo" placeholder="---Seleccione una opción---" disabled required></select> <!-- FECHA: 06-MARZO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6 form-group">
                                            <label for="proyecto"><b>PROYECTO/DEPARTAMENTO</b><span class="text-danger">*</span></label>
                                            <select class="listado_proyectos form-control select2" name="proyecto" style="width: 100%;" id="proyecto" required></select>
                                        </div>
                                        <div class="col-lg-6 form-group">
                                            <label for="proyecto"><b>OFICINA/SEDE</b><span class="text-danger">*</span></label>
                                            <select class="listado_oficinas form-control select2" name="oficina" style="width: 100%;" id="oficina" required></select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6 form-group">
                                            <label for="Etapa">ETAPA</label>
                                            <!--select name="etapa" class="form-control select2 select2-hidden-accessible lista_etapas" style="width: 100%;" id="Etapa" required></select-->
                                            <input name="etapa" class="form-control" id="Etapa">
                                        </div>
                                        <div class="col-lg-6 form-group">
                                            <label for="condominio">CONDOMINIO</label>
                                            <!--select name="condominio" class="form-control select2 select2-hidden-accessible lista_condominios" style="width: 100%;" id="condominio" required></select-->
                                            <input name="condominio" class="form-control" id="condominio">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 form-group">
                                            <label for="tServicio_partida"><b>TIPO SERVICIO/PARTIDA</b><span class="text-danger">*</span></label>
                                            <select class="listado_tServicio_partida form-control select2" name="tServicio_partida" style="width: 100%;" id="listado_tServicio_partida" required></select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6 form-group">
                                            <label for="proyecto">HOMOCLAVE<span class="text-danger">*</span></label>
                                            <select name="homoclave" id="homoclave" class="form-control" required></select>
                                        </div>
                                        <div class="col-lg-6 form-group">
                                            <label for="empresa">EMPRESA<span class="text-danger">*</span></label>
                                            <select name="empresa" id="empresa" class="form-control lista_empresa" required></select>
                                            <input type="hidden" class="form-control" name="empresa" disabled>
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4 form-group">
                                            <label for="empresa">REFERENCIA <i class="far fa-question-circle faq" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" title="Referencia" data-content="En caso de requerir una referencia favor de colocarla." data-placement="right"></i></label>
                                            <input type="text" class="form-control solo_letras_numeros" maxlength="40" name="referencia_pago" placeholder="Numeros o letras: FACT445000">
                                        </div>
                                        <div class="col-lg-8 form-group">
                                            <label for="proveedor">PROVEEDOR<span class="text-danger">*</span> <i class="far fa-question-circle faq text-danger" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" title="Proveedores" data-content="A continuación se muestra solo los proveedores que no requieren factura o que se puede agregar posterior al pago." data-placement="right"></i></label>
                                            <select name="proveedor" class="form-control select2 select2-hidden-accessible lista_provedores_libres" style="width: 100%;" id="proveedor" required></select>
                                            <input type="hidden" name="idproveedor" class="form-control" id="idproveedor">
                                        </div>
                                    </div>
                                    <div class="row contratos_proveedores">
                                        <div class="col-lg-6 form-group">
                                            <label for="empresa">CONTRATOS VIGENTES<span class="text-danger">*</span></label>
                                            <select name="cproveedor" id="cproveedor" class="form-control" disabled>
                                                <option value="" data-restante="">Seleccione una opción</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-6 form-group">
                                            <label for="empresa">ORDEN DE COMPRA<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="orden_compra" id="orden_compra" placeholder="Orden de compra" required>
                                        </div>
                                        <div class="col-lg-6 form-group">
                                            <label for="crecibo">CONTRA RECIBO<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="crecibo" id="crecibo" placeholder="Contra Recibo" required>
                                        </div>
                                        <div class="col-lg-6 form-group">
                                            <label for="requisicion">REQUISICIÓN<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="requisicion" id="requisicion" placeholder="Contra Recibo" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 form-group">
                                            <h5>DATOS DEL TICKET / FACTURA <i class="far fa-question-circle faq" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" title="Datos de la factura" data-content="En caso de no tener la factura deberás de llenar el campo de 'descripción', 'SubTtotal','IVA','Total' y 'Método de pago', así mismo, deberás de especificar en observaciones el por qué no tiene factura. Recuerda especificar si te darán factura después." data-placement="right"></i></h5>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6 form-group">
                                            <label for="fecha">FECHA<span class="text-danger">*</span></label>
                                            <input type="date" class="form-control" min='2019-04-01' id="fecha" name="fecha" placeholder="Fecha" value="" required>
                                        </div>
                                        <div class="col-lg-6 form-group">
                                            <label for="folio">FOLIO</label>
                                            <input type="text" class="form-control" placeholder="Folio" id="folio" name="folio" value="">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 form-group">
                                            <label for="descr">DESCRIPCIÓN</label>
                                            <textarea rows="10" class="form-control" id="descr" name="descr" placeholder="Descripción" readonly></textarea>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4 form-group">
                                            <label for="subtotal">SUBTOTAL</label>
                                            <input type="text" class="form-control" id="subtotal" name="subtotal" placeholder="SubTotal" value="" readonly>
                                        </div>
                                        <div class="col-lg-4 form-group">
                                            <label for="iva">IVA</label>
                                            <input type="text" class="form-control" id="iva" name="iva" placeholder="IVA" value="" readonly>
                                        </div>
                                        <div class="col-lg-4 form-group">
                                            <label for="total">TOTAL<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="total" name="total" placeholder="Total" value="" required>
                                        </div>
                                    </div>
                                    <div class="row">
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
                                            <label for="metpag">MÉTODO DE PAGO</label>
                                            <input type="text" class="form-control" placeholder="Método de Pago" id="metpag" name="metpag" value="" readonly>
                                        </div>
                                        <div class="col-lg-4 form-group">
                                            <label for="metpag">FORMA DE PAGO<span class="text-danger">*</span></label>
                                            <select class="form-control" id="forma_pago" name="forma_pago" required>
                                                <option value="">Seleccione una opción</option>
                                                <option value="ECHQ" data-value="ECHQ">Cheque</option>
                                                <option value="TEA" data-value="TEA">Transferencia electrónica</option>
                                                <option value="MAN" data-value="MAN">Manual</option>
                                                <option value="DOMIC" data-value="DOMIC">Domiciliario</option>
                                                <option value="EFEC" data-value="EFEC">Efectivo</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 form-group">
                                            <label for="obse">OBSERVACIONES FACTURA <i class="far fa-question-circle faq" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" title="Observaciones de la factura" data-content="En este campo pueden ser ingresados datos opcionales como descuentos, observaciones, descripción de la operación, etc." data-placement="right"></i></label><br>
                                            <textarea class="form-control" id="obse" name="obse" placeholder="Observaciones"></textarea>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 form-group">
                                            <label for="solobs">JUSTIFICACIÓN<span class="text-danger">*</span> <i class="far fa-question-circle faq" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" title="Observaciones de la solicitud" data-content="En caso de ser necesario, se podrán especificar datos, comentarios u observaciones para cuentas por pagar, DG, DP o para la dirección del departamento solicitante." data-placement="right"></i></label>
                                            <textarea class="form-control" id="solobs" name="solobs" placeholder="Observaciones de la solicitud" required></textarea>
                                        </div>
                                    </div>
                                    <div class="row" id="row_cc">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label for="metpag">RESPONSABLE</label>
                                                <select class="form-control" id="responsable_cc" name="responsable_cc" disabled required></select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 form-group">
                                            <button type="submit" class="btn btn-success btn-block">GUARDAR</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="altprov">
                        <form id="invitacion_proveedores" method="post" action="#">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <input type="email" name="correo_invitacion" placeholder="Correo" class="form-control" required>
                                        <button type="submit" id="btnSnd" class="btn btn-block btn-block">Enviar</button>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <p class="text-justify">Ingresa el correo del proveedor y se le enviará un link para que se registre en nuestro sistema. Si deseas dar de alta al proveedor de manera manual solicita al departamento de cuentas por pagar su alta <a href="mailto:cpp@ciudadmaderas.com">AQUÍ</a></p>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="modal_comentarios_especiales" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">OPCIONES ADICIONALES</h4>
            </div>
            <div class="modal-body">
                <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" href="#coment_add">COMENTARIO ADICIONAL</a></li>
                    <li><a data-toggle="tab" href="#factoraje">CAMBIAR A FACTORAJE</a></li>
                    <li><a data-toggle="tab" href="#destajo">DESTAJO / CONSTRUCCION</a></li>
                </ul>
                <div class="tab-content">
                    <div id="coment_add" class="tab-pane fade in active">
                        <form id="formulario_especial" method="post">
                            <div class="row">
                                <div class="col-lg-12 form-group">
                                    <label>TIPO DE MOVIMIENTO<span class="text-danger">*</span></label>
                                    <select class="form-control" name="rubro_especial" required>
                                        <option value="">Seleccione un opción</option>
                                        <option value="INTERCAMBIO">INTERCAMBIO</option>
                                        <option value="REFACTURACION">REFACTURACION</option>
                                        <option value="OTRO">OTRO</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 form-group">
                                    <label>COMENTARIO<span class="text-danger">*</span></label>
                                    <textarea class="form-control" rows="5" name="comentario_especial" maxlength="50" required></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-8 col-lg-offset-2 form-group">
                                    <button type="submit" class="btn btn-block btn-success">AGREGAR</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div id="factoraje" class="tab-pane fade">
                        <form id="formulario_fact" method="post">
                            <div class="row">
                                <div class="col-lg-4 form-group">
                                    <label>TIPO DE MOVIMIENTO<span class="text-danger">*</span></label>
                                    <select class="form-control" name="tipo_factoraje" required>
                                        <option value="">Seleccione un opción</option>
                                        <option value="FACT BAJIO">FACT BAJIO</option>
                                        <option value="FACT BANREGIO">FACT BANREGIO</option>
                                    </select>
                                </div>
                                <div class="col-lg-4 form-group">
                                    <label>FECHA DE PUBLICACION<span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" name="fecha_publicacion" required>
                                </div>
                                <div class="col-lg-4 form-group">
                                    <label>FECHA DE PAGO<span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" name="dias_factoraje" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 form-group">
                                    <label>COMENTARIO<small class="text-danger">(opcional)</small></label>
                                    <textarea class="form-control" rows="5" name="comentario_especial" maxlength="50"></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-8 col-lg-offset-2 form-group">
                                    <button type="submit" class="btn btn-block btn-success">AGREGAR</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div id="destajo" class="tab-pane fade">
                        <form id="formulario_destajo" method="post">
                            <div class="row">
                                <div class="col-lg-6 col-lg-offset-3 form-group">
                                    <label>FECHA DE PAGO<span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" name="fecha_publicacion" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-8 col-lg-offset-2 form-group">
                                    <button type="submit" class="btn btn-block btn-success">CAMBIAR</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-alert" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content text-center">
            <div style="padding: 5px;" class="modal-header bg-red">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title" id="exampleModalCenterTitle">¡AVISO!</h3>
            </div>
            <div class="modal-body">
                <h5> ¿Deseas eliminar esta solicitud? </h5>
                <input class="datos_modal_alert" type="hidden" value="" />
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" data-dismiss="modal">CANCELAR</button>
                <button type="button" class="btn btn-danger borrar_solicitud_modal">ELIMINAR</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade modal-alertas" id="askReadyModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static"> <!-- FECHA: 23-MAYO-2025 | APROBACION MULTIPLE DA | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <label class="modal-title" id="exampleModalLabel">APROBAR MULTIPLES REGISTROS</label>
            </div>
            <div class="modal-body text-center">
                <h4><b>¿ESTÁ SEGURO DE QUE DESEA APROBAR LOS REGISTROS?</b></h4>
                <div id="elementsToApprove" style="margin-top: 2em;"></div>
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-lg-6">
                        <button type="button" class="btn btn-danger btn-block" data-dismiss="modal">CERRAR</button>
                    </div>
                    <div class="col-lg-6">
                        <button type="button" class="btn btn-success btn-block" onclick="aceptarEnvio()">CONFIRMAR</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> <!-- FECHA: 23-MAYO-2025 | APROBACION MULTIPLE DA | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->

<script>
    var limite_cajachica = 0;
    var data_usuario = <?php echo json_encode($this->session->userdata("inicio_sesion")); ?>;
    let titulos_intxt = [];
    var documento_xml = null;
    var link_post = "";
    var depto = "";
    var idsolicitud = 0;
    var _data1 = [];
    var _data2 = [];
    var _data3 = [];
    var depto_excep = ['ADMINISTRACION', 'FUNDACION', 'SUB DIRECCION'];
    var depto_excep_proyecto = ['CONSTRUCCION', 'JARDINERIA', 'OOAM', 'OOAM TECNICO', 'OOAM ADMINISTRATIVO'];
    var idusuario_capturista = null;
    var contratos = null;
    var valor_input = Array($('#tblsolact th').length);
    var lista_proyectos_depto = [];
    var caja_chica;
    var tcredito;
    var empresas = [];
    const regex = /^[0-9]+$/;
    var titulo_encabezados = [];
    var num_columnas = [];
    var fechaInicial;
    var fechaFinal;

    $('.solo_numeros').keyup(function(e) {
        if (/\D/g.test(this.value)) {
            // Filter non-digits from input value.
            this.value = this.value.replace(/\D/g, '');
        }
    });

    //Initialize Select2 Elements
    $('.fechas_filtro').datepicker({
        format: 'dd/mm/yyyy',
        orientation: 'bottom auto'
    });

    $('input[type=radio][name=servicio1]').change(function() {
        if (documento_xml == null) {
            //RECUPERAMOS LA INFORMACION DE LAS EMPRESAS CARGADAS
            if ($("#empresa option").length != empresas.length) {
                $(".lista_empresa").html('').append('<option value="">Seleccione una opción</option>');
                $.each(empresas, function(i, v) {
                    $(".lista_empresa").append('<option value="' + v.idempresa + '" data-value="' + v.rfc + '">' + v.nombre + '</option>');
                });
            }

            //PROCEDIMIENTOS PARA HABILITAR EL RESPONSABLE DE CAJA CHICA
            $("#responsable_cc").html('<option value="">Selecciones una opción</option>');
            if (this.value == '9')
                $.each(caja_chica, function(i, v) {
                    $("#responsable_cc").append('<option value="' + v.idusuario + '">' + v.nombres + " " + v.apellidos + '</option>');
                });

            if (this.value == '11')
                $.each(tcredito, function(i, v) {
                    $("#responsable_cc").append('<option value="' + v.idusuario + '" data-rfcempresa="' + v.rfc + '" data-tempresa="' + v.idempresa + '">' + v.nresponsable + '</option>');
                });

            //VERIFICAMOS SI ESTA ES CORRECTO PARA HABILITAR EL RESPONSABLE DE CAJA CHICA
            $("#responsable_cc").prop("disabled", !['9', '11'].includes(this.value)).val(''); /** FECHA: 08-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/

            /***************************************************************************/
            /** INICIO FECHA: 06-MARZO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
            $("#insumo")[0].tomselect.setValue(""); // Vacía la selección
            $("#insumo")[0].tomselect[this.value == '9' ? 'enable' : 'disable'](); // Habilita si es '9', deshabilita si no
            /** FIN FECHA: 06-MARZO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/

            //DETERMINAMOS EL LISTADO DE PROVEEDORES ENE L CATALOGO
            $("input[type=hidden][name='servicio']").val(this.value).prop('disabled', false);
            switch (this.value) {
                case '11':
                    AutocompleteProveedor(_data3);
                    break;
                case '9':
                    AutocompleteProveedor(_data2);
                    break;
                default:
                    AutocompleteProveedor(_data1);
                    break;
            }
            /**************************************************/

            if ($('#proyecto').data('select2')) {
                $('#proyecto').select2('destroy');
            }

            $('#oficina').val('').empty();
            $("#oficina").html("<option value=''>Seleccione una opción</option>")

            $("#proyecto").html('<option value="">Seleccione una opción</option>');
            if ($(this).val() == 10) {
                $("#forma_pago").html('<option value="">Seleccione una opción</option> <option value="INTERCAMBIO">Intercambio</option>');

            } else {
                $("#forma_pago").html('<option value="">Seleccione una opción</option>\
                <option value="ECHQ" data-value="ECHQ">Cheque</option>\
                <option value="TEA" data-value="TEA">Transferencia electrónica</option>\
                <option value="MAN" data-value="MAN">Manual</option>\
                <option value="DOMIC" data-value="DOMIC">Domiciliario</option>\
                <option value="EFEC" data-value="EFEC">Efectivo</option>');

            }

            // $.each( lista_proyectos_depto, function( i, v){
            //     $("#proyecto").append('<option value="'+v.idProyectos+'">'+v.concepto+'</option>');
            // });

            obtenerProyectosDepartemento({}); /** FECHA: 08-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
            $("#proyecto").select2(); /** FECHA: 08-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
        }
    });

    var today = new Date();
    var yesterday = new Date(today);
    yesterday.setDate(today.getDate() - 40);
    var fecha_minima = yesterday.toISOString().substr(0, 10);
    var tipoInsumoArray = []; /** FECHA: 06-MARZO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */

    $('input[name="dias_factoraje"]').attr("min", (new Date(today)).toISOString().substr(0, 10))

    $(document).ready(function() {

        /**@author Dante Aldair <programador.analista18@ciudadmaderas.com> 
         * Configuracion de datapicker para una fecha predeterminada al inicio de la vista
         */

        const fechaActual = new Date().toLocaleDateString('es-ES', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric'
        });

        // Configura el datepicker inicial
        $('#datepicker_from').datepicker({
            dateFormat: 'dd/mm/yyyy', // Formato de fecha deseado
            defaultDate: '01/01/' + (new Date().getFullYear() - 1), // Establece la fecha predeterminada
            onSelect: function(dateText) {
                $(this).val(dateText); // Establece el valor del campo de entrada con la fecha seleccionada
            }
        });
        // Asigna la fecha predeterminada al campo de entrada
        $('#datepicker_from').datepicker('setDate', '01/01/' + (new Date().getFullYear() - 1));
        fechaInicial = $('#datepicker_from').val();
        // Configura el datepicker inicial
        $('#datepicker_to').datepicker({
            dateFormat: 'dd/mm/yyyy', // Formato de fecha deseado
            defaultDate: fechaActual, // Establece la fecha predeterminada
            onSelect: function(dateText) {
                $(this).val(dateText); // Establece el valor del campo de entrada con la fecha seleccionada
            }
        });
        // Asigna la fecha predeterminada al campo de entrada
        $('#datepicker_to').datepicker('setDate', fechaActual);
        fechaFinal = $('#datepicker_to').val();
        /*Final de los cambios por parte de Dante Aldair para configuracion de DataPickers*/

        $('#rbintercambio, #tcredito').prop('disabled', true);
        $.getJSON(url + "Listas_select/lista_proveedores_libres").done(function(data) {
            if ((data.listado_bloqueados).length > 0) {
                var listado = '<ul>';

                $.each(data.listado_bloqueados, function(i, v) {
                    listado += '<li>' + v.nombre + '</li>';
                });

                listado += '</ul>';

                $.notify.addStyle('vacantes', {
                    html: "<div><div class='clearfix alert alert-danger'>" +
                        "<div class='title' data-notify-html='title'/>" + listado +
                        "</div></div>"
                });

                $.notify({
                    title: "<h5><strong><i class='fas fa-exclamation text-danger'></i> ATENCIÓN</strong></h5><hr/> Hay (" + ((data.listado_bloqueados).length) + ") proveedore(s) bloqueados. Es necesario que cargue las facturas correspondientes."
                }, {
                    style: 'vacantes',
                    autoHide: true,
                    timer: 1000000,
                    clickToHide: true
                });
            }

            $("#oficina").append('<option value="">Seleccione una opción</option>');
            $("#oficinapr").append('<option value="">Seleccione una opción</option>');

            depto = data.departamento;

            lista_proyectos_depto.push(data.lista_proyectos_departamento);
            // lista_proyectos_depto.push(data.lista_proyectos_depto); /** FECHA: 08-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/

            $(".lista_condominios").append('<option value="N/A">N/A</option>');
            $.each(data.listado_condominios, function(i, v) {
                $(".lista_condominios").append('<option value="' + v.nombre + '">' + v.nombre + '</option>');
            });

            $(".lista_etapas").append('<option value="N/A">N/A</option>');
            $.each(data.listado_etapas, function(i, v) {
                $(".lista_etapas").append('<option value="' + v.nombre + '">' + v.nombre + '</option>');
            });

            $("#homoclave").html('<option value="N/A">N/A</option>');
            $.each(data.lista_proyectos_homoclaves, function(i, v) {
                $("#homoclave").append('<option value="' + v.concepto + '">' + v.concepto + '</option>');
            });

            $("#responsable_cc").html('');
            if (data.listado_responsable.length > 1) {
                $("#responsable_cc").append('<option value="">Seleccione una opción</option>');
            }

            caja_chica = data.listado_responsable;
            tcredito = data.rtcredito;

            switch (data.departamento) {
                case 'CONSTRUCCION':
                    limite_cajachica = 2000;
                    $("#forma_pago").append('<option value="FACT BAJIO" data-value="EFEC">Facturaje Bajio</option>');
                    $("#forma_pago").append('<option value="FACT BANREGIO" data-value="EFEC">Facturaje Banregio</option>');
                    break;
                case 'SUB DIRECCION':
                    limite_cajachica = 10000;
                    break;
                default:
                    limite_cajachica = 2000;
                    break;
            }

            /** INICIO FECHA: 06-MARZO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
            $.each(data.listado_insumos, function(i, v) {
                tipoInsumoArray.push({
                    value: v.idinsumo,
                    label: v.insumo,
                    opcionesData: {
                        dinamic: v.dinamico
                    }
                });
            });
            inputTomSelect('insumo', tipoInsumoArray, {
                valor: 'value',
                texto: 'label',
                opcDataSelect: 'opcionesData'
            });
            /** FIN FECHA: 06-MARZO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */

            depto = data.departamento;

            // switch( depto ){
            //     case "COMERCIALIZACION":
            //         $("#pbuscar, #pcurso").append( '<option value="10">COMERCIALIZACION</option>' );
            //         break;
            //     case "CONTABILIDAD":
            //         $("#pbuscar, #pcurso").append( '<option value="5">CONTABILIDAD</option>' );
            //         $("#pbuscar, #pcurso").append( '<option value="11">PRESTAMOS POR ADEUDO</option>' );
            //         $("#pbuscar, #pcurso").append( '<option value="4">NOMINAS</option>' );
            //         $("#pbuscar, #pcurso").append( '<option value="12">BONOS</option>' );
            //         $("#pbuscar, #pcurso").append( '<option value="6">IMPUESTOS</option>' );
            //         break;
            //     case "CAPITAL HUMANO":
            //         $("#pbuscar, #pcurso").append( '<option value="1">GASTOS DE CAPITAL HUMANO</option>' );
            //         $("#pbuscar, #pcurso").append( '<option value="2">GASTOS ADMON DE OFICINA</option>' );
            //         $("#pbuscar, #pcurso").append( '<option value="3">FINIQUITOS, PRESTAMOS, BONOS</option>' );
            //         $("#pbuscar, #pcurso").append( '<option value="4">NOMINAS</option>' );
            //         break;
            //     case "OOAM":
            //         $("#pbuscar, #pcurso").append( '<option value="15">OOAM</option>' );
            //         break;
            //     case "COMPRAS":
            //         $("#pbuscar, #pcurso").append( '<option value="16">COMPRAS</option>' );
            //         $("#pbuscar, #pcurso").append( '<option value="17">MERCADOTECNIA</option>' );
            //         break;
            //     case "DIRECCION GENERAL":
            //         $("#pbuscar, #pcurso").append( '<option value="7">DIRECCION GENERAL</option>' );
            //         $("#pbuscar, #pcurso").append( '<option value="13">COSOMI</option>' );
            //         $("#pbuscar, #pcurso").append( '<option value="14">GESTION</option>' );
            //         //$("#pbuscar, #pcurso").append( '<option value="9">NYSSA</option>' );
            //         break;
            // }

            $('.select2').select2();

            let current_info = <?= $this->session->userdata("inicio_sesion")['id']; ?>;
            if (data.departamento == 'ADMINISTRACION' || current_info == '1868') {

                table_autorizar.button().add(2, {
                    text: '<i class="fas fa-clock"></i> PAGO PROGRAMADO',
                    attr: {
                        class: 'btn btn-primary abrir_nueva_solicitud_programada'
                    }
                });
            }
        });

        $(".departamentoSolicitante").append('<option value="">Seleccione una opción</option>');
        $.getJSON(url + "Listas_select/lista_departamento").done(function(data) {
            $.each(data, function(i, item) {
                $('.departamentoSolicitante').append('<option value="' + item.iddepartamentos + '" >' + item.departamento + '</option>');
            });
        });
    });

    function obtenerListadoOficinas(params) {
        inputValue = params.input === 'oficina' ? $('#proyecto').val() : $('#proyectopr').val()
        $.ajax({
            url: url + "Listas_select/Lista_oficinas_sedes",
            data: {
                idProyecto: regex.test(inputValue) ? inputValue : 0
            },
            type: 'POST', // For jQuery < 1.9
            success: function(data) {
                data = JSON.parse(data);
                $(`#${params.input}`).empty();
                $(`#${params.input}`).append('<option value="">Seleccione una opción</option>');
                if (data.lista_oficinas_sedes)
                    data.lista_oficinas_sedes.forEach(function(v) {
                        $(`#${params.input}`).append('<option value="' + v.idOficina + '">' + v.oficina + '</option>');
                    })
                if (params.idOficina)
                    $(`#${params.input}`).val(params.idOficina)
            }
        });
    }

    $('#proyecto').change(function() {
        obtenerListadoOficinas({
            input: 'oficina'
        })
    })
    $('#proyectopr').change(function() {
        obtenerListadoOficinas({
            input: 'oficinapr'
        })
    })

    $("#pbuscar").change(function() {
        table_autorizar.ajax.reload();
        solicitudesArray = [];
        $("#enviarMultiplesAprobaciones").hide();
        $("#cantidadSeleccionada").html('');
        $("#badgeCantidadSol").html('');
    });

    function recargar_provedores() {

        $(".lista_provedores_libres").append('<option value="">Seleccione una opción</option>');
        $.getJSON(url + "Listas_select/lista_proveedores_libres").done(function(data) {
            $(".lista_provedores_libres").html('');
            $(".lista_provedores_libres").append('<option value="">Seleccione una opción</option>');

            _data1 = [];

            $.each(data.listado_disponibles, function(i, v) {
                //LISTADO DE PROVEEDORES TODOS
                /**
                 * INICIO
                 * FECHA : 27-MAYO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com
                 * Se agrego la cuenta bancaria despues del nombre del bano de cada proveedor 
                 */
                if (!(v.nombre.includes('GASTO NO DEDUCIBLE')) && !(v.nombre.includes('INVOICE'))) {
                    _data1.push({
                        value: v.idproveedor,
                        excp: v.excp,
                        label: v.nombre + " - " + v.nom_banco + (v.cuenta ? " - " + v.cuenta : ''),
                        rfc: v.rfc,
                        tinsumo: v.tinsumo
                    });
                }

                //COMPROBACION SIN DEDUCIBLE EN FACTURAS DE CREDITO
                if ((v.nombre.includes('GASTO NO DEDUCIBLE') && depto == 'ADMINISTRACION') || v.nombre.includes('INVOICE')) {
                    _data3.push({
                        value: v.idproveedor,
                        excp: v.excp,
                        label: v.nombre + " - " + v.nom_banco + (v.cuenta ? " - " + v.cuenta : ''),
                        rfc: v.rfc,
                        tinsumo: v.tinsumo
                    });
                }

                //LISTADO DE PROVEEDORES CAJA CHICA + NO DEDUCIBLE
                _data2.push({
                    value: v.idproveedor,
                    excp: v.excp,
                    label: v.nombre + " - " + v.nom_banco + (v.cuenta ? " - " + v.cuenta : ''),
                    rfc: v.rfc,
                    tinsumo: v.tinsumo
                });

                /**
                 * FIN
                 * FECHA : 27-MAYO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com 
                 */
            });

            AutocompleteProveedor(_data1);
            $(".lista_empresa").html('').append('<option value="">Seleccione una opción</option>');
            empresas = data.empresas
            $.each(data.empresas, function(i, v) {
                $(".lista_empresa").append('<option value="' + v.idempresa + '" data-value="' + v.rfc + '">' + v.nombre + '</option>');
            });
            contratos = data['contratos'];
        });
    }

    $("#xmlfile").change(function() {
        var fileName = $(this).val();
        if (fileName) {
            $('#rbintercambio, #tcredito').prop('disabled', false);
        } else {
            $('#rbintercambio #tcredito').prop('disabled', true);
        }
    });

    $("#frmnewsol").submit(function(e) {
        e.preventDefault();
    }).validate({
        submitHandler: function(form) {
            if ($("#proveedor").val() != "") {

                var data = new FormData($(form)[0]);
                data.append("idsolicitud", idsolicitud);
                data.append("xmlfile", documento_xml);

                if (!$("#proveedor").prop("disabled")) {
                    data.append("idproveedor", $("#proveedor").val());
                }

                data.append("descr", $("#descr").val());

                $.ajax({
                    url: url + link_post,
                    data: data,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    method: 'POST',
                    type: 'POST', // For jQuery < 1.9
                    success: function(data) {
                        if (data.resultado) {
                            //resear_formulario();
                            $("#modal_formulario_solicitud").modal('toggle');
                            table_autorizar.ajax.reload();
                        } else {
                            alert("NO SE HA PODIDO COMPLETAR LA SOLICITUD");
                        }
                    },
                    error: function() {
                        alert("Algo salio mal, recargue su página.");
                    }
                });

            } else {
                alert("HA INGRESADO UN PROVEEDOR NO REGISTRADO, PARA PODER HACER ALGÚN MOVIMIENTO NECESITA REGISTRARLO PREVIAMENTE.");
            }
        }
    });

    $("#frmnewsolp").submit(function(e) {
        e.preventDefault();
    }).validate({
        submitHandler: function(form) {
            // if($("#proveedor").val() != ""){

            var data = new FormData($(form)[0]);
            data.append("idsolicitud", idsolicitud);
            data.append("xmlfile", documento_xml);

            if (!$("#proveedor_programado").prop("disabled")) {
                data.append("idproveedor", $("#proveedor_programado").val());
            }

            data.append("descr", $("#descr").val());

            $.ajax({
                url: url + link_post,
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                method: 'POST',
                type: 'POST', // For jQuery < 1.9
                success: function(data) {
                    if (data.resultado) {
                        //resear_formulario();
                        $("#modal_solicitud_programado").modal('toggle');
                        table_autorizar.ajax.reload();
                    } else {
                        alert("NO SE HA PODIDO COMPLETAR LA SOLICITUD");
                    }
                },
                error: function() {
                    alert("Algo salio mal, recargue su página.");
                }
            });

            // }else{
            //     alert("HA INGRESADO UN PROVEEDOR NO REGISTRADO, PARA PODER HACER ALGÚN MOVIMIENTO NECESITA REGISTRARLO PREVIAMENTE.");
            // }
        }
    });

    $("#invitacion_proveedores").submit(function(e) {
        e.preventDefault();
    }).validate({
        submitHandler: function(form) {

            var data = new FormData($(form)[0]);
            $.ajax({
                url: url + "Solicitante/invitacion_proveedor",
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                method: 'POST',
                type: 'POST', // For jQuery < 1.9
                success: function(data) {
                    if (data[0]) {
                        alert("SE HA ENVIADO CON ÉXITO LA INVITACIÓN AL PROVEEDOR");
                        $("#invitacion_proveedores input").val('');
                    } else {
                        alert("NO SE HA PODIDO COMPLETAR LA SOLICITUD");
                    }
                },
                error: function() {
                    alert("Algo salio mal, recargue su página.");
                }
            });
        }
    });

    // FUNCION PARA TRAER LISTADO DE TIPO SERVICIO / PARTIDA
    $(document).ready(function() {
        $(".listado_tServicio_partida").append('<option value="">Seleccione una opción</option>');
        $.getJSON(url + "Listas_select/Lista_TipoServicioPartidas").done(function(data) {
            $.each(data, function(i, item) {
                $('.listado_tServicio_partida').append('<option value="' + item.idTipoServicioPartida + '" >' + item.nombre + '</option>');
            });
        })
    });

    //FUNCION PARA LIMPIAR EL FORMULARIO CON DE PAGOS A PROVEEDOR.
    function resear_formulario() {
        $("#modal_formulario_solicitud input.form-control").prop("readonly", false).val("");
        $("#modal_formulario_solicitud textarea").html('').val('');
        $("#modal_formulario_solicitud #orden_compra").val('');
        $("#modal_formulario_solicitud #descr").val('');
        $("#modal_formulario_solicitud #obse").val('');
        $("#modal_formulario_solicitud textarea").prop("readonly", false);
        $("#empresa, #proveedor, #xmlfile").prop('disabled', false);
        $("#empresa option, #proveedor option, #forma_pago option, #proyecto option, #homoclave option").prop("selected", false);
        $("#modal_formulario_solicitud .form-control, #modal_formulario_solicitud input[type='checkbox']").prop("checked", false).prop('disabled', false);
        $("input[type='hidden'], #responsable_cc").val("").prop('disabled', true);
        $(".programar_fecha").prop('disabled', true);

        $("#moneda").html("");
        $("#moneda").append('<option value="MXN" data-value="MXN">MXN</option>');
        $("#moneda").append('<option value="USD" data-value="USD">USD</option>');
        $("#moneda").append('<option value="CAD" data-value="CAD">CAD</option>');
        $("#moneda").append('<option value="EUR" data-value="EUR">EUR</option>');
        $("#moneda").append('<option value="UDIS" data-value="UDIS">UDIS</option>');

        $("#modal_formulario_solicitud #descr").prop("readonly", true).val('');
        $("#modal_formulario_solicitud #subtotal").prop("readonly", true).val('');
        $("#modal_formulario_solicitud #iva").prop("readonly", true).val('');
        $("#modal_formulario_solicitud #metpag").prop("readonly", true).val('');
        $("#modal_formulario_solicitud #obse").prop("readonly", true).val('');
        $('.default').prop('checked', true);
        $("input[type=radio][name=servicio1]").prop('disabled', false);
        $("input[type=radio][name=servicio1][value=0]").prop('checked', true);
        $("#rbintercambio").prop('disabled', true);

        obtenerProyectosDepartemento({});

        //$("#proveedor").replaceWith('<input type="text" name="proveedor" class="form-control" id="proveedor" placeholder="Nombre del proveedor" required>');
        //AutocompleteProveedor(_data1);
        $("#idproveedor").val('');

        var validator = $("#frmnewsol").validate();
        validator.resetForm();
        $("#frmnewsol div").removeClass("has-error");

        $("#fecha").attr("min", fecha_minima)

        documento_xml = null;
        _data3 = [];

        $("input[name='tentra_factura']").prop("checked", false).prop('disabled', true);
        $("input[type='hidden'][name='tentra_factura']").val("").prop('disabled', false);

        //PARA EL CAMPO DE CONTRATOS POR PROVEEDOR
        /*
            Verificamos si el usuario que esta capturando se encuentra en CONSTRUCCION O JARDINERIA
            Se cambio la regla por requerimiento de Lirio y Mariela, se abrio esta opcion para todos los usuarios
        */
        $("#cproveedor").html("<option value=''>Seleccione una opción</option>").prop("disabled", true);

        $("#insumo")[0].tomselect.disable(); /** FECHA: 06-MARZO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
        $("#insumo")[0].tomselect.setValue(""); /** FECHA: 06-MARZO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/

        $('#proyecto').val('').trigger('change');
        $('#Etapa').val('N/A').trigger('change');
        $('#condominio').val('N/A').trigger('change');
        $('#listado_tServicio_partida').val('').trigger('change');
    }

    //FUNCION PARA LIMPIAR EL FORMULARIO CORRESPONDIENTE AL DE PAGOS PROGRAMADOS
    function resear_formulario_programado() {
        $("#modal_solicitud_programado input.form-control").prop("readonly", false).val("");

        $("#modal_solicitud_programado #solobspr, #modal_solicitud_programado #orden_compra").html('').val('');
        $("#modal_solicitud_programado textarea").html('').prop("readonly", false);
        $("#empresapr, #proveedor_programado, #xmlfile").prop('disabled', false);
        $("#empresapr option, #proveedor_programado option, #forma_pagopr option, #proyectopr option, #metodo_pago option, #homoclave option").prop("selected", false);
        $("#modal_formulario_solicitud .form-control, #modal_formulario_solicitud input[type='checkbox']").prop("checked", false).prop('disabled', false);
        $("input[type='hidden']").val("").prop('disabled', true);
        $("#interes_dinamico").prop("checked", false);
        $(".programar_fecha, #responsable_cc").prop('disabled', true);

        $("#monedapr").html("");
        $("#monedapr").append('<option value="MXN" data-value="MXN">MXN</option>');
        $("#monedapr").append('<option value="USD" data-value="USD">USD</option>');
        $("#monedapr").append('<option value="CAD" data-value="CAD">CAD</option>');
        $("#monedapr").append('<option value="EUR" data-value="EUR">EUR</option>');
        $("#monedapr").append('<option value="UDIS" data-value="UDIS">UDIS</option>');

        $("#modal_solicitud_programado #descr").prop("readonly", true).val('');
        $("#modal_solicitud_programado #metpag").prop("readonly", true).val('');
        $("#modal_solicitud_programado #obse").prop("readonly", true).val('');
        $('.default').prop('checked', true);
        $("input[type=radio][name=servicio1][value=0]").prop('checked', true);
        $("input[type=radio][name=servicio1]").prop('disabled', false);
        obtenerProyectosDepartemento({
            programado: true
        });

        AutocompleteProveedor(_data1);
        $("#idproveedor").val('');

        var validatorp = $("#frmnewsolp").validate();
        validatorp.resetForm();
        $("#frmnewsolp div").removeClass("has-error");

        $("#fechapr").attr("min", fecha_minima)

        documento_xml = null;
        _data3 = [];

    }

    function checar_caja_chica(cantidad, moneda, tipo) {

        if (!$("#total").prop("readonly")) {
            if (moneda == 'MXN' && cantidad <= limite_cajachica && $(".caja_chica_label").prop('checked')) {
                if (cantidad)
                    $(".caja_chica_label").prop('disabled', false);
            } else if (moneda == 'USD' && cantidad <= 450 && $(".caja_chica_label").prop('checked')) {
                /** INICIO TICKET #87949 | FECHA: 25-SEPTIEMBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */

                if (cantidad)
                    $(".caja_chica_label").prop('disabled', false);
                /** FIN TICKET #87949 | FECHA: 25-SEPTIEMBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
            } else {

                if ($(".caja_chica_label").prop('checked')) {
                    alert("ESTE GASTO SUPERA LA CANTIDAD VÁLIDA PARA SER CONSIDERADA COMO CAJA CHICA.");
                    $("#total").val("");
                } else {
                    $(".caja_chica_label").prop('disabled', true);
                }
            }
        }

        //RESTRICCION PARA LOS CONTRATOS POR PROVEEDOR.
        /*
            VERIFICAMOS SI EL USUARIO QUE INGRESO ESTA EN CONSTRUCCION Y LA OPCION DE CONTRATOS ESTA VIGENTE.
            POR REQUERIMIENTO POR PARTE DE CONTROL INTERNO Y LIRIO SE HABILITARAN ESTOS CAMPOS PARA TODOS LOS USUARIOS.
        */
        if (cantidad > parseFloat($("#cproveedor option:selected").data("restante"))) {
            alert("El contrato seleccionado no cuenta con los fondos suficientes.");

            if ($("#total").prop("readonly")) {
                $("#cproveedor").val("")
            } else {
                $("#total").val("")
            }


        }

    }

    $("input[name='pago_programado']").click(function() {
        $(".programar_fecha").prop("disabled", ($(this).prop("checked") ? false : true))
    });

    $(document).on("click", ".abrir_nueva_solicitud", function() {
        resear_formulario();
        recargar_provedores();
        link_post = "Solicitante/guardar_solicitud";
        $("#modal_formulario_solicitud").modal({
            backdrop: 'static',
            keyboard: false
        });
    });

    $(document).on("click", ".abrir_nueva_solicitud_programada", function() {
        resear_formulario_programado();
        recargar_provedores();
        link_post = "Solicitante/guardar_solicitud";
        $("#modal_solicitud_programado").modal({
            backdrop: 'static',
            keyboard: false
        });
    });

    $("#recargar_formulario_solicitud").click(function() {
        resear_formulario();
        recargar_provedores();
    });

    $("#total, #moneda, #forma_pago, #cproveedor").change(function() {
        checar_caja_chica($("#total").val(), $("#moneda").val());
    });

    $("#cargar_xml").click(function() {
        subir_xml($("#xmlfile"));
    });

    var justificacion_globla = "";

    function subir_xml(input) {

        var data = new FormData();
        documento_xml = input[0].files[0];
        var xml = documento_xml;
        var caja_chicaval = ($("input[name='servicio1']:checked").val() ? $("input[name='servicio1']:checked").val() : $("input[type=hidden][name='servicio']").val());
        data.append("xmlfile", documento_xml);
        data.append("cajachica", caja_chicaval); //usa valor del radio para saber si es caja chica

        resear_formulario();

        $.ajax({
            url: url + "Solicitante/cargaxml",
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            method: 'POST',
            type: 'POST', // For jQuery < 1.9
            success: function(data) {
                if (data.respuesta[0]) {

                    limite_cajachica = data.limite;
                    documento_xml = xml;

                    var informacion_factura = data.datos_xml
                    input.prop('disabled', true);

                    if (data.proveedorcc) {
                        $("#proveedor").empty();
                        $("#proveedor").append('<option value="">Seleccione una opción</option>');

                        var carga_temporal = [];

                        $.each(data.proveedorcc, function(i, item) {
                            carga_temporal.push({
                                value: item.idproveedor,
                                excp: item.excp,
                                label: item.nombre + " - " + item.nom_banco,
                                rfc: item.rfc
                            });
                        });

                        AutocompleteProveedor(carga_temporal);
                        $("#idproveedor").val(data.proveedorcc[0].idproveedor);

                        if (data.proveedorcc.length > 1) {
                            $("#proveedor").val($("#proveedor option:first").val());
                        } else {
                            $('#proveedor option:eq(1)').attr('selected', 'selected');
                        }
                    }

                    cargar_info_xml(informacion_factura);

                    $("input[name='servicio1']").prop("disabled", true);
                    $("input[name='servicio1'][value='" + caja_chicaval + "']").prop("disabled", false).prop("checked", true);

                    if (caja_chicaval == 0 || caja_chicaval == 1) {
                        $("input[name='servicio1'][value='0']").prop("disabled", false);
                        $("input[name='servicio1'][value='1']").prop("disabled", false);
                    }

                    if (caja_chicaval == 11) {
                        $("#insumo")[0].tomselect.disable(); /** FECHA: 06-MARZO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                    }

                    //ALMACENABA LA JUSTIFICACION DEL GASTO GLOBALMENTE
                    //$("#solobs").val( justificacion_globla );

                } else {
                    input.val('');
                    alert(data.respuesta[1]);
                }
            },
            error: function(data) {
                input.val('');
                alert("Algo salio mal, recargue su página.");
            }
        });

    }

    function cargar_info_xml(informacion_factura) {
        descripcion = "";
        $.each(informacion_factura.conceptos, function(i, v) {
            descripcion += (i + 1) + ") " + v['@attributes']['Descripcion'] + " - Importe: $" + v['@attributes']['Importe'] + " \n";
        });

        $("#descr").append(descripcion).val(descripcion).prop('readonly', true);

        var fecha = informacion_factura.fecha[0];
        $("#fecha").val(fecha.substring(0, 10)).attr('readonly', true);

        $("#fecha").attr("min", fecha.substring(0, 10))

        $("#folio").val(informacion_factura.folio[0]).attr('readonly', true);
        $("#subtotal").val(informacion_factura.SubTotal[0]).attr('readonly', true);
        $("#iva").val(informacion_factura.Total[0] - informacion_factura.SubTotal[0]).attr('readonly', true);
        $("#total").val(informacion_factura.Total[0]).attr('readonly', true);
        $("#metpag").val((informacion_factura.MetodoPago ? informacion_factura.MetodoPago[0] : '')).attr('readonly', true);


        $("#empresa option").each(function() {
            if ($(this).attr('data-value') != informacion_factura.rfcrecep[0]) {
                $(this).remove();
            }
        });

        if ($("#proveedor option:selected").attr("data-privilegio") == 2) {
            $("input[name='tentra_factura']").prop("checked", false).prop('disabled', true);
            $("input[type='hidden'][name='tentra_factura']").val("").prop('disabled', true);
        } else {
            $("input[name='tentra_factura']").prop("checked", true).prop('disabled', true);
            $("input[type='hidden'][name='tentra_factura']").val("1").prop('disabled', false);
        }

        /****REVISAMOS QUE TIPO DE SERVICIO ES*/
        /*
            TOMAMOS LA LISTA Y MEDIANTE UN CICLO DESCARTAMOS TODOS LOS PROVEEDORES QUE NO PUEDEN SER EN BASE A LA FACTURA
            EN CASO DE ENCONTRAR 2 PROVEEDORES QUE COINCIDEN LOS DEJA Y COLOCA UNA OPCION EN BLANCO PARA OBLOGAR AL USUARIO DE SELECCIONAR
            LA CUENTA CORRECTA.
        */
        if ($("input[type=hidden][name='servicio']").val() != 9) {
            $("#proveedor option").each(function() {
                if ($(this).attr('data-value') != informacion_factura.rfcemisor[0]) {
                    $(this).remove();
                }
            });

            if ($('#proveedor > option').length > 1)
                $("#proveedor").prepend('<option value="" selected>Seleccione una opción</option>');
            else {
                //PARA CONSTRUCCION VERIFICAMOS SI EL PROVEEDOR SELECCIONADO TIENE CONTRATOS VALIDOS.
                revison_proveedor_contratos($('#proveedor option:eq(0)').val());
            }

            $("#idproveedor").val($("#proveedor").val()).prop('disabled', false);
        }
        /********/

        $("#moneda").html('');
        $("#moneda").append('<option value="' + informacion_factura.Moneda[0] + '" data-value="' + informacion_factura.Moneda[0] + '">' + informacion_factura.Moneda[0] + '</option>');

        var formapago = informacion_factura.formpago ? informacion_factura.formpago[0] : "N/A";

        $("#obse").append(informacion_factura.condpago ? informacion_factura.condpago[0] : "NA");
        $("#obse").prop('readonly', true);

        $("input[name='empresa']").val($("#empresa").val()).prop('disabled', false);

        switch (informacion_factura.formpago[0]) {
            case "01":
                if (informacion_factura.Total[0] <= limite_cajachica) {
                    $(".caja_chica_label").prop("checked", true);
                    $(".pago_proveedor").prop("disabled", true);
                    $("#insumo")[0].tomselect.enable(); /** FECHA: 06-MARZO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/

                } else {
                    $(".caja_chica_label").prop("disabled", true);
                    $(".caja_chica_label").prop("checked", false);
                    $(".pago_proveedor").prop("disabled", false);
                    alert("ESTA FACTURA ES MAYOR A LO PERMITIDO COMO PAGO EN EFECTIVO. $ " + formatMoney(limite_cajachica) + " PESOS");
                    resear_formulario();
                }
                $("input[type=hidden][name='servicio']").val(9);
                $("#responsable_cc").prop("disabled", false);
                break;
            case "03":
            case "04":
            case "05":
            case "06":
            case "28":
            case "29":
            case "31":
                $(".caja_chica_label").prop("checked", true);
                $(".pago_proveedor").prop("disabled", true);
                $("input[type=hidden][name='servicio']").val(9).prop("disabled", false);
                $("#responsable_cc").prop("disabled", false);
                $("#insumo")[0].tomselect.enable(); /** FECHA: 06-MARZO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                $("#insumo")[0].tomselect.setValue(informacion_factura.cch); /** FECHA: 06-MARZO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                break;
            default:
                if (informacion_factura.cch) {
                    $(".caja_chica_label").prop("checked", true);
                    $(".pago_proveedor").prop("disabled", true);
                    $("input[type=hidden][name='servicio']").val(9).prop("disabled", false);
                    $("#responsable_cc").prop("disabled", false);

                    $("#insumo")[0].tomselect.enable(); /** FECHA: 06-MARZO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                    $("#insumo")[0].tomselect.setValue(informacion_factura.cch); /** FECHA: 06-MARZO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                    1
                } else {
                    $(".caja_chica_label").prop("disabled", true);
                    $(".caja_chica_label").prop("checked", false);
                    $(".pago_proveedor").prop("disabled", false);
                    $("#responsable_cc").prop("disabled", true);
                }
                break;
        }

    }

    var table_autorizar;

    $("#tabla_autorizaciones").ready(function() {
        let opcion;
        if (!depto || depto == '' || depto == undefined) {
            switch (data_usuario['rol']) {
                case 'CT':
                    opcion = 'CONTABILIDAD';
                    break;
                case 'CE':
                    opcion = 'DEVOLUCIONES';
                    break;
                case 'CP':
                    opcion = 'ADMINISTRACION';
                    break;
                default:
                    opcion = data_usuario['depto'];
                    break;
            }

            switch (opcion) {
                case "COMERCIALIZACION":
                    $("#pbuscar, #pcurso").append('<option value="10">COMERCIALIZACION</option>');
                    break;
                case "CONTABILIDAD":
                    $("#pbuscar, #pcurso").append('<option value="5">CONTABILIDAD</option>');
                    $("#pbuscar, #pcurso").append('<option value="11">PRESTAMOS POR ADEUDO</option>');
                    $("#pbuscar, #pcurso").append('<option value="4">NOMINAS</option>');
                    $("#pbuscar, #pcurso").append('<option value="12">BONOS</option>');
                    $("#pbuscar, #pcurso").append('<option value="6">IMPUESTOS</option>');
                    break;
                case "CAPITAL HUMANO": /** FECHA: 06-JUNIO-2025 | NUEVO CONCEPTO SOLO DEPTO CH | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                    $("#pbuscar, #pcurso").append('<option value="1">GASTOS DE CAPITAL HUMANO</option>');

                    if (data_usuario['rol'] != 'DA' ) {
                        $("#pbuscar, #pcurso").append('<option value="2">GASTOS ADMON DE OFICINA</option>');
                    }

                    $("#pbuscar, #pcurso").append('<option value="3">FINIQUITOS, PRESTAMOS, BONOS</option>');

                    $("#pbuscar, #pcurso").append('<option value="4">NOMINAS</option>');

                    if (data_usuario['rol'] == 'DA') {
                        $("#pbuscar, #pcurso").append('<option value="9">PAGO FUERA DE NOMINA</option>');
                    }
                    break; /** FECHA: 06-JUNIO-2025 | NUEVO CONCEPTO SOLO DEPTO CH | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                case "OOAM":
                    $("#pbuscar, #pcurso").append('<option value="15">OOAM</option>');
                    break;
                case "COMPRAS":
                    $("#pbuscar, #pcurso").append('<option value="16">COMPRAS</option>');
                    $("#pbuscar, #pcurso").append('<option value="17">MERCADOTECNIA</option>');
                    break;
                case "DIRECCION GENERAL":
                    $("#pbuscar, #pcurso").append('<option value="7">DIRECCION GENERAL</option>');
                    $("#pbuscar, #pcurso").append('<option value="13">COSOMI</option>');
                    $("#pbuscar, #pcurso").append('<option value="14">GESTION</option>');
                    break;
                case "CI-COMPRAS":
                    $("#pbuscar, #pcurso").append('<option value="18">CI-COMPRAS</option>');
                    break;
                case "CONTROL INTERNO":
                    $("#pbuscar, #pcurso").append('<option value="19">CONTROL INTERNO</option>');
                    $("#pbuscar, #pcurso").append('<option value="18">CI-COMPRAS</option>');
                    $("#pbuscar, #pcurso").append('<option value="75">COMUNICACION</option>');
                    break;
                case "CONTRATACIÓN Y TITULACIÓN":
                    $("#pbuscar, #pcurso").append('<option value="20">JURÍDICO</option>');
                    $("#pbuscar, #pcurso").append('<option value="21">JURÍDICO INTERNO</option>');
                    $("#pbuscar, #pcurso").append('<option value="22">CONTRATACIÓN Y TITULACIÓN</option>');
                    break;
                case "NYSSA":
                    $("#pbuscar, #pcurso").append('<option value="49">NYSSA</option>');
                    $("#pbuscar, #pcurso").append('<option value="72">ALEBRIJE</option>');
                    $("#pbuscar, #pcurso").append('<option value="73">FUNDACION CIUDAD MADERAS</option>');
                    break;
            }
            $('.select2').select2();
            document.getElementById("pbuscar").selectedIndex = 0;
        }
        $('#tabla_autorizaciones').on('xhr.dt', function(e, settings, json, xhr) {
            table_autorizar.button(1).enable(parseInt(json.por_autorizar) > 0);

            var total = 0;

            $.each(json.data, function(i, v) {
                total += parseFloat(v.cantidad);
            });

            var to = formatMoney(total);
            $("#myText_1").html("$" + to);
            /*
            document.getElementById("myText_1").value = to;
            */
        });

        $('#tabla_autorizaciones thead tr:eq(0) th').each(function(i) {
            if (i > 1 && i < $('#tabla_autorizaciones thead tr:eq(0) th').length - 1) {
                /** FECHA: 23-MAYO-2025 @author Ángel Victoriano <programador.analista30@ciudadmaderas.com> */
                var title = $(this).text();
                $(this).html('<input type="text" style="font-size: .9em; width: 100%;" placeholder="' + title + '" />');

                $('input', this).on('keyup change', function() {
                    if (table_autorizar.column(i).search() !== this.value) {
                        table_autorizar
                            .column(i)
                            .search(this.value)
                            .draw();
                        var total = 0;
                        var index = table_autorizar.rows({
                            selected: true,
                            search: 'applied'
                        }).indexes();
                        var data = table_autorizar.rows(index).data();
                        $.each(data, function(i, v) {
                            total += parseFloat(v.cantidad);
                        });
                        var to1 = formatMoney(total);
                        $("#myText_1").html("$" + to1);
                    }
                });
            }
        });

        table_autorizar = $('#tabla_autorizaciones').DataTable({
            dom: 'Brtip',
            buttons: [{
                    text: '<i class="fas fa-plus"></i> NUEVA SOLICITUD',
                    attr: {
                        class: 'btn btn-success abrir_nueva_solicitud'
                    }
                },
                {
                    text: '<i class="fas fa-print"></i> AUT. PAGO A PROVEEDORES',
                    action: function() {
                        window.open(url + "Consultar/documentos_autorizacion", "_blank")
                    },
                    attr: {
                        class: 'btn btn-danger imprimir_pago_provedores',
                    }
                },
                {
                    text: '<i class="fas fa-clock"></i> PAGO PROGRAMADO',
                    attr: {
                        class: 'btn btn-primary abrir_nueva_solicitud_programada'
                    }
                }
            ],
            //"stateSave": true,
            "language": lenguaje,
            "processing": false,
            "pageLength": 10,
            "bAutoWidth": false,
            "bLengthChange": false,
            "order": [2, "asc"],
            /** FECHA: 23-MAYO-2025 | APROBACION MULTIPLE DA | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
            "bInfo": false,
            "scrollX": true,
            "columns": [{
                    /** FECHA: 23-MAYO-2025 | APROBACION MULTIPLE DA | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                    "orderable": false,
                    "data": function(d) {
                        let checkbox = '';
                        if (![1, 6, 8, 13, 25].includes(parseInt(d.idetapa))) { //"Borrador", "Rechazada DA", "Rechazada CXP", "Pago autorizado, espera de liberación Contabilidad", "Pausada Por DA"
                            checkbox = `<input
                                            type="checkbox"
                                            id="chb${d.idsolicitud}"
                                            class="checkboxes"
                                            style="width: 1em; height: 1em;"
                                            value="${d.idsolicitud}"
                                            data-fechaElab="${d.fecelab}"
                                            data-cajaChica="${d.caja_chica}"
                                            data-dpto="${d.nomdepto}"
                                            data-cantidad="${d.cantidad}"
                                            onchange="handleCheckBox(this)"
                                        >`;
                        }

                        let contenedor = '<div>' + checkbox + '</div>';
                        return contenedor;
                    }
                }, /** FIN FECHA: 23-MAYO-2025 | APROBACION MULTIPLE DA | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                {
                    "className": 'details-control',
                    "orderable": false,
                    "data": null,
                    "defaultContent": '<i class="fas animacion fa-caret-right"></i>'
                },
                {
                    "width": "8%",
                    "data": function(d) {
                        return '<p style="font-size: .8em">' + d.idsolicitud + '</p>'
                    }
                },
                {
                    "width": "8%",
                    "data": function(d) {
                        return '<p style="font-size: .8em">INT ' + d.folio + '<br/> FISC ' + (d.uuid).substr(-5) + '</p>'
                    }
                },

                {
                    "width": "10%",
                    "data": function(d) {
                        return '<p style="font-size: .8em">PROY ' + (!d.proyecto ? d.proyectoNuevo : d.proyecto) + '</p>' + (d.homoclave && d.homoclave != 'N/A' ? '<p style="font-size: .8em">HCL ' + d.homoclave + '</p>' : '');
                    }
                },
                {
                    /** INICIO FECHA: 08-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                    "width": "9%",
                    "data": function(d) {
                        return '<p style="font-size: .8em">' + (d.tServicioPartida ? d.tServicioPartida : 'NA') + '</p>'
                    }
                }, /** FIN FECHA: 08-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                {
                    "width": "9%",
                    "data": function(d) {
                        return '<p style="font-size: .8em">' + d.nempresa + '</p>'
                    }
                },
                {
                    "width": "9%",
                    "data": function(d) {
                        return '<p style="font-size: .8em">' + d.fecelab + '</p>'
                    }
                },
                {
                    "width": "12%",
                    "data": function(d) {
                        return '<p style="font-size: .7em">' + d.nombre + '</p>'
                    }
                },
                {
                    "width": "12%",
                    "data": function(d) {
                        return '<p style="font-size: .8em">' + d.nombre_completo + '</p>'
                    }
                },
                {
                    "width": "12%",
                    "data": function(d) {
                        return '<p style="font-size: .8em" class="text-center">$ ' + formatMoney(d.cantidad) + " " + d.moneda + '<br/><small class="label pull-center bg-blue">' + d.metoPago + '</small></p>'
                    }
                },
                {
                    /** INICIO FECHA: 23-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                    "width": "12%",
                    "data": function(d) {
                        return '<p class="justificacion-cell">' + d.justificacion + '</p>';
                    }
                }, /** FIN FECHA: 23-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                {
                    "width": "8%",
                    "data": function(d) {

                        var tipo_gasto = 'PROVEEDOR';

                        if (d.caja_chica == 1) {
                            tipo_gasto = 'CAJA CHICA'
                        }

                        if (d.caja_chica == 2) {
                            tipo_gasto = 'T CRÉDITO'
                        }
                        if (d.caja_chica == 3) {
                            tipo_gasto = 'REEMBOLSO'
                        }

                        if (d.caja_chica == 4) {
                            tipo_gasto = 'VIÁTICO'
                        }

                        return '<p style="font-size: .8em">' + d.etapa + '</p>' + '<small class="label pull-center bg-orange">' + tipo_gasto + '</small>'
                    }
                },
                {
                    "data": "opciones",
                    "orderable": false
                }
            ],
            "columnDefs": [{
                    "targets": [0],
                    "visible": false,
                },
                {
                    /** INICIO FECHA: 23-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                    "targets": [11],
                    "visible": false,
                }, /** FIN FECHA: 23-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
            ],
            "ajax": {
                "url": url + "Solicitante/tabla_autorizaciones_nominas_gastos",
                "type": "POST",
                "data": function(d) {
                    if ($("#pbuscar").val() == '' || $("#pbuscar").val() == null || $("#pbuscar").val() == undefined || !$("#pbuscar").val()) {
                        d.opcion = opcion;
                    } else {
                        d.opcion = $("#pbuscar").val();
                    }

                }
            }
        });

        $("#tabla_autorizaciones").DataTable().rows().every(function() {
            var tr = $(this.node());
            this.child(format(tr.data('child-value'))).show();
            tr.addClass('shown');
        });
        $('#fecInicial').change(function() {
            table_proceso.draw();
            var total = 0;
            var index = table_proceso.rows({
                selected: true,
                search: 'applied'
            }).indexes();
            var data = table_proceso.rows(index).data();
            $.each(data, function(i, v) {
                total += parseFloat(v.cantidad);
            });
            var to1 = formatMoney(total);
            document.getElementById("myText_2").value = to1;

        });

        $('#fecFinal').change(function() {
            table_proceso.draw();
            var total = 0;
            var index = table_proceso.rows({
                selected: true,
                search: 'applied'
            }).indexes();
            var data = table_proceso.rows(index).data();
            $.each(data, function(i, v) {
                total += parseFloat(v.cantidad);
            });
            var to1 = formatMoney(total);
            document.getElementById("myText_2").value = to1;
        });

        $('#tabla_autorizaciones tbody').on('click', 'td.details-control', function() {
            var tr = $(this).closest('tr');
            var row = table_autorizar.row(tr);

            if (row.child.isShown()) {
                row.child.hide();
                tr.removeClass('shown');
                $(this).parent().find('.animacion').removeClass("fa-caret-down").addClass("fa-caret-right");
            } else {

                var informacion_adicional = '<table class="table text-justify">' +
                    '<tr>' +
                    '<td><strong>JUSTIFICACIÓN: </strong>' + row.data().justificacion + '</td>' +
                    '</tr>' +
                    '</table>';

                row.child(informacion_adicional).show();
                tr.addClass('shown');
                $(this).parent().find('.animacion').removeClass("fa-caret-right").addClass("fa-caret-down");
            }
        });

        $('#tabla_autorizaciones').on("click", ".editar_factura", function() {
            recargar_provedores();
            var tr = $(this).closest('tr');
            var row = table_autorizar.row(tr);

            link_post = "Solicitante/editar_solicitud";

            //var limite_edicion = row.data().idetapa > 1 ? 2 : 1;
            var ideditar = $(this).val();

            $.post(url + "Solicitante/informacion_solicitud", {
                idsolicitud: ideditar
            }).done(function(data) {

                data = JSON.parse(data);
                //alert(JSON.stringify(data));
                if (data.resultado) {

                    idsolicitud = ideditar;

                    if (data.info_solicitud[0].programado != 'NULL' && data.info_solicitud[0].programado != null) {
                        resear_formulario_programado();
                        setTimeout(() => {
                            funcion2();
                        }, 5000)


                        function funcion2() {

                            $("#fechapr").val(data.info_solicitud[0].fecelab);
                            $("#fecha_finalpr").val(data.info_solicitud[0].fecha_fin);
                            $("#totalpr").val(data.info_solicitud[0].cantidad);
                            $("#monedapr").html("");
                            $("#monedapr").append('<option value="' + data.info_solicitud[0].moneda + '" data-value="' + data.info_solicitud[0].moneda + '">' + data.info_solicitud[0].moneda + '</option>');
                            $("#empresapr option[data-value='" + data.info_solicitud[0].rfc_empresas + "']").prop("selected", true);
                            // $("#proveedor_programado option[data-value='"+data.info_solicitud[0].idProveedor+"']").prop("selected", true);
                            // $("input[name='referencia_pagopr']").val( data.info_solicitud[0].ref_bancaria );
                            $("#referencia_pagopr").val(data.info_solicitud[0].ref_bancaria);
                            $("#solobspr").val(data.info_solicitud[0].justificacion);
                            $("#orden_compra").val(data.info_solicitud[0].orden_compra);
                            $("#crecibo").val(data.info_solicitud[0].crecibo);
                            justificacion_globla = data.info_solicitud[0].justificacion
                            $("#proyectopr option[value='" + data.info_solicitud[0].proyecto + "']").prop("selected", true).trigger('change');

                            $("#forma_pagopr option[value='" + data.info_solicitud[0].metoPago + "']").prop("selected", true);
                            $("#metodo_pago option[value='" + data.info_solicitud[0].programado + "']").prop("selected", true);
                            $("#proveedor_programado option[value='" + data.info_solicitud[0].idProveedor + "']").prop("selected", true);
                            $("#proveedor_programado").val(data.info_solicitud[0].idProveedor);
                            uno = data.info_solicitud[0].nombre_proveedor;
                            dos = data.info_solicitud[0].idProveedor;
                            $('#proveedor_programado').select2();
                            $("#modal_solicitud_programado").modal({
                                backdrop: 'static',
                                keyboard: false
                            });
                            obtenerProyectosDepartemento({
                                programado: true,
                                esApi: data.info_solicitud[0].Api == 1,
                                valueProyecto: data.info_solicitud[0].idProyectos,
                                valueOficina: data.info_solicitud[0].idOficina,
                                valueProyectoViejo: data.info_solicitud[0].proyectoViejo,
                                esProyectoNuevo: data.info_solicitud[0].esProyectoNuevo,
                                departamentoSolicitud: data.info_solicitud[0].nomdepto
                            });
                        }

                    } else {

                        resear_formulario();
                        if (data.xml) {
                            cargar_info_xml(data.xml);
                            $("#fecha").prop('disabled', true);
                            $("#folio").prop('disabled', true);

                            $("#empresa").prop('disabled', true);
                            $("#proveedor").prop('disabled', true);
                            //$("#moneda").prop('disabled', true );

                            //$("#forma_pago").prop('disabled', true );
                            $("#total").prop('disabled', true);
                            $("input[type=radio][name=servicio1]").prop('disabled', true);
                            $("#tentra_factura").prop('disabled', true);
                            //$("input[name='tentra_factura']").prop('disabled', true );
                        } else {
                            $("#fecha").val(data.info_solicitud[0].fecelab);
                            $("#folio").val(data.info_solicitud[0].folio);

                            $("#subtotal").val("");
                            $("#iva").val("");
                            $("#total").val(data.info_solicitud[0].cantidad);
                            $("#metpag").val("");
                            $("#moneda").val(data.info_solicitud[0].moneda);

                            //$("input[name='caja_chica']").prop('disabled', ( data.info_solicitud[0].cantidad > 2000 ? true : false ) );x
                            $("input[name='tentra_factura']").prop('disabled', !depto_excep.includes(data.info_solicitud[0].nomdepto));
                            $("input[type=radio][name=servicio1]").prop('disabled', false);

                        }

                        $("#descr").append(data.info_solicitud[0].descripcion);
                        $("input[name='referencia_pago']").val(data.info_solicitud[0].ref_bancaria);
                        $("input[name='tentra_factura']").prop("checked", (data.info_solicitud[0].tendrafac == 1 ? true : false));
                        $("input[name='prioridad']").prop("checked", (data.info_solicitud[0].prioridad ? true : false));

                        $("#solobs").val(data.info_solicitud[0].justificacion);
                        $("#orden_compra").val(data.info_solicitud[0].orden_compra);
                        $("#crecibo").val(data.info_solicitud[0].crecibo);
                        $("#requisicion").val(data.info_solicitud[0].requisicion);

                        $("#etapa").val(data.info_solicitud[0].etapa).trigger('change');
                        $("#condominio").val(data.info_solicitud[0].condominio).trigger('change');

                        justificacion_globla = data.info_solicitud[0].justificacion

                        $("#proyecto option[value='" + data.info_solicitud[0].proyecto + "']").prop("selected", true).trigger('change');
                        $("#homoclave option[value='" + data.info_solicitud[0].homoclave + "']").prop("selected", true);
                        if ($("#homoclave").val() == "N/A" && ['CONSTRUCCION', 'JARDINERIA', 'OOAM', 'OOAM TECNICO', 'OOAM ADMINISTRATIVO'].includes(depto) && data.info_solicitud[0].homoclave != "N/A") {
                            $("#homoclave").append('<option value="' + data.info_solicitud[0].homoclave + '">' + data.info_solicitud[0].homoclave + '</option>');
                            $("#homoclave option[value='" + data.info_solicitud[0].homoclave + "']").prop("selected", true).trigger('change');
                        }
                        $("#forma_pago").val(data.info_solicitud[0].metoPago);

                        $("#listado_tServicio_partida option[value='" + data.info_solicitud[0].idTipoServicioPartida + "']").prop("selected", true).change(); /** FECHA: 08-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/

                        /**********************COLOCAMOS EL PROVEEDOR SELECCIONADO*****************************/

                        $(".lista_provedores_libres").html('');
                        $(".lista_provedores_libres").append('<option value="">Seleccione una opción</option>');

                        var listado_proveedores = [];
                        /**
                         * INICIO
                         * FECHA : 27-MAYO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com
                         * Se agrego la cuenta bancaria despues del nombre del bano de cada proveedor al editar la solicitud
                         */
                        $.each(data.proveedores_todos, function(i, v) {
                            //ENCASO QUE SEA PAGO A PROVEEDOR
                            if ((data.info_solicitud[0].caja_chica == 0 || data.info_solicitud[0].caja_chica == null) && !(v.nombrep.includes('GASTO NO DEDUCIBLE')) && !(v.nombrep.includes('INVOICE'))) {
                                listado_proveedores.push({
                                    value: v.idproveedor,
                                    excp: v.excp,
                                    label: v.nombrep + " - " + v.nom_banco + (v.cuenta ? " - " + v.cuenta : ''),
                                    rfc: v.rfc,
                                    tinsumo: v.tinsumo
                                });
                            }

                            //EN CASO QUE SEA UN COMPROBANTE DE TDC Y NO TENGA FACTURA
                            if (data.xml == null && data.info_solicitud[0].caja_chica == 2 && (v.nombrep.includes('COMPROBANTE NO FISCAL') || v.nombrep.includes('INVOICE') || (v.nombrep.includes('GASTO NO DEDUCIBLE') && depto == 'ADMINISTRACION'))) {
                                listado_proveedores.push({
                                    value: v.idproveedor,
                                    excp: v.excp,
                                    label: v.nombrep + " - " + v.nom_banco + (v.cuenta ? " - " + v.cuenta : ''),
                                    rfc: v.rfc,
                                    tinsumo: v.tinsumo
                                });
                            }

                            //EN CASO QUE CUENTE CON XML EL REGISTRO
                            if (data.xml && data.info_solicitud[0].idProveedor == v.idproveedor) {
                                listado_proveedores.push({
                                    value: v.idproveedor,
                                    excp: v.excp,
                                    label: v.nombrep + " - " + v.nom_banco + (v.cuenta ? " - " + v.cuenta : ''),
                                    rfc: v.rfc,
                                    tinsumo: v.tinsumo
                                });
                            }

                            //EN CASO QUE SEA UN GASTO DE CAJA CHICA Y NO CUENTE CON CFDI
                            if (data.xml == null && data.info_solicitud[0].caja_chica == 1)
                                listado_proveedores.push({
                                    value: v.idproveedor,
                                    excp: v.excp,
                                    label: v.nombrep + " - " + v.nom_banco + (v.cuenta ? " - " + v.cuenta : ''),
                                    rfc: v.rfc,
                                    tinsumo: v.tinsumo
                                });
                        });
                        /**
                         * FIN
                         * FECHA : 27-MAYO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com 
                         */
                        AutocompleteProveedor(listado_proveedores);

                        $("#proveedor option[value='" + data.info_solicitud[0].idProveedor + "']").prop("selected", true);
                        $("#idproveedor").val(data.info_solicitud[0].idProveedor);
                        /************************************************************************************/

                        /**REINICIAMOS TODAS LAS EMPRESAS Y SELECCIONAMOS LA PREDETERMINADA**/
                        //REINICIAMOS EL CATALAGO DE EMPRESAS
                        $("#empresa").html('');
                        $("#empresa").append('<option value="">Seleccione una opción</option>');


                        $.each(data.empresas, function(i, v) {
                            $("#empresa").append('<option value="' + v.idempresa + '" data-value="' + v.rfc + '">' + v.nombre + '</option>');
                        });

                        //SELECCIONAMOS LA EMPRESA DETERMINADA EN EL REGISTRO
                        $("#empresa option[data-value='" + data.info_solicitud[0].rfc_empresas + "']").prop("selected", true);

                        //RETIRAMOS LAS EMPRESAS QUE NO FUERON SELECCIONADAS EN CASO DE TENER UN XML
                        if (data.xml) {
                            $("#empresa option").each(function() {
                                if ($(this).data('value') != data.info_solicitud[0].rfc_empresas) {
                                    $(this).remove();
                                }
                            });
                        }
                        /************************************************************************************/

                        /**DETERMINAMOS QUE TIPO DE GASTO ES**/
                        if (data.info_solicitud[0].caja_chica && data.info_solicitud[0].caja_chica > 0) {
                            //PROCEDIMIENTOS PARA HABILITAR EL RESPONSABLE DE CAJA CHICA
                            $("#responsable_cc").html('<option value="">Selecciones una opción</option>');
                            if (data.info_solicitud[0].caja_chica == 1 || data.info_solicitud[0].caja_chica == '1') {
                                $.each(data.listado_responsable, function(i, v) {
                                    $("#responsable_cc").append('<option value="' + v.idusuario + '">' + v.nombres + " " + v.apellidos + '</option>');
                                });

                                $("#insumo")[0].tomselect.enable(); /** FECHA: 06-MARZO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                                $("#insumo")[0].tomselect.setValue(data.info_solicitud[0].servicio); /** FECHA: 06-MARZO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/

                                $("input[type=hidden][name='servicio']").val('9').prop('disabled', false);
                                $(".caja_chica_label").prop('checked', true);

                            }
                            if (data.info_solicitud[0].caja_chica == 2 || data.info_solicitud[0].caja_chica == '2') {
                                $.each(data.listado_responsable, function(i, v) {
                                    $("#responsable_cc").append('<option value="' + v.idusuario + '" data-rfcempresa="' + v.rfc + '" data-tempresa="' + v.idempresa + '">' + v.nresponsable + '</option>');
                                });

                                $("#insumo")[0].tomselect.disable(); /** FECHA: 06-MARZO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                                $("input[type=radio][name=servicio1][value='11']").prop("checked", true);
                                $("input[type=hidden][name='servicio']").val('11').prop('disabled', false);
                            }

                            $("#responsable_cc").prop('disabled', false).val(data.info_solicitud[0].idResponsable);
                        } else {
                            $("input[type=hidden][name='servicio']").val('').prop('disabled', true);
                            if (data.info_solicitud[0].metoPago == "INTERCAMBIO")
                                $("input[type=radio][name=servicio1][value='10']").prop("checked", true).change();
                            else {
                                $("input[type=radio][name=servicio1][value='1']").prop("checked", (data.info_solicitud[0].servicio == 1 ? true : false));
                                $("input[type=radio][name=servicio1][value='0']").prop("checked", (data.info_solicitud[0].servicio == null || data.info_solicitud[0].servicio == 0 ? true : false));
                            }
                        }
                        /************************************************************************************/

                        obtenerProyectosDepartemento({
                            esApi: data.info_solicitud[0].Api == 1,
                            valueProyecto: data.info_solicitud[0].idProyectos,
                            valueOficina: data.info_solicitud[0].idOficina,
                            valueProyectoViejo: data.info_solicitud[0].proyectoViejo,
                            esProyectoNuevo: data.info_solicitud[0].esProyectoNuevo,
                            departamentoSolicitud: data.info_solicitud[0].nomdepto
                        })

                        uno = data.info_solicitud[0].nombre_proveedor;
                        dos = data.info_solicitud[0].idProveedor;
                        //SOLO PARA CONSTRUCCION
                        /*AL CARGAR LA INFO DE LA SOLICITUD SE EJECUTA Y VERIFICA SI HAY ALGUN CONTRATO PARA LA SOLICITITUD
                         * SE QUITA FILTRO POR DEPARTAMENTO EN CUESTION DE CONTRATO DE PROVEEDORES.
                         */
                        if (data.info_contrato.length > 0) {
                            revison_proveedor_contratos(data.info_solicitud[0].rfc_proveedores);

                            if (!$("#cproveedor").prop("disabled")) {
                                $("#cproveedor option[value='" + data.info_contrato[0].idcontrato + "']").prop("selected", true);
                            }
                        }
                        $("#modal_formulario_solicitud").modal({
                            backdrop: 'static',
                            keyboard: false
                        });

                    }
                } else {
                    alert("Algo salio mal, recargue su página.")
                }
            });
        });

        $('#tabla_autorizaciones').on("click", ".borrar_solicitud", function() {
            $('#modal-alert').modal('toggle');
            $('.datos_modal_alert').val($(this).val());
        });
        $('.borrar_solicitud_modal').on("click", function() {

            $.post(url + "Solicitante/borrar_solicitud", {
                idsolicitud: $('.datos_modal_alert').val()
            }).done(function(data) {
                $('#modal-alert').modal('hide');
                $('.datos_modal_alert').val('');
                data = JSON.parse(data);
                if (data.resultado) {
                    table_autorizar.ajax.reload(null, false);
                } else {
                    alert("Algo salio mal, recargue su página.")
                }
            });
        });

        $('#tabla_autorizaciones').on("click", ".enviar_a_dg", function() {

            var tr = $(this).closest('tr');
            var row = table_autorizar.row(tr).data();

            $.post(url + "Solicitante/enviar_a_dg", {
                idsolicitud: $(this).val(),
                departamento: row.nomdepto
            }).done(function(data) {
                data = JSON.parse(data);
                if (data.resultado) {

                    row.etapa = data.solicitudes_proceso.netapa;
                    table_autorizar.row(tr).data(row).draw();
                    table_autorizar.ajax.reload();
                    //table_autorizar.ajax.reload(null,false);
                    //table_proceso.ajax.reload(null,false);
                } else {
                    alert("Algo salio mal, recargue su página.")
                }
            });
        });

        $('#tabla_autorizaciones').on("click", ".reenviar_factura", function() {
            $.post(url + "Solicitante/reenviar_factura", {
                idsolicitud: $(this).val()
            }).done(function(data) {
                data = JSON.parse(data);
                if (data.resultado) {
                    table_autorizar.ajax.reload(null, false);
                    table_proceso.ajax.reload(null, false);
                } else {
                    alert("Algo salio mal, recargue su página.")
                }
            });
        });

        $('#tabla_autorizaciones').on("click", ".aprobada_da", function() {
            var tr = $(this).closest('tr');
            var row = table_autorizar.row(tr).data();
            $.post(url + "Solicitante/aprobada_da", {
                idsolicitud: $(this).val(),
                fecelab: row.fecelab,
                caja_chica: row.caja_chica,
                departamento: row.nomdepto
            }).done(function(data) {
                data = JSON.parse(data);
                /** MEJORA INICIO FECHA: 03-JUNIO-2025 | MEJORAR RENDIMIENTO ALERTA DA | @author Angel Victoriano <programador.analista30@ciudadmaderas.com>
                 * Se agregó una alerta para confirmar que la aprobación se realizó exitosamente.
                 */
                if (data.resultado) {
                    swal({
                        title: "¡ÉXITO!",
                        text: data.mensaje.toUpperCase(),
                        icon: "success",
                        buttons: false,
                        timer: 1000
                    }).then(() => {
                        table_autorizar.row(tr).remove().draw(false);
                        var total = parseFloat($('#myText_1').text().replace("$", "").replace(",", "")) - parseFloat(row.cantidad);
                        $("#myText_1").html("$" + formatMoney(total));

                        if (solicitudesArray.length > 0) {
                            solicitudesArray.forEach(element => {
                                optionToggle(element.idsolicitud, 2);
                            });
                            validaButtonMulAp();
                        }
                    });
                } else {
                    alert(data.mensaje);
                } /** FIN FECHA: 03-JUNIO-2025 | MEJORAR RENDIMIENTO ALERTA DA | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
            });
        });

        $('#tabla_autorizaciones').on("click", ".rechazada_da", function() {
            $.post(url + "Solicitante/rechazada_da", {
                idsolicitud: $(this).val()
            }).done(function(data) {
                data = JSON.parse(data);
                if (data.resultado) {
                    table_autorizar.ajax.reload(null, false);
                } else {
                    alert("Algo salio mal, recargue su página.")
                }
            });
        });

        $('#tabla_autorizaciones').on("click", ".congelar_solicitud", function() {
            $.post(url + "Solicitante/congelar_solicitud", {
                idsolicitud: $(this).val()
            }).done(function(data) {
                data = JSON.parse(data);
                if (data.resultado) {
                    table_autorizar.ajax.reload(null, false);
                } else {
                    alert("Algo salio mal, recargue su página.")
                }
            });
        });

        $('#tabla_autorizaciones').on("click", ".liberar_solicitud", function() {
            $.post(url + "Solicitante/liberar_solicitud", {
                idsolicitud: $(this).val()
            }).done(function(data) {
                data = JSON.parse(data);
                if (data.resultado) {
                    table_autorizar.ajax.reload(null, false);
                } else {
                    alert("Algo salio mal, recargue su página.")
                }
            });
        });

        /** INICIO FECHA: 23-MAYO-2025 | APROBACION MULTIPLE DA | @author Angel Victoriano <programador.analista30@ciudadmaderas.com>
         * Modificación: Se implementa funcionalidad para permitir aprobaciones múltiples,
         * exclusivamente cuando el usuario autenticado tiene rol 'DA'.
         */
        if (data_usuario['rol'] != 'DA') {
            table_autorizar.column(0).visible(false);
        } else if (data_usuario['rol'] == 'DA') {
            table_autorizar.column(0).visible(true);
            table_autorizar.column(1).visible(false);
            table_autorizar.column(11).visible(true);

            $('#autorizacionDA').append(`
                <div class="btn-group">
                    <button class="btn btn-warning seleccionarTodo" id="enviarMultiplesAprobaciones" onclick="askReady()" style="margin-bottom:5px; display: none;" tabindex="0" aria-controls="tabla_autorizaciones" type="button">
                        <i class="fas fa-thumbs-up"></i> ENVIAR MULTIPLES APROBACIONES 
                        &nbsp;<span class="badge pull-right" id="badgeCantidadSol"></span>
                    </button>
                </div>`);

            document.getElementById('seleccionarTodoCheckbox').addEventListener('click', function() {
                // Determinar si hay algún checkbox sin seleccionar en esta página
                let shouldSelectAll = false;

                table_autorizar.rows({
                    page: 'current'
                }).every(function() {
                    let row = this.node();
                    let checkbox = row.querySelector('input[type="checkbox"].checkboxes');
                    if (checkbox && !checkbox.checked) {
                        shouldSelectAll = true;
                        return false; // salir del bucle
                    }
                });

                // Ahora aplicamos la acción (seleccionar o deseleccionar todos)
                table_autorizar.rows({
                    page: 'current'
                }).every(function() {
                    let data = this.data();
                    let idsol = parseInt(data.idsolicitud);
                    let row = this.node();
                    let checkbox = row.querySelector('input[type="checkbox"].checkboxes');

                    if (!checkbox) return; // evitar errores si no se encuentra el checkbox

                    if (shouldSelectAll) {
                        if (!solicitudesArray.some(item => item.idsolicitud === idsol)) {
                            solicitudesArray.push({
                                idsolicitud: idsol,
                                fecelab: data.fecelab,
                                caja_chica: parseInt(data.caja_chica),
                                departamento: data.nomdepto,
                                /** FECHA: 03-JUNIO-2025 | MEJORAR RENDIMIENTO ALERTA DA | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                                cantidad: data.cantidad
                            });
                            optionToggle(idsol, 2);
                        }
                        checkbox.checked = true;
                    } else {
                        solicitudesArray = solicitudesArray.filter(item => item.idsolicitud !== idsol);
                        optionToggle(idsol, 1);
                        checkbox.checked = false;
                    }
                });

                // Actualizar estado visual del checkbox general
                document.getElementById('seleccionarTodoCheckbox').checked = shouldSelectAll;

                validaButtonMulAp();
            });

            table_autorizar.on('draw', function() {
                // Desmarcar el checkbox general inicialmente
                document.getElementById('seleccionarTodoCheckbox').checked = false;

                let totalCheckboxes = 0;
                let checkedCheckboxes = 0;

                // Marcar los checkboxes según solicitudesArray
                table_autorizar.rows({
                    page: 'current'
                }).nodes().each(function(row) {
                    let checkbox = row.querySelector('input[type="checkbox"].checkboxes');
                    let valor = checkbox?.value;

                    if (checkbox) {
                        totalCheckboxes++;
                        if (solicitudesArray.some(item => item.idsolicitud == valor)) {
                            optionToggle(checkbox.value, 2);
                            checkbox.checked = true;
                            checkedCheckboxes++;
                        } else {
                            checkbox.checked = false;
                        }
                    }
                });

                // Si todos los checkboxes visibles están activados
                if (totalCheckboxes > 0 && totalCheckboxes === checkedCheckboxes) {
                    document.getElementById('seleccionarTodoCheckbox').checked = true;
                }

                // Mostrar u ocultar el botón de aprobación múltiple
                $('#enviarMultiplesAprobaciones').toggle(solicitudesArray.length > 0);
            });
        } /** FIN FECHA: 23-MAYO-2025 | APROBACION MULTIPLE DA | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
    });

    var table_proceso;

    $('#tblsolact').on('xhr.dt', function(e, settings, json, xhr) {

        var total = 0;

        $.each(json.data, function(i, v) {
            total += parseFloat(v.cantidad);
        });

        var to = formatMoney(total);
        document.getElementById("myText_2").value = to;

        idusuario_capturista = json.idloguin_usuario;

        if (depto == 'CONSTRUCCION' && $.inArray(idusuario_capturista, ["21", "45"]) <= -1) {
            $("label [for='prioridad']").remove();
        }
    });

    $("#tblsolact").ready(function() {

        $('#tblsolact thead tr:eq(0) th').each(function(i) {
            if (i != 17) {
                var title = $(this).text();
                titulos_intxt.push(title);
                $(this).html('<input type="text" style="font-size: .9em; width:100%;" data-value="' + title + '" placeholder="' + title + '" />');

                $('input', this).on('keyup change', function() {
                    if (table_proceso.column(i).search() !== this.value) {
                        table_proceso
                            .column(i)
                            .search(this.value)
                            .draw();

                        valor_input[i] = this.value;
                    }

                    var total = 0;
                    var index = table_proceso.rows({
                        selected: true,
                        search: 'applied'
                    }).indexes();
                    var data = table_proceso.rows(index).data();

                    $.each(data, function(i, v) {
                        total += parseFloat(v.cantidad - v.pagado);
                    });
                    // console.log((typeof(v.cantidad) != 'string')? v.cantidad+' val='+v.cantidad: '');

                    var to1 = formatMoney(total);
                    document.getElementById("myText_2").value = to1;

                });
            }
        });


        table_proceso = $('#tblsolact').DataTable({
            dom: 'Brtip',
            "buttons": [{
                extend: 'excelHtml5',
                text: '<i class="fas fa-file-excel"></i> EXPORTAR REPORTE',
                titleAttr: "FACTURAS ACTIVAS",
                title: "FACTURAS ACTIVAS",
                exportOptions: {
                    format: {
                        header: function(data, columnIdx) {
                            return ' ' + titulos_intxt[columnIdx] + ' ';
                        }
                    },
                    columns: [0, 1, 2, 3, 16, 4, 5, 6, 7, 8, 9, 10, 13, 14, 15] /** FECHA: 08-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                },
                attr: {
                    class: 'btn btn-success',
                }
            }],
            "language": lenguaje,
            //"stateSave": true,
            "processing": false,
            "pageLength": 10,
            "bAutoWidth": false,
            "bLengthChange": false,
            "bInfo": false,
            "scrollX": true,
            "columns": [{
                    "width": "5%",
                    "data": function(d) {
                        return '<p style="font-size: .9em">' + d.idsolicitud + '</p>'
                    }
                },
                {
                    "width": "6%",
                    "data": function(d) {
                        return '<p style="font-size: .8em">' + d.abrev + '</p>'
                    }
                },
                {
                    "width": "12%",
                    "data": function(d) {
                        return '<p style="font-size: .8em">' + d.proyecto + '</p>'
                    }
                },
                {
                    /**  INICIO FECHA: 08-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                    "width": "9%",
                    "data": function(d) {
                        return '<p style="font-size: .8em">' + (d.tServicioPartida ? d.tServicioPartida : 'NA') + '</p>'
                    }
                }, /** FIN FECHA: 08-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                {
                    "width": "8%",
                    "data": function(d) {
                        return '<p style="font-size: .8em">' + d.folio + '</p>'
                    }
                },
                {
                    "width": "15%",
                    "data": function(d) {
                        return '<p style="font-size: .7em">' + d.nombre + '</p>' + (depto != d.nomdepto ? '<span class="label pull-center bg-orange">' + d.nomdepto + '</span>' : '') + (d.tipo_comentario ? '<span class="label pull-center bg-red">' + d.tipo_comentario + '</span>' : '');
                    }
                },
                {
                    "width": "10%",
                    "data": function(d) {
                        return '<p style="font-size: .8em">' + (d.fecelab ? formato_fechaymd(d.fecelab) : "") + '</p>'
                    }
                },
                {
                    "width": "10%",
                    "data": function(d) {
                        return '<p style="font-size: .8em">' + d.nombre_completo + '</p>'
                    }
                },
                {
                    "width": "10%",
                    "data": function(d) {

                        var resultado = d.cantidad;

                        // if( d.programado && (d.programado < 7 || d.programado != "7")  ){
                        //     resultado = d.cantidad * ( d.mpagar / d.programado );
                        // }

                        // if( d.programado && (d.programado == 7 || d.programado == "7") ){
                        //     resultado = d.cantidad * d.mpagar;
                        // }

                        return '<p style="font-size: .8em" class="text-center"> ' + formatMoney(resultado) + " " + d.moneda + '<br/><small class="label pull-center bg-blue">' + d.metoPago + '</small></p>'
                    }
                },
                {
                    "width": "9%",
                    "data": function(d) {
                        return '<p style="font-size: .8em"> ' + formatMoney(d.pagado) + '</p>'
                    }
                },
                {
                    "width": "9%",
                    "data": function(d) {

                        var resultado = d.cantidad;

                        // if( d.programado && (d.programado < 7 || d.programado != "7")  ){
                        //     resultado = d.cantidad * ( d.mpagar / d.programado );
                        // }

                        // if( d.programado && (d.programado == 7 || d.programado == "7") ){
                        //     resultado = d.cantidad * d.mpagar;
                        // }

                        return '<p style="font-size: .8em"> ' + formatMoney(resultado - d.pagado) + '</p>'
                    }
                },
                {
                    "width": "12%",
                    "data": function(d) {

                        switch (d.etapa) {
                            case 'Revisión Cuentas Por Pagar':

                                if ((d.metoPago == 'FACT BAJIO' || d.metoPago == 'FACT BANREGIO') && d.etapa == 'Revisión Cuentas Por Pagar') {
                                    return '<p style="font-size: .8em">FACTORAJE. En espera de autorizacion de pago.</p>'
                                } else {
                                    var day = today.getDay();
                                    var dias = 2 - day;

                                    if (dias > 0) {
                                        today.setDate(today.getDate() + dias);
                                    } else if (dias < 0) {
                                        today.setDate(today.getDate() + (7 + dias));
                                    } else if (dias == 0) {
                                        if (d.fecha_autorizacion == dateToDMY(today)) {
                                            today.setDate(today.getDate() + 7);
                                        }
                                    }
                                    return '<p style="font-size: .8em">Próxima revisión de Cuentas por Pagar</p>' +
                                        '<p style="font-size: .8em">' + dateToDMY(today) + '</p>';
                                }
                                break;
                            default:
                                return '<p style="font-size: .8em">' + d.etapa + '</p>'
                                break;
                        }
                    }
                },
                {
                    "data": function(d) {
                        return '<p style="font-size: .8em">' + formato_fechaymd(d.fechaCreacion) + '</p>'
                    }
                },
                {
                    "width": "10%",
                    "data": function(d) {
                        return '<p style="font-size: .8em">' + d.descripcion + '</p>'
                    }
                },
                {
                    "width": "10%",
                    "data": function(d) {
                        return '<p style="font-size: .8em">' + (d.orden_compra ? d.orden_compra : '') + '</p>'
                    }
                },
                {
                    "width": "10%",
                    "data": function(d) {
                        return '<p style="font-size: .8em">' + (d.contrato ? d.contrato : '') + '</p>' // COLUMNA 14
                    }
                },
                {
                    "width": "10%",
                    "data": function(d) {
                        return '<p style="font-size: .8em">' + (d.oficina ? d.oficina : '') + '</p>' // COLUMNA 15
                    }
                },
                {
                    "orderable": false,
                    "data": function(d) {

                        opciones = '<div class="btn-group-vertical">';
                        opciones += '<button type="button" class="btn btn-primary btn-sm consultar_modal notification" value="' + d.idsolicitud + '" data-value="BAS"><i class="fas fa-eye"></i>' + (d.visto == 0 ? '<span class="badge">!</span>' : '') + '</button>';

                        if (idusuario_capturista && $.inArray(idusuario_capturista, ["21", "45", "51", "99", "75"]) > -1) {
                            opciones += '<button type="button" class="btn btn-warning btn-sm comentario_especial" value="' + d.idsolicitud + '" data-value="BAS"><i class="fas fa-bullhorn"></i></button>';
                        }
                        return opciones + '</div>';
                    }
                }
            ],
            "columnDefs": [{
                    "orderable": false
                },
                {
                    "targets": [12],
                    "visible": false
                },
                {
                    "targets": [13],
                    "visible": false
                },
                {
                    "targets": [14],
                    "visible": false
                },
                {
                    "targets": [15, 16],
                    "visible": false
                }
            ],
            "ajax": {
                url: 'Solicitante/tabla_facturas_encurso', // Ruta al servidor o servicio para obtener datos
                type: 'POST',
                data: {
                    tipo_reporte: $("#tipo_reporte").val(),
                    fechaInicial: fechaInicial,
                    fechaFinal: fechaFinal
                }
            }
        });

        $('#tblsolact tbody').on('click', '.comentario_especial', function() {
            idsolicitud = $(this).val();
            trseleccionado = $(this).closest('tr');
            id_proveedor = table_proceso.row(trseleccionado).data().idProveedor;

            llenar_select_proyecto(id_proveedor);

            $("#formulario_especial .form-control").val("");
            $("#formulario_fact .form-control").val("");

            $("#formulario_destajo input[name='fecha_publicacion']").prop("disabled", (depto != table_proceso.row(trseleccionado).data().nomdepto));

            $("#modal_comentarios_especiales").modal();
        });

        $('#tblsolact tbody').on('click', 'td.details-control', function() {
            var tr = $(this).closest('tr');
            var row = table_proceso.row(tr);

            if (row.child.isShown()) {
                row.child.hide();
                tr.removeClass('shown');
                $(this).parent().find('.animacion').removeClass("fa-caret-down").addClass("fa-caret-right");
            } else {

                var informacion_adicional = '<table class="table text-justify">' +
                    '<tr>' +
                    '<td><strong>JUSTIFICACIÓN: </strong>' + row.data().justificacion + '</td>' +
                    '</tr>' +
                    '</table>';

                row.child(informacion_adicional).show();
                tr.addClass('shown');
                $(this).parent().find('.animacion').removeClass("fa-caret-right").addClass("fa-caret-down");
            }
        });

        $('#datepicker_from').change(function() {
            fechaInicial = $(this).val();
            fechaFinal = $("#datepicker_to").val();
            $.ajax({
                url: 'Solicitante/tabla_facturas_encurso', // Ruta al servidor o servicio para obtener datos
                type: 'POST',
                data: {
                    tipo_reporte: $("#tipo_reporte").val(),
                    fechaInicial: fechaInicial,
                    fechaFinal: fechaFinal
                }, // Enviar la nueva fecha al servidor
                success: function(result) {
                    // Actualizar la DataTable con los nuevos datos
                    resultado = JSON.parse(result);
                    table_proceso.clear().draw();
                    table_proceso.rows.add(resultado.data);
                    table_proceso.columns.adjust().draw();


                    var total = 0;
                    var index = table_proceso.rows({
                        selected: true,
                        search: 'applied'
                    }).indexes();
                    var data = table_proceso.rows(index).data();
                    $.each(data, function(i, v) {
                        total += parseFloat(v.cantidad);
                    });
                    var to1 = formatMoney(total);
                    document.getElementById("myText_2").value = to1;
                }
            });
            $("#datepicker_from").datepicker("hide");
        });

        $('#datepicker_to').change(function() {
            fechaFinal = $(this).val();
            fechaInicial = $("#datepicker_from").val();
            $.ajax({
                url: 'Solicitante/tabla_facturas_encurso', // Ruta al servidor o servicio para obtener datos
                type: 'POST',
                data: {
                    tipo_reporte: $("#tipo_reporte").val(),
                    fechaInicial: fechaInicial,
                    fechaFinal: fechaFinal
                }, // Enviar la nueva fecha al servidor
                success: function(result) {
                    // Actualizar la DataTable con los nuevos datos
                    resultado = JSON.parse(result);
                    table_proceso.clear().draw();
                    table_proceso.rows.add(resultado.data);
                    table_proceso.columns.adjust().draw();


                    var total = 0;
                    var index = table_proceso.rows({
                        selected: true,
                        search: 'applied'
                    }).indexes();
                    var data = table_proceso.rows(index).data();
                    $.each(data, function(i, v) {
                        total += parseFloat(v.cantidad);
                    });
                    var to1 = formatMoney(total);
                    document.getElementById("myText_2").value = to1;
                }
            });
            $("#datepicker_to").datepicker("hide");
        });

    });

    $("#formulario_especial").submit(function(e) {
        e.preventDefault();
    }).validate({
        submitHandler: function(form) {

            if (confirm("ESTE COMENTARIO NO SERA POSIBLE EDITAR NI BORRARLO ¿ESTA DE ACUERDO?")) {
                var data = new FormData($(form)[0]);
                data.append("idsolicitud", idsolicitud);

                $.ajax({
                    url: url + "Solicitante/comentario_especial",
                    data: data,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    method: 'POST',
                    type: 'POST', // For jQuery < 1.9
                    success: function(data) {
                        if (data.resultado) {
                            //resear_formulario();
                            $("#modal_comentarios_especiales").modal('toggle');
                        } else {
                            alert("NO SE HA PODIDO COMPLETAR LA SOLICITUD");
                        }
                    },
                    error: function() {
                        alert("Algo salio mal, recargue su página.");
                    }
                });
            }


        }
    });

    $("#formulario_fact").submit(function(e) {
        e.preventDefault();
    }).validate({
        submitHandler: function(form) {

            if (confirm("¿ESTA DE ACUERDO ENVIAR ESTA SOLICITUD HA FACTORAJE?")) {
                var data = new FormData($(form)[0]);
                data.append("idsolicitud", idsolicitud);

                $.ajax({
                    url: url + "Solicitante/factoraje_enviar",
                    data: data,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    method: 'POST',
                    type: 'POST', // For jQuery < 1.9
                    success: function(data) {

                        $("#modal_comentarios_especiales").modal('toggle');

                        table_proceso.clear();
                        table_proceso.rows.add(data.data);
                        table_proceso.draw();

                    },
                    error: function() {
                        alert("Algo salio mal, recargue su página.");
                    }
                });
            }
        }
    });

    var id;
    var trseleccionado;

    $("#formulario_destajo").submit(function(e) {
        e.preventDefault();
    }).validate({
        submitHandler: function(form) {
            var row = table_proceso.row(trseleccionado).data();

            if (row.etapa != 'Pago en Parcialidades, Esperando Proximo Abono DG') {

                switch (row.nomdepto) {
                    case 'CONSTRUCCION':
                        nuevo_gasto = "NOMINA DESTAJO";
                        break;
                    case 'NOMINA DESTAJO':
                        nuevo_gasto = "CONSTRUCCION";
                        break;
                }

                if (confirm("Estas a punto de cambiar el tipo de gasto de " + row.nomdepto + " a " + nuevo_gasto + " ¿Estas de acuerdo?")) {

                    var data = new FormData($(form)[0]);
                    data.append("ndepto", nuevo_gasto);
                    data.append("idsolicitud", row.idsolicitud);

                    $.ajax({
                        url: url + "Solicitante/nuevo_tipogasto",
                        data: data,
                        cache: false,
                        contentType: false,
                        processData: false,
                        dataType: 'json',
                        method: 'POST',
                        type: 'POST', // For jQuery < 1.9
                        success: function(data) {

                            row.nomdepto = nuevo_gasto;
                            row.fecelab = formato_fechaymd(data.resultado);
                            table_proceso.row(trseleccionado).data(row);

                            $("#modal_comentarios_especiales").modal("toggle");

                        },
                        error: function() {
                            alert("Algo salio mal, recargue su página.");
                        }
                    });

                }
            } else {
                alert("Ya se ha realizado un pago a esta solicitud con este rubro. Notifique al área correspondiente para hace el cambio correspondiente.");
            }
        }
    });

    function dateToDMY(date) {
        var d = date.getDate();
        var m = date.getMonth() + 1; //Month from 0 to 11
        var y = date.getFullYear();
        return '' + (d <= 9 ? '0' + d : d) + '/' + (m <= 9 ? '0' + m : m) + '/' + y;
    }
    /* ------------------------------------------------------------------------------------------------------- */
    var uno = "";
    var dos = "";
    var tres = "";

    function AutocompleteProveedor(data) {
        var id = $(".lista_provedores_libres").val();
        $(".lista_provedores_libres").empty().append('<option value="" selected>Seleccione una opción</option>');
        $.each(data, function(i, item) {
            $('.lista_provedores_libres').append('<option value="' + item.value + '" data-tinsumo="' + item.tinsumo + '" data-privilegio="' + item.excp + '" data-value="' + item.rfc + '">' + item.label + '</option>');
        });
    }

    /*FUNCION PARA NO CONSIDERAR FACTURAR EN LA EMPRESA ""GASTON JURY ARCE 28""*/
    /*ASI MISMO DETERMINA SI EL PROVEEDOR TENDRA O NO FACTURA*/
    $("#proveedor, #empresa").change(function() {
        if ($("#proveedor option:selected").attr("data-privilegio") == 2 || ($("#empresa option:selected").text() == 'GASTON JURY ARCE 28' && documento_xml == null)) {
            $("input[name='tentra_factura']").prop("checked", false).prop('disabled', true);
            $("input[type='hidden'][name='tentra_factura']").val("").prop('disabled', true);
        } else {
            $("input[name='tentra_factura']").prop("checked", true).prop('disabled', true);
            $("input[type='hidden'][name='tentra_factura']").val("1").prop('disabled', false);
        }
    });

    /**CONTRATOS PROVEEDORES*/
    /**AL SELECCIONAR UN PROVEEDOR*/
    $("#proveedor").change(function() {
        revison_proveedor_contratos($(this).val());

        // Si es tipo de insumo 18 (Luz Electrica) Y el usuario es de POST VENTA
        $tinsumo = $(this).find('option:selected').attr('data-tinsumo');
        if (depto == 'POST VENTA' && $tinsumo == 18) {
            $(`#empresa option`).hide();
            $(`#empresa option[value="3"]`).show();
        } else {
            $(`#empresa option`).show();
        }
    });

    //VERIFICAMOS LAS EMPRESAS QUE ESTAN RELACIONADAS CON LA TARJETA DE CREDITO
    //SOLO LAS EMPRESAS RELACIONADAS CON LA TARJETA SE PUEDE RELACIONAR EL GASTO
    $("#responsable_cc").change(function() {
        if ($("#responsable_cc option:selected").data("tempresa")) {
            if (documento_xml == null) {
                $(".lista_empresa").html('').append('<option value="">Seleccione una opción</option>');
                $.each(empresas, function(i, v) {
                    $(".lista_empresa").append('<option value="' + v.idempresa + '" data-value="' + v.rfc + '">' + v.nombre + '</option>');
                });

                $("#empresa option").each(function() {
                    if ($(this).val() != $("#responsable_cc option:selected").data("tempresa")) {
                        $(this).remove();
                    }
                });

                alert("Solo comprobación de gastos de " + $("#responsable_cc option:selected").data("rfcempresa"));
            } else {
                var encontrado = false;

                $("#empresa option").each(function(i, v) {
                    if ($(this).prop("value") == $("#responsable_cc option:selected").data("tempresa")) {
                        encontrado = true;
                        return;
                    }

                    if (!encontrado) {
                        alert("¡Factura no válida! La empresa receptora no coincide con la asignada a la TDC");
                        $("#responsable_cc").val("");
                    }

                });
            }
        }
    });

    //VERIFICAMOS SI EL PROVEEDOR TIENE CONTRATOS ACTIVOS PARA QUE EL USUARIO SELECCIONE ALGUNO.
    function revison_proveedor_contratos(idproveedor) {
        $("#cproveedor").html('<option value="" data-restante="">Seleccione una opción</option>');
        if (!depto_excep_proyecto.includes(depto))
            $('#cproveedor').append('<option value="N/A" data-restante=""N/A">N/A</option>');

        $.each(contratos, function(i, v) {
            if (v.idproveedor == idproveedor + "")
                $("#cproveedor").append('<option value="' + v.idcontrato + '" data-restante="' + parseFloat(v.cantidad - v.consumido).toFixed(2) + '">' + v.nproveedor + ' ' + v.ncontrato + ' DISP: $ ' + formatMoney(v.cantidad - v.consumido) + '</option>');
        });

        $("#cproveedor").prop("required", ($('#cproveedor > option').length > 1));
        $("#cproveedor").prop("disabled", !($('#cproveedor > option').length > 1));

    }

    // llenar select de contrato proveedor
    function llenar_select_proyecto(id_proveedor) {
        // var data = new FormData( $(form)[0] );
        const selector = $('#select_proyecto_by_proveedor');
        selector.html('<option value="">Seleccione un opción</option>');
        const data = new FormData();
        data.append("id_proveedor", id_proveedor);
        data.append("idsolicitud", idsolicitud);
        $.ajax({
            url: url + "Solicitante/proyecto_deproveedor",
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            method: 'POST',
            type: 'POST', // For jQuery < 1.9
            success: function(data) {
                var contratos_asig = Object.values(data.contratos_asig).map(value => value.idcontrato);
                data.data.map((contrato) => {
                    if (!contratos_asig.includes(contrato.idcontrato))
                        selector.append(`<option value="${contrato.idcontrato}">${contrato.nombre_contrato} / $${formatMoney(contrato.residuo)}</option>`);
                });
            },
            error: function() {
                alert("Algo salio mal, recargue su página.");
            }
        });
    }

    //Asignacion de contrato en el menu modal
    $("#formulario_contrato").submit(function(e) {
        e.preventDefault();
    }).validate({
        submitHandler: function(form) {
            const row = table_proceso.row(trseleccionado).data();
            const data = new FormData();
            const idcontrato = $('select#select_proyecto_by_proveedor').val();


            data.append('idsolicitud', row.idsolicitud);
            data.append('idcontrato', idcontrato);
            data.append('cant_solicitud', row.cantidad);

            $.ajax({
                url: url + "Solicitante/asignar_proveedor_contrato",
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                method: 'POST',
                type: 'POST', // For jQuery < 1.9
                success: function(data) {
                    // console.log(data);
                    alert(data.mensaje);
                    $("#modal_comentarios_especiales").modal('hide');
                },
                error() {
                    alert("Algo salio mal, recargue su página.");
                }
            });
        }
    });

    function obtenerProyectosDepartemento(params) {
        input = params.programado ? {
            proyecto: "proyectopr",
            oficina: 'oficinapr',
            servicioPartida: 'listado_tServicio_partidapr'
        } : {
            proyecto: "proyecto",
            oficina: 'oficina',
            servicioPartida: 'listado_tServicio_partida'
        };

        $(`#${input.proyecto}`).empty()
        $(`#${input.oficina}`).empty();

        $(`#${input.proyecto}`).append('<option value="" >Seleccione una opción</option>');
        $(`#${input.oficina}`).append('<option value="" >Seleccione una opción</option>');
        console.log(['CONSTRUCCION', 'JARDINERIA', 'OOAM', 'OOAM TECNICO', 'OOAM ADMINISTRATIVO'].includes(depto));
        if (['CONSTRUCCION', 'JARDINERIA', 'OOAM', 'OOAM TECNICO', 'OOAM ADMINISTRATIVO'].includes(depto)) {
            $('#crecibo').parent().show();
            $('#requisicion').parent().show();
            $('#orden_compra').prop('required', true);
        } else {
            $('#orden_compra').prop('required', false)
            $('#orden_compra').parent().children('label').eq(0).children('span').eq(0).remove()
            $('#crecibo').parent().hide();
            $('#requisicion').parent().hide();
        }
        // if(depto_excep_proyecto.includes(depto) || params.esApi || depto_excep_proyecto.includes(params.departamentoSolicitud)){ /** INICIO FECHA: 08-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
        //     $.each( lista_proyectos_depto[1], function( i, v){
        //         $(`#${input.proyecto}`).append('<option value="'+v.concepto+'" >'+v.concepto+'</option>');
        //     });
        //     $(`#${input.oficina}`).parent().hide();
        //     $(`#${input.servicioPartida}`).parent().hide()
        //     if(params.valueProyecto)  $(`#${input.proyecto}`).val(params.valueProyecto)
        // }else{ /** FIN FECHA: 08-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
        $.each(lista_proyectos_depto[0], function(i, v) {
            $(`#${input.proyecto}`).append('<option value="' + v.idProyectos + '" >' + v.concepto + '</option>');
        });
        $(`#${input.oficina}`).parent().show();
        $(`#${input.servicioPartida}`).parent().show();

        if (params.esProyectoNuevo == 'S' && params.valueProyecto) {
            $(`#${input.proyecto}`).val(params.valueProyecto)
            obtenerListadoOficinas({
                input: input.oficina,
                idOficina: params.valueOficina
            })
        } else if (params.esProyectoNuevo == 'N' && params.valueProyectoViejo) {
            $(`#${input.proyecto}`).append('<option value="' + params.valueProyectoViejo + '">' + params.valueProyectoViejo + '</option>');
            $(`#${input.proyecto}`).val(params.valueProyecto).change();
        }


    }

    /** INICIO FECHA: 23-MAYO-2025 | APROBACION MULTIPLE DA | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
    var solicitudesArray = [];

    function handleCheckBox(obj) {
        let idsolicitud = $(obj).val();
        let fechaElab = obj.getAttribute("data-fechaelab");
        let cajaChica = obj.getAttribute("data-cajachica");
        let nomDpto = obj.getAttribute("data-dpto");
        let cantidad = obj.getAttribute("data-cantidad");

        let totalCantidad = 0;

        if ($(obj).is(":checked")) {
            //seteo de las variables actuales
            let arrayHandler = [];
            arrayHandler['idsolicitud'] = parseInt(idsolicitud);
            arrayHandler['fecelab'] = fechaElab;
            arrayHandler['caja_chica'] = parseInt(cajaChica);
            arrayHandler['departamento'] = nomDpto;
            arrayHandler['cantidad'] = cantidad;
            //checkeado
            solicitudesArray.push(arrayHandler);
            optionToggle(idsolicitud, 2);

            $.each(solicitudesArray, function(i, v) {
                totalCantidad += parseFloat(v.cantidad);
            });
            let txt = `<strong>$${formatMoney(totalCantidad)}</strong> <label style="font-weight: normal;">DE</label>&nbsp;`;
            $("#cantidadSeleccionada").html(txt);
        } else {
            // no checkeado
            deleteItemArraySol(solicitudesArray, parseInt(idsolicitud));
            optionToggle(idsolicitud, 1);
        }
        validaButtonMulAp();
    }

    function optionToggle(idsolicitud, accion) {
        let idToDisabled = 'opEv' + idsolicitud;
        let elementToDisabled = document.getElementById(idToDisabled);

        if (!elementToDisabled) return; // <- Previene errores si no está en el DOM

        if (accion == 1) {
            elementToDisabled.removeAttribute('disabled');
            elementToDisabled.setAttribute('value', idsolicitud);
            elementToDisabled.classList.remove('not-allowed-btn');
            elementToDisabled.classList.add('btn', 'btn-success');
        } else if (accion == 2) {
            elementToDisabled.setAttribute('disabled', true);
            elementToDisabled.removeAttribute('value');
            elementToDisabled.classList.remove('btn', 'btn-success');
            elementToDisabled.classList.add('not-allowed-btn');
        }
    }

    function deleteItemArraySol(array, idsolicitud) {
        for (let i = 0; i < array.length; i++) {
            if (array[i]["idsolicitud"] === idsolicitud) {
                array.splice(i, 1);
                break; // Termina el bucle después de eliminar el elemento
            }
        }
        solicitudesArray = array;

        let totalCantidad = 0;
        $.each(solicitudesArray, function(i, v) {
            totalCantidad += parseFloat(v.cantidad);
        });
        let txt = `<strong>$${formatMoney(totalCantidad)}</strong> <label style="font-weight: normal;">DE</label>&nbsp;`;
        totalCantidad == 0 ? $("#cantidadSeleccionada").html('') : $("#cantidadSeleccionada").html(txt);
    }

    function validaButtonMulAp() {
        let cntMultiButton;

        if (solicitudesArray.length > 0) {
            $("#enviarMultiplesAprobaciones").show();
            let totalCantidad = 0;
            $.each(solicitudesArray, function(i, v) {
                totalCantidad += parseFloat(v.cantidad);
            });
            let txt = `<strong>$${formatMoney(totalCantidad)}</strong> <label style="font-weight: normal;">DE</label>&nbsp;`;
            totalCantidad == 0 ? $("#cantidadSeleccionada").html('') : $("#cantidadSeleccionada").html(txt);

            $("#badgeCantidadSol").html(solicitudesArray.length);

        } else {
            $("#enviarMultiplesAprobaciones").hide();
            $("#cantidadSeleccionada").html('');
        }

        // Ahora verificamos si TODOS los checkboxes visibles están activados
        let totalCheckboxes = 0;
        let checkedCheckboxes = 0;

        table_autorizar.rows({
            page: 'current'
        }).nodes().each(function(row) {
            let checkbox = row.querySelector('input[type="checkbox"].checkboxes');
            if (checkbox) {
                totalCheckboxes++;
                if (checkbox.checked) {
                    checkedCheckboxes++;
                }
            }
        });

        let allChecked = totalCheckboxes > 0 && totalCheckboxes === checkedCheckboxes;
        // Si todos están checked, marcamos el seleccionarTodoCheckbox
        document.getElementById('seleccionarTodoCheckbox').checked = allChecked;
    }

    function askReady() {
        $('#askReadyModal').modal();

        let contenerdorItemsAprobar = document.getElementById('elementsToApprove');
        let contenido = '<div class="row">';

        // Determinar columnas por fila y clase de columna
        let total = solicitudesArray.length;
        let columnasPorFila, colClass;

        if (total <= 10) {
            columnasPorFila = 1;
            colClass = 'col-md-12';
        } else if (total <= 20) {
            columnasPorFila = 2;
            colClass = 'col-md-6';
        } else {
            columnasPorFila = 3;
            colClass = 'col-md-4';
        }

        solicitudesArray.map((element, index) => {
            // Cerrar fila y abrir una nueva cada N elementos
            if (index % columnasPorFila === 0 && index !== 0) {
                contenido += '</div><div class="row">';
            }

            contenido += `
                <div class="${colClass}">
                    <h4>${element.idsolicitud}</h4>
                </div>
            `;
        });

        contenido += '</div>'; // cerrar la última fila
        contenerdorItemsAprobar.innerHTML = contenido;
    }

    function aceptarEnvio() {
        // parsearlo a json para enviar la data
        let handleArray = [];
        solicitudesArray.map((element, index) => {
            let handleInside = {};
            handleInside.idsolicitud = element['idsolicitud'];
            handleInside.caja_chica = element['caja_chica'];
            handleInside.fecelab = element['fecelab'];
            handleInside.departamento = element['departamento'];
            handleArray.push(handleInside);
        });

        $.ajax({
            url: 'Solicitante/aprobada_da_multiple',
            method: 'POST',
            data: {
                data: JSON.stringify(handleArray)
            },
            success: function(response) {
                // Lógica a ejecutar en caso de éxito
                response = JSON.parse(response);

                if (response.status == 'OK') {
                    /** INICIO FECHA: 03-JUNIO-2025 | MEJORAR RENDIMIENTO ALERTA DA | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                    swal({
                        title: "¡ÉXITO!",
                        text: "SE HA REALIZADO EL MOVIMIENTO CORRECTAMENTE",
                        icon: "success",
                        buttons: false,
                        timer: 1000
                    }).then(() => {
                        $('#askReadyModal').modal('toggle');
                        $("#seleccionarTodoCheckbox").prop('checked', false);

                        const ids = new Set(handleArray.map(item => parseInt(item.idsolicitud)));
                        let sumaCantidad = 0;
                        const paginaActual = table_autorizar.page();

                        // Procesamiento por lotes más eficiente
                        table_autorizar.rows({
                            search: 'none'
                        }).nodes().each((node, index) => {
                            const row = table_autorizar.row(node).data();
                            if (!row) return;

                            if (ids.has(parseInt(row.idsolicitud))) {
                                sumaCantidad += parseFloat(row.cantidad) || 0;
                                table_autorizar.row(node).remove();
                            }
                        });

                        // Restauración precisa de paginación
                        const paginasRestantes = table_autorizar.page.info().pages;
                        table_autorizar.page(Math.min(paginaActual, paginasRestantes - 1)).draw(false);

                        // Actualizar información de paginación
                        if (table_autorizar.page.info().pages !== paginaActual) {
                            // Si la eliminación afectó el número de páginas
                            table_autorizar.page(Math.min(paginaActual, table_autorizar.page.info().pages - 1)).draw(false);
                        }

                        var total = parseFloat($('#myText_1').text().replace("$", "").replace(",", "")) - parseFloat(sumaCantidad);
                        $("#myText_1").html("$" + formatMoney(total));

                        solicitudesArray = [];
                        validaButtonMulAp();
                    });
                } else {
                    /** FIN FECHA: 03-JUNIO-2025 | MEJORAR RENDIMIENTO ALERTA DA | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                    alert('Ocurrió un error, intentalo nuevamente');
                }
            },
            error: function(error) {
                // Lógica a ejecutar en caso de error
                console.log('Ocurrió un error');
            }
        });
    }

    $(window).resize(function() {
        table_autorizar.columns.adjust().draw(false);
        table_proceso.columns.adjust().draw(false);
    });

    $('.sidebar-toggle').click(function() {
        setTimeout(function() {
            // 1. Ajustar columnas
            table_autorizar.columns.adjust();
            table_proceso.columns.adjust();
            // 2. Forzar actualización de encabezados
            var headerCellsTableAutorizar = $('#tabla_autorizaciones thead th');
            headerCellsTableAutorizar.each(function() {
                var newWidth = $(this).width(); // Obtener ancho actualizado
                $(this).css('width', newWidth + 'px'); // Aplicarlo
            });
            var headerCellsTableProceso = $('#tblsolact thead th');
            headerCellsTableProceso.each(function() {
                var newWidth = $(this).width(); // Obtener ancho actualizado
                $(this).css('width', newWidth + 'px'); // Aplicarlo
            });
            // 3. Redibujar (sin resetear paginación)
            table_autorizar.draw(false);
            table_proceso.draw(false);
        }, 300); // Esperar a que termine la animación del sidebar
    });
    /** FIN FECHA: 23-MAYO-2025 | APROBACION MULTIPLE DA | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
</script>
<?php
require("footer.php");
?>