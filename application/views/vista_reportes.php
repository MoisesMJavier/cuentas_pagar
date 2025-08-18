<?php
    require("head.php");
    require("menu_navegador.php");
?>

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-10">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3>GRÁFICA</h3>
                        <div ng-app="myChart" ng-controller="myController" style="width:700px;">
                            <canvas id="bar" 
                                class="chart chart-bar" 
                                chart-data="data"
                                chart-labels="labels"
                                colours="colors">
                            </canvas>
                        </div>
                        <br>
                    </div>
                </div>
            </div>
        </div>
        <div>
            <div class="col-lg-10">
                <div class="box box-info">
                    <h3>Opciones</h3>
                    <div ng-controller="ExampleController">
                        <form name="myForm">
                            <label for="repeatSelect"> Año: </label>
                            <select name="repeatSelect" id="repeatSelect" ng-model="data.model">
                            <option ng-repeat="option in data.availableOptions" value="{{option.id}}">{{option.name}}</option>
                            </select>
                        </form>
                        <hr>
                        <p>model = {{data.model}}</p><br/>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
    var myApp = angular.module('CRM', ["chart.js"]);

    myApp.controller('myController',
        function ($scope, $http) {
            // REQUEST OPTIONS USING GET METHOD.
            var request = {
                method: 'get',
                url: 'Reportes/get_ventas',
                dataType: 'json',
                contentType: "application/json"
            };

            $scope.arrData = new Array;
            $scope.arrLabels = new Array;

            // MAKE REQUEST USING $http SERVICE.
            $http(request)
                .then(function (jsonData) {

                    // LOOP THROUGH DATA IN THE JSON FILE.
                    angular.forEach(jsonData.data, function (item) {
                        $scope.arrData.push(item.ventas);
                        $scope.arrLabels.push(item.mes);
                    });

                    $scope.data = new Array;
                    $scope.labels = new Array;

                    // UPDATE SCOPE PROPERTIES “data” and “label” FOR DATA.
                    $scope.data.push($scope.arrData.slice(0));

                    for (var i = 0; i < $scope.arrLabels.length; i++) {
                        $scope.labels.push($scope.arrLabels[i]);
                    }
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
    });

 
    myApp.controller('ExampleController', function($scope) {
        $scope.data = {
            model: null,
            availableOptions: [
            {id: '2017', name: '2017'},
            {id: '2018', name: '2018'}
            ]
        };
    });

</script>

<?php
    require("footer.php");
?>