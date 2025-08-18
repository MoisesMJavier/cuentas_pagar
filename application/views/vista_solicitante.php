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
                        <h3>SOLICITUD DE PAGOS</h3>
                    </div>
                    <div class="box-body">
                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                                <li class="active"><a id="autorizaciones-tab" data-toggle="tab" href="#facturas_aturizar" role="tab" aria-controls="#home" aria-selected="true">AUTORIZACIONES FACTURAS</a></li>
                                <li><a id="facturas_pagos_adeudo-tab" data-toggle="tab" href="#facturas_pagos_adeudo" role="tab" aria-controls="#facturas_pagos_adeudo" aria-selected="true">PAGOS SIN FACTURAS</a></li>
                                <li><a id="profile-tab" data-toggle="tab" href="#facturas_pagos_finalizadas" role="tab" aria-controls="facturas_pagos_finalizadas" aria-selected="false">COMPLEMENTOS FALTANTES</a></li>
                            </ul>
                        </div>
                        <div class="tab-content">
                            <div class="active tab-pane" id="facturas_aturizar">
                                <div class="row">
                                   <!--div class="col-lg-3">
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
                                       <div class="input-group-addon">
                                           <h4><label>Total $<input style="border-bottom: none; border-top: none; border-right: none;  border-left: none; background: white;" disabled="disabled" readonly="readonly" type="text"  name="myText_1" id="myText_1"></label></h4>
                                       </div>
                                   </div-->
                                    <div class="col-lg-12">
                                        <h4>AUTORIZACIONES DE COMPRA <i class="far fa-question-circle faq" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" title="Autorizaciones" data-content="La tabla muestra todas las solicitudes pendientes de autorización para poder realizar una compra o pago a proveedor." data-placement="right"></i> </h4>
                                        <table class="table table-striped" id="tabla_autorizaciones">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th></th>
                                                    <th style="font-size: .9em">FOLIO</th>
                                                    <th style="font-size: .9em">PROYECTO</th>
                                                    <th style="font-size: .9em">FECHA</th>
                                                    <th style="font-size: .9em">PROVEEDOR</th>
                                                    <th style="font-size: .9em">CAPTURISTA</th>                                                    
                                                    <th style="font-size: .9em">CANTIDAD</th>
                                                    <th style="font-size: .9em">ESTATUS</th>
                                                    <th style="font-size: .9em"></th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                                <hr/>
                                <div class="row">
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
                                       <div class="input-group-addon">
                                           <h4><label>Total $<input style="border-bottom: none; border-top: none; border-right: none;  border-left: none; background: white;" disabled="disabled" readonly="readonly" type="text"  name="myText_2" id="myText_2"></label></h4>
                                       </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <h4>FACTURAS ACTIVAS <i class="far fa-question-circle faq" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" data-placement="right" title="Solicitudes Activas" data-content="Estas son todas las solicitudes de pago a proveedores que se encuentran el proceso de pago, verifica el status de la solicitud para saber en que parte del proceso se encuentra."></i></h4>
                                        <hr>
                                        <table class="table table-striped" id="tblsolact">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th style="font-size: .9em">EMPRESA</th>
                                                    <th style="font-size: .9em">PROYECTO</th>
                                                    <th style="font-size: .9em">ETAPA</th>
                                                    <th style="font-size: .9em">CONDOMINIO</th>
                                                    <th style="font-size: .9em">HOMOCLAVE</th>
                                                    <th style="font-size: .9em">FOLIO</th>
                                                    <th style="font-size: .9em">PROVEEDOR</th>
                                                    <th style="font-size: .9em">CONCEPTO DE FACTURA</th>
                                                    <th style="font-size: .9em">JUSTIFICACIÓN</th>
                                                    <th style="font-size: .8em">FEC FAC</th>
                                                    <th style="font-size: .9em">AUTORIZACIÓN</th>
                                                    <th style="font-size: .9em">CAPTURA</th>
                                                    <th style="font-size: .9em">CANTIDAD</th>
                                                    <th style="font-size: .9em">PAGADO</th>
                                                    <th style="font-size: .9em">RESTANTE</th>
                                                    <th style="font-size: .9em">ESTATUS</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="facturas_pagos_adeudo">
                                <div class="row">
                                    <!--div class="col-lg-2">
                                       <div class="input-group">
                                           <span class="input-group-addon"><i class="fas fa-calendar-alt"></i> <b>INICIO</b></span>
                                            <input class="form-control fechas_filtro from" type="text" id="datepicker_fromP" />
                                        </div>
                                   </div>
                                     <div class="col-lg-2">
                                       <div class="input-group">
                                           <span class="input-group-addon"><i class="fas fa-calendar-alt"></i> <b>FIN</b></span>
                                            <input class="form-control fechas_filtro to" type="text" id="datepicker_toP" />
                                        </div>
                                   </div>
                                     <div class="col-md-2">
                                       <div class="input-group-addon">
                                           <h4><label>&nbsp;Total: $<input style="border-bottom: none; border-top: none; border-right: none;  border-left: none; background: white;" disabled="disabled" readonly="readonly" type="text"  name="myText_3" id="myText_3"></label></h4>
                                       </div>
                                   </div-->
                                    <div class="col-lg-12">
                                        <h4>FACTURAS PENDIENTES PAGO</h4>
                                        <table class="table table-striped" id="tblpa">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th style="font-size: .9em">FOLIO</th>
                                                    <th style="font-size: .9em">PROVEEDOR</th>
                                                    <th style="font-size: .9em">TOTAL</th>
                                                    <th style="font-size: .9em">FECHA</th>
                                                    <th style="font-size: .9em">TIPO PAGO</th>
                                                    <th style="font-size: .9em"></th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="facturas_pagos_finalizadas">
                                <div class="row">
                                  <!--div class="col-lg-2">
                                       <div class="input-group">
                                           <span class="input-group-addon"><i class="fas fa-calendar-alt"></i> <b>INICIO</b></span>
                                            <input class="form-control fechas_filtro from" type="text" id="datepicker_fromC" />
                                        </div>
                                   </div>
                                     <div class="col-lg-2">
                                       <div class="input-group">
                                           <span class="input-group-addon"><i class="fas fa-calendar-alt"></i> <b>FIN</b></span>
                                            <input class="form-control fechas_filtro to" type="text" id="datepicker_toC" />
                                        </div>
                                   </div>
                                     <div class="col-md-2">
                                       <div class="input-group-addon">
                                           <h4><label>&nbsp;Total: $<input style="border-bottom: none; border-top: none; border-right: none;  border-left: none; background: white;" disabled="disabled" readonly="readonly" type="text"  name="myText_4" id="myText_4"></label></h4>
                                       </div>
                                   </div-->
                                    <div class="col-lg-12">
                                        <h4>FACTURAS PENDIENTES COMPLEMENTO</h4>
                                        <table class="table table-striped" id="tblsolfin">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th style="font-size: .9em">FOLIO</th>
                                                    <th style="font-size: .9em">PROVEEDOR</th>
                                                    <th style="font-size: .9em">TOTAL</th>
                                                    <th style="font-size: .9em">FECHA</th>
                                                    <th style="font-size: .9em">PAGO</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!--End tab content-->
                </div><!--end box-->
            </div>
        </div>
    </div>
