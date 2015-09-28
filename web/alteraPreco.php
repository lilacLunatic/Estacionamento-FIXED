<?php 
	include "../lib/sessions.php";
    $tipoSession = checkSession();

	if(!$tipoSession){
        header('location: ../view/index.php');
    } else if ($tipoSession == "funcionario"){
        header('location: ../view/menu.php');
    } else{

		include_once "../dao/VeiculoDao.php";
		$veiculoDao = new VeiculoDao();
		$veiculoDao->alteraPreco($_POST['id'], $_POST['preco']);
		header("Location: templateMenuAdmin.php");
	}
?>