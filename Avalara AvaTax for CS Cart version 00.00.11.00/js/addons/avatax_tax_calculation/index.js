$( document ).ready(function() {
    if (document.querySelector("[id^=\'addon_option_avatax_tax_calculation_avatax_account_number\']").value != "") {
        $("#avatax_tax_calculation_section2").hide();
        $("#signinInfo").hide();
    } 

    $(".cm-dialog-closer").click(function() {
        if (document.querySelector("[id^=\'addon_option_avatax_tax_calculation_avatax_company_code\']").value != "") {
            $("[id^=\'container_addon_option_avatax_tax_calculation_select_codes\']").hide(); 
        }
        if (document.querySelector("[id^=\'addon_option_avatax_tax_calculation_avatax_account_number\']").value != "" 
                && document.querySelector("[id^=\'addon_option_avatax_tax_calculation_avatax_company_code\']").value == "") {
            alert("AvaTax Company Code should not be empty.\n Click On Test Connection to get Company Codes");
            return false;
        }    
    });

    $(".btn-primary").click(function() {
        if (!validateFields()) { return false; }
        if (document.querySelector("[id^=\'addon_option_avatax_tax_calculation_avatax_company_code\']").value == "") {
            alert("AvaTax Company Code should not be empty.\n Click On Test Connection to get Company Codes");
            return false;
        } else {
            var accountVal = document.querySelector("[id^=\'addon_option_avatax_tax_calculation_avatax_account_number\']").value;
            var licenseVal = document.querySelector("[id^=\'addon_option_avatax_tax_calculation_avatax_license_key\']").value;
            var serviceURLVal = document.querySelector("[id^=\'addon_option_avatax_tax_calculation_avatax_service_url\']").value;
            var isAvataxEnabled = $("[id^=\'addon_option_avatax_tax_calculation_avatax_tax_calculation\'] input[type=radio]:checked").val();
            var isUPCOption = $("[id^=\'addon_option_avatax_tax_calculation_avatax_tax_upc\'] input[type=radio]:checked").val();
            var isSaveTransaction = $("[id^=\'addon_option_avatax_tax_calculation_avatax_tax_savedoc\'] input[type=radio]:checked").val();
            var isAddressValidation = $("[id^=\'addon_option_avatax_tax_calculation_avatax_tax_address_validation\'] input[type=radio]:checked").val();
            var isLogEnabled = $("[id^=\'addon_option_avatax_tax_calculation_avatax_log_mode input\'][type=radio]:checked").val();
            var companyCode = document.querySelector("[id^=\'addon_option_avatax_tax_calculation_avatax_company_code\']").value;
            var environment = serviceURLVal.indexOf("development") > 0 ? "Development" : "Production";
                    
            $.ajax({
                url:"?dispatch=avatax_tax_calculation.config_log&security_hash="+Tygh.security_hash+"&acc="+accountVal+"&license="+licenseVal+"&serviceurl="+serviceURLVal+"&environment="+environment+"&client=" + AVATAX_CLIENT + "&isAvataxEnabled="+isAvataxEnabled+"&isUPCOption="+isUPCOption+"&isSaveTransaction="+isSaveTransaction+"&isLogEnabled="+isLogEnabled+"&companyCode="+companyCode+"&isAddressValidation="+isAddressValidation,
                success: function(result) {
                    //alert(result);
                },
                async: false,
                type : "POST"         
            });
        }     
    });
    
    //Hide the company name and and code fields if it is not added 
    if (document.querySelector("[id^=\'addon_option_avatax_tax_calculation_avatax_company_code\']").value == "") {      
        $("[id^=\'container_addon_option_avatax_tax_calculation_avatax_company_code\']").hide();
    }
    $("[id^=\'container_addon_option_avatax_tax_calculation_select_codes\']").hide();
    $("[id^=\'addon_option_avatax_tax_calculation_avatax_company_code\']").attr("readonly", "true");
});

if (document.querySelector("[id^=\'addon_option_avatax_tax_calculation_avatax_company_code\']").value != "") { 
    $("[id^=\'addon_option_avatax_tax_calculation_avatax_account_number\']").on("input", function() {
    $("[id^=\'addon_option_avatax_tax_calculation_avatax_company_code\']").val("");
    $("[id^=\'container_addon_option_avatax_tax_calculation_avatax_company_code\']").hide();
    $("[id^=\'container_addon_option_avatax_tax_calculation_select_codes\']").hide();                
    });
}

$("[id^=\'addon_option_avatax_tax_calculation_avatax_license_key\']").on("input", function() {
    $("[id^=\'addon_option_avatax_tax_calculation_avatax_company_code\']").val("");
    $("[id^=\'container_addon_option_avatax_tax_calculation_avatax_company_code\']").hide();
    $("[id^=\'container_addon_option_avatax_tax_calculation_select_codes\']").hide();                
}) ; 

