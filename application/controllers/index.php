<?php
	class Controller_index extends Controller_main
	{
		public function get_index($page = 1)
		{
			$articles = Model_Articles::getLast($page);
			
			$this->response->set(\Eliya\Tpl::get('index/index', [
				'articles' => $articles
			]));
		}
	}