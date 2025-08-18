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
                        <h3>PAGOS AUTORIZADOS</h3>
                    </div>
                    <div class="box-body">
                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">


                             <li class="active"><a id="profile-tab" data-toggle="tab" href="#pagos_autoriza_dg_trans" role="tab" aria-controls="#home" aria-selected="true">PAGOS TRANSFERENCIAS</a></li>
                             <li><a id="profile-tab" data-toggle="tab" href="#pagos_autoriza_dg_cheques" role="tab" aria-controls="pagos_autoriza_dg_cheques" aria-selected="false">PAGOS OTROS </a></li>
                             <li><a id="profile-tab" data-toggle="tab" href="#pagos_autoriza_dg_chica" role="tab" aria-controls="pagos_autoriza_dg_chica" aria-selected="false">PAGOS CAJA CHICA</a></li>

                         </ul>
                     </div>
                     <div class="tab-content">
                        <div class="active tab-pane" id="pagos_autoriza_dg_trans">
                            <div class="row">
                                <div class="col-lg-12">
                                    <h4>TRANSFERENCIAS ELECTRÓNICAS <i class="far fa-question-circle faq" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" title="Autorizaciones" data-content="Pagos autorizados como transferencias por Dirección General" data-placement="right"></i><label>&nbsp;Total: $<input style="border-bottom: none; border-top: none; border-right: none;  border-left: none; background: white;" disabled="disabled" readonly="readonly" type="text"  name="myText2" id="myText2"></label></h4><BR>
                                    <table class="table table-responsive table-bordered table-striped table-hover" id="tabla_autoriza_dg_tranferencias" name="tabla_autoriza_dg_tranferencias">
                                        <thead class="thead-dark">
                                            <tr>

                                                <th style="font-size: .8em"></th>
                                                <th style="font-size: .8em"></th>
                                                <th style="font-size: .8em">FOLIO</th>
                                                <th style="font-size: .8em">PROVEEDOR</th>
                                                <th style="font-size: .8em">CANTIDAD</th>
                                                <th style="font-size: .8em">CANTIDAD MXN</th>
                                                <th style="font-size: .8em">FECHA</th>
                                                <th style="font-size: .8em">DEPARTAMENTO</th>
                                                <th style="font-size: .8em">EMPRESA</th>
                                                <th style="font-size: .8em">MÓTIVO ESPERA</th>
                                                <th style="font-size: .8em"></th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>


                        <div class="tab-pane fade" id="pagos_autoriza_dg_cheques">
                            <div class="row">
                                <div class="col-lg-12">
                                    <h4>ECHQ, MAN, EFEC, DOMIC <i class="far fa-question-circle faq" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" title="Autorizaciones" data-content="Pagos autorizados por Dirección General" data-placement="right"></i><label>&nbsp;Total: $<input style="border-bottom: none; border-top: none; border-right: none;  border-left: none; background: white;" disabled="disabled" readonly="readonly" type="text"  name="myText3" id="myText3"></label></h4>
                                    <table class="table table-responsive table-bordered table-striped table-hover"  id="tabla_autoriza_dg_otros" name="tabla_autoriza_dg_otros">
                                        <thead class="thead-dark">
                                            <tr>


                                                <th style="font-size: .8em"></th>
                                                <th style="font-size: .8em"></th>
                                                <th style="font-size: .8em">FOLIO</th>
                                                <th style="font-size: .8em">PROVEEDOR</th>
                                                <th style="font-size: .8em">CANTIDAD</th>
                                                <th style="font-size: .8em">CANTIDAD MXN</th>
                                                <th style="font-size: .8em">PAGO</th>
                                                <th style="font-size: .8em">FECHA</th>
                                                <th style="font-size: .8em">DEPARTAMENTO</th>
                                                <th style="font-size: .8em">EMPRESA</th>
                                                <th style="font-size: .8em">MÓTIVO ESPERA</th>
                                                <th></th> 
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div> <!--End solicitudes finalizadas-->  

                        <div class="tab-pane fade" id="pagos_autoriza_dg_chica">
                           <div class="row">
                            <div class="col-lg-12">
                              <div class="box">
                                <h4>&nbsp;PAGOS AUTORIZADOS CAJA CHICA <i class="far fa-question-circle faq" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" title="Autorizaciones" data-content="Pagos terminados" data-placement="right"></i><label>&nbsp;Total: $<input style="border-bottom: none; border-top: none; border-right: none;  border-left: none; background: white;" disabled="disabled" readonly="readonly" type="text"  name="myText4" id="myText4"></label></h4>

                                <hr>  
                                <table class="table table-responsive table-bordered table-striped table-hover"  id="tabla_cajachica" name="tabla_cajachica">
                                   <thead>
                                    <tr>
                                        <th></th>
                                        <th style="font-size: .9em">RESPONSABLE</th>
                                        <th style="font-size: .9em">EMPRESA</th>
                                        <th style="font-size: .9em">FECHA FACTURA</th>
                                        <th style="font-size: .9em">CANTIDAD</th>
                                        <th style="font-size: .9em">DEPARTAMENTO</th>
                                        <th style="font-size: .8em">MÉTODO PAGO</th>
                                        <th style="font-size: .8em">MÉTODO INGRESADO</th>
                                        <th style="font-size: .8em">MÓTIVO ESPERA</th>

                                        <th></th>
                                    </tr>
                                </thead>

                            </table>
                        </div>
                    </div>
                </div>
            </div> <!--End solicitudes finalizadas-->  

        </div>
    </div><!--End tab content-->
</div><!--end box-->
</div>
</div>
</div>
</div>



<div class="modal fade" id="myModal2" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content"> 
            <div class="modal-header"> 
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4>GENERAR DOCUMENTOS DE TRANSFERENCIAS<h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">

                            <div class="col-lg-6">
                                <center><h5 class="modal-title">SELECCIONA EMPRESA</h5>
                                    <div class="input-group">    
                                        <select id="empresa_valor" name="empresa_valor" class=" form-control lista_empresa"></select>
                                    </div></center><br></div>

                                    <div class="col-lg-6">
                                        <center><h5 class="modal-title">SELECCIONA CUENTA</h5>
                                            <div class="input-group">    
                                                <select style="width: 100%;" id="cuenta_valor" name="cuenta_valor" class="form-control lista_cuenta" required><option value="">- Selecciona cuenta - </option></select>


                                            </div></center><br></div>

                                            <div class="col-lg-6">
                                                <center><h5 class="modal-title">SELECCIONA DEPARTAMENTO</h5>
                                                    <div class="input-group">  


                                                        <select style="width: 100%;" id="depar" name="depar" class="form-control depar" required>
                                                            <option value="">TODOS</option>
                                                            <option value="1">CONSTRUCCIÓN</option>
                                                            <option value="2">OTROS</option>
                                                        </select>  


                                                    </div></center><br></div>


                                                </div>

                                                <script type="text/javascript">

                                                   $('select#empresa_valor').on('change',function(){
                                                     $(".lista_cuenta").html("");
                                                      // $(".depar").html("");
                                                     var valor = $(this).val();
                                                    

                                                     $.getJSON( url + "Listas_select/lista_cuentas"+"/"+valor).done( function( data ){
                                                        $.each( data, function( i, v){
                                                            $(".lista_cuenta").append('<option value="'+v.idcuenta+'" data-value="'+v.idcuenta+'">'+v.nombre+" - "+v.nodecta+" - "+v.nombanco+'</option>');
                                                        });
                                                    });


                                                     // if(valor==2){
                                                     //   $(".depar").prop("disabled",false);
                                                     // }
                                                     // else{
                                                     //    $('.depar').html('');
                                                     //    $(".depar").prop("disabled",true);
                                                     //    $(".depar").append(' <option value="">TODOS</option>');
                                                     //    $(".depar").append(' <option value="1">CONSTRUCCIÓN</option>');
                                                     //    $(".depar").append(' <option value="2">OTROS</option>');
                                                          


                                                     // }

                                                 });

                                             </script>
                                             <div class="col-lg-4">

                                               <br><div class="input-group-btn">
                                                <button class="btn btn-info" onclick="botonArchivo()"><span class="glyphicon glyphicon-file"></span> Crear archivo</button>
                                            </div></div>

                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <a href="#" id="descargaPT" class="hidden" download></a>
                                                <div id="resban"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>  
                            </div>
                        </div>






                        <div class="modal fade" id="myModal2_caja_chica" role="dialog">
                            <div class="modal-dialog">
                                <div class="modal-content"> 
                                    <div class="modal-header"> 
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4>GENERAR DOCUMENTOS DE TRANSFERENCIAS CAJA CHICA<h4>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-lg-12">

                                                    <div class="col-lg-6">
                                                        <center><h5 class="modal-title">SELECCIONA EMPRESA</h5>
                                                            <div class="input-group">    
                                                                <select id="empresa_valor_ch" name="empresa_valor_ch" class=" form-control lista_empresa"></select>
                                                            </div></center><br></div>

                                                            <div class="col-lg-6">
                                                                <center><h5 class="modal-title">SELECCIONA CUENTA</h5>
                                                                    <div class="input-group">    
                                                                        <select style="width: 100%;" id="cuenta_valor_2ch" name="cuenta_valor_2ch" class=" form-control lista_cuenta_ch" required><option value="">- Selecciona cuenta - </option></select>
                                                                    </div></center><br></div>
                                                                </div>

                                                                <script type="text/javascript">

                                                                   $('select#empresa_valor_ch').on('change',function(){
                                                                     $(".lista_cuenta_ch").html("");
                                                                     var valor = $(this).val();

                                                                     $.getJSON( url + "Listas_select/lista_cuentas"+"/"+valor).done( function( data ){
                                                                        $.each( data, function( i, v){
                                                                            $(".lista_cuenta_ch").append('<option value="'+v.idcuenta+'" data-value="'+v.idcuenta+'">'+v.nombre+" - "+v.nodecta+" - "+v.nombanco+'</option>');
                                                                        });
                                                                    });

                                                                 });

                                                             </script>
                                                             <div class="col-lg-4">

                                                               <br><div class="input-group-btn">
                                                                <button class="btn btn-info" onclick="btn_caja_chica()"><span class="glyphicon glyphicon-file"></span> Crear archivo</button>
                                                            </div></div>

                                                        </div>
                                                        <div class="row">
                                                            <div class="col-lg-12">
                                                                <a href="#" id="descargaPTCH" class="hidden" download></a>
                                                                <div id="resban_caja_chica"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>  
                                            </div>
                                        </div>




                                        <div class="modal fade modal-alertas" id="myModalpoliza" role="dialog">
                                            <div class="modal-dialog">
                                               <div class="modal-content">
                                                <div class="modal-header"  style="background: #00A65A; color: #fff;">
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    <h4 class="modal-title">CONFIRMAR PAGO</h4>
                                                </div>  
                                                <form method="post" id="infopago_polizad">
                                                    <div class="modal-body"></div>
                                                    <div class="modal-footer"></div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>





                                    <div class="modal fade modal-alertas" id="myModalpoliza_chica" role="dialog">
                                        <div class="modal-dialog">
                                           <div class="modal-content">
                                            <div class="modal-header"  style="background: #00A65A; color: #fff;">
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                <h4 class="modal-title">CONFIRMAR PAGO</h4>
                                            </div>  
                                            <form method="post" id="infopago_chica">
                                                <div class="modal-body"></div>
                                                <div class="modal-footer"></div>
                                            </form>
                                        </div>
                                    </div>
                                </div>


                                <div class="modal fade modal-alertas" id="modalCaja" role="dialog">
                                    <div class="modal-dialog">
                                       <div class="modal-content">
                                        <div class="modal-header"  style="background: #00A65A; color: #fff;">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">REGISTRAR DATOS DE PAGO</h4>
                                        </div>  
                                        <form method="post" id="infopago_chica_1">
                                            <div class="modal-body"></div>
                                            <div class="modal-footer"></div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="modal fade modal-alertas" id="myModalpoliza_ch" role="dialog">
                                <div class="modal-dialog">
                                   <div class="modal-content">
                                    <div class="modal-header"  style="background: #00A65A; color: #fff;">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">CONFIRMAR PAGO</h4>
                                    </div>  
                                    <form method="post" id="infopago_ch">
                                        <div class="modal-body"></div>
                                        <div class="modal-footer"></div>
                                    </form>
                                </div>
                            </div>
                        </div>



                        <div class="modal fade modal-alertas" id="myModalcomentario1" role="dialog">
                            <div class="modal-dialog">
                               <div class="modal-content">
                                <div class="modal-header"  style="background: #DD4B39; color: #fff;">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">Mótivo de rechazo</h4>
                                </div>  
                                <form method="post" id="infosol1">
                                    <div class="modal-body">   
                                    </div>
                                    <div class="modal-footer">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>


                    <div class="modal fade modal-alertas" id="myModalcomentario3" role="dialog">
                        <div class="modal-dialog">
                           <div class="modal-content">
                            <div class="modal-header"  style="background: #DD4B39; color: #fff;">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">MÓTIVO DE RECHAZO</h4>
                            </div>  
                            <form method="post" id="infosol22">
                                <div class="modal-body"></div>
                                <div class="modal-footer"></div>
                            </form>
                        </div>
                    </div>
                </div>




                <div class="modal fade modal-alertas" id="myModalcomentario3_ch" role="dialog">
                    <div class="modal-dialog">
                       <div class="modal-content">
                        <div class="modal-header"  style="background: #DD4B39; color: #fff;">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">MÓTIVO DE RECHAZO</h4>
                        </div>  
                        <form method="post" id="infosol22_ch">
                            <div class="modal-body"></div>
                            <div class="modal-footer"></div>
                        </form>
                    </div>
                </div>
            </div>



            <div class="modal fade" id="myModal22" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content"> 
                        <div class="modal-header"> 
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4>GENERAR DOCUMENTO<h4>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <h5 class="modal-title">Selecciona una empresa</h5>
                                        <div class="input-group">    
                                            <select id="empr_otros" name="empr_otros" class=" form-control lista_empresa"></select>

                                            <div class="input-group-btn">
                                                <button class="btn btn-info" onclick="pdf_otros()"><span class="glyphicon glyphicon-file"></span> Crear archivo</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div id="resban_otros"></div>
                                    </div>
                                </div>
                            </div>
                        </div>  
                    </div>
                </div>



                <div class="modal fade modal-alertas" id="myModalEspera" role="dialog">
                    <div class="modal-dialog">
                       <div class="modal-content">
                        <div class="modal-header"  style="background: #DD4B39; color: #fff;">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">MÓTIVO DE ESPERA</h4>
                        </div>  
                        <form method="post" id="form_espera_uno">
                            <div class="modal-body"></div>
                            <div class="modal-footer"></div>
                        </form>
                    </div>
                </div>
            </div>



            <div class="modal fade modal-alertas" id="myModalEspera2" role="dialog">
                <div class="modal-dialog">
                   <div class="modal-content">
                    <div class="modal-header"  style="background: #DD4B39; color: #fff;">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">MÓTIVO DE ESPERA</h4>
                    </div>  
                    <form method="post" id="form_espera_dos">
                        <div class="modal-body"></div>
                        <div class="modal-footer"></div>
                    </form>
                </div>
            </div>
        </div>




        <div class="modal fade modal-alertas" id="myModalEspera3" role="dialog">
            <div class="modal-dialog">
               <div class="modal-content">
                <div class="modal-header"  style="background: #DD4B39; color: #fff;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">MÓTIVO DE ESPERA</h4>
                </div>  
                <form method="post" id="form_espera_tres">
                    <div class="modal-body"></div>
                    <div class="modal-footer"></div>
                </form>
            </div>
        </div>
    </div>






    <div class="modal fade modal-alertas" id="myModalcambioTEA" role="dialog">
        <div class="modal-dialog">
           <div class="modal-content">
            <div class="modal-header"  style="background: #3C8DBC; color: #fff;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">CAMBIAR METODO DE PAGO</h4>
            </div>  
            <form method="post" id="form_cambia_tea">
                <div class="modal-body"></div>
                <div class="modal-footer"></div>
            </form>
        </div>
    </div>
