<?php

require_once 'Setting.php';

abstract class DbConnection {

	private static $spdo;

	protected function queryCall($sql, Array $params = null) {
		if($params):
			$returnedData = self::dbConnect()->prepare($sql);
			// boucle sur les paramètres à binder
			foreach ($params as $var):
				$returnedData->bindParam($var[0],$var[1],$var[2]);
			endforeach;
			// execute la requete préparée
			$returnedData->execute();
		else:
			$returnedData = self::dbConnect()->query($sql);
		endif;

		return $returnedData;
	}

	protected function startTransaction() {
		return self::dbConnect()->beginTransaction();
	}

	protected function commitTransaction() {
		return self::dbConnect()->commit();
	}

	protected function preventTransaction() {
		return self::dbConnect()->rollBack();
	}

	private static function dbConnect(){
		try {
			if(self::$spdo === null):
				// récupération des paramètres de config
				$dsn = Setting::param("dsn");
				$user = Setting::param("user");
				$pwd = Setting::param("pwd");
				// génère la connexion
				self::$spdo = new PDO($dsn, $user, $pwd,
					[
					PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    				PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
					PDO::ATTR_EMULATE_PREPARES   => false]
				);
			endif;

			// SI $spdo est différent de null, alors on retourne l'objet courant
			return self::$spdo;
		} catch (PDOException $error) {
			$msg = 'ERREUR PDO within ' . $error->getFile() . ' L.' . $error->getLine() . ' : ' . $error->getMessage();
			die($msg);
		}
	}
}