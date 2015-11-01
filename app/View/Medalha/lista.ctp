<?php 
echo $this->Html->css('/css/bootstrap/css/bootstrap.css');
echo $this->Html->css('/js/jquery-ui/jquery-ui.css');
echo $this->Html->Script('/js/jquery-ui/jquery-ui.min.js');
echo $this->Html->css('demanda');
?>

<style type="text/css">
  
  #lista_pontuacao {
    width:90%;
    margin:0 auto;
  }
  
  .pontos {
    text-align:center;
  }
  
  h2 {
    text-align:center;
    margin:0;
    padding:10px 0;
  }
  
  #lista_pontuacao table {
    width:100%;
  }
  
  #lista_pontuacao table tbody td {
    padding:5px 7px;
    font-size:16px;
  }
  
  #lista_pontuacao table thead th {
    background:#a4a3a3;
    font-size:16px;
    color:#FFF;
    height:40px;
    line-height:40px;
    padding:5px 7px;
  }
  
  #lista_pontuacao table tfoot td {
    background:#a4a3a3;
    color:#FFF;
    height:35px;
    line-height:35px;
    padding:0 4px;
    text-align:center;
    font-weight:bold;
  }
  
  #lista_pontuacao table tbody td {
    height:45px;
    line-height:45px;    
  }
  
  #lista_pontuacao table tbody tr {
    background: #e9e9e9;
  }
  
  #lista_pontuacao table tbody tr:nth-of-type(odd) {
    background: #f6f6f6;
  }
  
  .img-maos-dadas {
    width:100%;
    height:70px;
    background:url('/img/img_maos_dadas.png') repeat-x;
    position:relative;
  }
  
  #dv-badges-indicador-maos-dadas {
    min-width:430px;
    max-width:553px;
    margin:0 auto;
  }
  
  #dv-badges-indicador-maos-dadas img {
    display:inline-block;
    margin:0;
  }
  
  #dv-badges-indicador-maos-dadas .indicador-maos-dadas {
    float:left;
    width:70%;
    margin:0 auto;
  }
  
  #dv-badges-indicador-maos-dadas .indicador-maos-dadas .indicador {
    dislay:block;
    float:left;
    width:10%;
    height:60px;
    margin:0;
    padding:0;
    background-size: cover !important;
  }
  
  #dv-badges-indicador-maos-dadas ul .selecionado {
    background-color:red !important;
  }
  
  #dv-badges-indicador-maos-dadas ul {
    margin:0;
    list-style:none;
    list-style-image: none;
    padding:0;
  }
  
  #dv-badges-indicador-maos-dadas .indicador-maos-dadas .indicador-par {
    background: #f6f6f6 url('/img/img_maos_dadas_par.png') no-repeat;
  }
  
  #dv-badges-indicador-maos-dadas .indicador-maos-dadas .indicador-impar {
    background: #f6f6f6 url('/img/img_maos_dadas_impar.png') no-repeat;
  }
  
  #lista_medalhas {
    text-align:center;
  }
  
  #lista_medalhas img {
    height:60px;
  }
  
  .marginBottom {
    margin-bottom:35px;
  }
  
</style>

<?php if($tiposocial=='facebook') { ?>
<h2>Seus compartilhamentos</h2>
<div id="dv-badges-indicador-maos-dadas">
  <div class="indicador-maos-dadas">
    <ul>
    <?php
    for($i=0;$i<10;$i++) { ?>
      <li class="indicador indicador-<?php echo $i%2==0?'par':'impar'?><?php echo $i<$ncomp?' selecionado':''?>"></li>
    <?php } ?>
    </ul>
  </div>
  <?php if($proximaMedalhaCompartilhamento!=null) { ?>
    <img src="/img/img-igual.png" height="60" />
    <img src="/img/medalhas/<?php echo $proximaMedalhaCompartilhamento['imagem']?>" height="60" />
  <?php } else { ?>
    <strong><?php echo $ncomp?></strong>
  <?php } ?>
</div>
<div class="noFloat marginBottom"></div>
<?php } ?>

<?php if($medalhas) { ?>
<h2>Suas medalhas</h2>
<div id="lista_medalhas">
  <?php
  foreach($medalhas as $k => $v) { ?>
    <img title="<?php echo $v['Medalha']['descricao']?>" src="/img/medalhas/<?php echo $v['Medalha']['imagem']?>" />
  <?php } ?>
</div>
<div class="noFloat marginBottom"></div>
<?php } ?>

<div id="lista_pontuacao">
  <?php
  $pontos = 0;
  
  if($lst_pontos) { ?>
  <h2>Sua pontuação</h2>
  <table>
    <thead>
      <tr>  
        <th>Data</th>
        <th>Descricao</th>
        <th>Pontos</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($lst_pontos as $k => $v) {  ?>
      <tr>
        <td><?php echo CakeTime::format($v['Gamification']['data'], '%d/%m/%Y %H:%M');?></td>
        <td><?php echo $v['ColaboradorTipoAcao']['Descricao']?></td>
        <td class="pontos"><?php echo $v['Gamification']['pontos']?></td>
      </tr>
      <?php
      $pontos+=$v['Gamification']['pontos'];
      } ?>
    </tbody>
    <tfoot>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;Total</td>
        <td><?php echo max($pontos,0)?></td>
      </tr>
    </tfoot>
  </table>
  <?php } else { ?>
    <h2>Você ainda não possui pontos.</h2>
  <?php } ?>

  
</div>