</div>




<div class="modal fade modal-alertas" id="myModalcambiOTRO" role="dialog">
    <div class="modal-dialog">
       <div class="modal-content">
        <div class="modal-header"  style="background: #3C8DBC; color: #fff;">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">CAMBIAR MÉTODO DE PAGO</h4>
        </div>  
        <form method="post" id="form_cambia_OTRO">
            <div class="modal-body"></div>
            <div class="modal-footer"></div>
        </form>
    </div>
</div>
</div>







<div class="modal fade modal-alertas" id="myModal" role="dialog">
    <div class="modal-dialog">
       <div class="modal-content">
        <div class="modal-header" style="background: #00A65A;">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">CONFIRMAR SOLICITUD</h4>
        </div>  
        <div class="modal-body"></div>
        <div class="modal-footer"></div>
    </div>
</div>
</div>



<div class="modal fade modal-alertas" id="myModal_chica_all" role="dialog">
    <div class="modal-dialog">
       <div class="modal-content">
        <div class="modal-header" style="background: #00A65A; color: #fff;">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">ENVIADO A DISPERSIÓN DE PAGOS</h4>
        </div>  
        <div class="modal-body"></div>
        <div class="modal-footer"></div>
    </div>
</div>
</div>


<div class="modal fade modal-alertas " id="myModaltxt" role="dialog">
    <div class="modal-dialog">
       <div class="modal-content">
       <div class="modal-header" >
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"><img style="width: 10%; height: 10%;" src= "<?= base_url("img/check.png")?>"> PAGOS CONFIRMADOS</h4>
        </div>   
        <div class="modal-body"></div>
        <div class="modal-footer"></div>
    </div>
</div>
</div>



<div class="modal fade modal-alertas" id="myModalregresar" role="dialog">
    <div class="modal-dialog">
       <div class="modal-content">
        <div class="modal-header"  style="background: #DD4B39;">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Regresar solicitud</h4>
        </div>  
        <div class="modal-body"></div>
        <div class="modal-footer"></div>
    </div>
</div>
</div>

<div class="modal fade modal-alertas" id="modal_tipo_cambio" role="dialog">
    <div class="modal-dialog">
       <div class="modal-content">
        <div class="modal-header"  style="background: #00A65A; color: #fff;">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">CAMBIO DE MONEDA</h4>
        </div>  
        <form method="post" id="form_cambio">
            <div class="modal-body"></div>
        </form>
    </div>
</div>
</div>

<form>
    <div class="modal fade modal-alertas" id="myModalx" role="dialog">
        <div class="modal-dialog">
           <div class="modal-content">
            <div class="modal-header"  style="background: #DD4B39;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Declinar solicitud</h4>
            </div>  
            <div class="modal-body"></div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>
</form>









