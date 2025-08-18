</section>
</div>
<div id="consultar_modal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
    </div>
  </div>
</div>

<!-- MODAL ARCHIVOS --> <!-- INICIO FECHA: 26-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
<div class="modal fade" role="dialog" id="archivos_descargados">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="CERRAR"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="titulo_archivos_descargados"></h4>
          </div>
          <div class="modal-body" id="contenido-archivos" style="padding-bottom: 0px; max-height: 700px; overflow-y: auto; overflow-x:hidden;">
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">CERRAR</button>
          </div>
        </div>
    </div>
</div>
<!-- FIN MODAL ARCHIVOS --> <!-- FIN FECHA: 26-AGOSTO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->

<div id="duplicidad" class="modal modal-danger modal-alertas fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">¡ERROR!</h4>
            </div>
            <div class="modal-body text-center"></div>
        </div>
    </div>
</div>
<div id="modalalerta" class="modal modal-warning modal-alertas fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">¡ADVERTENCIA!</h4>
            </div>
            <div class="modal-body text-center"></div>
        </div>
    </div>
</div>
<div id="modal_cambiar_password" class="modal modal-alertas fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="color: #fff; background-color: orangered;">
                <button type="button" class="close close-pass" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">ACTUALIZAR CONTRASEÑA Y/O USUARIO</h4>
            </div>
            <div class="modal-body text-center">
              <form id="formcambiarpassword">
                <div class="row">
                  <div class="col-lg-12 form-group">
					        <p>Por seguridad, ingrese un usuario y/o una contraseña:</p>
                    <div class="col-12">
                    <input type="text" class="form-control" id="input_usuario" name="input_usuario" placeholder="Ingrese el nuevo usuario" required value="<?php echo $this->input->post("input_usuario");?>">
                    </div>
                    <div class="input-group">
                    <input type="password" class="form-control" id="input_password" name="input_password" placeholder="Ingrese la nueva contraseña" required>
                      <div class="input-group-btn">
                        <button type="button" class="btn btn-default password_eye"><i class="far fa-eye"></i></button>
                      </div>
                    </div>
                    
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-12 form-group">
                    <button type="submit" class="btn btn-success btn-sm">GUARDAR</button>
                  </div>
                </div>
              </form>
            </div>
        </div>
    </div>
</div>
<div id="modal_reporte_falta_provision" class="modal modal-alertas fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="color: #fff; background-color: #4CAF50;">
                <button type="button" class="close close-pass" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">FACTURAS SIN PROVISIÓN</h4>
            </div>
            <div class="modal-body text-center">
              <form id="form_generar_provision" method="post" action="#">
                <div class="row">
                  <div class="col-lg-12 form-group">
					          <label>Seleccione una fecha:</label>
                    <div class="input-group">
                      <span class="input-group-addon"><i class="far fa-calendar-alt"></i></span>
                      <input type="month" class="form-control" id="fecha_mes_provision" name="fecha_mes_provision" required>
                    </div>                
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-12 form-group">
                    <button type="submit" class="btn btn-warning btn-block">GENERAR DOCUMENTO</button>
                  </div>
                </div>
              </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade modal-alertas" id="modal_acciones_sol" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: #00A65A; color: #fff;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">SOLICITUD VALIDA</h4>
            </div>
            <div class="modal-body">
                <form id="form_acciones_sol" action="#" method="post">
                    <input type="hidden" name="idsolicitud" id="idsolicitud_accion">
                    <div class="inputs_hidden">
                        
                    </div>
                    <div class="row">
                        <div class="col-lg-12 form-group">
                            <label>OBSERVACIÓN <small>(OPCIONAL)</small>:</label>
                            <textarea class="form-control" name="comentario"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-lg-offset-6">
                            <button type="submit" class="btn btn-block btn-success">ENVIAR</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<footer class="main-footer" style="text-align: center">
  © <?= date("Y");?> Ciudad Maderas, Departamento de TI | <b>Cuentas por pagar</b> 
</footer>
</div>
</body>
<script type="text/javascript" src="<?= base_url("js/utilidades.js?v=4")?>"></script>

<script type="text/javascript">
var liga_acciones_sol="";

$(document).on('hidden.bs.modal', function (event) {
  if ($('.modal:visible').length) {
    $('body').addClass('modal-open');
  }
});

$('table').on( 'draw.dt', function () {
    $('td:has(div)').css('text-align', 'center');
    $('td:has(button)').css('text-align', 'center');
});


function checkpass(igual){

  
  $("#input_password").val('');
    $(".password_eye").html( '<i class="far fa-eye"></i>' );
    $("#input_password").prop("type", "password");
    $('.close-pass').hide();
    $("#modal_cambiar_password").modal({backdrop: 'static', keyboard: false});
}

  $( document ).on( 'click', '#cambiar_password', function(){
    $("#input_password").val('');
    $(".password_eye").html( '<i class="far fa-eye"></i>' );
    $("#input_password").prop("type", "password");
    $('.close-pass').show();
    $("#modal_cambiar_password").modal();
  });

  $("#formcambiarpassword").submit( function(e) {
    e.preventDefault();
  }).validate({
    submitHandler: function( form ) {
      var data = new FormData( $(form)[0] );
      $.ajax({
        url: url + "Home/cambiar_password",
        data: data,
        cache: false,
        contentType: false,
        processData: false,
        dataType: 'json',
        method: 'POST',
        type: 'POST', // For jQuery < 1.9
        success: function( data ){
          //data = JSON.parse(data);
          if (data['result'] == true){
            $("#modal_cambiar_password").modal("toggle");
            alert("SE HA ACTUALIZADO LA CONTRASEÑA CON ÉXITO");
          }else{
            switch(data['msj']){
              case '1':
              alert("CONTRASEÑA INSEGURA, INTENTA CON UNA NUEVA.");
              break;
              default:
                
              break;
            }
          }
        },error: function( ){
          
        }
      });
    }
  });

  $("#form_generar_provision").submit( function() {
    $("#form_generar_provision").attr("action", url + "Historial/generar_documento_provision" );
  }).validate();

  

  $(".password_eye").click( function(){
		if($("#input_password").prop("type") == 'password'){
			$(this).html( '<i class="far fa-eye-slash"></i>' );
			$("#input_password").prop("type", "text");
		}else{
			$(this).html( '<i class="far fa-eye"></i>' );
			$("#input_password").prop("type", "password");
		}
	});
    
    function enviar_post(d,url) {
        var result= JSON.parse("{}");
        
        $.ajax({
            processData: false,
            contentType: false,
            type: "POST",
            url: url,
            data: d,
            dataType: "json",
            async: false,
            success: function(data) {
                // Run the code here that needs
                result = data;
            },
            error: function() {
                alert('Error occured');
            }
        });
        return result;
    }
    
    $(".dinero").maskMoney({prefix:'$', allowNegative: false, thousands:',', decimal:'.', affixesStay: true});
    
    $("select").on("select2:close", function (e) {  
        $(this).valid(); 
    });
    
</script>
</html>