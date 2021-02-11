<?php
require_once("TPAConfiguration.php");    //Fetch connection details from CSCart config file
$con=mysqli_connect($connection,$user,$password,$database);
// Check connection
if (mysqli_connect_errno())
{
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

//Started creating XML file
//header("Content-type: application/xml");
$xml= "<?xml version=\"1.0\"?>";
$xml.= "<TPA>
    <AccountCredentials>
    <AccountNumber>".$_REQUEST['acc']."</AccountNumber>
    <LicenseKey>".$_REQUEST['license']."</LicenseKey>
    <UserName>".$_REQUEST['username']."</UserName>
    <Password>".$_REQUEST['password']."</Password>
    <WebService>".$_REQUEST['serviceurl']."</WebService>
    <CompanyCode>".$_REQUEST['companyCode']."</CompanyCode>";

//Fetch Company details
$companyQuery = "SELECT distinct company,address,city,state,country,zipcode,email,phone from ".$tablePrefix."companies LIMIT 0,1";

$companyRes = mysqli_query($con,$companyQuery);

while($companyData = mysqli_fetch_array($companyRes))
{
    $companyName = $companyData['company'];
    $companyAddress = $companyData['address'];
    $companyCity = $companyData['city'];
    $companyState = $companyData['state'];
    $companyCountry = $companyData['country'];
    $companyZip = $companyData['zipcode'];
    $companyEmail = $companyData['email'];
    $companyPhone = $companyData['phone'];

    $xml.="
    <ERPName>CSCart</ERPName>
    </AccountCredentials>
    <Company>
    <CompanyName>".$companyName."</CompanyName>
    <TIN>TinAva</TIN>
    <BIN>BinAva</BIN>
    <Address>
    <Line1>".$companyAddress."</Line1>
      <Line2></Line2>
      <Line3>".$companyState." ".$companyZip."</Line3>
      <City>".$companyCity."</City>
      <StateProvince>".$companyState."</StateProvince>
      <Country>".$companyCountry."</Country>
        <ZipPostalCode>".$companyZip."</ZipPostalCode>
    </Address>
    <PrimaryContact>
      <FirstName>".$companyEmail."</FirstName>
      <LastName>".$companyEmail."</LastName>
      <Email>".$companyEmail."</Email>
      <PhoneNumber>".$companyPhone."</PhoneNumber>
      <Title></Title>
      <MobileNumber>".$companyPhone."</MobileNumber>
      <Fax />
    </PrimaryContact>";
}

$xml.="</Company>
    <Nexus>
    <CompanyLocations>
      <CompanyLocation>
        <Country>".$companyCountry."</Country>
        <States>".$companyState."</States>
      </CompanyLocation>
    </CompanyLocations>
    <WareHouseLocations>
        <WareHouseLocation>
            <Country>".$companyCountry."</Country>
            <States>".$companyState."</States>
        </WareHouseLocation>
    </WareHouseLocations>
    <PreviousCustomerLocations>";

//Fetch all order count to calculate average
$orderAvgQuery = "SELECT count(order_id) as 'Count' FROM ".$tablePrefix."orders";
$orderAvgQueryRes = mysqli_query($con,$orderAvgQuery);
$orderAvgQueryData = mysqli_fetch_array($orderAvgQueryRes,MYSQLI_ASSOC);
$orderAvgQueryRows = $orderAvgQueryData['Count'];

//Fetch all order delivery information of unique addresses
$today = date("Y-m-d");
$oneYearBack = date("Y-m-d",strtotime("$today -1 year"));
$addressQuery = "SELECT s_country,s_state FROM ".$tablePrefix."orders where from_unixtime(`timestamp`) BETWEEN '".$oneYearBack."' and NOW() GROUP BY s_state order by order_id desc LIMIT 0,1000";

$addressRes = mysqli_query($con,$addressQuery);
while($addressData = mysqli_fetch_array($addressRes,MYSQLI_ASSOC))
{
    //To check tax has been calculated for how may invoices for selected state and country
    $invoicesChargedQry = "SELECT COUNT(*) AS 'Count' FROM ".$tablePrefix."orders where from_unixtime(`timestamp`) BETWEEN '".$oneYearBack."' and NOW() and s_state='".$addressData["s_state"]."' and s_country='".$addressData["s_country"]."'  and  avatax_paytax_document_code <>'' order by order_id desc  LIMIT 0,1000";
    $invoicesChargedRes = mysqli_query($con,$invoicesChargedQry);
    $invoicesChargedData = mysqli_fetch_array($invoicesChargedRes,MYSQL_ASSOC);
    $invoicesChargedCount = $invoicesChargedData['Count'];

    $totalInvoicesQry = "SELECT COUNT(*) AS 'Count' FROM ".$tablePrefix."orders where from_unixtime(`timestamp`) BETWEEN '".$oneYearBack."' and NOW() and s_state='".$addressData['s_state']."' and s_country='".$addressData['s_country']."' LIMIT 0,1000";
    $totalInvoicesRes = mysqli_query($con,$totalInvoicesQry);
    $totalInvoicesData = mysqli_fetch_array($totalInvoicesRes,MYSQL_ASSOC);
        
    $totalInvoicesCount = $totalInvoicesData["Count"];
       
    //To calculate avergae of tax calculated invoices
    $avgCount = ($invoicesChargedCount * 100) / $totalInvoicesCount;
    
    $xml.="<PreviousCustomerLocation>
            <Country>".trim($addressData['s_country'])."</Country>
            <States>".trim($addressData['s_state'])."</States>
            <InvoicesCharged>".round($avgCount,0)."</InvoicesCharged>
            <TotalInvoices>".$totalInvoicesCount."</TotalInvoices>
        </PreviousCustomerLocation>";
}
$xml.="</PreviousCustomerLocations></Nexus>";
//Check Tax exempt user count
$exemptCustQry = "SELECT tax_exempt, COUNT(*) as 'Count' FROM ".$tablePrefix."users where user_type='C' GROUP BY tax_exempt";
$exemptCustRes = mysqli_query($con,$exemptCustQry);
$totalCustCnt = 0;
$exemptCustCnt = 0;
while($exemptCustData=mysqli_fetch_array($exemptCustRes,MYSQLI_ASSOC))
{
    if($exemptCustData['tax_exempt']=='Y')
        $exemptCustCnt=$exemptCustData['Count'];
    
    $totalCustCnt=$totalCustCnt+$exemptCustData['Count'];
}
$xml.="<AvaERPSettings>
    <TaxSchedule>
      <IsTaxScheduleMapped>true</IsTaxScheduleMapped>
      <TaxScheduleID />
    </TaxSchedule>
    <MapCustomer>
      <IsCustomerMappedToAvaTax>true</IsCustomerMappedToAvaTax>
      <MappedCustomers />
      <ExemptCustomers>
        <Total>".$totalCustCnt."</Total>
        <Exempted>".$exemptCustCnt."</Exempted>
        <Customers>";

//Check Tax exempt user details
if($exemptCustCnt>0)
{
    $exemptCustQry="SELECT firstname,lastname,user_id FROM ".$tablePrefix."users where user_type='C' and tax_exempt='Y'";
    $exemptCustRes=mysqli_query($con,$exemptCustQry);
    while($exemptCustData=mysqli_fetch_array($exemptCustRes,MYSQLI_ASSOC))
    {
          $xml.="<EntityNameCode>
            <Name>Enabled</Name>
            <Code>".$exemptCustData['user_id']."</Code>
          </EntityNameCode>";
    }
}
else
{
    $xml.="<EntityNameCode>
          <Name></Name>
          <Code></Code>
          </EntityNameCode>";
}
//check for products 
$productQry = "select ".$tablePrefix."products.product_code,".$tablePrefix."product_descriptions.product FROM ".$tablePrefix."products inner join ".$tablePrefix."product_descriptions on ".$tablePrefix."products.product_id=".$tablePrefix."product_descriptions.product_id";
$prodRes = mysqli_query($con,$productQry);
$totalProdCnt = 0;
$productArr = array();
while($productData=mysqli_fetch_array($prodRes,MYSQLI_ASSOC))
{
    $productArr[]=$productData;
}
$totalProdCnt=count($productArr);

//check for non taxable items 
$ntProductQry="select ".$tablePrefix."products.product_code,".$tablePrefix."product_descriptions.product FROM ".$tablePrefix."products inner join ".$tablePrefix."product_descriptions on ".$tablePrefix."products.product_id=".$tablePrefix."product_descriptions.product_id where ".$tablePrefix."products.tax_code='NT'";
$ntProdRes=mysqli_query($con,$ntProductQry);
$totalNTProdCnt=0;
$ntproductArr=array();
while($ntproductData=mysqli_fetch_array($ntProdRes,MYSQLI_ASSOC))
{
    $ntproductArr[]=$ntproductData;
}
$totalNTProdCnt=count($ntproductArr);

$xml.="</Customers>
      </ExemptCustomers>
    </MapCustomer>
    <MapItemCodes>
      <MappedItemsCount>".$totalProdCnt."</MappedItemsCount>
      <MappedItems>";
          if($totalProdCnt>0)
          {
              for($cnt=0;$cnt<$totalProdCnt;$cnt++)
              {
                 $xml .="<EntityNameCode>
                           <Name>".trim(htmlentities($productArr[$cnt]['product']))."</Name>
                           <Code>".trim(htmlentities($productArr[$cnt]['product_code']))."</Code>
                         </EntityNameCode>"; 
              }
          }else{    
        $xml .="<EntityNameCode>
                    <Name></Name>
                    <Code></Code>
                </EntityNameCode>";
          }      
     $xml .="</MappedItems>
      <NonTaxableItems>
        <Total>".$totalNTProdCnt."</Total>
        <NonTaxable>".$totalNTProdCnt."</NonTaxable>
        <Items>";
           if($totalNTProdCnt > 0){
            for($ntcnt=0;$ntcnt<$totalNTProdCnt;$ntcnt++)
              {
                 $xml .="<EntityNameCode>
            <Name>".trim(htmlentities($ntproductArr[$ntcnt]['product']))."</Name>
            <Code>".trim(htmlentities($ntproductArr[$ntcnt]['product_code']))."</Code>
                         </EntityNameCode>"; 
              }
            }
          else{    
        $xml .="<EntityNameCode>
                            <Name></Name>
                            <Code></Code>
                        </EntityNameCode>";
          } 
        $xml .="  </Items>
      </NonTaxableItems>
    </MapItemCodes>
    <AddressValidation>
      <IsAddressValidationEnabled>true</IsAddressValidationEnabled>
      <CountryNamesMapped>false</CountryNamesMapped>
      <MappedCountries>
        <MappedCountry>
          <ERPCountry></ERPCountry>
          <AvaCountry></AvaCountry>
        </MappedCountry>
        <MappedCountry>
          <ERPCountry></ERPCountry>
          <AvaCountry></AvaCountry>
        </MappedCountry>
      </MappedCountries>
    </AddressValidation>
  </AvaERPSettings>
  <HelpLink>
  </HelpLink>
</TPA>";
//echo $xml;
//exit;
//Pass XML data to API through file method
//$url = 'https://avataxprofileassistant.connectorsqa.avatax.com/TaxProfileAssistant/Post';
$url = 'https://avataxprofileassistant.com/TaxProfileAssistant/Post'; 

$ch = curl_init($url);
//curl_setopt($ch, CURLOPT_MUTE, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/xml'));
curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$output = curl_exec($ch);

if(curl_errno($ch))
{
    echo "In error";
    print curl_error($ch);
    curl_close($ch);
}
else
{
    curl_close($ch);
    echo json_encode($output);
    //echo "<script>window.location='$output';</script>";
    //echo "In success";
}
?>