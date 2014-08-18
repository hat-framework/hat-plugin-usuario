var crudModule = angular.module('crudModule', []);
crudModule.directive('multicrud',function (){
    return {
        restrict: 'EA',
        templateUrl: window.location.protocol+"//"+window.location.host+'/plugins/usuario/userconfig/apps/configapp/html/directives/directives.html',
        replace: true,
        scope: {
            "itens"    : "=itens",
            "options"  : "=options"
        },
        controller: ['$scope', function ($scope) {
            $scope.emptyItem = {};
            $scope.$watch('itens', function () {
                if(typeof $scope.itens === 'undefined'){return;}
                if($scope.itens.length === 0){
                    for(var i in $scope.itens){
                        $scope.emptyItem[$scope.itens[i].model] = "";
                    }
                }
                addEmpty();
            });
            
            $scope.remove = function(item){
                $scope.itens.splice($scope.itens.indexOf(item) , 1 );
                if($scope.itens.length === 0){addEmpty();}
            };

            $scope.addKeyPress = function(index){
                for(var i in $scope.itens){
                    if(index == i || $scope.isNotEmpty(i)){continue;}
                    $scope.remove($scope.itens[i]);
                }            
                if(!$scope.isNotEmpty(index)){ return;}
                addEmpty();
                persist(index);
            };

            $scope.isNotEmpty = function(index){
                for(var i in $scope.options.fields){
                    if(typeof $scope.options.fields[i].required === 'undefined' || $scope.options.fields[i].required === false){continue;}
                    var fieldname = $scope.options.fields[i].model;
                    if(typeof $scope.itens[index] === 'undefined'){continue;}
                    if(typeof $scope.itens[index][fieldname] === 'undefined' || $scope.itens[index][fieldname] === ""){return false;}
                }
                return true;
            };

            var persist = function(index){
                if(!$scope.isNotEmpty(index)){return;}
                var url = getUrl('edit');
                console.log($scope.itens[index]);
            };

            var addEmpty = function(){
                $scope.itens.push(angular.copy($scope.emptyItem));
            };   
            
            var getUrl = function(type_url){
                if(typeof $scope.options.url === 'undefined' || typeof $scope.options.url[type_url] === 'undefined'){
                    return "";
                }
                return window.location.protocol+"//"+window.location.host+"/"+$scope.options.url[type_url];
            };
        }]
    };
});