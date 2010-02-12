<ul id="album_list">
	<?php foreach ($albums as $album):?>
	<?php
		$photos = $album->find_related('photos');
		$photo = count($photos) ? $photos->current() : Auto_Modeler_ORM::factory('photo');
	?>
	<li>
	<div class="album">
		<?=html::anchor('album/view/'.$album->url_name, 
	                         html::image('photo/thumbnail/'.$album->url_name.'/'.$photo->photo_filename, $album->url_name, TRUE))?><br />
	    <div class="album_caption">
	    	<?=$album->album_name?>
	    	<?php if (Auth::instance()->logged_in('admin')):?><br />
			<?=html::anchor('admin/album/edit/'.$album->id,
				html::image('images/fam_silk/wrench_orange.png',
				array('alt' => 'Edit', 'title' => 'Edit')))?>
			<?=html::anchor('admin/album/delete/'.$album->id,
				html::image('images/fam_silk/cross.png', 
				array('alt' => 'Delete', 'title' => 'Delete')))?>
			<?php endif;?>
		</div>
	</div>
	</li>
	<?php endforeach;?>
</ul>
<?php if (Auth::instance()->logged_in('admin')):?><h2 style="clear: both;"><?=html::anchor('admin/album/create', 'Add album', array('rel' => 'facebox'))?></h2><?php endif;?>