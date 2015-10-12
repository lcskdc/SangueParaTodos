<?php

App::uses('Model', 'Model');

class Pais extends AppModel {

  public $useTable = 'pais';
  public $hasMany = array('Estado' => array(
      'className' => 'Estado',
      'foreignKey' => 'pais_id'
    )
  );

}
