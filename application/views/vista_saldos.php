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
                        <h3>CONTROL DE INGRESOS / EGRESOS EN EFECTIVO</h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <h4><div id="myText_1" class="col-md-12"></div></h4>
                            </div>
                        </div>
                        <hr/>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="col-lg-2">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fas fa-calendar-alt"></i> <b>INICIO</b></span>
                                        <input class="form-control fechas_filtro from" type="text" id="datepicker_from" maxlength="10"/>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fas fa-calendar-alt"></i> <b>FIN</b></span>
                                        <input class="form-control fechas_filtro to" type="text" id="datepicker_to" maxlength="10"/>
                                    </div>
                                </div>
                                <table class="table table-striped" id="tabla_autorizaciones">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th></th>
                                            <th style="font-size: .8em">CANTIDAD</th>
                                            <th style="font-size: .8em">EMPRESA</th>
                                            <th style="font-size: .8em">PROYECTO</th>
                                            <th style="font-size: .8em">FECHA MOVIMIENTO</th>
                                            <th style="font-size: .8em">USUARIO</th>
                                            <th style="font-size: .8em">FECHA REGISTRO</th>
                                            <th style="font-size: .8em">TIPO MOVIMIENTO</th>
                                            <th style="font-size: .8em">ACCIONES</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div><!--End tab content-->
                </div><!--end box-->
            </div>
        </div>
    </div>
</div>
 
<div class="modal fade modal-alertas" id="modal_tipo_cambio" role="dialog">
    <div class="modal-dialog">
       <div class="modal-content">
        <div class="modal-header bg-orange">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">MODIFICAR MOVIMIENTO</h4>
        </div>  
        <form method="post" id="form_cambio">
            <div class="modal-body"></div>
        </form>
    </div>
</div>
</div>



<div class="modal fade modal-alertas" id="modal_tipo_log" role="dialog">
    <div class="modal-dialog modal-lg">
       <div class="modal-content">
        <div class="modal-header bg-blue">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">VER MOVIMIENTOS</h4>
        </div>  
             <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div id="chat" class="form-control chat">
                            
                        </div>
                    </div>
                </div>
             </div>
     </div>
</div>
</div>

<div id="modal_solicitud_programado" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="exampleModalLabel">NUEVO REGISTRO</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12"> 
                        <form  id="frmnewsolp" method="post" action="#">       
                            <div class="row">
                                <div class="col-lg-4 form-group">
                                    <label for="proyecto">CANTIDAD</label>
                                    <input type="number" class="form-control" id="cantidad_saldo" name="cantidad_saldo" required>
                                </div>
                                <div class="col-lg-4 form-group">
                                    <label for="total">TIPO DE MOVIMIENTO</label>
                                    <select class="form-control" id="movimiento_saldo" name="movimiento_saldo" required>
                                        <option value="" data-value="">Seleccione un opción</option>
                                        <option value="1" data-value="INGRESO">INGRESO</option>
                                        <option value="0" data-value="EGRESO">EGRESO</option>            
                                    </select>
                                </div>

                                <div class="col-lg-4 form-group">
                                    <label for="fecha">FECHA MOVIMIENTO</label>
                                    <input type="date" class="form-control" min='2019-04-01' id="fecha_saldo" name="fecha_saldo" placeholder="Fecha" value="" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 form-group">
                                    <label for="empresa">EMPRESA</label>
                                    <select name="empresa" id="empresapr" class="form-control lista_empresa" required></select>
                                </div>
                                <div class="col-lg-6 form-group">
                                    <label for="proyecto">PROYECTO</label>
                                    <select name="proyecto" id="proyectopr" class="form-control lista_proyecto" required></select>
                                </div>

                            </div>
                            <div class="row">

                                <div class="col-lg-12 form-group">
                                    <label for="solobs">CONCEPTO <i class="far fa-question-circle faq" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover"  data-placement="right"></i></label>
                                    <textarea class="form-control" id="concepto" name="concepto" placeholder="Observaciones del movimiento" required></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 form-group">
                                    <button type="submit" class="btn btn-success btn-block">GUARDAR</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
 
