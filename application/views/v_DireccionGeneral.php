<?php
    require("head.php");
    require("menu_navegador.php");
?>  
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-header">
                    <h3>DIRECCIÓN GENERAL COMPRA A PROVEEDORES </h3>
                </div>
                <div class="box-body">
                    <div class="tab-content">
                        <div class="active tab-pane" id="facturas_aturizar">
                            <table class="table table-responsive table-bordered table-striped table-hover" id="solPorAutorizar">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th style="font-size: .8em">#</th>
                                        <th style="font-size: .8em">PROVEEDOR</th>
                                        <th style="font-size: .8em">EMPRESA</th>                                       
                                        <th style="font-size: .8em">FECHA FACT</th>
                                        <th style="font-size: .8em">CANTIDAD</th>
                                        <th style="font-size: .8em">ÁREA</th>
                                        <th style="font-size: .8em">ORDEN DE COMPRA</th>
                                        <th style="font-size: .8em">REQUISICIÓN</th>
                                        <th style="font-size: .8em">DIRECTOR</th>
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
<div id="modalAutoriza" class="modal fade modal modal-alertas modal-success" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header ">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">AUTORIZADA</h4>
            </div>
            <div class="modal-body text-center">
                <p>Se ha autorizado la solicitud</p>
            </div>
        </div>
    </div>
</div>

<div id="modalDeclina" class="modal fade modal modal-alertas" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formularioSOL">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">DECLINAR SOLICITUD</h4>
            </div>
            <div class="modal-body">
                <p id="datos_declinar">#</p>
                <textarea id="motivoDeclinada" name="motivoDeclinada" class="form-control" rows="4" cols="10" placeholder="Razón de la declinación" required></textarea> 
            </div>
            <div class="modal-footer">
                <div class="btn-group" role="group">
                    <button type="submit" class="btn btn-success btn-modalAcepto">ACEPTAR</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">CANCELAR</button>
                </div>
            </div>
            </form>
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
                <div class="btn-group-vertical" role="group" aria-label="Basic example">
                    <button type="submit" class="btn btn-success btn-modalDECLINOPago">ACEPTAR</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">CANCELAR</button>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>

