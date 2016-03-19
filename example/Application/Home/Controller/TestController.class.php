<?php
namespace Home\Controller;

class TestController
{
	public function test()
	{
		header('Content-type:text/json');
		echo json_encode($_REQUEST);
	}
}