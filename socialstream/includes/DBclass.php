<?php

// Database Access Class

class user
{
	public access_token;
	public user_id;
	public screen_name;
	function __construct($user_id,$screen_name,$access_token)
	{
		$this.user_id=$user_id;
		$this.screen_name=$screen_name;
		$this.access_token=$access_token;
	}
}
	function db_query($query)
	{
		//mysql connect---userid, password and host from config
		//mysql load database---database name from config
		//return query results
	}

	function get_user_by_id($userid)
	{
		//$query= SELECT * FROM users WHERE user_id = $userid
		//$results=db_query($query)
		//$user=new user($results['user_id'],$results['screen_name'],$results['access_token']);
		//return $user
	}
	function get_user_by_screenname($screen_name)
	{
		//$query= SELECT * FROM users WHERE screen_name = $screen_name
		//$results=db_query($query)
		//$user=new user($results['user_id'],$results['screen_name'],$results['access_token']);
		//return $user 
	}
	function get_users()
	{
		//$query= SELECT * FROM users WHERE 1 = 1
		//$results=db_query($query)
		//while($results){
		//$user=new user($results['user_id'],$results['screen_name'],$results['access_token']);
		//push into $array}
		//return $array 
	}
	function add_user($user)
	{
		//$screen_name=$user->screen_name
		//$user_id=$user->user_id
		//$access_token=$user->access_token
		//query= INSERT blah blah blah
		//db_query(query);
	}	
?>
