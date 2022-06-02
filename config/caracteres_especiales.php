<?php
//$texto="áber qué pasíto tómamos úna vez ç No hay que ser tarn Ñero ni tan ñoño < ac¿? > güiño el GÜañote ";
//Necesitamos recibir una variable llamada $texto, con la cadena que desea ser evaluada y reemplazada por los caracteres especiales.
$filtro=array("/á/", 
			  "/é/", 
			  "/í/", 
			  "/ó/", 
			  "/ú/", 
			  "/Á/", 
			  "/É/", 
			  "/Í/", 
			  "/Ó/", 
			  "/Ú/", 
			  "/ç/", 
			  "/Ñ/", 
			  "/ñ/",
			  "/ü/",
			  "/Ü/",
			  "/¿/",
			  "/</",
			  "/>/");

$reemplazo=array("&Aacute;",
				 "&Eacute;",
				 "&Iacute;",
				 "&Oacute;",
				 "&Uacute;", 
				 "&Aacute;",
				 "&Eacute;",
				 "&Iacute;",
				 "&Oacute;",
				 "&Uacute;",
				 "&ccedil;",
				 "&Ntilde;",
				 "&Ntilde;",
				 "&Uuml;",
				 "&Uuml;",				 
				 "&iquest;",
				 "&lt;",
				 "&gt;");
$texto = preg_replace($filtro,$reemplazo,$texto);
?>
