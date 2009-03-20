<?php

class Photo_Model extends Auto_Modeler_ORM {

	protected $table_name = 'photos';

	protected $data = array('id' => '',
	                        'photo_name' => '',
	                        'photo_filename' => '',
	                        'photo_order' => '',
	                        'album_id' => '');

	protected $rules = array('photo_name' => array('required'),
	                         'photo_filename' => array('required'),
	                         'album_id' => array('required', 'numeric'));

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
		else if ($id != NULL AND is_string($id))
		{
			// try and get a row with this username/email
			$data = $this->db->orwhere(array('photo_filename' => $id))->get($this->table_name)->result(FALSE);

			// try and assign the data
			if (count($data) == 1 AND $data = $data->current())
			{
				foreach ($data as $key => $value)
					$this->data[$key] = $value;
			}
		}
	}

	public function delete()
	{
		if (unlink(APPPATH.'views/media/photos/'.$this->photo_filename) AND unlink(APPPATH.'views/media/photos/thumb_'.$this->photo_filename))
			return parent::delete();
	}
}