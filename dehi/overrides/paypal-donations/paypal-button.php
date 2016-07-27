<!-- Begin PayPal Donations by https://www.tipsandtricks-hq.com/paypal-donations-widgets-plugin -->
<?php /* zig 27july16 remove LF from htlm & PHP_EOLs */ ?>
<?php
$url = isset($pd_options['sandbox']) ? 'https://www.sandbox.paypal.com/cgi-bin/webscr' : 'https://www.paypal.com/cgi-bin/webscr';
?>

<form action="<?php echo apply_filters('paypal_donations_url', $url); ?>" method="post"<?php
if (isset($pd_options['new_tab'])) {
        echo ' target="_blank"';
}
?>>
    <div class="paypal-donations"><input type="hidden" name="cmd" value="_donations" /><input type="hidden" name="bn" value="TipsandTricks_SP" /><input type="hidden" name="business" value="<?php echo $pd_options['paypal_account']; ?>" />
<?php
        # Build the button
        $paypal_btn = '';
        $indent = str_repeat(" ", 8);

        // Optional Settings
        if ($pd_options['page_style'])
            $paypal_btn .=  $indent.'<input type="hidden" name="page_style" value="' .esc_attr($pd_options['page_style']). '" />';
        if ($return_page)
            $paypal_btn .=  $indent.'<input type="hidden" name="return" value="' .esc_url($return_page). '" />'; // Return Page
        if ($purpose)
            $paypal_btn .=  apply_filters('paypal_donations_purpose_html', $indent.'<input type="hidden" name="item_name" value="' .esc_attr($purpose). '" />');  // Purpose
        if ($reference)
            $paypal_btn .=  $indent.'<input type="hidden" name="item_number" value="' .esc_attr($reference). '" />';  // Any reference for this donation
        if ($amount){
            if(!is_numeric($amount)){
                wp_die('Error! Donation amount must be a numeric value.');
            }
            $paypal_btn .=  $indent.'<input type="hidden" name="amount" value="' . apply_filters( 'paypal_donations_amount', $amount ) . '" />';
        }

        if (!empty($validate_ipn)){
            $notify_url = site_url() . '/?ppd_paypal_ipn=process';
            $paypal_btn .=  $indent.'<input type="hidden" name="notify_url" value="' .esc_url($notify_url). '" />'; // Notify URL
        }
        
        // More Settings
        if (isset($pd_options['return_method']))
            $paypal_btn .= $indent.'<input type="hidden" name="rm" value="' .esc_attr($pd_options['return_method']). '" />';
        if (isset($pd_options['currency_code']))
            $paypal_btn .= $indent.'<input type="hidden" name="currency_code" value="' .esc_attr($pd_options['currency_code']). '" />';
        if (isset($pd_options['button_localized']))
            { $button_localized = $pd_options['button_localized']; } else { $button_localized = 'en_US'; }
        if (isset($pd_options['set_checkout_language']) and $pd_options['set_checkout_language'] == true)
            $paypal_btn .= $indent.'<input type="hidden" name="lc" value="' .esc_attr($pd_options['checkout_language']). '" />';

        // Settings not implemented yet
        //      $paypal_btn .=     '<input type="hidden" name="amount" value="20" />';

        // Get the button URL
        if ( $pd_options['button'] != "custom" && !$button_url){
            $button_localized = apply_filters('pd_button_localized_value', $button_localized);
            $button_url = str_replace('en_US', $button_localized, $donate_buttons[$pd_options['button']]);
        }        
        $paypal_btn .=  $indent.'<input type="image" src="' .esc_url($button_url). '" name="submit" alt="PayPal - The safer, easier way to pay online." />';

        // PayPal stats tracking
        if (!isset($pd_options['disable_stats']) or $pd_options['disable_stats'] != true)
            $paypal_btn .=  $indent.'<img alt="" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1" />';
        echo $paypal_btn;
?>
    </div>
</form>
<!-- End PayPal Donations -->
