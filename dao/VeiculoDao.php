<?php
ini_set("error_reporting()", "E_ALL");
error_reporting(E_ALL);

date_default_timezone_set('America/Sao_Paulo');
include_once "../model/Veiculo.php";
include_once "Dao.php";
class VeiculoDao extends Dao{
	public function add($veiculo){
		$query = "insert into veiculo(placa, tipo, marca, modelo, cor) values ($1, $2, $3, $4, $5)";
		
		$params = Array($veiculo->getPlaca(), $veiculo->getTipo(), $veiculo->getMarca(), $veiculo->getModelo(), $veiculo->getCor()) ;

		parent::daoExecuteQuery($query, $params);
	}
	public function addVaga($andar, $numero, $tipo){
		$query = "insert into vaga(andar, numero, tipo_vaga) values ($1, $2, $3)";
		
		$params = Array($andar, $numero, $tipo) ;

		parent::daoExecuteQuery($query, $params);
	}

	public function getByPlaca($placa){
		$query = "select * from veiculo where placa = $1";
		$params = Array($placa);

		$veiculoArray = parent::daoFetchArray($query, $params);
		if(empty($veiculoArray)){
			return null;
		}else{
			$veiculo = new Veiculo(
				$veiculoArray['placa'],
				$veiculoArray['tipo'],
				$veiculoArray['marca'],
				$veiculoArray['modelo'],
				$veiculoArray['cor']);

			return $veiculo;
		}
	}
	public function getTipos(){
		$query = "select * from tipo";
		return parent::daoFetchAll($query);
	}
	public function alteraPreco($id, $valor){
		$query = "update preco set valor = $1 where id = $2";
		$params = Array($valor, $id);
		parent::daoExecuteQuery($query, $params);
	}


	public function getPrecos(){
		$query = "select preco.id, preco.valor, tipo.nome from preco
					join tipo on preco.tipo = tipo.id";
		return parent::daoFetchAll($query);
	}

	public function checaEntrada($placa){
		$query = "select * from entrada where placa_veiculo = $1 and hora_saida is null";
		//$now = date('d/m/Y H:i:s');
		$params = Array($placa);
		$result = parent::daoFetchAll($query, $params);
		if(is_null($result) || empty($result)){
			return false;
		}else{
			return true;
		}
	}

	public function getEntradasAtuais(){
		$query = "select entrada.*, tipo.nome from entrada
					join veiculo on veiculo.placa = entrada.placa_veiculo
					join tipo on veiculo.tipo = tipo.id
				 	where hora_saida is null";
		return parent::daoFetchAll($query);
	}

	public function getPrecoEspecial($param){
		$query = "";
		if($param == "diaria"){
			$query = "select * from preco_especial where nome = 'diaria'";
		} else {
			$query = "select * from preco_especial where nome = 'pernoite'";
		}
		parent::daoFetchAll($query);
	}

	public function getPrecoByTipoVeiculo($tipo){
		$query = "select preco.valor from preco where preco.tipo = $1";
		$params = Array($tipo);
		return parent::daoFetchAll($query, $params);
	}

	public function saidaVeiculo($veiculo){
		$entrada = $this->getEntrada($veiculo->getPlaca());
		$hora_entrada = new DateTime($entrada[0]['hora_entrada']);
		$now = date('d-m-Y H:i:s');
		$doze_horas_atras = new DateTime(date('d-m-Y H:i:s')); //diaria
		$doze_horas_atras->sub(new DateInterval("PT12H"));
		$oito_horas_atras = new DateTime(date('d-m-Y H:i:s')); //pernoite
		$oito_horas_atras->sub(new DateInterval("PT8H"));

		$valor_saida = 0;
		if ($hora_entrada < $doze_horas_atras){
			$valor_saida = $this->getPrecoEspecial("diaria")[0]['valor'];
		} else if ($hora_entrada < $oito_horas_atras){
			$valor_saida = $this->getPrecoEspecial("pernoite")[0]['valor'];
		} else{
			$valor_saida = $this->getPrecoByTipoVeiculo($veiculo->getTipo())[0]['valor'];
			$dtNow = new DateTime($now);
			$diff_hora_entrada = $dtNow->diff($hora_entrada);
			$intervalo_horas = $diff_hora_entrada->h;
			$intervalo_dias = $diff_hora_entrada->d;
			$valor_saida = substr($valor_saida, 3);
			$valor_saida = str_replace(',', '.', $valor_saida);
			$valor_saida = floatval($valor_saida);
			$valor_saida *= $intervalo_horas + 24 * $intervalo_dias + 1;
			$valor_saida = $valor_saida . "";
			$valor_saida = str_replace('.', ',', $valor_saida);
		}

		$query = "update entrada set hora_saida = $1, valor = $2 where placa_veiculo = $3 and hora_saida is null";
		$params = Array($now, $valor_saida, $veiculo->getPlaca());
		parent::daoExecuteQuery($query, $params);
		
	}

	public function entradaVeiculo($veiculo){
		$query = "insert into entrada(hora_entrada,placa_veiculo,andar_vaga,numero_vaga) values($1,$2,$3,$4)";
		$now = date('d-m-Y H:i:s');
		$vagas = $this->getVagasLivresPorTipo($veiculo);
		if(empty($vagas) || is_null($vagas)){
			return false;
		}
		$randomKey = array_rand($vagas);
		$params = Array($now, $veiculo->getPlaca(), $vagas[$randomKey]['andar'], $vagas[$randomKey]['numero']);
		parent::daoExecuteQuery($query, $params);
		return true;
	}

	public function getEntrada($placa){
		$query = "select * from entrada where placa_veiculo = $1 and hora_saida is null";
		$params = Array($placa);
		return parent::daoFetchAll($query, $params);
	}

	public function getUltimaSaida($placa){
		$query = "select * from entrada where placa_veiculo = $1 order by hora_saida desc limit 1";
		$params = Array($placa);
		return parent::daoFetchAll($query, $params);
	}

	public function getVagasLivresPorTipo($veiculo){
		$query = "select * from vaga
				where andar not in (select andar_vaga from entrada where hora_saida is null) and numero not in (select numero_vaga from entrada where hora_saida is null)
				and tipo_vaga = $1";
		$params = Array($veiculo->getTipo()//->getId()
			);
		return parent::daoFetchAll($query, $params);
	}
	public function getVagasLivres(){
		$query = "select vaga.*, tipo.nome from vaga
					join tipo on tipo.id = vaga.tipo_vaga
					where cast(andar as varchar) || cast(numero as varchar) not in (select cast(andar_vaga as varchar) || cast(numero_vaga as varchar) from entrada where hora_saida is null)";
		return parent::daoFetchAll($query);
	}
	public function getVagas(){
		$query = "select vaga.*, tipo.nome from vaga
					join tipo on tipo.id = vaga.tipo_vaga";
		return parent::daoFetchAll($query);
	}

}
/*
$joao = new Funcionario("joao123", "mango", true);

echo $joao->getLogin() . " " . $joao->getSenha() . " " . ($joao->isAdmin() ? 1 : 0) . "\n";

(new FuncionarioDao())->add($joao);*/
?>