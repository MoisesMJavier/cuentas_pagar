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
                        <h3>SOLICITUD DE PRÉSTAMOS, FINIQUITOS Y OTROS <button data-toggle="tooltip" data-placement="right" title="Actualizar tabla" class="btn btn-link btn-sm" id="refreshButton" onclick="recargar()"><i class="fa fa-refresh" style="font-size: 12px;" aria-hidden="true"></i></button></h3> <!-- FECHA: 06-JUNIO-2025 | NUEVO CONCEPTO SOLO DEPTO CH | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                    </div>
                    <div class="box-body">
                        <div class="tab-content">
                            <div class="active tab-pane" id="facturas_aturizar">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <h4>AUTORIZACIONES DE PENDIENTES <i class="far fa-question-circle faq" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" title="Autorizaciones" data-content="La tabla muestra todas las solicitudes pendientes de autorización para poder realizar una compra o pago a proveedor." data-placement="right"></i> TOTAL POR AUTORIZAR&nbsp;<b id="myText_1"></b></h4> <!-- FECHA: 31-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                                        <table class="table table-striped" id="tabla_autorizaciones">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th><input style="width: 15px; height: 15px; cursor:pointer;" title="Seleccionar Todo" type="checkbox"></th> <!-- /** FECHA: 08-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/ -->
                                                    <th style="font-size: .9em"></th>
                                                    <th style="font-size: .9em">#</th> <!-- /** FECHA: 08-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/ -->
                                                    <th style="font-size: .9em">PROYECTO</th>
                                                    <th style="font-size: .9em">SERVICIO PARTIDA</th> <!-- /** FECHA: 08-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/ -->
                                                    <th style="font-size: .9em">FECHA</th>
                                                    <th style="font-size: .9em">PROVEEDOR</th>
                                                    <th style="font-size: .9em">EMPRESA</th>
                                                    <th style="font-size: .9em">OPERACION</th>
                                                    <th style="font-size: .9em">CAPTURISTA</th>                                                    
                                                    <th style="font-size: .9em">CANTIDAD</th>
                                                    <th style="font-size: .9em">CANTIDAD</th>
                                                    <th style="font-size: .9em">FORMA_DE_PAGO</th>
                                                    <th style="font-size: .9em">ESTATUS</th>
                                                    <th style="font-size: .9em">OFICINA</th>
                                                    <th style="font-size: .9em"></th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                                <hr/>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <h4>FACTURAS ACTIVAS <i class="far fa-question-circle faq" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" data-placement="right" title="Solicitudes Activas" data-content="Estas son todas las solicitudes de pago a proveedores que se encuentran el proceso de pago, verifica el status de la solicitud para saber en que parte del proceso se encuentra."></i></h4>
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
                                        <div class="col-lg-3"> <!-- INICIO FECHA: 06-JUNIO-2025 | NUEVO CONCEPTO SOLO DEPTO CH | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                                            <div class="input-group">
                                                <span class="input-group-addon text-bold">TOTAL $</span>
                                                <input type="text"  name="myText_2" id="myText_2" class="form-control" style=" font-size: 16px; font-weight: bold;" disabled="disabled" readonly="readonly">
                                            </div>
                                        </div> <!-- FIN FECHA: 06-JUNIO-2025 | NUEVO CONCEPTO SOLO DEPTO CH | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                                        <table class="table table-striped" id="tblsolact">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th style="font-size: .9em">#</th>
                                                    <th style="font-size: .9em">EMPRESA</th>
                                                    <th style="font-size: .9em">PROYECTO</th>
                                                    <th style="font-size: .9em">SERVICIO PARTIDA</th> <!-- /** FECHA: 08-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/ -->
                                                    <th style="font-size: .9em">PROVEEDOR</th>
                                                    <th style="font-size: .9em">OPERACIÓN</th>
                                                    <th style="font-size: .9em">JUSTIFICACIÓN</th>
                                                    <th style="font-size: .9em">FEC FAC</th> <!-- /** FECHA: 08-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/ -->
                                                    <th style="font-size: .9em">CAPTURA</th>
                                                    <th style="font-size: .9em">SOLICITADO</th>
                                                    <th style="font-size: .9em">ESTATUS</th>
                                                    <th></th>
                                                    <th style="font-size: .9em">OFICINA</th>
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

<div id="modal_formulario_solicitud" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="modal-title-s"></h4> <!-- FECHA: 04-NOVIEMBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
            </div>
            <div class="modal-body">
                <!--p class="alert alert-warning">En caso de cerrar la ventana o actualizar la página, se eliminará la información que no hayas guardado.</p-->

                        <div class="row">
                            <div class="col-lg-12"> 
                                <form id="frmnewsol" method="post" action="#"> 
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" id="cb_tendra_fac" name="tendra_fac" checked="true"/>
                                        <label class="form-check-label" for="cb_tendra_fac">¿TENDRÁ FACTURA?</label>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6 form-group">
                                            <label for="proyecto"><b>PROYECTO/DEPARTAMENTO</b><span class="text-danger">*</span></label>
                                            <select name="proyecto" id="proyecto" required placeholder="---Seleccione una opción---" autocomplete="off"></select> <!-- FECHA: 31-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                                        </div>
                                         <div class="col-lg-6 form-group">
                                            <label for="oficina"><b>OFICINA/SEDE</b><span class="text-danger">*</span></label>
                                            <select name="oficina" id="oficina" required placeholder="---Seleccione una opción---" autocomplete="off"></select> <!-- FECHA: 31-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                                        </div>
                                    </div>
                                    <div class="row"> 
                                        <div class="col-lg-6 form-group">
                                            <label for="referencia_pagopr">REFERENCIA <i class="far fa-question-circle faq" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" title="Referencia" data-content="En caso de requerir una referencia favor de colocarla." data-placement="right"></i></label>
                                            <input type="text" placeholder="Numeros o letras: FACT445000" class="form-control solo_letras_numeros" maxlength="25" id="referencia_pagopr" name="referencia_pago">
                                        </div>
                                        <div class="col-lg-6 form-group">
                                            <label for="tServicio_partida"><b>TIPO SERVICIO/PARTIDA</b><span class="text-danger">*</span></label>
                                            <select name="tServicio_partida" id="listado_tServicio_partida" required placeholder="---Seleccione una opción---" autocomplete="off"></select> <!-- FECHA: 31-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 form-group">
                                            <label for="empresa">EMPRESA<span class="text-danger">*</span></label><!-- FECHA: 31-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                                            <select name="empresa" id="lista_empresa" class="lista_empresa" required placeholder="---Seleccione una opción---" autocomplete="off"></select> <!-- FECHA: 31-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="checkbox col-lg-6"> <!-- /** INICIO FECHA: 08-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/ -->
                                            <label for="noseencuentra"><input type="checkbox" id="noseencuentra" name="noseencuentra"> <b>¿NO SE ENCUENTRA EL COLABORADOR?</b></label>
                                        </div>
                                        <div class="col-lg-6 form-group"> <!-- /** FIN FECHA: 08-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/ -->
                                            <label for="proveedor">PROVEEDOR<span class="text-danger">*</span></label> <!-- FECHA: 31-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                                            <select name="idproveedor" id="idproveedor" required placeholder="---Seleccione una opción---" autocomplete="off"></select> <!-- FECHA: 31-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                                            <input id="nombreproveedor" name="nombreproveedor" class="form-control" required/>
                                        </div>
                                    </div>
                                    <hr> <!-- /** FECHA: 08-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/ -->
                                    <div class="row">
                                        <div class="col-lg-12 form-group"> 
                                            <h5>DATOS DE LA OPERACIÓN <i class="far fa-question-circle faq" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" title="Datos de la factura" data-content="En caso de no tener la factura deberás de llenar el campo de 'descripción', 'SubTtotal','IVA','Total' y 'Método de pago', así mismo, deberás de especificar en observaciones el por qué no tiene factura. Recuerda especificar si te darán factura después." data-placement="right"></i></h5>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6 form-group">
                                            <label for="metpag">OPERACIÓN<span class="text-danger">*</span></label> <!-- FECHA: 31-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                                            <select class="form-control" id="operacion" name="operacion" required>
                                                <option value="">Seleccione una opción</option>
                                                <option value="FINIQUITO">FINIQUITO</option>
                                                <option value="NOMINAS">NOMINAS</option>
                                                <option value="FINIQUITO POR PARCIALIDAD">FINIQUITO POR PARCIALIDAD</option>
                                                <option value="FINIQUITO POR RENUNCIA">FINIQUITO POR RENUNCIA</option>
                                                <option value="PRESTAMO">PRESTAMO</option>
                                                <option value="PRESTAMO POR SUSTITUCION PATRONAL">PRESTAMO POR SUSTITUCION PATRONAL</option>
                                                <option value="PRESTAMO POR ADEUDO">PRESTAMO POR ADEUDO</option>
                                                <option value="BONO">BONO</option>
                                                <?php if($this->session->userdata("inicio_sesion")['depto'] == 'CAPITAL HUMANO'): ?> <!-- FECHA: 06-JUNIO-2025 | NUEVO CONCEPTO SOLO DEPTO CH | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                                                    <option value="PAGO FUERA DE NOMINA">PAGO FUERA DE NOMINA</option>
                                                <?php endif; ?> <!-- FECHA: 06-JUNIO-2025 | NUEVO CONCEPTO SOLO DEPTO CH | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                                            </select>
                                        </div>
                                        <!--div class="col-lg-3 form-group">
                                            <label for="metpag"># PAGOS</label>
                                            <input type="text" id="numparcialidades" name="numparcialidades" class="form-control" required/>
                                        </div-->
                                        <div class="col-lg-6 form-group">
                                            <label for="metpag">FORMA DE PAGO</label>
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
                                    <div id="datosbancarios" class="row hidden">
                                        <div class="col-lg-3 form-group">
                                            <label for="metpag">TIPO DE CUENTA</label>
                                            <select class="form-control" id="tipocuenta" name="tipocta" required>
                                                <option value="">Seleccione una opción</option>
                                                <option value="1">Cuenta en Banco del Bajio</option>
                                                <option value="3">Tarjeta de débito / crédito</option>
                                                <option value="40">CLABE</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-4 form-group">
                                            <label for="metpag">BANCO</label>
                                            <select class="form-control" id="bancodestino" name="idbanco" required></select>
                                        </div>
                                        <div class="col-lg-5 form-group">
                                            <label for="metpag">NUMERO DE CUENTA</label>
                                            <input type="text" class="form-control" id="numcuenta" name="cuenta" placeholder="Numero de Cuenta" value="" required>
                                        </div>
                                    </div>
                                    <div id="inputspagos">
                                        <div class="row">
                                            <div class="col-lg-4 form-group">
                                                <label for="fecha">FECHA</label>
                                                <input type="date" class="form-control" min='2019-04-01' name="fecha" placeholder="Fecha" value="" required>
                                            </div>
                                            <div class="col-lg-4 form-group">
                                                <label for="total">TOTAL</label>
                                                <input type="text" class="form-control" name="total" placeholder="Total" value="" required>
                                            </div>
                                            <div class="col-lg-4 form-group">
                                                <label for="total">MONEDA</label>
                                                <select class="form-control" name="moneda" required>
                                                    <option value="MXN" data-value="MXN">MXN</option>
                                                    <option value="USD" data-value="USD">USD</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12 form-group">
                                                <label for="solobs">JUSTIFICACIÓN <i class="far fa-question-circle faq" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" title="Observaciones de la solicitud" data-content="En caso de ser necesario, se podrán especificar datos, comentarios u observaciones para cuentas por pagar, DG, DP o para la dirección del departamento solicitante." data-placement="right"></i></label>
                                                <textarea class="form-control" id="solobs" name="solobs" placeholder="Observaciones de la solicitud" required></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="agregarmaspagos"></div>
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

    var link_post = "";
    var idsolicitud = 0;
    var proveedores;
    var valor_input = Array( $('#tabla_autorizaciones th').length );
    var valor_input_label = Array( $('#tabla_autorizaciones th').length );
    // var depto_excep_proyecto = ['CONSTRUCCION', 'JARDINERIA', 'OOAM', 'OOAM TECNICO', 'OOAM ADMINISTRATIVO']; /** FECHA: 08-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
    var lista_proyectos_depto=[];
    const regex = /^[0-9]+$/;
    var modal_title_s; /** FECHA: 04-NOVIEMBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
    var fecha_minima = "1990-01-01";

    function reiniciar_filtros() {
        valor_input.length = 0;
        valor_input_label.length = 0;

        valor_input = Array( $('#tabla_autorizaciones th').length );
        valor_input_label = Array( $('#tabla_autorizaciones th').length );
    } 

    /* AJUSTE DE FILTRO DE RANGO DE FECHA - FECHA: 06-JUNIO-2025 | NUEVO CONCEPTO SOLO DEPTO CH | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
    $('.fechas_filtro').datepicker({
        format: 'dd/mm/yyyy',
        orientation: 'bottom auto',
        endDate: '-0d'
    });
    
    var hoy = new Date();

    $('#datepicker_from').datepicker('setDate', new Date(hoy.getFullYear(), 0, 1));
    $('#datepicker_to').val(hoy.toLocaleDateString('es-ES', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    }));
    /* FIN AJUSTE DE FILTRO DE RANGO DE FECHA - FECHA: 06-JUNIO-2025 | NUEVO CONCEPTO SOLO DEPTO CH | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
    
    //SELECCIONA TODAS LOS COMBOS EN LA TABLA
    $( "#tabla_autorizaciones tr th input[type='checkbox']" ).change( function(){
        var checked = $( this ).prop( "checked" );
        //$( table_autorizar.$('input[type="checkbox"]')).prop( "checked", $( this ).prop( "checked" ) );
        $("input", table_autorizar.rows({search:'applied'}).nodes()).each(function(){
            $(this).attr("checked", checked);
        });

    });

    var listaEmpresasArray = []; /** FECHA: 31-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
    var proveedoresArray = []; /** FECHA: 31-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
    var servicioPartidaArray = []; /** * FECHA: 31-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */

    $(document).ready( function(){

        $.getJSON( url + "Listas_select/lista_recursos_humanos" ).done( function( data ){
            
            depto = data.departamento;

            lista_proyectos_depto.push(data.lista_proyectos_departamento);
            // lista_proyectos_depto.push(data.lista_proyectos_depto); /** FECHA: 08-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/

            proveedores = data.listado_disponibles;
            
            $("#lista_empresa").append('<option value="">---Seleccione una opción---</option>'); /** FECHA: 31-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
            $.each( data.empresas, function( i, v){
                listaEmpresasArray.push({value: v.idempresa, label: v.nombre, opcionesData: {value : v.rfc}}); /** FECHA: 31-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
            });
            inputTomSelect('lista_empresa', listaEmpresasArray, {valor:'value', texto: 'label', opcDataSelect: 'opcionesData'});/** FECHA: 31-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */

            $("#bancodestino").append('<option value="">---Seleccione una opción---</option>'); /** FECHA: 31-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
            $.each( data.bancos, function( i, v){
                $("#bancodestino").append('<option value="'+v.idbanco+'">'+v.nombre+'</option>');
            });

            $("#listado_tServicio_partida").append('<option value="">---Seleccione una opción---</option>');/** FECHA: 31-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
            $.each(data.lista_servicio_partida, function (i, item) {
                servicioPartidaArray.push({value: item.idTipoServicioPartida, label: item.nombre}); /** FECHA: 31-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
            });
            inputTomSelect('listado_tServicio_partida', servicioPartidaArray, {valor:'value', texto: 'label'}); /** FECHA: 31-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */


        });
    });

    var formulario = $("#frmnewsol").submit( function(e) {
        e.preventDefault();
    }).validate({
        submitHandler: function( form ) {

            var data = new FormData( $(form)[0] );
            data.append("idsolicitud", idsolicitud);


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
                        resear_formulario();
                        $("#modal_formulario_solicitud").modal( 'toggle' );
                        table_autorizar.ajax.reload();
                    }else{
                        alert("NO SE HA PODIDO COMPLETAR LA SOLICITUD");
                    }
                },error: function( ){
                    
                }
            });
        },
        rules: {
            'fecha[]': {
                required: true
            },
            'total[]': {
                required: true
            },
            'moneda[]': {
                required: true
            },
            'solobs[]': {
                required: true
            }
        }
    });

    function resear_formulario(){
        $("#modal_formulario_solicitud input.form-control").prop("readonly", false).val("");
        $("#modal_formulario_solicitud textarea").html('');

        $("#modal_formulario_solicitud #solobs").val('');
        $("#modal_formulario_solicitud textarea").prop("readonly", false);
        $("#lista_empresa, #nombreproveedor, #xmlfile").prop('disabled', false); /** FECHA: 31-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
        $("#lista_empresa option, #nombreproveedor option, #proyecto option, #operacion option, #forma_pago option").prop("selected", false); /** FECHA: 31-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
        $("#modal_formulario_solicitud .form-control, #modal_formulario_solicitud input[type='checkbox']").prop("checked", false).prop('disabled', false);
        $("#cb_tendra_fac").prop("checked",true);
        $("#moneda").html( "" );
        $("#moneda").append( '<option value="MXN" data-value="MXN">MXN</option>' );
        var validator = $( "#frmnewsol" ).validate();
        validator.resetForm();
        $( "#frmnewsol div" ).removeClass("has-error");

        $("#fecha").attr( "min",  fecha_minima )

        $("#modal_formulario_solicitud #numparcialidades").prop("disabled", true);
        $("#modal_formulario_solicitud #nombreproveedor, #modal_formulario_solicitud #datosbancarios").addClass("hidden");
        
        $("#idproveedor").append('<option value="">---Seleccione una opción---</option>'); /** FECHA: 31-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
        $.each( proveedores, function( i, v){
            proveedoresArray.push({value: v.idproveedor, label: v.nombre+' - '+v.alias, opcionesData: {banco: v.idbanco, numcuenta: v.cuenta, tipocta: v.tipocta}}); /** FECHA: 31-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
        });
        inputTomSelect('idproveedor', proveedoresArray, {valor:'value', texto: 'label', opcDataSelect: 'opcionesData'}); /** FECHA: 31-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
        
        $("#proyecto").val('').trigger('change'); /** FECHA: 31-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */

        $('#listado_tServicio_partida')[0].tomselect.setValue(null); /** FECHA: 31-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
        $('#lista_empresa')[0].tomselect.setValue(null); /** FECHA: 31-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
        
        obtenerProyectosDepartemento({}, 'resear_formulario'); /** FECHA: 31-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
    }

    $(document).on( "click", ".abrir_nueva_solicitud", function(){
        resear_formulario();
        link_post = "Solicitante/guardar_solicitud_devolucion";
        modal_title_s = $('#modal-title-s').text('NUEVA SOLICITUD'); /** FECHA: 04-NOVIEMBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
        $("#modal_formulario_solicitud").modal( {backdrop: 'static', keyboard: false} );
    });

    //EN CASO DE ENCONTRAR AL COLABORAR SE CAMBI A UN INPUT PARA ESCRIBIR SU NOMBRE
    $(document).on( "click", "#noseencuentra", function(){
        if( $( this ).prop("checked") ){
            $("#idproveedor").next().hide();
            $("#nombreproveedor").removeClass( "hidden" );
            $("#datosbancarios .form-control").val("").prop( "disabled", false );
            $("#idproveedor").removeAttr("required");
        }else{
            $("#idproveedor").next().show();
            $("#nombreproveedor").addClass( "hidden" );
            $("#idproveedor").attr("required", true);
        }

        $("#idproveedor").val('').trigger('change');
    });

    //DEPENDIENDO DE LA OPERACION ACTIVA LA OPCION PARA INDICAR EL NUMERO DE PARCIALIDADES
    $(document).on( "change", "#operacion", function(){
        if( ( $( this ).val() ).indexOf('FINIQUITO') > -1 ){
            $("#numparcialidades").prop( "disabled", false );
        }else{
            $("#numparcialidades").prop( "disabled", true );
            $("#numparcialidades").val("");
        }

        $( "#modal_formulario_solicitud .titulo" ).remove();
        $("#agregarmaspagos").empty();
    });

    //SI LA FORMA DE PAGO ES DIFERENTE A TEA OCULTA LOS DATOS BANCARIOS PARA CAMBIARLO
    $(document).on( "change", "#forma_pago, #idproveedor", function(){

        $("#datosbancarios").addClass( "hidden" );

        if( $("#idproveedor").prop('selectedIndex') || $("#noseencuentra").prop("checked") ){
            if( ( $( "#forma_pago" ).val() ).indexOf('TEA') > -1 ){
                $("#datosbancarios").removeClass( "hidden" );

                //REVISAMOS SI ESTA CHEQUEADO LA OPCION QUE EL COLABORADOR NO EXISTE
                if( !$("#noseencuentra").prop("checked") ){
                    //DEL ARREGLO DE PROVEEDORES OBTENEMOS SU INFORMACION
                    let tomSelect = $("#idproveedor")[0].tomselect;

                    let selectedValue = tomSelect.getValue();
                    let item = tomSelect.getOption(selectedValue);

                    var dataBanco = null;
                    var dataNumCuenta = null;
                    var datatipocta = null;

                    dataBanco = item.getAttribute('data-banco');
                    dataNumCuenta = item.getAttribute('data-numcuenta');
                    datatipocta = item.getAttribute('data-tipocta');

                    $("#tipocuenta option[value='"+datatipocta+"']").prop("selected", true);
                    $("#bancodestino option[value='"+dataBanco+"']").prop("selected", true);
                    $("#numcuenta").val( dataNumCuenta ).prop( "disable", true );
                    
                    $("#datosbancarios .form-control").prop( "disabled", true );
                    /****/
                }else{
                    $("#datosbancarios .form-control").val("").prop( "disabled", false );
                }

            }
        }
    });

    //SI EL TOTAL DE PARCIALIDADES ES MAYOR A 1 ENTONCES COLOCA MAS DE 1 VEZ LOS CAMPOS PARA COLOCAR LOS DATOS BANCARIOS
    $(document).on( "change", "#numparcialidades", function(){
        numero_pagos = parseInt( $( this ).val() );
        $( "#modal_formulario_solicitud #inputspagos" ).remove( ".titulo" );
        if( numero_pagos > 1 ){

            i = 1
            while( numero_pagos != i ){
                i++
                $("#agregarmaspagos").append( "<hr/>" + $("#inputspagos").html() );
            }

            //$( "#inputspagos" ).prepend( "<h4 class='titulo'>PARCIALIDAD # 1</h4>" );

        }else{
            $("#agregarmaspagos").empty();
        }

        
        formulario.validate();
    });

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
        });

        $('#tabla_autorizaciones thead tr:eq(0) th').each( function (i) {
            if( i > 1 && i < $('#tabla_autorizaciones thead tr:eq(0) th').length - 1 ){
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

                           valor_input[i] = this.value;
                           valor_input_label[i] = title;
                    }
                } );
            }
        });

        table_autorizar = $('#tabla_autorizaciones').DataTable({
            dom: "<'row'<'col-lg-8'B><'col-lg-4'f>>" + "<'row'<'col-lg-12'tr>>" + "<'row'<'col-lg-5'i><'col-lg-7'p>>",
            buttons: [
                {
                    text: '<i class="fas fa-plus"></i> NUEVA SOLICITUD',
                    attr: {
                        class: 'btn btn-success abrir_nueva_solicitud'
                    }
                },
                {
                    text: '<i class="fas fa-print"></i> AUT. PAGO DE FINIQUITOS / PRESTAMOS',
                    action: function(){
                        var filtros = "";
                        
                        $('#tabla_autorizaciones thead tr:eq(0) input').each( function (i) {
                            if( valor_input[i] ){
                                filtros += "&" + valor_input_label[i] + "=" + valor_input[i];
                            }
                        });
                        // console.log(filtros);
                
                        if ($( table_autorizar.$('input[type="checkbox"]:checked')).length > 0) {
                            var idsolicitudes = $( table_autorizar.$('input[type="checkbox"]:checked')).map(function () { return this.value; }).get();
                            window.open( url + "Consultar/documentos_autorizacion_otros?idsolicitudes="+idsolicitudes, "_blank");
                        }else if(filtros != ""){
                            window.open( url + "Consultar/documentos_autorizacion_otros?filtros=si"+filtros, "_blank");
                        }else{
                            window.open( url + "Consultar/documentos_autorizacion_otros", "_blank");
                        }
                        
                    },
                    attr: {
                        class: 'btn btn-danger imprimir_pago_provedores',
                    }
                },
                {
                    extend: 'excelHtml5',
                    text: '<i class="fas fa-file-excel"></i>',
                    attr: {
                        class: 'btn btn-success'
                    },
                    exportOptions: {
                        format:{    
                            header:  function (data, columnIdx) {
                                return data.replace( '<input type="text" style="font-size: .9em; width: 100%;" placeholder="', '' ).split('"')[0];
                            }
                        },
                        rows : function( idx, data, node ){
                            return ( $('td input[type="checkbox"]:checked', node ).length ) ? true : false;
                        },
                        columns: [2,3,4,14,5,6,7,8,9,11,12,13] /** FECHA: 08-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                    }
                },
            ],
            "language" : lenguaje,
            "processing": false,
            "pageLength": 10,
            "bAutoWidth": false,
            "bLengthChange": false,
            "bInfo": false,
            "scrollX": true,
            "order": [[ 2, 'asc' ]], /** FECHA: 08-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
            "columns": [
                {
                },
                {
                    "className": 'details-control',
                    "orderable": false,
                    "data" : null,
                    "defaultContent": '<i class="fas animacion fa-caret-right"></i>'
                },
                {
                    "width": "9%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.idsolicitud+'</p>'
                    }
                },
                {
                    "width": "9%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.proyecto+'</p>' /** INICIO FECHA: 08-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                    }
                },
                { /** INICIO FECHA: 08-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                    "width": "10%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+(d.servicioPartida ? d.servicioPartida : '')+'</p>'
                    }
                }, /** FIN FECHA: 08-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                {
                    "width": "9%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.fecelab+'</p>'
                    }
                },
                {
                    "width": "17%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.nombre+'</p>'
                    }
                },
                {
                    "width": "7%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.abrev+'</p>'
                    }
                },
                {
                    "width": "10%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.nomdepto+'</p>'
                    }
                },
                {
                    "width": "15%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.nombre_completo+'</p>'
                    }
                },
                {
                    "width": "12%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em" class="text-center">$ '+formatMoney( d.cantidad )+" "+d.moneda+'<br/><small class="label pull-center bg-blue">'+d.metoPago+'</small></p>'
                    }
                },
                {
                    "width": "7%", 
                    "data" : function( d ){
                        return '<p style="font-size: .8em" class="text-center">$ '+formatMoney( d.cantidad )+'</p>'
                    }
                },
                {
                    "width": "12%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em" class="text-center">'+d.metoPago+'</p>'
                    }
                },
                {
                    "width": "10%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.etapa+'</p>'
                    }
                },
                {
                    "width": "10%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+(d.oficina ? d.oficina : '')+'</p>'
                    }
                },
                { 
                    "width": "20%",
                    "data" : "opciones",
                    "orderable": false
                }
            ],
            columnDefs: [
                {
                    orderable: false,
                    className: 'select-checkbox',
                    targets:   0,
                    'searchable':false,
                    'render': function (d, type, full, meta){
                        return '<input style="width: 15px; height: 15px; cursor:pointer;" type="checkbox" name="id[]" value="'+full.idsolicitud+'">';  /** INICIO FECHA: 08-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                    }
                },
                {
                    targets: [10, 12, 14], /** INICIO FECHA: 08-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                    visible: false
                },
                
            ],
            "ajax":  url + "Solicitante/tabla_autorizaciones_otros_gastos"
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

            link_post = "Solicitante/editar_solicitud_recursos_humanos";

            var ideditar =  $(this).val();
            modal_title_s = $('#modal-title-s').text('EDITAR SOLICITUD #'+ideditar); /** FECHA: 04-NOVIEMBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
            
            resear_formulario();

            $.post( url + "Solicitante/informacion_solicitud", { idsolicitud : ideditar, depto : "nominas" } ).done( function( data ){

                data = JSON.parse( data );
                if( data.resultado ){

                    idsolicitud = ideditar;
                    if(data.info_solicitud[0].tendrafac!=1)
                        $("#cb_tendra_fac").prop("checked",false);

                    $("input[name='fecha']").val( data.info_solicitud[0].fecelab );
                    $("input[name='total']").val( data.info_solicitud[0].cantidad );
                    $("#referencia_pagopr").val(data.info_solicitud[0].ref_bancaria)

                    $("#forma_pago option[data-value='"+data.info_solicitud[0].metoPago+"']").prop("selected", true);
                    $("#operacion option[value='"+data.info_solicitud[0].nomdepto+"']").prop("selected", true);
                    $("textarea[name='solobs']").val( data.info_solicitud[0].justificacion );

                    /**MANEJO DE LISTAS SELECT2**/
                    $("#proyecto option[value='"+data.info_solicitud[0].proyecto+"']").prop( "selected", true ).trigger('change');

                    $("#idproveedor")[0].tomselect.setValue(data.info_solicitud[0].idProveedor); /** FECHA: 31-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                    $('#listado_tServicio_partida')[0].tomselect.setValue(data.info_solicitud[0].idTipoServicioPartida); /** FECHA: 31-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                    $('#lista_empresa')[0].tomselect.setValue(data.info_solicitud[0].idempresa); /** FECHA: 31-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                    /****/

                    if( ( $( "#forma_pago" ).val() ).indexOf('TEA') > -1 ){
                        $("#datosbancarios").removeClass( "hidden" );

                        //REVISAMOS SI ESTA CHEQUEADO LA OPCION QUE EL COLABORADOR NO EXISTE
                        //DEL ARREGLO DE PROVEEDORES OBTENEMOS SU INFORMACION
                        $("#tipocuenta option[value='"+$( "#idproveedor option:selected" ).data( "tipocta" )+"']").prop("selected", true);
                        $("#bancodestino option[value='"+$( "#idproveedor option:selected" ).data( "banco" )+"']").prop("selected", true);
                        $("#numcuenta").val( $( "#idproveedor option:selected" ).data( "numcuenta" ) ).prop( "disable", true );
                            
                        $("#datosbancarios .form-control").prop( "disabled", true );
                        /****/
                    }

                    $("#modal_formulario_solicitud").modal( {backdrop: 'static', keyboard: false} );

                    obtenerProyectosDepartemento({ 
                        esApi: data.info_solicitud[0].Api == 1, 
                        valueProyecto: data.info_solicitud[0].idProyectos, 
                        valueOficina: data.info_solicitud[0].idOficina,
                        valueProyectoViejo: data.info_solicitud[0].proyectoViejo,
                        esProyectoNuevo: data.info_solicitud[0].esProyectoNuevo,
                        departamentoSolicitud: data.info_solicitud[0].nomdepto
                    }, 'editar_factura'); /** FECHA: 31-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */

                    $('#listado_tServicio_partida').val(data.info_solicitud[0].idTipoServicioPartida)

                }else{
                    alert("HA OCURRIDO UN ERROR")
                }
            });
        });

        $('#tabla_autorizaciones').on( "click", ".borrar_solicitud", function(){
            $.post( url + "Solicitante/borrar_solicitud", { idsolicitud : $(this).val() } ).done( function( data ){
                data = JSON.parse( data );
                if( data.resultado ){
                    table_autorizar.ajax.reload();
                }else{
                    alert("HA OCURRIDO UN ERROR")
                }
            });
        });

        $('#tabla_autorizaciones').on( "click", ".enviar_a_dg", function(){
            $.post( url + "Solicitante/enviar_a_dg", { idsolicitud : $(this).val() } ).done( function( data ){
                data = JSON.parse( data );
                if( data.resultado ){
                    table_autorizar.ajax.reload();
                    table_proceso.ajax.reload();
                }else{
                    alert("HA OCURRIDO UN ERROR")
                }
            });
        });

        $('#tabla_autorizaciones').on( "click", ".reenviar_factura", function(){
            $.post( url + "Solicitante/reenviar_factura", { idsolicitud : $(this).val() } ).done( function( data ){
                data = JSON.parse( data );
                if( data.resultado ){
                    table_autorizar.ajax.reload();
                    table_proceso.ajax.reload();
                }else{
                    alert("HA OCURRIDO UN ERROR")
                }
            });
        });

        $('#tabla_autorizaciones').on( "click", ".aprobada_da", function(){

            var tr = $(this).closest('tr');
            var row = table_autorizar.row( tr ).data();

            $.post( url + "Solicitante/aprobada_da", { idsolicitud : row.idsolicitud , departamento : (  row.nomdepto == 'NOMINAS' ? row.nomdepto : "FINIQUITO" ) } ).done( function( data ){
                data = JSON.parse( data );
                if( data.resultado ){
                    table_autorizar.ajax.reload();
                    table_proceso.ajax.reload();
                }else{
                    alert("HA OCURRIDO UN ERROR")
                }
            });
        });

        $('#tabla_autorizaciones').on( "click", ".rechazada_da", function(){
            $.post( url + "Solicitante/rechazada_da", { idsolicitud : $(this).val() } ).done( function( data ){
                data = JSON.parse( data );
                if( data.resultado ){
                    table_autorizar.ajax.reload();
                }else{
                    alert("HA OCURRIDO UN ERROR")
                }
            });
        });

        $('#tabla_autorizaciones').on( "click", ".congelar_solicitud", function(){
            $.post( url + "Solicitante/congelar_solicitud", { idsolicitud : $(this).val() } ).done( function( data ){
                data = JSON.parse( data );
                if( data.resultado ){
                    table_autorizar.ajax.reload();
                }else{
                    alert("HA OCURRIDO UN ERROR")
                }
            });
        });

        $('#tabla_autorizaciones').on( "click", ".liberar_solicitud", function(){
            $.post( url + "Solicitante/liberar_solicitud", { idsolicitud : $(this).val() } ).done( function( data ){
                data = JSON.parse( data );
                if( data.resultado ){
                    table_autorizar.ajax.reload();
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
    });

    $("#tblsolact").ready( function () {

        $('#tblsolact thead tr:eq(0) th').each( function (i) {
            if( i != 11 ){ /** FECHA: 08-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                var title = $(this).text();
                $(this).html( '<input type="text" style="font-size: .9em; width:100%" placeholder="'+title+'" />' );
        
                $( 'input', this ).on( 'keyup change', function () {
                    if ( table_proceso.column(i).search() !== this.value ) {
                        table_proceso
                            .column(i)
                            .search( this.value )
                            .draw();
                    }
                } );
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
                        },
                        columns: [0,1,2,3,12,4,5,6,7,8,9] /** FECHA: 08-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
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
            "ordering": false,
            "columns": [
                {
                    "width": "8%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.idsolicitud+'</p>'
                    }
                },
                {
                    "width": "8%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.abrev+'</p>'
                    }
                },
                {
                    "width": "12%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.proyecto+'</p>'
                    }
                },
                { /** INICIO FECHA: 08-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                    "width": "10%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+(d.servicioPartida ? d.servicioPartida : '')+'</p>'
                    }
                }, /** FIN FECHA: 08-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                {
                    "width": "19%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.nombre+'</p>'
                    }
                },
                {
                    "width": "10%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.nomdepto+'</p>'
                    }
                },
                {
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.justificacion+'</p>'
                    }
                },
                {
                    "width": "10%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.fecelab+'</p>'
                    }
                },
                {
                    "width": "15%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.nombre_completo+'</p>'
                    }
                },
                {
                    "width": "12%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">$ '+formatMoney(d.cantidad)+" "+d.moneda+'</p>'
                    }
                },
                {
                    "width": "9%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.etapa+'</p>'
                    }
                },
                {
                    "orderable": false,
                    "data": function( d ){
                        return '<div class="btn-group-vertical"><button type="button" class="btn btn-primary consultar_modal notification" value="'+d.idsolicitud+'" data-value="BAS"><i class="fas fa-eye"></i>'+(d.visto == 0 ? '<span class="badge">!</span>' : '')+'</button></div>';
                    }
                },
                {
                    "width": "10%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+(d.oficina ? d.oficina : '')+'</p>'
                    }
                },
            ],
            "columnDefs": [ {
                   "orderable": false
                },
                {
                    "targets": [ 12 ], /** FECHA: 08-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                    "visible": false,
                    "searchable": false
                }
            ],
            "ajax":  url + "Solicitante/tabla_facturas_encurso_otros_gastos"
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

    var table_pagos_sin_factura;
    var id;
    var link_complentos;

    /* AJUSTE DE FILTRO DE RANGO DE FECHA - FECHA: 06-JUNIO-2025 | NUEVO CONCEPTO SOLO DEPTO CH | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
    // Configuración inicial de datepickers
    $('#datepicker_from, #datepicker_to').datepicker({
        format: 'dd/mm/yyyy',
        autoclose: true,
        language: 'es'
    }).on('changeDate', function() {
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

    // Función de filtrado corregida
    $.fn.dataTableExt.afnFiltering.push(function(settings, data, dataIndex) {
        if(settings.nTable.id !== 'tblsolact') return true;
        
        // Obtener valores de los datepickers
        var fechaFrom = $('#datepicker_from').datepicker('getDate');
        var fechaTo = $('#datepicker_to').datepicker('getDate');
        
        // Obtener fecha de la celda (formato dd/mm/yyyy)
        var fechaCeldaStr = data[7]; // "11/06/2025"
        
        // Convertir string a objeto Date
        function parseDate(str) {
            var parts = str.split('/');
            return new Date(parts[2], parts[1]-1, parts[0]);
        }
        
        var fechaCelda = parseDate(fechaCeldaStr);
        
        // Validaciones según tu ejemplo
        if (!fechaFrom && !fechaTo) {
            return true; // Caso 1: No hay filtros
        } 
        else if (fechaFrom && !fechaTo && fechaFrom <= fechaCelda) {
            return true; // Caso 2: Solo fecha from
        }
        else if (fechaTo && !fechaFrom && fechaTo >= fechaCelda) {
            return true; // Caso 3: Solo fecha to
        }
        else if (fechaFrom && fechaTo && fechaFrom <= fechaCelda && fechaTo >= fechaCelda) {
            return true; // Caso 4: Ambas fechas
        }
        
        return false; // No cumple ninguna condición
    }); /* FIN AJUSTE DE FILTRO DE RANGO DE FECHA - FECHA: 06-JUNIO-2025 | NUEVO CONCEPTO SOLO DEPTO CH | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */

    function AutocompleteProveedor(data){
        
        $("#nombreproveedor").autocomplete({
            source: data,
            open: function(){
                $(this).autocomplete('widget').css('z-index', 1150);
                return false;
            },
            minLength: 2,
            select: function(event, ui) {
                event.preventDefault();
                $("#nombreproveedor").val(ui.item.label);

                if($("#idproveedor").val(ui.item.value) != 0){
                    $("#idproveedor").val(ui.item.value);
                }else{
                    $("#idproveedor").val("");
                }
                //alert($("#idproveedor").val(ui.item.value));
            },
            autoFocus: showLabel,
            focus: mifuncion,
            change: mifuncion
        });

        function showLabel(event, ui) {
               $("#idproveedor").val(ui.item.value);
            }

        function mifuncion(event, ui) {
            if(ui.item != null){
                uno = ui.item.label;
                dos = ui.item.value; 
            }
            else{
                $("#idproveedor").val("");
            }        
        }

    }

    function obtenerProyectosDepartemento(params, origenFun){ /** INICIO FECHA: 31-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
        input = { proyecto: "proyecto", oficina: 'oficina', servicioPartida: 'listado_tServicio_partida'  };
        $(`#${input.proyecto}`).empty();
        $(`#${input.oficina}`).empty();

        $(`#${input.proyecto}`).append('<option value="" >Seleccione una opción</option>');
        if (['CONSTRUCCION', 'JARDINERIA', 'OOAM', 'OOAM TECNICO', 'OOAM ADMINISTRATIVO'].includes(depto)) {
            $('#crecibo').parent().show();
            $('#requisicion').parent().show();
            $('#orden_compra').prop('required', true);
        }else{
            $('#orden_compra').prop('required', false)
            $('#orden_compra').parent().children('label').eq(0).children('span').eq(0).remove()
            $('#crecibo').parent().hide();
            $('#requisicion').parent().hide();
        }

        $(`#${input.oficina}`).parent().show();
        $(`#${input.servicioPartida}`).parent().show();
        

        if ( ['editar_factura'].includes(origenFun) && (params.esProyectoNuevo == 'N' || params.valueProyectoViejo != null)) {
            if(['resear_formulario'].includes(origenFun) && Object.keys(params).length <= 0){
                inputTomSelect(input.proyecto, lista_proyectos_depto[1], {valor: 'concepto', texto: 'concepto'});
                
            }else if($(`#${input.proyecto}`).hasClass('tomselected') && ['editar_factura'].includes(origenFun) && Object.keys(params).length > 0){
                $(`#${input.proyecto}`)[0].tomselect.setValue(params.valueProyectoViejo);
            }else{
                inputTomSelect(input.proyecto, lista_proyectos_depto[1], {valor: 'concepto', texto: 'concepto'});
            }
        }else{
            if(['resear_formulario'].includes(origenFun)){
                inputTomSelect(input.proyecto, lista_proyectos_depto[0], {valor: 'idProyectos', texto: 'concepto'});
                inputTomSelect(input.oficina);
            }else if($(`#${input.proyecto}`).hasClass('tomselected') && ['editar_factura'].includes(origenFun)){
                $(`#${input.proyecto}`)[0].tomselect.setValue(params.valueProyecto);
            }else{
                inputTomSelect(input.proyecto, lista_proyectos_depto[0], {valor: 'idProyectos', texto: 'concepto'});
                inputTomSelect(input.oficina);
            }
        }

        if(params.esProyectoNuevo == 'S' && params.valueProyecto){
            // $(`#${input.proyecto}`).val(params.valueProyecto)
            obtenerListadoOficinas({input: input.oficina, idOficina: params.valueOficina});
        }else if(params.esProyectoNuevo == 'N' && params.valueProyectoViejo){
            $(`#${input.proyecto}`).append('<option value="'+params.valueProyectoViejo+'">'+params.valueProyectoViejo+'</option>');
            $(`#${input.proyecto}`).val(params.valueProyecto).change();
        }

    } /** FIN FECHA: 31-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */

    function obtenerListadoOficinas(params){ /** INICIO FECHA: 31-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */       
        inputValue = params.input === 'oficina' 
            ? $('#proyecto').val() 
            : $('#proyectopr').val();

        $.ajax({
            url: url + "Listas_select/Lista_oficinas_sedes",
            data: {
                idProyecto: regex.test(inputValue) ? inputValue : 0
            },
            type: 'POST', // For jQuery < 1.9
            success: function(data){
                data = JSON.parse(data);
                $(`#${params.input}`).empty();
                $(`#${params.input}`).append('<option value="">Seleccione una opción</option>');

                if(params.idOficina == "" || !params.idOficina){
                    inputTomSelect(params.input, data.lista_oficinas_sedes, {valor: 'idOficina', texto: 'oficina'});
                }else {
                    $(`#${params.input}`)[0].tomselect.setValue(params.idOficina);
                }
                /********************************************************************************************/
            }
        })
    } /** FIN FECHA: 31-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */

    $('#proyecto').change(function(){
        obtenerListadoOficinas({input:'oficina'})
    })
    $('#proyectopr').change(function(){
        obtenerListadoOficinas({input:'oficinapr'})
    })

    function recargar() { /** FECHA: 06-JUNIO-2025 | NUEVO CONCEPTO SOLO DEPTO CH | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
        var button = document.getElementById("refreshButton");
        
        button.disabled = true;

        setTimeout(function() {
            button.disabled = false;
        }, 10000);

        table_autorizar.ajax.reload(null, false);
    } /** FIN FECHA: 06-JUNIO-2025 | NUEVO CONCEPTO SOLO DEPTO CH | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/

</script>
<?php
    require("footer.php");
?>