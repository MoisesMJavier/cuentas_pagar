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
                        <h3>HISTORIAL CAJAS CHICAS / CAJAS CHICAS CERRADAS  <small><i class="far fa-question-circle faq" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" title="Autorizaciones" data-content="Estas son todas las solicitudes de caja chica, cajas chicas cerradas en transito o ya pagadas." data-placement="right"></i></small></h3>
                    </div>
                    <div class="box-body">
                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                                <li class="active"><a data-toggle="tab" data-value="pagadas" href="#caja_chica">CAJAS CHICAS PAGADAS</a></li>
                                <li><a data-toggle="tab" data-value="en_transito" href="#caja_chica">CAJAS CHICAS EN TRÁNSITO</a></li>
                                <li><a data-toggle="tab" data-value="cch_cerradas" href="#cch_cerradas">CAJAS CHICAS CERRADAS</a></li>
                            </ul>
                        </div>
                        <div class="tab-content">
                            <div id="caja_chica" class="tab-pane active"> <!-- /** FECHA: 29-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                                <div class="row">
                                    <div class="col-lg-12"> <!-- /** INICIO FECHA: 29-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                                        <form id="formulario_reportecch" autocomplete="off" action="<?= site_url("Historial/reporte_cajachica") ?>" method="post">
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
                                            <div id="elementos_hidden"></div>
                                        </form> 
                                        <table class="table table-responsive table-bordered table-striped table-hover" id="tabla_autorizaciones_caja_chica">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th style="font-size: .8em"></th>                       <!-- COLUMNA[ 0 ]  -->
                                                    <th style="font-size: .8em"># PAGO</th>                 <!-- COLUMNA[ 1 ]  -->
                                                    <th style="font-size: .8em">RESPONSABLE</th>            <!-- COLUMNA[ 2 ]  -->
                                                    <th style="font-size: .8em">RESPONSABLE REEMBOLSO</th>  <!-- COLUMNA[ 3 ]  /** INICIO FECHA: 29-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/ -->
                                                    <th style="font-size: .8em">EMPRESA</th>                <!-- COLUMNA[ 4 ]  -->
                                                    <th style="font-size: .8em">FECHA</th>                  <!-- COLUMNA[ 5 ]  -->
                                                    <th style="font-size: .8em">TOTAL</th>                  <!-- COLUMNA[ 6 ]  -->
                                                    <th style="font-size: .8em">MÉTODO DE PAGO</th>         <!-- COLUMNA[ 7 ]  -->
                                                    <th style="font-size: .8em">FECHA AUT.</th>             <!-- COLUMNA[ 8 ]  -->
                                                    <th style="font-size: .8em">DEPARTAMENTO</th>           <!-- COLUMNA[ 9 ]  -->
                                                    <th style="font-size: .8em">ESTADO</th>                 <!-- COLUMNA[ 10 ]  -->
                                                </tr>
                                            </thead>
                                        </table>
                                    </div> <!-- /** FIN FECHA: 29-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                                </div>
                            </div>
                        </div>
                    </div><!--End tab content-->
                </div><!--end box-->
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="reporte_contabilidad" role="dialog">//
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="exampleModalLabel">CAJA CHICA CONTABILIDAD</h4>
            </div>
            <div class="modal-body">
                <form method="post" action="<?= site_url("Reportes/reporte_desglosado_CT")?>">                   
                    <div class="row">
                        <div class="col-lg-12 form-group">
                            <label>DEPARTAMENTO</label>  
                            <select class="form-control" id="depto_cch"></select>
                        </div>
                        <div class="col-lg-12 form-group">
                            <label>RESPONSABLE CAJA CHICA</label>  
                            <select class="form-control" id="resp_cch"></select>
                        </div>
                        <div class="col-lg-12 form-group">
                            <label>REEMBOLSOS HECHOS</label>  
                            <select class="form-control" id="reeb_cch" name="reeb_cch"></select>
                        </div>
                        <div class="col-lg-12 form-group">
                            <label>PROYECTO</label>  
                            <select class="form-control" id="proy_cch" name="proy_cch">
                            </select>    
                        </div>
                        <div class="col-lg-12 form-group">
                            <button type="submit" class="btn btn-block btn-danger">DESCARGAR</button>
                        </div>
                    </div>                           
                </form>
            </div>
        </div>
    </div>
</div>

<script>

    var tabla_nueva_caja_chica;
    var cajas_chicas_pagadas = true;
    var cch_pagadas = [];
    var valor_input = Array( $('#tabla_autorizaciones_caja_chica th').length );
    btn_remover = false;
    var proyectos = [];
    var id_empresa ;
    var tab_actual;

    $('[data-toggle="tab"]').click( function(e) {

        cajas_chicas_pagadas = false;
        tab_actual = $(this).data('value');
        switch( $(this).data('value') ){
            case 'pagadas':
                cajas_chicas_pagadas = true;
                btn_remover = false;
                tabla_nueva_caja_chica.ajax.url( url +"Historial/tabla_cajas_chicas" ).load();
                if($('.dt-buttons').children('button').length < 3){ /** INICIO FECHA: 07-ENERO-2025 @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                    tabla_nueva_caja_chica.button().add( 2, {
                        text: '<i class="fas fa-file-excel"></i> DESGLOSE CONTABILIDAD',
                        action: function(){
                            $("#reporte_contabilidad").modal();
                        },
                        attr: {
                            class: 'btn btn-primary',
                        }
                    });
                } /** FIN FECHA: 07-ENERO-2025 @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                break;
            case 'en_transito':
                tabla_nueva_caja_chica.column(10).visible(true); /** FECHA: 29-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                tabla_nueva_caja_chica.ajax.url( url +"Historial/tabla_cajas_chicas_transito" ).load();
                break;
            case 'cch_cerradas':
                tabla_nueva_caja_chica.column(10).visible(false); /** FECHA: 29-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                tabla_nueva_caja_chica.ajax.url( url +"Historial/tabla_cajas_chicas_cerradas" ).load();
                break;
        }
    });

    $(document).ready(function() { /** INICIO FECHA: 07-ENERO-2025 @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
        const fechaActual = new Date().toLocaleDateString('es-ES', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric'
        });

        // Configura el datepicker inicial
        $('#fecInicial').datepicker({
            dateFormat: 'dd/mm/yyyy', // Formato de fecha deseado
            defaultDate: '01/01/'+new Date().getFullYear(), // Establece la fecha predeterminada
            onSelect: function(dateText) {
                $(this).val(dateText); // Establece el valor del campo de entrada con la fecha seleccionada
            }
        });
        // Asigna la fecha predeterminada al campo de entrada
        $('#fecInicial').datepicker('setDate', '01/01/'+new Date().getFullYear());

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
    }); /** FIN FECHA: 07-ENERO-2025 @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
    
    $('.fechas_filtro').datepicker({
        format: 'dd/mm/yyyy',
        endDate: '-0d',
        zIndexOffset: 10000
        , orientation: 'bottom auto', /** FECHA: 29-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
    });


    $("#tabla_autorizaciones_caja_chica").ready( function () {
 
        $('#tabla_autorizaciones_caja_chica thead tr:eq(0) th').each( function (i) {
            if( i != 0 ){
                var title = $(this).text();
                var vari = (((title.replace(" ","")).replace("É","E")).substring(0,6)).toLowerCase();
                $(this).html( '<input type="text" style="font-size: .9em; width: 100%;" class="form-control" data-value="'+title+'" placeholder="'+title+'" />' );
                $('input', this).on('keyup change', function() {

                    valor_input[i] = this.value;

                    if (tabla_nueva_caja_chica.column(i).search() !== this.value ) {
                        tabla_nueva_caja_chica
                        .column(i)
                        .search( this.value )
                        .draw();
                    }
                });
            }
            
        });

        $('#tabla_autorizaciones_caja_chica').on('xhr.dt', function ( e, settings, json, xhr ) {

            // console.log( cajas_chicas_pagadas )

            if( json.permiso_desglose === false && !btn_remover ){
                tabla_nueva_caja_chica.button( '2' ).remove();
                btn_remover = true;
            }else{
                if( cajas_chicas_pagadas ){
                    $("#depto_cch, #resp_cch, #proy_cch, #empresa_cch").html("<option value=''>Seleccione una opción</option>");
                    
                    $.each( json.data, function( i, v ){
                        if( !cch_pagadas[ v.nomdepto ] ){
                            cch_pagadas[ v.nomdepto ] = [];
                        }
                        if( !cch_pagadas[ v.nomdepto ][ v.responsable ] ){
                            cch_pagadas[ v.nomdepto ][ v.responsable ] = [];   
                        }
                        cch_pagadas[ v.nomdepto ][ v.responsable ].push( { "idempresa" : v.idempresa, "empresa" : v.abrev, "cantidad" : v.cantidad,"idpago" : v.idpago, "fecha_operacion" : v.fecha } );
                    });
                    $.each( Object.keys( cch_pagadas ).sort(), function( i, v ){
                        $("#depto_cch").append("<option value='"+v+"'>"+v+"</option>");
                    });
                    proyectos = json.proyectos;
                   // $.each( json.proyectos, function( i, v ){
                     //   $("#proy_cch").append("<option value='"+v.nproyecto_neo+"'>"+v.nproyecto_neo+"</option>");
                    //});

                }
            }
        });

        $("#depto_cch").change( function(){    
            $("#resp_cch").html("<option value=''>Seleccione una opción</option>");                         
            $.each( Object.keys( cch_pagadas[ $(this).val() ] ).sort(), function( i, v ){
                $("#resp_cch").append("<option value='"+v+"'>"+v+"</option>");
            });
        });
        
        $("#resp_cch").change( function(){   
            $("#reeb_cch").html("<option value=''>Seleccione una opción</option>");      
            $.each( cch_pagadas[ $( "#depto_cch" ).val() ][ $(this).val() ], function( i, v ){
                $("#reeb_cch").append( '<option value="'+v.idpago+'">#'+v.idpago+" | "+formato_fechaymd(v.fecha_operacion)+", "+v.empresa+" $ "+formatMoney(v.cantidad)+'</option>' )
            });
        });
        
        $("#reeb_cch").change(function(){
            $("#proy_cch").empty();
            $("#proy_cch").html("<option value=''>Seleccione una opción</option>");
            id_pago=$(this).val();
            $.each(cch_pagadas[ $( "#depto_cch" ).val() ][ $("#resp_cch").val() ], function(i,v){
                if(v.idpago==id_pago){
                    id_empresa = v.idempresa;    
                 }
            });
            $.each(proyectos.filter(proyecto => proyecto["idempresa"]==id_empresa ), function( i, v ){
                $("#proy_cch").append("<option value='"+v.nproyecto_neo+"'>"+v.nproyecto_neo+"</option>"); });
        });

        $("#fpago_cch").change( function(){           
                             
            $.each( cch_pagadas[ $( "#depto_cch" ).val() ][ $(this).val() ], function( i, v ){
                if( (v.fecha_operacion).substring( 0, 7 ) == $("#fpago_cch").val() ){
                    
                }
            });
        });

        tabla_nueva_caja_chica = $('#tabla_autorizaciones_caja_chica').DataTable({
            dom: 'Brtip',
            width: 'auto',
            "buttons": [
               {
                    extend: 'excelHtml5',             
                    text: '<i class="fas fa-file-excel"></i> GENERAL',
                    messageTop: "LISTADO DE CAJAS CHICAS",
                    attr: {
                        class: 'btn btn-warning'       
                    },
                    exportOptions: {
                        columns: function(idx, data, node){ /** INICIO FECHA: 29-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                            if ($('ul.nav-tabs').children('li.active').eq(0).children('a').data('value') !== 'en_transito'){
                                if($.inArray(idx, [1, 2, 3, 4, 5, 6, 7, 8, 9]) !== -1) return true;
                            }else{
                                if($.inArray(idx, [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]) !== -1) return true;
                            }
                        },/** FIN FECHA: 29-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                        format:{    
                            header:  function (data, columnIdx) {
                                return data.replace( '<input type="text" style="font-size: .9em; width: 100%;" class="form-control" data-value="', '' ).split('"')[0];
                            }
                        }
                    }
                },
                {
                    text: '<i class="fas fa-file-excel"></i> DESGLOSADO',
                    action: function(){
                        $("#elementos_hidden").html("");
                        $('#tabla_autorizaciones_caja_chica thead tr:eq(0) input').each( function (i) {
                            if( valor_input[i+1] )
                                $("#elementos_hidden").append( '<input type="hidden" name="'+$(this).data('value')+'" value="'+valor_input[i+1]+'">' )
                        });

                        $("#elementos_hidden").append( '<input type="hidden" name="tipo_reporte" value="'+$("ul.nav-tabs li.active a[data-toggle='tab']").data('value')+'">' );

                        if( $("#formulario_reportecch").valid() ){
                            $("#formulario_reportecch").submit();
                        }
                        
                    },
                    attr: {
                        class: 'btn btn-success',
                    }
                },
                {
                    text: '<i class="fas fa-file-excel"></i> DESGLOSE CONTABILIDAD',
                    action: function(){
                        $("#reporte_contabilidad").modal();
                    },
                    attr: {
                        class: 'btn btn-primary',
                    }
                }
            ],
            "language" : lenguaje,
            "processing": false,
            "pageLength": 20,
            "bAutoWidth": false,
            "bLengthChange": false,
            "bInfo": false,
            "scrollX": true,
            responsive: true,
            "columns": [
                // COLUMNA # 0
                {
                    "width": "4%",
                    "className": 'details-control',
                    "orderable": false,
                    "data" : null,
                    "defaultContent": '<i class="fas animacion fa-caret-right"></i>'
                },
                // COLUMNA # 1
                {
                    "width": "15%",
                    "data": function( d ){
                        return '<p style="font-size: .9em">'+(d.idpago?d.idpago:"N/A")+'</p>';
                    }
                },
                // COLUMNA # 2
                {
                    "width": "15%",
                    "data": function( d ){
                        return '<p style="font-size: .9em">'+d.responsable+'</p>';
                    }
                },
                // COLUMNA # 3
                { /** INICIO FECHA: 29-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                    "width": "15%",
                    "data": function( d ){
                        return '<p style="font-size: .9em">'+(d.nombre_reembolso_cch ? d.nombre_reembolso_cch : 'NA')+'</p>';
                    }
                }, /** FIN FECHA: 29-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                // COLUMNA # 4
                {
                    "width": "12%",
                    "data": function( d ){
                        return '<p style="font-size: .9em">'+d.abrev+'</p>';
                    }
                },
                // COLUMNA # 5
                {
                    "width": "12%",
                    "data": function( d ){
                        return '<p style="font-size: .9em">'+formato_fechaymd(d.fecha)+'</p>';
                    }
                },
                // COLUMNA # 6
                {
                    "width": "12%",
                    "data": function( d ){
                        return '<p style="font-size: .9em">'+formatMoney( d.cantidad )+'</p>';
                    }
                },
                // COLUMNA # 7
                {
                    "width": "12%",
                    "data": function( d ){
                        return '<p style="font-size: .9em">'+( d.tipoPago ? d.tipoPago+' '+d.referencia : 'AUN SIN DEFINIR' )+'</p>';
                    }
                },
                // COLUMNA # 8
                {
                    "width": "12%",
                    "data": function( d ){
                        return '<p style="font-size: .9em">'+( d.fecha_cobro ? formato_fechaymd(d.fecha_cobro) : '---' )+'</p>';
                    }
                },
                // COLUMNA # 9
                {
                    "width": "15%",
                    "data": function( d ){
                        return '<p style="font-size: .9em">'+d.nomdepto+'</p>';
                    }
                },
                // COLUMNA # 10
                {
                    "width": "12%",
                    "data": function( d ){
                        return '<p style="font-size: .8em; text-align: center;">'+ /** INICIO FECHA: 29-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                                (d.estatus == 5 ? "<span class='label label-primary'>Revisión Cuentas Por Pagar</span>" : "<span class='label bg-olive'>Espera Autorización de Pago DG</span>" )
                            +'</p>'; /** FIN FECHA: 29-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                    }
                },
            ],
            bSort: false,
            "ajax" : url +"Historial/tabla_cajas_chicas" /** FECHA: 29-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
        });
        tabla_nueva_caja_chica.column(10).visible(false); /** FECHA: 29-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
        $('#fecInicial').change( function() { 
            tabla_nueva_caja_chica.draw();
        });

        $('#fecFinal').change( function() { 
            tabla_nueva_caja_chica.draw();
        });

        $('#tabla_autorizaciones_caja_chica tbody').on('click', 'td.details-control', function () {

            var tr = $(this).closest('tr');
            var row = tabla_nueva_caja_chica.row( tr );
            
            if ( row.child.isShown() ) {
                
                row.child.hide();
                tr.removeClass('shown');

                $(this).parent().find('.animacion').removeClass("fa-caret-down").addClass("fa-caret-right");
            }
            else {

                if( row.data().solicitudes == null || row.data().solicitudes == "null" ){
                    $.post( url + "Historial/carga_cajas_chicas" , { "idcajachicas" : row.data().idsolicitud } ).done( function( data ){
                        
                        row.data().solicitudes = JSON.parse( data );

                        tabla_nueva_caja_chica.row( tr ).data( row.data() );

                        row = tabla_nueva_caja_chica.row( tr );

                        row.child( construir_subtablas( row.data().solicitudes ) ).show();
                        tr.addClass('shown');
                        $(this).parent().find('.animacion').removeClass("fa-caret-right").addClass("fa-caret-down");

                    });
                }else{
                    row.child( construir_subtablas( row.data().solicitudes ) ).show();
                    tr.addClass('shown');
                    $(this).parent().find('.animacion').removeClass("fa-caret-right").addClass("fa-caret-down");
                }

            }
        });

        function construir_subtablas( data ){ /** INICIO FECHA: 29-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
            var solicitudes = '<div class="container" style="width: 100%; padding-top: 10px; padding-bottom: 10px; border-left: 1px solid #D2D6DE; border-right: 1px solid #D2D6DE; border-bottom: 1px solid #D2D6DE; border-radius: 0 0 5px 5px;">';
            $.each(data, function(i, v) {
                //i es el indice y v son los valores de cada fila
                solicitudes += `<div class="row" style="padding-right: 10px; padding-left: 10px;">`;
                solicitudes += `
                    <div class="row">
                        <div class="col-md-12">
                            <p><b>NO. SOLICITUD:</b> ${v.idsolicitud} </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <p><b>PROYECTO:</b> ${v.proyecto} </p>
                            <p><b>PROVEEDOR:</b> ${v.nombre_proveedor} </p>
                            <p><b>CANTIDAD:</b> ${formatMoney(v.cantidad) + ' ' + v.moneda} </p>
                        </div>
                        <div class="col-md-4">
                            <p><b>FECHA FACT:</b> ${v.fecelab} </p>
                            <p><b>FECHA AUT:</b> ${v.fecautorizacion} </p>
                            <p><b>TIPO SOLICITUD:</b> ${v.tipo_sol} </p>
                        </div>
                        <div class="col-md-4">
                        <p><b>CAPTURISTA:</b> ${v.nombre_capturista} </p>
                            <p><b>FOLIO:</b> ${v.folio} </p>
                            <p><b>FOLIO FISCAL:</b> ${v.ffiscal} </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <p><b>JUSTIFICACIÓN:</b> ${v.justificacion} </p>
                            <button type="button" class="btn btn-primary consultar_modal notification" value="${v.idsolicitud}" data-value="SOL" title="Ver Solicitud">
                                <i class="fas fa-eye"></i> VER SOLICITUD 
                                ${(v.visto == 0 ? '<span class="badge">!</span>' : '')}
                            </button>
                        </div>
                    </div>
                `;
                solicitudes += `</div>
                    ${(i < data.length - 1 ? '<hr style="border-top: 1px solid #BEBEBE; margin-top: 10px; margin-bottom: 10px;">' : '')}
                `;
            });
            solicitudes += '</div>';
            return solicitudes;
        } /** FIN FECHA: 29-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/

        $('.dt-buttons').attr('style','float: right !important; margin-right: 15px;');
    });

 
    $.fn.dataTableExt.afnFiltering.push( function( oSettings, aData, iDataIndex ) {
		var iFini = '';
        var iFinit = '';
        $('.from').each(function(i,v) {
            if($(this).val() !=''){
                iFini = $(this).val();
                //iFinit = $(this).val().toString();
                //iFini = iFini.split('-')[2] + "/" + iFini.split('-')[1] + "/" + iFini.split('-')[0] ;
                return false;
            }
        }); 

        var iFfin = ''; 
        var iFfint = ''; 
        $('.to').each(function(i,v) {
            if($(this).val() !=''){
                iFfin = $(this).val();
                //iFfint = $(this).val().toString();
                //iFfin = iFfint.split('-')[2] + "/" + iFfint.split('-')[1] + "/" + iFfint.split('-')[0] ;
                return false;
            }
        }); 

        var iStartDateCol = 5;
        var iEndDateCol = 5;
        
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
    });

    $(window).resize(function(){ /** INICIO FECHA: 07-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
        tabla_nueva_caja_chica.columns.adjust().draw(false);
    });

    $('.sidebar-toggle').click(function() {
        setTimeout(function() {
            // 1. Ajustar columnas
            tabla_nueva_caja_chica.columns.adjust();
            // 2. Forzar actualización de encabezados
            var headerCellsTablaAutorizacionesCajaChica = $('#tabla_autorizaciones_caja_chica thead th');
            headerCellsTablaAutorizacionesCajaChica.each(function() {
                var newWidth = $(this).width(); // Obtener ancho actualizado
                $(this).css('width', newWidth + 'px'); // Aplicarlo
            });
            // 3. Redibujar (sin resetear paginación)
            tabla_nueva_caja_chica.draw(false);
        }, 300); // Esperar a que termine la animación del sidebar
    }); /** FIN FECHA: 07-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/

</script>
<?php
require("footer.php");
?>