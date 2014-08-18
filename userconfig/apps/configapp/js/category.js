'use strict';
usuario_configApp.controller('usuario_config_categoryCTRL',['$scope','$routeParams','$http',function($scope, $routeParams,$http) {
    var LoadMenu = function(cod_menu){
        var url = 'http://'+window.location.host+'/index.php?ajax=true&url=usuario/userconfig/config/'+cod_menu;
        $http({method: 'GET', url: url}).success(function(response) {
            $scope.config = response.data;
        });
    };
    $scope.$watch('category', function() {
        LoadMenu($scope.category);
    });
    $scope.category = $routeParams.menuId;
}]);
