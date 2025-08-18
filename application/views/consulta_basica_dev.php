<style>
    #divDetalleTablePagos {
        max-height: 400px;
        overflow-y: auto;        
    }
    #divDetalleTablePagos table {
        width: 100%; /* Ancho de la tabla */
        border-collapse: collapse; /* Colapsar bordes de la tabla */
    }
    #divDetalleTablePagos th, #divDetalleTablePagos td {
        border: 1px solid #ddd; /* Bordes de celdas */
        padding: 8px; /* Espaciado interno */
    }
    #divDetalleTablePagos th {
        background-color: #f2f2f2; /* Color de fondo de encabezados */
    }
    /**
     * @uthor Efrain Martine Muñoz | Fecha : 27/03/2025
     * Se agregan estilo para vizualizar los contenedores de la imagen de mejor manera
     * INICIO
     */
    #contenedorImagenSubida {
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        width: 100%;
        height: 70vh;
        min-height: 300px; 
        border: 2px solid #ccc; 
        margin-top: 10px;
        margin-bottom: 10px;
        padding: 10px; 
        background: rgba(0, 0, 0, 0.7);
    }

    #contenedorImagenSubida img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }


    #contenedorImagenEditar {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: auto;
        min-height: 300px;
        border: 2px solid #ccc; 
        margin-top: 10px;
        margin-bottom: 10px;
        padding: 10px; 
        background: rgba(0, 0, 0, 0.7);
    }
    
    #imagenActualAc {
        max-width: 100%; 
        max-height: 70vh; 
        display: block; 
        margin: auto; 
        object-fit: contain; 
    }
    /**
    FIN
    */

    /**
    * INICIO FECHA: 16-ABRIL-2025 | @author Efrain Martinez Muñoz <programador.analista38@ciudadmaderas.com>
    * Se agrega el CSS que permite visualizar el documento 
    */
    .visualizarArchivoDocumento {
        width: 100%;         
        height: auto;
        min-height: 300px;      
        overflow: hidden;
        position: relative;
        border: 1px solid #ccc;
        background-color: rgb(123, 122, 122);
        text-align: center;
        margin-bottom: 5px;
    }

    .visualizarArchivoDocumento img {
        position: absolute;
        transform-origin: center; /* FECHA : 07-MAYO-2025 ||@author Efrain Martinez <programador.analista38@ciudadmaderas.com>*/ 
        max-height: 100%;
        height: auto;
        width: auto;         
        margin: 0 auto;
        max-width: 100%;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%) scale(1);
        cursor: zoom-in;
        user-select: none;
    }
    /**
    * FIN FECHA : 16-ABRIL-2025 | @author Efrain Martinez Muñoz <programador.analista38@ciudadmaderas.com>
    */

