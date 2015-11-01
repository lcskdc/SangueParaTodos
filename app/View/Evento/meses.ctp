<?php foreach($meses as $k => $v) {
$classe = $k==count($meses)-1?'':' dv';
$classe_sel = $v['mes'].'/'.$v['ano']==$selecionado?' selecionado':'';
?>
<div class="meses<?php echo $classe?>" rel="<?php echo $v['mes'].'/'.$v['ano']?>"><div class="selecao<?php echo $classe_sel?>">&nbsp;</div><br /><span class="mes" title="<?php echo $v['mes_ext']?>"><?php echo substr($v['mes_ext'],0,3)?></span><br /><span class="ano"><?php echo $v['ano']?></span><input type="hidden" class="cx-data" value="<?php echo $v['mes'].'/'.$v['ano']?>" /></div>
<?php } ?>