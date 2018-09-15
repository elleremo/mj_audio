<?php

function mj_get_term_apply_categories( $tem_id ) {
	$categories = get_field( 'categories', 'term_' . $tem_id );
	$p_arr      = array();
	if ( is_array( $categories ) && count( $categories ) > 0 ) {
		foreach ( $categories as $category ) {
			if ( strpos( $category, "_" ) ) {
				preg_match( '/_[0-9]{1,3}$/', $category, $result );
				$key           = str_replace( '_', '', $result[0] );
				$p_arr[ $key ] = str_replace( $result[0], '', $category );
			}
		}

		$out_arr = array();

		foreach ( $p_arr as $k => $v ) {
			$out_arr[ $v ] = array();

			foreach ( $categories as $category_c ) {
				if ( strpos( $category_c, "~" . $k ) ) {
					$name            = str_replace( '~' . $k, '', $category_c );
					$out_arr[ $v ][] = $name;
				}
			}

		}

		return $out_arr;
	}

	return false;
}

//mj_get_term_apply_categories( 2 );