<div class="modal fade" id="myModal3" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content"> 
            <div class="modal-header"> 
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4>GENERAR DOCUMENTO TXT INDIVIDUAL<h4>
                </div>
                <div class="modal-body">

                    <div class="col-lg-12">
                        <center><h5 class="modal-title">SELECCIONA CUENTA</h5>
                            <div class="input-group">    
                                <select style="width: 100%;" id="cuenta_valor_ind" name="cuenta_valor_ind" class="form-control lista_cuentas_ind" required>
                                </select>
                            </div></center><br></div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div id="resbane"></div>
                            </div>
                        </div>
                    </div>
                </div>  
            </div>
        </div>





        <div class="modal fade modal-alertas" id="myModalconsecutivo" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content"> 
                    <div class="modal-header"> 
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4>DATOS DE CHEQUE<h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="col-lg-12">
                                        <center><h5 class="modal-title"> SELECCIONA OPCIÓN </h5>
                                            <div class="input-group">



                                                <select style="width: 100%;" id="cuenta_valor" name="cuenta_valor" class=" form-control lista_cta" required> </select>
                                            </div></center><br>
                                            <input type="text" name="serie_cheque" id="serie_cheque" class="form-control">
                                            <input type="hidden" name="numpago" id="numpago" readonly="readonly" class="form-control">
                                            <input type="hidden" name="numctaserie" id="numctaserie" readonly="readonly" class="form-control">
                                        </div>
                                    </div>

                                    <script type="text/javascript">


                                        function revisar_nocta(valor){
                                            $.getJSON( url + "Cuentasxp/lista_cta"+"/"+valor).done( function( data ){
                                                $(".lista_cta").html("");
                                                $(".lista_cta").append('<option value="" selected=""> -SELECCIONA OPCIÓN- </option>');
                                                $.each( data, function( i, v){
                                                    $(".lista_cta").append('<option value="'+v.idcuenta+'" data-value="'+v.idcuenta+'">'+v.nombre+" - "+v.nodecta+" - "+v.nombanco+'</option>');
                                                });
                                            });
                                            document.getElementById("numpago").value = valor;
                                        }





                                        $('select#cuenta_valor').on('change',function(){
                                            $(".cuenta_valor").html("");
                                            var valor = $(this).val();
                                            $.getJSON( url + "Cuentasxp/getConsecutivo"+"/"+valor).done( function( data ){
                                                $.each( data, function( i, v){
                                                    document.getElementById("serie_cheque").value = v.serie;
                                                    document.getElementById("numctaserie").value = valor;
                                                });
                                            });
                                        });

                                    </script>



                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <a href="#" id="descargaPT" class="hidden" download></a>
                                        <div id="resban"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <div class='btn-group'><button type='button' class='btn btn-success' onclick='acepta_cheque()'>ACEPTAR</button><button type='button' class='btn btn-danger' onclick='cancelarr_cheque()'>CANCELAR</button></div>
                            </div>
                        </div>  
                    </div>
                </div>


                <!-- ________________________________________________________________________________________  -->  


                <!-- ________________________________________________________________________________________  -->  


                <!-- ________________________________________________________________________________________  -->  


                <div class="modal fade modal-alertas" id="myModalconsecutivo_chica" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content"> 
                            <div class="modal-header"> 
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4>FORMA DE PAGO<h4>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="col-lg-12">


                                                <center><h5 class="modal-title">SELECCIONA OPCIÓN DE PAGO</h5>
                                                    <div class="input-group">



                                                        <select style="width: 100%;"  id="tipoPago_chica" name="tipoPago_chica" class="form-control" required><option value="" selected=""> -SELECCIONA OPCIÓN- </option><option value="ECHQ">CHEQUE</option><option value="TEA">TRANSFERENCIA</option></select>
                                                    </div></center><BR>



                                                    <center><h5 name="titulocta" class="modal-title"><b>SELECCIONA CUENTA</b></h5>
                                                        <div class="input-group">    
                                                            <select style="width: 100%;" id="cuenta_valor_ch" name="cuenta_valor_ch" class=" form-control lista_cta_ch" required></select>
                                                        </div></center><br>
                                                        <center> <input type="text" style="width: 80%;" name="serie_cheque_ch" id="serie_cheque_ch" class="form-control"></center>


                                                        <center><div class="input-group">    

                                                            <select style="width: 100%;" id='idcuenta' name='idcuenta'  class=" form-control lista_prov_ch" required><option value="" selected=""> -SELECCIONA OPCIÓN- </option></select>

                                                        </div></center>


                                                        <input type="hidden" name="numpago_ch" id="numpago_ch" readonly="readonly" class="form-control">
                                                        <input type="hidden" name="numctaserie_ch" id="numctaserie_ch" readonly="readonly" class="form-control">

                                                    </div>
                                                </div>

                                                <script type="text/javascript">


                                                    function limpiar_mod_chica(){
                                                        $("select[name='cuenta_valor_ch']").hide();
                                                        $("select[name='idcuenta']").hide();
                                                        $("h5[name='titulocta']").hide();
                                                        $("input[name='serie_cheque_ch']").hide();
                                                        $("button[name='acepta_chic']").hide();
                                                        $("button[name='cierra_chic']").hide();

                                                        $(".lista_prov_ch").html("");
                                                        $(".lista_cta_ch").html("");
                                                    }

                                                    $("select[name='cuenta_valor_ch']").hide();
                                                    $("select[name='idcuenta']").hide();
                                                    $("h5[name='titulocta']").hide();
                                                    $("input[name='serie_cheque_ch']").hide();
                                                    $("button[name='acepta_chic']").hide();
                                                    $("button[name='cierra_chic']").hide();



                                                    $('select#tipoPago_chica').on('change',function(){

                                                        if( $(this).val() == ""){
                                                            $("select[name='cuenta_valor_ch']").hide();
                                                            $("select[name='idcuenta']").hide();
                                                            $("h5[name='titulocta']").hide();
                                                            $("input[name='serie_cheque_ch']").hide();
                                                            $("button[name='acepta_chic']").hide();
                                                            $("button[name='cierra_chic']").hide();

                                                        } if( $(this).val() == "ECHQ" ){
                                                            $("select[name='cuenta_valor_ch']").show();
                                                            $("select[name='idcuenta']").hide();
                                                            $("h5[name='titulocta']").show();
                                                            $("input[name='serie_cheque_ch']").show();
                                                            $("button[name='acepta_chic']").show();
                                                            $("button[name='cierra_chic']").show();
                                                        }

                                                        if( $(this).val() == "TEA" ){
                                                            $("select[name='cuenta_valor_ch']").hide();
                                                            $("h5[name='titulocta']").show();
                                                            $("input[name='serie_cheque_ch']").hide();
                                                            $("button[name='acepta_chic']").hide();
                                                            $("button[name='cierra_chic']").hide();
                                                            $("select[name='idcuenta']").show();
                                                            $("button[name='acepta_chic']").show();
                                                            $("button[name='cierra_chic']").show();



                                                        }

                                                    });
                                                    function consultar_cta_chica(valor){
                                                       $.getJSON( url + "Cuentasxp/revisar_proveedores"+"/"+valor).done( function( data ){
                                                        $(".lista_prov_ch").html("");
                                                        $(".lista_prov_ch").append('<option value="" selected=""> -SELECCIONA OPCIÓN- </option>');
                                                        $.each( data, function( i, v){
                                                            $(".lista_prov_ch").append('<option value="'+v.cuenta+'" data-value="'+v.idproveedor+'">'+v.alias+" - "+v.cuenta+" - "+v.nombanco+'</option>');
                                                        });

                                                    });

                                                   }
                                                   function revisar_nocta_chica(valor){
                                                    $.getJSON( url + "Cuentasxp/lista_cta_ch"+"/"+valor).done( function( data ){
                                                        $(".lista_cta_ch").html("");
                                                        $(".lista_cta_ch").append('<option value="" selected=""> -SELECCIONA OPCIÓN- </option>');
                                                        $.each( data, function( i, v){
                                                            $(".lista_cta_ch").append('<option value="'+v.idcuenta+'" data-value="'+v.idcuenta+'">'+v.nombre+" - "+v.nodecta+" - "+v.nombanco+'</option>');
                                                        });
                                                    });
                                                    document.getElementById("numpago_ch").value = valor;
                                                }

                                                $('select#cuenta_valor_ch').on('change',function(){
                                                    $(".cuenta_valor_ch").html("");
                                                    var valor = $(this).val();
                                                    $.getJSON( url + "Cuentasxp/getConsecutivo_chica"+"/"+valor).done( function( data ){
                                                        $.each( data, function( i, v){
                                                            document.getElementById("serie_cheque_ch").value = v.serie;
                                                            document.getElementById("numctaserie_ch").value = valor;
                                                        });
                                                    });
                                                });

                                            </script>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <a href="#" id="descargaPT" class="hidden" download></a>
                                                <div id="resban_chi"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <div class='btn-group'><button type='button' name="acepta_chic" class='btn btn-success' onclick='acepta_cheque_chi()'>ACEPTAR</button><button name="cierra_chic" type='button' class='btn btn-danger' onclick='cancelar_caja_ch()'>CANCELAR</button></div>
                                    </div>
                                </div>  
                            </div>
                        </div>


                        <script>

                            var tabla_provison_pago;
                            $(document).ready(function () {
                                $('.faq').popover();
                                $("#cxp1_sel2").change( function(){
                                    tabla_transferencias.ajax.reload();
                                });
                                $("#cxp2_sel2").change( function(){
                                    tabla_transferencias.ajax.reload();
                                });
                            });






   // TABLA DOS_____________________________________________

   $("#tabla_autoriza_dg_tranferencias").ready( function(){

    $('#tabla_autoriza_dg_tranferencias thead tr:eq(0) th').each( function (i) {

       if( i != 0  && i != 1  &&  i != 10){
        var title = $(this).text();
        $(this).html('<input type="text" style="width:100%;" placeholder="'+title+'"/>' );
        $( 'input', this ).on('keyup change', function () {
            if (tabla_transferencias.column(i).search() !== this.value ) {
                tabla_transferencias
                .column(i)
                .search( this.value )
                .draw();

                var total = 0;
                var index = tabla_transferencias.rows( { selected: true, search: 'applied' } ).indexes();
                var data = tabla_transferencias.rows( index ).data();

                $.each(data, function(i, v){
                    total += parseFloat(v.autorizado);
                });

                var to1 = formatMoney(total);
                document.getElementById("myText2").value = to1;
            }
        } );
    }
});




    $('#tabla_autoriza_dg_tranferencias').on('xhr.dt', function ( e, settings, json, xhr ) {
        var total = 0;
        $.each(json.data, function(i, v){
            total += parseFloat(v.autorizado);
        });
        var to = formatMoney(total);
        document.getElementById("myText2").value = formatMoney(total);
    });

    tabla_transferencias = $("#tabla_autoriza_dg_tranferencias").DataTable({
      dom: 'Brtip',
      width: 'auto',
      "buttons": [
      {            
        text: '<i class="fas fa-file-invoice"></i> GENERAR DOCUMENTO BANCO',
        action: function(){

            $("#myModal2").modal();
            $("#myModal2 #resban").html("");
            $("#myModal2 .form-control").val("");

        },
        attr: {
            class: 'btn btn-primary',
        }
    },
    {
     extend: 'excelHtml5',             
     text: '<i class="fas fa-file-excel"></i> EXPORTAR EXCEL',
     messageTop: "Lista de pagos autorizados por Dirección General (TRANSFERENCIAS)",
     attr: {
         class: 'btn btn-success'       
     },
     exportOptions: {
         columns: [ 0, 1, 2, 3, 4, 5, 6, 7 ]
     }
 },
 {
    text: '<i class="fa fa-check"></i> ACEPTAR PAGOS',
    action: function(){

        if ($('input[name="idT[]"]:checked').length > 0) {

            var idpago = $(tabla_transferencias.$('input[name="idT[]"]:checked')).map(function () { return this.value; }).get();

            $.get(url+"Cuentasxp/aceptocpp/"+idpago).done(function () { 
                $("#myModaltxt").modal('toggle'); 
                tabla_transferencias.ajax.reload();
                $("#myModaltxt .modal-footer").html("");
                $("#myModaltxt .modal-body").html("");
                $("#myModaltxt").modal();
                $("#myModaltxt .modal-body").append("<center><label>Pago(s) pasado(s) a proceso</label></center>");
            });
        }
    },
    attr: {
        class: 'btn btn-dark',
    }

},

],
"language":lenguaje,
"processing": true,
"pageLength": 50,
"bAutoWidth": false,
"bLengthChange": false,
"scrollX": true,
"bInfo": false,
"searching": true,
"fixedColumns": true,

"columns": [
{ "orderable": false,
"className": 'details-control',
"orderable": false,
"data" : null,

},

{"orderable": true, "width": "4%" },


// {

//     "orderable": false,
//     "width": "3%",
//     "data": function( d ){
//         return '<i class="fa animacion fa fa-caret-right"></i>'+'';
//     }
// },
{
    "width": "10%",
    "data": function( d ){
        if (d.folio != null && d.prioridad == 1) {
            return d.folio + "<br><small class='label pull-center bg-red'>URGENTE</small>";
        } else if (d.folio != null && d.prioridad != 1){
           return d.folio;
       } else if(d.folio == null && d.prioridad == 1){
        return "<b>SF</b> <br> <small class='label pull-center bg-red'>URGENTE</small>";
    } else if(d.folio == null && d.prioridad != 1){
        return "<b>SF</b>";
    }
}
},
{
    "data": function( d ){
        return '<p style="font-size: .9em">'+d.nombre+'</p>';
    }
},
{
    "width": "10%",
    "data": function( d ){

      if( d.moneda != 'MXN' ){
       return '<p style="font-size: .9em">$ '+formatMoney(d.autorizado)+' USD</p>';
   }else{
    return '<p style="font-size: .9em">$ '+formatMoney(d.autorizado)+' MXN</p>';
}
}
},



{
    "width": "10%",
    "data": function( d ){

        if(d.moneda=='USD'){

            if( d.tipoCambio == ''|| d.tipoCambio == null){
                return '<p style="font-size: .9em">$ '+formatMoney(0)+'</p>';
            } 
            else{

              if( d.moneda != 'MXN' ){
                return '<p style="font-size: .9em">$ '+formatMoney( (d.tipoCambio ? d.tipoCambio * d.autorizado : d.autorizado) )+" "+( d.tipoCambio ? 'MXN' : d.moneda )+'</p>';
            }else{
                return '<p style="font-size: .9em">$ '+formatMoney( d.autorizado )+' MXN</p>';
            }
        }

    }

    if(d.moneda=='MXN'){
        return '<p style="font-size: .9em">$ '+formatMoney(d.autorizado)+' MXN</p>';
    }
}
},
{
    "width": "10%",
    "data": function( d ){
        return '<p style="font-size: .9em">'+d.fecelab+'</p>';
    }
},
{
    "width": "10%",
    "data": function( d ){
        return '<p style="font-size: .9em">'+d.nomdepto+'</p>';
    }
},
{
    "width": "10%",
    "data": function( d ){
        return '<p style="font-size: .9em">'+d.nemp+'</p>';
    }
}, 

{
    "width": "10%",
    "data": function( d ){

       if(d.estatus==12){
        return '<p style="font-size: .9em">'+d.motivoEspera+'</p>';
    }else{
        return '';

    }

}
}, 
{ 
    "orderable": false,
    "data": function( data ){

        opciones = '<div class="btn-group" role="group">';
        // opciones += '<button type="button" class="btn btn-danger btn-sm cancelar_pago_solicitud" value="'+data.idpago+'" ><i class="fas fa-trash-alt"></i></button>';
        
        if(data.estatus==0){
           if( data.moneda != 'MXN' ){                        
            opciones += '<button type="button" class="btn btn-warning btn-sm cargar_cambio" value="'+data.idpago+'" title="Agregar tipo de cambio"><i class="fas fa-edit"></i></button>';

            opciones += '<button type="button" class="btn btn-warning btn-sm" onClick="mandar_espera('+ data.idpago+')" title="Mandar a espera"><i class="fas fa-clock"></i></button>';

            opciones += '<button data-toggle="tooltip" data-placement="top" title="Eliminar pago" type="button" class="btn btn-danger btn-sm" onClick="eliminar_pago('+data.idpago+')"><i class="fas fa-trash"></i></button>';

             opciones += '<button type="button" class="btn btn-primary btn-sm" onClick="Cambiar_TEA('+data.idpago+')" title="Cambiar metodo de pago"><i class="fas fa-retweet"></i></button>';

        }
        else{

         opciones += '<button type="button" class="btn btn-warning btn-sm" onClick="mandar_espera('+ data.idpago+')" title="Mandar a espera"><i class="fas fa-clock"></i></button>';

         opciones += '<button data-toggle="tooltip" data-placement="top" title="Eliminar pago" type="button" class="btn btn-danger btn-sm" onClick="eliminar_pago('+data.idpago+')"><i class="fas fa-trash"></i></button>';

     }

     opciones += '<button type="button" class="btn btn-primary btn-sm" onClick="Cambiar_TEA('+data.idpago+')" title="Cambiar metodo de pago"><i class="fas fa-retweet"></i></button>';
 }



 if(data.estatus==11){ 

    if( data.moneda != 'MXN' ){                        
        opciones += '<button type="button" class="btn btn-warning btn-sm cargar_cambio" value="'+data.idpago+'" title="Cargar Cambio"><i class="fas fa-edit"></i></button>';
        opciones +='<small class="label pull-right bg-green">Listo para aceptar pago</small><br>';
        opciones += '&nbsp;<button type="button" class="txt_ind btn btn-info btn-sm"  data-pago="'+data.idpago+'" data-empresa="'+data.idEmpresa+'" title="Descargar TXT Individual"><i class="fa fa-download"></i></button>';
 opciones += '<button type="button" class="btn btn-warning btn-sm" onClick="mandar_espera('+ data.idpago+')" title="Mandar a espera"><i class="fas fa-clock"></i></button>';

        opciones += '<button data-toggle="tooltip" data-placement="top" title="Eliminar pago" type="button" class="btn btn-danger btn-sm" onClick="eliminar_pago('+data.idpago+')"><i class="fas fa-trash"></i></button>';


         opciones += '<button type="button" class="btn btn-primary btn-sm" onClick="Cambiar_TEA('+data.idpago+')" title="Cambiar metodo de pago"><i class="fas fa-retweet"></i></button>';


    }

    else{
        opciones +='<small class="label pull-right bg-green">Listo para aceptar pago</small><br>';
        opciones += '&nbsp;<button type="button" id="descarga_2" name="descarga_2" class="txt_ind btn btn-info btn-sm" data-pago="'+data.idpago+'" data-empresa="'+data.idEmpresa+'" title="Descargar TXT Individual"><i class="fa fa-download"></i></button>';

 opciones += '<button type="button" class="btn btn-warning btn-sm" onClick="mandar_espera('+ data.idpago+')" title="Mandar a espera"><i class="fas fa-clock"></i></button>';
        opciones += '<button data-toggle="tooltip" data-placement="top" title="Eliminar pago" type="button" class="btn btn-danger btn-sm" onClick="eliminar_pago('+data.idpago+')"><i class="fas fa-trash"></i></button>';

         opciones += '<button type="button" class="btn btn-primary btn-sm" onClick="Cambiar_TEA('+data.idpago+')" title="Cambiar metodo de pago"><i class="fas fa-retweet"></i></button>';


    }
} 

if(data.estatus==12){ 


    opciones += '<button type="button" class="btn btn-secondary btn-sm" onClick="regresar_espera('+data.idpago+')"><i class="fas fa-clock"></i></button>';

    opciones += '<button data-toggle="tooltip" data-placement="top" title="Eliminar pago" type="button" class="btn btn-danger btn-sm" onClick="eliminar_pago('+data.idpago+')"><i class="fas fa-trash"></i></button>';



} 



return opciones + '</div>';
} 
}
],

columnDefs: [
{
 "searchable": false,
 "orderable": false,
 "targets": 0
},
{

    orderable: false,
    className: 'select-checkbox',
    targets:   1,
    'searchable':false,
    'className': 'dt-body-center',
    'render': function (d, type, full, meta){

        if(full.estatus==11 ){
         return '<input type="checkbox" name="idT[]" style="width:20px;height:20px;"  value="' + full.idpago + '">';
     }else{
         return '';
     }     
 },
 select: {
    style:    'os',
    selector: 'td:first-child'
},
}],

"ajax": {
    "url": url + "Cuentasxp/ver_datos_autdg",
    "type": "POST",
    cache: false,
    "data": function( d ){
    }
},
"order": [[ 1, 'asc' ]]

});


 
 




 $('#tabla_autoriza_dg_tranferencias tbody').on('click', 'td.details-control', function () {
    var tr = $(this).closest('tr');
    var row = tabla_transferencias.row( tr );

    if ( row.child.isShown() ) {
        row.child.hide();
        tr.removeClass('shown');
        $(this).parent().find('.animacion').removeClass("fa-caret-down").addClass("fa-caret-right");
    }
    else {

        var informacion_adicional = '<table class="table text-justify">'+
        '<tr>'+
        '<td style="font-size: .8em"><strong>JUSTIFICACIÓN: </strong>'+row.data().justificacion+'</td>'+
        '</tr>'+
        '</table>';

        row.child( informacion_adicional ).show();
        tr.addClass('shown');
        $(this).parent().find('.animacion').removeClass("fa-caret-right").addClass("fa-caret-down");
    }
});

 tabla_transferencias.on('search.dt order.dt', function (){
    tabla_transferencias.column(0,{search:'applied', order:'applied'}).nodes().each(function(cell, i){
        cell.innerHTML = i+1;
    });
}).draw(); 


 $("#tabla_autoriza_dg_tranferencias tbody").on("click", ".cancelar_pago_solicitud", function(){
    $.post( url + "Cuentasxp/regresar_pago", { idpago : $(this).val() } ).done( function(){
        tabla_transferencias.ajax.reload();
    });
});





$("#tabla_autoriza_dg_tranferencias tbody").on("click", ".cargar_cambio", function(){

    var tr = $(this).closest('tr');
    var row = tabla_transferencias.row( tr );

    idautopago = $(this).val();

    $("#modal_tipo_cambio .modal-body").html("");
    $("#modal_tipo_cambio .modal-body").append('<div class="row"><div class="col-lg-12"><p><b>MONEDA:</b> '+row.data().moneda+'</p></div></div>');
    $("#modal_tipo_cambio .modal-body").append('<div class="row"><div class="col-lg-12 form-group"><label>TIPO CAMBIO</label><div class="input-group"><span class="input-group-addon"><i class="fas fa-dollar-sign"></i></span><input class="form-control" name="tipo_cambio" required></div></div></div>');
    $("#modal_tipo_cambio .modal-body").append('<div class="row"><div class="col-lg-8 col-lg-offset-2"><button class="btn btn-success btn-block">EDITAR</button></div></div>');
    $("#modal_tipo_cambio").modal();
});


}); 
// FIN TABLA DOS_____________________________________________



