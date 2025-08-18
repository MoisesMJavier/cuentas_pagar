<?php
    require("head.php");
    require("menu_navegador.php");
?>
<style>
    .dataTables_wrapper .dt-buttons {
        display: flex;
        justify-content: flex-end; /* Alinea los botones a la derecha */
    }
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-header">
                    <h3>CONTABILIDAD</h3>
                </div>
                <div class="box-body">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a id="autorizaciones-tab" data-toggle="tab" href="#facturas_aturizar" role="tab" aria-controls="#home" aria-selected="true">PROVISIONAR FACTURAS</a></li>
                            <!--li><a id="profile-tab" data-toggle="tab" href="#facturas_finalizadas" role="tab" aria-controls="facturas_finalizadas" aria-selected="false">PÓLIZAS DE PAGO</a></li-->
                            <li><a id="profile-tab" data-toggle="tab" href="#historial_provisiones" role="tab" aria-controls="facturas_finalizadas" aria-selected="false">HISTORIAL DE PROVISIONES</a></li>
                        </ul>
                    </div>
                    <div class="tab-content">
                        <div class="active tab-pane" id="facturas_aturizar">
                           <!-- <div class="form-group col-md-4">
                                <label for="deptosel">Empresas: </label>
                                <select id="cxp1_sel" name="cxp1_sel" class="form-control lista_empresa"></select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="deptosel">Proveedores: </label>
                                <select id="cxp2_sel" name="cxp2_sel" class="form-control lista_provedores"></select>
                            </div>-->
                            <table class="table table-responsive table-bordered table-striped table-hover" id="solAProvision">
                                <thead>
                                    <tr>    
                                        <th style="font-size: .9em"></th>
                                        <th style="font-size: .9em">#</th>
                                        <th style="font-size: .9em">PROVEEDOR</th>
                                        <th style="font-size: .9em">METODO PAGO</th>
                                        <th style="font-size: .9em">FOLIO</th>
                                        <th style="font-size: .9em">EMPRESA</th>
                                        <th style="font-size: .9em">CANTIDAD</th>
                                        <th style="font-size: .9em">FECHA</th>
                                        <th style="font-size: .9em">DEPARTAMENTO</th>
                                        <th style="font-size: .9em"></th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <!--div class="tab tab-pane" id="facturas_finalizadas">
                            < <div class="form-group col-md-4">
                                <label for="deptosel">Empresas: </label>
                                <select id="cxp3_sel" name="cxp3_sel" class="form-control lista_empresa"></select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="deptosel">Proveedores: </label>
                                <select id="cxp4_sel" name="cxp4_sel" class="form-control lista_provedores"></select>
                            </div>>
                            <table class="table table-responsive table-bordered table-striped table-hover" id="solProvisionada">
                                <thead>
                                    <tr>
                                        <th style="font-size: .9em">FOLIO</th>
                                        <th style="font-size: .9em">PROVEEDOR</th>
                                        <th style="font-size: .9em">EMPRESA</th>
                                        <th style="font-size: .9em">CANTIDAD</th>
                                        <th style="font-size: .9em">PAGO</th>
                                        <th style="font-size: .9em">FECHA</th>
                                        <th style="font-size: .9em">DEPARTAMENTO</th>
                                        <th></th>
                                    </tr>
                                </thead>
                            </table>
                        </div-->
                        <div class="tab tab-pane" id="historial_provisiones">
                            <div class="col-lg-2">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fas fa-calendar-alt"></i> <b>INICIO</b></span>
                                    <input class="form-control fechas_filtro from" type="text" id="datepicker_from" />
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fas fa-calendar-alt"></i> <b>FIN</b></span>
                                    <input class="form-control fechas_filtro to" type="text" id="datepicker_to" />
                                </div>
                            </div>
                            <table class="table table-responsive table-bordered table-striped table-hover" id="historialPro">
                                <thead>
                                    <tr>
                                        <th style="font-size: .9em"># SOL</th>
                                        <th style="font-size: .9em"># PROV</th>
                                        <th style="font-size: .9em">FECHA PROVICIÓN</th>
                                        <th style="font-size: .9em">NUMERO PÓLIZA</th>
                                        <th style="font-size: .9em">PROVEEDOR</th>
                                        <th style="font-size: .9em">FOLIO FISCAL</th>
                                        <th style="font-size: .9em">EMPRESA</th>
                                        <th style="font-size: .9em">RESPONSABLE</th>
                                        <th style="font-size: .9em"></th>
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


