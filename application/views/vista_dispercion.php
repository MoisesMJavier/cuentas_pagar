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
                        <h3>DISPERSIÓN DE PAGOS</h3>
                    </div>
                    <div class="box-body">
                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                                <li class="active"><a id="autorizaciones-tab" data-toggle="tab"
                                    href="#sol_activas" role="tab" aria-controls="#home"
                                    aria-selected="true">PAGOS POR AUTORIZAR</a></li>

                                <li><a id="autorizaciones-tab" data-toggle="tab"
                                    href="#historial_dp" role="tab" aria-controls="#historial_dp"
                                    aria-selected="true">PAGOS AUTORIZADOS </a></li>

                                <li><a id="autorizaciones-tab" data-toggle="tab"
                                    href="#historial_echq" role="tab" aria-controls="#historial_echq"
                                    aria-selected="true">CHEQUES AUTORIZADOS </a></li>
                            </ul>
                        </div>
                        <div class="tab-content">
                            <div class="active tab-pane" id="sol_activas">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <h4>
                                            PAGOS ACTIVOS POR AUTORIZAR <i
                                                class="far fa-question-circle faq" tabindex="0"
                                                data-container="body" data-trigger="focus"
                                                data-toggle="popover" title="Autorizaciones"
                                                data-content="Estas son todas las solicitudes de pago a proveedores que se encuentran el proceso de pago, verifica el estatus de la solicitud para saber en que parte del proceso se encuentra."
                                                data-placement="right"></i><label>&nbsp;Total: $<input
                                                style="border-bottom: none; border-top: none; border-right: none; border-left: none; background: white;"
                                                disabled="disabled" readonly="readonly" type="text"
                                                name="myText_1" id="myText_1"></label>
                                        </h4>
                                        <div class="row"></div>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <table class="table table-striped" id="tabla_autorizaciones">
                                                    <thead class="thead-dark">
                                                        <tr>
                                                            <th></th>
                                                            <th style="font-size: .9em;">#</th>
                                                            <th style="font-size: .9em;">PROVEEDOR</th>
                                                            <th style="font-size: .9em;">CANTIDAD</th>
                                                            <th style="font-size: .9em;">AUTORIZADO</th>
                                                            <th style="font-size: .9em;">FORMA PAGO</th>
                                                            <th style="font-size: .9em;">TIPO PAGO</th>
                                                            <th style="font-size: .9em;">EMPRESA</th>
                                                            <th style="font-size: .9em;">AUTORIZÓ PAGO</th>
                                                            <th style="font-size: .9em;">ESTATUS</th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class=" tab-pane" id="historial_dp">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <h4>
                                            &nbsp;HISTORIAL DE SOLICITUDES AUTORIZADAS <i
                                                class="far fa-question-circle faq" tabindex="0"
                                                data-container="body" data-trigger="focus"
                                                data-toggle="popover" title="Autorizaciones"
                                                data-content="Estas son todas las solicitudes de pago a proveedores que se encuentran el proceso de pago, han pasado por la primer validación de Dirección General."
                                                data-placement="right"></i><label>&nbsp;Total: $<input
                                                style="border-bottom: none; border-top: none; border-right: none; border-left: none; background: white;"
                                                disabled="disabled" readonly="readonly" type="text"
                                                name="myText_2" id="myText_2"></label>
                                        </h4>
                                        <table class="table table-striped"
                                            id="tabla_historial_autorizaciones">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th></th>
                                                    <th style="font-size: .9em;">PROVEEDOR</th>
                                                    <th style="font-size: .9em;">CANTIDAD</th>
                                                    <th style="font-size: .9em;">AUTORIZADO</th>
                                                    <th style="font-size: .9em;">INTÉRES</th>
                                                    <th style="font-size: .9em;">TOTAL</th>
                                                    <th style="font-size: .9em;"># PAGO</th>
                                                    <th style="font-size: .9em;">MÉTODO PAGO</th>
                                                    <th style="font-size: .9em;">TIPO</th>
                                                    <th style="font-size: .9em;">AUTORIZÓ PAGO</th>
                                                    <th style="font-size: .9em;">EMPRESA</th>
                                                    <th style="font-size: .9em;">F DISPERSIÓN</th>
                                                    <th style="font-size: .9em;"></th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class=" tab-pane" id="historial_echq">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <h4>
                                            &nbsp;HISTORIAL DE CHEQUES AUTORIZADOS <i
                                                class="far fa-question-circle faq" tabindex="0"
                                                data-container="body" data-trigger="focus"
                                                data-toggle="popover" title="Autorizaciones"
                                                data-content="Estas son todas las solicitudes de pago a proveedores que se encuentran el proceso de pago, han pasado por la primer validación de Dirección General."
                                                data-placement="right"></i><label>&nbsp;Total: $<input
                                                style="border-bottom: none; border-top: none; border-right: none; border-left: none; background: white;"
                                                disabled="disabled" readonly="readonly" type="text"
                                                name="myText_3" id="myText_3"></label>
                                        </h4>
                                        <table class="table table-striped"
                                            id="tabla_historial_echq"
                                            name="tabla_historial_echq">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th></th>
                                                    <th style="font-size: .9em;">PROVEEDOR</th>
                                                    <th style="font-size: .9em;">CANTIDAD</th>
                                                    <th style="font-size: .9em;">AUTORIZADO</th>
                                                    <th style="font-size: .9em;">PAGO</th>
                                                    <th style="font-size: .9em;">TIPO</th>
                                                    <th style="font-size: .9em;">AUTORIZÓ PAGO</th>
                                                    <th style="font-size: .9em;">EMPRESA</th>
                                                    <th style="font-size: .9em;">FECHA DISPERSIÓN</th>
                                                    <th style="font-size: .9em;"></th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--End tab content-->
                </div>
                <!--end box-->
            </div>
        </div>
    </div>