// TABLA TRES ______________________________________________
$("#tabla_autoriza_dg_otros").ready( function(){

  $('#tabla_autoriza_dg_otros thead tr:eq(0) th').each( function (i) {
   if( i!=0 && i!=1 && i!=11){
    var title = $(this).text();
    $(this).html('<input type="text" style="width:100%;" placeholder="'+title+'"/>' );
    $( 'input', this ).on('keyup change', function () {

        if (tabla_otros.column(i).search() !== this.value ) {
            tabla_otros
            .column(i)
            .search( this.value )
            .draw();

            var total = 0;
            var index = tabla_otros.rows( { selected: true, search: 'applied' } ).indexes();
            var data = tabla_otros.rows( index ).data();

            $.each(data, function(i, v){
                total += parseFloat(v.autorizado);
            });
            var to1 = formatMoney(total);
            document.getElementById("myText3").value = formatMoney(total);
        }
    } );
}
});

  $('#tabla_autoriza_dg_otros').on('xhr.dt', function ( e, settings, json, xhr ) {
    var total = 0;
    $.each(json.data, function(i, v){
        total += parseFloat(v.autorizado);
    });
    var to = formatMoney(total);
    document.getElementById("myText3").value = to;
});

  tabla_otros = $("#tabla_autoriza_dg_otros").DataTable({

   dom: 'Brtip',
   width: 'auto',
   "buttons": [
   {            
    text: '<i class="fas fa-file-invoice"></i> GENERAR DOCUMENTO RELACIÓN',
    action: function(){
        $("#myModal22").modal();
        $("#myModal22 #resban_otros").html("");
        $("#myModal22 .form-control").val("");

    },
    attr: {
        class: 'btn btn-primary',
    }
},
{
 extend: 'excelHtml5',             
 text: '<i class="fas fa-file-excel"></i> EXPORTAR EXCEL',
 messageTop: "Lista de pagos autorizados por Dirección General (OTROS)",
 attr: {
     class: 'btn btn-success'       
 },
 exportOptions: {
    columns: [ 0, 1, 2, 3, 4, 5, 6, 7 ]
}
},
{
    text: '<i class="fa fa-check"></i> ACEPTAR PAGOS',
    action: function(){

      if ($('input[name="id[]"]:checked').length > 0) {

          var idpago = $(tabla_otros.$('input[name="id[]"]:checked')).map(function () { return this.value; }).get();

          $.get(url+"Cuentasxp/aceptocpp_OT/"+idpago).done(function () { 

            $("#myModaltxt").modal('toggle');
            tabla_otros.ajax.reload();
            $("#myModaltxt .modal-footer").html("");
            $("#myModaltxt .modal-body").html("");
            $("#myModaltxt").modal();
            $("#myModaltxt .modal-body").append("<center><label>Pago(s) pasado(s) a proceso</label></center>");

            


        });

      }
  },
  attr: {
    class: 'btn btn-dark',
}

}


],
"language":lenguaje,
"processing": true,
"pageLength": 50,
"bAutoWidth": false,
"bLengthChange": false,
"scrollX": true,
"bInfo": false,
"searching": true,
"fixedColumns": true,
"columns": [


{ "orderable": false,
"className": 'details-control',
"orderable": false,
"data" : null,

},
{ "width": "4%" },

 
{
    "width": "8%",
    "data": function( d ){

        if (d.folio != null && d.prioridad == 1) {
            return d.folio + "<br><small class='label pull-center bg-red'>URGENTE</small>";
        } else if (d.folio != null && d.prioridad != 1){
           return d.folio;
       } else if(d.folio == null && d.prioridad == 1){
        return "<b>SF</b> <br> <small class='label pull-center bg-red'>URGENTE</small>";
    } else if(d.folio == null && d.prioridad != 1){
        return "<b>SF</b>";
    }
}
},
{"width": "12%",
"data": function( d ){
    return '<p style="font-size: .9em">'+d.nombre+'</p>';
}
},
{
    "width": "10%",
    "data": function( d ){

        if( d.moneda != 'MXN' ){
         return '<p style="font-size: .9em">$ '+formatMoney( d.autorizado )+' USD</p>';
     }
     else{
        return '<p style="font-size: .9em">$ '+formatMoney( d.autorizado )+' MXN</p>';
    }

}
},

{
    "width": "12%",
    "data": function( d ){

      if( d.tipoCambio != '' || d.tipoCambio == 0 || d.tipoCambio == null){

        if( d.moneda != 'MXN' ){
            return '<p style="font-size: .9em">$ '+formatMoney( (d.tipoCambio ? d.tipoCambio * d.autorizado : d.autorizado) )+" "+( d.tipoCambio ? 'MXN' : d.moneda )+'</p>';
        }else{
            return '<p style="font-size: .9em">$ '+formatMoney( d.autorizado )+' MXN</p>';
        }

    } 
    else{

        return '<p style="font-size: .9em">$ '+formatMoney(0)+'</p>';
    }
}
},


{
    "width": "8%",
    "data": function( d ){
        return '<p style="font-size: .9em">'+d.metoPago+d.referencia+'</p>';
    }
},
{
    "width": "10%",
    "data": function( d ){
        return '<p style="font-size: .9em">'+d.fecelab+'</p>';
    }
},
{
    "width": "12%",
    "data": function( d ){
        return '<p style="font-size: .9em">'+d.nomdepto+'</p>';
    }
},
{
    "width": "10%",
    "data": function( d ){
        return '<p style="font-size: .9em">'+d.nemp+'</p>';
    }
}, 

{
    "width": "12%",
    "data": function( d ){

       if(d.estatus==12){
        return '<p style="font-size: .9em">'+d.motivoEspera+'</p>';
    }else{
        return '';

    }

}
}, 


{  "width": "12%",
"orderable": false,
"data": function( data ){

    opciones = '<div class="btn-group" role="group">';

    if(data.estatus==0&&data.metoPago =="ECHQ"){                        
       opciones += '<button type="button" class="btn btn-warning btn-sm" onClick="mandar_espera_ot('+data.idpago+')" title="Mandar a espera" ><i class="fas fa-clock"></i></button>';
       opciones += '<button type="button" class="btn btn-info btn-sm" onClick="ingresar_consecutivo('+data.idpago+');revisar_nocta('+data.idpago+');" title="Ingresar consecutivo de cheque" ><i class="fa fa-pencil-square-o"></i></button>';

       opciones += '<button data-toggle="tooltip" data-placement="top" title="Eliminar pago" type="button" class="btn btn-danger btn-sm" onClick="eliminar_pago('+data.idpago+')"><i class="fas fa-trash"></i></button>';

       opciones += '<button type="button" class="btn btn-primary btn-sm" onClick="Cambiar_OTRO('+data.idpago+')" title="Cambiar metodo de pago"><i class="fas fa-retweet"></i></button>';




   }

   if(data.estatus==0&&data.metoPago !="ECHQ"){                        
       opciones += '<button type="button" class="btn btn-warning btn-sm" onClick="mandar_espera_ot('+data.idpago+')" title="Mandar a espera"><i class="fas fa-clock"></i></button>';

       opciones += '<button data-toggle="tooltip" data-placement="top" title="Eliminar pago" type="button" class="btn btn-danger btn-sm" onClick="eliminar_pago('+data.idpago+')"><i class="fas fa-trash"></i></button>';

       opciones += '<button type="button" class="btn btn-primary btn-sm" onClick="Cambiar_OTRO('+data.idpago+')" title="Cambiar metodo de pago"><i class="fas fa-retweet"></i></button>';


   }


   if(data.estatus==11 ){                        
         opciones +='<small class="label pull-right bg-green">Listo para aceptar pago</small><br>';

        opciones += '<button data-toggle="tooltip" data-placement="top" title="Eliminar pago" type="button" class="btn btn-danger btn-sm" onClick="eliminar_pago('+data.idpago+')"><i class="fas fa-trash"></i></button>';

         opciones += '<button type="button" class="btn btn-primary btn-sm" onClick="Cambiar_OTRO('+data.idpago+')" title="Cambiar metodo de pago"><i class="fas fa-retweet"></i></button>';



    }

    if(data.estatus==12){ 
        opciones += '<button type="button" class="btn btn-secondary btn-sm" onClick="regresar_espera_otros('+data.idpago+')" title="Desbloquear"><i class="fas fa-clock"></i></button>';

        opciones += '<button data-toggle="tooltip" data-placement="top" title="Eliminar pago" type="button" class="btn btn-danger btn-sm" onClick="eliminar_pago('+data.idpago+')"><i class="fas fa-trash"></i></button>';


    } 


    if(data.estatus==13){ 
 
        opciones += '';

        opciones += '<button data-toggle="tooltip" data-placement="top" title="Eliminar pago" type="button" class="btn btn-danger btn-sm" onClick="eliminar_pago('+data.idpago+')"><i class="fas fa-trash"></i></button>';


         opciones += '<button type="button" class="btn btn-primary btn-sm" onClick="Cambiar_OTRO('+data.idpago+')" title="Cambiar metodo de pago"><i class="fas fa-retweet"></i></button>';


    } 

    return opciones + '</div>';
} 
}
],
columnDefs: [ {
    orderable: false,
    className: 'select-checkbox',
    targets:   1,
    'searchable':false,
    'className': 'dt-body-center',
    'render': function (d, type, full, meta){

        if(full.estatus==11 ){
         return '<input type="checkbox" name="id[]" style="width:20px;height:20px;" value="' + full.idpago + '">';
     }else{
         return '';
     }     
 },
 select: {
    style:    'os',
    selector: 'td:first-child'
},
}],
                //"order": false,
                "ajax": {
                    "url": url + "Cuentasxp/ver_datos_autdg_otros",
                    "type": "POST",
                    cache: false,
                    "data": function( d ){
                        return  $.extend( d, { "nomdepto": $("#cxp1_sel").val(), "idempresa": $("#cxp2_sel").val() } );
                    }
                }
            });

 $('#tabla_autoriza_dg_otros tbody').on('click', 'td.details-control', function () {
    var tr = $(this).closest('tr');
    var row = tabla_otros.row( tr );

    if ( row.child.isShown() ) {
        row.child.hide();
        tr.removeClass('shown');
        $(this).parent().find('.animacion').removeClass("fa-caret-down").addClass("fa-caret-right");
    }
    else {

        var informacion_adicional = '<table class="table text-justify">'+
        '<tr>'+
        '<td style="font-size: .8em"><strong>JUSTIFICACIÓN: </strong>'+row.data().justificacion+'</td>'+
        '</tr>'+
        '</table>';

        row.child( informacion_adicional ).show();
        tr.addClass('shown');
        $(this).parent().find('.animacion').removeClass("fa-caret-right").addClass("fa-caret-down");
    }
});

 tabla_otros.on('search.dt order.dt', function (){
    tabla_otros.column(0,{search:'applied', order:'applied'}).nodes().each(function(cell, i){
        cell.innerHTML = i+1;
    });
}).draw(); 




}); 

