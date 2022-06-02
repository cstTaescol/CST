<?php 
session_start(); /*     "This product includes PHP software, freely available from
                <http://www.php.net/software/>". */
set_time_limit (0); // Elimina la restriccion en el tiempo limite para la ejecicion del modulo.                
require("config/configuracion.php");
require("config/control_tiempo.php");
//*************************************************************

if(isset($_REQUEST['id_guia']))
{
  $id_guia=$_REQUEST['id_guia'];
}
else
{
 echo "Error al obtener los Datos"; 
 exit();
}

//Identificacion de Horas y Minutos de Llegada a Inicio del Proceso
$sql="SELECT master, courier_dato_llegada, courier_dato_inicio, courier_dato_fin, timediff(courier_dato_inicio,courier_dato_llegada) AS diferencia FROM guia WHERE id = '$id_guia'";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR: 01 ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$fila=mysql_fetch_array($consulta);
$courier_dato_llegada = $fila["courier_dato_llegada"];
$courier_dato_inicio = $fila["courier_dato_inicio"];
$courier_dato_fin = $fila["courier_dato_fin"];
$master = $fila["master"];
$Diferencia1_Horas = $fila["diferencia"]; 

$sql="SELECT TIMESTAMPDIFF(MINUTE, courier_dato_llegada, courier_dato_inicio) AS diferencia FROM guia WHERE id = '$id_guia'";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR: 02 ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$fila=mysql_fetch_array($consulta);
$Diferencia1_Minutos = $fila["diferencia"]; 


//Identificacion de Horas y Minutos de Inicio a Fin del Proceso
$sql="SELECT timediff(courier_dato_fin,courier_dato_inicio) AS diferencia FROM guia WHERE id = '$id_guia'";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR: 03 ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$fila=mysql_fetch_array($consulta);
$Diferencia2_Horas = $fila["diferencia"]; 

$sql="SELECT TIMESTAMPDIFF(MINUTE, courier_dato_inicio, courier_dato_fin) AS diferencia FROM guia WHERE id = '$id_guia'";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR: 04 ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$fila=mysql_fetch_array($consulta);
$Diferencia2_Minutos = $fila["diferencia"]; 

// Mayor valor para la tabla
if ($Diferencia1_Minutos > $Diferencia2_Minutos)
{
  $MayorValor= $Diferencia1_Minutos;
}
else
{
  $MayorValor= $Diferencia2_Minutos;
}

//Carga de Usuarios de Entidades
$MetadataDian="";
$sql_add="SELECT f.nombre, f.id_entidad FROM courier_funcionario f LEFT JOIN courier_funcionarios_guia g ON g.id_funcionario = f.id WHERE g.id_guia='$id_guia' AND f.id_entidad='1'";
$consulta_add=mysql_query ($sql_add,$conexion) or die ("ERROR: 05". mysql_error(). " INFORME AL SOPORTE TECNICO");
$nDian=mysql_num_rows($consulta_add);
while($fila_add=mysql_fetch_array($consulta_add))
{
  $MetadataDian .= '<li>'.$fila_add["nombre"] . '</li>';
}

$MetadataTaescol="";
$sql_add="SELECT f.nombre, f.id_entidad FROM courier_funcionario f LEFT JOIN courier_funcionarios_guia g ON g.id_funcionario = f.id WHERE g.id_guia='$id_guia' AND f.id_entidad='2'";
$consulta_add=mysql_query ($sql_add,$conexion) or die ("ERROR: 06". mysql_error(). " INFORME AL SOPORTE TECNICO");
$nTaescol=mysql_num_rows($consulta_add);
while($fila_add=mysql_fetch_array($consulta_add))
{
  $MetadataTaescol .= '<li>'.$fila_add["nombre"] . '</li>';
}

$MetadataPolfa="";
$sql_add="SELECT f.nombre, f.id_entidad FROM courier_funcionario f LEFT JOIN courier_funcionarios_guia g ON g.id_funcionario = f.id WHERE g.id_guia='$id_guia' AND f.id_entidad='3'";
$consulta_add=mysql_query ($sql_add,$conexion) or die ("ERROR: 07". mysql_error(). " INFORME AL SOPORTE TECNICO");
$nPolfa=mysql_num_rows($consulta_add);
while($fila_add=mysql_fetch_array($consulta_add))
{
  $MetadataPolfa .= '<li>'.$fila_add["nombre"] . '</li>';
}

