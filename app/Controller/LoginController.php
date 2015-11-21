<?php

ini_set('display_errors', 'on');
error_reporting(E_ALL);

class LoginController extends AppController {

  public $helpers = array('Js' => array('Jquery', 'Ajax', 'Time'));
  public $components = array('Captcha');

  public function index() {
    $host = $_SERVER['HTTP_HOST'];
    if(substr($host,0,22) == 'sangueparatodos.com.br') {
      $this->redirect('http://www.sangueparatodos.com.br/Login/');
    }
  }

  public function esquecisenha() {
    $msg = $email = "";
    $email_enviado = false;
    if(isset($this->request->data['email'])) {
      $resposta = $this->request->data['resposta'];
      $email = $this->request->data['email'];
      if($resposta != $this->Session->read('captcha.resposta')) {
        $this->set('msg',"A resposta informada está incorreta.");
      } else {
        $this->loadModel('Colaborador');
        $colaborador = $this->Colaborador->find(
          'first',
          array('conditions' => array('email' => $email))
        );
        if(!$colaborador) {
          $this->set('msg',"Verifique se o e-mail digitado está correto.");
        } else {
          $this->loadModel('EsqueceuSenha');
          $colaborador_id = $colaborador['Colaborador']['id'];
          $this->EsqueceuSenha->atualizaRegistrosAtivos($colaborador_id);
          $hash = md5(@mktime());
          $esqueceu_senha = array('colaborador_id' => $colaborador_id, 'id' => $hash);
          $this->EsqueceuSenha->save($esqueceu_senha);
          $colaborador['Colaborador']['chave'] = $hash;
          $this->enviaEmail($colaborador['Colaborador'],'esqueceusenha');
          $email_enviado = true;
        }
      }
    }
    $this->set('email',$email);
    if(!$email_enviado) {
      $this->Captcha = $this->Components->load('Captcha');
      $captcha = $this->Captcha->getQuestao();
      $this->Session->write('captcha.resposta',$captcha['resposta']);
      $this->set('questao',$captcha['questao']);
    } else {
      $this->set('email_enviado','Enviamos um e-mail contendo as instruções para redefinir sua senha. Caso não tenha recebido, verifique em sua pasta de "SPAM".');
    }
  }
  
