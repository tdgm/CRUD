<?php 
	require_once ("base.class.php");
	class usuarios extends base {
		public function __construct($campos=array()){
			parent::__construct();
			$this->tabela = "usuarios";
			if(sizeof($campos)<=0):
				$this->campos_valores = array (
					"nome" => NULL,
					"sobrenome" => NULL,
					"email" => NULL
				);
			else:
				$this->campos_valores = $campos;
			endif;
			$this->campoPk = "id";	
		}//construct
	}//fim classe usuarios
?>