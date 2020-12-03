<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Date;
use Altum\Models\User;
use Altum\Title;
use MaxMind\Db\Reader;
use Sinergi\BrowserDetector\Browser;
use Sinergi\BrowserDetector\Os;

class Link extends Controller {
    
    public $link;
    public $user;

    public function index() {

        $link_url = isset($this->params[0]) ? Database::clean_string($this->params[0]) : false;
        $link_id = isset($_GET['link_id']) ? (int) $_GET['link_id'] : false;

        /* Check if the current link accessed is actually the original url or not ( multi domain use ) */
        $original_url_host = parse_url(url())['host'];
        $request_url_host = Database::clean_string($_SERVER['HTTP_HOST']);

        if($original_url_host == $request_url_host) {

            /* If we have the link id, get it via the link id */
            /* This is used for the preview iframe */
            if($link_id) {
                $this->link = Database::get('*', 'links', ['link_id' => $link_id]);
            } else {
                $this->link = Database::get('*', 'links', ['url' => $link_url, 'is_enabled' => 1, 'domain_id' => 0]);
            }

        }

        if(!$this->link) {
            redirect();
        }

        $this->user = (new User())->get($this->link->user_id);

        /* Check if its a scheduled link and we should show it or not */
        if(
            $this->user->package_settings->scheduling &&

            !empty($this->link->start_date) &&
            !empty($this->link->end_date) &&
            (
                \Altum\Date::get('', null) < \Altum\Date::get($this->link->start_date, null, \Altum\Date::$default_timezone) ||
                \Altum\Date::get('', null) > \Altum\Date::get($this->link->end_date, null, \Altum\Date::$default_timezone)
            )
        ) {
            redirect();
        }

        /* Parse the settings */
        $this->link->settings = json_decode($this->link->settings);

        /* Determine the actual full url */
        $this->link->full_url = $this->link->domain_id && !isset($_GET['link_id']) ? $this->link->scheme . $this->link->host . '/' . $this->link->url : url($this->link->url);

        /* Only parse and add statistics if its not coming from inside the preview iframe from the settings */
        if(!isset($_GET['preview'])) {

            /* Generate an id for the log */
            $dynamic_id = md5(
                $this->link->link_id . $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT'] . (new \DateTime())->format('Y-m-d')
            );

            /* Detect the location */
            $maxmind = (new Reader(APP_PATH . 'includes/GeoLite2-Country.mmdb'))->get(get_ip());
            $country_code = $maxmind ? $maxmind['country']['iso_code'] : null;

            /* Detect extra details about the user */
            $os_name = (new Os($_SERVER['HTTP_USER_AGENT']))->getName();
            $browser_name = (new Browser($_SERVER['HTTP_USER_AGENT']))->getName();
            $browser_language = (new \Sinergi\BrowserDetector\Language($_SERVER['HTTP_ACCEPT_LANGUAGE']))->getLanguageLocale();
            $referrer = isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null;
            $device_type = get_device_type($_SERVER['HTTP_USER_AGENT']);

            /* Insert or update the log */
            $is_insert = true;

            /* Add the unique hit to the link table as well */
            if ($is_insert) {
                Database::$database->query("UPDATE `links` SET `clicks` = `clicks` + 1 WHERE `link_id` = {$this->link->link_id}");
            }
        }

        /* Check what to do next */
        if($this->link->type == 'biolink' && $this->link->subtype == 'base') {
            $this->process_biolink();
        } else {
            $this->process_redirect();
        }
    }

    public function process_biolink() {
        
        /* Determine the actual full url */
        $this->link->full_url = $this->link->domain_id && !isset($_GET['link_id']) ? $this->link->scheme . $this->link->host . '/' . $this->link->url : url($this->link->url);

            $result = Database::$database->query("SELECT * FROM `links` WHERE `biolink_id` = {$this->link->link_id} AND `type` = 'biolink' AND `subtype` <> 'base' AND `is_enabled` = 1 ORDER BY `order` ASC");
            $links = [];

            while($row = $result->fetch_object()) {
                $links[] = $row;
            }

        
        $data = [
            'link' => $this->link,
            'links' => $links
        ];

        $view_content = \Altum\Link::get_biolink($this, $this->link, $this->user, $links);

        $this->add_view_content('content', $view_content);

        /* Set a custom title */
        Title::set($this->link->settings->title, true);
    }

    public function process_redirect() {

        /* Check if we should redirect the user or kill the script */
        if(isset($_GET['no_redirect'])) {
            die();
        }

        header('Location: ' . $this->link->location_url, true, 301);

    }
}
