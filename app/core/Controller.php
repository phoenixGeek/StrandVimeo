<?php

namespace Altum\Controllers;

use Altum\Models\Page;
use Altum\Routing\Router;
use Altum\Traits\Paramsable;

class Controller {
    
    use Paramsable;

    public $views = [];

    public function __construct(Array $params = []) {

        $this->add_params($params);

    }

    public function add_view_content($name, $data) {

        $this->views[$name] = $data;

    }

    public function run() {

        if(Router::$path == 'link') {
            $wrapper = new \Altum\Views\View('link-path/wrapper', (array) $this);
        }

        if(Router::$path == '') {


            $wrapper = new \Altum\Views\View(Router::$controller_settings['wrapper'], (array) $this);
        }


        echo $wrapper->run();
    }

}
