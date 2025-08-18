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
                        <h3>HISTORIAL SOLICITUDES</h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="tab-content">
                                    <div class="active tab-pane" id="listado_solicitudes">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="col-lg-3">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="fas fa-calendar-alt"></i> <b>INICIO</b></span>
                                                        <input class="form-control fechas_filtro from" type="text" id="datepicker_from" maxlength="10"/>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="fas fa-calendar-alt"></i> <b>FIN</b></span>
                                                        <input class="form-control fechas_filtro to" type="text" id="datepicker_to" maxlength="10" />
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12"> <!-- INICIO FECHA: 07-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <div class="input-group-addon bg-gray text-bold">TOTAL: $</div>
                                                            <input class="form-control text-bold" style="background-color: white; font-size: 16px; cursor: default;" disabled="disabled" readonly="readonly" type="text"  name="myText_1" id="myText_1">
                                                        </div>
                                                    </div>
                                                </div>  <!-- FIN FECHA: 07-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                                                <table class="table table-striped" id="tablahistorialsolicitudes">
                                                    <thead>
                                                        <tr>
                                                            <th></th>
                                                            <th style="font-size: .9em">#</th>
                                                            <th style="font-size: .9em">EMPRESA</th>
                                                            <th style="font-size: .9em">PROYECTO</th>
                                                            <th style="font-size: .9em">FOLIO</th>
                                                            <th style="font-size: .9em">FECHA CAPTURA</th>
                                                            <th style="font-size: .9em">FECHA AUTORIZACIÓN</th>
                                                            <th style="font-size: .9em">FECHA FACT</th>
                                                            <th style="font-size: .9em">PROVEEDOR</th>                                                           
                                                            <th style="font-size: .9em">DEPARTAMENTO</th>
                                                            <th style="font-size: .9em">CANTIDAD</th>
                                                            <th style="font-size: .9em">JUSTIFICACION</th>
                                                            <th style="font-size: .9em">FECHA PAGO AUT</th>
                                                            <th style="font-size: .9em">FECHA DISPERSIÓN</th>
                                                            <th style="font-size: .9em">PAGADO</th>
                                                            <th style="font-size: .9em">SALDO</th>
                                                            <th style="font-size: .9em">ESTATUS</th>
                                                            <th style="font-size: .9em">TIPO</th>
                                                            <th style="font-size: .9em">FORMA PAGO</th>
                                                            <th style="font-size: .9em">RESPONSABLE</th>
                                                            <th style="font-size: .9em">CAPTURISTA</th>
                                                            <th style="font-size: .9em">FACTURA</th>
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
    </div>
