<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("../config/configuracion.php");
if(isset($_REQUEST['id_guia']) and isset($_REQUEST['tipo']))
{
	$tipo=$_REQUEST['tipo'];
	$id_guia=$_REQUEST['id_guia'];
	$sql="SELECT g.hija, v.nvuelo FROM guia g LEFT JOIN vuelo v ON g.id_vuelo = v.id WHERE g.id='$id_guia'";
	$consulta=mysql_query ($sql,$conexion) or die ("ERROR 1: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
	$fila=mysql_fetch_array($consulta);
	$impresion_imagenes="";
	$guia=$fila['hija'];
	switch($tipo)
	{
		case "foto_bodega":
			$mensaje="Bodega";
		break;
	
		case "foto_despacho":
			$mensaje="Despachos";
		break;
	
		case "foto_seguridad":
			$mensaje="Seguridad";
		break;
	}
}
else
{
	?>
    <script>
		alert('Error: El servidor no pudo obtener la informacion, intentelo de nuevo');
	</script>
    <?php
    exit();
}
?>
<!doctype html>
<html lang="en-us">
<head>
	<style>
        .titulo_add{
            color:#FFF;
        }
		.opaco_ie { 
 			filter: alpha(opacity=30);
		}
		img{
			 border-radius: 1em;
			 border-color: #FFF;
   			 border-width: 4px;
			 border-style: solid;
		}
    </style>	
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=9" />
  	<meta name="author" content="Lokesh Dhakar">
	<link rel="stylesheet" href="css/lightbox.css" type="text/css" media="screen" />
	<link href="../tema/estilo.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="../js/mootools-core-1.4.5-full-nocompat.js"></script>
	<script type="text/javascript" src="../js/mootools-more-1.4.0.1.js"></script>
	<script type="text/javascript">		
		function cargarImagen()
		{
			var cantidad_imagenes=0;
			var imagenes= new Array;
			<?php
				$cantidad=0;
				$sql="SELECT * FROM registro_fotografico WHERE id_guia='$id_guia' and seccion='$tipo'";
				$consulta=mysql_query ($sql,$conexion) or die ("ERROR 2: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
				$nfilas=mysql_num_rows($consulta);
				if ($nfilas > 0)
				{				
					while($fila=mysql_fetch_array($consulta))
					{
						echo "imagenes[".$cantidad."]='../fotos/mercancia/".$fila['nombre']."';\n";
						$impresion_imagenes .= ' <a href="../fotos/mercancia/'.$fila['nombre'].'" rel="lightbox[personal]" title="Nombre de la imagen: '.$fila['nombre'].'"><img src="../fotos/mercancia/'.$fila['nombre'].'" height="60px" width="80px"></a>';
						$cantidad++;
					}
					$cantidad--;
				}
				else
				{
					echo "imagenes[0]='../fotos/mercancia/sinfoto.jpg';\n";
					$impresion_imagenes = ' <a href="../fotos/mercancia/sinfoto.jpg" rel="lightbox[personal]" title="No existe registro fotografico"><img src="../fotos/mercancia/sinfoto.jpg" height="100px" width="100px"></a>';
				}
				
			?>
			var cantidad = <?php echo $cantidad; ?>; 		
			var totalimagenes=imagenes.length;
			var myImages = Asset.images(imagenes, 							   
			{
				onProgress: function(counter)
				{
					//Calculo de porcentaje cargado
					var porcentaje = (counter * 100)/ totalimagenes;
					document.getElementById("respuesta").setStyle("opacity",1);
					document.getElementById("respuesta").innerHTML='<p align="center"><img src="../imagenes/cargando.gif"> '+porcentaje+'%</p>';
									
				},
				onComplete: function()
				{
					document.getElementById("respuesta").setStyle("opacity",0);
				}
			});
		}
	</script>
</head>
<body>
	<div id="respuesta" class="opaco_ie" style="position:relative;opacity:0; background-image:url(../imagenes/background.png); width:98%; height:30px"></div>

	<div id="imagen_seleccionada" style="position:relative;opacity:1; background-image:url(../imagenes/background.png);  width:98%; height:100px">
        <p class="titulo_tab_principal">Registro Fotografico</p>
        <p class="asterisco" align="center">Fotos correspondientes a la guia No. <font color="red"><?php echo $guia ?></font></p>
        <p class="asterisco" align="center">Procesando fotos del departamente de <font color="#0066FF"><?php echo $mensaje ?></font></p>
    </div>
    <div id="contenedor_lista" style="background-color:#666; position:relative; width:98%; height:320px; top:15px; border-radius: 1em">
		<div id="listado_fotos" style="background-color: #666; position:relative;width:95%; top:3%;left:1%">
			<?php echo $impresion_imagenes; ?>
		</div>
	</div>
    <div style="background-color:#CCC; position:relative; width:98%; height:50px; top:10px; border-radius: 1em; font-family:Arial, Helvetica, sans-serif; font-size:11px; font-style:italic">
    	Oprima Click sobre la foto que desea visualizar. Puede desplazarse por la galeria fotografica usando las flechas del teclado.
    </div>	
</body>
</html>
<script type="text/javascript">		
	window.addEvent('load',function(){
		cargarImagen();
	});
</script>		
<script src="js/jquery-1.7.2.min.js"></script>
<script src="js/jquery-ui-1.8.18.custom.min.js"></script>
<script src="js/jquery.smooth-scroll.min.js"></script>
<script src="js/lightbox.js"></script>
<script>
  jQuery(document).ready(function($) {
      $('a').smoothScroll({
        speed: 1000,
        easing: 'easeInOutCubic'
      });

      $('.showOlderChanges').on('click', function(e){
        $('.changelog .old').slideDown('slow');
        $(this).fadeOut();
        e.preventDefault();
      })
  });

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-2196019-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>
