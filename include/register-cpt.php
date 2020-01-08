<?php
/*
 * File: kfp-recetas/include/register-cpt.php
 *
 * @package kfp_receta
 */

defined( 'ABSPATH' ) || die();

add_action( 'init', 'kfp_receta_register_post_type', 0 );
/**
 * Register Custom Post Type Receta
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
	// Ejecuta flush_rewrite_rules() si es la primera vez que se define el CPT
	// Su misión es hacer que funcionen los permalinks del CPT
	// Evita que se ejecute más veces comprobando un option para ganar tiempo.
	$check_option = get_option( 'post_type_rules_flased_receta' );
	if ( true !== $check_option ) {
		flush_rewrite_rules( false );
		update_option( 'post_type_rules_flased_receta', true );
	}
}