  public function indicacao() {
    
    $erros = array();
    $email_enviado = false;
    
    if($this->Session->check('colaborador.id')) {
      $this->loadModel('ColaboradorIndicacao');
      $this->loadModel("Colaborador");
      
      if(isset($this->params['url']['chave'])) {
        $chave = $this->params['url']['chave'];
        $consulta = $this->ColaboradorIndicacao->find('first',array('conditions' => array('ColaboradorIndicacao.chave' => $chave)));
        if($consulta) {
          $this->Session->write('sangue.id_indicacao',$consulta['ColaboradorIndicacao']['colaborador_id']);
        }
        $this->redirect('/cadastro');
      } elseif ($this->request->isPost()) {
        $email_indicado = $this->request['data']['email'];
        $nome_indicado = trim($this->request['data']['nome']);
        $resposta = $this->request['data']['resposta'];
        
        $existente = $this->Colaborador->find('first',array('conditions' => array('email' => $email_indicado)));
        
        if(!Validation::email($email_indicado, true)) {
          $erros[] = "Verifique se o e-mail digitado está correto.<br />";
        } elseif ($nome_indicado == "") {
          $erros[] = "O campo nome deve ser informado.<br />";
        } elseif ($resposta != $this->Session->read('captcha.resposta')) {
          $erros[] = "A resposta informada está incorreta.<br />";
        } elseif ($existente) {
          $erros[] = "O usuário indicado já está cadastrado.<br />";
        } else {
          $colaborador_id = $this->Session->read('colaborador.id');
          $colaborador = $this->Colaborador->find('first',array('conditions' => array('id' => $colaborador_id)));
          
          $chave = md5($colaborador_id.$email_indicado);
          
          $consulta = $this->ColaboradorIndicacao->find('all',array('conditions' => array('ColaboradorIndicacao.chave' => $chave)));
          if(!$consulta) {
            $indicacao['email'] = $email_indicado;
            $indicacao['nome'] = $nome_indicado;
            $indicacao['colaborador_id'] = $colaborador_id;
            $indicacao['chave'] = $chave;
            $this->ColaboradorIndicacao->save($indicacao);
          }
          
          App::uses('CakeEmail', 'Network/Email');
          $Email = new CakeEmail('gmail');
          $Email->from(array('lcskdc@gmail.com' => 'Sangue para todos'));
          $Email->to($email_indicado);
          $Email->subject('Indicação Sangue Para Todos');
          $msg = "Olá, um amigo nos indicou.\n\nSe você tiver interesse, pode se cadastrar em nosso portal, e ajudar-nos a salvar vidas.\n\n<a href=\"http://www.sangueparatodos.com.br/Login/indicacao?chave=$chave\">Cadastre-se</a>";
          $Email->send($msg);
          $email_enviado = true;
          
        }
        
        if(count($erros)>0) {
          $this->set('erros',implode('<br />',$erros));
        }
        $this->set('email',$email_indicado);
        $this->set('nome',$nome_indicado);
      } else {
        $this->set('email','');
        $this->set('nome','');
      }
      
      if(!$email_enviado) {
        $this->Captcha = $this->Components->load('Captcha');
        $captcha = $this->Captcha->getQuestao();
        $this->Session->write('captcha.resposta',$captcha['resposta']);
        $this->set('questao',$captcha['questao']);
      } else {
        $this->set('email_enviado','Enviamos um e-mail contendo as instruções para redefinir sua senha. Caso não tenha recebido, verifique em sua pasta de "SPAM".');
      }
      
    }
    
  }
  
  public function redefinir_senha() {
    $hash = $this->params['url']['hash'];
    $this->loadModel('EsqueceuSenha');
    $esqueceuSenha = $this->EsqueceuSenha->find('first',array('conditions' => array(
        'EsqueceuSenha.id' => $hash,
        'EsqueceuSenha.valido' => 'S',
        'EsqueceuSenha.data >= DATE_SUB(NOW(),INTERVAL 2 DAY)'
      )
    ));
    
    if($esqueceuSenha) {
      if(isset($this->request['data']['senha'])) {
        if($this->request['data']['senha']===$this->request['data']['confirma_senha']) {
          if(strlen($this->request['data']['senha'])>=6) {
            $senha = md5($this->request['data']['senha']);
            $this->loadModel("Colaborador");
            $this->Colaborador->read(null,$esqueceuSenha['Colaborador']['id']);
            $this->Colaborador->set('ativo','S');
            $this->Colaborador->set('senha',$senha);
            $this->salvaPontuacaoCadastro($this->Colaborador->id, $this->Colaborador->id_indicacao);
            $this->Colaborador->save();
            $this->EsqueceuSenha->read(null,$hash);
            $this->EsqueceuSenha->set('valido','N');
            $this->EsqueceuSenha->save();
            $this->montaMsgUsuario('OK', 'Sua senha foi redefinida.');
            $this->redirect("/Login");
          } else {
            $this->set('erro',"A senha deve conter pelo menos 6 caracteres.");
          }
        } else {
          $this->set('erro',"A senha e a confirmação não são iguais.");
        }
        
      }
      $this->set('hash',$hash);
    }
    
  }
  
