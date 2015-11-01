<?php

App::uses('Model', 'Model');

class Config extends AppModel {

  public $useTable = false;

  public function getConfSMS() {
    return array('conta' => 'lucaspo.rest', 'senha' => 'OrS13Mkev0');
  }
    
}