$MetadataInvima="";
$sql_add="SELECT f.nombre, f.id_entidad FROM courier_funcionario f LEFT JOIN courier_funcionarios_guia g ON g.id_funcionario = f.id WHERE g.id_guia='$id_guia' AND f.id_entidad='4'";
$consulta_add=mysql_query ($sql_add,$conexion) or die ("ERROR: 08". mysql_error(). " INFORME AL SOPORTE TECNICO");
$nInvima=mysql_num_rows($consulta_add);
while($fila_add=mysql_fetch_array($consulta_add))
{
  $MetadataInvima .= '<li>'.$fila_add["nombre"] . '</li>';
}

$MetadataIca="";
$sql_add="SELECT f.nombre, f.id_entidad FROM courier_funcionario f LEFT JOIN courier_funcionarios_guia g ON g.id_funcionario = f.id WHERE g.id_guia='$id_guia' AND f.id_entidad='5'";
$consulta_add=mysql_query ($sql_add,$conexion) or die ("ERROR: 09". mysql_error(). " INFORME AL SOPORTE TECNICO");
$nIca=mysql_num_rows($consulta_add);
while($fila_add=mysql_fetch_array($consulta_add))
{
  $MetadataIca .= '<li>'.$fila_add["nombre"] . '</li>';
}

$MetadataOtros="";
$sql_add="SELECT f.nombre, f.id_entidad FROM courier_funcionario f LEFT JOIN courier_funcionarios_guia g ON g.id_funcionario = f.id WHERE g.id_guia='$id_guia' AND f.id_entidad='6'";
$consulta_add=mysql_query ($sql_add,$conexion) or die ("ERROR: 10". mysql_error(). " INFORME AL SOPORTE TECNICO");
$nOtros=mysql_num_rows($consulta_add);
while($fila_add=mysql_fetch_array($consulta_add))
{
  $MetadataOtros .= '<li>'.$fila_add["nombre"] . '</li>';
}

