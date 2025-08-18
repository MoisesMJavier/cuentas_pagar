<?php
require("head.php");
require("menu_navegador.php");
?> 
<style>
    .ocultarColumns button{
        background-color: transparent;
        border-radius: 20px;
    }
    .ocultarColumns .open {
        border-radius: 15px;
        border: 1px solid #0000004f;
        background-color: transparent;
        padding: 0;
        margin-top: 5px;
        max-height: 20px;  /* Altura máxima del select */
        overflow-y: auto;
    }

    .ocultarColumns .open .open{
        margin-top: 0px;
    }
    .encabezado_tabla:focus, .encabezado_tabla:hover {
        border-bottom: 2px solid #000000ab; /* Cambia el color del borde inferior al enfocarse */
    }
    .encabezado_tabla[placeholder]:empty:before {
        content: attr(placeholder);
    }
    .encabezado_tabla[placeholder]:empty:focus:before {
        content: "";
    }
    .encabezado_tabla {
        width: 100%;
        display: inline-block;
        cursor: text;
        font-size: .9em;
        text-align: center;
        border: none;
        outline: none;
        padding: 2px 8px;
        white-space: nowrap;
    }
    .botones_DataTable{
        display: flex; 
        flex-direction: row; 
        flex-wrap: wrap; 
        justify-content: flex-end;
        padding: 0px 15px;
        padding-bottom: 15px;
    }
    .filtros_prov{
        padding: 15px 15px;
        display: flex;
        flex-direction: row;
        flex-wrap: nowrap;
        align-content: space-around;
        justify-content: space-between;
        align-items: center;
    }
    p small.etq {
        display: inline-block;
        padding: 3px 5px 3px;
        font-size: 0.8em;
        font-weight: 700;
        line-height: 1;
        color: #fff;
        text-align: center;
        max-width: 100%;
        border-radius: 10px;
        line-height: 11px;
    }
    td p {
        /* padding-right: 24px;
        padding-left: 2px; */
        text-align: center;
    }

</style>

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <div class="box">
                    <div class="box-header with-border">
                        <h3>PROVEEDORES ACTUALES / TEMPORALES / COLABORADORES DE CIUDAD MADERAS <i class="far fa-question-circle faq" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" title="Ayuda" data-content="Para registrar un proveedor será necesario llenar los datos de la sección NUEVO PROVEEDOR, en caso de querer consultar información específica de un proveedor se puede hacer en PROVEEDORES ACTUALES o TEMPORALES." data-placement="right"></i></h3>
                    </div>
                    <div class="box-body">
                        <a id  = "destxt" href="#" download></a>
                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                                <li class="active"><a id="autorizaciones-tab" date-value="#" data-toggle="tab" href="#facturas_actualizar" role="tab" aria-controls="#home" aria-selected="true">NUEVO PROVEEDOR</a></li> <!-- FECHA: 10-MARZO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                                <li><a id="profile-tab" data-toggle="tab" date-value="#"  href="#facturas_anteriores" role="tab" aria-controls="facturas_finalizadas" aria-selected="false">PROVEEDORES ACTUALES</a></li>
                                <li><a id="profile-tab" data-toggle="tab" date-value="ver_datosprovs_temporales" href="#proveedores_temporales" role="tab" aria-controls="proveedores_temporales" aria-selected="false">PROVEEDORES TEMPORALES</a></li>
                                <li><a id="profile-tab" data-toggle="tab" date-value="ver_datosprovs_cm" href="#proveedores_temporales" role="tab" aria-controls="proveedores_temporales" aria-selected="false">COLABORADORES CIUDAD MADERAS</a></li>
                            </ul>
                        </div>
                        <div class="tab-content">
                            <div class="active tab-pane" id="facturas_actualizar"> <!-- FECHA: 10-MARZO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                                <div class="row">
                                    <div class="col-lg-12">
                                        <form id="fomNuevoProv" >
                                            <div class="row">
                                                <div class="col-lg-3">
                                                    <input type="checkbox" name="proveedor_servicio" class="form-check-input proveedor_servicio" id="proveedor_servicio" value="1">
                                                    <label  class="form-check-label proveedor_servicio"
                                                            for="proveedor_servicio"
                                                            style="margin-top: 7px; margin-bottom: 0px;">
                                                        PROVEEDOR DE SERVICIOS
                                                    </label>
                                                </div>
                                                <div class="col-lg-4 col-lg-offset-5"> 
                                                    <button type="submit" style="width: 45%; float: right;" class="btn btn-success btn-block">REGISTRAR</button>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row">
                                                <div class="col-lg-12 form-group">
                                                    <label>NOMBRE (S)<span class="text-danger">*</span></label>
                                                    <input type="text" name="nombre"  id="nombre" class="form-control tf w-input" placeholder="Ingrese nombre sin ñÑ (ej. Apple, Telmex, etc.)" required onkeyup="mayus(this);" onKeyPress="if(this.value.length==100) return false;return check(event);">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-4 form-group">
                                                    <label>RFC:</label>
                                                    <input type="text"  id="rfc"  name="rfc" data-chars="^[a-zA-Z0-9]*$" onKeyPress="if(this.value.length==13) return false;return check(event);" class="form-control tf w-input" placeholder="Ingrese RFC del proveedor." minlength="12">
                                                </div>
                                                    <div class="col-lg-4 form-group">
                                                    <label>RÉGIMEN FISCAL:</label>
                                                        <select  id="rf_proveedor"  name="rf_proveedor"  class="form-control"></select>
                                                    </div>
                                                <div class="col-lg-4 form-group">
                                                    <label>CÓDIGO POSTAL:</label>
                                                    <input type="text"  id="cp_proveedor"  name="cp_proveedor" class="form-control tf w-input" placeholder="Ingrese código postal del proveedor.">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-4 form-group">
                                                    <label>BANCO<span class="text-danger">*</span></label>
                                                    <select name="idbanco" class="form-control bancos" id="idbanco" required >
                                                        <option value="">Seleccione una opción</option>
                                                    </select>
                                                </div>
                                                <div class="col-lg-3 form-group">
                                                    <label>TIPO DE CUENTA<span class="text-danger">*</span></label>
                                                    <select class="form-control tipoctaselect" id="tipocta" name="tipocta" required>
                                                        <option value="">Seleccione una opción</option>
                                                        <option value="1">Cuenta en Banco del Bajio</option>
                                                        <option value="3">Tarjeta de débito / crédito</option>
                                                        <option value="40">CLABE</option>
                                                    </select>
                                                </div>
                                                <div class="col-lg-5 form-group">
                                                    <label>NO. CUENTA<span class="text-danger">*</span></label>
                                                    <input type="text" name="cuenta" id="cuenta" class="form-control cuenta"  minlength="12" onkeypress="if(event.which &lt; 48 || event.which &gt; 57 ) if(event.which != 8) if(event.keyCode != 9) return false;" required>
                                                </div>
                                            </div>
                                            <div class = "row">
                                                <div class="col-lg-4 form-group">
                                                    <label>FACTURACIÓN<span class="text-danger">*</span></label>
                                                    <select id="excepcion_factura" name="excepcion_factura" class="form-control tprov" required>
                                                        <option value="">Seleccione una opción</option>
                                                        <option value="0">OBLIGATORIO CARGAR XML</option>
                                                        <option value="1">XML POSTERIOR AL PAGO</option>
                                                        <option value="2">NUNCA SE RECIBIRA FACTURA</option>
                                                        <option value="3">INVOICE</option><!-- Efrain Martinez Muñoz-->
                                                    </select>
                                                </div>
                                                <div class="col-lg-4 form-group">
                                                    <label>TIPO PROVEEDOR<span class="text-danger">*</span></label>
                                                    <select name="tipoprov" class="form-control tprov" id="tipoprov" data-value="idusuario" required>
                                                        <option value="">Seleccione una opción</option>
                                                        <option value="1">INTERNO</option>
                                                        <option value="0">EXTERNO</option>
                                                        <option value="2">EMPRESA</option>
                                                        <option value="3">IMPUESTO</option>
                                                    </select>
                                                </div>
                                                <div class="col-lg-4 form-group">
                                                    <label>PROVEEDOR<span class="text-danger">*</span></label>
                                                    <select name="idusuario" class="form-control usuario" id="idusuario" required>
                                                        <option value="">Seleccione una opción</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-4 form-group">
                                                    <label>SUCURSAL<span class="text-danger">*</span></label>
                                                    <select name="idsucursal" class="form-control sucursal" id="idsucursal"  required>
                                                        <option value="">Seleccione una opción</option>
                                                    </select>
                                                </div>
                                                <div class="col-lg-4 form-group">
                                                    <label>CONTACTO:</label>
                                                    <input type="text" name="contacto" class="form-control tf w-input" onKeyPress="if(this.value.length==50) return false;return check(event);" placeholder="Ingrese algun contacto (ej. Maria Morales.)">
                                                </div>
                                                <div class="col-lg-4 form-group">
                                                    <label>CORREO ELECTRÓNICO</label>
                                                    <input type="email" id="email" name="email" class="form-control" placeholder="Ingrese e-mail" onKeyPress="if(this.value.length==150) return false;">
                                                </div>
                                            </div>
                                        </form>                                              
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="facturas_anteriores">
                                <div class="row">
                                    <div class="col-lg-12 filtros_prov">
                                        <!-- <div class="col-md-2">
                                            <label for="colOcultar">Ocultal columnas:</label>
                                            <br>
                                            <select class="selectpicker ocultarColumns" multiple title="Ocultar columnas" id="colOcultar">
                                            </select>
                                        </div> -->
                                        
                                    </div>
                                    <div class="col-lg-12" style="padding: 0px 15px;">
                                        <div class="col-md-3">
                                            <input type="number" class="form-control" placeholder="Introducir consecutivo" id="consec" name="consec"  onKeyPress="if(this.value.length==3) return false;">
                                        </div>
                                        <!-- Campo oculto para seleccionar el archivo Excel -->
                                        <!-- <input type="file" id="subir_excel_prov" style="display:none;" accept=".xlsx, .xls" onchange="subirArchivoActualizarProv(event)"> -->
                                        <input type="file" id="subir_excel_prov" style="display:none;" accept=".xlsx, .xls">
                                        <table class="table table-striped" id="tabla_historial" style="width: 100%;">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th class="fac_fal" style="font-size: .9em"></th>                   <!-- COLUMNA 0 -->
                                                    <th class="fac_fal" style="font-size: .9em">NOMBRE</th>             <!-- COLUMNA 1 -->
                                                    <th class="fac_fal" style="font-size: .9em">ALIAS</th>              <!-- COLUMNA 2 -->
                                                    <th class="fac_fal" style="font-size: .9em">RFC</th>                <!-- COLUMNA 3 -->
                                                    <th class="fac_fal" style="font-size: .9em">REGIMEN FISCAL</th>     <!-- COLUMNA 4 -->
                                                    <th class="fac_fal" style="font-size: .9em">NO. CUENTA</th>         <!-- COLUMNA 5 -->
                                                    <th class="fac_fal" style="font-size: .9em">BANCO</th>              <!-- COLUMNA 6 -->
                                                    <th class="fac_fal" style="font-size: .9em">CORREO</th>             <!-- COLUMNA 7 -->
                                                    <th class="fac_fal" style="font-size: .9em">ESTATUS</th>            <!-- COLUMNA 8 -->
                                                    <th class="fac_fal" style="font-size: .9em">CREACIÓN</th>           <!-- COLUMNA 9 -->
                                                    <th class="fac_fal" style="font-size: .9em">PROVEEDOR</th>          <!-- COLUMNA 10 -->
                                                    <th class="fac_fal" style="font-size: .9em">PROV. SERVICIOS</th>    <!-- COLUMNA 11 -->
                                                    <th class="fac_fal" style="font-size: .9em">FACTURACION</th>        <!-- COLUMNA 12 -->
                                                    <th class="fac_fal" style="font-size: .9em">DÍAS DE CRÉDITO</th>    <!-- COLUMNA 13 -->
                                                    <th class="fac_fal" style="font-size: .9em">MONTO DE CRÉDITO</th>   <!-- COLUMNA 14 -->
                                                    <th class="fac_fal" style="font-size: .9em">IDPROVEEDOR</th>         <!-- COLUMNA 15 -->
                                                    <th></th>                                                           <!-- COLUMNA 16 -->
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div> <!--End solicitudes finalizadas--> 
                            <div class="tab-pane fade" id="proveedores_temporales">
                                <div class="row">
                                    <div class="col-lg-12">
                                            <!--   <h4>LISTA DE PROVEEDORES TEMPORALES</h4> --><br>
                                            <div class="col-md-4">
                                                <input type="number" class="form-control" placeholder="Introducir consecutivo" id="consec2" name="consec2" onKeyPress="if(this.value.length==3) return false;">
                                            </div>
                                            <div class="col-md-5">
                                            </div>
                                            <table class="table table-striped" id="tabla_proveedores_temporales" style="width: 100%;">
                                                <thead class="thead-dark">
                                                    <tr>
                                                        <th class="fac_fal" style="font-size: .9em"></th>
                                                        <th class="fac_fal" style="font-size: .9em">NOMBRE</th>
                                                        <th class="fac_fal" style="font-size: .9em">ALIAS</th>
                                                        <th class="fac_fal" style="font-size: .9em">RFC</th>
                                                        <th class="fac_fal" style="font-size: .9em">NO. CUENTA</th>
                                                        <th class="fac_fal" style="font-size: .9em">BANCO</th>
                                                        <th class="fac_fal" style="font-size: .9em">CORREO</th>
                                                        <th class="fac_fal" style="font-size: .9em">CREACIÓN</th>
                                                        <th class="fac_fal" style="font-size: .9em">PROVEEDOR</th>
                                                        <th class="fac_fal" style="font-size: .9em">ESTATUS</th> <!-- FECHA: 05-AGOSTO-2025 | AJUSTE CATALOGO PROVEEDORES | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                            </table>
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

