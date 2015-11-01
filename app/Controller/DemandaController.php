<?php

use Cake\Model;

class DemandaController extends AppController {

  public $helpers = array('Js' => array('Jquery', 'Ajax'));

  public function index() {
    echo md5("9");
    echo '<hr />';
    echo getcwd();
    unlink(getcwd().'/img/demandas/'.md5('8').'.jpg');
  }

  public function lista() {
    $this->set('i', 0);
    $demandas = $this->Demanda->find('all', array('conditions' => array('Demanda.id_colaborador' => $this->Session->read('colaborador.id'))));
    $this->set('demandas', $demandas);
  }

  public function cadastro() {

    $msgs = $tipos_sangue = array();
    $idColaborador = 0;

    $this->loadModel('TipoSanguineo');
    $tipos_sanguineos = $this->TipoSanguineo->find('list', array('fields' => array('TipoSanguineo.descricao', 'TipoSanguineo.descricao')));
    $this->set('tiposSanguineos', $tipos_sanguineos);

    if ($this->Session->check('colaborador.id')) {
      $idColaborador = $this->Session->read('colaborador.id');
      $this->set('idColaborador', $this->Session->read('colaborador.id'));
    }

    if ($this->request->isPost()) {

      $nome = trim($this->request->data('nome'));
      $descricao = trim($this->request->data('descricao'));
      $instituicao = $this->request->data('instituicao');
      $doadores = $this->request->data('doadores');
      $validade = $this->request->data('validade');
      $local = $this->request->data('local');
      $idLocal = $this->request->data('id_local');
      $data_cadastro = date("Y-m-d H:i:s");
      $tipos_sangue = $this->request->data('tipo_sanguineo') ? $this->request->data('tipo_sanguineo') : array();

      if ($idColaborador > 0) {
        $email = $this->Session->read('colaborador.email');
        $nmUsuario = $this->Session->read('colaborador.nome');
      } else {
        $email = $this->request->data('email');
        $nmUsuario = $this->request->data('nmUsuario');
      }

      $lat = $lng = 0;
      if ($this->request->data('posicao') != "") {
        list($lat, $lng) = explode(",", $this->request->data('posicao'));
      }

      $data_validade = "";
      if ($validade != "") {
        $d = explode("/", $validade);
        $data_validade = $d[2] . '-' . $d[1] . '-' . $d[0];
      }

      $this->loadModel('TipoSanguineo');
      $tiposSanguineos = $this->TipoSanguineo->find('all');

      $demanda = array(
        'paciente' => $nome,
        'descricao' => $descricao,
        'id_local' => $idLocal,
        'doadores' => $doadores,
        'validade' => $data_validade,
        'id_colaborador' => $idColaborador,
        'instituicao' => $instituicao,
        'endereco' => $local,
        'latitude' => $lat,
        'longitude' => $lng,
        'data_cadastro' => $data_cadastro,
        'nmUsuario' => $nmUsuario,
        'tipos_sangue' => count($tipos_sangue)==count($tiposSanguineos)?'todos':implode(',', $tipos_sangue)
      );

      $this->Demanda->set($demanda);

      $this->set('nome', $nome);
      $this->set('descricao', $descricao);
      $this->set('doadores', $doadores);
      $this->set('validade', $validade);
      $this->set('local', $local);
      $this->set('email', $email);
      $this->set('posicao', ($lat != "" && $lng != "") ? ($lat . ',' . $lng) : "");
      $this->set('instituicao', $instituicao);
      $this->set('idLocal', $idLocal);
      $this->set('nmUsuario', $nmUsuario);
      $this->set('tipos_sangue', $tipos_sangue);

      $this->Session->write('colaborador.nmUsuario', $nmUsuario);

      $demandaValida = $this->Demanda->validates();

      if (!$idColaborador > 0) {
        $this->loadModel("Colaborador");
        if (Validation::email($email)) {
          $busca = $this->Colaborador->find('first', array('conditions' => array('Colaborador.email' => $email)));
          if ($busca) {
            $idColaborador = $busca['Colaborador']['id'];
            $this->Demanda->set('id_colaborador', $idColaborador);
          }
        }
        
        if ($idColaborador == 0) {
          $hash = md5(@mktime());
          $this->Colaborador->set('email',$email);
          $this->Colaborador->set('nome', $nmUsuario);
          $this->Colaborador->set('senha','inalterada');
          $this->Colaborador->set('ativo','A');
          $this->Colaborador->set('chave',$hash);
          
          if (!$this->Colaborador->validates()) {
            $errors = $this->Colaborador->validationErrors;
            foreach ($errors as $k => $v) {
              $msgs[] = $v[0];
            }
          } else if ($demandaValida) {
            $this->Colaborador->save();
            $idColaborador = $this->Colaborador->getLastInsertId();
            $this->Demanda->set('id_colaborador', $idColaborador);
          }
          
          $busca = $this->Colaborador->find('first', array('conditions' => array('Colaborador.id' => $idColaborador)));
          if($busca) {
            $this->enviaEmail($busca['Colaborador'],'cadastro');
          }
          
        }
      } else {
        $this->Demanda->set('validado', date('Y-m-h H:i:s'));
      }

      if (!$demandaValida) {
        $errors = $this->Demanda->validationErrors;
        foreach ($errors as $k => $v) {
          $msgs[] = $v[0];
        }
      }

      if (count($msgs) > 0) {
        //Manda os erros
        $this->set('erros', $msgs);
      } else {
        //Então salva a demanda
        $this->Demanda->save();

        $id = $this->Demanda->getLastInsertId();
        
        if ($this->Session->check('colaborador.imagem')) {
          $img = file_get_contents($this->Session->read('colaborador.imagem'));
          if ($img) {
            $handle = fopen(getcwd() . '/img/demandas/' . md5($id) . '.jpg', 'a');
            fwrite($handle, $img, strlen($img));
            fclose($handle);
          }
        } else {
            unlink(getcwd().'/img/demandas/'.md5($id).'.jpg');
            if (file_exists( getcwd().'/img/usuarios/'.md5($email).'.jpg' )) {
                copy(getcwd().'/img/usuarios/'.md5($email).'.jpg', getcwd().'/img/demandas/'.md5($id).'.jpg');
            } else {
                $imgDefault = 'http://sangueparatodos.com.br/img/avatar.jpg';
                $urlImagem = "http://www.gravatar.com/avatar/" . md5( strtolower(trim($email))) . "?d=" . urlencode($imgDefault) . "&s=40";
                $strImg = file_get_contents($urlImagem);
                if($strImg) {
                   $handle = fopen(getcwd().'/img/demandas/'.md5($id).'.jpg','a');
                  fwrite($handle,$strImg,strlen($strImg));
                  fclose($handle);
                }
            }
        }
        
        if ($this->Session->read('colaborador.id') > 0) {
          $this->montaMsgUsuario('OK', 'Demanda cadastrada com sucesso');
          $this->redirect("/Login/interno/");
        } else {
          $this->redirect("/Demanda/cadastrado/");
        }
      }
    } else {
      $this->set('nome', '');
      $this->set('descricao', '');
      $this->set('doadores', '');
      $this->set('validade', '');
      $this->set('local', '');
      $this->set('posicao', '');
      $this->set('instituicao', '');
      $this->set('idLocal', '');
      $this->set('idInstituicao', '');
      $this->set('email', '');
      $this->set('nmUsuario', '');
      $this->set('tipos_sangue', $tipos_sangue);
    }
  }

