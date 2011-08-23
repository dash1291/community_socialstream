<?php
include dirname(__FILE__).'/config.php';
include dirname(__FILE__).'/includes/DBclass.php';
session_start();
?>
<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="http://twitter.github.com/bootstrap/assets/css/bootstrap-1.0.0.min.css">
		<title>Social Stream</title>
	</head>
	<body>
		<div>
<?php
if(isset($_SESSION['current_user']))
{
	$user_id=$_SESSION['current_user'];
	$current_user=get_user_by_id($user_id);
	echo '<script>window.location.href="'.$url.'/twitter-login.php?action=showsharebox"</script>';	
}
else 
{
	echo '<script>window.location.href="'.$url.'/twitter-login.php?action=showloginlink"</script>';	
}
?>		</div>
	</body>
</html>

