<?php 
	include "../dao/VeiculoDao.php";
	include "../lib/sessions.php";
    $tipoSession = checkSession();

    if(!$tipoSession){	
        header('location: ../view/index.php');
    } else if ($tipoSession == "funcionario"){
        header('location: ../view/menu.php');
    }

    $veiculoDao = new VeiculoDao();

    $veiculoDao->addVaga($_POST['andar'], $_POST['numero'], $_POST['tipos']);
    header('location: templateMenuAdmin.php');

?>