<?php

class usuario_promocodData extends \classes\Model\DataModel{
    
    protected $dados  = array(
        
         'cod' => array(
	    'name'     => 'Código Promocional',
	    'type'     => 'varchar',
	    'size'     => '128',
	    'pkey'    => true,
	    'grid'    => true,
	    'display' => true
        ),
        
        'dt_inicio' => array(
	    'name'        => 'Data de Início',
	    'type'        => 'datetime',
            'display'     => true,
            'grid'        => true,
            'description' => 'Data de início da promoção'
        ),
        
        'dt_termino' => array(
	    'name'        => 'Data de Término',
	    'type'        => 'datetime',
            'display'     => true,
            'grid'        => true,
            'description' => 'Data de término da promoção'
        ),
        
        'max_cadastros' => array(
	    'name'        => 'Máximo de cadastro',
	    'type'        => 'int',
            'size'        => '8',
            'display'     => true,
            'grid'        => true,
            'description' => 'Número máximo de cadastros na promoção (EX: limitar a promoção para os 50 primeiros usuários)'
        ),
        
        'status' => array(
            'name'     => 'Status',
            'type'     => 'enum',
            'display'  => true,
            'default'  => 'andamento',
            'grid'     => true,
            'options'  => array(
                'andamento'   => "Em andamento", 
                'concluido'   => "Concluído",
            ),
            'notnull'  => true
       	 ),
        
        'users' => array(
	    'name'    => 'Usuários desta promoção',
	    'display' => true,
            'especial'=> 'hide',
            'fkey'    => array(
                'refmodel'      => 'usuario/promocod',
	        'model'         => 'usuario/promocod/promouser',
	        'cardinalidade' => 'n1',
	        'keys'          => array('cod_usuario', 'dt_insc'),
	    ),
        ),
        
        'button' => array(
            'button' => "Salvar Promoção",
        )
    );
    
}