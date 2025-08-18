<?php
    require("head.php");
    require("menu_navegador.php");
?> 
<style>
    .justificacion-cell {
        font-size: 0.9em; /* Tamaño de fuente deseado */
        max-height: 80px; /* Ajusta la altura máxima según tus preferencias */
        overflow-y: auto; /* Agrega barras de desplazamiento vertical si es necesario */
    }
</style>
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <div class="box">
                    <div class="box-header with-border">
                        <h3>HISTORIAL DE CHEQUES</h3>
                    </div>
                    <div class="box-body">
                                <div class="nav-tabs-custom">
                                    <ul class="nav nav-tabs">
                                        <li class="active"><a id="listado_solicitudes-tab" data-toggle="tab" href="#listado_solicitudes" role="tab" aria-controls="#listado_solicitudes" aria-selected="true">CHEQUES ACTIVOS</a></li>
                                        <li><a id="listado_pagos-tab" data-toggle="tab" href="#listado_pagos" role="tab" aria-controls="#listado_pagos" aria-selected="true">CHEQUES CANCELADOS</a></li>
                                        <li><a id="listado_cheques-tab" data-toggle="tab" href="#listado_cheques" role="tab" aria-controls="#listado_cheques" aria-selected="true">CHEQUES COBRADOS</a></li>
                                        <li><a id="listado_allcheques-tab" data-toggle="tab" href="#listado_allcheques" role="tab" aria-controls="#listado_allcheques" aria-selected="true">TODOS LOS CHEQUES</a></li>
                                    </ul>
                                </div>
                                <div class="tab-content">
                                    <div class="active tab-pane" id="listado_solicitudes">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <form id="formulario_cheques_activos" autocomplete="off" action="<?= site_url("Reportes/reporte_pagos_auth_DG") ?>" method="post">
                                                    <div class="col-lg-2" style="margin-bottom: 15px;">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fas fa-calendar-alt"></i> <b>INICIO</b></span>
                                                                <input class="form-control fechas_filtro from" type="text" id="fecInicial" name="fechaInicial" maxlength="10"/>
                                                            </div>
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fas fa-calendar-alt"></i> <b>FIN</b></span>
                                                                <input class="form-control fechas_filtro to" type="text" id="fecFinal" name="fechaFinal" maxlength="10"/>
                                                            </div>
                                                    </div>
                                                    <div class="col-lg-1">
                                                        <div class="input-group-addon">
                                                            <h4 style="padding: 0; margin:0;"><label>&nbsp;Total: $<input style="text-align: right; border-bottom: none; border-top: none; border-right: none;  border-left: none; background: white;" disabled="disabled" readonly="readonly" type="text"  name="myText_1" id="myText_1"></label></h4>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">                                         
                                                    </div>
                                                    <div id="elementos_hidden1"></div>
                                                    <div id="tipo_reporte1"><input type="hidden" name="tipo_reporte" value="listado_solicitudes-tab"></div>
                                                </form>
                                                <table class="table table-striped" id="tablahistorialsolicitudes">
                                                    <thead>
                                                        <tr>
                                                            <th style="font-size: .8em"># SOLICITUD</th>
                                                            <th style="font-size: .8em">RESPONSABLE</th>
                                                            <th style="font-size: .8em">EMPRESA</th>
                                                            <th style="font-size: .8em">FECHA OPERACIÓN</th>
                                                            <th style="font-size: .8em">CANTIDAD</th>
                                                            <th style="font-size: .8em">REFERENCIA</th>
                                                            <th style="font-size: .8em">TIPO PAGO</th>
                                                            <th style="font-size: .8em">ESTATUS</th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="listado_pagos">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <form id="formulario_cheques_cancelados" autocomplete="off" action="<?= site_url("Reportes/reporte_pagos_auth_DG") ?>" method="post">
                                                    <div class="col-lg-3" style="margin-bottom: 15px;">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fas fa-calendar-alt"></i> <b>INICIO</b></span>
                                                                <input class="form-control fechas_filtro from" type="text" id="fecInicialP" name="fechaInicial" maxlength="10"/>
                                                            </div>
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fas fa-calendar-alt"></i> <b>FIN</b></span>
                                                                <input class="form-control fechas_filtro to" type="text" id="fecFinalP" name="fechaFinal" maxlength="10"/>
                                                            </div>
                                                    </div>
                                                    <div id="elementos_hidden2"></div>
                                                    <div id="tipo_reporte2"><input type="hidden" name="tipo_reporte" value=""></div>
                                                </form>
                                                <!--<div class="col-md-2">
                                                    <div class="input-group-addon">
                                                        <h4><label>&nbsp;Total: $<input style="border-bottom: none; border-top: none; border-right: none;  border-left: none; background: white;" disabled="disabled" readonly="readonly" type="text"  name="myText_2" id="myText_2"></label></h4>
                                                    </div>
                                                </div>-->
                                            
                                                <table class="table table-striped" id="tablahistorialpagos">
                                                    <thead>
                                                        <tr> 
                                                            <th style="font-size: .8em"># SOLICITUD</th>           
                                                            <th style="font-size: .8em">RESPONSABLE</th>
                                                            <th style="font-size: .8em">EMPRESA</th>
                                                            <th style="font-size: .8em">FECHA CANC</th>
                                                            <th style="font-size: .8em">FOLIO CANCELADO</th>
                                                            <th style="font-size: .8em">FOLIO REMPLAZO</th>
                                                            <th style="font-size: .8em">CANTIDAD</th>                                                            
                                                            <th style="font-size: .8em">TIPO PAGO</th>
                                                            <th style="font-size: .8em">MOTIVO</th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="listado_cheques">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <form id="formulario_cheques_quebrados" autocomplete="off" action="<?= site_url("Reportes/reporte_pagos_auth_DG") ?>" method="post">
                                                    <div class="col-lg-3" style="margin-bottom: 15px;">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fas fa-calendar-alt"></i> <b>INICIO</b></span>
                                                                <input class="form-control fechas_filtro from" type="text" id="fecInicialC" name="fechaInicial" maxlength="10"/>
                                                            </div>
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fas fa-calendar-alt"></i> <b>FIN</b></span>
                                                                <input class="form-control fechas_filtro to" type="text" id="fecFinalC" name="fechaFinal" maxlength="10"/>
                                                            </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="input-group-addon" style="padding: 4px;">
                                                            <h4 style="padding: 0; margin:0;"><label>&nbsp;Total: $<input style="text-align:right;  border-bottom: none; border-top: none; border-right: none;  border-left: none; background: white;" disabled="disabled" readonly="readonly" type="text"  name="myText_3" id="myText_3"></label></h4>
                                                        </div>
                                                    </div>
                                                    <div id="elementos_hidden3"></div>
                                                    <div id="tipo_reporte3"><input type="hidden" name="tipo_reporte" value=""></div>
                                                </form>
                                                <table class="table table-striped" id="tablachequesCobrados">
                                                    <thead>
                                                        <tr>
                                                            <th style="font-size: .8em"># SOLICITUD</th>
                                                            <th style="font-size: .8em">PROVEEDOR</th>
															<th style="font-size: .8em">RESPONSABLE</th>
															<th style="font-size: .8em">FECHA COBRO</th>
															<th style="font-size: .8em">USUARIO ALTA</th>
                                                            <th style="font-size: .8em">EMPRESA</th>
                                                            <th style="font-size: .8em">CANTIDAD</th>
                                                            <th style="font-size: .8em">REFERENCIA</th>
                                                            <th style="font-size: .8em">TIPO PAGO</th>
                                                            <th style="font-size: .8em">FOLIO</th>  
                                                            <th style="font-size: .8em">FECHA DISPERSIÓN</th>
                                                            <th style="font-size: .8em">FECHA AUTORIZADO</th>
                                                            <th style="font-size: .8em">DEPARTAMENTO</th>
                                                            <th style="font-size: .8em">PROYECTO</th>
                                                            <th style="font-size: .8em">ETAPA</th>
                                                            <th style="font-size: .8em">CONDOMINIO</th>
                                                            <th style="font-size: .8em">JUSTIFICACION</th>
                                                            <th style="font-size: .8em">METODO PAGO</th>
                                                            <th style="font-size: .8em">FACTURA</th>
                                                            <th style="font-size: .8em">DESCRIPCION DE FACTURA</th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="listado_allcheques">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <form id="formulario_cheques_todos" autocomplete="off" action="<?= site_url("Reportes/reporte_pagos_auth_DG") ?>" method="post">
                                                    <div class="col-lg-3" style="margin-bottom: 15px;">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fas fa-calendar-alt"></i> <b>INICIO</b></span>
                                                                <input class="form-control fechas_filtro from" type="text" id="fecInicialTC" name="fechaInicial" maxlength="10"/>
                                                            </div>
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fas fa-calendar-alt"></i> <b>FIN</b></span>
                                                                <input class="form-control fechas_filtro to" type="text" id="fecFinalTC" name="fechaFinal" maxlength="10"/>
                                                            </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="input-group-addon" style="padding: 4px;" >
                                                            <h4 style="padding: 0; margin:0;"><label>&nbsp;Total: $<input style="text-align:right; border-bottom: none; border-top: none; border-right: none;  border-left: none; background: white;" disabled="disabled" readonly="readonly" type="text"  name="myText_4" id="myText_4"></label></h4>
                                                        </div>
                                                    </div>
                                                    <div id="elementos_hidden4"></div>
                                                    <div id="tipo_reporte4"><input type="hidden" name="tipo_reporte" value=""></div>
                                                </form>
                                                <table class="table table-striped" id="tablaTodoCheques">
                                                    <thead>
                                                        <tr>
                                                            <th style="font-size: .8em"># SOLICITUD</th>
                                                            <th style="font-size: .8em">RESPONSABLE</th>
                                                            <th style="font-size: .8em">EMPRESA</th> 
                                                            <th style="font-size: .8em">FECHA OPERACIÓN</th>
                                                            <th style="font-size: .8em">CANTIDAD</th>
                                                            <th style="font-size: .8em">REFERENCIA</th>
                                                            <th style="font-size: .8em">ESTATUS</th>
                                                            <th style="font-size: .8em">TIPO PAGO</th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <!--</div>
                        </div>-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade modal-alertas" id="modal_editar_cheque" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"  style="background: #00A65A; color: #fff;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">REMPLAZAR CHEQUE</h4>
            </div>  

            <form method="post" id="form_cheques">
              
                <div class="modal-body">
                    <h5 id="informacion_cheque"></h5>
                    <div class="row">
                        <div class="col-lg-12 form-group">
                           <label>CUENTA</label>
                           <select style="width: 100%;" id="cuenta_valor" name="cuenta_valor" class=" form-control lista_cta" required> </select> 
                        </div>
                        <div class="col-lg-12 form-group">
                           <input type="text" name="serie_cheque" id="serie_cheque" class="form-control">
                           <input type="hidden" name="numpago" id="numpago" readonly="readonly" class="form-control">
                           <input type="hidden" name="numctaserie" id="numctaserie" readonly="readonly" class="form-control">
                        </div>   
                        <div class="col-lg-12 form-group">
                            <label>OBSERVACIONES</label>
                            <textarea class="form-control" name="observacion" required></textarea>
                        </div>                       
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-lg-offset-3 form-group">
                            <button class="btn btn-block btn-danger">EDITAR</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>



