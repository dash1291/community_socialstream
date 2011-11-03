<?php
include dirname(__FILE__).'/config.php';
include dirname(__FILE__).'/includes/TwitterOAuth/twitteroauth.php';
include dirname(__FILE__).'/includes/DBclass.php';
session_start();
if(isset($_SESSION['current_user']))
{
	$user=get_user_by_id($_SESSION['current_user']);
}
else
{
	if(!isset($_GET['action'])) header("Location: $url");
}
function markup_head()
{
global $user,$url;
?>
<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="http://twitter.github.com/bootstrap/assets/css/bootstrap-1.0.0.min.css">
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.js"></script>
	<script type="text/javascript" src="http://fgnass.github.com/spin.js/spin.min.js"></script>
	<script type="text/javascript" src="http://twitter.github.com/bootstrap/1.3.0/bootstrap-dropdown.js"></script>
		<script type="text/javascript" src="twitter.js"></script>
		<title>Social Stream</title>
	</head>
	<body>
	<input type="hidden" id="url" value="<?php echo $url;?>"/>
	<input type="hidden" id="session-id" value="<?php echo session_id();?>"/>
	<div class="topbar" id="topbar"> 
      <div class="container fixed"> 
        <h3><a class="logo" href="">Twitter Stream</a></h3>
        <ul> 
	<?php if(isset($user))
		{
		?>
          <li id="share-link"><a class="nav-link" href="<?php echo $url?>">Share Something</a></li>
	  	<?php if(is_admin($user))
	  		{
	  		?>
          			<li id="admin-link"><a class="nav-link" href="<?php echo $url.'/admin.php'?>">Admin Panel</a></li>
			<?php
			}
		}

		?>
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
			</ul> 														</li> 
	 </ul> 
			<?php
			}
		?>
      </div> 
    </div> <br/><br/><br/>
		<div class="container">
			<img src="logo_WP2.png"/>
<?php
}
function getLoginLink($redirect)
{
	global $api_key,$api_secret,$url;	
	$connection=new TwitterOAuth($api_key,$api_secret);
	$temporary_credentials=$connection->getRequestToken($url.'/twitter-login.php?action=callback&redirect='.$redirect);
	$_SESSION['oauth_token']=$token=$temporary_credentials['oauth_token'];
	$_SESSION['oauth_token_secret']=$temporary_credentials['oauth_token_secret'];
	$redirect_url=$connection->getAuthorizeURL($temporary_credentials);
	echo $redirect_url;
}
function showLoginLink()
{
?>		
	<div>
		<h1>Share. Spread Your Voice.</h1>
		<h1><small>You're not signed in</small></h1>
		<a class="btn large primary" href="<?php getLoginLink("") ?>">Sign in with Twitter</a>
	</div>
<?php
}
function getShareBox()
{
	global $url;
	?>
<div id="sharebox">	
	<form>
		<h1><small>Go share something...</small></h1>
		<textarea rows="5" cols="1000" id="status-text"></textarea>
		<p><input type="button" value="Share" style="margin-right:20px;" class="btn primary" id="share-button"/><img src="<?php echo $url?>/load.gif" id="spinner"/></p>
	</form>

<script>
	share_spinner();
</script>
</div>
	<?php
	//TODO:: display share box form markup
}

function shareStatus($id,$ssid)
{
	global $api_key,$api_secret,$user;
	if($ssid!=session_id()) return 0;
	if(!is_admin($user)) return 0;
	$status_text=get_status_text($id);
	$admin=get_admin_user();
	$connection=new TwitterOAuth($api_key,$api_secret,$admin->access_token,$admin->token_secret);
	$token=$_SESSION['oauth_token'];
	$result=$connection->post('statuses/update',array('status' => $status_text));
	if(!isset($result->id_str)) {echo "FAIL" ; die;}
	delete_status($id);
	echo $result->id_str;
}
function deleteStatus($id,$ssid)
{
	global $user;
	if($ssid!=session_id())return 0;
	if(!is_admin($user)) return 0;
	delete_status($id);
}
function send_email($subject,$text)
{
	global $admin_email;
	$headers="From: SocialStream<socialstream@".$_SERVER['HTTP_HOST'].">";
	if($admin_email!='') mail($admin_email,$subject,$text,$headers);
}
function createStatus($status_text,$ssid)
{
	global $api_key,$api_secret,$admin_token,$token_secret,$user;
	if($ssid!=session_id()) return 0;
	create_status_entry($status_text,$user->twitter_id);
	$subject="Moderation Required";
	$text=$twitter->screen_name." shared a status message. Please log in to either publish or delete the status";
	send_email($subject,$text);
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

function AuthCallback($redirect)
{
	global $api_key,$api_secret,$url,$user;
	$connection=new TwitterOAuth($api_key,$api_secret,$_REQUEST['oauth_token'],$_SESSION['oauth_token_secret']);
	$token=$_SESSION['oauth_token'];
	$token_credentials=$connection->getAccessToken($_REQUEST['oauth_verifier']);
	$response=$connection->get('account/verify_credentials');
	$id=$response->id;
	$screen_name=$response->screen_name;
	$auth_user=get_user_by_id($id);
	if(!$auth_user) 
	{
		$auth_user=add_user($id,$screen_name,$token_credentials['oauth_token'],$token_credentials['oauth_token_secret']);
	}
	if(strpos($redirect,"admin.php")>=0)
	{
		$admin=get_admin_user();
		if($admin==0){set_admin($auth_user,1);} 
	
		elseif(is_admin($user)) {set_admin($auth_user);}
	}
	setCurrentUser($id);
	echo '<script>window.location.href="'.$url.'/'.$redirect.'"</script>';
}
function RetweetStatus($id)
{
	global $api_key,$api_secret;
	$users=get_all_users();
	$index=0;
	while($users[$index])
	{
		$access_token=$users[$index]->oauth_token;
		$token_secret=$users[$index]->token_secret;
		$connection=new TwitterOAuth($api_key,$api_secret,$access_token,$token_secret);
		$connection->format='xml';
		$token=$_SESSION['oauth_token'];
		$result=$connection->post('statuses/retweet/'.$id,array('id' => $id));
		$index++;
		echo $result;
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
function get_picture($screen_name)
{
	return "http://api.twitter.com/1/users/profile_image?screen_name=".$screen_name."&size=bigger";
}
if(isset($_REQUEST['action']))
{
	$action=$_REQUEST['action'];
	switch($action)
	{
		case 'getloginlink':
			getLoginLink($_GET['redirect']);
			break;
		case 'auth':
			AuthRedirect();
			break;
		case 'callback':
			AuthCallback($_GET['redirect']);
			break;
		case 'share':
			createStatus($_POST['status'],$_POST['ssid']);
			break;
		case 'showloginlink':
			markup_head();
			showLoginLink();
			markup_foot();
			break;
		case 'showsharebox':
			markup_head();
			getShareBox();
			markup_foot();
			break;
		case 'signout':
			sign_out($_POST['ssid']);
			break;
		case 'retweet':
			RetweetStatus(0);
			break;
		case 'publish':
			shareStatus($_POST['id'],$_POST['ssid']);
			break;
		case 'delete':
			deleteStatus($_POST['id'],$_POST['ssid']);
			break;

	}
}
function markup_foot()
{
?>
	</body>
</html>
<?php
}
?>
