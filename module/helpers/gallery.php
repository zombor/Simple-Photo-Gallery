<?php

class gallery_Core {

	public static function process_upload($file, Album_Model $album, Photo_Model $photo)
	{
		if ( ! $photo['error'])
		{
			! is_dir(APPPATH.'views/media/photos/'.$album->url_name) AND mkdir(APPPATH.'views/media/photos/'.$album->url_name);

			// Create a thumbnail and resized version
			$image = new Image($file['tmp_name']);
			$image->resize(Kohana::config('photo_gallery.image_width'), Kohana::config('photo_gallery.image_height'), Image::AUTO);
			$resized_status = $image->save(APPPATH.'views/media/photos/'.$album->url_name.'/'.$file['name']);

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

			$thumb_status = $image->quality(65)->save(APPPATH.'views/media/photos/'.$album->url_name.'/thumb_'.$file['name']);
		}
	}
}