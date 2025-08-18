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
                        <h3>SOLICITUD DE DEVOLUCIONES / TRASPASOS</h3>
                    </div>
                    <div class="box-body">
                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                                <li class="active"><a id="autorizaciones-tab" data-toggle="tab" href="#facturas_autorizar" role="tab" aria-controls="#home" aria-selected="true">CAPTURA DEVOLUCIONES</a></li>
                                <li><a id="autorizaciones-tab" data-toggle="tab" href="#facturas_activas" role="tab" aria-controls="#home" aria-selected="true">DEVOLUCIONES EN CURSO</a></li>
                            </ul>
                        </div>
                        <div class="tab-content">
                            <div class="active tab-pane" id="facturas_autorizar">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12">
                                        <table class="table table-striped" id="tabla_autorizaciones">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th></th>
                                                    <th style="font-size: .9em">#</th>
                                                    <th style="font-size: .9em">PROYECTO</th>
                                                    <th style="font-size: .9em">FECHA</th>
                                                    <th style="font-size: .9em">PROVEEDOR</th>
                                                    <th style="font-size: .9em">CAPTURISTA</th>                                                    
                                                    <th style="font-size: .9em">CANTIDAD</th>
                                                    <th style="font-size: .9em">ESTATUS</th>
                                                    <th style="font-size: .9em">TIPO</th>
                                                    <th style="font-size: .9em">MÉTODO DE PAGO</th>
                                                    <th nowrap style="font-size: .9em" ></th>
                                                </tr>
                                            </thead>
                                            <tbody><tr><td colspan="11">SIN REGISTROS</td></tr></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="facturas_activas">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12">
                                        <form id="formulario_facturas_activas" autocomplete="off" action="<?= site_url("Reportes/solicitante_solPago_solActivas") ?>" method="post"  onkeydown="return event.key != 'Enter';">
                                            <div class="col-lg-3">
                                                <div class="input-group">
                                                    <span class="input-group-addon"><i class="fas fa-calendar-alt"></i> <b>INICIO</b></span>
                                                        <input class="form-control fechas_filtro from" type="text" id="fecInicial" name="fechaInicial" maxlength="10"/>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="input-group">
                                                    <span class="input-group-addon"><i class="fas fa-calendar-alt"></i> <b>FIN</b></span>
                                                    <input class="form-control fechas_filtro to" type="text" id="fecFinal" name="fechaFinal" maxlength="10"/>
                                                </div>
                                            </div>
                                            <div id="elementos_hidden"></div>
                                            <table class="table table-striped" id="tblsolact">
                                                <thead class="thead-dark">
                                                    <tr>
                                                        <th style="font-size: .9em">#</th>
                                                        <th style="font-size: .9em">EMPRESA</th>
                                                        <th style="font-size: .9em">PROVEEDOR</th>
                                                        <th style="font-size: .9em">FEC FAC</th>
                                                        <th style="font-size: .9em">CAPTURA</th>
                                                        <th style="font-size: .9em">CANTIDAD</th>
                                                        <th style="font-size: .9em">PAGADO</th>
                                                        <th style="font-size: .9em">RESTANTE</th>
                                                        <th style="font-size: .9em">ESTATUS</th>
                                                        <th></th> 
                                                    </tr>
                                                </thead>
                                            </table>
                                        </form>
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
                <div class="row">
                    <div class="col-lg-12"> 
                        <form id="frmnewsol" method="post" action="#" onkeydown="return event.key != 'Enter';">  
                            <div class="row"> 
                                <div class="col-md-12">
                                    <b style="color: red;"><small>Campos requeridos</small> &#42;</b>
                                </div>
                                <div class="col-md-8">
                                    <label><b>TIPO DE PAGO:</b></label><br/>
                                    <label class="radio-inline"><input type="radio" value="0" name="tipo" id="devolucion" checked><b>DEVOLUCIÓN</b></label>
                                    <label class="radio-inline"><input type="radio" value="2" name="tipo" id="traspaso"><b>TRASPASO</b></label>
                                    <input type="hidden" name="tipoproveedor" class="form-control" id="tipoproveedor">  
                                    <input type="hidden" name="operacion" class="form-control" id="operacion">
                                </div>
                                <div class="col-md-4">
                                    <label><b>PRIORIDAD:</b></label><br/>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" id="cb_prioridad" name="prioridad">
                                        <label class="form-check-label" for="cb_prioridad">URGENTE</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 form-group">
                                    <label for="proyecto">PROYECTO<b style="color: red;">*</b></label>
                                    <select name="proyecto" id="proyecto" class="form-control" required></select>
                                </div>
                                <div class="col-lg-6 form-group">
                                    <label for="Etapa">ETAPA</label>
                                    <input type="text" name="etapa" class="form-control" id="Etapa" placeholder="Etapa">
                                </div>
                            </div>  
                            <div class="row">
                                <div class="col-lg-6 form-group">
                                    <label for="condominio">CONDOMINIO</label>
                                    <input type="text" name="condominio" class="form-control" id="condominio" placeholder="Condominio">
                                </div>
                                <div class="col-lg-6 form-group">
                                    <label for="empresa">REFERENCIA <i class="far fa-question-circle faq" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" title="Referencia" data-content="En caso de requerir una referencia favor de colocarla." data-placement="right"></i></label>
                                    <input type="text" placeholder="Numeros o letras: FACT445000" class="form-control solo_letras_numeros" maxlength="25" id="referencia_pagopr" name="referencia_pago">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 form-group">
                                    <label for="empresa">EMPRESA<b style="color: red;">*</b></label>
                                    <select name="empresa" id="empresa" class="form-control lista_empresa" required></select>
                                </div>
                            </div> 
                            <div class="row">
                                <div class="col-lg-3 form-group" id="divnoseencuentra">
                                    <label for="noseencuentra">¿No se encuentra el cliente? <input type="checkbox" id="noseencuentra" name="noseencuentra"></label>
                                </div>
                                <div class="col-lg-9 form-group">
                                    <label for="proveedor">PROVEEDOR<b style="color: red;">*</b></label>
                                    <select id="idproveedor" name="idproveedor" class="form-control show-tick" data-live-search="true" required>
                                        <option value="">--Selecciona una opción--</option>
                                    </select>
                                    <input type="text" id="proveedor" name="nombreproveedor" class="form-control"/>
                                    <input type="hidden" id="nombreproveedor_edit" value="">
                                    <input type="hidden" id="idproveedor_edit" value="0">
                                </div>
                            </div>                                  
                            <div class="row">
                                <div class="col-lg-12 form-group"> 
                                    <h5><b>DATOS DEL TICKET / FACTURA</b> <i class="far fa-question-circle faq" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" title="Datos de la factura" data-content="En caso de no tener la factura, deberás llenar los campos de descripción, subtotal, IVA, total y método de pago, así mismo, deberás de especificar en observaciones el por qué no tiene factura. Recuerda especificar si te darán factura después." data-placement="right"></i></h5>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 form-group">
                                    <label for="fecha">FECHA<b style="color: red;">*</b></label>
                                    <input type="date" class="form-control" min='2019-04-01' id="fecha" name="fecha" placeholder="Fecha" value="" required>
                                </div>
                                <div class="col-lg-4 form-group">
                                    <label for="total">TOTAL $<b style="color: red;">*</b></label>
                                    <input type="text" class="form-control dinero" id="total" name="total" placeholder="Total" value="" required>
                                </div>
                                <div class="col-lg-4 form-group">
                                    <label for="total">MONEDA<b style="color: red;">*</b></label>
                                    <select class="form-control" id="moneda" name="moneda" required>
                                        <option value="MXN" data-value="MXN">MXN</option>
                                        <option value="USA" data-value="USA">USA</option>
                                        <option value="CAD" data-value="CAD">CAD</option>
                                    </select>
                                </div>
                            </div>
                            <!--<div class="row" id="row_tipo">
                                <div class="col-lg-4" style="float: right; position: relative;">
                                <label for="total">TIPO DE CAMBIO</label>
                                    <input type="text" class="form-control" id="tipo" name="tipo" placeholder="Tipo de cambio" value="" required>
                                </div>
                            </div>-->
                            <div class="row">
                                <div class="col-lg-12 form-group">
                                    <label for="forma_pago">FORMA DE PAGO<b style="color: red;">*</b></label>
                                    <select class="form-control" id="forma_pago" name="forma_pago" required>
                                        <option value="">Seleccione una opción</option>
                                        <option value="ECHQ" data-value="ECHQ">Cheque</option>
                                        <option value="TEA" data-value="TEA">Transferencia electrónica</option>
                                        <option value="MAN" data-value="MAN">Manual</option>
                                        <!-- <option value="DOMIC" data-value="DOMIC">Domiciliario</option>
                                        <option value="EFEC" data-value="EFEC">Efectivo</option> -->
                                    </select>
                                </div>
                            </div>
                            <div class="row" id="row_cta">
                                <div class="col-lg-4 form-group">
                                    <label>Tipo de cuenta<b style="color: red;">*</b><i class="far fa-question-circle faq" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" title="Modificación de datos bancarios" data-content="Esta información debe ser requerida al personal correspondiente, ya que en esta sección no son editables, sólo son visibles para fines informativos" data-placement="right"></i></label>
                                    <select class="form-control tipoctaselect" id="tipocta" name="tipocta" >
                                        <option value="">Seleccione una opción</option>
                                        <option value="1">Cuenta en Banco del Bajio</option>
                                        <option value="3">Tarjeta de débito / crédito</option>
                                        <option value="40">CLABE</option>
                                    </select>
                                </div>
                                <div class="col-lg-4 form-group">
                                    <label>No. de cuenta<b style="color: red;">*</b></label>
                                    <input type="text" name="cuenta" id="cuenta" class="form-control cuenta" maxlength="0" readonly>
                                </div>
                                <div class="col-lg-4 form-group">
                                    <label>Banco<b style="color: red;">*</b></label> <select name="idbanco" class="form-control" id="idbanco" ><option value="">Seleccione una opción</option></select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 form-group">
                                    <label for="solobs">JUSTIFICACIÓN <i class="far fa-question-circle faq" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" title="Observaciones de la solicitud" data-content="En caso de ser necesario, se podrán especificar datos, comentarios u observaciones para cuentas por pagar, DG, DP o para la dirección del departamento solicitante." data-placement="right"></i></label>
                                    <textarea class="form-control" id="solobs" name="solobs" placeholder="Observaciones de la solicitud" ></textarea>
                                </div>
                            </div>
                            <!--PAGO PROGRAMADO div class="row">
                                <div class="col-lg-12">
                                    <p for="fecprog"><label class="form-check-label" for="fecrecu"><input type="checkbox" value="1" name="pago_programado"> PAGO PROGRAMADO</label></p>
                                    <div class="row">
                                        <div class="col-lg-6 form-group">
                                            <label class="form-check-label" for="fecprog">FECHA INICIO</label>
                                            <input type="date" class="form-control programar_fecha" placeholder="Programado" id="fecha_inicio" name="fecha_inicio" required>
                                        </div>
                                        <div class="col-lg-6 form-group">
                                            <label class="form-check-label" for="fecprog">FECHA FIN</label>
                                            <input type="date" class="form-control programar_fecha" placeholder="Programado" id="fecha_final" name="fecha_final" required>
                                        </div>
                                    </div>
                                </div>
                            </div-->
                            <!--<div class="row">
                                <div class="col-lg-12"> 
                                    <h5><b>ADICIONALES</b></h5>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-5"> 
                                    <div class="form-group">
                                        <label><b>TIPO DE PAGO</b><label><br/>
                                        <label class="radio-inline"><input type="radio" value="2" name="servicio" checked><b>DEVOLUCIÓN</b></label>
                                    </div>
                                </div>
                                <div class="col-lg-7"> 
                                    <div class="form-group">
                                        <label class="form-check-label" for="fecrecu"><input type="checkbox" value="1" name="prioridad"> URGENTE</label>
                                    </div>
                                </div>
                            </div>-->
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
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script type="text/javascript">

    $('[data-toggle="tab"]').click( function(e) {
        switch( $(this).attr('href') ){
            case '#facturas_autorizar':
                table_autorizar.ajax.reload();
                break;
            case '#facturas_activas':
                table_proceso.ajax.url( url +"Devoluciones_Traspasos/tabla_facturas_encurso" ).load();
                break;
        }
    });

    $(document).ready(function (){
        $("#proveedor").hide().prop("required",false);
    });

    $(document).ajaxStart( $.blockUI ).ajaxStop($.unblockUI);

    var link_post = "";
    var idsolicitud = 0;
    var fecha_minima = "1990-01-01";
    var table_autorizar;
    var uno = "";
    var dos = "";
    var tres = false;
    var valor_input = Array( $('#tblsolact th').length );
    var proveedores;
    var proveedor_empresa;

    $("#tipocta").change(function(){
        switch ($(this).val()){
            case '1':
                $("#cuenta").attr('minlength','7');
                $("#cuenta").attr('maxlength','12');
            break;
            case '3':
                $("#cuenta").attr('minlength','16');
                $("#cuenta").attr('maxlength','16');
            break;
            case '40':
                $("#cuenta").attr('minlength','18');
                $("#cuenta").attr('maxlength','18');
            break;
            default:
                $("#cuenta").attr('minlength','0');
                $("#cuenta").attr('maxlength','0');
            break;
        }
        if($(this).val()==1){
            $("#idbanco").val("6").prop("disabled",true);
        }else
            $("#idbanco").val("").prop("disabled",false);
    });

    $('input#cuenta').keypress(function (event) {
        if (event.which < 48 || event.which > 57) {
            return false;
        }
    });

    $('.fechas_filtro').datepicker({
        format: 'dd/mm/yyyy',  /** FECHA: 17-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
        zIndexOffset: 10000, /** FECHA: 17-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
        orientation: 'bottom auto' /** FECHA: 17-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
    });
    
    $.getJSON(url+'Listas_select/listado_devoluciones_traspasos?estatus=2', function( data ) {
        proveedores = data.lista_proveedores_devoluciones;
        proveedor_empresa = data.listado_proveedores_empresas;
        get_proveedores_est2();

        $("#proyecto").append('<option value="">Seleccione una opción</option>');
        $.each( data.lista_proyectos_depto, function( i, v){
            $("#proyecto").append('<option value="'+v.concepto+'">'+v.concepto+'</option>');
        });

        $("#proyecto").select2({width:"100%"});

        $.each(data.bancos, function (ind, val) {
            $("#idbanco").append("<option value='"+val.idbanco+"'>"+val.nombre+"</option>");
        });

        $(".lista_empresa").append('<option value="">Seleccione una opción</option>');
        $.each( data.empresas, function( i, v){
            $(".lista_empresa").append('<option value="'+v.idempresa+'" data-value="'+v.rfc+'">'+v.nombre+'</option>');
        });
        $("#empresa").select2({width:"100%"});
    });

    async function get_proveedores_est2(){
        $('#idproveedor').each(function (i, obj) {
            if ($(obj).data('select2')){
                $("#idproveedor").select2("destroy");
            }
        });
        
        $("#idproveedor").html('<option value="">Selecciona un proveedor</option>');
        $.each( proveedores, function(i, v){
            if(v.estatus==2){
                $("#idproveedor").append('<option value="'+v.idproveedor+'">'+v.nombre+' - '+v.alias+'</option>');
            }
        });
        $("#idproveedor").select2({width: 'element'});
    }
    

    $("#frmnewsol").submit( function(e) {
        e.preventDefault();
        jQuery(this).find(':disabled').removeAttr('disabled');
    }).validate({
        submitHandler: function( form ) {
            
            var data = new FormData( $(form)[0] );
            data.append("idsolicitud", idsolicitud);

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
                        alert(data.msj);
                        table_autorizar.ajax.reload();
                    }else{
                        alert("NO SE HA PODIDO COMPLETAR LA SOLICITUD");
                    }
                },error: function( data ){
                    alert("ERROR EN EL SISTEMA");
                }
            });
        }
    });
    
    
    function resear_formulario(){
        $("#row_tipo").hide();
        $("#modal_formulario_solicitud input.form-control").prop("readonly", false).val("");
        $("#modal_formulario_solicitud textarea").html('');

        $("#modal_formulario_solicitud #solobs").val('');
        $("#modal_formulario_solicitud textarea").prop("readonly", false);
        $("#empresa, #proveedor, #xmlfile").prop('disabled', false).val("").change();
        $("#empresa option, #proveedor option").prop("selected", false);
        $("#modal_formulario_solicitud .form-control, #modal_formulario_solicitud input[type='checkbox']").prop("checked", false).prop('disabled', false);

        $(".programar_fecha").prop('disabled', true);

        $("#responsable_cc").prop("disabled", true);

        $("#moneda").html( "" );
        $("#moneda").append( '<option value="MXN" data-value="MXN">MXN</option>' );
        //$("#moneda").append( '<option value="USD" data-value="USD">USD</option>' );

        var validator = $( "#frmnewsol" ).validate();
        validator.resetForm();
        $( "#frmnewsol div" ).removeClass("has-error");

        $("#fecha").attr( "min",  fecha_minima );

        if(!$("#devolucion").is(":checked")){
            $("#devolucion").prop("checked", true).change();
        }else
            $("#devolucion").change();
        $("#proveedor").val("").hide();
        $("#idproveedor").select2().next().show();
        $("#idproveedor").prop("required",true).val("").change();       
        $("#proyecto").val("").change();
        $("#forma_pago").val($("#forma_pago option:first").val());
        $("#tipocta").val("").change();
        $("#idbanco").val($("#idbanco option:first").val());
        $("#tipoproveedor").val(0);
        $("#operacion").val('DEVOLUCIONES');
        uno = dos = "";
        tres = false; 
        
        $("select#tipocta").prop("required", false);
        $("#cuenta").attr("required", false);
        $("select#idbanco").prop("required", false);
        $("#row_cta").hide();
    }

    $(document).on( "click", ".abrir_nueva_solicitud", function(){
        $("#nombreproveedor_edit").val("");
        $("#idproveedor_edit").val("");
        resear_formulario();
        $("#frmnewsol input").prop("readonly",false);
        $("#frmnewsol select").prop("disabled",false);
        $('input[type=radio][name=tipo]').prop("disabled",false);
        link_post = "Devoluciones_Traspasos/guardar_solicitud_devolucion";
        $("#modal_formulario_solicitud").modal( {backdrop: 'static', keyboard: false} );
    });

    $("#tabla_autorizaciones").ready( function () {

        $('#tabla_autorizaciones').on('xhr.dt', function ( e, settings, json, xhr ) {
            table_autorizar.button( 1 ).enable( parseInt(json.por_autorizar) > 0 );
        });

        $('#tabla_autorizaciones thead tr:eq(0) th').each( function (i) {
            if( i != 0 && i < $('#tabla_autorizaciones thead tr:eq(0) th').length - 1 ){
                var title = $(this).text();
                $(this).html( '<input type="text" style="font-size: .9em; width: 100%;" placeholder="'+title+'" title="'+title+'" class="form-control"/>' );
        
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
                           $("#myText_1").text( to1);
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
                            window.open( url + "Consultar/documentos_autorizacion_devtras", "_blank")
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
                    "width": "9%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.idsolicitud+'</p>'
                    }
                },
                {
                    "width": "9%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.proyecto+'</p>'
                    }
                },
                {
                    "width": "9%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.fecelab+'</p>'
                    }
                },
                {
                    "width": "15%",
                    "data" : function( d ){
                        return '<p style="font-size: .7em">'+d.nombre+'</p>'
                    }
                },
                {
                    "width": "15%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.nombre_completo+'</p>'
                    }
                },
                {
                    "width": "12%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">$ '+formatMoney( d.cantidad )+" "+d.moneda+'</p>'
                    }
                },
                {
                    "width": "12%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.etapa+'</p>'
                    }
                },
                {
                    "width": "12%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.nomdepto+'</p>'
                    }
                },
                {
                    "width": "12%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+(d.metoPago)+'</p>'
                    }
                },
                { 
                    "data" : "opciones",
                    "orderable": false,
                    "className":"td-nowrap"
                }
            ],
            "columnDefs": [ {
                "orderable": false,
            }],
            "ajax":  url + "Devoluciones_Traspasos/tabla_autorizaciones"
        });
        
        $('#fecInicial').change( function() { 
            table_proceso.draw();
        });

        $('#fecFinal').change( function() {
            table_proceso.draw(); 
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

            link_post = "Devoluciones_Traspasos/editar_solicitud_contabilidad";

            var limite_edicion = 1;
            var ideditar =  $(this).val();
            $('input[type=radio][name=tipo]').prop("disabled",true);
            
            resear_formulario();
            
            $.post( url + "Devoluciones_Traspasos/informacion_solicitud", { idsolicitud : ideditar } ).done( function( data ){

                data = JSON.parse( data );
                if( data.resultado ){

                    idsolicitud = ideditar;

                    $("#descr").append( data.xml ? data.xml[0].conceptos : "" );

                    $("#fecha").val( data.info_solicitud[0].fecelab );
                    $("#folio").val( data.xml ? data.xml[0].folio : "" );
                    $("#subtotal").val( data.xml ? data.xml[0].SubTotal : "" );
                    $("#iva").val( data.xml ? data.xml[0].iva : "" );
                    $("#total").val( data.info_solicitud[0].cantidad );

                    $("#empresa option[data-value='"+data.info_solicitud[0].rfc_empresas+"']").prop("selected", true).change();

                    $("#proveedor").val( data.info_solicitud[0].nombre_proveedor );
                    $("#idproveedor").val( data.info_solicitud[0].idProveedor).change();
                        
                    $("input[name='tentra_factura']").prop("checked", true);
                    $("input[name='caja_chica']").prop('checked', ( data.info_solicitud[0].caja_chica ? true : false ) );

                    $("input[name='caja_chica']").prop('disabled', ( data.info_solicitud[0].cantidad > 2000 ? true : false ) );
                    $("input[name='prioridad']").prop( "checked", ( data.info_solicitud[0].prioridad ? true : false ) );

                    $("input[name='servicio']").prop( "checked", false );
                    $("input[name='servicio'][value='"+data.info_solicitud[0].servicio+"']").prop( "checked", true );
                    
                    $("#Etapa").val(data.info_solicitud[0].etapa);
                    $("#condominio").val(data.info_solicitud[0].condominio);
                    $("#referencia_pagopr").val(data.info_solicitud[0].ref_bancaria);
                    $("#solobs").val( data.info_solicitud[0].justificacion );
                    $("#proyecto").val(data.info_solicitud[0].proyecto).change();
                    //$("#proyecto").val(  ).change();
                    $("#modal_formulario_solicitud").modal( {backdrop: 'static', keyboard: false} );
                    $("#idproveedor_edit").val(data.info_solicitud[0].idproveedor);
                    $("#nombreproveedor_edit").val(data.info_solicitud[0].nombre_proveedor);
                    
                    if( data.info_solicitud[0].nomdepto != "DEVOLUCIONES" ){

                        proveedor_empresa = data.listado_proveedores_empresas;

                        //$("#idproveedor").select2("destroy");
                        $("#idproveedor").html('<option value="" disabled>Seleccione un proveedor</option>');
                        $("#idproveedor").prop("required",true).show();
                        $("#proveedor").hide().prop("required",false);
                        $("#proveedor").val("");
                        $("#tipoproveedor").val(2);
                        $("#operacion").val('TRASPASO');
                        $("#traspaso").prop("checked", true).change();
                    
                    }else{
                        proveedores = data.lista_proveedores_devoluciones;
                        get_proveedores_est2();
                        $("#noseencuentra").prop("checked",false).click();
                        $("#proveedor").show().prop("required",true);
                        $("#idproveedor").prop("required",false).val(data.info_solicitud[0].idproveedor).change();
                        $("#idproveedor").select2().next().hide();
                        $("#divnoseencuentra").hide();
                        
                        $("#proveedor").val(data.info_solicitud[0].nombre_proveedor);
                        $("#tipoproveedor").val(0);
                        $("#operacion").val('DEVOLUCIONES');
                        $("#devolucion").prop("checked", true).change();
                    }
                    
                    $("#forma_pago option[data-value='"+data.info_solicitud[0].metoPago+"']").prop("selected", true).change();
                    
                    
                    uno = $("#nombreproveedor_edit").val();
                    dos = data.info_solicitud[0].idproveedor;
                    tres = true;

                    /*
                    if(data.info_solicitud[0].idetapa==1 || data.info_solicitud[0].idetapa==54){
                        $("#frmnewsol input").prop("readonly",false);
                        $("#frmnewsol select").prop("disabled",false);
                    }else{
                        $("#frmnewsol input").prop("readonly",true);
                        $("#frmnewsol select").prop("disabled",true);
                    }
                    */

                    $("#idproveedor").prop("disabled",false);
                    $("#proveedor").prop("readonly",false);
                }else{
                    alert("HA OCURRIDO UN ERROR")
                }
            });
        });

        $('#tabla_autorizaciones').on( "click", ".borrar_solicitud", function(){
            if(confirm("¿Estás seguro de eliminar la solicitud "+$(this).val()+"?")){
                $.post( url + "Devoluciones_Traspasos/borrar_solicitud", { idsolicitud : $(this).val() } ).done( function( data ){
                    data = JSON.parse( data );
                    if( data.resultado ){
                        table_autorizar.ajax.reload(null, false);
                    }else{
                        alert("HA OCURRIDO UN ERROR")
                    }
                });
            }
        });

        $('#tabla_autorizaciones').on( "click", ".enviar_a_dg", function(){
            $.post( url + "Devoluciones_Traspasos/devolucion_sigetapa", { idsolicitud : $(this).val(), etapaold : $("#etapa_"+$(this).val()).val(), avanza:1 } ).done( function( data ){
                data = JSON.parse( data );
                if( data.resultado ){
                    table_autorizar.ajax.reload(null, false);
                    //table_proceso.ajax.url( url +"Devoluciones_Traspasos/tabla_facturas_encurso" ).load();
                }else{
                    alert("HA OCURRIDO UN ERROR")
                }
            });
        });

        $('#tabla_autorizaciones').on( "click", ".reenviar_factura", function(){
            $.post( url + "Devoluciones_Traspasos/reenviar_factura", { idsolicitud : $(this).val() } ).done( function( data ){
                data = JSON.parse( data );
                if( data.resultado ){
                    table_autorizar.ajax.reload(null,false);
                    //table_proceso.ajax.reload(null,false);
                }else{
                    alert("HA OCURRIDO UN ERROR")
                }
            });
        });

        $('#tabla_autorizaciones').on( "click", ".aprobada_da", function(){
            $.post( url + "Devoluciones_Traspasos/aprobada_da", { idsolicitud : $(this).val() } ).done( function( data ){
                data = JSON.parse( data );
                if( data.resultado ){
                    table_autorizar.ajax.reload(null,false);
                    table_proceso.ajax.reload(null,false);
                }else{
                    alert("HA OCURRIDO UN ERROR")
                }
            });
        });

        $('#tabla_autorizaciones').on( "click", ".rechazada_da", function(){
            $.post( url + "Devoluciones_Traspasos/rechazada_da", { idsolicitud : $(this).val() } ).done( function( data ){
                data = JSON.parse( data );
                if( data.resultado ){
                    table_autorizar.ajax.reload(null,false);
                }else{
                    alert("HA OCURRIDO UN ERROR")
                }
            });
        });

        $('#tabla_autorizaciones').on( "click", ".congelar_solicitud", function(){
            $.post( url + "Devoluciones_Traspasos/congelar_solicitud", { idsolicitud : $(this).val() } ).done( function( data ){
                data = JSON.parse( data );
                if( data.resultado ){
                    table_autorizar.ajax.reload(null,false);
                }else{
                    alert("HA OCURRIDO UN ERROR")
                }
            });
        });

        $('#tabla_autorizaciones').on( "click", ".liberar_solicitud", function(){
            $.post( url + "Devoluciones_Traspasos/liberar_solicitud", { idsolicitud : $(this).val() } ).done( function( data ){
                data = JSON.parse( data );
                if( data.resultado ){
                    table_autorizar.ajax.reload(null,false);
                }else{
                    alert("HA OCURRIDO UN ERROR")
                }
            });
        });
        
        $('#tabla_autorizaciones').on( "click", ".cancelar_solicitud", function(){
            td = null;
            $("#modal_acciones_sol").modal("show");
            $("#idsolicitud_accion").val($(this).val());
            $("#form_acciones_sol").trigger("reset");
            $("#form_acciones_sol .inputs_hidden").html('<input type="hidden" name="avanza" value=0> <input type="hidden" name="etapaold" value="'+$("#etapa_"+$(this).val()).val()+'">');
            $("#modal_acciones_sol .modal-header").css({backgroundColor: 'red'});
            $("#modal_acciones_sol .modal-title").text("CANCELAR SOL. #"+$(this).val());
            link_post= "../Devoluciones_Traspasos/devolucion_sigetapa/";
        });
        
        $("#form_acciones_sol").submit(function (e){
            e.preventDefault();
            var fd = new FormData($(this)[0]);
            var data = enviar_post(fd,link_post);
            if(data.resultado){
                table_autorizar.ajax.reload();
                $("#modal_acciones_sol").modal("hide");
            }
        });

    });

    var table_proceso;

    $('#tblsolact').on('xhr.dt', function ( e, settings, json, xhr ) {

    });

    $("#tblsolact").ready( function () {
        
        $('#tblsolact thead tr:eq(0) th').each( function (i) {
            if( i < $('#tblsolact thead tr:eq(0) th').length - 1 ){
                var title = $(this).text();
                $(this).html( '<input type="text" style="font-size: .9em; width: 100%;" data-value="'+title+'" placeholder="'+title+'" title="'+title+'" />' );
        
                $( 'input', this ).on( 'keyup change', function () {
                    if ( table_proceso.column(i).search() !== this.value ) {
                        table_proceso
                            .column(i)
                            .search( this.value )
                            .draw();

                            valor_input[i] = this.value;
                    }
                } );
            }
        });
        

        table_proceso = $('#tblsolact').DataTable({
            dom: 'Brtip',
            "buttons": [
                {
                    text: '<i class="fas fa-file-excel"></i> EXPORTAR EXCEL',
                    action: function(){

                        $("#elementos_hidden").html("");
                        $('#tblsolact thead tr:eq(0) input').each( function (i) {
                            if( valor_input[i] )
                                $("#elementos_hidden").append( '<input type="hidden" name="'+$(this).data('value')+'" value="'+valor_input[i]+'">' )
                        });

                        $("#elementos_hidden").append( '<input type="hidden" name="tipo_reporte" value="'+$("ul.nav-tabs li.active a[data-toggle='tab']").attr('href')+'">' );


                        if( $("#formulario_facturas_activas").valid() ){
                            $("#formulario_facturas_activas").submit();
                        }
                        
                    },
                    attr: {
                        class: 'btn btn-success',
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
                    "width": "8%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.idsolicitud+'</p>'
                    }
                },
                {
                    "width": "10%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.abrev+'</p>'
                    }
                },
                /*
                {
                    "width": "16%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.proyecto+'</p>'
                    }
                },*/
                {
                    "width": "15%",
                    "data" : function( d ){
                        return '<p style="font-size: .7em">'+d.nombre+'</p>'
                    }
                },
                {
                    "width": "10%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+formato_fechaymd(d.fecelab)+'</p>'
                    }
                },
                {
                    "width": "15%",
                    "data" : function( d ){
                        return '<p style="font-size: .7em">'+d.nombre_completo+'</p>'
                    }
                },
                {
                    "width": "10%",
                    "data" : function( d ){
                        return '<p style="font-size: .7em">$ '+formatMoney(d.cantidad)+" "+d.moneda+'</p>'
                    }
                },
                {
                    "width": "8%",
                    "data" : function( d ){
                        return '<p style="font-size: .7em">$ '+formatMoney(d.pagado)+'</p>'
                    }
                },
                {
                    "width": "8%",
                    "data" : function( d ){
                        return '<p style="font-size: .7em">$ '+formatMoney( d.cantidad - d.pagado )+'</p>'
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
                        return '<div class="btn-group-vertical"><button type="button" class="btn btn-sm btn-primary consultar_modal notification" value="'+d.idsolicitud+'" data-value="BAS"><i class="fas fa-eye"></i>'+(d.visto == 0 ? '<span class="badge">!</span>' : '')+'</button></div>';
                    }
                }
            ],
            "columnDefs": [ {
                   "orderable": false
                }
            ]/*,
            "ajax":  url + "Devoluciones_Traspasos/tabla_facturas_encurso"*/
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
                    
            var iStartDateCol = 3;
            var iEndDateCol = 3;

            var meses = ['','Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];
            
            var mes1 =  typeof aData[iStartDateCol] === 'undefined' ? '' : (meses.indexOf(aData[iStartDateCol].split('/')[1]).toString()).padStart(2, "0");
            var mes2 =  typeof aData[iEndDateCol] === 'undefined' ? '' : (meses.indexOf(aData[iEndDateCol].split('/')[1]).toString()).padStart(2, "0");

            iFini=iFini.substring(6,10) + iFini.substring(3,5) + iFini.substring(0,2);
            iFfin=iFfin.substring(6,10) + iFfin.substring(3,5) + iFfin.substring(0,2);
 
            var datofini=aData[iStartDateCol].substring(7,11) + mes1 + aData[iStartDateCol].substring(0,2);
            var datoffin=aData[iEndDateCol].substring(7,11) + mes2 + aData[iEndDateCol].substring(0,2);

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

    function AutocompleteProveedor1(){
        $.each(proveedor_empresa, function (i, v) {
            $('#idproveedor').append('<option value="'+v.idproveedor+'">'+v.nombre+'</option>');        
        });
        
        $("#idproveedor").select2({width:"100%"});
        $("#idproveedor").val($("#idproveedor_edit").val()).change();
    }
    
    $(window).resize(function(){
        table_autorizar.columns.adjust();
        table_proceso.columns.adjust();
    });

    $('input[type=radio][name=tipo]').change(function() {
        $("#tipoproveedor").val(this.value);
        if(this.value == 0){
            $("#proveedor").hide().prop("required",false);
            get_proveedores_est2();
            $("#operacion").val('DEVOLUCIONES');
            $("#noseencuentra").show().prop("checked",false);
            $("#divnoseencuentra").show();
            if($("#nombreproveedor_edit").val()!=''){
                $("#divnoseencuentra").hide();
                $("#idproveedor").select2().next().hide();
                $("#idproveedor").prop("required",false).val($("#idproveedor_edit").val());
                $("#proveedor").show().prop("required",true).val($("#nombreproveedor_edit").val());
            }
        }else  if(this.value == 2){
            $("#idproveedor").select2("destroy");
            $("#idproveedor").html('<option value="" disabled>Seleccione un proveedor</option>');
            $("#idproveedor").show().prop("required",true);
            AutocompleteProveedor1();
            $("#operacion").val('TRASPASO');
            $("#divnoseencuentra").hide();
            $("#noseencuentra").hide().prop("checked",false);
            if($("#nombreproveedor_edit").val()!=''){
                $("#proveedor").hide().prop("required",false).hide().val("");
                $("#idproveedor").select2().next().show();
                $("#idproveedor").prop("required",true).val($("#idproveedor_edit").val());
            }
        }
        
        if( $("#noseencuentra").prop("checked") && $("#nombreproveedor_edit").val()=='' ){
            $("#idproveedor").select2().next().hide();
            $("#idproveedor").prop("required",false);
            $("#proveedor").show().prop("required",true);
        }else if( $("#nombreproveedor_edit").val()=='' ){
            $("#idproveedor").select2().next().show();
            $("#idproveedor").prop("required",false);
            $("#proveedor").hide().prop("required",false);
        }
        evaluar_datos();
    });

    $("#moneda").change(function(){
        if($(this).val() == "MXN"){
            $("#row_tipo").hide();
        }else{
            $("#row_tipo").show();
        }
    });

    $("#forma_pago").change(function(){
        evaluar_datos();
    });
    
    function evaluar_datos(){
        if( $('input[type=radio][name=tipo]:checked').val() == 0 && $("#forma_pago").val() == "TEA" ){
            $("select#tipocta").prop("required", true);
            $("#cuenta").attr("required", true);
            $("select#idbanco").prop("required", true);
            $("#row_cta").show();
        }else{
            $("select#tipocta").prop("required", false);
            $("#cuenta").attr("required", false);
            $("select#idbanco").prop("required", false);
            $("#row_cta").hide();
        }
    }

    function dateToDMY(date) {
        var d = date.getDate();
        var m = date.getMonth() + 1; //Month from 0 to 11
        var y = date.getFullYear();
        return ''  + (d <= 9 ? '0' + d : d) + '/' + (m<=9 ? '0' + m : m) + '/' + y ;
    }

    //EN CASO DE ENCONTRAR AL COLABORAR SE CAMBI A UN INPUT PARA ESCRIBIR SU NOMBRE
    $(document).on( "click", "#noseencuentra", function(){
        if( $( this ).prop("checked") ){
            $("#idproveedor").select2().next().hide();
            $("#idproveedor").prop("required",false);
            $("#proveedor").show().prop("required",true).val($("#nombreproveedor_edit").val());
            $("#row_cta .form-control").val("").prop( "disabled", false );
        }else{
            $("#idproveedor").select2().next().show();
            $("#idproveedor").prop("required",true).val("");
            $("#proveedor").hide().prop("required",false);
        }

        $("#idproveedor").val('').trigger('change');
    });

    //SI LA FORMA DE PAGO ES DIFERENTE A TEA OCULTA LOS DATOS BANCARIOS PARA CAMBIARLO
    $(document).on( "change", "#forma_pago, #idproveedor", function(){
        $("#row_cta").addClass( "hidden" );
        if(  $("#idproveedor").prop('selectedIndex') || $("#noseencuentra").prop("checked") ){
            if( ( $( "#forma_pago" ).val() ).indexOf('TEA') > -1 ){
                $("#row_cta").removeClass( "hidden" );

                //REVISAMOS SI ESTA CHEQUEADO LA OPCION QUE EL COLABORADOR NO EXISTE
                if( !$("#noseencuentra").prop("checked") ){
                    
                    //DEL ARREGLO DE PROVEEDORES OBTENEMOS SU INFORMACION
                    //VERIFICAMOS SI YA CUENTA CON DATOS BANCARIOS PARA RELLENAR LOS DATOS EN LOS CAMPOS CORRESPONDIENTES.
                    if( JSON.stringify(proveedores.find(element => element.ididproveedor == $("#idproveedor").val())) != "undefined" 
                            //&& proveedores[ $("#idproveedor").prop('selectedIndex')-1 ].tipocta && proveedores[ $("#idproveedor").prop('selectedIndex')-1 ].idbanco && proveedores[ $("#idproveedor").prop('selectedIndex')-1 ].cuenta 
                            ){
                        $("#tipocta option[value='"+proveedores[ $("#idproveedor").prop('selectedIndex')-1 ].tipocta+"']").prop("selected", true).change();
                        $("#idbanco option[value='"+proveedores[ $("#idproveedor").prop('selectedIndex')-1 ].idbanco+"']").prop("selected", true).change();
                        $("#cuenta").val( proveedores[ $("#idproveedor").prop('selectedIndex')-1 ].cuenta ).prop( "readonly", true ).change();

                        $("#row_cta select").prop( "disabled", true );

                        /**EN CASO QUE EL PROVEEDOR YA CONTABA CON UNA CUENTA BANCARIA SE REALIZA LA NOTIFICACION DE VERIFICAR LOS DATOS.**/
                        if( $("#devolucion").prop("checked") && $("#nombreproveedor_edit").val() =='' ){
                            $("#modalalerta .modal-body").text("Este cliente ya tiene una cuenta y un banco asignado, verifique que sean correctos.");
                            $("#modalalerta").modal("toggle");
                        }
                    }else{
                        console.log("No entra a pintar los datos");
                    }

                }else{
                    $("#row_cta .form-control").val("").prop( "disabled", false );
                    $("#cuenta").prop( "readonly", false );
                }

            }
        }
    });

</script>
<?php
    require("footer.php");
?>