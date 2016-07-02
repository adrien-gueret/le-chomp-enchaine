<?php
	class Controller_index extends Controller_main
	{
		public function get_index()
		{
			$articles = Model_Articles::getLast();
			
			$this->response->set(\Eliya\Tpl::get('index/index', [
				'articles' => $articles
			]));
		}
	}