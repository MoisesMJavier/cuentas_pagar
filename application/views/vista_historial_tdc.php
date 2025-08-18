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
                        <h3>HISTORIAL TARJETAS DE CREDITO  <small><i class="far fa-question-circle faq" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" title="Autorizaciones" data-content="Estas son todas las solicitudes de tarjetas de crédito en transito o ya pagadas." data-placement="right"></i></small></h3>
                    </div>
                    <div class="box-body">
                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                                <li class="active"><a data-toggle="tab" data-value="pagadas_tdc" href="pagadas_tdc">PAGOS A TDC</a></li>
                                <li><a data-toggle="tab" data-value="en_transito_tdc" href="en_transito_tdc">TDC EN TRÁNSITO</a></li>
                            </ul>
                        </div>
                        <div class="row">
                            <form id="formulario_reportetdc" autocomplete="off" action="<?= site_url("Historial/reporte_cajachica") ?>" method="post">
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

                            <table class="table table-responsive table-bordered table-striped table-hover" id="tabla_autorizaciones_tdc">
                                <thead class="thead-dark">
                                    <tr>
                                        <th style="font-size: .8em"></th>
                                        <th style="font-size: .8em"># PAGO</th>
                                        <th style="font-size: .8em">RESPONSABLE</th>
                                        <th style="font-size: .8em">EMPRESA</th>
                                        <th style="font-size: .8em">FECHA</th>
                                        <th style="font-size: .8em">TOTAL</th>
                                        <th style="font-size: .8em">MÉTODO DE PAGO</th>
                                        <th style="font-size: .8em">FECHA AUT.</th>
                                        <th style="font-size: .8em">DEPARTAMENTO</th>
                                        <th style="font-size: .8em">ESTADO</th>
                                    </tr>
                                </thead>
                            </table>

                        </div>
                    </div><!--End tab content-->
                </div><!--end box-->
            </div>
        </div>
    </div>
