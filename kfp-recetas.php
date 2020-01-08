<?php
/**
 * Plugin Name:    KFP Recetas
 * Plugin Author:  Juanan Ruiz
 * Plugin URI:     https://kungfupress.com/recetario_de_cocina_con_wordpress/
 * Description:    Un plugin para aprender a programar WP practicando con un gestor de recetas.
 * Text Domain:    kfp-receta
 *
 * @package kfp_receta
 */

global $content;
global $post;
global $query;

define( 'KFP_RECETA_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'KFP_RECETA_DIR', plugin_dir_path( __FILE__ ) );

// Crea CPT receta.
require_once KFP_RECETA_DIR . 'include/register-cpt.php';
// Crea taxonomia receta-category.
require_once KFP_RECETA_DIR . 'include/register-taxonomies.php';
// Registra los metaboxes para los campos personalizados.
require_once KFP_RECETA_DIR . 'include/register-metaboxes.php';
// Graba los campos personalizados asociados a la receta.
require_once KFP_RECETA_DIR . 'include/save-metaboxes.php';
require_once KFP_RECETA_DIR . 'include/display-custom-fields.php';


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
