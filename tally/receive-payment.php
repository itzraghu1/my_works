<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
</head>
<body>
	
	<form action="" method="post">
		<table width="100%" border="1">
			<tr>
				<th scope="col">Name</th>
				<td><input name="nm" id="nm" type="text" /></td>
			</tr>
			<tr>
				<td colspan="4"><div align="center">
					<label>
						<input type="submit" name="Submit" value="Submit" />
					</label>
				</div></td>
			</tr>
		</table>

	</form>

</body>
</html>

<?php

if(isset($_POST['Submit']))
{
	$name = $_POST['nm'];
	$xml_str = ""
	. "<ENVELOPE>"
	. "<HEADER><TALLYREQUEST>Import Data</TALLYREQUEST></HEADER>"
	. "<BODY>"
	. "<IMPORTDATA>"
	. "<REQUESTDESC><REPORTNAME>Vouchers</REPORTNAME><STATICVARIABLES><SVCURRENTCOMPANY>Logimetrix Techsolutions Private Limited</SVCURRENTCOMPANY></STATICVARIABLES></REQUESTDESC>"
	. "<REQUESTDATA>"
	. "<TALLYMESSAGE xmlns:UDF=\"TallyUDF\">"
	. "<VOUCHER REMOTEID=\"00000001\" VCHTYPE=\"Receipt\" ACTION=\"Create\" OBJVIEW=\"Accounting Voucher View\">"    
	. "<DATE>20190401</DATE>"
	. "<VOUCHERTYPENAME>Receipt</VOUCHERTYPENAME>"
	. "<VOUCHERNUMBER>2</VOUCHERNUMBER>"
	. "<PARTYLEDGERNAME>Cash</PARTYLEDGERNAME>"
	. "<PERSISTEDVIEW>Accounting Voucher View</PERSISTEDVIEW>"
	. "<ALLLEDGERENTRIES.LIST>"
	. "<LEDGERNAME>Cash</LEDGERNAME>"
	. "<ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>"
	. "<AMOUNT>50000.00</AMOUNT>"
	. "</ALLLEDGERENTRIES.LIST>"
	. "<ALLLEDGERENTRIES.LIST>"    
	. "<LEDGERNAME>Cash</LEDGERNAME>"
	. "<ISDEEMEDPOSITIVE>Yes</ISDEEMEDPOSITIVE>"
	. "<AMOUNT>-50000.00</AMOUNT>"        
	. "</ALLLEDGERENTRIES.LIST>"  
	. "</VOUCHER>"
	. "</TALLYMESSAGE>"
	. "</REQUESTDATA>"
	. "</IMPORTDATA>"
	. "</BODY>"
	. "</ENVELOPE>";

	/* Actual code for importing goes here */
	$server = 'localhost:9999';
	$headers = array( "Content-type: text/xml" ,"Content-length: ".strlen($xml_str) ,"Connection: close" );

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $server);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_TIMEOUT, 100);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_str);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	$data = curl_exec($ch);

	if(curl_errno($ch)){
		print curl_error($ch);
		echo "  something went wrong..... try later";
	}else{
		echo " request accepted";
		print $data;
		curl_close($ch);
	}
}

?>