  public function valida_cadastro() {
    
    $this->layout = 'ajax';
    $this->autoRender = false;
    $chave = $this->params['url']['chave'];
    
    $this->loadModel('Colaborador');
    
    $login = $this->Colaborador->find('all',array('conditions' => array(
      'chave' => $chave
    ) ) );
    
    if($login) {
      $login = $login[0];
      if($login['Colaborador']['ativo'] == 'A') {
        $login['Colaborador']['ativo'] = 'S';
        $this->Colaborador->save($login);
        if($this->Session->check('colaborador')) {
          if($this->Session->read('colaborador.id') == $login['Colaborador']['id']) {
            $this->Session->write('colaborador.ativo','S');
            $this->montaMsgUsuario('OK', 'Obrigado '.$login['Colaborador']['nome'].', seu e-mail foi validado.' );
          }
        }
        $this->salvaPontuacaoCadastro($login['Colaborador']['id'], $login['Colaborador']['id_indicacao']);
        
        $this->loadModel('Demanda');
        $this->Demanda->ativarDemandas($login['Colaborador']['id']);

        if($login['Colaborador']['senha']=='inalterada') {
          $this->loadModel('EsqueceuSenha');
          $colaborador_id = $login['Colaborador']['id'];
          $this->EsqueceuSenha->atualizaRegistrosAtivos($colaborador_id);
          $hash = md5(@mktime());
          $esqueceu_senha = array('colaborador_id' => $colaborador_id, 'id' => $hash, 'tipo' => 2);
          $this->EsqueceuSenha->save($esqueceu_senha);
          $this->montaMsgUsuario('OK', 'É ncessário definir sua senha');
          $this->redirect("/Login/redefinir_senha?hash=$hash");
        }
        
      }
    }
    
    if($this->Session->check('colaborador') ) {
      $this->redirect('/Login/interno');
    }else{
      $this->redirect('/Login/');
    }
    
  }
  
