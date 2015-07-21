<?php

class Model_Articles extends EntityPHP\Entity {
	const DEFAULT_IMAGE_URL =	'img/articles/no_image.png';
	const ALLOWED_EXTENSION	=	'png';

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

	public function getUrl()
	{
		return BASE_URL.'articles/'.$this->getId().'-'.Library_String::makeUrlCompliant($this->title);
	}

	private function _getMainPictureFolder()
	{
		return 	'img/articles/' . $this->getId() . '/';
	}

	private function _getMainPictureFileName($timestamp = null)
	{
		$appendedTimestamp = $timestamp ?: $this->date_last_update;

		if (!is_numeric($appendedTimestamp)) {
			$dateTime = new DateTime($appendedTimestamp);
			$appendedTimestamp = $dateTime->getTimestamp();
		}

		return md5($this->getId().$appendedTimestamp).'.'.self::ALLOWED_EXTENSION;
	}

	private function _getMainPicturePhysicalFolder()
	{
		return PUBLIC_FOLDER_PATH.$this->_getMainPictureFolder();
	}

	private function _getMainPicturePhysicalPath($timestamp = null)
	{
		return $this->_getMainPicturePhysicalFolder().$this->_getMainPictureFileName($timestamp);
	}

	private function _getMainPictureData()
	{
		$path = $this->_getMainPicturePhysicalPath();

		if (!file_exists($path))
			return false;

		return file_get_contents($path);
	}

	private function _getMainPicturePublicFolder()
	{
		return STATIC_URL.$this->_getMainPictureFolder();
	}

	public function getMainPictureURL()
	{
		if (file_exists($this->_getMainPicturePhysicalPath()))
		{
			return $this->_getMainPicturePublicFolder().$this->_getMainPictureFileName();
		}

		return STATIC_URL.self::DEFAULT_IMAGE_URL;
	}

	private function _createPictureFolder()
	{
		$picture_folder = $this->_getMainPicturePhysicalFolder();

		if ( ! is_dir($picture_folder)) {
			mkdir($picture_folder);
			chmod($picture_folder, 0777);
			return true;
		}

		return false;
	}

	private function _deleteMainPicture()
	{
		$path = $this->_getMainPicturePhysicalPath();

		if (file_exists($path))
			return unlink($path);

		return false;
	}

	public function updateMainPicture($data_url = null)
	{
		$image_data = null;
		
		if (empty($data_url))
			$image_data	=	base64_encode($this->_getMainPictureData());
		else
			$image_data	=	substr($data_url, strpos($data_url, ',') + 1);

		if (empty($image_data))
			return false;

		if ( ! $this->_deleteMainPicture())
			$this->_createPictureFolder();

		$resource	=	imagecreatefromstring(base64_decode($image_data));

		$target_path =	$this->_getMainPicturePhysicalPath($_SERVER['REQUEST_TIME']);

		imagealphablending($resource, true);
		imagesavealpha($resource, true);
		imagepng($resource, $target_path);

		return chmod($target_path, 0777);
	}
}
