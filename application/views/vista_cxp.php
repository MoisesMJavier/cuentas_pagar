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
                        <h3>PAGOS AUTORIZADOS</h3>
                    </div>
                    <div class="box-body">
                        <a id="destxt" href="#" download></a>
                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                                <li class="active"><a id="profile-tab" data-toggle="tab" data-value="pagos_autoriza_dg_trans" href="#pagos_autoriza_dg_trans" role="tab" aria-controls="#home" aria-selected="true">PAGOS TRANSFERENCIAS</a></li>
                                <li><a id="profile-tab" data-toggle="tab" data-value="pagos_autoriza_dg_cheques" href="#pagos_autoriza_dg_cheques" role="tab" aria-controls="pagos_autoriza_dg_cheques" aria-selected="false">PAGOS OTROS </a></li>
                                <li><a id="profile-tab" data-toggle="tab" data-value="pagos_autoriza_dg_chica" href="#pagos_autoriza_dg_chica" role="tab" aria-controls="pagos_autoriza_dg_chica" aria-selected="false">PAGOS CAJA CHICA</a></li>
                                <li><a id="profile-tab" data-toggle="tab" data-value="pagos_facturaje" href="#pagos_facturaje" role="tab" aria-controls="pagos_facturaje" aria-selected="false">PAGOS FACTORAJE</a></li>
                                <li><a id="profile-tab" data-toggle="tab" data-value="pagos_programados" href="#pagos_programados" role="tab" aria-controls="pagos_programados" aria-selected="false">PAGOS PROGRAMADOS</a></li>
                                <li><a id="profile-tab" data-toggle="tab" data-value="pagos_autoriza_dg_tdc" href="#pagos_autoriza_dg_chica" role="tab" aria-controls="pagos_autoriza_dg_chica" aria-selected="false">PAGOS TDC</a></li>
                                <li><a id="profile-tab" data-toggle="tab" data-value="pagos_pausados" href="#pagos_pausados" role="tab" aria-controls="pagos_pausados" aria-selected="false">PAGOS PAUSADOS</a></li>
                                <li><a id="profile-tab" data-toggle="tab" data-value="pagos_autoriza_dg_reembolsos" href="#pagos_autoriza_dg_reembolsos" role="tab" aria-controls="pagos_autoriza_dg_reembolsos" aria-selected="false">PAGOS REEMBOLSOS</a></li>
                                <li><a id="profile-tab" data-toggle="tab" data-value="pagos_autoriza_dg_viaticos" href="#pagos_autoriza_dg_viaticos" role="tab" aria-controls="pagos_autoriza_dg_viaticos" aria-selected="false">PAGOS VIÁTICOS</a></li>
                            </ul>
                        </div>
                        <div class="tab-content">
                            <div class="active tab-pane" id="pagos_autoriza_dg_trans">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <h4>TRANSFERENCIAS ELECTRÓNICAS <i class="far fa-question-circle faq" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" title="Autorizaciones" data-content="Pagos autorizados como transferencias por Dirección General" data-placement="right"></i><label>&nbsp;Total: $<input style="border-bottom: none; border-top: none; border-right: none;  border-left: none; background: white;" disabled="disabled" readonly="readonly" type="text" name="myText2" id="myText2"></label></h4><BR>
                                        <table class="table table-responsive table-bordered table-striped table-hover" id="tabla_autoriza_dg_tranferencias" name="tabla_autoriza_dg_tranferencias">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th style="font-size: .8em"></th>
                                                    <th style="font-size: .8em"></th>
                                                    <th style="font-size: .8em">#</th>
                                                    <th style="font-size: .8em">FOLIO</th>
                                                    <th style="font-size: .8em">PROVEEDOR</th>
                                                    <th style="font-size: .8em">CANTIDAD</th>
                                                    <th style="font-size: .8em">CANT. MXN / INTÉRES</th>
                                                    <th style="font-size: .8em">FECHA</th>
                                                    <th style="font-size: .8em">DEPARTAMENTO</th>
                                                    <th style="font-size: .8em">EMPRESA</th>
                                                    <th style="font-size: .8em"></th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="pagos_autoriza_dg_cheques">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <h4>ECHQ, MAN, EFEC, DOMIC <i class="far fa-question-circle faq" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" title="Autorizaciones" data-content="Pagos autorizados por Dirección General" data-placement="right"></i><label>&nbsp;Total: $<input style="border-bottom: none; border-top: none; border-right: none;  border-left: none; background: white;" disabled="disabled" readonly="readonly" type="text" name="myText3" id="myText3"></label></h4>
                                        <table class="table table-responsive table-bordered table-striped table-hover" id="tabla_autoriza_dg_otros" name="tabla_autoriza_dg_otros">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th style="font-size: .9em"></th>
                                                    <th style="font-size: .9em"></th>
                                                    <th style="font-size: .9em">#</th>
                                                    <th style="font-size: .9em">FOLIO</th>
                                                    <th style="font-size: .9em">PROVEEDOR</th>
                                                    <th style="font-size: .9em">CANTIDAD</th>
                                                    <th style="font-size: .8em">CANT. MXN / INTÉRES</th>
                                                    <th style="font-size: .9em">PAGO</th>
                                                    <th style="font-size: .9em">FECHA</th>
                                                    <th style="font-size: .9em">DEPARTAMENTO</th>
                                                    <th style="font-size: .9em">EMPRESA</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div> <!--End solicitudes finalizadas-->
                            <div class="tab-pane fade" id="pagos_autoriza_dg_chica">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="box">
                                            <h4>&nbsp;PAGOS AUTORIZADOS <i class="far fa-question-circle faq" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" title="Autorizaciones" data-content="Pagos terminados" data-placement="right"></i><label>&nbsp;Total: $<input style="border-bottom: none; border-top: none; border-right: none;  border-left: none; background: white;" disabled="disabled" readonly="readonly" type="text" name="myText4" id="myText4"></label></h4>
                                            <hr>
                                            <table class="table table-responsive table-bordered table-striped table-hover" id="tabla_cajachica" name="tabla_cajachica">
                                                <thead>
                                                    <tr>
                                                        <th></th>                                              <!-- COLUMNA[ 0 ] -->
                                                        <th style="font-size: .9em">RESPONSABLE</th>           <!-- COLUMNA[ 1 ] -->
                                                        <th style="font-size: .9em">RESPONSABLE REEMBOLSO</th> <!-- COLUMNA[ 2 ] --> <!-- FECHA: 29-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                                                        <th style="font-size: .9em">EMPRESA</th>               <!-- COLUMNA[ 3 ] -->
                                                        <th style="font-size: .9em">FECHA FACTURA</th>         <!-- COLUMNA[ 4 ] -->
                                                        <th style="font-size: .9em">CANTIDAD</th>              <!-- COLUMNA[ 5 ] -->
                                                        <th style="font-size: .9em">DEPARTAMENTO</th>          <!-- COLUMNA[ 6 ] -->
                                                        <th style="font-size: .8em">MÉTODO INGRESADO</th>      <!-- COLUMNA[ 7 ] -->
                                                        <th></th>                                              <!-- COLUMNA[ 8 ] -->
                                                    </tr>
                                                </thead>

                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div> <!--End solicitudes finalizadas-->
                            <div class="tab-pane fade" id="pagos_facturaje">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <h4>FACT. BAJIO, FACT. BANREGIO<i class="far fa-question-circle faq" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" title="Autorizaciones" data-content="Pagos autorizados por Dirección General" data-placement="right"></i><label>&nbsp;Total: $<input style="border-bottom: none; border-top: none; border-right: none;  border-left: none; background: white;" disabled="disabled" readonly="readonly" type="text" name="myText5" id="myText5"></label></h4>
                                        <?php $sedesRep = $this->db->query("select * from cuentas"); ?>
                                        <div class="col-lg-4"><select class="form-control" style="width: 100%;font-size: 15px;" name="factoraje_sel" id="factoraje_sel" required="required">
                                                <option selected value="">TODOS</option><?php foreach ($sedesRep->result() as $row) {
                                                                                            echo '<option data-value="' . $row->idcuenta . '" value="' . $row->idempresa . '">' . $row->nombre . '</option>)';
                                                                                        } ?>
                                            </select></div>
                                        <table class="table table-responsive table-bordered table-striped table-hover" id="tabla_autoriza_dg_fact" name="tabla_autoriza_dg_fact">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th style="font-size: .9em"></th>
                                                    <th style="font-size: .9em">#</th>
                                                    <th style="font-size: .9em">FOLIO</th>
                                                    <th style="font-size: .9em">PROVEEDOR</th>
                                                    <th style="font-size: .9em">CANTIDAD</th>
                                                    <th style="font-size: .9em">CANTIDAD MXN</th>
                                                    <th style="font-size: .9em">PAGO</th>
                                                    <th style="font-size: .9em">FECHA</th>
                                                    <th style="font-size: .9em">DEPARTAMENTO</th>
                                                    <th style="font-size: .9em">EMPRESA</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="pagos_programados">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <h4>PAGOS PROGRAMADOS <i class="far fa-question-circle faq" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" title="Autorizaciones" data-content="Pagos autorizados como transferencias por Dirección General" data-placement="right"></i><label>&nbsp;Total: $<input style="border-bottom: none; border-top: none; border-right: none;  border-left: none; background: white;" disabled="disabled" readonly="readonly" type="text" name="myText8" id="myText8"></label></h4><BR>
                                        <table class="table table-responsive table-bordered table-striped table-hover" id="tabla_autorizados_programados" name="tabla_autorizados_programados">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th style="font-size: .8em"></th>
                                                    <th style="font-size: .8em"></th>
                                                    <th style="font-size: .8em">#</th>
                                                    <th style="font-size: .8em">PROVEEDOR</th>
                                                    <th style="font-size: .8em">CANTIDAD</th>
                                                    <th style="font-size: .8em">INTÉRES</th>
                                                    <th style="font-size: .8em">TOTAL PAGAR</th>
                                                    <th style="font-size: .8em">MÉTODO PAGO</th>
                                                    <th style="font-size: .8em">FECHA</th>
                                                    <th style="font-size: .8em">DEPARTAMENTO</th>
                                                    <th style="font-size: .8em">EMPRESA</th>
                                                    <th style="font-size: .8em"></th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="pagos_pausados">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="col-lg-6">
                                            <div class="input-group">
                                                <span class="input-group-addon"><b>PAGOS PAUSADOS TOTAL $</b></span>
                                                <input class="form-control" type="text" name="myText_pausados" id="myText_pausados" readonly>
                                            </div>
                                        </div>
                                        <table class="table table-responsive table-bordered table-striped table-hover" id="tabla_pagos_pausados" name="tabla_pagos_pausados">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th style="font-size: .8em"></th>
                                                    <th style="font-size: .8em">#</th>
                                                    <th style="font-size: .8em">PROVEEDOR</th>
                                                    <th style="font-size: .8em">CANTIDAD</th>
                                                    <th style="font-size: .8em">METODO PAGO</th>
                                                    <th style="font-size: .8em">F AUTORIZADO</th>
                                                    <th style="font-size: .8em">DEPARTAMENTO</th>
                                                    <th style="font-size: .8em">EMPRESA</th>
                                                    <th style="font-size: .8em">TIPO PAGO</th>
                                                    <th style="font-size: .8em">JUSTIFICACIÓN</th>
                                                    <th style="font-size: .8em">MOTIVO ESPERA</th>
                                                    <th style="font-size: .8em"></th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="pagos_autoriza_dg_reembolsos">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="box">
                                            <h4>&nbsp;PAGOS AUTORIZADOS <i class="far fa-question-circle faq" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" title="Autorizaciones" data-content="Pagos terminados" data-placement="right"></i><label>&nbsp;Total: $<input style="border-bottom: none; border-top: none; border-right: none;  border-left: none; background: white;" disabled="disabled" readonly="readonly" type="text" name="totalReembolsoTxt" id="totalReembolsoTxt"></label></h4>
                                            <hr>
                                            <table class="table table-responsive table-bordered table-striped table-hover" id="tabla_reembolsos" name="tabla_reembolsos">
                                                <thead>
                                                    <tr>
                                                        <th></th>
                                                        <th style="font-size: .9em">RESPONSABLE</th>
                                                        <th style="font-size: .9em">EMPRESA</th>
                                                        <th style="font-size: .9em">FECHA FACTURA</th>
                                                        <th style="font-size: .9em">CANTIDAD</th>
                                                        <th style="font-size: .9em">DEPARTAMENTO</th>
                                                        <th style="font-size: .8em">MÉTODO INGRESADO</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>

                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div> <!--End solicitudes finalizadas-->
                            <div class="tab-pane fade" id="pagos_autoriza_dg_viaticos">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="box">
                                            <h4>&nbsp;PAGOS AUTORIZADOS <i class="far fa-question-circle faq" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" title="Autorizaciones" data-content="Pagos terminados" data-placement="right"></i><label>&nbsp;Total: $<input style="border-bottom: none; border-top: none; border-right: none;  border-left: none; background: white;" disabled="disabled" readonly="readonly" type="text" name="totalViaticoTxt" id="totalViaticoTxt"></label></h4>
                                            <hr>
                                            <table class="table table-responsive table-bordered table-striped table-hover" id="tabla_viaticos" name="tabla_viaticos">
                                                <thead>
                                                    <tr>
													 <th></th> <!-- 0 -->
													 <th style="font-size: .8em">RESPONSABLE</th> <!-- 1 -->
													 <th style="font-size: .8em">PERTENECE REEMBOLSO</th>   <!-- COLUMNA[ 2 ] EXPORTAR CCH    --> <!-- INICIO FECHA: 12-JUNIO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
													 <th style="font-size: .8em">EMPRESA</th> <!-- 3 -->
													 <th style="font-size: .8em">FECHA FACTURA</th> <!-- 4 -->
													 <th style="font-size: .8em">CANTIDAD</th> <!-- 5 -->
													 <th style="font-size: .8em">DEPARTAMENTO</th> <!-- 6 -->
													 <th style="font-size: .8em">MÉTODO INGRESADO</th> <!-- 7 -->
													 <th></th> <!-- 8 -->
                                                    </tr>
                                                </thead>

                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div> <!--End solicitudes finalizadas-->
                        </div>
                    </div><!--End tab content-->
                </div><!--end box-->
            </div>
        </div>
    </div>
</div>

<!--MODAL PARA SACAR LOS PAGOS PROGRAMADOS-->
<div class="modal fade" id="myModal2" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4>GENERAR DOCUMENTOS DE TRANSFERENCIAS<h4>
            </div>
            <div class="modal-body text-center">
                <form id="fpagos_transferencias">
                    <div class="row">
                        <div class="col-lg-4 form-group">
                            <h5 class="modal-title">EMPRESA</h5>
                            <div class="input-group">
                                <select id="empresa_valor" name="empresa_valor" class=" form-control lista_empresa" data-value="cuenta_valor" required></select>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <h5 class="modal-title">CUENTA</h5>
                            <div class="input-group">
                                <select id="cuenta_valor" name="cuenta_valor" class="form-control lista_cuenta" required>
                                    <option value="">- Selecciona cuenta - </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4 form-group">
                            <h5 class="modal-title">DEPARTAMENTO</h5>
                            <div class="input-group">
                                <select id="depar" name="depar" class="form-control depar">
                                    <option value="">TODOS</option>
                                    <option value="1">CONSTRUCCIÓN</option>
                                    <option value="2">OTROS</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4  col-lg-offset-4 form-group">
                            <div class="input-group-btn">
                                <button type="submit" class="btn btn-info" id="generardor_txt"><span class="glyphicon glyphicon-file"></span>CREAR DOCUMENTO</button>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="row">
                    <div class="col-lg-12">
                        <a href="#" id="descargaPT" class="hidden" download></a>
                        <div id="resban"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>






<div class="modal fade" id="myModal2_caja_chica" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4>GENERAR DOCUMENTOS TXT CAJA CHICA<h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">

                        <div class="col-lg-4">
                            <center>
                                <h5 class="modal-title">MÉTODO PAGO</h5>
                                <div class="input-group">
                                    <select id="metodo_valor_ch" name="metodo_valor_ch" class=" form-control lista_metodos">
                                        <option value=""> - Selecciona opción - </option>
                                        <option>TEA</option>
                                        <option>ECHQ</option>
                                        <option>MAN</option>
                                    </select>
                                </div>
                            </center><br>
                        </div>

                        <div class="col-lg-4">
                            <center>
                                <h5 class="modal-title">EMPRESA</h5>
                                <div class="input-group">
                                    <select id="empresa_valor_ch" name="empresa_valor_ch" class=" form-control lista_empresa"></select>
                                </div>
                            </center><br>
                        </div>

                        <div class="col-lg-4">
                            <center>
                                <h5 class="modal-title">CUENTA</h5>
                                <div class="input-group">
                                    <select style="width: 100%;" id="cuenta_valor_2ch" name="cuenta_valor_2ch" class=" form-control lista_cuenta_ch" required>
                                        <option value="">- Selecciona cuenta - </option>
                                    </select>
                                </div>
                            </center><br>
                        </div>
                    </div>

                    <script type="text/javascript">
                        $('select#empresa_valor_ch').on('change', function() {
                            $(".lista_cuenta_ch").html("");
                            var valor = $(this).val();

                            $.getJSON(url + "Listas_select/lista_cuentas" + "/" + valor).done(function(data) {
                                $.each(data, function(i, v) {
                                    $(".lista_cuenta_ch").append('<option value="' + v.idcuenta + '" data-value="' + v.idcuenta + '">' + v.nombre + " - " + v.nodecta + " - " + v.nombanco + '</option>');
                                });
                            });

                        });
                    </script>
                    <div class="col-lg-4">

                        <br>
                        <div class="input-group-btn">
                            <button class="btn btn-info" onclick="btn_caja_chica()"><span class="glyphicon glyphicon-file"></span> Crear archivo</button>
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <a href="#" id="descargaPTCH" class="hidden" download></a><a href="#" id="descargaPTCH2" class="hidden" download></a>
                        <div id="resban_caja_chica"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="myModal2_reembolsos" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4>GENERAR DOCUMENTOS TXT CAJA CHICA<h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">

                        <div class="col-lg-4">
                            <center>
                                <h5 class="modal-title">MÉTODO PAGO</h5>
                                <div class="input-group">
                                    <select id="metodo_valor_reem" name="metodo_valor_reem" class=" form-control lista_metodos">
                                        <option value=""> - Selecciona opción - </option>
                                        <option>TEA</option>
                                        <option>ECHQ</option>
                                        <option>MAN</option>
                                    </select>
                                </div>
                            </center><br>
                        </div>

                        <div class="col-lg-4">
                            <center>
                                <h5 class="modal-title">EMPRESA</h5>
                                <div class="input-group">
                                    <select id="empresa_valor_reem" name="empresa_valor_reem" class=" form-control lista_empresa"></select>
                                </div>
                            </center><br>
                        </div>

                        <div class="col-lg-4">
                            <center>
                                <h5 class="modal-title">CUENTA</h5>
                                <div class="input-group">
                                    <select style="width: 100%;" id="cuenta_valor_2reem" name="cuenta_valor_2reem" class=" form-control lista_cuenta_reem" required>
                                        <option value="">- Selecciona cuenta - </option>
                                    </select>
                                </div>
                            </center><br>
                        </div>
                    </div>

                    <script type="text/javascript">
                        $('select#empresa_valor_ch').on('change', function() {
                            $(".empresa_valor_reem").html("");
                            var valor = $(this).val();

                            $.getJSON(url + "Listas_select/lista_cuentas" + "/" + valor).done(function(data) {
                                $.each(data, function(i, v) {
                                    $(".lista_cuenta_reem").append('<option value="' + v.idcuenta + '" data-value="' + v.idcuenta + '">' + v.nombre + " - " + v.nodecta + " - " + v.nombanco + '</option>');
                                });
                            });

                        });
                    </script>
                    <div class="col-lg-4">

                        <br>
                        <div class="input-group-btn">
                            <button class="btn btn-info" onclick="btn_reembolsos()"><span class="glyphicon glyphicon-file"></span> Crear archivo</button>
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <a href="#" id="descargaPTREEM" class="hidden" download></a><a href="#" id="descargaPTREEM2" class="hidden" download></a>
                        <div id="resban_reem"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>





<!--MODAL PARA LOS PAGOS PROGRAMADOS-->
<div class="modal fade" id="myModal2_progamados" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4>GENERAR DOCUMENTOS TXT PROGRAMADOS<h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <form id="fpagos_programados">
                        <div class="col-lg-4 form-group">
                            <center>
                                <h5 class="modal-title">SELECCIONA MÉTODO</h5>
                                <div class="input-group">
                                    <select id="metodo_valor_prog" name="metodo_valor_prog" class=" form-control lista_metodos" required>
                                        <option value=""> - Selecciona opción - </option>
                                        <option>TEA</option>
                                        <option>ECHQ/EFEC</option>
                                        <option>DOMIC</option>
                                    </select>
                                </div>
                            </center>
                        </div>
                        <div class="col-lg-4 form-group">
                            <center>
                                <h5 class="modal-title">SELECCIONA EMPRESA</h5>
                                <div class="input-group">
                                    <select id="empresa_valor_prog" name="empresa_valor_prog" class=" form-control lista_empresa" required></select>
                                </div>
                            </center><br>
                        </div>
                        <div class="col-lg-4 form-group">
                            <center>
                                <h5 class="modal-title">SELECCIONA CUENTA</h5>
                                <div class="input-group">
                                    <select id="cuenta_valor_prog" name="cuenta_valor_prog" class=" form-control lista_cuenta_ch" required>
                                        <option value="">- Selecciona cuenta - </option>
                                    </select>
                                </div>
                            </center><br>
                        </div>
                        <script type="text/javascript">
                            $('select#empresa_valor_prog').on('change', function() {
                                $(".lista_cuenta_ch").html("");
                                var valor = $(this).val();
                                $.getJSON(url + "Listas_select/lista_cuentas" + "/" + valor).done(function(data) {
                                    $.each(data, function(i, v) {
                                        $(".lista_cuenta_ch").append('<option value="' + v.idcuenta + '" data-value="' + v.idcuenta + '">' + v.nombre + " - " + v.nodecta + " - " + v.nombanco + '</option>');
                                    });
                                });
                            });
                        </script>
                        <div class="col-lg-4 col-lg-offset-4 form-group">
                            <div class="input-group-btn">
                                <button type="submit" class="btn btn-block btn-info"><span class="glyphicon glyphicon-file"></span> CREAR ARCHIVO</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <a href="#" id="descargaProg" class="hidden" download></a><a href="#" id="descargaProg" class="hidden" download></a>
                        <div id="resban_program" class="text-center"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




<div class="modal fade modal-alertas" id="myModalpoliza" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: #00A65A; color: #fff;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">CONFIRMAR PAGO</h4>
            </div>
            <form method="post" id="infopago_polizad">
                <div class="modal-body"></div>
                <div class="modal-footer"></div>
            </form>
        </div>
    </div>
</div>





<div class="modal fade modal-alertas" id="myModalpoliza_chica" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: #00A65A; color: #fff;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">CONFIRMAR PAGO</h4>
            </div>
            <form method="post" id="infopago_chica">
                <div class="modal-body"></div>
                <div class="modal-footer"></div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade modal-alertas" id="modalCaja" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: #00A65A; color: #fff;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">REGISTRAR DATOS DE PAGO</h4>
            </div>
            <form method="post" id="infopago_chica_1">
                <div class="modal-body"></div>
                <div class="modal-footer"></div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade modal-alertas" id="myModalpoliza_ch" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: #00A65A; color: #fff;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">CONFIRMAR PAGO</h4>
            </div>
            <form method="post" id="infopago_ch">
                <div class="modal-body"></div>
                <div class="modal-footer"></div>
            </form>
        </div>
    </div>
