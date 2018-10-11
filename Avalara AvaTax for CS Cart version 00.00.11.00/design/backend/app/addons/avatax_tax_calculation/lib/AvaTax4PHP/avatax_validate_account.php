<?php
$accountId = $_REQUEST['acc'];
$username = $_REQUEST['username'];
$password=$_REQUEST['password'];

$url = 'https://sandbox.onboarding.api.avalara.com/v1/Accounts/'.$accountId;

$authentication=base64_encode($username.":".$password);
//$authentication = base64_encode("TEST/vijay.nalawade@avalara.com:Avalara@123");

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


/*$data = '{"Message":"Account created successfully!\u000a\u000dUser created successfully!\u000a\u000dCompany created successfully!\u000a\u000dLocation created successfully!\u000a\u000dNexus created successfully!\u000a\u000d","Result":{"__type":"AccountSubscription:#AvaTaxSelfProcLib.Models","AccountId":"2000000334","CampaignId":"Test","Company":{"BIN":"","CompanyAddr":{"City":"Seattle","Country":"US","Line1":"ADDRESS LINE 2","Line2":"1000 2nd Ave","Line3":"","State":"WA","Zip":"98104-1094"},"CompanyCode":"default","CompanyContact":{"Email":"vijay@vijay1.com","Fax":"","FirstName":"My Name","LastName":"N","MobileNumber":"","PhoneNumber":"123","Title":""},"CompanyName":"My Store","TIN":"123456789"},"ConnectorName":"QuickBooks Online","EffDate":"2015-08-25","EndDate":"2015-09-24","LeadSourceMostRecent":"Test","LicenseKey":"ADECA7FB01A67F94","ProductRatePlans":["ZT - Free - Pro"],"User":{"TempPwd":"ydYD91-!","UserName":"vijay@vijay1.com"}},"Status":"Success"}';*/

//print_r($data);
/*$json = json_decode($data);

if($json->Status=="Error")
{
	echo "Error in Creating Account - ".$json->Message;
}*/

//Invalid response
//CURL Response</p>{"Message":"ConnectorName could not be found!","Result":"Validation error","Status":"Error"} - If we send OpenCart in connector name
//CURL Response</p>{"Message":"Account created successfully!\u000a\u000dUser created successfully!\u000a\u000dCompany created successfully!\u000a\u000dLocation created successfully!\u000a\u000dNexus created successfully!\u000a\u000d","Result":{"__type":"AccountSubscription:#AvaTaxSelfProcLib.Models","AccountId":"2000000185","CampaignId":"Test","Company":{"BIN":"124","CompanyAddr":{"City":"Seattle","Country":"US","Line1":"1000 2nd Ave","Line2":"","Line3":"","State":"WA","Zip":"98104-1094"},"CompanyCode":"default","CompanyContact":{"Email":"vijay@vijay.com","Fax":"","FirstName":"Vijay","LastName":"Nalawade","MobileNumber":"","PhoneNumber":"7276888868","Title":"Mr"},"CompanyName":"Vijay Store","TIN":"123456789"},"ConnectorName":"QuickBooks Online","EffDate":"2015-08-13","EndDate":"2015-09-12","LeadSourceMostRecent":"Test","LicenseKey":"E7772D30BD1DBBB5","ProductRatePlans":["ZT - Free - Pro"],"User":{"TempPwd":"viVJ83_$","UserName":"vijay@vijay.com"}},"Status":"Success"}
?>