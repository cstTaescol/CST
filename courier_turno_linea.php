<?php 
    session_start(); /*     "This product includes PHP software, freely available from
         						<http://www.php.net/software/>". */
    require("config/configuracion.php");
    require("config/control_tiempo.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="tema/estilo.css" rel="stylesheet" type="text/css" />    
    <link rel="stylesheet" href="js/bootstrap.min.css" >
    <script src="js/jquery-3.3.1.min.js" ></script>
    <script src="js/popper.min.js" ></script>   
    <script src="js/bootstrap.min.js"></script>     
</head>
<body>
<?php
  require("menu.php");
  $id_objeto=148;
  include("config/provilegios_modulo.php");
?>
<p class="titulo_tab_principal">Operaci&oacute;n en L&iacute;nea</p>
<p class="asterisco" align="center">---</p>    
<form method="post" name="formdata" id="formdata" action="courier_turno_opreacion.php" target="_blank">
    <table align="center">
      <tr>
        <td align="left" class="celda_tabla_principal"><div class="letreros_tabla">Selecione la L&iacute;nea</div></td>
        <td class="celda_tabla_principal celda_boton">
            <select name="linea" id="linea" tabindex="1">                
                <script type="text/javascript">         
                    document.getElementById("linea").focus();
                </script>        
                <?php                
                    $sql="SELECT id,nombre FROM courier_linea WHERE estado ='A' ORDER BY nombre ASC";
                    $consulta=mysql_query ($sql,$conexion) or die ("Error 1: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
                    while($fila=mysql_fetch_array($consulta))
                    {                        
                        echo '<option value="'.$fila['id'].'">'.$fila['nombre'].'</option>';
                    }
                ?>
            </select>    
        </td>
      </tr>
      <tr>
         <td colspan="2" align="center">
            <button type="submit" title="Iniciar">
                <img src="imagenes/scanner_1.png" width="200">
            </button>             
         </td> 
      </tr>
      <tr>
         <td class="celda_tabla_principal celda_boton" colspan="2">Seleccione la l&iacute;nea de trabajo y oprima en el bot&oacute;n para iniciar la solicitud de turnos</td>
      </td>
    </table>
    <input type="hidden" name="usuario" id="usuario" value="<?php echo $_SESSION["id_usuario"]; ?>">    
</form>
</html>
