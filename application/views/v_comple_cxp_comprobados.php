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
                        <h3>SOLICITUDES COMPROBADAS</h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <form id="formulario_reportefactcomp" autocomplete="off" action="<?= site_url("Complementos_cxp/reporte_factcomp") ?>" method="post">
                                <div class="col-lg-3">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fas fa-calendar-alt"></i> <b>INICIO</b></span>
                                        <input class="form-control fechas_filtro from" type="text" name="finicio" id="datepicker_fromP" />
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fas fa-calendar-alt"></i> <b>FIN</b></span>
                                        <input class="form-control fechas_filtro to" type="text" name="ffin" id="datepicker_toP" />
                                    </div>
                                </div>
                                <div id="elementos_hidden"></div>
                            </form> 
                        </div>
                        <hr/>
                        <div class="row">
                            <div class="col-lg-12">
                                    <table class="table table-striped" id="tblsolfin">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th></th>
                                                <th style="font-size: .8em">#</th> 
                                                <th style="font-size: .8em">PROVEEDOR</th>
                                                <th style="font-size: .8em">DEPARTAMENTO</th>
                                                <th style="font-size: .8em">CAPTURISTA</th>
                                                <th style="font-size: .8em">FORMA_PAGO</th>
                                                <th style="font-size: .8em">EMPRESA</th>
                                                <th style="font-size: .8em">FOLIO</th>
                                                <th style="font-size: .8em">CANTIDAD</th>
                                                <th style="font-size: .8em">CANTIDAD COMP</th>
                                                <th style="font-size: .8em">FECHA FACTURA</th>
                                                <th style="font-size: .8em">FECHA AUTORIZACIÓN</th>
                                                <th style="font-size: .8em">NUM FACT</th>
                                                <td></td>
                                                <th style="font-size: .8em">JUSTIFICACION</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div><!--End tab content-->
                </div><!--end box-->
            </div>
        </div>
    </div>
