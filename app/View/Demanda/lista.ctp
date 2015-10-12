<?php
?>
<div id="lista_demandas">
  <p>&nbsp;</p>
  <div class="setaabaixo">
    <div><img src="/img/coracao_batendo.gif" />&nbsp;Demandas que você cadastrou</div>
  </div>
  <?php

  if($demandas) { ?>
  <div class="grid-list">
    <table width="100%">
      <thead>
        <th>Nome</th>
        <th>Instituição</th>
        <th>Doadores</th>
        <th>Validade</th>
        <th>&nbsp;</th>
      </thead>
      <tbody>
      <?php foreach($demandas as $k => $v) { ?>
      <?php $i++; ?>
        <?php if($i==count($demandas)) { ?>
        <tr class="ultimaLinha">
        <?php } else { ?>
        <tr>  
        <?php } ?>
        <td><?php echo $v['Demanda']['paciente']?></td>
        <td>
          <?php echo $v['Demanda']['instituicao']?>
          <span class="grid-endereco"><?php echo $v['Demanda']['endereco']?></span>
        </td>
        <td><?php echo $v['Demanda']['doadores']?></td>
        <td><?php echo $v['Demanda']['validade']?></td>
        <td>
          <?php if(!empty($v['Demanda']['excluido'])) { ?>
          <a href="#self" rel="<?php echo $v['Demanda']['id']?>" class="excluir"><img src="/img/icon-trash.png" /></a>
          <?php } else { ?>
          &nbsp;
          <?php } ?>
        </td>
      </tr>
      <?php } ?>
      </tbody>
    </table>
  </div>
  <?php } else { ?>
  <p>Não há demandas cadastradas.</p>
  <?php } ?>
  <p class="ctrls">
    <a class="btn btn-lg btn-success" href="/Demanda/cadastro">Cadastrar nova demanda</a>
  </p>
  
</div>
<script language="javascript">
  $('.excluir').click(function(){
    if(confirm('Deseja realmente excluir esta demanda?')) {
      $.post('/Demanda/excluir',{id:$(this).attr("rel")});
      $(this).remove();
    }
  });
</script>