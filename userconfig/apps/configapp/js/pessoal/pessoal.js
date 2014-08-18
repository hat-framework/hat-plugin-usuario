'use strict';
usuario_configApp.controller('usuario_pessoalCTRL',['$scope','$routeParams','$http',function($scope, $routeParams,$http) {
    $scope.category = "pessoal";
    $scope.config = {
        'name': "Dados de Acesso",
        'files_of_grupo': [
            {title:"Email e Senha"    , descricao:"Dados de acesso ao site e alteração da senha", cod_cfile:'mail'},
            {title:"Emails adicionais", descricao:"Configure outros emails para contato", cod_cfile:'mail'},
            {title:"Telefone"         , descricao:"Configure todos os telefones para contato", cod_cfile:'phone'}
        ]
    };
}]);
