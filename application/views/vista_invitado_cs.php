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
                        <h3>HISTORIAL SOLICITUDES</h3>
                    </div>
                    <div class="box-body">
                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                             <li class="active"><a id="#historial_activas_prov" data-toggle="tab" href="#historial_activas" role="tab" aria-controls="#home" aria-selected="true">ACTIVAS PAGO PROVEEDOR</a></li>
                             <li><a id="#historial_activas_cch" data-toggle="tab" href="#historial_activas" role="tab" aria-controls="#home" aria-selected="true">ACTIVAS PAGO CAJA CHICA</a></li>
                         </ul>
                    </div>
                    <div class="tab-content">
                        <div class="active tab-pane" id="historial_activas">
                            <div class="row">
                                <div class="col-lg-12">
                                    <form id="formulario_historial_cp" autocomplete="off" action="<?= site_url("Reportes/reporte_historial_solicitudes") ?>" method="post">
                                        <div class="col-lg-2">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fas fa-calendar-alt"></i> <b>INICIO</b></span>
                                                <input class="form-control fechas_filtro from" type="text" id="fecInicial" name="fechaInicial" maxlength="10"/>
                                            </div>
                                        </div>
                                        <div class="col-lg-2">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fas fa-calendar-alt"></i> <b>FIN</b></span>
                                                <input class="form-control fechas_filtro to" type="text" id="fecFinal" name="fechaFinal" maxlength="10"/>
                                            </div>
                                        </div>
                                        <div id="elementos_hidden"></div>
                                        <div id="tipo_reporte"><input type="hidden" name="tipo_reporte" value="historial_activas_prov"></div>
                                    </form>
                                    <div class="col-md-3">
                                        <div class="input-group-addon" style="padding: 4px;">
                                            <h4 style="padding: 0; margin: 0;"><label>&nbsp;Total: $<input style="text-align: right; border-bottom: none; border-top: none; border-right: none;  border-left: none; background: white;" disabled="disabled" readonly="readonly" type="text"  name="myText_1" id="myText_1"></label></h4>
                                        </div>
                                    </div> 
                                        <table class="table table-striped" id="historial_sol_activas">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th style="font-size: .8em">#</th>
                                                    <th style="font-size: .8em">EMPRESA</th>
                                                    <th style="font-size: .8em">FOLIO</th>
                                                    <th style="font-size: .8em">FECHA CAPTURA</th>
                                                    <th style="font-size: .8em">FECHA AUTORIZACIÓN</th>
                                                    <th style="font-size: .8em">FECHA FACT.</th>
                                                    <th style="font-size: .8em">PROVEEDOR</th> 
                                                    <th style="font-size: .8em">DEPARTAMENTO</th>
                                                    <th style="font-size: .8em">CANTIDAD</th>
                                                    <th style="font-size: .8em">PAGADO</th>
                                                    <th style="font-size: .8em">SALDO</th>
                                                    <th style="font-size: .8em">ESTATUS</th>
                                                    <th style="font-size: .8em">GASTO</th>
                                                    <th style="font-size: .8em">FORMA PAGO</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                        </table>
                                </div>
                            </div>
                        </div>
                             <!--End solicitudes finalizadas--> 
                        
                                </div>
                            </div><!--End tab content-->
                        </div><!--end box-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade modal-alertas" id="modal_opciones" role="dialog">
    <div class="modal-dialog">
       <div class="modal-content">
        <div class="modal-header bg-maroon">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">SELECCIONA OPCIÓN</h4>
        </div>  
<!--         <form method="post" id="form_abono">
 -->            <div class="modal-body"></div>
             <div class="modal-footer"></div>
<!--         </form>
 -->    </div>
    </div>
</div>