  public function cadastro() {
    
    App::uses('CakeTime', 'Utility');
    
    $erros = array();
  
    $this->loadModel('Colaborador');
    $this->loadModel('TipoSanguineo');
    $tipos_sanguineos = $this->TipoSanguineo->find('list', array('fields' => array('TipoSanguineo.descricao', 'TipoSanguineo.descricao')));
    $this->set('tiposSanguineos', $tipos_sanguineos);

    if ($this->request->isPost()) {

      if ($this->Session->check('colaborador.id')) {
        $cadastro = $this->Colaborador->find('first', array('conditions' => array('id' => $this->Session->read('colaborador.id'))));
        unset($cadastro['Colaborador']['senha']);
      } else {
        $cadastro = $this->Colaborador->create();
        $cadastro['Colaborador']['email'] = $this->Session->check('colaborador.id') ? $this->Session->read('colaborador.email') : $this->request->data('email');
        if($this->request->data('senha')!=$this->request->data('confirmaSenha')) {
          $erros[] = "A senha e a confirmação devem ser idênticas.";
        } elseif(strlen($this->request->data('senha')) < 6) {
          $erros[] = "A senha deve contér no mínimo 6 caracteres.";
        } else {
          $cadastro['Colaborador']['senha'] = md5($this->request->data('senha'));
        }
        $cadastro['Colaborador']['chave'] = md5(rand(100000000,999999999));
        $cadastro['Colaborador']['ativo'] = 'A';
        $cadastro['Colaborador']['id_indicacao'] = $this->Session->read('sangue.id_indicacao');
      }

      $dataNascimento = "";
      if ($this->request->data('nascimento') != "") {
        list($dia, $mes, $ano) = explode("/", $this->request->data('nascimento'));
        $dataNascimento = $ano . '-' . $mes . '-' . $dia;
        if(!checkdate($mes,$dia,$ano)){
          $erros[] = "Data de nascimento inválida.";
        }
      }

      $cadastro['Colaborador']['nome'] = $this->request->data('nome');
      
      $sexo = $this->request->data('sexo');
      if(!in_array($sexo,array('U','F','M'))) {
        $sexo = "U";
      }
      $cadastro['Colaborador']['sexo'] = $sexo;
      
      
      $cadastro['Colaborador']['nascimento'] = $dataNascimento;
      $cadastro['Colaborador']['uf'] = $this->request->data('uf');
      $cadastro['Colaborador']['cidade'] = $this->request->data('cidade');
      $cadastro['Colaborador']['tipo_sanguineo'] = $this->request->data('tipoSanguineo');
      
      if(strlen(preg_replace('/[^0-9]/','',$this->request->data('telefone'))) >= 10) {
        $cadastro['Colaborador']['telefone'] = preg_replace('/[^0-9]/','',$this->request->data('telefone'));
        $cadastro['Colaborador']['receber_sms'] = $this->request->data('receber_sms')?'S':'N';
      } else {
        $cadastro['Colaborador']['telefone'] = null;
        $cadastro['Colaborador']['receber_sms'] = 'N';
      }

      $this->Colaborador->set($cadastro);
      if($erros) {
        foreach ($erros as $k => $v) {
          $msgs[] = $v;
        }
        $this->set('msg', $msgs);        
      }elseif ($this->Colaborador->validates()) {
        if (!$this->Session->check('colaborador.id')) {
          $this->Colaborador->save();
          $colaborador_id = $this->Colaborador->getLastInsertId();
          $cadastro['Colaborador']['id'] = $colaborador_id;
          $this->montaMsgUsuario('OK', 'Olá '.$this->request->data('nome').', Obrigado pelo seu cadastro. É necessário que você valide seu cadastro através do e-mail que lhe enviamos.');
          $this->enviaEmail($cadastro['Colaborador'],'cadastro');
          $this->salvaSessao($cadastro['Colaborador']);
        } else {
          $this->salvaSessao($cadastro['Colaborador']);
          $this->Colaborador->save();
          $this->montaMsgUsuario('OK', $this->request->data('nome').', suas alterações foram salvas.' );
        }
        $this->redirect("/Login/interno/");
      } else {
        $errors = $this->Colaborador->validationErrors;
        foreach ($errors as $k => $v) {
          $msgs[] = $v[0];
        }
        $this->set('msg', $msgs);
      }
    }

    if ($this->Session->check('colaborador.id')) {
      $this->set('nome', $this->Session->read('colaborador.nome'));
      $this->set('senha', $this->Session->read('colaborador.senha'));
      $this->set('telefone', $this->Session->read('colaborador.telefone'));
      $this->set('receber_sms', $this->Session->read('colaborador.receber_sms'));
      $this->set('nascimento', CakeTime::format($this->Session->read('colaborador.nascimento'), '%d/%m/%Y'));
      $this->set('tipo_sanguineo', $this->Session->read('colaborador.tipo_sanguineo'));
      $this->set('sexo', $this->Session->read('colaborador.sexo'));
      $this->set('uf', $this->Session->read('colaborador.uf'));
      $this->set('cidade', $this->Session->read('colaborador.cidade'));

      if ($this->request->isPost()) {
        $this->set('nome', $this->request->data('nome'));
        if (isset($this->request->data['senha'])) {
          $this->set('senha', $this->request->data('senha'));
        }
        //$this->set('tipo_sanguineo', $this->request->data('tipo_sanguineo'));
        $this->set('sexo', $this->request->data('sexo'));
        $this->set('cidade', $this->request->data('cidade'));
        $this->set('uf', $this->request->data('uf'));
      }

      $this->set('id', $this->Session->read('colaborador.id'));
      $this->set('email', $this->Session->read('colaborador.email'));
      $this->set('id_social', $this->Session->read('colaborador.id_social'));
      $this->set('id_indicacao', $this->Session->read('colaborador.id_indicacao'));
    } else if ($this->request->isPost()) {
      $this->set('id', empty($colaborador_id) ? 0 : $colaborador_id);
      $this->set('nome', $this->request->data('nome'));
      $this->set('email', $this->request->data('email'));
      $this->set('senha', $this->request->data('senha'));
      $this->set('telefone', $this->request->data('telefone'));
      $this->set('receber_sms', $this->request->data('receber_sms'));
      $this->set('nascimento', CakeTime::format($this->request->data('nascimento'), '%d/%m/%Y'));
      $this->set('tipo_sanguineo', $this->request->data('tipoSanguineo'));
      $this->set('sexo', $this->request->data('sexo'));
      $this->set('uf', $this->request->data('uf'));
      $this->set('cidade', $this->request->data('cidade'));
      $this->set('id_social', 0);
    } else {
      $this->set('id', 0);
      $this->set('nome', '');
      $this->set('email', '');
      $this->set('senha', '');
      $this->set('telefone', '');
      $this->set('receber_sms', 'N');
      $this->set('nascimento', '');
      $this->set('tipo_sanguineo', '');
      $this->set('sexo', '');
      $this->set('uf', '');
      $this->set('cidade', '');
      $this->set('id_indicacao', '');
      $this->set('id_social', '');
    }

    $this->loadModel('Estado');
    $estados = $this->Estado->find('all', array('recursive' => false, 'fields' => array('Estado.id', 'Estado.uf', 'Estado.nome'), 'order' => array('Estado.nome')));
    $this->set('estados', $estados);

    if ($this->Session->read('colaborador.cidade') > 0 || ( $this->request->isPost() && $this->request->data('uf') != "" ) ) {
      $optUF = ($this->request->isPost() && $this->request->data('uf') != "")?$this->request->data('uf'):$this->Session->read('colaborador.uf');
      $this->loadModel('Cidade');
      $cidades = $this->Cidade->find('all', array('conditions' => array('Estado.uf' => $optUF), 'order' => 'Cidade.nome'));
      $this->set('cidades', $cidades);
    }
    
  }
  
