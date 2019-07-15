<?php

class Setting {
    /** Tableau des paramètres de configuration */
    private static $params;
    /**
     * Renvoie la valeur d'un paramètre de configuration
     * 
     * @param string $nom Nom du paramètre
     * @param string $valeurParDefaut Valeur à renvoyer par défaut
     * @return string Valeur du paramètre
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
     * Renvoie le tableau des paramètres en le chargeant au besoin depuis un fichier de configuration.
     * 
     * @return array Tableau des paramètres
     * @throws Exception Si aucun fichier de configuration n'est trouvé
     */
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