<div class="modal fade modal-alertas" id="myModalProv" role="info">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"  style="background: #DD4B39; color: #fff;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">ELIMINAR</h4>
            </div>
            <form method="post" id="form_eliminar">
                <div class="modal-body">

                </div>
                <div class="modal-footer"></div>

            </form>
        </div>
    </div>
</div>

<!--modal update-->

<div class="modal fade" id="modalUpdate" role="dialog">
    <div class="modal-dialog">
     <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title" id="exampleModalLabel">INFORMACIÓN DEL PROVEEDOR</h4>
        </div>
        <div class="modal-body">
            <form id="proveedor_form">
                <p>Información del proveedor. Complete los campos requeridos (<span class="text-danger">*</span>)</p>
                <div class="col-lg-12" style="padding-left: 0px;">
                    <input  type="checkbox"
                            name="proveedor_servicio_edit" 
                            class="form-check-input proveedor_servicio_edit"
                            style="width: 9px; height: 9px;"
                            id="proveedor_servicio_edit" 
                            value="1">
                    <label  class="form-check-label proveedor_servicio_edit"
                            for="proveedor_servicio_edit"
                            style="font-size: x-small; font-weight: 800;">
                        PROVEEDOR DE SERVICIOS
                    </label>
                </div>
                <br>
                <br>
                <div class="row">
                    <div class="col-lg-12 form-group">
                        <label>NOMBRE(S)<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nombreActual" name="nombreActual" onkeyup="mayus(this);"  class="form-control tf w-input" required  onKeyPress="if(this.value.length==100) return false;return check(event);">
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4 form-group">
                        <label>RFC</label>
                        <input type="text" class="form-control" id="rfcA" name="rfcA" onKeyPress="if(this.value.length==13) return false;return check(event);" class="form-control tf w-input"  minlength="12">
                    </div>
                    <div class="col-lg-4 form-group">
                        <label>TIPO DE PROVEEDOR<span class="text-danger">*</span></label>
                        <select id="tipoprovA" name="tipoprovA" class="form-control tprov" data-value="idusuarioA" required>
                            <option value="">Seleccione una opción</option>
                            <option value="1">INTERNO</option>
                            <option value="0">EXTERNO</option>
                            <option value="2">EMPRESA</option>
                            <option value="3">IMPUESTO</option>
                        </select>
                    </div>
                    <div class="col-lg-4 form-group">
                        <label>PROVEEDOR<span class="text-danger">*</span></label>
                        <select  id="idusuarioA" name="idusuarioA" class="form-control usuario" required>
                            <option value="">Seleccione una opción</option>                             
                        </select>
                    </div>                
                </div>
                <div class="row">
                    <div class="col-lg-4 form-group">
                        <label>BANCO<span class="text-danger">*</span></label>
                        <select id="idbancoA" name="idbancoA" class="form-control bancos"></select>
                    </div>
                    <div class="col-lg-4 form-group">
                        <label>TIPO DE CUENTA<span class="text-danger">*</span></label>
                        <select class="form-control" id="tipoctaA" name="tipoctaA"  >
                            <option value="">Seleccione una opción</option>
                            <option value="1">Cuenta en Banco del Bajio</option>
                            <option value="3">Tarjeta de debito</option>
                            <option value="40">CLABE</option>
                        </select>
                    </div>
                    <div class="col-lg-4 form-group">
                        <label>NO.CUENTA<span class="text-danger">*</span></label>
                        <input type="text"  id="cuentaA" name="cuentaA" class="form-control"  onkeypress="if(event.which &lt; 48 || event.which &gt; 57 ) if(event.which != 8) return false;">
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4 form-group">
                        <label>SUCURSAL</label>
                        <select  id="idsucursalA" name="idsucursalA" class="form-control sucursal"></select>
                    </div>
                    <div class="col-lg-4 form-group">
                        <label>CONTACTO</label>
                        <input type="text" class="form-control" id="contactoA" name="contactoA">
                    </div>
                    <div class="col-lg-4 form-group">
                        <label>EMAIL</label>
                        <input type="email" class="form-control" id="emailA" name="emailA" onKeyPress="if(this.value.length==150) return false;">
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4 form-group">
                        <label>FACTURACIÓN<span class="text-danger">*</span></label>
                        <select id="excepcion_factura_edita" name="excepcion_factura" class="form-control tprov" required>
                            <option value="">Seleccione una opción</option>
                            <option value="0">OBLIGATORIO CARGAR XML</option>
                            <option value="1">XML POSTERIOR AL PAGO</option>
                            <option value="2">NUNCA SE RECIBIRA FACTURA</option>
                            <option value="3">INVOICE</option><!-- Efrain Martinez Muñoz-->
                        </select>
                    </div>
                    <div class="col-lg-4 form-group">
                        <label>CLASE PROVEEDOR<span class="text-danger">*</span></label>
                        <select id="clase_prov" name="clase_prov" class="form-control tprov" required>
                            <option value="1">PROVEEDOR</option>
                            <option value="2">TEMPORAL</option>
                            <option value="5">COLABORADOR MADERAS</option>
                        </select>
                    </div>
                    <div class="col-lg-4 form-group">
                        <label>CUENTA DE NOMINA<span class="text-danger">*</span></label>
                        <select id="cuenta_nomina" name="cuenta_nomina" class="form-control tprov" required>
                            <option value="1">SI</option>
                            <option value="0">NO</option>
                        </select>
                    </div>
                </div>
                <!-- Edicion de formulario de edicion proveedor. -->
                <!-- @author Dante Aldair Guerrero Aldana Fecha: 29-Enero-2025 -->
                <div class="row" id="datos_credito">
                    <div class="col-lg-4 form-group">
                        <label for="dias_credito">DÍAS DE CRÉDITO</label>
                        <input type="number" id="dias_credito" name="dias_credito" min="1" step="1" class="form-control">
                    </div>
                    <div class="col-lg-4 form-group">
                        <label for="monto_credito">MONTO DE CRÉDITO</label>
                        <input type="text" id="monto_credito" name="monto_credito" oninput="formatearMoneda(this)" class="form-control">
                    </div>
                    
                </div>

                <div class="row">
                    <div class="col-lg-4 col-lg-offset-4">
                        <button class="btn btn-success btn-block">GUARDAR</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</div><!-- FIN MODAL -->