<script>

    var link_post = "";
    var empresas = "";
     var proyectos = "";

    $('.fechas_filtro').datepicker({
          format: 'dd/mm/yyyy'
    });
    
    $(document).ready( function(){
        $.getJSON( url + "Listas_select/lista_proveedores_libres" ).done( function( data ){
            empresas = data.empresas;
            $(".lista_empresa").html('');
            $(".lista_empresa").append('<option value="">Seleccione una opción</option>');
            $(".lista_empresa").append('<option value="NULL">SIN DEFINIR</option>');
            $.each( data.empresas, function( i, v){
                $(".lista_empresa").append('<option value="'+v.idempresa+'" data-value="'+v.rfc+'">'+v.nombre+'</option>');
            });       
        });
    });



    $(document).ready( function(){
        $.getJSON( url + "Listas_select/lista_proveedores_libres" ).done( function( data ){
            proyectos = data.lista_proyectos_depto;
            $(".lista_proyecto").html('');
            $(".lista_proyecto").append('<option value="">Seleccione una opción</option>');
            $(".lista_proyecto").append('<option value="NULL">SIN DEFINIR</option>');
            $.each( data.lista_proyectos_depto, function( i, v){
                $(".lista_proyecto").append('<option value="'+v.idproyecto+'">'+v.concepto+'</option>');
            });       
        });
    });



    $("#frmnewsolp").submit( function(e) {
        e.preventDefault();
    }).validate({
        submitHandler: function( form ) {
            var data = new FormData( $(form)[0] );
 
            $.ajax({
                url: url + link_post,
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'JSON',
                method: 'POST',
                type: 'POST', // For jQuery < 1.9
                success: function(data){
                    if( data.resultado ){
                        $("#modal_solicitud_programado").modal( 'toggle' );

                        table_autorizar.clear();
                        table_autorizar.rows.add( data.data );
                        table_autorizar.draw();

                        $("#myText_1").html("");
            
                        var total = 0;
                        if( data.saldos.length ){
                            $("#myText_1").append("<div class='col-md-12'><label>SALDO GENERAL: </label>&nbsp;<input type='text' disabled readonly style='border:none; background:white; ' value='"+'$'+formatMoney(data.saldos[0].general)+"'></div><br>");

                            // $("#myText_1").append("<hr/><h4>SALDO POR EMPRESA:</h4>");

                            // $.each( json.saldos,  function(i, v){
                            //     $("#myText_1").append("<div class='col-md-2 text-center'><h5><b>"+v.abrev+"</b><span style='"+(v.suma<=0 ? "color:red" : "" )+"'> $ "+formatMoney(v.suma)+"</span></h5></div>");
                            // });
                        }

                    }else{
                        alert("NO SE HA PODIDO COMPLETAR LA SOLICITUD");
                    }
                },error: function( ){
                            
                }
            });
        }
    });


 

     function resear_formulario_programado(){
        $("#modal_solicitud_programado input.form-control").prop("readonly", false).val("");
        $("#modal_solicitud_programado textarea").html('');

        $("#modal_solicitud_programado #solobspr").val('');
 
        $("#modal_solicitud_programado textarea").prop("readonly", false);
        $("#empresapr, #proveedor_programado, #xmlfile").prop('disabled', false);
        $("#empresapr option, #proveedor_programado option, #forma_pagopr option, #proyectopr option, #homoclave option").prop("selected", false);
        $("#modal_formulario_solicitud .form-control, #modal_formulario_solicitud input[type='checkbox']").prop("checked", false).prop('disabled', false);
        $("input[type='hidden']").val("").prop('disabled', true);
        $(".programar_fecha").prop('disabled', true)
        
        $("#modal_solicitud_programado #concepto").val('');

        $("#modal_solicitud_programado #movimiento_saldo").val('');
        $("#modal_solicitud_programado #obse").prop("readonly", true).val('');
        $('.default').prop('checked', true);
        $("input[type=radio][name=servicio1][value=0]").prop('checked', true );
        $("input[type=radio][name=servicio1]").prop('disabled', false );

        $("#idproveedor").val('');

        var validator = $( "#frmnewsolp" ).validate();
        validator.resetForm();
        $( "#frmnewsolp div" ).removeClass("has-error");
      
    }

    $(document).on( "click", ".abrir_nueva_solicitud_programada", function(){
        resear_formulario_programado();
        link_post = "Saldos/guardar_solicitud";
        $("#modal_solicitud_programado").modal( {backdrop: 'static', keyboard: false} );
    });

    var table_autorizar;

    $("#tabla_autorizaciones").ready( function () {

        $('#tabla_autorizaciones').on('xhr.dt', function (e, settings, json, xhr) {
            $("#myText_1").html("");
            
            var total = 0;
            if(json.saldos.length ){
                    $("#myText_1").append("<div class='col-md-12'><label>SALDO GENERAL: </label>&nbsp;<input type='text' disabled readonly style='border:none; background:white; ' value='"+'$'+formatMoney(json.saldos[0].general)+"'></div><br>");
 
            } 
        });

        $('#tabla_autorizaciones thead tr:eq(0) th').each( function (i) {
            if( i != 0 && i !=8){
                var title = $(this).text();
                $(this).html( '<input type="text" style="font-size: .9em; width: 100%;" placeholder="'+title+'" />' );
        
                $( 'input', this ).on( 'keyup change', function () {
                    if (table_autorizar.column(i).search() !== this.value ) {
                        table_autorizar
                            .column(i)
                            .search( this.value)
                            .draw();
                           var total = 0;
                           var index = table_autorizar.rows( { selected: true, search: 'applied' } ).indexes();
                           var data = table_autorizar.rows( index ).data();
                           $.each(data, function(i, v){
                               total += parseFloat(v.cantidad);
                           });
                           var to1 = formatMoney(total);
                           $("#myText_1").html( "$ " + to1 );
                    }
                } );
            }
        });

        table_autorizar = $('#tabla_autorizaciones').DataTable({
            dom: 'Brtip',
            buttons: [
                {
                    text: '<i class="fas fa-plus"></i> NUEVO MOVIMIENTO',
                    attr: {
                        class: 'btn bg-blue abrir_nueva_solicitud_programada'
                    }
                },
                {
                    extend: 'excelHtml5',             
                    text: '<i class="fas fa-file-excel"></i> EXPORTAR EXCEL',
                    messageTop: "Lista de pagos autorizados por Dirección General (CHEQUES)",
                    attr: {
                        class: 'btn btn-success'       
                    },
                    exportOptions: {
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
            //"stateSave": true,
            "language" : lenguaje,
            "processing": false,
            "pageLength": 10,
            "bAutoWidth": false,
            "bLengthChange": false,
            "bInfo": false,
            "scrollX": true,
            "ordering": false,
            "columns": [
                {
                    "className": 'details-control',
                    "orderable": false,
                    "data" : null,
                    "defaultContent": '<i class="fas animacion fa-caret-right"></i>'
                },
 
                { 
                    "width": "12%",
                     "orderable": false,
                    "data" : function( d ){
                        return '<p style="font-size: .8em">$ '+formatMoney(d.cantidad)+'</p>'
                    }
                },
                 {
                    "width": "12%",
                     "orderable": false,
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.abrev+'</p>'
                    }
                },
                {
                    "width": "12%",
                     "orderable": false,
                    "data" : function( d ){
                        if(d.con_proy==null||d.con_proy=='null'||d.con_proy==''||d.con_proy==0){

                            return "<p style='font-size: .8em'>SIN DEFINIR</p>";

                        }
                        else
                        {
                            
                             return '<p style="font-size: .8em">'+d.con_proy+'</p>'
                        }
                       
                    }
                },
                {
                    "width": "12%",
                     "orderable": false,
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+formato_fechaymd(d.fecha_saldo)+'</p>'
                    }
                },
                {
                    "width": "12%",
                     "orderable": false,
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.nomuser+'</p>'
                    }
                },
                {
                    "width": "12%",
                     "orderable": false,
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+formato_fechaymd(d.fechacreacion)+'</p>'
                    }
                },

                {
                    "width": "10%",
                    "orderable": false,
                    "data" : function( d ){

                        if(d.tipo_saldo!='0'){
                             return "<center><small class='label pull-center bg-green'>ENTRADA</small></center>";
                        }else{
                            return "<center><small class='label pull-center bg-red'>SALIDA</small></center>";
                        }
 
                         
                    }
                },

                { 
                    "orderable": false,
                    "data": function( data ){
                        opciones = '<center><div class="btn-group-vertical" role="group">';
                        opciones += '<button type="button" style="margin-right: 5px;" class="btn btn-sm cargar_cambio bg-orange" value="'+data.id_saldo+'" title="Editar movimiento"><i class="fas fa-edit"></i></button>';
                        opciones += '<button type="button" style="margin-right: 5px;" class="btn btn-sm cargar_logs bg-blue" value="'+data.id_saldo+'" title="Ver movimientos"><i class="fas fa-eye"></i></button>';
                        return opciones + '</div></center>';
                    } 
                }  
           ],
            "columnDefs": [ {
                "orderable": false, "targets": 0
            }],
            "ajax":  url + "Saldos/ver_datos"
        });
        
        $("#tabla_autorizaciones").DataTable().rows().every( function () {
            var tr = $(this.node());
            this.child(format(tr.data('child-value'))).show();
            tr.addClass('shown');
        });
        
 

        $('#tabla_autorizaciones tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = table_autorizar.row( tr );
    
            if ( row.child.isShown() ) {
                row.child.hide();
                tr.removeClass('shown');
                $(this).parent().find('.animacion').removeClass("fa-caret-down").addClass("fa-caret-right");
            }
            else {

                var informacion_adicional = '<table class="table text-justify">'+
                    '<tr>'+
                        '<td><strong>CONCEPTO: </strong>'+row.data().concepto+'</td>'+
                    '</tr>'+
                '</table>';

                row.child( informacion_adicional ).show();
                tr.addClass('shown');
                $(this).parent().find('.animacion').removeClass("fa-caret-right").addClass("fa-caret-down");
            }
        });



        $("#tabla_autorizaciones tbody").on("click", ".cargar_cambio", function(){

            var tr = $(this).closest('tr');
            var row = table_autorizar.row( tr );
            id_saldo = $(this).val();
            $("#modal_tipo_cambio .modal-body").html("");

            $("#modal_tipo_cambio .modal-body").append('<input type="hidden" name="id_saldo" value="'+row.data().id_saldo+'">');

            $("#modal_tipo_cambio .modal-body").append('<div class="row"><div class="col-md-12"><p><b>CANTIDAD:</b> </p><input class="form-control" name="monto" value="'+row.data().cantidad+'" required></div></div>');

            $("#modal_tipo_cambio .modal-body").append('<div class="row"><div class="col-md-12"><p><b>EMPRESA:</b> </p> <select name="empresa2" id="empresa2" class="form-control" required></select></div></div>');

            $("#modal_tipo_cambio .modal-body").append('<div class="row"><div class="col-md-12"><p><b>PROYECTO:</b> </p> <select name="proyecto2" id="proyecto2" class="form-control" required></select></div></div><br>');
 
            $("#modal_tipo_cambio .modal-body").append('<div class="row"><div class="col-lg-8 col-lg-offset-2"><button class="btn bg-orange btn-block">GUARDAR</button></div></div>');

            $("#empresa2").html('');
            $("#empresa2").append('<option value="">Seleccione una opción</option>');
            $("#empresa2").append('<option value="NULL">SIN DEFINIR</option>');
            $.each( empresas, function( i, v){
                $("#empresa2").append('<option value="'+v.idempresa+'" data-value="'+v.rfc+'">'+v.nombre+'</option>');
            });  
 
            $("#proyecto2").html('');
            $("#proyecto2").append('<option value="">Seleccione una opción</option>');
            $("#proyecto2").append('<option value="NULL">SIN DEFINIR</option>');
            $.each(proyectos, function( i, v){
                $("#proyecto2").append('<option value="'+v.idproyecto+'">'+v.concepto+'</option>');
            });       
 
            $("#modal_tipo_cambio").modal();
        });








        $("#tabla_autorizaciones tbody").on("click", ".cargar_logs", function(){

            var tr = $(this).closest('tr');
            var row = table_autorizar.row( tr );
            id_saldo = $(this).val();
            $("#modal_tipo_log .modal-title").html("VER MOVIMIENTOS DE SOL. #"+id_saldo);
 
            $.getJSON( url + "Saldos/historial_logs/"+id_saldo).done( function( data ){
                $("#chat").html(data);
            });

            $("#modal_tipo_log .modal-body").append('<input type="hidden" name="id_saldo" value="'+row.data().id_saldo+'">');

            $("#modal_tipo_log").modal();
        });









        var id_saldo;

