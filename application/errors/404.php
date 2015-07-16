<?php
	//An instance of this class is automatically called by Eliya when a 404 error is thrown
	class Error_404
	{
		public function __construct(Eliya\Response $response)
		{
			$response->set(
				Eliya\Tpl::get('errors', [
			   		'error_number'	=>	404,
					'message'		=>	$response->error()
				])
			);
		}
	}