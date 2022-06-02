<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<?php
if(isset($_REQUEST['id_guia']) and isset($_REQUEST['tipo']))
{
	$tipo=$_REQUEST['tipo'];
	$id_guia=$_REQUEST['id_guia'];
	$sql="SELECT g.hija, v.nvuelo FROM guia g LEFT JOIN vuelo v ON g.id_vuelo = v.id WHERE g.id='$id_guia'";
	$consulta=mysql_query ($sql,$conexion) or die ("ERROR 1: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
	$fila=mysql_fetch_array($consulta);
}
else
{
	?>
    <script>
		alert('Error: El servidor no pudo obtener la informacion, intentelo de nuevo');
	</script>
    <?php
}
?>
<head>
	<style>
        .titulo_add{
            color:#FFF;
        }
		.opaco_ie { 
 			filter: alpha(opacity=30);
		}
    </style>
</head>
<body>
	<p align="center">
		<font size="+2" color="#0066FF"><strong>REGISTRO FOTOGRAFICO</strong></font> <img src="imagenes/camara.jpg" width="50" height="50" align="absmiddle" />
		<br>
		<font size="-1" color="red"><strong>ELIMINAR</strong></font>
	</p>
	<br>
	<div id="respuesta" class="opaco_ie" style="position:relative;opacity:0; background-color:#9F0; width:98%; height:30px"></div>
	<div id="listado_fotos"></div>
</body>
</html>
<script type="text/javascript" src="js/mootools-core-1.4.5-full-nocompat.js"></script>
<script type="text/javascript" src="js/mootools-more-1.4.0.1.js"></script>
<script type="text/javascript">		
	function cargarImagen()
	{
		var cantidad_imagenes=0;
		var imagenes= new Array;
		<?php
			$cantidad=0;
			$sql="SELECT * FROM registro_fotografico WHERE id_guia='$id_guia' and seccion='$tipo'";
			$consulta=mysql_query ($sql,$conexion) or die ("ERROR 2: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
			while($fila=mysql_fetch_array($consulta))
			{
				echo "imagenes[".$cantidad."]='fotos/mercancia/".$fila['nombre']."';\n";
				$cantidad++;
			}
			$cantidad--;
		?>
		var cantidad = <?php echo $cantidad ?>; 		
		var totalimagenes=imagenes.length;
		var myImages = Asset.images(imagenes, 							   
		{
			onProgress: function(counter)
			{
				//Calculo de porcentaje cargado
				var porcentaje = (counter * 100)/ totalimagenes;
				$('respuesta').setStyle("opacity",1);
				$('respuesta').innerHTML='<p align="center"><img src="imagenes/cargando.gif"> '+porcentaje+'%</p>';
				
			},
			onComplete: function()
			{
				//Ocultamos el div cargador
				$('respuesta').setStyle("opacity",0);
				//Creamos los objetos de imagen para cada foto
				for (i=0; i<=cantidad; i++)
				{	
					var imagen = new Element('img',
					{ 
						src: imagenes[i],  
						id: 'imagen_id_'+i, 
						styles: 
						{
							width: '100px', 
							height: '100px',
							'opacity': 0 
						}
					});
					imagen.inject('listado_fotos');
					imagen.set('morph',
					{ 
						duration: 500, 
						transition: 'linear'
					});
					imagen.morph({
						'opacity': 1
					});
					
					$("imagen_id_"+i).addEvent('mouseover',function()
					{
						$("imagen_id_"+i).morph({
							width:'200px',
							height: '200px'
						});				
					});
					
					//agregar_eventos(imagen.id,cantidad); 							
				}
			}
		});
	}
	
	window.addEvent('load',function(){
		cargarImagen();
	});
	
	function agregar_eventos(identificador,cantidad)
	{
		//alert(identificador);
		for (i=0; i<=cantidad; i++)
		{
			$("imagen_id_"+i).set('morph',
			{ 
				duration: 500, 
				transition: 'linear'
			});

			$("imagen_id_"+i).addEvent('mouseover',function()
			{
				$("imagen_id_"+i).morph({
					width:'200px',
					height: '200px'
				});				
			});
			
			$("imagen_id_"+i).addEvent('mouseout',function()
			{
				$("imagen_id_"+i).morph({
					width:'100px',
					height: '100px'
				});
			});
		}
	}
</script>



