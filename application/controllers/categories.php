<?php
	class Controller_categories extends Controller_index
	{
		const ARTICLES_BY_PAGE = 10;

		public function get_index($id_category = 0, $page = 1)
		{
			if ($id_category === 0) {
				$this->displayList();
			} else {
				$category = Model_Categories::getById($id_category);

				if(empty($category))
				{
					$this->response->error('La catégorie demandée est introuvable.', 404);
				}
				else
				{
					$this->displayDetails($category, $page);
				}
			}
		}

		private function displayList()
		{
			$categories = Model_Categories::getAll();
			$categoriesToReturn = [];

			$articlesCounts = Model_Articles::countAllByCategories();

			foreach($categories as $category)
			{
				if (!isset($articlesCounts[$category->getId()]))
				{
					continue;
				}

				$category->total_articles = $articlesCounts[$category->getId()];
				$categoriesToReturn[]	=	$category;
			}

			$this->response->set(\Eliya\Tpl::get('categories/list', [
				'categories' =>	$categoriesToReturn,
			]));
		}

		private function displayDetails(Model_Categories $category, $page)
		{
			if (empty($category)) {
				$this->response->error('La catégorie demandée est introuvable.', 404);
				return;
			}
			
			\Eliya\Tpl::set('page_title', $category->prop('name'));

			$articles	=	Model_Articles::getLast($page, self::ARTICLES_BY_PAGE, $category);

			if ($articles->isEmpty()) {
				$this->response->set(\Eliya\Tpl::get('categories/no_articles', [
					'category' => $category
				]));
				return;
			}

			$tpl_articles	=	Eliya\Tpl::get('common/articles/list', ['articles' => $articles]);

			$nbrPages		=	ceil(Model_Articles::countByCategory($category) / self::ARTICLES_BY_PAGE);

			if($page == 1) {
				\Eliya\Tpl::set('canonical_url', $category->getUrl());
			}

			$this->response->set(\Eliya\Tpl::get('categories/details', [
				'category' => $category,
				'tpl_articles' => $tpl_articles,
				'nbr_pages' => $nbrPages,
				'current_page' => $page
			]));
		}
	}