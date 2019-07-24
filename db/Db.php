<?php

/**
 * 
 */
class Db
{
	/**
	 * Mantiene instancia de la clase en sí.
	 * 
	 * @var Db
	 */
	private static $_instance = null;

	/**
	 * Contiene instancias de la clase base PDO.
	 * 
	 * @var PDO
	 */
	private $_conn = null;
	
	/**
	 * Método constructor de clase
	 */
	private function __construct() {
		try {
			$config = Config::get('db');
			$this->_conn = new PDO(
				$config['dsn'],
				$config['username'],
				$config['password'],
				array(
					PDO::ATTR_PERSISTENT => true,
					PDO::MYSQL_ATTR_INIT_COMMAND => "SET CHARACTER SET 'utf8'",
					PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
				)
			);
		} catch (PDOException $e) {
			trigger_error($e->getMessage(), E_USER_ERROR);
			exit;
		}
	}

	public static function getInstance() {
		// Si la var estatica no es una instancia de la clase misma
	    if (!self::$_instance instanceof self) {
	    	// Instanciamos la clase
	        self::$_instance = new self;
	    }
	    return self::$_instance;
	}

	public function query(/* $sql [, ... ] */) {
		// SQL statement
		$sql = func_get_arg(0);

		// parameters, if any
		$parameters = array_slice(func_get_args(), 1);

		$pattern = "
		/(?:'[^'\\\\]*(?:(?:\\\\.|'')[^'\\\\]*)*'
		| \"[^\"\\\\]*(?:(?:\\\\.|\"\")[^\"\\\\]*)*\"
		| `[^`\\\\]*(?:(?:\\\\.|``)[^`\\\\]*)*`
		)(*SKIP)(*F)| \?/x";

		preg_match_all($pattern, $sql, $matches);
		if (count($matches[0]) < count($parameters)) {
		    trigger_error("Too few placeholders in query", E_USER_ERROR);
		} else if (count($matches[0]) > count($parameters)) {
		    trigger_error("Too many placeholders in query", E_USER_ERROR);
		}

		// replace placeholders with quoted, escaped strings
		$patterns = [];
		$replacements = [];
		$M = count($parameters);
		for ($i = 0, $n = $M; $i < $n; $i++) {
		    array_push($patterns, $pattern);
		    array_push($replacements, preg_quote($this->_conn->quote($parameters[$i])));
		}
		$query = preg_replace($patterns, $replacements, $sql, 1);

		// execute query
		$statement = $this->_conn->query($query);
		if ($statement === false) {
		    trigger_error($this->_conn->errorInfo()[2], E_USER_ERROR);
		}

		// if query was SELECT
		// http://stackoverflow.com/a/19794473/5156190
		if ($statement->columnCount() > 0) {
		    // return result set's rows
		    return $statement->fetchAll(PDO::FETCH_ASSOC);
		}
		// if query was DELETE, INSERT, or UPDATE
		else {
		    // return number of rows affected
		    return ($statement->rowCount() == 1); // true o false
		}
	}
}