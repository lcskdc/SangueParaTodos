<?php
?>
<form name="cadastro" id="form-cadastro" class="forms" method="post" autocomplete="off">
  <p>&nbsp;</p>
  <?php if(isset($msg)) { ?>
  <div class="alert alert-danger" role="alert">
    <div class="img-icon icon-error"></div><?php echo implode("<br />",$msg)?>
  </div>
  <?php } ?>
  <?php if(isset($mensagemOk)) { ?>
    <div class="alert alert-success" role="alert">
      <div class="img-icon icon-ok"></div><?php echo $mensagemOk?>
    </div>
  <?php } ?>
  <div class="setaabaixo">
    <div class="seta"><img src="/img/coracao_batendo.gif" />&nbsp;Seu Cadastro<span class="require require_float">* Campos obrigatorios</span></div>
  </div>
  
  <?php if(!$id>0){ ?>
  <p>
    <input type="checkbox" id="aceite-termo-de-uso" value="S" />&nbsp;<label for="aceite-termo-de-uso">Eu li e concordo com os</label> <a href="/termos-de-uso" target="_blank">termos de uso</a>
  </p>
  <?php } ?>
  
  <?php if ($id > 0) { ?>
    <div class="alinhadoDireita">
      <?php echo $email ?>
    </div>
  <?php } ?>

  <?php if ($id > 0) { ?>
  <!--div class="alinhadoDireita">
    <label for="senha">&nbsp;</label><a href="/Login/alteraSenha"><?php echo ($senha!="")?"Alterar senha":"Definir senha"?></a>
  </div-->
  <?php } ?>
  
  <p>
    <label for="nome">Nome: <span class="require"> *</span></label>
    <input type="text" name="nome" id="nome" placeholder="Nome" autocomplete="off" value="<?php echo $nome ?>" autofocus class="form-control" />
  </p>

  <?php if (!$id > 0) { ?>
  <p>
    <label for="email">Email: <span class="require"> *</span></label>
    <input type="text" name="email" id="email" placeholder="exemplo@servidor.com" autocomplete="off" value="<?php echo $email?>" class="form-control" />
  </p>
  <?php } ?>
  
  <?php if (!$id > 0) { ?>
  <p>
    <label for="senha"><img src="/img/key_icon.gif" align="absmiddle" /> Senha: <span class="require"> *</span></label>
    <input type="password" name="senha" id="senha" value="" class="form-control" placeholder="Senha" />
  </p>
  <p>
    <label for="confirmaSenha"><img src="/img/key_icon.gif" align="absmiddle" /> Confirmação: <span class="require"> *</span></label>
    <input type="password" name="confirmaSenha" id="confirmaSenha" value="" class="form-control" placeholder="Confirme a senha" />
  </p>
  <?php } ?>
  
  <p>
    <label for="telefone">Celular: </label>
    <input type="text" name="telefone" id="telefone" value="<?php echo $telefone?>" placeholder="Ex.: (99)9999-99999" class="form-control" />
  </p>
  
  <p>
    <input type="checkbox" name="receber_sms" id="receber_sms"<?php echo $receber_sms=="S"?' checked':''?> value="S" /><label for="receber_sms">&nbsp;Deseja receber SMS de aviso sobre doação? </label>
    
  </p>  
  <p>
    <label for="nascimento">Data de nascimento: </label>
    <input type="text" name="nascimento" id="nascimento" value="<?php echo $nascimento?>" placeholder="Ex.: <?php echo date('d/m/Y');?>" class="form-control" />
  </p>
  <p>
    <label for="sexo">Sexo: </label>
    <select name="sexo" id="sexo" class="form-control">
      <option value="">Escolha</option>
      <option value="F"<?php echo $sexo=='F'?' selected':''?>>Feminino</option>
      <option value="M"<?php echo $sexo=='M'?' selected':''?>>Masculino</option>
    </select>
  </p>
  <p>
    <label for="tipoSanguineo">Tipo sanguineo: </label>
    <select name="tipoSanguineo" id="tiposanguineo" class="form-control">
      <option value="">Escolha</option>
      <?php foreach($tiposSanguineos as $key => $value) { ?>
      <option value="<?php echo $value?>"<?php echo $value==$tipo_sanguineo?' selected':''?>><?php echo $value?></option>
      <?php } ?>
    </select>
  </p>
  
  <p>
    <label for="uf">UF: </label>
    <select name="uf" id="uf" class="form-control">
      <option value="">Escolha</option>
      <?php foreach($estados as $key => $value) { ?>
        <option value="<?php echo $value['Estado']['uf']?>"<?php echo $value['Estado']['uf']==$uf?' selected':''?>><?php echo $value['Estado']['nome']?></option>
      <?php } ?>
    </select>
  </p>

  <p>
    <label for="cidade">Cidade: </label>
    <select name="cidade" id="cidade" class="form-control"<?php echo empty($cidade) && empty($uf)?' disabled':''?><?php echo empty($cidade)?' title="Selecione o estado"':''?>>
      <option value="">Escolha</option>
      <?php if(isset($cidades)) { ?>
        <?php foreach($cidades as $key => $value) { ?>
          <option value="<?php echo $value['Cidade']['id']?>"<?php echo $value['Cidade']['id']==$cidade?' selected':''?>><?php echo $value['Cidade']['nome']?></option>
        <?php } ?>
      <?php } ?>
    </select>
  </p>  
  
  <input type="hidden" name="id_social" value="<?php echo $id_social > 0 ? $id_social : '' ?>" />
  <input type="hidden" name="id" id="id" value="<?php echo $id > 0 ? $id : '' ?>" />

  <div id="controles">
    <hr />
    <p>
      <button class="btn btn-lg btn-success"<?php if(!$id>0){ ?> disabled="disabled"<?php } ?> id="btn-login">Salvar</button>
    </p>
  </div>
</form>