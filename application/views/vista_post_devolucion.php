<?php
require("head.php");
require("menu_navegador.php");
?>
<!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script> -->
<style>

</style>
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <div class="box">
                    <div class="box-header with-border">
                        <input type="hidden" id="rol" value="<?= $this->session->userdata("inicio_sesion")['rol'] ?>" />
                        <h3>SOLICITUDES POST-DEVOLUCIONES</h3>
                    </div>
                    <div class="box-body">


                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                                <li class="active"><a id="autorizaciones-tab" data-toggle="tab" href="#facturas_autorizar" role="tab" aria-controls="#home" aria-selected="true">POST-DEVOLUCIONES</a></li>
                                <li><a id="autorizaciones-tab" data-toggle="tab" href="#facturas_activas" role="tab" aria-controls="#home" aria-selected="true">POST-DEVOLUCIONES EN CURSO</a></li>
                            </ul>
                        </div>

                        <div class="tab-content">
                            <div class="active tab-pane" id="facturas_autorizar">

                                <div class="row">
                                    <div class="col-lg-12 col-md-12">
                                        <table class="table table-striped" id="tabla_autorizaciones">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th></th>
                                                    <th style="font-size: .9em">#</th>
                                                    <th style="font-size: .9em">F ENTREGA PV</th>
                                                    <th style="font-size: .9em">EMPRESA</th>
                                                    <th style="font-size: .9em">PROVEEDOR</th>
                                                    <th style="font-size: .9em">PROYECTO</th>
                                                    <th style="font-size: .9em">CANTIDAD</th>
                                                    <th style="font-size: .9em">PROCESO</th>
                                                    <th style="font-size: .9em">MÉTODO DE PAGO</th>
                                                    <th style="font-size: .9em">ÚLTIMO MOVIMIENTO</th>
                                                    <th style="font-size: .9em">ESTATUS</th>
                                                    <th nowrap style="font-size: .9em"></th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>          
                            </div>

                            <div class="tab-pane" id="facturas_activas">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12">
                                        <form id="formulario_facturas_activas" autocomplete="off" action="<?= site_url("Reportes/solicitante_solPago_solActivas") ?>" method="post" onkeydown="return event.key != 'Enter';">
                                            <div class="col-lg-3">
                                                <div class="input-group">
                                                    <span class="input-group-addon"><i class="fas fa-calendar-alt"></i> <b>INICIO</b></span>
                                                    <input class="form-control fechas_filtro from" type="text" id="fecInicial" name="fechaInicial" maxlength="10" />
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="input-group">
                                                    <span class="input-group-addon"><i class="fas fa-calendar-alt"></i> <b>FIN</b></span>
                                                    <input class="form-control fechas_filtro to" type="text" id="fecFinal" name="fechaFinal" maxlength="10" />
                                                </div>
                                            </div>
                                            <div id="elementos_hidden"></div>
                                            <table class="table table-striped" id="tblsolact">
                                                <thead class="thead-dark">
                                                    <tr>
                                                        <th style="font-size: .9em">#</th>
                                                        <th style="font-size: .9em">EMPRESA</th>
                                                        <th style="font-size: .9em">CLIENTE</th>
                                                        <th style="font-size: .9em">LOTE</th>
                                                        <th style="font-size: .9em">ETAPA</th>
                                                        <th style="font-size: .9em">F ENTREGA PV</th>
                                                        <th style="font-size: .9em">PROCESO</th>
                                                        <th style="font-size: .9em">JUSTIFICACION</th>
                                                        <th style="font-size: .9em">SOLICITANTE</th>
                                                        <th style="font-size: .9em">CUENTA CONTABLE</th>
                                                        <th style="font-size: .9em">CUENTA ORDEN</th>
                                                        <th style="font-size: .9em">COSTO LOTE</th>
                                                        <th style="font-size: .9em">SUPERFICIE</th>
                                                        <th style="font-size: .9em">PRECIO M2</th>
                                                        <th style="font-size: .9em">PREDIAL</th>
                                                        <th style="font-size: .9em">PENALIZACIÓN</th>
                                                        <th style="font-size: .9em">IMPORTE APORTADO</th>
                                                        <th style="font-size: .9em">MANTENIMIENTO</th>
                                                        <th style="font-size: .9em">MOTIVO</th>
                                                        <th style="font-size: .9em">ULT VOBO</th>
                                                        <th style="font-size: .9em">FECHA VOBO</th>
                                                        <th style="font-size: .9em">CANTIDAD</th>
                                                        <th style="font-size: .9em">PAGADO</th>
                                                        <th style="font-size: .9em">RESTANTE</th>
                                                        <th style="font-size: .9em">DIAS T</th>
                                                        <th style="font-size: .9em">RECHAZOS</th>
                                                        <th style="font-size: .9em">ESTATUS</th>
                                                        <th style="font-size: .9em">F CAPTURA</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </form>
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

