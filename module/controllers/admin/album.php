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

	public function edit($album_id = NULL)
	{
		$album = new Album_Model($album_id);
		if ( ! $album->id)
			Event::run('system.404');

		$this->template->content = new View('admin/album/form');
		$this->template->content->errors = '';
		$this->template->content->action = 'Edit';
		
		if ($_POST)
		{
			try
			{
				$old_name = $album->album_name;
				$album->set_fields($_POST);
				$album->url_name = url::title($_POST['album_name']);

				$album->save();

				// Rename the album folder too
				rename(APPPATH.'views/media/photos/'.url::title($old_name), APPPATH.'views/media/photos/'.$album->url_name);

				url::redirect('admin/album/index');
			}
			catch (Kohana_User_Exception $e)
			{
				$this->template->content->errors = $e;
			}
		}
		
		$this->template->content->album = $album;
	}

	public function delete($album_id = NULL)
	{
		$album = new Album_Model($album_id);
		if ( ! $album->id)
			Event::run('system.404');

		if(isset($_POST['confirm']))
		{

			// Delete all the files for the images in this album
			foreach ($album->find_related('photos') as $photo)
			{
				$photo->delete_file();
			}

			$album->delete(); // This also deletes related photo DB rows!

			url::redirect('admin/album/index');
		}
		elseif(isset($_POST['cancel']))
		{
			url::redirect('admin/album/index');
		}
		$this->template->content = View::factory('admin/confirm');
	}

	public function reorder($album_id = NULL)
	{
		$album = new Album_Model($album_id);
		if ( ! $album->id)
			Event::run('system.404');

		javascript::add(array('jquery-ui', 'photo_admin_effects'));
		stylesheet::add(array('photo_gallery'));

		$this->template->title = $album->album_name;
		$this->template->heading = $album->album_name;

		$this->template->content = new View('admin/album/reorder');
		$this->template->content->photos = $album->find_related('photos', array(), array('photo_order' => 'ASC'));
		$this->template->content->album = $album;
	}
	
	public function reorder_process()
	{
		if (Session::instance()->get('image_csrf', NULL) == $this->input->get('csrf_token', ''))
		{
			Auto_Modeler_ORM::factory('photo')->batch_reorder($_GET['photo']);
			die('<p>Reorder Successful!</p>');
		}
		else
			die('<p>Unexpected Error Occurred. Please Try Again.</p>');
	}
}