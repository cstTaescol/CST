<?php
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
?>
<html>
<head>
<script language="javascript">
// funcion para validar
function validar()
{
	if (document.forms[0].fecha.value=="")
	{
		alert("Atencion: Debe digitar un FECHA");
		document.forms[0].lanzador.focus();
		return(false);
	}
}
</script>

<!-Hoja de estilos del calendario -->
<link rel="stylesheet" type="text/css" media="all" href="js/calendar-color.css" title="win2k-cold-1" />
<!-- librería principal del calendario -->
<script type="text/javascript" src="js/calendar.js"></script>

<!-- librería para cargar el lenguaje deseado -->
<script type="text/javascript" src="js/calendar-es.js"></script>

<!-- librería que declara la función Calendar.setup, que ayuda a generar un calendario en unas pocas líneas de código -->
<script type="text/javascript" src="js/calendar-setup.js"></script>

<script language="javascript" type="text/javascript">
<!-- función que permite abrir ventanas emergentes con las propiedades deseadas -->
function openPopup(url,name,w,h,props,center){
	l=18;t=18
	if(center){l=(screen.availWidth-w)/2;t=(screen.availHeight-h)/2}
	url=url.replace(/[ ]/g,'%20')
	popup=window.open(url,name,'left='+l+',top='+t+',width='+w+',height='+h+',scrollbars=1'+((props)?','+props:''))
	props=props||''
	if(props.indexOf('fullscreen')!=-1){popup.moveTo(0,0);popup.resizeTo(screen.width,screen.height)}
	popup.focus()
}
</script>
</head>
<body>
<?php
require("menu.php");
?>
<p class="titulo_tab_principal">Feriados y Sabaticos</p>
<form name="fecha_excepcion" action="fecha_nuevo.php" onSubmit="return validar();" method="post">
<table align="center">
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Fecha</div></td>
    <td rowspan="2" class="celda_tabla_principal celda_boton">
         <!-- formulario con el campo de texto y el botón para lanzar el calendario-->
        <input type="text" name="fecha" id="fecha" readonly="readonly" size="15"/>
        <input type="button" id="lanzador" value="..." tabindex="1"/>
        <script type="text/javascript">
                document.forms[0].lanzador.focus();
        </script>
        
        <!-- script que define y configura el calendario-->
        <script type="text/javascript">
            Calendar.setup({
                inputField     :    "fecha",      // id del campo de texto
                ifFormat       :    "%Y-%m-%d",       // formato de la fecha, cuando se escriba en el campo de texto
                button         :    "lanzador"   // el id del botón que lanzará el calendario
            });
        </script>
	</td>
  </tr> 
  <tr>
    <td class="celda_tabla_principal" align="center">
            <button type="submit" name="guardar" id="guardar">
                <img src="imagenes/guardar-act.png" title="Guardar" />
            </button>
     </td>
    </tr> 
</table>
</form>
<?php
if(isset($_POST["fecha"]))
{
	$fecha=strtoupper($_POST["fecha"]);
	$sql="INSERT INTO feriados (fecha,estado) value ('$fecha','A')";
	mysql_query($sql,$conexion) or die (mysql_error());
	echo '<p align="center"><font color="green" size="4">Fecha Guardada</font></p>';
}
?>
</body>
</html>