<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
$cont=false;
if(isset($_REQUEST["cc"]))
{
	$cc=$_REQUEST["cc"];
	$nombre=strtoupper($_REQUEST["nombre"]);
	$telefono=strtoupper($_REQUEST["telefono"]);
	$cargo=strtoupper($_REQUEST["cargo"]);
	$login=strtoupper($_REQUEST["login"]);
	$id_aerolinea=$_REQUEST["id_aerolinea"];	
	
	$ncampos=$_REQUEST["ncampos"];	
	for ($i=1; $i<=$ncampos; $i++)
	{
		if(isset($_REQUEST["$i"]))
		{
			$cont=true;
			$objetos[]=$_REQUEST["$i"];
		}
	}
}
if ($cont == false)
{
	echo "<script>
			alert('ERROR: Debe dar permiso a  este USUARIO para acceder a algun OBJETO.');	document.location='usuario_registro2.php?cc=$cc&nombre=$nombre&telefono=$telefono&cargo=$cargo&login=$login&id_aerolinea=$id_aerolinea';
    	</script>";
}
else
{
	$sql="INSERT INTO usuario (cc,nombre,telefono1,cuenta,clave,cargo,id_aerolinea) value ('$cc','$nombre','$telefono','$login','7110eda4d09e062aa5e4a390b0a572ac0d2c0220','$cargo','$id_aerolinea')";
	mysql_query($sql,$conexion) or die (mysql_error());
	$id_usuario = mysql_insert_id($conexion); //obtiene el id de la ultima inserción
	
	$ncampos=count($objetos);
	for ($i = 0; $i < $ncampos; $i++)
	{
		$id_objeto=$objetos[$i];
		$sql="INSERT INTO privilegio (id_usuario,id_objeto) value ('$id_usuario','$id_objeto')";
		mysql_query($sql,$conexion) or die (mysql_error());
	}
	echo "<script>
			alert('Usuario Creado de manera exitosa');
			document.location='base.php';
		</script>";		
}
?>
