jQuery(document).ready(function($){
	var meta_image_frame;
	var meta_gallery_frame;
	$('#boton_imagen').click(function(e) {
		e.preventDefault();
		//If the uploader object has already been created, reopen the dialog
		if (meta_image_frame) {
			meta_image_frame.open();
			return;
		}
		//Extend the wp.media object
		meta_image_frame = wp.media.frames.file_frame = wp.media({
			title: 'Selecciona Imagen',
			button: {
				text: 'Selecciona Imagen'
			},
			multiple: false
		});
		//When a file is selected, grab the URL and set it as the text field's value
		meta_image_frame.on('select', function() {
			attachment = meta_image_frame.state().get('selection').first().toJSON();
			$('#imagen').val(attachment.url);
		});
		//Open the uploader dialog
		meta_image_frame.open();
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

		meta_gallery_frame.on("update", function(selection) {
			var ids = selection.models.map(
				function( e ) {
					/*element = e.toJSON();
					preview_img = typeof element.sizes.thumbnail !== 'undefined' ? element.sizes.thumbnail.url : element.url;
					preview_html = "<div class='screen-thumb'><img src='" + preview_img + "'/></div>";
					current_gallery.find( '.gallery-screenshot' ).append( preview_html );*/
					return e.id;
				}
			);
			console.log(ids.join( ',' ));
			$( '#galeria' ).val( ids.join( ',' ) ).trigger( 'change' );

			//console.log(meta_gallery_frame.state().get('selection'));
			//$('galeria').val(wp.media.gallery.shortcode(e).string());
		});

	});
});