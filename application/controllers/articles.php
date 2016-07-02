<?php
	class Controller_articles extends Controller_index
	{
		public function get_index($id_article = 0)
		{
			$article = Model_Articles::getById($id_article);

			if (empty($article)) {
				$this->response->error('L\'article demandé est introuvable.', 404);
				return;
			}

			$author = $article->load('author');

			$isPublished = $article->prop('is_published');
			$canReadUnpublished = $this->_currentUser->hasPermission(Model_Groups::PERM_READ_UNPUBLISHED_ARTICLES);
			
			if ( ! $isPublished && ! $canReadUnpublished && ! $this->_currentUser->equals($author)) {
				$this->response->error('L\'article demandé n\'est pas ou plus publié.', 403);
				return;
			}

			$canonical_url = $article->getUrl();

			$og_article = [
				'publisher' => \Eliya\Config('main')->FACEBOOK['PAGE_URL'],
				'category' => $article->load('category')->prop('name'),
				'modified_time' => $article->prop('date_last_update'),
				'published_time' => $article->prop('date_publication'),
			];

			Library_Facebook::setMetaOG([
				'og' => [
					'title'	=>	$article->prop('title'),
					'description'	=>	$article->prop('introduction'),
					'site_name' => \Eliya\Config('main')->SITE_NAME,
					'url' => $canonical_url,
					'image' => $article->getMainPictureURL(),
					'type' => Library_Facebook::TYPE_ARTICLE,
					'locale' => Library_Facebook::LOCALE_FR_FR,
				],
				'fb' => [
					'app_id' => \Eliya\Config('main')->FACEBOOK['APP_ID']
				],
				'article' => $og_article
			]);

			\Eliya\Tpl::set('page_title', $article->prop('title'));
			\Eliya\Tpl::set('page_description', $article->prop('introduction'));
			\Eliya\Tpl::set('canonical_url', $canonical_url);

			$this->response->set(\Eliya\Tpl::get('articles/article', [
				'article' =>	$article,
			]));
		}
	}