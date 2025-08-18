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
                        <h3>ALTA DE SERIES POR MONEDA (BANXICO)</h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <button class="btn btn-success" id="btnnuevaserie" type="button" onclick="accion(',','registrar')"><span><i class="fas fa-plus"></i> NUEVA SERIE</span></button>
                                <div class="table-responsive">
                                    <table class="table table-striped" id="tabla_series" name="tabla_historial">
                                         <thead class="thead-dark">
                                            <tr>
                                                <th>MONEDA</th>
                                                <th>SERIE</th>
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
</div>

<div class="modal fade" id="modalserie" role="dialog">
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
                        <div class="col-lg-6 form-group">
                            <label>MONEDA</label>
                            <input type="text" class="form-control" id="moneda" name="moneda" required>
                        </div>
                        <div class="col-lg-6 form-group">
                            <label>SERIE</label>
                            <input type="text" class="form-control" id="serie" name="serie" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm">Registrar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var pos=0;
    var table_series;
    $('#tabla_series').ready(function (e){
        
        $('#tabla_series thead tr:eq(0) th').each( function (i) {
            if( i != 2 ){
                var title = $(this).text();
                $(this).html( '<input type="text" class="form-control" style="font-size: .9em; width: 100%;" placeholder="'+title+'" title="'+title+'" />' );
        
                $( 'input', this ).on( 'keyup change', function () {
                    if ( table_series.column(i).search() !== this.value ) {
                        table_series
                            .column(i)
                            .search( this.value )
                            .draw();
                    }
                } );
            } 
        });

        table_series=$('#tabla_series').DataTable( {
            ajax: { url: "./TipoCambio/getdata", dataSrc: "" },
            dom: 'rtip',
            "language" : lenguaje,
            "scrollX": true,
            "processing": false,
            "pageLength": 25,
            "bAutoWidth": false,
            "bLengthChange": false,
            "bInfo": false,
            dataSrc: "",
            columns: [
                { data: 'moneda' },
                { data: 'serie' },
                { data: function( d ){
                        var txt='<button type="button" style="margin:2px 2px 2px 2px;border: solid 1px;" class="btn btn-warning btn-sm" title="Editar" onclick="accion(\''+(d.moneda)+','+(d.serie)+'\',\'editar\')"><i class="fas fa-pencil-alt"></i></button>';
                        return txt;
                    } 
                }
            ],
        } );
        
    });
    
    function accion(datos,accion){
        datos = datos.split(",");
        $("#idregistro").val($.trim(datos[0]));
        $("#moneda").val($.trim(datos[0]));
        $("#serie").val($.trim(datos[1]));
        $("#modalserie").modal();
        $("#accion").val(accion);
        if(accion=="registrar")
            $("#modalserie .modal-title").text("Nueva Serie");
        else if(accion=="editar")
            $("#modalserie .modal-title").text("Editando Serie");
    }
    
    $("#form_serie").submit(function (e){
        var data = new FormData($(this)[0]);
        e.preventDefault();
        $.ajax({
            url : url + "./TipoCambio/acciones_serie",
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            method: 'POST',
            type: 'POST',
            success: function(data){
                if(data.resultado){
                    table_series.ajax.reload();
                    $("#modalserie").modal('hide');
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

