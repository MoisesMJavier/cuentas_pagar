<?php
    require("head.php");
    require("menu_navegador.php");
?>  
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-header">
                    <h3>COMPRAS AUTORIZADAS POR DA</h3>
                </div>
                <div class="box-body">
                    <div class="tab-content">
                        <div class="active tab-pane" id="facturas_aturizar">
                          <!--div class="col-md-4">
                            <label for="week-dp">Seleccione una semana:</label-->
                            <input type="hidden" class="form-control datetimepicker-input" id="week-dp" data-toggle="datetimepicker" data-target="#week-dp" readonly>
                            <table class="table table-responsive table-bordered table-striped table-hover" id="tbl_compras">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th style="font-size: .8em">#</th>
                                        <th style="font-size: .8em">PROVEEDOR</th>
                                        <th style="font-size: .8em">EMPRESA</th>                                       
                                        <th style="font-size: .8em">FECHA FACT</th>
                                        <th style="font-size: .8em">FECHA AUT.</th>
                                        <th style="font-size: .8em">CANTIDAD</th>
                                        <th style="font-size: .8em">ÁREA</th>
                                        <th style="font-size: .8em">ORDEN DE COMPRA</th>
                                        <th style="font-size: .8em">REQUISICIÓN</th>
                                        <th style="font-size: .8em">DIRECTOR</th>
                                        <th style="font-size: .8em">JUSTIFICACIÓN</th>
                                        <th></th>
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
<script type="text/javascript">

    /*window.onbeforeunload = function() {
        return "Estas recargando la pagina.";
    }*/

    var idSol;
    var tota=0;
    var totaPen=0;
    var tota2=0;

    var tabla_compras;
    var tabla_getion_pagos;
    var tabla_getion_pagosP;
    var tabla_pdf;
    var tr;    
    let week_start;
    let week_end;

    set_picker_start_end = (picker, when) => {
      moment.updateLocale('en', {
          week: {
              dow: 7
          }
      });
      let m = (when == 'now') ? moment() : moment(when) //moment

      week_start  = m.startOf('week')
      week_end    = m.clone().endOf('week')
      
      picker.setStartDate(week_start);
      picker.setEndDate(week_end);

      
      $("#week-dp").val("Semana del "+week_start.toISOString().split("T")[0].split("-").reverse().join("/")+" al "+week_end.format("DD/MM/YYYY"));
      if(tabla_compras)
        tabla_compras.ajax.reload();
        $("#week-dp").data('daterangepicker').remove();
      
    }

    
    $("#week-dp").daterangepicker({
      // "showDropdowns": true,
      "showISOWeekNumbers": true,
      "autoApply": true,
      "showCustomRangeLabel": true,
      // "startDate": '', //not work because of one calendar. will be set further
      // "endDate": '', //not work because of one calendar. will be set further
      "drops": "down",
      "singleDatePicker" : true, //to make one click and one calendar
      "locale": {
        "weekLabel": "#",
        "firstDay": 0,
        "format":"DD/MM/YYYY",
        "separator": " - ",
      },
    });

    

    $("#week-dp").on('show.daterangepicker', function(ev, picker) {
      let active_cell = picker.container[0].querySelector('td.start-date')
      active_cell.parentElement.classList.add('active') //tr goes active
      $("#week-dp").val("Semana del "+week_start.toISOString().split("T")[0].split("-").reverse().join("/")+" al "+week_end.format("DD/MM/YYYY"));
    });

    $("#week-dp").on('hide.daterangepicker', function(ev, picker) {
      $("#week-dp").val("Semana del "+week_start.toISOString().split("T")[0].split("-").reverse().join("/")+" al "+week_end.format("DD/MM/YYYY"));
    });

    $("#week-dp").on('apply.daterangepicker', function(ev, picker) {
      set_picker_start_end(picker, picker.startDate)
    });

    set_picker_start_end($("#week-dp").data('daterangepicker'), 'now') //set current week selected
    

    $(".pago").on({
        "focus": function (event) {
            $(event.target).select();
        },
        "keyup": function (event) {
            $(event.target).val(function (index, value) {
                return value.replace(/\D/g, "")
                    .replace(/([0-9])([0-9]{2})$/, '$1.$2')
                    .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ",");
            });
        }
    });

    $("#recuperar_guardado").click( function(){

        var guardado = JSON.parse($.cookie("tautorizaciones-data"));
        
        $.each( JSON.parse( guardado ),  function( i, v ){
            totaPen += v.pa;
        });

        $("#totpagarPen").html( formatMoney( totaPen ) )

        tabla_getion_pagosP.clear();
        tabla_getion_pagosP.rows.add( JSON.parse( guardado ) );
        tabla_getion_pagosP.draw();

    });

    $("#tbl_compras").ready( function () {
        
        $('#tbl_compras thead tr:eq(0) th').each( function (i) {
        if( i > 0 && i < $('#tbl_compras thead tr:eq(0) th').length -1  ){
            var title = $(this).text();
            $(this).html( '<input type="text" style="width: 100%;" placeholder="'+title+'" />' );
    
            $( 'input', this ).on( 'keyup change', function () {
                if ( tabla_compras.column(i).search() !== this.value ) {
                    tabla_compras
                        .column(i)
                        .search( this.value )
                        .draw();
                }
            } );
        }
     });
          
        tabla_compras = $('#tbl_compras').DataTable({
            "language" : lenguaje,
            dom: 'Brtip',
            "buttons" : [
                {
                    extend: 'excelHtml5',             
                    text: '<i class="fas fa-file-excel"></i> EXPORTAR A EXCEL',
                    messageTop: "LISTADO DE PAGOS A AUTORIZAR",
                    attr: {
                        class: 'btn btn-success'       
                    },
                    exportOptions: {
                        format:{    
                            header:  function (data, columnIdx) {
                                return data.replace( '<input type="text" style="width: 100%;" placeholder="', '' ).split('"')[0];
                            }
                        },
                        columns: [ 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11 ]
                    }
                }
            ],
            "processing": false,
            "pageLength": 10,
            "bAutoWidth": false,
            "bLengthChange": false,
            "bInfo": false,
            "scrollX": true,
            responsive: true,
            "columns": [{
                    "width": "1%",
                    "className": 'details-control',
                    "orderable": false,
                    "data" : null,
                    "defaultContent": '<i class="fas animacion fa-caret-right"></i>'
                },
                {
                    "width": "1%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.ID+'</p>';
                    },
                },
                {
                    "width": "12%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em" id="prov_'+d.ID+'">'+d.Proveedor + ( d.prioridad == 1 ? "<br><small class='label pull-center bg-red'>URGENTE</small>" : "") +'</p>';
                    }
                },
                {
                    "width": "4%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.EMPRESA+'</p>';
                    }
                },
                {
                    "width": "4%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.FECHAFAC+'</p>';
                    }
                },
                {
                    "width": "4%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.fecha_autorizacion+'</p>';
                    }
                },
                {
                    "width": "5%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">$ '+formatMoney( d.Cantidad )+" "+d.moneda+'</p>';
                    }
                },
                {
                    "width": "6%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.Departamento+'</p>';
                    }
                },
                {
                    "width": "6%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.oc+'</p>';
                    }
                },
                {
                    "width": "6%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.requisicion+'</p>';
                    }
                },
                {
                    "width": "6%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.nombre_capturista+'</p>';
                    }
                },
                {
                    "width": "6%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.Observacion+'</p>';
                    }
                },
                { 
                    "width": "8%",
                    "data" : function( d ){
                        
                        var opciones = '<div class="btn-group-vertical">';
                       
                        opciones += '<button id = "verSol" class="btn btn-instagram consultar_modal notification btn-sm" value="'+d.ID+'" data-value="SOL" title="Ver Solicitud"><i class="fas fa-eye" ></i>'+(d.visto == 0 ? '</i><span class="badge">!</span>' : '')+'</button>'
                        
                        return opciones+"</div>";
                    },
                    "orderable": false
                }
            ],
            "order": [[1, 'asc']],
            "ajax": {
              url: url + "DireccionGeneral/obtenerSolCompras_Aut",
              "type": "POST",
              data: {
                fechaini: function() { return week_start.format("YYYY-MM-DD") },
                fechafin: function() { return week_end.format("YYYY-MM-DD") }
              }
            },
            "columnDefs": [
                {
                    "targets": [11],
                    "orderable": false,
                    "visible": false
                },
            ]
        });

        $('#tbl_compras tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = tabla_compras.row( tr );
    
            if ( row.child.isShown() ) {
                row.child.hide();
                tr.removeClass('shown');
                $(this).parent().find('.animacion').removeClass("fa-caret-down").addClass("fa-caret-right");
            }
            else {

                var informacion_adicional = '<table class="table text-justify">'+
                    '<tr>'+
                        '<td style="font-size: .8em"><strong>JUSTIFICACIÓN: </strong>'+row.data().Observacion+'</td>'+
                    '</tr>'+
                    '<tr>'+
                        '<td style="font-size: .8em"><strong>DESCRIPCIÓN FACTURA: </strong>'+row.data().descripcion+'</td>'+
                    '</tr>'+
                '</table>';

                row.child( informacion_adicional ).show();
                tr.addClass('shown');
                $(this).parent().find('.animacion').removeClass("fa-caret-right").addClass("fa-caret-down");
            }
        });
 
    });

</script>
<?php
require("footer.php");
?>





