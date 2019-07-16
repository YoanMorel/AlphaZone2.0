<?php

class Setting {
    /** Tableau des paramètres de configuration */
    private static $params;

    public static function param($paramVal, $defaultValue = null) {
        if (isset(self::getParams()[$paramVal])):
            $val = self::getParams()[$paramVal];
        else:
            $val = $defaultValue;
        endif;
        return $val;
    }

    private static function getParams() {
        if (self::$params == null):
            $path = "../../config/config.ini";
            if (!file_exists($path)):
                throw new Exception("Aucun fichier de configuration trouvé");
            else:
                self::$params = parse_ini_file($path);
            endif;
        endif;
        return self::$params;
    }
}

?>