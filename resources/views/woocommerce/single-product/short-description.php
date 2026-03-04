<?php

/**
 * Single product short description
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/short-description.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.3.0
 */

if (! defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

global $post, $product;

$short_description = apply_filters('woocommerce_short_description', $post->post_excerpt);

if (! $short_description) {
	return;
}

if (! $product || ! is_a($product, 'WC_Product')) {
	$product = wc_get_product(get_the_ID());
}

?>

<div class="woocommerce-product-details__short-description">
	<?php echo $short_description; ?>
<!-- 

	<div class="__info flex flex-col border-t border-primary border-dashed pt-8 mt-4">
		<div class="__shipping flex items-center">Wysyłka w 24–48 h </div>
		<div class="__payment flex items-center">Dostępne płatności na podstawie faktury</div>
		<?php if ($product) : ?>
			<div class="__stock flex items-center">
				<?php
				$status = $product->get_stock_status();
				if ($status === 'instock') {
					echo '<p class="stock in-stock">W magazynie</p>';
				} elseif ($status === 'outofstock') {
					echo '<p class="stock out-of-stock">Brak w magazynie</p>';
				} else {
					echo '<p class="stock available-on-backorder">Na zamówienie</p>';
				}
				?>
			</div>
		<?php endif; ?>
	</div> -->
</div>