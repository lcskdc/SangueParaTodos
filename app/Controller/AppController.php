<?php

/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {

  private $classes = array('OK' => 'alert-success', 'ERRO' => 'alert-error');
  private $inDebug = true;
  
  public function beforeFilter() {
    parent::beforeFilter();

    if ($this->Session->check('colaborador.msgUsuario')) {
      $this->set('msgUsuario', unserialize($this->Session->read('colaborador.msgUsuario')));
      $this->apagaMsgUsuario();
    }

    $this->set('imagem', $this->Session->read("colaborador.imagem"));
    $this->set('mostraMensagem', $this->Session->check('colaborador.mostraMensagem') ? false : true);
    
    if($this->Session->check('colaborador.mostra_validacao')) {
      $this->set('mostra_validacao','N');
    } else {
      $this->Session->write('colaborador.mostra_validacao','S');
      $this->set('mostra_validacao','S');
    }
    
    if ($this->Session->check('sangue.restante')) {
      $this->set('evento_tempo_restante', $this->Session->read('sangue.restante'));
    }

    $this->loadModel('Gamification');
    if ($this->Session->check('colaborador.id')) {
      $this->Session->write('sangue.pontos', $this->Gamification->pontuacao($this->Session->read('colaborador.id')));
    }

    $this->set('colaborador_pontuacao', $this->Session->check('sangue.pontos') ? $this->Session->read('sangue.pontos') : 0);
    $this->Session->write('colaborador.mostraMensagem', false);

    $topDoadores = $this->Gamification->topDoadores();
    if (count($topDoadores) > 0) {
      $this->set('topDoadores', $topDoadores);
    }

    $topDivulgadores = $this->Gamification->topDivulgadores();
    if (count($topDivulgadores) > 0) {
      $this->set('topDivulgadores', $topDivulgadores);
    }
  }

  public function montaMsgUsuario($status, $msg, $template = null, $classe = null) {
    if (!empty($status) && !empty($msg)) {
      $msg = array(
        'status' => $status,
        'classe' => empty($classe) ? $this->classes[$status] : $classe,
        'msg' => $msg,
        'template' => $template
      );
      $this->Session->write('colaborador.msgUsuario', serialize($msg));
    }
  }

  public function validaUsuarioLogado() {
    if (!$this->Session->check('colaborador.id')) {
      $this->redirect('/Login/');
    }
  }

  public function apagaMsgUsuario() {
    $this->Session->delete('colaborador.msgUsuario');
  }

  public function enviaEmail($colaborador, $template, $msg=null) {
    App::uses('CakeEmail', 'Network/Email');
    $server = "www.sangueparatodos.com.br";
    //$server = "localhost:9090";
    $Email = new CakeEmail('gmail');
    $Email->from(array('lcskdc@gmail.com' => 'Sangue para todos'));
    $Email->to($colaborador['email']);
    $Email->subject('Validação de conta');
    
    $msgs_templates = array(
      'cadastro' => "Olá __nome__, obrigado pelo seu cadastro.\n\nÉ necessário que você valide seu cadastro através do link abaixo.\n\nhttp://__server__/Login/valida_cadastro/?chave=__chave__",
      'esqueceusenha' => "Olá __nome__, acesse o link abaixo para redefinir sua senha.\n\n__server__/Login/redefinir_senha?hash=__chave__\n\nCaso não tenha solicitado, por favor desconsidere este e-mail."
    );

    $msg = ($msg==null)?$msgs_templates[$template]:$msg;
    $replaces = array(
      '__nome__' => $colaborador['nome'],
      '__server__' => $server,
      '__chave__' => $colaborador['chave']
    );

    foreach ($replaces as $k => $v) {
      $msg = str_replace($k, $v, $msg);
    }

    $Email->send($msg);
  }
  
  public function debug($msg) {
    if($this->inDebug==true) {
      echo $msg;
    }
  }

}
