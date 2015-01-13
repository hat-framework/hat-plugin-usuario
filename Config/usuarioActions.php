<?php

use classes\Classes\Actions;
class usuarioActions extends Actions{
        
    protected $permissions = array(
        'GerenciarPerfis' => array(
            'nome'      => "usuario_GP",
            'label'     => "Gerenciar Perfis de usuário",
            'descricao' => "Permite gerenciar os tipos de usuário que poderão acessar o sistema e suas permissões de acesso.",
            'default'   => 'n',
        ),
        'GerenciarUsuarios' => array(
            'nome'      => "usuario_GU",
            'label'     => "Gerenciar Usuários",
            'descricao' => "Permite adicionar, remover e visualizar os usuários do sistema.",
            'default'   => 'n',
        ),
        'AcessarConta' => array(
            'nome'      => "usuario_AC",
            'label'     => "Gerenciar Própria Conta",
            'descricao' => "Permite que o acesse página inicial e 
                altere os próprios dados de email e senha.",
            'default'   => 's',
        ),
        'FazerLogin' => array(
            'nome'      => "usuario_FL",
            'label'     => "Acessar o sistema",
            'descricao' => "Permite que o usuário cadastre sua conta e acesse o sistema",
            'default'   => 's',
        ),
        
        'GerenciarGadgets' => array(
            'nome'      => "usuario_gadget",
            'label'     => "Gerenciar Gadgets",
            'descricao' => "Permite adicionar novos gadgets para a página do usuário",
            'default'   => 'n',
        ),
        
        'AnalisarUsuários' => array(
            'nome'      => "usuario_analisar",
            'label'     => "Analisar Usuários",
            'descricao' => "Permite visualizar informações de usuários",
            'default'   => 'n',
        ),
    );
    
