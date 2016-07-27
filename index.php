<?php

	function getURL()
	{
		// check for https
		$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? 'https' : 'http';

		// return full URL
		return $protocol . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	}

	$url = getURL();

?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>web service tester</title>
		<link href="css/style.css" rel="stylesheet">
	</head>

	<body>
		<div class="container">
			<h1>web service tester</h1>
			<br>

			<div class="row">
				<div class="col-lg-4">
					<p><span class="glyphicon glyphicon-info-sign"></span> Some example URL calls to the web service</p>

					<div class="list-group">
						<a class="list-group-item" target="_blank" href="<?=$url;?>webservice.php?requesttype=textsearch&query=burritos in berlin">burritos in Berlin</a>
						<a class="list-group-item" target="_blank" href="<?=$url;?>webservice.php?requesttype=textsearch&query=ramen in Tokyo">ramen in Tokyo</a>
						<a class="list-group-item" target="_blank" href="<?=$url;?>webservice.php?requesttype=autocomplete&query=Schlesische Strasse 27C">Schlesische Strasse 27C</a>
						<a class="list-group-item" target="_blank" href="<?=$url;?>webservice.php?requesttype=autocomplete&query=Paris">Paris</a>
						<a class="list-group-item" target="_blank" href="<?=$url;?>webservice.php?requesttype=autocomplete&query=Gandalf">Gandalf</a>
					</div>
				</div>
				<div class="col-lg-6 col-lg-offset-1">
					<form role="search">
						<div class="form-group">
							<div class="input-group">
								<input type="search" class="form-control" id="search-box" placeholder="Type here your search">
								<div class="input-group-btn">
									<button class="btn btn-info" type="submit"><span class="glyphicon glyphicon-search"></span></button>
								</div>
							</div>
						</div>
					</form>

					<p><span class="glyphicon glyphicon-info-sign"></span> Input box performs autocomplete requests to the web service with a minimum 3 letter input via jQuery UI's autocomplete widget.</p>
					<p><span class="glyphicon glyphicon-info-sign"></span> On submit, it will perform a textsearch request to the web service with the given input, displaying the received json data.</p>
				</div>
				<div id="response" class="col-lg-12">
					<h3>Response</h3>
				</div>
			</div>
		</div>

		<script type="text/javascript" src="js/jquery-2.1.0.min.js"></script>
		<script type="text/javascript" src="js/jquery-ui-1.10.4.custom.min.js"></script>
		<script type="text/javascript" src="js/bootstrap.min.js"></script>
		<script type="text/javascript" src="js/code.js"></script>
	</body>
</html>