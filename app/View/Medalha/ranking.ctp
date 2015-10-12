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
    border:1px solid #a4a3a3;
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
  
</style>

<div id="lista_ranking">
  <?php
  
  if($lst_ranking) {   ?>
  <h2>Sua pontuação</h2>
  <table>
    <thead>
      <tr>  
        <th>Usuario</th>
        <th>Pontuacao</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($lst_ranking as $k => $v) {  ?>
      <tr>
        <td><?php echo $v['nome']?></td>
        <td class="pontos"><?php echo $v['pontos']?></td>
      </tr>
      <?php
      } ?>
    </tbody>
  </table>
  <?php } ?>

  
</div>
