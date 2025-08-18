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
                        <h3>HISTORIAL DE PAGOS</h3>
                    </div>
                    <div class="box-body">
                        <!--<div class="row">
                            <div class="col-lg-12">-->
                                <div class="tab-content">
                                    <div class="tab-pane active" id="listado_pagos">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="col-lg-3" style="margin-bottom: 30px;">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="fas fa-calendar-alt"></i> <b>INICIO</b></span>
                                                            <input class="form-control fechas_filtro from" type="text" id="datepicker_fromP" maxlength="10"/>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="fas fa-calendar-alt"></i> <b>FIN</b></span>
                                                            <input class="form-control fechas_filtro to" type="text" id="datepicker_toP" maxlength="10"/>
                                                        </div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <div class="input-group-addon" style="padding: 4px;">
                                                        <h4 style="padding: 0; margin: 0;"><label>&nbsp;Total: $<input style="text-align: right; border-bottom: none; border-top: none; border-right: none;  border-left: none; background: white;" disabled="disabled" readonly="readonly" type="text"  name="myText_1" id="myText_1"></label></h4>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3"></div>
                                                
                                                <table class="table table-striped " id="tablahistorialpagos">
                                                    <thead>
                                                        <tr>
                                                            <th style="font-size: .9em">#</th>
                                                            <th style="font-size: .9em">FOLIO</th>            
                                                            <th style="font-size: .9em">PROVEEDOR</th>
                                                            <th style="font-size: .9em">JUSTIFICACION</th>
                                                            <th style="font-size: .9em">F AUTORIZADO</th>
                                                            <th style="font-size: .9em">F DISPERSIÓN</th>
                                                            <th style="font-size: .9em">F COBRO</th>
                                                            <th style="font-size: .9em">EMPRESA</th>
                                                            <th style="font-size: .9em">DEPARTAMENTO</th>
                                                            <th style="font-size: .9em">CANTIDAD</th>                                                           
                                                            <th style="font-size: .9em">MÉTODO PAGO</th>
                                                            <th style="font-size: .9em">ESTATUS</th>
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
<script>

    var tabla_historial_pagos;
    var total;
    
    $('.fechas_filtro').datepicker({
        format: 'dd/mm/yyyy',
        endDate: '-0d'
    });
    $('#datepicker_fromP').on('keyup change', function(){
        var str = $(this).val();
        if(str.length==2 || str.length==5){
            $('#datepicker_fromP').val(str+'/');
        }
    }); $('#datepicker_toP').on('keyup change', function(){
        var str = $(this).val();
        if(str.length==2 || str.length==5){
            $('#datepicker_toP').val(str+'/');
        }
    }); 

    $("#tablahistorialpagos").ready( function(){

        $('#tablahistorialpagos thead tr:eq(0) th').each( function (i) {

            if( i < $('#tablahistorialpagos thead tr:eq(0) th').length - 1 ){
                var title = $(this).text();
                $(this).html( '<input type="text" style="font-size: .9em; width: 100%;" placeholder="'+title+'" />' );
                

                $( 'input', this ).on('keyup change', function () {
                    if (tabla_historial_pagos.column(i).search() !== this.value ) {
                        tabla_historial_pagos
                            .column(i)
                            .search( this.value)
                            .draw();

                        var total = 0;
                        var index = tabla_historial_pagos.rows( { selected: true, search: 'applied' } ).indexes();
                        var data = tabla_historial_pagos.rows( index ).data();
                        $.each(data, function(i, v){
                            total += parseFloat(v.cantidad);
                        });
                        $("#myText_1").val( formatMoney(total) );
                    }
                });
            }
            
        });

        $('#tablahistorialpagos').on('xhr.dt', function ( e, settings, json, xhr ) {
            total = 0;
            
            $.each( json.data,  function(i, v){
                total += parseFloat(v.cantidad);
            });

            $("#myText_1").val( formatMoney(total) );
        });

        tabla_historial_pagos = $("#tablahistorialpagos").DataTable({
            dom: 'Brtip',
            "buttons": [

             {
                    extend: 'excelHtml5',             
                    text: '<i class="fas fa-file-excel"></i> EXPORTAR',
                    messageTop: "HISTORIAL DE PAGOS REALIZADOS",
                    attr: {
                        class: 'btn btn-success'       
                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11],
                        format: {
                            header: function (data, columnIdx) { 

                                data = data.replace( '<input type="text" style="font-size: .9em; width: 100%;" placeholder="', '' );
                                data = data.replace( '">', '' )
                                return data;
                            }
                        }
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
                        return '<p style="font-size: .8em">'+d.folio+'</p>'
                    }
                },
                {
                    "width": "15%",
                    "data" : function( d ){
                        return '<p style="font-size: .75em">'+d.nombre+'</p>'
                    }
                },
                {
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.justificacion+'</p>'
                    }
                },
                {
                    "width": "10%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+formato_fechaymd(d.fechaaut)+'</p>'
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
                        return '<p style="font-size: .8em">'+( d.fecha_cobro ? formato_fechaymd(d.fecha_cobro): "---" ) +'</p>'
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
                {
                    "width": "8%",
                     "data": function( d ){
                         return '<p style="font-size: .8em">'+formatMoney( d.cantidad )+'</p>';
                     }
                },
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
                            return '<p style="font-size: .8em">AUTORIZADO POR DG</p>';
                                break;
                            case '1':
                            case '5':
                                return '<p style="font-size: .8em">DISPERSANDO</p>';
                                break;
                            case '11':
                                return '<p style="font-size: .8em">EN ESPERA PARA ENVIAR A DISPERCION</p>';
                                break;
                            case '12':
                                return '<p style="font-size: .8em">PAGO DETENIDO</p>';
                                break;
                            case '15':
                                return '<p style="font-size: .8em">POR CONFIRMAR PAGO </p>';
                                break;
                            case '16':
                                return '<p style="font-size: .8em">PAGO COMPLETO</p>';
                                break;
                            default:
                                return '<p style="font-size: .8em">PROCESANDO PAGO CXP</p>';
                                break;   
                        }
                    }
                },
                {
                    "width": "10%",
                    "data" : function( d ){
                        return '<div class="btn-group"><button type="button" class="btn btn-primary consultar_modal notification" value="'+d.idsolicitud+'" data-value="SOL" title="Ver Solicitud"><i class="fas fa-eye"></i>'+(d.visto == 0 ? '<span class="badge">!</span>' : '')+'</button></div>';
                    }
                } 
            ],
            "columnDefs": [ {
                   "orderable": false
                },
                {
                    "targets": [ 3 ],
                    "visible": false,
                    "searchable": false
                }
            ],
            order: [[0, "asc"]],
            bSort: false,
            "ajax": url + "Historial/TablaHPagosAdministrativo",
        });
        
        $('#datepicker_fromP').change( function() { 
           tabla_historial_pagos.draw();

            total = 0;
            var index = tabla_historial_pagos.rows( { selected: true, search: 'applied' } ).indexes();
            var data = tabla_historial_pagos.rows( index ).data();
            $.each(data, function(i, v){
                total += parseFloat(v.cantidad);
            });
            var to1 = formatMoney(total);
            document.getElementById("myText_1").value = to1;

        });
        
        $('#datepicker_toP').change( function() { 
           tabla_historial_pagos.draw(); 

            total = 0;
            var index = tabla_historial_pagos.rows( { selected: true, search: 'applied' } ).indexes();
            var data = tabla_historial_pagos.rows( index ).data();
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
                
		var iStartDateCol = 5;
		var iEndDateCol = 5;

		var meses = ['','Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
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
        tabla_historial_pagos.columns.adjust();
    });
</script>

<?php
    require("footer.php");
?>