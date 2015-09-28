<?php 
    ini_set("error_reporting()", "E_ALL");
    error_reporting(E_ALL);
	include "../lib/sessions.php";
    $tipoSession = checkSession();
    if(!$tipoSession){
        header('location: ../view/index.php');
    } else if ($tipoSession != "funcionario"){
        header('location: templateMenuAdmin.php');
    }else{

    $placa = $_POST['placa'];
	include "../dao/VeiculoDao.php";
    $veiculoDao = new VeiculoDao();
    $veiculo = $veiculoDao->getByPlaca($placa);
    $_SESSION['placa'] = $placa; 
    if(!is_null($veiculo)){
        if($veiculoDao->checaEntrada($placa)) {
            $veiculoDao->saidaVeiculo($veiculo);
            header('Location: templateSaida.php');
        }else{

            if($veiculoDao->entradaVeiculo($veiculo)){
                header('Location: templateEntrada.php');
            }else{
                header('Location: ../view/semVagas.html');
            }
        }
    
    }else{
    	header('Location: templateCadastroVeiculo.php');
    }
    }
 ?>