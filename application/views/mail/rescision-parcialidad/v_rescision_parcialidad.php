<div class="text-center">
    <div class="notificationText">
        Te informamos que la solicitud de pago <?= $contenido["idsolicitud"]; ?> de pago ha sido completado con éxito.
    </div>
    <div class="d-flex justify-center">
        <table class="tablaSolicitud">
            <thead>
                <tr>
                    <td colspan="2"><h3>DATOS DE LA SOLICITUD</h3></td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="inicioCelda">SOLICITUD</td>
                    <td><?= $contenido["idsolicitud"]; ?></td>
                </tr>
                <tr>
                    <td class="inicioCelda">FECHA INICIO PAGOS</td>
                    <td><?= $contenido["fechaInicioPagos"]; ?></td>
                </tr>
                <tr>
                    <td class="inicioCelda">FECHA ÚLTIMO PAGO</td>
                    <td><?= $contenido["fechaFinPagos"]; ?></td>
                </tr>
                <tr>
                    <td class="inicioCelda">PARCIALIDAD</td>
                    <td><?= $contenido["cantidadUltimoPago"]; ?></td>
                </tr>
                <tr>
                    <td class="inicioCelda">FRECUENCIA DE PAGOS</td>
                    <td><?= $contenido["frecuenciaPagos"]; ?></td>
                </tr>
                <tr>
                    <td class="inicioCelda" >CANTIDAD TOTAL</td>
                    <td><?= $contenido["cantidadTotal"]; ?></td>
                </tr>
                <tr>
                    <td class="inicioCelda">JUSTIFICACIÓN</td>
                    <td><?= $contenido["justificacion"]; ?></td>
                </tr>
                <tr>
                    <td class="inicioCelda">PROVEEDOR</td>
                    <td><?= $contenido["proveedor"]; ?></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="footerMessage">
        Para consultar la información completa ingresa al sistema de <a href=<?=base_url() ?>>Cuentas Por Pagar</a>
    </div>
</div>