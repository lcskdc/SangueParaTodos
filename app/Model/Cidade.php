<?php

App::uses('Model', 'Model');

class Cidade extends AppModel {
  public $useTable = 'cidades';
  public $belongsTo = 'Estado';

}
