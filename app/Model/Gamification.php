<?php

App::uses('Model', 'Model');

class Gamification extends AppModel {
  public $useTable = 'colaborador_pontuacao';
  public $belongsTo = array(
    'Colaborador' => array(
      'className' => 'Colaborador',
      'foreignKey' => 'colaborador_id',
      'dependent' => false
    ),
    'ColaboradorTipoAcao' => array(
      'className' => 'ColaboradorTipoAcao',
      'foreignKey' => 'tipo_id',
      'dependent' => false
    )    
  );
  
  public function topDoadores() {
    $r = array();
    $sql = 'SELECT
              c.id, 
              c.nome as nome,
              c.email,
              SUM(p.pontos) as pontos,
              MAX(p.data) as ultima_data
            FROM colaborador_pontuacao p
              JOIN colaboradores c ON c.id = p.colaborador_id
            GROUP BY c.id, c.nome, c.email
            ORDER BY SUM(pontos) DESC';
    $res = $this->query($sql);
    foreach($res as $k => $v) {
      if(!empty($v['c']['id'])) {
        $img = file_exists(getcwd().'/img/usuarios/'.md5($v['c']['email']).'.jpg')?'/img/usuarios/'.md5($v['c']['email']).'.jpg':'/img/avatar.jpg';
        $r[] = array(
          'id' => $v['c']['id'],
          'img' => $img,
          'colaborador' => $v['c']['nome'],
          'pontos' => $v[0]['pontos'],
          'ultima_data' => $v[0]['ultima_data']
        );
      }
    }
    unset($res);
    return $r;
  }
  
  public function pontuacao($id_colaborador) {
    $pontos = 0;
    $sql = 'SELECT 
            SUM(p.pontos) as pontos
            FROM colaborador_pontuacao p
            WHERE p.colaborador_id = '.$id_colaborador;
    $res = $this->query($sql);
    foreach($res as $k => $v) {
      $pontos = $v[0]['pontos'];
    }
    return $pontos;
  }
  
  public function topDivulgadores() {
    $r = array();
    $sql = 'SELECT 
              c.id, 
              c.nome as nome,
              c.email,
              COUNT(dc.chave) as divulgacoes,
              MAX(dc.data) as ultima_data
            FROM demandas_compartilhadas dc
              JOIN colaboradores c ON c.id = dc.colaborador_id
            ORDER BY COUNT(dc.colaborador_id) DESC';
    $res = $this->query($sql);
    foreach($res as $k => $v) {
      if(!empty($v['c']['id'])) {
      $img = file_exists(getcwd().'/img/usuarios/'.md5($v['c']['email']).'.jpg')?'/img/usuarios/'.md5($v['c']['email']).'.jpg':'/img/avatar.jpg';
        $r[] = array(
          'id' => $v['c']['id'],
          'img' => $img,
          'colaborador' => $v['c']['nome'],
          'divulgacoes' => $v[0]['divulgacoes'],
          'ultima_data' => $v[0]['ultima_data']
        );
      }
    }
    unset($res);
    return $r;
  }
  
  public function isPontuado($id_colaborador, $tipo) {
    $r = $this->find('all',array('conditions' => array('colaborador_id' => $id_colaborador, 'tipo_id' => $tipo)));
    return count($r)>0;
  }

  public function getPontosSum($idColaborador, $tipo) {
    $total = $this->find('first',
      array(
        'fields' => array('SUM(pontos) as total'),
        'conditions' => array('colaborador_id' => $idColaborador, 'medalha_adquirida' => '0', 'tipo_id' => $tipo)
      )
    );
    return @(int) $total[0]['total'];
  }
  
  public function getPontosCount($idColaborador, $tipo) {
    $total = $this->find('count',
      array('conditions' => array('colaborador_id' => $idColaborador, 'medalha_adquirida' => '0', 'tipo_id' => $tipo))
    );
    return @(int) $total;
  }  
  
  public function afterSave($create, $options = array()) {
    if($create) {

      App::Import('Model','Medalha');
      App::Import('Model','ColaboradorMedalha');
      $tipo = $this->data['Gamification']['tipo_id'];
      $idColaborador = $this->data['Gamification']['colaborador_id'];
      
      $Medalha = new Medalha();
      $m = $Medalha->buscaMedalhaTipo($idColaborador,$tipo);
      
      if($tipo==2) { //Cadastro
        $ColaboradorMedalha = new ColaboradorMedalha();
        $this->atribuiMedalha($idColaborador,$m,$tipo);
      } else if($tipo==4) { //Doação de sangue
        $ColaboradorMedalha = new ColaboradorMedalha();
        if($m['pontuacao']==0 || ($m['pontuacao']>$this->getPontosCount($idColaborador,$tipo))) {
          $this->atribuiMedalha($idColaborador,$m,$tipo);
        }
      }
      
    }
  }
  
  public function atribuiMedalha($idColaborador, $medalha, $tipo) {
    $medalha_id = $medalha['id'];
    $ColaboradorMedalha = new ColaboradorMedalha();
    $medalhaColaborador['colaborador_id'] = $idColaborador;
    $medalhaColaborador['medalha_id'] = $medalha_id;
    $medalhaColaborador['pontuacao'] = $medalha['pontuacao'];
    $ColaboradorMedalha->save($medalhaColaborador);
    $sql = "UPDATE colaborador_pontuacao SET medalha_adquirida = $medalha_id WHERE medalha_adquirida = 0 AND tipo_id = $tipo AND colaborador_id = $idColaborador";
    $this->query($sql);
  }
  
  
}
