<?php
/**
 * Customer completed order email
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates\Emails
 * @version 10.4.0
 */

use Automattic\WooCommerce\Utilities\FeaturesUtil;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$email_improvements_enabled = FeaturesUtil::feature_is_enabled( 'email_improvements' );

// Sprawdź, czy zamówienie zawiera produkt o ID 509.
$has_contract_product = false;
foreach ( $order->get_items() as $item ) {
    if ( (int) $item->get_product_id() === 509 || (int) $item->get_variation_id() === 509 ) {
        $has_contract_product = true;
        break;
    }
}

/*
 * @hooked WC_Emails::email_header() Output the email header
 */
do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

<?php echo $email_improvements_enabled ? '<div class="email-introduction">' : ''; ?>

<?php if ( $has_contract_product ) : ?>

    <p>Dzień dobry,</p>
    <p>cieszymy się, że jesteście Państwo z nami - zamówienie zostało pomyślnie zrealizowane.</p>
    <p>W załączniku przesyłamy kontrakt określający zasady współpracy. Prosimy o zapoznanie się z nim przed pierwszym spotkaniem. Przygotowaliśmy go z troską o komfort, bezpieczeństwo i jasność naszej wspólnej pracy.</p>
    <p>Jeśli pojawią się pytania, jesteśmy tu dla Państwa.<br>Dobrego dnia!</p>

<?php else : ?>

    <p>Dzień dobry,</p>
    <p>cieszymy się, że jesteście Państwo z nami - zamówienie zostało pomyślnie zrealizowane.</p>

    <?php
    /*
     * @hooked WC_Emails::order_details() Shows the order details table.
     */
    do_action( 'woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email );

    /*
     * @hooked WC_Emails::order_meta() Shows order meta data.
     */
    do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email );

    /*
     * @hooked WC_Emails::customer_details() Shows customer details
     */
    do_action( 'woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email );
    ?>

    <p>Jeśli pojawią się pytania, jesteśmy tu dla Państwa.<br>Dobrego dnia!</p>

<?php endif; ?>

<?php echo $email_improvements_enabled ? '</div>' : ''; ?>

<?php
/**
 * Show user-defined additional content - this is set in each email's settings.
 */
if ( $additional_content ) {
    echo $email_improvements_enabled ? '<table border="0" cellpadding="0" cellspacing="0" width="100%" role="presentation"><tr><td class="email-additional-content">' : '';
    echo wp_kses_post( wpautop( wptexturize( $additional_content ) ) );
    echo $email_improvements_enabled ? '</td></tr></table>' : '';
}

/*
 * @hooked WC_Emails::email_footer() Output the email footer
 */
do_action( 'woocommerce_email_footer', $email );