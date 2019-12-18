jQuery(document).ready(function($){
	var custom_uploader;
	var meta_gallery_frame;
	$('#boton_imagen').click(function(e) {
		e.preventDefault();
		//If the uploader object has already been created, reopen the dialog
		if (custom_uploader) {
			custom_uploader.open();
			return;
		}
		//Extend the wp.media object
		custom_uploader = wp.media.frames.file_frame = wp.media({
			title: 'Selecciona Imagen',
			button: {
				text: 'Selecciona Imagen'
			},
			multiple: false
		});
		//When a file is selected, grab the URL and set it as the text field's value
		custom_uploader.on('select', function() {
			attachment = custom_uploader.state().get('selection').first().toJSON();
			$('#imagen').val(attachment.url);
		});
		//Open the uploader dialog
		custom_uploader.open();
	});

	$('#boton_galeria').click(function(e) {
		e.preventDefault();
		// If the frame already exists, re-open it.
        if (meta_gallery_frame) {
            meta_gallery_frame.open();
            return;
        }

        // Sets up the media library frame
        meta_gallery_frame = wp.media.frames.wp_media_frame = wp.media( {
			title: 'Galería de fotos',
			frame: "post",
			state: 'gallery-library',
			library: {
				type: 'image'
			},
			multiple: true
		} );
	});
});