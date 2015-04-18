<?php 
	abstract class banco {
		//propriedades
		public $servidor		= "localhost";
		public $usuario			= "root";
		public $senha			= "";
		public $nomeBanco		= "thiagodel";
		public $conexao			= null;
		public $dataSet			= null;
		public $linhasAfetadas	= -1;

		//metodos
		public function __construct() {
			$this->conecta();
		}//construct
		public function __destruc() {
			if($this->conexao != NULL) :
				mysql_close($this->conexao);
			endif;
		}//destruct

		public function conecta() {
			$this->conexao = mysql_connect($this->servidor,$this->usuario,$this->senha,TRUE) 
			or die($this->trataErro(__FILE__,__FUNCTION__,mysql_errno(),mysql_error(),TRUE));
			mysql_select_db($this->nomeBanco) or die($this->trataErro(__FILE__,__FUNCTION__,mysql_errno(),mysql_error(),TRUE));
			mysql_query("SET NAMES 'utf8'");
			mysql_query("SET character_set_connection=utf8");
			mysql_query("SET character_set_client=utf8");
			mysql_query("SET character_set_results=utf8");
			//Usada somente para teste do metodo conecta:: echo "Metodo conecta foi chamado com sucesso";
		}//conecta

		public function inserir($objeto){
			//insert into nomedatabela (campo1, campo2) values(valor1, valor2)
			$sql = "INSERT INTO ".$objeto->tabela." (";
			for($i=0; $i<count($objeto->campos_valores); $i++):
				$sql .= key($objeto->campos_valores);
				if($i < (count($objeto->campos_valores)-1)):
					$sql .= ", ";
				else:
					$sql .= ") ";
				endif;
				next($objeto->campos_valores);
			endfor;
			reset($objeto->campos_valores);
			$sql .= "VALUES (";
			for($i=0; $i<count($objeto->campos_valores); $i++):
				$sql .= is_numeric($objeto->campos_valores[key($objeto->campos_valores)]) ?
					$objeto->campos_valores[key($objeto->campos_valores)] :
					"'".$objeto->campos_valores[key($objeto->campos_valores)]."'";
				if($i < (count($objeto->campos_valores)-1)):
					$sql .= ", ";
				else:
					$sql .= ") ";
				endif;
				next($objeto->campos_valores);
			endfor;	
			return $this->executaSQL($sql);
		}//inserir
		public function atualizar($objeto){
			//update nometabela set campo1=valor1, campo2=valor2 where campochave=valorchave
			$sql = "UPDATE ".$objeto->tabela." SET ";
			for($i=0; $i<count($objeto->campos_valores); $i++):
				$sql .= key($objeto->campos_valores)."=";
				$sql .= is_numeric($objeto->campos_valores[key($objeto->campos_valores)]) ?
					$objeto->campos_valores[key($objeto->campos_valores)] :
					"'".$objeto->campos_valores[key($objeto->campos_valores)]."'";
				if($i < (count($objeto->campos_valores)-1)):
					$sql .= ", ";
				else:
					$sql .= ") ";
				endif;
				next($objeto->campos_valores);
			endfor;
			$sql .= "WHERE ".$objeto->campoPk."=";
			$sql .= is_numeric($objeto->valorPk) ? $objeto->valorPk : "'".$objeto->valorPk."'";
			return $this->executaSQL($sql);
		}//atualizar
		public function deletar($objeto){
			//delete from tabela where campoPk=valorPk
			$sql = "DELETE FROM ".$objeto->tabela;
			$sql .= " WHERE ".$objeto->campoPk."=";
			$sql .= is_numeric($objeto->valorPk) ? $objeto->valorPk : "'".$objeto->valorPk."'";
			return $this->executaSQL($sql);
		}//deletar
		public function selecionaTudo($objeto){
			$sql = "SELECT * FROM ".$objeto->tabela;
			if($objeto->extras_select!=NULL):
				$sql .= " ".$objeto->extras_select;
			endif;
			return $this->executaSQL($sql);
		}//selecionaTudo
		public function selecionaCampos($objeto){
			$sql = "SELECT ";
			for($i=0; $i<count($objeto->campos_valores); $i++):
				$sql .= key($objeto->campos_valores);
				if($i < (count($objeto->campos_valores)-1)):
					$sql .= ", ";
				else:
					$sql .= " ";
				endif;
				next($objeto->campos_valores);
			endfor;

			$sql .= " FROM ".$objeto->tabela;
			if($objeto->extras_select!=NULL):
				$sql .= " ".$objeto->extras_select;
			endif;
			return $this->executaSQL($sql);
		}//selecionaCampos
		public function executaSQL($sql=NULL){
			if($sql!=NULL):
				$query = mysql_query($sql) or $this->trataErro(__FILE__,__FUNCTION__);
				$this->linhasAfetadas = mysql_affected_rows($this->conexao);
				if(substr(trim(strtolower($sql)),0,6)=='select'):
					$this->dataSet = $query;
					return $query;
				else:
					return $this->linhasAfetadas;
				endif;
			else:
				$this->trataErro(__FILE__,__FUNCTION__,NULL,'Comando SQL nao informado na rotina',FALSE);
			endif;
		}//executaSQL
		public function retornaDados($tipo=NULL){
			switch (strtolower($tipo)):
				case "array":
					return mysql_fetch_array($this->dataSet);
					break;
				case "assoc":
					return mysql_fetch_assoc($this->dataSet);
					break;
				case "object":
					return mysql_fetch_object($this->dataSet);
					break;
				default:
					return mysql_fetch_object($this->dataSet);
					break;
			endswitch;
		}//retonaDados
		public function trataErro ($arquivo=NULL,$rotina=NULL,$numErro=NULL,$msgErro=NULL,$geraExcept=FALSE) {
			if($arquivo==NULL) $arquivo="nao informado";
			if($rotina==NULL) $rotina="nao informada";
			if($numErro==NULL) $numErro=mysql_errno($this->conexao);
			if($msgErro==NULL) $msgErro=mysql_error($this->conexao);		
			$resultado = '<br/> <strong>Importante,</strong> veja abaixo o detalhamento do erro: <br/><br/>
							<strong>Arquivo:</strong> '.$arquivo.'<br/>
							<strong>Rotina:</strong> '.$rotina.'<br/>
							<strong>Codigo:</strong> '.$numErro.'<br/>
							<strong>Mensagem:</strong> '.$msgErro;
			if($geraExcept==FALSE):
				echo ($resultado);
			else:
				die($resultado);
			endif;
		}//trataErro

	} // fim da classse banco

?>