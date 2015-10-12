<?php ?>
<form name="frmEvento" id="frmEvento" action="/Evento/cadastro" class="forms" method="post">
  <p>&nbsp;</p>
  <?php if(isset($msg)) { ?>
  <div class="alert alert-danger" role="alert">
    <div class="img-icon icon-error"></div><?php echo implode("<br />",$msg)?>
  </div>  
  <?php } ?>
  <div class="setaabaixo">
    <div class="seta"><img src="/img/coracao_batendo.gif" />&nbsp;Cadastrar Evento</div>
  </div>
  <p>
    <label for="nome">Evento:</label>
    <select name="evento" id="evento" class="form-control">
      <option value="0">Selecione</option>
      <?php foreach($tipos as $key => $v) { ?>
        <option value="<?php echo $v['TipoEvento']['id']?>"><?php echo $v['TipoEvento']['descricao']?> - <?php echo $v['TipoEvento']['prazo']?> dias</option>
      <?php } ?>
    </select>
  </p>
  <p>
    <label for="validade">Data:</label>
    <input type="text" name="validade" class="form-control" id="validade" value="<?php echo date('d/m/Y')?>" />
  </p>
  <p>
    <label>&nbsp;</label>
    <input type="submit" class="btn btn-lg btn-success" value="Salvar" />
  </p>
</form>