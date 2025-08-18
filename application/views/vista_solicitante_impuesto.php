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
                        <h3>SOLICITUD DE PAGO DE IMPUESTOS <b id="myText_1"></b></h3>
                    </div>
                    <div class="box-body">
                        <div class="tab-content">
                            <div class="active tab-pane" id="facturas_aturizar">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <table class="table table-striped" id="tabla_autorizaciones">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th></th>
                                                    <th style="font-size: .8em"># REGISTRO</th>
                                                    <th style="font-size: .8em">FOLIO</th>
                                                    <th style="font-size: .8em">PROYECTO</th>
                                                    <th style="font-size: .8em">EMPRESA</th>
                                                    <th style="font-size: .8em">FECHA</th>
                                                    <th style="font-size: .8em">PROVEEDOR</th>
                                                    <th style="font-size: .8em">CAPTURISTA</th>                                                    
                                                    <th style="font-size: .8em">CANTIDAD</th>
                                                    <th style="font-size: .8em">ESTATUS</th>
                                                    <th style="font-size: .8em"></th>
                                                </tr>
                                            </thead>
                                        </table>
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
                    <div class="col-lg-12">    <!-- Se agregan al HTML los campos de Proyecto y Oficina | FECHA: 05-Junio-2024 | @author Lalo Silva <programador.analista38@ciudadmaderas.com>-->
                        <form id="frmnewsol" method="post" action="#">
                            <div class="row">
                                <div class="col-lg-6 form-group">
                                    <label for="proyecto"><b>PROYECTO/DEPARTAMENTO</b><span class="text-danger">*</span></label>
                                    <!-- <select class="listado_proyectos form-control select2" name="proyecto" style="width: 100%;" id="proyecto" required></select> -->
                                    <select class="listado_proyectos" name="proyecto" style="width: 100%;" id="proyecto" autocomplete="off" required></select>
                                </div>
                                    <div class="col-lg-6 form-group">
                                    <label for="proyecto"><b>OFICINA/SEDE</b><span class="text-danger">*</span></label>
                                    <!-- <select class="listado_oficinas form-control select2" name="oficina" style="width: 100%;" id="oficina" required></select> -->
                                    <select class="listado_oficinas" name="oficina" style="width: 100%;" id="oficina" autocomplete="off" placeholder="Seleccione una opción" required></select>
                                </div>
                            </div>              <!-- FIN Se agregan al HTML los campos de Proyecto y Oficina | FECHA: 05-Junio-2024 | @author Lalo Silva <programador.analista38@ciudadmaderas.com>-->
                            <div class="row">
                                <div class="col-lg-12 form-group">
                                    <p>Complete los campos marcados <span class="text-red">*</span> con la información solicitada.</p>
                                    <label for="empresa">EMPRESA <span class="text-red">*</span></label>
                                    <select name="empresa" id="empresa" class="form-control lista_empresa" required></select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 form-group">
                                    <label for="proveedor">PROVEEDOR <span class="text-red">*</span></label>
                                    <select name="proveedor" class="form-control lista_provedores_libres" id="proveedor" required></select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 form-group"> <!-- Se agregan al HTML el campo de Servicio Partida | FECHA: 05-Junio-2024 | @author Lalo Silva <programador.analista38@ciudadmaderas.com>-->
                                    <label for="tServicio_partida"><b>TIPO SERVICIO/PARTIDA</b><span class="text-danger">*</span></label>
                                    <!-- <select class="listado_tServicio_partida form-control select2" name="tServicio_partida" style="width: 100%;" id="listado_tServicio_partida" required></select> -->
                                    <select class="listado_tServicio_partida" name="tServicio_partida" style="width: 100%;" id="listado_tServicio_partida" autocomplete="off" placeholder="Seleccione una opción" required></select>
                                </div>
                            </div>                                  <!-- FIN Se agregan al HTML el campo de Servicio Partida | FECHA: 05-Junio-2024 | @author Lalo Silva <programador.analista38@ciudadmaderas.com>-->
                            <div class="row">
                                <div class="col-lg-4 form-group">
                                    <label for="fecha">FECHA <span class="text-red">*</span></label>
                                    <input type="date" class="form-control" min='2019-04-01' id="fecha" name="fecha" placeholder="Fecha" value="" required>
                                </div>
                                <div class="col-lg-4 form-group">
                                    <label for="total">TOTAL <span class="text-red">*</span></label>
                                    <input type="text" class="form-control" id="total" name="total" placeholder="Total" value="" required>
                                </div>
                                <div class="col-lg-4 form-group">
                                    <label for="total">FORMA DE PAGO<span class="text-red">*</span></label>
                                    <select class="form-control" id="forma_pago" name="forma_pago" required>
                                        <option value="">Seleccione una opción</option>
                                        <option value="ECHQ" data-value="ECHQ">Cheque</option>
                                        <option value="TEA" data-value="TEA">Transferencia Electrónica</option>
                                        <option value="MAN" data-value="MAN">Manual</option>
                                    </select>
                                </div>
                                
                            </div>
                            <div class="row">
                                <div class="col-lg-12 form-group">
                                    <label for="folio">LINEA DE CAPTURA<i class="far fa-question-circle faq" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" title="Agregue las primeras los primeros dígitos de la línea de captura." data-placement="right"></i></label>
                                    <input type="text" class="form-control" minlength="10" id="referencia" name="referencia" value="">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 form-group">
                                    <label for="solobs">JUSTIFICACIÓN <span class="text-red">*</span><i class="far fa-question-circle faq" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" title="Observaciones de la solicitud" data-content="En caso de ser necesario, se podrán especificar datos, comentarios u observaciones para cuentas por pagar, DG, DP o para la dirección del departamento solicitante." data-placement="right"></i></label>
                                    <textarea class="form-control" id="solobs" name="solobs" placeholder="Observaciones de la solicitud" required></textarea>
                                </div>
                            </div>
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
<script>

    var limite_cajachica = 0;
    var documento_xml = null;
    var link_post = "";
    var depto = "";
    var idsolicitud = 0;
    var _data1 = [];
    var _data2 = [];
    var _data3 = [];
    var depto_excep_proyecto = ['CONSTRUCCION', 'JARDINERIA', 'OOAM', 'OOAM TECNICO', 'OOAM ADMINISTRATIVO']; /** Se agregan las variables para tomar en cuenta los departamentos de Lirio | FECHA: 05-Junio-2024 | @author Lalo Silva <programador.analista38@ciudadmaderas.com> **/
    var lista_proyectos_depto=[];   /** FIN Se agregan las variables para tomar en cuenta los departamentos de Lirio | FECHA: 05-Junio-2024 | @author Lalo Silva <programador.analista38@ciudadmaderas.com> **/
    const regex = /^[0-9]+$/;

    //Initialize Select2 Elements
    $('.select2').select2();

    $('.fechas_filtro').datepicker({
          format: 'dd/mm/yyyy'
    });

    var today = new Date();
    var yesterday = new Date( today );
    yesterday.setDate( today.getDate() - 40 );
    var fecha_minima = yesterday.toISOString().substr(0, 10);
    
    $(document).ready( function(){
        $.getJSON( url + "Listas_select/listado_proveedores_impuesto" ).done( function( data ){

            lista_proyectos_depto.push(data.proyectosDepartamentos); /** Se obtienen los listados de proyectos y servicio partida | FECHA: 05-Junio-2024 | @author Lalo Silva <programador.analista38@ciudadmaderas.com> **/
            
            $(".lista_empresa").append('<option value="" data-value="">Seleccione una opción</option>');
            $.each( data.empresas, function( i, v){
                $(".lista_empresa").append('<option value="'+v.idempresa+'" data-value="'+v.rfc+'">'+v.nombre+'</option>');
            });

            $(".lista_provedores_libres").append('<option value="" data-value="">Seleccione una opción</option>');
            $.each( data.cuentas_internas, function( i, v){
                $(".lista_provedores_libres").append('<option value="'+v.value+'" data-value="'+v.value+'">'+v.label+'</option>');
            });

            // FUNCION PARA TRAER LISTADO DE TIPO SERVICIO / PARTIDA
            inputTomSelect('listado_tServicio_partida', data.lista_servicio_partida, {valor: 'idTipoServicioPartida', texto:'nombre'});
        });
    });

    $("#frmnewsol").submit( function(e) {
        e.preventDefault();
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
                        $("#modal_formulario_solicitud").modal( 'toggle' );
                        table_autorizar.ajax.reload();
                    }else{
                        alert( ( data.mensaje ? data.mensaje : "Ha ocurrido algo, intente mas tarde") );
                    }
                },error: function( ){
                    
                }
            });
        }
    });

    $(document).on( "click", ".abrir_nueva_solicitud", function(){
        link_post = "Impuestos/guardar_impuesto";
        $("#frmnewsol .form-control").val("");
        $("#frmnewsol .form-control").prop("selected", false);
        $("#modal_formulario_solicitud").modal( {backdrop: 'static', keyboard: false} );
        obtenerProyectosDepartemento({})    /** Al abrir una nueva solicitud se reinicia los valores de los nuevos campos | FECHA: 05-Junio-2024 | @author Lalo Silva <programador.analista38@ciudadmaderas.com> **/
        $(".listado_tServicio_partida").val('').trigger('change') 
    });

    var table_autorizar;

    $("#tabla_autorizaciones").ready( function () {

        $('#tabla_autorizaciones').on('xhr.dt', function ( e, settings, json, xhr ) {
            //table_autorizar.button( 1 ).enable( parseInt(json.por_autorizar) > 0 );
            var total = 0;
            
            $.each( json.data,  function(i, v){
                total += parseFloat(v.cantidad);
            });

            var to = formatMoney(total);
            $("#myText_1").html( "$ " + to );
            
        });

        $('#tabla_autorizaciones thead tr:eq(0) th').each( function (i) {
            if( i != 0 && i < $('#tabla_autorizaciones thead tr:eq(0) th').length - 1 ){
                var title = $(this).text();
                $(this).html( '<input type="text" style="font-size: .9em; width: 100%;" class="form-control" placeholder="'+title+'" />' );
        
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
                           $("#myText_1").html( "$ " + to1 );
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
                    "data" : function( d ){
                        return '<p style="font-size: .7em">'+d.idsolicitud+'</p>'
                    }
                },
                { 
                    "data" : function( d ){
                        return '<p style="font-size: .7em">'+d.folio+'</p>'
                    }
                },
                {
                    "width": "9%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.proyecto+'</p>'
                    }
                },
                {
                    "width": "8%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.nempresa+'</p>'
                    }
                },
                {
                    "width": "9%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.fecelab+'</p>'
                    }
                },
                {
                    "data" : function( d ){
                        return '<p style="font-size: .7em">'+d.nombre+'</p>'
                    }
                },
                {
                    "width": "12%",
                    "data" : function( d ){
                        return '<p style="font-size: .7em">'+d.nombre_completo+'</p>'
                    }
                },
                {
                    "width": "12%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">$ '+formatMoney( d.cantidad )+" "+d.moneda+'</p>'
                    }
                },
                {
                    "width": "8%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.netapa+'</p>'
                    }
                },
                { 
                    "data" : "opciones",
                    "orderable": false
                }
            ],
            "columnDefs": [ {
                "orderable": false, "targets": 0
            }],
            "ajax":  url + "Impuestos/tabla_autorizaciones_impuestos"
        });
        
        $("#tabla_autorizaciones").DataTable().rows().every( function () {
            var tr = $(this.node());
            this.child(format(tr.data('child-value'))).show();
            tr.addClass('shown');
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

            link_post = "Impuestos/editar_impuesto";

            idsolicitud =  $(this).val();

            $.post( url + "Solicitante/informacion_solicitud", { idsolicitud : idsolicitud } ).done( function( data ){
                
                data = JSON.parse( data );
                if( data.resultado ){

                    $("#frmnewsol .form-control").val("");
                    $("#frmnewsol .form-control").prop("selected", false);
                    $("select[name='empresa'] option[value='"+data.info_solicitud[0].idempresa+"']").prop("selected", true);
                    $("select[name='proveedor'] option[value='"+data.info_solicitud[0].idproveedor+"']").prop("selected", true);
                    $("select[name='forma_pago'] option[value='"+data.info_solicitud[0].metoPago+"']").prop("selected", true);
                    $("input[name='fecha']").val( data.info_solicitud[0].fecelab );
                    $("input[name='total']").val( data.info_solicitud[0].cantidad );
                    $("input[name='referencia']").val( data.info_solicitud[0].folio );
                    $("textarea[name='solobs']").val( data.info_solicitud[0].justificacion );
                    
                    $("#modal_formulario_solicitud").modal( {backdrop: 'static', keyboard: false} );
                    // obtenerProyectosDepartemento({      /** Llamado a la función obtenerProyectosDepartemento con la configiración para editar y configura el valor de servicio partida | FECHA: 05-Junio-2024 | @author Lalo Silva <programador.analista38@ciudadmaderas.com> **/
                    //     esApi: data.info_solicitud[0].Api == 1, 
                    //     valueProyecto: data.info_solicitud[0].idProyectos, 
                    //     valueOficina: data.info_solicitud[0].idOficina,
                    //     valueProyectoViejo: data.info_solicitud[0].proyectoViejo,
                    //     esProyectoNuevo: data.info_solicitud[0].esProyectoNuevo,
                    //     departamentoSolicitud: data.info_solicitud[0].nomdepto
                    // })

                    // $('#listado_tServicio_partida').val(data.info_solicitud[0].idTipoServicioPartida).trigger('change') /** FIN Llamado a la función obtenerProyectosDepartemento con la configiración para editar y configura el valor de servicio partida  | FECHA: 05-Junio-2024 | @author Lalo Silva <programador.analista38@ciudadmaderas.com> **/
                    /************************************************************************************/
                    if (!$('#proyecto').hasClass('tomselected') ) {
                        // Inicializamos Tom Select para los selects específicos
                        new TomSelect('#proyecto');
                    }

                    if (!$('#oficina').hasClass('tomselected') ) {
                        // Inicializamos Tom Select para los selects específicos
                        new TomSelect('#oficina');
                    }

                    /**
                     * @author DANTE ALDAIR GUERRERO ALDANA <coordinador6.desarrollo@ciudadmaderas.com>
                     * @Fecha_Cambio 24 - Octubre - 2024
                     * CAMBIO PROVISIONAL:
                     * SE CAMBIA PROVISIONALMENTE EL SELECT, YA QUE LA LIBRERIA DE SELECT2 TIENE ERRORES
                     * O INCOMPATIBILIDAD CON ALGUNOS NAVEGADORES Y NO HACE LA FUNCIONALIDAD DE BUSCAR EN 
                     * EL INPUT LA OPCION POR PALABRA.
                     * SE CAMBIA POR ELEMENTO TOM SELECT.
                    */
                    
                    obtenerProyectosDepartemento({ 
                        esApi: data.info_solicitud[0].Api == 1, 
                        valueProyecto: data.info_solicitud[0].idProyectos, 
                        valueOficina: data.info_solicitud[0].idOficina,
                        valueProyectoViejo: data.info_solicitud[0].proyectoViejo,
                        esProyectoNuevo: data.info_solicitud[0].esProyectoNuevo,
                        departamentoSolicitud: data.info_solicitud[0].nomdepto
                    }, 'editar_factura');

                    // $('#listado_tServicio_partida').select2('val', data.info_solicitud[0].idTipoServicioPartida)

                    $('#listado_tServicio_partida')[0].tomselect.setValue(data.info_solicitud[0].idTipoServicioPartida);

                    /********************************************************************************************/

                }else{
                    alert( ( !data.mensaje ? "Ha ocurrido algo, intente mas tarde" : data.mensaje ) );
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
            $.post( url + "Impuestos/enviar_impuestos", { idsolicitud : $(this).val() } ).done( function( data ){
                data = JSON.parse( data );
                if( data.resultado ){
                    table_autorizar.ajax.reload(null,false);
                }else{
                    alert("HA OCURRIDO UN ERROR")
                }
            });
        });

    });

    function dateToDMY(date) {
        var d = date.getDate();
        var m = date.getMonth() + 1; //Month from 0 to 11
        var y = date.getFullYear();
        return ''  + (d <= 9 ? '0' + d : d) + '/' + (m<=9 ? '0' + m : m) + '/' + y ;
    }

    function obtenerProyectosDepartemento(params, origenFun){        
        input = { proyecto: "proyecto", oficina: 'oficina', servicioPartida: 'listado_tServicio_partida'  };
        $(`#${input.proyecto}`).empty();
        // $(`#${input.oficina}`).empty();

        $(`#${input.proyecto}`).append('<option value="" >Seleccione una opción</option>');

        $(`#${input.oficina}`).parent().show();
        $(`#${input.servicioPartida}`).parent().show();
        
        
        if ( ['editar_factura'].includes(origenFun) && (params.esProyectoNuevo == 'N' || params.valueProyectoViejo != null)) {
            if(['resear_formulario', 'resear_formulario_programado'].includes(origenFun) && Object.keys(params).length <= 0){
                inputTomSelect(input.proyecto, lista_proyectos_depto[1], {valor: 'concepto', texto: 'concepto'});
            }else if($(`#${input.proyecto}`).hasClass('tomselected') && ['editar_factura', 'editar_factura_programada'].includes(origenFun) && Object.keys(params).length > 0){
                $(`#${input.proyecto}`)[0].tomselect.setValue(params.valueProyectoViejo);
            }else{
                inputTomSelect(input.proyecto, lista_proyectos_depto[1], {valor: 'concepto', texto: 'concepto'});
            }
        }else{
            if(['resear_formulario', 'resear_formulario_programado'].includes(origenFun)){
                inputTomSelect(input.proyecto, lista_proyectos_depto[0], {valor: 'idProyectos', texto: 'concepto'});
                inputTomSelect(input.oficina);
            }else if($(`#${input.proyecto}`).hasClass('tomselected') && ['editar_factura'].includes(origenFun)){
                inputTomSelect(input.proyecto, lista_proyectos_depto[0], {valor: 'idProyectos', texto: 'concepto'});
                inputTomSelect(input.oficina);
                $(`#${input.proyecto}`)[0].tomselect.setValue(params.valueProyecto);
            }else{
                inputTomSelect(input.proyecto, lista_proyectos_depto[0], {valor: 'idProyectos', texto: 'concepto'});
                inputTomSelect(input.oficina);
            }
        }
        
        if(params.esProyectoNuevo == 'S' && params.valueProyecto){
            obtenerListadoOficinas({input: input.oficina, idOficina: params.valueOficina});
        }else if(params.esProyectoNuevo == 'N' && params.valueProyectoViejo){
            $(`#${input.proyecto}`).append('<option value="'+params.valueProyectoViejo+'">'+params.valueProyectoViejo+'</option>');
            $(`#${input.proyecto}`).val(params.valueProyecto).change();
        }
    }

    function obtenerListadoOficinas(params){
        inputValue = $('#proyecto').val();
        $.ajax({
            url: url + "Listas_select/Lista_oficinas_sedes",
            data: {
                idProyecto: regex.test(inputValue) ? inputValue : 0
            },
            type: 'POST', // For jQuery < 1.9
            success: function(data){
                data = JSON.parse(data);
                $(`#${params.input}`).empty();
                $(`#${params.input}`).append('<option value="">Seleccione una opción</option>');

                /**
                 * @author DANTE ALDAIR GUERRERO ALDANA <coordinador6.desarrollo@ciudadmaderas.com>
                 * @Fecha_Cambio 24 - Octubre - 2024
                 * CAMBIO PROVISIONAL:
                 * SE CAMBIA PROVISIONALMENTE EL SELECT, YA QUE LA LIBRERIA DE SELECT2 TIENE ERRORES
                 * O INCOMPATIBILIDAD CON ALGUNOS NAVEGADORES Y NO HACE LA FUNCIONALIDAD DE BUSCAR EN 
                 * EL INPUT LA OPCION POR PALABRA.
                 * SE CAMBIA POR ELEMENTO TOM SELECT.
                */
                if(params.idOficina == "" || !params.idOficina){
                    inputTomSelect(params.input, data.lista_oficinas_sedes, {valor: 'idOficina', texto: 'oficina'});
                }else {
                    inputTomSelect(params.input, data.lista_oficinas_sedes, {valor: 'idOficina', texto: 'oficina'});
                    $(`#${params.input}`)[0].tomselect.setValue(params.idOficina);
                }
                /********************************************************************************************/
            }
        })
    }

    $('#proyecto').change(function(){
        obtenerListadoOficinas({input:'oficina'});
    });

</script>
<?php
    require("footer.php");
?>