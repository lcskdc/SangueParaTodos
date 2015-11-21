<?php

App::uses('CakeTime', 'Utility');
App::uses('CakeEmail', 'Network/Email');

class MedalhaController extends AppController {

  public $helpers = array('Js' => array('Jquery', 'Ajax'));

  public function index() {
    
  }

  public function lista() {
    
    /*
    $this->loadModel('DemandasCompartilhadas');
    $chave = rand(0,19999);
    $demanda_compartilhada['chave'] = md5($chave);
    $demanda_compartilhada['solicitacao'] = '1';
    $demanda_compartilhada['colaborador'] = '41';
    $demanda_compartilhada['colaborador_id'] = '51';
    $demanda_compartilhada['data'] = '2015-09-06 12:07:28';
    $demanda_compartilhada['tipo_id'] = '1';
    $demanda_compartilhada['medalha_adquirida'] = 'N';
    $this->DemandasCompartilhadas->save($demanda_compartilhada);
    */
    
    //echo '<pre>',print_r($this->Session->read('colaborador')),'</pre>';
    
    $this->validaUsuarioLogado();
    
    $this->loadModel("Gamification");
    $gm = new Gamification();

    $pontos = $gm->find('all', array('conditions' => array(
      'colaborador_id' => $this->Session->read('colaborador.id')
      ),'order' => array('Gamification.data DESC'))
    );
    
    $this->loadModel('ColaboradorMedalha');
    $this->loadModel('DemandasCompartilhadas');
    
    $idColaborador = $this->Session->read('colaborador.id');
    $compartilhamentos = $this->DemandasCompartilhadas->getDemandasCompartilhadas($idColaborador);
    $proximaMedalhaCompartilhamento = $this->ColaboradorMedalha->proximaMedalhaTipo($idColaborador,1);
    $medalhas = $this->ColaboradorMedalha->medalhasColaborador($idColaborador);
    
    //$this->loadModel('Medalha');
    //$this->Gamification->getPontosCount($idColaborador, 2);
    //$pontuacao['colaborador_id'] = $idColaborador;
    //$pontuacao['pontos'] = 100;
    //$pontuacao['tipo_id'] = 2;
    //$this->Gamification->save($pontuacao);
    
    $this->set('tiposocial',$this->Session->read('colaborador.tipo_social'));
    $this->set('compartilhamentos',$compartilhamentos);
    $this->set('proximaMedalhaCompartilhamento',$proximaMedalhaCompartilhamento);
    $this->set('ncomp',count($compartilhamentos));
    $this->set('medalhas',$medalhas);
    $this->set('lst_pontos', $pontos);
    
  }

  public function ranking() {
    $this->layout = 'ajax';
    $this->loadModel('Gamification');
    $gm = new Gamification();
    $sql = 'SELECT'
        . ' c.nome as nome,'
        . ' p.colaborador_id as id,'
        . ' SUM(p.pontos) as pontos,'
        . ' MAX(p.data) as ultima_data'
        . ' FROM colaborador_pontuacao p'
        . ' JOIN colaboradores c ON c.id = p.colaborador_id'
        . ' ORDER BY SUM(pontos) DESC';
    $res = $gm->query($sql);

    $regs = array();
    foreach ($res as $k => $v) {
      $reg = array(
        'nome' => $v['c']['nome'],
        'pontos' => $v[0]['pontos'],
        'colaborador_id' => $v['p']['id'],
        'data' => $v[0]['ultima_data']
      );
      $regs[] = $reg;
    }

    $this->set('lst_ranking', $regs);
  }

