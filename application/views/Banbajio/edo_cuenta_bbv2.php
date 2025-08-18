<?php
    require_once __DIR__ . "/../head.php";
    require_once __DIR__ . "/../menu_navegador.php";
?>
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <div class="box">
                    <div class="box-header with-border">
                        <h3>ESTADO DE CUENTA BANBAJIO</h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-lg-offset-5 col-lg-3 form-group">
                                <select class="form-control" id="mbuscar">
                                </select>
                            </div>
                            <div class="col-lg-3 form-group">
                                <select class="form-control" id="year_consult">
                                </select>
                            </div>
                            <div class="col-lg-1 form-group">
                                <button class="btn btn-block btn-primary" type="button"><i class='fas fa-search'></i></button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-offset-2 col-lg-4">
                                <table class="table table-striped table-bordered" id="totales_ingresos">
                                    <thead style="background-color: #0080FE; color: white;">
                                        <tr>
                                            <th style="text-align: center;" colspan="2">INGRESO</th>
                                        </tr>
                                        <tr>
                                            <th style="text-align: center;">MOVIMIENTO</th>
                                            <th style="text-align: center;">SALDO</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                    <tfoot></tfoot>
                                </table>
                            </div>
                            <div class="col-lg-4">
                                <table class="table table-striped" id="totales_egresos">
                                    <thead style="background-color: #fda400; color: white;">
                                        <tr>
                                            <th style="text-align: center;" colspan="2">EGRESO</th>
                                        </tr>
                                        <tr>
                                            <th style="text-align: center;">MOVIMIENTO</th>
                                            <th style="text-align: center;">SALDO</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                    <tfoot></tfoot>
                                </table>
                            </div>                          
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <table class="table table-striped" id="tabla_edo_cuenta">
                                    <thead class="thead-dark">
                                        <th style="font-size: .8em">CLIENTE</th>
                                        <th style="font-size: .8em">VISTA_INVERSION</th>
                                        <th style="font-size: .8em">COD_PROD</th>
                                        <th style="font-size: .8em">FECHA</th>
                                        <th style="font-size: .8em">RECIBO_DOCTO</th>
                                        <th style="font-size: .8em">COMISION</th>
                                        <th style="font-size: .8em">IVA</th>
                                        <th style="font-size: .8em">MONTO</th>
                                        <th style="font-size: .8em">DESCRIPCION</th>
                                        <th style="font-size: .8em">SALDO_DISP</th>
                                        <th style="font-size: .8em">MONEDA</th>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>

        let clsf_emp = [];
        let clsf_mov = [];
        let totales = [];

        year_enable();
        months_enable();

        function months_enable(){

            let month = new Date().getMonth()

            let monthNames = [ 
                "ENERO", 
                "FEBRERO", 
                "MARZO", 
                "ABRIL", 
                "MAYO", 
                "JUNIO",
                "JULIO", 
                "AGOSTO", 
                "SEPTIEMBRE", 
                "OCTUBRE", 
                "NOVIEMBRE", 
                "DICIEMBRE" 
            ];

            $.each( monthNames, function( i, v ){
                $("#mbuscar").append(  "<option value="+( i + 1 )+">"+v+"</option>" );
            });

            $("#mbuscar").val( month + 1 );

        }

        function year_enable(){
            let max = new Date().getFullYear()
            let min = max - 1
            let years = []

            for (var i = max; i >= min; i--) {
                $("#year_consult").append(  "<option>"+i+"</option>" );
            }
        }

        $('#tabla_edo_cuenta thead tr:eq(0) th').each( function (i) {

            var title = $(this).text();
            $(this).html( '<input type="text" class="form-control" style="font-size: .9em; width: 100%;" placeholder="'+title+'" />' );
    
            $( 'input', this ).on( 'keyup change', function () {
                if ( table_edo_cuenta.column(i).search() !== this.value ) {
                    table_edo_cuenta
                        .column(i)
                        .search( this.value )
                        .draw();
                }
            } );
        });

        let table_edo_cuenta = $('#tabla_edo_cuenta').DataTable({
            dom: 'Brtip',
            "buttons" : [
                {
                    extend: 'excelHtml5',             
                    text: '<i class="fas fa-file-excel"></i>',
                    messageTop: "LISTADO DE REGISTROS",
                    attr: {
                        class: 'btn btn-success'       
                    },
                    exportOptions: {
                        format:{    
                            header:  function (data, columnIdx) {
                                return data.replace( '<input type="text" class="form-control" style="font-size: .9em; width: 100%;" placeholder="', '' ).split('"')[0];
                            }
                        },
                    }
                }
            ],
            //"language" : lenguaje,
            "processing": false,
            "pageLength": 10,
            "bAutoWidth": false,
            "bLengthChange": false,
            "bInfo": false,
            "scrollX": true,
            "columns": [ 
                { "data" : "cliente" },
                { "data" : "vista_inversion" },
                { "data" : "cod_prod" },
                {                     
                    "data" : function( d ){
                        console.log("asdasd");
                        return formato_fechaymd( (d.fecha).substr(0, 4)+"-"+(d.fecha).substr(4, 2)+"-"+(d.fecha).substr(6, 2) )
                    }  
                },
                { "data" : "recibo_docto" },
                { "data" : "comision" },
                { "data" : "iva" },
                { 
                    "data" : function( d ){
                        return "$"+d.monto
                    }
                },
                { "data" : "descripcion" },
                { "data" : "saldo_disponible" },
                { "data" : "moneda" },
            ],
            "ajax": {
                "url" : url + "Movimientos_BB/saldos_bb",
                "type": "POST",
                "dataSrc" : "edo_cuenta",
                "data" : function( d ){
                    d.mes_consulta = $("#mbuscar").val();
                    d.year_consult = $("#year_consult").val();
                }
            }
        });

        $('#tabla_edo_cuenta').on('xhr.dt', function ( e, settings, resultado, xhr ) {

            $("table tbody, table tfoot").html("");
            let total = 0;
            let = id_elemento = "";
            //RESULTADOS TOTATALES
            /*
            if( ( resultado.totales ).length ){
                totales = resultado.totales;
                total = 0;
                $.each( totales, function( i, v ){
                    $("#totales_ingresos tbody").append("<tr><td>"+v.movimiento+"</td><td>$ "+formatMoney(v.saldo)+"</td></tr>");
                    total +=  parseFloat( v.saldo );
                });
                //$("#totales_gen tfoot").append("<tr><td>TOTAL</td><td>$ "+formatMoney( total )+"</td></tr>");
            }
            */

            //RESULTADOS POR TIPO DE MOVIMIENTO
            if( ( resultado.clsf_mov ).length ){
                clsf_mov = resultado.clsf_mov;
                total = 0;
                $.each( clsf_mov, function( i, v ){
                    if( ( v.signo_operacion ).replace(/ /g,'') == "+" )
                        id_elemento = "#totales_ingresos tbody";
                    else
                        id_elemento = "#totales_egresos tbody";
                    $( id_elemento ).append("<tr><td>"+v.movimiento+"</td><td>$ "+formatMoney(v.saldo)+"</td></tr>");
                    //total +=  parseFloat( v.saldo );
                });
                //$("#totales_mov tfoot").append("<tr><td>TOTAL</td><td>$ "+formatMoney( total )+"</td></tr>");
            }

            //RESULTADO POR EMPRESA
            if( ( resultado.clsf_emp ).length ){
                clsf_emp = resultado.clsf_emp;
                total = 0;
                $.each( clsf_emp, function( i, v ){

                    if( ( v.signo_operacion ).replace(/ /g,'') == "+" )
                        id_elemento = "#totales_ingresos tbody";
                    else{
                        id_elemento = "#totales_egresos tbody";
                        v.saldo = v.saldo * (-1);
                    }


                    $( id_elemento ).append("<tr><td>"+v.movimiento+"</td><td>$ "+formatMoney( v.saldo )+"</td></tr>");
                    //total +=  parseFloat( v.saldo );
                });
                //$("#totales_emp tfoot").append("<tr><td>TOTAL</td><td>$ "+formatMoney( total )+"</td></tr>");
            }
        });

        $( "button" ).on( "click", function(){

            clsf_emp = [];
            clsf_mov = [];
            totales = [];

            table_edo_cuenta.ajax.reload();
        });

        $( window ).resize(function(){
            table_edo_cuenta.columns.adjust();
        });

    </script>
<?php
    require_once __DIR__ . "/../footer.php";
?>