  private function salvaPontuacaoCadastro($colaborador_id, $indicacao_id) {
    
    $this->loadModel('Gamification');
    $gm = new Gamification();
    
    if(!$gm->isPontuado($colaborador_id,2)) {
      //Pontuação pelo cadastro, todos recebem esta pontuação.
      $ponto_cadastro['colaborador_id'] = $colaborador_id;
      $ponto_cadastro['pontos'] = 100; //Pontuação pelo cadastro
      $ponto_cadastro['ref'] = $this->referer();
      $ponto_cadastro['tipo_id'] = 2;
      $gm->save($ponto_cadastro);
      unset($gm);

      $gm = new Gamification();
      if($indicacao_id>0) { //Pontuação por cadastro de uma pessoa que acessou o site compartilhado
        $ponto_cadastro['colaborador_id'] = $indicacao_id;
        $ponto_cadastro['pontos'] = 50; //Pontuação por indicação
        $ponto_cadastro['ref'] = $this->referer();
        $ponto_cadastro['tipo_id'] = 3;
        $gm->save($ponto_cadastro);
      }
    }
    
  }

  public function cidades($uf,$retorno=false) {
    $this->loadModel('Cidade');
    $this->autoRender = false;
    $cidades = $this->Cidade->find('all', array(
      'conditions' => array('Estado.uf' => $uf),
      'order' => array('Cidade.nome')
        )
    );
    if($retorno==false) {
      echo json_encode($cidades);
    } else {
      return $cidades;      
    }
  }

  public function valida() {
    $this->loadModel('Colaborador');
    $this->autoRender = false;
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $usuario = $this->Colaborador->find('first', array(
        'conditions' =>
        array(
          'email' => $email,
          'senha' => md5($senha),
          'not' => array('senha' => null)
        )
      )
    );

    if ($usuario) {
      $this->salvaSessao($usuario['Colaborador']);
      echo json_encode(array("isLogado" => true, "nome" => $usuario["Colaborador"]["nome"], "status" => $usuario['Colaborador']['ativo']));
    } else {
      echo json_encode(array("isLogado" => false, "status" => "A"));
    }
  }

