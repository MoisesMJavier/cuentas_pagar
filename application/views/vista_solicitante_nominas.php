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
                        <h3>SOLICITUD DE PAGO NOMINA</h3>
                    </div>
                    <div class="box-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <h4>AUTORIZACIONES DE COMPRA <i class="far fa-question-circle faq" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" title="Autorizaciones" data-content="La tabla muestra todas las solicitudes pendientes de autorización para poder realizar una compra o pago a proveedor." data-placement="right"></i> TOTAL POR AUTORIZAR<b id="myText_1"></b></h4>
                                        <table class="table table-striped" id="tabla_autorizaciones">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th></th>
                                                    <th style="font-size: .8em">#</th>
                                                    <th style="font-size: .8em">FOLIOS</th>
                                                    <th style="font-size: .8em">PROYECTO / HCLAVE</th>
                                                    <th style="font-size: .8em">EMPRESA</th>
                                                    <th style="font-size: .8em">FECHA</th>
                                                    <th style="font-size: .8em">PROVEEDOR</th>
                                                    <th style="font-size: .8em">CAPTURISTA</th>                                                    
                                                    <th style="font-size: .8em">CANTIDAD</th>
                                                    <th style="font-size: .8em">ESTATUS</th>
                                                    <th style="font-size: .8em"></th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                                <hr/>
                                <div class="row">
                                    <div class="col-lg-3">
                                       <div class="input-group">
                                           <span class="input-group-addon"><i class="fas fa-calendar-alt"></i> <b>INICIO</b></span>
                                            <input class="form-control fechas_filtro from" type="text" id="datepicker_from" />
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                       <div class="input-group">
                                           <span class="input-group-addon"><i class="fas fa-calendar-alt"></i> <b>FIN</b></span>
                                            <input class="form-control fechas_filtro to" type="text" id="datepicker_to" />
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                       <div class="input-group-addon">
                                           <h4><label>Total $<input style="border-bottom: none; border-top: none; border-right: none;  border-left: none; background: white;" disabled="disabled" readonly="readonly" type="text"  name="myText_2" id="myText_2"></label></h4>
                                       </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <h4>FACTURAS ACTIVAS <i class="far fa-question-circle faq" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" data-placement="right" title="Solicitudes Activas" data-content="Estas son todas las solicitudes de pago a proveedores que se encuentran el proceso de pago, verifica el status de la solicitud para saber en que parte del proceso se encuentra."></i></h4>
                                        <hr>
                                        <table class="table table-striped" id="tblsolact">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th style="font-size: .9em">#</th>
                                                    <th style="font-size: .9em">EMPRESA</th>
                                                    <th style="font-size: .9em">PROYECTO</th>
                                                    <th style="font-size: .9em">PROVEEDOR</th>
                                                    <th style="font-size: .9em">JUSTIFICACIÓN</th>
                                                    <th style="font-size: .8em">FEC CAP</th>
                                                    <th style="font-size: .8em">FEC FAC</th>
                                                    <th style="font-size: .9em">AUTORIZACIÓN</th>
                                                    <th style="font-size: .9em">CAPTURA</th>
                                                    <th style="font-size: .9em">CANTIDAD</th>
                                                    <th style="font-size: .9em">PAGADO</th>
                                                    <th style="font-size: .9em">RESTANTE</th>
                                                    <th style="font-size: .9em">ESTATUS</th>
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

