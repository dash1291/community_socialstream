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
		<div class="container">
<?php
function markup_foot()
{?>
	</body>
</html>
<?php
}
if(isset($_POST['db_name']))
{
$db_pass=$_POST['db_pass'];
$db_user=$_POST['db_user'];
$db_host=$_POST['db_host'];
$db_name=$_POST['db_name'];
$api_key=$_POST['api_key'];
$api_secret=$_POST['api_secret'];
$url=$_POST['url'];
$conf_file=dirname(__FILE__).'/config.php';
//config file writeup

$config=@fopen($conf_file,"w");
$data='<?php $db_pass="'.$db_pass.'";$db_user="'.$db_user.'";$db_host="'.$db_host.'";$db_name="'.$db_name.'";$api_key="'.$api_key.'";$api_secret="'.$api_secret.'";$url="'.$url.'";?>';
if(!$config)
{
	echo '<h3>Cannot write config file</h3>';
	echo '<p><small>Please copy the following text into config.php</small></p>';
	echo $data;
}
else{
	fwrite($config,$data);
	fclose($config);
}
//database setup
$db=@mysql_connect($db_host,$db_user,$db_pass);
if(!$db) 
{
	echo '<h3>Cannot connect to database.</h3>';
	echo '<p><small>Check your settings in config.php and also if your mysql server is working.</small></p>';
	markup_foot();
	die;
}
$db_select=@mysql_select_db($db_name,$db);
if(!$db_select)
{
	echo '<h3>Cannot connect to database.</h3>';
	echo '<p><small>No database exists</small></p>';
	markup_foot();
	die;
}
$query="CREATE TABLE options (option_name varchar(25),value varchar(100))";
mysql_query($query);
$query="CREATE TABLE users (ID bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,twitter_id bigint(20),screen_name varchar(50),access_token varchar(100),token_secret varchar(100))";
mysql_query($query);
$query="CREATE TABLE statuses (ID bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,text varchar(140),author bigint(20),time timestamp DEFAULT CURRENT_TIMESTAMP)";
mysql_query($query);
mysql_close($db);
echo '<h3>So, its done.</h3>';
echo '<h3>Now you need to authorize your Twitter account.</h3>';
$login_link=file_get_contents($url."/twitter-login.php?action=getloginlink&redirect=admin.php?mode=account");
echo '<a class="btn normal" href="'.$login_link.'">Sign in with Twitter</a>';
markup_foot();
die;
}
else
{
?>
	<form action="setup.php" method="POST">
		<div class="input">	
			<label for="api_key">Twitter API Key</label>
			<input style="margin-left:20px" type="text" size="20" name="api_key"/>
		</div>
		<div class="input">
			<label for="api_secret">Twitter API Secret</label>
			<input style="margin-left:20px" type="text" size="20" name="api_secret"/>
		</div>
		<div class="input">
			<label for="db_host">Database hostname</label>
			<input style="margin-left:20px" type="text" size="20" name="db_host"/>
		</div>
		<div class="input">
			<label for="db_name">Database name</label>
			<input style="margin-left:20px" type="text" size="20" name="db_name"/>
		</div>
		<div class="input">
			<label for="db_user">Database username</label>
			<input style="margin-left:20px" type="text" size="20" name="db_user"/>
		</div>
		<div class="input">
			<label for="db_pass">Database password</label>
			<input style="margin-left:20px" type="text" size="20" name="db_pass"/>
		</div>
		<div class="input">
			<label for="url">Application URL</label>
			<input style="margin-left:20px" type="text" size="20" name="url"/>
		</div>
		<div class="input">
			<input style="margin-left:150px" type="submit" class="btn primary" value="Done"/>
		</div>
	</form>
	<?php
	markup_foot();
}
?>

