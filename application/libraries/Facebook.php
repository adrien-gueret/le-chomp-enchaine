<?php

abstract class Library_Facebook
{
	const	TYPE_ARTICLE = 'article',
			LOCALE_FR_FR = 'fr_FR';

	protected static function _getMetaProperties()
	{
		return [
			'og' => [
				'title', 'site_name', 'image', 'url', 'description', 'type', 'locale'
			],
			'fb' => [
				'app_id'
			],
			'article' => [
				'author', 'publisher', 'expiration_time', 'modified_time', 'published_time', 'section', 'tag'
			]
		];
	}

	public static function setMetaOG(Array $metas)
	{
		$tpl_facebook_meta_og = null;

		$structure = self::_getMetaProperties();

		foreach ($metas as $type => $properties) {
			if ( ! isset($structure[$type])) {
				continue;
			}

			foreach ($properties as $property => $value) {
				if ( ! in_array($property, $structure[$type])) {
					continue;
				}

				$tpl_facebook_meta_og	.=	Eliya\Tpl::get('facebook/metas', [
					'type'		=>	$type,
					'property'	=>	$property,
					'value'		=>	$value,
				]);
			}
		}

		Eliya\Tpl::set('facebook_meta_og', $tpl_facebook_meta_og);
	}
}