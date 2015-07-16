<?php

namespace EntityPHP;

//We don't extend ArrayIterator, because I don't like it =D
final class EntityArray implements \SeekableIterator, \ArrayAccess, \Countable
{
	private $i		=	0;
	private $array	=	array();
	private $entity	=	'';

	/* SeekableIterator */
	public function current()
	{
		return $this->offsetGet($this->i);
	}

	public function key()
	{
		return $this->i;
	}

	public function next()
	{
		$this->i++;
	}

	public function valid()
	{
		return isset($this->array[$this->i]);
	}

	public function rewind()
	{
		$this->i	=	0;
	}

	public function seek($i)
	{
		$old		=	$this->i;
		$this->i	=	$i;

		if(!$this->valid())
			$this->i	=	$old;
	}

	/* ArrayAccess */
	public function offsetExists($i)
	{
		return isset($this->array[$i]);
	}

	public function offsetGet($i)
	{
		return $this->offsetExists($i) ? $this->array[$i] : null;
	}

	public function offsetSet($i,$value)
	{
		if($value instanceof $this->entity)
		{
			if($value->existsInDB())
				$this->array[$i]	=	$value;
			else
				throw new \Exception('EntityArray : the given value must be saved in the DB.');
		}
		else
			throw new \Exception('EntityArray : the given value must be an instance of "'.$this->entity.'".');
	}

	public function offsetUnset($i)
	{
		unset($this->array[$i]);
	}


	/* Countable */
	public function count(Entity $entity = null)
	{
		if(is_null($entity))
			return count($this->array);

		$i	=	0;

		foreach($this->array as $obj)
			if($obj->equals($entity))
				$i++;

		return $i;
	}

	/* EntityArray */
	public function __construct($className, $array	=	array())
	{
		if(is_string($className))
		{
			$this->array	=	$array;
			$this->entity	=	$className;
		}
		else
			throw new \Exception('First parameter of EntityArray must be an Entity classname.');
	}

	/**
	 * Return the array stored in the EntityArray
	 * @access public
	 * @return Array The array of the EntityArray
	 */
	public function getArray()
	{
		return $this->array;
	}

	/**
	 * Return the first Entity contained in the array
	 * @access public
	 * @return Entity The first Entity
	 */
	public function getFirst()
	{
		return $this->offsetGet(0);
	}

	/**
	 * Return the last Entity contained in the array
	 * @access public
	 * @return Entity The last Entity
	 */
	public function getLast()
	{
		return $this->offsetGet($this->count()-1);
	}

	/**
	 * Get randomly one or several entities from the array
	 * @access public
	 * @param  int $total Number of entities to get
	 * @return Entity|EntityArray The Entity randomly gotten if $total is 1, an EntityArray otherwise
	 */
	public function getRandom($total = 1)
	{
		$total	=	max(1,min(count($this->array),intval($total)));

		if($total == 1)
			return $this->offsetGet(rand(0,$this->count()-1));

		if($total == $this->count())
		{
			$return	=	new EntityArray($this->entity, $this->array);
			return $return->shuffle();
		}

		$temp	=	$this->array;
		$return	=	array();
		for($i = 0; $i < $total; $i++)
		{
			$obj		=	array_splice($temp, rand(0, count($temp) - 1), 1);
			$return[]	=	$obj[0];
		}

		return new EntityArray($this->entity, $return);
	}

	/**
	 * Add an entity at the end of the array
	 * @access public
	 * @param  Entity $obj The entity to add
	 * @return EntityArray The calling EntityArray
	 */
	public function push(Entity $obj)
	{
		if($obj instanceof $this->entity)
		{
			$this->array[]	=	$obj;
			return $this;
		}

		throw new \Exception('EntityArray::push(Entity $obj) : the given value must be an instance of "'.$this->entity.'".');
	}

	/**
	 * Remove an Entity from the array at the given index
	 * @access public
	 * @param Int $i The index to remove
	 * @return EntityArray The calling EntityArray
	 * @throws \Exception
	 */
	public function removeIndex($i=0)
	{
		if(isset($this->array[$i]))
		{
			unset($this->array[$i]);
			return $this;
		}

		throw new \Exception('EntityArray::removeIndex(Int $i) : no instances found at index '.$i.'.');
	}

	/**
	 * Remove the given Entity from the array
	 * @access public
	 * @param Entity $entity The entity to remove (default: null)
	 * @param Bool $justFirst If true, remove only the first found entity. Remove all found entities otherwise. (default: true)
	 * @return EntityArray The calling EntityArray
	 */
	public function remove(Entity $entity=null, $justFirst=true)
	{
		foreach($this->array as $key => $obj)
		{
			if($obj->equals($entity))
			{
				unset($this->array[$key]);

				if($justFirst)
					return $this;
			}
		}
		return $this;
	}

	/**
	 * Reverse the order of the array
	 * @access public
	 * @return EntityArray The calling EntityArray
	 */
	public function reverse()
	{
		$this->array	=	array_reverse($this->array);
		return $this;
	}

	/**
	 * Suffle the array
	 * @access public
	 * @return EntityArray The calling EntityArray
	 */
	public function shuffle()
	{
		shuffle($this->array);
		return $this;
	}

	/**
	 * Order the array via given
	 * @access public
	 * @param Callable $func Function to send to usort() in order to order the array
	 * @return EntityArray The calling EntityArray
	 */
	public function sort(Callable $func)
	{
		usort($this->array, $func);
		return $this;
	}

	/**
	 * Indicates if the array contains the given Entity
	 * @access public
	 * @param Entity $other The Entity to check
	 * @return Bool True if the entity is in the array. False otherwise.
	 */
	public function hasEntity(Entity $other)
	{
		foreach($this->array as $obj)
		{
			if($other->equals($obj))
				return true;
		}

		return false;
	}

	/**
	 * Removes duplicate values from the array
	 * @access public
	 * @param Bool $set If True, the array will be changed. If False, the method will simply return a new EntityArray without duplicate values. (default: True)
	 * @return EntityArray The calling filtered EntityArray if $set is True. A filtered copy of this EntityArray otherwise.
	 */
	public function unique($set = true)
	{
		$tempEntity	=	new EntityArray($this->entity);

		foreach($this->array as $obj)
			if(!$tempEntity->hasEntity($obj))
				$tempEntity->array[]	=	$obj;

		if($set)
		{
			$this->array	=	$tempEntity->getArray();
			return $this;
		}

		return $tempEntity;
	}

	/**
	 * Removes all values from the array
	 * @access public
	 * @return EntityArray The calling filtered EntityArray if $set is True. A filtered copy of this EntityArray otherwise.
	 */
	public function removeAll()
	{
		$this->array	=	array();
		return $this;
	}

	/**
	 * Check if EntityArray is empty or not
	 * @access public
	 * @return EntityArray The calling EntityArray.
	 */
	public function isEmpty()
	{
		return empty($this->array);
	}

	/**
	 * Allow to echo an EntityArray
	 * @access public
	 * @return string The string representation of the Entity
	 */
	public function __toString()
	{
		return '<pre>'.print_r($this->array, true).'</pre>';
	}
}