<?php

class Photo_Model extends Auto_Modeler_ORM {

	protected $table_name = 'photos';

	protected $data = array('id' => '',
	                        'photo_name' => '',
	                        'photo_description' => '',
	                        'photo_filename' => '',
	                        'photo_order' => '',
	                        'album_id' => '',
	                        'date' => '');

	protected $rules = array('photo_name' => array('required'),
	                           'photo_filename' => array('required'),
	                           'album_id' => array('required', 'numeric'));

	protected $callbacks = array('photo_filename' => 'valid_filename');

	public function __construct($id = NULL)
	{
		parent::__construct();

		if ($id != NULL AND (ctype_digit($id) OR is_int($id)))
		{
			// try and get a row with this ID
			$data = $this->db->getwhere($this->table_name, array('id' => $id))->result(FALSE);

			// try and assign the data
			if (count($data) == 1 AND $data = $data->current())
			{
				foreach ($data as $key => $value)
					$this->data[$key] = $value;
			}
		}
		else if ($id != NULL AND is_string($id))  // Loads by a string of album_url/photo_filename
		{
			list($album, $photo_filename) = explode('/', $id);
			$album = new Album_Model($album);

			$where = array('album_id' => $album->id);
			if (ctype_digit($photo_filename))
				$where['id'] = $photo_filename;
			else
				$where['photo_filename'] = $photo_filename;

			// try and get a row with this username/email
			$data = $this->db->where($where)->get($this->table_name)->result(FALSE);

			// try and assign the data
			if (count($data) == 1 AND $data = $data->current())
			{
				foreach ($data as $key => $value)
					$this->data[$key] = $value;
			}
		}
	}

	public function delete_file()
	{
		return unlink(APPPATH.'views/media/photos/'.$this->album->url_name.'/'.$this->photo_filename)
		       AND unlink(APPPATH.'views/media/photos/'.$this->album->url_name.'/thumb_'.$this->photo_filename);
	}

	public function delete()
	{
		if ($this->delete_file())
			return parent::delete();
		return FALSE;
	}

	public function replace_order($photo, $direction)
	{
		if ($direction === 'up') // This is used for insertion
		{
			$this->db->query('UPDATE `'.$this->table_name.'` SET `photo_order` = `photo_order` + 1 WHERE `photo_order` >= ? AND `album_id` = ?', array($photo->photo_order, $photo->album_id));
		}
		elseif ($direction === 'down') // This is used for deleting
		{
			$this->db->query('UPDATE `'.$this->table_name.'` SET `photo_order` = `photo_order` - 1 WHERE `photo_order` >= ? AND `album_id` = ?', array($photo->photo_order, $photo->album_id));
		}
	}

	public function batch_reorder($photo_array)
	{
		foreach ($photo_array as $order => $photo_id)
		{
			$sql = 'UPDATE `'.$this->table_name.'` SET `photo_order` = ? WHERE `id` = ?';
			$this->db->query($sql, array(($order + 1), $photo_id));
		}
	}

	public function valid_filename(Validation $array, $field)
	{
		if ( $_FILES AND ! $_FILES['photo']['error'])
		{
			$filename_exists = (bool) $this->db->from($this->table_name)->select('photo_filename')->where(array('album_id' => $this->album_id, 'photo_filename' => $this->photo_filename))->get()->count();

			if ($filename_exists)
				$array->add_error($field, 'filename_exists');
		}
	}

	public function save_image_file(Validation & $validation)
	{
		//die(Kohana::debug($validation).Kohana::debug($validation['photo']));

		if ( ! $validation['photo']['photo']['error'])
		{
			if ( ! is_dir(APPPATH.'views/media/photos/'.$validation['album']->url_name))
				mkdir(APPPATH.'views/media/photos/'.$validation['album']->url_name);

			$file = $validation['photo']['photo'];

			// Create a thumbnail and resized version
			$image = new Image($file['tmp_name']);
			$image->resize(Kohana::config('photo_gallery.image_width'), Kohana::config('photo_gallery.image_height'), Image::AUTO);
			$resized_status = $image->save(APPPATH.'views/media/photos/'.$validation['album']->url_name.'/'.$file['name']);

			$ratio = $image->width / $image->height;
			if ($ratio > 1)
			{
				$image->resize(Kohana::config('photo_gallery.thumbnail_width')*$ratio, Kohana::config('photo_gallery.thumbnail_height'), Image::HEIGHT);
				$image->crop(Kohana::config('photo_gallery.thumbnail_width'), Kohana::config('photo_gallery.thumbnail_height'));
			}
			else
			{
				$image->resize(Kohana::config('photo_gallery.thumbnail_width'), Kohana::config('photo_gallery.thumbnail_height')/$ratio, Image::WIDTH);
				$image->crop(Kohana::config('photo_gallery.thumbnail_width'), Kohana::config('photo_gallery.thumbnail_height'), 'top');
			}

			$thumb_status = $image->quality(65)->save(APPPATH.'views/media/photos/'.$validation['album']->url_name.'/thumb_'.$file['name']);

			if ( ! $resized_status OR ! $thumb_status)
				$validation->add_error('photo', 'general_error');
		}

		unset ($validation['photo'], $validation['album']);
	}
}