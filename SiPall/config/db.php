<?php
class Database
{
	public static function connect(){
		$db=new mysqli("127.0.0.1","taescol","inventarios","cst");
		$db->query("SET NAMES 'utf8'");
		return $db;
	}
}
?>
