<?php

App::uses('Model', 'Model');

class Estado extends AppModel {

  public $useTable = 'estados';
  public $hasMany = array('Cidade' => array(
      'className' => 'Cidade',
      'foreignKey' => 'estado_id'
    )
  );

}
