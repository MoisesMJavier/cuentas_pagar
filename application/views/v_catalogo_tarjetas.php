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
                        <h3>TARJETAS DE CREDITO</h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-striped" id="tabla_solicitudes" width="125%">
                                    <thead>
                                        <tr>
                                            <th style="font-size: .8em">EMPRESA</th>
                                            <th style="font-size: .8em">CAPTURISTA</th>
                                            <th style="font-size: .8em">TARJETA</th>
                                            <th style="font-size: .8em">TITULAR TARJETA</th>
                                            <th style="font-size: .8em">PROVEEDOR</th>
                                            <th style="font-size: .8em">ESTATUS</th>
                                            <th></th>
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
                <h4 class="modal-title" id="exampleModalLabel">TARJETA DE CREDITO</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12"> 
                        <form  id="form_sol" method="post" action="#">
                            <div class="row">
                                <div class="col-lg-12 form-group">
                                    <label for="tarjeta">TARJETA<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="ntarjeta" id="ntarjeta" required></input>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 form-group">
                                    <label for="emisor">CAPTURISTA<span class="text-danger">*</span></label>
                                    <!-- idcapturista -->
                                    <select type="text" class="form-control" id="idresponsable" name="idresponsable"></select> 
                                </div>
                                <div class="col-lg-6 form-group">
                                    <label for="titular">TITULAR TARJETA<span class="text-danger">*</span></label>
                                    <select type="text" class="form-control" id="idtitular" name="idtitular" required></select> 
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 form-group">
                                    <label for="empresa">EMPRESA<span class="text-danger">*</span></label>
                                    <select name="idempresa" id="idempresa" class="form-control lista_empresa" required></select>
                                </div>
                                <div class="col-lg-6 form-group">
                                    <label for="estatus">ESTADO<span class="text-danger">*</span></label>
                                    <div style="margin-top: 3px;">
                                        <label class="radio-inline"><input type="radio" name="tdcestatus" value="1" required checked>ACTIVO</label>
                                        <label class="radio-inline"><input type="radio" name="tdcestatus" value="0" required>INACTIVO</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 form-group">
                                    <label for="emisor">EMISOR<span class="text-danger">*</span></label>
                                    <select class="form-control" id="idproveedor" name="idproveedor"></select>
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
<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<script type="text/javascript">
    
    var proyectos=[],empresas=[];
    var row_sel=null,row_seldata={};
    var tabla_solicitudes;
    var valor_input = Array( $('#tabla_solicitudes th').length );
    var id = null;

    $(document).ready(function(e){
        $("#idtitular, #idresponsable, #idempresa, #idproveedor" ).select2({
            placeholder: "--- Seleccione una opción ---",
            enableFiltering: true,
            allowClear: true,
            "language": {
                "noResults": function(){
                    return "No se encontraron resultados.";
                }
            },
        });

        enviar_post2( function(respuesta){
            
            //
            $("#idresponsable").html('<option value="">Seleccione una opción</option>');
            $.each( respuesta.responsables, function( i, v){
                $("#idresponsable").append('<option value="'+v.idusuario+'">'+v.nombre_completo+'</option>');
            });

            //
            $("#idempresa").html('<option value="">Seleccione una opción</option>');
            $.each( respuesta.empresas, function( i, v){
                $("#idempresa").append('<option value="'+v.idempresa+'" data-value="'+v.rfc+'">'+v.nombre+'</option>');
            });

            //
            $("#idproveedor").html('<option value="">Seleccione una opción</option>');
            $.each( respuesta.proveedores, function( i, v){
                $("#idproveedor").append('<option value="'+v.idproveedor+'" data-value="'+v.rfc+'">'+v.nproveedor+'</option>');
            });

            $("#idtitular").html('<option value="">---Seleccione una opción---</option>');
            $.each( respuesta.usuarios, function( i, v){
                $("#idtitular").append('<option value="'+v.idusuario+'">'+v.nombre_completo+'</option>');
            });
        },{},url + "Listas_select/listas_td_creditos");

        $("#tabla_solicitudes").ready(function(e){

            $('#tabla_solicitudes thead tr:eq(0) th').each( function (i) {
                if( i < valor_input.length-1 ){

                    var title = $(this).text();
                    $(this).html( '<input type="text" style="font-size: .8em; width: 100%;" class="form-control" data-value="'+title+'" placeholder="'+title+'" title="'+title+'" />' );

                    $( 'input', this ).on('keyup change', function () {
                        if (tabla_solicitudes.column(i).search() !== this.value ) {
                            tabla_solicitudes.column(i).search( this.value).draw();
                            valor_input[i] = this.value;
                        }
                    });
                }
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

                            id = null;
                            $("form#form_sol").attr("action", "Tarjetas_credito/nueva_tarjeta");

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
                "columns":[
                    {
                        "orderable": false,
                        "data" : function( d ){
                            return '<p style="font-size: .8em">'+d.abrev+'</p>'
                        }
                    },
                    {
                        "orderable": false,
                        "data" : function( d ){
                            return '<p style="font-size: .8em">'+d.nresponsable+'</p>'
                        }
                    },
                    {
                        "orderable": false,
                        "data" : function( d ){
                            return '<p style="font-size: .8em">'+d.ntarjeta+'</p>'
                        }
                    },
                    {
                        "orderable": true,
                        "data" : function( d ){
                            return '<p style="font-size: .8em">'+(d.idtitular == null ? 'NA' : d.ntitular)+'</p>'
                        }
                    },
                    {
                        "orderable": false,
                        "data" : function( d ){
                            return '<p style="font-size: .8em">'+d.nproveedor+'</p>'
                        }
                    },
                    {
                        "orderable": false,
                        "data" : function( d ){
                            return '<p style="font-size: .9em">'+( d.tdcestatus == 1 ? '<label class="label pull-center bg-green">ACTIVO</label>' : '<label class="label pull-center bg-red">INACTIVO</label>' )+ '</p>'
                        }
                    },
                    {
                        "orderable": false,
                        "data" : function( d ){
                            var btn='';
                            btn+='<button type="button" class="btn btn-sm btn-warning editar_sol" value="'+d.idtcredito+'" title="EDITAR SOLICITUD"><i class="fas fa-pencil-alt"></i></button> ';
                            return '<div class="btn-group" role="group" style="display: flex;">'+btn+'</div>'
                        }
                    }
                ]
            });

            $("#tabla_solicitudes > tbody").on("click", ".editar_sol", function(){

                $("#modal_solicitud form").trigger("reset");
                row_sel = $(this).closest('tr');

                row_seldata = tabla_solicitudes.row( row_sel ).data();
                id = row_seldata.idtcredito

                $("#ntarjeta").val( row_seldata.ntarjeta);
                $("#idresponsable").val(row_seldata.idusuario).trigger("change");
                $("#idempresa").val(row_seldata.idempresa).trigger("change");
                $("#idproveedor").val(row_seldata.idproveedor).trigger("change");
                $("#idtitular").val(row_seldata.idtitular).trigger("change");
                $("input[name='tdcestatus']").prop( "checked", false );
                $("input[name='tdcestatus'][value='"+row_seldata.tdcestatus+"']").prop( "checked", true );
                
                $("form#form_sol").attr("action", "Tarjetas_credito/actualizar_tarjeta");

                $("#modal_solicitud").modal( 'toggle' );

                $("#accion_sol").val("mod");
                $("#idsolicitud").val($(this).val());
                $("#modal_solicitud .modal-title").html('EDITANDO TARJETA');
                $('#modal_solicitud button[type="submit"]').text("ACTUALIZAR");
            });

            tabla_solicitudes.ajax.url( url+"Tarjetas_credito/tabla_tarjetas" ).load();

        });
    });

    $("#form_sol").submit(function(e) {
         e.preventDefault();
     }).validate({
        submitHandler: function( form ) {
            var data = new FormData( $(form)[0] );
            data.append( "id", id );
            enviar_post2( ( respuesta ) => {

                if( !respuesta.resultado ){
                    alert("NO SE HA PODIDO COMPLETAR LA SOLICITUD");
                }else{
                    $("#modal_solicitud").modal( 'toggle' );
                    if( respuesta.mensaje ){
                        alert( respuesta.mensaje );
                    }

                    tabla_solicitudes.clear().draw();
                    tabla_solicitudes.rows.add( respuesta.data );
                    tabla_solicitudes.columns.adjust().draw();

                }
            }, data, url + $( "form#form_sol" ).attr("action") );
        }
    });

    $( window ).resize(function(){
        tabla_solicitudes.columns.adjust();
    });

    
</script>

<?php
require("footer.php");
?>