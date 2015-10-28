<?php

App::uses('Model', 'Model');

class Colaborador extends AppModel {

  public $useTable = 'colaboradores';
  public $name = "Colaborador";
  public $validate = array(
    'nome' => array(
      'rule' => array('minLength', 3),
      'required' => true,
      'message' => 'Informe seu nome'
    ),
    'email' => array(
      'r0' => array(
        'rule' => 'email',
        'required' => true,
        'message' => 'Informe um endereço de e-mail válido'
      ),
      'r1' => array(
        'rule' => 'isUnique',
        'message' => 'Email já cadastrado. <a href="/Login/esquecisenha">Esqueceu sua senha?</a>'
      )
    ),
    'senha' => array(
      'rule' => array('minLength', 6),
      'message' => 'O campo senha deve contér no mínimo 6 caracteres'
    )
  );

  public function geraSenha() {
    $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 8; $i++) {
      $n = rand(0, $alphaLength);
      $pass[] = $alphabet[$n];
    }
    return md5(implode($pass)); //turn the array into a string    
  }
  
}
