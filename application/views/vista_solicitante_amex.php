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
                        <h3>COMPROBACIÓN AMERICAN EXPRESS</h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-striped" id="tabla_solicitudes" width="125%">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th style="font-size: .8em">#</th>
                                            <th style="font-size: .8em">PROYECTO</th>
                                            <th style="font-size: .8em">EMPRESA</th>
                                            <th style="font-size: .8em">FECHA</th>
                                            <th style="font-size: .8em">PROVEEDOR</th>
                                            <th style="font-size: .8em">CAPTURISTA</th>                                                    
                                            <th style="font-size: .8em">CANTIDAD</th>
                                            <th style="font-size: .8em">F PAGO</th>
                                            <th style="font-size: .8em">ESTATUS</th>
                                            <th style="font-size: .8em">DEPARTAMENTO</th>
                                            <th style="font-size: .8em">JUSTIFICACIÓN</th>
                                            <th style="font-size: .8em"></th>
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

<!--MODAL NUEVO PAGO-->
<div id="modal_solicitud" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="exampleModalLabel">NUEVO PAGO AMERICAN EXPRESS</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12"> 
                        <form  id="form_sol" method="post" action="#">
                            <input type="hidden" name="accion" value="reg" id="accion_sol"/>
                            <input type="hidden" name="idsolicitud" value="reg" id="idsolicitud"/>
                            <div class="row">
                                <div class="col-lg-6 form-group">
                                    <label for="proyecto">PROYECTO<span class="text-danger">*</span></label>
                                    <select class="listado_proyectos form-control" name="proyecto" id="idproyecto" required></select>
                                </div>
                                <div class="col-lg-6 form-group">
                                    <label for="empresa">EMPRESA<span class="text-danger">*</span></label>
                                    <select name="empresa" id="idempresa" class="form-control lista_empresa" required></select>
                                    <input type="hidden" class="form-control" name="empresa" disabled>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 form-group">
                                    <label for="empresa">REFERENCIA <i class="far fa-question-circle faq" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" title="Referencia" data-content="En caso de requerir una referencia favor de colocarla." data-placement="right"></i></label>
                                    <input type="text" placeholder="Numeros o letras: FACT445000" class="form-control solo_letras_numeros" maxlength="25" id="referencia_pago" name="referencia_pago">
                                </div>
                                <div class="col-lg-6 form-group">
                                    <label for="proveedor_programado">PROVEEDOR<span class="text-danger">*</span> <i class="far fa-question-circle faq text-danger" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" title="Proveedores" data-content="A continuación se muestra solo los proveedores que no requieren factura o que se puede agregar posterior al pago." data-placement="right"></i></label>
                                    <select name="proveedor" class="form-control" style="width: 100%;" id="idproveedor" required>
                                        <option value="176" data-tinsumo="null" data-privilegio="2" data-value="">AMEX - AMERICAN EXPRESS</option>
                                    </select>
                                    <input type="hidden" name="idproveedor" class="form-control" id="idproveedor" >
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 form-group">
                                    <label for="solobs">JUSTIFICACIÓN<span class="text-danger">*</span> <i class="far fa-question-circle faq" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" title="Observaciones de la solicitud" data-content="En caso de ser necesario, se podrán especificar datos, comentarios u observaciones para cuentas por pagar, DG, DP o para la dirección del departamento solicitante." data-placement="right"></i></label>
                                    <textarea class="form-control" id="justificacion" name="justificacion" placeholder="Observaciones de la solicitud" required></textarea>
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

