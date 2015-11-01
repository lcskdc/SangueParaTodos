<?php

App::uses('Model', 'Model');

class DemandasCompartilhadas extends AppModel {

  public $name = 'DemandasCompartilhadas';
  public $useTable = "demandas_compartilhadas";
  
  public function getDemandasCompartilhadas($idColaborador) {
    $compartilhamentos = $this->find('all',array(
      'conditions' => array('colaborador_id' => $idColaborador, 'medalha_adquirida' => '0')
    ));
    return $compartilhamentos;
  }
  
  public function getCountDemandasCompartilhadas($idColaborador) {
    $c = $this->find('count',array('conditions' => array('colaborador_id' => $idColaborador, 'medalha_adquirida' => '0')));
    return max($c,0);
  }
  
  public function afterSave($create, $options = array()) {
    if($create) {
      $idColaborador = $this->data['DemandasCompartilhadas']['colaborador_id'];
      App::Import('Model','Medalha');
      App::Import('Model','ColaboradorMedalha');
      $Medalha = new Medalha();
      $ColaboradorMedalha = new ColaboradorMedalha();
      $ultimaMedalha = $ColaboradorMedalha->ultimaMedalhaColaborador($idColaborador,1);
      $demandasCompartilhadas = $this->getCountDemandasCompartilhadas($idColaborador);
      
      if(!$ultimaMedalha) {
        $primeiraMedalha = $ColaboradorMedalha->primeiraMedalhaTipo($idColaborador,1);
        if($demandasCompartilhadas>=$primeiraMedalha['Medalha']['pontuacao']) {
          $this->atribuiMedalha($idColaborador,$primeiraMedalha['Medalha']);
        }
      } else {
        $proximaMedalha = $ColaboradorMedalha->proximaMedalhaTipo($idColaborador,1);
        if($proximaMedalha) {
          if($demandasCompartilhadas>=$proximaMedalha['pontuacao']) {
            $this->atribuiMedalha($idColaborador,$proximaMedalha);
          }
        }
      }
    }
    
  }
  
  private function atribuiMedalha($idColaborador,$medalha) {
    $medalha_id = $medalha['id'];
    $ColaboradorMedalha = new ColaboradorMedalha();
    $medalhaColaborador['colaborador_id'] = $idColaborador;
    $medalhaColaborador['medalha_id'] = $medalha_id;
    $medalhaColaborador['pontuacao'] = $medalha['pontuacao'];
    $ColaboradorMedalha->save($medalhaColaborador);
    $sqlLimit = ($medalha['pontuacao']>0)?" LIMIT ".$medalha['pontuacao']:"";
    $sql = "UPDATE demandas_compartilhadas SET medalha_adquirida = $medalha_id WHERE medalha_adquirida = 0 AND colaborador_id = $idColaborador ORDER BY data ASC $sqlLimit";
    $this->query($sql);
  }
  
}
