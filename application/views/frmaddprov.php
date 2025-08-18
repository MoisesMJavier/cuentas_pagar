<?php

    require("head.php");

?>
<style>
    html{
        height:100vh;
        width:100%;
        margin:0;
        padding:0;
    }
    
    body{
        background-image:url(https://cuentas.gphsis.com/img/bbalta_proveedor.jpg);
        background-size:cover;
        background-position:center;
        background-repeat: no-repeat;
        background-attachment: fixed;
    }

    .btn-cpp{
        background-color: #2c3e50;
        color: #ffffff;
    }

    .btn-cpp:hover{
        color: #2c3e50;
        background-color: #ffffff;
        box-shadow: inset 0px 1px 1px #2c3e50;
    }

    .container{
        margin-top: 2%
    }

    .texto_centrado{
        padding-top: 21%;
        position: relative;
    }

    .contenedor_elementos{
        height: 40.8em;
        background-color:rgba(255,255,255,0.90);
        border-radius:3px;
        margin-top: 1em;
        box-shadow:0px 2px 2px #000000;
    }

</style>
<body>
<div class="container">
    <div class="row">
        <div class="col-lg-8 col-lg-offset-2 text-center">
            <img src="https://www.ciudadmaderas.com/assets/img/logo.png" alt="Ciudad Maderas" class="img-responsive" title="Ciudad Maderas">
        </div>    
    </div>
    <div class="row">
        <div class="col-lg-6 hidden-xs">
            <div class="row">
                <div class="col-lg-10 contenedor_elementos texto_centrado">
                    <h4 class="text-center">Muchas gracias por formar parte de nuestros proveedores.</h4>
                    <h5 class="text-center">¡Te deseamos éxito!</h5>
                    <p class="text-justify" >Te pedimos llenes todos los campos del formulario de forma correcta ya que éstos son los datos que serán utilizados por nuestros asesores para poder solicitar tus servicios y poder realizar las transacciones necesarias.</p>
                    <p class="text-center">¡Gracias!</p>
                    <p class="text-center"><b>El equipo de Ciudad Maderas</b></p>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="row">
                <div class="col-lg-10 contenedor_elementos ">
                    <form id="frmAddProv">
                        <h4>REGISTRO DE PROVEEDORES</h4>
                        <hr>
                        <div class="form-row">
                            <div class="form-group col-lg-6">
                                <label for="nombreprov">NOMBRE O RAZÓN SOCIAL</label>
                                <input type="text" class="form-control" id="nombreprov" name="nombreprov" placeholder="Nombre o Razón Social" required>
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="rfc">RFC</label>
                                <input type="text" name="rfc" class="form-control" id="rfc" placeholder="RFC" required>
                            </div>
                        </div>
                        <div class="form-row">
                             <div class="col-lg-6 form-group">
                                <label>RÉGIMEN FISCAL:</label>
                                <select  id="rf_proveedor"  name="rf_proveedor"  class="form-control regimen_fiscal" required
                                
                                ></select>
                            </div>
                            <div class="col-lg-6 form-group">
                                <label>CÓDIGO POSTAL:</label>
                                <input type="text" name="cp_proveedor" class="form-control" placeholder="Ingrese código postal del proveedor." required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-lg-12">
                                <label for="contacto">CONTACTO</label>
                                <input type="text" class="form-control" id="contacto" name="contacto" placeholder="Contacto" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-lg-6">
                                <label for="sucursal">SUCURSAL</label>
                                <select name="sucursal" class="form-control estado_pais" id="sucursal" required></select>
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="Correo">CORREO</label>
                                <input type="text" name="correo" class="form-control" id="correo" placeholder="Correo" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-lg-6">
                                <label for="nombanco">BANCO</label>
                                <select name="nombanco" class="form-control lista_bancos" id="nombanco" required></select>
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="tipcont">TIPO DE CUENTA <i class="far fa-question-circle" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" data-placement="right" title="Tipos de cuenta" data-content="Dependiendo si es tarjeta de débito o CLABE indica en ésta parte."></i></label>
                                <select name="tipcont" id="tipcont" class="form-control" required>
                                    <option value="">Seleccione una opción</option>
                                    <option value="1">Cuenta Banco del Bajio</option>
                                    <option value="3">Tarjeta de débito</option>
                                    <option value="40">CLABE</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-lg-12">
                                <label for="cuenta">NO. CUENTA</label>
                                <input type="text" name="cuenta" class="form-control" id="cuenta" placeholder="Cuenta" required disabled>
                            </div>
                        </div>
                        <div class="form-row">
                            <button type="submit" class="btn btn-success btn-block"><i class="fas fa-paper-plane"></i> ENVIAR</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade modal-alertas" id="modal_advertencia" role="dialog">
    <div class="modal-dialog">
         <div class="modal-content">
            <div class="modal-header" style="background: #cc0000; color: #fff;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><i class="fas fa-exclamation-triangle"></i> ADVERTENCIA</h4>
            </div>  
            <div class="modal-body">     
                <div class="row">
                    <div class="col-lg-12">
                        <p class="text-justify"><i>¡Recuerda!</i> haber enviado tu documentación correspondiente.</p>
                        <p class="text-justify"><i>Verifique</i> que la información proporcionada sea la <b>correcta</b>, en caso de haber algún error puede que su <b>pago</b> se retrase.</p>
                        <hr/>
                    </div>
                </div>        
                <div class="row">
                    <div class="col-lg-10 col-lg-offset-1">
                        <button type="button" class="btn btn-success btn-block" id="confirmar_informacion">ES CORRECTA</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script> 

    $("#cuenta").keypress( function (e) {
        if( e.which < 48 || e.which > 57 || e.which == 46 ){
            return false;
        }
   });

    $.getJSON( url + "Invitacion/listas_dinamicas", function( data ){

        $(".lista_bancos").html( '<option value="">Seleccione una opción</option>' );
        $.each( data[0], function( i, v ){
            $(".lista_bancos").append( '<option value="'+v.idbanco+'">'+v.nombre+'</option>' );
        });

        $(".estado_pais").html( '<option value="">Seleccione una opción</option>' );
        $.each( data[1], function( i, v ){
            $(".estado_pais").append( '<option value="'+v.id_estado+'">'+v.estado+'</option>' );
        });

        $(".regimen_fiscal").html('<option value="">Seleccione una opción</option>');
        $.each( data[2], function( i, v ){
            $(".regimen_fiscal").append( '<option value="'+v.codrf+'">'+v.codrf+" - "+v.descrf+'</option>' );
        });

    });

    $("#tipcont").change( function(){

        if( $( this ).val() ){
            $("#cuenta").prop("disabled", false);

            $("#cuenta").rules("remove");

            switch( $( this ).val() ){
                case "3":
                    $("#cuenta").rules("add", {
                        required: true,
                        maxlength: 16,
                        minlength: 16
                    });
                    break;
                case "1":
                    $("#cuenta").rules("add", {
                        required: true,
                        maxlength: 12,
                        minlength: 12 
                    });
                    break;
                case "40":
                    $("#cuenta").rules("add", {
                        required: true,
                        maxlength: 18,
                        minlength: 18 
                    });
                    break;

            }

            formnulario.resetForm();

        }else{
            $("#cuenta").prop("disabled", true);
        }
    }); 

    $("#cuenta").change( function(){
        $.post( url + "Invitacion/checar_cuenta", { cuenta_proveedor : $(this).val() }).done( function( data ){
            data = JSON.parse( data );
            if( !data[0] ){
                $("#cuenta").val('')
                alert("Actualmente contamos con un proveedor que comparte con la misma cuenta que esta.");
            }
        });
    });

    var formnulario = $("#frmAddProv").submit( function(e) {
        e.preventDefault();
    }).validate({
        submitHandler: function() {
            $("#modal_advertencia").modal();
        }
    });
    
    $("#confirmar_informacion").click( function(){
        var data = new FormData( $("#frmAddProv")[0] );
        $.ajax({
            url: url + "Invitacion/alta_proveedor",
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            method: 'POST',
            type: 'POST', // For jQuery < 1.9
            success: function(data){
                if( data[0] ){
                    $("#modal_advertencia").modal("toggle");
                    $( ".container" ).html("")
                    $( ".container" ).html('<div class="row"><div class="col-lg-6 coltxt col-lg-offset-3 text-center"><img src="https://www.ciudadmaderas.com/assets/img/logo.png" alt="Ciudad Maderas" class="img-responsive" title="Ciudad Maderas"><h4>¡Felicidades se ha agregado correctamente!</h4></div></div>');
                }else{
                    alert("NO SE HA PODIDO COMPLETAR LA SOLICITUD");
                }
            },error: function( ){
                
            }
        });
    });

</script>
<script type="text/javascript" src="<?= base_url("js/utilidades.js")?>"></script>
</body>
</html>