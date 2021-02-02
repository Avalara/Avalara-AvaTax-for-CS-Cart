<?php

	include_once($lib_path."AvaTax4PHP/AvaTax.php");
	
	$successMsg = "";
	$development_url = $_REQUEST["serviceurl"];
	$account = $_REQUEST["acc"];
	$license = $_REQUEST["license"];
	$environment = $_REQUEST["environment"];
	$client = $_REQUEST["client"];
	
	new ATConfig($environment, array('url'=>$development_url, 'account'=>$account,'license'=>$license,'client'=>$client, 'trace'=> TRUE));

	$client = new TaxServiceSoap($environment);

	try
	{
		$result = $client->isAuthorized("");

        /************* Logging code snippet (optional) starts here *******************/
        // System Logger starts here:
		if($result->getResultCode() != SeverityLevel::$Success)	// call failed
		{
                        $errorMsg="";
			foreach($result->getMessages() as $msg)
			{

				$errorMsg .= $msg->getSummary()."<br/>\n";
			}
                        $successMsg .= "Welcome to the AvaTax Service.<br/>";
                        $successMsg .= "Connection Test Status: <span style='color:red;'>".$result->getResultCode()."</span><br/>";
                        $successMsg .= "Message : <span style='color:red;'>".$errorMsg."</span><br/>";
       

		} 
		else // successful calll
		{
                    $dateTime = new DateTime();
                    $dateTime = strtotime($result->getExpires());
                    $dateTime = date ("Y-m-d", $dateTime);
                    $successMsg .= "Welcome to the AvaTax Service.<br/>";
                    $successMsg .= "Connection Test Status: <span style='color:green;'>".$result->getResultCode()."</span><br/>";
                    $successMsg .= "Account Expiry Date : <span style='color:green;'>".$dateTime."</span><br/>";
		}
		echo "<div style='text-align:center;padding-top:10px;'>".$successMsg."</div>";
	}
	catch(SoapFault $exception)
	{
		$msg = "Reason: ";
		if($exception)
			$msg .= $exception->faultstring;

		$successMsg .= "Welcome to the Ava Tax Service.<br/>";
		$successMsg .= "Connection Test Status: <span style='color:red;'>Failed</span><br/>";
			
		$successMsg .= $msg."<br/>";
		//$successMsg .= $client->__getLastRequest()."<br/>";
		//$successMsg .= $client->__getLastResponse()."<br/>";
		echo "<div style='text-align:center;padding-top:10px;'>".$successMsg."</div>"; 
	}	
        
                if($log_mode==1){
            include_once $addon_path."SystemLogger.php";
            // Creating the System Logger Object
            $application_log 	= 	new SystemLogger;
            $timeStamp 			= 	new DateTime();						// Create Time Stamp

            $params				=   '[Input: ' . ']';		// Create Param List
            $u_name				=	'';							// Eventually will come from $_SESSION[] object


            $application_log->AddSystemLog($timeStamp->format('Y-m-d H:i:s'), __FUNCTION__, __CLASS__, __METHOD__, __FILE__, $u_name, $params, $client->__getLastRequest());		// Create System Log
            $application_log->WriteSystemLogToFile();			// Log info goes to log file

            $application_log->AddSystemLog($timeStamp->format('Y-m-d H:i:s'), __FUNCTION__, __CLASS__, __METHOD__, __FILE__, $u_name, $params, $client->__getLastResponse());		// Create System Log
            $application_log->WriteSystemLogToFile();			// Log info goes to log file

            //	$application_log->WriteSystemLogToDB();							// Log info goes to DB
            // 	System Logger ends here
            //	Logging code snippet (optional) ends here
        }
        else{}

?>