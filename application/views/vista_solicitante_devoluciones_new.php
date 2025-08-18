<?php
require("head.php");
require("menu_navegador.php");
?>
<!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script> -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>    
    .pdf-file::-webkit-file-upload-button
    {
        display: none;
        border: none;
    }

    .pdf-label{
        background-color: transparent;
        border: none;
        display: flex;
        align-items: center; 
        flex-direction: column;
    }

    .has-error {
        display: flex;
        flex-direction: column;
        align-items: center;
        color: #eb3b3b;
    }

    #divTablePagos {
        overflow-y: scroll;
        height: 25vh;
        
    }

    #divTablePagos table {
        width: 100%; /* Ancho de la tabla */
        border-collapse: collapse; /* Colapsar bordes de la tabla */
    }
    #divTablePagos th, #divTablePagos td {
        border: 1px solid #ddd; /* Bordes de celdas */
        padding: 8px; /* Espaciado interno */
    }
    #divTablePagos th {
        background-color: #f2f2f2; /* Color de fondo de encabezados */
    }
    
    #rowTablaProveedoresTabla {
        font-size: 10px;
        overflow-y: scroll;
        width: 100%;
        height: 25vh;        
    }

    #rowTablaProveedoresTablaEdit{
        font-size: 10px;
        overflow-y: scroll;
        width: 95%;
        height: 40vh;        
    }

    #rowTablaProveedoresTabla>table>tbody:hover {
        cursor: pointer;
    }

    #rowTablaProveedoresTablaEdit>table>tbody:hover {
        cursor: pointer;
    }

    #rowTablaProveedoresTablaNew {
        font-size: 10px;
        overflow-y: scroll;
        width: 100%;
        height: 25vh;        
    }

    #rowTablaProveedoresTablaEdit {
        font-size: 10px;
        overflow-y: scroll;
        width: 95%;
        height: 40vh;        
    }

    #rowTablaProveedoresTablaNew>table>tbody:hover {
        cursor: pointer;
    }

    #rowTablaProveedoresTablaEdit>table>tbody:hover {
        cursor: pointer;
    }
    .tooltip { /* INICIO FECHA: 17-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
        width: fit-content;
        position: fixed;
    }

    .tooltip .tooltip-inner {
        padding: 5px 10px;
        color: #ffffff;
        background-color: #333333;
        width: fit-content;
    }

    .tooltip.top .tooltip-arrow {
        border-top-color: #333333;
    }

    .tooltip.bottom .tooltip-arrow {
        border-bottom-color: #333333;
    } /* FIN FECHA: 17-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
     /**
    * @uthor Efrain Martinez Muñoz | Fecha : 27/03/2025
    * Se agregan estilo para previsualizar la imagen antes de cargarla
    */
    #contenedorImagen {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: auto;
        background: rgba(0, 0, 0, 0.7);
        margin-top: 10px;
        margin-bottom: 10px;
    }

    #contenedorImagen img {
        max-width: 90%;
        max-height: 50%;
        margin-top: 10%;
        margin-bottom: 10%;
        object-fit: contain;
    }
        

    /* Estilo base para la fila con advertencia */
    .fila-advertencia {
        background-color: #FFC2C2 !important;
        font-weight: bold;
        position: relative;
        text-align: center;
        vertical-align: middle;
        border-radius: 10%;
        animation: pulse 1s ease-out 0.8s 8 normal forwards running;
    }

    #tabla_devoluciones_parcialidad td, #tabla_autorizaciones td {
        vertical-align: middle;
    }
    
    /* Icono de advertencia (usando FontAwesome) */
    .icono-advertencia-llamativo {
        position: absolute;
        top: -8px;
        right: -8px;
        background: red;
        color: white;
        font-size: 0.7em;
        border-radius: 50%;
        padding: 4px;
        z-index: 10;
        animation: parpadeo 1s infinite;
    }

    /* Efecto hover para mayor interactividad */
    .fila-advertencia:hover {
        background-color:rgb(255, 120, 120) !important;
        cursor: pointer;
        box-shadow: 0 0 10px rgba(255, 82, 82, 0.2);
    }
    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.2; }
        100% { opacity: 1; }
    }

    @keyframes parpadeo {
        0% { opacity: 1; }
        50% { opacity: 0.4; }
        100% { opacity: 1; }
    }

    #modalHistorial{
        overflow-x: scroll;
    }
</style>

