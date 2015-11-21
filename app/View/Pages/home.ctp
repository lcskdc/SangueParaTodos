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
        <img src="/img/coracao_batendo.gif" />&nbsp;Locais próximas a você <a href="#self" title="Saiba mais" data-toggle="modal" data-target="#modalInfoGeoLocalizacao"><img src="/img/icone-mais.png" align="absmiddle" /></a>
        <a href="#self" class="icon-minha-localizacao" title="Localização do navegador"><span data-toggle="tooltip" data-placement="bottom" title="Localização do navegador"><img width="40" src="/img/icon_mylocation.png" /></span></a>
        <a href="#self" class="icon-edit-localizacao" title="Alterar localização"><span data-toggle="tooltip" data-placement="bottom" title="Alterar localização"><img src="/img/alterar_localizacao.png" /></span></a>
      </div>
    </div>
    <div id="mapa" class="mapa mapa-loading"></div>
    <div class="ind_mapa">
      <div class="ind-1">
        <img height="30" src="/img/icone-centro-coleta.png" />&nbsp;Centros de coleta. Local cadastrado por nossa equipe
      </div>
      <img src="/img/separador_60.jpg" class="img-sep" />
      <div class="ind-2">
        <img height="30" src="/img/green-dot.png" />&nbsp;Um usuário do portal solicitou doações neste local. Este local foi pré cadastrado por nossa equipe
      </div>
      <img src="/img/separador_60.jpg" class="img-sep" />
      <div class="ind-3">
        <img height="30" src="/img/blue-dot.png" />&nbsp;Um usuário solitou doações neste local. Este local foi cadastrado pelo usuário.
      </div>
    </div>
  </div>

  <div class="dv-ranking">

    <?php
    if(isset($topDoadores)) { ?>
    <div class="dv-top-doadores">
      <h2><center>Ranking</center></h2>
      <table align="center">
        <tbody>
          <?php foreach($topDoadores as $k => $v) {  ?>
          <tr>
            <td class="td-right"><img src="<?php echo $v['img']?>" class="img-circle" /><span class="label lbl-pontos" data-toggle="tooltip" data-placement="top" title="<?php echo $v['pontos']?> <?php echo $v['pontos']>1?'pontos':'ponto'?>"><?php echo $v['pontos']?></span></td>
            <td title="<?php echo $v['colaborador']?>"><?php echo strlen($v['colaborador'])>25?substr($v['colaborador'],0,25).'...':$v['colaborador']?></td>
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
            <td title="<?php echo $v['colaborador']?>"><?php echo strlen($v['colaborador'])>25?substr($v['colaborador'],0,25).'...':$v['colaborador']?></td>
          </tr>
          <?php
          } ?>
        </tbody>
      </table>
    </div>
    <?php } ?>

    <div class="noFloat">&nbsp;</div>

  </div>

  <div class="noFloat">&nbsp;</div>
  
  <div class="dv-controles-rodape">
    <a href="https://www.facebook.com/sangueparatodos?fref=ts" target="_blank" title="Curta nossa página no Facebook"><img src="/img/img_logo_spt_facebook.png" /></a>
    <img class="separador" src="/img/separador_200.png" />
    <img src="/img/icon-responsivo.png" />
    <img class="separador" src="/img/separador_200.png" />
    <a href="https://github.com/lcskdc/SangueParaTodos" target="_blank" title="Fork this project"><img src="/img/github-logo.png" /></a>
  </div>
  
</div>

<div class="modal fade" id="modalAlteraEndereco" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Definir local</h4>
      </div>
      <div class="modal-body">
        <p>
          <select title="Selecione o estado" id="hp-uf" class="form-control">
            <option value="0">UF</option>
            <?php foreach($estados as $id => $uf) { ?>
              <option value="<?php echo $uf?>"><?php echo $uf?></option>
            <?php } ?>
          </select>
          
          <select id="hp-cidade" title="Selecione a cidade" disabled="disabled" class="form-control">
            <option value="0">Selecione a cidade</option>
          </select>
          
        </p>
        <p>
          <div class="noFloat">&nbsp;</div>
          <input type="text" id="hp-endereco" class="form-control" placeholder="Insira um endereço" disabled="disabled" />
        </p>
      </div>
      <div class="modal-footer">
        <a href="#self" class="hp-outro-end">Outro endereço</a>
        <button type="button" id="hp-define-local" disabled="disabled" class="btn btn-success">Definir local</button>
        <button type="button" class="btn btn-primary" data-dismiss="modal">Voltar</button>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="modalInfoGeoLocalizacao" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Sobre a GeoLocalização</h4>
      </div>
      <div class="modal-body">
        <p>
          Diferentes fontes podem ser utilizadas para obter a localização do usuário, tendo cada uma seu próprio grau de precisão e/ou variação. Em um navegador instalado em um desktop é bem provável que o sistema de geolocalização utilize WiFi (com precisão de 20m) ou o IP, podendo fornecer algumas vezes falsas informações. Já os dispositivos móveis utilizam a técnica de triangulação do GPS (que possui precisão de 10m) , WiFi e GSM/ CDMA celular com uma precisão de 1000m.<br />Desta forma, os resultados obtidos podem variar de acordo com a coordenadas obtidas.
        </p>
        <p>
          <a href="http://g1.globo.com/tecnologia/blog/seguranca-digital/post/como-funciona-localizacao-geografica-de-um-ip.html" target="_blank">Leia mais</a>
        </p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>

