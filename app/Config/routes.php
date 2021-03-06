<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/View/Pages/home.ctp)...
 */
	Router::connect('/', array('controller' => 'pages', 'action' => 'display', 'home'));
/**
 * ...and connect the rest of 'Pages' controller's URLs.
 */
    
    /*
    $uri = $_SERVER['REQUEST_URI'];
    $nuri = preg_replace('/[^0-9a-zA-Z\/_]/','',$uri);
    
    if($uri!=$nuri && strpos($uri,'?') == -1) {
      $parts = explode('/',str_replace(' ','/',trim(str_replace('/',' ',$nuri))));
      if(!in_array($parts[1],array('markers'))) {
        Router::connect($uri, array('controller' => $parts[0], 'action' => isset($parts[1])?$parts[1]:'index'));
      }
    }
    */
    
	Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));
    Router::connect('/politica-de-privacidade', array('controller' => 'Seguranca', 'action' => 'privacidade'));
    Router::connect('/termos-de-uso', array('controller' => 'Seguranca', 'action' => 'termo'));
    Router::connect('/solicitar-doacao', array('controller' => 'Demanda', 'action' => 'cadastro'));
    Router::connect('/cadastro', array('controller' => 'Login', 'action' => 'cadastro'));
    Router::connect('/solicitacoes', array('controller' => 'Local', 'action' => 'demandas'));
    

/**
 * Load all plugin routes. See the CakePlugin documentation on
 * how to customize the loading of plugin routes.
 */
	CakePlugin::routes();

/**
 * Load the CakePHP default routes. Only remove this if you do not want to use
 * the built-in default routes.
 */
	require CAKE . 'Config' . DS . 'routes.php';