  public function excluir() {
    $this->autoRender = false;
    if ($this->request->isPost()) {
      if ($this->request->data('id') > 0) {
        $this->Demanda->read(null, $this->request->data('id'));
        $this->Demanda->set('excluido', date('Y-m-d H:i:s'));
        $this->Demanda->save();
      }
    }
  }

  public function cadastrado() {
    if ($this->Session->check('colaborador.id')) {
      $this->set('idColaborador', $this->Session->read('colaborador.id'));
    }
    if ($this->Session->check('colaborador.nmUsuario')) {
      $this->set('nmUsuario', $this->Session->read('colaborador.nmUsuario'));
    }
  }

  public function mapa() {
    $this->layout = "ajax";
  }

  public function compartilhado() {
      $this->layout = "ajax";
      $this->autoRender = false;
      $colaborador = $this->Session->read('colaborador.id');
      $demanda = $this->request['data']['demanda'];
      $this->loadModel("Gamification");
      $Gamification = new Gamification();
      
      $gm = $Gamification->find('all',array(
        'conditions' => array(
          'colaborador_id' => $colaborador,
          'demanda_id' => $demanda
        )
      ));
      
      if(!$gm && !empty($colaborador)) { //Se não houve resultado de compartilhamento desta demanda, então registra.
        $gm['colaborador_id'] = $colaborador;
        $gm['pontos'] = 50; //Após o primeiro acesso, passará para 5 pontos
        $gm['demanda_id'] = $demanda;
        $gm['tipo_id'] = 1; //compartilhado facebook
        $Gamification->save($gm);
      }
      
  }
  
