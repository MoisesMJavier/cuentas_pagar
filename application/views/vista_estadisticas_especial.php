<?php
    require("head.php");
    require("menu_navegador.php");
?>


<div class="content" ng-controller="datos">
    <div class="container-fluid">
        <div class="row">
            <div class="col" style="background-color : white;">
                <div class="box">
                    <div class="box-header with-border">
                        <form id="formUsuario" name="formUsuario" ng-submit="ObtenerReporte(datos)" novalidate>
                            <div class="row">

                                <div class="col-md-2 form-group">
                                    <label>Etapa</label>
                                    <select name="etapaSelect" class="form-control"  id="etapaSelect" ng-change="changeetapa()" ng-model="datos.etapa" ng-options="item.value as item.label for item in etapas track by item.value" required>
                                        <option value="">Seleccione una etapa</option>
                                    </select>
                                        <span ng-show="etapaInvalido" style="color:red" >Por favor, seleccione una etapa</span>
                                </div>

                                <div class="col-md-2 form-group">
                                    <label>Gerente</label>
                                        <select name="managerSelect" ng-change="Cambio(datos)" class="form-control" id="managerSelect" ng-model="datos.gerente" ng-options="item2.id_managers as item2.nombre_managers for item2 in gerentes" required>
                                            <option value="">Seleccione un gerente</option>
                                        </select>
                                        <span ng-show="gerenteInvalido" style="color:red">Por favor, seleccione un gerente</span>
                                </div>

                                <div class="col-md-2 form-group">  
                                    <label>Asesor</label>
                                    <select name="asesoresSelect" class="form-control" id="asesoresSelect"  ng-change="changeasesor()" ng-model="datos.asesor" ng-options="item1.id_asesores as item1.nombre_asesores for item1 in asesores" required>
                                    <option value="">Seleccione un asesor</option>
                                    </select>
                                    <span ng-show="asesorInvalido" style="color:red">Por favor, seleccione un asesor</span>
                                </div>

                                <div class="col-md-2 form-group ">
                                <label>Fecha inicio</label>
                                    <input type="date" class="form-control" name="fecha_inicio" id="fecha_inicio"  ng-change="changefecha1()" ng-model="datos.fecha1" required>
                                    <span ng-show="fecha2Invalido" style="color:red">Por favor, introduzca una fecha</span>
                                </div>

                                <div class="col-md-2 form-group">
                                <label>Fecha fin</label>
                                    <input type="date" class="form-control" name="fecha_final" id="fecha_final"  ng-change="changefecha2()" ng-model="datos.fecha2" required>
                                    <span ng-show="fechaInvalido" style="color:red">Por favor, introduzca una fecha</span>
                                </div>

                                <div class="col-md-2 form-group">
                                <br/>
                                    <button type="submit"  title="Generar Gráfica" class="btn btn-primary btn-block">Generar Gráfica</button>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-10">
                                    <div ng-app="myChart" style="width:100%;">
                                        <canvas id="bar" 
                                            class="chart chart-line" 
                                            chart-data="data"
                                            chart-labels="labels"
                                            chart-options="options"
                                            colours="colors">
                                        </canvas>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <!--<table  class = "table table-bordered" >
                                            <thead>
                                                <tr>
                                                    <th>Mes</th>
                                                    <th>Ventas</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr ng-repeat="user in users">
                                                    <td>{{user.mes}}</td>
                                                    <td>{{user.clientes}}</td>
                                                </tr>
                                            </tbody>
                                        </table>-->
                                            <table style="border:1px solid black;border-collapse:collapse;" >
                                                <tr>
                                                    <th style="border:1px solid black; text-align:center; width:70px;">Mes</th>
                                                    <th style="border:1px solid black; text-align:center;">Ventas</th>
                                                </tr>
                                                <tr ng-repeat="user in users">
                                                    <td style="border:1px solid black; text-align:center;">{{user.mes}}</td>
                                                    <td style="border:1px solid black; text-align:center; width:70px;">{{user.clientes}}</td>
                                                </tr>
                                            </table>
                                </div>
                            </div>
                            <div class="row">

                            <div class="col-md-3">
                                    <button type="button" title="Descargar Gráfica" class="btn btn-primary btn-block" ng-click="Download(datos)">Descargar Gráfica</button>
                                </div>

                                <div class="col-md-3">
                                    <button type="button" title="Exportar Listado" class="btn btn-success btn-block"  ng-click="exportData1(datos)">Exportar Listado</button>
                                </div>
                                
                                <div class="col-md-3">
                                    <button type="button" title="Exportar Listado General" class="btn btn-success btn-block"  ng-click="exportData(datos)">Exportar Listado General</button>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>                
        </div>
    </div>
