<?php
$requestXML = '<ENVELOPE>'.
'<HEADER>'.
'<TALLYREQUEST>Export Data</TALLYREQUEST>'.
'</HEADER>'.
'<BODY>'.
'<EXPORTDATA>'.
'<REQUESTDESC>'.
'<REPORTNAME>Daybook</REPORTNAME>'.
'<STATICVARIABLES>'.
'<SVEXPORTFORMAT>$$SysName:XML</SVEXPORTFORMAT>'.
'</STATICVARIABLES>'.
'</REQUESTDESC>'.
'</EXPORTDATA>'.
'</BODY>'.
'</ENVELOPE>';

$serverip = "localhost:9999";
$curl = curl_init($serverip);

curl_setopt($curl, CURLOPT_HTTPHEADER, array('Accept-Charset:UTF-8'));
$contentarray = array("content-type:text/xml;charset:UTF-8");
curl_setopt($curl, CURLOPT_HTTPHEADER, $contentarray);
curl_setopt($curl,CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_HEADER, 0);
curl_setopt($curl, CURLOPT_POST, 1);
curl_setopt($curl, CURLOPT_POSTFIELDS, $requestXML); // post the xml
curl_setopt($curl, CURLOPT_TIMEOUT, 300000); // set timeout in seconds
curl_setopt($curl,CURLOPT_ENCODING,"UTF-8");
$xmlstr = curl_exec ($curl);
if (!$xmlstr) die("No Response from tally: Curl");
// var_dump(curl_getinfo($curl, CURLINFO_CONTENT_TYPE));
curl_close ($curl);
echo ($xmlstr);