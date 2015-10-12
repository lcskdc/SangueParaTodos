<?php
ini_set('include_path','C:\Users\lpoliveira\Desktop\PESSOAL\webserver\root\sangue\lib');
include("Cake\Model\Model.php");
include("Cake\Model\ConnectionManager.php");
class BuscaLocais  {
  
  
  
  public function getLocaisGeoLocalizacao($latitude, $longitude, $raio) {
    
	echo ini_get('include_path');
	$conn = ConnectionManager::get('default');
	print_r($this);
     //print_r($this->find('all'));
    
  }

}