<div id="modal_agregar_ch" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="exampleModalLabel">NUEVO CHEQUE</h4>
            </div>
            <div class="modal-body">
                    <div id="generar_solicitud_cheque">
                        <div class="row">
                            <div class="col-lg-12"> 
                                <form id="frm_addcheque" method="post" action="#">    
                                    <div class="row">
                                        <!-- <div class="col-lg-6 form-group">
                                            <label for="proyecto">PROYECTO</label><br/>
                                            <select name="proyecto" id="proyecto" class="form-control lista_proyecto" required></select>
                                        </div> -->
                                        <div class="col-lg-12 form-group">
                                            <label for="empresa">EMPRESA</label>
                                            <select name="empresa" id="empresa" class="form-control lista_empresa" required></select>
                                        </div>
                                    </div>
                                    <!-- <div class="row">
                                        <div class="col-lg-6 form-group">
                                            <label for="proveedor">PROVEEDOR</label><br/>
                                            <select id="proveedor" name="proveedor" class="form-control lista_proveedores" required></select>
                                        </div>
                                        <div class="col-lg-6 form-group">
                                            <label for="estatus_ch">ESTATUS</label>
                                            <select class="form-control" id="estatus_ch" name="estatus_ch" required>
                                                <option value="">Seleccione una opción</option>
                                                <option value="15">POR ENTREGAR</option>
                                                <option value="14">ENTREGADO</option>
                                                <option value="30">CANCELADO</option>
                                            </select>
                                        </div>
                                    </div> -->
                                    <div class="row">
                                        <div class="col-lg-6 form-group">
                                            <label for="fecha">FECHA</label>
                                            <input type="date" class="form-control" min='2019-04-01' id="fecha" name="fecha" placeholder="Fecha" value="" required>
                                        </div>
                                        <div class="col-lg-6 form-group">
                                            <label for="ncheque"># CHEQUE</label>
                                            <input type="text" class="form-control" id="ncheque" name="ncheque" placeholder="# de cheque" value="" required>
                                        </div>
                                        <!-- <div class="col-lg-4 form-group">
                                            <label for="cantidad">CANTIDAD</label>
                                            <input type="text" class="form-control" id="cantidad" name="cantidad" placeholder="Cantidad" value="" required>
                                        </div> -->
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-lg-12 form-group">
                                            <label for="solobs">JUSTIFICACIÓN <i class="far fa-question-circle faq" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" title="Observaciones de la solicitud" data-content="En caso de ser necesario, se podrán especificar datos, comentarios u observaciones para cuentas por pagar, DG, DP o para la dirección del departamento solicitante." data-placement="right"></i></label>
                                            <textarea class="form-control" id="solobs" name="solobs" placeholder="Observaciones de la solicitud" required></textarea>
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
</div>




<div class="modal fade modal-alertas" id="modal_cancelar_cheque" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"  style="background: #00A65A; color: #fff;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">CANCELAR CHEQUE DEFINITIVAMENTE</h4>
            </div>  

            <form method="post" id="form_cheques_CANCELA">
              
                <div class="modal-body">
                    <h5 id="informacion_canc"></h5>
                    <div class="row">
 
                        <div class="col-lg-12 form-group">
                            <label>OBSERVACIONES</label>
                            <textarea class="form-control" name="observacion" id="observacion" required></textarea>
                        </div>                       
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-lg-offset-3 form-group">
                            <button class="btn btn-block btn-danger">ACEPTAR</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!--OPCIONES PARA UN CHEQUE REGISTRADO-->
