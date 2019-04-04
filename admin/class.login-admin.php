<?php


/**
 * The file that defines the admin plugin class
 *
 * A class definition that includes attributes and functions used across the admin area.
 *
 * @since      1.0.0
 *
 * @package    Test_Login
 * @subpackage Test_Login/admin
 */


class TestLoginAdmin {

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     */
    public function __construct() {

        add_action( 'admin_menu', array( 'TestLoginAdmin', 'admin_menu' ));

    }

    /**
     * Adds menu in the dashboard called Test Login
     * that explains the functionality of the plugin
     *
     * @since    1.0.0
     */
    public function admin_menu() {
        add_menu_page( 'Test Login', 'Test Login', 'manage_options', 'test-login', array( 'TestLoginAdmin', 'admin_page' ));
    }

    /**
     * The function that gets called when the Test Login menu opens
     *
     * @since    1.0.0
     */
    public function admin_page() {
        ?>
        <div class="wrap">
            <h1> <?php echo esc_html(get_admin_page_title()) ?></h1>
            <p> Use the following shortcode in order to add login form on any wordpress page:</p>
            <p style="color:#FF0000">[login_form]</p>
        </div>
        <?php
    }
}

?>