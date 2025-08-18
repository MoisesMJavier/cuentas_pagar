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
                        <div class="col-lg-12">
                            <div class="col-lg-3">
                                <select id="tipo_gasto" class="form-control">
                                    <option value="0">PROVEEDOR</option>
                                    <option value="1">CAJA CHICA</option>
                                </select>
                            </div>
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
                            <table class="table table-striped" id="tblsolfin">
                                <thead class="thead-dark">
                                    <tr>
                                        <th></th>
                                        <th style="font-size: .8em">#</th> 
                                        <th style="font-size: .8em">PROVEEDOR</th>
                                        <th style="font-size: .8em">DEPARTAMENTO</th>
                                        <th style="font-size: .8em">FORMA_PAGO</th>
                                        <th style="font-size: .8em">EMPRESA</th>
                                        <th style="font-size: .8em">FOLIO</th>
                                        <th style="font-size: .8em">UUID</th>
                                        <th style="font-size: .8em">CANTIDAD</th>
                                        <th style="font-size: .8em">F ALTA</th>
                                        <td></td>
                                        <th style="font-size: .8em">JUSTIFICACION</th>
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
<div id="factura_complemento" class="modal fade modal-alertas" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">RELACIONAR FACTURAS</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <form id="complemento_facturas">
                        <div class="col-lg-12 form-group">
                            <label>#SOLICITUDES RELACIONADAS</label>                            
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="nsolicitudes" placeholder="# Solicitudes sin espacio separadas por una coma">                            
                                        <div class="input-group-btn">
                                            <button type="button" class="btn btn-danger nsolicitudes"><i class="fas fa-upload"></i></button>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="completas">
                                                <label class="form-check-label" for="completas">Marcar como completas</label>
                                            </div>
                                        </div>
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
<script>

    var tdglobal;
    var table_complemento;
    var relacionar_factura = false;
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

    //ABRIMOS EL MODAL PARA RELACIONAR LAS SOLICITUDES
    $(document).on('click', '.fac_relacionadas', function(){
        tdglobal = $(this).closest('tr');
        $("#nsolicitudes").val( table_complemento.row( tdglobal ).data().idsolicitudr );
        $("#factura_complemento").modal();
    });

    //ENVIAMOS LA INFORMACION PARA INICIAR CON LA RELACIO DE LOS REGISTROS
    $(".nsolicitudes").click( function(){
        nsolicitudes = $("#nsolicitudes").val();
        row = table_complemento.row( tdglobal ).data();
        enviar_post_64( function(data){
            if( data.resultado ){
                row.idsolicitudr = nsolicitudes;
                table_complemento.row( tdglobal ).data( row ).draw();
                $("#factura_complemento").modal("toggle")
            }
        }, { idfactura: row.idfactura, numsols : $("#nsolicitudes").val(), completadas : $("#completas").val() }, "relacionar_nsol")
    });

    $("#tblsolfin").ready( function () {

        $('#tblsolfin thead tr:eq(0) th').each( function (i) {
            if( i > 0 && i < $('#tblsolfin thead tr:eq(0) th').length -1 ){
                var title = $(this).text();
                var vari = (((title.replace(" ","")).replace("É","E")).substring(0,6)).toLowerCase();
                $(this).html( '<input type="text" class="form-control" style="font-size: .9em; width: 100%;" data-value="'+title+'" placeholder="'+title+'" />' );
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
            relacionar_factura = json.permisos;
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
                    text: '<i class="fas fa-file-excel"></i>',
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
                    "width": "25%", 
                    "data" : function( d ){

                        return '<p style="font-size: .8em">'+d.nproveedor+'</p>'
                    }
                },
                {
                    "orderable": false,
                    "width": "10%", 
                    "data" : function( d ){

                        return '<p style="font-size: .8em">'+d.nomdepto+'</p>'
                    }
                },
                {
                    "orderable": false,
                    "width": "8%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em"> '+d.metoPago+'</p>';
                    }
                },
                {
                    "orderable": false,
                    "width": "8%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em"> '+d.abrev+'</p>';
                    }
                },
                {
                    "orderable": false,
                    "width": "5%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em"> '+d.folio+'</p>';
                    }
                },
                {
                    "orderable": false,
                    "width": "15%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em"> '+d.uuid+'</p>';
                    }
                },
                {
                    "orderable": false,
                    "width": "10%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em"> $ '+formatMoney(d.cantidad)+'</p>';
                    }
                },
                { 
                    "orderable": false,
                    "width": "10%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em"> '+formato_fechaymd(d.fechaCreacion)+'</p>'
                    }
                },
                {
                    "orderable": false,
                    "data": function( d ){

                        res = '<div class="btn-group-vertical"><button type="button" class="btn btn-primary btn-xs consultar_modal notification" value="'+d.idsolicitud+'" data-value="SOL" title="Ver Solicitud"><i class="far fa-eye"></i></button>';
                        if( relacionar_factura )
                            res += '<button type="button" class="btn btn-warning btn-xs fac_relacionadas" title="Relacionar facturas con diferentes registros."><i class="fas fa-plus"></i></button>';
                        return res+'</div>';
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
                    "targets": [ 11 ],
                    "visible": false,
                    "searchable": false
                },
            ],
            "ajax":{
                "url" : url + "Complementos_cxp/tabla_pagos_complemento",
                "type": "POST",
                "data" : function( d ){
                    d.opcion = $("#tipo_gasto").val()
                }
            }
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

    $( "#tipo_gasto" ).change( function(){
        table_complemento.ajax.reload();
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
                
		var iStartDateCol = 9;
		var iEndDateCol = 9;

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