  public function salvaSessao($Colaborador) {

    $this->Session->write('colaborador.id', $Colaborador['id']);
    $this->Session->write('colaborador.email', $Colaborador['email']);
    $this->Session->write('colaborador.nome', $Colaborador['nome']);

    if (isset($Colaborador['sexo'])) {
      $this->Session->write('colaborador.sexo', $Colaborador['sexo']);
    }

    if (isset($Colaborador['nascimento'])) {
      $this->Session->write('colaborador.nascimento', $Colaborador['nascimento']);
    }

    if (isset($Colaborador['tipo_sanguineo'])) {
      $this->Session->write('colaborador.tipo_sanguineo', $Colaborador['tipo_sanguineo']);
    }

    if (isset($Colaborador['uf']) && $Colaborador['uf'] != "") {
      $this->loadModel("Cidade");
      $resp = $this->Cidade->find('first',array('conditions'=>array('Cidade.id' => $Colaborador['cidade'])));
      $this->Session->write('colaborador.cidade', $Colaborador['cidade']);
      $this->Session->write('colaborador.nome_cidade', $resp['Cidade']['nome']);
      $this->Session->write('colaborador.uf', $resp['Estado']['uf']);
    }
    
    if(isset($Colaborador['id_social'])) {
      $this->Session->write('colaborador.id_social', $Colaborador['id_social']);
    }
    
    $this->Session->write('colaborador.ativo', $Colaborador['ativo']);
    $this->Session->write('colaborador.chave', $Colaborador['chave']);
    $this->Session->write('colaborador.telefone', $Colaborador['telefone']);
    $this->Session->write('colaborador.receber_sms', $Colaborador['receber_sms']);
    $this->Session->write('colaborador.tipo_social', isset($Colaborador['tipo_social'])?$Colaborador['tipo_social']:'');
    
    $this->busca_dados_usuario();
    
    try {
        $imgDefault = 'http://sangueparatodos.com.br/img/avatar.jpg';
        $urlImagem = $this->Session->check('colaborador.imagem')?$this->Session->read('colaborador.imagem'):"http://www.gravatar.com/avatar/" . md5( strtolower(trim($Colaborador['email']))) . "?d=" . urlencode($imgDefault) . "&s=40";
        //echo 'Abriu imagem: '.$urlImagem.'<br />';
        @unlink(getcwd().'/img/usuarios/'.md5($Colaborador['email']).'.jpg');
        $handle = fopen(getcwd().'/img/usuarios/'.md5($Colaborador['email']).'.jpg','a+');
        $strImg = file_get_contents($urlImagem);
        fwrite($handle,$strImg,strlen($strImg));
        fclose($handle);
    } catch ( Exception $e ) {
      print_r($e);
        //Código de erro, upload de imagem
    }
    
    $this->Session->write('colaborador.id_social', isset($Colaborador['id_social']) ? $Colaborador['id_social'] : 0);
  }