</div>
 


<div class="modal fade modal-alertas" id="myModalRegresarCPP" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-info"  >
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">¿DESEA REGRESAR EL PAGO A CXP? <i class="fas fa-undo-alt" style="color:#00C0EF;"></i> </h4>
            </div>
<!--             <div class="modal-body text-center"></div>
 -->            <div class="modal-footer"></div>
        </div>
    </div>
</div>



<div class="modal fade modal-alertas" id="myModaleliminarPago" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-info"  >
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">¿DESEA ELIMINAR EL PAGO? <i class="fas fa-trash" style="color:#DD4B39;"></i> </h4>
            </div>
<!--             <div class="modal-body text-center"></div>
 -->            <div class="modal-footer"></div>
        </div>
    </div>
</div>



<div class="modal fade modal-alertas" id="myModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: #00A65A; color: #fff;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">SOLICITUD VALIDA</h4>
            </div>
            <div class="modal-body text-center"></div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>



  <div class="modal fade modal-alertas" id="myModalchica" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: #00A65A;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">SOLICITUD VALIDA</h4>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer"></div>
        </div>
    </div>
  </div>


  <div class="modal fade modal-alertas" id="myModaleliminar" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header" style="background: #DD4B39;">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Eliminar pago</h4>
        </div>
        <div class="modal-body"></div>
        <div class="modal-footer">
        </div>
      </div> 
    </div>
  </div>





  <div class="modal fade modal-alertas" id="myModalcomentario1" role="dialog">
    <div class="modal-dialog">
         <div class="modal-content">
            <div class="modal-header"  style="background: #DD4B39; color: #fff;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">MOTIVO DE ELIMINACIÓN</h4>
            </div>  
            <form method="post" id="infosol1">
                <div class="modal-body"></div>
                <div class="modal-footer"></div>
             </form>
        </div>
    </div>
</div>




<div class="modal fade modal-alertas" id="myModalcomentario_ch" role="dialog">
    <div class="modal-dialog">
         <div class="modal-content">
            <div class="modal-header"  style="background: #DD4B39; color: #fff;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">MOTIVO DE ELIMINACIÓN</h4>
            </div>  
            <form method="post" id="infosol1_ch">
                <div class="modal-body">   </div>
                <div class="modal-footer"></div>
             </form>
        </div>
    </div>