<div id="modalNumeroPoliza" class="modal fade modal modal-alertas " role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-green">
                <button type="button" class="close" data-dismiss="modal" onclick="Limpiar()">&times;</button>
                <h4 class="modal-title">PROVISIÓN DE FACTURA</h4>
            </div>
            <div class="modal-body">
              <div class="from-group" role="group" aria-label="Basic example">
                <input type="text" id="numero" name="numero" class="form-control" placeholder="Ingresa nombre de la póliza"><br>
                <input type="text" id="ruta" name="ruta" class="form-control" placeholder="Ingresa ruta del archivo">
           </div>
            </div>
            <div class="modal-footer">
                <div class="col-md-12">
                    <div class="trans text-centers" role="group" aria-label="Basic example">
                        <button type="button" class="btn btn-success btn-modalAcepto"  disabled="disabled">ACEPTAR</button>
                        <button type="button" class="btn btn-danger btn-modalCancelar" data-dismiss="modal">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="modalNumeroPolizaP" class="modal fade modal modal-alertas" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-green">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">PÓLIZA DE PAGO</h4>
            </div>
            <div class="modal-body">
                <input type="text" id="numeroP" name="numeroP" class="form-control" placeholder="Ingresa folio de póliza">
            </div>
            <div class="modal-footer">
                <div class="col-md-12">
                    <div class="trans text-centers">
                        <button type="button" class="btn btn-success btn-modalAceptoP" disabled="disabled">ACEPTAR</button>
                        <button type="button" class="btn btn-danger btn-modalCancelarP" data-dismiss="modal">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<div id="modalDeclina" class="modal fade modal modal-alertas" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-red">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">DECLINAR PROVISIÓN</h4>
            </div>
            <div class="modal-body">        
                <textarea id="motivoDeclinada" name="motivoDeclinada" class="form-control" rows="4" cols="10" placeholder="Razón de la declinación" ></textarea> 
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-lg-12"> 
                        <div class="trans text-centers" role="group" aria-label="Basic example">
                            <button type="button" class="btn btn-success btn-modalAceptoDecli">ACEPTAR</button>
                            <button type="button" class="btn btn-danger btn-modalDeclinacion" data-dismiss="modal">CANCELAR</button>
                        </div>
                    </div> 
                </div> 
            </div>
        </div>
    </div>
</div>



<div id="modalSI" class="modal fade modal modal-alertas" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-green">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">POLIZA PROVISIONADA <i class="fa fa-check-circle"></i></h4>
            </div> 
            <div class="modal-body">    
            <span>Se ha procesado correctamente tu provisión.</span>    
             </div>
        </div>
    </div>
</div>




<div id="modalNO" class="modal fade modal modal-alertas" role="dialog">
     <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-red">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">POLIZA NO PROVISIONADA <i class="fa fa-close"></i></h4>
            </div>
            <div class="modal-body">    
            <span>Ya existe una provisión de esta solicitud, favor de verificar en tu historial.</span>    
             </div>
        </div>
    </div>
</div>

