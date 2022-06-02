<?php 
    session_start(); /*     "This product includes PHP software, freely available from
         						<http://www.php.net/software/>". */
    require("config/configuracion.php");
    require("config/control_tiempo.php");


    if(isset($_REQUEST['id_guia']))
    {
        $id_guia=$_REQUEST['id_guia'];
        // Verificacion de Datos Recibidos 
        $sql="SELECT master FROM guia WHERE id = '$id_guia'";
        $consulta=mysql_query($sql,$conexion) or die (exit('Error 1'.mysql_error()));
        $fila=mysql_fetch_array($consulta);
        $master=$fila["master"];    
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
    if(isset($_REQUEST['observaciones']))
    {   
        $fecha=date("Y").date("m").date("d");
        $hora=date("H:i:s");
        $usuario=$_SESSION["id_usuario"];   
        $id_guia=isset($_GET['id_guia']) ? $_GET['id_guia'] : $_POST['id_guia'];
        $observaciones=strtoupper($_POST['observaciones']);
        $courier_dato_fin = date("Y-m-d") . " " . $hora;

        //1. Insertamos datos nuevos
        $sql_update="UPDATE 
                        guia 
                    SET                     
                        id_tipo_bloqueo='1' 
                    WHERE 
                        id='$id_guia'";
        mysql_query($sql_update,$conexion) or die (exit('Error 2 '.mysql_error()));


        //3. Ingresar los datos del Tracking de la guia
        $sql2="INSERT INTO tracking (id_guia,
                          evento,
                          fecha_creacion,
                          hora,
                          tipo_tracking,
                          id_usuario) 
                            VALUE ('$id_guia',
                                    'Guia Reincorporada al Inventario<br>
                                     Motivo de Reincorporaci&oacute;n:<br>
                                     $observaciones
                                    ',
                                    '$fecha',
                                    '$hora',
                                    '1',
                                    '$usuario')";
        mysql_query($sql2,$conexion) or die (exit('Error 3 '.mysql_error()));
               
        //Actualiza el inventario padre y cierra la ventana hija actual
        echo '  
                <script language="javascript">
                        alert("Registro Exitoso");
                        //window.opener.location.reload();
                        window.opener.location="courier_inventario.php";
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
<p class="titulo_tab_principal">Reincorporaci&oacute;n de Gu&iacute;a</p>
<p class="asterisco" align="center">Guia No. <?php echo $master ?> </p>

<form method="post" action="<?php echo $_SERVER['SCRIPT_NAME'] ?>" onsubmit="return validar();" >
	<table align="center" width="300">
        <!-- OBSERVACIONES  -->
        <tr>
            <td class="celda_tabla_principal"><div class="letreros_tabla">Motivo</div></td>
            <td class="celda_tabla_principal celda_boton">
                <textarea id="observaciones" name="observaciones" cols="50" rows="5"></textarea>
                <script language="JavaScript" type="text/javascript">
                    document.getElementById('observaciones').focus();
                </script>
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
    //Validacion de formulario
    function validar()
    {   
        if (document.forms[0].observaciones.value=="")
        {
            alert("Atencion: Debe describir el Motivo de la reincorporacion de la guia ");
            document.forms[0].observaciones.focus();
            return(false);
        }
    }
</script>