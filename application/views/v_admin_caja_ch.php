<?php
    require("head.php");
    require("menu_navegador.php");
?>
<style>
    .btn-azure {
        box-shadow: none;
        color: #2874A6;
        background-color: transparent;
        border-radius: 27px;
        margin: 0;
    }

    .btn-azure:hover {
        background-color: #2874A6;
        color: #ffffff;
    }
    
    .btn-delete {
        box-shadow: none;
        color: #d73925;
        background-color: transparent;
        border-radius: 27px;
        margin: 0;
    }
    .btn-delete:hover{
        background-color: #d73925;
        color: #ffffff;
    }
    .tooltip {
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
    }
    .badge-custom {
        background-color: red; /* Color de fondo */
        color: white; /* Color del texto */
        font-size: 10px; /* Tamaño de la fuente */
        padding: 2px 5px; /* Relleno */
        border-radius: 50%; /* Borde redondeado */
        position: absolute; /* Posición */
        top: 5px; /* Ajuste superior */
        right: 5px; /* Ajuste derecho */
        transform: translate(50%, -50%); /* Mover ligeramente la insignia */
        display: flex;
        align-items: center;
        justify-content: center;
        width: 15px;
        height: 15px;
        line-height: 15px; /* Alineación del texto en el centro */
    }

    .popover {
      max-width: 276px;
      padding: 1px;
      font-size: 14px;
      font-weight: normal;
      line-height: 1.42857143;
      color: #333;
      text-align: left;
      background-color: #FCF8E3;
      border: 2px solid rgba(250, 235, 204, 1);
      border-radius: 6px;
      -webkit-box-shadow: 0 5px 10px rgba(0,0,0,.3);
              box-shadow: 0 5px 10px rgba(0,0,0,.3);
    }
    .popover-icon {
        cursor: help;
    }
    .popover.bottom>.arrow {
      border-bottom-color: #FCF8E3;
    }
    .popover.bottom>.arrow:after {
      border-bottom-color: #FCF8E3;
    }
    .popover-title {
        font-weight: bold;
        background-color: #FCF8E3;
    }
    .popover-content {
        font-size: 12px;
        font-family: Arial, Helvetica, sans-serif;
        background-color: #FCFCFC;
    }

