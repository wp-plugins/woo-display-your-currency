<?php
/*
Plugin Name: Woo Display Additional Currency
Plugin URI: 
Description: This plugin will add currency to catalog and product display page.  The currency symbol and exchange rates can be set from admin.
Author: Brijesh Mishra
Version: 1.0
Author URI: 
*/


add_action('admin_notices', 'brijesh_additional_currency_admin_notices');
function brijesh_additional_currency_admin_notices() {

    if (!is_plugin_active('woocommerce/woocommerce.php')) {

        echo '<div id="notice" class="error"><p>';
        echo '<b>' . __('Woocommerce Additional Currency', 'woocommerce-display-additional-currency') . '</b> ' . __('add-on requires', 'woocommerce-display-additional-currency') . ' ' . '<a href="http://www.woothemes.com/woocommerce/" target="_new">' . __('WooCommerce', 'woocommerce-display-additional-currency') . '</a>' . ' ' . __('plugin. Please install and activate it.', 'woocommerce-display-additional-currency');
        echo '</p></div>', "\n";

    }
}	// End brijesh_additional_currency_admin_notices()

// create custom plugin settings menu
add_action('admin_menu', 'woo_display_additional_currency');


function woo_display_additional_currency() {

	//create new top-level menu
	add_menu_page('Woo Additional Currency', 'Woo Additional Currency', 'administrator', __FILE__, 'brijesh_currency_settings_page',plugins_url('/images/icon.png', __FILE__));

	//call register settings function
	add_action( 'admin_init', 'register_mysettings' );
}


function register_mysettings() {
	//register our settings
	register_setting( 'brijesh_additional_currency', 'brijesh_currency_symbol' );
	register_setting( 'brijesh_additional_currency', 'brijesh_exchange_rate' );
	
}

function brijesh_currency_settings_page() {
?>
<div class="wrap">
<h2>Woo Display Additional Currency</h2>
Note: This currency is only for display.

<form method="post" action="options.php">
    <?php settings_fields( 'brijesh_additional_currency' ); ?>
    <?php do_settings_sections( 'brijesh_additional_currency' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">Currency Symbol</th>
        <td><input type="text" name="brijesh_currency_symbol" value="<?php echo esc_attr( get_option('brijesh_currency_symbol') ); ?>" /></td>
        </tr>
         
        <tr valign="top">
        <th scope="row">Exchange Rate</th>
        <td><input type="text" name="brijesh_exchange_rate" value="<?php echo esc_attr( get_option('brijesh_exchange_rate') ); ?>" /></td>
        </tr>
        
        
    </table>
    
    <?php submit_button(); ?>
	<h1>How does this works ?</h1>
	<p>Exchange rate will factor your configured woo commerce currency.  E.g. Say your configured currency is USD and you entered 60 in the above exchange rate box. Now the frontend will show $1 and INR 60. <br></p>
    <p>The above currency is only for display on front end so that visitors coming from other country have an idea of price in their own currency, this will not effect cart page.  </p>
    <p>Hope this helps!.  For additional support, features, functions and service, you can email me at <strong>brijeshmkt@gmail.com</strong> .  I will be gald assisting you.</p>

</form>


</div>
<?php
}
add_filter( 'woocommerce_get_price_html', 'custom_price_html', 100, 2 );


function custom_price_html( $price, $product ){

	$exchangerate = (float)get_option('brijesh_exchange_rate');

    $_new_price = $product->price * $exchangerate;
	$_new_price = number_format((float)$_new_price, 2, '.', '');
    $price = $price . '<br>'.get_option('brijesh_currency_symbol') ." " . $_new_price;

    

    return apply_filters( 'woocommerce_get_price', $price );
}
?>