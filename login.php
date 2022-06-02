<?php session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */

$cuenta=strtoupper($_POST["cuenta"]);
$clave=$_POST["clave"];
$operacion=$_POST["operacion"];

switch ($operacion)
{
	case 2:
		header("Location: ../CST_MEDELLIN/login.php?cuenta=$cuenta&clave=$clave");
		exit();
	break;
	
}



require("config/configuracion.php");
$objetos="";
$apertura="";
$id_usuario=""; 
?>
<!DOCTYPE html>
<html>
	<head>
	    <link href="tema/estilo.css" rel="stylesheet" type="text/css" />
    	<title>Accediendo....</title>
		<script type="text/javascript" src="js/mootools-core-1.4.5-full-nocompat.js"></script>
        <script type="text/javascript" src="js/mootools-more-1.4.0.1.js"></script>
	</head>
	<body>
    	<p class="titulo_tab_principal">Verificando Acceso</p>
		<?php    
        if ($cuenta == "CONFIG" && $clave == "39dfa55283318d31afe5a3ff4a0e3253e2045e43") // login de administrador que se entregara al cliente en el manual
		{
			$id_usuario = 1;
		}
		
		// 1. consultamos la coincidencia de los datos ingresados y el estado del usuario
        $sql="SELECT * FROM usuario WHERE cuenta= '$cuenta' AND clave='$clave' AND estado='A'";
        $consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
        $nfilas=mysql_num_rows($consulta);
        if ($nfilas != 1 && $id_usuario != 1)
            {	
                ?>
                <p align="center"><font color="red" size="5" aling="center">Error Al iniciar la Sesi&oacute;n.</font><br>Verifique sus datos y vuelva a intentarlo<br>
                <p align="center"><img src="imagenes/error.png" width="128" height="128" alt="Error de inicio de Sesion" />
                <p align="center">
                <button type="button" id="again" onClick="document.location='index.php'">
                    Volver a Intentar
                </button>
                    <script language="JavaScript" type="text/javascript">
                        if(document.getElementById) document.getElementById('again').focus();
                    </script>
                </p>
                <?php
            }	
            else
                {
                    $fila=mysql_fetch_array($consulta);
                    if ($id_usuario != 1)
						$id_usuario=$fila["id"];
						
                    //Ubica la Posicion en Bodega
                    $sql_privilegios="SELECT id_objeto FROM privilegio WHERE id_usuario='$id_usuario'";
                    $consulta_privilegios=mysql_query ($sql_privilegios,$conexion) or die (exit('Error al Obtener Privilegios '.mysql_error()));
                    while($fila_privilegios=mysql_fetch_array($consulta_privilegios))
                    {
                        $objetos[]=$fila_privilegios['id_objeto'];
                    }
                    //Evalua que el usuario si tenga privilegio asignados en el sistema
                    if ($objetos=="" && $id_usuario != 1)
                    {
                        echo "<script>
                                alert('ALERTA:No se encontraron privilegios para este Usuario, no se ha podido iniciar sesion.');
                            </script>";
                        echo '<meta http-equiv="Refresh" content="0;url=index.php">';
                        exit();
                    }
                        
                    $_SESSION['objetos']=$objetos;
                    $_SESSION['id_usuario']=$fila["id"];
                    $_SESSION['nombre']=$fila["nombre"];
                    $_SESSION['id_aerolinea_user']=$fila["id_aerolinea"];
					
					$lista_imagenes = "
										imagenes[0]= 'imagenes/logo.png';
										imagenes[1]= 'imagenes/sesion.png';
										imagenes[2]= 'imagenes/descargar-act.png';
										imagenes[3]= 'imagenes/camion2.png';
										imagenes[4]= 'imagenes/imprimir-act.png';
										imagenes[5]= 'imagenes/atras-act.png';
										imagenes[6]= 'imagenes/buscar-act.png';
										imagenes[7]= 'imagenes/agregar-act.png';
										imagenes[8]= 'imagenes/recargar-act.png';
										imagenes[9]= 'imagenes/abajo-act.png';
										imagenes[10]= 'imagenes/aceptar-act.png';
										imagenes[11]= 'imagenes/adelante-act.png';
										imagenes[12]= 'imagenes/al_final-act.png';
										imagenes[13]= 'imagenes/al_principio-act.png';
										imagenes[14]= 'imagenes/alerta-act.png';
										imagenes[15]= 'imagenes/arriba-act.png';
										imagenes[16]= 'imagenes/bloquear-act.png';
										imagenes[17]= 'imagenes/cancelar-act.png';
										imagenes[18]= 'imagenes/cargar-act.png';
										imagenes[19]= 'imagenes/consultar-act.png';
										imagenes[20]= 'imagenes/desbloquear-act.png';
										imagenes[21]= 'imagenes/descargar-act2.png';
										imagenes[22]= 'imagenes/eliminar-act.png';
										imagenes[23]= 'imagenes/guardar-act.png';
										imagenes[24]= 'imagenes/home-act.png';
										imagenes[25]= 'imagenes/nuevo_link-act.png';
										imagenes[26]= 'imagenes/quitar_link-act.png';
										imagenes[27]= 'imagenes/quitar-act.png';
										imagenes[28]= 'imagenes/settings-act.png';
										imagenes[29]= 'imagenes/buscar.png';
										imagenes[30]= 'imagenes/camion.jpg';
										imagenes[31]= 'imagenes/caja2.jpg';
										imagenes[32]= 'imagenes/caja1.jpg';
										imagenes[33]= 'imagenes/poli1.png';
										imagenes[34]= 'imagenes/borrar.jpg';
										imagenes[35]= 'imagenes/1.jpg';
										imagenes[36]= 'imagenes/2.jpg';
										imagenes[37]= 'imagenes/3.jpg';
										imagenes[38]= 'imagenes/recuadro_firma1.jpg';
										imagenes[39]= 'imagenes/recuadro_firma2.jpg';
										imagenes[40]= 'imagenes/recuadro_firma3.jpg';
									";

                    if ($id_usuario == 1)
                    {
                        $apertura= 'cfg_rt.php';
						?>
							<script type="text/javascript">		
								var cantidad_imagenes=0;
								var imagenes= new Array;
								<?php echo $lista_imagenes ?>
								function cargarImagen()
								{
									var totalimagenes=imagenes.length;
									var myImages = Asset.images(imagenes, 							   
									{
										properties: {
										},
										onProgress: function(counter, 
													index, 
													source)
										{
											//Calculo de porcentaje cargado
											var porcentaje = (counter * 800)/ totalimagenes;
											var ancho=$('barra_cargando').getSize().x;
											$('porcentaje_cargado').set('morph',{
												link: 'cancel'
											});
											
											$('porcentaje_cargado').morph({
												'width': porcentaje+'px' 
											});	
											porcentaje = porcentaje/8;
											$('porcentaje_cargado').innerHTML='Cargado '+ Math.round(porcentaje) +'%';
										},
										onComplete: function()
										{
											$('barra_cargando').setStyle("opacity",0);
											document.location='<?php echo $apertura ?>';
										}
									});
								}
								window.addEvent('load',function(){cargarImagen();});
						</script>        
						<?php							
                    }
						else
						{
							$apertura=PROGINI;
							?>
								<script type="text/javascript">		
                                    var cantidad_imagenes=0;
                                    var imagenes= new Array;
                                    <?php echo $lista_imagenes ?>
                                    function cargarImagen()
                                    {
                                        var totalimagenes=imagenes.length;
                                        var myImages = Asset.images(imagenes, 							   
                                        {
                                            properties: {
                                            },
                                            onProgress: function(counter, 
                                                        index, 
                                                        source)
                                            {
                                                //Calculo de porcentaje cargado
                                                var porcentaje = (counter * 800)/ totalimagenes;
                                                var ancho=$('barra_cargando').getSize().x;
                                                $('porcentaje_cargado').set('morph',{
                                                    link: 'cancel'
                                                });
                                                
                                                $('porcentaje_cargado').morph({
                                                    'width': porcentaje+'px' 
                                                });	
                                                porcentaje = porcentaje/8;
                                                $('porcentaje_cargado').innerHTML='Cargado '+ Math.round(porcentaje) +'%';
                                            },
                                            onComplete: function()
                                            {
                                                $('barra_cargando').setStyle("opacity",0);
                                                document.location='<?php echo $apertura ?>';
                                            }
                                        });
                                    }
                                    window.addEvent('load',function(){cargarImagen();});
                            </script>        
							<?php							
						}
                }
        ?>
        <div id="barra_cargando" style="position:relative;width:800px;height:100%;border-style:solid;border-color:#C6DFE6;border-width:1px;left:10%;top:20px;">
            <div id="porcentaje_cargado" style="position:relative;width:700px;height:20px;background-color:#C6DFE6;">Cargado 0%</div>
        </div>
	</body>
</html>
