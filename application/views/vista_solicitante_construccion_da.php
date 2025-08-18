<?php
    require("head.php");
    require("menu_navegador.php");
?> 
<style>
    input[type="checkbox"] { /** FECHA: 23-MAYO-2025 | APROBACION MULTIPLE DA | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
        cursor: pointer;
    } /** FECHA: 23-MAYO-2025 | APROBACION MULTIPLE DA | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
</style>
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <div class="box">
                    <div class="box-header with-border">
                        <h3>SOLICITUD DE PAGOS</h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <h4>AUTORIZACIONES DE COMPRA <i class="far fa-question-circle faq" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" title="AUTORIZACIONES" data-content="La tabla muestra todas las solicitudes pendientes de autorización para poder realizar una compra o pago a proveedor." data-placement="right"></i> | TOTAL POR AUTORIZAR: <label id="cantidadSeleccionada"></label><b id="myText_1"></b></h4> <!-- FECHA: 23-MAYO-2025 | APROBACION MULTIPLE DA | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                                <div style="display: flex; align-items: center; margin-bottom: 1em;"> <!-- INICIO FECHA: 23-MAYO-2025 | APROBACION MULTIPLE DA | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                                    <div style="display: flex; align-items: center; background: #F7F7F7; border: 1px solid #CCCCCC; padding-left: 0.5em; padding-right: 0.5em; border-radius: 4px;">
                                        <input type="checkbox" id="seleccionarTodoCheckbox" style="width: 1.5em; height: 1.5em; margin-bottom: 0.2em;">
                                        <label for="seleccionarTodoCheckbox" title="SELECCIONAR TODO" style="margin-left: 0.5em; font-weight: bold; margin-top: 0.5em;">
                                            SELECCIONAR TODO <i class="far fa-question-circle faq" tabindex="0" data-container="body" data-trigger="hover" data-toggle="popover" title="SELECCIÓN MULTIPLE" data-content="Seleccionar todas las solicitudes que estén para aprobar en la página actual." data-placement="right"></i>
                                        </label>
                                    </div>
                                </div> <!-- FIN FECHA: 23-MAYO-2025 | APROBACION MULTIPLE DA | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->

                                <table class="table table-striped" id="tabla_autorizaciones">
                                    <thead class="thead-dark">
                                            <!--<th></th>-->
                                            <th style="font-size: .8em">#</th>                  <!--COLUMNA # 0 -->
                                            <!--<th style="font-size: .8em">F FISCAL</th>-->
                                            <th style="font-size: .8em">PROYECTO</th>           <!--COLUMNA # 1 -->
                                            <th style="font-size: .8em">OFICINA/SEDE</th>       <!--COLUMNA # 2 -->
                                            <th style="font-size: .8em">SERVICIO/PARTIDA</th>   <!--COLUMNA # 3 --><!-- /** FECHA: 08-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/ -->
                                            <th style="font-size: .8em">FECHA</th>              <!--COLUMNA # 4 -->
                                            <th style="font-size: .8em">PROVEEDOR</th>          <!--COLUMNA # 5 -->
                                            <th style="font-size: .8em">CAPTURISTA</th>         <!--COLUMNA # 6 -->
                                            <th style="font-size: .8em">CANTIDAD</th>           <!--COLUMNA # 7 -->
                                            <th style="font-size: .8em">MONEDA</th>             <!--COLUMNA # 8 -->
                                            <th style="font-size: .8em">T GASTO</th>            <!--COLUMNA # 9 -->
                                            <!--<th style="font-size: .8em">JUSTIFICACIÓN</th>-->
                                            <th style="font-size: .8em"></th>                   <!--COLUMNA # 10 -->
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <hr/>
                        <h4>FACTURAS ACTIVAS <i class="far fa-question-circle faq" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" data-placement="right" title="Solicitudes Activas" data-content="Estas son todas las solicitudes de pago a proveedores que se encuentran el proceso de pago, verifica el status de la solicitud para saber en que parte del proceso se encuentra."></i></h4>      
                        <div class="row">
                            <div class="col-lg-12">
                                <form id="formulario_solicitante_construccion" autocomplete="off" action="<?= site_url("Reportes/solicitante_solPago_solActivas") ?>" method="post">
                                    <div class="row">
                                        <div class="col-lg-2">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fas fa-calendar-alt"></i> <b>INICIO</b></span>
                                                    <input class="form-control fechas_filtro from" type="text" name="fechaInicial" id="fecInicial"/>
                                            </div>
                                        </div>
                                        <div class="col-lg-2">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fas fa-calendar-alt"></i> <b>FIN</b></span>
                                                    <input class="form-control fechas_filtro to" type="text" name="fechaFinal" id="fecFinal" />
                                            </div>
                                        </div>
                                        <div class="col-lg-3"> <!-- INICIO FECHA: 23-MAYO-2025 | APROBACION MULTIPLE DA | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                                            <div class="input-group">
                                                <span class="input-group-addon text-bold">TOTAL $</span>
                                                <input type="text"  name="myText_2" id="myText_2" class="form-control" style=" font-size: 16px; font-weight: bold;" disabled="disabled" readonly="readonly">
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="input-group">
                                                <span class="input-group-addon text-bold">INTERCAMBIO $</span>
                                                <input type="text"  name="total_descuento" id="total_descuento" class="form-control" style=" font-size: 16px; font-weight: bold;" disabled="disabled" readonly="readonly">
                                            </div>
                                        </div> <!-- FIN FECHA: 23-MAYO-2025 | APROBACION MULTIPLE DA | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                                        <div id="elementos_hidden"></div>
                                    </div>
                                </form>
                                <br>
                                <table class="table table-striped" id="tblsolact">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th style="font-size: .8em">#</th>                      <!--COLUMNA # 0 --><!-- /** INICIO FECHA: 08-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/ -->
                                            <th style="font-size: .8em">EMPRESA</th>                <!--COLUMNA # 1 -->
                                            <th style="font-size: .8em">PROYECTO</th>               <!--COLUMNA # 2 -->
                                            <th style="font-size: .8em">OFICINA/SEDE</th>           <!--COLUMNA # 3 -->
                                            <th style="font-size: .8em">SERVICIO/PARTIDA</th>       <!--COLUMNA # 4 --><!-- /** FECHA: 08-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/ -->
                                            <th style="font-size: .8em">FOLIO</th>                  <!--COLUMNA # 5 -->
                                            <th style="font-size: .8em">PROVEEDOR</th>              <!--COLUMNA # 6 -->
                                            <!-- <th style="font-size: .8em">CONTRATO</th> -->
                                            <th style="font-size: .8em">FEC FAC</th>                <!--COLUMNA # 7 -->
                                            <th style="font-size: .8em">CAPTURA</th>                <!--COLUMNA # 8 -->
                                            <th style="font-size: .8em">CANTIDAD</th>               <!--COLUMNA # 9 -->
                                            <th style="font-size: .8em">INTERCAMBIO</th>            <!--COLUMNA # 10 -->
                                            <th style="font-size: .8em">PAGADO</th>                 <!--COLUMNA # 11 -->
                                            <th style="font-size: .8em">RESTANTE</th>               <!--COLUMNA # 12 -->
                                            <th style="font-size: .8em">ESTATUS</th>                <!--COLUMNA # 13 -->
                                            <th style="font-size: .8em">DESCRIPCION</th>            <!--COLUMNA # 14 -->
                                            <th style="font-size: .8em">ORDEN DE COMPRA</th>        <!--COLUMNA # 15 -->
                                            <th style="font-size: .8em">CONTRATO</th>               <!--COLUMNA # 16 --><!-- /** FIN FECHA: 08-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/ -->
                                            <th></th>                                               <!--COLUMNA # 17 -->
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
                <!--p class="alert alert-warning">En caso de cerrar la ventana o actualizar la página, se eliminará la información que no hayas guardado.</p-->
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="tab" href="#generar_solicitud" role="tab" aria-controls="generar_solicitud" aria-selected="false">GENERAR SOLICITUD</a></li>
                        <li><a data-toggle="tab" href="#altprov" role="tab" aria-controls="altprov" aria-selected="false">ALTA DE PROVEEDOR</a></li>
                    </ul>
                </div>
                <div class="tab-content">
                    <div class="active tab-pane" id="generar_solicitud">
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
                                        <div class="col-lg-8 row_tipo"> 
                                            <div class="form-group">
                                                <label><b>TIPO DE PAGO</b><label>
                                                <label class="radio-inline"><input type="radio" value="0" name="servicio1" class="default"><b>PROVEEDOR</b></label>
                                                <label class="radio-inline"><input type="radio" value="1" name="servicio1" ><b>SERVICIO</b></label>
                                                <label class="radio-inline"><input type="radio" value="9" name="servicio1" class="caja_chica_label"><b>CAJA CHICA</b></label>
                                                <input type="hidden" name="servicio" >
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="row">                                    
                                        <div class="col-lg-6"> 
                                            <div class="form-group">
                                                <label><b>OTRAS OPCIONES</b><label>
                                                <!--<label class="form-check-label" for="fecrecu" id="caja_chica_label"><input type="checkbox" value="1" name="caja_chica" id="caja_chica"> CAJA CHICA</label>-->
                                                <label class="form-check-label checkbox-inline" for="fecrecu"><input type="checkbox" value="1" name="prioridad"><b> URGENTE</b></label>
                                                <label class="form-check-label checkbox-inline" for="fecrecu"><input type="checkbox" value="1" name="tentra_factura" id="tentra_factura"><b> CON FACTURA</b></label>
                                                <input type="hidden" name="tentra_factura" >
                                            </div>
                                        </div>
                                    </div>   
                                    <div class="row">
                                        <div class="col-lg-6 form-group">
                                            <label for="proyecto"><b>PROYECTO/DEPARTAMENTO</b><span class="text-danger">*</span></label>
                                            <select class="listado_proyectos form-control select2" name="proyecto" style="width: 100%;" id="proyecto" required></select>
                                        </div>
                                        <div class="col-lg-6 form-group">
                                            <label for="proyecto"><b>OFICINA/SEDE</b><span class="text-danger">*</span></label>
                                            <select class="listado_oficinas form-control select2" name="oficina" style="width: 100%;" id="oficina" required></select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6 form-group">
                                            <label for="Etapa">ETAPA</label>
                                            <select name="etapa" class="form-control select2 select2-hidden-accessible lista_etapas" style="width: 100%;" id="Etapa" required>
                                            <input type="hidden" name="idetapa" class="form-control" id="idetapa" >
                                        </div>
                                        <div class="col-lg-6 form-group">
                                            <label for="condominio">CONDOMINIO</label>
                                            <select name="condominio" class="form-control select2 select2-hidden-accessible lista_condominios" style="width: 100%;" id="condominio" required>
                                            <input type="hidden" name="idcondominio" class="form-control" id="idcondominio" >
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 form-group">
                                            <label for="tServicio_partida"><b>TIPO SERVICIO/PARTIDA</b><span class="text-danger">*</span></label>
                                            <select class="listado_tServicio_partida form-control select2" name="tServicio_partida" style="width: 100%;" id="listado_tServicio_partida" required></select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6 form-group">
                                            <label for="proyecto">HOMOCLAVE</label>
                                            <select name="homoclave" id="homoclave" class="form-control" required></select>
                                        </div>
                                        <div class="col-lg-6 form-group">
                                            <label for="empresa">EMPRESA</label>
                                            <select name="empresa" id="empresa" class="form-control lista_empresa" required></select>
                                            <input type="hidden" class="form-control" name="empresa" disabled>
                                        </div>
                                        
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 form-group">
                                            <label for="proveedor">PROVEEDOR</label>
                                            <!--<select id="proveedor" name="proveedor" class="form-control lista_provedores_libres"  required></select>
                                            <input type="hidden" class="form-control" name="proveedor" disabled>-->
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
                                            <label for="fecha">FECHA</label>
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
                                            <label for="total">TOTAL</label>
                                            <input type="text" class="form-control" id="total" name="total" placeholder="Total" value="" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4 form-group">
                                            <label for="total">MONEDA</label>
                                            <select class="form-control" id="moneda" name="moneda" required>
                                                <option value="MXN" data-value="MXN">MXN</option>
                                                <option value="USD" data-value="USD">CAD</option>
                                                <option value="CAD" data-value="CAD">CAD</option>
                                                <option value="UDIS" data-value="UDIS">UDIS</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-4 form-group">
                                            <label for="metpag">MÉTODO DE PAGO</label>
                                            <input type="text" class="form-control" placeholder="Método de Pago" id="metpag" name="metpag" value="" readonly>
                                        </div>
                                        <div class="col-lg-4 form-group">
                                            <label for="metpag">FORMA DE PAGO</label>
                                            <select class="form-control" id="forma_pago" name="forma_pago" required>
                                                <option value="">Seleccione una opción</option>
                                                <option value="ECHQ" data-value="ECHQ">Cheque</option>
                                                <option value="TEA" data-value="TEA">Transferencia Electrónica</option>
                                                <option value="MAN" data-value="MAN">Manual</option>
                                                <option value="DOMIC" data-value="DOMIC">Domiciliario</option>
                                                <option value="EFEC" data-value="EFEC">Efectivo</option>
                                                <option value="FACT BAJIO" data-value="EFEC">Facturaje Bajio</option>
                                                <option value="FACT BANREGIO" data-value="EFEC">Facturaje Banregio</option>
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
                                            <label for="solobs">JUSTIFICACIÓN <i class="far fa-question-circle faq" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" title="Observaciones de la solicitud" data-content="En caso de ser necesario, se podrán especificar datos, comentarios u observaciones para cuentas por pagar, DG, DP o para la dirección del departamento solicitante." data-placement="right"></i></label>
                                            <textarea class="form-control" id="solobs" name="solobs" placeholder="Observaciones de la solicitud" required></textarea>
                                        </div>
                                    </div>
                                    <div class="row" id="row_cc">
                                        <div class="col-lg-12"> 
                                            <div class="form-group">
                                            <label for="metpag">RESPONSABLE DE CAJA CHICA</label>
                                                <select class="form-control" id="responsable_cc" name="responsable_cc" disabled required></select>
                                            </div>
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
                    <div class="tab-pane" id="altprov">
                        <form id="invitacion_proveedores" method="post" action="#">
                            <h5>ALTA DE PROVEEDOR</h5>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <input type="email" name="correo_invitacion" placeholder="Correo" class="form-control" required>
                                        <button type="submit" id="btnSnd" class="btn btn-block btn-block">Enviar</button>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <p class="text-justify">Ingresa el correo del proveedor y se le enviará un link para que se registre en nuestro sistema. Si deseas dar de alta al proveedor de manera manual solicita al departamento de cuentas por pagar su alta  <a href="mailto:cpp@ciudadmaderas.com">AQUÍ</a></p>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="modal_solicitud_programado" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="exampleModalLabel">NUEVO PAGO PROGRAMADO</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <form  id="frmnewsolp" method="post" action="#">
                            <div class="row">
                                <div class="col-lg-6 form-group">
                                    <label for="proyecto">PROYECTO/DEPARTAMENTO<span class="text-danger">*</span></label>
                                    <select class="listado_proyectos form-control" name="proyecto" id="proyectopr" required></select>
                                </div>
                                <div class="col-lg-6 form-group">
                                    <label for="proyecto"><b>OFICINA/SEDE</b><span class="text-danger">*</span></label>
                                    <select class="listado_oficinas form-control select2" name="oficina" style="width: 100%;" id="oficinapr" required></select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 form-group">
                                    <label for="empresa">EMPRESA<span class="text-danger">*</span></label>
                                    <select name="empresa" id="empresapr" class="form-control lista_empresa" required></select>
                                    <input type="hidden" class="form-control" name="empresa" disabled>
                                </div>
                                <div class="col-lg-4 form-group">
                                    <label for="empresa">REFERENCIA <i class="far fa-question-circle faq" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" title="Referencia" data-content="En caso de requerir una referencia favor de colocarla." data-placement="right"></i></label>
                                    <input type="text" placeholder="Numeros o letras: FACT445000" class="form-control solo_letras_numeros" maxlength="25" id="referencia_pagopr" name="referencia_pago">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-5 form-group">
                                    <label for="proveedor_programado">PROVEEDOR<span class="text-danger">*</span> <i class="far fa-question-circle faq text-danger" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" title="Proveedores" data-content="A continuación se muestra solo los proveedores que no requieren factura o que se puede agregar posterior al pago." data-placement="right"></i></label>
                                    <select name="proveedor" class="form-control select2 select2-hidden-accessible lista_provedores_libres" style="width: 100%;" id="proveedor_programado" required></select>
                                    <input type="hidden" name="idproveedor" class="form-control" id="idproveedor" >
                                </div>
                                <div class="col-lg-3 form-group">
                                    <br/>
                                    <label for="proveedor">INTERÉS</label>
                                    <input type="checkbox" name="interes_dinamico" id="interes_dinamico" style="width:20px;height:20px;" >
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 form-group">
                                    <h5>DATOS DEL PAGO <i class="far fa-question-circle faq" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" title="Datos de la factura" data-content="En caso de no tener la factura deberás de llenar el campo de 'descripción', 'SubTtotal','IVA','Total' y 'Método de pago', así mismo, deberás de especificar en observaciones el por qué no tiene factura. Recuerda especificar si te darán factura después." data-placement="right"></i></h5>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 form-group">
                                    <label for="fecha">FECHA INICIO<span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" min='2019-04-01' id="fechapr" name="fecha" placeholder="Fecha" value="" required>
                                </div>
                                <div class="col-lg-4 form-group">
                                    <label for="folio">FECHA FIN</label>
                                    <input type="date" class="form-control" placeholder="Fecha final" id="fecha_finalpr" name="fecha_final" value="">
                                </div>
                                <div class="col-lg-4 form-group">
                                    <label for="metpag">FORMA DE PAGO<span class="text-danger">*</span></label>
                                    <select class="form-control" id="forma_pagopr" name="forma_pago" required>
                                        <option value="">Seleccione una opción</option>
                                        <option value="TEA" data-value="TEA">Transferencia electrónica</option>
                                        <option value="DOMIC" data-value="DOMIC">Domiciliario</option>
                                        <option value="ECHQ">Cheque</option>
                                        <option value="EFEC">Efectivo</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 form-group">
                                    <label for="total">TOTAL<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="totalpr" name="total" placeholder="Total" value="" required>
                                </div>
                                <div class="col-lg-4 form-group">
                                    <label for="total">MONEDA</label>
                                    <select class="form-control" id="monedapr" name="moneda" required>
                                        <option value="MXN" data-value="MXN">MXN</option>
                                    </select>
                                </div>
                                <div class="col-lg-4 form-group">
                                    <label for="total">PERIODO DE PAGOS<span class="text-danger">*</span></label>
                                    <select class="form-control" id="metodo_pago" name="metodo_pago" required>
                                        <option value="">Seleccione un opción</option>
                                        <option value="7">SEMANAL</option>
                                        <option value="1">MENSUALMENTE</option>
                                        <option value="2">BIMESTRAL</option>
                                        <option value="3">TRIMESTRAL</option>
                                        <option value="4">CUATRIMESTRAL</option>
                                        <option value="6">SEMESTRAL</option>
                                        <option value="8">QUINCENAL</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 form-group">
                                    <label for="solobs">JUSTIFICACIÓN<span class="text-danger">*</span> <i class="far fa-question-circle faq" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" title="Observaciones de la solicitud" data-content="En caso de ser necesario, se podrán especificar datos, comentarios u observaciones para cuentas por pagar, DG, DP o para la dirección del departamento solicitante." data-placement="right"></i></label>
                                    <textarea class="form-control" id="solobspr" name="solobs" placeholder="Observaciones de la solicitud" required></textarea>
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
                            <label>DOCUMENTO XML</label>
                            <div class="input-group">
                                <input type="file" class="form-control" id="complemento" accept="text/xml" required>
                                <div class="input-group-btn">
                                    <button type="submit" class="btn btn-danger cargar_complemento"><i class="fas fa-upload"></i></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade modal-alertas" id="askReadyModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static"> <!-- INICIO FECHA: 23-MAYO-2025 | APROBACION MULTIPLE DA | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<label class="modal-title" id="exampleModalLabel">APROBAR MULTIPLES REGISTROS</label>
			</div>
			<div class="modal-body text-center">
				<h4><b>¿ESTÁ SEGURO DE QUE DESEA APROBAR LOS REGISTROS?</b></h4>
				<div id="elementsToApprove" style="margin-top: 2em;"></div>
			</div>
			<div class="modal-footer">
				<div class="row">
					<div class="col-lg-6">
						<button type="button" class="btn btn-danger btn-block" data-dismiss="modal">CERRAR</button>
					</div>
					<div class="col-lg-6">
						<button type="button" class="btn btn-success btn-block" onclick="aceptarEnvio()">CONFIRMAR</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div> <!-- FIN FECHA: 23-MAYO-2025 | APROBACION MULTIPLE DA | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->


<script>
    let titulos_intxt = [];
    var documento_xml = null;
    var link_post = "";
    var depto = "";
    var idsolicitud = 0;
    var _data1 = [];
    var _data2 = [];
    var _data3 = [];
    var depto_excep  = ['ADMINISTRACION','FUNDACION','SUB DIRECCION'];
    var depto_excep_proyecto = ['CONSTRUCCION', 'JARDINERIA', 'OOAM', 'OOAM TECNICO', 'OOAM ADMINISTRATIVO'];
    var lista_proyectos_depto=[];
    var valor_input = Array( $('#tblsolact th').length );
    const regex = /^[0-9]+$/;
    var valor_input = Array( $('#tblsolact th').length );
    var titulo_encabezados = [];
    var num_columnas = [];
    var fechaInicial;
    var fechaFinal;
    var depto_usuario = '<?php echo $this->session->userdata("inicio_sesion")['depto']?>';

    //Initialize Select2 Elements
    $('.select2').select2();

    $('.fechas_filtro').datepicker({
          format: 'dd/mm/yyyy',
          orientation: 'bottom auto',
          endDate: '-0d'
    });

    $('#fecInicial').on('keyup change', function(){
        var str = $(this).val();
        if(str.length==2 || str.length==5){
            $('#fecInicial').val(str+'/');
        }
    }); $('#fecFinal').on('keyup change', function(){
        var str = $(this).val();
        if(str.length==2 || str.length==5){
            $('#fecFinal').val(str+'/');
        }
    }); 


    $('#tentra_factura').change(function() {
        if(this.checked) {
            $("input[type='hidden'][name='tentra_factura']").val("1").prop('disabled', false);
        }else{
            $("input[type='hidden'][name='tentra_factura']").val("").prop('disabled', false);
        }        
    });

    $('input[type=radio][name=servicio1]').change(function() {
        if(!depto_excep.includes(depto)){    
            $("input[name='tentra_factura']").prop("checked", true).prop('disabled', true);
            $("input[type='hidden'][name='tentra_factura']").val("1").prop('disabled', false);
        }
        switch(this.value){
            case '0': 
                $("input[name='tentra_factura']").prop("checked", true).prop('disabled', ( !depto_excep.includes(depto) || documento_xml != null) );
                $("input[type='hidden'][name='tentra_factura']").val("1").prop('disabled', false);
            break;
            default:
                $("input[name='tentra_factura']").prop("checked", documento_xml != null).prop('disabled', ( !depto_excep.includes(depto) || documento_xml != null) );
                $("input[type='hidden'][name='tentra_factura']").val( documento_xml != null ? "1" : "").prop('disabled', false);
            break;
        }
        //$("#forma_pago option[data-value='ECHQ']").prop("selected", true);
        $("#responsable_cc").prop( "disabled", (this.value == '9' ? false : true ) ).val(''); /** FECHA: 08-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/

        $("input[type=hidden][name='servicio']").val(this.value).prop('disabled', false);

        if ($('#proyecto').data('select2')) {
            $('#proyecto').select2('destroy');
        }      

        $('#oficina').val('').empty();
        $("#oficina").html("<option value=''>Seleccione una opción</option>")

        obtenerProyectosDepartemento({}); /** FECHA: 08-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/

        $("#proyecto").select2(); 

        AutocompleteProveedor(_data3.length == 0 ? (this.value == '9' ? _data2 : _data1) : _data3);
        
    });

    var today = new Date();
    var yesterday = new Date( today );
    yesterday.setDate( today.getDate() - 40 );
    var fecha_minima = yesterday.toISOString().substr(0, 10);
    
    $(document).ready( function(){

        /**@author Dante Aldair <programador.analista18@ciudadmaderas.com> 
         * Configuracion de datapicker para una fecha predeterminada al inicio de la vista
        */

        const fechaActual = new Date().toLocaleDateString('es-ES', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric'
        });

        $(".input-group-addon").css('padding', '6px 8px');
        // Configura el datepicker inicial
        $('#fecInicial').datepicker({
            dateFormat: 'dd/mm/yyyy', // Formato de fecha deseado
            defaultDate: '01/01/'+(new Date().getFullYear() - 1), // Establece la fecha predeterminada
            onSelect: function(dateText) {
                $(this).val(dateText); // Establece el valor del campo de entrada con la fecha seleccionada
            }
        });
        // Asigna la fecha predeterminada al campo de entrada
        $('#fecInicial').datepicker('setDate', '01/01/' + (new Date().getFullYear() - 1));
        fechaInicial = $('#fecInicial').val();
        // Configura el datepicker inicial
        $('#fecFinal').datepicker({
            dateFormat: 'dd/mm/yyyy', // Formato de fecha deseado
            defaultDate: fechaActual, // Establece la fecha predeterminada
            onSelect: function(dateText) {
                $(this).val(dateText); // Establece el valor del campo de entrada con la fecha seleccionada
            }
        });
        // Asigna la fecha predeterminada al campo de entrada
        $('#fecFinal').datepicker('setDate', fechaActual);
        fechaFinal = $('#fecFinal').val();
        /*Final de los cambios por parte de Dante Aldair para configuracion de DataPickers*/
        
        $.getJSON( url + "Listas_select/lista_proveedores_libres" ).done( function( data ){
            if( data.provedores_total != data.provedores_disponibles ){
                var listado = '<ul>';
                
                $.each( data.listado_bloqueados , function(i, v){
                    listado += '<li>'+v.nombre+'</li>';
                });
                
                listado += '</ul>';

                $.notify.addStyle('vacantes', {
                html: 
                    "<div><div class='clearfix alert alert-danger'>" +
                        "<div class='title' data-notify-html='title'/>" +listado+
                    "</div></div>"
                });

                $.notify({
                    title: "<h5><strong><i class='fas fa-exclamation text-danger'></i> ATENCIÓN</strong></h5><hr/> Hay ("+( data.provedores_total - data.provedores_disponibles )+") proveedore(s) bloqueados. Es necesario que cargue las facturas correspondientes."
                }, {
                    style: 'vacantes',
                    autoHide: true,
                    timer: 1000000,
                    clickToHide: true
                });
            }

            $("#proyecto").append('<option value="">Seleccione una opción</option>');
            $("#oficina").append('<option value="">Seleccione una opción</option>');
            $("#oficinapr").append('<option value="">Seleccione una opción</option>');

            lista_proyectos_depto.push(data.lista_proyectos_departamento);
            // lista_proyectos_depto.push(data.lista_proyectos_depto); /** FECHA: 08-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/

            $(".lista_condominios").append('<option value="N/A">N/A</option>');
            $.each( data.listado_condominios, function( i, v){
                $(".lista_condominios").append('<option value="'+v.nombre+'">'+v.nombre+'</option>');
            });

            $(".lista_etapas").append('<option value="N/A">N/A</option>');
            $.each( data.listado_etapas, function( i, v){
                $(".lista_etapas").append('<option value="'+v.nombre+'">'+v.nombre+'</option>');
            });

            $("#homoclave").html('<option value="N/A">N/A</option>');
            $.each( data.lista_proyectos_homoclaves, function( i, v){
                $("#homoclave").append('<option value="'+v.concepto+'">'+v.concepto+'</option>');
            });

            $("#responsable_cc").html('');
            if( data.listado_responsable.length > 1 ){
                $("#responsable_cc").append('<option value="">Seleccione una opción</option>');
            }
            
            $.each( data.listado_responsable, function( i, v){
                $("#responsable_cc").append('<option value="'+v.idusuario+'">'+v.nombres+" "+v.apellidos+'</option>');
            });
            depto = data.departamento
            switch( data.departamento ){
                case 'CONSTRUCCION':
                    limite_cajachica = 999999;
                    break;
                default:
                    limite_cajachica = 2000;
                    break;
            }

        });

    });

    $("#frmnewsolp").submit( function(e) {
        e.preventDefault();
    }).validate({
        submitHandler: function( form ) {
            // if($("#proveedor").val() != ""){

                    var data = new FormData( $(form)[0] );
                    data.append("idsolicitud", idsolicitud);
                    data.append("xmlfile", documento_xml);

                    if( !$("#proveedor_programado").prop("disabled") ){
                        data.append("idproveedor", $("#proveedor_programado").val());
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
                                $("#modal_solicitud_programado").modal( 'toggle' );
                                table_autorizar.ajax.reload(null, false);
                            }else{
                                alert("NO SE HA PODIDO COMPLETAR LA SOLICITUD");
                            }
                        },error: function( ){
                            alert("Algo salio mal, recargue su página.");
                        }
                    });

            // }else{
            //     alert("HA INGRESADO UN PROVEEDOR NO REGISTRADO, PARA PODER HACER ALGÚN MOVIMIENTO NECESITA REGISTRARLO PREVIAMENTE.");
            // }
        }
    });

    function obtenerListadoOficinas(params){
        inputValue = params.input === 'oficina' ? $('#proyecto').val() : $('#proyectopr').val()
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
                if(data.lista_oficinas_sedes)
                    data.lista_oficinas_sedes.forEach(function(v){
                        $(`#${params.input}`).append('<option value="'+v.idOficina+'">'+v.oficina+'</option>');
                    })
                if(params.idOficina)
                    $(`#${params.input}`).val(params.idOficina)
            }
        })
    }

    $('#proyecto').change(function(){
        obtenerListadoOficinas({input:'oficina'})
    });
    $('#proyectopr').change(function(){
        obtenerListadoOficinas({input:'oficinapr'})
    });

    // FUNCION PARA TRAER LISTADO DE TIPO SERVICIO / PARTIDA
    $(document).ready( function(){
        $(".listado_tServicio_partida").append('<option value="">Seleccione una opción</option>');
        $.getJSON( url + "Listas_select/Lista_TipoServicioPartidas" ).done( function( data ){
            $.each(data, function (i, item) {
                $('.listado_tServicio_partida').append('<option value="'+item.idTipoServicioPartida+'" >'+item.nombre+'</option>');
            });
        })
    });

    function resear_formulario_programado(){
        $("#modal_solicitud_programado input.form-control").prop("readonly", false).val("");

        $("#modal_solicitud_programado #solobspr, #modal_solicitud_programado #orden_compra").html('').val('');
        $("#modal_solicitud_programado textarea").html('').prop("readonly", false);
        $("#empresapr, #proveedor_programado, #xmlfile").prop('disabled', false);
        $("#empresapr option, #proveedor_programado option, #forma_pagopr option, #proyectopr option, #metodo_pago option, #homoclave option").prop("selected", false);
        $("#modal_formulario_solicitud .form-control, #modal_formulario_solicitud input[type='checkbox']").prop("checked", false).prop('disabled', false);
        $("input[type='hidden']").val("").prop('disabled', true);
        $("#interes_dinamico").prop("checked",false);
        $(".programar_fecha, #responsable_cc").prop('disabled', true)
        $("#modal_solicitud_programado .departamentoSolicitante").val('');

        $("#monedapr").html( "" );
        $("#monedapr").append( '<option value="MXN" data-value="MXN">MXN</option>' );
        $("#monedapr").append( '<option value="USD" data-value="USD">USD</option>' );
        $("#monedapr").append( '<option value="CAD" data-value="CAD">CAD</option>' );
        $("#monedapr").append( '<option value="EUR" data-value="EUR">EUR</option>' );
        $("#monedapr").append( '<option value="UDIS" data-value="UDIS">UDIS</option>' );
        
        $("#modal_solicitud_programado #descr").prop("readonly", true).val('');
        $("#modal_solicitud_programado #metpag").prop("readonly", true).val('');
        $("#modal_solicitud_programado #obse").prop("readonly", true).val('');
        $('.default').prop('checked', true);
        $("input[type=radio][name=servicio1][value=0]").prop('checked', true );
        $("input[type=radio][name=servicio1]").prop('disabled', false );

        obtenerProyectosDepartemento({programado: true});

        AutocompleteProveedor(_data1);
        $("#idproveedor").val('');

        var validator = $( "#frmnewsolp" ).validate();
        validator.resetForm();
        $( "#frmnewsolp div" ).removeClass("has-error");

        $("#fechapr").attr( "min",  fecha_minima )

        documento_xml = null;
        _data3 = [];
      
    }

    function recargar_provedores(){
        $(".lista_provedores_libres").append('<option value="">Seleccione una opción</option>');
        $.getJSON( url + "Listas_select/lista_proveedores_libres" ).done( function( data ){
            $(".lista_provedores_libres").html('');
            $(".lista_provedores_libres").append('<option value="">Seleccione una opción</option>');
            
            _data1 = [];
            _data2 = [];
            /**
             * INICIO
             * FECHA : 27-MAYO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com
             * Se agrego la cuenta bancaria despues del nombre del bano de cada proveedor 
             */
            $.each( data.listado_disponibles, function( i, v){
                if(!(v.nombre.includes('GASTO NO DEDUCIBLE')))
                {
                    _data1.push({value : v.idproveedor, label : v.nombre + " - " + v.nom_banco + (v.cuenta ? " - " + v.cuenta : ''), rfc: v.rfc});
                    _data2.push({value : v.idproveedor, label : v.nombre + " - " + v.nom_banco + (v.cuenta ? " - " + v.cuenta : ''), rfc: v.rfc});
                }
                else
                {
                    _data2.push({value : v.idproveedor, label : v.nombre + " - " + v.nom_banco + (v.cuenta ? " - " + v.cuenta : ''), rfc: v.rfc});
                }
            });
             /**
             * FIN
             * FECHA : 27-MAYO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com 
             */
            
            AutocompleteProveedor(_data1);
            $(".lista_empresa").html('');
            $(".lista_empresa").append('<option value="">Seleccione una opción</option>');
            $.each( data.empresas, function( i, v){
                $(".lista_empresa").append('<option value="'+v.idempresa+'" data-value="'+v.rfc+'">'+v.nombre+'</option>');
            });
            
            depto = data.departamento;
            $("input[name='tentra_factura']").prop("checked", true);
            if( !depto_excep.includes(depto) ){
                $("input[name='tentra_factura']").prop('disabled', true);
                $("input[type='hidden'][name='tentra_factura']").val("1").prop('disabled', false);
            }
            
        });
    }

    $("#frmnewsol").submit( function(e) {
        e.preventDefault();
    }).validate({
        submitHandler: function( form ) {
            if($("#proveedor").val() != ""){

                var response = true;
                if( !$('#tentra_factura').is(':checked') && depto_excep.includes(depto)){
                    //$("#confirmar_factura").modal( {backdrop: 'static', keyboard: false});
                    response = confirm("LA SOLICITUD NO TENDRÁ FACTURA, ¿ESTÁ DE ACUERDO?");
                }

                if(response){
                    var data = new FormData( $(form)[0] );
                    data.append("idsolicitud", idsolicitud);
                    data.append("xmlfile", documento_xml);
                    data.append("idproveedor", $("#proveedor").val());
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
                                resear_formulario();
                                $("#modal_formulario_solicitud").modal( 'toggle' );
                                table_autorizar.ajax.reload();
                            }else{
                                alert("NO SE HA PODIDO COMPLETAR LA SOLICITUD");
                            }
                        },error: function( ){
                            
                        }
                    });
                }
            }else{
                alert("HA INGRESADO UN PROVEEDOR NO REGISTRADO, PARA PODER HACER ALGÚN MOVIMIENTO NECESITA REGISTRARLO PREVIAMENTE.");
            }
        }
    });

    $(document).on( "click", ".abrir_nueva_solicitud_programada", function(){
            resear_formulario_programado();
            recargar_provedores();
            link_post = "Solicitante/guardar_solicitud";
            $("#modal_solicitud_programado").modal( {backdrop: 'static', keyboard: false} );
    });

    $("#invitacion_proveedores").submit( function(e) {
        e.preventDefault();
    }).validate({
        submitHandler: function( form ) {

            var data = new FormData( $(form)[0] );
            $.ajax({
                url: url + "Solicitante/invitacion_proveedor",
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                method: 'POST',
                type: 'POST', // For jQuery < 1.9
                success: function(data){
                    if( data[0] ){
                        alert("SE HA ENVIADO CON ÉXITO LA INVITACIÓN AL PROVEEDOR");
                        $( "#invitacion_proveedores input" ).val('');
                    }else{
                        alert("NO SE HA PODIDO COMPLETAR LA SOLICITUD");
                    }
                },error: function( ){
                    
                }
            });
        }
    });

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

        $("#responsable_cc").prop("disabled", $("#responsable_cc").val() != 9).val(''); /** FECHA: 08-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/

        $("#moneda").html( "" );
        $("#moneda").append( '<option value="MXN" data-value="MXN">MXN</option>' );
        $("#moneda").append( '<option value="USD" data-value="USD">USD</option>' );
        $("#moneda").append( '<option value="CAD" data-value="CAD">CAD</option>' );
        $("#moneda").append( '<option value="UDIS" data-value="UDIS">UDIS</option>' );
        
        $("#modal_formulario_solicitud #descr").prop("readonly", true).val('');
        $("#modal_formulario_solicitud #subtotal").prop("readonly", true).val('');
        $("#modal_formulario_solicitud #iva").prop("readonly", true).val('');
        $("#modal_formulario_solicitud #metpag").prop("readonly", true).val('');
        $('.default').prop('checked', true);
        $("input[type=radio][name=servicio1]").prop('disabled', false );

        $("#idproveedor").val('');
        obtenerProyectosDepartemento({});

        //$("#proveedor").replaceWith('<input type="text" name="proveedor" class="form-control" id="proveedor" placeholder="Nombre del proveedor" required>');
        AutocompleteProveedor(_data1);
        $("#idproveedor").val('');

        $('#proyecto').val('').trigger('change');
        $('#Etapa').val('N/A').trigger('change');
        $('#condominio').val('N/A').trigger('change');
        $('#listado_tServicio_partida').val('').trigger('change');

        var validator = $( "#frmnewsol" ).validate();
        validator.resetForm();
        $( "#frmnewsol div" ).removeClass("has-error");

        $("#fecha").attr( "min",  fecha_minima )

        documento_xml = null;
        _data3 = [];
        
    }
     
    function checar_caja_chica( cantidad, moneda, tipo  ){
        if( moneda == 'MXN' && cantidad <= limite_cajachica && tipo == 'EFEC' ){
            if( cantidad )
            alert( "ESTE GASTO PUEDE ENTRAR COMO CAJA CHICA" );
            $(".caja_chica_label").prop('disabled', false );
        }/*else{
            $("input[name='caja_chica']").prop('disabled', true );
            $("input[name='caja_chica']").prop('checked', false );
        }*/
    }

        $("input[name='pago_programado']").click( function(){
            $(".programar_fecha").prop("disabled", ( $(this).prop("checked") ? false : true ) )
        });

        $(document).on( "click", ".abrir_nueva_solicitud", function(){
            resear_formulario();
            recargar_provedores();
            link_post = "Solicitante/guardar_solicitud";
            $("#modal_formulario_solicitud").modal( {backdrop: 'static', keyboard: false} );
        });

        document.getElementById('seleccionarTodoCheckbox').addEventListener('click', function () { /** INICIO FECHA: 23-MAYO-2025 | APROBACION MULTIPLE DA | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
            // Determinar si hay algún checkbox sin seleccionar en esta página
            let shouldSelectAll = false;

            table_autorizar.rows({ page: 'current' }).every(function () {
                let row = this.node();
                let checkbox = row.querySelector('input[type="checkbox"].checkboxes');
                if (checkbox && !checkbox.checked) {
                    shouldSelectAll = true;
                    return false; // salir del bucle
                }
            });

            // Ahora aplicamos la acción (seleccionar o deseleccionar todos)
            table_autorizar.rows({ page: 'current' }).every(function () {
                let data = this.data();
                let idsol = parseInt(data.idsolicitud);
                let row = this.node();
                let checkbox = row.querySelector('input[type="checkbox"].checkboxes');

                if (!checkbox) return; // evitar errores si no se encuentra el checkbox

                if (shouldSelectAll) {
                    if (!solicitudesArray.some(item => item.idsolicitud === idsol)) {
                        solicitudesArray.push({
                            idsolicitud: idsol,
                            fecelab: data.fecelab,
                            caja_chica: parseInt(data.caja_chica),
                            departamento: data.nomdepto, /** FECHA: 03-JUNIO-2025 | MEJORAR RENDIMIENTO ALERTA DA | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                            cantidad: data.cantidad
                        });
                        optionToggle(idsol, 2);
                    }
                    checkbox.checked = true;
                } else {
                    solicitudesArray = solicitudesArray.filter(item => item.idsolicitud !== idsol);
                    optionToggle(idsol, 1);
                    checkbox.checked = false;
                }
            });

            // Actualizar estado visual del checkbox general
            document.getElementById('seleccionarTodoCheckbox').checked = shouldSelectAll;

            validaButtonMulAp();
        }); /** FIN FECHA: 23-MAYO-2025 | APROBACION MULTIPLE DA | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */

        $("#recargar_formulario_solicitud").click( function(){
            resear_formulario();
            recargar_provedores();
        });

        $("#total, #moneda, #forma_pago").change( function(){
            checar_caja_chica( $("#total").val(), $("#moneda").val(), $("#forma_pago").val() );
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
            data.append("cajachica", true);//$('input[type=radio][name=servicio]').val() == '9');

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

                        documento_xml = xml;

                        var informacion_factura = data.datos_xml
                        input.prop('disabled', true); 
                        _data3 = [] ;
                        //$("#proveedor").replaceWith('<select id="proveedor" name="proveedor" class="form-control"  required></select>');
                        if( data.proveedorcc ){  
                            $("#proveedor").empty(); 
                                $("#proveedor").append('<option value="" SELECTED disable >Seleccione una opción</option>'); 
                            $.each(data.proveedorcc, function (i, item) {                                
                                _data3.push({value : item.idproveedor, label : item.nombre + " - " + item.nomba, rfc: item.rfc});
                            });
                            AutocompleteProveedor(_data3);
                            $("#idproveedor").val(data.proveedorcc[0].idproveedor);
                            //$(".lista_provedores_libres").append('<option value="'+data.proveedorcc[0].idproveedor+'" data-value="'+data.proveedorcc[0].rfc+'">'+data.proveedorcc[0].nombre+" - "+(data.proveedorcc[0].nomba.split(" "))[0]+'</option>');
                            //$("input[name='caja_chica']").prop( 'checked', true );
                            //$('.caja_chica_label').prop('checked', true);
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


        $("#moneda").html( '' );
        $("#moneda").append( '<option value="'+informacion_factura.Moneda[0]+'" data-value="'+informacion_factura.Moneda[0]+'">'+informacion_factura.Moneda[0]+'</option>' );

        //$("input[name='proveedor']").val($("#proveedor").val()).prop('disabled', false);
        //$("#proveedor").prop('disabled',true);

        $("input[name='tentra_factura']").prop("checked", true).prop('disabled', true);
        $("input[type='hidden'][name='tentra_factura']").val("1").prop('disabled', false);

        var formapago = informacion_factura.formpago ? informacion_factura.formpago[0] : "N/A";

        $("#obse").append( informacion_factura.condpago ? informacion_factura.condpago[0] : "NA" );
        $("#obse").prop( 'readonly', true );

        $("input[name='empresa']").val($("#empresa").val()).prop('disabled', false);
        $("#responsable_cc").prop("disabled", !$(".caja_chica_label").prop( 'checked' ));

        switch (informacion_factura.formpago[0]){
            case "01":
                $("#forma_pago").val('EFEC');
                break;
            case "02":
                $("#forma_pago").val('ECHQ');
                break;
            case "03":
                $("#forma_pago").val('TEA');
                break;
        }
        
    }


    var solicitudesArray = [];
    function handleCheckBox(obj){
    	let idsolicitud = $(obj).val();
		let fechaElab = obj.getAttribute("data-fechaelab");
		let cajaChica = obj.getAttribute("data-cajachica");
		let nomDpto = obj.getAttribute("data-dpto");
        let cantidad = obj.getAttribute("data-cantidad"); /** FECHA: 23-MAYO-2025 | APROBACION MULTIPLE DA | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */

        let totalCantidad = 0; /** FECHA: 23-MAYO-2025 | APROBACION MULTIPLE DA | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */

		if($(obj).is(":checked")){
			//seteo de las variables actuales
			let arrayHandler = [];
			arrayHandler['idsolicitud'] = parseInt(idsolicitud);
			arrayHandler['fecelab'] = fechaElab;
			arrayHandler['caja_chica'] = parseInt(cajaChica);
			arrayHandler['departamento'] = nomDpto;
            arrayHandler['cantidad'] = cantidad; /** FECHA: 23-MAYO-2025 | APROBACION MULTIPLE DA | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
			//checkeado
			solicitudesArray.push(arrayHandler);
			optionToggle(idsolicitud, 2);

            $.each(solicitudesArray, function(i, v){ /** INICIO FECHA: 23-MAYO-2025 | APROBACION MULTIPLE DA | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                totalCantidad += parseFloat(v.cantidad);
            });
            let txt = `<strong>$${formatMoney(totalCantidad)}</strong> <label style="font-weight: normal;">DE</label>&nbsp;`;
            $("#cantidadSeleccionada").html(txt); /** FIN FECHA: 23-MAYO-2025 | APROBACION MULTIPLE DA | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
		}else{
		// 	//no checkeado
			deleteItemArraySol(solicitudesArray, parseInt(idsolicitud));
		 	optionToggle(idsolicitud, 1)
		 }
		validaButtonMulAp();
		// //multiAcceptContent: id del contenedor innerHTML
	}
	function optionToggle(idsolicitud, accion){
    	let idToDisabled = 'opEv'+idsolicitud;
    	let elementToDisabled = document.getElementById(idToDisabled);
    	if(accion==1){
			elementToDisabled.removeAttribute('disabled');//activar elemento
			elementToDisabled.setAttribute('value', idsolicitud);//activar elemento
			elementToDisabled.classList.remove('not-allowed-btn');//activar elemento: esta clase se definió en AdminLTE.css:4978
			elementToDisabled.classList.add('btn', 'btn-success');//activar elemento
		}else if(accion==2){
			elementToDisabled.setAttribute('disabled', true);//desactivar elemento
			elementToDisabled.removeAttribute('value');//desactivar elemento
			elementToDisabled.classList.remove('btn', 'btn-success'); //desactivar elemento
			elementToDisabled.classList.add('not-allowed-btn');//desactivar elemento esta clase se definió en AdminLTE.css:4978
		}

	}

	function deleteItemArraySol(array, idsolicitud) {
		for (let i = 0; i < array.length; i++) {
			if (array[i]["idsolicitud"] === idsolicitud) {
				array.splice(i, 1);
				break; // Termina el bucle después de eliminar el elemento
			}
		}
		solicitudesArray = array;
	}

    /** INICIO FECHA: 23-MAYO-2025 | APROBACION MULTIPLE DA | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
    function validaButtonMulAp(){
        let cntMultiButton;

        if(solicitudesArray.length > 0){
            $("#env_mul_apro").show();
            let totalCantidad = 0;
            $.each(solicitudesArray, function(i, v){
                totalCantidad += parseFloat(v.cantidad);
            });
            let txt = `<strong>$${formatMoney(totalCantidad)}</strong> <label style="font-weight: normal;">DE</label>&nbsp;`;
            totalCantidad == 0 ? $("#cantidadSeleccionada").html('') : $("#cantidadSeleccionada").html(txt);

            $("#badgeCantidadSol").html(solicitudesArray.length);

        }else{
            $("#env_mul_apro").hide();
            $("#cantidadSeleccionada").html('');
        }
        
        // Ahora verificamos si TODOS los checkboxes visibles están activados
        let totalCheckboxes = 0;
        let checkedCheckboxes = 0;

        table_autorizar.rows({ page: 'current' }).nodes().each(function (row) {
            let checkbox = row.querySelector('input[type="checkbox"].checkboxes');
            if (checkbox) {
                totalCheckboxes++;
                if (checkbox.checked) {
                    checkedCheckboxes++;
                }
            }
        });

        let allChecked = totalCheckboxes > 0 && totalCheckboxes === checkedCheckboxes;

        // Si todos están checked, marcamos el seleccionarTodoCheckbox
        document.getElementById('seleccionarTodoCheckbox').checked = allChecked;
    }

    function askReady() {
        $('#askReadyModal').modal();

        let contenerdorItemsAprobar = document.getElementById('elementsToApprove');
        let contenido = '<div class="row">';

        // Determinar columnas por fila y clase de columna
        let total = solicitudesArray.length;
        let columnasPorFila, colClass;

        if (total <= 10) {
            columnasPorFila = 1;
            colClass = 'col-md-12';
        } else if (total <= 20) {
            columnasPorFila = 2;
            colClass = 'col-md-6';
        } else {
            columnasPorFila = 3;
            colClass = 'col-md-4';
        }

        solicitudesArray.map((element, index) => {
            // Cerrar fila y abrir una nueva cada N elementos
            if (index % columnasPorFila === 0 && index !== 0) {
                contenido += '</div><div class="row">';
            }

            contenido += `
                <div class="${colClass}">
                    <h4>${element.idsolicitud}</h4>
                </div>
            `;
        });

        contenido += '</div>'; // cerrar la última fila
        contenerdorItemsAprobar.innerHTML = contenido;
    } /** FIN FECHA: 23-MAYO-2025 | APROBACION MULTIPLE DA | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */

    function aceptarEnvio(){
    	//parsearlo a json para enviar la data
		let handleArray = [];
		solicitudesArray.map((element, index)=>{
			let handleInside = {};
			handleInside.idsolicitud = element['idsolicitud'];
			handleInside.caja_chica = element['caja_chica'];
			handleInside.fecelab = element['fecelab'];
			handleInside.departamento = element['departamento'];
			handleArray.push(handleInside);
		});



		//base de referencia: Solicitante/aprobada_da
		$.ajax({
			url: 'Solicitante/aprobada_da_multiple',
			method: 'POST',
			data: {data:JSON.stringify(handleArray)},
			success: function(response) {
				// Lógica a ejecutar en caso de éxito
				response = JSON.parse(response);
                /** INICIO FECHA: 23-MAYO-2025 @author Ángel Victoriano <programador.analista30@ciudadmaderas.com>
                 * Se agregó una alerta para confirmar que la aprobación se realizó exitosamente.
                 */
				if(response.status == 'OK'){ /** INICIO FECHA: 03-JUNIO-2025 | MEJORAR RENDIMIENTO ALERTA DA | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                    swal({
                        title: "¡ÉXITO!",
                        text: "SE HA REALIZADO EL MOVIMIENTO CORRECTAMENTE",
                        icon: "success",
                        buttons: false,
                        timer: 800
                    }).then(() => {
                        $('#askReadyModal').modal('toggle');
                        $("#seleccionarTodoCheckbox").prop('checked', false);

                        const ids = new Set(handleArray.map(item => parseInt(item.idsolicitud)));
                        let sumaCantidad = 0;
                        const paginaActual = table_autorizar.page();

                        // Procesamiento por lotes más eficiente
                        table_autorizar.rows({ search: 'none' }).nodes().each((node, index) => {
                            const row = table_autorizar.row(node).data();
                            if (!row) return;
                            
                            if (ids.has(parseInt(row.idsolicitud))) {
                                sumaCantidad += parseFloat(row.cantidad) || 0;
                                table_autorizar.row(node).remove();
                            }
                        });

                        // Restauración precisa de paginación
                        const paginasRestantes = table_autorizar.page.info().pages;
                        table_autorizar.page(Math.min(paginaActual, paginasRestantes - 1)).draw(false);

                        // Actualizar información de paginación
                        if (table_autorizar.page.info().pages !== paginaActual) {
                            // Si la eliminación afectó el número de páginas
                            table_autorizar.page(Math.min(paginaActual, table_autorizar.page.info().pages - 1)).draw(false);
                        }

                        var total = parseFloat($('#myText_1').text().replace("$","").replace(",","")) - parseFloat( sumaCantidad );
                        $("#myText_1").html( "$" + formatMoney(total) );

                        solicitudesArray = [];
                        validaButtonMulAp();
                    });
				}else{ /** FIN FECHA: 03-JUNIO-2025 | MEJORAR RENDIMIENTO ALERTA DA | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
					alert('Ocurrió un error, intentalo nuevamente');
				}
                /** FIN FECHA: 23-MAYO-2025 | APROBACION MULTIPLE DA | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
			},
			error: function(error) {
				// Lógica a ejecutar en caso de error
				console.log('Ocurrió un error');
				console.log(error);
			}
		});
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
            if( i > 0 && i < $('#tabla_autorizaciones thead tr:eq(0) th').length - 1 ){ /** FECHA: 23-MAYO-2025 | APROBACION MULTIPLE DA | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                var title = $(this).text();
                $(this).html( '<input type="text" style="font-size: .9em; width: 100%;" placeholder="'+title+'" title="'+title+'" />' );
        
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
                },
                {
                    text: '<i class="fas fa-clock"></i> PAGO PROGRAMADO',
                    attr: {
                        class: 'btn btn-primary abrir_nueva_solicitud_programada'
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
                // COLUMNA # 0 : IDSOLICITUD
                {
                    "width": "5%",
                    "orderable": false,
                    "data" : function( d ){
                    	let idsolicitud = '<p style="font-size: .8em">'+d.idsolicitud+'</p>';
						let checkbox = '';

                        // /** INICIO FECHA: 23-MAYO-2025 | APROBACION MULTIPLE DA | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                    	if(![1, 6, 8, 13, 25].includes(parseInt(d.idetapa))) { //"Borrador", "Rechazada DA", "Rechazada CXP", "Pago autorizado, espera de liberación Contabilidad", "Pausada Por DA"
                            checkbox = `<input
                                        type="checkbox"
                                        id="chb${d.idsolicitud}"
                                        class="checkboxes"
                                        style="width: 1em; height: 1em;"
                                        value="${d.idsolicitud}"
                                        data-fechaElab="${d.fecelab}"
                                        data-cajaChica="${d.caja_chica}"
                                        data-dpto="${d.nomdepto}"
                                        data-cantidad="${d.cantidad}"
                                        onchange="handleCheckBox(this)"
                                    >`;
						} /** FIN FECHA: 23-MAYO-2025 | APROBACION MULTIPLE DA | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                    	let contenedor = '<div>'+idsolicitud+checkbox+'</div>';
                        return contenedor;
                    }
                },
                
                // COLUMNA # 1 : PROYECTO
                {
                    "width": "12%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+ (!d.proyecto ? d.proyectoNuevo : d.proyecto) +'</p>'
                    }
                },
                // COLUMNA # 2 : OFICINA / SEDE
                {
                    "width": "8%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+ (d.oficina ? d.oficina : "NA") +'</p>'
                    }
                },
                // COLUMNA # 3 : TIPO SERVICIO / PARTIDA
                { /** INICIO FECHA: 08-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                    "width": "8%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+(d.servicioPartida ? d.servicioPartida : 'NA')+'</p>';
                    }
                }, /** FIN FECHA: 08-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/

                // COLUMNA # 4 : FECHA
                {
                    "width": "12%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.fecelab+'</p>'
                    }
                },

                // COLUMNA # 5 : PROVEEDOR
                {
                    "width": "14%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.nombre+'</p>'
                    }
                },

                // COLUMNA # 6 : CAPTURISTA
                {
                    "width": "12%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.nombre_completo+'</p>'
                    }
                },

                // COLUMNA # 7 : CANTIDAD
                {
                    "width": "8%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">$ '+formatMoney( d.cantidad )+'</p>'
                    }
                },

                // COLUMNA # 8 : MONEDA
                {
                    "width": "8%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.moneda+'</p>'
                    }
                },

                // COLUMNA # 9 : T GASTO
                {
                    "width": "15%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.tgasto+'</p>'
                    }
                },
                // COLUMNA # 10 : BOTONES DE ACCION
                { 
                    "data" : "opciones",
                    "orderable": false
                }
            ],
            "columnDefs": [ {
                    "orderable": false, 
                    "targets": 0
                }
            ],
            "order":[
                [ 5, 'desc' ]
            ],
            "ajax":  url + "Solicitante/tabla_autorizaciones",
            "createdRow": function(row, data, dataIndex) {
                // Personaliza el contenido del row.child para esta fila
                var rowChildContent = '<table class="table text-justify">'+
                    '<tr>'+
                        '<td><strong>JUSTIFICACIÓN: </strong>'+data.justificacion+'</td>'+
                    '</tr>'+
                '</table>';

                // Agrega el row.child a la fila actual
                var rowChild = table_autorizar.row(row).child(rowChildContent);
                if (rowChild) {
                    rowChild.show();
                }

                /*var informacion_adicional = '<table class="table text-justify">'+
                    '<tr>'+
                        '<td><strong>JUSTIFICACIÓN: </strong>'+row.data().justificacion+'</td>'+
                    '</tr>'+
                '</table>';

                row.child( informacion_adicional ).show();
                tr.addClass('shown');
                $(this).parent().find('.animacion').removeClass("fa-caret-right").addClass("fa-caret-down");*/
            }
        });

        if (['CONSTRUCCION', 'JARDINERIA', 'OOAM', 'OOAM TECNICO', 'OOAM ADMINISTRATIVO'].includes(depto_usuario)) {
            table_autorizar.column(1).visible(false);
        }

        $("#tabla_autorizaciones").DataTable().rows().every( function () {
            var tr = $(this.node());
            this.child(format(tr.data('child-value'))).show();
            tr.addClass('shown');
        });
        $('#fecInicial').change( function() { 
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
        
        $('#fecFinal').change( function() {
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

                    $(".lista_provedores_libres").html('');
                    $(".lista_provedores_libres").append('<option value="">Seleccione una opción</option>');
                    _data1 = [];
                    _data2 = [];
                    /**
                     * INICIO
                     * FECHA : 27-MAYO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com
                     * Se agrego la cuenta bancaria despues del nombre del bano de cada proveedor al editar la solicitud
                     */
                    $.each( data.proveedores_todos, function( i, v){
                        if(!(v.nombrep.includes('GASTO NO DEDUCIBLE')))
                        {
                            _data1.push({value : v.idproveedor, label : v.nombrep + " - " + v.nom_banco + (v.cuenta ? " - " + v.cuenta : ''), rfc: v.rfc});
                            _data2.push({value : v.idproveedor, label : v.nombrep + " - " + v.nom_banco + (v.cuenta ? " - " + v.cuenta : ''), rfc: v.rfc});
                        }
                        else
                        {
                            _data2.push({value : v.idproveedor, label : v.nombrep + " - " + v.nom_banco + (v.cuenta ? " - " + v.cuenta : ''), rfc: v.rfc});
                        }                        
                    });
                    /**
                     * FIN
                     * FECHA : 27-MAYO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com 
                     */
                    AutocompleteProveedor(data.info_solicitud[0].caja_chica ? _data2 : _data1 );

                    $(".lista_empresa").html('');
                    $(".lista_empresa").append('<option value="">Seleccione una opción</option>');
                    $.each( data.empresas, function( i, v){
                        $(".lista_empresa").append('<option value="'+v.idempresa+'" data-value="'+v.rfc+'">'+v.nombre+'</option>');
                    });
                    
                    idsolicitud = ideditar;
                    if( data.info_solicitud[0].programado != 'NULL' && data.info_solicitud[0].programado != null ){
                        resear_formulario_programado();

                        $("#fechapr").val( data.info_solicitud[0].fecelab );
                        $("#fecha_finalpr").val( data.info_solicitud[0].fecha_fin );
                        $("#totalpr").val( data.info_solicitud[0].cantidad );
                        $("#monedapr").html( "" );
                        $("#monedapr").append( '<option value="'+data.info_solicitud[0].moneda+'" data-value="'+data.info_solicitud[0].moneda+'">'+data.info_solicitud[0].moneda+'</option>' );
                        $("#empresapr option[data-value='"+data.info_solicitud[0].rfc_empresas+"']").prop("selected", true);
                        $("#proveedor_programado option[value='"+data.info_solicitud[0].idProveedor+"']").prop("selected", true);
                        $("#referencia_pagopr").val( data.info_solicitud[0].ref_bancaria );
                        $("#solobspr").val( data.info_solicitud[0].justificacion );
                        $("#orden_compra").val( data.info_solicitud[0].orden_compra );
                        $("#crecibo").val( data.info_solicitud[0].crecibo );
                        justificacion_globla = data.info_solicitud[0].justificacion
                        $("#proyectopr option[value='"+data.info_solicitud[0].proyecto+"']").prop("selected", true).trigger('change');

                        $("#forma_pagopr option[value='"+data.info_solicitud[0].metoPago+"']").prop("selected", true);
                        $("#metodo_pago option[value='"+data.info_solicitud[0].programado+"']").prop("selected", true);
                       // $("#proveedor_programado option[value='"+data.info_solicitud[0].idProveedor+"']").prop("selected", true);
                        $("#proveedor_programado").val(data.info_solicitud[0].idProveedor);
                        uno = data.info_solicitud[0].nombre_proveedor;
                        dos = data.info_solicitud[0].idProveedor;

                        obtenerProyectosDepartemento({ 
                            programado: true, 
                            esApi: data.info_solicitud[0].Api == 1, 
                            valueProyecto: data.info_solicitud[0].idProyectos, 
                            valueOficina: data.info_solicitud[0].idOficina, 
                            valueProyectoViejo: data.info_solicitud[0].proyectoViejo,
                            esProyectoNuevo: data.info_solicitud[0].esProyectoNuevo,
                            departamentoSolicitud: data.info_solicitud[0].nomdepto
                        });

                        $("#modal_solicitud_programado").modal( {backdrop: 'static', keyboard: false} );
                    }
                    else{
                    
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
                            if(data.xml.formpago[0] == "01" || data.xml.formpago[0] == "02" || data.xml.formpago[0] == "03"){
                                $("#forma_pago").prop('disabled', true );
                            }
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
                            $("#solobs").val( data.info_solicitud[0].justificacion );
                            $("input[name='tentra_factura']").prop('disabled',!depto_excep.includes(data.info_solicitud[0].nomdepto) );                        
                            $("input[type=radio][name=servicio1]").prop('disabled', false );
                        }
                        $("input[type=hidden][name='servicio']").val(data.info_solicitud[0].caja_chica ? '9' : data.info_solicitud[0].servicio).prop('disabled', false);
                        $("input[name='tentra_factura']").prop("checked", ( data.info_solicitud[0].tendrafac == 1 ? true : false ) );      
                        $("input[name='prioridad']").prop("checked", ( data.info_solicitud[0].prioridad ? true : false ) ); 
                        $("input[type=radio][name=servicio1][value='1']").prop("checked",( data.info_solicitud[0].servicio == 1 ? true : false ));      
                        $(".caja_chica_label").prop('checked', ( data.info_solicitud[0].caja_chica ? true : false ) );
                        $("#solobs").val( data.info_solicitud[0].justificacion );

                        
                        $("#Etapa").val(data.info_solicitud[0].etapa);
                        $("#condominio").val(data.info_solicitud[0].condominio);
                        
                        justificacion_globla = data.info_solicitud[0].justificacion
                        $("#proyecto option[value='"+data.info_solicitud[0].proyecto+"']").prop("selected", true);
                        $("#listado_tServicio_partida option[value='"+data.info_solicitud[0].idTipoServicioPartida+"']").prop("selected", true).change(); /** FECHA: 08-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                        
                        $("#homoclave option[value='"+data.info_solicitud[0].homoclave+"']").prop("selected", true);
                        if ($("#homoclave").val() == "N/A" && ['CONSTRUCCION', 'JARDINERIA', 'OOAM', 'OOAM TECNICO', 'OOAM ADMINISTRATIVO'].includes(depto) && data.info_solicitud[0].homoclave != "N/A") {
                            $("#homoclave").append('<option value="'+data.info_solicitud[0].homoclave+'">'+data.info_solicitud[0].homoclave+'</option>');
                            $("#homoclave option[value='"+data.info_solicitud[0].homoclave+"']").prop("selected", true).trigger('change');
                        }
                        $("#forma_pago option[data-value='"+data.info_solicitud[0].metoPago+"']").prop("selected", true);
                        $("#proveedor option[value='"+data.info_solicitud[0].idProveedor+"']").prop("selected", true);
                        //$("#proveedor").val(data.info_solicitud[0].nombre_proveedor);
                        $("#idproveedor").val(data.info_solicitud[0].idProveedor);

                        obtenerProyectosDepartemento({ 
                            esApi: data.info_solicitud[0].Api == 1, 
                            valueProyecto: data.info_solicitud[0].idProyectos, 
                            valueOficina: data.info_solicitud[0].idOficina,
                            valueProyectoViejo: data.info_solicitud[0].proyectoViejo,
                            esProyectoNuevo: data.info_solicitud[0].esProyectoNuevo,
                            departamentoSolicitud: data.info_solicitud[0].nomdepto
                        });

                        uno = data.info_solicitud[0].nombre_proveedor;
                        dos = data.info_solicitud[0].idProveedor;
            

                        $("#modal_formulario_solicitud").modal( {backdrop: 'static', keyboard: false} );
                    }
                }
                else alert("HA OCURRIDO UN ERROR")
                                    
            });
        });

        $('#tabla_autorizaciones').on( "click", ".borrar_solicitud", function(){
            var tr = $(this).closest('tr');
            var cantidad = table_autorizar.row(tr).data().cantidad;
            $.post( url + "Solicitante/borrar_solicitud", { idsolicitud : $(this).val() } ).done( function( data ){
                cuenta = 0;
                posicion = data.indexOf("}");
                while ( posicion != -1 ) {
                cuenta++;
                posicion = data.indexOf("}",posicion+1);
                }

                if(cuenta > 1){
                data=data.replace(/}/, ",");
                rest = "{"+data.replace(/}|{/g, "")+"}";
                data = JSON.parse( rest );
                }else{
                    data = JSON.parse( data );
                }
                if( data.resultado ){
                    table_autorizar.row( tr ).remove().draw(false);
                    var total = parseFloat($('#myText_1').text().replace("$ ","").replace(",","")) - parseFloat(cantidad);                    
                    $("#myText_1").html( "$ " + formatMoney(total) );
                    //table_autorizar.ajax.reload(null, false);
                }else{
                    alert("HA OCURRIDO UN ERROR")
                }
            });
        });

        $('#tabla_autorizaciones').on( "click", ".enviar_a_dg", function(){
            var tr = $(this).closest('tr');  
            var cantidad = table_autorizar.row(tr).data().cantidad;                      
            $.post( url + "Solicitante/enviar_a_dg", { idsolicitud : $(this).val() } ).done( function( data ){
                data = JSON.parse( data );
                if( data.resultado ){
                    table_autorizar.ajax.reload();
                    var total = parseFloat($('#myText_1').text().replace("$ ","").replace(",","")) - parseFloat(cantidad);                    
                    $("#myText_1").html( "$ " + formatMoney(total) );
                    //table_autorizar.ajax.reload(null,false);
                    //table_proceso.ajax.reload(null,false);
                }else{
                    alert("HA OCURRIDO UN ERROR")
                }
            });
        });

        $('#tabla_autorizaciones').on( "click", ".reenviar_factura", function(){
            var tr = $(this).closest('tr');
            var cantidad = table_autorizar.row(tr).data().cantidad;
            $.post( url + "Solicitante/reenviar_factura", { idsolicitud : $(this).val() } ).done( function( data ){
                data = JSON.parse( data );
                if( data.resultado ){
                    table_autorizar.row( tr ).remove().draw(false);
                    var total = parseFloat($('#myText_1').text().replace("$ ","").replace(",","")) - parseFloat(cantidad);                    
                    $("#myText_1").html( "$ " + formatMoney(total) );
                    //table_autorizar.ajax.reload(null, false);
                    //table_proceso.ajax.reload(null,false);
                }else{
                    alert("HA OCURRIDO UN ERROR")
                }
            });
        });

        $('#tabla_autorizaciones').on( "click", ".aprobada_da", function(){
            var tr = $(this).closest('tr');
            var row = table_autorizar.row(tr).data();
            $.post( url + "Solicitante/aprobada_da", { idsolicitud : $(this).val(), fecelab : row.fecelab, caja_chica : row.caja_chica } ).done( function( data ){
                data = JSON.parse( data );
                /** MEJORA INICIO FECHA: 03-JUNIO-2025 | MEJORAR RENDIMIENTO ALERTA DA | @author Angel Victoriano <programador.analista30@ciudadmaderas.com>
                 * Se agregó una alerta para confirmar que la aprobación se realizó exitosamente.
                 */
                if( data.resultado ){
                    swal({
                        title: "¡ÉXITO!",
                        text: data.mensaje.toUpperCase(),
                        icon: "success",
                        buttons: false,
                        timer: 800
                    }).then(() => {
                        table_autorizar.row( tr ).remove().draw(false);
                        var total = parseFloat($('#myText_1').text().replace("$","").replace(",","")) - parseFloat( row.cantidad );
                        $("#myText_1").html( "$" + formatMoney(total) );

                        if (solicitudesArray.length > 0) {
                            solicitudesArray.forEach(element => {
                                optionToggle(element.idsolicitud, 2);
                            });
                            validaButtonMulAp();
                        }
                    });
                }else{
                    alert( data.mensaje );
                }/** FIN FECHA: 03-JUNIO-2025 | MEJORAR RENDIMIENTO ALERTA DA | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
            });
        });

        $('#tabla_autorizaciones').on( "click", ".rechazada_da", function(){
            var tr = $(this).closest('tr');
            var cantidad = table_autorizar.row(tr).data().cantidad;
            $.post( url + "Solicitante/rechazada_da", { idsolicitud : $(this).val() } ).done( function( data ){
                data = JSON.parse( data );
                if( data.resultado ){
                    table_autorizar.row( tr ).remove().draw(false);
                    var total = parseFloat($('#myText_1').text().replace("$ ","").replace(",","")) - parseFloat(cantidad);                    
                    $("#myText_1").html( "$ " + formatMoney(total) );
                    //table_autorizar.ajax.reload(null,false);
                }else{
                    alert("HA OCURRIDO UN ERROR")
                }
            });
        });

        $('#tabla_autorizaciones').on( "click", ".congelar_solicitud", function(){
            var tr = $(this).closest('tr');
            $.post( url + "Solicitante/congelar_solicitud", { idsolicitud : $(this).val() } ).done( function( data ){
                data = JSON.parse( data );
                if( data.resultado ){
                    //table_autorizar.row( tr ).remove().draw();
                    table_autorizar.ajax.reload(null,false);
                }else{
                    alert("HA OCURRIDO UN ERROR")
                }
            });
        });

        $('#tabla_autorizaciones').on( "click", ".liberar_solicitud", function(){
            var tr = $(this).closest('tr');
            $.post( url + "Solicitante/liberar_solicitud", { idsolicitud : $(this).val() } ).done( function( data ){
                data = JSON.parse( data );
                if( data.resultado ){
                    //table_autorizar.row( tr ).remove().draw();
                    table_autorizar.ajax.reload(null,false);
                }else{
                    alert("HA OCURRIDO UN ERROR")
                }
            });
        });
        let containerBtns = document.getElementsByClassName('dt-buttons');
        containerBtns[0].innerHTML += '<button class="btn btn-warning" id="env_mul_apro" onclick="askReady()"><i class="fas fa-thumbs-up"></i> ENVIAR MULTIPLES APROBACIONES &nbsp;<span class="badge pull-right" id="badgeCantidadSol"></span></button>'; /** FECHA: 23-MAYO-2025 | APROBACION MULTIPLE DA | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
        $("#env_mul_apro").hide();
		//containerBtns[0].innerHTML += '<div id="multiAcceptContent"></div>';

        /** INICIO FECHA: 23-MAYO-2025 | APROBACION MULTIPLE DA | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
        table_autorizar.on('draw', function () {
            // Desmarcar el checkbox general inicialmente
            document.getElementById('seleccionarTodoCheckbox').checked = false;

            let totalCheckboxes = 0;
            let checkedCheckboxes = 0;

            // Marcar los checkboxes según solicitudesArray
            table_autorizar.rows({ page: 'current' }).nodes().each(function (row) {
                let checkbox = row.querySelector('input[type="checkbox"].checkboxes');
                let valor = checkbox?.value;

                if (checkbox) {
                    totalCheckboxes++;
                    if (solicitudesArray.some(item => item.idsolicitud == valor)) {
                        optionToggle(checkbox.value, 2);
                        checkbox.checked = true;
                        checkedCheckboxes++;
                    } else {
                        checkbox.checked = false;
                    }
                }
            });

            // Si todos los checkboxes visibles están activados
            if (totalCheckboxes > 0 && totalCheckboxes === checkedCheckboxes) {
                document.getElementById('seleccionarTodoCheckbox').checked = true;
            }

            // Mostrar u ocultar el botón de aprobación múltiple
            $('#env_mul_apro').toggle(solicitudesArray.length > 0);
        }); /** FIN FECHA: 23-MAYO-2025 | APROBACION MULTIPLE DA | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
    });

    var table_proceso;

    $('#tblsolact').on('xhr.dt', function ( e, settings, json, xhr ) {
            
        var total = 0;
        var descuento = 0

        $.each( json.data,  function(i, v){
            total += parseFloat(v.cantidad);
            if( v.tipo_comentario == 'INTERCAMBIO' )
                descuento += v.descuento ? parseFloat(v.descuento) : 0;
        });

        document.getElementById("myText_2").value = formatMoney( total );
        document.getElementById("total_descuento").value = formatMoney( descuento );
    });

    $("#tblsolact").ready( function () {
        
        $('#tblsolact thead tr:eq(0) th').each( function (i) {
            if( i != 17 ){ /** FECHA: 08-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                var title = $(this).text();
                titulos_intxt.push(title);
                $(this).html( '<input type="text" style="font-size: .9em; width:100%" data-value="'+title+'" placeholder="'+title+'" title="'+title+'"/>' );
        
                $( 'input', this ).on( 'keyup change', function () {
                    if ( table_proceso.column(i).search() !== this.value ) {
                        table_proceso
                            .column(i)
                            .search( this.value )
                            .draw();

                            valor_input[i] = this.value;
                    }

                    var total = 0;
                    var index = table_proceso.rows( { selected: true, search: 'applied' } ).indexes();
                    var data = table_proceso.rows( index ).data();
                    var descuento = 0

                    $.each( data,  function(i, v){
                        total += parseFloat(v.cantidad);
                        if( v.tipo_comentario == 'INTERCAMBIO' )
                            descuento += v.descuento ? parseFloat(v.descuento) : 0;
                    });

                    document.getElementById("myText_2").value = formatMoney( total );
                    document.getElementById("total_descuento").value = formatMoney( descuento );

                } );
            }
        });
        // Facturas activas
        table_proceso = $('#tblsolact').DataTable({
            dom: 'Brtip',
            "buttons": [
                {
                    extend: 'excelHtml5',             
                    text: '<i class="fas fa-file-excel"></i> EXPORTAR REPORTE',
                    titleAttr: "FACTURAS ACTIVAS",
                    title: "FACTURAS ACTIVAS",
                    exportOptions: {
                        format:{    
                            header:  function (data, columnIdx) {
                                return ' ' + titulos_intxt[columnIdx] + ' ';
                            }
                        },
                        columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16 ] /** FECHA: 08-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                    },
                    attr: {
                        class: 'btn btn-success',
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
                    "width": "6%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.abrev+'</p>'
                    }
                },
                {
                    "width": "10%", /** FECHA: 08-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.proyecto+'</p>'
                    }
                },
                // COLUMNA # 3 : OFICINA / SEDE
                {
                    "width": "8%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+ (d.oficina ? d.oficina : "NA") +'</p>'
                    }
                },
                { /** INICIO FECHA: 08-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                    "width": "10%",
                    "data" : function( d ){
                        // !$row->proyectoNuevo ? $row->proyecto : $row->servicioPartida
                        return '<p style="font-size: .8em">'+(d.tServicioPartida ? d.tServicioPartida : 'NA')+'</p>';
                    }
                }, /** FIN FECHA: 08-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                {
                    "width": "8%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.folio+'</p>'
                    }
                },
                {
                    "width": "15%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.nombre+'</p>'
                    }
                },
                // {
                //     "data" : function( d ){
                //         return `<p style="font-size: .8em">${d.nombre_contrato?d.nombre_contrato: ""}</p>`
                //     }
                // },
                {
                    "width": "10%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+(d.fecelab ? formato_fechaymd(d.fecelab) : "")+'</p>' +'<small class="label pull-center bg-blue">'+d.metoPago+'</small></p>'
                    }
                },
                {
                    "width": "10%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.nombre_completo+'</p>'
                    }
                },
                {
                    "width": "10%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+formatMoney(d.cantidad)+" "+d.moneda+'</p>'
                    }
                },
                {
                    "width": "10%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+(d.tipo_comentario == 'INTERCAMBIO' ? formatMoney(d.descuento) : '-')+'</p>'
                    }
                },
                {
                    "width": "9%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+formatMoney(d.pagado)+'</p>'
                    }
                },
                {
                    "width": "9%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+formatMoney( d.cantidad - d.pagado )+'</p>'
                    }
                },
                {
                    "width": "12%",
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
                    "width": "10%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.descripcion+'</p>'
                    }
                },
                {
                    "width": "10%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.orden_compra+'</p>'
                    }
                },
                {
                    "width": "10%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+(d.contrato ? d.contrato : 'NA')+'</p>' /** FECHA: 08-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                    }
                },
                {
                    "orderable": false,
                    "data": function( d ){
                        return '<div class="btn-group-vertical"><button type="button" class="btn btn-primary consultar_modal notification" value="'+d.idsolicitud+'" data-value="BAS"><i class="fas fa-eye"></i>'+(d.visto == 0 ? '<span class="badge">!</span>' : '')+'</button></div>';
                    }
                }
            ],
            "columnDefs": [ 
                {
                    "targets": [ 14 ], /** FECHA: 08-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                    "visible": false,
                },
                {
                    "targets": [ 15 ], /** FECHA: 08-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                    "visible": false,
                },
                {
                    "targets": [ 16 ], /** FECHA: 08-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                    "visible": false,
                }

            ],
            ajax: {
                url: 'Solicitante/tabla_facturas_encurso', // Ruta al servidor o servicio para obtener datos
                type: 'POST',
                data: {
                    fechaInicial: fechaInicial, 
                    fechaFinal: fechaFinal
                } // Enviar la nueva fecha al servidor
            }
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

        $('#fecInicial').change( function() {
            fechaInicial = $(this).val();
            fechaFinal = $("#fecFinal").val();
            $.ajax({
                url: 'Solicitante/tabla_facturas_encurso', // Ruta al servidor o servicio para obtener datos
                type: 'POST',
                data: {
                    fechaInicial: fechaInicial, 
                    fechaFinal: fechaFinal
                }, // Enviar la nueva fecha al servidor
                success: function(result) {
                    // Actualizar la DataTable con los nuevos datos
                    resultado = JSON.parse(result);
                    table_proceso.clear().draw();
                    table_proceso.rows.add(resultado.data);
                    table_proceso.columns.adjust().draw();
                    
                    
                    var total = 0;
                    var index = table_proceso.rows( { selected: true, search: 'applied' } ).indexes();
                    var data = table_proceso.rows( index ).data();
                    $.each(data, function(i, v){
                        total += parseFloat(v.cantidad);
                    });
                    var to1 = formatMoney(total);
                    document.getElementById("myText_2").value = to1;
                }
            });
            $("#fecInicial").datepicker("hide");
        });

        $('#fecFinal').change( function() {
            fechaFinal = $(this).val();
            fechaInicial = $("#fecInicial").val();
            $.ajax({
                url: 'Solicitante/tabla_facturas_encurso', // Ruta al servidor o servicio para obtener datos
                type: 'POST',
                data: {
                    fechaInicial: fechaInicial, 
                    fechaFinal: fechaFinal
                }, // Enviar la nueva fecha al servidor
                success: function(result) {
                    // Actualizar la DataTable con los nuevos datos
                    resultado = JSON.parse(result);
                    table_proceso.clear().draw();
                    table_proceso.rows.add(resultado.data);
                    table_proceso.columns.adjust().draw();
                    
                    
                    var total = 0;
                    var index = table_proceso.rows( { selected: true, search: 'applied' } ).indexes();
                    var data = table_proceso.rows( index ).data();
                    $.each(data, function(i, v){
                        total += parseFloat(v.cantidad);
                    });
                    var to1 = formatMoney(total);
                    document.getElementById("myText_2").value = to1;
                }
            });
            $("#fecFinal").datepicker("hide");
         });

    });

    var table_pagos_sin_factura;
    var id;
    var link_complentos;

    $("#complemento_facturas").submit( function(e) {
        e.preventDefault();
    }).validate({
        submitHandler: function( form ) {

            var data = new FormData();
            data.append("id", id);
            data.append("xmlfile", $("#complemento")[0].files[0]);

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
                        $("#factura_complemento .form-control").val( '');
                        $("#factura_complemento").modal( 'toggle');
                         if(link_complentos == "Solicitante/cargaxml_pagos"){
                          
                        table_pagos_sin_factura.ajax.reload();
                       }else{
                        table_complemento.ajax.reload();
                    }
                    }else{
                        alert( data.respuesta[1] );
                    }
                },error: function( ){
                    
                }
            });
        }
    });

    /* ------------------------------------------------------------------------------------------------------- */
    
    var uno ="";
    var dos ="";
    var tres ="";

    function AutocompleteProveedor(data){
        var id =  $(".lista_provedores_libres").val();
        $(".lista_provedores_libres").empty().append('<option value="" selected>Seleccione una opción</option>');
            $.each(data, function (i, item) {
                $('.lista_provedores_libres').append('<option value="'+item.value+'" data-tinsumo="'+item.tinsumo+'" data-privilegio="'+item.excp+'" data-value="'+item.rfc+'">'+item.label+'</option>');
        });
    }

    function obtenerProyectosDepartemento(params){
        input = params.programado /** INICIO FECHA: 08-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
            ? { proyecto: "proyectopr", oficina: 'oficinapr', servicioPartida: 'listado_tServicio_partidapr'  }
            : { proyecto: "proyecto", oficina: 'oficina', servicioPartida: 'listado_tServicio_partida'  } ; /** FIN FECHA: 08-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
        
        $(`#${input.proyecto}`).empty()
        $(`#${input.oficina}`).empty();

        $(`#${input.proyecto}`).append('<option value="" >Seleccione una opción</option>');
        $(`#${input.oficina}`).append('<option value="" >Seleccione una opción</option>');
        if (['CONSTRUCCION', 'JARDINERIA', 'OOAM', 'OOAM TECNICO', 'OOAM ADMINISTRATIVO'].includes(depto_usuario)) {
            $('#crecibo').parent().show();
            $('#requisicion').parent().show();
            $('#orden_compra').prop('required', true);
        }else{
            $('#orden_compra').prop('required', false)
            $('#orden_compra').parent().children('label').eq(0).children('span').eq(0).remove()
            $('#crecibo').parent().hide();
            $('#requisicion').parent().hide();
        }
        // console.log({ /** INICIO FECHA: 08-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
        //     inArray: depto_excep_proyecto.includes(depto),
        //     Api: params.esApi,
        //     departamento: depto_excep_proyecto.includes(params.departamentoSolicitud)
        // })
        // if(depto_excep_proyecto.includes(depto) || params.esApi || depto_excep_proyecto.includes(params.departamentoSolicitud)){
        //     $.each( lista_proyectos_depto[1], function( i, v){
        //         $(`#${input.proyecto}`).append('<option value="'+v.concepto+'" >'+v.concepto+'</option>');
        //     });
        //     $(`#${input.oficina}`).parent().hide();
        //     $(`#${input.servicioPartida}`).parent().hide();
        //     if(params.valueProyecto)  $(`#${input.proyecto}`).val(params.valueProyecto)
        // }else{ /** FIN FECHA: 08-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
            $.each( lista_proyectos_depto[0], function( i, v){
                $(`#${input.proyecto}`).append('<option value="'+v.idProyectos+'" >'+v.concepto+'</option>');
            });
            $(`#${input.oficina}`).parent().show();
            $(`#${input.servicioPartida}`).parent().show(); /** FECHA: 08-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
            
            // if(params.esProyectoNuevo == 'S' && params.valueProyecto){ /** FECHA: 08-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                $(`#${input.proyecto}`).val(params.valueProyecto)
                obtenerListadoOficinas({input: input.oficina, idOficina: params.valueOficina})
            // }else{ /** INICIO FECHA: 08-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
            //     $(`#${input.proyecto}`).append('<option value="'+params.valueProyectoViejo+'">'+params.valueProyectoViejo+'</option>');
            //     $(`#${input.proyecto}`).val(params.valueProyecto).change();
            // }
        // } /** FIN FECHA: 08-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/

    }

    /** INICIO FECHA: 23-MAYO-2025 | APROBACION MULTIPLE DA | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
    $(window).resize(function(){
        table_autorizar.columns.adjust().draw(false);
        table_proceso.columns.adjust().draw(false);
    });

    $('.sidebar-toggle').click(function() {
        setTimeout(function() {
            // 1. Ajustar columnas
            table_autorizar.columns.adjust();
            table_proceso.columns.adjust();
            // 2. Forzar actualización de encabezados
            var headerCellsTableAutorizar = $('#tabla_autorizaciones thead th');
            headerCellsTableAutorizar.each(function() {
                var newWidth = $(this).width(); // Obtener ancho actualizado
                $(this).css('width', newWidth + 'px'); // Aplicarlo
            });
            var headerCellsTableProceso = $('#tblsolact thead th');
            headerCellsTableProceso.each(function() {
                var newWidth = $(this).width(); // Obtener ancho actualizado
                $(this).css('width', newWidth + 'px'); // Aplicarlo
            });
            // 3. Redibujar (sin resetear paginación)
            table_autorizar.draw(false);
            table_proceso.draw(false);
        }, 300); // Esperar a que termine la animación del sidebar
    }); /** FIN FECHA: 23-MAYO-2025 | APROBACION MULTIPLE DA | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
</script>
<?php
    require("footer.php");
?>