<?php
$accountId = $_REQUEST['acc'];
$username = $_REQUEST['username'];
$password=$_REQUEST['password'];

$url = 'https://sandbox.onboarding.api.avalara.com/v1/Accounts/'.$accountId;

$authentication=base64_encode($username.":".$password);

$ch = curl_init($url);
$options = array(
		CURLOPT_RETURNTRANSFER => true,         // return web page
                CURLOPT_HEADER         => false,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_HTTPHEADER     => array(
			"Authorization: Basic $authentication",
                        "Content-Type: application/json"
		),
    
		
);

curl_setopt_array($ch,$options);
$data = curl_exec($ch);
$curl_errno = curl_errno($ch);
$curl_error = curl_error($ch);
//echo $curl_errno;
//echo $curl_error;
curl_close($ch);
//echo "<p>CURL Response</p>";
print_r($data);

//print_r($data);
/*$json = json_decode($data);

if($json->Status=="Error")
{
	echo "Error in Creating Account - ".$json->Message;
}*/

?>
