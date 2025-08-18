<?php
    require("head.php");
    require("menu_navegador.php");
?>  
<div  class="content" ng-controller="Catalogos">
    <div class="container-fluid" >
        <div class="row" >    
            <div class="col" style="background-color : white;">
                <div class="box">
                    <div class="box-header with-border">
                        <ul class="nav nav-tabs">
                            <li class="col-md-3 text-center active"><a data-toggle="tab" href="#sedes"><h4>Sedes</h4></a></li>
                            <li class="col-md-3 text-center"><a data-toggle="tab" href="#medios"><h4>Medios de prospección</h4></a></li>
                            <li class="col-md-3 text-center"><a data-toggle="tab" href="#lugares"><h4>Lugares de prospección</h4></a></li>
                            <li class="col-md-3 text-center"><a data-toggle="tab" href="#plazas"><h4>Plazas de venta</h4></a></li>
                        </ul>
                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade active in" id="sedes" role="tabpanel" aria-labelledby="nav-sedes" > <!-- SEDES -->
                                <div class="row">
                                    <form name="formSedes" ng-submit="GuardarSede(sede); formSedes.$setPristine()" class="col-md-10 col-md-offset-1">
                                        <br>
                                        <div class="row">
                                            <div class="col-md-4 form-group" ng-class="{ 'has-error' : (NombreInvalido && !formSedes.nombres.$pristine) || (formSedes.nombres.$invalid && !formSedes.nombres.$pristine) }">                                                    
                                                <div class="input-group col-md-12">
                                                    <input class="form-control" type="text" name="nombres" ng-model="sede.nombre" placeholder="* Nombre" maxlength="100" minlength="5" ng-change="ValidarSede(sede,1)"/>
                                                    <span data-toggle="tooltip" data-placement="top" title="{{tooltip_tittle}}" class="input-group-addon" ng-show="(NombreInvalido && !formSedes.nombres.$pristine) || (formSedes.nombres.$invalid && !formSedes.nombres.$pristine)" style="color: red"><i class="fas fa-times-circle"></i></span>
                                                    <span class="input-group-addon" ng-show="(!NombreInvalido  && !formSedes.nombres.$invalid && !formSedes.nombres.$pristine)" style="color: green"><i class="fas fa-check-circle"></i></span>
                                                </div>
                                            </div>
                                            <div class="col-md-4 form-group" ng-class="{ 'has-error' : (AbrevInvalido && !formSedes.abrev.$pristine) || (formSedes.abrev.$invalid && !formSedes.abrev.$pristine) }">
                                                <div class="input-group col-md-12">
                                                    <input class="form-control" type="text" name="abrev" ng-model="sede.abrev" placeholder="* Abreviatura" minlength="2"  maxlength="5" required ng-change="ValidarSede(sede,2)"/>  
                                                    <span data-toggle="tooltip" data-placement="top" title="{{tooltip_tittle2}}" class="input-group-addon" ng-show="(AbrevInvalido && !formSedes.abrev.$pristine) || (formSedes.abrev.$invalid && !formSedes.abrev.$pristine)" style="color: red"><i class="fas fa-times-circle"></i></span>
                                                    <span class="input-group-addon" ng-show="(!AbrevInvalido  && !formSedes.abrev.$invalid && !formSedes.abrev.$pristine)" style="color: green"><i class="fas fa-check-circle"></i></span>
                                                </div>                                                                              
                                            </div>
                                            <div class="col-md-4">
                                                <button type="submit" class="btn btn-primary btn-block" ng-disabled="formSedes.$invalid || NombreInvalido || AbrevInvalido" >Agregar</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="row">
                                    <div class="col-md-10 col-md-offset-1" ><br><!-- TABLA-->
                                        <table id="TablaSedes" datatable="ng" class="table table-striped"  dt-instance="dtInstance" dt-options="dtOptions" dt-columns="dtColumns" style="width: 100%;" >
                                            <thead>
                                                <tr>
                                                    <th class="text-center">Id</th>
                                                    <th class="text-center">Nombre</th>
                                                    <th class="text-center">Creador por</th>
                                                    <th class="text-center">Estatus</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            
                                            <tbody>
                                                <tr ng-repeat="item in sedes">
                                                    <td class="text-center">{{item.id_sede}}</td>
                                                    <td>{{item.nombre}}</td>
                                                    <td>{{item.creador}}</td>
                                                    <td class="text-center">
                                                        <label class='label pull-center bg-green' ng-show="{{item.estatus == 1}}">ACTIVO</label>
                                                        <label class='label pull-center bg-red' ng-hide="{{item.estatus == 1}}">INACTIVO</label>
                                                    </td>
                                                    <td>
                                                        <div class="text-center">
                                                            <button type="button" class="btn btn-success" ng-hide="{{item.estatus == 1}}" style="background-color: #90B900; border-color: #90B900; margin:5px;" ng-click="CambiarEstatusSede(item.id_sede,1)"><i class="fas fa-power-off"></i></i></button>
                                                            <button type="button" class="btn btn-success" ng-show="{{item.estatus == 1}}" style="background-color: #E85656; border-color: #E85656; margin:5px;" ng-click="CambiarEstatusSede(item.id_sede,0)"><i class="fas fa-power-off"></i></button>
                                                        </div>
                                                    </td>
                                                </tr>                                
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="medios" role="tabpanel" aria-labelledby="nav-medios" ><!-- MEDIOS DE PROSPECCION -->
                                <div class="row">
                                    <form name="formMedios" ng-submit="Guardar(medio,7); formMedios.$setPristine()" class="col-md-10 col-md-offset-1">
                                        <br>
                                        <div class="row">
                                            <div class="col-md-8 form-group" ng-class="{ 'has-error' : (ItemInvalido && !formMedios.nombrem.$pristine) || (formMedios.nombrem.$invalid && !formMedios.nombrem.$pristine)  }">
                                                <div class="input-group col-md-12">
                                                    <input class="form-control" type="text" name="nombrem" ng-model="medio.nombre" placeholder="* Nombre" minlength="5" maxlength="100" ng-change="Validar(medio,7)" required/>
                                                    <span data-toggle="tooltip" data-placement="top" title="{{tooltip_tittle3}}" class="input-group-addon" ng-show="(ItemInvalido && !formMedios.nombrem.$pristine) || (formMedios.nombrem.$invalid && !formMedios.nombrem.$pristine)" style="color: red"><i class="fas fa-times-circle"></i></span>
                                                    <span class="input-group-addon" ng-show="(!ItemInvalido  && !formMedios.nombrem.$invalid && !formMedios.nombrem.$pristine)" style="color: green"><i class="fas fa-check-circle"></i></span>
                                                </div>                                                
                                            </div>
                                            <div class="col-md-4">
                                                <button type="submit" class="btn btn-primary btn-block" ng-disabled="formMedios.$invalid || ItemInvalido">Agregar</button>
                                            </div>
                                        </div>
                                    </form> 
                                </div>
                                <div class="row">
                                    <div class="col-md-10 col-md-offset-1" ><br><!-- TABLA-->
                                        <table id="TablaMedios" datatable="ng" class="table table-striped"  dt-instance="dtInstance" dt-options="dtOptions" dt-columns="dtColumns" style="width: 100%;" >
                                            <thead>
                                                <tr>
                                                    <th class="text-center">Id</th>
                                                    <th class="text-center">Nombre</th>
                                                    <th class="text-center">Creador por</th>
                                                    <th class="text-center">Estatus</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            
                                            <tbody>
                                                <tr ng-repeat="item in catalogos.Medios">
                                                    <td class="text-center">{{item.id_opcion}}</td>
                                                    <td>{{item.nombre}}</td>
                                                    <td>{{item.creador}}</td>
                                                    <td class="text-center">
                                                        <label class='label pull-center bg-green' ng-show="{{item.estatus == 1}}">ACTIVO</label>
                                                        <label class='label pull-center bg-red' ng-hide="{{item.estatus == 1}}">INACTIVO</label>
                                                    </td>
                                                    <td>
                                                        <div class="text-center">
                                                            <button type="button" class="btn btn-success" ng-hide="{{item.estatus == 1}}" style="background-color: #90B900; border-color: #90B900; margin:5px" ng-click="CambiarEstatus(item.id_opcion,1,7)"><i class="fas fa-power-off"></i></i></button>
                                                            <button type="button" class="btn btn-success" ng-show="{{item.estatus == 1}}" style="background-color: #E85656; border-color: #E85656; margin:5px;" ng-click="CambiarEstatus(item.id_opcion,0,7)"><i class="fas fa-power-off"></i></i></button>
                                                        </div>
                                                    </td>
                                                </tr>                                
                                            </tbody>
                                        </table>
                                    </div>
                                </div>                  
                            </div>
                            <div class="tab-pane fade" id="lugares" role="tabpanel" aria-labelledby="nav-lugares" ><!-- LUGARES DE PROSPECCION -->
                                <div class="row">
                                    <form name="formLugares" ng-submit="Guardar(lugar,9); formLugares.$setPristine()" class="col-md-10 col-md-offset-1">
                                        <br>
                                        <div class="row">
                                            <div class="col-md-8 form-group" ng-class="{ 'has-error' : (ItemInvalido && !formLugares.nombrel.$pristine) || (formLugares.nombrel.$invalid && !formLugares.nombrel.$pristine) }">
                                                <div class="input-group col-md-12">
                                                    <input class="form-control" type="text" name="nombrel" ng-model="lugar.nombre" placeholder="* Nombre" minlength="5" maxlength="100" required ng-change="Validar(lugar,9)" required/>
                                                    <span data-toggle="tooltip" data-placement="top" title="{{tooltip_tittle3}}" class="input-group-addon" ng-show="(ItemInvalido && !formLugares.nombrel.$pristine) || (formLugares.nombrel.$invalid && !formLugares.nombrel.$pristine)" style="color: red"><i class="fas fa-times-circle"></i></span>
                                                    <span class="input-group-addon" ng-show="(!ItemInvalido  && !formLugares.nombrel.$invalid && !formLugares.nombrel.$pristine)" style="color: green"><i class="fas fa-check-circle"></i></span>
                                                </div>                                          
                                            </div>
                                            <div class="col-md-4">
                                                <button type="submit" class="btn btn-primary btn-block" ng-disabled="formLugares.$invalid || ItemInvalido">Agregar</button>
                                            </div>
                                        </div>
                                    </form> 
                                </div>
                                <div class="row">
                                    <div class="col-md-10 col-md-offset-1" ><br><!-- TABLA-->
                                        <table id="TablaLugares" datatable="ng" class="table table-striped"  dt-instance="dtInstance" dt-options="dtOptions" dt-columns="dtColumns" style="width: 100%;" >
                                            <thead>
                                                <tr>
                                                    <th class="text-center">Id</th>
                                                    <th class="text-center">Nombre</th>
                                                    <th class="text-center">Creador por</th>
                                                    <th class="text-center">Estatus</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            
                                            <tbody>
                                                <tr ng-repeat="item in catalogos.Lugares">
                                                    <td class="text-center">{{item.id_opcion}}</td>
                                                    <td>{{item.nombre}}</td>
                                                    <td>{{item.creador}}</td>
                                                    <td class="text-center">
                                                        <label class='label pull-center bg-green' ng-show="{{item.estatus == 1}}">ACTIVO</label>
                                                        <label class='label pull-center bg-red' ng-hide="{{item.estatus == 1}}">INACTIVO</label>
                                                    </td>
                                                    <td>
                                                        <div class="text-center">
                                                            <button type="button" class="btn btn-success" ng-hide="{{item.estatus == 1}}" style="background-color: #90B900; border-color: #90B900; margin:5px" ng-click="CambiarEstatus(item.id_opcion,1,9)"><i class="fas fa-power-off"></i></i></button>
                                                            <button type="button" class="btn btn-success" ng-show="{{item.estatus == 1}}" style="background-color: #E85656; border-color: #E85656; margin:5px;" ng-click="CambiarEstatus(item.id_opcion,0,9)"><i class="fas fa-power-off"></i></i></button>
                                                        </div>
                                                    </td>
                                                </tr>                                
                                            </tbody>
                                        </table>
                                    </div>
                                </div>                  
                            </div>
                            <div class="tab-pane fade" id="plazas" role="tabpanel" aria-labelledby="nav-plazas" ><!-- PLAZAS DE VENTA -->
                                <div class="row">
                                    <form name="formPlaza" ng-submit="Guardar(plaza,5); formPlaza.$setPristine()" class="col-md-10 col-md-offset-1">
                                        <br>
                                        <div class="row">
                                            <div class="col-md-8 form-group" ng-class="{ 'has-error' : (ItemInvalido && !formPlaza.nombrep.$pristine) || (formPlaza.nombrep.$invalid && !formPlaza.nombrep.$pristine) }">
                                                <div class="input-group col-md-12">
                                                    <input class="form-control" type="text" name="nombrep" ng-model="plaza.nombre" placeholder="* Nombre" minlength="5" maxlength="100" required ng-change="Validar(plaza,5)" required/>
                                                    <span data-toggle="tooltip" data-placement="top" title="{{tooltip_tittle3}}" class="input-group-addon" ng-show="(ItemInvalido && !formPlaza.nombrep.$pristine) || (formPlaza.nombrep.$invalid && !formPlaza.nombrep.$pristine)" style="color: red"><i class="fas fa-times-circle"></i></span>
                                                    <span class="input-group-addon" ng-show="(!ItemInvalido  && !formPlaza.nombrep.$invalid && !formPlaza.nombrep.$pristine)" style="color: green"><i class="fas fa-check-circle"></i></span>
                                                </div> 
                                            </div>
                                            <div class="col-md-4">
                                                <button type="submit" class="btn btn-primary btn-block" ng-disabled="formPlaza.$invalid || ItemInvalido">Agregar</button>
                                            </div>
                                        </div>
                                    </form> 
                                </div>
                                <div class="row">
                                    <div class="col-md-10 col-md-offset-1" ><br><!-- TABLA-->
                                        <table id="TablaPlazas" datatable="ng" class="table table-striped"  dt-instance="dtInstance" dt-options="dtOptions" dt-columns="dtColumns" style="width: 100%;" >
                                            <thead>
                                                <tr>
                                                    <th class="text-center">Id</th>
                                                    <th class="text-center">Nombre</th>
                                                    <th class="text-center">Creador por</th>
                                                    <th class="text-center">Estatus</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            
                                            <tbody>
                                                <tr ng-repeat="item in catalogos.Plazas">
                                                    <td class="text-center">{{item.id_opcion}}</td>
                                                    <td>{{item.nombre}}</td>
                                                    <td>{{item.creador}}</td>
                                                    <td class="text-center">
                                                        <label class='label pull-center bg-green' ng-show="{{item.estatus == 1}}">ACTIVO</label>
                                                        <label class='label pull-center bg-red' ng-hide="{{item.estatus == 1}}">INACTIVO</label>
                                                    </td>
                                                    <td>
                                                        <div class="text-center">
                                                            <button type="button" class="btn btn-success" ng-hide="{{item.estatus == 1}}" style="background-color: #90B900; border-color: #90B900; margin:5px" ng-click="CambiarEstatus(item.id_opcion,1,5)"><i class="fas fa-power-off"></i></i></button>
                                                            <button type="button" class="btn btn-success" ng-show="{{item.estatus == 1}}" style="background-color: #E85656; border-color: #E85656; margin:5px;" ng-click="CambiarEstatus(item.id_opcion,0,5)"><i class="fas fa-power-off"></i></i></button>
                                                        </div>
                                                    </td>
                                                </tr>                                
                                            </tbody>
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

    <div class="modal fade" id="Modal_exito" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header" style="background-color: #398439;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" style="color:white;">Éxito</h4>
            </div>
            <div class="modal-body">
                <h5 style="color: #4D4F5C;" class="text-center">{{exito_msj}}<h5>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal">Aceptar</button>
            </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="Modal_fail" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header" style="background-color: #E85656;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" style="color:white;">Error</h4>
            </div>
            <div class="modal-body">
                <h5 style="color: #4D4F5C;" class="text-center">{{fail_msj}}<h5>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success"  style="background-color: #E85656; border-color: #E85656;" data-dismiss="modal">Aceptar</button>
            </div>
            </div>
        </div>
    </div>

</div>

<script type="text/javascript" src="<?= base_url("js/Global.js")?>"></script>
<script type="text/javascript" src="<?= base_url("js/Controllers/Catalogos.js")?>"></script>
<?php
    require("footer.php");
?>