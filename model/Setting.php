<?php

/**
 * Administrator for configuration settings Class for DbConnection Class
 * 
 * @version 0.1
 * @author  Yoan Morel
 */
class Setting {

    /** 
     * Array of configuration settings
     */
    private static $params;

    /**
     * Returns desired configuration settings 
     * 
     * @param   string $paramVal Parameter's name
     * @param   string $defaultValue Default value
     * @return  string $val Parameter's name
     */
    public static function param($paramVal, $defaultValue = null) {
        if (isset(self::getParams()[$paramVal])):
            $val = self::getParams()[$paramVal];
        else:
            $val = $defaultValue;
        endif;
        return $val;
    }

    /**
     * Returns the parameter's array, charged if necessary from a configuration file
     * The searched configuration file is config.ini
     * 
     * @return array Parameter's array
     * @throws Exception If no configuration's file found
     */
    private static function getParams() {
        if (self::$params === null):
            $path = $_SERVER['DOCUMENT_ROOT'].'/testZone/config/config.ini';
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