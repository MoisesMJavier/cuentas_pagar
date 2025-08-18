<?php
    require("head.php");
    require("menu_navegador.php");
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-header">
                    <h3>IMPORTADOR DE SOLICITUDES</h3>                 
                </div>
                <div class="box-body">
                    <div class="tab-content">
                        <div class="active tab-pane" id="facturas_aturizar">
                            <div clas="row">
                                <form action="#">
                                    <div class="col-lg-4 col-lg-offset-2 form-group">
                                        <label>DOCUMENTO A IMPORTAR</label>
                                        <input class="form-control" type="file" id="documento_xls" name="documento_xls" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" required>
                                    </div> 
                                    <div class="col-lg-4">
                                        <br/>
                                        <input class="btn btn-block btn-info" type="submit" value="CARGAR">
                                    </div> 
                                </form>   
                            </div>
                            <hr/>
                            <div class="row" id="resultados_obtenidos"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $("form").submit( function(e) {
        e.preventDefault();
    }).validate({
        submitHandler: function( form ) {
            var data = new FormData( $(form)[0] );

            $.ajax({
                url: url + "I/import",
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST', // For jQuery < 1.9
                success: function(data){
                    console.log( data );
                    if( data.resultado ){
                        alert( data.correctos.length + "REGISTROS IMPORTADOS" );

                        $("#resultados_obtenidos").html("");
                        if( data.error.length > 0 ){

                            $("#resultados_obtenidos").append('<h2"><b>ERRORES DETECTADOS <small>(Verifique el documento)</small></b></h2>');

                            $.each( data.error, function( i, v ){
                                $("#resultados_obtenidos").append('<div class="col-lg-2">'+ v +'</div>');
                            });
                        }
                    }

                },error: function( data ){
                    console.log( data );
                    
                }
            });
        }
    });
</script>
<?php
    require("footer.php");
?>