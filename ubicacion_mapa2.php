<?php
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
$evento_boton="";
$contenido1="";
$contenido2="";
$j="";
$imagen="";
$id_posiciones="";
$errores=false;
if(isset($_REQUEST['evento']))
	$evento=$_REQUEST['evento'];
else
	$evento="consulta";
	
switch ($evento){
	case "consulta":
		$destino_boton="ubicacion_celda.php";
		$mensaja_global="Seleccione la ubicacion que desea <br /><strong>CONSULTAR</strong>";
	break;
	
	case "insertar":
		$destino_boton="ubicacion_guardar.php";
		//capturamos datos de la guia a ubicar.
		if(isset($_REQUEST['id_guia']))
			$id_guia=$_REQUEST['id_guia'];
		else
			$errores=true; 

		if(isset($_REQUEST['piezas']))
			$piezas=$_REQUEST['piezas'];
		else
			$errores=true;
			
		if(isset($_REQUEST['peso']))
			$peso=$_REQUEST['peso'];
		else
			$errores=true;

		if(isset($_REQUEST['observaciones']))
			$observaciones=$_REQUEST['observaciones'];
		else
			$errores=true;

		if(isset($_REQUEST['fondo']))
			$fondo=$_REQUEST['fondo'];
		else
			$fondo="N";
		
		if(isset($_REQUEST['retorno']))
			$retorno=$_REQUEST['retorno'];
		else
			$retorno="ubicacion_ubicar.php";			

		if(isset($_REQUEST['id_vuelo']))
			$id_vuelo=$_REQUEST['id_vuelo'];
		else
			$id_vuelo="";	

		$evento_boton="&id_guia=$id_guia&peso_ubicar=$peso&piezas_ubicar=$piezas&observaciones_ubicar=$observaciones&fondo=$fondo&retorno=$retorno&id_vuelo=$id_vuelo";
		$mensaja_global="Seleccione la ubicacion a la que <br /><strong>INGRESARA</strong> la gu&iacute;a";	
	break;
	
	case "mover":
		if(isset($_REQUEST['celda']))
			$celda=$_REQUEST['celda'];
		else
			$errores=true;
			
		if(isset($_REQUEST['msg']))
			$msg=$_REQUEST['msg'];
		else
			$errores=true;

		if(isset($_REQUEST['id_guia']))
			$id_guia=$_REQUEST['id_guia'];
		else
			$errores=true;
		$destino_boton="ubicacion_mover.php";
		$evento_boton="&id_guia=$id_guia&msg=$msg&celda=$celda";
		$mensaja_global="Seleccione la ubicacion a la que <br /><strong>MOVERA</strong> la gu&iacute;a";
	break;
	
	default:
		$destino_boton="ubicacion_celda.php";
		$mensaja_global="Seleccione la ubicacion que desea <br /><strong>CONSULTAR</strong>";
	break;
}

