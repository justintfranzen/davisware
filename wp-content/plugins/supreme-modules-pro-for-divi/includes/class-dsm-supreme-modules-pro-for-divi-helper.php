<?php
namespace DiviSupreme\Classes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class DiviSupreme_Helpers {

	/**
	 * Get Divi Library
	 *
	 * @return array
	 * @since 4.6.4
	 */
	public static function dsm_load_library() {
		$args = array(
			'post_type'      => 'et_pb_layout',
			'posts_per_page' => -1,
		);

		if ( false === ( $dsm_library_list = get_transient( 'dsm_load_library' ) ) ) {

			$dsm_library_list = array( 'none' => '-- Select Library --' );

			if ( $categories = get_posts( $args ) ) {
				foreach ( $categories as $category ) {
					$dsm_library_list[ $category->ID ] = $category->post_title;
				}
			}

			set_transient( 'dsm_load_library', $dsm_library_list, 24 * HOUR_IN_SECONDS );
		}

		return get_transient( 'dsm_load_library' );
	}
	public static function dsm_delete_library_transient() {
		delete_transient( 'dsm_load_library' );
	}
}
