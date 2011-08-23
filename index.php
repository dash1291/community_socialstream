<?php
include dirname(__FILE__).'/includes/TwitterOAuth/twitteroauth.php';
include dirname(__FILE__).'/content.php';

/*if(!isset($_REQUEST['oauth_token']))
{
	$connection=new TwitterOAuth('IPs4LAerAqSybJB9uOJ0A','pQngKocjGAp2FnTMly4GEMr8wc0Khu0ko9QhlEQSHI');
	$temporary_credentials=$connection->getRequestToken('http://localhost/socialstream/index1.php');
	$_SESSION['oauth_token']=$token=$temporary_credentials['oauth_token'];
	$_SESSION['oauth_token_secret']=$temporary_credentials['oauth_token_secret'];
	$redirect_url = $connection->getAuthorizeURL($temporary_credentials);
	header('Location: '.$redirect_url);
}
else
{
	$connection=new TwitterOAuth('IPs4LAerAqSybJB9uOJ0A','pQngKocjGAp2FnTMly4GEMr8wc0Khu0ko9QhlEQSHI',$_REQUEST['oauth_token'],$_SESSION['oauth_token_secret']);
	$token=$_SESSION['oauth_token'];
	$token_credentials=$connection->getAccessToken($_REQUEST['oauth_verifier']);
	echo $token_credentials['oauth_token'];
	echo '<br/>';
	echo $token_credentials['oauth_token_secret'];
}
*/
?>