// FIN TABLA TRES_____________________________________________

var idautopago;

$("#form_cambio").submit( function(e) {
    e.preventDefault();
}).validate({
    submitHandler: function( form ) {

        var data = new FormData( $(form)[0] );
        data.append("idautopago", idautopago);

        $.ajax({
            url: url + "Cuentasxp/cargar_tipo_cambio",
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            method: 'POST',
                type: 'POST', // For jQuery < 1.9
                success: function(data){
                    if( data[0] ){
                        $("#modal_tipo_cambio").modal('toggle' );
                        tabla_transferencias.ajax.reload();
                    }else{
                        alert("NO SE HA PODIDO COMPLETAR LA SOLICITUD");
                    }
                },error: function( ){
                    alert("ERROR EN EL SISTEMA");
                }
            });
    }
});







// TABLA CUATRO ______________________________________________

var tota2=0;

$("#tabla_cajachica").ready( function () {

   $('#tabla_cajachica thead tr:eq(0) th').each( function (i) {
    if( i != 0 && i != 9){
        var title = $(this).text();
        $(this).html( '<input type="text" style="width:100%;" placeholder="'+title+'" />' );
        $('input', this).on('keyup change', function() {

            if (tabla_caja_chica.column(i).search() !== this.value ) {
                tabla_caja_chica
                .column(i)
                .search( this.value )
                .draw();

                var total = 0;
                var index = tabla_caja_chica.rows( { selected: true, search: 'applied' } ).indexes();
                var data = tabla_caja_chica.rows( index ).data();

                $.each(data, function(i, v){
                    total += parseFloat(v.Cantidad);
                });
                var to1 = formatMoney(total);
                document.getElementById("myText4").value = formatMoney(total);
            }
        } );
    }
});

   $('#tabla_cajachica').on('xhr.dt', function ( e, settings, json, xhr ) {
    var total = 0;
    $.each(json.data, function(i, v){
        total += parseFloat(v.Cantidad);
    });
    var to = formatMoney(total);
    document.getElementById("myText4").value = to;
});

   tabla_caja_chica = $('#tabla_cajachica').DataTable({
    dom: 'Brtip',
    width: 'auto',
    "buttons": [
    {            
        text: '<i class="fas fa-file-invoice"></i> GENERAR DOCUMENTOS CAJA CHICA',
        action: function(){

            $("#myModal2_caja_chica").modal();
            $("#myModal2_caja_chica #resban_caja_chica").html("");
            $("#myModal2_caja_chica .form-control").val("");

        },
        attr: {
            class: 'btn btn-primary',
        }
    },
    {
     extend: 'excelHtml5',             
     text: '<i class="fas fa-file-excel"></i> EXPORTAR EXCEL',
     messageTop: "Lista de pagos autorizados por Dirección General (CAJA CHICA)",
     attr: {
         class: 'btn btn-success'       
     },
     exportOptions: {
        columns: [2, 3, 4, 5, 6]
    }
}

],
"language" : lenguaje,
"processing": true,
"pageLength": 50,
"bAutoWidth": false,
"bLengthChange": false,
"bInfo": false,
"scrollX": true,
responsive: true,
"columns": [
{

    "className": 'details-control',
    "orderable": false,
    "data" : null,
    "defaultContent": '<i class="fas animacion fa-caret-right"></i>'
},
{"width": "10%",
"data": function( d ){
    return '<p style="font-size: .9em">'+d.Responsable+" "+d.apellidos+'</p>';
}
},
{
    "width": "12%",
    "data": function( d ){
        return '<p style="font-size: .9em">'+d.abrev+'</p>';
    }
},
{
    "width": "12%",
    "data": function( d ){
        return '<p style="font-size: .9em">'+d.FECHAFACP+'</p>';
    }
},
{  "width": "10%",
"data": function( d ){
    return '<p style="font-size: .9em">$ '+formatMoney( d.cnt )+' MXN</p>';
}
},
{
    "width": "15%",
    "data": function( d ){
        return '<p style="font-size: .9em">'+d.Departamento+'</p>';
    }
},

{
    "width": "15%",
    "data": function( d ){
        return '<p style="font-size: .9em"> </p>';
    }
},
{
    "width": "15%",
    "data": function( d ){

        if(d.tipoPago!=null && (d.tipoPago=='TEA'||d.tipoPago=='DOMIC'||d.tipoPago=='MAN')){
            return '<p style="font-size: .9em"><small style="font-size:12px;" class="label bg-blue">'+d.tipoPago+' '+d.referencia+'<small></b></p>';
        } 



        if(d.tipoPago!=null && d.tipoPago=='ECHQ'){
            return '<p style="font-size: .9em"><small style="font-size:12px;" class="label bg-orange">'+d.tipoPago+' '+d.referencia+'<small></b></p>';
        } 


        if(d.tipoPago==null){
            return '<p>SIN DATOS</p>';
        } 


    }
},
{
    "width": "10%",
    "data": function( d ){

       if(d.ESTATUS==12){
        return '<p style="font-size: .9em">'+d.motivoEspera+'</p>';
    }else{
        return '';

    }

}
}, 
{
    "orderable": false,
    "data": function( data ){
        opciones = '<div class="btn-group" role="group">';


        if(data.ESTATUS==0){   
         opciones += '<button type="button" class="btn btn-info btn-sm" onClick="limpiar_mod_chica();ingresar_consecutivo_c_chica('+data.PAG+');revisar_nocta_chica('+data.PAG+');consultar_cta_chica('+data.idusuario+');" title="Ingresar consecutivo de cheque"><i class="fas fa-external-link-alt"></i></button>';

         opciones += '<button type="button" class="btn btn-danger btn-sm" onClick="mandar_espera_CH('+data.PAG+')" title="Mandar a espera" ><i class="fas fa-clock"></i></button>';
     }

     if(data.ESTATUS==1){                        
        opciones += '';
    }

    if(data.ESTATUS==2){                        
        opciones += '<button type="button" class="btn btn-success btn-sm" onClick="enviar_pag_caja('+data.PAG+')" title="Aceptar pago"><i class="fas fa-check"></i></button>';
    }

    if(data.ESTATUS==12){ 
        opciones += '<button type="button" class="btn btn-secondary btn-sm" onClick="regresar_espera_ch('+data.PAG+')" title="Desbloquear"><i class="fas fa-clock"></i></button>';
    } 




    return opciones + '</div>';
} 
}
],

"ajax": url + "Cuentasxp/tablaSolCaja"
});

   $('#tabla_cajachica tbody').on('click', 'td.details-control', function () {
    var tr = $(this).closest('tr');
    var row = tabla_caja_chica.row( tr );
    
    if ( row.child.isShown() ) {
        row.child.hide();
        tr.removeClass('shown');
        $(this).parent().find('.animacion').removeClass("fa-caret-down").addClass("fa-caret-right");
    }
    else {

        var solicitudes = '<table class="table">';
                $.each( row.data().solicitudes, function( i, v){//i es el indice y v son los valores de cada fila
                    solicitudes += '<tr>';
                    solicitudes += '<td>'+(i+1)+'.- <b>'+'Proyecto'+'</b> '+v.proyecto+'</td>';
                    solicitudes += '<td>'+'<b>'+'Etapa'+'</b> '+v.ETAPA+'</td>';
                    solicitudes += '<td>'+'<b>'+'Condominio '+'</b> '+v.Condominio+'</td>';
                    solicitudes += '<td>'+'<b>'+'Proveedor '+'</b> '+v.Proveedor+'</td>';
                    solicitudes += '<td>'+'<b>'+'Cantidad '+'</b> $'+formatMoney(v.cnt2)+' MXN</td>';
                    solicitudes += '<td>'+'<b>'+'Fecha Factura '+'</b> '+v.FECHAFACP+'</td>';
                    solicitudes += '<td>'+'<b>'+'Descripcion '+'</b> '+v.Observacion+'</td>';
                    solicitudes += '</tr>';
                });          

                solicitudes += '</table>';

                row.child( solicitudes ).show();
                tr.addClass('shown');
                $(this).parent().find('.animacion').removeClass("fa-caret-right").addClass("fa-caret-down");
            }
        });

   $('#tabla_cajachica').on( 'click', 'input', function () {
    tr = $(this).closest('tr');
    var row = tabla_caja_chica.row( tr );
    
    if($(this).prop("checked")){
     tota2 += parseFloat(row.data().Cantidad);
 }else{
     tota2 -= parseFloat(row.data().Cantidad); 
 }
 $("#totpagarC").html(formatMoney(tota2));
});
});

 function format(d) {

 }
