<div id="photo_gallery_shell">
<p class="page_links">Page: <?php for ($i = 1; $i <= $num_pages; $i++):?><?=html::anchor('album/view/'.$album->url_name.'?page='.$i, $i, array('class' => $this->input->get('page', 1) == $i ? 'active' : 'inactive'))?> <?php endfor;?></p>
<ul id="photo_gallery">
<?php foreach ($photos as $photo):?><li><?=html::file_anchor('photo/view/'.$photo->photo_filename, html::image('photo/thumbnail/'.$photo->photo_filename), array('rel' => 'facebox'))?><br /><?=$photo->photo_name?><?php if (Auth::instance()->logged_in()):?><br />(<?=html::anchor('admin/photo/edit/'.$photo->photo_filename, 'Edit')?>) (<?=html::anchor('admin/photo/delete/'.$photo->photo_filename, 'Delete')?>)<?php endif;?></li>
<?php endforeach;?>
</ul>
<?php if (Auth::instance()->logged_in()):?><h2 style="clear: both;"><?=html::anchor('admin/photo/add/'.$album->id, 'Add photo here')?></h2><?php endif;?>
</div>