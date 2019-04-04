<?php

/**
 * The file that defines the rest api plugin class
 *
 * A class definition that includes REST API endpoint.
 *
 * @since      1.0.0
 *
 * @package    Test_Login
 */

class TestLoginRestAPI {

    /**
     * The namespace of the REST API endpoint.
     *
     * @since    1.0.0
     * @var      string    $my_namespace    The namespace of the REST api endpoint.
     */
    var $my_namespace = 'btobet/v';

    /**
     * The version of the REST API endpoint.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $my_version    The version of the REST API endpoint.
     */
    var $my_version   = '1';

    /**
     * Initialize the class and call action on REST API init.
     *
     * @since    1.0.0
     */
    public function __construct(){
        add_action( 'rest_api_init', array( $this, 'register_routes' ) );
    }


    /**
     * Register route for login.
     *
     * @since    1.0.0
     */
    public function register_routes() {
        $namespace = $this->my_namespace . $this->my_version;
        $base      = 'login';
        register_rest_route( $namespace, '/' . $base, array(
            array(
                'methods'         => WP_REST_Server::CREATABLE,
                'callback'        => array( $this, 'login_callback' )
            )
        )  );
    }

    /**
     * The callback for the REST API endpoint.
     * Authenticates the user
     *
     * @since    1.0.0
     * @param $request - the request from the call
     * @return WP_Error | WP_REST_Response - If the username and password are correct
     * it returns WP_REST_Response with code 200 (OK),
     * or if the username and password are incorrect it returns WP_Error with code 200
     */
    public function login_callback( WP_REST_Request $request ){
        $username = $request['username'];
        $password = $request['password'];

        $user = wp_authenticate( $username, $password );

        if ( is_wp_error($user) ) {
            $result = new WP_Error('Request Failed', "", array("status" => 500, "error" => "Invalid Login Details. <br/>"));
        }
        else {
            $result = new WP_REST_Response( array("status" => "OK", "redirecturl" => get_home_url()), array("status" => "OK"));
        }

        return $result;
    }
}

?>