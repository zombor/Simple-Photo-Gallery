<?php defined('SYSPATH') or die('No direct script access.');

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
				// Put the photo at the end
				$photo->photo_filename = $_FILES['photo']['name'];
				$photo->album_id = $album->id;
				$current = Auto_Modeler_ORM::factory('photo')->fetch_where(array(array('album_id', '=', $photo->album_id)));
				$photo->photo_order = $current->count()+1;
				$photo->date = time();

				// Save the photo
				gallery::process_upload($_FILES['photo'], $album, $photo);

				$photo->save();

				url::redirect('album/view/'.$album->url_name);
			}
			catch (Kohana_User_Exception $e)
			{
				$this->template->content->errors = $e;
			}
		}

		$this->template->content->photo = $photo;
	}

	public function edit($album_url, $photo)
	{
		$photo = new Photo_Model($album_url.'/'.$photo);

		if ( ! $photo->id)
			Event::run('system.404');

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
					// Delete the old photo before we change it
					$photo->delete_file();

					$photo->photo_filename = $_FILES['photo']['name'];

					// Create a thumbnail and resized version
					$image = new Image($_FILES['photo']['tmp_name']);
					$image->resize(Kohana::config('photo_gallery.image_width'), Kohana::config('photo_gallery.image_height'), Image::AUTO);
					$image->save(APPPATH.'views/media/photos/'.$album_url.'/'.$_FILES['photo']['name']);

					$image->resize(Kohana::config('photo_gallery.thumbnail_width'), Kohana::config('photo_gallery.thumbnail_height'), Image::AUTO);
					$image->save(APPPATH.'views/media/photos/'.$album_url.'/thumb_'.$_FILES['photo']['name']);
				}

				$photo->save();

				url::redirect('album/view/'.$photo->album->url_name);
			}
			catch (Kohana_User_Exception $e)
			{
				$this->template->content->errors = $e;
			}
		}

		$this->template->content->photo = $photo;
	}

	public function delete($album_url, $photo)
	{
		$photo = new Photo_Model($album_url.'/'.$photo);

		if ( ! $photo->id)
			Event::run('system.404');

		if( isset($_POST['confirm']))
		{
			$photo->replace_order($photo, 'down');
			$url = Auto_Modeler_ORM::factory('album', $photo->album_id)->url_name;

			$photo->delete();
			url::redirect('album/view/'.$url);
		}
		elseif( isset($_POST['cancel']))
			url::redirect('album/view/'.Auto_Modeler_ORM::factory('album', $photo->album_id)->url_name);

		$this->template->content = View::factory('admin/confirm');
	}
}