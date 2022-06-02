<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
$anno_actual=date("Y");
$metadata_anno="";
for($i=2019; $i<=2040; $i++)
{
  if ($anno_actual == $i)
    $metadata_anno .='<option value="'.$i.'" selected="selected">'.$i.'</option>';
  else
    $metadata_anno .='<option value="'.$i.'">'.$i.'</option>';
} 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <!-Hoja de estilos del calendario -->
  <!-- librería principal del calendario -->
  <link rel="stylesheet" type="text/css" media="all" href="js/calendar-color.css" title="win2k-cold-1" />
  <script type="text/javascript" src="js/calendar.js"></script>
  
  <!-- librería para cargar el lenguaje deseado -->
  <script type="text/javascript" src="js/calendar-es.js"></script>

  <!-- librería que declara la función Calendar.setup, que ayuda a generar un calendario en unas pocas líneas de código -->
  <script type="text/javascript" src="js/calendar-setup.js"></script>

</head>
<body>
<?php
  require("menu.php");
?>
<p class="titulo_tab_principal">An&aacute;lisis de Bodega</p>

<!-- Analisis por Cantidad de Guias -->
<form method="post" action="courier_consulta_reportes_aBodega2.php">
  <table width="600" align="center" class="decoracion_tabla">
    <tr>
      <td colspan="2"  class="celda_tabla_principal">An&aacute;lisis por Cantidad de Gu&iacute;as</td>    
    </tr>
    <tr>
      <td width="70" class="celda_tabla_principal">      
          <button type="submit">
              <img src="imagenes/aceptar-act.png" width="45" height="43" title="Generar Reportes Por Transportador" />
          </button>
      </td>
      <td width="530" class="celda_tabla_principal celda_boton">
        <select name="mes" id="mes">
          <option value="01">Enero</option>
          <option value="02">Febrero</option>
          <option value="03">Marzo</option>
          <option value="04">Abril</option>
          <option value="05">Mayo</option>
          <option value="06">Junio</option>
          <option value="07">Julio</option>
          <option value="08">Agosto</option>
          <option value="09">Septiembre</option>
          <option value="10">Octubre</option>
          <option value="11">Noviembre</option>
          <option value="12">Diciembre</option>
        </select>
        <select name="anno" id="anno">
          <?php echo $metadata_anno?>
        </select>            
      </td>
    </tr>
    <tr>
      <td colspan="2"  class="celda_tabla_principal celda_boton">Despues de Dar Clic en el bot&oacute;n, espere unos minutos, este informe puede tardar en contruirse.</td>    
    </tr>
  </table>
</form>

<!-- Analisis por Cantidad de Peso -->
<form method="post" action="courier_consulta_reportes_aBodega4.php">
  <table width="600" align="center" class="decoracion_tabla">
    <tr>
      <td colspan="2"  class="celda_tabla_principal">An&aacute;lisis por Cantidad de Peso</td>    
    </tr>
    <tr>
      <td width="70" class="celda_tabla_principal">      
          <button type="submit">
              <img src="imagenes/aceptar-act.png" width="45" height="43" title="Generar Reportes Por Transportador" />
          </button>
      </td>
      <td width="530" class="celda_tabla_principal celda_boton">
        <table>
          <tr>
            <td width="250px" class="celda_tabla_principal celda_boton">
              <div class="asterisco">Desde</div>
              <input name="rangoini" type="text" id="rangoini" size="10" readonly="readonly"/>
                <input type="button" id="lanzador" value="..."/>
                <!-- formulario con el campo de texto y el botón para lanzar el calendario-->
                <!-- script que define y configura el calendario-->
                <script type="text/javascript">
                    Calendar.setup({
                        inputField     :    "rangoini",      // id del campo de texto
                        ifFormat       :    "%Y-%m-%d",       // formato de la fecha, cuando se escriba en el campo de texto
                        button         :    "lanzador"   // el id del botón que lanzará el calendario
                    });
              
                </script>
          </td>
            <td width="250px" class="celda_tabla_principal celda_boton">
               <div class="asterisco">Hasta</div>
              <input name="rangofin" type="text" id="rangofin" size="10" readonly="readonly"/>
              <input type="button" id="lanzador2" value="..."/>
              <!-- formulario con el campo de texto y el botón para lanzar el calendario-->
              <!-- script que define y configura el calendario-->
              <script type="text/javascript">
                    Calendar.setup({
                        inputField     :    "rangofin",      // id del campo de texto
                        ifFormat       :    "%Y-%m-%d",       // formato de la fecha, cuando se escriba en el campo de texto
                        button         :    "lanzador2"   // el id del botón que lanzará el calendario
                    });
                </script>
            </td>                  
          </tr>          
        </table>
      </td>
    </tr>
    <tr>
      <td colspan="2"  class="celda_tabla_principal celda_boton">Despues de Dar Clic en el bot&oacute;n, espere unos minutos, este informe puede tardar en contruirse.</td>    
    </tr>
  </table>
</form>

</body>
</html>
