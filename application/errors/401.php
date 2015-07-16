<?php
	//An instance of this class is automatically called by Eliya when a 401 error is thrown
	class Error_401
	{
		public function __construct(Eliya\Response $response)
		{
			$response->set(
				Eliya\Tpl::get('errors', [
			   		'error_number'	=>	401,
					'message'		=>	$response->error()
				])
			);
		}
	}