  public function gera_identificacao() {
    $this->layout = 'ajax';
    $this->autoRender = false;
    $colaborador = $this->request['data']['colaborador_id'];
    $solicitacao = $this->request['data']['solicitacao_id'];
    $colaborador_id = $this->Session->read('colaborador.id');
    $result['chave'] = md5($colaborador.'@'.$solicitacao);
    
    $ref = $this->referer();
    $this->loadModel('DemandasCompartilhadas');
    $DemandasCompartilhadas = new DemandasCompartilhadas();    
    
    $r = $DemandasCompartilhadas->find('first',array('conditions'=>array('chave' => $result['chave'])));
    if(!$r) {
      $dc['chave']          = $result['chave'];
      $dc['solicitacao']    = $solicitacao;
      $dc['colaborador']    = $colaborador;
      $dc['colaborador_id'] = $colaborador_id;
      $dc['tipo_id']        = 1;
      $dc['href']           = $ref;
      $DemandasCompartilhadas->save($dc);
    }
    
    echo json_encode($result);
  }
  
  public function instituicoes() {
    $this->autoRender = false;
    $this->layout = "ajax";
    $query = $this->request->query('query');
    $this->loadModel('Local');
    $res = $this->Local->find('all', array('conditions' => array('Local.nome like ' => "%$query%"))
    );

    $results = array();
    if (count($res) > 0) {
      foreach ($res as $k => $Local) {

        if ($Local['Local']['cidade'] != "" && $Local['Local']['UF'] != "") {
          $strEndereco = $Local['Local']['endereco'] . ', ' . $Local['Local']['cidade'] . ', ' . $Local['Local']['UF'];
        } else if ($Local['Local']['cidade'] != "") {
          $strEndereco = $Local['Local']['endereco'] . ', ' . $Local['Local']['cidade'];
        } else {
          $strEndereco = $Local['Local']['endereco'];
        }

        $informacoes = array(
          'id' => $Local['Local']['id'],
          'nome' => $Local['Local']['nome'],
          'local' => $strEndereco,
          'posicao' => $Local['Local']['latitude'] . ',' . $Local['Local']['longitude']
        );
        $results[] = array('value' => $Local['Local']['nome'], 'data' => $Local['Local']['id'], 'informacoes' => json_encode($informacoes));
      }
    }

    $cidades = array(
      'query' => $query,
      'suggestions' => $results
    );
    echo json_encode($cidades);
  }
  
  public function denuncia() {
    $this->layout = 'ajax';
    $this->autoRender = false;
    if($this->request->isPost()) {
      $msg = "";
      $acao = $this->request['data']['acao'];
      $denuncia = $this->request['data']['observacao_denuncia'];
      $id_demanda = $this->request['data']['idDemanda'];
      $resposta_captcha = $this->request['data']['resposta_captcha'];
      
      if($this->Session->read('captcha.resposta') != $resposta_captcha) {
        $msg = "A resposta de verificação não está correta.";
      }
      $this->loadModel('DenunciaDemanda');
      $denuncia = array(
        'id_demanda' => $id_demanda,
        'acao' => $acao,
        'denuncia' => $denuncia
      );
      if($this->Session->read('colaborador.id')) {
        $denuncia['id_colaborador'] = $this->Session->read('colaborador.id');
      }
      
      $this->DenunciaDemanda->save($denuncia);
      $this->montaMsgUsuario('OK', 'Registramos a sua denúncia');
      
      if($msg!="") {
        echo json_encode(array('status'=>'erro','msg'=>$msg));
      } else {
        echo json_encode(array('status'=>'ok'));
      }
      
    }
  }
  
}
