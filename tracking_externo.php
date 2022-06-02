<?php 
/*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
//require("config/control_tiempo.php");
?>

<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <link href="tema/estilo.css" rel="stylesheet" type="text/css" />
    <script language="javascript" type="text/javascript">
    function imprM(bot) {
    var txtarea = document.getElementById('ta');
    var teclaM = new Array('1','2','3','4','5','6','7','8','9','0','Q','W','E','R','T','Y','U','I','O','P','A','S','D','F','G','H','J','K','L',',','Z','X','C','V','B','N','M','-','.','_','\n',' ','              ');
    txtarea.value+=teclaM[bot];
    txtarea.focus();
    return;
    }
    
    var capa1
    var capa2
    var ns4 = (document.layers)? true:false
    var ie4 = (document.all)? true:false
    var ns6 = (document.getElementById)? true:false
    
    function teclado() {
       if (ns4) {
         capa1 = document.c1
         capa2 = document.c2
      }
     if (ie4) {
       capa1 = c1.style
       capa2 = c2.style
     }
     if (ns6) {
       capa1 = document.getElementById('c1').style
       capa2 = document.getElementById('c2').style
     }
    }
    
    function borrar() {
    var txtarea = document.getElementById('ta');
    var txtSeleccion = document.selection.createRange().text;
    
        if (document.selection) {
    
            if (!txtSeleccion) {
            txtarea.focus();
            var Sel = document.selection.createRange();
            Sel.moveStart ('character', -txtarea.value.length);
            curPos = Sel.text.length;
            txtarea.value=txtarea.value.substr(0,txtarea.value.length-1);
            return(curPos);
            }
    
            txtarea.focus();
            var Sel = document.selection.createRange();
            document.selection.createRange().text = "";
            Sel.moveStart ('character', -txtarea.value.length);
            curPos = Sel.text.length;
            return(curPos);
        }
    }
    </script>
    
    <style>
        input
		{
			width:40px; 
			height:40px; 
			text-align:center; 
			font-size:30px;
		}
		.radios
		{
			width:10px; 
			height:10px; 
			font-size:5px;
		}
        .estilotextobuscador
		{
			font-size:18pt;
			font-weight:bold;
			letter-spacing:5px;
			font-family: Garamond,verdana;
			width:400;
		}
    </style>
	<title>TRACKING SIC-CST</title>
</head>
<body onLoad="teclado();">
    <noscript>
    <h3>Se requiere JavaScript para utilizar este Portal</h3>
    <strong>*&nbsp;Este explorador web no admite JavaScript o las secuencias de comandos est&aacute;n bloqueadas.</strong>
    <meta http-equiv="Refresh" content="2;url=<?php echo URLCLIENTE ?>">
    </noscript>
<form name="tracking" action="tracking_externo2.php" method="post">
	<table align="center">
        <tr>
        	<td>
            	<a href="tracking_externo.php"><img src="imagenes/traking.jpg" border="0"></a>
            </td>
        </tr>
        <tr>
            <td>

                  <table width="770" border="1" align="center" cellpadding="8" cellspacing="8" class="celda_tabla_principal">
                    <tr>
                      <td align="center">
                        	<input type="text" class="estilotextobuscador" id="ta" name="ta" onKeyUp="this.value = this.value.toUpperCase();" onChange="this.value = this.value.toUpperCase();" autocomplete="off">
                        	<script>document.forms[0].ta.focus();</script>
                      </td>
                      <td>
                      	    <input type="radio" name="tipo_guia" value="hija" tabindex="2" checked="checked" class="radios"> Hija
                            <input type="radio" name="tipo_guia" value="master" tabindex="2" class="radios"> Master
                      </td>
                    </tr>
                    <tr>
                      <td width="560" height="259" align="center">
                        <!-- TECLADO -->
                        <div id="c1" class="posLay1">
                          <input type="button" id="1" value="1" onClick="imprM(0);" />
                          <input type="button" id="2" value="2" onClick="imprM(1);" />
                          <input type="button" id="3" value="3" onClick="imprM(2);" />
                          <input type="button" id="4" value="4" onClick="imprM(3);" />
                          <input type="button" id="5" value="5" onClick="imprM(4);" />
                          <input type="button" id="6" value="6" onClick="imprM(5);" />
                          <input type="button" id="7" value="7" onClick="imprM(6);" />
                          <input type="button" id="8" value="8" onClick="imprM(7);" />
                          <input type="button" id="9" value="9" onClick="imprM(8);" />
                          <input type="button" id="10" value="0" onClick="imprM(9);" />
                          <br>
                          <input type="button" id="11" value="Q" onClick="imprM(10);" />
                          <input type="button" id="12" value="W" onClick="imprM(11);" />
                          <input type="button" id="13" value="E" onClick="imprM(12);" />
                          <input type="button" id="14" value="R" onClick="imprM(13);" />
                          <input type="button" id="15" value="T" onClick="imprM(14);" />
                          <input type="button" id="16" value="Y" onClick="imprM(15);" />
                          <input type="button" id="17" value="U" onClick="imprM(16);" />
                          <input type="button" id="18" value="I" onClick="imprM(17);" />
                          <input type="button" id="19" value="O" onClick="imprM(18);" />
                          <input type="button" id="20" value="P" onClick="imprM(19);" />
                          <br>
                          <input type="button" id="21" value="A" onClick="imprM(20);" />
                          <input type="button" id="22" value="S" onClick="imprM(21);" />
                          <input type="button" id="23" value="D" onClick="imprM(22);" />
                          <input type="button" id="24" value="F" onClick="imprM(23);" />
                          <input type="button" id="25" value="G" onClick="imprM(24);" />
                          <input type="button" id="26" value="H" onClick="imprM(25);" />
                          <input type="button" id="27" value="J" onClick="imprM(26);" />
                          <input type="button" id="28" value="K" onClick="imprM(27);" />
                          <input type="button" id="29" value="L" onClick="imprM(28);" />
                          <input type="button" id="30" value="," onClick="imprM(29);" />
                          <br>
                          <input type="button" id="31" value="Z" onClick="imprM(30);" />
                          <input type="button" id="32" value="X" onClick="imprM(31);" />
                          <input type="button" id="33" value="C" onClick="imprM(32);" />
                          <input type="button" id="34" value="V" onClick="imprM(33);" />
                          <input type="button" id="35" value="B" onClick="imprM(34);" />
                          <input type="button" id="36" value="N" onClick="imprM(35);" />
                          <input type="button" id="37" value="M" onClick="imprM(36);" />
                          <input type="button" id="38" value="-" onClick="imprM(37);" />
                          <input type="button" id="39" value="." onClick="imprM(38);" />
                          <input type="button" id="40" value="_" onClick="imprM(39);" />
                          <br>
                          <button type="button" id="42"  onClick="imprM(41);" style="width:400px; height:46px; text-align:center; font-size:18px;"/>    
                            ESPACIO
                          </button>
                        </div>
                        </td>
                      <td width="152" align="center" valign="middle">
                          <button type="reset" id="eliminar_caracter" style="width:160px; height:60px"/>    
                            <img src="imagenes/borrar.jpg" width="140px" height="50px" align="absmiddle" title="Borrar" >
                          </button>
                        <button type="submit" style="width:160px; height:160px">
                            <img src="imagenes/atras-act.png" title="Entrar" ><br>
                            Enter
                         </button>
                       </td>
                    </tr>
                    <tr>
                      <td height="55" colspan="2" align="left" class="celda_tabla_principal celda_boton asterisco">Digite el n&uacute;mero de gu&iacute;a que desea rastrear y presione el Boton de Enter.</td>
                    </tr>
                  </table>
                  
          </td>
      </tr>
</table>
        
</form>
</body>
</html>  
