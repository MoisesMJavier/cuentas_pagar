<?php
    require("head.php");
    require("menu_navegador.php");
?> 
<style>
    .encabezado_tabla_input:focus, .encabezado_tabla_input:hover {
        border-bottom: 2px solid #000000ab; /* Cambia el color del borde inferior al enfocarse */
    }
    .encabezado_tabla_input[placeholder]:empty:before {
        content: attr(placeholder);
    }
    .encabezado_tabla_input[placeholder]:empty:focus:before {
        content: "";
    }
    .encabezado_tabla_input {
        width: 100%;
        display: inline-block;
        cursor: text;
        font-size: .8em;
        text-align: center;
        border: none;
        outline: none;
        padding: 0px 0px;
        /* white-space: nowrap; */
    }
    .encabezado_tabla {
        width: 100%;
        display: inline-block;
        cursor: text;
        font-size: .8em;
        text-align: center;
        border: none;
        outline: none;
        padding: 0px 0px;
        /* white-space: nowrap; */
    }
    p small.etq {
        display: inline-block;
        padding: 3px 5px 3px;
        font-size: 0.7em;
        font-weight: 700;
        color: #fff;
        text-align: center;
        max-width: 75%;
        border-radius: 10px;
        line-height: 11px;
    }
    td p {
        text-align: center;
    }
</style>
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <div class="box">
                    <div class="box-header with-border">
                        <h3>FACTURAS / COMPLEMENTOS PENDIENTES </h3>
                        <div class="row">
                            <div class="col-lg-12">
                                <h4 id="totalxaut"></h4>
                            </div>
                        </div>                               
                    </div>
                    <div class="box-body">
                        <div class="col-lg-12">
                            <div class="col-lg-4"> <!-- Reducir el tamaño de la tabla para evitar la barra de desplazamiento horizontal -->
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fas fa-calendar-alt"></i> <b>INICIO</b></span>
                                    <input class="form-control fechas_filtro from mr-2" type="text" id="fecInicial" name="fechaInicial" maxlength="10" autocomplete="off"/>
                                </div>
                            </div>
                            <div class="col-lg-4"> <!-- Reducir el tamaño de la tabla para evitar la barra de desplazamiento horizontal -->
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fas fa-calendar-alt"></i> <b>FIN</b></span>
                                    <input class="form-control fechas_filtro to ml-2" type="text" id="fecFinal" name="fechaFinal" maxlength="10" autocomplete="off"/>
                                </div>
                            </div>
                            <table class="table table-striped" id="tblsolfin" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th></th>              <!--Col. 0-->
                                        <th class="fac_fal" style="font-size: .8em"># SOLICITUD</th>            <!--Col. 1-->
                                        <th class="fac_fal" style="font-size: .8em">FOLIO</th>                  <!--Col. 2-->
                                        <th class="fac_fal" style="font-size: .8em">PROVEEDOR</th>              <!--Col. 3-->
                                        <th class="fac_fal" style="font-size: .8em">F FACTURA</th>              <!--Col. 4-->
                                        <th class="fac_fal" style="font-size: .8em">EMPRESA</th>                <!--Col. 5-->
                                        <th class="fac_fal" style="font-size: .8em">F DISP</th>                 <!--Col. 6-->
                                        <th class="fac_fal" style="font-size: .8em">METODO PAGO</th>            <!--Col. 7-->
                                        <th class="fac_fal" style="font-size: .8em">FECHA PAGO</th>             <!--Col. 8-->
                                        <th class="fac_fal" style="font-size: .8em">CANTIDAD</th>               <!--Col. 9-->
                                        <th class="fac_fal" style="font-size: .8em">COMPROBADO</th>             <!--Col. 10-->
                                        <th class="fac_fal" style="font-size: .8em">DEPARTAMENTO</th>           <!--Col. 11-->
                                        <th class="fac_fal" style="font-size: .8em">DEPARTAMENTO</th>           <!--Col. 12-->
                                        <th class="fac_fal" style="font-size: .8em">NOTA</th>                   <!--Col. 13-->
                                        <th class="fac_fal" style="font-size: .8em">FOLIO FISCAL (UUID)</th>    <!--Col. 14-->
                                        <th class="fac_fal" style="font-size: .8em">CAPTURISTA</th>             <!--Col. 15-->
                                        <th class="fac_fal" style="font-size: .8em">JUSTIFICACIÓN</th>          <!--Col. 16-->
                                        <th></th>                                                               <!--Col. 17-->
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div><!--End tab content-->
                </div><!--end box-->
            </div>
        </div>
    </div>
</div>
 
