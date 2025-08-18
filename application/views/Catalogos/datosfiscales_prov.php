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
                                    <table class="table table-striped" id="tabla_prov_cat">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th style="font-size: .9em">RFC</th>
                                                <th style="font-size: .9em">NOMBRE PROVEEDOR</th>
                                                <th style="font-size: .9em">REGIMEN FISCAL</th>
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
<div class="modal fade" id="modalUpdate" role="dialog">
    <div class="modal-dialog">
     <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title" id="exampleModalLabel">INFORMACIÓN DEL PROVEEDOR</h4>
        </div>
        <div class="modal-body">
            <form id="prov_cat">
                <p>Información del proveedor. Complete los campos requeridos (<span class="text-danger">*</span>)</p>
                <div class="row">
                    <div class="col-lg-6 form-group">
                        <label>RFC</label>
                        <input type="text" class="form-control" id="rfc_prov" name="rfc_prov" class="form-control tf w-input">
                    </div>
                    <div class="col-lg-6 form-group">
                        <label>RAZÓN SOCIAL</label>
                        <input type="text" class="form-control" id="razon_social" name="razon_social"  class="form-control tf w-input" required  onKeyPress="if(this.value.length==100) return false;">
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 form-group">
                        <label>RÉGIMEN FISCAL</label>
                        <select  id="rf_prov"  name="rf_prov"  class="form-control" ></select>
                    </div>
                    <div class="col-lg-6 form-group">
                        <label>CODIGO POSTAL</label>
                        <input type="text" class="form-control" id="cp_prov" name="cp_prov" onKeyPress="if(this.value.length==5) return false;" class="form-control tf w-input">
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


<script type="text/javascript">
    var table_prov;
    var opciones = "";
    var tr_global;
    var lista_regf = [];

    $("#tabla_prov_cat").ready( function () {

        $.getJSON( url + "Listas_select/cat_regimenfiscal").done(function( data ){
           
            lista_regf = data;

        });


        $('#tabla_prov_cat').on('xhr.dt', function ( e, settings, json, xhr ) {
            opciones = json.opciones;
        });

        $('#tabla_prov_cat thead tr:eq(0) th').each( function (i) {
            if(  i < $('#tabla_prov_cat thead tr:eq(0) th').length - 1 ){
                var title = $(this).text();
                $(this).html( '<input type="text" style="font-size: .9em; width: 100%;" class="form-control" placeholder="'+title+'" title="'+title+'"/>' );

                $( 'input', this ).on( 'keyup change', function () {
                    if ( table_prov.column(i).search() !== this.value ) {
                        table_prov
                        .column(i)
                        .search( this.value )
                        .draw();
                    }
                } );
            }
        });

        table_prov = $('#tabla_prov_cat').DataTable({
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
                    "width": "10%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.rfc+'</p>'
                    }
                },
                {
                    "width": "60%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.nproveedor+'</p>'
                    }
                },
                { 
                    "width": "10%",
                    "data" : function( d ){
                        return '<p style="font-size: .8em">'+d.regimenfiscal+'</p>'
                    }
                },
                { 
                    "width": "15%",
                    "data": function( d ){
                        return opciones;
                    },

                }
            ],
            "ajax": {
                "url": url + "Provedores_cxp/TablaProv_Cat",
                "type": "POST",
                cache: false,
            }
        });

        
        $("#tabla_prov_cat").on( "click", ".editar_prov_cat", function(){   
            tr = $(this).closest('tr');
            var row = table_prov.row( $(this).closest('tr') ).data();
            console.log(row);
        $("input[name='razon_social']").val( row.nproveedor);
        $("input[name='rfc_prov']").val( row.rfc ).prop('disabled', true);

        $("select[name='rf_prov']").append('<option value="">Seleccione una opción</option>');
        $.each(lista_regf, function (ind, val) {
        $("select[name='rf_prov']").append("<option value='"+val.codrf+"'>"+val.codrf+" - "+val.descrf+"</option>");
        });
        $("select[name='rf_prov'] option[value='"+(row.regimenfiscal != "SIN ESPECIFICAR" ? row.regimenfiscal : "" )+"']").prop("selected", true);   
      
        
        $("input[name='cp_prov']").val( (row.dirfiscal !='0')? row.dirfiscal : "" );
        $("#modalUpdate").modal();
        });

    });

    $('#prov_cat').submit(function(e) {
        e.preventDefault();
    }).validate({
        submitHandler: function( form ) {

            var formulario = new FormData( $(form)[0] );
            formulario.append( "rfc_prov", $("input[name='rfc_prov']").val()  );

            if( tr_global != false ){
                row = table_prov.row( tr_global ).data();
            }
            $.ajax({
                url : url + 'Provedores_cxp/updateprov_cat',
                data: formulario,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                method: 'POST',
                type: 'POST',
                success: function(data){
                    $("#modalUpdate").modal( 'toggle' );
                     if( !data[0] ){
                        alert("NO SE HA PODIDO COMPLETAR LA SOLICITUD");
                    } else{
                        alert("ACTUALIZADO CON ÉXITO");
                        table_prov.ajax.reload();
                    }

            },error: function( ){

            }
            });
        }
    });

 

</script>