<!-- MODAL PARA CARGAR FACTURA -->
<div id="modal_factura" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">NUEVA FACTURA PARA SOLICITUD #<b></b></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                <form id="form_fact">
                    <input type="hidden" name="idsolicitud" id="idsol_fact" value="0">
                    <input type="hidden" name="empresa_sol" id="empresasol_fact" value="0">
                    <div class="col-lg-12">
                        <div class="col-lg-6">
                            <input class="form-check-input" type="radio" name="confactura" id="rbt1" value="1" checked>
                            <label class="form-check-label" for="rbt1">CON FACTURA</label>
                        </div>
                        <div class="col-lg-6">
                            <input class="form-check-input" type="radio" name="confactura" id="rbt2" value="0">
                            <label class="form-check-label" for="rbt2">SIN FACTURA</label>
                        </div>
                    </div>
                    <div class="col-lg-12" id="div_factpago">
                        <div class="form-group">
                            <h5><b>CARGAR DOCUMENTO XML</b></h5>
                            <div class="input-group">
                                <input type="file" name="xmlfile" id="xmlfile"  class="form-control" accept="application/xml" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label>Justificación:</label>
                        <textarea class="form-control" rows="4" name="justificacion" id="justificacion_fact" required style="resize: none;" onKeyPress="return /[a-z 0-9]/i.test(event.key)"></textarea>
                    </div>
                </form>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" type="submit" form="form_fact"><i class="fas fa-upload"></i> AGREGAR PAGO</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var proyectos=[],empresas=[];
    var row_sel=null,row_seldata={};
    var tabla_solicitudes;
    var valor_input = Array( $('#tabla_solicitudes th').length );
    $(document).ready(function(e){
        enviar_post2(function(respuesta){
            $(".listado_proyectos").html('<option value="">Seleccione una opción</option>');
            proyectos=respuesta.lista_proyectos_depto;
            $.each( proyectos, function( i, v){
                $(".listado_proyectos").append('<option value="'+v.concepto+'">'+v.concepto+'</option>');
            });

            $(".lista_empresa").html('<option value="">Seleccione una opción</option>');
            empresas=respuesta.empresas;
            $.each( empresas, function( i, v){
                $(".lista_empresa").append('<option value="'+v.idempresa+'" data-value="'+v.rfc+'">'+v.nombre+'</option>');
            });

            $("#idproveedor").html('<option value="">Seleccione una opción</option>');
            $.each( respuesta.proveedores_tdc, function( i, v){
                $("#idproveedor").append('<option value="'+v.idproveedor+'" data-value="'+v.rfc+'">'+v.nombre+'</option>');
            });

        },{},url + "Listas_select/listas_td_creditos");

        $("#tabla_solicitudes").ready(function(e){

            $('#tabla_solicitudes thead tr:eq(0) th').each( function (i) {
                if( i != 0 && i != valor_input.length-1 ){
                    var title = $(this).text();
                    $(this).html( '<input type="text" style="font-size: .8em; width: 100%;" class="form-control" data-value="'+title+'" placeholder="'+title+'" title="'+title+'" />' );

                    $( 'input', this ).on('keyup change', function () {
                        if (tabla_solicitudes.column(i).search() !== this.value ) {
                            tabla_solicitudes.column(i).search( this.value).draw();
                            valor_input[i] = this.value;

                            var total = 0;
                            var index = tabla_solicitudes.rows( { selected: true, search: 'applied' } ).indexes();
                            var data = tabla_solicitudes.rows( index ).data();
                            $.each(data, function(i, v){
                                total += parseFloat(v.cantidad);
                            });
                            $("#total_solicitudes").val(formatMoney(total));
                        }
                    });
                }
            });

            $('#tabla_solicitudes').on('xhr.dt', function ( e, settings, json, xhr ) {
                var total = 0;
                $.each( json.data,  function(i, v){
                    total += parseFloat(v.cantidad);
                });
                $("#total_solicitudes").val(formatMoney(total));
            });

            tabla_solicitudes = $("#tabla_solicitudes").DataTable({
                dom: 'Brtip',
                buttons: [
                    {
                        text: '<i class="fas fa-plus"></i> NUEVA SOLICITUD',
                        attr: { class: 'btn btn-success btn-sm' },
                        action: function ( e, dt, node, config ) {
                            $("#modal_solicitud form").trigger("reset");
                            $("#accion_sol").val("reg");
                            $("#idsolicitud").val("0");
                            $("#modal_solicitud .modal-title").html('REGISTRAR NUEVA SOLICITUD');
                            $('#modal_solicitud button[type="submit"]').text("GUARDAR");
                            $("#modal_solicitud").modal('toggle');
                        }
                    },
                ],
                "language":lenguaje,
                "processing": false,
                "pageLength": 10,
                "bAutoWidth": false,
                "bLengthChange": true,
                "scrollX": true,
                "bInfo": false,
                "searching": true,
                "deferRender": true,
                "columns":[{
                        "className": 'details-control',
                        "orderable": false,
                        "data" : null,
                        "defaultContent": '<i class="fas animacion fa-caret-right"></i>'
                    },
                    {"orderable": false,
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.idsolicitud+'</p>'
                    }},
                    {"orderable": false,
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.proyecto+'</p>'
                    }},
                    {"orderable": false,
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.empresa+'</p>'
                    }},
                    {"orderable": false,
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+formato_fechaymd(d.fechaCreacion)+'</p>'
                    }},
                    {"orderable": false,
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.proveedor+'</p>'
                    }},
                    {"orderable": false,
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.usuario+'</p>'
                    }},
                    {"orderable": false,
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+formatMoney(d.cantidad)+" "+d.moneda+'</p>'
                    }},
                    {"orderable": false,
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.metoPago+'</p>'
                    }},
                    {"orderable": false,
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.etapa+'</p>'
                    }},
                    {"orderable": false,
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.nomdepto+'</p>'
                    }},
                    {"orderable": false,
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.justificacion+'</p>'
                    }},
                    {"orderable": false,
                    "data" : function( d ){
                        var btn='';
                        btn+='<button type="button" class="btn btn-sm btn-primary consultar_modal notification" value="'+d.idsolicitud+'" data-value="BAS" title="Ver Solicitud"><i class="fas fa-eye"></i>'+(d.visto == 0 ? '<span class="badge">!</span>' : '')+'</button> '
                        btn+='<button type="button" class="btn btn-sm bg-navy agregar_factura" value="'+d.idsolicitud+'" title="AGREGAR FACTURA/PAGO"><i class="fas fa-file-invoice-dollar"></i></button> ';
                        btn+='<button type="button" class="btn btn-sm btn-success enviar_a_dg" value="'+d.idsolicitud+'" title="ENVIAR SOLICITUD"><i class="fas fa-share-square"></i></button>';
                        btn+='<button type="button" class="btn btn-sm btn-warning editar_sol" value="'+d.idsolicitud+'" title="EDITAR SOLICITUD"><i class="fas fa-pencil-alt"></i></button> ';
                        return '<div class="btn-group" role="group" style="display: flex;">'+btn+'</div>'
                    }}
                ],
                "columnDefs": [
                    {
                        "targets": [10,11],
                        "visible": false,
                    },
                ],
            });

            $("#tabla_solicitudes > tbody").on("click", ".editar_sol", function(){
                $("#modal_solicitud form").trigger("reset");
                row_sel=$(this).closest('tr');
                row_seldata=tabla_solicitudes.row( row_sel ).data();
                $("#idproyecto").val(row_seldata.proyecto);
                $("#idempresa").val(row_seldata.idEmpresa);
                $("#referencia_pago").val(row_seldata.ref_bancaria);
                $("#idproveedor").val(row_seldata.idProveedor);
                $("#justificacion").val(row_seldata.justificacion);
                
                $("#modal_solicitud").modal( 'toggle' );

                $("#accion_sol").val("mod");
                $("#idsolicitud").val($(this).val());
                $("#modal_solicitud .modal-title").html('EDITANDO LA SOLICITUD # <b>'+$(this).val()+'</b>');
                $('#modal_solicitud button[type="submit"]').text("ACTUALIZAR");
            });

            $("#tabla_solicitudes > tbody").on("click", ".agregar_factura", function(){
                $("#form_fact")[0].reset();
                row_sel=$(this).closest('tr');
                row_seldata=tabla_solicitudes.row( row_sel ).data();
                $("#idsol_fact").val(row_seldata.idsolicitud);
                $("#empresasol_fact").val(row_seldata.rfc)
                $("#modal_factura .modal-title > b").text($(this).val());
                $("#modal_factura").modal( 'toggle' );
            });

            $('#tabla_solicitudes > tbody').on( "click", ".enviar_a_dg", function(){
                var tr = $(this).closest('tr');
                var row = tabla_solicitudes.row(tr).data();

                var fd = new FormData();
                fd.append("idsolicitud",$(this).val())
                fd.append("departamento",row.nomdepto)
                if(Number(row.cantidad)==0){
                    alert("No puede enviar una solicitud con cantidad 0")
                }else
                    enviar_post2((data)=>{
                        if( data.resultado ){
                            tabla_solicitudes.ajax.url( url+"Solicitante/solicitudes_amextabla" ).load();
                        }else{
                            alert("Algo salio mal, recargue su página.")
                        }
                    },fd, url + "Solicitante/enviar_a_dg" );
            });

            $('#tabla_solicitudes > tbody').on('click', 'td.details-control', function () {
                var tr = $(this).closest('tr');
                var row = tabla_solicitudes.row( tr );
                
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
                    '</table>';

                    row.child( informacion_adicional ).show();
                    tr.addClass('shown');
                    $(this).parent().find('.animacion').removeClass("fa-caret-right").addClass("fa-caret-down");
                }
            });

            tabla_solicitudes.ajax.url( url+"Solicitante/solicitudes_amextabla" ).load();

            $(".dt-buttons.btn-group").append('<div class="input-group-addon" style="padding: 3px;">\
                    <h4 style="padding: 0; margin: 0;"><label>&nbsp;Total: $<input style="text-align: right; border-bottom: none; border-top: none; border-right: none;  border-left: none; background: white;" disabled="disabled" readonly="readonly" type="text"  name="total_solicitudes" id="total_solicitudes"></label></h4>\
                </div>');
        });

        

    });

    $('input[type=radio][name=confactura]').change(function() {
        if (this.value == '1') {
            $("#div_factpago").html('<div class="form-group">\
                            <h5><b>CARGAR DOCUMENTO XML</b></h5>\
                            <div class="input-group">\
                                <input type="file" name="xmlfile" id="xmlfile"  class="form-control" accept="application/xml" required>\
                            </div>\
                        </div>');
        }else if (this.value == '0') {
            $("#div_factpago").html('<h5><b>MONTO</b></h5><input pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" type="text" name="monto" id="monto_pago" class="form-control" required>');
            $("#monto_pago").maskMoney({prefix:'$', allowNegative: false, thousands:',', decimal:'.', affixesStay: true});
        }
    });

    $("#form_sol").submit(function(e) {
         e.preventDefault();
     }).validate({
        submitHandler: function( form ) {
            var data = new FormData( $(form)[0] );
            enviar_post2((respuesta)=>{
                if( !respuesta.resultado ){
                    alert("NO SE HA PODIDO COMPLETAR LA SOLICITUD");
                }else{
                    $("#modal_solicitud").modal( 'toggle' );
                    alert(respuesta.mensaje+"");
                    tabla_solicitudes.ajax.url( url+"Solicitante/solicitudes_amextabla" ).load();
                }
            },data,url+"Solicitante/solicitudes_amex_acciones");
        }
    });

    $("#form_fact").submit(function(e) {
         e.preventDefault();
     }).validate({
        submitHandler: function( form ) {
            var fd = new FormData( $(form)[0] );
            fd.append("cajachica",0);
            enviar_post2((data)=>{
                if( data.respuesta[0] ){
                    alert( data.respuesta[1] );
                    tabla_solicitudes.ajax.url( url+"Solicitante/solicitudes_amextabla" ).load();
                    $("#modal_factura").modal('toggle');
                }else{
                    $("#xmlfile").val('');
                    alert( data.respuesta[1] );
                }
            },fd,url+"Solicitante/cargaxml_amex");
        }
    });

    $(window).resize(function(){
        tabla_solicitudes.columns.adjust();
    });

    
</script>

<?php
require("footer.php");
?>