<?php

set_time_limit(0);

use Cake\Model;

class SegurancaController extends AppController {

  public $helpers = array('Js' => array('Jquery', 'Ajax'));

  public function privacidade() {
    $this->render("politica_de_privacidade");
  }
  
  public function termo() {
    $this->render("termos_de_uso");
  }

}
