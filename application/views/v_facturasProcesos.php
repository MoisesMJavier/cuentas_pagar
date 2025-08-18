<?php
require("head.php");
require("menu_navegador.php");
?>
<style>
    /* Estilo base para la fila con advertencia */
    .fila-advertencia {
        background-color: #FFC2C2 !important;
        font-weight: bold;
        position: relative;
        text-align: center;
        vertical-align: middle;
        border-radius: 10%;
        animation: pulse 1s ease-out 0.8s 8 normal forwards running ;
    }
    
    /* Icono de advertencia (usando FontAwesome) */
    .icono-advertencia-llamativo {
        position: absolute;
        top: -8px;
        right: -8px;
        background: red;
        color: white;
        font-size: 0.7em;
        border-radius: 50%;
        padding: 4px;
        z-index: 10;
        animation: parpadeo 1s infinite;
    }
    /* Efecto hover para mayor interactividad */
    .fila-advertencia:hover {
        background-color:rgb(255, 120, 120) !important;
        cursor: pointer;
        box-shadow: 0 0 10px rgba(255, 82, 82, 0.2);
    }
    #solPagosPen td {
        vertical-align: middle;
    }
    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.2; }
        100% { opacity: 1; }
    }

    @keyframes parpadeo {
        0% { opacity: 1; }
        50% { opacity: 0.4; }
        100% { opacity: 1; }
    }
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-header">
                    <h3>DIRECCIÓN GENERAL DEVOLUCIONES Y TRASPASOS</h3>
                </div>
                <div class="box-body">
                    <h4>TOTAL POR AUTORIZAR <b id="myText_1"></b></h4>
                    <button class="btn btn-info" onclick="autorizarSeleccionadasPendientes()">Autorizar seleccionadas</button> | Total: $<span id="totpagarPen"></span>
                    <hr>
                    <table class="table table-responsive table-bordered table-striped table-hover" id="solPagosPen">
                        <thead>
                            <tr>
                                <th></th>
                                <th></th>
                                <th>#</th>
                                <th style="font-size: .8em">CLIENTE</th>
                                <th style="font-size: .8em">PROYECTO</th>
                                <th style="font-size: .8em">EMPRESA</th>
                                <th style="font-size: .8em">F SOLICITUD PV</th>
                                <th style="font-size: .8em">CANTIDAD</th>
                                <th style="font-size: .8em">AUTORIZADO</th>
                                <th style="font-size: .8em">MOVIMIENTO</th>
                                <th></th>
                            </tr>
                        </thead>
                    </table>
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
                            <div class="btn-group-vertical" role="group" aria-label="Basic example">
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
<div class="modal fade" id="modal_reubicar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">REGRESAR #<span id="idsol_regre"> </span></h4>
            </div>
            <form id="comentario" method="post" action="#" onkeydown="return event.key != 'Enter';">
                <div class="modal-body">

                    <div class="row">
                        <div class="col-lg-12 form-group">
                            <h5>Seleccione el área al que desea regresar este proceso</h5>
                        </div>
                        <input type="hidden" id="iddocumento" name="iddocumento" />
                        <!-- <div class="col-lg-12 form-group" id="radios"> -->
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

