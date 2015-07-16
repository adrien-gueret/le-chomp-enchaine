<?php

namespace EntityPHP;

final class EntityRequest
{
	private $select						=	'*';
	private $where						=	'1=1';
	private $orderBy					=	1;
	private $totalPropertiesToSelect	=	0;
	private $limit						=	'';
	private $join						=	'';
	private $joinedTables				=	array();
	private $canFetchClass				=	true;
	private $fetchClassName				=	'';
	private $originClassName			=	'';
	private $tableName					=	'';
	private $idName						=	'';
	private $totalToGet					=	999;
	private $useLeftJoins				=	false;
	private $propertiesAsTableAlias		=	[];

	public function __construct($className = '', $useLeftJoins = false)
	{
		if(is_string($className) && !empty($className))
		{
			$this->tableName		=	$className::getTableName();
			$this->idName			=	$className::getIdName();
			$this->fetchClassName	=	$className;
			$this->originClassName	=	$className;
			$this->useLeftJoins		=	$useLeftJoins;
		}
		else
		{
			throw new \Exception('Parameter of EntityRequest must be an Entity classname.');
		}
	}

	/**
	 * Generate a SQL join request according to the given values
	 * @access private
	 * @param String $table The table name to join
	 * @param String $table_id_name The name of primary id of the table to join
	 * @param String $property The property name used to link the two tables
	 * @param String $originTable The table name used to base the join (default: $this->tableName)
	 * @return EntityRequest The calling EntityRequest.
	 */
	private function join($table, $table_id_name, $property, $originTable = '')
	{
		if( ! in_array($table, $this->joinedTables))
		{
			$this->propertiesAsTableAlias[]	=	$property;
			$table_alias					=	$property;

			$originTable	=	empty($originTable) ? $this->tableName : $originTable;

			$this->join		.=	($this->useLeftJoins ? ' LEFT' : '').' JOIN '.$table.' AS '.$table_alias;
			$this->join		.=	' ON '.$table_alias.'.'.$table_id_name.'='.$originTable.'.id_'.$property;

			$this->joinedTables[]	=	$table;
		}
		return $this;
	}

	/**
	 * Generate a SQL join request for Many to Many relations according to the given values
	 * @access private
	 * @param String $table The table name to join
	 * @param String $originTable The table name used to base the join
	 * @param String $idTableName The name of the primary id field in $table
	 * @param String $idOriginTableName The name of the primary id field in $originTable
	 * @param String $property The property name used to link the two tables via the linked table
	 * @return EntityRequest The calling EntityRequest.
	 */
	private function joinMany2Many($table, $originTable, $idTableName, $idOriginTableName, $property)
	{
		$newTable		=	$originTable.'2'.$property;

		if( ! in_array($newTable, $this->joinedTables))
		{
			if($newTable === $this->tableName)
			{
				$this->propertiesAsTableAlias[]	=	$property;
				$table_alias					=	$property;
			}
			else
				$table_alias	=	$newTable;

			$this->join	.=	($this->useLeftJoins ? ' LEFT' : '').' JOIN '.$newTable.' AS '.$table_alias;
			$this->join	.=	' ON '.$table_alias.'.id_'.$originTable.'='.$originTable.'.'.$idOriginTableName;

			$this->joinedTables[]	=	$newTable;
		}

		if( ! in_array($table, $this->joinedTables))
		{
			$this->join($table, $idTableName, $property, $newTable);
		}
		return $this;
	}

