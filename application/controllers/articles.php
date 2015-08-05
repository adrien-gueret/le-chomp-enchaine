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

			$newspaper = $article->load('newspaper');
			$author = $article->load('author');

			$isPublished = ! empty($newspaper) && ! is_null($newspaper->prop('date_publication')) && ! empty($author);
			$canReadUnpublished = $this->_currentUser->hasPermission(Model_Groups::PERM_READ_UNPUBLISHED_ARTICLES);

			if ( ! $isPublished && ! $canReadUnpublished) {
				$this->response->error('L\'article demandé n\'est pas ou plus publié.', 403);
				return;
			}

			$canonical_url = $article->getUrl();

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
				]
			]);

			\Eliya\Tpl::set('page_title', $article->prop('title'));
			\Eliya\Tpl::set('page_description', $article->prop('introduction'));
			\Eliya\Tpl::set('canonical_url', $canonical_url);

			$previousArticle = $article->getPreviousArticle();
			$nextArticle = $article->getNextArticle();

			if(empty($previousArticle)) {
				$tpl_previous_article	=	\Eliya\Tpl::get('articles/no_sibbling_article');
			} else {
				$tpl_previous_article	=	\Eliya\Tpl::get('articles/previous_article_link', [
					'article'	=>	$previousArticle,
				]);
			}

			if(empty($nextArticle)) {
				$tpl_next_article	=	\Eliya\Tpl::get('articles/no_sibbling_article');
			} else {
				$tpl_next_article	=	\Eliya\Tpl::get('articles/next_article_link', [
					'article'	=>	$nextArticle,
				]);
			}

			$this->response->set(\Eliya\Tpl::get('articles/article', [
				'article' 				=>	$article,
				'newspaper' 			=>	$article->load('newspaper'),
				'tpl_previous_article'	=>	$tpl_previous_article,
				'tpl_next_article'		=>	$tpl_next_article,
			]));
		}
	}