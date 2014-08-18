'use strict';
usuario_messageApp.controller('usuario_mensagem_formCTRL',['$scope','$http','$rootScope',function($scope,$http,$rootScope) {
    $scope.cansend = false;
    $scope.content = '';
    $scope.sender  = [];
    $scope.user    = [];
    $scope.features= [];
    $scope.user_url= window.location.protocol+"//"+window.location.host+"/usuario/login/show";
    $scope.sendMessage = function(){
        $rootScope.$emit("usuario_message_send", {
            mensagem :$scope.content, 
            from     :$scope.sender.cod_usuario,
            to       :$scope.user.cod_usuario,
            date     :$scope.getDate()
        });
        $scope.persistMessage();
    };
    $scope.getDate = function(){
        var currentdate = new Date(); 
        var datetime = currentdate.getFullYear()  + "-"
                + (currentdate.getMonth()+1<10?'0':'')   + currentdate.getMonth()+1 + "-" 
                + (currentdate.getDate()<10?'0':'')    + currentdate.getDate() + " " 
                + (currentdate.getHours()<10?'0':'')   + currentdate.getHours() + ":"  
                + (currentdate.getMinutes()<10?'0':'') + currentdate.getMinutes() + ":" 
                + (currentdate.getSeconds()<10?'0':'') + currentdate.getSeconds()
        return datetime;
    };
    
    $scope.persistMessage = function(msg){
        var url = window.location.protocol+"//"+window.location.host+"/index.php?ajax=true&url=usuario/mensagem/formulario";
        var data = {
            from     :$scope.sender.cod_usuario, 
            to       :$scope.user.cod_usuario, 
            mensagem :$scope.content, 
        };
        $http.post(url, data).success(function(response) {/*console.log(response);*/});
        $scope.content = "";
    };
    
    $rootScope.$on('usuario_message_changeSender', function(ev, data){$scope.sender  = data;});
    $rootScope.$on('usuario_message_changeUser', function(ev, data){$scope.user = data;});
    $rootScope.$on('usuario_message_setFeatures', function(ev, data){$scope.features = data;});
    
    $scope.$watch('features', function(newValue, oldValue) {changeFeatures();});
    $scope.$watch('user', function(newValue, oldValue) {changeFeatures();});
    var changeFeatures = function(){
        if($scope.user.length === 0){
            $scope.cansend = false;
            return;
        }
        if(typeof $scope.user.cod_perfil !== 'undefined'){
            $scope.cansend = ($scope.user.cod_perfil === 3||$scope.user.cod_perfil === 2)?
                $scope.features.USUARIO_MENSAGEM_ANY_USER:
                $scope.features.USUARIO_MENSAGEM_FULL_CHAT;
        }else{
            $scope.cansend = ($scope.user.cod_usuario === 'group_todos')?
                    $scope.features.USUARIO_MENSAGEM_ALL_CHAT:
                    $scope.features.USUARIO_MENSAGEM_GROUP_CHAT;
        }
    };
}]);