</div>
<script>

    var tabla_nueva_tdc;
    var result_pagadas_tdc = {};
    var result_transito_tdc = {};
    var valor_input = Array( $('#tabla_nueva_tdc th').length );
    var tabla_activa = 'pagadas';

    $('[data-toggle="tab"]').click( function(e) {
        switch( $(this).data('value') ){
            case 'pagadas_tdc':
                tabla_activa = 'pagadas';
                tabla_nueva_tdc.ajax.url( url +"Historial/tabla_tdc" ).load();
                break;
            case 'en_transito_tdc':
                tabla_activa = 'transito';
                tabla_nueva_tdc.ajax.url( url +"Historial/tabla_tdc_transito" ).load();
                break;
        }
    });

    $('.fechas_filtro').datepicker({
        format: 'dd/mm/yyyy',
        endDate: '-0d'
    });

    $("#tabla_autorizaciones_tdc").ready( function () {
 
        $('#tabla_autorizaciones_tdc thead tr:eq(0) th').each( function (i) {
            if( i != 0 ){
                var title = $(this).text();
                var vari = (((title.replace(" ","")).replace("É","E")).substring(0,6)).toLowerCase();
                $(this).html( '<input type="text" style="font-size: .9em; width: 100%;" class="form-control" data-value="'+title+'" placeholder="'+title+'" />' );
                $('input', this).on('keyup change', function() {

                    valor_input[i] = this.value;

                    if (tabla_nueva_tdc.column(i).search() !== this.value ) {
                        tabla_nueva_tdc
                        .column(i)
                        .search( this.value )
                        .draw();
                    }
                });
            }
            
        });


        $('#tabla_autorizaciones_tdc').on('xhr.dt', function ( e, settings, json, xhr ) {
        });


        tabla_nueva_tdc = $('#tabla_autorizaciones_tdc').DataTable({
            dom: 'Brtip',
            width: 'auto',
            
            "buttons": [
               {
                    extend: 'excelHtml5',             
                    text: '<i class="fas fa-file-excel"></i> REPORTE (SIN DESGLOSAR)',
                    messageTop: "LISTADO DE CAJAS CHICAS",
                    attr: {
                        class: 'btn btn-warning'       
                    },
                    exportOptions: {
                        format:{    
                            header:  function (data, columnIdx) {
                                return data.replace( '<input type="text" style="font-size: .9em; width: 100%;" class="form-control" data-value="', '' ).split('"')[0];
                            }
                        }
                    }
                },
                {
                    text: '<i class="fas fa-file-excel"></i> REPORTE DESGLOSADO',
                    action: function(){
                        $("#elementos_hidden").html("");
                        $('#tabla_autorizaciones_tdc thead tr:eq(0) input').each( function (i) {
                            if( valor_input[i+1] )
                                $("#elementos_hidden").append( '<input type="hidden" name="'+$(this).data('value')+'" value="'+valor_input[i+1]+'">' )
                        });

                        $("#elementos_hidden").append( '<input type="hidden" name="tipo_reporte" value="'+$("ul.nav-tabs li.active a[data-toggle='tab']").attr('href')+'">' );

                        if( $("#formulario_reportetdc").valid() ){
                            $("#formulario_reportetdc").submit();
                        }
                        
                    },
                    attr: {
                        class: 'btn btn-success',
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
                {
                    "width": "4%",
                    "className": 'details-control',
                    "orderable": false,
                    "data" : null,
                    "defaultContent": '<i class="fas animacion fa-caret-right"></i>'
                },
                {
                    "width": "15%",
                    "data": function( d ){
                        return '<p style="font-size: .9em">'+(d.idpago?d.idpago:"N/A")+'</p>';
                    }
                },
                {
                    "width": "15%",
                    "data": function( d ){
                        return '<p style="font-size: .9em">'+d.responsable+'</p>';
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
                        return '<p style="font-size: .9em">'+formato_fechaymd(d.fecha)+'</p>';
                    }
                },
                {
                    "width": "12%",
                    "data": function( d ){
                        return '<p style="font-size: .9em">'+formatMoney( d.cantidad )+'</p>';
                    }
                },
                {
                    "width": "12%",
                    "data": function( d ){
                        return '<p style="font-size: .9em">'+( d.tipoPago ? d.tipoPago+' '+d.referencia : 'AUN SIN DEFINIR' )+'</p>';
                    }
                },
                {
                    "width": "12%",
                    "data": function( d ){
                        return '<p style="font-size: .9em">'+( d.fecha_cobro ? formato_fechaymd(d.fecha_cobro) : '---' )+'</p>';
                    }
                },
                {
                    "width": "15%",
                    "data": function( d ){
                        return '<p style="font-size: .9em">'+d.nomdepto+'</p>';
                    }
                },
                {
                    "width": "15%",
                    "data": function( d ){
                        console.log(tabla_activa);
                        if(tabla_activa==='pagadas')
                            //return '<p style="font-size: .9em">PAGADAS</p>';
                            return '<p style="font-size: .8em; text-align: center;">'+
                            "<span class='label bg-olive'>PAGADAS</span></p>"
                        else{
                            return '<p style="font-size: .8em; text-align: center;">'+
                            (d.estado == 5 ? "<span class='label label-primary'>Revisión Cuentas Por Pagar</span>":  "<span class='label bg-olive'>Espera Autorización de Pago DG</span>" )+'</p>'
                            //return '<p style="font-size: .9em">'+d.estado+'</p>';
                        }
                            
                    }
                },
                

            ],
            bSort: false,
            "ajax" : url +"Historial/tabla_tdc"
        });

        $('#fecInicial').change( function() { 
            tabla_nueva_tdc.draw();
        });

        $('#fecFinal').change( function() { 
            tabla_nueva_tdc.draw();
        });

        $('#tabla_autorizaciones_tdc tbody').on('click', 'td.details-control', function () {

            var tr = $(this).closest('tr');
            var row = tabla_nueva_tdc.row( tr );
            
            if ( row.child.isShown() ) {
                
                row.child.hide();
                tr.removeClass('shown');

                $(this).parent().find('.animacion').removeClass("fa-caret-down").addClass("fa-caret-right");
            }
            else {
                if( row.data().solicitudes == null || row.data().solicitudes == "null" ){
                    $.post( url + "Historial/carga_cajas_chicas" , { "idcajachicas" : row.data().ID } ).done( function( data ){
                        
                        row.data().solicitudes = JSON.parse( data );

                        tabla_nueva_tdc.row( tr ).data( row.data() );

                        row = tabla_nueva_tdc.row( tr );

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
        
        function construir_subtablas( data ){
            var solicitudes = '<table class="table">';
            $.each( data, function( i, v){ 
               //i es el indice y v son los valores de cada fila
                solicitudes += '<tr>';
                solicitudes += '<td>'+v.idsolicitud+'.- <b>'+'PROYECTO: '+'</b> '+v.proyecto+'</td>';
                solicitudes += '<td colspan="3">'+'<b>'+'PROVEEDOR '+'</b> '+v.nombre_proveedor+'</td>';
                solicitudes += '<td>'+'<b>'+'CANTIDAD: '+'</b> $ '+formatMoney(v.cantidad)+' '+v.moneda+'</td>';
                solicitudes += '</tr>';
                solicitudes += '<tr>';
                solicitudes += '<td>'+'<b>'+'CAPTURISTA '+'</b> '+v.nombre_capturista+'</td>';
                solicitudes += '<td>'+'<b>'+'FOLIO '+'</b> '+v.folio+'</td>';
                solicitudes += '<td>'+'<b>'+'FOLIO FISCAL'+'</b> '+v.ffiscal+'</td>';
                solicitudes += '<td>'+'<b>'+'FECHA FACT: '+'</b> '+v.fecelab+'</td>';
                solicitudes += '<td>'+'<b>'+'FECHA AUT: '+'</b> '+v.fecautorizacion+'</td>';
                solicitudes += '</tr>';
                solicitudes += '<tr>';
                solicitudes += '<td colspan="5">'+'<b>'+'JUSTIFICACIÓN: '+'</b> '+v.justificacion+'</td>';
                solicitudes += '<td colspan="5"><div class="btn-group-vertical"><button type="button" class="btn btn-primary consultar_modal notification" value="'+v.idsolicitud+'" data-value="SOL" title="Ver Solicitud"><i class="fas fa-eye"></i>'+(v.visto == 0 ? '<span class="badge">!</span>' : '')+'</button></div></td>';
 
                solicitudes += '</tr>';
            });          

            return solicitudes += '</table>';
        }

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

        var iStartDateCol = 4;
        var iEndDateCol = 4;
        
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

    $(window).resize(function(){
        tabla_nueva_tdc.columns.adjust();
    });

</script>
<?php
require("footer.php");
?>