<?php defined('SYSPATH') or die('No direct script access.');

class Album_Controller extends Photo_Website_Controller
{
	public function index()
	{
		javascript::add(array('jquery.colorbox.js', 'photo_effects.js'));
		stylesheet::add(array('colorbox.css', 'colorbox-custom.css', 'photo_gallery.css'));

		$this->template->title = $this->template->heading = 'Photo Gallery';

		$this->template->content = View::factory('album/index')
			->bind('albums', $albums)
			->bind('user', $user);

		$albums = Auto_Modeler_ORM::factory('album')->fetch_all('album_order');

		// Either an object or FALSE
		$user = Auth::instance()->get_user();
	}

	public function view($album_name = NULL)
	{
		javascript::add(array('jquery.colorbox.js', 'photo_effects.js'));
		stylesheet::add(array('colorbox.css', 'colorbox-custom.css', 'photo_gallery.css'));

		$album = new Album_Model($album_name);
		if ( ! $album->id)
			Event::run('system.404');

		$this->template->title = $album->album_name;
		$this->template->heading = $album->album_name;

		$page_num = Input::instance()->get('page', 1);

		$this->template->content = View::factory('album/view')
			->bind('photos', $photos)
			->bind('album', $album)
			->bind('num_pages', $num_pages)
			->bind('page_num', $page_num)
			->bind('user', $user);

		$photos = $album->find_photos($page_num);
		$num_pages = ceil(count($album->find_related('photos')) / Kohana::config('photo_gallery.photos_per_page'));

		// Either an object or FALSE
		$user = Auth::instance()->get_user();
	}
}