  public function envia_msg_doadores() {

    $this->layout = 'ajax';
    $this->autoRender = false;

    $this->loadModel('ControleEnvio');
    /* Consulta antiga
      $sql = "SELECT evento.id_colaborador, evento.data, colaborador.nome, tipo.prazo, tipo.descricao, tipo.desc_msgs, colaborador.email, colaborador.telefone, colaborador.receber_sms, controle_envio.ultimo_envio, DATEDIFF(NOW(),evento.data) AS dias
      FROM eventos evento
      JOIN tipoevento tipo ON tipo.id = evento.id_evento
      JOIN colaboradores colaborador ON colaborador.id = evento.id_colaborador
      LEFT JOIN controle_envio controle_envio ON controle_envio.id_colaborador = evento.id_colaborador AND controle_envio.ultimo = 'S'
      WHERE
      evento.ultimo = 'S' AND
      tipo.prazo - DATEDIFF(NOW(),evento.data) <= 0 AND
      (controle_envio.ultimo_envio IS NULL OR DATEDIFF(NOW(),controle_envio.ultimo_envio) >= 15)";
     */
    $sql = "SELECT e1.id_colaborador, e1.data, c1.nome, c1.email, c1.telefone, c1.receber_sms, e1.dias as prazo, tipo.descricao, tipo.desc_msgs, ce1.ultimo_envio, DATEDIFF(NOW(),e1.data) AS dias
            FROM eventos e1
              JOIN tipoevento tipo ON tipo.id = e1.id_evento
              JOIN colaboradores c1 ON c1.id = e1.id_colaborador
              LEFT JOIN controle_envio ce1 ON ce1.id_colaborador = e1.id_colaborador AND ce1.ultimo = 'S'
            WHERE
                e1.ultimo = 'S' AND
                e1.dias - DATEDIFF(NOW(),e1.data) <= 0 AND
                (ce1.ultimo_envio IS NULL OR DATEDIFF(NOW(),ce1.ultimo_envio) >= 15)
            UNION ALL
            SELECT c2.id, NOW() AS data, c2.nome, c2.email, c2.telefone, c2.receber_sms, NULL as prazo, 'NOVO' as descricao, NULL as desc_msgs, NULL as ultimo_envio, 0 as dias
            FROM colaboradores c2
            WHERE
                     NOT EXISTS (SELECT e2.id_colaborador FROM eventos e2 WHERE e2.id_colaborador = c2.id) AND
                     NOT EXISTS (SELECT ce2.id_colaborador FROM controle_envio ce2 WHERE ce2.id_colaborador = c2.id)";
    $lista = $this->ControleEnvio->query($sql);

    foreach ($lista as $k => $Envio) {
      $dt_evento = CakeTime::format($Envio[0]['data'], '%d/%m/%Y');
      $nomes = explode(' ', $Envio[0]['nome']);

      if ($Envio[0]['descricao'] == 'NOVO') {
        $msg = "Oi " . substr($nomes[0], 0, 7) . ", uma nova doação espera por você.\n\nPortal Sangue Para Todos";
      } else {
        $msg = "Oi " . substr($nomes[0], 0, 7) . ", uma nova doação espera por você.\n\nFaz " . $Envio[0]['dias'] . " dias que você registrou um evento em nosso portal.\n\nPortal Sangue Para Todos";
      }

      $telefone = $Envio[0]['telefone'];
      $id_colaborador = $Envio[0]['id_colaborador'];

      $sql = "UPDATE controle_envio SET ultimo = 'N' WHERE id_colaborador = $id_colaborador AND ultimo = 'S'";
      $this->ControleEnvio->query($sql);

      $receber_sms = $Envio[0]['receber_sms'];

      $ce['id_colaborador'] = $id_colaborador;
      $ce['envio_email'] = 'S';
      $ce['envio_sms'] = $receber_sms;
      $this->ControleEnvio->save($ce);
      $id = $this->ControleEnvio->getLastInsertId();

      if ($receber_sms == 'S') {
        if (strlen($telefone) == 10) {
          /** Se enviou SMS, grava o status do envio * */
          $status = $this->envia_sms($msg, $telefone, $id);
          $this->ControleEnvio->read(null, $id);
          $this->ControleEnvio->set('sms_enviado', $status);
          $this->ControleEnvio->save();
          echo "Envio de SMS para $telefone: <br />$msg<hr />";
        }
      }

      if ($Envio[0]['descricao'] == 'NOVO') {
        $msg = "Oi " . substr($nomes[0], 0, 7) . ", uma nova doação espera por você.\n\nCadastre suas ações através do portal Sangue para todos e nós iremos avisar quando você estiver apto a doar e salvar mais vidas!\n\nAcesse http://www.sangueparatodos.com.br/";
      } else {
        $msg = "Oi " . substr($nomes[0], 0, 7) . ", uma nova doação espera por você.\n\nFaz " . $Envio[0]['dias'] . " dias que você registrou um evento em nosso portal.\n\nAcesse http://www.sangueparatodos.com.br/";
      }

      $Email = new CakeEmail('gmail');
      $Email->from(array('lcskdc@gmail.com' => 'Sangue para todos'));
      $Email->to($Envio[0]['email']);
      $Email->subject('Você pode salvar até quatro vidas');
      $Email->send($msg);
      echo "Envio de mensagem para " . $Envio[0]['email'] . ": <br />$msg<br /><hr />";
    }
  }

