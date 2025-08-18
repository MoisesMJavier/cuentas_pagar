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
                        <h3>REVISIÓN DEVOLUCIONES</h3>
                    </div>
                    <div class="box-body">
                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                                <li class="active"><a id="autorizaciones-tab" data-toggle="tab" href="#sol_activas" role="tab" aria-controls="#home" aria-selected="true">DEVOLUCIONES POR AUTORIZAR</a></li>
                                <li><a id="autorizaciones-tab" data-toggle="tab" href="#historial_dp" role="tab" aria-controls="#historial_dp" aria-selected="true">DEVOLUCIONES AUTORIZADAS </a></li>
                            </ul>
                        </div>
                        <div class="tab-content">
                            <div class="active tab-pane" id="sol_activas">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <h4>POR AUTORIZAR <i class="far fa-question-circle faq" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" title="Autorizaciones" data-content="Estas son todas las solicitudes de pago a proveedores que se encuentran en proceso de pago, verifica el status de la solicitud para saber en qué parte del proceso se encuentra." data-placement="right"></i><label>Total: $<input style="border-bottom: none; border-top: none; border-right: none; border-left: none; background: white;" disabled="disabled" readonly="readonly" type="text" name="myText_1" id="myText_1"></label></h4>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <table class="table table-striped" id="tabla_autorizaciones" name="tabla_autorizaciones">
                                                    <thead class="thead-dark">
                                                        <tr>
                                                            <th></th>
                                                            <th style="font-size: .9em;">#</th>
                                                            <th style="font-size: .9em;">EMPRESA</th>
                                                            <th style="font-size: .9em;">PROYECTO</th>
                                                            <th style="font-size: .9em;">PROVEEDOR</th>
                                                            <th style="font-size: .9em;">FECHA SOL</th>
                                                            <th style="font-size: .9em;">CAPTURA</th>
                                                            <th style="font-size: .9em;">DEPARTAMENTO</th>
                                                            <th style="font-size: .9em;">CANTIDAD</th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class=" tab-pane" id="historial_dp">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <h4> HISTORIAL AUTORIZADAS <i class="far fa-question-circle faq" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" title="Autorizaciones" data-content="Estas son todas las solicitudes de pago a proveedores que se encuentran el proceso de pago, han pasado por la primer validación de Dirección General." data-placement="right"></i><label>&nbsp;Total: $<input style="border-bottom: none; border-top: none; border-right: none; border-left: none; background: white;" disabled="disabled" readonly="readonly" type="text" name="myText_2" id="myText_2"></label></h4>
                                        <table class="table table-striped" id="tabla_historial_autorizaciones" name="tabla_historial_autorizaciones">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th></th>
                                                    <th style="font-size: .9em;">#</th>
                                                    <th style="font-size: .9em;">EMPRESA</th>
                                                    <th style="font-size: .9em;">PROYECTO</th>
                                                    <th style="font-size: .9em;">PROVEEDOR</th>
                                                    <th style="font-size: .9em;">FECHA SOL</th>
                                                    <th style="font-size: .9em;">CAPTURA</th>
                                                    <th style="font-size: .9em;">DEPARTAMENTO</th>
                                                    <th style="font-size: .9em;">CANTIDAD</th>
                                                    <th style="font-size: .9em;">ETAPA</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
 
