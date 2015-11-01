<?php

App::uses('Model', 'Model');

class DenunciaDemanda extends AppModel {

  public $useTable = "denuncias_demandas";
  
  public $belongsTo = array(
    'Demanda' => array(
      'className' => 'Demanda',
      'foreignKey' => 'id_demanda',
      'dependent' => false
    )
  );
  

}
