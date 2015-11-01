<?php
?>
<div id=lista_eventos">
  <p>&nbsp;</p>
  <div class="setaabaixo">
    <div class="seta"><img src="/img/coracao_batendo.gif" />&nbsp;Eventos que você cadastrou</div>
  </div>
  <?php if($eventos) { ?>
  <div class="grid-list">
    <table width="100%">
      <thead>
        <th>Evento</th>
        <th>Data</th>
        <th>Prazo</th>
        <th>Decorridos</th>
        <th>Restam</th>
      </thead>
      <tbody>  
        <?php foreach($eventos as $v) { ?>
        <?php $i++; ?>
          <?php if($i==count($eventos)) { ?>
          <tr class="ultimaLinha">
        <?php } else { ?>
          <tr>
        <?php } ?>  
          <td><?php echo $v['tipoevento']['descricao']?></td>
          <td><?php echo $v[0]['data']?></td>
          <td><?php echo $v['evento']['prazo']?></td>
          <td><?php echo $v[0]['tempo']?></td>
          <td><?php echo $v[0]['restante']?></td>
        </tr>
        
        <?php } ?>
      </tbody>
    </table>
  </div>
  <?php } else { ?>
    <p>Não há demandas cadastradas.</p>
  <?php } ?>
  <p class="ctrls">
    <a class="btn btn-success btn-lg" href="/Evento/cadastro">Cadastrar novo evento</a>
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