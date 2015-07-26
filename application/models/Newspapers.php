<?php
	class Model_Newspapers extends EntityPHP\Entity
	{
		use Trait_Picture;

		protected static $table_name = 'newspapers';

		protected $articles;
		protected $name;
		protected $date_publication;

		public static function getByIdArticle($id_article)
		{
			return self::createRequest()
					->where('articles.id=?', [intval($id_article)])
					->getOnly(1)
					->exec();
		}

		public static function getAllPublished()
		{
			return self::createRequest()
					->where('date_publication IS NOT NULL')
					->orderBy('date_publication DESC')
					->exec();
		}

		public function __construct($name = 'Sans titre')
		{
			$this->name	=	$name;
			$this->articles	=	[];
			$this->date_publication	=	null;
		}

		public static function __structure()
		{
			return [
				'name' => 'VARCHAR(255)',
				'date_publication' => 'DATETIME',
				'articles' => array('Model_Articles')
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
