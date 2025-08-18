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
                        <h3>CLASIFICACIÓN FACTURAS</h3>
                        <div class="row">
                            <div class="col-lg-12">
                                <h4 id="totalxaut"></h4>
                            </div>
                        </div>                               
                    </div>
                    <div class="box-body">
                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                                <li class="active"><a id="fact_tod" data-toggle="tab" data-value="fact_tod" href="#fact_todas" role="tab" aria-controls="#home" aria-selected="true">POR ETIQUETAR</a></li>
                                <li><a id="facturas_etq" data-toggle="tab" data-value="facturas_etq" href="#fact_todas" role="tab" aria-controls="#fact_todas" aria-selected="true">FACTURAS ETIQUETADAS</a></li>
                            </ul>
                        </div>

                        <div class="tab-content">
                            <div class="active tab-pane" id="fact_todas">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="col-lg-3">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fas fa-calendar-alt"></i> <b>INICIO</b></span>
                                                <input class="form-control fechas_filtro from" type="text" id="fecInicial" name="fechaInicial" maxlength="10" autocomplete="off"/>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fas fa-calendar-alt"></i> <b>FIN</b></span>
                                                <input class="form-control fechas_filtro to" type="text" id="fecFinal" name="fechaFinal" maxlength="10" autocomplete="off"/>
                                            </div>
                                        </div>
                                        <table class="table table-striped" id="fac_etq">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th></th>
                                                    <th style="font-size: .8em" nowrap>#</th>
                                                    <th style="font-size: .8em">FOLIO</th>
                                                    <th style="font-size: .8em">PROVEEDOR</th>
                                                    <th style="font-size: .8em">F FACTURA</th>
                                                    <th style="font-size: .8em">EMPRESA</th>
                                                    <th style="font-size: .8em">F DISP</th>
                                                    <th style="font-size: .8em">METODO PAGO</th>
                                                    <th style="font-size: .8em">CANTIDAD</th>
                                                    <th style="font-size: .8em">COMPROBADO</th>
                                                    <th style="font-size: .8em">DEPARTAMENTO</th>
                                                    <th style="font-size: .8em">DEPARTAMENTO</th>
                                                    <th style="font-size: .8em">NOTA</th>
                                                    <th style="font-size: .8em">FOLIO FISCAL (UUID)</th>
                                                    <th style="font-size: .8em">CAPTURISTA</th>
                                                    <th style="font-size: .8em">JUSTIFICACIÓN</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                                
                    </div><!--End tab content-->
                </div><!--end box-->
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="etiquetar" role="dialog" >
    <div class="modal-dialog modal-lg" >
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close etqCls" data-dismiss="modal" >&times;</button>
                <h4 class="modal-title" >ETIQUETAR FACTURA</h4>
            </div>
            <div class="modal-body"  style="width: 430px;">
                <form id="add_etiqueta">
                    <div class="row">
                        <div class="col-lg-12 form-group form-group-lg">
                                <h5><b>ETIQUETA</b></h5>
                                <select class="form-control etq" id="idetiqueta" name="idetiqueta" required>
                                    <option value="">Seleccione una opción</option>
                                </select>
                                
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 form-group">
                            <h5><b>OBSERVACIÓN</b></h5>
                            <textarea id="observacion" name="observacion"  rows="3" class="form-control" maxlength="350" required></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-lg-offset-5">
                            <button type="button" class="btn btn-danger etqCls" data-dismiss="modal"><i class="fas fa-times"></i> CERRAR</button>
                            <button class="btn btn-success" type="submit">GUARDAR</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    var etiqueta = 0;
    var nomEtq = '';
    var idsol = 0;
    var link_post = '';
    $('[data-toggle="tab"]').click(function(e) {
            //switch ($(this).attr('href')) 
            switch( $(this).data('value') ){
                case 'fact_tod':
                    etiqueta = 0;
                    tabla_etiquetafac.ajax.reload();
                    break;
                case 'facturas_etq':
                    etiqueta = 1;
                    console.log(etiqueta);
                    tabla_etiquetafac.ajax.reload();
                    break;  
            }
        });
    //  tabla_nueva_caja_chica.column( 6 ).visible( cerrar_ch );
    $(document).ready(function (){
        $(".etq").select2({
            placeholder: "Seleccione una opción",
            enableFiltering: true,
            allowClear: true
        });
        $.getJSON(url+'Complementos_cxp/getEtiquetas').done(function(data){
            $.each( data.data, function( i, v ){
                $(".etq").append('<option value="'+v.idetiqueta+'" data-etq="'+v.etiqueta+'" >'+v.etiqueta+'</option>')
            });
        });
    });
    $(".etqCls").click( function(){
        $("#etiquetar").validate().resetForm();
        $('#idetiqueta').val('null').trigger('change');
        $('#observacion').val('');
    });
    $('#fac_etq').ready(function(){
        
        $('#fac_etq thead tr:eq(0) th').each(function(i){
            if( i != 0 && i < $('#fac_etq thead tr:eq(0) th').length - 1 ){
                var title = $(this).text();
                $(this).html( '<input type="text" class="form-control" style="font-size: .9em; width: 100%;" placeholder="'+title+'">' );
        
                $( 'input', this ).on( 'keyup change', function () {
                    if ( tabla_etiquetafac.column(i).search() !== this.value ) {
                        tabla_etiquetafac
                            .column(i)
                            .search( this.value )
                            .draw();
                           
                    }
                } );
                
            } 
        });

        tabla_etiquetafac = $('#fac_etq').DataTable({
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
                        format:{    
                            header:  function (data, columnIdx) {
                                data = data.replace( '<input type="text" class="form-control" style="font-size: .9em; width: 100%;" placeholder="', '' );
                                data = data.replace( '" />', '' );
                                data = data.replace( '">', '' );
                                return data;
                            },
                            columns: [ 1, 2, 4, 5, 6, 7, 8, 9, 12, 13, 14, 15 ]
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
                    "className": 'details-control',
                    "orderable": false,
                    "data" : null,
                    "defaultContent": '<i class="fas animacion fa-caret-right"></i>'
                },
                {
                    "width": "10%", 
                    "data" : function( d ){

                        return '<p style="font-size: .8em">'+d.idsolicitud+'</p>'
                    }
                },
                {
                    "width": "7%",
                    "data" : function( d ){
                        if( d.foliofac ){
                            return '<p style="font-size: .8em"> '+d.foliofac+( d.folio_fiscal ? "/" + (d.folio_fiscal).substr(d.folio_fiscal.length - 5) : '' )+'</p>';
                        }else{
                            return '<p style="font-size: .8em"><small class="label pull-center bg-red">FACTURA FALTANTE</small></p>';
                        }
                    }
                },
                {
                    "width": "15%",
                    "data" : function( d ){
                        return '<p style="font-size: .6em"> '+d.nombre+'</p>';
                    }
                },
                {
                    "width": "10%",
                    "data" : function( d ){
                        return '<p style="font-size: .6em"> '+formato_fechaymd(d.fecha_factura)+'</p>';
                    }
                },
                {
                    "width": "7%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em"> '+d.abrev+'</p>';
                    }
                },
                { 
                    "width": "10%",
                    "data" : function( d ){
                        return '<p style="font-size: .7em"> '+( d.fechaDis ? formato_fechaymd(d.fechaDis) : '-' )+'</p>'
                    }
                },
                {
                    "width": "10%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em"> '+d.metoPago+'</p>';
                    }
                },
                { 
                    "width": "10%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">$ '+ formatMoney(d.cantidad)+'</p>'
                    }
                },
                { 
                    "width": "10%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+( d.tcomprobado > 0 ? '$ '+ formatMoney(d.tcomprobado)+' '+d.moneda : 'NA') +'</p>'
                    }
                },
                { 
                    "width": "9%",
                    "data" : function( d ){
                        var p = '<p style="font-size: .8em"> '+d.nomdepto+'</p>'; // background-color:rgb(206, 199, 207 );
                        p += d.idetiqueta != null   ? '<p style="font-size: .7em"><small class="label pull-center badge badge-pill" style="border-style: double;">'+d.etiqueta+'</small></p>' 
                                                    :  ((d.folio_fiscal && d.tcomprobado === null) ? '<p style="font-size: .7em"><small class="label pull-center bg-blue"> FALTA COMPLEMENTO</small></p>' 
                                                                                                : ( ( d.folio_fiscal && d.tcomprobado ) ? '<p style="font-size: .7em"><small class="label pull-center bg-red">PENDIENTE POR COMPROBAR '+( formatMoney( d.cantidad - d.tcomprobado ) )+'</small></p>'
                                                                                                                                        : '<p style="font-size: .8em"><small class="label pull-center bg-red">FACTURA FALTANTE</small></p>') );
                         return p;
                    }
                },
                { 
                    "width": "9%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em"> '+d.nomdepto+'</p>';
                    }
                },
                { 
                    "width": "9%",
                    "data" : function( d ){
                        var p = ""
                        if( d.folio_fiscal && d.tcomprobado === null ){
                            return p += '<p style="font-size: .7em"><small class="label pull-center bg-blue"> FALTA COMPLEMENTO</small></p>';
                        }else if( d.folio_fiscal && d.tcomprobado ){
                            return p += '<p style="font-size: .7em"><small class="label pull-center bg-red">PENDIENTE POR COMPROBAR '+( formatMoney( d.cantidad - d.tcomprobado ) )+'</small></p>';
                        }else{
                            return p += '<p style="font-size: .8em"><small class="label pull-center bg-red">FACTURA FALTANTE</small></p>';
                        }
                    }
                },
                { 
                    "width": "9%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em"> '+( d.folio_fiscal ? d.folio_fiscal : '' )+'</p>';
                    }
                },
                { 
                    "width": "10%",
                    "data" : function( d ){
                        return '<p style="font-size: .6em"> '+d.nombre_capturista+'</p>'
                    }
                },
                { 
                    "width": "10%",
                    "data" : function( d ){
                        return '<p style="font-size: .6em"> '+d.justificacion+'</p>'
                    }
                },
                {
                    "data": function( d ){
                        var opciones = '<div class="btn-group-vertical">';
                        opciones +='<button type="button" class="btn btn-sm btn-primary consultar_modal notification"  tittle="Consultar Información" value="'+d.idsolicitud+'" data-value="BAS"><i class="fas fa-eye"></i></button>'
                        opciones += d.idetiqueta != null ? '<button class="btn btn-warning btn-sm edit_etiqueta" title="Editar Etiqueta"><i class="fa fa-tag"></i></button>':'';
                        opciones += d.idetiqueta == null ? '<button class="btn btn-sm btn-info etiquetar_sol"  title="Etiquetar" ><i class="fa fa-tag"></i></button>':'';
                        return opciones += '</div>';
                    },
                    "orderable": false
                }
            ],
            "columnDefs": [
                {
                    "targets": [0],
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
                    "targets": [9],
                    "orderable": false,
                    "visible": false,
                },
                {
                    "targets": [15],
                    "orderable": false,
                    "visible": false,
                }
            ],
            "ajax":  {  "url": url + "Complementos_cxp/tabla_facturas_etiqueta",
                        "data": function (d){
                            d.etiqueta = etiqueta
                        },
                        "type": "POST"
                }
        });

        $("#fac_etq").on("click", ".etiquetar_sol", function (e){
            row = tabla_etiquetafac.row($(this).parents('tr')).data();
            idsol = row.idsolicitud;
            link_post = 'Complementos_cxp/add_etiqueta';
            $("#etiquetar").modal();
        });
        $("#fac_etq").on("click", ".edit_etiqueta", function (e){
            row = tabla_etiquetafac.row($(this).parents('tr')).data();
            idsol = row.idsolicitud;
            link_post = 'Complementos_cxp/edit_etiqueta';
            $('#etiquetar .modal-title').html('EDITAR ETIQUETA');
            $('#idetiqueta').val(row.idetiqueta).trigger('change');
            $("#etiquetar").modal();
        });
    });

    $('#idetiqueta').change(function(){
        nomEtq = $( this ).find(':selected').data('etq');
    });

    $('#add_etiqueta').submit(function(e){
        e.preventDefault();
    }).validate({
        submitHandler: function(form){
            var data = new FormData($(form)[0]);
            data.append("idsolicitud",idsol);
            data.append("etiqueta",nomEtq);
            $.ajax({
                    url: url + link_post,
                    data: data,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    method: 'POST',
                    type: 'POST', // For jQuery < 1.9
                    success: function(data){
                        console.log(data);
                        if( data.res ){
                            $("#etiquetar").modal( 'toggle' );
                            tabla_etiquetafac.ajax.reload();
                            $("#etiquetar").validate().resetForm();
                            $('#idetiqueta').val('null').trigger('change');
                            $('#observacion').val('');
                        }else{
                            alert("NO SE HA PODIDO COMPLETAR LA SOLICITUD");
                        }
                        idsolicitud = 0;
                    },error: function( ){
                        alert("Algo salio mal, recargue su página.");
                    }
                }); 
        }
    });
    
    $('.fechas_filtro').datepicker({
          format: 'dd/mm/yyyy',
          autoclose: true
    });
    
    $('#fecInicial').change( function() { 
        tabla_etiquetafac.draw();
    }).on('dp.change', function (selected) {
        end.data("DateTimePicker").minDate(selected.date);
    });
    
    $('#fecFinal').change( function() {  tabla_etiquetafac.draw(); } );
    $.fn.dataTableExt.afnFiltering.push(
        function( oSettings, aData, iDataIndex ) {
            if( oSettings.nTable.getAttribute('id') == "fac_etq" && ($("#fecInicial").val()!='' || $("#fecFinal").val()!= '' )){
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
</script>
<?php
    require("footer.php");
?>
 