<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"></div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <button type="button" id="cerrar_mymodal" class="btn btn-danger">CERRAR</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="myModal12" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
        </div>
        <div class="modal-body"></div>
        <div class="modal-footer">
        </div>
    </div>
</div>
</div>

<div class="modal fade" id="modal_formulario_generico" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"></div>
            <div class="modal-body">
                <form id="formulario_generico" action="#">
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

var lista_regf = [];
var idproveedor;
var link_post;
var table_proceso;
var table_proveedores_temporales;
var proveedores_dinamicos = "";
var tabla_update = "";
//Dante Aldair
var reziseInfo;

/**
 * @author Dante Aldair <programador.analista18@ciudadmaderas.com>
 * Cambios: Variables para la automatización al momento de exportar un archivo Excel.
 *
**/
var titulos_encabezado = [];
var num_colum_encabezado = [];

var infoUserDepto = '<?= $this->session->userdata("inicio_sesion")['depto'];?>';
var infoUserRol = '<?= $this->session->userdata("inicio_sesion")['rol'];?>';
var infoUserId = <?= $this->session->userdata("inicio_sesion")['id']; ?>;
var botonReporteExcel; /** FECHA: 10-MARZO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
var botonDescargarTxt; /** FECHA: 10-MARZO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
var botonSubirExcel; /** FECHA: 10-MARZO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
var botones = []; /** FECHA: 10-MARZO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
var titulos_encabezado_temp = [];
var num_colum_encabezado_temp = [];
var usuariosPermisosUnicos = [99]; // (#99 | Lirio Lugo | ASISTENTES | CONSTRUCCION), () /** FECHA: 10-MARZO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
/***********************************************************************/
    document.addEventListener("DOMContentLoaded", function () { /** INICIO FECHA: 10-MARZO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
        function toggleTabsVisibility(activateTabId, hideTabIds) {
            // Activar la pestaña especificada
            document.getElementById(activateTabId).classList.add("active", "show");
            document.getElementById(activateTabId).classList.remove("fade");

            // Ocultar otras pestañas
            hideTabIds.forEach(id => {
                document.getElementById(id).classList.remove("active", "show");
                document.getElementById(id).classList.add("fade");
            });
        }

        if (usuariosPermisosUnicos.includes(infoUserId)) {
            toggleTabsVisibility("facturas_anteriores", ["facturas_actualizar", "proveedores_temporales"]);

            // Ocultar pestañas en el menú
            document.querySelector("a[href='#facturas_actualizar']").parentElement.style.display = "none";
            document.querySelectorAll("a[href='#proveedores_temporales']").forEach(el => el.parentElement.style.display = "none");
        } else {
            toggleTabsVisibility("facturas_actualizar", ["facturas_anteriores", "proveedores_temporales"]);

            // Restaurar visibilidad de todas las pestañas en el menú
            document.querySelectorAll(".nav-tabs li").forEach(el => el.style.display = "block");
        }

        // Manejo del cambio de pestañas
        document.querySelectorAll(".nav-tabs a").forEach(tab => {
            tab.addEventListener("click", function (event) {
                event.preventDefault(); // Evitar cambios de URL en enlaces #
                let target = this.getAttribute("href");

                // Asegurar que solo la pestaña seleccionada esté activa
                document.querySelectorAll(".tab-pane").forEach(pane => pane.classList.remove("active", "show"));
                document.querySelector(target).classList.add("active", "show");
            });
        });
    }); /** FIN FECHA: 10-MARZO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/


    $(document).ready(function () {
        
        $("#datos_credito").hide();

        $("form").validate();

        $.getJSON( url + "Listas_select/lista_proveedores_edicion").done(function( data ){

            $.each(data['bancos'], function (ind, val) {
                $(".bancos").append("<option value='"+val.idbanco+"'>"+val.clvbanco+"  "+val.nombre+"</option>");
             });

            $.each(data['sucursales'], function (ind, val) {
                $(".sucursal").append("<option value='"+val.id_estado+"'>"+val.estado+"</option>");
             });

            $.each(data['usuarios'], function (ind, val) {
                $(".usuario").append("<option value='"+val.idusuario+"'>"+val.nombres+" "+val.apellidos+"</option>");
             }); 
        });

        $.getJSON( url + "Listas_select/cat_regimenfiscal").done(function( data ){
            $("#rf_proveedor").append('<option value="">Seleccione una opción</option>');

            $.each(data, function (ind, val) {
              $("#rf_proveedor").append("<option value='"+val.codrf+"'>"+val.codrf+" - "+val.descrf+"</option>");
            });
            
            lista_regf = data;

        });

        $(".usuario").prop("disabled" ,true);
 
        $("#tipoprovA, #tipoprov").change(function() {
            var selected = $( this );
            if( selected.children("option:selected").val() == "1" ){
                $( "#" + selected.attr( "data-value" ) ).prop("disabled" ,false);
            }else{
                $( "#" + selected.attr( "data-value" )  ).prop("disabled" ,true).val( "" );
            }
        });

        //inicio de CLABE
        $("#cuentaA").change(function(){
            var input = $( this );

            $.post(url+"Provedores_cxp/validar_cuenta_prov", {cuenta:$(this).val()}).done(function(data){
                dato = JSON.parse(data);
                if( dato == 1 ) {

                    $("#myModal12 .modal-title").html("");
                    $("#myModal12 .modal-body").html("");
                    $("#myModal12 .modal-footer").html("");

                    $('#myModal12').modal( { backdrop: 'static', keyboard: false } );
                    $("#myModal12").modal();
                    $("#myModal12 .modal-header").append("<h4 class='modal-title'>Alerta</b></h4>");
                    $("#myModal12 .modal-body").append("<h5>Esta <b>CLABE</b> <U>ya está registrada,</U> si la CLABE es correcta favor de verficar en la lista de provedores y ver si ya está registrada esta CLABE, de lo contrario pongase en contacto con un Administrador.</h5>");
                    $("#myModal12 .modal-footer").append("<input type='button' class='btn btn-info' value='Aceptar' onClick='cancela2()'>");
                    input.val("");
                }
            });
        });

        /** INICIO FECHA: 10-MARZO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
        botonReporteExcel = {
            extend: 'excelHtml5',             
            text: '<i class="fas fa-file-excel"></i> EXPORTAR ',
            messageTop: "Lista de proveedores",
            attr: {
                class: 'btn btn-success',
                style: 'margin-right: 1px;'
            },
            exportOptions: { 
                columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15],
                format: {
                    header: function (data, columnIdx) { 
                        return " " + titulos_encabezado[columnIdx-1] + " ";
                    }
                }
            },
            customize: function (xlsx) {
                // Obtener la hoja de trabajo
                var sheet = xlsx.xl.worksheets['sheet1.xml'];

                // Ocultar la columna 15 en el archivo Excel
                $('col', sheet).each(function (index) {
                    if (index == 14) { // Índice de la columna 15 (base 0)
                        $(this).attr('hidden', '1');
                    }
                });
            }
        }

        botonDescargarTxt = {
            text: '<i class="fas fa-file-download"></i> DESCARGAR TXT',
            action: function(){

                var rows = $( table_proceso.$('input[type="checkbox"]:checked')).map(function(){
                    return $(this).val();
                }).get();

                var consec = document.getElementById("consec").value;
                $.post( url + "Provedores_cxp/txtProveedores", {consec : consec,idproveedor : JSON.stringify( rows ) } ).done( function( data ){
                    data = JSON.parse( data );
                    if( !data.resultado ){
                        $("#destxt").attr("href", data.file );
                        $("#destxt")[0].click();
                    }else{
                        alert(data.mensaje);
                    }             
                });
            },
            attr: {
                class: 'btn btn-info',
            }
        }

        if ( !usuariosPermisosUnicos.includes(infoUserId) ){
            botones.push(botonReporteExcel);
            botones.push(botonDescargarTxt);
        } else if ( usuariosPermisosUnicos.includes(infoUserId) ) {
            // ANTES: if ( (['CI-COMPRAS', 'DIRECCION GENERAL'].includes(infoUserDepto) && ['CP', 'SU', 'DG'].includes(infoUserRol)) ) {
            // Botón para subir archivo Excel
            botonSubirExcel = {
                text: '<i class="fas fa-upload"></i> SUBIR EXCEL',
                action: function () {
                    // Abre un cuadro de diálogo para seleccionar el archivo
                    $('#subir_excel_prov').click();
                },
                attr: {
                    class: 'btn btn-primary',
                }
            }

            botones.push(botonReporteExcel);
            botones.push(botonSubirExcel);
        }
        /** FIN FECHA: 10-MARZO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
    });

    $('[data-toggle="tab"]').click( function(e) {
        if( $(this).attr('date-value') != "#" && proveedores_dinamicos != $(this).attr('date-value') ){
            //$("#consec2, #tabla_proveedores_temporales input[type='text']").val("");
            $("#consec2").val("");
            table_proveedores_temporales.ajax.url( url +"Provedores_cxp/"+$(this).attr('date-value') ).load();
        }
    });

    $("#tabla_historial").ready( function () {

        $('#tabla_historial thead tr:eq(0) th').each( function (i) {
            if( i != 0 && i < $('#tabla_historial thead tr:eq(0) th').length - 1 ){
                var title = $(this).text();
                titulos_encabezado.push(title);
                num_colum_encabezado.push(i);
                $(this).empty();
                $(this)[0].insertAdjacentHTML("beforeend", `<div class="encabezado_tabla" contenteditable="true" id="enc_${title.match(/[a-zA-Z]+/g).join("").toLowerCase()}_${i}" placeholder="${title}"></div>` );

                $(this).find('div.encabezado_tabla').on('keydown', function(event) {
                    // Evitar la tecla "Enter" (código 13)
                    if (event.keyCode === 13) {
                        event.preventDefault();
                        return false;
                    }
                });


                $(this).find('div.encabezado_tabla').on('keyup change', function() {
                    if (table_proceso.column(i).search() !== $(this).text().trim()) {
                        table_proceso
                            .column(i)
                            .search($(this).text())
                            .draw();
                        
                        var total = 0;
                        var index = table_proceso.rows({ selected: true, search: 'applied' }).indexes();
                        var data = table_proceso.rows(index).data();
                        
                        $.each(data, function(i, v) {
                            total += parseFloat(v.cantidad);
                        });
                        
                        var formattedTotal = formatMoney(total);
                        $("#totalxaut").html("<b>Total por comprobar:</b> $"+formattedTotal);
                    }
                });
                // $(this).html( `<div class="encabezado_tabla" contenteditable="true" id="enc_${title.match(/[a-zA-Z]+/g).join("").toLowerCase()}_${i}" placeholder="${title}"></div>` );
            }
        });
        table_proceso = $('#tabla_historial').DataTable({
            dom: botones.length > 0 ? '<"botones_DataTable" B>rtip' : 'rtip',
            "buttons": botones.length > 0 ? botones : undefined,
            "language" : lenguaje,
            "processing": false,
            "pageLength": 10,
            "bAutoWidth": false,
            "bLengthChange": false,
            "bInfo": false,
            "searching": true,
            "ordering": false,
            "scrollX": true,
            "autoWidth": true,
            "initComplete": function(settings, json) {
                reziseInfo = table_proceso.rows().data().toArray();
            },
            "columns": [
                { 
                    "width": "5%"
                },
                { 
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+ (d.nombre ? d.nombre.toUpperCase() : "") + '</p>'
                    }
                },
                { 
                    "data" : function( d ){
                        return '<p style="font-size: .9em">'+d.alias+'</p>'
                    }
                },
                {  
                    "data" : function( d ){
                        return '<p style="font-size: .9em">'+(d.rfc ? d.rfc : "---") +'</p>'
                    }
                },
                {
                    "data" : function( d ){
                        return '<p style="font-size: .9em">'+d.rf_proveedor +'</p>'
                    }
                },
                {  
                    "data" : function( d ){
                        if(d.cuenta==null||d.cuenta==''){
                        return '<small class="label pull-center bg-yellow">SIN CUENTA</small>';

                        }
                        else{ 
                        return '<p style="font-size: .9em">'+d.cuenta+'</p>';
                    }
                    }
                },
               
                {  
                    "data" : function( d ){
                       if(d.nomba==null||d.nomba==''){
                        return '<p style="font-size: .9em"><small class="label pull-center bg-gray">SIN BANCO</small></p>';

                        }
                        else{ 
                        return '<p style="font-size: .9em">'+d.nomba+'</p>';
                    }
                    }
                },
                 {  
                    "data" : function( d ){
                        if(d.email==null||d.email==''){
                        return '<small class="label pull-center bg-teal">SIN CORREO</small>';

                        }
                        else{ 
                        return '<p style="font-size: .9em">'+d.email+'</p>';
                    }
                    }
                },
                {
                    "data" : function( d ){
                        return '<p style="font-size: .9em" class="'+ (d.estatus == 1 ? 'text-success' : 'text-danger') +'">'+(d.estatus == 1 ? "ACTIVO" : "BLOQUEADO")+'</p>'
                    }
                },
                {
                    "data" : function( d ){
                        return '<center><p style="font-size: .9em">'+ ( d.fecha ? d.fecha : '' )+'</p></center>'
                    },

                }, 
                {
                    "data" : function( d ){
                        switch( d.tipo_prov ){
                            case "0":
                                return '<center><p style="font-size: .9em">EXTERNO</p></center>';
                                break;
                            case "1":
                                return '<center><p style="font-size: .9em">INTERNO</p></center>';
                                break;
                            case "2":
                                return '<center><p style="font-size: .9em">EMPRESA</p></center>';
                                break;
                            case "3":
                                return '<center><p style="font-size: .9em">IMPUESTO</p></center>';
                                break;
                            default:
                                return '<center><p style="font-size: .9em">AUN SIN DEFINIR</p></center>';
                                break;
                        }   
                    }

                },
                {
                    "data" : function( d ){
                        switch( d.prov_serv ){
                            case "0":
                                return '<center><p style="font-size: .9em">---</p></center>';
                                break;
                            case "1":
                                return '<center><p style="font-size: .9em">ACTIVO</p></center>';
                                break;
                            default:
                                return '<center><p style="font-size: .9em">---</p></center>';
                                break;
                        }
                    }
                },
                {
                    "data" : function( d ){
                        return '<center><p style="font-size: .9em">'+ d.facturacion+'</p></center>'
                    }
                },
                {
                    "data" : function( d ){
                        return '<center><p style="font-size: .9em">'+ (d.dias_credito ? d.dias_credito : ' --- ') +'</p></center>'
                    }
                },
                {
                    "data" : function( d ){
                        return '<center><p style="font-size: .9em">'+ (d.monto_credito ? ('$' + formatMoney(d.monto_credito)) : ' --- ') +'</p></center>'
                    }
                },
                {
                    "data" : function( d ){
                        return '<center><p style="font-size: .9em">'+ d.idproveedor +'</p></center>'
                    }
                },
                { 
                    "data": function( d ){

                        opciones = '';

                        switch( d.estatus ){
                            case "0":
                                opciones = '<div class="btn-group-vertical" role="group">';
                                opciones += '<button type="button" style="background:#8F888F;border:#8F888F;" class="btn btn-success btn-reactivar btn-sm"><i class="fas fa-lock"></i></button>';
                                opciones += '</div>';
                                break;
                            case "1":
                                opciones = '<div class="btn-group-vertical" role="group">';
                                opciones += '<button type="button" style="margin:2px 2px 2px 2px;border: solid 1px;" class="btn btn-primary btn-sm" onClick="acepta('+ d.idproveedor +')" title="Ver información"><i class="fas fa-eye"></i></button>';

                                if (d.estatus != 3) {/** INICIO FECHA: 05-AGOSTO-2025 | AJUSTE CATALOGO PROVEEDORES | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                                    opciones += '<button type="button" style="margin:2px 2px 2px 2px;border: solid 1px;" class="btn btn-warning btn-editar btn-sm" title="Editar" data-value="PROCESO"><i class="fas fa-pencil-alt"></i></button>';
                                }/** FIN FECHA: 05-AGOSTO-2025 | AJUSTE CATALOGO PROVEEDORES | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */

                                opciones += '<button type="button" style="margin:2px 2px 2px 2px;border: solid 1px;" class="btn btn-danger btn-sm elimina_prov"  onClick="elimina_provedo('+d.idproveedor+')" title="Eliminar"><i class="fas fa-trash"></i></button>';
                                opciones += '<button type="button" style="margin:2px 2px 2px 2px; " class="btn btn-bloquear btn-sm" title="Bloquear"><i class="fas fa-ban"></i></button>';
                                opciones += '<button type="button" style="margin:2px 2px 2px 2px; " class="btn btn-primary btn-sm" title="Desbloquear" onclick="desbloquea_prov('+d.idproveedor+')"><i class="fas fa-unlock"></i></button>';
                                opciones += '</div>';
                                break;
                            case "9":
                                opciones = '<center><p style="font-size: .9em">Validando su documentación</p></center>';
                                opciones += '<div class="btn-group-vertical" role="group">';
                                opciones += '<button type="button" style="margin:2px 2px 2px 2px;border: solid 1px;" class="btn btn-danger btn-sm elimina_prov"  onClick="elimina_provedo('+d.idproveedor+')" title="Eliminar"><i class="fas fa-trash"></i></button>';
                                opciones += '</div>';
                                break;
                        }

                        return opciones;
                    },

                }
            ],
            columnDefs: [
                {
                    "searchable": false,
                    "orderable": false,
                    "targets": 0
                },
                {
                    targets: 7,
                    visible: false
                },
                {
                    targets: 9,
                    visible: false
                },
                {
                    targets: 12,
                    visible: false
                },
                {
                    targets: 13,
                    visible: false
                },
                {
                    targets: 14,
                    visible: false
                },
                {
                    targets: 15,
                    visible: false
                },
                {

                    orderable: false,
                    className: 'select-checkbox',
                    targets:   0,
                    'searchable':false,
                    'className': 'dt-body-center',
                    'render': function (d, type, full, meta){

                        if(full.alias==null||full.alias==""||full.cuenta==""||full.cuenta==null||full.cuenta==0||full.tipocta==null||full.tipocta==""||full.tipocta==0||full.idbanco==null||full.idbanco==""||full.idbanco==0){
                            return '';
                        }else{
                            return '<input type="checkbox" name="id[]" style="width:20px;height:20px;"  value="' + full.idproveedor + '">';
                        }     
                    },
                    select: {
                        style:    'os',
                        selector: 'td:first-child'
                    },
                }
            ],
            "ajax": {
                "url": url + "Provedores_cxp/ver_datosprovs",
                "type": "POST",
                cache: false,
            }
        });

        // CONDICIONAMOS COLUMNAS 13 Y 14 (DÍAS DE CREDITO Y MONTO DE CREDITO)
        if ( ['CI-COMPRAS', 'DIRECCION GENERAL'].includes(infoUserDepto) && ['CP', 'SU', 'DG'].includes(infoUserRol) || usuariosPermisosUnicos.includes(infoUserId) ) { /** FECHA: 10-MARZO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
            // 1ra Condición: SI EL ROL ES DE CUENTAS POR PAGAR, SUPER USUARIO O DIRECCION GENERAL Y SI EL DEPARTAMENTO ES CI-COMPRAS Y DIRECCION GENERAL SE MOSTRARA LA INFORMACION.
            // 2da Condición: SI EL USUARIO LOGEADO ESTA DENTRO DEL ARREGLO DE USUARIOS PERMISOS UNICOS /** FECHA: 10-MARZO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
            table_proceso.columns(10).visible(false);
            table_proceso.columns(11).visible(false);
            table_proceso.columns(13).visible(true);
            table_proceso.columns(14).visible(true);
        }

        if(usuariosPermisosUnicos.includes(infoUserId) ) { /** INICIO FECHA: 10-MARZO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
            // SI EL USUARIO LOGEADO ESTA DENTRO DEL ARREGLO DE USUARIOS PERMISOS UNICOS NO PUEDE VER ESTAS COLUMNAS
            table_proceso.columns(0).visible(false);
            table_proceso.columns(16).visible(false);
        } /** INICIO FECHA: 10-MARZO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/

        $("#tabla_historial").on( "click", ".btn-reactivar", function(){
            var row = table_proceso.row( $(this).closest('tr') ).data();
            $.post( url + "Provedores_cxp/reactivar_proveedor", { idproveedor : row.idproveedor } ).done( function(){
                table_proceso.ajax.reload();
            });
        });

        $("#tabla_historial").on( "click", ".btn-descargar", function(){
            var row = table_proceso.row( $(this).closest('tr') ).data();
            $.post( url + "Provedores_cxp/txtProveedores", { idproveedor : row.idproveedor } ).done( function(){
                $("#destxt").attr("href", url+"../UPLOADS/txtbancos/PROVEEDOR_"+ row.idproveedor+".txt");
                $("#destxt")[0].click();

            });
        });

        $( document ).on("click", ".elimina_prov", function(){
            $("#myModalProv").modal();
        });

        $("#tabla_historial").on( "click", ".btn-bloquear", function(){
            var row = table_proceso.row( $(this).closest('tr') ).data();

            $("#formulario_generico").html( '<h5>BLOQUEAR PROVEEDOR</h5>' );

            $("#formulario_generico").append( '<div class="row"><div class="col-lg-12 form-group"><label>OBSERVACIONES</label><textarea name="observacion" class="form-control" required></textarea></div></div>' );
            $("#formulario_generico").append( '<div class="row"><div class="col-lg-4 col-lg-offset-4"><button class="btn btn-success btn-block">ENVIAR</button></div></div>' );

            idproveedor = row.idproveedor;
            link_post = "Provedores_cxp/bloquear_proveedor";

            $("#modal_formulario_generico").modal();
        });
    });

    $("#tabla_proveedores_temporales").ready( function () {

        $('#tabla_proveedores_temporales thead tr:eq(0) th').each( function (i) {
            if( i > 0 && i < $('#tabla_proveedores_temporales thead tr:eq(0) th').length - 1 ){ /** FECHA: 05-AGOSTO-2025 | AJUSTE CATALOGO PROVEEDORES | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                var title = $(this).text();
                titulos_encabezado_temp.push(title);

                var esUsuarioEspecial = <?= json_encode(/** INICIO FECHA: 05-AGOSTO-2025 | AJUSTE CATALOGO PROVEEDORES | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                    in_array($this->session->userdata('inicio_sesion')['id'], [77, 257]) || 
                    ($this->session->userdata('inicio_sesion')['depto'] == 'ADMINISTRACION' && $this->session->userdata('inicio_sesion')['rol'] == 'CP')
                ) ?>;
                if (i !== 9 || esUsuarioEspecial) {
                    num_colum_encabezado_temp.push(i);
                }/** FIN FECHA: 05-AGOSTO-2025 | AJUSTE CATALOGO PROVEEDORES | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */

                $(this).empty();
                $(this)[0].insertAdjacentHTML("beforeend", `<div class="encabezado_tabla" contenteditable="true" id="enc_${title.match(/[a-zA-Z]+/g).join("").toLowerCase()}_${i}" placeholder="${title}"></div>` );

                $(this).find('div.encabezado_tabla').on('keydown', function(event) {
                    // Evitar la tecla "Enter" (código 13)
                    if (event.keyCode === 13) {
                        event.preventDefault();
                        return false;
                    }
                });


                $(this).find('div.encabezado_tabla').on('keyup change', function() {
                    if (table_proveedores_temporales.column(i).search() !== $(this).text().trim()) {
                        table_proveedores_temporales
                            .column(i)
                            .search($(this).text())
                            .draw();
                    }
                });
            }
        });

        table_proveedores_temporales = $('#tabla_proveedores_temporales').DataTable({
            dom: 'Brtip',
            "buttons": [
            {
                extend: 'excelHtml5',             
                text: '<i class="fas fa-file-excel"></i> EXPORTAR ',
                messageTop: "Lista de proveedores ",
                attr: {
                    class: 'btn btn-success',
                    style: 'margin-right: 1px;'
                },
                exportOptions: {
                    columns: num_colum_encabezado_temp,
                    format: {
                        header: function (data, columnIdx) { 
                            return " " + titulos_encabezado_temp[columnIdx-1] + " ";
                        }
                    }
                }
            },
            {
                text: '<i class="fas fa-file-download"></i> DESCARGAR TXT',
                action: function(){
                    var rows = $( table_proveedores_temporales.$('input[type="checkbox"]:checked')).map(function(){
                        return $(this).val();
                    }).get();

                    var consec2 = document.getElementById("consec2").value;
                    $.post( url + "Provedores_cxp/txtProveedores", {consec : consec2 ,idproveedor : JSON.stringify( rows ) } ).done( function( data ){

                        data = JSON.parse( data );
                        if( !data.resultado ){
                            $("#destxt").attr("href", data.file );
                            $("#destxt")[0].click();
                        }else{
                            alert(data.mensaje)
                        }             
                    });
                },
                attr: {
                    class: 'btn btn-info',
                }
            }
            ],
            "language" : lenguaje,
            "processing": false,
            "pageLength": 10,
            "bAutoWidth": false,
            "bLengthChange": false,
            "bInfo": false,
            "ordering": false,
            "searching": true,
            "scrollX": true,
            "columns": [
                { "width": "4%" },
                {
                    "data" : function( d ){
                        return '<p style="font-size: .9em">'+d.nombre+'</p>'
                    }
                },
                {
                    "data" : function( d ){
                        return '<p style="font-size: .9em">'+(d.alias ? d.alias : "") +'</p>'
                    }
                },
                {
                    "width": "8%",
                    "data" : function( d ){
                        return '<p style="font-size: .9em">'+(d.rfc ? d.rfc : "SD")+'</p>'
                    }
                },
                {  
                    "width": "8%",
                    "data" : function( d ){
                        if(d.cuenta==null||d.cuenta==''){
                            return '<center><small class="label pull-center bg-yellow">SIN CUENTA</small></center>'; /** FECHA: 05-AGOSTO-2025 | AJUSTE CATALOGO PROVEEDORES | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                        }
                        else{ 
                            return '<p style="font-size: .9em">'+d.cuenta+'</p>';
                        }
                    }
                },
                {  
                    "width": "8%",
                    "data" : function( d ){
                        if(d.nomba==null||d.nomba==''){
                            return '<center><small class="label pull-center bg-gray">SIN BANCO</small></center>'; /** FECHA: 05-AGOSTO-2025 | AJUSTE CATALOGO PROVEEDORES | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                        }
                        else{ 
                        return '<p style="font-size: .9em">'+d.nomba+'</p>';
                    }
                    }
                },

                {  
                    "width": "8%",
                    "data" : function( d ){
                        if(d.email==null||d.email==''){
                        return '<center><small class="label pull-center bg-teal">SIN CORREO</small></center>'; /** FECHA: 05-AGOSTO-2025 | AJUSTE CATALOGO PROVEEDORES | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */

                        }
                        else{ 
                        return '<p style="font-size: .9em">'+d.email+'</p>';
                    }
                    }
                },
                {
                    "width": "8%",
                    "data" : function( d ){
                        return '<p style="font-size: .9em">'+ ( d.fecha ? d.fecha : '' )+'</p>'
                    }
                },
                {
                    "width": "8%",
                    "data" : function( d ){/** INICIO FECHA: 05-AGOSTO-2025 | AJUSTE CATALOGO PROVEEDORES | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                        return `
                            <center>
                                <p style="font-size: .9em">
                                    ${d.tipo_proveedor}
                                </p>
                            </center>`;
                    }/** FIN FECHA: 05-AGOSTO-2025 | AJUSTE CATALOGO PROVEEDORES | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                }, 
                {
                    "data": function(d) { /** INICIO FECHA: 05-AGOSTO-2025 | AJUSTE CATALOGO PROVEEDORES | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                        return `<center>
                                <small class="label pull-center ${d.estatus == 2 || d.estatus == 5 ? 'bg-green' : 'bg-red'}">
                                    ${d.estatus == 2 || d.estatus == 5 ? "ACTIVO" : (d.estatus == 3 ? "BLOQUEADO" : '')}
                                </small>
                                ${d.estatus == 3 && d.fecha_bloqueo 
                                    ? `<br><label style="font-size: .8em;" class="text-danger">${d.fecha_bloqueo}</label>` 
                                    : ''
                                }
                            </center>
                        `;
                    } /** FIN FECHA: 05-AGOSTO-2025 | AJUSTE CATALOGO PROVEEDORES | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                },
                { 
                    "width": "15%",
                    "data": function(d) { /** INICIO FECHA: 05-AGOSTO-2025 | AJUSTE CATALOGO PROVEEDORES | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                        let opciones = `<div class="btn-group-vertical" role="group">
                            <button type="button" style="margin:2px 2px 2px 2px;" class="btn btn-primary btn-sm" onClick="acepta(${d.idproveedor})" title="Ver información">
                                <i class="fas fa-eye"></i>
                            </button>`;
                        
                        if (d.estatus != 3) {
                            opciones += `<button type="button" style="margin:2px 2px 2px 2px;" class="btn btn-warning fsd btn-sm" data-value="">
                                <i class="fas fa-pencil-alt" title="Editar"></i>
                            </button>`;
                        }
                        
                        opciones += `</div>`;
                        return opciones;
                    }, /** FIN FECHA: 05-AGOSTO-2025 | AJUSTE CATALOGO PROVEEDORES | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                    "orderable": false
                }
            ],
            columnDefs: [
                {
                    "searchable": false,
                    "orderable": false,
                    "targets": 0
                },
                {
                    orderable: false,
                    className: 'select-checkbox',
                    targets:   0,
                    'searchable':false,
                    'className': 'dt-body-center',
                    'render': function (d, type, full, meta){
                        if(full.alias==null||full.alias==""||full.cuenta==""||full.cuenta==null||full.cuenta==0||full.tipocta==null||full.tipocta==""||full.tipocta==0||full.idbanco==null||full.idbanco==""||full.idbanco==0){
                            return '';
                        }else{
                            return '<input type="checkbox" name="id[]" style="width:20px;height:20px;"  value="' + full.idproveedor + '">';
                        }     
                    },
                    select: {
                        style:    'os',
                        selector: 'td:first-child'
                    },
                },
                {/** INICIO FECHA: 05-AGOSTO-2025 | AJUSTE CATALOGO PROVEEDORES | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                    "searchable": <?= in_array($this->session->userdata('inicio_sesion')['id'], [77, 257])  || ( $this->session->userdata('inicio_sesion')['depto'] == 'ADMINISTRACION' && $this->session->userdata('inicio_sesion')['rol'] == 'CP') ? 'true' : 'false'?>,
                    "orderable": false,
                    "visible": <?= in_array($this->session->userdata('inicio_sesion')['id'], [77, 257])  || ( $this->session->userdata('inicio_sesion')['depto'] == 'ADMINISTRACION' && $this->session->userdata('inicio_sesion')['rol'] == 'CP') ? 'true' : 'false'?>,
                    "targets": 9
                },/** FIN FECHA: 05-AGOSTO-2025 | AJUSTE CATALOGO PROVEEDORES | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
            ]
        });        

        $("#tabla_proveedores_temporales").on( "click", ".btn-descargar", function(){
            var row = table_proveedores_temporales.row( $(this).closest('tr') ).data();
            $.post( url + "Provedores_cxp/txtProveedores", { idproveedor : row.idproveedor } ).done( function(){
                $("#destxt").attr("href", url+"../UPLOADS/txtbancos/PROVEEDOR_"+ row.idproveedor+".txt");
                $("#destxt")[0].click();

            });
        });
    });

    $("#tipocta").change(function(){
        switch ($(this).val()){
            case '1':
                $("#cuenta").attr('minlength','7');
                $("#cuenta").attr('maxlength','12');
            break;
            case '3':
                $("#cuenta").attr('minlength','16');
                $("#cuenta").attr('maxlength','16');
            break;
            case '40':
                $("#cuenta").attr('minlength','18');
                $("#cuenta").attr('maxlength','18');
            break;
            default:
                $("#cuenta").attr('minlength','0');
                $("#cuenta").attr('maxlength','0');
            break;
        }
        $('input#cuenta').val("");
    });
    
    $("#tipoctaA").change(function(){
        switch ($(this).val()){
            case '1':
                $("#cuentaA").attr('minlength','7');
                $("#cuentaA").attr('maxlength','12');
            break;
            case '3':
                $("#cuentaA").attr('minlength','16');
                $("#cuentaA").attr('maxlength','16');
            break;
            case '40':
                $("#cuentaA").attr('minlength','18');
                $("#cuentaA").attr('maxlength','18');
            break;
            default:
                $("#cuentaA").attr('minlength','0');
                $("#cuentaA").attr('maxlength','0');
            break;
        }
        $('input#cuenta').val("");
    });    

    $("#tabla_historial, #tabla_proveedores_temporales").on( "click", ".btn-editar, .fsd", function( event ){

        tr = $(this).closest('tr');
        tabla_update = $( this ).data("value");
        if( tabla_update == "PROCESO" ){
            var row = table_proceso.row( tr );
            $("input[name='rfcA']").val( row.data().rfc );
            if (['CI-COMPRAS', 'DIRECCION GENERAL'].includes(infoUserDepto) && ['CP', 'SU', 'DG'].includes(infoUserRol)) {
                $("#datos_credito").show();
            }
        }else{
            var row = table_proveedores_temporales.row( tr );
            $("input[name='rfcA']").val( "" );
            $("#datos_credito").hide();
        }
        //Inicio
        if (row.data().estatus != 1) {
            var valueToHide = '3';
            var selectElement = document.getElementById('excepcion_factura_edita');

            for (var i = 0; i < selectElement.options.length; i++) {
                var option = selectElement.options[i];
                if (option.value === valueToHide) {
                    option.style.display = 'none';
                }
            }
        } else {
            var valueToShow = '3';
            var selectElement = document.getElementById('excepcion_factura_edita');

            for (var i = 0; i < selectElement.options.length; i++) {
                var option = selectElement.options[i];
                if (option.value === valueToShow) {
                    option.style.display = '';
                }
            }
        }//Fin se agrego este condicional que solo pernmite existe el value de INVOICE cuando uno entra a proveedores actuales
        idproveedor = row.data().idproveedor

        $("input[name='nombreActual']").val( row.data().nombre);
        $("input[name='contactoA']").val( row.data().contacto );
        $("input[name='emailA']").val( row.data().email );
        $("#tipoctaA option[value='"+row.data().tipocta+"']").prop("selected", true);
        $("#idbancoA option[value='"+row.data().idbanco+"']").prop("selected", true);
        $("input[name='cuentaA']").val( row.data().cuenta);
        $("#idsucursalA option[value='"+row.data().estado+"']").prop("selected", true);
        $("#tipoprovA option[value='"+row.data().tipo_prov+"']").prop("selected", true);
        $("#idusuarioA option[value='"+row.data().idusuario+"']").prop("selected", true);
        $("#idusuarioA").prop("disabled", row.data().idusuari ? false : true);
        $("#excepcion_factura_edita option").prop("selected", false);
        $("#excepcion_factura_edita option[value='"+row.data().excp+"']").prop("selected", true);
        $("input[name='dias_credito']").val( row.data().dias_credito);
        $("input[name='monto_credito']").val((row.data().monto_credito ? '$'+row.data().monto_credito : ""));
        $("#proveedor_form #clase_prov").val( row.data().estatus );
        $("#proveedor_form #cuenta_nomina").val( row.data().honorarios );
    
        $("#proveedor_servicio_edit").prop(check, true)
        row.data().prov_serv === "1" ? $("#proveedor_servicio_edit").prop('checked', true) : $("#proveedor_servicio_edit").prop('checked', false);

        link_post = 'Provedores_cxp/updateProveedor';

        tabla_update = $(this).data("value");
        $("#modalUpdate").modal();

    });

    $('#proveedor_form').submit(function(e) {
        e.preventDefault();
        }).validate({
        submitHandler: function( form, event ) {
            // Quitamos el simbolo de pesos "$"
            $('#monto_credito').val($('#monto_credito').val().replace(/[$,]/g, ''));
            var data = new FormData( $(form)[0] );
            data.append("idproveedor", idproveedor);
            var inputNombre = event.target[1];
            var valor = inputNombre.value;

            if(inputNombre.value[inputNombre.value.length-1]==' '){
                //inputNombre.value = valor.slice(0, -1); //Borra el espacio al final del nombre
                alert("HAY ESPACIO AL FINAL DEL NOMBRE, FAVOR DE VERIFICAR");
            }else{
                $.ajax({
                    url : url + link_post,
                    data: data,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    method: 'POST',
                    type: 'POST',
                    success: function(data){

                        if( !data[0] ){
                            alert("NO SE HA PODIDO COMPLETAR LA SOLICITUD");
                        }
                        $("#modalUpdate").modal( 'toggle' );
                        
                        if( tabla_update == 'PROCESO' ){
                            table_proceso.ajax.reload();
                        }else{
                            table_proveedores_temporales.ajax.reload();
                        }
                        
                    },error: function( ){
                        
                    }
                });
            }
        }
    });

    $('#formulario_generico').submit(function(e) {
        e.preventDefault();}).validate({
            submitHandler: function( form ) {
                var data = new FormData( $(form)[0] );
                data.append("idproveedor", idproveedor);
                $.ajax({
                    url : url + link_post,
                    data: data,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    method: 'POST',
                    type: 'POST',
                    success: function(data){

                        $("#modal_formulario_generico").modal( 'toggle' );
                        table_proceso.ajax.reload();

                    },error: function( ){
                    }
                });
            }
    }); 

    $('#fomNuevoProv').submit(function(e) {e.preventDefault();}).validate({
        submitHandler: function( form, event ) {
            var inputNombre = event.target[2];
            var valor = inputNombre.value;      
            if(inputNombre.value[inputNombre.value.length-1]==' '){
                //inputNombre.value = valor.slice(0, -1); //Borra el espacio al final del nombre
                alert("HAY ESPACIO AL FINAL DEL NOMBRE, FAVOR DE VERIFICAR");
            }else{
                var data = new FormData( $(form)[0] );
                $.ajax({
                    url : url + "/Provedores_cxp/registrar_nuevo_proveedor",
                    data: data,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    method: 'POST',
                    type: 'POST',
                    success: function(data){
                        alert("El proveedor ha sido guardado correctamente");
                        $('#fomNuevoProv')[0].reset();
                    },
                    error: function(data ){
                    }
                });
            }
        }
    });

    function acepta(idp) {

        $("#myModal .modal-title").html("");
        $("#myModal .modal-body").html("");

        $.getJSON(url+"Provedores_cxp/ver_datosprovs2/"+idp, function( value ){

            value = value[0];
            $('#myModal').modal({backdrop: 'static', keyboard: false});
            $("#myModal").modal();

            $("#myModal .modal-header").append("<h4 class='modal-title'>NOMBRE: <b>"+ value.nomp +"</b></h4>");
            
            /*-------*/
            $("#myModal .modal-body").append('<div class="row"><div class="col-lg-6"><h5><b>RFC:</b> '+value.rfc+'</h5></div><div class="col-lg-6"><h5><b>CONTACTO:</b> '+value.contacto+'</h5></div></div>');
            /*-------*/
            tipo_cuenta = "";
            if (value.tipocta==1) {
                tipo_cuenta = '<h5><label>TIPO:</label><br>CUENTA BANBAJIO</h5>';
            }

            if (value.tipocta==3) {
                tipo_cuenta = '<h5><label>TIPO:</label><br>TARJETA</h5>';
            }
            
            if (value.tipocta==40) {
                tipo_cuenta = '<h5><label>TIPO:</label><br>CLABE</h5>';
            }

            $("#myModal .modal-body").append('<div class="row"><div class="col-lg-6"><h5><b>Email:</b> '+value.email+'</h5></div><div class="col-lg-6"><h5><b>SUCURSAL:</b> '+value.estado+'</h5></div></div>');
            /*-------*/
            $("#myModal .modal-body").append('<div class="row"><div class="col-md-3"><h5><label>BANCO:</label><br>'+value.nomba+'</h5></div><div class="col-md-3">'+tipo_cuenta+'</div><div class="col-md-6"><h5><label>No CUENTA:</label><br>'+value.cuenta+'</h5></div></div>');
            
            tipo_proveedor = "";

            switch( value.tipo_prov  ){
                case "0":
                    tipo_proveedor = "EXTERNO";
                    break;
                case "1":
                    tipo_proveedor = "INTERNO";
                    break;
                case "2":
                    tipo_proveedor = "EMPRESA";
                    break;
                case "3":
                    tipo_proveedor = "IMPUESTO";
                    break;
            }
            
            $("#myModal .modal-body").append('<div class="row"><div class="col-lg-6"><h5><b>FECHA REGISTRO:</b> '+value.fecadd+'</h5></div><div class="col-lg-6"><h5><b>TIPO PROVEEDOR:</b> '+tipo_proveedor+'</h5></div></div>');             
        });   
    } 

    $("#cerrar_mymodal").click( function(){
        $("#myModal").modal('toggle');
    });


    function cancela2(){
        $("#myModal12").modal('toggle');
    } 

    function mayus(e) {
        e.value = e.value.toUpperCase();
    }

    function check(e) {
        tecla = (document.all) ? e.keyCode : e.which;
        if (tecla == 8) {
            return true;
        }
        else{
            var inputNombre = e.target;
            patron = /[A-Na-nO-Zo-z0-9 ]/;
            if(inputNombre.value.length === 0)
                patron = /[A-Na-nO-Zo-z0-9]/;
            tecla_final = String.fromCharCode(tecla);
            return patron.test(tecla_final);
        }
    }

    $('select#tipoctaA').on('change',function(){
        document.getElementById("cuentaA").value = "";
    });

    $('input#cuentaA').keypress(function (event) {
        var combo = document.getElementById("tipoctaA");
        var selected = combo.options[combo.selectedIndex].value;

        if (selected==="") { 
            document.getElementById("cuentaA").setAttribute("minlength", "0");

            if (event.which < 48 || event.which > 57 || this.value.length === 0) {
                return false;
            }

        }

        if (selected==="1") { 
            document.getElementById("cuentaA").setAttribute("minlength", "12");

            if (event.which < 48 || event.which > 57 || this.value.length === 12) {
                return false;
            }

        }
        if (selected==="3") { 
            document.getElementById("cuentaA").setAttribute("minlength", "16");
            if (event.which < 48 || event.which > 57 || this.value.length === 16) {
                return false;
            }
        }
        if (selected==="40") { 
            document.getElementById("cuentaA").setAttribute("minlength", "18");
            if (event.which < 48 || event.which > 57 || this.value.length === 18) {
                return false;
            }
        }
    });

    function elimina_provedo(idproveedor) {
        $("#myModalProv .modal-footer").html("");
        $("#myModalProv .modal-body").html("");
        $("#myModalProv ").modal();

        $.getJSON(url+"Provedores_cxp/get_provs3/"+idproveedor).done( function( data ){
            $.each( data, function( i, v){
            $("#myModalProv .modal-body").append("<div class='form-group col-lg-12'>¿Está seguro de eliminar al proveedor? <br><br><b>"+v.nombre+"</b><input type='hidden' id='valor_prov' value='"+v.idproveedor+"' ></div>");
            $("#myModalProv .modal-footer").append("<center> <div class='btn-group'><button type='button' style='margin-right:20px;' class='btn btn-success' onclick='eliminar_proveedor_OK("+v.idproveedor+")'>Eliminar</button><button type='button' class='btn btn-danger' onclick='cerrareliminar()'>Cancelar</button></div></center>");
        });

        });
    }
    
    function cerrareliminar(){
        $("#myModalProv").modal('toggle');
    } 

    function eliminar_proveedor_OK(idproveedor){

        $.post( url + "Provedores_cxp/eliminar_proveedor/"+idproveedor)
        .done(function(response) { /** INICIO FECHA: 28-MAYO-2025 | ELIMINAR PROVEEDOR RECHAZADO | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
            let res = JSON.parse(response);

            if (res === true || res.success) {
                swal({
                title: "¡ÉXITO!",
                text: "SE HA REALIZADO EL MOVIMIENTO CORRECTAMENTE",
                icon: "success",
                buttons: false,
                timer: 3000
                }).then(() => {
                    table_proceso.ajax.reload();
                    $("#myModalProv").modal('toggle');
                });
            } else {
                alert("Ha ocurrido un error");
            } /** FIN FECHA: 28-MAYO-2025 | ELIMINAR PROVEEDOR RECHAZADO | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
        });
    } 

    function desbloquea_prov(id){
        if(confirm("¿Deseas desbloquear el PROVEEDOR seleccionado para cargar facturas?")){
            $.post(
                "Provedores_cxp/desbloquea_prov",
                { idproveedor: id},
                function(data) {
                    var response = jQuery.parseJSON(data);
                    if(response.resultado){
                        alert("El proveedor ha sido desbloqueado correctamente");
                        //table_proceso.ajax.reload();
                    }else
                        alert("Ha ocurrido un error");
                }
            );
        }
    }
    $(window).resize(function(){
        table_proceso.clear().draw();
        table_proceso.rows.add(reziseInfo);
        table_proceso.columns.adjust().draw();
    });

    function formatearMoneda(input) {
        
        let posicion = input.selectionStart; // Guarda la posición actual del cursor
        let valor = input.value.replace(/[^0-9.]/g, ''); // Permite solo números y punto decimal
        let partes = valor.split('.');

        if (partes.length > 2) {
            valor = partes[0] + '.' + partes.slice(1).join('');
        }

        if (valor) {
            let numero = parseFloat(valor);
            if (!isNaN(numero)) {
                let valorFormateado = numero.toLocaleString('es-MX', { style: 'currency', currency: 'MXN' });
                input.value = valorFormateado;
            }
        }

        // Restaurar la posición del cursor después del cambio
        setTimeout(() => {
            input.selectionStart = input.selectionEnd = posicion;
        }, 0);
    }

    // Ejemplo usando SheetJS (xlsx)
    document.getElementById('subir_excel_prov').addEventListener('change', function(e) {
        // Obtener el archivo seleccionado
        var file = e.target.files[0];

        // Verificar si se seleccionó un archivo
        // if (!file) {
        //     document.getElementById('status').textContent = 'No se seleccionó ningún archivo.';
        //     return;
        // }

        // // Mostrar mensaje de estado
        // document.getElementById('status').textContent = 'Procesando archivo...';

        // Crear un FileReader para leer el archivo
        var reader = new FileReader();

        // Definir qué hacer cuando el archivo se haya leído
        reader.onload = function (e) {
            // Convertir el archivo a un ArrayBuffer
            var data = new Uint8Array(e.target.result);

            // Leer el archivo Excel usando SheetJS
            var workbook = XLSX.read(data, { type: 'array' });

            // Obtener la primera hoja del archivo
            var firstSheetName = workbook.SheetNames[0];
            var firstSheet = workbook.Sheets[firstSheetName];

            // Convertir la hoja a JSON (array de arrays)
            var jsonData = XLSX.utils.sheet_to_json(firstSheet, { header: 1 });

            // Enviar los datos al backend usando AJAX
            $.ajax({
                url: 'Provedores_cxp/actualizarProvExcel', // URL de tu script PHP
                method: 'POST',
                data: { excelData: JSON.stringify(jsonData) }, // Enviar los datos como JSON
                success: function (response) {
                    var result = JSON.parse(response);

                    alert(result.msg);

                    table_proceso.ajax.reload();
                },
                error: function (xhr, status, error) {
                    // Manejar errores
                    // document.getElementById('status').textContent = 'Error al procesar el archivo.';
                    console.error('Error:', error);

                    // Resetear el input de archivo en caso de error
                    e.target.value = ''; // Esto resetea el input
                },
                complete: function () {
                    // Resetear el input de archivo después de que la solicitud AJAX se complete
                    $('#subir_excel_prov').val("");
                }
            });
        };

        // Leer el archivo como ArrayBuffer
        reader.readAsArrayBuffer(file);
    });

    /** INICIO FECHA: 05-AGOSTO-2025 | AJUSTE CATALOGO PROVEEDORES | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
    $(window).resize(function(){
        table_proveedores_temporales.columns.adjust().draw(false);
        table_proceso.columns.adjust().draw(false);
    });

    $('.sidebar-toggle').click(function() {
        setTimeout(function() {
            // 1. Ajustar columnas
            table_proveedores_temporales.columns.adjust();
            table_proceso.columns.adjust();
            // 2. Forzar actualización de encabezados
            var headerCellsTableProveedorTemporales = $('#tabla_proveedores_temporales thead th');
            headerCellsTableProveedorTemporales.each(function() {
                var newWidth = $(this).width(); // Obtener ancho actualizado
                $(this).css('width', newWidth + 'px'); // Aplicarlo
            });
            var headerCellsTableProceso = $('#tabla_historial thead th');
            headerCellsTableProceso.each(function() {
                var newWidth = $(this).width(); // Obtener ancho actualizado
                $(this).css('width', newWidth + 'px'); // Aplicarlo
            });
            // 3. Redibujar (sin resetear paginación)
            table_proveedores_temporales.draw(false);
            table_proceso.draw(false);
        }, 300); // Esperar a que termine la animación del sidebar
    }); /** FIN FECHA: 05-AGOSTO-2025 | AJUSTE CATALOGO PROVEEDORES | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
</script>

<?php
    require("footer.php");
?>

