<?php 

class usuario_enderecoData extends \classes\Model\DataModel{
    
    protected $hasFeatures = true;
    public $dados  = array(
         'cod' => array(
	    'name'     => 'Código',
	    'type'     => 'int',
	    'pkey'    => true,
	    'ai'      => true,
	    'grid'    => true,
	    'display' => true,
	    'private' => true
        ),
         'login' => array(
	    'name'     => 'Usuário',
	    'type'     => 'int',
	    'size'     => '11',
	    'notnull' => true,
	    'grid'    => true,
	    'display' => true,
            'especial' => 'autentication',
            'autentication' => array('needlogin' => true),
	    'fkey' => array(
	        'model' => 'usuario/login',
	        'cardinalidade' => '1n',
	        'keys' => array('cod_usuario', 'user_name'),
	    ),
        ),
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
	    'name'     => 'Rua',
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
         'padrao' => array(
	    'name'     => 'Padrão',
	    'type'     => 'enum',
	    'default' => 'n',
	    'options' => array(
	    	's' => 's',
	    	'n' => 'n',
	    ),
            'feature' => 'USUARIO_MULTI_ADRESS',
	    'notnull' => true,
	    'grid'    => true,
	    'display' => true,
        ),
	    'button'     => array('button' => 'Gravar Endereço')
    );
    
    public function getDados() {
        if(false === getBoleanConstant('USUARIO_MULTI_ADRESS')) {
            $this->dados['login']['unique'] = array('model'=>'usuario/endereco');
        }
        return parent::getDados();
    }
}