document.querySelector("[id^=\'addon_option_avatax_tax_calculation_avatax_service_url\']").addEventListener("change", function () {
    document.querySelector("[id^=\'addon_option_avatax_tax_calculation_avatax_company_code\']").value = "";
    $("[id^=\'container_addon_option_avatax_tax_calculation_avatax_company_code\']").hide();
    $("[id^=\'container_addon_option_avatax_tax_calculation_select_codes\']").hide();                
}); 
            
$("[id^=\'addon_option_avatax_tax_calculation_select_codes\']").change( function() {
    $(this).find(":selected").each(function () {
        $("[id^=\'addon_option_avatax_tax_calculation_avatax_company_code\']").val($(this).val());
    });
});

document.getElementById("variant_avatax_tax_calculation_1").addEventListener("click", function() {
    if (document.querySelector("[id^=\'addon_option_avatax_tax_calculation_avatax_account_number\']").value == "") {
        alert("AvaTax Account ID should not empty.");
        document.getElementById("variant_avatax_tax_calculation_0").checked = true;
        document.querySelector("[id^=\'addon_option_avatax_tax_calculation_avatax_account_number\']").focus();                
    }
    else if (document.querySelector("[id^=\'addon_option_avatax_tax_calculation_avatax_license_key\']").value == "") {
        alert("AvaTax License Key should not empty.");
        document.getElementById("variant_avatax_tax_calculation_0").checked = true;
        document.querySelector("[id^=\'addon_option_avatax_tax_calculation_avatax_license_key\']").focus();
    }
    else if (document.querySelector("[id^=\'addon_option_avatax_tax_calculation_avatax_service_url\']").value == "") {
        alert("AvaTax Service URL should not empty.");
        document.getElementById("variant_avatax_tax_calculation_0").checked = true;
        document.querySelector("[id^=\'addon_option_avatax_tax_calculation_avatax_service_url\']").focus();
    }
    else if (document.querySelector("[id^=\'addon_option_avatax_tax_calculation_avatax_company_code\']").value == "") {
        alert("AvaTax Company Code should not empty.");
        document.getElementById("variant_avatax_tax_calculation_0").checked = true;
        document.querySelector("[id^=\'addon_option_avatax_tax_calculation_avatax_company_code\']").focus();
    }
});   

document.getElementById("variant_avatax_tax_address_validation_1").addEventListener("click", function() {
    if (document.querySelector("[id^=\'addon_option_avatax_tax_calculation_avatax_account_number\']").value == "") {
        alert("AvaTax Account ID should not empty.");
        document.getElementById("variant_avatax_tax_calculation_0").checked = true;
        document.querySelector("[id^=\'addon_option_avatax_tax_calculation_avatax_account_number\']").focus();
    }
    else if (document.querySelector("[id^=\'addon_option_avatax_tax_calculation_avatax_license_key\']").value == "") {
        alert("AvaTax License Key should not empty.");
        document.getElementById("variant_avatax_tax_calculation_0").checked = true;
        document.querySelector("[id^=\'addon_option_avatax_tax_calculation_avatax_license_key\']").focus();
    }
    else if (document.querySelector("[id^=\'addon_option_avatax_tax_calculation_avatax_service_url\']").value == "") {
        alert("AvaTax Service URL should not empty.");
        document.getElementById("variant_avatax_tax_calculation_0").checked = true;
        document.querySelector("[id^=\'addon_option_avatax_tax_calculation_avatax_service_url\']").focus();
    }
    else if (document.querySelector("[id^=\'addon_option_avatax_tax_calculation_avatax_company_code\']").value == "") {
        alert("AvaTax Company Name should not empty.");
        document.getElementById("variant_avatax_tax_calculation_0").checked = true;
        document.querySelector("[id^=\'addon_option_avatax_tax_calculation_avatax_company_code\']").focus();
    }
});

var devMode = document.getElementById("addon_option_avatax_tax_calculation_avatax_development_mode");
if(devMode !== null) {
    devMode.addEventListener("click", function() {
        var selectedVal = "";
        var selected = $('input[type="radio"][id="variant_avatax_development_mode_1"]:checked');
        if (selected.length > 0) {
            selectedValue = selected.val();
        } else {
            selected = $('input[type="radio"][id="variant_avatax_development_mode_0"]:checked');
            if (selected.length > 0)
                selectedValue = selected.val();
        }    
        
        document.querySelector("[id^=\'addon_option_avatax_tax_calculation_avatax_service_url\']").removeAttr("disabled");
        
        if (selectedValue == 1)
            document.querySelector("[id^=\'addon_option_avatax_tax_calculation_avatax_service_url\']").val("https://development.avalara.net/");
        else if (selectedValue == 0)
            document.querySelector("[id^=\'addon_option_avatax_tax_calculation_avatax_service_url\']").val("https://avatax.avalara.net");
    });
}

