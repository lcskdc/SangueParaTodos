<?php

use Cake\Model;

class LocalController extends AppController {

  public $helpers = array('Js' => array('Jquery', 'Ajax'));

  public function index() {
    //$latitude = "-30.06324950";
    //$longitude = "-51.17878120";
    $distancia = 10;
    $this->Local->useTable = "locais";
    $this->Local->recursive = 1;

  }
  
  public function demandas($id_colaborador = 0, $id_demanda = 0) {
    $locais = $this->buscaItensProximos(15, $id_colaborador, $id_demanda);
    //print_r($this->Session->read("colaborador"));
    //echo '<pre>',print_r($locais),'</pre>';
    $this->set('locais', $locais);
    $this->set('colaborador_id',$this->Session->read("colaborador.id"));
    $this->set('flt_colaborador',$id_colaborador);
    $this->set('flt_demanda',$id_demanda);
    $this->set('tipo_social',$this->Session->read("colaborador.tipo_social"));
    $coords_usuario = $this->Session->check("colaborador.lat")?$this->Session->read("colaborador.lat").",".$this->Session->read("colaborador.lng"):'';
    $this->set('coordenadas_usuario',$coords_usuario);
  }
  
  public function vdemanda() {
    
    //session_start();
   // App::import("Vendor", "FacebookAuto", array("file" => "Facebook/autoload.php"));
    
    $server = 'http://sangueparatodos.com.br/';
    $server = 'http://localhost:9090/';
    
    $this->layout = 'ajax';
    $this->autoRender = false;
    $ref = $this->referer();
    $chave = $this->params['url']['k'];

    $this->loadModel('DemandasCompartilhadas');
    $dc = new DemandasCompartilhadas();
    
    $r = $dc->find('all',array(
      'conditions' => array('chave' => $chave,'tipo_id' => 1)
    ));
    
    if($r) {
      $pontuar = true;
      $url = '/Local/demandas/'.$r[0]['DemandasCompartilhadas']['colaborador'].'/'.$r[0]['DemandasCompartilhadas']['solicitacao'].'?chave='.$r[0]['DemandasCompartilhadas']['chave'];
      
      $this->Session->write('sangue.id_indicacao',$r[0]['DemandasCompartilhadas']['colaborador_id']);
    
      if($this->Session->check('colaborador.id')) {
        $pontuar = $this->Session->read('colaborador.id')!=$r[0]['DemandasCompartilhadas']['colaborador_id'];
      }
      
      if($pontuar) {
        $this->loadModel('Gamification');
        $gm = new Gamification();
        $r = $gm->find('first',array(
          'conditions' => array(
            'colaborador_id' => $r[0]['DemandasCompartilhadas']['colaborador_id'],
            'demanda_id' => $r[0]['DemandasCompartilhadas']['solicitacao'],
            'pontos <> ' => 75
          )
        ));

        if($r) {
          $r['Gamification']['pontos'] = 75;
          $gm->save($r);
        }
      }
      $this->redirect($url);
      
    } else {
      echo "Desculpe, mas esta demanda não está mais ativa. <a href=\"/Local/demandas/\">Clique aqui</a> para visualizar outras demandas";
    }

    //$this->redirect('/Local/demandas/'.$r[0]['DemandasCompartilhadas']['colaborador'].'/'.$r[0]['DemandasCompartilhadas']['solicitacao'].'?chave='.$r[0]['DemandasCompartilhadas']['chave']);
  }