$("#form_cambio").submit( function(e) {
    e.preventDefault();
}).validate({
    submitHandler: function( form ) {

        var data = new FormData( $(form)[0] );
        data.append("id_saldo", id_saldo);

        $.ajax({
            url: url + "Saldos/editar_datos",
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            method: 'POST',
                type: 'POST', // For jQuery < 1.9
                success: function(data){
                    // alert(data[0]);
                    if(true){
                        $("#modal_tipo_cambio").modal('toggle' );
                        table_autorizar.ajax.reload();
                    }else{
                        alert("NO SE HA PODIDO COMPLETAR LA SOLICITUD");
                    }
                },error: function( ){
                    
                }
            });
    }
});

});

     /* ------------------------------------------------------------------------------------------------------- */

    $('.from, .to').change( function() { 
        table_autorizar.draw();
    });

    $.fn.dataTableExt.afnFiltering.push( function( oSettings, aData, iDataIndex ) {

        if( $('.from').val() || $('.to').val() ){
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

            iFini = iFini.substring(6,10) + iFini.substring(3,5)+ iFini.substring(0,2);
            iFfin = iFfin.substring(6,10) + iFfin.substring(3,5)+ iFfin.substring(0,2);
            $meses = { "Ene" : "01", "Feb" : "02", "Mar" : "03", "Abr" : "04", "May" : "05", "Jun" : "06", "Jul" : "07", "Ago" : "08", "Sep" : "09", "Oct" : "10", "Nov" : "11", "Dic" : "12" };
            console.log( aData[iStartDateCol].substring(7,11) + aData[iStartDateCol].substring(3,6)+ aData[iStartDateCol].substring(0,2) );

            var datofini=aData[iStartDateCol].substring(7,11) + $meses[aData[iStartDateCol].substring(3,6)]+ aData[iStartDateCol].substring(0,2);
            var datoffin=aData[iEndDateCol].substring(7,11) + $meses[aData[iEndDateCol].substring(3,6)]+ aData[iEndDateCol].substring(0,2);

            if ( iFini === "" && iFfin === "" ){
                return true;
            }else if ( iFini <= datofini && iFfin === ""){
                return true;
            }else if ( iFfin >= datoffin && iFini === ""){
                return true;
            }else if (iFini <= datofini && iFfin >= datoffin){
                return true;
            }
            
            return false;
        }else{
            return true;
        }
	});

</script>
<?php
    require("footer.php");
?>