<?php

$cakeDescription = __d('cake_dev', 'Portal Sangue Para Todos');
$cakeVersion = '';
$imagem = $this->Session->read("colaborador.imagem");
?>
<!DOCTYPE html>
<html>
    <head>
    <?php echo $this->Html->charset(); ?>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo $cakeDescription ?></title>
    <?php
    echo $this->Html->meta('icon');
    $ctrl = strtolower($this->request->params['controller']);
    
    echo $this->Html->css('/css/bootstrap/css/bootstrap.css');
    echo $this->Html->css('/css/bootstrap/css/bootstrap-theme.min.css');
    
    echo $this->Html->css('generico');
    echo $this->Html->css('menus');
    echo $this->Html->css('padroes');
    
    //if(file_exists('./css/'.$ctrl.'.css')) {
      echo $this->Html->css($ctrl);
    //}
    
    echo $this->Html->script('jquery');
    echo $this->Html->script('https://maps.googleapis.com/maps/api/js?v=3.exp');
    
    echo $this->Html->script('menu');
    echo $this->Html->script('facebook');
    echo $this->Html->script('googleplus');
    
    echo $this->Html->script('/css/bootstrap/js/bootstrap.min.js');
    echo $this->Html->script('jquery.mask');
    echo $this->Html->script('localizacao');
    
    if($ctrl=="demanda") {
      echo $this->Html->script('jquery.autocomplete');
    }
    
    //if(file_exists(getcwd().'/js/'.$ctrl.'.js')) {
      echo $this->Html->script($ctrl);
    //}

    echo $this->fetch('meta');
    echo $this->fetch('css');
    echo $this->fetch('script');

    //echo $this->Html->script('facebook');
    ?>
    </head>
    <body>

        <?php if(isset($msgUsuario)) { ?>
          <div class="msgs-portal alert <?php echo $msgUsuario['classe']?>" role="alert">
            <div class="img-icon icon-ok"></div><?php echo $msgUsuario['msg']?>
          </div>
        <?php } ?>
      
        <div id="container">
          <!-- imagem cache -->  
          <img class="imagem-cache" src="/img/coracao_batendo.gif" />
          <div id="header">

            <div id="dv-info-user">
            <?php if(isset($evento_tempo_restante)) { ?>
              <div title="Restam <?php echo $evento_tempo_restante?> dias para uma nova doação de sangue." class="badge-indica-restante"><div>Restam <?php echo $evento_tempo_restante?> dias para uma nova doação de sangue.</div></div>
            <?php } ?>            
            
            <?php if($this->Session->check('colaborador.nome')) { ?>
              <div class="dv-info">
                <a href="/Login/cadastro" title="Visualizar seu cadastro">
                <?php if($imagem!="") { ?>
                  <div class="img_col"><img src="<?php echo $imagem?>" class="img-circle" /></div>
                <?php } else { ?>
                  <div class="img_col"><img src="/img/avatar.jpg" class="img-circle" /></div>
                <?php } ?>
                </a>
                <div class="dv-informacoes">
                  <a id="btn-sair" href="/Login/sair" title="Clique para sair"><img src="/img/icon-sair.png" /></a>
                  <?php if($colaborador_pontuacao>0) { ?>
                  <a class="dv-info-pontuacao" title="Sua pontuação" href="/Medalha/lista"><?php echo $colaborador_pontuacao?></a>
                  <?php } ?>
                  <?php if($this->Session->read('colaborador.ativo')=='A') { ?>
                  <a href="#self" id="btn-info-ativacao"><img src="/img/icon-info.png" /></a>
                  <?php } ?>
                  <!--a href="/Login/sair">sair</a-->
                </div>
                
              </div>
              
            <?php } ?>&nbsp;
            </div>
            <a href="/" class="img-logo" title="Ir para a página inicial"></a>
              
              <div id="menu-sup">  
                <ul class="menu">
                  <li><a href="/">Início</a>	</li>
                  <li><a href="/Local/demandas/">Solicitações realizadas</a></li>
                  <li><a href="/Demanda/cadastro">Solicitar doação</a></li>
                  <?php if ($this->Session->read('colaborador.id') > 0) { ?>
                  <li><a href="/Login/interno/">Área interna</a><input type="hidden" name="id_usuario" id="id_usuario" value="<?php echo $this->Session->read('colaborador.id')?>" /><input type="hidden" name="id_social" id="id_social" value="<?php echo $this->Session->read('colaborador.id_social')?>" /></li>
                  <?php } else { ?>
                  <li><a href="/Login/cadastro">Cadastro</a></li>
                  <li><a href="/Login">Login</a></li>
                  <?php } ?>
                </ul>
              </div>

              <!--img src="/img/img-separador.png" /-->

          </div>
          <div id="content">
              <?php echo $this->Session->flash(); ?>
              <?php echo $this->fetch('content'); ?>
          </div>

          <div class="modal fade" id="modalMsgAtivacao" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title" id="myModalLabel">Sangue para todos</h4>
                </div>
                <div class="modal-body">
                  Seu e-mail não foi confirmado.<br />
                  Lhe enviamos um e-mail com o link de ativação.<br /><br />
                  <p>Se você não recebeu, primeiramente verifique em sua caixa de spam ou <a href="#self" id="btn-reenvia-email">Clica aqui</a> para caso queira reenviar o e-mail de ativação.</p>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-primary" data-dismiss="modal">Voltar</button>
                </div>
              </div>
            </div>
          </div>          

        </div>
      
        <!--?php echo $this->element('sql_dump'); ?-->
        <div id="footer">
            <div>
              <p>Projeto Sangue Para Todos</p>
              <p>Desenvolvido por Lucas Pacheco Oliveira - Senac RS</p>
              <p><a href="/termos-de-uso">Termos de uso</a> | <a href="/politica-de-privacidade">Política de privacidade</a></p>
            </div>
        </div>

    </body>
</html>
