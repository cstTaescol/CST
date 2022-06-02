<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");

$entidad=$_REQUEST["entidad"];

if ($entidad != 6) 
{
   $visibilidad ='style="display: none;"';
}
else
{    
   $visibilidad ='';    
}  

//Cuando se oprima el boton de guardar
if(isset($_REQUEST['cc']))
{   
    $cc=$_REQUEST['cc'];
    $nombre=strtoupper($_REQUEST["nombre"]);    
    switch ($entidad) 
    {
        case '6':
            $otros=strtoupper($_REQUEST["otros"]);
            $id_guia=$_REQUEST["id_guia"];            
            $sql_update="INSERT INTO courier_funcionario ( 
                                nombre,
                                no_documento,
                                id_entidad,
                                id_guia,
                                otros
                                )
                         VALUE (
                                '$nombre',
                                '$cc',
                                '$entidad',
                                '$id_guia',
                                '$otros'
                                )";
        break;        

        case '7':
            $id_guia=$_REQUEST["id_guia"];
            //consulta auxiliar    
            $sql="SELECT id_consignatario FROM guia WHERE id='$id_guia'";
            $consulta=mysql_query ($sql,$conexion) or die ("ERROR 1: ". mysql_error(). " INFORME AL SOPORTE TECNICO");  
            $fila=mysql_fetch_array($consulta);
            $id_consignatario=$fila['id_consignatario'];    

            $sql_update="INSERT INTO courier_funcionario ( 
                                nombre,
                                no_documento,
                                id_entidad,
                                id_consignatario,
                                otros
                                )
                         VALUE (
                                '$nombre',
                                '$cc',
                                '$entidad',
                                '$id_consignatario',
                                '$otros'
                                )";            
        break;

        default:            
            $sql_update="INSERT INTO courier_funcionario ( 
                                nombre,
                                no_documento,
                                id_entidad
                                )
                         VALUE (
                                '$nombre',
                                '$cc',
                                '$entidad' 
                                )";
        break;
    }
    
    //Insertamos datos nuevos
    mysql_query($sql_update,$conexion) or die ("ERROR 2: ". mysql_error(). " INFORME AL SOPORTE TECNICO");      
                
    //Actualiza el inventario padre y cierra la ventana hija actual
    echo '  
            <script language="javascript">
                    alert("Registro Exitoso");
                    window.opener.location.reload();
                    self.close();
            </script>';
    exit();
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="tema/estilo.css" rel="stylesheet" type="text/css" />
    <script src="js/srcNewCalendar/js/jscal2.js"></script>
    <script src="js/srcNewCalendar/js/lang/es.js"></script>
    <link rel="stylesheet" type="text/css" href="js/srcNewCalendar/css/jscal2.css" />
    <link rel="stylesheet" type="text/css" href="js/srcNewCalendar/css/border-radius.css" />
    <link rel="stylesheet" type="text/css" href="js/srcNewCalendar/css/steel/steel.css" />    
    <title>Seguridad Courier</title>
</head>
<body>
<form method="post" enctype="multipart/form-data" action="<?php echo $_SERVER['SCRIPT_NAME'] ?>" onsubmit="return validar();" >
	<p class="titulo_tab_principal">Funcionario</p>
	<p class="asterisco" align="center">Nuevo</p>
	<table align="center" width="300">
        <!-- CC  -->
        <tr>
        	<td class="celda_tabla_principal">
        		<div class="letreros_tabla asterisco">C&eacute;dula</div>          		
        	</td>      	
            <td class="celda_tabla_principal celda_boton">
            	<input type="text" name="cc" id="cc" tabindex="2" size="6" maxlength="13" onkeypress="return numeric(event)">            
                <script type="text/javascript">			
                    document.getElementById("cc").focus();
                </script>             	
            </td>                 
        </tr>
        <!-- NOMBRE -->
        <tr>
        	<td class="celda_tabla_principal"><div class="letreros_tabla asterisco">Nombre</div></td>
            <td class="celda_tabla_principal celda_boton">
            	<input type="text" name="nombre" id="nombre" tabindex="3" size="20" maxlength="50">
            </td>                 
        </tr>
    </table>
    <div <?php echo $visibilidad ?>>
        <table align="center" width="300">
            
                <tr>
                    <td class="celda_tabla_principal"><div class="letreros_tabla asterisco">Nombre Entidad</div></td>
                    <td class="celda_tabla_principal celda_boton">
                        <input type="text" name="otros" id="otros" tabindex="4" size="20" maxlength="40">
                    </td>                 
                </tr>        
        </table>    
    </div>
    <input type="hidden" name="entidad" id="entidad" value="<?php echo $_REQUEST["entidad"]; ?>">
    <input type="hidden" name="id_guia" id="id_guia" value="<?php echo $_REQUEST["id_guia"]; ?>">    
    <table width="450px" align="center">
        <tr>
          <td align="center" valign="middle" class="celda_tabla_principal"><div class="letreros_tabla">MENU DE ACCIONES</div></td>
        </tr>
        <tr>
          <td align="center" valign="middle" class="celda_tabla_principal celda_boton">
            <button type="reset" name="reset" id="reset" tabindex="5">
                <img src="imagenes/descargar-act.png" title="Limpiar" />
            </button>
            <button type="submit" name="guardar" id="guardar" tabindex="4">
                <img src="imagenes/guardar-act.png" title="Guardar" />
            </button>
          </td>
        </tr>
     </table>    
</form>
</body>
</html>
<script language="javascript">

// funcion para validar
function validar()
{	    

	if (document.forms[0].cc.value=="")
	{
		alert("Atencion: Debe digitar la CEDULA del funcionario.");
		document.forms[0].cc.focus();		
		return(false);
	}
	
	if (document.forms[0].nombre.value=="")
	{
		alert("Atencion: Debe digitar el NOMBRE del funcionario.");
		document.forms[0].nombre.focus();		
		return(false);		
	}
    var entidad=<?php echo $entidad ?>;
    if (entidad == "6")
    {
        if (document.forms[0].otros.value=="")
        {
            alert("Atencion: Debe digitar el nombre de la ENTIDAD del funcionario.");
            document.forms[0].otros.focus();       
            return(false);      
        }        
    }	

}

//Validacion de campos numéricos
function numeric(e) { // 1
    tecla = (document.all) ? e.keyCode : e.which; // 2
    if (tecla==8) return true; // 3
    patron =/[0-9]/; // 4 -> Puede ser[0-9-.\s] para permitir agregar puntos al campo de texto
    te = String.fromCharCode(tecla); // 5
    return patron.test(te); // 6
}
</script>