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
	protected $category;

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
			'category' => 'Model_Categories',
		];
	}

	protected static function _getArticleObjectFromData(\stdClass $article_data)
	{
		$author = new Model_Users($article_data->author_username);
		$author->prop('id', $article_data->author_id);

		$category = new Model_Categories($article_data->category_name);
		$category->prop('id', $article_data->category_id);

		return new Model_Articles([
			'id' => $article_data->id,
			'title' => $article_data->title,
			'introduction' => $article_data->introduction,
			'content' => $article_data->content,
			'date_publication' => $article_data->date_publication,
			'date_last_update' => $article_data->date_last_update,
			'is_published' => $article_data->is_published,
			'author' => $author,
			'category' => $category,
		]);
	}

	public static function getUnpublished()
	{
		$result = self::createRequest(true)
						->select('id, title, category')
						->orderBy('category.id DESC')
						->where('is_published = ?', [0])
						->exec();

		return is_array($result) ? $result : [];
	}

	public static function getLast($page = 1, $total = 10, Model_Categories $category = null)
	{
		$startIndex = $page - 1;
		$where = 'is_published = ?';
		$whereValues = [1];

		if(!empty($category)) {
			$where	.=	' AND category.id = ?';
			$whereValues[]	=	$category->getId();
		}

		return self::createRequest(true)
						->where($where, $whereValues)
						->orderBy('date_publication DESC')
						->getOnly($total, $startIndex * $total)
						->exec();
	}

	public static function countByCategory(Model_Categories $category)
	{
		return Model_Articles::count('category.id = ? AND is_published = ?', [$category->getId(), 1]);
	}

	public static function countAllByCategories()
	{
		$resultObjects = \EntityPHP\EntityRequest::executeSQL('
			SELECT id_category, COUNT(id) AS total
			FROM articles
			WHERE is_published = 1
			GROUP BY id_category '
		);

		$arrayToReturn = [];

		foreach($resultObjects as $object) {
			$arrayToReturn[$object->id_category] = $object->total;
		}

		return $arrayToReturn;
	}

	public function getPrepublishedId() {
		return md5('prepublished'.$this->getId());
	}

	public function getUrl($prepublished = false)
	{
		$url = BASE_URL.'articles/'.$this->getId().'-'.Library_String::makeUrlCompliant($this->title);

		if ($prepublished) {
			$url .= '?c='.$this->getPrepublishedId();
		}

		return $url;
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
