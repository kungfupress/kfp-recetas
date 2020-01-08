<?php
/**
 * File: kfp-recetas/include/save-metaboxex.php
 *
 * @package kfp_receta
 */

defined( 'ABSPATH' ) || die();

add_action( 'save_post', 'kfp_receta_save_meta_boxes' );
/**
 * Graba los campos personalizados que vienen del formulario de edición del post
 *
 * @param int $post_id Post ID.
 *
 * @return bool|int
 */
function kfp_receta_save_meta_boxes( $post_id ) {
	// Comprueba que el tipo de post es receta.
	if ( isset( $_POST ) && 'receta' !== $_POST['post_type'] ) {
		return $post_id;
	}
	// Comprueba que el nonce es correcto para evitar ataques CSRF.
	if ( ! isset( $_POST['receta_nonce'] ) || ! wp_verify_nonce( $_POST['receta_nonce'], 'graba_receta' ) ) {
		return $post_id;
	}
	// Comprueba que el usuario actual tiene permiso para editar esto
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		wp_die(
			'<h1>' . __( 'Necesitas más privilegios para publicar contenidos.', 'kfp-recetas' ) . '</h1>' .
			'<p>' . __( 'Lo siento, no puedes crear contenidos desde esta cuenta.', 'kfp-recetas' ) . '</p>',
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
