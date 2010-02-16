<?php defined('SYSPATH') or die('No direct script access.');

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
			$data = db::build()->select('*')->from($this->table_name)->where('id', '=', $id)->execute($this->db)->as_array();

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

			$where = array(array('album_id', '=', $album->id));
			if (ctype_digit($photo_filename))
				$where[] = array('id', '=', $photo_filename);
			else
				$where[] = array('photo_filename', '=', $photo_filename);

			// try and get a row with this username/email
			$data = db::build()->where($where)->from($this->table_name)->execute($this->db)->as_array();

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
			db::query('UPDATE `'.$this->table_name.'` SET `photo_order` = `photo_order` + 1 WHERE `photo_order` >= :photo_order AND `album_id` = :album_id')->value(':photo_order', $photo->photo_order)->value(':album_id', $photo->album_id)->execute($this->db);
			//$this->db->query('UPDATE `'.$this->table_name.'` SET `photo_order` = `photo_order` + 1 WHERE `photo_order` >= ? AND `album_id` = ?', array($photo->photo_order, $photo->album_id));
		}
		elseif ($direction === 'down') // This is used for deleting
		{
			db::query('UPDATE `'.$this->table_name.'` SET `photo_order` = `photo_order` - 1 WHERE `photo_order` >= :photo_order AND `album_id` = :album_id')->value(':photo_order', $photo->photo_order)->value(':album_id', $photo->album_id)->execute($this->db);
			//$this->db->query('UPDATE `'.$this->table_name.'` SET `photo_order` = `photo_order` - 1 WHERE `photo_order` >= ? AND `album_id` = ?', array($photo->photo_order, $photo->album_id));
		}
		//die(Kohana::debug($this->db->last_query()));
	}

	public function batch_reorder($photo_array)
	{
		foreach ($photo_array as $order => $photo_id)
		{
			db::build()->update($this->table_name, array('photo_order' => $order+1), array('id', '=', $photo_id))->execute($this->db);
		}
	}

	public function valid_filename(Validation $array, $field)
	{
		// This kinda sucks
		if ( $_FILES AND ! $_FILES['photo']['error'])
		{
			$filename_exists = (bool) db::build()->from($this->table_name)->select('photo_filename')->where(array(array('album_id', '=', $this->album_id), array('photo_filename', '=', $this->photo_filename)))->execute($this->db)->count();

			if ($filename_exists)
				$array->add_error($field, 'filename_exists');
		}
	}
}