  public function buscaItensProximos($distancia = 15, $id_colaborador = 0, $id_demanda = 0, $consulta_sem_registros = false, $consulta_locais = false) {
    
    $r = array();
    $ret = $this->getLatLng();
    //$this->loadModel('Gamification');
    
    if( $this->Session->check('colaborador.lat') && $this->Session->check('colaborador.lng') ) {
      $latitude  = $ret['latitude'];
      $longitude = $ret['longitude']; 
    } else if(isset($ret['latitude'])){
      $latitude  = $ret['latitude'];
      $longitude = $ret['longitude'];
      $distancia = $ret['distancia'];
    } else {
      $consulta_sem_registros = true;
      $longitude = $latitude = null;
    }
    
    if($consulta_locais) {
      $sqlFiltroDistancia = "HAVING distancia <= 15";
      $sql = "SELECT 
            locais.*,
            (((acos(sin(($latitude*pi()/180)) * 
                sin((latitude*pi()/180))+cos(($latitude*pi()/180)) * 
                cos((latitude*pi()/180)) * cos((($longitude-longitude)* 
                pi()/180))))*180/pi())*60*1.609344
            ) as distancia
          FROM locais
          WHERE ativo = 'S'
          $sqlFiltroDistancia
          ORDER BY distancia
          LIMIT 20";
      
      $demandas = $this->Local->query($sql);

      foreach($demandas as $key => $value) {
        $descricao = $value['locais']['nome'];
        $latitude  = $value['locais']['latitude'];
        $longitude = $value['locais']['longitude'];
        $endereco  = $value['locais']['endereco'].', '.$value['locais']['cidade'].' <br />'.$value['locais']['telefone'];
        $r[] = array(
          'tipo'           => 'local',
          'descricao'      => $descricao,
          'endereco'       => $endereco,
          'latitude'       => $latitude,
          'longitude'      => $longitude
        );
      }
    }
    
    if (empty($latitude) || empty($longitude) || $consulta_sem_registros) {
      $sql = "SELECT
          Demanda.*,
          Colaborador.nome,
          0 as distancia
      FROM demandas Demanda
        JOIN colaboradores Colaborador ON Colaborador.id = Demanda.id_colaborador
      ORDER BY Demanda.id DESC
      LIMIT 25";
    } else {
      $sqlFiltroDistancia = "HAVING distancia <= $distancia";
      $sqlFiltroColaborador = $sqlFiltroDemanda = "";

      if($id_demanda > 0) {
        $sqlFiltroDistancia = "";
        $sqlFiltroDemanda = " AND Demanda.id = $id_demanda";
      } else if($id_colaborador > 0) {
        $sqlFiltroDistancia = "";
        $sqlFiltroColaborador = " AND Demanda.id_colaborador = $id_colaborador";
      }
      
      $sql = "SELECT
          Demanda.*,
          Colaborador.nome,
          (((acos(sin(($latitude*pi()/180)) * 
              sin((Demanda.latitude*pi()/180))+cos(($latitude*pi()/180)) * 
              cos((Demanda.latitude*pi()/180)) * cos((($longitude-Demanda.longitude)* 
              pi()/180))))*180/pi())*60*1.609344
          ) as distancia
      FROM demandas Demanda
        JOIN colaboradores Colaborador ON Colaborador.id = Demanda.id_colaborador
        LEFT JOIN denuncias_demandas DenunciaDemanda ON DenunciaDemanda.id_demanda = Demanda.id AND Colaborador.id = DenunciaDemanda.id_colaborador
      WHERE 1 = 1 AND DenunciaDemanda.id IS NULL
      $sqlFiltroColaborador
      $sqlFiltroDemanda
      $sqlFiltroDistancia
      ORDER BY Demanda.id DESC
      LIMIT 100";
    }
    $demandas = $this->Local->query($sql);
    //echo '<pre>',print_r($demandas),'</pre>';
    foreach($demandas as $key => $value) {
      $d           = $demandas[$key]['Demanda'];
      $img = file_exists(getcwd().'/img/demandas/'.md5($d['id']).'.jpg')?'/img/demandas/'.md5($d['id']).'.jpg':'/img/avatar.jpg';
      
      $saddr = "";
      if($latitude != null && $longitude != null) {
        $saddr    = $latitude.','.$longitude;
      }
      
      $daddr    = $demandas[$key]['Demanda']['latitude']!=""&&$demandas[$key]['Demanda']['longitude']!=""?$demandas[$key]['Demanda']['latitude'].','.$demandas[$key]['Demanda']['longitude']:$demandas[$key]['Demanda']['endereco'];
      
      if($saddr!="") {
        $url_rota = "https://maps.google.com?saddr=$saddr&daddr=$daddr";
      }else{
        $url_rota = "https://maps.google.com?daddr=$daddr";
      }
      
      $descricao      = $d['descricao'];
      $paciente       = $d['paciente'];
      $doadores       = $d['doadores'];
      $instituicao    = $d['instituicao'];
      $endereco       = $d['endereco'];
      $validade       = $d['validade'];
      $colaborador    = $d['id_colaborador'];
      $nm_colaborador = $demandas[$key]['Colaborador']['nome'];
      $latitude       = $d['latitude'];
      $longitude      = $d['longitude'];
      $tipos_sangue   = $d['tipos_sangue'];
      $id_local       = $d['id_local'];
      $id             = $d['id'];
      $distancia      = round(isset($value[0]['distancia'])?$value[0]['distancia']:0,2);

      $r[] = array(
        'tipo'           => 'demanda',
        'descricao'      => $descricao,
        'paciente'       => $paciente,
        'doadores'       => $doadores,
        'instituicao'    => $instituicao,
        'endereco'       => $endereco,
        'validade'       => $validade,
        'id_colaborador' => $colaborador,
        'nm_colaborador' => $nm_colaborador,
        'id'             => $id,
        'latitude'       => $latitude,
        'longitude'      => $longitude,
        'id_local'       => $id_local,
        'tipos_sangue'   => $tipos_sangue,
        'img'            => $img,
        'url_rota'       => $url_rota,
        'distancia'      => $distancia
      );
      
    }
    
    return $r;
  }

