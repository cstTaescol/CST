<?php
define ("appName","SiPall");
define ("appFather","CST");
$tmpRuta=explode(appName, $_SERVER['PHP_SELF']);
define ("SERVER","https://cst.taescol.com.co/");

//2022-06-09: Navegación con apuntamiento de puerto
//define ("port","80");
//define ("base_url","http://".$_SERVER['SERVER_NAME'].":".port.$tmpRuta[0].appName."/");
//define ("base_login","http://".$_SERVER['SERVER_NAME'].":".port.$tmpRuta[0]."cerrar_sesion.php");
//define ("base_Father","http://".$_SERVER['SERVER_NAME'].":".port.$tmpRuta[0]);
//define ("base_relative",":".port.$tmpRuta[0]);

//2022-06-09: Navegación Libre (http y https)
//define ("base_url","https://".$_SERVER['SERVER_NAME'].$tmpRuta[0].appName."/");
define ("base_url",SERVER.appName."/");
define ("base_login",SERVER."cerrar_sesion.php");
//define ("base_Father","https://".$_SERVER['SERVER_NAME'].$tmpRuta[0]);
define ("base_Father","../../../../");
$tmpRuta=explode('index.php', $_SERVER['SCRIPT_FILENAME']);
define ("base_relative",$tmpRuta[0]);
define ("base_assets",base_url."views/assets/");
define ("alerta",'<META HTTP-EQUIV="REFRESH" CONTENT="0;URL='.base_url.'views/layouts/layout2/"');
define ("confirmacion",'<META HTTP-EQUIV="REFRESH" CONTENT="0;URL='.base_url.'views/layouts/layout3/"');
define ("controller_default","CentralController");
define ("action_default","start");
define ("version","1.1");
define ("CLIENTE_NOMBRE","Taescol");
define ("CLIENTE_IDENTIFICADOR","NIT 800.136.926-1");
define ("CLIENTE_DIRECCION","Av. El Dorado No. 111-51 Of 150 – TC1 – Bodegas 2");
define ("CLIENTE_TELEFONO","(57)(1)5190777");
define ("CLIENTE_CIUDAD","Bogotá");
define ("CLIENTE_PAIS","Colombia");
?>
