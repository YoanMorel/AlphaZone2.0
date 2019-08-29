<?php

/**
 * Classe gestionnaire de paramètres de configuration pour la classe DbConnection
 * 
 * @version 0.1
 * @author  Yoan Morel
 */
class Setting {

    /** 
     * Tableau des paramètres de configuration 
     */
    private static $params;

    /**
     * Retourne le paramètre de configuration recherché
     * 
     * @param   string $paramVal Nom du paramètre
     * @param   string $defaultValue Valeur par défaut
     * @return  string $val Valeur du paramètre
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
     * Retourne le tableau de paramètres en le chargeant si nécessaire depuis un fichier de configuration
     * Le fichier de configuration recherché est config.ini
     * 
     * @return array Tableau des paramètres
     * @throws Exception si aucun fichier de configuration trouvé
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