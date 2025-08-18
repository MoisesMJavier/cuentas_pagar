<div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">INFORMACIÓN DE LA SOLICITUD</h4>
    </div>
    <div class="modal-body">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li <?= $notificacion == 0 ? 'class="active"' : '' ?>><a data-toggle="tab" href="#info">INFORMACIÓN</a></li>
                <li><a data-toggle="tab" href="#infoImpuestos">IMPUESTOS</a></li>
                <li><a data-toggle="tab" href="#infoPago">INFORMACIÓN PAGO</a></li>
                <li <?= $notificacion > 0 ? 'class="active"' : '' ?>><a data-toggle="tab" href="#obser">OBSERVACIONES</a></li>
            </ul>
        </div>
        <div class="tab-content">
            <div id="info" class="tab-pane fade <?= $notificacion == 0 ? 'in active' : '' ?>">
                <div class="row">
                    <div class="col-lg-12">
                    <h5 id="version_fac">VERSION: <b><?= ( isset($datos_factura) ? $datos_factura['version'] : "---" ) ?></b></h5>
                    </div>
                    <div class="col-lg-6">
                        <h4>RECEPTOR</h4>
                        <h5>RFC: <b><?= $datos_solicitud->rfc_receptor ?></b></h5>
                        <h5>NOMBRE: <b><?= $datos_solicitud->n_receptor ?></b></h5>
                    </div>
                    <div class="col-lg-6">
                        <h4>EMISOR</h4>
                        <h5>RFC: <b><?= $datos_solicitud->rfc ?></b></h5>
                        <h5>NOMBRE <b><?= $datos_solicitud->nombre ?></b></h5>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-7">
                        <h5>PROYECTO: <b><?= $datos_solicitud->proyecto ?></b></h5>
                        <h5>CONDOMINIO: <b><?= $datos_solicitud->condominio ?></b></h5>
                        <h5>TIPO INSUMO: <b><?= $datos_solicitud->caja_chica && $datos_solicitud->insumo ? $datos_solicitud->insumo : '-' ?></b></h5>
                    </div>
                    <div class="col-lg-5">
                        <h5>ETAPA: <b><?= $datos_solicitud->etapa ?></b></h5>
                        <h5>HOMOCLAVE <b><?= $datos_solicitud->homoclave ?></b></h5>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <h5>FACTURA: <b><?php
                        switch($datos_solicitud->fac_status){
                            case '1':
                                echo "<label class='label pull-center bg-red'>ESTE GASTO NO TENDRÁ FACTURA</label>";
                            break;
                            case '2':
                                echo "<label class='label pull-center bg-orange'>SE AGREGARÁ FACTURA POSTERIORMENTE</label>";
                            break;
                            case '3':
                                echo "<label class='label pull-center bg-green'>XML DISPONIBLE</label>";
                            break;
                        }
                         ?></b></h5>            
                    </div>
                    <div class="col-lg-6">
                        <h5>CANTIDAD SOLICITADA: <b>$ <?= number_format($datos_solicitud->cantidad, 2, ".", ",") ?></b></h5>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <p class="text-justify"><b>JUSTIFICACIÓN: </b><?= $datos_solicitud->justificacion ?></p>
                    </div>
                    <hr/>
                    <div class="col-lg-6">
                        <h5>ESTATUS: <b><?= $datos_solicitud->prioridad == 1 ? "<small class='label pull-center bg-red'>URGENTE</small>" : "NORMAL" ?></b></h5>
                        <h5>TIPO DE GASTO: <b><?= $datos_solicitud->caja_chica == 1 ? "Caja Chica" : "Otro" ?></b></h5>
                        <h5>FOLIO: <b><?= $datos_solicitud->folio ?></b></h5>
                        <h5>TIPO COMPROBANTE: <b><?= isset($datos_factura) ? $datos_factura['tipocomp'][0] : "---" ?></b></h5>
                        <h5>MÉTODO DE PAGO: <b><?= $datos_solicitud->metodo_pago ?></b></h5>
                        <h5>CONDICIONES DE PAGO: <b><?= isset($datos_factura) ? $datos_factura['condpago'][0] : "---" ?></b></h5>
                        
                    </div>
                    <div class="col-lg-6">
                        <h5>MODO DE PAGO A PROVEEDOR: <b><?= $datos_solicitud->forma_pago?></b></h5>
                        <h5>FECHA FACTURA: <b><?= isset($datos_factura) ? $datos_factura['fecha'][0] : "---" ?></b></h5>
                        <h5>FORMA DE PAGO: <b><?= isset($datos_factura) ? $datos_factura['formpago'][0] : "---" ?></b></h5>
                        <h5>USO CFDI: <b><?= isset($datos_factura) ? $datos_factura['usocfdi'][0] : "---" ?></b></h5>
                        <h5>TOTAL EN FACTURA: <b><?= $datos_solicitud->total ? "$ ".number_format( $datos_solicitud->total, 2, ".", "," ) : "---" ?></b></h5>
                    </div>
                    <div class="col-lg-12">
                        <h5>UUID: <b><?= $datos_solicitud->uuid ?></b><h5>
                    </div>
                    <div class="col-lg-6">
                        <p class="text-justify"><b>DESCRIPCIÓN:</b> <?= $datos_solicitud->descripcion ?  $datos_solicitud->descripcion : "---" ?></b></h5>            
                    </div>
                    <div class="col-lg-6">
                        <p class="text-justify"><b>OBSERVACIONES:</b> <?= $datos_solicitud->observaciones ?  $datos_solicitud->observaciones : "---" ?></b></h5>            
                    </div>
                </div>
                <!--div class="row pre-scrollable">
                    <div class="col-lg-12 table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <td>
                                        CLVP
                                    </td>
                                    <td>
                                        DESCRIPCIÓN
                                    </td>
                                    <td>
                                        CANT
                                    </td>
                                    <td>
                                        CLV UNI
                                    </td>
                                    <td>
                                        IMPORTE
                                    </td>
                                    <td>
                                        DESCUENTO
                                    </td>
                                    <td>
                                        PRECIO
                                    </td>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            /*
                                if( isset($datos_factura) ){
                                    foreach($datos_factura['conceptos'] as $concepto){
                                        echo "<tr>";
                                        echo "<td>".$concepto[0][0]['ClaveProdServ'][0]."</td>";
                                        echo "<td>".$concepto['Descripcion'][0]."</td>";
                                        echo "<td>".$concepto[0][0]['Cantidad'][0]."</td>";
                                        echo "<td>".$concepto[0][0]['ClaveUnidad'][0]."</td>";
                                        echo "<td class='text-left'>$".number_format(floatval($concepto[0][0]['ValorUnitario'][0]),2)."</td>";
                                        echo "<td class='text-left'>".number_format(floatval($concepto[0][0]['Descuento'][0]),2)."</td>";
                                        echo "<td class='text-left'>$".number_format(floatval($concepto[0][0]['Importe'][0]),2)."</td>";
                                        echo "<tr>";
                                    }
                                    echo "<tr>";
                                    echo "<td colspan='5' class='text-left'>IMPUESTO ".$datos_factura['impuesto'][0]." </td>";
                                    echo "<td class='text-left'>TOTAL</td><td class='text-left'>".$datos_factura['Total'][0]." </td>";
                                    echo "</tr>";
                                }else{
                                    echo '<tr><td colspan="7">"SIN DEFINIR"</td></tr>';
                                }
                                */
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div-->
            </div>
            <div id="infoImpuestos" class="tab-pane fade">
                <div class="row">
                    <div class="col-lg-6">
                        <h4 class="text-center"><b>TRASLADOS</b></h4>
                        <?php
                            if( isset($datos_factura) && isset($datos_factura["impuestos_traslados"]) ){
                                foreach( $datos_factura["impuestos_traslados"] as $traslado ){
                                    echo '<h5>IMPUESTO: <b>'.$traslado['Impuesto'].'</b></h5>';
                                    echo '<h5>TASA CUOTA: <b>'.$traslado['TasaOCuota'].'</b></h5>';
                                    echo '<h5>IMPORTE: <b>'.$traslado['Importe'].'</b></h5>';
                                    echo '<hr/>';
                                }
                            }else{
                                echo '<h5>IMPUESTO: <b> --- </b></h5>';
                                echo '<h5>TASA CUOTA: <b>  ---  </b></h5>';
                                echo '<h5>IMPORTE: <b>  ---  </b></h5>';
                                echo '<hr/>';
                            }
                        ?>
                    </div>
                    <div class="col-lg-6">
                        <h4 class="text-center"><b>RETENCIONES</b></h4>
                        <?php
                            if( isset($datos_factura) && isset($datos_factura["impuestos_retenciones"]) ){
                                foreach( $datos_factura["impuestos_retenciones"] as $traslado ){
                                    echo '<h5>IMPUESTO: <b>'.$traslado['Impuesto'].'</b></h5>';
                                    echo '<h5>TASA CUOTA: <b>'.$traslado['TasaOCuota'].'</b></h5>';
                                    echo '<h5>IMPORTE: <b>'.$traslado['Importe'].'</b></h5>';
                                    echo '<hr/>';
                                }
                            }else{
                                echo '<h5>IMPUESTO: <b> --- </b></h5>';
                                echo '<h5>TASA CUOTA: <b>  ---  </b></h5>';
                                echo '<h5>IMPORTE: <b>  ---  </b></h5>';
                                echo '<hr/>';
                            }
                        ?>
                    </div>
                </div>
            </div>
            <div id="infoPago" class="tab-pane fade">
                <div class="row">
                    <div class="col-lg-12">
                        <h5>FORMA DE PAGO: <b><?= $datos_pago->formaPago ?></b></h5>
                        <h5>REFERENCIA: <b><?= $datos_pago->referencia ?></b></h5>
                        <h5>TIPO DE PAGO: <b><?= $datos_pago->referencia ?></b></h5>
                        <h5>CAMBIO: <b><?= $datos_pago->tipoCambio ? $datos_pago->tipoCambio : "---" ?></b></h5>
                        <h5>FECHA DE OPERACIÓN: <b><?= $datos_pago->fechaOpe ?></b></h5>
                        <h5>CANTIDAD: <b><?= $datos_pago->cantidad ?></b></h5>
                    </div>
                </div>
            </div>
            <div id="obser" class="tab-pane fade <?= $notificacion > 0 ? 'in active' : '' ?>">
                <div class="row">
                    <div class="col-lg-12">
                        <div id="chat" class="form-control chat"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="input-group">
                            <input type="text" class="form-control" id="comentario" required> <!-- FECHA: 27-FEBRERO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                            <div class="input-group-btn">
                                <button type="button" class="btn btn-primary" id="agregar_comentario"><i class="fas fa-comments"></i> COMENTAR</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>

        var num_comentarios = 0;

        $(document).ready( function(){
            recargar_conversacion();
        });

        function recargar_conversacion(){
            $.post( url + "Consultar/getConversacion", { idsolicitud : <?= $idsolicitud ?> } ).done(function( data ){
                data = JSON.parse(data);
                if( data.comentarios > num_comentarios ){
                    $.each( data.observaciones, function( i, v ){
                        $('#chat').append( v );
                    });
                    num_comentarios = data.comentarios;
                }else{
                    for( var i = num_comentarios + 1; i < data.comentarios; i++ ){
                        $('#chat').append( data.observaciones[i] );
                    }
                }
                $("#chat").scrollTop($("#chat")[0].scrollHeight);
            });
        }


        $("#agregar_comentario").click(function () { /** INICIO FECHA: 27-FEBRERO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
            let comentario = $("#comentario").val().trim(); // Eliminamos espacios en blanco

            // Verificamos si el comentario está vacío
            if (comentario === "") {
                // Mostramos el mensaje de error debajo del input
                if ($("#error-comentario").length === 0) {
                    $("#comentario").after('<small id="error-comentario" style="color: red;">Por favor, ingrese un comentario.</small>');
                }
                return; // Evita que se continúe con la petición AJAX
            } else {
                $("#error-comentario").remove(); // Eliminamos el mensaje de error si ya escribió algo
            }

            $.post( url + "Consultar/agregar_comentario", { 
                    idsolicitud : <?= $idsolicitud ?>
                    , comentario : comentario
                }).done( function( data ){
                    data = JSON.parse( data );
                    $("#comentario").val("");
                    $('#chat').append(data.mensaje);
                    $("#chat").scrollTop($("#chat")[0].scrollHeight);
                    num_comentarios += num_comentarios;
                });
        }); /** FIN FECHA: 27-FEBRERO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/

    </script>