<noscript>
<h3>Se requiere JavaScript para utilizar este Portal</h3>
<strong>*&nbsp;Este explorador web no admite JavaScript o las secuencias de comandos est&aacute;n bloqueadas.</strong>
<meta http-equiv="Refresh" content="2;url=index.php">
</noscript>
<?php
//Funcion que redirecciona la pagina hacia cerrar la cesion automaticamente en 10 minutos
//Se aplica por que phpinfo() indica las sesion con duracion máxima de 24 minutos
if (!isset($_SESSION['id_usuario']))
{
	echo "
	<script tipe='JavaScript'>
		alert('Sesion finalizada, Vuelva a iniciarla');
		function actulizar(url){parent.location=url;}javascript:actulizar('index.php');
	</script>";	
	exit;
}
?>
<script language='JavaScript' type='text/javascript'>
var pagina='cerrar_sesion.php';
var tiempo_sesion =  <?php echo TIEMPOSESION; ?>;

function redireccionar()
	{
		parent.location.href=pagina
	} 
setTimeout ('redireccionar()',tiempo_sesion); //tiempo en milisegundos 1000 x 1 segundo
</script>