'use strict';
var usuario_configApp = angular.module('usuario_configApp',['ngRoute', 'crudModule']);
usuario_configApp.config(['$routeProvider', function ($routeProvider) {
    var url = window.location.protocol + "//"+window.location.host+ "/plugins/usuario/userconfig/apps/configapp/html/";
    $routeProvider.
        when('/menu', {
            templateUrl: url + 'redirect.html',
            controller: 'usuario_config_menuRedirectCTRL'
        }).
            when('/menu/pessoal', {
                templateUrl: url + 'pessoal/pessoal.html',
                controller: 'usuario_pessoalCTRL'
            }).
            when('/menu/pessoal/access', {
                templateUrl: url + 'pessoal/access.html',
                controller: 'usuario_pessoal_accessCTRL'
            }).
            when('/menu/pessoal/address', {
                templateUrl: url + 'pessoal/address.html',
                controller: 'usuario_pessoal_addressCTRL'
            }).
            when('/menu/pessoal/phone', {
                templateUrl: url + 'pessoal/phone.html',
                controller: 'usuario_pessoal_phoneCTRL'
            }).
            when('/menu/pessoal/mail', {
                templateUrl: url + 'pessoal/mail.html',
            }).
        when('/menu/:menuId', {
            templateUrl: url + 'category.html',
            controller: 'usuario_config_categoryCTRL'
        }).
                
        when('/menu/:menuId/:configId', {
            templateUrl: url + 'configuration.html',
            controller: 'usuario_config_configurationCTRL'
        }).

        otherwise({
            redirectTo: '/menu'
        });
}]);

usuario_configApp.directive('ngEnter', function () {
    return function (scope, element, attrs) {
        element.bind("keydown keypress", function (event) {
            if(event.which === 13) {
                scope.$apply(function (){
                    scope.$eval(attrs.ngEnter);
                });
 
                event.preventDefault();
            }
        });
    };
});