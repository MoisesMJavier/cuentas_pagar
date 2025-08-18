<?php
    require("head.php");
    require("menu_navegador.php");
?> 
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <div class="box">
                    <div class="box-header with-border">
                        <h3>LISTADO DE CAPTURISTAS</h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <table class="table table-striped" id="listado_capturistas">
                                    <thead>
                                        <tr>  
                                            <th>NOMBRE</th>
                                            <th>USUARIO</th>
                                            <th>TIPO USUARIO</th>
                                            <th>ESTATUS</th>
                                            <th></th>
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
</div>
<div id="modal_formulario_capturista" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="exampleModalLabel">INFORMACIÓN DE CAPTURISTA</h4>
            </div>
            <div class="modal-body">
                <form id="capturista_form">
                    <div class="row">
                        <div class="col-lg-6 form-group">
                            <label>NOMBRE(S)</label>
                            <input type="text" class="form-control" name="nombre_usuario" required>
                        </div>
                        <div class="col-lg-6 form-group">
                            <label>APELLIDO(S)</label>
                            <input type="text" class="form-control" name="apellido_usuario" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 form-group">
                            <label>USUARIO</label>
                            <input type="text" class="form-control duplicidad_usuario" name="usuario" required>
                        </div>
                        <div class="col-lg-4 form-group">
                            <label>CONTRASEÑA</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password_usuario" name="password_usuario" required>
                                <div class="input-group-btn">
									<button type="button" class="btn btn-default password_eye"><i class="far fa-eye"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 form-group">
                            <label>ROL DE USUARIO</label>
                            <select type="text" class="form-control" id="rol_usuario" name="rol_usuario" required>
                                <option value="AS">ASISTENTE</option>
                                <option value="CA">CAPTURISTA</option>
                            </select>
                        </div>
                        
                    </div>
                    <div class="row">
                        <div class="col-lg-6 form-group">
                            <label>CORREO</label>
                            <input type="email" class="form-control duplicidad_correo" name="correo_usuario" required>
                        </div>
                        <div class="col-lg-6 form-group">
                            <h5><b>ESTADO DEL USUARIO<b></h5>
                            <label class="radio-inline"><input type="radio" name="estatus" value="1" required>ACTIVO</label>
                            <label class="radio-inline"><input type="radio" name="estatus" value="0" >INHABILITADO</label>
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
</div>
<script>

    var listado_capturistas;
    var idusuario = null;
    var link_post;

    $(".password_eye").click( function(){
		if($("#password_usuario").prop("type") == 'password'){
			$(this).html( '<i class="far fa-eye-slash"></i>' );
			$("#password_usuario").prop("type", "text");
		}else{
			$(this).html( '<i class="far fa-eye"></i>' );
			$("#password_usuario").prop("type", "password");
		}
	});

    $("#capturista_form").submit( function(e) {
        e.preventDefault();
    }).validate({
        submitHandler: function( form ) {

            var data = new FormData( $(form)[0] );
            data.append("idusuario", idusuario);

            $.ajax({
                url: url + link_post,
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                method: 'POST',
                type: 'POST', // For jQuery < 1.9
                success: function(data){

                    if( !data[0] ){
                        alert("NO SE HA PODIDO COMPLETAR LA SOLICITUD");
                    }
                    $("#modal_formulario_capturista").modal( 'toggle' );
                    listado_capturistas.ajax.reload();
                },error: function( ){
                    
                }
            });
        }
    });

    $( document ).on( "click", ".ventana_capturista", function(){
        link_post = 'Capturistas/nuevo_capturista';
        $("#modal_formulario_capturista .form-control").val('').prop( "checked", false );
        $("#modal_formulario_capturista").modal();
    });

    $("#listado_capturistas").ready( function(){
        listado_capturistas = $("#listado_capturistas").DataTable({
            dom: 'Bfrtip',
            "buttons": [
                {
                    text: '<i class="fas fa-plus"></i> NUEVO CAPTURISTA',
                    attr: {
                        class: 'btn btn-danger ventana_capturista'
                    }
                },
                {
                    extend: 'excelHtml5',             
                    text: '<i class="fas fa-file-excel"></i> EXPORTAR EXCEL',
                    messageTop: "Lista de pagos autorizados por Dirección General (CHEQUES)",
                    attr: {
                        class: 'btn btn-success'       
                    },
                    exportOptions: {
                        columns: [0,1,2,3,4],
                        format: {
                            header: function (data, columnIdx) { 

                                data = data.replace( '<input type="text" style="width:100%;" placeholder="', '' );
                                data = data.replace( '">', '' )
                                return data;
                            }
                        }
                    }
                }
            ],
            "language":lenguaje,
            "processing": false,
            "pageLength": 10,
            "bAutoWidth": false,
            "bLengthChange": false,
            "scrollX": true,
            "bInfo": false,
            "searching": true,
            "columns": [
                { 
                    "data" : function( d ){
                        return d.nombres+" "+d.apellidos;
                    } 
                },
                { 
                    "data" : function( d ){
                        return d.nickname
                    } 
                },
                { 
                    "data" : function( d ){
                        return d.rol == 'AS' ? "ASISTENTE" : "CAPTURISTA";
                    } 
                },
                { 
                    "data" : function( d ){
                        return d.estatus ? "ACTIVO" : "---";
                    } 
                },
                {
                    "orderable": false,
                    "data": function( d ){
                        var opciones =  '<div class="btn-group-vertical">';
                        opciones += '<button type="button" class="btn btn-warning editar_usuario"><i class="fas fa-edit"></i></button>';

                        if( d.estatus ){
                            opciones += '<button type="button" class="btn btn-danger on_off"><i class="fas fa-power-off"></i></button>';
                        }else{
                            opciones += '<button type="button" class="btn btn-success on_off"><i class="fas fa-power-off"></i></button>';
                        }

                        return opciones += '</div>';
                    }
                }
            ],
            "order": [[1, 'asc']],
            "ajax": url + "Capturistas/TablaCapturistas",
        });

        $("#listado_capturistas").on( "click", ".editar_usuario", function(){
            tr = $(this).closest('tr');
            var row = listado_capturistas.row( tr );

            idusuario = row.data().idusuario

            $("input[name='nombre_usuario']").val( row.data().nombres );
            $("input[name='apellido_usuario']").val( row.data().apellidos );
            $("input[name='usuario']").val( row.data().nickname );

            $("#rol_usuario option[value='"+row.data().rol+"']").prop( "selected", true );
            
            $("input[name='password_usuario']").val( row.data().pass );
            $("input[name='correo_usuario']").val( row.data().correo );
            $("input[name='estatus'][value='"+row.data().estatus+"']").prop( "checked", true );

            link_post = 'Capturistas/editar_capturista'

            $("#modal_formulario_capturista").modal();

        });
    });

</script>
<?php
    require("footer.php");
?>