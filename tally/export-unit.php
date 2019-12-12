<?php
$requestXML = '<?xml version="1.0"?>
<ENVELOPE>
<HEADER>
<TALLYREQUEST>Export Data</TALLYREQUEST>
</HEADER>
<BODY>
<EXPORTDATA>
<REQUESTDESC>
<REPORTNAME>Unit of Quantity</REPORTNAME>
</REQUESTDESC>
</EXPORTDATA>
</BODY>
</ENVELOPE>';


$url = "http://localhost:9999/";
        //setting the curl parameters.
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
// Following line is compulsary to add as it is:
curl_setopt($ch, CURLOPT_POSTFIELDS,
	"xmlRequest=" . $requestXML);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300);
$data = curl_exec($ch);
curl_close($ch);
		// get the xml object 
$object = simplexml_load_string( $data );

print_r($data);die;

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
	<title>Tally</title>

	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>

	
	<div class="container">

		<div class="col-md-6 col-md-offset-3" style="margin-top:20px">
			<?php if(isset($msg) && $msg) :?>
				<div class="alert alert-success" role="alert"><?=$msg?></div>
			<?php endif;?>
			
			<h3>Tally Item List <div class="pull-right"><a href="create-unit.php">Add Unit</a></div></h3>
			<hr>
			
			<table class="table table-bodered">
				<tr>
					<th>Item Name</th>
				</tr>
				<?php $i = 0;?>
				<?php foreach($object->DSPACCNAME as $value) :?>
					<tr>
						<td><?=$value->DSPDISPNAME?></td>
					</tr>
					<?php $i++;?>
				<?php endforeach;?>
			</table>

		</div>
	</div>

	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<!-- Include all compiled plugins (below), or include individual files as needed -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>
</html>