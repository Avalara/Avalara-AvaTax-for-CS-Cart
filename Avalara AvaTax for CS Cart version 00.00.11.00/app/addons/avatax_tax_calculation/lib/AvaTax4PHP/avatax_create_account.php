<?php
require_once("avatax_config.php");
//print_r($_REQUEST);

$effective_date = date('Y-m-d', strtotime("-1 day"));
$end_date = date('Y-m-d', strtotime("+30 days"));
$countryCode = $_REQUEST['country'];
$stateCode = $_REQUEST['state'];
$tin=$_REQUEST['tin'];
$title=$_REQUEST['contact_title'];
$mobile=$_REQUEST['mobile'];
$fax=$_REQUEST['fax'];
if($title=="" || $title=="undefined")
{
    $title="";
}
if($mobile=="" || $mobile=="undefined")
{
    $mobile="";
}
if($fax=="" || $fax=="undefined")
{
    $fax="";
}
if($tin=="")
{
    $tin='000000000';
}

$json='{
	"ProductRatePlan":'.FREE_PLANS_JSON.',
	"ConnectorName":"CS Cart",
	"CampaignId":"'.CAMPAIGNID.'",
	"LeadSourceMostRecent":"'.LEADSOURCEMOSTRECENT.'",
	"PaymentMethodId":"",
	"EffDate":"'.$effective_date.'",
	"EndDate":"'.$end_date.'",
	"Company":{
		"BIN":"'.$_REQUEST['bin'].'",
		"CompanyAddr":{
			"City":"'.$_REQUEST['city'].'",
			"Country":"'.$countryCode.'",
			"Line1":"'.$_REQUEST['line1'].'",
			"Line2":"'.$_REQUEST['line2'].'",
			"Line3":"'.$_REQUEST['line3'].'",
			"State":"'.$stateCode.'",
			"Zip":"'.$_REQUEST['zip'].'"
		},
		"CompanyCode":"'.COMPANY_CODE.'",
		"CompanyContact":{
			"Email":"'.$_REQUEST['email'].'",
			"Fax":"'.$fax.'",
			"FirstName":"'.$_REQUEST['firstname'].'",
			"LastName":"'.$_REQUEST['lastname'].'",
			"MobileNumber":"'.$mobile.'",
			"PhoneNumber":"'.$_REQUEST['phone'].'",
			"Title":"'.$title.'"
		},
		"CompanyName":"Avalara-'.$_REQUEST['company'].'",
		"TIN":"'.$tin.'"
	}
}';

$url = 'https://onboarding.api.avalara.com/v1/Accounts';

$authentication = base64_encode(ONBOARDING_USERNAME.":".ONBOARDING_PASSWORD);

$ch = curl_init($url);
$options = array(
		CURLOPT_RETURNTRANSFER => true,         // return web page
		CURLOPT_HEADER         => false,        // don't return headers
		CURLOPT_FOLLOWLOCATION => false,         // follow redirects
	   // CURLOPT_ENCODING       => "utf-8",           // handle all encodings
		CURLOPT_AUTOREFERER    => true,         // set referer on redirect
		CURLOPT_CONNECTTIMEOUT => 20,          // timeout on connect
		CURLOPT_TIMEOUT        => 20,          // timeout on response
		CURLOPT_POST            => 1,            // i am sending post data
		CURLOPT_POSTFIELDS     => $json,    // this are my post vars
		CURLOPT_SSL_VERIFYHOST => 0,            // don't verify ssl
		CURLOPT_SSL_VERIFYPEER => false,        //
		CURLOPT_VERBOSE        => 1,
		CURLOPT_HTTPHEADER     => array(
			"Authorization: Basic $authentication",
			"Content-Type: application/json"
		)
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
?>