</div>


    <script>
    var myApp = angular.module('CRM', ["chart.js"]);
    var grafica;
    var ttipo = 'Clientes '; var ta = '2019'; var ass = '';
    var texto = ttipo + ass + ta;

        myApp.controller('datos',
            function ($scope, $http) {
                // REQUEST OPTIONS USING GET METHOD.
                var request = {
                    method: 'get',
                    url: 'Estadisticas/get_total_director',
                    dataType: 'json',
                    contentType: "application/json"
                };
                
                $scope.etapas = [{
                    value: 0,
                    label: 'Prospectos'
                }, {
                    value: 1,
                    label: 'Clientes'
                }];

                $scope.options = {
                    scales: {
                        yAxes: [{id: 'y-axis-1', type: 'linear', position: 'left', ticks: {min: 0}}]
                    },
            
                    animation : {
                        onComplete : function(datooos){    
                            grafica = datooos.chartInstance.toBase64Image();
                            //console.log(grafica);
                        }
                    },

                    title: {
                        display: true,
                        text: texto,
                        fontSize: 26
                    }
                };

                var opts = {sheetid : ' Reporte',
                            headers:true, 
                            column: {style:{Font:{Bold:"1",Color: "#3C3741"}}},
                            rows: {1:{style:{Font:{Color:"#FF0077"}}}},
                            cells: {1:{1:{
                                style: {Font:{Color:"#00FFFF"}}
                            }}}

            
                };

                // MAKE REQUEST USING $http SERVICE.
                $http(request)
                    .then(function (jsonData) {
                        $scope.CrearGrafica(jsonData.data);
                    })
                    .catch(function (Object) {
                        alert(Object.data);
                    });



                // NOW, ADD COLOURS TO THE BARS.
                $scope.colors = [{
                    fillColor: 'rgba(30, 169, 224, 0.8)',
                    strokeColor: 'rgba(30, 169, 224, 0.8)',
                    highlightFill: 'rgba(30, 169, 224,, 0.8)',
                    highlightStroke: 'rgba(30, 169, 224, 0.8)'
                }];

                /*$http({
                    method: 'get',
                    url: 'Estadisticas/get_asesores'
                    }).then(function successCallback(response) {
                    // Store response data
                    $scope.asesores = response.data;
                });*/

                $http({
                    method: 'get',
                    url: 'Estadisticas/get_managers'
                    }).then(function successCallback(response) {
                    // Store response data
                    $scope.gerentes = response.data;
                });

                $scope.Cambio = function(datos){
                    var request1 = { };
                    if(datos.gerente){

                            request1 = {
                            method: 'POST',
                            url: url + 'Estadisticas/get_asesores_gerentes',
                            data: JSON.stringify({gerente: datos.gerente})
                            };

                            $http(request1)
                            .then(function successCallback(response) {
                                $scope.asesores = response.data;
                            })
                            .catch(function (Object) {
                                alert(Object.data);
                            });
                        
                        
                    }
                    $scope.datos.fecha1 = null;
                    $scope.datos.fecha2 = null;
                    $scope.datos.asesor = null;
                    $scope.gerenteInvalido = $scope.formUsuario.managerSelect.$invalid;
                };

                $scope.changeetapa = function(){
                $scope.datos.fecha1 = null;
                $scope.datos.fecha2 = null;
                $scope.datos.asesor = null;
                $scope.datos.gerente = null;
                $scope.etapaInvalido = $scope.formUsuario.etapaSelect.$invalid;
                };

                $scope.changeasesor = function(){
                        $scope.datos.fecha1 = null;
                        $scope.datos.fecha2 = null;
                        $scope.asesorInvalido = $scope.formUsuario.asesoresSelect.$invalid;
                };

                $scope.changefecha1 = function(){
                        $scope.datos.fecha2 = null;
                        $scope.fecha2Invalido = $scope.formUsuario.fecha_inicio.$invalid;
                };

                $scope.changefecha2 = function(){
                $scope.fechaInvalido = $scope.formUsuario.fecha_final.$invalid;
                };

            $scope.CrearGrafica = function(data){
                $scope.users = data;
                $scope.arrData = new Array;
                $scope.arrLabels = new Array;
                // LOOP THROUGH DATA IN THE JSON FILE.
                angular.forEach(data, function (item) {
                    $scope.arrData.push(item.clientes);
                    $scope.arrLabels.push(item.mes);
                });

                $scope.data = new Array;
                $scope.labels = new Array;

                // UPDATE SCOPE PROPERTIES “data” and “label” FOR DATA.
                $scope.data.push($scope.arrData.slice(0));

                for (var i = 0; i < $scope.arrLabels.length; i++) {
                    $scope.labels.push($scope.arrLabels[i]);
                }
            }

            $scope.ObtenerReporte = function(data){
                $scope.asesorInvalido = $scope.formUsuario.asesoresSelect.$invalid;
                $scope.gerenteInvalido = $scope.formUsuario.managerSelect.$invalid;
                $scope.etapaInvalido = $scope.formUsuario.etapaSelect.$invalid;
                $scope.fechaInvalido = $scope.formUsuario.fecha_final.$invalid;
                $scope.fecha2Invalido = $scope.formUsuario.fecha_inicio.$invalid;
                
                var a = $scope.formUsuario.fecha_inicio.$viewValue;
                var b = $scope.formUsuario.fecha_final.$viewValue;
                var c = $scope.formUsuario.etapaSelect.$viewValue;
                var d = $scope.formUsuario.asesoresSelect.$viewValue;
                var e = $scope.formUsuario.managerSelect.$viewValue;

                if(a == "" || b == "" || c == undefined || d == undefined || e == undefined){
                }else{
                    request = {
                    method: 'POST',
                    url: url + 'Estadisticas/get_chart_gerente',
                    data: JSON.stringify({tipo : data.etapa, gerente: data.gerente, asesor : data.asesor, fecha_ini : data.fecha1, fecha_fin : data.fecha2})
                    };
                    $http(request)
                    .then(function (jsonData) {
                        var tttipo = $scope.datos.etapa;
                        ttipo = $.grep($scope.etapas, function (data) {
                        return data.value == tttipo;
                        })[0].label;

                        var aas = $scope.datos.asesor;
                        ass = $.grep($scope.asesores, function (data) {
                        return data.id_asesores == aas;
                        })[0].nombre_asesores;

                        var yr = a.substring(0, 4);

                        $scope.options = {
                            scales: {
                                yAxes: [{id: 'y-axis-1', type: 'linear', position: 'left', ticks: {min: 0}}]
                                    },
                        
                            animation : {
                                onComplete : function(datooos){    
                                    grafica = datooos.chartInstance.toBase64Image();
                                    //console.log(grafica);
                                }
                            },

                            title: {
                                display: true,
                                text: ttipo + " " + ass + " " + yr,
                                fontSize: 26
                            }
                        };
                        $scope.CrearGrafica(jsonData.data);
                    })
                    .catch(function (Object) {
                        alert(Object.data);
                    });
                }

            }

            $scope.exportData = function (data) {

            request = {
                method: 'POST',
                url: url + 'Estadisticas/get_repo_dir'
                };
                $http(request)
                .then(function (jsonData) {

                    
                    alasql('SELECT * INTO XLSX("Listado.xlsx",?) FROM ?',[opts,jsonData.data]);

                })
                .catch(function (Object) {
                    alert(Object.data);
                });   
            };

            $scope.exportData1 = function (data) {
                $scope.asesorInvalido = $scope.formUsuario.asesoresSelect.$invalid;
                $scope.gerenteInvalido = $scope.formUsuario.managerSelect.$invalid;
                $scope.etapaInvalido = $scope.formUsuario.etapaSelect.$invalid;
                $scope.fechaInvalido = $scope.formUsuario.fecha_final.$invalid;
                $scope.fecha2Invalido = $scope.formUsuario.fecha_inicio.$invalid;
                
                var a = $scope.formUsuario.fecha_inicio.$viewValue;
                var b = $scope.formUsuario.fecha_final.$viewValue;
                var c = $scope.formUsuario.etapaSelect.$viewValue;
                var d = $scope.formUsuario.asesoresSelect.$viewValue;
                var e = $scope.formUsuario.managerSelect.$viewValue;

                if(a == "" || b == "" || c == undefined || d == undefined || e == undefined){
                }else{
                    request = {
                        method: 'POST',
                        url: url + 'Estadisticas/get_repo_dir1',
                        data: JSON.stringify({tipo : data.etapa, gerente : data.gerente, asesor : data.asesor, fecha_ini : data.fecha1, fecha_fin : data.fecha2})
                        };
                        $http(request)
                        .then(function (jsonData) {

                            
                            alasql('SELECT * INTO XLSX("Listado.xlsx",?) FROM ?',[opts,jsonData.data]);

                        })
                        .catch(function (Object) {
                            alert(Object.data);
                    });  
                } 
            };

            $scope.Download = function() {
                $http.get(grafica, {
                    responseType: "arraybuffer"
                })
                .then(function(data) {
                    var anchor = angular.element('<a/>');
                    //var blob = new Blob([data]);
                    var blob = new Blob( [ data ]);
                        anchor.attr({
                           href: grafica,
                            target: '_blank',
                            download: 'fileName.jpg'
                        })[0].click();
                })
            };

        });

</script>

<?php
    require("footer.php");
?>