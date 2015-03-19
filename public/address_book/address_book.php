<?php

class UnexpectedTypeException extends Exception { }

require_once '../../inc/address_data_store.php';

$AddressDataStore = new AddressDataStore('data/address_book.csv');

$addressBook = $AddressDataStore->read();




if (!empty($_POST)) {
	try { 
		if(strlen($_POST['address']) > 125 || empty($_POST['address'])) {
	    	throw new UnexpectedTypeException ('Must include Address less than 125 characters.');
		}	
		if(strlen($_POST['city']) > 125 || empty($_POST['city'])) {
			throw new UnexpectedTypeException ('Must include City less than 125 characters.');
		}
		if(strlen($_POST['state']) > 125 || empty($_POST['state'])) {
			throw new UnexpectedTypeException ('Must include State less than 125 characters.');
		}
		if (strlen($_POST['zip']) > 125 || empty($_POST['zip'])) {
			throw new UnexpectedTypeException ('Must include Zip less than 125 characters.');
		}

		$addressBook[] = $_POST;
		$AddressDataStore->write($addressBook);

	} catch (UnexpectedTypeException $e) {
		echo $e->getMessage();
	}
}

if (isset($_GET['remove'])) {
	$id = $_GET['remove'];
	unset($addressBook[$id]);
	$AddressDataStore->write($addressBook);
}

if (count($_FILES) > 0 && $_FILES['file1']['error'] == UPLOAD_ERR_OK) {
	    // Set the destination directory for uploads
	    $uploadDir = '/vagrant/sites/planner.dev/public/uploads/';
	    // Grab the filename from the uploaded file by using basename
	    $filename = basename($_FILES['file1']['name']);
	    // Create the saved filename using the file's original name and our upload directory
	    $savedFilename = $uploadDir . $filename;

	    $uploadedAddressData = new AddressDataStore($savedFilename);

	    if(substr($filename, -3) == 'csv') {

		    // Move the file from the temp location to our uploads directory
		    move_uploaded_file($_FILES['file1']['tmp_name'], $savedFilename);

		    $addressBook = array_merge($addressBook, $uploadedAddressData->read());

		    $AddressDataStore->write($addressBook);

			} else {
				echo "There was an error processing your file, please use CSV file.";
			}
	}

?>

<html>
<head>
    <title>Address Book</title>
     <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Fontawesome -->
    <link href="css/font-awesome.min.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style type="text/css">
	table {
	  border-collapse: separate;
	  border-spacing: 0 5px;
	}

	thead th {
	  background-color: #006DCC;
	  color: white;
	}

	tbody td {
	  background-color: #EEEEEE;
	}

	tr td:first-child,
	tr th:first-child {
	  border-top-left-radius: 6px;
	  border-bottom-left-radius: 6px;
	}

	tr td:last-child,
	tr th:last-child {
	  border-top-right-radius: 6px;
	  border-bottom-right-radius: 6px;
	}

	body {
		background-image: url(images/addressbookbackground.jpg);
		opacity: 50%;
		background-color: rgba(128, 128, 128, 0.8); 
	}

	.navbar {

		background-color: #40404C;
	}

	.table-bordered {
		border-color: rgba(128, 128, 128, 0.0); 
	}

	.btn-default2 {
		background-color: #EEE;
		color: grey;
	}

	.btn-default3 {
		background-color: #EEE;
		color: grey;
		border-color: rgba(128, 128, 128, 0.1);
	}

	.btn-default3:hover {
		color: white;

	}

	th {
		color: #3F5467;

	}

	h2 {

		text-align: center;
	}

	.btn-lg {
		display: block;
		margin:0 auto;
		background-color: #40404C;
	}
	
    </style>

</head>
<body>


	<!-- NAVBAR HERE! -->

	<nav class="navbar navbar-default">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="/address_book/address_book.php"><span class="glyphicon glyphicon-book"></span> Address Book</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>

<!-- NAVBAR END! -->


<!-- TABLE BEGIN! -->

	<div class="container">
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<table class="table table-bordered">
					<tr>

						<th>Address</th>
						<th>City</th>
						<th>State</th>
						<th>Zip</th>
						<th> </th>
						
					</tr>

					<? foreach ($addressBook as $key => $entry): ?>
					

						<tr>
							<? foreach ($entry as $value): ?>
								<td><?= $value; ?></td>
							<? endforeach; ?>
								<td><a href="/address_book/address_book.php?remove=<?= $key ?>"><button class="btn btn-default3">Delete</button></a></td>
						
						</tr>

					<? endforeach; ?>

				</table>
			</div>
		</div>
	</div>

<!-- TABLE END! -->

<!-- FORM BEGIN! -->
<div class="container">
	<div class="row">
		<div class="col-md-offset-1">
			<div class="form-group">

				<form name="additem" id="" method="POST" action="address_book.php">
					<div class="col-md-3">	 
						<input type="text" class="form-control" id="address" name="address" placeholder="Address">
					</div>

					<div class="col-md-3">
					<input type="text" class="form-control" id="city" name="city" placeholder="City">
					</div>

					<div class="col-md-2">
					<input type="text" class="form-control" id="state" name="state" placeholder="State">
					</div>

					<div class="col-md-2">
					<input type="text" class="form-control" id="zip" name="zip" placeholder="Zip">
					</div>

					<div class="col-md-2">
					<button type="submit" class="btn btn-default2" id="addNew">Submit</button>
					</div>
						 
				</form>
			</div>
		</div>
		
	</div>
</div>

<!-- FORM END! -->

<!-- UPLOAD BEGIN! -->
<br>
<div class="container">
	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<div class="jumbotron">
			  <h2>Upload File</h2>
			  <p>
			  		<? if (isset($savedFilename)): ?>

							<p>You can download your file <a href="/address_book/uploads/<?= $filename ?>">here</a>.</p>

					<? endif; ?>
			  </p>
			  <p><form method="POST" enctype="multipart/form-data" action="/address_book/address_book.php">
					        
					            
					            <div class="col-md-offset-3"><input class="btn" type="file" id="file1" name="file1"></div>
					        
					        
					    <input class="btn btn-lg" type="submit" value="Upload"></p>
				</form>
			</div>		
		</div>
	</div>
</div>

		   
		

<!-- UPLOAD END! -->


    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>

</body>
</html>

