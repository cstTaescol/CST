<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");


if(isset($_REQUEST['id_guia']))
{
    $id_guia=$_REQUEST['id_guia'];
    // Verificacion de Datos Recibidos 
    $sql="SELECT master,piezas_inconsistencia, peso_inconsistencia FROM guia WHERE id = '$id_guia'";
    $consulta=mysql_query($sql,$conexion) or die (exit('Error 1'.mysql_error()));
    $fila=mysql_fetch_array($consulta);
    $master=$fila["master"];    
    $piezas=$fila["piezas_inconsistencia"];
    $peso=$fila["peso_inconsistencia"];
}
else
{
    echo '
        <script>
            alert("Error: El servidor no pudo obtener la informacion, intentelo de nuevo y comuniquese con el soporte tecnico.");
            document.location="base.php";
        </script>
        ';
    exit();
}

//Cuando se oprima el boton de guardar
if(isset($_REQUEST['piezas']))
{   
    $piezas=$_REQUEST['piezas'];
    $peso=$_REQUEST['peso'];
    $sql_update="UPDATE  guia 
                 SET piezas_inconsistencia='$piezas', 
                     peso_inconsistencia='$peso'
                 WHERE 
                     id='$id_guia'
            ";    
    //Insertamos datos nuevos
    mysql_query($sql_update,$conexion) or die (exit('Error 2'.mysql_error()));
    
    //Crea registro en el historial
    $fecha=date("Y").date("m").date("d");
    $hora=date("H:i:s");
    $usuario=$_SESSION["id_usuario"];
    $registro_novedad='Guia Verificada con:<br>Piezas:'.$piezas.'<br>Peso:'.$peso;
    $sql_trak="INSERT INTO tracking (id_guia,
                                 fecha_creacion,
                                 hora,
                                 evento,
                                 tipo_tracking,
                                 id_usuario) 
                                    VALUE ('$id_guia',
                                           '$fecha',
                                           '$hora',
                                           '$registro_novedad',
                                           '1',
                                           '$id_usuario')";
    mysql_query($sql_trak,$conexion) or die (exit('Error 3'.mysql_error()));

            
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
    <title>Verificaci&oacute;n</title>
</head>
<body>
<p class="titulo_tab_principal">Conteo de Guia</p>
<p class="asterisco" align="center">Guia No. <?php echo $master ?> </p>

<form method="post" action="<?php echo $_SERVER['SCRIPT_NAME'] ?>" onsubmit="return validar();" >
	<table align="center" width="300">
        <!-- PIEZAS  -->
        <tr>
            <td class="celda_tabla_principal"><div class="letreros_tabla">Piezas</div></td>
            <td class="celda_tabla_principal celda_boton">
                <input type="text" name="piezas" id="piezas" onKeyPress="return numeric(event)" size="5" maxlength="10" value="<?php echo $piezas ?>" />
                <script language="JavaScript" type="text/javascript">
                    document.getElementById('piezas').focus();
                </script>
            </td>
        </tr>
        <!-- PESO -->
        <tr>
            <td class="celda_tabla_principal"><div class="letreros_tabla">Peso</div></td>
            <td class="celda_tabla_principal celda_boton">
                <input name="peso" type="text" id="peso" onKeyPress="return numeric2(event)" size="5" maxlength="10" value="<?php echo $peso ?>"/>
            </td>
        </tr>
    </table>
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

    //Validacion de campos numéricos
    function numeric(e) 
    { 
        tecla = (document.all) ? e.keyCode : e.which; // 2
        if (tecla==8) return true; // 3
        patron =/[0-9]/; // 4 -> Puede ser[0-9-.\s] para permitir agregar puntos al campo de texto
        te = String.fromCharCode(tecla); // 5
        return patron.test(te); // 6
    } 

    //Validacion de campos numéricos
    function numeric2(e) 
    { 
        tecla = (document.all) ? e.keyCode : e.which; // 2
        if (tecla==8) return true; // 3
        patron =/[0-9-.]/; // 4 -> Puede ser[0-9-.\s] para permitir agregar puntos al campo de texto
        te = String.fromCharCode(tecla); // 5
        return patron.test(te); // 6
    } 

    //Validacion de formulario
    function validar()
    {   
        if (document.forms[0].piezas.value=="")
        {
            alert("Atencion: Se requiere cantidad de Piezas");
            document.forms[0].piezas.focus();
            return(false);
        }
        if (document.forms[0].peso.value=="")
        {
            alert("Atencion: Se requiere cantidad de Peso");
            document.forms[0].peso.focus();
            return(false);
        }        
    }


</script>