<script type="text/javascript">

    var tabla_provisiones;
    var tabla_provisiones_pago;
    var tabla_historial_provisiones;
    var idProv = "";
    var editar = false;
    var aux = new Array();
    
    $('.fechas_filtro').datepicker({
        format: 'dd/mm/yyyy',
        endDate: '-0d'
    });

    $(document).ready(function(){
        $('.faq').popover();

        $('[data-toggle="tooltip"]').tooltip();
        /*
        $("#cxp1_sel").change( function(){
            tabla_provisiones.ajax.reload();
        });

        $("#cxp2_sel").change( function(){
            tabla_provisiones.ajax.reload();
        });
        $("#cxp3_sel").change( function(){
            tabla_provisiones_pago.ajax.reload();
        });

        $("#cxp4_sel").change( function(){
            tabla_provisiones_pago.ajax.reload();
        });
        */
       const fechaActual = new Date().toLocaleDateString('es-ES', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric'
        });

        // Configura el datepicker inicial
        $('#datepicker_from').datepicker({
            dateFormat: 'dd/mm/yyyy', // Formato de fecha deseado
            defaultDate: '01/01/'+new Date().getFullYear(), // Establece la fecha predeterminada
            onSelect: function(dateText) {
                $(this).val(dateText); // Establece el valor del campo de entrada con la fecha seleccionada
            }
        });
        // Asigna la fecha predeterminada al campo de entrada
        $('#datepicker_from').datepicker('setDate', '01/01/'+new Date().getFullYear());

        // Configura el datepicker inicial
        $('#datepicker_to').datepicker({
            dateFormat: 'dd/mm/yyyy', // Formato de fecha deseado
            defaultDate: fechaActual, // Establece la fecha predeterminada
            onSelect: function(dateText) {
                $(this).val(dateText); // Establece el valor del campo de entrada con la fecha seleccionada
            }
        });
        // Asigna la fecha predeterminada al campo de entrada
        $('#datepicker_to').datepicker('setDate', fechaActual);
    });  

    $('#datepicker_from').on('keyup change', function(){
        var str = $(this).val();
        if(str.length==2 || str.length==5){
            $('#datepicker_from').val(str+'/');
        }
    }); 
    
    $('#datepicker_to').on('keyup change', function(){
        var str = $(this).val();
        if(str.length==2 || str.length==5){
            $('#datepicker_to').val(str+'/');
        }
    });

    var idSol;
