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
  
  <label for="nome">Evento:</label>
  <div class="list-group">
  <?php foreach($tipos as $key => $v) { ?>
    <a href="#self" class="list-group-item" rel="<?php echo $v['id']?>">
      <h4 class="list-group-item-heading"><?php echo $v['descricao']?> (<span class="dv-dias"><?php echo $v['prazo']?></span> dias)</h4>
      <div class="list-group-item-text"><?php echo $v['desc_msgs']?></div>
    </a>
  <?php } ?>
  </div>
  <input type="hidden" name="idEvento" id="idEvento" value="<?php echo $idEvento?>" />
  <!--p>
    <select name="evento" id="evento" class="form-control">
      <option value="0">Selecione</option>
      <?php foreach($tipos as $key => $v) { ?>
        <option value="<?php echo $v['id']?>"><?php echo $v['descricao']?> - <?php echo $v['prazo']?> dias</option>
      <?php } ?>
    </select>
  </p-->
  
  <!--p>
    <label for="validade">Data:</label>
    <input type="text" name="validade" class="form-control" id="validade" value="<?php echo date('d/m/Y')?>" />
  </p-->
  
  <div class="dv-calendario">
    
  </div>
  
  <p class="ctrls">
    <input type="hidden" name="validade" id="validade" value="<?php echo $validade?>" />
    <input type="submit" class="btn btn-lg btn-success" value="Salvar" />
  </p>
  
</form>
<script language="javascript">

  $(function(){
    
    $('.list-group-item').click(function(){
      $('.list-group .list-group-item').removeClass('active');
      $(this).addClass('active');
      var idEvento = $(this).attr('rel');
      $('#idEvento').val(idEvento);
      $('.dv-calendario').html('<img src="/img/carregando.gif" />');
      $.post('/Evento/meses',{idEvento:idEvento,validade:$('#validade').val()},function(data){
        $('.dv-calendario').html(data);
        atualizaFuncoesMeses();
        $('#validade').val($('.dv-calendario .meses .selecionado').parent().find('.cx-data').val());
      });      
    });
    
  });
  
  function atualizaFuncoesMeses() {
    $('.dv-calendario .meses').click(function(){
      $('.dv-calendario .meses .selecionado').removeClass('selecionado');
      $(this).find('.selecao').addClass('selecionado');
      $('#validade').val($(this).find('.cx-data').val());
    });    
  };
  
</script>
<style type="text/css">
  .dv-calendario {
    margin:10px auto;
    text-align: center;
  }
  
  .dv-calendario .meses {
    padding:10px;
    width:auto;
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