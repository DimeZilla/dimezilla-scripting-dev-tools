<?php
/**
Plugin Name: Dimezilla WordPress Scripting Dev Tools
Plugin URI: radcampaign.com
Description: Provides some helpful tools for developing scripts with WordPress
Author: Joshua Diamond
Version: 0.0
Author URI: radcampaign.com
*/
namespace Dimezilla_Scripting_Dev_Tools;

require( __DIR__ . '/vendor/autoload.php');

if (!function_exists('is_debug')) {
    function is_debug() {
        return defined('WP_DEBUG') && true === WP_DEBUG;
    }
}

function dsdt_root_dir() {
    return dirname(__FILE__);
}

function dsdt_plugin_dir_url() {
    return plugin_dir_url(__FILE__);
}

function dsdt_asset_path($handle) {
    return Assets::assetUri($handle);
}

function load_debug_enqueue_library()
{
    wp_enqueue_script('dsdt/debug-wp-enqueued-library.js');
    // lets get the unminified development versions of
    // react and rect-dom
    wp_deregister_script('react');
    wp_deregister_script('react-dom');
    wp_register_script('react', '/wp-includes/js/dist/vendor/react.js', ['wp-polyfill'], '16.6.3', false);
    wp_register_script('react-dom', '/wp-includes/js/dist/vendor/react-dom.js', ['react'], '16.6.3', false);
}

if (is_debug()) {
    add_action('init', function () {
        wp_register_script('dsdt/debug-wp-enqueued-library.js', dsdt_asset_path('debug-wp-enqueued-library.js'), ["lodash"], null, true);
    });

    add_action('wp_enqueue_scripts', function () {
        load_debug_enqueue_library();
    });

    add_action('admin_enqueue_scripts', function () {
        load_debug_enqueue_library();
    });

    /**
     * Useful debug tool for figuring out what scripts
     * and styles are being loaded and with what handles -
     * provides an object called WP_ENQUEUED that shows is bound to the
     * window and accessible in dev tools.
     */
     $printed = false;
    if (!$printed) {
        add_action( 'wp_print_scripts', function () {
            $loader = new WP_Enqueued_Library_Loader;
            $loader->printLoadScripts();
        }, 103);
        $printed = true;
    }
}
