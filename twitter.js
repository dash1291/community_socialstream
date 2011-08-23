$(document).ready(function(){
	initButtons();
});

function initButtons()
{
	$("#share-button").click(makeShareRequest);
	$("#sign-out-link").click(sign_out);


function setButton(id) {
    $("a.nav-link").parent("li").removeClass("active");
	
  }
	$("a.nav-link").click(function(){setButton(this.id);$(this).parent("li").addClass("active");});

	$("body").bind("click", function(e) {
    $("ul.menu-dropdown").hide();
    $('a.menu').parent("li").removeClass("open").children("ul.menu-dropdown").hide();
  });

 $("a.menu").click(function(e) {
    var $target = $(this);
    var $parent = $target.parent("li");
    var $siblings = $target.siblings("ul.menu-dropdown");
    var $parentSiblings = $parent.siblings("li");
    if ($parent.hasClass("open")) {
      $parent.removeClass("open");
      $siblings.hide();
    } else {
      $parent.addClass("open");
      $siblings.show();
    }
    $parentSiblings.children("ul.menu-dropdown").hide();
    $parentSiblings.removeClass("open");
    return false;
  });
}

function makeShareRequest()
{
	text=document.getElementById("status-text").value;
	ssid=document.getElementById("session-id").value;
	$.post("twitter-login.php",{action:"share",status:text,ssid:ssid},function()
		{
			
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
