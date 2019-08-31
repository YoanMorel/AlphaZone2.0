<?php

require_once 'Setting.php';

/**
 * Abstract Class DbConnection
 * 
 * Centralizes access services to the database
 * Use PDO API
 * 
 * @version 1.2
 * @author 	Yoan Morel
 */
abstract class DbConnection {

	/**
	 * PDO object related to the instance of the child classes
	 */
	private static $spdo;

	/**
	 * SQL queries service method
	 * 
	 * @param 	string $sql mySQL queries
	 * @param 	array $params Query's parameters
	 * @return 	PDOStmt Query's results
	 */
	protected function queryCall($sql, Array $params = null) {
		if($params):
			$returnedData = self::dbConnect()->prepare($sql);
			// loop on the parameters to bind
			foreach ($params as $param):
				$returnedData->bindParam($param[0], $param[1], $param[2]);
			endforeach;
			// execute the prepared query
			$returnedData->execute();
		else:
			$returnedData = self::dbConnect()->query($sql);
		endif;

		return $returnedData;
	}

	/**
	 * SQL transaction initialization service method
	 * 
	 * @return PDO PDO Object
	 */
	protected function startTransaction() {
		return self::dbConnect()->beginTransaction();
	}

	/**
	 * SQL commit service method
	 * 
	 * @return PDO PDO Object
	 */
	protected function commitTransaction() {
		return self::dbConnect()->commit();
	}

	/**
	 * SQL rollBack service method
	 * 
	 * @return PDO PDO Object
	 */
	protected function preventTransaction() {
		return self::dbConnect()->rollBack();
	}

	/**
	 * Last mySQL entry recovery service method
	 * 
	 * @return PDO PDO Object
	 */
	protected function getLastInsertId() {
		return self::dbConnect()->lastInsertId();
	}

	/**
	 * SQL line counter service method
	 * 
	 * @return PDO PDO Object
	 */
	protected function getRowCount() {
		return self::dbConnect()->rowCount();
	}

	/**
	 * PDO connection service method if needed
	 * 
	 * @return PDO $spdo PDO Object
	 */
	private static function dbConnect(){
		try {
			if(self::$spdo === null):
				// saves configuration settings
				$dsn = Setting::param("dsn");
				$user = Setting::param("user");
				$pwd = Setting::param("pwd");
				// start connection
				self::$spdo = new PDO($dsn, $user, $pwd,
					[
					PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    				PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
					PDO::ATTR_EMULATE_PREPARES   => false]
				);
			endif;

			// IF spdo's null, returns current object value
			return self::$spdo;
		} catch (PDOException $error) {
			$msg = 'ERREUR PDO within ' . $error->getFile() . ' L.' . $error->getLine() . ' : ' . $error->getMessage();
			die($msg);
		}
	}
}