<?php
require("head.php");
require("menu_navegador.php");
?> 
<style>
    .encabezado_tabla:focus, .encabezado_tabla:hover {
        border-bottom: 2px solid #000000ab; 
    }
    .encabezado_tabla[placeholder]:empty:before {
        content: attr(placeholder);
    }
    .encabezado_tabla[placeholder]:empty:focus:before {
        content: "";
    }
    .encabezado_tabla {
        display: inline-block;
        cursor: text;
        font-size: .9em;
        text-align: center;
        border: none;
        outline: none;
        padding: 2px 8px;
        white-space: normal;
        overflow: visible;
        width: auto;
    }

    td p {
        text-align: center;
    }

    th {
        text-align: center;
    }

    input[type="text"]::placeholder { 
    text-overflow: ellipsis;
    white-space: nowrap;
    overflow: visible;
    width: 100%;
    display: block;
    }
    
    #tablaHistorialParcialidades>tbody:hover {
        cursor: pointer;
    }
    
    .modal-content {
        flex-grow: 1;
    }

    #modalHistorial{
        overflow-x: scroll;
    }
            .advertencia-pulso{
        animation: pulse-advertencia 1s ease-out 0.8s 8 normal forwards running;
        color: #dc3545;
        font-weight: bold;
    }

     /* Estilo base para la fila con advertencia */
    .fila-advertencia {
        background-color: #FFC2C2 !important;
        font-weight: bold;
        position: relative;
        text-align: center;
        vertical-align: middle;
        border-radius: 10%;
        animation: pulse-advertencia 1s ease-out 0.8s 8 normal forwards running;
    }

    #tablaHistorialParcialidades td {
        vertical-align: middle;
    }
    
    /* Icono de advertencia (usando FontAwesome) */
    .icono-advertencia-llamativo {
        position: absolute;
        top: -8px;
        right: -8px;
        background: red;
        color: white;
        font-size: 0.7em;
        border-radius: 50%;
        padding: 4px;
        z-index: 10;
        animation: parpadeo 1s infinite;
    }

    /* Efecto hover para mayor interactividad */
    .fila-advertencia:hover {
        background-color:rgb(255, 120, 120) !important;
        cursor: pointer;
        box-shadow: 0 0 10px rgba(255, 82, 82, 0.2);
    }
    @keyframes parpadeo {
        0% { opacity: 1; }
        50% { opacity: 0.4; }
        100% { opacity: 1; }
    }

    @keyframes pulse-advertencia {
        0% { opacity: 1; }
        50% { opacity: 0.2; }
        100% { opacity: 1; }
    }

</style>
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <div class="box">
                    <div class="box-header with-border">
                        <h3>HISTORIAL DEVOLUCIONES EN PARCIALIDADES</h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="tab-content">
                                    <div class="active tab-pane">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="col-lg-3">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="fas"></i> <b>TIPO REPORTE </b></span>
                                                        <select name="tipoReporte" class="form-control" id="tipoReporte" >
                                                            <option value="T" selected>TODOS</option>
                                                            <option value="PC">PAGO COMPLETO</option>
                                                            <option value="EC">EN CURSO</option>
                                                            <option value="CC">CANCELADAS</option>
                                                            <!-- <option value="ADV">ADVERTENCIAS</option> 🔥 NUEVA OPCIÓN -->
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="fas fa-calendar-alt"></i> <b>INICIO</b></span>
                                                        <input class="form-control fechas_filtro" type="text" id="fechaInicial" name="fechaInicial" maxlength="10"/>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="fas fa-calendar-alt"></i> <b>FIN</b></span>
                                                        <input class="form-control fechas_filtro" type="text" id="fechaFinal" name="fechaFinal" maxlength="10" />
                                                    </div>
                                                </div>
                                                <table class="table table-striped" id="tablaHistorialParcialidades" style="width: 100%;"    >
                                                    <thead>
                                                        <tr>
                                                            <th style="font-size: .8em">SOLICITUD</th>           <!-- COLUMNA 0 -->
                                                            <th style="font-size: .8em">PERIODO</th>             <!-- COLUMNA 1 -->
                                                            <th style="font-size: .8em">PROVEEDOR</th>           <!-- COLUMNA 2 -->
                                                            <th style="font-size: .9em">LOTE</th>                <!-- COLUMNA 3 -->
                                                            <th style="font-size: .8em">TOTAL</th>               <!-- COLUMNA 4 -->
                                                            <th style="font-size: .8em">MET. PAGO</th>           <!-- COLUMNA 5 -->
                                                            <th style="font-size: .8em">FEC. AUT.</th>           <!-- COLUMNA 6 -->
                                                            <th style="font-size: .8em">PARCIALIDAD</th>         <!-- COLUMNA 7 -->
                                                            <th style="font-size: .8em">PAGADO</th>              <!-- COLUMNA 8 -->                                                   
                                                            <th style="font-size: .8em">PAGO</th>                <!-- COLUMNA 9 -->
                                                            <th style="font-size: .8em">FEC. INICIO</th>         <!-- COLUMNA 10 -->
                                                            <th style="font-size: .8em">FEC. FINAL</th>          <!-- COLUMNA 11 -->
                                                            <th style="font-size: .8em">PRX FECHA</th>           <!-- COLUMNA 12 -->
                                                            <th style="font-size: .8em">ESTATUS</th>             <!-- COLUMNA 13 -->
                                                            <th></th>                                            <!-- COLUMNA 14 -->
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
    </div>
