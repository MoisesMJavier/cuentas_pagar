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
                        <H3>&nbsp;PAGO PROGRAMADO EN CURSO<i class="far fa-question-circle faq" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" title="Autorizaciones" data-content="Estas son todas las solicitudes de pago a proveedores que han pasado por la primer validación de Dirección General." data-placement="right"></i></h3>                
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <table class="table table-responsive table-bordered table-striped table-hover" id="tabla_pagos_programados">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th style="font-size: .8em"></th>
                                                    <th style="font-size: .8em">#</th>
                                                    <th style="font-size: .8em">PERIODO</th>
                                                    <th style="font-size: .8em">PROVEEDOR</th>
                                                    <th style="font-size: .8em">TOTAL A PAGAR</th>
                                                    <th style="font-size: .8em">FORMA PAGO</th>
                                                    <th style="font-size: .8em">PARCIALIDAD</th>
                                                    <th style="font-size: .8em">TOTAL PAGADO</th>
                                                    <th style="font-size: .8em">TOTAL INTERÉS </th>
                                                    <th style="font-size: .8em">TOTAL CON INTERES</th>
                                                    <th style="font-size: .8em"># PAGO</th>
                                                    <th style="font-size: .8em">FECHA CAPTURA</th>
                                                    <th style="font-size: .8em">FEC TERMINO</th>
                                                    <th style="font-size: .8em">PRX FECHA</th>
                                                    <th style="font-size: .8em">EMPRESA</th>
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

<!-- Modal -->
<div class="modal fade" id="modal_plan" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content modal-sm">
      <form id="form_plan">
        <div class="modal-header bg-green">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Agregar plan al pago programado <b>#</b></h4>
        </div>
        <div class="modal-body">
        <p></p>
        <div class="form-group">
            <label for="plan_excel">Archivo de EXCEL</label>
            <input type="hidden" id="idsol_plan" name="idsolicitud" value="0"/>
            <input type="hidden" id="pago_act" name="pago_act" value="0"/>
            <input type="hidden" id="total_pagos" name="total_pagos" value="0"/>
            <input type="file" class="form-control-file" id="plan_excel" name="plan_excel" required accept=".xlsx">
        </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-success">Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div>
<div id="modal_cant_pprog" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header" style="background-color: green; color: white;">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">MODIFICA LOS DATOS DEL PAGO PROGRAMADO # <b></b></h4>
        </div>
        <form id="form_cant_pprog">
            <input type="hidden" name="idsolicitud" id="idsol_pprog">
            <div class="modal-body">
            <div class="row">
                <div class="col-md-6">
                <label for="fechaini_mod">Fecha inicio pago</label>
                <input type='text' class='form-control' name='fechaini' id='fechaini_mod' required autocomplete="off">
                </div>
                <div class="col-md-6">
                <label for="fechafin_mod">Fecha termino pago</label>
                <input type='text' class='form-control' name='fechafin' id='fechafin_mod' autocomplete="off">
                </div>
                <div class="col-md-6">
                <label for="cant_mod">Cantidad</label>
                <input type='text' class='form-control dinero' name='cantidad' id='cant_mod' required>
                </div>
                <div class="col-md-6 form-group">
                    <label for="per_pago">Periódo de pagos<span class="text-danger">*</span></label>
                    <select class="form-control" id="per_pago" name="per_pago" required="">
                        <option value="">Seleccione un opción</option>
                        <option value="7">SEMANAL</option>
                        <option value="1">MENSUALMENTE</option>
                        <option value="2">BIMESTRAL</option>
                        <option value="3">TRIMESTRAL</option>
                        <option value="4">CUATRIMESTRAL</option>
                        <option value="6">SEMESTRAL</option>
                    </select>
                </div>
                <div class="col-md-12">
                <label for="observaciones_mod">Observaciones</label>
                <textarea class="form-control" name="observaciones" id="observaciones_mod" rows="3" required style="resize:none"></textarea>
                </div>
            </div>
            </div>
            <div class="modal-footer">
                <div class="btn-group">
                <button type="submit" class="btn btn-success cargar_complemento">Actualizar</button>
                </div>
            </div>
        </form>
    </div>
  </div>
