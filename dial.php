<?php
$number=htmlspecialchars($_GET["number"]);
function push2phone($ip, $uri, $uid, $pwd)
{
    $response = "";
    $auth = base64_encode($uid.":".$pwd);
//    $xml = "<CiscoIPPhoneExecute><ExecuteItem Priority=\"0\" URL=\"Key:Soft2\"/></CiscoIPPhoneExecute>";
    $xml = "<CiscoIPPhoneExecute><ExecuteItem Priority=\"0\" URL=\"".$uri."\"/></CiscoIPPhoneExecute>";
    $xml = "XML=".urlencode($xml);

    $post = "POST /CGI/Execute HTTP/1.0\r\n";
    $post .= "Host: $ip\r\n";
    $post .= "Authorization: Basic $auth\r\n";
    $post .= "Connection: close\r\n";
    $post .= "Content-Type: application/x-www-form-urlencoded\r\n";
    $post .= "Content-Length: ".strlen($xml)."\r\n\r\n";

    $fp = fsockopen ( $ip, 80, $errno, $errstr, 30);
    if(!$fp){ echo "$errstr ($errno)<br>\n"; }
    else
    {
        fputs($fp, $post.$xml);
        flush();
        while (!feof($fp))
        {
            $response .= fgets($fp, 128);
            flush();
        }
    }

//    return $response;
$array=array('hangup', 'redial');
if (in_array(htmlspecialchars($_GET["number"]),$array)) {
	return "Executing " . htmlspecialchars($_GET["number"]) . "\n";
	} else { 
	return "Dialing " . htmlspecialchars($_GET["number"]) . "\n";
	}
}

$ip = htmlspecialchars($_GET["ip"]);

if ($number == "redial") {$uri = "Key:Soft1";}
else if ($number == "hangup") {$uri = "Key:Soft2";} 
	else {$uri = "Dial:" . $number;}
$uid = "cisco";
$pwd = "cisco";
echo push2phone($ip, $uri, $uid, $pwd);
?>