$MetadataCourier="";
$sql_add="SELECT f.nombre, f.id_entidad FROM courier_funcionario f LEFT JOIN courier_funcionarios_guia g ON g.id_funcionario = f.id WHERE g.id_guia='$id_guia' AND f.id_entidad='7'";
$consulta_add=mysql_query ($sql_add,$conexion) or die ("ERROR: 11". mysql_error(). " INFORME AL SOPORTE TECNICO");
$nCourier=mysql_num_rows($consulta_add);
while($fila_add=mysql_fetch_array($consulta_add))
{
  $MetadataCourier .= '<li>'.$fila_add["nombre"] . '</li>';
}

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
      <p class="titulo_tab_principal">An&aacute;lisis de Gu&iacute;a</p>
      <p class="asterisco" align="center">Gu&iacute;a: <?php echo $master ?></p>      
      <table width="450" align="center">
          <tr>
            <td align="center" valign="middle" class="celda_tabla_principal"><div class="letreros_tabla">MENU DE ACCIONES</div></td>
          </tr>
          <tr>
            <td align="center" valign="middle" class="celda_tabla_principal celda_boton">
                  <button name="imprimir" type="button" onClick="document.location='courier_consulta_reporteXfecha.php'">
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


        <div id="wrapper">
          <div id="content-wrapper">
            <div class="container-fluid">
              <!-- Area Chart Example-->
              <div class="card mb-3">
                <div class="card-header">
                  <i class="fas fa-chart-area"> Funcionarios Asignados a la Gu&iacute;a</i></div>
                <div class="card-body">                 
                  <!-- Icon Cards-->
                  <div class="row">
                    <div class="col-xl-3 col-sm-6 mb-3">
                      <div class="card text-white bg-danger o-hidden h-100">
                        <div class="card-body">
                          <div class="card-body-icon">
                            <i class="fas fa-fw fa-life-ring"></i>
                          </div>
                          <div class="mr-5">Datos del Pesonal</div>
                        </div>
                        <a class="card-footer text-white clearfix small z-1" href="#">
                          <span class="float-left">Identifica el Personal Asociado a las Guias dando Clic en Ver detalles</span>

                        </a>
                      </div>
                    </div>

                    <div class="col-xl-3 col-sm-6 mb-3">
                      <div class="card text-white bg-success o-hidden h-100">
                        <div class="card-body">
                          <div class="card-body-icon">
                            <i class="fas fa-fw fa-shopping-cart"></i>
                          </div>
                          <div class="mr-5">Dian (<?php echo $nDian?>)</div>
                        </div>
                        <a class="card-footer text-white clearfix small z-1" href="#">
                          <span class="float-left" data-toggle="collapse" data-target="#usuariosDian">Ver Detalles</span>
                          <span class="float-right">
                            <i class="fas fa-angle-right" data-toggle="collapse" data-target="#usuariosDian"></i>
                          </span>
                        </a>
                        <div id="usuariosDian" class="collapse"><?php echo $MetadataDian ?></div>
                      </div>
                    </div>

                    
                    <div class="col-xl-3 col-sm-6 mb-3">
                      <div class="card text-white bg-primary o-hidden h-100">
                        <div class="card-body">
                          <div class="card-body-icon">
                            <i class="fas fa-fw fa-comments"></i>
                          </div>
                          <div class="mr-5">Taescol (<?php echo $nTaescol?>)</div>
                        </div>
                        <a class="card-footer text-white clearfix small z-1" href="#">
                          <span class="float-left" data-toggle="collapse" data-target="#usuariosTaescol">Ver Detalles</span>
                          <span class="float-right">
                            <i class="fas fa-angle-right" data-toggle="collapse" data-target="#usuariosTaescol"></i>
                          </span>
                        </a>
                        <div id="usuariosTaescol" class="collapse"><?php echo $MetadataTaescol ?></div>
                      </div>
                    </div>

                    <div class="col-xl-3 col-sm-6 mb-3">
                      <div class="card text-white bg-success o-hidden h-100">
                        <div class="card-body">
                          <div class="card-body-icon">
                            <i class="fas fa-fw fa-shopping-cart"></i>
                          </div>
                          <div class="mr-5">Polfa  (<?php echo $nPolfa?>)</div>
                        </div>
                        <a class="card-footer text-white clearfix small z-1" href="#">
                          <span class="float-left" data-toggle="collapse" data-target="#usuariosPolfa">Ver Detalles</span>
                          <span class="float-right">
                            <i class="fas fa-angle-right" data-toggle="collapse" data-target="#usuariosPolfa"></i>
                          </span>
                        </a>
                        <div id="usuariosPolfa" class="collapse"><?php echo $MetadataPolfa ?></div>
                      </div>
                    </div>

                    <div class="col-xl-3 col-sm-6 mb-3">
                      <div class="card text-white bg-warning o-hidden h-100">
                        <div class="card-body">
                          <div class="card-body-icon">
                            <i class="fas fa-fw fa-list"></i>
                          </div>
                          <div class="mr-5">Invima (<?php echo $nInvima?>)</div>
                        </div>
                        <a class="card-footer text-white clearfix small z-1" href="#">
                          <span class="float-left" data-toggle="collapse" data-target="#usuariosInvima">Ver Detalles</span>
                          <span class="float-right">
                            <i class="fas fa-angle-right" data-toggle="collapse" data-target="#usuariosInvima"></i>
                          </span>
                        </a>
                        <div id="usuariosInvima" class="collapse"><?php echo $MetadataInvima ?></div>
                      </div>
                    </div>


                    <div class="col-xl-3 col-sm-6 mb-3">
                      <div class="card text-white bg-danger o-hidden h-100">
                        <div class="card-body">
                          <div class="card-body-icon">
                            <i class="fas fa-fw fa-life-ring"></i>
                          </div>
                          <div class="mr-5">Ica  (<?php echo $nIca?>)</div>
                        </div>
                        <a class="card-footer text-white clearfix small z-1" href="#">
                          <span class="float-left" data-toggle="collapse" data-target="#usuariosIca">Ver Detalles</span>
                          <span class="float-right">
                            <i class="fas fa-angle-right" data-toggle="collapse" data-target="#usuariosIca"></i>
                          </span>
                        </a>
                        <div id="usuariosIca" class="collapse"><?php echo $MetadataIca ?></div>
                      </div>
                    </div>

                    <div class="col-xl-3 col-sm-6 mb-3">
                      <div class="card text-white bg-warning o-hidden h-100">
                        <div class="card-body">
                          <div class="card-body-icon">
                            <i class="fas fa-fw fa-list"></i>
                          </div>
                          <div class="mr-5">Otros  (<?php echo $nOtros?>)</div>
                        </div>
                        <a class="card-footer text-white clearfix small z-1" href="#">
                          <span class="float-left" data-toggle="collapse" data-target="#usuariosOtros">Ver Detalles</span>
                          <span class="float-right">
                            <i class="fas fa-angle-right" data-toggle="collapse" data-target="#usuariosOtros"></i>
                          </span>
                        </a>
                        <div id="usuariosOtros" class="collapse"><?php echo $MetadataOtros ?></div>
                      </div>
                    </div>

                    
                    <div class="col-xl-3 col-sm-6 mb-3">
                      <div class="card text-white bg-primary o-hidden h-100">
                        <div class="card-body">
                          <div class="card-body-icon">
                            <i class="fas fa-fw fa-comments"></i>
                          </div>
                          <div class="mr-5">Courier  (<?php echo $nCourier?>)</div>
                        </div>
                        <a class="card-footer text-white clearfix small z-1" href="#">
                          <span class="float-left" data-toggle="collapse" data-target="#usuariosCourier">Ver Detalles</span>
                          <span class="float-right">
                            <i class="fas fa-angle-right" data-toggle="collapse" data-target="#usuariosCourier"></i>
                          </span>
                        </a>
                        <div id="usuariosCourier" class="collapse"><?php echo $MetadataCourier ?></div>
                      </div>
                    </div>
                  </div>                  
                </div>
                 <div class="card-footer small text-muted"></div>
              </div>


              <div class="row">
                <div class="col-lg-8">
                  <div class="card mb-3">
                    <div class="card-header">
                      <i class="fas fa-chart-bar"></i>
                      An&aacute;lisis de Tiempo</div>
                    <div class="card-body">
                      <canvas id="myBarChart" width="100%" height="50"></canvas>
                    </div>
                    <div class="card-footer small text-muted">An&aacute;lisis de Tiempo entre los datos de Llegada y Datos de Inicios; Y entre los Datos de Inicio y datos de Finalizaci&oacute;n(Seguridad)</div>
                  </div>
                </div>


                <div class="col-lg-4">
                  <div class="card mb-3">
                    <div class="card-header">
                      <i class="fas fa-chart-pie"></i>
                      Datos Registrados</div>
                    <div class="card-body">
                      <li><strong>Llegada</strong> = <?php echo $courier_dato_llegada?></li>
                      <li><strong>Inicio</strong> = <?php echo $courier_dato_inicio?></li>
                      <li><strong>Diferencia</strong> = <?php echo $Diferencia1_Horas?> (h:m:s)</li>
                      <hr>
                      <li><strong>Inicio</strong> = <?php echo $courier_dato_inicio?></li>
                      <li><strong>Finalizacion</strong> = <?php echo $courier_dato_fin?></li>
                      <li><strong>Diferencia</strong> = <?php echo $Diferencia2_Horas?> (h:m:s)</li>
                    </div>
                    <div class="card-footer small text-muted">An&aacute;lisis de Tiempos</div>
                  </div>
                </div>
              </div>
            </div>
            <!-- /.container-fluid -->
          </div>
          <!-- /.content-wrapper -->
  </div>

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
</body>

</html>

<script type="text/javascript">
//Analisis de Barras
  // Set new default font family and font color to mimic Bootstrap's default styling
  Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
  Chart.defaults.global.defaultFontColor = '#292b2c';

  // Bar Chart Example
  var ctx = document.getElementById("myBarChart");
  var myLineChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ["Llegada a Inicio - <?php echo $Diferencia1_Horas ?> Horas", "Inicio a Finalizacion <?php echo $Diferencia2_Horas ?>"],
      datasets: [{
        label: "Minutos=",
        backgroundColor: "rgba(2,117,216,1)",
        borderColor: "rgba(2,117,216,1)",
        data: [<?php echo $Diferencia1_Minutos ?>,<?php echo $Diferencia2_Minutos ?>],
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
            max: <?php echo $MayorValor+50 ?>,
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