<?php

App::uses('Model', 'Model');

class Demanda extends AppModel {

  public $belongsTo = array(
    'Local' => array(
      'className' => 'Local',
      'foreignKey' => 'id_local',
      'dependent' => false
    )
  );
  
  public $validate = array(
    'paciente' => array(
      'rule' => array('minLength', 3),
      'required' => true,
      'message' => 'O nome do paciente deve conter pelo menos 3 caracteres'
    ),
    'instituicao' => array(
      'rule' => array('minLength', 3),
      'required' => true,
      'message' => 'O nome do local para doação deve conter pelo menos 3 caracteres'
    ),
    'doadores' => array(
      'rule' => 'maiorQueZero',
      'required' => true,
      'message' => 'Número de doadores inválido'
    ),
    'validade' => array(
      'rule' => 'verificaDataValida',
      'required' => true,
      'message' => 'Data de validade inválida'
    ),
    'endereco' => array(
      'rule' => 'notEmpty',
      'required' => true,
      'message' => 'É necessário informar o endereço do local'
    ),
    'tipos_sangue' => array(
      'rule' => 'notEmpty',
      'required' => true,
      'message' => 'É necessário informar pelo menos um tipo de sangue'
    )
  );

  public function maiorQueZero($check) {
    return $check['doadores'] > 0;
  }

  public function verificaDataValida($check) {
    if (preg_match('/[0-9]{4}\-[0-9]{2}\-[0-9]{2}/', $check['validade'])) {
      list($ano, $mes, $dia) = explode('-', $check['validade']);
      $hj = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
      $validade = mktime(0, 0, 0, $mes, $dia, $ano);
      if ($hj > $validade) {
        return false;
      } else {
        return checkdate($mes, $dia, $ano);
      }
    } else {
      return false;
    }
  }

}
