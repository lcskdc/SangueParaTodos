<?php ?>
<div id="menu_area_interna">

    <?php if(isset($informacoes_eventos[0])) { ?>
      <?php echo '<p>Restam '.$informacoes_eventos[0][0]['restante'].' dias para a sua nova doação.</p>'; ?>
    <?php } ?>

    <?php if(isset($msgUsuario)) { ?>
      <?php if($msgUsuario['tipo']=='OK') { ?>
  <div class="alert alert-success" role="alert">
    <div class="img-icon icon-ok"></div><?php echo implode("<br />",$msgUsuario['msg'])?>
  </div>
      <?php } else { ?>
  <div class="alert alert-danger" role="alert">      
    <div class="img-icon icon-error"></div><?php echo implode("<br />",$msgUsuario['msg'])?>
  </div>
      <?php } ?>

    <?php } ?>

  <div class="setaabaixo">
    <div class="seta"><img src="/img/coracao_batendo.gif" />&nbsp;Área interna</div>
    <ul class="menu_interno">
      <li><a href="/Login/cadastro" class="acadastro"><img src="/img/icon-cadastro.jpg" class="img-circle" />Seu<br />Cadastro</a></li>
      <li><a href="/Medalha/lista" class="amedalhas"><img src="/img/icon-medalha.jpg" class="img-circle" />Sua<br />Pontuação</a></li>
      <li><a href="/Demanda/cadastro" class="asolicitacao"><img src="/img/icon-solicitacao.jpg" class="img-circle" />Solicitar<Br />doadores</a></li>
      <li><a href="/Local/demandas/<?php echo $id_colaborador?>" class="asolicitacoes"><img src="/img/icon-solicitacoes.jpg" class="img-circle" />Suas<br />Solicitações</a></li>
      <li><a href="/Evento/cadastro" class="aevento"><img src="/img/icon-calendario.jpg" class="img-circle" />Novo<br />Evento</a></li>
      <li><a href="/Evento/lista" class="aeventos"><img src="/img/icon-eventos.jpg" class="img-circle" />Seus<br />Eventos</a></li>
    </ul>
  </div>

  <div style="clear:both;float:none;"></div>


</div>