// FIN TABLA CUATRO_____________________________________________




var idsolrechazo;
var link_post2;

$("#infosol1").submit( function(e) {
    e.preventDefault();
}).validate({
    submitHandler: function( form ) {

        var data = new FormData( $(form)[0] );

        data.append("idsolicitud", idsolrechazo);

        $.ajax({
            url: url + link_post2,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            method: 'POST',
                type: 'POST', // For jQuery < 1.9
                success: function(data){
                    if( data.resultado ){
                        $("#myModalcomentario1").modal( 'toggle' );
                        tabla_provison_pago.ajax.reload();
                    }else{
                        alert("NO SE HA PODIDO COMPLETAR LA SOLICITUD");
                    }
                },error: function( ){
                    alert("ERROR EN EL SISTEMA");
                }
            });
    }
});


 // idpagoespera = idpago;
  //   link_espera1 = "Cuentasxp/enviarcolapagos_una/";

  var idpagoespera;
  var link_espera1;

  $("#form_espera_uno").submit( function(e) {
    e.preventDefault();
}).validate({
    submitHandler: function( form ) {

        var data = new FormData( $(form)[0] );

        data.append("idpago", idpagoespera);

        $.ajax({
            url: url + link_espera1,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            method: 'POST',
                type: 'POST', // For jQuery < 1.9
                success: function(data){
                    if( data.resultado ){
                        $("#myModalEspera").modal( 'toggle' );
                        tabla_transferencias.ajax.reload();
                    }else{
                        alert("NO SE HA PODIDO COMPLETAR LA SOLICITUD");
                    }
                },error: function( ){
                    alert("ERROR EN EL SISTEMA");
                }
            });
    }
});












var idpagocambiar;
var link_cambia1;

$("#form_cambia_tea").submit( function(e) {
    e.preventDefault();
}).validate({
    submitHandler: function( form ) {

        var data = new FormData( $(form)[0] );

        data.append("idpago", idpagocambiar);

        $.ajax({
            url: url + link_cambia1,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            method: 'POST',
            type: 'POST',  
            success: function(data){
                if( data.resultado ){
                    $("#myModalcambioTEA").modal( 'toggle' );
                    tabla_transferencias.ajax.reload();
                    tabla_otros.ajax.reload();
                }else{
                    alert("NO SE HA PODIDO COMPLETAR LA SOLICITUD");
                }
            },error: function( ){
                alert("ERROR EN EL SISTEMA");
            }
        });
    }
});










var idpagocambiar2;
var link_cambia12;

$("#form_cambia_OTRO").submit( function(e) {
    e.preventDefault();
}).validate({
    submitHandler: function( form ) {

        var data = new FormData( $(form)[0] );

        data.append("idpago", idpagocambiar2);

        $.ajax({
            url: url + link_cambia12,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            method: 'POST',
            type: 'POST',  
            success: function(data){
                if( data.resultado ){
                    $("#myModalcambiOTRO").modal( 'toggle' );
                    tabla_transferencias.ajax.reload();
                    tabla_otros.ajax.reload();
                }else{
                    alert("NO SE HA PODIDO COMPLETAR LA SOLICITUD");
                }
            },error: function( ){
                alert("ERROR EN EL SISTEMA");
            }
        });
    }
});








var idpagoespera;
var link_espera2;

$("#form_espera_dos").submit( function(e) {
    e.preventDefault();
}).validate({
    submitHandler: function( form ) {

        var data = new FormData( $(form)[0] );

        data.append("idpago", idpagoespera);

        $.ajax({
            url: url + link_espera2,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            method: 'POST',
                type: 'POST', // For jQuery < 1.9
                success: function(data){
                    if( data.resultado ){
                        $("#myModalEspera2").modal( 'toggle' );
                        tabla_otros.ajax.reload();
                    }else{
                        alert("NO SE HA PODIDO COMPLETAR LA SOLICITUD");
                    }
                },error: function( ){
                    alert("ERROR EN EL SISTEMA");
                }
            });
    }
});






var idpagoespera;
var link_espera3;

$("#form_espera_tres").submit( function(e) {
    e.preventDefault();
}).validate({
    submitHandler: function( form ) {

        var data = new FormData( $(form)[0] );

        data.append("idpago", idpagoespera);

        $.ajax({
            url: url + link_espera3,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            method: 'POST',
                type: 'POST', // For jQuery < 1.9
                success: function(data){
                    if( data.resultado ){
                        $("#myModalEspera3").modal( 'toggle' );
                        tabla_caja_chica.ajax.reload();
                    }else{
                        alert("NO SE HA PODIDO COMPLETAR LA SOLICITUD");
                    }
                },error: function( ){
                    alert("ERROR EN EL SISTEMA");
                }
            });
    }
});




var param1;
var param2;
var param3;

var numProv = document.getElementById('idcuentas');
var formaPago = document.getElementById('tipoPago_chica');

var link_post_caja;

$("#infopago_chica_1").submit( function(e) {
    e.preventDefault();
}).validate({
    submitHandler: function( form ) {

        var data = new FormData( $(form)[0] );

        $.ajax({
            url: url + link_post_caja,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            method: 'POST',
                type: 'POST', // For jQuery < 1.9
                success: function(data){
                    if( data.resultado ){
                        $("#modalCaja").modal('toggle' );
                        tabla_caja_chica.ajax.reload();
                    }else{
                        alert("NO SE HA PODIDO COMPLETAR LA SOLICITUD");
                    }
                },error: function( ){
                    alert("ERROR EN EL SISTEMA");
                }
            });
    }
});

var idpagorechazo;
var link_post22;

$("#infosol22").submit( function(e) {
    e.preventDefault();
}).validate({
    submitHandler: function( form ) {

        var data = new FormData( $(form)[0] );

        data.append("idpago", idpagorechazo);

        $.ajax({
            url: url + link_post22,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            method: 'POST',
                type: 'POST', // For jQuery < 1.9
                success: function(data){
                    if( data.resultado ){
                        $("#myModalcomentario3").modal( 'toggle' );
                        tabla_4.ajax.reload();
                    }else{
                        alert("NO SE HA PODIDO COMPLETAR LA SOLICITUD");
                    }
                },error: function( ){
                    alert("ERROR EN EL SISTEMA");
                }
            });
    }
});

var idpagorechazo_ch;
var link_post22_ch;

$("#infosol22_ch").submit( function(e) {
    e.preventDefault();
}).validate({
    submitHandler: function( form ) {

        var data = new FormData($(form)[0]);
        data.append("idpago", idpagorechazo_ch);

        $.ajax({
            url: url + link_post22_ch,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            method: 'POST',
                type: 'POST', // For jQuery < 1.9
                success: function(data){
                    if(data.resultado){
                        $("#myModalcomentario3_ch").modal( 'toggle' );
                        tabla_44.ajax.reload();
                    }else{
                        alert("NO SE HA PODIDO COMPLETAR LA SOLICITUD");
                    }
                },error: function( ){
                    alert("ERROR EN EL SISTEMA");
                }
            });
    }
});








function regresar_da(idsolicitud) {
 idsolrechazo = idsolicitud;
 link_post2 = "Cuentasxp/datos_para_rechazo1/";
 $("#myModalcomentario1 .modal-footer").html("");
 $("#myModalcomentario1 .modal-body").html("");
 $("#myModalcomentario1 ").modal();
 $("#myModalcomentario1 .modal-body").append("<div class='form-group col-lg-12'><input type='text' class='form-control' placeholder='Describe motivo de rechazo.' name='observacion' id='observacion' required></div>");
 $("#myModalcomentario1 .modal-footer").append("<div class='btn-group'><button type='submit' class='btn btn-success'>ACEPTAR</button><button type='button' class='btn btn-danger' onclick='cancelarr2()'>CANCELAR</button></div>");
}




function regresar_dp(idpago) {

   idpagorechazo = idpago;
   link_post22 = "Cuentasxp/datos_para_rechazo2/";
   $("#myModalcomentario3 .modal-footer").html("");
   $("#myModalcomentario3 .modal-body").html("");
   $("#myModalcomentario3 ").modal();

   $("#myModalcomentario3 .modal-body").append("<div class='form-group col-lg-12'><input type='text' class='form-control' placeholder='Describe motivo de rechazo.' name='observacion' id='observacion' required></div>");

   $("#myModalcomentario3 .modal-footer").append("<div class='btn-group'><button type='submit' class='btn btn-success'>ACEPTAR</button><button type='button' class='btn btn-danger' onclick='cancelarr22()'>CANCELAR</button></div>");

}










function regresar_dp_chica(idpago) {

   idpagorechazo_ch = idpago;
   link_post22_ch = "Cuentasxp/datos_para_rechazo2_ch/";
   $("#myModalcomentario3_ch .modal-footer").html("");
   $("#myModalcomentario3_ch .modal-body").html("");
   $("#myModalcomentario3_ch ").modal();

   $("#myModalcomentario3_ch .modal-body").append("<div class='form-group col-lg-12'><input type='text' class='form-control' placeholder='Describe motivo de rechazo.' name='observacion' id='observacion' required></div>");

   $("#myModalcomentario3_ch .modal-footer").append("<div class='btn-group'><button type='submit' class='btn btn-success'>ACEPTAR</button><button type='button' class='btn btn-danger' onclick='cancelarr22_ch()'>CANCELAR</button></div>");

}




function cancela(){
   $("#myModal").modal('toggle');
} 

function cancelarr2(){
// $("#myModal").modal('toggle');
$("#myModalcomentario1").modal('toggle');
}


function cancelarr22(){
// $("#myModal").modal('toggle');
$("#myModalcomentario3").modal('toggle');
}

function cancelarr22_ch(){
// $("#myModal").modal('toggle');
$("#myModalcomentario3_ch").modal('toggle');
}

function cancelamal(){
   $("#myModalx").modal('toggle');
} 



function acepta(idsolicitud) {

   // $.get(url+"Cuentasxp/enviarcpp/"+idsolicitud).done(function () { $("#myModal").modal('toggle'); tabla_provison_pago.ajax.reload();
   $("#myModal .modal-footer").html("");
   $("#myModal .modal-body").html("");
   $("#myModal ").modal();
   $("#myModal .modal-body").append("<label>¿Desea enviar esta solicitud a provisión?</label>");
   $("#myModal .modal-footer").append("<input type='button' class='btn btn-success' value='ENVIAR A PROVISIÓN' onclick='provisionar_solicitud("+idsolicitud+")'><input type='button' class='btn btn-warning' value='SIN PROVISIÓN' onclick='no_provisionar_solicitud("+idsolicitud+")'>");


   // $("#myModal .modal-footer").append("<div class='btn-group'><button class='btn btn-success' onClick=">ENVIAR A PROVISIÓN</button><button type='submit' class='btn btn-warning'>SIN PROVISIÓN</button> </div>");

}


