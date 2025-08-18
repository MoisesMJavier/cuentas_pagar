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
                        <h3>SOLICITUDES DE DEVOLUCIONES / TRASPASO</h3>
                    </div>
                    <div class="box-body">
                        <div class="active tab-pane" id="facturas_aturizar">
                            <div class="row">
                                <div class="col-lg-12">
                                    <table class="table table-striped" id="tabla_autorizaciones">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th></th>
                                                <th style="font-size: .9em">#</th>
                                                <th style="font-size: .9em">PROYECTO</th>
                                                <th style="font-size: .9em">FECHA</th>
                                                <th style="font-size: .9em">TRANSACCION</th>
                                                <th style="font-size: .9em">PROVEEDOR</th>
                                                <th style="font-size: .9em">FORMA PAGO</th>
                                                <th style="font-size: .9em">EMPRESA</th>
                                                <th style="font-size: .9em">JUSTIFICACION</th>
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
                                <form id="formulario_facturas_activas" target="_blank" autocomplete="off" action="<?= site_url("Reportes/solicitante_solPago_solActivas") ?>" method="post">
                                    <div class="col-lg-3">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fas fa-calendar-alt"></i> <b>INICIO</b></span>
                                            <input class="form-control fechas_filtro from" type="text" id="fecInicial" name="fechaInicial" maxlength="10" required/>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fas fa-calendar-alt"></i> <b>FIN</b></span>
                                            <input class="form-control fechas_filtro to" type="text" id="fecFinal" name="fechaFinal" maxlength="10" required/>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                    <div class="input-group-addon" style="padding: 4px;">
                                        <h4 style="padding: 0; margin:0;"><label>Total $<input style="border-bottom: none; border-top: none; border-right: none;  border-left: none; background: white;" disabled="disabled" readonly="readonly" type="text"  name="myText_2" id="myText_2"></label></h4>
                                    </div>
                                    </div>
                                </form>
                                <div class="col-lg-12">
                                    <h4>FACTURAS ACTIVAS <i class="far fa-question-circle faq" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" data-placement="right" title="Solicitudes Activas" data-content="Estas son todas las solicitudes de pago a proveedores que se encuentran el proceso de pago, verifica el status de la solicitud para saber en que parte del proceso se encuentra."></i></h4>
                                    <hr>
                                    <table class="table table-striped" id="tblsolact">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th style="font-size: .9em">ID SOLICITUD</th>
                                                <th style="font-size: .9em">EMPRESA</th>
                                                <th style="font-size: .9em">PROYECTO</th>
                                                <th style="font-size: .9em">PROVEEDOR</th>
                                                <th style="font-size: .8em">FEC FAC</th>
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
                    </ul>
                </div>
                <div class="tab-content">
                    <div class="active tab-pane" id="generar_solicitud">
                        <div class="row">
                            <div class="col-lg-12"> 
                                <form id="frmnewsol" method="post" action="#">  
                                    <div class="row"> 
                                        <div class="col-lg-12"> 
                                            <div class="form-group">
                                                <label><b>TIPO DE PAGO:  </b><label><br/>
                                                <label class="radio-inline"><input type="radio" value="0" name="tipo" id="devolucion" checked><b>DEVOLUCIÓN</b></label>
                                                <label class="radio-inline"><input type="radio" value="2" name="tipo" id="traspaso"><b>TRASPASO</b></label>
                                                <input type="hidden" name="tipoproveedor" class="form-control" id="tipoproveedor">  
                                                <input type="hidden" name="operacion" class="form-control" id="operacion">  
                                                <label style="margin-left:15px; " class="checkbox-inline text-red" for="fecrecu"><input type="checkbox" value="1" name="prioridad"><b>URGENTE</b></label>
                                            </div>
                                        </div>
                                    </div>  
                                    <div class="row">
                                        <div class="col-lg-6 form-group">
                                            <label for="proyecto">PROYECTO</label>
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
                                        <div class="col-lg-6 form-group">
                                            <label for="empresa">EMPRESA</label>
                                            <select name="empresa" id="empresa" class="form-control lista_empresa" required></select>
                                        </div>
                                        <div class="col-lg-6 form-group">
                                            <label for="proveedor">PROVEEDOR</label>
                                            <input type="text" name="proveedor" class="form-control" id="proveedor" placeholder="Nombre del proveedor" required>
                                            <input type="hidden" name="nombreproveedor" class="form-control" id="nombreproveedor" >
                                            <input type="hidden" name="idproveedor" class="form-control" id="idproveedor">
                                        </div>
                                    </div>                                   
                                    <div class="row">
                                        <div class="col-lg-12 form-group"> 
                                            <h5>DATOS DEL TICKET / FACTURA <i class="far fa-question-circle faq" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" title="Datos de la factura" data-content="En caso de no tener la factura deberás de llenar el campo de 'descripción', 'SubTtotal','IVA','Total' y 'Método de pago', así mismo, deberás de especificar en observaciones el por qué no tiene factura. Recuerda especificar si te darán factura después." data-placement="right"></i></h5>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4 form-group">
                                            <label for="fecha">FECHA</label>
                                            <input type="date" class="form-control" min='2019-04-01' id="fecha" name="fecha" placeholder="Fecha" value="" required>
                                        </div>
                                        <div class="col-lg-4 form-group">
                                            <label for="total">TOTAL</label>
                                            <input type="text" class="form-control" id="total" name="total" placeholder="Total" value="" required>
                                        </div>
                                        <div class="col-lg-4 form-group">
                                            <label for="total">MONEDA</label>
                                            <select class="form-control" id="moneda" name="moneda" required>
                                                <option value="MXN" data-value="MXN">MXN</option>
                                                <option value="USD" data-value="USD">USD</option>
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
                                    <div class="row" id="row_cta">
                                        <div class="col-lg-4 form-group">
                                            <label>Tipo de cuenta.</label>
                                            <select class="form-control tipoctaselect" id="tipocta" name="tipocta" >
                                                <option value="">Seleccione una opción</option>
                                                <option value="1">Cuenta en Banco del Bajio</option>
                                                <option value="3">Tarjeta de débito / crédito</option>
                                                <option value="40">CLABE</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-4 form-group">
                                            <label>No. de cuenta:</label>
                                            <input type="text" name="cuenta" id="cuenta" class="form-control cuenta" maxlength="0">
                                        </div>
                                        <div class="col-lg-4 form-group">
                                            <label>Banco:</label> <select name="idbanco" class="form-control" id="idbanco" ><option value="">Seleccione una opción</option></select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 form-group">
                                            <label for="solobs">JUSTIFICACIÓN <i class="far fa-question-circle faq" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" title="Observaciones de la solicitud" data-content="En caso de ser necesario, se podrán especificar datos, comentarios u observaciones para cuentas por pagar, DG, DP o para la dirección del departamento solicitante." data-placement="right"></i></label>
                                            <textarea class="form-control" id="solobs" name="solobs" placeholder="Observaciones de la solicitud" required></textarea>
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
<script>
    $(document).ajaxStart( $.blockUI ).ajaxStop($.unblockUI);
    var documento_xml = null;
    var link_post = "";
    var idsolicitud = 0;
    var fecha_minima = "1990-01-01";
    var table_autorizar;
    var uno = "";
    var dos = "";
    var tres = false;

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
        $('input#cuenta').val("");
    });

    $('input#cuenta').keypress(function (event) {
        if (event.which < 48 || event.which > 57) {
            return false;
        }
    });

    $.getJSON( url + "Listas_select/lista_proveedores_edicion").done(function( data ){

        $.each(data['bancos'], function (ind, val) {
            $("#idbanco").append("<option value='"+val.idbanco+"'>"+val.nombre+"</option>");
        });

    });

    $('.fechas_filtro').datepicker({
          format: 'dd/mm/yyyy'
      });
    
    /*
    var today = new Date();
    var yesterday = new Date( today );
    yesterday.setDate( today.getDate() - 7 );
    var fecha_minima = yesterday.toISOString().substr(0, 10);
    */
    $(".lista_empresa").append('<option value="">Seleccione una opción</option>');
    $.getJSON( url + "Listas_select/lista_empresas").done( function( data ){
        $.each( data, function( i, v){
            $(".lista_empresa").append('<option value="'+v.idempresa+'" data-value="'+v.rfc+'">'+v.nombre+'</option>');
        });
    });
    
    
    
    $.getJSON( url + "Listas_select/lista_proveedores_libres" ).done( function( data ){
        $("#proyecto").append('<option value="">Seleccione una opción</option>');
        $.each( data.lista_proyectos_depto, function( i, v){
            $("#proyecto").append('<option value="'+v.concepto+'">'+v.concepto+'</option>');
        });
    });

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
                },error: function( data ){
                    
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
        $("#empresa, #proveedor, #xmlfile").prop('disabled', false);
        $("#empresa option, #proveedor option").prop("selected", false);
        $("#modal_formulario_solicitud .form-control, #modal_formulario_solicitud input[type='checkbox']").prop("checked", false).prop('disabled', false);

        $(".programar_fecha").prop('disabled', true);

        $("#responsable_cc").prop("disabled", true);

        $("#moneda").html( "" );
        $("#moneda").append( '<option value="MXN" data-value="MXN">MXN</option>' );
        $("#moneda").append( '<option value="USD" data-value="USD">USD</option>' );

        var validator = $( "#frmnewsol" ).validate();
        validator.resetForm();
        $( "#frmnewsol div" ).removeClass("has-error");

        $("#fecha").attr( "min",  fecha_minima );

        documento_xml = null;
        $("#devolucion").prop("checked", true);
        $("#proveedor").replaceWith('<input type="text" name="proveedor" class="form-control" id="proveedor" placeholder="Nombre del proveedor" required/>');
        //AutocompleteProveedor();         
        $("#idproveedor").val("");
        $("#proyecto").val($("#proyecto option:first").val());
        $("#forma_pago").val($("#forma_pago option:first").val());
        $("#tipocta").val($("#tipocta option:first").val());
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
        resear_formulario();
        link_post = "Solicitante/guardar_solicitud_devolucion";
        $("#modal_formulario_solicitud").modal( {backdrop: 'static', keyboard: false} );
    });

    

    $("#tabla_autorizaciones").ready( function () {

        $('#tabla_autorizaciones').on('xhr.dt', function ( e, settings, json, xhr ) {
            // table_autorizar.button( 1 ).enable( parseInt(json.por_autorizar) > 0 );
            
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
            if( i != 0 && i < $('#tabla_autorizaciones thead tr:eq(0) th').length - 1  ){
                var title = $(this).text();
                $(this).html( '<input type="text" style="font-size: .9em; width: 100%;" placeholder="'+title+'"/>' );
        
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
                    extend: 'excelHtml5',             
                    text: '<i class="fas fa-file-excel"></i> EXPORTAR EXCEL',
                    messageTop: "Listado de Devoluciones / Traspasos por autorizar",
                    attr: {
                        class: 'btn btn-success'       
                    },
                    exportOptions: {
                        columns: [0, 1, 4, 6, 7, 8, 9, 10, 11,],
                        format: {
                            header: function (data, columnIdx) { 

                            data = data.replace( '<input type="text" style="font-size: .9em; width: 100%;" placeholder="', '' );
                            data = data.replace( '">', '' )
                            return data;
                            }
                        }
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
                    "width": "15%",
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
                        return '<p style="font-size: .7em">'+d.nomdepto+'</p>'
                    }
                },
                {
                    "width": "20%",
                    "data" : function( d ){
                        return '<p style="font-size: .7em">'+d.nombre+'</p>'
                    }
                },
                {
                    "width": "15%",
                    "data" : function( d ){
                        return '<p style="font-size: .7em">'+d.metoPago+'</p>'
                    }
                },
                {
                    "width": "7%",
                    "data" : function( d ){
                        return '<p style="font-size: .7em">'+d.nempresa+'</p>'
                    }
                },
                {
                    "width": "15%",
                    "data" : function( d ){
                        return '<p style="font-size: .7em">'+d.justificacion+'</p>'
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
                    "data" : "opciones",
                    "orderable": false
                }
            ],
            "columnDefs": [
                {
                    "targets": [0],
                    "orderable": false
                },
                {
                    "targets": [4],
                    "orderable": false,
                    "visible": false,
                },
                {
                    "targets": [6],
                    "orderable": false,
                    "visible": false,
                },
                {
                    "targets": [8],
                    "orderable": false,
                    "visible": false,
                },
                {
                    "targets": [9],
                    "orderable": false,
                    "visible": false,
                }
            ],
            "ajax":  url + "Solicitante/tabla_autorizaciones"
        });
        
      $('#fecInicial').change( function() { 
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
         $('#fecFinal').change( function() {
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

            link_post = "Solicitante/editar_solicitud_contabilidad";

            var limite_edicion = 1;
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
                    

                    $("#descr").append( data.xml ? data.xml[0].conceptos : "" );

                    $("#fecha").val( data.info_solicitud[0].fecelab );
                    $("#folio").val( data.xml ? data.xml[0].folio : "" );
                    $("#subtotal").val( data.xml ? data.xml[0].SubTotal : "" );
                    $("#iva").val( data.xml ? data.xml[0].iva : "" );
                    $("#total").val( data.info_solicitud[0].cantidad );

                    $("#empresa option[data-value='"+data.info_solicitud[0].rfc_empresas+"']").prop("selected", true);

                    $("#proveedor").val( data.info_solicitud[0].nombre_proveedor );
                    $("#idproveedor").val( data.info_solicitud[0].idProveedor );
                        
                    $("#forma_pago option[data-value='"+data.info_solicitud[0].metoPago+"']").prop("selected", true);

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
                    $("#proyecto").val( data.info_solicitud[0].proyecto );
                    $("#modal_formulario_solicitud").modal( {backdrop: 'static', keyboard: false} );
                    
                    if(data.info_solicitud[0].TipoProv == 2){

                        $("#traspaso").prop("checked", true);
                        $("#proveedor").replaceWith('<select name="proveedor" class="form-control" id="proveedor" required>'+ 
                        '<option disabled selected>Seleccione un proveedor</option>'+
                        '</select>');
                        AutocompleteProveedor1();
                        $("#proveedor").val(data.info_solicitud[0].idproveedor);
                        $("#nombreproveedor").val(data.info_solicitud[0].nombre_proveedor);
                        $("#tipoproveedor").val(2);
                        $("#operacion").val('TRASPASO');
                    }else{
                        $("#devolucion").prop("checked", true);
                        $("#proveedor").replaceWith('<input type="text" name="proveedor" class="form-control" id="proveedor" placeholder="Nombre del proveedor"/>');
                        AutocompleteProveedor(); 
                        $("#proveedor").val(data.info_solicitud[0].nombre_proveedor);
                        $("#nombreproveedor").val($("#proveedor").val());
                        $("#tipoproveedor").val(0);
                        $("#operacion").val('DEVOLUCIONES');
                    }
                    
                    uno = $("#nombreproveedor").val();
                    dos = data.info_solicitud[0].idproveedor;
                    tres = true;

                }else{
                    alert("HA OCURRIDO UN ERROR")
                }
            });
        });

        $('#tabla_autorizaciones').on( "click", ".borrar_solicitud", function(){
            $.post( url + "Solicitante/borrar_solicitud", { idsolicitud : $(this).val() } ).done( function( data ){
                data = JSON.parse( data );
                if( data.resultado ){
                    table_autorizar.ajax.reload(null, false);
                }else{
                    alert("HA OCURRIDO UN ERROR")
                }
            });
        });

        $('#tabla_autorizaciones').on( "click", ".enviar_a_dg", function(){
            $.post( url + "Solicitante/enviar_a_dg", { idsolicitud : $(this).val() } ).done( function( data ){
                data = JSON.parse( data );
                if( data.resultado ){
                    table_autorizar.ajax.reload(null, false);
                    table_proceso.ajax.reload(null, false);
                }else{
                    alert("HA OCURRIDO UN ERROR")
                }
            });
        });

        $('#tabla_autorizaciones').on( "click", ".reenviar_factura", function(){
            $.post( url + "Solicitante/reenviar_factura", { idsolicitud : $(this).val() } ).done( function( data ){
                data = JSON.parse( data );
                if( data.resultado ){
                    table_autorizar.ajax.reload(null,false);
                    table_proceso.ajax.reload(null,false);
                }else{
                    alert("HA OCURRIDO UN ERROR")
                }
            });
        });

        $('#tabla_autorizaciones').on( "click", ".aprobada_da", function(){
            $.post( url + "Solicitante/aprobada_da", { idsolicitud : $(this).val() } ).done( function( data ){
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
            $("#modal_acciones_sol").modal("show");
            $("#idsolicitud_accion").val($(this).val());
            $("#modal_acciones_sol .modal-header").css({backgroundColor: 'red'});
            $("#modal_acciones_sol .modal-title").text("RECHAZAR SOLICITUD #"+$(this).val());
            liga_acciones_sol= "Solicitante/rechazada_da";
        });
        
        $("#form_acciones_sol").submit(function (e){
            e.preventDefault();
            $.post( url + liga_acciones_sol, $(this).serialize() ).done( function( data ){
                data = JSON.parse( data );
                if( data.resultado ){
                    table_autorizar.ajax.reload(null,false);
                    $("#modal_acciones_sol").modal("hide");
                    $("#form_acciones_sol").trigger("reset");
                }else{
                    alert("HA OCURRIDO UN ERROR");
                }
            });
        });

        $('#tabla_autorizaciones').on( "click", ".congelar_solicitud", function(){
            $.post( url + "Solicitante/congelar_solicitud", { idsolicitud : $(this).val() } ).done( function( data ){
                data = JSON.parse( data );
                if( data.resultado ){
                    table_autorizar.ajax.reload(null,false);
                }else{
                    alert("HA OCURRIDO UN ERROR")
                }
            });
        });

        $('#tabla_autorizaciones').on( "click", ".liberar_solicitud", function(){
            $.post( url + "Solicitante/liberar_solicitud", { idsolicitud : $(this).val() } ).done( function( data ){
                data = JSON.parse( data );
                if( data.resultado ){
                    table_autorizar.ajax.reload(null,false);
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
            if( i!=10 ){
                var title = $(this).text();
                $(this).html( '<input type="text" style="font-size: .9em; width: 100%;" placeholder="'+title+'"/>' );
        
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
                    text: '<i class="fas fa-file-excel"></i> EXPORTAR EXCEL',
                    action: function(){

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
                    "width": "9%",
                    "data" : function( d ){
                        return '<p style="font-size: .9em">'+d.idsolicitud+'</p>'
                    }
                },
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
                    "width": "15%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.nombre+'</p>'
                    }
                },
                {
                    "width": "10%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.fecelab+'</p>'
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
                        if( d.etapa == 'Revisión Cuentas Por Pagar'){
                            var fec =  new Date();//new Date(fec[1]+'/'+fec[0]+"/"+fec[2]);
                            var day = fec.getDay();
                            var dias = 2-day;
                            if (dias > 0){
                                fec.setDate(fec.getDate() + dias);
                            }else if (dias < 0){
                                fec.setDate(fec.getDate() + (7 + dias));
                            }else if( dias == 0){                                
                                if(d.fecha_autorizacion == dateToDMY(fec)){
                                    fec.setDate(fec.getDate() + 7);
                                }
                            }
                            return '<p style="font-size: .8em">Próxima revisión de Cuentas por Pagar</p>' + 
                            '<p style="font-size: .8em">'+dateToDMY(fec)+'</p>'
                            }
                        else{
                            return '<p style="font-size: .8em">'+d.etapa+'</p>'
                        }
                        //return '<p style="font-size: .8em">'+d.etapa+'</p>'
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
                    
            var iStartDateCol = 4;
            var iEndDateCol = 4;

            iFini=iFini.substring(6,10) + iFini.substring(3,5)+ iFini.substring(0,2);
            iFfin=iFfin.substring(6,10) + iFfin.substring(3,5)+ iFfin.substring(0,2);
            console.log(iFini+" "+iFfin);
                //alert(iFini);
                // alert(iFfin);
            var datofini=aData[iStartDateCol].substring(0,4) + aData[iStartDateCol].substring(5,7)+ aData[iStartDateCol].substring(8,12);
            var datoffin=aData[iEndDateCol].substring(0,4) + aData[iEndDateCol].substring(5,7)+ aData[iEndDateCol].substring(8,12);
            console.log(datofini+" "+datofini+" -- "+iFini);
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
     var consulta = "";
    //Autocomplementación en provedoores ya sea tanto para editar o agregar NUEVA FACTURA - PAGO A PROVEEDOR
    $.getJSON( url + "Listas_select/lista_provedores_autocomplemento" ).done( function( data ){    
        consulta = data; 
        AutocompleteProveedor();         
    }); 

    function AutocompleteProveedor(){
        var ss= [];
        /*$.each(consulta, function (i, item) {
            if(item.tipo != 2)
            {
                ss.push(item);
            }
        
        });*/
        $("#proveedor").autocomplete({
            source: ss,
            open: function(){
                $(this).autocomplete('widget').css('z-index', 1150);
                return false;
            },
            minLength: 2,
            select: function(event, ui) {
                event.preventDefault();
                $("#proveedor").val(ui.item.label);

                if($("#idproveedor").val(ui.item.value) != 0){
                    $("#nombreproveedor").val(ui.item.label);
                    $("#idproveedor").val(ui.item.value);
                }else{
                    $("#idproveedor").val("");
                }
                //alert($("#idproveedor").val(ui.item.value));
            },
            autoFocus: showLabel,
            focus: mifuncion,
            change: mifuncion
        });

        function showLabel(event, ui) {
               $("#nombreproveedor").val(ui.item.label);
               $("#idproveedor").val(ui.item.value);
            }

        function mifuncion(event, ui) {
            uno = ui.item.label;
            dos = ui.item.value;         
        }

        $(document).on('focusout', '#proveedor', function(){
            var tipp = $('#proveedor')[0].tagName;
            if(tipp == "INPUT"){
                if( $(this).val() == uno ){
                    $("#nombreproveedor").val(uno);
                    $("#idproveedor").val(dos);
                }
                else{
                    if(tres){                        
                        $("#idproveedor").val(dos);
                        $("#nombreproveedor").val($(this).val());
                    }else{
                        $("#idproveedor").val("");
                        $("#nombreproveedor").val($(this).val());
                    }
                } 
            } 
            else if(tipp == "SELECT"){
                $("#nombreproveedor").val($('select[name="proveedor"] option:selected').text() );
                $("#idproveedor").val($('#proveedor').val());
            }
            

        });
    }

    function AutocompleteProveedor1(){
        
        $.each(consulta, function (i, item) {
            if(item.tipo == 2)
            {
                $('#proveedor').append($('<option>', { 
                    value: item.value,
                    text : item.label 
                }));
            }        
        });
        
        $("#proveedor").change(function(){ 
            uno = $('select[name="proveedor"] option:selected').text();
            dos = this.value;
            $("#idproveedor").val(dos);
            $("#nombreproveedor").val(uno );
        });
    }


    $("#proveedor").keydown(function(event){                   
        $("#idproveedor").val("");
    });
    
    $(window).resize(function(){
        table_autorizar.columns.adjust();
        table_proceso.columns.adjust();
    });

    $('input[type=radio][name=tipo]').change(function() {
        $("#tipoproveedor").val(this.value);
        if(this.value == 0){
            $("#proveedor").replaceWith('<input type="text" name="proveedor" class="form-control" id="proveedor" placeholder="Nombre del proveedor" required/>');
            AutocompleteProveedor();
            $("#operacion").val('DEVOLUCIONES');
        }else  if(this.value == 2){
            $("#proveedor").replaceWith('<select name="proveedor" class="form-control" id="proveedor" placeholder="otro place del proveedor" required>'+ 
            '<option disabled selected>Seleccione un proveedor</option>'+
            '</select>');
            AutocompleteProveedor1();
            $("#operacion").val('TRASPASO');
        }
        $("#idproveedor").val("");
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
            console.log("devolucion y transferencia");
            $("select#tipocta").prop("required", true);
            $("#cuenta").attr("required", true);
            $("select#idbanco").prop("required", true);
            $("#row_cta").show();
        }
        else{
            console.log("otra forma de pago y tipo");
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

</script>
<?php
    require("footer.php");
?>