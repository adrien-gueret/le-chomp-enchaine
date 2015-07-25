<?php
	class Controller_articles extends Controller_index
	{
		public function get_index($id_article = 0)
		{
			$article = Model_Articles::getFullDataById($id_article);
			$newspaper = Model_Newspapers::getByIdArticle($article->getId());

			if (empty($article)) {
				$this->response->error('L\'article demandé est introuvable', 404);
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

			$this->response->set(\Eliya\Tpl::get('articles/article', [
				'article' 	=> $article,
				'newspaper' => $newspaper,
			]));
		}
	}