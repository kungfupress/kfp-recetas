<?php
/**
 * File: kfp-recetas/include/register-metaboxes.php
 *
 * @package kfp_receta
 */

defined( 'ABSPATH' ) || die();

add_action( 'add_meta_boxes', 'kfp_receta_register_meta_boxes' );
/**
 * Registra Meta Boxes para el Custom Post Type Receta
 *
 * @return void
 */
function kfp_receta_register_meta_boxes() {
	add_meta_box(
		'receta-info',
		'Informacion',
		'kfp_receta_info_show_meta_box',
		'receta',
		'normal',
		'high'
	);
	add_meta_box(
		'receta-ingredientes',
		'Ingredientes',
		'kfp_receta_ingredientes_show_meta_box',
		'receta',
		'normal',
		'high'
	);
	add_meta_box(
		'receta-preparacion',
		'Preparación',
		'kfp_receta_preparacion_show_meta_box', 
		'receta',
		'normal',
		'high'
	);
	add_meta_box(
		'receta-imagen',
		'Imagen',
		'kfp_receta_imagen_show_meta_box',
		'receta',
		'normal',
		'high'
	);
	add_meta_box(
		'receta-galeria',
		'Galería',
		'kfp_receta_galeria_show_meta_box',
		'receta',
		'normal',
		'high'
	);
}

/**
 * Muestra meta box con tiempo de preparación y número de comensales
 *
 * @param WP_Post $post WordPress Post object.
 */
function kfp_receta_info_show_meta_box( $post ) {
	$tiempo_preparacion = $post->_tiempo_preparacion;
	$comensales         = $post->_comensales;
	wp_nonce_field( 'graba_receta', 'receta_nonce' );
	$html  = '<label for="tiempo_preparacion">';
	$html .= esc_html__( 'Tiempo de preparación', 'kfp-recetas' ) . '</label>';
	$html .= '&nbsp; <input type="text" name="tiempo_preparacion" ';
	$html .= 'id="tiempo_preparacion" value="' . esc_attr( $tiempo_preparacion ) . '">';
	$html .= '<p><label for="comensales">' . esc_html__( 'Comensales', 'kfp-recetas' );
	$html .= '</label> &nbsp; ';
	$html .= '<input type="number" name="comensales" id="comensales" value="';
	$html .= esc_attr( $comensales ) . '">';
	echo $html;
}

/**
 * Muestra el meta box con los ingredientes, en formato tinyMCE
 *
 * @param Post $post
 * @return void
 */
function kfp_receta_ingredientes_show_meta_box( $post ) {
	// Cuidado, aquí parece que hay que usar la función y no la propiedad del objeto: $post->_ingredientes.
	$ingredientes = get_post_meta( $post->ID, '_ingredientes', true );
	echo '<div id="postdivrich" class="postarea">';
	wp_editor(
		$ingredientes,
		'ingredientes',
		array(
			'drag_drop_upload'  => true,
			'tabfocus_elements' => 'content-html,save-post',
			'editor_height'     => 200,
			'tinymce'           => array(
				'resize'             => false,
				'add_unload_trigger' => false,
			),
		)
	);
	echo '</div>';
}

/**
 * Muestra el meta box con los preparativos, en formato tinyMCE
 *
 * @param Post $post
 * @return void
 */
function kfp_receta_preparacion_show_meta_box( $post ) {
	// Cuidado, aquí hay que usar la función y no la propiedad del objeto: $post->_ingredientes.
	$preparacion = get_post_meta( $post->ID, '_preparacion', true );

	echo '<div id="postdivrich" class="postarea">';
	wp_editor(
		$preparacion,
		'preparacion',
		array(
			'drag_drop_upload'  => true,
			'tabfocus_elements' => 'content-html,save-post',
			'editor_height'     => 200,
			'tinymce'           => array(
				'resize'             => false,
				'add_unload_trigger' => false,
			),
		)
	);
	echo '</div>';
}

/**
 * Muestra el meta box para asociar una imagen
 *
 * @param Post $post
 * @return void
 */
function kfp_receta_imagen_show_meta_box( $post ) {
	$imagen = $post->_imagen;

	$html  = '<input id="imagen" type="text" size="36" name="imagen" value="';
	$html .= esc_attr( $imagen ) . '" >';
	$html .= '<input id="boton_imagen" class="button" type="button" value="';
	$html .= esc_html__( 'Subir Imagen', 'kfp-recetas' ) . '" >';
	$html .= '<br>' . esc_html__( 'Introduce URL o sube una imagen', 'kfp-recetas' );
	echo $html;
}

/**
 * Muestra el meta box para asociar una galería de imágenes
 *
 * @param Post $post
 * @return void
 */
function kfp_receta_galeria_show_meta_box( $post ) {
	$galeria = $post->_galeria;
	$html    = '<div id="mb-vista-previa-galeria">';
	if ( ! empty( $galeria ) ) {
		$galeria_ids = explode( ',', $galeria );
		foreach ( $galeria_ids as $attachment_id ) {
			$img   = wp_get_attachment_image_src( $attachment_id, 'thumbnail' );
			$html .= '<div class="mb-miniatura-galeria"><img src="';
			$html .= esc_url( $img[0] ) . '" /></div>';
		}
	}
	$html .= '</div>';
	$html .= '<input id="ids_galeria" type="hidden" size="36" name="galeria" value="';
	$html .= esc_attr( $galeria ) . '" >';
	$html .= '<div class="mb-botonera-galeria">';
	$html .= '<input id="boton_crear_galeria" class="button" type="button" value="';
	$html .= esc_html__( 'Crear/editar galería', 'kfp-recetas' ) . '" >';
	$html .= '<input id="boton_eliminar_galeria" class="button" type="button" value="';
	$html .= esc_html__( 'Eliminar galería', 'kfp-recetas' ) . '" >';
	$html .= '</div>';
	echo $html;
}

add_action( 'admin_enqueue_scripts', 'kfp_recetas_admin_scripts' );
/**
 * Agrega los scripts que crean la conexión entre los campos de imagen y galería y el media uploader
 *
 * @return void
 */
function kfp_recetas_admin_scripts() {
	if ( is_admin() ) {
		wp_enqueue_media(); // Carga la API de JavaScript para utilizar wp.media.
		wp_register_script( 'kfp-recetas-image-meta-box', KFP_RECETA_PLUGIN_URL . 'js/image-meta-box.js', array( 'jquery' ) );
		wp_register_script( 'kfp-recetas-gallery-meta-box', KFP_RECETA_PLUGIN_URL . 'js/gallery-meta-box.js', array( 'jquery' ) );
		wp_enqueue_script( 'kfp-recetas-image-meta-box' );
		wp_enqueue_script( 'kfp-recetas-gallery-meta-box' );
		wp_enqueue_style('kfp-recetas-admin-css', KFP_RECETA_PLUGIN_URL . 'css/admin.css');
	}
}