<div id="modalDiferidoPendientes" class="modal fade modal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">CANTIDAD A AUTORIZAR</h4>
            </div>
            <div class="modal-body">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fas fa-dollar-sign"></i></span>        
                    <input type="text" class="form-control pago" placeholder="¿Cuanto desea pagar a esta solicitud?" onKeyPress="return acceptNum(event)">
                    <span class="input-group-addon" id="tipo_moneda"></span> 
                </div>
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="btn-group-vertical" role="group" aria-label="Basic example">
                            <button type="button" id="aceptar_diferirPago" class="btn btn-success btn-modalAceptoP">ACEPTAR</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">CANCELAR</button>
                            <!--<button type="button"  class="btn btn-danger btn-aceptoTodo" data-dismiss="modal">Pagar Todo</button>-->
                        </div> 
                    </div> 
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">

    /*window.onbeforeunload = function() {
        return "Estas recargando la pagina.";
    }*/

    var idSol;
    var tota=0;
    var totaPen=0;
    var tota2=0;

    var tabla_por_autorizar;
    var tabla_getion_pagos;
    var tabla_getion_pagosP;
    var tabla_pdf;
    var tr;    
    var idusuario = <?= $this->session->userdata('inicio_sesion')["id"] ?> === 2636; /** FECHA: 17-DICIEMBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */

    $( document ).ready( function(){        
        if( !$.cookie('tautorizaciones-data') ){
            $("#recuperar_guardado").remove();
        }
    });


    $(".pago").on({
        "focus": function (event) {
            $(event.target).select();
        },
        "keyup": function (event) {
            $(event.target).val(function (index, value) {
                return value.replace(/\D/g, "")
                    .replace(/([0-9])([0-9]{2})$/, '$1.$2')
                    .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ",");
            });
        }
    });

    $("#recuperar_guardado").click( function(){

        var guardado = JSON.parse($.cookie("tautorizaciones-data"));
        
        $.each( JSON.parse( guardado ),  function( i, v ){
            totaPen += v.pa;
        });

        $("#totpagarPen").html( formatMoney( totaPen ) )

        tabla_getion_pagosP.clear();
        tabla_getion_pagosP.rows.add( JSON.parse( guardado ) );
        tabla_getion_pagosP.draw();

    });

    $("#solPorAutorizar").ready( function () {
        
        $('#solPorAutorizar thead tr:eq(0) th').each( function (i) {
        if( i > 0 && i < $('#solPorAutorizar thead tr:eq(0) th').length -1  ){
            var title = $(this).text();
            $(this).html( '<input type="text" style="width: 100%;" placeholder="'+title+'" />' );
    
            $( 'input', this ).on( 'keyup change', function () {
                if ( tabla_por_autorizar.column(i).search() !== this.value ) {
                    tabla_por_autorizar
                        .column(i)
                        .search( this.value )
                        .draw();
                }
            } );
        }
     });
          
        tabla_por_autorizar = $('#solPorAutorizar').DataTable({
            "language" : lenguaje,
            dom: 'rtip',
            "processing": false,
            "pageLength": 50,
            "bAutoWidth": false,
            "bLengthChange": false,
            "bInfo": false,
            "scrollX": true,
            responsive: true,
            "columns": [{
                    "width": "1%",
                    "className": 'details-control',
                    "orderable": false,
                    "data" : null,
                    "defaultContent": '<i class="fas animacion fa-caret-right"></i>'
                },
                {
                    "width": "1%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.ID+'</p>';
                    },
                },
                {
                    "width": "12%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em" id="prov_'+d.ID+'">'+d.Proveedor + ( d.prioridad == 1 ? "<br><small class='label pull-center bg-red'>URGENTE</small>" : "") +'</p>';
                    }
                },
                {
                    "width": "4%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.EMPRESA+'</p>';
                    }
                },
                {
                    "width": "4%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.FECHAFAC+'</p>';
                    }
                },
                {
                    "width": "5%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">$ '+formatMoney( d.Cantidad )+" "+d.moneda+'</p>';
                    }
                },
                {
                    "width": "6%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.Departamento+'</p>';
                    }
                },
                {
                    "width": "6%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.oc+'</p>';
                    }
                },
                {
                    "width": "6%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.requisicion+'</p>';
                    }
                },
                {
                    "width": "6%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.nombre_capturista+'</p>';
                    }
                },
                { 
                    "width": "8%",
                    "data" : function( d ){
                        /** INICIO FECHA: 17-DICIEMBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                        var opciones = `
                            <div class="btn-group-vertical">
                                <button id="verSol" class="btn btn-instagram consultar_modal notification btn-sm" value="${d.ID}" data-value="SOL" title="Ver Solicitud">
                                    <i class="fas fa-eye"></i>${d.visto == 0 ? '<span class="badge">!</span>' : ''}
                                </button>
                                ${!idusuario ? `
                                    <button value="${d.ID}" class="btn btn-success btn-autorizar btn-sm">
                                        <i class="fas fa-check-circle"></i>
                                    </button>
                                    <button value="${d.ID}" class="btn btn-danger btn-declinar btn-sm" data-toggle="tooltip" data-placement="bottom" title="Declinar Pago">
                                        <i class="fas fa-times"></i>
                                    </button>
                                ` : ''}
                            </div>
                        `;
                        /** FIN FECHA: 17-DICIEMBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                        return opciones;
                    },
                    "orderable": false
                }
            ],
            "order": [[1, 'asc']],
            "ajax": url + "DireccionGeneral/tablaSolC"
        });

        $('#solPorAutorizar tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = tabla_por_autorizar.row( tr );
    
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
                    '<tr>'+
                        '<td style="font-size: .8em"><strong>DESCRIPCIÓN FACTURA: </strong>'+row.data().descripcion+'</td>'+
                    '</tr>'+
                '</table>';

                row.child( informacion_adicional ).show();
                tr.addClass('shown');
                $(this).parent().find('.animacion').removeClass("fa-caret-right").addClass("fa-caret-down");
            }
        });
        
        $('#solPorAutorizar').on( "click", ".btn-autorizar", function( e ){
            idSol = $(this).val();
            var tr = $(this).closest('tr');
            $.ajax({
                  url : url + "DireccionGeneral/AutorizarSolicitud",
                  type : "POST" ,
                  dataType : "json" ,
                  data :{ id_sol : idSol },
                  error : function( data ){

                 },
                 complete: function( data ){
                    //$('#modalAutoriza').modal('show');
                    tabla_por_autorizar.row( tr ).remove().draw(false);
                    //tabla_por_autorizar.ajax.reload(null, false);
                    //tabla_getion_pagosP.ajax.reload(null, false);
                 }
            });
       });
    $("#formularioSOL").submit( function(e) {
        e.preventDefault();
    }).validate({
        submitHandler: function( form ) {
            if(trd != null){
                var observacion = $("textarea#motivoDeclinada").val();
                $.ajax({
                    url : url + "DireccionGeneral/DeclinarSolicitud",
                    type : "POST" ,
                    dataType : "json" ,
                    cache:false,
                    data :{ id_sol : idSol , Obervacion : observacion},
                    error : function( data ){

                },
                complete: function( data ){
                        $("#modalDeclina").modal("toggle");
                        document.getElementById("motivoDeclinada").value = "";
                        tabla_por_autorizar.row( trd ).remove().draw(false);
                        trd = null;
                        //tabla_por_autorizar.ajax.reload(null, false);
                    }
                });
        
            }
        }
    });
    var trd = null;
     $('#solPorAutorizar').on( "click", ".btn-declinar", function( e ){    
        trd = $(this).closest('tr');    
        idSol = $(this).val();
        $("#btn-modalAcepto").val( $(this).val());
        $("#motivoDeclinada").val("");
        $('#modalDeclina').modal();
        $('#modalDeclina #datos_declinar').html("#"+idSol+" del proveedor <b>"+$("#prov_"+idSol).text()+"</b>");
    });
 
 });
 
  
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
            if( i != 0){
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
            "pageLength": 10,
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
                    "width": "8%",
                    "orderable":false,
                    "data" : function( d ){
                        return '<p style="font-size: 1em">'+d.Proveedor+'</p>';
                    }
                },            
                {
                    "width": "8%",
                    "orderable":false,
                    "data" : function( d ){
                        return '<p style="font-size: 1em">'+d.FECHAFACP+'</p>';
                    }
                },
                {
                    "width": "8%",
                    "orderable":false,
                    "data" : function( d ){
                        return '<p style="font-size: 1em">$ '+formatMoney( d.Cantidad )+' MXN</p>';
                    }
                },
                {
                    "width": "8%",
                    "orderable":false,
                    "data" : function( d ){
                        return '<p style="font-size: 1em">$ '+formatMoney( d.pa )+' MXN</p>';
                    }
                }
            ],
            "ajax": url + "DireccionGeneral/tablaSolPendientes"
            
        });
    
      $('#solPagosPen tbody').on('click', 'td.details-control', function () {
        
        if( !tr ){
            tr = $(this).parents('tr');
        }
        
        if( tr.is( $(this).parents('tr') )){
            var row = tabla_getion_pagosP.row( tr );
            if ( row.child.isShown() ) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            }else {
                if ( tabla_getion_pagosP.row( '.shown' ).length ) {
                    $('td.details-control', tabla_getion_pagosP.row( '.shown' ).node()).click();
                }

                // Open this row
                row.child( formatP(row.data().solicitudes) ).show();
                tr.addClass('shown');
            }
        }else{
            var row = tabla_getion_pagosP.row( tr );
            if( row.child.isShown() ){
                
                row.child.hide();
                tr.removeClass('shown');
            }
            
            tr = $(this).parents('tr');
            row = tabla_getion_pagosP.row( tr );
            row.child( formatP(row.data().solicitudes) ).show();
            tr.addClass('shown');
        }
    });
  

        
      
     $('#solPagosPen').on( "click", ".btn-diferirPago", function( e ){
        var index = $(this).attr("data-value");
        var row = tabla_getion_pagosP.row( tr ).data();
        $("#aceptar_diferirPago").attr("data-value", index);
        $("#tipo_moneda").html( row.solicitudes[index].moneda );

        $('#modalDiferidoPendientes').modal();
        $('#modalDiferidoPendientes .pago').val('').removeAttr('autofocus').prop('autofocus');

     });
          
     var index_cancela;
     
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
                        var row = tabla_getion_pagosP.row( tr );
                        var adeudo = parseFloat(row.data().solicitudes[index_cancela].Cantidad);
                        var abonado = parseFloat(row.data().solicitudes[index_cancela].pa);
                        row.data().solicitudes.splice(index_cancela,1);

                        totaPen -= abonado;
                        $("#totpagarPen").html(formatMoney(totaPen));

                        if( row.data().solicitudes.length > 0 ){

                            row.data().Cantidad -= adeudo;
                            row.data().pa -= abonado;

                            tabla_getion_pagosP.row( tr ).data( row.data() );
                            row.child( formatP( row.data().solicitudes) ).show();

                        }else{
                            tabla_getion_pagosP.row( tr ).remove().draw( false );
                        }
                    }
                });

        }
    });
    
       $('#solPagosPen').on( "click", ".btn-recharzarPago", function( e ){
            index_cancela = $(this).attr("data-value");
            var row = tabla_getion_pagosP.row( tr ).data();
            $(".btn-modalDECLINOPago").val(row.solicitudes[index_cancela].ID);
            $("#motivoDeclinadaPago").val("");
            $('#modalDeclinaPago').modal();
            
         });    
 
         
        
        $('#solPagosPen').on( 'click', '.selecionadoPEN', function () {
            var index = $(this).attr("data-value");
            var row = tabla_getion_pagosP.row( tr ).data();
           
            if($(this).prop("checked")){
                row.solicitudes[index].pa = ( row.solicitudes[index].Cantidad - row.solicitudes[index].Autorizado );
                row.pa += row.solicitudes[index].pa;
                totaPen += parseFloat(row.solicitudes[index].pa);           
            }else{
               //tota -= row.data().Cantidad - row.data().Autorizado;
                row.pa -= row.solicitudes[index].pa;
                 totaPen -= parseFloat(row.solicitudes[index].pa);
                row.solicitudes[index].pa = 0;              
               
            }
            
            tabla_getion_pagosP.row( tr ).data( row );
            tabla_getion_pagosP.row( tr ).child( formatP( row.solicitudes ) ).show();
            $("#totpagarPen").html(formatMoney(totaPen));

            $("#recuperar_guardado").remove();

            $.cookie("tautorizaciones-data", JSON.stringify( tabla_getion_pagosP.rows().data() ) );
        });
        
        $('#modalDiferidoPendientes .btn-modalAceptoP').click( function () {
            var index = $(this).attr("data-value");
            var row = tabla_getion_pagosP.row(tr).data();                      
            
            var cantidad = ($('#modalDiferidoPendientes .pago').val()).replace(/,/g, '');

            if( cantidad > ( row.solicitudes[index].Cantidad - row.solicitudes[index].Autorizado ) ){
                alert("Cantidad no permitida: mayor al adeudo");
                $('#modalDiferidoPendientes .pago').val('');
            }else{
                row.pa -= parseFloat(row.solicitudes[index].pa);
                totaPen -= parseFloat(row.solicitudes[index].pa);
                
                row.solicitudes[index].pa = cantidad;
                row.pa += parseFloat( cantidad );

                totaPen += parseFloat(row.solicitudes[index].pa);

                tabla_getion_pagosP.row( tr ).data( row );                     
                
                $('#modalDiferidoPendientes').modal("toggle");
                $("#totpagarPen").html(formatMoney(totaPen));
                
                tabla_getion_pagosP.row( tr ).child( formatP( row.solicitudes ) ).show();

            }
            
            $("#recuperar_guardado").remove();
            $.cookie("tautorizaciones-data", JSON.stringify( tabla_getion_pagosP.rows().data() ) );           
        });
                 
        $('#modalDiferidoPendientes .btn-aceptoTodo').click(function() {
             var row = tabla_getion_pagosP.row(tr).data();
             tr.children().eq(1).children('input[type="checkbox"]').prop("checked",true);
             row.pa = ( row.Cantidad - row.Autorizado );
             tabla_getion_pagosP.row( tr ).data( row ).draw();
             $('#modalDiferidoPendientes').modal("toggle");
          });
          
          $('#solPagosPen').on( "click", ".btn-espera", function(){               
                //idSol = $(this).val();
                $.post( url + "DireccionGeneral/enEsperaDG", {id_sol : $(this).val() } ).done( function(){
                   tabla_getion_pagosP.ajax.reload(null, false);
               });
                
                
           }); 
        $('#solPagosPen').on( "click", ".btn-regresar", function(){               
                //idSol = $(this).val();
                $.post( url + "DireccionGeneral/regresarDG", {id_sol : $(this).val() } ).done( function(){
                   tabla_getion_pagosP.ajax.reload(null, false);
               });
                
           }); 
       }); 


    function Marcar_todo(){
        totaPen=0;
        var index=0;
        var row = tabla_getion_pagosP.rows().data();

        $.each( row, function(i,v){
            $.each( v.solicitudes, function( c, d){
                    d.pa = ( d.Cantidad - d.Autorizado );
                    totaPen += parseFloat(d.pa); 
                    index+=1;
            });
            

        });
        
        $("#totpagarPen").html( formatMoney(totaPen) );
    }
var registros = 0   ;
    function autorizarSeleccionadasPendientes(){ 
        if( totaPen == 0 ){
            alert('No hay monto autorizado');
        }else if( window.confirm('Se pagará el total autorizado.\nEl total es de $ '+ formatMoney(totaPen)+' ¿Estás de acuerdo?') ){
            var apagar = [];
            
            var row = tabla_getion_pagosP.rows().data();
            $.each( row, function(i,v){
                $.each( v.solicitudes, function( c, d){
                    if( d.pa != 0 ){
                        apagar.push( [ d.ID, d.pa ] );
                    }
                });

                row.solicitudes = null;
                tabla_getion_pagosP.row( i ).remove().draw( false );

            });
            
            $.post( url + "DireccionGeneral/PagarTodo", {jsonApagar : JSON.stringify(apagar)} ).done( function( data ){

                data = JSON.parse( data );

                if( data[0] ){
                    totaPen = 0;
                    $("#totpagarPen").html(formatMoney(0));
                    tabla_getion_pagosP.ajax.reload(null, false);
                    $.removeCookie('tautorizaciones-data', { path: '/' })

                    window.open( url + "DireccionGeneral/pdf_recibo_pago/" + data[1], "_blank" ); 
                }

            }).fail( function(){
                alert("HA OCURRIDO UN ERROR, INTENTE MAS TARDE");
            });
        }
    }
    
   function formatP ( d ) {
        var solicitudesP = '<table class="table" style="font-size: .8em">';
        
        solicitudesP += '<tr>';
            solicitudesP += '<th></th>';
             solicitudesP += '<th>'+'<b>'+'PRIORIDAD '+'</b></th>';
            solicitudesP += '<th>'+'<b>'+'DEPTO'+'</b></td>';
            solicitudesP += '<th>'+'<b>'+'EMPRESA '+'</b></th>';
            solicitudesP += '<th>'+'<b>'+'FECHA '+'</b></th>';
           solicitudesP += '<th>'+'<b>'+'FOLIO '+'</b></th>';
           
            solicitudesP += '<th>'+'<b>'+'ULT. PAGO'+'</b></th>';
            solicitudesP += '<th>'+'<b>'+'SOLICITADO '+'</b></th>';
            solicitudesP += '<th>'+'<b>'+'RESTANTE '+'</b></th>';
            solicitudesP += '<th>'+'<b>'+'ABONADO '+'</b></th>';
            solicitudesP += '<th>'+'<b>'+'POR AUTORIZAR '+'</b></th>';
        solicitudesP += '</tr>';

        $.each(d, function( i, v){
            solicitudesP += '<tr>';
            solicitudesP += '<td>'+'<input type="checkbox" class="selecionadoPEN" style="width:30px;height:30px;" data-value="'+i+'" '+(v.pa ? "checked" : "") +'>'+'</td>';
            solicitudesP += '<td>'+'<p style="font-size: .8em">'+(v.prioridad ? '<span class="text-danger"><b>URGENTE</b></span>' : "")+'</p>'+'</td>'; 
            solicitudesP += '<td>'+v.nomDepto+'</td>';
            solicitudesP += '<td>'+v.abrev+'</td>';
            solicitudesP += '<td>'+v.FECHAFACP+'</td>';
            solicitudesP += '<td>'+v.folio+'</td>';
            solicitudesP += '<td>'+v.FECHAU+'</td>'; 
            solicitudesP += '<td>$ '+formatMoney(v.Cantidad)+" "+v.moneda+'</td>';
            solicitudesP += '<td>$ '+formatMoney( v.Cantidad - v.Autorizado )+" "+v.moneda+'</td>';
            solicitudesP += '<td>$ '+formatMoney( v.Autorizado )+" "+v.moneda+'</td>';
            solicitudesP += '<td>$ '+formatMoney( v.pa )+" "+v.moneda+'</td>';
            solicitudesP += '<td>'+'<div class="btn-group-vertical"><button id = "verSol" name="verSol" class="btn btn-instagram consultar_modal notification btn-sm" value="'+v.ID+'"\n\
            data-value="BAS" data-toggle="tooltip" data-placement="bottom"  title="Ver Solicitud">'+'<i class="fa fa-eye">'+( v.visto == 0 ? '</i><span class="badge">!</span>' : '')+'</i>'+'</button></div>';
            solicitudesP +=   '<button id="diferido" name="diferido" value="'+v.ID+'" data-value="'+i+'" class="btn btn-success btn-diferirPago btn-sm" data-toggle="tooltip" data-placement="bottom" title = "Diferir Pago"><i class="far fa-money-bill-alt"></i></button>'
            solicitudesP += '<button value="'+v.ID+'" data-value="'+i+'" class="btn btn-danger btn-recharzarPago btn-sm" title="Declinar Pago"><i class="fas fa-times"></i></button>';
            solicitudesP += '</tr>';
            solicitudesP += '<tr>';
            solicitudesP += '<td colspan="7"><b>'+'JUSTIFICACIÓN '+'</b>'+v.Observacion+'</td>';
             solicitudesP += '<td colspan="3">'+'<b>'+'AUTORIZACIÓN '+'</b>'+( v.nombre_dg && v.fecha_autorizacion ? v.nombre_dg : '' )+'</td>';
            solicitudesP += '<td colspan="2">'+'<b>'+'FECHA AUT. '+'</b>'+( v.nombre_dg && v.fecha_autorizacion ? v.fecha_autorizacion : '' )+'</td>';
            solicitudesP += '</tr>';
        });          
        return solicitudesP+'</table>';
    }

    var nav4 = window.Event ? true : false; 
        function acceptNum(evt){  
        // NOTE: Backspace = 8, Enter = 13, '0' = 48, '9' = 57  
        var key = nav4 ? evt.which : evt.keyCode;  
        return ( key >= 48 && key <= 57 ) || key==46 || key==44; 
    } 
</script>
<?php
require("footer.php");
?>





