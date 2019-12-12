<?php    
if(count($_POST)) {
	
	$unit_name = $_POST['unit_name'];
	$requestXML = '<?xml version="1.0"?>
	<ENVELOPE>
	<HEADER>
	<TALLYREQUEST>Import Data</TALLYREQUEST>
	</HEADER>
	<BODY>
	<IMPORTDATA>
	<REQUESTDESC>
	<REPORTNAME>All Masters</REPORTNAME>
	</REQUESTDESC>
	<REQUESTDATA>

	<TALLYMESSAGE xmlns:UDF="TallyUDF">
	<UNIT NAME="'.$unit_name.'" ACTION="CREATE">
	<NAME>'.$unit_name.'</NAME>
	<ISSIMPLEUNIT>Yes</ISSIMPLEUNIT>
	<FORPAYROLL>No</FORPAYROLL>
	</UNIT>
	</TALLYMESSAGE>

	</REQUESTDATA>
	</IMPORTDATA>
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

	if(curl_errno($ch)){
		print curl_error($ch);
		var_dump($data);
	} else {
		$msg = 'Data successfully inserted into tally.';
	}
	curl_close($ch);


}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Tally</title>

	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>

	
	<div class="container">

		<div class="col-md-6 col-md-offset-3" style="margin-top:20px">
			<?php if(isset($msg) && $msg) :?>
				<div class="alert alert-success" role="alert"><?=$msg?></div>
			<?php endif;?>
			<h3>Insert data into Tally <div class="pull-right">
				<a href="export-stock-item.php">Item List</a></div>
			</h3>
			<hr>
			
			<form class="form-horizontal" method="post" action="">
				<div class="form-group">
					<label for="unit-name" class="col-sm-4 control-label">Unit Name</label>
					<div class="col-sm-6">
						<input type="text" class="form-control" id="unit-name" placeholder="Unit name" name="unit_name" required>
					</div>
				</div>
				


				<div class="form-group">
					<div class="col-sm-offset-4 col-sm-6">
						<button type="submit" class="btn btn-primary">Insert</button>
					</div>
				</div>
			</form>
		</div>
	</div>

	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<!-- Include all compiled plugins (below), or include individual files as needed -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>
</html>