  public function loc_usuario() {
    $this->autoRender = false;
    $ret = $this->getLatLng();
    echo json_encode($ret);
  }
  
  public function setLocalizacaoUsuario() {
    $this->autoRender = false;
    $this->layout = 'ajax';
    $latitude = $this->request['data']['latitude'];
    $longitude = $this->request['data']['longitude'];
    $this->Session->write("colaborador.lat",$latitude);
    $this->Session->write("colaborador.lng",$longitude);
  }

  private function getLatLng() {
    $retorno = null;
    $retorno['distancia'] = 0;

    if ($this->Session->check("colaborador.lat") && $this->Session->check("colaborador.lng")) {
      //Obtem informações da latitude e longitude adquiridos na página inicial, via GeoLocation
      $latitude = $this->Session->read("colaborador.lat");
      $longitude = $this->Session->read("colaborador.lng");
      $retorno['latitude'] = $latitude;
      $retorno['longitude'] = $longitude;
      $retorno['distancia'] = 15;
    } else if ($this->Session->check("colaborador.nome_cidade") && $this->Session->check("colaborador.uf")) {
      //Obtem a localização via Google, com base no estado e cidade, caso cadastrados pelo usuário.
      $endereco = urlencode($this->Session->read("colaborador.nome_cidade") . ' ' . $this->Session->read("colaborador.uf"));
      $url_json_address = "http://maps.google.com/maps/api/geocode/json?address=$endereco&sensor=false";
      $resp_json_address = file_get_contents($url_json_address);
      $resp = json_decode($resp_json_address);
      if ($resp->results[0]->geometry->location->lat) {
        $latitude = $resp->results[0]->geometry->location->lat;
        $longitude = $resp->results[0]->geometry->location->lng;
        $retorno = array();
        $retorno['latitude'] = $latitude;
        $retorno['longitude'] = $longitude;
        $retorno['distancia'] = 30;
      }
    }

    return $retorno;
  }

  public function markers($latitude, $longitude) {
    $this->autoRender = false; // We don't render a view in this example
    $this->response->type('json');
    $r = $this->buscaItensProximos(15,0,0,false,true);
    return json_encode($r);
  }

  public function jsonLocais() {
    $this->autoRender = false; // We don't render a view in this example
    $this->response->type('json');
    $this->Local->useTable = "locais";
    $locais = $this->Local->find('all', array(
      'conditions' => array('ativo' => 'S'),
      'fields' => array('nome', 'id'),
      'order' => array('nome')
    ));
    echo json_encode($locais);
  }

}
