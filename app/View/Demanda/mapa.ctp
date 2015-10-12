<!DOCTYPE html>
<html>
  <head>
    <?php echo $this->Html->charset(); ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Adicionar local</title>
    <?php
    echo $this->Html->css('/css/bootstrap/css/bootstrap.css');
    echo $this->Html->css('/css/bootstrap/css/bootstrap-theme.min.css');
    
    echo $this->Html->css('demanda');
    echo $this->Html->script('jquery');
    echo $this->Html->script('https://maps.googleapis.com/maps/api/js?v=3.exp');
    echo $this->Html->script('/css/bootstrap/js/bootstrap.min.js');
    echo $this->Html->script('localizacao');
    echo $this->Html->script('demanda_mapa');
    ?>
    <style type="text/css">
      body, html {margin:0;padding:0;overflow:hidden}
    </style>
  </head>
  <body>
    <div id="conteudo_mapa">
      <div id="mapa_controles">
        <input type="text" title="Informe o endereço" id="local" value="" placeholder="Ex.: Av Paulista 100, São Paulo, SP" />
        <a href="#self" id="aBuscaLocal"><img src="/img/icon-search.png" /></a>
        <img src="/img/carregando_p.gif" id="imgLoading" />
        <input type="hidden" id="posicao" value="" />
        <button class="btn btn-default" id="btn-adicionar" disabled="disabled">Adicionar</button>
        <button class="btn btn-primary" id="btn-fechar">Fechar</button>
      </div>
      <div id="mapa" class="mapa"></div>
    </div>
    
    <div class="modal fade" id="modalLocais" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">Seleção de local</h4>
          </div>
          <div class="modal-body">
            
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
          </div>
        </div>
      </div>
    </div>
    
  </body>
</html>
