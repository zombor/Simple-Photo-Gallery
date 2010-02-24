<?php defined('SYSPATH') or die('No direct script access.');

class Photo_Website_Controller extends Website_Controller
{
	public function __construct()
	{
		if (request::is_ajax())
		{
			$this->template = 'blank';
		}

		parent::__construct();
	}
}