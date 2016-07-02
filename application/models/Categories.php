<?php
	class Model_Categories extends EntityPHP\Entity
	{
		protected static $table_name = 'categories';

		protected $name;

		public function __construct($name = null)
		{
			$this->name = $name;
		}

		public static function __structure()
		{
			return [
				'name' => 'VARCHAR(255)'
			];
		}
	}
