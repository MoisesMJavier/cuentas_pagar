<?php
    require("head.php");
    require("menu_navegador.php");
?>  
<div  class="content" ng-controller="CuentaUsuario">
    <div class="container-fluid" >
        <div class="row" >    
            <div class="col" style="background-color : white;">
                <div class="box">
                    <div class="box-header with-border">
                        <form name="formUsuario" ng-submit="GuardarUsuario(user)" class="col-md-10 col-md-offset-1">
                            <div class="col-md-5">                                
                            <h4 style="color: #4D4F5C;">Información de la cuenta</h4><br>
                                <div class="row" style="display: flex; align-items: center; min-height: 60vh;">
                                    <div class="col-md-8 col-md-offset-2" style="text-align: center;">
                                        <div class="text-center" style="margin-right: auto;" >
                                            <img ng-src="{{user.src}}" alt="..." class="img-circle img-responsive center-block" style="height:180px; width: 180px;">
                                            <span class="btn close btn-file badge" style="background-color: transparent;position: absolute;left: 65%;bottom: 39%;">
                                                <i class="fas fa-pen" ></i> <input type="file" accept=".jpg, .jpeg, .png"  onchange="angular.element(this).scope().imageUpload(event)">
                                            </span>
                                        </div>
                                        <h4><b>{{user.perfil}}</b></h4>
                                        <h6>{{user.nombre}} {{user.apellidop}} {{user.apellidom}}</h6>
                                        <h6>{{user.correo}}</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-7" ><br>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group" ng-class="{ 'has-error' : formUsuario.nombre.$invalid}">
                                            <label for="nombre">Nombre(s):</label>
                                            <input class="form-control" type="text" id="nombre" name="nombre" ng-model="user.nombre" placeholder="Nombre(s)" ng-pattern="patternNombre" minlength="2" maxlength="45" required/>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group" ng-class="{ 'has-error' : formUsuario.apellidop.$invalid}">
                                            <label for="apellidop">Apellido paterno:</label>
                                            <input class="form-control" type="text" id="apellidop" name="apellidop" ng-model="user.apellidop" placeholder="Apellido paterno" ng-pattern="patternNombre" minlength="2" maxlength="45" required/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group" ng-class="{ 'has-error' : formUsuario.apellidom.$invalid}">
                                            <label for="apellidom">Apellido materno:</label>
                                            <input class="form-control" type="text" id="apellidom" name="apellidom" ng-model="user.apellidom" placeholder="Apellido materno" ng-pattern="patternNombre" minlength="2" maxlength="45" />
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group" ng-class="{ 'has-error' : formUsuario.usuario.$invalid}">
                                            <label for="usuario">Nombre de usuario:</label>
                                            <input class="form-control" type="text" id="usuario" name="usuario" ng-model="user.usuario" placeholder="Nombre de usuario" ng-pattern="patternUsuario" minlength="6" maxlength="20" required readonly/>
                                        </div>
                                    </div>
                                </div>                      
                                <br>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group" ng-class="{ 'has-error' : formUsuario.correo.$invalid}">
                                            <label for="correo">Correo electrónico</label>
                                            <input class="form-control" type="email" id="correo" name="correo" ng-model="user.correo" placeholder="Correo electrónico" ng-pattern="patternCorreo" minlength="10" maxlength="150"  required readonly/>
                                        </div>
                                    </div>
                                </div>                        
                                <br>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group" ng-class="{ 'has-error' : formUsuario.tel.$invalid}">
                                            <label for="tel">Teléfono</label>
                                            <input class="form-control" type="text" id="tel" name="tel" ng-model="user.telefono" placeholder="Teléfono" minlength="7" maxlength="15" oninput="this.value=this.value.replace(/[^0-9]/g,'');" required/>
                                        </div>
                                    </div>
                                </div>                       
                                <br>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group" ng-class="{ 'has-error' : formUsuario.contrasena.$invalid }">
                                            <label for="contrasena">Contraseña</label>
                                            <input class="form-control" type="password" name="contrasena" id="contrasena" ng-model="user.contrasena" placeholder="Contraseña" minlength="6" maxlength="10" required/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group" ng-class="{ 'has-error' : formUsuario.contrasena2.$invalid }">
                                            <label for="contrasena2">Confirme su contraseña</label>
                                            <div class="input-group col-md-12">
                                                <input class="form-control" type="password" name="contrasena2" id="contrasena2" ng-model="user.contrasena2" placeholder="Confirme su contraseña" minlength="6" maxlength="10" required data-password-verify="user.contrasena"/>
                                                <span class="input-group-addon" ng-show="formUsuario.contrasena2.$error.passwordVerify" style="color: red"><i class="fas fa-times-circle"></i></span>
                                                <span class="input-group-addon" ng-show="!formUsuario.contrasena2.$error.passwordVerify && !formUsuario.contrasena2.$pristine " style="color: green"><i class="fas fa-check-circle"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-4 col-md-offset-8">
                                    <button type="submit" class="btn btn-primary btn-block" ng-disabled="formUsuario.$invalid 11">Guardar</button>
                                    </div>
                                </div>
                                <br>
                                <br>
                            </div>
                        </form>
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
<script type="text/javascript" src="<?= base_url("js/Controllers/Cuenta.js")?>"></script>
<?php
    require("footer.php");
?>