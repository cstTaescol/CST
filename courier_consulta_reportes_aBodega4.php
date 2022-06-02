<?php 
session_start(); /*     "This product includes PHP software, freely available from
                <http://www.php.net/software/>". */
set_time_limit (0); // Elimina la restriccion en el tiempo limite para la ejecicion del modulo.                
require("config/configuracion.php");
require("config/control_tiempo.php");

//Captura de Rangos de fecha
if(isset($_POST['rangoini']))
{
  $rangoini=$_POST['rangoini'];
}
else
{
 echo "Error al obtener los Datos"; 
 exit();
}


if(isset($_POST['rangofin']))
{
  $rangofin=$_POST['rangofin'];
}
else
{
 echo "Error al obtener los Datos"; 
 exit();
}
//*************************************************************


//Análisis de Cantidad de Guias Recibidas en el Mes (Grafico)
$MayorValor=0;
$TotalPeso=0;
$metadataLabels="";
$metadataData="";
//Consulta de guias en el rango de fecha solicitado  
$sql="SELECT fecha_creacion, SUM(peso) AS pesoDia FROM guia WHERE id_tipo_guia='5' AND fecha_creacion BETWEEN '$rangoini' AND '$rangofin' GROUP BY fecha_creacion";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR: 01 ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$nfilas=mysql_num_rows($consulta);
while($filas=mysql_fetch_array($consulta))
{
  $metadataLabels .= '"'.$filas['fecha_creacion'].'",';
  $metadataData .= round($filas['pesoDia']) . ",";
  if($filas['pesoDia'] > $MayorValor) $MayorValor=$filas['pesoDia'];
  $TotalPeso += $filas['pesoDia'];
}
$metadataLabels = trim($metadataLabels, ',');
$metadataData = trim($metadataData, ',');



//Análisis de los couriere que más carga movilizaron en el més (Pastel)
$metadataTopClientesValor="";
$metadataTopClientesNombre="";
$sql="SELECT id_consignatario,SUM(peso) AS valor FROM guia WHERE id_tipo_guia='5' AND fecha_creacion BETWEEN '$rangoini' AND '$rangofin' GROUP BY id_consignatario ORDER BY valor DESC LIMIT 4";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR: 02 ". mysql_error(). " INFORME AL SOPORTE TECNICO");
while($filas=mysql_fetch_array($consulta))
{
  $metadataTopClientesValor .= $filas['valor'].",";
  $id_courier=$filas['id_consignatario'];

  $sqlAdd="SELECT nombre FROM couriers WHERE id='$id_courier'";
  $consultaAdd=mysql_query ($sqlAdd,$conexion) or die ("ERROR: 03 ". mysql_error(). " INFORME AL SOPORTE TECNICO");
  $nfilasAdd=mysql_fetch_array($consultaAdd);
  $metadataTopClientesNombre .= '"'.$nfilasAdd['nombre'].'",';
}
$metadataTopClientesValor = trim($metadataTopClientesValor, ',');
$metadataTopClientesNombre = trim($metadataTopClientesNombre, ',');

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>An&aacute;lisis de Bodega</title>

  <!-- Custom fonts for this template-->
  <link href="js/js_analisis/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

  <!-- Page level plugin CSS-->
  <link href="js/js_analisis/vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="js/js_analisis/css/sb-admin.css" rel="stylesheet">

</head>

