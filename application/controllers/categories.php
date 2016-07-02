<?php
	class Controller_categories extends Controller_index
	{
		public function get_index()
		{
			$this->response->set('Catégories');
		}
	}