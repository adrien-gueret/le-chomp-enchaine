<?php

trait Trait_Picture
{
	protected static $_allowed_extension = 'png';

	abstract protected function _getMainPictureRootFolder();
	abstract protected function _getAppendedTimestamp();

	protected function _getDefaultImageUrl()
	{
		return 	$this->_getMainPictureRootFolder().'no_image.png';
	}

	protected function _getMainPictureFolder()
	{
		return 	$this->_getMainPictureRootFolder().$this->getId().'/';
	}

	protected function _getMainPictureFileName($timestamp = null)
	{
		$appendedTimestamp = $timestamp ?: $this->_getAppendedTimestamp();

		if (!is_numeric($appendedTimestamp)) {
			$dateTime = new DateTime($appendedTimestamp);
			$appendedTimestamp = $dateTime->getTimestamp();
		}

		return md5($this->getId().$appendedTimestamp).'.'.self::$_allowed_extension;
	}

	protected function _getMainPicturePhysicalFolder()
	{
		return PUBLIC_FOLDER_PATH.$this->_getMainPictureFolder();
	}

	protected function _getMainPicturePhysicalPath($timestamp = null)
	{
		return $this->_getMainPicturePhysicalFolder().$this->_getMainPictureFileName($timestamp);
	}

	protected function _getMainPictureData()
	{
		$path = $this->_getMainPicturePhysicalPath();

		if (!file_exists($path))
			return false;

		return file_get_contents($path);
	}

	protected function _getMainPicturePublicFolder()
	{
		return STATIC_URL.$this->_getMainPictureFolder();
	}

	public function getMainPictureURL()
	{
		if (file_exists($this->_getMainPicturePhysicalPath()))
		{
			return $this->_getMainPicturePublicFolder().$this->_getMainPictureFileName();
		}

		return STATIC_URL.$this->_getDefaultImageUrl();
	}

	protected function _createPictureFolder()
	{
		$picture_folder = $this->_getMainPicturePhysicalFolder();

		if ( ! is_dir($picture_folder)) {
			mkdir($picture_folder);
			chmod($picture_folder, 0777);
			return true;
		}

		return false;
	}

	protected function _deleteMainPicture()
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

	public function downloadPicture($picture_url)
	{
		do {
			$file_name		=	uniqid('image_').'.png';
			$physical_path	=	$this->_getMainPicturePhysicalFolder().$file_name;
		} while(file_exists($physical_path));

		$public_path	=	$this->_getMainPicturePublicFolder().$file_name;

		$success	=	copy($picture_url, $physical_path);

		return $success ? $public_path : false;
	}

	public function deletePicture($url)
	{
		$file_name		=	basename($url);
		$physical_path	=	$this->_getMainPicturePhysicalFolder().$file_name;

		if (file_exists($physical_path)) {
			return unlink($physical_path);
		}

		return false;
	}
}