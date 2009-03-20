<?php

class Photo_Controller extends Website_Controller
{
	public function thumbnail($filename = NULL)
	{
		$photo = new Photo_Model($filename);

		if ( ! $photo->id)
			Event::run('system.404');

		header('Content-Type: image/jpeg');
		header('Content-Disposition: attachment; filename=thumb_'.$filename);
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');
		header('Content-Length: '.filesize(APPPATH.'/views/media/photos/thumb_'.$filename));

		readfile(APPPATH.'/views/media/photos/thumb_'.$filename);
		exit;
	}

	public function view($filename = NULL)
	{
		$photo = new Photo_Model($filename);

		if ( ! $photo->id)
			Event::run('system.404');

		header('Content-Type: image/jpeg');
		header('Content-Disposition: attachment; filename='.$filename);
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');
		header('Content-Length: '.filesize(APPPATH.'/views/media/photos/'.$filename));

		readfile(APPPATH.'/views/media/photos/'.$filename);
		exit;
	}
}