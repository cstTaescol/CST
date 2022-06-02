<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
$cont=false;
if(isset($_REQUEST["cc"]))$cc=strtoupper($_REQUEST["cc"]);
if(isset($_REQUEST["nombre"]))$nombre=strtoupper($_REQUEST["nombre"]);
if(isset($_REQUEST["telefono"]))$telefono=strtoupper($_REQUEST["telefono"]);
if(isset($_REQUEST["cargo"]))$cargo=strtoupper($_REQUEST["cargo"]);
if(isset($_REQUEST["login"]))$login=strtoupper($_REQUEST["login"]);
if(isset($_REQUEST["id_aerolinea"]))$id_aerolinea=$_REQUEST["id_aerolinea"];
if(isset($_REQUEST["ncampos"]))$ncampos=$_REQUEST["ncampos"];
if(isset($_REQUEST["id_usuario_modificar"]))$id_usuario_modificar=$_REQUEST["id_usuario_modificar"];
$ncampos=$_REQUEST["ncampos"];	
for ($i=1; $i<=$ncampos; $i++)
{
	if(isset($_REQUEST["$i"]))
	{
		$cont=true;
		$objetos[]=$_REQUEST["$i"];
	}
}
if ($cont == false)
{
	echo "<script>
			alert('ERROR: Debe dar permiso a este USUARIO para acceder a algun MODULO.');	
			document.location='usuario_modificar.php?id_usuario_modificar=$id_usuario_modificar';
    	</script>";
}
else
{
	//Verificar que no exista otro usuario con el mismo login
	$sql="SELECT * FROM usuario WHERE cuenta = '$login' AND estado = 'A' AND id != '$id_usuario_modificar'";
	$consulta=mysql_query($sql,$conexion) or die (mysql_error()); 
	$nfilas=mysql_num_rows($consulta);
	if ($nfilas != 0)
	{
		echo "<script>
				alert('ERROR:Ya existe un usuario con el mismo LOGIN de Ingreso');
				document.location='usuario_modificar.php?id_usuario_modificar=$id_usuario_modificar';
			</script>";
		exit();
	}

	
	//Actualizamos datos basicos del Usuario
	$sql="UPDATE usuario SET cc='$cc',nombre='$nombre',telefono1='$telefono',cuenta='$login',cargo='$cargo',id_aerolinea='$id_aerolinea' WHERE id='$id_usuario_modificar'";
	mysql_query($sql,$conexion) or die (mysql_error());

	//Limpiamos el usuario de Todos los privilegios
	$sql="DELETE FROM privilegio WHERE id_usuario='$id_usuario_modificar'";
	mysql_query($sql,$conexion) or die (mysql_error());

	//Añadimos nuevos privilegios al usuario
	$ncampos=count($objetos);
	for ($i = 0; $i < $ncampos; $i++)
	{
		$id_objeto=$objetos[$i];
		$sql="INSERT INTO privilegio (id_usuario,id_objeto) value ('$id_usuario_modificar','$id_objeto')";
		mysql_query($sql,$conexion) or die (mysql_error());
	}
	echo "<script>
			alert('Usuario ACTUALIZADO de manera exitosa');
			document.location='usuario_lista.php';
		</script>";		
}



?>