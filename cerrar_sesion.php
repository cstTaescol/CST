<?php session_start();
session_destroy ();
require("config/configuracion.php");
echo '<meta http-equiv="Refresh" content="0;url=index.php">';
?>
