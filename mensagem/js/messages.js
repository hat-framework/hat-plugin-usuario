'use strict';
usuario_messageApp.controller('usuario_mensagemCTRL',['$scope','$http','$rootScope',function($scope,$http,$rootScope) {
    $scope.messages = [];
    $scope.sender   = [];
    $scope.user     = [];
    $scope.url      = window.location.protocol+"//"+window.location.host+"/usuario/login/show";
    //$scope.reddit   = new Reddit();

    $rootScope.$on('usuario_message_send', function(ev, data){
        $scope.messages.unshift($scope.prepareMessageData(data));
    });
    
    $rootScope.$on('usuario_message_changeUser', function(ev, data){
        $scope.user = data;
        $scope.messages = [];
        $scope.getMessages();
    });
    
    $rootScope.$on('usuario_message_changeSender', function(ev, data){
        $scope.sender  = data;
        $scope.messages = [];
        $scope.getMessages();
    });
    
    $scope.getMessages = function(){
        if($scope.user.length === 0 || $scope.sender.length === 0){return;}
        var link = $scope.sender.cod_usuario+"/"+$scope.user.cod_usuario;
        var url = window.location.protocol+"//"+window.location.host+"/index.php?ajax=true&url=usuario/mensagem/conversa/"+link;
        $http({method: 'GET', url: url}).success(function(response) {
            for(var i in response){
                $scope.messages.push($scope.prepareMessageData(response[i]));
            }
        });
    };
    
    $scope.prepareMessageData = function(data){
        data['cod_usuario'] = data['from'];
        data['from'] = (data['from'] == $scope.sender.cod_usuario)?$scope.sender.user_name:$scope.user.user_name;
        data['to']   = (data['to']   == $scope.sender.cod_usuario)?$scope.sender.user_name:$scope.user.user_name;
        return data;
    };
    
}]);