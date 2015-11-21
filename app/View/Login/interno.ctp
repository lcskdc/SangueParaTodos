<?php ?>
<div id="menu_area_interna">

    <?php if(isset($informacoes_eventos[0])) { ?>
      <?php echo '<p>Restam '.$informacoes_eventos[0][0]['restante'].' dias para a sua nova doação.</p>'; ?>
    <?php } ?>

  <div class="setaabaixo">
    <div class="seta"><img src="/img/coracao_batendo.gif" />&nbsp;Área interna</div>
    <ul class="menu_interno">
      <li><a href="/Login/cadastro" class="acadastro"><img src="/img/icon-cadastro.jpg" class="img-circle" />Seu<br />Cadastro</a></li>
      <li><a href="/Medalha/lista" class="amedalhas"><img src="/img/icon-medalha.jpg" class="img-circle" />Sua<br />Pontuação</a></li>
      <li><a href="/Demanda/cadastro" class="asolicitacao"><img src="/img/icon-solicitacao.jpg" class="img-circle" />Solicitar<Br />doação</a></li>
      <li><a href="/Local/demandas/<?php echo $id_colaborador?>" class="asolicitacoes"><img src="/img/icon-solicitacoes.jpg" class="img-circle" />Suas<br />Solicitações</a></li>
      <li><a href="/Evento/cadastro" class="aevento"><img src="/img/icon-calendario.jpg" class="img-circle" />Novo<br />Evento</a></li>
      <li><a href="/Evento/lista" class="aeventos"><img src="/img/icon-eventos.jpg" class="img-circle" />Seus<br />Eventos</a></li>
      <li><a href="/Login/indicacao" class="aindicar"><img src="/img/icon-indicar.jpg" class="img-circle" />Indique um<br />Amigo</a></li>
    </ul>
  </div>

  <div style="clear:both;float:none;"></div>
  <input type="hidden" name="id-user" id="id-user" value="<?php echo $id_social?>" />
  <!--a href="#self" onclick="testeAquireFriends();">Teste aqq facebook users</a-->


</div>