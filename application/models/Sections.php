<?php
	class Model_Sections extends EntityPHP\Entity
	{
		protected static $table_name = 'sections';

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
