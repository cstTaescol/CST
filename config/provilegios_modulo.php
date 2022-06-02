<?php
if ($_SESSION['id_usuario'] != 1)
{
	$resultado=privilegios($id_objeto);
	if ($resultado != true)
	{
		echo "<script>
				alert('ALERTA:No Tiene Privilegios para visualizar este modulo.');
				document.location='base.php';
			</script>";
		exit();
	}
}
?>