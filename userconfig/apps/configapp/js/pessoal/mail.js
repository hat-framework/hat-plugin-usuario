'use strict';

usuario_configApp.controller('usuario_pessoal_generalCTRL',['$scope',function($scope) {}]);
usuario_configApp.controller('usuario_pessoal_emailCTRL',['$scope',function($scope) {
    $scope.options = {
        title: 'Emails',
        url:{
            edit  :'usuario/dados/setData/',
        },
        fields:[
            {type:'text' , required:false, model:'detalhes', input:{style:{width: '74px'}}, row:{style:{width: '74px'}}, placeholder:'Pessoal, empresarial, ...'},
            {type:'email', required:true , model:'email'   , placeholder:'Email', ngChange:'addKeyPress(i)'}
         ]
    };
    $scope.itens     = [];
}]);

usuario_configApp.controller('usuario_pessoal_telefoneCTRL',['$scope',function($scope) {
    $scope.options = {
        title: 'Telefones',
        url:{
            edit  :'usuario/dados/setData/',
        },
        fields:[
            {type:'text', required:false, model:'detalhes', input:{style:{width: '74px'}}, row:{style:{width: '74px'}}, placeholder:'Celular, Casa, Tabalho, ...'},
            {type:'tel' , required:true , model:'numero'   , placeholder:'Número', ngChange:'addKeyPress(i)'}
         ]
    };
    $scope.itens     = [];
}]);

usuario_configApp.controller('usuario_pessoal_addressCTRL',['$scope',function($scope) {
    $scope.options = {
        title: 'Endereços',
        url:{
            edit  :'usuario/dados/setData/'
        },
        fields:[
            {type:'text', required:true , model:'cep'        , placeholder:'Cep', input:{style:{width: '74px'}}} ,
            {type:'text', required:true , model:'rua'        , placeholder:'Logradouro', input:{style:{width: '100px'}}},
            {type:'text', required:true , model:'numero'     , placeholder:'Número', input:{style:{width: '54px'}}},
            {type:'text', required:true , model:'complemento', placeholder:'Complemento', input:{style:{width: '74px'}}},
            {type:'text', required:true , model:'bairro'     , placeholder:'Bairro', input:{style:{width: '100px'}}},
            {type:'text', required:true , model:'cidade'     , placeholder:'Cidade', input:{style:{width: '100px'}}},
            {type:'text', required:true , model:'uf'         , placeholder:'Estado', input:{style:{width: '50px'}}}
         ]
    };
    $scope.itens     = [];
}]);