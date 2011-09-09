<?php
//header("content-type: plain/text");
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Content-type: application/json');

$form_url = $_GET['url'];
$form_url = urldecode($form_url);
$form_get_title = $_GET['title'];

$resp_url = "";
$resp_error = "";
$resp_title = "";

// Respond with a d.ahk4.me link if the user starts a query with any of these words:
//	 command, commands, cmd
// This is different than the next check, because command's urls are written
// differently 
if(preg_match('/^(command|commands|cmd)\W(\S+)$/i', $form_url, $cmd_matches))
{
	$resp_url = 'http://d.ahk4.me/~' . $cmd_matches[2];
}

// Respond with a d.ahk4.me link if the user starts a query with any of these words:
//	 docs, documentation, help, about
else if(preg_match('/^(docs|documentation|help|about)\W(\S+)$/i', $form_url, $docs_matches))
{
	$resp_url = 'http://d.ahk4.me/' . $docs_matches[2];
}

// If it isn't an AutoHotkey url, we don't wnat to shorten it
else if (!preg_match('/^(http:\/\/|www\.){1,2}autohotkey\..*$/i', $form_url))
{
   $resp_error =  "Only AutoHotkey URLs are allowed";
}

else
{
	$resp_url = file_get_contents('http://api.bitly.com/v3/shorten?login=ahk4me&apiKey=R_4b3df1f5417d94ff356ed511fd50a153&longUrl=' 
	. urlencode($form_url) . '&format=txt');
}

if (!empty($form_get_title) && !empty($resp_url))
{
	// Taken from: http://www.go4expert.com/forums/showthread.php?t=1179
	if (@fclose(@fopen($form_url, "r")))
	{
		$file = implode('', file($form_url));
		if(preg_match("/<title>(.+)<\/title>/i",$file,$m))
			$resp_title = $m[1];
	}
}

// Remove surrounding whitespace to make the JavaScript's job easier
$resp_error = trim($resp_error);
$resp_title = trim($resp_title);
$resp_url = trim($resp_url);

// Display the output in json format
?>{
	"s_url": "<?php echo $resp_url; ?>",
    "error": "<?php echo $resp_error; ?>",
    "title": "<?php echo $resp_title; ?>"
}