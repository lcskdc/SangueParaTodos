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
  
  
  #lista_pontuacao h2 {
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
    border:1px solid #000;
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
    width:100%;
    margin:0 auto;
    background:#EFEFEF;
  }
  
  #dv-badges-indicador-maos-dadas .indicador-maos-dadas .dv-fundo {
    float:left;
    height:70px;
  }
  
  #dv-badges-indicador-maos-dadas .indicador-maos-dadas .dv-fundo-vermelho {
    background:#D62A2A;
    border:1px solid #D62A2A;
    width:70%;
  }
  
</style>

<div id="dv-badges-indicador-maos-dadas">
  <div class="indicador-maos-dadas">
    <div class="dv-fundo dv-fundo-vermelho"></div>
    <div class="img-maos-dadas"></div>
  </div>
  <img src="/img/img-igual.png" />
  <img src="/img/medalhas/medalha-1.png" height="70" />
</div>

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
