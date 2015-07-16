<?php
	//An instance of this class is automatically called by Eliya when a 403 error is thrown
	class Error_403
	{
		public function __construct(Eliya\Response $response)
		{
			$response->set(
				Eliya\Tpl::get('errors', [
			   		'error_number'	=>	403,
					'message'		=>	$response->error()
				])
			);
		}
	}