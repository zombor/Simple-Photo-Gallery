<ul id="album_list">

	<?php foreach ($albums as $album): ?>
		<?php if ($album->can_be_viewed_by($user)): ?>

	<?php
		$photos = $album->find_related('photos', array(), 'photo_order');
		$photo = count($photos) ? $photos->current() : Auto_Modeler_ORM::factory('photo');
	?>
	
	<li>
		<div class="album">
			<?php echo html::anchor('album/view/'.$album->url_name,
	                         html::image('photo/thumbnail/'.$album->url_name.'/'.$photo->photo_filename, $album->url_name, TRUE)); ?><br />

			<div class="album_caption">

				<?php echo $album->album_name; ?>
				<?php if ($album->can_be_edited_by($user)): ?><br />

					<?php echo html::anchor('admin/album/edit/'.$album->id,
						html::image('images/fam_silk/wrench_orange.png',
						array('alt' => 'Edit', 'title' => 'Edit'))); ?>
					<?php echo html::anchor('admin/album/delete/'.$album->id,
						html::image('images/fam_silk/cross.png',
						array('alt' => 'Delete', 'title' => 'Delete'))); ?>
				<?php endif; ?>
			</div>
	</div>
	</li>
	<?php endif; ?>
	<?php endforeach; ?>
</ul>

<?php if (Album_Model::can_be_created_by($user)): ?>

<h2 style="clear: both;"><?php echo html::anchor('admin/album/create', 'Add album', array('rel' => 'facebox')); ?></h2>

<?php endif; ?>