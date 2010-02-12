$(document).ready(
	
	function() 
	{
		$('#photo_gallery_shell div.photo a, #photo_gallery_shell div.caption a, ul#album_list div.album_caption a, #photo_gallery_shell a[href*=admin/photo/add], a[href*=admin/album/create]').colorbox({transition:"elastic"});
});