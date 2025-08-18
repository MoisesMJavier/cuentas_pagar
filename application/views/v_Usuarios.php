<?php
    require("head.php");
    require("menu_navegador.php");
?>  
<div class="content" ng-controller="RegistroUsuarios">
    <div class="container-fluid" >
        <div class="row" >    
            <div class="col" style="background-color : white;">
                <div class="box">
                    <div class="box-header with-border">
                        <ul class="nav nav-tabs">
                            <li class="col-md-6 text-center active"><a data-toggle="tab" href="#registro"><h4>Alta</h4></a></li>
                            <li class="col-md-6 text-center"><a data-toggle="tab" href="#listado"><h4>Ver todos</h4></a></li>
                        </ul>
                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade active in" id="registro" role="tabpanel" aria-labelledby="nav-registro" >
                                <form name="formUsuario" ng-submit="GuardarUsuario(user); formUsuario.$setPristine()" class="col-md-10 col-md-offset-1" novalidate >
                                    <br>
                                    <div class="row">
                                        <div class="col-md-4 col-md-offset-4" style="text-align: center;">
                                            <img ng-src="{{user.src}}" alt="..." class="img-circle img-responsive center-block" style="height:175px; width: 175px;">
                                            <span class="btn close btn-file badge" style="background-color: transparent;position: absolute;left: 65%;bottom: 10%;">
                                                <i class="fas fa-pen" ></i> <input type="file" accept=".jpg, .jpeg, .png"  onchange="angular.element(this).scope().imageUpload(event)">
                                            </span>
                                        </div>
                                    </div>
                                    <br>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-6 form-group has-float-label" show-errors>
                                            <input class="form-control" type="text" name="nombre" ng-model="user.nombre" placeholder="Nombre(s)" ng-pattern="patternNombre" minlength="2" maxlength="45" required/>
                                            <label>* Nombre(s)</label>
                                        </div>
                                        <div class="col-md-3 form-group has-float-label" show-errors>
                                            <input class="form-control" type="text" name="apellidop" ng-model="user.apellidop" placeholder="Apellido paterno" ng-pattern="patternNombre" minlength="2" maxlength="45" required/>
                                            <label>* Apellido paterno</label>
                                        </div>
                                        <div class="col-md-3 form-group has-float-label" show-errors>
                                            <input class="form-control" type="text" name="apellidom" ng-model="user.apellidom" placeholder="Apellido materno" ng-pattern="patternNombre" minlength="2" maxlength="45"/>
                                            <label>Apellido materno</label>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-6 form-group has-float-label" show-errors>
                                            <input class="form-control" type="email" name="correo" ng-pattern="patternCorreo" ng-model="user.correo" placeholder="Correo electrónico" minlength="10" maxlength="150" required/>
                                            <label>* Correo electrónico</label>
                                        </div>
                                        <div class="col-md-3 form-group has-float-label" show-errors>
                                            <input class="form-control" type="text" name="telefono" ng-model="user.telefono" placeholder="Teléfono" minlength="7" maxlength="15" oninput="this.value=this.value.replace(/[^0-9]/g,'');" required/>
                                            <label>* Teléfono</label>
                                        </div>
                                        <div class="col-md-3 form-group has-float-label" show-errors>
                                            <select ng-model="user.hijos" name="hijos" class="form-control" ng-options="item.label for item in data.SN" required>
                                            <option disabled value="">Seleccione una opción</option>
                                            </select>
                                            <label >* ¿Tiene hijos?</label>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-3 form-group has-float-label" show-errors>
                                            <select ng-model="user.sede" name="sede" id="sede" class="form-control" ng-change="Cambio(user)" ng-options="item.label for item in data.Sedes" required>
                                            <option disabled value="">Seleccione una sede</option>
                                            </select>
                                            <label>* Sede</label>
                                        </div>
                                        <div class="col-md-3 form-group has-float-label" show-errors>
                                            <select ng-model="user.tipo" name="tipo" class="form-control" ng-change="Cambio(user)" ng-options="item.label for item in data.Roles" required>
                                            <option disabled value="">Seleccione un miembro</option>
                                            </select>
                                            <label>* Tipo de miembro</label>
                                        </div>
                                        <div class="col-md-6 form-group has-float-label" show-errors>
                                            <select ng-model="user.gerente" name="gerente" class="form-control" ng-disabled="disabled"  ng-required="!disabled" ng-options="item.label for item in data.Gerentes" >
                                            <option disabled value="">Seleccione un Líder</option>
                                            </select>
                                            <label>{{disabled? '' : '* '}}Líder</label>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-3 form-group" show-errors ng-class="{ 'has-error' : (UsuarioInvalido && !formUsuario.usuario.$pristine) }">
                                            <div class="input-group"  style="width: 100%;">
                                                <input class="form-control" type="text" name="usuario" ng-model="user.usuario" placeholder="* Nombre de usuario" ng-pattern="patternUsuario" minlength="6" maxlength="20" required autocomplete="off"  ng-change="VerficarUsuario(user)"/>
                                                <span data-toggle="tooltip" data-placement="top" title="{{tooltip_tittle}}" class="input-group-addon" ng-show="(UsuarioInvalido && !formUsuario.usuario.$pristine) || (formUsuario.usuario.$invalid && !formUsuario.usuario.$pristine)" style="color: red"><i class="fas fa-times-circle"></i></span>
                                                <span class="input-group-addon" ng-show="(!UsuarioInvalido  && !formUsuario.usuario.$invalid && !formUsuario.usuario.$pristine)" style="color: green"><i class="fas fa-check-circle"></i></span>
                                            </div>
                                            <!--label>* Nombre de usuario</label-->
                                        </div>
                                        <div class="col-md-3 form-group" show-errors>
                                            <input class="form-control" type="password" name="contrasena" ng-model="user.contrasena" placeholder="* Contraseña" minlength="6" maxlength="10" required autocomplete="off"/>        
                                            <!--label>* Contraseña</label-->
                                        </div>
                                        <div class="col-md-3 form-group" show-errors>
                                            <div class="input-group" style="width: 100%;">
                                                <input class="form-control" type="password" name="contrasena2" ng-model="user.contrasena2" placeholder="* Confirme la contraseña" minlength="6" maxlength="10" required data-password-verify="user.contrasena"/>
                                                <span class="input-group-addon" ng-show="formUsuario.contrasena2.$error.passwordVerify" style="color: red"><i class="fas fa-times-circle"></i></span>
                                                <span class="input-group-addon" ng-show="!formUsuario.contrasena2.$error.passwordVerify && !formUsuario.contrasena2.$pristine " style="color: green"><i class="fas fa-check-circle"></i></span>
                                            </div>
                                            <!--label>* Confirme la contraseña</label-->
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-3 col-md-offset-9">
                                        <button type="submit" class="btn btn-primary btn-block">Guardar</button>
                                        </div>
                                    </div>
                                    <br>
                                    <br>
                                </form>
                            </div>
                            <div class="tab-pane fade" id="listado" role="tabpanel" aria-labelledby="nav-listado" >
                                <div class="col-md-12">
                                    <div >
                                        <div ng-switch on="selected">
                                            <button ng-switch-when='true' ng-click='button1()' style="background-color: transparent; border-color:transparent;"><img style="height: 30px;" src="<?= base_url("img/off.png")?>"> Ver en lista</button>
                                            <button ng-switch-when='false' ng-click='button1()' style="background-color: transparent; border-color:transparent;"><img style="height: 30px;" src="<?= base_url("img/on.png")?>"> Ver en lista</button>
                                        </div>
                                    </div>
                                    <div class="col-md-12" ng-show="!selected"  ><br><!-- TABLA-->
                                        <table id="TablaUsuarios" datatable="ng" class="table table-striped"  dt-instance="dtInstance" dt-options="dtOptions" dt-columns="dtColumns" style="width: 100%;" >
                                            <thead>
                                                <tr>
                                                    <th class="text-center">Id</th>
                                                    <th class="text-center">Nombre</th>
                                                    <th class="text-center">Puesto</th>
                                                    <th class="text-center">Teléfono</th>
                                                    <th class="text-center">Estatus</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            
                                            <tbody>
                                                <tr ng-repeat="usuario in usuarios">
                                                    <td class="text-center">{{usuario.id_usuario}}</td>
                                                    <td>{{usuario.nombre}}</td>
                                                    <td>{{usuario.rol}}</td>
                                                    <td>{{usuario.telefono}}</td>
                                                    <td class="text-center">
                                                        <label class='label pull-center bg-green' ng-show="{{usuario.estatus == 1}}">ACTIVO</label>
                                                        <label class='label pull-center bg-red' ng-hide="{{usuario.estatus == 1}}">INACTIVO</label>
                                                    </td>
                                                    <td>
                                                        <div class="text-center">
                                                            <button type="button" class="btn btn-success"  style="background-color: #90B900; border-color: #90B900; margin:5px" ng-click="Editar(usuario.id_usuario)"><i class="fas fa-pen"></i></button>
                                                            <button type="button" class="btn btn-success"  style="background-color: #E85656; border-color: #E85656; margin:5px;" ng-click="Borrar(usuario.id_usuario)"><i class="fas fa-times"></i></button>
                                                        </div>
                                                    </td>
                                                </tr>                                
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-md-12" ng-show="selected" > <!-- BLOCK -->
                                        <div class="row">
                                            <div class="col-md-6"  style="margin: 10px 0;">
                                                <input type="text" class="form-control" ng-pagination-search="usuarios" placeholder="Buscar...">
                                            </div>
                                        </div>
                                        <div class="col-md-6" style="margin: 10px 0;"  ng-pagination="usuario in usuarios" ng-pagination-size="10">
                                            <div class="col-md-12" style="border-radius: 5px; border: 2px solid #F1F1F3;">
                                                <div class="col-md-4 text-center" style="display: table-row;">
                                                    <div class="col-sm-12" style="margin-top: auto;margin-bottom: auto;">
                                                        <img src="{{usuario.src}}" alt="..." class="img-circle img-fluid" style="height:80px; width: 80px; margin-top: auto;margin-bottom: auto;">
                                                    </div>
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="row">
                                                        <button type="button" class="btn close" style="margin:5px" ng-click="Borrar(usuario.id_usuario)"><i class="fas fa-times"></i></button>
                                                        <button type="button" class="btn close" style="margin:5px" ng-click="Editar(usuario.id_usuario)"><i class="fas fa-pen"></i></button>
                                                        <h5><b>{{usuario.nombre}}</b></h5>
                                                    </div>
                                                    <div class="row">
                                                        <h6 style="color: #4D4F5C; margin: 0;" >{{usuario.rol}}</h6>
                                                    </div>
                                                    <div class="row">
                                                        <h6 style="color: #4D4F5C; margin: 0;" >Tel: {{usuario.telefono}}</h6>
                                                    </div>
                                                    <div class="row" style="margin-bottom: 10px;">
                                                        <label class='label pull-center bg-green' ng-show="{{usuario.estatus == 1}}">ACTIVO</label>
                                                        <label class='label pull-center bg-red' ng-hide="{{usuario.estatus == 1}}">INACTIVO</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                        <div class="col-md-12 text-center">
                                            <ng-pagination-control pagination-id="usuarios"></ng-pagination-control>
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
                                                <form name="formUsuarioE" ng-submit="GuardarUsuario(usere)" class="col-md-12" novalidate>
                                                    <div class="row">
                                                        <div class="col-md-4 col-md-offset-4" style="text-align: center;">
                                                            <img ng-src="{{usere.src}}" alt="..." class="img-circle img-responsive center-block" style="height:180px; width:180px;">
                                                            <span class="btn close btn-file badge" style="background-color: transparent;position: absolute;left: 70%;bottom: 10%;">
                                                                <i class="fas fa-pen" ></i> <input type="file" accept=".jpg, .jpeg, .png"  onchange="angular.element(this).scope().imageUpload(event)">
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <br>
                                                    <div class="row">
                                                        <div class="col-md-6 form-group has-float-label" show-errors>
                                                            <input class="form-control" type="text" name="nombre" ng-model="usere.nombre" placeholder="Nombre(s)" ng-pattern="patternNombre" minlength="2" maxlength="45" required/>
                                                            <label>* Nombre(s)</label>
                                                        </div>
                                                        <div class="col-md-3 form-group has-float-label" show-errors>
                                                            <input class="form-control" type="text" name="apellidop" ng-model="usere.apellidop" placeholder="Apellido paterno" ng-pattern="patternNombre" minlength="2" maxlength="45" required/>
                                                            <label>* Apellido paterno</label>
                                                        </div>
                                                        <div class="col-md-3 form-group has-float-label" show-errors>
                                                            <input class="form-control" type="text" name="apellidom" ng-model="usere.apellidom" placeholder="Apellido materno" ng-pattern="patternNombre" minlength="2" maxlength="45"/>
                                                            <label>Apellido materno</label>
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <div class="row">
                                                        <div class="col-md-6 form-group has-float-label" show-errors>
                                                            <input class="form-control" type="email" name="correo" ng-pattern="patternCorreo" ng-model="usere.correo" placeholder="Correo electrónico" minlength="10" maxlength="150" required readonly/>
                                                            <label>* Correo electrónico</label>
                                                        </div>
                                                        <div class="col-md-3 form-group has-float-label" show-errors>
                                                            <input class="form-control has-float-label" type="text" name="telefono" ng-model="usere.telefono" placeholder="Teléfono" minlength="7" maxlength="15" oninput="this.value=this.value.replace(/[^0-9]/g,'');" required/>
                                                            <label>* Teléfono</label>
                                                        </div>
                                                        <div class="col-md-3 form-group has-float-label" show-errors>
                                                            <select ng-model="usere.hijos" name="hijos" class="form-control" ng-options="item.label for item in data.SN" required>
                                                            <option disabled value="">Seleccione una opción</option>
                                                            </select>
                                                            <label >* ¿Tiene hijos?</label>
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <div class="row">
                                                        <div class="col-md-3 form-group has-float-label" show-errors>
                                                            <select ng-model="usere.sede" name="sede" class="form-control" ng-change="Cambio(user)" ng-options="item.label for item in data.Sedes" required>
                                                            <option disabled value="">* Seleccione una sede</option>
                                                            </select>
                                                            <label >* Sede</label>
                                                        </div>
                                                        <div class="col-md-3 form-group has-float-label" show-errors>
                                                            <select ng-model="usere.tipo" name="tipo" class="form-control" ng-change="Cambio(user)" ng-options="item.label for item in data.Roles" required>
                                                            <option disabled value="">* Seleccione un miembro</option>
                                                            </select>
                                                            <label >* Tipo de miembro</label>
                                                        </div>
                                                        <div class="col-md-6 form-group has-float-label" show-errors>
                                                            <select ng-model="usere.gerente" name="gerente" class="form-control" ng-disabled="disabled"  ng-required="!disabled" ng-options="item.label for item in data.Gerentes" >
                                                            <option disabled value="">* Seleccione un Líder</option>
                                                            </select>
                                                            <label >* Líder</label>
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <div class="row">
                                                        <div class="col-md-3 form-group" show-errors>
                                                            <div class="input-group"  style="width: 100%;">
                                                                <input class="form-control" type="text" name="usuario" ng-model="usere.usuario" placeholder="* Nombre de usuario" ng-pattern="patternUsuario" minlength="6" maxlength="20" required autocomplete="off"  ng-change="VerficarUsuario(usere)" readonly/>
                                                                <span data-toggle="tooltip" data-placement="top" title="{{tooltip_tittle}}" class="input-group-addon" ng-show="(UsuarioInvalido && !formUsuarioE.usuario.$pristine) || (formUsuarioE.usuario.$invalid && !formUsuarioE.usuario.$pristine)" style="color: red"><i class="fas fa-times-circle"></i></span>
                                                                <span class="input-group-addon" ng-show="(!UsuarioInvalido )" style="color: green"><i class="fas fa-check-circle"></i></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 form-group" show-errors>
                                                            <input class="form-control" type="password" name="contrasena" ng-model="usere.contrasena" placeholder="* Contraseña" minlength="6" maxlength="10" required autocomplete="off"/>
                                                        </div>
                                                        <div class="col-md-3 form-group" show-errors>
                                                            <div class="input-group" style="width:100%;">
                                                                <input class="form-control" type="password" name="contrasena2" ng-model="usere.contrasena2" placeholder="* Confirme la contraseña" minlength="6" maxlength="10" required data-password-verify="usere.contrasena"/>
                                                                <span class="input-group-addon" ng-show="formUsuarioE.contrasena2.$error.passwordVerify" style="color: red"><i class="fas fa-times-circle"></i></span>
                                                                <span class="input-group-addon" ng-show="!formUsuarioE.contrasena2.$error.passwordVerify" style="color: green"><i class="fas fa-check-circle"></i></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 form-group  has-float-label" show-errors>
                                                            <select name="estado" ng-model="usere.estado" class="form-control" ng-options="item.label for item in data.Estatus" required>
                                                            <option disabled value="">Seleccione un estatus</option>
                                                            </select>
                                                            <label >* Estatus</label>
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
        </div>
    </div>

    <div class="modal fade" id="Modal_exito" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
                <button type="button" class="btn btn-success"  data-dismiss="modal">Aceptar</button>
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

    <div class="modal fade" id="Modal_eliminar" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header" style="background-color: #E85656;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" style="color:white;">Elimina registro</h4>
            </div>
            <div class="modal-body">
                <h5 style="color: #4D4F5C;" class="text-center">¿Estás seguro que desea eliminar este usuario?<h5>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success"  style="background-color: #E85656; border-color: #E85656;" data-dismiss="modal" ng-click="Aceptar()">Aceptar</button>
                <button type="button" class="btn btn-success"  style="background-color: #808495; border-color: #808495;" data-dismiss="modal">Cancelar</button>
            </div>
            </div>
        </div>
    </div>

</div>

<script type="text/javascript" src="<?= base_url("js/Global.js")?>"></script>
<script type="text/javascript" src="<?= base_url("js/Controllers/Usuarios.js")?>"></script>
<?php
    require("footer.php");
?>