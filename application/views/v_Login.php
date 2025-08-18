<?php
    require("head.php");
?>  
<div class="container" style="padding:0; width: 100%;" ng-controller="Login">
    <div class="col-lg-6" style="padding:0;">
        <!--<img src="<?= base_url("img/img_1.jpg")?>" class="img-responsive" alt="Responsive image">-->
        <div style="background-image:url('<?= base_url("img/img_2.jpg")?>'); background-position: center center; background-repeat: no-repeat; background-size:cover; height: 100vh; width: 100%;"></div>
    </div>
    <div class="col-lg-4  col-xs-offset-1 container ">
        <div class="login-box-body" style="    margin: 25% 0 0 0;" >
            <h1 style="color: #AEA76E; font-family: 'Trajan Bold';" class="text-center"><b>CIUDAD MADERAS</b></h1>
                <br>
                <div class="col-md-12" ng-hide="mostrar">
                    <form id="formulario_login" action="<?= site_url("Login/Verificar")?>" method="post" >
                        <h5 style="color: #4D4F5C;" class="text-center">¡Bienvenido! Por favor ingresa a tu cuenta<h5>
                        <br>
                        <br>
                        <?= $this->session->flashdata('error_usuario') ?>
                        <br>
                        <div style="white-space:nowrap" class="has-float-label">
                            <input class="form-control" type="text" id="login_usuario" name="login_usuario" placeholder="Nombre de usuario" required/>
                            <label style="left:.75rem">Nombre de usuario</label>
                        </div>
                        <br>
                        <br>
                        <div style="white-space:nowrap" class="has-float-label">
                            <input class="form-control" type="password" id="login_password" name="login_password" placeholder="Contraseña" required/>
                            <label style="left:.75rem">Contraseña</label>
                        </div>
                        <br>
                        <br>
                        <div class="row">
                            <div class="col-xs-6  col-xs-offset-3">
                                <button type="submit" class="btn btn-primary btn-block">Iniciar sesión</button>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-xs-6  col-xs-offset-3">
                                <button type="button" class="btn btn-link btn-block btn-sm" ng-click="mostrar = !mostrar">Se te olvidó tu contraseña</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-12" ng-show="mostrar" >
                    <form id="recuperar"ng-submit="Recuperar()"  >
                        <!--h5 style="color: #4D4F5C;" class="text-center">Ingresa tu correo electrónico y te enviaremos un enlace para restablecer tu contraseña<h5-->
                        <h5 style="color: #4D4F5C;" class="text-center">Ingresa tu correo electrónico y te enviaremos una contraseña provisional, la cual deberás cambiar inmediatamente al ingresar.<h5>
                        <br>
                        <br>
                        <br>
                        <div class="alert alert-danger text-danger" role="alert" ng-show="error"><strong>¡ERROR!</strong> {{msj}} </div>
                        <div class="alert alert-success text-success" role="alert" ng-show="exito"><strong>¡ÉXITO!</strong> {{msj}} </div>
                        <div style="white-space:nowrap" class="has-float-label">
                            <input class="form-control" type="text" id="correo_login" ng-model="usuario" placeholder="Correo electrónico o usuario" required/>
                            <label style="left:.75rem">Correo electrónico o usuario</label>
                        </div>
                        <br>
                        <br>
                        <div class="row">
                            <div class="col-xs-6 col-xs-offset-3">
                                <button type="submit" class="btn btn-primary btn-block">Enviar</button>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-xs-6 col-xs-offset-3">
                                <button type="button" class="btn btn-link btn-block" ng-click="mostrar = !mostrar">Regresar</button>
                            </div>
                        </div>
                    </form>
                </div>
        </div>
    </div>
</div>
<script>
var app = angular.module("CRM",[]);

app.controller("Login", function($scope,$http){
    $scope.mostrar = false;
    $scope.error = false;
    $scope.exito = false;
    $scope.Recuperar = function(){
        $http({url: url + 'Login/Recuperar', method: "POST", data: JSON.stringify($scope.usuario) })
            .then(function(response) {
                if(response.data.resultado){
                    $scope.msj = "Se te ha enviado una contraseña provisional a: " + response.data.data.correo;
                    $scope.exito = true;
                    $scope.error = false;
                    $scope.usuario = "";
                }else{
                    $scope.msj = "El usuario no existe.";
                    $scope.error = true;
                    $scope.exito = false;
                }
            }, 
            function(response) { // optional
                    $scope.msj = "Falla con el servidor.";
            });
    }
});
</script>
</body>