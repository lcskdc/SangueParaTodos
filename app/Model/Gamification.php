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
  
}
