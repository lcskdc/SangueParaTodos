<?php

echo $this->Html->css('/js/owl-carousel/owl.carousel.css');
echo $this->Html->script('/js/owl-carousel/owl.carousel.min.js');
echo $this->Html->script('/js/home.js');
?>

<!--div id="banner">
  <div class="item"><img src="/img/img-banner-2.png" /></div>
  <div class="item"><img src="/img/img-banner-3.png" /></div>
  <div class="item"><img src="/img/img-banner-1.png" /></div>
</div-->
<!--div class="banner banner-1"></div-->

<div id="dv-home">
  <div class="dv-home-mapa">
    <div class="setaabaixo">
      <div class="seta">
        <img src="/img/coracao_batendo.gif" />&nbsp;Locais próximas a você 
        <a href="#self" class="icon-minha-localizacao" title="Localização do navegador"><span data-toggle="tooltip" data-placement="bottom" title="Localização do navegador"><img width="40" src="/img/icon_mylocation.png" /></span></a>
        <a href="#self" class="icon-edit-localizacao" title="Alterar localização"><span data-toggle="tooltip" data-placement="bottom" title="Alterar localização"><img src="/img/alterar_localizacao.png" /></span></a>
      </div>
    </div>
    <div id="mapa" class="mapa mapa-loading"></div>
    <p class="ind_mapa">
      <span><img height="20" src="/img/icone-centro-coleta.png" />&nbsp;Centro de coleta</span>
      <span><img height="20" src="http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=%E2%80%A2|59E369" />&nbsp;Demanda em local pré cadastrado</span>
      <span><img height="20" src="http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=%E2%80%A2|5477EB" />&nbsp;Demanda em local informado pelo usuário</span>
    </p>
  </div>

  <div class="dv-ranking">

    <?php
    if(isset($topDoadores)) { ?>
    <div class="dv-top-doadores">
      <h2><center>Top doadores</center></h2>
      <table align="center">
        <tbody>
          <?php foreach($topDoadores as $k => $v) {  ?>
          <tr>
            <td class="td-right"><img src="<?php echo $v['img']?>" class="img-circle" /><span class="label lbl-pontos" data-toggle="tooltip" data-placement="top" title="<?php echo $v['pontos']?> <?php echo $v['pontos']>1?'pontos':'ponto'?>"><?php echo $v['pontos']?></span></td>
            <td><?php echo $v['colaborador']?></td>
          </tr>
          <?php
          } ?>
        </tbody>
      </table>
    </div>
    <?php
    }
    
    if(isset($topDivulgadores)) { ?>
    <div class="dv-top-divulgadores">
      <h2><center>Top divulgadores</center></h2>
      <table align="center">
        <tbody>
          <?php foreach($topDivulgadores as $k => $v) {  ?>
          <tr>
            <td class="td-right"><img src="<?php echo $v['img']?>" class="img-circle" /><span class="label lbl-divulgacoes" data-toggle="tooltip" data-placement="top" title="<?php echo $v['divulgacoes']?> <?php echo $v['divulgacoes']>1?'divulgações':'divulgação'?>"><?php echo $v['divulgacoes']?></span></td>
            <td><?php echo $v['colaborador']?></td>
          </tr>
          <?php
          } ?>
        </tbody>
      </table>
    </div>
    <?php } ?>

    <div class="noFloat">&nbsp;</div>

  </div>

  <div class="dv-controles-rodape">
    <a href="https://github.com/lcskdc/SangueParaTodos" target="_blank" title="Fork this project"><img src="/img/github-logo.png" /></a>
    <img class="separador" src="/img/separador_200.png" />
    <a href="https://www.facebook.com/sangueparatodos?fref=ts" target="_blank" title="Curta nossa página no Facebook"><img src="/img/img_logo_spt_facebook.png" /></a>
    <img class="separador" src="/img/separador_200.png" />
    <img src="/img/icon-responsivo.png" />
  </div>
  
  
</div>

<div class="modal fade" id="modalAlteraEndereco" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Sangue para todos</h4>
      </div>
      <div class="modal-body">
        <p>
          <input type="text" name="endereco" id="endereco" class="form-control" placeholder="Insira um endereço" />
        </p>
        <p>

        </p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Voltar</button>
      </div>
    </div>
  </div>
</div>

<script language="javascript">
  $(function() {
    $('[data-toggle="tooltip"]').tooltip()

    $('.icon-minha-localizacao').click(function(){
      getLocalizacao();
    });

    $('.icon-edit-localizacao').click(function() {
      $('#modalAlteraEndereco').modal();
      $('#endereco').val('');

      $('#endereco').keyup(function(e) {
        if (e.keyCode == 13) {
          var endereco = $(this).val();
          
          buscaEndereco(endereco).done(function(data) {
            if (data.status == "OK") {
              $('#endereco').removeClass('localNaoEncontrado').prop("title", "Informe o endereço");
              if (data.results.length > 1) {
                $("#lista_locais").remove();
                var lista = document.createElement('ul');
                $.each(data.results, function(k, v) {
                  $(lista).prop("id", "lista_locais");
                  var g = v.geometry.location.lat + ',' + v.geometry.location.lng;
                  $(lista).append('<li rel="' + g + '">' + v.formatted_address + '</li>');
                  $("#modalAlteraEndereco .modal-body").append(lista);
                });
                $("#lista_locais li").click(function() {
                  $('#endereco').val($(this).attr("rel"));
                  var g = $('#endereco').val().split(",");
                  $("#modalLocais").modal("hide");
                  $("#lista_locais").remove();
                  
                  $.post('/Local/setLocalizacaoUsuario',{latitude: g[0], longitude: g[1]},function(){
                    criarMapaLocalizacaoManual({latitude: g[0], longitude: g[1]});
                  });
                  
                  $("#modalAlteraEndereco").modal("hide");
                });
              } else {
                var loc = data.results[0].geometry.location;
                $('#endereco').val(loc.lat + ',' + loc.lng);
                
                $.post('/Local/setLocalizacaoUsuario',{latitude: loc.lat, longitude: loc.lng},function(){
                  criarMapaLocalizacaoManual({latitude: loc.lat, longitude: loc.lng});
                });
                
                $("#modalAlteraEndereco").modal("hide");
              }
            } else if (data.status == "ZERO_RESULTS") {
              $('#endereco').addClass('localNaoEncontrado').prop("title", "O endereço informado não foi encontrado");
            }
          });

        }
      });

    });
  });
</script>

