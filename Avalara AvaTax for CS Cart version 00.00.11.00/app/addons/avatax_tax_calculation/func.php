<?php
/****************************************************************************
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
 
 /***************************************************************************
 *                                                                          *
 *   This source file was cleaned-up to meet the requirements for release   *
 *   done by Avalara - on 02/20/2015                                        *
 *   File Version       :                                                   *
 *   Last Updated On    :    03/02/2021                                     *
 ***************************************************************************/
 

use Tygh\Registry;
use Tygh\Session;

if (!defined('BOOTSTRAP')) {
    die('Access denied');
}
define('AVALARA_VERSION', 'CS Cart.devcombuild');
define('CLIENT_NAME',AVALARA_VERSION);

/** [GENERAL FUNCTIONS] **/

#{

 /***************************************************************************
 *                                                                          *
 *   Function Header                                                        *
 *                                                                          *
 *   File Version       :                                                   *
 *   Last Updated On    :   03/02/2021                                      *
 *   Description        :   This function allow user to test the connection *
 *                          between CS Cart and Avalara Admin Console. This *
 *                          action is performed after installing the AvaTax *
 *                          Addon from CS Cart Admin Console                *
 ****************************************************************************/
 
function fn_avatax_tax_calculation_testconnection()
{
    $curPageURL = explode("?", curPageURL());
    $text = '
    <script type="text/javascript">
        var AVATAX_CLIENT = "'.CLIENT_NAME.'";
    </script>
    <div class="control-group setting-wide avatax_tax_calculation">
        <label for="addon_option_avatax_tax_calculation_avatax_test_connection" class="control-label ">Make a test call to the AvaTax Service:</label>
        <div class="controls"><a href="javascript:;" id="AvaTaxTestConnection" ><img src="design/backend/media/images/Avatax_test_connection.png" ></a></div>
    </div>
    <style>
    .ui-dailog-inner {
        z-index: 9999 !important;
        overflow:"scroll";
    }
    </style>
    <div id="AvaTaxTestConnectionDialog" title="AvaTax Test Connection" style="z-index: 9999 !important;"></div>
    ';

    return $text;
}

function fn_avatax_tax_account_creation_info() {
    $text="";
       $text = '<div class="control-group setting-wide avatax_tax_calculation">
        <div><strong>Please click on Sign In tab, once you have successfully created an Avalara AvaTax account.</strong></div>
        </div>';
    return $text;
}     

function fn_avatax_tax_gen_info() {
    $text="";
       $text = '<div class="control-group setting-wide avatax_tax_calculation" id="signinInfo">
            <div><strong>If you already have Avalara Account<br/></strong>
            </div>
        </div>';
    return $text;

}     