  function loginSocial() {

    $this->loadModel('Colaborador');
    $this->autoRender = false;
    $colaborador = $this->Colaborador->find('first', array(
      'conditions' => array(
        'id_social' => $this->request->data('id'),
        'not' => array('id_social' => null)
      )
    ));

    if (!$colaborador) {
      $colaborador = $this->Colaborador->find('first', array(
        'conditions' => array(
          'email' => $this->request->data('email'),
          'not' => array('email' => null)
        )
      ));

      $sexo = strtoupper(substr($this->request->data('sexo'), 0, 1));
      if (!$colaborador) {
        $cadastro['Colaborador']['nome'] = $this->request->data('nome');
        
        if($this->request->data('tipo')=='facebook') {
          if(!$this->request->data('email')) {
            $cadastro['Colaborador']['email'] = $this->request->data('id').'@facebook.com';
          } else {
            $cadastro['Colaborador']['email'] = $this->request->data('email');
          }
        } else {
          $cadastro['Colaborador']['email'] = $this->request->data('email');
        }
        
        $cadastro['Colaborador']['senha'] = $this->Colaborador->geraSenha();
        $cadastro['Colaborador']['sexo'] = $sexo;
        $cadastro['Colaborador']['id_social'] = $this->request->data('id');
        $cadastro['Colaborador']['tipo_social'] = $this->request->data('tipo');
        $cadastro['Colaborador']['chave'] = md5(rand(100000000,999999999));
        $cadastro['Colaborador']['id_indicacao'] = $this->Session->read('sangue.id_indicacao');
      } else {
        $cadastro = $colaborador;
        $cadastro['Colaborador']['id_social'] = $this->request->data('id');
        $cadastro['Colaborador']['tipo_social'] = $this->request->data('tipo');
        if ($cadastro['Colaborador']['sexo'] == "") {
          $cadastro['Colaborador']['sexo'] = $sexo;
        }
      }
      $cadastro['Colaborador']['ativo'] = 'S';
      
      $this->Colaborador->set($cadastro);
      $this->Colaborador->save();
      $errors = $this->Colaborador->validationErrors;

      $colaborador_id = !$colaborador?$this->Colaborador->getLastInsertId():$colaborador['Colaborador']['id'];
      if($colaborador_id>0 && !$errors) {
        $this->salvaPontuacaoCadastro($colaborador_id, null);
      }
      
      $colaborador = $this->Colaborador->find('first', array(
        'conditions' => array(
          'id_social' => $this->request->data('id'),
          'not' => array('id_social' => null)
        )
      ));
      $this->Session->write('colaborador.imagem', $this->request->data('urlImagem'));
      $this->salvaSessao($colaborador['Colaborador']);
    } else {
      $this->Session->write('colaborador.imagem', $this->request->data('urlImagem'));
      $colaborador_id = $colaborador['Colaborador']['id'];
      $this->salvaSessao($colaborador['Colaborador']);
    }
    
    echo json_encode(array('id' => $colaborador_id));
  }

  function coordenadas () {
    $this->autoRender = false;
    $lat = $this->request->data('lat');
    $lng = $this->request->data('lng');
    if($lat != "" && $lng != "") {
      $this->Session->write("colaborador.lat",$lat);
      $this->Session->write("colaborador.lng",$lng);
    }
  }
  
  function loginTwitter() {

    App::uses('TwitterOAuth', 'Vendor/Twitter');
    
    $this->loadModel('Colaborador');
    $this->autoRender = false;
    $host = $_SERVER['HTTP_HOST'];
    $CONSUMER_KEY = '7XtC5TO4NycPa1t8P0alPKtL9';
    $CONSUMER_SECRET = 'G8DSEigv0Q3qui2NsjdARb8OJ2FAYH1ATyyynODER7pumiEN4O';
    $OAUTH_CALLBACK = 'http://'.$host.'/Login/loginTwitter/';

    if (!$this->request->query['oauth_token']) {
      $connection = new TwitterOAuth($CONSUMER_KEY, $CONSUMER_SECRET);
      $request_token = $connection->getRequestToken($OAUTH_CALLBACK);
      if ($request_token) {
        $token = $request_token['oauth_token'];
        $this->Session->write('twitter.request_token', $token);
        $this->Session->write('twitter.request_token_secret', $request_token['oauth_token_secret']);
        echo $connection->http_code . '<br />';
        switch ($connection->http_code) {
          case 200:
            $url = $connection->getAuthorizeURL($token);
            $this->redirect($url);
            break;
          default:
            echo "Coonection with twitter Failed";
            break;
        }
      }
    } else {
      $connection = new TwitterOAuth($CONSUMER_KEY, $CONSUMER_SECRET, $this->Session->read('twitter.request_token'), $this->Session->read('twitter.request_token_secret'));
      $access_token = $connection->getAccessToken($this->request->query['oauth_verifier']);

      if ($access_token) {
        $connection = new TwitterOAuth($CONSUMER_KEY, $CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);
        $params = array();
        $params['include_entities'] = 'false';
        $content = $connection->get('account/verify_credentials', $params);

        if ($content && isset($content->screen_name) && isset($content->name)) {
          $id_social = $content->id;
          $nome = $content->name;
          $email = $content->screen_name . '@twitter.com';
          $urlImg = $content->profile_image_url;
        }

        $colaborador = $this->Colaborador->find('first', array(
          'conditions' => array(
            'id_social' => $id_social,
            'tipo_social' => 'twitter',
            'not' => array('id_social' => null)
          )
        ));

        if (!$colaborador) {
          $colaborador['Colaborador']['nome'] = $nome;
          $colaborador['Colaborador']['email'] = $email;
          $colaborador['Colaborador']['senha'] = $this->Colaborador->geraSenha();
          $colaborador['Colaborador']['id_social'] = $id_social;
          $colaborador['Colaborador']['tipo_social'] = 'twitter';
          $colaborador['Colaborador']['id_indicacao'] = $this->Session->read('sangue.id_indicacao');
          $colaborador['Colaborador']['chave'] = md5(rand(100000000,999999999));
          $colaborador['Colaborador']['ativo'] = 'S';
          $this->Colaborador->set($colaborador);
          $this->Colaborador->save();
          $errors = $this->Colaborador->validationErrors;
          $colaborador_id = $this->Colaborador->getLastInsertId();
          $colaborador['Colaborador']['id'] = $colaborador_id;
          if ($colaborador) {
            $this->salvaPontuacaoCadastro($colaborador_id, $cadastro['Colaborador']['id_indicacao']);
          }
        }

        $this->salvaSessao($colaborador['Colaborador']);
        $this->Session->write('colaborador.imagem', $urlImg);
      }
    }
    $this->redirect("/Login/interno/");
  }

