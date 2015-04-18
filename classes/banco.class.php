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