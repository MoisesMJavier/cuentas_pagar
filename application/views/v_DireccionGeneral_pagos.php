<?php
    require("head.php");
    require("menu_navegador.php");
?> 
<style>
    
    a.consultar_historico{
        color: black;
    }

    a.consultar_historico:hover {
        color: black;
        font-weight: 2em;
        font-style: italic;
    }

    .swal-modal .swal-text {
        text-align: center;
    }

    input {
        font-family: Arial, Helvetica, sans-serif;
    }

</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-header">
                    <h3>DIRECCIÓN GENERAL PAGO A PROVEEDORES</h3>
                </div>
                <div class="box-body">
                    <h4>PAGOS PENDIENTES </i> TOTAL POR AUTORIZAR:<b id="myText_1"></b></h4> <!-- FECHA: 17-DICIEMBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                    <?php if ($this->session->userdata('inicio_sesion')["id"] !== '2636'): ?>
                        <div class="row"> <!-- INICIO FECHA: 17-DICIEMBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                            <div class="col-lg-6 col-md-6">
                                <h4>TOTAL POR AUTORIZAR: $<span id="totpagarPen" style="font-weight: bold;"></span></h4>
                            </div>
                            <div class="col-lg-6 col-md-6 text-right">
                                <div class="btn-group">        
                                    <button class="btn btn-warning" id="recuperar_guardado">RECUPERAR TRABAJO ANTERIOR</button>
                                    <button class="btn btn-info" onclick="autorizarSeleccionadasPendientes()">AUTORIZAR SELECCIONADAS</button>
                                </div>
                            </div>
                        </div> <!-- FIN FECHA: 17-DICIEMBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                    <?php endif; ?>
                    <hr>
                    <table class="table table-responsive table-bordered table-striped table-hover" id="solPagosPen">
                        <thead>
                            <tr>                                       
                                <th></th>
                                <th style="font-size: .8em">DÍAS CRÉDITO</th>
                                <th style="font-size: .8em">MONTO CRÉDITO</th>
                                <th style="font-size: .8em">URGENTES</th>
                                <th style="font-size: .8em">INTERCAMBIO</th>
                                <th style="font-size: .8em">PROVEEDOR</th>                                                                              
                                <th style="font-size: .8em">FECHA</th>
                                <th style="font-size: .8em">PENDIENTE POR PAGAR</th>
                                <th style="font-size: .8em">TOTAL PAGADO A PROV</th>
                                <th style="font-size: .8em">AUTORIZADO</th>                                                                              
                            </tr>
                        </thead>
                    </table>
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
                <textarea id="motivoDeclinada" name="motivoDeclinada" class="form-control" rows="4" cols="10" placeholder="Razón de la declinación" required></textarea> 
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-lg-12"> 
                        <div class="btn-group" role="group" aria-label="Basic example">
                            <button type="submit" class="btn btn-success btn-modalAcepto">ACEPTAR</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">CANCELAR</button>
                        </div>
                    </div> 
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

