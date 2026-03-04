<?php

/**
 * Loop Add to Cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/add-to-cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     9.2.0
 */

if (! defined('ABSPATH')) {
	exit;
}

global $product;

// Zdefiniuj SVG jako zmienną dla czystszego kodu
$cart_icon_svg = '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="20" viewBox="0 0 22 20" fill="none"><path d="M10.713 17.446C10.713 18.8565 9.62939 20 8.29272 20C6.95604 20 5.87245 18.8565 5.87245 17.446C5.87245 16.505 6.35479 15.6827 7.07284 15.2397C7.0598 15.2032 6.02524 10.7536 3.96925 1.89092C3.93556 1.74538 3.81798 1.64013 3.67875 1.62672L0.770066 1.62527C0.344795 1.62522 0 1.26142 0 0.81261C0 0.37875 0.322161 0.0243414 0.72782 0.00118521L3.6486 0C4.49098 0 5.22675 0.59372 5.45064 1.44544L5.83146 3.08707L21.2299 3.08721C21.6552 3.08721 22 3.45105 22 3.89982C22 3.93214 21.9898 4.01683 21.9695 4.15394L20.0496 12.2799C19.9581 12.6673 19.6171 12.9198 19.2524 12.8944L8.10187 12.8973L8.502 14.6256C8.5357 14.7712 8.65328 14.8764 8.7925 14.8898L17.7537 14.892C19.0904 14.892 20.1739 16.0355 20.1739 17.446C20.1739 18.7679 19.2222 19.8553 18.0023 19.9866C17.9206 19.9954 17.8376 20 17.7537 20C16.417 20 15.3334 18.8565 15.3334 17.446C15.3334 17.1181 15.392 16.8046 15.4986 16.5166H10.5477C10.6544 16.8046 10.713 17.1181 10.713 17.446ZM17.7538 16.5173C17.2677 16.5173 16.8737 16.9331 16.8737 17.446C16.8737 17.9589 17.2677 18.3747 17.7538 18.3747C18.2398 18.3747 18.6339 17.9589 18.6339 17.446C18.6339 16.9331 18.2398 16.5173 17.7538 16.5173ZM8.29267 16.5173C7.80662 16.5173 7.41258 16.9331 7.41258 17.446C7.41258 17.9589 7.80662 18.3747 8.29267 18.3747C8.77872 18.3747 9.17276 17.9589 9.17276 17.446C9.1728 16.9331 8.77876 16.5173 8.29267 16.5173ZM20.2501 4.71229H6.20774L7.72593 11.2726L18.7003 11.2721L20.2501 4.71229Z" fill="#FFF"/></svg>';

echo apply_filters(
	'woocommerce_loop_add_to_cart_link', // WPCS: XSS ok.
	sprintf(
		'<a href="%s" data-quantity="%s" class="%s" %s>%s</a>',
		esc_url($product->add_to_cart_url()),
		esc_attr(isset($args['quantity']) ? $args['quantity'] : 1),
		esc_attr(isset($args['class']) ? $args['class'] : 'button'),
		isset($args['attributes']) ? wc_implode_html_attributes($args['attributes']) : '',
		// Zamiast esc_html( $product->add_to_cart_text() ) wstawiamy naszą ikonę
		$cart_icon_svg
	),
	$product,
	$args
);