    protected $actions = array(
        
        'usuario/perfil/index' => array(
            'label' => 'Tipos de Usuário', 'publico' => 'n', 'default_yes' => 's','default_no' => 'n',
            'permission' => 'usuario_GP', 
            'menu' => array('usuario/perfil/formulario'),
            'breadscrumb' => array('usuario/login/report', 'usuario/perfil/index'),
        ),
        
        'usuario/perfil/formulario' => array(
            'label' => 'Criar perfil', 'publico' => 'n', 'default_yes' => 's','default_no' => 'n',
            'permission' => 'usuario_GP', 
            'menu' => array(),
            'breadscrumb' => array('usuario/login/report', 'usuario/perfil/index', 'usuario/perfil/formulario' ),
        ),
        
        'usuario/perfil/permissoes' => array(
            'label' => 'Editar Permissões', 'publico' => 'n', 'default_no' => 'n',
            'permission' => 'usuario_GP', 'needcod' => true,
            'menu' => array(),
            'breadscrumb' => array('usuario/login/report', 'usuario/perfil/index', 'usuario/perfil/show', 'usuario/perfil/permissoes' ),
        ),
        
        
        
        'usuario/perfil/show' => array(
            'label' => 'Visualizar perfil', 'publico' => 'n', 'default_yes' => 's','default_no' => 'n',
            'permission' => 'usuario_GP', 'needcod' => true,
            'menu' => array(
                'Ações' => array(
                    'Editar'     => 'usuario/perfil/edit', 
                    'Permissões' => 'usuario/perfil/permissoes',
                    'Excluir'    => 'usuario/perfil/apagar',
                )
            ),
            'breadscrumb' => array('usuario/login/report', 'usuario/perfil/index', 'usuario/perfil/show' ),
        ),
        
        'usuario/perfil/sublist' => array(
            'label' => 'Tornar Padrão', 'publico' => 'n', 'default_yes' => 's','default_no' => 'n',
            'permission' => 'usuario_GP', 'needcod' => true,
            'menu' => array(
                'Minha Conta'                    => 'usuario/login/logado', 
                'Voltar para gerência de Perfis' => 'usuario/perfil/index', 
                'Ações' => array(
                    'Editar'     => 'usuario/perfil/edit', 
                    'Permissões' => 'usuario/perfil/permissoes',
                    'Excluir'    => 'usuario/perfil/apagar',
                )
            )
        ),
        
        'usuario/perfil/edit' => array(
            'label' => 'Editar perfil', 'publico' => 'n', 'default_yes' => 's','default_no' => 'n', 
            'permission' => 'usuario_GP', 'needcod' => true,
            'menu' => array(),
            'breadscrumb' => array('usuario/login/report', 'usuario/perfil/index', 'usuario/perfil/show', 'usuario/perfil/edit' ),
        ),

        'usuario/perfil/apagar' => array(
            'label' => 'Apagar perfil', 'publico' => 'n', 'default_yes' => 's','default_no' => 'n',
            'permission' => 'usuario_GP', 'needcod' => true,
            'menu' => array()
        ),
 
        'usuario/index/index'=> array(
            'label' => 'Fazer Login', 'publico' => 's', 'default_yes' => 's','default_no' => 's',
            'permission' => 'usuario_FL', 
            'menu' => array()
        ),
        
        'usuario/login/reenviar'=> array(
            'label' => 'Reenviar Confirmação', 'publico' => 's', 'default_yes' => 's','default_no' => 'n',
            'permission' => 'usuario_FL', 
            'menu' => array()
        ),
        
        'usuario/login/logout' => array(
            'label' => 'Sair do Sistema', 'publico' => 's', 'default_yes' => 's','default_no' => 's',
            'permission' => 'usuario_FL', 
            'menu' => array()
        ),
        
        'usuario/login/index' => array(
            'label' => 'Login', 'publico' => 's', 'default_yes' => 's','default_no' => 's',
            'permission' => 'usuario_FL', 
            'menu' => array()
        ),
        
        'usuario/login/facebook' => array(
            'label' => 'Login com Facebook', 'publico' => 's', 'default_yes' => 's','default_no' => 's',
            'permission' => 'usuario_FL', 
            'menu' => array()
        ),
        
        'usuario/login/inserir' => array(
            'label' => 'Nova Conta', 'publico' => 's', 'default_yes' => 's','default_no' => 's',
            'permission' => 'usuario_FL', 
            'menu' => array('Voltar' => 'usuario/login/index')
        ),
        
        'usuario/login/recuperar' => array(
            'label' => 'Recuperar Conta', 'publico' => 's', 'default_yes' => 's','default_no' => 's',
            'permission' => 'usuario_FL', 
            'menu' => array('Voltar' => 'usuario/login/index')
        ),
        
        'usuario/login/confirmar' => array(
            'label' => 'Confirmar Conta', 'publico' => 's', 'default_yes' => 's','default_no' => 's',
            'permission' => 'usuario_FL', 
            'menu' => array()
        ),
        
        'usuario/login/confirmrec' => array(
            'label' => 'Confirmar Recuperação', 'publico' => 's', 'default_yes' => 's','default_no' => 's',
            'permission' => 'usuario_FL', 
            'menu' => array()
        ),
        'usuario/login/why_confirm' => array(
            'label' => 'Porque Confirmar', 'publico' => 'n', 'default_yes' => 's','default_no' => 's',
            'permission' => 'usuario_FL', 
            'menu' => array()
        ),
        
        'usuario/login/confirm_resend' => array(
            'label' => 'Reenviar Confirmação', 'publico' => 'n', 'default_yes' => 's','default_no' => 's',
            'permission' => 'usuario_FL', 
            'menu' => array()
        ),
        
         'usuario/login/identidade'=> array(
            'label' => 'Fazer Login', 'publico' => 'n', 'default_yes' => 's','default_no' => 'n',
            'permission' => 'usuario_AC', 
            'menu' => array()
        ),
        
        'usuario/login/logado' => array(
            'label' => 'Minha Conta', 'publico' => 'n', 'default_yes' => 's','default_no' => 'n',
            'permission' => 'usuario_AC', 
            'menu' => array('Preferência de Notificação' => 'usuario/notify/index','Recuperar Senha' => 'usuario/login/recuperar'),
            'breadscrumb' => array('usuario/login/logado')
        ),
        
        'usuario/login/alterar' => array(
            'label' => 'Alterar dados', 'publico' => 'n', 'default_yes' => 's','default_no' => 'n',
            'permission' => 'usuario_AC', 
            'menu' => array('usuario/login/logado')
        ),
        
        'usuario/login/tutorial' => array(
            'label' => 'Tutorial', 'publico' => 'n', 'default_yes' => 's','default_no' => 'n',
            'permission' => 'usuario_AC', 
            'menu' => array('usuario/login/logado')
        ),
        
        'usuario/login/report'=> array(
            'label' => 'Relatório de usuários', 'publico' => 'n', 'default_yes' => 's','default_no' => 'n',
            'permission' => 'usuario_analisar', 
            'menu' => array(
                'Todos os usuários'      => 'usuario/login/todos', 
                'Perfis de usuário'      => 'usuario/perfil/index',
                'Relatórios Visão Geral' => 'usuario/login/report',
                'Relatórios por action'  => 'usuario/login/actionreport',
                'Relatório por usuário'  => 'usuario/login/personalreport',
                'Mais relatórios'        => 'usuario/login/otherreport'),
            'breadscrumb' => array('usuario/login/todos','usuario/login/report')
        ),
        
         'usuario/login/otherreport'=> array(
            'label' => 'Mais Relatório de usuários', 'publico' => 'n', 'default_yes' => 's','default_no' => 'n',
            'permission' => 'usuario_analisar', 
            'menu' => array(
                'Todos os usuários'      => 'usuario/login/todos', 
                'Perfis de usuário'      => 'usuario/perfil/index',
                'Relatórios Visão Geral' => 'usuario/login/report',
                'Relatórios por action'  => 'usuario/login/actionreport',
                'Relatório por usuário'  => 'usuario/login/personalreport',
                'Mais relatórios'        => 'usuario/login/otherreport'),
            'breadscrumb' => array('usuario/login/todos','usuario/login/report','usuario/login/otherreport')
        ),
        
        'usuario/login/personalreport'=> array(
            'label' => 'Relatório de usuários', 'publico' => 'n', 'default_yes' => 's','default_no' => 'n',
            'permission' => 'usuario_analisar', 
            'menu' => array(
                'Todos os usuários'      => 'usuario/login/todos', 
                'Perfis de usuário'      => 'usuario/perfil/index',
                'Relatórios Visão Geral' => 'usuario/login/report',
                'Relatórios por action'  => 'usuario/login/actionreport',
                'Relatório por usuário'  => 'usuario/login/personalreport',
                'Mais relatórios'        => 'usuario/login/otherreport'),
            'breadscrumb' => array('usuario/login/todos','usuario/login/report','usuario/login/personalreport')
        ),
        
        'usuario/login/actionreport'=> array(
            'label' => 'Relatório de usuários', 'publico' => 'n', 'default_yes' => 's','default_no' => 'n',
            'permission' => 'usuario_analisar', 
            'menu' => array(
                'Todos os usuários'      => 'usuario/login/todos', 
                'Perfis de usuário'      => 'usuario/perfil/index',
                'Relatórios Visão Geral' => 'usuario/login/report',
                'Relatórios por action'  => 'usuario/login/actionreport',
                'Relatório por usuário'  => 'usuario/login/personalreport',
                'Mais relatórios'        => 'usuario/login/otherreport'),
            'breadscrumb' => array('usuario/login/todos','usuario/login/report','usuario/login/personalreport')
        ),
        
         'usuario/login/todos' => array(
            'label' => 'Gerenciar ', 'publico' => 'n', 'default_yes' => 's','default_no' => 'n',
            'permission' => 'usuario_analisar', 
            'menu' => array(
                'usuario/login/formulario',
                'Todos os usuários'      => 'usuario/login/todos', 
                'Perfis de usuário'      => 'usuario/perfil/index',
                'Relatórios Visão Geral' => 'usuario/login/report',
                'Relatórios por action'  => 'usuario/login/actionreport',
                'Relatório por usuário'  => 'usuario/login/personalreport',
                'Mais relatórios'        => 'usuario/login/otherreport'),
            'breadscrumb' => array('usuario/login/report', 'usuario/login/todos'),
        ),
        
        
        
        'usuario/login/widgets'=> array(
            'label' => 'Visualizar Widget', 'publico' => 'n', 'default_yes' => 's','default_no' => 'n',
            'permission' => 'usuario_analisar', 
            'menu' => array(),
            'breadscrumb' => array('usuario/login/report', 'usuario/login/widgets')
        ),
        
        
        'usuario/login/show' => array(
            'label' => 'Visualizar usuário', 'publico' => 'n', 'default_yes' => 's','default_no' => 'n',
            'permission' => 'usuario_analisar', 'needcod' => true,
            'menu' => array(
                'Visualizar Log' => "usuario/login/seelog",
                'Dados Extras'   => "usuario/login/seedata",
                'Opções' => array('usuario/login/edit', 'usuario/login/apagar')
             ),
            'breadscrumb' => array('usuario/login/report', 'usuario/login/todos', 'usuario/login/show')
        ),
        
         'usuario/login/seelog' => array(
            'label' => 'Visualizar log', 'publico' => 'n', 'default_yes' => 's','default_no' => 'n',
            'permission' => 'usuario_analisar', 'needcod' => true,
            'menu' => array(),
            'breadscrumb' => array('usuario/login/report', 'usuario/login/todos', 'usuario/login/show', 'usuario/login/seelog')
        ),
        
        
         'usuario/login/seedata' => array(
            'label' => 'Visualizar Dados Extras', 'publico' => 'n', 'default_yes' => 's','default_no' => 'n',
            'permission' => 'usuario_analisar', 'needcod' => true,
            'menu' => array(),
            'breadscrumb' => array('usuario/login/report', 'usuario/login/todos', 'usuario/login/show', 'usuario/login/seelog')
        ),
        
        
        'usuario/login/gadget' => array(
            'label' => 'Gadgets do Usuário', 'publico' => 'n', 'default_yes' => 's','default_no' => 'n',
            'permission' => 'usuario_analisar', 'needcod' => true,
            'menu' => array(
                'usuario/login/logado', 
                'usuario/login/todos',
                'Opções' => array('usuario/login/edit', 'usuario/login/apagar')
             )
        ),
        
        'usuario/login/apagar' => array(
            'label' => 'Excluir Usuário', 'publico' => 'n', 'default_yes' => 's','default_no' => 'n',
            'permission' => 'usuario_GU', 'needcod' => true,
        ),
        
        'usuario/login/block' => array(
            'label' => 'Bloquear Usuário', 'publico' => 'n', 'default_yes' => 's','default_no' => 'n',
            'permission' => 'usuario_GU', 'needcod' => true,
        ),
        
        'usuario/login/unblock' => array(
            'label' => 'Desbloquear Usuário', 'publico' => 'n', 'default_yes' => 's','default_no' => 'n',
            'permission' => 'usuario_GU', 'needcod' => true,
        ),
        
        'usuario/login/formulario' => array(
            'label' => 'Novo usuário', 'publico' => 'n', 'default_yes' => 's','default_no' => 'n',
            'permission' => 'usuario_GU', 
            'menu' => array(),
            'breadscrumb' => array('usuario/login/report', 'usuario/login/todos', 'usuario/login/formulario')
        ),
        
        'usuario/login/edit' => array(
            'label' => 'Editar usuário', 'publico' => 'n', 'default_yes' => 's','default_no' => 'n',
            'permission' => 'usuario_GU', 'needcod' => true,
            'menu' => array(),
            'breadscrumb' => array('usuario/login/report', 'usuario/login/todos', 'usuario/login/show', 'usuario/login/edit')
        ),
        
        'usuario/login/editar' => array(
            'label' => 'Editar usuário', 'publico' => 'n', 'default_yes' => 's','default_no' => 'n',
            'permission' => 'usuario_GU', 'needcod' => true,
            'menu' => array('usuario/login/logado', 'usuario/login/todos', 'usuario/login/show')
        ),
        
       
        
        
        
        
        'usuario/gadget/index' => array(
            'label' => 'Todos os Gadgets', 'publico' => 'n', 'default_yes' => 's','default_no' => 'n',
            'permission' => 'usuario_gadget', 
            'menu' => array(
                'Minha Conta'        => 'usuario/login/logado', 
                'usuario/gadget/formulario',
             )
        ),
        
        'usuario/gadget/formulario' => array(
            'label' => 'Criar Gadget', 'publico' => 'n', 'default_yes' => 's','default_no' => 'n',
            'permission' => 'usuario_gadget', 
            'menu' => array("usuario/gadget/index")
        ),
        
        'usuario/gadget/show' => array(
            'label' => 'Visualizar Gadget', 'publico' => 'n', 'default_yes' => 's','default_no' => 'n',
            'permission' => 'usuario_gadget', 'needcod' => true,
            'menu' => array(
                'Todos os Gadgets' => "usuario/gadget/index",
                'Ações' => array(
                    'Editar'     => 'usuario/gadget/edit', 
                    'Excluir'    => 'usuario/gadget/apagar',
                )
            )
        ),
        
        'usuario/gadget/edit' => array(
            'label' => 'Editar Gadget', 'publico' => 'n', 'default_yes' => 's','default_no' => 'n', 
            'permission' => 'usuario_gadget', 'needcod' => true,
            'menu' => array(
                'Voltar' => 'usuario/gadget/show')
        ),

        'usuario/gadget/apagar' => array(
            'label' => 'Apagar Gadget', 'publico' => 'n', 'default_yes' => 's','default_no' => 'n',
            'permission' => 'usuario_gadget', 'needcod' => true,
            'menu' => array()
        ),
        
         'usuario/notify/index' => array(
            'label' => 'Preferência de Notificação', 'publico' => 'n', 'default_yes' => 's','default_no' => 'n',
            'permission' => 'usuario_AC', 
             'breadscrumb' => array('usuario/login/logado','usuario/notify/index')
        ),
        
        'usuario/endereco/getcep' => array(
            'label' => 'Getcep', 'publico' => 's', 'default_yes' => 's','default_no' => 'n', 
            'permission' => 'usuario_AC', 'needcod' => false,
        ),
        
        
        'usuario/endereco/edit' => array(
            'label' => 'Editar Endereço', 'publico' => 'n', 'default_yes' => 's','default_no' => 'n', 
            'permission' => 'usuario_AC', 'needcod' => true,
            'menu' => array('Voltar' => 'usuario/endereco/show')
        ),
        'usuario/endereco/show' => array(
            'label' => 'Visualizar Endereço', 'publico' => 'n', 'default_yes' => 's','default_no' => 'n', 
            'permission' => 'usuario_AC', 'needcod' => true,
            'menu' => array('Voltar' => 'usuario/endereco/show')
        ),
        'usuario/endereco/formulario' => array(
            'label' => 'Inserir Endereço', 'publico' => 'n', 'default_yes' => 's','default_no' => 'n', 
            'permission' => 'usuario_AC', 'needcod' => false,
            'menu' => array('Voltar' => 'usuario/login/logado')
        ),
        'usuario/endereco/show' => array(
            'label' => 'Visualizar Endereço', 'publico' => 'n', 'default_yes' => 's','default_no' => 'n', 
            'permission' => 'usuario_AC', 'needcod' => true,
            'menu' => array('Voltar' => 'usuario/endereco/show')
        ),

    );
    
