<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="http://twitter.github.com/bootstrap/assets/css/bootstrap-1.0.0.min.css">
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.js"></script>
		<script type="text/javascript" src="http://twitter.github.com/bootstrap/1.3.0/bootstrap-tabs.js"></script>
		<script type="text/javascript">
			$(document).ready(function(){
				$(".tabs").tabs();
				$("#tab-content").pills();
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
<h1>Admin Panel</h1>
			<ul class="tabs">
				<li class="active"><a href="#statuses">Statuses</a></li>
				<li><a href="#account">Account Settings</a></li>
				<li><a href="#global">Global Settings</a></li>
			</ul>
				<div class="tab-content" id="tab-content">
				  <div class="active" id="statuses">Statuses-this sucks</div>
				  <div id="account">Account Settings-bar bar foo</div>
				  <div id="global">Global Settings-foo bar</div>
				</div>
		</div>
	</body>
</html>