</div>
<div class="modal fade bd-example-modal-lg" id="modalHistorial" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="border-radius: 20px;">
            <div class="modal-header" style="padding-bottom: 0px;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title" id="exampleModalLongTitle"></h5>
            </div>
            <div class="row" style="padding: 4px; margin-left: 0px; margin-right: 0px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);">
                <div class="col-lg-6 text-start my-4" style="padding-left: 15px;" id="divMontoTotal">
                    <h5 class="text-muted mb-2" style="margin-bottom: 5px;">IMPORTE TOTAL A LIQUIDAR POR LA SOLICITUD:</h5>
                    <div id="montoTotal" class="display-4 fw-bold text-primary" style="font-size: 1.8rem; color: #0d6efd; margin-bottom: 5px;">
                        $0.00
                    </div>
                </div>
                <div class="col-lg-6" style="padding-right: 15px; text-align: end;" id="divNumParcialidades">
                    
                </div>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <div style="box-shadow: 0 4px 10px rgba(0, 0, 0, 0.01); text-align: left;">
                    <div id="montoTotalAutorizado" class="h4 text-dark fw-semibold" style="font-size: 1.5rem; margin-bottom: 10px; padding-bottom: 10px;">
                    </div>
                </div>
                <br>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<script>
    var titulos_encabezado = [];
    var num_colum_encabezado = [];
    var fecha_hoy = new Date();
    $(document).ready(function() {
        const fechaActual = new Date().toLocaleDateString('es-ES', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric'
        });

        // Configura el datepicker inicial
        $('#fechaInicial').datepicker({
            dateFormat: 'dd/mm/yyyy', // Formato de fecha deseado
            defaultDate: '01/01/'+new Date().getFullYear(), // Establece la fecha predeterminada
            onSelect: function(dateText) {
                $(this).val(dateText); // Establece el valor del campo de entrada con la fecha seleccionada
            }
        });
        // Asigna la fecha predeterminada al campo de entrada
        $('#fechaInicial').datepicker('setDate', '01/01/'+new Date().getFullYear());

        // Configura el datepicker inicial
        $('#fechaFinal').datepicker({
            dateFormat: 'dd/mm/yyyy', // Formato de fecha deseado
            defaultDate: fechaActual, // Establece la fecha predeterminada
            onSelect: function(dateText) {
                $(this).val(dateText); // Establece el valor del campo de entrada con la fecha seleccionada
            }
        });
        // Asigna la fecha predeterminada al campo de entrada
        $('#fechaFinal').datepicker('setDate', fechaActual);

        $('#fechaInicial').change( function() { 
            obtenerListado()
            $("#fechaInicial").datepicker("hide");
        });

        $('#fechaFinal').change( function() {
            obtenerListado()
            $("#fechaFinal").datepicker("hide");
        });
        $('#tipoReporte').change(obtenerListado);
    });
    
    $('.fechas_filtro').datepicker({
        format: 'dd/mm/yyyy',
        endDate: '-0d'
    });
    
    $('#fechaInicial').on('keyup change', function(){
        var str = $(this).val();
        if(str.length==2 || str.length==5){
            $('#fechaInicial').val(str+'/');
        }
    }); 
    
    $('#fechaFinal').on('keyup change', function(){
        var str = $(this).val();
        if(str.length==2 || str.length==5){
            $('#fechaFinal').val(str+'/');
        }
    }); 


    $("#tablaHistorialParcialidades").ready(function() {
        $('#tablaHistorialParcialidades thead tr:eq(0) th').each(function(i) {
            if (i < $('#tablaHistorialParcialidades thead tr:eq(0) th').length - 1) {
                var title = $(this).text();
                titulos_encabezado.push(title);
                num_colum_encabezado.push(i);
                
                $(this).html('<input type="text" class="encabezado_tabla" style="font-size: .9em; width: 100%;" placeholder="'+title+'" title="'+title+'" />')
                
                $(this).children('input').eq(0).on('keyup change', function() {
                    if (tabla_parcialidades.column(i).search() !== $(this).val().trim()) {
                        tabla_parcialidades
                            .column(i)
                            .search($(this).val())
                            .draw();
                        var index = tabla_parcialidades.rows({
                            selected: true,
                            search: 'applied'
                        }).indexes();
                    }
                    
                });
            }
        });

        tabla_parcialidades = $('#tablaHistorialParcialidades').DataTable({
        dom: 'Brtip',
        buttons: [
            {
                extend: 'excelHtml5',
                text: '<i class="fas fa-file-excel"></i> EXPORTAR',
                messageTop: "DEVOLUCIONES EN PARCIALIDADES",
                attr: {
                    class: 'btn btn-success'
                },
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13],
                    format: {
                        header: function (data, columnIdx) { 
                            return " " + titulos_encabezado[columnIdx] + " ";
                        }
                    }
                }
            },
            {
                text: '<i class="fas fa-file-excel"></i> DESGLOSADO',
                attr: {
                    class: 'btn btn-warning'       
                },
                action: function(){
                    dataReporte = {
                        fechaInicial: new Date($('#fechaInicial').val()).toJSON(),
                        fechaFinal: new Date($('#fechaFinal').val()).toJSON(),
                        tipoReporte: $('#tipoReporte').val().toString()
                    }
                    var reporte = 'Devoluciones_Traspasos/historialParcialidadesDesglosado/' + JSON.stringify(dataReporte);

                    window.open(url + reporte, '_self');
                }
            },
            {
                text: '<i class="fas fa-file-excel"></i> PAGOS',
                attr: {
                    class: 'btn btn-secondary'       
                },
                action: function(){
                    dataReporte = {
                        fechaInicial: new Date($('#fechaInicial').val()).toJSON(),
                        fechaFinal: new Date($('#fechaFinal').val()).toJSON(),
                        tipoReporte: $('#tipoReporte').val().toString()
                    }
                    var reporte = 'Devoluciones_Traspasos/historialParcialidadesDesglosadoPagos/' + JSON.stringify(dataReporte);

                    window.open(url + reporte, '_self');
                }
            }
        ],
        "language":lenguaje,
        "processing": false,
        "pageLength": 10,
        "bAutoWidth": false,
        "bLengthChange": false,
        "scrollX": true,
        "bInfo": false,
        "ordering":true,
        "searching": true,
        "deferRender": true,
        "drawCallback": function(settings) {
            $('#tablaHistorialParcialidades tbody tr').on('dblclick', function(event) {
                if($(event.target).is('button') || $(event.target).is('i')) return;
                var data = tabla_parcialidades.row(this).data();
                obtenerHistorialPagos(data)

            });
        },
        "columns": [{
                    "width": "7%",
                    "data": function(d) {
                        return '<p style="font-size: .8em">' + d.idsolicitud + '</p>'                   //Columna 0
                    }
                },
                {
                    "width": "7%",
                    "data": function(d) {
                        return '<p style="font-size: .8em">' + d.periodo + '</p>'                       //Columna 1
                    }
                },
                {
                    "width": "15%",
                    "data": function(d) {
                        return '<p style="font-size: .8em">' + (d.proveedor ? d.proveedor : '') + '</p>'                       //Columna 2
                    }
                },
                {
                    "width": "15%",
                    "data": function(d) {
                        return '<p style="font-size: .8em">' + (d.lote ? d.lote : '') + '</p>'                       //Columna 3
                    }
                },
                {
                    "width": "7%",
                    "data": function(d) {
                        return '<p style="font-size: .8em">$' + formatMoney(d.cantidad) + '</p>'                      //Columna 4
                    }
                },
                {
                    "width": "7%",
                    "data": function(d) {
                        return '<p style="font-size: .7em">' + d.metoPago + '</p>'                      //Columna 5
                    }
                },
                {
                    "width": "7%",
                    "data": function(d) {
                        return '<p style="font-size: .8em">' + formato_fechaymd(d.fecha_autorizacion) + '</p>'            //Columna 6
                    }
                },
                {
                    "width": "8%",
                    "data": function(d) {
                        return '<p style="font-size: .8em">$ ' + d.parcialidad+'</p>';                  //Columna 7
                    }
                },

                {
                    "width": "7%",
                    "data": function(d) {
                        return '<p style="font-size: .8em">$' + formatMoney(d.pagado) + '</p>'                        //Columna 8
                    }
                },
                {
                    "width": "7%",
                    "data": function(d) {
                        return '<p style="font-size: .8em">' + d.pagos+' / <small class="label pull-center bg-orange">'+d.totalPagos+ '</p>'        //Columna 9
                    }
                },
                {
                    "width": "7%",
                    "data": function(d) {
                        return '<p style="font-size: .8em">' + formato_fechaymd(d.fechaInicio) + '</p>'                   //Columna 10
                    }
                },
                {
                    "width": "7%",
                    "data": function(d) {
                        return '<p style="font-size: .8em">' + formato_fechaymd(d.fechaFin) + '</p>'                    //Columna11
                    }
                },
                {
                    "width": "7%",
                    "data": function(d) {
                        return '<p style="font-size: .8em">' + ( d.proximo_pago== '-'  ? d.proximo_pago : formato_fechaymd(d.proximo_pago)) + '</p>'       //Columna 12
                    }
                },
               {   //Columna 13
                    "width": "7%",
                    "data": function(d) {
                        let dias_diferencia = 0;
                        $estatus_sol = '<p style="font-size: .8em">' + d.etapa + '</p>'
                        if (d.idetapa == 11) {
                            return $estatus_sol + "<small class='label pull-center bg-green'>PAGO COMPLETO</small>"
                        }
                        if (d.idetapa == 0) {
                                return $estatus_sol + "<small class='label pull-center bg-red'>PAGO ELIMINADO</small>"
                        }
                        if (d.idetapa == 30) {
                            return $estatus_sol + "<small class='label pull-center bg-red'>PAGO CANCELADO</small>"
                        }
                        dias_diferencia = moment(d.proximo_pago).startOf('day').diff(moment(fecha_hoy).startOf('day'), 'days');
                        if( dias_diferencia < 0 && d.estatus_ultimo_pago == null ){
                            return $estatus_sol + "<small class='label pull-center bg-red'>VENCIDO POR "+ ( dias_diferencia < 0 ? -1 * dias_diferencia : dias_diferencia )+" DIAS</small>";
                        }else if(dias_diferencia == 0 && d.estatus_ultimo_pago == null){
                            return $estatus_sol + "<small class='label pull-center bg-yellow'>VENCE EL DÍA DE HOY "+ ( dias_diferencia < 0 ? -1 * dias_diferencia : dias_diferencia )+" DIAS</small>";
                        }else if( dias_diferencia < 0 ){
                            switch( d.estatus_ultimo_pago ){
                                case "15":
                                    return $estatus_sol +  "<small class='label pull-center bg-orange'>VENCIDO | POR CONFIRMAR PAGO</small>";
                                    break; 
                                case "1":
                                    return $estatus_sol + "<small class='label pull-center bg-orange'>VENCIDO | POR DISPERSAR</small>";
                                    break;
                                case "11":
                                case "0":
                                    return $estatus_sol + "<small class='label pull-center bg-orange'>VENCIDO | SUBIENDO PAGO</small>";
                                    break; 
                                default:
                                    return $estatus_sol + "<small class='label pull-center bg-orange'>VENCIDO | PAGO DETENIDO</small>";
                                    break;
                            }
                        }else{
                            return $estatus_sol + "<small class='label pull-center bg-green'>EN TIEMPO</small>"
                        }
                    }
                },
                {
                    "orderable": false,
                    "data": function(d) {
                        return '<div class="btn-group-vertical"><button type="button" class="btn btn-sm btn-primary consultar_modal notification" value="' + d.idsolicitud + '" data-value="DEV_BASICA"><i class="fas fa-eye"></i>' + (d.visto == 0 ? '<span class="badge">!</span>' : '') + '</button></div>';
                    }
                }
            ],
            "createdRow": function(row, data, dataIndex){
                if (data.idproceso == "30") {
                    let planPagos = MyLib.montoTotalSolicitudParcialidad(data.cantidad, data.totalPagos, data.parcialidad, data.programado, moment("'"+data.fecelab+"'", 'YYYY-MM-DD').format('YYYY-MM-DD') );
                
                    let pagoParaAutorizarActual;
                    let ultimoPagoSolicitud;

                    if (!data.numPagosAutorizados) {
                        pagoParaAutorizarActual = 1;
                    }else{
                        pagoParaAutorizarActual = data.pagoPlanPagos;
                    }

                    ultimoPagoSolicitud = data.ultimoPagoParcialidades;
                    // if (data.idetapa == 11) {
                    //     ultimoPagoSolicitud = data.ultimoPagoAut;
                    // }else{
                    //     ultimoPagoSolicitud = data.ultimoPagoParcialidades;
                    // }

                    if( ( parseFloat(data.totalPagos) <= 0) || 
                        ( parseFloat(data.totalPagos) == 1 && parseFloat(data.cantidad) <= parseFloat(ultimoPagoSolicitud) ) || 
                        ( parseFloat(data.cantidad) < parseFloat(planPagos.montoTotalPagar) ) ||
                        ( parseFloat(data.cantidad) > parseFloat(planPagos.montoTotalPagar) ) ||
                        ( parseFloat(data.cantidad) < parseFloat(data.totalAutorizado) ) || 
                        ( parseFloat(data.cantidad) <= 0 || parseFloat(planPagos.montoTotalPagar) <= 0 ) ||
                        ( parseFloat(data.numPagosAutorizados) == parseFloat(data.totalPagos) && parseFloat(data.totalAutorizado) < parseFloat(data.cantidad) ) ||
                        ( parseFloat(data.numPagosAutorizados) == parseFloat(data.totalPagos) && parseFloat(data.totalAutorizado) > parseFloat(data.cantidad) ) ||
                        ( parseFloat(ultimoPagoSolicitud) != parseFloat(planPagos.tabla_pagos[pagoParaAutorizarActual]) ) ||
                        ( parseFloat(ultimoPagoSolicitud) != planPagos.tabla_pagos[pagoParaAutorizarActual]) ){
                        
                        // Tooltip para mejor UX (requiere jQuery UI o Bootstrap)
                        $(row).find('.icono-advertencia').tooltip({
                            placement: 'right'
                        });
                        if (data.totalPagos <= 0) {
                            $(row).addClass('fila-advertencia');
                            $(row).find('.avanzar, .avanzar_contable').attr('disabled', true);
                            $(row).attr('title','Advertencia: Favor de revisar la solicitud, ya que aparentemente no cuenta con un número de pagos definido.');
                            $(row).find('.consultar_modal i')
                                .prepend(`<span class="icono-advertencia-llamativo" 
                                                title="Advertencia: Favor de revisar la solicitud, ya que aparentemente no cuenta con un número de pagos definido.">
                                            <i class="fas fa-exclamation-triangle"></i>
                                          </span>`);
                            // Alternativa más robusta para el filtrado
                            if ($(row).hasClass('fila-advertencia')) {
                                $(row).data('tiene-advertencia', true);
                            }
                        }else if (data.totalPagos == 1 && parseFloat(data.cantidad) < parseFloat(ultimoPagoSolicitud)) {
                            console.log(data.idsolicitud);
                            console.log(parseFloat(data.cantidad));
                            console.log(parseFloat(ultimoPagoSolicitud));
                            
                            $(row).addClass('fila-advertencia');
                            $(row).find('.avanzar, .avanzar_contable').attr('disabled', true);
                            $(row).attr('title','Advertencia: Favor de revisar la solicitud. Se indica pago en parcialidades, pero se programó en una sola exhibición y el monto de la parcialidad excede el importe registrado a devolver.');
                            $(row).find('.consultar_modal i')
                                .prepend(`<span class="icono-advertencia-llamativo" 
                                                title="Advertencia: Favor de revisar la solicitud. Se indica pago en parcialidades, pero se programó en una sola exhibición y el monto de la parcialidad excede el importe registrado a devolver.">
                                            <i class="fas fa-exclamation-triangle"></i>
                                          </span>`);
                            // Alternativa más robusta para el filtrado
                            if ($(row).hasClass('fila-advertencia')) {
                                $(row).data('tiene-advertencia', true);
                            }
                        }else if( parseFloat(data.cantidad) < parseFloat(planPagos.montoTotalPagar) ){
                            $(row).addClass('fila-advertencia');
                            $(row).find('.avanzar, .avanzar_contable').attr('disabled', true);
                            $(row).attr('title','Advertencia: Favor de revisar la solicitud. El monto calculado según las parcialidades excede el total registrado a devolver.');
                            $(row).find('.consultar_modal i')
                                .prepend(`<span class="icono-advertencia-llamativo" 
                                                title="Advertencia: Favor de revisar la solicitud. El monto calculado según las parcialidades excede el total registrado a devolver.">
                                            <i class="fas fa-exclamation-triangle"></i>
                                          </span>`);
                        }else if (parseFloat(data.cantidad) > parseFloat(planPagos.montoTotalPagar)) {
                            $(row).addClass('fila-advertencia');
                            $(row).find('.avanzar, .avanzar_contable').attr('disabled', true);
                            $(row).attr('title','Advertencia: Revisar el importe total a devolver, ya que excede el monto programado en el plan de pagos.');
                            $(row).find('.consultar_modal i')
                                .prepend(`<span class="icono-advertencia-llamativo" 
                                                title="Advertencia: Revisar el importe total a devolver, ya que excede el monto programado en el plan de pagos.">
                                            <i class="fas fa-exclamation-triangle"></i>
                                          </span>`);
                            // Alternativa más robusta para el filtrado
                            if ($(row).hasClass('fila-advertencia')) {
                                $(row).data('tiene-advertencia', true);
                            }
                        }else if (parseFloat(data.cantidad) < parseFloat(data.totalAutorizado)) {
                            $(row).addClass('fila-advertencia');
                            $(row).find('.avanzar, .avanzar_contable').attr('disabled', true);
                            $(row).attr('title','Advertencia: Revisar los pagos autorizados hasta la fecha, ya que su suma superan el importe total de la solicitud.');
                            $(row).find('.consultar_modal i')
                                .prepend(`<span class="icono-advertencia-llamativo" 
                                                title="Advertencia: Revisar los pagos autorizados hasta la fecha, ya que su suma superan el importe total de la solicitud.">
                                            <i class="fas fa-exclamation-triangle"></i>
                                          </span>`);
                            // Alternativa más robusta para el filtrado
                            if ($(row).hasClass('fila-advertencia')) {
                                $(row).data('tiene-advertencia', true);
                            }
                        }else if (parseFloat(data.cantidad) <= 0 || parseFloat(planPagos.montoTotalPagar) <= 0) {
                            $(row).addClass('fila-advertencia');
                            $(row).find('.avanzar, .avanzar_contable').attr('disabled', true);
                            $(row).attr('title','Advertencia: Revisar el importe total de la solicitud y/o el plan de pagos, ya que alguno de los valores parece estar en cero o en negativo.');
                            $(row).find('.consultar_modal i')
                                .prepend(`<span class="icono-advertencia-llamativo" 
                                                title="Advertencia: Revisar el importe total de la solicitud y/o el plan de pagos, ya que alguno de los valores parece estar en cero o en negativo.">
                                            <i class="fas fa-exclamation-triangle"></i>
                                          </span>`);
                            // Alternativa más robusta para el filtrado
                            if ($(row).hasClass('fila-advertencia')) {
                                $(row).data('tiene-advertencia', true);
                            }
                        }else if (data.numPagosAutorizados == data.totalPagos && data.totalAutorizado < data.cantidad) {
                            $(row).addClass('fila-advertencia');
                            $(row).find('.avanzar, .avanzar_contable').attr('disabled', true);
                            $(row).attr('title','Advertencia: Revisar pagos autorizados; aunque el número coincide con el plan de pagos, la suma total es inferior al importe de la solicitud.');
                            $(row).find('.consultar_modal i')
                                .prepend(`<span class="icono-advertencia-llamativo" 
                                                title="Advertencia: Revisar pagos autorizados; aunque el número coincide con el plan de pagos, la suma total es inferior al importe de la solicitud.">
                                            <i class="fas fa-exclamation-triangle"></i>
                                          </span>`);
                            // Alternativa más robusta para el filtrado
                            if ($(row).hasClass('fila-advertencia')) {
                                $(row).data('tiene-advertencia', true);
                            }
                        }else if (data.numPagosAutorizados == data.totalPagos && data.totalAutorizado > data.cantidad) {
                            $(row).addClass('fila-advertencia');
                            $(row).find('.avanzar, .avanzar_contable').attr('disabled', true);
                            $(row).attr('title','Advertencia: Revisar pagos autorizados; aunque el número coincide con el plan de pagos, la suma total autorizada excede el importe de la solicitud.');
                            $(row).find('.consultar_modal i')
                                .prepend(`<span class="icono-advertencia-llamativo" 
                                                title="Advertencia: Revisar pagos autorizados; aunque el número coincide con el plan de pagos, la suma total autorizada excede el importe de la solicitud.">
                                            <i class="fas fa-exclamation-triangle"></i>
                                          </span>`);
                            // Alternativa más robusta para el filtrado
                            if ($(row).hasClass('fila-advertencia')) {
                                $(row).data('tiene-advertencia', true);
                            }
                        }else if( parseFloat(ultimoPagoSolicitud) != parseFloat(planPagos.tabla_pagos[pagoParaAutorizarActual].includes(',') ? planPagos.tabla_pagos[pagoParaAutorizarActual].replace(/,/g, '') : parseFloat(planPagos.tabla_pagos[pagoParaAutorizarActual]) ) ){
                            
                            console.log(data.idsolicitud);
                            console.log(parseFloat(ultimoPagoSolicitud));
                            console.log(planPagos.tabla_pagos[pagoParaAutorizarActual]);
                            console.log(pagoParaAutorizarActual);
                            console.log(planPagos);
                            
                            $(row).addClass('fila-advertencia');
                            $(row).find('.avanzar, .avanzar_contable').attr('disabled', true);
                            $(row).attr('title','Advertencia: Favor de revisar la solicitud. El monto a autorizar difiere del indicado en el plan de pagos.');
                            $(row).find('.consultar_modal i')
                                .prepend(`<span class="icono-advertencia-llamativo" 
                                                title="Advertencia: Favor de revisar la solicitud. El monto a autorizar difiere del indicado en el plan de pagos.">
                                            <i class="fas fa-exclamation-triangle"></i>
                                          </span>`);
                            // Alternativa más robusta para el filtrado
                            if ($(row).hasClass('fila-advertencia')) {
                                $(row).data('tiene-advertencia', true);
                            }
                        }else if( ultimoPagoSolicitud && (parseFloat(ultimoPagoSolicitud) != parseFloat(planPagos.tabla_pagos[pagoParaAutorizarActual].includes(',') ? planPagos.tabla_pagos[pagoParaAutorizarActual].replace(/,/g, '') : parseFloat(planPagos.tabla_pagos[pagoParaAutorizarActual]) )) ) {
                            $(row).addClass('fila-advertencia');
                            $(row).find('.avanzar, .avanzar_contable').attr('disabled', true);
                            $(row).attr('title','Advertencia: Favor de revisar el último pago autorizado, ya que no coincide con el monto correspondiente según el plan de pagos.');
                            $(row).find('.consultar_modal i')
                                .prepend(`<span class="icono-advertencia-llamativo" 
                                                title="Advertencia: Favor de revisar el último pago autorizado, ya que no coincide con el monto correspondiente según el plan de pagos.">
                                            <i class="fas fa-exclamation-triangle"></i>
                                          </span>`);
                            // Alternativa más robusta para el filtrado
                            if ($(row).hasClass('fila-advertencia')) {
                                $(row).data('tiene-advertencia', true);
                            }
                        }
                        
                    }
                }
            },
        ajax: url + "Devoluciones_Traspasos/obtenerHistrialParcialidades",
            
            
        });
    });

    function obtenerListado(){
        let fechaInicio = $("#fechaInicial").val();
        let fechaFin = $("#fechaFinal").val();
        let tipoReporte = $("#tipoReporte").val();
        if (tipoReporte === 'ADV') {
            tabla_parcialidades.rows().every(function() {
                let row = $(this.node());
                if (row.hasClass('fila-advertencia')) {
                    row.show();
                } else {
                    row.hide();
                }
            });
            return; // ✅ Salimos para que no se haga AJAX
        }
        
        // Limpiar filtros anteriores si no es ADV
        $('tr.hidden-by-filter').removeClass('hidden-by-filter').show();
        $.ajax({
            url: url + 'Devoluciones_Traspasos/obtenerHistrialParcialidades', // Ruta al servidor o servicio para obtener datos
            type: 'POST',
            data: {
                fechaInicial: fechaInicio,
                fechaFinal: fechaFin,
                tipoReporte: tipoReporte
            }, // Enviar la nueva fecha al servidor
            success: function(result) {
                // Actualizar la DataTable con los nuevos datos
                resultado = JSON.parse(result);
                tabla_parcialidades.clear().draw();
                tabla_parcialidades.rows.add(resultado.data);
                tabla_parcialidades.columns.adjust().draw();
                
                
                var total = 0;
                var index = tabla_parcialidades.rows( { selected: true, search: 'applied' } ).indexes();
                var data = tabla_parcialidades.rows( index ).data();
            }
        });
    }

    function obtenerHistorialPagos(data){
        const montoTotalLiquidar = new Intl.NumberFormat('es-MX', {
            style: 'currency',
            currency: 'MXN',
            minimumFractionDigits: 2
        }).format(data.cantidad);
        let totalAutorizado = 0;
        let diferenciaAutTotal
        $.ajax({
            url: url + 'Devoluciones_Traspasos/obtenerHistorialPagos',
            type: 'POST',
            data: {
                idsolicitud: data.idsolicitud
            }, 
            success: function(result) {
                result = JSON.parse(result);
                $('#modalHistorial').find('#exampleModalLongTitle').text('');
                $('#modalHistorial').find('#exampleModalLongTitle').append(`<p style="font-size: 20px;">HISTORIAL DE PAGOS DE LA SOLICITUD: <b>${data.idsolicitud}<b></p>`);
                $('#divMontoTotal #montoTotal').html(`<b>${montoTotalLiquidar}<b>`);
                $('#divNumParcialidades')
                    .html(` <h6 class="text-muted mb-2 " style="margin-bottom: 5px; display: inline;">
                                TOTAL DE PARCIALIDADES PROGRAMADAS: 
                            </h6>
                            <span class="display-4 fw-bold text-primary" style="font-size: 1.8rem; color: #0d6efd; margin-bottom: 5px;">
                                <b>${data.totalPagos}<b>
                            </span><br>
                            <h6 class="text-muted mb-2 " style="margin-bottom: 5px; display: inline;">
                                TOTAL DE PARCIALIDADES AUTORIZADAS: 
                            </h6>
                            <span class="display-4 fw-bold text-primary" style="font-size: 1.8rem; color: #0d6efd; margin-bottom: 5px;">
                                <b>${data.numPagosAutorizados}<b>
                            </span><br>
                            <h6 class="text-muted mb-2 " style="margin-bottom: 5px; display: inline;">
                                TOTAL DE PARCIALIDADES PAGADAS: 
                            </h6>
                            <span class="display-4 fw-bold text-primary" style="font-size: 1.8rem; color: #0d6efd; margin-bottom: 5px;">
                                <b>${data.pagos}<b>
                            </span>`)
                var modalBody = $('#modalHistorial').find('.modal-body');
                modalBody.text('');

                if(result.data.length > 0){
                    var tabla = $(`<table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">PAGO</th>
                                    <th scope="col">CANTIDAD</th>
                                    <th scope="col">FECHA PV</th>
                                    <th scope="col">FECHA PAGO</th>
                                    <th scope="col">FECHA OPERACIÓN</td>
                                    <th scope="col">TIPO PAGO</td>
                                </tr>
                            </thead>
                            <tbody>
                            
                            </tbody>
                        </table>`);
                    
                    result.data.forEach(function(row, i){
                        tr = `<tr>
                                <th scope="row">${i+1}</th>
                                <td><p>${row.idpago}</p></td>
                                <td><p>$${formatMoney(row.cantidad)}</td>
                                <td><p>${formato_fechaymd(row.fechaPV)}</p></td>
                                <td><p>${formato_fechaymd(row.fechaPago)}</p></td>
                                <td><p>${formato_fechaymd(row.fechaOperacion)}</p></td>
                                <td><p>${row.tipoPago ? row.tipoPago : '-'}</p></td>
                            </tr>`
                        tabla.children('tbody').append(tr);
                        if(formato_fechaymd(row.fechaPV) != '00/00/0000'){
                            totalAutorizado += parseFloat(row.cantidad);
                        }
                    });
                    modalBody.append(tabla);
                }
                else{
                    modalBody.append('<p class="text-center">NO HAY REGISTRO DE PAGOS.</p>');
                }
                $('#montoTotalAutorizado').html(`<span style='color: #198754;' class=' text-muted mb-2'>IMPORTE TOTAL AUTORIZADO: <b>${new Intl.NumberFormat('es-MX',{style:'currency', currency:'MXN', minimumFractionDigits:2}).format(totalAutorizado)}</b></span><br>`);
                diferenciaAutTotal = parseFloat(data.cantidad) - parseFloat(totalAutorizado.toFixed(2));

                if (parseFloat(data.cantidad) >= parseFloat(totalAutorizado.toFixed(2))) {
                    $('#montoTotalAutorizado').append(`<br><span style='color: #fd7e14;' class=' text-muted mb-2'>SALDO PENDIENTE DE AUTORIZACIÓN: <b>${new Intl.NumberFormat('es-MX',{style:'currency', currency:'MXN', minimumFractionDigits:2}).format(diferenciaAutTotal)}<b>`);
                }
                else if (parseFloat(data.cantidad) < parseFloat(totalAutorizado.toFixed(2))) {
                    $('#montoTotalAutorizado').append(`<br><span class='advertencia-pulso text-muted mb-2'>¡ADVERTENCIA! SE HA AUTORIZADO UN MONTO EXCEDENTE POR: <b>${new Intl.NumberFormat('es-MX',{style:'currency', currency:'MXN', minimumFractionDigits:2}).format(diferenciaAutTotal)}<b>`);
                }
                $('#modalHistorial').modal('show');
            }
        });

    }
    
</script>
<?php
require("footer.php");
?>