<div id="factura_complemento" class="modal fade modal-alertas" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">COMPLEMENTO</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <form id="complemento_facturas">
                        <div class="col-lg-12 form-group">
                            <!--div class="row" id="row_cambio">
                                <div class="col-lg-12 form-group">
                                    <label for="tipocam">TIPO DE CAMBIO</label>
                                    <input type="text" class="form-control" id="tipocam" name="tipocam" placeholder="Tipo de cambio" value="" required>
                                </div>
                            </div-->
                            <label>DOCUMENTO XML</label>                            
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="input-group">
                                        <input type="file" class="form-control" id="complemento" accept="text/xml" required>                                
                                        <div class="input-group-btn">
                                            <button type="submit" class="btn btn-danger cargar_complemento"><i class="fas fa-upload"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>  
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-check">
                                        <input type="checkbox" name="validar_checkbox" class="form-check-input validar_checkbox" id="validar_checkbox">
                                        <label class="form-check-label validar_checkbox" for="validar_checkbox">Cargar sin validar</label>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-check">
                                        <input type="checkbox" name="cancelar_checkbox" class="form-check-input validar_checkbox" id="cancelar_checkbox">
                                        <label class="form-check-label validar_checkbox" for="cancelar_checkbox">Cancelar facturas</label>
                                    </div>
                                </div>
                            </div>                            
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para mostrar las facturas ingresadas en el sistema -->
<div id="alert-modal" class="modal fade" role="dialog">
    <div class="modal-dialog"  role="document">
        <div class="modal-content">
            <div class="modal-header bg-green">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">REGISTROS COMPROBADOS</h4>
            </div>
            <div class="modal-body">
                <P>Estos son los folios fiscales que incluía el complemento.</P>
                <div class="continer">
                    <div class="table-responsive">
                        <table class="table modal-data" style="font-size: .9em; text-align: center;">
                        <!-- Datos -->
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>

    var documento_xml = null;
    var link_post = "";
    var idsolicitud = 0;
    var rol = `<?= $this->session->userdata("inicio_sesion")['rol'] ?>`;

    $('input#tipocam').keypress(function (event) {
        if ((event.which < 48 || event.which > 57) && event.which != 46) {
            return false;
        }
        //alert($(this).val());
    });

    var table_complemento;
    var table_pagos_sin_factura;
    var id;
    var link_complentos;
    var titulos_encabezado = [];
    var num_colum_encabezado = [];
    //Dante Aldair
    var reziseInfo;

    $("#complemento_facturas").submit( function(e) {
        e.preventDefault();
    }).validate({
        submitHandler: function( form ) {
            var data = new FormData();
            data.append("id", id);
            data.append("xmlfile", $("#complemento")[0].files[0]);
            data.append("tpocam", $("#tipocam").val());
            data.append("validarxml", $("#validar_checkbox:checked").val() ? 1 : 0);
            data.append("cancelarxml", $("#cancelar_checkbox:checked").val() ? 1 : 0);

            $("#factura_complemento .form-control").val( '');
            $("#factura_complemento").modal( 'toggle');

            $.ajax({
                url: url + link_complentos,
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                method: 'POST',
                type: 'POST', // For jQuery < 1.9
                success: function(data){
                    if( data.respuesta[0] ){

                        

                        if( data.deuda > 0.05 ){
                            alert(data.mensaje[0]+"\n \n"+data.respuesta[1] );
                            
                        }else if( data.uuids && data.uuids.length > 0 ){

                            $('#alert-modal table.modal-data').html("");
                            $('#alert-modal table.modal-data').append(`<tr><th>SOLICITUD</th><th>FOLIO FISCAL</th></tr>`);

                            $.each( data.uuids, function( i, v ){
                                $('#alert-modal table.modal-data').append(`<tr><td>${v.idsolicitud}</td><td>${v.uuid}</td></tr>`);
                            });
                            

                            $("#alert-modal").modal();
                        }
                    
                        table_complemento.ajax.reload();
                        
                    }else{
                        alert( data.respuesta[1] );
                    }
                },error: function( ){
                    
                }
            });
        }
    });

    $(document).ready(function() {
        $("#tblsolfin").ready( function () {
            $('#tblsolfin thead tr:eq(0) th').each( function (i) {

                if( i != 0 && i < $('#tblsolfin thead tr:eq(0) th').length - 1 ){
                    var title = $(this).text();
                    titulos_encabezado[i] = title;
                    num_colum_encabezado.push(i);
                    $(this).empty();
                    //$(this)[0].insertAdjacentHTML("beforeend", `<div class="encabezado_tabla" contenteditable="true" id="enc_${title.match(/[a-zA-Z]+/g).join("").toLowerCase()}_${i}" placeholder="${title}"></div>` );
                    $(this).html(`<input type="text" class="encabezado_tabla_input" placeholder="${title}">`);
                    // $(this).html(`<input type="text" class="encabezado_tabla" id="enc_${title.match(/[a-zA-Z]+/g).join("").toLowerCase()}_${i}" placeholder="${title}">`);
                    $(this).find('input.encabezado_tabla_input').click(function(event) {
                        event.stopPropagation();
                    });

                    // Habilita el modo de edición cuando se presiona el botón del mouse en el div
                    $(this).find('div.encabezado_tabla').mousedown(function(event) {
                        event.stopPropagation();
                        $(this).focus();
                        document.execCommand('selectAll', true, null);
                    });

                    $(this).find('div.encabezado_tabla').keydown(function(event) {
                        
                        let keyAprov = [39, 37, 36, 35, 17]
                        if (event.shiftKey && keyAprov.includes(event.keyCode) ) { // Flecha izquierda o derecha
                            var range = document.createRange();
                            range.selectNodeContents(this);
                            var selection = window.getSelection();
                            selection.removeAllRanges();
                            selection.addRange(range);
                            event.preventDefault(); // Evita que el cursor cambie de posición
                        }
                    });


                    $(this).find('input.encabezado_tabla_input').on('keyup change', function() {
                        if (table_complemento.column(i).search() !== $(this).val()) {
                            table_complemento
                                .column(i)
                                .search($(this).val())
                                .draw();
                            
                            var total = 0;
                            var index = table_complemento.rows({ selected: true, search: 'applied' }).indexes();
                            var data = table_complemento.rows(index).data();
                            
                            $.each(data, function(i, v) {
                                total += parseFloat(v.cantidad);
                            });
                            
                            var formattedTotal = formatMoney(total);
                            $("#totalxaut").html("<b>Total por comprobar:</b> $"+formattedTotal);
                        }
                    });
                    // $(this).html( `<div class="encabezado_tabla" contenteditable="true" id="enc_${title.match(/[a-zA-Z]+/g).join("").toLowerCase()}_${i}" placeholder="${title}"></div>` );
                }

            });

            table_complemento = $('#tblsolfin').DataTable({
                dom: 'Brtip',
                "buttons": [
                    {
                        extend: 'excel',             
                        text: '<i class="fas fa-file-excel"></i>',
                        messageTop: "FACTURAS FALTANTES",
                        attr: {
                            class: 'btn btn-success'       
                        },
                        exportOptions: {
                            columns: num_colum_encabezado,
                            format:{    
                                header:  function (data, columnIdx) {
                                    return " " + titulos_encabezado[columnIdx] + " ";
                                }
                            }
                        }
                    }
                ],
                "ordering": true,
                "language" : lenguaje,
                "processing": false,
                "pageLength": 10,
                "bLengthChange": false,
                "bInfo": false,
                "scrollX": true,
                "autoWidth": true,
                "order": [1, "asc"], /** FECHA: 07-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                "initComplete": function(settings, json) {
                    sumar_total();
                    $('.dt-buttons').attr('style','float: right !important; margin-right: 15px;');
                    reziseInfo = table_complemento.rows().data().toArray();
                },
                "columns": [
                    {
                        "className": 'details-control sorting_disabled',
                        "orderable": false,
                        "data" : null,
                        "defaultContent": '<i class="fas animacion fa-caret-right"></i>'
                    },
                    {
                        "data" : function( d ){
                            return '<p style="font-size: .6em">'+d.idsolicitud+'</p>'
                        }
                    },
                    {
                        "data" : function( d ){
                            if( d.foliofac ){
                                return '<p style="font-size: .6em"> '+d.foliofac+( d.folio_fiscal ? "/" + (d.folio_fiscal).substr(d.folio_fiscal.length - 5) : '' )+'</p>';
                            }else{
                                return '<p style="font-size: .6em"><small class="label pull-center bg-red">FACTURA FALTANTE</small></p>';
                            }
                        }
                    },
                    {
                        "data" : function( d ){
                            return '<p style="font-size: .6em"> '+d.nombre+'</p>';
                        }
                    },
                    {
                        "data" : function( d ){
                            return '<p style="font-size: .6em"> '+formato_fechaymd(d.fecha_factura)+'</p>';
                        }
                    },
                    {
                        "data" : function( d ){
                            return '<p style="font-size: .6em"> '+d.abrev+'</p>';
                        }
                    },
                    {
                        "data" : function( d ){
                            return '<p style="font-size: .6em"> '+( d.fechaDis ? formato_fechaymd(d.fechaDis) : '-' )+'</p>'
                        }
                    },
                    {
                        "data" : function( d ){
                            return '<p style="font-size: .6em"> '+d.metoPago+'</p>';
                        }
                    },
                    { 
                        "data" : function( d ){
                            return d.fecha_pago ? '<p style="font-size: .7em">' + formato_fechaymd(d.fecha_pago) + '</p>': '<p style="font-size: .7em"><small class="etq pull-center bg-blue"> SIN FECHA DE PAGO</small></p>';
                        }
                    },
                    {
                        "data" : function( d ){
                            return '<p style="font-size: .6em">$ '+ formatMoney(d.cantidad)+'</p>'
                        }
                    },
                    {
                        "data" : function( d ){
                            return '<p style="font-size: .6em">'+( d.tcomprobado > 0 ? '$ '+ formatMoney(d.tcomprobado)+' '+d.moneda : '$' + formatMoney(0)+' '+d.moneda ) +'</p>'
                        }
                    },
                    {
                        "data" : function( d ){
                            var p = '<p style="font-size: .6em"> '+d.nomdepto+'</p>';
                            if( d.impcomp ){
                                return p += '<p style="font-size: .7em"><small class="etq pull-center bg-blue"> FALTA COMPLEMENTO(S) COMPROBADO '+( formatMoney( d.impcomp ) )+'</small></p>';
                            }else if( d.folio_fiscal && d.tcomprobado === null ){
                                return p += '<p style="font-size: .7em"><small class="etq pull-center bg-blue"> FALTA COMPLEMENTO</small></p>';
                            }else if( d.folio_fiscal && d.tcomprobado ){
                                return p += '<p style="font-size: .7em"><small class="etq pull-center bg-red">FACTURA PENDIENTE POR: '+( formatMoney( d.cantidad - d.tcomprobado ) )+'</small></p>';
                            }else{
                                return p += '<p style="font-size: .7em"><small class="etq pull-center bg-red">FACTURA FALTANTE</small></p>';
                            }
                        }
                    },
                    {
                        "data" : function( d ){
                            return '<p style="font-size: .6em"> '+d.nomdepto+'</p>';
                        }
                    },
                    {
                        "data" : function( d ){
                            var p = ""
                            if( d.impcomp ){
                                return p += '<p style="font-size: .6em"><small class="etq pull-center bg-blue"> FALTA COMPLEMENTO POR COMPROBAR '+( formatMoney( d.cantidad - d.impcomp ) )+'</small></p>';
                            }else if( d.folio_fiscal && d.tcomprobado === null ){
                                return p += '<p style="font-size: .6em"><small class="etq pull-center bg-blue"> FALTA COMPLEMENTO</small></p>';
                            }else if( d.folio_fiscal && d.tcomprobado ){
                                return p += '<p style="font-size: .6em"><small class="etq pull-center bg-red">PENDIENTE POR COMPROBAR '+( formatMoney( d.cantidad - d.tcomprobado ) )+'</small></p>';
                            }else{
                                return p += '<p style="font-size: .6em"><small class="etq pull-center bg-red">FACTURA FALTANTE</small></p>';
                            }
                        }
                    },
                    {
                        "data" : function( d ){
                            return '<p style="font-size: .6em"> '+( d.folio_fiscal ? d.folio_fiscal : '' )+'</p>';
                        }
                    },
                    {
                        "data" : function( d ){
                            return '<p style="font-size: .6em"> '+d.nombre_capturista+'</p>'
                        }
                    },
                    {
                      
                        "data" : function( d ){
                            return '<p style="font-size: .6em"> '+d.justificacion+'</p>'
                        }
                    },
                    {
                        "data": function( d ){
                            var opciones = '<div class="btn-group-vertical">';
                            
                            opciones += '<button type="button" class="btn btn-sm btn-primary consultar_modal notification"  tittle="Consultar Información" value="'+d.idsolicitud+'" data-value="BAS"><i class="fas fa-eye"></i>'+(d.visto == 0 ? '</i><span class="badge">!</span>' : '')+'</button>';
                            opciones += '<button type="button" class="btn btn-sm btn-warning subir_factura_pago" tittle="Cargar Factura / Complemento faltante" value="'+d.idsolicitud+'|'+d.moneda+'"><i class="fas fa-file-export"></i></button>';
                            
                            return opciones += '</div>';
                        },
                        "orderable": false
                    }
                ],
                "columnDefs": [
                    {
                        "targets": [8],
                        "orderable": true,
                        "visible": true,
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
                        "targets": [16],
                        "orderable": false,
                        "visible": false,
                    }
                ],
                "ajax":  url + "Complementos_cxp/tabla_pagos_sin_complemento"
            });

            // if(rol == 'CP')
            //     table_complemento.column(8).visible(true);

            $("#tblsolfin tbody").on('click', '.subir_factura_pago', function(){

                id = $(this).val().split('|')[0];
                /*
                    if($(this).val().split('|')[1] != 'MXN'){
                        $("#row_cambio").show();
                        $("#tipocam").get(0).type = 'text';
                        $("#tipocam").val("");
                    }else{
                        $("#row_cambio").hide();
                        $("#tipocam").get(0).type = 'hidden';
                        $("#tipocam").val("1");
                    }
                */
                link_complentos = "Complementos_cxp/cargaxml_complemento";

                $("#factura_complemento").modal();
                if(rol == 'CP'){
                    $(".validar_checkbox").show();
                }else{
                    $(".validar_checkbox").hide();
                }
                
            });

            $('#tblsolfin tbody').on('click', 'td.details-control', function () {
                var tr = $(this).closest('tr');
                var row = table_complemento.row( tr );
        
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
            
        });

        $('[data-toggle="tooltip"]').tooltip();

    });    
        
    $.fn.dataTableExt.afnFiltering.push(
        function( oSettings, aData, iDataIndex ) {
            if( oSettings.nTable.getAttribute('id') == "tblsolfin" && ($("#fecInicial").val()!='' || $("#fecFinal").val()!= '' )){
                var mes = new Map([
                    ["Ene","01"],
                    ["Feb","02"],
                    ["Mar","03"],
                    ["Abr","04"],
                    ["May","05"],
                    ["Jun","06"],
                    ["Jul","07"],
                    ["Ago","08"],
                    ["Sep","09"],
                    ["Oct","10"],
                    ["Nov","11"],
                    ["Dic","12"],
                ]);
                
                
                var iFini = document.getElementById('fecInicial').value;
                var iFfin = document.getElementById('fecFinal').value;
                var iStartDateCol = 4;
                var iEndDateCol = 4;
                
                iFini=iFini.substring(6,10) + iFini.substring(3,5)+ iFini.substring(0,2);
                iFfin=iFfin.substring(6,10) + iFfin.substring(3,5)+ iFfin.substring(0,2);
                                
                //28/Nov/2019
                //01 2 345 6 7890
                
                var datofini=aData[iStartDateCol].trim().substring(7,11)+mes.get( aData[iStartDateCol].trim().substring(3,6))+aData[iStartDateCol].trim().substring(0,2);
                var datoffin=aData[iEndDateCol].trim().substring(7,11) + mes.get(aData[iEndDateCol].trim().substring(3,6))+aData[iEndDateCol].trim().substring(0,2);

                //console.log("Fecha sel: "+iFini+" "+iFfin+" Fecha tbl: "+datofini);
                
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
            }else
                return true;
        }
    );
    
    $('.fechas_filtro').datepicker({
          format: 'dd/mm/yyyy',
          autoclose: true
    });
    
    $('#fecInicial').change( function() { 
        table_complemento.draw();
        sumar_total();
    }).on('dp.change', function (selected) {
        end.data("DateTimePicker").minDate(selected.date);
    });
    
    $('#fecFinal').change( function() {  table_complemento.draw(); sumar_total(); } );
    
    function sumar_total(){
        var total = 0;
       var index = table_complemento.rows( { selected: true, search: 'applied' } ).indexes();
       var data = table_complemento.rows( index ).data();
       $.each(data, function(i, v){
           total += parseFloat(v.cantidad.replace(/[^0-9.-]+/g,""));
           //console.log(formatMoney(total.toString()));
         });
       var to1 = formatMoney(total);
       $("#totalxaut").html("<b>Total por comprobar:</b> $"+to1);
    }

    $(window).resize(function(){
        table_complemento.clear().draw();
        table_complemento.rows.add(reziseInfo);
        table_complemento.columns.adjust().draw();
    });

    $('.sidebar-toggle').click(function() { /** INICIO FECHA: 07-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
        setTimeout(function() {
            // 1. Ajustar columnas
            table_complemento.columns.adjust();
            // 2. Forzar actualización de encabezados
            var headerCellsTableComplemento = $('#tblsolfin thead th');
            headerCellsTableComplemento.each(function() {
                var newWidth = $(this).width(); // Obtener ancho actualizado
                $(this).css('width', newWidth + 'px'); // Aplicarlo
            });
            // 3. Redibujar (sin resetear paginación)
            table_complemento.draw(false);
        }, 300); // Esperar a que termine la animación del sidebar
    }); /** FIN FECHA: 07-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/

</script>
<?php
    require("footer.php");
?>