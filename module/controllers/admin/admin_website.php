<?php

abstract class Admin_Website_Controller extends Photo_Website_Controller {

	public function __construct()
	{
		if ( ! Auth::instance()->logged_in('admin'))
			Event::run('system.404');

		parent::__construct();
	}
}