if ($errores==true)
{
	echo "
		<script language=\"javascript\">
			alert(\"Error al comunicarse con el Servidor, Intente nuevamente\");
			document.location='ubicacion1.php';
		</script>";
	exit();
}



function estado_posicion ($identificador)
{
	global $conexion, $accion, $imagen, $id_posiciones;
	$sql="SELECT id,id_guia FROM posicion_carga WHERE id_posicion='$identificador'";
	$consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
	$nfilas=mysql_num_rows($consulta); 
	if ($nfilas == 0)
	{
		return array("");
	}
	else
	{
		if(isset($_REQUEST['id_guia']))
		{
			$id_guia_solicitada=$_REQUEST['id_guia'];
			//Cuando realizan la consulta por numero de guia, buscamos las coincidencias para cambiar el icono.
			while($fila=mysql_fetch_array($consulta))
				{
					$id_guia_almacenada=$fila['id_guia'];
					if ($id_guia_solicitada == $id_guia_almacenada)
					{
						//Si existe la guia solicitada en esta posicion, señalara la posicion con oro icono
						return array("<img src=\"imagenes/btn_verde.gif\" width=\"20\" height=\"20\"/>");
						break;
					}
				}
			//Si existen guias en esta posicion, pero no la solicitada, deja el icono original
			return array("<img src=\"imagenes/caja.png\" width=\"10\" height=\"14\"/>");
		}
		else
		{
			//Cuanso solicitan el mapa, pero no una ubicacion especifica
			return array("<img src=\"imagenes/caja.png\" width=\"10\" height=\"14\"/>");
		}
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style type="text/css">
<!--
.ISLA {
	font-size: 36px;
}
.SECCIONES {
	font-size: 9px;
}
-->
.button {
	height:55px;
	width:38px;
	padding:10px;
}

.titulo_seccion_tunel{
	height:17px;
}
.celda_tunel{
	height:69px;
	background-color:#699;
}

.boton_amplio{
	height:130px; 
	width:130px;
}

.seccion_cuartofrio{
	background-color:#09F;
}

.seccion_import{
	background-color:#999966;
}
</style>
</head>
<body>
<?php 
include ("menu.php"); 
//Privilegios Consultar Todo el Modulo
$id_objeto=85; 
include("config/provilegios_modulo.php");  
//---------------------------

?>
<p class="titulo_tab_principal">Mapa de Bodega 4</p>
<br />
<table width="700" align="center">
  <tr>
    <td align="center" class="celda_tabla_principal">
    	<?php echo $mensaja_global; ?><br />
        <table width="700" align="center">
          <tr>
            <td align="center" class="celda_tabla_principal">
            	<button type="button" class="button" onclick="document.location='ubicacion_mapa.php'">
                	<img src="imagenes/home-act.png" height="15" />
                </button>
                <img src="imagenes/menu_next.png" align="absmiddle"/> BODEGA 2
            </td>
            <td align="center" class="celda_tabla_principal"><img src="imagenes/caja.png" width="31" height="27" align="absmiddle" /> CON OTRA CARGA</td>
            <td align="center" class="celda_tabla_principal"><img src="imagenes/btn_verde.gif" width="13" height="13" /> CON LA MISMA GUIA</td>
          </tr>
        </table>
    </td>
  </tr>
</table>
<br />
<p>
<?php 
$j=1530;
?>
<table width="50%" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
  <td class="ISLA">J</td>
    <!-- Seccion 1 - Acendente -->
    <td>
        <?php 
			//$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
        <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 1</td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
        </table>        
    </td>
    <!-- Seccion 2 - Acendente -->    
    <td>
        <?php 
			$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
        <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 2</td>
          </tr>
          <tr>
            <td align="center">
<button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
              </button>
            </td>
            <td align="center">
<button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
              </button>
            </td>
          </tr>
          <tr>
            <td align="center">
<button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
              </button>
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">
<button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
              </button>
            </td>
            <td align="center">
<button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
              </button>
            </td>
          </tr>
          <tr>
            <td align="center">
<button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
              </button>
            </td>
            <td align="center">
<button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
              </button>
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
<button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
              </button>
            </td>
          </tr>
        </table>    
    </td>
    <!-- Seccion 3 - Acendente -->    
    <td>
        <?php 
			$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
        <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 3</td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
        </table>        
    </td>
    <!-- Seccion 4 - Acendente -->    
    <td>
        <?php 
			$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
        <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 4</td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
        </table>        
    </td>
    <!-- Seccion 5 - Acendente -->    
    <td>
        <?php 
			$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
        <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 5</td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
        </table>        
    </td>
    <!-- Seccion 6 - Acendente -->    
    <td>
        <?php 
			$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
        <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 6</td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
        </table>        
    </td>
    <!-- Seccion 7 - Acendente -->
    <td>
        <?php 
			$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
        <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 7</td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
        </table>        
    </td>
    <!-- Seccion 8 - Acendente -->
    <td>
        <?php 
			$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
        <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 8</td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
        </table>        
    </td>
    <!-- Seccion 9 - Acendente -->
    <td> 
        <?php 
			$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
        <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 9</td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
        </table>        
    </td>
    <!-- Seccion 10 - Acendente -->
    <td>
        <?php 
			$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
        <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 10</td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
        </table>        
    </td>    
    <td class="ISLA">J</td>
  </tr>
</table>
</p>

<?php 
$j=1739;
?>
<p>
<table width="50%" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td class="ISLA">K</td>
    <!-- Seccion 10 - Decendente -->
    <td>
       <?php 
			//$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
       <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 10</td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >    
                <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
          </tr>
          <tr>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">           
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">     
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
           </td>
          </tr>
          <tr>
            <td colspan="2" align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
      </table>    
    </td>
    <!-- Seccion 9 - Decendente -->
    <td>
       <?php 
			//$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
       <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 9</td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >    
                <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
          </tr>
          <tr>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">           
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">     
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
           </td>
          </tr>
          <tr>
            <td colspan="2" align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
      </table>    
    </td>
    <!-- Seccion 8 - Decendente -->
    <td>
       <?php 
			//$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
       <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 8</td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >    
                <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
          </tr>
          <tr>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">           
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">     
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
           </td>
          </tr>
          <tr>
            <td colspan="2" align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
      </table>    
    </td>
    <!-- Seccion 7 - Decendente -->
    <td>
       <?php 
			//$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
       <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 7</td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >    
                <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
          </tr>
          <tr>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">           
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">     
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
           </td>
          </tr>
          <tr>
            <td colspan="2" align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
      </table>    
    </td>
    <!-- Seccion 6 - Decendente -->
    <td>
       <?php 
			//$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
       <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 6</td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >    
                <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
          </tr>
          <tr>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">           
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">     
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
           </td>
          </tr>
          <tr>
            <td colspan="2" align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
      </table>    
    </td>
    <!-- Seccion 5 - Decendente -->    
    <td>
       <?php 
			//$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
       <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 5</td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >    
                <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
          </tr>
          <tr>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">           
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">     
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
           </td>
          </tr>
          <tr>
            <td colspan="2" align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
      </table>    
    </td>
    <!-- Seccion 4 - Decendente -->        
    <td>
       <?php 
			//$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
       <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 4</td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >    
                <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
          </tr>
          <tr>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">           
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">     
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
           </td>
          </tr>
          <tr>
            <td colspan="2" align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
      </table>    
    </td>
    <!-- Seccion 3 - Decendente -->        
    <td>
       <?php 
			//$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
       <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 3</td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >    
                <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
          </tr>
          <tr>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">           
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">     
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
           </td>
          </tr>
          <tr>
            <td colspan="2" align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
      </table>    
    </td>
    <!-- Seccion 2 - Decendente -->            
    <td>
       <?php 
			//$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
       <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 2</td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >    
                <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
          </tr>
          <tr>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">           
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">     
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
           </td>
          </tr>
          <tr>
            <td colspan="2" align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
      </table>    
    </td>
    <!-- Seccion 1 - Decendente -->                
    <td>
       <?php 
			//$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
       <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 1</td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >    
                <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
          </tr>
          <tr>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">           
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">     
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
           </td>
          </tr>
          <tr>
            <td colspan="2" align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
      </table>
	</td>
    <td class="ISLA">K</td>
  </tr>
</table>
</p>
<?php 
$j=1750;
?>
<p>
<table width="50%" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
  <td class="ISLA">L</td>
    <td>
        <?php 
			//$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
        <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 1</td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
        </table>        
    </td>
    <td>
        <?php 
			$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
        <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 2</td>
          </tr>
          <tr>
            <td align="center">
<button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
              </button>
            </td>
            <td align="center">
<button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
              </button>
            </td>
          </tr>
          <tr>
            <td align="center">
<button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
              </button>
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">
<button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
              </button>
            </td>
            <td align="center">
<button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
              </button>
            </td>
          </tr>
          <tr>
            <td align="center">
<button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
              </button>
            </td>
            <td align="center">
<button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
              </button>
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
<button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
              </button>
            </td>
          </tr>
        </table>    
    </td>
    <td>
        <?php 
			$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
        <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 3</td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
        </table>        
    </td>
    <td>
        <?php 
			$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
        <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 4</td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
        </table>        
    </td>
    <td>
        <?php 
			$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
        <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 5</td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
        </table>        
    </td>
    <td>
        <?php 
			$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
        <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 6</td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
        </table>        
    </td>
    <!-- Seccion 7 - Acendente -->
    <td>
        <?php 
			$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
        <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 7</td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
        </table>        
    </td>
    <!-- Seccion 8 - Acendente -->
    <td>
        <?php 
			$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
        <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 8</td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>

            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
        </table>        
    </td>
    <!-- Seccion 9 - Acendente -->
    <td> 
        <?php 
			$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
        <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 9</td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
        </table>        
    </td>
    <!-- Seccion 10 - Acendente -->
    <td>
        <?php 
			$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
        <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 10</td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
        </table>        
    </td>    
    <td class="ISLA">L</td>
  </tr>
</table>
</p>
<?php 
	$j=1959;
?>
<p>
<table width="50%" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td class="ISLA">M</td>
    <!-- Seccion 10 - Decendente -->
    <td>
       <?php 
			//$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
       <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 10</td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >    
                <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
          </tr>
          <tr>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">           
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">     
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
           </td>
          </tr>
          <tr>
            <td colspan="2" align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
      </table>    
    </td>
    <!-- Seccion 9 - Decendente -->
    <td>
       <?php 
			//$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
       <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 9</td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >    
                <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
          </tr>
          <tr>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">           
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">     
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
           </td>
          </tr>
          <tr>
            <td colspan="2" align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
      </table>    
    </td>
    <!-- Seccion 8 - Decendente -->
    <td>
       <?php 
			//$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
       <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 8</td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >    
                <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
          </tr>
          <tr>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">           
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">     
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
           </td>
          </tr>
          <tr>
            <td colspan="2" align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
      </table>    
    </td>
    <!-- Seccion 7 - Decendente -->
    <td>
       <?php 
			//$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
       <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 7</td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >    
                <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
          </tr>
          <tr>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">           
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">     
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
           </td>
          </tr>
          <tr>
            <td colspan="2" align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
      </table>    
    </td>    
    <td>
       <?php 
			//$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
       <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 6</td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >    
                <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
          </tr>
          <tr>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">           
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">     
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
           </td>
          </tr>
          <tr>
            <td colspan="2" align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
      </table>    
    </td>
    <td>
       <?php 
			//$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
       <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 5</td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >    
                <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
          </tr>
          <tr>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">           
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">     
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
           </td>
          </tr>
          <tr>
            <td colspan="2" align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
      </table>    
    </td>
    <td>
       <?php 
			//$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
       <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 4</td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >    
                <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
          </tr>
          <tr>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">           
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">     
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
           </td>
          </tr>
          <tr>
            <td colspan="2" align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
      </table>    
    </td>
    <td>
       <?php 
			//$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
       <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 3</td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >    
                <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
          </tr>
          <tr>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">           
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">     
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
           </td>
          </tr>
          <tr>
            <td colspan="2" align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
      </table>    
    </td>
    <td>
       <?php 
			//$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
       <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 2</td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >    
                <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
          </tr>
          <tr>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">           
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">     
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
           </td>
          </tr>
          <tr>
            <td colspan="2" align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
      </table>    
    </td>
    <td>
       <?php 
			//$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
       <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 1</td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >    
                <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
          </tr>
          <tr>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">           
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">     
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
           </td>
          </tr>
          <tr>
            <td colspan="2" align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
      </table>
	</td>
    <td class="ISLA">M</td>
  </tr>
</table>
</p>
<?php 
$j=1970;
?>
<p>
<table width="50%" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
  <td class="ISLA">N</td>
    <td>
        <?php 
			//$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
        <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 1</td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
        </table>        
    </td>
    <td>
        <?php 
			$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
        <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 2</td>
          </tr>
          <tr>
            <td align="center">
<button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
              </button>
            </td>
            <td align="center">
<button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
              </button>
            </td>
          </tr>
          <tr>
            <td align="center">
<button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
              </button>
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">
<button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
              </button>
            </td>
            <td align="center">
<button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
              </button>
            </td>
          </tr>
          <tr>
            <td align="center">
<button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
              </button>
            </td>
            <td align="center">
<button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
              </button>
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
<button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>

              </button>
            </td>
          </tr>
        </table>    
    </td>
    <td>
        <?php 
			$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
        <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 3</td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
        </table>        
    </td>
    <td>
        <?php 
			$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
        <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 4</td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
        </table>        
    </td>
    <td>
        <?php 
			$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
        <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 5</td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
        </table>        
    </td>
    <td>
        <?php 
			$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
        <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 6</td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
        </table>        
    </td>
    <!-- Seccion 7 - Acendente -->
    <td>
        <?php 
			$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
        <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 7</td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
        </table>        
    </td>
    <!-- Seccion 8 - Acendente -->
    <td>
        <?php 
			$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
        <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 8</td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>

            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
        </table>        
    </td>
    <!-- Seccion 9 - Acendente -->
    <td> 
        <?php 
			$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
        <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 9</td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
        </table>        
    </td>
    <!-- Seccion 10 - Acendente -->
    <td>
        <?php 
			$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
        <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 10</td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
        </table>        
    </td>        
    <td class="ISLA">N</td>
  </tr>  
</table>
</p>
<?php 
	$j=2179;
?>
<p>
<table width="50%" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td class="ISLA">O</td>
    <!-- Seccion 10 - Decendente -->
    <td>
       <?php 
			//$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
       <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 10</td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >    
                <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
          </tr>
          <tr>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">           
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">     
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
           </td>
          </tr>
          <tr>
            <td colspan="2" align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
      </table>    
    </td>
    <!-- Seccion 9 - Decendente -->
    <td>
       <?php 
			//$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
       <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 9</td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >    
                <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
          </tr>
          <tr>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">           
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">     
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
           </td>
          </tr>
          <tr>
            <td colspan="2" align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
      </table>    
    </td>
    <!-- Seccion 8 - Decendente -->
    <td>
       <?php 
			//$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
       <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 8</td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >    
                <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
          </tr>
          <tr>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">           
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">     
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
           </td>
          </tr>
          <tr>
            <td colspan="2" align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
      </table>    
    </td>
    <!-- Seccion 7 - Decendente -->
    <td>
       <?php 
			//$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
       <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 7</td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >    
                <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
          </tr>
          <tr>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">           
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">     
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
           </td>
          </tr>
          <tr>
            <td colspan="2" align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
      </table>    
    </td>
    
    <td>
       <?php 
			//$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
       <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 6</td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >    
                <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
          </tr>
          <tr>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">           
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">     
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
           </td>
          </tr>
          <tr>
            <td colspan="2" align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
      </table>    
    </td>
    <td>
       <?php 
			//$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
       <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 5</td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >    
                <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
          </tr>
          <tr>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">           
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">     
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
           </td>
          </tr>
          <tr>
            <td colspan="2" align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
      </table>    
    </td>
    <td>
       <?php 
			//$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
       <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 4</td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >    
                <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
          </tr>
          <tr>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">           
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">     
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
           </td>
          </tr>
          <tr>
            <td colspan="2" align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
      </table>    
    </td>
    <td>
       <?php 
			//$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
       <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 3</td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >    
                <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
          </tr>
          <tr>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">           
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">     
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
           </td>
          </tr>
          <tr>
            <td colspan="2" align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
      </table>    
    </td>
    <td>
       <?php 
			//$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
       <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 2</td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >    
                <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
          </tr>
          <tr>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">           
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">     
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
           </td>
          </tr>
          <tr>
            <td colspan="2" align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
      </table>    
    </td>
    <td>
       <?php 
			//$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
       <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 1</td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >    
                <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
          </tr>
          <tr>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">           
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">     
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
           </td>
          </tr>
          <tr>
            <td colspan="2" align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
      </table>
	</td>
    <td class="ISLA">O</td>
  </tr>
</table>
</p>
<?php 
$j=2190;
?>
<p>
<table width="50%" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
  <td class="ISLA">P</td>
    <td>
        <?php 
			//$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
        <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 1</td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
        </table>        
    </td>
    <td>
        <?php 
			$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
        <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 2</td>
          </tr>
          <tr>
            <td align="center">
<button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
              </button>
            </td>
            <td align="center">
<button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
              </button>
            </td>
          </tr>
          <tr>
            <td align="center">
<button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
              </button>
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">
<button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
              </button>
            </td>
            <td align="center">
<button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
              </button>
            </td>
          </tr>
          <tr>
            <td align="center">
<button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
              </button>
            </td>
            <td align="center">
<button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
              </button>
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
<button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>


              </button>
            </td>
          </tr>
        </table>    
    </td>
    <td>
        <?php 
			$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
        <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 3</td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
        </table>        
    </td>
    <td>
        <?php 
			$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
        <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 4</td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
        </table>        
    </td>
    <td>
        <?php 
			$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
        <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 5</td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
        </table>        
    </td>
    <td>
        <?php 
			$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
        <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 6</td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
        </table>        
    </td>
    <!-- Seccion 7 - Acendente -->
    <td>
        <?php 
			$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
        <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 7</td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
        </table>        
    </td>
    <!-- Seccion 8 - Acendente -->
    <td>
        <?php 
			$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
        <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 8</td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>

            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
        </table>        
    </td>
    <!-- Seccion 9 - Acendente -->
    <td> 
        <?php 
			$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
        <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 9</td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
        </table>        
    </td>
    <!-- Seccion 10 - Acendente -->
    <td>
        <?php 
			$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
        <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 10</td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
        </table>        
    </td>        
    <td class="ISLA">P</td>
  </tr>
</table>
</p>
<?php 
	$j=2399;
?>
<p>
<table width="50%" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td class="ISLA">Q</td>
    <!-- Seccion 10 - Decendente -->
    <td>
       <?php 
			//$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
       <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 10</td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >    
                <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
          </tr>
          <tr>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">           
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">     
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
           </td>
          </tr>
          <tr>
            <td colspan="2" align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
      </table>    
    </td>
    <!-- Seccion 9 - Decendente -->
    <td>
       <?php 
			//$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
       <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 9</td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >    
                <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
          </tr>
          <tr>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">           
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">     
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
           </td>
          </tr>
          <tr>
            <td colspan="2" align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
      </table>    
    </td>
    <!-- Seccion 8 - Decendente -->
    <td>
       <?php 
			//$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
       <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 8</td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >    
                <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
          </tr>
          <tr>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">           
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">     
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
           </td>
          </tr>
          <tr>
            <td colspan="2" align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
      </table>    
    </td>
    <!-- Seccion 7 - Decendente -->
    <td>
       <?php 
			//$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
       <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 7</td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >    
                <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
          </tr>
          <tr>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">           
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">     
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
           </td>
          </tr>
          <tr>
            <td colspan="2" align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
      </table>    
    </td>    
    <td>
    <!-- Seccion 6 - Decendente -->
       <?php 
			//$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
       <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 6</td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >    
                <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
          </tr>
          <tr>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">           
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">     
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
           </td>
          </tr>
          <tr>
            <td colspan="2" align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
      </table>    
    </td>
    <td>
    <!-- Seccion 5 - Decendente -->
       <?php 
			//$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
       <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 5</td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >    
                <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
          </tr>
          <tr>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">           
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">     
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
           </td>
          </tr>
          <tr>
            <td colspan="2" align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
      </table>    
    </td>
    <td>
    <!-- Seccion 4 - Decendente -->
       <?php 
			//$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
       <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 4</td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >    
                <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
          </tr>
          <tr>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">           
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">     
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
           </td>
          </tr>
          <tr>
            <td colspan="2" align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
      </table>    
    </td>
    <td>
    <!-- Seccion 3 - Decendente -->
       <?php 
			//$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
       <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 3</td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >    
                <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
          </tr>
          <tr>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">           
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">     
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
           </td>
          </tr>
          <tr>
            <td colspan="2" align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
      </table>    
    </td>
    <td>
    <!-- Seccion 2 - Decendente -->
       <?php 
			//$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
       <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 2</td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >    
                <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
          </tr>
          <tr>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">           
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">     
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
           </td>
          </tr>
          <tr>
            <td colspan="2" align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
      </table>    
    </td>
    <td>
    <!-- Seccion 1 - Decendente -->
       <?php 
			//$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
       <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 1</td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >    
                <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
          </tr>
          <tr>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">           
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">     
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
           </td>
          </tr>
          <tr>
            <td colspan="2" align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
      </table>
	</td>
    <td class="ISLA">Q</td>
  </tr>
</table>
</p>
<?php 
$j=2410;
?>
<p>
<table width="50%" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
  <td class="ISLA">R</td>
    <!-- Seccion 1 - Acendente -->    
    <td>
        <?php 
			//$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
        <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 1</td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
        </table>        
    </td>
    <!-- Seccion 2 - Acendente -->    
    <td>
        <?php 
			$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
        <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 2</td>
          </tr>
          <tr>
            <td align="center">
<button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
              </button>
            </td>
            <td align="center">
<button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
              </button>
            </td>
          </tr>
          <tr>
            <td align="center">
<button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
              </button>
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">
<button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
              </button>
            </td>
            <td align="center">
<button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
              </button>
            </td>
          </tr>
          <tr>
            <td align="center">
<button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
              </button>
            </td>
            <td align="center">
<button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
              </button>
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
<button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>



              </button>
            </td>
          </tr>
        </table>    
    </td>
    <!-- Seccion 3 - Acendente -->    
    <td>
        <?php 
			$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
        <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 3</td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
        </table>        
    </td>
    <!-- Seccion 4 - Acendente -->    
    <td>
        <?php 
			$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
        <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 4</td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>

                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
        </table>        
    </td>
    <!-- Seccion 5 - Acendente -->    
    <td>
        <?php 
			$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
        <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 5</td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
        </table>        
    </td>
    <!-- Seccion 6 - Acendente -->    
    <td>
        <?php 
			$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
        <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 6</td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
        </table>        
    </td>
    <!-- Seccion 7 - Acendente -->
    <td>
        <?php 
			$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
        <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 7</td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
        </table>        
    </td>
    <!-- Seccion 8 - Acendente -->
    <td>
        <?php 
			$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
        <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 8</td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>

            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
        </table>        
    </td>
    <!-- Seccion 9 - Acendente -->
    <td> 
        <?php 
			$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
        <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 9</td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
        </table>        
    </td>
    <!-- Seccion 10 - Acendente -->
    <td>
        <?php 
			$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
        <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 10</td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
        </table>        
    </td>        
    <td class="ISLA">R</td>
  </tr>
</table>

<?php 
$j=2520;
?>
<p>
<table width="50%" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
  <td class="ISLA">S</td>
    <td>
    <!-- Seccion 1 - Acendente -->    
        <?php 
			//$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
        <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 1</td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
        </table>        
    </td>
    <!-- Seccion 2 - Acendente -->    
    <td>
        <?php 
			$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
        <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 2</td>
          </tr>
          <tr>
            <td align="center">
<button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
              </button>
            </td>
            <td align="center">
<button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
              </button>
            </td>
          </tr>
          <tr>
            <td align="center">
<button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
              </button>
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">
<button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
              </button>
            </td>
            <td align="center">
<button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
              </button>
            </td>
          </tr>
          <tr>
            <td align="center">
<button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
              </button>
            </td>
            <td align="center">
<button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
              </button>
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
<button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>



              </button>
            </td>
          </tr>
        </table>    
    </td>
    <!-- Seccion 3 - Acendente -->    
    <td>
        <?php 
			$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
        <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 3</td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
        </table>        
    </td>
    <!-- Seccion 4 - Acendente -->    
    <td>
        <?php 
			$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
        <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 4</td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>

                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
        </table>        
    </td>
    <!-- Seccion 5 - Acendente -->    
   <td>
        <?php 
			$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
        <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 5</td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
        </table>        
    </td>
    <!-- Seccion 6 - Acendente -->    
    <td>
        <?php 
			$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
        <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 6</td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
        </table>        
    </td>
    <!-- Seccion 7 - Acendente -->    
    <td>
        <?php 
			$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
        <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 7</td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
        </table>        
    </td>
    <!-- Seccion 8 - Acendente -->    
    <td>
        <?php 
			$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
        <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 8</td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>

            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
        </table>        
    </td>
    <!-- Seccion 9 - Acendente -->    
    <td> 
        <?php 
			$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
        <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 9</td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
        </table>        
    </td>
    <!-- Seccion 10 - Acendente -->    
    <td>
        <?php 
			$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
        <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 10</td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
        </table>        
    </td>
    <!-- Seccion 11 - Acendente -->               
    <td>
        <?php 
			$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
        <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 11</td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
        </table>        
    </td>       
    <!-- Seccion 12 - Acendente -->    
    <td>
        <?php 
			$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
        <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 12</td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
        </table>        
    </td>       
    <!-- Seccion 13 - Acendente -->    
    <td>
        <?php 
			$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
        <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 13</td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
        </table>        
    </td>       
    <!-- Seccion 14 - Acendente -->    
    <td>
        <?php 
			$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
        <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 14</td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
        </table>        
    </td>       
    <!-- Seccion 15 - Acendente -->    
    <td>
        <?php 
			$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
        <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 15</td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
        </table>        
    </td>       
    <!-- Seccion 16 - Acendente -->    
    <td>
        <?php 
			$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
        <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 16</td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
        </table>        
    </td>       
    <!-- Seccion 17 - Acendente -->    
    <td>
        <?php 
			$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
        <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 17</td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
        </table>        
    </td>       
     <!-- Seccion 18 - Acendente -->    
   <td>
        <?php 
			$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
        <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 18</td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
        </table>        
    </td>       
    <!-- Seccion 19 - Acendente -->    
    <td>
        <?php 
			$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
        <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 19</td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
        </table>        
    </td>       
    <!-- Seccion 20 - Acendente -->    
    <td>
        <?php 
			$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
        <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 20</td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
        </table>        
    </td>           
    <td class="ISLA">S</td>
  </tr>
</table>


<?php 
$j=2748;
?>
<p>
<table width="50%" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
  <td class="ISLA">T</td>
    <td>
    <!-- Seccion 1 - Acendente -->    
        <?php 
			//$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
        <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 1</td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
        </table>        
    </td>
    <!-- Seccion 2 - Acendente -->    
    <td>
        <?php 
			$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
        <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 2</td>
          </tr>
          <tr>
            <td align="center">
<button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
              </button>
            </td>
            <td align="center">
<button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
              </button>
            </td>
          </tr>
          <tr>
            <td align="center">
<button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
              </button>
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">
<button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
              </button>
            </td>
            <td align="center">
<button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
              </button>
            </td>
          </tr>
          <tr>
            <td align="center">
<button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
              </button>
            </td>
            <td align="center">
<button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
              </button>
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
<button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>



              </button>
            </td>
          </tr>
        </table>    
    </td>
    <!-- Seccion 3 - Acendente -->    
    <td>
        <?php 
			$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
        <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 3</td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
        </table>        
    </td>
    <!-- Seccion 4 - Acendente -->    
    <td>
        <?php 
			$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
        <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 4</td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>

                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
        </table>        
    </td>
    <!-- Seccion 5 - Acendente -->    
   <td>
        <?php 
			$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
        <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 5</td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
        </table>        
    </td>
    <!-- Seccion 6 - Acendente -->    
    <td>
        <?php 
			$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
        <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 6</td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
        </table>        
    </td>
    <!-- Seccion 7 - Acendente -->    
    <td>
        <?php 
			$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
        <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 7</td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
        </table>        
    </td>
    <!-- Seccion 8 - Acendente -->    
    <td>
        <?php 
			$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
        <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 8</td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>

            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
        </table>        
    </td>
    <!-- Seccion 9 - Acendente -->    
    <td> 
        <?php 
			$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
        <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 9</td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
        </table>        
    </td>
    <!-- Seccion 10 - Acendente -->    
    <td>
        <?php 
			$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
        <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 10</td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
        </table>        
    </td>
    <!-- Seccion 11 - Acendente -->               
    <td>
        <?php 
			$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
        <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 11</td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
        </table>        
    </td>       
    <!-- Seccion 12 - Acendente -->    
    <td>
        <?php 
			$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
        <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 12</td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
        </table>        
    </td>       
    <!-- Seccion 13 - Acendente -->    
    <td>
        <?php 
			$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
        <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 13</td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
        </table>        
    </td>       
    <!-- Seccion 14 - Acendente -->    
    <td>
        <?php 
			$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
        <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 14</td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
        </table>        
    </td>       
    <!-- Seccion 15 - Acendente -->    
    <td>
        <?php 
			$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
        <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 15</td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
        </table>        
    </td>       
    <!-- Seccion 16 - Acendente -->    
    <td>
        <?php 
			$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
        <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 16</td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
        </table>        
    </td>       
    <!-- Seccion 17 - Acendente -->    
    <td>
        <?php 
			$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
        <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 17</td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
        </table>        
    </td>       
     <!-- Seccion 18 - Acendente -->    
   <td>
        <?php 
			$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
        <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 18</td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
        </table>        
    </td>       
    <!-- Seccion 19 - Acendente -->    
    <td>
        <?php 
			$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
        <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 19</td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
        </table>        
    </td>       
    <!-- Seccion 20 - Acendente -->    
    <td>
        <?php 
			$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
        <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 20</td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
              <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
                  <?php
                            list ($contenido1)=estado_posicion($j);
                            $j=$j-1;
                            echo "$contenido1 $contenido2"; 
                    ?>
                </button>        
            </td>
          </tr>
        </table>        
    </td>           
    <td class="ISLA">T</td>
  </tr>
</table>

</p>
<?php 
	$j=3178;
?>
<p>
<table width="50%" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td class="ISLA">U</td>
    <!-- Seccion 20 - Decendente -->
    <td>
       <?php 
			//$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
       <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 20</td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >    
                <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
          </tr>
          <tr>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">           
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">     
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
           </td>
          </tr>
          <tr>
            <td colspan="2" align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
      </table>    
    </td>    
    <!-- Seccion 19 - Decendente -->
    <td>
       <?php 
			//$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
       <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 19</td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >    
                <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
          </tr>
          <tr>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">           
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">     
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
           </td>
          </tr>
          <tr>
            <td colspan="2" align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
      </table>    
    </td>    
    <!-- Seccion 18 - Decendente -->
    <td>
       <?php 
			//$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
       <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 18</td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >    
                <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
          </tr>
          <tr>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">           
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">     
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
           </td>
          </tr>
          <tr>
            <td colspan="2" align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
      </table>    
    </td>    
    <!-- Seccion 17 - Decendente -->
    <td>
       <?php 
			//$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
       <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 17</td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >    
                <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
          </tr>
          <tr>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">           
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">     
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
           </td>
          </tr>
          <tr>
            <td colspan="2" align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
      </table>    
    </td>    
    <!-- Seccion 16 - Decendente -->
    <td>
       <?php 
			//$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
       <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 16</td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >    
                <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
          </tr>
          <tr>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">           
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">     
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
           </td>
          </tr>
          <tr>
            <td colspan="2" align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
      </table>    
    </td>    
    <!-- Seccion 15 - Decendente -->
    <td>
       <?php 
			//$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
       <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 15</td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >    
                <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
          </tr>
          <tr>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">           
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">     
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
           </td>
          </tr>
          <tr>
            <td colspan="2" align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
      </table>    
    </td>
    <!-- Seccion 14 - Decendente -->
    <td>
       <?php 
			//$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
       <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 14</td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >    
                <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
          </tr>
          <tr>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">           
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">     
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
           </td>
          </tr>
          <tr>
            <td colspan="2" align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
      </table>    
    </td>
    <!-- Seccion 13 - Decendente -->
    <td>
       <?php 
			//$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
       <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 13</td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >    
                <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
          </tr>
          <tr>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">           
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">     
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
           </td>
          </tr>
          <tr>
            <td colspan="2" align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
      </table>    
    </td>    
    <!-- Seccion 12 - Decendente -->
    <td>
       <?php 
			//$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
       <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 12</td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >    
                <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
          </tr>
          <tr>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">           
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">     
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
           </td>
          </tr>
          <tr>
            <td colspan="2" align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
      </table>    
    </td>    
    <!-- Seccion 11 - Decendente -->
    <td>
       <?php 
			//$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
       <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 11</td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >    
                <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
          </tr>
          <tr>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">           
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">     
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
           </td>
          </tr>
          <tr>
            <td colspan="2" align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
      </table>    
    </td>    
    <!-- Seccion 10 - Decendente -->
    <td>
       <?php 
			//$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
       <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 10</td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >    
                <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
          </tr>
          <tr>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">           
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">     
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
           </td>
          </tr>
          <tr>
            <td colspan="2" align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
      </table>    
    </td>
    <!-- Seccion 9 - Decendente -->
    <td>
       <?php 
			//$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
       <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 9</td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >    
                <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
          </tr>
          <tr>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">           
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">     
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
           </td>
          </tr>
          <tr>
            <td colspan="2" align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
      </table>    
    </td>
    <!-- Seccion 8 - Decendente -->
    <td>
       <?php 
			//$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
       <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 8</td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >    
                <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
          </tr>
          <tr>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">           
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">     
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
           </td>
          </tr>
          <tr>
            <td colspan="2" align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
      </table>    
    </td>
    <!-- Seccion 7 - Decendente -->
    <td>
       <?php 
			//$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
       <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 7</td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >    
                <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
          </tr>
          <tr>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">           
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">     
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
           </td>
          </tr>
          <tr>
            <td colspan="2" align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
      </table>    
    </td>    
    <td>
    <!-- Seccion 6 - Decendente -->
       <?php 
			//$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
       <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 6</td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >    
                <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
          </tr>
          <tr>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">           
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">     
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
           </td>
          </tr>
          <tr>
            <td colspan="2" align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
      </table>    
    </td>
    <td>
    <!-- Seccion 5 - Decendente -->
       <?php 
			//$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
       <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 5</td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >    
                <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
          </tr>
          <tr>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">           
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">     
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
           </td>
          </tr>
          <tr>
            <td colspan="2" align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
      </table>    
    </td>
    <td>
    <!-- Seccion 4 - Decendente -->
       <?php 
			//$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
       <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 4</td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >    
                <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
          </tr>
          <tr>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">           
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">     
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
           </td>
          </tr>
          <tr>
            <td colspan="2" align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
      </table>    
    </td>
    <td>
    <!-- Seccion 3 - Decendente -->
       <?php 
			//$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
       <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 3</td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >    
                <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
          </tr>
          <tr>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">           
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">     
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
           </td>
          </tr>
          <tr>
            <td colspan="2" align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
      </table>    
    </td>
    <td>
    <!-- Seccion 2 - Decendente -->
       <?php 
			//$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
       <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 2</td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >    
                <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
          </tr>
          <tr>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">           
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">     
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
           </td>
          </tr>
          <tr>
            <td colspan="2" align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
      </table>    
    </td>
    <td>
    <!-- Seccion 1 - Decendente -->
       <?php 
			//$j = $j + 22; 
			$status= '';
			//$status= 'disabled="disabled"';
		?>
       <table width="56" height="160" border="1" cellspacing="0" cellpadding="6">
          <tr>
            <td colspan="2" align="center" class="SECCIONES">SECCION 1</td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>            
            </td>
          </tr>
          <tr>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
            <td align="center">
              <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >    
                <?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
              </button>
            </td>
          </tr>
          <tr>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
          </tr>
          <tr>
            <td align="center">           
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
            	</button>
            </td>
            <td align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
          <tr>
            <td align="center">
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
            <td align="center">     
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >

				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
           </td>
          </tr>
          <tr>
            <td colspan="2" align="center">            
                <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" <?php echo  $status ?> >
				<?php
                        list ($contenido1)=estado_posicion($j);
                        $j=$j-1;
                        echo "$contenido1 $contenido2"; 
                ?>
                </button>
            </td>
          </tr>
      </table>
	</td>
    <td class="ISLA">U</td>
  </tr>
</table>

</p>
<?php 
$j=2969;
?>
  <br />
<table border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td width="50" height="54" class="seccion_import"><strong>IM-7</strong></td>
    <td width="190" align="center" class="seccion_import">
    <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button" style="height:130px; width:130px;" >
    <?php
		list ($contenido1)=estado_posicion($j);
		$j=$j+1;
		echo "$contenido1 $contenido2"; 
    ?>
	</button>
   </td>
    <td width="50" align="center" class="seccion_import"><strong>IM-8</strong></td>
    <td width="190" align="center" class="seccion_import">
    <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button boton_amplio" >
    <?php
		list ($contenido1)=estado_posicion($j);
		$j=$j+1;
		echo "$contenido1 $contenido2"; 
    ?>
	</button>
    </td>
    <td width="50" align="center" class="seccion_import"><strong>IM-9</strong></td>
    <td width="190" align="center" class="seccion_import">
    <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button boton_amplio" >
    <?php
		list ($contenido1)=estado_posicion($j);
		$j=$j+1;
		echo "$contenido1 $contenido2"; 
	?>
	</button>    
    </td>
  </tr>
</table>
<br />
<table border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td width="50" height="54" class="seccion_import"><strong>IM-10</strong></td>
    <td width="190" align="center" class="seccion_import">
    <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button boton_amplio" >
    <?php
		list ($contenido1)=estado_posicion($j);
		$j=$j+1;
		echo "$contenido1 $contenido2"; 
	?>
	</button>    
    </td>
    <td width="50" align="center" class="seccion_import"><strong>IM-11</strong></td>
    <td width="190" align="center" class="seccion_import">
    <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button boton_amplio" >
    <?php
		list ($contenido1)=estado_posicion($j);
		$j=$j+1;
		echo "$contenido1 $contenido2"; 
	?>
	</button>
    </td>
    <td width="50" align="center" class="seccion_import"><strong>IM-12</strong></td>
    <td width="190" align="center" class="seccion_import">
    <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button boton_amplio" >
    <?php
		list ($contenido1)=estado_posicion($j);
		$j=$j+1;
		echo "$contenido1 $contenido2"; 
	?>
	</button>    
    </td>
  </tr>
</table>
<?php 
	$j=2736; 
?>
<br />
<table border="0" cellspacing="0" cellpadding="0" align="center">
      <td width="50" height="54" ><strong>PF-3</strong></td>
    <td width="190" align="center" >
        <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button boton_amplio">
        	<?php
            	list ($contenido1)=estado_posicion($j);
                $j=$j+1;
                echo "$contenido1 $contenido2"; 
            ?>
        </button>    
	</td>
    <td width="50" align="center"><strong>PF-4</strong></td>
    <td width="190" align="center">
    <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button boton_amplio">
    <?php
		list ($contenido1)=estado_posicion($j);
		$j=$j+1;
		echo "$contenido1 $contenido2"; 
	?>
	</button>        
    </td>
  </tr>
</table>
<br />
<p align="center"><strong>ATENCI&Oacute;N</strong><br />Los siguientes Cuartos Frios a los que se hace menci&oacute;n, son los que est&aacute;n ubicados en <strong>BODEGA 2</strong></p>
<?php 
	$j=1315; 
?>
<table border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td width="50" height="54" class="seccion_cuartofrio"><strong>CF-1</strong></td>
    <td width="190" align="center" class="seccion_cuartofrio">
        <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button boton_amplio">
        	<?php
				list ($contenido1)=estado_posicion($j);
				$j=$j+1;
				echo "$contenido1 $contenido2"; 
            ?>
        </button>    
	</td>
    <td width="50" align="center" class="seccion_cuartofrio"><strong>CF-2</strong></td>
    <td width="190" align="center" class="seccion_cuartofrio">
        <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button boton_amplio" >
        	<?php
                list ($contenido1)=estado_posicion($j);
                $j=$j+1;
                echo "$contenido1 $contenido2"; 
            ?>
        </button>    
	</td>
    <td width="50" align="center" class="seccion_cuartofrio"><strong>CF-3</strong></td>
    <td width="190" align="center" class="seccion_cuartofrio">    
        <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button boton_amplio" >
        	<?php
            	list ($contenido1)=estado_posicion($j);
                $j=$j+1;
                echo "$contenido1 $contenido2"; 
             ?>
        </button>    
	</td>   
  </tr>
</table>
<br />
<table border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td width="50" height="54" class="seccion_cuartofrio"><strong>CF-4</strong></td>
    <td width="190" align="center" class="seccion_cuartofrio">
        <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button boton_amplio">
        	<?php
            	list ($contenido1)=estado_posicion($j);
                $j=$j+1;
                echo "$contenido1 $contenido2"; 
            ?>
        </button>    
	</td>
    <td width="50" height="54" class="seccion_cuartofrio"><strong>CF-5</strong></td>
    <td width="190" align="center" class="seccion_cuartofrio">
        <button type="button" id="<?php echo "$j"?>" onclick="document.location='<?php echo "$destino_boton"?>?id_posicion=<?php echo "$j"?><?php echo "$evento_boton"?>'" class="button boton_amplio">
        	<?php
            	list ($contenido1)=estado_posicion($j);
                $j=$j+1;
                echo "$contenido1 $contenido2"; 
            ?>
        </button>    
	</td>
  </tr>
</table>

<?php $_SESSION["identificador_guia"]=""; ?>
<p>&nbsp;</p>
<script language="javascript">
	document.getElementById("cargando").innerHTML="";
</script>
</p>
</body>
</html>
