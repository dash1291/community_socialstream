<?php
// Database Access Class
class user
{
	public $access_token;
	public $user_id;
	public $screen_name;
	public $token_secret;
	function __construct($user_id,$screen_name,$access_token,$token_secret)
	{
		$this->twitter_id=$user_id;
		$this->screen_name=$screen_name;
		$this->access_token=$access_token;
		$this->token_secret=$token_secret;
	}
}
	function db_query($query)
	{
		global $db_host,$db_user,$db_pass,$db_name;
		$con=mysql_connect($db_host,$db_user,$db_pass);
		$select_state=mysql_select_db($db_name,$con);
		if(!$con || !$select_state) return 0;
		else
		{
			$results=mysql_query($query);
		}
		if(!$results) return 0;
		return $results;
	}

	function get_user_by_id($userid)
	{
		$query= "SELECT * FROM users WHERE twitter_id = '$userid'";
		$results=db_query($query);
		if($results)
		{
			$row=mysql_fetch_array($results);
			if(!$row) return 0;
			$user=new user($row['twitter_id'],$row['screen_name'],$row['access_token'],$row['token_secret']);
			return $user;
		}
		else return 0;
	}

	function get_user_by_screenname($screen_name)
	{
		$query="SELECT * FROM users WHERE screen_name = '$screen_name'";
		$results=db_query($query);
		
		if($results)
		{
			$row=mysql_fetch_array($results);
			$user=new user($row['twitter_id'],$row['screen_name'],$row['access_token'],$row['token_secret']);
			return $user;
		}
		else return 0; 
	}
	function get_all_users()
	{
		$query="SELECT * FROM users";
		$results=db_query($query);
		$array=array();
		if($results)
		{
			while($row=mysql_fetch_array($results))
			{
				$user=new user($row['twitter_id'],$row['screen_name'],$row['access_token'],$row['token_secret']);
				array_push($array,$user);
			}
			return $array;
		}
		else return 0;
	}
	function add_user($twitter_id,$screen_name,$access_token,$token_secret)
	{
		$query="INSERT INTO users (screen_name, twitter_id, access_token, token_secret) VALUES ('$screen_name', '$twitter_id', '$access_token', '$token_secret')";
		db_query($query);
	}	
?>
