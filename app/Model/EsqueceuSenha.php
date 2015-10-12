<?php

App::uses('Model', 'Model');

class EsqueceuSenha extends AppModel {
  public $useTable = 'colaborador_esqueceu_senha';
  public $belongsTo = array(
    'Colaborador' => array(
      'className' => 'Colaborador',
      'foreignKey' => 'colaborador_id',
      'dependent' => false
    )
  );
  
  public function atualizaRegistrosAtivos($colaborador_id) {
    $this->query("UPDATE colaborador_esqueceu_senha SET valido = 'N' WHERE colaborador_id = $colaborador_id");
  }
  
}
