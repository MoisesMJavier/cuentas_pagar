<?php
require("head.php");
require("menu_navegador.php");
$usuario = $_SESSION;
echo '<script>';
echo 'var usuario = ' . json_encode($usuario) . ';';
echo '</script>';
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
                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                             <li class="active"><a id="#historial_activas_prov" data-toggle="tab" href="#historial_activas" role="tab" aria-controls="#home" aria-selected="true">ACTIVAS PAGO PROVEEDOR</a></li>
                             <li><a id="#historial_activas_cch" data-toggle="tab" href="#historial_activas" role="tab" aria-controls="#home" aria-selected="true">ACTIVAS PAGO CAJA CHICA</a></li>
                             <li><a id="#historial_activas_tdc" data-toggle="tab" href="#historial_activas" role="tab" aria-controls="#home" aria-selected="true">ACTIVAS PAGO TDC</a></li>
                             <li><a id="#historial_activas_viaticos" data-toggle="tab"data-value="pago_viaticos" href="#historial_activas" aria-controls="#home" aria-selected="true">ACTIVAS VIÁTICOS</a></li>
                             <li><a id="#historial_canceladas" data-toggle="tab" href="#historial_canceladas" role="tab" aria-controls="historial_canceladas" aria-selected="false">CANCELADAS </a></li>
                             <li><a id="#historial_pausadas" data-toggle="tab" href="#historial_pausadas" role="tab" aria-controls="historial_pausadas" aria-selected="false">PAUSADAS</a></li>
                         </ul>
                        </div>
                     <div class="tab-content">
                        <div class="active tab-pane" id="historial_activas">
                            <div class="row">
                                <div class="col-lg-12">
                                    <!-- <form id="formulario_historial_cp" autocomplete="off" action="<?= site_url("Reportes/reporte_historial_solicitudes") ?>" method="post" target="_blank"> -->
                                        <div class="col-lg-2">
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
                                        <div id="elementos_hidden"></div>
                                    <!-- </form> -->
                                    <div class="col-lg-2">
                                        <div class="input-group-addon" style="padding: 4px;">
                                            <h4 style="padding: 0; margin: 0;"><label>&nbsp;Total: $<input style="text-align: right; border-bottom: none; border-top: none; border-right: none;  border-left: none; background: white;" disabled="disabled" readonly="readonly" type="text"  name="myText_1" id="myText_1"></label></h4>
                                        </div>
                                    </div> 
                                    <table class="table table-striped" id="historial_sol_activas">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th style="font-size: .8em">#</th>
                                                <th style="font-size: .8em">EMPRESA</th>
                                                <th style="font-size: .8em">FOLIO</th>
                                                <th style="font-size: .8em">FECHA CAPTURA</th>
                                                <th style="font-size: .8em">FECHA AUTORIZACIÓN</th>
                                                <th style="font-size: .8em">FECHA FACT</th>
                                                <th style="font-size: .8em">PROVEEDOR</th> 
                                                <th style="font-size: .8em">DEPARTAMENTO</th>
                                                <th style="font-size: .8em">CANTIDAD</th>
                                                <th style="font-size: .8em">PAGADO</th>
                                                <th style="font-size: .8em">SALDO</th>
                                                <th style="font-size: .8em">ESTATUS</th>
                                                <th style="font-size: .8em">GASTO</th>
                                                <th style="font-size: .8em">FORMA PAGO</th>
                                                <th style="font-size: .8em" id='estado'>ESTADO</th>
                                                <th></th>
                                                <th style="font-size: .8em">TITULAR TARJETA</th>         <!-- COLUMAN 18 /** Cambios TDC Reportes | FECHA: 17-Abril-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com>  **/-->
                                                <th style="font-size: .8em">RESPONSABLE REEMBOLSO</th>   <!-- COLUMNA 19 /** FECHA: 29-Abril-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com>  **/-->
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                            <div class="tab-pane fade" id="historial_canceladas">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="col-md-6">
                                            <div class="input-group-addon" style="padding: 4px;">
                                                <h4 style="padding: 0; margin: 0;"><label>&nbsp;Total: $<input style="text-align: right; border-bottom: none; border-top: none; border-right: none;  border-left: none; background: white;" disabled="disabled" readonly="readonly" type="text"  name="myText_2" id="myText_2"></label></h4>
                                            </div>
                                        </div>
                                        <table class="table table-striped" id="historial_sol_canceladas">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th style="font-size: .9em">#</th>
                                                    <th style="font-size: .9em">EMPRESA</th>
                                                    <th style="font-size: .9em">FOLIO</th>
                                                    <th style="font-size: .9em">FECHA CAPTURA</th>
                                                    <th style="font-size: .9em">FECHA AUTORIZACIÓN</th>
                                                    <th style="font-size: .9em">FECHA FACT.</th>
                                                    <th style="font-size: .9em">PROVEEDOR</th>                                                           
                                                    <th style="font-size: .9em">DEPARTAMENTO</th>
                                                    <th style="font-size: .9em">CANTIDAD</th>
                                                    <th style="font-size: .9em">PAGADO</th>
                                                    <th style="font-size: .9em">SALDO</th>
                                                    <th style="font-size: .9em">ESTATUS</th>
                                                    <th style="font-size: .9em">GASTO</th>
                                                    <th style="font-size: .9em">CON FACTURA</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div> <!--End solicitudes finalizadas--> 
                        <div class="tab-pane fade" id="historial_pausadas">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="col-md-6">
                                        <div class="input-group-addon" style="padding: 4px;">
                                            <h4 style="padding: 0; margin: 0;"><label>&nbsp;Total: $<input style="text-align: right; border-bottom: none; border-top: none; border-right: none;  border-left: none; background: white;" disabled="disabled" readonly="readonly" type="text"  name="myText_3" id="myText_3"></label></h4>
                                                    </div>
                                                </div>
                                                <table class="table table-striped" id="historial_sol_pausadas">
                                                    <thead>
                                                        <tr>
                                                            <th></th>
                                                            <th style="font-size: .9em">#</th>
                                                            <th style="font-size: .9em">EMPRESA</th>
                                                            <th style="font-size: .9em">FOLIO</th>
                                                            <th style="font-size: .9em">FECHA CAPTURA</th>
                                                            <th style="font-size: .9em">FECHA AUTORIZACIÓN</th>
                                                            <th style="font-size: .9em">FECHA FACT.</th>
                                                            <th style="font-size: .9em">PROVEEDOR</th>                                     
                                                            <th style="font-size: .9em">DEPARTAMENTO</th>
                                                            <th style="font-size: .9em">CANTIDAD</th>
                                                            <th style="font-size: .9em">PAGADO</th>
                                                            <th style="font-size: .9em">SALDO</th>
                                                            <th style="font-size: .9em">ESTATUS</th>
                                                            <th style="font-size: .9em">GASTO</th>
                                                            <th style="font-size: .9em">CON FACTURA</th>
                                                            <th></th>
                                                            <th style="font-size: .9em">JUSTIFICACIÓN</th>
                                                        </tr>
                                                    </thead>
                                               </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div><!--End tab content-->
                            <!-- INICIO LOADER DE PROGRESO -->
                            <div id="loader-overlay" style="display:none;">
                                <div class="loader-container">
                                    <div id="lottie-animation" style="width: 160px; height: 160px; margin: 0 auto;"></div>
                                    <p class="loader-text" id="animated-text">Generando archivo Excel...</p>
                                </div>
                            </div>
                            <!-- FIN LOADER DE PROGRESO -->
                        </div><!--end box-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade modal-alertas" id="modal_opciones" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-maroon">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">SELECCIONA OPCIÓN</h4>
            </div>  
            <form method="post" id="formulario_opciones">
                <div class="modal-body"></div>
            </form>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>
<!-- MODAL QUE CONTROLA LA CARGA DE NUEVAS FACTURAS -->
<div id="factura_complemento" class="modal fade modal-alertas" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">COMPLEMENTO</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <form id="ncomplemento_facturas">
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
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-check">
                                        <input type="checkbox" name="validar_checkbox" class="form-check-input validar_checkbox" id="validar_checkbox">
                                        <label class="form-check-label validar_checkbox" for="validar_checkbox">Cargar sin validar</label>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-check">
                                        <input type="checkbox" name="cancelar_checkbox" class="form-check-input validar_checkbox" id="cancelar_checkbox">
                                        <label class="form-check-label validar_checkbox" for="cancelar_checkbox">Cancelar facturas</label>
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

<!-- MODAL QUE MANIPULARÁ LOS ABONOS DE LAS SOLICITUDES DE PAGO -->
<div class="modal fade bd-example-modal-lg" role="dialog" id="modal_abonos">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header bg-green">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">ABONOS DE LA SOLICITUD # <b></b></h4>
        </div> 
        <div class="modal-body">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <h6 id="idpago_h6"></h6>
                    <form id="form_abonos">
                        <input type="hidden" name="idpago" id="idpago_abono" value="0">
                        <input type="hidden" name="idsolicitud" id="idsolicitud_abono" value="0">
                        <input type="hidden" name="accion" id="accion_abono" value="reg">
                        <input type="hidden" name="abono_old" id="abono_old" value="0">
                        <div class="row align-items-center">
                            <div class="col-md-6"><b>Cantidad a abonar:</b><input type="text" class="form-control dinero" placeholder="$" name="cantidad_abonada" id="cantidad_abonada" required></input></div>
                            <div class="col-md-6"><label>Cantidad restante:</label> <input type="text" name="cantidadxabonar" id="cantidadxabonar" class="form-control" readonly/></div>
                        </div>
                        <div class="row align-items-center">
                            <div class="col-md-6"><b>Justificación:</b><textarea class="form-control" name="razon_abono" id="razon_abono" required onKeyPress="if(this.value.length==255) return false;return check(event);"></textarea></div>
                            <div class="col-md-6">
                            <br>
                                <div class="btn-group">
                                <button type="submit" id="bt_abonoaccion" class="btn btn-success btn-sm">REGISTRAR</button>
                                <button type="button" id="bt_abonocancel" class="btn btn-warning btn-sm" onclick="acciones_abono('reg',0,0,0)">CANCELAR<br>EDICIÓN</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-12">
                    <table class="table table-striped table-hover table-sm" id="tabla_abonos" style="width:100%">
                        <thead>
                            <tr>
                            <th style="font-size: .9em">#</th>
                            <th style="font-size: .9em">Cantidad</th>
                            <th style="font-size: .9em">Realizado por</th>
                            <th style="font-size: .9em"></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <div class="btn-group"><button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">CERRAR</button></div>
        </div>
    </div>
  </div>
