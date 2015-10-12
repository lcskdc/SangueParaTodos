<?php
?>
<form name="frmDemanda" id="frmDemanda" class="forms" method="post">
  <p>&nbsp;</p>
  <?php if(isset($erros)) { ?>
  <div class="alert alert-danger" role="alert">
    <div class="img-icon icon-error"></div><?php echo implode("<br />",$erros)?>
  </div>
  <?php } ?>
  
  <!--div class="alert alert-info">
    <strong>Demandas</strong> são solicitações de doações em um determinado local e/ou instituição. Com base nos dados deste cadastro, a sua solicitação será exibida no portal para os demais usuários.
  </div-->
  
  <div class="setaabaixo">
    <div class="seta"><img src="/img/coracao_batendo.gif" />&nbsp;Solicitar doações<span class="require require_float">* Campos obrigatorios</span></div>
  </div>
  <?php if(!isset($idColaborador)) { ?>
  <p>
    <label for="nome">Qual é o seu nome:<span class="require"> *</span></label>
    <input class="form-control" type="text" name="nmUsuario" id="nmUsuario" placeholder="Seu nome" value="<?php echo $nmUsuario?>" autofocus />
  </p>
  <p>
    <label for="nome">Seu e-mail:<span class="require"> *</span></label>
    <input class="form-control" type="text" name="email" id="email" placeholder="e-mail" value="<?php echo $email?>" autofocus />
  </p>
  <?php } ?>
  <p>
    <label for="nome">Nome do paciente:<span class="require"> *</span></label>
    <input class="form-control" type="text" name="nome" id="nome" placeholder="paciente" value="<?php echo $nome?>" autofocus />
  </p>
  <p>
    <label for="Instituicao">Nome do local para doação:<span class="require"> *</span></label>
    <input class="form-control" type="text" name="instituicao" id="instituicao" placeholder="nome do local" value="<?php echo $instituicao?>" autofocus />
  </p>
  <p>
    <label for="descricao">Descrição:</label>
    <textarea class="form-control" title="Máximo 200 caracteres" placeholder="Máximo 200 caracteres" name="descricao" id="descricao" autofocus><?php echo $descricao?></textarea>
  </p>
  <div class="emLinha">
    <div>
      <label for="doadores">Quantos doadores?<span class="require"> *</span></label>
      <input class="form-control" type="text" name="doadores" id="doadores" value="<?php echo $doadores?>" autofocus />
    </div>
    <div>
      <label for="validade">Até quando?<span class="require"> *</span></label>
      <input class="form-control" type="text" name="validade" id="validade" placeholder="Ex.: <?php echo date('d/m/Y')?>" value="<?php echo $validade?>" autofocus />
    </div>
  </div>
  <div class="emLinha">
    <div>Tipos sanguíneos<span class="require"> *</span>&nbsp;&nbsp;<a id="todosTiposSangue" href="#self">Marcar todos</a></div>
    <div>
    <?php
    $i=0;
    foreach($tiposSanguineos as $key => $value) {
      $i = $i+1; //Utilizado apenas por causa do ID do objeto
      ?>
      <input type="checkbox" class="ckTipoSangue" id="ck_<?php echo $i?>" name="tipo_sanguineo[]"<?php echo in_array($value,$tipos_sangue)?' checked':''?> value="<?php echo $value?>">&nbsp;<label for="ck_<?php echo $i?>"><?php echo $value?>&nbsp;</label>
    <?php } ?>
      <!--input type="checkbox" id="ck_0" name="tipo_sanguineo[]"<?php echo in_array('todos',$tipos_sangue)?' checked':''?> value="todos">&nbsp;<label for="ck_0">Todos&nbsp;</label-->
    </div>
  </div>
  <?php if($local!="") { ?>
    <div class="local emLinha" title="<?php echo $local?>">Local: <?php echo $local?><input type="hidden" id="local" name="local" value="<?php echo $local?>"><input type="hidden" id="posicao" name="posicao" value="<?php echo $posicao?>"></div>  
  <?php } ?>
  <p>
    <span class="require"> *</span><a href="#self" id="adicionar_local"><img align="absmiddle" src="/img/icon-local.png" />&nbsp;Adicionar Local</a>
  </p>
<hr />
<p>
  <label for="local">&nbsp;</label>
  <input type="hidden" name="id_local" id="id_local" value="<?php echo $idLocal?>" />
  <input type="submit" class="btn btn-lg btn-success" value="Cadastrar demanda" />
</p>
</form>