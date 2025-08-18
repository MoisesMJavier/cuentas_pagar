<?php
    require("head.php");
    require("menu_navegador.php");
?> 
<style>
    /* Fondo con efecto glass y desenfoque */
    #loader-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        backdrop-filter: blur(8px);
        background-color: rgba(255, 255, 255, 0.3);
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .loader-container {
        position: relative;
        z-index: 1;
        text-align: center;
        padding: 60px;
        width: 400px;
        min-height: 300px;
        background: rgba(255, 255, 255, 0.15);
        border-radius: 20px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
    }

    .loader-text {
        font-size: 1.2em;
        color: #222;
        font-weight: 500;
        margin-top: 1.5em;
        min-height: 2.5em;
        white-space: pre; /* IMPORTANTE para conservar espacios */
    }
</style>
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <div class="box">
                    <div class="box-header with-border">
                        <h3>HISTORIAL</h3>
                    </div>
                    <div class="box-body">
                        <!--div class="row">
                            <div class="col-lg-12">-->
                                <div class="nav-tabs-custom">
                                    <ul class="nav nav-tabs">
                                        <li class="active"><a id="historial_activas_prov" data-toggle="tab" href="#historial_activas_prov-tab" role="tab" aria-controls="#historial_activas_prov" aria-selected="true">PROVEEDOR</a></li>
                                        <li><a id="historial_activas_cch" data-toggle="tab" href="#historial_activas_prov-tab" aria-controls="#historial_activas_prov" aria-selected="true">CAJAS CHICAS</a></li>
                                        <li><a id="historial_activas_tdc" data-toggle="tab" href="#historial_activas_prov-tab" aria-controls="#historial_activas_prov" aria-selected="true">TARJETAS DE CRÉDITO </a></li>
                                        <li><a id="historial_activas_viaticos" data-toggle="tab" href="#historial_activas_prov-tab" aria-controls="#historial_activas_prov" aria-selected="true">VIÁTICOS</a></li>
                                        <li><a id="listado_pagos-tab" data-toggle="tab" href="#listado_pagos" role="tab" aria-controls="#listado_pagos" aria-selected="true">PAGOS PROVEEDOR</a></li>
                                        <li><a id="listado_cch-tab" data-toggle="tab" href="#listado_cch" role="tab" aria-controls="#listado_cch" aria-selected="true">CAJAS CHICAS REEMBOLSADAS</a></li>
                                        <li><a id="listado_aut_yola" data-toggle="tab" href="#listado_aut_yola-tab" role="tab" aria-controls="#listado_aut_yola" aria-selected="true">AUTORIZACIONES DE CONTABILIDAD</a></li>
                                        <?php 
                                            if(in_array($this->session->userdata("inicio_sesion")['id'], array(219, 224, 1901, 2086, 223, 2109, 2609, 2517, 2615, 2429)))
                                                echo '<li><a id="listado_pv-tab" data-toggle="tab" href="#listado_pv" role="tab" aria-controls="#listado_pv" aria-selected="true">TRÁMITES PV</a></li>'
                                        ?>
                                    </ul>
                                </div>

                                <div class="tab-content">
                                    <div class="active tab-pane" id="historial_activas_prov-tab">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <p><b>Considere lo siguiente:</b> El rango de fechas aplicado en este filtro, corresponde a la fecha de la factura en la solicitud de pago.</p>
                                                <!-- <form method="post" id="formfiltro" target="_blank" action="Reportes/reporte_historial_solicitudes" autocomplete="off">  -->
                                                    <div class="col-lg-3">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fas fa-calendar-alt"></i> <b>INICIO</b></span>
                                                            <input class="form-control fechas_filtro from" type="text" id="datepicker_from" name="fechaInicial"/>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fas fa-calendar-alt"></i> <b>FIN</b></span>
                                                            <input class="form-control fechas_filtro to" type="text" id="datepicker_to" name="fechaFinal"/>
                                                        </div>
                                                    </div>
                                                    <div id="inputs_hidden"></div>
                                                <!-- </form> -->
                                                <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12"> <!-- INICIO FECHA: 07-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <div class="input-group-addon bg-gray text-bold">TOTAL: $</div>
                                                            <input class="form-control text-bold" style="background-color: white; font-size: 16px; cursor: default;" disabled="disabled" readonly="readonly" type="text"  name="myText_1" id="myText_1">
                                                        </div>
                                                    </div>
                                                </div>  <!-- FIN FECHA: 07-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                                                
                                                <table class="table table-striped" id="tablahistorialsolicitudes">
                                                    <thead>
                                                        <tr>
                                                            <th></th>                                         <!-- COLUMNA [ 0 ] -->
                                                            <th style="font-size: .9em">#</th>                <!-- COLUMNA [ 1 ] -->
                                                            <th style="font-size: .9em">PROYECTO</th>         <!-- COLUMNA [ 2 ] FECHA: 10-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com>-->
                                                            <th style="font-size: .9em">SERVICIO/PARTIDA</th> <!-- COLUMNA [ 3 ] FECHA: 10-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com>-->
                                                            <th style="font-size: .9em">OFICINA/SEDE</th>     <!-- COLUMNA [ 4 ] FECHA: 10-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com>-->
                                                            <th style="font-size: .9em">EMPRESA</th>          <!-- COLUMNA [ 5 ] -->
                                                            <th style="font-size: .9em">FECHA_CAPTURA</th>    <!-- COLUMNA [ 6 ] -->
                                                            <th style="font-size: .9em">FEC_FAC</th>          <!-- COLUMNA [ 7 ] -->
                                                            <th style="font-size: .9em">FOLIO</th>            <!-- COLUMNA [ 8 ] -->
                                                            <th style="font-size: .9em">FECHA PAGO</th>       <!-- COLUMNA [ 9 ] -->
                                                            <th style="font-size: .9em">PROVEEDOR</th>        <!-- COLUMNA [ 10 ] -->
                                                            <th style="font-size: .9em">DEPARTAMENTO</th>     <!-- COLUMNA [ 11 ] -->
                                                            <th style="font-size: .9em">CANTIDAD</th>         <!-- COLUMNA [ 12 ] -->
                                                            <th style="font-size: .9em">FORMA_PAGO</th>       <!-- COLUMNA [ 13 ] -->
                                                            <th style="font-size: .9em">CAPTURISTA</th>       <!-- COLUMNA [ 14 ] -->
                                                            <th style="font-size: .9em">ESTATUS</th>          <!-- COLUMNA [ 15 ] -->
                                                            <th style="font-size: .9em">TIPO</th>             <!-- COLUMNA [ 16 ] -->
                                                            <th></th>                                         <!-- COLUMNA [ 17 ] -->
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                        <div class="tab-pane" id="listado_pagos">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="col-lg-3">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fas fa-calendar-alt"></i> <b>INICIO</b></span>
                                                                <input class="form-control fechas_filtro from" type="text" id="datepicker_fromP" />
                                                            </div>
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fas fa-calendar-alt"></i> <b>FIN</b></span>
                                                                <input class="form-control fechas_filtro to" type="text" id="datepicker_toP" />
                                                            </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="input-group-addon" style="padding: 4px;">
                                                            <h4 style="padding: 0; margin: 0;"><label>&nbsp;Total: $<input style="text-align: right; border-bottom: none; border-top: none; border-right: none;  border-left: none; background: white;" disabled="disabled" readonly="readonly" type="text"  name="myText_2" id="myText_2"></label></h4>
                                                        </div>
                                                    </div>
                                                    <table class="table table-striped" id="tablahistorialpagos">
                                                        <thead>
                                                            <tr>    
                                                                <th style="font-size: .9em">#</th>              <!-- COLUMNA[ 0 ] -->
                                                                <th style="font-size: .9em">FOLIO</th>          <!-- COLUMNA[ 1 ] -->
                                                                <th style="font-size: .9em">F FISCAL</th>       <!-- COLUMNA[ 2 ] --> 
                                                                <th style="font-size: .9em">PROVEEDOR</th>      <!-- COLUMNA[ 3 ] -->
                                                                <th style="font-size: .9em">F AUTORIZADO</th>   <!-- COLUMNA[ 4 ] -->
                                                                <th style="font-size: .9em">PROVEEDOR</th>      <!-- COLUMNA[ 5 ] -->
                                                                <th style="font-size: .9em">DEPARTAMENTO</th>   <!-- COLUMNA[ 6 ] -->
                                                                <th style="font-size: .9em">CANTIDAD</th>       <!-- COLUMNA[ 7 ] -->
                                                                <th style="font-size: .9em">EMPRESA</th>        <!-- COLUMNA[ 8 ] -->
                                                                <th style="font-size: .9em">F DISPERSADO</th>   <!-- COLUMNA[ 9 ] -->
                                                                <th style="font-size: .9em">ESTATUS</th>        <!-- COLUMNA[ 10 ] -->
                                                                <th style="font-size: .9em">FORM PAGO</th>      <!-- COLUMNA[ 11 ] -->
                                                                <th style="font-size: .9em">REFERENCIA</th>     <!-- COLUMNA[ 12 ] -->
                                                                <th style="font-size: .9em">JUSTIFICACION</th>  <!-- COLUMNA[ 13 ] -->
                                                                <th style="font-size: .9em">CAPTURISTA</th>     <!-- COLUMNA[ 14 ] -->
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane" id="listado_cch">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="col-lg-3">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fas fa-calendar-alt"></i> <b>INICIO</b></span>
                                                                <input class="form-control fechas_filtro from" type="text" id="datepicker_fromC" />
                                                            </div>
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fas fa-calendar-alt"></i> <b>FIN</b></span>
                                                                <input class="form-control fechas_filtro to" type="text" id="datepicker_toC" />
                                                            </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="input-group-addon" style="padding: 4px;">
                                                            <h4 style="padding: 0; margin: 0;"><label>&nbsp;Total: $<input style="text-align: right; border-bottom: none; border-top: none; border-right: none;  border-left: none; background: white;" disabled="disabled" readonly="readonly" type="text"  name="myText_3" id="myText_3"></label></h4>
                                                        </div>
                                                    </div>
                                                    <table class="table table-striped" id="tablahistorialcch">
                                                        <thead>
                                                            <tr>     
                                                            <th style="font-size: .7em"></th>                       <!-- COLUMNA[ 0 ] -->
                                                            <th style="font-size: .7em">RESPONSABLE</th>            <!-- COLUMNA[ 1 ] -->
                                                            <th style="font-size: .7em">RESPONSABLE REEMBOLSO</th>  <!-- COLUMNA[ 2 ] /** FECHA: 29-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/-->
                                                            <th style="font-size: .7em">EMPRESA</th>                <!-- COLUMNA[ 3 ] -->
                                                            <th style="font-size: .7em">TOTAL</th>                  <!-- COLUMNA[ 4 ] -->
                                                            <th style="font-size: .7em">FECHA</th>                  <!-- COLUMNA[ 5 ] -->
                                                            <th style="font-size: .7em">MÉTODO PAGO</th>            <!-- COLUMNA[ 6 ] -->
                                                            <th style="font-size: .7em">F COBRO</th>                <!-- COLUMNA[ 7 ] -->
                                                            <th style="font-size: .7em">DEPTO</th>                  <!-- COLUMNA[ 8 ] -->
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="tab-pane" id="listado_aut_yola-tab">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="col-lg-3">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fas fa-calendar-alt"></i> <b>INICIO</b></span>
                                                                <input class="form-control fechas_filtro from" type="text" id="datepicker_fromA"  />
                                                            </div>
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fas fa-calendar-alt"></i> <b>FIN</b></span>
                                                                <input class="form-control fechas_filtro to" type="text" id="datepicker_toA"  />
                                                            </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="input-group-addon" style="padding: 4px;">
                                                            <h4 style="padding: 0; margin: 0;"><label>&nbsp;Total: $<input style="text-align: right; border-bottom: none; border-top: none; border-right: none;  border-left: none; background: white;" disabled="disabled" readonly="readonly" type="text"  name="myText_4" id="myText_4"></label></h4>
                                                        </div>
                                                    </div>
                                                    <table class="table table-striped" id="tablahistorialautorizaciones">
                                                        <thead>
                                                            <tr>   
                                                            <th style="font-size: .7em">#</th>
                                                            <th style="font-size: .7em">PROYECTO</th>
                                                            <th style="font-size: .7em">FOLIO</th>
                                                            <th style="font-size: .7em">EMPRESA</th>
                                                            <th style="font-size: .7em">FECHA AUTORIZACION</th>
                                                            <th style="font-size: .7em">PROVEEDOR</th>
                                                            
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane" id="listado_pv">
                                            <!-- Ticket #76788 Folios en reporte de trámites PV | FECHA: 18-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                                            <div class="col-lg-8" style="margin-top: 2px; margin-bottom: 2px;"> 
                                                <div class="row" id="mesesSeleccionados">
                                                    <div class="col-lg-1 col-md-3 col-sm-3 col-xs-6">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                <input type="checkbox" value="01" class="month" aria-label="Enero">Ene
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-1 col-md-3 col-sm-3 col-xs-6">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                <input type="checkbox" value="02" class="month" aria-label="Febrero">Feb
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-1 col-md-3 col-sm-3 col-xs-6">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                <input type="checkbox" value="03" class="month" aria-label="Marzo">Mar
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-1 col-md-3 col-sm-3 col-xs-6">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                <input type="checkbox" value="04" class="month" aria-label="Abril">Abr
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-1 col-md-3 col-sm-3 col-xs-6">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                <input type="checkbox" value="05" class="month" aria-label="Mayo">May
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-1 col-md-3 col-sm-3 col-xs-6">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                <input type="checkbox" value="06" class="month" aria-label="Junio">Jun
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-1 col-md-3 col-sm-3 col-xs-6">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                <input type="checkbox" value="07" class="month" aria-label="Julio">Jul
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-1 col-md-3 col-sm-3 col-xs-6">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                <input type="checkbox" value="08" class="month" aria-label="Agosto">Ago
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-1 col-md-3 col-sm-3 col-xs-6">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                <input type="checkbox" value="09" class="month" aria-label="Septiembre">Sep
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-1 col-md-3 col-sm-3 col-xs-6">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                <input type="checkbox" value="10" class="month" aria-label="Octubre">Oct
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-1 col-md-3 col-sm-3 col-xs-6">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                <input type="checkbox" value="11" class="month" aria-label="Noviembre">Nov
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-1 col-md-3 col-sm-3 col-xs-6">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                <input type="checkbox" value="12" class="month" aria-label="Diciembre">Dic
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-2 col-md-6 col-sm-6 col-xs-12">
                                                <div class="row">
                                                    <div class="col-lg-10">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                                            <select class="form-control" name="select" id="anio"></select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <table class="table table-striped" id="tablahistorialpv">
                                                <thead>
                                                    <tr>
                                                        <th style="font-size: .9em">#</th>
                                                        <th style="font-size: .9em">FOLIO</th>
                                                        <th style="font-size: .9em">USUARIO</th>
                                                        <th style="font-size: .9em">DESARROLLO</th>
                                                        <th style="font-size: .9em">LOTE</th>
                                                        <th style="font-size: .9em">RECHAZOS</th>
                                                        <th style="font-size: .9em">F CONTRALORÍA</th>
                                                        <th style="font-size: .9em">F AUT CONTRALORÍA</th>
                                                        <th style="font-size: .9em">ETAPA</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                            <!-- FIN Ticket #76788 Folios en reporte de trámites PV | FECHA: 18-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                                        </div>

                                    </div>
                                </div>
                            <!--</div>
                        </div>-->
                        <!-- INICIO LOADER DE PROGRESO -->
                        <div id="loader-overlay" style="display:none;">
                            <div class="loader-container">
                                <div id="lottie-animation" style="width: 160px; height: 160px; margin: 0 auto;"></div>
                                <p class="loader-text" id="animated-text">Generando archivo Excel...</p>
                            </div>
                        </div>
                        <!-- FIN LOADER DE PROGRESO -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="modal_comentarios_especiales" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">OPCIONES ADICIONALES</h4>
            </div>
            <div class="modal-body">
                <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" href="#contrato" >ASIGNAR CONTRATO</a></li>
                    <li><a data-toggle="tab" href="#financiamiento" >FINANCIAMIENTO</a></li>
                </ul>
                <div class="tab-content">
                    <!-- Fomrulario de asignar contrato, posicionado dentro del modal -->
                    <div id="contrato" class="tab-pane fade in active">
                        <form id="formulario_contrato" method="post">
                            <div class="row">
                                <div class="col-lg-12 form-group">
                                    <label>CONTRATO<span class="text-danger">*</span></label>
                                    <select id="select_proyecto_by_proveedor" class="form-control" name="nombre_proyecto" required>
                                        <option value="">Seleccione un opción</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-8 col-lg-offset-2 form-group">
                                    <button type="submit" class="btn btn-block btn-success">ASIGNAR</button>
                                </div>
                            </div>
                        </form>
                    </div>
                     <!-- Financiamiento -->
                     <div id="financiamiento" class="tab-pane fade">
                        <form id="formulario_financ" method="post">
                            <div class="row">
                                <div class="col-lg-12 form-group">
                                    <label>FINANCIAMIENTO<span class="text-danger">*</span></label> 
                                    <select id="financ" class="form-control financ" name="financ" required>
                                        <option value="">Seleccione un opción</option>
                                        <option value="1">SI</option>
                                        <option value="0">N/A</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-8 col-lg-offset-2 form-group">
                                    <button type="submit" class="btn btn-block btn-success">APLICAR</button>
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

    var tabla_historial_solicitudes;
    var tabla_historial_pagos;
    var tabla_historial_cch;
    var tabla_autorizacion_yola;
    var tabla_reporte_pv;
    var inicial;
    var final;
    var tabla_historial_pv;
    var tipo_solicitud = 0;
    var tipo_reporte;
    var valor_input = Array( $('#tablahistorialsolicitudes th').length );

    // Frases con espacios iniciales (usa \u00A0 para espacio no colapsable)
    const frases = [
        "  Generando archivo Excel...",
        "  Preparando datos...",
        "  Por favor, espera un momento...",
        "  Optimizando archivo final..."
    ];

    let indexFrase = 0;
    let indexLetra = 0;
    const animatedText = document.getElementById('animated-text');
    
    const filtrosPorTabla = {
        tablahistorialpv: function (aData) {
            const mesesSeleccionados = [];
            $('input[type="checkbox"]:checked').each(function () {
                mesesSeleccionados.push(parseInt($(this).val()));
            });

            const anio = $('#anio').val();
            const meses = ['', 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];

            if (!aData[6]) return false;

            // aData[5] = "F CONTRALORÍA", ej. "14/May/2024"
            const mesTexto = aData[6].split('/')[1];
            const dia = aData[6].substring(0, 2);
            const anioTexto = aData[6].substring(7, 11);
            const mesNum = meses.indexOf(mesTexto).toString().padStart(2, "0");
            const fechaFormateada = anioTexto + mesNum + dia;

            if (mesesSeleccionados.length === 0 && anio === 'T') {
                return true;
            } else if (mesesSeleccionados.length > 0 && anio === 'T') {
                return mesesSeleccionados.some(mes => fechaFormateada.substring(4, 6) === mes.toString().padStart(2, '0'));
            } else if (mesesSeleccionados.length === 0 && anio !== 'T') {
                return fechaFormateada.startsWith(anio);
            } else {
                return mesesSeleccionados.some(mes => {
                    const mesTexto = mes.toString().padStart(2, '0');
                    return fechaFormateada.startsWith(anio + mesTexto);
                });
            }
        },
        tablahistorialsolicitudes: function (aData) {
            return filtroRangoFechas(aData, 7, 7);
        },
        tablahistorialpagos: function (aData) {
            return filtroRangoFechas(aData, 4, 4);
        },
        tablahistorialcch: function (aData) {
            return filtroRangoFechas(aData, 5, 5);
        },
        tablahistorialautorizaciones: function (aData) {
            return filtroRangoFechas(aData, 4, 4);
        }
    };
    
    $('[data-toggle="tab"]').off('click').click( function(e) {
        e.preventDefault(); // Evita que Bootstrap maneje el clic
        const idTab = "#"+$(this).attr('id');
        $(".fechas_filtro").val('');
        rangoFechasFiltro(idTab);
        switch( idTab ){
            case '#historial_activas_prov':
                tipo_solicitud = 0;
                tabla_historial_solicitudes.ajax.reload();
                break;
            case '#historial_activas_cch':
                tipo_solicitud = 1;
                tabla_historial_solicitudes.ajax.reload();
                break;
            case '#historial_activas_tdc':
                tipo_solicitud = 2;
                tabla_historial_solicitudes.ajax.reload();
                break;    
            case '#listado_pagos-tab':
                tabla_historial_pagos.ajax.url( url +"Historial/TablaHPagosDepartmento" ).load();
                break;
            case '#listado_cch-tab':
                tabla_historial_cch.ajax.url( url +"Historial/tabla_cajas_chicas" ).load();
                break;
            case '#listado_aut_yola':
                tabla_autorizacion_yola.ajax.url( url +"Historial/autorizacion_yola").load();
                break;   
            case '#listado_pv-tab':
                tabla_historial_pv.ajax.url( url +"Historial/TablaHistorialSolicitudesPV" ).load();
                break;
            case '#historial_activas_viaticos':
                tipo_solicitud = 4;
                tabla_historial_solicitudes.ajax.reload();
        }
    });

    $(document).ready(function() {
        var year = new Date().getFullYear();
        var select = $('#anio')
        select.append($('<option value="T">TODOS</option>'))
        for(var i = year ; i >= 2021; i--){
            select.append($('<option value='+i+'>'+i+'</option>'))
        }
        rangoFechasFiltro('#historial_activas_prov');

        // Animación Lottie desde archivo local o URL
        lottie.loadAnimation({
            container: document.getElementById("lottie-animation"),
            renderer: "svg",
            loop: true,
            autoplay: true,
            path: "<?= base_url('img/Animation-descarga.json') ?>" // Ruta local en tu servidor
        });
    });
    
   
    $('#tablahistorialsolicitudes').on('xhr.dt', function ( e, settings, json, xhr ) {
         let total = 0;
         $.each( json.data,  function(i, v){
           total += parseFloat(v.cantidad);
         });
         let to = formatMoney(total);
        document.getElementById("myText_1").value = to;
        
    });
    
    $("#tablahistorialsolicitudes").ready( function(){
        var inputs_filtro= ["o","idsol","proyecto","empresa","fecha_fac","folio","fecha_pago","f_fisc","proveedor","cantidad","justificacion","forma_pago","responsable","estatus","tipo","con_factura" ];
        $('#tablahistorialsolicitudes thead tr:eq(0) th').each( function (i) {
            if( $(this).text() != '' && i!=0){
                var title = $(this).text();
                $(this).html( '<input type="text" class="form-control" style="font-size: .9em; width: 100%;" data-value="'+title+'" placeholder="'+title+'" name="'+title.toString().replace(" ","_").toLowerCase()+'" />' ); /** FECHA: 07-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/

                // valor_input = Array( $('#tablahistorialsolicitudes thead tr:eq(0) input').length );

                $( 'input', this ).on('keyup change', function () {

                    // valor_input[$(this).data('value')] = this.value;

                    if (tabla_historial_solicitudes.column(i).search() !== this.value ) {
                        tabla_historial_solicitudes
                            .column(i)
                            .search( this.value)
                            .draw();
                            valor_input[title] = this.value;
                           var total = 0;
                           var index = tabla_historial_solicitudes.rows( { selected: true, search: 'applied' } ).indexes();
                           var data = tabla_historial_solicitudes.rows( index ).data();
                           $.each(data, function(i, v){
                               total += parseFloat(v.cantidad);
                           });
                           var to1 = formatMoney(total);
                           document.getElementById("myText_1").value = to1;
                    }
                });
            }
        });
        
        tabla_historial_solicitudes = $("#tablahistorialsolicitudes").DataTable({
            dom: 'Brtip',
            "buttons": [
                {
                    text: '<i class="fas fa-file-excel"></i> EXPORTAR',
                    messageTop: "Descarga de Excel",
                    attr: {
                        class: 'btn btn-success',
                        id: 'exportarExcel'
                    },
                    action: function(){
                        tipo_reporte = "#" + $('.nav-tabs li.active a').attr('id');
                        descargaReporteExcel();
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
            "order": [[1, "ASC"]], /** FECHA: 10-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
            "bSort": false, /** FECHA: 10-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
            "columns": [
                {
                    "width": "1%",
                    "className": 'details-control',
                    "orderable": false,
                    "data" : null,
                    "defaultContent": '<i class="fas animacion fa-caret-right"></i>'
                },
                {
                    "width": "4%",
                    "data" : function( d ){
                        return '<p style="font-size: .7em">'+d.idsolicitud+'</p>'
                    }
                },
                {
                    "width": "8%", /** FECHA: 10-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                    "data" : function( d ){
                        return '<p style="font-size: .7em">'+d.proyecto+'</p>'
                    }
                },
                { /** INICIO FECHA: 10-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                    "width": "8%",
                    "data" : function( d ){
                        return '<p style="font-size: .7em">'+(d.servicioPartida ? d.servicioPartida : 'NA')+'</p>'
                    }
                },
                {
                    "width": "8%",
                    "data" : function( d ){
                        return '<p style="font-size: .7em">'+(d.oficina ? d.oficina : 'NA')+'</p>'
                    }
                }, /** FIN FECHA: 10-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                {
                    "width": "4%",
                    "data" : function( d ){
                        return '<p style="font-size: .7em">'+d.abrev+'</p>'
                    }
                },
                {
                    "data" : function( d ){
                        return formato_fechaymd(d.feccrea);
                    }
                },
                {
                    "width": "8%",
                    "data" : function( d ){
							return '<p style="font-size: .7em"><b>'+(d.fecelab? formato_fechaymd(d.fecelab): "")+'</b><br></p>';
					}
                },
                {
                    "width": "5%",
                    "data" : function( d ){
                        return '<p style="font-size: .7em">'+(d.folio ? d.folio : "")+(d.folifis ? '/'+d.folifis : "")+'</p>'
                    }
                },
				{
                    "width": "8%",
                    "data" : function( d ){
                        return d.fech_auto ? '<p style="font-size: .7em">'+(d.fech_auto? formato_fechaymd(d.fech_auto): "") +'</p>' : '<p style="font-size: .7em"></p>';
                    }
                },
                { "width": "15%",
                    "data" : function( d ){
                        return '<p style="font-size: .7em">'+d.nombre+'</p>'
                    }
                },
                { "width": "15%",
                    "data" : function( d ){
                        return '<p style="font-size: .7em">'+d.nomdepto+'</p>';
                    }
                },
                { 
                    "width": "10%",
                    "data" : function( d ){
                        return '<p style="font-size: .9em">'+formatMoney( d.cantidad )+'</p>'
                    } 
                },
                {
                    "width": "10%",
                    "data" : function( d ){
                        return '<p style="font-size: .9em">'+d.metoPago+'</p>'
                    }
                },
                {
                    "width": "12%",
                    "data" : function( d ){
                        return '<p style="font-size: .7em">'+d.nombre_completo+'</p>'
                    }
                },
                {
                    "width": "10%",
                    "data" : function( d ){

                        switch( d.estatus_pago ){
                            case '0':
                                return '<p style="font-size: .7em">PAGO AUTORIZADO DG</p>';
                                break;
                            case '1':
                                return '<p style="font-size: .7em">POR DISPERSAR</p>';
                                break;
                            case '2':
                                return '<p style="font-size: .7em">SE HA PAUSADO EL PROCESO DE ESTE PAGO</p>';
                                break;
                            case '5':
                                return '<p style="font-size: .7em">POR DISPERSAR</p>';
                                break;
                            case '15':
                                return '<p style="font-size: .7em">POR CONFIRMAR PAGO CXP</p>';
                                break;
                            case '12':
                                return '<p style="font-size: .7em">PAGO DETENIDO</p>';
                                break;
                            case '16':
                                if(d.estatus_pago && d.estatus_pago == '16' && (d.etapa=='Pago Aut. por DG, Factura Pendiente'||d.etapa=='Pagado')){ 
                                    return '<p style="font-size: .7em">PAGO COMPLETO</p>' ;
                                }
                                else{
                                    return '<p style="font-size: .7em">'+d.etapa+'</p>';
                                }
                                break;
                            default:
                                if( d.idetapa > 9 && d.idetapa < 20 && d.idprovision == null && ( d.caja_chica == 0 || d.caja_chica == null ) && d.tipo_factura == 1 ){
                                    return '<p style="font-size: .7em">FACTURA PAGADA, EN ESPERA DE PROVISION</p>'; 
                                }else{
                                    return '<p style="font-size: .7em">'+d.etapa+'</p>';
                                }
                        }                        
                    }
                },
                {
                    "orderable": false,
                    "width": "10%",
                    "data" : function( d ){
                        let tipo ; /** INICIO FECHA: 10-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                        switch (d.caja_chica) {
                            case '1':
                                tipo = 'CAJA CHICA';
                                break;
                            case '2':
                                tipo = 'TARJETA DE CRÉDITO';
                                break;
                            case '3':
                                tipo = 'REEMBOLSO';
                                break;
                            case '4':
                                tipo = 'VIÁTICOS';
                                break;
                            default:
                                tipo = 'PAGO PROVEEDOR';
                                break;
                        }
                        return '<p style="font-size: .7em">'+tipo+'</p><span class="label pull-center bg-orange">' + d.nomdepto + '</span>'; /** FIN FECHA: 10-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                    }
                },
                {
                    "orderable": false,
                    "data": function( d ){

                        if( d.idproceso && d.idproceso != null)
                            tipo_modal = 'DEV_BASICA';
                        else
                            tipo_modal = 'BAS';
                        
                        var txt='<div class="btn-group-vertical"><button type="button" class="btn btn-sm btn-primary consultar_modal notification" value="'+d.idsolicitud+'" data-value="'+tipo_modal+'"><i class="fas fa-eye"></i>'+(d.visto == 0 ? '<span class="badge">!</span>' : '')+'</button>';
                        <?php
                        if($this->session->userdata("inicio_sesion")['id'] == 99)
                            echo 'txt+=\' <button type="button" class="btn btn-warning btn-sm comentario_especial" value="\'+d.idsolicitud+\',\'+d.idproveedor+\'"><i class="fas fa-bullhorn"></i></button> \';  ';
                        ?> 
                        return txt+'</div>';
                        
                    }
                }
            ],
            "columnDefs": [ 
                {
                   "orderable": false
                },
                {
                    "targets": [ 6 ], /** FECHA: 10-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                    "visible": false
                },
                {
                    "targets": [ 11 ], /** FECHA: 10-OCTUBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                    "visible": false
                }
            ],
            "ajax": {
                "url" : url + "Historial/TablaHistorialSolicitudes",
                "type": "POST",
                "data" : function( d ){
                    d.tipo_reporte  = tipo_solicitud,
                    d.fechaInicio   = moment($('#datepicker_from').val(), 'DD/MM/YYYY').format('YYYY-MM-DD'),
                    d.fechaFinal    = moment($('#datepicker_to').val(), 'DD/MM/YYYY').format('YYYY-MM-DD')
                }
            }
        });

        $('#tablahistorialsolicitudes tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = tabla_historial_solicitudes.row( tr );
    
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
        
        $('#tablahistorialsolicitudes tbody').on('click', '.comentario_especial', function () {
            var ids = $(this).val().split(",");
            idsolicitud = ids[0];
            trseleccionado = $(this).closest('tr');
            id_proveedor = ids[1];
            $("#formulario_contrato .form-control").val("");
            $("#formulario_financ .form-control").val("");

            llenar_select_proyecto(id_proveedor);

            tabla_historial_solicitudes.row( trseleccionado ).data().financiamiento > 0 
            ? $("#formulario_financ .financ").val(tabla_historial_solicitudes.row( trseleccionado ).data().financiamiento)
            : null ;

            $("#modal_comentarios_especiales").modal("toggle");
        });
        
        // llenar select de contrato proveedor
    function llenar_select_proyecto(id_proveedor){
        // var data = new FormData( $(form)[0] );
        const selector = $('#select_proyecto_by_proveedor');
        selector.html('<option value="">Seleccione un opción</option>');
        const data = new FormData();
        data.append("id_proveedor", id_proveedor);
        data.append("idsolicitud", idsolicitud);
        $.ajax({
            url: url + "Solicitante/proyecto_deproveedor",
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            method: 'POST',
            type: 'POST', // For jQuery < 1.9
            success: function(data){
                var contratos_asig = Object.values(data.contratos_asig).map(value => value.idcontrato);
                data.data.map((contrato) =>{
                    if(!contratos_asig.includes(contrato.idcontrato))
                        selector.append(`<option value="${contrato.idcontrato}">${contrato.nombre_contrato} / $${formatMoney(contrato.residuo)}</option>`);
                } );
            },error: function( ){
                alert("Algo salio mal, recargue su página.");
            }
        });
    }
        
        $('#datepicker_from').change( function() { 
           tabla_historial_solicitudes.draw();
           var total = 0;
           var index = tabla_historial_solicitudes.rows( { selected: true, search: 'applied' } ).indexes();
           
           var data = tabla_historial_solicitudes.rows( index ).data();
           
           $.each(data, function(i, v){
               total += parseFloat(v.cantidad);
           });
           var to1 = formatMoney(total);
           document.getElementById("myText_1").value = to1;
        });
        
        $('#datepicker_to').change( function() {
            tabla_historial_solicitudes.draw();
            var total = 0;
            var index = tabla_historial_solicitudes.rows( { selected: true, search: 'applied' } ).indexes();
            var data = tabla_historial_solicitudes.rows( index ).data();
           $.each(data, function(i, v){
               total += parseFloat(v.cantidad);
           });
           var to1 = formatMoney(total);
           document.getElementById("myText_1").value = to1;
         });
           
        $('.dt-buttons').attr('style','float: right !important; margin-right: 15px;');
        
    });

    $('#tablahistorialpagos').on('xhr.dt', function ( e, settings, json, xhr ) {
        let total = 0;
        $.each( json.data,  function(i, v){
            total += parseFloat(v.cantidad);
        });
        let to = formatMoney(total);
        document.getElementById("myText_2").value = to;
    
    });
       
    $("#tablahistorialpagos").ready( function(){

        $('#tablahistorialpagos thead tr:eq(0) th').each( function (i) {
                var title = $(this).text();
                $(this).html( '<input type="text" class="form-control" style="font-size: .9em; width: 100%;" placeholder="'+title+'" />' ); /** FECHA: 07-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
            
                $( 'input', this ).on('keyup change', function () {
                    if (tabla_historial_pagos.column(i).search() !== this.value ) {
                        tabla_historial_pagos
                            .column(i)
                            .search( this.value)
                            .draw();
                        var total = 0;
                        var index = tabla_historial_pagos.rows( { selected: true, search: 'applied' } ).indexes();
                        var data = tabla_historial_pagos.rows( index ).data();
                        $.each(data, function(i, v){
                            total += parseFloat(v.cantidad);
                        });
                        var to1 = formatMoney(total);
                        document.getElementById("myText_2").value = to1;
                    }
                });
        });

        tabla_historial_pagos = $("#tablahistorialpagos").DataTable({
            dom: 'Brtip',
            "buttons": [
                {
                    extend: 'excelHtml5',             
                    text: '<i class="fas fa-file-excel"></i> EXPORTAR',
                    messageTop: "Lista de pagos autorizados por Dirección General (CHEQUES)",
                    attr: {
                        class: 'btn btn-success'       
                    },
                    exportOptions: {
                        format: {
                            header: function (data, columnIdx) { 

                                data = data.replace( '<input type="text" class="form-control" style="font-size: .9em; width: 100%;" placeholder="', '' ); /** FECHA: 07-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                                data = data.replace( '">', '' )
                                return data;
                            }
                        },
                        columns: [ 0, 1, 2, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14 ]
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
                // COLUMNA [ 0 ]
                {
                    "width": "4%",
                    "data" : function( d ){
                        return '<p style="font-size: .9em">'+d.idsolicitud+'</p>'
                    }
                },
                // COLUMNA [ 1 ]
                {
                    "width": "5%",
                    "data" : function( d ){
                        return '<p style="font-size: .9em">'+d.folio+'</p>'
                    }
                },
                // COLUMNA [ 2 ]
                {
                    "width": "5%",
                    "data" : function( d ){
                        return '<p style="font-size: .9em">'+(d.folifis ? d.folifis : "SF")+'</p>'
                    }
                },
                // COLUMNA [ 3 ]
                {
                    "data" : function( d ){
                        return '<p style="font-size: .9em">'+d.nombre+'</p>'+'</p><span class="label pull-center bg-orange">' + d.nomdepto + '</span>'
                    }
                },
                // COLUMNA [ 4 ]
                {
                    "width": "10%",
                    "data" : function( d ){
                        /*
                        if(d.estapag==16){

                            return '<p style="font-size: .9em"><b>'+(d.fecha_dispersion? formato_fechaymd(d.fecha_dispersion): "")+'</b></p>';

                        }if(d.estapag!=16){

                            if(d.fechaaut==null){
                                return '<p style="font-size: .9em">---</p>';

                            }else{
                                return '<p style="font-size: .9em">'+(d.fechaaut? formato_fechaymd(d.fechaaut): "")+'</p>';
                            }
                        }
                        */
                        return '<p style="font-size: .9em">'+(d.fechaaut? formato_fechaymd(d.fechaaut): "")+'</p>';
                    }
                },
                // COLUMNA [ 5 ]
                {
                    "data" : function( d ){
                        return '<p style="font-size: .9em">'+d.nombre+'</p>'
                    }
                },
                // COLUMNA [ 6 ]
                {
                    "data" : function( d ){
                        return '<p style="font-size: .9em">'+d.nomdepto+'</p>'
                    }
                },
                // COLUMNA [ 7 ]
                {
                    "width": "10%",
                    "data" : function( d ){
                        return '<p style="font-size: .9em">'+formatMoney( d.cantidad )+'</p>';
                    }
                },       
                // COLUMNA [ 8 ]
                {
                    "width": "5%",
                    "data" : function( d ){
                        return '<p style="font-size: .9em">'+d.abrev+'</p>'
                    }
                },
                // COLUMNA [ 9 ]
                {
                    "width": "5%",
                    "data" : function( d ){
                        return '<p style="font-size: .9em">'+(d.fecha_dispersion ? d.fecha_dispersion : '-')+'</p>'
                    }
                },
                // COLUMNA [ 10 ]
                {
                    "width": "10%",
                    "data" : function( d ){

                        switch( d.estatus ){
                            case "0":
                            return '<p style="font-size: .9em">AUTORIZADO POR DG</p>';
                                break;
                            case "16":
                                return '<p style="font-size: .9em">PAGADA</p>';
                                break;
                            default:
                                return '<p style="font-size: .9em">PROCESANDO PAGO</p>';
                                break;
                                
                        }
                    }
                },
                // COLUMNA [ 11 ]
                {
                    "width": "10%",
                    "data" : function( d ){

                        if(d.estapag==16){
                            return '<p style="font-size: .9em"><center><span class="badge bg-blue">'+d.formaPago+'</span></center></p>'
                        }
                        else{
                            return '<p style="font-size: .9em">POR CONFIRMAR</p>';

                        }
                        
                    }
                },
                // COLUMNA [ 12 ]
                {
                    "width": "10%",
                    "data" : function( d ){
                        return '<p style="font-size: .9em">'+( d.referencia ? d.referencia : "---" )+'</p>'
                    }
                },
                // COLUMNA [ 13 ]
                {
                    "width": "10%",
                    "data" : function( d ){
                        return '<p style="font-size: .9em">'+d.justificacion+'</p>';
                    }
                },
                // COLUMNA [ 14 ]
                {
                    "width": "10%",
                    "data" : function( d ){
                        return '<p style="font-size: .9em">'+d.nombre_capturista+'</p>';
                    }
                }
            ],
            "columnDefs": [
                {
                    "targets": [5],
                    "orderable": false,
                    "visible": false,
                },
                {
                    "targets": [6],
                    "orderable": false,
                    "visible": false,
                },
                {
                    "targets": [9],
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
            ],
            "order": [[1, "asc"]],
            bSort: false
            //"ajax": url + "Historial/TablaHPagosDepartmento",
        });
        
        $('#datepicker_fromP').change( function() { 
        tabla_historial_pagos.draw(); 
        var total = 0;
        var index = tabla_historial_pagos.rows( { selected: true, search: 'applied' } ).indexes();
        var data = tabla_historial_pagos.rows( index ).data();
        $.each(data, function(i, v){
            total += parseFloat(v.cantidad);
            });
        var to1 = formatMoney(total);
        document.getElementById("myText_2").value = to1;
        
        });
        $('#datepicker_toP').change( function() { 
            tabla_historial_pagos.draw(); 
            var total = 0;
            var index = tabla_historial_pagos.rows( { selected: true, search: 'applied' } ).indexes();
            var data = tabla_historial_pagos.rows( index ).data();
        $.each(data, function(i, v){
            total += parseFloat(v.cantidad);
            });
        var to1 = formatMoney(total);
        document.getElementById("myText_2").value = to1;
        } );
        
        $('.dt-buttons').attr('style','float: right !important; margin-right: 15px;');
    });

    $('#tablahistorialcch').on('xhr.dt', function ( e, settings, json, xhr ) {
        let total = 0;
        $.each( json.data,  function(i, v){
            total += parseFloat(v.cantidad);
        });
        let to = formatMoney(total);
        document.getElementById("myText_3").value = to;
    
    });
    
    $("#tablahistorialcch").ready( function(){
        $('#tablahistorialcch thead tr:eq(0) th').each( function (i) {
            if( $(this).text() != '' ){
                var title = $(this).text();
                $(this).html( '<input type="text" class="form-control" style="font-size: .9em; width: 100%;" placeholder="'+title+'" />' ); /** FECHA: 07-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/

                $( 'input', this ).on('keyup change', function () {
                    if (tabla_historial_cch.column(i).search() !== this.value ) {
                        tabla_historial_cch
                            .column(i)
                            .search( this.value)
                            .draw();
                           var total = 0;
                           var index = tabla_historial_cch.rows( { selected: true, search: 'applied' } ).indexes();
                           var data = tabla_historial_cch.rows( index ).data();
                           $.each(data, function(i, v){
                               total += parseFloat(v.cantidad);
                           });
                           var to1 = formatMoney(total);
                           document.getElementById("myText_3").value = to1;
                    }
                });
            }
        });

        tabla_historial_cch = $("#tablahistorialcch").DataTable({
            dom: 'Brtip',
            "buttons": [
                {
                    extend: 'excelHtml5',             
                    text: '<i class="fas fa-file-excel"></i> EXPORTAR',
                    messageTop: 'LISTADO DE CAJA CHICA',
                    attr: {
                        class: 'btn btn-success'       
                    },
                    exportOptions: {
                        columns: [1,2,3,4,5,6,7,8], /** FECHA: 29-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                        format: {
                            header: function (data, columnIdx) { 
                                data = data.replace( '<input type="text" class="form-control" style="font-size: .9em; width: 100%;" placeholder="', '' ); /** FECHA: 07-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                                data = data.replace( '">', '' )
                                return data;
                            }
                        }
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
                    "width": "4%",
                    "className": 'details-control',
                    "orderable": false,
                    "data" : null,
                    "defaultContent": '<i class="fas animacion fa-caret-right"></i>'
                },
                {
                    "width": "15%",
                    "data": function( d ){
                        return '<p style="font-size: .9em">'+d.responsable+'</p>';
                    }
                },
                { /** INICIO FECHA: 29-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                    "width": "15%",
                    "data": function( d ){
                        return '<p style="font-size: .9em">'+(d.nombre_reembolso_cch ? d.nombre_reembolso_cch : 'NA')+'</p>';
                    }
                }, /** FIN FECHA: 29-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                {
                    "width": "12%",
                    "data": function( d ){
                        return '<p style="font-size: .9em">'+d.abrev+'</p>';
                    }
                },
                {
                    "width": "12%",
                    "data": function( d ){
                        return '<p style="font-size: .9em"> $ '+formatMoney( d.cantidad )+'</p>';
                    }
                },
                {
                    "width": "12%",
                    "data": function( d ){
                        return '<p style="font-size: .9em">'+formato_fechaymd(d.fecha)+'</p>';
                    }
                },
                {
                    "width": "12%",
                    "data": function( d ){
                        return '<p style="font-size: .9em">'+( d.tipoPago ? d.tipoPago+' '+d.referencia : 'AUN SIN DEFINIR' )+'</p>';
                    }
                },
                {
                    "width": "12%",
                    "data": function( d ){
                        return '<p style="font-size: .9em">'+( d.fecha_cobro ? formato_fechaymd(d.fecha_cobro) : '---' )+'</p>';
                    }
                },
                {
                    "width": "15%",
                    "data": function( d ){
                        return '<p style="font-size: .9em">'+d.nomdepto+'</p>';
                    }
                },
            ],
            "order": [[1, "asc"]],
            // "bSort": false,
            //"ajax": url + "Historial/tabla_cajas_chicas"
        });


        $('#tablahistorialcch tbody').on('click', 'td.details-control', function () {

            var tr = $(this).closest('tr');
            var row = tabla_historial_cch.row( tr );

            if ( row.child.isShown() ) {
                
                row.child.hide();
                tr.removeClass('shown');

                $(this).parent().find('.animacion').removeClass("fa-caret-down").addClass("fa-caret-right");
            }
            else {

                if( row.data().solicitudes == null || row.data().solicitudes == "null" ){
                    $.post( url + "Historial/carga_cajas_chicas" , { "idcajachicas" : row.data().idsolicitud } ).done( function( data ){
                        
                        row.data().solicitudes = JSON.parse( data );

                        tabla_historial_cch.row( tr ).data( row.data() );

                        row = tabla_historial_cch.row( tr );

                        row.child( construir_subtablas( row.data().solicitudes ) ).show();
                        tr.addClass('shown');
                        $(this).parent().find('.animacion').removeClass("fa-caret-right").addClass("fa-caret-down");

                    });
                }else{
                    row.child( construir_subtablas( row.data().solicitudes ) ).show();
                    tr.addClass('shown');
                    $(this).parent().find('.animacion').removeClass("fa-caret-right").addClass("fa-caret-down");
                }

            }
        });

        function construir_subtablas( data ){ /** INICIO FECHA: 29-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
            var solicitudes = '<div class="container" style="width: 100%; padding-top: 10px; padding-bottom: 10px; border-left: 1px solid #D2D6DE; border-right: 1px solid #D2D6DE; border-bottom: 1px solid #D2D6DE; border-radius: 0 0 5px 5px;">';
            $.each(data, function(i, v) { 
                solicitudes += `<div class="row" style="padding-right: 10px; padding-left: 10px;">`;
                solicitudes += `
                    <div class="row">
                        <div class="col-md-12">
                            <p><b> ${(i+1)} .- SOLICITUD: #${v.idsolicitud}</b></p>
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
                            <p><b>TIPO DE SOLICITUD:</b> ${v.tipo_sol} </p>
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
        
        $('#datepicker_fromC').change( function() { 
            tabla_historial_cch.draw();
           var total = 0;
           var index = tabla_historial_cch.rows( { selected: true, search: 'applied' } ).indexes();
           var data = tabla_historial_cch.rows( index ).data();
           $.each(data, function(i, v){
               total += parseFloat(v.cantidad);
           });
           var to1 = formatMoney(total);
           document.getElementById("myText_3").value = to1;
        });
        
        $('#datepicker_toC').change( function() {
            tabla_historial_cch.draw();
            var total = 0;
            var index = tabla_historial_cch.rows( { selected: true, search: 'applied' } ).indexes();
            var data = tabla_historial_cch.rows( index ).data();
           $.each(data, function(i, v){
               total += parseFloat(v.cantidad);
           });
           var to1 = formatMoney(total);
           document.getElementById("myText_3").value = to1;
        });
                   
        $('.dt-buttons').attr('style','float: right !important; margin-right: 15px;');
        
    });
    //--------------------------------------------------------Tramites PV
    $("#tablahistorialpv").ready( function(){
        $('#tablahistorialpv thead tr:eq(0) th').each( function (i) {
            if( $(this).text() != '' ){
                var title = $(this).text();
                $(this).html( '<input type="text" class="form-control" style="font-size: .9em; width: 100%;" placeholder="'+title+'" />' ); /** FECHA: 07-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/

                $( 'input', this ).on('keyup change', function () {
                    if (tabla_historial_pv.column(i).search() !== this.value ) {
                        tabla_historial_pv
                            .column(i)
                            .search( this.value)
                            .draw();
                           var index = tabla_historial_pv.rows( { selected: true, search: 'applied' } ).indexes();
                           var data = tabla_historial_pv.rows( index ).data();
                    }
                });
            }
        });

        tabla_historial_pv = $("#tablahistorialpv").DataTable({
            dom: 'Brtip',
            "buttons": [
                {
                    extend: 'excelHtml5',             
                    text: '<i class="fas fa-file-excel"></i> EXPORTAR',
                    messageTop: 'LISTADO DE TRÁMITES PV',
                    attr: {
                        class: 'btn btn-success'       
                    },
                    exportOptions: {
                        columns: [0,1,2,3,4,5,6,7,8],
                        format: {
                            header: function (data, columnIdx) { 

                                data = data.replace( '<input type="text" class="form-control" style="font-size: .9em; width: 100%;" placeholder="', '' ); /** FECHA: 07-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                                data = data.replace( '">', '' )
                                return data;
                            }
                        }
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
                    "width": "2%",
                    "data": function( d ){
                        return '<p style="font-size: .9em">'+d.idsolicitud+'</p>';
                    }
                },
                {
                    "width": "7%",
                    "data": function( d ){
                        return '<p style="font-size: .9em">'+d.folio+'</p>';
                    }
                },
                {
                    "width": "12%",
                    "data": function( d ){
                        return '<p style="font-size: .9em">'+d.usuario+'</p>';
                    }
                },
                {
                    "width": "12%",
                    "data": function( d ){
                        return '<p style="font-size: .9em">'+d.desarrollo+'</p>';
                    }
                },
                {
                    "width": "5%",
                    "data": function( d ){
                        return '<p style="font-size: .9em">'+d.lote+'</p>';
                    }
                },
                {
                    "width": "5%",
                    "data": function( d ){
                        return '<p style="font-size: .9em">'+d.rechazos+'</p>';
                    }
                },
                {
                    "width": "12%",
                    "data": function( d ){
                        return '<p style="font-size: .9em">'+formato_fechaymd(d.fechaContraloria)+'</p>';
                    }
                },
                {
                    "width": "12%",
                    "data": function( d ){
                        return '<p style="font-size: .9em">'+formato_fechaymd(d.fechaAutContraloria)+'</p>';
                    }
                },
                {
                    "width": "12%",
                    "data": function( d ){
                        return '<p style="font-size: .9em">'+d.etapa+'</p>';
                    }
                }
            ],
            "order": [[1, "asc"]],
            // "bSort": false,
        });

        
        $('#datepicker_fromPV').change( function() { 
            tabla_historial_pv.draw();
           var total = 0;
           var index = tabla_historial_pv.rows( { selected: true, search: 'applied' } ).indexes();
           var data = tabla_historial_pv.rows( index ).data();
        });
        
        $('#datepicker_toPV').change( function() {
            tabla_historial_pv.draw();
            var total = 0;
            var index = tabla_historial_pv.rows( { selected: true, search: 'applied' } ).indexes();
            var data = tabla_historial_pv.rows( index ).data();
        });
                   
        $('.dt-buttons').attr('style','float: right !important; margin-right: 15px;');

        // Ticket #76788 Folios en reporte de trámites PV | FECHA: 18-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com>
        var mesesSeleccionados = [];
        $('.month, #anio').change(function() {
            var mes = $(this).val();
            var anio = $('#anio').val();
            if ($(this).is(':checked')) {
                // Agrega el mes al array si está seleccionado
                if (!mesesSeleccionados.includes(mes)) {
                    mesesSeleccionados.push(mes);
                }
            } else {
                // Elimina el mes del array si está deseleccionado
                mesesSeleccionados = mesesSeleccionados.filter(function(item) {
                    return item !== mes;
                });
            }

            $.ajax({
                url: url +"Historial/TablaHistorialSolicitudesPV",
                data: { mesesSeleccionados: mesesSeleccionados, anio: anio },
                type: 'post',
                dataType: 'json',
                success: function(response) {
                    tabla_historial_pv.clear(); //Reseteamos el datatable
                    tabla_historial_pv.rows.add(response.data).draw(); //Asignamos datos a datatable
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });

        });
    });
    // FIN Ticket #76788 Folios en reporte de trámites PV | FECHA: 18-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com>

    $('input[type="checkbox"]').on('change', function () {
            tabla_historial_pv.draw();
    });

    $('#anio').on('change', function () {
        tabla_historial_pv.draw();
    });

    $(window).resize(function(){
        tabla_historial_pagos.columns.adjust();
    });
    
    $("#formulario_contrato").submit( function(e) {
        e.preventDefault();
    }).validate({
        submitHandler: function( form ) {
            const row = tabla_historial_solicitudes.row( trseleccionado ).data();
            const data = new FormData();
            const idcontrato = $('select#select_proyecto_by_proveedor').val();
            

            data.append('idsolicitud', row.idsolicitud);
            data.append('idcontrato', idcontrato);
            data.append('cant_solicitud', row.cantidad);

            $.ajax({
                url: url+"Solicitante/asignar_proveedor_contrato",
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                method: 'POST',
                type: 'POST', // For jQuery < 1.9
                success: function(data){
                    // console.log(data);
                    alert(data.mensaje);
                    $("#modal_comentarios_especiales").modal('hide');
                }, error(){
                    alert("Algo salio mal, recargue su página.");
                }
            });
        }
    });

    $("#formulario_financ").submit( function(e) {
        e.preventDefault();
    }).validate({
        submitHandler: function( form ) {
            const row = tabla_historial_solicitudes.row( trseleccionado ).data();
            const data = new FormData($(form)[0] );            
            data.append('idsolicitud', row.idsolicitud);
            
            $.ajax({
                url: url+"Solicitante/asignar_financiamiento",
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                method: 'POST',
                type: 'POST', // For jQuery < 1.9
                success: function(data){
                    // console.log(data);
                    row.financiamiento = data.financ;
                    tabla_historial_solicitudes.row( trseleccionado ).data( row ).draw();
                    alert(data.mensaje);
                    $("#modal_comentarios_especiales").modal('hide');
                }, error(){
                    alert("Algo salio mal, recargue su página.");
                }
            });
        }
    }); 

    $('#tablahistorialautorizaciones').on('xhr.dt', function ( e, settings, json, xhr ) {
        let total = 0;
        $.each( json.data,  function(i, v){
            total += parseFloat(v.cantidad);
        });
        let to = formatMoney(total);
        document.getElementById("myText_2").value = to;
    
    });
    $("#tablahistorialautorizaciones").ready( function(){

        $('#tablahistorialautorizaciones thead tr:eq(0) th').each( function (i) {
                var title = $(this).text();
                $(this).html( '<input type="text" class="form-control" style="font-size: .9em; width: 100%;" placeholder="'+title+'" />' ); /** FECHA: 07-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
            
                $( 'input', this ).on('keyup change', function () {
                    if (tabla_autorizacion_yola.column(i).search() !== this.value ) {
                        tabla_autorizacion_yola
                            .column(i)
                            .search( this.value)
                            .draw();
                        var total = 0;
                        var index = tabla_autorizacion_yola.rows( { selected: true, search: 'applied' } ).indexes();
                        var data = tabla_autorizacion_yola.rows( index ).data();
                        $.each(data, function(i, v){
                            total += parseFloat(v.cantidad);
                        });
                        var to1 = formatMoney(total);
                        document.getElementById("myText_4").value = to1;
                    }
                });
        });

        tabla_autorizacion_yola = $("#tablahistorialautorizaciones").DataTable({
            dom: 'Brtip',
            "buttons": [
                {
                    extend: 'excelHtml5',             
                    text: '<i class="fas fa-file-excel"></i> EXPORTAR',
                    messageTop: "Autorizaciones de Contabilidad",
                    attr: {
                        class: 'btn btn-success'       
                    },
                    exportOptions: {
                        format: {
                            header: function (data, columnIdx) { 

                                data = data.replace( '<input type="text" class="form-control" style="font-size: .9em; width: 100%;" placeholder="', '' ); /** FECHA: 07-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                                data = data.replace( '">', '' )
                                return data;
                            }
                        },
                        columns: [ 0, 1, 2, 3, 4, 5]
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
                    "width": "4%",
                    "data" : function( d ){
                        return '<p style="font-size: .9em">'+d.idsolicitud+'</p>'
                    }
                },
                {
                    "width": "5%",
                    "data" : function( d ){
                        return '<p style="font-size: .9em">'+d.proyecto+'</p>'
                    }
                },
                {
                    "width": "5%",
                    "data" : function( d ){
                        return '<p style="font-size: .9em">'+d.folio+'</p>'
                    }
                },
                {
                    "data" : function( d ){
                        return '<p style="font-size: .9em">'+d.empresa+'</p>'
                    }
                },
                {
                    "width": "10%",
                    "data" : function( d ){
                        
                        return '<p style="font-size: .9em">'+(d.fecha_autorizacion? formato_fechaymd(d.fecha_autorizacion): "")+'</p>';
                    }
                },
                
                
                
                {
                    "data" : function( d ){
                        return '<p style="font-size: .9em">'+d.proveedor+'</p>'
                    }
                }
            ],
            "order": [[1, "asc"]],
            bSort: false,
            // ajax:{
            //         url: url + "Historial/autorizacion_yola",
            //         type: 'POST',
            //         beforeSend: function () {
                        
            //         }
            //     }
        });

        function editarDatos(d) {
            

            d.inicial = formatfech($("#datepicker_fromA").val());
            d.final = formatfech($("#datepicker_toA").val());
        }

        function formatfech(valor){
            //console.log(valor);
            return valor.substring(6,10)+'-'+valor.substring(3,5)+'-'+valor.substring(0,2);
        }

        $('#datepicker_fromA').change( function() { 
            inicial = formatfech($("#datepicker_fromA").val());
            
            tabla_autorizacion_yola.draw();
            var index = tabla_autorizacion_yola.rows( { selected: true, search: 'applied' } ).indexes();
        });
        $('#datepicker_toA').change( function() { 
            final = formatfech($("#datepicker_toA").val());
            
            tabla_autorizacion_yola.draw();
            var index = tabla_autorizacion_yola.rows( { selected: true, search: 'applied' } ).indexes();
        } );
        //$('.dt-buttons').attr('style','float: right !important; margin-right: 15px;');
    });

    $("#tablareporte_pv").ready( function(){

        $('#tablareporte_pv thead tr:eq(0) th').each( function (i) {
                var title = $(this).text();
                $(this).html( '<input type="text" class="form-control" style="font-size: .9em; width: 100%;" placeholder="'+title+'" />' ); /** FECHA: 07-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
            
                $( 'input', this ).on('keyup change', function () {
                    if (tabla_reporte_pv.column(i).search() !== this.value ) {
                        tabla_reporte_pv
                            .column(i)
                            .search( this.value)
                            .draw();
                        var total = 0;
                        var index = tabla_reporte_pv.rows( { selected: true, search: 'applied' } ).indexes();
                        var data = tabla_reporte_pv.rows( index ).data();
                        $.each(data, function(i, v){
                            total += parseFloat(v.cantidad);
                        });
                        var to1 = formatMoney(total);
                        document.getElementById("myText_1").value = to1;
                    }
                });
        });

        tabla_reporte_pv = $("#tablareporte_pv").DataTable({
            dom: 'Brtip',
            "buttons": [
                {
                    extend: 'excelHtml5',             
                    text: '<i class="fas fa-file-excel"></i> EXPORTAR',
                    messageTop: "Autorizaciones de Contabilidad",
                    attr: {
                        class: 'btn btn-success'       
                    },
                    exportOptions: {
                        format: {
                            header: function (data, columnIdx) { 

                                data = data.replace( '<input type="text" class="form-control" style="font-size: .9em; width: 100%;" placeholder="', '' ); /** FECHA: 07-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                                data = data.replace( '">', '' )
                                return data;
                            }
                        },
                        columns: [ 0, 1, 2, 3, 4, 5, 6]
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
                    "width": "4%",
                    "data" : function( d ){
                        return '<p style="font-size: .9em">'+d.idsolicitud+'</p>'
                    }
                },
                {
                    "width": "5%",
                    "data" : function( d ){
                        return '<p style="font-size: .9em">'+d.Usuario+'</p>'
                    }
                },
                
                {
                    "data" : function( d ){
                        return '<p style="font-size: .9em">'+d.Desarrollo+'</p>'
                    }
                },
                {
                    "data" : function( d ){
                        return '<p style="font-size: .9em">'+d.Lote+'</p>'
                    }
                },
                {
                    "width": "10%",
                    "data" : function( d ){
                        
                        return '<p style="font-size: .9em">'+(d.fechae? formato_fechaymd(d.fechae): "")+'</p>';
                    }
                },
                {
                    "data" : function( d ){
                        return '<p style="font-size: .9em">'+d.usuarioe+'</p>'
                    }
                },
                {
                    "width": "10%",
                    "data" : function( d ){
                        
                        return '<p style="font-size: .9em">'+(d.fechae? formato_fechaymd(d.fechas): "")+'</p>';
                    }
                },
                {
                    "data" : function( d ){
                        return '<p style="font-size: .9em">'+d.usuariosa+'</p>'
                    }
                },
                {
                    "width": "5%",
                    "data" : function( d ){
                        return '<p style="font-size: .9em">'+d.folio+'</p>'
                    }
                },
                
                
                
                {
                    "data" : function( d ){
                        return '<p style="font-size: .9em">'+d.estatus+'</p>'
                    }
                },
                {
                    "data" : function( d ){
                        return '<p style="font-size: .9em">'+d.rechazadas+'</p>'
                    }
                }
            ],
            "order": [[1, "asc"]],
            bSort: false,
            ajax:{
                    url: url + "Historial/reporte_pv",
                    type: 'POST',
                    beforeSend: function () {
                        
                    }
                }
        });


        $('#datepicker_fromR').change( function() { 
            //inicial = formatfech($("#datepicker_fromR").val());
            
            tabla_reporte_pv.draw();
            var index = tabla_reporte_pv.rows( { selected: true, search: 'applied' } ).indexes();
            
            

        });
        $('#datepicker_toR').change( function() { 
            //final = formatfech($("#datepicker_toR").val());
            
            tabla_reporte_pv.draw();
            var index = tabla_reporte_pv.rows( { selected: true, search: 'applied' } ).indexes();
            
            
        } );


        //$('.dt-buttons').attr('style','float: right !important; margin-right: 15px;');
    });


    $(window).resize(function(){ /** INICIO FECHA: 07-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
        tabla_historial_solicitudes.columns.adjust().draw(false);
        tabla_reporte_pv.columns.adjust().draw(false);
        tabla_autorizacion_yola.columns.adjust().draw(false);
        tabla_historial_pv.columns.adjust().draw(false);
        tabla_historial_cch.columns.adjust().draw(false);
        tabla_historial_pagos.columns.adjust().draw(false);
        tabla_historial_solicitudes.columns.adjust().draw(false);
    });

    $('.sidebar-toggle').click(function() {
        setTimeout(function() {
            // 1. Ajustar columnas
            tabla_reporte_pv.columns.adjust();
            tabla_autorizacion_yola.columns.adjust();
            tabla_historial_pv.columns.adjust();
            tabla_historial_cch.columns.adjust();
            tabla_historial_pagos.columns.adjust();
            tabla_historial_solicitudes.columns.adjust();
            // 2. Forzar actualización de encabezados
            var headerCellsTableReportePV = $('#tablareporte_pv thead th');
            var headerCellsTableHistorialAutorizaciones = $('#tablahistorialautorizaciones thead th');
            var headerCellsTableHistorialPV = $('#tablahistorialpv thead th');
            var headerCellsTableHistorialCCH = $('#tablahistorialcch thead th');
            var headerCellsTableHistorialPagos = $('#tablahistorialpagos thead th');
            var headerCellsTableHistorialSolicitudes = $('#tablahistorialsolicitudes thead th');

            headerCellsTableReportePV.each(function() {
                var newWidth = $(this).width(); // Obtener ancho actualizado
                $(this).css('width', newWidth + 'px'); // Aplicarlo
            });
            headerCellsTableHistorialAutorizaciones.each(function() {
                var newWidth = $(this).width(); // Obtener ancho actualizado
                $(this).css('width', newWidth + 'px'); // Aplicarlo
            });
            headerCellsTableHistorialPV.each(function() {
                var newWidth = $(this).width(); // Obtener ancho actualizado
                $(this).css('width', newWidth + 'px'); // Aplicarlo
            });
            headerCellsTableHistorialCCH.each(function() {
                var newWidth = $(this).width(); // Obtener ancho actualizado
                $(this).css('width', newWidth + 'px'); // Aplicarlo
            });
            headerCellsTableHistorialPagos.each(function() {
                var newWidth = $(this).width(); // Obtener ancho actualizado
                $(this).css('width', newWidth + 'px'); // Aplicarlo
            });
            headerCellsTableHistorialSolicitudes.each(function() {
                var newWidth = $(this).width(); // Obtener ancho actualizado
                $(this).css('width', newWidth + 'px'); // Aplicarlo
            });
            // 3. Redibujar (sin resetear paginación)
            tabla_reporte_pv.draw(false);
            tabla_autorizacion_yola.draw(false);
            tabla_historial_pv.draw(false);
            tabla_historial_cch.draw(false);
            tabla_historial_pagos.draw(false);
            tabla_historial_solicitudes.draw(false);
        }, 300); // Esperar a que termine la animación del sidebar
    }); /** FIN FECHA: 07-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/

    async function descargaReporteExcel() {
        try {
            mostrarLoader(); // Mostrar loader
            // Limpiar errores anteriores
            $('.has-error').removeClass('has-error');
            $('.help-block').hide();

            var valido = true;
            var data = new FormData();
            var inputData;

            // Obtener los valores de las fechas
            var fechaInicio = $('#datepicker_from').val();
            var fechaFin = $('#datepicker_to').val();

            if (!fechaInicio) {
                $('#datepicker_from').closest('.form-group').addClass('has-error');
                $('#error-fecha-inicio').show();
                valido = false;
            }

            if (!fechaFin) {
                $('#datepicker_to').closest('.form-group').addClass('has-error');
                $('#error-fecha-fin').show();
                valido = false;
            }

            // Si hay errores, detener la ejecución
            if (!valido){
                ocultarLoader(); // Ocultar loader
                return;
            }            

            // Si todo está bien, agregar valores al FormData
            data.append('fechaInicio', moment(fechaInicio, 'DD/MM/YYYY').format('YYYY-MM-DD'));
            data.append('fechaFinal', moment(fechaFin, 'DD/MM/YYYY').format('YYYY-MM-DD'));
            data.append('tipo_reporte', tipo_reporte);

            // Recopilar los valores de los inputs de la tabla
            $('#tablahistorialsolicitudes thead tr:eq(0) input').each(function(i) {  
                inputData = $(this).data('value');            
                if (Object.keys(valor_input).indexOf(inputData) >= 0) {
                    data.append(inputData, valor_input[inputData]); // Agregar valores al formulario oculto
                }
            });

            // Enviamos a procesar los datos al BackEnd
            const datosEnviarGoogle = await fetch(url + "Reportes/reporte_historial_solicitudes",{
                method: 'POST',
                body: data
            });

            if (!datosEnviarGoogle.ok) {
                throw new Error("Error interno del servidor. Favor de contactar al administrador del sistema.");
                ocultarLoader(); // Ocultar loader
            }

            const blob = await datosEnviarGoogle.blob();
            
            const filename = datosEnviarGoogle.headers
                .get('Content-Disposition').split('filename=')[1]
                ?.replace(/"/g, '') || 'reporte.csv';

            // Creamos el link temporal de descarga para el archivo excel
            const urlExcel = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = urlExcel;
            a.download = filename;
            document.body.appendChild(a);
            a.click();
            a.remove();
            window.URL.revokeObjectURL(urlExcel);
            ocultarLoader(); // Ocultar loader
            
        } catch (error) {
            ocultarLoader(); // Ocultar loader
            console.error('Error en el proceso: ', error);
            alert('Ocurrió un error. Por favor, póngase en contacto con el administrador del sistema.')
        }
    }

    function rangoFechasFiltro(tipoReporteDataTable) {
        // Traemos la fecha actual con libreria moment
        const fechaActual = moment();
        // Como fecha de inicio restamos 4 años a la fecha de cuando se consulta (para dejar un filtro parecido a la version anterior.)
        const fechaDataPickerInicio = moment(fechaActual).subtract(4, 'YEARS').format('DD/MM/YYYY');
        
        $('.fechas_filtro').datepicker({
            format: 'dd/mm/yyyy',
            autoclose: true,
            //endDate: '-0d'
            zIndexOffset: 10000, /** FECHA: 30-MAYO-2025 | @author Dante Aldair Guerrero Aldana <coordinador6.desarrollo@ciudadmaderas.com> **/
            orientation: 'bottom auto' /** FECHA: 30-MAYO-2025 | @author Dante Aldair Guerrero Aldana <coordinador6.desarrollo@ciudadmaderas.com> **/
        });

        switch (tipoReporteDataTable) {
            case '#historial_activas_prov':
            case '#historial_activas_cch':
            case '#historial_activas_tdc':
            case '#historial_activas_viaticos':
                // Asigna la fecha predeterminada al campo de entrada
                $('#datepicker_from').datepicker('setDate', fechaDataPickerInicio);
                $('#datepicker_from').val(fechaDataPickerInicio);

                // Asigna la fecha predeterminada al campo de entrada
                $('#datepicker_to').datepicker('setDate', fechaActual.format('dd/mm/yyyy'));
                $('#datepicker_to').val(fechaActual.format('DD/MM/YYYY'));
                
                break;
            case '#listado_pagos-tab':
                // Asigna la fecha predeterminada al campo de entrada
                $('#datepicker_fromP').datepicker('setDate', fechaDataPickerInicio);
                $('#datepicker_fromP').val(fechaDataPickerInicio);
                // Asigna la fecha predeterminada al campo de entrada
                $('#datepicker_toP').datepicker('setDate', fechaActual.format('dd/mm/yyyy'));
                $('#datepicker_toP').val(fechaActual.format('DD/MM/YYYY'));

            break;
            case '#listado_cch-tab':
                // Asigna la fecha predeterminada al campo de entrada
                $('#datepicker_fromC').datepicker('setDate', fechaDataPickerInicio);
                $('#datepicker_fromC').val(fechaDataPickerInicio);
                // Asigna la fecha predeterminada al campo de entrada
                $('#datepicker_toC').datepicker('setDate', fechaActual.format('dd/mm/yyyy'));
                $('#datepicker_toC').val(fechaActual.format('DD/MM/YYYY'));
            break;
            case '#listado_aut_yola':
                // Asigna la fecha predeterminada al campo de entrada
                $('#datepicker_fromA').datepicker('setDate', fechaDataPickerInicio);
                $('#datepicker_fromA').val(fechaDataPickerInicio);
                // Asigna la fecha predeterminada al campo de entrada
                $('#datepicker_toA').datepicker('setDate', fechaActual.format('dd/mm/yyyy'));
                $('#datepicker_toA').val(fechaActual.format('DD/MM/YYYY'));
            break;
        }
    }

    function mostrarLetraPorLetra() {
        const fraseActual = frases[indexFrase];
        animatedText.textContent = fraseActual.slice(0, indexLetra);
        indexLetra++;
        if (indexLetra > fraseActual.length) {
        setTimeout(() => {
            indexFrase = (indexFrase + 1) % frases.length;
            indexLetra = 0;
            setTimeout(mostrarLetraPorLetra, 100);
        }, 2000); // Tiempo visible antes de cambiar
        } else {
        setTimeout(mostrarLetraPorLetra, 50);
        }
    }
    
    // Mostrar loader (llámalo desde fetch u otro evento)
    function mostrarLoader() {
        document.getElementById('loader-overlay').style.display = 'flex';
        indexFrase = 0;
        indexLetra = 0;
        mostrarLetraPorLetra();
    }

    // Ocultar loader
    function ocultarLoader() {
        document.getElementById('loader-overlay').style.display = 'none';
    }
    
    $.fn.dataTableExt.afnFiltering.push(function(oSettings, aData, iDataIndex) {
        const idTabla = oSettings.sTableId;
        if (filtrosPorTabla[idTabla]) {
            return filtrosPorTabla[idTabla](aData);
        }
        return true;
    });

    function convertirFecha(fechaStr) {
        if (!fechaStr) return '';        
        const meses = ['', 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
        const partes = fechaStr.split('/');
        const dia = partes[0];
        const mes = meses.indexOf(partes[1]).toString().padStart(2, '0');
        const anio = partes[2];
        return anio + mes + dia;
    }

    function filtroRangoFechas(aData, iStartDateCol, iEndDateCol) {
        let fechaInicio = '', fechaFin = '';
        moment.locale('es'); // Coloca esto antes de usar moment
        $('.from').each(function () {
            if ($(this).val() !== '') {
                fechaInicio = convertirFecha(moment($(this).val(), 'DD/MM/YYYY').format('DD/MMM/YYYY').replace('.', ''));
                return false;
            }
        });

        $('.to').each(function () {
            if ($(this).val() !== '') {
                fechaFin = convertirFecha(moment($(this).val(), 'DD/MM/YYYY').format('DD/MMM/YYYY').replace('.', ''));
                return false;
            }
        });

        const fechaDatoIni = convertirFecha(aData[iStartDateCol]);
        const fechaDatoFin = convertirFecha(aData[iEndDateCol]);        

        if (!fechaInicio && !fechaFin) return true;
        if (fechaInicio && !fechaFin && fechaDatoIni >= fechaInicio) return true;
        if (!fechaInicio && fechaFin && fechaDatoFin <= fechaFin) return true;
        if (fechaInicio && fechaFin && fechaDatoIni >= fechaInicio && fechaDatoFin <= fechaFin) return true;

        return false;
    }
</script>

<?php
    require("footer.php");
?>