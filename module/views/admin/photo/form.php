<h2><?=$action?> Photo</h2>
<?=$errors?>
<?=form::open_multipart()?>
<ul>
	<li><label for="photo_name">Photo Name: </label><?=form::input('photo_name', $photo->photo_name)?></li>
	<li><label for="photo">File: </label><?=form::upload('photo')?></li>
	<li><?=form::submit('create', $action.' Photo')?></li>
</ul>
</form>