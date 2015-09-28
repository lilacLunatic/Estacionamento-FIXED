<?php 
	include_once "../dao/VeiculoDao.php";
	include_once "../model/Veiculo.php";
	include "../lib/sessions.php";
    $tipoSession = checkSession();

    if(!$tipoSession){
        header('location: ../view/index.php');
    } else if ($tipoSession != "funcionario"){
        header('location: templateMenuAdmin.php');
    } else{
		$marca = $_POST['marca'];
		$placa = $_POST['placa'];
		$modelo = $_POST['modelo'];
		$cor = $_POST['cor'];
		$tipo = $_POST['tipo'];
		
		
		$_SESSION['placa'] = $placa;

		$veiculo = new Veiculo($placa, $tipo, $marca, $modelo, $cor);
		$veiculoDao = new VeiculoDao();

		$veiculoDao->add($veiculo);

		if($veiculoDao->entradaVeiculo($veiculo)){
	                header('Location: templateEntrada.php');
	            }else{
	                header('Location: ../view/semVagas.html');
	    }
	}
?>