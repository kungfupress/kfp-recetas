<?php
/**
 * File: kfp-recetas/include/display-custom-fields.php
 *
 * @package kfp_receta
 */

defined( 'ABSPATH' ) || die();

add_filter( 'the_content', 'kfp_receta_display_custom_fields' );
/**
 * Agrega los custom fields al contenido de la receta
 * Observa que los Custom Fields se devuelven como array (por si hay más de uno)
 * Por ello al llamarlos hay que cargar el primer elemento del array con [0]
 *
 * @param $content
 * @return string
 */
function kfp_receta_display_custom_fields( $content ) {
	$custom_fields = get_post_custom();
	if ( isset( $custom_fields['_imagen'] ) ) {
		$content .= '<img src="' . $custom_fields['_imagen'][0] . '" alt="foto receta">';
	}
	if ( isset( $custom_fields['_galeria'] ) ) {
		$galeria_ids = explode( ',', $custom_fields['_galeria'][0] );
		$content    .= '<div id="vista-previa-galeria">';
		foreach ( $galeria_ids as $attachment_id ) {
			$img      = wp_get_attachment_image_src( $attachment_id, 'thumbnail' );
			$content .= '<div class="miniatura-galeria"><img src="';
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
	if ( isset( $custom_fields['_ingredientes'] ) ) {
		$content .= '<h3>' . __( 'Ingredientes', 'kfp-recetas' ) . '</h3><div>';
		$content .= $custom_fields['_ingredientes'][0] . '</div>';
	}
	if ( isset( $custom_fields['_preparacion'] ) ) {
		$content .= '<h3>' . __( 'Preparación', 'kfp-recetas' ) . '</h3><div>';
		$content .= $custom_fields['_preparacion'][0] . '</div>';
	}

	return $content;
}