$("#solAProvision").ready(function(){
           
    $("#solAProvision").ready( function () {
          
        $('#solAProvision thead tr:eq(0) th').each( function (i) {
            if( i != 0 && i != 9 ){
                var title = $(this).text();
                $(this).html( '<input type="text" style="width:100%;" placeholder="'+title+'" />' );
        
                $( 'input', this ).on( 'keyup change', function () {
                    if ( tabla_provisiones.column(i).search() !== this.value ) {
                        tabla_provisiones
                            .column(i)
                            .search( this.value )
                            .draw();
                    }
                });
            }
        }); 
        
        tabla_provisiones = $('#solAProvision').DataTable({
            dom: 'rtip',
            "language" : lenguaje,
            "processing": false,
            "pageLength": 10,
            "bAutoWidth": false,
            "bLengthChange": false,
            "bInfo": false,
            "scrollX": true,
            responsive: true,
            fixedHeader: true,
            "columns": [
                {
                    "className": 'details-control',
                    "orderable": false,
                    "data" : null,
                    "defaultContent": '<i class="fas animacion fa-caret-right"></i>'
                },
                {   
                    "width": "8%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.ID+'</p>';
                    }
                },
                {
                    "data" : function( d ){
                        var k =  '<p style="font-size: .7em">'+d.Proveedor+'</p>';
                        return k + (d.prioridad == 1 ? "<small class='label pull-center bg-red'>URGENTE</small>":"");
                    }
                },
                {
                    "width": "9%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.metoPago+'</p>';
                    }
                },
                {
                    "width": "9%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.folio+'</p>';
                    }
                },
                {
                    "width": "11%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.abrev+'</p>';
                    }
                },
                {
                    "width": "15%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">$ '+formatMoney( d.Cantidad )+" "+d.moneda+'</p>';
                    }
                },
                {
                    "width": "10%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.Fecha+'</p>';
                    }
                },
                {
                    "width": "13%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.Departamento+'</p>';
                    }
                },
                { 
                    "data" : function( d ){
                        
                        var opciones = '<div class="btn-group-vertical">';
                        
                        opciones += '<button id = "verSol" class="btn btn-instagram consultar_modal notification" value="'+d.ID+'" data-value="SOL" data-toggle="tooltip" data-placement="bottom" title="Ver Solicitud" ><i class="fas fa-eye" ></i>'+(d.visto == 0 ? '</i><span class="badge">!</span>' : '')+'</button>'
                        opciones += '<button value="'+d.ID+'" data-value="'+d.IDETAPA+'" class="btn btn-success btn-validar" data-toggle="tooltip" data-placement="bottom" title="Provisionar"><i class="far fa-share-square"></i></button>'
                        opciones += '<button value="'+d.ID+'" class="btn btn-danger btn-declinaProvision" data-toggle="tooltip" data-placement="bottom" title="Rechazar Factura"><i class="fas fa-times"></i></button>'
                        
                        return opciones+"</div>";
                    },
                    "orderable": false
                }
            ],
           
            "ajax":  url + "Contabilidad/getFC"
 
        });
         
        //new $.fn.dataTable.FixedHeader( tabla_provisiones);
        
        $('#solAProvision tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = tabla_provisiones.row( tr );
    
            if ( row.child.isShown() ) {
                row.child.hide();
                tr.removeClass('shown');
                $(this).parent().find('.animacion').removeClass("fa-caret-down").addClass("fa-caret-right");
            }
            else {

                var informacion_adicional = '<table class="table text-justify">'+
                    '<tr>'+
                        '<td><strong>JUSTIFICACIÓN: </strong>'+row.data().Concepto+'</td>'+
                    '</tr>'+
                '</table>';

                row.child( informacion_adicional ).show();
                tr.addClass('shown');
                $(this).parent().find('.animacion').removeClass("fa-caret-right").addClass("fa-caret-down");
            }
        });

        var solicitud_provision;

        $('#solAProvision').on( "click", ".btn-validar", function( e ){
            //var folprov = window.prompt("Ingresa folio de poliza");

            solicitud_provision = $(this).val();
            editar = false;

            $('#modalNumeroPoliza').modal("show");
        });
        
        
        $('.btn-modalAcepto').click(function() {
            
            var folprov = $("input#numero").val();
            var ruta = $("input#ruta").val();
            
            if(folprov !== "" && ruta !== ""){
                if(!editar){
                    $.post( url + "Contabilidad/savpoliza", { id_sol : solicitud_provision, folprov : folprov , url :ruta }, 'json' ).done( function( data ){          
                        
                        data = JSON.parse( data );
                        $('#modalNumeroPoliza').modal("toggle");

                        $("input#numero, input#ruta").val("");
                        
                        tabla_provisiones.ajax.reload(null,false);
                        tabla_historial_provisiones.ajax.reload(null,false);
 
                        if( data.resultado ){
                          $('#modalSI').modal('show');
                        }else{
                            $('#modalNO').modal('show');
                        }

                    }).fail( function(){
                         // console.log("no se procesó la solicitud_provision");
                    });
                }
                else{
                    $.post( url + "Contabilidad/UpdatePoliza", { id_sol :  aux[2], folprov : folprov , url :ruta, id_prov : aux[1] }, 'json' ).done( function( data ){          
                        $('#modalNumeroPoliza').modal("toggle");

                        $("input#numero").val("");
                        $("input#ruta").val("");
                        
                        tabla_provisiones.ajax.reload(null,false);
                        tabla_historial_provisiones.ajax.reload(null,false);

                    }).fail( function(){
                    });
                }
            }else{
                alert("Necesitas llenar todos los campos.");
            }
        });
    });
              
    $('#solAProvision').on( "click", ".btn-declinaProvision", function( e ){
        $('#modalDeclina').modal('show');
        idSol = $(this).val();
        $('.btn-modalAceptoDecli').click(function() {
            var observacion = $("textarea#motivoDeclinada").val();
            $.ajax({
                url : url + "Contabilidad/ProvisionDeclinada",
                type : "POST" ,
                dataType : "json" ,
                data :{ id_sol : idSol , Obervacion : observacion },
                error : function( data ){

                },
                complete: function( data ){
                    $("#modalDeclina").modal("toggle");
                    tabla_provisiones.ajax.reload(null,false);
                    tabla_provisiones_pago.ajax.reload(null,false);
                }
            });
        }); 
    });

    $('#numero').change(function( e ){
	   var folprov = $(this).val();
       
       if(editar && folprov == aux[0]){

       }else{
           
            $.post( url + "Contabilidad/revisarPoliza", { folprov : folprov }, 'json' ).done( function( data ){
                if( parseInt(data[0]) == 1){
                    alert('Folio Ya Existente');
                    $('#numero').val('');
                    }
                if ($('#numero').val() != "" && $('#ruta').val() != ""){
                    $('.btn-modalAcepto').prop("disabled",false);
                }
            }).fail( function(){});
        }
    });

    $('#ruta').change(function( e ){
	   
        if ($('#numero').val() != "" && $('#ruta').val() != ""){
            $('.btn-modalAcepto').prop("disabled",false);
        }
	});

	$('.btn-modalCancelar').click(function (e){

		$('#numero').val('');
                $('#ruta').val('');
	});
        
    $('.btn-modalDeclinacion').click(function() {
            $('#motivoDeclinada').val('');
    });
        
    $('.btn-modalAceptoP').click(function() {
        //var folprov = window.prompt("Ingresa folio de poliza");
        var folprov = $("input#numeroP").val();
        if(folprov){
        idSol = $('.btn-validar2').val();
        idPAGO = $('.btn-validar2').attr('data-value');
            $.ajax({
                  url : url + "Contabilidad/savpolizaP",
                  type : "POST" ,
                  dataType : "json" ,
                  data :{ id_sol : idSol,folprov : folprov , id_pago : idPAGO},
                  error : function( data ){
                    alert("no se proceso la solicitud_provision");

                 },
                 complete: function( data ){
                    $(this).closest('tr').remove();
                    $('#modalNumeroPolizaP').modal("toggle");
                    document.getElementById("numeroP").value = ""; 
                    tabla_provisiones_pago.ajax.reload(null,false);
                 }
            });
        }
        
    });

    $('.btn-modalCancelarP').click(function (e){

 		$('#numeroP').val('');
 	}); 

 	$('#numeroP').change(function (){

 		$('.btn-modalAceptoP').prop("disabled",false);
 	});
         
    $("#historialPro").ready( function () {
        $('#historialPro thead tr:eq(0) th').each( function (i) {
            if( i != 8 ){
                var title = $(this).text();
                $(this).html( '<input type="text" style="width:100%;" placeholder="'+title+'" />' );
    
                $( 'input', this ).on( 'keyup change', function () {
                    if ( tabla_historial_provisiones.column(i).search() !== this.value ) {
                        tabla_historial_provisiones
                            .column(i)
                            .search( this.value )
                            .draw();
                    }
                });
            }
        });
        tabla_historial_provisiones = $('#historialPro').DataTable({
            dom: 'Brtip',
            "buttons": [
                {
                    text: '<i class="fas fa-file-excel"></i> REPORTE FACTURAS',
                    attr: {
                        class: 'btn btn-success'
                    },
                    action: function (e, dt, node, config){
                        // bitacora_excel();
                        provisiones_excel();
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
            responsive: true,
            fixedHeader: true,
            "columns": [
                {
                    "width": "5%",
                    "data" : function( d ){
                        return '<p style="font-size: .9em">'+d.idsolicitud+'</p>';
                    }
                },
                {
                    "width": "5%",
                    "data" : function( d ){
                        return '<p style="font-size: .9em">'+d.idprovision+'</p>';
                    }
                },
                {
                    "width": "5%",
                    "data" : function( d ){
                        return '<p style="font-size: .9em">'+d.fecreg+'</p>';
                    }
                },
                {
                    "width": "15%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.numpoliza+'</p>';
                    }
                },
                {
                    "width": "15%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.nom_proveedor+'</p>';
                    }
                },
                {
                    "width": "5%",
                    "data" : function( d ){
                        return '<p style="font-size: .9em">'+d.folio_fiscal+'ASD</p>';
                    }
                },
                {
                    "width": "5%",
                    "data" : function( d ){
                        return '<p style="font-size: .9em">'+d.abrev+'</p>';
                    }
                },
                {
                    "width": "10%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.Responsable+'</p>';
                    }
                },
                {
                    "orderable": false,
                    "width": "10%",
                    "data" : function( d ){
                        var opciones = '<div class="btn-group-vertical" role="group">';
                        opciones += '<button id = "verSol" class="btn btn-instagram consultar_modal notification btn-sm" value="'+d.idsolicitud+'" data-value="SOL" data-toggle="tooltip" data-placement="bottom" title="Ver Solicitud" ><i class="fas fa-eye" ></i>'+(d.visto == 0 ? '</i><span class="badge">!</span>' : '')+'</button>'

                        if( d.resultado != null )
                        {
            
                           if(d.estatus == 1){                                
                                opciones += '<button data-toggle="tooltip" data-placement="top" title="Eliminar provisión" type="button" class="btn btn-warning btn-sm" onClick="Eliminar_Provision('+d.idprovision+',\''+d.numpoliza+'\')"><i class="fas fa-trash"></i></button>';
                                opciones += '<button data-toggle="tooltip" data-placement="top" title="Editar" type="button" class="btn btn-info btn-sm" onClick="Editar_Provision('+d.idprovision+','+d.idsolicitud+')"><i class="fas fa-share-square"></i></button>';
                                opciones += '<button data-toggle="tooltip" data-placement="top" title="Cancelar provisión" type="button" class="btn btn-danger btn-sm" onClick="Cancelar_Provision('+d.idprovision+',\''+d.numpoliza+'\')"><i class="fas fa-times"></i></button>';
                            }
                            else if (d.estatus == 0){                                
                                opciones += "<b><span class='text-red'>CANCELADA</span></b>";
                            }
                        }
                        return opciones + '</div>';
                    }
                }
                
                
            ],
            "ajax": url + "Contabilidad/HistorialProvision"
        });
        
        $('#datepicker_from').change( function() { 
            let fechaInicio = $(this).val();
            let fechaFin = $("#datepicker_to").val();
            $.ajax({
                url: 'Contabilidad/HistorialProvision', // Ruta al servidor o servicio para obtener datos
                type: 'POST',
                data: {
                    fechaInicio: fechaInicio,
                    fechaFin: fechaFin
                }, // Enviar la nueva fecha al servidor
                success: function(result) {
                    // Actualizar la DataTable con los nuevos datos
                    resultado = JSON.parse(result);
                    tabla_historial_provisiones.clear().draw();
                    tabla_historial_provisiones.rows.add(resultado.data);
                    tabla_historial_provisiones.columns.adjust().draw();
                }
            });
            $("#datepicker_from").datepicker("hide");        
        });
        
        $('#datepicker_to').change( function() { 
            let fechaInicio = $("#datepicker_to").val();
            let fechaFin = $(this).val();
            $.ajax({
                url: 'Contabilidad/HistorialProvision', // Ruta al servidor o servicio para obtener datos
                type: 'POST',
                data: {
                    fechaInicio: fechaInicio,
                    fechaFin: fechaFin
                }, // Enviar la nueva fecha al servidor
                success: function(result) {
                    // Actualizar la DataTable con los nuevos datos
                    resultado = JSON.parse(result);
                    tabla_historial_provisiones.clear().draw();
                    tabla_historial_provisiones.rows.add(resultado.data);
                    tabla_historial_provisiones.columns.adjust().draw();
                }
            });
            $("#datepicker_to").datepicker("hide");     
         });
         
    });       
      
 });   


    function Eliminar_Provision(idprovision, numpoliza){
        if( window.confirm('Se eliminará la provisión con póliza ' + numpoliza + ' ¿Estás de acuerdo?') ){
            $.get(url+"Contabilidad/EliminarProvison/"+idprovision).done(function () {  
    
                tabla_historial_provisiones.ajax.reload(null,false);

            });
        }
    }
     
    function Editar_Provision(idprovision,idsolicitud){
        $.get(url+"Contabilidad/GetProvison/"+idprovision).done(function (data) {  
            editar = true;
            data = JSON.parse(data);
            aux[0] = data['data'][0]['numpoliza'];
            aux[1] = idprovision;
            aux[2] = idsolicitud;
            $("input#numero").val(data['data'][0]['numpoliza']);
            $("input#ruta").val(data['data'][0]['rutaArchivo']);
            $('#modalNumeroPoliza').modal("show");
    });

    }
    
    function Limpiar(){

        $("input#numero").val('');
        $("input#ruta").val('');
    }
    
    function Cancelar_Provision(idprovision, numpoliza){
        if( window.confirm('Se cancelará la provisión con póliza ' + numpoliza + ' ¿Estás de acuerdo?') ){
            $.get(url+"Contabilidad/CancelarProvison/"+idprovision).done(function () {  
    
                tabla_historial_provisiones.ajax.reload(null,false);

            });
        }
    }
    // function bitacora_excel() {
    //     let fecha_inicio = $("#datepicker_from").val();
    //     let fecha_fin = $("#datepicker_to").val();
    //     $.ajax({
    //         url : url + "Reportes/descarga_bitacora_xlsx",
    //         type : "POST" ,
    //         data :{ 
    //             fecha_inicio : fecha_inicio, 
    //             fecha_fin : fecha_fin 
    //         },
    //         xhrFields: {
    //             responseType: 'blob' // Manejar la respuesta como un blob
    //         },
            
    //         success: function(data) {
    //             const url = window.URL.createObjectURL(data);
    //             const a = document.createElement('a');
    //             a.style.display = 'none';
    //             a.href = url;
    //             a.download = 'BITACORA.xlsx';
    //             document.body.appendChild(a);
    //             a.click();
    //             window.URL.revokeObjectURL(url);
    //         },
    //         error: function(jqXHR, textStatus, errorThrown) {
    //             console.error('Errors:', textStatus, errorThrown);
    //         }
    //     });
    // }

    function provisiones_excel() {
        let fecha_inicio = $("#datepicker_from").val();
        let fecha_fin = $("#datepicker_to").val();
        $.ajax({
            url : url + "Reportes/reporteFacturasProvisiones",
            type : "POST" ,
            data :{ 
                fecha_inicio : fecha_inicio, 
                fecha_fin : fecha_fin 
            },
            xhrFields: {
                responseType: 'blob' // Manejar la respuesta como un blob
            },
            
            success: function(data) {
                const url = window.URL.createObjectURL(data);
                const a = document.createElement('a');
                a.style.display = 'none';
                a.href = url;
                a.download = 'generated_file.xlsx';
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Errors:', textStatus, errorThrown);
            }
        });
    }

    $(window).resize(function(){
        tabla_provisiones.columns.adjust();
        tabla_historial_provisiones.columns.adjust();
    });
</script>     
<?php
require("footer.php");
?>
