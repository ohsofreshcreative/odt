<?php

/**
 * Empty cart page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-empty.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.0.1
 */

defined('ABSPATH') || exit;

/*
 * @hooked wc_empty_cart_message - 10
 */
do_action('woocommerce_cart_is_empty');

if (wc_get_page_id('shop') > 0) : ?>
	<div class="c-main text-center pb-20">
		<h5>Nie masz jeszcze umówionej wizyty.</h5>
		 <a
            href="<?php echo esc_url(site_url('/umow-wizyte/')); ?>"
            class="btn-primary rounded-full px-6 py-4 inline-block mt-6"
            data-gsap-element="btn">
            Umów wizytę
        </a>
	</div>
<?php endif; ?>