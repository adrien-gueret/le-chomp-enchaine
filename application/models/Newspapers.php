<?php
	class Model_Newspapers extends EntityPHP\Entity
	{
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
	}
