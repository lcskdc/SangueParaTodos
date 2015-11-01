<?php
App::uses('Model','Model');
App::uses('Medalha', 'Medalha');

class ColaboradorMedalha extends AppModel {
  
  public $name = 'ColaboradorMedalha';
  public $useTable = 'colaborador_medalhas';
  public $belongsTo = 'Medalha';
  
  public function ultimaMedalhaColaborador($idColaborador, $tipo) {
    $c = $this->find('first',array('conditions' => array('colaborador_id' => $idColaborador, 'Medalha.tipo_id' => $tipo), 'order' => 'ColaboradorMedalha.id DESC'));
    return $c;
  }
  
  public function proximaMedalhaTipo($idColaborador, $tipo) {
    $c = $this->find('first',array('conditions' => array('colaborador_id' => $idColaborador, 'Medalha.tipo_id' => $tipo), 'order' => 'ColaboradorMedalha.id DESC'));
    if($c) {
      $idProximoMedalha = $c['Medalha']['medalha_id'];
      $Medalha = new Medalha();
      $medalha = $Medalha->find('first', array('conditions' => array('id' => $idProximoMedalha)));
    }else{
      $medalha = $this->primeiraMedalhaTipo($idColaborador, $tipo);
    }
    if($medalha) {
      return $medalha['Medalha'];
    } else {
      return null;
    }
  }
  
  public function primeiraMedalhaTipo($idColaborador, $tipo) {
    $Medalha = new Medalha();
    $medalha = $Medalha->find('first', array('conditions' => array('Medalha.tipo_id' => $tipo),'order' => 'Medalha.id ASC'));
    return $medalha;
  }
  
  
  public function medalhasColaborador($idColaborador, $tipo=null) {
    $c = $this->find('all',array('conditions' => array('colaborador_id' => $idColaborador), 'order' => 'ColaboradorMedalha.id ASC'));
    return $c;
  }
  

  
  
}