<script>
    
    $('[data-toggle="tab"]').click( function(e) {
        switch( $(this).attr('href') ){
            case '#sol_activas':
                tabla_devolucion.ajax.reload();
                break;
            case '#historial_dp':
                //table_proceso.ajax.url( url +"Devoluciones_Traspasos/tabla_facturas_encurso" ).load();
                tabla_historial.ajax.reload();
                break;
        }
    });
    
    $(window).resize(function(){
        tabla_devolucion.columns.adjust();
        tabla_historial.columns.adjust();
    });

    var tabla_devolucion;
    var tabla_historial;
    var tabla;
    var idsolicitud;
    var td;
    var link_post;
    var avanza=1;

    $("#tabla_autorizaciones").ready( function(){

        $('#tabla_autorizaciones thead tr:eq(0) th').each( function (i) {
            if( i != 0 && i < $('#tabla_autorizaciones thead tr:eq(0) th').length - 1){
                var title = $(this).text();
                $(this).html( '<input type="text" style="width:100%;" placeholder="'+title+'"/>' );
                $( 'input', this ).on('keyup change', function () {

                    if (tabla_devolucion.column(i).search() !== this.value ) {
                        tabla_devolucion
                        .column(i)
                        .search( this.value )
                        .draw();
                        var total = 0;
                        var index = tabla_devolucion.rows( { selected: true, search: 'applied' } ).indexes();
                        var data = tabla_devolucion.rows( index ).data()
                        $.each(data, function(i, v){
                            total += parseFloat(v.cantidad);
                        });
                        var to1 = formatMoney(total);
                        document.getElementById("myText_1").value = to1;
                    }
                });
            }
        });

        $('#tabla_autorizaciones').on('xhr.dt', function ( e, settings, json, xhr ) {
            var total = 0;
            $.each( json.data,  function(i, v){
                total += parseFloat(v.cantidad);
            });
            var to = formatMoney(total);
            document.getElementById("myText_1").value = to;
        });

        tabla_devolucion = $("#tabla_autorizaciones").DataTable({
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
                        columns: [ 1, 2, 3, 4, 5, 6, 7, 8 ],
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
            "language":lenguaje,
            "processing": false,
            "pageLength": 50,
            "bAutoWidth": false,
            "bLengthChange": false,
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
                "width": "6%",
                "data": function( d ){
                    return '<p style="font-size: .8em;">'+d.idsolicitud+"</p>";
                } 
            },
            {
                "width": "8%",
                "data": function( data ){
                    return '<p style="font-size: .8em;">'+data.abrev+"</p>";
                }
            },
            {
                "width": "8%",
                "data": function( data ){
                    return '<p style="font-size: .8em;">'+data.proyecto+"</p>";
                }
            },
            
            { 
                "width": "15%",
                "data": function( data ){

                    return '<p style="font-size: .8em;">'+data.nombre+"</p>";

                } 
            },

            { 
                "width": "10%",
                "data": function( d ){
                    return '<p style="font-size: .8em;">'+d.fecha_autorizacion+"</p>";
                }
            },
            { 
                "width": "12%",
                "data": function( d ){
                    return '<p style="font-size: .8em;">'+d.auto+'</p>';

                }
            },

            {
                "width": "12%", 
                "data": function( data ){
                    return '<p style="font-size: .8em;">'+data.nomdepto+"</p>";

                } 
            },

            {
                "width": "12%", 
                "data": function( data ){
                    return '<p style="font-size: .8em;">'+formatMoney(data.cantidad)+"</p>";

                } 
            },
            {
                "orderable": false,
                "data": function( data ){
                        opciones = '<div class="btn-group-vertical" role="group">';

                        opciones += '<button type="button" class="notification btn btn-primary btn-sm consultar_modal" value="'+data.idsolicitud+'" data-value="BAS" title="VER SOLICITUD"><i class="far fa-eye"></i>'+(data.visto == 0 ? '</i><span class="badge">!</span>' : '')+'</button>';
                        opciones += '<button data-toggle="tooltip" title="APROBAR SOLICITUD" type="button" class="btn bg-olive btn-sm aceptar_devolucion" value="'+data.idsolicitud+'"><i class="fa fa-check-circle"></i></button>';
                        opciones += '<button data-toggle="tooltip" title="REGRESAR SOLICITUD" type="button" class="btn bg-red btn-sm cancelar_devolucion" value="'+data.idsolicitud+'"><i class="fas fa-undo-alt"></i></button>';            

                        return opciones + '</div>';
            
                }
            }
        ],
        "ajax": {
            "url": url + "Contabilidad/ver_devXaut",
            "type": "POST",
            cache: false,
            "data": function( d ){
                return  $.extend( d, { "nomdepto": $("#cxp1_sel").val(), "idempresa": $("#cxp2_sel").val() } );
            }
            }
        });

        $('#tabla_autorizaciones tbody').on('click', 'td.details-control', function () {
            var tr = $(this).parents('tr');
            var row = tabla_devolucion.row( tr );

            if ( row.child.isShown() ) {
                row.child.hide();
                tr.removeClass('shown');
                $(this).parent().find('.animacion').removeClass("fa-caret-down").addClass("fa-caret-right");
            }
            else {
                var informacion_adicional = '<table class="table text-justify">'+
                '<tr>'+
                '<td style="font-size: .9em" colspan="2"><strong>JUSTIFICACIÓN: </strong>'+row.data().justificacion+'</td>'+
                '</tr>'+
                '</table>';

                row.child( informacion_adicional ).show();
                tr.addClass('shown');
                $(this).parent().find('.animacion').removeClass("fa-caret-right").addClass("fa-caret-down");
            }
        });
        
        $('#tabla_autorizaciones').on( "click", ".aceptar_devolucion", function(){
            $("#modal_acciones_sol").modal("show");
            $("#idsolicitud_accion").val($(this).val());
            $("#modal_acciones_sol .modal-header").css({backgroundColor: 'green'});
            $("#modal_acciones_sol .modal-title").text("ACEPTAR SOLICITUD #"+$(this).val());
            liga_acciones_sol= "../Devoluciones_Traspasos/devolucion_sigetapa/";
            $("#form_acciones_sol").trigger("reset");
            avanza=1;
        });
        
        $('#tabla_autorizaciones').on( "click", ".cancelar_devolucion", function(){
            $("#modal_acciones_sol").modal("show");
            $("#idsolicitud_accion").val($(this).val());
            $("#modal_acciones_sol .modal-header").css({backgroundColor: 'red'});
            $("#modal_acciones_sol .modal-title").text("RECHAZAR SOLICITUD #"+$(this).val());
            liga_acciones_sol= "../Devoluciones_Traspasos/devolucion_sigetapa/";
            $("#form_acciones_sol").trigger("reset");
            avanza=0;
        });
        
        $("#form_acciones_sol").submit(function (e){
            e.preventDefault();
            var fd = new FormData( $(this)[0] );
            fd.append("etapaold", 53);
            fd.append("avanza", avanza);
            
            var data = enviar_post(fd,liga_acciones_sol);
            if(data.resultado){
                tabla_devolucion.ajax.reload();
                tabla_historial.ajax.reload();
                $("#modal_acciones_sol").modal("hide");
            }
        });
    });

    //SEGUIMIENTO DE DEVOLUCIONES CAPTURADOS
    $("#tabla_historial_autorizaciones").ready( function(){

        $('#tabla_historial_autorizaciones thead tr:eq(0) th').each( function (i) {
            if( i != 0 && i < $('#tabla_historial_autorizaciones thead tr:eq(0) th').length - 1 ){
                var title = $(this).text();
                $(this).html( '<input type="text" style="width:100%;" placeholder="'+title+'"/>' );
                $( 'input', this ).on('keyup change', function () {

                    if (tabla_historial.column(i).search() !== this.value ) {
                        tabla_historial
                        .column(i)
                        .search( this.value )
                        .draw();

                        var total = 0;
                        var index = tabla_historial.rows( { selected: true, search: 'applied' } ).indexes();
                        var data = tabla_historial.rows( index ).data();

                        $.each(data, function(i, v){
                            total += parseFloat(v.cantidad);
                        });

                        var to1 = formatMoney(total);
                        document.getElementById("myText_2").value = to1;
                    }
                });
            }
        });
        

        $('#tabla_historial_autorizaciones').on('xhr.dt', function ( e, settings, json, xhr ) {
            var total = 0;
            $.each( json.data,  function(i, v){
                total += parseFloat(v.cantidad);
            });
            var to = formatMoney(total);
            document.getElementById("myText_2").value = to;

            tabla_historial.button( 1 ).enable( parseInt(json.por_autorizar) > 0 );
        });

        tabla_historial = $("#tabla_historial_autorizaciones").DataTable({
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
                    columns: [ 1, 2, 3, 4, 5, 6, 7 , 8, 9 ],
                    format: {
                            header: function (data, columnIdx) { 

                                data = data.replace( '<input type="text" style="font-size: .8em; width:100%;" placeholder="', '' );
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
            "language":lenguaje,
            "processing": false,
            "pageLength": 50,
            "bAutoWidth": false,
            "bLengthChange": false,
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
            "width": "6%",
            "data": function( data ){
                return '<p style="font-size: .8em;">'+data.idsolicitud+"</p>";
            } 
        },
        {
            "width": "10%",
            "data": function( data ){
                return '<p style="font-size: .8em;">'+data.abrev+"</p>";
            }
        },
        {
            "width": "10%",
            "data": function( data ){
                // return '<p style="font-size: .9em;">'+formatMoney(data.CA)+"</p>";
                return '<p style="font-size: .8em;">'+data.proyecto+"</p>";
            }
        },
        { 
            "width": "20%",
            "data": function( data ){

                return '<p style="font-size: .8em;">'+data.nombre+"</p>";

            } 
        },
        { 
            "width": "12%",
            "data": function( data ){
                return '<p style="font-size: .8em;">'+data.fecha_autorizacion+"</p>";
            }
        },
        { 
            "width": "16%",
            "data": function( data ){
                return '<p style="font-size: .8em;">'+data.auto+'</p>';

            }
        },
        {
            "width": "12%", 
            "data": function( data ){
                return '<p style="font-size: .8em;">'+data.nomdepto+"</p>";

            } 
        },
        {
            "width": "12%", 
            "data": function( data ){
                return '<p style="font-size: .8em;">'+formatMoney(data.cantidad)+"</p>";

            } 
        },
        {
            "data": function( data ){
                return '<p style="font-size: .8em;">'+data.etapa+"</p>";

            } 
        },
        {
            "orderable": false,
            "data": function( data ){
                
                opciones = '<div class="btn-group-vertical" role="group">';            
                opciones += '<button type="button" class="btn btn-primary btn-sm consultar_modal notification" value="'+data.idsolicitud+'" data-value="BAS"><i class="fas fa-eye"></i>'+'</button>';
                opciones += '</div>';
                return opciones;
            }
        },
        ],
            "ajax": {
                "url": url + "Contabilidad/ver_devautorizadas", 
                "type": "POST",
                cache: false,
                "data": function( d ){
                    return  $.extend( d, { "nomdepto": $("#cxp1_sel").val(), "idempresa": $("#cxp2_sel").val() } );
                }
            }
        });



        $('#tabla_historial_autorizaciones tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = tabla_historial.row( tr );

            if ( row.child.isShown() ) {
                row.child.hide();
                tr.removeClass('shown');
                $(this).parent().find('.animacion').removeClass("fa-caret-down").addClass("fa-caret-right");
            }
            else {
                var informacion_adicional = '<table class="table text-justify">'+
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

</script>
<?php
    require ("footer.php");
?>