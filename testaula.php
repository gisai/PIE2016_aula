<?php
	include("mysqlconfig.php");
	include("session.php");
	session_start();

	$sql = sprintf("CALL informacionUsuario('%s')",$login_session);

	echo $sql;

	$cosa = array();
	/*if($result = mysqli_query($db,$sql,MYSQLI_USE_RESULT)){
		

		while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){

		array_push($cosa,$row);
		}	
	}
	
	myqli_free_result($result);*/

	echo '<pre>';
	print_r($cosa);
	echo '</pre>';

	$sql2 = "CALL getAsignaturas();";

	echo $sql2;

	$dataArray = array();
	if($result2 = mysqli_query($db,$sql2,MYSQLI_USE_RESULT)){
		
		
		while($row2 = mysqli_fetch_array($result2,MYSQLI_ASSOC)){
			array_push($dataArray,$row2);		
		}
	}

	mysqli_free_result($result);

	echo '<pre>';
	print_r($dataArray);
	echo '</pre>';

	mysqli_close($db);


?>