<body id="page-top">
  <?php require("menu.php"); ?>
  
  <div id="conenido">   
      <p class="titulo_tab_principal">An&aacute;lisis de Bodega</p>
      <p class="asterisco" align="center">Visualizaci&oacute;n  de diferentes An&aacute;lisis de operaci&oacute;n Zona de Verificaci&oacute;n</p>      
      <table width="450" align="center">
          <tr>
            <td align="center" valign="middle" class="celda_tabla_principal"><div class="letreros_tabla">MENU DE ACCIONES</div></td>
          </tr>
          <tr>
            <td align="center" valign="middle" class="celda_tabla_principal celda_boton">
                  <button name="imprimir" type="button" onClick="document.location='courier_consulta_reportes_aBodega1.php'">
                      <img src="imagenes/atras-act.png" title="Volver" /><br />
                    <strong>Volver</strong>
                  </button>

                  <button name="terminar" type="button" onClick="document.location='courier_consulta_reportes.php'">
                      <img src="imagenes/aceptar-act.png" title="Terminar Analisis" /><br />
                    <strong>Terminar</strong>
                  </button>
                              
            </td>
          </tr>
      </table>    
  </div>  

  <div id="wrapper">
    <div id="content-wrapper">
      <div class="container-fluid">
        <!-- Area Chart Example-->
        <div class="card mb-3">
          <div class="card-header">
            <i class="fas fa-chart-area"></i>Reporte de Peso de Gu&iacute;as Atendidas</div>
          <div class="card-body">
            <canvas id="myAreaChart" width="100%" height="30"></canvas>
          </div>
           <div class="card-footer small text-muted">Total Peso atendidos durante <?php echo $rangoini." a ".$rangofin?> = <?php echo number_format($TotalPeso,2,",",".") ?></div>
        </div>
      
          <div class="col-lg-4">
            <div class="card mb-3">
              <div class="card-header">
                <i class="fas fa-chart-pie"></i>
                Top Mejores 4 Clientes del Periodo</div>
              <div class="card-body">
                <canvas id="myPieChart" width="100%" height="100"></canvas>
              </div>
              <div class="card-footer small text-muted">Clientes con m&aacute;s carga durante el rango de Fecha: <?php echo $rangoini."-".$rangofin?></div>
            </div>
          </div>
        </div>

      </div>
      <!-- /.container-fluid -->
    </div>
    <!-- /.content-wrapper -->

  </div>
  <!-- /#wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Bootstrap core JavaScript-->
  <script src="js/js_analisis/vendor/jquery/jquery.min.js"></script>
  <script src="js/js_analisis/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="js/js_analisis/vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Page level plugin JavaScript-->
  <script src="js/js_analisis/vendor/chart.js/Chart.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/js_analisis/js/sb-admin.min.js"></script>

  <!-- Demo scripts for this page-->
  <!--
    <script src="js/demo/chart-area-demo.js"></script>
    <script src="js/js_analisis/js/demo/chart-pie-demo.js"></script>       
    <script src="js/js_analisis/js/demo/chart-bar-demo.js"></script>
  -->
</body>

</html>

<script type="text/javascript">
//Analisis de Grafico
  // Set new default font family and font color to mimic Bootstrap's default styling
  Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
  Chart.defaults.global.defaultFontColor = '#292b2c';

  // Area Chart Example
  var ctx = document.getElementById("myAreaChart");
  var myLineChart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: [<?php echo $metadataLabels ?>],
      datasets: [{
        label: "Peso",
        lineTension: 0.3,
        backgroundColor: "rgba(2,117,216,0.2)",
        borderColor: "rgba(2,117,216,1)",
        pointRadius: 5,
        pointBackgroundColor: "rgba(2,117,216,1)",
        pointBorderColor: "rgba(255,255,255,0.8)",
        pointHoverRadius: 5,
        pointHoverBackgroundColor: "rgba(2,117,216,1)",
        pointHitRadius: 50,
        pointBorderWidth: 2,
        data: [<?php echo $metadataData ?>],
      }],
    },
    options: {
      scales: {
        xAxes: [{
          time: {
            unit: 'date'
          },
          gridLines: {
            display: false
          },
          ticks: {
            maxTicksLimit: 7
          }
        }],
        yAxes: [{
          ticks: {
            min: 0,
            max: <?php echo $MayorValor + 1000?>,
            maxTicksLimit: 5
          },
          gridLines: {
            color: "rgba(0, 0, 0, .125)",
          }
        }],
      },
      legend: {
        display: false
      }
    }
  });  

//Analisis de Pastel
  // Set new default font family and font color to mimic Bootstrap's default styling
  Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
  Chart.defaults.global.defaultFontColor = '#292b2c';

  // Pie Chart Example
  var ctx = document.getElementById("myPieChart");
  var myPieChart = new Chart(ctx, {
    type: 'pie',
    data: {
      labels: [<?php echo $metadataTopClientesNombre; ?>],
      datasets: [{
        data: [<?php echo $metadataTopClientesValor; ?>],
        backgroundColor: ['#007bff', '#dc3545', '#ffc107', '#28a745'],
      }],
    },
  });
//****************************************************************************************************

</script>