<?php
	class Controller_sections extends Controller_index
	{
		public function get_index()
		{
			$this->response->set('rubriques');
		}
	}