<h2><?=$action?> Album</h2>
<?=$errors?>
<?=form::open()?>
<ul>
	<li><label for="album_name">Album Name: </label><?=form::input('album_name', $album->album_name)?></li>
	<li><label for="album_order">Album Order: </label><?=form::input('album_order', $album->album_order)?></li>
	<li><?=form::submit('create', $action.' Album')?></li>
</ul>
<?=form::close()?>