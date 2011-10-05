<?php
include dirname(__FILE__).'/twitter-login.php';
markup_head();
if(!is_admin($user)) {
echo "<h2>Sorry, this place is for the admin only.</h2>";
markup_foot();
die;
}
function loadAccountSettings()
{	
	$user=get_admin_user();
	$image_url=get_picture($user->screen_name);
	?>
	<h3>Admin Account</h3>
	<div class="well">
		<div style="margin-left:25%" id="account-container">
		<img style="float:left;margin-right:10px" src="<?php echo $image_url?>"/>
		<a href="http://twitter.com/<?php echo $user->screen_name?>"><h3>@<?php echo $user->screen_name?></h3></a>		
		<div style="clear:right"><h3 style="float:left;margin-right:10px;"><small>Use a different account? </small></h3><a class="btn large primary" href="<?php getLoginLink("admin.php?mode=account")?>">Sign in with Twitter</a>
		</div>
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
	global $user,$url;
	$statuses=get_statuses();
	foreach($statuses as $status)
	{
		
		$author=get_user_by_id($status['author']);
		$image_url=get_picture($author->screen_name);
		?>
		<div class="well status-item" id="<?php echo $status['ID']?>">
			<img style="float:left;margin-right:10px" class="thumbnail user-pic" src="<?php echo $image_url?>"/>
			<a href="http://twitter.com/<?php echo $author->screen_name?>"><h3 class="user-name">@<?php echo $author->screen_name?></h3></a>
			<p class="status-text"><?php echo $status['text'] ?></p>
			<br/>
			<p style="clear:both"><button class="btn success publish">Publish</button>
			<button class="btn danger delete" style="margin-right:20px;">Delete</button><img class="spinner" src="<?php echo $url?>/load.gif"/></p>
		</div>
		<?php
		//prepare markup
	}
	?>

		<script>
			admin_spinner();
		</script>
	<?php
	//TODO::load statuses
	//load pending statuses from DB
	//ask for moderation
}

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