document.getElementById("AvaTaxTestConnection").addEventListener("click", function() {
    if (validateFields()) {
        $("#AvaTaxTestConnectionDialog").html('<div style="text-align:center;padding-top:10px;"><img src="design/backend/media/images/loading2.gif" border="0" alt="Work In Progress..." ><br/>Work In Progress...</div>');
        $("#AvaTaxTestConnectionDialog").dialog({
            width:350,
            height:200,
            buttons: {
                "OK": function() {
                    $( this ).dialog( "close" );
                }
            },
            open: function( event, ui ) {
                if (!$("#AvaTaxTestConnectionDialog").parent().hasClass("ui-dailog-inner"))
                   $("#AvaTaxTestConnectionDialog").parent().addClass("ui-dailog-inner");
            } 
        });
        var accountVal = document.querySelector("[id^=\'addon_option_avatax_tax_calculation_avatax_account_number\']").value;
        var licenseVal = document.querySelector("[id^=\'addon_option_avatax_tax_calculation_avatax_license_key\']").value;
        var serviceURLVal = document.querySelector("[id^=\'addon_option_avatax_tax_calculation_avatax_service_url\']").value;
      
        var environment = serviceURLVal.indexOf("development") > 0 ? "Development" : "Production";
        
        $.post("?dispatch=avatax_tax_calculation.connection_test&security_hash="+Tygh.security_hash+"&from=AvaTaxConnectionTest&acc="+accountVal+"&license="+licenseVal+"&serviceurl="+serviceURLVal+"&environment="+environment+"&client=" + AVATAX_CLIENT, {q: ""}, function(data) {
            if (data.length > 0) {
                $("#AvaTaxTestConnectionDialog").html(data);
                if (!data.match(/Failed/gi)) {
                    showCompanyFields();
                    validateCompany();
                } else {
                    $("[id^=\'addon_option_avatax_tax_calculation_avatax_company_code\']").val("");
                    $("[id^=\'container_addon_option_avatax_tax_calculation_avatax_company_code\']").hide();
                }                                       
            }
        });
    }            
});
        
function validateFields() {
    if (document.querySelector("[id^=\'addon_option_avatax_tax_calculation_avatax_account_number\']").value == "") {
        alert("Please enter AvaTax Account ID!");
        document.querySelector("[id^=\'addon_option_avatax_tax_calculation_avatax_account_number\']").focus();
        return false;
    } else if (document.querySelector("[id^=\'addon_option_avatax_tax_calculation_avatax_account_number\']").value.length!=10) {
        alert("AvaTax Account ID should not be less than or greater than 10 digits!");
        document.querySelector("[id^=\'addon_option_avatax_tax_calculation_avatax_account_number\']").focus();
        return false;
    } else if (document.querySelector("[id^=\'addon_option_avatax_tax_calculation_avatax_license_key\']").value == "") {
        alert("Please enter AvaTax License Key");
        document.querySelector("[id^=\'addon_option_avatax_tax_calculation_avatax_license_key\']").focus();
        return false;
    } else if (document.querySelector("[id^=\'addon_option_avatax_tax_calculation_avatax_license_key\']").value.length!=16) {
        alert("AvaTax License Key should not be less than or greater than 16 chars");
        document.querySelector("[id^=\'addon_option_avatax_tax_calculation_avatax_license_key\']").focus();
        return false;
    } else if (document.querySelector("[id^=\'addon_option_avatax_tax_calculation_avatax_service_url\']").value == "") {
        alert("Please enter AvaTax Service URL");
        document.querySelector("[id^=\'addon_option_avatax_tax_calculation_avatax_service_url\']").focus();
        return false;
    }

    return true;
}

function showCompanyFields() {
    $("[id^=\'addon_option_avatax_tax_calculation_avatax_company_code\']").attr("readonly", "true");                                                      
    $("[id^=\'container_addon_option_avatax_tax_calculation_avatax_company_code\']").show();
    $("[id^=\'container_addon_option_avatax_tax_calculation_select_codes\']").show();
}

function validateCompany() {
                            
    var accountVal = document.querySelector("[id^=\'addon_option_avatax_tax_calculation_avatax_account_number\']").value;
    var licenseVal = document.querySelector("[id^=\'addon_option_avatax_tax_calculation_avatax_license_key\']").value;
    var serviceURLVal = document.querySelector("[id^=\'addon_option_avatax_tax_calculation_avatax_service_url\']").value;
            
    var environment = serviceURLVal.indexOf("development") ? "Development" : "Production";

    $.post("?dispatch=avatax_tax_calculation.setup_assistant&security_hash="+Tygh.security_hash+"&from=AvaTaxFetchCompanies&acc="+accountVal+"&license="+licenseVal+"&serviceurl="+serviceURLVal+"&environment="+environment+"&client=" + AVATAX_CLIENT, {q: ""}, function(data) {
        if (data.length > 0) {
            var accountsData = JSON.parse(data);
            $("[id^=\'addon_option_avatax_tax_calculation_select_codes\']").find("option").remove();
            var  _select="";
            _select = $("<select>");
            _select.append($("<option></option>").val("").html("Select one"));
            $.each(accountsData, function(index, value) {
                _select.append($("<option></option>").val(index).html(value));
            });
            $("[id^=\'addon_option_avatax_tax_calculation_select_codes\']").append(_select.html());                                                       
        }
    });
}