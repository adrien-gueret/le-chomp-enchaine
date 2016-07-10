<?php
	class Model_Categories extends EntityPHP\Entity
	{
		use Trait_Picture;

		protected static $table_name = 'categories';

		protected $name;

		public function __construct($name = null)
		{
			parent::__construct();
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

		protected function _getMainPictureRootFolder()
		{
			return 	'img/categories/';
		}

		protected function _getAppendedTimestamp()
		{
			return '';
		}
	}
