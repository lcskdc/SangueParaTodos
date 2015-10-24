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
    <div class="alert alert-info">
      <img src="/img/icon-info.png" style="position:absolute;margin-left:-30px;margin-top:-30px;" />Esta área é destinada para que você nos indique qual foi seu último evento, para que possamos entender o prazo e lhe avisar perto da data de uma nova doação.<br />Existem alguns eventos e/ou acontecimentos que restringem a doação de sangue por um determinado período. Abaixo listamos os mais comuns. <a href="http://bvsms.saude.gov.br/bvs/saudelegis/gm/2013/prt2712_12_11_2013.html" target="_blank">Leia mais</a>. 
    </div>
  </p>
  <p>
    <label for="nome">Evento:</label>
    <select name="evento" id="evento" class="form-control">
      <option value="0">Selecione</option>
      <?php foreach($tipos as $key => $v) { ?>
        <option value="<?php echo $v['TipoEvento']['id']?>"><?php echo $v['TipoEvento']['descricao']?> - <?php echo $v['TipoEvento']['prazo']?> dias</option>
      <?php } ?>
    </select>
  </p>
  <!--p>
    <label for="validade">Data:</label>
    <input type="text" name="validade" class="form-control" id="validade" value="<?php echo date('d/m/Y')?>" />
  </p-->
  
  <div class="dv-calendario">
    
    <?php foreach($meses as $k => $v) {
    $classe = $k==count($meses)-1?'':' dv';
    $classe_sel = $v['mes'].'/'.$v['ano']==$selecionado?' selecionado':'';
    ?>
    <div class="meses<?php echo $classe?>" rel="<?php echo $v['mes'].'/'.$v['ano']?>"><div class="selecao<?php echo $classe_sel?>">&nbsp;</div><br /><span class="mes"><?php echo $v['mes_ext']?></span><br /><span class="ano"><?php echo $v['ano']?></span><input type="hidden" class="cx-data" value="<?php echo $v['mes'].'/'.$v['ano']?>" /></div>
    <?php } ?>
    
  </div>
  
  <p class="ctrls">
    <input type="hidden" name="validade" id="validade" value="<?php echo date('m/Y')?>" />
    <input type="submit" class="btn btn-lg btn-success" value="Salvar" />
  </p>
  
</form>
<script language="javascript">

  $(function(){
    $('.dv-calendario .meses').click(function(){
      $('.dv-calendario .meses .selecionado').removeClass('selecionado');
      $(this).find('.selecao').addClass('selecionado');
      $('#validade').val($(this).find('.cx-data').val());
    });
  });
  
</script>
<style type="text/css">
  .dv-calendario {
    margin:10px auto;
    width:410px;
  }
  
  .dv-calendario .meses {
    padding:10px;
    width:32%;
    display:inline-block;
    text-align:center;
    cursor:pointer;
  }
  
  .dv-calendario .meses:hover {
    background:#FBFDD0;
  }
  
  .dv-calendario .dv {
    border-right:1px solid #EBEBEB;
  }
  
  .dv-calendario .mes {
    font-size:16px;
  }
  
  .dv-calendario .ano {
    font-size:20px;
  }
  
  .selecao {
    display:inline-block;
  }
  
  .selecionado {
    background:url('/img/test-pass-icon.png') no-repeat;
    width:16px;
  }
  
  .ctrls {
    margin-top:30px;
    text-align:center;
  }
  
  .ctrls .btn {
    width:50%;
  }
  
</style>