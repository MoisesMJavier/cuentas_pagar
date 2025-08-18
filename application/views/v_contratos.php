<?php
    require("head.php");
    require("menu_navegador.php");
?>
<div class="content">
    <div class="container-fluid">
        <div class="row justify-content-lg-center">
            <div class="col-lg-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3>CONTRATOS PROVEEDORES</h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-lg-12">
                            <?php
                            if($this->session->userdata("inicio_sesion")['depto']=="CONSTRUCCION"){
                                echo '<div class="col-lg-3">
                                            <button type="button" id="add_contrato" class="btn btn-block btn-primary"><i class="fa fa-plus"></i> AGREGAR CONTRATO</button>
                                        </div>';
                                    //     <div class="col-lg-3">                                        
                                    //     <button type="button" id="add_porcentaje" class="btn btn-block btn-info"><i class="fa fa-plus"></i> AGREGAR PORCENTAJE</button>
                                    // </div>';
                            }
                            ?>
                            <table class="table table-striped" id="tablacontratos">
                                <thead>
                                    <tr>
                                        <th style="font-size: .9em">NOMBRE</th>
                                        <th style="font-size: .9em">PROVEEDOR</th>
                                        <th style="font-size: .9em">CONSUMIDO/TOTAL</th>
                                        <th style="font-size: .9em">% DE INTERCAMBIO</th>
                                        <th style="font-size: .9em">FECHA CREACIÓN</th>
                                        <th style="font-size: .9em">ESTATUS</th>
                                        <th style="font-size: .9em"></th>
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
<div id="modal_add_contrato" tabindex="-1" class="modal fade">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><strong>AGREGAR CONTRATO</strong></h5>
            </div>
            <form id="frm_contrato" method="post" >
                <div class="modal-body">
                
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <h5><b>PROVEEDOR</b></h5>
                                <select class="form-control proveedor" id="proveedor" name="proveedor">
                                    <option value="">Seleccione una opción</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <h5><b>NOMBRE CONTRATO</b></h5>
                            <input type="text" class="form-control" id="nombre_contrato" name="nombre_contrato" required/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <h5><b>CANTIDAD</b></h5>
                            <div class="input-group">
                                <span class="input-group-addon">$</span>
                                <input type="number" class="form-control" id="cantidad" name="cantidad" required/>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <h5><b>PORCENTAJDE DE INTERCAMBIO</b></h5>
                            <div class="input-group">
                                <span class="input-group-addon">%</span>
                                <input type="number" class="form-control" min="0" id="porcentaje" name="porcentaje" required/>
                            </div>
                        </div>  
                    </div>
                    <hr/>
                    <!-- <div class="row">
                        <div class="col-lg-5 col-lg-offset-7">
                            <div class="btn-group-vertical">
                                <button class="btn btn-success" type="submit">AGREGAR</button>
                                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times"></i> CERRAR</button>
                            </div>
                        </div>
                    </div> -->
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times"></i> CERRAR</button>
                    <button class="btn btn-success" type="submit">AGREGAR</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div id="modal_add_porcentaje" class="modal fade" tabindex="-1" role="dialog" >
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><strong>PORCENTAJDE DE INTERCAMBIO </strong></h5>
            </div>
            <form id="frm_porcentaje" method="post" >
                <div class="modal-body" style="width: 500px;">

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <h5><b>PROVEEDOR</b></h5>
                                    <select class="form-control proveedor" id="prov_intercambio" name="prov_intercambio">
                                        <option value="">Seleccione una opción</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <h5><b>Porcentaje</b></h5>
                                <div class="input-group">
									<span class="input-group-addon">%</span>
                                    <input type="number" class="form-control" id="porcentaje" name="porcentaje" required/>
                                </div>
                            </div>  
                        </div>
                        <hr/> 
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times"></i> CERRAR</button>
                    <button class="btn btn-success" type="submit">AGREGAR</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    
    var tabla_contratos;
    var idcontrato = null;
    var link;
    var formnulario;
    var proveedores=[];
    $("#add_contrato").click( function(){
        $("#modal_add_contrato .form-control").val("");
        link = "Contratos/add_contrato/";
        idcontrato = null;

        $('#proveedor').val('').trigger('change');

        $("#cantidad").rules("remove");
        $("#cantidad").rules("add", {
            required: true
        });
        formnulario.resetForm();

        $("#modal_add_contrato").modal();

    });

    $("#add_porcentaje").click( function(){
        $("#modal_add_porcentaje .form-control").val("");
        link = "Contratos/updatePorcentProv";
        // idcontrato = null;

        $('#prov_intercambio').val('').trigger('change');

        $("#porcentaje").rules("remove");
        $("#porcentaje").rules("add", {
            required: true
        });
        form_porcentaje.resetForm();

        $("#modal_add_porcentaje").modal();

    });

    $("#updatRfc").click( function(){
        $.post(  url + "Contratos/activar_contrato", {idcontrato : $(this).val()}).done( function(){
                row.estatus = 1; 
                tabla_contratos.row( tr ).data( row );
                tabla_contratos.draw();
            });
    });

    $(document).on( "change", "#prov_intercambio", function(){
		console.log($( this ).find(':selected').data('porcentaje'));
		if($( this ).find(':selected').data('porcentaje') != null){			
			$("#porcentaje").val($( this ).find(':selected').data('porcentaje'));
		}else{
            $("#porcentaje").val(0);
        }
	});

    function llenarSelectProv(){
        // data-index="'+i+'"
             $.each( proveedores, function( i, v ){
                $("#proveedor").append('<option value="'+v.rfc_proveedor+'" data-porcentaje="'+v.porcentaje+'" >'+v.rs_proveedor+'</option>')
            });

            $('#proveedor').select2({
                width: '100%',
                dropdownParent: $('#modal_add_contrato')
            });
            $.each( proveedores, function( i, v ){
                $("#prov_intercambio").append('<option value="'+v.rfc_proveedor+'" data-porcentaje="'+v.porcentaje+'" >'+v.rs_proveedor+'</option>')
            });

            $('#prov_intercambio').select2({
                width: '100%',
                dropdownParent: $('#modal_add_porcentaje')
            });
    }

    $(document).ready(function(){

       $.getJSON( url + "Listas_select/pconstruccion/" ).done( function( data ){
            proveedores = data;
            llenarSelectProv();
        });

        formnulario = $("#frm_contrato").submit( function(e) {
            e.preventDefault();
        }).validate({
            rules: {
                proveedor : "required",
                cantidad : "required",
                nombre_contrato : "required"
            },
            submitHandler: function( form ) {
                var data = new FormData( $(form)[0] );
                data.append("idcontrato", idcontrato)
                $.ajax({
                    url: url + link,
                    data: data,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    method: 'POST',
                    type: 'POST', // For jQuery < 1.9
                    success: function( data ){
                        
                        if( data.resultado ){
                            tabla_contratos.clear();
                            tabla_contratos.rows.add( data.data );
                            tabla_contratos.draw();
                        }
                                                
                        $("#modal_add_contrato").modal("toggle");
                    },error: function( ){
                        
                    }
                });
            }
                
        });

        form_porcentaje = $("#frm_porcentaje").submit( function(e) {
            e.preventDefault();
        }).validate({
            rules: {
                prov_intercambio : "required",
                porcentaje : "required"
            },
            submitHandler: function( form ) {
                var data = new FormData( $(form)[0] );                
                $.ajax({
                    url: url + link,
                    data: data,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    method: 'POST',
                    type: 'POST', // For jQuery < 1.9
                    success: function( data ){
                        
                        if( data.resultado ){
                            // volver a llenar selects
                            tabla_contratos.ajax.reload();
                          
                        }
                                                
                        $("#modal_add_porcentaje").modal("toggle");
                    },error: function( ){
                        
                    }
                });
            }
                
        });

    });

    $("#tablacontratos").ready( function(){

        $('#tablacontratos thead tr:eq(0) th').each( function (i) {
            if( i != 6 ){
                var title = $(this).text();
                $(this).html( '<input type="text" style="font-size: .9em; width: 100%;" placeholder="'+title+'" />' );
        
                $( 'input', this ).on( 'keyup change', function () {
                    if ( tabla_contratos.column(i).search() !== this.value ) {
                        tabla_contratos
                            .column(i)
                            .search( this.value )
                            .draw();
                    }
                } );
            } 
        });

        tabla_contratos = $('#tablacontratos').DataTable({
            "language" : lenguaje,
            "scrollX": true,
            "processing": false,
            "pageLength": 10,
            "bAutoWidth": false,
            "bLengthChange": false,
            "bInfo": false,
            dom: 'Brtip',
            buttons: [
                {
                    extend: 'excelHtml5',             
                    text: '<i class="fas fa-file-excel"></i>',
                    messageTop: "LISTADO DE PAGOS A AUTORIZAR",
                    attr: {
                        class: 'btn btn-success'       
                    },
                    exportOptions: {
                        format:{    
                            header:  function (data, columnIdx) {
                                return data.replace( '<input type="text" style="font-size: .9em; width: 100%;" placeholder="', '' ).split('"')[0];
                            }
                        },
                        columns: [ 0, 1, 2, 3 ]
                    }
                }
            ],
            "columns": [
                {   "width": "25%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.nombre+'</p>'
                    }
                },
                { 
                    "width": "30%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.proveedor+'</p>'
                    }
                 },
                { 
                    "width": "15%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em"> $ '+ formatMoney( d.consumido )+ " / $ " + formatMoney( d.cantidad )+'</p>'
                    }
                 },
                 { 
                    "width": "5%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em; text-align:center;">'+(d.porcentaje == null ? "0%"  : d.porcentaje+"% ") +'</p>'
                    }
                 },
                { 
                    "width": "14%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+ formato_fechaymd( d.fecha_creacion )+'</p>'
                    }
                },
                { "width": "9%",
                    "data": function( d ){
                        if(d.estatus == 1)
                            return '<p><small class="label pull-center bg-green">ACTIVO</small></p>';
                        else
                            return '<p><small class="label pull-center bg-red">INACTIVO</small></p>';
                    } 
                },
                { "width": "7%",
                    "data": function( d ){
                        var p='';
                        <?php
                        if($this->session->userdata("inicio_sesion")['depto']=="CONSTRUCCION"){
                            echo 'p = \'<div class="btn-group-vertical">\';
                            if(d.estatus == 1)
                                p+= "<button title = \'Desactivar contrato\' type=\'button\' value=\'"+d.idcontrato+"\' class=\'desactivar_contrato btn btn-sm btn-danger\'><i class=\'fas fa-power-off\'></i></button>";
                            else
                                p+= "<button title = \'Activar contrato\' type=\'button\' value=\'"+d.idcontrato+"\' class=\'activar_contrato btn btn-sm btn-success\'><i class=\'fas fa-power-off\'></i></button>";

                            p += "<button title = \'Editar contrato\' type=\'button\' value=\'"+d.idcontrato+"\' class=\'editar_contrato btn btn-sm btn-warning\'><i class=\'fas fa-pencil-alt\'></i></button></div>";';
                        }
                        ?>
                        return p;
                    } 
                }
            ],
            "ajax":  url + "Contratos/tablaContratos"
        });

        //Activar contrato
        $("#tablacontratos tbody").on('click', 'button.activar_contrato', function (){
            var tr = $(this).closest('tr');
            var row = tabla_contratos.row( $(this).closest('tr') ).data();

            $.post(  url + "Contratos/activar_contrato", {idcontrato : $(this).val()}).done( function(){
                row.estatus = 1; 
                tabla_contratos.row( tr ).data( row );
                tabla_contratos.draw();
            });
        });

        //Desactivar contrato
        $("#tablacontratos tbody").on('click', 'button.desactivar_contrato', function (){
            var tr = $(this).closest('tr');
            var row = tabla_contratos.row( $(this).closest('tr') ).data();

            $.post(  url + "Contratos/desactivar_contrato", {idcontrato : $(this).val()}).done( function(){
                row.estatus = 0
                tabla_contratos.row( tr ).data( row );
                tabla_contratos.draw();
            });
        });
        
        //Desactivar contrato
        $("#tablacontratos tbody").on('click', 'button.editar_contrato', function (){

            link = "Contratos/editar_contrato/";
            idcontrato = $(this).val();

            var tr = $(this).closest('tr');
            var row = tabla_contratos.row( $(this).closest('tr') ).data();
            console.log(row);
            $("#proveedor").val( row.rfc_proveedor ).trigger('change');
            $("#cantidad").val( row.cantidad );
            $("#nombre_contrato").val( row.nombre );
            $("#porcentaje").val( row.porcentaje );

            if( row.consumido ){
                $("#cantidad").rules("remove");
                $("#cantidad").rules("add", {
                    required: true,
                    min: parseFloat( row.consumido )  
                });
                formnulario.resetForm();
            }
            
            $("#modal_add_contrato").modal();
        });

    });



</script> 
<?php
    require("footer.php");
?>