<div id="mensaje_correcto" class="modal modal-default error_duplicidad fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><span id="titulo_modal"></span></h4>

            </div>

            <div class="modal-body text-center">
                <span id="mensaje_modal"></span>
            </div>
        </div>
    </div>
</div>



<div class="modal fade" id="modal_reubicar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">REGRESAR <span id="txt_proceso"> </span> #<span id="idsol_regre"> </span><span id="idsol_regre_new"> </span></h4>
            </div>
            <form id="comentario" method="post" action="#" onkeydown="return event.key != 'Enter';">
                <div class="modal-body">

                    <div class="row">
                        <div class="col-lg-12 form-group">
                            <h5>Seleccione el área al que desea regresar este proceso</h5>
                        </div>
                        <input type="hidden" id="iddocumento" name="iddocumento" />
                        <div class="col-lg-12 form-group">

                            <div class="form-group">
                                <label>ÁREAS</label>
                                <select class="form-control" id="radios" name="radios" required>
                                    <option value="">Seleccione una opción</option>

                                </select>
                            </div>

                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>Comentario</label>
                                <textarea id='text_comentario' name="text_comentario" rows='4' style='margin: 0px; width: 570px;' required></textarea>
                            </div>

                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary" id="regresar_sol">Regresar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_comentario_avanzar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">AVANZAR <span id="span_proceso"> </span> #<span id="id_sol_avancom"> </span> </h4>
            </div>
            <form id="comentario_avanzar" method="post" action="#" onkeydown="return event.key != 'Enter';">
                <div class="modal-body">
                    <input type="hidden" id="id_sol_avancom1" name="id_sol_avancom1" />
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>Comentario</label>
                                <textarea id='text_comentario_ava' name="text_comentario_ava" rows='4' style='margin: 0px; width: 570px;' required></textarea>
                            </div>

                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Avanzar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_reubicar_avanzar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">AVANZAR <span id="text_proceso"></span> #<span id="sol_modal_avanzar"></span></h4>
            </div>
            <form id="areas_avanzar_form" method="post" action="#" onkeydown="return event.key != 'Enter';">
                <div class="modal-body">

                    <div class="row">
                        <div class="col-lg-12 form-group">
                            <h5>Seleccione el área al que desea avanzar el proceso</h5>
                        </div>
                        <div class="col-lg-12 form-group">
                            <input type="hidden" name="idsol_area" id="idsol_area" />
                            <div class="form-group">
                                <label>ÁREAS</label>
                                <select class="form-control" id="areas_avanzar" name="areas_avanzar" required>
                                    <option value="">Seleccione una opción</option>

                                </select>
                            </div>

                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary" id="avanzar_area">Avanzar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal" tabindex="-1" role="dialog" id="modal_confirm">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Borrar documento</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>¿Está seguro que desea eliminar el documento?.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="eliminar_doc_modal">Eliminar</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script type="text/javascript">

    var idsolicitud = 0;
    var link_post = "", consulta = 0, opcion = 0,  trglobal;
    var rol = $('#rol').val();

    $('#fecInicial').val(moment().subtract(2, 'year').calendar());
    $('#fecFinal').val(moment().add(720, 'days').format('L'));

    $(document).ready(function() {


        $('[data-toggle="tab"]').click(function(e) {
            switch ($(this).attr('href')) {
                case '#facturas_autorizar':
                    tabla_devoluciones.ajax.reload();
                    consulta = 0;
                    break;
                case '#facturas_activas':
                    var f1=$('#fecInicial').val();
                    var f2=$('#fecFinal').val();
                    $.ajax({ 
                        "url" : url + "Post_Devoluciones/tabla_dev_encurso", //"Devoluciones_Traspasos/tabla_facturas_encurso",
                        "type": "POST",
                        "data" : {
                            finicial : f1.substring(6,10)+'-'+f1.substring(3,5)+'-'+f1.substring(0,2),
                            ffinal : f2.substring(6,10)+'-'+f2.substring(3,5)+'-'+f2.substring(0,2)
                         },
                         success: function(result){
                           data = JSON.parse(result);
                            table_proceso.clear().draw();
                            table_proceso.rows.add(data.data); 
                            table_proceso.columns.adjust().draw();   
                         } });
                    consulta = 1;
                    break;
            }
        });
     
    }).ajaxStart($.blockUI).ajaxStop($.unblockUI);



    $("#modal_reubicar").on('hidden.bs.modal', function() {
        $("#radios").val('');
        $("#text_comentario").val('');
    });

    $("#tabla_autorizaciones").ready(function() {

        $('#tabla_autorizaciones').on('xhr.dt', function(e, settings, json, xhr) {
            tabla_devoluciones.button(1).enable(parseInt(json.por_autorizar) > 0);
        });

        $('#tabla_autorizaciones thead tr:eq(0) th').each(function(i) {
            if (i != 0 && i < $('#tabla_autorizaciones thead tr:eq(0) th').length - 1) {
                var title = $(this).text();
                $(this).html('<input type="text" style="font-size: .9em; width: 100%;" placeholder="' + title + '" title="' + title + '" class="form-control"/>');

                $('input', this).on('keyup change', function() {
                    if (tabla_devoluciones.column(i).search() !== this.value) {
                        tabla_devoluciones
                            .column(i)
                            .search(this.value)
                            .draw();
                        var total = 0;
                        var index = tabla_devoluciones.rows({
                            selected: true,
                            search: 'applied'
                        }).indexes();
                        var data = tabla_devoluciones.rows(index).data();
                        $.each(data, function(i, v) {
                            total += parseFloat(v.cantidad);
                        });
                        var to1 = formatMoney(total);
                        $("#myText_1").text(to1);
                    }
                });
            }
        });

        tabla_devoluciones = $('#tabla_autorizaciones').DataTable({
            "language": lenguaje,
            "processing": false,
            "pageLength": 10,
            "bAutoWidth": false,
            "bLengthChange": false,
            "bInfo": false,
            "scrollX": true,
            "columns": [{
                    "className": 'details-control',
                    "orderable": false,
                    "data": null,
                    "defaultContent": '<i class="fas animacion fa-caret-right"></i>'
                },
                {
                    "width": "9%",
                    "data": function(d) {
                        return '<p style="font-size: .8em">' + d.idsolicitud + '</p>'
                    }
                },
                {
                    "width": "9%",
                    "data": function(d) {
                        return '<p style="font-size: .8em">' + d.fecelab + '</p>'
                    }
                },
                {
                    "width": "9%",
                    "data": function(d) {
                        return '<p style="font-size: .8em">' + d.nempresa + '</p>'
                    }
                },
                {
                    "width": "15%",
                    "data": function(d) {
                        return '<p style="font-size: .7em">' + d.nombre + '</p>'
                    }
                },
                {
                    "width": "15%",
                    "data": function(d) {
                        return '<p style="font-size: .8em">' + d.condominio + '</p>'
                    }
                },
                {
                    "width": "12%",
                    "data": function(d) {
                        return '<p style="font-size: .8em">$ ' + formatMoney(d.cantidad) + " " + d.moneda + '</p>'
                    }
                },

                {
                    "width": "12%",
                    "data": function(d) {
                        return '<p style="font-size: .8em">' + d.nombre_proceso + '</p>'
                    }
                },
                {
                    "width": "12%",
                    "data": function(d) {
                        return '<p style="font-size: .8em">' + (d.metoPago) + '</p>'
                    }
                },
                {
                    "width": "12%",
                    "data": function(d) {
                        return '<p style="font-size: .8em">' + (d.fecha_autorizacion != null ? formato_fechaymd(d.fecha_autorizacion) : '') + '</p>'
                    }
                },
                {
                    "width": "12%",
                    "data": function(d) {
                        return '<p style="font-size: .8em">' + d.etapa + '</p>'
                    }
                },
                {
                    "data": "opciones",
                    "orderable": false,
                    "className": "td-nowrap"
                }
            ],
            "columnDefs": [{
                "orderable": false,
            }],
            "ajax": url + "Post_Devoluciones/tabla_postdevoluciones"
        });

        $('#tabla_autorizaciones tbody').on('click', 'td.details-control', function() {
            var tr = $(this).closest('tr');
            var row = tabla_devoluciones.row(tr);

            if (row.child.isShown()) {
                row.child.hide();
                tr.removeClass('shown');
                $(this).parent().find('.animacion').removeClass("fa-caret-down").addClass("fa-caret-right");
            } else {

                var informacion_adicional = '<table class="table text-justify">' +
                    '<tr>' +
                    '<td><strong>JUSTIFICACIÓN: </strong>' + row.data().justificacion + '</td>' +
                    '</tr>' +
                    '</table>';

                row.child(informacion_adicional).show();
                tr.addClass('shown');
                $(this).parent().find('.animacion').removeClass("fa-caret-right").addClass("fa-caret-down");
            }
        });


        $('#tabla_autorizaciones').on("click", ".avanzar", function() {

            var tr = $(this).closest('tr');
            var row = tabla_devoluciones.row(tr);
            trglobal = tr;
            $('#span_proceso').text(row.data().nombre_proceso);
            $('#id_sol_avancom').text($(this).val());
            $('#id_sol_avancom1').val($(this).val());
            $('#modal_comentario_avanzar').modal();

        });

        $("#comentario_avanzar").submit(function(e) {
            e.preventDefault();
            jQuery(this).find(':disabled').removeAttr('disabled');
        }).validate({
            submitHandler: function(form) {
                var data = new FormData($(form)[0]);
                enviar_post2(function(respuesta) {
                    // console.log(respuesta.res);
                    // data = JSON.parse(respuesta);
                    
                    if (respuesta.res) {
                        tabla_devoluciones.row( trglobal ).remove().draw();
                        $('#id_sol_avancom1').val('');
                        $('#modal_comentario_avanzar').modal("toggle");
                        $('#text_comentario_ava, #text_comentario_ava').html('').val('');
                    } else {
                        alert(respuesta.msn)
                    }

                }, data, url + 'Post_Devoluciones/devolucion_sigetapa');
            }
        });
     
        $('#tabla_autorizaciones tbody').on('click', '.cancelar_sol', function() {
            
            var idso_back = $(this).val();
            var tr = $(this).closest('tr');
            var row = tabla_devoluciones.row(tr);

            $('#txt_proceso').text(row.data().nombre_proceso);
            $('#idsol_regre').text(idso_back);
            $('#idsol_regre_new').text(idso_back);
            $("#modal_reubicar").modal();

            $.post(url + "Post_Devoluciones/conusltar_areas_proceso_menor", {
                idsolicitud: $(this).val()
            }, function(datos) {
                data = JSON.parse(datos);
                if (data.data) {
                    $('#radios').append('<option value="">Seleccione una opción</option>');
                    $.each(data.data, function(i, v) {
                        $('#radios').append('<option  data-value="' + v.solicitud + '" value="' + v.orden + '">' + v.nombre + '</option>');
                    });

                }

            });
            $("#radios").html("");
            $("#text_comentario").html("");
            $("#idsol_regre").html("");
        });

        $('#tabla_autorizaciones tbody').on('click', '.avanzar_lista', function() {
            
            var tr = $(this).closest('tr');
            var row = tabla_devoluciones.row(tr);

            $('#text_proceso').text(row.data().nombre_proceso);
            $('#sol_modal_avanzar').text($(this).val());
            $("#modal_reubicar_avanzar").modal();

            $.post(url + "Post_Devoluciones/conusltar_areas_proceso", {
                idsolicitud: $(this).val()
            }, function(datos) {
                data = JSON.parse(datos);
                if (data.data) {
                    $('#idsol_area').val(data.info[0].idsolicitud);
                    $('#areas_avanzar').append('<option value="">Seleccione una opción</option>');
                    $.each(data.data, function(i, v) {
                        $('#areas_avanzar').append(' <option  data-value="' + v.solicitud + '" value="' + v.orden + '">' + v.nombre + '</option>');
                    });
                }
            });

            $("#areas_avanzar").html("");
        });
    

        $("#comentario").submit(function(e) {
            e.preventDefault();
            jQuery(this).find(':disabled').removeAttr('disabled');
        }).validate({
            submitHandler: function(form) {
                var data = new FormData($(form)[0]);
                var sol = $("#radios").find('option:selected').attr("data-value");
                data.append("solicitud_fom", sol);
                enviar_post2(function(respuesta) {
                    if (data != false) {
                        $("#modal_reubicar").modal("toggle");
                        var tr = $(this).closest('tr').remove();

                        tabla_devoluciones.ajax.reload(null, false);
                    }

                }, data, url + 'Post_Devoluciones/regresar_sol_area');
            }
        });


        $('#eliminar_doc_modal').on('click', function(){
            var dx =$(this).val();
        $.post(url + "Post_Devoluciones/borrar_documento", {
                    iddocumento: dx
                }, function(datos) {
                    data = JSON.parse(datos);
                    // console.log(data.resultado);
                    if (data.resultado) {
                        documentos();
                        descarga_dosc();
                        $('#eliminar_doc_modal').val('');
                        $("#modal_confirm").modal("toggle");
                        var tr = $(this).closest('tr').remove();
                        tabla_devoluciones.ajax.reload(null, false);

                    }

                });
        }); 
    });

    $("#areas_avanzar_form").submit(function(e) {
        e.preventDefault();
        jQuery(this).find(':disabled').removeAttr('disabled');
    }).validate({
        submitHandler: function(form) {
            var data = new FormData($(form)[0]);
            enviar_post2(function(respuesta) {
                if (data != false) {
                    $("#modal_reubicar_avanzar").modal("toggle");
                    var tr = $(this).closest('tr').remove();
                    tabla_devoluciones.ajax.reload(null, false);
                }

            }, data, url + 'Post_Devoluciones/avanzar_area_r');
        }
    });

    function myFunctionBorrar(dx, idsol) {
        $("#iddocumento").val(dx);
        $("#eliminar_doc_modal").val(dx);
        $("#idsol_regre_new").text(idsol);
        $("#consultar_modal").modal("toggle");
        $("#modal_confirm").modal();
    } 
    
    function myFunction(dx, idsol) {
        $("#iddocumento").val(dx);
        $("#idsol_regre_new").text(idsol);
        $("#consultar_modal").modal("toggle");
        $("#modal_reubicar").modal();
        $.post(url + "Post_Devoluciones/conusltar_areas_proceso_menor ", { // Post_Devoluciones/conusltar_areas_proceso
            idsolicitud: idsol
        }, function(datos) {
            data = JSON.parse(datos);

            if (data.data) {
                $('#radios').append('<option value="">Seleccione una opción</option>');
                $.each(data.data, function(i, v) {
                    $('#radios').append('<option  data-value="' + v.solicitud + '" value="' + v.orden + '">' + v.nombre + '</option>');
                });
            }

        });
        $("#radios").html("");
        $("#text_comentario").html("");
        $("#iddocumento").html("");
        $("#idsol_regre").html("");
    }

    $('.fechas_filtro').datepicker({
        format: 'dd/mm/yyyy'
    })

        var table_proceso;
    
        $('#fecInicial').change(function() {
           table_proceso.draw();
        });

        $('#fecFinal').change(function() {
            table_proceso.draw();
        });
        
    $("#tblsolact").ready(function() {

        $('#tblsolact thead tr:eq(0) th').each(function(i) {
            if (i < $('#tblsolact thead tr:eq(0) th').length - 1) {
                var title = $(this).text();
                $(this).html('<input type="text" class="form-control" style="font-size: .9em; width: 100%;" placeholder="' + title + '"/>');

                $('input', this).on('keyup change', function() {
                    if (table_proceso.column(i).search() !== this.value) {
                        table_proceso
                            .column(i)
                            .search(this.value)
                            .draw();
                    }
                });
            }
        });


        table_proceso = $('#tblsolact').DataTable({
            dom: 'Brtip',
            "buttons": [
                {
                    text: '<i class="fas fa-search"></i>&nbsp;&nbsp',
                
                    attr: { class: 'btn' },

                    action: function(e, dt, node, config ) {
                    $('[data-toggle="tab"]').click();
                    }
                    
                },
                {
                    extend: 'excelHtml5',
                    text: '<i class="fas fa-file-excel"></i> EXPORTAR EXCEL',
                    messageTop: "DEVOLUCIONES EN CURSO",
                    attr: {
                        class: 'btn btn-success'
                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27],
                        format: {
                            header: function(data, columnIdx) {

                                data = data.replace('<input type="text" class="form-control" style="font-size: .9em; width: 100%;" placeholder="', '');
                                data = data.replace('">', '')
                                return data;
                            }
                        }
                    }
                }
            ],
            "language": lenguaje,
            "processing": false,
            "pageLength": 10,
            "bAutoWidth": false,
            "bLengthChange": false,
            "bInfo": false,
            "scrollX": true,
            "columns": [{
                    "width": "7%",
                    "data": function(d) {
                        return '<p style="font-size: .8em">' + d.idsolicitud + '</p>'
                    }
                },
                {
                    "width": "8%",
                    "data": function(d) {
                        return '<p style="font-size: .8em">' + d.abrev + '</p>'
                    }
                },
                {
                    "width": "15%",
                    "data": function(d) {
                        return '<p style="font-size: .7em">' + d.nombre + '</p>'
                    }
                },
                {
                    "width": "12%",
                    "data": function(d) {
                        return '<p style="font-size: .7em">' + d.condominio + '</p>'
                    }
                },
                {
                    "width": "16%",
                    "data": function(d) {
                        return '<p style="font-size: .8em">' + d.etapa + '</p>'
                    }
                },
                {
                    "width": "8%",
                    "data": function(d) {
                        return '<p style="font-size: .8em">' + (d.fecelab) + '</p>'
                    }
                },
                {
                    "width": "12%",
                    "data": function(d) {
                        return '<p style="font-size: .7em">' + d.nproceso + '</p>'
                    }
                },
                {
                    "width": "16%",
                    "data": function(d) {
                        return '<p style="font-size: .8em">' + d.justificacion + '</p>'
                    }
                },
                {
                    "width": "15%",
                    "data": function(d) {
                        return '<p style="font-size: .7em">' + (d.nombre_completo ? d.nombre_completo : '') + '</p>'
                    }
                },
                {
                    "width": "15%",
                    "data": function(d) {
                        return '<p style="font-size: .7em">' + (d.cuenta_contable ? d.cuenta_contable : '') + '</p>'
                    }
                },
                {
                    "width": "15%",
                    "data": function(d) {
                        return '<p style="font-size: .7em">' + (d.cuenta_orden ? d.cuenta_orden : '') + '</p>'
                    }
                },
                
                {
                    "width": "15%",
                    "data": function(d) {
                        return '<p style="font-size: .7em">' + (d.costo_lote ? d.costo_lote : '') + '</p>'
                    }
                },
                {
                    "width": "15%",
                    "data": function(d) {
                        return '<p style="font-size: .7em">' + (d.superficie ? d.superficie : '') + '</p>'
                    }
                },
                {
                    "width": "15%",
                    "data": function(d) {
                        return '<p style="font-size: .7em">' + (d.preciom ? d.preciom : '') + '</p>'
                    }
                },
                {
                    "width": "15%",
                    "data": function(d) {
                        return '<p style="font-size: .7em">' + (d.predial ? d.predial : '') + '</p>'
                    }
                },
                {
                    "width": "15%",
                    "data": function(d) {
                        return '<p style="font-size: .7em">' + (d.penalizacion ? d.penalizacion : '') + '</p>'
                    }
                },
                {
                    "width": "15%",
                    "data": function(d) {
                        return '<p style="font-size: .7em">' + (d.importe_aportado ? d.importe_aportado : '') + '</p>'
                    }
                },
                {
                    "width": "15%",
                    "data": function(d) {
                        return '<p style="font-size: .7em">' + (d.mantenimiento ? d.mantenimiento : '') + '</p>'
                    }
                },
                {
                    "width": "15%",
                    "data": function(d) {
                        return '<p style="font-size: .7em">' + (d.motivo ? d.motivo : '') + '</p>'
                    }
                },
                
                {
                    "width": "15%",
                    "data": function(d) {
                        return '<p style="font-size: .7em">' + d.nautoriza + '</p>'
                    }
                },
                {
                    "width": "10%",
                    "data": function(d) {
                        return '<p style="font-size: .7em">' + d.fecha_autorizacion + '</p>'
                    }
                },

                {
                    "width": "10%",
                    "data": function(d) {
                        return '<p style="font-size: .7em">$ ' + formatMoney(d.cantidad) + '</p>'
                    }
                },
                {
                    "width": "8%",
                    "data": function(d) {
                        return '<p style="font-size: .7em">$ ' + formatMoney(d.pagado) + '</p>'
                    }
                },
                {
                    "width": "8%",
                    "data": function(d) {
                        return '<p style="font-size: .7em">$ ' + formatMoney(d.cantidad - d.pagado) + '</p>'
                    }
                },
                {
                    "width": "6%",
                    "data": function(d) {
                        return '<p style="font-size: .8em">' + d.diasTrans+'</p>'
                    }
                },
                {
                    "width": "6%",
                    "data": function(d) {
                        return '<p style="font-size: .8em">' + d.numCancelados+'</p>'
                    }
                },

                {
                    "width": "8%",
                    "data": function(d) {
                        return '<p style="font-size: .8em">' + d.etapa_estatus +( d.prioridad ? "<br/><small class='label pull-center bg-red'>RECHAZADA</small>" : "" )+'</p>'
                    }
                },
                {
                    "width": "9%",
                    "data": function(d) {
                        return '<p style="font-size: .8em">' + formato_fechaymd(d.fechaCreacion)+'</p>'
                    }
                },
                
                {
                    "orderable": false,
                    "data": function(d) {
                        return '<div class="btn-group-vertical"><button type="button" class="btn btn-sm btn-primary consultar_modal notification" value="' + d.idsolicitud + '" data-value="DEV_BASICA"><i class="fas fa-eye"></i>' + (d.visto == 0 ? '<span class="badge">!</span>' : '') + '</button></div>';
                    }
                }
            ],

            "columnDefs": [{
                    "targets": [4],
                    "orderable": false,
                    "visible": false,
                },
                {
                    "targets": [7],
                    "orderable": false,
                    "visible": false,
                },
                {
                    "targets": [8],
                    "orderable": false,
                    "visible": false,
                },
                {
                    "targets": [9],
                    "orderable": false,
                    "visible": false,
                },
                {
                    "targets": [10],
                    "orderable": false,
                    "visible": false,
                },

                {
                    "targets": [11],
                    "orderable": false,
                    "visible": false,
                },
                {
                    "targets": [12],
                    "orderable": false,
                    "visible": false,
                },
                {
                    "targets": [13],
                    "orderable": false,
                    "visible": false,
                },
                {
                    "targets": [14],
                    "orderable": false,
                    "visible": false,
                },
                {
                    "targets": [15],
                    "orderable": false,
                    "visible": false,
                },
                {
                    "targets": [16],
                    "orderable": false,
                    "visible": false,
                },
                {
                    "targets": [17],
                    "orderable": false,
                    "visible": false,
                },
                {
                    "targets": [18],
                    "orderable": false,
                    "visible": false,
                },
                {
                    "targets": [19],
                    "orderable": false,
                    "visible": false,
                },
                {
                    "targets": [21],
                    "orderable": false,
                    "visible": false,
                },
                {
                    "targets": [22],
                    "orderable": false,
                    "visible": false,
                },
                {
                    "targets": [27],
                    "orderable": false,
                    "visible": true,
                }
            ],
            /*, "ajax":  url + "Devoluciones_Traspasos/tabla_facturas_encurso"*/
            
        });

        $('#tblsolact tbody').on('click', 'td.details-control', function() {
            var tr = $(this).closest('tr');
            var row = table_proceso.row(tr);

            if (row.child.isShown()) {
                row.child.hide();
                tr.removeClass('shown');
                $(this).parent().find('.animacion').removeClass("fa-caret-down").addClass("fa-caret-right");
            } else {

                var informacion_adicional = '<table class="table text-justify">' +
                    '<tr>' +
                    '<td><strong>JUSTIFICACIÓN: </strong>' + row.data().justificacion + '</td>' +
                    '</tr>' +
                    '</table>';

                row.child(informacion_adicional).show();
                tr.addClass('shown');
                $(this).parent().find('.animacion').removeClass("fa-caret-right").addClass("fa-caret-down");
            }
        });

    });

</script>
<?php
    require("footer.php");
?>