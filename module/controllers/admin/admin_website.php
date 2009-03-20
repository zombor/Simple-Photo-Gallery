<?php

abstract class Admin_Website_Controller extends Website_Controller {

	public function __construct()
	{
		if ( ! Auth::instance()->logged_in())
			Event::run('system.404');

		parent::__construct();
	}
}