</div>

<div id="modal_formulario_solicitud" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="exampleModalLabel">NUEVA FACTURA - PAGO A PROVEEDOR</h4>
            </div>
            <div class="modal-body">
                <!--p class="alert alert-warning">En caso de cerrar la ventana o actualizar la página, se eliminará la información que no hayas guardado.</p-->
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
                                                <input type="file" name="xmlfile" id="xmlfile"  class="form-control" accept="application/xml">
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
                                        <div class="col-lg-4 form-group">
                                            <label for="proyecto">PROYECTO</label>
                                            <select name="proyecto" id="proyecto" class="form-control" required></select>
                                        </div>
                                        <div class="col-lg-4 form-group">
                                            <label for="Etapa">ETAPA</label>
                                            <input type="text" name="etapa" class="form-control" id="Etapa" placeholder="Etapa">
                                        </div>
                                        <div class="col-lg-4 form-group">
                                            <label for="condominio">CONDOMINIO</label>
                                            <input type="text" name="condominio" class="form-control" id="condominio" placeholder="Condominio">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4 form-group">
                                            <label for="proyecto">HOMOCLAVE</label>
                                            <select name="homoclave" id="homoclave" class="form-control" required></select>
                                        </div>
                                        <div class="col-lg-4 form-group">
                                            <label for="empresa">EMPRESA</label>
                                            <select name="empresa" id="empresa" class="form-control lista_empresa" required></select>
                                            <input type="hidden" class="form-control" name="empresa" disabled>
                                        </div>
                                        <div class="col-lg-4 form-group">
                                            <label for="proveedor">PROVEEDOR</label>
                                            <select id="proveedor" name="proveedor" class="form-control lista_provedores_libres" required></select>
                                            <input type="hidden" class="form-control" name="proveedor" disabled>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 form-group"> 
                                            <h5>DATOS DEL TICKET / FACTURA <i class="far fa-question-circle faq" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" title="Datos de la factura" data-content="En caso de no tener la factura deberás de llenar el campo de 'descripción', 'SubTtotal','IVA','Total' y 'Método de pago', así mismo, deberás de especificar en observaciones el por qué no tiene factura. Recuerda especificar si te darán factura después." data-placement="right"></i></h5>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6 form-group">
                                            <label for="fecha">FECHA</label>
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
                                            <textarea rows="10" class="form-control" id="descr" name="descr" placeholder="Descripción"></textarea>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4 form-group">
                                            <label for="subtotal">SUBTOTAL</label>
                                            <input type="text" class="form-control" id="subtotal" name="subtotal" placeholder="SubTotal" value="">
                                        </div>
                                        <div class="col-lg-4 form-group">
                                            <label for="iva">IVA</label>
                                            <input type="text" class="form-control" id="iva" name="iva" placeholder="IVA" value="">
                                        </div>
                                        <div class="col-lg-4 form-group">
                                            <label for="total">TOTAL</label>
                                            <input type="text" class="form-control" id="total" name="total" placeholder="Total" value="" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4 form-group">
                                            <label for="total">MONEDA</label>
                                            <select class="form-control" id="moneda" name="moneda" required>
                                                <option value="MXN" data-value="MXN">MXN</option>
                                                <option value="USD" data-value="USD">CAD</option>
                                                <option value="CAD" data-value="CAD">CAD</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-4 form-group">
                                            <label for="metpag">MÉTODO DE PAGO</label>
                                            <input type="text" class="form-control" placeholder="Método de Pago" id="metpag" name="metpag" value="">
                                        </div>
                                        <div class="col-lg-4 form-group">
                                            <label for="metpag">FORMA DE PAGO</label>
                                            <select class="form-control" id="forma_pago" name="forma_pago" required>
                                                <option value="">Seleccione una opción</option>
                                                <option value="ECHQ" data-value="ECHQ">Cheque</option>
                                                <option value="TEA" data-value="TEA">Transferencia Electrónica</option>
                                                <option value="MAN" data-value="MAN">Manual</option>
                                                <option value="DOMIC" data-value="DOMIC">Domiciliario</option>
                                                <option value="EFEC" data-value="EFEC">Efectivo</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 form-group">
                                            <label for="obse">OBSERVACIONES FACTURA <i class="far fa-question-circle faq" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" title="Observaciones de la factura" data-content="En este campo pueden ser ingresados datos opcionales como descuentos, opservaciones, descripción de la operación, etc." data-placement="right"></i></label><br>
                                            <textarea class="form-control" id="obse" name="obse" placeholder="Observaciones"></textarea>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 form-group">
                                            <label for="solobs">JUSTIFICACIÓN <i class="far fa-question-circle faq" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" title="Observaciones de la solicitud" data-content="En caso de ser necesario, se podrán especificar datos, comentarios u observaciones para cuentas por pagar, DG, DP o para la dirección del departamento solicitante." data-placement="right"></i></label>
                                            <textarea class="form-control" id="solobs" name="solobs" placeholder="Observaciones de la solicitud" required></textarea>
                                        </div>
                                    </div>
 
                                    <div class="row">
                                        <div class="col-lg-12"> 
                                            <h5><b>ADICIONALES</b></h5>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-5"> 
                                            <div class="form-group">
                                                <label><b>TIPO DE PAGO</b><label>
                                                <label class="radio-inline"><input type="radio" value="0" name="servicio" checked><b>PROVEEDOR</b></label>
                                                <label class="radio-inline"><input type="radio" value="1" name="servicio1"><b>SERVICIO</b></label>
                                            </div>
                                        </div>
                                        <div class="col-lg-7"> 
                                            <div class="form-group">
                                                <br/>
                                                <label class="form-check-label" for="fecrecu"><input type="checkbox" value="1" name="caja_chica"> CAJA CHICA</label>
                                                <label class="form-check-label" for="fecrecu"><input type="checkbox" value="1" name="prioridad"> URGENTE</label>
                                                <label class="form-check-label" for="fecrecu"><input type="checkbox" value="1" name="tentra_factura"> CON FACTURA</label>
                                                <input type="hidden" name="tentra_factura" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12"> 
                                            <div class="form-group">
                                            <label for="metpag">RESPONSABLE DE CAJA CHICA</label>
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
                            <h5>ALTA DE PROVEEDOR</h5>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <input type="email" name="correo_invitacion" placeholder="Correo" class="form-control" required>
                                        <button type="submit" id="btnSnd" class="btn btn-block btn-block">Enviar</button>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <p class="text-justify">Ingresa el correo del proveedor y se le enviará un link para que se registre en nuestro sistema. Si deseas dar de alta al proveedor de manera manual solicita al departamento de cuentas por pagar su alta  <a href="mailto:cpp@ciudadmaderas.com">AQUÍ</a></p>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="factura_complemento" class="modal fade modal-alertas" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">COMPLEMENTO</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <form id="complemento_facturas">
                        <div class="col-lg-12 form-group">
                            <label>DOCUMENTO XML</label>
                            <div class="input-group">
                                <input type="file" class="form-control" id="complemento" accept="text/xml" required>
                                <div class="input-group-btn">
                                    <button type="submit" class="btn btn-danger cargar_complemento"><i class="fas fa-upload"></i></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>

    var documento_xml = null;
    var link_post = "";
    var idsolicitud = 0;
    
    $('.fechas_filtro').datepicker({
          format: 'dd/mm/yyyy'
      });

    var today = new Date();
    var yesterday = new Date( today );
    yesterday.setDate( today.getDate() - 7 );
    var fecha_minima = yesterday.toISOString().substr(0, 10);
    
    $(document).ready( function(){
        $.getJSON( url + "Listas_select/lista_proveedores_libres" ).done( function( data ){
            if( data.provedores_total != data.provedores_disponibles ){
                var listado = '<ul>';
                
                $.each( data.listado_bloqueados , function(i, v){
                    listado += '<li>'+v.nombre+'</li>';
                });
                
                listado += '</ul>';

                $.notify.addStyle('vacantes', {
                html: 
                    "<div><div class='clearfix alert alert-danger'>" +
                        "<div class='title' data-notify-html='title'/>" +listado+
                    "</div></div>"
                });

                $.notify({
                    title: "<h5><strong><i class='fas fa-exclamation text-danger'></i> ATENCIÓN</strong></h5><hr/> Hay ("+( data.provedores_total - data.provedores_disponibles )+") proveedore(s) bloqueados. Es necesario que cargue las facturas correspondientes."
                }, {
                    style: 'vacantes',
                    autoHide: true,
                    timer: 1000000,
                    clickToHide: true
                });
            }

            $("#proyecto").append('<option value="">Seleccione una opción</option>');
            $.each( data.lista_proyectos_depto, function( i, v){
                $("#proyecto").append('<option value="'+v.concepto+'">'+v.concepto+'</option>');
            });

            $("#homoclave").html('<option value="N/A">N/A</option>');
            $.each( data.lista_proyectos_homoclaves, function( i, v){
                $("#homoclave").append('<option value="'+v.concepto+'">'+v.concepto+'</option>');
            });

            $("#responsable_cc").html('');
            if( data.listado_responsable.length > 1 ){
                $("#responsable_cc").append('<option value="">Seleccione una opción</option>');
            }
            
            $.each( data.listado_responsable, function( i, v){
                $("#responsable_cc").append('<option value="'+v.idusuario+'">'+v.nombres+" "+v.apellidos+'</option>');
            });
        });
    });

    function recargar_provedores(){
        $(".lista_provedores_libres").append('<option value="">Seleccione una opción</option>');
        $.getJSON( url + "Listas_select/lista_proveedores_libres" ).done( function( data ){
            $(".lista_provedores_libres").html('');
            $(".lista_provedores_libres").append('<option value="">Seleccione una opción</option>');
            
            $.each( data.listado_disponibles, function( i, v){
                $(".lista_provedores_libres").append('<option value="'+v.idproveedor+'" data-value="'+v.rfc+'">'+v.nombre+" - "+(v.nom_banco.split(" "))[0]+'</option>');
            });
        });
    }

    $("#frmnewsol").submit( function(e) {
        e.preventDefault();
    }).validate({
        submitHandler: function( form ) {

            var data = new FormData( $(form)[0] );
            data.append("idsolicitud", idsolicitud);
            data.append("xmlfile", documento_xml);

            $.ajax({
                url: url + link_post,
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                method: 'POST',
                type: 'POST', // For jQuery < 1.9
                success: function(data){
                    if( data.resultado ){
                        resear_formulario();
                        $("#modal_formulario_solicitud").modal( 'toggle' );
                        table_autorizar.ajax.reload();
                    }else{
                        alert("NO SE HA PODIDO COMPLETAR LA SOLICITUD");
                    }
                },error: function( ){
                    
                }
            });
        }
    });

    $("input[name='correo_invitacion']").change( function(){
        if($(this).val()){
            $.post( url + "Solicitante/check_correo_invitacion", { "correo_invitacion" : $(this).val() } ).done( function( data ){
                data = JSON.parse( data );7
                if( data.resultado ){
                    $("input[name='correo_invitacion']").val('');
                    $("#duplicidad .modal-body").html("<p class='text-center'>Anteriormente se ha enviado una invitación a este proveedor.</p>");
                    $("#duplicidad").modal();
                }
            });
        }
    });

    $("#invitacion_proveedores").submit( function(e) {
        e.preventDefault();
    }).validate({
        submitHandler: function( form ) {

            var data = new FormData( $(form)[0] );
            $.ajax({
                url: url + "Solicitante/invitacion_proveedor",
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                method: 'POST',
                type: 'POST', // For jQuery < 1.9
                success: function(data){
                    if( data[0] ){
                        alert("SE HA ENVIADO CON ÉXITO LA INVITACIÓN AL PROVEEDOR");
                        $( "#invitacion_proveedores input" ).val('');
                    }else{
                        alert("NO SE HA PODIDO COMPLETAR LA SOLICITUD");
                    }
                },error: function( ){
                    
                }
            });
        }
    });

    function resear_formulario(){
        $("#modal_formulario_solicitud input.form-control").prop("readonly", false).val("");
        $("#modal_formulario_solicitud textarea").html('');

        $("#modal_formulario_solicitud #solobs").val('');
        $("#modal_formulario_solicitud textarea").prop("readonly", false);
        $("#empresa, #proveedor, #xmlfile").prop('disabled', false);
        $("#empresa option, #proveedor option").prop("selected", false);
        $("#modal_formulario_solicitud .form-control, #modal_formulario_solicitud input[type='checkbox']").prop("checked", false).prop('disabled', false);
        $("input[type='hidden']").val("").prop('disabled', true);
        $(".programar_fecha").prop('disabled', true)

        $("#responsable_cc").prop("disabled", true);

        $("#moneda").html( "" );
        $("#moneda").append( '<option value="MXN" data-value="MXN">MXN</option>' );
        $("#moneda").append( '<option value="USD" data-value="USD">USD</option>' );
        $("#moneda").append( '<option value="CAD" data-value="CAD">CAD</option>' );

        var validator = $( "#frmnewsol" ).validate();
        validator.resetForm();
        $( "#frmnewsol div" ).removeClass("has-error");

        $("#fecha").attr( "min",  fecha_minima )

        documento_xml = null;
    }
     
    function checar_caja_chica( cantidad, moneda ){
        if( moneda == 'MXN' && cantidad <= 2000 ){
            if( cantidad )
                alert( "ESTE GASTO PUEDE ENTRAR COMO CAJA CHICA" );
            $("input[name='caja_chica']").prop('disabled', false );
        }else{
            $("input[name='caja_chica']").prop('disabled', true );
            $("input[name='caja_chica']").prop('checked', false );
        }
    }

    $("input[name='caja_chica']").click( function(){

        $("#forma_pago option[data-value='ECHQ']").prop("selected", true);
        $("#responsable_cc").prop( "disabled", !( $(this).prop( "checked" ) ) );
        
    });

        $("input[name='pago_programado']").click( function(){
            $(".programar_fecha").prop("disabled", ( $(this).prop("checked") ? false : true ) )
        });

        $(document).on( "click", ".abrir_nueva_solicitud", function(){
            resear_formulario();
            recargar_provedores();
            link_post = "Solicitante/guardar_solicitud";
            $("#modal_formulario_solicitud").modal( {backdrop: 'static', keyboard: false} );
        });

        $("#recargar_formulario_solicitud").click( function(){
            resear_formulario();
            recargar_provedores();
        });

        $("#total, #moneda").change( function(){
            checar_caja_chica( $("#total").val(), $("#moneda").val() );
        });

        $("#cargar_xml").click( function(){
            subir_xml( $("#xmlfile") );
        });

        function subir_xml( input ){

            var data = new FormData();
            documento_xml = input[0].files[0];
            var xml = documento_xml;
            data.append("xmlfile", documento_xml);
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
                success: function(data){
                    if( data.respuesta[0] ){

                        documento_xml = xml;

                        var informacion_factura = data.datos_xml
                        input.prop('disabled', true);  

                        cargar_info_xml( informacion_factura );
                    
                    }else{
                        input.val('');
                        alert( data.respuesta[1] );
                    }
                },
                error: function( data ){
                    input.val('');
                    alert("ERROR INTENTE COMUNIQUESE CON EL PROVEEDOR");
                }
            });

        }

    function cargar_info_xml( informacion_factura ){

        $.each( informacion_factura.conceptos, function( i, v ){
            $("#descr").append( (i+1) + ") "+v['@attributes']['Descripcion']+" - Importe: $"+v['@attributes']['Importe']+" \n");
        });

        $("#descr").prop('readonly',true);

        var fecha = informacion_factura.fecha[0];
        $("#fecha").val( fecha.substring( 0, 10 ) ).attr('readonly', true);

        $("#fecha").attr( "min",  fecha.substring( 0, 10 ) )

        $("#folio").val( informacion_factura.folio ).attr('readonly', true);
        $("#subtotal").val( informacion_factura.SubTotal[0] ).attr('readonly', true);
        $("#iva").val( informacion_factura.Total[0] -informacion_factura.SubTotal[0] ).attr('readonly', true);
        $("#total").val( informacion_factura.Total[0] ).attr('readonly', true);
        $("#metpag").val( ( informacion_factura.MetodoPago ? informacion_factura.MetodoPago[0] : '') ).attr('readonly',true);

        $("#empresa option[data-value='"+informacion_factura.rfcrecep[0]+"']").prop("selected", true);
        $("#empresa").prop('disabled',true);
        
        
        $("#proveedor option").each( function(){
            if( $(this).attr('data-value') != informacion_factura.rfcemisor[0] ){
                $(this).remove();
            }
        });


        $("#moneda").html( '' );
        $("#moneda").append( '<option value="MXN" data-value="'+informacion_factura.Moneda[0]+'">'+informacion_factura.Moneda[0]+'</option>' );

        //$("input[name='proveedor']").val($("#proveedor").val()).prop('disabled', false);
        //$("#proveedor").prop('disabled',true);

        $("input[name='tentra_factura']").prop("checked", true).prop('disabled', true);
        $("input[type='hidden'][name='tentra_factura']").val("1").prop('disabled', false);

        var formapago = informacion_factura.formpago ? informacion_factura.formpago[0] : "N/A";

        switch( formapago ){
            case "04":
            case "28":
                $("input[name='caja_chica']").prop('disabled', ( informacion_factura.Total[0] > limite ? true : false ) );
                break;
            case "99":
                $("input[name='caja_chica']").prop( 'disabled', true );
                break;
            default:
                $("input[name='caja_chica']").prop('disabled', ( informacion_factura.Total[0] > 2000.00 ? true : false ) );
                break;
        }

        $("#obse").append( informacion_factura.condpago ? informacion_factura.condpago[0] : "NA" );
        $("#obse").prop( 'readonly', true );

        $("input[name='empresa']").val($("#empresa").val()).prop('disabled', false);
        $("#responsable_cc").prop("disabled", true);
        
    }

    var table_autorizar;

    $("#tabla_autorizaciones").ready( function () {

        $('#tabla_autorizaciones').on('xhr.dt', function ( e, settings, json, xhr ) {
            table_autorizar.button( 1 ).enable( parseInt(json.por_autorizar) > 0 );
            
            /*
            var total = 0;
            
            $.each( json.data,  function(i, v){
                total += parseFloat(v.cantidad);
            });

            var to = formatMoney(total);
            document.getElementById("myText_1").value = to;
            */
        });

        $('#tabla_autorizaciones thead tr:eq(0) th').each( function (i) {
            if( i == 1 || i == 2 || i == 4 || i == 5 ){
                var title = $(this).text();
                $(this).html( '<input type="text" style="font-size: .9em; width: 100%;" placeholder="'+title+'" />' );
        
                $( 'input', this ).on( 'keyup change', function () {
                    if ( table_autorizar.column(i).search() !== this.value ) {
                        table_autorizar
                            .column(i)
                            .search( this.value )
                            .draw();
                           var total = 0;
                           var index = table_autorizar.rows( { selected: true, search: 'applied' } ).indexes();
                           var data = table_autorizar.rows( index ).data();
                           $.each(data, function(i, v){
                               total += parseFloat(v.cantidad);
                           });
                           var to1 = formatMoney(total);
                           document.getElementById("myText_1").value = to1;
                    }
                } );
            }
        });

        table_autorizar = $('#tabla_autorizaciones').DataTable({
            dom: 'Brtip',
            buttons: [
                {
                    text: '<i class="fas fa-plus"></i> NUEVA SOLICITUD',
                    attr: {
                        class: 'btn btn-success abrir_nueva_solicitud'
                    }
                },
                {
                    text: '<i class="fas fa-print"></i> AUT. PAGO A PROVEEDORES',
                    action: function(){
                        window.open( url + "Consultar/documentos_autorizacion", "_blank")
                    },
                    attr: {
                        class: 'btn btn-danger imprimir_pago_provedores',
                    }
                }
            ],
            "language" : lenguaje,
            "processing": false,
            "pageLength": 10,
            "bAutoWidth": false,
            "bLengthChange": false,
            "bInfo": false,
            "scrollX": true,
            "columns": [
                {
                    "className": 'details-control',
                    "orderable": false,
                    "data" : null,
                    "defaultContent": '<i class="fas animacion fa-caret-right"></i>'
                },
                { 
                    "width": "8%",
                    "data" : "folio"
                },
                {
                    "width": "9%",
                    "data" : function( d ){
                        return '<p style="font-size: .9em">'+d.proyecto+'</p>'
                    }
                },
                {
                    "width": "9%",
                    "data" : function( d ){
                        return '<p style="font-size: .9em">'+d.fecelab+'</p>'
                    }
                },
                {
                    "width": "15%",
                    "data" : function( d ){
                        return '<p style="font-size: .9em">'+d.nombre+'</p>'
                    }
                },
                {
                    "width": "15%",
                    "data" : function( d ){
                        return '<p style="font-size: .9em">'+d.nombre_completo+'</p>'
                    }
                },
                {
                    "width": "12%",
                    "data" : function( d ){
                        return '<p style="font-size: .9em">$ '+formatMoney( d.cantidad )+" "+d.moneda+'</p>'
                    }
                },
                {
                    "width": "12%",
                    "data" : function( d ){
                        return '<p style="font-size: .9em">'+d.etapa+'</p>'
                    }
                },
                { 
                    "data" : "opciones",
                    "orderable": false
                }
            ],
            "columnDefs": [ {
                   "orderable": false
            }],
            "ajax":  url + "Solicitante/tabla_autorizaciones"
        });
        
      $('#datepicker_from').change( function() { 
            table_proceso.draw(); 
           var total = 0;
           var index = table_proceso.rows( { selected: true, search: 'applied' } ).indexes();
           var data = table_proceso.rows( index ).data();
           $.each(data, function(i, v){
               total += parseFloat(v.cantidad);
             });
           var to1 = formatMoney(total);
           document.getElementById("myText_2").value = to1;
        
        });
         $('#datepicker_to').change( function() {
            table_proceso.draw(); 
           var total = 0;
           var index = table_proceso.rows( { selected: true, search: 'applied' } ).indexes();
           var data = table_autorizar.rows( index ).data();
           $.each(data, function(i, v){
               total += parseFloat(v.cantidad);
             });
           var to1 = formatMoney(total);
           document.getElementById("myText_2").value = to1;
         });

        $('#tabla_autorizaciones tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = table_autorizar.row( tr );
    
            if ( row.child.isShown() ) {
                row.child.hide();
                tr.removeClass('shown');
                $(this).parent().find('.animacion').removeClass("fa-caret-down").addClass("fa-caret-right");
            }
            else {

                var informacion_adicional = '<table class="table text-justify">'+
                    '<tr>'+
                        '<td><strong>JUSTIFICACIÓN: </strong>'+row.data().justificacion+'</td>'+
                    '</tr>'+
                '</table>';

                row.child( informacion_adicional ).show();
                tr.addClass('shown');
                $(this).parent().find('.animacion').removeClass("fa-caret-right").addClass("fa-caret-down");
            }
        });

        $('#tabla_autorizaciones').on( "click", ".editar_factura", function(){

            var tr = $(this).closest('tr');
            var row = table_autorizar.row( tr );

            link_post = "Solicitante/editar_solicitud";

            var limite_edicion = row.data().idetapa > 1 ? 2 : 1;
            var ideditar =  $(this).val();
            
            resear_formulario();

            $.post( url + "Solicitante/informacion_solicitud", { idsolicitud : ideditar } ).done( function( data ){

                data = JSON.parse( data );
                if( data.resultado ){

                    $(".lista_provedores_libres").html('');
                    $(".lista_provedores_libres").append('<option value="">Seleccione una opción</option>');
                    $.each( data.proveedores_todos, function( i, v){
                        $(".lista_provedores_libres").append('<option value="'+v.idproveedor+'" data-value="'+v.rfc+'">'+v.nombre+" - "+(v.nom_banco.split(" "))[0]+'</option>');
                    });

                    idsolicitud = ideditar;
                    
                    if( data.xml ){
                        cargar_info_xml( data.xml );
                    }else{

                        $("#descr").append( data.xml ? data.xml[0].conceptos : "" );

                        $("#fecha").val( data.info_solicitud[0].fecelab );
                        
                        $("#subtotal").val( "" );
                        $("#iva").val( "" );
                        $("#total").val( data.info_solicitud[0].cantidad );
                        $("#metpag").val( "" );


                        $("#empresa option[data-value='"+data.info_solicitud[0].rfc_empresas+"']").prop("selected", true);
                        $("#proveedor option[data-value='"+data.info_solicitud[0].idProveedor+"']").prop("selected", true);

                        $("input[name='tentra_factura']").prop("checked", true);
                        $("input[name='caja_chica']").prop('checked', ( data.info_solicitud[0].caja_chica ? true : false ) );

                        $("input[name='caja_chica']").prop('disabled', ( data.info_solicitud[0].cantidad > 2000 ? true : false ) );
                        $("input[name='prioridad']").prop( "checked", ( data.info_solicitud[0].prioridad ? true : false ) );

                        $("input[name='servicio']").prop( "checked", false );
                        $("input[name='servicio'][value='"+data.info_solicitud[0].servicio+"']").prop( "checked", true );

                        

                        if( limite_edicion == "2" ){

                            $("#fecha").prop('disabled', true );
                            $("#folio").prop('disabled', true );

                            $("#empresa").prop('disabled', true );
                            $("#proveedor").prop('disabled', true );
                            
                            $("#forma_pago").prop('disabled', true );
                            $("#total").prop('disabled', true );

                            $("input[name='tentra_factura']").prop('disabled', true );
                            $("input[name='caja_chica']").prop('disabled', true );
                            $("input[name='prioridad']").prop('disabled', true );
                            $("input[name='servicio']").prop('disabled', true );

                        }

                    }
                    
                    $("#folio").val( data.info_solicitud[0].folio );
                    $("#solobs").val( data.info_solicitud[0].justificacion );

                    $("#proyecto option[value='"+data.info_solicitud[0].proyecto+"']").prop("selected", true);
                    $("#homoclave option[value='"+data.info_solicitud[0].homoclave+"']").prop("selected", true);
                    $("#forma_pago option[data-value='"+data.info_solicitud[0].metoPago+"']").prop("selected", true);
                    $("#proveedor option[value='"+data.info_solicitud[0].idProveedor+"']").prop("selected", true);

                    $("#modal_formulario_solicitud").modal( {backdrop: 'static', keyboard: false} );

                }else{
                    alert("HA OCURRIDO UN ERROR")
                }
            });
        });

        $('#tabla_autorizaciones').on( "click", ".borrar_solicitud", function(){
            $.post( url + "Solicitante/borrar_solicitud", { idsolicitud : $(this).val() } ).done( function( data ){
                data = JSON.parse( data );
                if( data.resultado ){
                    table_autorizar.ajax.reload();
                }else{
                    alert("HA OCURRIDO UN ERROR")
                }
            });
        });

        $('#tabla_autorizaciones').on( "click", ".enviar_a_dg", function(){
            $.post( url + "Solicitante/enviar_a_dg", { idsolicitud : $(this).val() } ).done( function( data ){
                data = JSON.parse( data );
                if( data.resultado ){
                    table_autorizar.ajax.reload();
                    table_proceso.ajax.reload();
                }else{
                    alert("HA OCURRIDO UN ERROR")
                }
            });
        });

        $('#tabla_autorizaciones').on( "click", ".reenviar_factura", function(){
            $.post( url + "Solicitante/reenviar_factura", { idsolicitud : $(this).val() } ).done( function( data ){
                data = JSON.parse( data );
                if( data.resultado ){
                    table_autorizar.ajax.reload();
                    table_proceso.ajax.reload();
                }else{
                    alert("HA OCURRIDO UN ERROR")
                }
            });
        });

        $('#tabla_autorizaciones').on( "click", ".aprobada_da", function(){
            $.post( url + "Solicitante/aprobada_da", { idsolicitud : $(this).val() } ).done( function( data ){
                data = JSON.parse( data );
                if( data.resultado ){
                    table_autorizar.ajax.reload();
                    table_proceso.ajax.reload();
                }else{
                    alert("HA OCURRIDO UN ERROR")
                }
            });
        });

        $('#tabla_autorizaciones').on( "click", ".rechazada_da", function(){
            $.post( url + "Solicitante/rechazada_da", { idsolicitud : $(this).val() } ).done( function( data ){
                data = JSON.parse( data );
                if( data.resultado ){
                    table_autorizar.ajax.reload();
                }else{
                    alert("HA OCURRIDO UN ERROR")
                }
            });
        });

        $('#tabla_autorizaciones').on( "click", ".congelar_solicitud", function(){
            $.post( url + "Solicitante/congelar_solicitud", { idsolicitud : $(this).val() } ).done( function( data ){
                data = JSON.parse( data );
                if( data.resultado ){
                    table_autorizar.ajax.reload();
                }else{
                    alert("HA OCURRIDO UN ERROR")
                }
            });
        });

        $('#tabla_autorizaciones').on( "click", ".liberar_solicitud", function(){
            $.post( url + "Solicitante/liberar_solicitud", { idsolicitud : $(this).val() } ).done( function( data ){
                data = JSON.parse( data );
                if( data.resultado ){
                    table_autorizar.ajax.reload();
                }else{
                    alert("HA OCURRIDO UN ERROR")
                }
            });
        });

    });

    var table_proceso;

    $('#tblsolact').on('xhr.dt', function ( e, settings, json, xhr ) {
            
        var total = 0;
            
        $.each( json.data,  function(i, v){
            total += parseFloat(v.cantidad);
        });

        var to = formatMoney(total);
        document.getElementById("myText_2").value = to;
    });

    $("#tblsolact").ready( function () {
        
        $('#tblsolact thead tr:eq(0) th').each( function (i) {
            if( i == 0 || i == 1 || i == 5 || i == 6 || i == 8 || i == 11 ){
                var title = $(this).text();
                $(this).html( '<input type="text" style="font-size: .9em; width:100%" placeholder="'+title+'" />' );
        
                $( 'input', this ).on( 'keyup change', function () {
                    if ( table_proceso.column(i).search() !== this.value ) {
                        table_proceso
                            .column(i)
                            .search( this.value )
                            .draw();
                    }
                } );
            }
        });
        

        table_proceso = $('#tblsolact').DataTable({
            dom: 'Brtip',
            "buttons": [
                {
                    extend: 'excelHtml5',             
                    text: '<i class="fas fa-file-excel"></i> EXPORTAR EXCEL',
                    messageTop: "Lista de pagos autorizados por Dirección General (CHEQUES)",
                    attr: {
                        class: 'btn btn-success'       
                    },
                    exportOptions: {
                        columns: [0,1,5,6,9,11,12,13,14,15],
                        format: {
                            header: function (data, columnIdx) { 

                                data = data.replace( '<input type="text" style="font-size: .9em; width:100%" placeholder="', '' );
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
            "bAutoWidth": false,
            "bLengthChange": false,
            "bInfo": false,
            "scrollX": true,
            "columns": [
                {
                    "width": "10%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.abrev+'</p>'
                    }
                },
                {
                    "width": "16%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.proyecto+'</p>'
                    }
                },
                {
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+(d.soletapa ? d.soletapa : '-')+'</p>'
                    }
                },
                {
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+(d.condominio ? d.condominio : '-')+'</p>'
                    }
                },
                {
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+(d.homoclave ? d.homoclave : 'N/A')+'</p>'
                    }
                },
                {
                    "width": "10%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.folio+'</p>'
                    }
                },
                {
                    "width": "15%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.nombre+'</p>'
                    }
                },
                {
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.descripcion+'</p>'
                    }
                },
                {
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.justificacion+'</p>'
                    }
                },
                {
                    "width": "10%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.fecelab+'</p>'
                    }
                },
                {
                    "width": "9%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.fecha_autorizacion+'</p>'
                    }
                },
                {
                    "width": "15%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.nombre_completo+'</p>'
                    }
                },
                {
                    "width": "15%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">$ '+formatMoney(d.cantidad)+" "+d.moneda+'</p>'
                    }
                },
                {
                    "width": "9%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">$ '+formatMoney(d.pagado)+'</p>'
                    }
                },
                {
                    "width": "9%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">$ '+formatMoney( d.cantidad - d.pagado )+'</p>'
                    }
                },
                {
                    "width": "9%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.etapa+'</p>'
                    }
                },
                {
                    "orderable": false,
                    "data": function( d ){
                        return '<div class="btn-group-vertical"><button type="button" class="btn btn-primary consultar_modal notification" value="'+d.idsolicitud+'" data-value="BAS"><i class="fas fa-eye"></i>'+(d.visto == 0 ? '<span class="badge">!</span>' : '')+'</button></div>';
                    }
                }
            ],
            "columnDefs": [ {
                   "orderable": false
                },
                {
                    "targets": [ 2 ],
                    "visible": false,
                    "searchable": false
                },
                {
                    "targets": [ 3 ],
                    "visible": false,
                    "searchable": false
                },
                {
                    "targets": [ 4 ],
                    "visible": false,
                    "searchable": false
                },
                {
                    "targets": [ 7 ],
                    "visible": false,
                    "searchable": false
                },
                {
                    "targets": [ 8 ],
                    "visible": false,
                    "searchable": false
                },
                {
                    "targets": [ 10 ],
                    "visible": false,
                    "searchable": false
                }
            ],
            "ajax":  url + "Solicitante/tabla_facturas_encurso"
        });

        $('#tblsolact tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = table_proceso.row( tr );
    
            if ( row.child.isShown() ) {
                row.child.hide();
                tr.removeClass('shown');
                $(this).parent().find('.animacion').removeClass("fa-caret-down").addClass("fa-caret-right");
            }
            else {

                var informacion_adicional = '<table class="table text-justify">'+
                    '<tr>'+
                        '<td><strong>JUSTIFICACIÓN: </strong>'+row.data().justificacion+'</td>'+
                    '</tr>'+
                '</table>';

                row.child( informacion_adicional ).show();
                tr.addClass('shown');
                $(this).parent().find('.animacion').removeClass("fa-caret-right").addClass("fa-caret-down");
            }
        });

    });

    var table_pagos_sin_factura;
    var id;
    var link_complentos;

    $("#complemento_facturas").submit( function(e) {
        e.preventDefault();
    }).validate({
        submitHandler: function( form ) {

            var data = new FormData();
            data.append("id", id);
            data.append("xmlfile", $("#complemento")[0].files[0]);

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
                         if(link_complentos == "Solicitante/cargaxml_pagos"){
                          
                        table_pagos_sin_factura.ajax.reload();
                       }else{
                        table_complemento.ajax.reload();
                    }
                    }else{
                        alert( data.respuesta[1] );
                    }
                },error: function( ){
                    
                }
            });
        }
    });

    $("#tblpa").ready( function () {
        
        /*
        $('#tblpa').on('xhr.dt', function ( e, settings, json, xhr ) {
            var total = 0;
            $.each( json.data,  function(i, v){
            total += parseFloat(v.cantidad);
            });
            var to = formatMoney(total);
            document.getElementById("myText_3").value = to;
            
        });
        */

        $('#tblpa thead tr:eq(0) th').each( function (i) {
            if( i != 2 && i != 3 && i != 5){
                var title = $(this).text();
                $(this).html( '<input type="text" style="width:100%" placeholder="'+title+'" />' );
        
                $( 'input', this ).on( 'keyup change', function () {
                    if ( table_pagos_sin_factura.column(i).search() !== this.value ) {
                        table_pagos_sin_factura
                            .column(i)
                            .search( this.value )
                            .draw();
                           var total = 0;
                           var index = table_pagos_sin_factura.rows( { selected: true, search: 'applied' } ).indexes();
                           var data = table_pagos_sin_factura.rows( index ).data();
                           $.each(data, function(i, v){
                               total += parseFloat(v.cantidad);
                           });
                           var to1 = formatMoney(total);
                           document.getElementById("myText_3").value = to1;
                    }
                } );
            }
        });

        table_pagos_sin_factura = $('#tblpa').DataTable({
            dom: 'rtip',
            "language" : lenguaje,
            "processing": false,
            "pageLength": 10,
            "bAutoWidth": false,
            "bLengthChange": false,
            "bInfo": false,
            "scrollX": true,
            "columns": [
                {
                    "width": "10%",
                    "data" : function( d ){
                        return '<p style="font-size: .9em">'+d.folio+'</p>'
                    }
                },
                { 
                    "data" : function( d ){
                        return '<p style="font-size: .9em"> '+d.nombre+'</p>';
                    }
                },
                { 
                    "width": "9%",
                    "data" : function( d ){
                        return '<p style="font-size: .9em">$ '+ formatMoney(d.cantidad)+" "+d.moneda+'</p>';
                    }
                },
                { 
                    "width": "10%",
                    "data" : function( d ){
                        return '<p style="font-size: .9em"> '+d.fechaOpe+'</p>';
                    }
                },
                {
                    "width": "11%",
                    "data" : function( d ){
                        return '<p style="font-size: .9em"> '+d.formaPago+'</p>';
                    }
                },
                {
                    "data": function( d ){
                        var opciones = '<div class="btn-group-vertical">';
                        
                        opciones += '<button type="button" class="btn btn-primary consultar_modal notification" value="'+d.idsolicitud+'" data-value="BAS"><i class="fas fa-eye"></i>'+(d.visto == 0 ? '</i><span class="badge">!</span>' : '')+'</button>';
                        opciones += '<button type="button" class="btn btn-warning subir_factura_pago" value="'+d.idpago+'"><i class="fas fa-file-export"></i></button>';
                        
                        return opciones += '</div>';
                    },
                    "orderable": false,
                }
            ],
            "order": [[1, 'asc']],
            "ajax":  url + "Solicitante/tabla_pagos_sin_factura"
        });
        
           $('#datepicker_fromP').change( function() { 
           table_pagos_sin_factura.draw(); 
           var total = 0;
           var index = table_pagos_sin_factura.rows( { selected: true, search: 'applied' } ).indexes();
           var data = table_pagos_sin_factura.rows( index ).data();
           $.each(data, function(i, v){
               total += parseFloat(v.cantidad);
             });
           var to1 = formatMoney(total);
           document.getElementById("myText_3").value = to1;
        
        });
         $('#datepicker_toP').change( function() { table_pagos_sin_factura.draw(); 
         
         });

        $("#tblpa tbody").on('click', '.subir_factura_pago', function(){
            id = $(this).val();
            link_complentos = "Solicitante/cargaxml_pagos";

            $("#factura_complemento").modal();
        });
    });

    var table_complemento;
    
    $("#tblsolfin").ready( function () {
        
        /*
        $('#tblsolfin').on('xhr.dt', function ( e, settings, json, xhr ) {
            var total = 0;
            $.each( json.data,  function(i, v){
            total += parseFloat(v.cantidad);
            });
            var to = formatMoney(total);
            document.getElementById("myText_4").value = to;
        
        });
        */

        $('#tblsolfin thead tr:eq(0) th').each( function (i) {
            if( i != 5 && i != 2 && i != 3 ){
                var title = $(this).text();
                $(this).html( '<input type="text" style="width:100%" placeholder="'+title+'" />' );
        
                $( 'input', this ).on( 'keyup change', function () {
                    if ( table_complemento.column(i).search() !== this.value ) {
                        table_complemento
                            .column(i)
                            .search( this.value )
                            .draw();
                           var total = 0;
                           var index = table_complemento.rows( { selected: true, search: 'applied' } ).indexes();
                           var data = table_complemento.rows( index ).data();
                           $.each(data, function(i, v){
                               total += parseFloat(v.cantidad);
                           });
                           var to1 = formatMoney(total);
                           document.getElementById("myText_4").value = to1;
                    }
                } );
            }
        });

        table_complemento = $('#tblsolfin').DataTable({
            dom: 'rtip',
            "language" : lenguaje,
            "processing": false,
            "pageLength": 10,
            "bAutoWidth": false,
            "bLengthChange": false,
            "bInfo": false,
            "scrollX": true,
            "columns": [
                {
                    "width": "10%", 
                    "data" : function( d ){
                        return '<p style="font-size: .9em">'+d.folio+'</p>'
                    }
                },
                {
                    "data" : function( d ){
                        return '<p style="font-size: .9em"> '+d.folio+'</p>';
                    }
                },
                {
                    "width": "10%",
                    "data" : function( d ){
                        return '<p style="font-size: .9em">'+d.nombre+'</p>'
                    }
                },
                { 
                    "width": "9%",
                    "data" : function( d ){
                        return '<p style="font-size: .9em">$ '+ formatMoney(d.cantidad)+'</p>'
                    }
                },
                { 
                    "width": "10%",
                    "data" : function( d ){
                        return '<p style="font-size: .9em"> '+d.fechaOpe+'</p>'
                    }
                },
                { 
                    "width": "10%",
                    "data" : function( d ){
                        return '<p style="font-size: .9em"> '+d.formaPago+'</p>'
                    }
                },
                {
                    "data": function( d ){
                        var opciones = '<div class="btn-group-vertical">';
                        
                        opciones += '<button type="button" class="btn btn-primary consultar_modal notification" value="'+d.idsolicitud+'" data-value="BAS"><i class="fas fa-eye"></i>'+(d.visto == 0 ? '</i><span class="badge">!</span>' : '')+'</button>';
                        opciones += '<button type="button" class="btn btn-warning subir_factura_pago" value="'+d.idsolicitud+'"><i class="fas fa-file-export"></i></button>';
                        
                        return opciones += '</div>';
                    },
                    "orderable": false
                }
            ],
            "order": [[1, 'asc']],
            "ajax":  url + "Solicitante/tabla_pagos_sin_complemento"
        });
        
        $('#datepicker_fromC').change( function() { 
           table_complemento.draw(); 
           var total = 0;
           var index = table_complemento.rows( { selected: true, search: 'applied' } ).indexes();
           var data = table_complemento.rows( index ).data();
           $.each(data, function(i, v){
               total += parseFloat(v.cantidad);
             });
           var to1 = formatMoney(total);
           document.getElementById("myText_4").value = to1;
        
        });
         $('#datepicker_toC').change( function() { table_complemento.draw(); 
         
         });

        $("#tblsolfin tbody").on('click', '.subir_factura_pago', function(){
            id = $(this).val();
            link_complentos = "Solicitante/cargaxml_complemento";

            $("#factura_complemento").modal();
        });
    });
    
    $.fn.dataTableExt.afnFiltering.push( function( oSettings, aData, iDataIndex ) {

        if( oSettings.nTable.getAttribute('id') == "tblsolact" ){

            var iFini = '';
            $('.from').each(function(i,v) {
                    
                if($(this).val() !=''){
                    iFini = $(this).val();
                    return false;
                }
            }); 
                    
            var iFfin = ''; 
            
            $('.to').each(function(i,v) {
                if($(this).val() !=''){
                    iFfin = $(this).val();
                    return false;
                    }
            }); 
                    
            var iStartDateCol = 9;
            var iEndDateCol = 9;

            iFini=iFini.substring(6,10) + iFini.substring(3,5)+ iFini.substring(0,2);
            iFfin=iFfin.substring(6,10) + iFfin.substring(3,5)+ iFfin.substring(0,2);
                //alert(iFini);
                // alert(iFfin);
            var datofini=aData[iStartDateCol].substring(6,10) + aData[iStartDateCol].substring(3,5)+ aData[iStartDateCol].substring(0,2);
            var datoffin=aData[iEndDateCol].substring(6,10) + aData[iEndDateCol].substring(3,5)+ aData[iEndDateCol].substring(0,2);
                    //alert(datofini);
                    //alert(datoffin);
            if ( iFini === "" && iFfin === "" ){
                return true;
            }else if ( iFini <= datofini && iFfin === ""){
                return true;
            }else if ( iFfin >= datoffin && iFini === ""){
                return true;
            }else if (iFini <= datofini && iFfin >= datoffin){
                return true;
            }
            
            return false;
        }else{
            return true;
        }

	});

    
$(".lista_empresa").ready( function(){
        $(".lista_empresa").append('<option value="">Seleccione una opción</option>');
        $.getJSON( url + "Listas_select/lista_empresas").done( function( data ){
            $.each( data, function( i, v){
                $(".lista_empresa").append('<option value="'+v.idempresa+'" data-value="'+v.rfc+'">'+v.nombre+'</option>');
            });
        });
    });
</script>
<?php
    require("footer.php");
?>