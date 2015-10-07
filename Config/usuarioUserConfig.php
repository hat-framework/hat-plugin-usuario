<?php
        
class usuarioUserConfig extends \classes\Classes\UserConfig{
    protected $config = array(
        
        'acesso_email' => array(
            'cod'       =>'acesso_email', 
            'group'     =>'acesso',
            'title'     =>'Email de acesso',
            'icon'      =>'fa fa-envelope',
            'ordem'     =>'1',
            'type'      => 'component' , 
            'ref'       => 'usuario/login/alterar', 
            'method'    =>'email',
        ),
        'acesso_senha' => array(
            'cod'   =>'acesso_senha',
            'group' =>'acesso',
            'title' =>'Redefinir Senha',
            'icon'  =>'fa fa-lock',
            'ordem' =>'2',
            'type'  => 'component' , 
            'ref'   => 'usuario/login/alterar', 
            'method'=>'senha'
         ),
        
        'pessoal_address' => array(
            'cod'       =>'pessoal_address', 
            'group'     =>'pessoal',
            'title'     =>'Endereço'          ,
            'icon'      =>'fa fa-map-marker',
            'ordem'     =>'4',
            'type'      => 'directdata',
            'multiple'  => '1',
            'form_data' => array(
                'cep' => array(
                    'name'     => 'Cep',
                    'type'     => 'int',
                    'size'     => '8',
                    'especial' => 'cep',
                    'notnull' => true,
                    'grid'    => true,
                    'display' => true,
                ),
                 'rua' => array(
                    'name'     => 'Logradouro',
                    'type'     => 'varchar',
                    'size'     => '190',
                    'notnull' => true,
                    'grid'    => true,
                    'display' => true,
                ),
                 'numero' => array(
                    'name'     => 'Número',
                    'type'     => 'varchar',
                    'size'     => '10',
                    'notnull' => true,
                    'grid'    => true,
                    'display' => true,
                ),
                 'complemento' => array(
                    'name'     => 'Complemento',
                    'type'     => 'varchar',
                    'size'     => '199',
                    'grid'    => true,
                    'display' => true,
                ),
                 'referencia' => array(
                    'name'     => 'Referência',
                    'type'     => 'text',
                    'grid'    => true,
                    'display' => true,
                ),
                 'bairro' => array(
                    'name'     => 'Bairro',
                    'type'     => 'varchar',
                    'size'     => '64',
                    'notnull' => true,
                    'grid'    => true,
                    'display' => true,
                ),
                 'cidade' => array(
                    'name'     => 'Cidade',
                    'type'     => 'varchar',
                    'size'     => '64',
                    'grid'    => true,
                    'display' => true,
                ),
                 'estado' => array(
                    'name'     => 'Estado',
                    'type'     => 'enum',
                    'options'  => array("AC"=>"Acre", "AL"=>"Alagoas", "AM"=>"Amazonas", "AP"=>"Amapá","BA"=>"Bahia","CE"=>"Ceará","DF"=>"Distrito Federal","ES"=>"Espírito Santo","GO"=>"Goiás","MA"=>"Maranhão","MT"=>"Mato Grosso","MS"=>"Mato Grosso do Sul","MG"=>"Minas Gerais","PA"=>"Pará","PB"=>"Paraíba","PR"=>"Paraná","PE"=>"Pernambuco","PI"=>"Piauí","RJ"=>"Rio de Janeiro","RN"=>"Rio Grande do Norte","RO"=>"Rondônia","RS"=>"Rio Grande do Sul","RR"=>"Roraima","SC"=>"Santa Catarina","SE"=>"Sergipe","SP"=>"São Paulo","TO"=>"Tocantins"),
                    'notnull' => true,
                    'grid'    => true,
                    'display' => true,
                ),

                'button' => array('button' => "Salvar Endereço")
            )
            
        ),
        
        'pessoal_phone' => array(
            'cod'       => 'pessoal_phone', 
            'group'     => 'pessoal',
            'title'     => 'Telefone',
            'icon'      => 'fa fa-phone',
            'ordem'     => '3',
            'type'      => 'directdata',
            'multiple'  => '1',
            'form_data' => array(
                'type' => array(
                    'name'     => 'Tipo',
                    'type'     => 'enum',
                    'default'  => 'fixo',
                    'options'  => array(
                        'fixo'     => "Residencial",
                        'trabalho' => "Trabalho",
                        'tim'      => "Celular Tim",
                        'oi'       => "Celular Oi",
                        'claro'    => "Celular Claro",
                        'vivo'     => "Celular Vivo",
                        'outro'    => "Outra operadora de Celular",
                    ),
                    'notnull'  => true
                ),

                'numero' => array(
                    'name'     => 'Número',
                    'type'     => 'varchar',
                    'size'     => '11',
                    'especial' => 'telefone',
                    'notnull'  => true,
                    'grid'    => true,
                    'display' => true,
                ),

                'button' => array('button' => "Salvar Telefone")
            ),
         ),
        
        'pessoal_email' => array(
            'cod'       =>'pessoal_email'  ,
            'group'     =>'pessoal',
            'title'     =>'Email Alternativo' ,
            'icon'      =>'fa fa-map-marker',
            'ordem'     =>'5',
            'type'      => 'directdata',
            'multiple'  => '1', 
            'form_data' => array(
                'type' => array(
                    'name'     => 'Tipo',
                    'type'     => 'enum',
                    'default'  => 'p',
                    'options'  => array(
                        'p' => "Pessoal",
                        't' => "Trabalho"
                    ),
                    'notnull'  => true
                ),

                'email' => array(
                    'name'     => 'Email',
                    'type'     => 'varchar',
                    'display'  => true,
                    'size'     => '64',
                    'notnull'  => true,
                    'grid'     => true,
                    'especial' => 'email',
                    'description' => "Este email será utilizado para entrarmos em contato com você (Não será utilizado para fazer login)",
                 ),

                'button' => array('button' => "Salvar Email")
            ),
        ),
        
        'pessoal_referrer' => array(
            'cod'   =>'pessoal_referrer',
            'group' =>'pessoal',
            'title' =>'Programa de Afiliados',
            'icon'  =>'fa fa-exchange',
            'ordem' =>'6',
            'type'  => 'component', 
            'ref'   => 'usuario/referencia/codigo', 
            'method'=> 'show'
         ),
        /*'pessoal_dados' =>array(
            'cod'       => 'pessoal_dados', 
            'group'     => 'notify', 
            'title'     => 'Informações Pessoais',
            'ordem'     => '5',
            'icon'      => 'fa fa-user',
            'type'      => 'directdata', 
            'form_data' => array(
                'cpf' => array(
                    'name'     => 'Cpf',
                    'type'     => 'varchar',
                    'size'     => '16',
                    'especial' => 'cpf',
                    'display' => true,
                ),
                 'rg' => array(
                    'name'     => 'Rg',
                    'type'     => 'varchar',
                    'size'     => '32',
                    'grid'    => true,
                    'display' => true,
                ),
                 'nascimento' => array(
                    'name'     => 'Nascimento',
                    'type'     => 'date',
                    'grid'    => true,
                    'display' => true,
                ),

                'button' => array('button' => "Salvar Informações pessoais")
            ),
        ),*/
        'notify_conta' =>array(
            'cod'       => 'notify_conta', 
            'group'     => 'notify', 
            'title'     => 'Notificações da Conta',
            'ordem'     => '1',
            'icon'      => 'fa fa-user',
            'type'      => 'directdata', 
            'form_data' => array(
                'modificacao' => array(
                    'name'        => 'Alteração de Email e Senha (recomendado)',
                    'description' => 'Receber notificação por email se o meu email de acesso for alterado no site (Aumenta a segurança)',
                    'type'        => 'enum',
                    'default'     => 's',
                    'options'     => array(
                        's' => 'Receber',
                        'n' => 'Não'
                    ),
                    'notnull'     => true
                ),
                
                'novidades' => array(
                    'name'        => 'Mudanças no site',
                    'description' => 'Receber um email quando ocorrerem mudanças significativas no site (novas funcionalidades, remoção ou descontinuidade de produtos, novo design, etc)',
                    'type'        => 'enum',
                    'default'     => 's',
                    'options'     => array(
                        's' => 'Receber',
                        'n' => 'Não'
                    ),
                    'notnull'     => true
                ),

                'button' => array('button' => "Salvar Opção")
            ),
        ),
    );
}