<div id="modal_formulario_solicitud" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="exampleModalLabel">NUEVA FACTURA - PAGO A PROVEEDOR</h4>
            </div>
            <div class="modal-body">
            
                        <div class="row">
                            <div class="col-lg-12"> 
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <h5><b>CARGAR DOCUMENTO XML</b></h5>
                                            <div class="input-group">
                                                <input type="file" name="xmlfile" id="xmlfile"  class="form-control" accept="application/xml">
                                                <div class="input-group-btn">
                                                    <button class="btn btn-danger" type="button" id="cargar_xml"><i class="fas fa-upload"></i> CARGAR</button>
                                                    <button class="btn btn-warning" type="button" id="recargar_formulario_solicitud"><i class="fas fa-sync-alt"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <form id="frmnewsol" method="post" action="#">
                                    <div class="row">
                                        <div class="col-lg-6 form-group">
                                            <label for="empresa">EMPRESA<span class="text-danger">*</span></label>
                                            <select name="empresa" id="empresa" class="form-control lista_empresa" required></select>
                                            <input type="hidden" class="form-control" name="empresa" disabled>
                                        </div>
                                         <div class="col-lg-6 form-group">
                                            <label for="empresa">REFERENCIA <i class="far fa-question-circle faq" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" title="Referencia" data-content="En caso de requerir una referencia favor de colocarla." data-placement="right"></i></label>
                                            <input type="text" class="form-control solo_letras_numeros" maxlength="25" name="referencia_pago" placeholder="Numeros o letras: FACT445000">
                                        </div>
                                        
                                    </div>
                                    <div class="row">
                                       
                                        <div class="col-lg-12 form-group">
                                            <label for="proveedor">PROVEEDOR<span class="text-danger">*</span> <i class="far fa-question-circle faq text-danger" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" title="Proveedores" data-content="A continuación se muestra solo los proveedores que no requieren factura o que se puede agregar posterior al pago." data-placement="right"></i></label>
                                            <select name="proveedor" class="form-control select2 select2-hidden-accessible lista_provedores_libres" style="width: 100%;" id="proveedor" required>
                                            <input type="hidden" name="idproveedor" class="form-control" id="idproveedor" >
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 form-group"> 
                                            <h5>DATOS DEL TICKET / FACTURA <i class="far fa-question-circle faq" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" title="Datos de la factura" data-content="En caso de no tener la factura deberás de llenar el campo de 'descripción', 'SubTtotal','IVA','Total' y 'Método de pago', así mismo, deberás de especificar en observaciones el por qué no tiene factura. Recuerda especificar si te darán factura después." data-placement="right"></i></h5>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6 form-group">
                                            <label for="fecha">FECHA<span class="text-danger">*</span></label>
                                            <input type="date" class="form-control" min='2019-04-01' id="fecha" name="fecha" placeholder="Fecha" value="" required>
                                        </div>
                                        <div class="col-lg-6 form-group">
                                            <label for="folio">FOLIO</label>
                                            <input type="text" class="form-control" placeholder="Folio" id="folio" name="folio" value="">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 form-group">
                                            <label for="descr">DESCRIPCIÓN</label>
                                            <textarea rows="10" class="form-control" id="descr" name="descr" placeholder="Descripción" readonly></textarea>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4 form-group">
                                            <label for="subtotal">SUBTOTAL</label>
                                            <input type="text" class="form-control" id="subtotal" name="subtotal" placeholder="SubTotal" value="" readonly>
                                        </div>
                                        <div class="col-lg-4 form-group">
                                            <label for="iva">IVA</label>
                                            <input type="text" class="form-control" id="iva" name="iva" placeholder="IVA" value="" readonly>
                                        </div>
                                        <div class="col-lg-4 form-group">
                                            <label for="total">TOTAL<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="total" name="total" placeholder="Total" value="" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4 form-group">
                                            <label for="total">MONEDA<span class="text-danger">*</span></label>
                                            <select class="form-control" id="moneda" name="moneda" required>
                                                <option selected="true" value="MXN" data-value="MXN">MXN</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-4 form-group">
                                            <label for="metpag">MÉTODO DE PAGO</label>
                                            <input type="text" class="form-control" placeholder="Método de Pago" id="metpag" name="metpag" value="" readonly>
                                        </div>
                                        <div class="col-lg-4 form-group">
                                            <label for="metpag">FORMA DE PAGO<span class="text-danger">*</span></label>
                                            <select class="form-control" id="forma_pago" name="forma_pago" required>
                                                <option value="">Seleccione una opción</option>
                                                <option value="ECHQ" data-value="ECHQ">Cheque</option>
                                                <option value="TEA" data-value="TEA">Transferencia Electrónica</option>
                                                <option value="MAN" data-value="MAN">Manual</option>
                                                <option value="DOMIC" data-value="DOMIC">Domiciliario</option>
                                                <option value="EFEC" data-value="EFEC">Efectivo</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 form-group">
                                            <label for="obse">OBSERVACIONES FACTURA <i class="far fa-question-circle faq" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" title="Observaciones de la factura" data-content="En este campo pueden ser ingresados datos opcionales como descuentos, observaciones, descripción de la operación, etc." data-placement="right"></i></label><br>
                                            <textarea class="form-control" id="obse" name="obse" placeholder="Observaciones"></textarea>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 form-group">
                                            <label for="solobs">JUSTIFICACIÓN<span class="text-danger">*</span> <i class="far fa-question-circle faq" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" title="Observaciones de la solicitud" data-content="En caso de ser necesario, se podrán especificar datos, comentarios u observaciones para cuentas por pagar, DG, DP o para la dirección del departamento solicitante." data-placement="right"></i></label>
                                            <textarea class="form-control" id="solobs" name="solobs" placeholder="Observaciones de la solicitud" required></textarea>
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

