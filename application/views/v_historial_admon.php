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
                        <h3> <?=$consulta == "DEV" ? "HISTORIAL TRASPASOS Y DEVOLUCIONES" : "HISTORIAL POST-DEVOLUCIONES" ?> </h3> <!-- FECHA: 17-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                    </div>
                    <div class="box-body">
                        <?php if ($this->session->userdata("inicio_sesion")['depto'] == 'CONTRALORIA') { ?> <!-- INICIO FECHA: 21-MARZO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <small class="col-lg-9 col-md-9 text-warning">
                                        Por defecto, el rango de fechas es desde el primer día del año hasta la fecha actual, mostrando solo Solicitudes Activas. Puedes cambiar el tipo de solicitud (activa o rechazada/cancelada) y las fechas, luego hacer clic en la lupa para buscar.
                                        <i class="far fa-question-circle faq text-warning" tabindex="0" 
                                            data-container="body" data-trigger="focus" data-toggle="popover" 
                                            title="Instrucciones para Buscar y Exportar Solicitudes" 
                                            data-content="Por defecto, el rango de fechas es desde el primer día del año hasta la fecha actual, mostrando solo Solicitudes Activas. Puedes cambiar el tipo de solicitud (activa o rechazada/cancelada) y las fechas. Para buscar las solicitudes, selecciona una fecha inicial y una fecha final, luego haz clic en el ícono de la lupa (buscar) para ver los resultados. Si deseas exportar a Excel, usa el botón de EXPORTAR EXCEL. Sin embargo, si el rango de fechas es muy grande (varios meses o más), la exportación podría fallar debido a los límites del servidor en tiempo y memoria. Este error no depende de nuestro sistema, sino de la capacidad del servidor. Si ocurre, te sugerimos seleccionar un rango de fechas más pequeño." 
                                            data-placement="right">
                                        </i>
                                    </small>
                                </div>
                            </div>
                        <?php } ?> <!-- FIN FECHA: 21-MARZO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                        <div class="row">
                            <div class="col-lg-12 col-md-12">
                                <div class="tab-content">
                                    <div class="active tab-pane" id="listado_solicitudes">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12"> <!-- INICIO FECHA: 07-ABRIL-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                                                <!-- <form id="formularioHistorialTranspasosDevoluciones" autocomplete="off" action="<?= site_url("Reportes/reporteHistorialTraspasosDevoluciones") ?>" method="POST"> -->
                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <select class="form-control" name="activos" id="activos_sel">
                                                                <option value="1">SOLICITUDES ACTIVAS</option>
                                                                <?php if ($consulta == "DEV") { ?>
                                                                    <option value="0">SOLICITUDES RECHAZADAS/CANCELADAS</option>
                                                                <?php } ?>
                                                            </select>
                                                            <span id="error-activos" class="help-block" style="display:none;">Por favor, selecciona una opción.</span>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <div class="input-group">
                                                                <span class="input-group-addon"><i class="fas fa-calendar-alt"></i></span>
                                                                <input class="form-control fechas_filtro from" name="finicio" type="text" id="datepicker_from" maxlength="10"/>
                                                            </div>
                                                            <span id="error-fecha-inicio" class="help-block" style="display:none;">La fecha de inicio es obligatoria.</span>
                                                            <span id="error-fecha-rango" class="help-block" style="display:none;">La fecha de inicio no puede ser mayor que la fecha final.</span>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <div class="input-group">
                                                                <span class="input-group-addon"><i class="fas fa-calendar-alt"></i></span>
                                                                <input class="form-control fechas_filtro to" name="ffin" type="text" id="datepicker_to" maxlength="10"/>
                                                            </div>
                                                            <span id="error-fecha-fin" class="help-block" style="display:none;">La fecha de fin es obligatoria.</span>
                                                        </div>
                                                    </div>
                                                    <div id="elementos_hidden"></div>
                                                <!-- </form>  FIN FECHA: 07-ABRIL-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                                                <table class="table table-striped" id="tablahistorialsolicitudes">
                                                    <thead> <!-- INICIO FECHA: 27-FEBRERO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                                                        <tr>
                                                            <th></th>                                         <!-- COLUMNA [ 0 ] --> <!-- FECHA: 21-MARZO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                                                            <th style="font-size: .9em">#</th>                <!-- COLUMNA [ 1 ] Table -->
                                                            <th style="font-size: .9em">EMPRESA</th>          <!-- COLUMNA [ 2 ] Table -->
                                                            <th style="font-size: .9em">PROYECTO</th>         <!-- COLUMNA [ 3 ] Table -->
                                                            <th style="font-size: .9em">SERVICIO/PARTIDA</th> <!-- COLUMNA [ 4 ] Table -->
                                                            <th style="font-size: .9em">OFICINA/SEDE</th>     <!-- COLUMNA [ 5 ] Table -->
                                                            <th style="font-size: .9em">FOLIO</th>            <!-- COLUMNA [ 6 ] Table -->
                                                            <th style="font-size: .9em">F CAPTURA</th>        <!-- COLUMNA [ 7 ] Table -->
                                                            <th style="font-size: .9em">F VoBo CONT</th>      <!-- COLUMNA [ 8 ] Table -->
                                                            <th style="font-size: .9em">F FACT</th>           <!-- COLUMNA [ 9 ] -->
                                                            <th style="font-size: .9em">CAPTURISTA</th>       <!-- COLUMNA [ 10 ] Table -->
                                                            <th style="font-size: .9em">PROVEEDOR</th>        <!-- COLUMNA [ 11 ] Table -->
                                                            <th style="font-size: .9em">LOTE</th>             <!-- COLUMNA [ 12 ] Table -->
                                                            <th style="font-size: .9em">MANTENIMIENTO</th>    <!-- COLUMNA [ 13 ] -->
                                                            <th style="font-size: .9em">PREDIAL</th>          <!-- COLUMNA [ 14 ] -->
                                                            <th style="font-size: .9em">MOVIMIENTO</th>       <!-- COLUMNA [ 15 ] Table -->
                                                            <th style="font-size: .9em">FORMA PAGO</th>       <!-- COLUMNA [ 16 ] Table -->
                                                            <th style="font-size: .9em">CANTIDAD</th>         <!-- COLUMNA [ 17 ] Table -->
                                                            <th style="font-size: .9em">JUSTIFICACION</th>    <!-- COLUMNA [ 18 ] -->
                                                            <th style="font-size: .9em">F AUT PAGO</th>       <!-- COLUMNA [ 19 ] -->
                                                            <th style="font-size: .9em">F DISPERSION</th>     <!-- COLUMNA [ 20 ] -->
                                                            <th style="font-size: .9em">F ENTREGA</th>        <!-- COLUMNA [ 21 ] -->
                                                            <th style="font-size: .9em">F COBRO</th>          <!-- COLUMNA [ 22 ] -->
                                                            <th style="font-size: .9em">DIAS T</th>           <!-- COLUMNA [ 23 ] Table -->
                                                            <th style="font-size: .9em">RECHAZOS</th>         <!-- COLUMNA [ 24 ] Table -->
                                                            <th style="font-size: .9em">INICIADO POR</th>     <!-- COLUMNA [ 25 ] Table -->
                                                            <th style="font-size: .9em">ESTATUS</th>          <!-- COLUMNA [ 26 ] Table -->
                                                            <th style="font-size: .9em">MOTIVO</th>           <!-- COLUMNA [ 27 ] --> <!-- FECHA: 17-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                                                            <th style="font-size: .9em">DETALLE MOTIVO</th>   <!-- COLUMNA [ 28 ] --> <!-- FECHA: 20-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                                                            <th style="font-size: .9em">REF. LOTE</th>        <!-- COLUMNA [ 29 ] --> <!-- FECHA: 07-ABRIL-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> --> 
                                                            <th style="font-size: .9em">ESTATUS_LOTE</th>     <!-- COLUMNA [ 30 ] --> <!-- FECHA: 16-ABRIL-2025 | @author Efrain Martinez Muñoz <programador.analista38@ciudadmaderas.com> | SE AGREGA LA COLUMNA ESTATUS_LOTE -->   
                                                            <th></th>                                         <!-- COLUMNA [ 31 ] -->
                                                        </tr>
                                                    </thead> <!-- FIN FECHA: 27-FEBRERO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
    var valor_input = Array( $('#tablahistorialsolicitudes th').length );
    var titulo_encabezados = []; /** FECHA: 27-FEBRERO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
    var num_columnas = []; /** FECHA: 27-FEBRERO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
    var consulta = 1;

    // Frases con espacios iniciales (usa \u00A0 para espacio no colapsable)
    const frases = [
        "  Generando archivo Excel...",
        "  Conectando con la nube...",
        "  Preparando datos...",
        "  Por favor, espera un momento...",
        "  Optimizando archivo final..."
    ];

    let indexFrase = 0;
    let indexLetra = 0;
    const animatedText = document.getElementById('animated-text');

    $( document ).ready(function() {
        $('.fechas_filtro').datepicker({
            format: 'dd/mm/yyyy',
            autoclose: true,
            //endDate: '-0d'
            zIndexOffset: 10000, /** FECHA: 27-FEBRERO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
            orientation: 'bottom auto' /** FECHA: 27-FEBRERO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
        });

        $('#datepicker_from').val(moment().subtract(4, 'months').format('L')); /** FECHA: 03-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
        $('#datepicker_to').val(moment().format('L')); /** FECHA: 03-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
        
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

        // Animación Lottie desde archivo local o URL
        lottie.loadAnimation({
            container: document.getElementById("lottie-animation"),
            renderer: "svg",
            loop: true,
            autoplay: true,
            path: "<?= base_url('img/Animation-descarga.json') ?>" // Ruta local en tu servidor
        });

    });

    $("#tablahistorialsolicitudes").ready( function(){
        var link = "Devoluciones_Traspasos/tablaHistorialDevTrap" ;

        /** INICIO FECHA: 27-FEBRERO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
        // Variable que determina el departamento del usuario
        var departamentoUsuario = '<?php echo $this->session->userdata("inicio_sesion")['depto'] ?>';  // Esto debería ser dinámico según el usuario logueado

        // Crear el array de botones
        var buttons = [
            {
                text: '<i class="fas fa-search"></i>&nbsp;&nbsp',
                
                attr: { class: 'btn' },

                action: function(e, dt, node, config ) {
                    tabla_historial_solicitudes.ajax.reload();
                }
                
            },
        ];

        // Si el usuario es del departamento "CONTRALORIA", reemplazar el botón de Excel
        if (departamentoUsuario === "CONTRALORIA") {
            // Eliminar el botón actual de Excel
            buttons = buttons.filter(function(button) {
                return button.extend !== 'excelHtml5';  // Elimina el botón de Excel
            });

            // Añadir el nuevo botón para CONTRALORIA
            buttons.push({
                text: '<i class="fas fa-file-excel"></i> EXPORTAR EXCEL',
                attr: {
                    class: 'btn btn-success exportar-contraloria', /** FECHA: 07-ABRIL-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                },
            });
        } else {
            // Si no es del departamento de CONTRALORIA, añadir el botón de Excel normal
            buttons.push({
                extend: 'excelHtml5',             
                text: '<i class="fas fa-file-excel"></i> EXPORTAR EXCEL',
                messageTop: "Listado de Pagos Liquidados",
                attr: {
                    class: 'btn btn-success'       
                },
                exportOptions: {
                    columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 29, 12, 30, 13, 14, 27, 28, 15, 16, 17, 18, 20, 21, 22, 23, 24, 25, 26],
                    format: {
                        header: function(data, columnIdx) {
                            let clean = data.replace(/<\/?[^>]+(>|$)/g, "").trim();
                            return titulo_encabezados[columnIdx] || clean;
                        }
                    },
                    orthogonal: 'export' //FECHA: 16-ABRIL-2025 | @uthor Efrain Martinez Muñoz <programador.analista38@ciudadmaderas.com> | SE AGREGA ESTA PROPIEDAD QUE PERMITE QUE AL EXPORTAR LA TABLA SOLO APAREZCA TEXTO PLANO SIN ETIQUETAS.
                }
            });
        } /** FIN FECHA: 27-FEBRERO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/

        $('#tablahistorialsolicitudes thead tr:eq(0) th').each(function(i) { /** INICIO FECHA: 07-ABRIL-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
            let title = $(this).text().trim();
            titulo_encabezados[i] = title; // Guarda TODOS, incluso los ocultos

            // Aplica filtros solo a las columnas visibles (omite 0 y última)
            if (i !== 0 && i !== $('#tablahistorialsolicitudes thead tr:eq(0) th').length - 1) {
                $(this).html('<input type="text" class="form-control" style="font-size: .9em; width:100%;" data-value="'+title+'" placeholder="'+title+'" title="'+title+'"/>');

                $('input', this).on('keyup change', function () {
                    if (tabla_historial_solicitudes.column(i).search() !== this.value) {
                        tabla_historial_solicitudes
                            .column(i)
                            .search(this.value)
                            .draw();
                        valor_input[title] = this.value;
                    }
                });
            }
        }); /** FIN FECHA: 07-ABRIL-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
        
        $("#formfiltro input").val("");
        
        $('#tablahistorialsolicitudes').on('xhr.dt', function ( e, settings, json, xhr ) {
            /*
            var total = 0;
            $.each( json.data,  function(i, v){
                total += parseFloat(v.mpagar);
            });
            var to = formatMoney(total);
            document.getElementById("myText_1").value = to;
            */
        });

        tabla_historial_solicitudes = $("#tablahistorialsolicitudes").DataTable({
            dom: 'Brtip',
            "buttons": buttons, /** FECHA: 27-FEBRERO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
            "language":lenguaje,
            "processing": false,
            "pageLength": 10,
            "bAutoWidth": false,
            "bLengthChange": false,
            "scrollX": true,
            "bInfo": false,
            "searching": true,
            "deferRender": true,
            "order": [[1, "ASC"]], /** FECHA: 10-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
            "columns": [
            {
                "className": 'details-control',
                "orderable": false,
                "data" : null,
                "defaultContent": '<i class="fas animacion fa-caret-right"></i>'
            },
            {
                "orderable": true, /** FECHA: 10-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                "width": "5%",
                "data" : function( d ){
                    return '<p style="font-size: .8em">'+d.idsolicitud+'</p>'
                }
            },
            {
                "orderable": false,
                "width": "6%",
                "data" : function( d ){
                    return '<p style="font-size: .7em">'+d.abrev+'</p>'
                }
            },
            {
                "orderable": false,
                "width": "7%",
                "data" : function( d ){
                    return '<p style="font-size: .7em">'+d.proyecto+'</p>'
                }
            }, /** INICIO FECHA: 10-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
            { 
                "orderable": false,
                "width": "7%",
                "data" : function( d ){
                    return '<p style="font-size: .7em">'+(d.servicioPartida ? d.servicioPartida : 'NA')+'</p>';
                }
            },
            {
                "orderable": false,
                "width": "7%",
                "data" : function( d ){
                    return '<p style="font-size: .7em">'+(d.oficina ? d.oficina : 'NA')+'</p>';
                }
            }, /** FIN FECHA: 10-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
            {
                "orderable": false,
                "width": "5%",
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
                "width": "7%",
                "data" : function( d ){
                    return '<p style="font-size: .7em">'+formato_fechaymd(d.fecha_autorizacion)+'</p>'
                }
            },
            {
                "orderable": false,
                "width": "10%",
                "data" : function( d ){
                    return '<p style="font-size: .7em">'+formato_fechaymd(d.fecelab)+'</p>'
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
                "width": "18%",
                "data" : function( d ){
                    return '<p style="font-size: .7em">'+d.nombre+'</p>'
                }
            },
            { /** INICIO FECHA: 16-ABRIL-2025 | @uthor Efrain Martinez Muñoz <programador.analista38@ciudadmaderas.com> */
              // SE CREA UNA VALIDACION PAR QUE LA ETIQUETA SOLO SE MUESTRE EN LA VISTA Y SE OMITA LA EXPORTAR A EXCEL
                "orderable": false,
                "width": "7%",
                "data": "lote",
                "render": function(data, type, row) {
                    if (type === 'export') {
                        // Solo el texto sin etiquetas HTML
                        return row.lote;
                    }

                    // Vista en pantalla (HTML con etiquetas)
                    return '<p style="font-size: .7em">' + row.lote +
                        (row.estatusLote
                            ? ((row.idetiqueta == 6 && row.tipo_doc == 9) || row.idetiqueta == 7
                                ? '<br><span class="label label-success"> ' + row.estatusLote + '</span>'
                                : '<br><span class="label label-danger"> ' + row.estatusLote + '</span>')
                            : '') +
                        '</p>';
                }
             // FIN | FECHA : 16-ABRIL-2025 | @uthor Efrain Martinez Muñoz <programador.analista38@ciudadmaderas.com> 
            },
            { /** INICIO FECHA: 10-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                "orderable": false,
                "width": "7%",
                "data" : function( d ){
                    return '<p style="font-size: .7em">'+(d.mantenimiento ? d.mantenimiento : 'NA')+'</p>'; /** creación de la columna predial ACTUALIZACION: 18-09-2024 - @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                }
            },
            {
                "orderable": false,
                "width": "7%",
                "data" : function( d ){
                    return '<p style="font-size: .7em">'+(d.predial ? d.predial : 'NA')+'</p>'; /** creación de la columna mantenimiento ACTUALIZACION: 18-09-2024 - @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                }
            }, /** FIN FECHA: 10-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
            {
                "orderable": false,
                "width": "7%",
                "data" : function( d ){
                    return '<p style="font-size: .7em">'+d.nomdepto+'</p>'
                }
            },
            {
                "orderable": false,
                "width": "7%",
                "data" : function( d ){
                    return '<p style="font-size: .7em">'+d.metoPago+'</p>'
                }
            },
            {
                "orderable": false,
                "width": "7%",
                "data": function( d ){
                    return '<p style="font-size: .7em">'+formatMoney( d.cantidad )+'</p>';
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
                    return '<p style="font-size: .7em">'+(d.upago ?  formato_fechaymd(d.upago)  : '-' )+'</p>';
                }
            },
            {
                "orderable": false,
                "data": function( d ){
                    return '<p style="font-size: .7em">'+(d.fechaDis ?  formato_fechaymd(d.fechaDis)  : '-' )+'</p>'; /** FECHA: 10-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                }
            },
            {
                "orderable": false,
                "data": function( d ){
                    return '<p style="font-size: .7em">'+(d.fEntrega ?  formato_fechaymd(d.fEntrega)  : '-' )+'</p>';
                }
            },
            {
                "orderable": false,
                "data": function( d ){
                    return '<p style="font-size: .7em">'+(d.fecha_cobro ?  formato_fechaymd(d.fecha_cobro)  : '-' )+'</p>';
                }
            },
            {
                "orderable": false,
                "width": "5%",
                "data": function( d ){
                    return '<p style="font-size: .7em">'+(d.diasTrans)+'</p>';
                }
            },{
                "orderable": false,
                "width": "6%",
                "data": function( d ){
                    return '<p style="font-size: .7em">'+(d.numCancelados )+'</p>';
                }
            },
            { /** INICIO FECHA: 10-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                "orderable": false,
                "width": "6%",
                "data": function( d ){
                    return '<p style="font-size: .7em">'+(d.deptoInicia == null ? ' - ' : d.deptoInicia)+'</p>';
                }
            }, /** FIN FECHA: 10-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
            {
                "orderable": false,
                "width": "10",
                "data" : function( d ){                        
                    if( d.estatus_pago && d.estatus_pago != 16 ){
                        if( d.estatus_pago == 15 && d.metoPago == 'TEA' )
                            return '<p style="font-size: .8em">POR CONFIRMAR PAGO</p>';
                        if( d.estatus_pago == 15 && d.metoPago == 'ECHQ' )
                            return '<p style="font-size: .8em">POR ENTREGAR ECHQ</p>';
                        if(d.estatus_pago == 0 && (d.idetapa == 6 || d.idetapa == 8 || d.idetapa == 21 || d.idetapa == 54 || d.idetapa == 30) ) /** FECHA: 10-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                            return '<p style="font-size: .8em">'+d.etapa+'</p>'; /** FECHA: 10-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                        else
                            return '<p style="font-size: .8em">'+d.etapa_pago+'</p>';
                    }else{
                        return '<p style="font-size: .8em">'+d.etapa+'</p>';
                    }
                }
            },
            { /** INICIO FECHA: 17-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                "orderable": false,
                "data": function( d ){
                    return '<p style="font-size: .8em">'+d.motivo+'</p>';
                }
            }, /** FIN FECHA: 17-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
            { /** INICIO FECHA: 20-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                "orderable": false,
                "data": function( d ){
                    return '<p style="font-size: .8em">'+d.detalle_motivo+'</p>';
                }
            }, /** FIN FECHA: 20-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
            { // 29 /** INICIO FECHA: 07-ABRIL-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                "orderable": false,
                "data": function( d ){
                    return '<p style="font-size: .8em">'+d.referencia+'</p>';
                }
            }, /** FIN FECHA: 07-ABRIL-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
            { // 30 /** INICIO FECHA: 16-ABRIL-2025 | @author Efrain Martinez Muñoz <programador.analista38@ciudadmaderas.com> 
             // SE OCULTA LA COLUMNA ESTATUS LOTE DE LA VISTA**/
                "orderable": false,
                "data": function( d ){
                    return '<p style="font-size: .8em">'+ (d.estatusLote ? d.estatusLote : 'N/A') +'</p>';
                }
            }, /** FIN FECHA: 16-ABRIL-2025 | @author Efrain Martinez Muñoz <programador.analista38@ciudadmaderas.com> **/
            {
                "orderable": false,
                "data": function( d ){
                    return '<div class="btn-group-vertical"><button type="button" class="btn btn-sm btn-primary consultar_modal notification" value="'+d.idsolicitud+'" data-value="DEV" title="Ver Solicitud"><i class="fas fa-eye"></i>'+(d.visto == 0 ? '<span class="badge">!</span>' : '')+'</button></div>';
                }
            }
            ],
            "columnDefs": [
                {
                    "orderable": false
                },
                {
                    "targets": [ 9 ], /** FECHA: 10-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                    "visible": false,
                    "searchable": false
                },
                {/** INICIO FECHA: 10-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                    "targets": [ 13 ],
                    "visible": false,
                    "searchable": false
                },/** FIN  FECHA: 10-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                {
                    "targets": [ 14 ],
                    "visible": false,
                    "searchable": false
                },
                {/** INICIO FECHA: 10-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                    "targets": [ 18 ],
                    "visible": false,
                    "searchable": false
                },
                {
                    "targets": [ 19 ], /** FIN FECHA: 10-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */   
                    "visible": false,
                    "searchable": false
                },
                {
                    "targets": [ 20 ], /** FECHA: 10-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                    "visible": false,
                    "searchable": false
                },
                {
                    "targets": [ 21 ], /** FECHA: 10-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                    "visible": false,
                    "searchable": false
                },
                {
                    "targets": [ 22 ], /** FECHA: 10-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                    "visible": false,
                    "searchable": false
                },
                { /** INICIO FECHA: 17-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                    "targets": [ 27 ], /** FECHA: 10-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                    "visible": false,
                    "searchable": false
                }, /** FIN FECHA: 17-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                { /** INICIO FECHA: 20-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                    "targets": [ 28 ], /** FECHA: 10-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                    "visible": false,
                    "searchable": false
                }, /** FIN FECHA: 20-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                { /** INICIO FECHA: 07-ABRIL-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                    "targets": [ 29 ],
                    "visible": false,
                    "searchable": false
                }, /** FIN FECHA: 07-ABRIL-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                { /** INICIO FECHA: 16-Abril-2025 | @author Efrain Martinez Muñoz <programador.analista38@ciudadmaderas.com> **/
                    "targets": [ 30 ],
                    "visible": false,
                    "searchable": false
                } /** FIN FECHA: 16-ABRIL-2025 | @author Efrain Martinez Muñoz <programador.analista38@ciudadmaderas.com> **/
            ],
            "ajax":{ 
                url: url + link, //"Devoluciones_Traspasos/tablaHistorialDevTrap",
                type: 'POST', 
                data: function(d){
                    d.activo = $("#activos_sel").val(),
                    d.finicial = formatfech($('#datepicker_from').val()),
                    d.ffinal= formatfech($('#datepicker_to').val())
                }
            }
        });

        function formatfech(valor){
            return valor.substring(6,10)+'-'+valor.substring(3,5)+'-'+valor.substring(0,2);
        }

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
            tabla_historial_solicitudes.draw();
            $("#fechaini_f").val($(this).val());
        });

        $('#datepicker_to').change( function() { 
            tabla_historial_solicitudes.draw();
            $("#fechafin_f").val($(this).val());
        });
        
        $('.dt-buttons').attr('style','float: right !important; margin-right: 15px;');

        /** INICIO FECHA: 07-ABRIL-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
        /** FECHA ACTUALIZACION: 21-MAYO-2025 | @author DANTE ALDAIR GUERRERO ALDANA <coordinador6.desarrollo@ciudadmaderas.com> @version 1.2**/
        // Añadir el nuevo botón para CONTRALORIA
        // Evento de clic para el botón "exportar-contraloria"
        $(document).on('click', '.exportar-contraloria', function() {

            // Limpiar errores anteriores
            $('.has-error').removeClass('has-error');
            $('.help-block').hide();

            var valido = true;
            var data = new FormData();
            var nomArchivoNdjson = new FormData();

            // Obtener los valores de las fechas
            var fechaInicio = $('#datepicker_from').val();
            var fechaFin = $('#datepicker_to').val();

            if (!fechaInicio) {
                $('#datepicker_from').closest('.form-group').addClass('has-error');
                $('#error-fecha-inicio').show();
                valido = false;
            }

            if (!fechaFin) {
                $('#datepicker_to').closest('.form-group').addClass('has-error');
                $('#error-fecha-fin').show();
                valido = false;
            }

            if (fechaInicio && fechaFin) {
                // Convertir formato DD/MM/YYYY a YYYY-MM-DD para evitar problemas con Date()
                var partesInicio = fechaInicio.split('/');
                var partesFin = fechaFin.split('/');
                
                var fechaInicioObj = new Date(partesInicio[2], partesInicio[1] - 1, partesInicio[0]); // Año, Mes (0-11), Día
                var fechaFinObj = new Date(partesFin[2], partesFin[1] - 1, partesFin[0]);

                if (fechaInicioObj > fechaFinObj) {
                    $('#datepicker_from, #datepicker_to').closest('.form-group').addClass('has-error');
                    $('#error-fecha-rango').show();
                    valido = false;
                }
            }

            // Validar el select "activos"
            var activosValue = $('#activos_sel').val();
            if (!activosValue) {
                $('#activos_sel').closest('.form-group').addClass('has-error');
                $('#error-activos').show();
                valido = false;
            }

            // Si hay errores, detener la ejecución
            if (!valido) return;

            // Si todo está bien, agregar valores al FormData
            data.append('finicio', fechaInicio);
            data.append('ffin', fechaFin);
            data.append('activos', activosValue);

            // Recopilar los valores de los inputs de la tabla
            $('#tablahistorialsolicitudes thead tr:eq(0) input').each(function(i) {  
                var inputData = $(this).data('value');
                if (Object.keys(valor_input).indexOf(inputData) >= 0) {
                    data.append(inputData, valor_input[inputData]); // Agregar valores al formulario oculto
                }
            });
            mostrarLoader(); // Mostrar loader
            // Version 1.2 para el llamado de funcion y descarga de archivo en excel.
            fetch('<?= site_url("Reportes/reporteHistorialTraspasosDevoluciones") ?>', {
                method: 'POST',
                body: data, // FormData
            })
            .then(async response => {
                    // 1. Primero capturamos las cabeceras y determinamos si es un archivo o texto/JSON
                    const contentType = response.headers.get('Content-Type') || '';
                    const contentDisposition = response.headers.get('Content-Disposition') || '';
                    
                    // Verificamos si la respuesta es un archivo descargable * Esto se queda ya que anteriormente se descargaba un archivo tal cual directo *
                    const esArchivo =   contentType.includes('application/vnd.openxmlformats') || 
                                        contentType.includes('application/octet-stream') ||
                                        response.headers.get('Content-Disposition')?.includes('attachment');
                    
                    // Asignamos un nombre de archivo por default
                    let filename = 'reporte_default.xlsx';

                    // Validamos si lo recivido en las cabeceras es un archivo o no
                    if (esArchivo) {
                        // Caso 1: - Es un archivo para descargar -
                        const blob = await response.blob();

                        // Validamos si desde el Back ya tenemos un nombre a ponerle al archivo.
                        if (contentDisposition.includes("filname=") || contentDisposition != '') {
                            // Asignamos nombre de archivo recuperado desde las cabeceras del Back.
                            filename = contentDisposition.split('filename=')[1].replace(/"/g, '');
                        }
                        
                        // Creamos un enlace temporal para descargar el archivo
                        const url = window.URL.createObjectURL(blob);
                        const a = document.createElement('a');
                        a.href = url;
                        a.download = filename;
                        document.body.appendChild(a);
                        a.click();
                        a.remove();


                    } 
                    // En este caso si de parte del BackEnd se imprime un texto simple ya sea un array, una cadena, una variable, una respuesta Json, etc. para cuestion de pruebas
                    else {
                        // Caso 2: Respuesta es texto o JSON (por ejemplo, para pruebas)
                        const textResponse = await response.text();
                        
                        try {
                            // Intentamos convertir la respuesta a JSON
                            const jsonResponse = JSON.parse(textResponse);

                            // Validamos que sea la estructura esperada
                            if(jsonResponse.datosEnviarGoogle && jsonResponse.urlGoogleCloud && jsonResponse.nombreArchivo){
                                // Aquí se activaria un loader
                                    //... Codigo
                                fetch(jsonResponse.urlGoogleCloud , {
                                    method: 'POST',
                                    headers: {'Content-Type': 'application/json',},
                                    body: JSON.stringify({
                                        urlJson: jsonResponse.datosEnviarGoogle,
                                        nombreArchivo: jsonResponse.nombreArchivo
                                    })
                                })
                                .then(async respuestaGoogleCloud => {
                                    // Verificamos si la respuesta de la nube es válida
                                    if (!respuestaGoogleCloud.ok) {
                                        throw new Error("Error desde Google Cloud Function, revisar con administrador de servicios");
                                    }
                                    // Archivo para descargar cachado desde GoogleCloud
                                    const blob = await respuestaGoogleCloud.blob();

                                    // Pponemos nombre al archivo.
                                    filename = jsonResponse.nombreArchivo;

                                    // Capturamos el nombre del NDJSON comprimido para su eliminacion.
                                    nomArchivoNdjson.append('nomArchivoNdJson', jsonResponse.datosEnviarGoogle.split("/").pop());
                                    
                                    // Descargamos el archivo
                                    const url = window.URL.createObjectURL(blob);
                                    const a = document.createElement('a');
                                    a.href = url;
                                    a.download = filename;
                                    document.body.appendChild(a);
                                    a.click();
                                    a.remove();
                                    
                                })
                                .catch(err => {
                                    ocultarLoader(); // Ocultar loader
                                    // Error al llamar al servicio en la nube
                                    console.error("Error en Cloud Function:", err);
                                    alert("Ocurrió un error al generar el Excel en la nube.");
                                    
                                })
                                .finally(() =>  {
                                    // Enviamos una solicitud POST para eliminar un archivo en el servidor
                                    fetch(url + 'Reportes/eliminarArchivo', {
                                        method: "POST", // Enviamos una solicitud POST para eliminar un archivo en el servidor
                                        body: nomArchivoNdjson // El cuerpo de la solicitud contiene el nombre del archivo en formato NDJSON
                                    })
                                    .then(response => response.json()) // Convertimos la respuesta a formato JSON
                                    .then(respuesta => {
                                        // Verificamos si la operación fue exitosa
                                        if (respuesta.estatus === 'ok') {
                                            console.log('El proceso se completó con éxito.');                                            
                                        }else{
                                            // Si hubo un error del lado del servidor, lo mostramos
                                            console.error(respuesta.mensaje);
                                        }
                                    })
                                    .catch(error =>{
                                        // Captura cualquier error de red o del servidor
                                        console.error("Error de red o conexión con el servidor:", error);
                                    });
                                    ocultarLoader(); // Ocultar loader
                                    
                                });
                            } else{
                                // Si la estructura del JSON no es la esperada mostramos respuesta que no sea la esperada al momento de mandar a llamar  el servicio al Google Cloud.
                                console.log("Respuesta JSON normal:", jsonResponse);
                                
                            }
                        } catch {
                            ocultarLoader(); // Ocultar loader
                            // Si no es JSON, mostramos el texto plano (print_r del backend).
                            console.log("Respuesta del servidor (texto):", textResponse);
                            alert("Ocurrió un error al generar el Excel en la nube.");
                            
                        }
                    }
                })
                .catch(error => {
                    ocultarLoader(); // Ocultar loader
                    // Error en la solicitud al servidor
                    console.error('Error en la solicitud:', error);
                    alert('Ocurrió un error: ' + error.message);
                    
                });
            
        });
        /** FIN FECHA: 07-ABRIL-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
        /** FIN FECHA: 21-MAYO-2025 | @author DANTE ALDAIR GUERRERO ALDANA <coordinador6.desarrollo@ciudadmaderas.com> **/
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

        var iStartDateCol = 7;
        var iEndDateCol = 7;

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
    });
    
    function llenar_filtro(id,val){
        $("#"+id+"_f").val(val);
        $("#fechaini_f").val($("#datepicker_from").val());
        $("#fechafin_f").val($("#datepicker_to").val());
    }
    
    function filtro_excel(){
        $("#formfiltro").submit();
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

</script>

<?php
require("footer.php");
?>

