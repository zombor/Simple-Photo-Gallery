<h2><?php echo $action; ?> Album</h2>
<?php echo $errors; ?>
<?php echo form::open(); ?>
<ul>
	<li><label for="album_name">Album Name: </label><?php echo form::input('album_name', $album->album_name); ?></li>
	<li><label for="album_order">Album Order: </label><?php echo form::input('album_order', $album->album_order); ?></li>
	<li><?php echo form::submit('create', $action.' Album'); ?></li>
</ul>
</form>