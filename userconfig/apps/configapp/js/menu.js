'use strict';
usuario_configApp.controller('usuario_config_menuCTRL',['$scope','$http', function($scope, $http) {
    $scope.options  = [];
    $scope.userMenu = [];
    var init = function(){
        var url = 'http://'+window.location.host+'/index.php?ajax=true&url=usuario/userconfig/getmenu/';
        $http({method: 'GET', url: url}).success(function(response) {
            //console.log(response);
            $scope.options  = response.options;
            $scope.userMenu = response.userMenu;
        });
    };
    init();
}]);
