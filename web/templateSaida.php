<?php 
    error_reporting(E_ALL);

	
    require_once("../lib/Template.php");
    use raelgc\view\Template;
    include "../dao/VeiculoDao.php";
    include "../lib/sessions.php";
    $tipoSession = checkSession();

    if(!$tipoSession){
        header('location: ../view/index.php');
    } else if ($tipoSession != "funcionario"){
        header('location: templateMenuAdmin.php');
    }
    
    $tpl = new Template("../view/saidaVeiculo.html");
    $veiculoDao = new VeiculoDao();
    $entrada = $veiculoDao->getUltimaSaida($_SESSION['placa']);
        $tpl->PLACA = $entrada[0]['placa_veiculo'];
        $tpl->HORARIO_ENTRADA = $entrada[0]['hora_entrada'];
        $tpl->HORARIO_SAIDA = $entrada[0]['hora_saida'];
        $tpl->VALOR = $entrada[0]['valor'];       
    $tpl->show();
    

 ?>