<?php
/**
 * Plugin Name:    KFP Recetas
 * Plugin Author:  Juanan Ruiz
 * Plugin URI:     https://kungfupress.com/recetario_de_cocina_con_wordpress/
 * Description:    Un plugin para aprender a programar WP practicando con un gestor de recetas.
 * Text Domain:    kfp-recetas
 *
 * @package kfp_recetas
 */

global $content;
global $post;
global $query;

define( 'KFP_RECETA_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

add_action( 'init', 'kfp_receta_register_post_type', 0 );
/**
 * Register Custom Post Type
 */
function kfp_receta_register_post_type() {
	$labels = array(
		'name'                  => _x( 'Recetas', 'Post Type General Name', 'kfp-recetas' ),
		'singular_name'         => _x( 'Receta', 'Post Type Singular Name', 'kfp-recetas' ),
		'menu_name'             => __( 'Recetas', 'kfp-recetas' ),
		'name_admin_bar'        => __( 'Receta', 'kfp-recetas' ),
		'archives'              => __( 'Item Archives', 'kfp-recetas' ),
		'attributes'            => __( 'Item Attributes', 'kfp-recetas' ),
		'parent_item_colon'     => __( 'Parent Item:', 'kfp-recetas' ),
		'all_items'             => __( 'Todas las recetas', 'kfp-recetas' ),
		'add_new_item'          => __( 'Agregar nueva receta', 'kfp-recetas' ),
		'add_new'               => __( 'Agregar nueva', 'kfp-recetas' ),
		'new_item'              => __( 'Nueva Receta', 'kfp-recetas' ),
		'edit_item'             => __( 'Editar Receta', 'kfp-recetas' ),
		'update_item'           => __( 'Actualizar Receta', 'kfp-recetas' ),
		'view_item'             => __( 'Ver Receta', 'kfp-recetas' ),
		'view_items'            => __( 'Ver Recetas', 'kfp-recetas' ),
		'search_items'          => __( 'Buscar Recetas', 'kfp-recetas' ),
		'not_found'             => __( 'Not found', 'kfp-recetas' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'kfp-recetas' ),
		'featured_image'        => __( 'Featured Image', 'kfp-recetas' ),
		'set_featured_image'    => __( 'Set featured image', 'kfp-recetas' ),
		'remove_featured_image' => __( 'Remove featured image', 'kfp-recetas' ),
		'use_featured_image'    => __( 'Use as featured image', 'kfp-recetas' ),
		'insert_into_item'      => __( 'Insert into item', 'kfp-recetas' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'kfp-recetas' ),
		'items_list'            => __( 'Items list', 'kfp-recetas' ),
		'items_list_navigation' => __( 'Items list navigation', 'kfp-recetas' ),
		'filter_items_list'     => __( 'Filter items list', 'kfp-recetas' ),
	);

	$args = array(
		'label'               => __( 'Receta', 'kfp-recetas' ),
		'description'         => __( 'Receta', 'kfp-recetas' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'thumbnail', 'revisions' ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_icon'           => 'dashicons-carrot',
		'menu_position'       => 5,
		'show_in_admin_bar'   => true,
		'show_in_nav_menus'   => true,
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'capability_type'     => 'page',
		'show_in_rest'        => true,
	);
	register_post_type( 'receta', $args );
	$set = get_option( 'post_type_rules_flased_receta' );
	if ( true !== $set ) {
		flush_rewrite_rules( false );
		update_option( 'post_type_rules_flased_receta', true );
	}
}

add_action( 'add_meta_boxes', 'kfp_receta_register_meta_boxes' );
/**
 * Registra tres Meta Box en el Custom Post Type Receta
 * https://code.tutsplus.com/tutorials/create-a-simple-crm-in-wordpress-creating-custom-fields--cms-20048
 * https://developer.wordpress.org/reference/functions/add_meta_box/
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
	// Cuidado, aquí parece que hay que usar la función y no la propiedad del objeto: $post->_ingredientes.
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
 * Muestra el meta box para introducir una imagen
 *
 * @param Post $post
 * @return void
 */
function kfp_receta_imagen_show_meta_box( $post ) {
	$imagen = $post->_imagen;

	$html  = '<label for="imagen">' . esc_html__( 'Imagen', 'kfp-recetas' ) . '</label>';
	$html .= '&nbsp; <input id="imagen" type="text" size="36" name="imagen" value="';
	$html .= esc_attr( $imagen ) . '" >';
	$html .= '<input id="boton_imagen" class="button" type="button" value="';
	$html .= esc_html__( 'Subir Imagen', 'kfp-recetas' ) . '" >';
	$html .= '<br>' . esc_html__( 'Introduce URL o sube una imagen', 'kfp-recetas' );
	echo $html;
}

/**
 * Muestra el meta box para introducir una imagen
 *
 * @param Post $post
 * @return void
 */
function kfp_receta_galeria_show_meta_box( $post ) {
	$galeria     = $post->_galeria;
	$galeria_ids = explode( ',', $galeria );

	$html  = '<label for="galeria">' . esc_html__( 'Galería de fotos', 'kfp-recetas' ) . '</label>';
	$html .= '<div id="vista-previa-galeria">';
	foreach ( $galeria_ids as $attachment_id ) {
		$img   = wp_get_attachment_image_src( $attachment_id, 'thumbnail' );
		$html .= '<div class="screen-thumb"><img src="';
		$html .= esc_url( $img[0] ) . '" /></div>';
	}
	$html .= '</div>';
	$html .= '&nbsp; <input id="galeria" type="text" size="36" name="galeria" value="';
	$html .= esc_attr( $galeria ) . '" >';
	$html .= '<input id="boton_galeria" class="button" type="button" value="';
	$html .= esc_html__( 'Crear galería', 'kfp-recetas' ) . '" >';
	echo $html;
}

add_action( 'save_post', 'kfp_receta_save_meta_boxes' );
/**
 * Graba los campos personalizados que vienen del formulario de edición del post
 *
 * @param int $post_id Post ID.
 *
 * @return bool|int
 */
function kfp_receta_save_meta_boxes( $post_id ) {
	// Comprueba que el nonce es correcto para evitar ataques CSRF.
	if ( ! isset( $_POST['receta_nonce'] ) || ! wp_verify_nonce( $_POST['receta_nonce'], 'graba_receta' ) ) {
		return $post_id;
	}

	// Comprueba que el tipo de post es receta
	if ( 'receta' != $_POST['post_type'] ) {
		return $post_id;
	}

	// Comprueba que el usuario actual tiene permiso para editar esto
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		wp_die(
			'<h1>' . __( 'Necesitas más privilegios para publicar contenidos.', 'kfp-recetas' ) . '</h1>' .
			'<p>' . __( 'Lo siento, no te está permitido crear contenidos con esta cuenta de usuario.', 'kfp-recetas' ) . '</p>',
			403
		);
	}

	// Ahora puedes grabar los datos
	$tiempo_preparacion = sanitize_text_field( $_POST['tiempo_preparacion'] );
	update_post_meta( $post_id, '_tiempo_preparacion', $tiempo_preparacion );
	$comensales = sanitize_text_field( $_POST['comensales'] );
	update_post_meta( $post_id, '_comensales', $comensales );
	$ingredientes = sanitize_post( $_POST['ingredientes'] );
	update_post_meta( $post_id, '_ingredientes', $ingredientes );
	$preparacion = sanitize_post( $_POST['preparacion'] );
	update_post_meta( $post_id, '_preparacion', $preparacion );
	$imagen = sanitize_url( $_POST['imagen'] );
	update_post_meta( $post_id, '_imagen', $imagen );
	$galeria = sanitize_text_field( $_POST['galeria'] );
	update_post_meta( $post_id, '_galeria', $galeria );
	
	return true;
}

add_filter( 'the_content', 'kfp_receta_add_custom_fields_to_content' );
/**
 * Agrega los custom fields al contenido de las receta
 * Observa que los Custom Fields se devuelven como array (por si hay más de uno)
 * Por ello en este caso al llamarlos hay que cargar el primer elemento del array con [0]
 *
 * @param $content
 * @return string
 */
function kfp_receta_add_custom_fields_to_content( $content ) {
	$custom_fields = get_post_custom();
	if ( isset ( $custom_fields['_imagen'] ) ) {
		$content .= '<img src="' . $custom_fields['_imagen'][0] . '" alt="foto receta">';
	}
	if ( isset ( $custom_fields['_galeria'] ) ) {
		$galeria_ids = explode(',', $custom_fields['_galeria'][0]);
		$content .= '<div id="vista-previa-galeria">';
		foreach ($galeria_ids as $attachment_id) {
			$img = wp_get_attachment_image_src( $attachment_id, 'thumbnail' );
			$content .= '<div class="screen-thumb"><img src="';
			$content .= esc_url( $img[0] ) . '" /></div>';
		}
		$content .= '</div>';
		$content .= '<div>' . $custom_fields['_galeria'][0] . '</div>';
	}
	$content .= '<ul>';
	if ( isset( $custom_fields['_tiempo_preparacion'] ) ) {
		$content .= '<li><b>' . __( 'Tiempo de preparación', 'kfp-recetas' );
		$content .= ':</b> ' . $custom_fields['_tiempo_preparacion'][0] . '</li>';
	}
	if ( isset( $custom_fields['_comensales'] ) ) {
		$content .= '<li><b>' . __( 'Comensales', 'kfp-recetas' ) . ':</b> ';
		$content .= $custom_fields['_comensales'][0] . '</li>';
	}
	$content .= '</ul>';
	if ( isset ( $custom_fields['_ingredientes'] ) ) {
		$content .= '<h3>' . __( 'Ingredientes', 'kfp-recetas' ) . '</h3><div>';
		$content .= $custom_fields['_ingredientes'][0] . '</div>';
	}
	if ( isset ( $custom_fields['_preparacion'] ) ) {
		$content .= '<h3>' . __( 'Preparación', 'kfp-recetas' ) . '</h3><div>';
		$content .= $custom_fields['_preparacion'][0] . '</div>';
	}

	return $content;
}

add_filter( 'pre_get_posts', 'get_posts_y_recetas' );
/**
 * Agrega el tipo personalizado Receta a la página inicial de WP
 *
 * @param Query $query
 *
 * @return Query
 */
function get_posts_y_recetas( $query ) {
	if ( is_home() && $query->is_main_query() ) {
		$query->set( 'post_type', array( 'post', 'receta' ) );
	}

	return $query;
}

add_action( 'init', 'taxonomia_tipo_receta' );
/**
 * Registra una taxonomía para las recetas
 */
function taxonomia_tipo_receta() {
	$labels = array(
		'name'                => _x( 'Tipo de comida', 'taxonomy general name', 'kfp-recetas' ),
		'singular_name'       => _x( 'Tipo de comida', 'taxonomy singular name', 'kfp-recetas' ),
		'search_items'        => __( 'Buscar tipo de comida', 'kfp-recetas' ),
		'all_items'           => __( 'Todos los tipos de comida', 'kfp-recetas' ),
		'parent_item'         => __( 'Tipo de comida padre', 'kfp-recetas' ),
		'parent_item_colon'   => __( 'Tipo de comida Padre:', 'kfp-recetas' ),
		'edit_item'           => __( 'Editar tipo de comida', 'kfp-recetas' ),
		'update_item'         => __( 'Editar tipo de comida', 'kfp-recetas' ),
		'add_new_item'        => __( 'Agregar nuevo tipo de comida', 'kfp-recetas' ),
		'new_item_name'       => __( 'Nuevo tipo de comida', 'kfp-recetas' ),
		'menu_name'           => __( 'Tipo de comida', 'kfp-recetas' ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'tipo-comida' ),
	);
	// Nombre de taxonomia, post type al que se aplica y argumentos.
	register_taxonomy( 'tipo-comida', array( 'recetas' ), $args );
}

add_action('admin_enqueue_scripts', 'kfp_recetas_admin_scripts');
/**
 * Agrega el script que crea la conexión entre los campos de imagen o galería y el media uploader
 *
 * @return void
 */
function kfp_recetas_admin_scripts() {
	if (is_admin()) {
		wp_enqueue_media(); //Carga la API de JavaScript para utilizar wp.media
		wp_register_script( 'kfp-recetas-admin-js', KFP_RECETA_PLUGIN_URL . 'js/admin.js', array( 'jquery' ) );
		wp_enqueue_script( 'kfp-recetas-admin-js' );
	}
}
