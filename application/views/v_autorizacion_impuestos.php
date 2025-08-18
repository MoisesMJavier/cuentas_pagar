<?php
    require("head.php");
    require("menu_navegador.php");
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-header">
                    <h3>AUTORIZACIÓN DE PAGO DE IMPUESTOS</h3>                 
                </div>
                <div class="box-body">
                        <div class="active tab-pane" id="facturas_pendientes">
                        <h4>PENDIENTE </i> POR AUTORIZAR <b id="myText_1"></b></h4>   
                        <button class="btn btn-info" onclick="autorizarSeleccionadasPendientes()" >Autorizar seleccionadas</button> | Total: $<span id="totpagarPen"></span>
                        <hr>
                        <table class="table table-responsive table-bordered table-striped table-hover" id="solPagosPen">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th style="font-size: .8em">LINEA DE CAPTURA</th>
                                        <th style="font-size: .8em">PROVEEDOR</th>
                                        <th style="font-size: .8em">PROYECTO</th>
                                        <th style="font-size: .8em">EMPRESA</th>
                                        <th style="font-size: .8em">FECHA</th>
                                        <th style="font-size: .8em">CANTIDAD</th>
                                        <th style="font-size: .8em">CAPTURISTA</th>
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
<div id="modalDeclinaPago" class="modal fade modal modal-alertas" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formularioPP">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">DECLINAR PAGO</h4>
            </div>
            <div class="modal-body">        
                <textarea id="motivoDeclinadaPago" name="motivoDeclinadaPago" class="form-control" rows="4" cols="10" placeholder="Razón de la declinación" required></textarea> 
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-lg-12"> 
                        <div class="btn-group" role="group" aria-label="Basic example">
                            <button type="submit" class="btn btn-success btn-modalDECLINOPago">ACEPTAR</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">CANCELAR</button>
                        </div>
                    </div> 
                </div> 
            </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">

   var tabla_getion_pagosP;
   var totaPen = 0;
   var tr;
   $("#solPagosPen").ready( function () {
    
        $('#solPagosPen').on('xhr.dt', function ( e, settings, json, xhr ) {
            
            var total = 0;

            $.each( json.data,  function(i, v){                
                total += parseFloat(v.Cantidad);
            });

            var to = formatMoney(total);
            $("#myText_1").html( "$ " + to );
            
        });

         $('#solPagosPen thead tr:eq(0) th').each( function (i) {
            if( i != 0 && i!=1 && i!=9 ){
                var title = $(this).text();
                $(this).html( '<input type="text" style="width: 100%;" placeholder="'+title+'" />' );
        
                $( 'input', this ).on( 'keyup change', function () {
                    if ( tabla_getion_pagosP.column(i).search() !== this.value ) {
                        tabla_getion_pagosP
                            .column(i)
                            .search( this.value )
                            .draw();
                    }

                    var total = 0;
                    var index = tabla_getion_pagosP.rows( { selected: true, search: 'applied' } ).indexes();
                    var data = tabla_getion_pagosP.rows( index ).data();


                    $.each(data, function(i, v){
                        total += parseFloat(v.Cantidad);
                    });
                    
                    var to1 = formatMoney(total);
                    $("#myText_1").html( "$ " + to1 );
                } );
            }
        });
     
        tabla_getion_pagosP = $('#solPagosPen').DataTable({
            dom: 'rtip',
            "language" : lenguaje,
            "processing": false,
            "pageLength": 50,
            "bAutoWidth": false,
            "bLengthChange": false,
            "bInfo": false,
            "scrollX": false,            
            "columns": [
                {
                    "width": "1%",
                    "className": 'details-control',
                    "orderable": false,
                    "data" : null,
                    "defaultContent": '<i class="fas animacion fa-caret-right"></i>'
                },
                {
                    "width": "1%",
                    "orderable":false,
                    "data": function(d){
           //             if(d.ETAPA !=30){
                            return '<input type="checkbox" class="selecionado">';
           //             }else{
           //                 return '';
           //             }
                    }
                },
                {
                    "width": "8%",
                    "orderable":false,
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.folio+'</p>';
                    }
                },
                {
                    "width": "12%",
                    "orderable":false,
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.Proveedor+'</p>';
                    }
                },
               {
                    "width": "8%",
                    "orderable":false,
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.proyecto+'</p>';
                    }
                },
                {
                    "width": "8%",
                    "orderable":false,
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.abrev+'</p>';
                    }
                },
                {
                    "width": "8%",
                    "orderable":false,
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.FECHAFACP+'</p>';
                    }
                },
                {
                    "width": "8%",
                    "orderable":false,
                    "data" : function( d ){
                        return '<p style="font-size: .8em">$ '+formatMoney( d.Cantidad )+" "+d.moneda+'</p>';
                    }
                },
                {
                    "width": "8%",
                    "orderable":false,
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.nombre_capturista+'</p>';
                    }
                },
                { 
                    "width": "8%",
                    "data" : function( d ){
                        
                        var opciones = '<div class="btn-group">';
                        
                        opciones += '<button id = "verSol" name="verSol" class="btn btn-instagram consultar_modal notification btn-sm" value="'+d.ID+'" data-value="BAS" data-toggle="tooltip" data-placement="bottom"  title="Ver Solicitud"><i class="fas fa-eye" ></i>'+(d.visto == 0 ? '</i><span class="badge">!</span>' : '')+'</button>'                        
                        //opciones += '<button id="diferido" name="diferido" value="'+d.ID+'" data-value="'+(d.Cantidad - d.Autorizado)+'" class="btn btn-success btn-diferirPago btn-sm" data-toggle="tooltip" data-placement="bottom" title = "Diferir Pago"><i class="far fa-money-bill-alt"></i></button>'
                        //opciones += '<button id="espera" name="espera" value="'+d.ID+'" class="btn btn-danger btn-espera btn-sm" title="En espera Pago" ><i class="fas fa-pause"></i>'
                        opciones += '<button id="rechazado" name="rechazado" value="'+d.ID+'"  class="btn btn-danger btn-recharzarPago btn-sm" title="Declinar Pago"><i class="fas fa-times"></i></button>'
        
                        return opciones+"</div>";
                    },
                    "orderable": false
                }
            ],
            columnDefs: [ {
                orderable: false,
                className: 'select-checkbox',
                targets:   1,
                'searchable':false,
                'className': 'dt-body-center',
                'render': function (data, type, full, meta){
                    return '<input type="checkbox" name="id[]" value="' +full.ID+ '">';
                },
                select: {
                    style:    'os',
                    selector: 'td:first-child'
                },
            }],
            "ajax": url + "Impuestos/tablaDGimpuestos"
            
        });
    
      $('#solPagosPen tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = tabla_getion_pagosP.row( tr );
    
            if ( row.child.isShown() ) {
                row.child.hide();
                tr.removeClass('shown');
                $(this).parent().find('.animacion').removeClass("fa-caret-down").addClass("fa-caret-right");
            }
            else {

                var informacion_adicional = '<table class="table text-justify">'+
                    '<tr>'+
                        '<td style="font-size: .8em"><strong>JUSTIFICACIÓN: </strong>'+row.data().Observacion+'</td>'+
                    '</tr>'+
                '</table>';

                row.child( informacion_adicional ).show();
                tr.addClass('shown');
                $(this).parent().find('.animacion').removeClass("fa-caret-right").addClass("fa-caret-down");
            }
        });
      
        $('#solPagosPen').on( "click", ".btn-diferirPago", function( e ){
            tr = $(this).closest('tr');

            var row = tabla_getion_pagosP.row( tr ).data();
            $("#tipo_moneda").html( row.moneda );

            $('#modalDiferidoPendientes').modal();

        });

        $("#formularioPP").submit( function(e) {
            e.preventDefault();
        }).validate({
            submitHandler: function( form ) {
            $('#modalDeclinaPago').modal('show');
                idSol = $(".btn-modalDECLINOPago").val();
                var observacion = $("textarea#motivoDeclinadaPago").val();
                    $.ajax({
                        url : url + "DireccionGeneral/PagoDeclinado",
                        type : "POST" ,
                        dataType : "json" ,
                        data :{ id_sol : idSol , Obervacion : observacion },
                        error : function( data ){

                        },
                        complete: function( data ){
                            $("#modalDeclinaPago").modal("toggle");
                            tabla_getion_pagosP.ajax.reload();
                        }
                    });

            }
        });

        $('#solPagosPen').on( "click", ".btn-recharzarPago", function( e ){
            tr = $(this).closest('tr');
            var row = tabla_getion_pagosP.row( tr ).data();
            $(".btn-modalDECLINOPago").val( row.ID );
            $("#motivoDeclinadaPago").val("");
            $('#modalDeclinaPago').modal();
            
         });    
 
        $('#solPagosPen').on( 'click', 'input', function () {
            tr = $(this).closest('tr');
            
            var row = tabla_getion_pagosP.row( tr ).data();
    
            if( row.pa == 0 ){

                row.pa = row.Cantidad;
                tabla_getion_pagosP.row( tr ).data( row );
                totaPen += parseFloat( row.pa );
                tr.children().eq(1).children('input[type="checkbox"]').prop( "checked",true );
            }else{

                totaPen -= parseFloat( row.pa );
                row.pa = 0;
                tabla_getion_pagosP.row( tr ).data( row );
            
            }

            $("#totpagarPen").html(formatMoney(totaPen));
        });
    });

    function autorizarSeleccionadasPendientes(){        
        if(totaPen == 0){
            alert('No hay monto autorizado');
        }else if(window.confirm('Se pagará el total autorizado.\nEl total es de $ '+ formatMoney(totaPen)+' ¿Estás de acuerdo?')){
                
            var apagar = [];
            
            $( tabla_getion_pagosP.$('input[type="checkbox"]:checked')).each(function(){
                tr = $(this).closest('tr');
                var row = tabla_getion_pagosP.row( tr );
                apagar.push([ row.data().ID,  row.data().pa ]);       
            });

            $.post( url + "DireccionGeneral/PagarTodo", {jsonApagar : JSON.stringify(apagar)} ).done( function( data ){
                if( data[0] ){
                    totaPen = 0;
                    $("#totpagarPen").html(formatMoney(0));
                    tabla_getion_pagosP.ajax.reload();
                }
            }).fail( function(){
                alert("HA OCURRIDO UN ERROR, INTENTE MAS TARDE");
            });
        }
    }
</script>
<?php
require("footer.php");
?>
