<?php
	require_once("../lib/Template.php");
	use raelgc\view\Template;
	include "../dao/VeiculoDao.php";
	include "../lib/sessions.php";
    $tipoSession = checkSession();

    if(!$tipoSession){
        header('location: ../view/index.php');
    } else if ($tipoSession == "funcionario"){
        header('location: ../view/menu.php');
    }

	$tpl = new Template("../view/menuAdmin.html");
    $veiculoDao = new VeiculoDao();
	
	$tabela_precos = $veiculoDao->getPrecos();
	if($tabela_precos){
		foreach ($tabela_precos as $item){
			$tpl->NOME_VAGA = $item['nome'];
			$tpl->VALOR_VAGA = $item['valor'];
			$tpl->ID_PRECO = $item['id'];
			$tpl->block("BLOCK_PRECOS"); 
		}
	}

	$vagas = $veiculoDao->getVagas();
	if($vagas){
	    foreach($vagas as $vaga){
	        $tpl->ANDAR_VAGA= $vaga['andar'];
	        $tpl->NUMERO_VAGA = $vaga['numero'];
	        $tpl->TIPO_VAGA = $vaga['nome'];
	        $tpl->block("BLOCK_VAGAS");
	    }
	}

	$tipos = $veiculoDao->getTipos();
    foreach($tipos as $t){
        $tpl->OPTION = $t['id'];
        $tpl->OPTION_NAME = $t['nome'];
        $tpl->block("BLOCK_OPTIONS");
    }

	$tpl->show();
?>