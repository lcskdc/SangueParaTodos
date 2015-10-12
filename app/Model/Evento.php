<?php

App::uses('Model', 'Model');

class Evento extends AppModel {

  public $validate = array(
    'id_colaborador' => array(
      'rule' => 'notEmpty',
      'required' => true,
      'message' => 'Erro interno'
    ),
    'id_evento' => array(
      'rule' => 'maiorQueZero',
      'required' => true,
      'message' => 'Evento inválido'
    ),
    'data' => array(
      'rule' => 'verificaDataValida',
      'required' => true,
      'message' => 'Data de validade inválida'
    )
  );

  public function maiorQueZero($check) {
    return $check['id_evento'] > 0;
  }

  public function verificaDataValida($check) {
    if (preg_match('/[0-9]{4}\-[0-9]{2}\-[0-9]{2}/', $check['data'])) {
      list($ano, $mes, $dia) = explode('-', $check['data']);
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
