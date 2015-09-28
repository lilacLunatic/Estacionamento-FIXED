<!DOCTYPE html>
<?php
	include "../lib/sessions.php";
	$tipoSession = checkSession();
		if($tipoSession == "funcionario"){
	    	header('location: menu.php');
		} else if ($tipoSession == "admin"){
	    	header('location: ../web/templateMenuAdmin.php');
		}
		
?>

<html>
<head>
	<title>Index</title>
	<link rel="stylesheet" href="../css/bootstrap.min.css">
	<link rel="stylesheet" href="../css/bootstrap-theme.min.css">
</head>
<body>
	<div class="container">
		<form role="form" method="post" action="../web/login.php">
		  <div class="form-group">
		    <label for="login">Login:</label>
		    <input type="text" class="form-control" name="login" id="name" required>
		  </div>

		  <div class="form-group">
		    <label for="pwd">Senha:</label>
		    <input type="password" name="senha" class="form-control" id="pwd" required>
		  </div>
		  <div class="checkbox">
		    <label><input type="checkbox" name="admin"> Admin</label>
		  </div>

		  <button type="submit" class="btn btn-default">Submit</button>

		</form>
	</div>
</html>