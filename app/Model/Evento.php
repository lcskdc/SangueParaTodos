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
  

  public function getPrazo($colaborador_id) {
    $resultado = 0;
    $sql = "SELECT
              MAX(GREATEST(tipoevento.prazo-DATEDIFF(NOW(),evento.data),0)) as restante
            FROM eventos evento
              JOIN tipoevento tipoevento ON tipoevento.id = evento.id_evento
            WHERE evento.id_colaborador = $colaborador_id";
    $r = $this->query($sql);
    if($r){
      $resultado = $r[0][0]['restante'];
    }
    return $resultado;
  }
  
  public function getEventoPendente($colaborador_id, $evento_id, $idEventoCadastrado) {
    $sql = "SELECT
            GREATEST(tipoevento.prazo-DATEDIFF(NOW(),evento.data),0) as restante
            FROM eventos evento
              JOIN tipoevento tipoevento ON tipoevento.id = evento.id_evento
            WHERE evento.id_colaborador = $colaborador_id AND evento.id <> $idEventoCadastrado AND tipoevento.id = $evento_id ORDER BY evento.data DESC";
    return $this->query($sql);
    
  }
  
  public function maiorQueZero($check) {
    return $check['id_evento'] > 0;
  }

  public function verificaDataValida($check) {
    if (preg_match('/[0-9]{4}\-[0-9]{2}\-[0-9]{2}/', $check['data'])) {
      list($ano, $mes, $dia) = explode('-', $check['data']);
      $hj = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
      $validade = mktime(0, 0, 0, $mes, $dia, $ano);
      if ($validade > $hj) {
        return false;
      } else {
        return checkdate($mes, $dia, $ano);
      }
    } else {
      return false;
    }
  }
  
  public function getEventosUsuario($colaborador_id) {
    $sql = "SELECT
              DATE_FORMAT(evento.data,\"%d/%m/%Y\") as data,
              tipoevento.descricao,
              tipoevento.prazo,
              GREATEST(tipoevento.prazo-DATEDIFF(NOW(),evento.data),0) as restante,
              DATEDIFF(NOW(),evento.data) as tempo
            FROM eventos evento
              JOIN tipoevento tipoevento ON tipoevento.id = evento.id_evento
            WHERE evento.id_colaborador = $colaborador_id
            ORDER BY GREATEST(tipoevento.prazo-DATEDIFF(NOW(),evento.data),0) DESC";
    return $this->query($sql);
  }
  

}