<!-- DataTable's para vizualizar la informacion de ambas pestañas -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <div class="box">
                    <div class="box-header with-border">
                        <input type="hidden" id="rol" value="<?= $this->session->userdata("inicio_sesion")['rol'] ?>" />
                        <h3>SOLICITUD DE DEVOLUCIONES / TRASPASOS</h3>
                    </div>
                    <div class="box-body">
                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                                <li class="active"><a id="autorizaciones-tab" data-toggle="tab" href="#facturas_autorizar" role="tab" aria-controls="#home" aria-selected="true">CAPTURA DEVOLUCIONES</a></li>
                                <li><a id="activas-tab" data-toggle="tab" href="#facturas_activas" role="tab" aria-controls="#home" aria-selected="true">DEVOLUCIONES EN CURSO</a></li> <!-- FECHA: 27-FEBRERO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                                <li><a id="devoluciones-tab" data-toggle="tab" href="#devoluciones_parcialidad" role="tab" aria-controls="#home" aria-selected="true">DEVOLUCIONES EN PARCIALIDAD</a></li>
                            </ul>
                        </div>
                        <div class="tab-content">
                            <div class="active tab-pane" id="facturas_autorizar">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12">
                                        <table class="table table-striped" id="tabla_autorizaciones">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th></th>
                                                    <th style="font-size: .9em">#</th>
                                                    <!-- <th style="font-size: .9em">PROYECTO</th> -->
                                                    <th style="font-size: .9em">F ENTREGA PV</th>
                                                    <th style="font-size: .9em">EMPRESA</th>
                                                    <th style="font-size: .9em">BENEFICIARIO</th> <!-- FECHA: 08-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                                                    <th style="font-size: .9em">PROYECTO</th>
                                                    <th style="font-size: .9em">CANTIDAD</th>
                                                    <th style="font-size: .9em">PROCESO</th>
                                                    <th style="font-size: .9em">MÉTODO DE PAGO</th>
                                                    <th style="font-size: .9em">ÚLTIMO MOVIMIENTO</th>
                                                    <th style="font-size: .9em">ESTATUS</th>
                                                    <th nowrap style="font-size: .9em"></th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="facturas_activas">
                                <?php if ($this->session->userdata("inicio_sesion")['depto'] == 'CONTRALORIA') { ?> <!-- INICIO FECHA: 21-MARZO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12">
                                            <div class="col-lg-6 col-md-6 text-warning">
                                                <small>
                                                    Por defecto, el rango de fechas es de un año atrás hasta la fecha actual. Puedes modificar las fechas y hacer clic en la lupa para buscar.
                                                    <i class="far fa-question-circle faq text-warning" tabindex="0" 
                                                        data-container="body" data-trigger="focus" data-toggle="popover" 
                                                        title="Instrucciones para Buscar y Exportar Solicitudes" 
                                                        data-content="Para buscar las solicitudes, selecciona una fecha inicial y una fecha final, luego haz clic en el ícono de la lupa (buscar) para ver los resultados. 
                                                                        Si deseas exportar a Excel, usa el botón de EXPORTAR EXCEL. Sin embargo, si el rango de fechas es muy grande (varios meses o más), la exportación podría fallar debido 
                                                                        a los límites del servidor en tiempo y memoria. Este error no depende de nuestro sistema, sino de la capacidad del servidor. Si ocurre, te sugerimos seleccionar un rango de fechas más pequeño." 
                                                        data-placement="right">
                                                    </i>
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?> <!-- FIN FECHA: 21-MARZO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                                <div class="row">
                                    <div class="col-lg-12 col-md-12">
                                        <form id="formularioSolicitudDevolucionesTraspasosEnCurso" autocomplete="off" onkeydown="return event.key != 'Enter';"> <!-- INICIO FECHA: 07-ABRIL-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="fas fa-calendar-alt"></i> <b>INICIO</b></span>
                                                        <input class="form-control fechas_filtro from" type="text" id="fecInicial" name="fechaInicial" maxlength="10" />
                                                    </div>
                                                    <span id="error-fecInicial" class="help-block" style="display:none;"></span>
                                                    <span id="error-fecha-rango" class="help-block" style="display:none;"></span>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="fas fa-calendar-alt"></i> <b>FIN</b></span>
                                                        <input class="form-control fechas_filtro to" type="text" id="fecFinal" name="fechaFinal" maxlength="10" />
                                                    </div>
                                                    <span id="error-fecFinal" class="help-block" style="display:none;"></span>
                                                </div>
                                            </div>
                                            <div id="elementos_hidden"></div>
                                        </form> <!-- FIN FECHA: 07-ABRIL-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                                        <table class="table table-striped" id="tblsolact"> <!-- INICIO FECHA: 27-FEBRERO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th style="font-size: .9em">#</th>                      <!-- COLUMNA # 0 SHOW TABLE-->
                                                    <th style="font-size: .9em">EMPRESA</th>                <!-- COLUMNA # 1 SHOW TABLE-->
                                                    <th style="font-size: .9em">CLIENTE</th>                <!-- COLUMNA # 2 SHOW TABLE-->
                                                    <th style="font-size: .9em">LOTE</th>                   <!-- COLUMNA # 3 SHOW TABLE-->
                                                    <th style="font-size: .9em">ETAPA</th>                  <!-- COLUMNA # 4 -->
                                                    <th style="font-size: .9em">F ENTREGA PV</th>           <!-- COLUMNA # 5 SHOW TABLE-->
                                                    <th style="font-size: .9em">PROCESO</th>                <!-- COLUMNA # 6 SHOW TABLE-->
                                                    <th style="font-size: .9em">JUSTIFICACION</th>          <!-- COLUMNA # 7 -->
                                                    <th style="font-size: .9em">SOLICITANTE</th>            <!-- COLUMNA # 8 -->
                                                    <th style="font-size: .9em">CUENTA CONTABLE</th>        <!-- COLUMNA # 9 -->
                                                    <th style="font-size: .9em">CUENTA ORDEN</th>           <!-- COLUMNA # 10 -->
                                                    <th style="font-size: .9em">COSTO LOTE</th>             <!-- COLUMNA # 11 -->
                                                    <th style="font-size: .9em">SUPERFICIE</th>             <!-- COLUMNA # 12 -->
                                                    <th style="font-size: .9em">PRECIO M2</th>              <!-- COLUMNA # 13 -->
                                                    <th style="font-size: .9em">PREDIAL</th>                <!-- COLUMNA # 14 -->
                                                    <th style="font-size: .9em">PENALIZACIÓN</th>           <!-- COLUMNA # 15 -->
                                                    <th style="font-size: .9em">IMPORTE APORTADO</th>       <!-- COLUMNA # 16 -->
                                                    <th style="font-size: .9em">MANTENIMIENTO</th>          <!-- COLUMNA # 17 -->
                                                    <th style="font-size: .9em">MOTIVO</th>                 <!-- COLUMNA # 18 -->
                                                    <th style="font-size: .9em">ULT VOBO</th>               <!-- COLUMNA # 19 -->
                                                    <th style="font-size: .9em">FECHA VOBO</th>             <!-- COLUMNA # 20 SHOW TABLE-->
                                                    <th style="font-size: .9em">CANTIDAD</th>               <!-- COLUMNA # 21 -->
                                                    <th style="font-size: .9em">PAGADO</th>                 <!-- COLUMNA # 22 -->
                                                    <th style="font-size: .9em">RESTANTE</th>               <!-- COLUMNA # 23 SHOW TABLE-->
                                                    <th style="font-size: .9em">DIAS T</th>                 <!-- COLUMNA # 24 SHOW TABLE-->
                                                    <th style="font-size: .9em">RECHAZOS</th>               <!-- COLUMNA # 25 SHOW TABLE-->
                                                    <th style="font-size: .9em">ESTATUS</th>                <!-- COLUMNA # 26 SHOW TABLE-->
                                                    <th style="font-size: .9em">F CAPTURA</th>              <!-- COLUMNA # 27 SHOW TABLE-->
                                                    <th style="font-size: .9em">DETALLE MOTIVO</th>         <!-- COLUMNA # 28 FECHA: 20-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                                                    <th style="font-size: .9em">REF. LOTE</th>              <!-- COLUMNA # 29 FECHA: 07-ABRIL-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                                                    <th style="font-size: .9em">ESTATUS LOTE</th>           <!-- COLUMNA # 30 FECHA: 16-ABRIL-2025 | @uthor Efrain Martinez Muñoz <programador.analista38@ciudadmaderas.com> -->    
                                                    <th></th>
                                                </tr>
                                            </thead>
                                        </table> <!-- FIN FECHA: 27-FEBRERO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="devoluciones_parcialidad">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12">
                                        <table class="table table-striped" id="tabla_devoluciones_parcialidad">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th style="font-size: .9em">#</th>
                                                    <th style="font-size: .9em">PERIODOS</th>
                                                    <th style="font-size: .9em">TITULAR</th>
                                                    <th style="font-size: .9em">TOTAL</th>
                                                    <th style="font-size: .9em">FORMA PAGO</th>
                                                    <th style="font-size: .9em">TOTAL PAGADO</th>
                                                    <th style="font-size: .9em">PARCIALIDAD</th>
                                                    <th style="font-size: .9em"># PAGO</th>
                                                    <th style="font-size: .9em">FECHA INICIO PARCIALIDAD</th>
                                                    <th style="font-size: .9em">PROXIMA FECHA PAGO</th>
                                                    <th style="font-size: .9em">ESTATUS</th>
                                                    <th nowrap style="font-size: .9em"></th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--End tab content-->
                </div>
                <!--end box-->
            </div>
        </div>
    </div>
</div>

<div id="mensaje_correcto" class="modal modal-default error_duplicidad fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><span id="titulo_modal"></span></h4>

            </div>

            <div class="modal-body text-center">
                <span id="mensaje_modal"></span>
            </div>
        </div>
    </div>
</div>
<!-- Modal para el Alta de una solicitud. -->
<div id="modal_formulario_solicitud" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="titulo-modal"></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <form id="frmnewsol" method="post" action="#" onkeydown="return event.key != 'Enter';">
                            <div class="row">
                                <div class="col-lg-12 form-group">
                                    <label for="proceso">PROCESO<b style="color: red;">*</b></label>
                                    <select name="proceso"
                                            id="proceso"
                                            class="form-control"
                                            required>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 form-group">
                                    <label for="proyecto">PROYECTO<b style="color: red;">*</b></label>
                                    <select name="proyecto"
                                            id="proyecto"
                                            class="form-control"
                                            placeholder="Seleccione una opción"
                                            required>
                                    </select>
                                </div>

                            </div>
                            <div class="row"> <!-- FECHA: 29-ABRI-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                                <div class="col-lg-12 form-group">
                                    <label for="desarrollo"> <!-- INICIO FECHA: 08-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                                        DESARROLLO <b style="color: red;">*</b>
                                        <i class="far fa-question-circle faq" tabindex="0" data-container="body" data-trigger="focus" 
                                            data-toggle="popover" 
                                            title="NOTA:" 
                                            data-content="En este campo, si se ingresa o asigna de forma manual, deberá utilizarse la abreviatura correcta del CRM, tal como aparece en el sistema. Evite escribir el nombre completo del desarrollo."
                                            data-placement="right">
                                        </i>
                                        <button type="button" id="btnReset" class="btn btn-xs btn-warning mt-2" style="display: none;" title="Limpiar"><i class="fas fa-sync-alt"></i></button> 
                                    </label> <!-- FIN FECHA: 08-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                                    <select name="desarrollo" id="desarrollo" style="width: 100%;" required>
                                        <option value="">--- Seleccione una opción ---</option>
                                        <option value="AFM">ASIGNAR MANUALMENTE</option>
                                    </select>
                                    <input type="text" id="inputManualDesarrollo" class="form-control mt-2" placeholder="Asignar desarrollo manualmente" style="display: none;">
                                    <div id="errorDesarrollo" class="text-danger" style="display:none;"></div> <!-- Mensaje de error -->
                                </div>
                            </div>
                            <div id="wrapperCondominioLote">
                                <div class="row">
                                    <div class="col-lg-6 form-group">
                                        <label for="condominios">CONDOMINIO <b style="color: red;">*</b> <!-- INICIO FECHA: 08-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                                            <i class="far fa-question-circle faq" tabindex="0" data-container="body" data-trigger="focus" 
                                                data-toggle="popover" 
                                                title="NOTA:" 
                                                data-content="En este campo, si se ingresa o asigna de forma manual, deberá utilizarse la abreviatura correcta del CRM, tal como aparece en el sistema. Evite escribir el nombre completo del condominio." 
                                                data-placement="right">
                                            </i>
                                        </label> <!-- FIN FECHA: 08-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                                        <select name="condominios" id="condominios" style="width: 100%;" required disabled>
                                            <option value="">--- Seleccione una opción ---</option>
                                            <option value="AFM">ASIGNAR MANUALMENTE</option>
                                        </select>
                                        <input type="text" id="inputManualCondominio" style="display: none;" class="form-control" placeholder="Asignar condominio manualmente">
                                        <div id="errorCondominio" class="text-danger" style="display:none;"></div> <!-- Mensaje de error -->
                                    </div>
                                    <div class="col-lg-6 form-group">
                                        <label for="lote">LOTE <b style="color: red;">*</b> <!-- INICIO FECHA: 08-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                                            <i class="far fa-question-circle faq" tabindex="0" data-container="body" data-trigger="focus" 
                                                data-toggle="popover" 
                                                title="NOTA:" 
                                                data-content="En este campo, si se ingresa o asigna de forma manual, deberá utilizarse la abreviatura correcta del CRM, tal como aparece en el sistema. Evite escribir el nombre completo del lote." 
                                                data-placement="right">
                                            </i>
                                        </label> <!-- FIN FECHA: 08-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                                        <select name="lote" id="lote" style="width: 100%;" required disabled>
                                            <option value="">--- Seleccione una opción ---</option>
                                            <option value="AFM">ASIGNAR MANUALMENTE</option>
                                        </select>
                                        <input type="hidden" id="idLoteSeleccionado" name="idLoteSeleccionado" value="">
                                        <input type="text" id="inputManualLote" style="display:none;" class="form-control" placeholder="Asignar número de lote manualmente">
                                        <div id="errorLote" class="text-danger" style="display:none;"></div> <!-- Mensaje de error -->
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 form-group">
                                        <label for="Referencia">REF. LOTE</label>
                                        <input type="text" id="referencia" name="referencia" value="" class="form-control" placeholder="Referencia lote" disabled>
                                    </div>
                                    <div class="col-lg-6 form-group">
                                        <label for="Etapa">RESULTADO</label>
                                        <input type="text" id="condominioCompleto" value="" class="form-control" placeholder="Resultado final" disabled>
                                    </div>
                                </div>
                            </div> <!-- FECHA: 29-ABRI-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                            <div class="row" id="etapa_ad">
                                <div class="col-lg-12 form-group">
                                    <label for="Etapa">ETAPA</label>
                                    <input type="text" name="etapa" class="form-control" id="Etapa" placeholder="Etapa">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 form-group">
                                    <label for="empresa">EMPRESA<b style="color: red;">*</b></label>
                                    <select name="empresa" id="empresa" class="form-control lista_empresa" required></select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 form-group">
                                    <label for="proveedor">NOMBRE DEL TITULAR<b style="color: red;">*</b></label>
                                    <input type="text" name="titular" id="titular" class="form-control" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 form-group">
                                    <label for="fecha">F ENTREGA PV<b style="color: red;">*</b></label>
                                    <input type="date" class="form-control" min='2019-04-01' id="fecha" name="fecha" placeholder="Fecha" value="" required>
                                </div>
                                <div class="col-lg-4 form-group">
                                    <label for="total">TOTAL $<b style="color: red;">*</b></label>
                                    <input type="number" class="form-control" id="total" name="total" placeholder="Total" min="0.01" value="" required>
                                </div>
                                <div class="col-lg-4 form-group">
                                    <label for="total">MONEDA<b style="color: red;">*</b></label>
                                    <select class="form-control" id="moneda" name="moneda" required>
                                        <option value="MXN" data-value="MXN">MXN</option>
                                        <option value="USA" data-value="USA">USA</option>
                                        <option value="CAD" data-value="CAD">CAD</option>
                                    </select>
                                </div>
                            </div> 
                            <div class="row cuenta_orden_cuenta_contable hidden">
                                <div class="col-lg-6 form-group">
                                    <div class="form-group">
                                        <label>CUENTA CONTABLE</label>
                                        <input type="text" class="form-control" id="cuenta_contable" name="frmnewsol_cuenta_contable" placeholder="Cuenta contable">
                                    </div>
                                </div>
                                <div class="col-lg-6 form-group">
                                    <div class="form-group">
                                        <label>IMPORTE CUENTA DE ORDEN</label>
                                        <input type="text" class="form-control" id="cuenta_orden" name="frmnewsol_cuenta_orden" placeholder="Importe cuenta de orden">
                                    </div>
                                </div>
                            </div> 
                            <div class="row hidden" id="rowTipoResicion">
                                <div class="col-lg-12 form-group">
                                    <label for="tipoResicion">TIPO DE RESICIÓN</label>
                                    <select class="form-control" id="tipoResicion" name="tipoResicion" required>
                                        <option value="">Seleccione una opción</option>
                                        <option value="M">MONTO</option>
                                        <option value="T">TIEMPO</option>
                                        <option value="MA">MANUAL</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row" id="rowFechasParcialidades">
                            </div> 
                            <div class="row" id="rowTablaPagos">
                            </div>                          
                            <div class="row">
                                <div class="col-lg-12 form-group">
                                    <h5><b>DATOS BANCARIOS</b> <i class="far fa-question-circle faq" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" title="Datos de la factura" data-content="En caso de no tener la factura, deberás llenar los campos de descripción, subtotal, IVA, total y método de pago, así mismo, deberás de especificar en observaciones el por qué no tiene factura. Recuerda especificar si te darán factura después." data-placement="right"></i></h5>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-8 form-group">
                                    <label for="proveedor">BENEFICIARIO<b style="color: red;">*</b></label>
                                    <select id="idproveedor_ad" name="idproveedor_ad" class="form-control show-tick" data-live-search="true" style="display :none" required>
                                        <option value="">--Selecciona una opción--</option>
                                    </select>
                                    <input list="browsers" name="nombreproveedor" id="nombreproveedor" class="form-control" required>
                                    <datalist id="browsers"></datalist>
                                </div>
                                <div class="col-lg-4 form-group">
                                    <label for="forma_pago">FORMA DE PAGO<b style="color: red;">*</b></label>
                                    <select class="form-control" id="forma_pago" name="forma_pago" required>
                                        <option value="" disabled selected>Seleccione una opción</option>
                                        <option id='NA' value="NA" data-value="NA">NA</option>
                                        <option id='ECHQ' value="ECHQ" data-value="ECHQ">Cheque</option>
                                        <option id='TEA' value="TEA" data-value="TEA">Transferencia electrónica</option>
                                        <option id='MAN' value="MAN" data-value="MAN">Transferencia extranjero</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row hidden" id="rowTablaProveedores">
                                <div class="col-lg-12 form-group" id="rowTablaProveedoresTabla">
                                </div>
                                <div class="col-lg-12 form-group">
                                </div>
                            </div>
                            <div class="row hidden" id="dev_TEA">
                                <div class="col-lg-4 form-group">
                                    <label>TIPO DE CUENTA<b style="color: red;">*</b><i class="far fa-question-circle faq" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" title="Modificación de datos bancarios" data-content="Esta información debe ser requerida al personal correspondiente, ya que en esta sección no son editables, sólo son visibles para fines informativos" data-placement="right"></i></label>
                                    <select class="form-control tipoctaselect" id="tipocta" name="tipocta" required>
                                        <option value="">Seleccione una opción</option>
                                        <option value="1">Cuenta en Banco del Bajio</option>
                                        <option value="3">Tarjeta de débito / crédito</option>
                                        <option value="40">CLABE</option>
                                    </select>
                                </div>
                                <div class="col-lg-4 form-group">
                                    <label>No. DE CUENTA<b style="color: red;">*</b></label>
                                    <input type="text" name="cuenta" id="cuenta" class="form-control cuenta" required>
                                </div>
                                <div class="col-lg-4 form-group">
                                    <label>BANCO<b style="color: red;">*</b></label>
                                    <select name="idbanco" class="form-control" id="idbanco" required>
                                        <option value="">Seleccione una opción</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row hidden" id="dev_MAN">
                                <div class="col-lg-12 form-group">
                                    <label>No. DE CUENTA<b style="color: red;">*</b></label>
                                    <input type="text" name="cuenta_extr" id="cuenta_extr" class="form-control" required>
                                </div>
                                <div class="col-lg-6 form-group">
                                    <label>PLAZA<b style="color: red;">*</b></label>
                                    <input type="text" name="sucursal_ext" id="sucursal_ext" class="form-control">
                                </div>
                                <div class="col-lg-6 form-group">
                                    <label>BANCO<b style="color: red;"></b></label>
                                    <input type="text" name="banco_ext" id="banco_ext" class="form-control" required>
                                </div>
                                <div class="col-lg-6 form-group">
                                    <label>ABA/SWIFT<b style="color: red;"></b></label>
                                    <input type="text" name="aba" id="aba" class="form-control" required>
                                </div>
                                <div class="col-lg-6 form-group">
                                    <label>REFERENCIA<b style="color: red;"></b></label>
                                    <input type="text" name="referencia_ext" id="referencia_ext" class="form-control" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 form-group">
                                    <label for="costo_lote">COSTO DE LOTE</label>
                                    <input type="text" placeholder="Costo de lote" class="form-control" maxlength="25" id="costo_lote" name="costo_lote" >
                                </div>
                                <div class="col-lg-4 form-group">
                                    <label for="superficie">SUPERFICIE M2</label>
                                    <input type="text" placeholder="Superficie" class="form-control" maxlength="25" id="superficie" name="superficie" >
                                </div>
                                <div class="col-lg-4 form-group">
                                    <label for="preciom">PRECIO M2</label>
                                    <input type="text" placeholder="Precio" class="form-control" maxlength="25" id="preciom" name="preciom" >
                                </div>
                                <div class="col-lg-4 form-group">
                                    <label for="predial">PREDIAL</label>
                                    <input type="text" placeholder="Predial" class="form-control" maxlength="25" id="predial" name="predial" >
                                </div>
                                <div class="col-lg-4 form-group">
                                    <label for="penalizacion">% PENALIZACIÓN</label>
                                    <input type="text" placeholder="Porcentaje Penalización" class="form-control" maxlength="25" id="por_penalizacion" name="por_penalizacion" > <!-- FECHA: 08-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                                </div>
                                <div class="col-lg-4 form-group">
                                    <label for="penalizacion">PENALIZACIÓN</label>
                                    <input type="text" placeholder="Monto Penalización" class="form-control" maxlength="25" id="penalizacion" name="penalizacion" >
                                </div>
                                <div class="col-lg-6 form-group">
                                    <label for="importe_aportado">IMPORTE APORTADO</label>
                                    <input type="text" placeholder="Penalización" class="form-control" maxlength="25" id="importe_aportado" name="importe_aportado" >
                                </div>
                                <div class="col-lg-6 form-group">
                                    <label for="importe_aportado">MANTENIMIENTO</label>
                                    <input type="text" placeholder="Mantenimiento" class="form-control" maxlength="25" id="mantenimiento" name="mantenimiento" >
                                </div>
                            </div>
                            <div class="row" id="div_motivo" style="display:none;">
                                <div class="col-lg-12 form-group">
                                    <div class="form-group">
                                        <label>MOTIVO RESCISIÓN<b style="color: red;">*</b></label>
                                        <select class="form-control" id="tipo_motivo" name="tipo_motivo" required> <!-- INICIO FECHA: 17-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                                            <option value="">Seleccione una opción</option>
                                            <option value="ADMINISTRACION">ADMINISTRACIÓN</option>
                                            <option value="DESEMPLEO">DESEMPLEO</option>
                                            <option value="DISMINUCION DE INGRESOS">DISMINUCIÓN DE INGRESOS</option>
                                            <option value="ECONOMICO">ECONÓMICO</option>
                                            <option value="FALLECIMIENTO">FALLECIMIENTO</option>
                                            <option value="FALTA INTERÉS">FALTA INTERÉS</option>
                                            <option value="INSEGURIDAD">INSEGURIDAD</option>
                                            <option value="JURIDICO">JURIDICO</option>
                                            <option value="MAL SERVICIO DE COBRANZA">MAL SERVICIO DE COBRANZA</option>
                                            <option value="MAL SERVICIO DE PV">MAL SERVICIO DE PV</option>
                                            <option value="PAGO ERRONEO">PAGO ERRÓNEO</option>
                                            <option value="POSTVENTA">POSTVENTA</option>
                                            <option value="SALUD">SALUD</option>
                                            <option value="VENTAS">VENTAS</option>
                                            <option value="OTRO">OTRO</option>
                                        </select> <!-- FIN FECHA: 17-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                                        <input type="hidden" name="proceso_motivo" id="proceso_motivo" class="form-control"/>
                                    </div>
                                    <div class="form-group"> <!-- INICIO FECHA: 20-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                                        <label>EXPLICACIÓN DEL MOTIVO<b style="color: red;">*</b></label>
                                        <textarea class="form-control" id="detalle_motivo" name="detalle_motivo" rows="3" minlength="15" maxlength="250" style="min-width: 100%; max-width: 100%;" required></textarea>
                                    </div> <!-- FIN FECHA: 20-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 form-group">
                                    <label for="solobs">JUSTIFICACIÓN <i class="far fa-question-circle faq" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" title="Observaciones de la solicitud" data-content="En caso de ser necesario, se podrán especificar datos, comentarios u observaciones para cuentas por pagar, DG, DP o para la dirección del departamento solicitante." data-placement="right"></i></label>
                                    <textarea class="form-control" id="solobs" name="solobs" rows="3" placeholder="Observaciones de la solicitud" style="min-width: 100%; max-width: 100%;" required></textarea> <!-- FECHA: 20-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
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

<div class="modal fade" id="modal_reubicar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">REGRESAR <span id="txt_proceso"> </span> #<span id="idsol_regre"> </span><span id="idsol_regre_new"> </span></h4>
            </div>
            <form id="comentario" method="post" action="#" onkeydown="return event.key != 'Enter';">
                <div class="modal-body">

                    <div class="row">
                        <div class="col-lg-12 form-group">
                            <h5>Seleccione el área al que desea regresar este proceso</h5>
                        </div>
                        <input type="hidden" id="iddocumento" name="iddocumento" />
                        <!-- <div class="col-lg-12 form-group" id="radios"> -->
                        <div class="col-lg-12 form-group">

                            <div class="form-group">
                                <label>ÁREAS</label>
                                <select class="form-control" id="radios" name="radios" required>
                                    <option value="">Seleccione una opción</option>

                                </select>
                            </div>

                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>Comentario</label>
                                <textarea id='text_comentario' name="text_comentario" rows='4' style='margin: 0px; width: 570px;' required></textarea>
                            </div>

                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary" id="regresar_sol">Regresar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="modal_comentario_avanzar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">AVANZAR <span id="span_proceso"> </span> #<span id="id_sol_avancom"> </span> </h4>
            </div>
            <form id="comentario_avanzar" method="post" action="#" onkeydown="return event.key != 'Enter';">
                <div class="modal-body">
                    <input type="hidden" id="id_sol_avancom1" name="id_sol_avancom1" />
                    <div class="row hidden cuenta_orden_cuenta_contable">
                        <div class="col-lg-6 form-group">
                            <div class="form-group">
                                <label>CUENTA CONTABLE</label>
                                <input type="text" class="form-control" id="comentario_avanzar_cuenta_contable" name="cuenta_contable" placeholder="Cuenta contable">
                            </div>
                        </div>
                        <div class="col-lg-6 form-group">
                            <div class="form-group">
                                <label>IMPORTE CUENTA DE ORDEN</label>
                                <input type="text" class="form-control" id="comentario_avanzar_cuenta_orden" name="cuenta_orden" placeholder="Importe cuenta de orden">
                            </div>
                        </div>
                    </div>  
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>Comentario</label>
                                <textarea id='text_comentario_ava' name="text_comentario_ava" rows='4' style='margin: 0px; width: 570px;' required></textarea>
                            </div>

                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Avanzar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_reubicar_avanzar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">AVANZAR <span id="text_proceso"></span> #<span id="sol_modal_avanzar"></span></h4>
            </div>
            <form id="areas_avanzar_form" method="post" action="#" onkeydown="return event.key != 'Enter';">
                <div class="modal-body">

                    <div class="row">
                        <div class="col-lg-12 form-group">
                            <h5>Seleccione el área al que desea avanzar el proceso</h5>
                        </div>

                        <!-- <div class="col-lg-12 form-group" id="radios"> -->
                        <div class="col-lg-12 form-group">
                            <input type="hidden" name="idsol_area" id="idsol_area" />
                            <div class="form-group">
                                <label>ÁREAS</label>
                                <select class="form-control" id="areas_avanzar" name="areas_avanzar" required>
                                    <option value="">Seleccione una opción</option>

                                </select>
                            </div>

                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary" id="avanzar_area">Avanzar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- @uthor Efrain Martinez Muñoz | Fecha : 27/03/2025 | Se crea el modal en el cual el usuario podra seleccionar el estatus del lote y cargar la imagen en casa de que ya cuente con ella -->
<div class="modal fade" id="modal_subir_imagen" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">TIPO DE LOTE #<span id="id_sol"></span></h4>
            </div>
            <form id="cargar_imagen" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="text-center">
                        <h4>Selecciona el estatus del lote.</h4>
                    </div>
                    <input type="hidden" id="id_sol_input">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-check">
                                <input type="checkbox" name="estatus_lote" class="form-check-input" id="baldio" value="7">
                                <label class="form-check-label" for="baldio">BALDÍO</label>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-check">
                                <input type="checkbox" name="estatus_lote" class="form-check-input" id="en_construccion" value="6">
                                <label class="form-check-label" for="en_construccion">EN CONSTRUCCIÓN    </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group text-center" id="carga_imagen" style="display: none;">
                        <label for="subir_imagen">Carga la imagen del lote</label>
                        <input type="file" class="form-control" name="subir_imagen" id="subir_imagen" onchange="previsualizarImagen(event)">
                    </div>
                    <div class="col-lg-12" id="contenedorImagen" style="display: none; text-align: center;">
                        <img id="imagenActual" src="" alt="Imagen actual" class = "img-fluid m-3">
                    </div>
                </div>
                <div class="modal-footer" style="text-align: center; width: 100%;">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-success" id="guardar_estatus_lote">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- FIN -->
<div class="modal fade" id="modal_cuenta_contable" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">AVANZAR PROCESO #<span id="idsol_ad1"></span></h4>
            </div>
            <form id="cuenta_contable_form" method="post" action="#">
                <div class="modal-body">
                    <div id="solictud_rechazada" class="row hidden">
                        <div class="col-lg-12 form-group">
                            <input type="hidden" name="idsol_area_new" id="idsol_area_new" />
                            <div class="form-group">
                                <label>SELECCIONE EL ÁREA AL QUE DESEA AVANZAR EL PROCESO</label>
                                <select class="form-control" id="areas_avanzar_new" name="areas_avanzar_new" required></select>
                            </div>
                        </div>
                    </div>
                    <div id="condominioRefLoteAd"> <!-- FECHA: 29-ABRIL-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                    </div> <!-- FECHA: 29-ABRIL-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                    <div class="row">
                        <div class="col-lg-8 form-group">
                            <label for="proveedor">BENEFICIARIO<b style="color: red;">*</b></label>
                            <select id="idproveedor_ad_new" name="idproveedor_ad_new" class="form-control show-tick" data-live-search="true" style="display :none" required>
                                <option value="">Selecciona una opción</option>
                            </select>
                            <input list="browsers" name="nombreproveedor_new" id="nombreproveedor_new" class="form-control">
                            <datalist id="browsers"></datalist>
                            <input type="hidden" id="idproveedor_new" name="idproveedor_new">
                        </div>
                        <div class="col-lg-4 form-group">
                            <label>FORMA DE PAGO<b style="color: red;">*</b><i class="far fa-question-circle faq" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" title="Modificación de datos bancarios" data-content="Esta información debe ser requerida al personal correspondiente, ya que en esta sección no son editables, sólo son visibles para fines informativos" data-placement="right"></i></label>
                            <select class="form-control tipoctaselect" id="nforma_pago_new" name="nforma_pago">
                                <option value="">Seleccione una opción</option>
                                <option id="NA" value="NA" data-value="NA">NA</option>
                                <option id="ECHQ" value="ECHQ" data-value="ECHQ">Cheque</option>
                                <option id="TEA" value="TEA" data-value="TEA">Transferencia electrónica</option>
                                <option id="MAN" value="MAN" data-value="MAN">Transferencia extranjero</option>
                            </select>
                        </div>
                        <hr/>
                        <div class="row hidden" id="rowTablaProveedoresNew">
                            <div class="col-lg-12 form-group" id="rowTablaProveedoresTablaNew">
                            </div>
                            <div class="col-lg-12 form-group">
                            </div>
                        </div>
                        <div class="row_cta_new" style="display:none">
                            <div class="col-lg-4 form-group">
                                <label>TIPO CUENTA<b style="color: red;">*</b><i class="far fa-question-circle faq" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" title="Modificación de datos bancarios" data-content="Esta información debe ser requerida al personal correspondiente, ya que en esta sección no son editables, sólo son visibles para fines informativos" data-placement="right"></i></label>
                                <select class="form-control tipoctaselect" id="tipocta_new" name="tipocta_new" required>
                                    <option value="">Seleccione una opción</option>
                                    <option value="1">Cuenta en Banco del Bajio</option>
                                    <option value="3">Tarjeta de débito / crédito</option>
                                    <option value="40">CLABE</option>
                                </select>
                            </div>
                            <div class="col-lg-4 form-group">
                                <label>No. DE CUENTA<b style="color: red;">*</b></label>
                                <input type="text" name="cuenta_new_TEA" id="cuenta_new" class="form-control cuenta" required>
                            </div>
                            <div class="col-lg-4 form-group">
                                <label>BANCO<b style="color: red;">*</b></label>
                                    <select name="idbanco_TEA" class="form-control listado_bancos" id="idbanco_new" required>
                                    <option value="">Seleccione una opción</option>
                                </select>
                            </div>
                        </div>
                        <hr/>
                        <div class="row_extran_new" style="display:none">
                            <div class="col-lg-6 form-group">
                                <label>No. DE CUENTA<b style="color: red;">*</b></label>
                                <input type="text" name="cuenta_new_MAN" id="cuenta_extr_new" class="form-control" required>
                            </div>
                            <div class="col-lg-6 form-group">
                                <label>PLAZA</label>
                                <input type="text" name="sucursal_ext_new" id="sucursal_ext_new" class="form-control">
                            </div>
                            <div class="col-lg-4 form-group">
                                <label>BANCO<b style="color: red;">*</b></label>
                                <input type="text" name="banco_ext_new" id="banco_ext_new" class="form-control" required>
                            </div>
                            <div class="col-lg-4 form-group">
                                <label>ABA/SWIFT<b style="color: red;">*</b></label>
                                <input type="text" name="aba_new" id="aba_new" class="form-control" required>
                            </div>
                            <div class="col-lg-4 form-group">
                                <label>REFERENCIA<b style="color: red;"></b></label>
                                <input type="text" name="referencia_ext_new" id="referencia_ext_new" class="form-control">
                            </div>
                            <input type="hidden" name="idbanco_MAN" id="idbanco_xx_new" class="form-control" required>
                        </div>
                        <div class="col-lg-6 form-group">
                            <label for="costo_lote">COSTO DE LOTE</label>
                            <input type="text" placeholder="Costo de lote" class="form-control" maxlength="25" id="costo_lote_new" name="costo_lote_new" >
                        </div>
                        <div class="col-lg-6 form-group">
                            <label for="superficie">SUPERFICIE M2</label>
                            <input type="text" placeholder="Superficie" class="form-control" maxlength="25" id="superficie_new" name="superficie_new" >
                        </div>
                        <div class="col-lg-6 form-group">
                            <label for="preciom">PRECIO M2</label>
                            <input type="text" placeholder="Precio" class="form-control" maxlength="25" id="preciom_new" name="preciom_new" >
                        </div>
                        <div class="col-lg-6 form-group">
                            <label for="predial">PREDIAL</label>
                            <input type="text" placeholder="Predial" class="form-control" maxlength="25" id="predial_new" name="predial_new" >
                        </div>
                        <div class="col-lg-6 form-group">
                            <label for="penalizacion">% PENALIZACIÓN</label>
                            <input type="text" placeholder="Penalización" class="form-control" maxlength="25" id="por_penalizacion_new" name="por_penalizacion_new" >
                        </div>
                        <div class="col-lg-6 form-group">
                            <label for="penalizacion">$ PENALIZACIÓN</label>
                            <input type="text" placeholder="Penalización" class="form-control" maxlength="25" id="penalizacion_new" name="penalizacion_new" >
                        </div>
                        <div class="col-lg-6 form-group">
                            <label for="importe_aportado">IMPORTE APORTADO</label>
                            <input type="text" placeholder="Penalización" class="form-control" maxlength="25" id="importe_aportado_new" name="importe_aportado_new" >
                        </div>
                        <div class="col-lg-6 form-group">
                            <div class="form-group">
                                <label>CUENTA CONTABLE</label>
                                <input type="text" class="form-control" id="cuenta_contable" name="cuenta_contable" placeholder="Cuenta contable">
                                <input type="hidden" class="form-control" id="idsol_ad" name="idsol_ad">
                            </div>
                        </div>
                        <div class="col-lg-6 form-group">
                            <div class="form-group">
                                <label>IMPORTE CUENTA DE ORDEN</label>
                                <input type="text" class="form-control" id="cuenta_orden" name="cuenta_orden" placeholder="Importe cuenta de orden">
                            </div>
                        </div>
                        <div class="col-lg-6 form-group">
                            <div class="form-group">
                                <label>ETAPA</label>
                                <input name="etapa_conta" id="etapa_conta" class="form-control"/>
                            </div>
                        </div>
                        <div class="col-lg-6 form-group">
                            <div class="form-group">
                                <label>MANTENIMIENTO</label>
                                <input name="mantenimiento_new" id="mantenimiento_new" class="form-control"/>
                            </div>
                        </div>
                        <div class="col-lg-6 form-group">
                            <div class="form-group">
                                <label>EMPRESA<b style="color: red;">*</b></label>
                                <select name="empresa_ad" id="empresa_ad" class="form-control lista_empresa" required></select>
                            </div>
                        </div>
                        <input type="hidden" id="edita" name="edita"/>
                        <div class="col-lg-12 form-group" id="div_copropiedad">
                            <div class="form-group">
                                <label>NOMBRE COPROPIETARIO<b style="color: red;">*</b></label>
                                <input type="text" name="nom_copropiedad" id="nom_copropiedad" class="form-control" disabled/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 form-group">
                            <label for="solobs">JUSTIFICACIÓN<b style="color: red;">*</b><i class="far fa-question-circle faq" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" title="Observaciones de la solicitud" data-content="En caso de ser necesario, se podrán especificar datos, comentarios u observaciones para cuentas por pagar, DG, DP o para la dirección del departamento solicitante." data-placement="right"></i></label>
                            <textarea class="form-control" id="solobs_ad" name="solobs_ad" placeholder="Observaciones de la solicitud"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">CANCELAR</button>
                    <button type="submit" class="btn btn-success" id="avanzar_sol_ad">AVANZAR</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal" tabindex="-1" role="dialog" id="modal_confirm">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Borrar documento</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>¿Está seguro que desea eliminar el documento?.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="eliminar_doc_modal">Eliminar</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modal_registro_docs" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"><b>REGISTRO DE DOCUMENTACIÓN</b></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                    <form action="#" method="POST" id="regDocReestructura">
                        <div class="row">
                            <div class="col-lg-6 form-group" style="display: flex; flex-direction: column;">
                                <button style="margin:2px; background-color: transparent; border: none; display: flex; align-items: center; flex-direction: column;"
                                        type="button"
                                        class="d-flex align-items-center"
                                        id="IDEOV"
                                        title="SUBIR ARCHIVO 'IDENTIFICACIÓN OFICIAL VIGENTE'">
                                    <i class="fas fa-cloud-upload-alt" style="color: #eb3b3b; font-size: 24px; margin-bottom: 5px;"></i>                                    
                                    <label style="margin-bottom: 0px;" title="SUBIR ARCHIVO 'IDENTIFICACIÓN OFICIAL VIGENTE'">IDENTIFICACIÓN OFICIAL VIGENTE<span class="text-danger">*</span></label>
                                </button>
                                <div class="pdf-label">                                    
                                    <b><input type="file" style="width: 195px;" name="PDF_IDEOV" id="PDF_IDEOV" class="pdf-file" accept=".pdf" required></b>
                                </div>
                            </div>

                            <div class="col-lg-6 form-group" style="display: flex; flex-direction: column;">
                                <button style="margin:2px;background-color: transparent; border: none; display: flex; align-items: center; flex-direction: column;"
                                        type="button"
                                        id="DS"
                                        title="SUBIR ARCHIVO 'DEPOSITO DE SERIEDAD'">
                                    <i class="fas fa-cloud-upload-alt" style="color: #eb3b3b; font-size: 24px; margin-bottom: 5px;"></i>
                                    <label style="margin-bottom: 0px;" title="SUBIR ARCHIVO 'DEPOSITO DE SERIEDAD'">DEPOSITO DE SERIEDAD<span class="text-danger">*</span></label>
                                </button>
                                <div class="pdf-label">
                                    <b><input type="file" style="width: 195px;" name="PDF_DS" id="PDF_DS" class="pdf-file" accept=".pdf"  required></b>
                                </div>                                
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 form-group" style="display: flex; flex-direction: column;">
                                <button style="margin:2px;background-color: transparent; border: none; display: flex; align-items: center; flex-direction: column;"
                                        type="button"
                                        class="d-flex align-items-center"
                                        id="CD"
                                        title="SUBIR ARCHIVO 'COMPROBANTE DE DOMICILIO'">
                                    <i class="fas fa-cloud-upload-alt" style="color: #eb3b3b; font-size: 24px; margin-bottom: 5px;"></i>                                    
                                    <label style="margin-bottom: 0px;" title="SUBIR ARCHIVO 'COMPROBANTE DE DOMICILIO'">COMPROBANTE DE DOMICILIO<span class="text-danger">*</span></label>
                                </button>
                                <div class="pdf-label">
                                    
                                    <b><input type="file" style="width: 195px;" name="PDF_CD" id="PDF_CD" class="pdf-file" accept=".pdf"  required></b>
                                </div>
                            </div>
                            <div class="col-lg-6 form-group" style="display: flex; flex-direction: column;">                 
                                <button style="margin:2px;background-color: transparent; border: none; display: flex; align-items: center; flex-direction: column;"
                                        type="button"
                                        class="d-flex align-items-center"
                                        id="RPNC"
                                        title="SUBIR ARCHIVO 'RECIBO DE PAGO DEL NUEVO CLIENTE'">
                                    <i class="fas fa-cloud-upload-alt" style="color: #eb3b3b; font-size: 24px; margin-bottom: 5px;"></i>
                                    <label style="margin-bottom: 0px;" title="SUBIR ARCHIVO 'RECIBO DE PAGO DEL NUEVO CLIENTE'">RECIBO DE PAGO NUEVO CLIENTE<span class="text-danger">*</span></label>
                                </button>
                                <div class="pdf-label">
                                    <b><input type="file" style="width: 195px;" style="padding: 2px" name="PDF_RPNC" id="PDF_RPNC" class="pdf-file" accept=".pdf"  required></b>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 form-group" style="display: flex; flex-direction: column;">                        
                                <button style="margin:2px;background-color: transparent; border: none; display: flex; align-items: center; flex-direction: column;"
                                        type="button"
                                        class="d-flex align-items-center"
                                        id="CCDO"
                                        title="SUBIR ARCHIVO 'CARTA DE CESIÓN DE DERECHOS (ORIGINAL)'">                        
                                    <i class="fas fa-cloud-upload-alt" style="color: #eb3b3b; font-size: 24px; margin-bottom: 5px;"></i>
                                    <label style="margin-bottom: 0px;" title="SUBIR ARCHIVO 'CARTA DE CESIÓN DE DERECHOS (ORIGINAL)'">CARTA DE CESIÓN DE DERECHOS (ORIGINAL)<span class="text-danger">*</span></label>
                                </button>
                                <div class="pdf-label">
                                    
                                    <b><input type="file" style="width: 195px;" style="padding: 2px" name="PDF_CCDO" id="PDF_CCDO" class="pdf-file" accept=".pdf"  required></b>
                                </div>
                            </div>
                            <!-- #16d057 -->
                            <div class="col-lg-6 form-group" style="display: flex; flex-direction: column;">                        
                                <button style="margin:2px;background-color: transparent; border: none; display: flex; align-items: center; flex-direction: column;"
                                        type="button"
                                        class="d-flex align-items-center"
                                        id="RF"
                                        title="SUBIR ARCHIVO 'RESCISIÓN FIRMADA'">
                                    <i class="fas fa-cloud-upload-alt" style="color: #eb3b3b; font-size: 24px; margin-bottom: 5px;"></i>
                                    <label style="margin-bottom: 0px;" title="SUBIR ARCHIVO 'RESCISIÓN FIRMADA'">RESCISIÓN FIRMADA<span class="text-danger">*</span></label>
                                </button>
                                <div class="pdf-label">
                                    
                                    <b><input type="file" style="width: 195px;" style="padding: 2px" name="PDF_RF" id="PDF_RF" class="pdf-file" accept=".pdf"  required></b>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 form-group" style="display: flex; flex-direction: column;">
                                <button style="margin:2px;background-color: transparent; border: none; display: flex; align-items: center; flex-direction: column;"
                                        type="button"
                                        class="d-flex align-items-center"
                                        id="CPPT"
                                        title="SUBIR ARCHIVO 'COMPROBANTE DE PAGO POR TRÁMITE'">
                                    <i class="fas fa-cloud-upload-alt" style="color: #eb3b3b; font-size: 24px; margin-bottom: 5px;"></i>
                                    <label style="margin-bottom: 0px;" title="SUBIR ARCHIVO 'COMPROBANTE DE PAGO POR TRÁMITE''">COMPROBANTE DE PAGO POR TRÁMITE<span class="text-danger">*</span></label>
                                </button>
                                <div class="pdf-label">
                                    
                                    <b><input type="file" style="width: 195px;" style="padding: 2px" name="PDF_CPPT" id="PDF_CPPT" class="pdf-file" accept=".pdf"  required></b>
                                </div>
                            </div>

                            <div class="col-lg-6 form-group" style="display: flex; flex-direction: column;">                    
                                <button style="margin:2px;background-color: transparent; border: none; display: flex; align-items: center; flex-direction: column;"
                                        type="button"
                                        class="d-flex align-items-center"
                                        id="A2170"
                                        title="SUBIR ARCHIVO 'AUX. 2170'">
                                    <i class="fas fa-cloud-upload-alt" style="color: #eb3b3b; font-size: 24px; margin-bottom: 5px;"></i>
                                    <label for="PDF-A2170" style="margin-bottom: 0px;" title="SUBIR ARCHIVO 'AUX. 2170'">AUX. 2170<span class="text-danger">*</span></label>
                                </button>
                                <div class="pdf-label">
                                    
                                    <b><input type="file" style="width: 195px;" style="padding: 2px" name="PDF_A2170" id="PDF_A2170" class="pdf-file" accept=".pdf"  required></b>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 form-group" style="display: flex; flex-direction: column;">                        
                                <button style="margin:2px;background-color: transparent; border: none; display: flex; align-items: center; flex-direction: column;"
                                        type="button"
                                        class="d-flex align-items-center"
                                        id="AERP"
                                        title="SUBIR ARCHIVO 'AUX. ERP'">
                                    <i class="fas fa-cloud-upload-alt" style="color: #eb3b3b; font-size: 24px; margin-bottom: 5px;"></i>
                                    <label style="margin-bottom: 0px;" title="SUBIR ARCHIVO 'AUX. ERP'">AUX. ERP<span class="text-danger">*</span></label>
                                </button>
                                <div class="pdf-label">
                                    
                                    <b><input type="file" style="width: 195px;" style="padding: 2px" name="PDF_AERP" id="PDF_AERP" class="pdf-file" accept=".pdf"  required></b>
                                </div>
                            </div>

                            <div class="col-lg-6 form-group" style="display: flex; flex-direction: column;">
                                                        
                                <button style="margin:2px;background-color: transparent; border: none; display: flex; align-items: center; flex-direction: column;"
                                        type="button"
                                        class="d-flex align-items-center"
                                        id="A1150"
                                        title="SUBIR ARCHIVO 'AUX. 1150'">
                                    <i class="fas fa-cloud-upload-alt" style="color: #eb3b3b; font-size: 24px; margin-bottom: 5px;"></i>
                                    <label style="margin-bottom: 0px;" title="SUBIR ARCHIVO 'AUX. 1150'">AUX. 1150<span class="text-danger">*</span></label>
                                </button>
                                <div class="pdf-label">
                                    
                                    <b><input type="file" style="width: 195px;" style="padding: 2px" name="PDF_A1150" id="PDF_A1150" class="pdf-file" accept=".pdf"  required></b>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 form-group" style="display: flex; flex-direction: column;">                        
                                <button style="margin:2px;background-color: transparent; border: none; display: flex; align-items: center; flex-direction: column;"
                                        type="button"
                                        class="d-flex align-items-center"
                                        id="CTB"
                                        title="SUBIR ARCHIVO 'CARTA DE TRASPASO DE BONIFICACIÓN'">
                                    <i class="fas fa-cloud-upload-alt" style="color: #eb3b3b; font-size: 24px; margin-bottom: 5px;"></i>
                                    <label style="margin-bottom: 0px;" title="SUBIR ARCHIVO 'CARTA DE TRASPASO DE BONIFICACIÓN'">CARTA DE TRASPASO DE BONIFICACIÓN<span class="text-danger">*</span></label>
                                </button>
                                <div class="pdf-label">
                                    
                                    <b><input type="file" style="width: 195px;" name="PDF_CTB" id="PDF_CTB" class="pdf-file" accept=".pdf"  required></b>
                                </div>
                            </div>

                            <div class="col-lg-6 form-group" style="display: flex; flex-direction: column;">
                                                        
                                <button style="margin:2px;background-color: transparent; border: none; display: flex; align-items: center; flex-direction: column;"
                                        type="button"
                                        class="d-flex align-items-center"
                                        id="A4200"
                                        title="SUBIR ARCHIVO 'AUX. 4200'">
                                    <i class="fas fa-cloud-upload-alt" style="color: #eb3b3b; font-size: 24px; margin-bottom: 5px;"></i>
                                    <label style="margin-bottom: 0px;" title="SUBIR ARCHIVO 'AUX. 4200'">AUX. 4200<span class="text-danger">*</span></label>
                                </button>
                                <div class="pdf-label">
                                    
                                    <b><input type="file" style="width: 195px;" style="padding: 2px" name="PDF_A4200" id="PDF_A4200" class="pdf-file" accept=".pdf"  required></b>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 form-group" style="display: flex; flex-direction: column;">                        
                                <button style="margin:2px;background-color: transparent; border: none; display: flex; align-items: center; flex-direction: column;"
                                        type="button"
                                        class="d-flex align-items-center"
                                        id="RPECND"
                                        title="SUBIR ARCHIVO 'RESUMEN DE PAGOS (EDO. DE CTA. DE NEODATA)'">
                                    <i class="fas fa-cloud-upload-alt" style="color: #eb3b3b; font-size: 24px; margin-bottom: 5px;"></i>
                                    <label style="margin-bottom: 0px;" title="SUBIR ARCHIVO 'RESUMEN DE PAGOS (EDO. DE CTA. DE NEODATA)'">RESUMEN DE PAGOS (EDO. DE CTA. DE NEODATA)<span class="text-danger">*</span></label>
                                </button>
                                <div class="pdf-label">
                                    
                                    <b><input type="file" style="width: 195px;" style="padding: 2px" name="PDF_RPECND" id="PDF_RPECND" class="pdf-file" accept=".pdf"  required></b>
                                </div>
                            </div>

                            <div class="col-lg-6 form-group" style="display: flex; flex-direction: column;">
                                                        
                                <button style="margin:2px;background-color: transparent; border: none; display: flex; align-items: center; flex-direction: column;"
                                        type="button"
                                        class="d-flex align-items-center"
                                        id="A4300"
                                        title="SUBIR ARCHIVO 'AUX. 4300'">
                                    <i class="fas fa-cloud-upload-alt" style="color: #eb3b3b; font-size: 24px; margin-bottom: 5px;"></i>
                                    <label style="margin-bottom: 0px;" title="SUBIR ARCHIVO 'AUX. 4300'">AUX. 4300<span class="text-danger">*</span></label>
                                </button>
                                <div class="pdf-label">
                                    
                                    <b><input type="file" style="width: 195px;" style="padding: 2px" name="PDF_A4300" id="PDF_A4300" class="pdf-file" accept=".pdf"  required></b>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 form-group" style="display: flex; flex-direction: column;">                        
                                <button style="margin:2px;background-color: transparent; border: none; display: flex; align-items: center; flex-direction: column;"
                                        type="button"
                                        class="d-flex align-items-center"
                                        id="CL"
                                        title="SUBIR ARCHIVO 'CORRIDA DEL LOTE'">
                                    <i class="fas fa-cloud-upload-alt" style="color: #eb3b3b; font-size: 24px; margin-bottom: 5px;"></i>
                                    <label style="margin-bottom: 0px;" title="SUBIR ARCHIVO 'CORRIDA DEL LOTE'">CORRIDA DEL LOTE<span class="text-danger">*</span></label>
                                </button>
                                <div class="pdf-label">
                                    
                                    <b><input type="file" style="width: 195px;" style="padding: 2px" name="PDF_CL" id="PDF_CL" class="pdf-file" accept=".pdf"  required></b>
                                </div>
                            </div>

                            <div class="col-lg-6 form-group" style="display: flex; flex-direction: column;">
                                                        
                                <button style="margin:2px;background-color: transparent; border: none; display: flex; align-items: center; flex-direction: column;"
                                        type="button"
                                        class="d-flex align-items-center"
                                        id="CNSD"
                                        title="SUBIR ARCHIVO 'CARTA DE NO SALIDA DE DINERO'">
                                    <i class="fas fa-cloud-upload-alt" style="color: #eb3b3b; font-size: 24px; margin-bottom: 5px;"></i>
                                    <label style="margin-bottom: 0px;" title="SUBIR ARCHIVO 'CARTA DE NO SALIDA DE DINERO'">CARTA DE NO SALIDA DE DINERO<span class="text-danger">*</span></label>
                                </button>
                                <div class="pdf-label">
                                    
                                    <b><input type="file" style="width: 195px;" style="padding: 2px" name="PDF_CNSD" id="PDF_CNSD" class="pdf-file" accept=".pdf"  required></b>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-lg-12">
                                <button type="submit" class="btn btn-success btn-block">SUBIR ARCHIVOS</button>
                            </div>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade bd-example-modal-lg" id="modalHistorial" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" >
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="border-radius: 20px;">
            <div class="modal-header" style="padding-bottom: 0px;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title" id="exampleModalLongTitle"></h5>
            </div>
            <div class="row" style="padding: 4px; margin-left: 0px; margin-right: 0px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);">
                <div class="col-lg-6 text-start my-4" style="padding-left: 15px;" id="divMontoTotal">
                    <h5 class="text-muted mb-2" style="margin-bottom: 5px;">IMPORTE TOTAL A LIQUIDAR POR LA SOLICITUD:</h5>
                    <div id="montoTotal" class="display-4 fw-bold text-primary" style="font-size: 1.8rem; color: #0d6efd; margin-bottom: 5px;">
                        $0.00
                    </div>
                </div>
                <div class="col-lg-6" style="padding-right: 15px; text-align: end;" id="divNumParcialidades">
                    
                </div>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <div style="box-shadow: 0 4px 10px rgba(0, 0, 0, 0.01); text-align: left;">
                    <div id="montoTotalAutorizado" class="h4 text-dark fw-semibold" style="font-size: 1.5rem; margin-bottom: 10px; padding-bottom: 10px;">
                    </div>
                </div>
                <br>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<div id="modal_edit_forma_pago" class="modal fade" role="dialog">
</div>

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

<script type="text/javascript">

    var idsolicitud = 0;
    var fecha_minima = "1990-01-01";
    var table_autorizar, proveedores, proveedor_empresa, link_post = "", consulta = 0, opcion = 0, bancos, trglobal, tabla_devoluciones_parcialidades;
    var editproyecto = 0;
    var editcondominio = 0;
    var editdesarrollo = 0;
    var rol = $('#rol').val();
    var respuesta_etapas = []; 
    var resp_etapas = {};
    var lista_procesos;
    var clientes = [];
    //@uthor Efraín Martinez Muñoz || Fecha: 31/01/2024 || Se crea la variable iniSesion la cual contiene los datos de la persona que esta logeada
    // en el momento en el que se accede a la vista.
    var iniSesion = <?= json_encode($this->session->userdata("inicio_sesion")['id']) ?>;
    var id_usuario = <?php echo $this->session->userdata("inicio_sesion")['id']?>;
    var rolUsuario = '<?php echo $this->session->userdata("inicio_sesion")['rol']?>';
    var nombres = null;
    var fecha_hoy = new Date();
    var nombres = null;
    /**
     * En el siguiente evento se pone como predeterminada
     * (dependiendo del "Proceso") la opcion de "Forma de Pago"
     * teniendo en cuenta los siguientes ID de la lista de "Proceso"
      + IdProseso correspondiente al departamento de PostVentas:
        - 26:	CON SALIDA DE DINERO
        - 27:	CON SALIDA DE DINERO TEA
        - 28:	SIN SALIDA DE DINERO
        - 29:	CERO
        - 30:	PARCIALIDADES
      + IdProseso correspondiente al departamento de Administracion:
        - 31:   CON SALIDA DE DINERO ADMÓN
        - 32:   SIN SALIDA DE DINERO ADMÓN
        - 33:   CON SALIDA DE DINERO JURÍDICO 

    */

    var opcQuitar = {
        //PostVentas
        26: Array("NA", "TEA", "MAN"),
        27: Array("NA", "ECHQ"),
        28: Array("NA", "ECHQ"),
        29: Array("ECHQ", "TEA", "MAN"),
        30: Array("NA"),
        //Administracion
        31: Array("NA"),
        32: Array("NA", "ECHQ"),
        33: Array("NA")
    };
            
    /**
     * Escaneo de opciones a eliminar según el paso del proceso (ID del proyecto/plano).
     *
     * Este objeto se utiliza en el contexto de procesos relacionados con
     * "Parcialidades por parte de PostVenta", idproceso = 30.
     *
     * Se toma en cuenta el ID del proyecto que extrae de la base de datos.
     * Posteriormente se podria condicionar a un odproceso en conjunto al idproyecto.
     *
     * Esto permite que el sistema adapte dinámicamente las opciones mostradas
     * dependiendo del punto del flujo en el que se encuentre el usuario.
    */
    var quitarOpcProcesoProyecto = {
        213: Array("NA", "TEA", "MAN"), // RESCISIÓN PARCIALIDAD 
        221: Array("NA", "ECHQ") // RESCISIÓN PARCIALIDAD TEA
    };
    var formaPago = document.getElementById("forma_pago");
    var isClick = false;
    var proceso;

    $('#fecInicial').val(moment().subtract(1, 'years').format('L'));  // 1 año atrás desde la fecha actual /* FECHA: 21-MARZO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
    $('#fecFinal').val(moment().format('L'));  // Fecha actual /* FECHA: 21-MARZO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/

    $(document).ready(function() {
        evaluar_datos();
        ObtenerDatosElementos('nueva_sol', null);

        if( [ 'AD', 'CAD', 'GAD', 'AOO' ].includes( rol ) ){
            $( "div.cuenta_orden_cuenta_contable" ).removeClass( "hidden" );
        }else{
            $( "div.cuenta_orden_cuenta_contable" ).remove();
        }

        $('[data-toggle="tab"]').click(function(e) { /// MAR
            switch ($(this).attr('href')) {
                case '#facturas_autorizar':
                    tabla_devoluciones.ajax.reload();
                    consulta = 0;
                    break;
                case '#facturas_activas':
                    var f1=$('#fecInicial').val();
                    var f2=$('#fecFinal').val();
                    $.ajax({ 
                        "url" : url + "Devoluciones_Traspasos/tabla_facturas_encurso",
                        "type": "POST",
                        "data" : {
                            finicial : f1.substring(6,10)+'-'+f1.substring(3,5)+'-'+f1.substring(0,2),
                            ffinal : f2.substring(6,10)+'-'+f2.substring(3,5)+'-'+f2.substring(0,2)
                         },
                         success: function(result){
                           data = JSON.parse(result);
                            table_proceso.clear().draw();
                            table_proceso.rows.add(data.data); 
                            table_proceso.columns.adjust().draw();   
                         } });
                    consulta = 1;
                    break;
                case '#devoluciones_parcialidad':
                    tabla_devoluciones_parcialidades.ajax.url( url + "Devoluciones_Traspasos/tabla_devoluciones" ).load();
                break;
            }
        });

        $("#comentario_avanzar").submit(function(e) {
            e.preventDefault();
            }).validate({
                submitHandler: function(form) {
                    enviar_post2(function(respuesta) {
                        var data = new FormData($(form)[0]);
                        if (data) {
                            $('ul.nav-tabs').children('li.active').eq(0).children('a').attr('href') === '#devoluciones_parcialidad' ? tabla_devoluciones_parcialidades.row( trglobal ).remove().draw() : tabla_devoluciones.row( trglobal ).remove().draw();
                            $('#id_sol_avancom1').val('');
                            $('#modal_comentario_avanzar').modal("toggle");
                            $('#text_comentario_ava, #text_comentario_ava').html('').val('');
                            $("#comentario_avanzar input[name='cuenta_contable'], #comentario_avanzar input[name='cuenta_orden']").val();
                        } else {
                            alert("HA OCURRIDO UN ERROR")
                        }

                    }, new FormData($(form)[0]), url + 'Devoluciones_Traspasos/devolucion_sigetapa');
                }
        });
     
    }).ajaxStart($.blockUI).ajaxStop($.unblockUI);

    $("#frmnewsol #tipocta, #cuenta_contable_form #tipocta_new").change(function() {

        switch ($(this).val()) {
            case '1':
                $("#frmnewsol #cuenta, #cuenta_contable_form #cuenta_new").attr('minlength', '7');
                $("#frmnewsol #cuenta, #cuenta_contable_form #cuenta_new").attr('maxlength', '12');
                break;
            case '3':
                $("#frmnewsol #cuenta, #cuenta_contable_form #cuenta_new").attr('minlength', '16');
                $("#frmnewsol #cuenta, #cuenta_contable_form #cuenta_new").attr('maxlength', '16');
                break;
            case '40':
                $("#frmnewsol #cuenta, #cuenta_contable_form #cuenta_new").attr('minlength', '18');
                $("#frmnewsol #cuenta, #cuenta_contable_form #cuenta_new").attr('maxlength', '18');
                break;
        }

        $("#idbanco, #idbanco_new").html( '<option value="">Seleccione una opción</option>' );
        $.each(bancos, function(ind, val) {
            $("#idbanco, #idbanco_new").append("<option value='" + val.idbanco + "'>" + val.nombre + "</option>");
        });

        if ($(this).val() == 1) {
            $( "#frmnewsol #idbanco option, #cuenta_contable_form #idbanco_new option" ).each( function(){
                if( $( this ).val() != "6"){
                    $( this ).remove();
                }
            });
        } else{
            $("#frmnewsol #idbanco, #cuenta_contable_form #idbanco_new").val("");
        }
    });

    $('#frmnewsol input#cuenta, #cuenta_contable_form input#cuenta_new').keypress(function(event) {
        if (event.which < 48 || event.which > 57) {
            return false;
        }
    });

    $('.fechas_filtro').datepicker({
        format: 'dd/mm/yyyy',  /** FECHA: 17-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
        zIndexOffset: 10000, /** FECHA: 17-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
        orientation: 'bottom auto' /** FECHA: 17-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
    });

    function ObtenerDatosElementos(tipoConsulta, idProcesoEdit = null) {
        if( (proveedores !== undefined && proveedores !== null) && (lista_procesos !== undefined && lista_procesos !== null)){
            document.getElementById('proceso').options.length = 1;
            $.each(lista_procesos, function(i, v) {
                var $option = $('<option>', {
                    'data-value': v.nom_depto,
                    'value': v.idproceso,
                    'text': v.nombre,
                    'id': 'option-'+v.idproceso,
                    'data-motivo': v.motivo
                });
                if (tipoConsulta = 'nueva_sol') {
                    if(v.estatus == 1)
                        $('#proceso').append($option);
                }else{
                    if(v.estatus == 1 || v.idproceso == idProcesoEdit)
                        $('#proceso').append($option);
                }
                // $("#proceso").append('<option data-value="' + v.nom_depto + '" value="' + v.idproceso + '">' + v.nombre + '</option>');
            });

            return Promise.resolve();
        }else{
            return new Promise(function (resolve, reject) {
                $.getJSON(url + 'Listas_select/listado_devoluciones_traspasos?estatus=2', function(data) {
                    // proveedor_empresa = data.listado_proveedores_empresas;
                    // listado_proveedores_devoluciones = data.listado_proveedores_devoluciones;
                    $("#proceso").append('<option value="" disabled selected>Seleccione una opción</option>');
                    $("#proyecto").append('<option value="" disabled selected>Seleccione una opción</option>');
                    proveedores = data.listado_proveedores_empresas;
                    lista_procesos = data.lista_procesos;
                    get_proveedores_est2();
                    $.each(lista_procesos, function (indice, valor) {
                        var $option = $('<option>', {
                            'data-value': valor.nom_depto,
                            'value': valor.idproceso,
                            'text': valor.nombre,
                            'id': 'option-'+valor.idproceso,
                            'data-motivo': valor.motivo
                        });
                        
                        if (tipoConsulta == 'nueva_sol') {
                            if(valor.estatus == 1)
                                $('#proceso').append($option);
                        }else{
                            if(valor.estatus == 1 || valor.idproceso == idProcesoEdit){
                                $('#proceso').append($option);
                            }
                        }
                    });
                    
                    $.each(data.lista_proveedores_devoluciones, function(i, v) {
                        $("#browsers").append('<option data-value="' + v.idproveedor + '" value="' + v.nproveedor + '">' + v.nproveedor + '</option>');
                    });

                    $(".lista_empresa").append('<option value="">Seleccione una opción</option>');
                    $.each(data.empresas, function(i, v) {
                        $(".lista_empresa").append('<option value="' + v.idempresa + '" data-value="' + v.rfc + '">' + v.nombre + '</option>');
                    });

                    $("#proyecto, #proceso, #empresa").select2({
                        width: "100%"
                    });

                    bancos = data.bancos;
                    $.each(data.bancos, function(ind, val) {
                        $("#idbanco, #idbanco_new").append("<option value='" + val.idbanco + "'>" + val.nombre + "</option>");
                    });
                    
                    clientes = data.clientes
                    resolve();
                }).fail(function(error){
                    reject(error);
                });
            });
        }
        
    }

    //Se calcula la fecha final de los pagos por parcialidad
    function obtenerFechaFinPagos(){
        $('#fecha_Fin').val('');
        if(!$('#fecha_Inicio').val() || !$('#programado').val() || !$('#numeroPagos').val() ) return;
        

        var numeroPagos =parseInt($('#numeroPagos').val());
        var programado = parseInt($('#programado').val());
        var fechaInicio = new Date($('#fecha_Inicio').val());
        const fechaInicioFin = moment($('#fecha_Inicio').val());
        let fechaFinPago;        
        switch (programado) {
            case 7: // CASO DE ACUERDO A SOLICITUD PROGRAMADA EN MODALIDAD DE SEMANALIDADES.
                fechaFinPago = fechaInicioFin.clone().add((numeroPagos - 1), 'weeks').format("YYYY-MM-DD");
                break;
            case 8:
                fechaFinPago = fechaInicioFin.clone().add(((numeroPagos - 1) * 15), 'days').format("YYYY-MM-DD");
                break;
            case 1:
                fechaFinPago = fechaInicioFin.clone().add((numeroPagos - 1), 'months').format("YYYY-MM-DD");
                break;
            case 2:
                fechaFinPago = fechaInicioFin.clone().add(((numeroPagos - 1) * 2), 'months').format("YYYY-MM-DD");
                break;
            case 3:
                fechaFinPago = fechaInicioFin.clone().add(((numeroPagos - 1) * 3), 'months').format("YYYY-MM-DD");
                break;
            case 4:
                fechaFinPago = fechaInicioFin.clone().add(((numeroPagos - 1) * 4), 'months').format("YYYY-MM-DD");
                break;
            case 6:
                fechaFinPago = fechaInicioFin.clone().add(((numeroPagos - 1) * 6), 'months').format("YYYY-MM-DD");
                break;
        }
        fechaInicio.setDate(fechaInicio.getDate() + 1)
        var fechaFin = fechaFinPago;
        $('#fecha_Fin').val(fechaFin);
    }


    // $.getJSON(url + 'Listas_select/listado_devoluciones_traspasos?estatus=2', function(data) {
    //     proveedores = data.listado_proveedores_empresas;
    //     proveedor_empresa = data.listado_proveedores_empresas;
    //     listado_proveedores_devoluciones = data.listado_proveedores_devoluciones;
    //     get_proveedores_est2();

    //     $.each(data.lista_proveedores_devoluciones, function(i, v) {
    //         $("#browsers").append('<option data-value="' + v.idproveedor + '" value="' + v.nproveedor + '">' + v.nproveedor + '</option>');
    //     });

    //     $("#proceso").append('<option value="">Seleccione una opción</option>');
    //     $.each(data.lista_procesos, function(i, v) {
    //         $("#proceso").append('<option data-value="' + v.nom_depto + '" value="' + v.idproceso + '">' + v.nombre + '</option>');
    //     });

    //     $(".lista_empresa").append('<option value="">Seleccione una opción</option>');
    //     $.each(data.empresas, function(i, v) {
    //         $(".lista_empresa").append('<option value="' + v.idempresa + '" data-value="' + v.rfc + '">' + v.nombre + '</option>');
    //     });

    //     $("#proyecto, #proceso, #empresa, #tipo_motivo").select2({ /** FECHA: 17-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
    //         width: "100%"
    //     });

    //     bancos = data.bancos;
    //     $.each(data.bancos, function(ind, val) {
    //         $("#idbanco, #idbanco_new").append("<option value='" + val.idbanco + "'>" + val.nombre + "</option>");
    //     });
    // });
    
    // Aqui se llena de proceso_proyecto
    $('#proceso').change(function() {
        $("#proyecto").empty();
        $("#forma_pago").val('');
        
        opcion = $("#proceso").find('option:selected').attr("data-value");
        reglas = {
            required: true,
            min: 0.01
        };

        $('#fecha').parent().show();
        $('#rowTipoResicion').addClass('hidden');

        $("#NA, #ECHQ, #MAN, #TEA").show();

        if ( opcion == "TRASPASO" || opcion == "TRASPASO OOAM" ) {
            
            $("#MAN, #ECHQ, #NA").hide();
            $("#forma_pago").val("TEA");
            
            $("#row_cta").hide();
            $("#row_extran").hide();
            $("#cuenta").val('');
            $("#nombreproveedor").val('');
            $("#idproveedor").val('');
            OpcionEspecial(opcion);

            $("#rowTablaProveedores").addClass("hidden");
            $( "#frmnewsol #dev_TEA, #frmnewsol #dev_MAN" ).addClass("hidden");
        }
        else{
            if($(this).val() == 30){
                $('#fecha_Fin').prop('disabled', true);
                $('#fecha').parent().hide();
                $('#rowTipoResicion').removeClass("hidden");
                $("#nombreproveedor").val('');
                $("#idproveedor").val('');
            }
            reglas = {
                required: true,
                min: 0
            };

            $('#idproveedor_ad').css('display', 'none');
            $('#idproveedor_ad').each(function(i, obj) {
                if ($(obj).data('select2')) {
                    $("#idproveedor_ad").select2("destroy");
                }
            });
            $('#nombreproveedor').css('display', 'block');
            $('#nombreproveedor').val('');

        }
        var proceso_motivo = "";

        if($(this).val() == "34") $("#NA").hide();
        
        if($(this).val() == "35") $("#NA, #MAN, #ECHQ").hide();
        
        $('#rowTablaPagos').text('');
        $('#rowFechasParcialidades').text('')

        if($(this).val() == "30") $('#total').parent().hide()
        else $('#total').parent().show()

        $("#total").rules("remove");
        $("#total").rules("add", reglas );
        $.post(url + "Listas_select/proceso_proyectos", {
            opcion: $(this).val()
        }, function(data) {
            data = JSON.parse(data);
            
            proceso_motivo = $("#proceso").find('option:selected').attr("data-motivo");

            $("#proceso_motivo").val(proceso_motivo); /** INICIO FECHA: 20-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
            if (proceso_motivo == '1') {
                $('#div_motivo').css('display', 'block');
                $("#tipo_motivo").prop('required', true);
                $("#detalle_motivo").prop('required', true); /** FECHA: 20-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                // $("#tipo_motivo").val(data.info_solicitud[0].nom_motivo);
            } else {
                $('#div_motivo').css('display', 'none');
                $("#tipo_motivo").prop('required', false);
                $("#detalle_motivo").prop('required', false); /** FECHA: 20-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
            }

            $("#proyecto").append('<option data-value="" value="" disabled selected>--- Seleccione una opción ---</option>'); /** FECHA: 08-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
            $.each(data.lista_procesos_proyectos, function(i, v) {
                if (editproyecto == v.concepto) {
                    $("#proyecto").append('<option data-value="' + v.idproceso_proyecto + '" value="' + v.concepto + '" selected>' + v.concepto + '</option>'); /** FECHA: 08-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                } else {
                    $("#proyecto").append('<option data-value="' + v.idproceso_proyecto + '" value="' + v.concepto + '">' + v.concepto + '</option>');
                }

            });
        });
        let idProceso = $('#proceso').val();
        cambioFormaPago(idProceso, 'proceso');
    });

    function OpcionEspecial(dx) {

        $('#idproveedor_ad').css('display', 'block');
        $('#nombreproveedor').css('display', 'none');

        $('#idproveedor_ad').each(function(i, obj) {
            if ($(obj).data('select2')) {
                $("#idproveedor_ad").select2("destroy");
            }
        });

        $("#idproveedor_ad").html('<option value="">Selecciona un proveedor</option>');
        $.each(proveedores, function(i, v) {
            // if (v.estatus == 2) {
            $("#idproveedor_ad").append('<option value="' + v.idproveedor + '">' + v.nombre + ' - ' + v.alias + '</option>');
            // }
        });

        $("#idproveedor_ad").select2({
            width: 'element'
        });
    }

    $("#modal_reubicar").on('hidden.bs.modal', function() {
        $("#radios").val('');
        $("#text_comentario").val('');
    });

    $("#modal_cuenta_contable").on('hidden.bs.modal', function() {
        $("#idsol_ad").val('');
        $("#cuenta_contable").val('');
        $("#otro_campo").val('');
    });

    async function get_proveedores_est2() {
        if (rol == 'AD' || rol == 'CAD' || rol == 'GAD' || rol == 'AOO') {
            $('#idproveedor_ad').css('display', 'block');

            $('#idproveedor_ad').each(function(i, obj) {
                if ($(obj).data('select2')) {
                    $("#idproveedor_ad").select2("destroy");
                }
            });

            $("#idproveedor_ad").html('<option value="">Selecciona un proveedor</option>');
            $.each(proveedores, function(i, v) {
                $("#idproveedor_ad").append('<option value="' + v.idproveedor + '">' + v.nombre + ' - ' + v.alias + '</option>');
            });

            $("#idproveedor_ad").select2({
                width: 'element'
            });
        } else {
            $('#idproveedor_ad').css('display', 'none');
        }
    }


    $("#frmnewsol").submit(function(e) {
        e.preventDefault();
        // jQuery(this).find(':disabled').removeAttr('disabled');
        }).validate({
            submitHandler: function(form) {
                var data = new FormData($(form)[0]);
                if($('#fecha_Fin').val()) data.append("fecha_final", $('#fecha_Fin').val())
                if($('#numeroPagos').prop('disabled')) data.append("numeroPagos", $('#numeroPagos').val())
                if($('#montoParcialidad').prop('disabled')) data.append("montoParcialidad", $('#montoParcialidad').val())
                data.append("idsolicitud", idsolicitud);
                data.append("operacion", $("#proceso").find('option:selected').attr("data-value"));
                data.append("idautor",$('#idautor').val());
                /** INICIO FECHA: 29-ABRIL-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                data.append("desarrollo",$('#desarrollo').val()); /** FECHA: 08-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                data.append("condominios",$('#condominios').val()); /** FECHA: 08-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                data.append("lote",$('#lote').val()); /** FECHA: 08-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                data.append("referencia",$('#referencia').val());
                data.append("idLoteSeleccionado",$('#idLoteSeleccionado').val());
                if ($('#desarrollo').val() === 'AFM' || $('#condominios').val() === 'AFM' || $('#lote').val() === 'AFM') {
                    let isValid = true;
                    // Limpiamos mensajes de error anteriores
                    $('#errorDesarrollo').hide().text('');
                    $('#errorCondominio').hide().text('');
                    $('#errorLote').hide().text('');

                    // Validar Desarrollo (solo letras y acentos, sin espacios)
                    if ($('#inputManualDesarrollo').is(':visible')) {
                        const valorDesarrollo = $('#inputManualDesarrollo').val().trim();
                        const regexLetrasAcentos = /^[a-zA-ZáéíóúÁÉÍÓÚüÜñÑ]+$/; // Sin espacios (\s eliminado)

                        if (valorDesarrollo === '') {
                            isValid = false;
                            $('#errorDesarrollo').text('Por favor ingrese el nombre del desarrollo.').show();
                        } else if (!regexLetrasAcentos.test(valorDesarrollo)) {
                            isValid = false;
                            $('#errorDesarrollo').text('Solo se permiten letras y acentos (sin espacios).').show();
                        } else if (valorDesarrollo !== valorDesarrollo.trim()) {
                            isValid = false;
                            $('#errorDesarrollo').text('No se permiten espacios al inicio o final.').show();
                        }
                    }

                    // Validar Condominio (solo letras y acentos, sin espacios)
                    if ($('#inputManualCondominio').is(':visible')) {
                        const valorCondominio = $('#inputManualCondominio').val().trim();
                        const regexLetrasAcentos = /^[a-zA-ZáéíóúÁÉÍÓÚüÜñÑ]+$/; // Sin espacios (\s eliminado)

                        if (valorCondominio === '') {
                            isValid = false;
                            $('#errorCondominio').text('Por favor ingrese el nombre del condominio.').show();
                        } else if (!regexLetrasAcentos.test(valorCondominio)) {
                            isValid = false;
                            $('#errorCondominio').text('Solo se permiten letras y acentos (sin espacios).').show();
                        } else if (valorCondominio !== valorCondominio.trim()) {
                            isValid = false;
                            $('#errorCondominio').text('No se permiten espacios al inicio o final.').show();
                        }
                    }

                    // Validar Lote (no vacio) /** FECHA: 10-JUNIO-2025 | RETIRAR VALIDACIONES | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                    if ($('#inputManualLote').is(':visible')) {
                        const rawValorLote = $('#inputManualLote').val(); // sin trimar aún
                        const valorLote = rawValorLote.trim();

                        if (valorLote === '' || rawValorLote !== valorLote) {
                            isValid = false;
                            $('#errorLote').text('El número de lote no debe tener espacios al inicio o al final, ni estar vacío.').show();
                        }
                    }

                    // Si no es válido, no enviamos el formulario
                    if (!isValid) {
                        event.preventDefault();
                        return;
                    }

                    data.set("inputManualDesarrollo", $('#inputManualDesarrollo').val());
                    data.set("inputManualCondominio", $('#inputManualCondominio').val());
                    data.set("inputManualLote", $('#inputManualLote').val());
                }

                data.set("lote", $('#condominioCompleto').val());
                data.set("isDesarrolloManual", $('#inputManualDesarrollo').is(':visible') ? 1 : 0); /** FECHA: 08-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                data.set("isCondominioManual", $('#inputManualCondominio').is(':visible') ? 1 : 0); /** FECHA: 08-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                data.set("isLoteManual", $('#inputManualLote').is(':visible') ? 1 : 0); /** FECHA: 08-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                data.set("isReferenciaManual", $('#inputManualLote').is(':visible') ? 1 : 0); /** FECHA: 08-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                /** FIN FECHA: 29-ABRIL-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/

                $.ajax({
                    url: url + link_post,
                    data: data,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    method: 'POST',
                    type: 'POST', // For jQuery < 1.9
                    success: function(data) {
                        if (data.resultado) { /** INICIO FECHA: 07-ABRIL-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                            Swal.fire({
                                position: 'center',
                                icon: 'success',
                                title: data.msj,
                                showConfirmButton: false,
                                timer: 2000
                            }).then(() => {                            
                                $("#modal_formulario_solicitud").modal('toggle');
                                tabla_devoluciones.ajax.reload(null, false); // Evita que recargue toda la página
                                // alert(data.msj);
                                if(data.clientes){
                                    clientes = data.clientes
                                }
                            });
                        } /** FIN FECHA: 07-ABRIL-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                    },
                    error: function(data) {
                        alert("ERROR EN EL SISTEMA");
                    }
                });

            }
    });

    function resear_formulario() {
        $("#titulo-modal").text(''); /** FECHA: 20-SEPTIEMBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
        editproyecto = 0;
        editcondominio = 0;
        editdesarrollo = 0;

        if (rol == 'PV' && rol =='SPV') {
            $('#pv_form').css('display', 'block');
        }

        $('#rowTipoResicion').addClass('hidden');
        $("#row_tipo").hide();
        $("#modal_formulario_solicitud input.form-control").prop("readonly", false).val("");
        $("#modal_formulario_solicitud textarea").html('');

        $("#modal_formulario_solicitud #solobs").val('');
        $("#modal_formulario_solicitud textarea").prop("readonly", false);
        $("#empresa, #proveedor, #xmlfile").prop('disabled', false).val("").change();
        $("#empresa option, #proveedor option").prop("selected", false);
        $("#modal_formulario_solicitud .form-control, #modal_formulario_solicitud input[type='checkbox']").prop("checked", false).prop('disabled', false);

        $(".programar_fecha").prop('disabled', true);

        $("#responsable_cc").prop("disabled", true);

        $("#moneda").html("");

        $("#moneda").append('<option value="MXN" data-value="MXN">MXN</option>');
        //$("#moneda").append( '<option value="USD" data-value="USD">USD</option>' );
        $("#proceso").val('');
        var validator = $("#frmnewsol").validate();
        validator.resetForm();
        $("#frmnewsol div").removeClass("has-error");

        $("#fecha").attr("min", fecha_minima);

        if (!$("#devolucion").is(":checked")) {
            $("#devolucion").prop("checked", true).change();
        } else
            $("#devolucion").change();
        $("#proveedor").val("").hide();
        $("#idproveedor_ad").val("");

        $("#proyecto").val("").change();
        $("#etapa").val("").change();
        $("#tipo_motivo").val("").change(); /** FECHA: 17-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
        $("#detalle_motivo").val(""); /** FECHA: 20-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/

        $("#forma_pago").val('');
        $("#programado").val("");
        $("#tipocta").val("").change();
        $("#idbanco").val($("#idbanco option:first").val());
        $("#operacion").val('DEVOLUCIONES');

        $("select#tipocta").prop("required", false);
        $("#cuenta").attr("required", false);
        $("select#idbanco").prop("required", false);
        $("#row_cta").hide();
        $("#cuenta").val('');
        $("#nombreproveedor").val('');
        $("#idproveedor").val('');
        $("#idbanco").val('');
        $("#idcuenta").val('');
        $("#tipoResicion").val('')

        $('#rowTablaProveedores').addClass('hidden');
        $('#rowTablaPagos').text('');

    }

    $(document).on("click", ".abrir_nueva_solicitud", function() {
        $("#nombreproveedor").css('display', 'none');

        $("#nombreproveedor_edit").val("");
        $("#idproveedor_edit").val("");
        
        $("#frmnewsol input").prop("readonly", false);
        $("#frmnewsol select").prop("disabled", false);
        $('input[type=radio][name=tipo]').prop("disabled", false);
        link_post = "Devoluciones_Traspasos/guardar_solicitud_devolucion";

        resear_formulario();
        $("#titulo-modal").text('NUEVA SOLICITUD'); /** FECHA: 20-SEPTIEMBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
        ObtenerDatosElementos('nueva_sol', null);
        $("#modal_formulario_solicitud").modal({
            backdrop: 'static',
            keyboard: false
        });

        /** FECHA: 29-ABRIL-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
        resetearFormulario(); // Resetea los selects (#desarrollo, #condominios, #lote)
    });

    $("#tabla_autorizaciones").ready(function() {

        $('#tabla_autorizaciones').on('xhr.dt', function(e, settings, json, xhr) {
            tabla_devoluciones.button(1).enable(parseInt(json.por_autorizar) > 0);
        });

        $('#tabla_autorizaciones thead tr:eq(0) th').each(function(i) {
            if (i != 0 && i < $('#tabla_autorizaciones thead tr:eq(0) th').length - 1) {
                var title = $(this).text();
                $(this).html('<input type="text" style="font-size: .9em; width: 100%;" placeholder="' + title + '" title="' + title + '" class="form-control"/>');

                $('input', this).on('keyup change', function() {
                    if (tabla_devoluciones.column(i).search() !== this.value) {
                        tabla_devoluciones
                            .column(i)
                            .search(this.value)
                            .draw();
                        var total = 0;
                        var index = tabla_devoluciones.rows({
                            selected: true,
                            search: 'applied'
                        }).indexes();
                        var data = tabla_devoluciones.rows(index).data();
                        $.each(data, function(i, v) {
                            total += parseFloat(v.cantidad);
                        });
                        var to1 = formatMoney(total);
                        $("#myText_1").text(to1);
                    }
                });
            }
        });

        tabla_devoluciones = $('#tabla_autorizaciones').DataTable({
            dom: 'Brtip',
            buttons: [{
                    text: '<i class="fas fa-plus"></i> NUEVA SOLICITUD',
                    attr: {
                        class: 'btn btn-success abrir_nueva_solicitud'
                    }
                },
                {
                    text: '<i class="fas fa-print"></i> AUT. PAGO A PROVEEDORES',
                    action: function() {
                        window.open(url + "Consultar/documentos_autorizacion_devtras", "_blank")
                    },
                    attr: {
                        class: 'btn btn-danger imprimir_pago_provedores',
                    }
                }
            ],
            "language": lenguaje,
            "processing": false,
            "pageLength": 10,
            "bAutoWidth": false,
            "bLengthChange": false,
            "bInfo": false,
            "scrollX": true,
            "order": [[1, "desc"]], /** FECHA: 07-ABRIL-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
            "columns": [{
                    "className": 'details-control',
                    "orderable": false,
                    "data": null,
                    "defaultContent": '<i class="fas animacion fa-caret-right"></i>'
                },
                {
                    "width": "9%",
                    "data": function(d) {
                        return '<p style="font-size: .8em">' + d.idsolicitud + '</p>'
                    }
                },
                // {
                //     "width": "9%",
                //     "data": function(d) {
                //         return '<p style="font-size: .8em">' + d.proyecto + '</p>'
                //     }
                // },
                {
                    "width": "9%",
                    "data": function(d) {
                        return '<p style="font-size: .8em">' + d.fecelab + '</p>'
                    }
                },
                {
                    "width": "9%",
                    "data": function(d) {
                        return '<p style="font-size: .8em">' + d.nempresa + '</p>'
                    }
                },
                {
                    "width": "15%",
                    "data": function(d) {
                        return '<p style="font-size: .7em">' + d.nombre + '</p>'
                    }
                },
                {
                    "width": "15%",
                    "data": function(d) {
                        return '<p style="font-size: .8em">' + d.condominio + (d.estatusLote ? ((d.idetiqueta == 6 && d.tipo_doc == 9) || d.idetiqueta == 7 ? '<br><span class="label label-success"> '+ d.estatusLote +'</span>' : '<br><span class="label label-danger"> '+ d.estatusLote +'</span>') : '')+ '</p>'// 27/03/2025 Se agrega la etiqueta del estatus del lote en caso de que la solictud cuente con ella.
                    }
                },
                {
                    "width": "12%",
                    "data": function(d) {
                        return '<p style="font-size: .8em">$ ' + (d.esParcialidad === 'S' ? d.numeroParcialidades > 0 ?  ( formatMoney(d.cantidad)) + " / " + formatMoney(d.montoParcialidad) +' '+ d.moneda + '<br><small class="label pull-center bg-blue">TOTAL / PARCIALIDAD</small>':  formatMoney(d.cantidad)  :  formatMoney(d.cantidad) + " " + d.moneda)+'</p>';
                    }
                },

                {
                    "width": "12%",
                    "data": function(d) {
                        return '<p style="font-size: .8em">' + d.nombre_proceso + (d.idusuario != id_usuario ? ('<br><span class="label label-info"> '+ d.nombre_completo +'</span>') : '') + '</p>';
                    }
                },
                {
                    "width": "12%",
                    "data": function(d) {
                        return '<p style="font-size: .8em">' + (d.metoPago) + '</p>'
                    }
                },
                {
                    "width": "12%",
                    "data": function(d) {
                        return '<p style="font-size: .8em">' + (d.fecha_autorizacion != null ? formato_fechaymd(d.fecha_autorizacion) : '') + '</p>'
                    }
                },
                {
                    "width": "12%",
                    "data": function(d) {
                        return '<p style="font-size: .8em">' + d.etapa + '</p>'
                    }
                },
                {
                    "data": "opciones",
                    "orderable": false,
                    "className": "td-nowrap"
                }
            ],
            "createdRow": function(row, data, dataIndex){
                if (data.idproceso == "30") {
                    let planPagos = MyLib.montoTotalSolicitudParcialidad(data.cantidad, data.numeroParcialidades, data.montoParcialidad, data.programado, moment("'"+data.fecelab+"'", 'DD/MM/YYYY').format('YYYY-MM-DD') );
                    
                    if( (data.numeroParcialidades <= 0) || (data.numeroParcialidades == 1 && parseFloat(data.cantidad) <= parseFloat(data.montoParcialidad) ) || (parseFloat(data.cantidad) < parseFloat(planPagos.montoTotalPagar)) ){
                        
                        $(row).attr('title', 'Advertencia: Favor de revisar los pagos autorizados.');
                        $(row).find('.consultar_modal i').prepend('<span class="icono-advertencia-llamativo" title="Advertencia: Favor de revisar los pagos autorizados."><i class="fas fa-exclamation-triangle"></i></span>');
                        $(row).find('.avanzar, .avanzar_contable').attr('disabled', true);
                        // Tooltip para mejor UX (requiere jQuery UI o Bootstrap)
                        $(row).find('.icono-advertencia').tooltip({
                            placement: 'right'
                        });
                    }
                }
            },
            "columnDefs": [{
                "orderable": false,
            }],
            drawCallback: function() { /** INICIO FECHA: 17-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                $('[data-toggle="tooltip"]').tooltip("destroy");
                $('[data-toggle="tooltip"]').tooltip({ trigger: "hover" });
            }, /** FIN FECHA: 17-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
            "ajax": url + "Devoluciones_Traspasos/tabla_autorizaciones",
        });

        $('#tabla_autorizaciones tbody').on('click', 'td.details-control', function() {
            var tr = $(this).closest('tr');
            var row = tabla_devoluciones.row(tr);

            if (row.child.isShown()) {
                row.child.hide();
                tr.removeClass('shown');
                $(this).parent().find('.animacion').removeClass("fa-caret-down").addClass("fa-caret-right");
            } else {

                var informacion_adicional = '<table class="table text-justify">' +
                    '<tr>' +
                    '<td><strong>JUSTIFICACIÓN: </strong>' + row.data().justificacion + '</td>' +
                    '</tr>' +
                    '</table>';

                row.child(informacion_adicional).show();
                tr.addClass('shown');
                $(this).parent().find('.animacion').removeClass("fa-caret-right").addClass("fa-caret-down");
            }
        });

        $('#tabla_autorizaciones').on("click", ".editar_factura", function() {
            var tr = $(this).closest('tr');
            var row = tabla_devoluciones.row(tr);

            link_post = "Devoluciones_Traspasos/editar_solicitud_contabilidad";
            resear_formulario();
            resetearFormulario(); // Resetea opciones de #desarrollo, #condominios y #lote /** FECHA: 29-ABRIL-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/

            $.post(url + "Devoluciones_Traspasos/informacion_solicitud", {
                idsolicitud: row.data().idsolicitud
            }).done(async function(data) {
                data = JSON.parse(data);
                if (data.resultado) {
                    idsolicitud = row.data().idsolicitud;
                    try{
                        await ObtenerDatosElementos('editar_sol', data.info_solicitud[0].idproceso);
                    }catch(error){
                        console.error('Error al obtener datos:', error );
                    }
                    $("#frmnewsol #proceso").val(data.info_solicitud[0].idproceso).change();
                    $("#frmnewsol #proyecto").val( data.info_solicitud[0].proyecto ).change();

                    /**INFORMACION DE LOS LOTES**/
                    /** MEJORA INICIO FECHA: 08-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                    // Obtenemos la información de backend de la solicitud

                    const condominio = data.info_solicitud[0].condominio;
                    const idDesarrollo = data.info_solicitud[0].iddesarrollo;
                    const isDesarrolloManual = data.info_solicitud[0].isDesarrolloManual;
                    const idCondominio = data.info_solicitud[0].idcondominio;
                    const isCondominioManual = data.info_solicitud[0].isCondominioManual;
                    const idLote = data.info_solicitud[0].idlote;
                    const isLoteManual = data.info_solicitud[0].isLoteManual;
                    const referencia = data.info_solicitud[0].referencia;
                    const isReferenciaManual = data.info_solicitud[0].isReferenciaManual;

                    if (idDesarrollo == null || idCondominio == null || idLote == null) { /** INICIO FECHA: 08-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                        $("#desarrollo, #condominios, #lote").prop('required', false);
                    }else{
                        $("#desarrollo, #condominios, #lote").prop('required', true);
                    } /** FIN FECHA: 08-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/

                    // Listeners para cambios en los selects y los inputs manuales
                    $('#desarrollo').on('change', manejarCambioDesarrollo);
                    $('#condominios').on('change', manejarCambioCondominio);
                    $('#lote').on('change', manejarCambioLote);
                    $('#btnReset').on('click', resetearFormulario);

                    // Desarrollo
                    if (isDesarrolloManual == 1 || isDesarrolloManual == '1') {
                        $("#desarrollo")[0].tomselect.setValue('AFM', true);
                        $("#desarrollo").trigger('change');
                        $('#inputManualDesarrollo').val(idDesarrollo).change();

                    }else{
                        $("#desarrollo")[0].tomselect.setValue(idDesarrollo, true);
                        $("#desarrollo").trigger('change');
                    }

                    // Condominios
                    if (isCondominioManual == 1 || isCondominioManual == '1') {
                        $("#condominios")[0].tomselect.setValue('AFM', true);
                        $("#condominios").trigger('change');
                        $('#inputManualCondominio').val(idCondominio).change();
                    }else{
                        $("#condominios")[0].tomselect.setValue(idCondominio, true);
                        $("#condominios").trigger('change');
                    }

                    // Lotes
                    if (isLoteManual == 1 || isLoteManual == '1') {
                        $("#lote")[0].tomselect.setValue('AFM', true);
                        $("#lote").trigger('change');
                        
                        $('#referencia').prop('disabled', false);
                        $('#inputManualLote').val(idLote).change();
                        $('#referencia').val(referencia);
                    }else{
                        $("#lote")[0].tomselect.setValue(condominio, true);
                        $("#lote").trigger('change');
                    }

                    $('#condominioCompleto').val(condominio).prop('disabled', true).attr('title', condominio);
                    /***************************/
                    /** FIN FECHA: 08-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                    
                    $("#frmnewsol #Etapa").val(data.info_solicitud[0].etapa);
                    $("#frmnewsol #empresa").val(data.info_solicitud[0].idEmpresa).change();
                    $("#frmnewsol #titular").val(data.info_solicitud[0].requisicion);
                    $("#frmnewsol #fecha").val(data.info_solicitud[0].fecelab);
                    $("#frmnewsol #total").val(data.info_solicitud[0].cantidad);
                    $("#frmnewsol #moneda").val(data.info_solicitud[0].moneda);

                    /**DATOS DE PROVEEDOR**/
                    $("#frmnewsol #nombreproveedor").val(data.info_solicitud[0].nomprove);

                    editproyecto = data.info_solicitud[0].proyecto; /** FECHA: 08-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                    setTimeout(() => {
                        mostrarDivs($("#frmnewsol #nombreproveedor"));
                        $("#frmnewsol #proyecto").val( data.info_solicitud[0].proyecto ).change();
                        $("#frmnewsol #forma_pago").val(data.info_solicitud[0].metoPago).change();
                    }, 500);

                    $("#idproveedor_ad").val( data.info_solicitud[0].idProveedor ).change();
                    
                    $("#frmnewsol #tipocta").val(data.info_solicitud[0].tipocta);
                    $("#frmnewsol #idbanco").val(data.info_solicitud[0].idbanco);
                    $("#frmnewsol #cuenta").val(data.info_solicitud[0].cuenta);

                    $("#frmnewsol #cuenta_extr").val(data.info_solicitud[0].cuenta);
                    $("#frmnewsol #sucursal_ext").val( "" );
                    $("#frmnewsol #banco_ext").val(data.info_solicitud[0].nomban);
                    $("#frmnewsol #aba").val(data.info_solicitud[0].clvbanco);
                    $("#frmnewsol #referencia_ext").val("");
                    /**********************/

                    if(data.info_solicitud[0].idproceso == 30){
                        $('#frmnewsol #tipoResicion').val(data.info_solicitud[0].tipoParcialidad).change();
                        $("#frmnewsol #fecha_Inicio").val(data.info_solicitud[0].fecelab);
                        $("#frmnewsol #fecha_Fin").val(data.info_solicitud[0].fecha_fin);
                        $("#frmnewsol #programado").val(data.info_solicitud[0].programado);
                        $('#frmnewsol #numeroPagos').val(data.info_solicitud[0].numeroPagos);
                        $('#frmnewsol #montoParcialidad').val(data.info_solicitud[0].montoParcialidad);
                        $('#frmnewsol #montoTotal').val(data.info_solicitud[0].cantidad);
                        $("#frmnewsol #fecha").val('');

                        setTimeout(() => {
                            generarTablaPagos();
                        }, 500);
                    }
                    
                    $('#titulo-modal').html('EDITAR SOLICITUD  <b>#'+idsolicitud+'</b>'); /** FECHA: 20-SEPTIEMBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/

                    if( [ 'AD', 'CAD', 'GAD', 'AOO' ].includes( rol ) ){
                        $("#frmnewsol #cuenta_contable").val( row.data().homoclave );
                        $("#frmnewsol #cuenta_orden").val( row.data().orden_compra );                  
                    }

                    $("#frmnewsol #costo_lote").val(data.info_solicitud[0].costo_lote);
                    $("#frmnewsol #superficie").val(data.info_solicitud[0].superficie);
                    $("#frmnewsol #preciom").val(data.info_solicitud[0].preciom);
                    $("#frmnewsol #predial").val(data.info_solicitud[0].predial);
                    $("#frmnewsol #por_penalizacion").val(data.info_solicitud[0].por_penalizacion);
                    $("#frmnewsol #penalizacion").val(data.info_solicitud[0].penalizacion);
                    $("#frmnewsol #importe_aportado").val(data.info_solicitud[0].importe_aportado);
                    $("#frmnewsol #mantenimiento").val(data.info_solicitud[0].mantenimiento);
                    $("#frmnewsol #tipo_motivo").val(data.info_solicitud[0].motivo).change(); /** FECHA: 20-SEPTIEMBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                    $("#frmnewsol #detalle_motivo").val(data.info_solicitud[0].detalle_motivo); /** FECHA: 20-SEPTIEMBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                    $("#frmnewsol #solobs").val(data.info_solicitud[0].justificacion);
                    
                    $("#operacion").val(data.info_solicitud[0].nomdepto);
                    
                    $("#modal_formulario_solicitud").modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                } else {
                    alert("HA OCURRIDO UN ERROR")
                }
            });
        });

        $('#tabla_autorizaciones').on("click", ".borrar_solicitud", function() {
            if (confirm("¿Estás seguro de eliminar la solicitud " + $(this).val() + "?")) {
                $.post(url + "Devoluciones_Traspasos/borrar_solicitud", {
                    idsolicitud: $(this).val()
                }).done(function(data) {
                    data = JSON.parse(data);
                    if (data.resultado) {
                        tabla_devoluciones.ajax.reload(null, false);
                    } else {
                        alert("HA OCURRIDO UN ERROR")
                    }
                });
            }
        });

        $("#nforma_pago_new, #nforma_pago").change( function(){
            if( $( this ).val() == 'TEA' ){
                $('.row_cta_new').css('display', 'block');
                $('.row_extran_new').css('display', 'none');
            }else if( $( this ).val() == 'MAN' ){
                $('.row_extran_new').css('display', 'block');
                $('.row_cta_new').css('display', 'none');
            }else{
                $('.row_extran_new').css('display', 'none');
                $('.row_cta_new').css('display', 'none');
            }
        });

        $('#tabla_autorizaciones').on("click", ".reenviar_factura", function() {
            $.post(url + "Devoluciones_Traspasos/reenviar_factura", {
                idsolicitud: $(this).val()
            }).done(function(data) {
                data = JSON.parse(data);
                if (data.resultado) {
                    tabla_devoluciones.ajax.reload(null, false);
                    //table_proceso.ajax.reload(null,false);
                } else {
                    alert("HA OCURRIDO UN ERROR")
                }
            });
        });

        $('#tabla_autorizaciones').on("click", ".aprobada_da", function() {
            $.post(url + "Devoluciones_Traspasos/aprobada_da", {
                idsolicitud: $(this).val()
            }).done(function(data) {
                data = JSON.parse(data);
                if (data.resultado) {
                    tabla_devoluciones.ajax.reload(null, false);
                    table_proceso.ajax.reload(null, false);
                } else {
                    alert("HA OCURRIDO UN ERROR")
                }
            });
        });

        $('#tabla_autorizaciones').on("click", ".rechazada_da", function() {
            $.post(url + "Devoluciones_Traspasos/rechazada_da", {
                idsolicitud: $(this).val()
            }).done(function(data) {
                data = JSON.parse(data);
                if (data.resultado) {
                    tabla_devoluciones.ajax.reload(null, false);
                } else {
                    alert("HA OCURRIDO UN ERROR")
                }
            });
        });

        $('#tabla_autorizaciones').on("click", ".congelar_solicitud", function() {
            $.post(url + "Devoluciones_Traspasos/congelar_solicitud", {
                idsolicitud: $(this).val()
            }).done(function(data) {
                data = JSON.parse(data);
                if (data.resultado) {
                    tabla_devoluciones.ajax.reload(null, false);
                } else {
                    alert("HA OCURRIDO UN ERROR")
                }
            });
        });

        $('#tabla_autorizaciones').on("click", ".liberar_solicitud", function() {
            $.post(url + "Devoluciones_Traspasos/liberar_solicitud", {
                idsolicitud: $(this).val()
            }).done(function(data) {
                data = JSON.parse(data);
                if (data.resultado) {
                    tabla_devoluciones.ajax.reload(null, false);
                } else {
                    alert("HA OCURRIDO UN ERROR")
                }
            });
        });

        $("#form_acciones_sol").submit(function(e) {
            e.preventDefault();
            var fd = new FormData($(this)[0]);
            var data = enviar_post(fd, link_post);
            if (data.resultado) {
                tabla_devoluciones.ajax.reload();
                $("#modal_acciones_sol").modal("hide");
            }
        });
        /////////////////////AQUI COMIENZA NUEVO ///////////////////////////////
        /**
         * @uthor Efrain Martinez Muñoz | Fecha : 27/03/2025
         * Permite mostrar el modal para seleccionar el estatus del lote de la solicitud y se da funcionalidad a los checkbox,
         * se agrega la función para poder cargar la imagen y el estatus del lote dependiendo de lo qeu el usuario aya registradfo en el modal.
         * 
         * INICIO 
         * */ 
        $('#tabla_autorizaciones').on("click", ".cargar_imagen", function() {

        $('#id_sol').text($(this).val());
        $('#id_sol_input').val($(this).val());
        $('#subir_imagen').val(null);
        $("#en_construccion").prop("checked", false);
        $("#baldio").prop("checked", false);
        $('#carga_imagen').hide();
        $('#contenedorImagen').hide();
        $('#modal_subir_imagen').modal();


        });
        $(document).ready(function () {
        // Función para alternar selección
        $("#en_construccion").change(function() {
            if (this.checked) {
                $("#baldio").prop("checked", false);
                $('#carga_imagen').show();
            }
        });

        $("#baldio").change(function() {
            if (this.checked) {
                $("#en_construccion").prop("checked", false);
                $('#carga_imagen').hide();
            }
        });

            // Subir la imagen y el checkbox con AJAX
        $("#cargar_imagen").submit(function (e) {
            e.preventDefault();
            var fileInput = document.getElementById('subir_imagen');
            var formData = new FormData(this);
            var idsolicitud = document.getElementById("id_sol_input").value;

            if (fileInput.files.length > 0) {
                var file = fileInput.files[0]; // Obtiene el archivo seleccionado
                var tipoImagen = file.type; // Obtiene el tipo MIME
                if (tipoImagen != "image/jpeg" && tipoImagen != "image/png") {
                    alert("Solo se permiten imágenes JPG o PNG.");
                    fileInput.value = "";
                    $('#contenedorImagen').hide();
                    return;
                }
            }
            // Obtener el valor del checkbox seleccionado
            var estatusLote = $("input[name='estatus_lote']:checked").val();
            if (!estatusLote) {
                alert("Por favor, selecciona el estatus del lote.");
                return;
            }
            
            formData.append("id_solicitud", idsolicitud);
            formData.append("idUsuario", iniSesion);

            $.ajax({
                url: url + "Devoluciones_Traspasos/guardarImagen", 
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                dataType: "json",
                success: function (response) {
                    if (response.status === "success") {
                        $('#modal_subir_imagen').modal("toggle");
                            tabla_devoluciones.ajax.reload(function(){
                                Swal.fire({
                                    position: 'center',
                                    icon: 'success',
                                    title: '¡El estatus del lote se guardo con éxito!',
                                    showConfirmButton: false,
                                    timer: 2000
                                });

                            });                         
                        
                    } else {
                        alert("Error: " + response.message);
                    }
                },
                error: function () {
                    alert("Hubo un error en la subida.");
                }
            });
        });
    });
    /**
    * FIN
    */

        $('#tabla_autorizaciones').on("click", ".avanzar", function() {

            var tr = $(this).closest('tr');
            var row = tabla_devoluciones.row(tr);
            trglobal = tr;

            $('#span_proceso').text(row.data().nombre_proceso);
            $('#id_sol_avancom').text($(this).val());
            $('#id_sol_avancom1').val($(this).val());
            
            if( [ 'AD', 'CAD', 'GAD', 'AOO' ].includes( rol ) ){
                $("#comentario_avanzar #comentario_avanzar_cuenta_contable").val( row.data().homoclave );
                $("#comentario_avanzar #comentario_avanzar_cuenta_orden").val( row.data().orden_compra );                  
            }

            $('#modal_comentario_avanzar').modal();

        });

        $("#comentario_avanzar").submit(function(e) {
            e.preventDefault();
            jQuery(this).find(':disabled').removeAttr('disabled');
            }).validate({
                submitHandler: function(form) {
                    var data = new FormData($(form)[0]);
                    enviar_post2(function(respuesta) {
                        data = JSON.parse(respuesta);
                        if (data) {
                            tabla_devoluciones.row( trglobal ).remove().draw();
                            $('#id_sol_avancom1').val('');
                            $('#modal_comentario_avanzar').modal("toggle");
                            $('#text_comentario_ava, #text_comentario_ava').html('').val('');
                            $("#comentario_avanzar input[name='cuenta_contable'], #comentario_avanzar input[name='cuenta_orden']").val();
                        } else {
                            alert("HA OCURRIDO UN ERROR")
                        }

                    }, data, url + 'Devoluciones_Traspasos/devolucion_sigetapa');
                }
        });
        /****************************************************************************/
        $('#tabla_autorizaciones').on("click", ".avanzar_contable", function() {
            var tr = $(this).closest('tr')
            var row = tabla_devoluciones.row( tr );
            ObtenerDatosElementos('', null);
            // $("#tipo_motivo").val("");
            $("input[type='file']").val("");
            $("#regDocReestructura").find("i").css("color", "#eb3b3b")
            $("#nom_copropiedad").val("");
            var idsolll = $(this).val();
            trglobal = $(this).closest('tr');
            $("#idsol_ad").val(idsolll);
            $("#idsol_ad1").text(idsolll);
            $("#modal_cuenta_contable #idsol_area_new, #modal_cuenta_contable #areas_avanzar_new").val("")
            $("#modal_cuenta_contable").modal();
            $.post(url + "Devoluciones_Traspasos/informacion_solicitud_adm", {
                idsolicitud: idsolll
            }).done(function(data) {
                data = JSON.parse(data);

                if( data.data && ( data.info_solicitud[0].prioridad ) ) {

                    $("#solictud_rechazada").removeClass("hidden")

                    if (data.data[0].solicitud) {
                        $('#idsol_area_new').val(data.data[0].solicitud);
                    }
                    $('#areas_avanzar_new').html('<option value="">Seleccione una opción</option>');
                    $.each(data.data, function(i, v) {
                        $('#areas_avanzar_new').append(' <option  data-value="' + v.solicitud + '" value="' + v.idetapa + '">' + v.nombre + '</option>');
                    });

                }else{
                    $("#solictud_rechazada").addClass("hidden");
                }

                if (data.info_solicitud[0].idetapa == 1 && ( rol == 'AD' || 'AOO' )) {
                    $('#cuenta_contable').attr('required', false);
                } else {
                    $('#cuenta_contable').attr('required', true);
                }

                
                if(data.info_solicitud[0].motid != null){
                    $("#edita").val('si');
                }else{
                    $("#edita").val('no');
                }

                $("#cuenta_contable_form input[name='costo_lote_new']").val(data.info_solicitud[0].costo_lote);
                $("#cuenta_contable_form input[name='superficie_new']").val(data.info_solicitud[0].superficie);
                $("#cuenta_contable_form input[name='preciom_new']").val(data.info_solicitud[0].preciom);
                $("#cuenta_contable_form input[name='predial_new']").val(data.info_solicitud[0].predial);
                $("#cuenta_contable_form input[name='por_penalizacion_new']").val(data.info_solicitud[0].por_penalizacion);
                $("#cuenta_contable_form input[name='penalizacion_new']").val(data.info_solicitud[0].penalizacion);
                $("#cuenta_contable_form input[name='importe_aportado_new']").val(data.info_solicitud[0].importe_aportado);
                $("#cuenta_contable_form input[name='mantenimiento_new']").val(data.info_solicitud[0].mantenimiento); 
                
                $("#cuenta_contable_form select[name='empresa_ad']").val( data.info_solicitud[0].idEmpresa );
                $("#cuenta_contable_form input[name='etapa_conta']").val(data.info_solicitud[0].etapa);
                $("#cuenta_contable_form input[name='nombreproveedor_new']").val(data.info_solicitud[0].nomprove);
                $("#cuenta_contable_form input[name='cuenta_orden']").val(data.info_solicitud[0].orden_compra);
                $("#cuenta_contable_form input[name='idproveedor_new']").val(data.info_solicitud[0].idProveedor);
                $("#cuenta_contable_form textarea[name='solobs_ad']").val( data.info_solicitud[0].justificacion )
                $("#cuenta_contable_form input[name='cuenta_contable']").val(data.info_solicitud[0].homoclave);
                $("#cuenta_contable_form input[name='nom_copropiedad']").val(data.info_solicitud[0].copropietario);

                $("#cuenta_contable_form input[name='idbanco_xx_new']").val(data.info_solicitud[0].idbanco);
                $("#cuenta_contable_form input[name='idbanco_xx_new']").val(data.info_solicitud[0].idbanco);
                
                //DATOS BANCARIOS NACIONALES
                $("#cuenta_contable_form input[name='cuenta_new_TEA']").val(data.info_solicitud[0].cuenta);
                $("#cuenta_contable_form select[name='tipocta_new']").val( data.info_solicitud[0].tipocta );
                $("#cuenta_contable_form select[name='idbanco_TEA']").val( data.info_solicitud[0].idbanco );
                //DATOS BANCARIOS INTERNACIONALES
                $("#cuenta_contable_form input[name='cuenta_new_MAN']").val(data.info_solicitud[0].cuenta);
                $("#cuenta_contable_form input[name='banco_ext_new']").val(data.info_solicitud[0].nomban);
                $("#cuenta_contable_form input[name='aba_new']").val(data.info_solicitud[0].clvbanco);

                if (data.info_solicitud[0].metoPago == 'TEA') {
                    $('#row_cta_new').css('display', 'block');
                    $('#row_extran_new').css('display', 'none');
                } else if (data.info_solicitud[0].metoPago == 'MAN') {
                    $('#row_extran_new').css('display', 'block');
                    $('#row_cta_new').css('display', 'none');
                }

                if (data.info_solicitud[0].copropietario == '1') {
                    $('#div_copropiedad').css('display', 'block');
                    $("#nom_copropiedad").prop('required', true);
                } else {
                    $('#div_copropiedad').css('display', 'none');
                    $("#nom_copropiedad").prop('required', false);
                }
                
                setTimeout(() => {
                    $("#cuenta_contable_form select[name='nforma_pago']").val(data.info_solicitud[0].metoPago).change();
                    cambioFormaPago(data.info_solicitud[0].idproceso_proyecto, 'proyecto_adm');
                }, 500);

                /** INICIO FECHA: 29-ABRIL-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                // Obtenemos la información de referencia de lote
                const condominio = data.info_solicitud[0].condominio;
                const referencia = data.info_solicitud[0].referencia ? data.info_solicitud[0].referencia : 'SIN ASIGNAR';

                $('#condominioRefLoteAd').html(`
                    <div class="row">
                        <div class="col-lg-6 form-group">
                            <label for="Lote-AD">CONDOMINIO</label>
                            <input type="text" id="condominioAd" value="${condominio}" class="form-control" disabled>
                        </div>
                        <div class="col-lg-6 form-group">
                            <label for="Referencia-AD">REF. LOTE</label>
                            <input type="text" id="referenciaAd" name="referenciaAd" value="${referencia}" class="form-control" placeholder="Referencia lote" required>
                        </div>
                    </div><hr/>
                `);
                /** FIN FECHA: 29-ABRIL-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
            });
        });
        /*
        $('#nombreproveedor_new').change(function() {
            var idproveedor = $("#nombreproveedor_new").val();
        
            $.post(url + "Consultar/verificarDatos", {
                cuenta: '',
                idproveedor: idproveedor,
                idbanco: ''
            }, function(datos) {
                data = JSON.parse(datos);
                if (data.ok) {

                    if (data.quien.nombre != $("#nombreproveedor").val()) {
                        swal({
                            title: "¡Alerta!",
                            text: "Ya existe un usuario. Nombre: " + data.quien.nombre + ', cuenta : ' + data.quien.cuenta,
                            buttons: {
                                cancel: true,
                                confirm: true,
                            },
                        });
                        $("#idproveedor_new").val(data.quien.idproveedor);
                        $('#tipocta_new').attr('disabled', true);
                        $('#cuenta_new').attr('disabled', true);
                        $('#idbanco_new').attr('disabled', true);

                        $('#tipocta_new').val('');
                        $('#cuenta_new').val('');
                        $('#idbanco_new').val('');
                        ///////
                        // $('#cuenta_extr_new').attr('disabled', true);
                        // $('#sucursal_ext_new').attr('disabled', true);
                        // $('#banco_ext_new').attr('disabled', true);
                        // $('#aba_new').attr('disabled', true);
                        // $('#referencia_ext_new').attr('disabled', true);
                    }

                    // $("#duplicidad_cuenta").text("CUENTA DUPLICADA");
                } else {
                    // $("#idproveedor").val('');
                    $("#idproveedor_new").val('');
                    $("#duplicidad_cuenta").text("");
                    $('#tipocta_new').attr('disabled', false);
                    $('#cuenta_new').attr('disabled', false);
                    $('#idbanco_new').attr('disabled', false);
                    /////
                    $('#cuenta_extr_new').attr('disabled', false);
                    $('#sucursal_ext_new').attr('disabled', false);
                    $('#banco_ext_new').attr('disabled', false);
                    $('#aba_new').attr('disabled', false);
                    $('#referencia_ext_new').attr('disabled', false);

                }

            });

        });
        */
    });

    var table_proceso;
    var valor_input = Array( $('#tblsolact th').length ); /** FECHA: 27-FEBRERO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
    var titulo_encabezados = []; /** FECHA: 27-FEBRERO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
    var num_columnas = []; /** FECHA: 27-FEBRERO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/

    $('#fecInicial').change(function() {
        table_proceso.draw();
    });

    $('#fecFinal').change(function() {
        table_proceso.draw();
    });

            /// MAR
    $("#tblsolact").ready(function() {

        /** FECHA: 27-FEBRERO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
        // Variable que determina el departamento del usuario
        var departamentoUsuario = '<?php echo $this->session->userdata("inicio_sesion")['depto'] ?>';  // Esto debería ser dinámico según el usuario logueado

        // Crear el array de botones
        var buttons = [
            {
                text: '<i class="fas fa-search"></i>&nbsp;&nbsp;',
                attr: { class: 'btn' },

                action: function(e, dt, node, config ) {
                    $('#activas-tab').click(); // Activa la pestaña específica
                }
            },
            {
                text: '<i class="fas fa-file-excel"></i> BITÁCORA',
                attr: {
                    class: 'btn btn-success'
                },
                action: function (e, dt, node, config){
                    bitacora_excel();
                }
            }
        ];

        // Si el usuario es del departamento "CONTRALORIA", reemplazar el botón de Excel
        if (departamentoUsuario === "CONTRALORIA") {
            // Eliminar el botón actual de Excel
            buttons = buttons.filter(function(button) {
                return button.extend !== 'excelHtml5';  // Elimina el botón de Excel
            });

            // Añadir el nuevo botón para CONTRALORIA
            buttons.push({
                text: '<i class="fas fa-file-excel"></i> EXPORTAR EXCEL',
                attr: {
                    class: 'btn btn-success exportar-contraloria', /** FECHA: 07-ABRIL-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                },
            });
        } else {
            // Si no es del departamento de CONTRALORIA, añadir el botón de Excel normal
            buttons.push({
                extend: 'excelHtml5',
                text: '<i class="fas fa-file-excel"></i> EXPORTAR EXCEL',
                messageTop: "DEVOLUCIONES EN CURSO",
                attr: {
                    class: 'btn btn-success'
                },
                exportOptions: {
                    columns: [0, 1, 2, 3, 30, 29, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 28, 19, 20, 21, 22, 23, 24, 25, 26, 27], /** ACTUALIZACIÓN  FECHA: 16-ABRIL-2025 | @author Efrain Martinez Muñoz <programador.analista38@ciudadmaderas.com> | Se agrega la columna ESTATUS LOTE al documento exportado a excel**/
                    format: {
                        // En esta función, utilizamos los títulos limpios de los encabezados
                        header: function(data, columnIdx) {
                            // Usar el título limpio de `titulo_encabezados`
                            return titulo_encabezados[columnIdx] || data; // Si no hay título limpio, usamos el texto original
                        }
                    },
                    // FECHA : 16-ABRIL-2025 | @author Efrain Martinez Muñoz <programdor.analista38@ciudadmaderas>
                    // Se agrega esta propiedad que permite definir como se exportaran los datos al excel.
                    orthogonal: 'export'
                }
            });
        } /** FIN FECHA: 27-FEBRERO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
        
        /** INICIO FECHA: 27-FEBRERO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
        $('#tblsolact').on('xhr.dt', function(e, settings, json, xhr) {

        });

        $('#tblsolact thead tr:eq(0) th').each(function(i) {
            if (i < $('#tblsolact thead tr:eq(0) th').length - 1) {
                var title = $(this).text();
                titulo_encabezados.push(title);
                num_columnas.push(i);
                $(this).html( '<input type="text" class="form-control" style="font-size: .9em; width:100%;" data-value="'+title+'" placeholder="'+title+'" title="'+title+'"/>' );

                $( 'input', this ).on( 'keyup change', function () {
                    if ( table_proceso.column(i).search() !== this.value ) {
                        table_proceso
                            .column(i)
                            .search( this.value )
                            .draw();
                            valor_input[title] = this.value;
                    }
                });
            }
        }); /** FIN FECHA: 27-FEBRERO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/

        table_proceso = $('#tblsolact').DataTable({
            dom: 'Brtip',
            "buttons": buttons, /** FECHA: 27-FEBRERO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
            "language": lenguaje,
            "processing": false,
            "pageLength": 10,
            "bAutoWidth": false,
            "bLengthChange": false,
            "bInfo": false,
            "scrollX": true,
            "columns": [
                {
                    "width": "7%",
                    "data": function(d) {
                        return '<p style="font-size: .8em">' + d.idsolicitud + '</p>'
                    }
                },
                {
                    "width": "8%",
                    "data": function(d) {
                        return '<p style="font-size: .8em">' + d.abrev + '</p>'
                    }
                },
                {
                    "width": "15%",
                    "data": function(d) {
                        return '<p style="font-size: .7em">' + d.nombre + '</p>'
                    }
                },
                {   // INICIO FECHA : 16-ABRIL-2025 | @author Efrain Martinez Muñoz <programdor.analista38@ciudadmaderas.com>
                    // SE CREA UNA VALIDACION PAR QUE LA ETIQUETA SOLO SE MUESTRE EN LA VISTA Y SE OMITA LA EXPORTAR A EXCEL
                    "orderable": false,
                    "width": "12%",
                    "data": "condominio", // o null si usas todo el objeto
                    "render": function(data, type, row) {
                        if (type === 'export') {
                            // Solo el texto sin etiquetas HTML
                            return row.condominio;
                        }

                        // Vista en pantalla (HTML con etiquetas)
                        return '<p style="font-size: .7em">' + row.condominio +
                            (row.estatusLote
                                ? ((row.idetiqueta == 6 && row.tipo_doc == 9) || row.idetiqueta == 7
                                    ? '<br><span class="label label-success"> ' + row.estatusLote + '</span>'
                                    : '<br><span class="label label-danger"> ' + row.estatusLote + '</span>')
                                : '') +
                            '</p>';
                    }
                    // FIN FECHA : 16-ABRIL-2025 <programador.analista38@ciudadmaderas.com >
                },
                {
                    "width": "16%",
                    "data": function(d) {
                        return '<p style="font-size: .8em">' + d.etapa + '</p>'
                    }
                },
                {
                    "width": "8%",
                    "data": function(d) {
                        return '<p style="font-size: .8em">' + formato_fechaymd(d.fecelab) + '</p>'
                    }
                },
                {
                    "width": "12%",
                    "data": function(d) {
                        return '<p style="font-size: .7em">' + d.nproceso + '</p>'
                    }
                },
                {
                    "width": "16%",
                    "data": function(d) {
                        return '<p style="font-size: .8em">' + d.justificacion + '</p>'
                    }
                },
                {
                    "width": "15%",
                    "data": function(d) {
                        return '<p style="font-size: .7em">' + (d.nombre_completo ? d.nombre_completo : '') + '</p>'
                    }
                },
                {
                    "width": "15%",
                    "data": function(d) {
                        return '<p style="font-size: .7em">' + (d.cuenta_contable ? d.cuenta_contable : '') + '</p>'
                    }
                },
                {
                    "width": "15%",
                    "data": function(d) {
                        return '<p style="font-size: .7em">' + (d.cuenta_orden ? d.cuenta_orden : '') + '</p>'
                    }
                },
                
                {
                    "width": "15%",
                    "data": function(d) {
                        return '<p style="font-size: .7em">' + (d.costo_lote ? d.costo_lote : '') + '</p>'
                    }
                },
                {
                    "width": "15%",
                    "data": function(d) {
                        return '<p style="font-size: .7em">' + (d.superficie ? d.superficie : '') + '</p>'
                    }
                },
                {
                    "width": "15%",
                    "data": function(d) {
                        return '<p style="font-size: .7em">' + (d.preciom ? d.preciom : '') + '</p>'
                    }
                },
                {
                    "width": "15%",
                    "data": function(d) {
                        return '<p style="font-size: .7em">' + (d.predial ? d.predial : '') + '</p>'
                    }
                },
                {
                    "width": "15%",
                    "data": function(d) {
                        return '<p style="font-size: .7em">' + (d.penalizacion ? d.penalizacion : '') + '</p>'
                    }
                },
                {
                    "width": "15%",
                    "data": function(d) {
                        return '<p style="font-size: .7em">' + (d.importe_aportado ? d.importe_aportado : '') + '</p>'
                    }
                },
                {
                    "width": "15%",
                    "data": function(d) {
                        return '<p style="font-size: .7em">' + (d.mantenimiento ? d.mantenimiento : '') + '</p>'
                    }
                },
                {
                    "width": "15%",
                    "data": function(d) {
                        return '<p style="font-size: .7em">' + (d.motivo ? d.motivo : '') + '</p>'
                    }
                },
                
                {
                    "width": "15%",
                    "data": function(d) {
                        return '<p style="font-size: .7em">' + d.nautoriza + '</p>'
                    }
                },
                {
                    "width": "10%",
                    "data": function(d) {
                        return '<p style="font-size: .7em">' + formato_fechaymd(d.fecha_autorizacion) + '</p>'
                    }
                },

                {
                    "width": "10%",
                    "data": function(d) {
                        return '<p style="font-size: .7em">$ ' + formatMoney(d.cantidad) + '</p>'
                    }
                },
                {
                    "width": "8%",
                    "data": function(d) {
                        return '<p style="font-size: .7em">$ ' + formatMoney(d.pagado) + '</p>'
                    }
                },
                {
                    "width": "8%",
                    "data": function(d) {
                        return '<p style="font-size: .7em">$ ' + formatMoney(d.cantidad - d.pagado) + '</p>'
                    }
                },
                {
                    "width": "6%",
                    "data": function(d) {
                        return '<p style="font-size: .8em">' + d.diasTrans+'</p>'
                    }
                },
                {
                    "width": "6%",
                    "data": function(d) {
                        return '<p style="font-size: .8em">' + d.numCancelados+'</p>'
                    }
                },
                {
                    "width": "8%",
                    "data": function(d) {
                        return '<p style="font-size: .8em">' + d.etapa_estatus +( d.prioridad ? "<br/><small class='label pull-center bg-red'>RECHAZADA</small>" : "" )+'</p>'
                    }
                },
                {
                    "width": "9%",
                    "data": function(d) {
                        return '<p style="font-size: .8em">' + formato_fechaymd(d.fechaCreacion)+'</p>'
                    }
                },
                { /** INICIO FECHA: 20-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                    "width": "9%",
                    "data": function(d) {
                        return '<p style="font-size: .8em">' + d.detalle_motivo +'</p>'
                    }
                }, /** FIN FECHA: 20-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                {// 29 /** INICIO FECHA: 07-ABRIL-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                    "width": "9%",
                    "data": function(d) {
                        return '<p style="font-size: .8em">' + d.referencia +'</p>'
                    }
                }, /** FIN FECHA: 07-ABRIL-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                {// 30 /** INICIO FECHA: 16-ABRIL-2025 | @author Efrain Martinez Muñoz <programador.analista38@ciudadmaderas.com> **/
                    "width": "9%",
                    "data": function(d) {
                        return '<p style="font-size: .8em">' + (d.estatusLote ? d.estatusLote: 'N/A') +'</p>'
                    }
                }, /** FIN FECHA: 16-Abril-2025 | @author Efrain Martinez Muñoz <programador.analista38@ciudadmaderas.com> **/
                {// 31
                    "orderable": false,
                    "data": function(d) {
                        return '<div class="btn-group-vertical"><button type="button" class="btn btn-sm btn-primary consultar_modal notification" data-toggle="tooltip" data-placement="left" title="VER SOLICITUD" value="' + d.idsolicitud + '" data-value="DEV_BASICA"><i class="fas fa-eye"></i>' + (d.visto == 0 ? '<span class="badge">!</span>' : '') + '</button></div>'; /** INICIO FECHA: 17-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                    }
                }
            ],
            "columnDefs": [{
                    "targets": [4],
                    "orderable": false,
                    "visible": false,
                },
                {
                    "targets": [7],
                    "orderable": false,
                    "visible": false,
                },
                {
                    "targets": [8],
                    "orderable": false,
                    "visible": false,
                },
                {
                    "targets": [9],
                    "orderable": false,
                    "visible": false,
                },
                {
                    "targets": [10],
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
                    "targets": [14],
                    "orderable": false,
                    "visible": false,
                },
                {
                    "targets": [15],
                    "orderable": false,
                    "visible": false,
                },
                {
                    "targets": [16],
                    "orderable": false,
                    "visible": false,
                },
                {
                    "targets": [17],
                    "orderable": false,
                    "visible": false,
                },
                {
                    "targets": [18],
                    "orderable": false,
                    "visible": false,
                },
                {
                    "targets": [19],
                    "orderable": false,
                    "visible": false,
                },
                {
                    "targets": [21],
                    "orderable": false,
                    "visible": false,
                },
                {
                    "targets": [22],
                    "orderable": false,
                    "visible": false,
                },
                {
                    "targets": [27],
                    "orderable": false,
                    "visible": true,
                },
                { /** INICIO FECHA: 20-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                    "targets": [28],
                    "orderable": false,
                    "visible": false,
                }, /** FIN FECHA: 20-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                { /** INICIO FECHA: 07-ABRIL-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                    "targets": [29],
                    "orderable": false,
                    "visible": false,
                }, /** FIN FECHA: 07-ABRIL-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                { /** INICIO FECHA: 16-Abril-2025 | @author Efrain Martinez Muñoz <programador.analista38@ciudadmaderas.com> **/
                    "targets": [30],
                    "orderable": false,
                    "visible": false,
                } /** FIN FECHA: 16-ABRIL-2025 | @author Efrain Martinez Muñoz <programador.analista38@ciudadmaderas.com> **/
            ],
            drawCallback: function() { /** INICIO FECHA: 17-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                $('[data-toggle="tooltip"]').tooltip("destroy");
                $('[data-toggle="tooltip"]').tooltip({ trigger: "hover" });
            } /** FIN FECHA: 17-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
            /*,
                        "ajax":  url + "Devoluciones_Traspasos/tabla_facturas_encurso"*/

            
        });

        $('#tblsolact tbody').on('click', 'td.details-control', function() {
            var tr = $(this).closest('tr');
            var row = table_proceso.row(tr);

            if (row.child.isShown()) {
                row.child.hide();
                tr.removeClass('shown');
                $(this).parent().find('.animacion').removeClass("fa-caret-down").addClass("fa-caret-right");
            } else {

                var informacion_adicional = '<table class="table text-justify">' +
                    '<tr>' +
                    '<td><strong>JUSTIFICACIÓN: </strong>' + row.data().justificacion + '</td>' +
                    '</tr>' +
                    '</table>';

                row.child(informacion_adicional).show();
                tr.addClass('shown');
                $(this).parent().find('.animacion').removeClass("fa-caret-right").addClass("fa-caret-down");
            }
        });
        /////////////////nuevo 
        $('#tabla_autorizaciones tbody').on('click', '.cancelar_sol', function() {
            var idso_back = $(this).val();
            var tr = $(this).closest('tr');
            var row = tabla_devoluciones.row(tr);

            $('#txt_proceso').text(row.data().nombre_proceso);
            $('#idsol_regre').text(idso_back);
            $('#idsol_regre_new').text(idso_back);
            $("#modal_reubicar").modal();

            $.post(url + "Devoluciones_Traspasos/conusltar_areas_proceso_menor", {
                idsolicitud: $(this).val()
            }, function(datos) {
                data = JSON.parse(datos);
                if (data.data) {
                    $('#radios').append('<option value="">Seleccione una opción</option>');
                    $.each(data.data, function(i, v) {
                        $('#radios').append('<option  data-value="' + v.solicitud + '" value="' + v.orden + '">' + v.nombre + '</option>');
                    });

                }

            });
            $("#radios").html("");
            $("#text_comentario").html("");
            $("#idsol_regre").html("");
        });

        $('#tabla_autorizaciones tbody').on('click', '.avanzar_lista', function() {
            
            var tr = $(this).closest('tr');
            var row = tabla_devoluciones.row(tr);

            $('#text_proceso').text(row.data().nombre_proceso);
            $('#sol_modal_avanzar').text($(this).val());
            $("#modal_reubicar_avanzar").modal();

            $.post(url + "Devoluciones_Traspasos/conusltar_areas_proceso", {
                idsolicitud: $(this).val()
            }, function(datos) {
                data = JSON.parse(datos);
                if (data.data) {
                    $('#idsol_area').val(data.info[0].idsolicitud);
                    $('#areas_avanzar').append('<option value="">Seleccione una opción</option>');
                    $.each(data.data, function(i, v) {
                        $('#areas_avanzar').append(' <option  data-value="' + v.solicitud + '" value="' + v.orden + '">' + v.nombre + '</option>');
                    });

                }

            });
            $("#areas_avanzar").html("");
        });
    
        /** INICIO FECHA: 07-ABRIL-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
        // Añadir el nuevo botón para CONTRALORIA
        // Evento de clic para el botón "exportar-contraloria"
        $(document).on('click', '.exportar-contraloria', function() {
            // Limpiar errores anteriores
            $('.has-error').removeClass('has-error');
            $('.help-block').hide();

            var valido = true;
            var data = new FormData();

            // Obtener los valores de las nuevas fechas
            var fecInicial = $('#fecInicial').val();
            var fecFinal = $('#fecFinal').val();

            // Validar fecInicial
            if (!fecInicial) {
                $('#fecInicial').closest('.form-group').addClass('has-error');
                $('#error-fecInicial').text('Por favor, ingresa una fecha de inicio').show(); // Mensaje de error
                valido = false;
            }

            // Validar fecFinal
            if (!fecFinal) {
                $('#fecFinal').closest('.form-group').addClass('has-error');
                $('#error-fecFinal').text('Por favor, ingresa una fecha de fin').show(); // Mensaje de error
                valido = false;
            }

            // Validar que fecInicial no sea mayor o igual a fecFinal
            if (fecInicial && fecFinal) {
                var partesInicio = fecInicial.split('/');
                var partesFin = fecFinal.split('/');
                
                var fecInicialObj = new Date(partesInicio[2], partesInicio[1] - 1, partesInicio[0]); // Año, Mes (0-11), Día
                var fecFinalObj = new Date(partesFin[2], partesFin[1] - 1, partesFin[0]);

                if (fecInicialObj > fecFinalObj) {
                    $('#fecInicial, #fecFinal').closest('.form-group').addClass('has-error');
                    $('#error-fecha-rango').text('La fecha de inicio no puede ser mayor o igual a la fecha de fin').show(); // Mensaje de error
                    valido = false;
                }
            }

            // Si hay errores, detener la ejecución
            if (!valido) return;

            // Si todo está bien, agregar valores al FormData
            data.append('fechaInicial', fecInicial);
            data.append('fechaFinal', fecFinal);

            // Recopilar los valores de los inputs de la tabla
            $('#tblsolact thead tr:eq(0) input').each(function(i) {  
                var inputData = $(this).data('value');
                if (Object.keys(valor_input).indexOf(inputData) >= 0) {
                    data.append(inputData, valor_input[inputData]); // Agregar valores al formulario oculto
                }
            });

            $.ajax({
                url: '<?= site_url("Reportes/reporteSolicitudDevolucionesTraspasosEnCurso") ?>', // URL de la API
                type: 'POST',
                data: data,
                processData: false,  // No procesar los datos (usamos FormData)
                contentType: false,  // No establecer tipo de contenido
                success: function(response, textStatus, jqXHR) {
                    try {
                        // Leer la cabecera Server-Timing para ver el tiempo de ejecución
                        const serverTiming = jqXHR.getResponseHeader('Server-Timing');
                        if (serverTiming) {
                            const timingData = serverTiming.split(';');
                            const executionTime = timingData[1].split('=')[1]; // Tiempo de ejecución en segundos
                            console.log(`Tiempo de ejecución del servidor: ${executionTime} segundos`);
                        }

                        // Intentar parsear la respuesta JSON
                        var jsonResponse = (typeof response === 'string') ? JSON.parse(response) : response;

                        if (jsonResponse.status === 'success') {
                            // Ahora realizamos la descarga del archivo
                            const link = document.createElement('a');
                            link.href = 'data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64,' + jsonResponse.file_base64;
                            link.download = jsonResponse.filename;
                            document.body.appendChild(link);
                            link.click();
                            document.body.removeChild(link);
                        } else {
                            console.error('Error:', jsonResponse.message || 'Ocurrió un error desconocido.');
                        }
                    } catch (error) {
                        console.error('Error al procesar la respuesta JSON:', error);
                        console.error('Respuesta del servidor:', response);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error en la solicitud AJAX:', error);
                }
            });
        }); /** FIN FECHA: 07-ABRIL-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
    });

    $( document ).on("click", ".aceptar_pago", function(){
        var trsolicitudes = $(this).closest('tr');
        var row = tabla_devoluciones_parcialidades.row( trsolicitudes );
        myModal
    });
    
    $("#tabla_devoluciones_parcialidad").ready(function() {

        $('#tabla_devoluciones_parcialidad').on('xhr.dt', function(e, settings, json, xhr) {
        
        });

        $('#tabla_devoluciones_parcialidad thead tr:eq(0) th').each(function(i) {
            if (i < $('#tabla_devoluciones_parcialidad thead tr:eq(0) th').length - 1) {
                var title = $(this).text();
                $(this).html('<input type="text" style="font-size: .9em; width: 100%;" placeholder="' + title + '" title="' + title + '" class="form-control"/>');

                $('input', this).on('keyup change', function() {
                    if (tabla_devoluciones_parcialidades.column(i).search() !== this.value) {
                        tabla_devoluciones_parcialidades
                            .column(i)
                            .search(this.value)
                            .draw();
                        var total = 0;
                        var index = tabla_devoluciones_parcialidades.rows({
                            selected: true,
                            search: 'applied'
                        }).indexes();
                        var data = tabla_devoluciones_parcialidades.rows(index).data();
                        $.each(data, function(i, v) {
                            total += parseFloat(v.cantidad);
                        });
                        var to1 = formatMoney(total);
                        $("#myText_1").text(to1);
                    }
                });
            }
        });

        tabla_devoluciones_parcialidades = $('#tabla_devoluciones_parcialidad').DataTable({
            dom: 'Brtip',
            "buttons":[],
            "language": lenguaje,
            "processing": false,
            "pageLength": 10,
            "bAutoWidth": false,
            "bLengthChange": false,
            "bInfo": false,
            "scrollX": true,
            "drawCallback": function(settings) {
            $('#tabla_devoluciones_parcialidad tbody tr').on('dblclick', function(event) {
                if($(event.target).is('button') || $(event.target).is('i')) return;
                var data = tabla_devoluciones_parcialidades.row(this).data();
                obtenerHistorialPagos(data)

            });
        },
            "columns": [{
                    "width": "9%",
                    "data": function(d) {
                        return '<p style="font-size: .8em">' + d.idsolicitud + '</p>'
                    }
                },
                {
                    "width": "9%",
                    "data": function( d ){
                        p = "" ;

                        switch (d.programado) {
                            case '1':
                                p =  "<small class='label pull-center bg-gray'>MENSUAL</small>";
                                break;
                            case '2':
                                p =  "<small class='label pull-center bg-gray'>BIMESTRAL</small>";
                                break;
                            case '3':
                                p =  "<small class='label pull-center bg-gray'>TRIMESTRAL</small>";
                                break;
                            case '4':
                                p =  "<small class='label pull-center bg-gray'>CUATRIMESTRAL</small>";
                                break;
                            case '6':
                                p =  "<small class='label pull-center bg-gray'>SEMESTRAL</small>";
                                break;
                            case '7':
                                p =  "<small class='label pull-center bg-gray'>SEMANAL</small>";
                                break;
                            case '8':
                                p =  "<small class='label pull-center bg-gray'>QUINCENAL</small>";
                                break;
                        }
                        return p += d.intereses != null ? "<br><small class='label pull-center bg-orange'>CRÉDITO</small>" : "";
                    }
                
                },
                {
                    "width": "9%",
                    "data": function(d) {
                        return '<p style="font-size: .8em">' + d.nombre + '</p>'
                    }
                },
                {
                    "width": "9%",
                    "data": function(d) {
                        return '<p style="font-size: .8em">$' + formatMoney(Number(d.cantidad)) +' ' + d.moneda + '</p>'
                    }
                },
                {
                    "width": "9%",
                    "data": function(d) {
                        return '<p style="font-size: .7em">' + d.metoPago + '</p>'
                    }
                },
                {
                    "width": "12%",
                    "data": function(d) {
                        return '<p style="font-size: .8em">$' + formatMoney(d.cantidad_confirmada ) + " " + d.moneda + '</p>'
                    }
                },
                {
                    "width": "12%",
                    "data": function(d) {
                        return '<p style="font-size: .8em">$' + formatMoney(d.parcialidad ) + " " + d.moneda + '</p>'
                    }
                },
                {
                    "width": "9%",
                    "data": function(d) {
                        return '<p style="font-size: .8em">'+d.ptotales+' / <small class="label pull-center bg-orange">'+d.ppago+'</small></p>'
                    }
                },

                {
                    "width": "9%",
                    "data": function(d) {
                        return '<p style="font-size: .8em">' + formato_fechaymd(d.fecelab) +'</p>'
                    }
                },
                {
                    "width": "12%",
                    "data": function(d) {
                        return '<p style="font-size: .8em">' + formato_fechaymd(d.proximo_pago) + '</p>'
                    }
                },
                {
                    "width": "12%",
                    "data": function( d ){
                        var fecha_hoy = new Date();
                        if( moment( d.proximo_pago ).diff( moment( fecha_hoy ), 'days' ) < 2 && d.estatus_ultimo_pago == null ){
                            return "<small class='label pull-center bg-red'>VENCIDO POR "+ ( -1*moment( d.proximo_pago ).diff( moment( fecha_hoy ), 'days' ) )+" DIAS</small>" + ( d.prioridad ? "<br><p style='text-align-last: center'><small class='label pull-center bg-red' style='font-size: 8px'; >RECHAZADA</small></p>" : "" ) + (d.idusuario != id_usuario ? ("<br><span class='label label-info'> "+ d.nombre_capturista +"</span>") : "");
                        }else if( moment( d.proximo_pago ).diff( moment( fecha_hoy ), 'days' ) < 2 ){
                            switch( d.estatus_ultimo_pago ){
                                case "15":
                                    return "<small class='label pull-center bg-orange'>VENCIDO | POR CONFIRMAR PAGO</small>" + (d.idusuario != id_usuario ? ("<br><span class='label label-info'> "+ d.nombre_capturista +"</span>") : "");
                                    break; 
                                case "1":
                                    return "<small class='label pull-center bg-orange'>VENCIDO | POR DISPERSAR</small>" + (d.idusuario != id_usuario ? ("<br><span class='label label-info'> "+ d.nombre_capturista +"</span>") : "");
                                    break;
                                case "11":
                                case "0":
                                    return "<small class='label pull-center bg-orange'>VENCIDO | SUBIENDO PAGO</small>" + (d.idusuario != id_usuario ? ("<br><span class='label label-info'> "+ d.nombre_capturista +"</span>") : "");
                                    break; 
                                default:
                                    return "<small class='label pull-center bg-orange'>VENCIDO | PAGO DETENIDO</small>" + (d.idusuario != id_usuario ? ("<br><span class='label label-info'> "+ d.nombre_capturista +"</span>") : "");
                                    break;
                            }
                        }else{
                            return "<small class='label pull-center bg-green'>EN TIEMPO</small>" + (d.idusuario != id_usuario ? ("<br><span class='label label-info'> "+ d.nombre_capturista +"</span>") : "");
                        }                        
                    }
                },
                {
                    "width": "12%",
                    "orderable": false,
                    "data": function( d ){
                            //@uthor Efrain Martinez Muñoz || Fecha: 31/01/2024 || Se agregan validaciones para la muestra de botones, la primera validación si la etapa anterior
                            //de la solicitud es 80 muestra el boton de 'Enviar solicitud', en caso contrario muestra el boton de 'Aceptar solicitud', para la segunda validación 
                            //permite que solo se mustre el boton de 'CANCELAR SOL' a la DA 'Patricia Maya Jerez' 
                            opciones = `<div class="btn-group-vertical" role="group">
                            <button style="margin-bottom:4px;" type="button" class="notification btn btn-primary btn-sm consultar_modal" style="" value="${d.idsolicitud}" data-value="DEV" title="Ver Solicitud"><i class="far fa-eye"></i>${d.visto == 0 ? '</i><span class="badge">!</span>' : ''}</button>
                            ${(rol === 'PV' || rol === 'PVM') ?  '<button style="margin-bottom:4px;" type="button" class="btn btn-sm btn-warning editar_factura" value="'+d.idsolicitud+'" title="EDITAR SOLICITUD"><i class="fas fa-pencil-alt"></i></button>' : ''}
                            ${["1","2"].includes(d.prioridad) ? '<button style="margin-bottom:4px;" type="button" class="btn btn-sm btn-success avanzar_lista" value="'+ d.idsolicitud+ '" title="ENVIAR SOLICITUD"><i class="fas fa-share-square"></i></button>' : '' }
                            ${d.prioridad == 0 || d.prioridad == null ? '<button style="margin-bottom:4px;" type="button" class="btn btn-success btn-sm avanzar" data-value="DEV" title="Aceptar Solicitud"><i class="fas fa-check"></i></button>' : ''}
                            ${id_usuario === 67 ? '<button style="margin-bottom:4px;" type="button" class="btn btn-sm btn-danger cancelar_sol" value="'+d.idsolicitud+'" title="CANCELAR SOL"><i class="fas fa-undo-alt"></i></button>' : ''}
                        </div>`;
                        
                        return opciones;
                    }
                }
            ],
            "createdRow": function(row, data, dataIndex){
                if (data.idproceso == "30") {
                    let planPagos = MyLib.montoTotalSolicitudParcialidad(data.cantidad, data.totalPagos, data.montoParcialidad, data.programado, moment("'"+data.fecelab+"'", 'YYYY-MM-DD').format('YYYY-MM-DD') );
                    
                    let pagoParaAutorizarActual = (id_usuario == 67 && rolUsuario == 'DA')
                        ? parseFloat(data.numPagosAutorizados)
                        : parseFloat(data.pagos) + 1;

                    let ultimoPagoSolicitud = data.ultimoPagoParcialidad ? data.ultimoPagoParcialidad : data.ultimoPagoAut;
                    if( ( data.totalPagos <= 0) || 
                        ( data.totalPagos == 1 && parseFloat(data.cantidad) <= parseFloat(data.parcialidad) ) || 
                        ( parseFloat(data.cantidad) < parseFloat(planPagos.montoTotalPagar) ) ||
                        ( parseFloat(data.cantidad) > parseFloat(planPagos.montoTotalPagar) ) ||
                        ( parseFloat(data.cantidad) < parseFloat(data.totalAutorizado) ) || 
                        ( parseFloat(data.cantidad) <= 0 || parseFloat(planPagos.montoTotalPagar) <= 0 ) ||
                        ( data.numPagosAutorizados == data.totalPagos && data.totalAutorizado < data.cantidad ) ||
                        ( data.numPagosAutorizados == data.totalPagos && data.totalAutorizado > data.cantidad ) ||
                        ( parseFloat(data.parcialidad) != parseFloat(planPagos.tabla_pagos[pagoParaAutorizarActual]) ) ||
                        ( parseFloat(ultimoPagoSolicitud) != planPagos.tabla_pagos[pagoParaAutorizarActual]) ){

                        
                        // Tooltip para mejor UX (requiere jQuery UI o Bootstrap)
                        $(row).find('.icono-advertencia').tooltip({
                            placement: 'right'
                        });
                        if (data.totalPagos <= 0) {
                            $(row).addClass('fila-advertencia');
                            $(row).find('.avanzar, .avanzar_contable').attr('disabled', true);
                            $(row).attr('title','Advertencia: Favor de revisar la solicitud, ya que aparentemente no cuenta con un número de pagos definido.');
                            $(row).find('.consultar_modal i')
                                .prepend(`<span class="icono-advertencia-llamativo" 
                                                title="Advertencia: Favor de revisar la solicitud, ya que aparentemente no cuenta con un número de pagos definido.">
                                            <i class="fas fa-exclamation-triangle"></i>
                                          </span>`);
                        }else if (data.totalPagos == 1 && parseFloat(data.cantidad) <= parseFloat(data.parcialidad)) {

                            $(row).addClass('fila-advertencia');
                            $(row).find('.avanzar, .avanzar_contable').attr('disabled', true);
                            $(row).attr('title','Advertencia: Favor de revisar la solicitud. Se indica pago en parcialidades, pero se programó en una sola exhibición y el monto de la parcialidad excede el importe registrado a devolver.');
                            $(row).find('.consultar_modal i')
                                .prepend(`<span class="icono-advertencia-llamativo" 
                                                title="Advertencia: Favor de revisar la solicitud. Se indica pago en parcialidades, pero se programó en una sola exhibición y el monto de la parcialidad excede el importe registrado a devolver.">
                                            <i class="fas fa-exclamation-triangle"></i>
                                          </span>`);
                        }else if( parseFloat(data.cantidad) < parseFloat(planPagos.montoTotalPagar) ){

                            $(row).addClass('fila-advertencia');
                            $(row).find('.avanzar, .avanzar_contable').attr('disabled', true);
                            $(row).attr('title','Advertencia: Favor de revisar la solicitud. El monto calculado según las parcialidades excede el total registrado a devolver.');
                            $(row).find('.consultar_modal i')
                                .prepend(`<span class="icono-advertencia-llamativo" 
                                                title="Advertencia: Favor de revisar la solicitud. El monto calculado según las parcialidades excede el total registrado a devolver.">
                                            <i class="fas fa-exclamation-triangle"></i>
                                          </span>`);
                        }else if (parseFloat(data.cantidad) > parseFloat(planPagos.montoTotalPagar)) {

                            $(row).addClass('fila-advertencia');
                            $(row).find('.avanzar, .avanzar_contable').attr('disabled', true);
                            $(row).attr('title','Advertencia: Revisar el importe total a devolver, ya que excede el monto programado en el plan de pagos.');
                            $(row).find('.consultar_modal i')
                                .prepend(`<span class="icono-advertencia-llamativo" 
                                                title="Advertencia: Revisar el importe total a devolver, ya que excede el monto programado en el plan de pagos.">
                                            <i class="fas fa-exclamation-triangle"></i>
                                          </span>`);
                        }else if (parseFloat(data.cantidad) < parseFloat(data.totalAutorizado)) {

                            $(row).addClass('fila-advertencia');
                            $(row).find('.avanzar, .avanzar_contable').attr('disabled', true);
                            $(row).attr('title','Advertencia: Revisar los pagos autorizados hasta la fecha, ya que su suma superan el importe total de la solicitud.');
                            $(row).find('.consultar_modal i')
                                .prepend(`<span class="icono-advertencia-llamativo" 
                                                title="Advertencia: Revisar los pagos autorizados hasta la fecha, ya que su suma superan el importe total de la solicitud.">
                                            <i class="fas fa-exclamation-triangle"></i>
                                          </span>`);
                        }else if (parseFloat(data.cantidad) <= 0 || parseFloat(planPagos.montoTotalPagar) <= 0) {

                            $(row).addClass('fila-advertencia');
                            $(row).find('.avanzar, .avanzar_contable').attr('disabled', true);
                            $(row).attr('title','Advertencia: Revisar el importe total de la solicitud y/o el plan de pagos, ya que alguno de los valores parece estar en cero o en negativo.');
                            $(row).find('.consultar_modal i')
                                .prepend(`<span class="icono-advertencia-llamativo" 
                                                title="Advertencia: Revisar el importe total de la solicitud y/o el plan de pagos, ya que alguno de los valores parece estar en cero o en negativo.">
                                            <i class="fas fa-exclamation-triangle"></i>
                                          </span>`);
                        }else if (data.numPagosAutorizados == data.totalPagos && data.totalAutorizado < data.cantidad) {

                            $(row).addClass('fila-advertencia');
                            $(row).find('.avanzar, .avanzar_contable').attr('disabled', true);
                            $(row).attr('title','Advertencia: Revisar pagos autorizados; aunque el número coincide con el plan de pagos, la suma total es inferior al importe de la solicitud.');
                            $(row).find('.consultar_modal i')
                                .prepend(`<span class="icono-advertencia-llamativo" 
                                                title="Advertencia: Revisar pagos autorizados; aunque el número coincide con el plan de pagos, la suma total es inferior al importe de la solicitud.">
                                            <i class="fas fa-exclamation-triangle"></i>
                                          </span>`);
                        }else if (data.numPagosAutorizados == data.totalPagos && data.totalAutorizado > data.cantidad) {

                            $(row).addClass('fila-advertencia');
                            $(row).find('.avanzar, .avanzar_contable').attr('disabled', true);
                            $(row).attr('title','Advertencia: Revisar pagos autorizados; aunque el número coincide con el plan de pagos, la suma total autorizada excede el importe de la solicitud.');
                            $(row).find('.consultar_modal i')
                                .prepend(`<span class="icono-advertencia-llamativo" 
                                                title="Advertencia: Revisar pagos autorizados; aunque el número coincide con el plan de pagos, la suma total autorizada excede el importe de la solicitud.">
                                            <i class="fas fa-exclamation-triangle"></i>
                                          </span>`);
                        }else if( parseFloat(data.parcialidad) != parseFloat(planPagos.tabla_pagos[pagoParaAutorizarActual].includes(',') ? planPagos.tabla_pagos[pagoParaAutorizarActual].replace(/,/g, '') : parseFloat(planPagos.tabla_pagos[pagoParaAutorizarActual]) ) ){
                             
                            $(row).addClass('fila-advertencia');
                            $(row).find('.avanzar, .avanzar_contable').attr('disabled', true);
                            $(row).attr('title','Advertencia: Favor de revisar la solicitud. El monto a autorizar difiere del indicado en el plan de pagos.');
                            $(row).find('.consultar_modal i')
                                .prepend(`<span class="icono-advertencia-llamativo" 
                                                title="Advertencia: Favor de revisar la solicitud. El monto a autorizar difiere del indicado en el plan de pagos.">
                                            <i class="fas fa-exclamation-triangle"></i>
                                          </span>`);
                        }else if( parseFloat(ultimoPagoSolicitud) != parseFloat(planPagos.tabla_pagos[pagoParaAutorizarActual].includes(',') ? planPagos.tabla_pagos[pagoParaAutorizarActual].replace(/,/g, '') : parseFloat(planPagos.tabla_pagos[pagoParaAutorizarActual]) )) {

                            $(row).addClass('fila-advertencia');
                            $(row).find('.avanzar, .avanzar_contable').attr('disabled', true);
                            $(row).attr('title','Advertencia: Favor de revisar el último pago autorizado, ya que no coincide con el monto correspondiente según el plan de pagos.');
                            $(row).find('.consultar_modal i')
                                .prepend(`<span class="icono-advertencia-llamativo" 
                                                title="Advertencia: Favor de revisar el último pago autorizado, ya que no coincide con el monto correspondiente según el plan de pagos.">
                                            <i class="fas fa-exclamation-triangle"></i>
                                          </span>`);
                        }
                        
                    }
                }
            },
            "columnDefs": [{
                "orderable": false,
            }]
        });

        $("#nforma_pago_new, #nforma_pago").change( function(){
            if( $( this ).val() == 'TEA' ){
                $('.row_cta_new').css('display', 'block');
                $('.row_extran_new').css('display', 'none');
            }else if( $( this ).val() == 'MAN' ){
                $('.row_extran_new').css('display', 'block');
                $('.row_cta_new').css('display', 'none');
            }else{
                $('.row_extran_new').css('display', 'none');
                $('.row_cta_new').css('display', 'none');
            }
        });

        
        $("#form_acciones_sol").submit(function(e) {
            e.preventDefault();
            var fd = new FormData($(this)[0]);
            var data = enviar_post(fd, link_post);
            if (data.resultado) {
                tabla_devoluciones_parcialidades.ajax.reload();
                $("#modal_acciones_sol").modal("hide");
            }
        });
        /////////////////////AQUI COMIENZA NUEVO ///////////////////////////////

        $('#tabla_devoluciones_parcialidad').on("click", ".avanzar", function() {
            var tr = $(this).closest('tr');
            var row = tabla_devoluciones_parcialidades.row(tr);
            trglobal = tr;
            
            $('#span_proceso').text(row.data().nombre_proceso);
            $('#id_sol_avancom').text(row.data().idsolicitud);
            $('#id_sol_avancom1').val(row.data().idsolicitud);
            
            if( [ 'AD', 'CAD', 'GAD', 'AOO' ].includes( rol ) ){
                $("#comentario_avanzar #comentario_avanzar_cuenta_contable").val( row.data().homoclave );
                $("#comentario_avanzar #comentario_avanzar_cuenta_orden").val( row.data().orden_compra );                  
            }

            $('#modal_comentario_avanzar').modal();

        });
        //@uthor Efrain Martinez Muñoz || Fecha: 31/01/2024 || Función que permite generar el select de del botón de Cancelar solicitud que solo
        //le aparece al DA Patricia Maya y solo permite regresarla a etapa anterior. 
        $('#tabla_devoluciones_parcialidad').on("click", ".cancelar_sol", function() {
            var idso_back = $(this).val();
            var tr = $(this).closest('tr');
            var row = tabla_devoluciones_parcialidades.row(tr);

            $('#txt_proceso').text(row.data().nombre_proceso);
            $('#idsol_regre').text(idso_back);
            $('#idsol_regre_new').text(idso_back);

            $("#modal_reubicar").modal();

            $.post(url + "Devoluciones_Traspasos/conusltar_areas_proceso_menor", {
                idsolicitud: $(this).val()
            }, function(datos) {
                data = JSON.parse(datos);

                if (data.data) {
                    $('#radios').append('<option value="">Seleccione una opción</option>');
                    $.each(data.data, function(i, v) {
                        if( v.orden === '12'){
                            $('#radios').append('<option  data-value="' + v.solicitud + '" value="' + v.orden + '">' + v.nombre + '</option>');
                        }
                       
                    });

                }

            });
            $("#radios").html("");
            $("#text_comentario").html("");
            $("#idsol_regre").html("");
            
        });
        //@uthor Efrain Martinez Muñoz || Fecha : 31/01/2024 || Función que permite generar el select del boton 'Enviar solicitud'
        $('#tabla_devoluciones_parcialidad tbody').on('click', '.avanzar_lista', function() {
            
            var tr = $(this).closest('tr');
            var row = tabla_devoluciones_parcialidades.row(tr);

            $('#text_proceso').text(row.data().nombre_proceso);
            $('#sol_modal_avanzar').text($(this).val());
            $("#modal_reubicar_avanzar").modal();

            $.post(url + "Devoluciones_Traspasos/consultar_areas_proceso_mayor", {
                idsolicitud: $(this).val()
            }, function(datos) {
                data = JSON.parse(datos);
                
                if (data.data) {
                    $('#idsol_area').val(data.data[0].solicitud);
                    $('#areas_avanzar').append('<option value="">Seleccione una opción</option>');
                    $.each(data.data, function(i, v) {
                        $('#areas_avanzar').append(' <option  data-value="' + v.solicitud + '" value="' + v.orden + '">' + v.nombre + '</option>');
                    });

                }

            });
            $("#areas_avanzar").html("");
        });


        $('#tabla_devoluciones_parcialidad').on("click", ".editar_factura", function() {
            var tr = $(this).closest('tr');
            var row = tabla_devoluciones_parcialidades.row(tr);
            
            var modal = $('#modal_edit_forma_pago')
            modal.text('')
            modal.append($(`<div class="modal-dialog" style="display: block;">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #e08e0b; color: white;">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">MODIFICACIÓN DE LA FORMA DE PAGO<b></b></h4>
                    </div>
                    <form id="formEdicionPago">
                        <input type="hidden" name="idsolicitud" id="idsol_pprog">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-12 form-group">
                                    <label>FORMA DE PAGO<b style="color: red;">*</b><i class="far fa-question-circle faq" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" title="Modificación de datos bancarios" data-content="Esta información debe ser requerida al personal correspondiente, ya que en esta sección no son editables, sólo son visibles para fines informativos" data-placement="right"></i></label>
                                    <select class="form-control tipoctaselect" id="nforma_pago_new" name="nforma_pago">
                                        <option value="">Seleccione una opción</option>
                                        <option id="ECHQ" value="ECHQ" data-value="ECHQ">Cheque</option>
                                        <option id="TEA" value="TEA" data-value="TEA">Transferencia electrónica</option>
                                        <option id="MAN" value="MAN" data-value="MAN">Transferencia extranjero</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="row_cta_new" style="display:none">
                                    <div class="col-lg-4 form-group">
                                        <label>TIPO CUENTA<b style="color: red;">*</b><i class="far fa-question-circle faq" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" title="Modificación de datos bancarios" data-content="Esta información debe ser requerida al personal correspondiente, ya que en esta sección no son editables, sólo son visibles para fines informativos" data-placement="right"></i></label>
                                        <select class="form-control tipoctaselect" id="tipocta_new" name="tipocta_new" required>
                                            <option value="">Seleccione una opción</option>
                                            <option value="1">Cuenta en Banco del Bajio</option>
                                            <option value="3">Tarjeta de débito / crédito</option>
                                            <option value="40">CLABE</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-4 form-group">
                                        <label>No. DE CUENTA<b style="color: red;">*</b></label>
                                        <input type="text" name="cuenta_new_TEA" id="cuenta_new" class="form-control cuenta" required>
                                    </div>
                                    <div class="col-lg-4 form-group">
                                        <label>BANCO<b style="color: red;">*</b></label>
                                        <select name="idbanco" class="form-control" id="idbanco" required>
                                            <option value="">Seleccione una opción</option>
                                        </select>
                                    </div>
                                </div>
                                <hr/>
                                <div class="row_extran_new" style="display:none">
                                    <div class="col-lg-6 form-group">
                                        <label>No. DE CUENTA<b style="color: red;">*</b></label>
                                        <input type="text" name="cuenta_new_MAN" id="cuenta_extr_new" class="form-control" required>
                                    </div>
                                    <div class="col-lg-6 form-group">
                                        <label>PLAZA</label>
                                        <input type="text" name="sucursal_ext_new" id="sucursal_ext_new" class="form-control">
                                    </div>
                                    <div class="col-lg-4 form-group">
                                        <label>BANCO<b style="color: red;">*</b></label>
                                        <input type="text" name="banco_ext_new" id="banco_ext_new" class="form-control" required>
                                    </div>
                                    <div class="col-lg-4 form-group">
                                        <label>ABA/SWIFT<b style="color: red;">*</b></label>
                                        <input type="text" name="aba_new" id="aba_new" class="form-control" required>
                                    </div>
                                    <div class="col-lg-4 form-group">
                                        <label>REFERENCIA<b style="color: red;"></b></label>
                                        <input type="text" name="referencia_ext_new" id="referencia_ext_new" class="form-control">
                                    </div>
                                    <input type="hidden" name="idbanco_MAN" id="idbanco_xx_new" class="form-control" required>
                                </div>
                            </div>
                            <div class="row"> <!-- INICIO FECHA: 04-MARZO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                                <div class="col-lg-12 form-group">
                                    <label>OBSERVACIONES<b style="color: red;"></b></label>
                                    <textarea class="form-control" name="justificacion_solicitud" id="editar_justificacion_solicitud" style="min-width: 100%; max-width: 100%; height: 150px;"></textarea>
                                </>
                            </div> <!-- FIN FECHA: 04-MARZO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                        </div>
                        <div class="modal-footer">
                            <div class="btn-group">
                            <button type="submit" class="btn btn-success" id="">Actualizar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>`));

        $("#editar_justificacion_solicitud").val(row.data().justificacion ? row.data().justificacion : ''); /** FECHA: 04-MARZO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
        
        $.each(bancos, function(ind, val) {
            modal.find('#idbanco').append("<option value='" + val.idbanco + "'>" + val.nombre + "</option>");
        });

        modal.find('#nforma_pago_new').change(function(){

            if( $( this ).val() == 'TEA' ){
                $('.row_cta_new').css('display', 'block');
                $('.row_extran_new').css('display', 'none');
            }else if( $( this ).val() == 'MAN' ){
                $('.row_extran_new').css('display', 'block');
                $('.row_cta_new').css('display', 'none');
            }else{
                $('.row_extran_new').css('display', 'none');
                $('.row_cta_new').css('display', 'none');
            }
        })
        
        //$('body').append(modal);

        modal.find('#nforma_pago_new').val(row.data().metoPago).trigger( "change" );
        if(row.data().metoPago == 'MAN')
            modal.find('#cuenta_extr_new').val(row.data().cuenta)
        else 
            modal.find('#cuenta_new').eq(0).val(row.data().cuenta)
        if(row.data().metoPago == 'MAN')
            modal.find('#banco_ext_new').val(row.data().banco)
        else
            modal.find('#idbanco').val(row.data().idbanco)
        if(row.data().metoPago == 'MAN')
            modal.find('#aba_new').val(row.data().clvbanco)
        modal.find('#tipocta_new').val(row.data().tipocta)

        modal.find('#formEdicionPago').validate({ /** INICIO FECHA: 04-MARZO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                rules: {
                    justificacion_solicitud: {
                        required: true
                    }
                },
                messages: {
                    justificacion_solicitud: {
                        required: "Este campo no puede estar vacío."
                    }
                }, /** FIN FECHA: 04-MARZO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                submitHandler: function(form) {
                    var data = new FormData($(form)[0]);
                    data.append('idsolicitud', row.data().idsolicitud);
                    data.append('idProveedor', row.data().idProveedor);

                    $.ajax({
                        url: url + 'Devoluciones_Traspasos/editarFormaPago',
                        data: data,
                        cache: false,
                        processData: false,
                        contentType: false,
                        method: 'POST',
                        type: 'POST', // For jQuery < 1.9
                        success: function(data) { 
                            data = JSON.parse(data);
                            if (data.data == 'OK') tabla_devoluciones_parcialidades.ajax.reload();
                            else alert( 'Error al editar el método de pago' );
                            modal.modal('toggle');
                        },
                        error: function(data) {
                            alert("ERROR EN EL SISTEMA");
                        }
                    });
                }
        })
    
        modal.modal('toggle');
        })

        
    });

    var table_pagos_sin_factura;
    var id;
    var link_complentos;

    $.fn.dataTableExt.afnFiltering.push(function(oSettings, aData, iDataIndex) {

        if (oSettings.nTable.getAttribute('id') == "tblsolact") {

            var iFini = '';
            $('.from').each(function(i, v) {

                if ($(this).val() != '') {
                    iFini = $(this).val();
                    return false;
                }
            });

            var iFfin = '';

            $('.to').each(function(i, v) {
                if ($(this).val() != '') {
                    iFfin = $(this).val();
                    return false;
                }
            });

            var iStartDateCol = 27;
            var iEndDateCol = 27;

            var meses = ['', 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];

            var mes1 = typeof aData[iStartDateCol] === 'undefined' ? '' : (meses.indexOf(aData[iStartDateCol].split('/')[1]).toString()).padStart(2, "0");
            var mes2 = typeof aData[iEndDateCol] === 'undefined' ? '' : (meses.indexOf(aData[iEndDateCol].split('/')[1]).toString()).padStart(2, "0");

            iFini = iFini.substring(6, 10) + iFini.substring(3, 5) + iFini.substring(0, 2);
            iFfin = iFfin.substring(6, 10) + iFfin.substring(3, 5) + iFfin.substring(0, 2);

            var datofini = aData[iStartDateCol].substring(7, 11) + mes1 + aData[iStartDateCol].substring(0, 2);
            var datoffin = aData[iEndDateCol].substring(7, 11) + mes2 + aData[iEndDateCol].substring(0, 2);

            if (iFini === "" && iFfin === "") {
                return true;
            } else if (iFini <= datofini && iFfin === "") {
                return true;
            } else if (iFfin >= datoffin && iFini === "") {
                return true;
            } else if (iFini <= datofini && iFfin >= datoffin) {
                return true;
            }

            return false;
        } else {
            return true;
        }

    });

    $(window).resize(function() {
        tabla_devoluciones.columns.adjust();
        table_proceso.columns.adjust();
    });

    /** INICIO FECHA: 08-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
    $('.sidebar-toggle').click(function() {
        setTimeout(function() {
            // 1. Ajustar columnas
            tabla_devoluciones.columns.adjust();
            table_proceso.columns.adjust();
            // 2. Forzar actualización de encabezados
            var headerCellsTableDevoluciones = $('#tabla_autorizaciones thead th');
            headerCellsTableDevoluciones.each(function() {
                var newWidth = $(this).width(); // Obtener ancho actualizado
                $(this).css('width', newWidth + 'px'); // Aplicarlo
            });
            var headerCellsTableProceso = $('#tblsolact thead th');
            headerCellsTableProceso.each(function() {
                var newWidth = $(this).width(); // Obtener ancho actualizado
                $(this).css('width', newWidth + 'px'); // Aplicarlo
            });
            // 3. Redibujar (sin resetear paginación)
            tabla_devoluciones.draw(false);
            table_proceso.draw(false);
        }, 300); // Esperar a que termine la animación del sidebar
    }); /** FIN FECHA: 08-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/

    $("#moneda").change(function() {
        if ($(this).val() == "MXN") {
            $("#row_tipo").hide();
        } else {
            $("#row_tipo").show();
        }
    });

    $("#frmnewsol #forma_pago").change(function() {
        $forma_pago = $( this ).val();
        if ($forma_pago == '') {
            $('#rowTablaProveedores').addClass('hidden');
        }
        if( opcion != 'TRASPASO' && opcion != 'INFORMATIVA' && opcion != 'TRASPASO OOAM' ) {
            $( "#frmnewsol #dev_TEA, #frmnewsol #dev_MAN" ).addClass("hidden");
            
            if( $forma_pago == "TEA" ){
                $( "#frmnewsol #dev_TEA" ).removeClass("hidden");
                $( "#frmnewsol #dev_MAN" ).addClass("hidden");
            }
            
            if($forma_pago == "MAN" ){
                $( "#frmnewsol #dev_TEA" ).addClass("hidden");
                $( "#frmnewsol #dev_MAN" ).removeClass("hidden");
            }

        }

        if($forma_pago != 'TEA' && $forma_pago != 'MAN') {
            $('#rowTablaProveedores').addClass('hidden');
        }
        else{
            if ($("#nombreproveedor").val().trim() != '' && $forma_pago != '') {
                $('#rowTablaProveedores').removeClass('hidden');
            }
        }
    });

    function evaluar_datos() {
        if ($("#forma_pago").val() == "TEA") {
            $('#cuenta_extr').val('');
            $('#sucursal_ext').val('');
            $('#banco_ext').val('');
            $('#aba').val('');
            $('#idproveedor_ad').val('');
            $('#referencia_ext').val('');
            $("#row_cta").show();
            $("#row_extran").css('display', 'none');
            $("#row_cta").removeClass("hidden");
            $("select#tipocta").prop("required", true);
            $("#cuenta").attr("required", true);
            $("select#idbanco").prop("required", true);

        } else if ($("#forma_pago").val() == "MAN") {
            $('#cuenta').val('');
            // $('#idproveedor_ad').val('');
            $('#idbanco').val('');
            $('#tipocta').val('');
            // $('#idproveedor').val('');
            $("#row_extran").show();
            $("#row_extran").removeClass("hidden");
            $("#row_cta").addClass("hidden");


        } else {
            $('#cuenta').val('');
            $('#idbanco').val('');
            $('#tipocta').val('');
            $('#cuenta_extr').val('');
            $('#sucursal_ext').val('');
            $('#banco_ext').val('');
            $('#idproveedor').val('');
            $('#aba').val('');
            $('#idproveedor_ad').val('');
            $('#referencia_ext').val('');
            $("select#tipocta").prop("required", false);
            $("#cuenta").attr("required", false);
            $("select#idbanco").prop("required", false);
            $("#row_cta").addClass("hidden");
            $("#row_extran").addClass("hidden");
        }


    }

    function dateToDMY(date) {
        var d = date.getDate();
        var m = date.getMonth() + 1; //Month from 0 to 11
        var y = date.getFullYear();
        return '' + (d <= 9 ? '0' + d : d) + '/' + (m <= 9 ? '0' + m : m) + '/' + y;
    }

    //EN CASO DE ENCONTRAR AL COLABORAR SE CAMBI A UN INPUT PARA ESCRIBIR SU NOMBRE
    $(document).on("click", "#noseencuentra", function() {
        if ($(this).prop("checked")) {
            $("#idproveedor").select2().next().hide();
            $("#idproveedor").prop("required", false);
            $("#proveedor").show().prop("required", true).val($("#nombreproveedor_edit").val());
            $("#row_cta .form-control").val("").prop("disabled", false);
        } else {
            $("#idproveedor").select2().next().show();
            $("#idproveedor").prop("required", true).val("");
            $("#proveedor").hide().prop("required", false);
        }

        $("#idproveedor").val('').trigger('change');
    });
    //@uhtor Efrain Martinez Muñoz || Fecha: 31/01/2024 || Se agrega el reload de la tabla tabla_devoluciones_parcialidades parque esta se recargue al regresar o avanzar la solicitud.
    $("#comentario").submit(function(e) {
        e.preventDefault();
        jQuery(this).find(':disabled').removeAttr('disabled');
        }).validate({
            submitHandler: function(form) {
                var data = new FormData($(form)[0]);
                var sol = $("#radios").find('option:selected').attr("data-value");
                data.append("solicitud_fom", sol);
                enviar_post2(function(respuesta) {
                    if (data != false) {
                        $("#modal_reubicar").modal("toggle");
                        var tr = $(this).closest('tr').remove();

                        tabla_devoluciones.ajax.reload(null, false);
                        tabla_devoluciones_parcialidades.ajax.reload(null, false);
                    }

                }, data, url + 'Devoluciones_Traspasos/regresar_sol_area');
            }
    });

    $("#cuenta_contable_form").submit(function(e) {
        e.preventDefault();
        jQuery(this).find(':disabled').removeAttr('disabled');
        $('#condominioAd').prop("disabled", true); /** FECHA: 29-ABRIL-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
        }).validate({
            submitHandler: function(form) {
                var data = new FormData($(form)[0]);
                enviar_post2(function(respuesta) {
                    if( data ) {
                        $("#modal_cuenta_contable").modal("toggle");
                        tabla_devoluciones.ajax.reload();
                    }

                }, data, url + 'Devoluciones_Traspasos/avanzar_sol_ad');
            }
    });
    //@uhtor Efrain Martinez Muñoz || Fecha: 31/01/2024 || Se agrega el reload de la tabla tabla_devoluciones_parcialidades parque esta se recargue al regresar o avanzar la solicitud.
    $("#areas_avanzar_form").submit(function(e) {
        e.preventDefault();
        jQuery(this).find(':disabled').removeAttr('disabled');
        }).validate({
            submitHandler: function(form) {
                var data = new FormData($(form)[0]);
                enviar_post2(function(respuesta) {
                    if (data != false) {
                        $("#modal_reubicar_avanzar").modal("toggle");
                        var tr = $(this).closest('tr').remove();
                        tabla_devoluciones.ajax.reload(null, false);
                        tabla_devoluciones_parcialidades.ajax.reload(null, false);
                    }

                }, data, url + 'Devoluciones_Traspasos/avanzar_area_r');
            }
    });

    function myFunction(dx, idsol) {
        $("#iddocumento").val(dx);
        $("#idsol_regre_new").text(idsol);
        $("#consultar_modal").modal("toggle");
        $("#modal_reubicar").modal();
        $.post(url + "Devoluciones_Traspasos/conusltar_areas_proceso", {
            idsolicitud: idsol
        }, function(datos) {
            data = JSON.parse(datos);

            if (data.data) {
                $('#radios').append('<option value="">Seleccione una opción</option>');
                $.each(data.data, function(i, v) {
                    $('#radios').append('<option  data-value="' + v.solicitud + '" value="' + v.orden + '">' + v.nombre + '</option>');
                });

            }

        });
        $("#radios").html("");
        $("#text_comentario").html("");
        $("#iddocumento").html("");
        $("#idsol_regre").html("");


    }

    function myFunctionBorrar(dx, idsol) {
        $("#iddocumento").val(dx);
        $("#eliminar_doc_modal").val(dx);
        $("#idsol_regre_new").text(idsol);
        $("#consultar_modal").modal("toggle");
        $("#modal_confirm").modal();

    }

    $('#eliminar_doc_modal').on('click', function(){
        var dx =$(this).val();
        $.post(url + "Devoluciones_Traspasos/borrar_documento", {
                iddocumento: dx
        }, function(datos) {
                data = JSON.parse(datos);
                if (data.resultado) {
                    documentos();
                    descarga_dosc();
                    $('#eliminar_doc_modal').val('');
                    $("#modal_confirm").modal("toggle");
                    var tr = $(this).closest('tr').remove();
                    tabla_devoluciones.ajax.reload(null, false);

                }

            });
    });

    /** INICIO FECHA: 29-ABRIL-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
    let residenciales = [];
    let condominios = [];
    let lotes = [];
    var desarrolloArray = [];
    var condominiosArray = [];
    var lotesArray = [];

    $(document).ready(function () {
        cargarDatos();

        // Listeners
        $('#desarrollo').on('change', manejarCambioDesarrollo);
        $('#condominios').on('change', manejarCambioCondominio);
        $('#lote').on('change', manejarCambioLote);

        $('#inputManualDesarrollo, #inputManualCondominio, #inputManualLote').on('input', actualizarResultadoFinal);
        $('#btnReset').off('click').on('click', resetearFormulario);

    });

    function cargarDatos() {
        $.when(
            $.getJSON("https://prueba.gphsis.com/CXP/index.php/Api/obtenerDatosCrm?tipoSp=RESIDENCIALES", function (res) {
                residenciales = res;
            }),
            $.getJSON("https://prueba.gphsis.com/CXP/index.php/Api/obtenerDatosCrm?tipoSp=CONDOMINIOS", function (res) {
                condominios = res;
            }),
            $.getJSON("https://prueba.gphsis.com/CXP/index.php/Api/obtenerDatosCrm?tipoSp=LOTES", function (res) {
                lotes = res;
            })
        ).done(function () {
            desarrolloArray.push({value: '', label: '--- Seleccione una opción ---'});
            desarrolloArray.push({value: 'AFM', label: 'ASIGNAR MANUALMENTE'});
            $.each( residenciales, function( i, v){
                desarrolloArray.push({value: v.idResidencial, label: v.nombre, opcionesData: {abreviatura: v.abreviatura}});
            });
            inputTomSelect('desarrollo', desarrolloArray, {valor:'value', texto: 'label', opcDataSelect: 'opcionesData'});

            $('#desarrollo')[0].tomselect.enable();
        });
    }

    function manejarCambioDesarrollo() {
        const idResidencial = $(this)[0].tomselect.getValue(); /** FECHA: 08-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/        

        // Limpiar los selects de Condominios y Lotes
        condominiosArray = [];
        condominiosArray.push({value: '', label: '--- Seleccione una opción ---'});
        inputTomSelect('condominios', condominiosArray, {valor:'value', texto: 'label'});
        $('#condominios')[0].tomselect.disable();

        lotesArray = [];
        lotesArray.push({value: '', label: '--- Seleccione una opción ---'});
        inputTomSelect('lote', lotesArray, {valor:'value', texto: 'label'});
        $('#lote')[0].tomselect.disable();

        // Ocultar los inputs manuales y resetear valores
        $('#inputManualCondominio').hide().val('');
        $('#inputManualLote').hide().val('');
        $('#btnReset').hide();

        if (idResidencial === '') { // Si se selecciona "AFM" o no se selecciona nada, se muestran los inputs manuales /** FECHA: 08-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
            $('#condominios').hide();
            $('#lote').hide();
            $('#condominioCompleto').val('').prop('disabled', false);
            $('#referencia').val('').prop('disabled', false);

            actualizarResultadoFinal();
            return;
        } else if (idResidencial === 'AFM') { /** FECHA: 08-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
            // Modo manual
            $('#inputManualDesarrollo').show().attr('placeholder', 'Asignar nombre de desarrollo manualmente');
            $('#inputManualCondominio').show().attr('placeholder', 'Asignar nombre de condominio manualmente');
            $('#inputManualLote').show().attr('placeholder', 'Asignar número de lote manualmente');

            $('#condominios').hide();
            $('#lote').hide();

            $('#condominioCompleto').val('').prop('disabled', false);
            $('#btnReset').show();
            $('#referencia').val('').prop('disabled', false);

            actualizarResultadoFinal();
            return;
        } else {
            // Requerir campo #desarrollo
            $("#desarrollo").prop('required', true); /** FECHA: 08-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/

            // Si se eligió algo distinto de "AFM", asegurarse de que inputs manuales estén vacíos
            $('#inputManualDesarrollo').val('').hide();
            $('#inputManualCondominio').val('').hide();
            $('#inputManualLote').val('').hide();
            $('#condominios').show();
            $('#lote').show();

            $('#btnReset').hide();
            $('#referencia').val('').prop('disabled', true);
            $('#condominioCompleto').val('').prop('disabled', true);

            $('#wrapperCondominioLote').show();

            const filtrados = condominios.filter(c => c.idResidencial == idResidencial);
            condominiosArray.push({value: 'AFM', label: 'ASIGNAR MANUALMENTE'});
            $.each( filtrados, function( i, v){
                condominiosArray.push({value: v.idCondominio, label: v.nombre, opcionesData: {abreviatura: v.abreviatura, idres: v.idResidencial}});
            });
            inputTomSelect('condominios', condominiosArray, {valor:'value', texto: 'label', opcDataSelect: 'opcionesData'});
            $('#condominios')[0].tomselect.enable();

            actualizarResultadoFinal();
        }
    }

    function manejarCambioCondominio() {
        const idCondominio = $(this)[0].tomselect.getValue(); /** FECHA: 08-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
        // Siempre limpiar y deshabilitar el select de lotes
        lotesArray = [];
        lotesArray.push({value: '', label: '--- Seleccione una opción ---'});
        lotesArray.push({value: 'AFM', label: 'ASIGNAR MANUALMENTE'});
        $('#lote')[0].tomselect.disable();

        if (!idCondominio || idCondominio === "") { /** FECHA: 08-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
            inputTomSelect('lote', lotesArray, {valor:'value', texto: 'label'});
            $('#referencia').val('').prop('disabled', false);
            $('#condominioCompleto').val('').prop('disabled', true);
            // Requerir campo #condominios
            $("#condominios").prop('required', true); /** FECHA: 08-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
        }else if (idCondominio === 'AFM') { // Si se selecciona "AFM", mostrar los inputs manuales /** FECHA: 08-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
            $('#inputManualCondominio').val('').show().attr('placeholder', 'Asignar número de condominio manualmente');
            $('#inputManualLote').val('').show().attr('placeholder', 'Asignar número de lote manualmente');

            $('#condominios').hide();
            $('#lote').hide();

            $('#btnReset').show();
            $('#referencia').val('').prop('disabled', false);
            $('#condominioCompleto').val('').prop('disabled', false);

            actualizarResultadoFinal();
            return;
        } else {
            // Requerir campo #condominios
            $("#condominios").prop('required', true); /** FECHA: 08-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/

            // Si se eligió algo válido, limpiar inputs manuales
            $('#inputManualCondominio').val('').hide();
            $('#inputManualLote').val('').hide();
    
            $('#condominios').show();
            $('#lote').show();
            $('#btnReset').hide();
    
            const filtrados = lotes.filter(l => l.idCondominio == idCondominio);
            $.each( filtrados, function( i, v){
                lotesArray.push({value: v.nombreLote, label: v.nombreLote, opcionesData: {id: v.idLote, referencia: v.referencia}});
            });
            inputTomSelect('lote', lotesArray, {valor:'value', texto: 'label', opcDataSelect: 'opcionesData'});
            $('#lote')[0].tomselect.enable();
    
            actualizarResultadoFinal();
            return;
        }

    }

    function manejarCambioLote() {
        const valor = $(this)[0].tomselect.getValue(); /** FECHA: 08-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
        const seleccionado = $(this)[0].tomselect.activeOption;
        const idLote = seleccionado ? seleccionado.getAttribute('data-id') : '';
        const referencia = seleccionado ? seleccionado.getAttribute('data-referencia') : '';

        if (!valor || valor === "") {
            $('#referencia').val('').prop('disabled', true);
            $('#condominioCompleto').val('').prop('disabled', true);
            $('#idLoteSeleccionado').val('');

            // Requerir campo #lote
            $("#lote").prop('required', true); /** FECHA: 08-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
        } else if (valor === 'AFM') {
            $('#inputManualLote').val('').show().attr('placeholder', 'Asignar número de lote manualmente');
            $('#lote').hide();
            $('#referencia').val('').prop('disabled', false);
            $('#condominioCompleto').val('').prop('disabled', false);
            $('#btnReset').show();
            $('#idLoteSeleccionado').val('');
        } else {
            // Requerir campo #lote
            $("#lote").prop('required', true); /** FECHA: 08-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/

            $('#inputManualLote').val('').hide(); // limpieza del input manual
            $('#lote').show();
            $('#idLoteSeleccionado').val(idLote).prop('disabled', true);
            referencia ? $('#referencia').val(referencia).prop('disabled', true) : $('#referencia').val('').prop('disabled', false);
            $('#btnReset').hide();
        }

        actualizarResultadoFinal();
        return;
    }

    function actualizarResultadoFinal() {
        let desarrollo = '', condominio = '', lote = '';
        let desarrolloVal = $("#desarrollo")[0].tomselect.getValue(); /** FECHA: 08-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
        let condominioVal = $("#condominios")[0].tomselect.getValue(); /** FECHA: 08-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
        let loteVal = $("#lote")[0].tomselect.getValue(); /** FECHA: 08-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/

        // Si el campo de desarrollo es manual
        if ($('#inputManualDesarrollo').is(':visible')) {
            desarrollo = $('#inputManualDesarrollo').val().trim();
            desarrolloVal = 'AFM';
        } else {
            const tomSelectDesarrollo = $("#desarrollo")[0].tomselect;
            const selectedValue = tomSelectDesarrollo.getValue(); /** FECHA: 08-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
            desarrollo = tomSelectDesarrollo.options?.[selectedValue]?.opcionesData?.abreviatura || ''; /** FECHA: 08-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
        }

        // Si el campo de condominio es manual
        if ($('#inputManualCondominio').is(':visible')) {
            condominio = $('#inputManualCondominio').val().trim();
            condominioVal = 'AFM';
        } else {
            condominio = $("#condominios")[0].tomselect.activeOption ? $("#condominios")[0].tomselect.activeOption.getAttribute('data-abreviatura') : '';
            condominio = condominio ?? ''
        }

        // Si el campo de lote es manual
        if ($('#inputManualLote').is(':visible')) {
            lote = $('#inputManualLote').val().trim();
            loteVal = 'AFM';
        } else {
            const loteSeleccionado = $('#lote option:selected');
            lote = loteSeleccionado.val() && loteSeleccionado.val() !== 'AFM' ? loteSeleccionado.val() : '';
        }

        let resultadoFinal = '';

        // Si el lote es válido y distinto de "" y "AFM", usarlo como resultado final
        if (loteVal && loteVal !== 'AFM') {
            resultadoFinal = lote;
        } else {
            resultadoFinal = [desarrollo, condominio, lote].filter(Boolean).join('-');
        }

        $('#condominioCompleto').val(resultadoFinal).prop('disabled', true).attr('title', resultadoFinal);
        
    }

    function resetearFormulario() {
        $('#condominios, #lote').show();
        $('#referencia').val('');
        $('#referencia').prop('disabled', true);
        $('#inputManualDesarrollo, #inputManualCondominio, #inputManualLote').hide().val('');
        $('#condominioCompleto').text('').val('').html('').prop('disabled', false);
        $('#wrapperCondominioLote').show();
        $('#btnReset').hide();

        $('#desarrollo')[0].tomselect.setValue("");
        $("#desarrollo").trigger('change');
        $('#desarrollo')[0].tomselect.enable();

        $('#condominios')[0].tomselect.setValue("");
        $("#condominios").trigger('change');
        $('#condominios')[0].tomselect.disable();

        $('#lote')[0].tomselect.setValue("");
        $("#lote").trigger('change');
        $('#lote')[0].tomselect.disable();

        // Limpiamos mensajes de error anteriores
        $('#errorDesarrollo').hide().text('');
        $('#errorCondominio').hide().text('');
        $('#errorLote').hide().text('');

        actualizarResultadoFinal();
        return;
    }
    /** FIN FECHA: 29-ABRIL-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/

    function cambioFormaPago(idValor, tipo) {
        if (tipo === 'proceso') {
            if (opcQuitar[idValor]) {
                opcQuitar[idValor].forEach(opc=>{
                    let opcFormaPago = document.getElementById(opc);
                    if(opcFormaPago){
                        opcFormaPago.style.display = "none";
                    }
                });
            }
        }else if(tipo === 'proyecto'){
            if (quitarOpcProcesoProyecto[idValor]) {
                quitarOpcProcesoProyecto[idValor].forEach(opc=>{
                    let opcFormaPago = document.getElementById(opc);
                    if(opcFormaPago){
                        opcFormaPago.style.display = "none";
                    }
                });
            }
        }else if (tipo === 'proyecto_adm') {            
            if (quitarOpcProcesoProyecto[idValor]) {
                quitarOpcProcesoProyecto[idValor].forEach(opc=>{
                    let opcFormaPago = document.querySelector("#cuenta_contable_form #"+opc);
                    
                    if(opcFormaPago){
                        opcFormaPago.style.display = "none";
                    }
                });
            }
        }
    }

    $('#IDEOV, #DS, #CD, #RPNC, #CCDO, #RF, #CPPT, #A2170, #AERP, #A1150, #CTB, #A4200, #RPECND, #A4300, #CL, #CNSD').click(function () {
        $("#PDF_"+$(this).attr('id')).click();
    });

    $('#PDF_IDEOV, #PDF_DS, #PDF_CD, #PDF_RPNC, #PDF_CCDO, #PDF_RF, #PDF_CPPT, #PDF_A2170, #PDF_AERP, #PDF_A1150, #PDF_CTB, #PDF_A4200, #PDF_RPECND, #PDF_A4300, #PDF_CL, #PDF_CNSD').on("change", function () {
        if ($(this).length == 1) {
            $("#"+$(this).attr('id').replace("PDF_", "")).find("i").css("color", "#16d057");
        }else{
            $("#"+$(this).attr('id').replace("PDF_", "")).find("i").css("color", "#eb3b3b");
        }
    });

    $("#regDocReestructura").submit(function (e) {
        e.preventDefault();
    }).validate({
        submitHandler: function(form) {
            var data = new FormData($(form)[0]);
            data.append("idsolicitud", idsolicitud);
            data.append("operacion", $("#proceso").find('option:selected').attr("data-value"));

            $.ajax({
                url: url + "Devoluciones_Traspasos/documentacion_reembolsos",
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                method: 'POST',
                type: 'POST', // For jQuery < 1.9
                success: function(data) {
                    if (data.resultado) {
                        alert( data.msj );
                    }
                    alert( data.msj );
                },
                error: function(data) {
                    alert("ERROR EN EL SISTEMA");
                }
            });
        }
    });

    $('#tipoResicion').change(function(){
        $('#rowFechasParcialidades').text('')
        if(!$(this).val()) return;
        var montoTotal = $(`<div class="col-lg-6 form-group">
                <label for="montoTotal">MONTO A DEVOLVER</label>
                <input type="number" class="form-control" id="montoTotal" name="montoTotal" placeholder="Total" min="0.01" value="" required>
            </div>`);

        parcialidad = $(`<div class="col-lg-6 form-group">
                <label for="numeroPagos"># PARCIALIDADES</label>
                <input type="number" class="form-control" id="numeroPagos" name="numeroPagos" placeholder="PARCIALIDADES" min="1" value="" required ${['T', 'MA'].includes($(this).val()) ? '': 'disabled'}>  
            </div>`);
        
        montoParcialidad = $(`<div class="col-lg-6 form-group">
                <label for="montoParcialidad">MONTO POR PARCIALIDAD</label>
                <input type="number" class="form-control" id="montoParcialidad" name="montoParcialidad" placeholder="CANTIDAD" min="1" value="" pattern="^\d*(\.\d{0,2})?$" required ${$(this).val() == 'T' ? 'disabled': ''}>  
            </div>`);

        periodoPagos = $(`<div class="col-lg-6 form-group">
            <label for="programado">PERIODO DE PAGOS<span class="text-danger">*</span></label>
            <select class="form-control" id="programado" name="programado" required>
                <option value="">Seleccione un opción</option>
                <option value="7">SEMANAL</option>
                <option value="8">QUINCENAL</option>
                <option value="1">MENSUALMENTE</option>
                <option value="2">BIMESTRAL</option>
                <option value="3">TRIMESTRAL</option>
                <option value="4">CUATRIMESTRAL</option>
                <option value="6">SEMESTRAL</option>
            </select>
            </div>`);

        fechaInicio = $(`<div class="col-lg-6 form-group">
                                    <label for="fecha">FECHA INICIO<span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" min='2019-04-01' id="fecha_Inicio" name="fecha_Inicio" placeholder="Fecha" value="" required>
                                </div>`);

        fechaFinal = $(`<div class="col-lg-6 form-group">
                                    <label for="fecha">FECHA FIN</label>
                                    <input type="date" class="form-control" id="fecha_Fin" name="fecha_Final" disabled/>
                                </div>`);

        montoTotal.children('input').eq(0).change(function (){
            inputChange();
            generarTablaPagos();
        })
        parcialidad.children('input').eq(0).change(function (){
            inputChange();
            generarTablaPagos();
        })
        montoParcialidad.children('input').eq(0).change(function (){
            inputChange();
            if(parseFloat($('#montoTotal').val()) > 0 && parseFloat($(this).val()) > 0 && parseFloat($('#montoTotal').val()) < parseFloat($(this).val())){
                Swal.fire({
                    position: 'top',
                    icon: 'error',
                    title: 'LA PARCIALIDAD ES MAYOR QUE EL MONTO A DEVOLVER.',
                    showConfirmButton: false,
                    timer: 4000
                });
                $(this).val(0)
                $('#numeroPagos').val(0)
                return; 
            }
            $(this).val(parseFloat($(this).val()).toFixed(2));
            generarTablaPagos();
        });
        periodoPagos.children('select').eq(0).change(function (){
            generarTablaPagos();
        });
        fechaInicio.children('input').eq(0).change(function (){
            generarTablaPagos();
        });
        fechaFinal.children('input').eq(0).change(function (){
            generarTablaPagos();
        });

        $('#rowFechasParcialidades').append(fechaInicio);
        $('#rowFechasParcialidades').append(fechaFinal);
        $('#rowFechasParcialidades').append(montoTotal);
        $('#rowFechasParcialidades').append(parcialidad);
        $('#rowFechasParcialidades').append(periodoPagos);
        $('#rowFechasParcialidades').append(montoParcialidad);
    });

    function inputChange(){
        if($('#tipoResicion').val() == 'T') {
            obtenerMontoParcialidades();
        }else if(($('#tipoResicion').val() == 'M')){
            obtenerParcialdades();
        }
    }
    

    function obtenerParcialdades(){
        if(!$('#montoTotal').val() || !$('#montoParcialidad').val()) return;
        total = $('#montoTotal').val();
        parcialidad = $('#montoParcialidad').val();

        if(total % parcialidad != 0) $('#numeroPagos').val(Math.floor((total/parcialidad)) + 1)
        else $('#numeroPagos').val(total/parcialidad)
    }

    function obtenerMontoParcialidades(){
        if(!$('#montoTotal').val() || !$('#numeroPagos').val()) return;
        total = $('#montoTotal').val();
        parcialidad = $('#numeroPagos').val();

        $('#montoParcialidad').val((total/parcialidad).toFixed(2));
    }

    function generarTablaPagos(){
        /**
         * @author Dante Aldair Guerrero Aldana <coordinador6.desarrollo@ciudadmaderas.com>
         * A continuación se muestra el tipo de parcialidad de acuerdo con el campo 
         * "programado" en la tabla "solpagos".
         * ****************************
         *  __________________________
         * |     Periodo      | Valor |
         * |__________________________|
         * |     Semanal      |   7   |
         * |__________________________|
         * |    Quincenal     |   8   |
         * |__________________________|
         * |   Mensualmente   |   1   |
         * |__________________________|
         * |    Bimestral     |   2   |
         * |__________________________|
         * |    Trimestral    |   3   |
         * |__________________________|
         * |   Cuatrimestral  |   4   |
         * |__________________________|
         * |    Semestral     |   6   |
         * |__________________________|
         */
        obtenerFechaFinPagos();

        $('#rowTablaPagos').text('');
        if(!$('#montoTotal').val() || !$('#numeroPagos').val() || !$('#montoParcialidad').val() || !$('#programado').val() || !$('#fecha_Inicio').val()) return;
        montoTotal = parseFloat($('#montoTotal').val()); // cantidad
        parcialidades = parseInt($('#numeroPagos').val()) // numeroPagos
        montoParcialidad = parseFloat($('#montoParcialidad').val()) // montoParcialidad
        periodo = parseInt($('#programado').val()) // programado
        divContainer = $('<div id="divTablePagos" class="col-lg-12"></div>')
        const fechaInicioNuevo = moment($('#fecha_Inicio').val())  // fecelab
        
        tabla = $(`<table class="table">
            <thead>
                <tr>
                    <th scope="col" class="text-center">#</th>
                    <th scope="col" class="text-center">FECHA PAGO</th>
                    <th scope="col" class="text-center">PARCIALIDAD</th>
                    <th scope="col" class="text-center">ACUMULADO</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
            </table>
        `);
        let fechaProxPag = fechaInicioNuevo.clone();
        let montoAcumulado = 0;
        switch (periodo) {
            case 7: // CASO DE ACUERDO A SOLICITUD PROGRAMADA EN MODALIDAD DE SEMANALIDADES.
                for (let numPago = 1; numPago <= parcialidades; numPago++) {
                    if (numPago == parcialidades) {
                        montoParcialidad = montoTotal - montoAcumulado;
                    }
                    montoAcumulado += montoParcialidad;
                    tr = $(`<tr>
                        <th scope="row" class="text-center">${numPago}</th>
                        <td class="text-center">${formato_fechaymd(fechaProxPag.format('YYYY-MM-DD'))}</td>
                        <td class="text-center">$ ${formatMoney(montoParcialidad)}</td>
                        <td class="text-center">$ ${formatMoney(montoAcumulado)}</td>
                    </tr>`);
                    tabla.append(tr);
                    fechaProxPag = fechaInicioNuevo.clone().add(numPago, 'weeks');
                }
                break;
            case 8:
                for (let numPago = 1; numPago <= parcialidades; numPago++) {
                    if (numPago == parcialidades) {
                        montoParcialidad = montoTotal - montoAcumulado;
                    }
                    montoAcumulado += montoParcialidad;
                    tr = $(`<tr>
                        <th scope="row" class="text-center">${numPago}</th>
                        <td class="text-center">${formato_fechaymd(fechaProxPag.format('YYYY-MM-DD'))}</td>
                        <td class="text-center">$ ${formatMoney(montoParcialidad)}</td>
                        <td class="text-center">$ ${formatMoney(montoAcumulado)}</td>
                    </tr>`);
                    tabla.append(tr);
                    fechaProxPag = fechaInicioNuevo.clone().add((numPago*15), 'days');
                }
                break;
            case 1:
                for (let numPago = 1; numPago <= parcialidades; numPago++) {
                    if (numPago == parcialidades) {
                        montoParcialidad = montoTotal - montoAcumulado;
                    }
                    montoAcumulado += montoParcialidad;
                    tr = $(`<tr>
                        <th scope="row" class="text-center">${numPago}</th>
                        <td class="text-center">${formato_fechaymd(fechaProxPag.format('YYYY-MM-DD'))}</td>
                        <td class="text-center">$ ${formatMoney(montoParcialidad)}</td>
                        <td class="text-center">$ ${formatMoney(montoAcumulado)}</td>
                    </tr>`);
                    tabla.append(tr);
                    fechaProxPag = fechaInicioNuevo.clone().add(numPago, 'months');
                }
                break;
            case 2:
                for (let numPago = 1; numPago <= parcialidades; numPago++) {
                    if (numPago == parcialidades) {
                        montoParcialidad = montoTotal - montoAcumulado;
                    }
                    montoAcumulado += montoParcialidad;
                    tr = $(`<tr>
                        <th scope="row" class="text-center">${numPago}</th>
                        <td class="text-center">${formato_fechaymd(fechaProxPag.format('YYYY-MM-DD'))}</td>
                        <td class="text-center">$ ${formatMoney(montoParcialidad)}</td>
                        <td class="text-center">$ ${formatMoney(montoAcumulado)}</td>
                    </tr>`);
                    tabla.append(tr);
                    fechaProxPag = fechaInicioNuevo.clone().add((numPago * 2), 'months');
                }
                break;
            case 3:
                for (let numPago = 1; numPago <= parcialidades; numPago++) {
                    if (numPago == parcialidades) {
                        montoParcialidad = montoTotal - montoAcumulado;
                    }
                    montoAcumulado += montoParcialidad;
                    tr = $(`<tr>
                        <th scope="row" class="text-center">${numPago}</th>
                        <td class="text-center">${formato_fechaymd(fechaProxPag.format('YYYY-MM-DD'))}</td>
                        <td class="text-center">$ ${formatMoney(montoParcialidad)}</td>
                        <td class="text-center">$ ${formatMoney(montoAcumulado)}</td>
                    </tr>`);
                    tabla.append(tr);
                    fechaProxPag = fechaInicioNuevo.clone().add((numPago * 3), 'months');
                }
                break;
            case 4:
                for (let numPago = 1; numPago <= parcialidades; numPago++) {
                    if (numPago == parcialidades) {
                        montoParcialidad = montoTotal - montoAcumulado;
                    }
                    montoAcumulado += montoParcialidad;
                    tr = $(`<tr>
                        <th scope="row" class="text-center">${numPago}</th>
                        <td class="text-center">${formato_fechaymd(fechaProxPag.format('YYYY-MM-DD'))}</td>
                        <td class="text-center">$ ${formatMoney(montoParcialidad)}</td>
                        <td class="text-center">$ ${formatMoney(montoAcumulado)}</td>
                    </tr>`);
                    tabla.append(tr);
                    fechaProxPag = fechaInicioNuevo.clone().add((numPago * 4), 'months');
                }
                break;
            case 6:
                for (let numPago = 1; numPago <= parcialidades; numPago++) {
                    if (numPago == parcialidades) {
                        montoParcialidad = montoTotal - montoAcumulado;
                    }
                    montoAcumulado += montoParcialidad;
                    tr = $(`<tr>
                        <th scope="row" class="text-center">${numPago}</th>
                        <td class="text-center">${formato_fechaymd(fechaProxPag.format('YYYY-MM-DD'))}</td>
                        <td class="text-center">$ ${formatMoney(montoParcialidad)}</td>
                        <td class="text-center">$ ${formatMoney(montoAcumulado)}</td>
                    </tr>`);
                    tabla.append(tr);
                    fechaProxPag = fechaInicioNuevo.clone().add((numPago * 6), 'months');
                }
                break;
        }
 
        divContainer.append(tabla)
        $('#rowTablaPagos').append(divContainer)
    }
    
    $("#nombreproveedor").change(function (){
        if ($(this).val().trim() != '') {
            mostrarDivs($(this));
        }else{
            $("#rowTablaProveedores").addClass("hidden");
        }
        if ($("#forma_pago").val() != '' || !$("#forma_pago").val()) {
            $("#rowTablaProveedores").addClass("hidden");
        }
    })

    $('#nombreproveedor').on( "keyup",(function(event){
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if ($(this).val().trim() != '') {
            if(keycode == '13'){
                mostrarDivs($(this))
            }
        }else{
            $("#rowTablaProveedores").addClass("hidden");
        }

        if ($("#forma_pago").val() != '' || !$("#forma_pago").val()) {
            $("#rowTablaProveedores").addClass("hidden");
        }
    }));

    function mostrarDivs(input){
        divContainer = $('#rowTablaProveedores').children('div').eq(0);
        divContainerMsg = $('#rowTablaProveedores').children('div').eq(1);
        divContaineParent = $('#rowTablaProveedores')
        divContainer.parent().removeClass('hidden')
        
        if((input.val().length + 1) < 6) {
            divContainer.addClass('hidden')
            divContainerMsg.removeClass('hidden')
            divContainerMsg.text('')
            if(divContainerMsg.children('p').length == 0) divContainerMsg.append(`<p>Son requeridos ${6 - (input.val().length + 1) } carácteres para realizar la busqueda</p>`)
            $('#forma_pago').val('')
            $('#tipocta').val('')
            $('#idbanco').val('')
            $('#cuenta').val('')    
            return;
        }
        divContainerMsg.addClass('hidden')
        divContainer.removeClass('hidden')
        divContainerMsg.text('')
        obtenerTablaProveedores(input.val())
    }

    function obtenerTablaProveedores(nombreProveedor){
        divContainer = $('#rowTablaProveedores').children('div').eq(0);
        divContainerMsg = $('#rowTablaProveedores').children('div').eq(1);
        divContainer.text('')
        resultados = []
        tabla = $(`<table class="table">
                    <thead>
                        <tr>
                            <th scope="col" class="text-center">NOMBRE</th>
                            <th scope="col" class="text-center">ALIAS</th>
                            <th scope="col" class="text-center">TIPO CUENTA</th>
                            <th scope="col" class="text-center">CUENTA</th>
                            <th scope="col" class="text-center">BANCO</th>
                            <th scope="col" class="text-center">ESTATUS</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    </table>
                `)
        
        nombres = $("#nombreproveedor");
        resultados = clientes.filter((x) => x.nombreProveedor.includes(nombres.val().toUpperCase().trim()))
        

        if(resultados.length > 0){
            divContainer.removeClass('hidden')
            divContainerMsg.addClass('hidden')
            resultados.forEach(function(proveedor){
                tr = $(`<tr>
                        <th scope="row" class="text-center">${proveedor.nombreProveedor}</th>
                        <td class="text-center">${proveedor.alias}</td>
                        <td class="text-center">${proveedor.tipoCuenta}</td>
                        <td class="text-center">${proveedor.cuenta == '0' ||proveedor.cuenta == 'null' ? '-' : proveedor.cuenta}</td>
                        <td class="text-center">${proveedor.banco}</td>
                        <td class="text-center">${proveedor.estatus}</td>
                    </tr>`)
                tr.data('row', proveedor)
                tr.click(function (){
                    $("#idproveedor_ad").append('<option value="' + proveedor.idproveedor + '">' + proveedor.nombreProveedor + ' - ' + proveedor.alias + '</option>');
                    $("#idproveedor_ad").val(proveedor.idproveedor)

                    nombres.val(proveedor.nombreProveedor)
                    divContainer.text('')
                    divContainer.addClass('hidden')
                    if(["1", "3", "40"].includes(proveedor.tipocta)){
                        $('#forma_pago').val("TEA").change()
                        $('#tipocta').val(proveedor.tipocta)
                        $('#idbanco').val(proveedor.idbanco)
                        $('#cuenta').val(proveedor.cuenta)
                    }
                })
                tabla.children('tbody').append(tr);
            })
            divContainer.append(tabla)
        }
        else{
            if(divContainerMsg.children('p').eq(0).length == 0 ) divContainerMsg.append('<p>No se encontraron beneficiarios</p>')
            divContainerMsg.removeClass('hidden')
            divContainer.text('')
            divContainer.addClass('hidden')
            divContainer.append('<p>No se encontraron clientes</p>')

        }
            
    }


    $("#nombreproveedor_new").change(function (){
        mostrarDivsAvanzarEtapa($(this));
    });

    $('#nombreproveedor_new').on( "keyup",(function(event){
        var keycode = (event.keyCode ? event.keyCode : event.which);
            
        if(keycode == '13'){
            mostrarDivsAvanzarEtapa($(this))
        }
    }));


    function mostrarDivsAvanzarEtapa(input){
        divContainer = $('#rowTablaProveedoresNew').children('div').eq(0);
        divContainerMsg = $('#rowTablaProveedoresNew').children('div').eq(1);
        divContaineParent = $('#rowTablaProveedoresNew')
        divContainer.parent().removeClass('hidden')
        
        if((input.val().length + 1) < 6) {
            divContainer.addClass('hidden')
            divContainerMsg.removeClass('hidden')
            divContainerMsg.text('')
            if(divContainerMsg.children('p').length == 0) divContainerMsg.append(`<p>Son requeridos ${6 - (input.val().length + 1) } carácteres para realizar la busqueda</p>`)
            $('#nforma_pago_new').val('')
            $('#tipocta_new').val('')
            $('#idbanco_new').val('')
            $('#cuenta_new').val('')    
            return;
        }
        divContainerMsg.addClass('hidden')
        divContainer.removeClass('hidden')
        divContainerMsg.text('')
        obtenerTablaProveedoresNew(input.val())
    }

    function obtenerTablaProveedoresNew(nombreProveedor){
        divContainer = $('#rowTablaProveedoresNew').children('div').eq(0);
        divContainerMsg = $('#rowTablaProveedoresNew').children('div').eq(1);
        divContainer.text('')
        resultados = []
        tabla = $(`<table class="table">
                    <thead>
                        <tr>
                            <th scope="col" class="text-center">NOMBRE</th>
                            <th scope="col" class="text-center">ALIAS</th>
                            <th scope="col" class="text-center">TIPO CUENTA</th>
                            <th scope="col" class="text-center">CUENTA</th>
                            <th scope="col" class="text-center">BANCO</th>
                            <th scope="col" class="text-center">ESTATUS</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    </table>
                `)
        
        var nombresNew = $("#nombreproveedor_new");
        resultados = clientes.filter((x) => x.nombreProveedor.includes(nombresNew.val().toUpperCase().trim()))
        

        if(resultados.length > 0){
            divContainer.removeClass('hidden')
            divContainerMsg.addClass('hidden')
            resultados.forEach(function(proveedor){
                tr = $(`<tr>
                        <th scope="row" class="text-center">${proveedor.nombreProveedor}</th>
                        <td class="text-center">${proveedor.alias}</td>
                        <td class="text-center">${proveedor.tipoCuenta}</td>
                        <td class="text-center">${proveedor.cuenta == '0' ||proveedor.cuenta == 'null' ? '-' : proveedor.cuenta}</td>
                        <td class="text-center">${proveedor.banco}</td>
                        <td class="text-center">${proveedor.estatus}</td>
                    </tr>`)
                tr.data('row', proveedor)
                tr.click(function (){
                    $("#idproveedor_ad_new").append('<option value="' + proveedor.idproveedor + '">' + proveedor.nombreProveedor + ' - ' + proveedor.alias + '</option>');
                    $("#idproveedor_ad_new").val(proveedor.idproveedor)

                    nombresNew.val(proveedor.nombreProveedor)
                    divContainer.text('')
                    divContainer.addClass('hidden')
                    if(["1", "3", "40"].includes(proveedor.tipocta)){
                        $('#nforma_pago_new').val("TEA").change()
                        $('#tipocta_new').val(proveedor.tipocta)
                        $('#idbanco_new').val(proveedor.idbanco)
                        $('#cuenta_new').val(proveedor.cuenta)
                    }
                })
                tabla.children('tbody').append(tr);
            })
            divContainer.append(tabla)
        }
        else{
            if(divContainerMsg.children('p').eq(0).length == 0 ) divContainerMsg.append('<p>No se encontraron beneficiarios</p>')
            divContainerMsg.removeClass('hidden')
            divContainer.text('')
            divContainer.addClass('hidden')
            divContainer.append('<p>No se encontraron clientes</p>')

        }
            
    }

    function bitacora_excel() {
        let fecha_inicio = $("#fecInicial").val();
        let fecha_fin = $("#fecFinal").val();
        $.ajax({
            url : url + "Reportes/descarga_bitacora_xlsx",
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
                a.download = 'BITACORA.xlsx';
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Errors:', textStatus, errorThrown);
            }
        });
    }
    /**
     * @uthor Efrain Martinez Muñoz | Fecha : 27/03/2025
     * Se crea la función previsualizarImagen que permite vizualizar la imagen antes de cargarla.
     */
    function previsualizarImagen(event) {
        var fileInput = event.target; // Obtiene el input de tipo file
        var contenedorImagen = document.getElementById('contenedorImagen');
        if (fileInput.files && fileInput.files[0]) {
            var newImageUrl = URL.createObjectURL(fileInput.files[0]); // Crea una URL temporal
            contenedorImagen.style.display = "block";
            document.getElementById('imagenActual').src = newImageUrl;
        }
    }
    $("#proyecto").change(function (event) {
        $("#forma_pago").val('').change();
        if ($("#proceso").val() == 30) {
            $("#NA, #ECHQ, #MAN, #TEA").show();
            let idProyecto = $(this).find('option:selected').data('value');
            cambioFormaPago(idProyecto, 'proyecto');
        }
    });
    
    /********************************************************************************************************************************/

    function obtenerHistorialPagos(data){
        const montoTotalLiquidar = new Intl.NumberFormat('es-MX', {
            style: 'currency',
            currency: 'MXN',
            minimumFractionDigits: 2
        }).format(data.cantidad);
        let totalAutorizado = 0;
        let diferenciaAutTotal
        $.ajax({
            url: url + 'Devoluciones_Traspasos/obtenerHistorialPagos',
            type: 'POST',
            data: {
                idsolicitud: data.idsolicitud
            }, 
            success: function(result) {
                result = JSON.parse(result);
                $('#modalHistorial').find('#exampleModalLongTitle').text('');
                $('#modalHistorial').find('#exampleModalLongTitle').append(`<p style="font-size: 20px;">HISTORIAL DE PAGOS DE LA SOLICITUD: <b>${data.idsolicitud}<b></p>`);
                $('#divMontoTotal #montoTotal').html(`<b>${montoTotalLiquidar}<b>`);
                $('#divNumParcialidades')
                    .html(` <h6 class="text-muted mb-2 " style="margin-bottom: 5px; display: inline;">
                                TOTAL DE PARCIALIDADES PROGRAMADAS: 
                            </h6>
                            <span class="display-4 fw-bold text-primary" style="font-size: 1.8rem; color: #0d6efd; margin-bottom: 5px;">
                                <b>${data.totalPagos}<b>
                            </span><br>
                            <h6 class="text-muted mb-2 " style="margin-bottom: 5px; display: inline;">
                                TOTAL DE PARCIALIDADES AUTORIZADAS: 
                            </h6>
                            <span class="display-4 fw-bold text-primary" style="font-size: 1.8rem; color: #0d6efd; margin-bottom: 5px;">
                                <b>${data.pagosAut}<b>
                            </span><br>
                            <h6 class="text-muted mb-2 " style="margin-bottom: 5px; display: inline;">
                                TOTAL DE PARCIALIDADES PAGADAS: 
                            </h6>
                            <span class="display-4 fw-bold text-primary" style="font-size: 1.8rem; color: #0d6efd; margin-bottom: 5px;">
                                <b>${data.pagos}<b>
                            </span>`)
                var modalBody = $('#modalHistorial').find('.modal-body');
                modalBody.text('');

                if(result.data.length > 0){
                    var tabla = $(`<table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">PAGO</th>
                                    <th scope="col">CANTIDAD</th>
                                    <th scope="col">FECHA PV</th>
                                    <th scope="col">FECHA PAGO</th>
                                    <th scope="col">FECHA OPERACIÓN</td>
                                    <th scope="col">TIPO PAGO</td>
                                </tr>
                            </thead>
                            <tbody>
                            
                            </tbody>
                        </table>`);
                    
                    result.data.forEach(function(row, i){
                        tr = `<tr>
                                <th scope="row">${i+1}</th>
                                <td><p>${row.idpago}</p></td>
                                <td><p>$${formatMoney(row.cantidad)}</td>
                                <td><p>${formato_fechaymd(row.fechaPV)}</p></td>
                                <td><p>${formato_fechaymd(row.fechaPago)}</p></td>
                                <td><p>${formato_fechaymd(row.fechaOperacion)}</p></td>
                                <td><p>${row.tipoPago ? row.tipoPago : '-'}</p></td>
                            </tr>`
                        tabla.children('tbody').append(tr);
                        totalAutorizado += parseFloat(row.cantidad);
                    });
                    modalBody.append(tabla);            
                }
                else{
                    modalBody.append('<p class="text-center">NO HAY REGISTRO DE PAGOS.</p>');
                }                
                diferenciaAutTotal = data.cantidad - data.cantidad_autorizada_total;
                $colorEtq = diferenciaAutTotal < 0 ? '#a00c0c' : '#198754';
                $('#montoTotalAutorizado').html(`<span style='color: ${$colorEtq};' class=' text-muted mb-2'>IMPORTE TOTAL AUTORIZADO: <b>${new Intl.NumberFormat('es-MX',{style:'currency', currency:'MXN', minimumFractionDigits:2}).format(data.cantidad_autorizada_total)}</b></span><br>`);
                
                if (data.cantidad >= data.cantidad_autorizada_total) {
                    $('#montoTotalAutorizado').append(`<br><span style='color: #fd7e14;' class=' text-muted mb-2'>SALDO PENDIENTE DE AUTORIZACIÓN: <b>${new Intl.NumberFormat('es-MX',{style:'currency', currency:'MXN', minimumFractionDigits:2}).format(diferenciaAutTotal)}<b>`);
                }
                else if (data.cantidad < data.cantidad_autorizada_total) {
                    $('#montoTotalAutorizado').append(`<br><span class='advertencia-pulso text-muted mb-2' style='color: #ff0000'>¡ADVERTENCIA! SE HA AUTORIZADO UN MONTO EXCEDENTE POR: <b>${new Intl.NumberFormat('es-MX',{style:'currency', currency:'MXN', minimumFractionDigits:2}).format(diferenciaAutTotal)}<b>`);
                }
                $('#modalHistorial').modal('show');
            }
        });

    }

    
</script>

<?php
    require("footer.php");
?>