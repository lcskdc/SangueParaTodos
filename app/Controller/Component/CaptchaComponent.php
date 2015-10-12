<?php
  App::uses('Component', 'Controller');

  class CaptchaComponent extends Component{
    
    private $tipos = array('+','-');
    
    public function getQuestao() {
      
      $tp = $this->tipos[rand(0,count($this->tipos)-1)];
      
      if($tp=='-') {
        $n2 = rand(1,30);
        $n1 = rand($n2,35+$n2);
      } else {
        $n1 = rand(1,30);
        $n2 = rand(1,30);
      }
      
      if($tp=="-") {
        $resposta = $n1-$n2;
      } else {
        $resposta = $n1+$n2;
      }
      
      $questao = "Qual o resultado de $n1 $tp $n2?";
      $retorno = $resposta>0?array('questao'=>$questao, 'resposta'=>$resposta):$this->getQuestao();
      return $retorno;
      
    }
    
    

  }