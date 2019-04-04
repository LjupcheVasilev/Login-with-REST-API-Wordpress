<?php

/**
 * The file that defines the public plugin class
 *
 * A class definition that includes attributes and functions used across the public side of the site.
 *
 * @since      1.0.0
 *
 * @package    Test_Login
 * @subpackage Test_Login/public
 */

class TestLoginPublic {

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
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct( $plugin_name, $version ) {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

        $this->enqueue_scripts();
        $this->enqueue_styles();

        add_shortcode('login_form', array($this, 'shortcode'));
    }

    /**
     * Register the stylesheets for the public side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {

        wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/login.css', array(), $this->version, 'all' );

    }

    /**
     * Register the JavaScript for the public side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {

        wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/login-form.js', array( 'jquery' ), $this->version );

    }

    /**
     * The functionality of the shortcode [login_form]
     *
     * Calls to the REST API and authenticates the user
     * with the username and password entered.
     *
     * @since    1.0.0
     * @return string
     */

    public function shortcode() {
        if ($this->check_times_tried($_SERVER['REMOTE_ADDR'])):

            if (isset($_POST) && isset($_POST['validator'])) {
                global $wpdb;
                $pluginPrefix = $wpdb->prefix . "tl_testlogin";

                $url = get_home_url() . '/wp-json/btobet/v1/login';
                $username = $_POST['username'];
                $password = $_POST['password'];
                $post_data = array(
                    'username' => urlencode($username),
                    'password' => urlencode($password));


                $result = wp_remote_post($url, array(
                    'body' => $post_data));

                if ($result['response']['code'] == 500) {
                    $wpdb->insert($pluginPrefix, array(
                        'ip_address'    => $_SERVER['REMOTE_ADDR']
                    ));
                }

                $result_print = json_decode($result['body']);
                echo "<b>RESULT: </b>";
                print_r($result_print);
            }
            return '
            <div class="login_form">
                <p class="message hidden"></p>
                <form method="post">
                    <h1 class="login_title">Login</h1>
                    <hr>
                    <p>
                        <label for="username">Username</label>
                        <input type="text" class="username" placeholder="Username" name="username" />
                    </p>
                    <p>
                        <label for="password">Password</label>
                        <input type="password" placeholder="Password" class="password" name="password" />
                    </p>
                    <p>
                        <input type="hidden" name="validator" />
                        <input type="submit" name="login" value="Login" class="btn_login" />
                    </p>
                </form>
            </div>
            ';
        else:
            return "<span class='failed'>You have failed to login 3 times in the last 30 minutes. Please wait for 30 minutes</span>";
        endif;
    }

    /**
     * Checks if the user has logged in more than 3 times in the last 30 minutes
     * by getting data from the tl_testlogin table
     *
     * @since    1.0.0
     * @param $ip_address
     * @return bool
     */
    private function check_times_tried($ip_address) {
        global $wpdb;

        $table_name = $wpdb->prefix . "tl_testlogin";
        $times_query = $wpdb->prepare("SELECT COUNT(ip_address) as times FROM $table_name WHERE ip_address=%s AND TIMESTAMPDIFF(MINUTE, timestmp, NOW()) <= 30", array($ip_address));

        $times_res = $wpdb->get_results($times_query, ARRAY_A);

        return $times_res[0]['times'] < 3 ? true : false;
    }
}

?>