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
			'section' => 'Model_Sections'
		];
	}

	public static function getUnpublished()
	{
		$result	=	\EntityPHP\EntityRequest::executeSQL('
			SELECT articles.id, articles.id_author, articles.id_section, articles.title, sections.name AS section_name
			FROM articles
			JOIN sections ON articles.id_section = sections.id
			WHERE articles.id NOT IN (
				SELECT DISTINCT id_articles
				FROM newspapers2articles
			)
			ORDER BY articles.id_section
		');

		return is_array($result) ? $result : [];
	}

	public static function getFullDataById($id_article)
	{
		$article_data = static::createRequest()
					->select('id, title, introduction, content, date_last_update, author.username, author.id, section')
					->where('id=?', [intval($id_article)])
					->getOnly(1)
					->exec();

		if (empty($article_data))
			return null;

		$author = new Model_Users($article_data->author_username);
		$author->prop('id', $article_data->author_id);

		$section = new Model_Sections($article_data->section_name);
		$section->prop('id', $article_data->section_id);

		return new Model_Articles([
			'id' => $article_data->id,
			'title' => $article_data->title,
			'introduction' => $article_data->introduction,
			'content' => $article_data->content,
			'date_last_update' => $article_data->date_last_update,
			'author' => $author,
			'section' => $section,
		]);
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