<div class="modal fade modal-alertas" id="modal_confirmar_cheque" role="dialog">
    <div class="modal-dialog">
         <div class="modal-content">
            <div class="modal-header bg-yellow">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">SUSTITUIR EL CHEQUE</h4>
            </div>  
            <div class="modal-body">              
                <div class="row">
                    <div class="col-lg-12 form-group">
                        <button type="button" class="btn btn-block bg-aqua regresar" data-dismiss="modal"><i class="fa fa-retweet"></i>AHORA ES TEA</button>
                        <hr/>
                        <button type="button" class="btn btn-block bg-olive confirmacion"><i class="fa fa-edit"></i>CAMBIO CONSECUTIVO</button>
                        <hr/>
                        <button type="button" class="btn btn-block bg-red regresar_definitiva" data-dismiss="modal"><i class="fa fa-close"></i>CANCELAR DEFINITIVAMENTE</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!---->
<div class="modal fade modal-alertas" id="modal_observacion_cheque" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"  style="background: #00A65A; color: #fff;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">OBSERVACION DEL CHEQUE</h4>
            </div>  
            <form method="post" id="form_obv_cheques">
                <div class="modal-body">
                    <h5 id="informacion_cheque"></h5>
                    <div class="row">
                        <div class="col-lg-12 form-group">
                            <label>OBSERVACIONES</label>
                            <textarea class="form-control" id="observacionCHEQUE" name="observacionCHEQUE" required></textarea>
                        </div>                       
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-lg-offset-3 form-group">
                            <button type="submit" class="btn btn-block btn-success btn-informacionCheque">GUARDAR</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade modal-alertas" id="mform_fechacobro" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"  style="background: #00A65A; color: #fff;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">FECHA DE COBRO</h4>
            </div>  
            <form method="post" id="form_fechacobro" autocomplete="off">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12 form-group">
                            <label>FECHA DE COBRO</label>
                            <input class="form-control fechas_filtro" id="fecha_movimiento" name="fecha_movimiento" required/>
                        </div>                       
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-lg-offset-3 form-group">
                            <button type="submit" class="btn btn-block btn-success">GUARDAR</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


 


