<?php
/**
 * File: kfp-recetas/include/register-taxonomies.php
 *
 * @package kfp_receta
 */

defined( 'ABSPATH' ) || die();
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
