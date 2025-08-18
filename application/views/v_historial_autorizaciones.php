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
                        <h3>HISTORIAL AUTORIZACIONES </h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <table class="table table-responsive table-bordered table-striped table-hover" id="tabla_autorizaciones_excel">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th style="font-size: .8em">RESPONSABLE</th>
                                            <th style="font-size: .8em">TOTAL</th>
                                            <th style="font-size: .8em">FECHA AUTORIZACIÓN</th>
                                            <th style="font-size: .8em">ULTIMO PAGO</th>
                                            <th style="font-size: .8em"></th>
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

<script>

    var tota2=0;

    $("#tabla_autorizaciones_excel").ready( function () {

        $('#tabla_autorizaciones_excel thead tr:eq(0) th').each( function (i) {
            if( i != 4 ){
                var title = $(this).text();
                $(this).html( '<input type="text" style="width:100%;" placeholder="'+title+'" />' );
                $('input', this).on('keyup change', function() {

                    if (tabla_historial_autorizaciones.column(i).search() !== this.value ) {
                        tabla_historial_autorizaciones
                        .column(i)
                        .search( this.value )
                        .draw();
                    }
                } );
            }
        });

        tabla_historial_autorizaciones = $('#tabla_autorizaciones_excel').DataTable({
            dom: 'rtip',
            "language" : lenguaje,
            "processing": false,
            "pageLength": 10,
            "bAutoWidth": false,
            "bLengthChange": false,
            "bInfo": false,
            "scrollX": true,
            "ordering": false,
            responsive: true,
            "columns": [
                {
                    "width": "15%",
                    "orderable": false,
                    "data": function( d ){
                        return '<p style="font-size: .9em">'+d.nombres+'</p>';
                    }
                },
               
                {
                    "width": "12%",
                    "orderable": false,
                    "data": function( d ){
                        return '<p style="font-size: .9em">$ '+formatMoney(d.cnt)+'</p>';
                    }
                },
                
                {
                    "width": "15%",
                    "data": function( d ){
                        return formato_fechaymd(d.fecreg);

                    }
                },
                {
                    "width": "15%",
                    "data": function( d ){
                        return d.upago;

                    }
                },
                {
                    "width": "15%",
                    "orderable": false,
                    "data": function( d ){
                        opciones = '<div class="btn-group-vertical" role="group">';

                        nueva3 = String(d.fecreg).substring(0, 10);

                        opciones += '<button type="button" class="btn btn-success btn-sm enviar_datos_excel" value = "'+d.idrealiza+'" data-value = "'+nueva3+'" title="Descargar Reporte"><i class="fas fa-file-excel"></i></button>';
                        return opciones + '</div>';
                    }
                },
            ],
            "ajax": url + "Historial/tabla_autorizaciones"
        });

        $('#tabla_autorizaciones_excel tbody').on('click', 'td.details-control', function () {

            var tr = $(this).closest('tr');
            var row = tabla_historial_autorizaciones.row( tr );
            
            if ( row.child.isShown() ) {
                
                row.child.hide();
                tr.removeClass('shown');

                $(this).parent().find('.animacion').removeClass("fa-caret-down").addClass("fa-caret-right");
            }
            else {

                var solicitudes = '<table class="table">';
                $.each( row.data().solicitudes, function( i, v){ 
                //i es el indice y v son los valores de cada fila
                    solicitudes += '<tr>';
                    solicitudes += '<td>'+(i+1)+'.- <b>'+'PROYECTO: '+'</b> '+v.proyecto+'</td>';
                    solicitudes += '<td>'+'<b>'+'DEPARTAMENTO '+'</b> '+v.nomdepto+'</td>';
                    solicitudes += '<td>'+'<b>'+'CANTIDAD: '+'</b> $ '+formatMoney(v.cantidad)+' '+v.moneda+'</td>';
                    solicitudes += '<td>'+'<b>'+'PROVEEDOR: '+'</b> '+v.nombre+'</td>';
                    solicitudes += '<td>'+'<b>'+'EMPRESA: '+'</b> '+v.abrev+'</td>';
                    solicitudes += '</tr>';
                    solicitudes += '<tr>';
                    solicitudes += '<td style="background:#DFDEDE;" colspan="5">'+'<b>'+'JUSTIFICACIÓN: '+'</b> '+v.justificacion+'</td>';
                    solicitudes += '</tr>';
                });          

                solicitudes += '</table>';
 
                row.child( solicitudes ).show();
                tr.addClass('shown');
                $(this).parent().find('.animacion').removeClass("fa-caret-right").addClass("fa-caret-down");
            }
        });
        
        $('.dt-buttons').attr('style','float: right !important; margin-right: 15px;');
    });
    
$(window).resize(function(){
    tabla_historial_autorizaciones.columns.adjust();
    });



    $( document ).on("click", ".enviar_datos_excel", function(){
        index_date = $(this).attr( "data-value" );
        index  = $(this).attr( "value" );
         window.open(url+"Historial/descargarExcel/"+index+"/"+index_date, "_blank");
    });


 
 
</script>
<?php
require("footer.php");
?>