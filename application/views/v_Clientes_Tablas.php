<?php
    require("head.php");
    require("menu_navegador.php");
?>  
<div class="content" ng-controller="RegistroClientes">
    <div class="container-fluid" >
        <div class="row" >    
            <div class="col" style="background-color : white;">
                <div class="box">
                    <div class="box-header with-border">
                        <ul class="nav nav-tabs">
                            <li class="col-md-6 text-center active"><a data-toggle="tab" href="#listado"><h4>Ver prospectos</h4></a></li>
                            <li class="col-md-6 text-center"><a data-toggle="tab" href="#listado2"><h4>Ver clientes</h4></a></li>
                        </ul>
                        <div class="tab-content" id="nav-tabContent">                            
                            <div class="tab-pane fade  active in" id="listado" role="tabpanel" aria-labelledby="nav-listado" ><!-- TABLAS PROSPECTOS-->
                                <div class="col-md-12">
                                    <div > <!-- SWITCH CUADROS - TABLA -->
                                        <div ng-switch on="selected">
                                            <button ng-switch-when='true' ng-click='button1()' style="background-color: transparent; border-color:transparent;"><img style="height: 30px;" src="<?= base_url("img/off.png")?>"> Ver en lista</button>
                                            <button ng-switch-when='false' ng-click='button1()' style="background-color: transparent; border-color:transparent;"><img style="height: 30px;" src="<?= base_url("img/on.png")?>"> Ver en lista</button>
                                        </div>
                                    </div>
                                    <div class="col-md-12" ng-show="!selected"  ><br><!-- TABLA-->
                                        <table id="TablaProspectos" datatable="ng" class="table table-striped"  dt-instance="dtInstance" dt-options="dtOptions" dt-columns="dtColumns" dt-column-defs="dtColumnDefs" style="width: 100%;" >
                                            <thead>
                                                <tr>
                                                    <th class="text-center"> 
                                                        ID
                                                    </th>
                                                    <th class="text-center">
                                                        <input class="form-control" type="text" ng-model="search.cliente" placeholder="Nombre"/> 
                                                    </th>
                                                    <th class="text-center">
                                                        <input class="form-control" type="text" ng-model="search.asesor" placeholder="Asesor"/> 
                                                    </th>
                                                    <th class="text-center">
                                                        <input class="form-control" type="text" ng-model="search.telefono" placeholder="Teléfono"/> </th>
                                                    <th class="text-center">Gerente</th>
                                                    <th class="text-center">Estatus</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            
                                            <tbody>
                                                <tr ng-repeat="usuario in tablas.prospectos | filter:search">
                                                    <td class="text-center">{{usuario.id_cliente}}</td>
                                                    <td>{{usuario.cliente}}</td>
                                                    <td>{{usuario.telefono}}</td>
                                                    <td>{{usuario.asesor}}</td>
                                                    <td>{{usuario.gerente == null ? 'NA': usuario.gerente}}</td>
                                                    <td class="text-center">
                                                        <label class="label pull-center {{usuario.estatus == 1 ? 'bg-green' : 'bg-red'}}">{{usuario.estatus == 1 ? 'ACTIVO' : 'INACTIVO'}}</label>
                                                    </td>
                                                    <td>
                                                        <div class="text-center">
                                                            <button type="button" data-toggle="tooltip" data-placement="top" title="Editar" class="btn btn-success btn-cuadrado"  style="background-color: #e77600; border-color: #e77600;" ng-click="Editar(usuario.id_cliente)" tooltip><i class="fas fa-pen icon-center"></i></button>
                                                            <button type="button" data-toggle="tooltip" data-placement="top" title="Agregar comentario" class="btn btn-success btn-cuadrado"  style="background-color: #90B900; border-color: #90B900;" ng-click="Comentar(usuario.id_cliente)" tooltip><i class="fas fa-list-alt icon-center"></i></button>
                                                            <button type="button" data-toggle="tooltip" data-placement="top" title="Ver" class="btn btn-info btn-cuadrado" ng-click="Ver(usuario.id_cliente)" tooltip><i class="fas fa-eye icon-center"></i></button>
                                                        </div>
                                                    </td>
                                                </tr>                                
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-md-12" ng-show="selected" > <!-- BLOCK -->
                                        <div class="row">
                                            <div class="col-md-6"  style="margin: 10px 0;">
                                                <input type="text" class="form-control" ng-pagination-search="tablas.prospectos" placeholder="Buscar en prospectos...">
                                            </div>
                                        </div>
                                        <div class="row" >
                                            <div class="col-md-4" style="margin: 10px 0;"  ng-pagination="usuario in tablas.prospectos" ng-pagination-size="12" >
                                                <div class="col-md-12 box-usuario">                                    
                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <button type="button" data-toggle="tooltip" data-placement="top" title="Ver" class="btn close" style="margin:5px" ng-click="Ver(usuario.id_cliente)" tooltip><i class="far fa-eye"></i></i></button>
                                                            <button type="button" data-toggle="tooltip" data-placement="top" title="Agregar comentario" class="btn close" style="margin:5px" ng-click="Comentar(usuario.id_cliente)" tooltip><i class="far fa-list-alt"></i></button>
                                                            <button type="button" data-toggle="tooltip" data-placement="top" title="Editar" class="btn close" style="margin:5px" ng-click="Editar(usuario.id_cliente)" tooltip><i class="fas fa-pen"></i></button>
                                                            <h5><b>{{usuario.cliente}}</b></h5>
                                                        </div>
                                                        <div class="row">
                                                            <h6 style="color: #4D4F5C; margin: 0;" >Tel: {{usuario.telefono}}</h6>
                                                        </div>
                                                        <div class="row">
                                                            <h6 style="color: #4D4F5C; margin: 0;" >Asesor: {{usuario.asesor}}</h6>
                                                        </div>
                                                        <div class="row">
                                                            <h6 style="color: #4D4F5C; margin: 0;" >{{usuario.gerente == null ? 'NA': usuario.gerente}}</h6>
                                                        </div>
                                                        <div class="row" style="margin-bottom: 10px;">
                                                            <label class="label pull-center {{usuario.estatus == 1 ? 'bg-green' : 'bg-red'}}">{{usuario.estatus == 1 ? 'ACTIVO' : 'INACTIVO'}}</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                        <div class="col-md-12 text-center">
                                            <ng-pagination-control pagination-id="tablas.prospectos"></ng-pagination-control>
                                        </div>
                                        </div>
                                    </div>
                                </div>                    
                            </div>
                            <div class="tab-pane fade" id="listado2" role="tabpanel" aria-labelledby="nav-listado2" ><!-- TABLAS CLIENTES-->
                                <div class="col-md-12">
                                    <div > <!-- SWITCH CUADROS - TABLA -->
                                        <div ng-switch on="selected">
                                            <button ng-switch-when='true' ng-click='button1()' style="background-color: transparent; border-color:transparent;"><img style="height: 30px;" src="<?= base_url("img/off.png")?>"> Ver en lista</button>
                                            <button ng-switch-when='false' ng-click='button1()' style="background-color: transparent; border-color:transparent;"><img style="height: 30px;" src="<?= base_url("img/on.png")?>"> Ver en lista</button>
                                        </div>
                                    </div>
                                    <div class="col-md-12" ng-show="!selected"  ><br><!-- TABLA-->
                                        <table id="TablaClientes"  datatable="ng" class="table table-striped"  dt-instance="dtInstance" dt-options="dtOptions" dt-columns="dtColumns" dt-column-defs="dtColumnDefs"  style="width: 100%;" >
                                            <thead>
                                                <tr>
                                                    <th class="text-center"> 
                                                        ID
                                                    </th>
                                                    <th class="text-center">
                                                        <input class="form-control" type="text" ng-model="search.cliente" placeholder="Nombre"/> 
                                                    </th>
                                                    <th class="text-center">
                                                        <input class="form-control" type="text" ng-model="search.asesor" placeholder="Asesor"/> 
                                                    </th>
                                                    <th class="text-center">
                                                        <input class="form-control" type="text" ng-model="search.telefono" placeholder="Teléfono"/> </th>
                                                    <th class="text-center">Gerente</th>
                                                    <th class="text-center">Estatus</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            
                                            <tbody>
                                                <tr ng-repeat="usuario in tablas.clientes | filter:search">
                                                    <td class="text-center">{{usuario.id_cliente}}</td>
                                                    <td>{{usuario.cliente}}</td>
                                                    <td>{{usuario.telefono}}</td>
                                                    <td>{{usuario.asesor}}</td>
                                                    <td>{{usuario.gerente == null ? 'NA': usuario.gerente}}</td>
                                                    <td class="text-center">
                                                        <label class="label pull-center {{usuario.estatus == 1 ? 'bg-green' : 'bg-red'}}">{{usuario.estatus == 1 ? 'ACTIVO' : 'INACTIVO'}}</label>
                                                    </td>
                                                    <td>
                                                        <div class="text-center">
                                                            <button type="button" data-toggle="tooltip" data-placement="top" title="Editar" class="btn btn-success btn-cuadrado"  style="background-color: #e77600; border-color: #e77600;" ng-click="Editar(usuario.id_cliente)" tooltip><i class="fas fa-pen icon-center"></i></button>
                                                            <button type="button" data-toggle="tooltip" data-placement="top" title="Agregar comentario" class="btn btn-success btn-cuadrado"  style="background-color: #90B900; border-color: #90B900;;" ng-click="Comentar(usuario.id_cliente)" tooltip><i class="fas fa-list-alt icon-center"></i></button>
                                                            <button type="button" data-toggle="tooltip" data-placement="top" title="Ver" class="btn btn-info btn-cuadrado" ng-click="Ver(usuario.id_cliente)" tooltip><i class="fas fa-eye icon-center"></i></button>
                                                        </div>
                                                    </td>
                                                </tr>                                
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-md-12" ng-show="selected" > <!-- BLOCK -->
                                        <div class="row">
                                            <div class="col-md-6"  style="margin: 10px 0;">
                                                <input type="text" class="form-control" ng-pagination-search="tablas.clientes" placeholder="Buscar en clientes...">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4" style="margin: 10px 0;"  ng-pagination="usuario in tablas.clientes" ng-pagination-size="12">
                                                <div class="col-md-12 box-usuario">                                    
                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <button type="button" data-toggle="tooltip" data-placement="top" title="Ver" class="btn close" style="margin:5px" ng-click="Ver(usuario.id_cliente)" tooltip><i class="far fa-eye"></i></i></button>
                                                            <button type="button" data-toggle="tooltip" data-placement="top" title="Agregar comentario" class="btn close" style="margin:5px" ng-click="Comentar(usuario.id_cliente)" tooltip><i class="far fa-list-alt"></i></button>
                                                            <button type="button" data-toggle="tooltip" data-placement="top" title="Editar" class="btn close" style="margin:5px" ng-click="Editar(usuario.id_cliente)" tooltip><i class="fas fa-pen"></i></button>
                                                            <h5><b>{{usuario.cliente}}</b></h5>
                                                        </div>
                                                        <div class="row">
                                                            <h6 style="color: #4D4F5C; margin: 0;" >Tel: {{usuario.telefono}}</h6>
                                                        </div>
                                                        <div class="row">
                                                            <h6 style="color: #4D4F5C; margin: 0;" >Asesor: {{usuario.asesor}}</h6>
                                                        </div>
                                                        <div class="row">
                                                            <h6 style="color: #4D4F5C; margin: 0;" >{{usuario.gerente == null ? 'NA': usuario.gerente}}</h6>
                                                        </div>
                                                        <div class="row" style="margin-bottom: 10px;">
                                                            <label class="label pull-center {{usuario.estatus == 1 ? 'bg-green' : 'bg-red'}}">{{usuario.estatus == 1 ? 'ACTIVO' : 'INACTIVO'}}</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 text-center">
                                                <ng-pagination-control pagination-id="tablas.clientes"></ng-pagination-control>
                                            </div>
                                        </div>
                                    </div>
                                </div>                    
                            </div>
                        </div>
                        <div class="modal fade" id="Modal_edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">Editar información</h4>
                                    </div>
                                    <div class="modal-body">
                                        <form name="formUsuarioE" ng-submit="GuardarUsuario(usere,false)" class="col-md-10 col-md-offset-1" novalidate>
                                            <br>
                                            <br>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-control">
                                                        <div class="col-md-6">Folio:</div>
                                                        <div class="col-md-6" style="text-align: right;"><b>{{usere.clave}}</b></div>
                                                    </div>                                                            
                                                </div>
                                            </div>
                                            <br>
                                            <br>
                                            
                                            <div class="row">
                                                <div class="col-md-3 form-group has-float-label" show-errors>
                                                    <select ng-model="usere.nacionalidad" name="nacionalidad" class="form-control" ng-options="item.label for item in data.Nacionalidades" required>
                                                        <option disabled value="">Seleccione una nacionalidad</option>
                                                    </select>
                                                    <label>* Nacionalidad</label>
                                                </div>
                                                <div class="col-md-3 form-group has-float-label" show-errors>
                                                    <select ng-model="usere.juridica" name="juridica" class="form-control" ng-options="item.label for item in data.Personalidades" ng-change="usere.curp='';usere.apellidop='';usere.apellidom=''" required>
                                                        <option disabled value="">Seleccione una personalidad jurídica</option>
                                                    </select>              
                                                    <label>* Personalidad jurídica</label>                  
                                                </div>
                                                <div class="col-md-3 form-group has-float-label" show-errors>
                                                    <input class="form-control text-uppercase" type="text" name="curp" placeholder="CURP" ng-model="usere.curp" minlength="18" maxlength="18" ng-pattern="patternCurp" ng-readonly="usere.juridica.value == 1"/>                                                        
                                                    <label>CURP</label>
                                                </div>
                                                <div class="col-md-3 form-group has-float-label" show-errors>
                                                    <input class="form-control text-uppercase" type="text" name="rfc" ng-model="usere.rfc" placeholder="RFC" minlength="{{usere.juridica.value == 1 ? 12: 13}}" maxlength="{{usere.juridica.value == 1 ? 12: 13}}"  ng-pattern="patternCurp"/>
                                                    <label>RFC</label>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row">
                                                <div class="col-md-6 form-group has-float-label" show-errors>
                                                    <input class="form-control" type="text" name="nombre" ng-model="usere.nombre" placeholder="{{usere.juridica.value == 1 ? '* Razón social': '* Nombre(s)'}}" ng-pattern="usere.juridica.value == 1 ? patternRazon : patternNombre" minlength="2" maxlength="100" required data-z-validate/>
                                                    <label>{{usere.juridica.value == 1 ? '* Razón social': '* Nombre(s)'}}</label>
                                                </div>
                                                <div class="col-md-3 form-group has-float-label" show-errors>
                                                    <input class="form-control" type="text" name="apellidop" ng-model="usere.apellidop" placeholder="{{usere.juridica.value == 1 ? 'Apellido paterno': '* Apellido paterno'}}"  ng-pattern="patternNombre" minlength="2" maxlength="45" ng-readonly="usere.juridica.value == 1"  ng-required="usere.juridica.value != 1"/>
                                                    <label>{{usere.juridica.value == 1 ? 'Apellido paterno': '* Apellido paterno'}}</label>
                                                </div>
                                                <div class="col-md-3 form-group has-float-label" show-errors>
                                                    <input class="form-control" type="text" name="apellidom" ng-model="usere.apellidom" placeholder="Apellido materno" ng-readonly="usere.juridica.value == 1"  ng-pattern="patternNombre" minlength="2" maxlength="45"/>
                                                    <label>Apellido materno</label>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row">
                                                <div class="col-md-6 form-group has-float-label" show-errors>
                                                    <input class="form-control" type="email" name="correo" ng-pattern="patternCorreo" ng-model="usere.correo" placeholder="Correo electrónico" minlength="10" maxlength="150" required/>
                                                    <label>* Correo electrónico</label>
                                                </div>
                                                <div class="col-md-3 form-group has-float-label" show-errors>
                                                    <input class="form-control" type="text" name="telefono" ng-model="usere.telefono" placeholder="Teléfono"  minlength="7" maxlength="15" oninput="this.value=this.value.replace(/[^0-9]/g,'');" required/>
                                                    <label>* Teléfono</label>
                                                </div>
                                                <div class="col-md-3 form-group has-float-label" show-errors>
                                                    <input class="form-control" type="text" name="telefono2" ng-model="usere.telefono2" placeholder="Teléfono (otro)"  minlength="7" maxlength="15" oninput="this.value=this.value.replace(/[^0-9]/g,'');"/>
                                                    <label>Teléfono (otro)</label>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row">
                                                <div class="col-md-4 form-group has-float-label" show-errors>
                                                    <select ng-model="usere.lugar" name="lugar" class="form-control" ng-options="item.label for item in data.Lugares" required>
                                                    <option disabled value="">Seleccione un lugar prospección</option>
                                                    </select>
                                                    <label>* Lugar prospección</label>
                                                </div>
                                                <div class="col-md-4 form-group has-float-label" show-errors>
                                                    <select ng-model="usere.medio" name="medio" class="form-control" ng-options="item.label for item in data.Medios" required>
                                                    <option disabled value="">Seleccione un método de prospección</option>
                                                    </select>
                                                    <label>* Método de prospección</label>
                                                </div>
                                                <div class="col-md-4 form-group has-float-label" show-errors>
                                                    <select ng-model="usere.territorio" name="territorio" class="form-control" ng-options="item.label for item in data.Territorios" required>
                                                    <option disabled value="">Seleccione una plaza de venta</option>
                                                    </select>
                                                    <label>* Plaza de venta</label>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row">
                                                <div class="col-md-3 col-md-offset-9">
                                                <button type="submit" class="btn btn-primary btn-block" >Guardar</button>
                                                </div>
                                            </div>
                                            <br>
                                            <br>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="Modal_exito" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #398439;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" style="color:white;">{{exito_titulo}}</h4>
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

    <div class="modal fade" id="Modal_fail" tabindex="-1" role="dialog" aria-hidden="true">
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

    <div class="modal fade" id="Modal_fail2" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header" style="background-color: #E85656;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" style="color:white;">¿Está seguro de que desea continuar?</h4>
            </div>
            <div class="modal-body">
                <h5 style="color: #4D4F5C;" class="text-center">{{fail_msj}}<h5>
                <div class="col-md-12"ng-repeat="fail in usuariosfail" >
                    <h5 style="color: #4D4F5C;">Nombre: <b>{{fail.cliente}}</b> <h5>
                    <h5 style="color: #4D4F5C;">Contacto: {{fail.telefono}} -- {{fail.correo}} <h5>
                    <h5 style="color: #4D4F5C;">RFC: {{fail.rfc}} -- CURP: {{fail.curp}} <h5>
                    <h5 style="color: #4D4F5C;">Agregado por: {{fail.asesor}} -- {{fail.fecha_creacion}}<h5>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success"  style="background-color: #E85656; border-color: #E85656;" data-dismiss="modal" ng-click="GuardarUsuario(user,true)">Aceptar</button>
                <button type="button" class="btn btn-success"  style="background-color: #808495; border-color: #808495;" data-dismiss="modal">Cancelar</button>
            </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="Modal_eliminar" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header" style="background-color: #E85656;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" style="color:white;">Advertencia</h4>
            </div>
            <div class="modal-body">
                <h5 style="color: #4D4F5C;" class="text-center">¿Estás seguro que desea eliminar este cliente?<h5>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success"  style="background-color: #E85656; border-color: #E85656;" data-dismiss="modal" ng-click="Aceptar()">Aceptar</button>
                <button type="button" class="btn btn-success"  style="background-color: #808495; border-color: #808495;" data-dismiss="modal">Cancelar</button>
            </div>
            </div>
        </div>
    </div>

    <div class="modal modal-alertas fade" id="Modal_Comentario" role="dialog" >
        <div class="modal-dialog modal-dialog-centered" >
            <div class="modal-content">
                <div class="modal-header" style="background-color: #3c8dbc;">
                    <h4 class="modal-title" style="color:white;">Agregar un comentario</h4>
                </div>
                <div class="modal-body">
                    <form name="formComentario" action="<?= site_url("Clientes/Comentar")?>" method="post" target="hiddenFrame">
                        <div class="row">
                            <div class="col-lg-12 form-group">
                                <textarea type="password" class="form-control" id="observacion" name="observacion" placeholder="Ingrese un nuevo comentario..." required></textarea>
                                <input type="hidden" name="id" value="{{id}}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-offset-9"><br>
                                <button type="submit" class="btn btn-primary" ng-disabled="formComentario.$invalid" onclick="$('#Modal_Comentario').modal('hide');">GUARDAR</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <iframe name="hiddenFrame" width="0" height="0" border="0" style="display: none;"></iframe>
    
    <div class="modal fade" id="Modal_cliente" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" ng-bind-html="modal | trust">
            </div>
        </div>
    </div>

</div>


<script type="text/javascript" src="<?= base_url("js/Global.js")?>"></script>
<script type="text/javascript" src="<?= base_url("js/Controllers/Clientes.js")?>"></script>
<?php
    require("footer.php");
?>