<?php
if ( !isset($_GET['ip']))
   $ip = getenv("REMOTE_ADDR");
else
   $ip = $_GET['ip'];

if ( !valid_ip($ip))
   echo "ERROR: Invalid IP";
else
{
   $result = file_get_contents('http://api.ipinfodb.com/v3/ip-city/?key=YOURAPIKEY&ip=' . $ip);
   echo $result;
}

function valid_ip($ip) {
    return preg_match("/^([1-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])" .
            "(\.([0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])){3}$/", $ip);
} 
?>