<?php
/**
* Filter ajax fragments
* @author : Kai
*/

if (class_exists('woocommerce')) {

    // Ensure cart contents update when products are added to the cart via AJAX (place the following in functions.php)
    add_filter('add_to_cart_fragments', 'woocommerce_header_add_to_cart_fragment');

    function woocommerce_header_add_to_cart_fragment($fragments) {
        global $woocommerce;

        ob_start();
        // Do some things else
        $fragments['a.cart-contents'] = ob_get_clean();

        return $fragments;
    }
    }