function acepta_sin(idsolicitud) {

   $.get(url+"Cuentasxp/enviarcpp/"+idsolicitud).done(function () { $("#myModal").modal('toggle'); tabla_provison_pago.ajax.reload();
    $("#myModal .modal-footer").html("");
    $("#myModal .modal-body").html("");
    $("#myModal ").modal();
    $("#myModal .modal-body").append("<label>Se ha aceptado la solicitud</label>");
});

}


function provisionar_solicitud(idsolicitud) {
    $.get(url+"Cuentasxp/provisionar_ok/"+idsolicitud).done(function () { $("#myModal").modal('toggle'); tabla_provison_pago.ajax.reload();});
}

function no_provisionar_solicitud(idsolicitud) {
    $.get(url+"Cuentasxp/provisionar_mal/"+idsolicitud).done(function () { $("#myModal").modal('toggle'); tabla_provison_pago.ajax.reload();});
}



 


function atrasda(idsolicitud) {

   $("#myModalx .modal-footer").html("");
   $.get(url+"Cuentasxp/enviar_da/"+idsolicitud).done(function () { $("#myModalx").modal('toggle'); tabla_provison_pago.ajax.reload();
});
}

function declinasol(idsolicitud) {
    $("#myModal .modal-footer").html("");
    $("#myModal .modal-body").html("");
    $("#myModal ").modal();
    $("#myModal .modal-body").append("<label>¿Está seguro de declinar esta solicitud?</label>");
    $("#myModal .modal-footer").append("<input type='button' class='btn btn-warning' value='Enviar cola de pagos' onclick='badsol("+idsolicitud+")'>");
    $("#myModal .modal-footer").append("<input type='button' class='btn btn-danger' value='Cancelar'onclick='cancela()'>");
}





function mandar_espera(idpago) {
 idpagoespera = idpago;
   // link_post2 = "Cuentasxp/datos_para_rechazo1/";
   link_espera1 = "Cuentasxp/enviarcolapagos_una/";
   $("#myModalEspera .modal-footer").html("");
   $("#myModalEspera .modal-body").html("");
   $("#myModalEspera ").modal();
   $("#myModalEspera .modal-body").append("<div class='form-group col-lg-12'><input type='text' class='form-control' placeholder='Describe motivo por el cual se envía a espera.' name='observacion' id='observacion' required></div>");
   $("#myModalEspera .modal-footer").append("<div class='btn-group'><button type='submit' class='btn btn-success'>ACEPTAR</button><button type='button' class='btn btn-danger' onclick='cancela_espera1()'>CANCELAR</button></div>");


}



function mandar_espera_ot(idpago) {
 idpagoespera = idpago;
   // link_post2 = "Cuentasxp/datos_para_rechazo1/";
   link_espera2 = "Cuentasxp/enviarcolapagos_una/";
   $("#myModalEspera2 .modal-footer").html("");
   $("#myModalEspera2 .modal-body").html("");
   $("#myModalEspera2 ").modal();
   $("#myModalEspera2 .modal-body").append("<div class='form-group col-lg-12'><input type='text' class='form-control' placeholder='Describe motivo por el cual se envía a espera.' name='observacion' id='observacion' required></div>");
   $("#myModalEspera2 .modal-footer").append("<div class='btn-group'><button type='submit' class='btn btn-success'>ACEPTAR</button><button type='button' class='btn btn-danger' onclick='cancela_espera1()'>CANCELAR</button></div>");


}





function regresar_espera(pago) {
  // alert("enviar a espera");
  $.get(url+"Cuentasxp/regresarcolapagos_transf/"+pago).done(function () {  
    tabla_transferencias.ajax.reload();
} );}





  function mandar_espera_CH(idpago) {
     idpagoespera = idpago;
     link_espera3 = "Cuentasxp/enviarcolapagos_CHICA/";
     $("#myModalEspera3 .modal-footer").html("");
     $("#myModalEspera3 .modal-body").html("");
     $("#myModalEspera3 ").modal();
     $("#myModalEspera3 .modal-body").append("<div class='form-group col-lg-12'><input type='text' class='form-control' placeholder='Describe motivo por el cual se envía a espera.' name='observacion' id='observacion' required></div>");
     $("#myModalEspera3 .modal-footer").append("<div class='btn-group'><button type='submit' class='btn btn-success'>ACEPTAR</button><button type='button' class='btn btn-danger' onclick='cancela_espera_chica()'>CANCELAR</button></div>");


 }


 function regresar_espera_ch(pago){
     $.get(url+"Cuentasxp/regresarcolapagos_CHICA/"+pago).done(function () {  
        tabla_caja_chica.ajax.reload();
    } );
 }






 function regresar_espera_otros(pago) {
  // alert("enviar a espera");
  $.get(url+"Cuentasxp/regresarcolapagos_transf/"+pago).done(function () {  
    tabla_otros.ajax.reload();
} );}


  function badsol(idsolicitud) {
    $("#myModal .modal-footer").html("");
    $.get(url+"Cuentasxp/enviarcolapagos/"+idsolicitud).done(function () { $("#myModal").modal('toggle'); tabla_provison_pago.ajax.reload();
} );}



    $(document).on("change", "#tipoPago_chica", function(){
       if( $(this).val() == "" || $(this).val() == "ECHQ" ){
        $("select[name='idcuentas']").hide();
        $("label[name='lab2_CH']").hide();
        $("input[name='idcheque']").show();
        $("label[name='lab3_CH']").show();

    } if( $(this).val() == "TEA" ){
       $("select[name='idcuentas']").show();
       $("label[name='lab2_CH']").show();
       $("input[name='idcheque']").hide();
       $("label[name='lab3_CH']").hide();
   }

});




    $(document).on("change", "#formaPago", function(){

     if( $(this).val() == "NULL" ){

        $("label[name='lab3']").hide();
        $("input[name='idcheque_general']").hide();
        $("input[name='fecha_pago']").hide();
        $("label[name='lab2']").hide();
        $("button[name='btn1']").hide();
        $("button[name='btn2']").hide();


    } if( $(this).val() == "TEA" ){
     $("label[name='lab3']").hide();
     $("input[name='idcheque_general']").hide();
     $("input[name='fecha_pago']").show();
     $("label[name='lab2']").show();
     $("button[name='btn1']").hide();
     $("button[name='btn2']").hide();
 }
 if( $(this).val() == "ECHQ" ){
   $("label[name='lab3']").show();
   $("input[name='idcheque_general']").show();
   $("input[name='fecha_pago']").show();
   $("label[name='lab2']").show();
   $("button[name='btn1']").show();
   $("button[name='btn2']").show();
}

if( $(this).val() == "EFEC" ){
  $("label[name='lab3']").hide();
  $("input[name='idcheque_general']").hide();
  $("input[name='fecha_pago']").show();
  $("label[name='lab2']").show();
  $("button[name='btn1']").hide();
  $("button[name='btn2']").hide();
}
});


    function ingresar_consecutivo(idpago) {
       $("#myModalconsecutivo").modal();
       $("#myModalconsecutivo #resban").html("");
       $("#myModalconsecutivo .form-control").val("");

   }




   function ingresar_consecutivo_c_chica(idpago) {
       $("#myModalconsecutivo_chica").modal();
       $("#myModalconsecutivo_chica #resban_chi").html("");
       $("#myModalconsecutivo_chica .form-control").val("");

   }





   function cancela_espera_chica(){
    $("#myModalEspera3").modal('toggle');
} 

function cancelarr(){
    $("#myModalpoliza").modal('toggle');
} 

function cancela_espera1(){
    $("#myModalEspera").modal('toggle');
} 


function cancela_lis(){
    $("#modalCaja").modal('toggle');
} 


function cancelarr_ch(){
    $("#myModalpoliza_ch").modal('toggle');
} 

function cancelarr_cheque(){
    $("#myModalconsecutivo").modal('toggle');
} 

function cancelar_caja_ch(){
    $("#myModalconsecutivo_chica").modal('toggle');
}

function cancela2(){
    $("#myModal").modal('toggle');
} 


function acepta2(idsolicitud) {
    $("#myModal .modal-footer").html("");
    $.get(url+"Cuentasxp/terminaproceso/"+idsolicitud).done(function () { 
        $("#myModal").modal('toggle');
        tabla_4.ajax.reload();
    });
}



function enviar_pag_caja(idpago) {

   $.get(url+"Cuentasxp/enviar_pag_caja/"+idpago).done(function () { $("#myModal_chica_all").modal('toggle'); tabla_caja_chica.ajax.reload();
    $("#myModal_chica_all .modal-footer").html("");
    $("#myModal_chica_all .modal-body").html("");
    $("#myModal_chica_all ").modal();
    $("#myModal_chica_all .modal-body").append("<label>Se ha aceptado la solicitud</label>");
});
}


function regresar(idsolicitud) {
    $("#myModalregresar .modal-footer").html("");
    $("#myModalregresar .modal-body").html("");
    $("#myModalregresar ").modal();
    $("#myModalregresar .modal-body").append("<label>¿Está seguro de regresar esta solicitud?</label>");
    $("#myModalregresar .modal-footer").append("<input type='button' class='btn btn-warning' value='Regresar DP' onclick='badsol("+idsolicitud+")'>");
    $("#myModalregresar .modal-footer").append("<input type='button' class='btn btn-danger' value='Cancelar'onclick='cerrar()'>");
}

function badsol(idsolicitud) {
    $("#myModalregresar .modal-footer").html("");
    $.get(url+"Cuentasxp/regresarcolapagos/"+idsolicitud).done(function () {
        $("#myModalregresar").modal('toggle');
        tabla_4.ajax.reload();
    });
}

function areaImprimir1() {
   var contenido= document.getElementById("areaImprimir").innerHTML;
   var contenidoOriginal= document.body.innerHTML;
   document.body.innerHTML = contenido;
   window.print();
   document.body.innerHTML = contenidoOriginal;
}



function botonArchivo(){



   empr2 = document.getElementById("empresa_valor").value;
   cta2 = document.getElementById("cuenta_valor").value;
   filtro = document.getElementById("depar").value;


   if(filtro!=""){
    filtro2 = document.getElementById("depar").value;
   }else{
     filtro2 = 0;
   }

   // alert(filtro);
   if(empr2==""||cta2==""){
     alert("Seleccione una de la opciones");
 }
 else{

   $.getJSON(url+"ArchivoBanco/genpbanc/"+empr2+"/"+cta2+"/"+filtro2).done(function (data) {

    if( !data.resultado ){
       $('#resban').html('<h5><BR>Descarga archivos (TXT Banco y PDF listado)&nbsp;<button class="btn btn-info btn-xs" onClick="generarPDF('+data['empresa_valor']+","+data['cuenta_valor']+","+data['filtro_valor']+');clickAndDisable(this);" ><i class="fas fa-download"></i></button></h5><hr>Total a pagar: <b>$ '+ formatMoney(data['totpag'])+'</b><hr>');

       $("#descargaPT").attr("href", data['file']);

   }else{
    $('#resban').html(data.mensaje);

}

});
}

}


function btn_caja_chica(){


   empr2_ch = document.getElementById("empresa_valor_ch").value;
   cta2_ch = document.getElementById("cuenta_valor_2ch").value;

   $.getJSON(url+"ArchivoBanco/genpbanc_caja_chica/"+empr2_ch+"/"+cta2_ch).done(function (data) {
  // $.getJSON(url+"ArchivoBanco/genpbanc_caja_chica/"+empr2_ch, {noarch : 25}, function (data) {
   if(!data.resultado){

      $('#resban_caja_chica').html('<h5><BR>Descarga archivos (TXT Banco y PDF listado)&nbsp;<button class="btn btn-info btn-xs" onClick="generarPDF_chica('+data['empresa_valor']+');clickAndDisable(this);" ><i class="fas fa-download"></i></button></h5><hr>Total a pagar: <b>$ '+ formatMoney(data['totpag'])+'</b><hr>');
      $("#descargaPTCH").attr("href", data['file']);




  }else{
    generarPDF_chica_2(empr2_ch);
    $('#resban_caja_chica').html(data.mensaje);

}
});

}






