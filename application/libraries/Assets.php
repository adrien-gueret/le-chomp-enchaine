<?php

abstract class Library_Assets
{
	protected static $assets_config = null;

	protected static function _getConfig()
	{
		if (self::$assets_config === null) {
			try {
				self::$assets_config = \Eliya\Config('assets');
			} catch (Exception $e) {
				self::$assets_config = false;
			}
		}
	}

	public static function get($file_path)
	{
		self::_getConfig();

		$normal_path = STATIC_URL.$file_path;

		// No assets.json file: simply return the normal path
		if (self::$assets_config === false)
			return $normal_path;

		$cache_broken_path = self::$assets_config->$file_path;

		// File is not cache broken: return normal path
		if (empty($cache_broken_path))
			return $normal_path;

		return STATIC_URL.$cache_broken_path;
	}
}