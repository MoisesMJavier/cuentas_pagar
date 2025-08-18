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
                        <h3>HISTORIAL A FACTORAJE BANREGIO / BANBAJÍO</h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="tab-content">
                                    <div class="active tab-pane" id="listado_solicitudes">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <form id="formulario_reportefact" autocomplete="off" action="<?= site_url("Historial/reporte_factoraje") ?>" method="post">
                                                <div class="col-lg-3">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="fas fa-calendar-alt"></i> <b>INICIO</b></span>
                                                        <input class="form-control fechas_filtro from" name="finicio" type="text" id="datepicker_from" maxlength="10"/>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="fas fa-calendar-alt"></i> <b>FIN</b></span>
                                                        <input class="form-control fechas_filtro to" name="ffinal" type="text" id="datepicker_to" maxlength="10" />
                                                    </div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <div class="input-group-addon" style="padding: 4px;">
                                                        <h4 style="padding: 0; margin: 0;"><label>Total:   $<input style="border-bottom: none; border-top: none; border-right: none;  border-left: none; background: white;" disabled="disabled" readonly="readonly" type="text"  name="myText_1" id="myText_1"></label></h4>
                                                    </div>
                                                </div>
                                                <div id="elementos_hidden"></div>
                                                </form>
                                                <table class="table table-striped" id="tablahistorialsolicitudes">
                                                    <thead>
                                                        <tr>
                                                            <th></th>
                                                            <th style="font-size: .8em">#</th>
                                                            <th style="font-size: .8em">PROYECTO</th>
                                                            <th style="font-size: .8em">PROVEEDOR</th>
                                                            <th style="font-size: .8em">EMPRESA</th>
                                                            <th style="font-size: .8em">FOLIO/FISCAL</th>
                                                            <th style="font-size: .8em">F FACTURA</th>
                                                            <th style="font-size: .8em">VENCIOMIENTO</th>                                                   
                                                            <th style="font-size: .8em">DEPARTAMENTO</th>
                                                            <th style="font-size: .8em">F PAGO</th>
                                                            <th style="font-size: .8em">FECHA DISPERSIÓN</th>
                                                            <th style="font-size: .8em">CANTIDAD</th>
                                                            <th style="font-size: .8em">PAGADO</th>
                                                            <th style="font-size: .8em">ESTATUS</th>
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
    var valor_input = Array( $('#tablahistorialsolicitudes th').length );
    
    $('.fechas_filtro').datepicker({
        format: 'dd/mm/yyyy',
        endDate: '-0d'
    });
    $('#datepicker_from').on('keyup change', function(){
        var str = $(this).val();
        if(str.length==2 || str.length==5){
            $('#datepicker_from').val(str+'/');
        }
    }); $('#datepicker_to').on('keyup change', function(){
        var str = $(this).val();
        if(str.length==2 || str.length==5){
            $('#datepicker_to').val(str+'/');
        }
    }); 


    $("#tablahistorialsolicitudes").ready( function(){
        $('#tablahistorialsolicitudes thead tr:eq(0) th').each( function (i) {
            if( i != 0 && i != 14 ){
                var title = $(this).text();
                $(this).html( '<input type="text" style="font-size: .8em; width: 100%;" data-value="'+title+'" placeholder="'+title+'" />' );

                $( 'input', this ).on('keyup change', function () {
                    if (tabla_historial_solicitudes.column(i).search() !== this.value ) {

                        valor_input[i] = this.value;

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
                        document.getElementById("myText_1").value = to1;
                    }
                });
            }
        });
        
        $('#tablahistorialsolicitudes').on('xhr.dt', function ( e, settings, json, xhr ) {
         var total = 0;
         $.each( json.data,  function(i, v){
           total += parseFloat(v.cantidad);
       });
         var to = formatMoney(total);
         document.getElementById("myText_1").value = to;

     });

        tabla_historial_solicitudes = $("#tablahistorialsolicitudes").DataTable({
            dom: 'Brtip',
            "buttons": [
                {
                    text: '<i class="fas fa-file-excel"></i> GENERAR REPORTE',
                    action: function(){
                        $("#elementos_hidden").html("");
                        $('#tablahistorialsolicitudes thead tr:eq(0) input').each( function (i) {
                            if( valor_input[i+1] )
                                $("#elementos_hidden").append( '<input type="hidden" name="'+$(this).data('value')+'" value="'+valor_input[i+1]+'">' )
                        });

                        $("#formulario_reportefact").submit();
                    },
                    attr: {
                        class: 'btn btn-success'
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
                    return '<p style="font-size: .7em">'+d.idsolicitud+'</p>'
                }
            },
            {
                "orderable": false,
                "width": "8%",
                "data" : function( d ){
                    return '<p style="font-size: .65em">'+d.proyecto+'</p>'
                }
            },
            {
                "orderable": false,
                "width": "9%",
                "data" : function( d ){
                    return '<p style="font-size: .65em">'+d.nombre+'</p>'
                }
            },
            {
                "orderable": false,
                "width": "7%",
                "data" : function( d ){
                    return '<p style="font-size: .7em">'+d.abrev+'</p>'
                }
            },
            {
                "orderable": false,
                "width": "7%",
                "data" : function( d ){
                    return '<p style="font-size: .65em">'+d.folio +'/'+(d.ffiscal ? d.ffiscal : "SF")+'</p>'
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
                "width": "8%",
                "data" : function( d ){
                    return '<p style="font-size: .65em">'+(d.fvencimiento ? formato_fechaymd(d.fvencimiento) : '-' )+'</p>'
                }
            },
            {
                "orderable": false,
                "width": "9%",
                "data" : function( d ){
                    return '<p style="font-size: .65em">'+d.nomdepto+'</p>';
                }
            },
            {
                "orderable": false,
                "width": "9%",
                "data" : function( d ){
                    return '<p style="font-size: .65em">'+d.metoPago+'</p>';
                }
            },
            {
                "orderable": false,
                "width": "10%",
                "data" : function( d ){
                    return '<p style="font-size: .7em">'+(d.fechaDis ? formato_fechaymd( d.fechaDis ) : 'NA')+'</p>'
                }
            },
            {
                "orderable": false,
                "width": "10%",
                "data" : function( d ){
                    return '<p style="font-size: .7em">$ '+formatMoney( d.cantidad )+'</p>'
                }
            },            
            {
                "orderable": false,
                "width": "9%",
                "data" : function( d ){
                    return '<p style="font-size: .7em">$ '+formatMoney( d.pagado )+'</p>'
                }
            },
            {
                "orderable": false,
                "width": "9%",
                "data" : function( d ){
                    switch( d.estatus_pago ){
                        case '0':
                            return '<p style="font-size: .65em">PAGO AUTORIZADO DG</p>';
                            break;
                        case '1':
                            return '<p style="font-size: .65em">POR DISPERSAR</p>';
                            break;
                        case '2':
                            return '<p style="font-size: .65em">SE HA PAUSADO EL PROCESO DE ESTE PAGO</p>';
                            break;
                        case '5':
                            return '<p style="font-size: .65em">POR DISPERSAR</p>';
                            break;
                        case '12':
                            return '<p style="font-size: .65em">PAGO DETENIDO</p>';
                            break;
                        case '15':
                            return '<p style="font-size: .65em">' +( d.metoPago=='ECHQ'?'CHEQUE SIN COBRAR':'POR CONFIRMAR PAGO CXP') +'</p>';
                            break;
                        case '16':
                            if(d.estatus_pago && d.estatus_pago == '16' && (d.etapa=='Pago Aut. por DG, Factura Pendiente'||d.etapa=='Pagado')){ 
                                return '<p style="font-size: .65em">PAGO COMPLETO</p>' ;
                            }
                            else{
                                return '<p style="font-size: .65em">'+d.etapa+'</p>';
                            }
                            break;
                        default:
                            if( d.etapa == 'Revisión Cuentas Por Pagar'){
                                return '<p style="font-size: .65em">Espera autorización de pago.</p>';
                            }else{

                                if( d.idetapa > 9 && d.idetapa < 20 && d.idprovision == null ){
                                    return '<p style="font-size: .65em">FACTURA PAGADA, EN ESPERA DE PROVISION</p>'; 
                                }else{
                                    return '<p style="font-size: .65em">'+d.etapa+'</p>';
                                }
                            }
                            break;
                    }
                }
            },
            {
                "orderable": false,
                "data": function( d ){
                    return '<div class="btn-group-vertical"><button type="button" class="btn btn-sm btn-primary consultar_modal notification" value="'+d.idsolicitud+'" data-value="BAS" title="Ver Solicitud"><i class="fas fa-eye"></i>'+(d.visto == 0 ? '<span class="badge">!</span>' : '')+'</button></div>';
                }
            }],
            "ajax": url + "Historial/tabla_factorajes",
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
                '<td><strong>CAPTURISTA: </strong>'+row.data().ncap+'</td>'+
                '</tr>'+
                '</table>';

                row.child( informacion_adicional ).show();
                tr.addClass('shown');
                $(this).parent().find('.animacion').removeClass("fa-caret-right").addClass("fa-caret-down");
            }
        });

        $('#datepicker_from').change( function() { 
            tabla_historial_solicitudes.draw();
            var total = 0;
            var index = tabla_historial_solicitudes.rows( { selected: true, search: 'applied' } ).indexes();
            var data = tabla_historial_solicitudes.rows( index ).data();
            $.each(data, function(i, v){
                total += parseFloat(v.cantidad);
            });
            var to1 = formatMoney(total);
            document.getElementById("myText_1").value = to1;
        });

        $('#datepicker_to').change( function() { 
            tabla_historial_solicitudes.draw();
            var total = 0;
            var index = tabla_historial_solicitudes.rows( { selected: true, search: 'applied' } ).indexes();
            var data = tabla_historial_solicitudes.rows( index ).data();
            $.each(data, function(i, v){
                total += parseFloat(v.cantidad);
            });
            var to1 = formatMoney(total);
            document.getElementById("myText_1").value = to1;
        });
        
        $('.dt-buttons').attr('style','float: right !important; margin-right: 15px;');
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

        var iStartDateCol = 6;
        var iEndDateCol = 6;

        var meses = ['','Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];
        
        var mes1 =  typeof aData[iStartDateCol] === 'undefined' ? '' : (meses.indexOf(aData[iStartDateCol].split('/')[1]).toString()).padStart(2, "0");
        var mes2 =  typeof aData[iEndDateCol] === 'undefined' ? '' : (meses.indexOf(aData[iEndDateCol].split('/')[1]).toString()).padStart(2, "0");

        iFini=iFini.substring(6,10) + iFini.substring(3,5) + iFini.substring(0,2);
        iFfin=iFfin.substring(6,10) + iFfin.substring(3,5) + iFfin.substring(0,2);

        var datofini=aData[iStartDateCol].substring(7,11) + mes1 + aData[iStartDateCol].substring(0,2);
        var datoffin=aData[iEndDateCol].substring(7,11) + mes2 + aData[iEndDateCol].substring(0,2);

        if ( iFini === "" && iFfin === "" ){
            return true;
        }else if ( iFini <= datofini && iFfin === ""){
            return true;
        }else if ( iFfin >= datoffin && iFini === ""){
            return true;
        }else if (iFini <= datofini && iFfin >= datoffin){
            return true;
        }
        
        return false;

    });


    $(window).resize(function(){
        tabla_historial_solicitudes.columns.adjust();
    });

</script>

<?php
require("footer.php");
?>