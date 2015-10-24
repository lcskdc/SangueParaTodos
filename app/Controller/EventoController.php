<?php

set_time_limit(0);

use Cake\Model;

class EventoController extends AppController {

  public $helpers = array('Js' => array('Jquery', 'Ajax'));
  
  public function index() {
    
  }

  public function lista_json() {
    $this->autoRender = false;
    $this->Evento->useTable = 'tipoevento';
    $tiposEvento = $this->Evento->find('all');
    echo json_encode($tiposEvento);
  }

  public function lista() {
    $this->set('i',0);
    $this->atualizaPrazoSessao();
    $eventos = $this->Evento->getEventosUsuario($this->Session->read('colaborador.id'));
    $this->set('eventos', $eventos);
  }

  public function cadastro() {
    
    $this->validaUsuarioLogado();
    $this->atualizaPrazoSessao();
    
    if ($this->request->isPost()) {
      
      $data = '';
      if (preg_match('/[0-9]{2}\/[0-9]{4}/', $this->request->data('validade'))) {
        $d = explode('/', $this->request->data('validade'));
        $data = $d[1] . '-' . $d[0] . '-01';
        $mk_data = mktime(0,0,0,$d[0],1,$d[1]);
      }

      $id_evento = $this->request->data('evento');
      $evento = array(
        'id_evento' => $id_evento,
        'data' => $data,
        'id_colaborador' => $this->Session->read('colaborador.id')
      );
      
      $msgGamification = "";

      if ($this->Evento->saveAll($evento, array('validate' => true))) {

        $idEventoCadastrado = $this->Evento->getLastInsertId();
        $sql = "UPDATE eventos SET ultimo = 'N' WHERE id_colaborador = ".$this->Session->read('colaborador.id')." AND ultimo = 'S' AND id <> $idEventoCadastrado";
        $this->Evento->query($sql);
        
        if($id_evento==3) {
          $evento_pendente = $this->Evento->getEventoPendente($this->Session->read('colaborador.id'), $id_evento, $idEventoCadastrado);
          if(count($evento_pendente)==0) {
            $msgGamification = "<br />Você ganhou 30 pontos";
            $this->loadModel('Gamification');
            $gm = new Gamification();
            $rg_doacao['colaborador_id'] = $this->Session->read('colaborador.id');
            $rg_doacao['pontos'] = 30; //
            $rg_doacao['tipo_id'] = 4;
            $gm->save($rg_doacao);
          }
        }
        
        $this->loadModel('TipoEvento');
        $tpEvento = $this->TipoEvento->find('first',array('conditions' => array('id' => $id_evento)));
        $nome = $this->Session->read('colaborador.nome');
        $this->montaMsgUsuario('OK', "Obrigado $nome, registramos o evento.<br />Decorrido o prazo deste evento, lhe comunicaremos para que você possa realizar uma nova doação e salvar vidas.".$msgGamification);
        $this->redirect("/Login/interno/");
      } else {
        $errors = $this->Evento->validationErrors;
        foreach ($errors as $k => $v) {
          $msgs[] = $v[0];
        }
        $this->set('msg', $msgs);
      }

    }

    if($this->request->isPost()) {
      $selecionado = $this->request->data('validade');
    }else {
      $selecionado = date('m/Y');
    }
    
    $this->set('meses',$this->mostra_meses(3));
    $this->set('selecionado',$selecionado);
    
    $this->loadModel("TipoEvento");
    $tipos = $this->TipoEvento->find('all');
    $this->set('tipos', $tipos);
    
    
  }

  public function excluir() {
    
    $this->validaUsuarioLogado();
    
    $this->autoRender = false;
    if ($this->request->isPost()) {
      if ($this->request->data('id') > 0) {
        $this->Demanda->read(null, $this->request->data('id'));
        $this->Demanda->set('excluido', 'S');
        $this->Demanda->save();
      }
    }
  }
  
  public function atualizaPrazoSessao() {
    $prazo = $this->Evento->getPrazo($this->Session->read('colaborador.id'));
    $this->Session->write('sangue.restante',$prazo);
  }
  
  public function mostra_meses($tempo_evento_maximo) {
    $arr_meses = array('Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro');
    $arr_datas = array();
    for($i=0;$i<=$tempo_evento_maximo-1;$i++) {
      $mk = mktime(0,0,0,date('m')-$i,1,date('Y'));
      array_unshift($arr_datas,
        array('mes' => date('m',$mk), 'mes_ext' => $arr_meses[date('m',$mk)-1], 'ano' => date('Y',$mk))
      );
    }
    return $arr_datas;
  }

}