</style>
<div class="container-fluid">
    <div class="row justify-content-lg-center">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3>CAJAS CHICAS <button data-toggle="tooltip" data-placement="right" title="Actualizar tabla" class="btn btn-link btn-sm" id="refreshButton" onclick="recargar()"><i class="fa fa-refresh" style="font-size: 12px;" aria-hidden="true"></i></button></h3> <!-- /** FECHA: 29-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/ -->
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="col-lg-3 col-xs-10 col-md-3" id="add_btn_caja"></div> <!-- /** FECHA: 29-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/ -->
                            <table class="table table-striped" id="tablacajas">
                                <thead>
                                    <tr>
                                        <th></th>                                               <!-- COLUMNA [0] NO VISIBLE EN EXCEL --> <!-- /** INICIO FECHA: 29-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/ -->
                                        <th style="font-size: .9em"># CAJA</th>                 <!-- COLUMNA [1] -->
                                        <th style="font-size: .9em">RESPONSABLE</th>            <!-- COLUMNA [2] -->
                                        <th style="font-size: .9em">REMBOLSAR A</th>            <!-- COLUMNA [3] -->
                                        <th style="font-size: .9em">DEPARTAMENTO(S)</th>        <!-- COLUMNA [4] -->
                                        <th style="font-size: .9em">EMPRESA(s)</th>             <!-- COLUMNA [5] -->
                                        <th style="font-size: .9em">MONTO</th>                  <!-- COLUMNA [6] -->
                                        <th style="font-size: .9em">USUARIO</th>                <!-- COLUMNA [7] -->
                                        <th style="font-size: .9em">TOTAL REEMBOLSO</th>        <!-- COLUMNA [8] -->
                                        <th style="font-size: .9em">TOTAL CIERRE</th>           <!-- COLUMNA [9] -->
                                        <th style="font-size: .9em">COMENTARIO CIERRE</th>      <!-- COLUMNA [10] NO VISIBLE EN TABLA, SI EN EXCEL-->
                                        <th style="font-size: .9em">ESTATUS</th>                <!-- COLUMNA [11] -->
                                        <th style="font-size: .9em"></th>                       <!-- COLUMNA [12] --> <!-- /** FIN FECHA: 29-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/ -->
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

<!-- Agregar Nueva Caja Chica -->
<div class="modal fade"  id="modal_add_caja" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close clse" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="titulo-modal"></h4>
            </div>
            <div class="modal-body">
                <form id="cajach_form">
                    <div class="row">
                        <div class="col-lg-12 form-group">
                            <h5><b>RESPONSABLE</b></h5>
                            <select class="nombre" id="nombre" name="nombre" required placeholder="---Seleccione una opción---" autocomplete="off"></select>
                            <input type="hidden" class="form-control" id="idcontrato" name="idcontrato"/>
                            <small class="text-danger"></small>
                        </div>
                    </div>
                    <!-- FECHA : 25-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | S e crea el div en que se mostrara el select al editar
                     siempre y cuando el campo nombre_reembolso_ch sea NULL-->
                    <div id="userColaboradorEdit" class="row">
                    </div>
                    <div class="row">
                        <div class="col-lg-12 form-group">
                            <h5><b>DEPARTAMENTO</b></h5>
                            <select class="form-control depto" id="depto" name="depto[]" required multiple="multiple"></select>
                            <small class="text-danger"></small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 form-group">
                            <h5><b>EMPRESA</b></h5>
                            <select class="form-control empresa" id="empresa" name="empresa[]" required multiple="multiple"></select>
                            <small class="text-danger"></small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 form-group">
                            <h5><b>CANTIDAD</b></h5>
                            <div class="input-group">
                                <span class="input-group-addon">$</span>
                                <input type="text" class="form-control" id="monto" name="monto" required placeholder="0" />
                            </div>
                            <small class="text-danger"></small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 form-group">
                            <h5><b>OBSERVACIÓN</b></h5>
                            <textarea class="form-control" id="observacionFill" name="observacion" spellcheck="true" maxlength="250" style="min-height: 100px; width: 100%; max-width: 100%; min-width: 100%;" required></textarea>
                            <small class="text-danger"></small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12" style="display: flex; justify-content: flex-end;">
                            <button type="button" class="btn btn-danger clse" data-dismiss="modal" style="margin-right: 10px;"><i class="fas fa-times"></i> CERRAR</button>
                            <button class="btn btn-success" type="submit">GUARDAR</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Asignar Usuario  -->
<div class="modal fade" id="asignar_user" role="dialog" >
    <div class="modal-dialog modal-lg" >
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close cls_adduser" data-dismiss="modal" >&times;</button>
                <h4 class="modal-title" >ASIGNAR A QUIEN SE REMBOLSARA</h4>
            </div>
            <div class="modal-body">
                <form id="adduser_form" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-lg-12 form-group">
                            <h5><b>USUARIO</b>&nbsp;<span class="text-sm">(Responsable)</span></h5>
                            <select class="user" id="user" name="user" required placeholder="---Seleccione una opción---" autocomplete="off"></select>
                            <small class="text-danger"></small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 form-group">
                            <h5><b>REEMBOLSAR A</b></h5>
                            <select class="idreembolso_ch" id="idreembolso_ch" name="idreembolso_ch" required placeholder="---Seleccione una opción---" autocomplete="off"></select>
                            <input type="hidden" class="nombre_reembolso_ch" name="nombre_reembolso_ch" id="nombre_reembolso_ch">
                            <small class="text-danger"></small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 form-group">
                            <h5><b>DOCUMENTO DE AUTORIZACIÓN</b></h5>
                            <input type="file" name="usrfile" id="usrfile" class="form-control" accept="application/pdf" required>
                            <small class="text-danger"></small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12" style="display: flex; justify-content: flex-end;">
                            <button type="button" class="btn btn-danger clse" data-dismiss="modal" style="margin-right: 10px;"><i class="fas fa-times"></i> CERRAR</button>
                            <button class="btn btn-success" type="submit">GUARDAR</button>
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
                <h4 class="modal-title" id="confirmTittle">¡ADVERTENCIA!</h4>
            </div>
            <div class="modal-body confirm-body" id="confirm-body" style="width: 430px;">
                <h4><p>Al realizar esta acción no podrá revertirla.</p></h4>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger clse" data-dismiss="modal"><i class="fas fa-times"></i> CERRAR</button>
                <button class="btn btn-success" onclick="peticion()">ACEPTAR</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Cerrar Caja  -->
<div class="modal fade" id="cerrar_caja" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close cls_ccja" data-dismiss="modal" aria-label="Close">&times;</button>
                <h4 class="modal-title" id="titulo-modal-cerrar"></h4>
            </div>
            <div class="modal-body"  style="max-width: 900px;">
                <form id="crrcaja_form" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-lg-12 form-group" id="groupDocuments">
                            <h5><b>FICHA DE DEPÓSITO</b></h5>
                            <div class="col-lg-12" style="padding: 0px;" id="document1">
                                <div class="form-group col-lg-6" style="padding: 0 15px;">
                                    <h5><b>ARCHIVO</b></h5>
                                    <input type="file" name="crrfile1" id="crrfile1" class="form-control" accept="application/pdf" required>
                                    <small class="text-danger"></small>
                                </div>
                                <div class="form-group col-lg-6" style="padding: 0 15px;">
                                    <h5><b>RENOMBRAR ARCHIVO</b></h5>
                                    <input type="text" name="archivo1" id="archivo1" class="form-control" autocomplete="off" maxlength="50" minlength="1" required>
                                    <small class="text-danger"></small>
                                </div>
                            </div>
                            <div class="col-lg-12 form-group" style="padding: 0 15px;">
                                <h5><b>OBSERVACIÓN</b></h5>
                                <textarea name="observacion_crrfile1" id="observacion_crrfile1" class="form-control" spellcheck="true" maxlength="250" style="min-height: 100px; width: 100%; max-width: 100%; min-width: 100%;" required></textarea>
                                <small class="text-danger"></small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12" style="display: flex; justify-content: flex-end;">
                            <button type="button" class="btn btn-danger clse" data-dismiss="modal" style="margin-right: 10px;"><i class="fas fa-times"></i> CERRAR</button>
                            <button class="btn btn-success" type="submit">GUARDAR</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Editar Documento  -->
<div class="modal fade" id="modal_edit_doc" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close cls_edoc" data-dismiss="modal" aria-label="Close">&times;</button>
                <h4 class="modal-title" >EDITAR DOCUMENTO</h4>
            </div>
            <div class="modal-body">
                <form id="edoc_form" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-lg-12 form-group form-group">
                            <h5><b>NOMBRE DEL DOCUMENTO</b></h5>
                            <input type="text" class="form-control" id="ndocname" name="ndocname"
                                oninput="this.value = this.value.toUpperCase();" 
                                spellcheck="true" 
                                required
                            />
                            <small class="text-danger"></small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 form-group" id="motivo_doc">
                            <h5><b>MOTIVO</b></h5>
                            <select class="form-control motivo_documento" id="motivo_documento" name="motivo_documento" required onchange="toggleInputFile(this.value)">
                                <option value="">--- Seleccione una Opción ---</option>
                                <?php foreach ($tipo_motivos as $motivo): ?>
                                    <option value="<?php echo $motivo['idMotivo']; ?>"><?php echo $motivo['motivo']; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <small class="text-danger"></small>
                        </div>
                        <div class="col-lg-6 form-group" id="inputFile">
                            <h5><b>ARCHIVO</b></h5>
                            <input type="file" name="crrfile3" id="crrfile3" class="form-control" accept="application/pdf">
                            <small class="text-danger"></small>
                        </div>
                    </div>
                    <div class="row"> 
                        <div class="col-lg-12 form-group form-group">
                            <h5><b>OBSERVACIÓN</b></h5>
                            <textarea class="form-control" id="observacionAutorizacionFill" name="observacion" rows="5" spellcheck="true" maxlength="250" style="min-height: 100px; width: 100%; max-width: 100%; min-width: 100%;" required></textarea>
                            <small class="text-danger"></small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12" style="display: flex; justify-content: flex-end;">
                            <button type="button" class="btn btn-danger clse" data-dismiss="modal" style="margin-right: 10px;"><i class="fas fa-times"></i> CERRAR</button>
                            <button class="btn btn-warning" >SIGUIENTE</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal documentos  -->
<div class="modal fade" id="docs" role="dialog" style="z-index: 1100;">
    <div class="modal-dialog modal-dialog-centered" role="document"> <!-- Añadido modal-dialog-centered para centrar el modal verticalmente -->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
                <h5><b id="title-docs"></b></h5>
                <h6><b id="usuario"></b></h6> <!-- Agrego renglon con id "usuario" -->
            </div>
            <div class="modal-body" id="detalle">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger clse" data-dismiss="modal">
                    <i class="fas fa-times"></i> CERRAR
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal historialGeneral  -->
<div class="modal fade modal" id="historialGeneral" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
                <h4 class="modal-title" id="exampleModalLabel">HISTORIAL GENERAL</h4>
            </div>
            <div class="modal-body">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li id="empresasTab"><a id="empresas-tab" data-toggle="tab" href="#data-empresas">EMPRESAS</a></li>
                        <li id="documentosTab"><a id="documentos-tab" data-toggle="tab" href="#data-documentos">DOCUMENTOS</a></li>
                    </ul>
                </div>
                <div class="tab-content">
                    <div id="data-empresas" class="tab-pane fade"></div>
                    <div id="data-documentos" class="tab-pane fade"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger clse" data-dismiss="modal"><i class="fas fa-times"></i> CERRAR</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Eliminar Documento  -->
<div class="modal fade" id="modal_delete_doc" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close cls_edoc" data-dismiss="modal" aria-label="Close">&times;</button>
                <h4 class="modal-title" >ELIMINAR DOCUMENTO</h4>
            </div>
            <div class="modal-body">
                <form id="eliminardoc_form">
                    <div class="row">
                        <div class="col-lg-12 form-group">
                            <h5><b>NOMBRE DEL DOCUMENTO A ELIMINAR</b></h5> <!-- FECHA: 26-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                            <input type="text" class="form-control" id="docEliminarName" name="docEliminarName" readonly disabled/>   
                            <small class="text-danger"></small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 form-group">
                            <h5><b>MOTIVO</b></h5>
                            <select class="form-control motivo_documento_delete" id="motivo_documento_delete" name="motivo_documento_delete" required>
                                <option value="">--- Seleccione una Opción ---</option>
                                <?php foreach ($tipo_motivos as $motivo): ?>
                                    <?php if ($motivo['idMotivo'] !== 3) {?>
                                        <option value="<?php echo $motivo['idMotivo']; ?>"><?php echo $motivo['motivo']; ?></option>
                                    <?php } ?>
                                <?php endforeach; ?>
                            </select>
                            <small class="text-danger"></small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 form-group">
                            <h5><b>OBSERVACIÓN</b></h5>
                            <textarea class="form-control" id="observacionEliminarFill" name="observacion" spellcheck="true" maxlength="250" style="min-height: 100px; width: 100%; max-width: 100%; min-width: 100%;" required></textarea>
                            <small class="text-danger"></small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12" style="display: flex; justify-content: flex-end;">
                            <button type="button" class="btn btn-danger clse" data-dismiss="modal" style="margin-right: 10px;"><i class="fas fa-times"></i> CERRAR</button>
                            <button class="btn btn-warning" >SIGUIENTE</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Agregar Documento  --> <!-- INICIO FECHA: 29-ABRIL-2024 
    IGNORAR: YA ESTABA, SOLO QUE SE HABIA ELIMINADO DE MI RAMA ANTERIORMENTE
| @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
<div class="modal fade" id="modal_add_doc" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close cls_addoc" data-dismiss="modal" aria-label="Close">&times;</button>
                <h4 class="modal-title" >AGREGAR DOCUMENTO</h4>
            </div>
            <div class="modal-body modal-lg">
                <form id="addoc_form" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-lg-6 form-group">
                            <h5><b>NOMBRE DEL DOCUMENTO</b></h5>
                            <input type="text" class="form-control" id="docname" name="docname" required/>
                            <small class="text-danger"></small>
                        </div>
                        <div class="col-lg-6 form-group">
                            <h5><b>DOCUMENTO</b></h5>
                            <input type="file" name="crrfile2" id="crrfile2" class="form-control" accept="application/pdf" required/>
                            <small class="text-danger"></small>
                        </div>
                        <div class="col-lg-12 form-group">
                            <h5><b>OBSERVACIÓN</b></h5>
                            <textarea name="observacion_crrfile_addoc" id="observacion_crrfile_addoc" class="form-control" spellcheck="true" maxlength="250" style="min-height: 100px; width: 100%; max-width: 100%; min-width: 100%;" required></textarea>
                            <small class="text-danger"></small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-lg-offset-9">
                            <button type="button" class="btn btn-danger cls_addoc" data-dismiss="modal"><i class="fas fa-times"></i> CERRAR</button>
                            <button class="btn btn-success">GUARDAR</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> <!-- FIN FECHA: 29-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->

<script>

    var idCaja=0;
    var link_post = '';
    var users = [];
    var info = [];
    var modal ='';
    var empresas = [];
    var documentN = 1;
    var validacion = 0;
    let row = false;

    var id_usuario = <?php echo $this->session->userdata('inicio_sesion')['id'] ?>;
    var rol_usuario = '<?php echo $this->session->userdata('inicio_sesion')['rol'] ?>';
    var depto_usuario = '<?php echo $this->session->userdata("inicio_sesion")['depto']?>';
    
    let validarAgregarCaja;
    let validarAutorizacionCaja;
    let validarEditarDoc;
    let validarEliminarDoc;
    let validarAgregarDocumento;
    let validarCerrarCaja;

    var verNotificaciones = false;

    var colaboradoresArray = [];
    var colaboradoresResponsableArray = [];
    var usuariosCXP = [];

    var tomSelectUsuarioCXP;
    // Tabla Cajas Chicas
        $("#tablacajas").ready( function () {
            $('#tablacajas thead tr:eq(0) th').each( function (i) {
                if( i > 0 && i < $('#tablacajas thead tr:eq(0) th').length - 1 ){
                    var title = $(this).text();
                    $(this).html( '<input type="text" style="font-size: .8em; width: 100%;" class="form-control" placeholder="'+title+'" />' );
            
                    $( 'input', this ).on( 'keyup change', function () {
                        if ( table_cajas.column(i).search() !== this.value ) {
                            table_cajas
                                .column(i)
                                .search( this.value )
                                .draw();
                        }
                    } );
                }
            });

            
            table_cajas = $("#tablacajas").DataTable({
                dom: 'Brtip', //'rtip',
                buttons: [ {
                    title: 'CAJAS CHICAS CPP',/** FECHA: 21-JUNIO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                    extend: 'excelHtml5',
                    text: '<i class="fas fa-file-excel"></i>',
                    messageTop: "LISTADO DE CAJAS CHICAS",
                    attr: {
                        class: 'btn btn-success'       
                    },
                    exportOptions: {
                        format:{    
                            header:  function (data, columnIdx) {
                                data = data.replace( '<input type="text" style="font-size: .8em; width: 100%;" class="form-control" placeholder="', '' );
                                data = data.replace( '">', '' )                               
                                return data;
                            }
                        },
                        columns: [ 1, 2, 3, 4, 5, 6, 7, 8, 9, 10 ]
                    }
                }],
                "language": lenguaje,
                "processing": false,
                "pageLength": 10,
                "bAutoWidth": false,
                "bLengthChange": false,
                "bInfo": false,
                "scrollX": true,
                "order": [[1, "asc"]], /** FECHA: 29-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                "columns": [
                    { /** INICIO FECHA: 29-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                        "width": "0.01%",
                        "className": 'details-control',
                        "orderable": false,
                        "data" : null,
                        "defaultContent": '<i class="fas animacion fa-caret-right"></i>'
                    }, /** FIN FECHA: 29-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                    {
                        "width": "5%",
                        "data": function (d){
                            return '<p style="font-size: .8em">'+d.idcaja+'</p>'
                        }
                    },
                    {
                        "width": "8%",
                        "data": function (d){
                            return '<p style="font-size: .7em">'+(d.nombre ? d.nombre : 'NA')+'</p>'
                        }
                    },
                    { /** INICIO FECHA: 29-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                        "width": "8%",
                        "data": function (d){
                            return '<p style="font-size: .7em">'+d.pertenece_a+'</p>'
                        }
                    }, /** FIN FECHA: 29-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                    {
                        "width": "8%",
                        "data": function (d){
                            return '<p style="font-size: .8em">'+d.deptos+'</p>'
                        }
                    },
                    {
                        "width": "8%",
                        "data": function (d){
                            return '<p style="font-size: .8em">'+d.empresas+'</p>'
                        }
                    },
                    {
                        "width": "8%",
                        "data": function (d){
                            return '<p style="font-size: .8em; text-align: center;">$ '+formatMoney(d.monto)+" MXN"+'</p>'
                        }
                    },
                    {
                        "width": "8%",
                        "data": function (d){
                            return '<p style="font-size: .7em">'+d.rembolsar+'</p>'
                        }
                    },
                    {
                        "width": "8%",
                        "data": function (d){
                            return '<p style="font-size: .8em; text-align: center;">$ '+formatMoney( d.rem)+" MXN"+'</p>'
                        }
                    },
                    {
                        "width": "8%",
                        "data": function (d){
                            return '<p style="font-size: .8em; text-align: center;">$ '+formatMoney( d.cierre)+" MXN"+'</p>'
                        }
                    },
                    { /** INICIO FECHA: 29-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                        "width": "15%",
                        "data": function (d){
                            return '<p style="font-size: .8em; text-align: left;">'+(d.comentario_cierre == null ? 'NA' : d.comentario_cierre) +'</p>'
                        }
                    }, /** FIN FECHA: 29-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                    {
                        "width": "8%",
                        "data": function (d){
                            let etq_estatus = "";
                            if(d.aumento >= 1){
                                etq_estatus = "<span class='label label-success'>VIGENTE</span>&nbsp/&nbsp<span class='label label-primary'>INCREMENTO</span" ;
                            }else if(d.aumento < 0){
                                etq_estatus = "<span class='label label-success'>VIGENTE</span>&nbsp/&nbsp<span class='label label-danger'>REDUCCION</span" ;
                            }else{
                                etq_estatus = "<span class='label label-success'>VIGENTE</span>"
                            }
                            return '<p style="font-size: .8em; text-align: center;">'+ /** INICIO FECHA: 29-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                                (d.estatus == 0 ? "<span class='label label-danger'>CERRADA</span>"
                                        : etq_estatus )
                                +'</p>'/** FIN FECHA: 29-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                        }
                    },
                    {
                        "width": "4%",
                        "data": function( d ){
                            lock = id_usuario !== 2259 && d.responsable != null && d.estatus == 1 ? '<button class="btn btn-default btn-opciones_extra btn-sm desactivar" data-toggle="tooltip" data-placement="left" title="Cerrar Caja"><i class="fas fa-lock-open"></i></button>': '';
                            usr =  id_usuario !== 2259 && d.responsable == null && d.estatus == 1 ? '<button class="btn btn-primary btn-sm add_user" data-toggle="tooltip" data-placement="left" title="Agregar Usuario"><i class="fa fa-user-plus"></i></button>':'';
                            docs = docs = d.docs >= 1 
                                    ? '<button class="btn btn-success btn-sm ver_docs" data-toggle="tooltip" data-placement="left" title="Ver Documentos" style="position: relative;">' +
                                    '<i class="fa fa-files-o"></i>' + 
                                    (id_usuario === 2259 && d.docs_r >= 1 
                                        ? `<span class="badge-custom">${d.docs_r}</span>` 
                                        : '') + 
                                    '</button>' 
                                    : '';
                            incremento = id_usuario !== 2259 && (d.aumento >= 1 || d.aumento < 0) ? '<button class="btn btn-info btn-sm historial" data-toggle="tooltip" data-placement="left" title="Ver Incrementos"><i class="fa fa-line-chart"></i></button>' : '';
                            edit = d.responsable != null && id_usuario !== 2259 && d.estatus == 1 ? '<button '+ (d.docs_r >= 1 ?  'disabled data-toggle="tooltip" data-placement="left" title="Se Espera Validación de Documentos."' : '')+' class="btn btn-warning btn-sm edit_caja" data-toggle="tooltip" data-placement="left" title="Editar Caja"><i class="fas fa-edit"></i></button>': '';
                            // edit = d.responsable != null && id_usuario !== 2259 && d.estatus == 1 ? '<button class="btn btn-warning btn-sm edit_caja" data-toggle="tooltip" data-placement="left" title="Editar Caja"><i class="fas fa-edit"></i></button>': '';
                            view = d.movEmpDoc >= 1 ? '<button class="btn btn-primary btn-sm view_historial" data-toggle="tooltip" data-placement="left" title="Ver Historial"><i class="fas fa-eye"></i></button>' : '';
                            add = d.responsable != null && id_usuario !== 2259 && d.estatus == 1 ? '<button class="btn bg-maroon btn-sm add_doc" data-toggle="tooltip" data-placement="left" title="Agregar Documento"><i class="fas fa-plus-square"></i></button>': '';
                            return '<div class="btn-group-vertical" role="group">' + view + edit + lock + usr + docs +incremento + add +'</div>';
                            /** FIN FECHA: 29-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
                        },
                        "orderable": false 
                    }
                ],
                "columnDefs": [{
                    "targets": [10], 
                    "orderable": false,
                    "visible": false
                    }
                ],
                "ajax": {   
                    "url": url + "Cajas_ch/cajaschicas",
                    "type": "POST",
                    cache: false
                },
                initComplete: function () {
                    $('[data-toggle="tooltip"]').tooltip("destroy");
                    $('[data-toggle="tooltip"]').tooltip({ trigger: "hover" });
                },
                drawCallback: function() {
                    $('[data-toggle="tooltip"]').tooltip("destroy");
                    $('[data-toggle="tooltip"]').tooltip({ trigger: "hover" });
                    if (!verNotificaciones) {
                        idsCumplenCondicion = [];

                        this.api().rows().every(function () {
                            var data = this.data();
                            if (id_usuario === 2259 && data.docs_r >= 1) {
                                idsCumplenCondicion.push(`<b>&#9679; CAJA ${data.idcaja}  &ndash;</b> ${data.nombre} (${data.rembolsar})`);
                            }
                        });

                        if (idsCumplenCondicion.length > 0) {
                            $.notify.addStyle('vacantes', {
                                html: 
                                    `<div>
                                        <div class="clearfix alert alert-danger">
                                            <div class="title" data-notify-html="title"/>
                                                ${idsCumplenCondicion.join('<br> ')}
                                            </div>
                                        </div>
                                    </div>`
                            });

                            $.notify({
                                title: "<h5><strong><i class='fas fa-exclamation text-danger'></i> ATENCIÓN</strong></h5> TIENE DOCUMENTOS PENDIENTES POR REVISAR<hr/>"
                            }, {
                                style: 'vacantes',
                                autoHide: true,
                                timer: 1000000,
                                clickToHide: true
                            });

                            verNotificaciones = true;
                        }
                    }
                }
            });   

            let isVisible = false; /** INICIO FECHA: 21-JUNIO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
            if (id_usuario === 2259) {
                isVisible = true;
            } else if ((rol_usuario === 'SO' || rol_usuario === 'CP') && depto_usuario !== 'ADMINISTRACION') {
                isVisible = true;
            }
            table_cajas.column(12).visible(isVisible); /**FIN FECHA: 21-JUNIO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/

            $('#tablacajas tbody').on('click', 'td.details-control', function () {  /** INICIO FECHA: 29-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                var tr = $(this).closest('tr');
                var row = table_cajas.row( tr );
                
                if (row.data().comentario_cierre) {
                    if ( row.child.isShown() ) {
                        row.child.hide();
                        tr.removeClass('shown');
                        $(this).parent().find('.animacion').removeClass("fa-caret-down").addClass("fa-caret-right");
                    }
                    else {
        
                        var informacion_adicional = 
                            '<table class="table text-justify">'+
                                '<tr>'+
                                    '<td>'+(row.data().comentario_cierre ? '<strong>INFORMACIÓN CIERRE: </strong>'+row.data().comentario_cierre : '')+'</td>'+
                                '</tr>'+
                            '</table>'
                        ;
        
                        row.child( informacion_adicional ).show();
                        tr.addClass('shown');
                        $(this).parent().find('.animacion').removeClass("fa-caret-right").addClass("fa-caret-down");
                    }
                }
            });/** FIN FECHA: 29-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/

            // Verificar si el id_usuario es diferente de 2259 (Maricela Rico) mostrar boton de Agregar Caja
            if (id_usuario !== 2259 && (rol_usuario === 'CP' || rol_usuario === 'SO') && depto_usuario !== 'ADMINISTRACION'){/** FECHA: 21-JUNIO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                $("#add_btn_caja").append(`
                    <button type="button" id="add_caja" class="btn btn-block btn-primary">
                        <i class="fa fa-plus"></i> AGREGAR CAJA
                    </button>`
                );

                $("#add_btn_caja").on("click", "#add_caja", function() {
                    // FECHA : 25-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Se elimina el contenido de que encuentre dentro del div userColaboradorEdit
                    // cuando el usuario desee agregar una nueva caja chica.
                    $("#userColaboradorEdit").html('');
                    
                    var cajach_form = document.getElementById("cajach_form");
                    tomSelectUsuarioCXP ? tomSelectUsuarioCXP.enable() : null;
                    cajach_form.reset();
                    validarAgregarCaja.resetForm();
                    $("#cajach_form .has-error").removeClass("has-error");


                    $(".empresa").empty();

                    $('.depto').val(null).trigger('change').prop("disabled", false);
                    $('#titulo-modal').html('CAJA CHICA');
                    inputTomSelect('nombre', colaboradoresArray, {valor:'value', texto: 'label', opcDataSelect: 'opcionesData'});

                    link_post = "Cajas_ch/nueva_caja";
                    $("#modal_add_caja").modal();
                });
            } else {
                $("#add_btn_caja").remove();
            }
        });
    // Fin Tabla Cajas Chicas

    $(document).ready(function () {

        // Cargar select2
            const select2Options = {
                placeholder: "--- Seleccione una opción ---",
                allowClear: true,
                language: {
                    noResults: function() {
                        return "No se encontraron resultados.";
                    }
                },
                width: "100%"
            };

            // Inicializar Select2 en todos los selectores relevantes
            $(".empresa, .depto, .motivo_documento, .motivo_documento_delete").select2(select2Options);
        // Fin Cargar Select2

        $.getJSON(url + "Cajas_ch/listas").done(function(data) {

            $("#nombre").append('<option value="">---Seleccione una opción---</option>');
            $("#user").append('<option value="">---Seleccione una opción---</option>');
            $("#idreembolso_ch").append('<option value="">---Seleccione una opción---</option>');

            // Usuarios
            $.each(data.usuarios, function(i, v) {
                usuariosCXP.push({value: v.idusuario, label: v.nombre_completo});
            });

            // Departamentos
            $.each(data.deptos, function(i, v) {
                $(".depto").append('<option value="'+v.iddepartamentos+'">'+v.departamento+'</option>');                    
            });

            // Empresas y colaboradores
            empresas = data.empresas;
            var colaboradores = JSON.parse(data.responsable);

            // Nombres
            $.each(colaboradores, function(i, v) {
                var nombre = v.nombre_persona+' '+v.apellido_paterno_persona+' '+v.apellido_materno_persona;
                colaboradoresArray.push({value: nombre, label: nombre, opcionesData: {idcont : v.idcontrato}});
            });

            // Usuario Reembolso
            $.each(colaboradores, function(i, v) {
                var nombre = v.nombre_persona+' '+v.apellido_paterno_persona+' '+v.apellido_materno_persona;
                colaboradoresResponsableArray.push({value: v.idcontrato, label: nombre, opcionesData: {idcont : v.idcontrato}});
            });

            // Re-inicializar Select2
            $(".empresa, .depto").select2(select2Options);
        }).fail(function(xhr, status, error) {
            console.error("Error al obtener datos:", error);
        });

        // Selects Dinamicos
            $("#nombre").change(function () {

                const tomSelect = $(this)[0].tomselect;
                if (!tomSelect) {
                    return;
                }

                const selectedValue = tomSelect.getValue();
                const item = tomSelect.getOption(selectedValue);

                var dataIdcont = null;
                // FECHA : 25-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Se agrega el valor de dataIdcont ya que no lo estaba asignando
                // y lo esta ba paasndo vacio.
                if (item) {
                    dataIdcont = item.getAttribute('data-idcont');
                    $('#idcontrato').val(dataIdcont);
                }

                if ((dataIdcont && dataIdcont >= 1) || (link_post == "Cajas_ch/edit_caja")) {
                    $.post(url + "Cajas_ch/busca_empresas", {
                        idcontrato: dataIdcont
                    }).done(function (data) {  
                        $(".empresa").empty();
    
                        var data = JSON.parse(data);
    
                        $.each(empresas, function (i, v) {
                            $(".empresa").append('<option value="' + v.idempresa + '">' + v.abrev + '</option>');
                        });
    
                        if (row !== false && link_post == "Cajas_ch/edit_caja") {
                            $('#empresa').val(row.idsempresa.split(",")).trigger('change');
                        }
                    }).fail(function (xhr, status, error) {
                        console.error("Error al obtener empresas:", error);
                    });
                }else{
                    $(".empresa").empty();
                }
            });
        // Fin Selects Dinamicos

        // Agregar Caja Chica
            $('#monto').on('input', function() {
                var value = this.value.replace(/[^0-9]/g, '');
                if (value.length > 11) {
                    value = value.slice(0, 11);
                }
                this.value = formatNumberWithCommas(value);
            });


            function formatNumberWithCommas(number) {
                return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            }

            function parseNumberFromCommas(value) {
                return value.replace(/,/g, '');
            }

            $.validator.addMethod("integer", function(value, element) {
                var valueWithoutCommas = parseNumberFromCommas(value);
                return this.optional(element) || (/^\d+$/.test(valueWithoutCommas));
            }, "Ingrese un número entero válido.");

            $.validator.addMethod("number", function(value, element) {
                var valueWithoutCommas = parseNumberFromCommas(value);
                return this.optional(element) || !isNaN(Number(valueWithoutCommas));
            }, "Ingrese un número válido.");

            $.validator.addMethod("minValue", function(value, element, param) {
                var valueWithoutCommas = parseNumberFromCommas(value);
                return this.optional(element) || Number(valueWithoutCommas) >= param;
            }, "La cantidad ingresada debe ser mayor a {0}.");

            $.validator.addMethod("maxValue", function(value, element, param) {
                var valueWithoutCommas = parseNumberFromCommas(value);
                return this.optional(element) || Number(valueWithoutCommas) <= param;
            }, "La cantidad ingresada debe ser menor a {0}.");

            $.validator.addMethod("maxLengthWithoutCommas", function(value, element, param) {
                var valueWithoutCommas = parseNumberFromCommas(value);
                return this.optional(element) || valueWithoutCommas.length <= param;
            }, "El número ingresado no debe exceder {0} dígitos.");


            function restoreCommas() {
                var montoInput = $('#monto');
                var montoValue = montoInput.val();
                montoInput.val(formatNumberWithCommas(parseNumberFromCommas(montoValue)));
            }
            //Fecha : 25-JINIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Se valida que el slect idreembolsoEdit tenga datos 
            // en caso de que no sea asi se muestra un mensaje que indica que se debe selecionar el nombre de la persona a la que se va a reembolsar.

            validarAgregarCaja = $('#cajach_form').validate({
                rules: {
                    nombre: {
                        required: true
                    },
                    'depto[]': {
                        required: true
                    },
                    'empresa[]': {
                        required: true
                    },
                    monto: {
                        required: true,
                        number: true,
                        integer: true,
                        minValue: 1,
                        maxValue: 2147483647,
                        maxLengthWithoutCommas: 11
                    },
                    observacion: {
                        required: true,
                        maxlength: 250
                    },
                    idreembolsoEdit: {
                        required: true
                    }
                },
                messages: {
                    nombre: {
                        required: "Este campo es obligatorio. Seleccione un responsable."
                    },
                    'depto[]': {
                        required: "Este campo es obligatorio. Seleccione al menos un departamento."
                    },
                    'empresa[]': {
                        required: "Este campo es obligatorio. Seleccione al menos una empresa."
                    },
                    monto: {
                        required: "Este campo es obligatorio. Ingrese una cantidad.",
                        number: "Ingrese un número válido.",
                        integer: "Ingrese un número entero válido.",
                        minValue: "La cantidad ingresada debe ser mayor a 0.",
                        maxValue: "La cantidad ingresada debe ser menor a 2,147,483,647.",
                        maxLengthWithoutCommas: "Solo se permiten 11 dígitos."
                    },
                    observacion: {
                        required: "Este campo es obligatorio. Ingrese una observación.",
                        maxlength: "Solo se permiten 250 caracteres."
                    },
                    idreembolsoEdit: {
                        required: "Este campo es obligatorio. Seleccione a quién reembolsar.",
                        maxlength: "Solo se permiten 250 caracteres."
                    }
                },
                errorPlacement: function(error, element) {
                    error.addClass('text-danger');
                    error.appendTo(element.closest('.form-group').find('small'));
                },
                submitHandler: function(form) {
                    var montoInput = $('#monto');
                    var montoValue = parseNumberFromCommas(montoInput.val());
                    montoInput.val(montoValue);

                    info = new FormData(form);

                    modal = $("#modal_add_caja");
                    $("#confirm").modal();
                    restoreCommas();
                },
                invalidHandler: function(event, validator) {
                    restoreCommas();
                }
            });
        // Fin Agregar Caja Chica

        // Autorizar Caja Chica
            var nombre_reembolso_ch;
            $(".idreembolso_ch").on("change", function(e) {
                nombre_reembolso_ch = $(this).find(":selected").text();
                $("#nombre_reembolso_ch").val(nombre_reembolso_ch);
            });

            $("#tablacajas").on("click", ".add_user", function (e){
                row = table_cajas.row($(this).parents('tr')).data();
                idCaja = row.idcaja;

                var adduser_form = document.getElementById("adduser_form");
                adduser_form.reset();
                validarAutorizacionCaja.resetForm();
                $("#adduser_form .has-error").removeClass("has-error");

                $('.usrfile').val(null);
                inputTomSelect('user', usuariosCXP, {valor:'value', texto: 'label'});
                inputTomSelect('idreembolso_ch', colaboradoresResponsableArray, {valor:'value', texto: 'label', opcDataSelect: 'opcionesData'});

                $("#asignar_user").modal();
            });

            $.validator.addMethod("extension", function(value, element, param) {
                param = typeof param === "string" ? param.replace(/,/g, '|') : "pdf";
                return this.optional(element) || value.match(new RegExp("\\.(" + param + ")$", "i"));
            }, "El archivo debe ser un documento PDF.");

            validarAutorizacionCaja = $('#adduser_form').validate({
                rules: {
                    user: {
                        required: true
                    },
                    idreembolso_ch: {
                        required: true
                    },
                    usrfile: {
                        required: true,
                        extension: "pdf"
                    }
                },
                messages: {
                    user: {
                        required: "Este campo es obligatorio. Seleccione un usuario."
                    },
                    idreembolso_ch: {
                        required: "Este campo es obligatorio. Seleccione a quién reembolsar."
                    },
                    usrfile: {
                        required: "Este campo es obligatorio. Adjunte el documento de autorización.",
                        extension: "Solo se permiten archivos en PDF."
                    }
                },
                errorPlacement: function(error, element) {
                    error.addClass('text-danger');
                    error.appendTo(element.closest('.form-group').find('small'));
                },
                submitHandler: function(form) {
                    info = new FormData($(form)[0]);
                    link_post = "Cajas_ch/asignar_usuario";
                    modal = $("#asignar_user");
                    $("#confirm").modal();
                }
            });

            
        // Fin Autorizar Caja Chica

        // Popover-icon
            $(document).on('mouseenter', '.popover-icon', function() {
                var $popoverIcon = $(this);
                var popoverContent = $popoverIcon.attr('data-content');
                var popoverTitle = $popoverIcon.attr('title');

                if (!$popoverIcon.data('bs.popover')) {
                    $popoverIcon.popover({
                        title: popoverTitle,
                        content: popoverContent,
                        trigger: 'manual',
                        placement: 'bottom'
                    });
                    $popoverIcon.popover('show');
                } else {
                    $popoverIcon.popover('show');
                }
            });

            $(document).on('mouseleave', '.popover-icon', function() {
                $(this).popover('hide');
            });

            $(document).on('click', function(e) {
                $('.popover-icon').each(function() {
                    if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
                        $(this).popover('hide');
                    }
                });
            });

            $(document).keyup(function(e) {
                if (e.keyCode === 27) {
                    $('.popover-icon').popover('hide');
                }
            });

            $(window).scroll(function() {
                $('.popover-icon').popover('hide');
            });
        // Fin popover-icon
    });

    // Ver Documentos de Caja Chica
        $("#tablacajas").on("click", ".ver_docs", function (e){
            row = table_cajas.row($(this).parents('tr')).data();
            idCaja = row.idcaja;
            $("#title-docs").text('DOCUMENTOS CARGADOS - CAJA CHICA #'+idCaja);
            $("#detalle").html('');
            $.post(url + "Cajas_ch/link_documentos", {
                idcaja: idCaja
            }).done(function(data) {
                data = JSON.parse(data);

                if(data.docs){
                    $.each( data.docs, function( i, v){
                        if(v.iddocumento == "2" || v.iddocumento == "7")
                            doc = v.ndocumento ? v.ndocumento : "DOCUMENTO DE AUTORIZACIÓN";
                        if(v.iddocumento == "5")
                            doc = v.ndocumento;
                        if(v.iddocumento == "60" || v.iddocumento == "8")
                            doc = "CIERRE DE CAJA" + (v.desc ? ': ' + v.desc : '');
                        if(row.estatus == "0"){
                            $("#detalle").append(`
                                <div class="row">
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <h5>- ${doc}</h5>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-5 col-xs-5 text-center">
                                        <div class="btn-group btn-group-justified" role="group" aria-label="...">
                                            <div class="btn-group" role="group">
                                                <a ${(v.link == null ? 'disabled' : 'target="_blank"')} 
                                                    ${v.link == null ? 'title="Sin Documento."' : `href="${v.link}" title="Descargar"`}
                                                    class="btn btn-sm btn-primary archivos">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                ${(i < data.docs.length - 1 ? '<hr style="border-top: 1px solid #E1E1E1; margin-top: 5px; margin-bottom: 5px;">' : '')}
                            `);
                        }else{
                            let estatusMsj = '';
                            if (v.estatus == 2) {
                                estatusMsj = '<label style="margin-top: 0.2rem; padding: 0.2rem; padding-left:5px; padding-right:5px;" class="bg-warning text-sm">Esperando Autorización.</label>';
                            } else if (v.estatus == 4) {
                                estatusMsj = '<label style="margin-top: 0.2rem; padding: 0.2rem; padding-left:5px; padding-right:5px;" class="bg-warning text-sm">Esperando Autorización para Eliminar.</label>';
                            } else if (v.estatus == 3) {
                                estatusMsj = '<label style="margin-top: 0.2rem; padding: 0.2rem; padding-left:5px; padding-right:5px;" class="bg-danger text-sm">Documento Rechazado.</label>';
                            }

                            if (![2259].includes(id_usuario)) { /** INICIO FECHA: 29-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                                var editButton = (v.estatus != 2 && v.estatus != 4) ? `
                                    <div class="btn-group" role="group">
                                        <button class="btn btn-warning btn-sm edit_doc" id="btn_edoc" data-toggle="tooltip" data-placement="top" title="Editar" data-value="${v.ubicacion},${v.idDoc},${v.estatus}" value="${doc}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </div>
                                ` : '';

                                var deleteButton = (v.estatus != 2 && v.estatus != 4) ? `
                                    <div class="btn-group" role="group">
                                        <button ${(v.link == null ? 'disabled' : '')} class="btn btn-danger btn-sm destroy_doc" id="btn_elidoc" data-toggle="tooltip" data-placement="top" ${v.link == null ? 'title="Sin Documento."' : 'title="Eliminar"'} data-value="${v.ubicacion},${v.idDoc},${idCaja},${v.estatus}" value="${v.ndocumento ? v.ndocumento : "DOCUMENTO DE AUTORIZACIÓN"}">
                                            <i class='fas fa-trash'></i>
                                        </button>
                                    </div>
                                ` : '';

                                var mostrar = `
                                    <div class="row ">
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <h5>- ${doc}</h5>
                                            ${estatusMsj}
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-5 col-xs-5 text-center">
                                            <div class="btn-group btn-group-justified" role="group" aria-label="...">
                                                <div class="btn-group" role="group">
                                                    <a ${(v.link == null ? 'disabled' : 'target="_blank"')} data-toggle="tooltip" data-placement="top"
                                                    ${v.link == null ? 'title="Sin Documento."' : `href="${v.link}" title="Descargar"`}
                                                    class="btn btn-sm btn-primary archivos">
                                                        <i class="fas fa-download"></i>
                                                    </a>
                                                </div>
                                                ${editButton}
                                                ${deleteButton}
                                            </div>
                                        </div>
                                    </div>
                                    ${(i < data.docs.length - 1 ? '<hr style="border-top: 1px solid #E1E1E1; margin-top: 5px; margin-bottom: 5px;">' : '')}
                                `;
                                $("#detalle").append(mostrar);
                            }else{ 
                                var aprobarBtn = v.estatus == 2 || v.estatus == 4 ? `
                                    <div class="btn-group" role="group">
                                        <a data-toggle="tooltip" data-placement="top" title="Aprobar" data-value="${v.ubicacion},${v.idDoc},${idCaja},${v.estatus}" class="btn btn-sm btn-success aprobar_btn">
                                            <i class="fa fa-thumbs-up"></i>
                                        </a>
                                    </div>
                                    <div class="btn-group" role="group">
                                        <a data-toggle="tooltip" data-placement="top" title="Rechazar" data-value="${v.ubicacion},${v.idDoc},${idCaja},${v.estatus}" class="btn btn-sm btn-danger rechazar_btn">
                                            <i class="fa fa-thumbs-down"></i>
                                        </a>
                                    </div>
                                ` : '';

                                var popover = (v.estatus == 2 || v.estatus == 4) ? 
                                    `<i class="fa fa-info-circle text-info popover-icon" 
                                        data-toggle="popover" 
                                        title="${v.estatus == 4 ? 'MOTIVO DE ELIMINACIÓN' : 'MOTIVO DE ACTUALIZACIÓN'}" 
                                        data-content="${v.desc ? v.desc : 'NA'}.">
                                    </i>` : '';

                                var mostrar = `
                                    <div class="row">
                                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <h5>- ${doc} ${popover}</h5>
                                            ${estatusMsj}
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-5 col-xs-5 text-center">
                                            <div class="btn-group btn-group-justified" role="group" aria-label="...">
                                                <div class="btn-group" role="group">
                                                    <a data-toggle="tooltip" data-placement="top" ${(v.link == null ? 'disabled' : 'target="_blank"')} 
                                                        ${v.link == null ? 'title="Sin Documento."' : `href="${v.link}" title="Descargar"`}
                                                        class="btn btn-sm btn-primary archivos">
                                                        <i class="fas fa-download"></i>
                                                    </a>
                                                </div>
                                                ${aprobarBtn}
                                            </div>
                                        </div>
                                        <div class="col-lg-12" id="btns_msj_${v.idDoc}"></div>
                                    </div>
                                    ${(i < data.docs.length - 1 ? '<hr style="border-top: 1px solid #E1E1E1; margin-top: 5px; margin-bottom: 5px;">' : '')}
                                `;

                                $("#detalle").append(mostrar);
                            } /** FIN FECHA: 29-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                        }
                    });
                    $('[data-toggle="tooltip"]').tooltip({ trigger: "hover" });
                    $("#docs").modal();
                }else{
                        $("#detalle").append("<div class='col-lg-12'><h5>SIN DOCUMENTOS </h5></div>");
                }
            });
        });
    // Fin Ver Documentos de Caja Chica

    // Ver Incremento/Reducciones de Caja Chica
        $("#tablacajas").on("click", ".historial", function (e){
            row = table_cajas.row($(this).parents('tr')).data();
            idCaja = row.idcaja;
            $("#title-docs").text("INCREMENTOS/REDUCCIONES");
            $("#usuario").text("Control Interno"); //Al modal Generado para el historial, se le agrega el texto solicitado
            $("#detalle").html(`
                <table class="table table-striped table-bordered table-hover hidden-xs hidden-sm">
                    <thead>
                        <tr>
                            <th scope="col">Monto Anterior</th>
                            <th scope="col">Monto Nuevo</th>
                            <th scope="col">Fecha Movimiento</th>
                            <th scope="col">Observación</th>
                        </tr>
                    </thead>
                    <tbody id="historial"></tbody>
                </table>
                <div class="panel-group visible-xs visible-sm" role="tablist" aria-multiselectable="true" id="historial_list"></div>
            `);
            $.post(url + "Cajas_ch/historial_incrementos", {
                idcaja: idCaja
            }).done(function(data) {
                data = JSON.parse(data);
                if(data.info){
                    $.each( data.info, function( i, v){
                        $("#historial").append(`
                            <tr>
                                <td>$${formatMoney(v.anterior)}MXN</td>
                                <td>$${formatMoney( v.nuevo )}MXN</td>
                                <td>${formato_fechaymd(v.fmovimiento)}</td>
                                <td>${(v.observacion?v.observacion:"NA")}</td>
                            </tr>
                        `);
                        $("#historial_list").append(`
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="inc${i}">
                                    <h4 class="panel-title">
                                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseInc${i}" aria-expanded="true" aria-controls="collapseInc${i}">
                                            <h4 class="list-group-item-heading">Monto Nuevo: $${formatMoney( v.nuevo )} MXN</h4>
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseInc${i}" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="inc${i}">
                                    <div class="panel-body">
                                        <p class="list-group-item-text"><label class="text-bold">Monto Anterior:&nbsp;</label>$ ${formatMoney(v.anterior)} MXN</p>
                                        <p class="list-group-item-text"><label class="text-bold">Fehca Movimiento:&nbsp;</label>${formato_fechaymd(v.fmovimiento)}</p>
                                        <p class="list-group-item-text"><label class="text-bold">Observación:&nbsp;</label>${(v.observacion?v.observacion:"NA")}</p>
                                    </div>
                                </div>
                            </div>
                        `);
                    });
                    $("#docs").modal();
                }
            });
        });
    // Fin Ver Incremento/Reducciones de Caja Chica

    // Ver Historial General
        $("#tablacajas").on("click", ".view_historial", function (e) {
            e.preventDefault();
            let row = table_cajas.row($(this).parents('tr')).data();
            let idcaja = row.idcaja;

            const empresasHtml = `
                <table class="table table-striped table-bordered table-hover hidden-xs hidden-sm">
                    <thead>
                        <tr>
                            <th scope="col">Tipo</th>
                            <th scope="col">Fecha Movimiento</th>
                            <th scope="col">Observación</th>
                            <th scope="col">Usuario</th>
                        </tr>
                    </thead>
                    <tbody id="historial_empresas"></tbody>
                </table>
                <div class="panel-group visible-xs visible-sm" role="tablist" aria-multiselectable="true" id="historial_empresas_list"></div>
            `;

            const documentosHtml = `
                <table class="table table-striped table-bordered table-hover hidden-xs hidden-sm hidden-md">
                    <thead>
                        <tr>
                            <th scope="col">Tipo</th>
                            <th scope="col">Fecha Movimiento</th>
                            <th scope="col">Observación</th>
                            <th scope="col">Usuario</th>
                        </tr>
                    </thead>
                    <tbody id="historial_documentos"></tbody>
                </table>
                <div class="panel-group visible-xs visible-sm" role="tablist" aria-multiselectable="true" id="historial_documentos_list"></div>
            `;

            $("#data-empresas").html(empresasHtml);
            $("#data-documentos").html(documentosHtml);

            $.post(url + "Cajas_ch/historialGeneral", { idcaja: idcaja })
                .done(function (data) {
                    data = JSON.parse(data);

                    // Reset all tabs and sections
                    $("#documentosTab, #empresasTab, #data-empresas, #data-documentos").removeClass('active in').addClass('hidden');

                    if (data.empresas !== false) {
                        $("#empresasTab").removeClass('hidden').addClass('active');
                        $("#data-empresas").removeClass('hidden').addClass('tab-pane fade in active');
                        data.empresas.forEach((itemEmp, i) => {
                            $("#historial_empresas").append(`
                                <tr>
                                    <td>${itemEmp.tipo}</td>
                                    <td>${formato_fechaymd(itemEmp.fmovimiento)}</td>
                                    <td>${itemEmp.observacion || "NA"}</td>
                                    <td>${itemEmp.nombreUsuario}</td>
                                </tr>
                            `);
                            $("#historial_empresas_list").append(`
                                <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="headingEmp${i}">
                                        <h4 class="panel-title">
                                            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseEmp${i}" aria-expanded="true" aria-controls="collapseEmp${i}">
                                                <h6 class="list-group-item-heading">${itemEmp.tipo}</h6>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseEmp${i}" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingEmp${i}">
                                        <div class="panel-body">
                                            <p class="list-group-item-text"><label class="text-bold">Fecha Movimiento:&nbsp;</label>${formato_fechaymd(itemEmp.fmovimiento)}</p>
                                            <p class="list-group-item-text"><label class="text-bold">Observación:&nbsp;</label>${itemEmp.observacion || "NA"}</p>
                                            <p class="list-group-item-text"><label class="text-bold">Usuario:&nbsp;</label>${itemEmp.nombreUsuario}</p>
                                        </div>
                                    </div>
                                </div>
                            `);
                        });
                        $("#documentosTab").removeClass('hidden');
                    } else {
                        $("#documentosTab").addClass('active');
                        $("#data-documentos").removeClass('hidden').addClass('tab-pane fade in active');
                        $("#historial_empresas").append('<tr><td colspan="4" style="text-align: center; background-color: #f8d7da;"><b style="color: #762129">No se han registrado movimientos.</b></td></tr>');
                    }

                    if (data.documentos !== false) {
                        data.documentos.forEach((itemDoc, i) => {
                            $("#historial_documentos").append(`
                                <tr>
                                    <td>${itemDoc.tipo}</td>
                                    <td>${formato_fechaymd(itemDoc.fmovimiento)}</td>
                                    <td>${itemDoc.observacion ? itemDoc.observacion : "NA"}</td>
                                    <td>${itemDoc.nombreUsuario}</td>
                                </tr>
                            `);
                            $("#historial_documentos_list").append(`
                                <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="headingDoc${i}">
                                        <h4 class="panel-title">
                                            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseDoc${i}" aria-expanded="true" aria-controls="collapseDoc${i}">
                                                <h6 class="list-group-item-heading">${itemDoc.tipo}</h6>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseDoc${i}" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingDoc${i}">
                                        <div class="panel-body">
                                            <p class="list-group-item-text"><label class="text-bold">Fecha Movimiento:&nbsp;</label>${formato_fechaymd(itemDoc.fmovimiento)}</p>
                                            <p class="list-group-item-text"><label class="text-bold">Observación:&nbsp;</label>${itemDoc.observacion ? itemDoc.observacion : "NA"}</p>
                                            <p class="list-group-item-text"><label class="text-bold">Usuario:&nbsp;</label>${itemDoc.nombreUsuario}</p>
                                        </div>
                                    </div>
                                </div>
                            `);
                        });

                        // Ensure "Documentos" tab content is set up correctly even if hidden initially
                        $("#documentosTab").on('click', function () {
                            $("#empresasTab").removeClass('active');
                            $("#data-empresas").removeClass('in active').addClass('fade');
                            $("#documentosTab").addClass('active');
                            $("#data-documentos").removeClass('hidden').addClass('tab-pane fade in active');
                        });
                    } else {
                        $("#documentosTab").addClass('hidden');
                        $("#historial_documentos").append('<tr><td colspan="4" style="text-align: center; background-color: #f8d7da;"><b style="color: #762129">No se han registrado movimientos.</b></td></tr>');
                    }

                    // Set the active tab and pane based on data availability
                    if (data.empresas !== false) {
                        $("#empresasTab").addClass('active');
                        // $("#empresasTab").removeClass('hidden').addClass('active'); 
                        $("#data-empresas").addClass('tab-pane fade in active');
                    } else if (data.documentos !== false) {
                        $("#documentosTab").removeClass('hidden').addClass('active');
                        $("#data-documentos").addClass('tab-pane fade in active');
                    }

                    $("#historialGeneral").modal();
                });
        });
    // Fin Ver Historial General

    // Agregar N+ Documentos
        $("#tablacajas").on("click", ".add_doc", function (e){
            row = table_cajas.row($(this).parents('tr')).data();
            idCaja = row.idcaja;

            var addoc_form = document.getElementById("addoc_form");
            addoc_form.reset();
            validarAgregarDocumento.resetForm();
            $("#addoc_form .has-error").removeClass("has-error");
                
            $("#modal_add_doc").modal();
        });

        $.validator.addMethod("extension", function(value, element, param) {
                param = typeof param === "string" ? param.replace(/,/g, '|') : "pdf";
                return this.optional(element) || value.match(new RegExp("\\.(" + param + ")$", "i"));
        }, "El archivo debe ser un documento PDF.");

        $.validator.addMethod("validWindowsFilename", function(value, element) {
            // Nombres reservados en Windows
            var reservedNames = /^(CON|PRN|AUX|NUL|COM[1-9]|LPT[1-9])$/i;

            // Caracteres no permitidos en nombres de archivos de Windows
            var invalidChars = /[<>:"/\\|?*\x00-\x1F]/;

            // No debe terminar con un espacio o un punto
            var invalidEnding = /[. ]$/;

            // Validar que el nombre no esté reservado
            if (reservedNames.test(value)) {
                $.validator.messages.validWindowsFilename = "El nombre de archivo no puede ser un nombre reservado del sistema (por ejemplo, CON, PRN, AUX, NUL, COM1, LPT1).";
                return false;
            } 

            // Validar que no contenga caracteres no permitidos
            else if (invalidChars.test(value)) {
                $.validator.messages.validWindowsFilename = "El nombre de archivo contiene caracteres no permitidos: \\ / : * ? \" < > |.";
                return false;
            } 

            // Validar que no termine con un espacio o un punto
            else if (invalidEnding.test(value)) {
                $.validator.messages.validWindowsFilename = "El nombre de archivo no puede terminar con un espacio o un punto.";
                return false;
            }

            // Validar que solo contenga letras, números, espacios, guion bajo, guion medio y acentos
            else if (!/^[a-zA-Z0-9 _\-áéíóúÁÉÍÓÚñÑüÜ]+$/.test(value)) {
                $.validator.messages.validWindowsFilename = "El nombre de archivo solo puede contener letras, números, espacios, guion bajo (_), guion medio (-) y acentos.";
                return false;
            }

            return true;
        }, "Nombre de archivo no válido.");

        validarAgregarDocumento = $('#addoc_form').validate({
            rules: {
                docname: {
                    required: true,
                    minlength: 1,
                    maxlength: 250,
                    validWindowsFilename: true
                },
                crrfile2: {
                    required: true,
                    extension: "pdf"
                },
                observacion_crrfile_addoc: {
                    required: true,
                    maxlength: 250,
                }
            },
            messages: {
                docname: {
                    required: "Este campo es obligatorio. Ingrese el nombre del documento.",
                    maxlength: "Solo se permiten 250 caracteres."
                },
                crrfile2: {
                    required: "Este campo es obligatorio. Adjunte un documento PDF.",
                    extension: "El archivo debe ser un documento PDF."
                },
                observacion_crrfile_addoc: {
                    required: "Este campo es obligatorio. Ingrese una observación.",
                    maxlength: "Solo se permiten 250 caracteres."
                }
            },
            errorPlacement: function(error, element) {
                error.addClass('text-danger');
                error.appendTo(element.closest('.form-group').find('small'));
            },
            submitHandler: function(form) {
                info = new FormData($(form)[0]);
                link_post = "Cajas_ch/add_doc";
                modal = $("#modal_add_doc");
                $("#confirm").modal();
            }
        });
    // Fin Agregar N+ Documentos

    // Cerrar Caja Chica
        $("#tablacajas").on("click", ".desactivar", function (e){
            row = table_cajas.row($(this).parents('tr')).data();
            idCaja = row.idcaja;

            var crrcaja_form = document.getElementById("crrcaja_form");
            crrcaja_form.reset();
            validarCerrarCaja.resetForm();
            $("#crrcaja_form .has-error").removeClass("has-error");

            $("#titulo-modal-cerrar").text('CERRAR CAJA - CAJA CHICA #'+idCaja);

            $("#cerrar_caja").modal();
        });

        $.validator.addMethod("pdfOnly", function(value, element) {
            if (element.files.length > 0) {
                var fileExtension = element.files[0].name.split('.').pop().toLowerCase();
                return fileExtension === 'pdf';
            }
            return true;
        }, "El archivo debe ser un documento PDF.");

        $.validator.addMethod("validWindowsFilename", function(value, element) {
            // Nombres reservados en Windows
            var reservedNames = /^(CON|PRN|AUX|NUL|COM[1-9]|LPT[1-9])$/i;

            // Caracteres no permitidos en nombres de archivos de Windows
            var invalidChars = /[<>:"/\\|?*\x00-\x1F]/;

            // No debe terminar con un espacio o un punto
            var invalidEnding = /[. ]$/;

            // Validar que el nombre no esté reservado
            if (reservedNames.test(value)) {
                $.validator.messages.validWindowsFilename = "El nombre de archivo no puede ser un nombre reservado del sistema (por ejemplo, CON, PRN, AUX, NUL, COM1, LPT1).";
                return false;
            } 

            // Validar que no contenga caracteres no permitidos
            else if (invalidChars.test(value)) {
                $.validator.messages.validWindowsFilename = "El nombre de archivo contiene caracteres no permitidos: \\ / : * ? \" < > |.";
                return false;
            } 

            // Validar que no termine con un espacio o un punto
            else if (invalidEnding.test(value)) {
                $.validator.messages.validWindowsFilename = "El nombre de archivo no puede terminar con un espacio o un punto.";
                return false;
            }

            // Validar que solo contenga letras, números, espacios, guion bajo, guion medio y acentos
            else if (!/^[a-zA-Z0-9 _\-áéíóúÁÉÍÓÚñÑüÜ]+$/.test(value)) {
                $.validator.messages.validWindowsFilename = "El nombre de archivo solo puede contener letras, números, espacios, guion bajo (_), guion medio (-) y acentos.";
                return false;
            }

            return true;
        }, "Nombre de archivo no válido.");

        validarCerrarCaja = $('#crrcaja_form').validate({
            rules: {
                crrfile1: {
                    required: true,
                    pdfOnly: true
                },
                archivo1: {
                    required: true,
                    minlength: 1,
                    maxlength: 250,
                    validWindowsFilename: true
                },
                observacion_crrfile1: {
                    required: true,
                    maxlength: 250
                }
            },
            messages: {
                crrfile1: {
                    required: "Este campo es obligatorio. Adjunte un archivo PDF.",
                    pdfOnly: "El archivo debe ser un documento PDF."
                },
                archivo1: {
                    required: "Este campo es obligatorio. Renombre el archivo.",
                    minlength: "El nombre del archivo debe tener al menos 1 carácter.",
                    maxlength: "El nombre del archivo no puede tener más de 255 caracteres."
                },
                observacion_crrfile1: {
                    required: "Este campo es obligatorio. Ingrese una observación.",
                    maxlength: "Solo se permiten 250 caracteres."
                }
            },
            errorPlacement: function(error, element) {
                error.addClass('text-danger');
                error.appendTo(element.closest('.form-group').find('small'));
            },
            submitHandler: function(form) {
                info = new FormData($(form)[0]);
                link_post= "Cajas_ch/cerrar_caja";
                modal = $("#cerrar_caja");
                $("#confirm").modal();
            }
        });
    // Fin  Cerrar Caja Chica

    // Editar Caja
        // INICIO | FECHA : 25-JUNIO-2025 | @author Efrain Martinez programador.analista38@ciudadmaderas.com | Se elimina el contenido del div userColaboradorEdit
        // para que al el modal de editar se llene nuevamente y no existan duplicidades, tambien se valida que row.pertenece_a sea NA y se muestra el select para que el usuario pueda agregar 
        // el nombre de la persona a la que se le va a reembolsar.
        $("#tablacajas").on("click", ".edit_caja", function(e){
            $("#userColaboradorEdit").html('');
            link_post = "Cajas_ch/edit_caja";
            row = table_cajas.row($(this).parents('tr')).data();
            idCaja = row.idcaja;
            var cajach_form = document.getElementById("cajach_form");
                cajach_form.reset();
                validarAgregarCaja.resetForm();
                $("#cajach_form .has-error").removeClass("has-error");

            if ($('#nombre')[0].tomselect) {
                $('#nombre')[0].tomselect.destroy();
            }

            tomSelectUsuarioCXP = new TomSelect('#nombre', {
                options: usuariosCXP,
                valueField: 'value',
                labelField: 'label',
                searchField: 'label'
            });

            if (row.pertenece_a == 'NA') {
                $("#userColaboradorEdit").append(`
                    <div class="col-lg-12 form-group">
                        <h5><b>REEMBOLSAR A</b></h5>
                        <select class="idreembolsoEdit" id="idreembolsoEdit" name="idreembolsoEdit" required placeholder="---Seleccione una opción---" autocomplete="off"></select>
                        <input type="hidden" class="nombre_reembolsoEdit" name="nombre_reembolsoEdit" id="nombre_reembolsoEdit">
                        <small class="text-danger"></small>
                    </div>
                `);
                if ($('#idreembolsoEdit')[0] && $('#idreembolsoEdit')[0].tomselect) {
                    $('#idreembolsoEdit')[0].tomselect.destroy();
                }

                // Inicializar TomSelect
                tomSelectUsuarioColaborador = new TomSelect('#idreembolsoEdit', {
                    options: colaboradoresResponsableArray,
                    valueField: 'value',
                    labelField: 'label',
                    searchField: 'label'
                });

                // Evento para actualizar el input nombre_reembolsoEdit
                tomSelectUsuarioColaborador.on('change', function(value) {
                    const selectedOption = tomSelectUsuarioColaborador.options[value];
                    if (selectedOption) {
                        $('#nombre_reembolsoEdit').val(selectedOption.label);
                    }
                });
            }
            tomSelectUsuarioCXP.setValue(row.responsable); // Asigna el valor si la opción existe
            tomSelectUsuarioCXP.disable(); // Inhabilita el select después de asignar el valor

            $('#depto').val(row.iddeptos.split(",")).trigger('change').prop("disabled", row.responsable != null );
            
            $('#monto').val(formatNumberWithCommas(row.monto));
            $('#titulo-modal').html('EDITAR CAJA CHICA #'+idCaja );
            $("#modal_add_caja").modal();
        
        });
        function formatNumberWithCommas(number) {
            return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }
    // Fin Editar Caja

    // Editar Documentos
        function toggleInputFile(selectedValue) {
            var inputFileDiv = document.getElementById('inputFile');
            var motivoDocDiv = document.getElementById('motivo_doc');
            
            if (selectedValue == 3) {
                inputFileDiv.style.display = 'none';
                motivoDocDiv.classList.remove("col-lg-6");
                motivoDocDiv.classList.add("col-lg-12");

                crrfile3.removeAttribute('required');
            } else {
                inputFileDiv.style.display = 'block';
                motivoDocDiv.classList.remove("col-lg-12");
                motivoDocDiv.classList.add("col-lg-6");

                crrfile3.setAttribute('required', 'true');
            }
        }

        let valores = $(this).data('value');
        $("#detalle").on("click", ".edit_doc", function(e){
            var edoc_form = document.getElementById("edoc_form");
                edoc_form.reset();
                validarEditarDoc.resetForm();
                $("#edoc_form .has-error").removeClass("has-error");


            $('.motivo_documento').val(null).trigger('change');

            var btnEdoc = $(this).val();
            var ndocname = document.getElementById("ndocname");
            ndocname.value = btnEdoc;

            valores = $(this).data('value');

            $("#docs").modal('toggle');
            $("#modal_edit_doc").modal();
        });

        $.validator.addMethod("pdfOnly", function(value, element) {
            if (element.files.length > 0) {
                var fileExtension = element.files[0].name.split('.').pop().toLowerCase();
                return fileExtension === 'pdf';
            }
            return true;
        }, "El archivo debe ser un documento PDF.");

        $.validator.addMethod("validWindowsFilename", function(value, element) {
            // Nombres reservados en Windows
            var reservedNames = /^(CON|PRN|AUX|NUL|COM[1-9]|LPT[1-9])$/i;

            // Caracteres no permitidos en nombres de archivos de Windows
            var invalidChars = /[<>:"/\\|?*\x00-\x1F]/;

            // No debe terminar con un espacio o un punto
            var invalidEnding = /[. ]$/;

            // Validar que el nombre no esté reservado
            if (reservedNames.test(value)) {
                $.validator.messages.validWindowsFilename = "El nombre de archivo no puede ser un nombre reservado del sistema (por ejemplo, CON, PRN, AUX, NUL, COM1, LPT1).";
                return false;
            } 

            // Validar que no contenga caracteres no permitidos
            else if (invalidChars.test(value)) {
                $.validator.messages.validWindowsFilename = "El nombre de archivo contiene caracteres no permitidos: \\ / : * ? \" < > |.";
                return false;
            } 

            // Validar que no termine con un espacio o un punto
            else if (invalidEnding.test(value)) {
                $.validator.messages.validWindowsFilename = "El nombre de archivo no puede terminar con un espacio o un punto.";
                return false;
            }

            // Validar que solo contenga letras, números, espacios, guion bajo, guion medio y acentos
            else if (!/^[a-zA-Z0-9 _\-áéíóúÁÉÍÓÚñÑüÜ]+$/.test(value)) {
                $.validator.messages.validWindowsFilename = "El nombre de archivo solo puede contener letras, números, espacios, guion bajo (_), guion medio (-) y acentos.";
                return false;
            }

            return true;
        }, "Nombre de archivo no válido.");

        validarEditarDoc = $('#edoc_form').validate({
            rules: {
                ndocname: {
                    required: true,
                    minlength: 1,
                    maxlength: 250,
                    validWindowsFilename: true
                },
                motivo_documento: {
                    required: true
                },
                crrfile3: {
                    required: true,
                    pdfOnly: true
                },
                observacion: {
                    required: true
                }
            },
            messages: {
                ndocname: {
                    required: "Este campo es obligatorio. Renombre el archivo.",
                    minlength: "El nombre del archivo debe tener al menos 1 carácter.",
                    maxlength: "El nombre del archivo no puede tener más de 250 caracteres."
                },
                motivo_documento: {
                    required: "Este campo es obligatorio. Seleccione un motivo."
                },
                crrfile3: {
                    required: "Este campo es obligatorio. Adjunte un archivo PDF.",
                    pdfOnly: "El archivo debe ser un documento PDF."
                },
                observacion: {
                    required: "Este campo es obligatorio. Ingrese una observación.",
                    maxlength: "Solo se permiten 250 caracteres."
                }
            },
            errorPlacement: function(error, element) {
                error.addClass('text-danger');
                error.appendTo(element.closest('.form-group').find('small'));
            },
            submitHandler: function(form) {
                info = new FormData($(form)[0]);

                var dataValue = valores;
                var values = dataValue.split(',');
                var ubicacion = values[0];
                var idDoc = values[1];
                var estatusDoc = values[2];

                info.append("ubicacion", ubicacion);
                info.append("idDoc", idDoc);
                info.append("estatusDoc", estatusDoc);

                link_post= "Cajas_ch/edit_doc";
                modal = $("#modal_edit_doc");
                $("#confirm").modal();
            }
        });
    // Fin Editar Documentos
    
    // Eliminar Documento
        let valoresEliminar = $(this).data('value');
        $("#detalle").on("click", ".destroy_doc", function(e){
            var eliminardoc_form = document.getElementById("eliminardoc_form");
            eliminardoc_form.reset();
            validarEliminarDoc.resetForm();
            $("#eliminardoc_form .has-error").removeClass("has-error");
            
            var btnEdoc = $(this).val();
            var docEliminarName = document.getElementById("docEliminarName");
            docEliminarName.value = btnEdoc;
            
            valoresEliminar = $(this).data('value');
            
            $("#modal_delete_doc").modal();
            $("#docs").modal('toggle');
        });

        validarEliminarDoc = $('#eliminardoc_form').validate({
            rules: {
                docEliminarName: {
                    required: true,
                },
                motivo_documento_delete: {
                    required: true
                },
                observacion: {
                    required: true,
                    maxlength: 250
                },
            },
            messages: {
                docEliminarName: {
                    required: "Este campo es obligatorio.",
                },
                motivo_documento_delete: {
                    required: "Este campo es obligatorio. Seleccione un motivo."
                },
                observacion: {
                    required: "Este campo es obligatorio. Ingrese una observación.",
                    maxlength: "Solo se permiten 250 caracteres."
                }
            },
            errorPlacement: function(error, element) {
                error.addClass('text-danger');
                error.appendTo(element.closest('.form-group').find('small'));
            },
            submitHandler: function(form) {
                info = new FormData($(form)[0]);

                var dataValueDelete = valoresEliminar;
                var valuesDelete = dataValueDelete.split(',');
                var ubicacion = valuesDelete[0];
                var idDoc = valuesDelete[1];
                var idCaja = valuesDelete[2];
                var estatus = valuesDelete[3];

                info.append("ubicacion", ubicacion);
                info.append("idDoc", idDoc);
                info.append("idCaja", idCaja);
                info.append("estatus", estatus);

                link_post= "Cajas_ch/delete_doc";
                modal = $("#modal_delete_doc");
                $("#confirm").modal();
            }
        });
    // Fin Eliminar Documento

    // APROBAR
        $("#detalle").on("click", ".aprobar_btn", function(e) {
            $("#error span").remove();
            var link_post = "Cajas_ch/aprobar_documentos";
            var $this = $(this);
            var dataValue = $this.data('value');
            var values = dataValue.split(',');
            var ubicacion = values[0];
            var idDoc = values[1];
            var idCaja = values[2];
            var estatusDoc = values[3];

            var formData = new FormData();
            formData.append("idDoc", idDoc);
            formData.append("ubicacion", ubicacion);
            formData.append("idCaja", idCaja);
            formData.append("estatusDoc", estatusDoc);

            var msj = '#btns_msj_' + idDoc;
            // console.log(msj);

            $.ajax({
                url: '<?= base_url() ?>index.php/Cajas_ch/aprobar_documentos',
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                cache: false,
                dataType: 'json',
                success: function(data) {
                    if (data.res == true) {
                        table_cajas.ajax.reload();
                        $("#docs").modal('toggle');
                    } else {
                        $(msj).append('<span id="error" style="font-size: 12px; padding: 1.5px; background-color: #f8d7da; color: #762129">'+data.data+'</span>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error en la solicitud AJAX: ", error);
                }
            });
        });
    // FIN APROBAR

    // RECHAZAR
        $("#detalle").on("click", ".rechazar_btn", function(e) {
            $("#error span").remove();
            var link_post = "Cajas_ch/aprobar_documentos";
            var $this = $(this);
            var dataValue = $this.data('value');
            var values = dataValue.split(',');
            var ubicacion = values[0];
            var idDoc = values[1];
            var idCaja = values[2];
            var estatusDoc = values[3];

            var formData = new FormData();
            formData.append("idDoc", idDoc);
            formData.append("ubicacion", ubicacion);
            formData.append("idCaja", idCaja);
            formData.append("estatusDoc", estatusDoc);

            var msj = '#btns_msj_' + idDoc;
            // console.log(msj);
            $(msj).find('#error').remove();

            $.ajax({
                url: '<?= base_url() ?>index.php/Cajas_ch/rechazar_documentos',
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                cache: false,
                dataType: 'json',
                success: function(data) {
                    if (data.res == true) {
                        table_cajas.ajax.reload();
                        $("#docs").modal('toggle');
                    } else {
                        $(msj).append('<span id="error" style="font-size: 12px; padding: 1.5px; background-color: #f8d7da; color: #762129">'+data.data+'</span>');
                    }
                    // console.log(data);
                },
                error: function(xhr, status, error) {
                    console.error("Error en la solicitud AJAX: ", error);
                }
            });
        });
    // FIN RECHAZAR

    function validarCampos(element) {
        $(element).valid();
    }

    function peticion(){
        if(idCaja !== 0){
            info.append("idcaja",idCaja);  
        }

        $.ajax({
            url: url + link_post,
            data: info,
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            method: 'POST',
            type: 'POST', // For jQuery < 1.9
            success: function(data){
                switch(data.res){
                    case 1:
                        table_cajas.ajax.reload(null, false);
                        $("#confirm").modal('toggle');
                        modal.modal('toggle');
                        break;
                    case 2:
                        alert("YA HAY UN DOCUMENTO CON ESTE NOMBRE");
                        $("#confirm").modal('toggle');
                        modal.modal('toggle');
                        break;
                    case 3:
                        alert("EL NOMBRE DEL DOCUMENTO ES EL MISMO");
                        $("#confirm").modal('toggle');
                        break;
                    case 4:
                        alert("NO HA REALIZADO NINGUN CAMBIO PARA EL REGISTRO");
                        $("#confirm").modal('toggle');
                        break;
                    
                    default:
                        alert("NO SE HA PODIDO COMPLETAR LA SOLICITUD");
                        $("#confirm").modal('toggle');
                        modal.modal('toggle');
                        break;
                }
            },error: function( ){
                alert("Algo salio mal, recargue su página.");
                $("#confirm").modal('toggle');
            }
        });
    }
   

    function recargar() { // ACTUALIZAR CAJA
        var button = document.getElementById("refreshButton");
        
        button.disabled = true;

        setTimeout(function() {
            button.disabled = false;
        }, 10000);

        verNotificaciones = false;
        table_cajas.ajax.reload(null, false);
    }

</script>
<?php
    require("footer.php");
?>