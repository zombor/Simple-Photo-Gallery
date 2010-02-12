<h2>Albums</h2>
<ul>
	<?php foreach ($albums as $album):?>
	<li><?=html::anchor('album/view/'.$album->url_name, $album->album_name)?>
	<?=html::anchor('admin/album/delete/'.$album->id,
		html::image('images/fam_silk/cross.png',
		array('alt' => 'Delete', 'title' => 'Delete')))?>
	<?=html::anchor('admin/album/edit/'.$album->id,
		html::image('images/fam_silk/wrench_orange.png',
		array('alt' => 'Edit', 'title' => 'Edit')))?></li>
	<?php endforeach;?>
</ul>
<h2><?=html::anchor('admin/album/create', 'Create Album')?></h2>