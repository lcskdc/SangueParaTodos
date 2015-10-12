<?php
?>
<form name="cadastro" id="form-esquecisenha" class="forms" method="post" autocomplete="off">
  
  <div class="setaabaixo">
      <div class="seta"><img src="/img/coracao_batendo.gif" />&nbsp;Redefinir senha?</div>
  </div>
  
  <?php if(isset($hash)) { ?>
    
    <?php if(isset($erro)) { ?>
    <p>
      <div class="alert alert-danger"><?php echo $erro?></div>
    </p>
    <?php } ?>
    
    <p>
      <div class="alert alert-info">A senha deve conter no mínimo 6 caracteres, entre números e caracteres alfanuméricos.</div>
    </p>
    <p>
      <label for="senha">Senha: <span class="require"> *</span></label>
      <input type="password" name="senha" id="senha" placeholder="Senha" autocomplete="off" value="" autofocus class="form-control" />
    </p>
    <p>
      <label for="confirma_senha">Confirme a senha: <span class="require"> *</span></label>
      <input type="password" name="confirma_senha" id="confirma_senha" placeholder="Confirme a senha" autocomplete="off" value="" autofocus class="form-control" />
    </p>

    <hr />
    
    <div id="controles">
      <hr />
      <p>
        <button class="btn btn-lg btn-success" id="btn-login">Salvar</button>
      </p>
    </div>
  <?php } else { ?>
    <div class="alert alert-danger">A solicitação não é mais válida.</div>
  <?php } ?>

</form>