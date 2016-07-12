<?php
	class Model_Categories extends EntityPHP\Entity
	{
		use Trait_Picture;

		protected static $table_name = 'categories';

		protected $name;
		protected $description;

		public function __construct($name = null, $description = null)
		{
			parent::__construct();
			$this->name = $name;
			$this->description = $description;
		}

		public static function __structure()
		{
			return [
				'name' => 'VARCHAR(255)',
				'description' => 'TEXT'
			];
		}

		public function getUrl()
		{
			return BASE_URL.'categories/'.$this->getId().'-'.Library_String::makeUrlCompliant($this->name);
		}

		protected function _getMainPictureRootFolder()
		{
			return 	'img/categories/';
		}

		protected function _getAppendedTimestamp()
		{
			return 1;
		}
	}
