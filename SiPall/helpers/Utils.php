<?php
class Utils {
    public static function contentPrivilegios($idComponente){        
        if ($_SESSION['id_usuario'] == 1) //Admin
        {
             return true;
        }
        else
        {
            $result = false;
            foreach ($_SESSION['objetos'] as $value) {
                if($value == $idComponente){
                    $result = true;
                    break;
                }
            }
            return $result;           
        }
    }
}
?>