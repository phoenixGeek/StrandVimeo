<?php

namespace Altum;

use Altum\Models\Package;
use Altum\Models\User;
use \Altum\Routing\Router;
use \Altum\Models\Settings;

class App {

    protected $database;

    public function __construct() {

        /* Connect to the database */
        $this->database = Database\Database::initialize();

        /* Parse the URL parameters */
        Router::parse_url();

        /* Handle the controller */
        Router::parse_controller();

        /* Create a new instance of the controller */
        $controller = Router::get_controller(Router::$controller, Router::$path);

        /* Process the method and get it */
        $method = Router::parse_method($controller);

        /* Get the remaining params */
        $params = Router::get_params();

        /* Check for Preflight requests for the tracking of submissions from biolink pages */
        if(Router::$controller == 'LinkAjax') {
            header('Access-Control-Allow-Origin: *');
            header('Access-Control-Allow-Methods: POST, OPTIONS');
            header('Access-Control-Allow-Headers: Content-Type');

            /* Check if preflight request */
            if($_SERVER['REQUEST_METHOD'] == 'OPTIONS') die();
        }

        /* Initiate the Language system */
        Language::initialize(APP_PATH . 'languages/', "english");

        /* Get the needed language strings */
        $language = Language::get();

        date_default_timezone_set(Date::$default_timezone);
        Date::$timezone = date_default_timezone_get();

        /* Setting the datetime for backend usages ( insertions in database..etc ) */
        Date::$date = Date::get();
        
        /* Add main vars inside of the controller */
        $controller->add_params([
            'database'  => $this->database,
            'params'    => $params,
            'language'  => $language,

            /* Potential logged in user */
        ]);

        /* Call the controller method */
        call_user_func_array([ $controller, $method ], []);

        /* Render and output everything */
        $controller->run();

        /* Close database */
        Database\Database::close();
    }

}