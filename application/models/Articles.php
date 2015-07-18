<?php

class Model_Articles extends EntityPHP\Entity {
	const DEFAULT_IMAGE_URL =	'img/articles/no_image.png';

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
			SELECT id, id_author, id_section, title FROM articles
			WHERE id NOT IN (
				SELECT DISTINCT id_articles
				FROM newspapers2articles
			)
		');

		return is_array($result) ? $result : [];
	}

	public function getMainPictureFolder() {
		return 	'img/articles/' . $this->getId() . '/';
	}

	public function createPictureFolder() {
		$picture_folder = PUBLIC_FOLDER_PATH.$this->getMainPictureFolder();

		if ( ! is_dir($picture_folder)) {
			mkdir($picture_folder);
			chmod($picture_folder, 0777);
			return true;
		}

		return false;
	}

	public function getMainPicturePath()
	{
		$extensions = ['png', 'jpg', 'jpeg'];
		$base_path = $this->getMainPictureFolder() . $this->getId() . '.';

		foreach($extensions as $extension)
		{
			$path = $base_path.$extension;

			if(is_file(PUBLIC_FOLDER_PATH.$path))
				return $path;
		}

		return self::DEFAULT_IMAGE_URL;
	}

	public function getMainPictureURL()
	{
		return STATIC_URL.$this->getMainPicturePath();
	}

	public function deleteMainPicture() {
		$current_image_url = $this->getMainPicturePath();
		$physical_path = PUBLIC_FOLDER_PATH.$current_image_url;

		if ($current_image_url !== self::DEFAULT_IMAGE_URL && is_file($physical_path)) {
			return unlink($physical_path);
		}

		return false;
	}

	public function updateMainPicture($data_url) {
		if ( ! $this->deleteMainPicture()) {
			$this->createPictureFolder();
		}

		$image_data	=	substr($data_url, strpos($data_url, ',') + 1);
		$resource	=	imagecreatefromstring(base64_decode($image_data));

		$target_url =	PUBLIC_FOLDER_PATH.$this->getMainPictureFolder() . $this->getId() . '.png';

		imagepng($resource, $target_url);

		chmod($target_url, 0777);
	}

	public function getUrl() {
		return BASE_URL.'articles/'.$this->getId().'-'.Library_String::makeUrlCompliant($this->title);
	}
}