<script>

    var tabla_activas;
    var tr_global;
    var valor_input = Array( $('#historial_sol_activas th').length );

    $('.fechas_filtro').datepicker({
        format: 'dd/mm/yyyy',
        endDate: '-0d'
    });
    $('#fecInicial').on('keyup change', function(){
        var str = $(this).val();
        if(str.length==2 || str.length==5){
            $('#fecInicial').val(str+'/');
        }
    }); $('#fecFinal').on('keyup change', function(){
        var str = $(this).val();
        if(str.length==2 || str.length==5){
            $('#fecFinal').val(str+'/');
        }
    }); 

    $('[data-toggle="tab"]').click( function(e) {
        $("#tipo_reporte").html("");

        $(".fechas_filtro ").val("");

        switch( $(this).attr('id') ){
            case '#historial_activas_prov':
                tabla_activas.ajax.url( url +"Invitado/TablaInvitadoSolicitudesA" ).load();
                $("#tipo_reporte").append(`<input type="hidden" name="tipo_reporte" value="${($(this).attr('id')).replace(/[#]/g,'')}">`);
                break;
            case '#historial_activas_cch':
                tabla_activas.ajax.url( url +"Invitado/TablaInvitadoSolicitudesB" ).load();
                $("#tipo_reporte").append(`<input type="hidden" name="tipo_reporte" value="${($(this).attr('id')).replace(/[#]/g,'')}">`);
                break;
        }
    });

    $("#historial_sol_activas").ready( function(){
        $('#historial_sol_activas thead tr:eq(0) th').each( function (i) {
            if( i != 0 && i != 15 ){
                var title = $(this).text();
                $(this).html( '<input type="text" style="font-size: .9em; width: 100%;" data-value="'+title+'" placeholder="'+title+'" />' );

                $( 'input', this ).on('keyup change', function () {
                    if (tabla_activas.column(i).search() !== this.value ) {
                        tabla_activas
                        .column(i)
                        .search( this.value)
                        .draw();
                        
                        valor_input[i] = this.value;

                        var total = 0;
                        var index = tabla_activas.rows( { selected: true, search: 'applied' } ).indexes();
                        var data = tabla_activas.rows( index ).data();
                        $.each(data, function(i, v){
                           total += parseFloat(v.cantidad);
                       });
                        var to1 = formatMoney(total);
                        document.getElementById("myText_1").value = to1;
                    }
                });
            }
        });
        
        $('#historial_sol_activas').on('xhr.dt', function ( e, settings, json, xhr ) {
            var total = 0;
            $.each( json.data,  function(i, v){
                total += parseFloat(v.cantidad);
            });
            document.getElementById("myText_1").value = formatMoney( total );
        });

        tabla_activas = $("#historial_sol_activas").DataTable({
            dom: 'Brtip',
            "buttons": [
                {
                    text: '<i class="fas fa-file-excel"></i> EXPORTAR',
                    action: function(){
                        $("#elementos_hidden").html("");
                        $('#historial_sol_activas thead tr:eq(0) input').each( function (i) {
                            if( valor_input[i+1] )
                                $("#elementos_hidden").append( '<input type="hidden" name="'+$(this).data('value')+'" value="'+valor_input[i+1]+'">' )
                        });

                        $("#elementos_hidden").append( '<input type="hidden" name="tipo_reporte" value="'+$("ul.nav-tabs li.active a[data-toggle='tab']").attr('href')+'">' );
                        
                        if( $("#formulario_historial_cp").valid() ){
                            $("#formulario_historial_cp").submit();
                        }
                        
                    },
                    attr: {
                        class: 'btn btn-success',
                    }
                },
                // {
                //     extend: 'excel',             
                //     text: '<i class="fas fa-file-excel"></i> EXPORTAR',
                //     messageTop: "LISTADO DE SOLICITUDES",
                //     attr: {
                //         class: 'btn btn-success'       
                //     },
                //     exportOptions: {
                //         columns: [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21],
                //         format:{    
                //             header:  function (data, columnIdx) {

                //                 data = data.replace( '<input type="text" style="font-size: .9em; width: 100%;" placeholder="', '' );
                //                 data = data.replace( '" />', '' );
                //                 data = data.replace( '">', '' );
                //                 return data;
                //             }
                //         }
                //     }
                // },
                {
                    text: '<i class="fas fa-file-excel"></i> AUXILIAR DE AUTORIZACIÓN',
                    action: function(){
                        window.open( url + "Historial/generar_documento_relacion", "_blank")
                    },
                    attr: {
                        class: 'btn btn-danger'
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
                "width": "5%",
                "data" : function( d ){
                    return '<p style="font-size: .8em">'+d.idsolicitud+'</p>'
                }
            },
            {
                "orderable": false,
                "width": "7%",
                "data" : function( d ){
                    return '<p style="font-size: .8em">'+d.abrev+'</p>'
                }
            },
            {
                "orderable": false,
                "width": "7%",
                "data" : function( d ){
                    return '<p style="font-size: .8em">'+(d.folio ? d.folio : "SF")+'</p>'
                }
            },
            {
                "orderable": false,
                "width": "8%",
                "data" : function( d ){
 
                    return '<p style="font-size: .8em">'+formato_fechaymd(d.feccrea)+'</p>'
 
                }
            },
            {
                "orderable": false,
                "width": "8%",
                "data" : function( d ){
                    return '<p style="font-size: .8em">'+( d.fecha_autorizacion ? formato_fechaymd(d.fecha_autorizacion) : "-" )+'</p>'
                }
            },
            {
                "orderable": false,
                "width": "8%",
                "data" : function( d ){
                    return '<p style="font-size: .8em">'+formato_fechaymd(d.fecelab)+'</p>'
                }
            },
            {
                "orderable": false,
                "width": "8%",
                "data" : function( d ){
                    return '<p style="font-size: .7em">'+d.nombre+'</p>'
                }
            },
            {
                "orderable": false,
                "width": "8%",
                "data" : function( d ){
                    return '<p style="font-size: .8em">'+d.nomdepto+'</p>'
                }
            },
            {
                "orderable": false,
                "width": "7%",
                "data": function( d ){

                    var resultado = d.cantidad;

                    if( d.programado && (d.programado < 7 || d.programado != "7")  ){
                        resultado = d.cantidad * ( d.mpagar / d.programado );
                    }
                    
                    if( d.programado && (d.programado == 7 || d.programado == "7") ){
                        resultado = d.cantidad * d.mpagar;
                    }

                    return '<p style="font-size: .8em">'+formatMoney(  resultado )+'</p>';
                }
            },
            {
                "orderable": false,
                "width": "7%",
                "data": function( d ){
                    return '<p style="font-size: .8em">'+formatMoney( d.pagado )+'</p>';
                }
            },
            {
                "orderable": false,
                "data": function( d ){

                    var resultado = d.cantidad;

                    if( d.programado && (d.programado < 7 || d.programado != "7")  ){
                        resultado = d.cantidad * ( d.mpagar / d.programado );
                    }
                    
                    if( d.programado && (d.programado == 7 || d.programado == "7") ){
                        resultado = d.cantidad * d.mpagar;
                    }

                    return '<p style="font-size: .8em">'+formatMoney( resultado - d.pagado )+'</p>';
                }
            },
            {
                "orderable": false,
                "width": "8%",
                "data" : function( d ){

                    switch(d.estatus_pago){
                        case '0':
                            return '<p style="font-size: .7em">PAGO AUTORIZADO DG</p>';
                            break;
                        case '1':
                            return '<p style="font-size: .7em">POR DISPERSAR</p>';
                            break;
                        case '2':
                            return '<p style="font-size: .7em">SE HA PAUSADO EL PROCESO DE ESTE PAGO</p>';
                            break;
                        case '5':
                            return '<p style="font-size: .7em">POR DISPERSAR</p>';
                            break;
                        case '15':
                            return '<p style="font-size: .7em">'+(d.metoPago=='ECHQ'?'CHEQUE SIN COBRAR':'POR CONFIRMAR PAGO CXP')+'</p>';
                            break;
                        case '11':
                            return '<p style="font-size: .7em">EN ESPERA PARA ENVIAR A DISPERSION, "PAGOS" CXP</p>';
                            break;
                        case '12':
                            return '<p style="font-size: .7em">PAGO DETENIDO, REVISAR "PAGOS PAUSADOS" CXP</p>';
                            break;
                        case '16':
                            if(d.estatus_pago && d.estatus_pago == '16' && (d.etapa=='Pago Aut. por DG, Factura Pendiente'||d.etapa=='Pagado')){ 
                                return '<p style="font-size: .7em">PAGO COMPLETO</p>' ;
                            }
                            else{
                                return '<p style="font-size: .7em">'+d.etapa+'</p>';
                            }
                            break;
                        default:
                            if( d.etapa == 'Revisión Cuentas Por Pagar'){
                                var fec =  new Date();//new Date(fec[1]+'/'+fec[0]+"/"+fec[2]);
                                var day = fec.getDay();
                                var dias = 2-day;
                                if (dias > 0){
                                    fec.setDate(fec.getDate() + dias);
                                }else if (dias < 0){
                                    fec.setDate(fec.getDate() + (7 + dias));
                                }else if( dias == 0){                                
                                    if(d.fecha_autorizacion == dateToDMY(fec)){
                                        fec.setDate(fec.getDate() + 7);
                                    }
                                }
                                return '<p style="font-size: .7em">Próxima revisión de Cuentas por Pagar</p>' + 
                                '<p style="font-size: .8em">'+dateToDMY(fec)+'</p>'
                            }
                            else{
                                if( d.idetapa > 9 && d.idetapa < 20 && d.idprovision == null && ( d.caja_chica == 0 || d.caja_chica == null ) && d.tipo_factura == 1 ){
                                    return '<p style="font-size: .7em">FACTURA PAGADA, EN ESPERA DE PROVISION</p>'; 
                                }else{
                                    return '<p style="font-size: .7em">'+d.etapa+'</p>';
                                }
                            }
                            break;
                    }
                }
            },
            {
                "orderable": false,
                "width": "8%",
                "data" : function( d ){
                    return '<p style="font-size: .8em">'+(d.caja_chica ? "CAJA CHICA" : "PAGO PROVEEDOR")+'</p>';

                }
            },
            {
                "orderable": false,
                "width": "8%",
                "data" : function( d ){
                    return '<p style="font-size: .8em"><small class="label pull-center bg-blue">'+d.metoPago+'</small></p>'
                }
            },
            {
                "orderable": false,
                "width": "15%",
                "data": function( d ){
                    
                    opciones = '<div>';
                    opciones += '<button type="button" class="btn btn-primary btn-sm consultar_modal notification" value="'+d.idsolicitud+'" data-value="SOL" title="Ver Solicitud"><i class="fas fa-eye"></i>'+(d.visto == 0 ? '<span class="badge">!</span>' : '')+'</button>';

                    return opciones + '</div>';
                }
            }],
            "columnDefs": [
                {
                    "orderable": false
                },
            ],
                "ajax": url + "Invitado/TablaInvitadoSolicitudesA"
            });

$('#historial_sol_activas tbody').on('click', 'td.details-control', function () {
    var tr = $(this).closest('tr');
    var row = tabla_activas.row( tr );
    
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
        '<td><strong>CAPTURISTA: </strong>'+row.data().nombre_completo+'</td>'+
        '</tr>'+
        '</table>';

        row.child( informacion_adicional ).show();
        tr.addClass('shown');
        $(this).parent().find('.animacion').removeClass("fa-caret-right").addClass("fa-caret-down");
    }
});

$('#fecInicial').change( function() { 
    tabla_activas.draw();
    var total = 0;
    var index = tabla_activas.rows( { selected: true, search: 'applied' } ).indexes();
    var data = tabla_activas.rows( index ).data();
    $.each(data, function(i, v){
        total += parseFloat(v.cantidad);
    });
    var to1 = formatMoney(total);
    document.getElementById("myText_1").value = to1;
});

$('#fecFinal').change( function() { 
    tabla_activas.draw();
    var total = 0;
    var index = tabla_activas.rows( { selected: true, search: 'applied' } ).indexes();
    var data = tabla_activas.rows( index ).data();
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

        var iStartDateCol = 6;
        var iEndDateCol = 6;

        var meses = ['','Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];
        
        var mes1 =  typeof aData[iStartDateCol] === 'undefined' ? '' : (meses.indexOf(aData[iStartDateCol].split('/')[1]).toString()).padStart(2, "0");
        var mes2 =  typeof aData[iEndDateCol] === 'undefined' ? '' : (meses.indexOf(aData[iEndDateCol].split('/')[1]).toString()).padStart(2, "0");

        iFini=iFini.substring(6,10) + iFini.substring(3,5) + iFini.substring(0,2);
        iFfin=iFfin.substring(6,10) + iFfin.substring(3,5) + iFfin.substring(0,2);
 
            var datofini=aData[iStartDateCol].substring(7,11) + mes1 + aData[iStartDateCol].substring(0,2);
            var datoffin=aData[iEndDateCol].substring(7,11) + mes2 + aData[iEndDateCol].substring(0,2);
 
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


    $( document ).on("click", ".cancela_solicitud", function(){
        index_cancelada = $(this).attr( "value" );
        $.post( url + "Opciones/cancelar_historial/"+index_cancelada).done(function () { 
             $("#modal_opciones").modal('toggle');
             tabla_activas.row( tr_global ).remove().draw();
        });
    });

    $( document ).on("click", ".pausa_solicitud", function(){
        index_pausada = $(this).attr( "value" );

        $.post( url + "Opciones/pausar_historial/"+index_pausada).done( function ( data ) { 

            data = JSON.parse( data );

            $("#modal_opciones").modal('toggle');
            if( data.resultado )
                tabla_activas.row( tr_global ).remove().draw();
            else
                alert("No se ha realizado el movimiento solicitado")
        });
    });

    //QUITAMOS LA PAUSA DE LA SOLCITUD
    $( document ).on("click", ".btn-quitarpausahistorial", function(){
        var tr = $(this).closest('tr');
        $.post( url + "Opciones/quitar_pausa_historial/"+$(this).attr( "value" )).done(function () {
            tabla_pausadas.row( tr ).remove().draw();
        });
    });

    $(window).resize(function(){
        tabla_activas.columns.adjust();
        tabla_canceladas.columns.adjust();
        tabla_pausadas.columns.adjust();
    });

    $( document ).on("click", ".btn-masopciones", function(){
        //PONER URGENTE
        index_solicitud = $(this).attr("value");
        index_saldo = $(this).attr( "data-value" );
        var tr = $(this).closest('tr');
        var row = tabla_activas.row( tr ).data();

        $("#modal_opciones .modal-header").html("");
        $("#modal_opciones .modal-body").html("");
        $("#modal_opciones .modal-footer").html("");
        

        $("#modal_opciones .modal-header").append('<button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="modal-title">SELECCIONE ACCIÓN</h4><br><select id="change_options" name="change_options" class="fa form-control"><option value="" class="fa form-control">-- Selecciona opción --</option><option value="1" class="fa form-control">&#xf071 PONER URGENTE</option><option value="4" class="fa form-control">&#xf15c TENDRÁ FACTURA (SI/NO)</option><option value="8" class="fa form-control">&#xf15c NOTA DE CRÉDITO</option><option value="9" class="fa form-control"><i class="fas fa-exchange-alt"></i> CAMBIAR PROVEEDOR</option><option value="3" class="fa form-control"><i class="fas fa-exchange-alt"></i> CAMBIAR EMPRESA</option><option value="7" class="fa form-control"><i class="fas fa-exchange-alt"></i> CAMBIAR CANTIDAD</option></select>');

        // <option value="5" class="fa form-control">&#xf1c3 LIBERAR FACTURA XML</option>

        $('#change_options').on('change',function(){
            cuenta = $(this).val();
            
            $("#modal_opciones .modal-body").html("");
            $("#modal_opciones .modal-footer").html("");
            
            switch(cuenta){
                case '1':
                    //URGENTE
                    $("#modal_opciones .modal-body").html("");
                    $("#modal_opciones .modal-footer").html("");
                    $("#modal_opciones .modal-body").append("<center><img style='width: 20%; height: 20%;' src= '<?= base_url('img/alert.png')?>'></center>");
                    $("#modal_opciones .modal-footer").append("<center><button type='button' style:margin-right: 50px;' class='btn bg-teal urgente' value="+index_solicitud+">ACEPTAR</button><button type='button' style:'margin-right: 50px;' class='btn btn-danger' onClick='cancelar_opcion()'>CANCELAR</button></center>");
                    break;
                case '3':
                    cambiar_empresa( tr );
                    break;
                case '4':
                    //TENDRA FACTURA
                    $("#modal_opciones .modal-body").html("");
                    $("#modal_opciones .modal-footer").html("");
                    $("#modal_opciones .modal-footer").append("<center><button type='button' style:margin-right: 50px;' class='btn bg-olive tendra_factura' value="+index_solicitud+">TENDRÁ</button><button type='button' style:margin-right: 50px;' class='btn bg-orange no_tendra' value="+index_solicitud+">NO TENDRÁ</button><button type='button' style:'margin-right: 50px;' class='btn btn-danger' onClick='cancelar_opcion()'>CANCELAR</button></center>");
                    break;
                case '7':
                    //cambiar_monto_registro( tr );
                    break;
                case '8':
                    $("#modal_opciones .modal-body").html('<form id="complemento_facturas"><div class="col-lg-12 form-group"><label>DOCUMENTO XML</label><div class="row"><div class="col-lg-12"><div class="input-group"><input type="file" class="form-control" id="complemento" accept="text/xml" required><div class="input-group-btn"><button type="submit" class="btn btn-danger cargar_complemento"><i class="fas fa-upload"></i></button></div></div></div></div></div></form>');
                    
                    $("#complemento_facturas").submit( function(e) {
                        e.preventDefault();
                    }).validate({
                        submitHandler: function( form ) {

                            var data = new FormData();
                            data.append("id", index_solicitud);
                            data.append("xmlfile", $("#complemento")[0].files[0]);

                            $.ajax({
                                url: url + "Complementos_cxp/notas_credito",
                                data: data,
                                cache: false,
                                contentType: false,
                                processData: false,
                                dataType: 'json',
                                method: 'POST',
                                type: 'POST', // For jQuery < 1.9
                                success: function(data){
                                    if( data.respuesta[0] ){
                                        $("#modal_opciones").modal( 'toggle');
                                        row.cantidad = parseFloat( row.cantidad ) - parseFloat( data.monto_aplicado );
                                        tabla_activas.row( tr ).data( row ).draw();
                                    }else{
                                        alert( data.respuesta[1] );
                                    }
                                },error: function( ){
                                    
                                }
                            });
                        }
                    });
                    break;
                case '9':
                    cambiar_proveedor( tr );
                    break;
                default:
                    $("#modal_opciones .modal-body").html("");
                    $("#modal_opciones .modal-footer").html("");
                    break;

            }
        });

        
        $("#modal_opciones").modal();

    });

    $( document ).on("click", ".btn-masopcionessegundo", function(){
    
        index_solicitud = $(this).attr("value");
        index_saldo = $(this).attr( "data-value" );
        var tr = $(this).closest('tr');
        var row = tabla_activas.row( tr ).data();
        tr_global = tr;

        $("#modal_opciones .modal-header").html("");
        $("#modal_opciones .modal-body").html("");
        $("#modal_opciones .modal-footer").html("");
    
        $("#modal_opciones .modal-header").append('<button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="modal-title">SELECCIONE ACCIÓN</h4><br><select id="change_options" name="change_options" class="fa form-control"><option value="" class="fa form-control">-- Selecciona opción --</option><option value="1" class="fa form-control">&#xf2ed AGREGAR REF. BANCARIA</option><option value="2" class="fa form-control">&#xf155 AÑADIR ABONO</option><option value="4" class="fa form-control">&#xf15c TENDRÁ FACTURA (SI/NO)</option><option value="5" class="fa form-control">&#xf00d CANCELAR SOLICITUD</option><option value="6" class="fa form-control">&#xf017 PAUSAR SOLICITUD</option><option value="8" class="fa form-control">&#xf15c NOTA DE CRÉDITO</option><option value="9" class="fa form-control"><i class="fas fa-exchange-alt"></i> CAMBIAR PROVEEDOR</option></select>');
        // <option value="5" class="fa form-control">&#xf1c3 LIBERAR FACTURA XML</option><option value="3" class="fa form-control">&#xf249 AGREGAR NOTA CRÉDITO</option>

        $('#change_options').on('change',function(){
            cuenta = $(this).val();

            switch(cuenta){
                case '1':
                    //REFERENCIA BANCARIA
                    $("#modal_opciones .modal-body").html("");
                    $("#modal_opciones .modal-footer").html("");
                    $("#modal_opciones .modal-body").append("<center><input type='text' name='valor_referencia' id='valor_referencia' class='form-control' placeholder='Digite referencia bancaria' required></center>");
                    $("#modal_opciones .modal-footer").append("<center><button type='button' style:margin-right: 50px;' class='btn bg-teal add_referencia' value="+index_solicitud+">CAMBIAR</button><button type='button' style:'margin-right: 50px;' class='btn btn-danger' onClick='cancelar_opcion()'>CANCELAR</button></center>");
                    break;
                case '2':
                    //AÑANIR ABONO
                    $("#modal_opciones .modal-body").html("");
                    $("#modal_opciones .modal-footer").html("");
                    $("#modal_opciones .modal-body").append('<div class="row"><div class="col-lg-12"><b>Cantidad restante: </b>$ '+index_saldo+'</div></div><br>');
                    $("#modal_opciones .modal-body").append('<div class="row"><div class="col-lg-12"><b>Cantidad a abonar:</b><input type="number" class="form-control  " placeholder="$" name="cantidad_abonada" id="cantidad_abonada" required></input></div></div>');
                    $("#modal_opciones .modal-body").append('<div class="row"><div class="col-lg-12"><b>Justificación:</b><textarea class="form-control" name="razon_abono" id="razon_abono" required onKeyPress="if(this.value.length==255) return false;return check(event);"></textarea></div></div>');
                    $("#modal_opciones .modal-footer").append('<div class="btn-group-vertical"><button class="btn btn-success "onclick="validar_abono_ok('+index_solicitud+','+index_saldo+')">ACEPTAR</button><button type="button" class="btn btn-danger" onclick="cancelar_opcion()">CANCELAR</button></div>');
                    break;
                case '3':
                    cambiar_empresa( tr );
                    break;
                case '4':
                    //TENDRA FACTURA
                    $("#modal_opciones .modal-body").html("");
                    $("#modal_opciones .modal-footer").html("");
                    $("#modal_opciones .modal-footer").append("<center><button type='button' style:margin-right: 50px;' class='btn bg-olive tendra_factura' value="+index_solicitud+">TENDRÁ</button><button type='button' style:margin-right: 50px;' class='btn bg-orange no_tendra' value="+index_solicitud+">NO TENDRÁ</button><button type='button' style:'margin-right: 50px;' class='btn btn-danger' ='cancelar_opcion()'>CANCELAR</button></center>");
                    break;
                case '5':
                    //CANCELAR LA SOLICTUD
                    $("#modal_opciones .modal-body").html("");
                    $("#modal_opciones .modal-footer").html("");
                    $("#modal_opciones .modal-footer").append("<center><button type='button' style:margin-right: 50px;' class='btn bg-olive cancela_solicitud' value="+index_solicitud+">CANCELAR</button><button type='button' style:'margin-right: 50px;' class='btn btn-danger' onClick='cancelar_opcion()'>CANCELAR</button></center>");
                    break;
                case '7':
                    //cambiar_monto_registro( tr );
                    break;
                case '6':
                    //PAUSAR SOLICITUD
                    $("#modal_opciones .modal-body").html("");
                    $("#modal_opciones .modal-footer").html("");
                    $("#modal_opciones .modal-footer").append("<center><button type='button' style:margin-right: 50px;' class='btn bg-olive pausa_solicitud' value="+index_solicitud+">PAUSAR</button><button type='button' style:'margin-right: 50px;' class='btn btn-danger' onClick='cancelar_opcion()'>CANCELAR</button></center>");                    
                    break;
                case '9':
                    cambiar_proveedor( tr );
                    break;
                case '8':
                    $("#modal_opciones .modal-body").html('<form id="complemento_facturas"><div class="col-lg-12 form-group"><label>DOCUMENTO XML</label><div class="row"><div class="col-lg-12"><div class="input-group"><input type="file" class="form-control" id="complemento" accept="text/xml" required><div class="input-group-btn"><button type="submit" class="btn btn-danger cargar_complemento"><i class="fas fa-upload"></i></button></div></div></div></div></div></form>');
                    
                    $("#complemento_facturas").submit( function(e) {
                        e.preventDefault();
                    }).validate({
                        submitHandler: function( form ) {

                            var data = new FormData();
                            data.append("id", index_solicitud);
                            data.append("xmlfile", $("#complemento")[0].files[0]);

                            $.ajax({
                                url: url + "Complementos_cxp/notas_credito",
                                data: data,
                                cache: false,
                                contentType: false,
                                processData: false,
                                dataType: 'json',
                                method: 'POST',
                                type: 'POST', // For jQuery < 1.9
                                success: function(data){
                                    if( data.respuesta[0] ){
                                        $("#modal_opciones").modal( 'toggle');
                                        row.cantidad = parseFloat( row.cantidad ) - parseFloat( data.monto_aplicado );
                                        console.log( row );
                                        tabla_activas.row( tr ).data( row ).draw();
                                    }else{
                                        alert( data.respuesta[1] );
                                    }
                                },error: function( ){
                                    
                                }
                            });
                        }
                    });
                    break;
                default:
                    $("#modal_opciones .modal-body").html("");
                    $("#modal_opciones .modal-footer").html("");
                    break;

            }
        });
    
        $("#modal_opciones").modal();

    });

    $( document ).on("click", ".btn-masopcionestercero", function(){
    
        index_solicitud = $(this).attr("value");
        index_saldo = $(this).attr( "data-value" );

        var tr = $(this).closest('tr');
        var row = tabla_activas.row( tr ).data();

        $("#modal_opciones .modal-header").html("");
        $("#modal_opciones .modal-body").html("");
        $("#modal_opciones .modal-footer").html("");
        
        $("#modal_opciones .modal-header").append('<button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="modal-title">SELECCIONE ACCIÓN</h4><br><select id="change_options" name="change_options" class="fa form-control"><option value="" class="fa form-control">-- Selecciona opción --</option><option value="1" class="fa form-control">&#xf2ed AGREGAR REF. BANCARIA</option><option value="4" class="fa form-control">&#xf15c TENDRÁ FACTURA (SI/NO)</option><option value="8" class="fa form-control">&#xf15c NOTA DE CRÉDITO</option><option value="9" class="fa form-control"><i class="fas fa-exchange-alt"></i> CAMBIAR PROVEEDOR</option><option value="3" class="fa form-control"><i class="fas fa-exchange-alt"></i> CAMBIAR EMPRESA</option><option value="7" class="fa form-control"><i class="fas fa-exchange-alt"></i> CAMBIAR CANTIDAD</option></select>');

        $('#change_options').on('change',function(){
            cuenta = $(this).val();
            // console.log(cuenta);

            switch(cuenta){
                case '1':
                    //REFERENCIA BANCARIA
                    $("#modal_opciones .modal-body").html("");
                    $("#modal_opciones .modal-footer").html("");
                    $("#modal_opciones .modal-body").append("<center><input type='text' name='valor_referencia' id='valor_referencia' class='form-control' placeholder='Digite referencia bancaria' required></center>");
                    $("#modal_opciones .modal-footer").append("<center><button type='button' style:margin-right: 50px;' class='btn bg-teal add_referencia' value="+index_solicitud+">CAMBIAR</button><button type='button' style:'margin-right: 50px;' class='btn btn-danger' onClick='cancelar_opcion()'>CANCELAR</button></center>");
                    break;
                case '3':
                    cambiar_empresa( tr );
                    break;
                case '4':
                    //TENDRA FACTURA
                    $("#modal_opciones .modal-body").html("");
                    $("#modal_opciones .modal-footer").html("");
                    $("#modal_opciones .modal-footer").append("<center><button type='button' style:margin-right: 50px;' class='btn bg-olive tendra_factura' value="+index_solicitud+">TENDRÁ</button><button type='button' style:margin-right: 50px;' class='btn bg-orange no_tendra' value="+index_solicitud+">NO TENDRÁ</button><button type='button' style:'margin-right: 50px;' class='btn btn-danger' onClick='cancelar_opcion()'>CANCELAR</button></center>");
                    break;
                case '7':
                    //cambiar_monto_registro( tr );
                    break;
                case '9':
                    cambiar_proveedor( tr );
                    break;
                case '8':
                    $("#modal_opciones .modal-body").html('<form id="complemento_facturas"><div class="col-lg-12 form-group"><label>DOCUMENTO XML</label><div class="row"><div class="col-lg-12"><div class="input-group"><input type="file" class="form-control" id="complemento" accept="text/xml" required><div class="input-group-btn"><button type="submit" class="btn btn-danger cargar_complemento"><i class="fas fa-upload"></i></button></div></div></div></div></div></form>');
                    
                    $("#complemento_facturas").submit( function(e) {
                        e.preventDefault();
                    }).validate({
                        submitHandler: function( form ) {

                            var data = new FormData();
                            data.append("id", index_solicitud);
                            data.append("xmlfile", $("#complemento")[0].files[0]);

                            $.ajax({
                                url: url + "Complementos_cxp/notas_credito",
                                data: data,
                                cache: false,
                                contentType: false,
                                processData: false,
                                dataType: 'json',
                                method: 'POST',
                                type: 'POST', // For jQuery < 1.9
                                success: function(data){
                                    if( data.respuesta[0] ){
                                        $("#modal_opciones").modal( 'toggle');
                                        row.cantidad = parseFloat( row.cantidad ) - parseFloat( data.monto_aplicado );
                                        tabla_activas.row( tr ).data( row ).draw();
                                    }else{
                                        alert( data.respuesta[1] );
                                    }
                                },error: function( ){
                                    
                                }
                            });
                        }
                    });
                    break;          
                default:
                    $("#modal_opciones .modal-body").html("");
                    $("#modal_opciones .modal-footer").html("");
                    break;

            }
        });
        $("#modal_opciones").modal();

    });

    $( document ).on("click", ".btn-caja_chica", function(){
    
        index_solicitud = $(this).attr("value");
        index_saldo = $(this).attr( "data-value" );

        var tr = $(this).closest('tr');
        var row = tabla_activas.row( tr ).data();

        $("#modal_opciones .modal-header").html("");
        $("#modal_opciones .modal-body").html("");
        $("#modal_opciones .modal-footer").html("");
        
        var opciones_disponibles = "<button type='button' class='close' data-dismiss='modal'>&times;</button>" +
        "<h4 class='modal-title'>SELECCIONE ACCIÓN</h4>"+
        "<br>"+
        "<select id='change_options' name='change_options' class='fa form-control'>"+
        "<option value='' class='fa form-control'>-- Selecciona opción --</option>"+
        "<option value='3' class='fa form-control'><i class='fas fa-exchange-alt'></i> CAMBIAR EMPRESA</option>";

        if( row.idetapa != "11" && row.idetapa != "31" )
            opciones_disponibles += "<option value='7' class='fa form-control'><i class='fas fa-exchange-alt'></i> CAMBIAR CANTIDAD</option>";

        opciones_disponibles += "<option value='9' class='fa form-control'><i class='fas fa-exchange-alt'></i> CAMBIAR PROVEEDOR</option>"+
        "</select>";

        $("#modal_opciones .modal-header").append( opciones_disponibles );

        $('#change_options').on('change',function(){
            switch( $(this).val() ){
                case '3':
                    cambiar_empresa( tr );
                    break;
                case '7':
                    //cambiar_monto_registro( tr );
                    break;
                case '9':
                    cambiar_proveedor( tr );
                    break;         
                default:
                    $("#modal_opciones .modal-body").html("");
                    $("#modal_opciones .modal-footer").html("");
                    break;
            }
        });
        $("#modal_opciones").modal();

    });


    $(document).on("click", ".urgente", function(){
        index_solicitud = $(this).attr("value");

        $.post( url + "Opciones/poner_urgente/"+index_solicitud).done( function(){
            $("#modal_opciones").modal('toggle');
        });
    });

$(document).on("click", ".tendra_factura", function(){
  index_solicitud = $(this).attr("value");

$.post( url + "Opciones/tendra_factura/"+index_solicitud).done( function(){
       $("#modal_opciones").modal('toggle');

    });

 });


$(document).on("click", ".no_tendra", function(){
  index_solicitud = $(this).attr("value");

$.post( url + "Opciones/no_tendra/"+index_solicitud).done( function(){
       $("#modal_opciones").modal('toggle');

    });

 });




$(document).on("click", ".add_referencia", function(){
  index_solicitud = $(this).attr("value");
  index_referencia = document.getElementById("valor_referencia").value;

  if(index_referencia==null||index_referencia==''){
       alert("Ingrese referencia");

  }
  else{

     $.post( url + "Opciones/agregar_referencia/"+index_solicitud+'/'+index_referencia).done( function(){
       $("#modal_opciones").modal('toggle');

    });

  }



 });





 function validar_abono_ok($index_solicitud, $index_saldo){
 
var saldo_actual = document.getElementById("cantidad_abonada").value;
var datos_justificacion = document.getElementById("razon_abono").value;

 
total = parseFloat(index_saldo).toFixed(3);

 
 if(parseFloat(saldo_actual)>total){
     alert("La cantidad excede $ "+formatMoney($index_saldo));
    document.getElementById("cantidad_abonada").value="";
 }
 else{

  if(saldo_actual!=""&&datos_justificacion!=""){

    $.get(url+"Historial/agregar_abono/"+saldo_actual+"/"+datos_justificacion+"/"+index_solicitud).done(function () { 
                // $("#modal_abono").modal('toggle'); 
                 $("#modal_opciones").modal('toggle'); 
                
                tabla_activas.ajax.reload();
                 alert("Se abonarón $ "+formatMoney(saldo_actual));
                 document.getElementById("cantidad_abonada").value="";
            });
 }
 else{
 alert("Llene todos los campos");
  }


 }
}
 



 function check(e) {
    tecla = (document.all) ? e.keyCode : e.which;
    if (tecla == 8) {
        return true;
    }
    else{
        patron = /[A-Za-z0-9 ]/;

        tecla_final = String.fromCharCode(tecla);
        return patron.test(tecla_final);
    }}

    //CAMBIAR PROVEEDOR DE LA SOLICITUD REGISTRADA
    function cambiar_proveedor( tr ){
        $("#modal_opciones .modal-body").html('<form id="cambio_proveedor"><div class="col-lg-12 form-group"><label>PROVEEDOR</label><div class="row"><div class="col-lg-12"><select id="idproveedor" name="idproveedor" required></select><br/><button type="submit" class="btn btn-block btn-danger">CAMBIAR</button></div></div></div></form>');
                        
        $.get(  url + "Listas_select/Allproveedores/" ).done( function( data ){
            
            proveedores = JSON.parse( data );

            $("#idproveedor").html( "" ).append('<option value="">Seleccione una opción</option>');
            $.each( proveedores, function( i, v){
                $("#idproveedor").append('<option value="'+v.idproveedor+'">'+v.nombre+' - '+v.alias+'</option>');
            });

            $("#idproveedor").next().show();

            $('#idproveedor').select2({
                width: '100%'
            });

        });

        $("#cambio_proveedor").submit( function(e) {
            e.preventDefault();
        }).validate({
            submitHandler: function( form ) {

                var data = new FormData();
                data.append( "idsolicitud", index_solicitud );
                data.append( "idproveedor", $("#cambio_proveedor #idproveedor").val() );

                $.ajax({
                    url: url + "Cuentasxp/cambiar_proveedor",
                    data: data,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    method: 'POST',
                    type: 'POST', // For jQuery < 1.9
                    success: function( data ){
                        if( data.resultado ){
                            $("#modal_opciones").modal( 'toggle');
                            tabla_activas.row( tr ).data( data.solicitud ).draw();
                        }else{
                            alert( "No se pudo procesar su solicitud." );
                        }
                    },error: function( ){
                        
                    }
                });
            }
        });
    }

    //CAMBIAR EMPRESA DE LA SOLICITUD
    function cambiar_empresa( tr ){
        $("#modal_opciones .modal-body").html('<form id="cambio_empresa"><div class="col-lg-12 form-group"><label>EMPRESA</label><div class="row"><div class="col-lg-12"><select id="idempresa" name="idempresa" required></select><br/><button type="submit" class="btn btn-block btn-danger">CAMBIAR</button></div></div></div></form>');
                        
        $.get(  url + "Listas_select/lista_empresas/" ).done( function( data ){
            
            proveedores = JSON.parse( data );

            $("#idempresa").html( "" ).append('<option value="">Seleccione una opción</option>');
            $.each( proveedores, function( i, v){
                $("#idempresa").append('<option value="'+v.idempresa+'">'+v.nombre+'</option>');
            });

            $("#idempresa").next().show();

            $('#idempresa').select2({
                width: '100%'
            });

        });

        $("#cambio_empresa").submit( function(e) {
            e.preventDefault();
        }).validate({
            submitHandler: function( form ) {

                var data = new FormData();
                data.append( "idsolicitud", index_solicitud );
                data.append( "idempresa", $("#cambio_empresa #idempresa").val() );

                $.ajax({
                    url: url + "Opciones/cambiar_empresa",
                    data: data,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    method: 'POST',
                    type: 'POST', // For jQuery < 1.9
                    success: function( data ){
                        if( data.resultado ){
                            $("#modal_opciones").modal( 'toggle');
                            tabla_activas.row( tr ).data( data.solicitud ).draw();
                        }else{
                            alert( "No se pudo procesar su solicitud." );
                        }
                    },error: function( ){
                        
                    }
                });
            }
        });
    }

    //CAMBIAR LA CANTIDAD DEL REGISTRO
    function cambiar_monto_registr( tr ){
        $("#modal_opciones .modal-body").html('<form id="cambiar_monto"><div class="col-lg-12 form-group"><label>NUEVO MONTO</label><div class="row"><div class="col-lg-12"><input id="nmonto" name="nmonto" tipe="number" class="form-control" required><br/><button type="submit" class="btn btn-block btn-danger">CAMBIAR</button></div></div></div></form>');

        $("#cambiar_monto").submit( function(e) {
            e.preventDefault();
        }).validate({
            submitHandler: function( form ) {

                var data = new FormData();
                data.append( "idsolicitud", index_solicitud );
                data.append( "nmonto", $("#cambiar_monto #nmonto").val() );

                $.ajax({
                    url: url + "Opciones/cambiar_monto",
                    data: data,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    method: 'POST',
                    type: 'POST', // For jQuery < 1.9
                    success: function( data ){
                        if( data.resultado ){
                            $("#modal_opciones").modal( 'toggle');
                            tabla_activas.row( tr ).data( data.solicitud ).draw();
                        }else{
                            alert( "No se pudo procesar su solicitud." );
                        }
                    },error: function( ){
                        
                    }
                });
            }
        });
    }

    function cancelar_opcion() {
        $("#modal_opciones .modal-body").html("");
        $("#modal_opciones .modal-footer").html("");
        $("#modal_opciones").modal('toggle');
    }
 
</script>

<?php
require("footer.php");
?>