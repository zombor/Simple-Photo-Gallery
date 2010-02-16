<?php defined('SYSPATH') or die('No direct script access.');

class stylesheet_Core {

	protected static $stylesheets = array();

	public static function add($stylesheets = array())
	{
		if ( ! is_array($stylesheets))
			$stylesheets = array($stylesheets);

		foreach ($stylesheets as $key => $stylesheet)
		{
			self::$stylesheets[] = $stylesheet;
		}
	}

	public static function render()
	{
		foreach (self::$stylesheets as $key => $stylesheet)
		{
			echo html::stylesheet('css/'.$stylesheet);
		}
	}
}