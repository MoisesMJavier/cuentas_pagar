<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <div class="box">
                    <div class="box-header with-border">
                        <h3>PROVEEDORES</h3>
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
                                                <th style="font-size: .9em">NOMBRE PROVEEDOR</th>
                                                <th style="font-size: .9em">RFC</th>
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
                            <input name="concepto" class="form-control" type="text" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 form-group">
                            <h5>DESCRIPCIÓN</h5>
                            <textarea name="descripcion" class="form-control" type="text"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 form-group">
                            <h5><b>ESTADO DEL USUARIO</b><span class="text-danger">*</span></h5>
                            <label class="radio-inline"><input type="radio" name="estatus" value="1" checked required>ACTIVO</label>
                            <label class="radio-inline"><input type="radio" name="estatus" value="0">INHABILITADO</label>
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
                <h4 class="modal-title" id="exampleModalLabel">PROVEEDOR/EMPRESA</h4>
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
                                            <button type="submit" class="btn btn-danger"><i class="fas fa-upload"></i></button>
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

        $('#tabla_historial').on('xhr.dt', function ( e, settings, json, xhr ) {

            if( !['CX'].includes( json.rol ) ){
                //table_proceso.button( '2' ).remove();
                table_proceso.button( '1' ).remove();
            }

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
                    "width": "40%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.nproveedor+'</p>'
                    }
                },
                { 
                    "width": "10%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.rfc+'</p>'
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
                "url": url + "Catalogos/TablaProveedores",
                "type": "POST",
                cache: false,
            }
        });

        $("#tabla_historial").on( "click", ".inactivar_proyecto, .borrar_proyecto", function(){
            tr_global = $(this).closest('tr');
            var row = table_proceso.row( $(this).closest('tr') ).data();
            nuevo_estatus = row.estatus == 0 ;
            if( $( this ).data("estatus") ){
                nuevo_estatus = $( this ).data("estatus");
            }

            enviar_post_64( function( response ){
                
                if( nuevo_estatus != 3 ){
                    row.estatus = nuevo_estatus;
                    table_proceso.row( tr_global ).data( row ).draw( false );
                }
                else
                    table_proceso.row( tr_global ).remove().draw( false );

            },{ concepto_proyecto : row.concepto, nuevo_estatus : nuevo_estatus }, url + "Catalogos/cambiarEstatus/" );

        });

        concepto_neo = "";
        moneda_prov = "";
        tasa_prov = "";

        function lista_empresas( empresas, empresas_seleccionadas, click = true ){
            $("#proy_empresa").html( "<option value=''>Selecciona una opción</option>" );
            $("#formulario_generico #inputs").html("");
            $.each( empresas, function( i, v ){

                $("#proy_empresa").append( "<option value='"+v.idempresa+"'>"+v.nombre+" "+v.abrev+"</option>" );

                if( empresas_seleccionadas.length > 0 ){
                    $.each( empresas_seleccionadas, function( ii, vv ){
                        if( click && v.idempresa == vv.idempresa ){
                            $("#proy_empresa").val( vv.idempresa );
                            concepto_neo = vv.clvneo;
                            moneda_prov = vv.moneda_sat;
                            tasa_prov = vv.tasa;
                            $("#addEmpresa").click();
                        }
                    });
                }
                
            });

            concepto_neo = "";
            moneda_prov = "";
            tasa_prov = "";
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
                },{ concepto : row.rfc }, url + "Catalogos/Proveedor_Empresa/" );
            }else{
                lista_empresas( empresas, row.pryemp, true );
                $("#mEmpresaProyecto").modal();
            }
        });

        $( document ).on( "click", "#addEmpresa", function(){
            if( $("#proy_empresa").val() ){
                $("#formulario_generico #inputs").append( '<div class="col-lg-12 form-group">'+
                    '<label><i class="fas fa-minus text-danger"></i> '+$("#proy_empresa option:selected").text()+'</label>'+
                    '<div class="row">'+
                        '<div class="col-lg-4">'+
                            '<input type="hidden" class="form-control" value="'+$("#proy_empresa").val()+'" name="empresa_proy[]" required/>'+
                            '<input type="text" placeholder="Clave de proveedor como se encuentra en NEODATA RP" class="form-control" value="'+concepto_neo+'" name="concepto_proy[]" required/>'+
                        '</div>'+ 
                        '<div class="col-lg-4">'+
                            '<input type="text" placeholder="Tipo de moneda." class="form-control" value="'+moneda_prov+'" name="moneda_sat[]" required/>'+ 
                        '</div>'+
                        '<div class="col-lg-4">'+
                            '<select class="form-control" name="tasa[]">'+
                                '<option value="16">16</option>'+
                                '<option value="8">8</option>'+
                                '<option value="0">0</option>'+
                                '<option value="NA">NA</option>'+
                            '</select>'+
                        '</div>'+
                    '</div>'+
                '</div>' );

                $( "select[name='tasa[]']" ).last().val( tasa_prov )
            }

        });
        
        $( document ).on("click", "#formulario_generico #inputs div.col-lg-12 label i.fa-minus", function(){
            $( this ).parents('div.col-lg-12').remove();
            empresas_array = [];
            /*
            $.each( $("#formulario_generico #inputs input[name='empresa_proy[]']"), function( i, v ){
                empresas_array.push( { idempresa : $( this ).val() } );
            });
            */
            //lista_empresas( empresas, empresas_array, false )
        });
    });

    $('#proyecto_proveedores').submit(function(e) {
        e.preventDefault();
    }).validate({
        submitHandler: function( form ) {

            var formulario = new FormData( $(form)[0] );
            formulario.append( "bdtabla", "catalogo_proyecto" );

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
            data.append( "tipo_dato", 1 );
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
                },error: function( ){
                    
                }
            });
        }
    });

    $('#formulario_generico').submit(function(e) {
        e.preventDefault();
    }).validate({
        submitHandler: function( form ) {
            var row = table_proceso.row( tr_global ).data();
            var data = new FormData( $(form)[0] );
            data.append("rfc", row.rfc);
            $.ajax({
                url : url + "Catalogos/insertRFCEmp",
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                method: 'POST',
                type: 'POST',
                success: function(response){

                    if( response.resultado ){
                        row.pryemp = response.data;
                        table_proceso.row( tr_global ).data( row );
                        $("#mEmpresaProyecto").modal( 'toggle' );
                    }
                    
                },error: function( ){
                    
                }
            });
        }
    }); 

</script>