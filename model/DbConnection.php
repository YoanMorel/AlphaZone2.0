<?php

require_once 'Setting.php';

/**
 * Classe abstraite DbConnection
 * Centralise les services d'accès à la base de données
 * Utilise l'API PDO
 * 
 * @version 1.2
 * @author 	Yoan Morel
 */
abstract class DbConnection {

	/**
	 * Objet PDO relatif à l'instance des classes filles
	 */
	private static $spdo;

	/**
	 * Methode de service de requêtes SQL
	 * 
	 * @param 	string $sql Requête mySQL
	 * @param 	array $params Paramétrage de la requête
	 * @return 	PDOStmt Résultat de la requête
	 */
	protected function queryCall($sql, Array $params = null) {
		if($params):
			$returnedData = self::dbConnect()->prepare($sql);
			// boucle sur les paramètres à binder
			foreach ($params as $param):
				$returnedData->bindParam($param[0], $param[1], $param[2]);
			endforeach;
			// execute la requete préparée
			$returnedData->execute();
		else:
			$returnedData = self::dbConnect()->query($sql);
		endif;

		return $returnedData;
	}

	/**
	 * Methode de service d'initialisation de transaction PDO
	 * 
	 * @return PDO Objet PDO
	 */
	protected function startTransaction() {
		return self::dbConnect()->beginTransaction();
	}

	/**
	 * Methode de service de dépot de transaction PDO
	 * 
	 * @return PDO Objet PDO
	 */
	protected function commitTransaction() {
		return self::dbConnect()->commit();
	}

	/**
	 * Methode de service d'annulation de transaction PDO
	 * 
	 * @return PDO Objet PDO
	 */
	protected function preventTransaction() {
		return self::dbConnect()->rollBack();
	}

	/**
	 * Methode de service de récupération de la dernière entrée mySQL
	 * 
	 * @return PDO Object PDO
	 */
	protected function getLastInsertId() {
		return self::dbConnect()->lastInsertId();
	}

	/**
	 * Methode de service de comptage de ligne retournée par PDO
	 * 
	 * @return PDO objet PDO
	 */
	protected function getRowCount() {
		return self::dbConnect()->rowCount();
	}

	/**
	 * Methode de connexion PDO au besoin
	 * 
	 * @return PDO Objet PDO spdo
	 */
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