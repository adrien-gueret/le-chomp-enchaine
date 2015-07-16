<?php

namespace EntityPHP;

require_once 'Entity.php';
require_once 'EntityArray.php';
require_once 'EntityRequest.php';

abstract class Core
{
	const	UNDEFINED			=	'_EntityPHP_undefined_',
			TYPE_STRING			=	'string',
			TYPE_INTEGER		=	'integer',
			TYPE_FLOAT			=	'float',
			TYPE_BOOLEAN		=	'boolean',
			TYPE_DATETIME		=	'datetime',
			TYPE_TIMESTAMP		=	'timestamp',
			TYPE_DATE			=	'date',
			TYPE_TIME			=	'time',
			TYPE_YEAR			=	'year',
			TYPE_CLASS			=	'class',
			TYPE_ARRAY			=	'array';

	protected static $all_dbs			=	array();
	public static $current_db			=	null;
	public static $current_db_is_utf8	=	false;

	/**
	 * Create a connection to the database
	 * @static
	 * @access public
	 * @param string $host Host of database
	 * @param string $user Username for connection
	 * @param string $password Password for connection
	 * @param string $database Database you want to connect to
	 * @param bool $utf8 Do we have to use UTF-8 encoding ?
	 */
	final public static function connectToDB($host, $user, $password, $database, $utf8 = true)
	{
		//TODO: make this connection more customisable and allow other drivers and not only MySQL
		$newDb	=	new \PDO('mysql:host='.$host.';dbname='.$database, $user, $password);

		if($utf8)
			$newDb->exec('SET NAMES UTF8');

		self::$all_dbs[$database]	=	array('db' => $newDb, 'utf8' => $utf8);
		self::switchToDB($database);
	}

	/**
	 * Switch database to use
	 * @static
	 * @access public
	 * @param string $database Database you want use
	 * @throws \Exception
	 */
	final public static function switchToDB($database)
	{
		if(isset(self::$all_dbs[$database]))
		{
			self::$current_db			=	self::$all_dbs[$database]['db'];
			self::$current_db_is_utf8	=	self::$all_dbs[$database]['utf8'];
		}
		else
			throw new \Exception('Try to switch to non-connected database "'.$database.'".');
	}

	/**
	 * Get the classes inherited from Entity
	 * @static
	 * @access public
	 * @return array An array of classes as strings
	 */
	final public static function getEntities()
	{
		$entities	=	array();

		foreach(get_declared_classes() as $class)
			if(is_subclass_of($class,'EntityPHP\Entity'))
				$entities[]	=	$class;

		return $entities;
	}

	/**
	 * Return the PHP type corresponding to given SQL type
	 * @static
	 * @access public
	 * @param string $sqlType SQL type to check
	 * @return array An array of classes as strings
	 */
	final public static function getPHPType($sqlType)
	{
		if(is_array($sqlType))
			return self::TYPE_ARRAY;

		if((class_exists($sqlType) || class_exists('\\'.$sqlType)) && is_subclass_of($sqlType, 'EntityPHP\Entity'))
			return self::TYPE_CLASS;

		$sqlType	=	strtoupper($sqlType);

		if(preg_match('/CHAR|TEXT|ENUM|SET/', $sqlType))
			return self::TYPE_STRING;

		if(preg_match('/INT/', $sqlType))
			return self::TYPE_INTEGER;

		if(preg_match('/DECIMAL|FLOAT|DOUBLE|REAL/', $sqlType))
			return self::TYPE_FLOAT;

		if(preg_match('/BOOLEAN/', $sqlType))
			return self::TYPE_BOOLEAN;

		if(preg_match('/DATETIME/', $sqlType))
			return self::TYPE_DATETIME;

		if(preg_match('/TIMESTAMP/', $sqlType))
			return self::TYPE_TIMESTAMP;

		if(preg_match('/DATE/', $sqlType))
			return self::TYPE_DATE;

		if(preg_match('/TIME/', $sqlType))
			return self::TYPE_TIME;

		if(preg_match('/YEAR/', $sqlType))
			return self::TYPE_YEAR;

		return null;
	}

	/**
	 * Create the database according to your Entities classes definition
	 * @static
	 * @access public
	 * @throws \Exception
	 */
	public static function generateDatabase()
	{
		$entities	=	self::getEntities();
		$query		=	self::$current_db->query('SHOW TABLES');
		$tables		=	array();

		while($table = $query->fetch(\PDO::FETCH_NUM))
			$tables[]	=	strtolower($table[0]);

		foreach($entities as $entity)
		{
			if( ! in_array(strtolower($entity::getTableName()), $tables))
				$entity::createTable();
			else
				$entity::updateTable();
		}
	}

	final public static function generateRequestForForeignFields($tableName, $refTableName, $idName, $refIdName, $field)
	{
		$request	=	'CREATE TABLE '.$tableName.'2'.$field.' (id_'.$tableName.' INT(11) UNSIGNED NOT NULL,';
		$request	.=	'id_'.$field.' INT(11) UNSIGNED NOT NULL,CONSTRAINT FOREIGN KEY fk_'.$tableName.'2'.$field;
		$request	.=	'_'.$tableName.'$'.$tableName.'2'.$refTableName.' (id_'.$tableName.')  REFERENCES '.$tableName;
		$request	.=	'('.$idName.') ON DELETE CASCADE, CONSTRAINT FOREIGN KEY fk_'.$tableName.'2'.$field.'_'.$field.'$';
		$request	.=	$tableName.'2'.$refTableName.' (id_'.$field.') REFERENCES '.$refTableName.'('.$refIdName.') ON DELETE CASCADE) ';
		$request	.=	(self::$current_db_is_utf8 ? 'DEFAULT CHARSET=utf8 ' : '').'ENGINE=InnoDB';

		return $request;
	}

}