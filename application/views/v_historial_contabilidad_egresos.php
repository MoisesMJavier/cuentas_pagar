<?php
    require("head.php");
    require("menu_navegador.php");
?> 
<style>
    /* Fondo con efecto glass y desenfoque */
    #loader-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        backdrop-filter: blur(8px);
        background-color: rgba(255, 255, 255, 0.3);
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .loader-container {
        position: relative;
        z-index: 1;
        text-align: center;
        padding: 60px;
        width: 400px;
        min-height: 300px;
        background: rgba(255, 255, 255, 0.15);
        border-radius: 20px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
    }

    .loader-text {
        font-size: 1.2em;
        color: #222;
        font-weight: 500;
        margin-top: 1.5em;
        min-height: 2.5em;
        white-space: pre; /* IMPORTANTE para conservar espacios */
    }
</style>
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
                                <div class="col-lg-3">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fas fa-calendar-alt"></i> <b>INICIO</b></span>
                                            <input class="form-control fechas_filtro from" id="fecInicial" type="text" />
                                        </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fas fa-calendar-alt"></i> <b>FIN</b></span>
                                            <input class="form-control fechas_filtro to" id="fecFinal" type="text" />
                                        </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group-addon" style="padding: 4px;">
                                        <h4 style="padding: 0; margin: 0;"><label>&nbsp;Total: $<input style="text-align: right; border-bottom: none; border-top: none; border-right: none;  border-left: none; background: white;" disabled="disabled" readonly="readonly" type="text"  name="myText_1" id="myText_1"></label></h4>
                                    </div>
                                </div>
                                
                                <table class="table table-striped" id="tablahistorialsolicitudes">
                                    <thead>
                                        <tr>
                                            <th></th>                                               <!-- [ COLUMNA 0 ] -->
                                            <th style="font-size: .9em">#</th>                      <!-- [ COLUMNA 1 ] -->
                                            <th style="font-size: .9em">EMPRESA</th>                <!-- [ COLUMNA 2 ] -->
                                            <th style="font-size: .9em">FOLIO</th>                  <!-- [ COLUMNA 3 ] -->
                                            <th style="font-size: .9em">F FACT</th>                 <!-- [ COLUMNA 4 ] -->
                                            <th style="font-size: .9em">F ALTA</th>                 <!-- [ COLUMNA 5 ] -->
                                            <th style="font-size: .9em">F PAGO</th>                 <!-- [ COLUMNA 6 ] -->
                                            <th style="font-size: .9em">PROVEEDOR</th>              <!-- [ COLUMNA 7 ] -->
                                            <th style="font-size: .9em">PROYECTO</th>               <!-- [ COLUMNA 8 ] -->
                                            <th style="font-size: .9em">OFICINA/SEDE</th>           <!-- [ COLUMNA 9 ] --><!-- INICIO FECHA: 22-ENERO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                                            <th style="font-size: .9em">CONDOMINIO</th>             <!-- [ COLUMNA 10 ] --><!-- INICIO FECHA: 22-ENERO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                                            <th style="font-size: .9em">DEPARTAMENTO</th>           <!-- [ COLUMNA 11 ] -->
                                            <th style="font-size: .9em">CANTIDAD</th>               <!-- [ COLUMNA 12 ] -->
                                            <th style="font-size: .9em">JUSTIFICACION</th>          <!-- [ COLUMNA 13 ] -->
                                            <th style="font-size: .9em">FORMA PAGO</th>             <!-- [ COLUMNA 14 ] -->
                                            <th style="font-size: .9em">RESPONSABLE</th>            <!-- [ COLUMNA 15 ] -->
                                            <th style="font-size: .9em">ESTATUS</th>                <!-- [ COLUMNA 16 ] -->
                                            <th style="font-size: .9em">FOLIO FISCAL</th>           <!-- [ COLUMNA 17 ] -->
                                            <th style="font-size: .9em">POLIZA</th>                 <!-- [ COLUMNA 18 ] -->
                                            <th style="font-size: .9em">F PROVISION</th>            <!-- [ COLUMNA 19 ] -->
                                            <th style="font-size: .9em">TIPO SERVICIO/PARTIDA</th>  <!-- [ COLUMNA 20 ] -->
                                            <th style="font-size: .9em">TIPO DE INSUMO</th>         <!-- [ COLUMNA 21 ] -->
                                            <th style="font-size: .9em">ETAPA</th>                  <!-- [ COLUMNA 22 ] -->
                                            <th style="font-size: .9em">HOMOCLAVE</th>              <!-- [ COLUMNA 23 ] -->
                                            <th></th>                                               <!-- [ COLUMNA 24 ] -->
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <!-- INICIO LOADER DE PROGRESO -->
                        <div id="loader-overlay" style="display:none;">
                            <div class="loader-container">
                                <div id="lottie-animation" style="width: 160px; height: 160px; margin: 0 auto;"></div>
                                <p class="loader-text" id="animated-text">Generando archivo Excel...</p>
                            </div>
                        </div>
                        <!-- FIN LOADER DE PROGRESO -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>

    var tabla_historial_solicitudes;
    var tipo_solicitud = 0;
        // Traemos la fecha actual con libreria moment
    const fechaActual = moment();
    // Como fecha de inicio restamos 4 años a la fecha de cuando se consulta (para dejar un filtro parecido a la version anterior.)
    const fechaDataPickerInicio = moment(fechaActual).subtract(4, 'YEARS').format('DD/MM/YYYY');
    
    // Frases con espacios iniciales (usa \u00A0 para espacio no colapsable)
    const frases = [
        "  Generando archivo Excel...",
        "  Preparando datos...",
        "  Por favor, espera un momento...",
        "  Optimizando archivo final..."
    ];

    let indexFrase = 0;
    let indexLetra = 0;
    const animatedText = document.getElementById('animated-text');
    // ID del usuario desde el unicio de sesion
    var id_usuario = <?php echo $this->session->userdata("inicio_sesion")['id']?>;
    // VARIABLE QUE CONTROLA LAS COLUMNAS A EXPORTAR.
    var columnasExcel = [];

    $(document).ready(function() {
        rangoFechasFiltro();

        // Animación Lottie desde archivo local o URL
        lottie.loadAnimation({
            container: document.getElementById("lottie-animation"),
            renderer: "svg",
            loop: true,
            autoplay: true,
            path: "<?= base_url('img/Animation-descarga.json') ?>" // Ruta local en tu servidor
        });

        // SE CONDICIONAN LAS COLUMNAS DEL DATATABLE A EXPORTAR EN EL EXCEL.
        if (id_usuario == 92) {
            columnasExcel = [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23];
        }else{
            columnasExcel = [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19];
        }
        
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
    });

    $('.fechas_filtro').datepicker({
        format: 'dd/mm/yyyy',
        endDate: '-0d'
    });

    $('#tablahistorialsolicitudes').on('xhr.dt', function ( e, settings, json, xhr ) {
         var total = 0;
         $.each( json.data,  function(i, v){
            total += parseFloat(v.cantidad);
         });
         
         var to = formatMoney(total);
        document.getElementById("myText_1").value = to;
    });
    
    $("#tablahistorialsolicitudes").ready( function(){

        $('#tablahistorialsolicitudes thead tr:eq(0) th').each( function (i) {
            if( $(this).text() != '' ){
                var title = $(this).text();
                $(this).html( '<input type="text" style="font-size: .9em; width: 100%;" placeholder="'+title+'" title="'+title+'"  />' );

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
                               total += parseFloat(v.cantidad);
                           });
                           
                           var to1 = formatMoney(total);
                           document.getElementById("myText_1").value = formatMoney(total);
                    }
                });
            }
        });

        tabla_historial_solicitudes = $("#tablahistorialsolicitudes").DataTable({
            dom: 'Brtip',
            "buttons": [
                {
                    text: '<i class="fas fa-search"></i>&nbsp;&nbsp',
                   
                    attr: { class: 'btn' },

                    action: function() {
                    
                        $.ajax({ 
                        "url" : url + "Historial/ThistorialContabilidad",
                        "type": "POST",
                        "data" : {
                            tipo_reporte : tipo_solicitud,
                            finicial : formatfech($('#fecInicial').val()),
                            ffinal : formatfech($('#fecFinal').val())
                         },
                         success: function(result){
                           console.log("Reporte creado con exito.");
                         } });
                    }
                    
                },
                {
                    extend: 'excelHtml5',             
                    text: '<i class="fas fa-file-excel"></i> EXPORTAR',
                    messageTop: "Listado de Pagos Liquidados",
                    attr: {
                        class: 'btn btn-success'       
                    },
                    exportOptions: {
                        columns: columnasExcel,
                        format: {
                            header: function (data, columnIdx) { 
                                // Extraer placeholder
                                let tituloEncabezado = data.split('placeholder="')[1]?.split('"')[0];
                                return tituloEncabezado;
                            }
                        }
                    },
                    action: function (e, dt, button, config) {
                        const self = this;

                        // Mostrar el loader
                        mostrarLoader();
                        requestAnimationFrame(() => {
                            // Esperar y ocultar el loader (ajusta el tiempo según el tamaño de tus datos)
                            setTimeout(function () {
                                
                                // Llamar la acción original del botón
                                $.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, button, config);

                                setTimeout(function (){
                                    // Ocultar el loader
                                    ocultarLoader();
                                }, 1000);

                            }, 1000); // Puedes aumentar este valor si tarda más en exportar
                        });
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
            "columns": [
                /* [ COLUMNA 0 ] */
                {
                    "width": "1%",
                    "className": 'details-control',
                    "orderable": false,
                    "data" : null,
                    "defaultContent": '<i class="fas animacion fa-caret-right"></i>'
                },
                /* [ COLUMNA 1 ] */
                {
                    "width": "4%",
                    "data" : function( d ){
                        return '<p style="font-size: .9em">'+d.idsolicitud+'</p>'
                    }
                },
                /* [ COLUMNA 2 ] */
                {
                    "width": "4%",
                    "data" : function( d ){
                        return '<p style="font-size: .9em">'+d.abrev+'</p>'
                    }
                },
                /* [ COLUMNA 3 ] */
                {
                    "width": "5%",
                    "data" : function( d ){
                        return '<p style="font-size: .9em">'+( d.folio ? d.folio : "NA" )+( d.folifis ? "/"+d.folifis : "")+'</p>'
                    }
                },
                /* [ COLUMNA 4 ] */
                {
                    "width": "8%",
                    "data" : function( d ){
							return '<p style="font-size: .9em">'+(d.fecelab ? formato_fechaymd(d.fecelab): "")+'<br></p>';
					}
                },
                /* [ COLUMNA 5 ] */
                {
                    "width": "8%",
                    "data" : function( d ){
							return '<p style="font-size: .9em">'+(d.feccrea ? formato_fechaymd(d.feccrea): "")+'<br></p>';
					}
                },
                /* [ COLUMNA 6 ] */
				{
                    "width": "8%",
                    "data" : function( d ){
                        return '<p style="font-size: .9em"><b>'+(d.fechaDis ? formato_fechaymd(d.fechaDis) : "")+'</b></p>';
                    }
                },
                /* [ COLUMNA 7 ] */
                { "width": "12%",
                    "data" : function( d ){
                        return '<p style="font-size: .7em">'+d.nombre+'</p>'
                    }
                },
                /* [ COLUMNA 8 ] */
                { "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.proyecto+'</p>'
                    }
                },
                /* [ COLUMNA 9 ] */
                { "data" : function( d ){ /** INICIO FECHA: 22-ENERO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                        return '<p style="font-size: .8em">'+(d.oficina ? d.oficina : 'NA')+'</p>'
                    }
                },
                /* [ COLUMNA 10 ] */
                { "data" : function( d ){
                        return '<p style="font-size: .8em">'+(d.condominio ? d.condominio : 'NA')+'</p>'
                    }
                }, /** FIN FECHA: 22-ENERO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                /* [ COLUMNA 11 ] */
                { "width": "12%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.nomdepto+'</p>'
                    }
                },
                /* [ COLUMNA 12 ] */
                { 
                    "width": "10%",
                    "data" : function( d ){
                        return '<p style="font-size: .9em">'+formatMoney(d.cantidad)+'</p>'
                    } 
                },
                /* [ COLUMNA 13 ] */
                {
                    "data" : function( d ){
                        return '<p style="font-size: .9em">'+d.justificacion+'</p>'
                    }
                },
                /* [ COLUMNA 14 ] */
                {
                    "width": "8%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.metoPago+'</p>'
                    }
                },
                /* [ COLUMNA 15 ] */
                {
                    "width": "12%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.nombre_completo+'</p>'
                    }
                },
                /* [ COLUMNA 16 ] */
                {
                    "width": "10%",
                    "data" : function( d ){                        
                        if( d.estatus_pago && d.estatus_pago != 16 ){
                            if( d.estatus_pago == 15 && d.metoPago == 'TEA' )
                                return '<p style="font-size: .8em">POR CONFIRMAR PAGO</p>';
                            if( d.estatus_pago == 15 && d.metoPago == 'ECHQ' )
                                return '<p style="font-size: .8em">POR ENTREGAR ECHQ</p>';
                            else
                                return '<p style="font-size: .8em">'+d.etapa_pago+'</p>';
                        }else{
                            return '<p style="font-size: .8em">'+d.etapa+'</p>';
                        }
                    }
                },
                /* [ COLUMNA 17 ] */
                {
                    "orderable": false,
                    "data": function( d ){
                        return '<p style="font-size: .8em">'+(d.uuid ? d.uuid : 'NA' )+'</p>'
                    }
                },
                /* [ COLUMNA 18 ] */
                {
                    "orderable": false,
                    "data": function( d ){
                        return '<p style="font-size: .8em">'+(d.numpoliza ? d.numpoliza : 'SIN PROVISION' )+'</p>'
                    }
                },
                /* [ COLUMNA 19 ] */
                {
                    "orderable": false,
                    "data": function( d ){
                        return '<p style="font-size: .8em">'+(d.fprovision ? formato_fechaymd(d.fprovision) : '' )+'</p>'
                    }
                },
                /* [ COLUMNA 20 ] */
                {
                    "orderable": false,
                    "data": function( d ){
                        return '<p style="font-size: .8em">'+(d.tipoServParti ? d.tipoServParti : 'NA' )+'</p>'
                    }
                },
                /* [ COLUMNA 21 ] */
                {
                    "orderable": false,
                    "data": function( d ){
                        return '<p style="font-size: .8em">'+(d.insumo ? d.insumo : '' )+'</p>'
                    }
                },
                /* [ COLUMNA 22 ] */
                {
                    "orderable": false,
                    "data": function( d ){
                        return '<p style="font-size: .8em">'+(d.etapaSp ? d.etapaSp : '' )+'</p>'
                    }
                },
                /* [ COLUMNA 23 ] */
                {
                    "orderable": false,
                    "data": function( d ){
                        return '<p style="font-size: .8em">'+(d.homoclave ? d.homoclave : '' )+'</p>'
                    }
                },
                /* [ COLUMNA 24 ] */
                {
                    "orderable": false,
                    "data": function( d ){
                        return '<div class="btn-group-vertical"><button type="button" class="btn btn-primary consultar_modal notification" value="'+d.idsolicitud+'" data-value="SOL"><i class="fas fa-eye"></i>'+(d.visto == 0 ? '<span class="badge">!</span>' : '')+'</button></div>';
                    }
                }
            ],
            "columnDefs": [ {
                   "orderable": false
                },
                {
                    "targets": [ 5 ],
                    "visible": false,
                    "searchable": false
                },
                {
                    "targets": [ 8 ],
                    "visible": false,
                    "searchable": false
                },
                {
                    "targets": [ 11 ],
                    "visible": false,
                    "searchable": false
                },
                {
                    "targets": [ 15 ],
                    "visible": false,
                    "searchable": false
                },
                {
                    "targets": [ 16 ],
                    "visible": false,
                    "searchable": false
                },
                {
                    "targets": [ 17 ],
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
                },
                {
                    "targets": [ 22 ],
                    "visible": false,
                    "searchable": false
                },
                {
                    "targets": [ 23 ],
                    "visible": false,
                    "searchable": false
                }
            ],
            order: [[0, "asc"]],
            bSort: false,
            "ajax": {
                "type": "POST",
                "url": url + "Historial/ThistorialContabilidad",
                "data" : function( d ){
                    d.tipo_reporte  = tipo_solicitud,
                    d.fechaInicio   = moment($('#fecInicial').val(), 'DD/MM/YYYY').format('YYYY-MM-DD'),
                    d.fechaFin    = moment($('#fecFinal').val(), 'DD/MM/YYYY').format('YYYY-MM-DD')
                }
            }
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
                '</table>';

                row.child( informacion_adicional ).show();
                tr.addClass('shown');
                $(this).parent().find('.animacion').removeClass("fa-caret-right").addClass("fa-caret-down");
            }
        });

        $('.dt-buttons').attr('style','float: right !important; margin-right: 15px;');// aquí


        $('#fecInicial').change( function() { 
            let fechaInicio = moment($(this).val(), 'DD/MM/YYYY').format('YYYY-MM-DD');
            let fechaFin = moment($('#fecFinal').val(), 'DD/MM/YYYY').format('YYYY-MM-DD');
            $.ajax({
                url:   url + "Historial/ThistorialContabilidad", // Ruta al servidor o servicio para obtener datos
                type: 'POST',
                data: {
                    fechaInicio: fechaInicio,
                    fechaFin: fechaFin,
                    tipo_reporte: tipo_solicitud
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
                        total += parseFloat(v.cantidad);
                    });
                    var to1 = formatMoney(total);
                    document.getElementById("myText_1").value = to1;
                }
            });
            $("#fecInicial").datepicker("hide");
        });

        $('#fecFinal').change( function() {
            let fechaFin = moment($(this).val(), 'DD/MM/YYYY').format('YYYY-MM-DD');
            let fechaInicio = moment($('#fecInicial').val(), 'DD/MM/YYYY').format('YYYY-MM-DD');
            $.ajax({
                url: url + 'Historial/ThistorialContabilidad', // Ruta al servidor o servicio para obtener datos
                type: 'POST',
                data: {
                    fechaInicio: fechaInicio,
                    fechaFin: fechaFin,
                    tipo_reporte: tipo_solicitud
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
                        total += parseFloat(v.cantidad);
                    });
                    var to1 = formatMoney(total);
                    document.getElementById("myText_1").value = to1;
                }
            });
            $("#fecFinal").datepicker("hide");
        });
        
        
    });
    
    $("ul.nav-tabs > li > a").on("shown.bs.tab", function(e) {
            $(".fechas_filtro").val('');
            tabla_historial_solicitudes.draw();
    });

    $(window).resize(function(){
        tabla_historial_solicitudes.columns.adjust();
    });

    function formatfech(valor){
        return valor.substring(6,10)+'-'+valor.substring(3,5)+'-'+valor.substring(0,2);
    }
        
    function rangoFechasFiltro(tipoReporteDataTable) {
        // Traemos la fecha actual con libreria moment
        const fechaActual = moment();
        // Como fecha de inicio restamos 4 años a la fecha de cuando se consulta (para dejar un filtro parecido a la version anterior.)
        const fechaDataPickerInicio = moment().startOf('year').format('DD/MM/YYYY');
        
        $('.fechas_filtro').datepicker({
            format: 'dd/mm/yyyy',
            autoclose: true,
            //endDate: '-0d'
            zIndexOffset: 10000, /** FECHA: 30-MAYO-2025 | @author Dante Aldair Guerrero Aldana <coordinador6.desarrollo@ciudadmaderas.com> **/
            orientation: 'bottom auto' /** FECHA: 30-MAYO-2025 | @author Dante Aldair Guerrero Aldana <coordinador6.desarrollo@ciudadmaderas.com> **/
        });
        $('#fecInicial').datepicker('setDate', fechaDataPickerInicio);
        $('#fecInicial').val(fechaDataPickerInicio);

        // Asigna la fecha predeterminada al campo de entrada
        $('#fecFinal').datepicker('setDate', fechaActual.format('dd/mm/yyyy'));
        $('#fecFinal').val(fechaActual.format('DD/MM/YYYY'));
    }

    // Mostrar loader (llámalo desde fetch u otro evento)
    function mostrarLoader() {
        document.getElementById('loader-overlay').style.display = 'flex';
        indexFrase = 0;
        indexLetra = 0;
        mostrarLetraPorLetra();
    }

    // Ocultar loader
    function ocultarLoader() {
        document.getElementById('loader-overlay').style.display = 'none';
    }

    function mostrarLetraPorLetra() {
        const fraseActual = frases[indexFrase];
        animatedText.textContent = fraseActual.slice(0, indexLetra);
        indexLetra++;
        if (indexLetra > fraseActual.length) {
        setTimeout(() => {
            indexFrase = (indexFrase + 1) % frases.length;
            indexLetra = 0;
            setTimeout(mostrarLetraPorLetra, 100);
        }, 2000); // Tiempo visible antes de cambiar
        } else {
        setTimeout(mostrarLetraPorLetra, 50);
        }
    }

</script>

<?php
    require("footer.php");
?>