<!DOCTYPE html>
<?php
	include "../lib/sessions.php";
	$tipoSession = checkSession();
		if(!$tipoSession){
	    	header('location: index.php');
		} else if ($tipoSession == "admin"){
	    	header('location: ../web/templateMenuAdmin.php');
		}
		
?>
<html>
<head>
	<title>Menu</title>
	<link rel="stylesheet" href="../css/bootstrap.min.css">
	<link rel="stylesheet" href="../css/bootstrap-theme.min.css">
	<script type="text/javascript">
	function validaPlaca(){
		placa = document.getElementById('placa').value;
		botao = document.getElementById('submit');
		if(placa.match(/[A-Z]{3}-[0-9]{4}/) == null){
			botao.disabled = 1;
		} else {
			botao.disabled = 0;
		}
	}
	</script>
</head>
<body>
	<div class="container">
		<form role="form" method="post" action="../web/entradaVeiculo.php">
		  <div class="form-group">
		    <label for="placa">Placa do carro:</label>
		    <input type="text" class="form-control" name="placa" required onblur="validaPlaca()" id="placa">
		  </div>

		  <button id="submit" type="submit" class="btn btn-default">Submit</button>

		</form>
		<br>
		<a href="../web/templateSituacaoEstacionamento.php">Situacao estacionamento</a>
		<a href="../web/logout.php">Logout</a>
	</div>
</body>
</html>