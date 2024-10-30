<?php
/**
 * @package Millionchats
 * @version 1.1.1
 */
/*
Plugin Name: Millionchats
Description: Install a button on your website to start talking to your customers on Whatsapp. Automated conversations, chatbots and multi-agent support are available in the Pro version.
Author: Nicehop
Version: 1.1.1
Text Domain: millionchats
Domain Path: /languages
*/


function millionchats_draw_btn() {
    $options = get_option( 'millionchats_plugin_options' );
    $api_key = $options['api_key'];
    wp_enqueue_script( 'millionchats_snippet_script',  'https://www.millionchats.com/mc-client.php?id=' .  $api_key);
}

add_action('wp_footer','millionchats_draw_btn');



/*                  SHORTCODES                               */
function millionchats_form_shortcode( $atts = []) {
    // normalize attribute keys, lowercase
    $atts = array_change_key_case( (array) $atts, CASE_LOWER );
 
    // override default attributes with user attributes
    $mc_atts = shortcode_atts(
        array(
            'form' => '',
        ), $atts
    );
 
    // start box
    $o = '<div id="mc_form" data-formid="' . esc_html__( $mc_atts['form']) . '"></div>';
 
    // return output
    return $o;
}
 
/**
 * Central location to create all shortcodes.
 */
function millionchats_shortcodes_init() {
    add_shortcode( 'millionchats_form', 'millionchats_form_shortcode' );
}
 
add_action( 'init', 'millionchats_shortcodes_init' );




/*               ADMINISTRACION                              */
                                                       
function millionchats_enqueue_custom_admin_style($hook_suffix) {

    // Check if it's the ?page=yourpagename. If not, just empty return before executing the folowing scripts. 
    if($hook_suffix != 'settings_page_millionchats-plugin') {
        return;
    }

    // Load your css.
    wp_register_style( 'millionchats_admin_css', plugin_dir_url(__FILE__) . 'admin_css/css.css', false, '1.0.0' );
    wp_enqueue_style( 'millionchats_admin_css' );
}
add_action( 'admin_enqueue_scripts', 'millionchats_enqueue_custom_admin_style' );


function millionchats_add_plugin_page_settings_link( $links ) {
	$links[] = '<a href="' .
		admin_url( 'options-general.php?page=millionchats-plugin' ) .
		'">' . __('Settings', 'millionchats') . '</a>';
	return $links;
}
add_filter('plugin_action_links_'.plugin_basename(__FILE__), 'millionchats_add_plugin_page_settings_link');


function millionchats_add_settings_page() {
    add_options_page( 'millionchats', 'Millionchats', 'manage_options', 'millionchats-plugin', 'millionchats_render_plugin_settings_page' );
}
add_action( 'admin_menu', 'millionchats_add_settings_page' );


