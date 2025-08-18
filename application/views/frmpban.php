<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">ARCHIVO TXT PARA BANCOS</h5>
        <button type="button" class="close" aria-label="Close" onClick="clsmodal()"><span aria-hidden="true">&times;</span></button>
    </div>
    <div class="modal-body">
        <div class="row">
            <label class="col-lg-2" for="empresa">EMPRESA</label>
            <select class="form-control col-lg-6" name="empresa" id="empresa" required>
                <option value="null">ELIGE EMPRESA</option>
            </select>
            <div class="col-lg-3">
                <button class="btn btn-default" id="generar_txt">GENERAR DOCUMENTO</button>
            </div>
        </div>
        <hr>
        <div id="resban"></div>
    </div>
</div>
<script>
    
    var gdafrm = false;

    $(document).ready(function (){
        
        veremp();
        obdeptos();

        $("#generar_txt").click( function(){
            if( $("select#empresa").val() !=='null' ){
                $.getJSON(url+"ArchivoBanco/genpbanc/"+$("select#empresa").val(), { noarch : 25 }, function (data) {
                        if( !data.resultado ){
                            $('#resban').html('<h5>Verifica la información y descarga el archivo <a class="btn btn-cpp" href="'+url+data['file']+'" target="_blank" download><i class="fas fa-download"></i></a></h5><br><hr>Total a pagar: <b>$'+data['totpag'].toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')+'</b> <hr><table class="table"><thead class="thead-dark"><tr><th>ID</th><th>Proveedor</th><th>Tipo de Cuenta</th><th>CLABE</th><th>Banco</th><th>Descripción</th><th>Cantidad a pagar</th></tr></thead><tbody id="tbodban"></tbody></table>');
                            $.each(data['data'], function (ind, val) { 
                                $("#tbodban").append('<tr><td>'+val.idsol+'</td><td>'+val.prov+'</td><td>'+val.tipocta+'</td><td>'+val.clabe+'</td><td>'+val.clvbanco+'-'+val.bconom+'</td><td>'+val.descr+'</td><td>$'+val.cant+'</td></tr>');
                            });
                        }else{
                            $('#resban').html( data.mensaje );
                        }
                    }
                );
            }
        });

    });

    function clsmodal(){
        $('.modal').modal('toggle');
    }
    
/* 



function ldpbancos(){

    var idemp=$("select#empresa").val();
    var iddepto=$("select#deptosel").val();
    $("#resban").html('');
    if(idemp!=='null'){
        var nocta=window.prompt("Ingresa el número de cuenta para la emisión de pagos.")
        if(nocta!=null){
            $.getJSON(url+"Pprov/genpbanc/"+idemp,{deptoid:iddepto,noarch:25,ncta:nocta},
                function (data) {
                    if(data!=='vacio'){
                        $('#resban').html('<h5>Verifica la información y descarga el archivo <a class="btn btn-cpp" href="'+url+data['file']+'" target="_blank" download><i class="fas fa-download"></i></a></h5><br><hr>Total a pagar: <b>$'+data['totpag'].toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')+'</b> <hr><table class="table"><thead class="thead-dark"><tr><th>ID</th><th>Proveedor</th><th>Tipo de Cuenta</th><th>CLABE</th><th>Banco</th><th>Descripción</th><th>Cantidad a pagar</th></tr></thead><tbody id="tbodban"></tbody></table>');
                        $.each(data['data'], function (ind, val) { 
                            $("#tbodban").append('<tr><td>'+val.idsol+'</td><td>'+val.prov+'</td><td>'+val.tipocta+'</td><td>'+val.clabe+'</td><td>'+val.clvbanco+'-'+val.bconom+'</td><td>'+val.descr+'</td><td>$'+val.cant+'</td></tr>');
                        });
                    }else{
                        $('#resban').html('<h3>Sin pagos para realizar de esa empresa o departamento por empresa.</h3>');
                    }
                }
            );
        }
    }else{
        window.alert("Elige una empresa");
    }
    
}
*/
</script>