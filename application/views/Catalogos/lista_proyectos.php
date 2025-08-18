<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <div class="box">
                    <div class="box-header with-border">
                        <h3>PROYECTOS</h3>
                    </div>
                    <div class="box-body">
                        <div class="tab-content">
                            <div class="row">
                                <div class="col-lg-12">
                                    <table class="table table-striped" id="tabla_historial">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th style="font-size: .9em"></th>
                                                <th style="font-size: .9em"></th>
                                                <th style="font-size: .9em">CONCEPTO</th>
                                                <th style="font-size: .9em">ESTATUS</th>
                                                <th style="font-size: .9em">F REGISTRO</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div><!--End tab content-->
                </div><!--end box-->
            </div>
        </div>
    </div>
</div>

<div class="modal fade modal-alertas" id="myModalProv" role="info">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"  style="background: #DD4B39; color: #fff;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">ELIMINAR</h4>
            </div>
            <form method="post" id="form_eliminar">
                <div class="modal-body">

                </div>
                <div class="modal-footer"></div>

            </form>
        </div>
    </div>
</div>

<!--modal update-->

<div class="modal fade" id="modalUpdate" role="dialog">
    <div class="modal-dialog">
     <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title" id="exampleModalLabel">INFORMACIÓN DEL PROVEEDOR</h4>
        </div>
        <div class="modal-body">
            <form id="proveedor_form">
                <p>Información del proveedor. Complete los campos requeridos (<span class="text-danger">*</span>)</p>
                <div class="row">
                    <div class="col-lg-6 form-group">
                        <label>NOMBRE(S)<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nombreActual" name="nombreActual"  class="form-control tf w-input" required  onKeyPress="if(this.value.length==100) return false;return check(event);">
                    </div>
                    <div class="col-lg-6 form-group">
                        <label>RFC</label>
                        <input type="text" class="form-control" id="rfcA" name="rfcA"  onKeyPress="if(this.value.length==13) return false;return check(event);" class="form-control tf w-input"  minlength="12">
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 form-group">
                        <label>CONTACTO</label>
                        <input type="text" class="form-control" id="contactoA" name="contactoA">
                    </div>
                    <div class="col-lg-6 form-group">
                        <label>EMAIL</label>
                        <input type="email" class="form-control" id="emailA" name="emailA" onKeyPress="if(this.value.length==150) return false;">
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 form-group">
                        <label>TIPO DE CUENTA<span class="text-danger">*</span></label>
                        <select class="form-control" id="tipoctaA" name="tipoctaA"  >
                            <option value="">Seleccione una opción</option>
                            <option value="1">Cuenta en Banco del Bajio</option>
                            <option value="3">Tarjeta de debito</option>
                            <option value="40">CLABE</option>
                        </select>
                    </div>
                    <div class="col-lg-6 form-group">
                        <label>BANCO<span class="text-danger">*</span></label>
                        <select id="idbancoA" name="idbancoA" class="form-control bancos"></select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 form-group">
                        <label>NO.CUENTA<span class="text-danger">*</span></label>
                        <input type="text"  id="cuentaA" name="cuentaA" class="form-control"  onkeypress="if(event.which &lt; 48 || event.which &gt; 57 ) if(event.which != 8) return false;">
                    </div>
                    <div class="col-lg-6 form-group">
                        <label>SUCURSAL</label>
                        <select  id="idsucursalA" name="idsucursalA" class="form-control sucursal"></select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4 form-group">
                        <label>TIPO DE PROVEEDOR<span class="text-danger">*</span></label>
                        <select id="tipoprovA" name="tipoprovA" class="form-control tprov" data-value="idusuarioA" required>
                            <option value="">Seleccione una opción</option>
                            <option value="1">INTERNO</option>
                            <option value="0">EXTERNO</option>
                            <option value="2">EMPRESA</option>
                            <option value="3">IMPUESTO</option>
                        </select>
                    </div>
                    <div class="col-lg-4 form-group">
                        <label>FACTURACIÓN<span class="text-danger">*</span></label>
                        <select id="excepcion_factura_edita" name="excepcion_factura" class="form-control tprov" data-value="idusuarioA" required>
                            <option value="">Seleccione una opción</option>
                            <option value="0">OBLIGATORIO CARGAR XML</option>
                            <option value="1">XML POSTERIOR AL PAGO</option>
                            <option value="2">NUNCA SE RECIBIRA FACTURA</option>
                        </select>
                    </div>
                    <div class="col-lg-4 form-group">
                        <label>PROVEEDOR<span class="text-danger">*</span></label>
                        <select  id="idusuarioA" name="idusuarioA" class="form-control usuario" required>
                            <option value="">Seleccione una opción</option>                             
                        </select>
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
</div><!-- FIN MODAL -->


