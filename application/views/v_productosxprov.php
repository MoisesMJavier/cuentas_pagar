<?php
    require("head.php");
    require("menu_navegador.php");
?> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css" integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous" />

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <div class="box">
                    <div class="box-header with-border">
                        <h3>PRODUCTOS POR PROVEEDOR</h3>
                        <h6>Catálogo para productos de construcción y jardinería que tendrán que tener aprobación de compras</h6>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-lg-4">
                                <button class="btn btn-success" id="btnnuevaserie" type="button" onclick="accion(',,','registrar')"><span><i class="fas fa-plus"></i> NUEVA RELACIÓN</span></button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="table-responsive col-lg-12">
                                <table class="table table-striped" id="tabla_prod" name="tabla_historial">
                                     <thead class="thead-dark">
                                        <tr>
                                            <th>CLAVE PROD SERV</th>
                                            <th>PROVEEDOR</th>
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

<div class="modal fade" id="modalprod" role="dialog">
    <div class="modal-dialog modal-lg">
               <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="exampleModalLabel"></h4>
            </div>
            <div class="modal-body">
                <form id="form_serie">
                    <input type="hidden" id="idregistro" name="idregistro" value="0">
                    <input type="hidden" id="accion" name="accion" value="reg">
                    <div class="row">
                        <div class="col-lg-12 form-group">
                            <label for="producto">PRODUCTO</label>
                            <input type="text" name="producto" id="producto" class="form-control" required="" oninput="this.value = this.value.toUpperCase()"S/>
                        </div>
                        <div class="col-lg-12 form-group">
                            <label for="proveedor">PROVEEDOR</label>
                            <select class="form-control" id="proveedor" name="proveedor" required>
                                <option value="">--Seleccione una opción--</option>
                                <?php
                                foreach($proveedores->result() as $row){
                                    echo '<option value="'.$row->idproveedor.'">'.$row->nombre.' - '.$row->alias.'</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-lg-4 col-lg-offset-4">
                            <button type="submit" class="btn btn-primary btn-block">GUARDAR</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

    $(document).ready(function(e){
        $('select').selectize({
          sortField: 'text'
        });
    });
    var pos=0;
    var table_prod;
    $('#tabla_prod').ready(function (e){
        
        $('#tabla_prod thead tr:eq(0) th').each( function (i) {
            if( i != 3 ){
                var title = $(this).text();
                $(this).html( '<input type="text" class="form-control" style="font-size: .9em; width: 100%;" placeholder="'+title+'" title="'+title+'" />' );
        
                $( 'input', this ).on( 'keyup change', function () {
                    if ( table_prod.column(i).search() !== this.value ) {
                        table_prod
                            .column(i)
                            .search( this.value )
                            .draw();
                    }
                } );
            } 
        });

        table_prod=$('#tabla_prod').DataTable( {
            ajax: { url: "../getProductosXprov", dataSrc: "" },
            dataSrc: "",
            dom: 'rtip',
            "language" : lenguaje,
            "scrollX": true,
            "processing": false,
            "pageLength": 25,
            "bAutoWidth": false,
            "bLengthChange": false,
            "bInfo": false,
            columns: [
                { data: function( d ){
                        var txt=d.producto;
                        return txt;
                    }  
                },
                { data: 'nombre' },
                { data: function( d ){
                        var txt='';
                        if(d.activo==1)
                            txt+='ACTIVO';
                        else
                            txt+='INACTIVO';
                        return txt;
                    } 
                },
                { data: function( d ){
                        var txt='<button type="button" style="margin:2px 2px 2px 2px;border: solid 1px;" class="btn btn-warning btn-sm" title="Editar" onclick="accion(\''+(d.id)+','+(d.producto)+','+(d.idproveedor)+','+(d.activo)+'\',\'editar\')"><i class="fas fa-pencil-alt"></i></button>';
                        if(d.activo==1)
                            txt+='<button type="button" style="margin:2px 2px 2px 2px;border: solid 1px;" class="btn btn-danger btn-sm" title="Inactivar" onclick="estatus_registro('+(d.id)+')"><i class="fas fa-power-off"></i></button>';
                        else
                            txt+='<button type="button" style="margin:2px 2px 2px 2px;border: solid 1px;" class="btn btn-success btn-sm" title="Activar" onclick="estatus_registro('+(d.id)+')"><i class="fas fa-power-off"></i></button>';
                        return txt;
                    } 
                }
            ],
        } );
        
    });
    
    function accion(datos,accion){
        datos = datos.split(",");
        $("#idregistro").val($.trim(datos[0]));
        $("#producto").val($.trim(datos[1]));
        setval($("#proveedor"),$.trim(datos[2]) );
        $("#modalprod").modal();
        $("#accion").val(accion);
        if(accion=="registrar")
            $("#modalprod .modal-title").text("NUEVA RELACIÓN");
        else if(accion=="editar")
            $("#modalprod .modal-title").text("EDITAR RELACIÓN");
    }
    
    function setval(select,val){
        select = select.selectize();
        var selectize = select[0].selectize;
        selectize.setValue(val);
    }

    function estatus_registro(id){
        var d = new FormData();
        d.append("idregistro",id);
        d.append("accion","des-activar");
        var result = enviar_post(d,"../acciones_ProductosXprov");
        alert(result.msj);
        if (result.resultado){
            table_prod.ajax.reload();
        }
    }

    $("#form_serie").submit(function (e){
        var data = new FormData($(this)[0]);
        e.preventDefault();
        $.ajax({
            url : "../acciones_ProductosXprov",
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            method: 'POST',
            type: 'POST',
            success: function(data){
                if(data.resultado){
                    table_prod.ajax.reload();
                    $("#modalprod").modal('hide');
                }else
                    alert(data.msj);
            },error: function( ){
                
            }
        });
    });
</script>

<?php
    require("footer.php");
?>