</div>
<script>

    var tabla_historial_solicitudes;
    var no_espera = ['TRASPASO','DEVOLUCIONES','CESION OOAM','RESCISION OOAM','DEVOLUCION OOAM','TRASPASO OOAM','DEVOLUCION DOM OOAM','INFORMATIVA','INFORMATIVA CERO'];
    
    $(document).ready(function() {
        const fechaActual = new Date().toLocaleDateString('es-ES', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric'
        });

        // Configura el datepicker inicial
        $('#datepicker_from').datepicker({
            dateFormat: 'dd/mm/yyyy', // Formato de fecha deseado
            defaultDate: '01/01/'+new Date().getFullYear(), // Establece la fecha predeterminada
            onSelect: function(dateText) {
                $(this).val(dateText); // Establece el valor del campo de entrada con la fecha seleccionada
            }
        });
        // Asigna la fecha predeterminada al campo de entrada
        $('#datepicker_from').datepicker('setDate', '01/01/'+new Date().getFullYear());

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
    });
    
    $('.fechas_filtro').datepicker({
        format: 'dd/mm/yyyy',
        endDate: '-0d'
    });
    
    $('#datepicker_from').on('keyup change', function(){
        var str = $(this).val();
        if(str.length==2 || str.length==5){
            $('#datepicker_from').val(str+'/');
        }
    }); 
    
    $('#datepicker_to').on('keyup change', function(){
        var str = $(this).val();
        if(str.length==2 || str.length==5){
            $('#datepicker_to').val(str+'/');
        }
    }); 


    $("#tablahistorialsolicitudes").ready( function(){
        $('#tablahistorialsolicitudes thead tr:eq(0) th').each( function (i) {
            if( i != 0 && i != 22 ){
                var title = $(this).text();
                $(this).html( '<input type="text" class="form-control" style="font-size: .9em; width: 100%;" placeholder="'+title+'" />' );

                $( 'input', this ).on('keyup change', function () {
                    if (tabla_historial_solicitudes.column(i).search() !== this.value ) {
                        tabla_historial_solicitudes
                        .column(i)
                        .search( this.value)
                        .draw();

                        var total = 0;
                        var index = tabla_historial_solicitudes.rows( { selected: true, search: 'applied' } ).indexes();
                        var data = tabla_historial_solicitudes.rows( index ).data();
                        $.each(data, function(i, v){
                           total += parseFloat(v.mpagar);
                       });
                        var to1 = formatMoney(total);
                        document.getElementById("myText_1").value = to1;
                    }
                });
            }
        });
        
        $('#tablahistorialsolicitudes').on('xhr.dt', function ( e, settings, json, xhr ) {
            
            if( !['CX'].includes( json.rol ) ){
                tabla_historial_solicitudes.button( '1' ).remove();
            }
            
            var total = 0;
            $.each( json.data,  function(i, v){
                total += parseFloat(v.mpagar);
            });
            var to = formatMoney(total);
            document.getElementById("myText_1").value = to;
        });

        tabla_historial_solicitudes = $("#tablahistorialsolicitudes").DataTable({
            dom: 'Brtip',
            "buttons": [
                {
                    extend: 'excel',             
                    text: '<i class="fas fa-file-excel"></i> EXPORTAR EXCEL',
                    messageTop: "HISTORIAL DE SOLICITUDES SISTEMA CUENTAS POR PAGAR",
                    attr: {
                        class: 'btn btn-success'       
                    },
                    exportOptions: {
                        format:{    
                            header:  function (data, columnIdx) {

                                data = data.replace( '<input type="text" class="form-control" style="font-size: .9em; width: 100%;" placeholder="', '' ); /** FECHA: 07-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                                data = data.replace( '" />', '' );
                                data = data.replace( '">', '' );
                                return data;
                            }
                        }
                    }
                },
                {
                    text: '<i class="fas fa-file-excel"></i> PAGOS EN ESPERA DE PAGO',
                    action: function(){
                        window.open( url + "Historial/generar_documento_relacion", "_blank")
                    },
                    attr: {
                        class: 'btn btn-danger'
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
            "searching": true,
            "deferRender": true,
            "order": [[ 1, 'asc' ]], /** FECHA: 31-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
            "columns": [
                {
                    "className": 'details-control',
                    "orderable": false,
                    "data" : null,
                    "defaultContent": '<i class="fas animacion fa-caret-right"></i>'
                },
                {
                    "orderable": true, /** FECHA: 31-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                    "width": "8%",
                    "data" : function( d ){
                        return '<p style="font-size: .7em">'+(d.idsolicitud ? d.idsolicitud : 'NA')+'</p>'
                    }
                },
                {
                    "orderable": false,
                    "width": "8%",
                    "data" : function( d ){
                        return '<p style="font-size: .7em">'+d.abrev+'</p>'
                    }
                },
                {
                    "orderable": false,
                    "width": "9%",
                    "data" : function( d ){
                        return '<p style="font-size: .7em">'+d.proyecto+'</p>'
                    }
                },
                {
                    "orderable": false,
                    "width": "8%",
                    "data" : function( d ){
                        return '<p style="font-size: .7em">'+(d.folio ? d.folio : "SF")+'</p>'
                    }
                },
                {
                    "orderable": false,
                    "width": "10%",
                    "data" : function( d ){
                        return '<p style="font-size: .7em">'+formato_fechaymd(d.feccrea)+'</p>'
                    }
                },
                {
                    "orderable": false,
                    "width": "10%",
                    "data" : function( d ){
                        return '<p style="font-size: .7em">'+( d.fecha_autorizacion ? formato_fechaymd(d.fecha_autorizacion) : "-" )+'</p>'
                    }
                },
                {
                    "orderable": false,
                    "width": "10%",
                    "data" : function( d ){
                        return '<p style="font-size: .7em">'+( d.fecelab ? formato_fechaymd(d.fecelab) : "-" )+'</p>'
                    }
                },
                {
                    "orderable": false,
                    "width": "9%",
                    "data" : function( d ){
                        return '<p style="font-size: .7em">'+d.nombre+'</p>'
                    }
                },
                {
                    "orderable": false,
                    "width": "9%",
                    "data" : function( d ){
                        return '<p style="font-size: .7em">'+d.nomdepto+'</p>'
                    }
                },
                {
                    "orderable": false,
                    "width": "10%",
                    "data": function( d ){
                        return '<p style="font-size: .7em">'+formatMoney( d.mpagar )+'</p>';
                    }
                },
                {
                    "orderable": false,
                    "data": function( d ){
                        return '<p style="font-size: .7m">'+d.justificacion+'</p>';
                    }
                },
                {
                    "orderable": false,
                    "data": function( d ){
                        return '<p style="font-size: .7em">'+(d.fautorizado ?  formato_fechaymd(d.fautorizado)  : '-' )+'</p>';
                    }
                },
                {
                    "orderable": false,
                    "data": function( d ){
                        return '<p style="font-size: .7em">'+(d.fechaDis2 ?  formato_fechaymd(d.fechaDis2)  : '-' )+'</p>';
                    }
                },
                {
                    "orderable": false,
                    "width": "7%",
                    "data": function( d ){
                        return '<p style="font-size: .7em">'+formatMoney( d.pagado )+'</p>';
                    }
                },
                    {
                    "width": "8%",
                    "orderable": false,
                    "data": function( d ){
                        return '<p style="font-size: .7em">'+formatMoney( d.mpagar - d.pagado )+'</p>';
                    }
                },
                {
                    "orderable": false,
                    "width": "7%",
                    "data" : function( d ){

                        switch(d.estatus_pago){
                            case '0':
                                return '<p style="font-size: .7em">PAGO AUTORIZADO DG</p>';
                                break;
                            case '1':
                                return '<p style="font-size: .7em">POR DISPERSAR</p>';
                                break;
                            case '2':
                                return '<p style="font-size: .7em">SE HA PAUSADO EL PROCESO DE ESTE PAGO</p>';
                                break;
                            case '5':
                                return '<p style="font-size: .7em">POR DISPERSAR</p>';
                                break;
                            case '12':
                                return '<p style="font-size: .7em">PAGO DETENIDO</p>';
                                break;
                            case '15':
                                return '<p style="font-size: .7em">'+(d.metoPago=='ECHQ'?'CHEQUE SIN COBRAR':'POR CONFIRMAR PAGO CXP')+'</p>';
                                break;
                            case '16':
                                if(d.estatus_pago && d.estatus_pago == '16' && (d.etapa=='Pago Aut. por DG, Factura Pendiente'||d.etapa=='Pagado')){ 
                                    return '<p style="font-size: .7em">PAGO COMPLETO</p>' ;
                                }
                                else{
                                    return '<p style="font-size: .7em">'+d.etapa+'</p>';
                                }
                                break;
                            default:
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
                                    return '<p style="font-size: .7em">Próxima revisión de Cuentas por Pagar</p>' + 
                                    '<p style="font-size: .7em">'+dateToDMY(fec)+'</p>'
                                }else{

                                    if( d.idetapa > 9 && d.idetapa < 20 && d.idprovision == null && ( d.caja_chica == 0 || d.caja_chica == null ) && d.tipo_factura == 1 ){
                                        return '<p style="font-size: .7em">FACTURA PAGADA, EN ESPERA DE PROVISION</p>'; 
                                    }else{
                                        return '<p style="font-size: .7em">'+d.etapa+'</p>';
                                    }
                                    
                                }
                                break;
                            }
                        }
                    },
                {
                    "orderable": false,
                    "width": "10%",
                    "data" : function( d ){
                        return '<p style="font-size: .7em">'+(d.caja_chica ? "CAJA CHICA" : "PAGO PROVEEDOR")+'</p>'
                    }
                },
                {
                    "orderable": false,
                    "width": "10%",
                    "data" : function( d ){
                        return '<p style="font-size: .7em">'+d.metoPago+'</p>'
                    }
                },
                {
                    "orderable": false,
                    "width": "10%",
                    "data" : function( d ){
                        return '<p style="font-size: .7em">'+d.nombredir+'</p>'
                    }
                },
                {
                    "orderable": false,
                    "width": "10%",
                    "data" : function( d ){
                        return '<p style="font-size: .7em">'+d.nombre_completo+'</p>'
                    }
                },
                {
                    "orderable": false,
                    "width": "10%",
                    "data" : function( d ){
                        return '<p style="font-size: .7em">'+(d.uuid ? d.uuid : '-' )+'</p>';
                    }
                },
                {
                    "orderable": false,
                    "data": function( d ){
                        if( d.idsolicitud ){
                            var tipo_ventana = no_espera.includes( d.nomdepto ) ? "DEV_BASICA" : "SOL";
                            return '<div class="btn-group-vertical"><button type="button" class="btn btn-sm btn-primary consultar_modal notification" value="'+d.idsolicitud+'" data-value="'+tipo_ventana+'" title="Ver Solicitud"><i class="fas fa-eye"></i>'+(d.visto == 0 ? '<span class="badge">!</span>' : '')+'</button></div>';
                        }else
                            return '';
                    }
                }
            ],
            "columnDefs": [
                {
                    "orderable": false
                },
                {
                    "targets": [ 5 ],
                    "visible": false,
                    "searchable": false
                },
                {
                    "targets": [ 11 ],
                    "visible": false,
                    "searchable": false
                },
                {
                    "targets": [ 12 ],
                    "visible": false,
                    "searchable": false
                },
                {
                    "targets": [ 13 ],
                    "visible": false,
                    "searchable": false
                },
                {
                    "targets": [ 17 ],
                    "visible": false,
                    "searchable": false
                },
                {
                    "targets": [ 18 ],
                    "visible": false,
                    "searchable": false
                },
                {
                    "targets": [ 19 ],
                    "visible": false,
                    "searchable": false
                },
                {
                    "targets": [ 20 ],
                    "visible": false,
                    "searchable": false
                },
                {
                    "targets": [ 21 ],
                    "visible": false,
                    "searchable": false
                }
            ],
            "ajax": url + "Historial/TablaHistorialSolicitudesOri",
            });

        $('#tablahistorialsolicitudes tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = tabla_historial_solicitudes.row( tr );
            
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
                '<tr>'+
                '<td><strong>CAPTURISTA: </strong>'+row.data().nombre_completo+'</td>'+
                '</tr>'+
                '</table>';

                row.child( informacion_adicional ).show();
                tr.addClass('shown');
                $(this).parent().find('.animacion').removeClass("fa-caret-right").addClass("fa-caret-down");
            }
        });

        $('#datepicker_from').change( function() { 
            // tabla_historial_solicitudes.draw();
            let fechaInicio = $(this).val();
            let fechaFin = $("#datepicker_to").val();
            $.ajax({
                url: 'Historial/TablaHistorialSolicitudesOri', // Ruta al servidor o servicio para obtener datos
                type: 'POST',
                data: {
                    fechaInicio: fechaInicio,
                    fechaFin: fechaFin
                }, // Enviar la nueva fecha al servidor
                success: function(result) {
                    // Actualizar la DataTable con los nuevos datos
                    resultado = JSON.parse(result);
                    tabla_historial_solicitudes.clear().draw();
                    tabla_historial_solicitudes.rows.add(resultado.data);
                    tabla_historial_solicitudes.columns.adjust().draw();
                    
                    
                    var total = 0;
                    var index = tabla_historial_solicitudes.rows( { selected: true, search: 'applied' } ).indexes();
                    var data = tabla_historial_solicitudes.rows( index ).data();
                    $.each(data, function(i, v){
                        total += parseFloat(v.mpagar);
                    });
                    var to1 = formatMoney(total);
                    document.getElementById("myText_1").value = to1;
                }
            });
            $("#datepicker_from").datepicker("hide");
        });

        $('#datepicker_to').change( function() {
            // tabla_historial_solicitudes.draw();
            let fechaFin = $(this).val();
            let fechaInicio = $("#datepicker_from").val();
            $.ajax({
                url: 'Historial/TablaHistorialSolicitudesOri', // Ruta al servidor o servicio para obtener datos
                type: 'POST',
                data: {
                    fechaInicio: fechaInicio,
                    fechaFin: fechaFin
                }, // Enviar la nueva fecha al servidor
                success: function(result) {
                    // Actualizar la DataTable con los nuevos datos
                    resultado = JSON.parse(result);
                    tabla_historial_solicitudes.clear().draw();
                    tabla_historial_solicitudes.rows.add(resultado.data);
                    tabla_historial_solicitudes.columns.adjust().draw();
                    
                    
                    var total = 0;
                    var index = tabla_historial_solicitudes.rows( { selected: true, search: 'applied' } ).indexes();
                    var data = tabla_historial_solicitudes.rows( index ).data();
                    $.each(data, function(i, v){
                        total += parseFloat(v.mpagar);
                    });
                    var to1 = formatMoney(total);
                    document.getElementById("myText_1").value = to1;
                }
            });
            $("#datepicker_to").datepicker("hide");
        });
        
        $('.dt-buttons').attr('style','float: right !important; margin-right: 15px;');
    });


    $(window).resize(function(){
        tabla_historial_solicitudes.columns.adjust();
    });

    $('.sidebar-toggle').click(function() { /** INICIO FECHA: 07-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
        setTimeout(function() {
            // 1. Ajustar columnas
            tabla_historial_solicitudes.columns.adjust();
            // 2. Forzar actualización de encabezados
            var headerCellsTableHistorialSolicitudes = $('#tablahistorialsolicitudes thead th');
            headerCellsTableHistorialSolicitudes.each(function() {
                var newWidth = $(this).width(); // Obtener ancho actualizado
                $(this).css('width', newWidth + 'px'); // Aplicarlo
            });
            // 3. Redibujar (sin resetear paginación)
            tabla_historial_solicitudes.draw(false);
        }, 300); // Esperar a que termine la animación del sidebar
    }); /** FIN FECHA: 07-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/

</script>

<?php
require("footer.php");
?>