<div class="modal fade" id="mProyectos" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="exampleModalLabel">PROYECTO</h4>
            </div>
            <div class="modal-body">
                <form id="proyecto_proveedores">
                    <div class="row">
                        <div class="col-lg-12 form-group">
                            <h5>CONCEPTO DEL PROYECTO<span class="text-danger">*</span></h5>
                            <input name="nproyecto_neo" class="form-control" type="text" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 form-group">
                            <h5>EMPRESA<span class="text-danger">*</span></h5>
                            <select class="form-control " id="empresa" type="text" name="idempresa" required></select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 form-group">
                            <h5>DESCRIPCIÓN</h5>
                            <textarea name="descripcion" class="form-control" type="text"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 form-group ">
                            <h5><b>ESTADO DEL USUARIO</b><span class="text-danger">*</span></h5>
                            <label class="radio-inline "><input type="radio" name="idestatus" value="1" checked required>ACTIVO</label>
                            <label class="radio-inline "><input type="radio" name="idestatus" value="0">INHABILITADO</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 form-group">
                            <button class="btn btn-primary btn-block">GUARDAR</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="mImportador" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="exampleModalLabel">PROYECTO/EMPRESA</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <form id="actualizacion_masiva">
                        <div class="col-lg-12 form-group">
                            <label>DOCUMENTO EXCEL</label>                            
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="input-group">
                                        <input type="file" class="form-control" id="excel_importador" required>                                
                                        <div class="input-group-btn">
                                            <button type="submit" class="btn btn-danger cargar_complemento"><i class="fas fa-upload"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>                           
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!--CREAR UN PAGO PROGRAMADO-->
<div id="mEmpresaProyecto" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="exampleModalLabel">PROYECTO/EMPRESA</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12 form-group">
                        <label>EMPRESA</label>
                        <div class="input-group">
                            <select class="form-control" id="proy_empresa"></select>
                            <div class="input-group-prepend">
                                <button type="button" id="addEmpresa" class="btn btn-primary btn-block">+</button>
                            </div>
                        </div>    
                    </div>
                </div>
                <hr/>
                <form id="formulario_generico" action="#">
                    <div class="row" style="overflow: scroll; height: 200px;" id="inputs"></div>
                    <div class="row">
                        <div class="col-lg-12 form-group">
                            <button class="btn btn-success btn-block"><i class="fas fa-save"></i> GUARDAR</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">

    var empresa;
    var link_post;
    var table_proceso;
    
    var proveedores_dinamicos = "";
    var opciones = "";
    var tr_global;

    $("#tabla_historial").ready( function () {
        llenado_select();
        

        $('#tabla_historial').on('xhr.dt', function ( e, settings, json, xhr ) {
            empresas = json.empresas
            opciones = json.opciones;
        });

        $('#tabla_historial thead tr:eq(0) th').each( function (i) {
            if( i > 1 && i < $('#tabla_historial thead tr:eq(0) th').length - 1 ){
                var title = $(this).text();
                $(this).html( '<input type="text" style="font-size: .9em; width: 100%;" class="form-control" placeholder="'+title+'" title="'+title+'"/>' );

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

        table_proceso = $('#tabla_historial').DataTable({
            dom: 'Brtip',
            "buttons": [
                {
                    extend: 'excelHtml5',             
                    text: '<i class="fas fa-file-excel"></i> ',
                    messageTop: "Lista de proveedores",
                    attr: {
                        class: 'btn btn-success'    
                    },
                    exportOptions: {
                        columns: [1,2,3,4,5,6,7,8,9,10],
                        format: {
                            header: function (data, columnIdx) { 

                                data = data.replace( '<input type="text" style="font-size: .9em; width: 100%;" class="form-control" placeholder="', '' );
                                data = data.replace( '">', '' )
                                data = data.split(" ")[0].replace(String.fromCharCode(34), "");
                                return data;
                            }
                        }
                    }
                },
                {
                    text: '<i class="fas fa-plus"></i>',
                    action: function(){
                        tr_global = false;
                        link_post = "Catalogos/InsertNuevoRegistro";
                        
                        $("#mProyectos #proyecto_proveedores input[type='text']").val("");
                        $("#mProyectos #proyecto_proveedores input[type='radio'][value='1']").prop("checked", true);
                        $("#mProyectos #proyecto_proveedores textarea").val("").html("");
                        $("#mProyectos #proyecto_proveedores select" ).html( "<option value=''>Seleccione una opción</option>" );
                        $.each( selectempresas, function( i, v ){
			            $("#mProyectos #proyecto_proveedores select" ).append( "<option value='"+v.idempresa+"'>"+v.nombre+"</option>" );});
                        $("#mProyectos").modal();
                    },
                    attr: {
                        class: 'btn btn-primary',
                    }
                },
                {
                    text: '<i class="fas fa-upload"></i>',
                    action: function(){
                        $("#mImportador").modal();
                    },
                    attr: {
                        class: 'btn btn-info',
                    }
                }
                /*,
                {
                    text: '<i class="fas fa-trash-alt"></i>',
                    action: function(){

                    },
                    attr: {
                        class: 'btn btn-danger',
                    }
                },
                {
                    text: '<i class="fas fa-power-off"></i>',
                    action: function(){

                    },
                    attr: {
                        class: 'btn btn-default',
                    }
                },*/
            ],
            "language" : lenguaje,
            "processing": false,
            "pageLength": 10,
            "bAutoWidth": false,
            "bLengthChange": false,
            "bInfo": false,
            "searching": true,
            "ordering": false,
            "scrollX": true,
            "columns": [
                { 
                    "width": "4%" 
                },
                {
                    "width": "4%",
                    "className": 'details-control',
                    "orderable": false,
                    "data" : null,
                    "defaultContent": '<i class="fas animacion fa-caret-right"></i>'
                },
                {
                "width": "5%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.nproyecto_neo+'</p>'
                    }
                },
                { 
                    "width": "8%",
                    "data" : function( d ){
                        if( d.idestatus == 1 )
                            return '<span class="label pull-center bg-green">ACTIVO</span>';
                        else
                            return'<span class="label pull-center bg-orange">NO DISPONIBLE</span>';
                    }
                },
                {  
                    "width": "10%",
                    "data" : function( d ){
                        return '<p style="font-size: .9em">'+d.fcreacion+'</p>'
                    }
                },
                { 
                    "width": "20%",
                    "data": function( d ){
                        return opciones;
                    },

                }
            ],
            columnDefs: [
                {
                    orderable: false,
                    className: 'select-checkbox',
                    targets:   0,
                    'searchable':false,
                    'className': 'dt-body-center',
                    'render': function (d, type, full, meta){
                        return '<input type="checkbox" name="id[]" style="width:1.5em;height:1.5em;">';     
                    },
                    select: {
                        style:    'os',
                        selector: 'td:first-child'
                    },
                },
                {
                    "searchable": false,
                    "orderable": false,
                    "targets": 1
                },
            ],
            "ajax": {
                "url": url + "Catalogos/TablaProyectos",
                "type": "POST",
                cache: false,
            }
        });

        $("#tabla_historial").on( "click", ".inactivar_proyecto, .borrar_proyecto", function(){
            tr_global = $(this).closest('tr');
            var row = table_proceso.row( $(this).closest('tr') ).data();
            nuevo_estatus = row.idestatus == 0 ;
            if( $( this ).data("estatus") ){
                nuevo_estatus = $( this ).data("estatus");
            }

            enviar_post_64( function( response ){
                
                if( nuevo_estatus != 3 ){
                    row.idestatus = nuevo_estatus;
                    table_proceso.row( tr_global ).data( row ).draw( false );
                }
                else
                    table_proceso.row( tr_global ).remove().draw( false );

            },{ nproyecto_neo : row.nproyecto_neo, nuevo_estatus : nuevo_estatus }, url + "Catalogos/cambiarEstatus/" );

        });

        concepto_neo = "";

        function lista_empresas( empresas, empresas_seleccionadas, click = true ){
            $("#proy_empresa").html( "<option value=''>Selecciona una opción</option>" );
            $.each( empresas, function( i, v ){

                $("#proy_empresa").append( "<option value='"+v.idempresa+"'>"+v.nombre+" "+v.abrev+"</option>" );

                if( empresas_seleccionadas.length > 0 ){
                    $.each( empresas_seleccionadas, function( ii, vv ){
                        if( click && v.idempresa == vv.idempresa ){
                            $("#proy_empresa").val( vv.idempresa );
                            concepto_neo = vv.nproyecto_neo;
                            $("#addEmpresa").click();
                        }
                    });
                }
                
            });

            concepto_neo = "";
        }

        $("#tabla_historial").on( "click", ".relacion_empresa", function(){
            tr_global = $(this).closest('tr');
            var row = table_proceso.row( $(this).closest('tr') ).data();
            if( !row.pryemp ){
                enviar_post_64( function( response ){
                    lista_empresas( empresas, response.proy_emp );
                    row.pryemp = response.proy_emp;
                    table_proceso.row( tr_global ).data( row );
                    $("#mEmpresaProyecto").modal();
                },{ concepto : row.concepto }, url + "Catalogos/Proyecto_Empresa/" );
            }else{
                lista_empresas( empresas, row.pryemp, true );
                $("#mEmpresaProyecto").modal();
            }
        });

        $("#tabla_historial").on( "click", ".editar_proyecto", function(){
            $("#proyecto_proveedores select[name='idempresa']").html("<option value=''>Seleccione una opción</option>");
            $.each( selectempresas, function( i, v ){
			$("#mProyectos #proyecto_proveedores select" ).append( "<option value='"+v.idempresa+"'>"+v.nombre+"</option>" );});
            tr_global = $(this).closest('tr');
            var row = table_proceso.row( $(this).closest('tr') ).data();
            $("#proyecto_proveedores select[name='idempresa'] option[value="+row.idempresa+"]").attr("selected", true);
            $("#proyecto_proveedores input[name='nproyecto_neo']").val( row.nproyecto_neo ); 
            $("#proyecto_proveedores textarea[name='descripcion']").val( (row.descripcion != null)?row.descripcion:" ");
            $("#proyecto_proveedores input[input='idestatus']").val( row.idestatus );
            link_post = "Catalogos/updateRegistro";
            $("#mProyectos").modal();

        });

        $("#tabla_historial").on( "click", ".relacion_empresa", function(){
            tr_global = $(this).closest('tr');
            var row = table_proceso.row( $(this).closest('tr') ).data();
            if( !row.pryemp ){
                enviar_post_64( function( response ){
                    lista_empresas( empresas, response.proy_emp );
                    row.pryemp = response.proy_emp;
                    table_proceso.row( tr_global ).data( row );
                    $("#mEmpresaProyecto").modal();
                },{ concepto : row.concepto }, url + "Catalogos/Proyecto_Empresa/" );
            }else{
                lista_empresas( empresas, row.pryemp, true );
                $("#mEmpresaProyecto").modal();
            }
        });

        $( document ).on( "click", "#addEmpresa", function(){
            if( $("#proy_empresa").val() ){
                $("#formulario_generico #inputs").append( '<div class="col-lg-12 form-group">'+
                    '<label><i class="fas fa-minus text-danger"></i> '+$("#proy_empresa option:selected").text()+'</label>'+
                    '<input type="hidden" class="form-control" value="'+$("#proy_empresa").val()+'" name="empresa_proy[]" required/>'+
                    '<input type="text" class="form-control" value="'+concepto_neo+'" name="concepto_proy[]" required/>'+ 
                '</div>' );

                $("#proy_empresa option:selected").remove()
            }

        });

        $( document ).on("click", "#formulario_generico #inputs div.col-lg-12 label i.fa-minus", function(){
            $( this ).closest('div.col-lg-12').remove();
            empresas_array = [];
            $.each( $("#formulario_generico #inputs input[name='empresa_proy[]']"), function( i, v ){
                empresas_array.push( { idempresa : $( this ).val() } );
            });
            lista_empresas( empresas, empresas_array, false )
        });
    });
    var selectempresas;
    function llenado_select( ){
            $.get(url + "Catalogos/empresas", function(data){
                selectempresas = JSON.parse(data);
            });
	    }
    $('#proyecto_proveedores').submit(function(e) {
        e.preventDefault();
    }).validate({
        submitHandler: function( form ) {

            var formulario = new FormData( $(form)[0] );
            formulario.append( "bdtabla", "cat_proyecto_empresa" );
            if( tr_global != false ){
                row = table_proceso.row( tr_global ).data();
                formulario.append( "idproyecto", row.idproyecto );
            }
            
            $.ajax({
                url : url + link_post,
                data: formulario,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                method: 'POST',
                type: 'POST',
                success: function(data){
                    if( data.resultado ){

                        if( tr_global != false ){
                            
                            row.concepto = formulario.get("concepto");
                            row.descripcion = formulario.get("descripcion");
                            row.estatus = formulario.get("estatus");
                            row.idempresa = formulario.get("idempresa");
                            table_proceso.row( tr_global ).data( row ).draw();

                        }else{
                            table_proceso.clear();
                            table_proceso.rows.add(data.data);
                            table_proceso.draw(false);
                        }
                       
                        
                        alert("Se ha realizado la tarea con éxito.");
                        $("#mProyectos").modal( 'toggle' );
                    }else{
                        alert("Algo ha salido mal. Intente más tarde.");
                    }
                },error: function( ){
                    
                }
            });
        }
    });

    $('#actualizacion_masiva').submit(function(e) {
        e.preventDefault();
    }).validate({
        submitHandler: function( form ) {
            var data = new FormData();
            data.append( "excel_importador", $("#actualizacion_masiva #excel_importador")[0].files[0]);
            data.append( "tipo_dato", 0 );
            $.ajax({
                url : url + "Catalogos/uExcel",
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                method: 'POST',
                type: 'POST',
                success: function(data){
                    $("#mImportador").modal( 'toggle' );
                    alert(data.mensaje)
                    table_proceso.ajax.reload();
                },error: function( ){
                    
                }
            });
        }
    });

    $('#formulario_generico').submit(function(e) {
        e.preventDefault();
    }).validate({
        submitHandler: function( form ) {
            var data = new FormData( $(form)[0] );
            data.append("proyecto", table_proceso.row( tr_global ).data().concepto);
            $.ajax({
                url : url + "Catalogos/insertProyEmp",
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                method: 'POST',
                type: 'POST',
                success: function(data){
                    $("#modal_formulario_generico").modal( 'toggle' );
                },error: function( ){
                    
                }
            });
        }
    }); 

</script>