</div>



<div class="modal fade modal-alertas" id="myModalAceptar" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: #00A65A; color: #fff;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">SE HA ACEPTADO EL PAGO</h4>
            </div>
            <form method="post" id="form_aceptar">
                <div class="modal-body">El pago a pasado a proceso</div>
                <div class="modal-footer"></div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade modal-alertas" id="myModalcomentario1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: #DD4B39; color: #fff;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Mótivo de rechazo</h4>
            </div>
            <form method="post" id="infosol1">
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade modal-alertas" id="myModalcomentario3" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: #DD4B39; color: #fff;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">MOTIVO DE RECHAZO</h4>
            </div>
            <form method="post" id="infosol22">
                <div class="modal-body"></div>
                <div class="modal-footer"></div>
            </form>
        </div>
    </div>
</div>



<div class="modal fade modal-alertas" id="myModalcomentario3_ch" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: #DD4B39; color: #fff;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">MOTIVO DE RECHAZO</h4>
            </div>
            <form method="post" id="infosol22_ch">
                <div class="modal-body"></div>
                <div class="modal-footer"></div>
            </form>
        </div>
    </div>
</div>




<div class="modal fade" id="myModal22" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4>GENERAR TXT CHEQUES<h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">

                        <div class="col-lg-12">
                            <center>
                                <h5 class="modal-title">SELECCIONA EMPRESA</h5>
                                <div class="input-group">
                                    <select id="empresa_valor_ot" name="empresa_valor_ot" class=" form-control lista_empresa"></select>
                                </div>
                            </center><br>
                        </div>
                    </div>


                    <div class="col-lg-4">

                        <br>
                        <div class="input-group-btn">
                            <button class="btn btn-info" onclick="btn_otros()"><span class="glyphicon glyphicon-file"></span> Crear archivo</button>
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <a href="#" id="descargaotros" class="hidden" download></a>
                        <div id="resban_otros"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade modal-alertas" id="myModalMas" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-maroon" style="color: #fff;">

            </div>
            <form method="post" id="form_mixto">
                <div class="modal-body"></div>
                <div class="modal-footer"></div>
            </form>
        </div>
    </div>
</div>








<div class="modal fade modal-alertas" id="myModalEspera" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: #F39C12; color: #fff;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">MOTIVO DE ESPERA</h4>
            </div>
            <form method="post" id="form_espera_uno">
                <div class="modal-body"></div>
                <div class="modal-footer"></div>
            </form>
        </div>
    </div>
</div>




<div class="modal fade modal-alertas" id="myModalEspera4" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: #DD4B39; color: #fff;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">MOTIVO DE ESPERA</h4>
            </div>
            <form method="post" id="form_espera_cuatro">
                <div class="modal-body"></div>
                <div class="modal-footer"></div>
            </form>
        </div>
    </div>
</div>



<div class="modal fade modal-alertas" id="myModalEspera2" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: #DD4B39; color: #fff;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">MOTIVO DE ESPERA</h4>
            </div>
            <form method="post" id="form_espera_dos">
                <div class="modal-body"></div>
                <div class="modal-footer"></div>
            </form>
        </div>
    </div>
</div>



<div class="modal fade modal-alertas" id="myModalEspera_chica" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: #F39C12; color: #fff;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">MOTIVO DE ESPERA</h4>
            </div>
            <form method="post" id="form_espera_cch">
                <div class="modal-body"></div>
                <div class="modal-footer"></div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade modal-alertas" id="myModalEspera_via" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: #F39C12; color: #fff;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">MOTIVO DE ESPERA</h4>
            </div>
            <form method="post" id="form_espera_via">
                <div class="modal-body"></div>
                <div class="modal-footer"></div>
            </form>
        </div>
    </div>
</div>



<div class="modal fade modal-alertas" id="myModalcambioTEA" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: #3C8DBC; color: #fff;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">CAMBIAR METODO DE PAGO</h4>
            </div>
            <form method="post" id="form_cambia_tea">
                <div class="modal-body"></div>
                <div class="modal-footer"></div>
            </form>
        </div>
    </div>
</div>




<div class="modal fade modal-alertas" id="myModalcambiOTRO" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: #3C8DBC; color: #fff;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">CAMBIAR MÉTODO DE PAGO</h4>
            </div>
            <form method="post" id="form_cambia_OTRO">
                <div class="modal-body"></div>
                <div class="modal-footer"></div>
            </form>
        </div>
    </div>
</div>




<div class="modal fade modal-alertas" id="myModalcamb_fact" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: #3C8DBC; color: #fff;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">CAMBIAR MÉTODO DE PAGO</h4>
            </div>
            <form method="post" id="form_cambiaFACTO">
                <div class="modal-body"></div>
                <div class="modal-footer"></div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade modal-alertas" id="myModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: #00A65A;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">CONFIRMAR SOLICITUD</h4>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>
<div class="modal fade modal-alertas" id="myModal_chica_all" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: #00A65A; color: #fff;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">¡PAGO PROCESADO!</h4>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>
<div class="modal fade modal-alertas " id="myModaltxt" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body"></div>
        </div>
    </div>
</div>
<div class="modal fade modal-alertas" id="myModalregresar" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: #DD4B39;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Regresar solicitud</h4>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>

<div class="modal fade modal-alertas" id="modal_tipo_cambio" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-olive">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">CAMBIO DE MONEDA</h4>
            </div>
            <form method="post" id="form_cambio">
                <div class="modal-body"></div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade modal-alertas" id="modal_intereses" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-red">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">INTÉRES AGREGADO</h4>
            </div>
            <form method="post" id="form_interes">
                <div class="modal-body"></div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade modal-alertas" id="modal_intereses_ot" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-red">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">INTÉRES AGREGADO</h4>
            </div>
            <form method="post" id="form_interes_ot">
                <div class="modal-body"></div>
            </form>
        </div>
    </div>
</div>



<div class="modal fade modal-alertas" id="modal_tipo_cambio2" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-olive">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">CAMBIO DE MONEDA</h4>
            </div>
            <form method="post" id="form_cambio2">
                <div class="modal-body"></div>
            </form>
        </div>
    </div>
</div>

<form>
    <div class="modal fade modal-alertas" id="myModalx" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background: #DD4B39;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Declinar solicitud</h4>
                </div>
                <div class="modal-body"></div>
                <div class="modal-footer"></div>
            </div>
        </div>
    </div>
</form>









<div class="modal fade" id="myModal3" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4>GENERAR DOCUMENTO TXT INDIVIDUAL<h4>
            </div>
            <div class="modal-body">

                <div class="col-lg-12">
                    <center>
                        <h5 class="modal-title">SELECCIONA CUENTA</h5>
                        <div class="input-group">
                            <select style="width: 100%;" id="cuenta_valor_ind" name="cuenta_valor_ind" class="form-control lista_cuentas_ind" required>
                            </select>
                        </div>
                    </center><br>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div id="resbane"></div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<div class="modal fade modal-alertas" id="myModalconsecutivo" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4>DATOS CHEQUE / EFECTIVO<h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-10 col-lg-offset-1 input-group">
                        <h5 class="modal-title">NUMERO DE SERIE</h5>
                        <input type="text" name="serie_cheque" id="serie_cheque" class="form-control">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class='btn-group'><button type='button' class='btn btn-success' onclick='acepta_cheque()'>ACEPTAR</button><button type='button' class='btn btn-danger' onclick='cancelarr_cheque()'>CANCELAR</button></div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade modal-alertas" id="myModalconsecutivo_chica" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4>FORMA DE PAGO<h4>
            </div>
            <div class="modal-body">
                <form id="forma_pago_caja_chica">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group col-lg-12">
                                <label class="modal-title">FORMA DE PAGO</label>
                                <select id="tipoPago_chica" name="tipoPago_chica" class="form-control" required>
                                    <option value="" selected=""> -SELECCIONA OPCIÓN- </option>
                                    <option value="ECHQ">CHEQUE</option>
                                    <option value="TEA">TRANSFERENCIA</option>
                                    <option value="EFEC">EFECTIVO</option>
                                    <option value="MAN">MAN</option>
                                </select>
                            </div>
                            <hr />
                            <div class="form-group col-lg-12">
                                <input type="text" name="serie_cheque_ch" id="serie_cheque_ch" class="form-control" placeholder="Referencia / No Cheque" required>
                            </div>
                            <div class="form-group col-lg-12">
                                <select id='idcuenta' name='idcuenta' class=" form-control lista_prov_ch" required>
                                    <option value="" selected=""> -SELECCIONA OPCIÓN- </option>
                                </select>
                            </div>
                        </div>
                        <hr />
                        <div class="form-group col-lg-12">
                            <div class='btn-group'>
                                <button type='submit' class='btn btn-success'>ACEPTAR</button>
                                <button type='button' class='btn btn-danger' class="close" data-dismiss="modal">CANCELAR</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Confirmacion  -->
<div class="modal fade" id="confirm" role="dialog" style="z-index:1100;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close cls_confirm" data-dismiss="modal" aria-label="Close">&times;</button>
                <h4 class="modal-title" id="confirmTittle">ADVERTENCIA!</h4>
            </div>
            <div class="modal-body confirm-body" id="confirm-body" style="width: 430px;">
                <h4>
                    <p>Al realizar esta acción no podrá revertirla.</p>
                </h4>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger clse" data-dismiss="modal"><i class="fas fa-times"></i> CERRAR</button>
                <button class="btn btn-success" onclick="cerrar_caja()">ACEPTAR</button>
            </div>
        </div>
    </div>
</div>


<!-- Modal para Confirmacion  -->
<div class="modal fade" id="confirmReembolsos" role="dialog" style="z-index:1100;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close cls_confirm" data-dismiss="modal" aria-label="Close">&times;</button>
                <h4 class="modal-title" id="confirmTittle">ADVERTENCIA!</h4>
            </div>
            <div class="modal-body confirm-body" id="confirm-body" style="width: 430px;">
                <h4>
                    <p>Al realizar esta acción no podrá revertirla.</p>
                </h4>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger clse" data-dismiss="modal"><i class="fas fa-times"></i> CERRAR</button>
                <button class="btn btn-success" onclick="cerrar_reembolsos()">ACEPTAR</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="confirmViaticos" role="dialog" style="z-index:1100;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close cls_confirm" data-dismiss="modal" aria-label="Close">&times;</button>
                <h4 class="modal-title" id="confirmTittle">ADVERTENCIA!</h4>
            </div>
            <div class="modal-body confirm-body" id="confirm-body" style="width: 430px;">
                <h4>
                    <p>Al realizar esta acción no podrá revertirla.</p>
                </h4>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger clse" data-dismiss="modal"><i class="fas fa-times"></i> CERRAR</button>
                <button class="btn btn-success" onclick="cerrar_viaticos()">ACEPTAR</button>
            </div>
        </div>
    </div>
</div>



<!--modal reembolso-->
<div class="modal fade modal-alertas" id="modalReembolsoConsecutivo" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4>FORMA DE PAGO<h4>
            </div>
            <div class="modal-body">
                <form id="forma_pago_reembolso">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group col-lg-12">
                                <label class="modal-title">FORMA DE PAGO</label>
                                <select id="tipoPago_reem" name="tipoPago_reem" class="form-control" required>
                                    <option value="" selected=""> -SELECCIONA OPCIÓN- </option>
                                    <option value="ECHQ">CHEQUE</option>
                                    <option value="TEA">TRANSFERENCIA</option>
                                    <option value="EFEC">EFECTIVO</option>
                                    <option value="MAN">MAN</option>
                                </select>
                            </div>
                            <hr />
                            <div class="form-group col-lg-12">
                                <input type="text" name="serie_cheque_reem" id="serie_cheque_reem" class="form-control" placeholder="Referencia / No Cheque" required>
                            </div>
                            <div class="form-group col-lg-12">
                                <select id='idcuentaReem' name='idcuentaReem' class=" form-control lista_prov_ch" required>
                                    <option value="" selected=""> -SELECCIONA OPCIÓN- </option>
                                </select>
                            </div>
                        </div>
                        <hr />
                        <div class="form-group col-lg-12">
                            <div class='btn-group'>
                                <button type='submit' class='btn btn-success'>ACEPTAR</button>
                                <button type='button' class='btn btn-danger' class="close" data-dismiss="modal">CANCELAR</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<!--modal viaticos-->
<div class="modal fade modal-alertas" id="modalViaticos" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4>FORMA DE PAGO<h4>
            </div>
            <div class="modal-body">
                <form id="forma_pago_viaticos">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group col-lg-12">
                                <label class="modal-title">FORMA DE PAGO</label>
                                <select id="tipoPago_via" name="tipoPago_via" class="form-control" required>
                                    <option value="" selected=""> -SELECCIONA OPCIÓN- </option>
                                    <option value="ECHQ">CHEQUE</option>
                                    <option value="TEA">TRANSFERENCIA</option>
                                    <option value="EFEC">EFECTIVO</option>
                                    <option value="MAN">MAN</option>
                                </select>
                            </div>
                            <hr />
                            <div class="form-group col-lg-12">
                                <input type="text" name="serie_cheque_via" id="serie_cheque_via" class="form-control" placeholder="Referencia / No Cheque" required>
                            </div>
                            <div class="form-group col-lg-12">
                                <select id='idcuentaVia' name='idcuentaVia' class=" form-control lista_prov_ch_viaticos" required>
                                    <option value="" selected=""> -SELECCIONA OPCIÓN- </option>
                                </select>
                            </div>
                        </div>
                        <hr />
                        <div class="form-group col-lg-12">
                            <div class='btn-group'>
                                <button type='submit' class='btn btn-success'>ACEPTAR</button>
                                <button type='button' class='btn btn-danger' class="close" data-dismiss="modal">CANCELAR</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- MODAL PARA LA CREACION DE TXT VIATICOS -->