function fn_avatax_tax_calculation_TPA_link()
{
    $text = '<div class="control-group setting-wide avatax_tax_calculation">
        <label for="addon_option_avatax_tax_calculation_avatax_test_connection" class="control-label ">AvaTax Tax Profile Assistant:</label>
        <div class="controls" style="margin-top:5px;"><a href="#" id="AvaTaxTPALink">Click here for AvaTax Tax Profile Assistant</a><br/><strong>Note:</strong> Nexus recommendations are based on the analysis of either your last 1000 transactions or your last 1 year\'s transactional data.</div>
        </div>';

    $text = $text .'<div id="AvaTaxTpaDialog" title="Avalara AvaTax Credentials"  style="z-index: 9999 !important;"></div>';

    $text = $text.'
        <script type="text/javascript">
            document.getElementById("AvaTaxTPALink").addEventListener("click", function() {
                
                if (document.querySelector("[id^=\'addon_option_avatax_tax_calculation_avatax_account_number\']").value == "") {
                    alert("Please enter AvaTax Account Number!");
                    document.querySelector("[id^=\'addon_option_avatax_tax_calculation_avatax_account_number\']").focus();
                } else if (document.querySelector("[id^=\'addon_option_avatax_tax_calculation_avatax_license_key\']").value == "") {
                    alert("Please enter AvaTax License Key");
                    document.querySelector("[id^=\'addon_option_avatax_tax_calculation_avatax_license_key\']").focus();
                } else if (document.querySelector("[id^=\'addon_option_avatax_tax_calculation_avatax_service_url\']").value == "") {
                    alert("Please enter AvaTax Service URL");
                    document.querySelector("[id^=\'addon_option_avatax_tax_calculation_avatax_service_url\']").focus();
                } else {
                    var accountVal = document.querySelector("[id^=\'addon_option_avatax_tax_calculation_avatax_account_number\']").value;
                    var licenseVal = document.querySelector("[id^=\'addon_option_avatax_tax_calculation_avatax_license_key\']").value;
                    var serviceURLVal = document.querySelector("[id^=\'addon_option_avatax_tax_calculation_avatax_service_url\']").value;
                    var companyCode = document.querySelector("[id^=\'addon_option_avatax_tax_calculation_avatax_company_code\']").value;
                    var environment = serviceURLVal.indexOf("development") ? "Development" : "Production";
                    
                    $("#AvaTaxTpaDialog").html("<div style=\'align:left; float:left;margin:5px;padding:2px;\'> AvaTax Username : <input type=text name=avataxUsername id=addon_option_avatax_tax_calculation_avatax_user_name /><br/>AvaTax Password : <input type=password name=avataxPassword id=addon_option_avatax_tax_calculation_avatax_user_password /><br/><div style=\'align:left; float:right;margin:5px;padding:2px;\'><a class=btn id=avataxcreateNexus href=#>Submit</a></div></div>").dialog({
                        resizable: true,
                        modal: true,
                        width: "500",
                        open: function( event, ui ) {
                            if (!$("#AvaTaxTpaDialog").parent().hasClass("ui-dailog-inner")) {
                                $("#AvaTaxTpaDialog").parent().addClass("ui-dailog-inner");
                            }
                        }
                    });
                    
                    $("#avataxcreateNexus").click(function() {
                            
                        var consoleUserName = document.getElementById("addon_option_avatax_tax_calculation_avatax_user_name").value;
                        var consolePassword = document.getElementById("addon_option_avatax_tax_calculation_avatax_user_password").value;
                        if (consoleUserName == "") {
                            alert("AvaTax Username cannot be empty!!!!!");
                            return false;
                        } else if (consolePassword == "") {
                            alert("AvaTax password cannot be empty!!!!!");
                            return false;
                        } else {
                            if (environment == "Development") {
                            consoleUserName = "Test/"+consoleUserName;
                        }

                        $("#AvaTaxTpaDialog").html(\'<div style="text-align:center;padding-top:10px;"><img src="design/backend/media/images/loading2.gif" border="0" alt="Work In Progress..." ><br/>Work In Progress...</div>\');

                            $.ajax({
                                url:"?dispatch=avatax_tax_calculation.validate_account&security_hash="+Tygh.security_hash+"&acc="+accountVal+"&username="+consoleUserName+"&password="+consolePassword,
                                success: function(result1) {
                                    var jsonValacc=JSON.parse(result1);
                                    if (jsonValacc.Status=="Success") {
                                        $.ajax({
                                            url:"?dispatch=avatax_tax_calculation.tpa&security_hash="+Tygh.security_hash+"&acc="+accountVal+"&license="+licenseVal+"&serviceurl="+serviceURLVal+"&companyCode="+companyCode+"&username="+consoleUserName+"&password="+consolePassword+"&erp=cscart&environment="+environment,
                                            success: function(result) {
                                                var json=JSON.parse(result);
                                                var msg="";
                                                window.open(
                                                    json,
                                                    "_blank" // <- This is what makes it open in a new window.
                                                );
                                                $("#AvaTaxTpaDialog").dialog( "close" );
                                            },
                                            async:false,
                                            type : "POST"
                                        });
                                    } else {
                                        alert("Invalid AvaTax Username or Password!!!");
                                        $("#AvaTaxTpaDialog").dialog( "close" );
                                    }
                                },
                                async: false,
                                type : "POST"
                            });
                        }
                    });
                }    
            });
        </script>';

    return $text;
}
               
function fn_avatax_tax_account_provision() {
    $text="";
    $curPageURL = explode("?", curPageURL());
    $text = '
            <script type="text/javascript" src="js/addons/avatax_tax_calculation/jquery.AvaWidget.js"></script>
            <script>
            //$(".ui-dialog ui-widget ui-widget-content ui-corner-all ui-front").css("width",1500);
            $("#avalaraDiv").AvaWidget({
                InheritCss:false,
                CssLinks: GetCssURLs(),
                AvalaraOnboardingObject: new AvalaraOnboarding("Test Connector","","000000000","","", "","","","","","","", "",""),                             
                onAvaTaxCompanyCreated: function (onboardingData) {
                    alert(JSON.stringify(onboardingData)); 
                },
                FinishButton: { Visible: false, Caption: "Continue", onFinishClicked: function (onboardingData) {
                    console.log(JSON.stringify(onboardingData));        
                }}                
            });            
            </script>
            <div id="avalaraDiv" style="height:500px;width:1000px;overflow:hidden;"></div>
    ';
    return $text;
}

function fn_avatax_tax_setup_assistant() {
    $text="";
    $curPageURL = explode("?", curPageURL());
    $text = '
        <script type="text/javascript">
        $("#avatax_setup_assistant").click(function() {validateCompany()});
        </script>
        <div class="control-group setting-wide avatax_tax_calculation">
            <div class="controls"><a class="btn btn-primary" href="#"  name="avatax_setup_assistant" id="avatax_setup_assistant">Get Company Code</a></div>
        </div>
        <style>
        .ui-dailog-inner {
            z-index: 9999 !important;
        }
        </style>
        <div id="company_code_list" title="AvaTax Company Codes" style="z-index: 9999 !important;"></div>
    ';
    return $text;    
}


 /***************************************************************************
  *                                                                          *
  *   Function Header                                                        *
  *                                                                          *
  *   File Version       :                                                   *
  *   Last Updated On    :   03/02/2021                                      *
  *   Description        :   This function displays the link to Avalara      *
  *                          Production Admin Console.                       *
  *                            To be accessed from  Avatax ->Setting         *
  ****************************************************************************/

function fn_avatax_tax_calculation_admin_console_link()
{
    $text = '<div class="control-group setting-wide avatax_tax_calculation">
        <label for="addon_option_avatax_tax_calculation_avatax_test_connection" class="control-label ">AvaTax Dashboard link:</label>
             <div class="controls"><a href="https://home.avalara.com/" id="AvaTax Production Dashboard" target="_blank">Click here for AvaTax Dashboard</a></div>
        </div>';
    return $text;
}

/***************************************************************************
 *                                                                          *
 *   Function Header                                                        *
 *                                                                          *
 *   File Version       :                                                   *
 *   Last Updated On    :   03/02/2021                                      *
 *   Description        :   This function displays the link to Avalara      *
 *                          Nexus help.                                     *
 *                            To be accessed from  Avatax ->Setting         *
 ****************************************************************************/
function fn_avatax_tax_calculation_admin_nexus_link()
{
    $text = '<div class="control-group setting-wide avatax_tax_calculation">
             <div class="controls"><a href="http://help.avalara.com/000_AvaTax_Calc/000AvaTaxCalc_User_Guide/020_Add_Nexus_Jurisdictions/What_Is_Nexus%3F" id="AvaTax_nexus_help" target="_blank">Learn more about Nexus</a></div>
        </div>';
    return $text;
}

 /***************************************************************************
  *                                                                          *
  *   Function Header                                                        *
  *                                                                          *
  *   File Version       :                                                   *
  *   Last Updated On    :   03/02/2021                                      *
  *   Description        :   This function returns the Page URL via          *
  *                          curl library.                                   *
  *                                                                          *
  ****************************************************************************/
function curPageURL()
{
    $pageURL = 'http';
    if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {
        $pageURL .= "s";
    }
    $pageURL .= "://";
    if ($_SERVER["SERVER_PORT"] != "80") {
        $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
    } else {
        $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
    }
    return $pageURL;
}

 /***************************************************************************
 *                                                                          *
 *   Function Header                                                        *
 *                                                                          *
 *   File Version       :                                                   *
 *   Last Updated On    :   03/02/2021                                      *
 *   Description        :   This function returns the original price of     * 
 *                            product by product ID                         *
 *                                                                          *
 ****************************************************************************/
function fn_product_real_price($product_id)
{
    return db_get_field("SELECT price FROM ?:product_prices WHERE product_id = ?i AND lower_limit = 1", $product_id);
}

 /****************************************************************************
  *                                                                          *
  *   Function Header                                                        *
  *                                                                          *
  *   File Version       :                                                   *
  *   Last Updated On    :    03/02/2021                                     *
  *   Description        :   This function updates the AvaTax fields in order* 
  *                                                                          *
  ****************************************************************************/
 
function fn_avatax_tax_calculation_update_order_with_avatax_fields($avatax_document_id, $avatax_transaction_id, $avatax_document_code, $avatax_error_message, $order_id)
{
    db_query("UPDATE `?:orders` SET avatax_paytax_document_id = '" . (int)$avatax_document_id . "', avatax_paytax_transaction_id = '" . (int)$avatax_transaction_id . "', avatax_paytax_document_code = '" . (int)$avatax_document_code . "', avatax_paytax_error_message = '" . $avatax_error_message . "' WHERE order_id = '" . (int)$order_id . "'");
}


 /****************************************************************************
  *                                                                          *
  *   Function Header                                                        *
  *                                                                          *
  *   File Version       :                                                   *
  *   Last Updated On    :    02/22/2015                                     *
  *   Description        :   This function returns Doc Code from Return Order* 
  *                                                                          *
  ****************************************************************************/
 
function fn_avatax_tax_calculation_get_return_order_doc_code($order_id)
{

    return db_get_field("SELECT count(*) as total FROM ?:rma_returns WHERE order_id = ?i", $order_id);
}

 /***************************************************************************
 *                                                                          *
 *   Function Header                                                        *
 *                                                                          *
 *   File Version       :                                                   *
 *   Last Updated On    :   02/03/2021                                      *
 *   Description        :   This function returns the ID of Return Reason   * 
 *                                                                          *
 ****************************************************************************/
 
function fn_return_reason($return_reason_id)
{
    return db_get_field("SELECT property FROM ?:rma_property_descriptions WHERE property_id = ?i", $return_reason_id);
}


 /***************************************************************************
 *                                                                          *
 *   Function Header                                                        *
 *                                                                          *
 *   File Version       :                                                   *
 *   Last Updated On    :   02/03/2021                                      *
 *   Description        :   This function returns the ID of Return Reason   * 
 *                                                                          *
 ****************************************************************************/
 
function fn_edit_return_with_avatax_doccode($avatax_return_document_code, $return_id)
{

    //Add one new field to CS-Cart "return" table
    $avatax_fields_exists = db_query("SHOW columns from `?:rma_returns` where field='avatax_return_document_code'");

    if ($avatax_fields_exists->num_rows == 0) {
        db_query("ALTER TABLE ?:rma_returns ADD `avatax_return_document_code` VARCHAR( 10 ) NOT NULL");
    }

    db_query("UPDATE `?:rma_returns` SET avatax_return_document_code = '" . $avatax_return_document_code . "' WHERE return_id = '" . (int)$return_id . "'");

}

 /***************************************************************************
 *                                                                          *
 *   Function Header                                                        *
 *                                                                          *
 *   File Version       :                                                   *
 *   Last Updated On    :   02/03/2021                                      *
 *   Description        :   This function fetches the tax exemption details *
 *                          from the database table                         *
 ****************************************************************************/

function fn_get_tax_exemption_details($user_id)
{
    $tax_exemption_details = db_get_row("SELECT tax_exempt, tax_exempt_number, tax_entity_usecode FROM ?:users WHERE user_id = ?i", $user_id);
        
    if ($tax_exemption_details["tax_exempt"] == 'Y') {
        if ($tax_exemption_details['tax_exempt_number'] == "") {
            $tax_exemption_details['tax_exempt_number'] = "Exempt";
        }
        $tax_exemption_details['tax_entity_usecode'] = "";
    }

    if ($tax_exemption_details["tax_exempt"] == 'N') {
        if ($tax_exemption_details['tax_entity_usecode'] == "-") {
            $tax_exemption_details['tax_entity_usecode'] = "";
        }
        $tax_exemption_details['tax_exempt_number'] = "";
       // $tax_exemption_details['tax_entity_usecode'] = "";
    }

    return $tax_exemption_details;
}


 /***************************************************************************
 *                                                                          *
 *   Function Header                                                        *
 *                                                                          *
 *   File Version       :                                                   *
 *   Last Updated On    :   02/03/2021                                      *
 *   Description        :   This function fetches the timestamp when the    *
 *                          tax was computed for given Order ID             *
 ****************************************************************************/
 
function fn_avatax_tax_calculation_get_order_timestamp($order_id)
{
    return db_get_field("SELECT timestamp FROM ?:orders WHERE order_id = ?i", $order_id);
}

#}
/** [/GENERAL FUNCTIONS] **/

/** [ORDER STATUS CHANGES FUNCTIONS] **/
#{ Region STARTS

/****************************************************************************
 *                                                                          *
 *   Function Header                                                        *
 *                                                                          *
 *   File Version       :                                                   *
 *   Last Updated On    :   02/03/2021                                      *
 *   Description        :   This function fetches document Status           *
 ****************************************************************************/
function fn_get_avatax_document_status()
{
    $order_statuses = fn_get_statuses();

    $avatax_document_status = array();
    foreach ($order_statuses as $key => $value) {
        if ($value["type"] == 'O') {
            $avatax_document_status[$key] = $value["avatax_document_status"];
        }
    }
    return $avatax_document_status;
}


 /***************************************************************************
 *                                                                          *
 *   Function Header                                                        *
 *                                                                          *
 *   File Version       :                                                   *
 *   Last Updated On    :   02/03/2021                                     *
 *   Description        :   This function fetches document Status            *
 ****************************************************************************/
function fn_get_avatax_return_status()
{
    $order_statuses = fn_get_statuses(STATUSES_RETURN, array(), true);

    $avatax_document_status = array();
    foreach ($order_statuses as $key => $value) {
        if ($value["type"] == 'R') {
            $avatax_document_status[$key] = $value["avatax_document_status"];
        }
    }

    return $avatax_document_status;
}

 /***************************************************************************
 *                                                                          *
 *   Function Header                                                        *
 *                                                                          *
 *   File Version       :                                                   *
 *   Last Updated On    :   02/03/2021                                      *
 *   Description        :   This function sets Document State = void        *
 ****************************************************************************/
function fn_document_state_voided($order_info, $status_from)
{
    $AvaTaxDocumentStatus = fn_get_avatax_document_status();

    if (trim($AvaTaxDocumentStatus[$status_from]) == "Committed") {
        //1. Call CancelTax with CancelCode = DocVoided
        $DocDeletedReturn = fn_avatax_tax_calculation_avatax_canceltax($order_info["avatax_paytax_document_code"], "DocVoided");
    } else if (trim($AvaTaxDocumentStatus[$status_from]) == "Voided") {
        //1. Call CancelTax with CancelCode = DocDeleted
        $DocDeletedReturn = fn_avatax_tax_calculation_avatax_canceltax($order_info["avatax_paytax_document_code"], "DocDeleted");

        if ($DocDeletedReturn == 'Success') {
            //2. Call GetTax with Commit = False

            $DocCommittedReturn = fn_avatax_tax_calculation_avatax_gettax_commit($order_info, 0);

            //3. Call CancelTax with CancelCode = DocVoided
            $DocDeletedReturn = fn_avatax_tax_calculation_avatax_canceltax($order_info["avatax_paytax_document_code"], "DocVoided");
        }
    } else if (trim($AvaTaxDocumentStatus[$status_from]) == "Uncommitted") {
        //1. Call CancelTax with CancelCode = DocVoided
        $DocDeletedReturn = fn_avatax_tax_calculation_avatax_canceltax($order_info["avatax_paytax_document_code"], "DocVoided");
    }
}

/****************************************************************************
 *                                                                          *
 *   Function Header                                                        *
 *                                                                          *
 *   File Version       :                                                   *
 *   Last Updated On    :   02/03/2021                                      *
 *   Description        :   This function changes                           *
 *                          Document State = Uncommitted                    *
 ****************************************************************************/
 
function fn_document_state_uncommitted($order_info, $status_from)
{
    $AvaTaxDocumentStatus = fn_get_avatax_document_status();

    if (trim($AvaTaxDocumentStatus[$status_from]) == "Committed") {
        //1. Call CancelTax with CancelCode = Voided
        $DocVoidedReturn = fn_avatax_tax_calculation_avatax_canceltax($order_info["avatax_paytax_document_code"], "DocVoided");
        //2. Call CancelTax with CancelCode = DocDeleted
        if ($DocVoidedReturn == 'Success') {
        $DocDeletedReturn = fn_avatax_tax_calculation_avatax_canceltax($order_info["avatax_paytax_document_code"], "DocDeleted");
        //3. Call GetTax with Commit = False
            $DocCommittedReturn = fn_avatax_tax_calculation_avatax_gettax_commit($order_info, 0);
        }
    } else if (trim($AvaTaxDocumentStatus[$status_from]) == "Voided") {
        //1. Call CancelTax with CancelCode = DocDeleted
        $DocDeletedReturn = fn_avatax_tax_calculation_avatax_canceltax($order_info["avatax_paytax_document_code"], "DocDeleted");

        //2. Call GetTax with Commit = False
        if ($DocDeletedReturn == 'Success') {
            $DocCommittedReturn = fn_avatax_tax_calculation_avatax_gettax_commit($order_info, 0);
        }
    } else if (trim($AvaTaxDocumentStatus[$status_from]) == "Uncommitted") {
        //1. Call GetTax with Commit = False
        $DocCommittedReturn = fn_avatax_tax_calculation_avatax_gettax_commit($order_info, 0);
    }
}

/****************************************************************************
 *                                                                          *
 *   Function Header                                                        *
 *                                                                          *
 *   File Version       :                                                   *
 *   Last Updated On    :   02/03/2021                                      *
 *   Description        :   This function changes                           *
 *                          Document State = Committed                      *
 ****************************************************************************/
function fn_document_state_committed($order_info, $status_from)
{
    $AvaTaxDocumentStatus = fn_get_avatax_document_status();

    if (trim($AvaTaxDocumentStatus[$status_from]) == "Committed") {
        //1. Call CancelTax with CancelCode = Voided
        $DocVoidedReturn = fn_avatax_tax_calculation_avatax_canceltax($order_info["avatax_paytax_document_code"], "DocVoided");
        if ($DocVoidedReturn == 'Success') {
            //2. Call CancelTax with CancelCode = DocDeleted
            $DocDeletedReturn = fn_avatax_tax_calculation_avatax_canceltax($order_info["avatax_paytax_document_code"], "DocDeleted");
            //3. Call GetTax with Commit = False
            $DocCommittedReturn = fn_avatax_tax_calculation_avatax_gettax_commit($order_info, 1);
        }
    } else if (trim($AvaTaxDocumentStatus[$status_from]) == "Voided") {
        //1. Call CancelTax with CancelCode = DocDeleted
        $DocDeletedReturn = fn_avatax_tax_calculation_avatax_canceltax($order_info["avatax_paytax_document_code"], "DocDeleted");
        //2. Call GetTax with Commit = False
        if ($DocDeletedReturn == 'Success') {
            $DocCommittedReturn = fn_avatax_tax_calculation_avatax_gettax_commit($order_info, 1);
        }
    } else if (trim($AvaTaxDocumentStatus[$status_from]) == "Uncommitted") {
        //1. Call GetTax with Commit = True
        $DocCommittedReturn = fn_avatax_tax_calculation_avatax_gettax_commit($order_info, 1);
    }
}

/****************************************************************************
*                                                                           *
*   Function Header                                                         *
*                                                                           *
*   File Version       :                                                    *
*   Last Updated On    :    02/03/2021                                      *
*   Description        :    This function changes                           *
*                           Document State to the desired state as per the  *
*                           passed second parameter                         *
****************************************************************************/

function fn_avatax_change_document_status($order_info, $status_to, $status_from, $order_id = 0)
{
    $log_mode = Registry::get('addons.avatax_tax_calculation.avatax_log_mode');
    if ($log_mode == 1) {
        $e = new Exception();
        $application_log->AddSystemLog($timeStamp->format('Y-m-d H:i:s'), __FUNCTION__, __CLASS__, __METHOD__, __FILE__, '', $status_to, $e->getTraceAsString());
        $application_log->WriteSystemLogToFile();
    }
    
    Switch ($status_to) {
        case 'P': //Processed
            fn_document_state_committed($order_info, $status_from);
            break;
        case 'C': //Complete
            fn_document_state_committed($order_info, $status_from);
            break;
        case 'O': //Open
            fn_document_state_uncommitted($order_info, $status_from);
            break;
        case 'F': //Failed
            fn_document_state_voided($order_info, $status_from);
            break;
        case 'D': //Declined
            fn_document_state_voided($order_info, $status_from);
            break;
        case 'B': //Backordered
            fn_document_state_uncommitted($order_info, $status_from);
            break;
        case 'I': //Canceled
            fn_document_state_voided($order_info, $status_from);
            break;
        default: //Default
            //fn_document_state_committed($order_info, $status_from);
            break;
    }
}

#}
/** [/ORDER STATUS CHANGES FUNCTIONS] **/

/** [AVATAX API CALLS FUNCTIONS] **/
#{

/****************************************************************************
*                                                                           *
*   Function Header                                                         *
*                                                                           *
*   File Version       :                                                    *
*   Last Updated On    :    02/03/2021                                      *
*   Description        :    This function calculate the Taxable Amount      *
*                                                                           *
*   Important (on Feb25):    Removed all commented code from this function  *
*                                                                           *
****************************************************************************/

function fn_avatax_tax_calculation_avatax_amount($group_products, $shipping_rates, $company_id, $cart, $auth, $order_info)
{ 
    $timeStamp = new DateTime();
    $connectorstart = $timeStamp->format('Y-m-d\TH:i:s').".".substr((string)microtime(), 2, 3)." ".$timeStamp->format("P"); 
    $time_start = round(microtime(true) * 1000);
    $lib_path = Registry::get('config.dir.addons') . 'avatax_tax_calculation/lib/';
    require_once($lib_path . "AvaTax4PHP/AvaTax.php");

    $company_details = fn_get_company_placement_info($company_id);

    $service_url = Registry::get('addons.avatax_tax_calculation.avatax_service_url');
    $account = Registry::get('addons.avatax_tax_calculation.avatax_account_number');
    $license = Registry::get('addons.avatax_tax_calculation.avatax_license_key');
    $client = CLIENT_NAME;
    $CompanyCode = Registry::get('addons.avatax_tax_calculation.avatax_company_code');

    $environment = (strpos($service_url, "development") === false) ? 'Production' : 'Development';

    new ATConfig($environment, array('url' => $service_url, 'account' => $account, 'license' => $license, 'client' => $client, 'trace' => TRUE));

    $CustomerCode = $auth['user_id'];
    $OrigAddress = $company_details["company_address"];
    $OrigCity = $company_details["company_city"];
    $OrigRegion = $company_details["company_state"];
    $OrigPostalCode = $company_details["company_zipcode"];
    $OrigCountry = $company_details["company_country"];

    if (!empty($cart['user_data'])) {
        $DestAddress = $cart['user_data']['s_address'];
        $DestCity = $cart['user_data']['s_city'];
        $DestRegion = $cart['user_data']['s_state'];
        $DestPostalCode = $cart['user_data']['s_zipcode'];
        $DestCountry = $cart['user_data']['s_country'];
    } else {
        $user_info = fn_get_user_info($auth['user_id']);

        $DestAddress = $user_info['s_address'];
        $DestCity = $user_info['s_city'];
        $DestRegion = $user_info['s_state'];
        $DestPostalCode = $user_info['s_zipcode'];
        $DestCountry = $user_info['s_country'];
    }

    $DocType = "SalesOrder";
    $a = session_id();
    if (empty($a)) session_start();

    $DocCode = array_key_exists('order_id', $order_info) && $order_info['order_id'] != "" ? $order_info['order_id'] : session_id();
    $SalesPersonCode = "";
    $EntityUseCode = "";
    $Discount = $cart['subtotal_discount'];
    $PurchaseOrderNo = '';
    $ExemptionNo = "";
    $LocationCode = '';
    $LineNo = 1;

    //Get Tax Exemption Details
    if ($auth["user_id"] != "0") {
        $tax_exemption_details = fn_get_tax_exemption_details($auth["user_id"]);
        $ExemptionNo = $tax_exemption_details["tax_exempt_number"];
        $EntityUseCode = $tax_exemption_details["tax_entity_usecode"];
    }

    $client = new TaxServiceSoap($environment);
    $request = new GetTaxRequest();
    $dateTime = new DateTime();
    //$request->setDocDate($DocDate);
    $request->setCompanyCode($CompanyCode);
    $request->setDocType($DocType);
    $request->setDocCode($DocCode);
    $request->setDocDate(date_format($dateTime, "Y-m-d"));
    $request->setSalespersonCode($SalesPersonCode);
    $request->setCustomerCode($CustomerCode);
    $request->setCustomerUsageType($EntityUseCode);
    $request->setDiscount($Discount);
    $request->setPurchaseOrderNo($PurchaseOrderNo);
    $request->setExemptionNo($ExemptionNo);
    $request->setDetailLevel(DetailLevel::$Tax);
    $request->setLocationCode($LocationCode);
    $request->setCurrencyCode(CART_PRIMARY_CURRENCY);
    $request->setCommit(FALSE);
    
    //Add Origin Address
    $origin = new Address();
    $origin->setLine1($OrigAddress);
    $origin->setLine2("");
    $origin->setCity($OrigCity);
    $origin->setRegion($OrigRegion);
    $origin->setPostalCode($OrigPostalCode);
    $origin->setCountry($OrigCountry);
    $request->setOriginAddress($origin);

    // Add Destination Address
    $destination = new Address();
    $destination->setLine1($DestAddress);
    $destination->setLine2("");
    $destination->setCity($DestCity);
    $destination->setRegion($DestRegion);
    $destination->setPostalCode($DestPostalCode);
    $destination->setCountry($DestCountry);
    $request->setDestinationAddress($destination);

    // Line level processing
    $Ref1 = '';
    $Ref2 = '';
  
    $RevAcct = '';
  
    $lines = array();
    $product_total = 0;
    $i = 0;
    $cart_products = $cart['products'];
   
    foreach ($cart_products as $product) {
        $keys_cart_products = array_keys($cart_products, $product);
        $keys_cart_products = $keys_cart_products[0];

        $TaxCode = $product['product_id'];
        $TaxCode = db_get_field("SELECT `tax_code` FROM ?:products WHERE `product_id` = $TaxCode");
        if ($TaxCode == "none") {
            $TaxCode = 'P0000000';
        }

        /////////////////////// UPC Implementation - Start /////////////////////////
        // Receiving UPC Code if Set

        if (Registry::get('addons.avatax_tax_calculation.avatax_tax_upc') == 1)
        {
            $UpcCode = $product['product_id'];
            $UpcCode = db_get_field("SELECT `upc_code` FROM ?:products WHERE `product_id` = $UpcCode");
            if  ( ($UpcCode == "") || ($UpcCode == "none") ) {
                $itemCode = $product["product_code"] == "" ? substr($product["product"], 0, 50) : $product["product_code"];
            } else {
                // UPC Code validation logic will come here : (Future)
                $itemCode = 'UPC:' . $UpcCode;
            }
        } else if ($product["product_code"] == "") {
            $itemCode  = substr($product["product"], 0, 50);
        } else {
            $itemCode = $product["product_code"];
        }
        /////////////////////// UPC Implementation - End /////////////////////////
      
        $line1 = new Line();
        $line1->setNo($i); //$product["product_id"]
        $line1->setItemCode($itemCode);
        $line1->setDescription($product['product']);
        $line1->setTaxCode($TaxCode);
        $line1->setQty($product['amount']);
        $line1->setAmount($product['display_price'] * $product['amount']);
        $line1->setDiscounted(true);
        $line1->setRevAcct($RevAcct);
        $line1->setRef1($Ref1);
        $line1->setRef2($Ref2);
        $line1->setExemptionNo($ExemptionNo);
        $line1->setCustomerUsageType($EntityUseCode);

        $lines[$i] = $line1;
        $i++;
       
    }
    $shipping_id = $cart['chosen_shipping'][0];
    if (isset($shipping_id[0]) || isset($shipping_id)) {
        $TaxCode = db_get_field("SELECT `tax_code` FROM ?:shippings WHERE `shipping_id` = $shipping_id");
        if ($TaxCode == "none") {
            $TaxCode = 'FR';
        }
        $line1 = new Line();
        $line1->setNo($i); 
        $line1->setItemCode("shipping");
        $line1->setDescription($shipping_rates[$shipping_id]['shipping']);
        $line1->setTaxCode($TaxCode);
        $line1->setQty(1);
        $line1->setAmount($shipping_rates[$shipping_id]['rate']);
        $line1->setDiscounted(false);
        $line1->setRevAcct($RevAcct);
        $line1->setRef1($Ref1);
        $line1->setRef2($Ref2);
        $line1->setExemptionNo($ExemptionNo);
        $line1->setCustomerUsageType($EntityUseCode);
        $lines[$i] = $line1;
    }
   
    $request->setLines($lines);
    $returnMessage = "";

    try {
        if (!empty($DestPostalCode)) {
            $connectortime = round(microtime(true) * 1000) - $time_start;
            $latency = round(microtime(true) * 1000);
            $connectorcalling = $timeStamp->format('Y-m-d\TH:i:s').".".substr((string)microtime(), 2, 3)." ".$timeStamp->format("P"); 
            $getTaxResult = $client->getTax($request);
            $connectorcomplete = $timeStamp->format('Y-m-d\TH:i:s').".".substr((string)microtime(), 2, 3)." ".$timeStamp->format("P"); 
            $latency = round(microtime(true) * 1000) - $latency;
            // Error Trapping
            if ($getTaxResult->getResultCode() == SeverityLevel::$Success) {
                /***
                 * Place holder for logs
                 * getLastRequest
                 * getLastResponse
                 $client->__getLastRequest()
                 $client->__getLastRes()
                 
                ******/
                 
                /************* Logging code snippet (optional) starts here *******************/
                // System Logger starts here:
                    require_once "SystemLogger.php";
                    // Creating the System Logger Object
                    $application_log = new SystemLogger;
                    $connectorend = $timeStamp->format('Y-m-d\TH:i:s').".".substr((string)microtime(), 2, 3)." ".$timeStamp->format("P"); 
                    $performance_metrics[] = array("CallerTimeStamp","MessageString","CallerAcctNum","DocCode","Operation","ServiceURL","LogType","LogLevel","ERPName","ERPVersion","ConnectorVersion");            
                    $performance_metrics[] = array($connectorstart,"\"LINECOUNT -".count($getTaxResult->getTaxLines())."PreGetTax Start Time-\"".$connectorstart,$account,$getTaxResult->getDocCode(),"GetTax",$service_url,"Performance","Informational","CS-Cart",PRODUCT_VERSION,AVALARA_VERSION);
                    $performance_metrics[] = array($connectorcalling,"\"LINECOUNT -".count($getTaxResult->getTaxLines())."PreGetTax End Time-\"".$connectorcalling,$account,$getTaxResult->getDocCode(),"GetTax ",$service_url,"Performance","Informational","CS-Cart",PRODUCT_VERSION,AVALARA_VERSION);
                    $performance_metrics[] = array($connectorcomplete,"\"LINECOUNT -".count($getTaxResult->getTaxLines())."PostGetTax Start Time-\"".$connectorcomplete,$account,$getTaxResult->getDocCode(),"GetTax ",$service_url,"Performance","Informational","CS-Cart",PRODUCT_VERSION,AVALARA_VERSION);
                    $performance_metrics[] = array($connectorend,"\"LINECOUNT -".count($getTaxResult->getTaxLines())."PostGetTax End Time-\"".$connectorend,$account,$getTaxResult->getDocCode(),"GetTax ",$service_url,"Performance","Informational","CS-Cart",PRODUCT_VERSION,AVALARA_VERSION);
                    //Call serviceLog function
                    $returnServiceLog = $application_log->serviceLog($performance_metrics);
                   
                    $log_mode = Registry::get('addons.avatax_tax_calculation.avatax_log_mode');
                if ($log_mode == 1) {
                    $params = '[Input: ' . ']'; // Create Param List
                    $u_name = ''; // Eventually will come from $_SESSION[] object
                    $application_log->AddSystemLog($timeStamp->format('Y-m-d H:i:s'), __FUNCTION__, __CLASS__, __METHOD__, __FILE__, $u_name, $params, $client->__getLastRequest());        // Create System Log
                    $application_log->WriteSystemLogToFile(); // Log info goes to log file

                    $application_log->AddSystemLog($timeStamp->format('Y-m-d H:i:s'), __FUNCTION__, __CLASS__, __METHOD__, __FILE__, $u_name, $params, $client->__getLastResponse());        // Create System Log
                    $application_log->WriteSystemLogToFile(); // Log info goes to log file
                    //$application_log->metric('GetTax '.$getTaxResult->getDocType(),count($getTaxResult->getTaxLines()),$getTaxResult->getDocCode(),$connectortime,$latency);

                    //    $application_log->WriteSystemLogToDB(); // Log info goes to DB
                    //     System Logger ends here
                    //    Logging code snippet (optional) ends here
                } 

                // If NOT success - display error messages to console
                // it is important to iterate through the entire message class
                return $getTaxResult;
            } else {
                foreach ($getTaxResult->getMessages() as $msg) {
                    $returnMessage .= $msg->getName() . ": " . $msg->getSummary() . "\n";
                }
                return $getTaxResult;
            }
        }
    } catch (SoapFault $exception) {
        $returnMessage = "Exception: ";
        if ($exception)
            $returnMessage .= $exception->faultstring;

        $return_message = "<b>AvaTax - Error Message</b><br/>";
        $return_message .= $returnMessage;
        $avatax_tax_error = '<div class="warning">' . $return_message . '</div>';
        fn_set_notification('E', __('error'), $avatax_tax_error);
        return 0;
    } //Comment this line to return SOAP XML
}


/****************************************************************************
 *                                                                          *
 *   Function Header                                                        *
 *                                                                          *
 *   File Version       :                                                   *
 *   Last Updated On    :    02/03/2021                                     *
 *   Description        :    This function validates the give address       *
 *   Description        :    along with order info                          *
 *                                                                          *
 *   Important (on Feb25):    To be removed                                 *    
 *                            all commented code from this function         *
 *                                                                          *
 ****************************************************************************/


function fn_avatax_tax_calculation_avatax_address_validation($user_data)
{
    /*
    $company_details = fn_get_company_placement_info($user_data['company_id']);

    $environment = 'Development';

    //if (Registry::get('addons.avatax_tax_calculation.avatax_development_mode')==1) $environment = 'Development';
    //else $environment = 'Production';

    $service_url = Registry::get('addons.avatax_tax_calculation.avatax_service_url');
    $pos = strpos($service_url, "development");
    if ($pos === false) $environment = 'Production';
    else $environment = 'Development';

    $address_data = array();
    $address_data["service_url"] = Registry::get('addons.avatax_tax_calculation.avatax_service_url');
    $address_data["account"] = Registry::get('addons.avatax_tax_calculation.avatax_account_number');
    $address_data["license"] = Registry::get('addons.avatax_tax_calculation.avatax_license_key');
    $address_data["client"] = CLIENT_NAME;
    $address_data["environment"] = $environment;
    $address_data["line1"] = $user_data["b_address"];
    $address_data["line2"] = $user_data["b_address_2"];
    $address_data["line3"] = "";
    $address_data["city"] = $user_data["b_city"];
    $address_data["region"] = $user_data["b_state"];
    $address_data["postalcode"] = $user_data["b_zipcode"];

    $return_message = "";
    $lib_path = Registry::get('config.dir.addons') . 'avatax_tax_calculation/lib/';
    require_once($lib_path . "AvaTax4PHP/address_validation.php");
    $log_mode = Registry::get('addons.avatax_tax_calculation.avatax_log_mode');
    $addon_path = Registry::get('config.dir.addons') . 'avatax_tax_calculation/';

    //$return_message = AddressValidation($address_data);

    $avatax_address_validation = "";
    if (trim($return_message) != "") {
        $avatax_address_validation = '<div class="warning">' . $return_message . '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>';
    } */
    $avatax_address_validation = "";
    return $avatax_address_validation;
}


/****************************************************************************
 *                                                                          *
 *   Function Header                                                        *
 *                                                                          *
 *   File Version       :                                                   *
 *   Last Updated On    :    02/03/2021                                     *
 *   Description        :    This function serves as the wrapper function   *
 *                           for AvaTax GetTax() Call                       *
 *                                                                          *
 *   Important (on Feb25):    Removed all commented code from this function *
 *                                                                          *
 ****************************************************************************/

function fn_avatax_tax_calculation_avatax_gettax($order_info, $auth)
{
    $timeStamp = new DateTime(); // Create Time Stamp
    $connectorstart = $timeStamp->format('Y-m-d\TH:i:s').".".substr((string)microtime(), 2, 3)." ".$timeStamp->format("P"); 
    $time_start = round(microtime(true) * 1000);
    $lib_path = Registry::get('config.dir.addons') . 'avatax_tax_calculation/lib/';
    require_once($lib_path . "AvaTax4PHP/AvaTax.php");

    $company_details = fn_get_company_placement_info($order_info['company_id']);

    $service_url = Registry::get('addons.avatax_tax_calculation.avatax_service_url');
    $account = Registry::get('addons.avatax_tax_calculation.avatax_account_number');
    $license = Registry::get('addons.avatax_tax_calculation.avatax_license_key');
    $client = CLIENT_NAME;
    $CompanyCode = Registry::get('addons.avatax_tax_calculation.avatax_company_code');

    $environment = strpos($service_url, "development") === false ? 'Production' : 'Development';

    new ATConfig($environment, array('url' => $service_url, 'account' => $account, 'license' => $license, 'client' => $client, 'trace' => TRUE));

    //Variable Mapping
    if (!empty($auth['user_id'])) $CustomerCode = $auth['user_id'];
    else $CustomerCode = $order_info['payment_method']['usergroup_ids'];

    $OrigAddress = $company_details["company_address"];
    $OrigCity = $company_details["company_city"];
    $OrigRegion = $company_details["company_state"];
    $OrigPostalCode = $company_details["company_zipcode"];
    $OrigCountry = $company_details["company_country"];

    $DestAddress = $order_info["s_address"];
    $DestCity = $order_info["s_city"];
    $DestRegion = $order_info["s_state"];
    $DestPostalCode = $order_info["s_zipcode"];
    $DestCountry = $order_info["s_country"];
    
    //Code Added for Implementation of Doc Sav Feature in CS Cart 4.2.4 Build
    $DocType = Registry::get('addons.avatax_tax_calculation.avatax_tax_savedoc') == 1 ? "SalesInvoice" : "SalesOrder";
    $DocCode = $order_info['order_id'];
    $SalesPersonCode = "";
    $EntityUseCode = "";
    $Discount = $order_info['subtotal_discount'];
    $PurchaseOrderNo = '';
    $ExemptionNo = "";
    $LocationCode = '';
    $LineNo = 1;

    //Get Tax Exemption Details
    if ($auth["user_id"] != "0") {
        $tax_exemption_details = fn_get_tax_exemption_details($auth["user_id"]);
        $ExemptionNo = $tax_exemption_details["tax_exempt_number"];
        $EntityUseCode = $tax_exemption_details["tax_entity_usecode"];
    }

    $client = new TaxServiceSoap($environment);
    $request = new GetTaxRequest();
    $dateTime = new DateTime();
    //$request->setDocDate($DocDate);
    $request->setCompanyCode($CompanyCode);
    $request->setDocType($DocType);
    $request->setDocCode($DocCode);
    $request->setDocDate(date_format($dateTime, "Y-m-d"));
    $request->setSalespersonCode($SalesPersonCode);
    $request->setDiscount($Discount);
    $request->setCustomerCode($CustomerCode);
    $request->setCustomerUsageType($EntityUseCode);
    $request->setPurchaseOrderNo($PurchaseOrderNo);
    $request->setExemptionNo($ExemptionNo);
    $request->setDetailLevel(DetailLevel::$Tax);
    $request->setLocationCode($LocationCode);
    $request->setCurrencyCode(CART_PRIMARY_CURRENCY);
    $request->setCommit(FALSE);

    //Add Origin Address
    $origin = new Address();
    $origin->setAddressCode(0);
    $origin->setLine1($OrigAddress);
    $origin->setLine2("");
    $origin->setCity($OrigCity);
    $origin->setRegion($OrigRegion);
    $origin->setPostalCode($OrigPostalCode);
    $origin->setCountry($OrigCountry);
    $request->setOriginAddress($origin);

    // Add Destination Address
    $destination = new Address();
    $destination->setAddressCode(1);
    $destination->setLine1($DestAddress);
    $destination->setLine2("");
    $destination->setCity($DestCity);
    $destination->setRegion($DestRegion);
    $destination->setPostalCode($DestPostalCode);
    $destination->setCountry($DestCountry);
    $request->setDestinationAddress($destination);

    // Line level processing
    $Ref1 = '';
    $Ref2 = '';
   
    $RevAcct = '';
  
    $lines = array();
    $product_total = 0;
    $i = 0;

    $avatax_discount_amount = 0;

    foreach ($order_info['products'] as $k => $v) {

        $product_original_amount = fn_product_real_price($v["product_id"]);
        $total_amount = ($v["price"] * $v["amount"]);

        $product_data = fn_get_product_data($v["product_id"], $auth);

        $product_categories = "";
        foreach ($product_data['category_ids'] as $pck => $pcv) {
            $product_categories .= fn_get_category_name($pcv) . ",";
        }
        $Description = $product_categories;

        $TaxCode = $product_data['tax_code'];
        if ($TaxCode == "none") {
            $TaxCode = 'P0000000';
        }

        /////////////////////// UPC Implementation - Start /////////////////////////
        // Receiving UPC Code if set
        // Implementation of UPC Code Implementation Feature in CS Cart 4.2.4
        $UpcCode = $product_data['upc_code'];
        if (Registry::get('addons.avatax_tax_calculation.avatax_tax_upc') == 1)
        {
            if ( ($UpcCode == "") || ($UpcCode == "none") ) {
                $itemCode = $v["product_code"] == "" ? substr($v["product"], 0, 50) : $v["product_code"];
            } else {
                // UPC Code validation logic will come here : Future
                $itemCode = 'UPC:' . $UpcCode;
            }
        } else if ($v["product_code"] == "") {
            $itemCode  = substr($v["product"], 0, 50);
        } else {
            $itemCode  = $v["product_code"];
        }

        /////////////////////// UPC Implementation - End /////////////////////////

        $discount_count = 0;
        $temp_discount_amount = $product_original_amount - $v["price"];
        if ($temp_discount_amount > 0) {
            $discount_count = 1;
            $discount_amount = $v["price"];
        }

        $line1 = new Line();
        $line1->setNo($i + 1);
        $line1->setItemCode($itemCode);
        $line1->setDescription($v["product"]);
        $line1->setTaxCode($TaxCode);
        $line1->setQty($v["amount"]);
        $line1->setAmount($total_amount);
        $line1->setDiscounted(true);
      
        $line1->setRevAcct($RevAcct);
        $line1->setRef1($Ref1);
        $line1->setRef2($Ref2);
        $line1->setExemptionNo($ExemptionNo);
        $line1->setCustomerUsageType($EntityUseCode);
        $line1->setOriginAddress($origin);
        $line1->setDestinationAddress($destination);

        $lines[$i] = $line1;
        $i++;

        $product_total += $v['amount'];
    }

    // Shipping Line Item
    // Order Totals

    $shipping_count = 0;
    foreach ($order_info["shipping"] as $order_shipment) {
        $shipping_id = $order_shipment['shipping_id'];
        $TaxCode = db_get_field("SELECT `tax_code` FROM ?:shippings WHERE `shipping_id` = $shipping_id");
        if ($TaxCode == "none") {
            $TaxCode = 'FR';
        }
        $line1 = new Line();
        $line1->setNo($i + 1);
        //$line1->setItemCode($order_shipment['shipping_id']);
        $line1->setItemCode("Shipping");
        $line1->setDescription($order_shipment['shipping']);
        $line1->setTaxCode($TaxCode);
        $line1->setQty(1);
        $line1->setAmount($order_shipment['rate']);
        $line1->setDiscounted(false);
        $line1->setRevAcct($RevAcct);
        $line1->setRef1($Ref1);
        $line1->setRef2($Ref2);
        $line1->setExemptionNo($ExemptionNo);
        $line1->setCustomerUsageType($EntityUseCode);
        $line1->setOriginAddress($origin);
        $line1->setDestinationAddress($destination);

        $lines[$i] = $line1;
        $i++;
        $shipping_count++;
    }
    
    $request->setLines($lines);
    $request->setDiscount($Discount);

    $GetTaxData = array();
    $returnMessage = "";

    try {
        $connectortime = round(microtime(true) * 1000)-$time_start;
        $latency = round(microtime(true) * 1000);
        $connectorcalling=$timeStamp->format('Y-m-d\TH:i:s').".".substr((string)microtime(), 2, 3)." ".$timeStamp->format("P"); 
        $getTaxResult = $client->getTax($request);
        $connectorcomplete=$timeStamp->format('Y-m-d\TH:i:s').".".substr((string)microtime(), 2, 3)." ".$timeStamp->format("P"); 
        $latency = round(microtime(true) * 1000)-$latency;

        // Error Trapping
        if ($getTaxResult->getResultCode() == SeverityLevel::$Success) {

            $GetTaxData['GetTaxDocCode'] = $getTaxResult->getDocCode();
            $GetTaxData['GetTaxDocDate'] = $getTaxResult->getDocDate();
            $GetTaxData['GetTaxTotalAmount'] = $getTaxResult->getTotalAmount();
            $GetTaxData['GetTaxTotalTax'] = $getTaxResult->getTotalTax();
           
            /***
             * Place holder for logs
             * getLastRequest
             * getLastResponse
             */

            /************* Logging code snippet (optional) starts here *******************/

            require_once "SystemLogger.php";
            // Creating the System Logger Object
            $application_log = new SystemLogger;
            $connectorend = $timeStamp->format('Y-m-d\TH:i:s').".".substr((string)microtime(), 2, 3)." ".$timeStamp->format("P"); 
            $performance_metrics[] = array("CallerTimeStamp","MessageString","CallerAcctNum","DocCode","Operation","ServiceURL","LogType","LogLevel","ERPName","ERPVersion","ConnectorVersion");            
            $performance_metrics[] = array($connectorstart,"\"LINECOUNT -".count($getTaxResult->getTaxLines())."PreGetTax Start Time-\"".$connectorstart,$account,$getTaxResult->getDocCode(),"GetTax",$service_url,"Performance","Informational","CS-Cart",PRODUCT_VERSION,AVALARA_VERSION);
            $performance_metrics[] = array($connectorcalling,"\"LINECOUNT -".count($getTaxResult->getTaxLines())."PreGetTax End Time-\"".$connectorcalling,$account,$getTaxResult->getDocCode(),"GetTax ",$service_url,"Performance","Informational","CS-Cart",PRODUCT_VERSION,AVALARA_VERSION);
            $performance_metrics[] = array($connectorcomplete,"\"LINECOUNT -".count($getTaxResult->getTaxLines())."PostGetTax Start Time-\"".$connectorcomplete,$account,$getTaxResult->getDocCode(),"GetTax ",$service_url,"Performance","Informational","CS-Cart",PRODUCT_VERSION,AVALARA_VERSION);
            $performance_metrics[] = array($connectorend,"\"LINECOUNT -".count($getTaxResult->getTaxLines())."PostGetTax End Time-\"".$connectorend,$account,$getTaxResult->getDocCode(),"GetTax ",$service_url,"Performance","Informational","CS-Cart",PRODUCT_VERSION,AVALARA_VERSION);
            //Call serviceLog function
            $returnServiceLog = $application_log->serviceLog($performance_metrics);
            
            // System Logger starts here:
            $log_mode = Registry::get('addons.avatax_tax_calculation.avatax_log_mode');
                
            if ($log_mode == 1) {

                $params = '[Input: ' . ']';        // Create Param List
                $u_name = '';                      // Eventually will come from $_SESSION[] object
                $application_log->AddSystemLog($timeStamp->format('Y-m-d H:i:s'), __FUNCTION__, __CLASS__, __METHOD__, __FILE__, $u_name, $params, $client->__getLastRequest());        // Create System Log
                $application_log->WriteSystemLogToFile();            // Log info goes to log file
                
                $application_log->AddSystemLog($timeStamp->format('Y-m-d H:i:s'), __FUNCTION__, __CLASS__, __METHOD__, __FILE__, $u_name, $params, $client->__getLastResponse());        // Create System Log
                $application_log->WriteSystemLogToFile();            // Log info goes to log file

               // $application_log->metric('GetTax '.$getTaxResult->getDocType(),count($getTaxResult->getTaxLines()),$getTaxResult->getDocCode(),$connectortime,$latency);

                //    $application_log->WriteSystemLogToDB();                            // Log info goes to DB
                //     System Logger ends here
                //    Logging code snippet (optional) ends here
            }
        
            return $GetTaxData;

        } else {
            foreach ($getTaxResult->getMessages() as $msg) {
               
                $returnMessage .= $msg->getName() . ": " . $msg->getSummary() . "\n";
            }
            return $returnMessage;
        }
    } catch (SoapFault $exception) {
        $msg = "Exception: ";
        if ($exception)
            $msg .= $exception->faultstring;

        // If you desire to retrieve SOAP IN / OUT XML
        //  - Follow directions below
        //  - if not, leave as is

        //echo $msg . "\n";
        return $msg;
        /*    }   //UN-comment this line to return SOAP XML*/
    } //Comment this line to return SOAP XML
    /**/
}


/****************************************************************************
 *                                                                          *
 *   Function Header                                                        *
 *                                                                          *
 *   File Version       :                                                   *
 *   Last Updated On    :    02/03/2021                                     *
 *   Description        :    This function serves as the wrapper function   *
 *                           for AvaTax GetTax() Call                       *
 *                                                                          *
 *   Important (on Feb25):    Removed all commented code from this function *
 *                                                                          *
 ****************************************************************************/

function fn_avatax_tax_calculation_avatax_gettax_commit($order_info, $commit_status)
{
    $timeStamp = new DateTime();                        // Create Time Stamp
    $connectorstart=$timeStamp->format('Y-m-d\TH:i:s').".".substr((string)microtime(), 2, 3)." ".$timeStamp->format("P"); 
   
    $time_start = round(microtime(true) * 1000);
    $lib_path = Registry::get('config.dir.addons') . 'avatax_tax_calculation/lib/';
    require_once($lib_path . "AvaTax4PHP/AvaTax.php");

    $company_details = fn_get_company_placement_info($order_info['company_id']);

    $service_url = Registry::get('addons.avatax_tax_calculation.avatax_service_url');
    $account = Registry::get('addons.avatax_tax_calculation.avatax_account_number');
    $license = Registry::get('addons.avatax_tax_calculation.avatax_license_key');
    $client = CLIENT_NAME;

    $environment = strpos($service_url, "development") === false ? 'Production' : 'Development';

    new ATConfig($environment, array('url' => $service_url, 'account' => $account, 'license' => $license, 'client' => $client, 'trace' => TRUE));

    //Variable Mapping
    $CustomerCode = $order_info["user_id"];

    $OrigAddress = $company_details["company_address"];
    $OrigCity = $company_details["company_city"];
    $OrigRegion = $company_details["company_state"];
    $OrigPostalCode = $company_details["company_zipcode"];
    $OrigCountry = $company_details["company_country"];

    $DestAddress = $order_info["s_address"];
    $DestCity = $order_info["s_city"];
    $DestRegion = $order_info["s_state"];
    $DestPostalCode = $order_info["s_zipcode"];
    $DestCountry = $order_info["s_country"];

    $CompanyCode = Registry::get('addons.avatax_tax_calculation.avatax_company_code');
    $DocType = Registry::get('addons.avatax_tax_calculation.avatax_tax_savedoc') == 1 ? "SalesInvoice" : "SalesOrder";
    $DocCode = $order_info["order_id"];
    $SalesPersonCode = "";
    $EntityUseCode = "";
    $Discount = $order_info['subtotal_discount'];
    $PurchaseOrderNo = '';
    $ExemptionNo = "";
    $LocationCode = '';
    $LineNo = 1;

    //Get Tax Exemption Details
    if ($order_info["user_id"] > 0) {
        $tax_exemption_details = fn_get_tax_exemption_details($order_info["user_id"]);
        $ExemptionNo = $tax_exemption_details["tax_exempt_number"];
        $EntityUseCode = $tax_exemption_details["tax_entity_usecode"];
    }

    $client = new TaxServiceSoap($environment);
    $request = new GetTaxRequest();
    $dateTime = new DateTime(date('Y-m-d H:i:s', $order_info["timestamp"]));
    //$request->setDocDate($DocDate);
    $request->setCompanyCode($CompanyCode);
    $request->setDocType($DocType);
    $request->setDocCode($DocCode);

    $request->setDocDate(date_format($dateTime, "Y-m-d"));
    $request->setSalespersonCode($SalesPersonCode);
    $request->setCustomerCode($CustomerCode);
    $request->setCustomerUsageType($EntityUseCode);
    $request->setDiscount($Discount);
    $request->setPurchaseOrderNo($PurchaseOrderNo);
    $request->setExemptionNo($ExemptionNo);
    $request->setDetailLevel(DetailLevel::$Tax);
    $request->setLocationCode($LocationCode);
    $request->setCurrencyCode(CART_PRIMARY_CURRENCY);
    //$request->setCommit(FALSE);
    if ($commit_status == 0) $request->setCommit(FALSE);
    else $request->setCommit(TRUE);

    //Add Origin Address
    $origin = new Address();
    $origin->setAddressCode(0);
    $origin->setLine1($OrigAddress);
    $origin->setLine2("");
    $origin->setCity($OrigCity);
    $origin->setRegion($OrigRegion);
    $origin->setPostalCode($OrigPostalCode);
    $origin->setCountry($OrigCountry);
    $request->setOriginAddress($origin);

    // Add Destination Address
    $destination = new Address();
    $destination->setAddressCode(1);
    $destination->setLine1($DestAddress);
    $destination->setLine2("");
    $destination->setCity($DestCity);
    $destination->setRegion($DestRegion);
    $destination->setPostalCode($DestPostalCode);
    $destination->setCountry($DestCountry);
    $request->setDestinationAddress($destination);

    // Line level processing
    $Ref1 = '';
    $Ref2 = '';
    //$ExemptionNo = '';
    $RevAcct = '';
    //$EntityUseCode = '';

    $lines = array();
    $product_total = 0;
    $i = 0;

    $avatax_discount_amount = 0;
    $auth = & $_SESSION['auth'];
    foreach ($order_info['products'] as $k => $v) {
        $product_original_amount = fn_product_real_price($v["product_id"]);
        $total_amount = ($v["price"] * $v["amount"]);
        $product_data = fn_get_product_data($v["product_id"], $auth);
        $product_categories = "";
        foreach ($product_data['category_ids'] as $pck => $pcv) {
            $product_categories .= fn_get_category_name($pcv) . ",";
        }
        $Description = $product_categories;
        $TaxCode = $product_data["tax_code"];
        if ($TaxCode == "none") {
            $TaxCode = 'P0000000';
        }

        /////////////////////// UPC Implementation - Start /////////////////////////
        // Receiving UPC Code if set
        $UpcCode = $product_data['upc_code'];
        if (Registry::get('addons.avatax_tax_calculation.avatax_tax_upc') == 1) {
            if  ( ($UpcCode == "") || ($UpcCode == "none") ) {
                if ($v["product_code"] == "") {
                    $itemCode = substr($v["product"], 0, 50);
                } else{
                    $itemCode  = $v["product_code"];
                }
            } else {
                // UPC Code validation logic will come here : Future
                $itemCode = 'UPC:' . $UpcCode;
            }
        } else if ($v["product_code"] == "") {
            $itemCode  = substr($v["product"], 0, 50);
        } else{
            $itemCode  = $v["product_code"];
        }
        /////////////////////// UPC Implementation - End /////////////////////////

        $discount_count = 0;
        $temp_discount_amount = $product_original_amount - $v["price"];
        if ($temp_discount_amount > 0) {
            $discount_count = 1;
            $discount_amount = $v["price"];
        }


        $line1 = new Line();
        $line1->setNo($i + 1);
        $line1->setItemCode($itemCode);
        $line1->setDescription($v["product"]);
        $line1->setTaxCode($TaxCode);
        $line1->setQty($v["amount"]);
        $line1->setAmount($total_amount);
        $line1->setDiscounted(true);
        $line1->setRevAcct($RevAcct);
        $line1->setRef1($Ref1);
        $line1->setRef2($Ref2);
        $line1->setExemptionNo($ExemptionNo);
        $line1->setCustomerUsageType($EntityUseCode);
        $line1->setOriginAddress($origin);
        $line1->setDestinationAddress($destination);

        $lines[$i] = $line1;
        $i++;

        $product_total += $v['amount'];
    }

    //Shipping Line Item
    // Order Totals

    $shipping_count = 0;
    foreach ($order_info["shipping"] as $order_shipment) {
        $shipping_id = $order_shipment['shipping_id'];
        $TaxCode = db_get_field("SELECT `tax_code` FROM ?:shippings WHERE `shipping_id` = $shipping_id");
        if ($TaxCode == "none") {
            $TaxCode = 'FR';
        }
        $line1 = new Line();
        $line1->setNo($i + 1);
        $line1->setItemCode("Shipping");
        $line1->setDescription($order_shipment['shipping']);
        $line1->setTaxCode($TaxCode);
        $line1->setQty(1);
        $line1->setAmount($order_shipment['rate']);
        $line1->setDiscounted(false);
        $line1->setRevAcct($RevAcct);
        $line1->setRef1($Ref1);
        $line1->setRef2($Ref2);
        $line1->setExemptionNo($ExemptionNo);
        $line1->setCustomerUsageType($EntityUseCode);
        $line1->setOriginAddress($origin);
        $line1->setDestinationAddress($destination);

        $lines[$i] = $line1;
        $i++;
        $shipping_count++;
    }

    $request->setLines($lines);
    $GetTaxData = array();
    $returnMessage = "";

    try {
        $connectortime = round(microtime(true) * 1000)-$time_start;
        $latency = round(microtime(true) * 1000);
        $connectorcalling = $timeStamp->format('Y-m-d\TH:i:s').".".substr((string)microtime(), 2, 3)." ".$timeStamp->format("P"); 
        $getTaxResult = $client->getTax($request);
        $connectorcomplete = $timeStamp->format('Y-m-d\TH:i:s').".".substr((string)microtime(), 2, 3)." ".$timeStamp->format("P"); 
        $latency = round(microtime(true) * 1000)-$latency;
        // Error Trapping
        if ($getTaxResult->getResultCode() == SeverityLevel::$Success) {
            $GetTaxData['GetTaxDocCode'] = $getTaxResult->getDocCode();
            $GetTaxData['GetTaxDocDate'] = $getTaxResult->getDocDate();
            $GetTaxData['GetTaxTotalAmount'] = $getTaxResult->getTotalAmount();
            $GetTaxData['GetTaxTotalTax'] = $getTaxResult->getTotalTax();
            /***
             * Place holder for logs
             * getLastRequest
             * getLastResponse
             */
            /************* Logging code snippet (optional) starts here *******************/
            // System Logger starts here:
            require_once "SystemLogger.php";
            // Creating the System Logger Object
            $application_log = new SystemLogger;
            $connectorend=$timeStamp->format('Y-m-d\TH:i:s').".".substr((string)microtime(), 2, 3)." ".$timeStamp->format("P"); 
            $performance_metrics[] = array("CallerTimeStamp","MessageString","CallerAcctNum","DocCode","Operation","ServiceURL","LogType","LogLevel","ERPName","ERPVersion","ConnectorVersion");            
            $performance_metrics[] = array($connectorstart,"\"LINECOUNT -".count($getTaxResult->getTaxLines())."PreGetTax Start Time-\"".$connectorstart,$account,$getTaxResult->getDocCode(),"GetTax",$service_url,"Performance","Informational","CS-Cart",PRODUCT_VERSION,AVALARA_VERSION);
            $performance_metrics[] = array($connectorcalling,"\"LINECOUNT -".count($getTaxResult->getTaxLines())."PreGetTax End Time-\"".$connectorcalling,$account,$getTaxResult->getDocCode(),"GetTax ",$service_url,"Performance","Informational","CS-Cart",PRODUCT_VERSION,AVALARA_VERSION);
            $performance_metrics[] = array($connectorcomplete,"\"LINECOUNT -".count($getTaxResult->getTaxLines())."PostGetTax Start Time-\"".$connectorcomplete,$account,$getTaxResult->getDocCode(),"GetTax ",$service_url,"Performance","Informational","CS-Cart",PRODUCT_VERSION,AVALARA_VERSION);
            $performance_metrics[] = array($connectorend,"\"LINECOUNT -".count($getTaxResult->getTaxLines())."PostGetTax End Time-\"".$connectorend,$account,$getTaxResult->getDocCode(),"GetTax ",$service_url,"Performance","Informational","CS-Cart",PRODUCT_VERSION,AVALARA_VERSION);
            //Call serviceLog function
            $returnServiceLog = $application_log->serviceLog($performance_metrics);
            
            if (Registry::get('addons.avatax_tax_calculation.avatax_log_mode') == 1) {
                $params                =   '[Input: ' . ']';        // Create Param List
                $u_name                =    '';                            // Eventually will come from $_SESSION[] object
                $application_log->AddSystemLog($timeStamp->format('Y-m-d H:i:s'), __FUNCTION__, __CLASS__, __METHOD__, __FILE__, $u_name, $params, $client->__getLastRequest());        // Create System Log
                $application_log->WriteSystemLogToFile();            // Log info goes to log file
                
                $application_log->AddSystemLog($timeStamp->format('Y-m-d H:i:s'), __FUNCTION__, __CLASS__, __METHOD__, __FILE__, $u_name, $params, $client->__getLastResponse());        // Create System Log
                $application_log->WriteSystemLogToFile();            // Log info goes to log file

                //$application_log->metric('GetTax '.$getTaxResult->getDocType(),count($getTaxResult->getTaxLines()),$getTaxResult->getDocCode(),$connectortime,$latency);

                //    $application_log->WriteSystemLogToDB();                            // Log info goes to DB
                //     System Logger ends here
                //    Logging code snippet (optional) ends here
            }
                
            return $GetTaxData;

        } else {
            if ($getTaxResult->getResultCode() == SeverityLevel::$Error) {
                $return_message = "<b>AvaTax - Error Message</b><br/>";
                foreach($getTaxResult->getMessages() as $msg) {
                    $return_message .= $msg->getSummary()."<br/>";
                }
                $avatax_tax_error = '<div class="warning">' . $return_message . '</div>';
                fn_set_notification('E', __('error'), $avatax_tax_error);
            }

            foreach ($getTaxResult->getMessages() as $msg) {
                //echo $msg->getName() . ": " . $msg->getSummary() . "\n";
                $returnMessage .= $msg->getName() . ": " . $msg->getSummary() . "\n";
            }
            return $returnMessage;
        }
    } catch (SoapFault $exception) {
        $msg = "Exception: ";
        if ($exception)
            $msg .= $exception->faultstring;
            $return_message = "<b>AvaTax - Error Message</b><br/>";
            $return_message .= $msg;
            $avatax_tax_error = '<div class="warning">' . $return_message . '</div>';
            fn_set_notification('E', __('error'), $avatax_tax_error);

        // If you desire to retrieve SOAP IN / OUT XML
        //  - Follow directions below
        //  - if not, leave as is

        //echo $msg . "\n";
        return $msg;
        /*    }   //UN-comment this line to return SOAP XML */
    } //Comment this line to return SOAP XML
    /**/
}


/****************************************************************************
 *                                                                          *
 *   Function Header                                                        *
 *                                                                          *
 *   File Version       :                                                   *
 *   Last Updated On    :    02/03/2021                                     *
 *   Description        :    This function serves as the wrapper function   *
 *                           for AvaTax PostTax() Call                      *
 *                                                                          *
 *   Important (on Feb25):    Removed all commented code from this function *
 *                                                                          *
 ****************************************************************************/

function fn_avatax_tax_calculation_avatax_posttax($GetTaxReturnValue)
{
    $service_url = Registry::get('addons.avatax_tax_calculation.avatax_service_url');
    $environment = strpos($service_url, "development") === false ? 'Production' : 'Development';

    $order_data = array();
    $dateTime = new DateTime();

    $company_details = fn_get_company_placement_info($order_info['company_id']);
    $order_data["service_url"] = Registry::get('addons.avatax_tax_calculation.avatax_service_url');
    $order_data["account"] = Registry::get('addons.avatax_tax_calculation.avatax_account_number');
    $order_data["license"] = Registry::get('addons.avatax_tax_calculation.avatax_license_key');
    $order_data["client"] = CLIENT_NAME;
    $order_data["environment"] = $environment;
    $order_data["CompanyCode"] = Registry::get('addons.avatax_tax_calculation.avatax_company_code');
    $order_data["DocType"] = "SalesInvoice";

    $order_data["DocCode"] = $GetTaxReturnValue['GetTaxDocCode'];
    $order_data["DocDate"] = $GetTaxReturnValue['GetTaxDocDate'];

    $order_data["TotalAmount"] = $GetTaxReturnValue['GetTaxTotalAmount'];
    $order_data["TotalTax"] = $GetTaxReturnValue['GetTaxTotalTax'];
    $order_data["Commit"] = 1;

    $returnMessage = "";
    $lib_path = Registry::get('config.dir.addons') . 'avatax_tax_calculation/lib/';
    $addon_path = Registry::get('config.dir.addons') . 'avatax_tax_calculation/';
    require_once($lib_path . "AvaTax4PHP/post_tax.php");

    return $return_message;
}


/****************************************************************************
 *                                                                          *
 *   Function Header                                                        *
 *                                                                          *
 *   File Version       :                                                   *
 *   Last Updated On    :    02/03/2021                                     *
 *   Description        :    This function serves as the wrapper function   *
 *                           for AvaTax CancelTax() Call                    *
 *                                                                          *
 *   Important (on Feb25):    Removed all commented code from this function *
 *                                                                          *
 ****************************************************************************/

function fn_avatax_tax_calculation_avatax_canceltax($AvaTaxDocumentCode, $CancelCode, $DocType = "SalesInvoice")
{
    //echo $AvaTaxDocumentCode;
   
    $service_url = Registry::get('addons.avatax_tax_calculation.avatax_service_url');
    $environment = strpos($service_url, "development") === false ? 'Production' : 'Development';

    $order_data = array();
    $dateTime = new DateTime();

    $order_data["service_url"] = Registry::get('addons.avatax_tax_calculation.avatax_service_url');
    $order_data["account"] = Registry::get('addons.avatax_tax_calculation.avatax_account_number');
    $order_data["license"] = Registry::get('addons.avatax_tax_calculation.avatax_license_key');
    $order_data["client"] = CLIENT_NAME;
    $order_data["environment"] = $environment;
    $order_data["CompanyCode"] = Registry::get('addons.avatax_tax_calculation.avatax_company_code');
    $order_data["DocType"] = $DocType;
    $order_data["DocCode"] = $AvaTaxDocumentCode;
    $order_data["CancelCode"] = $CancelCode;

    $returnMessage = "";
    $lib_path = Registry::get('config.dir.addons') . 'avatax_tax_calculation/lib/';
    $addon_path = Registry::get('config.dir.addons') . 'avatax_tax_calculation/';
    $log_mode = Registry::get('addons.avatax_tax_calculation.avatax_log_mode');
  
    include($lib_path . "AvaTax4PHP/cancel_tax.php");

    return $returnMessage;
}

/****************************************************************************
 *                                                                          *
 *   Function Header                                                        *
 *                                                                          *
 *   File Version       :                                                   *
 *   Last Updated On    :    02/03/2021                                     *
 *   Description        :    This function fetches gettax history for the   *
 *                           sent order details                             *
 *                                                                          *
 *   Important (on Feb25):    Removed all commented code from this function *
 *                                                                          *
 ****************************************************************************/


function fn_avatax_tax_calculation_avatax_gettax_history($order_info)
{
    $service_url = Registry::get('addons.avatax_tax_calculation.avatax_service_url');
    $environment = strpos($service_url, "development") === false ? 'Production' : 'Development';

    $company_details = fn_get_company_placement_info($order_info['company_id']);

    $order_data = array();
    $dateTime = new DateTime();

    $order_data["service_url"] = Registry::get('addons.avatax_tax_calculation.avatax_service_url');
    $order_data["account"] = Registry::get('addons.avatax_tax_calculation.avatax_account_number');
    $order_data["license"] = Registry::get('addons.avatax_tax_calculation.avatax_license_key');
    $order_data["client"] = CLIENT_NAME;
    $order_data["environment"] = $environment;
    $order_data["CompanyCode"] = Registry::get('addons.avatax_tax_calculation.avatax_company_code');
    $order_data["DocType"] = "SalesInvoice";
    $order_data["DocCode"] = $order_info['avatax_paytax_document_code'];

    $returnMessage = "";
    $GetTaxHistoryReturnValue = array();
    $lib_path = Registry::get('config.dir.addons') . 'avatax_tax_calculation/lib/';
    $addon_path = Registry::get('config.dir.addons') . 'avatax_tax_calculation/';
    $log_mode = Registry::get('addons.avatax_tax_calculation.avatax_log_mode');
    include($lib_path . "AvaTax4PHP/get_tax_history.php");
   
    return $GetTaxHistoryReturnValue;
}


/****************************************************************************
 *                                                                          *
 *   Function Header                                                        *
 *                                                                          *
 *   File Version       :                                                   *
 *   Last Updated On    :    02/03/2021                                     *
 *   Description        :    This function does tax computation on          *
 *                           sent return invoice details                    *
 *                                                                          *
 *   Important (on Feb25):    Removed all commented code from this function *
 *                                                                          *
 ****************************************************************************/

function fn_avatax_tax_calculation_avatax_return_invoice($return_info, $order_info, $tax_history_data)
{
    $timeStamp = new DateTime(); // Create Time Stamp
    $connectorstart = $timeStamp->format('Y-m-d\TH:i:s').".".substr((string)microtime(), 2, 3)." ".$timeStamp->format("P"); 
    
    $time_start = round(microtime(true) * 1000);
    $lib_path = Registry::get('config.dir.addons') . 'avatax_tax_calculation/lib/';
    require_once($lib_path . "AvaTax4PHP/AvaTax.php");

    $company_details = fn_get_company_placement_info($order_info['company_id']);

    $service_url = Registry::get('addons.avatax_tax_calculation.avatax_service_url');
    $account = Registry::get('addons.avatax_tax_calculation.avatax_account_number');
    $license = Registry::get('addons.avatax_tax_calculation.avatax_license_key');
    $client = CLIENT_NAME;
    $CompanyCode = Registry::get('addons.avatax_tax_calculation.avatax_company_code');
    $environment = strpos($service_url, "development") === false ? 'Production' : 'Development';

    new ATConfig($environment, array('url' => $service_url, 'account' => $account, 'license' => $license, 'client' => $client, 'trace' => TRUE));

    $CustomerCode = $return_info["user_id"];
    $auth = & $_SESSION['auth'];
   
    $OrigAddress = $company_details["company_address"];
    $OrigCity = $company_details["company_city"];
    $OrigRegion = $company_details["company_state"];
    $OrigPostalCode = $company_details["company_zipcode"];
    $OrigCountry = $company_details["company_country"];

    $DestAddress = $order_info["s_address"];
    $DestCity = $order_info["s_city"];
    $DestRegion = $order_info["s_state"];
    $DestPostalCode = $order_info["s_zipcode"];
    $DestCountry = $order_info["s_country"];
    
    //Code Added to implement Doc Type Feature in CS Cart 4.2.4 build)
    $DocType = Registry::get('addons.avatax_tax_calculation.avatax_tax_savedoc') == 1 ? "ReturnInvoice" : "ReturnOrder";
    $DocCode = $return_info["avatax_return_document_code"];

    $SalesPersonCode = "";
    $EntityUseCode = "";
    $Discount = 0; //$order_info['subtotal_discount'];
    $PurchaseOrderNo = '';
    $ExemptionNo = "";
    $LocationCode = '';
    $LineNo = 1;

    //Get Tax Exemption Details
    if ($return_info["user_id"] > 0) {
        $tax_exemption_details = fn_get_tax_exemption_details($return_info["user_id"]);
        $ExemptionNo = $tax_exemption_details["tax_exempt_number"];
        $EntityUseCode = $tax_exemption_details["tax_entity_usecode"];
    }

    $client = new TaxServiceSoap($environment);
    $request = new GetTaxRequest();
    $dateTime = new DateTime();
    $request->setCompanyCode($CompanyCode);
    $request->setDocType($DocType);
    $request->setDocCode($DocCode);
    $request->setDocDate(date_format($dateTime, "Y-m-d"));
    $request->setSalespersonCode($SalesPersonCode);
    $request->setCustomerCode($CustomerCode);
    $request->setCustomerUsageType($EntityUseCode);
    $request->setDiscount(-$Discount);
    $request->setPurchaseOrderNo($PurchaseOrderNo);
    $request->setExemptionNo($ExemptionNo);
    $request->setDetailLevel(DetailLevel::$Tax);
    $request->setLocationCode($LocationCode);
    $request->setCurrencyCode(CART_PRIMARY_CURRENCY);

    //Return Status = Complete then Committed else Uncommitted
    if ($return_info['status'] == 'A' || $return_info['status'] == 'C') $request->setCommit(TRUE);
    else $request->setCommit(FALSE);

    //Add Origin Address
    $origin = new Address();
    $origin->setAddressCode(0);
    $origin->setLine1($OrigAddress);
    $origin->setLine2("");
    $origin->setCity($OrigCity);
    $origin->setRegion($OrigRegion);
    $origin->setPostalCode($OrigPostalCode);
    $origin->setCountry($OrigCountry);
    $request->setOriginAddress($origin);

    // Add Destination Address
    $destination = new Address();
    $destination->setAddressCode(1);
    $destination->setLine1($DestAddress);
    $destination->setLine2("");
    $destination->setCity($DestCity);
    $destination->setRegion($DestRegion);
    $destination->setPostalCode($DestPostalCode);
    $destination->setCountry($DestCountry);
    $request->setDestinationAddress($destination);

    // Line level processing
    $Ref1 = '';
    $Ref2 = '';
  
    $RevAcct = '';
  

    $lines = array();
    $product_total = 0;
    $i = 0;
    $discount_amount = 0;

    $avatax_discount_amount = 0;
    $return_reason = "";
    $return_order_array = $return_info['items'];
    $accepted_items = $return_order_array['A'];
    
    if (count($accepted_items) > 0) {
        foreach ($accepted_items as $key_inner => $value_inner) {

            $v = $order_info["products"][$key_inner];

            $return_reason = fn_return_reason($value_inner["reason"]);
            $product_original_amount = fn_product_real_price($value_inner["product_id"]);
            
            $total_amount = ($value_inner["price"] * $value_inner["amount"]);

            $product_data = fn_get_product_data($value_inner["product_id"], $auth);
            $product_categories = "";
            foreach ($product_data['category_ids'] as $pck => $pcv) {
                $product_categories .= fn_get_category_name($pcv) . ",";
            }
            $Description = $product_categories;

            $TaxCode = $product_data["tax_code"];

            if ($TaxCode == "none") {
                $TaxCode = 'P0000000';
            }

            /////////////////////// UPC Implementation - Start /////////////////////////
            // Receiving UPC Code if set 
            $UpcCode = $product_data['upc_code'];
            if (Registry::get('addons.avatax_tax_calculation.avatax_tax_upc') == 1)
            {
                if  ( ($UpcCode == "") || ($UpcCode == "none") ) {
                    if ($product_data["product_code"] == "") {
                        $itemCode = substr($product_data["product"], 0, 50);
                    }
                    else{
                        $itemCode  = $product_data["product_code"];
                    }
                }
                else
                {
                    // UPC Code validation logic will come here : Future
                    $itemCode = 'UPC:' . $UpcCode;
                }
            }
            else if ($product_data["product_code"] == "")
            {
                $itemCode  = substr($product_data["product"], 0, 50);
            }
            else{
                $itemCode  = $product_data["product_code"];
            }
            /////////////////////// UPC Implementation - End /////////////////////////


            $discount_count = 0;
            $temp_discount_amount = $product_original_amount - $value_inner["price"];
            if ($temp_discount_amount > 0) {
                $discount_count = 1;
                $discount_amount = $value_inner["price"];
            }

            $line1 = new Line();
            $line1->setNo($i+1);//$product["product_id"]
            
            $line1->setItemCode($itemCode);
            $line1->setDescription($value_inner["product"]);
            $line1->setTaxCode($TaxCode);
            
            $line1->setQty($value_inner["amount"]);
            
            $line1->setAmount(-$total_amount);
            $line1->setDiscounted(true);
            
            $line1->setRevAcct($RevAcct);
            $line1->setRef1($Ref1);
            $line1->setRef2($Ref2);
            $line1->setExemptionNo($ExemptionNo);
            $line1->setCustomerUsageType($EntityUseCode);
            $line1->setOriginAddress($origin);
            $line1->setDestinationAddress($destination);

            $lines[$i] = $line1;
            $i++;

            
            $product_total += $value_inner['amount'];
        }
    //}

        $request->setLines($lines);
    
        $TaxOverride = new TaxOverride();
    
        $TaxOverride->setTaxOverrideType("TaxDate");
        $TaxOverride->setTaxDate($tax_history_data["DocDate"]);
        $TaxOverride->setReason($return_reason);
        $request->setTaxOverride($TaxOverride);

        $GetTaxData = array();
        $returnMessage = "";

        try {
            $connectortime = round(microtime(true) * 1000) - $time_start;
            $latency = round(microtime(true) * 1000);
            $connectorcalling=$timeStamp->format('Y-m-d\TH:i:s').".".substr((string)microtime(), 2, 3)." ".$timeStamp->format("P"); 
            $getTaxResult = $client->getTax($request);
            $connectorcomplete=$timeStamp->format('Y-m-d\TH:i:s').".".substr((string)microtime(), 2, 3)." ".$timeStamp->format("P"); 
            $latency = round(microtime(true) * 1000)-$latency;

            // Error Trapping
            if ($getTaxResult->getResultCode() == SeverityLevel::$Success) {
                $GetTaxData['GetTaxDocCode'] = $getTaxResult->getDocCode();
                $GetTaxData['GetTaxDocDate'] = $getTaxResult->getDocDate();
                $GetTaxData['GetTaxTotalAmount'] = $getTaxResult->getTotalAmount();
                $GetTaxData['GetTaxTotalTax'] = $getTaxResult->getTotalTax();
                /***
                 * Place holder for logs
                 * getLastRequest
                 * getLastResponse
                 */
                
                /************* Logging code snippet (optional) starts here *******************/
                // System Logger starts here:
                include_once "SystemLogger.php";
                // Creating the System Logger Object
                $application_log     =     new SystemLogger;
                
                $connectorend=$timeStamp->format('Y-m-d\TH:i:s').".".substr((string)microtime(), 2, 3)." ".$timeStamp->format("P"); 
                $performance_metrics[] = array("CallerTimeStamp","MessageString","CallerAcctNum","DocCode","Operation","ServiceURL","LogType","LogLevel","ERPName","ERPVersion","ConnectorVersion");            
                $performance_metrics[] = array($connectorstart,"\"LINECOUNT -".count($getTaxResult->getTaxLines())."PreGetTax Start Time-\"".$connectorstart,$account,$getTaxResult->getDocCode(),"GetTax",$service_url,"Performance","Informational","CS-Cart",PRODUCT_VERSION,AVALARA_VERSION);
                $performance_metrics[] = array($connectorcalling,"\"LINECOUNT -".count($getTaxResult->getTaxLines())."PreGetTax End Time-\"".$connectorcalling,$account,$getTaxResult->getDocCode(),"GetTax ",$service_url,"Performance","Informational","CS-Cart",PRODUCT_VERSION,AVALARA_VERSION);
                $performance_metrics[] = array($connectorcomplete,"\"LINECOUNT -".count($getTaxResult->getTaxLines())."PostGetTax Start Time-\"".$connectorcomplete,$account,$getTaxResult->getDocCode(),"GetTax ",$service_url,"Performance","Informational","CS-Cart",PRODUCT_VERSION,AVALARA_VERSION);
                $performance_metrics[] = array($connectorend,"\"LINECOUNT -".count($getTaxResult->getTaxLines())."PostGetTax End Time\"".$connectorend,$account,$getTaxResult->getDocCode(),"GetTax ",$service_url,"Performance","Informational","CS-Cart",PRODUCT_VERSION,AVALARA_VERSION);
                //Call serviceLog function
                $returnServiceLog = $application_log->serviceLog($performance_metrics);
        
                if (Registry::get('addons.avatax_tax_calculation.avatax_log_mode') == 1) {
                    $params = '[Input: ' . ']'; // Create Param List
                    $u_name = ''; // Eventually will come from $_SESSION[] object
                    $application_log->AddSystemLog($timeStamp->format('Y-m-d H:i:s'), __FUNCTION__, __CLASS__, __METHOD__, __FILE__, $u_name, $params, $client->__getLastRequest());        // Create System Log
                    $application_log->WriteSystemLogToFile(); // Log info goes to log file

                    $application_log->AddSystemLog($timeStamp->format('Y-m-d H:i:s'), __FUNCTION__, __CLASS__, __METHOD__, __FILE__, $u_name, $params, $client->__getLastResponse());        // Create System Log
                    $application_log->WriteSystemLogToFile(); // Log info goes to log file
                    // $application_log->metric('GetTax '.$getTaxResult->getDocType(),count($getTaxResult->getTaxLines()),$getTaxResult->getDocCode(),$connectortime,$latency);
                    //    $application_log->WriteSystemLogToDB();                            // Log info goes to DB
                    //     System Logger ends here
                    //    Logging code snippet (optional) ends here
                }

                return $GetTaxData;

            } else {
                $return_message = "<b>AvaTax - Error Message</b><br/>";
                foreach ($getTaxResult->getMessages() as $msg) {
                    $return_message .= $msg->getName() . ": " . $msg->getSummary() . "\n";
                }

                $avatax_tax_error = '<div class="warning">' . $return_message . '</div>';
                fn_set_notification('E', __('error'), $avatax_tax_error);
                return $getTaxResult;
            }
        } catch (SoapFault $exception) {
            $msg = "Exception: ";
            if ($exception)
                $msg .= $exception->faultstring;

            $return_message = "<b>AvaTax - Error Message</b><br/>";
            $return_message .= $msg;
            $avatax_tax_error = '<div class="warning">' . $return_message . '</div>';
            fn_set_notification('E', __('error'), $avatax_tax_error);

            // If you desire to retrieve SOAP IN / OUT XML
            //  - Follow directions below
            //  - if not, leave as is

            //echo $msg . "\n";
            return $msg;
            //    }   //UN-comment this line to return SOAP XML
        } //Comment this line to return SOAP XML
    } else{
       return fn_avatax_tax_calculation_avatax_canceltax($return_info['avatax_return_document_code'], "DocVoided", "ReturnInvoice");
    }
}

#}
/** [/AVATAX API CALLS FUNCTIONS] **/

/** [HOOKS] **/

#{

/****************************************************************************
 *                                                                          *
 *   Function Header                                                        *
 *                                                                          *
 *   File Version       :                                                   *
 *   Last Updated On    :    02/03/2021                                     *
 *   Description        :    This function invoke the posttax()             *
 *                                                                          *
 *   Important (on Feb25):    Removed all commented code from this function *
 *                                                                          *
 ****************************************************************************/
function fn_avatax_tax_calculation_calculate_taxes_post($cart, $group_products, $shipping_rates, $auth, &$calculated_data)
{
    $user_info = fn_get_user_info($auth['user_id']);
    $user_data = $cart['user_data'];
    $ava_tax_flag = "";
    $avatax_product_taxes = "";
    $company_id = 0;
    if (isset($cart['chosen_shipping']) && !empty($cart['chosen_shipping'])) {    
        $shipping_id = $cart['chosen_shipping'][0];
    }
    $lib_path = Registry::get('config.dir.addons') . 'avatax_tax_calculation/lib/';
    
    if (isset($_REQUEST['edit_step'] )) {
        $ava_tax_flag = $_REQUEST['edit_step'];
    }
    if ($_REQUEST['dispatch'] == 'order_management.update') {
        $ava_tax_flag = 'step_four';
    }

    if (Registry::get('addons.avatax_tax_calculation.avatax_tax_calculation') == 1 && (isset($cart['payment_id']) && $cart['payment_id'] > 0) && (($ava_tax_flag == 'step_four') || (!empty($shipping_id)) || !$cart['shipping_required'] ) ) {
        $cart_products = $cart['products'];
        if (isset($cart['avatax_tax_taxes'])) {    
            $avatax_product_taxes = $cart['avatax_tax_taxes'];
        }
        if (!empty($cart_products[0])) {    
            $company_id = $cart_products[0]['company_id'];
        }
        $p_rate = 0;
        $s_rate = 0;

        $order_info = !empty($cart['user_data']) ? $cart['user_data'] : fn_get_user_info($auth['user_id']);

        //Address Validation Starts Here
        /**************

        if (Registry::get('addons.avatax_tax_calculation.avatax_tax_address_validation') == 1) {
            //AvaTax - Address Validation - Check
            $avatax_tax_country = "";

            if (trim(Registry::get('addons.avatax_tax_calculation.avatax_tax_address_validation_place')) == "both") {
                $avatax_tax_country = "|US|CA|";
            } else {
                $avatax_tax_country = "|" . Registry::get('addons.avatax_tax_calculation.avatax_tax_address_validation_place') . "|";
            }
            $avatax_tax_country_pos = strpos($avatax_tax_country, "|" . $order_info["s_country"] . "|");

            if ($avatax_tax_country_pos !== false) {
                $return_message = fn_avatax_tax_calculation_avatax_address_validation($order_info);
                //Registry::set('addons.avatax_tax_calculation.avatax_tax_flag','1');
                if (trim($return_message) != "") {
                    fn_set_notification('E', __('error'), $return_message);
                    $errors = true;
                    //$_REQUEST['next_step'] = $_REQUEST['update_step'];
                }
                if ($order_info['avatax_paytax_error_message'] != 'Success') {
                    //Add error code
                    $avatax_tax_error = "<b>AvaTax - Error Message</b><br/>";
                    $avatax_tax_error .= '<div class="warning">' . $order_info['avatax_paytax_error_message'] . '</div>';
                    fn_set_notification('E', __('error'), $avatax_tax_error);
                }
            }
        }
        ************/
        //Address Validation Ends Here

        $rate_value = 0;
        $tax_result = 0;
        if (!empty($cart) && !empty($cart_products)) {
            $tax_rate_data = fn_avatax_tax_calculation_avatax_amount($group_products, $shipping_rates, $company_id, $cart, $auth, $order_info);
            
            if (!empty($tax_rate_data)) {
                if ($tax_rate_data->getResultCode() == SeverityLevel::$Success) {
                    $rate_value = $tax_rate_data->getTaxLine(0)->getRate();
                    $tax_result = $tax_rate_data->getTotalTax();
                    $rate_value = $rate_value * 100;
                    $p_rate = $tax_result;
                }
                if ($tax_rate_data->getResultCode() == SeverityLevel::$Error) {

                    $return_message = "<b>AvaTax - Error Message</b><br/>";
                    foreach($tax_rate_data->getMessages() as $msg)
                    {
                       $return_message .= $msg->getSummary()."<br/>";
                    }
                    $avatax_tax_error = '<div class="warning">' . $return_message . '</div>';
                    fn_set_notification('E', __('error'), $avatax_tax_error);
                }
            }
        }

        $avatax_tax_id = 1;
        $calculated_data = array($avatax_tax_id => array(
            'rate_type' => 'P',
            'rate_value' => $rate_value,
            'price_includes_tax' => 'N',
            'priority' => 0,
            'tax_subtotal' => $tax_result,
            'description' => 'Total Tax',
            'applies' => Array
            (
                'P' => $p_rate,
                'S' => $s_rate
            )
        ));
       
    } else {
        $avatax_tax_id = 1;
        $calculated_data = array($avatax_tax_id => array(
            'rate_type' => 'P',
            'rate_value' => 0,
            'price_includes_tax' => 'N',
          
            'priority' => 0,
            'tax_subtotal' => 0,
            'description' => 'Total Tax',
            'applies' => Array
            (
                'P' => 0,
                'S' => 0
            )
        ));
    }
}

/****************************************************************************
 *                                                                          *
 *   Function Header                                                        *
 *                                                                          *
 *   File Version       :                                                   *
 *   Last Updated On    :    02/03/2021                                     *
 *   Description        :    **** PENDING *****                             *
 *                                                                          *
 *                                                                          *
 *   Important (on Feb25):    *** Action Pending ***                        *
 *                                                                          *
 ****************************************************************************/

//function fn_avatax_tax_calculation_post_add_to_cart($product_data, &$cart, $auth, $update)
//{
//    if (Registry::get('addons.avatax_tax_calculation.avatax_tax_calculation')==1)
//    {
//        $cart_products = $cart['products'];
//
//        foreach ($product_data as $key => $data) {
//            foreach ($cart_products as $key_cp => $data_cp) {
//                if ($data['product_id']==$data_cp['product_id'])
//                {
//                    $product_id = $data_cp['product_id'];
//                    $product_price = $data_cp['price'];
//                    $company_id = $data_cp['company_id'];
//                }
//            }
//        }
//
//        /*$tax_result = fn_avatax_tax_calculation_avatax_amount($product_price, $company_id, $cart, $auth);
//        $tax_rate_data = array();
//        foreach($tax_result->getTaxLines() as $tax_line)
//        {
//            $tax_rate_count = 0;
//            foreach($tax_line->getTaxDetails() as $tax_details)
//            {
//                $tax_rate_data[$tax_rate_count] = array(
//                    'tax_rate_id' => $tax_rate_count,
//                    'name'        => $tax_details->getTaxName(),
//                    'rate'        => $tax_details->getRate(),
//                    'type'        => $tax_details->getTaxType(),
//                    'amount'      => $tax_details->getTax()
//                );
//                $tax_rate_count++;
//            }
//        }*/
//
//        $tax_rate_data = fn_avatax_tax_calculation_get_avatax_taxes($product_price, $company_id, $cart, $auth);
//        if (is_array($cart['avatax_tax_taxes'])) $temp_avatax_taxes = $cart['avatax_tax_taxes'];
//        else $temp_avatax_taxes = array();
//        $cart['avatax_tax_taxes'] = ($temp_avatax_taxes + array($product_id=>$tax_rate_data));
//        //$cart['avatax_tax_taxes'] = ($temp_avatax_taxes + array($product_price=>$tax_rate_data));
//
//        fn_get_avatax_document_status();
//    }
//}



/****************************************************************************
 *                                                                          *
 *   Function Header                                                        *
 *                                                                          *
 *   File Version       :                                                   *
 *   Last Updated On    :    02/03/2021                                     *
 *   Description        :    This function re-calculate the tax - once the  *
 *                           order is updated                               *
 ****************************************************************************/


function fn_avatax_tax_calculation_update_order($order, $order_id) {
    if (Registry::get('addons.avatax_tax_calculation.avatax_tax_calculation') == 1) {
        if (Registry::get('addons.avatax_tax_calculation.avatax_tax_savedoc') == 1) {
            $order_info = fn_get_order_info($order_id);
            $order['order_id'] = $order_id;
            $order['avatax_paytax_document_code'] = $order_info['avatax_paytax_document_code'];
            $status_to = $order['order_status'];
            $status_from = $order['order_status'];
            fn_avatax_change_document_status($order, $status_to, $status_from);
        }
    }
}


/****************************************************************************
 *                                                                          *
 *   Function Header                                                        *
 *                                                                          *
 *   File Version       :                                                   *
 *   Last Updated On    :    02/03/2021                                     *
 *   Description        :    This function calculates the tax on the placed *
 *                           order                                          *
 ****************************************************************************/

function fn_avatax_tax_calculation_place_order($order_id, $action, $order_status, $cart, $auth)
{
    if (isset($_SESSION['tax_rate_data'])) {
        unset($_SESSION['tax_rate_data']);
    }
    if (isset($_SESSION['prev_result'])) {
        unset($_SESSION['prev_result']);
    }

    if (Registry::get('addons.avatax_tax_calculation.avatax_tax_calculation') == 1) {
        $order_info = fn_get_order_info($order_id);

        // This checking is to avoid call to AvaTax API when order is edited from the Admin
        if (isset($order_info['avatax_paytax_document_code'])) {
            //Call 2 Methods
            //1. GetTax with OrderType = SalesInvoice
            $GetTaxReturnValue = fn_avatax_tax_calculation_avatax_gettax($order_info, $auth);
            //$avatax_fields_exists = mysql_query("SHOW columns from `?:orders` where field='avatax_paytax_document_id'");
            $avatax_fields_exists = db_query("SHOW columns from `?:orders` where field='avatax_paytax_document_id'");

            //if ($avatax_fields_exists==false) {

            if ($avatax_fields_exists->num_rows == 0) {
                db_query("ALTER TABLE ?:orders ADD `avatax_paytax_document_id` INT NOT NULL DEFAULT '0', ADD `avatax_paytax_transaction_id` INT NOT NULL DEFAULT '0', ADD `avatax_paytax_document_code` VARCHAR( 40 ) NOT NULL, ADD `avatax_paytax_error_message` TEXT NOT NULL");
            }

            //2. PostTax with GUID
            //C = Completed, P = Processed
            if ($order_info['status'] == 'C' || $order_info['status'] == 'P') {
                if (is_array($GetTaxReturnValue)) {
                    $PostTaxReturnValue = fn_avatax_tax_calculation_avatax_posttax($GetTaxReturnValue);

                    if (is_array($PostTaxReturnValue)) {
                        fn_avatax_tax_calculation_update_order_with_avatax_fields($PostTaxReturnValue["DocId"], $PostTaxReturnValue["TransactionId"], $GetTaxReturnValue["GetTaxDocCode"], "Success", $order_id);
                    } else {
                        fn_avatax_tax_calculation_update_order_with_avatax_fields(0, 0, $GetTaxReturnValue["GetTaxDocCode"], "Success", $order_id);
                    }
                } else {
                    fn_avatax_tax_calculation_update_order_with_avatax_fields(0, 0, 0, $GetTaxReturnValue, $order_id);
                }
            } else {
                if (is_array($GetTaxReturnValue)) {
                    fn_avatax_tax_calculation_update_order_with_avatax_fields(0, 0, $GetTaxReturnValue["GetTaxDocCode"], "Success", $order_id);
                } else {
                    fn_avatax_tax_calculation_update_order_with_avatax_fields(0, 0, 0, $GetTaxReturnValue, $order_id);
                }
            }
        }
    }
}

/****************************************************************************
 *                                                                          *
 *   Function Header                                                        *
 *                                                                          *
 *   File Version       :                                                   *
 *   Last Updated On    :    02/03/2021                                     *
 *   Description        :    ***** PENDING *****                            *
 *                                                                          *
 ****************************************************************************/

function fn_avatax_tax_calculation_is_user_exists_post($user_id, $user_data, $is_exist)
{
    if (Registry::get('addons.avatax_tax_calculation.avatax_tax_address_validation') == 1) {
        //AvaTax - Address Validation - Check
        //$avatax_tax_country = "";
        //if (trim(Registry::get('addons.avatax_tax_calculation.avatax_tax_address_validation_place')) == "both") {
        //    $avatax_tax_country = "|US|CA|";
        //} else {
        //    $avatax_tax_country = "|" . Registry::get('addons.avatax_tax_calculation.avatax_tax_address_validation_place') . "|";
        //}
        //$avatax_tax_country_pos = strpos($avatax_tax_country, "|" . $user_data["s_country"] . "|");

        //if ($avatax_tax_country_pos !== false) {
        //    $return_message = fn_avatax_tax_calculation_avatax_address_validation($user_data);
            //Registry::set('addons.avatax_tax_calculation.avatax_tax_flag','1');
        //    if (trim($return_message) != "") {
        //        fn_set_notification('E', __('error'), $return_message);
        //        $errors = true;
                //$_REQUEST['next_step'] = $_REQUEST['update_step'];
        //    }
        //}
    }
}

/****************************************************************************
 *                                                                          *
 *   Function Header                                                        *
 *                                                                          *
 *   File Version       :                                                   *
 *   Last Updated On    :    02/03/2021                                     *
 *   Description        :    Recalculate the tax when the order status is   *
 *                           changed from one state to another              *
 ****************************************************************************/

function fn_avatax_tax_calculation_change_order_status($status_to, $status_from, $order_info, $force_notification, $order_statuses, $place_order)
{
    //Code added for address validation
    /***
    if (Registry::get('addons.avatax_tax_calculation.avatax_tax_address_validation') == 1) {
        //AvaTax - Address Validation - Check
        $avatax_tax_country = "";
        if (trim(Registry::get('addons.avatax_tax_calculation.avatax_tax_address_validation_place')) == "both") {
            $avatax_tax_country = "|US|CA|";
        } else {
            $avatax_tax_country = "|" . Registry::get('addons.avatax_tax_calculation.avatax_tax_address_validation_place') . "|";
        }
        $avatax_tax_country_pos = strpos($avatax_tax_country, "|" . $order_info["s_country"] . "|");

        if ($avatax_tax_country_pos !== false) {
            $return_message = fn_avatax_tax_calculation_avatax_address_validation($order_info);
            //Registry::set('addons.avatax_tax_calculation.avatax_tax_flag','1');
            if (trim($return_message) != "") {
                fn_set_notification('E', __('error'), $return_message);
                $errors = true;
                //$_REQUEST['next_step'] = $_REQUEST['update_step'];
            }
            if ($order_info['avatax_paytax_error_message'] != 'Success') {
                //Add error code
                $avatax_tax_error = "<b>AvaTax - Error Message</b><br/>";
                $avatax_tax_error .= '<div class="warning">' . $order_info['avatax_paytax_error_message'] . '</div>';
                fn_set_notification('E', __('error'), $avatax_tax_error);
            }
        }
    }
    *****/
    //Code for address validation ends here.

    //This checking is to avoid more call to AvaTax API
    if ((trim($status_from) != trim($status_to)) && Registry::get('addons.avatax_tax_calculation.avatax_tax_calculation') == 1) {
        if (Registry::get('addons.avatax_tax_calculation.avatax_tax_savedoc') == 1) {
            if ($status_from == 'N') //This checking is to avoid more call to AvaTax API
            {
                if ($status_to != 'O') {
                    fn_avatax_change_document_status($order_info, $status_to, $status_from);
                }
            } else {
                fn_avatax_change_document_status($order_info, $status_to, $status_from);
            }
        }
    }
}

/****************************************************************************
 *                                                                          *
 *   Function Header                                                        *
 *                                                                          *
 *   File Version       :                                                   *
 *   Last Updated On    :    02/03/2021                                     *
 *   Description        :    **** PENDING *****                             *
 ****************************************************************************/

function fn_avatax_tax_calculation_send_return_mail_pre($return_info, $order_info)
{

    if (Registry::get('addons.avatax_tax_calculation.avatax_tax_calculation') == 1) {
        $ReturnOrderDocCode = $return_info['return_id'];

        $return_info["avatax_return_document_code"] = $ReturnOrderDocCode;

        $TaxHistoryReturnValue = fn_avatax_tax_calculation_avatax_gettax_history($order_info);

        $ReturnsReturnValue = fn_avatax_tax_calculation_avatax_return_invoice($return_info, $order_info, $TaxHistoryReturnValue);

        if (is_array($ReturnsReturnValue)) {
            fn_edit_return_with_avatax_doccode($ReturnsReturnValue['GetTaxDocCode'], $return_info['return_id']);
        }
    }
}

/****************************************************************************
*                                                                           *
*   Function Header                                                         *
*                                                                          *
*   File Version       :                                                   *
*   Last Updated On        :    02/03/2021                                     *
*   Description            :      This function invokes CancelTax on return order    *
****************************************************************************/


function fn_return_state_voided($return_info, $order_info,$status_from)
{
    $AvaTaxDocumentStatus = fn_get_avatax_return_status();

    if (trim($AvaTaxDocumentStatus[$status_from]) == "Committed") {
        //1. Call CancelTax with CancelCode = DocVoided
        $DocDeletedReturn = fn_avatax_tax_calculation_avatax_canceltax($return_info['avatax_return_document_code'], "DocVoided", "ReturnInvoice");
    } else if (trim($AvaTaxDocumentStatus[$status_from]) == "Voided") {

    } else if (trim($AvaTaxDocumentStatus[$status_from]) == "Uncommitted") {
        //1. Call CancelTax with CancelCode = DocVoided
        $DocDeletedReturn = fn_avatax_tax_calculation_avatax_canceltax($return_info['avatax_return_document_code'], "DocVoided", "ReturnInvoice");
    }
}


/****************************************************************************
 *                                                                          *
 *   Function Header                                                        *
 *                                                                          *
 *   File Version       :                                                   *
 *   Last Updated On    :    02/03/2021                                     *
 *   Description        :    This function invokes CancelTax on return order*
 ****************************************************************************/

function fn_return_state_uncommitted($return_info, $order_info,$status_from)
{
    $AvaTaxDocumentStatus = fn_get_avatax_return_status();
    $TaxHistoryReturnValue = fn_avatax_tax_calculation_avatax_gettax_history($order_info);
    if (trim($AvaTaxDocumentStatus[$status_from]) == "Committed") {
        //1. Call CancelTax with CancelCode = Voided
        $DocVoidedReturn = fn_avatax_tax_calculation_avatax_canceltax($return_info['avatax_return_document_code'], "DocVoided", "ReturnInvoice");

        if ($DocVoidedReturn == 'Success') {
            //2. Call CancelTax with CancelCode = DocDeleted
            $DocDeletedReturn =fn_avatax_tax_calculation_avatax_canceltax($return_info['avatax_return_document_code'], "DocDeleted", "ReturnInvoice");
            //3. Call GetTax with Commit = False
            $DocCommittedReturn = fn_avatax_tax_calculation_avatax_return_invoice($return_info, $order_info, $TaxHistoryReturnValue);
        }
    } else if (trim($AvaTaxDocumentStatus[$status_from]) == "Voided") {
        //1. Call CancelTax with CancelCode = DocDeleted
        $DocDeletedReturn = fn_avatax_tax_calculation_avatax_canceltax($return_info['avatax_return_document_code'], "DocDeleted", "ReturnInvoice");
        if ($DocDeletedReturn == 'Success') {
            //2. Call GetTax with Commit = False
            $DocCommittedReturn = fn_avatax_tax_calculation_avatax_return_invoice($return_info, $order_info, $TaxHistoryReturnValue);
        }
    } else if (trim($AvaTaxDocumentStatus[$status_from]) == "Uncommitted") {
        //1. Call GetTax with Commit = False
        $DocCommittedReturn = fn_avatax_tax_calculation_avatax_return_invoice($return_info, $order_info, $TaxHistoryReturnValue);
    }
}


/****************************************************************************
 *                                                                          *
 *   Function Header                                                        *
 *                                                                          *
 *   File Version       :                                                   *
 *   Last Updated On    :    02/03/2021                                     *
 *   Description        :    This function invokes CancelTax on return order*
 ****************************************************************************/

function fn_return_state_committed($return_info, $order_info,$status_from)
{
    $AvaTaxDocumentStatus = fn_get_avatax_return_status();
    $TaxHistoryReturnValue = fn_avatax_tax_calculation_avatax_gettax_history($order_info);
    if (trim($AvaTaxDocumentStatus[$status_from]) == "Committed") {
        //1. Call CancelTax with CancelCode = Voided
        $DocVoidedReturn = fn_avatax_tax_calculation_avatax_canceltax($return_info['avatax_return_document_code'], "DocVoided", "ReturnInvoice");

        if ($DocVoidedReturn == 'Success') {
            //2. Call CancelTax with CancelCode = DocDeleted
            $DocDeletedReturn = fn_avatax_tax_calculation_avatax_canceltax($return_info['avatax_return_document_code'], "DocDeleted", "ReturnInvoice");

            //3. Call GetTax with Commit = False
            $DocCommittedReturn = fn_avatax_tax_calculation_avatax_return_invoice($return_info, $order_info, $TaxHistoryReturnValue);
        }
    } else if (trim($AvaTaxDocumentStatus[$status_from]) == "Voided") {
        //1. Call CancelTax with CancelCode = DocDeleted
        $DocDeletedReturn =  fn_avatax_tax_calculation_avatax_canceltax($return_info['avatax_return_document_code'], "DocDeleted", "ReturnInvoice");

        if ($DocDeletedReturn == 'Success') {
            //2. Call GetTax with Commit = False
            $DocCommittedReturn = fn_avatax_tax_calculation_avatax_return_invoice($return_info, $order_info, $TaxHistoryReturnValue);
        }
    } else if (trim($AvaTaxDocumentStatus[$status_from]) == "Uncommitted") {
        //1. Call GetTax with Commit = True
        $DocCommittedReturn = fn_avatax_tax_calculation_avatax_return_invoice($return_info, $order_info, $TaxHistoryReturnValue);
    }
}

/****************************************************************************
 *                                                                          *
 *   Function Header                                                        *
 *                                                                          *
 *   File Version       :                                                   *
 *   Last Updated On    :    02/03/2021                                     *
 *   Description        :    This function changes the Return Status of     *
 *                           a return order                                 *
 ****************************************************************************/

function fn_avatax_tax_calculation_change_return_status($return_info, $order_info)
{
    $change_return_status = $_REQUEST['change_return_status'];
    $status_from = $change_return_status['status_from'];
    
    if (Registry::get('addons.avatax_tax_calculation.avatax_tax_calculation') == 1) {
        if (Registry::get('addons.avatax_tax_calculation.avatax_tax_savedoc') == 1)
        {
            Switch ($return_info['status']) {
                case 'A': //Accepted
                    fn_return_state_committed($return_info, $order_info,$status_from);
                    break;
                case 'C': //Complete
                    fn_return_state_committed($return_info, $order_info,$status_from);
                    break;
                case 'R': //Requested
                   fn_return_state_uncommitted($return_info, $order_info,$status_from);
                    break;
                case 'D': //Declined
                    fn_return_state_voided($return_info, $order_info,$status_from);
                    break;
            }
        }
    }
}

/****************************************************************************
 *                                                                          *
 *   Function Header                                                        *
 *                                                                          *
 *   File Version       :                                                   *
 *   Last Updated On    :    02/03/2021                                     *
 *   Description        :    This function feeds entity use code to         *
 *                           view from Database                             *
 *                                                                          *
 ****************************************************************************/

function fn_avatax_tax_calculation_get_user_info_before(&$condition, &$user_id, &$user_fields, &$join)
{
    array_push($user_fields, "tax_exempt_number");
    array_push($user_fields, "tax_entity_usecode");
    
    $tax_entity_usecode_data = db_get_array("SELECT * FROM ?:avatax_entity_usecode WHERE 1");

    Registry::get('view')->assign('tax_entity_usecode_data', $tax_entity_usecode_data);
}

/****************************************************************************
 *                                                                          *
 *   Function Header                                                        *
 *                                                                          *
 *   File Version       :                                                   *
 *   Last Updated On    :    09/07/2015                                     *
 *   Description        :    This function is for Avatax Address Validation *
 ****************************************************************************/

function fn_avatax_tax_calculation_address_validation($flag)
{
    $address_validation = Registry::get('addons.avatax_tax_calculation.avatax_tax_address_validation');
    if ($address_validation && !empty($_REQUEST['userData'])) {
        $user_data = array();
        $resArr = array();
        $address_data = array();
        $valid_address_data = array();
        parse_str($_REQUEST['userData'], $userDataarray);
        $user_data = $userDataarray['user_data'];
        $address_data["service_url"] = Registry::get('addons.avatax_tax_calculation.avatax_service_url');
        $address_data["account"] = Registry::get('addons.avatax_tax_calculation.avatax_account_number');
        $address_data["license"] = Registry::get('addons.avatax_tax_calculation.avatax_license_key');
        $environment = strpos($address_data["service_url"], "development") === false ? 'Production': 'Development';
        $address_data["environment"] = $environment;
       
        //$address_data["client"] = CLIENT_NAME;
        if ($flag == 1) {
            $address_data["line1"] = $user_data["b_address"];
            $address_data["line2"] = $user_data["b_address_2"];
            //$address_data["line3"] = "";
            $address_data["city"] = $user_data["b_city"];
            $address_data["region"] = $user_data["b_state"];
            $address_data["postalcode"] = $user_data["b_zipcode"];
            $address_data["country"] = $user_data["b_country"];
        } else if ($flag == 2) {
            $address_data["line1"] = $user_data["s_address"];
            $address_data["line2"] = $user_data["s_address_2"];
            //$address_data["line3"] = "";
            $address_data["city"] = $user_data["s_city"];
            $address_data["region"] = $user_data["s_state"];
            $address_data["postalcode"] = $user_data["s_zipcode"];
            $address_data["country"] = $user_data["s_country"];
        }    
        $lib_path = Registry::get('config.dir.addons') . 'avatax_tax_calculation/lib/';
        require_once($lib_path . "AvaTax4PHP/address_validation.php");
        $log_mode = Registry::get('addons.avatax_tax_calculation.avatax_log_mode');
        $addon_path = Registry::get('config.dir.addons') . 'avatax_tax_calculation/';
        $valid_address_data['address'] = $address->getLine1();
        $valid_address_data['address_2'] = $address->getLine2();
        $valid_address_data['city'] = $address->getCity();
        $valid_address_data["zipcode"] = $address->getPostalCode();
        $valid_address_data["state"] = $address->getRegion();
        $valid_address_data["country"] = $address->getCountry();
        
        if ($return_message != "") {
            $resArr["success"]=0;
            $resArr["msg"]=$return_message_js;
             $resArr["address"]=$address_data;
        }
        else{
            $resArr["success"]=1;
            $resArr["msg"]=$valid_address_data;
            $resArr["address"]=$address_data;
        }
        echo json_encode($resArr);

        exit;
        
    }
}     

function fn_avatax_tax_calculation_update_cart_by_data_post($cart, $new_cart_data, $auth)
{  
    $order_id = $cart['order_id'];
    $order_info = fn_get_order_info($order_id);
    $cart['stored_taxes'] = 'Y';
    $cart['tax_subtotal'] = $GetTaxReturnValue['GetTaxTotalTax'];

    $GetTaxReturnValue = fn_avatax_tax_calculation_avatax_gettax($order_info, $auth);
    $rate_value = $GetTaxReturnValue['GetTaxTotalTax'];
    $avatax_tax_id = 1;    
    return $cart["stored_taxes_data"] = array($avatax_tax_id => array(
        'rate_type' => 'P',
        'rate_value' => $rate_value,
        'price_includes_tax' => 'N',
        'priority' => 0,
        'tax_subtotal' => $tax_result,
        'description' => 'Total Tax',
        'applies' => array (
            'P' => $p_rate,
            'S' => $s_rate
        )
    ));
}

#}
//$content .= "Product Original Price *** ".print_r($product_original_price,true)."\n";
//file_put_contents("usage_log.txt", $content, FILE_APPEND | LOCK_EX);

/** [/HOOKS] **/
