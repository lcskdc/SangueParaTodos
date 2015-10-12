<?php
?>
<div class="msgDemanda">
  <div class="alert alert-success" role="alert">
    <?php if(isset($nmUsuario)) { ?>
      <?php if(!isset($idColaborador)) { ?>
        <!--div class="img-icon icon-ok"></div>Falta pouco <em><?php echo $nmUsuario?></em>, registramos sua demanda, porém é necessário validá-la através do e-mail que lhe enviamos.-->
        <div class="img-icon icon-ok"></div>Obrigado <em><?php echo $nmUsuario?></em>, sua demanda foi registrada.
      <?php } else { ?>
        <div class="img-icon icon-ok"></div>Obrigado <em><?php echo $nmUsuario?></em>, sua demanda foi registrada.
      <?php } ?>
    <?php } else { ?>
      <div class="img-icon icon-ok"></div>Obrigado, Demanda sua demanda foi registrada.
    <?php } ?>

  </div>
  <hr />
  <p>
    <label for="local">&nbsp;</label>
    <a href="/Demanda/cadastro" class="btn btn-lg btn-success">Cadastrar nova demanda</a>
  </p>
</div>