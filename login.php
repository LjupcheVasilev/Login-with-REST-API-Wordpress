<?php

/*
Plugin Name: Test Login
Plugin URI: /
Description: This is a test login plugin.
Version: 1.0
Author: Ljupche Vasilev
Author URI: http://ljupchevasilev.com
License: GPL2
*/


/**
 * Make sure we don't expose any info if called directly
 */
if ( ! defined( 'WPINC' ) ) {
    die;
}

/**
 * Current plugin version
 */
define( 'TEST_LOGIN_VERSION', '1.0.0' );

/**
 * The plugin directory path
 */
define( 'TEST_LOGIN__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

/**
 * The code that runs during plugin activation.
 * This action is documented in class.login.php
 */
register_activation_hook( __FILE__, array( 'TestLogin', 'plugin_activation' ) );

/**
 * The code that runs during plugin deactivation.
 * This action is documented in class.login.php
 */
register_deactivation_hook( __FILE__, array( 'TestLogin', 'plugin_deactivation' ) );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require_once( TEST_LOGIN__PLUGIN_DIR . 'class.login.php' );

/**
 * Begins execution of the plugin.
 */
$plugin = new TestLogin();

?>