  function interno() {
    
    $this->validaUsuarioLogado();
    
    $id_colaborador = $this->Session->read('colaborador.id');
    $id_social = $this->Session->check('colaborador.id_social')?$this->Session->read('colaborador.id_social'):0;

    if (!$id_colaborador > 0) {
      $this->redirect('/Login');
    }
    
    $this->set('id_colaborador',$id_colaborador);
    $this->set('id_social',$id_social);

    if ($this->Session->check('colaborador.msgUsuario')) {
      $this->set('msgUsuario', unserialize($this->Session->read('colaborador.msgUsuario')));
      $this->Session->delete('colaborador.msgUsuario');
    }
  }
  
  function busca_dados_usuario() {
    $id_colaborador = $this->Session->read('colaborador.id');
    $sql = "SELECT
            DATEDIFF(NOW(),evento.data) as tempo,
            evento.dias-DATEDIFF(NOW(),evento.data) as restante,
            DATE_ADD(data, INTERVAL evento.dias DAY) as previsao,
            evento.data,
            tp.descricao,
            evento.dias as prazo
            FROM eventos evento
              JOIN tipoevento tp ON tp.id = evento.id_evento
            WHERE id_colaborador = '$id_colaborador'
            HAVING restante > 0
            ORDER BY previsao DESC
            LIMIT 1";
    $this->loadModel("Evento");
    $r = $this->Evento->query($sql);
    if(isset($r[0][0]['restante'])) {
      $this->Session->write('sangue.restante',$r[0][0]['restante']);
    }
    
    $this->loadModel("Gamification");
    $sql = "SELECT SUM(pontos) as pontos FROM colaborador_pontuacao WHERE colaborador_id = $id_colaborador";
    $r = $this->Gamification->query($sql);
    if(isset($r[0][0]['pontos'])) {
      $this->Session->write('sangue.pontos',$r[0][0]['pontos']);
    }
    
  }

  public function sair() {
    $this->Session->destroy();
    $this->redirect('/');
  }
  
  public function reenvia_email() {
    $this->layout = 'ajax';
    $this->autoRender = false;
    $this->loadModel('Colaborador');
    $this->enviaEmail($this->Session->read('colaborador'),'cadastro');
    $this->montaMsgUsuario('OK', 'Reenviamos um e-mail para '.$this->Session->read('colaborador.email'));
  }
  
}
