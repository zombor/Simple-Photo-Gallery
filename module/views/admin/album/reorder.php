<div id="photo_gallery_shell">
<p>To reorder photos, simply drag them in the desired order.<input type="hidden" name="csrf_token" value="<?=$_SESSION['image_csrf'] = text::random()?>" id="csrf_token" /></p>
<p><?=html::anchor('album/view/'.$album->url_name, 'Back to Album')?></p>
<ul id="photo_gallery" class="sortable">
<?php foreach ($photos as $photo):?>
<li id="photo_<?=$photo->id?>">
	<div class="photo">
	<?=html::image('photo/thumbnail/'.$album->url_name.'/'.$photo->photo_filename, $photo->photo_filename, TRUE)?>
	</div>
</li><?php endforeach;?>
</ul>
<div id="info"></div>
</div>