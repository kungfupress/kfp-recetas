jQuery(document).ready(function($){
	var meta_image_frame;
	
	$('#boton_imagen').click(function(e) {
		e.preventDefault();
		// Si el frame existe abre la modal
		if (meta_image_frame) {
			meta_image_frame.open();
			return;
		}
		// Crea un nuevo frame
		meta_image_frame = wp.media.frames.file_frame = wp.media({
			title: 'Selecciona Imagen',
			button: {
				text: 'Selecciona Imagen'
			},
			multiple: false
		});
		// Cuando se selecciona un fichero, captura la URL y asígnala al input
		meta_image_frame.on('select', function() {
			attachment = meta_image_frame.state().get('selection').first().toJSON();
			$('#imagen').val(attachment.url);
		});
		// Abre la modal con el frame 
		meta_image_frame.open();
	});

});
