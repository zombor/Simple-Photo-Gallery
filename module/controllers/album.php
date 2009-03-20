<?php

class Album_Controller extends Website_Controller
{
	public function index()
	{
		$this->template->title = $this->template->heading = 'Photo Gallery';

		$this->template->content = new View('album/index');
		$this->template->content->albums = Auto_Modeler_ORM::factory('album')->fetch_all('album_order');
	}

	public function view($album_name)
	{
		javascript::add(array('facebox', 'photo_effects'));
		stylesheet::add(array('facebox', 'photo_gallery'));

		$page_num = $this->input->get('page', 1);

		$album = new Album_Model($album_name);

		$this->template->title = $album->album_name;
		$this->template->heading = $album->album_name;

		$this->template->content = new View('album/view');
		$this->template->content->photos = $album->find_photos($page_num);
		$this->template->content->album = $album;
		$this->template->content->num_pages = ceil(count($album->find_related('photos')) / 18);
	}
}