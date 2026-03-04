<?php
/**
 * Single Product title
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/title.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see        https://woocommerce.com/document/template-structure/
 * @package    WooCommerce\Templates
 * @version    1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

global $product;

// Wyświetlenie SKU nad tytułem
if ( wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type( 'variable' ) ) ) {
    $sku = $product->get_sku() ? $product->get_sku() : esc_html__( 'N/A', 'woocommerce' );
    echo '<div class="product-sku">Kod produktu: <span>' . esc_html( $sku ) . '</span></div>';
}

the_title( '<h4 class="product_title entry-title text-primary block-inline !py-2">', '</h4>' );