    protected $perfis = array(
        
        'Webmaster' => array(
            'cod'         => Webmaster,
            'nome'        => 'Webmaster',
            'default'     => '0',
            'tipo'        => 'sistema',
            'descricao'   => 'Perfil destinado aos Webmasters. Eles terão acesso à todos os dados do site',
            'permissions' => array('usuario_GP' => 's', 'usuario_GU'=> 's','usuario_AC'=> 's','usuario_FL'=> 's',)
        ),
        
        'Administrador' => array(
            'cod'         => Admin,
            'pai'         => Webmaster,
            'nome'        => 'Administrador',
            'default'     => '0',
            'tipo'        => 'sistema',
            'descricao'   => 'Usuários com previlégios administrativos, podem alterar configurações do site',
            'permissions' => array('usuario_GP' => 's', 'usuario_GU'=> 's','usuario_AC'=> 's','usuario_FL'=> 's',)
        ),
        
        'Visitante' => array(
            'cod'       => Visitante,
            'pai'       => Admin,
            'nome'      => 'Visitante',
            'default'   => '0',
            'tipo'      => 'sistema',
            'descricao' => 'Perfil destinado aos visitantes do site, qualquer usuário que fizer o próprio cadastro automaticamente',
            'permissions' => array( 'usuario_AC'=> 's','usuario_FL'=> 's')
        ),
        'Analista_Informacao' => array(
            'cod'       => Analista_Informacao,
            'pai'       => Admin,
            'nome'      => 'Analista Informação',
            'default'   => '0',
            'tipo'      => 'usuario',
            'descricao' => 'Perfil destinado para analistas que terão acesso as métricas e aos acessos premium',
            'permissions' => array('usuario_AC'=> 's','usuario_FL'=> 's','usuario_analisar' => 's', 'Plugins_ANA'=> 's')
        ),
    );
}