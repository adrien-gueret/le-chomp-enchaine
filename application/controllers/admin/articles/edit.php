<?php
	class Controller_admin_articles_edit extends Controller_admin_articles
	{
		public function get_index($id = null)
		{
			$article = Model_Articles::getById($id);

			if (empty($article)) {
				$this->response
						->status(404)
						->redirectToFullErrorPage(false)
						->set(\Eliya\Tpl::get('admin/articles/edit/not_found'));
				return;
			}

			$author_is_current_user = $article->load('author')->equals($this->_currentUser);
			$can_edit_other_articles = $this->_currentUser->hasPermission(Model_Groups::PERM_EDIT_OTHER_ARTICLES);

			if ( ! $author_is_current_user && ! $can_edit_other_articles) {
				$this->response->error('Vous ne pouvez pas modifier cet article.', 403);
				return;
			}

			$article->fileSrc = $article->getMainPictureURL();

			$this->response->set(\Eliya\Tpl::get('admin/articles/edit/index', [
				'article'		=>	$article,
				'section'		=>	$article->load('section'),
				'all_sections'	=>	Model_Sections::getAll()
			]));
		}

		public function put_index($id, $title, $introduction, $content, $id_section, $base64img = null)
		{
			// First, get article to update
			$article = Model_Articles::getById($id);

			// Get all images from the last version
			$old_images = array_filter($article->getContentImagesUrl(), function($link) {
				// Keep only images stored in our own server
				return strpos($link, STATIC_URL) !== false;
			});

			// Update main picture
			$article->updateMainPicture($base64img);

			// Update all other properties
			$article->setProps([
				'title'				=>	$title,
				'introduction'		=>	$introduction,
				'content'			=>	$content,
				'section'			=>	Model_Sections::getById($id_section),
				'date_last_update'	=> $_SERVER['REQUEST_TIME']
			]);

			// Then get images after this update
			$new_images = $article->getContentImagesUrl();

			// Old images not in new images have to be removed
			$images_to_remove = array_diff($old_images, $new_images);
			foreach ($images_to_remove as $url) {
				$article->deletePicture($url);
			}

			// New images not stored in our server have to be downloaded
			$images_to_download = array_filter($new_images, function($link) {
				return strpos($link, STATIC_URL) === false;
			});

			$urls_to_replace	=	[];
			foreach ($images_to_download as $url) {
				$public_url	=	$article->downloadPicture($url);

				if (empty($public_url)) {
					continue;
				}

				// Stored URL from our server where picture has been downloaded
				$urls_to_replace[$url]	=	$public_url;
			}

			// Replace all occurences of downloaded images with the new public URLs
			$content = strtr($content, $urls_to_replace);
			$article->prop('content', $content);

			// Don't forget to load author and newspaper to not erase them!
			$article->load('author');
			$article->load('newspaper');

			Model_Articles::update($article);

			$this->get_index($id);
		}
	}