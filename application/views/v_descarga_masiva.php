<?php
    require("head.php");
    require("menu_navegador.php");
?>  
<style>
    /* Anular los márgenes predeterminados de list-group de Bootstrap */
    .list-group {
      margin-bottom: 0; /* Esto quita el margen inferior */
    }
    .list-group-item {
      margin-bottom: 0; /* Esto quita el margen inferior en los elementos de la lista */
    }
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-header">
                    <h3>DESCARGAR XML</h3>
                </div>
                <div class="box-body">
                    <form action="<?= site_url("Descargar_XML/descargar_zip"); ?>" id="descargaMasiva" method="post">
                    <div class="row">
                        <div class=" col-lg-3 form-group">
                            <h5><b>EMPRESAS</b></h5>
                            <select id="idempresa" name="idempresa" class="form-control lista_empresa" required>
                            </select>
                        </div>
                        <div class=" col-lg-3 form-group">
                            <h5><b>FECHA INICIO</b></h5>
                            <input type="date" id="fecha_ini" name="fecha_ini" class="form-control" required>
                        </div>
                        <div class=" col-lg-3 form-group">
                            <h5><b>FECHA FINAL</b></h5>
                            <input type="date" id="fecha_fin" name="fecha_fin" class="form-control" required>
                        </div>
                        <div class=" col-lg-3">
                            <h5><b>TIPO FACTURAS</b></h5>
                            <div class="list-group">
                                <label class="list-group-item"><input type="radio" name="tipo_factura" value="1" checked>Provisionar</label>
                                <label class="list-group-item"><input type="radio" name="tipo_factura" value="2">Facturas Post Pago</label>
                                <label class="list-group-item"><input type="radio" name="tipo_factura" value="4">Complementos</label>
                                <label class="list-group-item"><input type="radio" name="tipo_factura" value="3">Caja Chica</label>
                                <label class="list-group-item"><input type="radio" name="tipo_factura" value="5">Facturas TDC</label>
                                <label class="list-group-item"><input type="radio" name="tipo_factura" value="6">Viáticos</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-lg-offset-4">
                            <button type="submit" class="btn btn-success btn-block">DESCARGAR</button>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(".lista_empresa").ready( function(){
        $(".lista_empresa").append('<option value="">Seleccione una opción</option>');
        $.getJSON( url + "Listas_select/lista_empresas").done( function( data ){
            $.each( data, function( i, v){
                $(".lista_empresa").append('<option value="'+v.idempresa+'" data-value="'+v.rfc+'">'+v.nombre+'</option>');
            });
        });
    });
</script>
<?php
    require("footer.php");
?>