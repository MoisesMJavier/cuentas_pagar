<div class="modal-header" >
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h3 class="modal-title">Información</h3>
</div>
<div class="modal-body">
<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#info">Datos</a></li>
        <li><a data-toggle="tab" href="#obser">Observaciones</a></li>
    </ul>
    <div class="tab-content">
        <div id="info" class="tab-pane fade in active">
            <h4 class="text-primary">General</h4>
            <div class="row">
                <div class="col-md-12">
                    <h5><b>Nombre: </b><?=$informacion->cliente?></h5>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <h5><b>CURP: </b><?=$informacion->curp?></h5>
                </div>
                <div class="col-md-6">
                    <h5><b>RFC: </b><?=$informacion->rfc?></h5>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <h5><b>Personalidad jurídica: </b><?=$informacion->personalidad?></h5>
                </div>
                <div class="col-md-6">
                    <h5><b>Nacionalidad: </b><?=$informacion->nacionalidad?></h5>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <h5><b>Tel 1: </b><?=$informacion->telefono?></h5>
                </div>
                <div class="col-md-6">
                    <h5><b>Tel 2: </b><?=$informacion->telefono_2?></h5>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <h5><b>Correo: </b><?=$informacion->correo?></h5>
                </div>
            </div><br>
            <h4 class="text-primary">Prospección</h4>
            <div class="row">
                <div class="col-md-6">
                    <h5><b>Lugar: </b><?=$informacion->lugar?></h5>
                </div>
                <div class="col-md-6">
                    <h5><b>Método: </b><?=$informacion->metodo?></h5>
                </div>
                <div class="col-md-6">
                    <h5><b>Plaza: </b><?=$informacion->plaza?></h5>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <h5><b>Asesor: </b><?=$informacion->asesor?></h5>
                </div>
                <div class="col-md-4">
                    <h5><b>Tel: </b><?=$informacion->telefono_asesor?></h5>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <h5><b>Gerente: </b><?=$informacion->gerente?></h5>
                </div>
                <div class="col-md-4">
                    <h5><b>Tel: </b><?=$informacion->telefono_gerente?></h5>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <h5><b>Creado por: </b><?=$informacion->creacion?></h5>
                </div>
            </div><br>
            <div class="row">
                <div class="col-md-4 col-md-offset-8">
                    <button type="button" class="btn btn-primary  btn-block" data-dismiss="modal">Aceptar</button>
                </div>
            </div>
        </div>
        <div id="obser" class="tab-pane fade" >
            <div class="row" id="Comentarios">
                <!--?php 
                foreach($comentarios as $comentario ){
                    echo '<div class="col-md-12" style="border-radius: 5px; border: 2px solid #F1F1F3;margin: 10px 0;">
                            <div class="row">
                                <div class="col-md-12">
                                    <h5><b>'.$comentario->creador.' '.$comentario->fecha_creacion.'</b></h5>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <h5>'.$comentario->observacion.'</h5>
                                </div>
                            </div>
                        </div>';
                }
                ?>
                {{patternCurp}}-->
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="input-group">
                        <input type="text" class="form-control" id="comentario">
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

/*$("ul.nav-tabs > li > a").on("shown.bs.tab", function(e) {
    $("#chat").scrollTop($("#chat")[0].scrollHeight);
});*/

function recargar_conversacion(){
    $.post( url + "Clientes/Observaciones", { id_cliente : <?= $informacion->id_cliente ?> } ).done(function( data ){
        data = JSON.parse(data);
            $.each( data, function( i, v ){
                $('#Comentarios').append( 
                    '<div class="col-md-12" style="border-radius: 5px; border: 2px solid #F1F1F3;margin: 10px 0;">\
                            <div class="row">\
                                <div class="col-md-12">\
                                    <h5><b>'+v.creador+' '+v.fecha_creacion+'</b></h5>\
                                </div>\
                            </div>\
                            <div class="row">\
                                <div class="col-md-12">\
                                    <h5>'+v.observacion+'</h5>\
                                </div>\
                            </div>\
                        </div>\
                ' );
            });           
    });
}

$("#agregar_comentario").click( function(){
    $.post( url + "Clientes/Comentar", { id : <?=$informacion->id_cliente ?>, observacion : $("#comentario").val() }).done( function( data ){
        data = JSON.parse( data );
        $("#comentario").val("");
        $('#Comentarios').append( 
            '<div class="col-md-12" style="border-radius: 5px; border: 2px solid #F1F1F3;margin: 10px 0;">\
                    <div class="row">\
                        <div class="col-md-12">\
                            <h5><b>'+data.creador+' '+data.fecha_creacion+'</b></h5>\
                        </div>\
                    </div>\
                    <div class="row">\
                        <div class="col-md-12">\
                            <h5>'+data.observacion+'</h5>\
                        </div>\
                    </div>\
                </div>\
        ' );
    });
});

</script>