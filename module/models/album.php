<?php defined('SYSPATH') or die('No direct script access.');

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
								$data = db::build()->select('*')->from($this->table_name)->where('id', '=', $id)->execute($this->db)->as_array();

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
								$data = db::build()->select('*')->from($this->table_name)->where(array('url_name', '=', $id))->execute($this->db)->as_array();

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
		return db::build()->from('photos')->limit($per_page)->offset($page_number*$per_page-$per_page)->order_by('photo_order', 'ASC')->where('album_id', '=', $this->data['id'])->execute($this->db)->as_object('Photo_Model');
	}
}