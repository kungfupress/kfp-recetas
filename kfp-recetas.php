<?php
/*
Plugin Name: Recetario
Author Name: Juanan Ruiz
Plugin URI: https://kungfupress.com/recetario_de_cocina_con_wordpress/
Description: Un plugin para aprender a programar WP practicando con un gestor de recetas.
*/

global $content;
global $post;
global $query;

/**
 * Register Custom Post Type
 */
function kfp_receta_register_post_type() {
	$labels = array(
		'name'                  => _x( 'Recetas', 'Post Type General Name', 'text_domain' ),
		'singular_name'         => _x( 'Receta', 'Post Type Singular Name', 'text_domain' ),
		'menu_name'             => __( 'Recetas', 'text_domain' ),
		'name_admin_bar'        => __( 'Receta', 'text_domain' ),
		'archives'              => __( 'Item Archives', 'text_domain' ),
		'attributes'            => __( 'Item Attributes', 'text_domain' ),
		'parent_item_colon'     => __( 'Parent Item:', 'text_domain' ),
		'all_items'             => __( 'Todas las recetas', 'text_domain' ),
		'add_new_item'          => __( 'Agregar nueva receta', 'text_domain' ),
		'add_new'               => __( 'Agregar nueva', 'text_domain' ),
		'new_item'              => __( 'Nueva Receta', 'text_domain' ),
		'edit_item'             => __( 'Editar Receta', 'text_domain' ),
		'update_item'           => __( 'Actualizar Receta', 'text_domain' ),
		'view_item'             => __( 'Ver Receta', 'text_domain' ),
		'view_items'            => __( 'Ver Recetas', 'text_domain' ),
		'search_items'          => __( 'Buscar Recetas', 'text_domain' ),
		'not_found'             => __( 'Not found', 'text_domain' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
		'featured_image'        => __( 'Featured Image', 'text_domain' ),
		'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
		'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
		'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
		'insert_into_item'      => __( 'Insert into item', 'text_domain' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'text_domain' ),
		'items_list'            => __( 'Items list', 'text_domain' ),
		'items_list_navigation' => __( 'Items list navigation', 'text_domain' ),
		'filter_items_list'     => __( 'Filter items list', 'text_domain' ),
	);

	$args = array(
		'label'               => __( 'Receta', 'text_domain' ),
		'description'         => __( 'Receta', 'text_domain' ),
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
	);
	register_post_type( 'receta', $args );
	$set = get_option( 'post_type_rules_flased_receta' );
	if ( true !== $set ) {
		flush_rewrite_rules( false );
		update_option( 'post_type_rules_flased_receta', true );
	}
}

add_action( 'init', 'kfp_receta_register_post_type', 0 );

/**
 * Registra tres Meta Box en el Custom Post Type Receta
 * https://code.tutsplus.com/tutorials/create-a-simple-crm-in-wordpress-creating-custom-fields--cms-20048
 * https://developer.wordpress.org/reference/functions/add_meta_box/
 */
function kfp_receta_register_meta_boxes() {
	add_meta_box(
		'receta-info',
		'Informacion',
		'kfp_receta_info_output_meta_box',
		'receta',
		'normal',
		'high'
	);
	add_meta_box(
		'receta-ingredientes',
		'Ingredientes',
		'kfp_receta_ingredientes_output_meta_box',
		'receta',
		'normal',
		'high'
	);
	add_meta_box(
		'receta-preparacion',
		'Preparación',
		'kfp_receta_preparacion_output_meta_box', 
		'receta',
		'normal',
		'high'
	);
}

/**
 * Output a Contact Details meta box
 *
 * @param WP_Post $post WordPress Post object.
 */
function kfp_receta_info_output_meta_box( $post ) {
	$tiempo_preparacion = $post->_tiempo_preparacion;
	$comensales         = $post->_comensales;
	wp_nonce_field( 'graba_receta', 'receta_nonce' );

	echo( '<label for="tiempo_preparacion">' . __( 'Tiempo de preparación', 'text_domain' ) . '</label>' );
	echo( '&nbsp; <input type="text" name="tiempo_preparacion" id="tiempo_preparacion" value="' . esc_attr( $tiempo_preparacion ) . '">' );
	echo( '<p><label for="comensales">' . __( 'Comensales', 'text_domain' ) . '</label>' );
	echo( '&nbsp; <input type="number" name="comensales" id="comensales" value="' . esc_attr( $comensales ) . '">' );
}

function kfp_receta_ingredientes_output_meta_box( $post ) {
	// Cuidado, aquí parece que hay que usar la función y no la propiedad del objeto: $post->_ingredientes
	$ingredientes = get_post_meta( $post->ID, '_ingredientes', true );
	echo( '<div id="postdivrich" class="postarea">' );
	wp_editor( $ingredientes, 'ingredientes', array(
		'drag_drop_upload'  => true,
		'tabfocus_elements' => 'content-html,save-post',
		'editor_height'     => 200,
		'tinymce'           => array(
			'resize'             => false,
			'add_unload_trigger' => false,
		),
	) );
	echo( '</div>' );
}

function kfp_receta_preparacion_output_meta_box( $post ) {
	$preparacion = get_post_meta( $post->ID, '_preparacion', true );

	echo( '<div id="postdivrich" class="postarea">' );
	wp_editor( $preparacion, 'preparacion', array(
		'drag_drop_upload'  => true,
		'tabfocus_elements' => 'content-html,save-post',
		'editor_height'     => 200,
		'tinymce'           => array(
			'resize'             => false,
			'add_unload_trigger' => false,
		),
	) );
	echo( '</div>' );
}

add_action( 'add_meta_boxes', 'kfp_receta_register_meta_boxes' );

/**
 * Graba los campos personalizados que vienen del formulario de edición del post
 *
 * @param int $post_id Post ID
 *
 * @return bool|int
 */
function kfp_receta_save_meta_boxes( $post_id ) {
	// Comprobamos que el nonce es correcto para evitar ataques CSRF
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
			'<h1>' . __( 'You need a higher level of permission.' ) . '</h1>' .
			'<p>' . __( 'Sorry, you are not allowed to create posts as this user.' ) . '</p>',
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

	return true;
}

add_action( 'save_post', 'kfp_receta_save_meta_boxes' );

/**
 * Agrega los custom fields al contenido de las recetas
 *
 * @param $content
 * @return string
 */
function kfp_receta_add_custom_fields_to_content( $content ) {

	$custom_fields = get_post_custom();

	$content .= '<ul>';
	if ( isset( $custom_fields['_tiempo_preparacion'] ) ) {
		$content .= '<li>Tiempo de preparación: ' . $custom_fields['_tiempo_preparacion'][0] . '</li>';
	}
	if ( isset( $custom_fields['_comensales'] ) ) {
		$content .= '<li>Comensales: ' . $custom_fields['_comensales'][0] . '</li>';
	}
	$content .= '</ul>';

	return $content;
}

add_filter( 'the_content', 'kfp_receta_add_custom_fields_to_content' );

/**
 * Agrega el tipo personalizado Receta a la página inicial de WP
 *
 * @param $query
 *
 * @return mixed
 */
function get_posts_y_recetas( $query ) {

	if ( is_home() && $query->is_main_query() ) {
		$query->set( 'post_type', array( 'post', 'receta' ) );
	}

	return $query;
}

add_filter( 'pre_get_posts', 'get_posts_y_recetas' );

/**
 * Registra una taxonomía para las recetas
 */
function taxonomia_tipo_receta() {
	$labels = array(
		'name'                => _x( 'Tipo de comida', 'taxonomy general name' ),
		'singular_name'       => _x( 'Tipo de comida', 'taxonomy singular name' ),
		'search_items'        => __( 'Buscar tipo de comida' ),
		'all_items'           => __( 'Todos los tipos de comida' ),
		'parent_item'         => __( 'Tipo de comida padre' ),
		'parent_item_colon'   => __( 'Tipo de comida Padre:' ),
		'edit_item'           => __( 'Editar tipo de comida' ),
		'update_item'         => __( 'Editar tipo de comida' ),
		'add_new_item'        => __( 'Agregar nuevo tipo de comida' ),
		'new_item_name'       => __( 'Nuevo tipo de comida' ),
		'menu_name'           => __( 'Tipo de comida' ),
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

add_action( 'init', 'taxonomia_tipo_receta' );
