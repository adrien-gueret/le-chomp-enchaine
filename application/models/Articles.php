<?php

class Model_Articles extends EntityPHP\Entity {
	use Trait_Picture;

	protected static $table_name = 'articles';

	protected $title;
	protected $introduction;
	protected $content;
	protected $date_last_update;
	protected $author;
	protected $section;
	protected $newspaper;

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

	public static function getFromNewspaper(Model_Newspapers $newspaper)
	{
		$articles = self::createRequest()
						->select('id, title, introduction, content, date_last_update, author.username, author.id, section, newspaper')
						->where('newspaper.id = ?', [$newspaper->getId()])
						->exec();

		$articles = is_array($articles) ? $articles : [];

		if (empty($articles))
			return $articles;

		$formattedArticles = [];

		foreach($articles as $article_data)
			$formattedArticles[] = self::_getArticleObjectFromData($article_data);

		return $formattedArticles;
	}

	public static function getFullDataById($id_article)
	{
		$article_data = static::createRequest()
					->select('id, title, introduction, content, date_last_update, author.username, author.id, section, newspaper')
					->where('id=?', [intval($id_article)])
					->getOnly(1)
					->exec();

		if (empty($article_data))
			return null;

		return self::_getArticleObjectFromData($article_data);
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
