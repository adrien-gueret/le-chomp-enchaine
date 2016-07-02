<?php

class Model_Articles extends EntityPHP\Entity {
	use Trait_Picture;

	protected static $table_name = 'articles';

	protected $title;
	protected $introduction;
	protected $content;
	protected $date_publication;
	protected $date_last_update;
	protected $is_published;
	protected $author;
	protected $section;

	const	MOVE_TO_TOP = 1,
			MOVE_TO_BOTTOM = 2;

	public function __construct(Array $props = array())
	{
		$this->date_publication = null;
		$this->date_last_update = $_SERVER['REQUEST_TIME'];
		$this->is_published = false;
		parent::__construct($props);
	}

	public static function __structure()
	{
		return [
			'title' => 'VARCHAR(255)',
			'introduction' => 'TEXT',
			'content' => 'TEXT',
			'date_publication' => 'DATETIME',
			'date_last_update' => 'DATETIME',
			'is_published' => 'TINYINT(1)',
			'author' => 'Model_Users',
			'section' => 'Model_Sections',
		];
	}

	protected static function _getArticleObjectFromData(\stdClass $article_data)
	{
		$author = new Model_Users($article_data->author_username);
		$author->prop('id', $article_data->author_id);

		$section = new Model_Sections($article_data->section_name);
		$section->prop('id', $article_data->section_id);

		return new Model_Articles([
			'id' => $article_data->id,
			'title' => $article_data->title,
			'introduction' => $article_data->introduction,
			'content' => $article_data->content,
			'date_publication' => $article_data->date_publication,
			'date_last_update' => $article_data->date_last_update,
			'is_published' => $article_data->is_published,
			'author' => $author,
			'section' => $section,
		]);
	}

	public static function getUnpublished()
	{
		$result = self::createRequest(true)
						->select('id, title, section')
						->orderBy('section.id')
						->where('is_published = ?', [0])
						->exec();

		return is_array($result) ? $result : [];
	}

	public function getUrl()
	{
		return BASE_URL.'articles/'.$this->getId().'-'.Library_String::makeUrlCompliant($this->title);
	}

	public function getContentImagesUrl()
	{
		$content = $this->prop('content');
		preg_match_all('#\[[a-zA-Z0-9_-]+\]: (https?:\/\/[^ ]+)(?:[\s]+|$)#isU', $content, $links);

		$images_links = [];

		if ( ! empty($links[1])) {
			$regexps = [
				'youtube_embed' => '/https?:\/\/www\.youtube\.com\/embed\/.+/i',
				'soundcloud_embed' => '/https?:\/\/api\.soundcloud\.com\/tracks\/.+/i',
				'mp3' => '/\.mp3(\?.*)?$/i',
			];

			$images_links	=	array_filter($links[1], function($link) use ($regexps) {
				foreach ($regexps as $regexp) {
					if (preg_match($regexp, $link)) {
						return false;
					}
				}

				return true;
			});
		}

		return $images_links;
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
