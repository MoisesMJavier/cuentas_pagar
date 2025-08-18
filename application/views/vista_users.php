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
                        <h3>ALTA DE USUARIOS</h3>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <table class="table table-striped" id="tabla_usuarios" name="tabla_usuarios">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>NOMBRES</th>
                                            <th>CORREO</th>
                                            <th>TIPO USUARIO</th>
                                            <th>DEPARTAMENTO</th>
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
<div class="modal fade" id="modalUpdate" role="dialog">
    <div class="modal-dialog">
               <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="exampleModalLabel">INFORMACIÓN DEL USUARIO</h4>
            </div>
            <div class="modal-body">
                <form id="usuario_form">
                    <div id="updateInfoUser">
                        <div class="row">
                            <div class="col-lg-6 form-group">
                                <label><b>NOMBRE(S)</b><span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nombre" name="nombre" required>
                            </div>
                            <div class="col-lg-6 form-group">
                                <label><b>APELLIDO(S)</b><span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="apellidos" name="apellidos" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 form-group">
                                <label><b>USUARIO</b><span class="text-danger">*</span></label>
                                <input type="text" class="form-control duplicidad_usuario" id="usuario" name="usuario" required>
                            </div>
                            <div class="col-lg-6 form-group">
                                <label><b>CONTRASEÑA</b><span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password" name="password" required autocomplete="new-password">
                                    <div class="input-group-btn">
                                        <button type="button" class="btn btn-default password_eye"><i class="far fa-eye"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 form-group">
                                <label><b>CORREO</b><span class="text-danger">*</span></label>
                                <input type="email" class="form-control duplicidad_correo" id="correo" name="correo" required>
                            </div>
                            <div class="col-lg-6 form-group">
                                <label><b>TIPO DE USUARIO</b><span class="text-danger">*</span></label>
                                <select id="rol" name="rol" class="form-control" required></select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 form-group">
                                <label><b>DEPARTAMIENTO</b><span class="text-danger">*</span></label>
                                <select id="depto" name="depto" class="form-control ldepartamentos" disabled required></select>
                            </div>
                            <div class="col-lg-12 form-group">
                                <label>DIRECTOR ÁREA</b><span class="text-danger">*</span></label>
                                <select id="director_area" name="director_area" class="form-control ldirectores" disabled required></select>
                            </div>
                            <div class="col-lg-12 form-group">
                                <label><b>EMPRESA</b><span class="text-danger">*</span></label><br/>
                                <select id="empresas" name="depto[]" class="form-control lempresas" required disabled multiple="multiple"></select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 form-group">
                            <h5><b>ESTADO DEL USUARIO</b><span class="text-danger">*</span></h5>
                            <label class="radio-inline"><input type="radio" name="estatus" value="1" checked required>ACTIVO</label>
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
<div class="modal fade" id="permisos_extra" role="dialog">
    <div class="modal-dialog">
               <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="exampleModalLabel">CONFIGURACION ADICIONALES</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12 col-12 form-group">
                        <label>COORDINADOR/SUPERVISOR</label><br/>
                        <select id="coordsup" class="form-control coordsup" multiple="multiple" ></select>
                    </div>
                    <div class="col-lg-12 col-12 form-group">
                        <label>DEPARTAMENTO:</label><br/>
                        <select id="deptos" class="form-control deptos" multiple="multiple" ></select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <button class="btn btn-configuracion_adicional btn-primary btn-block">GUARDAR</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="permisos_autoriza" role="dialog">
    <div class="modal-dialog">
               <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">AUTORIZACIONES</h4>
            </div>
            <div class="modal-body" style="width: 500px;">
                <form id="usuarioAut">
                    <div class="row">
                        <div class="col-lg-12 col-12 form-group">
                            <label>TIPO GASTO</label><br/>
                            <select id="gasto" class="form-control gasto" name="gasto[]" multiple="multiple" required></select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-12 form-group">
                            <label>DEPARTAMENTO:</label><br/>
                            <select id="dept" class="form-control dept" name="dept[]" multiple="multiple" required></select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-lg-12">
                            <button class="btn btn-primary btn-block" type="submit">GUARDAR</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

 <script type="text/javascript">

    let rol_usuario;
    var idusuario;
    var table_proceso;
    var lista_departamento;
    var listado_usuarios;
    var lista_empresas;
    var lista_directores;
    var dep_rol;
    var roles_empresa = [ 'CX', 'CT' ];
    var tipousuario = [];
    var tipo_gasto = [{idgasto:0,gasto:'Proveedor'},{idgasto:1,gasto:'Caja Chica'},{idgasto:2,gasto:'Tarjeta de Credito'}];
    // ARREGLO PARA LA EXCLUSION DE ALGUNAS OPCIONES A USUARIOS (PETICION DE SOPORTE).
    // EN UN FUTURO  OPTIMIZAR ESTE PROCESO MEDIANTE BD O ALGO MAS DINAMICO.
    var idusuarios_nopermitidos = ['2579', '2582', '2583', '2416'];
    var idusuario_logeado;
    /***********************************************************************************************************************/
    /**********************************MULTI SELECT OPCIONES ***********************************************************/
    $( document ).ready(function(){
        $( ".dept, .gasto, .coordsup, .deptos, .lempresas, .gastD" ).select2({
            allowClear: true,
            placeholder: "Seleccione una opción",
            enableFiltering: true,
            buttonWidth:"100%"
        }); // 
    });


    $( document ).on( "click", ".btn-opciones_extra", function(){
        trglobal = $(this).closest('tr');
        

        $("#coordsup, #deptos").html("");

        var row = table_proceso.row( trglobal ).data();

        $.map( listado_usuarios, function( value, index ) {
            if( value.estatus == 1 && row.idusuario != value.idusuario );
                $("#coordsup").append( '<option value="'+value.idusuario+'">'+value.nombres+' '+value.apellidos+' '+(value.estatus == 1 ? 'ACTIVO' : 'INACTIVO')+'</option>' )
        });

        $.map( lista_departamento, function( value, index ) {
            //if( table_proceso.row( trglobal ).data().depto != value.departamento )
            $("#deptos").append( '<option value="'+value.iddepartamentos+'">'+value.departamento+'</option>' )
        });

        if( table_proceso.row( trglobal ).data().iddepto ){

            $("#deptos").val( (table_proceso.row( trglobal ).data().iddepto).split(",") );
        }

        if( table_proceso.row( trglobal ).data().sup ){
            $("#coordsup").val( (table_proceso.row( trglobal ).data().sup ).split(",") );
        }

        $.fn.select2.defaults.reset();
        $("#permisos_extra").modal();

    });

    $("#permisos_extra button.btn-configuracion_adicional").click( function(){
        
        enviar_post_64( function( response ){
                if( response.resultado ){

                    //TOMAMOS LA INFORMACION DEL ROW
                    var data_row = table_proceso.row( trglobal ).data()

                    //ACTUALIZAMOS LA INFORMACION QUE TENIA EL ROW POR LO SELECCIONADO
                    data_row.iddepto = ( $("#deptos").val() ).join(',');
                    data_row.sup = ( $("#coordsup").val() ).join(',');

                    //ACTUALIZAMOS LA INFORMACION DEL ROW DE LA TABLA
                    table_proceso.row( trglobal ).data( data_row );
                    //CERRAMOS EL MODAL
                    $("#permisos_extra").modal( 'toggle' );
                    //MENSAJE DE CONFIRMACION
                    alert( "¡Se ha actualizado la información del usuario!" )

                }else{
                    alert("¡Algo ha ocurrido! Intente mas tarde.")
                }
            },{ 
                idusuario : table_proceso.row( trglobal ).data().idusuario,
                supervisar : $("#coordsup").val(), 
                departamentos : $("#deptos").val() 
            }, url + "Usuarios_cxp/opciones_especiales/" );
    });

     // autorizacion de gastos
     $( document ).on( "click", ".btn-permisos", function(){
        trglobal = $(this).closest('tr');
        var row = table_proceso.row( trglobal ).data();
        $("#gasto, #dept").html("");
        
        $.each( lista_departamento, function( i, v){
            if( table_proceso.row( trglobal ).data().depto != v.departamento )
                $("#dept").append( '<option value="'+v.iddepartamentos+'">'+v.departamento+'</option>' )                   
        });

        $.each(tipo_gasto, function (i,v){
            $("#gasto").append( '<option value="'+v.idgasto+'">'+v.gasto+'</option>' )
        });

        if( table_proceso.row( trglobal ).data().permiso ){
            $("#gasto").val( (table_proceso.row( trglobal ).data().permiso ).split(",") );
            $("#dept").val( (table_proceso.row( trglobal ).data().deptosAut ).split(",") );
        }
   
        $("#permisos_autoriza").modal();

    });

    $('#usuarioAut').submit(function(e) {
            e.preventDefault();
        }).validate({
            submitHandler: function( form ) {
                var data = new FormData($(form)[0]);
                data.append("idusuario", table_proceso.row( trglobal ).data().idusuario);
                $.ajax({
                    url: url + "Usuarios_cxp/usuario_aut_gastos",
                    data: data,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    method: 'POST',
                    type: 'POST', 
                    success: function(data){
                        if( data.resultado ){
                            var data_row = table_proceso.row( trglobal ).data()
                            data_row.deptosAut = ( $("#dept").val() ).join(',');
                            data_row.permiso = ( $("#gasto").val() ).join(',');
                            table_proceso.row( trglobal ).data( data_row );
                            $("#permisos_autoriza").modal( 'toggle' );
                        }else{
                            alert("NO SE HA PODIDO COMPLETAR LA SOLICITUD");
                        }
                    },error: function( ){
                        alert("Algo salio mal, recargue su página.");
                    }
                }); 
            }
    });

    /***********************************************************************************************************************/

    function accion(datos,accion){
        datos = datos.split(",");
        $("#idregistro").val($.trim(datos[0]));
        document.getElementById("updateInfoUser").style.display = 'block';
        $("#modalUpdate").modal();
        $("#accion").val(accion);
        if(accion=="registrar"){
            document.getElementById("nombre").readOnly = false;
            document.getElementById("apellidos").readOnly = false;
            document.getElementById("usuario").readOnly = false;
            document.getElementById("correo").readOnly = false;
            document.getElementById("rol").disabled = false;
            document.getElementById("depto").disabled = false;
            document.getElementById("director_area").disabled = false;
            document.getElementById("empresas").disabled = false;
            $("#modalUpdate .modal-title").text("NUEVO REGISTRO");
            limpia_form();
            link_post=url + "Usuarios_cxp/registrar_nuevo_usuario";
        }else if(accion=="editar"){
            $("#modalUpdate .modal-title").text("EDITANDO REGISTRO #"+datos[0]);
        }
    }

    $(".password_eye").click( function(){
		if($("#password").prop("type") == 'password'){
			$(this).html( '<i class="far fa-eye-slash"></i>' );
			$("#password").prop("type", "text");
		}else{
			$(this).html( '<i class="far fa-eye"></i>' );
			$("#password").prop("type", "password");
		}
	});

    function limpia_form(){
        $("#usuario_form input.form-control").val("");
        $("#usuario_form select").val("");
        $("#director_area").prop( "required" ,true);
        $("input[name='estatus'][value='1']").prop("checked", true)
    }

    $(".lista_departamentos").ready( function(){
        $("#director_area.ldirectores, #depto.ldepartamentos, #rol").html("<option value=''>Seleccione una opción.</option>");
        $.getJSON( url + "Listas_select/lista_user_page" ).done( function( data ){
            if(data.respuesta){


                //lista_directores = data.directores;
                /*
                $.map( lista_directores, function( v, i){
                    $("#director_area.ldirectores").append('<option value="'+v.idusuario+'" data-depto = "'+v.adeptos+'">'+v.nombres+' '+v.apellidos+'</option>');
                });
                */
                lista_departamento = data.departamento;
                $.map( lista_departamento, function( v, i){
                    $("#depto.ldepartamentos").append('<option value="'+v.departamento+'">'+v.departamento+'</option>');                    
                });

                lista_empresas = data.empresa;
                $.map( lista_empresas, function( v, i){
                    $("#empresas.lempresas").append('<option value="'+v.idempresa+'">'+v.nombre+'</option>');                    
                });


                $.map( data.roles, function( v, i){
                    if(v.idrol != 'CJ')
                        $("#rol").append('<option value="'+v.idrol+'">'+v.descripcion+'</option>');
                    
                    tipousuario[v.idrol]=v.descripcion;
                });
                
            }else{
                alert("OPS, algo ha ocurrido.");
            }
        });
    });

    $("#rol").change( function(){
        rol_seleccionado = $( this ).val();

        $("#empresas, #depto, #director_area").prop("disabled", true).val("");
        // habilitar select Cajas_ch
        if( roles_empresa.includes( rol_seleccionado ) ){
            $("#empresas").prop("disabled", false);
        }else{
            $("#depto").prop("disabled", false);
        }

    });

    $("#depto").change( function(){
        departamento = $( this ).val();
        
        $("#director_area.ldirectores").html("<option value=''>Seleccione una opción.</option>");
        $.map( lista_directores, function( v, i){
            if( ( v.adeptos ).split(',').includes( departamento ) )
                $("#director_area.ldirectores").append('<option value="'+v.idusuario+'">'+v.nombres+' '+v.apellidos+'</option>');
        });

        $("#director_area").prop("disabled", !( $("#director_area option").length > 1 ));
        
    });

    $("#tabla_usuarios").ready( function () {

        $('#tabla_usuarios').on('xhr.dt', function ( e, settings, json, xhr ) {
            listado_usuarios = json.data;
            lista_directores = json.directores;
            rol_usuario = json.rol;
            idusuario_logeado = json.idusuario;
        });

        $('#tabla_usuarios thead tr:eq(0) th').each( function (i) {
            if( i < $('#tabla_usuarios thead tr:eq(0) th').length - 1 ){
                var title = $(this).text();
                $(this).html( '<input type="text" style="font-size: .8em; width: 100%;" class="form-control" placeholder="'+title+'" />' );
        
                $( 'input', this ).on( 'keyup change', function () {
                    if ( table_proceso.column(i).search() !== this.value ) {
                        table_proceso
                            .column(i)
                            .search( this.value )
                            .draw();
                    }
                } );
            }
        });

        table_proceso = $('#tabla_usuarios').DataTable({
            dom: 'Brtip',
             "buttons": [
                {
                   extend: 'excelHtml5',             
                   text: '<i class="fas fa-plus"></i> AGREGAR USUARIO',
                   messageTop: "Nuevo",
                   attr: {
                       class: 'btn btn-warning'       
                   },action : function(e){
                       accion('0,',"registrar");
                   }
               },
               {
                   extend: 'excelHtml5',             
                   text: '<i class="fas fa-file-excel"></i>',
                   messageTop: "Listado de Usuario CXP",
                   attr: {
                       class: 'btn btn-success btn-excel'       
                   },
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4],
                        format: {
                            header: function (data, columnIdx) { 

                                data = data.replace( '<input type="text" style="font-size: .8em; width: 100%;" class="form-control" placeholder="', '' );
                                data = data.replace( '">', '' )
                                return data;
                            }
                        }
                    }  
               },
            ],
            "language" : lenguaje,
            "processing": false,
            "pageLength": 10,
            "bAutoWidth": false,
            "bLengthChange": false,
            "bInfo": false,
            "searching": true,
            "scrollX":true,
            "columns": [
                { 
                    "data": function( d ){
                        return '<p style="font-size: .8em">'+d.nombres+" "+d.apellidos+'</p>';
                    } 
                },
                { 
                    "data": function( d ){
                        return '<p style="font-size: .8em">'+d.correo+'</p>';
                    } 
                },
                {
                    "data": function( d ){
                        return '<p style="font-size: .8em">'+( d.rol_descripcion.toUpperCase() )+'</p>'
                    }
                },
                {
                    "data": function( d ){
                        return '<p style="font-size: .8em">'+((d.rol == 'CE') ? 'DEVOLUCIONES' : ( (d.rol == 'CT' || d.rol == 'CX' ) ? 'CONTABILIDAD' : d.depto))+'</p>';
                    }
                },
                {
                    "data": function( d ){
                        return '<p style="font-size: .8em">'+(d.estatus == 1 ? "<label class='label pull-center bg-green'>ACTIVO</label>" : "<label class='label pull-center bg-red'>DESACTIVADO</label>")+'</p>';
                    }
                },
                { 
                    "data": function( d ){
                        
                        if( rol_usuario == "SO" || rol_usuario == "DA" ){
                            opciones = '<div class="btn-group-vertical" role="group">';
                            opciones += '<button type="button" class="btn btn-warning btn-editar btn-sm" title="Editar Usuario"><i class="fas fa-user-edit"></i></button>';
                            opciones += !idusuarios_nopermitidos.includes(idusuario_logeado) ? '<button type="button" class="btn btn-default btn-opciones_extra btn-sm" title="Opciones Extra"><i class="fas fa-cogs"></i></button>':'';
                            opciones += d.rol == 'DA' ? '<button type="button" class="btn btn-primary btn-permisos btn-sm" title=""><i class="fas fa-cog"></i></button>':'';
                            opciones += !idusuarios_nopermitidos.includes(idusuario_logeado) ? '<button type="button" class="btn btn-danger btn-borrar-usuario btn-sm" title="Borrar usuario"><i class="fas fa-trash"></i></button>':'';
                            
                            return opciones + '</div>';
                        }else{
                            return "";
                        }

                    },
                    "orderable": false 
                }
            ],
            "ajax": {
                "url": url + "Usuarios_cxp/tabla_autorizaciones",
                "type": "POST",
                cache: false,
            }
        });
        
                 
        $("#tabla_usuarios").on( "click", ".btn-editar", function(){
            $("#modalUpdate .form-control").val('');
            tr = $(this).closest('tr');
            
            var row = table_proceso.row( tr );

            idusuario = row.data().idusuario
            accion(idusuario+",","editar");
            $("input[name='nombre']").val( row.data().nombres );
            $("input[name='apellidos']").val( row.data().apellidos );

            $("input[name='usuario']").val( row.data().nickname );
            $("input[name='correo']").val( row.data().correo );   

            $("input[name='password']").val( row.data().pass );
            $("input[name='estatus'][value='"+row.data().estatus+"']").prop( "checked", true );        
            $("#rol option[value='"+row.data().rol+"']").prop( "selected", true ).change();  

            if( row.data().rol == 'CT' || row.data().rol == 'CX' ){
                let idsEmpresas = row.data().depto.split(",").map(val => val.trim());
                $("#empresas.lempresas").val(idsEmpresas).change();
            }else{
                $("#depto.ldepartamentos").val( row.data().depto ).change().prop( "disabled", false );
                $("#director_area.ldirectores").val( row.data().da );
            }

            $("#director_area.ldirectores").val( row.data().da );

            link_post = 'Usuarios_cxp/updateUsuarios'
            /*!idusuarios_nopermitidos.includes(idusuario_logeado)
                ? document.getElementById("updateInfoUser").style.display = 'block'
                : document.getElementById("updateInfoUser").style.display = 'none';*/
            if(idusuarios_nopermitidos.includes(idusuario_logeado)){
                document.getElementById("nombre").readOnly = true;
                document.getElementById("apellidos").readOnly = true;
                document.getElementById("usuario").readOnly = true;
                document.getElementById("correo").readOnly = true;
                document.getElementById("rol").disabled = true;
                document.getElementById("depto").disabled = true;
                document.getElementById("director_area").disabled = true;
                document.getElementById("empresas").disabled = true;
            }
            $("#modalUpdate").modal();

        });  

        $("#tabla_usuarios").on( "click", ".btn-borrar-usuario", function(){
            //MAR
            tr = $(this).closest('tr');
            
            var row = table_proceso.row( tr );

            if (confirm("¿Estás seguro de eliminar el Usuario:" +row.data().nombres+row.data().apellidos + "?")) {
                $.post(url + "Usuarios_cxp/borrarUsuario", {
                    idusuario: row.data().idusuario
                }).done(function(data) {
                    data = JSON.parse(data);
                    if (data.resultado) {
                        table_proceso.ajax.reload(null, false);
                    } else {
                        alert("HA OCURRIDO UN ERROR")
                    }
                });

            }

        });
    }); 

    $('#usuario_form').submit(function(e) {
         e.preventDefault();
     }).validate({
        submitHandler: function( form ) {
            $("#modalUpdate .form-control").prop( "disabled", false );

            var data = new FormData( $(form)[0] );

            data.append("idusuario", idusuario);
            var resultado = enviar_post(data,link_post);
            if( !resultado[0] ){
                alert("NO SE HA PODIDO COMPLETAR LA SOLICITUD");
            }else{
                $("#modalUpdate").modal( 'toggle' );
                table_proceso.ajax.reload();
            }
            
         }
    });

    $(window).resize(function(){ /** INICIO FECHA: 07-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
        table_proceso.columns.adjust().draw();
    });

    $('.sidebar-toggle').click(function() {
        setTimeout(function() {
            // 1. Ajustar columnas
            table_proceso.columns.adjust();
            // 2. Forzar actualización de encabezados
            var headerCellsTableProceso = $('#tabla_usuarios thead th');
            headerCellsTableProceso.each(function() {
                var newWidth = $(this).width(); // Obtener ancho actualizado
                $(this).css('width', newWidth + 'px'); // Aplicarlo
            });
            // 3. Redibujar (sin resetear paginación)
            table_proceso.draw(false);
        }, 300); // Esperar a que termine la animación del sidebar
    }); /** FIN FECHA: 07-MAYO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
</script>
<?php
    require("footer.php");
?>

 