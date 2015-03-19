<?php

require_once 'includes/address_data_store.php';

$AddressDataStore = new AddressDataStore('data/address_book.csv');

$addressBook = $AddressDataStore->read();



if (!empty($_POST)) {
	$addressBook[] = $_POST;
	$AddressDataStore->write($addressBook);
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



<!doctype html>
<html>
<head lang="en">
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	
	<title>Address Book</title> 
	<meta name="description" content="BlackTie.co - Free Handsome Bootstrap Themes" />	    
	<meta name="keywords" content="themes, bootstrap, free, templates, bootstrap 3, freebie,">
	<meta property="og:title" content="">

	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="fancybox/jquery.fancybox-v=2.1.5.css" type="text/css" media="screen">
    <link rel="stylesheet" href="css/font-awesome.min.css" rel="stylesheet">
	
	<link rel="stylesheet" type="text/css" href="css/style.css">	
	
	<link href='http://fonts.googleapis.com/css?family=Titillium+Web:400,600,300,200&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
	
	
	<link rel="prefetch" href="images/zoom.png">

	<style type="text/css">

	.table {

		background-color: rgba(235, 235, 235, 0.5);

	}

	.navbar {

		background-color: rgba(235,235,235, 1.0);
	}

	input {

		margin: auto;
	}

	.btn-default {

		background-color: white;
		color: grey;
	}

	</style>
		
</head>

<body>
	<div class="navbar navbar-fixed-top" data-activeslide="1">
		<div class="container">
		
			<!-- .navbar-toggle is used as the toggle for collapsed navbar content -->
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-responsive-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			
			
			<div class="nav-collapse collapse navbar-responsive-collapse">
				<ul class="nav row">
					<li data-slide="1" class="col-12 col-sm-2"><a id="menu-link-1" href="#slide-1" title="Next Section"><span class="icon icon-home"></span> <span class="text">HOME</span></a></li>
				</ul>
				<div class="row">
				</div>
			</div><!-- /.nav-collapse -->
		</div><!-- /.container -->
	</div><!-- /.navbar -->

	
	<!-- === MAIN Background === -->
	<div class="slide story" id="slide-1" data-slide="1">
		<div class="container">
			<div id="home-row-1" class="row">
				<div class="col-12">
					<h1 class="font-semibold">ADDRESS BOOK <span class="font-thin">PROJECT</span></h1>

						<table class="table table-bordered">
							<tr>

								<th>Address</th>
								<th>City</th>
								<th>State</th>
								<th>Zip</th>
								<th></th>
								
							</tr>
									
							<?php foreach ($addressBook as $key => $entry): ?>
								<tr>
									<?php foreach ($entry as $value): ?>
										<td><?= $value ?></td>
									<?php endforeach ?>
										<td><a target="_blank" href="/address_book/index.php?remove=<?= $key ?>"><button class="btn btn-default">Delete</button></a></td>
								
								</tr>
							<? endforeach; ?>

						</table>

						<form method="POST" action="index.php">
			 

							<input type="text" name="address" placeholder="Address">

							<input type="text" name="city" placeholder="City">
	
							<input type="text" name="state" placeholder="State">

							<input type="text" name="Zip" placeholder="Zip">

							<input id="addNew" name="addNew" type="button" class="btn btn-default" value="Submit"></input>
							
							<!-- <button class="btn btn-default" value="submit">Submit</button> -->
	
			 
						</form>

					<br>
					<div class="container">
						<div class="row">
							<div class="col-md-6 col-md-offset-3">
							<h1><span class="font-thin">Upload File</span></h1>

								<? if (isset($savedFilename)): ?>

									<p>You can download your file <a href="/address_book/uploads/<?= $filename ?>">here</a>.</p>

								<? endif; ?>


							    <form method="POST" enctype="multipart/form-data" action="/address_book/index.php">
							        <p>
							            <label for="file1">File to upload: </label>
							           
							          		<input type="file" id="file1" name="file1">
							             
							        </p>
							        <p>
							            <input class="btn btn-default" type="submit" value="Upload">
							        </p>
							    </form>
							</div>
						</div>
					</div>
						<br>
					</div>
				</div><!-- /col-12 -->
			</div><!-- /row -->
			<div class="container">
				<div id="home-row-1" class="row clearfix">
					<div class="col-md-4 col-centered">
						
					</div>
				</div>
			</div>
			<div id="home-row-2" class="row clearfix">
				<div class="col-12 col-sm-4">
				<div class="col-12 col-sm-4">
				<div class="col-12 col-sm-4">
			</div><!-- /row -->
		</div><!-- /container -->
	</div><!-- /slide1 -->
	

</body>


</html>