<?php
include Kohana::find_file('controllers', 'admin/admin_website');
class Album_Controller extends Admin_Website_Controller
{
	public function index()
	{
		// List out all the albums
		$this->template->content = new View('admin/album/index');
		$this->template->content->albums = Auto_Modeler_ORM::factory('album')->fetch_all();
	}

	public function create()
	{
		$album = new Album_Model;
		$this->template->content = new View('admin/album/form');
		$this->template->content->errors = '';
		$this->template->content->action = 'Create';
		
		if ($_POST)
		{
			try
			{
				$album->set_fields($_POST);
				$album->url_name = url::title($_POST['album_name']);

				$album->save();
				
				url::redirect('admin/album/index');
			}
			catch (Kohana_User_Exception $e)
			{
				$this->template->content->errors = $e;
			}
		}
		
		$this->template->content->album = $album;
	}

	public function edit($album_id)
	{
		$album = new Album_Model($album_id);
		$this->template->content = new View('admin/album/form');
		$this->template->content->errors = '';
		$this->template->content->action = 'Edit';
		
		if ($_POST)
		{
			try
			{
				$album->set_fields($_POST);
				$album->url_name = url::title($_POST['album_name']);

				$album->save();
				
				url::redirect('admin/album/index');
			}
			catch (Kohana_User_Exception $e)
			{
				$this->template->content->errors = $e;
			}
		}
		
		$this->template->content->album = $album;
	}

	public function delete($album_id)
	{
		$album = new Album_Model($album_id);
		
		// Delete all the files for the images in this album
		foreach ($album->find_related('photos') as $photo)
		{
			unlink(APPPATH.'views/media/photos/'.$photo->photo_filename);
			unlink(APPPATH.'views/media/photos/thumb_'.$photo->photo_filename);
		}

		$album->delete(); // This also deletes related photo DB rows!

		url::redirect('admin/album/index');
	}
}