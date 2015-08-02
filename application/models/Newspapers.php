<?php
	class Model_Newspapers extends EntityPHP\Entity
	{
		use Trait_Picture;

		protected static $table_name = 'newspapers';

		protected $name;
		protected $date_publication;

		public static function getAllPublished()
		{
			return self::createRequest()
					->where('date_publication IS NOT NULL')
					->orderBy('date_publication DESC')
					->exec();
		}

		public function __construct($name = 'Sans titre', $date_publication = null)
		{
			$this->name	=	$name;
			$this->date_publication	=	$date_publication;
		}

		public static function __structure()
		{
			return [
				'name' => 'VARCHAR(255)',
				'date_publication' => 'DATETIME',
			];
		}

		public function getUrl()
		{
			return BASE_URL.'newspapers/'.$this->getId().'-'.Library_String::makeUrlCompliant($this->name);
		}

		protected function _getMainPictureRootFolder()
		{
			return 	'img/newspapers/';
		}

		protected function _getAppendedTimestamp()
		{
			return 	$this->date_publication;
		}
	}