</div>
<script>

    var fecha_hoy = new Date();
    fecha_hoy = fecha_hoy.getFullYear()+"-"+(fecha_hoy.getMonth() +1)+"-"+fecha_hoy.getDate();

    $("#tabla_pagos_programados").ready( function(){

        $('#tabla_pagos_programados thead tr:eq(0) th').each( function (i) {
            if( i > 0 && i < $('#tabla_pagos_programados thead tr:eq(0) th').length - 1 ){
                var title = $(this).text();
                $(this).html( '<input type="text" class="form-control" style="width:100px;" placeholder="'+title+'" />' );
                $( 'input', this ).on('keyup change', function () {

                    if (tabla_pagos_programados_nuevo.column(i).search() !== this.value ) {
                        tabla_pagos_programados_nuevo
                        .column(i)
                        .search( this.value )
                        .draw();
                        var total = 0;
                        var index = tabla_pagos_programados_nuevo.rows( { selected: true, search: 'applied' } ).indexes();
                        var data = tabla_pagos_programados_nuevo.rows( index ).data()
                    }
                });
            }
        });

        tabla_pagos_programados_nuevo = $("#tabla_pagos_programados").DataTable({
            dom: 'Brtip',
                "buttons": [{
                    extend: 'excelHtml5',             
                    text: '<i class="fas fa-file-excel"></i> EXPORTAR EXCEL',
                    messageTop: "Lista de solicitudes por autorizar",
                        attr: {
                            class: 'btn btn-success'       
                        },
                    exportOptions: {
                        columns: function (idx, data, node) {/** INICIO FECHA: 17-JUNIO-2025 | PANEL CP A USUARIOS ESPECIFICOS | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                            return idx > 0 && idx < $('#tabla_pagos_programados thead tr:eq(0) th').length - 1;
                        },/** FIN FECHA: 17-JUNIO-2025 | PANEL CP A USUARIOS ESPECIFICOS | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                        format: {
                            header: function (data, columnIdx) { 
                                data = data.replace( '<input type="text" class="form-control" style="width:100px;" placeholder="', '' );
                                data = data.replace( '">', '' )
                                return data;
                            }
                        }
                    }
                }],
                "fnInitComplete": function(){

                    // Enable THEAD scroll bars
                    $('.dataTables_scrollHead').css('overflow', 'auto');

                    // Sync THEAD scrolling with TBODY
                    $('.dataTables_scrollHead').on('scroll', function () {
                        $('.dataTables_scrollBody').scrollLeft($(this).scrollLeft());
                    });                    
                },
                "language":lenguaje,
                "processing": false,
                "pageLength": 10,
                "bAutoWidth": false,
                "bLengthChange": false,
                "ordering" :false,
                "scrollX": true,
                "bInfo": false,
                "searching": true,
                "columns": [{
                    "className": 'details-control',
                    "orderable": false,
                    "data" : null,
                    "defaultContent": '<i class="fas animacion fa-caret-right"></i>'
                },
                {
                    "width": "6%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.idsolicitud+'</p>'
                    }
                },
                {
                    "width": "8%",
                    "data" : function( d ){
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
                                p =  "<small class='label pull-center bg-gray'>SEMESTRAK</small>";
                                break;
                            case '7':
                                p =  "<small class='label pull-center bg-gray'>SEMANAL</small>";
                                break;
                            case '8':
                                p =  "<small class='label pull-center bg-gray'>QUINCENAL</small>";
                                break;
                        }
                        return p += d.intereses != null ? "<br><small class='label pull-center bg-orange'>CRÉDITO</small>" : "";
                    }
                },
                {
                    "width": "12%",
                    "data": function( d ){
                        return  '<p style="font-size: .7em">'+d.nombre + "</p>";
                    }
                },
                {
                    "width": "8%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">$ '+formatMoney( /*d.cantidad * d.ppago*/Number(d.cantidad_confirmada)+(isNaN(d.ppago)?0:Number((d.ppago-d.ptotales)*d.cantidad)) )+'</p>'
                    }
                },
                {
                    "width": "8%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.metoPago+'</p>'
                    }
                },
                {
                    "width": "8%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">$ '+formatMoney( d.cantidad )+'</p>'
                    }
                },  
                {
                    "width": "8%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">$ '+formatMoney( ( d.tparcial != d.cantidad_confirmada ? d.cantidad_confirmada : d.tparcial ) )+'</p>'
                    }
                }, 
                {
                    "width": "8%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">$ '+formatMoney( d.interes )+'</p>'
                    }
                },
                {
                    "width": "8%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">$ '+formatMoney( ( parseFloat( d.tparcial != d.cantidad_confirmada ? d.cantidad_confirmada : d.tparcial ) + Number(d.interes) ) )+'</p>'
                    }
                },
                {
                    "width": "8%",
                    "data" : function( d ){
                        return '<p style="font-size: .9em">'+d.ptotales+' / <small class="label pull-center bg-orange">'+d.ppago+'</small></p>';
                    }
                },
                {
                    "width": "8%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+formato_fechaymd(d.fecreg)+'</p>'
                    }
                },
                {
                    "width": "8%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+(d.fecha_fin ? formato_fechaymd(d.fecha_fin) : "<small class='label pull-center bg-red'>SIN DEFINIR</small>")+'</p>'
                    }
                },
                {
                    "width": "8%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+formato_fechaymd(d.proximo_pago)+'</p>'
                    }
                },
                {
                    "width": "7%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.nemp+'</p>'
                    }
                },
                {
                    "data" : function( d ){

                        if( d.idetapa == "11" ){
                            return "<small class='label pull-center bg-blue'>FINALIZADO</small>"
                        }else{
                            if( moment( d.proximo_pago ).diff( moment( fecha_hoy ), 'days' ) < 2 && d.estatus_ultimo_pago == null ){
                                return "<small class='label pull-center bg-red'>VENCIDO POR "+ ( -1*moment( d.proximo_pago ).diff( moment( fecha_hoy ), 'days' ) )+" DIAS</small>";
                            }else if( moment( d.proximo_pago ).diff( moment( fecha_hoy ), 'days' ) < 2 ){
                                switch( d.estatus_ultimo_pago ){
                                    case "15":
                                        return "<small class='label pull-center bg-orange'>VENCIDO | POR CONFIRMAR PAGO</small>";
                                        break; 
                                    case "1":
                                        return "<small class='label pull-center bg-orange'>VENCIDO | POR DISPERSAR</small>";
                                        break;
                                    case "0":
                                        return "<small class='label pull-center bg-orange'>VENCIDO | SUBIENDO PAGO</small>";
                                        break; 
                                    default:
                                        return "<small class='label pull-center bg-orange'>VENCIDO | PAGO DETENIDO</small>";
                                        break;
                                }
                            }else{
                                return "<small class='label pull-center bg-green'>EN TIEMPO</small>"
                            }
                        }
                    }
                },
                {
                    "width": "12%",
                    "data": function( data ){
                        if( data.idetapa != 11 ){
                            opciones = '<div class="btn-group-vertical" role="group">';
                            opciones += '<button type="button" class="notification btn btn-primary btn-sm consultar_modal" style="" value="'+data.idsolicitud+'" data-value="SOL" title="Ver Solicitud"><i class="far fa-eye"></i>'+(data.visto == 0 ? '</i><span class="badge">!</span>' : '')+'</button>';
                            opciones += '<button type="button" class="btn btn-warning btn-sm modifica_pago" data-value="PROG" value="'+data.idsolicitud+'" data-cantidad="'+data.cantidad+'" data-fecreg="'+data.fecreg+'" data-fecha_fin="'+data.fecha_fin+'" data-programado="'+data.programado+'" title="Modificar cantidad de pago"><i class="fas fa-pencil-alt"></i></button>';
                            opciones += '<button type="button" class="btn btn-sm bg-green agrega_plan" data-value="PROG" value="'+data.idsolicitud+'" data-pagoact="'+data.ptotales+'" data-totalpagos="'+data.ppago+'" data-cant_plan="'+data.cant_plan+'" title="Agregar plan de pago"><i class="far fa-handshake"></i></button>';
                            return  opciones + '</div>';
                        }else{
                            return  '';
                        }

                    } 
                }],
                "ajax": {
                    "url": url + "Historial/ver_programados",
                    "type": "POST",
                    cache: false,
                    "data" : {
                        "etapas" : "0, 30, 2"
                    }
                    /*"data": function( d ){
                        return  $.extend( d, { "nomdepto": $("#cxp1_sel").val(), "idempresa": $("#cxp2_sel").val() } );
                    }*/
                }
            });

            $('#tabla_pagos_programados tbody').on('click', 'td.details-control', function () {
                var tr = $(this).closest('tr');
                var row = tabla_pagos_programados_nuevo.row( tr );

                if ( row.child.isShown() ) {
                    row.child.hide();
                    tr.removeClass('shown');
                    $(this).parent().find('.animacion').removeClass("fa-caret-down").addClass("fa-caret-right");
                }
                else {
                    var informacion_adicional = '<table class="table text-justify">'+
                    '<tr>'+
                    '<td style="font-size: .9em"><strong>CAPTURISTA: </strong>'+row.data().nombre_capturista+'</td>'+
                    '<td style="font-size: .9em"><strong>FORMA DE PAGO: </strong>'+row.data().metoPago+'</td>'+
                    '</tr>'+
                    '<tr>'+
                    '<td style="font-size: .9em" colspan="2"><strong>JUSTIFICACIÓN: </strong>'+row.data().justificacion+'</td>'+
                    '</tr>'+
                    '</table>';

                    row.child( informacion_adicional ).show();
                    tr.addClass('shown');
                    $(this).parent().find('.animacion').removeClass("fa-caret-right").addClass("fa-caret-down");
                }
            });

            $( document ).on("click", ".modifica_pago", function(){
                var fi=$(this).data("fecreg").split("-").reverse().join("\/");
                var ff=$(this).data("fecha_fin")?$(this).data("fecha_fin").split("-").reverse().join("\/"):null;

                $("#modal_cant_pprog .modal-title > b").text($(this).val());
                $("#idsol_pprog").val($(this).val());
                $("#cant_mod").val($(this).data("cantidad")).trigger('mask.maskMoney');

                $("#fechaini_mod").datepicker({
                    todayBtn:  1,
                    autoclose: true,
                    format: 'dd/mm/yyyy'
                }).val( fi );

                $("#fechafin_mod").datepicker({
                    todayBtn:  1,
                    autoclose: true,
                    format: 'dd/mm/yyyy'
                }).val( ff );

                $("#per_pago").val($(this).data("programado"));
                $("#modal_cant_pprog").modal("show");
            });

            $("#form_cant_pprog").submit(function(e) {
                e.preventDefault();
            }).validate({
                submitHandler: function( form ) {
                    if (!confirm('¿Estás seguro de modificar el monto a pagar?')) {
                        return;
                    }else{
                        var data = new FormData( $(form)[0] );
                        var resultado = enviar_post(data, url + "Cuentasxp/cambiar_cant_pprog");
                        if( !resultado.resultado ){
                            alert(resultado.msj);
                        }else{
                            $(form).trigger("reset");
                            alert(resultado.msj);
                            $("#modal_cant_pprog").modal( 'toggle' );
                            tabla_pagos_programados_nuevo.ajax.reload();
                        }
                    }
                }
            });

            $( document ).on("click", ".agrega_plan", function(){
                $("#modal_plan .modal-title > b").text("#"+$(this).val());
                $("#idsol_plan").val($(this).val());
                $("#pago_act").val($(this).data("pagoact"));
                $("#total_pagos").val($(this).data("totalpagos")=="SIN DEFINIR"?-1:($(this).data("totalpagos")));
                $("#modal_plan .modal-body p").text($(this).data("cant_plan")!=0?"Este pago programado ya cuenta con un plan activo":"");
                $("#modal_plan").modal("show");
            });

            $("#form_plan").submit(function(e) {
                e.preventDefault();
            }).validate({
                submitHandler: function( form ) {
                    if (!confirm('¿Estás seguro de agregar este plan a la solicitud?')) {
                        return;
                    }else{
                        var data = new FormData( $(form)[0] );
                        enviar_post2((d)=>{
                            alert(d.msj);
                            if( d.resultado ){
                                $(form).trigger("reset");
                                $("#modal_plan").modal( 'toggle' );
                                tabla_pagos_programados_nuevo.ajax.reload();
                            }
                        },data,url+"Cuentasxp/reg_plan_pagoprog");
                        
                    }
                }
            });
        });

        $(window).resize(function(){
            tabla_pagos_programados_nuevo.columns.adjust();
        });
</script>
<?php
    require("footer.php");
?>