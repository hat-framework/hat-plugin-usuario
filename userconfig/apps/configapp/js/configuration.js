'use strict';
usuario_configApp.controller('usuario_config_configurationCTRL',['$scope','$http','$routeParams', function($scope, $http, $routeParams) {
    var Load = function(configId){
        var url = 'http://'+window.location.host+'/index.php?ajax=true&url=usuario/userconfig/loadConfig/'+configId;
        $http({method: 'GET', url: url}).success(function(response) {
            console.log(response);
        });
    };
    $scope.$watch('category', function() {
        Load($scope.category);
    });
    $scope.category = $routeParams.configId;
}]);
