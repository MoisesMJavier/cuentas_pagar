<?php
    require("head.php");
    require("menu_navegador.php");
?>  
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-header">
                    <h3>PAGADO A PROVEEDORE(S)/PENDIENTE POR PAGAR</h3>
                </div>
                <div class="box-body">
                    <div class="col-lg-3 form-group">
                        <input class="form-control" id="inicio_consulta" type="date">
                    </div>
                    <div class="col-lg-3 form-group">
                        <input class="form-control" id="fin_consulta" type="date">
                    </div>
                    <table class="table table-responsive table-bordered table-striped table-hover" id="solPagosPen">
                        <thead>
                            <tr>   
                                <th style="font-size: .8em">PROVEEDOR</th>                                      
                                <th style="font-size: .8em">TOTAL PAGADO</th>
                                <th style="font-size: .8em">DEUDA PENDIENTE</th>                                                                            
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

    window.onbeforeunload = function() {
        return "Estas recargando la pagina.";
    }

    var idSol;
    var tota=0;
    var totaPen=0;
    var tota2=0;

    var tabla_por_autorizar;
    var tabla_getion_pagos;
    var tabla_getion_pagosP;
    var tabla_pdf;
    var tr;    

    var db = !window.openDatabase ? false : openDatabase('tautorizaciones', '1.0', 'Respado de todo lo autorizado', 50 * 1024 * 1024);
    console.log(db);
    $( document ).ready( function(){ 
        
        if( db ){
            db.transaction(function (tx) {
                tx.executeSql('CREATE TABLE IF NOT EXISTS respaldo ( data TEXT )');
                tx.executeSql('SELECT * FROM respaldo', [], function ( tx, results ) {
                    if( results.rows.length == 0 ){
                        $("#recuperar_guardado").remove();
                    }
                });
            });
        }else{
            $("#recuperar_guardado").remove();
            alert("El dispositivo usado no es compatible con la funcion de recuperación.")
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

        if( db )
            db.transaction(function (tx) {
                tx.executeSql('SELECT * FROM respaldo', [], function ( tx, results ) {
                    
                    var guardado = JSON.parse( results.rows.item( 0 ).data );
                    $.each( guardado,  function( i, v ){
                        totaPen += v.pa;
                    });

                    $("#totpagarPen").html( formatMoney( totaPen ) )

                    tabla_getion_pagosP.clear();
                    tabla_getion_pagosP.rows.add( guardado );
                    tabla_getion_pagosP.draw();

                });
            });
    }); 
  
    $("#solPagosPen").ready( function () {
    
        $('#solPagosPen').on('xhr.dt', function ( e, settings, json, xhr ) {
            if( json.permiso ){
                $("input#inicio_consulta").val( json.operaciones[0].minoperacion ).attr("min", json.operaciones[0].minoperacion ).attr("max", json.operaciones[0].maxoperacion );
                $("input#fin_consulta").val( json.operaciones[0].maxoperacion ).attr("min", json.operaciones[0].minoperacion ).attr("max", json.operaciones[0].maxoperacion );
            }else{
                tabla_getion_pagosP.button( '0' ).remove();
                $(".form-group").remove();
            }
        });

        $('#solPagosPen thead tr:eq(0) th').each( function (i) {
            var title = $(this).text();
            $(this).html( '<input type="text" class="form-control" style="width: 100%;" placeholder="'+title+'" />' );

            $( 'input', this ).on( 'keyup change', function () {
                if ( tabla_getion_pagosP.column(i).search() !== this.value ) {
                    tabla_getion_pagosP
                        .column(i)
                        .search( this.value )
                        .draw();
                }
            } );
        });
     
        tabla_getion_pagosP = $('#solPagosPen').DataTable({
            dom: 'Brtip',
            "buttons": [
                {
                    text: '<i class="fas fa-search"></i>',
                    action: function(){
                        tabla_getion_pagosP.ajax.reload();
                    },
                    attr: {
                        class: 'btn btn-primary'
                    }
                },
                {
                extend: 'excelHtml5',             
                text: '<i class="fas fa-file-excel"></i>',
                messageTop: "Lista de solicitudes por autorizar",
                    attr: {
                        class: 'btn btn-success'       
                    },
                exportOptions: {
                    format: {
                        header: function (data, columnIdx) { 
                            data = data.replace( '<input type="text" class="form-control" style="width: 100%;" placeholder=', '' );
                            data = data.replace( '">', '' )
                            return data;
                        }
                    }
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
                    "width": "5%",
                    "orderable":false,
                    "data" : function( d ){
                        if( d.urgentes == "0" )
                            return '<p style="font-size: 1em"></p>';
                        else
                            return '<p style="font-size: 1em">'+d.pnombre+'</p>';
                    }
                },
                {
                    "width": "8%",
                    "orderable":false,
                    "data" : function( d ){
                        return '<p style="font-size: 1em">$ '+formatMoney(d.totalPagado)+'</p>';
                    }
                },              
                {
                    "width": "8%",
                    "orderable":false,
                    "data" : function( d ){
                        return '<p style="font-size: 1em">$ '+formatMoney(d.deuda)+'</p>';
                    }
                }
            ],
            "ajax": {
                "url" : url + "Indicadores/total_adeudo_proveedores",
                "type": "POST",
                "data" : function( d ){
                    d.minoperacion = $("#inicio_consulta").val();
                    d.maxoperacion = $("#fin_consulta").val();
                }
            }
            
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
            
            if( row.solicitudes[index].pa == 0 ){
                row.solicitudes[index].pa = ( row.solicitudes[index].Cantidad - row.solicitudes[index].Autorizado );
                row.pa += row.solicitudes[index].pa;
                totaPen += parseFloat(row.solicitudes[index].pa);  
                //console.log( row.pa );         
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

            if( db )
                db.transaction(function (tx) {
                    tx.executeSql('DELETE FROM respaldo');
                    tx.executeSql('INSERT INTO respaldo ( data ) VALUES ( ? )', [ JSON.stringify( tabla_getion_pagosP.rows().data() ) ]);
                });
        });
        
        $('#modalDiferidoPendientes .btn-modalAceptoP').click( function () {
            var index = $(this).attr("data-value");
            var row = tabla_getion_pagosP.row(tr).data();                      
            
            var cantidad = ($('#modalDiferidoPendientes .pago').val()).replace(/,/g, '');

            if( formatMoney(cantidad) == formatMoney(row.solicitudes[index].Cantidad - row.solicitudes[index].Autorizado) )
                cantidad = row.solicitudes[index].Cantidad - row.solicitudes[index].Autorizado;

            if( cantidad > ( row.solicitudes[index].Cantidad - row.solicitudes[index].Autorizado ) ){
                alert("Cantidad no permitida: mayor al adeudo ");
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

            if( db )
                db.transaction(function (tx) {
                    tx.executeSql('DELETE FROM respaldo');
                    tx.executeSql('INSERT INTO respaldo ( data ) VALUES ( ? )', [ JSON.stringify( tabla_getion_pagosP.rows().data() ) ]);
                });        
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

        function autorizarSeleccionadasPendientes(){ 
            if( totaPen == 0 ){
                alert('No hay monto autorizado');
            }else if( window.confirm('Se pagará el total autorizado.\nEl total es de $ '+ formatMoney(totaPen)+' ¿Estás de acuerdo?') ){
                var apagar = [];
                
                var row = tabla_getion_pagosP.rows().data();
                $.each( row, function(i,v){
                    $.each( v.solicitudes, function( c, d){
                        if( d.pa != 0 ){
                            apagar.push( [ d.ID, d.pa,d.vdivisa ] );
                        }
                    });

                    row.solicitudes = null;
                    tabla_getion_pagosP.row( i ).remove().draw( false );

                });
                
                $.post( url + "DireccionGeneral/PagarTodo", {jsonApagar : JSON.stringify(apagar)} ).done( function( data ){

                    data = JSON.parse( data );

                    if( data[0] ){
                        if( db )
                            db.transaction(function (tx) {
                                tx.executeSql('DELETE FROM respaldo');

                                totaPen = 0;
                                $("#totpagarPen").html(formatMoney(0));
                                tabla_getion_pagosP.ajax.reload(null, false);
                                $("#recuperar_guardado").remove();
                            });
                        else{
                            totaPen = 0;
                            $("#totpagarPen").html(formatMoney(0));
                            tabla_getion_pagosP.ajax.reload(null, false);
                            $("#recuperar_guardado").remove();
                        }
                    }

                }).fail( function(){
                    alert("HA OCURRIDO UN ERROR, INTENTE MAS TARDE");
                });
            }
        }
    
        function formatP ( d ) {
                var solicitudesP = '<table class="table" style="font-size: .8em">';
                
                solicitudesP += '<thead><tr>';
                    solicitudesP += '<th></th>';
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
                    solicitudesP += '<td>'+'<input type="checkbox" class="selecionadoPEN" style="width:30px;height:30px;" data-value="'+i+'" '+(v.pa != 0 || v.pa != "0" ? "checked" : "") +'>'+'</td>';
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
                    solicitudesP += '<td>'+'<div class="btn-group-vertical"><button id = "verSol" name="verSol" class="btn btn-primary consultar_modal notification btn-sm" value="'+v.ID+'"\n\
                    data-value="BAS" data-toggle="tooltip" data-placement="bottom"  title="Ver Solicitud">'+'<i class="fa fa-eye">'+( v.visto == 0 ? '</i><span class="badge">!</span>' : '')+'</i>'+'</button>';

                    if( v.programado == "NULL" || v.programado == null ){
                        solicitudesP += '<button id="diferido" name="diferido" value="'+v.ID+'" data-value="'+i+'" class="btn btn-success btn-diferirPago btn-sm" data-toggle="tooltip" data-placement="bottom" title = "Diferir Pago"><i class="far fa-money-bill-alt"></i></button>'
                    }
                    
                    solicitudesP += '<button value="'+v.ID+'" data-value="'+i+'" class="btn btn-danger btn-recharzarPago btn-sm" title="Declinar Pago"><i class="fas fa-times"></i></button></div></td></tr>';

                    if( v.CantidadOriginal != 0 ){
                        solicitudesP += '<tr><td colspan="11"><p class="text-right">Ajuste de divisa de 1 <b>'+v.divisa+'</b> por $ '+v.vdivisa+' MXN.</p></td></tr>';
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
</script>
<?php
require("footer.php");
?>