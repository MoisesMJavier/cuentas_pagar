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
                        <h3>HISTORIAL DE PAGOS</h3>
                    </div>
                    <div class="box-body">
                        <div class="tab-content">
                            <div class="tab-pane active" id="listado_pagos">
                                <form id="formulario_reporttransferencias" autocomplete="off" action="<?= site_url("Reportes/reporte_historial_pagos") ?>" method="post">
                                    <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12"> <!-- FECHA: 26-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                                        <div class="input-group">
                                            <span class="input-group-addon text-bold"><i class="fas fa-calendar-alt"></i>&nbsp;INICIO</span> <!-- FECHA: 26-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                                            <input class="form-control fechas_filtro from" type="text" id="fecInicial" name="fechaInicial"/>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12"> <!-- FECHA: 26-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                                        <div class="input-group">
                                            <span class="input-group-addon text-bold"><i class="fas fa-calendar-alt"></i>&nbsp;FIN</span> <!-- FECHA: 26-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                                            <input class="form-control fechas_filtro to" type="text" id="fecFinal" name="fechaFinal"/>
                                        </div>
                                    </div>
                                    <div id="elementos_hidden"></div>
                                </form>
                                <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12"> <!-- INICIO FECHA: 26-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="input-group-addon bg-gray text-bold">TOTAL: $</div>
                                            <input class="form-control text-bold" style="background-color: white; font-size: 14px; cursor: default;" disabled="disabled" readonly="readonly" type="text"  name="myText_1" id="myText_1">
                                        </div>
                                    </div>
                                </div>  <!-- FIN FECHA: 26-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->

                                <table class="table table-striped " id="tablahistorialpagos">
                                    <thead>
                                        <tr>  <!-- INICIO FECHA: 26-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                                            <th style="font-size: .9em">#</th>                  <!-- COLUMNA [ 0 ] -->
                                            <th style="font-size: .9em">FOLIO</th>              <!-- COLUMNA [ 1 ] -->
                                            <th style="font-size: .9em">PROVEEDOR</th>          <!-- COLUMNA [ 2 ] -->
                                            <th style="font-size: .9em">F DISPERSIÓN</th>       <!-- COLUMNA [ 3 ] -->
                                            <th style="font-size: .9em">F AUTORIZADO</th>       <!-- COLUMNA [ 4 ] -->
                                            <th style="font-size: .9em">EMPRESA</th>            <!-- COLUMNA [ 5 ] -->
                                            <th style="font-size: .9em">DEPARTAMENTO</th>       <!-- COLUMNA [ 6 ] -->
                                            <th style="font-size: .9em">CANTIDAD</th>           <!-- COLUMNA [ 7 ] -->
                                            <th style="font-size: .9em">MONEDA</th>             <!-- COLUMNA [ 8 ] SOLO LO PUEDEN VER "CC, CE, CT, CX" -->
                                            <th style="font-size: .9em">CONVERSIÓN</th>         <!-- COLUMNA [ 9 ] SOLO LO PUEDEN VER "CC, CE, CT, CX" -->
                                            <th style="font-size: .9em">MÉTODO PAGO</th>        <!-- COLUMNA [ 10 ] -->
                                            <th style="font-size: .9em">ESTATUS</th>            <!-- COLUMNA [ 11 ] -->
                                            <th></th>
                                        </tr>  <!-- FIN FECHA: 26-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <!-- INICIO LOADER DE PROGRESO -->
                        <div id="loader-overlay" style="display:none;">
                            <div class="loader-container">
                                <div id="lottie-animation" style="width: 250px; height: 250px; margin: 0 auto;"></div>
                                <p class="loader-text" id="animated-text" style="font-weight: bold;"><b>Generando archivo Excel...</b></p>
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

    var tabla_historial_pagos;
    var total;
    var valor_input = Array( $('#tablahistorialpagos th').length );
    
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
    // informacion de inicio de sesion del usuario en formato JSON
    var idUsuario = '<?php echo $this->session->userdata("inicio_sesion")['id']?>';
    var deptoUsuario = '<?php echo $this->session->userdata("inicio_sesion")['depto']?>';
    var rolUsuario = '<?php echo $this->session->userdata("inicio_sesion")['rol']?>';
    var imagenAnimation = '';
    var timeoutId; // Variable global para guardar el ID del timeout

    $(document).ready(function() {

        // Traemos la fecha actual con libreria moment
        const fechaActual = moment();
        // Como fecha de inicio restamos 4 años a la fecha de cuando se consulta (para dejar un filtro parecido a la version anterior.)
        const fechaDataPickerInicio = moment(fechaActual).startOf('year').format('DD/MM/YYYY');
        
        $('.fechas_filtro').datepicker({
            format: 'dd/mm/yyyy',
            autoclose: true,
            zIndexOffset: 10000, /** FECHA: 30-MAYO-2025 | @author Dante Aldair Guerrero Aldana <coordinador6.desarrollo@ciudadmaderas.com> **/
            orientation: 'bottom auto', /** FECHA: 30-MAYO-2025 | @author Dante Aldair Guerrero Aldana <coordinador6.desarrollo@ciudadmaderas.com> **/
            endDate: new Date()
        });

        // Asigna la fecha predeterminada al campo de entrada
        $('#fecInicial').datepicker('setDate', fechaDataPickerInicio);
        $('#fecInicial').val(fechaDataPickerInicio);

        // Asigna la fecha predeterminada al campo de entrada
        $('#fecFinal').datepicker('setDate', fechaActual.format('DD/MM/YYYY'));
        $('#fecFinal').val(fechaActual.format('DD/MM/YYYY'));

        if (['ADMINISTRACION'].includes(deptoUsuario) && ['309'].includes(idUsuario)) {
            imagenAnimation = ['309'].includes(idUsuario) 
                ? "<?= base_url('img/Animation - gatoGirando.json') ?>"
                : "<?= base_url('img/Animation-descarga.json') ?>";

            // Animación Lottie desde archivo local o URL
            lottie.loadAnimation({
                container: document.getElementById("lottie-animation"),
                renderer: "svg",
                loop: true,
                autoplay: true,
                path: imagenAnimation// Ruta local en tu servidor
            });
        }
    });
   
    $('#fecInicial').on('keyup change', function(){
        var str = $(this).val();
        if(str.length==2 || str.length==5){
            $('#datepicker_fromP').val(str+'/');
        }
    }); 
    
    $('#fecFinal').on('keyup change', function(){
        var str = $(this).val();
        if(str.length==2 || str.length==5){
            $('#fecFinal').val(str+'/');
        }
    }); 

    $("#tablahistorialpagos").ready( function(){

        $('#tablahistorialpagos thead tr:eq(0) th').each( function (i) {

            if( i < $('#tablahistorialpagos thead tr:eq(0) th').length - 1 ){
                var title = $(this).text();                
                $(this).html( '<input type="text" class="form-control" style="font-size: .9em; width: 100%;" data-value="'+title+'" placeholder="'+title+'" />' ); /** FECHA: 07-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                
                $( 'input', this ).on('keyup change', function () {
                    if (tabla_historial_pagos.column(i).search() !== this.value ) {
                        tabla_historial_pagos
                            .column(i)
                            .search( this.value)
                            .draw();
                        var total = 0;
                        var index = tabla_historial_pagos.rows( { selected: true, search: 'applied' } ).indexes();
                        var data = tabla_historial_pagos.rows( index ).data();
                        valor_input[title] = this.value;                        
                        $.each(data, function(i, v){
                            const cantidad = v.conversion; // FECHA: 26-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com>
                            total += parseFloat(cantidad); // FECHA: 26-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com>
                        });
                        $("#myText_1").val( formatMoney(total) );
                    }
                });
            }
            
        });

        $('#tablahistorialpagos').on('xhr.dt', function ( e, settings, json, xhr ) {
            total = 0;
            $.each( json.data,  function(i, v){
                const cantidad = v.conversion; // FECHA: 26-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com>
                total += parseFloat(cantidad); // FECHA: 26-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com>
            });

            $("#myText_1").val( formatMoney(total) );
        });

        // MOSTRAR COLUMNAS A "CC, CE, CT, CX" // INICIO FECHA: 26-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com>
        var userRole = '<?php echo $this->session->userdata("inicio_sesion")['rol'] ?>';
        var columnDefs = [];
        if (['CC', 'CE', 'CT', 'CX'].includes(userRole)) {
            columnDefs.push({
                targets: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11],
                visible: true
            });
        } else {
            columnDefs.push({
                targets: [8, 9],
                visible: false
            });
        }
        // FIN MOSTRAR COLUMNAS A "CC, CE, CT, CX" // INICIO FECHA: 26-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com>

        tabla_historial_pagos = $("#tablahistorialpagos").DataTable({
            dom: 'Brtip',
            "buttons": [
                {
                    text: '<i class="fas fa-search"></i>', // INICIO FECHA: 26-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com>  
                    attr: { class: 'btn btn-default' }, // INICIO FECHA: 26-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com>
                    action: function() {
                    
                        $.ajax({ 
                            "url" : url + "Historial/TablaHPagosAdministrativo",
                            "type": "POST",
                            "data" : {
                                finicial : moment($('#fecInicial').val(), 'DD/MM/YYYY').format('YYYY-MM-DD'),
                                ffinal : moment($('#fecFinal').val(), 'DD/MM/YYYY').format('YYYY-MM-DD')
                            },
                            success: function(result){
                                data = JSON.parse(result);
                                tabla_historial_pagos.clear().draw();
                                tabla_historial_pagos.rows.add(data.data); 
                                tabla_historial_pagos.columns.adjust().draw();

                                let total = 0;
                                $.each(data.data, function(i, v){
                                    console.log(v.cantidad);
                                    
                                    total += parseFloat(v.cantidad);
                                });                                
                                let to1 = formatMoney(total);
                                document.getElementById("myText_1").value = to1;
                            } 
                        });
                    }
                    
                },
                {
                    text: '<i class="fas fa-file-excel"></i> EXPORTAR A EXCEL',
                    action: function(){
                        if (deptoUsuario == 'ADMINISTRACION' && rolUsuario == 'CP') {
                            descargaReporteExcel();
                        }else{
                            $("#elementos_hidden").html("");
                            $('#tablahistorialpagos thead tr:eq(0) input').each( function (i) {                                                            
                                if( valor_input[$(this).data('value')] )
                                    $("#elementos_hidden").append( '<input type="hidden" name="'+$(this).data('value')+'" value="'+valor_input[$(this).data('value')]+'">' )
                            });
                            if( $("#formulario_reporttransferencias").valid() ){
                                $("#formulario_reporttransferencias").submit();
                            }
                        }
                    },
                    attr: {
                        class: 'btn btn-success',
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
                {
                    "width": "5%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.idsolicitud+'</p>'
                    }
                },
                {
                    "width": "8%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.folio+( d.uuid ? '/'+d.uuid : '' )+'</p>'
                    }
                },
                {
                    "width": "15%",
                    "data" : function( d ){
                        return '<p style="font-size: .75em">'+d.nombre+'</p>'
                    }
                },
                {
                    "width": "10%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+( d.fecha_dispersion ? formato_fechaymd(d.fecha_dispersion): "---" ) +'</p>'
                    }
                },
                {
                    "width": "10%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+formato_fechaymd(d.fechaaut)+'</p>'
                    }
                },  
                {
                    "width": "9%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.abrev+'</p>'
                    }
                },
                {
                    "width": "9%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.nomdepto+'</p>'
                    }
                },
                { // INICIO FECHA: 26-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com>
                    "width": "8%",
                     "data": function( d ){
                        const cantidad = ['CC', 'CE', 'CT', 'CX'].includes(userRole) ? d.cantidad : d.conversion;
                        return '<p style="font-size: .8em">$'+formatMoney(cantidad)+'</p>';
                     }
                },
                {
                    "width": "8%",
                     "data": function( d ){
                         return '<p style="font-size: .8em">'+(d.moneda ? d.moneda : 'NA')+'</p>';
                     }
                },
                {
                    "width": "10%",
                     "data": function( d ){
                        if (d.conversion != 0) {
                            return `<p style="font-size: .8em">$${ formatMoney( d.conversion ) } MXN</p>`;
                        }else{
                            return `<mark style="font-size: .7em" class="text-bold">SIN TIPO DE CAMBIO</mark>`;
                        }
                     }
                }, // FIN FECHA: 26-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com>
                {
                    "width": "10%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.metoPago+' '+d.referencia+'</p>'
                    }
                },
                {
                    "width": "10%",
                    "data" : function( d ){

                        switch( d.estatus ){
                            case '0':
                            return '<p style="font-size: .8em">AUTORIZADO POR DG</p> <p style="font-size: .8em"></p>';
                                break;
                            case '1':
                            case '5':
                                return '<p style="font-size: .8em">DISPERSANDO</p> <p style="font-size: .8em"></p>';
                                break;
                            case '11':
                                return '<p style="font-size: .8em">EN ESPERA PARA ENVIAR A DISPERCIÓN</p> <p style="font-size: .8em"></p>';
                                break;
                            case '12':
                                return '<p style="font-size: .8em">PAGO DETENIDO</p> <p style="font-size: .8em"></p>';
                                break;
                            case '15':
                                return '<p style="font-size: .8em">POR CONFIRMAR PAGO </p> <p style="font-size: .8em"></p>';
                                break;
                            case '16':
                                return '<p style="font-size: .8em">PAGO COMPLETO</p> <p style="font-size: .8em"></p>';
                                break;
                            default:
                                return '<p style="font-size: .8em">PROCESANDO PAGO CXP</p> <p style="font-size: .8em"></p>';
                                break;   
                        }
                    }
                },
                {
                    "width": "10%",
                    "data" : function( d ){
                        var tipo_ventana = ( d.nomdepto == "DEVOLUCIONES" || d.nomdepto == "TRASPASO" || d.nomdepto == "TRASPASO OOAM" ) ? "DEV_BASICA" : "SOL";

                        return '<div class="btn-group-vertical"><button type="button" class="btn btn-primary consultar_modal notification" value="'+d.idsolicitud+'" data-value="'+tipo_ventana+'" title="Ver Solicitud"><i class="fas fa-eye"></i>'+(d.visto == 0 ? '<span class="badge">!</span>' : '')+'</button></div>';
                    }
                } 
            ],
            "columnDefs": columnDefs, // FECHA: 26-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com>
            order: [[0, "asc"]],
            bSort: false,
            //"ajax": url + "Historial/TablaHPagosAdministrativo",
           "ajax": { 
                    url :   url + "Historial/TablaHPagosAdministrativo",
                    type: 'POST', 
                    data: function(d){
                        d.finicial = moment($('#fecInicial').val(), 'DD/MM/YYYY').format('YYYY-MM-DD'),
                        d.ffinal= moment($('#fecFinal').val(), 'DD/MM/YYYY').format('YYYY-MM-DD')
                    }
           }
        });

        function formatfech(valor){
                return valor.substring(6,10)+'-'+valor.substring(3,5)+'-'+valor.substring(0,2);
            }

        $('#fecInicial').change( function() { 
           tabla_historial_pagos.draw();

            var total = 0;
            var index = tabla_historial_pagos.rows( { selected: true, search: 'applied' } ).indexes();
            var data = tabla_historial_pagos.rows( index ).data();
            $.each(data, function(i, v){
                const cantidad = v.conversion; // FECHA: 26-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com>
                total += parseFloat(cantidad); // FECHA: 26-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com>
            });
            var to1 = formatMoney(total);
            document.getElementById("myText_1").value = to1;

        });
        
        $('#fecFinal').change( function() { 
           tabla_historial_pagos.draw(); 

            var total = 0;
            var index = tabla_historial_pagos.rows( { selected: true, search: 'applied' } ).indexes();
            var data = tabla_historial_pagos.rows( index ).data();
            $.each(data, function(i, v){
                const cantidad = v.conversion; // FECHA: 26-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com>
                total += parseFloat(cantidad); // FECHA: 26-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com>
            });
            var to1 = formatMoney(total);
            document.getElementById("myText_1").value = to1;

        });
        
        $('.dt-buttons').attr('style','float: right !important; margin-right: 15px;');
    });
    

    $.fn.dataTableExt.afnFiltering.push(
	    function( oSettings, aData, iDataIndex ) {
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
   
    $(window).resize(function(){ /** FECHA: 07-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
        tabla_historial_pagos.columns.adjust().draw(false);
    });

    $('.sidebar-toggle').click(function() {
        setTimeout(function() {
            // 1. Ajustar columnas
            tabla_historial_pagos.columns.adjust();
            // 2. Forzar actualización de encabezados
            var headerCellsTableHistorialPagos = $('#tablahistorialpagos thead th');
            headerCellsTableHistorialPagos.each(function() {
                var newWidth = $(this).width(); // Obtener ancho actualizado
                $(this).css('width', newWidth + 'px'); // Aplicarlo
            });
            // 3. Redibujar (sin resetear paginación)
            tabla_historial_pagos.draw(false);
        }, 300); // Esperar a que termine la animación del sidebar
    }); /** FECHA: 07-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/

    /**
     * Inicia el proceso de generación y descarga del reporte Excel.
     * Valida fechas, manda info. a google cloud function, genera reporte, descarga reporte, elimina archivo temporal y maneja respuestas del backend.
     *
     * @functions: descargaReporteExcel, mostrarLoader, ocultarLoader, mostrarLetraPorLetra, limpiarErrores, validarFechas, construirFormData, manejarRespuesta, obtenerNombreArchivo, descargarBlob, procesarRespuestaJson, manejarErrorGlobal
     * @author Dante Aldair Guerrero Aldana <coordinador6.desarrollo@ciudadmaderas.com>
     * Fecha de Modificacion: 2025-07-01
     * @returns {Promise<void>} No devuelve valor directamente, pero controla todo el flujo.
    */
    async function descargaReporteExcel() {
        try {
            mostrarLoader();
            limpiarErrores();

            if (!validarFechas()) {
                ocultarLoader();
                return;
            }

            const formData = construirFormData();
            const response = await fetch('<?= site_url("Reportes/reporte_historial_pagos") ?>', {
                method: 'POST',
                body: formData
            });

            await manejarRespuesta(response);

        } catch (error) {
            manejarErrorGlobal(error);
        } finally {
            ocultarLoader();
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
        clearTimeout(timeoutId);
    }

    function mostrarLetraPorLetra() {
        const fraseActual = frases[indexFrase];
        animatedText.textContent = fraseActual.slice(0, indexLetra);
        indexLetra++;
        if (indexLetra > fraseActual.length) {
            timeoutId = setTimeout(() => {
                indexFrase = (indexFrase + 1) % frases.length;
                indexLetra = 0;
                timeoutId = setTimeout(mostrarLetraPorLetra, 100);
            }, 2000); // Tiempo visible antes de cambiar
        } else {
            timeoutId = setTimeout(mostrarLetraPorLetra, 50);
        }
    }


    // Limpia errores visuales
    function limpiarErrores() {
        $('.has-error').removeClass('has-error');
        $('.help-block').hide();
    }

    // Valida fechas requeridas
    function validarFechas() {
        let valido = true;
        const fechaInicio = $('#fecInicial').val();
        const fechaFin = $('#fecFinal').val();

        if (!fechaInicio) {
            $('#fecInicial').closest('.form-group').addClass('has-error');
            $('#error-fecha-inicio').show();
            valido = false;
        }

        if (!fechaFin) {
            $('#fecFinal').closest('.form-group').addClass('has-error');
            $('#error-fecha-fin').show();
            valido = false;
        }

        return valido;
    }

    // Arma FormData con filtros de tabla y fechas
    function construirFormData() {
        const formData = new FormData();
        const fechaInicio = moment($('#fecInicial').val(), 'DD/MM/YYYY').format('YYYY-MM-DD');
        const fechaFin = moment($('#fecFinal').val(), 'DD/MM/YYYY').format('YYYY-MM-DD');

        formData.append('fechaInicial', fechaInicio);
        formData.append('fechaFinal', fechaFin);

        // Agregamos filtros de cabeceras si es que exite alguno
        $('#tablahistorialpagos thead tr:eq(0) input').each(function () {
            const key = $(this).data('value');
            if (valor_input.hasOwnProperty(key)) {
                formData.append(key, valor_input[key]);
            }
        });

        return formData;
    }

    /**
     * Procesa respuesta del backend (archivo o JSON).
     * Mediante la consulta a la BD generamos un archivo NDJSON, para la manipulacion de datos muy grandes.
     * 
     * @author Dante Aldair Guerrero Aldana <coordinador6.desarrollo@ciudadmaderas.com>
     * @param response $response Informacion obtenida desde el BackEnd, es un Json que contiene ruta de archivo NDJSON, ruta de function de gooogle Cloud y nombre de archivo que tendra el excel
     * @throws Si existe algún problema al momento de la generacion de excel 
    */
    async function manejarRespuesta(response) {
        const contentType = response.headers.get('Content-Type') || '';
        const contentDisposition = response.headers.get('Content-Disposition') || '';
        const esArchivo = contentType.includes('application/vnd.openxmlformats') || contentDisposition.includes('attachment');

        if (esArchivo) {
            const blob = await response.blob();
            const filename = obtenerNombreArchivo(contentDisposition, 'reporte_default.xlsx');
            descargarBlob(blob, filename);
        } else {
            const texto = await response.text();
            try {
                const json = JSON.parse(texto);
                await procesarRespuestaJson(json);
            } catch {
                console.error('Respuesta inválida:', texto);
                alert("Error inesperado del servidor.");
            }
        }
    }

    // Extrae nombre de archivo desde Content-Disposition
    function obtenerNombreArchivo(header, defaultName) {
        const match = header.match(/filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/);
        return match && match[1] ? match[1].replace(/['"]/g, '') : defaultName;
    }

    // Descarga el archivo desde Blob
    function descargarBlob(blob, filename) {
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = filename;
        document.body.appendChild(a);
        a.click();
        a.remove();
        URL.revokeObjectURL(url);
    }

    // Procesa la respuesta JSON con datos de Google Cloud
    async function procesarRespuestaJson(json) {
        if (!json.datosEnviarGoogle || !json.urlGoogleCloud || !json.nombreArchivo) {
            console.log("Respuesta JSON sin datos válidos:", json);
            return;
        }

        const res = await fetch(json.urlGoogleCloud, {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({
                urlJson: json.datosEnviarGoogle,
                nombreArchivo: json.nombreArchivo
            })
        });

        if (!res.ok) {
            const errJson = await res.json().catch(() => ({}));
            throw new Error(errJson.mensaje || 'Error al generar Excel desde la nube');
        }

        const blob = await res.blob();
        descargarBlob(blob, json.nombreArchivo);

        // Solicitar eliminación del archivo NDJSON
        const formEliminar = new FormData();
        formEliminar.append('nomArchivoNdJson', json.datosEnviarGoogle.split("/").pop());
        formEliminar.append('rutaArchivo', 'REPORTES_CXP');
        await fetch('<?= site_url("Reportes/eliminarArchivo") ?>', {
            method: 'POST',
            body: formEliminar
        });
    }

    // Manejo de errores global
    function manejarErrorGlobal(error) {
        console.error('Error global:', error);
        alert('Ocurrió un error inesperado. Contacta al administrador.');
    }
    
</script>

<?php
    require("footer.php");
?>