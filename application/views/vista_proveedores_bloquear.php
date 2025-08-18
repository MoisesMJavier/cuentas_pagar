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
                        <h4>PROVEEDORES POR AUTORIZAR</h4>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <table class="table table-striped" id="tabla_historial">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th style="font-size: .9em">NOMBRE</th>
                                            <th style="font-size: .9em">ALIAS</th>
                                            <th style="font-size: .9em">RFC</th>
                                            <th style="font-size: .9em">NO. CUENTA</th>
                                            <th style="font-size: .9em">BANCO</th>
                                            <th style="font-size: .9em">CORREO</th>
                                            <th style="font-size: .9em">CREACIÓN</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div><!--End tab content-->
                </div><!--end box-->
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"></div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <button type="button" id="cerrar_mymodal" class="btn btn-danger">CERRAR</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_formulario_generico" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"></div>
            <div class="modal-body">
                <form id="formulario_generico" action="#">
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">

    var table_proceso;
    var idproveedor;
    var link_post;

    $('[data-toggle="tab"]').click( function(e) {
        if( $(this).attr('date-value') != "#" && proveedores_dinamicos != $(this).attr('date-value') ){
            //$("#consec2, #tabla_proveedores_temporales input[type='text']").val("");
            $("#consec2").val("");
            table_proveedores_temporales.ajax.url( url +"Provedores_cxp/"+$(this).attr('date-value') ).load();
        }
    });

    $("#tabla_historial").ready( function () {

        $('#tabla_historial thead tr:eq(0) th').each( function (i) {
            if( i != 7 ){
                var title = $(this).text();
                $(this).html( '<input type="text" style="font-size: .9em; width: 100%;" placeholder="'+title+'" />' );

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
                    text: '<i class="fas fa-file-excel"></i> EXPORTAR ',
                    messageTop: "Lista de proveedores",
                    attr: {
                        class: 'btn btn-success' ,
                        style: 'margin-right: 20px;'       
                    },
                    exportOptions: {
                        format: {
                            header: function (data, columnIdx) { 

                                data = data.replace( '<input type="text" style="font-size: .9em; width: 100%;" placeholder="', '' );
                                data = data.replace( '">', '' )
                                return data;
                            }
                        }
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
            "columns": [
                { 
                    "width": "35%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.nombre+'</p>'
                    }
                },
                { 
                    "width": "8%",
                    "data" : function( d ){
                        return '<p style="font-size: .9em">'+d.alias+'</p>'
                    }
                },
                {  
                    "width": "8%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+(d.rfc ? d.rfc : "SD") +'</p>'
                    }
                },
                {  
                    "width": "8%",
                    "data" : function( d ){
                        if(d.cuenta==null||d.cuenta==''){
                        return '<small class="label pull-center bg-yellow">SIN CUENTA</small>';

                        }
                        else{ 
                        return '<p style="font-size: .9em">'+d.cuenta+'</p>';
                    }
                    }
                },
                {  
                    "width": "8%",
                    "data" : function( d ){
                       if(d.nomba==null||d.nomba==''){
                        return '<small class="label pull-center bg-gray">SIN BANCO</small>';

                        }
                        else{ 
                        return '<p style="font-size: .8em">'+d.nomba+'</p>';
                    }
                    }
                },
                 {  
                    "data" : function( d ){
                        if( d.email == null || d.email == '' ){
                            return '<small class="label pull-center bg-teal">SIN CORREO</small>';
                        }else{ 
                            return '<p style="font-size: .7em">'+d.email+'</p>';
                        }
                    }
                },
                {
                    "width": "8%",
                    "data" : function( d ){
                        return '<center><p style="font-size: .9em">'+ ( d.fecha ? formato_fechaymd(d.fecha) : '' )+'</p></center>'
                    },

                },
                { 
                    "width": "20%",
                    "data": function( d ){

                        opciones = '<div class="btn-group-vertical" role="group">';
                        
                        // opciones += '<button type="button" class="btn btn-primary btn-sm" onClick="acepta('+ d.idproveedor +')" title="Ver información"><i class="fas fa-eye"></i></button>';
                        // opciones += '<button type="button" class="btn btn-danger btn-sm elimina_prov" value="'+d.idproveedor+'" title="Eliminar"><i class="fas fa-trash"></i></button>';
                        // opciones += '<button type="button" class="btn btn-success btn-reactivar btn-sm"><i class="fas fa-thumbs-up"></i></button>'; 
                        switch( d.estatus ){
                            case "0":
                                opciones += '<button type="button" style="background:#8F888F;border:#8F888F;" class="btn btn-success btn-reactivar btn-sm"><i class="fas fa-lock"></i></button>';
                                break;
                            case "1":
                                opciones += '<button type="button" style="margin:2px 2px 2px 2px; " class="btn btn-bloquear btn-sm" title="Bloquear"><i class="fas fa-ban"></i></button>';
                                break;
                            // case "9":
                            //     opciones += '<center><p style="font-size: .9em">Validando su documentación</p></center>';
                            //     break;
                        }                        
                        return opciones + '</div>';
                    },

                }
            ],
            "ajax": {
                "url": url + "Invitado/TablaBloquearProveedores",
                "type": "POST",
                cache: false,
            }
        });
        
        $("#tabla_historial").on( "click", ".btn-reactivar", function(){
            var row = table_proceso.row( $(this).closest('tr') ).data();
            $.post( url + "Invitado/reactivar_proveedor", { idproveedor : row.idproveedor } ).done( function(){
                table_proceso.ajax.reload();
            });
        });

        $("#tabla_historial").on( "click", ".btn-bloquear", function(){
            var row = table_proceso.row( $(this).closest('tr') ).data();

            $("#formulario_generico").html( '<h5>BLOQUEAR PROVEEDOR</h5>' );

            $("#formulario_generico").append( '<div class="row"><div class="col-lg-12 form-group"><label>OBSERVACIONES</label><textarea name="observacion" class="form-control" required></textarea></div></div>' );
            $("#formulario_generico").append( '<div class="row"><div class="col-lg-4 col-lg-offset-4"><button class="btn btn-success btn-block">ENVIAR</button></div></div>' );

            idproveedor = row.idproveedor;
            link_post = "Invitado/bloquear_proveedor";

            $("#modal_formulario_generico").modal();
        });

        // $("#tabla_historial").on( "click", ".btn-reactivar", function(){
        //     var tr = $(this).closest('tr')
        //     var row = table_proceso.row( $(this).closest('tr') ).data();
        //     $.post( url + "Alta_proveedores/aceptar_proveedor", { idproveedor : row.idproveedor } ).done( function(){
        //         table_proceso.row( tr ).remove().draw();
        //     });
        // });
    }); 

    // $("#tabla_historial").on( "click", ".elimina_prov", function(){
    //     var tr = $(this).closest('tr')
    //     $.post( url + "Alta_proveedores/eliminar_proveedor/" + $( this ).val() ).done( function(){
    //         table_proceso.row( tr ).remove().draw();
    //     });
    // });

    // function acepta(idp) {

    //     $("#myModal .modal-title").html("");
    //     $("#myModal .modal-body").html("");

    //     $.getJSON(url+"Provedores_cxp/ver_datosprovs2/"+idp, function( value ){

    //         value = value[0];
    //         $('#myModal').modal({backdrop: 'static', keyboard: false});
    //         $("#myModal").modal();

    //         $("#myModal .modal-header").append("<h4 class='modal-title'>NOMBRE: <b>"+ value.nomp +"</b></h4>");
            
    //         /*-------*/
    //         $("#myModal .modal-body").append('<div class="row"><div class="col-lg-6"><h5><b>RFC:</b> '+value.rfc+'</h5></div><div class="col-lg-6"><h5><b>CONTACTO:</b> '+value.contacto+'</h5></div></div>');
    //         /*-------*/
    //         tipo_cuenta = "";
    //         if (value.tipocta==1) {
    //             tipo_cuenta = '<h5><b>TIPO:</b> CUENTA BANBAJIO</h5>';
    //         }

    //         if (value.tipocta==3) {
    //             tipo_cuenta = '<h5><b>TIPO:</b> TARJETAo</h5>';
    //         }
            
    //         if (value.tipocta==40) {
    //             tipo_cuenta = '<h5><b>TIPO:</b> CLABE</h5>';
    //         }

    //         $("#myModal .modal-body").append('<div class="row"><div class="col-lg-6"><h5><b>Email:</b> '+value.email+'</h5></div><div class="col-lg-6"><h5><b>SUCURSAL:</b> '+value.estado+'</h5></div></div>');
    //         /*-------*/
    //         $("#myModal .modal-body").append('<div class="row"><div class="col-lg-3"><h5><b>BANCO:</b> '+value.nomba+'</h5></div><div class="col-lg-3">'+tipo_cuenta+'</div><div class="col-lg-6"><h5><b>No CUENTA:</b> '+value.cuenta+'</h5></div></div>');
            
    //         tipo_proveedor = "";

    //         switch( value.tipo_prov  ){
    //             case "0":
    //                 tipo_proveedor = "EXTERNO";
    //                 break;
    //             case "1":
    //                 tipo_proveedor = "INTERNO";
    //                 break;
    //             case "2":
    //                 tipo_proveedor = "EMPRESA";
    //                 break;
    //             case "3":
    //                 tipo_proveedor = "IMPUESTO";
    //                 break;
    //         }
            
    //         $("#myModal .modal-body").append('<div class="row"><div class="col-lg-6"><h5><b>FECHA REGISTRO:</b> '+value.fecadd+'</h5></div><div class="col-lg-6"><h5><b>TIPO PROVEEDOR:</b> '+tipo_proveedor+'</h5></div></div>');             
    //     });   
    // } 

    $("#cerrar_mymodal").click( function(){
        $("#myModal").modal('toggle');
    });

    $('#formulario_generico').submit(function(e) {
        e.preventDefault();
    }).validate({
        submitHandler: function( form ) {
            var data = new FormData( $(form)[0] );
            data.append("idproveedor", idproveedor);
            $.ajax({
                url : url + link_post,
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                method: 'POST',
                type: 'POST',
                success: function(data){

                    $("#modal_formulario_generico").modal( 'toggle' );
                    table_proceso.ajax.reload();

                },error: function( ){
                    
                }
            });
        }
    }); 

</script>

<?php
    require("footer.php");
?>

