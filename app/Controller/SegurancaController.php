<?php

set_time_limit(0);

use Cake\Model;

class SegurancaController extends AppController {

  public $helpers = array('Js' => array('Jquery', 'Ajax'));
  public $components = array('Captcha');

  public function privacidade() {
    $this->render("politica_de_privacidade");
  }
  
  public function termo() {
    $this->render("termos_de_uso");
  }
  
  public function captcha() {
    $this->layout = 'ajax';
    $this->Captcha = $this->Components->load('Captcha');
    $captcha = $this->Captcha->getQuestao();
    $this->Session->write('captcha.resposta',$captcha['resposta']);
    $this->set('questao',$captcha['questao']);
  }

}
