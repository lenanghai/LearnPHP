<?php 
/**
* Kai Excerpt with limit content
* copy to functions.php in your theme
*/


// Truncate string to x letters/words
function kai_truncate( $str, $length = 40, $units = 'letters', $ellipsis = '&nbsp;&hellip;' ) {
    if ( $units == 'letters' ) {
        if ( mb_strlen( $str ) > $length ) {
            return mb_substr( $str, 0, $length ) . $ellipsis;
        } else {
            return $str;
        }
    } else {
        $words = explode( ' ', $str );
        if ( count( $words ) > $length ) {
            return implode( " ", array_slice( $words, 0, $length ) ) . $ellipsis;
        } else {
            return $str;
        }
    }
}

// Kai Excerpt 

if ( ! function_exists( 'kai_excerpt' ) ) {
// Limit content param
    function kai_excerpt( $limit = 40 ) {
      return kai_truncate( get_the_excerpt(), $limit, 'words' );
    }
}