<script type="text/javascript">
    var tabla_getion_pagosP;
    var totaPen = 0;
    var tr;
    $("#solPagosPen").ready(function() {

        $('#solPagosPen').on('xhr.dt', function(e, settings, json, xhr) {

            var total = 0;

            $.each(json.data, function(i, v) {
                total += parseFloat(v.Cantidad);
            });

            var to = formatMoney(total);
            $("#myText_1").html("$ " + to);

        });

        $('#solPagosPen thead tr:eq(0) th').each(function(i) {
            if (i != 0 && i != 1 && i != 10) {
                var title = $(this).text();
                $(this).html('<input type="text" style="width: 100%;" placeholder="' + title + '" />');

                $('input', this).on('keyup change', function() {
                    if (tabla_getion_pagosP.column(i).search() !== this.value) {
                        tabla_getion_pagosP
                            .column(i)
                            .search(this.value)
                            .draw();
                    }

                    var total = 0;
                    var index = tabla_getion_pagosP.rows({
                        selected: true,
                        search: 'applied'
                    }).indexes();
                    var data = tabla_getion_pagosP.rows(index).data();


                    $.each(data, function(i, v) {
                        total += parseFloat(v.Cantidad);
                    });

                    var to1 = formatMoney(total);
                    $("#myText_1").html("$ " + to1);
                });
            }
        });

        tabla_getion_pagosP = $('#solPagosPen').DataTable({
            dom: 'Brtip',
            buttons: [
                {
                    extend: 'excelHtml5',             
                    text: '<i class="fas fa-file-excel"></i>',
                    messageTop: "DEVOLUCIONES/TRASPASOS",
                    attr: {
                        class: 'btn btn-success'       
                    },
                    exportOptions: {
                        format:{    
                            header:  function (data, columnIdx) {
                                return data.replace( '<input type="text" style="width: 100%;" placeholder="', '' ).split('"')[0];
                            }
                        },
                        columns: [ 2, 3, 4, 5, 6, 7, 8, 9 ]
                    }
                }
            ],
            "language": lenguaje,
            "processing": false,
            "pageLength": 20,
            "bAutoWidth": false,
            "bLengthChange": false,
            "bInfo": false,
            "scrollX": false,
            "columns": [{
                    "width": "1%",
                    "className": 'details-control',
                    "orderable": false,
                    "data": null,
                    "defaultContent": '<i class="fas animacion fa-caret-right"></i>'
                },
                {
                    "width": "1%",
                    "orderable": false,
                    "data": function(d) {
                        //             if(d.ETAPA !=30){
                        return '<input type="checkbox" class="selecionado">';
                        //             }else{
                        //                 return '';
                        //             }
                    }
                },
                {
                    "width": "5%",
                    "orderable": false,
                    "data": function(d) {
                        return '<p style="font-size: .8em">' + d.idsolicitud + '</p>';
                    }
                },
                {
                    "width": "20%",
                    "orderable": false,
                    "data": function(d) {
                        return '<p style="font-size: .8em">' + d.Proveedor + '</p>';
                    }
                },
                {
                    "width": "12%",
                    "orderable": false,
                    "data": function( d ){
                        p = "" ;

                        switch (d.programado) {
                            case '1':
                                p =  "<small class='label pull-center bg-gray'>MENSUAL</small>";
                                break;
                            case '2':
                                p =  "<small class='label pull-center bg-gray'>BIMESTRAL</small>";
                                break;
                            case '3':
                                p =  "<small class='label pull-center bg-gray'>TRIMESTRAL</small>";
                                break;
                            case '4':
                                p =  "<small class='label pull-center bg-gray'>CUATRIMESTRAL</small>";
                                break;
                            case '6':
                                p =  "<small class='label pull-center bg-gray'>SEMESTRAL</small>";
                                break;
                            case '7':
                                p =  "<small class='label pull-center bg-gray'>SEMANAL</small>";
                                break;
                            case '8':
                                p =  "<small class='label pull-center bg-gray'>QUINCENAL</small>";
                                break;
                        }
                        return '<p style="font-size: .7em">' + d.proyecto + p + '</p>';
                    }
                },
                {
                    "width": "5%",
                    "orderable": false,
                    "data": function(d) {
                        return '<p style="font-size: .8em">' + d.abrev + '</p>';
                    }
                },
                {
                    "width": "8%",
                    "orderable": false,
                    "data": function(d) {
                        return '<p style="font-size: .8em">' + d.FECHAFACP + '</p>';
                    }
                },
                {
                    "width": "8%",
                    "orderable": false,
                    "data": function(d) {
                        return '<p style="font-size: .8em">$ ' + (d.esParcialidad === 'S' ? d.numeroPagos > 0 ?  ( formatMoney(d.Cantidad)) + " / $" + formatMoney(d.montoParcialidad) +' '+ d.moneda + '<br><small class="label pull-center bg-blue">TOTAL / PARCIALIDAD</small>':  formatMoney(d.Cantidad)  :  formatMoney(d.Cantidad) + " " + d.moneda)+'</p>';
                    }
                },
                {
                    "width": "8%",
                    "orderable": false,
                    "data": function(d) {
                        return '<p style="font-size: .8em">$ ' + formatMoney(d.pa) + " " + d.moneda + '</p>';
                    }
                },
                {
                    "width": "10%",
                    "orderable": false,
                    "data": function(d) {
                        return '<p style="font-size: .8em">' + d.Departamento + '</p>';
                    }
                },
                {
                    "width": "8%",
                    "data": function(d) {

                        var opciones = '<div class="btn-group-vertical">';

                        opciones += '<button id = "verSol" name="verSol" class="btn btn-instagram consultar_modal notification btn-sm" value="' + d.ID + '" data-value="DEV_BASICA" data-toggle="tooltip" data-placement="bottom"  title="Ver Solicitud"><i class="fas fa-eye" ></i>' + (d.visto == 0 ? '</i><span class="badge">!</span>' : '') + '</button>';
                        opciones += '<button id="rechazado" name="rechazado" value="' + d.ID + '"  class="btn btn-danger btn-recharzarPago btn-sm" title="Declinar Pago"><i class="fas fa-times"></i></button>';

                        return opciones + "</div>";
                    },
                    "orderable": false
                }
            ],
            "createdRow": function(row, data, dataIndex){
                if (data.idproceso == "30") {
                    let planPagos = MyLib.montoTotalSolicitudParcialidad(data.Cantidad, data.numeroPagos, data.montoParcialidad, data.programado, moment("'"+data.FECHAFACP+"'", 'DD/MMM/YYYY').format('YYYY-MM-DD') );
                    let pagoParaAutorizarActual = parseInt(data.pagosAutorizados) + 1;
                    
                    if( (data.numeroPagos <= 0) || (data.numeroPagos == 1 && parseFloat(data.Cantidad) <= parseFloat(data.montoParcialidad) ) || (parseFloat(data.Cantidad) < parseFloat(planPagos.montoTotalPagar)) ){
                        $(row).addClass('fila-advertencia');
                        $(row).find('.avanzar, .avanzar_contable').attr('disabled', true);
                        // Tooltip para mejor UX (requiere jQuery UI o Bootstrap)
                        $(row).find('.icono-advertencia').tooltip({
                            placement: 'right'
                        });
                        if (data.numeroPagos <= 0) {
                            $(row).attr('title','Advertencia: Favor de revisar la solicitud, ya que aparentemente no cuenta con un número de pagos definido.');
                            $(row).find('.consultar_modal i')
                                .prepend(`<span class="icono-advertencia-llamativo" 
                                                title="Advertencia: Favor de revisar la solicitud, ya que aparentemente no cuenta con un número de pagos definido.">
                                            <i class="fas fa-exclamation-triangle"></i>
                                          </span>`);
                        }else if (data.numeroPagos == 1 && parseFloat(data.Cantidad) <= parseFloat(data.montoParcialidad)) {
                            $(row).attr('title','Favor de revisar la solicitud. Se indica pago en parcialidades, pero se programó en una sola exhibición y el monto de la parcialidad excede el importe registrado a devolver.');
                            $(row).find('.consultar_modal i')
                                .prepend(`<span class="icono-advertencia-llamativo" 
                                                title="Advertencia: Favor de revisar la solicitud. Se indica pago en parcialidades, pero se programó en una sola exhibición y el monto de la parcialidad excede el importe registrado a devolver.">
                                            <i class="fas fa-exclamation-triangle"></i>
                                          </span>`);
                        }else if( parseFloat(data.Cantidad) < parseFloat(planPagos.montoTotalPagar) ){
                            $(row).attr('title','Advertencia: Favor de revisar la solicitud. El monto calculado según las parcialidades excede el total registrado a devolver.');
                            $(row).find('.consultar_modal i')
                                .prepend(`<span class="icono-advertencia-llamativo" 
                                                title="Advertencia: Favor de revisar la solicitud. El monto calculado según las parcialidades excede el total registrado a devolver.">
                                            <i class="fas fa-exclamation-triangle"></i>
                                          </span>`);
                        }else if(parseFloat(data.montoParcialidad) != parseFloat(planPagos.tabla_pagos[pagoParaAutorizarActual])){
                            $(row).attr('title','Advertencia: Favor de revisar la solicitud. El monto a autorizar difiere del indicado en el plan de pagos.');
                            $(row).find('.consultar_modal i')
                                .prepend(`<span class="icono-advertencia-llamativo" 
                                                title="Advertencia: Favor de revisar la solicitud. El monto a autorizar difiere del indicado en el plan de pagos.">
                                            <i class="fas fa-exclamation-triangle"></i>
                                          </span>`);
                        }
                        
                    }
                }
            },
            columnDefs: [{
                orderable: false,
                className: 'select-checkbox',
                targets: 1,
                'searchable': false,
                'className': 'dt-body-center',
                'render': function(data, type, full, meta) {
                    return '<input type="checkbox" name="id[]" value="' + full.ID + '">';
                },
                select: {
                    style: 'os',
                    selector: 'td:first-child'
                },
            }],
            "ajax": url + "Devoluciones_Traspasos/tablaDGprocesos"

        });

        $('#solPagosPen tbody').on('click', 'td.details-control', function() {
            var tr = $(this).closest('tr');
            var row = tabla_getion_pagosP.row(tr);

            if (row.child.isShown()) {
                row.child.hide();
                tr.removeClass('shown');
                $(this).parent().find('.animacion').removeClass("fa-caret-down").addClass("fa-caret-right");
            } else {

                var informacion_adicional = '<table class="table text-justify">' +
                    '<tr>' +
                    '<td style="font-size: .8em"><strong>JUSTIFICACIÓN: </strong>' + row.data().Observacion + '</td>' +
                    '</tr>' +
                    '</table>';

                row.child(informacion_adicional).show();
                tr.addClass('shown');
                $(this).parent().find('.animacion').removeClass("fa-caret-right").addClass("fa-caret-down");
            }
        });

        $("#formularioPP").submit(function(e) {
            e.preventDefault();
        }).validate({
            submitHandler: function(form) {
                $('#modalDeclinaPago').modal('show');
                idSol = $(".btn-modalDECLINOPago").val();
                var observacion = $("textarea#motivoDeclinadaPago").val();
                $.ajax({
                    url: url + "DireccionGeneral/PagoDeclinado",
                    type: "POST",
                    dataType: "json",
                    data: {
                        id_sol: idSol,
                        Obervacion: observacion
                    },
                    error: function(data) {

                    },
                    complete: function(data) {
                        $("#modalDeclinaPago").modal("toggle");
                        tabla_getion_pagosP.ajax.reload();
                    }
                });

            }
        });

        $('#solPagosPen').on("click", ".btn-recharzarPago", function(e) {
            tr = $(this).closest('tr');
            var row = tabla_getion_pagosP.row(tr).data();
            var idso_back = $(this).val();
            $('#idsol_regre').text(idso_back);
            $("#modal_reubicar").modal();
            // $("#idsol_regre").html("");
            $.post(url + "Devoluciones_Traspasos/conusltar_areas_proceso_menor", {
                idsolicitud: $(this).val()
            }, function(datos) {
                data = JSON.parse(datos);
                console.log(data.data);
                if (data.data) {
                    $('#radios').append('<option value="">Seleccione una opción</option>');
                    $.each(data.data, function(i, v) {
                        // $('#radios').append('<input type="hidden" name="idsol" value="' + v.solicitud + '"/> <input type="radio" id="' + v.orden + '" name="rol_di"  data-value="' + v.idetapa + '" value="' + v.orden + '"> <span for="html">' + v.nombre + '</span><br>');
                        $('#radios').append('<option  data-value="' + v.solicitud + '" value="' + v.orden + '">' + v.nombre + '</option>');
                    });

                }

            });
            $("#radios").html("");
            $("#text_comentario").html("");
            

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
                    // console.log(respuesta);
                    if (data != false) {
                        $("#modal_reubicar").modal("toggle");
                        var tr = $(this).closest('tr').remove();

                        tabla_getion_pagosP.ajax.reload(null, false);
                    }

                }, data, url + 'Devoluciones_Traspasos/regresar_sol_area');
            }
        });
        // $('#solPagosPen').on( "click", ".btn-recharzarPago", function( e ){
        //     tr = $(this).closest('tr');
        //     var row = tabla_getion_pagosP.row( tr ).data();
        //     $(".btn-modalDECLINOPago").val( row.ID );
        //     $("#motivoDeclinadaPago").val("");
        //     $('#modalDeclinaPago').modal();

        //  });    

        $('#solPagosPen').on('click', 'input[type="checkbox"]', function() {
            tr = $(this).closest('tr');

            var row = tabla_getion_pagosP.row(tr).data();

            if (row.pa == 0) {
                row.idproceso == 30 && row.esParcialidad == "S" /**@author Dante Aldair Guerrero Aldana <coordinador6.desarrollo@ciudadmaderas.com> * Cambio para las solicitudes tipo parcialidades * Fecha: 18-10-2024 */
                    ? row.pa = parseFloat(row.montoParcialidad)
                    : row.pa = (row.Cantidad - row.Autorizado);

                tabla_getion_pagosP.row(tr).data(row);
                totaPen += parseFloat(row.pa);
                tr.children().eq(1).children('input[type="checkbox"]').prop("checked", true);
            } else {

                totaPen -= parseFloat(row.pa);
                row.pa = 0;
                tabla_getion_pagosP.row(tr).data(row);

            }

            $("#totpagarPen").html(formatMoney(totaPen));
        });
    });

    function autorizarSeleccionadasPendientes() {
        if ( $(tabla_getion_pagosP.$('input[type="checkbox"]:checked')).length == 0) {
            alert('No hay monto autorizado');
        } else if (window.confirm('Se pagará el total autorizado.\nEl total es de $ ' + formatMoney(totaPen) + ' ¿Estás de acuerdo?')) {

            var apagar = [];

            $(tabla_getion_pagosP.$('input[type="checkbox"]:checked')).each(function() {
                tr = $(this).closest('tr');
                var row = tabla_getion_pagosP.row(tr);
                apagar.push([row.data().ID, row.data().esParcialidad == 'S' ? row.data().montoParcialidad : row.data().pa]);
            });

            $.post(url + "Devoluciones_Traspasos/PagoProcesos", {
                jsonApagar: JSON.stringify(apagar)
            }).done(function(data) {
                if (data[0]) {
                    totaPen = 0;
                    $("#totpagarPen").html(formatMoney(0));
                    tabla_getion_pagosP.ajax.reload();
                }
            }).fail(function() {
                alert("HA OCURRIDO UN ERROR, INTENTE MAS TARDE");
            });
        }
    }
</script>
<?php
require("footer.php");
?>