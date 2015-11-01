<?php
App::uses('Model', 'Model');

class Medalha extends AppModel {
  public $name = "Medalha";
  public $useTable = 'medalhas';

  public function buscaMedalhaTipo($idColaborador, $tipo) {
    
    App::Import('Model','ColaboradorMedalha');
    $ColaboradorMedalha = new ColaboradorMedalha();
    $medalhasAdquiridas = $ColaboradorMedalha->find('all',array('conditions' => array(
      'colaborador_id' => $idColaborador,
      'Medalha.tipo_id' => $tipo
    )));
    $adquiridas = array();
    
    if($medalhasAdquiridas) {
      foreach($medalhasAdquiridas as $k => $v) {
        $adquiridas[] = $v['Medalha']['id'];
      }
    }
    
    if(count($adquiridas)>0) {
      $condicoes['conditions'] = array('tipo_id' => $tipo, 'NOT' => array('id' => $adquiridas));
    } else {
      $condicoes['conditions'] = array('tipo_id' => $tipo);
    }
    
    $condicoes['order'] = array('Medalha.id ASC');
    //print_r($condicoes);
    $medalha = $this->find('first',$condicoes);
    
    if($medalha) {
      return $medalha['Medalha'];
    }else{
      return null;
    }
  }
  
}
