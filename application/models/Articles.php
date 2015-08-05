<?php

class Model_Articles extends EntityPHP\Entity {
	use Trait_Picture;

	protected static $table_name = 'articles';

	protected $title;
	protected $introduction;
	protected $content;
	protected $date_last_update;
	protected $position;
	protected $author;
	protected $section;
	protected $newspaper;

	const	MOVE_TO_TOP = 1,
			MOVE_TO_BOTTOM = 2;

	public function __construct(Array $props = array())
	{
		$this->date_last_update = $_SERVER['REQUEST_TIME'];
		parent::__construct($props);
	}

	public static function __structure()
	{
		return [
			'title' => 'VARCHAR(255)',
			'introduction' => 'TEXT',
			'content' => 'TEXT',
			'date_last_update' => 'DATETIME',
			'position' => 'TINYINT(1)',
			'author' => 'Model_Users',
			'section' => 'Model_Sections',
			'newspaper' => 'Model_Newspapers',
		];
	}

	protected static function _getArticleObjectFromData(\stdClass $article_data)
	{
		$author = new Model_Users($article_data->author_username);
		$author->prop('id', $article_data->author_id);

		$section = new Model_Sections($article_data->section_name);
		$section->prop('id', $article_data->section_id);

		$newspaper = new Model_Newspapers($article_data->newspaper_name, $article_data->newspaper_date_publication);
		$newspaper->prop('id', $article_data->newspaper_id);

		return new Model_Articles([
			'id' => $article_data->id,
			'title' => $article_data->title,
			'introduction' => $article_data->introduction,
			'content' => $article_data->content,
			'date_last_update' => $article_data->date_last_update,
			'position' => $article_data->position,
			'author' => $author,
			'section' => $section,
			'newspaper' => $newspaper,
		]);
	}

	public static function getUnpublished()
	{
		$result = self::createRequest(true)
						->select('id, title, section')
						->where('newspaper.id IS NULL')
						->orderBy('section.id')
						->exec();

		return is_array($result) ? $result : [];
	}

	protected static function _getIdsFromNewspaper($id_newspaper)
	{
		return self::createRequest()
					->select('id')
					->where('newspaper.id = ?', [$id_newspaper])
					->orderBy('position')
					->exec();
	}

	public static function getFromNewspaper(Model_Newspapers $newspaper)
	{
		$articles = self::createRequest()
						->select('id, title, introduction, position,content, date_last_update,
								author.username, author.id, section, newspaper')
						->where('newspaper.id = ?', [$newspaper->getId()])
						->orderBy('position')
						->exec();

		$articles = is_array($articles) ? $articles : [];

		if (empty($articles))
			return $articles;

		$formattedArticles = [];

		foreach($articles as $article_data)
			$formattedArticles[] = self::_getArticleObjectFromData($article_data);

		return $formattedArticles;
	}

	// TODO: is now unused
	public static function getFullDataById($id_article)
	{
		$article_data = static::createRequest()
					->select('id, title, introduction, position, content, date_last_update,
							author.username, author.id, section, newspaper')
					->where('id=?', [intval($id_article)])
					->getOnly(1)
					->exec();

		if (empty($article_data))
			return null;

		return self::_getArticleObjectFromData($article_data);
	}

	public static function updateArticlePosition(Model_Articles $article, $moveTo)
	{
		// Get current and next position
		$originalPosition	=	$article->prop('position');
		$deltaPosition		=	$moveTo == self::MOVE_TO_BOTTOM ? 1 : -1;

		$newPosition = $originalPosition + $deltaPosition;

		$newspaper = $article->load('newspaper');

		// We want to move an article, but we have to also move the one which is already on the targetted position!
		$otherArticleQuery	=	'UPDATE ' . self::$table_name . ' SET position = ' . $originalPosition;
		$otherArticleQuery	.=	' WHERE id_newspaper = ' . $newspaper->getId();
		$otherArticleQuery	.=	' AND position = ' . $newPosition;
		$otherArticleQuery	.=	' LIMIT 1';
		\EntityPHP\EntityRequest::executeSQL($otherArticleQuery);

		// We can now update our article
		$article->load('author');
		$article->load('section');
		$article->prop('position', $newPosition);

		self::update($article);

		return $article;
	}

	public static function cleanArticlesPositions($id_newspaper) {
		$articlesIds = self::_getIdsFromNewspaper($id_newspaper);

		foreach($articlesIds as $index => $article) {
			// Vanilla SQL for performancess reasons
			$query	=	'UPDATE ' . self::$table_name . ' SET position = ' . ($index + 1);
			$query	.=	' WHERE ' . self::$id_name .' = ' . $article->id;
			\EntityPHP\EntityRequest::executeSQL($query);
		}
	}

	public function getPreviousArticle()
	{
		$newspaper = $this->load('newspaper');
		if(empty($newspaper))
			return null;

		return self::createRequest()
			->where('position < ? AND newspaper.id = ?', [$this->prop('position'), $newspaper->getId()])
			->getOnly(1)
			->orderBy('position DESC')
			->exec();
	}

	public function getNextArticle()
	{
		$newspaper = $this->load('newspaper');
		if(empty($newspaper))
			return null;

		return self::createRequest()
				->where('position > ? AND newspaper.id = ?', [$this->prop('position'), $newspaper->getId()])
				->getOnly(1)
				->exec();
	}

	public function getUrl()
	{
		return BASE_URL.'articles/'.$this->getId().'-'.Library_String::makeUrlCompliant($this->title);
	}

	protected function _getMainPictureRootFolder()
	{
		return 	'img/articles/';
	}

	protected function _getAppendedTimestamp()
	{
		return 	$this->date_last_update;
	}
}
