/* Author: Frankie Bagnardi
 * Handle server requests for shoretning URLs
 * Requires jQuery
*/

var state = "default text";
var last_resp = null;
var default_text = "Put your URL here";

$(document).ready(function(e) {
    $(".moreInfo").hide();
	$("#url-input").val(default_text);
});

$("#infoButton").click(function(e) {
	$(".moreInfo").slideToggle(400);
});

$("#url-input").keypress(function(e) {
    if (state === "default text")
	{
		state = "normal"
		$("#url-input").val("");
		$("#url-input").css("color", "#010F00");
	}	
});

$("#url-input").keyup(function(e) {
	if (e.which == 13) // Enter
	{
	    handleInput(e);
	}
	
	else if (state !== "normal")
	{
		$("#url-input").css("color", "#010F00");
		state = "normal";
	}
});


$("#shorten").click(function(e) {
    handleInput(e);
});

$("#bbcode").click(function(e) {
	var currentVal = $("#url-input").val();
	
	// If it's already bbcode, revert it
	/*
	var bbStart = currentVal.indexOf("[url=");
	var bbLinkEnd = currentVal.indexOf("]");
	*/
	if (currentVal.indexOf("[url=") === 0)
	{
		$("#url-input").val(last_resp.s_url);
	}	
	
    else if ( currentVal.indexOf("ahk4.me") == -1 )
	{
		handleInput(e);
	}
	else if (last_resp.error == "")
	{
		$("#url-input").val("[url=" + currentVal + "]" + last_resp.title + "[/url]");
	}
	else 
	{
		$("#url-input").val("Please type a valid Url first");
	}
});


function handleInput(e) {
	var inputElement = $("#url-input");
	var url = inputElement.val();
	if (url.length < 8)
	{
		inputElement.val("StrLen(Your_URL) < Long enough");
		inputElement.css("color", "#EA1515");
		state = "error";
		return;
	}
	inputElement.css("color", "#7AF400");
	inputElement.val("Loading...");
	$.getJSON("h/",
	{ 
		url: url,
		title: "yes please"
	},
	function(data){
		/*if (data.indexOf(" ") !== -1)
		{
			inputElement.css("color", "#EA1515");
			state = "error";
		}
		else
		{
			inputElement.css("color", "#3C0");
			state = "we did it!";
		}*/
		
		if (data.s_url)
		{
			inputElement.val(data.s_url);
			inputElement.css("color", "#3C0");
			state = "we did it!";
		}
		else if (data.error)
		{
			inputElement.css("color", "#EA1515");
			state = "error";
			inputElement.val(data.error);
			$("#url-input").oneTime(3000, "hide-error", function(){
				$("#url-input").val(default_text);
				$("#url-input").css("color", "#010F00");
				state = "default text";
			});
			
		}
		last_resp = data;
	});
}