	/**
	 * Analyze a property name in order to detect subproperties and generate the SQL request
	 * @access private
	 * @param String $targetClassName Class name containing the property $prop
	 * @param String $prop The property to analyze
	 * @param String $type Type of request to generate with this analyse (select, where, order). (default: select)
	 * @param String $parentProp The name of the parent property if $prop is a subproperty (default: '')
	 * @return Void|String Void if $type equals "select", a string wich will replace a part of a WHERE request if $type equals "where"
	 * @throws \Exception
	 */
	private function analyzeProperty($targetClassName, $prop, $type = 'select', $parentProp = '')
	{
		$targetTableName	=	$targetClassName::getTableName();
		$targetIdName		=	$targetClassName::getIdName();

		$originClassName	=	$this->originClassName;
		$originTableName	=	$originClassName::getTableName();
		$originIdName		=	$originClassName::getIdName();

		$fields			=	explode('.', trim($prop));
		$prop			=	array_shift($fields);

		if($type === 'where')
		{
			$errorMethod	=	'where(String $props, Array $values)';
			$structure		=	empty($parentProp) ? $originClassName::__structure() : $targetClassName::__structure();
		}
		else
		{
			$structure		=	$targetClassName::__structure();
			$errorMethod	=	$type === 'orderBy' ? 'orderBy(String $props)' : 'select(String $props)';
		}

		if($prop === $targetIdName)
			$structure[$prop]	=	'INTEGER(11)';

		//Check if property exists
		if(isset($structure[$prop]))
		{
			$totalSplit	=	count($fields);
			$sql_type	=	$structure[$prop];
			$php_type	=	Core::getPHPType($sql_type);

			$is_many_2_many	=	true;

			switch($php_type)
			{
				case Core::TYPE_CLASS:
					$sql_type		=	array($sql_type);
					$is_many_2_many	=	false;

				case Core::TYPE_ARRAY:
					$className	=	current($sql_type);

					if(!is_subclass_of($className,'EntityPHP\Entity'))
						throw new \Exception('EntityRequest::'.$errorMethod.' : "'.$targetClassName.'.'.$prop.'" is not a subclass of Entity.');

					$otherTableName	=	$className::getTableName();
					$otherIdName	=	$className::getIdName();
					$tableAlias		=	null;

					if($is_many_2_many)
						$this->joinMany2Many($otherTableName, $originTableName, $otherIdName, $originIdName, $prop);
					else
						$this->join($otherTableName, $otherIdName, $prop, $targetTableName);

					if($totalSplit > 0) //We want to select a property of the contained Entity
						return $this->analyzeProperty($className, implode('.', $fields), $type, (!empty($parentProp) ? $parentProp.'.' : '').$prop);
					else //We want to select ALL properties of the contained Entity
					{
						if($type == 'select') {
							$this->generateSelectAll($className, $prop, true);
						}
						else
							throw new \Exception('EntityRequest::'.$errorMethod.' : "'.$targetClassName.'.'.$prop.'" can\'t be used for this method.');
					}

					break;

				default:
					if(empty($parentProp))
						$tableName	=	$originTableName;
					else if(in_array($parentProp, $this->propertiesAsTableAlias))
						$tableName	=	$parentProp;
					else
						$tableName	=	$targetTableName;

					switch($type)
					{
						case 'select':
							return ','.$tableName.'.'.$prop.(!empty($parentProp)?' AS "'.str_replace('.','_',$parentProp).'_'.$prop.'"':'');

						case 'where':
							return $tableName.'.'.$prop;

						case 'orderBy':
							return ','.$tableName.'.'.$prop;
					}
					return null;
			}
		}
		else
			throw new \Exception('EntityRequest::'.$errorMethod.' : "'.$targetClassName.'" has no properties named "'.$prop.'".');
	}

	/**
	 * Perform a Vanilla SQL request
	 * @param  String $request The  SQLrequest to execute
	 * @param  String $fetchMode PDO fetch constant Default PDO::FETCH_OBJ
	 * @access public
	 * @final
	 * @static
	 * @return Array An Array containing data gotten form the request
	 * @throws \Exception
	 */
	final public static function executeSQL($request, $fetchMode = \PDO::FETCH_OBJ)
	{
		$query	=	Core::$current_db->query($request);

		if($query)
		{
			if($query->rowCount() > 0)
				return $query->fetchAll($fetchMode);

			return $query;
		}

		$error	=	Core::$current_db->errorInfo();

		if(empty($error) || empty($error[0]))
			return $query;

		throw new \Exception("EntityRequest::executeSQL() : An error occurs while running the given SQL request.\n[SQLSTATE: ".$error[0].'][DriverCode: '.$error[1].'] => '.$error[2]."\n");
	}

	/**
	 * Generate a SQL select request according to the given value
	 * @access public
	 * @param String $props The properties to select form the table, separated by comas.
	 * @return EntityRequest The calling EntityRequest.
	 */
	public function select($props)
	{
		$this->totalPropertiesToSelect	=	0;

		if($props == '*')
		{
			$this->select			=	'*';
			$this->canFetchClass	=	true;
		}
		else
		{
			$this->select			=	'';
			$this->canFetchClass	=	false;

			$props	=	explode(',', $props);



			foreach($props as $prop)
			{
				$this->totalPropertiesToSelect++;
				$this->select	.=	$this->analyzeProperty($this->fetchClassName, $prop);
			}

			$this->select	=	substr($this->select, 1);
		}

		return $this;
	}

	/**
	 * Generate a SQL where request according to the given values
	 * @access public
	 * @param String $props The query used to filter
	 * @param Array $values Values of variables noted as ? in $props (default: array())
	 * @return EntityRequest The calling EntityRequest.
	 * @throws \Exception
	 */
	public function where($props, Array $values = array())
	{
		if($props == '1=1')
			$this->where	=	'1=1';
		else
		{
			if(substr_count($props,'?') == count($values))
			{
				$keywords	=	array('IS', 'NULL', 'AND','OR','BETWEEN','IN','\(','\)','!=','<=','>=','<','>','=','\*','\+','-','/',',');

				$props		=	str_replace(' ','',trim($props));
				$props		=	preg_replace('#('.implode('|',$keywords).')#sU',' $1 ',$props);
				$indexData	=	0;
				$props		=	explode(' ',$props);

				foreach($props as $key => $elem)
				{
					$elem	=	trim($elem);

					if(!in_array($elem,$keywords) && $elem!='(' && $elem !=')' && $elem !='*' && $elem !='+' && strlen($elem)>0)
					{
						if($elem == '?')
						{
							$value	=	$values[$indexData++];
							if(!is_numeric($value))
								$value	=	'"'.htmlspecialchars($value,ENT_QUOTES,Core::$current_db_is_utf8 ? 'UTF-8' : 'ISO-8859-1').'"';

							$props[$key]	=	$value;
						}
						else
							$props[$key]	=	$this->analyzeProperty($this->fetchClassName, $elem, 'where');
					}
				}

				$this->where	=	implode(' ', $props);
			}
			else
				throw new \Exception('EntityRequest::where(String $props, Array $values) : $props doesn\'t contain as much "?" than the number of data in $values.');
		}
		return $this;
	}

