<?php 

	require('http.php');
	require('oauth_client.php');

	$client = new oauth_client_class;
	$client->debug=false;
	$client->debug_http = true;
	$client->server = 'Twitter';
	$client->redirect_uri = 'http://'.$_SERVER['HTTP_HOST'].dirname(strtok($_SERVER['REQUEST_URI'], '?')).'/test.php';

	if(defined('OAUTH_PIN'))
		$client->pin = OAUTH_PIN;
	
	//Supongo que aqui hay que meter las claves.
	$client->client_id = 'dQqkE0zoJaJKNZywzdviEATIw'; $application_line = __LINE__;
	$client->client_secret='W1CscmJAoCkim2IU57LK3wlkbOGJxMyUTnlj7NO5h1l88BYeIc';

	if(strlen($client->client_id) == 0 || strlen($client->client_secret) == 0)
		die('Please go to Twitter Apps page https://dev.twitter.com/apps/new , '.
			'create an application, and in the line '.$application_line.
			' set the client_id to Consumer key and client_secret with Consumer secret. '.
			'The Callback URL must be '.$client->redirect_uri.' If you want to post to '.
			'the user timeline, make sure the application you create has write permissions');
	if(($success = $client->Initialize()))
	{
		if(($success = $client->Process()))
		{
			if(strlen($client->access_token))
			{
				$success = $client->CallAPI(
					'https://api.twitter.com/1.1/account/verify_credentials.json', 'GET', array(), array('FailOnAccessError'=>true), $user);
			}
		}
		$success = $client->Finalize($success);
	}
	if($client->exit)
		exit;
	if($success)
	{
?>

<!DOCTYPE html>
<html>
<head>
	<title>Test</title>
	<style type="text/css">
		body{
			background-color: #f1f1f1;
    		font: 11px/1.5em Arial,Helvetica,sans-serif;
    		font-style: normal;
    		font-variant-ligatures: normal;
    		font-variant-caps: normal;
    		font-variant-numeric: normal;
    		font-weight: normal;
    		font-stretch: normal;
    		font-size: 11px;
    		line-height: 1.5em;
    		font-family: Arial, Helvetica, sans-serif;
		}
		.header-container{
			position:absolute;
			left:0; right:0;
			height: 92px;
			background-color:  #D7E7F4;
			position: fixed;
			text-align: center;
			vertical-align: text-bottom;
			font-size: 20px;
		}

		.sidenav{
			position: absolute;
			left:0; top:92px; bottom: 0;
			width: 178px;
			background: -webkit-linear-gradient(#F8FE83, #E2E1C3);
  			background: -moz-linear-gradient(#F8FE83, #E2E1C3);
			background: -o-linear-gradient(#F8FE83, #E2E1C3);
		}
		.main{
			position: absolute;
			left: 178px; top:92px; right: 0; bottom: 0;
			background-color: #f1f1f1;
		}
	</style>
</head>
<body>
	<div class="header-container">
		<p class="header">Bienvenido al Aula Virtual de GISAI,<?php echo $_POST["uname"];?></p>
		
	</div>
	<div class="sidenav">hi</div>
	<div class="main">hey
	<?php 
	?>
	
	</div>
</body>
</html>