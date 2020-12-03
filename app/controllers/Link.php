<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Models\Domain;
use Altum\Title;

class Link extends Controller {
    public $link;

    public function index() {

        $link_id = isset($this->params[0]) ? (int) $this->params[0] : false;
        $method = isset($this->params[1]) && in_array($this->params[1], ['settings', 'statistics']) ? $this->params[1] : 'settings';

        if(!$this->link = Database::get('*', 'links', ['link_id' => $link_id, 'user_id' => 1])) {
            redirect('dashboard');
        }

        $this->link->settings = json_decode($this->link->settings);

        /* Get the current domain if needed */
        $this->link->domain = $this->link->domain_id ? (new Domain())->get_domain($this->link->domain_id) : null;

        /* Determine the actual full url */
        $this->link->full_url = $this->link->domain ? $this->link->domain->url . $this->link->url : url($this->link->url);

        /* Handle code for different parts of the page */
        switch($method) {
            
            case 'settings':

                if($this->link->type == 'biolink') {
                    
                    /* Get the links available for the biolink */
                    $link_links_result = $this->database->query("SELECT * FROM `links` WHERE `biolink_id` = {$this->link->link_id} ORDER BY `order` ASC");

                    $biolink_link_types = require APP_PATH . 'includes/biolink_link_types.php';

                    /* Add the modals for creating the links inside the biolink */
                    foreach($biolink_link_types as $key) {
                        $data = [
                            'link'                      => $this->link,
                        ];
                        $view = new \Altum\Views\View('link/settings/create_' . $key . '_modal.settings.biolink.method', (array) $this);
                        \Altum\Event::add_content($view->run($data), 'modals');
                    }

                    if($this->link->subtype != 'base') {
                        redirect('link/' . $this->link->biolink_id);
                    }
                }

                /* Get the available domains to use */
                $domains = "minibio.link";

                /* Prepare variables for the view */
                $data = [
                    'link'                      => $this->link,
                    'method'                    => $method,
                    'link_links_result'         => $link_links_result ?? null,
                    'domains'                   => $domains
                ];

                break;
        }

        /* Prepare the method View */
        $view = new \Altum\Views\View('link/' . $method . '.method', (array) $this);
        $this->add_view_content('method', $view->run($data));

        /* Prepare the View */
        $data = [
            'link'      => $this->link,
            'method'    => $method
        ];

        $view = new \Altum\Views\View('link/index', (array) $this);
        $this->add_view_content('content', $view->run($data));

        /* Set a custom title */
        Title::set(sprintf($this->language->link->title, $this->link->url));

    }

}
