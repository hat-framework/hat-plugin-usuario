'use strict';
usuario_messageApp.controller('usuario_mensagem_usersCTRL',['$scope','$http','$rootScope',function($scope,$http,$rootScope) {
    $scope.friends       = [];
    $scope.hideForm      = true;
    $scope.groups        = [];
    $scope.hideGroupForm = true;
    $scope.user_url = window.location.protocol+"//"+window.location.host+"/usuario/login/show";
    $rootScope.$on('usuario_message_setFriendList', function(ev, data){
        $scope.friends  = data;
        $scope.hideForm = (data.length > 10)?false:true;
        if(typeof data[0] !== 'undefined'){
            $scope.currentUser(data[0]);
        }
    });
    
    $rootScope.$on('usuario_message_setGroups', function(ev, data){
        $scope.groups  = data;
        $scope.hideGroupForm = (data.length > 10)?false:true;
    });
    
    $scope.currentGroup = function(group){
        var fakeuser = {cod_usuario: 'group_'+group.usuario_perfil_cod, user_name:group.usuario_perfil_nome};
        $rootScope.$emit("usuario_message_changeUser", fakeuser);
    };
    
    $scope.currentUser = function(user){
        $rootScope.$emit("usuario_message_changeUser", user);
    };
    
    $rootScope.$on('usuario_message_send', function(ev, data){
        console.log(data);
    });
}]);