</div>
<script>
    var tabla_activa = '#historial_activas_prov';
    var tabla_activas;
    var tabla_abonos;
    var tr_global;
    var valor_input = Array( $('#historial_sol_activas th').length );
    var no_espera = ['TRASPASO','DEVOLUCIONES','CESION OOAM','RESCISION OOAM','DEVOLUCION OOAM','TRASPASO OOAM','DEVOLUCION DOM OOAM','INFORMATIVA','INFORMATIVA CERO'];
    var depto_excep_proyecto = ['CONSTRUCCION', 'JARDINERIA', 'OOAM', 'OOAM TECNICO', 'OOAM ADMINISTRATIVO'];
    var saldo_abono=0;

    var form_factoraje='\
        <form id="formulario_fact" method="post">\
            <div class="row">\
                <div class="col-lg-12 form-group">\
                    <label>TIPO DE MOVIMIENTO<span class="text-danger">*</span></label>\
                    <select class="form-control" name="tipo_factoraje" required>\
                        <option value="">Seleccione una opción</option>\
                        <option value="FACT BAJIO">FACT BAJIO</option>\
                        <option value="FACT BANREGIO">FACT BANREGIO</option>\
                    </select>\
                </div>\
                <div class="col-lg-12 form-group">\
                    <label>FECHA DE PUBLICACION<span class="text-danger">*</span></label>\
                    <input type="date" class="form-control" name="fecha_publicacion" required>\
                </div>\
                <div class="col-lg-12 form-group">\
                    <label>FECHA DE PAGO<span class="text-danger">*</span></label>\
                    <input type="date" class="form-control" name="dias_factoraje" required>\
                </div>\
            </div>\
            <div class="row">\
                <div class="col-lg-12 form-group">\
                    <label>COMENTARIO<small class="text-danger">(opcional)</small></label>\
                    <textarea class="form-control" rows="5" name="comentario_especial" maxlength="50"></textarea>\
                </div>\
            </div>\
            <div class="row">\
                <div class="col-lg-8 col-lg-offset-2 form-group">\
                    <button type="submit" class="btn btn-block btn-success">AGREGAR</button>\
                </div>\
            </div>\
        </form>\
        <script>\
        $("#formulario_fact").submit( function(e) {\
            e.preventDefault();\
        }).validate({\
            submitHandler: function( form ) {\
                if( confirm("¿ESTÁ DE ACUERDO EN ENVIAR LA SOLICITUD #"+index_solicitud+" A FACTORAJE?") ){\
                    var fd = new FormData( $(form)[0] );\
                    fd.append("idsolicitud", index_solicitud);\
                    var data = enviar_post(fd,"Solicitante/factoraje_enviar");\
                    if(data.resultado){\
                        tabla_activas.ajax.url( url +"Historial/TablaHistorialSolicitudesA" ).load();\
                        $("#modal_opciones").modal( \'toggle\' );\
                    }\
                }\
            }\
        });\
        <\/script>\
    ';

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

    $('.fechas_filtro').datepicker({
        format: 'dd/mm/yyyy',
        autoclose: true,
        orientation: 'bottom auto', /** Cambios TDC Reportes | FECHA: 17-Abril-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com>  **/
        //endDate: '-0d'
        zIndexOffset: 10000 /** Ajuste datepicker | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> | FECHA: 05-ABRIL-2024 **/
    });

    $(document).ready(function() {
        const fechaActual = new Date().toLocaleDateString('es-ES', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric'
        });
        
        // Asigna la fecha predeterminada al campo de entrada
        const AñoPasado = new Date(new Date().getFullYear() -1, 0, 1);
        // Configura el datepicker inicial
        $('#fecInicial').datepicker({
            dateFormat: 'dd/mm/yyyy', // Formato de fecha deseado
            // defaultDate: '01/01/'+new Date().getFullYear(), // Establece la fecha predeterminada
            onSelect: function(dateText) {
                $(this).val(dateText); // Establece el valor del campo de entrada con la fecha seleccionada
            }
        }).datepicker('setDate', AñoPasado);
        
        // Asigna la fecha predeterminada al campo de entrada
        // $('#fecInicial').datepicker('setDate', '01/01/'+new Date().getFullYear());

        // Configura el datepicker inicial
        $('#fecFinal').datepicker({
            dateFormat: 'dd/mm/yyyy', // Formato de fecha deseado
            defaultDate: fechaActual, // Establece la fecha predeterminada
            onSelect: function(dateText) {
                $(this).val(dateText); // Establece el valor del campo de entrada con la fecha seleccionada
            }
        });
        // Asigna la fecha predeterminada al campo de entrada
        $('#fecFinal').datepicker('setDate', fechaActual);
        $('.tab-content').data('fechas', {fechaI: $("#fecInicial").val(), fechaF: $("#fecFinal").val()}) //Guardamos las fechas de los datepickers dentro de div con la clase tab-content

        // Animación Lottie desde archivo local o URL
        lottie.loadAnimation({
            container: document.getElementById("lottie-animation"),
            renderer: "svg",
            loop: true,
            autoplay: true,
            path: "<?= base_url('img/Animation-descarga.json') ?>" // Ruta local en tu servidor
        });

    });

    // $('#fecInicial').val(moment().startOf('year').calendar());//@uthor Efrain Martinez Muñoz se actualizo el rango de fechas 
    // $('#fecFinal').val(moment().format('L'));//para que solo se carguen los valores de principo de año a la fecha actual.
    

    $('#fecInicial').on('keyup change', function(){
        var str = $(this).val();
        if(str.length==2 || str.length==5){
            $('#fecInicial').val(str+'/');
        }
    }); $('#fecFinal').on('keyup change', function(){
        var str = $(this).val();
        if(str.length==2 || str.length==5){
            $('#fecFinal').val(str+'/');
        }
    }); 

    

    $('[data-toggle="tab"]').click( function(e) {
        $("#tipo_reporte").html("");
        // $('#fecInicial').val(moment().startOf('year').calendar());//@uthor Efrain Martinez Muñoz se actualizo el rango de las fechas
        // $('#fecFinal').val(moment().format('L'));//para que solo se cargue los valores de principio de año a la fecha actual.
        //$(".fechas_filtro ").val("");
        tabla_activa = $(this).attr('id');
        switch( $(this).attr('id') ){
            case '#historial_activas_prov':
                loadTablaActivas("Historial/TablaHistorialSolicitudesA");
                $("#tipo_reporte").append(`<input type="hidden" name="tipo_reporte" value="${($(this).attr('id')).replace(/[#]/g,'')}">`);
                break;
            case '#historial_activas_cch':
                loadTablaActivas("Historial/TablaHistorialSolicitudesB");
                $("#tipo_reporte").append(`<input type="hidden" name="tipo_reporte" value="${($(this).attr('id')).replace(/[#]/g,'')}">`);
                break;
            case '#historial_activas_tdc':
                loadTablaActivas("Historial/TablaHistorialSolicitudesTDC");
                $("#tipo_reporte").append(`<input type="hidden" name="tipo_reporte" value="${($(this).attr('id')).replace(/[#]/g,'')}">`);
                break;
            case '#historial_canceladas':
                tabla_canceladas.ajax.url( url +"Historial/TablaHistorialSolicitudesC" ).load();
                break;
            case '#historial_pausadas':
                tabla_pausadas.ajax.url( url +"Historial/TablaHistorialSolicitudesP" ).load();
                break;
            case '#historial_activas_viaticos':
                loadTablaActivas("Historial/TablaHistorialSolicitudesV");
                break;
        }
        $('.tab-content').data('fechas', {fechaI: $("#fecInicial").val(), fechaF: $("#fecFinal").val()}) //Guardamos las fechas de los datepickers dentro de div con la clase tab-content al momento de hacer click en las pestañas
    });


    function loadTablaActivas(link){    
         $.ajax({ 
            "url" : url + link,
            "dataType": "json",
            "type": "POST",
            "data" : {
                finicial : formatfech($('#fecInicial').val()),
                ffinal : formatfech($('#fecFinal').val())
            },
            success: function(data){
                actualizaTotal(data);
                tabla_activas.clear().draw();
                tabla_activas.rows.add(data.data); 
                tabla_activas.columns.adjust().draw();
            },
            error: function (xhr, status, error) {
                console.log(xhr.responseText);
                console.log(status);
                console.log(error);
            }
        });
    }

    function actualizaTotal(data) {  
        tabla_activas.clear().draw();
        tabla_activas.rows.add(data.data); 
        tabla_activas.columns.adjust().draw();  
        var total = 0;
        var index = tabla_activas.rows( { selected: true, search: 'applied' } ).indexes();
        var data = tabla_activas.rows( index ).data();
        $.each(data, function(i, v){
            total += parseFloat(v.cantidad);
        });
        var to1 = formatMoney(total);
        document.getElementById("myText_1").value = to1;
    }


    function formatfech(valor){
                return valor.substring(6,10)+'-'+valor.substring(3,5)+'-'+valor.substring(0,2);
            }

    $("#historial_sol_activas").ready( function(){
        $('#historial_sol_activas thead tr:eq(0) th').each( function (i) {
            if( i != 0 && i != 16){
                var title = $(this).text();
                $(this).html( '<input type="text" id="t_'+title+'" class="form-control" style="font-size: .9em; width: 100%;" data-value="'+title+'" placeholder="'+title+'" />' );

                $( 'input', this ).on('keyup change', function () {
                    if (tabla_activas.column(i).search() !== this.value ) {
                        tabla_activas
                        .column(i)
                        .search( this.value)
                        .draw();
                        
                        valor_input[title] = this.value;

                        var total = 0;
                        var index = tabla_activas.rows( { selected: true, search: 'applied' } ).indexes();
                        var data = tabla_activas.rows( index ).data();
                        $.each(data, function(i, v){
                           total += parseFloat(v.cantidad);
                       });
                        var to1 = formatMoney(total);
                        document.getElementById("myText_1").value = to1;
                    }
                });
            }
        });
        
        $('#historial_sol_activas').on('xhr.dt', function ( e, settings, json, xhr ) {
            var total = 0;
            $.each( json.data,  function(i, v){
                total += parseFloat(v.cantidad);
                // console.log((typeof(v.cantidad) != 'string')?+' '+v.idsolicitud+' val='+v.cantidad: '');
            });
            document.getElementById("myText_1").value =  formatMoney(total);
        });

        tabla_activas = $("#historial_sol_activas").DataTable({
            dom: 'Brtip',
            "buttons": [
                {
                    text: '<i class="fas fa-search"></i>&nbsp;&nbsp',
                   
                    attr: { class: 'btn' },

                    action: function() {
                    
                        /**
                         * Inicio 11 de ocubre de 2024
                         * Se actualizo la función del action del botón de buscar ya que siempre cargaba los datos de PROVEEDORES y no tomaba en cuenta la pestaña en la que el usuario se encontraba.
                         * Ademas marcaba un error en el value ya que el parametro que se estaba pasando estaba vacio. 
                         */
                        $("#tipo_reporte").html("");
                            switch( $('.nav-tabs .active a').attr('id') ){
                                case '#historial_activas_prov':
                                    loadTablaActivas("Historial/TablaHistorialSolicitudesA");
                                    $("#tipo_reporte").append(`<input type="hidden" name="tipo_reporte" value="${$('.nav-tabs .active a').attr('id').replace(/[#]/g,'')}">`);
                                    break;
                                case '#historial_activas_cch':
                                    loadTablaActivas("Historial/TablaHistorialSolicitudesB");
                                    $("#tipo_reporte").append(`<input type="hidden" name="tipo_reporte" value="${$('.nav-tabs .active a').attr('id').replace(/[#]/g,'')}">`);
                                    break;
                                case '#historial_activas_tdc':
                                    loadTablaActivas("Historial/TablaHistorialSolicitudesTDC");
                                    $("#tipo_reporte").append(`<input type="hidden" name="tipo_reporte" value="${$('.nav-tabs .active a').attr('id').replace(/[#]/g,'')}">`);
                                    break;
                            };
                        /**
                         * Fin 11/08/2024
                         */
                    }
                    
                },
                {
                    text: '<i class="fas fa-file-excel"></i> EXPORTAR',
                    messageTop: "Descarga de Excel",
                    attr: {
                        class: 'btn btn-success',
                        id: 'exportarExcel'
                    },
                    action: function(){
                        tipo_reporte = $('.nav-tabs li.active a').attr('id');
                        descargaReporteExcel();
                    }
                },
                {
                    text: '<i class="fas fa-file-excel"></i> AUXILIAR DE AUTORIZACIÓN',
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
            "bLengthChange": true,
            "scrollX": true,
            "bInfo": false,
            "searching": true,
            "deferRender": true,
            "columns": [
                {
                    "className": 'details-control',
                    "orderable": false,
                    "data" : null,
                    "defaultContent": '<i class="fas animacion fa-caret-right"></i>'
                },
                {
                    "orderable": false,
                    "width": "5%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.idsolicitud+'</p>'
                    }
                },
                {
                    "orderable": false,
                    "width": "7%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.abrev+'</p>'
                    }
                },
                {
                    "orderable": false,
                    "width": "7%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+(d.folio ? d.folio : "SF")+'</p>'
                    }
                },
                {
                    "orderable": false,
                    "width": "8%",
                    "data" : function( d ){
    
                        return '<p style="font-size: .8em">'+(d.feccrea ? formato_fechaymd(d.feccrea) : "" )+'</p>'
    
                    }
                },
                {
                    "orderable": false,
                    "width": "8%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+( d.fecha_autorizacion ? formato_fechaymd(d.fecha_autorizacion) : "-" )+'</p>'
                    }
                },
                {
                    "orderable": false,
                    "width": "8%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+( d.fecelab ? formato_fechaymd(d.fecelab) : "" )+'</p>'
                    }
                },
                {
                    "orderable": false,
                    "width": "8%",
                    "data" : function( d ){
                        return '<p style="font-size: .7em">'+d.nombre+'</p>'
                    }
                },
                {
                    "orderable": false,
                    "width": "8%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.nomdepto+'</p>'+((d.programado > 0)? '<p style="font-size: .8em"><small class="label pull-center bg-blue">PROGRAMADO</small></p>': '')
                    }
                },
                {
                    "orderable": false,
                    "width": "7%",
                    "data": function( d ){

                        return '<p style="font-size: .8em">'+formatMoney(  d.cantidad )+'</p>';
                    }
                },
                {
                    "orderable": false,
                    "width": "7%",
                    "data": function( d ){
                        return '<p style="font-size: .8em">'+formatMoney( d.pagado )+'</p>';
                    }
                },
                {
                    "orderable": false,
                    "data": function( d ){
                        return '<p style="font-size: .8em">'+formatMoney( d.cantidad - d.pagado )+'</p>';
                    }
                },
                {
                    "orderable": false,
                    "width": "7%",
                    "data" : function( d ){

                        if( d.caja_chica && d.caja_chica == "1" && d.etapa == 'Revisión Cuentas Por Pagar' ){
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
                            '<p style="font-size: .8em">'+dateToDMY(fec)+'</p>'
                        /*
                        }else if( d.idetapa > 9 && d.idetapa < 20 && d.idprovision == null ){
                            return '<p style="font-size: .7em">FACTURA PAGADA, EN ESPERA DE PROVISION</p>'; 
                        */
                        }else if(d.etapa_sol == 10 && (d.tendrafac != 1 || d.tendrafac == null || d.tendrafac == '') ){
                            return '<p style="font-size: .7em">PAGO COMPLETO</p>';
                        }else{
                            return '<p style="font-size: .7em">'+d.etapa+'</p>';
                        }
                    }
                },
                {
                    "orderable": false,
                    "width": "7%",
                    "data" : function( d ){

                        if( d.caja_chica == 1 )
                            return '<p style="font-size: .8em">CAJA CHICA</p>'; 

                        if( d.caja_chica == 2 )
                            return '<p style="font-size: .8em">TARJETA DE CRÉDITO</p>';
                        else

                            return '<p style="font-size: .8em">PAGO PROVEEDOR</p>';

                    }
                },
                {
                    "orderable": false,
                    "width": "7%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em"><small class="label pull-center bg-blue">'+d.metoPago+'</small></p>'
                    }
                },
                {
                    "orderable": false,
                    "width": "14%",
                    "data": function (d) {
                        
                        //console.log(usuario['inicio_sesion']['id']);
                        if ((tabla_activa == '#historial_activas_cch') && (usuario['inicio_sesion']['id'] == '2367' || usuario['inicio_sesion']['id'] == '2637' || usuario['inicio_sesion']['id'] == '2673' || usuario['inicio_sesion']['id'] == '2692')) {
                            
                            $('#t_ESTADO').show();
                            if(d.estado == 0)
                                return '<p style="font-size: .8em"><small class="label pull-center bg-green">' + 'ACTIVO' + '</small></p>';
                            else
                                return '<p style="font-size: .8em"><small class="label pull-center bg-red">' + 'FUERA DE TIEMPO' + '</small></p>';
                        } else {
                            $('#t_ESTADO').hide();
                            return ''; 
                        }
                    }
                },
                {
                    "orderable": false,
                    "width": "14%",
                    "data": function( d ){
                        
                        var tipo_ventana = no_espera.includes( d.nomdepto ) ? "DEV_BASICA" : "SOL";

                        opciones = '<div class="btn-group-vertical" role="group">';
                        
                        opciones += '<button type="button" class="btn btn-primary btn-sm consultar_modal notification" value="'+d.idsolicitud+'" data-value="'+tipo_ventana+'" title="Ver Solicitud"><i class="fas fa-eye"></i>'+(d.visto == 0 ? '<span class="badge">!</span>' : '')+'</button>';
                        //Fecha : 12-Agosto-2025 @author Efrain Martinez programdor.analista38@ciudadmaderas.com
                        //Se muestra el boton caja_chica para caja chica, TDC y viáticos

                        if( d.caja_chica == 1 || d.caja_chica == 2 || d.caja_chica == 4){
                            opciones += '<button type="button" style="margin-bottom: 5px;"class="btn bg-maroon btn-sm btn-caja_chica"  data-value="d.caja_chica" value="'+d.idsolicitud+'" title="Más opciones"><i class="fa fa-plus-circle"></i></button>';
                        }else{
                            var idus='<?= $this->session->userdata("inicio_sesion")['id'];?>';
                            if(['257'].includes(idus)){
                                // if(d.etapa == 'PAGO AUTORIZADO' || d.etapa == 'ESPERA AUTORIZACIÓN DE PAGO DG')
                                    opciones += '<button type="button" style="margin-bottom: 5px;"class="btn bg-maroon btn-sm btn-masopcionestercero" id="'+idus+'" data-idetapa="'+(d.idetapa)+'" data-value="'+(d.cantidad-d.pagado)+'" value="'+d.idsolicitud+'" title="Más opciones"><i class="fa fa-plus-circle"></i></button>';
                                /*else{
                                    switch( d.idetapa ){
                                        case '2':
                                        case '5':
                                            opciones += '<button type="button" style="margin-bottom: 5px;"class="btn bg-maroon btn-sm btn-masopciones" data-idetapa="'+(d.idetapa)+'" data-value="'+(d.cantidad-d.pagado)+'" value="'+d.idsolicitud+'" title="Más opciones"><i class="fa fa-plus-circle"></i></button>';
                                            break;
                                        default:
                                            opciones += '<button type="button" style="margin-bottom: 5px;"class="btn bg-maroon btn-sm btn-masopcionessegundo" data-idetapa="'+(d.idetapa)+'" data-value="'+(d.cantidad-d.pagado)+'" value="'+d.idsolicitud+'" title="Más opciones"><i class="fa fa-plus-circle"></i></button>';
                                            break;
                                    }
                                }*/
                            }else{
                                switch( d.idetapa ){
                                    case '2':
                                    case '5':
                                        opciones += '<button type="button" style="margin-bottom: 5px;"class="btn bg-maroon btn-sm btn-masopciones" data-idetapa="'+(d.idetapa)+'" data-value="'+(d.cantidad-d.pagado)+'" value="'+d.idsolicitud+'" title="Más opciones"><i class="fa fa-plus-circle"></i></button>';
                                        break;
                                    default:
                                        opciones += '<button type="button" style="margin-bottom: 5px;"class="btn bg-maroon btn-sm btn-masopcionessegundo" data-idetapa="'+(d.idetapa)+'" data-value="'+(d.cantidad-d.pagado)+'" value="'+d.idsolicitud+'" title="Más opciones"><i class="fa fa-plus-circle"></i></button>';
                                        break;
                                }
                            }
                            
                        }

                        return opciones + '</div>';
                    }
                }
                ,{ // Cambios TDC Reportes | FECHA: 17-Abril-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com>
                        "orderable": false,
                        "width": "14%",
                        "data": function( d ){
                            return '<p style="font-size: .7em">'+d.titular_nombre+'</p>';
                        }
                } // FIN Cambios TDC Reportes | FECHA: 17-Abril-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com>
                ,{ // FECHA: 29-Abril-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com>
                    "orderable": false,
                    "width": "14%",
                    "data": function( d ){
                        return '<p style="font-size: .7em">'+(d.nombre_reembolso_cch ? d.nombre_reembolso_cch : 'NA')+'</p>';
                    }
                } // FIN FECHA: 29-Abril-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com>
            ],
            "columnDefs": [
                {
                    "orderable": false
                },
                {
                    "targets": [17, 18], // FECHA: 29-Abril-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com>
                    "orderable": false,
                    "visible": false,
                }
            ],
            // "ajax": url + "Historial/TablaHistorialSolicitudesA"
            "ajax":{                 
                url:url + "Historial/TablaHistorialSolicitudesA",
                type: 'POST', 
                data: function(d){
                    d.finicial = formatfech($('#fecInicial').val()),
                    d.ffinal= formatfech($('#fecFinal').val())
                }
            } 
        });//

        $('#historial_sol_activas tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = tabla_activas.row( tr );
            
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
                '</tr>'+ (tabla_activa == '#historial_activas_tdc' ? '<td><strong>TITULAR TARJETA: </strong>'+row.data().titular_nombre : '')+ // Cambios TDC Reportes | FECHA: 17-Abril-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com>
                '</tr>'+ ((tabla_activa == '#historial_activas_cch' || tabla_activa == '#historial_activas_viaticos') ? '<td><strong>RESPONSABLE REEMBOLSO: </strong>'+(row.data().nombre_reembolso_cch ? row.data().nombre_reembolso_cch : 'NA') : '')+ // FECHA: 29-Abril-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com>
                '</table>';

                row.child( informacion_adicional ).show();
                tr.addClass('shown');
                $(this).parent().find('.animacion').removeClass("fa-caret-right").addClass("fa-caret-down");
            }
        });

        $('#fecInicial').change( function() { 
            tabla_activas.draw();
            var total = 0;
            var index = tabla_activas.rows( { selected: true, search: 'applied' } ).indexes();
            var data = tabla_activas.rows( index ).data();
            $.each(data, function(i, v){
                total += parseFloat(v.cantidad);
            });
            var to1 = formatMoney(total);
            document.getElementById("myText_1").value = to1;
        });

        $('#fecFinal').change( function() { 
            tabla_activas.draw();
            var total = 0;
            var index = tabla_activas.rows( { selected: true, search: 'applied' } ).indexes();
            var data = tabla_activas.rows( index ).data();
            $.each(data, function(i, v){
                total += parseFloat(v.cantidad);
            });
            var to1 = formatMoney(total);
            document.getElementById("myText_1").value = to1;
        });
        $('.dt-buttons').attr('style','float: right !important; margin-right: 15px;');
    });

    $.fn.dataTableExt.afnFiltering.push(
        function( oSettings, aData, iDataIndex ) {

            // check if current table is part of the allow list
            if ( $.inArray( oSettings.nTable.getAttribute('id'), ["historial_sol_canceladas", "historial_sol_pausadas", "tabla_abonos"] ) >=0 )
            {
                // if not table should be ignored
                return true;
            }

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

            var iStartDateCol = 6;
            var iEndDateCol = 6;

            var meses = ['','Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];
            
            var mes1 =  typeof aData[iStartDateCol] === 'undefined' ? '' : (meses.indexOf(aData[iStartDateCol].split('/')[1]).toString()).padStart(2, "0");
            var mes2 =  typeof aData[iEndDateCol] === 'undefined' ? '' : (meses.indexOf(aData[iEndDateCol].split('/')[1]).toString()).padStart(2, "0");

            iFini=iFini.substring(6,10) + iFini.substring(3,5) + iFini.substring(0,2);
            iFfin=iFfin.substring(6,10) + iFfin.substring(3,5) + iFfin.substring(0,2);
    
                var datofini=aData[iStartDateCol].substring(7,11) + mes1 + aData[iStartDateCol].substring(0,2);
                var datoffin=aData[iEndDateCol].substring(7,11) + mes2 + aData[iEndDateCol].substring(0,2);
    
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
        }
    );

    $("#historial_sol_canceladas").ready( function(){
        $('#historial_sol_canceladas thead tr:eq(0) th').each( function (i) {
            if( i != 0 && i != 15 ){
                var title = $(this).text();
                $(this).html( '<input type="text" class="form-control" style="font-size: .9em; width: 100%;" placeholder="'+title+'" />' );

                $( 'input', this ).on('keyup change', function () {
                    if (tabla_canceladas.column(i).search() !== this.value ) {
                        tabla_canceladas
                        .column(i)
                        .search( this.value)
                        .draw();

                        var total = 0;
                        var index = tabla_canceladas.rows( { selected: true, search: 'applied' } ).indexes();
                        var data = tabla_canceladas.rows( index ).data();
                        $.each(data, function(i, v){
                           total += parseFloat(v.cantidad);
                       });
                        var to1 = formatMoney(total);
                        document.getElementById("myText_2").value = to1;
                    }
                });
            }
        });
        
        $('#historial_sol_canceladas').on('xhr.dt', function ( e, settings, json, xhr ) {
         var total = 0;
         $.each( json.data,  function(i, v){
           total += parseFloat(v.cantidad);
       });
         var to = formatMoney(total);
         document.getElementById("myText_2").value = to;

     });

        tabla_canceladas = $("#historial_sol_canceladas").DataTable({
            dom: 'Brtip',
            "buttons": [
            {
                extend: 'excel',             
                text: '<i class="fas fa-file-excel"></i> EXPORTAR',
                messageTop: "LISTADO DE SOLICITUDES",
                attr: {
                    class: 'btn btn-success'       
                },
                exportOptions: {
                    format:{    
                        header:  function (data, columnIdx) {

                            data = data.replace( '<input type="text" class="form-control" style="font-size: .9em; width: 100%;" placeholder="', '' );
                            data = data.replace( '" />', '' );
                            data = data.replace( '">', '' );
                            return data;
                        }
                    },
                    columns: depto_excep_proyecto.includes(usuario.depto) ? [1, 2, 3, 4, 5, 7, 8, 9, 10, 11, 12, 13, 14] :  [1, 16, 17, 18, 2, 3, 4, 5, 7, 8, 9, 10, 11, 12, 13, 14]
                        
                }
            },
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
            "columns": [
                {
                    "className": 'details-control',
                    "orderable": false,
                    "data" : null,
                    "defaultContent": '<i class="fas animacion fa-caret-right"></i>'
                },
                {
                    "orderable": false,
                    "width": "8%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.idsolicitud+'</p>'
                    }
                },
                {
                    "orderable": false,
                    "width": "9%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.abrev+'</p>'
                    }
                },
                {
                    "orderable": false,
                    "width": "9%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+(d.folio ? d.folio : "SF")+'</p>'
                    }
                },
                {
                    "orderable": false,
                    "width": "10%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+( d.feccrea ? formato_fechaymd(d.feccrea) : "" )+'</p>'
                    }
                },
                {
                    "orderable": false,
                    "width": "10%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+( d.fecha_autorizacion ? formato_fechaymd(d.fecha_autorizacion) : "-" )+'</p>'
                    }
                },
                {
                    "orderable": false,
                    "width": "10%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+( d.fecelab ? formato_fechaymd(d.fecelab) : "" )+'</p>'
                    }
                },
                {
                    "orderable": false,
                    "width": "9%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.nombre+'</p>'
                    }
                },
                {
                    "orderable": false,
                    "width": "9%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.nomdepto+'</p>'
                    }
                },
                {
                    "orderable": false,
                    "width": "12%",
                    "data": function( d ){
                    return '<p style="font-size: .8em">'+formatMoney( d.cantidad )+'</p>';
                }
                },
                {
                    "orderable": false,
                    "width": "12%",
                    "data": function( d ){
                        return '<p style="font-size: .8em">'+formatMoney( d.pagado )+'</p>';
                    }
                },
                {
                    "orderable": false,
                    "data": function( d ){
                        return '<p style="font-size: .8em">'+formatMoney( d.cantidad - d.pagado )+'</p>';
                    }
                },
                {
                    "orderable": false,
                    "width": "10%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.etapa+'</p>';        
                    }
                },
                {
                    "orderable": false,
                    "width": "10%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+(d.caja_chica ? "CAJA CHICA" : "PAGO PROVEEDOR")+'</p>'
                    }
                },
                {
                    "orderable": false,
                    "width": "10%",
                    "data" : function( d ){
                        return d.tienefac == 'NO' ? '<p style="font-size: .8em">'+d.tienefac+'</p>' : '<p style="font-size: .8em">SI</p>';
                    }
                },
                {
                    "orderable": false,
                    "data": function( d ){

                        var tipo_ventana = ( d.nomdepto == "DEVOLUCIONES" || d.nomdepto == "TRASPASO" || d.nomdepto == "TRASPASO OOAM" ) ? "DEV_BASICA" : "SOL";
                        return '<div class="btn-group-vertical"><button type="button" class="btn btn-primary consultar_modal notification" value="'+d.idsolicitud+'" data-value="'+tipo_ventana+'" title="Ver Solicitud"><i class="fas fa-eye"></i>'+(d.visto == 0 ? '<span class="badge">!</span>' : '')+'</button></div>';
                    }
                },
                {
                    "orderable": false,
                    "width": "10%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.proyecto ? d.proyecto : '' +'</p>';
                    }
                },
                {
                    "orderable": false,
                    "width": "10%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.oficina ? d.oficina : ''+'</p>';
                    }
                },
                {
                    "orderable": false,
                    "width": "10%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.servicioPartida ? d.servicioPartida : '' +'</p>';
                    }
                }
            ],
            "columnDefs": [ 
                {
                "orderable": false
                },
                {
                    "targets": [16, 17, 18],
                    "visible": false,
                }
            ]
        });

        $('#historial_sol_canceladas tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = tabla_canceladas.row( tr );
            
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
    });

    $("#historial_sol_pausadas").ready( function(){
        $('#historial_sol_pausadas thead tr:eq(0) th').each( function (i) {
            if( i != 0 && i != 15 ){
                var title = $(this).text();
                $(this).html( '<input type="text" class="form-control" style="font-size: .9em; width: 100%;" placeholder="'+title+'" />' );

                $( 'input', this ).on('keyup change', function () {
                    if (tabla_pausadas.column(i).search() !== this.value ) {
                        tabla_pausadas
                        .column(i)
                        .search( this.value)
                        .draw();

                        var total = 0;
                        var index = tabla_pausadas.rows( { selected: true, search: 'applied' } ).indexes();
                        var data = tabla_pausadas.rows( index ).data();
                        $.each(data, function(i, v){
                           total += parseFloat(v.cantidad);
                       });
                        var to1 = formatMoney(total);
                        document.getElementById("myText_3").value = to1;
                    }
                });
            }
        });
        
        $('#historial_sol_pausadas').on('xhr.dt', function ( e, settings, json, xhr ) {

            var total = 0;

            $.each( json.data,  function(i, v){
                total += parseFloat(v.cantidad);
            });

            var to = formatMoney(total);
            document.getElementById("myText_3").value = to;

        });

        tabla_pausadas = $("#historial_sol_pausadas").DataTable({
            dom: 'Brtip',
            "buttons": [
                {
                    extend: 'excel',
                    text: '<i class="fas fa-file-excel"></i> EXPORTAR',
                    messageTop: "LISTADO DE SOLICITUDES",
                    attr: {
                        class: 'btn btn-success'       
                    },
                    exportOptions: {
                        format:{    
                            header:  function (data, columnIdx) {

                                data = data.replace( '<input type="text" class="form-control" style="font-size: .9em; width: 100%;" placeholder="', '' );
                                data = data.replace( '" />', '' );
                                data = data.replace( '">', '' );
                                return data;
                            }
                        },columns: depto_excep_proyecto.includes(usuario.depto) ? [1, 2, 3, 4, 5, 7, 8, 9, 10, 11, 12, 13, 14, 16] :  [1, 17, 18, 19, 2, 3, 4, 5, 7, 8, 9, 10, 11, 12, 13, 14, 16]
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
            "columns": [
            {
                "className": 'details-control',
                "orderable": false,
                "data" : null,
                "defaultContent": '<i class="fas animacion fa-caret-right"></i>'
            },
            {
                "orderable": false,
                "width": "8%",
                "data" : function( d ){
                    return '<p style="font-size: .8em">'+d.idsolicitud+'</p>'
                }
            },
            {
                "orderable": false,
                "width": "9%",
                "data" : function( d ){
                    return '<p style="font-size: .8em">'+d.abrev+'</p>'
                }
            },
            {
                "orderable": false,
                "width": "9%",
                "data" : function( d ){
                    return '<p style="font-size: .8em">'+(d.folio ? d.folio : "SF")+'</p>'
                }
            },
            {
                "orderable": false,
                "width": "10%",
                "data" : function( d ){
                    return '<p style="font-size: .8em">'+( d.feccrea ? formato_fechaymd(d.feccrea) : "" )+'</p>'
                }
            },
            {
                "orderable": false,
                "width": "10%",
                "data" : function( d ){
                    return '<p style="font-size: .8em">'+( d.fecha_autorizacion ? formato_fechaymd(d.fecha_autorizacion) : "-" )+'</p>'
                }
            },
            {
                "orderable": false,
                "width": "10%",
                "data" : function( d ){
                    return '<p style="font-size: .8em">'+( d.fecelab ? formato_fechaymd(d.fecelab) : "")+'</p>'
                }
            },
            {
                "orderable": false,
                "width": "9%",
                "data" : function( d ){
                    return '<p style="font-size: .8em">'+d.nombre+'</p>'
                }
            },
            {
                "orderable": false,
                "width": "9%",
                "data" : function( d ){
                    return '<p style="font-size: .8em">'+d.nomdepto+'</p>'
                }
            },
            {
                "orderable": false,
                "width": "12%",
                "data": function( d ){
                    return '<p style="font-size: .8em">'+formatMoney( d.cantidad )+'</p>';
                }
            },
            {
                "orderable": false,
                "width": "12%",
                "data": function( d ){
                    return '<p style="font-size: .8em">'+formatMoney( d.pagado )+'</p>';
                }
            },
            {
                "orderable": false,
                "data": function( d ){
                    return '<p style="font-size: .8em">'+formatMoney( d.cantidad - d.pagado )+'</p>';
                }
            },
            {
                "orderable": false,
                "width": "10%",
                "data" : function( d ){

                    switch(d.estatus_pago){
                        case '0':
                        return '<p style="font-size: .8em">PAGO AUTORIZADO DG</p>';
                        break;
                        case '1':
                        return '<p style="font-size: .8em">POR DISPERSAR</p>';
                        break;
                        case '5':
                        return '<p style="font-size: .8em">POR DISPERSAR</p>';
                        break;
                        case '15':
                        return '<p style="font-size: .8em">'+(d.metoPago=='ECHQ'?'CHEQUE SIN COBRAR':'POR CONFIRMAR PAGO CXP')+'</p>';
                        break;
                        case '16':
            

                        if(d.estatus_pago && d.estatus_pago == '16' && (d.etapa=='Pago Aut. por DG, Factura Pendiente'||d.etapa=='Pagado')){ 
                            return '<p style="font-size: .8em">PAGO COMPLETO</p>' ;
                        }
                        else{
                            return '<p style="font-size: .8em">'+d.etapa+'</p>';
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
                            return '<p style="font-size: .8em">Próxima revisión de Cuentas por Pagar</p>' + 
                            '<p style="font-size: .8em">'+dateToDMY(fec)+'</p>'
                        }
                        else{
                            return '<p style="font-size: .8em">'+d.etapa+'</p>'
                        }

                        break;
                    }
                }
            },
            {
                "orderable": false,
                "width": "10%",
                "data" : function( d ){
                    return '<p style="font-size: .8em">'+[ "PAGO PROVEEDOR", "CAJA CHICA", "TDC" ][ d.caja_chica ]+'</p>'
                }
            },
            {
                "orderable": false,
                "width": "10%",
                "data" : function( d ){
                    return '<p style="font-size: .7em">'+d.tienefac+'</p>'
                }
            },
            {
                 
                "orderable": false,
                "data": function( d ){
                        var tipo_ventana = ( d.nomdepto == "DEVOLUCIONES" || d.nomdepto == "TRASPASO" || d.nomdepto == "TRASPASO OOAM" ) ? "DEV_BASICA" : "SOL";
                        opciones = '<div>';
                        opciones += '<button type="button" class="btn btn-small btn-primary consultar_modal notification" value="'+d.idsolicitud+'" data-value="'+tipo_ventana+'" title="Ver Solicitud"><i class="fas fa-eye"></i>'+(d.visto == 0 ? '<span class="badge">!</span>' : '')+'</button>';

                        opciones += '<button type="button" class="btn btn-small btn btn-quitarpausahistorial " value="'+d.idsolicitud+'" title="Regresar a curso"><i class="fas fa-clock"></i></button>';
                      return opciones + '</div>';
                }
            },
            { data: 'justificacion' },
            {
                "orderable": false,
                "width": "10%",
                "data" : function( d ){
                    return '<p style="font-size: .8em">'+d.proyecto ? d.proyecto : '' +'</p>';
                }
            },
            {
                "orderable": false,
                "width": "10%",
                "data" : function( d ){
                    return '<p style="font-size: .8em">'+d.oficina ? d.oficina : ''+'</p>';
                }
            },
            {
                "orderable": false,
                "width": "10%",
                "data" : function( d ){
                    return '<p style="font-size: .8em">'+d.servicioPartida ? d.servicioPartida : '' +'</p>';
                }
            }
            ],
            "columnDefs": [ 
                {
                    "orderable": false
                },
                {
                    "targets": [16, 17, 18, 19],
                    "visible": false,
                }
            ]
                //"ajax": url + "Historial/TablaHistorialSolicitudesP",
        });

        $('#historial_sol_pausadas tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = tabla_pausadas.row( tr );
            
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
    });

    $( document ).on("click", ".cancela_solicitud", function(){
        if( $( "#formulario_opciones" ).valid() ){

            index_cancelada = $(this).attr( "value" );
            justificacion = $("#justificacion").val();
            
            $("#modal_opciones").modal('toggle');
            //Fecha : 12-Agosto-2025 @author Efrain Martinez programador.analista38@ciudadmaderas.com
            // Se agrega la validacion para saber si la solicitud fue eliminada correctamente, en caso contrario mostrara una alerta
            // indicando que la solicitud ya esta pagada o que surgio un problema al cancelar la solicitud. 
            enviar_post_64( function( response ){
                if( response.resultado && response.eliminada){
                    tabla_activas.row( tr_global ).remove().draw();
                }
                if( response.resultado == true && response.eliminada == false){
                    alert("¡Esta solicitud ya esta pagada!"); /** FECHA: 10-FEBRERO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                }
                if(response.resultado == false){
                    alert("¡Algo ha ocurrido! Intente mas tarde.");
                }
            },{ idsolicitud : index_cancelada, justificacion : justificacion }, url + "Opciones/cancelar_historial/" );
        }
    });

    $( document ).on("click", ".pausa_solicitud", function(){
        if( $( "#formulario_opciones" ).valid() ){

            index_pausada = $(this).attr( "value" );
            justificacion = $("#justificacion").val();

            $("#modal_opciones").modal('toggle');
            enviar_post_64( function( response ){
                if( response.resultado ){
                    tabla_activas.row( tr_global ).remove().draw();
                }else{
                    alert( response.resultado )
                }
            },{ idsolicitud : index_pausada, justificacion : justificacion }, url + "Opciones/pausar_historial/" );
        }

    });

    //QUITAMOS LA PAUSA DE LA SOLCITUD
    $( document ).on("click", ".btn-quitarpausahistorial", function(){
        var tr = $(this).closest('tr');
        $.post( url + "Opciones/quitar_pausa_historial/"+$(this).attr( "value" )).done(function () {
            tabla_pausadas.row( tr ).remove().draw();
        });
    });

    $(window).resize(function(){
        tabla_activas.columns.adjust();
        tabla_canceladas.columns.adjust();
        tabla_pausadas.columns.adjust();
        tabla_abonos.columns.adjust();
    });

    $("#tabla_abonos").ready(function(e){
        tabla_abonos = $('#tabla_abonos').DataTable( {
            dom: 'Brtip',
            "columns": [
                { "data": function(d){
                        return ''+d.idpago;
                    } 
                },
                { "data": function(d){return ''+formatMoney(d.cantidad);} },
                { "data": function(d){return ''+d.nombres+' '+d.apellidos;} },
                { "data": function(d){
                        return '<div class="btn-group">\
                        <button type="button" class="btn btn-primary btn-sm" title="Editar" onclick="acciones_abono(\'mod\',\''+d.idpago+'\',\''+d.cantidad+'\',\''+d.idsolicitud+'\')"><i class="fas fa-edit"></i></button>\
                        <button type="button" class="btn btn-danger btn-sm" title="Eliminar" onclick="acciones_abono(\'eli\',\''+d.idpago+'\',\''+d.cantidad+'\',\''+d.idsolicitud+'\')"><i class="fas fa-trash-alt"></i></button>\
                        </div>';
                    } 
                }
            ],
            "buttons": [],
            "order": [[ 0, "asc" ]],
            "language":lenguaje,
            "processing": false,
            "pageLength": 10,
            "bAutoWidth": false,
            "bLengthChange": false,
            "scrollX": true,
            "bInfo": false,
            "deferRender": true,
            "columnDefs": [ {
               "orderable": false
            }]
        });
    });

    $('#modal_abonos').on('shown.bs.modal', function () {
       tabla_abonos.columns.adjust();
   });

    function acciones_abono(accion,idpago,cnt,idsol){
        $("#razon_abono").val("");
        $("#idpago_abonos").val(idpago);
        $("#accion_abonos").val(accion);
        $("#cantidad_abonada").val("");
        $("#idpago_abono").val(idpago);
        $("#idsolicitud_abono").val(idsol);
        $("#accion_abono").val(accion);
        $("#cantidadxabonar").val("$"+formatMoney( Number(saldo_abono)+Number(cnt) ));
        $("#abono_old").val(cnt);
        if(idpago==0){
            $("#bt_abonoaccion").text("REGISTRAR");
            $("#idpago_h6").text("Registrando un nuevo abono");
            $("#bt_abonocancel").hide("slow");
        }else if(accion=='eli'){
            if(confirm('¿Estás seguro de eliminar el abono #'+idpago+'?')){
                var data = new FormData();
                data.append("idsolicitud",idsol)
                data.append("idpago",idpago);
                data.append("accion",accion);
                data.append("cantidad_abonada",cnt);
                data.append("razon_abono","Se ha eliminado el abono "+idpago+" por la cantidad de $"+formatMoney(cnt));
                enviar_post2((respuesta)=>{
                    if( !respuesta.respuesta ){
                        alert("NO SE HA PODIDO COMPLETAR LA SOLICITUD");
                    }else{
                        $("#modal_abonos").modal( 'toggle' );
                        alert("Se ha eliminado el abono correctamente, queda por saldar $"+formatMoney(respuesta.data[0].cantidad-respuesta.data[0].pagado));
                        var row_data = tabla_activas.row( tr_global ).data();
                        row_data={...row_data,cantidad:respuesta.data[0].cantidad,pagado:respuesta.data[0].pagado};
                        tabla_activas.row( tr_global ).data( row_data ).draw();
                    }
                },data,"Historial/accion_abonos");
            }
        }else{
            $("#idpago_h6").html("Actualizando el abono #"+idpago);
            $("#bt_abonoaccion").text("ACTUALIZAR");
            $("#bt_abonocancel").show("slow");
            $("#cantidad_abonada").val(cnt).trigger('mask.maskMoney');
        }
    }

    $("#form_abonos").submit(function(e) {
         e.preventDefault();
     }).validate({
        submitHandler: function( form ) {
            var data = new FormData( $(form)[0] );
            if(Number($("#cantidad_abonada").val().replace(/[^0-9.]/g, '') ) > Number( $("#cantidadxabonar").val().replace(/[^0-9.]/g, '') ) ){
                alert("No puede ingresar una cantidad mayor al saldo pendiente");
            }else{
                enviar_post2((respuesta)=>{
                    if( !respuesta.respuesta ){
                        alert("NO SE HA PODIDO COMPLETAR LA SOLICITUD");
                    }else{
                        $("#modal_abonos").modal( 'toggle' );
                        alert("Se ha agregado el abono correctamente, queda por saldar $"+formatMoney(respuesta.data[0].cantidad-respuesta.data[0].pagado));
                        var row_data = tabla_activas.row( tr_global ).data();
                        row_data={...row_data,cantidad:respuesta.data[0].cantidad,pagado:respuesta.data[0].pagado};
                        tabla_activas.row( tr_global ).data( row_data ).draw();
                    }
                },data,"Historial/accion_abonos");
                
            }
         }
    });

    $( document ).on("click", ".btn-masopciones", function(){
        //PONER URGENTE
        index_solicitud = $(this).attr("value");
        index_saldo = $(this).attr( "data-value" );
        var idetapa= $(this).attr( "data-idetapa" );
        tr_global = $(this).closest('tr');
        var tr = $(this).closest('tr');
        var row = tabla_activas.row( tr ).data();

        $("#modal_opciones .modal-header").html("");
        $("#modal_opciones .modal-body").html("");
        $("#modal_opciones .modal-footer").html("");
        
        // Fecha : 12-Agosto-2025 @author Efrain Martinez <programador.analista38@ciudadmaderas.com>
        // SE AGREGA LA OPCION CANCELAR SOLICITUD AL BOTÓN masopciones EL CUAL SE MUESTRA CUANDO LA ETAPA DE LA SOLICITUD SEA 2 O 5
        $("#modal_opciones .modal-header")
            .append(`<button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">SELECCIONE ACCIÓN</h4>
                    <br/>
                    <select id="change_options" name="change_options" class="form-control">
                        <option value="">-- Selecciona opción --</option>
                        <option value="1">PONER URGENTE</option>
                        <option value="4">TENDRÁ FACTURA (SI/NO)</option>
                        <option value="5">CANCELAR SOLICITUD</option>
                        <option value="8">NOTA DE CRÉDITO</option>
                        <!-- <option value="9"><i class="fas fa-exchange-alt"></i> CAMBIAR PROVEEDOR</option> -->
                        <!-- <option value="3"><i class="fas fa-exchange-alt"></i> CAMBIAR EMPRESA</option> -->
                        <option value="10">
                            <i class="fas fa-exchange-alt"></i>ELIMINAR FACTURA
                        </option>
                        <!-- <option value="12">CAMBIAR PROVEEDOR DESDE XML</option> -->
                    </select>`);

        // <option value="5" class="form-control">&#xf1c3 LIBERAR FACTURA XML</option>
        if(idetapa<=7){// Esta opción valida que la opción factoraje sea visible para Esther
            <?php
            if(in_array($this->session->userdata('inicio_sesion')["id"],[84]))
                echo '$(\'#change_options\').append(`<option value="11">CAMBIAR A FACTORAJE</option>`);';
            ?>
        }

        $('#change_options').on('change',function(){
            cuenta = $(this).val();
            
            $("#modal_opciones .modal-body").html("");
            $("#modal_opciones .modal-footer").html("");
            
            switch(cuenta){
                case '1':
                    //URGENTE
                    $("#modal_opciones .modal-body").html("");
                    $("#modal_opciones .modal-footer").html("");
                    $("#modal_opciones .modal-body").append("<center><img style='width: 20%; height: 20%;' src= '<?= base_url('img/alert.png')?>'></center>");
                    $("#modal_opciones .modal-footer").append("<center><button type='button' style:margin-right: 50px;' class='btn bg-teal urgente' value="+index_solicitud+">ACEPTAR</button><button type='button' style:'margin-right: 50px;' class='btn btn-danger' onClick='cancelar_opcion()'>CANCELAR</button></center>");
                    break;
                case '3':
                    cambiar_empresa( tr );
                    break;
                case '4':
                    //TENDRA FACTURA
                    $("#modal_opciones .modal-body").html("");
                    
                    $("#modal_opciones .modal-body").append("<center><div class=\"btn-group-vertical\" role=\"group\"><button type='button' style:margin-right: 50px;' class='btn btn-success tendra_factura' value="+index_solicitud+">TENDRÁ</button><button type='button' style:margin-right: 50px;' class='btn btn-warning no_tendra' value="+index_solicitud+">NO TENDRÁ</button><button type='button' style:'margin-right: 50px;' class='btn btn-danger' onClick='cancelar_opcion()'>CANCELAR</button></div></center>");
                    $("#modal_opciones .modal-footer").html("");
                    break;
                //Fecha : 12-Agosto-2025 @author Efrain Martinez <programador.analista38@ciudadmaderas.com>
                // SE AGREGA EN EL MODAL EL INPUT Y EL BOTON PARA QUE EL USUARIO INGRESE LA JUSTIFICACIÓN Y SE PUEDA CANCELAR LA SOLICITUD
                case '5':
                    //CANCELAR LA SOLICTUD
                    $("#modal_opciones .modal-body").html("<textarea class='form-control' id='justificacion' rows='3' placeholder='Justificación' required></textarea>");
                    $("#modal_opciones .modal-footer").html("<center><button type='button' class='btn btn-block bg-olive cancela_solicitud' value="+index_solicitud+">CANCELAR REGISTRO</button></center>");
                    break;
                case '7':
                    //cambiar_monto_registro( tr );
                    break;
                case '8':
                    $("#modal_opciones .modal-body").html('<form id="complemento_facturas"><div class="col-lg-12 form-group"><label>DOCUMENTO XML</label><div class="row"><div class="col-lg-12"><div class="input-group"><input type="file" class="form-control" id="complemento" accept="text/xml" required><div class="input-group-btn"><button type="button" class="btn btn-danger cargar_nota_credito"><i class="fas fa-upload"></i></button></div></div></div></div></div></form>');
                    break;
                case '9':
                    cambiar_proveedor( tr );
                    break;
                case '10':
                    $("#modal_opciones .modal-body").html('<p><b>¿Deseas eliminar la factura cargada a la solicitud #'+index_solicitud+'?</b></p>\n\<br><center><button type="button" class="btn btn-danger" onclick="borra_factura('+index_solicitud+')">Eliminar</button></center>');
                    break;
                case '11':
                    $("#modal_opciones .modal-body").html(form_factoraje);
                    break;
                case '12':
                    cambiarempresa_xml(tr);
                    break;
                default:
                    $("#modal_opciones .modal-body").html("");
                    $("#modal_opciones .modal-footer").html("");
                    break;

            }
        });

        
        $("#modal_opciones").modal();

    });

    $( document ).on("click", ".btn-masopcionestercero", function() {
        const etapasConPermisoParaPausar = [7]; //Espera Autorización de Pago DG
        index_solicitud = $(this).attr("value");
        index_saldo = $(this).attr( "data-value" );
        var idetapa= parseInt($(this).attr("data-idetapa"));
        tr_global = $(this).closest('tr');
        var tr = $(this).closest('tr');
        var row = tabla_activas.row( tr ).data();
        $("#modal_opciones .modal-header").html("");
        $("#modal_opciones .modal-body").html("");
        $("#modal_opciones .modal-footer").html("");
        //if (etapasConPermisoParaPausar.includes(idetapa)){
            $("#modal_opciones .modal-header").append('<button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="modal-title">SELECCIONE ACCIÓN</h4><br>'+
                '<select id="change_options" name="change_options" class="form-control">'+
                '<option value="">-- Selecciona opción --</option>'+
                '<option value="1">AGREGAR REF. BANCARIA</option>'+
                '<option value="2">AÑADIR ABONO</option>'+
                '<option value="3" ><i class="fas fa-exchange-alt"></i> CAMBIAR EMPRESA</option>'+
                '<option value="4">TENDRÁ FACTURA (SI/NO)</option>'+
                '<option value="5">CANCELAR SOLICITUD</option>'+
                '<option value="6">PAUSAR SOLICITUD</option>'+
                '<option value="8">NOTA DE CRÉDITO</option>'+
                '<option value="9">CAMBIAR PROVEEDOR</option>'+
                '<option value="10">ELIMINAR FACTURA</option>'+
                '<option value="12">CARGAR FACTURA</option>'+
            '</select>');
        //}
        
        $('#change_options').on('change',function() {
            
            cuenta = $(this).val();
            $("#modal_opciones .modal-body").html("");
            $("#modal_opciones .modal-footer").html("");
            switch(cuenta){
                case '1':
                    //REFERENCIA BANCARIA
                    $("#modal_opciones .modal-body").html("<center><input type='text' name='valor_referencia' id='valor_referencia' class='form-control' placeholder='Digite referencia bancaria' required></center>");
                    $("#modal_opciones .modal-footer").html("<center><button type='button' class='btn btn-block bg-teal add_referencia' value="+index_solicitud+">CAMBIAR</button></center>");
                    break;
                case '2':
                    //AÑANIR ABONO
                    $("#modal_opciones").modal("hide");
                    $("#modal_abonos .modal-title > b").text(index_solicitud);
                    $("#modal_abonos").modal("show");
                    tabla_abonos.ajax.url( "Historial/abonos_sol?ids="+index_solicitud ).load();
                    saldo_abono=index_saldo;
                    acciones_abono("reg",0,0,index_solicitud);
                    break;
                case '3':
                    cambiar_empresa( tr );
                    break;
                case '4':
                    //TENDRA FACTURA
                    $("#modal_opciones .modal-body").html("");
                    $("#modal_opciones .modal-footer").html("");
                    $("#modal_opciones .modal-footer").append("<center><button type='button' class='btn btn-block bg-olive tendra_factura' value="+index_solicitud+">TENDRÁ</button><button type='button' style:margin-right: 50px;' class='btn btn-block bg-orange no_tendra' value="+index_solicitud+">NO TENDRÁ</button>");
                    break;
                case '5':
                    //CANCELAR LA SOLICTUD
                    $("#modal_opciones .modal-body").html("<textarea class='form-control' id='justificacion' rows='3' placeholder='Justificación' required></textarea>");
                    $("#modal_opciones .modal-footer").html("<center><button type='button' class='btn btn-block bg-olive cancela_solicitud' value="+index_solicitud+">CANCELAR REGISTRO</button></center>");
                    break;
                case '7':
                    //cambiar_monto_registro( tr );
                    break;
                case '6':
                    //PAUSAR SOLICITUD
                    $("#modal_opciones .modal-body").html("<textarea class='form-control' id='justificacion' rows='3' placeholder='Justificación' required></textarea>");
                    $("#modal_opciones .modal-footer").html("<center><button type='button' class='btn btn-block bg-olive pausa_solicitud' value="+index_solicitud+">PAUSAR</button></center>");                    
                    break;
                case '9':
                    cambiar_proveedor( tr );
                    break;
                case '8':
                    $("#modal_opciones .modal-body").html('<div class="col-lg-12 form-group"><label>DOCUMENTO XML</label><div class="row"><div class="col-lg-12"><div class="input-group"><input type="file" class="form-control" id="complemento" accept="text/xml" required><div class="input-group-btn"><button type="button" class="btn btn-danger cargar_nota_credito"><i class="fas fa-upload"></i></button></div></div></div></div></div>');
                    break;
                case '10':
                    $("#modal_opciones .modal-body").html('<p><b>¿Deseas eliminar la factura cargada a la solicitud #'+index_solicitud+'?</b></p>\n\
                        <br><center><button type="button" class="btn btn-block bg-olive" onclick="borra_factura('+index_solicitud+')">Eliminar</button></center>');
                    $("#modal_opciones .modal-footer").html("");
                    break;
                case '11':
                    $("#modal_opciones .modal-body").html(form_factoraje);
                    $("#modal_opciones .modal-footer").html("");
                    break;
                case '12':
                    //CARGAR NUEVA_FACTURA
                    $("#modal_opciones").modal("toggle");
                    $("#factura_complemento").modal();
                    break;
                default:
                    $("#modal_opciones .modal-body").html("");
                    $("#modal_opciones .modal-footer").html("");
                    break;

            }
        }); 
        $("#modal_opciones").modal();
    });
    
    function cambiarempresa_xml(tr){ /** INICIO FECHA: 28-JULIO-2025 | TOM SELECT EMPRESAS-PROVEEDORES HISTORIAL SOLICITUD | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
        tr_global = tr;
        $.post( url + "Listas_select/lista_proveedor_xml", {"idsolicitud":index_solicitud}).done( function(data){
            var proveedores = JSON.parse(data);

            if(proveedores.length > 0){
                $("#modal_opciones .modal-body").html(`
                    <div class="col-lg-12 form-group">
                        <label>PROVEEDOR DESDE XML</label>
                        <div class="row">
                            <div class="col-lg-12">
                                <select id="idproveedor" name="idproveedor" required style="display: none;"></select>
                                <br>
                                <button type="button" class="btn btn-block btn-danger cambio_proveedor">CAMBIAR</button>
                            </div>
                        </div>
                    </div>
                `);

                var arrayProveedoresXML = [];
                arrayProveedoresXML.push({value: '', label: '--- Seleccione una opción ---'});
                
                for(var i = 0; i < proveedores.length; i++) {
                    var v = proveedores[i];
                    arrayProveedoresXML.push({
                        value: v.idproveedor,
                        label: v.nombreproveedor+' - '+v.nombrebanco+' - '+v.cuenta
                    });
                }
                inputTomSelect('idproveedor', arrayProveedoresXML, {valor:'value', texto: 'label'});
                
                $("#idproveedor")[0].tomselect.setValue('');
                $("#idproveedor").trigger('change');
                $("#idproveedor").show();

            }else{
                alert("NO HAY FACTURAS REGISTRADAS");
            }
        }); 
    } /** FIN FECHA: 28-JULIO-2025 | TOM SELECT EMPRESAS-PROVEEDORES HISTORIAL SOLICITUD | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
    
    $( document ).on("click", ".btn-masopcionessegundo", function(){
        index_solicitud = $(this).attr("value");
        index_saldo = $(this).attr( "data-value" );
        var idetapa= $(this).attr( "data-idetapa" );
        var tr = $(this).closest('tr');
        var row = tabla_activas.row( tr ).data();
        tr_global = tr;

        $("#modal_opciones .modal-header").html("");
        $("#modal_opciones .modal-body").html("");
        $("#modal_opciones .modal-footer").html("");
    
        $("#modal_opciones .modal-header").append('<button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="modal-title">SELECCIONE ACCIÓN</h4><br>'+
        '<select id="change_options" name="change_options" class="form-control">'+
            '<option value="">-- Selecciona opción --</option>'+
            '<option value="1">AGREGAR REF. BANCARIA</option>'+
            '<option value="2">AÑADIR ABONO</option>'+
            '<option value="4">TENDRÁ FACTURA (SI/NO)</option>'+
            '<option value="5">CANCELAR SOLICITUD</option>'+
            '<option value="6">PAUSAR SOLICITUD</option>'+
            '<option value="8">NOTA DE CRÉDITO</option>'+
            // '<option value="9">CAMBIAR PROVEEDOR</option>'+
            '<option value="10">ELIMINAR FACTURA</option>'+
            '<option value="12">CARGAR FACTURA</option>'+
        '</select>');

        /*
        if(idetapa<=7){// Esta opción valida que la opción factoraje sea visible para Esther
            <?php
            if(in_array($this->session->userdata('inicio_sesion')["id"],[84]))
                echo '$(\'#change_options\').append(`<option value="11">CAMBIAR A FACTORAJE</option>`);';
            ?>
        }
        */

        $('#change_options').on('change',function(){
            cuenta = $(this).val();

            switch(cuenta){
                case '1':
                    //REFERENCIA BANCARIA
                    $("#modal_opciones .modal-body").html("<center><input type='text' name='valor_referencia' id='valor_referencia' class='form-control' placeholder='Digite referencia bancaria' required></center>");
                    $("#modal_opciones .modal-footer").html("<center><button type='button' class='btn btn-block bg-teal add_referencia' value="+index_solicitud+">CAMBIAR</button></center>");
                    break;
                case '2':
                    //AÑANIR ABONO
                    $("#modal_opciones").modal("hide");
                    $("#modal_abonos .modal-title > b").text(index_solicitud);
                    $("#modal_abonos").modal("show");
                    tabla_abonos.ajax.url( "Historial/abonos_sol?ids="+index_solicitud ).load();
                    saldo_abono=index_saldo;
                    acciones_abono("reg",0,0,index_solicitud);
                    break;
                case '3':
                    cambiar_empresa( tr );
                    break;
                case '4':
                    //TENDRA FACTURA
                    $("#modal_opciones .modal-body").html("");
                    $("#modal_opciones .modal-footer").html("");
                    $("#modal_opciones .modal-footer").append("<center><button type='button' class='btn btn-block bg-olive tendra_factura' value="+index_solicitud+">TENDRÁ</button><button type='button' style:margin-right: 50px;' class='btn btn-block bg-orange no_tendra' value="+index_solicitud+">NO TENDRÁ</button>");
                    break;
                case '5':
                    //CANCELAR LA SOLICTUD
                    $("#modal_opciones .modal-body").html("<textarea class='form-control' id='justificacion' rows='3' placeholder='Justificación' required></textarea>");
                    $("#modal_opciones .modal-footer").html("<center><button type='button' class='btn btn-block bg-olive cancela_solicitud' value="+index_solicitud+">CANCELAR REGISTRO</button></center>");
                    break;
                case '7':
                    //cambiar_monto_registro( tr );
                    break;
                case '6':
                    //PAUSAR SOLICITUD
                    $("#modal_opciones .modal-body").html("<textarea class='form-control' id='justificacion' rows='3' placeholder='Justificación' required></textarea>");
                    $("#modal_opciones .modal-footer").html("<center><button type='button' class='btn btn-block bg-olive pausa_solicitud' value="+index_solicitud+">PAUSAR</button></center>");                    
                    break;
                case '9':
                    cambiar_proveedor( tr );
                    break;
                case '8':
                    $("#modal_opciones .modal-body").html('<div class="col-lg-12 form-group"><label>DOCUMENTO XML</label><div class="row"><div class="col-lg-12"><div class="input-group"><input type="file" class="form-control" id="complemento" accept="text/xml" required><div class="input-group-btn"><button type="button" class="btn btn-danger cargar_nota_credito"><i class="fas fa-upload"></i></button></div></div></div></div></div>');
                    break;
                case '10':
                    $("#modal_opciones .modal-body").html('<p><b>¿Deseas eliminar la factura cargada a la solicitud #'+index_solicitud+'?</b></p>\n\
                        <br><center><button type="button" class="btn btn-block bg-olive" onclick="borra_factura('+index_solicitud+')">Eliminar</button></center>');
                    $("#modal_opciones .modal-footer").html("");
                    break;
                case '11':
                    $("#modal_opciones .modal-body").html(form_factoraje);
                    $("#modal_opciones .modal-footer").html("");
                    break;
                case '12':
                    //CARGAR NUEVA_FACTURA
                    $("#modal_opciones").modal("toggle");
                    $("#factura_complemento").modal();
                    break;
                default:
                    $("#modal_opciones .modal-body").html("");
                    $("#modal_opciones .modal-footer").html("");
                    break;

            }
        });
    
        $("#modal_opciones").modal();

    });

    //VALIDAR FORMULARIO PARA LOS COMPLEMENTOS
    $("#ncomplemento_facturas").submit( function(e) {
        e.preventDefault();
    }).validate({
        submitHandler: function( form ) {
            var data = new FormData();
            data.append("id", index_solicitud);
            data.append("xmlfile", $("#complemento")[0].files[0]);
            data.append("tpocam", $("#tipocam").val());
            data.append("validarxml", $("#validar_checkbox:checked").val() ? 1 : 0);
            data.append("cancelarxml", $("#cancelar_checkbox:checked").val() ? 1 : 0);

            $("#factura_complemento .form-control").val( '');
            $("#factura_complemento").modal( 'toggle');

            $.ajax({
                url: url + "Complementos_cxp/cargaxml_complemento",
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                method: 'POST',
                type: 'POST', // For jQuery < 1.9
                success: function(data){
                    if( data.respuesta[0] ){
                        if( data.deuda > 0.05 ){
                            alert(data.mensaje[0]+"\n \n"+data.respuesta[1] );
                        }else if( data.uuids && data.uuids.length > 0 ){
                            $('#alert-modal table.modal-data').html("");
                            $('#alert-modal table.modal-data').append(`<tr><th>SOLICITUD</th><th>FOLIO FISCAL</th></tr>`);

                            $.each( data.uuids, function( i, v ){
                                $('#alert-modal table.modal-data').append(`<tr><td>${v.idsolicitud}</td><td>${v.uuid}</td></tr>`);
                            });
                            $("#alert-modal").modal();
                        }
                    }else{
                        alert( data.respuesta[1] );
                    }
                },error: function( ){
                    
                }
            });
        }
    });

    //PARA LA CARGA DE NOTAS DE CREDITO PARA TODO REGISTRO EN EL HISTORIAL
    $( document ).on("click", "#formulario_opciones button.cargar_nota_credito", function(){
        if( $( "#formulario_opciones" ).valid() ){
            var data = new FormData();
            var row = tabla_activas.row( tr_global ).data();

            data.append("id", row.idsolicitud);
            data.append("xmlfile", $("#complemento")[0].files[0]);
            var resultado= enviar_post(data,"Complementos_cxp/notas_credito")
            
            if( resultado.respuesta[0] ){
                $("#modal_opciones").modal( 'toggle');
                row.cantidad = parseFloat( row.cantidad ) - parseFloat( resultado.monto_aplicado );
                tabla_activas.row( tr_global ).data( row ).draw();
            }else{
                alert( resultado.respuesta[1] );
            }
        }
    });

    $( document ).on("click", ".btn-caja_chica", function(){
    
        index_solicitud = $(this).attr("value");
        index_saldo = $(this).attr( "data-value" );

        var tr = $(this).closest('tr');
        tr_global = $(this).closest('tr');

        var row = tabla_activas.row( tr ).data();
        let idUsuario='<?= $this->session->userdata("inicio_sesion")['id'];?>'; // esta variable se pone de mientras, se puede optimizar, fue un cambio de urgencia para la contadora Maricela Velazquez

        $("#modal_opciones .modal-header").html("");
        $("#modal_opciones .modal-body").html("");
        $("#modal_opciones .modal-footer").html("");
        //Fecha : 12-Agosto-2025 @author Efrain Martinez <programador.analista38@ciudadmaderas.com>
        // SE AGREGA LA OPCION CANCELAR SOLICITUD AL BOTÓN masopciones EL CUAL SE MUESTRA CUANDO LA SOLICITUD DE SEA DE CAJA CHICA, TDC O VIATICOS
        var opciones_disponibles = "<button type='button' class='close' data-dismiss='modal'>&times;</button>" +
        "<h4 class='modal-title'>SELECCIONE ACCIÓN</h4>"+
        "<br>"+
        "<select id='change_options' name='change_options' class='form-control'>"+
        "<option value='' class='form-control'>-- Selecciona opción --</option>";
        ['257'].includes(idUsuario)
            ? opciones_disponibles += "<option value='3' class='form-control'>CAMBIAR EMPRESA</option>"+"<option value='12' class='form-control'>CAMBIAR PROVEEDOR DESDE XML</option>"+"<option value='5' class='form-control'>CANCELAR SOLICITUD</option>"
            : '';
        opciones_disponibles+='<option value="11" class="form-control"><i class="fas fa-exchange-alt"></i>ADELANTAR REVISIÓN</option>';        
        opciones_disponibles += "<option value=\"8\" class=\"form-control\">NOTA DE CRÉDITO</option>";
        ['257'].includes(idUsuario)
            ? opciones_disponibles += "<option value='9' class='form-control'><i class='fas fa-exchange-alt'></i> CAMBIAR PROVEEDOR</option>"
            : '';
        opciones_disponibles +='<option value="10">CARGAR FACTURA</option>'+"</select>";

        $("#modal_opciones .modal-header").append( opciones_disponibles );

        $('#change_options').on('change',function(){
            switch( $(this).val() ){
                case '3':
                    cambiar_empresa( tr );
                    break;
                //Fecha : 12-Agosto-2025 @author Efrain Martinez <programador.analista38@ciudadmaderas.com>
                // SE AGREGA EN EL MODAL EL INPUT Y EL BOTON PARA QUE EL USUARIO INGRESE LA JUSTIFICACIÓN Y SE PUEDA CANCELAR LA SOLICITUD
                case '5':
                    //CANCELAR LA SOLICTUD
                    $("#modal_opciones .modal-body").html("<textarea class='form-control' id='justificacion' rows='3' placeholder='Justificación' required></textarea>");
                    $("#modal_opciones .modal-footer").html("<center><button type='button' class='btn btn-block bg-olive cancela_solicitud_cch' value="+index_solicitud+">CANCELAR REGISTRO</button></center>");
                    break;
                case '7':
                    //cambiar_monto_registro( tr );
                    break;
                case '8':
                    $("#modal_opciones .modal-body").html('<div class="col-lg-12 form-group"><label>DOCUMENTO XML</label><div class="row"><div class="col-lg-12"><div class="input-group"><input type="file" class="form-control" id="complemento" accept="text/xml" required><div class="input-group-btn"><button type="button" class="btn btn-danger cargar_nota_credito"><i class="fas fa-upload"></i></button></div></div></div></div></div>');
                    break; 
                case '9':
                    cambiar_proveedor( tr );
                    break;
                case '10':
                    //CARGAR NUEVA_FACTURA
                    $("#modal_opciones").modal("toggle");
                    $("#factura_complemento").modal();
                    break;
                case '11':
                    $("#modal_opciones .modal-body").html('<p><b>¿Deseas adelantar la revisión a la solicitud #'+index_solicitud+'?</b></p>\n\<br><center><button type="button" style="margin-right: 50px;" class="btn bg-olive" onclick="cambiafecha_auto('+index_solicitud+')">Confirmar</button></center>');
                    break;
                case '12':
                    cambiarempresa_xml(tr);
                    break;
                default:
                    $("#modal_opciones .modal-body").html("");
                    $("#modal_opciones .modal-footer").html("");
                    break;
            }
        });
        $("#modal_opciones").modal();

    });


    $(document).on("click", ".urgente", function(){
        index_solicitud = $(this).attr("value");

        $.post( url + "Opciones/poner_urgente/"+index_solicitud).done( function(){
            $("#modal_opciones").modal('toggle');
        });
    });

    $(document).on("click", ".tendra_factura", function(){
        index_solicitud = $(this).attr("value");

        $.post( url + "Opciones/tendra_factura/"+index_solicitud).done( function(){
            $("#modal_opciones").modal('toggle');

        });

    });


    $(document).on("click", ".no_tendra", function(){
        index_solicitud = $(this).attr("value");

        $.post( url + "Opciones/no_tendra/"+index_solicitud).done( function(){
            $("#modal_opciones").modal('toggle');

        });

    });




    $(document).on("click", ".add_referencia", function(){
        index_solicitud = $(this).attr("value");
        index_referencia = document.getElementById("valor_referencia").value;

        if(index_referencia==null||index_referencia==''){
            alert("Ingrese referencia");

        }
        else{

            $.post( url + "Opciones/agregar_referencia/"+index_solicitud+'/'+index_referencia).done( function(){
            $("#modal_opciones").modal('toggle');

            });

        }
    });

    function validar_abono_ok($index_solicitud, $index_saldo){
 
        var saldo_actual = document.getElementById("cantidad_abonada").value;
        var datos_justificacion = document.getElementById("razon_abono").value;

        total = parseFloat(index_saldo).toFixed(3);

        if(parseFloat(saldo_actual)>total){
            alert("La cantidad excede $ "+formatMoney($index_saldo));
            document.getElementById("cantidad_abonada").value="";
        }
        else{
            if(saldo_actual!=""&&datos_justificacion!=""){
                $.get(url+"Historial/agregar_abono/"+saldo_actual+"/"+datos_justificacion+"/"+index_solicitud).done(function () { 
                    // $("#modal_abono").modal('toggle'); 
                    $("#modal_opciones").modal('toggle'); 
                    
                    tabla_activas.ajax.reload();
                    alert("Se abonarón $ "+formatMoney(saldo_actual));
                    document.getElementById("cantidad_abonada").value="";
                });
            }else{
                alert("Llene todos los campos");
            }
        }
    }
    
    function check(e) {
        tecla = (document.all) ? e.keyCode : e.which;
        if (tecla == 8) {
            return true;
        }
        else{
            patron = /[A-Za-z0-9 ]/;
            tecla_final = String.fromCharCode(tecla);
            return patron.test(tecla_final);
        }
    }

    //CAMBIAR PROVEEDOR DE LA SOLICITUD REGISTRADA
    function cambiar_proveedor(tr) { /** INICIO FECHA: 28-JULIO-2025 | TOM SELECT EMPRESAS-PROVEEDORES HISTORIAL SOLICITUD | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
        tr_global = tr;
        
        $("#modal_opciones .modal-body").html(`
            <div class="col-lg-12 form-group">
                <label>PROVEEDOR</label>
                <div class="row">
                    <div class="col-lg-12">
                        <select id="idproveedor" name="idproveedor" required style="display: none;"></select>
                        <br><br>
                        <button type="button" class="btn btn-block btn-danger cambio_proveedor">
                            CAMBIAR
                        </button>
                    </div>
                </div>
            </div>
        `);

        $.get(url + "Listas_select/Allproveedores/").done(function(data) {
            var proveedores = JSON.parse(data);
            var arrayProveedores = [];
            arrayProveedores.push({value: '', label: '--- Seleccione una opción ---'});
            
            for(var i = 0; i < proveedores.length; i++) {
                var v = proveedores[i];
                arrayProveedores.push({
                    value: v.idproveedor,
                    label: v.nombre + ' - ' + v.alias
                });
            }
            inputTomSelect('idproveedor', arrayProveedores, {valor:'value', texto: 'label'});
            
            $("#idproveedor")[0].tomselect.setValue('');
            $("#idproveedor").trigger('change');
            $("#idproveedor").show();
        });
    } /** FIN FECHA: 28-JULIO-2025 | TOM SELECT EMPRESAS-PROVEEDORES HISTORIAL SOLICITUD | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/

    $( document ).on("click", "#formulario_opciones button.cambio_proveedor", function(){
        if( $( "#formulario_opciones" ).valid() ){
            var data = new FormData();
            data.append( "idsolicitud", index_solicitud );
            data.append( "idproveedor", $("#formulario_opciones #idproveedor").val() );

            var data = enviar_post( data, "Cuentasxp/cambiar_proveedor" );
            if( data.resultado ){
                $("#modal_opciones").modal( 'toggle');
                tabla_activas.row( tr_global ).data( data.solicitud ).draw();
            }else{
                alert( "NO SE PUDO PROCESAR SU SOLICITUD." );
            }
        }
    });
    var obempresa = false;
    //CAMBIAR EMPRESA DE LA SOLICITUD
    /******************************************************************************************/
    function cambiar_empresa( tr ){ /** INICIO FECHA: 28-JULIO-2025 | TOM SELECT EMPRESAS-PROVEEDORES HISTORIAL SOLICITUD | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
        tr_global = tr;
        obempresa = false;
        if(tr_global.prevObject[0].id == '309'){
            $("#modal_opciones .modal-body").html(`
                <div class="col-lg-12 form-group">
                    <label>EMPRESA</label>
                    <div class="row">
                        <div class="col-lg-12">
                            <select id="idempresa" name="idempresa" required style="display: none;"></select>
                            <br>
                            <input type="text" class="form-control" name="observaciones" id="observaciones" placeholder="Observaciones de cambio de empresa" required>
                            <br><br>
                            <button type="button" class="btn btn-block btn-danger cambiar_empresa">CAMBIAR</button>
                        </div>
                    </div>
                </div>
            `);
            obempresa = true;
        } else {
            $("#modal_opciones .modal-body").html(`
                <div class="col-lg-12 form-group">
                    <label>EMPRESA</label>
                    <div class="row">
                        <div class="col-lg-12">
                            <select id="idempresa" name="idempresa" required style="display: none;"></select>
                            <br><br>
                            <button type="button" class="btn btn-block btn-danger cambiar_empresa">CAMBIAR</button>
                        </div>
                    </div>
                </div>`
            );
        }

        $.get(  url + "Listas_select/lista_empresas/" ).done( function( data ){
            var empresas = JSON.parse( data );
            var arrayEmpresas = [];
            arrayEmpresas.push({value: '', label: '--- Seleccione una opción ---'});
            
            for(var i = 0; i < empresas.length; i++) {
                var v = empresas[i];
                arrayEmpresas.push({
                    value: v.idempresa,
                    label: v.nombre
                });
            }
            inputTomSelect('idempresa', arrayEmpresas, {valor:'value', texto: 'label'});
            
            $("#idempresa")[0].tomselect.setValue('');
            $("#idempresa").trigger('change');
            $("#idempresa").show();
        });
    } /** FIN FECHA: 28-JULIO-2025 | TOM SELECT EMPRESAS-PROVEEDORES HISTORIAL SOLICITUD | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/

    $( document ).on("click", "#formulario_opciones button.cambiar_empresa", function(){
        if( $( "#formulario_opciones" ).valid() ){

            var data = new FormData();
            data.append( "idsolicitud", index_solicitud );
            data.append( "idempresa", $("#formulario_opciones #idempresa").val() );
            if(obempresa)
                data.append( "observaciones", $("#modal_opciones #observaciones").val() );
            else
                data.append( "observaciones", "" );
            var data = enviar_post( data, url + "Opciones/cambiar_empresa" );
            if( data.resultado ){
                $("#modal_opciones").modal( 'toggle');
                tabla_activas.row( tr_global ).data( data.solicitud ).draw();
            }else{
                alert( "NO SE PUDO PROCESAR SU SOLICITUD." );
            }
        }
    });
    /******************************************************************************************/

    //CAMBIAR LA CANTIDAD DEL REGISTRO
    function cambiar_monto_registr( tr ){
        $("#modal_opciones .modal-body").html('<form id="cambiar_monto"><div class="col-lg-12 form-group"><label>NUEVO MONTO</label><div class="row"><div class="col-lg-12"><input id="nmonto" name="nmonto" tipe="number" class="form-control" required><br/><button type="submit" class="btn btn-block btn-danger">CAMBIAR</button></div></div></div></form>');

        $("#cambiar_monto").submit( function(e) {
            e.preventDefault();
        }).validate({
            submitHandler: function( form ) {

                var data = new FormData();
                data.append( "idsolicitud", index_solicitud );
                data.append( "nmonto", $("#cambiar_monto #nmonto").val() );

                $.ajax({
                    url: url + "Opciones/cambiar_monto",
                    data: data,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    method: 'POST',
                    type: 'POST', // For jQuery < 1.9
                    success: function( data ){
                        if( data.resultado ){
                            $("#modal_opciones").modal( 'toggle');
                            tabla_activas.row( tr ).data( data.solicitud ).draw();
                        }else{
                            alert( "NO SE PUDO PROCESAR SU SOLICITUD." );
                        }
                    },error: function( ){
                        
                    }
                });
            }
        });
    }

    function cancelar_opcion() {
        $("#modal_opciones .modal-body").html("");
        $("#modal_opciones .modal-footer").html("");
        $("#modal_opciones").modal('toggle');
    }
    
    function borra_factura(idsol){
        $.post(
            "Historial/borra_factura",
            { idsol: idsol},
            function(data) {
                data = jQuery.parseJSON(data);
                if(data.resultado){
                    alert(data.msj);
                    $("#modal_opciones").modal('toggle');
                    tabla_activas.ajax.reload();
                }else
                    alert(data.msj);
            }
        );
    }
    
    function cambiafecha_auto(idsol){
        $.post(
            "Historial/cambiafecha_auto",
            { idsol: idsol},
            function(data) {
                data = jQuery.parseJSON(data);
                if(data.resultado){
                    alert(data.msj);
                    $("#modal_opciones").modal('toggle');
                    //tabla_activas.ajax.reload();
                }else
                    alert(data.msj);
            }
        );
    }  

    $($.fn.dataTable.tables(true)).DataTable().columns.adjust();

    async function descargaReporteExcel() {
        try {
            mostrarLoader(); // Mostrar loader
            // Limpiar errores anteriores
            $('.has-error').removeClass('has-error');
            $('.help-block').hide();

            var valido = true;
            var data = new FormData();
            var inputData;

            // Obtener los valores de las fechas
            var fechaInicio = $('#fecInicial').val();
            var fechaFin = $('#fecFinal').val();

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

            // Si hay errores, detener la ejecución
            if (!valido){
                ocultarLoader(); // Ocultar loader
                return;
            }            

            // Si todo está bien, agregar valores al FormData
            data.append('fechaInicio', moment(fechaInicio, 'DD/MM/YYYY').format('YYYY-MM-DD'));
            data.append('fechaFinal', moment(fechaFin, 'DD/MM/YYYY').format('YYYY-MM-DD'));
            data.append('tipo_reporte', tipo_reporte);
            
            // Recopilar los valores de los inputs de la tabla
            $('#historial_sol_activas thead tr:eq(0) input').each(function(i) {  
                inputData = $(this).data('value');            
                if (Object.keys(valor_input).indexOf(inputData) >= 0) {
                    data.append(inputData, valor_input[inputData]); // Agregar valores al formulario oculto
                }
            });

            // Enviamos a procesar los datos al BackEnd
            const datosEnviarGoogle = await fetch(url + "Reportes/reporte_historial_solicitudes",{
                method: 'POST',
                body: data
            });

            if (!datosEnviarGoogle.ok) {
                throw new Error("Error interno del servidor. Favor de contactar al administrador del sistema.");
                ocultarLoader(); // Ocultar loader
            }

            const blob = await datosEnviarGoogle.blob();
            
            const filename = datosEnviarGoogle.headers
                .get('Content-Disposition').split('filename=')[1]
                ?.replace(/"/g, '') || 'reporte.csv';

            // Creamos el link temporal de descarga para el archivo excel
            const urlExcel = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = urlExcel;
            a.download = filename;
            document.body.appendChild(a);
            a.click();
            a.remove();
            window.URL.revokeObjectURL(urlExcel);
            ocultarLoader(); // Ocultar loader
            
        } catch (error) {
            ocultarLoader(); // Ocultar loader
            console.error('Error en el proceso: ', error);
            alert('Ocurrió un error. Por favor, póngase en contacto con el administrador del sistema.')
        }
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
     /**
     * INICIO
     * FECHA : 12-Agosto-2025 @author Efrain Martinez <programador.analista38@ciudadmaderas.com>
     * Se envian los datos de la solicitud que se va a cancelar.
     */
    $( document ).on("click", ".cancela_solicitud_cch", function(){
        if( $( "#formulario_opciones" ).valid() ){

            index_cancelada = $(this).attr( "value" );
            justificacion = $("#justificacion").val();

            $("#modal_opciones").modal('toggle');
            enviar_post_64( function( response ){
                if( response.resultado && response.eliminada){
                    tabla_activas.row( tr_global ).remove().draw();
                }
                if( response.resultado == true && response.eliminada == false){
                    alert("¡Esta solicitud ya esta pagada!")
                }
                if(response.resultado == false){
                    alert("¡Algo ha ocurrido! Intente mas tarde.");
                }
            },{ idsolicitud : index_cancelada, justificacion : justificacion }, url + "Opciones/cancelar_historial_cch/" );
        }
    });

</script>

<?php
require("footer.php");
?>