<?php 
session_start(); /*     "This product includes PHP software, freely available from
                <http://www.php.net/software/>". */
set_time_limit (0); // Elimina la restriccion en el tiempo limite para la ejecicion del modulo.                
require("config/configuracion.php");
require("config/control_tiempo.php");

//Captura de Rangos de fecha
if(isset($_POST['anno']))
{
  $anno=$_POST['anno'];
}
else
{
 echo "Error al obtener los Datos"; 
 exit();
}


if(isset($_POST['mes']))
{
  $mes=$_POST['mes'];
}
else
{
 echo "Error al obtener los Datos"; 
 exit();
}
//*************************************************************


//Análisis de Cantidad de Guias Recibidas en el Mes (Grafico)
$MayorValor=0;
$metadataCantidades="";
for ($i=1; $i<=31; $i++) 
{ 
  //Casto de la variable para los primeros dias del mes
  switch ($i) 
  {
    case '1':
      $dia='01';
    break;
    case '2':
      $dia='02';
    break;
    case '3':
      $dia='03';
    break;
    case '4':
      $dia='04';
    break;
    case '5':
      $dia='05';
    break;
    case '6':
      $dia='06';
    break;
    case '7':
      $dia='07';
    break;
    case '8':
      $dia='08';
    break;
    case '9':
      $dia='09';
    break;   
    default:
       $dia=$i;
    break;
  }
  //Consulta de guias en el rango de fecha solicitado  
  $sql="SELECT id FROM guia WHERE fecha_creacion ='$anno-$mes-$dia' AND id_tipo_guia='5'";
  $consulta=mysql_query ($sql,$conexion) or die ("ERROR: 01 ". mysql_error(). " INFORME AL SOPORTE TECNICO");
  $nfilas=mysql_num_rows($consulta);
  if($nfilas > $MayorValor) $MayorValor=$nfilas;
  $metadataCantidades .= $nfilas.",";  
}
$metadataCantidades = trim($metadataCantidades, ',');


//Análisis de los couriere que más carga movilizaron en el més (Pastel)
$metadataTopClientesValor="";
$metadataTopClientesNombre="";
$sql="SELECT id_consignatario,count(*) AS valor FROM guia WHERE id_tipo_guia='5' AND fecha_creacion BETWEEN '$anno-$mes-01' AND '$anno-$mes-31' GROUP BY id_consignatario ORDER BY valor DESC LIMIT 4";
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


//Análisis Historico (Barras)
$MayorValorHitorico=0;
$metadataCantidadesHistorico="";
$metadataMesHistorico="";
$fecha_recibida = date("$anno-$mes");
for ($i=1; $i<=6; $i++) 
{       
  $sql="SELECT id FROM guia WHERE id_tipo_guia='5' AND fecha_creacion BETWEEN '$fecha_recibida-01' AND '$fecha_recibida-31'";
  $consulta=mysql_query ($sql,$conexion) or die ("ERROR: 01 ". mysql_error(). " INFORME AL SOPORTE TECNICO");
  $nfilasHistorico=mysql_num_rows($consulta);
  if($nfilasHistorico > $MayorValorHitorico) $MayorValorHitorico=$nfilasHistorico;
  $metadataCantidadesHistorico .= $nfilasHistorico.",";  
  $metadataMesHistorico .= '"'.$fecha_recibida.'",';
  $fecha_recibida=date("Y-m",strtotime($fecha_recibida."- 1 month"));     
}
$metadataCantidadesHistorico = trim($metadataCantidadesHistorico, ',');
$metadataMesHistorico = trim($metadataMesHistorico, ',');

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
            <i class="fas fa-chart-area"></i> An&aacute;lisis de Cantidad de Gu&iacute;as Atendidas <?php echo $anno."-".$mes."-01 al ".$anno."-".$mes."-31"?></div>
          <div class="card-body">
            <canvas id="myAreaChart" width="100%" height="30"></canvas>
          </div>
           <div class="card-footer small text-muted">Gu&iacute;as atendidas dureante el mes seleccionado. Rango de Fecha - <?php echo $anno."-".$mes."-01 al ".$anno."-".$mes."-31";?></div>
        </div>
        <div class="row">
          <div class="col-lg-8">
            <div class="card mb-3">
              <div class="card-header">
                <i class="fas fa-chart-bar"></i>
                An&aacute;lisis Hist&oacute;rico</div>
              <div class="card-body">
                <canvas id="myBarChart" width="100%" height="50"></canvas>
              </div>
              <div class="card-footer small text-muted">Comparativo de la cantidad de Gu&iacute;as atendidas en el &uacute;ltimo semestre</div>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="card mb-3">
              <div class="card-header">
                <i class="fas fa-chart-pie"></i>
                Top Mejores 4 Clientes del Mes</div>
              <div class="card-body">
                <canvas id="myPieChart" width="100%" height="100"></canvas>
              </div>
              <div class="card-footer small text-muted">Clientes con m&aacute;s Gu&iacute;as durante el rango de Fecha: <?php echo $anno."-".$mes."-01 al ".$anno."-".$mes."-31" ; ?></div>
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
      labels: ["Dia 1","Dia 2","Dia 3","Dia 4","Dia 5","Dia 6","Dia 7","Dia 8","Dia 9","Dia 10","Dia 11","Dia 12","Dia 13","Dia 14","Dia 15","Dia 16","Dia 17","Dia 18","Dia 19","Dia 20","Dia 21","Dia 22","Dia 23","Dia 24","Dia 25","Dia 26","Dia 27","Dia 28","Dia 29","Dia 30","Dia 31"],
      datasets: [{
        label: "Sessions",
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
        data: [<?php echo $metadataCantidades ?>],
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
            max: <?php echo $MayorValor + 5?>,
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
//****************************************************************************************************

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

//Analisis de Barras
  // Set new default font family and font color to mimic Bootstrap's default styling
  Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
  Chart.defaults.global.defaultFontColor = '#292b2c';

  // Bar Chart Example
  var ctx = document.getElementById("myBarChart");
  var myLineChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: [<?php echo $metadataMesHistorico ?>],
      datasets: [{
        label: "Total Guias",
        backgroundColor: "rgba(2,117,216,1)",
        borderColor: "rgba(2,117,216,1)",
        data: [<?php echo $metadataCantidadesHistorico ?>],
      }],
    },
    options: {
      scales: {
        xAxes: [{
          time: {
            unit: 'month'
          },
          gridLines: {
            display: false
          },
          ticks: {
            maxTicksLimit: 6
          }
        }],
        yAxes: [{
          ticks: {
            min: 0,
            max: <?php echo $MayorValorHitorico + 5 ?>,
            maxTicksLimit: 5
          },
          gridLines: {
            display: true
          }
        }],
      },
      legend: {
        display: false
      }
    }
  });
//****************************************************************************************************
</script>