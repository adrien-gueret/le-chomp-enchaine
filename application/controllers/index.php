<?php
	class Controller_index extends Controller_main
	{
		const ARTICLES_BY_PAGE = 10;

		public function get_index($page = 1)
		{
			$articles 		=	Model_Articles::getLast($page, self::ARTICLES_BY_PAGE);

			if ($articles->isEmpty())
			{
				$this->response->set(\Eliya\Tpl::get('index/no_articles'));
				return;
			}

			$tpl_articles	=	Eliya\Tpl::get('common/articles/list', ['articles' => $articles]);

			$nbrPages		=	ceil(Model_Articles::count('is_published = ?', [1]) / self::ARTICLES_BY_PAGE);

			if($page == 1) {
				\Eliya\Tpl::set('canonical_url', BASE_URL);
			}

			$this->response->set(\Eliya\Tpl::get('index/index', [
				'tpl_articles' => $tpl_articles,
				'nbr_pages' => $nbrPages,
				'current_page' => $page
			]));
		}
	}