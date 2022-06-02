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
    <!--[if IE]>
        <style type="text/css" media="screen">
            @font-face
            {
                font-family:'OpenSans';
                src: url('tema/fonts/Open_Sans/OpenSans-Regular.eot');
             }
         </style>
     <![endif]-->
</head>

<body>
<?php
require("menu.php");
?>
<table width="50%" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td align="center" valign="middle"></td>
  </tr>
  <tr>
    <td align="center" valign="middle">
        <p><font face="Tahoma, Geneva, sans-serif" size="+4" color="#0033FF"><?php echo SIGLA ?></font><br>
        <font face="Courier New, Courier, monospace" size="+1" color="#009900"><em><?php echo PROGRAMA ?></em></font></p>
        <p>Bienvenido <?php echo $usuario ?></p>
        <hr>
        <p>Para una mejor experiencia de uso, recomendamos usar la &uacute;ltima versi&oacute;n de Firefox o Google Crome</p>
    </td>
  </tr>
</table>
</body>
</html>
