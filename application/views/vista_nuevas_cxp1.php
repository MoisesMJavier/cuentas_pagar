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
                                <li class="active"><a id="autorizaciones-tab" data-toggle="tab" href="#facturas_aturizar" role="tab" aria-controls="#home" aria-selected="true">SOLICITUDES PROVEEDOR</a></li>                               
                                <li><a id="profile-tab" data-toggle="tab" href="#pagos_autoriza_dg_trans" role="tab" aria-controls="pagos_autoriza_dg_trans" aria-selected="false">SOLICITUDES CAJA CHICA</a></li>
                                <li><a id="profile-tab" data-toggle="tab" href="#pagos_autoriza_dg_fact" role="tab" aria-controls="pagos_autoriza_dg_fact" aria-selected="false">SOLICITUDES FACTORAJE</a></li>
                                <li><a id="profile-tab" data-toggle="tab" href="#pagos_programados" role="tab" aria-controls="pagos_programados" aria-selected="false">PAGOS PROGRAMADOS</a></li>
                            </ul>
                        </div>
                        <div class="tab-content">
                            <div class="active tab-pane" id="facturas_aturizar">
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
                                        <h4>SOLICITUDES NUEVAS CAJA CHICA<i class="far fa-question-circle faq" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" title="Autorizaciones" data-content="Estas son todas las solicitudes de pago a caja chica que han pasado por la primer validación de Dirección General." data-placement="right"></i><label>&nbsp;Total: $<input style="border-bottom: none; border-top: none; border-right: none;  border-left: none; background: white;" disabled="disabled" readonly="readonly" type="text"  name="myText_2" id="myText_2"></label></h4><BR>
                                        <table class="table table-responsive table-bordered table-striped table-hover" id="tabla_autorizaciones_caja_chica" name="tabla_autorizaciones_caja_chica">
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
                             <div class="tab-pane fade" id="pagos_autoriza_dg_fact">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <h4>SOLICITUDES NUEVAS FACTORAJE<i class="far fa-question-circle faq" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" title="Autorizaciones" data-content="Estas son todas las solicitudes de pago a caja chica que han pasado por la primer validación de Dirección General." data-placement="right"></i><label>&nbsp;Total: $<input style="border-bottom: none; border-top: none; border-right: none;  border-left: none; background: white;" disabled="disabled" readonly="readonly" type="text"  name="myText_3" id="myText_3"></label></h4><BR>
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
                                        <h4>PAGOS PROGRAMADOS<i class="far fa-question-circle faq" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" title="Autorizaciones" data-content="Solicitudes que involucran un fecha especifica de pago. Pueden tener una fecha inicial o son indeterminados." data-placement="right"></i><label>&nbsp;Total: $ <input style="border-bottom: none; border-top: none; border-right: none;  border-left: none; background: white;" disabled="disabled" readonly="readonly" type="text" id="tprogramado"></label></h4><BR>
                                        <table class="table table-responsive table-bordered table-striped table-hover" id="tabla_pagos_programados">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th style="font-size: .8em"></th>
                                                    <th style="font-size: .8em">#</th>
                                                    <th style="font-size: .8em">PERIODOS</th>
                                                    <th style="font-size: .8em">PROVEEDOR</th>
                                                    <th style="font-size: .8em">TOTAL CUOTA</th>
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