</style>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">SOLICITUD <b>#<?php echo $idsolicitud; ?></b></h4>
</div>
<div class="modal-body">
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li <?= $notificacion == 0 ? 'class="active"' : '' ?>><a data-toggle="tab" href="#info">INFORMACIÓN</a></li>
            <li><a data-toggle="tab" href="#docs">DOCUMENTOS</a></li>
            <li><a data-toggle="tab" href="#docs_desc">DOCS. DISPONIBLES</a></li>
            <li <?= $notificacion > 0 ? 'class="active"' : '' ?>><a data-toggle="tab" href="#obser">OBSERVACIONES</a></li>
            <?php if ($this->session->userdata("inicio_sesion")["depto"] == 'CONTRALORIA'){ ?> <!-- INICIO FECHA: 27-FEBRERO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                <li><a data-toggle="tab" href="#obserDep">OBSERVACIONES POR DEPARTAMENTO</a></li>
            <?php } ?> <!-- FIN FECHA: 27-FEBRERO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
            <li><a data-toggle="tab" href="#historial">HISTORIAL</a></li>
            <?php if ($datos_solicitud->idproceso == 30){ ?> 
                <li><a id="tablaPagosTab" data-toggle="tab" href="#tabla_pagos">TABLA PAGOS</a></li>
            <?php } ?>
        </ul>
    </div>
    <div class="tab-content">
        <div id="info" class="tab-pane fade <?= $notificacion == 0 ? 'in active' : '' ?>">
            <div class="row">
                <div class="col-lg-10">
                    <center>
                        <h4><b><?= (isset($datos_solicitud->nombre_proceso) && $datos_solicitud->nombre_proceso != null ? $datos_solicitud->nombre_proceso : '') ?></b></h4>
                        <p class="text-center">(<?= (isset($datos_solicitud->proyecto) && $datos_solicitud->proyecto != null ? $datos_solicitud->proyecto : '--') ?>)</p>
                    </center>
                </div>
                <div class="col-lg-2">

                    <h4><button class="btn btn-danger btn-sm generar_caratula" id="caratula" name="caratula"><i class="fas fa-print"></i></button></h4>

                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <h5 id="fecha_sol" style="text-align: right;">FECHA DE SOLICITUD: <b><?= (isset($datos_solicitud->fechaCreacion) && $datos_solicitud->fechaCreacion != null ? $datos_solicitud->fechaCreacion : '---') ?></b></h5>
                    <h5 id="fecha_sol" style="text-align: right;">SOLICITANTE: <b><?= (isset($datos_solicitud->capturista) && $datos_solicitud->capturista != null ? $datos_solicitud->capturista : '---') ?></b></h5>
                </div>
                <div class="col-lg-12">
                    <h5 id="nombrerec_fac" class="text-justify"><b>NOMBRE TITULAR:</b> <?= (isset($datos_solicitud->requisicion) && $datos_solicitud->requisicion != null ? $datos_solicitud->requisicion : '--') ?></h5>
                    
                </div>
                <!--INICIO FECHA : 16-ABRIL-2025 | @author Efrain Martinez Muñoz <programador.analista38@ciudadmaderas.com>
                    Se agrega la etiqueta del estatus del lote para que aparezca en la caratula de la solicitud-->
                <div class="col-lg-12">
                    <h5 class="text-justify">
                        <b>LOTE:</b> 
                        <?= (isset($datos_solicitud->condominio) && $datos_solicitud->condominio != null ? $datos_solicitud->condominio : '--') .' '. 
                            (!empty($datos_solicitud->estatusLote) 
                                ? '<span class="label ' . 
                                    ((($datos_solicitud->idetiqueta == 6 && $datos_solicitud->tipo_doc == 9) || $datos_solicitud->idetiqueta == 7) 
                                        ? 'label-success' 
                                        : 'label-danger') . 
                                    '"> ' . $datos_solicitud->estatusLote . '</span>' 
                                : '') 
                        ?>
                    </h5>
                </div>
                <!--FIN 16-ABRIL-2025 | @author Efrain Martinez Muñoz <programador.analista38@ciudadmaderas.com> -->
                <div class="col-lg-12"> <!-- FECHA: 29-ABRIL-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                    <h5 class="text-justify"><b>REF. LOTE:</b> <?= (isset($datos_solicitud->referencia) ? $datos_solicitud->referencia : '--') ?>
                </div> <!-- FECHA: 29-ABRIL-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                <div class="col-lg-12">
                    <h5 class="text-justify"><b>ETAPA: </b> <?= ($datos_solicitud->etapa != null ? $datos_solicitud->etapa : '') ?> </h5>
                </div>
                <div class="col-lg-12">
                    <h5 class="text-justify"><b>No DE SOLICITUD:</b> <?php echo $idsolicitud; ?></h5>
                </div>

                <div class="col-lg-12">
                    <h5 class="text-justify"><b>CUENTA CONTABLE:</b> <?= (isset($datos_solicitud->homoclave) && $datos_solicitud->homoclave != null ? $datos_solicitud->homoclave : '--'); ?></h5>
                </div>
                <div class="col-lg-12" style="display: <?= (isset($datos_solicitud->orden_compras) && $datos_solicitud->orden_compras = !null ? 'block' : 'none') ?>">
                    <h5 class="text-justify"><b>CUENTA ORDEN:</b> <?= (isset($datos_solicitud->orden_compra) && $datos_solicitud->orden_compra != null ? $datos_solicitud->orden_compra : '--') ?></h5>
                </div>
                <div class="col-lg-12">
                    <h5 class="text-justify"><b>EMPRESA:</b> <?= (isset($datos_solicitud->nempresa) && $datos_solicitud->nempresa != null ? $datos_solicitud->nempresa : '--'); ?></h5>
                </div>
                <div class="col-lg-12">
                    <h5 class="text-justify"><?php if ($datos_solicitud->idproceso == 30){?><b>CANTIDAD TOTAL: </b><?php }else{?><b>CANTIDAD: </b><?php } ?> $<?= (isset($datos_solicitud->cantidad) && $datos_solicitud->cantidad != null ? number_format($datos_solicitud->cantidad, 2) . ' (' . strtolower(convertir(number_format($datos_solicitud->cantidad, 2))) . ' )' : '--') ?> <?= isset($datos_solicitud->intereses) ? "INTERES AGREGADO: <b>" . number_format($datos_solicitud->cantidad, 2, ".", ",") . "</b>" : "" ?></h5>
                </div>
                <?php
                    if($datos_solicitud->idproceso == 30){
                ?>
                    <div class="col-lg-12">
                        <h5 class="text-justify"><b>CANTIDAD PARCIALIDAD: </b>$<?= (isset($datos_solicitud->montoParcialidadCaratula) && $datos_solicitud->montoParcialidadCaratula != null ? number_format($datos_solicitud->montoParcialidadCaratula, 2) . ' (' . strtolower(convertir(number_format($datos_solicitud->montoParcialidadCaratula, 2))) . ' )' : '--') ?> <?= isset($datos_solicitud->intereses) ? "INTERES AGREGADO: <b>" . number_format($datos_solicitud->montoParcialidadCaratula, 2, ".", ",") . "</b>" : "" ?></h5>
                    </div>
                <?php
                    }
                ?>
                <div class="col-lg-12">
                    <h5 class="text-justify"><b>FECHA ENTREGA: </b><?= $datos_solicitud->fecelab ?></h5>
                </div>
                <?PHP
                    if( $datos_solicitud->idproceso  == 30 ){
                ?>
                    <div class="col-lg-12">
                        <h5 class="text-justify"><b>FECHA FINAL: </b><?= ($datos_solicitud->fecha_fin != null ? $datos_solicitud->fecha_fin : 'SIN DEFINR')?></h5>
                    </div>
                    <div class="col-lg-12">
                        <h5 class="text-justify"><b>TOTAL PARCIALIDADES: </b> <?= ($datos_solicitud->numeroPagos != null ? $datos_solicitud->numeroPagos : '') ?> </h5>
                    </div>
                    <div class="col-lg-12">
                        <h5 class="text-justify"><b>PARCIALIDAD ACTUAL: </b> <?= ($datos_solicitud->pagoActual != null ? $datos_solicitud->pagoActual : '') ?> </h5>
                    </div>
                <?PHP
                }
            ?>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <p class="text-justify"><b>OBSERVACIONES: </b><?= (isset($datos_solicitud->justificacion) && $datos_solicitud->justificacion != null ?  $datos_solicitud->justificacion : '--') ?></p>
                </div>
            </div>
            <?PHP
                if( $datos_adicionales->num_rows() > 0 ){
                    $datos_adicionales = $datos_adicionales->row();
            ?>
                <hr />
                <div class="row">
                    <div class="col-lg-4">
                        <h5 class="text-justify"><b>COSTO DE LOTES: </b><?= $datos_adicionales->costo_lote ?></h5>
                    </div>
                    <div class="col-lg-4">
                        <h5 class="text-justify"><b>SUPERFICIE: </b><?= $datos_adicionales->superficie ?></h5>
                    </div>
                    <div class="col-lg-4">
                        <h5 class="text-justify"><b>PRECIO M2: </b><?= $datos_adicionales->preciom ?></h5>
                    </div>
                    <div class="col-lg-6">
                        <h5 class="text-justify"><b>PREDIAL: </b><?= $datos_adicionales->predial ?></h5>
                    </div>
                    <div class="col-lg-6">
                        <h5 class="text-justify"><b>IMPORTE APORTADO: </b><?= $datos_adicionales->importe_aportado ?></h5>
                    </div>
                    <div class="col-lg-6">
                        <h5 class="text-justify"><b>PENALIZACIÓN %: </b><?= $datos_adicionales->por_penalizacion ?></h5>
                    </div>
                    <div class="col-lg-6">
                        <h5 class="text-justify"><b>PENALIZACIÓN $: </b><?= $datos_adicionales->penalizacion ?></h5>
                    </div>
                    <div class="col-lg-12">
                        <h5 class="text-justify"><b>MANTENIMIENTO: </b><?= $datos_adicionales->mantenimiento ?></h5>
                    </div>
                </div>

                <?php if($datos_solicitud->proceso_motivo == 1){ ?>
                    <hr />
                    <div class="row">
                        <div class="col-lg-12">
                            <h5 class="text-justify"><b>MOTIVO RESCISIÓN: </b><?= $datos_adicionales->motivo ? $datos_adicionales->motivo : 'NA' ?></h5>
                        </div>
                        <div class="col-lg-12">
                            <h5 class="text-justify"><b>DETALLE MOTIVO: </b><?= $datos_adicionales->detalle_motivo ? $datos_adicionales->detalle_motivo : 'NA' ?></h5>
                        </div>
                    </div>
                <?php } ?>

            <?PHP
                }
            ?>
            <hr />
            <div class="row">
                <div class="col-lg-12">
                    <table class="table table-bordered table-hover">
                        <tr>
                            <td><b>NOMBRE BENEFICIARIO</b></th>
                            <td><?= isset($datos_solicitud->nomprove) ? $datos_solicitud->nomprove : '' ?> </td>
                        </tr>
                        <tr>
                            <td><b>MODO DE PAGO A PROVEEDOR:</b></td>
                            <td><?= isset($datos_solicitud->metoPago) ? $datos_solicitud->metoPago : '' ?></td>
                        </tr>
                    <?PHP if( in_array( $datos_solicitud->metoPago, ['TEA', 'MAN'] ) ){
                    ?>
                        <tr>
                            <td><b>BANCO:</b></td>
                            <td><?= isset($datos_solicitud->nomban) ? $datos_solicitud->nomban : "---" ?></td>
                        </tr>
                        <tr>
                            <td><b>NUM. CUENTA:</b></td>
                            <td><?= isset($datos_solicitud->cuenta) ? $datos_solicitud->cuenta : "---" ?></td>
                        </tr>
                    <?PHP
                    } ?>
                    </table>
                </div>
            </div>
        </div>
        <div id="obser" class="tab-pane fade <?= $notificacion == 1 ? 'in active' : '' ?>">
            <div class="row">
                <div class="col-lg-12">
                    <div id="chat" class="form-control chat"></div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="input-group">
                        <input type="text" class="form-control" id="comentarios" name="comentarios" required> <!-- FECHA: 27-FEBRERO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                        <div class="input-group-btn"> <!-- FECHA: 27-FEBRERO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                            <button type="button" class="btn btn-primary" id="agregar_comentario"><i class="fas fa-comments"></i> COMENTAR</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="obserDep" class="tab-pane fade"> <!-- INICIO FECHA: 27-FEBRERO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
            <div class="row">
                <div class="col-lg-12">
                    <div id="chatDep"></div>
                </div>
            </div>
        </div> <!-- FIN FECHA: 27-FEBRERO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
        <div id="docs" class="tab-pane fade ">
            <div class="row">
                <div class="col-lg-12">
                    <label>DOCUMENTOS</label>
                    <form id="subir_documentos" method="post" action="#" onkeydown="return event.key != 'Enter';">
                        <input type="hidden" id="idsolicitud" name="idsolicitud" value='<?php echo $idsolicitud; ?>'>
                        <div class="row" id="documentos"></div>
                    </form>
                </div>
                <!-- @uthor Efrain Martinez Muñoz | Fecha : 27/03/2025-->
                <!-- Contendra el espacio en el cual se podrá subir la imagen del lote si qesuqe hace falta-->
                <div class="col-lg-12" id="imagen_lote" style="display: none;">
                    <label>ESTATUS LOTE</label>
                    <form id="subirImagen" method="post" action="#" enctype="multipart/form-data" onkeydown="return event.key != 'Enter';">
                        <input type="hidden" id="idsolicitud" name="idsolicitud" value="<?php echo $idsolicitud; ?>">
                        <div class="input-group">
                            <input type="file" class="form-control d-block mb-8" id="subirImagenDoc" onchange="previsualizarImagenConsultaDev(event)" required>
                            <div class="input-group-btn">
                                <button type="submit" class="btn btn-danger guardaImagen"><i class="fas fa-upload"></i></button>
                            </div>
                        </div>
                        <div id="contenedorImagenSubida" class="d-block mt-8" style="display: none;">
                            <img id="imagenSubida" src="" alt="Imagen actual" class="img-fluid m-3">
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div id="docs_desc" class="tab-pane fade ">
            <div class="row">
                <div class="col-lg-12">
                    <input type="hidden" id="idsolicitud" name="idsolicitud" value='<?php echo $idsolicitud; ?>'>
                    <div class="row">
                        <div class="col-lg-12" id="descargar_all"></div>
                    </div>
                    <div class="row">
                        <div class='col-lg-12' id="descarga_doc"></div>
                    </div>
                    <!-- @uthor Efrain Martinez Muñoz | Fecha : 27/03/2025-->
                    <!-- En este espacio, si la solicitud ya tiene una imagen se mostraran botones para ver, descargar y editar la imagen cargada-->
                    <div class="row">
                        <div class='col-lg-12' id="descargaImagen"></div>
                </div>
                    <div class="col-lg-12" id="imagenMos"></div> 
                    <div class="row" id="editarImagenCon" style="display: none;">
                        <div class='col-lg-12'>
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Editar Imagen</h5>
                                </div>
                                <div class="card-body">
                                    <input type="file" id="nuevaImagen" class="form-control d-block mb-8" onchange="previsualizarImagenConsultaDev(event)">
                                    <div id="contenedorImagenEditar" class="d-block mt-8">
                                        <img id="imagenActualAc" src="" alt="Imagen actual" class="img-fluid">
                                    </div>
                                </div>
                                <div class="card-footer text-center">
                                    <button type="button" class="btn btn-danger" onclick="cancelarEdit()">Cancelar</button>
                                    <button type="button" class="btn btn-success" onclick="guardarNuevaImagen()">Actualizar imagen</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="historial" class="tab-pane fade <?= $notificacion > 1 ? 'in active' : '' ?>">
            <div class="row">
                <div class="col-lg-6">
                    <h4>HISTORIAL DE MOVIMIENTOS</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div id="chat" class="form-control chat">
                        <?php /** INICIO FECHA: 14-MARZO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                            echo $movimientos;
                        ?> <!-- FIN FECHA: 14-MARZO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                    </div>
                </div>
            </div>
        </div>
        <div id="tabla_pagos" class="tab-pane fade">
            <div class="row">
                <div class="col-lg-6">
                    <h4>PLAN DE PAGOS</h4>
                </div>
            </div>
            <div class="row" id="detalleTablaPagos">
            </div>
        </div>

    </div>
</div>
<script>
    var num_comentarios = 0;
    
    $(document).ready(function() {
        recargar_conversacion();
        documentos();
        descarga_dosc();
        cargarConversacionDepartamento(); /** FECHA: 27-FEBRERO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
    });
    /**
     * @uthor Efrain Martinez Muñoz | Fecha : 27/03/2025
     * Se crean variables que se utilizaran para validar el estatus del lote y si la solicitud ya cuenta con imagen.
     */
    var iniSesion = <?php echo json_encode($this->session->userdata("inicio_sesion")['id']); ?>;
    var num_comentarios = 0;
    var tipo_doc = <?= $datos_solicitud->tipo_doc ? $datos_solicitud->tipo_doc: 0 ?>;
    var idDocumento = <?= $datos_solicitud->idDocumento ? $datos_solicitud->idDocumento : 0 ?>;
    var idetiqueta = <?= $datos_solicitud->idetiqueta ? $datos_solicitud->idetiqueta : 0 ?>;
    var idUsuario = <?= $datos_solicitud->idusuario?>;
    
    
    $("ul.nav-tabs > li > a").on("shown.bs.tab", function(e) {
        $("#chat").scrollTop($("#chat")[0].scrollHeight);
    });

    function recargar_conversacion() {
        
        $.post(url + "Consultar/getConversacion", {
            idsolicitud: <?= $idsolicitud ?>
        }).done(function(data) {
            data = JSON.parse(data);
            if (data.comentarios > num_comentarios) {
                $.each(data.observaciones, function(i, v) {
                    $('#chat').append(v);
                });
                num_comentarios = data.comentarios;
            } else {
                for (var i = num_comentarios + 1; i < data.comentarios; i++) {
                    $('#chat').append(data.observaciones[i]);
                }
            }
            setTimeout(function() {
                $("#chat").scrollTop($("#chat")[0].scrollHeight);
            }, 1000);
        });
    }

    function cargarConversacionDepartamento() { /** INICIO FECHA: 27-FEBRERO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
        $.post(url + "Consultar/getConversacionDepartamento", {
            idsolicitud: <?= $idsolicitud ?>
        }).done(function(data) {
            data = JSON.parse(data);
            
            // Limpiar el chat antes de agregar nuevos mensajes
            $('#chatDep').empty();

            $.each(data.observaciones, function(i, v) {
                $('#chatDep').append(v);
            });

            $("#chatDep").scrollTop($("#chatDep")[0].scrollHeight);
        });
    } /** FIN FECHA: 27-FEBRERO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/

    function documentos() {
        
        var link =  <?= $tipo_consulta == "POST_DEV" ? 2 : 0 ?> > 0 ? 'Consultar/doc_sol_post' : 'Consultar/documentos_sol';

        $.post(url + link , {
            idsolicitud: <?= $idsolicitud ?>
        }).done(function(data) {
            data = JSON.parse(data);
            $('#documentos').html("");
            if (data != "") {
                $.each(data, function(i, value) {
                    if (consulta != 0) {
                        $('#documentos').append('<div class="col-lg-12 form-group"> <label>"' + value.ndocumento + '" </label><label style="color:white;"> *</label><label style="color:red;">' + value.req + '</label></div>');

                    } else {
                        $('#documentos').append('<div class="col-lg-12 form-group"> <label>"' + value.ndocumento + '" </label><label style="color:white;"> *</label><label style="color:red;">' + value.req + '</label><div class="input-group"><input type="file" class="form-control"  name="' + value.iddocumentos + '"  ><div class="input-group-btn"><button type="submit" class="btn btn-danger cargar_complemento"><i class="fas fa-upload"></i></button></div></div></div>');
                    }
                });
            }
        });
        /**
         * @uthor Efrain Martinez Muñoz | Fecha : 27/03/2025
         * Se valida si la solicitud debe llevar imagen y no tiene, ademas tambien se valida si el usuario logueado se encuentra en el arreglo de la variable rol entonces
         * se mostrara el espacio para que se cargar la imagen.
         *  
         */
        if (idetiqueta === 6 && tipo_doc === 0 && <?= in_array($this->session->userdata("inicio_sesion")['rol'], explode(',', $datos_solicitud->rol)) ? 'true' : 'false'?>){
            $('#imagen_lote').show();
        }

    }



    function descarga_dosc() {
        var contDocumentos = 0;
        var link =  <?= $tipo_consulta == "POST_DEV" ? 2 : 0 ?> > 0 ? "Post_Devoluciones/descargar_docs" : "Devoluciones_Traspasos/descargar_docs";
        $('#descargar_all').html('');
        $('#descargaImagen').html('');
        arra = [];
        $.post(url + link , {
            idsolicitud: <?= $idsolicitud ?>
        }).done(function(data) {
            data = JSON.parse(data);
            //  varjson["data"] = array();
            $('#descarga_doc').html("");
            if (data != '') {

                $.each(data, function(i, value) {
                    arra.push({url:value.rutaMasiva, nombre:value.rutaBase});
                    contDocumentos++;
                    if(<?= $tipo_consulta == "POST_DEV" ? 2 : 0 ?> > 0){
                        // $("#descarga_doc").append("<div class='col-lg-9'><h5>" + (i + 1) + ".- " + value.documento + "</h5></div><div class='col-lg-3'><div class='btn-group' role='group' aria-label='Basic example'> <a href='" + value.ruta + "' target='_blank' class='btn btn-sm btn-primary archivos'><i class='fas fa-download'></i></a>"+
                        $("#descarga_doc").append("<div class='col-lg-9'><h5>" + (i + 1) + ".- " + value.documento + "</h5></div><div class='col-lg-3'><div class='btn-group' role='group' aria-label='Basic example'> <a href='#' onclick='forceDownloadFile(&quot;"+value.ruta+"&quot;)' class='btn btn-sm btn-primary archivos'><i class='fas fa-download'></i></a>"+
                                            (value.idproceso == 18 ? "<button title='Regresar documento' name= '" + value.iddocumentos_solicitud + "' onclick='myFunction(" + value.iddocumentos_solicitud + " , " + value.idsolicitud + " )' class='btn btn-sm btn-warning' style='display: " + (consulta != 1 ? 'block' : 'none') + "'><i class='fas fa-undo-alt'></i></button><button title='Borrar documento' name= '" + value.iddocumentos_solicitud + "' onclick='myFunctionBorrar(" + value.iddocumentos_solicitud + " , " + value.idsolicitud + " )' class='btn btn-sm btn-danger' style='display: " + (consulta != 1 ? 'block' : 'none') + "'><i class='fas fa-trash-alt'></i></i></button>":"")+"</div> </div>");
                    }
                    else{
                            //INICIO FECHA : 16-ABRIL-2025 | @author Efraina Martinez Muñoz <programador.analista38@ciudadmaderas.com>
                            // Se agrega el boton para poder vizualizar los archivos sin necesidad de descargarlos.
                            $("#descarga_doc").append(`
                                <div class='col-lg-8'>
                                    <h5>${i + 1}.- ${value.documento}</h5>
                                </div>
                                <div class='col-lg-4'>
                                    <div class='btn-group' role='group' aria-label='Basic example'>
                                        <button 
                                            title='Ver documento' 
                                            data-url='${value.rutaLocal}'
                                            data-urlBase = '${value.rutaBase}'
                                            data-doc='${i + 1}'
                                            onclick='verDocumento(this)' 
                                            class='btn btn-sm btn-primary' 
                                            id='verDoc${i + 1}'>
                                            <i class='fas fa-eye'></i>
                                        </button>
                                        <button 
                                            title='Cerrar documento' 
                                            data-url='${value.rutaLocal}'
                                            data-doc='${i + 1}'
                                            onclick='cerrarDocumento(this)' 
                                            class='btn btn-sm btn-danger' 
                                            id='cerrarDoc${i + 1}'
                                            style='display: none; border-top-left-radius: 4px; border-bottom-left-radius: 4px; margin-bottom: 0;'>
                                            <i class='fas fa-eye-slash'></i>
                                        </button>
                                        <a title='Descargar documento'  href='${value.ruta}' target='_blank' class='btn btn-sm btn-success archivos'>
                                            <i class='fas fa-download'></i>
                                        </a>
                                        <button 
                                            title='Regresar documento' 
                                            name='${value.iddocumentos_solicitud}' 
                                            onclick='myFunction(${value.iddocumentos_solicitud}, ${value.idsolicitud})' 
                                            class='btn btn-sm btn-warning' 
                                            style='display: ${consulta != 1 ? 'block' : 'none'}'>
                                            <i class='fas fa-undo-alt'></i>
                                        </button>
                                        <button 
                                            title='Borrar documento' 
                                            name='${value.iddocumentos_solicitud}' 
                                            onclick='myFunctionBorrar(${value.iddocumentos_solicitud}, ${value.idsolicitud})' 
                                            class='btn btn-sm btn-danger' 
                                            style='display: ${consulta != 1 ? 'block' : 'none'}'>
                                            <i class='fas fa-trash-alt'></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-lg-12" id="documentoMos${i + 1}" style='display: none'>
                                    <div id="mostrarDocumento${i + 1}" style="text-align: center;"></div>
                                </div>
                            `);
                            //FIN FECHA : 16-ABRIL-2025 | @author Efrain Martinez Muñoz <programador.analista38@ciudadmaderas.com>
                        }

                });
    
                if (arra.length < 0) {
                    $('.descall').css('display', "none");
                } else {
                    $('.descall').css('display', "none");
                }
                
                $('#descargar_all').append('<button title ="Descargar todos los archivos" class="btn btn-info btn-sm descall" onclick="downloadAll()"><i class="fas fa-file-archive"></i></button>');
            }
            /**
             * @uthor Efrain Martinez Muñoz | Fecha : 27/03/2025
             * Se valida si ya existe una imagen se mostraran los botones para ver y descaragar la imagen, ademas si el usuario loguedo se encuentra en el arreglo de la variable
             * rol se mostrara el boton para editar la imagen.
             *  
             */

            if (tipo_doc === 9) {
                    contDocumentos++;
                var imageUrl = "<?= base_url('UPLOADS/TEMP/DEVOLUCIONES_TRASPASOS/LOTE/IdUsuario_' . $datos_solicitud->idusuario . '/' . $datos_solicitud->expediente) ?>";
                    var html = "<div class='col-lg-8'><h5> " + contDocumentos + ".- ESTATUS LOTE. </h5></div>" +
                            "<div class='col-lg-4'>" +
                        "<div class='btn-group' role='group' aria-label='Basic example'>" +
                                "<button title='Ver imagen' data-url='" + imageUrl + "' data-doc='" + contDocumentos +"' onclick='verImagen(this)' class='btn btn-sm btn-primary' id='verIma'>" +
                                "<i class='fas fa-eye'></i>" +
                            "</button>" +
                                "<button title='Cerrar imagen'  onclick='cerrarImagen(this)' class='btn btn-sm btn-danger' id='cerrarIma' style='display: none; border-top-left-radius: 4px; border-bottom-left-radius: 4px; margin-bottom: 0;'>" +
                                "<i class='fas fa-eye-slash'></i>" +
                            "</button>" +
                            "<a title='Descargar imagen' href='" + imageUrl + "' download class='btn btn-sm btn-success' target='_blank'>" +
                                "<i class='fas fa-download'></i>" +
                            "</a>";

                if (<?= (in_array($this->session->userdata("inicio_sesion")['rol'], explode(',', $datos_solicitud->rol))) ? 'true' : 'false' ?>) {
                    html += "<button title='Editar imagen'  data-url='" + imageUrl + "' onclick='editarImagen(this)' class='btn btn-sm btn-warning'>" +
                                "<i class='fas fa-pencil-alt'></i>" +
                            "</button>";
                }
                html += "</div></div>";

                $('#descargaImagen').append(html);
            }
        });
    }
    /**
     * INICIO FECHA : 16-ABRIL-2025 | @author Efrain Martinez Muñoz <programador.analista38@ciudadmaderas.com>
     * Función que permite vizualizar los archivos que se encuentran cargados de la solicitud.
     */
    function verDocumento(button) {
        //Se obtienen los datos que enviados al dar clic en el boton "Ver documento"
        var archivoUrl = button.getAttribute('data-url');
        var archivoUrlBase = button.getAttribute('data-urlBase');
        var numDoc = button.getAttribute('data-doc');
        var contenedor = document.getElementById('documentoMos' + numDoc);
        var verDocumento = document.getElementById('verDoc' + numDoc);
        var cerrarDocumento = document.getElementById('cerrarDoc' + numDoc);
        var visualizador = document.getElementById('mostrarDocumento' + numDoc);

        //validación que permite analizar si las variables principales cuentan con datos para poder continuar con la ejecución del codigo
        if (!contenedor || !verDocumento || !cerrarDocumento || !visualizador) {
            console.error(`No se encontraron los elementos necesarios para el documento ${numDoc}`);
            return;
        }
        //Se extrae la extension del archivo para poder darle el tratamiento adecuado
        var extension = archivoUrlBase.split('.').pop().toLowerCase();

        //Si el archivo es algun tipo de imagen entonces entra en esta condicional en la cual se muestra la imagen y se le asignan propiedades de zoom y arrastre.
        if (['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'jfif'].includes(extension)) {
            visualizador.innerHTML = `<div class="visualizarArchivoDocumento" id="contenedorZoom${numDoc}">
                                        <img id="imgZoom${numDoc}" src="${archivoUrl}">
                                    </div>`;

            const img = document.getElementById(`imgZoom${numDoc}`);
            zomMovImagen(img);
        } 
        //Si el archivo es pdf simplemente se muestra dentro de un iframe.
        else if (extension === 'pdf') {
            visualizador.innerHTML = `<iframe src="${archivoUrl}" width="100%" height="500px" style="border: none;"></iframe>`;
        }
        /**
         * INICIO FECHA: 07-MAYO-2025 || @uthor Efrain Martinez <programador.analista38@ciudadmaderas.com>
         * En caso de que el archivo no sea de ningun tipo de los anteriores se genera un parrafo en el cual se indica que el archivo no se puede vizualizar 
         */
        else {
            const tipos = {
                'docx': 'DE <strong>WORD</strong>',
                'xlsx': 'DE <strong>EXCEL</strong>',
                'html': 'DE <strong>HTML</strong>',
                'rar': 'UNA <strong>CARPETA COMPRIMIDA</strong>',
                'zip': 'UNA <strong>CARPETA COMPRIMIDA</strong>',
                'eml': 'DE <strong>EML</strong>'
            };

            const tipoArchivo = tipos[extension] || `DE <strong>${extension.toUpperCase()}</strong>`;

            visualizador.innerHTML = `
                <p>
                    ESTE ARCHIVO ES ${tipoArchivo} POR LO QUE NO SE PUEDE VIZUALIZAR. 
                    <a href="${archivoUrl}" target="_blank">DESCARGAR AQUÍ</a>
                </p>`;
        }
        /**
         * FIN FECHA : 07-MAYO-2025 || @author Efrain Martinez <programador.analista38@ciudadmaderas.com>
         */
        //Oculta el boton de Ver documento y muestra el boton de cerrar documento
        contenedor.style.display = "block";
        verDocumento.style.display = "none";
        cerrarDocumento.style.display = "block";
    }
    /**
     * FIN FECHA : 16-ABRIL-2025 | @author Efrain Martinez Muñoz <programador.analista38@ciudadmaderas.com>
     */

    /**
     * INICIO FECHA : 16-ABRIL-2025 | @author Efrain Martinez Muñoz <programador.analista38@ciudadmaderas.com>
     * Función que permite cerrar el documento al momento que el usuario da clic en el boton de cerrar documento.
     */
    function cerrarDocumento(button) {
        //Se obtinen los datos que son enviados al dar clic al boton de cerrar documento.
        var numDoc = button.getAttribute('data-doc');
        var contenedor = document.getElementById('documentoMos' + numDoc);
        var verDocumento = document.getElementById('verDoc' + numDoc);
        var cerrarDocumento = document.getElementById('cerrarDoc' + numDoc);
        var visualizador = document.getElementById('mostrarDocumento' + numDoc);
        
        //En caso de que algun dato no se encuentre, se muestra el siguiente error y se detiene la ejecución del codigo.
        if (!contenedor || !verDocumento || !cerrarDocumento || !visualizador) {
            console.error(`No se encontraron los elementos necesarios para el documento ${numDoc}`);
            return;
        }

        // Limpiar contenido del visualizador
        visualizador.innerHTML = "";
        
        // Ocultar contenedor y mostrar botón "Ver"
        contenedor.style.display = "none";
        verDocumento.style.display = "block";
        cerrarDocumento.style.display = "none";
    }
    /**
     * FIN FECHA : 16-ABRIL-2025 | @author Efrain Martinez Muñoz <programador.analista38@ciudadmaderas.com>
     */

    /**
     * INICIO FECHA : 19-ABRIL-2025 | @author Efrain Martinez Muñoz <programador.analista38@ciudadmadereas.com>
     * Función que permite agregar a los documentos de tipo imagen la propiedad de zoom y la de arrastre, con la finalidad de permitir al usuario vizualizar de major manera este tipo de archivos.
     */
    function zomMovImagen(imgElement) {
        // Variables utilizadas para manejar zoom y movimiento
        let scale = 1; // Escala inicial de la imagen
        let isDragging = false; // Estado de arrastre (true si se está arrastrando)
        let startX, startY; // Coordenadas donde inicia el arrastre
        let currentX = 0, currentY = 0; // Posición actual del desplazamiento

        const contenedor = imgElement.parentElement; // Se asume que la imagen está centrada dentro de un contenedor

        // Se ejecuta cuando la imagen se carga
        imgElement.onload = () => {
            scale = 1; // Reinicia la escala
            currentX = 0;
            currentY = 0;
            setTransform(); // Aplica transformaciones iniciales
            imgElement.style.cursor = 'zoom-in'; // Cambia el cursor a "zoom-in"
        };

        // Aplica transformación de escala y posición a la imagen
        function setTransform() {
            limitarMovimiento(); // FECHA : 07-MAYO-2025 || @author Efrain Muñoz <programador.analista38@ciudadmaderas.com> || Se llama a la función limitarMovimiento que no permite que la imagen se salga del contenedor.
            imgElement.style.transform = `translate(calc(-50% + ${currentX}px), calc(-50% + ${currentY}px)) scale(${scale})`;
        }

        // Controla el zoom con la rueda del mouse
        contenedor.addEventListener('wheel', (e) => {
            e.preventDefault(); // Evita el scroll normal de la página

            const delta = e.deltaY > 0 ? -0.1 : 0.1; // Determina si se hace zoom in o out
            scale = Math.min(Math.max(1, scale + delta), 8); // Limita el zoom entre 1x y 8x
            setTransform(); // Aplica la nueva escala

            // Cambia el cursor según el tipo de zoom
            imgElement.style.cursor = delta > 0 ? 'zoom-in' : 'zoom-out';

            // Después de 400ms sin hacer zoom, cambia el cursor a "grab" si no se está arrastrando
            clearTimeout(imgElement._cursorTimeout);
            imgElement._cursorTimeout = setTimeout(() => {
                if (!isDragging) imgElement.style.cursor = 'grab';
            }, 400);
        });

        // Inicio del arrastre al presionar el botón izquierdo del mouse
        imgElement.addEventListener('mousedown', (e) => {
            if (e.button !== 0) return; // Solo botón izquierdo
            isDragging = true;
            startX = e.clientX - currentX; // Guarda posición inicial respecto al movimiento actual
            startY = e.clientY - currentY;
            imgElement.style.cursor = 'grabbing'; // Cambia el cursor a "grabbing"
            e.preventDefault(); // Previene selección de texto u otras acciones por defecto
        });

        // Movimiento mientras se arrastra
        document.addEventListener('mousemove', (e) => {
            if (!isDragging) return;
            if ((e.buttons & 1) !== 1) {
                // Si ya no se mantiene presionado el botón izquierdo, termina el arrastre
                isDragging = false;
                imgElement.style.cursor = 'grab';
                return;
            }

            currentX = e.clientX - startX; // Calcula nueva posición
            currentY = e.clientY - startY;
            setTransform(); // Aplica la transformación
        });

        // Fin del arrastre al soltar el botón del mouse
        document.addEventListener('mouseup', (e) => {
            if (e.button === 0 && isDragging) {
                isDragging = false;
                imgElement.style.cursor = 'grab';
            }
        });

        // Cambia el cursor cuando se entra en la imagen con el mouse
        imgElement.addEventListener('mouseenter', () => {
            if (!isDragging) imgElement.style.cursor = 'zoom-in';
        });

        // Restaura el cursor cuando se sale de la imagen con el mouse
        imgElement.addEventListener('mouseleave', () => {
            if (!isDragging) imgElement.style.cursor = 'default';
        });
        /** 
         * INICIO FECHA : 07-MAYO-2025 || @author Efrain Martinez <programador.analista38@ciudadmaderas.com>
         * Función que permite que la imagen no se salga del contenedor.
        */
        function limitarMovimiento() {
            const contRect = contenedor.getBoundingClientRect();
            const imgRect = imgElement.getBoundingClientRect();

            const maxX = Math.max(0, (imgRect.width - contRect.width) / 2);
            const maxY = Math.max(0, (imgRect.height - contRect.height) / 2);

            currentX = Math.max(-maxX, Math.min(maxX, currentX));
            currentY = Math.max(-maxY, Math.min(maxY, currentY));
        }
        /**
         * FIN FECHA : 07-MAYO-2025 || @author Efrain Martinez Muñoz <programador.analista38@ciudadmaderas.com> 
         */
    }
    
    /**
     * FIN FECHA : 16-ABRIL-2025 | @author Efrain Martinez Muñoz <porgramador.analista38@ciudadmaderas.com>
     */

    /**
     * INICIO @uthor Efrain Martinez Muñoz | Fecha : 27/03/2025
     * Se crean las funciones para abrir, cerrar, editar, previzualizar y guargar la imagen que se va a actualizar.
     *Función para mostrar la imagen en el modal
     */
    function verImagen(button) {
        var imageUrl = button.getAttribute('data-url');
        var numArchivo = button.getAttribute('data-doc');
        var contenedor = document.getElementById('imagenMos');
        var verImagen = document.getElementById('verIma');
        var abrirEditar = document.getElementById('editarImagenCon');

        contenedor.innerHTML = `<div class="visualizarArchivoDocumento" id="contenedorZoom${numArchivo}">
                                    <img id="imgZoom${numArchivo}" src="${imageUrl}?t=${new Date().getTime()}">
                                </div>`;
                                
        const img = document.getElementById(`imgZoom${numArchivo}`);
        zomMovImagen(img);
        contenedor.style.display = "flex";
        abrirEditar.style.display = "none";
        verImagen.style.display = 'none';
        const replegarButton = verImagen.closest('.btn-group').querySelector('#cerrarIma');
        replegarButton.style.display = 'block';
    }
    //Función para cerrar la imagen
    function cerrarImagen(button) {
        var contenedor = document.getElementById('imagenMos');
        var cerrarImagen = document.getElementById('cerrarIma');

        contenedor.style.display = "none";
        cerrarImagen.style.display = 'none';
        const verButton = cerrarImagen.closest('.btn-group').querySelector('#verIma');
        verButton.style.display = 'block';
    }
    //Función para editar la imagen
    function editarImagen(button) {
        var imageUrl = button.getAttribute('data-url'); // Obtiene la URL de la imagen desde el botón
        var contenedor = document.getElementById('imagenMos');
        var cerrarImagen = document.getElementById('cerrarIma');
        var abrirEditar = document.getElementById('editarImagenCon');
        

        document.getElementById('imagenSubida').src = imageUrl + "?t=" + new Date().getTime(); // Muestra la imagen en el modal
        document.getElementById('imagenActualAc').src = imageUrl + "?t=" + new Date().getTime();
        document.getElementById('nuevaImagen').value = "";
        cerrarImagen.style.display = 'none';
        contenedor.style.display = "none";
        const verButton = cerrarImagen.closest('.btn-group').querySelector('#verIma');
        verButton.style.display = 'block';
        abrirEditar.style.display = "block"; // Muestra el contenedor para editar la imagen
    }
    // Función que permite previzualizar la imagen antes de cargarla o actualizarla.
    function previsualizarImagenConsultaDev(event) {
        var fileInput = event.target; // Obtiene el input de tipo file
        var file = fileInput.files[0];

        if (file) {
            var newImageUrl = URL.createObjectURL(file); // Crea una URL temporal

            if (fileInput.id === "subirImagenDoc") {
                // Imagen para el formulario de subida
                document.getElementById("imagenSubida").src = newImageUrl;
                document.getElementById("contenedorImagenSubida").style.display = "block";
            } else if (fileInput.id === "nuevaImagen") {
                // Imagen para la edición
                document.getElementById("contenedorImagenEditar").style.display = "block";
                document.getElementById("imagenActualAc").src = newImageUrl;
            }
        }
    }
    // Función que permite actualizar la imagen
    function guardarNuevaImagen() {
        var fileInput = document.getElementById('nuevaImagen');

        if (fileInput.files.length > 0) {
            var file = fileInput.files[0]; // Obtiene el archivo seleccionado
            var tipoImagen = file.type; // Obtiene el tipo MIME

            if (tipoImagen === "image/jpeg" || tipoImagen === "image/png") {
                var formData = new FormData();
                formData.append('imagen', file); // Agrega el archivo a FormData
                formData.append('idsolicitud', <?= $idsolicitud ?>);
                formData.append("idUsuario", idUsuario);
                if(idDocumento != 0){
                    formData.append('idDocumento', idDocumento);
                }

                // Enviar la imagen al servidor con AJAX
                $.ajax({
                    url: url + "Devoluciones_Traspasos/actualizarImagen",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: "json",
                    success: function (response) {
                        console.log(response.status);
                        if (response.status === "success") {
                             Swal.fire({
                                position: 'center',
                                icon: 'success',
                                title: '¡Imagen actualizada con exito!',
                                showConfirmButton: false,
                                timer: 2000
                            });
                            $('#editarImagenCon').modal('hide'); // Cierra el modal

                            // Actualizar la imagen mostrada en la interfaz
                            document.getElementById('imagenActualAc').src = URL.createObjectURL(file);
                        } else {
                            Swal.fire({
                                title: "¡ERROR!",
                                text: response.message,
                                icon: "error",
                                confirmButtonText: "Aceptar",
                                confirmButtonColor: "#dc3545"
                            });
                        }
                    },
                    error: function () {
                            Swal.fire({
                                title: "¡ERROR!",
                                text: "Hubo un error en la subida",
                                icon: "error",
                                confirmButtonText: "Aceptar",
                                confirmButtonColor: "#dc3545"
                            });
                    }
                });

            } else {
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: '¡Solo se permiten imágenes JPG o PNG.!',
                    showConfirmButton: false,
                    timer: 2000
                });
                fileInput.value = ""; // Borra la selección
            }

        } else {
            Swal.fire({
                title: "¡ERROR!",
                text: "Selecciona una imagen para actualizar.",
                icon: "error",
                confirmButtonText: "Aceptar",
                confirmButtonColor: "#dc3545"
                
            });
        }
    }
    //Función que le da funcionalidad al boton cancelar
    function cancelarEdit(){
        var abrirEditar = document.getElementById('editarImagenCon');
        abrirEditar.style.display = "none";
    }
    //FIN Fecha : 27/03/2025 | @author Efrain Martinez Muñoz <programador.analista38ciudadmaderas.com>
    function downloadAll() {
        urls = arra;

        for (var i = 0; i < urls.length; i++) {
            forceDownload(urls[i].url, urls[i].nombre.substring(urls[i].nombre.lastIndexOf('/') + 1, urls[i].nombre.length));
        }
    }

    function forceDownload(url, fileName) {
        var xhr = new XMLHttpRequest();
        xhr.open("GET", url, true);
        xhr.responseType = "blob";
        xhr.onload = function() {
            var urlCreator = window.URL || window.webkitURL;
            var imageUrl = urlCreator.createObjectURL(this.response);
            var tag = document.createElement('a');
            tag.href = imageUrl;
            tag.download = fileName;
            document.body.appendChild(tag);
            tag.click();
            document.body.removeChild(tag);
        }
        xhr.send();
    }

    function forceDownloadFile(url, nombreArchivo) {
        let fileName = nombreArchivo || url.substring(url.lastIndexOf('/') + 1, url.length);
        var xhr = new XMLHttpRequest();
        xhr.open("GET", url, true);
        xhr.responseType = "blob";
        xhr.onload = function() {
            var urlCreator = window.URL || window.webkitURL;
            var imageUrl = urlCreator.createObjectURL(this.response);
            var tag = document.createElement('a');
            tag.href = imageUrl;
            tag.download = fileName;
            document.body.appendChild(tag);
            tag.click();
            document.body.removeChild(tag);
        }
        xhr.send();
    }

    $("#agregar_comentario").click(function () { /** INICIO FECHA: 27-FEBRERO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
        let comentario = $("#comentarios").val().trim(); // Eliminamos espacios en blanco

        // Verificamos si el comentario está vacío
        if (comentario === "") {
            // Mostramos el mensaje de error debajo del input
            if ($("#error-comentario").length === 0) {
                $("#comentarios").after('<small id="error-comentario" style="color: red;">Por favor, ingrese un comentario.</small>');
            }
            return; // Evita que se continúe con la petición AJAX
        } else {
            $("#error-comentario").remove(); // Eliminamos el mensaje de error si ya escribió algo
        }

        $.post(url + "Consultar/agregar_comentario", {
            idsolicitud: <?= $idsolicitud ?>,
            comentario: comentario
        }).done(function(data) {
            data = JSON.parse(data);
            $("#comentarios").val("");
            $('#chat').append(data.mensaje);
            $("#chat").scrollTop($("#chat")[0].scrollHeight);
            num_comentarios += num_comentarios;
        }).then(function() {
            cargarConversacionDepartamento(); // Asegura que se ejecuta después del comentario
        });
    }); /** FIN FECHA: 27-FEBRERO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/


    $('.generar_caratula').on('click', function() {
        window.open(url + "Devoluciones_Traspasos/generar_caratula/" + <?= $idsolicitud ?>, "_self");
    });

    $("#subir_documentos").submit(function(e) {
        e.preventDefault();
        jQuery(this).find(':disabled').removeAttr('disabled');
    }).validate({
        submitHandler: function(form) {
            var data = new FormData($(form)[0]);

            var link =  <?= $tipo_consulta == "POST_DEV" ? 2 : 0 ?> > 0 ? "Post_Devoluciones/agregar_documentos" : "Devoluciones_Traspasos/agregar_documentos";
            enviar_post2(function(respuesta) {

                if ( respuesta["resultado"] && data != '') {
                    documentos();
                    descarga_dosc();
                    var tr = $(this).closest('tr').remove();
                }else{
                    if( respuesta.msj )
                        alert( respuesta.msj );      
                    else
                        alert("¡No fue posible cargar el documento!");
                }
                tabla_devoluciones.ajax.reload(null, false);
            }, data, url + link);
        }
    });

    $("#tablaPagosTab").on('click', function() {
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

        $("#detalleTablaPagos").text("");
        let montoTotal = <?= $datos_solicitud->cantidad?>;
        let parcialidades = <?= $datos_solicitud->numeroPagos ? $datos_solicitud->numeroPagos : 0 ?>;
        let montoParcialidad = <?= $datos_solicitud->montoParcialidad ? $datos_solicitud->montoParcialidad : 0 ?>;
        let periodo = <?= $datos_solicitud->programado ? $datos_solicitud->programado : 0 ?>;
        const fechaInicio = moment("<?= $datos_solicitud->fecelab?>");
        let divContainer = $('<div id="divDetalleTablePagos" class="col-lg-12"></div>');
        let tabla = $(`<table class="table">
                            <thead>
                                <tr>
                                    <th scope="col" class="text-center">#</th>
                                    <th scope="col" class="text-center">FECHA PAGO</th>
                                    <th scope="col" class="text-center">MONTO PARCIALIDAD</th>
                                    <th scope="col" class="text-center">MONTO ACUMULADO</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>`);
        
        let fechaProxPag = fechaInicio.clone();
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
                        fechaProxPag = fechaInicio.clone().add(numPago, 'weeks');
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
                        fechaProxPag = fechaInicio.clone().add((numPago*15), 'days');
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
                        fechaProxPag = fechaInicio.clone().add(numPago, 'months');
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
                        fechaProxPag = fechaInicio.clone().add((numPago * 2), 'months');
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
                        fechaProxPag = fechaInicio.clone().add((numPago * 3), 'months');
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
                        fechaProxPag = fechaInicio.clone().add((numPago * 4), 'months');
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
                        fechaProxPag = fechaInicio.clone().add((numPago * 6), 'months');
                    }
                    break;
        }
        divContainer.append(tabla);
        $('#detalleTablaPagos').append(divContainer);
    });
/**
     * @uthor Efrain Martinez Muñoz | Fecha : 27/03/2025
     * Se carga la imagen a la solicitud desde la pestaña de DOCUMENTOS siempre y cuando sea jpg o png.
     */
    $("#subirImagen").submit(function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        var idsolicitud = document.getElementById("idsolicitud").value;
        var fileInput = document.getElementById('subirImagenDoc');

        if (fileInput.files.length > 0) {
            var file = fileInput.files[0]; // Obtiene el archivo seleccionado
            var tipoImagen = file.type; // Obtiene el tipo MIME

            if (tipoImagen === "image/jpg" || tipoImagen === "image/jpeg" || tipoImagen === "image/png") {
                
                formData.append("subir_imagen", file);
                formData.append("id_solicitud", idsolicitud);
                formData.append("idUsuario", idUsuario);

                $.ajax({
                    url: url + "Devoluciones_Traspasos/guardarImagen",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    dataType: "json",
                    success: function (response) {
                        if (response.status === "success") {
                            if(tabla_devoluciones){
                                tabla_devoluciones.ajax.reload(null, false);
                            }
                            if (typeof tabla_historial_solicitudes !== "undefined") {
                                tabla_historial_solicitudes.ajax.reload(null, false);
                            }
                                                        
                            $('#imagen_lote').hide();
                        } else {
                            alert("Error: " + response.message);
                        }
                    },
                    error: function () {
                        Swal.fire({
                            title: "¡ERROR!",
                            text: "Hubo un error en la subida",
                            icon: "error",
                            confirmButtonText: "Aceptar",
                            confirmButtonColor: "#dc3545"
                        });
                    }
                });
            } else {
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: '¡Solo se permiten imágenes JPG o PNG.!',
                    showConfirmButton: false,
                    timer: 2000
                });
                fileInput.value = ""; // Borra la selección
            }
        } else {
            Swal.fire({
                title: "¡ERROR!",
                text: "Selecciona una imagen para cargarla.",
                icon: "error",
                confirmButtonText: "Aceptar",
                confirmButtonColor: "#dc3545" 
            });
        }        
    });
</script>