<?php

App::uses('Model', 'Model');

class TipoEvento extends AppModel {

  public $useTable = "tipoevento";

  public function buscaTiposEvento($sexo,$id=0) {
    $sexo = !in_array($sexo,array('U','M','F'))?'U':$sexo;
    
    $conds['order'] = array('TipoEvento.prazo_u ASC');
    if($id>0) {
      $conds['conditions'] = array('id' => $id);
    }
    $tipos = $this->find('all',$conds);
    
    foreach($tipos as $k => $tipo) {
      $prazo = $tipo['TipoEvento']['prazo_'.strtolower($sexo)];
      $result_tipos[] = array(
        'id' => $tipo['TipoEvento']['id'],
        'descricao' => $tipo['TipoEvento']['descricao'],
        'desc_msgs' => $tipo['TipoEvento']['desc_msgs'],
        'prazo' => $prazo
      );
    }
    if(count($result_tipos)==1) {
      return $result_tipos[0];
    } else {
      return $result_tipos;
    }
    
  }
  
}
