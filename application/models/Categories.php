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

		public function getUrl()
		{
			return BASE_URL.'categories/'.$this->getId().'-'.Library_String::makeUrlCompliant($this->name);
		}
	}
