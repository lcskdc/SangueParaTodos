<?php
session_start();
require_once('./twitteroauth/twitteroauth.php');
include('config.php');
if(isset($_GET['oauth_token']))
{
	$connection = new TwitterOAuth($CONSUMER_KEY, $CONSUMER_SECRET, $_SESSION['request_token'], $_SESSION['request_token_secret']);
	$access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);
	if($access_token)
	{
			$connection = new TwitterOAuth($CONSUMER_KEY, $CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);
			$params =array();
			$params['include_entities']='false';
			$content = $connection->get('account/verify_credentials',$params);
			if($content && isset($content->screen_name) && isset($content->name))
			{
				$id_social = $content->id;
				$nome   = $content->name;
				$email  = $content->screen_name.'@twitter.com';
				$urlImg = $content->profile_image_url;
				$_SESSION['twitter'] = array('id'=>$id_social, 'nome' => $nome, 'email' => $email, 'urlImagem' => $urlImg);
				print_r($_SESSION);
				//header('location:/Login/loginTwitter');
				exit;
			}
	}
}