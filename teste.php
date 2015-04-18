<?php 
	require_once ("classes/usuarios.class.php");
	$usuario = new usuarios();

	//$usuario->setValor('nome','Thiago');
	//$usuario->setValor('sobrenome','Machado');
	//$usuario->setValor('email','thiago.delgiudice@gmail.com');
	$usuario->valorPk = 1;

	//$usuario->inserir($usuario);
	//$usuario->atualizar($usuario);
	//$usuario->deletar($usuario);
	//$usuario->extras_select = "order by id DESC";
	
	$usuario->selecionaTudo($usuario);
	//$usuario->selecionaCampos($usuario);
	while ($res = $usuario->retornaDados()):
		echo $res->id .' | '. $res->nome .' | '. $res->sobrenome .' | '. $res->email .'<br/>';
	endwhile;

	echo '<hr/>';
	echo '<pre>';
	print_r($usuario);
	echo '</pre>';

?>