</div>
<script>

    var table_complemento;
    var valor_input = Array( $('#tblsolfin th').length );

    $('.fechas_filtro').datepicker({
        format: 'dd/mm/yyyy'
    });

    $('#datepicker_fromP').change( function() { 
        table_complemento.draw();

    });
        
    $('#datepicker_toP').change( function() { 
        table_complemento.draw(); 
    });

    $("#tblsolfin").ready( function () {

        $('#tblsolfin thead tr:eq(0) th').each( function (i) {
            if( i != 0 && i != 7 && i != 8 && i != 9 && i != 10 && i != 11 && i != 12 ){
                var title = $(this).text();
                var vari = (((title.replace(" ","")).replace("É","E")).substring(0,6)).toLowerCase();
                $(this).html( '<input type="text" style="font-size: .9em; width: 100%;" data-value="'+title+'" placeholder="'+title+'" />' );
                //$(this).html( '<input type="text" style="font-size: .8em; width: 100%;" placeholder="'+title+'" />' );
        
                $( 'input', this ).on( 'keyup change', function () {

                    valor_input[i] = this.value;
                    if ( table_complemento.column(i).search() !== this.value ) {
                        table_complemento
                            .column(i)
                            .search( this.value )
                            .draw();
                    }
                } );
            }
        });

        $('#tblsolfin').on('xhr.dt', function ( e, settings, json, xhr ) {
        });

        table_complemento = $('#tblsolfin').DataTable({
            dom: 'Brtip',
            "buttons": [
                /*{
                    extend: 'excelHtml5',             
                    text: '<i class="fas fa-file-excel"></i> EXPORTAR EXCEL',
                    messageTop: "HISTORIAL GASTOS COMPROBADOS",
                    attr: {
                        class: 'btn btn-success'       
                    },
                    exportOptions: {
                        columns: [ 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16 ],
                        format: {
                            header: function (data, columnIdx) { 

                                data = data.replace( '<input type="text" style="font-size: .8em; width: 100%;" placeholder="', '' );
                                data = data.replace( '">', '' )
                                return data;
                            }
                        }
                    } 
                },*/
                {
                    text: '<i class="fas fa-file-excel"></i> HISTORIAL GASTOS COMPROBADOS',
                    action: function(){
                        $("#elementos_hidden").html("");
                        $('#tblsolfin thead tr:eq(0) input').each( function (i) {
                            if( valor_input[i+1] )
                                $("#elementos_hidden").append( '<input type="hidden" name="'+$(this).data('value')+'" value="'+valor_input[i+1]+'">' )
                        });

                        //if( $("#formulario_reportecch").valid() ){
                            $("#formulario_reportefactcomp").submit();
                        //}
                        
                    },
                    attr: {
                        class: 'btn btn-success',
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
                    "orderable": false,
                    "width": "7%", 
                    "data" : function( d ){

                        return '<p style="font-size: .8em">'+d.idsolicitud+'</p>'
                    }
                },
                {
                    "orderable": false,
                    "width": "9%", 
                    "data" : function( d ){

                        return '<p style="font-size: .8em">'+d.nproveedor+'</p>'
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
                    "width": "13%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em"> '+d.nombre_capturista+'</p>';
                    }
                },
                {
                    "orderable": false,
                    "width": "5%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em"> '+d.metoPago+'</p>';
                    }
                },
                {
                    "orderable": false,
                    "width": "5%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em"> '+d.abrev+'</p>';
                    }
                },
                {
                    "orderable": false,
                    "width": "5%",
                    "data" : function( d ){
                        if(d.nfacturas > 1){var uuid = 'N/A';}else{ var uuid = d.uuid;}
                        return '<p style="font-size: .8em"> '+d.folio+' - ' +uuid +'</p>';
                    }
                },
                {
                    "orderable": false,
                    "width": "10%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em"> '+d.cantidad+'</p>';
                    }
                },
                {
                    "orderable": false,
                    "width": "10%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em"> '+d.ccomp+'</p>';
                    }
                },
                { 
                    "orderable": false,
                    "width": "10%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em"> '+d.fecelab+'</p>'
                    }
                },
                {
                    "orderable": false, 
                    "width": "10%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em"> '+(d.fecha_autorizacion ? d.fecha_autorizacion : 'AUN NO DEFINIDA')+'</p>'
                    }
                },
                /*{
                    "orderable": false, 
                    "width": "9%",
                    "data" : function( d ){
                        if(d.complemento == "Falta complemento"){
                            return '<p style="font-size: .8em"> '+d.uuid+'&nbsp;&nbsp;'+'<br><small class="label pull-center bg-red">'+d.complemento+ '</small>' +'</p>'
                        }else if(d.complemento == "Con complemento"){
                            return '<p style="font-size: .8em"> '+d.uuid+'&nbsp;&nbsp;'+'<br><small class="label pull-center bg-green">'+d.complemento+ '</small>' +'</p>'
                        }else{
                            return '<p style="font-size: .8em"> '+d.uuid+'&nbsp;&nbsp;'+'<br><small class="label pull-center bg-blue">'+d.complemento+ '</small>' +'</p>'
                        }

                        if(d.nfacturas > 1){
                            return '<p style="font-size: .8em"> N/A &nbsp;&nbsp;'+'</p>'
                        }else{
                            return '<p style="font-size: .8em"> '+d.uuid+'&nbsp;&nbsp;</p>'
                        }
                        
                    }
                },*/
                { 
                    "orderable": false, 
                    "width": "15%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em"> '+d.nfacturas+'</p>'
                    }
                },
                {
                    "orderable": false,
                    "data": function( d ){

                        res = '<div class="btn-group-vertical"><button type="button" class="btn btn-primary btn-xs consultar_modal notification" value="'+d.idsolicitud+'" data-value="SOL" title="Ver Solicitud"><i class="far fa-eye"></i></button></div>';

                        /*
                        var res = "";
                        var f;
                        var arr;

                        arr = d.idfacturas.split(',');
                            for (var i = 0; i < d.nfacturas; i++) {
                                f = i+1;
                               res += '<div class="btn-group-vertical"><button type="button" class="btn btn-primary btn-xs consultar_modal notification" value="'+arr[i]+'" data-value="FAC" title="Ver Solicitud"><i class="fas">F'+f+'</i></button></div>';
                            }

                        /*if(d.complemento == "Con complemento"){
                            arr = d.idfacturas.split(',');
                            for (var i = 0; i < 1; i++) {
                                f = i+1;
                               res += '<div class="btn-group-vertical"><button type="button" class="btn btn-primary btn-xs consultar_modal notification" value="'+arr[i]+'" data-value="FAC" title="Ver Solicitud"><i class="fas">F'+f+'</i></button></div>';
                            }
                        }
                        else if(d.complemento == "Falta complemento"){
                            f = 1;
                            res = '<div class="btn-group-vertical"><button type="button" class="btn btn-primary btn-xs consultar_modal notification" value="'+d.idsolicitud+'" data-value="SOL" title="Ver Solicitud"><i class="fas">F'+f+'</i></button></div>';
                        }
                        else{
                            arr = d.idfacturas.split(',');
                            for (var i = 0; i < d.nfacturas; i++) {
                                f = i+1;
                               res += '<div class="btn-group-vertical"><button type="button" class="btn btn-primary btn-xs consultar_modal notification" value="'+arr[i]+'" data-value="FAC" title="Ver Solicitud"><i class="fas">F'+f+'</i></button></div>';
                            }
                        }*/

                        /*if(d.complemento == "Sin complemento" && d.nfacturas>1){
                            var arr = d.idfacturas.split(',');
                            for (var i = 0; i < d.nfacturas; i++) {
                                var f = i+1;
                               res += '<div class="btn-group-vertical"><button type="button" class="btn btn-primary btn-xs consultar_modal notification" value="'+arr[i]+'" data-value="FAC" title="Ver Solicitud"><i class="fas">F'+f+'</i></button></div>';
                            }
                        }else{
                            var f = 1;
                            res = '<div class="btn-group-vertical"><button type="button" class="btn btn-primary btn-xs consultar_modal notification" value="'+d.idsolicitud+'" data-value="SOL" title="Ver Solicitud"><i class="fas">F'+f+'</i></button></div>';
                        }*/

                        return res;
                    }
                },
                {
                    "orderable": false, 
                    "width": "10%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em"> '+d.justificacion+'</p>'
                    }
                }
            ],
            "order": [[0, 'asc']],
            "columnDefs": [ {
                   "orderable": false,
                },
                {
                    "targets": [ 14 ],
                    "visible": false,
                    "searchable": false
                },
            ],
            "ajax":  url + "Complementos_cxp/tabla_pagos_complemento"
        });

        $('#tblsolfin tbody').on('click', 'td.details-control', function () {

            var tr = $(this).closest('tr');
            var row = table_complemento.row( tr );

            if ( row.child.isShown() ) {
                
                row.child.hide();
                tr.removeClass('shown');

                $(this).parent().find('.animacion').removeClass("fa-caret-down").addClass("fa-caret-right");
            }
            else {

                var solicitudes = '<table class="table">';
                solicitudes += '<tr>';
                solicitudes += '<td style="font-size: .8em">'+'<b>'+'JUSTIFICACIÓN: '+'</b> '+row.data().justificacion+'</td>';
                solicitudes += '<td style="font-size: .8em">'+'<b>NOMBRE DEL ARCHIVO:'+'</b> '+row.data().nombre_archivo+'</td>';
                solicitudes += '<td style="font-size: .8em">'+'<b>FECHA(S) DISPERSIÓN:'+'</b> '+row.data().fechadisp+'</td>';
                solicitudes += '</tr>';        
                solicitudes += '</table>';

                row.child( solicitudes ).show();
                tr.addClass('shown');
                $(this).parent().find('.animacion').removeClass("fa-caret-right").addClass("fa-caret-down");
            }
        });
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
                
		var iStartDateCol = 10;
		var iEndDateCol = 10;

		iFini=iFini.substring(6,10) + iFini.substring(3,5)+ iFini.substring(0,2);
		iFfin=iFfin.substring(6,10) + iFfin.substring(3,5)+ iFfin.substring(0,2);
              //alert(iFini);
              // alert(iFfin);

		var datofini=aData[iStartDateCol].substring(6,10) + aData[iStartDateCol].substring(3,5)+ aData[iStartDateCol].substring(0,2);
		var datoffin=aData[iEndDateCol].substring(6,10) + aData[iEndDateCol].substring(3,5)+ aData[iEndDateCol].substring(0,2);
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
        table_complemento.columns.adjust();
    });

</script>
<?php
    require("footer.php");
?>