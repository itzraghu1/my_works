<?php  
ini_set('display_errors', 1);  
$requestXML13 = '<ENVELOPE>  
<HEADER>  
<TALLYREQUEST>Export Data</TALLYREQUEST>  
</HEADER>  
<BODY>  
<EXPORTDATA>  
<REQUESTDESC>  
<REPORTNAME>List of Accounts</REPORTNAME>     
<STATICVARIABLES>  
<SVEXPORTFORMAT>$$SysName:XML</SVEXPORTFORMAT>     
</STATICVARIABLES>  
</REQUESTDESC>  
</EXPORTDATA>  
</BODY>  
</ENVELOPE>';  
$requestXML = '<ENVELOPE>  
<HEADER>  
<VERSION>1</VERSION>  
<TALLYREQUEST>Export</TALLYREQUEST>  
<TYPE>Data</TYPE>  
<ID>Balance Sheet</ID>  
</HEADER>  
<BODY>  
<DESC>  
<STATICVARIABLES>  
<EXPLODEFLAG>Yes</EXPLODEFLAG>  
<SVEXPORTFORMAT>$$SysName:XML</SVEXPORTFORMAT>  
</STATICVARIABLES>  
</DESC>  
</BODY>  
</ENVELOPE>';  
$requestXML1 = '<ENVELOPE>  
<HEADER>  
<VERSION>1</VERSION>  
<TALLYREQUEST>EXPORT</TALLYREQUEST>  
<TYPE>COLLECTION</TYPE>  
<ID>Remote Ledger Coll</ID>  
</HEADER>  
<BODY>  
<DESC>  
<STATICVARIABLES>  
<SVEXPORTFORMAT>$$SysName:XML</SVEXPORTFORMAT>  
</STATICVARIABLES>  
<TDL>  
<TDLMESSAGE>  
<COLLECTION NAME="Remote Ledger Coll"  
ISINITIALIZE="Yes">  
<TYPE>Ledger</TYPE>  
<NATIVEMETHOD>Name</NATIVEMETHOD>  
<NATIVEMETHOD>OpeningBalance  
</NATIVEMETHOD>  
</COLLECTION>  
</TDLMESSAGE>  
</TDL>  
</DESC>  
</BODY>  
</ENVELOPE>';  
$arr1 = array();  
$DSPDISPNAME = '';  
$BSSUBAMT = '';  
$BSMAINAMT = '';  
$server = 'http://localhost:9999';  
     //$server='http://192.168.1.220:9000';  
$headers = array("Content-type: text/xml;charset=UTF-8", "Content-length: " . strlen($requestXML), "Connection: close");  
$ch = curl_init();  
curl_setopt($ch, CURLOPT_URL, $server);  
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  
curl_setopt($ch, CURLOPT_TIMEOUT, 100);  
curl_setopt($ch, CURLOPT_POST, true);  
curl_setopt($ch, CURLOPT_POSTFIELDS, $requestXML);  
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);  
$data = curl_exec($ch);  
if (curl_errno($ch)) {  
	print curl_error($ch);  
} else {  
	/* My Code */  
	$p = xml_parser_create();  
	xml_parse_into_struct($p, $data, $vals, $index);  
	xml_parser_free($p);  
	$val_length = count($vals);  
	$i = 0;  
	$f1 = 0;  
	$f2 = 0;  
	$f3 = 0;  
	while ($val_length > $i) {  
		if ($vals[$i]['tag'] == 'DSPDISPNAME') {  
			$f1 = 1;  
			foreach ($vals[$i] as $key => $value) {  
				if ($key == 'value') {  
					$DSPDISPNAME = '';  
					if($value != '')  
					{  
						$DSPDISPNAME = $value;  
					}  
				}  
				else {  
					$DSPDISPNAME = '';  
				}  
			}  
		}  
		else if ($vals[$i]['tag'] == 'BSSUBAMT') {  
			$f2 = 1;  
			foreach ($vals[$i] as $key => $value1) {  
				if ($key == 'value') {  
					$BSSUBAMT = '';  
					if($value1 != ''){  
						$BSSUBAMT = $value1;  
					}  
				}  
				else {  
					$BSSUBAMT = '';  
				}  
               //$BSSUBAMT = '';  
			}  
		}  
		else if ($vals[$i]['tag'] == 'BSMAINAMT') {  
			$f3 = 1;  
			foreach ($vals[$i] as $key => $value2) {  
				if ($key == 'value') {  
					$BSMAINAMT = '';  
					if($value2 != ''){  
						$BSMAINAMT = $value2;  
					}  
				}  
				else {  
					$BSMAINAMT = '';  
				}  
			}  
		}  
		if ($f1 == 1 && $f2 == 1 && $f3 == 1) {         
			array_push($arr1,array('DSPDISPNAME' =>$DSPDISPNAME ,'BSSUAMT' => $BSSUBAMT,'BSMAINAMT'=>$BSMAINAMT));  
			$f1=0;  
			$f2=0;  
			$f3=0;  
		}  
		$i++;  
	}  
	/* end My Code */ 
	echo "<pre>"; 
	print_r($arr1);

	curl_close($ch);  
}
?> 