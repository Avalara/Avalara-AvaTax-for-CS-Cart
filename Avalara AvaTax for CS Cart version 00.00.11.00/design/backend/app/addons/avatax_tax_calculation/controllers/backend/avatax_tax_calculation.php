<?php
/***************************************************************************
*                                                                          *
*   (c) 2004 Vladimir V. Kalynyak, Alexey V. Vinokurov, Ilya M. Shalnev    *
*                                                                          *
* This  is  commercial  software,  only  users  who have purchased a valid *
* license  and  accept  to the terms of the  License Agreement can install *
* and use this program.                                                    *
*                                                                          *
****************************************************************************
* PLEASE READ THE FULL TEXT  OF THE SOFTWARE  LICENSE   AGREEMENT  IN  THE *
* "copyright.txt" FILE PROVIDED WITH THIS DISTRIBUTION PACKAGE.            *
****************************************************************************/

use Tygh\Registry;

if (!defined('BOOTSTRAP')) { die('Access denied'); }

if ($_SERVER['REQUEST_METHOD']	== 'POST') {

    if ($mode == 'connection_test') {
        $lib_path = Registry::get('config.dir.addons') . 'avatax_tax_calculation/lib/';
        $addon_path = Registry::get('config.dir.addons') . 'avatax_tax_calculation';
        $log_mode = Registry::get('addons.avatax_tax_calculation.avatax_log_mode');
        require_once($lib_path."AvaTax4PHP/avatax_test_connection.php");	
        exit;
    }
    
    if ($mode == 'setup_assistant') {
        $lib_path = Registry::get('config.dir.addons') . 'avatax_tax_calculation/lib/';
        $addon_path = Registry::get('config.dir.addons') . 'avatax_tax_calculation';
        $log_mode = Registry::get('addons.avatax_tax_calculation.avatax_log_mode');
        
        require_once($lib_path."AvaTax4PHP/avatax_accounts.php");
            
        if (isset($_REQUEST["from"]) && $_REQUEST["from"] == "AvaTaxFetchCompanies"){
            AccountValidation(); 
        }
        exit;
    }
    
    if ($mode == 'config_log') {
        $account=$_REQUEST["acc"];
        $license=$_REQUEST["license"]; 
        $serviceurl=$_REQUEST["serviceurl"];
        $companyCode=$_REQUEST["companyCode"];
        $environment=$_REQUEST["environment"];
        $client=$_REQUEST["client"];
        $isAvataxEnabled=$_REQUEST["isAvataxEnabled"];
        $isUPCOption =$_REQUEST["isUPCOption"];
        $isSaveTransaction=$_REQUEST["isSaveTransaction"];
        $isLogEnabled=$_REQUEST["isLogEnabled"];
        $isAddressValidation=$_REQUEST["isAddressValidation"];
        
        
        $addon_path = Registry::get('config.dir.addons') . 'avatax_tax_calculation';
        require_once($addon_path."/SystemLogger.php");
        // Creating the System Logger Object
        $config_log 	= 	new SystemLogger;
        $msgString='Connector Configuration -Account ID : '.$account.' License Key : '.$license.' ServiceURL : '.$serviceurl.' CompanyCode :'.$companyCode.' Environment : '.$environment.' Client : '.$client.' Is Avatax Enabled : '.$isAvataxEnabled;
        $msgString .=' Is UPC Option Enabled: '.$isUPCOption.' Is Save Transaction Enabled: '.$isSaveTransaction.' Is Log Enabled: '.$isLogEnabled.' Is Address Validation Enabled: '.$isAddressValidation;
        $timeStamp 			= 	new DateTime();						// Create Time Stamp
        
        $connectorstart=$timeStamp->format('Y-m-d\TH:i:s').".".substr((string)microtime(), 2, 3)." ".$timeStamp->format("P"); 
        $performance_metrics[] = array("CallerTimeStamp","MessageString","CallerAcctNum","DocCode","Operation","ServiceURL","LogType","LogLevel","ERPName","ERPVersion","ConnectorVersion");            
        $performance_metrics[] = array($connectorstart,$msgString,$account,"","Connector Configuration",$serviceurl,"Performance","Informational","CS-Cart",PRODUCT_VERSION,AVALARA_VERSION);
        
        //Call serviceLog function
        $returnServiceLog = $config_log->serviceLog($performance_metrics);
        //$config_log->writeConfigLog($account,$license,$serviceurl,$companyCode,$environment,$client,$isAvataxEnabled,$isUPCOption,$isSaveTransaction,$isLogEnabled,$isAddressValidation);
        exit;   
    }
     
    if ($mode == 'create_account') {
    
        $lib_path = Registry::get('config.dir.addons') . 'avatax_tax_calculation/lib/';
        $addon_path = Registry::get('config.dir.addons') . 'avatax_tax_calculation';
        $log_mode = Registry::get('addons.avatax_tax_calculation.avatax_log_mode');
        include_once($lib_path."AvaTax4PHP/avatax_create_account.php");
        exit;
    }
    
    if ($mode == 'tpa') {
    
        $lib_path = Registry::get('config.dir.addons') . 'avatax_tax_calculation/lib/';
        $addon_path = Registry::get('config.dir.addons') . 'avatax_tax_calculation';
        $log_mode = Registry::get('addons.avatax_tax_calculation.avatax_log_mode');
        include_once($lib_path."AvaTax4PHP/tpa.php");
        exit;
    }
    
    if ($mode == 'validate_account') {
    
        $lib_path = Registry::get('config.dir.addons') . 'avatax_tax_calculation/lib/';
        $addon_path = Registry::get('config.dir.addons') . 'avatax_tax_calculation';
        $log_mode = Registry::get('addons.avatax_tax_calculation.avatax_log_mode');
        include_once($lib_path."AvaTax4PHP/avatax_validate_account.php");
        exit;
    }
}