function millionchats_render_plugin_settings_page() {

    $options = get_option( 'millionchats_plugin_options' );
    $apiKey = (!empty($options['api_key'])) ? $options['api_key'] : "";

    echo '<h2>' . __('Plugin activation and Settings', 'millionchats') . '</h2>
    <p>' . __('Thank you for activating the Millionchats plugin.', 'millionchats') . '</p>
    <p>' . __('You\'re just two steps away from starting to connect with your customers on Whatsapp.', 'millionchats') . '</p>
    <p>' . __('Step 1. Create your account on the millionchats website, or click', 'millionchats') . ' <a href="https://app.millionchats.com?locale=' . get_locale() . '" target="_blank">' . __('here', 'millionchats') . '</a></p>
    <p>' . __('Step 2. Get your License key and introduce it in the API key field below. Get your key', 'millionchats') . ' <a href="https://app.millionchats.com/apikey.php?locale=' . get_locale() . '" target="_blank">' . __('here', 'millionchats') . '</a></p>
    <p>' . __('Do you want to learn more about how to configure your plugin? Click', 'millionchats') . ' <a href="https://app.millionchats.com/help_wp.php?locale=' . get_locale() . '" target="_blank">' . __('here', '', 'millionchats') . '</a></p>
    <form action="options.php" method="post">';

    settings_fields( 'millionchats_plugin_options' );
    do_settings_sections( 'millionchats_plugin' );

    echo '<input name="submit" class="button button-primary" type="submit" value="' . __('Save', 'millionchats') . '" />
    </form>
    <hr/>
    <ul class="millionchats_settings_menu">';

    if(!empty($apiKey)){
        echo '
        <li><a href="https://app.millionchats.com?locale=' . get_locale() . '" class="millionchats_btn_connect" target="_blank">' . __('Connect', 'millionchats') . '</a></li>
        <li><a href="https://app.millionchats.com/settings.php?locale=' . get_locale() . '" class="millionchats_btn_settings" target="_blank">' . __('Settings', 'millionchats') . '</a>
        <li><a href="https://app.millionchats.com/bubble.php?locale=' . get_locale() . '" class="millionchats_btn_bubble" target="_blank">' . __('Add bubble', 'millionchats') . '</a>
        <li><a href="https://app.millionchats.com/bubbles.php?system=wordpress&locale=' . get_locale() . '" class="millionchats_btn_bubbles" target="_blank">' . __('View bubbles', 'millionchats') . '</a>
        <li><a href="https://app.millionchats.com/forms.php?system=wordpress&locale=' . get_locale() . '" class="millionchats_btn_forms" target="_blank">' . __('View forms', 'millionchats') . '</a>';

    } else {
        echo '
        <li><a href="#" class="millionchats_btn_connect millionchats_disabled_btn" onclick="javascript:alert(\'' . __('Please, enter an Api Key first', 'millionchats') . '\')">' . __('Connect', 'millionchats') . '</a></li>
        <li><a href="#" class="millionchats_btn_settings millionchats_disabled_btn" onclick="javascript:alert(\'' . __('Please, enter an Api Key first', 'millionchats') . '\')">' . __('Settings', 'millionchats') . '</a>
        <li><a href="#" class="millionchats_btn_bubble millionchats_disabled_btn" onclick="javascript:alert(\'' . __('Please, enter an Api Key first', 'millionchats') . '\')">' . __('Add bubble', 'millionchats') . '</a>
        <li><a href="#" class="millionchats_btn_forms millionchats_disabled_btn" onclick="javascript:alert(\'' . __('Please, enter an Api Key first', 'millionchats') . '\')">' . __('View forms', 'millionchats') . '</a>';
    }

echo '
</ul>
    ';
}


function millionchats_register_settings() {
    register_setting( 'millionchats_plugin_options', 'millionchats_plugin_options', 'millionchats_plugin_options_validate' );
    
    add_settings_section( 'api_key', 'API License Key', 'millionchats_plugin_api_key_text', 'millionchats_plugin' );
    add_settings_field( 'millionchats_plugin_setting_api_key', 'Api Key', 'millionchats_plugin_setting_api_key', 'millionchats_plugin', 'api_key' );
}
add_action( 'admin_init', 'millionchats_register_settings' );


function millionchats_plugin_options_validate( $input ) {
    $newinput['api_key'] = trim( $input['api_key'] );
    return $newinput;
}


function millionchats_plugin_api_key_text() {
    echo '<p>' . __('Introduce your API license key in the field below. You can obtain your license key', 'millionchats') . ' <a href="https://app.millionchats.com/apikey.php?locale=' . get_locale() . '" target="_blank">' . __('here', 'millionchats') . '</a></p>';
}


function millionchats_plugin_setting_api_key() {
    $options = get_option( 'millionchats_plugin_options' );
    echo "<input id='millionchats_plugin_api_key' name='millionchats_plugin_options[api_key]' type='text' value='" . esc_attr( @$options['api_key'] ) . "' />";
}
 ?>