	/**
	 * Generate a SQL order request according to the given values
	 * @access public
	 * @param String $props The properties used to order the results, separated by comas.
	 * @return EntityRequest The calling EntityRequest.
	 */
	public function orderBy($props)
	{
		$this->orderBy	=	'';

		//We can order according to several properties
		$props	=	explode(',',$props);
		foreach($props as $prop)
		{
			$desc	=	false;
			if(substr_count($prop,' DESC') > 0 || substr_count($prop, ' desc') > 0)
			{
				$desc	=	true;
				$prop	=	str_replace(' DESC', '', str_replace(' desc', '', $prop));
			}

			$this->orderBy	.=	$this->analyzeProperty($this->fetchClassName,$prop,'orderBy').($desc ? ' DESC' : '');
		}

		$this->orderBy	=	substr($this->orderBy,1);

		return $this;
	}

	/**
	 * Generate a SQL limit request according to the given values
	 * @access public
	 * @param Int $total The maximum number of results to return
	 * @param Int $fromRecord The offset of the first result to return (default: 0)
	 * @return EntityRequest The calling EntityRequest.
	 */
	public function getOnly($total, $fromRecord = 0)
	{
		$this->totalToGet	=	$total;
		$this->limit		=	' LIMIT '.$fromRecord.','.$total;
		return $this;
	}

	/**
	 * Set a SELECT * SQL request for the given entity
	 * @access private
	 * @param String $className Entity classname we want to make a SELECT * request
	 * @param String $prop The property name of the Entity in case of JOIN request (default: '')
	 * @param Bool $propIsTableName Indicates that given $prop should be used as table name (default: false)
	 * @return EntityRequest The calling EntityRequest
	 */
	private function generateSelectAll($className, $prop = '', $propIsTableName = false)
	{
		$vars		=	$className::__structure();
		$setAlias	=	! $this->canFetchClass && ! empty($prop) && $this->totalPropertiesToSelect > 1;
		$tableName	=	($propIsTableName && ! empty($prop)) ? $prop : $className::getTableName();
		$idName		=	$className::getIdName();

		if($this->totalPropertiesToSelect === 1)
		{
			$this->canFetchClass	=	true;
			$this->fetchClassName	=	$className;
		}

		$vars[$idName]	=	'INTEGER(11)';

		foreach($vars as $name => $sql_type)
		{
			$php_type	=	Core::getPHPType($sql_type);

			switch($php_type)
			{
				case Core::TYPE_CLASS:
					$this->select	.=	','.$tableName.'.'.'id_'.$name;
					break;

				case Core::TYPE_ARRAY:
					//In case on many 2 many, we should not fetch property
					continue 2;

				default:
					$this->select	.=	','.$tableName.'.'.$name;
					break;
			}

			$this->select	.=	($setAlias ? ' AS "'.$prop.'_'.$name.'"' : '');
		}

		return $this;
	}

	/**
	 * Generate and return the complete SQL request of this EntityRequest
	 * @access public
	 * @return String The SQL request
	 */
	public function getSQLRequest()
	{
		if($this->select == '*')
		{
			$this->select	=	'';
			$this->generateSelectAll($this->fetchClassName);
			$this->select	=	substr($this->select,1);
		}

		if($this->orderBy == 1)
			$this->orderBy	=	$this->tableName.'.'.$this->idName;

		return 'SELECT '.$this->select.' FROM '.$this->tableName.$this->join.' WHERE '.$this->where.' ORDER BY '.$this->orderBy.$this->limit.';';
	}

	/**
	 * Execute the SQL query stored in this EntityRequest
	 * @access public
	 * @return EntityArray An EntityArray containing the entities gotten form the request
	 * @throws \Exception
	 */
	public function exec()
	{
		$query	=	Core::$current_db->query($this->getSQLRequest());
		$return	=	array();

		if($query)
		{
			if($query->rowCount()>0)
			{
				if($this->canFetchClass)
					$query->setFetchMode(\PDO::FETCH_CLASS|\PDO::FETCH_PROPS_LATE, $this->fetchClassName);
				else
					$query->setFetchMode(\PDO::FETCH_OBJ);

				while($obj = $query->fetch())
					$return[]	=	$obj;
			}

			if($this->totalToGet > 1)
				return $this->canFetchClass ? new EntityArray($this->fetchClassName, $return) : $return;
			else
				return isset($return[0]) ? $return[0] : null;
		}

		$error	=	Core::$current_db->errorInfo();
		throw new \Exception("EntityRequest::exec() : An error occurs while running the generated SQL request.\n".$this->getSQLRequest()."\n[SQLSTATE: ".$error[0].'][DriverCode: '.$error[1].'] => '.$error[2]."\n");
	}
}