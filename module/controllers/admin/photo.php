<?php
include Kohana::find_file('controllers', 'admin/admin_website');
class Photo_Controller extends Admin_Website_Controller
{
	public function add($album)
	{
		$album = new Album_Model($album);
		$photo = new Photo_Model;
		$this->template->content = new View('admin/photo/form');
		$this->template->content->errors = '';
		$this->template->content->action = 'Create';
		
		if ($_POST)
		{
			try
			{
				$photo->set_fields($_POST);
				$photo->photo_filename = $_FILES['photo']['name'];
				$photo->album_id = $album->id;
				$photo->save();

				// Create a thumbnail and resized version
				$image = new Image($_FILES['photo']['tmp_name']);
				$image->resize(Kohana::config('photo_gallery.image_width'), Kohana::config('photo_gallery.image_height'), Image::AUTO);
				$image->save(APPPATH.'views/media/photos/'.$_FILES['photo']['name']);

				$image->resize(Kohana::config('photo_gallery.thumbnail_width'), Kohana::config('photo_gallery.thumbnail_height'), Image::AUTO);
				$image->save(APPPATH.'views/media/photos/thumb_'.$_FILES['photo']['name']);

				url::redirect('album/view/'.$album->url_name);
			}
			catch (Kohana_User_Exception $e)
			{
				$this->template->content->errors = $e;
			}
		}

		$this->template->content->photo = $photo;
	}

	public function edit($photo)
	{
		$photo = new Photo_Model($photo);
		$this->template->content = new View('admin/photo/form');
		$this->template->content->errors = '';
		$this->template->content->action = 'Edit';
		
		if ($_POST)
		{
			try
			{
				$photo->set_fields($_POST);

				if ( ! $_FILES['photo']['error'])
				{
					$photo->photo_filename = $_FILES['photo']['name'];

					// Create a thumbnail and resized version
					$image = new Image($_FILES['photo']['tmp_name']);
					$image->resize(640, 480, Image::HEIGHT);
					$image->save(APPPATH.'views/media/photos/'.$_FILES['photo']['name']);

					$image->resize(150, 150, Image::HEIGHT);
					$image->save(APPPATH.'views/media/photos/thumb_'.$_FILES['photo']['name']);
				}

				$photo->save();

				url::redirect('album/view/'.Auto_Modeler_ORM::factory('album', $photo->album_id)->url_name);
			}
			catch (Kohana_User_Exception $e)
			{
				$this->template->content->errors = $e;
			}
		}

		$this->template->content->photo = $photo;
	}

	public function delete($photo)
	{
		$photo = new Photo_Model($photo);

		$photo->delete();
		
		url::redirect('album/view/'.Auto_Modeler_ORM::factory('album', $photo->album_id)->url_name);
	}
}