<?php
$activacion="";
if ($_SESSION['id_usuario'] != 1)
{
	$resultado=privilegios($id_objeto);
	if ($resultado != true)
	{
		//Desactivara el boton
		$activacion= 'disabled="disabled"';
	}
}
?>