<div class="modal fade" id="modal_viaticos_txt" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4>GENERAR DOCUMENTOS TXT VIÁTICOS<h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">

                        <div class="col-lg-4">
                            <center>
                                <h5 class="modal-title">MÉTODO PAGO</h5>
                                <div class="input-group">
                                    <select id="metodo_valor_viaticos" name="metodo_valor_viaticos" class=" form-control lista_metodos">
                                        <option value=""> - Selecciona opción - </option>
                                        <option>TEA</option>
                                        <option>ECHQ</option>
                                        <option>MAN</option>
                                    </select>
                                </div>
                            </center><br>
                        </div>

                        <div class="col-lg-4">
                            <center>
                                <h5 class="modal-title">EMPRESA</h5>
                                <div class="input-group">
                                    <select id="empresa_valor_viaticos" name="empresa_valor_viaticos" class=" form-control lista_empresa"></select>
                                </div>
                            </center><br>
                        </div>

                        <div class="col-lg-4">
                            <center>
                                <h5 class="modal-title">CUENTA</h5>
                                <div class="input-group">
                                    <select style="width: 100%;" id="cuenta_valor_viaticos" name="cuenta_valor_viaticos" class=" form-control lista_cuenta_viaticos" required>
                                        <option value="">- Selecciona cuenta - </option>
                                    </select>
                                </div>
                            </center><br>
                        </div>
                    </div>

                    <script type="text/javascript">
                        $('select#empresa_valor_viaticos').on('change', function() {
                            $(".lista_cuenta_viaticos").html("");
                            var valor = $(this).val();

                            $.getJSON(url + "Listas_select/lista_cuentas" + "/" + valor).done(function(data) {
                                $.each(data, function(i, v) {
                                    $(".lista_cuenta_viaticos").append('<option value="' + v.idcuenta + '" data-value="' + v.idcuenta + '">' + v.nombre + " - " + v.nodecta + " - " + v.nombanco + '</option>');
                                });
                            });

                        });
                    </script>
                    <div class="col-lg-4">

                        <br>
                        <div class="input-group-btn">
                            <button class="btn btn-info" onclick="btn_viaticos()"><span class="glyphicon glyphicon-file"></span> Crear archivo</button>
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <a href="#" id="descargaPTCH" class="hidden" download></a><a href="#" id="descargaPTCH2" class="hidden" download></a>
                        <div id="resban_viaticos"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var tabla_provison_pago;
    var activeTab = "pagos_autoriza_dg_trans";
    var no_espera = ['TRASPASO', 'DEVOLUCIONES', 'CESION OOAM', 'RESCISION OOAM', 'DEVOLUCION OOAM', 'TRASPASO OOAM', 'DEVOLUCION DOM OOAM', 'INFORMATIVA', 'INFORMATIVA CERO'];
    $(document).ready(function() {
        $('.faq').popover();

        $('.lista_empresa').on('change', function() {
            if ($(this).attr("data-value")) {
                var elemento = $(this).data("value");

                $("#" + elemento).html('<option value="">Seleccione una opción</option>');
                $.getJSON(url + "Listas_select/lista_cuentas" + "/" + $(this).val()).done(function(data) {
                    $.each(data, function(i, v) {
                        $("#" + elemento).append('<option value="' + v.idcuenta + '" data-value="' + v.idcuenta + '">' + v.nombre + " - " + v.nodecta + " - " + v.nombanco + '</option>');
                    });
                });
            }
        });

    });

    $('[data-toggle="tab"]').click(function(e) {
        activeTab = $(this).data('value');
        switch ($(this).data('value')) {
            case 'pagos_autoriza_dg_trans':
                tabla_transferencias.ajax.url(url + "Cuentasxp/ver_datos_autdg").load();
                break;
            case 'pagos_autoriza_dg_cheques':
                tabla_otros.ajax.url(url + "Cuentasxp/ver_datos_autdg_otros").load();
                break;
            case 'pagos_autoriza_dg_chica':
                tabla_caja_chica.ajax.url(url + "Cuentasxp/tablaSolCaja").load();
                tabla_caja_chica.column(2).visible(true); /** FECHA: 29-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                break;
            case 'pagos_facturaje':
                tabla_facturaje.ajax.url(url + "Cuentasxp/ver_datos_autdgfact").load();
                break;
            case 'pagos_programados':
                tabla_aut_programados.ajax.url(url + "Cuentasxp/ver_datos_autdg_prgo").load();
                break;
            case 'pagos_pausados':
                tabla_pausados.ajax.url(url + "Cuentasxp/ver_datos_pausados").load();
                break;
            case 'pagos_autoriza_dg_reembolsos':
                tabla_reembolsos.ajax.url(url + "Cuentasxp/tablaSolReem").load();
                break;
            case 'pagos_autoriza_dg_viaticos':
                tabla_viaticos.ajax.url(url + "Cuentasxp/tablaSolViaticos").load();
                break;
            case 'pagos_autoriza_dg_tdc':
                tabla_caja_chica.ajax.url(url + "Cuentasxp/tablaTDC").load();
                tabla_caja_chica.column(2).visible(false); /** FECHA: 29-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                break;
        }
    });
    
    // TABLA DOS_____________________________________________
    $("#tabla_autoriza_dg_tranferencias").ready(function() {

        $('#tabla_autoriza_dg_tranferencias thead tr:eq(0) th').each(function(i) {

            if (i != 0 && i != 1 && i != 10) {
                var title = $(this).text();
                $(this).html('<input type="text" style="width:100%;" placeholder="' + title + '"/>');
                $('input', this).on('keyup change', function() {
                    if (tabla_transferencias.column(i).search() !== this.value) {
                        tabla_transferencias
                            .column(i)
                            .search(this.value)
                            .draw();

                        var total = 0;
                        var index = tabla_transferencias.rows({
                            selected: true,
                            search: 'applied'
                        }).indexes();
                        var data = tabla_transferencias.rows(index).data();

                        $.each(data, function(i, v) {
                            total += parseFloat(v.autorizado);
                        });

                        var to1 = formatMoney(total);
                        document.getElementById("myText2").value = to1;
                    }
                });
            }
        });

        $('#tabla_autoriza_dg_tranferencias').on('xhr.dt', function(e, settings, json, xhr) {

            var total = 0;

            $.each(json.data, function(i, v) {
                total += parseFloat(v.autorizado);
            });

            var to = formatMoney(total);
            document.getElementById("myText2").value = formatMoney(total);

        });

        tabla_transferencias = $("#tabla_autoriza_dg_tranferencias").DataTable({
            dom: 'Brtip',
            width: 'auto',
            "buttons": [{
                    text: '<i class="fas fa-file-invoice"></i> GENERAR DOCUMENTO BANCO',
                    action: function() {

                        $("#myModal2").modal();
                        $("#myModal2 #resban").html("");
                        $("#myModal2 .form-control").val("");

                    },
                    attr: {
                        class: 'btn bg-teal',
                        style: 'margin-right: 30px;'
                    }
                },
                {
                    extend: 'excelHtml5',
                    text: '<i class="fas fa-file-excel"></i> EXPORTAR EXCEL',
                    messageTop: "Lista de pagos autorizados por Dirección General (TRANSFERENCIAS)",
                    attr: {
                        class: 'btn bg-green',
                        style: 'margin-right: 30px;'
                    },
                    exportOptions: {
                        columns: [2, 3, 4, 5, 6, 7, 8],
                        format: {
                            header: function(data, columnIdx) {
                                data = data.replace('<input type="text" style="width:100%;" placeholder="', '');
                                data = data.replace('">', '')
                                return data;
                            }
                        }
                    }
                },
                {
                    text: '<i class="fa fa-check"></i> ACEPTAR PAGOS',
                    action: function() {

                        if ($('input[name="idT[]"]:checked').length > 0) {

                            var idpago = $(tabla_transferencias.$('input[name="idT[]"]:checked')).map(function() {
                                return this.value;
                            }).get();

                            $.get(url + "Cuentasxp/aceptocpp/" + idpago).done(function() {
                                $("#myModaltxt").modal('toggle');
                                click_tab();
                                $("#myModaltxt .modal-body").html("");
                                $("#myModaltxt").modal();
                                $("#myModaltxt .modal-body").append("<center><img style='width: 40%; height: 40%;' src= '<?= base_url('img/ver.gif') ?>'><br><b><P>ENVIADO(S) A DISPERSIÓN</P></b></center>");
                            });
                        }
                    },
                    attr: {
                        class: 'btn bg-navy',
                    }

                },
            ],
            "language": lenguaje,
            "processing": false,
            "pageLength": 50,
            "bAutoWidth": false,
            "bLengthChange": false,
            "scrollX": true,
            "bInfo": false,
            "searching": true,
            "ordering": false,
            "fixedColumns": true,
            "ordering": false,
            "columns": [{
                    "width": "1%", /** FECHA: 29-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                    "className": 'details-control',
                    "data": null,
                },
                {
                    "width": "5%"
                },
                {
                    "width": "15%",
                    "data": function(d) {
                        return '' + d.idsolicitud;
                    }
                },
                {
                    "width": "10%",
                    "data": function(d) {
                        var p = d.folio ? d.folio : 'SF';
                        return p + (d.prioridad == 1 ? "<br><small class='label pull-center bg-red'>URGENTE</small>" : "");

                    }
                },
                {
                    "width": "15%",
                    "data": function(d) {
                        return '<p style="font-size: .9em">' + d.nombre + '</p>';
                    }
                },
                {
                    "width": "12%",
                    "data": function(d) {
                        return '<p style="font-size: .9em">$ ' + formatMoney(d.autorizado) + ' ' + d.moneda + '</p>';
                    }
                },
                {
                    "width": "10%",
                    "data": function(d) {
                        return '<p style="font-size: .9em">$ ' + formatMoney((d.tipoCambio ? d.tipoCambio * d.autorizado : d.autorizado)) + " " + (d.tipoCambio ? 'MXN' : d.moneda) + '</p>';
                    }
                },
                {
                    "width": "10%",
                    "data": function(d) {
                        return '<p style="font-size: .9em">' + d.fecelab + '</p>';
                    }
                },
                {
                    "width": "10%",
                    "data": function(d) {
                        return '<p style="font-size: .9em">' + d.nomdepto + '</p>';
                    }
                },
                {
                    "width": "10%",
                    "data": function(d) {
                        return '<p style="font-size: .9em">' + d.nemp + '</p>';
                    }
                },
                {
                    "orderable": false,
                    "data": function(data) {
                        //if( data.nomdepto == 'TRASPASO' || data.nomdepto == 'DEVOLUCIONES' )
                        etiqueta = "";
                        opciones = '<div class="btn-group-vertical" role="group">';

                        opciones += '<button type="button" class="btn btn-primary consultar_modal notification" value="' + data.idsolicitud + '" data-value="' + (no_espera.includes(data.nomdepto) ? "DEV_BASICA" : "SOL") + '" title="Ver Solicitud"><i class="fas fa-eye"></i></button>';

                        if (data.estatus == '11') {
                            etiqueta = '<small class="label pull-right bg-navy">Listo para aceptar pago</small>';
                            opciones += '<button type="button" class="txt_ind btn bg-teal btn-sm"  data-pago="' + data.idpago + '" data-empresa="' + data.idEmpresa + '" style="margin-right: 5px;" title="Descargar TXT Individual"><i class="fa fa-download"></i></button>';
                        }
                        opciones += '<button type="button" class="btn btn-warning btn-sm" onClick="mandar_espera(' + data.idpago + ')" title="Mandar a espera" style="margin-right: 5px;"><i class="fas fa-clock"></i></button>';

                        if (data.moneda != 'MXN') {
                            opciones += '<button type="button" style="margin-right: 5px;" class="btn btn-sm cargar_cambio bg-olive" value="' + data.idpago + '" title="Ingresar interés"><i class="fas fa-money-check-alt"></i></button>';
                        }

                        opciones += '<button type="button" class="btn btn-sm bg-maroon" onClick="mas_opciones(' + data.idpago + ', 1, \'' + data.nomdepto + '\' )" title="Más opciones" style="margin-right: 5px;"><i class="fa fa-plus-circle"></i></button>';
                        return etiqueta + opciones + '</div>';
                    }
                }
            ],
            columnDefs: [{
                    "searchable": false,
                    "orderable": false,
                    "targets": 0
                },
                {

                    orderable: false,
                    className: 'select-checkbox',
                    targets: 1,
                    'searchable': false,
                    'className': 'dt-body-center',
                    'render': function(d, type, full, meta) {

                        if (full.estatus == 11) {
                            return '<input type="checkbox" name="idT[]" style="width:20px;height:20px;"  value="' + full.idpago + '">';
                        } else {
                            return '';
                        }
                    },
                    select: {
                        style: 'os',
                        selector: 'td:first-child'
                    },
                }
            ],
            "ajax": {
                "url": url + "Cuentasxp/ver_datos_autdg",
                "type": "POST",
                cache: false,
                "data": function(d) {}
            },
            "order": [
                [1, 'asc']
            ]
        });

        $('#tabla_autoriza_dg_tranferencias tbody').on('click', 'td.details-control', function() {
            var tr = $(this).closest('tr');
            var row = tabla_transferencias.row(tr);

            if (row.child.isShown()) {
                row.child.hide();
                tr.removeClass('shown');
                $(this).parent().find('.animacion').removeClass("fa-caret-down").addClass("fa-caret-right");
            } else {

                var informacion_adicional = '<table class="table text-justify">' +
                    '<tr>' +
                    '<td style="font-size: .8em"><strong>JUSTIFICACIÓN: </strong>' + row.data().justificacion + '</td>' +
                    '</tr>' +
                    '</table>';

                row.child(informacion_adicional).show();
                tr.addClass('shown');
                $(this).parent().find('.animacion').removeClass("fa-caret-right").addClass("fa-caret-down");
            }
        });

        //NUMERA LAS FILAS DE LA TABLA
        tabla_transferencias.on('search.dt order.dt', function() {
            tabla_transferencias.column(0, {
                search: 'applied',
                order: 'applied'
            }).nodes().each(function(cell, i) {
                cell.innerHTML = i + 1;
            });
        }).draw();


        $("#tabla_autoriza_dg_tranferencias tbody").on("click", ".cancelar_pago_solicitud", function() {
            $.post(url + "Cuentasxp/regresar_pago", {
                idpago: $(this).val()
            }).done(function() {
                click_tab();
            });
        });

        $("#tabla_autoriza_dg_tranferencias tbody").on("click", ".cargar_cambio", function() {

            var tr = $(this).closest('tr');
            var row = tabla_transferencias.row(tr);

            idautopago = $(this).val();

            $("#modal_tipo_cambio .modal-body").html("");
            $("#modal_tipo_cambio .modal-body").append('<div class="row"><div class="col-lg-12"><p><b>MONEDA:</b> ' + row.data().moneda + '</p></div></div>');
            $("#modal_tipo_cambio .modal-body").append('<div class="row"><div class="col-lg-12 form-group"><label>TIPO CAMBIO</label><div class="input-group"><span class="input-group-addon"><i class="fas fa-dollar-sign"></i></span><input type="number" class="form-control" name="tipo_cambio" required></div></div></div>');
            $("#modal_tipo_cambio .modal-body").append('<div class="row"><div class="col-lg-8 col-lg-offset-2"><button class="btn bg-olive btn-block">EDITAR</button></div></div>');
            $("#modal_tipo_cambio").modal();
        });


        $("#tabla_autoriza_dg_tranferencias tbody").on("click", ".cargar_interes", function() {

            var tr = $(this).closest('tr');
            var row = tabla_transferencias.row(tr);
            row_transferencia = $(this).closest('tr');


            idautopago = $(this).val();

            $("#modal_intereses .modal-body").html("");
            $("#modal_intereses .modal-body").append('<div class="row"><div class="col-lg-12"><p><b>Cantidad:</b> $' + formatMoney(row.data().cantidad) + '</p></div></div>');
            $("#modal_intereses .modal-body").append('<div class="row"><div class="col-lg-12 form-group"><label>INTÉRES</label><div class="input-group"><span class="input-group-addon"><i class="fas fa-dollar-sign"></i></span><input type="number" class="form-control" name="valor_interes" placeholder="Cantidad en $" required></div></div></div>');
            $("#modal_intereses .modal-body").append('<div class="row"><div class="col-lg-8 col-lg-offset-2"><button class="btn bg-green btn-block">ACEPTAR</button></div></div>');
            $("#modal_intereses").modal();
        });
    });
    // FIN TABLA DOS_____________________________________________



    // TABLA TRES ______________________________________________
    $("#tabla_autoriza_dg_otros").ready(function() {

        $('#tabla_autoriza_dg_otros thead tr:eq(0) th').each(function(i) {
            if (i != 0 && i != 1 && i != 11) {
                var title = $(this).text();
                $(this).html('<input type="text" style="width:100%;" placeholder="' + title + '"/>');
                $('input', this).on('keyup change', function() {

                    if (tabla_otros.column(i).search() !== this.value) {
                        tabla_otros
                            .column(i)
                            .search(this.value)
                            .draw();

                        var total = 0;
                        var index = tabla_otros.rows({
                            selected: true,
                            search: 'applied'
                        }).indexes();
                        var data = tabla_otros.rows(index).data();

                        $.each(data, function(i, v) {
                            total += parseFloat(v.autorizado);
                        });
                        var to1 = formatMoney(total);
                        document.getElementById("myText3").value = formatMoney(total);
                    }
                });
            }
        });

        $('#tabla_autoriza_dg_otros').on('xhr.dt', function(e, settings, json, xhr) {
            var total = 0;
            $.each(json.data, function(i, v) {
                total += parseFloat(v.autorizado);
            });
            var to = formatMoney(total);
            document.getElementById("myText3").value = to;
        });

        tabla_otros = $("#tabla_autoriza_dg_otros").DataTable({
            dom: 'Brtip',
            width: 'auto',
            "buttons": [{
                    text: '<i class="fas fa-file-invoice"></i> GENERAR TXT CHEQUES',
                    action: function() {
                        $("#myModal22").modal();
                        $("#myModal22 #resban_otros").html("");
                        $("#myModal22 .form-control").val("");

                    },
                    attr: {
                        class: 'btn bg-teal',
                        style: 'margin-right: 30px;'
                    }
                },
                {
                    extend: 'excelHtml5',
                    text: '<i class="fas fa-file-excel"></i> EXPORTAR EXCEL',
                    messageTop: "Lista de pagos autorizados por Dirección General (OTROS)",
                    attr: {
                        class: 'btn btn-success',
                        style: 'margin-right: 30px;'
                    },
                    exportOptions: {
                        columns: [2, 3, 4, 5, 6, 7, 8, 9, 10],
                        format: {
                            header: function(data, columnIdx) {
                                data = data.replace('<input type="text" style="width:100%;" placeholder="', '');
                                data = data.replace('">', '')
                                return data;
                            }
                        }
                    }
                },
                {
                    text: '<i class="fa fa-check"></i> ACEPTAR PAGOS',
                    action: function() {

                        if ($('input[name="id[]"]:checked').length > 0) {

                            var idpago = $(tabla_otros.$('input[name="id[]"]:checked')).map(function() {
                                return this.value;
                            }).get();

                            $.get(url + "Cuentasxp/aceptocpp_OT/" + idpago).done(function() {
                                $("#myModaltxt").modal('toggle');
                                click_tab();
                                $("#myModaltxt .modal-footer").html("");
                                $("#myModaltxt .modal-body").html("");
                                $("#myModaltxt").modal();
                                $("#myModaltxt .modal-body").append("<center><label>Pago(s) pasado(s) a proceso</label></center>");
                            });

                        }
                    },
                    attr: {
                        class: 'btn bg-navy',
                    }
                }
            ],
            "language": lenguaje,
            "processing": false,
            "pageLength": 50,
            "bAutoWidth": false,
            "bLengthChange": false,
            "scrollX": true,
            "bInfo": false,
            "searching": true,
            "ordering": false,
            "fixedColumns": true,
            "columns": [{
                    "orderable": false,
                    "className": 'details-control',
                    "orderable": false,
                    "data": null,
                },
                {
                    "width": "4%"
                },
                {
                    "width": "10%",
                    "data": function(d) {
                        return "" + d.idsolicitud;
                    }
                },
                {
                    "width": "10%",
                    "data": function(d) {
                        var p = d.folio ? d.folio : 'SF';
                        return p + (d.prioridad == 1 ? "<br><small class='label pull-center bg-red'>URGENTE</small>" : "");

                    }
                },
                {
                    "width": "16%",
                    "data": function(d) {
                        return '<p style="font-size: .9em">' + d.nombre + '</p>';
                    }
                },
                {
                    "width": "8%",
                    "data": function(d) {
                        return '<p style="font-size: .9em">$ ' + formatMoney(d.autorizado) + ' ' + d.moneda + '</p>';
                    }
                },
                {
                    "width": "10%",
                    "data": function(d) {
                        return '<p style="font-size: .9em">$ ' + formatMoney((d.tipoCambio ? d.tipoCambio * d.autorizado : d.autorizado)) + " " + (d.tipoCambio ? 'MXN' : d.moneda) + '</p>';
                    }
                },
                {
                    "width": "8%",
                    "data": function(d) {
                        return '<p style="font-size: .9em">' + d.metoPago + ' <small class="label bg-blue">' + ((d.metoPago == "ECHQ" || d.metoPago == 'EFEC') && d.referencia && d.referencia != '' ? d.referencia : '') + '</small></p>';
                    }
                },
                {
                    "width": "8%",
                    "data": function(d) {
                        return '<p style="font-size: .9em">' + d.fecelab + '</p>';
                    }
                },
                {
                    "width": "12%",
                    "data": function(d) {
                        return '<p style="font-size: .9em">' + d.nomdepto + '</p>';
                    }
                },
                {
                    "width": "8%",
                    "data": function(d) {
                        return '<p style="font-size: .9em">' + d.nemp + '</p>';
                    }
                },
                {
                    "width": "18%",
                    "orderable": false,
                    "data": function(data) {


                        opciones = data.estatus == '11' ? '<small class="label pull-right bg-navy">Listo para aceptar pago</small><br>' : '';
                        opciones += '<div class="btn-group-vertical" role="group">';
                        opciones += '<div class="btn-group-vertical"><button type="button" class="btn btn-primary consultar_modal notification" value="' + data.idsolicitud + '" data-value="' + (no_espera.includes(data.nomdepto) ? "DEV_BASICA" : "SOL") + '" title="Ver Solicitud"><i class="fas fa-eye"></i></button></div>'
                        if (data.estatus == '11' && data.metoPago == 'ECHQ') {
                            opciones += '<button type="button" class="txt_ind_ot btn bg-teal btn-sm"  data-pago="' + data.idpago + '" data-empresa="' + data.idEmpresa + '" style="margin-right: 5px;" title="Descargar TXT Individual"><i class="fa fa-download"></i></button>';
                        }
                        opciones += '<button type="button" class="btn btn-warning btn-sm" onClick="mandar_espera(' + data.idpago + ')" title="Mandar a espera" style="margin-right: 5px;"><i class="fas fa-clock"></i></button>';

                        if (data.moneda != 'MXN') {
                            opciones += '<button type="button" style="margin-right: 5px;" class="btn bg-olive btn-sm cargar_cambio2" value="' + data.idpago + '" title="Ingresar interés"><i class="fas fa-money-check-alt"></i></button>';
                        }

                        if (data.metoPago == 'ECHQ' || data.metoPago == 'EFEC') {
                            opciones += '<button type="button"  style="margin-right: 5px;" class="btn bg-blue btn-sm" onClick="ingresar_consecutivo(' + data.idpago + ', \'' + data.metoPago + '\');" title="Ingresar consecutivo de cheque" ><i class="fa fa-pencil-square-o"></i></button>';
                        }

                        opciones += '<button type="button" class="btn btn-sm bg-maroon" onClick="mas_opciones(' + data.idpago + ', 2, \'' + data.nomdepto + '\' )" title="Más opciones" style="margin-right: 5px;"><i class="fa fa-plus-circle"></i></button>';


                        return opciones + '</div>';
                    }
                }
            ],
            columnDefs: [{
                orderable: false,
                className: 'select-checkbox',
                targets: 1,
                'searchable': false,
                'className': 'dt-body-center',
                'render': function(d, type, full, meta) {

                    if (full.estatus == 11) {
                        return '<input type="checkbox" name="id[]" style="width:20px;height:20px;" value="' + full.idpago + '">';
                    } else {
                        return '';
                    }
                },
                select: {
                    style: 'os',
                    selector: 'td:first-child'
                }
            }]
            /*,
            "ajax": {
                "url": url + "Cuentasxp/ver_datos_autdg_otros",
                "type": "POST",
                cache: false,
                "data": function( d ){
                    return  $.extend( d, { "nomdepto": $("#cxp1_sel").val(), "idempresa": $("#cxp2_sel").val() } );
                }
            }
            */
        });

        $('#tabla_autoriza_dg_otros tbody').on('click', 'td.details-control', function() {
            var tr = $(this).closest('tr');
            var row = tabla_otros.row(tr);

            if (row.child.isShown()) {
                row.child.hide();
                tr.removeClass('shown');
                $(this).parent().find('.animacion').removeClass("fa-caret-down").addClass("fa-caret-right");
            } else {

                var informacion_adicional = '<table class="table text-justify">' +
                    '<tr>' +
                    '<td style="font-size: .8em"><strong>JUSTIFICACIÓN: </strong>' + row.data().justificacion + '</td>' +
                    '</tr>' +
                    '</table>';

                row.child(informacion_adicional).show();
                tr.addClass('shown');
                $(this).parent().find('.animacion').removeClass("fa-caret-right").addClass("fa-caret-down");
            }
        });

        tabla_otros.on('search.dt order.dt', function() {
            tabla_otros.column(0, {
                search: 'applied',
                order: 'applied'
            }).nodes().each(function(cell, i) {
                cell.innerHTML = i + 1;
            });
        }).draw();


        $("#tabla_autoriza_dg_otros tbody").on("click", ".cargar_cambio2", function() {

            var tr = $(this).closest('tr');
            var row = tabla_otros.row(tr);

            idautopago = $(this).val();

            $("#modal_tipo_cambio2 .modal-body").html("");
            $("#modal_tipo_cambio2 .modal-body").append('<div class="row"><div class="col-lg-12"><p><b>MONEDA:</b> ' + row.data().moneda + '</p></div></div>');
            $("#modal_tipo_cambio2 .modal-body").append('<div class="row"><div class="col-lg-12 form-group"><label>TIPO CAMBIO</label><div class="input-group"><span class="input-group-addon"><i class="fas fa-dollar-sign"></i></span><input class="form-control" name="tipo_cambio" required></div></div></div>');
            $("#modal_tipo_cambio2 .modal-body").append('<div class="row"><div class="col-lg-8 col-lg-offset-2"><button class="btn bg-olive btn-block">EDITAR</button></div></div>');
            $("#modal_tipo_cambio2").modal();
        });

        $(document).on('click', '.cargar_interes_ot', function(e) {

            idautopago = $(this).val();
            var tr = $(this).closest('tr');
            var row = tabla_aut_programados.row(tr);



            e.preventDefault();



            $("#modal_intereses_ot .modal-body").html("");
            $("#modal_intereses_ot .modal-body").append('<div class="row"><div class="col-lg-12"><p><b>Cantidad:</b> $' + formatMoney(row.data().cantidad) + '</p></div></div>');
            $("#modal_intereses_ot .modal-body").append('<div class="row"><div class="col-lg-12 form-group"><label>INTÉRES</label><div class="input-group"><span class="input-group-addon"><i class="fas fa-dollar-sign"></i></span><input type="number" class="form-control" name="valor_interes" placeholder="Cantidad en $" required></div></div></div>');
            $("#modal_intereses_ot .modal-body").append('<div class="row"><div class="col-lg-8 col-lg-offset-2"><button class="btn bg-green btn-block">ACEPTAR</button></div></div>');
            $("#modal_intereses_ot").modal();



        });

        $(document).on('click', '.cargar_cambio2_2', function(e) {

            idautopago = $(this).val();
            var tr = $(this).closest('tr');
            var row = tabla_aut_programados.row(tr);

            e.preventDefault();

            $("#modal_tipo_cambio2 .modal-body").html("");
            $("#modal_tipo_cambio2 .modal-body").append('<div class="row"><div class="col-lg-12"><p><b>MONEDA:</b> ' + row.data().moneda + '</p></div></div>');
            $("#modal_tipo_cambio2 .modal-body").append('<div class="row"><div class="col-lg-12 form-group"><label>TIPO CAMBIO</label><div class="input-group"><span class="input-group-addon"><i class="fas fa-dollar-sign"></i></span><input class="form-control" name="tipo_cambio" required></div></div></div>');
            $("#modal_tipo_cambio2 .modal-body").append('<div class="row"><div class="col-lg-8 col-lg-offset-2"><button class="btn bg-olive btn-block">EDITAR</button></div></div>');
            $("#modal_tipo_cambio2").modal();
        });
    });



    $("#tabla_autoriza_dg_fact").ready(function() {
        $('#tabla_autoriza_dg_fact thead tr:eq(0) th').each(function(i) {

            if (i != 0 && i != 10) {
                var title = $(this).text();
                $(this).html('<input type="text" style="width:100%;" placeholder="' + title + '"/>');
                $('input', this).on('keyup change', function() {
                    if (tabla_facturaje.column(i).search() !== this.value) {
                        tabla_facturaje
                            .column(i)
                            .search(this.value)
                            .draw();

                        var total = 0;
                        var index = tabla_facturaje.rows({
                            selected: true,
                            search: 'applied'
                        }).indexes();
                        var data = tabla_facturaje.rows(index).data();

                        $.each(data, function(i, v) {
                            total += parseFloat(v.autorizado);
                        });

                        var to1 = formatMoney(total);
                        document.getElementById("myText5").value = to1;
                    }
                });
            }
        });

        $('#tabla_autoriza_dg_fact').on('xhr.dt', function(e, settings, json, xhr) {
            if (json) {
                var total = 0;
                $.each(json.data, function(i, v) {
                    total += parseFloat(v.autorizado);
                });
                var to = formatMoney(total);
                document.getElementById("myText5").value = formatMoney(total);
            }
        });

        tabla_facturaje = $('#tabla_autoriza_dg_fact').DataTable({
            dom: 'Brtip',
            "buttons": [{
                    extend: 'excelHtml5',
                    text: '<i class="fas fa-file-excel"></i> EXPORTAR ',
                    messageTop: "Lista de proveedores",
                    attr: {
                        class: 'btn btn-success',
                        style: 'margin-right: 20px;'
                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6],
                        format: {
                            header: function(data, columnIdx) {

                                data = data.replace('<input type="text" style="width:100%;" placeholder="', '');
                                data = data.replace('">', '')
                                return data;
                            }
                        }
                    }
                },
                {
                    text: '<i class="fas fa-file-download"></i> DESCARGAR TXT',
                    action: function() {

                        var rows = $(tabla_facturaje.$('input[name="id[]"][data-value="por_autorizar"]:checked, input[name="id[]"][data-value="puede_validar"]:checked')).map(function() {
                            return this.value;
                        }).get();

                        if (rows.length > 0) {
                            $.post(url + "Cuentasxp/txtProveedores", {
                                cuenta_val: $("#factoraje_sel option:selected").attr("data-value"),
                                idpago: JSON.stringify(rows)
                            }).done(function(data) {
                                data = JSON.parse(data);

                                if (!data.resultado) {
                                    $("#destxt").attr("href", data.file);
                                    $("#destxt")[0].click();
                                    click_tab();
                                } else {
                                    alert(data.mensaje)
                                }
                            });
                        }
                    },
                    attr: {
                        id: "txt_facturaje",
                        class: 'btn bg-teal',
                        style: 'margin-right: 30px;'
                    }
                },
                {
                    text: '<i class="fa fa-check"></i> ACEPTAR PAGOS',
                    action: function() {
                        var rows = $(tabla_facturaje.$('input[name="id[]"][data-value="puede_validar"]:checked')).map(function() {
                            return this.value;
                        }).get();
                        if (rows.length > 0) {
                            $.get(url + "Cuentasxp/aceptocpp/" + rows).done(function(data) {
                                click_tab();
                            });
                        }
                    },
                    attr: {
                        id: "aprobar_facturaje",
                        class: 'btn bg-navy',
                        style: 'margin-right: 30px;'
                    }
                }
            ],
            "language": lenguaje,
            "processing": false,
            "pageLength": 10,
            "bAutoWidth": false,
            "bLengthChange": false,
            "bInfo": false,
            "searching": true,
            "ordering": false,
            "columns": [{
                    "width": "5%"
                },
                {
                    "width": "10%",
                    "data": function(d) {
                        var p = d.folio ? d.folio : 'SF';
                        return p + (d.prioridad == 1 ? "<br><small class='label pull-center bg-red'>URGENTE</small>" : "");

                    }
                },
                {
                    "width": "12%",
                    "data": function(d) {
                        return '<p style="font-size: .9em">' + d.idsolicitud + '</p>';
                    }
                },
                {
                    "width": "12%",
                    "data": function(d) {
                        return '<p style="font-size: .9em">' + d.nombre + '</p>';
                    }
                },
                {
                    "width": "10%",
                    "data": function(d) {
                        return '<p style="font-size: .9em">$ ' + formatMoney(d.autorizado) + ' ' + d.moneda + '</p>';
                    }
                },
                {
                    "width": "10%",
                    "data": function(d) {
                        return '<p style="font-size: .9em">$ ' + formatMoney((d.tipoCambio ? d.tipoCambio * d.autorizado : d.autorizado)) + " " + (d.tipoCambio ? 'MXN' : d.moneda) + '</p>';
                    }
                },
                {
                    "width": "8%",
                    "data": function(d) {
                        return '<p style="font-size: .9em">' + d.metoPago + ' <small class="label bg-blue">' + (d.metoPago == "ECHQ" && d.referencia != '' && d.referencia != null ? d.referencia : '') + '</small></p>';
                    }
                },
                {
                    "width": "10%",
                    "data": function(d) {
                        return '<p style="font-size: .9em">' + d.fecelab + '</p>';
                    }
                },
                {
                    "width": "10%",
                    "data": function(d) {
                        return '<p style="font-size: .9em">' + d.nomdepto + '</p>';
                    }
                },
                {
                    "width": "8%",
                    "data": function(d) {
                        return '<p style="font-size: .9em">' + d.nemp + '</p>';
                    }
                },
                {
                    "orderable": false,
                    "data": function(data) {

                        opciones = '<div class="btn-group-vertical" role="group">';

                        opciones += '<div class="btn-group-vertical"><button type="button" class="btn btn-primary consultar_modal notification" value="' + data.idsolicitud + '" data-value="SOL" title="Ver Solicitud"><i class="fas fa-eye"></i></button></div>';

                        if (data.estatus == '11') {
                            opciones = '<small class="label pull-right bg-green">Listo para aceptar pago</small>';
                            opciones += '<div class="btn-group-vertical" role="group">';
                            opciones += '<button type="button" class="txt_ind btn bg-teal btn-sm"  data-pago="' + data.idpago + '" data-empresa="' + data.idEmpresa + '" style="margin-right: 5px;" title="Descargar TXT Individual"><i class="fa fa-download"></i></button>';
                            opciones += '<button data-toggle="tooltip" data-placement="top" title="Aceptar pago" type="button" class="btn  bg-green btn-sm" onClick="aceptar_facturaje(' + data.idpago + ')" style="margin-right: 5px;"><i class="fas fa-check"></i></button>';
                        }

                        opciones += '<button type="button" class="btn btn-warning btn-sm" onClick="mandar_espera(' + data.idpago + ')" title="Mandar a espera" style="margin-right: 5px;"><i class="fas fa-clock"></i></button>';
                        opciones += '<button type="button" class="btn btn-sm bg-maroon" onClick="mas_opciones(' + data.idpago + ', 0, \'' + data.nomdepto + '\' )" title="Más opciones" style="margin-right: 5px;"><i class="fa fa-plus-circle"></i></button>';

                        if (data.moneda != 'MXN') {
                            opciones += '<button type="button" style="margin-right: 5px;" class="btn btn-sm cargar_cambio bg-olive" value="' + data.idpago + '" title="Agregar tipo de cambio"><i class="fas fa-money-check-alt"></i></button>';
                        }

                        return opciones + '</div>';
                    }
                }
            ],
            columnDefs: [{
                orderable: false,
                className: 'select-checkbox',
                targets: 0,
                'searchable': false,
                'className': 'dt-body-center',
                'render': function(d, type, full, meta) {
                    var valor_sel = $("#factoraje_sel").val();
                    if (full.estatus == "11")
                        return '<input type="checkbox" name="id[]" class="checked_factoraje" style="width:20px;height:20px;" data-value="puede_validar" value="' + full.idpago + '">';
                    else
                        return ((valor_sel && valor_sel != "") && (full.moneda == 'MXN' || (full.moneda != 'MXN' && (full.tipoCambio && full.tipoCambio != '')))) ? '<input type="checkbox" name="id[]" style="width:20px;height:20px;" class="checked_factoraje" data-value="por_autorizar" value="' + full.idpago + '">' : '';
                },
                select: {
                    style: 'os',
                    selector: 'td:first-child'
                }
            }],
        });

        $('#tabla_autoriza_dg_fact tbody').on('click', ".checked_factoraje", function() {

            var pagos_txt = $(tabla_facturaje.$('input[name="id[]"][data-value="por_autorizar"]:checked')).map(function() {
                return this.value;
            }).get();
            var pagos_enviar = $(tabla_facturaje.$('input[name="id[]"][data-value="puede_validar"]:checked')).map(function() {
                return this.value;
            }).get();

            if (pagos_txt.length > 0 || pagos_enviar.length > 0) {
                $('#txt_facturaje').prop('disabled', false);
            } else {
                $('#txt_facturaje').prop('disabled', true);
            }

            if (pagos_enviar.length > 0) {
                $('#aprobar_facturaje').prop('disabled', false);
            } else {
                $('#aprobar_facturaje').prop('disabled', true);
            }

        });
    });



    $("#factoraje_sel").change(function() {
        var e = document.getElementById("factoraje_sel");
        var strUser = e.options[e.selectedIndex].value;
        valor_cuenta = $("#factoraje_sel option:selected").attr("data-value");
        click_tab();

        $('#txt_facturaje').prop('disabled', true);
        $('#aprobar_facturaje').prop('disabled', true);
    });

    // FIN TABLA CUATRO_____________________________________________


    // FIN TABLA TRES_____________________________________________

    var idautopago;

    $("#form_cambio").submit(function(e) {
        e.preventDefault();
    }).validate({
        submitHandler: function(form) {

            var data = new FormData($(form)[0]);
            data.append("idautopago", idautopago);

            $.ajax({
                url: url + "Cuentasxp/cargar_tipo_cambio",
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                method: 'POST',
                type: 'POST', // For jQuery < 1.9
                success: function(data) {
                    if (data[0]) {
                        $("#modal_tipo_cambio").modal('toggle');
                        click_tab();
                    } else {
                        alert("NO SE HA PODIDO COMPLETAR LA SOLICITUD");
                    }
                },
                error: function() {

                }
            });
        }
    });

    var idautopago;
    var row_transferencia;

    $("#form_interes").submit(function(e) {
        e.preventDefault();
    }).validate({
        submitHandler: function(form) {

            var data = new FormData($(form)[0]);
            data.append("idautopago", idautopago);

            $.ajax({
                url: url + "Cuentasxp/cargar_interes_agregado",
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                method: 'POST',
                type: 'POST',
                // For jQuery < 1.9
                success: function(data) {

                    if (data[0]) {
                        $("#modal_intereses").modal('toggle');
                        click_tab();
                    } else {
                        alert("NO SE HA PODIDO COMPLETAR LA SOLICITUD");
                    }

                    // if( data[0] ){
                    //     $("#modal_intereses").modal('toggle' );

                    //     var row = tabla_transferencias.row( row_transferencia ).data();
                    //     row.interes = data[1];
                    //     tabla_transferencias.row( row_transferencia ).data( row ).draw();

                    // }else{
                    //     alert("NO SE HA PODIDO COMPLETAR LA SOLICITUD");
                    // }


                },
                error: function() {

                }
            });
        }
    });

    $("#form_interes_ot").submit(function(e) {
        e.preventDefault();
    }).validate({
        submitHandler: function(form) {

            var data = new FormData($(form)[0]);
            data.append("idautopago", idautopago);

            $.ajax({
                url: url + "Cuentasxp/cargar_interes_agregado",
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                method: 'POST',
                type: 'POST', // For jQuery < 1.9
                success: function(data) {
                    if (data[0]) {
                        $("#modal_intereses_ot").modal('toggle');
                        click_tab();
                    } else {
                        alert("NO SE HA PODIDO COMPLETAR LA SOLICITUD");
                    }
                },
                error: function() {

                }
            });
        }
    });

    $("#form_cambio2").submit(function(e) {
        e.preventDefault();
    }).validate({
        submitHandler: function(form) {

            var data = new FormData($(form)[0]);
            data.append("idautopago", idautopago);

            $.ajax({
                url: url + "Cuentasxp/cargar_tipo_cambio",
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                method: 'POST',
                type: 'POST', // For jQuery < 1.9
                success: function(data) {
                    if (data[0]) {
                        $("#modal_tipo_cambio2").modal('toggle');
                        click_tab();
                    } else {
                        alert("NO SE HA PODIDO COMPLETAR LA SOLICITUD");
                    }
                },
                error: function() {

                }
            });
        }
    });

    // TABLA DE CAJAS CHICAS
    var tota2 = 0;
    var idpago_caja_chica = null;
    var trcajachica;

    $("#tabla_cajachica").ready(function() {

        $('#tabla_cajachica thead tr:eq(0) th').each(function(i) {
            if (i != 0 && i != 8) { /** FECHA: 29-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                var title = $(this).text();
                $(this).html('<input type="text" style="width:100%;" placeholder="' + title + '" />');
                $('input', this).on('keyup change', function() {

                    if (tabla_caja_chica.column(i).search() !== this.value) {
                        tabla_caja_chica
                            .column(i)
                            .search(this.value)
                            .draw();

                        var total = 0;
                        var index = tabla_caja_chica.rows({
                            selected: true,
                            search: 'applied'
                        }).indexes();
                        var data = tabla_caja_chica.rows(index).data();

                        $.each(data, function(i, v) {
                            total += parseFloat(v.Cantidad);
                        });
                        var to1 = formatMoney(total);
                        document.getElementById("myText4").value = formatMoney(total);
                    }
                });
            }
        });

        $('#tabla_cajachica').on('xhr.dt', function(e, settings, json, xhr) {
            var total = 0;
            $.each(json.data, function(i, v) {
                total += parseFloat(v.Cantidad);
            });
            var to = formatMoney(total);
            document.getElementById("myText4").value = to;
        });

        tabla_caja_chica = $('#tabla_cajachica').DataTable({
            dom: 'Brtip',
            width: 'auto',
            "buttons": [{
                    text: '<i class="fas fa-file-invoice"></i> GENERAR DOCUMENTOS',
                    action: function() {

                        $("#myModal2_caja_chica").modal();
                        $("#myModal2_caja_chica #resban_caja_chica").html("");
                        $("#myModal2_caja_chica .form-control").val("");

                    },
                    attr: {
                        class: 'btn bg-teal',
                        style: 'margin-right: 5px;'
                    }
                },
                {
                    extend: 'excelHtml5',
                    text: '<i class="fas fa-file-excel"></i> EXPORTAR EXCEL',
                    messageTop: "Lista de pagos autorizados por Dirección General (CAJA CHICA)",
                    attr: {
                        class: 'btn btn-success',
                        style: 'margin-right: 30px;'
                    },
                    exportOptions: {
                        columns: function(idx, data, node){ /** INICIO FECHA: 29-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                            if ($('ul.nav-tabs').children('li.active').eq(0).children('a').data('value') !== 'pagos_autoriza_dg_tdc'){
                                if($.inArray(idx, [1, 2, 3, 4, 5, 6]) > -1) return true;
                            }else{
                                if($.inArray(idx, [1, 3, 4, 5, 6]) > -1) return true;
                            }
                        }, /** FIN FECHA: 29-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                        format: {
                            header: function(data, columnIdx) {
                                data = data.replace('<input type="text" style="width:100%;" placeholder="', '');
                                data = data.replace('">', '')
                                return data;
                            }
                        }
                    }
                }

            ],
            "language": lenguaje,
            "processing": false,
            "pageLength": 50,
            "bAutoWidth": false,
            "bLengthChange": false,
            "bInfo": false,
            "scrollX": true,
            "ordering": false,
            responsive: true,
            "columns": [{

                    "className": 'details-control',
                    "orderable": false,
                    "data": null,
                    "defaultContent": '<i class="fas animacion fa-caret-right"></i>'
                },
                {
                    "width": "15%",
                    "data": function(d) {
                        return '<p style="font-size: .9em">' + d.Responsable + " " + d.apellidos + '</p>';
                    }
                },
                { /** INICIO FECHA: 29-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                    "width": "10%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+(d.nombre_reembolso_cch ? d.nombre_reembolso_cch : 'NA' )+'</p>'
                    }
                }, /** FIN FECHA: 29-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                {
                    "width": "12%",
                    "data": function(d) {
                        return '<p style="font-size: .9em">' + d.abrev + '</p>';
                    }
                },
                {
                    "width": "12%",
                    "data": function(d) {
                        return '<p style="font-size: .9em">' + d.FECHAFACP + '</p>';
                    }
                },
                {
                    "width": "10%",
                    "data": function(d) {
                        return '<p style="font-size: .9em">$ ' + formatMoney(d.cnt) + ' MXN</p>';
                    }
                },
                {
                    "width": "15%",
                    "data": function(d) {
                        return '<p style="font-size: .9em">' + d.Departamento + '</p>';
                    }
                },
                {
                    "width": "15%",
                    "data": function(d) {
                        if (d.tipoPago) {
                            switch (d.tipoPago) {
                                case 'ECHQ':
                                    clases = 'label bg-maroon';
                                    break;

                                case 'EFEC':
                                    clases = 'label bg-olive';
                                    break;
                                case 'TEA':
                                case 'MAN':
                                    clases = 'label bg-purple';
                                    break;
                            }
                            resultado = '<p style="font-size: .9em"><small style="font-size:12px;" class="' + clases + '">' + d.tipoPago + ' ' + (d.referencia ? d.referencia : '') + '<small></b></p>';
                        } else {
                            resultado = '<p>SIN DATOS</p>';
                        }

                        return resultado;
                    }
                },
                {
                    "width": "15%",
                    "orderable": false,
                    "data": function(data) {
                        opciones = '<div class="btn-group-vertical" role="group">';

                        opciones += `<button type="button"
                                             style="margin-right: 10px;"
                                             class="btn bg-blue btn-sm opciones_caja_chica"
                                             title="Ingresar consecutivo de cheque">
                                        <i class="fas fa-external-link-alt"></i>
                                    </button>`;

                        opciones += `<button type="button"
                                             class="btn btn-warning btn-sm"
                                             onClick="mandar_espera_chica('+${data.PAG}+')"
                                             title="Mandar a espera">
                                        <i class="fas fa-clock"></i>
                                    </button>`;

                        if (data.ESTATUS == 2) {
                            opciones += '<button type="button" class="btn btn-success btn-sm enviar_caja_chica" title="Aceptar pago"><i class="fas fa-check"></i></button>';
                        }
                        /*
                           -- Se comenta el siguiente código ya que se requiere que no se muestren registro con etapa de caja cerrada --
                           -- Lo que hace el código comentado es el deshabilitar boton para cerrar caja chica desde este modulo. --*/
                        let disabled = data.idetapa == "31" ? 'disabled' : '';
                        let title_caja_cerrada = data.idetapa == "31" ? 'Caja chica cerrada' : 'Cerrar caja chica';
                        opciones += `<button class="btn btn-warning btn-sm cerrar_caja"
                                              title="${title_caja_cerrada}"
                                              ${disabled}>
                                        <i class="fas fa-toolbox">&nbsp<i class="fas fa-key "></i></i>
                                    </button>`;
                        return opciones + '</div>';
                    }
                }
            ]
        });

        $('#tabla_cajachica tbody').on('click', 'td.details-control', function() {

            var tr = $(this).closest('tr');
            var row = tabla_caja_chica.row(tr);

            if (row.child.isShown()) {

                row.child.hide();
                tr.removeClass('shown');

                $(this).parent().find('.animacion').removeClass("fa-caret-down").addClass("fa-caret-right");
            } else {

                if (row.data().solicitudes == null || row.data().solicitudes == "null") {
                    $.post(url + "Historial/carga_cajas_chicas", {
                        "idcajachicas": row.data().ID
                    }).done(function(data) {

                        row.data().solicitudes = JSON.parse(data);

                        tabla_caja_chica.row(tr).data(row.data());

                        row = tabla_caja_chica.row(tr);

                        row.child(construir_subtablas(row.data().solicitudes)).show();
                        tr.addClass('shown');
                        $(this).parent().find('.animacion').removeClass("fa-caret-right").addClass("fa-caret-down");

                    });
                } else {
                    row.child(construir_subtablas(row.data().solicitudes)).show();
                    tr.addClass('shown');
                    $(this).parent().find('.animacion').removeClass("fa-caret-right").addClass("fa-caret-down");
                }

            }
        });

        function construir_subtablas(data) { /** INICIO FECHA: 29-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
        var solicitudes = '<div class="container" style="width: 100%; padding-top: 10px; padding-bottom: 10px; border-left: 1px solid #D2D6DE; border-right: 1px solid #D2D6DE; border-bottom: 1px solid #D2D6DE; border-radius: 0 0 5px 5px;">';
        $.each(data, function(i, v) { 
            solicitudes += `<div class="row" style="padding-right: 10px; padding-left: 10px;">`;
            solicitudes += `
                <div class="row">
                    <div class="col-md-12">
                        <p><b>NO. SOLICITUD:</b> ${v.idsolicitud} </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <p><b>PROYECTO:</b> ${v.proyecto} </p>
                        <p><b>PROVEEDOR:</b> ${v.nombre_proveedor} </p>
                        <p><b>CANTIDAD:</b> ${formatMoney(v.cantidad) + ' ' + v.moneda} </p>
                    </div>
                    <div class="col-md-4">
                        <p><b>FECHA FACT:</b> ${v.fecelab} </p>
                        <p><b>FECHA AUT:</b> ${v.fecautorizacion} </p>
                    </div>
                    <div class="col-md-4">
                    <p><b>CAPTURISTA:</b> ${v.nombre_capturista} </p>
                        <p><b>FOLIO:</b> ${v.folio} </p>
                        <p><b>FOLIO FISCAL:</b> ${v.ffiscal} </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <p><b>JUSTIFICACIÓN:</b> ${v.justificacion} </p>
                        <button type="button" class="btn btn-primary consultar_modal notification" value="${v.idsolicitud}" data-value="SOL" title="Ver Solicitud">
                            <i class="fas fa-eye"></i> VER SOLICITUD 
                            ${(v.visto == 0 ? '<span class="badge">!</span>' : '')}
                        </button>
                    </div>
                </div>
            `;
            solicitudes += `</div>
                ${(i < data.length - 1 ? '<hr style="border-top: 1px solid #BEBEBE; margin-top: 10px; margin-bottom: 10px;">' : '')}
            `;
        });
        solicitudes += '</div>';
        return solicitudes;
    } /** FIN FECHA: 29-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/

        /*
        $('#tabla_cajachica tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = tabla_caja_chica.row( tr );

            if ( row.child.isShown() ) {
                row.child.hide();
                tr.removeClass('shown');
                $(this).parent().find('.animacion').removeClass("fa-caret-down").addClass("fa-caret-right");
            }
            else {

                var solicitudes = '<table class="table">';
                $.each( row.data().solicitudes, function( i, v){//i es el indice y v son los valores de cada fila
                    solicitudes += '<tr>';
                    solicitudes += '<td>'+(i+1)+'.- <b>'+'Proyecto'+'</b> '+v.proyecto+'</td>';
                    solicitudes += '<td>'+'<b>'+'Etapa'+'</b> '+v.ETAPA+'</td>';
                    solicitudes += '<td>'+'<b>'+'Condominio '+'</b> '+v.Condominio+'</td>';
                    solicitudes += '<td>'+'<b>'+'Proveedor '+'</b> '+v.Proveedor+'</td>';
                    solicitudes += '<td>'+'<b>'+'Cantidad '+'</b> $'+formatMoney(v.cnt2)+' MXN</td>';
                    solicitudes += '<td>'+'<b>'+'Fecha Factura '+'</b> '+v.FECHAFACP+'</td>';
                    solicitudes += '<td>'+'<b>'+'Descripcion '+'</b> '+v.Observacion+'</td>';
                    solicitudes += '</tr>';
                });

                solicitudes += '</table>';

                row.child( solicitudes ).show();
                tr.addClass('shown');
                $(this).parent().find('.animacion').removeClass("fa-caret-right").addClass("fa-caret-down");
            }
        });
        */
        $('#tabla_cajachica').on('click', 'input', function() {
            trcajachica = $(this).closest('tr');
            var row = tabla_caja_chica.row(trcajachica);

            if ($(this).prop("checked")) {
                tota2 += parseFloat(row.data().Cantidad);
            } else {
                tota2 -= parseFloat(row.data().Cantidad);
            }
            $("#totpagarC").html(formatMoney(tota2));
        });

        //OPCIONES PARA TRABAJAR CON LA CAJA CAJA CHICA
        $('#tabla_cajachica tbody').on('click', '.opciones_caja_chica', function() {

            trcajachica = $(this).closest('tr');
            var row = tabla_caja_chica.row(trcajachica).data();

            $.post(url + "Opciones/revisar_proveedores", {
                idusuario: row.idusuario,
                departamento: row.Departamento
            }).done(function(data) {

                idpago_caja_chica = row.PAG;

                data = JSON.parse(data);

                vfcaja_chica.resetForm();

                if (data.length > 0) {
                    $(".lista_prov_ch").html("");
                    $(".lista_prov_ch").append('<option value="" selected=""> -SELECCIONA OPCIÓN- </option>');
                    $.each(data, function(i, v) {
                        $(".lista_prov_ch").append('<option value="' + v.idproveedor + '" data-value="' + v.cuenta + '">' + v.alias + " - " + v.cuenta + " - " + v.nombanco + '</option>');
                    });
                }

                $("#myModalconsecutivo_chica").modal();
                $("#myModalconsecutivo_chica .form-control").val("");

                $("select[name='idcuenta']").hide();
                $("input[name='serie_cheque_ch']").hide();
            });
        });

        $('#tabla_cajachica tbody').on('click', '.enviar_caja_chica', function() {

                trcajachica = $(this).closest('tr');
                var row = tabla_caja_chica.row(trcajachica).data();

                $.post(url + "Cuentasxp/enviar_pag_caja/", {
                    idpago: row.PAG
                }).done(function() {

                    $("#myModal_chica_all").modal('toggle');

                    $("#myModal_chica_all .modal-footer").html("");
                    $("#myModal_chica_all .modal-body").html("");
                    $("#myModal_chica_all ").modal();
                    $("#myModal_chica_all .modal-body").append("<p class='text-center'>Se ha enviado a dispersión de pagos.</p>");

                    tabla_caja_chica.row(trcajachica).remove().draw(false);

                });

        });

        $('#tabla_cajachica tbody').on('click','.cerrar_caja', function (){
            var row = tabla_caja_chica.row( $(this).closest('tr') );
            ids = row.data().ID;
            $("#confirm").modal();
        });

    });


    var totaRem = 0;
    var trreembolsos;
    var idpago_reembolsos = null;
    $("#tabla_reembolsos").ready(function() {

        $('#tabla_reembolsos thead tr:eq(0) th').each(function(i) {
            if (i != 0 && i != 7) {
                var title = $(this).text();
                $(this).html('<input type="text" style="width:100%;" placeholder="' + title + '" />');
                $('input', this).on('keyup change', function() {

                    if (tabla_reembolsos.column(i).search() !== this.value) {
                        tabla_reembolsos
                            .column(i)
                            .search(this.value)
                            .draw();

                        var total = 0;
                        var index = tabla_reembolsos.rows({
                            selected: true,
                            search: 'applied'
                        }).indexes();
                        var data = tabla_reembolsos.rows(index).data();

                        $.each(data, function(i, v) {
                            console.log("numero: ", v);
                            total += parseFloat(v.Cantidad);
                        });
                        var to1 = formatMoney(total);
                        document.getElementById("totalReembolsoTxt").value = formatMoney(total);
                    }
                });
            }
        });

        $('#tabla_reembolsos').on('xhr.dt', function(e, settings, json, xhr) {
            var total = 0;
            $.each(json.data, function(i, v) {
                total += parseFloat(v.Cantidad);
            });
            var to = formatMoney(total);
            document.getElementById("totalReembolsoTxt").value = to;
        });

        tabla_reembolsos = $('#tabla_reembolsos').DataTable({
            dom: 'Brtip',
            width: 'auto',
            "buttons": [{
                text: '<i class="fas fa-file-invoice"></i> GENERAR DOCUMENTOS',
                action: function() {

                    $("#myModal2_caja_chica").modal();
                    $("#myModal2_caja_chica #resban_reem").html("");
                    $("#myModal2_caja_chica .form-control").val("");

                },
                attr: {
                    class: 'btn bg-teal',
                    style: 'margin-right: 5px;'
                }
            },
                {
                    extend: 'excelHtml5',
                    text: '<i class="fas fa-file-excel"></i> EXPORTAR EXCEL',
                    messageTop: "Lista de pagos autorizados por Dirección General (CAJA CHICA)",
                    attr: {
                        class: 'btn btn-success',
                        style: 'margin-right: 30px;'
                    },
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5, 7],
                        format: {
                            header: function(data, columnIdx) {
                                data = data.replace('<input type="text" style="width:100%;" placeholder="', '');
                                data = data.replace('">', '')
                                return data;
                            }
                        }
                    }
                }

            ],
            "language": lenguaje,
            "processing": false,
            "pageLength": 50,
            "bAutoWidth": false,
            "bLengthChange": false,
            "bInfo": false,
            "scrollX": true,
            "ordering": false,
            responsive: true,
            "columns": [
                {

                    "className": 'details-control',
                    "orderable": false,
                    "data": null,
                    "defaultContent": '<i class="fas animacion fa-caret-right"></i>'
                },
                {
                    "width": "15%",
                    "data": function(d) {
                        return '<p style="font-size: .9em">' + d.Responsable + " " + d.apellidos + '</p>';
                    }
                },
                {
                    "width": "12%",
                    "data": function(d) {
                        return '<p style="font-size: .9em">' + d.abrev + '</p>';
                    }
                },
                {
                    "width": "12%",
                    "data": function(d) {
                        return '<p style="font-size: .9em">' + d.FECHAFACP + '</p>';
                    }
                },
                {
                    "width": "10%",
                    "data": function(d) {
                        return '<p style="font-size: .9em">$ ' + formatMoney(d.cnt) + ' MXN</p>';
                    }
                },
                {
                    "width": "15%",
                    "data": function(d) {
                        return '<p style="font-size: .9em">' + d.Departamento + '</p>';
                    }
                },
                {
                    "width": "15%",
                    "data": function(d) {
                        if (d.tipoPago) {
                            switch (d.tipoPago) {
                                case 'ECHQ':
                                    clases = 'label bg-maroon';
                                    break;

                                case 'EFEC':
                                    clases = 'label bg-olive';
                                    break;
                                case 'TEA':
                                case 'MAN':
                                    clases = 'label bg-purple';
                                    break;
                            }
                            resultado = '<p style="font-size: .9em"><small style="font-size:12px;" class="' + clases + '">' + d.tipoPago + ' ' + (d.referencia ? d.referencia : '') + '<small></b></p>';
                        } else {
                            resultado = '<p>SIN DATOS</p>';
                        }

                        return resultado;
                    }
                },
                {
                    "width": "15%",
                    "orderable": false,
                    "data": function(data) {
                        opciones = '<div class="btn-group-vertical" role="group">';

                        opciones += `<button type="button"
                                                 style="margin-right: 10px;"
                                                 class="btn bg-blue btn-sm opciones_reembolso"
                                                 title="Ingresar consecutivo de cheque">
                                            <i class="fas fa-external-link-alt"></i>
                                        </button>`;

                        opciones += `<button type="button"
                                                 class="btn btn-warning btn-sm"
                                                 onClick="mandar_espera_chica(${data.PAG})"
                                                 title="Mandar a espera">
                                            <i class="fas fa-clock"></i>
                                        </button>`;

                        if (data.ESTATUS == 2) {
                            opciones += '<button type="button" class="btn btn-success btn-sm enviar_reembolsos" title="Aceptar pago"><i class="fas fa-check"></i></button>';
                        }

                        opciones += `<button class="btn btn-warning btn-sm cerrar_reembolsos"
                                                 title="Cerrar caja chica">
                                            <i class="fas fa-toolbox">&nbsp<i class="fas fa-key "></i></i>
                                        </button>`;

                        return opciones + '</div>';
                    }
                }
            ]
        });

        $('#tabla_reembolsos tbody').on('click', 'td.details-control', function() {

            var tr = $(this).closest('tr');
            var row = tabla_reembolsos.row(tr);

            if (row.child.isShown()) {

                row.child.hide();
                tr.removeClass('shown');

                $(this).parent().find('.animacion').removeClass("fa-caret-down").addClass("fa-caret-right");
            } else {

                if (row.data().solicitudes == null || row.data().solicitudes == "null") {
                    $.post(url + "Historial/carga_cajas_chicas", {
                        "idcajachicas": row.data().ID
                    }).done(function(data) {

                        row.data().solicitudes = JSON.parse(data);

                        tabla_reembolsos.row(tr).data(row.data());

                        row = tabla_reembolsos.row(tr);

                        row.child(construir_subtablas(row.data().solicitudes)).show();
                        tr.addClass('shown');
                        $(this).parent().find('.animacion').removeClass("fa-caret-right").addClass("fa-caret-down");

                    });
                } else {
                    row.child(construir_subtablas(row.data().solicitudes)).show();
                    tr.addClass('shown');
                    $(this).parent().find('.animacion').removeClass("fa-caret-right").addClass("fa-caret-down");
                }

            }
        });

        function construir_subtablas(data) {
            var solicitudes = '<table class="table">';
            $.each(data, function(i, v) {
                //i es el indice y v son los valores de cada fila
                solicitudes += '<tr>';
                solicitudes += '<td>' + v.idsolicitud + '.- <b>' + 'PROYECTO: ' + '</b> ' + v.proyecto + '</td>';
                solicitudes += '<td colspan="3">' + '<b>' + 'PROVEEDOR ' + '</b> ' + v.nombre_proveedor + '</td>';
                solicitudes += '<td>' + '<b>' + 'CANTIDAD: ' + '</b> $ ' + formatMoney(v.cantidad) + ' ' + v.moneda + '</td>';
                solicitudes += '</tr>';
                solicitudes += '<tr>';
                solicitudes += '<td>' + '<b>' + 'CAPTURISTA ' + '</b> ' + v.nombre_capturista + '</td>';
                solicitudes += '<td>' + '<b>' + 'FOLIO ' + '</b> ' + v.folio + '</td>';
                solicitudes += '<td>' + '<b>' + 'FOLIO FISCAL' + '</b> ' + v.ffiscal + '</td>';
                solicitudes += '<td>' + '<b>' + 'FECHA FACT: ' + '</b> ' + v.fecelab + '</td>';
                solicitudes += '<td>' + '<b>' + 'FECHA AUT: ' + '</b> ' + v.fecautorizacion + '</td>';
                solicitudes += '</tr>';
                solicitudes += '<tr>';
                solicitudes += '<td colspan="5">' + '<b>' + 'JUSTIFICACIÓN: ' + '</b> ' + v.justificacion + '</td>';
                solicitudes += '<td colspan="5"><div class="btn-group-vertical"><button type="button" class="btn btn-primary consultar_modal notification" value="' + v.idsolicitud + '" data-value="SOL" title="Ver Solicitud"><i class="fas fa-eye"></i>' + (v.visto == 0 ? '<span class="badge">!</span>' : '') + '</button></div></td>';

                solicitudes += '</tr>';
            });

            return solicitudes += '</table>';
        }

        /*
        $('#tabla_cajachica tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = tabla_caja_chica.row( tr );

            if ( row.child.isShown() ) {
                row.child.hide();
                tr.removeClass('shown');
                $(this).parent().find('.animacion').removeClass("fa-caret-down").addClass("fa-caret-right");
            }
            else {

                var solicitudes = '<table class="table">';
                $.each( row.data().solicitudes, function( i, v){//i es el indice y v son los valores de cada fila
                    solicitudes += '<tr>';
                    solicitudes += '<td>'+(i+1)+'.- <b>'+'Proyecto'+'</b> '+v.proyecto+'</td>';
                    solicitudes += '<td>'+'<b>'+'Etapa'+'</b> '+v.ETAPA+'</td>';
                    solicitudes += '<td>'+'<b>'+'Condominio '+'</b> '+v.Condominio+'</td>';
                    solicitudes += '<td>'+'<b>'+'Proveedor '+'</b> '+v.Proveedor+'</td>';
                    solicitudes += '<td>'+'<b>'+'Cantidad '+'</b> $'+formatMoney(v.cnt2)+' MXN</td>';
                    solicitudes += '<td>'+'<b>'+'Fecha Factura '+'</b> '+v.FECHAFACP+'</td>';
                    solicitudes += '<td>'+'<b>'+'Descripcion '+'</b> '+v.Observacion+'</td>';
                    solicitudes += '</tr>';
                });

                solicitudes += '</table>';

                row.child( solicitudes ).show();
                tr.addClass('shown');
                $(this).parent().find('.animacion').removeClass("fa-caret-right").addClass("fa-caret-down");
            }
        });
        */
        $('#tabla_reembolsos').on('click', 'input', function() {
            tabla_reembolsos = $(this).closest('tr');
            var row = tabla_reembolsos.row(trreembolsos);

            if ($(this).prop("checked")) {
                totaRem += parseFloat(row.data().Cantidad);
            } else {
                totaRem -= parseFloat(row.data().Cantidad);
            }
            $("#totpagarC").html(formatMoney(tota2));
        });

        //OPCIONES PARA TRABAJAR CON LA CAJA CAJA CHICA
        $('#tabla_reembolsos tbody').on('click', '.opciones_reembolso', function() {

            trreembolsos = $(this).closest('tr');
            var row = tabla_reembolsos.row(trreembolsos).data();

            $.post(url + "Opciones/revisar_proveedores", {
                idusuario: row.idusuario,
                departamento: row.Departamento
            }).done(function(data) {

                idpago_reembolsos = row.PAG;

                data = JSON.parse(data);

                vfcaja_chica.resetForm();

                if (data.length > 0) {
                    $(".lista_prov_ch").html("");
                    $(".lista_prov_ch").append('<option value="" selected=""> -SELECCIONA OPCIÓN- </option>');
                    $.each(data, function(i, v) {
                        $(".lista_prov_ch").append('<option value="' + v.idproveedor + '" data-value="' + v.cuenta + '">' + v.alias + " - " + v.cuenta + " - " + v.nombanco + '</option>');
                    });
                }

                $("#modalReembolsoConsecutivo").modal();
                $("#modalReembolsoConsecutivo .form-control").val("");

                $("select[name='idcuentaReem']").hide();
                $("input[name='serie_cheque_reem']").hide();
            });
        });

        $('#tabla_reembolsos tbody').on('click', '.enviar_reembolsos', function() {

            trreembolsos = $(this).closest('tr');
            var row = tabla_reembolsos.row(trreembolsos).data();

            $.post(url + "Cuentasxp/enviar_pag_caja/", {
                idpago: row.PAG
            }).done(function() {

                $("#myModal_chica_all").modal('toggle');

                $("#myModal_chica_all .modal-footer").html("");
                $("#myModal_chica_all .modal-body").html("");
                $("#myModal_chica_all ").modal();
                $("#myModal_chica_all .modal-body").append("<p class='text-center'>Se ha enviado a dispersión de pagos.</p>");

                tabla_reembolsos.row(trreembolsos).remove().draw(false);

            });

        });

        $('#tabla_reembolsos tbody').on('click', '.cerrar_reembolsos', function() {
            var row = tabla_reembolsos.row($(this).closest('tr'));
            ids = row.data().ID;
            $("#confirmReembolsos").modal();
        });

    });


    $("#forma_pago_reembolso #idcuentaReem").change(function() {
        $("#forma_pago_reembolso #serie_cheque_reem").val($("#forma_pago_reembolso #idcuentaReem option:selected").data("value"));
    });


    var vfreembolsos = $("#forma_pago_reembolso").submit(function(e) {
        e.preventDefault();
    }).validate({
        submitHandler: function(form) {

            var data = new FormData($(form)[0]);
            data.append("idpago", idpago_reembolsos);

            $.ajax({
                url: url + "Opciones/update_serie_reembolsos",
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                method: 'POST',
                type: 'POST', // For jQuery < 1.9
                success: function(data) {
                    if (data.resultado) {
                        $("#modalReembolsoConsecutivo").modal('toggle');

                        var row = tabla_reembolsos.row(trreembolsos).data();

                        row.ESTATUS = data.valores_enviados.estatus;
                        row.tipoPago = data.valores_enviados.tipoPago;
                        row.referencia = data.valores_enviados.referencia;

                        tabla_reembolsos.row(trreembolsos).data(row).draw();
                        /*
                        tabla_caja_chica.clear();
                        tabla_caja_chica.row().data( row ).add();
                        tabla_caja_chica.draw();
                        */

                        tabla_reembolsos.row(trreembolsos).data(row).draw();
                    } else {
                        alert("NO SE HA PODIDO COMPLETAR LA SOLICITUD");
                    }
                },
                error: function() {
                    alert("Algo salio mal, recargue su página.");
                }
            });
        }
    });

    //TABLA DE VIATICOS
    var totaVia = 0;
    var trviaticos;
    var idpago_viaticos = null;
    $("#tabla_viaticos").ready(function() {

        $('#tabla_viaticos thead tr:eq(0) th').each(function(i) {
			 if (i != 0 && i != 8) {
                var title = $(this).text();
                $(this).html('<input type="text" style="width:100%;" placeholder="' + title + '" />');
                $('input', this).on('keyup change', function() {

                    if (tabla_viaticos.column(i).search() !== this.value) {
                        tabla_viaticos
                            .column(i)
                            .search(this.value)
                            .draw();

                        var total = 0;
                        var index = tabla_viaticos.rows({
                            selected: true,
                            search: 'applied'
                        }).indexes();
                        var data = tabla_viaticos.rows(index).data();

                        $.each(data, function(i, v) {
                            console.log("numero: ", v);
                            total += parseFloat(v.Cantidad);
                        });
                        var to1 = formatMoney(total);
                        document.getElementById("totalViaticoTxt").value = formatMoney(total);
                    }
                });
            }
        });

        $('#tabla_viaticos').on('xhr.dt', function(e, settings, json, xhr) {
            var total = 0;
            $.each(json.data, function(i, v) {
                total += parseFloat(v.Cantidad);
            });
            var to = formatMoney(total);
            document.getElementById("totalViaticoTxt").value = to;
        });

        tabla_viaticos = $('#tabla_viaticos').DataTable({
            dom: 'Brtip',
            width: 'auto',
            "buttons": [{
                    text: '<i class="fas fa-file-invoice"></i> GENERAR DOCUMENTOS',
                    action: function() {

					 $("#modal_viaticos_txt").modal();
					 $("#modal_viaticos_txt #resban_viaticos").html("");
					 $("#modal_viaticos_txt .form-control").val("");

                    },
                    attr: {
                        class: 'btn bg-teal',
                        style: 'margin-right: 5px;'
                    }
                },
                {
                    extend: 'excelHtml5',
                    text: '<i class="fas fa-file-excel"></i> EXPORTAR EXCEL',
					 messageTop: "Lista de pagos autorizados por Dirección General (VIÁTICOS)",
                    attr: {
                        class: 'btn btn-success',
                        style: 'margin-right: 30px;'
                    },
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5, 7],
                        format: {
                            header: function(data, columnIdx) {
                                data = data.replace('<input type="text" style="width:100%;" placeholder="', '');
                                data = data.replace('">', '')
                                return data;
                            }
                        }
                    }
                }

            ],
            "language": lenguaje,
            "processing": false,
            "pageLength": 50,
            "bAutoWidth": false,
            "bLengthChange": false,
            "bInfo": false,
            "scrollX": true,
            "ordering": false,
            responsive: true,
            "columns": [{

                    "className": 'details-control',
                    "orderable": false,
                    "data": null,
                    "defaultContent": '<i class="fas animacion fa-caret-right"></i>'
                },
                {
                    "width": "15%",
                    "data": function(d) {
                        return '<p style="font-size: .9em">' + d.Responsable + " " + d.apellidos + '</p>';
                    }
                },
                 { /** FECHA: 12-JUNIO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                    "width": "15%",
					"data": function( d ){
						return '<p style="font-size: .9em">'+(d.nombre_reembolso_cch ? d.nombre_reembolso_cch : 'NA')+'</p>';
                    }
                 }, /** FECHA: 12-JUNIO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                {
                    "width": "12%",
                    "data": function(d) {
                        return '<p style="font-size: .9em">' + d.abrev + '</p>';
                    }
                },
                {
                    "width": "12%",
                    "data": function(d) {
                        return '<p style="font-size: .9em">' + d.FECHAFACP + '</p>';
                    }
                },
                {
                    "width": "10%",
                    "data": function(d) {
                        return '<p style="font-size: .9em">$ ' + formatMoney(d.cnt) + ' MXN</p>';
                    }
                },
                {
                    "width": "15%",
                    "data": function(d) {
                        return '<p style="font-size: .9em">' + d.Departamento + '</p>';
                    }
                },
                {
                    "width": "15%",
                    "data": function(d) {
                        if (d.tipoPago) {
                            switch (d.tipoPago) {
                                case 'ECHQ':
                                    clases = 'label bg-maroon';
                                    break;

                                case 'EFEC':
                                    clases = 'label bg-olive';
                                    break;
                                case 'TEA':
                                case 'MAN':
                                    clases = 'label bg-purple';
                                    break;
                            }
                            resultado = '<p style="font-size: .9em"><small style="font-size:12px;" class="' + clases + '">' + d.tipoPago + ' ' + (d.referencia ? d.referencia : '') + '<small></b></p>';
                        } else {
                            resultado = '<p>SIN DATOS</p>';
                        }

                        return resultado;
                    }
                },
                {
                    "width": "15%",
                    "orderable": false,
                    "data": function(data) {
                        opciones = '<div class="btn-group-vertical" role="group">';

                        opciones += `<button type="button"
                                             style="margin-right: 10px;"
                                             class="btn bg-blue btn-sm opciones_viaticos"
                                             title="Ingresar consecutivo de cheque">
                                        <i class="fas fa-external-link-alt"></i>
                                    </button>`;

                        opciones += `<button type="button"
                                             class="btn btn-warning btn-sm"
                                             onClick="mandar_espera_viaticos(${data.PAG})"
                                             title="Mandar a espera">
                                        <i class="fas fa-clock"></i>
                                    </button>`;

                        if (data.ESTATUS == 2) {
                            opciones += '<button type="button" class="btn btn-success btn-sm enviar_viaticos" title="Aceptar pago"><i class="fas fa-check"></i></button>';
                        }

                        opciones += `<button class="btn btn-warning btn-sm cerrar_viaticos"
                                             title="Cerrar viáticos">
                                        <i class="fas fa-toolbox">&nbsp<i class="fas fa-key "></i></i>
                                    </button>`;

                        return opciones + '</div>';
                    }
                }
            ]
        });

        $('#tabla_viaticos tbody').on('click', 'td.details-control', function() {

            var tr = $(this).closest('tr');
            var row = tabla_viaticos.row(tr);

            if (row.child.isShown()) {

                row.child.hide();
                tr.removeClass('shown');

                $(this).parent().find('.animacion').removeClass("fa-caret-down").addClass("fa-caret-right");
            } else {

                if (row.data().solicitudes == null || row.data().solicitudes == "null") {
                    $.post(url + "Historial/carga_cajas_chicas", {
                        "idcajachicas": row.data().ID
                    }).done(function(data) {

                        row.data().solicitudes = JSON.parse(data);

                        tabla_viaticos.row(tr).data(row.data());

                        row = tabla_viaticos.row(tr);

                        row.child(construir_subtablas(row.data().solicitudes)).show();
                        tr.addClass('shown');
                        $(this).parent().find('.animacion').removeClass("fa-caret-right").addClass("fa-caret-down");

                    });
                } else {
                    row.child(construir_subtablas(row.data().solicitudes)).show();
                    tr.addClass('shown');
                    $(this).parent().find('.animacion').removeClass("fa-caret-right").addClass("fa-caret-down");
                }

            }
        });

        function construir_subtablas(data) {
            var solicitudes = '<table class="table">';
            $.each(data, function(i, v) {
                //i es el indice y v son los valores de cada fila
                solicitudes += '<tr>';
                solicitudes += '<td>' + v.idsolicitud + '.- <b>' + 'PROYECTO: ' + '</b> ' + v.proyecto + '</td>';
                solicitudes += '<td colspan="3">' + '<b>' + 'PROVEEDOR ' + '</b> ' + v.nombre_proveedor + '</td>';
                solicitudes += '<td>' + '<b>' + 'CANTIDAD: ' + '</b> $ ' + formatMoney(v.cantidad) + ' ' + v.moneda + '</td>';
                solicitudes += '</tr>';
                solicitudes += '<tr>';
                solicitudes += '<td>' + '<b>' + 'CAPTURISTA ' + '</b> ' + v.nombre_capturista + '</td>';
                solicitudes += '<td>' + '<b>' + 'FOLIO ' + '</b> ' + v.folio + '</td>';
                solicitudes += '<td>' + '<b>' + 'FOLIO FISCAL' + '</b> ' + v.ffiscal + '</td>';
                solicitudes += '<td>' + '<b>' + 'FECHA FACT: ' + '</b> ' + v.fecelab + '</td>';
                solicitudes += '<td>' + '<b>' + 'FECHA AUT: ' + '</b> ' + v.fecautorizacion + '</td>';
                solicitudes += '</tr>';
                solicitudes += '<tr>';
                solicitudes += '<td colspan="5">' + '<b>' + 'JUSTIFICACIÓN: ' + '</b> ' + v.justificacion + '</td>';
                solicitudes += '<td colspan="5"><div class="btn-group-vertical"><button type="button" class="btn btn-primary consultar_modal notification" value="' + v.idsolicitud + '" data-value="SOL" title="Ver Solicitud"><i class="fas fa-eye"></i>' + (v.visto == 0 ? '<span class="badge">!</span>' : '') + '</button></div></td>';

                solicitudes += '</tr>';
            });

            return solicitudes += '</table>';
        }

        $('#tabla_viaticos').on('click', 'input', function() {
            trviaticos = $(this).closest('tr');
            var row = tabla_viaticos.row(trviaticos);

            if ($(this).prop("checked")) {
                totaVia += parseFloat(row.data().Cantidad);
            } else {
                totaVia -= parseFloat(row.data().Cantidad);
            }
            $("#totpagarC").html(formatMoney(tota2));
        });

        //OPCIONES PARA TRABAJAR CON LA CAJA CAJA CHICA
        $('#tabla_viaticos tbody').on('click', '.opciones_viaticos', function() {

            trviaticos = $(this).closest('tr');
            var row = tabla_viaticos.row(trviaticos).data();

            $.post(url + "Opciones/revisar_proveedores", {
                idusuario: row.idusuario,
                departamento: row.Departamento
            }).done(function(data) {

                idpago_viaticos = row.PAG;

                data = JSON.parse(data);

                vfcaja_chica.resetForm();

                if (data.length > 0) {
                    $(".lista_prov_ch_viaticos").html("");
                    $(".lista_prov_ch_viaticos").append('<option value="" selected=""> -SELECCIONA OPCIÓN- </option>');
                    $.each(data, function(i, v) {
                        $(".lista_prov_ch_viaticos").append('<option value="' + v.idproveedor + '" data-value="' + v.cuenta + '">' + v.alias + " - " + v.cuenta + " - " + v.nombanco + '</option>');
                    });
                }

                $("#modalViaticos").modal();
                $("#modalViaticos .form-control").val("");

                $("select[name='idcuentaVia']").hide();
                $("input[name='serie_cheque_via']").hide();
            });
        });

        $('#tabla_viaticos tbody').on('click', '.enviar_viaticos', function() {

            trviaticos = $(this).closest('tr');
            var row = tabla_viaticos.row(trviaticos).data();

            $.post(url + "Cuentasxp/enviar_pag_caja/", {
                idpago: row.PAG
            }).done(function() {

                $("#myModal_chica_all").modal('toggle');

                $("#myModal_chica_all .modal-footer").html("");
                $("#myModal_chica_all .modal-body").html("");
                $("#myModal_chica_all ").modal();
                $("#myModal_chica_all .modal-body").append("<p class='text-center'>Se ha enviado a dispersión de pagos.</p>");

                tabla_viaticos.row(trviaticos).remove().draw(false);

            });

        });

        $('#tabla_viaticos tbody').on('click', '.cerrar_viaticos', function() {
            var row = tabla_viaticos.row($(this).closest('tr'));
            ids = row.data().ID;
            $("#confirmViaticos").modal();
        });

    });
    //

    $("#forma_pago_viaticos #idcuentaVia").change(function() {
        $("#forma_pago_viaticos #serie_cheque_via").val($("#forma_pago_viaticos #idcuentaVia option:selected").data("value"));
    });

    var vfviaticos = $("#forma_pago_viaticos").submit(function(e) {
        e.preventDefault();
    }).validate({
        submitHandler: function(form) {

            var data = new FormData($(form)[0]);
            data.append("idpago", idpago_viaticos);

            $.ajax({
                url: url + "Opciones/update_serie_viaticos",
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                method: 'POST',
                type: 'POST', // For jQuery < 1.9
                success: function(data) {
                    if (data.resultado) {
                        $("#modalViaticos").modal('toggle');

                        var row = tabla_viaticos.row(trviaticos).data();

                        row.ESTATUS = data.valores_enviados.estatus;
                        row.tipoPago = data.valores_enviados.tipoPago;
                        row.referencia = data.valores_enviados.referencia;

                        tabla_viaticos.row(trviaticos).data(row).draw();
                        /*
                        tabla_caja_chica.clear();
                        tabla_caja_chica.row().data( row ).add();
                        tabla_caja_chica.draw();
                        */

                        tabla_viaticos.row(trviaticos).data(row).draw();
                    } else {
                        alert("NO SE HA PODIDO COMPLETAR LA SOLICITUD");
                    }
                },
                error: function() {
                    alert("Algo salio mal, recargue su página.");
                }
            });
        }
    });

    // FIN TABLA CUATRO_____________________________________________

    var ids = 0;

    function cerrar_caja() {
        if (ids != 0) {
            $.post(url + "Cuentasxp/cerrar_caja_sol", {
                "ids": ids
            }).done(function(data) {
                tabla_caja_chica.ajax.url(url + "Cuentasxp/tablaSolCaja").load();
                $("#confirm").modal('toggle');
            });
        } else
            alert("Ocurrio un error intentelo mas tarde");
    }

    function cerrar_reembolsos() {
        if (ids != 0) {
            $.post(url + "Cuentasxp/cerrar_caja_sol", {
                "ids": ids
            }).done(function(data) {
                tabla_reembolsos.ajax.url(url + "Cuentasxp/tablaSolReem").load();
                $("#confirmReembolsos").modal('toggle');
            });
        } else
            alert("Ocurrio un error intentelo mas tarde");
    }

    function cerrar_viaticos() {
        if (ids != 0) {
            $.post(url + "Cuentasxp/cerrar_caja_sol", {
                "ids": ids
            }).done(function(data) {
                tabla_viaticos.ajax.url(url + "Cuentasxp/tablaSolViaticos").load();
                $("#confirmViaticos").modal('toggle');
            });
        } else
            alert("Ocurrio un error intentelo mas tarde");
    }
    $("#forma_pago_caja_chica #idcuenta").change(function() {
        $("#forma_pago_caja_chica #serie_cheque_ch").val($("#forma_pago_caja_chica #idcuenta option:selected").data("value"));
    });

    var vfcaja_chica = $("#forma_pago_caja_chica").submit(function(e) {
        e.preventDefault();
    }).validate({
        submitHandler: function(form) {

            var data = new FormData($(form)[0]);
            data.append("idpago", idpago_caja_chica);

            $.ajax({
                url: url + "Opciones/update_serie_ch",
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                method: 'POST',
                type: 'POST', // For jQuery < 1.9
                success: function(data) {
                    if (data.resultado) {
                        $("#myModalconsecutivo_chica").modal('toggle');

                        var row = tabla_caja_chica.row(trcajachica).data();

                        row.ESTATUS = data.valores_enviados.estatus;
                        row.tipoPago = data.valores_enviados.tipoPago;
                        row.referencia = data.valores_enviados.referencia;

                        tabla_caja_chica.row(trcajachica).data(row).draw();
                        /*
                        tabla_caja_chica.clear();
                        tabla_caja_chica.row().data( row ).add();
                        tabla_caja_chica.draw();
                        */

                        tabla_caja_chica.row(trcajachica).data(row).draw();
                    } else {
                        alert("NO SE HA PODIDO COMPLETAR LA SOLICITUD");
                    }
                },
                error: function() {
                    alert("Algo salio mal, recargue su página.");
                }
            });
        }
    });

    /************************************/
    //TABLA PAUSADO
    $("#tabla_pagos_pausados").ready(function() {

        $('#tabla_pagos_pausados thead tr:eq(0) th').each(function(i) {
            if (i != 0 && i < $('#tabla_pagos_pausados thead tr:eq(0) th').length - 1) {
                var title = $(this).text();
                $(this).html('<input type="text" style="width:100%;" class="form-control" placeholder="' + title + '"/>');
                $('input', this).on('keyup change', function() {

                    if (tabla_pausados.column(i).search() !== this.value) {
                        tabla_pausados
                            .column(i)
                            .search(this.value)
                            .draw();

                        var total = 0;
                        var index = tabla_pausados.rows({
                            selected: true,
                            search: 'applied'
                        }).indexes();
                        var data = tabla_pausados.rows(index).data();

                        $.each(data, function(i, v) {
                            total += parseFloat(v.cantidad);
                        });
                        var to1 = formatMoney(total);
                        document.getElementById("myText_pausados").value = formatMoney(total);
                    }
                });
            }
        });

        $('#tabla_pagos_pausados').on('xhr.dt', function(e, settings, json, xhr) {
            var total = 0;
            $.each(json.data, function(i, v) {
                total += parseFloat(v.cantidad);
            });
            var to = formatMoney(total);
            document.getElementById("myText_pausados").value = to;
        });

        tabla_pausados = $("#tabla_pagos_pausados").DataTable({
            dom: 'Bfrtip',
            width: 'auto',
            "buttons": [{
                extend: 'excelHtml5',
                text: '<i class="fas fa-file-excel"></i> EXPORTAR',
                messageTop: "LISTADO DE PAGOS PAUSADOS",
                attr: {
                    class: 'btn btn-success'
                },
                exportOptions: {
                    columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
                    format: {
                        header: function(data, columnIdx) {
                            data = data.replace('<input type="text" style="width:100%;" class="form-control" placeholder="', '');
                            data = data.replace('">', '')
                            return data;
                        }
                    }
                }

            }],
            "language": lenguaje,
            "processing": false,
            "pageLength": 10,
            "bAutoWidth": false,
            "bLengthChange": false,
            "scrollX": true,
            "bInfo": false,
            "searching": true,
            "ordering": false,
            "fixedColumns": true,
            "columns": [{
                    "className": 'details-control',
                    "orderable": false,
                    "data": null,
                    "defaultContent": '<i class="fas animacion fa-caret-right"></i>'
                },
                {
                    "width": "10%",
                    "data": function(d) {
                        return '<p style="font-size: .8em">' + d.idsolicitud + '</p>';
                    }
                },
                {
                    "width": "15%",
                    "data": function(d) {
                        return '<p style="font-size: .8em">' + d.nombre + '</p>';
                    }
                },
                {
                    "width": "10%",
                    "data": function(d) {
                        return '<p style="font-size: .8em">$ ' + formatMoney(d.cantidad) + '</p>';
                    }
                },
                {
                    "width": "10%",
                    "data": function(d) {
                        return '<p style="font-size: .8em">' + d.tipoPago + (d.folio ? " " + d.folio : "") + '</p>';
                    }
                },
                {
                    "width": "15%",
                    "data": function(d) {
                        return '<p style="font-size: .8em">' + d.fecreg + '</p>';
                    }
                },
                {
                    "width": "12%",
                    "data": function(d) {
                        return '<p style="font-size: .8em">' + d.nomdepto + '</p>';
                    }
                },
                {
                    "width": "10%",
                    "data": function(d) {
                        return '<p style="font-size: .8em">' + d.abrev + '</p>';
                    }
                },
                {
                    "width": "12%",
                    "data": function(d) {

                        if (d.notab == 1) {
                            return '<p style="font-size: .8em">PROVEEDOR</p>' + (d.prioridad ? "<br><small class='label pull-center bg-red'>URGENTE</small>" : "");
                        } else {
                            return '<p style="font-size: .8em">CAJA CHICA</p>';
                        }

                    }
                },
                {
                    "width": "10%",
                    "data": function(d) {
                        return '<p style="font-size: .8em">' + d.justificacion + '</p>';
                    }
                },
                {
                    "width": "10%",
                    "data": function(d) {
                        return '<p style="font-size: .8em">' + d.motivoEspera + '</p>';
                    }
                },
                {
                    "width": "12%",
                    "orderable": false,
                    "data": function(data) {

                        opciones = '<div class="btn-group-vertical" role="group">';
                        opciones += '<button type="button" class="btn bg-silver btn-sm desbloquear_pago" title="Desbloquear"><i class="fas fa-clock" style="color:#001F3F;"></i></button>';

                        return opciones + '</div>';
                    }
                }
            ],
            "columnDefs": [{
                    "targets": [9],
                    "visible": false,
                },
                {
                    "targets": [10],
                    "visible": true
                }
            ],
            "order": false,
        });

        $('#tabla_pagos_pausados tbody').on('click', 'td.details-control', function() {
            var tr = $(this).closest('tr');
            var row = tabla_pausados.row(tr);

            if (row.child.isShown()) {
                row.child.hide();
                tr.removeClass('shown');
                $(this).parent().find('.animacion').removeClass("fa-caret-down").addClass("fa-caret-right");
            } else {

                var informacion_adicional = '<table class="table text-justify">' +
                    '<tr>' +
                    '<td style="font-size: 1em"><strong>JUSTIFICACIÓN: </strong>' + row.data().justificacion + '</td>' +
                    '</tr>' +
                    '<tr>' +
                    '<td style="font-size: 1em"><strong>MOTIVO ESPERA: </strong>' + row.data().motivoEspera + '</td>' +
                    '</tr>' +
                    '</table>';

                row.child(informacion_adicional).show();
                tr.addClass('shown');
                $(this).parent().find('.animacion').removeClass("fa-caret-right").addClass("fa-caret-down");
            }
        });

        $('#tabla_pagos_pausados tbody').on('click', '.desbloquear_pago', function() {

            var tr = $(this).closest('tr');
            var row = tabla_pausados.row(tr).data();

            var link_dinamico = (row.notab == '1' ? "Cuentasxp/regresarcolapagos_transf/" : "Cuentasxp/regresarcolapagos_CHICA/");

            $.post(url + link_dinamico, {
                idpago: row.idpago
            }).done(function(data) {
                data = JSON.parse(data);

                if (data.resultado) {
                    tabla_pausados.row(tr).remove().draw(false);
                }
            });
        });

        //OPCIÓN PARA NUMERAR EL LISTADO DE PAGOS EN LA TABLA
        /*
        tabla_pausados.on('search.dt order.dt', function (){
            tabla_pausados.column(0,{search:'applied', order:'applied'}).nodes().each(function(cell, i){
                cell.innerHTML = i+1;
            });
        }).draw();
        */
    });
    // FIN TABLA PAUSADOS
    /************************************/

    var idsolrechazo;
    var link_post2;

    $("#infosol1").submit(function(e) {
        e.preventDefault();
    }).validate({
        submitHandler: function(form) {

            var data = new FormData($(form)[0]);

            data.append("idsolicitud", idsolrechazo);

            $.ajax({
                url: url + link_post2,
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                method: 'POST',
                type: 'POST', // For jQuery < 1.9
                success: function(data) {
                    if (data.resultado) {
                        $("#myModalcomentario1").modal('toggle');
                        z
                        click_tab();
                    } else {
                        alert("NO SE HA PODIDO COMPLETAR LA SOLICITUD");
                    }
                },
                error: function() {

                }
            });
        }
    });

    var idpagoespera_cch;
    var link_espera1_cch;

    $("#form_espera_cch").submit(function(e) {
        e.preventDefault();
    }).validate({
        submitHandler: function(form) {

            var data = new FormData($(form)[0]);

            data.append("idpago", idpagoespera_cch);

            $.ajax({
                url: url + link_espera1_cch,
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                method: 'POST',
                type: 'POST', // For jQuery < 1.9
                success: function(data) {
                    if (data.resultado) {
                        $("#myModalEspera_chica").modal('toggle');
                        click_tab();
                    } else {
                        alert("NO SE HA PODIDO COMPLETAR LA SOLICITUD");
                    }
                },
                error: function() {

                }
            });
        }
    });

    var idpagoespera_via;
    var link_espera1_via;
    $("#form_espera_via").submit(function(e) {
        e.preventDefault();
    }).validate({
        submitHandler: function(form) {

            var data = new FormData($(form)[0]);

            data.append("idpago", idpagoespera_via);

            $.ajax({
                url: url + link_espera1_via,
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                method: 'POST',
                type: 'POST', // For jQuery < 1.9
                success: function(data) {
                    if (data.resultado) {
                        $("#myModalEspera_via").modal('toggle');
                        click_tab();
                    } else {
                        alert("NO SE HA PODIDO COMPLETAR LA SOLICITUD");
                    }
                },
                error: function() {

                }
            });
        }
    });


    var idpagoespera;
    var link_espera1;

    $("#form_espera_uno").submit(function(e) {
        e.preventDefault();
    }).validate({
        submitHandler: function(form) {

            var data = new FormData($(form)[0]);

            data.append("idpago", idpagoespera);

            $.ajax({
                url: url + link_espera1,
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                method: 'POST',
                type: 'POST', // For jQuery < 1.9
                success: function(data) {
                    if (data.resultado) {
                        $("#myModalEspera").modal('toggle');
                        click_tab();
                    } else {
                        alert("NO SE HA PODIDO COMPLETAR LA SOLICITUD");
                    }
                },
                error: function() {

                }
            });
        }
    });

    var idpagocambiar;
    var link_cambia1;

    $("#form_cambia_tea").submit(function(e) {
        e.preventDefault();
    }).validate({
        submitHandler: function(form) {

            var data = new FormData($(form)[0]);

            data.append("idpago", idpagocambiar);

            $.ajax({
                url: url + link_cambia1,
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                method: 'POST',
                type: 'POST',
                success: function(data) {
                    if (data.resultado) {
                        $("#myModalcambioTEA").modal('toggle');
                        click_tab();
                    } else {
                        alert("NO SE HA PODIDO COMPLETAR LA SOLICITUD");
                    }
                },
                error: function() {

                }
            });
        }
    });

    var idpagocambiar_fact;
    var link_cambia_facto;

    $("#form_cambiaFACTO").submit(function(e) {
        e.preventDefault();
    }).validate({
        submitHandler: function(form) {

            var data = new FormData($(form)[0]);

            data.append("idpago", idpagocambiar_fact);

            $.ajax({
                url: url + link_cambia_facto,
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                method: 'POST',
                type: 'POST',
                success: function(data) {
                    if (data.resultado) {
                        $("#myModalcamb_fact").modal('toggle');
                        click_tab();
                    } else {
                        alert("NO SE HA PODIDO COMPLETAR LA SOLICITUD");
                    }
                },
                error: function() {

                }
            });
        }
    });

    var idpagocambiar2;
    var link_cambia12;

    $("#form_cambia_OTRO").submit(function(e) {
        e.preventDefault();
    }).validate({
        submitHandler: function(form) {

            var data = new FormData($(form)[0]);

            data.append("idpago", idpagocambiar2);

            $.ajax({
                url: url + link_cambia12,
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                method: 'POST',
                type: 'POST',
                success: function(data) {
                    if (data.resultado) {
                        $("#myModalcambiOTRO").modal('toggle');
                        click_tab();
                    } else {
                        alert("NO SE HA PODIDO COMPLETAR LA SOLICITUD");
                    }
                },
                error: function() {

                }
            });
        }
    });

    var param1;
    var param2;
    var param3;

    var numProv = document.getElementById('idcuentas');
    var formaPago = document.getElementById('tipoPago_chica');

    var link_post_caja;

    $("#infopago_chica_1").submit(function(e) {
        e.preventDefault();
    }).validate({
        submitHandler: function(form) {

            var data = new FormData($(form)[0]);

            $.ajax({
                url: url + link_post_caja,
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                method: 'POST',
                type: 'POST', // For jQuery < 1.9
                success: function(data) {
                    if (data.resultado) {
                        $("#modalCaja").modal('toggle');
                        click_tab();
                    } else {
                        alert("NO SE HA PODIDO COMPLETAR LA SOLICITUD");
                    }
                },
                error: function() {

                }
            });
        }
    });

    var idpagorechazo;
    var link_post22;

    $("#infosol22").submit(function(e) {
        e.preventDefault();
    }).validate({
        submitHandler: function(form) {

            var data = new FormData($(form)[0]);

            data.append("idpago", idpagorechazo);

            $.ajax({
                url: url + link_post22,
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                method: 'POST',
                type: 'POST', // For jQuery < 1.9
                success: function(data) {
                    if (data.resultado) {
                        $("#myModalcomentario3").modal('toggle');
                        click_tab();
                    } else {
                        alert("NO SE HA PODIDO COMPLETAR LA SOLICITUD");
                    }
                },
                error: function() {

                }
            });
        }
    });

    var idpagorechazo_ch;
    var link_post22_ch;

    $("#infosol22_ch").submit(function(e) {
        e.preventDefault();
    }).validate({
        submitHandler: function(form) {

            var data = new FormData($(form)[0]);
            data.append("idpago", idpagorechazo_ch);

            $.ajax({
                url: url + link_post22_ch,
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                method: 'POST',
                type: 'POST', // For jQuery < 1.9
                success: function(data) {
                    if (data.resultado) {
                        $("#myModalcomentario3_ch").modal('toggle');
                        click_tab();
                    } else {
                        alert("NO SE HA PODIDO COMPLETAR LA SOLICITUD");
                    }
                },
                error: function() {

                }
            });
        }
    });

    function regresar_da(idsolicitud) {
        idsolrechazo = idsolicitud;
        link_post2 = "Cuentasxp/datos_para_rechazo1/";
        $("#myModalcomentario1 .modal-footer").html("");
        $("#myModalcomentario1 .modal-body").html("");
        $("#myModalcomentario1 ").modal();
        $("#myModalcomentario1 .modal-body").append("<div class='form-group col-lg-12'><input type='text' class='form-control' placeholder='Describe motivo de rechazo.' name='observacion' id='observacion' required></div>");
        $("#myModalcomentario1 .modal-footer").append("<div class='btn-group'><button type='submit' class='btn btn-success'>ACEPTAR</button><button type='button' class='btn btn-danger' onclick='cancelarr2()'>CANCELAR</button></div>");
    }

    function regresar_dp(idpago) {

        idpagorechazo = idpago;
        link_post22 = "Cuentasxp/datos_para_rechazo2/";
        $("#myModalcomentario3 .modal-footer").html("");
        $("#myModalcomentario3 .modal-body").html("");
        $("#myModalcomentario3 ").modal();

        $("#myModalcomentario3 .modal-body").append("<div class='form-group col-lg-12'><input type='text' class='form-control' placeholder='Describe motivo de rechazo.' name='observacion' id='observacion' required></div>");

        $("#myModalcomentario3 .modal-footer").append("<div class='btn-group'><button type='submit' class='btn btn-success'>ACEPTAR</button><button type='button' class='btn btn-danger' onclick='cancelarr22()'>CANCELAR</button></div>");

    }

    function regresar_dp_chica(idpago) {

        idpagorechazo_ch = idpago;
        link_post22_ch = "Cuentasxp/datos_para_rechazo2_ch/";
        $("#myModalcomentario3_ch .modal-footer").html("");
        $("#myModalcomentario3_ch .modal-body").html("");
        $("#myModalcomentario3_ch ").modal();

        $("#myModalcomentario3_ch .modal-body").append("<div class='form-group col-lg-12'><input type='text' class='form-control' placeholder='Describe motivo de rechazo.' name='observacion' id='observacion' required></div>");

        $("#myModalcomentario3_ch .modal-footer").append("<div class='btn-group'><button type='submit' class='btn btn-success'>ACEPTAR</button><button type='button' class='btn btn-danger' onclick='cancelarr22_ch()'>CANCELAR</button></div>");

    }

    function cancela() {
        $("#myModal").modal('toggle');
    }

    function cancelarr2() {
        // $("#myModal").modal('toggle');
        $("#myModalcomentario1").modal('toggle');
    }

    function cancelarr22() {
        // $("#myModal").modal('toggle');
        $("#myModalcomentario3").modal('toggle');
    }

    function cancelarr22_ch() {
        // $("#myModal").modal('toggle');
        $("#myModalcomentario3_ch").modal('toggle');
    }

    function cancelamal() {
        $("#myModalx").modal('toggle');
    }

    function acepta(idsolicitud) {

        // $.get(url+"Cuentasxp/enviarcpp/"+idsolicitud).done(function () { $("#myModal").modal('toggle'); click_tab();
        $("#myModal .modal-footer").html("");
        $("#myModal .modal-body").html("");
        $("#myModal ").modal();
        $("#myModal .modal-body").append("<label>¿Desea enviar esta solicitud a provisión?</label>");
        $("#myModal .modal-footer").append("<input type='button' class='btn btn-success' value='ENVIAR A PROVISIÓN' onclick='provisionar_solicitud(" + idsolicitud + ")'><input type='button' class='btn btn-warning' value='SIN PROVISIÓN' onclick='no_provisionar_solicitud(" + idsolicitud + ")'>");


        // $("#myModal .modal-footer").append("<div class='btn-group'><button class='btn btn-success' onClick=">ENVIAR A PROVISIÓN</button><button type='submit' class='btn btn-warning'>SIN PROVISIÓN</button> </div>");

    }


    function acepta_sin(idsolicitud) {

        $.get(url + "Cuentasxp/enviarcpp/" + idsolicitud).done(function() {
            $("#myModal").modal('toggle');
            click_tab();
            $("#myModal .modal-footer").html("");
            $("#myModal .modal-body").html("");
            $("#myModal ").modal();
            $("#myModal .modal-body").append("<label>Se ha aceptado la solicitud</label>");
        });

    }

    function provisionar_solicitud(idsolicitud) {
        $.get(url + "Cuentasxp/provisionar_ok/" + idsolicitud).done(function() {
            $("#myModal").modal('toggle');
            click_tab();
        });
    }

    function no_provisionar_solicitud(idsolicitud) {
        $.get(url + "Cuentasxp/provisionar_mal/" + idsolicitud).done(function() {
            $("#myModal").modal('toggle');
            click_tab();
        });
    }

    function atrasda(idsolicitud) {

        $("#myModalx .modal-footer").html("");
        $.get(url + "Cuentasxp/enviar_da/" + idsolicitud).done(function() {
            $("#myModalx").modal('toggle');
            click_tab();
        });
    }

    function declinasol(idsolicitud) {
        $("#myModal .modal-footer").html("");
        $("#myModal .modal-body").html("");
        $("#myModal ").modal();
        $("#myModal .modal-body").append("<label>¿Está seguro de declinar esta solicitud?</label>");
        $("#myModal .modal-footer").append("<input type='button' class='btn btn-warning' value='Enviar cola de pagos' onclick='badsol(" + idsolicitud + ")'>");
        $("#myModal .modal-footer").append("<input type='button' class='btn btn-danger' value='Cancelar'onclick='cancela()'>");
    }

    function mandar_espera(idpago) {
        idpagoespera = idpago;
        // link_post2 = "Cuentasxp/datos_para_rechazo1/";
        link_espera1 = "Cuentasxp/enviarcolapagos_una/";
        $("#myModalEspera .modal-footer").html("");
        $("#myModalEspera .modal-body").html("");
        $("#myModalEspera ").modal();
        $("#myModalEspera .modal-body").append("<div class='form-group col-lg-12'><textarea type='text' rows='6' class='form-control' style=\"resize:none\" placeholder='Describe motivo por el cual se envía a espera.' name='observacion' id='observacion' required></textarea> </div>");
        $("#myModalEspera .modal-footer").append("<div class='btn-group'><button type='submit' class='btn btn-success'>ACEPTAR</button><button type='button' class='btn btn-danger' onclick='cancela_espera1()'>CANCELAR</button></div>");


    }

    function mandar_espera_chica(idpago) {
        idpagoespera_cch = idpago;
        // link_post2 = "Cuentasxp/datos_para_rechazo1/";
        link_espera1_cch = "Cuentasxp/enviarcolapagos_caja_chica/";
        $("#myModalEspera_chica .modal-footer").html("");
        $("#myModalEspera_chica .modal-body").html("");
        $("#myModalEspera_chica ").modal();
        $("#myModalEspera_chica .modal-body").append("<div class='form-group col-lg-12'><input type='text' class='form-control' placeholder='Describe motivo por el cual se envía a espera.' name='observacion' id='observacion' required></div>");
        $("#myModalEspera_chica .modal-footer").append("<div class='btn-group'><button type='submit' class='btn btn-success'>ACEPTAR</button><button type='button' class='btn btn-danger' onclick='cancela_espera_chica()'>CANCELAR</button></div>");
    }

    function mandar_espera_viaticos(idpago) {
        idpago_viaticos = idpago;
        // link_post2 = "Cuentasxp/datos_para_rechazo1/";
        link_espera1_via = "Cuentasxp/enviarcolapagos_caja_chica/";
        $("#myModalEspera_via .modal-footer").html("");
        $("#myModalEspera_via .modal-body").html("");
        $("#myModalEspera_via ").modal();
        $("#myModalEspera_via .modal-body").append("<div class='form-group col-lg-12'><input type='text' class='form-control' placeholder='Describe motivo por el cual se envía a espera.' name='observacion' id='observacion' required></div>");
        $("#myModalEspera_via .modal-footer").append("<div class='btn-group'><button type='submit' class='btn btn-success'>ACEPTAR</button><button type='button' class='btn btn-danger' onclick='cancela_espera_viaticos()'>CANCELAR</button></div>");
    }

    function badsol(idsolicitud) {
        $("#myModal .modal-footer").html("");
        $.get(url + "Cuentasxp/enviarcolapagos/" + idsolicitud).done(function() {
            $("#myModal").modal('toggle');
            click_tab();
        });
    }


    //DECLARAMOS COMO SE VA A PAGAR LA CAJA CHICA SOLICITADA
    $(document).on("change", "#tipoPago_chica", function() {

        $("select[name='idcuenta']").hide();
        $("input[name='serie_cheque_ch']").hide();

        if ($(this).val() == "ECHQ" || $(this).val() == "EFEC") {
            $("input[name='serie_cheque_ch']").show();
        }

        if ($(this).val() == "TEA") {
            $("select[name='idcuenta']").show();
        }
    });

    //DECLARAMOS COMO SE VA A PAGAR LA CAJA CHICA SOLICITADA
    $(document).on("change", "#tipoPago_reem", function() {

        $("select[name='idcuentaReem']").hide();
        $("input[name='serie_cheque_reem']").hide();

        if ($(this).val() == "ECHQ" || $(this).val() == "EFEC") {
            $("input[name='serie_cheque_reem']").show();
        }

        if ($(this).val() == "TEA") {
            $("select[name='idcuentaReem']").show();
        }
    });

    //DECLARAMOS COMO SE VA A PAGAR LA CAJA CHICA SOLICITADA
    $(document).on("change", "#tipoPago_via", function() {

        $("select[name='idcuentaVia']").hide();
        $("input[name='serie_cheque_via']").hide();

        if ($(this).val() == "ECHQ" || $(this).val() == "EFEC") {
            $("input[name='serie_cheque_via']").show();
        }

        if ($(this).val() == "TEA") {
            $("select[name='idcuentaVia']").show();
        }
    });

    $(document).on("change", "#formaPago", function() {

        if ($(this).val() == "NULL") {

            $("label[name='lab3']").hide();
            $("input[name='idcheque_general']").hide();
            $("input[name='fecha_pago']").hide();
            $("label[name='lab2']").hide();
            $("button[name='btn1']").hide();
            $("button[name='btn2']").hide();

        }
        if ($(this).val() == "TEA") {
            $("label[name='lab3']").hide();
            $("input[name='idcheque_general']").hide();
            $("input[name='fecha_pago']").show();
            $("label[name='lab2']").show();
            $("button[name='btn1']").hide();
            $("button[name='btn2']").hide();
        }
        if ($(this).val() == "ECHQ") {
            $("label[name='lab3']").show();
            $("input[name='idcheque_general']").show();
            $("input[name='fecha_pago']").show();
            $("label[name='lab2']").show();
            $("button[name='btn1']").show();
            $("button[name='btn2']").show();
        }

        if ($(this).val() == "EFEC") {
            $("label[name='lab3']").hide();
            $("input[name='idcheque_general']").hide();
            $("input[name='fecha_pago']").show();
            $("label[name='lab2']").show();
            $("button[name='btn1']").hide();
            $("button[name='btn2']").hide();
        }
    });

    /****AGREGAMOS LA REFERENCIA DEL CHEQUE O EFECTIVO*/
    var idpago_modificar;
    var forma_pago;

    function ingresar_consecutivo(idpago, metodo_pago) {

        idpago_modificar = idpago;
        forma_pago = metodo_pago;

        $("#myModalconsecutivo").modal();
        $("#myModalconsecutivo .form-control").val("");
    }

    function acepta_cheque() {
        var liga = '';
        var data = {
            base_datos: 1,
            referencia: $("#serie_cheque").val(),
            forma_pago: forma_pago,
            numpago: idpago_modificar
        };

        switch ($("ul.nav-tabs li.active a").attr('href')) {
            case '#pagos_autoriza_dg_cheques':
                liga = "Cuentasxp/putReferencia/";
                break;
            case '#pagos_programados':
                liga = "Cuentasxp/putReferencia_programados/";
                break;
        }

        $.post(url + liga, data).done(function(data) {
            $("#myModalconsecutivo").modal('toggle');
            switch ($("ul.nav-tabs li.active a").attr('href')) {
                case '#pagos_autoriza_dg_cheques':
                    tabla_otros.ajax.url(url + "Cuentasxp/ver_datos_autdg_otros").load();
                    break;
                case '#pagos_programados':
                    tabla_aut_programados.ajax.url(url + "Cuentasxp/ver_datos_autdg_prgo").load();
                    break;
            }
        });
    }

    function cancela_espera_chica() {
        $("#myModalEspera_chica").modal('toggle');
    }

    function cancela_espera_viaticos() {
        $("#myModalEspera_via").modal('toggle');
    }

    function cancelarr() {
        $("#myModalpoliza").modal('toggle');
    }

    function cancela_espera1() {
        $("#myModalEspera").modal('toggle');
    }

    function cancela_espera4() {
        $("#myModalEspera4").modal('toggle');
    }

    function cancela_lis() {
        $("#modalCaja").modal('toggle');
    }

    function cancelarr_ch() {
        $("#myModalpoliza_ch").modal('toggle');
    }

    function cancelarr_cheque() {
        $("#myModalconsecutivo").modal('toggle');
    }

    function cancela2() {
        $("#myModal").modal('toggle');
    }

    function acepta2(idsolicitud) {
        $("#myModal .modal-footer").html("");
        $.get(url + "Cuentasxp/terminaproceso/" + idsolicitud).done(function() {
            $("#myModal").modal('toggle');
            click_tab();
        });
    }

    function regresar(idsolicitud) {
        $("#myModalregresar .modal-footer").html("");
        $("#myModalregresar .modal-body").html("");
        $("#myModalregresar ").modal();
        $("#myModalregresar .modal-body").append("<label>¿Está seguro de regresar esta solicitud?</label>");
        $("#myModalregresar .modal-footer").append("<input type='button' class='btn btn-warning' value='Regresar DP' onclick='badsol(" + idsolicitud + ")'>");
        $("#myModalregresar .modal-footer").append("<input type='button' class='btn btn-danger' value='Cancelar'onclick='cerrar()'>");
    }

    function badsol(idsolicitud) {
        $("#myModalregresar .modal-footer").html("");
        $.get(url + "Cuentasxp/regresarcolapagos/" + idsolicitud).done(function() {
            $("#myModalregresar").modal('toggle');
            click_tab();
        });
    }

    function areaImprimir1() {
        var contenido = document.getElementById("areaImprimir").innerHTML;
        var contenidoOriginal = document.body.innerHTML;
        document.body.innerHTML = contenido;
        window.print();
        document.body.innerHTML = contenidoOriginal;
    }

    //FUNCION PARA EL PROCESAMIENTO DE CAJAS CHICAS
    function btn_caja_chica() {

        empr2_ch = document.getElementById("empresa_valor_ch").value;
        cta2_ch = document.getElementById("cuenta_valor_2ch").value;
        valor_metodo = document.getElementById("metodo_valor_ch").value;

        $.getJSON(url + "ArchivoBanco/genpbanc_caja_chica/" + empr2_ch + "/" + cta2_ch + "/" + valor_metodo + "/" + activeTab).done(function(data) {

            if (data.resultado) {

                $('#resban_caja_chica').html('<hr>Total a pagar: <b>$ ' + formatMoney(data['totpag']) + '</b><hr>');

                if (valor_metodo == 'TEA' || valor_metodo == 'ECHQ') {
                    $("#descargaPTCH").attr("href", data['file']);
                    $("a#descargaPTCH")[0].click();
                }

                click_tab();

            } else {
                $('#resban_caja_chica').html(data.mensaje);
            }
        });

    }

    //GENERA DOCUMENTO TXT PARA LA ALTA EN EL BANCO
    //FORMULARIO DE TRANSFERENCIAS
    $("#fpagos_transferencias").submit(function(e) {
        e.preventDefault();
    }).validate({
        submitHandler: function(form) {

            empr2 = $("#empresa_valor").val();
            cta2 = $("#cuenta_valor").val();
            filtro = $("#depar").val();

            filtro2 = filtro != "" ? filtro : 0;

            if (empr2 == "" && cta2 == "") {
                alert("Seleccione una de la opciones");
            } else {

                $.getJSON(url + "ArchivoBanco/genpbanc/" + empr2 + "/" + cta2 + "/" + filtro2).done(function(data) {

                    if (!data.resultado) {
                        $('#resban').html('<hr>Total a pagar: <b>$ ' + formatMoney(data['totpag']) + '</b><hr>');
                        $("#descargaPT").attr("href", data['file']);
                        $("a#descargaPT")[0].click();

                        tabla_transferencias.clear();
                        tabla_transferencias.rows.add(data.transferencias);
                        tabla_transferencias.draw();

                    } else {
                        $('#resban').html(data.mensaje);
                    }
                });
            }

        }
    });

    //PROCESAMOS LOS PAGOS PROGRAMADOS TXT TEA, CHEQUES, DOMIC Y EFEC
    $("#fpagos_programados").submit(function(e) {
        e.preventDefault();
    }).validate({
        submitHandler: function(form) {
            empr2_ch = $("#empresa_valor_prog").val();
            cta2_ch = $("#cuenta_valor_prog").val();
            valor_metodo = $("#metodo_valor_prog").val();

            $.post(url + "ArchivoBanco/genpbanc_caja_programados/" + empr2_ch + "/" + cta2_ch, {
                valor_metodo: valor_metodo
            }).done(function(data) {

                data = JSON.parse(data)

                if (data.resultado) {

                    if (data['file']) {
                        $('#resban_program').html('<hr>Total a pagar: <b>$ ' + formatMoney(data['totpag']) + '</b>');
                        $("#descargaProg").attr("href", data['file']);
                        $("a#descargaProg")[0].click();
                    }

                    tabla_aut_programados.clear();
                    tabla_aut_programados.rows.add(data.data);
                    tabla_aut_programados.draw();

                } else {
                    $('#resban_program').html(data.mensaje);
                }
            });
        }
    });

    //PROCESAMOS LOS PAGOS OTROS Y ACTUALIZAMOS AL SIGUIENTE ESTATUS
    function btn_otros() {
        empr2_ch = document.getElementById("empresa_valor_ot").value;

        $.getJSON(url + "ArchivoBanco/genpbanc_otros/" + empr2_ch).done(function(data) {

            if (!data.resultado) {
                $('#resban_otros').html('<hr>Total a pagar: <b>$ ' + formatMoney(data['totpag']) + '</b><hr>');
                $("#descargaotros").attr("href", data['file']);
                click_tab();
                $("a#descargaotros")[0].click();
            } else {
                click_tab();
                $('#resban_otros').html(data.mensaje);
            }
        });
    }

    //ACTUALIZA EL ESTATUS DE LOS PAGOS DE CAJA CHICA SELECCIONADOS
    function cambiar_estatus_tea_prog($empresa) {
        $.getJSON(url + "ArchivoBanco/actualizar_pagos_tea_pg/" + $empresa, function(data) {
            if (data) {
                // $('#myModal2_caja_chica').modal('toggle');
                click_tab();
            } else {
                alert('FALSE');
            }
        });
    }


    function cambiar_estatus_echq_prog($empresa) {
        $.getJSON(url + "ArchivoBanco/actualizar_pagos_echq_pg/" + $empresa, function(data) {
            if (data) {
                click_tab();
            } else {
                alert('FALSE');
            }
        });
    }


    function clickAndDisable_otros(link) {
        // disable subsequent clicks
        link.onclick = function(event) {
            event.preventDefault();
            alert("Ya se descargó el archivo, revise sus descargas");
        }
    }



    function autorizarSeleccionadasCajaChica() {
        var apagar = [];

        $('.selecionadoC').each(function(index, va) {

            if ($(this).is(":checked")) {
                tr = $(this).closest('tr');
                var row = tabla_caja_chica.row(tr);
                apagar.push({
                    idsolicitud: row.data().ID,
                    totalpagar: row.data().Cantidad,
                    idempresa: row.data().Empresa,
                    nomdepto: row.data().Departamento,
                    idresponsable: row.data().IDR
                });
                // alert(JSON.stringify(apagar));
            }
        });
        if (window.confirm('Se pagará el total autorizado.\nEl total es de $ ' + formatMoney(tota2) + ' ¿Estás de acuerdo?')) {
            $.post(url + "Cuentasxp/PagarTotalCajaChica", {
                jsonApagar: JSON.stringify(apagar)
            }).done(function() {
                tota2 = 0;
                $("#totpagarC").html(formatMoney(0));
                click_tab();

            });
        }
    }





    function Cambiar_TEA(idpago) {
        idpagocambiar = idpago;
        // link_post2 = "Cuentasxp/datos_para_rechazo1/";
        link_cambia1 = "Cuentasxp/cambiarmetodo";
        $("#myModalcambioTEA .modal-footer").html("");
        $("#myModalcambioTEA .modal-body").html("");
        $("#myModalcambioTEA ").modal();
        $("#myModalcambioTEA .modal-body").append("<div class='form-group col-lg-12'><select name='nuevo_metodo' id='nuevo_metodo' class='form-control' required><option value=''> -SELECCIONA OPCIÓN -</option><option value='ECHQ'>ECHQ</option><option value='MAN'>MAN</option><option value='EFEC'>EFEC</option><option value='DOMIC'>DOMIC</option><option value='FACT BAJIO'>FACT BAJIO</option><option value='FACT BANREGIO'>FACT BANREGIO</option></select></div>");
        $("#myModalcambioTEA .modal-footer").append("<div class='btn-group'><button type='submit' class='btn btn-success'>ACEPTAR</button><button type='button' class='btn btn-danger' onclick='cerrartea()'>CANCELAR</button></div>");


    }





    function Cambiar_OTRO(idpago) {
        idpagocambiar2 = idpago;
        // link_post2 = "Cuentasxp/datos_para_rechazo1/";
        link_cambia12 = "Cuentasxp/cambiarmetodoOTROS";
        $("#myModalcambiOTRO .modal-footer").html("");
        $("#myModalcambiOTRO .modal-body").html("");
        $("#myModalcambiOTRO ").modal();
        $("#myModalcambiOTRO .modal-body").append("<div class='form-group col-lg-12'><select name='nuevo_metodo2' id='nuevo_metodo2' class='form-control' required><option value=''> -SELECCIONA OPCIÓN -</option><option value='TEA'>TEA</option><option value='ECHQ'>ECHQ</option><option value='MAN'>MAN</option><option value='EFEC'>EFEC</option><option value='DOMIC'>DOMIC</option><option value='FACT BAJIO'>FACT BAJIO</option><option value='FACT BANREGIO'>FACT BANREGIO</option></select></div>");
        $("#myModalcambiOTRO .modal-footer").append("<div class='btn-group'><button type='submit' class='btn btn-success'>ACEPTAR</button><button type='button' class='btn btn-danger' onclick='cerrarOTRO()'>CANCELAR</button></div>");


    }




    function Cambiar_OTRO_fact(idpago) {
        idpagocambiar_fact = idpago;
        // link_post2 = "Cuentasxp/datos_para_rechazo1/";
        link_cambia_facto = "Cuentasxp/cambiarmetodofact";
        $("#myModalcamb_fact .modal-footer").html("");
        $("#myModalcamb_fact .modal-body").html("");
        $("#myModalcamb_fact ").modal();
        $("#myModalcamb_fact .modal-body").append("<div class='form-group col-lg-12'><select name='nuevo_factoraje' id='nuevo_factoraje' class='form-control' required><option value=''> - SELECCIONA OPCIÓN - </option><option value='TEA'>TEA</option><option value='ECHQ'>ECHQ</option><option value='MAN'>MAN</option><option value='EFEC'>EFEC</option><option value='DOMIC'>DOMIC</option><option value='FACT BAJIO'>FACT BAJIO</option><option value='FACT BANREGIO'>FACT BANREGIO</option></select></div>");
        $("#myModalcamb_fact .modal-footer").append("<div class='btn-group'><button type='submit' class='btn btn-success'>ACEPTAR</button><button type='button' class='btn btn-danger' onclick='cerrarFACT()'>CANCELAR</button></div>");


    }

    function cerrar() {
        $("#myModalregresar").modal('toggle');
    }


    function cerrartea() {
        $("#myModalcambioTEA").modal('toggle');
    }

    function cerrarOTRO() {
        $("#myModalcambiOTRO").modal('toggle');
    }

    function cerrarFACT() {
        $("#myModalcamb_fact").modal('toggle');
    }

    function generarPDF(emp, cta, filtro) {
        window.open(url + "Generar_PDFile/documentos_autorizacion/" + emp + "/" + cta + "/" + filtro, "_blank");
        alert("Se han descargado sus documentos con éxito");

        click_tab();
        $("a#descargaPT")[0].click();
    }

    function generarPDF_chica_2(data) {
        window.open(url + "Generar_PDFile3/documentos_autorizacion/" + data, "_blank");
        alert("Se han descargado sus documentos con éxito");

        click_tab();
    }

    function generarPDF_otros(data) {
        window.open(url + "Generar_PDFile2/documentos_autorizacion/" + data);
        alert("Se han descargado sus documentos con éxito");
        click_tab();

    }

    function mas_opciones(idpago, autorizado, depto) {

        $("#myModalMas .modal-header, #myModalMas .modal-body, #myModalMas .modal-footer").html("");
        $("#myModalMas").modal();

        $("#myModalMas .modal-header").append('<button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="modal-title">SELECCIONE ACCIÓN</h4><br><select id="change_options" name="change_options" class="form-control"><option value="" class="form-control">-- Selecciona opción --</option><option value="1" class="form-control">Cambiar método de pago</option><option value="3" class="form-control">Eliminar pago</option>' +
            (no_espera.includes(depto) ? '<option value="4" class="form-control">Rechazar pago Dev/Trasp</option>' : '') +
            '</select>');
        $('#change_options').on('change', function() {
            switch ($(this).val()) {
                case '1':
                    $("#myModalMas .modal-body").html('<select id="cambia_metodopago" name="cambia_metodopago" class="form-control" required><option value="" class="form-control">-- Selecciona opción --</option><option value="DOMIC" class="form-control">DOMIC</option><option value="ECHQ" class="form-control">ECHQ</option><option value="EFEC" class="form-control">EFEC</option><option value="FACT. BAJIO" class="form-control">FACT. BAJIO</option><option value="FACT. BANREGIO" class="form-control">FACT. BANREGIO</option><option value="MAN" class="form-control">MAN</option><option value="TEA" class="form-control">TEA</option></select>');
                    $("#myModalMas .modal-footer").html(" <center><button type='button' style:margin-right: 50px;' class='btn btn-success' onClick='cambia_metodo(" + idpago + ")'>CAMBIAR MÉTODO</button><button type='button' style:'margin-right: 50px;' class='btn btn-danger' onClick='cancelar_opcion()'>CANCELAR</button></center>");
                    break;
                case '2':
                    $("#myModalMas .modal-body").html('<input id="ncantidad" type="number" class="form-control">');
                    $("#myModalMas .modal-footer").html("<center><button type='button' style:margin-right: 50px;' class='btn btn-primary' onClick='editar_pago(" + idpago + ", " + autorizado + ")'>EDITAR MONTO</button><button type='button' style:'margin-right: 50px;' class='btn btn-danger' onClick='cancelar_opcion()'>CANCELAR</button></center>");
                    break;
                case '3':
                    $("#myModalMas .modal-body").html("");
                    $("#myModalMas .modal-footer").html("<center><button type='button' class='btn bg-olive' onClick='regresar_dg(" + idpago + ")'>Regresar a DG</button><button type='button' class='btn bg-orange' onClick='eliminar_pago(" + idpago + ")'>Cancelar pago / solicitud</button></center>");
                    break;
                case '4':
                    $.post(url + "Opciones/consultar_areas_proceso_menor", {
                        idpago: idpago
                    }, function(datos) {
                        data = JSON.parse(datos);
                        if (data.data) {

                            $("#myModalMas .modal-body").html('<div class="row"><div class="col-lg-12"><h5>Seleccione el área al que desea regresar este proceso</h5><div class="form-group"><label>ÁREAS</label><select class="form-control" id="idprocesos_dev_tras" name="idprocesos"><option value="">Seleccione una opción</option></select></div>' +
                                '<div class="form-group"><label>Comentario</label><textarea id="text_comentario" name="text_comentario" rows="4" class="form-control" required></textarea></div></div></div>');
                            $("#myModalMas .modal-footer").html("<center><button type='button' class='btn btn-danger btn-block' onClick='regresar_pago_dev_traps()'>RECHAZAR SOLICITUD</button></center>");
                            //@uthor Efraín Martinez Muñoz || Fecha: 31/01/2024 || Validación que permite que cuando la solicitud se encuentre en la etapa 80 y el proceso sea 30 entonces
                            //solo permitira regresar a las 2 etapas anteriores y cuando no se cumpla esta condición se regrese a todas las etapas anteriores del proceso de la solicitud. 
                            if (data.data[0].idproceso === '30' && data.data[11]){
                                $.each(data.data, function(i, v){
                                    if(parseInt(v.orden) === 12){
                                        $("#myModalMas .modal-body #idprocesos_dev_tras").append('<option  data-value="' + v.solicitud + '" value="' + v.orden + '">' + v.nombre + '</option>');
                                    }
                                })
                            }else{
                                $.each(data.data, function(i, v){
                                    $("#myModalMas .modal-body #idprocesos_dev_tras").append('<option  data-value="' + v.solicitud + '" value="' + v.orden + '">' + v.nombre + '</option>');
                                });
                            }
                        }

                    });

                    break;
                default:
                    $("#myModalMas .modal-body").html("");
                    $("#myModalMas .modal-footer").html("");
                    break;
            }
        });
    }
    //RECHAZAMOS UN PAGO DESDE EL APARTADO DE CXP
    function regresar_pago_dev_traps() {

        var data = new FormData();

        data.append("solicitud_fom", $("#myModalMas .modal-body #idprocesos_dev_tras").find('option:selected').attr("data-value"));
        data.append("radios", $("#myModalMas .modal-body #idprocesos_dev_tras").val());
        data.append("text_comentario", $("#myModalMas .modal-body #text_comentario").val());

        enviar_post2(function(respuesta) {
            if (data != false) {
                $("#myModalMas").modal('toggle');
                click_tab();
            }

        }, data, url + 'Opciones/regresar_sol_area');
    }

    function regresar_dg(idpago) {
        $("#myModalMas .modal-footer").html("");
        $.get(url + "Cuentasxp/eliminar_pagoregresar_dg/" + idpago).done(function() {
            $("#myModalMas").modal('toggle');
            click_tab();
        });
    }

    function eliminar_pago(idpago) {
        $("#myModalMas .modal-footer").html("");
        $.get(url + "Cuentasxp/eliminar_pago/" + idpago).done(function() {
            $("#myModalMas").modal('toggle');
            click_tab();
        });
    }

    var idpago;

    $(document).on('click', '.txt_ind', function(e) {

        e.preventDefault();

        idpago = $(this).attr("data-pago");
        var idempresa = $(this).attr("data-empresa");

        $(".lista_cuentas_ind").html("");

        e.preventDefault();
        $('#myModal3').modal();

        $.getJSON(url + "Listas_select/lista_cuentas2/" + idempresa).done(function(data) {
            $(".lista_cuentas_ind").append('<option value = "0">ELIJA CUENTA</option>');
            $.each(data, function(i, v) {
                $(".lista_cuentas_ind").append('<option value="' + v.idcuenta + '" data-value="' + v.idcuenta + '">' + v.nombre + " - " + v.nodecta + " - " + v.nombanco + '</option>');
            });
        });

    });

    var idpago_ot;

    $(document).on('click', '.txt_ind_ot', function(e) {

        e.preventDefault();

        idpago_ot = $(this).attr("data-pago");
        var idempresa = $(this).attr("data-empresa");

        $(".lista_cuentas_ind").html("");

        e.preventDefault();
        $('#myModal3').modal();

        $.getJSON(url + "Listas_select/lista_cuentas2/" + idempresa).done(function(data) {
            $(".lista_cuentas_ind").append('<option value = "0">ELIJA CUENTA</option>');

            $.each(data, function(i, v) {
                $(".lista_cuentas_ind").append('<option value="' + v.idcuenta + '" data-value="' + v.idcuenta + '">' + v.nombre + " - " + v.nodecta + " - " + v.nombanco + '</option>');
            });
        });

    });



    //GENERAMOS TXT INDIVIDUALES
    $('.lista_cuentas_ind').on('change', function() {
        cuenta = $(this).val();
        $.getJSON(url + "ArchivoBanco/genpbanc_individual/" + idpago + "/" + cuenta, function(data) {
            if (data.resultado) {
                $('#resbane').html('<center><h4 style="color:red;"><h5></center><center>Descarga archivo (TXT Banco)&nbsp;<a name="sendName" id="sendId" class="btn btn-info btn-xs" id  href="' + data['file'] + '"target="_blank" download><i class="fas fa-download"></i></a></h5><hr>Total: <b>$ ' + formatMoney(data['totpag']) + '</b><hr></center>');
            } else {
                $('#resbane').html(data.mensaje);
            }
        });
    });

    jQuery(document).ready(function() {
        jQuery('#myModal3').on('hidden.bs.modal', function(e) {
            jQuery(this).find('#cuenta_valor_ind').val(0);
            $("#myModal3 #resbane").html("");

        })
    })

    function clickAndDisable(link) {
        link.onclick = function(event) {
            event.preventDefault();
            alert("Ya se descargó el archivo, revise sus descargas");
        }
    }


    $(".lista_empresa").ready(function() {
        $(".lista_empresa").append('<option value="">Seleccione una opción</option>');
        $.getJSON(url + "Listas_select/lista_empresas").done(function(data) {
            $.each(data, function(i, v) {
                $(".lista_empresa").append('<option value="' + v.idempresa + '" data-value="' + v.rfc + '">' + v.nombre + '</option>');
            });
        });
    });

    function aceptar_facturaje(idpago) {
        $("#myModalAceptar .modal-footer").html("");
        $.get(url + "Cuentasxp/aceptar_facturaje/" + idpago).done(function() {
            click_tab();
        });
    }

    function cambia_metodo(idpago) {
        valor_nuevo = $("#cambia_metodopago option:selected").text();

        if (valor_nuevo == null || valor_nuevo == "" || valor_nuevo == "-- Selecciona opción --") {
            alert("Favor de seleccionar una opción")
        } else {
            $.get(url + "Cuentasxp/cambiarmetodo/" + idpago + "/" + valor_nuevo).done(function() {
                $("#myModalMas .modal-body").html("");
                $("#myModalMas .modal-footer").html("");
                $("#myModalMas .modal-body").html("");
                $("#myModalMas").modal('toggle');

                click_tab();
            });
        }

    }

    function cambiar_empresa(idpago) {
        valor_new_emp = $("#valor_empresa option:selected").data("value");
        if (valor_new_emp == null || valor_new_emp == "" || valor_new_emp == "- Selecciona empresa - ") {
            alert("Favor de seleccionar una opción")
        } else {

            $.get(url + "Cuentasxp/cambiarempresa/" + idpago + "/" + valor_new_emp).done(function() {

                $("#myModalMas .modal-body").html("");
                $("#myModalMas .modal-footer").html("");
                $("#myModalMas .modal-body").html("");
                $("#myModalMas").modal('toggle');

                click_tab();
            });
        }

    }

    function cancelar_opcion() {
        $("#myModalMas .modal-body").html("");
        $("#myModalMas .modal-footer").html("");
        $("#myModalMas").modal('toggle');
    }

    $("#tabla_autoriza_dg_fact tbody").on("click", ".cargar_cambio_FACT", function() {

        var tr = $(this).closest('tr');
        var row = tabla_facturaje.row(tr);

        idautopago = $(this).val();

        $("#modal_tipo_cambiof .modal-body").html("");
        $("#modal_tipo_cambiof .modal-body").append('<div class="row"><div class="col-lg-12"><p><b>MONEDA:</b> ' + row.data().moneda + '</p></div></div>');
        $("#modal_tipo_cambiof .modal-body").append('<div class="row"><div class="col-lg-12 form-group"><label>TIPO CAMBIO</label><div class="input-group"><span class="input-group-addon"><i class="fas fa-dollar-sign"></i></span><input class="form-control" name="tipo_cambio" required></div></div></div>');
        $("#modal_tipo_cambiof .modal-body").append('<div class="row"><div class="col-lg-8 col-lg-offset-2"><button class="btn btn-success btn-block">EDITAR</button></div></div>');
        $("#modal_tipo_cambiof").modal();
    });



    var tabla_aut_programados;
    // TABLA DE PAGOS PROGRAMADOS
    $("#tabla_autorizados_programados").ready(function() {

        $('#tabla_autorizados_programados thead tr:eq(0) th').each(function(i) {
            if (i != 0 && i != 1 && i != 11) {
                var title = $(this).text();
                $(this).html('<input type="text" style="width:100%;" placeholder="' + title + '"/>');
                $('input', this).on('keyup change', function() {
                    if (tabla_aut_programados.column(i).search() !== this.value) {
                        tabla_aut_programados
                            .column(i)
                            .search(this.value)
                            .draw();

                        var total = 0;
                        var index = tabla_aut_programados.rows({
                            selected: true,
                            search: 'applied'
                        }).indexes();
                        var data = tabla_aut_programados.rows(index).data();

                        $.each(data, function(i, v) {
                            total += parseFloat(v.autorizado);
                        });

                        var to1 = formatMoney(total);
                        document.getElementById("myText8").value = to1;
                    }
                });
            }
        });

        $('#tabla_autorizados_programados').on('xhr.dt', function(e, settings, json, xhr) {
            var total = 0;
            $.each(json.data, function(i, v) {
                total += parseFloat(v.autorizado);
            });
            var to = formatMoney(total);
            document.getElementById("myText8").value = formatMoney(total);
        });

        tabla_aut_programados = $("#tabla_autorizados_programados").DataTable({
            dom: 'Brtip',
            width: 'auto',
            "buttons": [{
                    text: '<i class="fas fa-file-invoice"></i> GENERAR DOCUMENTO BANCO',
                    action: function() {
                        $("#myModal2_progamados").modal();
                        $("#myModal2_progamados #resban").html("");
                        $("#myModal2_progamados #cuenta_valor_prog").html("<option value=''>Selecciones una opción</option>");
                        $("#myModal2_progamados .form-control").val("");
                    },
                    attr: {
                        class: 'btn bg-teal',
                        style: 'margin-right: 30px;'
                    }
                },
                {
                    extend: 'excelHtml5',
                    text: '<i class="fas fa-file-excel"></i> EXPORTAR EXCEL',
                    messageTop: "Lista de pagos autorizados por Dirección General (TRANSFERENCIAS)",
                    attr: {
                        class: 'btn bg-green',
                        style: 'margin-right: 30px;'
                    },
                    exportOptions: {
                        columns: [2, 3, 4, 5, 6, 7, 8, 9],
                        format: {
                            header: function(data, columnIdx) {
                                data = data.replace('<input type="text" style="width:100%;" placeholder="', '');
                                data = data.replace('">', '')
                                return data;
                            }
                        }
                    }
                },
                {
                    text: '<i class="fa fa-check"></i> ACEPTAR PAGOS',
                    action: function() {

                        if ($('input[name="idT[]"]:checked').length > 0) {

                            var idpago = $(tabla_aut_programados.$('input[name="idT[]"]:checked')).map(function() {
                                return this.value;
                            }).get();

                            $.get(url + "Cuentasxp/aceptocpp/" + idpago).done(function() {

                                $("#myModaltxt").modal('toggle');

                                click_tab();

                                $("#myModaltxt .modal-body").html("");
                                $("#myModaltxt").modal();
                                $("#myModaltxt .modal-body").append("<center><img style='width: 40%; height: 40%;' src= '<?= base_url('img/ver.gif') ?>'><br><b><P>ENVIADO(S) A DISPERSIÓN</P></b></center>");

                            });
                        }
                    },
                    attr: {
                        class: 'btn bg-navy',
                    }
                }
            ],
            "language": lenguaje,
            "processing": false,
            "pageLength": 50,
            "bAutoWidth": false,
            "bLengthChange": false,
            "scrollX": true,
            "bInfo": false,
            "searching": true,
            "ordering": false,
            "fixedColumns": true,
            "ordering": false,
            "columns": [{
                    "width": "4%",
                    "className": 'details-control',
                    "data": null,
                },
                {
                    "width": "4%"
                },
                {
                    "width": "8%",
                    "data": function(d) {
                        return '<p style="font-size: .9em">' + d.idsolicitud + '</p>';
                    }
                },
                {
                    "width": "18%",
                    "data": function(d) {
                        if (d.programado >= 1) {
                            return '<p style="font-size: .8em">' + d.nombre + '</p><small class="label pull-center bg-blue">PROGRAMADO</small>';
                        } else {
                            return '<p style="font-size: .8em">' + d.nombre + '</p>';
                        }
                    }
                },
                {
                    "width": "8%",
                    "data": function(d) {
                        return '<p style="font-size: .9em">$ ' + formatMoney(d.autorizado) + ' ' + d.moneda + '</p>';
                    }
                },
                {
                    "width": "10%",
                    "data": function(d) {
                        if (d.intereses == 1) {
                            if (d.interes > 0) {
                                return '<p style="font-size: .9em">$ ' + formatMoney(d.interes) + ' MXN</p><small class="label pull-center bg-purple">CRÉDITO</small>';
                            } else {
                                return '<small class="label pull-center bg-gray">Agregar interés</small>';
                            }
                        } else {
                            return '<p style="font-size: .9em"> - </p>';
                        }
                    }
                },
                {
                    "width": "8%",
                    "data": function(d) {


                        return '<p style="font-size: .9em">$ ' + formatMoney(parseFloat(d.autorizado) + (parseFloat(d.interes) > 0 ? parseFloat(d.interes) : 0)) + ' MXN</p>';
                    }
                },

                {
                    "width": "8%",
                    "data": function(d) {
                        if ((d.metoPago == "ECHQ" || d.metoPago == "EFEC") && d.referencia != '' && d.referencia != null) {
                            return '<p style="font-size: .9em">' + d.metoPago + ' <small class="label bg-blue">' + d.referencia + '</small></p>';
                        } else {
                            return '<p style="font-size: .9em">' + d.metoPago + '</p>';

                        }
                    }
                },
                {
                    "width": "10%",
                    "data": function(d) {
                        return '<p style="font-size: .9em">' + d.fecelab + '</p>';
                    }
                },
                {
                    "width": "10%",
                    "data": function(d) {
                        return '<p style="font-size: .9em">' + d.nomdepto + '</p>';
                    }
                },
                {
                    "width": "10%",
                    "data": function(d) {
                        return '<p style="font-size: .9em">' + d.nemp + '</p>';
                    }
                },
                {
                    "orderable": false,
                    "data": function(data) {
                        opciones = '<div class="btn-group-vertical" role="group">';

                        opciones += '<div class="btn-group-vertical"><button type="button" class="btn btn-primary consultar_modal notification" value="' + data.idsolicitud + '" data-value="SOL" title="Ver Solicitud"><i class="fas fa-eye"></i></button></div>';

                        switch (data.estatus) {
                            case '11':
                                opciones = '<small class="label pull-right bg-navy">Listo para aceptar pago</small>';
                                opciones += '<div class="btn-group-vertical" role="group">';
                                if (data.metoPago == 'ECHQ') {
                                    opciones += '&nbsp;<button type="button" class="txt_ind_ot btn bg-teal btn-sm"  data-pago="' + data.idpago + '" data-empresa="' + data.idEmpresa + '" style="margin-right: 5px;" title="Descargar TXT Individual"><i class="fa fa-download"></i></button>';
                                }
                                break;
                        }

                        opciones += '<button type="button" class="btn btn-warning btn-sm" onClick="mandar_espera(' + data.idpago + ')" title="Mandar a espera" style="margin-right: 5px;"><i class="fas fa-clock"></i></button>';
                        opciones += '<button type="button" class="btn btn-sm bg-maroon" onClick="mas_opciones(' + data.idpago + ', 3, \'' + data.nomdepto + '\' )" title="Más opciones" style="margin-right: 5px;"><i class="fa fa-plus-circle"></i></button>';

                        if (data.moneda != 'MXN' && data.intereses != 1) {
                            opciones += '<button type="button" style="margin-right: 5px;" class="cargar_cambio2_2 btn bg-olive btn-sm" value="' + data.idpago + '" title="Ingresar interés"><i class="fas fa-money-check-alt"></i></button>';
                        }

                        if (data.metoPago == 'ECHQ' || data.metoPago == 'EFEC') {
                            opciones += '<button type="button"  style="margin-right: 5px;" class="btn bg-blue btn-sm" onClick="ingresar_consecutivo(' + data.idpago + ', \'' + data.metoPago + '\');" title="Ingresar consecutivo de cheque" ><i class="fa fa-pencil-square-o"></i></button>';
                        }

                        if (data.intereses == 1 && data.moneda == 'MXN') {
                            opciones += '<button type="button" style="margin-right: 5px;" class="cargar_interes_ot btn btn-sm  bg-red" value="' + data.idpago + '" title="Ingresar interés"><i class="fa fa-percent"></i></button>';
                        }
                        return opciones + '</div>';
                    }
                }
            ],
            columnDefs: [{
                    "searchable": false,
                    "orderable": false,
                    "targets": 0
                },
                {
                    orderable: false,
                    className: 'select-checkbox',
                    targets: 1,
                    'searchable': false,
                    'className': 'dt-body-center',
                    'render': function(d, type, full, meta) {
                        return full.estatus == 11 ? '<input type="checkbox" name="idT[]" style="width:20px;height:20px;"  value="' + full.idpago + '">' : '';
                    },
                    select: {
                        style: 'os',
                        selector: 'td:first-child'
                    },
                }
            ],
            /*
            "ajax": {
                "url": url + "Cuentasxp/ver_datos_autdg_prgo",
                "type": "POST",
                cache: false,
                "data": function( d ){
                }
            },*/
            "order": [
                [1, 'asc']
            ]
        });

        $('#tabla_autorizados_programados tbody').on('click', 'td.details-control', function() {
            var tr = $(this).closest('tr');
            var row = tabla_aut_programados.row(tr);

            if (row.child.isShown()) {
                row.child.hide();
                tr.removeClass('shown');
                $(this).parent().find('.animacion').removeClass("fa-caret-down").addClass("fa-caret-right");
            } else {

                var informacion_adicional = '<table class="table text-justify">' +
                    '<tr>' +
                    '<td style="font-size: .8em"><strong>JUSTIFICACIÓN: </strong>' + row.data().justificacion + '</td>' +
                    '</tr>' +
                    '</table>';

                row.child(informacion_adicional).show();
                tr.addClass('shown');
                $(this).parent().find('.animacion').removeClass("fa-caret-right").addClass("fa-caret-down");
            }
        });

        tabla_aut_programados.on('search.dt order.dt', function() {
            tabla_aut_programados.column(0, {
                search: 'applied',
                order: 'applied'
            }).nodes().each(function(cell, i) {
                cell.innerHTML = i + 1;
            });
        }).draw();

        $("#tabla_autorizados_programados tbody").on("click", ".cancelar_pago_solicitud", function() {
            $.post(url + "Cuentasxp/regresar_pago", {
                idpago: $(this).val()
            }).done(function() {
                click_tab();
            });
        });

        //TIPO DE CAMBIO DE MONEDA
        $("#tabla_autorizados_programados tbody").on("click", ".cargar_cambio", function() {

            row_transferencia = $(this).closest('tr');
            var row = tabla_aut_programados.row(row_transferencia);

            idautopago = $(this).val();

            $("#modal_tipo_cambio .modal-body").html("");
            $("#modal_tipo_cambio .modal-body").append('<div class="row"><div class="col-lg-12"><p><b>MONEDA:</b> ' + row.data().moneda + '</p></div></div>');
            $("#modal_tipo_cambio .modal-body").append('<div class="row"><div class="col-lg-12 form-group"><label>TIPO CAMBIO</label><div class="input-group"><span class="input-group-addon"><i class="fas fa-dollar-sign"></i></span><input type="number" class="form-control" name="tipo_cambio" required></div></div></div>');
            $("#modal_tipo_cambio .modal-body").append('<div class="row"><div class="col-lg-8 col-lg-offset-2"><button class="btn bg-olive btn-block">EDITAR</button></div></div>');
            $("#modal_tipo_cambio").modal();
        });

        //CARGAR INTERES POR SE UNA FACTURA DE CREDITO
        $("#tabla_autorizados_programados tbody").on("click", ".cargar_interes", function() {
            row_transferencia = $(this).closest('tr');
            var row = tabla_aut_programados.row(row_transferencia);

            idautopago = $(this).val();

            $("#modal_intereses .modal-body").html("");
            $("#modal_intereses .modal-body").append('<div class="row"><div class="col-lg-12"><p><b>Cantidad:</b> $' + formatMoney(row.data().cantidad) + '</p></div></div>');
            $("#modal_intereses .modal-body").append('<div class="row"><div class="col-lg-12 form-group"><label>INTÉRES</label><div class="input-group"><span class="input-group-addon"><i class="fas fa-dollar-sign"></i></span><input type="number" class="form-control" name="valor_interes" placeholder="Cantidad en $" required></div></div></div>');
            $("#modal_intereses .modal-body").append('<div class="row"><div class="col-lg-8 col-lg-offset-2"><button class="btn bg-green btn-block" data-table="programado">ACEPTAR</button></div></div>');
            $("#modal_intereses").modal();
        });

    });
    // FIN TABLA PAGOS PROGRAMADOS

    $(window).resize(function() {
        tabla_transferencias.columns.adjust();
        tabla_otros.columns.adjust();
        tabla_caja_chica.columns.adjust();
        tabla_aut_programados.columns.adjust();
        tabla_pausados.columns.adjust();
    });

    function click_tab() {
        $('a[data-toggle="tab"][data-value="' + activeTab + '"]').click();
    }

    
     //FUNCION PARA EL PROCESAMIENTO DE VIÁTICOS
    function btn_viaticos () {
        empr2_ch = document.getElementById("empresa_valor_viaticos").value;
        cta2_ch = document.getElementById("cuenta_valor_viaticos").value;
        valor_metodo = document.getElementById("metodo_valor_viaticos").value;

        $.getJSON(url + "ArchivoBanco/genpbanc_viaticos/" + empr2_ch + "/" + cta2_ch + "/" + valor_metodo + "/" + activeTab).done(function(data) {

            if (data.resultado) {

                $('#resban_viaticos').html('<hr>Total a pagar: <b>$ ' + formatMoney(data['totpag']) + '</b><hr>');

                if (valor_metodo == 'TEA' || valor_metodo == 'ECHQ') {
                    $("#descargaPTCH").attr("href", data['file']);
                    $("a#descargaPTCH")[0].click();
                }

                click_tab();

            } else {
                $('#resban_viaticos').html(data.mensaje);
            }
        });

    }
 </script>
 <?php
    require("footer.php");
    ?>