<script>

    var limite_cajachica = 0;
    var documento_xml = null;
    var link_post = "";
    var depto = "";
    var idsolicitud = 0;
    var _data1 = [];
    var _data2 = [];
    var _data3 = [];
    var depto_excep  = ['ADMINISTRACION','FUNDACION','SUB DIRECCION'];
    var idusuario_capturista = null;

    $('.solo_numeros').keyup( function(e){
        if (/\D/g.test(this.value)){
            // Filter non-digits from input value.
            this.value = this.value.replace(/\D/g, '');
        }
    });

    //Initialize Select2 Elements
    $('.select2').select2();

    $('.fechas_filtro').datepicker({
          format: 'dd/mm/yyyy'
    });

    $('input[type=radio][name=servicio1]').change(function() {

        $("input[type=hidden][name='servicio']").val(this.value).prop('disabled', false);
       
        AutocompleteProveedor(_data3.length == 0 ? (this.value == '9' ? _data2 : _data1) : _data3);

    });

    var today = new Date();
    var yesterday = new Date( today );
    yesterday.setDate( today.getDate() - 40 );
    var fecha_minima = yesterday.toISOString().substr(0, 10);

    function recargar_provedores(){
        $(".lista_provedores_libres").append('<option value="">Seleccione una opción</option>');
        $.getJSON( url + "Listas_select/lista_proveedores_nomina" ).done( function( data ){
            $(".lista_provedores_libres").html('');
            $(".lista_provedores_libres").append('<option value="">Seleccione una opción</option>');
            
            _data1 = [];
            _data2 = [];

            $.each( data.provedores_disponibles, function( i, v){
                _data1.push({value : v.idproveedor, excp : v.excp, label : v.nombre + " - " + v.nom_banco, rfc: v.rfc});
                    
                _data2.push({value : v.idproveedor, excp : v.excp, label : v.nombre + " - " + v.nom_banco, rfc: v.rfc});
            });
            
            AutocompleteProveedor(_data1);
            $(".lista_empresa").html('');
            $(".lista_empresa").append('<option value="">Seleccione una opción</option>');
            $.each( data.empresas, function( i, v){
                $(".lista_empresa").append('<option value="'+v.idempresa+'" data-value="'+v.rfc+'">'+v.nombre+'</option>');
            });       
        });
    }

    $("#frmnewsol").submit( function(e) {
        e.preventDefault();
    }).validate({
        submitHandler: function( form ) {
            if($("#proveedor").val() != ""){

                    var data = new FormData( $(form)[0] );
                    data.append("idsolicitud", idsolicitud);
                    data.append("xmlfile", documento_xml);

                    if( !$("#proveedor").prop("disabled") ){
                        data.append("idproveedor", $("#proveedor").val());
                    }
                    
                    data.append("descr", $("#descr").val());

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
                            if( data.resultado ){
                                //resear_formulario();
                                $("#modal_formulario_solicitud").modal( 'toggle' );
                                table_autorizar.ajax.reload();
                            }else{
                                alert("NO SE HA PODIDO COMPLETAR LA SOLICITUD");
                            }
                        },error: function( ){
                            
                        }
                    });

            }else{
                alert("HA INGRESADO UN PROVEEDOR NO REGISTRADO, PARA PODER HACER ALGÚN MOVIMIENTO NECESITA REGISTRARLO PREVIAMENTE.");
            }
        }
    });

    //FUNCION PARA LIMPIAR EL FORMULARIO CON DE PAGOS A PROVEEDOR.
    function resear_formulario(){
        $("#modal_formulario_solicitud input.form-control").prop("readonly", false).val("");
        $("#modal_formulario_solicitud textarea").html('');

        $("#modal_formulario_solicitud #solobs").val('');
        $("#modal_formulario_solicitud #descr").val('');
        $("#modal_formulario_solicitud #obse").val('');
        $("#modal_formulario_solicitud textarea").prop("readonly", false);
        $("#empresa, #proveedor, #xmlfile").prop('disabled', false);
        $("#empresa option, #proveedor option, #forma_pago option, #proyecto option, #homoclave option").prop("selected", false);
        $("#modal_formulario_solicitud .form-control, #modal_formulario_solicitud input[type='checkbox']").prop("checked", false).prop('disabled', false);
        $("input[type='hidden']").val("").prop('disabled', true);
        $(".programar_fecha").prop('disabled', true)

        $("#moneda").html( "" );
        $("#moneda").append( '<option value="MXN" data-value="MXN">MXN</option>' );
        
        $("#modal_formulario_solicitud #descr").prop("readonly", true).val('');
        $("#modal_formulario_solicitud #subtotal").prop("readonly", true).val('');
        $("#modal_formulario_solicitud #iva").prop("readonly", true).val('');
        $("#modal_formulario_solicitud #metpag").prop("readonly", true).val('');
        $("#modal_formulario_solicitud #obse").prop("readonly", true).val('');
        $('.default').prop('checked', true);
        $("input[type=radio][name=servicio1][value=0]").prop('checked', true );
        $("input[type=radio][name=servicio1]").prop('disabled', false );

        AutocompleteProveedor(_data1);
        $("#idproveedor").val('');

        var validator = $( "#frmnewsol" ).validate();
        validator.resetForm();
        $( "#frmnewsol div" ).removeClass("has-error");

        $("#fecha").attr( "min",  fecha_minima )

        documento_xml = null;
        _data3 = [];

        $("input[name='tentra_factura']").prop( "checked", false ).prop('disabled', true);
        $("input[type='hidden'][name='tentra_factura']").val("").prop('disabled', false);        
    }

        $(document).on( "click", ".abrir_nueva_solicitud", function(){
            resear_formulario();
            recargar_provedores();
            link_post = "Solicitante/guardar_solicitud_nominas";
            $("#modal_formulario_solicitud").modal( {backdrop: 'static', keyboard: false} );
        });

        $("#recargar_formulario_solicitud").click( function(){
            resear_formulario();
            recargar_provedores();
        });

        $("#cargar_xml").click( function(){
            subir_xml( $("#xmlfile") );
        });

        var justificacion_globla = "";

        function subir_xml( input ){

            var data = new FormData();
            documento_xml = input[0].files[0];
            var xml = documento_xml;

            data.append("xmlfile", documento_xml);
            data.append("cajachica", ( $("input[name='servicio1']:checked").val() ? $("input[name='servicio1']:checked").val()  : $("input[type=hidden][name='servicio']").val() ) );//usa valor del radio para saber si es caja chica

            resear_formulario();

            $.ajax({
                url: url + "Solicitante/cargaxml",
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                method: 'POST',
                type: 'POST', // For jQuery < 1.9
                success: function(data){
                    if( data.respuesta[0] ){

                        limite_cajachica = data.limite;
                        documento_xml = xml;

                        var informacion_factura = data.datos_xml
                        input.prop('disabled', true); 
                        _data3 = [] ;
                        if( data.proveedorcc ){  
                            $("#proveedor").empty(); 
                                $("#proveedor").append('<option value="" SELECTED disable >Seleccione una opción</option>'); 
                            $.each(data.proveedorcc, function (i, item) {                                
                                _data3.push({value : item.idproveedor, excp : item.excp, label : item.nombre + " - " + item.nomba, rfc: item.rfc});
                            });

                            AutocompleteProveedor(_data3);
                            $("#idproveedor").val(data.proveedorcc[0].idproveedor);
                            $(".caja_chica_label").prop("checked",true);
                            $("input[type=hidden][name='servicio']").val(9)
                            if(data.proveedorcc.length > 1){
                                $("#proveedor").val($("#proveedor option:first").val());
                            }else{
                                $('#proveedor option:eq(1)').attr('selected', 'selected');
                            }
                        }

                        cargar_info_xml( informacion_factura );
                        $("#solobs").val( justificacion_globla );

                    }else{
                        input.val('');
                        alert( data.respuesta[1] );
                    }
                },
                error: function( data ){
                    input.val('');
                    alert("ERROR INTENTE COMUNIQUESE CON EL PROVEEDOR");
                }
            });

        }

    function cargar_info_xml( informacion_factura ){

        $.each( informacion_factura.conceptos, function( i, v ){
            $("#descr").append( (i+1) + ") "+v['@attributes']['Descripcion']+" - Importe: $"+v['@attributes']['Importe']+" \n");
        });

        $("#descr").prop('readonly',true);

        var fecha = informacion_factura.fecha[0];
        $("#fecha").val( fecha.substring( 0, 10 ) ).attr('readonly', true);

        $("#fecha").attr( "min",  fecha.substring( 0, 10 ) )

        $("#folio").val( informacion_factura.folio[0] ).attr('readonly', true);
        $("#subtotal").val( informacion_factura.SubTotal[0] ).attr('readonly', true);
        $("#iva").val( informacion_factura.Total[0] -informacion_factura.SubTotal[0] ).attr('readonly', true);
        $("#total").val( informacion_factura.Total[0] ).attr('readonly', true);
        $("#metpag").val( ( informacion_factura.MetodoPago ? informacion_factura.MetodoPago[0] : '') ).attr('readonly',true);


        $("#empresa option").each( function(){
            if( $(this).attr('data-value') != informacion_factura.rfcrecep[0] ){
                $(this).remove();
            }
        });

        if( $("#proveedor option:selected").attr( "data-privilegio" ) == 2 ){
            $("input[name='tentra_factura']").prop( "checked", false ).prop('disabled', true);
            $("input[type='hidden'][name='tentra_factura']").val("").prop('disabled', true);
        }else{
            $("input[name='tentra_factura']").prop( "checked", true ).prop('disabled', true);
            $("input[type='hidden'][name='tentra_factura']").val("1").prop('disabled', false);
        }

        if($("input[type=hidden][name='servicio']").val() != 9){
            $("#proveedor option").each( function(){
                if( $(this).attr('data-value') != informacion_factura.rfcemisor[0] ){
                    $(this).remove();
                }
            });
            $("#idproveedor").val($("#proveedor").val()).prop('disabled', false);
        }

        $("#moneda").html( '' );
        $("#moneda").append( '<option value="'+informacion_factura.Moneda[0]+'" data-value="'+informacion_factura.Moneda[0]+'">'+informacion_factura.Moneda[0]+'</option>' );

        $("input[name='tentra_factura']").prop("checked", true).prop('disabled', true);
        $("input[type='hidden'][name='tentra_factura']").val("1").prop('disabled', false);

        var formapago = informacion_factura.formpago ? informacion_factura.formpago[0] : "N/A";

        $("#obse").append( informacion_factura.condpago ? informacion_factura.condpago[0] : "NA" );
        $("#obse").prop( 'readonly', true );
        
        $("input[name='empresa']").val($("#empresa").val()).prop('disabled', false);

        switch (informacion_factura.formpago[0]){
            case "01":
                if( informacion_factura.Total[0] <= limite_cajachica ){
                    $("input[name='servicio1']").prop("checked", false);
                    $(".caja_chica_label").prop("checked", true);
                    $(".pago_proveedor").prop("disabled", true);
                    
                }else{
                    $(".caja_chica_label").prop("disabled", true);
                    $(".caja_chica_label").prop("checked", false);
                    $(".pago_proveedor").prop("disabled", false);
                    alert("ESTA FACTURA ES MAYOR A LO PERMITIDO COMO PAGO EN EFECTIVO. $ "+ formatMoney( limite_cajachica )+ " PESOS");
                    resear_formulario();
                }
                $("input[type=hidden][name='servicio']").val(9);
                break;
            case "03":
            case "04":
            case "05":
            case "06":
            case "28":
                $("input[name='servicio1']").prop("checked", false);
                $(".caja_chica_label").prop("checked", true);
                $(".pago_proveedor").prop("disabled", true);
                $("input[type=hidden][name='servicio']").val(9);
                break;
            default:
                $(".caja_chica_label").prop("disabled", true);
                $(".caja_chica_label").prop("checked", false);
                $(".pago_proveedor").prop("disabled", false);
                $("input[type=radio][name=servicio1][value=0]").prop('checked', true );
                break;
        }
        
    }

    var table_autorizar;

    $("#tabla_autorizaciones").ready( function () {

        $('#tabla_autorizaciones').on('xhr.dt', function ( e, settings, json, xhr ) {
            table_autorizar.button( 1 ).enable( parseInt(json.por_autorizar) > 0 );
            
            
            var total = 0;
            
            $.each( json.data,  function(i, v){
                total += parseFloat(v.cantidad);
            });

            var to = formatMoney(total);
            $("#myText_1").html( "$ " + to );
            /*
            document.getElementById("myText_1").value = to;
            */
            
            
        });

        $('#tabla_autorizaciones thead tr:eq(0) th').each( function (i) {
            if( i != 0 && i != 10 ){
                var title = $(this).text();
                $(this).html( '<input type="text" style="font-size: .9em; width: 100%;" placeholder="'+title+'" />' );
        
                $( 'input', this ).on( 'keyup change', function () {
                    if ( table_autorizar.column(i).search() !== this.value ) {
                        table_autorizar
                            .column(i)
                            .search( this.value )
                            .draw();
                           var total = 0;
                           var index = table_autorizar.rows( { selected: true, search: 'applied' } ).indexes();
                           var data = table_autorizar.rows( index ).data();
                           $.each(data, function(i, v){
                               total += parseFloat(v.cantidad);
                           });
                           var to1 = formatMoney(total);
                           $("#myText_1").html( "$ " + to1 );
                    }
                } );
            }
        });

        table_autorizar = $('#tabla_autorizaciones').DataTable({
            dom: 'Brtip',
            buttons: [
                {
                    text: '<i class="fas fa-plus"></i> NUEVA SOLICITUD',
                    attr: {
                        class: 'btn btn-success abrir_nueva_solicitud'
                    }
                },
                {
                    text: '<i class="fas fa-print"></i> AUT. PAGO A PROVEEDORES',
                    action: function(){
                        window.open( url + "Consultar/documentos_autorizacion", "_blank")
                    },
                    attr: {
                        class: 'btn btn-danger imprimir_pago_provedores',
                    }
                }
            ],
            //"stateSave": true,
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
                    "width": "8%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.idsolicitud+'</p>'
                    }
                },
                { 
                    "width": "8%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">INT '+d.folio+'<br/> FISC '+(d.uuid).substr(-5)+'</p>'
                    }
                },
                {
                    "width": "10%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">PROY '+d.proyecto+'</p>' + ( d.homoclave && d.homoclave != 'N/A' ? '<p style="font-size: .8em">HCL '+d.homoclave+'</p>' : '' ) ;
                    }
                },
                {
                    "width": "9%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.abrev+'</p>'
                    }
                },
                {
                    "width": "9%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.fecelab+'</p>'
                    }
                },
                {
                    "width": "12%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.nombre+'</p>'
                    }
                },
                {
                    "width": "12%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.nombre_completo+'</p>'
                    }
                },
                {
                    "width": "12%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">$ '+formatMoney( d.cantidad )+" "+d.moneda+'</p>'
                    }
                },
                {
                    "width": "8%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.etapa+'</p>'
                    }
                },
                { 
                    "data" : "opciones",
                    "orderable": false
                }
            ],
            "columnDefs": [ {
                "orderable": false, "targets": 0
            }],
            "ajax":  url + "Solicitante/tabla_autorizaciones_nominas_gastos"
        });
        
        $("#tabla_autorizaciones").DataTable().rows().every( function () {
            var tr = $(this.node());
            this.child(format(tr.data('child-value'))).show();
            tr.addClass('shown');
        });
        
        $('#datepicker_from').change( function() { 
            table_proceso.draw(); 
           var total = 0;
           var index = table_proceso.rows( { selected: true, search: 'applied' } ).indexes();
           var data = table_proceso.rows( index ).data();
           $.each(data, function(i, v){
               total += parseFloat(v.cantidad);
             });
           var to1 = formatMoney(total);
           document.getElementById("myText_2").value = to1;
        
        });
        
        $('#datepicker_to').change( function() {
            table_proceso.draw(); 
           var total = 0;
           var index = table_proceso.rows( { selected: true, search: 'applied' } ).indexes();
           var data = table_autorizar.rows( index ).data();
           $.each(data, function(i, v){
               total += parseFloat(v.cantidad);
             });
           var to1 = formatMoney(total);
           document.getElementById("myText_2").value = to1;
         });

        $('#tabla_autorizaciones tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = table_autorizar.row( tr );
    
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

        $('#tabla_autorizaciones').on( "click", ".editar_factura", function(){

            var tr = $(this).closest('tr');
            var row = table_autorizar.row( tr );

            link_post = "Solicitante/editar_solicitud";

            //var limite_edicion = row.data().idetapa > 1 ? 2 : 1;
            var ideditar =  $(this).val();
            
            resear_formulario();


            $.post( url + "Solicitante/informacion_solicitud", { idsolicitud : ideditar } ).done( function( data ){
                
                data = JSON.parse( data );
                //alert(JSON.stringify(data));
                if( data.resultado ){


                  _data3 = [];

                    idsolicitud = ideditar;

                    $(".lista_provedores_libres").html('');
                    $(".lista_provedores_libres").append('<option value="">Seleccione una opción</option>');
                    _data1 = [];
                    _data2 = [];
                    $.each( data.proveedores_todos, function( i, v){
                        if(!(v.nombrep.includes('GASTO NO DEDUCIBLE')))
                        {
                            _data1.push({value : v.idproveedor, excp : v.excp, label : v.nombrep + " - " + v.nom_banco, rfc: v.rfc});
                            _data2.push({value : v.idproveedor, excp : v.excp, label : v.nombrep + " - " + v.nom_banco, rfc: v.rfc});
                        }
                        else
                        {
                            _data2.push({value : v.idproveedor, excp : v.excp, label : v.nombrep + " - " + v.nom_banco, rfc: v.rfc});
                        }                        
                    });
                    AutocompleteProveedor(data.info_solicitud[0].caja_chica ? _data2 : _data1 );

                    $(".lista_empresa").html('');
                    $(".lista_empresa").append('<option value="">Seleccione una opción</option>');
                    $.each( data.empresas, function( i, v){
                        $(".lista_empresa").append('<option value="'+v.idempresa+'" data-value="'+v.rfc+'">'+v.nombre+'</option>');
                    });                  
                    
                    if( data.xml ){
                        cargar_info_xml( data.xml );
                        $("#fecha").prop('disabled', true );
                        $("#folio").prop('disabled', true );

                        $("#empresa").prop('disabled', true );
                        $("#proveedor").prop('disabled', true );
                        //$("#moneda").prop('disabled', true );
                            
                        //$("#forma_pago").prop('disabled', true );
                        $("#total").prop('disabled', true );
                        $("input[type=radio][name=servicio1]").prop('disabled', true );
                        $("#tentra_factura").prop('disabled', true );
                        //$("input[name='tentra_factura']").prop('disabled', true );
                    }else{
                        $("#descr").append( data.xml ? data.xml[0].conceptos : "" );
                        $("#fecha").val( data.info_solicitud[0].fecelab );
                        $("#folio").val( data.info_solicitud[0].folio );
                        
                        $("#subtotal").val( "" );
                        $("#iva").val( "" );
                        $("#total").val( data.info_solicitud[0].cantidad );
                        $("#metpag").val( "" );
                        $("#moneda").html( "" );
                        $("#moneda").append( '<option value="'+data.info_solicitud[0].moneda+'" data-value="'+data.info_solicitud[0].moneda+'">'+data.info_solicitud[0].moneda+'</option>' );
                    

                        $("#empresa option[data-value='"+data.info_solicitud[0].rfc_empresas+"']").prop("selected", true);
                        $("#proveedor option[data-value='"+data.info_solicitud[0].idProveedor+"']").prop("selected", true);

                        //$("input[name='caja_chica']").prop('disabled', ( data.info_solicitud[0].cantidad > 2000 ? true : false ) );x
                        $("input[name='tentra_factura']").prop('disabled',!depto_excep.includes(data.info_solicitud[0].nomdepto) );                        
                        $("input[type=radio][name=servicio1]").prop('disabled', false );

                    }

                    $("input[name='referencia_pago']").val( data.info_solicitud[0].ref_bancaria );
                    $("input[type=hidden][name='servicio']").val(data.info_solicitud[0].caja_chica ? '9' : data.info_solicitud[0].servicio).prop('disabled', false);
                    $("input[name='tentra_factura']").prop("checked", ( data.info_solicitud[0].tendrafac == 1 ? true : false ) );      
                    $("input[name='prioridad']").prop("checked", ( data.info_solicitud[0].prioridad ? true : false ) ); 
                    $("input[type=radio][name=servicio1][value='1']").prop("checked",( data.info_solicitud[0].servicio == 1 ? true : false ));
                     $("input[type=radio][name=servicio1][value='0']").prop("checked",( data.info_solicitud[0].servicio == null || data.info_solicitud[0].servicio == 0 ? true : false ));      
                      
                    $(".caja_chica_label").prop('checked', ( data.info_solicitud[0].caja_chica ? true : false ) );
                    $("#solobs").val( data.info_solicitud[0].justificacion );

                    
                    $("#Etapa").val(data.info_solicitud[0].etapa);
                    $("#condominio").val(data.info_solicitud[0].condominio);
                    
                    justificacion_globla = data.info_solicitud[0].justificacion
                    $("#proyecto option[value='"+data.info_solicitud[0].proyecto+"']").prop("selected", true);
                    
                    $("#homoclave option[value='"+data.info_solicitud[0].homoclave+"']").prop("selected", true);
                    $("#forma_pago option[data-value='"+data.info_solicitud[0].metoPago+"']").prop("selected", true);
                    $("#proveedor option[value='"+data.info_solicitud[0].idProveedor+"']").prop("selected", true);
                    //$("#proveedor").val(data.info_solicitud[0].nombre_proveedor);
                    $("#idproveedor").val(data.info_solicitud[0].idProveedor);
                    uno = data.info_solicitud[0].nombre_proveedor;
                    dos = data.info_solicitud[0].idProveedor;
           

                    $("#modal_formulario_solicitud").modal( {backdrop: 'static', keyboard: false} );

                }else{
                    alert("HA OCURRIDO UN ERROR")
                }
            });
        });

        $('#tabla_autorizaciones').on( "click", ".borrar_solicitud", function(){
            $.post( url + "Solicitante/borrar_solicitud", { idsolicitud : $(this).val() } ).done( function( data ){
                data = JSON.parse( data );
                if( data.resultado ){
                    table_autorizar.ajax.reload(null, false);
                }else{
                    alert("HA OCURRIDO UN ERROR")
                }
            });
        });

        $('#tabla_autorizaciones').on( "click", ".enviar_a_dg", function(){
            $.post( url + "Solicitante/enviar_a_dg", { idsolicitud : $(this).val() } ).done( function( data ){
                data = JSON.parse( data );
                if( data.resultado ){
                    table_autorizar.ajax.reload(null,false);
                    table_proceso.ajax.reload(null,false);
                }else{
                    alert("HA OCURRIDO UN ERROR")
                }
            });
        });

        $('#tabla_autorizaciones').on( "click", ".reenviar_factura", function(){
            $.post( url + "Solicitante/reenviar_factura", { idsolicitud : $(this).val() } ).done( function( data ){
                data = JSON.parse( data );
                if( data.resultado ){
                    table_autorizar.ajax.reload(null, false);
                    table_proceso.ajax.reload(null,false);
                }else{
                    alert("HA OCURRIDO UN ERROR")
                }
            });
        });

        // $('#tabla_autorizaciones').on( "click", ".aprobada_da", function(){
        //     var tr = $(this).closest('tr');
        //     var row = table_autorizar.row(tr).data();
        //     $.post( url + "Solicitante/enviar_nomina", { idsolicitud : $(this).val(), fecelab : row.fecelab, caja_chica : row.caja_chica } ).done( function( data ){
        //         data = JSON.parse( data );
        //         if( data.resultado ){
        //             table_autorizar.row( tr ).remove().draw(false);
        //             var total = parseFloat($('#myText_1').text().replace("$ ","").replace(",","")) - parseFloat( row.cantidad );                    
        //             $("#myText_1").html( "$ " + formatMoney(total) );
        //             //table_autorizar.ajax.reload(null, false);
        //             //table_proceso.ajax.reload(null,false);
        //         }else{
        //             alert( data.mensaje );
        //         }
        //     });
        // });

        $('#tabla_autorizaciones').on( "click", ".rechazada_da", function(){
            $.post( url + "Solicitante/rechazada_da", { idsolicitud : $(this).val() } ).done( function( data ){
                data = JSON.parse( data );
                if( data.resultado ){
                    table_autorizar.ajax.reload(null,false);
                }else{
                    alert("HA OCURRIDO UN ERROR")
                }
            });
        });

        $('#tabla_autorizaciones').on( "click", ".congelar_solicitud", function(){
            $.post( url + "Solicitante/congelar_solicitud", { idsolicitud : $(this).val() } ).done( function( data ){
                data = JSON.parse( data );
                if( data.resultado ){
                    table_autorizar.ajax.reload(null,false);
                }else{
                    alert("HA OCURRIDO UN ERROR")
                }
            });
        });

        $('#tabla_autorizaciones').on( "click", ".liberar_solicitud", function(){
            $.post( url + "Solicitante/liberar_solicitud", { idsolicitud : $(this).val() } ).done( function( data ){
                data = JSON.parse( data );
                if( data.resultado ){
                    table_autorizar.ajax.reload(null,false);
                }else{
                    alert("HA OCURRIDO UN ERROR")
                }
            });
        });

    });

    var table_proceso;

    $('#tblsolact').on('xhr.dt', function ( e, settings, json, xhr ) {
            
        var total = 0;
            
        $.each( json.data,  function(i, v){
            total += parseFloat(v.cantidad);
        });

        var to = formatMoney(total);
        document.getElementById("myText_2").value = to;

        idusuario_capturista = json.idloguin_usuario;

    });

    $("#tblsolact").ready( function () {
        
        $('#tblsolact thead tr:eq(0) th').each( function (i) {
            if( i != 13 ){
                var title = $(this).text();
                $(this).html( '<input type="text" style="font-size: .9em; width:100%" placeholder="'+title+'" />' );
        
                $( 'input', this ).on( 'keyup change', function () {
                    if ( table_proceso.column(i).search() !== this.value ) {
                        table_proceso
                            .column(i)
                            .search( this.value )
                            .draw();
                    }

                    var total = 0;
                    var index = table_proceso.rows( { selected: true, search: 'applied' } ).indexes();
                    var data = table_proceso.rows( index ).data();
                    
                    $.each(data, function(i, v){
                        total += parseFloat( v.cantidad - v.pagado );
                    });

                    var to1 = formatMoney(total);
                    document.getElementById("myText_2").value = to1;

                });
            }
        });
        

        table_proceso = $('#tblsolact').DataTable({
            dom: 'Brtip',
            "buttons": [
                {
                    extend: 'excelHtml5',             
                    text: '<i class="fas fa-file-excel"></i> EXPORTAR EXCEL',
                    messageTop: "Lista de pagos autorizados por Dirección General (CHEQUES)",
                    attr: {
                        class: 'btn btn-success'       
                    },
                    exportOptions: {
                        format: {
                            header: function (data, columnIdx) { 

                                data = data.replace( '<input type="text" style="font-size: .9em; width:100%" placeholder="', '' );
                                data = data.replace( '">', '' )
                                return data;
                            }
                        }
                    }
                }
            ],
            "language" : lenguaje,
            //"stateSave": true,
            "processing": false,
            "pageLength": 10,
            "bAutoWidth": false,
            "bLengthChange": false,
            "bInfo": false,
            "scrollX": true,
            "columns": [
                {
                    "width": "5%",
                    "data" : function( d ){
                        return '<p style="font-size: .9em">'+d.idsolicitud+'</p>'
                    }
                },
                {
                    "width": "8%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.abrev+'</p>'
                    }
                },
                {
                    "width": "10%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.proyecto+'</p>'
                    }
                },
 
 
                {
                    "width": "12%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.nombre+'</p>'
                    }
                },
          
                {
                    "width": "10%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.justificacion+'</p>'
                    }
                },
                {
                    "width": "8%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.fechaCreacion+'</p>'
                    }
                },
                {
                    "width": "8%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.fecelab+'</p>'
                    }
                },
                {
                    "width": "8%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.fecha_autorizacion+'</p>'
                    }
                },
                {
                    "width": "8%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.nombre_completo+'</p>'
                    }
                },
                {
                    "width": "8%",
                    "data" : function( d ){

                        var resultado = d.cantidad;

                        if( d.programado && (d.programado < 7 || d.programado != "7")  ){
                            resultado = d.cantidad * ( d.mpagar / d.programado );
                        }
                        
                        if( d.programado && (d.programado == 7 || d.programado == "7") ){
                            resultado = d.cantidad * d.mpagar;
                        }

                        return '<p style="font-size: .8em"> '+formatMoney(resultado)+" "+d.moneda+'</p>'
                    }
                },
                {
                    "width": "8%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em"> '+formatMoney(d.pagado)+'</p>'
                    }
                },
                {
                    "width": "8%",
                    "data" : function( d ){

                        var resultado = d.cantidad;

                        if( d.programado && (d.programado < 7 || d.programado != "7")  ){
                            resultado = d.cantidad * ( d.mpagar / d.programado );
                        }
                        
                        if( d.programado && (d.programado == 7 || d.programado == "7") ){
                            resultado = d.cantidad * d.mpagar;
                        }

                        return '<p style="font-size: .8em"> '+formatMoney( resultado - d.pagado )+'</p>'
                    }
                },
                {
                    "width": "10%",
                    "data" : function( d ){
                        if( d.etapa == 'Revisión Cuentas Por Pagar'){
                            var fec =  new Date();//new Date(fec[1]+'/'+fec[0]+"/"+fec[2]);
                            var day = fec.getDay();
                            var dias = 2-day;
                            if (dias > 0){
                                fec.setDate(fec.getDate() + dias);
                            }else if (dias < 0){
                                fec.setDate(fec.getDate() + (7 + dias));
                            }else if( dias == 0){                                
                                if(d.fecha_autorizacion == dateToDMY(fec)){
                                    fec.setDate(fec.getDate() + 7);
                                }
                            }
                            return '<p style="font-size: .8em">Próxima revisión de Cuentas por Pagar</p>' + 
                            '<p style="font-size: .8em">'+dateToDMY(fec)+'</p>'
                            }
                        else{
                            return '<p style="font-size: .8em">'+d.etapa+'</p>'
                        }
                    }
                },
                {
                    "orderable": false,
                    "data": function( d ){
                        
                        opciones = '<div class="btn-group-vertical">';
                        opciones += '<button type="button" class="btn btn-primary btn-sm consultar_modal notification" value="'+d.idsolicitud+'" data-value="BAS"><i class="fas fa-eye"></i>'+(d.visto == 0 ? '<span class="badge">!</span>' : '')+'</button>';
                        
                        if( idusuario_capturista && $.inArray( idusuario_capturista, [ "21", "45", "99", "75" ] ) > -1 ){
                            opciones += '<button type="button" class="btn btn-warning btn-sm comentario_especial" value="'+d.idsolicitud+'" data-value="BAS"><i class="fas fa-bullhorn"></i></button>';
                        }
                        return opciones + '</div>';
                    }
                }
            ],
            "columnDefs": [ {
                   "orderable": false
                } 
                 
            ],
            "ajax":  url + "Solicitante/tabla_facturas_encurso_nominas_gastos"
        });

        

        $('#tblsolact tbody').on('click', '.comentario_especial', function () {
            idsolicitud = $( this ).val();
        });

        $('#tblsolact tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = table_proceso.row( tr );
    
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

    var id;
    
    $.fn.dataTableExt.afnFiltering.push( function( oSettings, aData, iDataIndex ) {

        if( oSettings.nTable.getAttribute('id') == "tblsolact" ){

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
                    
            var iStartDateCol = 9;
            var iEndDateCol = 9;

            iFini=iFini.substring(6,10) + iFini.substring(3,5)+ iFini.substring(0,2);
            iFfin=iFfin.substring(6,10) + iFfin.substring(3,5)+ iFfin.substring(0,2);
                //alert(iFini);
                // alert(iFfin);
            var datofini=aData[iStartDateCol].substring(6,10) + aData[iStartDateCol].substring(3,5)+ aData[iStartDateCol].substring(0,2);
            var datoffin=aData[iEndDateCol].substring(6,10) + aData[iEndDateCol].substring(3,5)+ aData[iEndDateCol].substring(0,2);
                    //alert(datofini);
                    //alert(datoffin);
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
        }else{
            return true;
        }

	});
    function dateToDMY(date) {
        var d = date.getDate();
        var m = date.getMonth() + 1; //Month from 0 to 11
        var y = date.getFullYear();
        return ''  + (d <= 9 ? '0' + d : d) + '/' + (m<=9 ? '0' + m : m) + '/' + y ;
    }
    /* ------------------------------------------------------------------------------------------------------- */
    var uno ="";
    var dos ="";
    var tres ="";

    function AutocompleteProveedor(data){
        var id =  $(".lista_provedores_libres").val();
        $(".lista_provedores_libres").empty().append('<option value="" selected>Seleccione una opción</option>');
            $.each(data, function (i, item) {
                $('.lista_provedores_libres').append('<option value="'+item.value+'" data-privilegio="'+item.excp+'" data-value="'+item.rfc+'">'+item.label+'</option>');
        });
        $(".lista_provedores_libres option[value='"+id+"']").prop("selected", true);
    }

    $("#proveedor").change( function(){
        if( $("#proveedor option:selected").attr( "data-privilegio" ) == 2 ){
            $("input[name='tentra_factura']").prop( "checked", false ).prop('disabled', true);
            $("input[type='hidden'][name='tentra_factura']").val("").prop('disabled', true);
        }else{
            $("input[name='tentra_factura']").prop( "checked", true ).prop('disabled', true);
            $("input[type='hidden'][name='tentra_factura']").val("1").prop('disabled', false);
        }
    });

    $(".solo_letras_numeros").keypress( function ( key ) {
        window.console.log( key.charCode )
        if ( 
            (key.charCode < 97 || key.charCode > 122)//letras mayusculas
            && (key.charCode < 65 || key.charCode > 90) //letras minusculas
            && (key.charCode < 47 || key.charCode > 57 ) //numeros del 0 - 9
            && (key.charCode != 45) //retroceso 
        )
            return false;
    });

</script>
<?php
    require("footer.php");
?>