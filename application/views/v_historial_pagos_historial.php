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
                        <h3>HISTORIAL DE CHEQUES</h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="col-lg-3" style="margin-bottom: 15px;">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fas fa-calendar-alt"></i> <b>INICIO</b></span>
                                            <input class="form-control fechas_filtro from" type="text" id="datepicker_fromTC" maxlength="10"/>
                                        </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fas fa-calendar-alt"></i> <b>FIN</b></span>
                                            <input class="form-control fechas_filtro to" type="text" id="datepicker_toTC" maxlength="10"/>
                                        </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group-addon" style="padding: 4px;" >
                                        <h4 style="padding: 0; margin:0;"><label>&nbsp;Total: $<input style="text-align:right; border-bottom: none; border-top: none; border-right: none;  border-left: none; background: white;" disabled="disabled" readonly="readonly" type="text"  name="myText_4" id="myText_4"></label></h4>
                                    </div>
                                </div>
                                <table class="table table-striped" id="tablaTodoCheques">
                                    <thead>
                                        <tr>
                                            <th style="font-size: .8em"># SOLICITUD</th>
                                            <th style="font-size: .8em">RESPONSABLE</th>
                                            <th style="font-size: .8em">EMPRESA</th>    
                                            <th style="font-size: .8em">FECHA OPERACIÓN</th>

                                            <th style="font-size: .8em">DEPARTAMENTO</th>
                                            <th style="font-size: .8em">PROYECTO</th>
                                            <th style="font-size: .8em">LOTE</th>
                                            <th style="font-size: .8em">FECHA AUTORIZACIÓN</th>
                                            <th style="font-size: .8em">FECHA DISPERSIÓN</th>

                                            <th style="font-size: .8em">CANTIDAD</th>
                                            <th style="font-size: .8em">REFERENCIA</th>
                                            <th style="font-size: .8em">ESTATUS</th>
                                            <th style="font-size: .8em">PAGO</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>

    var tablaTodoCheques;
    
    $('.fechas_filtro').datepicker({
        format: 'dd/mm/yyyy',
        endDate: '-0d'
    });

    $('#datepicker_fromTC').on('keyup change', function(){
        var str = $(this).val();
        if(str.length==2 || str.length==5){
            $('#datepicker_fromTC').val(str+'/');
        }
    }); 

    $('#datepicker_toTC').on('keyup change', function(){
        var str = $(this).val();
        if(str.length==2 || str.length==5){
            $('#datepicker_toTC').val(str+'/');
        }
    });

    $('#tablaTodoCheques').on('xhr.dt', function ( e, settings, json, xhr ) {
        var total = 0;
        $.each( json.data,  function(i, v){
            total += parseFloat(v.cantidad);
        });
        var to = formatMoney(total);
        document.getElementById("myText_4").value = to;
    });
     
 
   $("#tablaTodoCheques").ready( function(){

        $('#tablaTodoCheques thead tr:eq(0) th').each( function (i) {
            // if( i != 0 && i != 3 ){
                var title = $(this).text();
                $(this).html( '<input type="text" style="font-size: .9em; width: 100%;" placeholder="'+title+'" />' );
            
                $( 'input', this ).on('keyup change', function () {
                    if (tablaTodoCheques.column(i).search() !== this.value ) {
                        tablaTodoCheques
                            .column(i)
                            .search( this.value)
                            .draw();
                           var total = 0;
                           var index = tablaTodoCheques.rows( { selected: true, search: 'applied' } ).indexes();
                           var data = tablaTodoCheques.rows( index ).data();
                           $.each(data, function(i, v){
                               total += parseFloat(v.cantidad);
                           });
                           var to1 = formatMoney(total);
                           document.getElementById("myText_4").value = to1;
                    }
                });
            // }
        });

        tablaTodoCheques = $("#tablaTodoCheques").DataTable({
           dom: 'Brtip',
            "buttons": [
                {
                    extend: 'excelHtml5',             
                    text: '<i class="fas fa-file-excel"></i> EXPORTAR',
                    messageTop: "Lista de todos pagos (CHEQUES)",
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
                        },
                        columns: [ 0, 1, 2, 4, 5, 6, 7, 8, 9, 10, 11, 12 ]
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
            "columns": [
                {
                    "width" : "7%",
                    "data" : function( d ){
                        return '<p style="font-size: .9em">'+( d.bd == 1 ? d.idpago : 'NA' )+'</p>';
                    }
                },
                {
                    "data" : function( d ){
                        return '<p style="font-size: .9em">'+d.responsable+'</p>'+( d.programado >= 1 ? '<small class="label pull-center bg-blue">PROGRAMADO</small>' : '' );
                    }
                },
                {
                    "data" : function( d ){
                        return '<p style="font-size: .9em">'+d.abrev+'</p>'
                    }
                },
                {
                    "data" : function( d ){
                        return '<p style="font-size: .9em">'+formato_fechaymd(d.fecha_operacion)+'</p>'
                    }
                },
                {
                    "data" : function( d ){
                        return '<p style="font-size: .9em">'+d.nomdepto+'</p>'
                    }
                },
                {
                    "data" : function( d ){
                        return '<p style="font-size: .9em">'+d.proyecto+'</p>'
                    }
                },
                {
                    "data" : function( d ){
                        return '<p style="font-size: .9em">'+d.lote+'</p>'
                    }
                },
                {
                    "data" : function( d ){
                        return '<p style="font-size: .9em">'+formato_fechaymd(d.fautorizacion)+'</p>'
                    }
                },
                {
                    "data" : function( d ){
                        return '<p style="font-size: .9em">'+( d.fdispersion ? formato_fechaymd(d.fdispersion) : '' )+'</p>'
                    }
                },
                {
                     "data": function( data ){
                          if(data.intereses=='1'){
                            return "$ "+formatMoney(data.cantidad)+ "<br><small class='label pull-center bg-maroon'>CREDITO</small><small class='label pull-center bg-gray'>INTÉRES: "+formatMoney(data.interes)+"</small>";
                        }
                        else{
                            return "$ "+formatMoney(data.cantidad);
                        }
                     }
                },
                {
                    "data" : function( d ){
                        return '<p style="font-size: .9em">'+d.referencia+'</p>'
                    }
                },
                {
                     "data": function( d ){
 
                        if(d.estatus=='COBRADO'){
                            return "<small class='label pull-center bg-green'>COBRADO</small>";
                        }else if(d.estatus=='CANCELADO'){
                            return "<small class='label pull-center bg-red'>CANCELADO</small>";
                        }else{
                            return "<small class='label pull-center bg-orange'>"+d.estatus+"</small>";
                        }

                     }
                },
                {
                    "data" : function( d ){
                        return '<p style="font-size: .9em">'+(d.bd == 1 ? "PROVEEDOR" : "CAJA CHICA" )+'</p>'
                    }
                }
            ],
            "columnDefs": [ {
                   "orderable": false
                },
                {
                    "targets": [4],
                    "visible": false,
                    "orderable": false,
                },
                {
                    "targets": [5],
                    "visible": false,
                    "orderable": false,
                },
                {
                    "targets": [6],
                    "visible": false,
                    "orderable": false,
                },
                {
                    "targets": [7],
                    "visible": false,
                    "orderable": false,
                },
                {
                    "targets": [8],
                    "visible": false,
                    "orderable": false,
                }
            ],
            "ajax": url + "Historial_cheques/tablaAllCheques",
            bSort: false,
        });
        
        $('#datepicker_fromTC').change( function() { 
            tablaTodoCheques.draw();
            
            var total = 0;
            var index = tablaTodoCheques.rows( { selected: true, search: 'applied' } ).indexes();
            var data = tablaTodoCheques.rows( index ).data();
            
            $.each(data, function(i, v){
                total += parseFloat(v.cantidad);
            });
            
            var to1 = formatMoney(total);
            document.getElementById("myText_4").value = to1;

        });
        $('#datepicker_toTC').change( function() {
            
            tablaTodoCheques.draw(); 
            
            var total = 0;
            var index = tablaTodoCheques.rows( { selected: true, search: 'applied' } ).indexes();
            var data = tablaTodoCheques.rows( index ).data();

            $.each(data, function(i, v){
                total += parseFloat(v.cantidad);
            });
            var to1 = formatMoney(total);
            document.getElementById("myText_4").value = to1;
        });
        $('.dt-buttons').attr('style','float: right !important; margin-right: 15px;');
   
    });
  

    $.fn.dataTableExt.afnFiltering.push( function( oSettings, aData, iDataIndex ) {
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
                
        var iStartDateCol = 3;
        var iEndDateCol = 3;

        var meses = ['','Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];

        var mes1 =  typeof aData[iStartDateCol] === 'undefined' ? '' : (meses.indexOf(aData[iStartDateCol].split('/')[1]).toString()).padStart(2, "0");
        var mes2 =  typeof aData[iEndDateCol] === 'undefined' ? '' : (meses.indexOf(aData[iEndDateCol].split('/')[1]).toString()).padStart(2, "0");

        iFini=iFini.substring(6,10) + iFini.substring(3,5) + iFini.substring(0,2);
        iFfin=iFfin.substring(6,10) + iFfin.substring(3,5) + iFfin.substring(0,2);
            //alert(iFini);
            // alert(iFfin);
        var datofini=aData[iStartDateCol].substring(7,11) + mes1 + aData[iStartDateCol].substring(0,2);
        var datoffin=aData[iEndDateCol].substring(7,11) + mes2 + aData[iEndDateCol].substring(0,2);
                //alert(datofini);
                //alert(datoffin);
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
    
    $(window).resize(function(){
        tablaTodoCheques.columns.adjust();
    });

</script>

<?php
    require("footer.php");
?>