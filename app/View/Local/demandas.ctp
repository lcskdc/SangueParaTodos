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
    foreach($locais as $key => $obj) {
      if($obj['tipo']=='demanda') { ?>
      <li>
        <div class="controles">
          <a class="img_colaborador" href="/Local/demandas/<?php echo $obj['id_colaborador']?>"><img class="img-circle" title="Postado por <?php echo $obj['nm_colaborador']?>" src="<?php echo $obj['img']?>" /></a>
          <a title="Compartilhar via Facebook" class="btn-compartilhar-solicitacao rotacionar" href="#self" onclick="compartilharSolicitacao(<?php echo $obj['id_colaborador']?>, <?php echo $obj['id']?>)"></a>
          <a title="Veja no mapa como chegar" href="<?php echo $obj['url_rota']?>" target="_blank" class="rotacionar"><img width="50" alt="Veja no mapa como chegar" class="img-circle" src="/img/icon_mapa_rota_60.png" /></a>
        </div>

        <p>Descrição: <?php echo $obj['descricao']?></p>
        <p>Paciente: <?php echo $obj['paciente']?></p>
        <p>Doadores: <?php echo $obj['doadores']?></p>
        <p>Local: <?php echo $obj['instituicao']?></p>
        <p>
          <?php if($obj['id_local']>0){ ?>
          <img height="24" style="float:left;" src="http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=%E2%80%A2|F56A58" title="Local cadastrado pelo portal" />
          <?php } else { ?>
          <img height="24" style="float:left;" src="http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=%E2%80%A2|5477EB" title="Local cadastrado pelo usuário" />
          <?php } ?>
          &nbsp;<?php echo $obj['endereco']?>

        </p>
        <p>Tipos sanguíneos: </p>
        <p><?php
        $tipos = explode(',',$obj['tipos_sangue']);
        foreach($tipos as $k => $v) {
            ?><span class="badge <?php echo $classes_css_sangue[$v]?>"><?php echo $v?></span>
            <?php
        }
      } ?>
        </p>
        <p>Validade: <?php echo date('d/m/Y', strtotime($obj['validade']));?></p>

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

<!--div id="mapa" class="mapa" style="width:700px; height:420px;"></div-->