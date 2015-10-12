<?php
$con = mysql_connect("localhost","root","usbw");
mysql_select_db('sangue');
$sql = "SELECT * FROM locais";
$r = mysql_query($sql,$con);
while($rs = mysql_fetch_assoc($r)) {
    $locais[] = $rs['id'];
}
$sql = "SELECT * FROM colaboradores";
$r = mysql_query($sql,$con);
while($rs = mysql_fetch_assoc($r)) {
    $colaboradores[] = $rs['id'];
}

for($i=0;$i<1000;$i++){
    $colaborador = $colaboradores[rand(0,count($colaboradores)-1)];
    $local = $locais[rand(0,count($locais)-1)];
    $qtde = rand(1, 10);
    $sql = "INSERT INTO demandas (id_colaborador,id_local,qtde,validade,nome,descricao) VALUES ('$colaborador','$local','$qtde',NOW()+$qtde,'Demanda inserida para teste script','Demanda inserida para teste script')";
    mysql_query($sql) or die(mysql_error().'<br /><br />'.$sql);
}