function pdf_otros(){
    empr2 = document.getElementById("empr_otros").value;
    $.getJSON(url+"ArchivoBanco/datos_otros/"+empr2, {noarch : 25}, function (data){
        if( !data.resultado ){
            $('#resban_otros').html('<h5><BR>Descargar archivo PDF listado &nbsp;<a class="btn btn-info btn-xs" target="_blank" onClick="generarPDF_otros('+data['empresa_valor']+');clickAndDisable_otros(this);" download><i class="fas fa-download"></i></a></h5><hr>Total a pagar: <b>$ '+ formatMoney(data['totpag'])+'</b> <hr>');
            tabla_otros.ajax.reload();
        }else{
            $('#resban_otros').html(data.mensaje);

        }
    });
}


function clickAndDisable_otros(link) {
     // disable subsequent clicks
     link.onclick = function(event) {
        event.preventDefault();
        alert("Ya se descargó el archivo, revise sus descargas");
    }
}



function autorizarSeleccionadasCajaChica(){
   var apagar = [];

   $('.selecionadoC').each(function(index,va){

    if($(this).is(":checked")){
        tr = $(this).closest('tr');
        var row = tabla_caja_chica.row( tr );   
        apagar.push({ idsolicitud : row.data().ID , totalpagar : row.data().Cantidad ,idempresa : row.data().Empresa , nomdepto : row.data().Departamento 
            , idresponsable :row.data().IDR });
                // alert(JSON.stringify(apagar));
            }
        });
   if(window.confirm('Se pagará el total autorizado.\nEl total es de $ '+ formatMoney(tota2)+' ¿Estás de acuerdo?')){   
    $.post( url + "Cuentasxp/PagarTotalCajaChica", {jsonApagar : JSON.stringify(apagar)} ).done( function(){
     tota2 = 0;
     $("#totpagarC").html(formatMoney(0));
     tabla_caja_chica.ajax.reload();

 });
}
}





function Cambiar_TEA(idpago) {
 idpagocambiar = idpago;
   // link_post2 = "Cuentasxp/datos_para_rechazo1/";
   link_cambia1 = "Cuentasxp/cambiarmetodo";
   $("#myModalcambioTEA .modal-footer").html("");
   $("#myModalcambioTEA .modal-body").html("");
   $("#myModalcambioTEA ").modal();
   $("#myModalcambioTEA .modal-body").append("<div class='form-group col-lg-12'><select name='nuevo_metodo' id='nuevo_metodo' class='form-control' required><option value=''> -SELECCIONA OPCIÓN -</option><option value='ECHQ'>ECHQ</option><option value='MAN'>MAN</option><option value='EFEC'>EFEC</option></select></div>");
   $("#myModalcambioTEA .modal-footer").append("<div class='btn-group'><button type='submit' class='btn btn-success'>ACEPTAR</button><button type='button' class='btn btn-danger' onclick='cerrartea()'>CANCELAR</button></div>");


}





function Cambiar_OTRO(idpago) {
 idpagocambiar2 = idpago;
   // link_post2 = "Cuentasxp/datos_para_rechazo1/";
   link_cambia12 = "Cuentasxp/cambiarmetodoOTROS";
   $("#myModalcambiOTRO .modal-footer").html("");
   $("#myModalcambiOTRO .modal-body").html("");
   $("#myModalcambiOTRO ").modal();
   $("#myModalcambiOTRO .modal-body").append("<div class='form-group col-lg-12'><select name='nuevo_metodo2' id='nuevo_metodo2' class='form-control' required><option value=''> -SELECCIONA OPCIÓN -</option><option value='TEA'>TEA</option><option value='ECHQ'>ECHQ</option><option value='MAN'>MAN</option><option value='EFEC'>EFEC</option></select></div>");
   $("#myModalcambiOTRO .modal-footer").append("<div class='btn-group'><button type='submit' class='btn btn-success'>ACEPTAR</button><button type='button' class='btn btn-danger' onclick='cerrarOTRO()'>CANCELAR</button></div>");


}









function cerrar(){
    $("#myModalregresar").modal('toggle');
} 


function cerrartea(){
    $("#myModalcambioTEA").modal('toggle');
} 

function cerrarOTRO(){
    $("#myModalcambiOTRO").modal('toggle');
} 

function generarPDF(emp, cta, filtro){
    window.open(url+"Generar_PDFile/documentos_autorizacion/"+emp+"/"+cta+"/"+filtro, "_blank");
    alert("Se han descargado sus documentos con éxito");

    tabla_transferencias.ajax.reload();
    $("a#descargaPT")[0].click();
} 


function generarPDF_chica(data){
    // alert(url);
    window.open(url+"Generar_PDFile3/documentos_autorizacion/"+data, "_blank");
    alert("Se han descargado sus documentos con éxito");

    tabla_caja_chica.ajax.reload();
    $("a#descargaPTCH")[0].click();

} 


function generarPDF_chica_2(data){
    // alert(url);
    window.open(url+"Generar_PDFile3/documentos_autorizacion/"+data, "_blank");
    alert("Se han descargado sus documentos con éxito");

    tabla_caja_chica.ajax.reload();

    // tabla_caja_chica.ajax.reload();

} 

function acepta_cheque(){

   aX710 = document.getElementById("serie_cheque").value;
   aX711 = document.getElementById("numpago").value;
   aX712 = document.getElementById("numctaserie").value;
   $.get(url+"Cuentasxp/update_serie/"+aX710+"/"+aX711+"/"+aX712).done( function( data ){
      $("#myModalconsecutivo").modal('toggle');
      tabla_otros.ajax.reload();
  }); 
} 


function acepta_cheque_chi(){

    res = document.getElementById("tipoPago_chica").value;

    if(res=="TEA"){

        tipo =1;

        ach610 = 0;


        ach610 = $("#idcuenta option:selected").attr("data-value");
        ach611 = document.getElementById("numpago_ch").value;
        ach612 = document.getElementById("idcuenta").value;

    }

    if(res=="ECHQ"){
      tipo =2;
      ach610 = document.getElementById("serie_cheque_ch").value;
      ach611 = document.getElementById("numpago_ch").value;
      ach612 = document.getElementById("numctaserie_ch").value;

  }


  if(ach612==""||ach612==null){
    alert("Ingrese un numero de cuenta, o relacione con un proveedor de tipo interno")
}
else{

  $.get(url+"Cuentasxp/update_serie_ch/"+ach610+"/"+ach611+"/"+ach612+"/"+tipo).done( function( data ){

   $("#myModalconsecutivo_chica").modal('toggle');
   tabla_caja_chica.ajax.reload();

});
}


} 



function generarPDF_otros(data){
    window.open(url+"Generar_PDFile2/documentos_autorizacion/"+data); 
    alert("Se han descargado sus documentos con éxito");

    tabla_otros.ajax.reload(); 

} 

//    function txt_individual($idpago){



//     idp = $idpago;

//     $.getJSON(url+"ArchivoBanco/genpbanc_individual/"+idp, function (data) {

//  if(!data.resultado){
//     $('#myModal3').modal();

//     $('#resbane').html('<h4 style="color:red;">'+data['descarga_valor']+'° DESCARGA PARA ESTE PAGO</h4><h5> Descarga archivo (TXT Banco)&nbsp;<a onclick="clickAndDisable(this);" name="sendName" id="sendId" class="btn btn-info btn-xs" id  href="'+data['file']+'"target="_blank" download><i class="fas fa-download"></i></a></h5><hr>Total: <b>$ '+ formatMoney(data['totpag'])+'</b><hr>');

//     tabla_transferencias.ajax.reload();

// }else{
//     $('#resbane').html(data.mensaje);

// }
// });

// }



function eliminar_pago(idpago) {
//  $("#myModal .modal-footer").html("");
$.get(url+"Cuentasxp/eliminar_pago/"+idpago).done(function () {  
    tabla_transferencias.ajax.reload();
    tabla_otros.ajax.reload();

});
}













var idpago;

$(document).on('click', '.txt_ind', function(e) {

    e.preventDefault();

    idpago = $(this).attr("data-pago");
    var idempresa = $(this).attr("data-empresa");



    $(".lista_cuentas_ind").html("");

    e.preventDefault();
    $('#myModal3').modal();

    $.getJSON( url + "Listas_select/lista_cuentas2/"+idempresa).done( function( data ){
        $(".lista_cuentas_ind").append('<option value = "0">ELIJA CUENTA</option>');
        $.each( data, function( i, v){
            $(".lista_cuentas_ind").append('<option value="'+v.idcuenta+'" data-value="'+v.idcuenta+'">'+v.nombre+" - "+v.nodecta+" - "+v.nombanco+'</option>');
        });
    });

});




$('.lista_cuentas_ind').on('change',function(){

    cuenta = $(this).val();

    $.getJSON(url+"ArchivoBanco/genpbanc_individual/"+idpago+"/"+cuenta , function (data) {

        if(!data.resultado){

            $('#resbane').html('<center><h4 style="color:red;">'+data['descarga_valor']+'° DESCARGA PARA ESTE PAGO</h4><h5></center><center>Descarga archivo (TXT Banco)&nbsp;<a name="sendName" id="sendId" class="btn btn-info btn-xs" id  href="'+data['file']+'"target="_blank" download><i class="fas fa-download"></i></a></h5><hr>Total: <b>$ '+ formatMoney(data['totpag'])+'</b><hr></center>');


        }else{
           $('#resbane').html(data.mensaje);

       }
   });
});






jQuery(document).ready(function(){
   jQuery('#myModal3').on('hidden.bs.modal', function (e) {
       jQuery(this).find('#cuenta_valor_ind').val(0);
       $("#myModal3 #resbane").html("");

   })
})





// function txt_individual($idpago, $idempresa){


//   $(".lista_cuentas_ind").html("");



//     $.getJSON( url + "Listas_select/lista_cuentas2/"+$idempresa).done( function( data ){
//         $(".lista_cuentas_ind").append('<option value = "0">ELIJA CUENTA</option>');
//        $.each( data, function( i, v){
//         $(".lista_cuentas_ind").append('<option value="'+v.idcuenta+'" data-value="'+v.idcuenta+'">'+v.nombre+" - "+v.nodecta+" - "+v.nombanco+'</option>');
//     });
//    });


//   var idp = $idpago;
//   var cuenta = $('#cuenta_valor_ind').val();

// $('#myModal3').modal();

// $('.lista_cuentas_ind').on('change',function(){

//     cuenta = $(this).val();

//     $.getJSON(url+"ArchivoBanco/genpbanc_individual/"+idp+"/"+cuenta , function (data) {

//     if(!data.resultado){

//     $('#resbane').html('<center><h4 style="color:red;">'+data['descarga_valor']+'° DESCARGA PARA ESTE PAGO</h4><h5></center><center>Descarga archivo (TXT Banco)&nbsp;<a name="sendName" id="sendId" class="btn btn-info btn-xs" id  href="'+data['file']+'"target="_blank" download><i class="fas fa-download"></i></a></h5><hr>Total: <b>$ '+ formatMoney(data['totpag'])+'</b><hr></center>');


//        }else{
//          $('#resbane').html(data.mensaje);

//     }
//   });
// });
// }







// function obtener_archivo(idp, variable){


//     $.getJSON(url+"ArchivoBanco/genpbanc_individual/"+idp+"/"+variable , function (data) {

//  if(!data.resultado){
//     $('#myModal3').modal();

//     $('#resbane').html('<center><h4 style="color:red;">'+data['descarga_valor']+'° DESCARGA PARA ESTE PAGO</h4><h5></center><center>Descarga archivo (TXT Banco)&nbsp;<a onclick="clickAndDisable(this);" name="sendName" id="sendId" class="btn btn-info btn-xs" id  href="'+data['file']+'"target="_blank" download><i class="fas fa-download"></i></a></h5><hr>Total: <b>$ '+ formatMoney(data['totpag'])+'</b><hr></center>');

//     tabla_transferencias.ajax.reload();

// }else{
//     $('#resbane').html(data.mensaje);

// }
// });

// }



function clickAndDisable(link) {
    link.onclick = function(event){
        event.preventDefault();
        alert("Ya se descargó el archivo, revise sus descargas");
    }
}

</script>
<?php
require("footer.php");
?>