<script>

    var tabla_historial_solicitudes;
    var tabla_historial_pagos;
    var tablaChequesCobrados;
    var tablaTodoCheques;
    var valor_input = Array( $('#historial_sol_activas th').length );
    
    $('.fechas_filtro').datepicker({
          format: 'dd/mm/yyyy',
            endDate: '-0d'
      });
    $('#fecInicial').on('keyup change', function(){
        var str = $(this).val();
        if(str.length==2 || str.length==5){
            $('#fecInicial').val(str+'/');
        }
    }); 
    $('#fecFinal').on('keyup change', function(){
        var str = $(this).val();
        if(str.length==2 || str.length==5){
            $('#fecFinal').val(str+'/');
        }
    }); //
    $('#fecInicialP').on('keyup change', function(){
        var str = $(this).val();
        if(str.length==2 || str.length==5){
            $('#fecInicialP').val(str+'/');
        }
    }); 
    $('#fecFinalP').on('keyup change', function(){
        var str = $(this).val();
        if(str.length==2 || str.length==5){
            $('#fecFinalP').val(str+'/');
        }
    }); //
    $('#fecInicialC').on('keyup change', function(){
        var str = $(this).val();
        if(str.length==2 || str.length==5){
            $('#fecInicialC').val(str+'/');
        }
    }); 
    $('#fecFinalC').on('keyup change', function(){
        var str = $(this).val();
        if(str.length==2 || str.length==5){
            $('#fecFinalC').val(str+'/');
        }
    }); //
    $('#fecInicialTC').on('keyup change', function(){
        var str = $(this).val();
        if(str.length==2 || str.length==5){
            $('#fecInicialTC').val(str+'/');
        }
    }); 
    $('#fecFinalTC').on('keyup change', function(){
        var str = $(this).val();
        if(str.length==2 || str.length==5){
            $('#fecFinalTC').val(str+'/');
        }
    }); 
    $('#tablahistorialsolicitudes').on('xhr.dt', function ( e, settings, json, xhr ) {
         var total = 0;
         $.each( json.data,  function(i, v){
           total += parseFloat(v.cantidad);
         });
         var to = formatMoney(total);
        document.getElementById("myText_1").value = to;
        
    });

    $('[data-toggle="tab"]').click( function(e) {
        $("#tipo_reporte1").html("");
        $("#tipo_reporte2").html("");
        $("#tipo_reporte3").html("");
        $("#tipo_reporte4").html("");

        $(".fechas_filtro ").val("");

        switch( $(this).attr('id') ){
            case 'listado_solicitudes-tab':
                tabla_historial_solicitudes.ajax.url( url +"Historial_cheques/tablaCheques" ).load();
                $("#tipo_reporte1").append(`<input type="hidden" name="tipo_reporte" value="${($(this).attr('id'))}">`);
                break;
            case 'listado_pagos-tab':
                tabla_historial_pagos.ajax.url( url +"Historial_cheques/tablaChequesCancelados" ).load();
                $("#tipo_reporte2").append(`<input type="hidden" name="tipo_reporte" value="${($(this).attr('id'))}">`);
                break;
            case 'listado_cheques-tab':
                tablaChequesCobrados.ajax.url( url +"Historial_cheques/tablaChequesCobrados" ).load();
                $("#tipo_reporte3").append(`<input type="hidden" name="tipo_reporte" value="${($(this).attr('id'))}">`);
                break;
            case 'listado_allcheques-tab':
                tablaTodoCheques.ajax.url( url +"Historial_cheques/tablaAllCheques" ).load();
                $("#tipo_reporte4").append(`<input type="hidden" name="tipo_reporte" value="${($(this).attr('id'))}">`);
                break;
        }
    });
    
    $("#tablahistorialsolicitudes").ready( function(){

        $('#tablahistorialsolicitudes thead tr:eq(0) th').each( function (i) {
            if( i != 8 ){
                var title = $(this).text();
                $(this).html( '<input type="text" style="font-size: .9em; width: 100%;" data-value="'+title+'" placeholder="'+title+'" />' );

                $( 'input', this ).on('keyup change', function () {
                    if (tabla_historial_solicitudes.column(i).search() !== this.value ) {
                        tabla_historial_solicitudes
                            .column(i)
                            .search( this.value)
                            .draw();

                            valor_input[i] = this.value;

                           var total = 0;
                           var index = tabla_historial_solicitudes.rows( { selected: true, search: 'applied' } ).indexes();
                           var data = tabla_historial_solicitudes.rows( index ).data();
                           $.each(data, function(i, v){
                               total += parseFloat(v.cantidad);
                           });
                           var to1 = formatMoney(total);
                           document.getElementById("myText_1").value = to1;
                    }
                });
            }
        });

        tabla_historial_solicitudes = $("#tablahistorialsolicitudes").DataTable({
            dom: 'Brtip',
            "buttons": [
                {
                    text: '<i class="fas fa-money-check"></i> REGISTRAR CHEQUE',
                    action: function(){

                        $('.lista_proveedores, .lista_proyecto').val(null).trigger('change');

                        $("#modal_agregar_ch .form-control").val("");
                        $("#modal_agregar_ch").modal();
                    },
                    attr: {
                        class: 'btn btn-default',
                    }
                },
                {
                    text: '<i class="fas fa-file-excel"></i> EXPORTAR',
                    action: function(){
                        $("#elementos_hidden1").html("");
                        $('#tablahistorialsolicitudes thead tr:eq(0) input').each( function (i) {
                            if( valor_input[i] )
                                $("#elementos_hidden1").append( '<input type="hidden" name="'+$(this).data('value')+'" value="'+valor_input[i]+'">' )
                        });

                        $("#elementos_hidden1").append( '<input type="hidden" name="tipo_reporte" value="'+$("ul.nav-tabs li.active a[data-toggle='tab']").attr('href')+'">' );


                        if( $("#formulario_cheques_activos").valid() ){
                            $("#formulario_cheques_activos").submit();
                        }
                        
                    },
                    attr: {
                        class: 'btn btn-success',
                    }
                }
                // {
                //     extend: 'excelHtml5',             
                //     text: '<i class="fas fa-file-excel"></i> EXPORTAR',
                //     messageTop: "Lista de pagos autorizados por Dirección General (CHEQUES)",
                //     attr: {
                //         class: 'btn btn-success'       
                //     },
                //     exportOptions: {
                //         columns: [0, 1, 2, 3, 4, 5],
                //         format: {
                //             header: function (data, columnIdx) { 

                //                 data = data.replace( '<input type="text" style="font-size: .9em; width: 100%;" placeholder="', '' );
                //                 data = data.replace( '">', '' )
                //                 return data;
                //             }
                //         }
                //     } 
                // }
            ],
            "language":lenguaje,
            "processing": false,
            "pageLength": 10,
            "bAutoWidth": false,
            "bLengthChange": false,
            "scrollX": true,
            "bInfo": false,
            "searching": true,
            "columns": [
                {
                    "width" : "7%",
                    "data" : function( d ){
                        return '<p style="font-size: .9em">'+( d.bd == 1 ? d.idsolicitud : 'NA' )+'</p>';
                    }
                },
                {
                    "data" : function( d ){
                        return '<p style="font-size: .9em">'+d.responsable+'</p>'+( d.programado >= 1 ? '<small class="label pull-center bg-blue">PROGRAMADO</small>' : '' );
                    }
                },
                {
                    "width" : "8%",
                    "data" : function( d ){
                        return '<p style="font-size: .9em">'+d.abrev+'</p>'
                    }
                },
                {
                    "width" : "10%",
                    "data" : function( d ){
                        return '<p style="font-size: .9em">'+formato_fechaymd(d.fecha_operacion)+'</p>'
                    }
                },
                {
                    "width" : "8%",
                     "data": function( data ){
                        if(data.intereses=='1'){
                            return '<p style="font-size: .8em">$ '+formatMoney(data.cantidad)+"</p>"+ "<br><small class='label pull-center bg-maroon'>CREDITO</small><small class='label pull-center bg-gray'>INTÉRES: "+formatMoney(data.interes)+"</small>";
                        }
                        else{
                            return '<p style="font-size: .8em">$ '+formatMoney(data.cantidad)+"</p>";
                        }
        
                     }
                },
                {
                    "width" : "8%",
                    "data" : function( d ){
                        return '<p style="font-size: .9em">'+ ( d.referencia ? d.referencia : 'SIN DEFINIR' ) +'</p>'
                    }
                },
                {
                    "width" : "8%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+(d.bd == 1 ? "PROVEEDOR" : "CAJA CHICA" )+'</p>'
                    }
                },
                {
                    "width" : "8%",
                    "data" : function( d ){
                        switch( d.estatus ){
                            case "14":
                                //return '<p style="font-size: .9em">ENTREGADO</p>';
                                return '<h5><span style="font-size: .8em" class="label label-success">ENTREGADO</span></h5>';
                                break;
                            case "13":
                            case "15":
                                //return '<p style="font-size: .9em">POR ENTREGAR</p>';
                                return '<h5><span style="font-size: .8em" class="label label-warning">POR ENTREGAR</span></h5>';
                                break;
                        }
                    }
                },
                {
                    "orderable": false,
                    "data": function( d ){
                        var opciones = '<div class="btn-group-vertical">';
                        
                        if(d.bd == 1){
                            opciones += '<button style="margin: 2px;" type="button" class="btn btn-warning editar_cheque btn-sm" value="'+d.idsolicitud+'" title="Editar"><i class="fas fa-pencil-alt"></i></button>';
                            opciones += '<button style="margin: 2px;" type="button" class="notification btn btn-primary btn-sm consultar_modal" style="" value="'+d.idsolicitud+'" data-value="SOL" title="Ver"><i class="far fa-eye"></i>'+(d.visto == 0 ? '</i><span class="badge">!</span>' : '')+'</button>';
                        }
                        if(d.bd == 2){
                            opciones += '<button style="margin: 2px;" type="button" class="btn btn-warning remplazar_cheque btn-sm" value="'+d.idsolicitud+'" title="Reemplazar"><i class="fas fa-pencil-alt"></i></button>';
                        }

                        opciones += '<button style="margin: 2px;" type="button" class="btn btn-success aprobar_cheque btn-sm" value="'+d.idpago+'" data-value="'+d.bd+'" title="Aprobar"><i class="fas fa-check-circle"></i></button>';
                        return opciones +"</div>";
                    }
                }
            ],
            "ajax": url + "Historial_cheques/tablaCheques",
            "columnDefs": [ {
                   "orderable": false
               }],
            bSort: false,

        });

        $(".registrar_cheque").click(function(){
            $("#observacion").html('');
            $("#modal_agregar_ch").modal();
        });

            var row;
           $('#tablahistorialsolicitudes tbody').on('click', '.editar_cheque', function () {
              $('#modal_confirmar_cheque').modal();
              tr = $(this).closest('td').closest('tr');
              row = tabla_historial_solicitudes.row( tr );
           });
           
           $('#tablahistorialsolicitudes tbody').on('click', '.remplazar_cheque', function () {
              
              tr = $(this).closest('td').closest('tr');
              row = tabla_historial_solicitudes.row( tr );
              
              $('#modal_editar_cheque').modal();
              
             
              $("#informacion_cheque").html('<div class="col-lg-12">&nbsp;</div>');
              $("#informacion_cheque").html( '<b>REFERENCIA ACTUAL:</b>'+row.data().referencia );
              var valor = row.data().idpago;
              
              $.getJSON( url + "Cuentasxp/lista_cta_ch/"+valor).done( function( data ){
                                    $(".lista_cta").html("");
                                    $(".lista_cta").append('<option value="">Seleccione una opción</option>');
                                    $.each( data, function( i, v){
                                        $(".lista_cta").append('<option value="'+v.idcuenta+'" data-value="'+v.idcuenta+'">'+v.nombre+" - "+v.nodecta+" - "+v.nombanco+'</option>');
                                    });

                                });
 
                            $('select#cuenta_valor').on('change',function(){
                            $(".cuenta_valor").html("");
                             var valor = $(this).val();
                             
                            $.getJSON( url + "Cuentasxp/getConsecutivo"+"/"+valor).done( function( data ){
                            $.each( data, function( i, v){
                            document.getElementById("serie_cheque").value = v.serie;
                            document.getElementById("numctaserie").value = valor;
                            });
                          });
                         });
                  $("#modal_editar_cheque .form-control").val('');
           }); 


 

             
           $(".confirmacion").click(function(){
              $('#modal_confirmar_cheque').modal("toggle");
              $('#modal_editar_cheque').modal();
              
             
              $("#informacion_cheque").html('<div class="col-lg-12">&nbsp;</div>');
              $("#informacion_cheque").html( '<b>REFERENCIA ACTUAL:</b>'+row.data().referencia );
              var valor = row.data().idpago;
              
              $.getJSON( url + "Cuentasxp/lista_cta/"+valor).done( function( data ){ 
                                    $(".lista_cta").html("");
                                    $(".lista_cta").append('<option value="">Seleccione una opción</option>');
                                    $.each( data, function( i, v){
                                        $(".lista_cta").append('<option value="'+v.idcuenta+'" data-value="'+v.idcuenta+'">'+v.nombre+" - "+v.nodecta+" - "+v.nombanco+'</option>');
                                    });

                                });
                                //document.getElementById("numpago").value = valor;
                            

                            $('select#cuenta_valor').on('change',function(){
                            $(".cuenta_valor").html("");
                             var valor = $(this).val();
                             
                            $.getJSON( url + "Cuentasxp/getConsecutivo"+"/"+valor).done( function( data ){
                            $.each( data, function( i, v){
                            document.getElementById("serie_cheque").value = v.serie;
                            document.getElementById("numctaserie").value = valor;
                            });
                          });
                         });
                  $("#modal_editar_cheque .form-control").val('');
                  
             });
             
            $(".regresar").click(function(){
                $.ajax({
                    url : url + "Historial_cheques/regresar_cheque",
                    type : "POST" ,
                    dataType : "json" ,
                    data :{ id_pago : row.data().idpago, id_Sol : row.data().idsolicitud, referencia : row.data().referencia },
                    error : function( data ){

                    },
                    complete: function( data ){
                        tabla_historial_solicitudes.row( tr ).remove().draw();
                    }
                });
            });

$(".regresar_definitiva").click(function(){

   
    $("#observacion").html('');
     $("#modal_cancelar_cheque").modal();
 
            });

        $('#fecInicial').change( function() { 
           tabla_historial_solicitudes.draw();
           var total = 0;
           var index = tabla_historial_solicitudes.rows( { selected: true, search: 'applied' } ).indexes();
           var data = tabla_historial_solicitudes.rows( index ).data();
           $.each(data, function(i, v){
               total += parseFloat(v.cantidad);
           });
           var to1 = formatMoney(total);
           document.getElementById("myText_1").value = to1;
        });
        
        $('#fecFinal').change( function() { 
            tabla_historial_solicitudes.draw(); 
            tabla_historial_solicitudes.draw();
           var total = 0;
           var index = tabla_historial_solicitudes.rows( { selected: true, search: 'applied' } ).indexes();
           var data = tabla_historial_solicitudes.rows( index ).data();
           $.each(data, function(i, v){
               total += parseFloat(v.cantidad);
           });
           var to1 = formatMoney(total);
           document.getElementById("myText_1").value = to1;
         });  
         
    
        $('.dt-buttons').attr('style','float: right !important; margin-right: 15px;');     
           
    });

    var tr;

    $("#estatus_ch").change( function(){
        switch( $( this ).val() ){
            case '30':
                $("#proyecto").prop( "required", false );
                $("#proveedor").prop( "required", false );
                break;
            default:
                $("#proyecto").prop( "required", true );
                $("#proveedor").prop( "required", true );
                break;
        }
        
        $('#form_cheques').validate();

    });

    $("#form_cheques").submit( function(e) {
        e.preventDefault();
    }).validate({
        submitHandler: function( form ) {
            
            var data = new FormData( $(form)[0] );
            var row = tabla_historial_solicitudes.row( tr );

            data.append("vieja_referencia", row.data().referencia );
            data.append("idpago", row.data().idpago);
            data.append("bd", row.data().bd );
   
            $.ajax({
                url: url + "Historial_cheques/editar_cheque",
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                method: 'POST',
                type: 'POST', // For jQuery < 1.9
                success: function(data){
                    if( data[0] ){
                        $("#modal_editar_cheque").modal('toggle' );
                        tabla_historial_solicitudes.row( tr ).remove().draw();
                    }else{
                        alert("NO SE HA PODIDO COMPLETAR LA SOLICITUD");
                    }
                },error: function( ){
                    
                }
            });
        }
    });


    $("#frm_addcheque").submit( function(e) {
        e.preventDefault();
    }).validate({
        submitHandler: function( form ) {
            var data = new FormData( $(form)[0] );
            data.append('estatus_ch', '30');
            $.ajax({
                url: url + "Historial_cheques/registrar_cheque",
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                method: 'POST',
                type: 'POST', // For jQuery < 1.9
                success: function(data){
                        tabla_historial_solicitudes.ajax.reload();
                        $("#modal_agregar_ch").modal('toggle' );
                },error: function( err ){
                    console.log(err);
                    
                }
            });
        }
    });

    $("#form_cheques_CANCELA").submit( function(e) {
        e.preventDefault();
    }).validate({
        submitHandler: function( form ) {
            
            var data = new FormData( $(form)[0] );
            var row = tabla_historial_solicitudes.row( tr );

            // data.append("vieja_referencia", row.data().referencia );
            data.append("idpago", row.data().idpago);
            data.append("bd", row.data().bd );
            data.append("ref", row.data().referencia);
   
            $.ajax({
                url: url + "Historial_cheques/editar_cheque_DEFIN",
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                method: 'POST',
                type: 'POST', // For jQuery < 1.9
                success: function(data){
                    if( data[0] ){
                        $("#modal_cancelar_cheque").modal('toggle' );
                        tabla_historial_solicitudes.row( tr ).remove().draw();
                    }else{
                        alert("NO SE HA PODIDO COMPLETAR LA SOLICITUD");
                    }
                },error: function( ){
                    
                }
            });
        }
    });
    
    /**CAMBIAR EL ESTATUS DE LOS CHEQUES - | GENERADO A ENTREGADO | ENTREGADO COBRADO**/
    var row_cheques_activos;

    $('#tablahistorialsolicitudes').on( "click", ".aprobar_cheque", function( e ){
        tr = $(this).closest('tr');
        row_cheques_activos = tabla_historial_solicitudes.row( tr ).data();

        if( row_cheques_activos.estatus == "15" )
            actualizar_estatus()
        else{
            $("#form_fechacobro #fecha_movimiento").val("");
            $("#mform_fechacobro").modal();
        }

    });
    
    $("#form_fechacobro").submit( function(e) {
        e.preventDefault();
    }).validate({
        submitHandler: function( form ) {
            actualizar_estatus();
        }
    });

    function actualizar_estatus(){
        $.ajax({
            url: url + "Historial_cheques/aprovar_cheque",
            dataType: 'json',
            type: 'POST',  
            data :{ idSol: row_cheques_activos.idsolicitud,
                idpago : row_cheques_activos.idpago , 
                        bd : row_cheques_activos.bd, 
                        estatus : row_cheques_activos.estatus, 
                        fecha_movimiento : $("#fecha_movimiento").val() },
            success: function(data){
                if(row_cheques_activos.estatus == "15"){
                    row_cheques_activos.estatus = "14";
                    tabla_historial_solicitudes.row( tr ).data( row_cheques_activos ).draw();
                }else{
                    $("#mform_fechacobro").modal("toggle");
                    tabla_historial_solicitudes.ajax.reload();
                }
                
                tr = null;
                row_cheques_activos = null;

            },error: function( ){
                
            }
        });
    }
    /******************************************************************************/
    
    $("#tablahistorialpagos").ready( function(){

        $('#tablahistorialpagos thead tr:eq(0) th').each( function (i) {
                var title = $(this).text();
                $(this).html( '<input type="text" style="font-size: .9em; width: 100%;" data-value="'+title+'" placeholder="'+title+'" />' );
            
                $( 'input', this ).on('keyup change', function () {
                    if (tabla_historial_pagos.column(i).search() !== this.value ) {
                        tabla_historial_pagos
                            .column(i)
                            .search( this.value)
                            .draw();

                            valor_input[i] = this.value;
                    }
                });
        });

        tabla_historial_pagos = $("#tablahistorialpagos").DataTable({
            dom: 'Brtip',
            width: 'auto',
            "buttons": [
                {
                    text: '<i class="fas fa-file-excel"></i> EXPORTAR',
                    action: function(){
                        $("#elementos_hidden2").html("");
                        $('#tablahistorialpagos thead tr:eq(0) input').each( function (i) {
                            if( valor_input[i] )
                                $("#elementos_hidden2").append( '<input type="hidden" name="'+$(this).data('value')+'" value="'+valor_input[i]+'">' )
                        });

                        $("#elementos_hidden2").append( '<input type="hidden" name="tipo_reporte" value="'+$("ul.nav-tabs li.active a[data-toggle='tab']").attr('href')+'">' );


                        if( $("#formulario_cheques_cancelados").valid() ){
                            $("#formulario_cheques_cancelados").submit();
                        }
                        
                    },
                    attr: {
                        class: 'btn btn-success',
                    }
                }
                // extend: 'excelHtml5',             
                // text: '<i class="fas fa-file-excel"></i> EXPORTAR EXCEL',
                // messageTop: "Lista de pagos autorizados por Dirección General (CHEQUES)",
                // attr: {
                //     class: 'btn bg-green',
                //     style: 'margin-right: 30px;'     
                // },
                // exportOptions: {
                //     format: {
                //         header: function (data, columnIdx) { 
                //             data = data.replace( '<input type="text" style="width:100%;" placeholder="', '' );
                //             data = data.replace( '">', '' )
                //             return data;
                //         }
                //     }
                // }
            ],
            "language":lenguaje,
            "processing": false,
            "pageLength": 10,
            "bAutoWidth": false,
            "bLengthChange": false,
            "scrollX": true,
            "bInfo": false,
            "searching": true,
            "columns": [
                {
                    "width" : "7%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+( d.tipo == 1 ? d.idsolicitud : 'NA' )+'</p>';
                    }
                },
                {
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.nombre+'</p>'+( d.nombre >= 1 ? '<small class="label pull-center bg-blue">PROGRAMADO</small>' : '' );
                    }
                },
                {
                    "width" : "7%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.abrev+'</p>'
                    }
                },
                {
                    "width" : "7%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+formato_fechaymd(d.fecha_cancelacion_f)+'</p>'
                    }
                },    
                {
                    "width" : "7%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.numCan+'</p>'
                    }
                },
                {
                    "width" : "7%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.numRem+'</p>'
                    }
                },
                {
                    "width" : "7%",
                     "data": function( data ){
                         if(data.intereses=='1'){
                            return "<p style='font-size: .8em'>$ "+formatMoney(data.cantidad)+ "<br><small class='label pull-center bg-maroon'>CREDITO</small><small class='label pull-center bg-gray'>INTÉRES: "+formatMoney(data.interes)+"</small></p>";
                        }
                        else{
                            return "<p style='font-size: .8em'>$ "+formatMoney(data.cantidad)+"</p>";
                        }
                     }
                },
                {
                    "width" : "7%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+( d.tipo == 1 ? 'PROVEEDOR': 'CAJA CHICA' )+'</p>';
                    }
                },
                {
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.observaciones+'</p>'
                    }
                }
            ],
            "columnDefs": [ {
                   "orderable": false
               }],
            //"ajax": url + "Historial_cheques/tablaChequesCancelados",
            bSort: false,
        });
        
        $('#fecInicialP').change( function() { 
            tabla_historial_pagos.draw();

            var total = 0;
            var index = tabla_historial_pagos.rows( { selected: true, search: 'applied' } ).indexes();
            var data = tabla_historial_pagos.rows( index ).data();

            $.each(data, function(i, v){
                total += parseFloat(v.cantidad);
            });
            var to1 = formatMoney(total);
            
            document.getElementById("myText_2").value = to1;

        });
        
        $('#fecFinalP').change( function() { 
            tabla_historial_pagos.draw();
            var total = 0;
            var index = tabla_historial_pagos.rows( { selected: true, search: 'applied' } ).indexes();
            var data = tabla_historial_pagos.rows( index ).data();
            
            $.each(data, function(i, v){
                total += parseFloat(v.cantidad);
            });
           
           var to1 = formatMoney(total);
           
           document.getElementById("myText_2").value = to1;
               
        });
        $('.dt-buttons').attr('style','float: right !important; margin-right: 15px;');
    });
    
    
    $("ul.nav-tabs > li > a").on("shown.bs.tab", function(e) {
        $(".fechas_filtro").val('');
        tabla_historial_pagos.draw();
        tabla_historial_solicitudes.draw();
        tablaTodoCheques.draw();
    });
       
       
    $('#tablachequesCobrados').on('xhr.dt', function ( e, settings, json, xhr ) {
        
        var total = 0;
        
        $.each( json.data,  function(i, v){
            total += parseFloat(v.cantidad);
        });
        
        var to = formatMoney(total);
        document.getElementById("myText_3").value = to;
        
    });
    var titulosTabla = [];
    var numColumEncabezados = [];
    $("#tablachequesCobrados").ready( function(){
              $('#tablachequesCobrados thead tr:eq(0) th').each( function (i) {
                var title = $(this).text();
                titulosTabla.push(title);
                numColumEncabezados.push(i);
                $(this).html(  `<input type="text" style="font-size: .9em; width: 100%;" data-value="${title}" data-toggle="tooltip" data-placement="top" title="${title}" placeholder="${title}" />` );
            
                $( 'input', this ).on('keyup change', function () {
                    if (tablaChequesCobrados.column(i).search() !== this.value ) {
                        tablaChequesCobrados
                            .column(i)
                            .search( this.value)
                            .draw();

                            valor_input[i] = this.value;

                           var total = 0;
                           var index = tablaChequesCobrados.rows( { selected: true, search: 'applied' } ).indexes();
                           var data = tablaChequesCobrados.rows( index ).data();
                           $.each(data, function(i, v){
                               total += parseFloat(v.cantidad);
                           });
                           var to1 = formatMoney(total);
                           document.getElementById("myText_3").value = to1;
                    }
                });
        });


        tablaChequesCobrados = $("#tablachequesCobrados").DataTable({
            dom: 'Brtip',
            buttons: [
                {
                    text: '<i class="fas fa-file-excel"></i> EXPORTAR',
                    action: function(){
                        $("#elementos_hidden3").html("");
                        $('#tablachequesCobrados thead tr:eq(0) input').each( function (i) {
                            if( valor_input[i] )
                                $("#elementos_hidden3").append( '<input type="hidden" name="'+$(this).data('value')+'" value="'+valor_input[i]+'">' )
                        });

                        $("#elementos_hidden3").append( '<input type="hidden" name="tipo_reporte" value="'+$("ul.nav-tabs li.active a[data-toggle='tab']").attr('href')+'">' );


                        if( $("#formulario_cheques_quebrados").valid() ){
                            $("#formulario_cheques_quebrados").submit();
                        }
                        
                    },
                    attr: {
                        class: 'btn btn-success',
                    }
                }
            ],
            width: '100%',
            language:lenguaje,
            processing: false,
            pageLength: 10,
            bAutoWidth: false,
            bLengthChange: false,
            scrollX: true,
            bInfo: false,
            searching: true,
            columns: [
                {
                    data : function( d ){
                        return '<p style="font-size: .9em">'+( d.bd == 1 ? d.idsolicitud : 'NA' )+'</p>';
                    }
                },
                {
                    data : function( d ){
                        return '<p style="font-size: .9em">'+d.responsable+'</p>'+( d.programado >= 1 ? '<small class="label pull-center bg-blue">PROGRAMADO</small>' : '' );
                    }
                },
				{
					data : function( d ){
						return `<p style="font-size: .9em">${d.responsableDA}</p>`;
					}
				},
				{
					data : function( d ){
						return '<p style="font-size: .9em">'+formato_fechaymd(d.fecha_cobro)+'</p>';
					}
				},
				{
					data : function( d ){
						return `<p style="font-size: .9em">${d.usuarioAlta}</p>`;
					}
				},
                {
                    data : function( d ){
                        return `<p style="font-size: .9em">${d.abrev}</p>`;
                    }
                },
                {
                     data: function( data ){
                          if(data.intereses=='1')
                            return "$"+formatMoney(data.cantidad)+ "<br><small class='label pull-center bg-maroon'>CREDITO</small><small class='label pull-center bg-gray'>INTÉRES: "+formatMoney(data.interes)+"</small>";
                        else
                            return "$"+formatMoney(data.cantidad);
                     }
                },
                {
                    data : function( d ){
                        return `<p style="font-size: .9em">${d.referencia}</p>`;
                    }
                },
                {
                    data : function( d ){
                        return '<p style="font-size: .9em">'+( d.bd == 1 ? 'PROVEEDOR': 'CAJA CHICA' )+'</p>';
                    }
                },
                {
                    data : function( d ){
                        return '<p style="font-size: .8em">' + d.folio + (d.uuid ? '/' + d.uuid : '') + '</p>';
                    }
                },
                {
                    data : function( d ){
                        return '<p style="font-size: .8em">' + ( d.fecha_dispersion ? formato_fechaymd(d.fecha_dispersion): "---" ) + '</p>';
                    }
                },
                {
                    data : function( d ){
                        return '<p style="font-size: .8em">' + formato_fechaymd(d.fechaaut) + '</p>';
                    }
                },
                {
                    data : function( d ){
                        return `<p style="font-size: .8em">${d.nomdepto}</p>`;
                    }
                },

                {
					data : function( d ){
						return '<p style="font-size: .9em">'+(d.proyecto)+'</p>';
					}
				},
                {
					data : function( d ){
						return '<p style="font-size: .9em">'+(d.etapa)+'</p>';
					}
				},
                {
					data : function( d ){
						return '<p style="font-size: .9em">'+(d.condominio ?? 'NA')+'</p>'; /** FECHA: 06-JUNIO-2025 | NUEVO CONCEPTO SOLO DEPTO CH | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
					}
				},
                {
					data : function( d ){
						return '<p class="justificacion-cell" style="font-size: .9em">'+(d.justificacion)+'</p>';
					}
				},
                {
					data : function( d ){
						return '<p style="font-size: .9em">'+(d.metoPago)+'</p>';
					}
				},
                {
					data : function( d ){
						return '<p style="font-size: .9em">'+(d.idfactura == null ? 'NA' : d.idfactura)+'</p>';
					}
				},
                {
					data : function( d ){
						return '<p style="font-size: .9em">'+(d.descFac == null ? 'NA' : d.descFac)+'</p>';
					}
				},
            ],
            columnDefs: [ {
                orderable: false
            }],
            bSort: false,
        });

        $('#fecInicialC').change( function() { 
            tablaChequesCobrados.draw();
            var total = 0;
            var index = tablaChequesCobrados.rows( { selected: true, search: 'applied' } ).indexes();
            var data = tablaChequesCobrados.rows( index ).data();
            $.each(data, function(i, v){
                total += parseFloat(v.cantidad);
            });
            var to1 = formatMoney(total);
            document.getElementById("myText_3").value = to1;
        });

        $('#fecFinalC').change( function() { tablaChequesCobrados.draw(); 
            var total = 0;
            var index = tablaChequesCobrados.rows( { selected: true, search: 'applied' } ).indexes();
            var data = tablaChequesCobrados.rows( index ).data();
            $.each(data, function(i, v){
                total += parseFloat(v.cantidad);
            });
            var to1 = formatMoney(total);
            document.getElementById("myText_3").value = to1;
        });
        $('.dt-buttons').attr('style','float: right !important; margin-right: 15px;');

    });

    $('#tablaTodoCheques').on('xhr.dt', function ( e, settings, json, xhr ) {
        var total = 0;
        $.each( json.data,  function(i, v){
            total += parseFloat(v.cantidad);
        });
        var to = formatMoney(total);
        document.getElementById("myText_4").value = to;
    });
     
 
   $("#tablaTodoCheques").ready( function(){
        $('#tablaTodoCheques thead tr:eq(0) th').each( function (i) {
            // if( i != 0 && i != 3 ){
                var title = $(this).text();
                $(this).html( '<input type="text" style="font-size: .9em; width: 100%;" placeholder="'+title+'">' );
            
                $( 'input', this ).on('keyup change', function () {
                    if (tablaTodoCheques.column(i).search() !== this.value ) {
                        tablaTodoCheques
                            .column(i)
                            .search( this.value)
                            .draw();

                            valor_input[i] = this.value;

                           var total = 0;
                           var index = tablaTodoCheques.rows( { selected: true, search: 'applied' } ).indexes();
                           var data = tablaTodoCheques.rows( index ).data();
                           $.each(data, function(i, v){
                               total += parseFloat(v.cantidad);
                           });
                           var to1 = formatMoney(total);
                           document.getElementById("myText_4").value = to1;
                    }
                });
            // }
        });
         tablaTodoCheques = $("#tablaTodoCheques").DataTable({
           dom: 'Brtip',
            "buttons": [
                /*
                {
                    text: '<i class="fas fa-file-excel"></i> EXPORTAR',
                    action: function(){
                        $("#elementos_hidden4").html("");
                        $('#tablaTodoCheques thead tr:eq(0) input').each( function (i) {
                            if( valor_input[i] )
                                $("#elementos_hidden4").append( '<input type="hidden" name="'+$(this).data('value')+'" value="'+valor_input[i]+'">' )
                        });

                        console.log(valor_input);

                        $("#elementos_hidden4").append( '<input type="hidden" name="tipo_reporte" value="'+$("ul.nav-tabs li.active a[data-toggle='tab']").attr('href')+'">' );


                        if( $("#formulario_cheques_todos").valid() ){
                            $("#formulario_cheques_todos").submit();
                        }
                        
                    },
                    attr: {
                        class: 'btn btn-success',
                    }
                },
                */
                {
                    extend: 'excelHtml5',             
                    text: '<i class="fas fa-file-excel"></i> EXPORTAR',
                    messageTop: "Lista de todos pagos (CHEQUES)",
                    attr: {
                        class: 'btn btn-success'       
                    },
                    exportOptions: {
                        format: {
                            header: function (data, columnIdx) { 
                                data = data.replace( '<input type="text" style="font-size: .9em; width: 100%;" placeholder="', '' );
                                data = data.replace( '">', '' )
                                return data;
                            }
                        }
                    } 
                }
                // {
                //     extend: 'excelHtml5',             
                //     text: '<i class="fas fa-file-excel"></i> EXPORTAR',
                //     messageTop: "Lista de todos pagos (CHEQUES)",
                //     attr: {
                //         class: 'btn btn-success'       
                //     },
                //     exportOptions: {
                //         format: {
                //             header: function (data, columnIdx) { 

                //                 data = data.replace( '<input type="text" style="font-size: .9em; width: 100%;" placeholder="', '' );
                //                 data = data.replace( '">', '' )
                //                 return data;
                //             }
                //         }
                //     } 
                // }
            ],
            "language":lenguaje,
            "processing": false,
            "pageLength": 10,
            "bAutoWidth": false,
            "bLengthChange": false,
            "scrollX": true,
            "bInfo": false,
            "searching": true,
            "columns": [
                {
                    "width" : "7%",
                    "data" : function( d ){
                        return '<p style="font-size: .9em">'+( d.bd == 1 ? d.idpago : 'NA' )+'</p>';
                    }
                },
                {
                    "data" : function( d ){
                        return '<p style="font-size: .9em">'+d.responsable+'</p>'+( d.programado >= 1 ? '<small class="label pull-center bg-blue">PROGRAMADO</small>' : '' );
                    }
                },
                {
                    "data" : function( d ){
                        return '<p style="font-size: .9em">'+d.abrev+'</p>'
                    }
                },
                {
                    "data" : function( d ){
                        return '<p style="font-size: .9em">'+formato_fechaymd(d.fecha_operacion)+'</p>'
                    }
                },
                {
                     "data": function( data ){
                          if(data.intereses=='1'){
                            return "$ "+formatMoney(data.cantidad)+ "<br><small class='label pull-center bg-maroon'>CREDITO</small><small class='label pull-center bg-gray'>INTÉRES: "+formatMoney(data.interes)+"</small>";
                        }
                        else{
                            return "$ "+formatMoney(data.cantidad);
                        }
                     }
                },
                {
                    "data" : function( d ){
                        return '<p style="font-size: .9em">'+d.referencia+'</p>'
                    }
                },
                {
                     "data": function( d ){
 
                        if(d.estatus=='COBRADO'){
                            return "<small class='label pull-center bg-green'>COBRADO</small>";
                        }else if(d.estatus=='CANCELADO'){
                            return "<small class='label pull-center bg-red'>CANCELADO</small>";
                        }else{
                            return "<small class='label pull-center bg-orange'>"+d.estatus+"</small>";
                        }

                     }
                },
                {
                    "data" : function( d ){
                        return '<p style="font-size: .9em">'+(d.bd == 1 ? "PROVEEDOR" : "CAJA CHICA" )+'</p>'
                    }
                }
            ],
            "columnDefs": [ {
                   "orderable": false
                }
            ],
            //"ajax": url + "Historial_cheques/tablaAllCheques",
            bSort: false,
        });
        
        $('#fecInicialTC').change( function() { 
            tablaTodoCheques.draw();
            
            var total = 0;
            var index = tablaTodoCheques.rows( { selected: true, search: 'applied' } ).indexes();
            var data = tablaTodoCheques.rows( index ).data();
            
            $.each(data, function(i, v){
                total += parseFloat(v.cantidad);
            });
            
            var to1 = formatMoney(total);
            document.getElementById("myText_4").value = to1;

        });
        $('#fecFinalTC').change( function() {
            
            tablaTodoCheques.draw(); 
            
            var total = 0;
            var index = tablaTodoCheques.rows( { selected: true, search: 'applied' } ).indexes();
            var data = tablaTodoCheques.rows( index ).data();

            $.each(data, function(i, v){
                total += parseFloat(v.cantidad);
            });
            var to1 = formatMoney(total);
            document.getElementById("myText_4").value = to1;
        });
        $('.dt-buttons').attr('style','float: right !important; margin-right: 15px;');
   
    });
  

    $.fn.dataTableExt.afnFiltering.push( function( oSettings, aData, iDataIndex ) {
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
                    
            var iStartDateCol = 3;
            var iEndDateCol = 3;

            var meses = ['','Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];
            
            var mes1 =  typeof aData[iStartDateCol] === 'undefined' ? '' : (meses.indexOf(aData[iStartDateCol].split('/')[1]).toString()).padStart(2, "0");
            var mes2 =  typeof aData[iEndDateCol] === 'undefined' ? '' : (meses.indexOf(aData[iEndDateCol].split('/')[1]).toString()).padStart(2, "0");

            iFini=iFini.substring(6,10) + iFini.substring(3,5) + iFini.substring(0,2);
            iFfin=iFfin.substring(6,10) + iFfin.substring(3,5) + iFfin.substring(0,2);
                //alert(iFini);
                // alert(iFfin);
            var datofini=aData[iStartDateCol].substring(7,11) + mes1 + aData[iStartDateCol].substring(0,2);
            var datoffin=aData[iEndDateCol].substring(7,11) + mes2 + aData[iEndDateCol].substring(0,2);
                    //alert(datofini);
                    //alert(datoffin);
            if ( iFini === "" && iFfin === "" )
            {
                return true;
            }
            else if ( iFini <= datofini && iFfin === "")
            {
                return true;
            }
            else if ( iFfin >= datoffin && iFini === "")
            {
                return true;
            }
            else if (iFini <= datofini && iFfin >= datoffin)
            {
                return true;
            }
            return false;
    });
    
    $(window).resize(function(){
        tabla_historial_solicitudes.columns.adjust();
        tabla_historial_pagos.columns.adjust();
        tablaChequesCobrados.columns.adjust();
        tablaTodoCheques.columns.adjust();
    });

    $(document).ready( function(){
        $(".lista_proveedores").append('<option value="">Seleccione opción</option>');
        $(".lista_empresa").append('<option value="">Seleccione opción</option>');
        $(".lista_proyecto").append('<option value="">Seleccione opción</option>');
        $.getJSON( url + "Listas_select/listado_pyempro").done( function( data ){
            $.each( data.proveedores, function( i, v){
                $(".lista_proveedores").append('<option value="'+v.idproveedor+'">'+v.nombre+'</option>');
            });

            $.each( data.empresas, function( i, v){
                $(".lista_empresa").append('<option value="'+v.idempresa+'">'+v.nombre+'</option>');
            });
            $.each( data.proyecto, function( i, v){
                $(".lista_proyecto").append('<option value="'+v.proyecto+'">'+v.proyecto+'</option>');
            });

            $('.lista_proyecto, .lista_proveedores').select2( { width: '100%' } );
        });
    });

</script>

<?php
    require("footer.php");
?>