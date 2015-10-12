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

    public function beforeFilter() {
      parent::beforeFilter();   
      $this->set('imagem', $this->Session->read("colaborador.imagem"));
      $this->set('mostraMensagem', $this->Session->check('colaborador.mostraMensagem') ? false : true);
      if($this->Session->check('sangue.restante')) {
        $this->set('evento_tempo_restante',$this->Session->read('sangue.restante'));
      }
      
      $this->loadModel('Gamification');
      if($this->Session->check('colaborador.id')) {
        $this->Session->write('sangue.pontos',$this->Gamification->pontuacao($this->Session->read('colaborador.id')));
      }
      
      $this->set('colaborador_pontuacao',$this->Session->check('sangue.pontos')?$this->Session->read('sangue.pontos'):0);
      $this->Session->write('colaborador.mostraMensagem', false);
      
      $topDoadores =$this->Gamification->topDoadores();
      if(count($topDoadores) > 0) {
        $this->set('topDoadores',$topDoadores);
      }
      
      $topDivulgadores = $this->Gamification->topDivulgadores();
      if(count($topDivulgadores)>0) {
        $this->set('topDivulgadores',$topDivulgadores);
      }
      
    }

}
