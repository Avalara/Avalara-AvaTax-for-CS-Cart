<?php
function searchVariableName($string,$variable)
{
	$variablePos = stripos($string, $variable);
	$equalPos = stripos($string, "=", $variablePos);
	$endPos = stripos($string, ";", $equalPos);
    $valueLen=($endPos-$equalPos);

	if($valueLen!= 0)
    {
       $value=substr($string,$equalPos,$valueLen);
	   $replace_arr=array("=","'");
	   return trim(str_replace($replace_arr,"",$value));
    }
    else
	  return false;
	 
}

//Get config file contents in a variable
$contents=file_get_contents("config.local.php",0,NULL,1);
//print_r($contents);

//Search for connection value
$connectionString="config['db_host']";
$connection = searchVariableName($contents,$connectionString);

//Search for database value
$databaseString="config['db_name']";
$database = searchVariableName($contents,$databaseString);

//Search for mysql user value
$userString="config['db_user']";
$user = searchVariableName($contents,$userString);

//Search for mysql password value
$passwordString="config['db_password']";
$password = searchVariableName($contents,$passwordString);

//Search for table prefix value
$tableString="config['table_prefix']";
$tablePrefix = searchVariableName($contents,$tableString);
?>