<?php
$classes_css_sangue = array(
'A-'=>'bg-amenos',
'A+'=>'bg-amais',
'AB-'=>'bg-abmenos',
'AB+'=>'bg-abmais',
'B-'=>'bg-bmenos',
'B+'=>'bg-bmais',
'O-'=>'bg-omenos',
'O+'=>'bg-omais',
'todos'=>'bg-todos'
);
?>
<script language="javascript">
  //google.maps.event.addDomListener(window,'load',criarMapa);
</script>

<div class="demandas">
  
  <!--div class="alert alert-info">
    <p><strong>Demandas</strong> são solicitações de doações em um determinado local e/ou instituição. Como base nos dados deste cadastro, é exibido para os demais usuários do portal, a informação de que você precisa de doadores.</p>
  </div-->

  <ul id="lista_demandas">
  <?php
  
  if(count($locais)>0) {
    //echo '<pre>',print_r($locais),'</pre>';
    foreach($locais as $key => $obj) {
      if($obj['tipo']=='demanda') { ?>
      <li>
        <div class="controles">
          <a class="rotacionar" href="/Local/demandas/<?php echo $obj['id_colaborador']?>"><img class="img-circle" title="Postado por <?php echo $obj['nm_colaborador']?>" src="<?php echo $obj['img']?>" /></a>
          <?php if($tipo_social=='facebook') { ?>
            <a title="Compartilhar via Facebook" class="btn-compartilhar-solicitacao rotacionar" href="#self" onclick="compartilharSolicitacao(<?php echo $obj['id_colaborador']?>, <?php echo $obj['id']?>)"></a>
          <?php } ?>
          
          <?php if($obj['url_rota']!="") { ?>
            <a class="rotacionar" title="Visualizar no mapa" href="<?php echo $obj['url_rota']?>" target="_blank"><img width="50" alt="Veja no mapa como chegar" class="img-circle" src="/img/icon_mapa_rota_60.png" /></a>
          <?php } ?>
            
          <a href="#self" class="denunciar_erro" rel="<?php echo $obj['id']?>" class="rotacionar" title="Informar erro"><img src="/img/warning_48.png" /></a>
        </div>

        <p><strong>Descrição: </strong><?php echo $obj['descricao']?></p>
        <p><strong>Paciente: </strong><?php echo $obj['paciente']?></p>
        <p><strong>Doadores: </strong><?php echo $obj['doadores']?></p>
        <p><strong>Local: </strong><?php echo $obj['instituicao']?></p>
        <p>
          <?php if($obj['id_local']>0){ ?>
          <img height="24" style="float:left;" src="http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=%E2%80%A2|F56A58" title="Local pré cadastrado" />
          <?php } else { ?>
          <img height="24" style="float:left;" src="http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=%E2%80%A2|5477EB" title="Local cadastrado pelo usuário" />
          <?php } ?>
          &nbsp;<?php echo $obj['endereco']?>
          <?php if($obj['distancia']>0) { ?> (<a target="_blank" href="<?php echo $obj['url_rota']?>" title="Visualizar no mapa"><?php echo $obj['distancia']?> km</a>)<?php } ?>

        </p>
        <p><strong>Tipos sanguíneos: </strong>
        <?php
          $tipos = explode(',',$obj['tipos_sangue']);
          foreach($tipos as $k => $v) {
            ?><span class="badge <?php echo $classes_css_sangue[$v]?>"><?php echo $v?></span>&nbsp;<?php
          }
        }
        ?>
        </p>
        <p><strong>Validade: </strong><?php echo date('d/m/Y', strtotime($obj['validade']));?></p>
      </li>
    <?php }
  } else if($flt_demanda>0) { 
    ?><center><p>A solicitação não está mais ativa.</p><p><a href="/Demanda/cadastro" class="btn btn-success btn-lg">Cadastrar uma solicitação</a></p></center><?php
  } else if($flt_colaborador>0) {
    ?><center><p>Você ainda não possui solicitações cadastradas.</p><p><a href="/Demanda/cadastro" class="btn btn-success btn-lg">Cadastrar uma solicitação</a></p></center><?php
  } else {
    ?><center><p>Não há solicitações cadastradas.</p><a href="/Demanda/cadastro" class="btn btn-success btn-lg">Cadastrar uma solicitação</a></center><?php
  }
  ?>
  </ul>
</div>

<div class="modal fade bg-parabens" id="modalSolicitacaoCompartilhada" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Obrigado</h4>
      </div>
      <div class="modal-body">
        Obrigado pela sua ajuda.
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="modalDenunciarErro" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Conte-nos sobre o erro</h4>
      </div>
      <div class="modal-body">
        <form name="frmDenuncia" id="frmDenuncia">
        <p>
          <input type="radio" name="acao" id="rb_mudou" value="1" /><label for="rb_mudou">&nbsp;Localização inválida</label><br />
          <input type="radio" name="acao" id="rb_desatualizado" value="2" /><label for="rb_desatualizado">&nbsp;Uma informação desatualizada</label><br />
          <input type="radio" name="acao" id="rb_outros" value="3" /><label for="rb_outros">&nbsp;Outros</label>
        </p>
        <p>
          <textarea class="form-control" placeholder="Reporte-nos o erro encontrado" title="Reporte-nos o erro encontrado" name="observacao_denuncia" id="observacao_denuncia"></textarea>
        </p>
        <p id="controles">
          <input type="hidden" name="idDemanda" id="idDemanda" value="" />
          <button class="btn btn-primary" id="btn-envia-denuncia">Enviar</button> ou <a href="#self" id="cancelar-denuncia">Cancelar</a>
        </p>
        <p id="registrando" class="escondido">
          <img src="/img/carregando.gif" />&nbsp;Registrando a sua solicitação
        </p>
        </form>
      </div>
    </div>
  </div>
</div>

<!--div id="mapa" class="mapa" style="width:700px; height:420px;"></div-->