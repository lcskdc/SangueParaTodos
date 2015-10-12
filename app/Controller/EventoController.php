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
    $sql = 'SELECT
              DATE_FORMAT(evento.data,"%d/%m/%Y") as data,
              tipoevento.descricao,
              tipoevento.prazo,
              GREATEST(tipoevento.prazo-DATEDIFF(NOW(),evento.data),0) as restante,
              DATEDIFF(NOW(),evento.data) as tempo
            FROM eventos evento JOIN tipoevento tipoevento ON tipoevento.id = evento.id_evento WHERE evento.id_colaborador = ' . $this->Session->read('colaborador.id') . ' ORDER BY evento.data DESC';
    $eventos = $this->Evento->query($sql);
    $this->set('eventos', $eventos);
  }

  public function cadastro() {
    
    if ($this->request->isPost()) {
      
      $data = '';
      if (preg_match('/[0-9]{2}\/[0-9]{2}\/[0-9]{4}/', $this->request->data('validade'))) {
        $d = explode('/', $this->request->data('validade'));
        $data = $d[2] . '-' . $d[1] . '-' . $d[0];
      }

      $id_evento = $this->request->data('evento');
      $evento = array(
        'id_evento' => $id_evento,
        'data' => $data,
        'id_colaborador' => $this->Session->read('colaborador.id')
      );

      if ($this->Evento->saveAll($evento, array('validate' => true))) {

        $idEventoCadastrado = $this->Evento->getLastInsertId();
        $sql = "UPDATE eventos SET ultimo = 'N' WHERE id_colaborador = ".$this->Session->read('colaborador.id')." AND ultimo = 'S' AND id <> $idEventoCadastrado";
        $this->Evento->query($sql);
        
        if($id_evento==3) {
          $sql = 'SELECT
                  GREATEST(tipoevento.prazo-DATEDIFF(NOW(),evento.data),0) as restante
                  FROM eventos evento
                    JOIN tipoevento tipoevento ON tipoevento.id = evento.id_evento
                  WHERE evento.id_colaborador = ' . $this->Session->read('colaborador.id') . ' AND evento.id <> ' . $idEventoCadastrado . ' AND tipoevento.id = '.$id_evento.' ORDER BY evento.data DESC';
          $evento_pendente = $this->Evento->query($sql);

          if(count($evento_pendente)==0) {
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
        
        $prazo = $tpEvento['TipoEvento']['prazo'];
        $this->Session->write('sangue.restante',$prazo);
        
        $this->Session->write('colaborador.msgUsuario', serialize(array('tipo' => 'OK', 'msg' => array('Evento registrado'))));
        $this->redirect("/Login/interno/");
      } else {
        $errors = $this->Evento->validationErrors;
        foreach ($errors as $k => $v) {
          $msgs[] = $v[0];
        }
        $this->set('msg', $msgs);
      }

    }

    $this->loadModel("TipoEvento");
    $tipos = $this->TipoEvento->find('all');
    $this->set('tipos', $tipos);
  }

  public function excluir() {
    $this->autoRender = false;
    if ($this->request->isPost()) {
      if ($this->request->data('id') > 0) {
        $this->Demanda->read(null, $this->request->data('id'));
        $this->Demanda->set('excluido', 'S');
        $this->Demanda->save();
      }
    }
  }

}