<script>

    var tabla_nueva_proveedor;
    var tabla_nueva_caja_chica;

    var fecha_hoy = new Date();
    var rol = `<?= $this->session->userdata("inicio_sesion")['rol'] ?>`;

    $('[data-toggle="tab"]').click( function(e) {
        switch( $(this).attr('href') ){
            case '#facturas_aturizar':
                tabla_nueva_proveedor.ajax.url( url + "Cuentasxp/ver_nuevas_proveedor" ).load();
                break;
            case '#pagos_autoriza_dg_trans':
                tabla_nueva_caja_chica.ajax.url( url + "Cuentasxp/ver_nuevas_caja_chica" ).load();
                break;
            case '#pagos_autoriza_dg_fact':
                tabla_nueva_fact.ajax.url( url + "Cuentasxp/ver_nuevas_fact" ).load();
                break;
            case '#pagos_programados':
                tabla_pagos_programados_nuevo.ajax.url( url +"Cuentasxp/tabla_programados_espera" ).load();
                break;
        }
    });

    fecha_hoy = fecha_hoy.getFullYear()+"-"+(fecha_hoy.getMonth() +1)+"-"+fecha_hoy.getDate();

    $("#tabla_autorizaciones_proveedores").ready( function(){

        $('#tabla_autorizaciones_proveedores thead tr:eq(0) th').each( function (i) {
            if( i != 0 && i != 12 && i != 10 && i != 11){
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
            "processing": true,
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
                    return '<p style="font-size: .8em">'+d.idsolicitud+'</p>'
                }
            },
            {
                "width": "8%",
                "data" : function( d ){
                    return '<p style="font-size: .8em">'+d.folio+'</p>'
                }
            },
            {
                "width": "12%",
                "data": function( d ){

                    if (d.programado != null && d.programado != 0 && d.programado != '') {
                        return  d.nombre + "<br><small class='label pull-center bg-blue'>PROGRAMADO</small>";
                    }else{
                        return '<p style="font-size: .9em">'+d.nombre+'</p>';
                    }
                
                }
            },
            {
                "width": "10%",
                "data" : function( d ){
                    return '<p style="font-size: .8em">'+d.nombres+" "+d.apellidos+'</p>'
                }
            },
            {
                "width": "11%",
                "data" : function( d ){
                    return '<p style="font-size: .8em">$ '+formatMoney( d.cantidad )+" "+d.moneda+'</p>'
                }
            },
            {
                "width": "10%",
                "data" : function( d ){
                    return '<p style="font-size: .8em">'+d.fecelab+'</p>'
                }
            },
            {
                "width": "10%",
                "data" : function( d ){
                    return '<p style="font-size: .8em">'+d.nomdepto+'</p>'
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
                "data": function( d ){
                    return '<p style="font-size: .8em">'+d.fechaCreacion+'</p>'
                }
            },
            {
                "width": "5%",
                "data": function( d ){
                    
                    var texto = "";
                    if( d.okfactura ){ 
                        texto += "<center><i style='color:orange;' class='fas fa-check'></i> <span style='font-size: .7em'>FACTURA</span></center>";
                    }

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

                    opciones = '<div class="btn-group" role="group">';
 
                    opciones += '<button type="button" class="notification btn btn-primary btn-sm consultar_modal" style="" value="'+data.idsolicitud+'" data-value="SOL" title="Ver Solicitud"><i class="far fa-eye"></i>'+(data.visto == 0 ? '</i><span class="badge">!</span>' : '')+'</button>';
                    opciones += '<button type="button" class="btn btn-success btn-sm aceptar_pago" data-value="PROV" title="Aceptar Solicitud"><i class="fas fa-check"></i></button>';
                    opciones += '<button type="button" class="btn btn-danger rechazar_pago btn-sm" data-value="PROV" title="Rechazar Solicitud"><i class="fas fa-close"></i></button>';
 
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
                var informacion_adicional = '<table class="table text-justify">'+
                '<tr>'+
                '<td style="font-size: .9em"><strong>CAPTURISTA: </strong>'+row.data().nombre_capturista+'</td>'+
                '<td style="font-size: .9em"><strong>FORMA DE PAGO: </strong>'+row.data().metoPago+'</td>'+
                '</tr>'+
                '<tr>'+
                '<td style="font-size: .9em" colspan="2"><strong>JUSTIFICACIÓN: </strong>'+row.data().justificacion+'</td>'+
                '</tr>'+
                '</table>';

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
            "processing": true,
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
                        opciones = '<div class="btn-group" role="group">';
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
            "processing": true,
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
            ]
        });

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
    });

    function detalles_caja_chica( data ){
        var solicitudes = '<table class="table">';
        $.each( data, function( i, v){ 
            //i es el indice y v son los valores de cada fila
            solicitudes += '<tr>';
            solicitudes += '<td>'+(v.solinum)+' - <b>'+'PROYECTO: '+'</b> '+v.proyecto+'</td>';
            solicitudes += '<td>'+'<b>'+'PROVEEDOR '+'</b> '+v.Proveedor+'</td>';
            solicitudes += '<td>'+'<b>'+'CANTIDAD: '+'</b> $'+formatMoney(v.cnt2)+' MXN</td>';
            solicitudes += '<td>'+'<b>'+'TIPO PAGO: '+'</b>'+v.metoPago+'</td>';
            solicitudes += '<td>'+
                '<div class="btn-group" role="group">'+
                '<button type="button" class="notification btn btn-primary btn-sm consultar_modal" style="" value="'+v.solinum+'" data-value="SOL" title="Ver Solicitud"><i class="far fa-eye"></i>'+(v.visto == 0 ? '</i><span class="badge">!</span>' : '')+'</button>'+
                '<button type="button" class="btn btn-success btn-sm aceptar_pago" data-value="'+i+'" title="Aceptar Solicitud">'+'<i class="fas fa-check">'+'</i>'+'</button>'+
                '<button type="button" class="btn btn-danger btn-sm rechazar_pago" data-value="'+i+'" title = "Rechazar Solicitud">'+'<i class="fas fa-close">'+'</i>'+'</button>'+
                (v.nfac==0?'<button type="button" class="btn btn-sm btn-warning" onclick="modal_xml(\''+v.solinum+'|'+v.moneda+'\')" title="Cargar Factura XML" value="'+v.solinum+'|'+v.moneda+'"><i class="fas fa-file-export"></i></button>':'')+
                '</div></td>';
            solicitudes += '</tr>';
            solicitudes += '<tr>';
            solicitudes += '<td>'+'<b>'+'FECHA FACT: '+'</b> '+v.FECHAFACP+'</td>';
            solicitudes += '<td colspan="4">'+'<b>'+'JUSTIFICACIÓN: '+'</b> '+v.Observacion+'</td>';
            solicitudes += '</tr>';
        });          

        return solicitudes + '</table>';
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
                        tabla_nueva_caja_chica.ajax.reload();
                        
                        
                    }else{
                        alert( data.respuesta[1] );
                    }
                },error: function( ){
                    alert("ERROR EN EL SISTEMA");
                }
            });
        }
    });

    var trsolicitudes;
    var trsolicitudescch;
    var indexcajas_chicas;

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

    //RECHAZAMOS EL PAGO Y MANDAMOS A ESTATUS DE RECHAZO.
    $( document ).on("click", ".rechazar_pago", function(){
        if( $(this).attr( "data-value" ) == 'PROV' || $(this).attr( "data-value" ) == 'PROG' ){
            trsolicitudes = $(this).closest('tr');    
        } 
        indexcajas_chicas = $(this).attr( "data-value" );
        $("#observacion").val('');
        $("#myModalcomentario1").modal();
    });

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
                $.post( url + "Cuentasxp/enviarcpp/", { idsolicitud : row.data().solicitudes[indexcajas_chicas].solinum } ).done(function ( data ) { 
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

    $( document ).on("click", "#sin_provisionar", function(){
        var row = tabla_nueva_proveedor.row( trsolicitudes );
        $.get( url + "Cuentasxp/provisionar_mal/"+row.data().idsolicitud ).done(function (data) { 
          
        if(data!='0'){
                         $("#modal_provisionar").modal('toggle');
                          tabla_nueva_proveedor.ajax.reload();
                        }
                        else{
                            $("#modal_provisionar").modal('toggle');
                          alert("Esta solicitud ya se atendió recientemente por CXP");
                         }
 
                    }).fail( function(){
 
                });
    });

    $( document ).on("click", "#provisionar", function(){
        var row = tabla_nueva_proveedor.row( trsolicitudes );
        $.get(url+"Cuentasxp/provisionar_ok/"+row.data().idsolicitud).done(function (data) {
           
            if(data!='0'){
                $("#modal_provisionar").modal('toggle');
                tabla_nueva_proveedor.ajax.reload();
            }else{
                $("#modal_provisionar").modal('toggle');
                alert("Esta solicitud ya se atendió recientemente por CXP");
            }

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
                    idsolrechazo = row.data().solicitudes[indexcajas_chicas].solinum;
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
                    alert("ERROR EN EL SISTEMA");
                }
            });
        }
    });

    //TABLA DE PAGOS PROGRAMADOS
    var tabla_pagos_programados_nuevo;

    $("#tabla_pagos_programados").ready( function(){

        $('#tabla_pagos_programados thead tr:eq(0) th').each( function (i) {
            if( i != 15 && i != 0 ){
                var title = $(this).text();
                $(this).html( '<input type="text" style="width:100%;" placeholder="'+title+'" />' );
                $( 'input', this ).on('keyup change', function () {

                    if (tabla_pagos_programados_nuevo.column(i).search() !== this.value ) {
                        tabla_pagos_programados_nuevo
                        .column(i)
                        .search( this.value )
                        .draw();
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
                "processing": true,
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
                                p =  "<small class='label pull-center bg-gray'>SEMESTRAK</small>";
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
                        return '<p style="font-size: .8em">$ '+formatMoney( d.cantidad * d.ppago )+'</p>'
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
                    "width": "8%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">$ '+formatMoney( ( parseFloat( d.tparcial != d.cantidad_confirmada ? d.cantidad_confirmada : d.tparcial ) + parseFloat(d.interes) ) )+'</p>'
                    }
                },
                {
                    "width": "8%",
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
                    "width": "7%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.nemp+'</p>'
                    }
                },
                {
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
                    "width": "12%",
                    "data": function( data ){
                        opciones = '<div class="btn-group" role="group">';
                        opciones += '<button type="button" class="notification btn btn-primary btn-sm consultar_modal" style="" value="'+data.idsolicitud+'" data-value="SOL" title="Ver Solicitud"><i class="far fa-eye"></i>'+(data.visto == 0 ? '</i><span class="badge">!</span>' : '')+'</button>';
                        opciones += '<button type="button" class="btn btn-success btn-sm aceptar_pago" data-value="PROG" title="Aceptar Solicitud"><i class="fas fa-check"></i></button>';
                        opciones += '<button type="button" class="btn btn-danger rechazar_pago btn-sm" data-value="PROG" title="Rechazar Solicitud"><i class="fas fa-close"></i></button>';

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
                    var informacion_adicional = '<table class="table text-justify">'+
                    '<tr>'+
                    '<td style="font-size: .9em"><strong>CAPTURISTA: </strong>'+row.data().nombre_capturista+'</td>'+
                    '<td style="font-size: .9em"><strong>FORMA DE PAGO: </strong>'+row.data().metoPago+'</td>'+
                    '</tr>'+
                    '<tr>'+
                    '<td style="font-size: .9em" colspan="2"><strong>JUSTIFICACIÓN: </strong>'+row.data().justificacion+'</td>'+
                    '</tr>'+
                    '</table>';

                    row.child( informacion_adicional ).show();
                    tr.addClass('shown');
                    $(this).parent().find('.animacion').removeClass("fa-caret-right").addClass("fa-caret-down");
                }
            });
        });
    //FIN PAGOS PROGRAMADOS


    $(window).resize(function(){
        tabla_nueva_proveedor.columns.adjust();
        tabla_nueva_caja_chica.columns.adjust();
        tabla_nueva_proveedor.columns.adjust();
    });

</script>
<?php
    require("footer.php");
?>