<div id="modalDiferidoPendientes" class="modal fade modal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">CANTIDAD A AUTORIZAR</h4>
                <h4>Restante: <b><span id="restante_deuda"></span></b></h4>
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
                        <div class="btn-group" role="group" aria-label="Basic example">
                            <button type="button" id="aceptar_diferirPago" class="btn btn-success btn-modalAceptoP">ACEPTAR</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">CANCELAR</button>
                            <!--<button type="button"  class="btn btn-danger btn-aceptoTodo" data-dismiss="modal">Pagar Todo</button>-->
                        </div> 
                    </div> 
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">

    var idSol;
    var tota=0;
    var totaPen=0;
    var tota2=0;

    var tabla_por_autorizar;
    var tabla_getion_pagos;
    var tabla_getion_pagosP;
    var tabla_pdf;
    var tr;    

    var request = indexedDB.open('tautorizaciones', 1);
    var db;
    var idusuario = <?= $this->session->userdata('inicio_sesion')["id"] ?> === 2636; /** FECHA: 17-DICIEMBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */

    window.onbeforeunload = function() {
        if(!idusuario){ /** INICIO FECHA: 17-DICIEMBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
            return "Estas recargando la pagina.";
        } /** FIN FECHA: 17-DICIEMBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
    }

    request.onupgradeneeded = function(event) {
        if(!idusuario){ /** INICIO FECHA: 17-DICIEMBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
            db = event.target.result;
            db.createObjectStore('respaldo', { keyPath: 'id', autoIncrement: true });
        } /** FIN FECHA: 17-DICIEMBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
    };

    request.onsuccess = function(event) {
        if(!idusuario){ /** INICIO FECHA: 17-DICIEMBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
            db = event.target.result;

            $(document).ready(function() {
                if (db) {
                    var transaction = db.transaction('respaldo', 'readonly');
                    var objectStore = transaction.objectStore('respaldo');
                    var request = objectStore.openCursor();

                    request.onsuccess = function(event) {
                        var cursor = event.target.result;
                        if (cursor) {
                            var guardado = JSON.parse(cursor.value.data);

                            $("#totpagarPen").html(formatMoney(totaPen));
                        } else {
                            $("#recuperar_guardado").remove();
                        }
                    };
                } else {
                    $("#recuperar_guardado").remove();
                    alert("El dispositivo usado no es compatible con la función de recuperación.");
                }
            });

            $(".pago").on({
                "focus": function(event) {
                    $(event.target).select();
                },
                "keyup": function(event) {
                    $(event.target).val(function(index, value) {
                        return value.replace(/\D/g, "")
                            .replace(/([0-9])([0-9]{2})$/, '$1.$2')
                            .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ",");
                    });
                }
            });

            $("#recuperar_guardado").click(function() {
                if (db) {
                    totaPen = 0;
                    var transaction = db.transaction('respaldo', 'readonly');
                    var objectStore = transaction.objectStore('respaldo');
                    var request = objectStore.openCursor();

                    request.onsuccess = function(event) {
                        var cursor = event.target.result;
                        if (cursor) {
                            var guardado = JSON.parse(cursor.value.data);
                            $.each(guardado, function(i, v) {
                                totaPen += v.pa;
                            });

                            $("#totpagarPen").html(formatMoney(totaPen));

                            tabla_getion_pagosP.clear();
                            tabla_getion_pagosP.rows.add(guardado);
                            tabla_getion_pagosP.draw();
                        }
                    };
                }
            });
        } /** FIN FECHA: 17-DICIEMBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
    };
  
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
                $(this).html( '<input type="text" class="form-control" style="width: 100%;" placeholder="'+title+'" title="'+title+'" />' ); /** FECHA: 07-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
        
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
            "scrollX": true,
            "order": [ 1, 'asc' ],
            "columns": [
                {
                    "width": "1%",
                    "className": 'details-control',
                    "orderable": false,
                    "data" : null,
                    "defaultContent": '<i class="fas animacion fa-caret-right"></i>'
                },
                {
                    "width": "3%",
                    "orderable":false,
                    "data" : function( d ){
                        return '<p style="font-size: 1em; text-align: center;"> '+ (d.dias_credito == null ? "" : d.dias_credito ) +'</p>';
                    }
                },
                {
                    "width": "7%",
                    "orderable":false,
                    "data" : function( d ){
                        return '<p style="font-size: 1em;">'+ ((d.monto_credito == 0 || d.monto_credito == null || d.monto_credito == "") ? "" : '$ '+formatMoney(d.monto_credito)+'MXN' ) +'</p>';
                    }
                },
                {
                    "width": "5%",
                    "orderable":true,
                    "data" : function( d ){
                        if( d.urgentes == "0" )
                            return '<p style="font-size: 1em"></p>';
                        else
                            return '<p style="font-size: 1em">'+d.urgentes+' URGENTE(S)</p>';
                    }
                },
                {
                    "width": "7%",
                    "orderable":false,
                    "data" : function( d ){
                        return '<p style="font-size: 1em; text-align:center;">'+(d.contratos != null ? d.contratos+"% INTERCAMBIO":"")+'</p>';
                    }
                },
                {
                    "width": "8%",
                    "orderable":false,
                    "data" : function( d ){
                        return '<p style="font-size: 1em"><a href="#" class="consultar_historico" title="Información adicional del proveedor." data-idprov="'+d.ids+'">'+d.Proveedor+'</a></p>';
                    }
                },              
                {
                    "width": "8%",
                    "orderable":false,
                    "data" : function( d ){
                        return '<p style="font-size: 1em">'+(d.FECHAFACP)+'</p>';
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
                        return '<p style="font-size: 1em">$ '+formatMoney( d.totalPagado )+' MXN</p>';
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
            $("#restante_deuda").html( "$ " + formatMoney ( row.solicitudes[index].Cantidad - row.solicitudes[index].Autorizado ) + " " + row.solicitudes[index].moneda  );
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

                            if( row.data().solicitudes.length > 0 ){

                                row.data().Cantidad -= adeudo;
                                row.data().pa -= abonado;

                                tabla_getion_pagosP.row( tr ).data( row.data() );
                                row.child( formatP( row.data().solicitudes) ).show();
    
                                sumatoria_general();
                                //$("#totpagarPen").html(formatMoney(totaPen));
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
 
        $('#solPagosPen').on('click', '.selecionadoPEN', function () {
            var index = $(this).attr("data-value");
            var row = tabla_getion_pagosP.row(tr).data();

            if (row.solicitudes[index].pa == 0) {
                row.solicitudes[index].pa = (row.solicitudes[index].Cantidad - row.solicitudes[index].Autorizado);
                row.pa += row.solicitudes[index].pa;
                totaPen += parseFloat(row.solicitudes[index].pa);
                //console.log( row.pa );         
            } else {
                //tota -= row.data().Cantidad - row.data().Autorizado;
                row.pa -= row.solicitudes[index].pa;
                totaPen -= parseFloat(row.solicitudes[index].pa);
                row.solicitudes[index].pa = 0;
            }

            tabla_getion_pagosP.row(tr).data(row);
            tabla_getion_pagosP.row(tr).child(formatP(row.solicitudes)).show();

            sumatoria_general();
            //$("#totpagarPen").html(formatMoney(totaPen));

            $("#recuperar_guardado").remove();

            if (db) {
                var transaction = db.transaction(['respaldo'], 'readwrite');
                var respaldoStore = transaction.objectStore('respaldo');

                respaldoStore.clear().onsuccess = function () {
                    respaldoStore.add({ id: 1, data: JSON.stringify(tabla_getion_pagosP.rows().data()) });
                };
            }
        });

        
        $('#modalDiferidoPendientes .btn-modalAceptoP').click(function () {
            var index = $(this).attr("data-value");
            var row = tabla_getion_pagosP.row(tr).data();

            var cantidad = ($('#modalDiferidoPendientes .pago').val()).replace(/,/g, '');

            if (formatMoney(cantidad) == formatMoney(row.solicitudes[index].Cantidad - row.solicitudes[index].Autorizado))
                cantidad = row.solicitudes[index].Cantidad - row.solicitudes[index].Autorizado;

            if (cantidad > (row.solicitudes[index].Cantidad - row.solicitudes[index].Autorizado)) {
                alert("Cantidad no permitida: mayor al adeudo ");
                $('#modalDiferidoPendientes .pago').val('');
            } else {
                row.pa -= parseFloat(row.solicitudes[index].pa);
                totaPen -= parseFloat(row.solicitudes[index].pa);

                row.solicitudes[index].pa = cantidad;
                row.pa += parseFloat(cantidad);

                totaPen += parseFloat(row.solicitudes[index].pa);

                tabla_getion_pagosP.row(tr).data(row);

                $('#modalDiferidoPendientes').modal("toggle");
                sumatoria_general();
                //$("#totpagarPen").html(formatMoney(totaPen));

                tabla_getion_pagosP.row(tr).child(formatP(row.solicitudes)).show();
            }

            $("#recuperar_guardado").remove();

            if (db) {
                var transaction = db.transaction(['respaldo'], 'readwrite');
                var respaldoStore = transaction.objectStore('respaldo');

                respaldoStore.clear().onsuccess = function () {
                    respaldoStore.add({ id: 1, data: JSON.stringify(tabla_getion_pagosP.rows().data()) });
                };
            }
        });

                 
        $('#modalDiferidoPendientes .btn-aceptoTodo').click(function() {
            var row = tabla_getion_pagosP.row(tr).data();
            tr.children().eq(1).children('input[type="checkbox"]').prop("checked",true);
            row.pa = ( row.Cantidad - row.Autorizado );
            tabla_getion_pagosP.row( tr ).data( row ).draw();
            $('#modalDiferidoPendientes').modal("toggle");
        });
          
        $('#solPagosPen').on( "click", ".btn-espera", function(){               
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
                    /*d.pa = parseFloat(d.Cantidad);
                    totaPen  += parseFloat(d.Cantidad);*/
                        d.pa = ( d.Cantidad - d.Autorizado );
                        totaPen += parseFloat(d.pa); 
                        index+=1;
                });
                

            });
            
            $("#totpagarPen").html( formatMoney(totaPen) );
        }

        var registros = 0;

        function autorizarSeleccionadasPendientes() {
            if (totaPen == 0) {
                alert('No hay monto autorizado');
            } else if (window.confirm('Se pagará el total autorizado.\nEl total es de $ ' + formatMoney(totaPen) + ' ¿Estás de acuerdo?')) {
                var apagar = [];

                var rows = tabla_getion_pagosP.rows().data();
                $.each(rows, function (i, v) {
                    $.each(v.solicitudes, function (c, d) {
                        if (d.pa != 0) {
                            apagar.push([d.ID, d.pa, d.vdivisa]);
                        }
                    });

                    v.solicitudes = null;
                    tabla_getion_pagosP.row(i).remove().draw(false);
                });

                $.post(url + "DireccionGeneral/PagarTodo", { jsonApagar: JSON.stringify(apagar) }).done(function (data) {
                    data = JSON.parse(data);

                    if (data[0]) {
                        if (db) {
                            var transaction = db.transaction(['respaldo'], 'readwrite');
                            var respaldoStore = transaction.objectStore('respaldo');

                            respaldoStore.clear().onsuccess = function () {
                                totaPen = 0;
                                $("#totpagarPen").html(formatMoney(0));
                                tabla_getion_pagosP.ajax.reload(null, false);
                                $("#recuperar_guardado").remove();
                            };
                        } else {
                            totaPen = 0;
                            $("#totpagarPen").html(formatMoney(0));
                            tabla_getion_pagosP.ajax.reload(null, false);
                            $("#recuperar_guardado").remove();
                        }
                    }
                }).fail(function () {
                    alert("HA OCURRIDO UN ERROR, INTENTE MÁS TARDE");
                });
            }
        }

    
        $( document ).on( "click", ".consultar_historico", function( event ){
            event.preventDefault();
            let nproveedor = tabla_getion_pagosP.row( $(this).parents('tr') ).data().Proveedor;

            $.post( url + "DireccionGeneral/consultar_proveedor", btoa( JSON.stringify( {
                idproveedor : $( this ).data("idprov")
            } ) ) ).done( function( resultado ){

                resultado = JSON.parse( atob( resultado ) );
                if( resultado ){
                    if( resultado.estatus )
                        alert( nproveedor+".\n"+resultado.mjs );
                    else
                        alert( resultado.mjs );
                }else
                    alert("Llegue hasta aqui");
            })
        });

        function formatP ( d ) {
                var solicitudesP = '<table class="table" style="font-size: .8em">';
                
                solicitudesP += '<thead><tr>';
                    solicitudesP += !idusuario ? '<th></th>' : ''; /** FECHA: 17-DICIEMBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                    solicitudesP += '<th>'+'<b>'+'# SOLICITUD '+'</b></th>';//creado por Efrain Martinez Muñoz 29/07/2024
                    solicitudesP += '<th>'+'<b>'+'PRIORIDAD '+'</b></th>';
                    solicitudesP += '<th>'+'<b>'+'DEPTO'+'</b></td>';
                    solicitudesP += '<th>'+'<b>'+'EMPRESA '+'</b></th>';
                    solicitudesP += '<th>'+'<b>'+'FECHA '+'</b></th>';
                    solicitudesP += '<th>'+'<b>'+'FOLIO '+'</b></th>';
                
                    solicitudesP += '<th>'+'<b>'+'ULT. PAGO'+'</b></th>';
                    solicitudesP += '<th>'+'<b>'+'SOLICITADO '+'</b></th>';
                    solicitudesP += '<th>'+'<b>'+'ABONADO '+'</b></th>';
                    solicitudesP += '<th>'+'<b>'+'RESTANTE '+'</b></th>';
                    solicitudesP += '<th>'+'<b>'+'POR AUTORIZAR '+'</b></th>';
                    solicitudesP += '<th></th>';
                solicitudesP += '</tr></thead>';

                $.each(d, function( i, v){
                    solicitudesP += '<tr>';

                    /** INICIO FECHA: 17-DICIEMBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                    solicitudesP += !idusuario ? `
                        <td>
                            <input type="checkbox" class="selecionadoPEN" style="cursor:pointer;width:20px;height:20px;" data-value="${i}" ${v.pa != 0 || v.pa != '0' ? 'checked' : ''}>
                        </td> `
                    : ''; /** FIN FECHA: 17-DICIEMBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */

                    solicitudesP += '<td>'+v.ID+'</td>'; //creado por Efrain Martinez Muñoz 29/07/2024
                    solicitudesP += '<td>'+(v.prioridad ? '<p style="font-size: .8em"><span class="text-danger"><b>URGENTE</b></span></p>' : "")+( v.programado != "NULL" && v.programado != null ? '<p style="font-size: .85em"><span class=""><b>'+( v.fecha_fin ? 'PAGO RECURRENTE HASTA EL '+formato_fechaymd(v.fecha_fin) : 'PAGO RECURRENTE, NO SE HA DEFINIDO UNA FECHA TERMINO' )+'</b></span></p><p style="font-size: .85em"><span class=""><b>EL PAGO SE HARA ' + ( v.programado != "7" ? { "1" : 'MENSUALMENTE', "2" : 'BIMESTRAL', "3" : 'TRIMESTRAL', "4" : 'CUATRIMESTRAL', "5" : '', "6" : 'SEMESTRAL', "8" : 'QUINCENAL' }[v.programado] : 'SEMANALMENTE' ) + '</b></span> PAGOS REALIZADOS '+v.prealizados+' / '+v.ppago+'</p>' : '' )+'</td>'; 
                    solicitudesP += '<td>'+v.nomDepto+(v.porcentaje != null ?"<br>INTERCAMBIO "+v.porcentaje+"%" :" ")+'</td>';
                    solicitudesP += '<td>'+v.abrev+'</td>';
                    solicitudesP += '<td>'+formato_fechaymd(v.FECHAFACP)+'</td>';
                    solicitudesP += '<td>'+v.folio+'</td>';
                    solicitudesP += '<td>'+(v.FECHAU ? formato_fechaymd(v.FECHAU) : '')+'</td>'; 
                    solicitudesP += '<td>$ '+formatMoney(v.Cantidad)+" "+v.moneda+'</td>';
                    solicitudesP += '<td>$ '+formatMoney( v.Autorizado )+" "+v.moneda+'</td>';
                    solicitudesP += '<td>$ '+formatMoney( v.Cantidad - v.Autorizado )+" "+v.moneda+'</td>';
                    solicitudesP += '<td>$ '+formatMoney( v.pa )+" "+v.moneda+'</td>';

                    /** INICIO FECHA: 17-DICIEMBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                    solicitudesP += `
                        <td>
                            <div class="btn-group-vertical text-right">
                                <button id="verSol" name="verSol" class="btn btn-primary consultar_modal notification btn-sm" value="${v.ID}"
                                    data-value="BAS" data-toggle="tooltip" data-placement="bottom" title="Ver Solicitud">
                                    <i class="fa fa-eye">${v.visto == 0 ? '<span class="badge">!</span>' : ''}</i>
                                </button>
                                ${!idusuario ? `
                                    ${v.programado === "NULL" || v.programado === null ? `
                                        <button id="diferido" name="diferido" value="${v.ID}" data-value="${i}" class="btn btn-success btn-diferirPago btn-sm" data-toggle="tooltip" data-placement="bottom" title="Diferir Pago">
                                            <i class="far fa-money-bill-alt"></i>
                                        </button>
                                    ` : ''}

                                    <button value="${v.ID}" data-value="${i}" class="btn btn-danger btn-recharzarPago btn-sm" title="Declinar Pago">
                                        <i class="fas fa-times"></i>
                                    </button>
                                ` : ''}
                            </div>
                        </td>
                    `;
                    solicitudesP += '</tr>';
                    /** FIN FECHA: 17-DICIEMBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */

                    if( v.divisa != 'MXN' )
                        if( v.CantidadOriginal != 0 ){
                            solicitudesP += '<tr><td colspan="11"><p class="text-right">Ajuste de divisa de 1 <b>'+v.divisa+'</b> por $ '+v.vdivisa+' MXN.</p></td></tr>';
                        }else{
                            solicitudesP += '<tr><td colspan="11"><p class="text-right">Actualmente no se encuentra disponible el ajuste de divisa <b>'+v.divisa+'</b>';
                        }

                    if( v.comentario_especial ){
                        solicitudesP += '<tr>';
                        solicitudesP += '<td colspan="12"><b>'+'COMENTARIOS ADICIONALES '+'</b></td>';
                        solicitudesP += '</tr>';
                        solicitudesP += '<tr>';
                        solicitudesP += '<td colspan="12">'+v.comentario_especial+'</td>';
                        solicitudesP += '</tr>';
                    }

                    solicitudesP += '<tr><td colspan="7"><b>'+'JUSTIFICACIÓN '+'</b>'+v.Observacion+'</td>';
                    solicitudesP += '<td colspan="3">'+'<b>'+'AUTORIZACIÓN '+'</b>'+( v.nombre_dg && v.fecha_autorizacion ? v.nombre_dg : '' )+'</td>';
                    solicitudesP += '<td colspan="2">'+'<b>'+'FECHA AUT. '+'</b>'+( v.nombre_dg && v.fecha_autorizacion ? v.fecha_autorizacion : '' )+'</td>';
                    solicitudesP += '</tr>';
                });          
                return solicitudesP+'</table>';
            }

        var nav4 = window.Event ? true : false; 
        function acceptNum(evt){  
            var key = nav4 ? evt.which : evt.keyCode;  
            return ( key >= 48 && key <= 57 ) || key==46 || key==44; 
        }
        
        function sumatoria_general(){
            var data = tabla_getion_pagosP.rows().data();

            let total = 0

            data.map( ( v, i ) => {
                if( v.pa > 0 ){
                    total += v.pa
                }
            });
            
            $("#totpagarPen").html(formatMoney(total));
            console.log( total );
        }

        $(window).resize(function(){ /** INICIO FECHA: 07-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
            tabla_getion_pagosP.columns.adjust().draw(false);
        });

        $('.sidebar-toggle').click(function() {
            setTimeout(function() {
                // 1. Ajustar columnas
                tabla_getion_pagosP.columns.adjust();
                // 2. Forzar actualización de encabezados
                var headerCellsTablaGestionPagosPendientes = $('#solPagosPen thead th');
                headerCellsTablaGestionPagosPendientes.each(function() {
                    var newWidth = $(this).width(); // Obtener ancho actualizado
                    $(this).css('width', newWidth + 'px'); // Aplicarlo
                });
                // 3. Redibujar (sin resetear paginación)
                tabla_getion_pagosP.draw(false);
            }, 300); // Esperar a que termine la animación del sidebar
        }); /** FIN FECHA: 07-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/

</script>
<?php
require("footer.php");
?>