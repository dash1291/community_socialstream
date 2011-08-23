<?php
include dirname(__FILE__).'/config.php';
include dirname(__FILE__).'/includes/TwitterOAuth/twitteroauth.php';
include dirname(__FILE__).'/includes/DBclass.php';
session_start();
if(isset($_SESSION['current_user']))
{
	$user=get_user_by_id($_SESSION['current_user']);
}
?>
<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="http://twitter.github.com/bootstrap/assets/css/bootstrap-1.0.0.min.css">
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.js"></script>
		<script type="text/javascript" src="twitter.js"></script>
		<title>Social Stream</title>
	</head>
	<body>
	<input type="hidden" id="url" value="<?php echo $url;?>"/>
	<div class="topbar"> 
      <div class="container fixed"> 
        <h3><a class="logo" href="">Twitter Stream</a></h3>
        <ul> 
          <li id="view-link" class="active"><a class="nav-link" href="#">View Stream</a></li> 
          <li id="share-link"><a class="nav-link" href="#">Share Something</a></li>
        </ul> 
        <?php 
		if(isset($user)) 
			{
				?> 
        <ul class="nav secondary-nav"> 
          <li class="menu"> 
            
			<a href="#" class="menu"><?php echo $user->screen_name;?></a> 
	
            <ul class="menu-dropdown"> 
              <li><a id="sign-out-link" href="#">Sign Out</a></li> 
              
            </ul> 
          </li> 
        </ul> 
				<?php
			}
		?>
      </div> 
    </div> <br/><br/><br/>
		<div class="container">
			<img src="logo_WP2.png"/>
<?php
function getLoginLink()
{
	global $api_key,$api_secret,$url;
	
	$connection=new TwitterOAuth($api_key,$api_secret);
	$temporary_credentials=$connection->getRequestToken($url.'/twitter-login.php?action=callback');
	$_SESSION['oauth_token']=$token=$temporary_credentials['oauth_token'];
	$_SESSION['oauth_token_secret']=$temporary_credentials['oauth_token_secret'];
	$redirect_url=$connection->getAuthorizeURL($temporary_credentials);
	?>
	<div>
		<h1>Share. Spread Your Voice.</h1>
		<h1><small>You're not signed in</small></h1>
		<a class="btn large primary" href="<?php echo $redirect_url ?>">Sign in with Twitter</a>
	</div>
	<?php
}

function getShareBox()
{
	?>
	
	<form>
		<h1><small>Go share something...</small></h1>
		<textarea rows="5" cols="1000" id="status-text"></textarea>
		<p><input type="button" value="Share" class="btn primary" id="share-button"/></p>
		<input type="hidden" id="session-id" value="<?php echo session_id();?>"/>
	</form>
	<?php
	//TODO:: display share box form markup
}

function shareStatus($status_text,$ssid)
{
	global $api_key,$api_secret,$admin_token,$token_secret;
	if($ssid!=session_id()) return 0;
	$connection=new TwitterOAuth($api_key,$api_secret,$admin_token,$token_secret);
	$connection->format='xml';
	$token=$_SESSION['oauth_token'];
	$result=$connection->post('statuses/update',array('status' => $status_text));
	echo $result;

}

function AuthRedirect()
{
	global $api_key,$api_secret;
	$connection=new TwitterOAuth($api_key,$api_secret);
	$temporary_credentials=$connection->getRequestToken($url.'/login.php');
	$_SESSION['oauth_token']=$token=$temporary_credentials['oauth_token'];
	$_SESSION['oauth_token_secret']=$temporary_credentials['oauth_token_secret'];
	$redirect_url = $connection->getAuthorizeURL($temporary_credentials);
	header('Location: '.$redirect_url);
}

function AuthCallback()
{
	global $api_key,$api_secret,$url;
	$connection=new TwitterOAuth($api_key,$api_secret,$_REQUEST['oauth_token'],$_SESSION['oauth_token_secret']);
	$token=$_SESSION['oauth_token'];
	$token_credentials=$connection->getAccessToken($_REQUEST['oauth_verifier']);
	$response=$connection->get('account/verify_credentials');
	$id=$response->id;
	$screen_name=$response->screen_name;
	$user=get_user_by_id($id);
	if(!$user) 
	{
		add_user($id,$screen_name,$token_credentials['oauth_token'],$token_credentials['oauth_token_secret']);
		setCurrentUser($id);
		echo '<script>window.location.href="'.$url.'/twitter-login.php?action=showsharebox"</script>';
	}
	else 
	{
		setCurrentUser($id);
		echo '<script>window.location.href="'.$url.'/twitter-login.php?action=showsharebox"</script>';
	}	
}

function setCurrentUser($id)
{
	$_SESSION['current_user']=$id;
}

function sign_out($ssid)
{
	if($ssid!=session_id()) return 0;
	session_destroy();

}
	
$action=$_REQUEST['action'];
switch($action)
{
	case 'auth':
		AuthRedirect();
		break;
	case 'callback':
		AuthCallback();
		break;
	case 'share':
		shareStatus($_POST['status'],$_POST['ssid']);
		break;
	case 'showloginlink':
		getLoginLink();
		break;
	case 'showsharebox':
		getShareBox();
		break;
	case 'signout':
		sign_out($_POST['ssid']);
		break;
}
?>
	</div>
	</body>
</html>

