<?php

//function AddressValidation($address_data)
//{
	//require_once('AvaTax.php');
	include_once($lib_path."AvaTax4PHP/AvaTax.php");
	spl_autoload_register(__autoload);
    $return_message = "";
    $return_message_js = "";
   
	//new ATConfig($address_data["environment"], array('url'=>$address_data["service_url"], 'account'=>$address_data["account"],'license'=>$address_data["license"], 'trace'=> TRUE));
	new ATConfig($address_data["environment"], array('url'=>$address_data["service_url"], 'account'=>$address_data["account"],'license'=>$address_data["license"],'client'=>$address_data["client"], 'trace'=> TRUE));

	$client = new AddressServiceSoap($address_data["environment"]);
	
	try
	{
		$address = new Address();
		$address->setLine1($address_data["line1"]);
		$address->setLine2($address_data["line2"]);
		$address->setLine3($address_data["line3"]);
		$address->setCity($address_data["city"]);
		$address->setRegion($address_data["region"]);
		$address->setPostalCode($address_data["postalcode"]);

		$textCase = TextCase::$Mixed;
		$coordinates = 1;

		$request = new ValidateRequest($address, ($textCase ? $textCase : TextCase::$Default), $coordinates);
		
		$result = $client->Validate($request);

        $address = $result->getValidAddresses(0);

        $address = $address[0];
        /************* Logging code snippet (optional) starts here *******************/
        // System Logger starts here:

        if($log_mode==1){
            include_once $addon_path."SystemLogger.php";

            $timeStamp 			= 	new DateTime();						// Create Time Stamp
            $params				=   '[Input: ' . ']';		// Create Param List
            $u_name				=	'';							// Eventually will come from $_SESSION[] object

            // Creating the System Logger Object
            $application_log 	= 	new SystemLogger;

            $application_log->AddSystemLog($timeStamp->format('Y-m-d H:i:s'), __FUNCTION__, __CLASS__, __METHOD__, __FILE__, $u_name, $params, $client->__getLastRequest());		// Create System Log
            $application_log->WriteSystemLogToFile();			// Log info goes to log file

            $application_log->AddSystemLog($timeStamp->format('Y-m-d H:i:s'), __FUNCTION__, __CLASS__, __METHOD__, __FILE__, $u_name, $params, $client->__getLastResponse());		// Create System Log
            $application_log->WriteSystemLogToFile();			// Log info goes to log file

            //	$application_log->WriteSystemLogToDB();							// Log info goes to DB
            // 	System Logger ends here
            //	Logging code snippet (optional) ends here
        }
        else{}

		//echo "\n".'Validate ResultCode is: '. $result->getResultCode()."\n";


		if($result->getResultCode() != SeverityLevel::$Success)
		{
			$return_message .= "<b>AvaTax - Address Validation - Error Message</b><br/>";
            $return_message_js .= "<b>Error Message </b> : ";
			foreach($result->getMessages() as $msg)
			{
				//$return_message .= $msg->getName().": ".$msg->getSummary()."<br/>";
				$return_message .= $msg->getSummary()."<br/>";
                $return_message_js .= $msg->getSummary();
			}		
		}/*
		else
		{
			$return_message .= "Success";
		}   */
		//return $return_message;
	}
	catch(SoapFault $exception)
	{
		$return_message .= "Exception: ";
		if($exception)
			$return_message .= $exception->faultstring;

			$return_message .= $msg . "<br/>";
			$return_message .= $client->__getLastRequest() . "<br/>";
			$return_message .= $client->__getLastResponse() . "<br/>";
			
		//return $return_message;
	} 
//}  
//}
?>