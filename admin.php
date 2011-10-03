<?php
include dirname(__FILE__).'/twitter-login.php';
function loadAccountSettings()
{	
	$user=get_admin_user();
	$image_url=get_picture($user->screen_name);
	?>
	<div class="well">
		<img style="float:left;margin-right:10px" src="<?php echo $image_url?>"/>
	</div>
	<?php
	//TODO::account settings markup
	//load account info from options table

	//admin account authorization
}
function loadGlobalSettings()
{

	//TODO::load global settings markup
	//load global settings from options table
	//retweet settings
	//login id and password, email
	//community info
	//account authorization
}
function loadStatuses()
{
	global $user;
	$statuses=get_statuses();
	foreach($statuses as $status)
	{
		
		$author=get_user_by_id($status['author']);
		$image_url=get_picture($author->screen_name);
		?>
		<div class="well status-item">
			<img style="float:left;margin-right:10px" class="thumbnail user-pic" src="<?php echo $image_url?>"/>
			<a href="http://twitter.com/<?php echo $author->screen_name?>">	<h3 class="user-name"><?php echo $author->screen_name?></h3></a>
			<p class="status-text"><?php echo $status['text'] ?></p>
			<button class="btn success" id="btn_publish">Publish</button>
			<button class="btn danger" id="btn_delete">Delete</button>
		</div>
		<?php
		//prepare markup
	}
	//TODO::load statuses
	//load pending statuses from DB
	//ask for moderation
}

?>
<!--<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="http://twitter.github.com/bootstrap/assets/css/bootstrap-1.0.0.min.css">
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.js"></script>
		<script type="text/javascript" src="http://twitter.github.com/bootstrap/1.3.0/bootstrap-tabs.js"></script>
		<script type="text/javascript">
			$(document).ready(function(){
				$(".tabs").tabs();
			});
		</script>
		<title>Social Stream</title>
	</head>
	<body>
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
			-->

<?php
if(isset($_GET['mode'])) $mode=$_GET['mode'];
else $mode='statuses';

switch($mode)
{
	case 'account':
		$active_state=array('account'=>'class="active"','global'=>'','statuses'=>'');
		$call_func="loadAccountSettings";
		break;
	case 'global':
		$active_state=array('account'=>'','global'=>'class="active"','statuses'=>'');
		$call_func="loadGlobalSettings";
		break;
	case 'statuses':
		$active_state=array('account'=>'','global'=>'','statuses'=>'class="active"');
		$call_func="loadStatuses";
		break;
}
?>
<h1>Admin Panel</h1>
			<ul class="tabs">
				<li <?php echo $active_state['statuses']?>><a href="admin.php?mode=statuses">Statuses</a></li>
				<li <?php echo $active_state['account']?>><a href="admin.php?mode=account">Account Settings</a></li>
				<li <?php echo $active_state['global']?>><a href="admin.php?mode=global">Global Settings</a></li>
			</ul>
<?php
call_user_func($call_func);

?>
		</div>
	</body>
</html>
