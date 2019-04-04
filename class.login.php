<?php


/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @since      1.0.0
 *
 * @package    Test_Login
 */

class TestLogin {

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     */
    public function __construct()
    {

        $this->plugin_name = 'Test Login';
        if ( defined( 'TEST_LOGIN_VERSION' ) ) {
            $this->version = TEST_LOGIN_VERSION;
        } else {
            $this->version = '1.0.0';
        }

        $this->load_dependencies();
        $this->init_dependencies();

    }

    /**
     * Loading the dependencies
     *
     * @since    1.0.0
     */
    public function load_dependencies() {
        /**
         * The class responsible for the menu in the admin dashboard.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'seavus/admin/class.login-admin.php';

        /**
         * The class responsible for defining the shortcode and actions that occur in the public
         * side of the site
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'seavus/public/class.login-public.php';

        /**
         * The class responsible for defining the custom REST API endpoint
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'seavus/class.login-rest-api.php';
    }

    /**
     * Initiating the dependencies
     *
     * @since    1.0.0
     */
    public function init_dependencies() {
        $login_public = new TestLoginPublic( $this->get_plugin_name(), $this->get_version() );
        $login_admin = new TestLoginAdmin();
        $login_rest_api = new TestLoginRestAPI();
    }

    /**
     * @since    1.0.0
     * @return string
     */
    public function get_plugin_name()
    {
        return $this->plugin_name;
    }

    /**
     * @since    1.0.0
     * @return string
     */
    public function get_version()
    {
        return $this->version;
    }

    /**
     * Attached to activate_{ plugin_basename( __FILES__ ) } by register_activation_hook()
     * Creates a table in the wordpress database for login attempts
     *
     * @since    1.0.0
     * @static
     */
    public static function plugin_activation() {
        global $wpdb;
        $pluginPrefix    = $wpdb->prefix . "tl";
        $sql             = "
        CREATE TABLE " . $pluginPrefix . "_testlogin(
          id INT NOT NULL AUTO_INCREMENT,
          ip_address VARCHAR(16) NOT NULL,
          timestmp DATETIME DEFAULT NOW() ,
          PRIMARY KEY (id)
        );";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }

    /**
     * Removes the database
     *
     * @since    1.0.0
     * @static
     */
    public static function plugin_deactivation( ) {
        global $wpdb;
        $pluginPrefix       = $wpdb->prefix . "tl_";

        $testlogin   = $pluginPrefix . 'testlogin';

        $sql = "DROP TABLE IF EXISTS $testlogin";
        $wpdb->query($sql);
    }
}

?>