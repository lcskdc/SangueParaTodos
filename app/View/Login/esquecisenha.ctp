<?php
 
//echo $this->Html->css('/js/owl-carousel/owl.carousel.css');
echo $this->Html->css('/css/bootstrap/css/bootstrap.css');
echo $this->Html->css('/css/login.css');
?>
<form name="esquecisenha" id="form-esquecisenha" method="post" class="forms">
    <div class="setaabaixo">
        <div class="seta"><img src="/img/coracao_batendo.gif" />&nbsp;Esqueceu sua senha?</div>
    </div>
  
    <?php if(isset($email_enviado)) { ?>
      <div class="alert alert-success"><?php echo $email_enviado?></div>
    <?php } else { ?>
  
      <?php if(isset($msg)) { ?>
        <div class="alert alert-danger"><?php echo $msg?></div>
      <?php } ?>

      <p>
          <label for="email">Email: </label>
          <input type="text" name="email" id="email" value="<?php echo $email?>" class="form-control" autocomplete="off" autocapitalize="off" autocorrect="off" autofocus="autofocus" placeholder="E-mail" />
      </p>
      <p>
          <label for="resp"><?php echo $questao?></label>
          <input type="text" name="resposta" id="resposta" value="" class="form-control" autocomplete="off" autocapitalize="off" autocorrect="off" placeholder="<?php echo $questao?>" />
      </p>
      <p>
        <button type="submit" class="btn btn-lg btn-success btnWith100" id="btn_redefinir_senha">Redefinir senha</button>
      </p>
      <p>
        <a href="/Login/cadastro" class="btn btn-primary btnWith100">Cadastrar-se</a>
      </p>
    
    <?php } ?>
    
</form>