<h2><?php echo $action; ?> Photo</h2>
<?php echo $errors; ?>
<?php echo form::open_multipart(); ?>
<ul>
	<li><label for="photo_name">Photo Name: </label><?php echo form::input('photo_name', $photo->photo_name); ?></li>
	<li><label for="photo">File: </label><?php echo form::upload('photo'); ?></li>
	<li><?php echo form::submit('create', $action.' Photo'); ?></li>
</ul>
</form>