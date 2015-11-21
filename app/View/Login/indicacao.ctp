<?php
?>
<form name="cadastro" id="form-cadastro" class="forms" method="post" autocomplete="off">
  <p>&nbsp;</p>
  <div class="setaabaixo">
    <div class="seta"><img src="/img/coracao_batendo.gif" />&nbsp;Indique um amigo</div>
  </div>
  <?php if(isset($erros)) { ?>
    <div class="alert alert-danger"><?php echo $erros?></div>
  <?php }
  
  if(isset($email_enviado)) { ?>
    <div class="alert alert-success"><?php echo $email_enviado?></div>
  <?php } else { ?>
  <p>
    <label for="email">E-mail do seu amigo: <span class="require"> *</span></label>
    <input type="text" name="email" id="email" placeholder="E-mail do seu amigo" autocomplete="off" value="<?php echo $email?>" autofocus class="form-control" />
  </p>
  <p>
    <label for="nome">Nome do seu amigo: <span class="require"> *</span></label>
    <input type="text" name="nome" id="nome" placeholder="Nome do seu amigo" autocomplete="off" value="<?php echo $nome?>" autofocus class="form-control" />
  </p>  
  <p>
      <label for="resp"><?php echo $questao?> <span class="require"> *</span></label>
      <input type="text" name="resposta" id="resposta" value="" class="form-control" autocomplete="off" autocapitalize="off" autocorrect="off" placeholder="<?php echo $questao?>" />
  </p>
  <p align="center">
    <input type="submit" id="btn-indicacao" class="btn btn-primary" value="Indicar" />
  </p>
  <?php } ?>
</form>