  public function envia_email_doacoes2() {

    $this->layout = 'ajax';
    $this->autoRender = false;

    $this->loadModel('ControleEnvio');
    $sql = "SELECT e1.id_colaborador, e1.data, c1.nome, c1.email, c1.telefone, c1.receber_sms, e1.dias as prazo, tipo.descricao, tipo.desc_msgs, ce1.ultimo_envio, DATEDIFF(NOW(),e1.data) AS dias
            FROM eventos e1
              JOIN tipoevento tipo ON tipo.id = e1.id_evento
              JOIN colaboradores c1 ON c1.id = e1.id_colaborador
              LEFT JOIN controle_envio ce1 ON ce1.id_colaborador = e1.id_colaborador AND ce1.ultimo = 'S'
            WHERE
                e1.ultimo = 'S' AND
                e1.dias - DATEDIFF(NOW(),e1.data) <= 0 AND
                (ce1.ultimo_envio IS NULL OR DATEDIFF(NOW(),ce1.ultimo_envio) >= 15)
            UNION ALL
            SELECT c2.id, NOW() AS data, c2.nome, c2.email, c2.telefone, c2.receber_sms, NULL as prazo, 'NOVO' as descricao, NULL as desc_msgs, NULL as ultimo_envio, 0 as dias
            FROM colaboradores c2
            WHERE
                     NOT EXISTS (SELECT e2.id_colaborador FROM eventos e2 WHERE e2.id_colaborador = c2.id) AND
                     NOT EXISTS (SELECT ce2.id_colaborador FROM controle_envio ce2 WHERE ce2.id_colaborador = c2.id)";
    $lista = $this->ControleEnvio->query($sql);
    print_r($lista);

    foreach ($lista as $k => $Envio) {
      $dt_evento = CakeTime::format($Envio[0]['data'], '%d/%m/%Y');
      $nomes = explode(' ', $Envio[0]['nome']);

      if ($Envio[0]['descricao'] == 'NOVO') {
        $msg = "Oi " . substr($nomes[0], 0, 7) . ", uma nova doação espera por você.\n\nCadastre suas ações através do portal Sangue para todos e nós iremos avisar quando você estiver apto a doar e salvar mais vidas!\n\nAcesse http://www.sangueparatodos.com.br/";
      } else {
        $msg = "Oi " . substr($nomes[0], 0, 7) . ", uma nova doação espera por você.\n\nFaz " . $Envio[0]['dias'] . " dias que você registrou um evento em nosso portal.\n\nAcesse http://www.sangueparatodos.com.br/";
      }

      $telefone = $Envio[0]['telefone'];
      $id_colaborador = $Envio[0]['id_colaborador'];

      $sql = "UPDATE controle_envio SET ultimo = 'N' WHERE id_colaborador = $id_colaborador AND ultimo = 'S'";
      $this->ControleEnvio->query($sql);

      $receber_sms = $Envio[0]['receber_sms'];

      $ce['id_colaborador'] = $id_colaborador;
      $ce['envio_email'] = 'S';
      $ce['envio_sms'] = $receber_sms;
      $this->ControleEnvio->save($ce);
      $id = $this->ControleEnvio->getLastInsertId();

      //echo '>>>'.$id;

      if ($receber_sms == 'S') {
        if (strlen($telefone) == 10) {
          /** Se enviou SMS, grava o status do envio * */
          $status = $this->envia_sms($msg, $telefone, $id);
          $this->ControleEnvio->read(null, $id);
          $this->ControleEnvio->set('sms_enviado', $status);
          $this->ControleEnvio->save();
        }
      }

      if ($Envio[0]['descricao'] == 'NOVO') {
        $msg = "Oi " . substr($nomes[0], 0, 7) . ", uma nova doação espera por você.\n\nCadastre suas ações através do portal www.sangueparatodos.com.br e nós iremos avisar quando você estiver apto a doar e salvar mais vidas!\n\nAcesse http://www.sangueparatodos.com.br/";
      } else {
        $msg = "Oi " . substr($nomes[0], 0, 7) . ", uma nova doação espera por você.\n\nFaz " . $Envio[0]['dias'] . " dias que você " . $Envio[0]['desc_msgs'] . ".\n\nAcesse http://www.sangueparatodos.com.br/";
      }

      $Email = new CakeEmail('gmail');
      $Email->from(array('lcskdc@gmail.com' => 'Sangue para todos'));
      $Email->to($Envio[0]['email']);
      $Email->subject('Você pode salvar até quatro vidas');
      $Email->send($msg);
    }
  }

  public function teste() {
    $this->autoRender = false;
    $this->layout = 'ajax';
    $status = $this->envia_sms('Ola esta mensagem é teste','5197230660',1);
    echo '>>>'.$status.'<br />';
  }

  
  private function envia_sms($msg, $telefone, $id) {
    App::import("Vendor", "zenvia", array("file" => "zenvia/human_gateway_client_api/HumanClientMain.php"));

    $this->loadModel('Config');
    $config = $this->Config->getConfSMS();

    $sender = new HumanSimpleSend($config['conta'], $config['senha']);
    $message = new HumanSimpleMessage(utf8_decode($msg), '55' . $telefone, "_hide", $id);
    $response = $sender->sendMessage($message);
    $statusEnvio = $response->getCode() . " - " . $response->getMessage();
    $response = $sender->queryStatus($id);
    $statusEnvio = $response->getCode() . "-" . $response->getMessage();
    return $statusEnvio;
  }

}
