$(document).ready(
	
	function() 
	{
		$("#photo_gallery.sortable").sortable({
			update: function(event, ui) {
				var order = $('#photo_gallery.sortable').sortable('serialize');
				$("#info").load("/gallery/index.php/admin/album/reorder_process?csrf_token="+$('#csrf_token').val()+"&"+order);
			}
		});
});