<?php

// Load mooupload class
require('Source/mooupload.php');

// Upload file to tmp directory
Mooupload::upload(dirname(__FILE__).DIRECTORY_SEPARATOR.'../fotos/adjuntos'.DIRECTORY_SEPARATOR);
?>
