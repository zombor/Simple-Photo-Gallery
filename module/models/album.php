<?php

class Album_Model extends Auto_Modeler_ORM {

	protected $table_name = 'albums';

	protected $data = array('id' => '',
	                        'album_name' => '',
	                        'url_name' => '',
	                        'album_order' => 0);

	protected $rules = array('album_name' => array('required'),
	                         'album_order' => array('required', 'numeric'));

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
			$data = $this->db->orwhere(array('url_name' => $id))->get($this->table_name)->result(FALSE);

			// try and assign the data
			if (count($data) == 1 AND $data = $data->current())
			{
				foreach ($data as $key => $value)
					$this->data[$key] = $value;
			}
		}
	}

	public function find_photos($page_number)
	{
		$per_page = Kohana::config('photo_gallery.photos_per_page');
		return $this->db->from('photos')->limit(18, ($page_number*$per_page-$per_page))->orderby(array('photo_order' => 'ASC'))->where(array('album_id' => $this->data['id']))->get()->result(TRUE, 'Photo_Model');
	}
}