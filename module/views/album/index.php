<ul>
	<?php foreach ($albums as $album):?><li><h3><?=html::anchor('album/view/'.$album->url_name, $album->album_name)?></h3></li>
	<?php endforeach;?>
</ul>