</div>


 
<script>



    var idpagorechazo;
   
    var link_post2;

    $("#infosol1").submit( function(e) {
        e.preventDefault();
    }).validate({
        submitHandler: function( form ) {

            var data = new FormData($(form)[0]);

            data.append("idpago", idpagorechazo);

            $.ajax({
                url: url + link_post2,
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                method: 'POST',
                type: 'POST', // For jQuery < 1.9
                success: function(data){
                    if( data.resultado ){
                        $("#myModalcomentario1").modal( 'toggle' );
                        tabla_provison_pago.ajax.reload();
                    }else{
                        alert("NO SE HA PODIDO COMPLETAR LA SOLICITUD");
                    }
                },error: function( ){
                    
                }
            });
        }
    });

    function eliminar(idpago) {
 
        idpagorechazo = idpago;
        link_post2 = "Dispersion/datos_para_rechazo_dp/";
        $("#myModalcomentario1 .modal-footer").html("");
        $("#myModalcomentario1 .modal-body").html("");
        $("#myModalcomentario1 ").modal();
 
        $("#myModalcomentario1 .modal-body").append("<div class='form-group col-lg-12'><input type='text' class='form-control' placeholder='Describe motivo de rechazo.' name='observacion' id='observacion' required></div>");

        $("#myModalcomentario1 .modal-footer").append("<div class='btn-group'><button type='submit' class='btn btn-success'>ACEPTAR</button><button type='button' class='btn btn-danger' onclick='cancelarr2()'>CANCELAR</button></div>");
    }

    function cancelarr2(){
        $("#myModalcomentario1").modal('toggle');
    }

    /**CACHAMOS EL EVENTO DE TRANSICION ENTRE LAS PESTAÑAS DEL TAB**/
    //DESDE AQUI CONTROLAMOS EL AUTO LOAD DE INFORMACION

    $('[data-toggle="tab"]').click( function(e) {
        //DEPENDIENDO QUE OPCION VA A ESCOGER ES CUANDO EJECUTA EL CORRESPONDIENTE AJAX.
        switch ( $( this ).attr("href") ) {
            case "#sol_activas":
                tabla_provison_pago.ajax.reload();
                break;
            case "#historial_dp":
                //if( tabla_historial.rows().count() == 0 )
                    tabla_historial.ajax.url( url + "Dispersion/ver_datos_historial" ).load();  
                break;
            case "#historial_echq":
                //if( tabla_echq.rows().count() == 0 )
                    tabla_echq.ajax.url( url + "Dispersion/ver_datos_historial_echq" ).load();
                break;
        }

    });

    /***/
    var tabla_provison_pago;
    /**TABLA UNO*/
    $("#tabla_autorizaciones").ready( function(){

        $('#tabla_autorizaciones thead tr:eq(0) th').each( function (i) {
            if( i != 0 && i != 12 && i != 10 && i != 11){
                var title = $(this).text();
                $(this).html( '<input type="text" style="width:100%;" placeholder="'+title+'" />' );
                $( 'input', this ).on('keyup change', function () {

                    if (tabla_provison_pago.column(i).search() !== this.value ) {
                        tabla_provison_pago
                        .column(i)
                        .search( this.value )
                        .draw();
                        var total = 0;
                        var index = tabla_provison_pago.rows( { selected: true, search: 'applied' } ).indexes();
                        var data = tabla_provison_pago.rows( index ).data()
                        $.each(data, function(i, v){
                            total += parseFloat(v.CA);
                        });
                        var to1 = formatMoney(total);
                        document.getElementById("myText_1").value = to1;
                    }
                });
            }
        });

        $('#tabla_autorizaciones').on('xhr.dt', function ( e, settings, json, xhr ) {
            var total = 0;
            $.each( json.data,  function(i, v){
                total += parseFloat(v.CA);
            });
            var to = formatMoney(total);
            document.getElementById("myText_1").value = to;
        });

        tabla_provison_pago = $("#tabla_autorizaciones").DataTable({
            dom: 'Brtip',
            "buttons": [{
                extend: 'excelHtml5',             
                text: '<i class="fas fa-file-excel"></i> EXPORTAR EXCEL',
                messageTop: "Lista de solicitudes por autorizar",
                attr: {
                    class: 'btn btn-success'       
                },
                exportOptions: {
                    columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
                    format: {
                        header: function (data, columnIdx) { 
                            data = data.replace( '<input type="text" style="width:100%;" placeholder="', '' );
                            data = data.replace( '">', '' )
                            return data;
                        }
                    }
                }
            }],
            "language":lenguaje,
            "processing": false,
            "pageLength": 50,
            "bAutoWidth": false,
            "bLengthChange": false,
            "scrollX": true,
            "bInfo": false,
             "orderable": false,
            "searching": true,
            "columns": [
                {
                    "className": 'details-control',
                    "orderable": false,
                    "data" : null,
                    "defaultContent": '<i class="fas animacion fa-caret-right"></i>'
                },
                {
                    "width": "12%",
                    "data": function( d ){
                        if(d.justificacion != 'CAJA CHICA' && d.justificacion != 'TARJETA CREDITO'){
                            return '<p style="font-size: .8em">'+d.idsolicitud+'</p>';
                        }else{
                            return '<p style="font-size: .8em">'+d.idpago+'</p>';
                        }
                    }
                },
                {
                    "width": "12%",
                    "data": function( d ){
                        return '<p style="font-size: .7em">'+d.nombre+'</p>' + (
                                d.esParcialidad == 'S'
                                    ? "<br><small class='label pull-center bg-blue'>PARCIALIDADES</small>" 
                                    : d.programado && d.programado > 0 
                                        ? "<br><small class='label pull-center bg-blue'>PROGRAMADO</small>" 
                                        : "" )
                    }
                },
                { 
                    "width": "10%",
                    "data": function( data ){

                        var p = '<p style="font-size: .8em">'+formatMoney(data.cantidad) +" "+ data.moneda;

                        if( data.intereses == '1' ){
                            p += "<br><small class='label pull-center bg-maroon'>CREDITO</small><small class='label pull-center bg-gray'>INTÉRES: "+formatMoney(data.interes)+"</small>";
                        }

                        if( data.tipoCambio ){
                            p += "<br><small class='label pull-center bg-maroon'>CAMBIO</small><small class='label pull-center bg-gray'>"+data.tipoCambio+"</small>";
                        }

                        return p+'</p>';
                        
                    }
                },
                { 
                    "width": "10%",
                    "data": function( data ){
                        if(data.intereses=='1'){
                            return '<p style="font-size: .8em">'+formatMoney(data.int_acum)+'</p>';
                        }
                        else{
                            return '<p style="font-size: .8em">'+formatMoney(data.CA)+'</p>';
                        }
                        
                    }
                },
                {   
                    "width": "10%",
                    "data": function( d ){
                        return '<p style="font-size: .8em;">'+d.tipoPago+" <b>"+( d.referencia ?  d.referencia : '' )+"</b></p>"; 
                    }
                },
                {
                    "width": "8%",
                    "data": function( data ){
                        opcio_CH = '<div class="btn-group-vertical" role="group" style="font-size: .8em;">';

                        switch( data.notab){
                            case "1":
                                opcio_CH += "<span>PROVEEDOR</span>";
                                break;
                            case "2":
                                opcio_CH += "<span>"+data.folio+"</span>";  
                                break;
                        }

                        return opcio_CH + '</div>';
                    } 
                },
                {  
                    "width": "8%",
                    "data": function( d ){
                        return '<p style="font-size: .8em;">'+d.abrev+"</p>";
                    }
                },
                {  
                    "width": "12%",
                    "data": function( d ){
                        return '<p style="font-size: .8em;"><B>'+d.auto+'</B></p><p style="font-size: .8em;">'+d.fecha_autorizacion+'</p>';

                    }
                },
                {
                    "width": "12%", 
                    "data": function( data ){
                        opciones2 = '<div class="btn-group-vertical" role="group" style="font-size: .8em;">';                        
                        switch( data.estatus + "" ){
                            case "1":
                            case "5":
                                opciones2 += "<span>Por validar</span>";
                                break;
                            case "35":
                                opciones2 += "<b><span class='text-red'>Rechazó Cuentas por pagar</span></b>";
                                break;  
                            case "25":
                                opciones2 += "<b><span class='text-orange'>En espera</span></b>";
                                break; 
                        }

                        if(data.tienehistorial){
                            opciones2 += "<p><span class='label pull-center bg-yellow'>SUSTITUYO AL CHEQUE:</span> "+data.tienehistorial+"</p>";
                        }
                    
                        return opciones2 + '</div>';
                    } 
                },
                {
                    
                    "data": function( data ){

                        opciones = '<div class="btn-group-vertical" role="group">';
                        if( data.estatus != 25 ){

                            
                            if( data.estatus == 1 || data.estatus==35 )
                                opciones += '<button type="button" class="notification btn btn-primary btn-sm consultar_modal" value="'+data.idsolicitud+'" data-value="BAS"  title="Ver solicitud" style="margin-right: 5px;"><i class="far fa-eye"></i>'+(data.visto == 0 ? '</i><span class="badge">!</span>' : '')+'</button>';

                            opciones += '<button style:"margin-right: 30px;" title="Aceptar pago" class="btn btn-success btn-sm aceptar_solicitud"><i class="fas fa-check"></i></button>';
                            opciones += '<button style:"margin-right: 30px;" title="Enviar a cola de pagos" class="btn btn-warning btn-sm cola_depagos"><i class="fas fa-clock"></i></button>';
                            opciones += '<button style:"margin-right: 30px;" title="Regresar a CPP" class="btn btn-danger btn-sm regresar_cxp"><i class="fas fa-undo-alt"></i></button>';

                            /*
                            if( data.estatus == 1 || data.estatus==35 )
                                opciones += '<button style:"margin-right: 30px;" title="Eliminar pago" class="btn btn-danger btn-sm" onClick="eliminar_pago('+data.idpago+","+data.notab+')"><i class="fas fa-trash"></i></button>';
                            */
                        }else
                            opciones += '<button style:"margin-right: 30px;" title="Enviar a cola de pagos" class="btn btn-secondary btn-sm cola_depagos"><i class="fas fa-clock"></i></button>';

                        return opciones + '</div>';

                    }
        }],
        "columnDefs": [ {
            "orderable": false, "targets": 0
        }],
        "ajax": {
            "url": url + "Dispersion/ver_datos",
            "type": "POST",
            cache: false,
            "data": function( d ){
                return  $.extend( d, { "nomdepto": $("#cxp1_sel").val(), "idempresa": $("#cxp2_sel").val() } );
            }
        }
        });

        $('#tabla_autorizaciones tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = tabla_provison_pago.row( tr );

            if ( row.child.isShown() ) {
                row.child.hide();
                tr.removeClass('shown');
                $(this).parent().find('.animacion').removeClass("fa-caret-down").addClass("fa-caret-right");
            }
            else {
                var informacion_adicional = '<table class="table text-justify">'+
                '<tr>'+
                '<td style="font-size: .9em" colspan="2"><strong>JUSTIFICACIÓN: </strong>'+row.data().justificacion+'</td>'+
                '</tr>'+
                '</table>';

                row.child( informacion_adicional ).show();
                tr.addClass('shown');
                $(this).parent().find('.animacion').removeClass("fa-caret-right").addClass("fa-caret-down");
            }
        });

        //SE ACEPTA EL PAGO PARA REALIZAR DISPERCION
        $('#tabla_autorizaciones tbody').on('click', '.aceptar_solicitud', function () {
            
            $("#myModal .modal-footer").html("");
            var tr = $(this).closest('tr');
            var row = tabla_provison_pago.row( $(this).closest('tr') ).data();

            $.post( url + "Dispersion/provisionardp", { idpago : row.idpago, tabla : row.notab, forma_pago : row.tipoPago } ).done( function ( data ) {  

                data = JSON.parse(data);

                if( data.resultado ){
                    
                    tabla_provison_pago.row( tr ).remove().draw();

                    var total = 0;
                    $.each( tabla_provison_pago.rows().data(),  function(i, v){
                        total += parseFloat(v.cantidad);
                    });
                    var to = formatMoney(total);
                    document.getElementById("myText_1").value = to;

                    /*RECUPERAR LA INFORMACION DE LA TABLA QUE SE CONSULTO Y ACTUALIZA
                    if( row.tipoPago == 'ECHQ' ){

                        tabla_echq.clear();
                        tabla_echq.rows.add( data.tabla );
                        tabla_echq.draw();

                    }else{

                        tabla_historial.clear();
                        tabla_historial.rows.add( data.tabla );
                        tabla_historial.draw();

                    }
                    */
                }
                
            });
            
        });

        //SE MANDA A COLA DE PAGOS DESDE DISPERSION
        $('#tabla_autorizaciones tbody').on('click', '.cola_depagos', function () {
            
            $("#myModal .modal-footer").html("");
            var tr = $(this).closest('tr');
            var row = tabla_provison_pago.row( $(this).closest('tr') ).data();

            $.post( url + "Dispersion/regresarcolapagosdp", { idpago : row.idpago, tabla : row.notab } ).done(function () { 
                if( row.estatus == 25 ){
                    row.estatus = row.notab == 1 ? 1 : 5;
                }else{
                    row.estatus = 25;
                }

                tabla_provison_pago.row( tr ).data( row ).draw();
            });
        });


        //RETORNA EL PAGO A CXP
        $('#tabla_autorizaciones tbody').on('click', '.regresar_cxp', function () {
            trdispersando = $(this).closest('tr');
            var row = tabla_provison_pago.row( $(this).closest('tr') ).data();

            $("#myModalRegresarCPP .modal-footer").html("");
            $("#myModalRegresarCPP .modal-footer").append('<button class="btn btn-info btn-block" onClick=regresa_pago_cxp()>REGRESAR</button><button class="btn btn-danger btn-block" onClick="cancelar_regresa()">CANCELAR</button>');
            $("#myModalRegresarCPP").modal();
        });
    });

    var trdispersando;

    function regresa_pago_cxp() {
        var row = tabla_provison_pago.row( trdispersando ).data();
        
        $.post( url + "Dispersion/rechazo_para_cuentas", { idpago : row.idpago, tabla : row.notab } ).done(function () {  
            $("#myModalRegresarCPP").modal('toggle');
            tabla_provison_pago.row( trdispersando ).remove().draw();
        });
    }

    // ____________________________________________________

    /**TABLA DOS*/
    $("#tabla_historial_autorizaciones").ready( function(){

        $('#tabla_historial_autorizaciones thead tr:eq(0) th').each( function (i) {
            if( i != 0 && i != 12){
                var title = $(this).text();
                $(this).html( '<input type="text" style="font-size: .8em; width:100%;" placeholder="'+title+'" />' );
                $( 'input', this ).on('keyup change', function () {

                    if (tabla_historial.column(i).search() !== this.value ) {
                        tabla_historial
                        .column(i)
                        .search( this.value )
                        .draw();

                        var total = 0;
                        var index = tabla_historial.rows( { selected: true, search: 'applied' } ).indexes();
                        var data = tabla_historial.rows( index ).data();

                        $.each(data, function(i, v){
                            total += parseFloat(v.CA);
                        });

                        var to1 = formatMoney(total);
                        document.getElementById("myText_2").value = to1;
                    }
                });
            }
        });

        $('#tabla_historial_autorizaciones').on('xhr.dt', function ( e, settings, json, xhr ) {
            var total = 0;
            
            $.each( json.data,  function(i, v){
                total += parseFloat(v.CA);
            });

            var to = formatMoney(total);
            document.getElementById("myText_2").value = to;
        });

        tabla_historial = $("#tabla_historial_autorizaciones").DataTable({
            dom: 'Brtip',
            "buttons": [{
                extend: 'excelHtml5',             
                text: '<i class="fas fa-file-excel"></i> EXPORTAR EXCEL',
                messageTop: "Lista de solicitudes dispersadas",
                attr: {
                    class: 'btn btn-success'       
                },
                exportOptions: {
                    columns: [ 0, 1, 2, 3, 4, 5 ,6,7,8,9,10],
                    format: {
                        header: function (data, columnIdx) { 
                            data = data.replace( '<input type="text" style="font-size: .8em; width:100%;" placeholder="', '' );
                            data = data.replace( '">', '' )
                            return data;
                        }
                    }
                }
            }],
            "language":lenguaje,
            "processing": false,
            "pageLength": 50,
            "bAutoWidth": false,
            "bLengthChange": false,
            "scrollX": true,
            "bInfo": false,
            "searching": true,
            "columns": [
                {
                    "width": "1%",
                    "className": 'details-control',
                    "orderable": false,
                    "data" : null,
                    "defaultContent": '<i class="fas animacion fa-caret-right"></i>'
                },
                { 
                    
                    "width": "13%",
                    "data": function( d ){
                        if(d.programado != null && d.programado != 0 && d.programado != '') {
                            return '<p style="font-size: .8em">'+d.nombre +'</p><br><small class="label pull-center bg-blue">PROGRAMADO</small>';
                        }else{
                            return '<p style="font-size: .8em">'+d.nombre+'</p>';
                        }
                    }
                },
                {
                    
                    "width": "10%",
                    "data": function( data ){
                        // return '<p style="font-size: .9em;">'+formatMoney(data.cantidad)+'</p>';
                        if(data.intereses=='1'){
                            return "<p style='font-size: .9em;'>"+formatMoney(data.cantidad)+"<br><small class='label pull-center bg-maroon'>CRÉDITO</small>";
                        }else{
                            return "<p style='font-size: .9em;'>"+formatMoney(data.cantidad)+"</p>";
                        }
                    }
                },
                {
                    
                    "width": "10%", 
                    "data": function( data ){
                        return '<p style="font-size: .9em;">'+formatMoney(data.CA)+'</p>';
                    }
                },
                {
                    
                    "width": "10%",
                    "data": function( data ){
                        if(data.intereses=='1'){
                            return "<p style='font-size: .9em;'><small class='label pull-center bg-gray'>"+formatMoney(data.interes)+"</small></p>";
                        }else{
                            return '<p style="font-size: .9em;"> - </p>';
                        }        
                    }
                },
                {
                    
                    "width": "10%",
                    "data": function( data ){
                        return '<p style="font-size: .9em;">'+formatMoney(data.sum_interes)+'</p>';
                    }
                },
                {
                    
                    "width": "10%",
                    "data": function( d ){
                        switch (d.programado) {
                            case '1':
                                return '<p style="font-size: .9em">'+d.num_pago+ ( d.ppago!='' && d.ppago != null ? '/'+d.ppago : '' ) +'</p>'+"<small class='label pull-center bg-gray'>MENSUAL</small>";
                                break;
                            case '2':
                                return '<p style="font-size: .9em">'+d.num_pago+ ( d.ppago!='' && d.ppago != null ? '/'+d.ppago : '' ) +'</p>'+"<small class='label pull-center bg-gray'>BIMESTRAL</small>";
                                break;
                            case '3':
                                return '<p style="font-size: .9em">'+d.num_pago+ ( d.ppago!='' && d.ppago != null ? '/'+d.ppago : '' ) +'</p>'+"<small class='label pull-center bg-gray'>TRIMESTRAL</small>";
                                break;
                            case '4':
                                return '<p style="font-size: .9em">'+d.num_pago+ ( d.ppago!='' && d.ppago != null ? '/'+d.ppago : '' ) +'</p>'+"<small class='label pull-center bg-gray'>CUATRIMESTRAL</small>";
                                break;
                            case '6':
                                return '<p style="font-size: .9em">'+d.num_pago+ ( d.ppago!='' && d.ppago != null ? '/'+d.ppago : '' ) +'</p>'+"<small class='label pull-center bg-gray'>SEMESTRAK</small>";
                                break;
                            case '7':
                                return '<p style="font-size: .9em">'+d.num_pago+ ( d.ppago!='' && d.ppago != null ? '/'+d.ppago : '' ) +'</p>'+"<small class='label pull-center bg-gray'>SEMANAL</small>";
                                break;
                            case '8':
                                return '<p style="font-size: .9em">'+d.num_pago+ ( d.ppago!='' && d.ppago != null ? '/'+d.ppago : '' ) +'</p>'+"<small class='label pull-center bg-gray'>QUINCENAL</small>";
                                break;
                            default:
                                return '<p style="font-size: .9em"> - </p>';
                                break;
                        }         
                    }
                },
                {
                    
                    "width": "10%",
                    "data": function( d ){
                        if(d.tipoPago == "ECHQ"){
                            return '<p style="font-size: .9em;">'+d.tipoPago+" <b>"+d.referencia+"</b></p>";                    
                        }else{
                            return '<p style="font-size: .9em;">'+d.tipoPago+"</p>";
                        }
                    }
                },
                { 
                    
                    "width": "9%", 
                    "data": function( data ){
                        opciones2_CH = '<div class="btn-group-vertical" role="group" style="font-size: .9em;">';
                        switch( data.notab){
                            case "1":
                                opciones2_CH += "<span>PROVEEDOR</span>";
                                break;            
                            case "2":
                                opciones2_CH += "<span>CAJA CHICA</span>";
                                break;          
                        }

                        return opciones2_CH + '</div>';
                    } 
                },
                {
                    "width": "10%",
                    "data": function( d ){
                        return '<p style="font-size: .9em;"><B>'+d.auto+'</B></p><p style="font-size: .9em;">'+d.fecha_autorizacion+'</p>';
                    }
                },
                {
                    "width": "5%",
                    "data": function( data ){
                        return '<p style="font-size: .9em;">'+data.abrev+'</p>';
                    }
                },
                {

                    "width": "10%",
                    "data": function( data ){
                        if(data.fechaDis==null||data.fechaDis==''){
                            return "<small class='label pull-center bg-olive'> DISPERSADA</small>";
                        }else{
                            return '<p style="font-size: .9em;"><b><i>'+data.fechaDis+'</i></b></p>'+"<small class='label pull-center bg-olive'> DISPERSADA</small>";
                        }                                
                    }
                },
                {
                    "width": "5%", 
                    "data": function( data ){
                        opciones2 = '<div class="btn-group-vertical" role="group">';

                        if( data.justificacion != 'PAGO DE CAJA CHICA' )
                            opciones2 += '<button data-toggle="tooltip" data-placement="top" title="Regresar a activos" type="button" class="btn btn-warning btn-sm regresar_dispercion"><i class="fas fa-undo"></i></button>';
                        else
                            opciones2 += "<small class='label pull-center bg-red'> CAJA CHICA</small>";

                        return opciones2 + '</div>';
                    },
                    
                }],
                "columnDefs": [ {
                    "orderable": false,
                    "targets": 0
                }],
                /*,
                "ajax": {
                    "url": url + "Dispersion/ver_datos_historial",
                    "type": "POST",
                    cache: false,
                    "data": function( d ){
                        return  $.extend( d, { "nomdepto": $("#cxp1_sel").val(), "idempresa": $("#cxp2_sel").val() } );
                    }
                }
                */
        });

        $('#tabla_historial_autorizaciones tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = tabla_historial.row( tr );

            if ( row.child.isShown() ) {
                row.child.hide();
                tr.removeClass('shown');
                $(this).parent().find('.animacion').removeClass("fa-caret-down").addClass("fa-caret-right");
            }else {
                var informacion_adicional = '<table class="table text-justify">'+
                   '<tr>'+       
                    '<td style="font-size: .9em" colspan="2"><strong>JUSTIFICACIÓN: </strong>'+row.data().justificacion+'</td>'+
                    '</tr>'+
                '</table>';

                row.child( informacion_adicional ).show();
                tr.addClass('shown');
                $(this).parent().find('.animacion').removeClass("fa-caret-right").addClass("fa-caret-down");
            }
        });

        $('#tabla_historial_autorizaciones tbody').on('click', '.regresar_dispercion', function () {
            
            var tr = $(this).closest('tr');
            var row = tabla_historial.row( tr ).data();

            regresar_pago( row.idpago, 1, tr, row.tipoPago );
        });
    }); 
    // ____________________________________________________

    /*TABLA TRES*/
    $("#tabla_historial_echq").ready( function(){

        $('#tabla_historial_echq thead tr:eq(0) th').each( function (i) {
            if( i != 0 && i != 9){
                var title = $(this).text();
                $(this).html( '<input type="text" style="font-size: .8em; width:100%;" placeholder="'+title+'" />' );
                $( 'input', this ).on('keyup change', function () {

                    if (tabla_echq.column(i).search() !== this.value ) {
                        tabla_echq
                        .column(i)
                        .search( this.value )
                        .draw();

                        var total = 0;
                        var index = tabla_echq.rows( { selected: true, search: 'applied' } ).indexes();
                        var data = tabla_echq.rows( index ).data();

                        $.each(data, function(i, v){
                            total += parseFloat(v.CA);
                        });

                        var to1 = formatMoney(total);
                        document.getElementById("myText_3").value = to1;
                    }
                });
            }
        });

        $('#tabla_historial_echq').on('xhr.dt', function ( e, settings, json, xhr ) {
            var total = 0;
            $.each( json.data,  function(i, v){
                total += parseFloat(v.CA);
            });
            var to = formatMoney(total);
            document.getElementById("myText_3").value = to;
        });

        tabla_echq = $("#tabla_historial_echq").DataTable({
            dom: 'Brtip',
             "buttons": [
               {
                   extend: 'excelHtml5',             
                   text: '<i class="fas fa-file-excel"></i> EXPORTAR EXCEL',
                   messageTop: "Lista de solicitudes por autorizar",
                   attr: {
                       class: 'btn btn-success'       
                   },
                    exportOptions: {
                       columns: [ 0, 1, 2, 3, 4, 5 ],
                       format: {
                            header: function (data, columnIdx) { 

                                data = data.replace( '<input type="text" style="font-size: .8em; width:100%;" placeholder="', '' );
                                data = data.replace( '">', '' )
                                return data;
                            }
                        }
                   }
               },
               ],
            "language":lenguaje,
            "processing": false,
            "pageLength": 50,
            "bAutoWidth": false,
            "bLengthChange": false,
            "scrollX": true,
            "bInfo": false,
            "searching": true,
            "columns": [
                {   
                    "width": "3%",
                    "className": 'details-control',
                    "orderable": false,
                    "data" : null,
                    "defaultContent": '<i class="fas animacion fa-caret-right"></i>'
                },
                {
                    "width": "15%",
                    "data": function( d ){
                        if (d.programado != null && d.programado != 0 && d.programado != '') {
                            return  '<p style="font-size: .8em">'+d.responsable + "<br><small class='label pull-center bg-blue'>PROGRAMADO</small></p>";
                        }else{
                            return '<p style="font-size: .8em">'+d.responsable+'</p>';
                        }
                        
                    }
                },
                {
                    "width": "8%",
                    "data": function( data ){
                        if(data.intereses=='1'){
                            return '<p style="font-size: .9em">'+formatMoney(data.cantidad)+ "</p><small class='label pull-center bg-maroon'>CREDITO</small><small class='label pull-center bg-gray'>INTÉRES: "+formatMoney(data.interes)+"</small>";
                        }else{
                            return '<p style="font-size: .9em">'+formatMoney(data.cantidad)+'</p>';
                        }
                    }
                },
                {
                    "width": "8%", 
                    "data": function( data ){
                        if( data.intereses == '1' ){
                            return  '<p style="font-size: .9em">'+formatMoney( data.int_acum )+'</p>';
                        }else{
                            return '<p style="font-size: .9em">'+formatMoney( data.CA )+'</p>';
                        }
                    }
                },
                {
                    "width": "8%",
                    "data": function( d ){
                        if(d.tipoPago == "ECHQ"){
                            return '<p style="font-size: .9em;">'+d.tipoPago+" <b>"+ d.referencia ? d.referencia : '' +"</b></p>";                    
                        }else{
                            return '<p style="font-size: .9em;">'+d.tipoPago+"</p>";
                        }
                    }
                },
                {
                    "width": "9%", 
                    "data": function( data ){
                            opciones2_CH = '<div class="btn-group-vertical" role="group" style="font-size: .9em;">';

                            switch( data.bd){
                                    case "1":
                                    opciones2_CH += "<span>PROVEEDOR</span>";
                                    break;
                                     
                                    case "2":
                                    opciones2_CH += "<span>CAJA CHICA</span>";

                                    break;    
                                      
                            }

                            return opciones2_CH + '</div>';
                    } 
                },
                {
                    "width": "10%",
                    "data": function( d ){
                        return '<p style="font-size: .9em;"><B>'+d.auto+'</B></p><p style="font-size: .9em;">'+d.fecha_autorizacion+'</p>';

                    }
                },
                {
                    "width": "10%",
                    "data": function( data ){
                        return '<p style="font-size: .9em;">'+data.abrev+'</p>';
                    }
                },
                {
                    "width": "10%",
                    "data": function( data ){
 
                        if(data.programado == null||data.programado == 0||data.programado == ''){
                             if(data.fechaDis==null||data.fechaDis==''){
                            return "<small class='label pull-center bg-olive'>DISPERSADO</small><br><small class='label pull-center bg-maroon'>CHEQUE AUN NO ENTREGADO</small>";

                        }
                        else{
                            return '<p style="font-size: .9em;"><b><i>'+data.fechaDis+'</i></b></p>'+"<small class='label pull-center bg-olive'>DISPERSADO</small><br><small class='label pull-center bg-maroon'>CHEQUE AUN NO ENTREGADO</small>";
                        }
                         }
                        else{
                            if( data.fechaDis == null || data.fechaDis=='' ){
                            return "<small class='label pull-center bg-olive'>DISPERSADO</small><br><small class='label pull-center bg-maroon'>CHEQUE AUN NO ENTREGADO</small>";

                        }
                        else{
                            return '<p style="font-size: .9em;"><b><i>'+data.fechaDis+'</i></b></p>'+"<small class='label pull-center bg-olive'>DISPERSADO</small><br><small class='label pull-center bg-maroon'>CHEQUE AUN NO ENTREGADO</small>";
                        }
                             }
                        }
 
                },
                {
                    "width": "5%", 
                        "data": function(data){
                            opciones2 = '<div class="btn-group-vertical" role="group">';
                           
                            opciones2 += '<button data-toggle="tooltip" data-placement="top" title="Regresar a activos" type="button" class="btn btn-warning btn-sm regresar_dispercion"><i class="fas fa-undo"></i></button>';
 
                            return opciones2 + '</div>';
                        },
                        "orderable": false
                }]/*,
                "ajax": {
                    "url": url + "Dispersion/ver_datos_historial_echq",
                    "type": "POST",
                    cache: false,
                    "data": function( d ){
                        return  $.extend( d, { "nomdepto": $("#cxp1_sel").val(), "idempresa": $("#cxp2_sel").val() } );
                    }
                }
                */
            });

            $('#tabla_historial_echq tbody').on('click', 'td.details-control', function () {
                var tr = $(this).closest('tr');
                var row = tabla_echq.row( tr );

                if ( row.child.isShown() ) {
                    row.child.hide();
                    tr.removeClass('shown');
                    $(this).parent().find('.animacion').removeClass("fa-caret-down").addClass("fa-caret-right");
                }
                else {
                    var informacion_adicional = '<table class="table text-justify">'+
                    '<tr>'+
    
                    '<td style="font-size: .9em" colspan="2"><strong>JUSTIFICACIÓN: </strong>'+row.data().justificacion+'</td>'+
                    '</tr>'+
                    '</table>';

                    row.child( informacion_adicional ).show();
                    tr.addClass('shown');
                    $(this).parent().find('.animacion').removeClass("fa-caret-right").addClass("fa-caret-down");
                }
            });

            $('#tabla_historial_echq tbody').on('click', '.regresar_dispercion', function () {
            
                var tr = $(this).closest('tr');
                var row = tabla_echq.row( tr ).data();

                regresar_pago( row.idpago, 2, tr, row.tipoPago );
            });

        }); 

    var idpagorechazo_ch;
    var link_post2_ch;

    $("#infosol1_ch").submit( function(e) {
        e.preventDefault();
    }).validate({
        submitHandler: function( form ) {

            var data = new FormData( $(form)[0] );
            data.append("idpago", idpagorechazo_ch);
 
            $.ajax({
                url: url + link_post2_ch,
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                method: 'POST',
                type: 'POST', // For jQuery < 1.9
                success: function(data){
                    if( data.resultado ){
                        $("#myModalcomentario_ch").modal( 'toggle' );
                        tabla_44.ajax.reload();
                    }else{
                        alert("NO SE HA PODIDO COMPLETAR LA SOLICITUD");
                    }
                },error: function( ){
                    
                }
            });
        }
    });

    function cancelar_regresa() {
        $("#myModalRegresarCPP").modal('toggle');
    }
    /***********************/

    //ELIMINA PAGO Y CANCELA LA SOLICITUD
    function eliminar_pago(idpago, tabla) {
        $("#myModaleliminarPago .modal-footer").html("");
        $("#myModaleliminarPago .modal-footer").append('<button class="btn btn-danger btn-block" onClick="elimina_pago('+idpago+','+tabla+')">ELIMINAR</button><button class="btn btn-block" onClick="cancelar_eliminar()">CANCELAR</button>');
        $("#myModaleliminarPago").modal();
    }

    function elimina_pago(idpago, tabla) {
        $.get(url+"Dispersion/eliminar_pago/"+idpago+"/"+tabla).done(function () {  
            $("#myModaleliminarPago").modal('toggle');
            tabla_provison_pago.ajax.reload(null,false);
            tabla_historial.ajax.reload(null,false);
            tabla_echq.ajax.reload(null,false);
        });
    }
    /********************************/

    function regresar_pago( idpago, tabla, tr, forma_pago ) {
        $.post( url + "Dispersion/regresar_pago", {  idpago :  idpago, tabla : tabla } ) .done(function () {  
            if( forma_pago == "ECHQ" ){
                tabla_echq.row( tr ).remove().draw();
            }else{
                tabla_historial.row( tr ).remove().draw();
            }
        });
    }

    function cancelar_eliminar() {
        $("#myModaleliminarPago").modal('toggle');
    }

    function regresar_colap_dp(idsolicitud, tabla) {
        $("#myModal .modal-footer").html("");
        $.get(url+"Dispersion/regresar_cp_new/"+idsolicitud+"/"+tabla).done(function () {  
            tabla_provison_pago.ajax.reload(null,false);
            tabla_historial.ajax.reload(null,false);
            tabla_echq.ajax.reload(null,false);
        });
    }

    $(window).resize(function(){
        tabla_provison_pago.columns.adjust();
        tabla_historial.columns.adjust();
        tabla_echq.columns.adjust();
    });
 
</script>

<?php
    require ("footer.php");
?>