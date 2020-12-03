<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Date;
use Altum\Response;
use Altum\Routing\Router;

class LinkAjax extends Controller {

    public function index() {

        switch($_POST['request_type']) {

            /* Create */
            case 'create': $this->create(); break;

            /* Delete */
            case 'delete': $this->delete(); break;

        }

        die($_POST['request_type']);
    }

    private function create() {
        $_POST['type'] = trim(Database::clean_string($_POST['type']));

        /* Check for possible errors */
        if(!in_array($_POST['type'], ['link', 'biolink'])) {
            die();
        }

        switch($_POST['type']) {
            case 'link':

                $this->create_link();

                break;

            case 'biolink':

                $biolink_link_types = require APP_PATH . 'includes/biolink_link_types.php';

                /* Check for subtype */
                if(isset($_POST['subtype']) && in_array($_POST['subtype'], $biolink_link_types)) {
      
                    $_POST['subtype'] = trim(Database::clean_string($_POST['subtype']));

                        $this->create_biolink_other($_POST['subtype']);

                } else {
                    /* Base biolink */
                    $this->create_biolink();
                }

                break;
        }

        die();
    }


    private function create_biolink() {

        $_POST['project_id'] = (int) $_POST['project_id'];
        $_POST['url'] = !empty($_POST['url']) ? get_slug(Database::clean_string($_POST['url'])) : false;

        if(!Database::exists('project_id', 'projects', ['user_id' => 1, 'project_id' => $_POST['project_id']])) {
            die();
        }

        /* Check for duplicate url if needed */
        if($_POST['url']) {
            if(Database::exists('link_id', 'links', ['url' => $_POST['url']])) {
                Response::json($this->language->create_biolink_modal->error_message->url_exists, 'error');
            }
        }

        /* Start the creation process */
        $url = $_POST['url'] ? $_POST['url'] : string_generate(10);
        $type = 'biolink';
        $subtype = 'base';

        $binding_name = url();
        $binding_url = url();

        $settings = json_encode([
            'title' => $this->language->link->biolink->title_default,
            'description' => $this->language->link->biolink->description_default,
            'display_verified' => false,
            'image' => '',
            'background_type' => 'preset',
            'background' => 'one',
            'text_color' => 'white',
            'socials_color' => 'white',
            'google_analytics' => '',
            'facebook_pixel' => '',
            'display_branding' => true,
            'branding' => [
                'url' => $binding_url,
                'name' => $binding_name
            ],
            'seo' => [
                'title' => '',
                'meta_description' => ''
            ],
            'utm' => [
                'medium' => '',
                'source' => '',
            ],
            'socials' => [],
            'font' => null
        ]);

        /* Generate random url if not specified */
        while(Database::exists('link_id', 'links', ['url' => $url])) {
            $url = string_generate(10);
        }

        $this->check_url($_POST['url']);

        $username = Database::simple_get('name', 'users', ['user_id' => 1]);
        $name = 'Get your LinkinBio';
        $user_id = 1;
        /* Insert to database */
        $stmt = Database::$database->prepare("INSERT INTO `links` (`project_id`, `user_id`, `type`, `subtype`, `url`, `settings`, `date`) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('sssssss', $_POST['project_id'], $user_id, $type, $subtype, $url,  $settings, \Altum\Date::$date);
        $stmt->execute();
        $link_id = $stmt->insert_id;
        $stmt->close();

        Response::json('', 'success', ['url' => url('link/' . $link_id). '?tab=links']);
    }

    private function create_biolink_link() {

        $_POST['link_id'] = (int) $_POST['link_id'];
        $_POST['location_url'] = trim(Database::clean_string($_POST['location_url']));

        $this->check_location_url($_POST['location_url']);

        if(!$project_id = Database::simple_get('project_id', 'links', ['user_id' => 1, 'link_id' => $_POST['link_id'], 'type' => 'biolink', 'subtype' => 'base'])) {
            die();
        }

        $max_order = Database::simple_get_max_order('order', 'links', ['user_id' => 1, 'biolink_id' => $_POST['link_id']], 'order');
        $new_order = (int) $max_order + 1;

        $url = string_generate(10);
        $type = 'biolink';
        $subtype = 'link';
        $settings = json_encode([
            'name' => $this->language->link->biolink->link->name_default,
            'text_color' => 'black',
            'background_color' => 'white',
            'outline' => false,
            'border_radius' => 'rounded',
            'animation' => false,
            'animation_duration' => '2s',
            'icon' => ''
        ]);

        /* Generate random url if not specified */
        while(Database::exists('link_id', 'links', ['url' => $url])) {
            $url = string_generate(10);
        }

        $stmt = Database::$database->prepare("INSERT INTO `links` (`project_id`, `biolink_id`, `user_id`, `type`, `subtype`, `url`, `location_url`, `settings`, `order`, `date`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('ssssssssss', $project_id, $_POST['link_id'], 1, $type, $subtype, $url, $_POST['location_url'], $settings, $new_order, \Altum\Date::$date);
        $stmt->execute();
        $stmt->close();

        Response::json('', 'success', ['url' => url('link/' . $_POST['link_id'] . '?tab=links')]);
    }

    private function create_biolink_other($subtype) {

        $_POST['link_id'] = (int) $_POST['link_id'];
        $_POST['location_url'] = trim(Database::clean_string($_POST['location_url']));

        $user_id = 1;
        $this->check_location_url($_POST['location_url']);

        if(!$project_id = Database::simple_get('project_id', 'links', ['user_id' => 1, 'link_id' => $_POST['link_id'], 'type' => 'biolink', 'subtype' => 'base'])) {
            die();
        }

        $max_order = Database::simple_get_max_order('order', 'links', ['user_id' => 1, 'biolink_id' => $_POST['link_id']], 'order');
        $new_order = (int) $max_order + 1;

        $url = string_generate(10);
        $type = 'biolink';
        $settings = json_encode([]);

        /* Generate random url if not specified */
        while(Database::exists('link_id', 'links', ['url' => $url])) {
            $url = string_generate(10);
        }

        $stmt = Database::$database->prepare("INSERT INTO `links` (`project_id`, `biolink_id`, `user_id`, `type`, `subtype`, `url`, `location_url`, `settings`, `order`, `date`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('ssssssssss', $project_id, $_POST['link_id'], $user_id, $type, $subtype, $url, $_POST['location_url'], $settings, $new_order, \Altum\Date::$date);
        $stmt->execute();
        $stmt->close();

        Response::json('', 'success', ['url' => url('link/' . $_POST['link_id'] . '?tab=links')]);

    }

    private function delete() {

        $_POST['link_id'] = (int) $_POST['link_id'];
        $user_id = 1;
        /* Check for possible errors */
        if(!$link = Database::get(['project_id', 'biolink_id', 'type', 'subtype', 'settings'], 'links', ['user_id' => 1, 'link_id' => $_POST['link_id']])) {
            die();
        }

        if(empty($errors)) {

            if( $link->subtype === 'pdf' ) {

                $link->settings = json_decode($link->settings);
                $file_name = $link->settings->pdf;
                if( !empty($link->settings->pdf) && file_exists(UPLOADS_PATH . 'pdfs/'. $file_name) ) {
                    unlink(UPLOADS_PATH . 'pdfs/'. $file_name);
                }
            }
            /* Delete from database */
            $stmt = Database::$database->prepare("DELETE FROM `links` WHERE `link_id` = ? OR `biolink_id` = ? AND `user_id` = ?");
            $stmt->bind_param('sss', $_POST['link_id'], $_POST['link_id'], $user_id);
            $stmt->execute();
            $stmt->close();

            /* Determine where to redirect the user */
            if($link->type == 'biolink' && $link->subtype != 'base') {
                $redirect_url = url('link/' . $link->biolink_id . '?tab=links');
            } else {
                $redirect_url = url('project/' . $link->project_id);
            }

            Response::json('', 'success', ['url' => $redirect_url]);
        }
    }

    /* Function to bundle together all the checks of a custom url */
    private function check_url($url) {

        if($url) {
            /* Make sure the url alias is not blocked by a route of the product */
            if(array_key_exists($url, Router::$routes[''])) {
                Response::json($this->language->link->error_message->blacklisted_url, 'error');
            }


        }

    }

    /* Function to bundle together all the checks of an url */
    private function check_location_url($url) {

        if(empty(trim($url))) {
            Response::json($this->language->global->error_message->empty_fields, 'error');
        }

        $url_details = parse_url($url);

        if(!isset($url_details['scheme'])) {
            Response::json($this->language->link->error_message->invalid_location_url, 'error');
        }


    }
}