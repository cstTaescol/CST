<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
     						 						
/**
 *
 * Mooupload class
 * 
 * Provides a easy way for recept and save files from MooUpload
 * 
 * DISCLAIMER: You must add your own special rules for limit the upload of
 * insecure files like .php, .asp or .htaccess     
 * 
 * @author: Juan Lago <juanparati[at]gmail[dot].com>
 * 
 */       
class Mooupload 
{

	// Container index for HTML4 and Flash method
	const container_index = '_tbxFile';
	
			
	/**
	 *
	 * Detect if upload method is HTML5
	 * 	 
	 * @return	boolean
	 * 	 	  	 
	 */
	public static function is_HTML5_upload()
	{
		return empty($_FILES);
	}
	
	
	/**
	 *
	 * Upload a file using HTML4 or Flash method
	 * 
	 * @param		string	Directory destination path 	 	 	 	 
	 * @param		boolean	Return response to the script	 
	 * @return	array		Response
	 * 	 	  	 
	 */
	public static function HTML4_upload($destpath, $send_response = TRUE)
	{
	
		// Normalize path
		$destpath = self::_normalize_path($destpath);
			
		// Check if path exist
		if (!file_exists($destpath))
			throw new Exception('Path do not exist!');
		
		// Upload file using traditional method	
		$response = array();
  		//Datos de usuario para tracking
	  	$id_usuario=$_SESSION['id_usuario'];
		$fecha=date("Y").date("m").date("d");
		$id_tipo_bloqueo=1;   	
		$tamano_maximo=$_SESSION['maxsize'];  	
	  foreach ($_FILES as $k => $file)
	  {
	   	$hora=date("H:i:s"); 	
	    $response['key']         = (int)substr($k, strpos($k, self::container_index) + strlen(self::container_index));
	    $response['name']        = basename($file['name']);	// Basename for security issues
	    $response['error']       = $file['error'];
	    $response['size']        = $file['size'];
	    $response['upload_name'] = $file['name'];
	    $response['finish']      = FALSE;
	    
		//CONTROL DE TAMAÑO
		if ($file['size'] > $tamano_maximo)
		{
			echo "<script>
					alert('ERROR: EL ARCHIVO: ".$file['name'].", TIENE UN TAMANO QUE SUPERA EL MAXIMO PERMITIDO.');
				</script>";
			$response['error']=1;
		}
		
	    if ($response['error'] == 0)
	    {
	    	//Nombre de Archivo que se subirá al servidor	
	    	$nombre_archivo=time()."-".$file['name'];			
			
			//Almacenamiento en Base de Datos
			$boton=$_SESSION['boton'];
			$id_guia=$_SESSION['id_guia'];
			
			require("../config/configuracion.php");
			$sql="INSERT INTO registro_fotografico (id_guia,nombre,seccion) VALUE ('$id_guia','$nombre_archivo','$boton')";
			mysql_query($sql,$conexion) or die (mysql_error());
			
			//2. almacenamiento del traking
			$sql_trak="INSERT INTO tracking (id_guia,fecha_creacion,hora,evento,tipo_tracking,id_usuario) value ('$id_guia','$fecha','$hora','FOTO ALMACENADA: $nombre_archivo','$id_tipo_bloqueo','$id_usuario')";
			mysql_query($sql_trak,$conexion);
			
			
	      if (move_uploaded_file($file['tmp_name'], $destpath.$nombre_archivo) === FALSE)      
	        $response['error'] = UPLOAD_ERR_NO_TMP_DIR;
	      else
	        $response['finish'] = TRUE;
	      
		  include("redimensionar.php");
	    }          
	  }
			
	         
	 	// Send response to iframe 
		if ($send_response) 
	  	echo json_encode($response);    				
		
		return $response;	
	}
	
	
	/**
	 *	
	 * Detect the upload method and process the files uploaded
	 * 
	 * @param		string	Directory destination path	 	 	 	 	 
	 * @param		boolean	Return response to the script	 
	 * @return	array		Response
	 * 
	 */
	public static function upload($destpath, $send_response = TRUE)
	{			
		return self::is_HTML5_upload() ? self::HTML5_upload($destpath, $send_response) : self::HTML4_upload($destpath, $send_response);		
	}	 		 	 	
	
	
	
	/**
	 *
	 * Convert to bytes a information scale
	 * 
	 * @param		string	Information scale
	 * @return	integer	Size in bytes	 
	 *
	 */	 	 	 	 
	public static function _convert_size($val)
	{
		$val = trim($val);
	  $last = strtolower($val[strlen($val) - 1]);
	  
	  switch ($last) {
	    case 'g': $val *= 1024;
	 
	    case 'm': $val *= 1024;
	 
	    case 'k': $val *= 1024;
	  }
	 
	  return $val;
	}	
	
	
	/**
	 *
	 * Normalize a directory path
	 * 
	 * @param		string	Directory path
	 * @return	string	Path normalized	 
	 *
	 */	 	 	 	 
	public static function _normalize_path($path)
	{
		if ($path[sizeof($path) - 1] != DIRECTORY_SEPARATOR)
			$path .= DIRECTORY_SEPARATOR;
		
		return $path; 
	}	
	
	/**
	 *
	 * Read and normalize headers
	 * 	 
	 * @return	array	 
	 *
	 */
	public static function _read_headers()
	{
	
		// GetAllHeaders doesn't work with PHP-CGI
		if (function_exists('getallheaders')) 
		{
			$headers = getallheaders();
		}
		else 
		{
			$headers = array();
			$headers['Content-Length'] 	= $_SERVER['CONTENT_LENGTH'];
			$headers['X-File-Id'] 			= $_SERVER['HTTP_X_FILE_ID'];
			$headers['X-File-Name'] 		= $_SERVER['HTTP_X_FILE_NAME'];			
			$headers['X-File-Resume'] 	= $_SERVER['HTTP_X_FILE_RESUME'];
			$headers['X-File-Size'] 		= $_SERVER['HTTP_X_FILE_SIZE'];
		}
		
		return $headers;
		
	}	 	 	
	
}

?>
