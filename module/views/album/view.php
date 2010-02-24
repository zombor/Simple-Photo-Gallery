<?php $count = 0; ?>

<div id="photo_gallery_shell">
	<p class="page_links">Page: 
		
	<?php for ($i = 1; $i <= $num_pages; $i++): ?>
		<?php echo html::anchor('album/view/'.$album->url_name.'?page='.$i, $i,
				array('class' => $page_num == $i ? 'active' : 'inactive')); ?>
	<?php endfor;?></p>

	<table id="photo_gallery">

	<?php foreach ($photos as $photo): ?>
		<?php if ($count % 3 == 0): ?>

		<tr>

		<?php endif;?>

			<td valign="top" class="cell_<?php echo $count % 3; ?>">
				<div class="photo">
					<?php echo html::file_anchor('photo/view/'.$album->url_name.'/'.$photo->photo_filename,
						html::image('photo/thumbnail/'.$album->url_name.'/'.$photo->photo_filename, $photo->photo_filename, TRUE),
						array('rel' => $album->album_name, 'title' => $photo->photo_name)); ?>
				</div>
				<div class="caption">
					<p><?php echo $photo->photo_name; ?></p>
					<p><?php echo $photo->photo_description; ?></p>
				<?php if ($photo->can_be_edited_by($user)): ?><br />
				<?php echo html::anchor('admin/photo/edit/'.$album->url_name.'/'.$photo->id,
					html::image('images/fam_silk/wrench_orange.png',
					array('alt' => 'Edit', 'title' => 'Edit'))); ?>
				<?php echo html::anchor('admin/photo/delete/'.$album->url_name.'/'.$photo->id,
					html::image('images/fam_silk/cross.png',
					array('alt' => 'Delete', 'title' => 'Delete'))); ?>
				<?php endif; ?>
				</div>
			</td>

		<?php if (++$count % 3 == 0): ?>
		
		</tr>
		
		<?php endif; ?>
		
	<?php endforeach; ?>

	</table>

<?php if ($album->can_be_edited_by($user)): ?>
	
	<h2 style="clear: both;"><?php echo html::anchor('admin/photo/add/'.$album->id, 'Add photo here'); ?></h2>
	<h2><?php echo html::anchor('admin/album/reorder/'.$album->id, 'Reorder Photos In This Album'); ?></h2>

<?php endif; ?>

</div>