$(document).ready(initButtons);
function initButtons()
{
	$(".menu-dropdown").dropdown();
	$(".menu").dropdown();
	$("#share-button").click(makeShareRequest);
	$("#sign-out-link").click(sign_out);
	$(".publish").click(function()
	{
		publish_status($(this).parents("div.status-item").attr("id"));
	});
	$(".delete").click(function()
	{
		delete_status($(this).parents("div.status-item").attr("id"));
	});

}
function makeShareRequest()
{
	$("#spinner").show();
	text=document.getElementById("status-text").value;
	ssid=document.getElementById("session-id").value;
	url=document.getElementById("url").value;
	$.post("twitter-login.php",{action:"share",status:text,ssid:ssid},function()
		{
			document.getElementById("sharebox").innerHTML='<h2>Your status has been posted and queued for moderation.</h2><h3><a href="'+url+'">Share more? Go ahead.</a></h3>';
		});
}
function sign_out()
{

	ssid=document.getElementById("session-id").value;
	$.post("twitter-login.php",{action:"signout",ssid:ssid},function()
		{
			url=document.getElementById("url").getAttribute("value");
			window.location.href=url;
		});
}
function publish_status(id)
{
	status_container=document.getElementById(id);
	$(status_container).find(".spinner").show();
	ssid=document.getElementById("session-id").value;
	$.post("twitter-login.php",{action:"publish",id:id,ssid:ssid},function()
		{
			status_container.innerHTML='<p class=".alert-message">Status published.</p>';	
			$(status_container).fadeOut("slow");

		});
}
function delete_status(id)
{
	status_container=document.getElementById(id);
	$(status_container).find(".spinner").show();
	ssid=document.getElementById("session-id").value;
	$.post("twitter-login.php",{action:"delete",id:id,ssid:ssid},function()
		{
			status_container.innerHTML='<p class=".alert-message">Status removed.</p>';	
			$(status_container).fadeOut("slow");

		});
}
function share_spinner()
{
	$("#spinner").hide();
}
function admin_spinner()
{
	$(".spinner").hide();
}
