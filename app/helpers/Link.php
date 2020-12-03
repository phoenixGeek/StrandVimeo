<?php

namespace Altum;
use Altum\Database\Database;

class Link {

    public static function get_biolink($tthis, $link, $user = null, $links = null) {

        /* Determine the background of the biolink */
        $link->design = new \StdClass();
        $link->design->background_class = '';
        $link->design->background_style = '';

        /* Check if the user has the access needed from the package */
        if(!$user->package_settings->custom_backgrounds && in_array($link->settings->background_type, ['image', 'gradient', 'color'])) {

            /* Revert to a default if no access */
            $link->settings->background_type = 'preset';
            $link->settings->background = 'one';

        }

        switch($link->settings->background_type) {

            case 'image':

                $link->design->background_style = 'background: url(\'' . SITE_URL . UPLOADS_URL_PATH . 'backgrounds/' . $link->settings->background . '\');';

                break;

            case 'gradient':

                $link->design->background_style = 'background-image: linear-gradient(135deg, ' . $link->settings->background->color_one . ' 10%, ' . $link->settings->background->color_two . ' 100%);';

                break;

            case 'color':

                $link->design->background_style = 'background: ' . $link->settings->background . ';';

                break;

            case 'preset':

                $link->design->background_class = 'link-body-background-' . $link->settings->background;

                break;
        }

        /* Determine the color of the header text */
        $link->design->text_style = 'color: ' . $link->settings->text_color;

        /* Determine the socials text */
        $link->design->socials_style = 'color: ' . $link->settings->socials_color;

        /* Determine the notification branding settings */
        if($user && !$user->package_settings->removable_branding && !$link->settings->display_branding) {
            $link->settings->display_branding = true;
        }

        if($user && $user->package_settings->removable_branding && !$link->settings->display_branding) {
            $link->settings->display_branding = false;
        }

        /* Check if we can show the custom branding if available */
        if(isset($link->settings->branding, $link->settings->branding->name, $link->settings->branding->url) && !$user->package_settings->custom_branding) {
            $link->settings->branding = false;
        }

        /* Prepare the View */
        $data = [
            'link'  => $link,
            'user'  => $user,
            'links' => $links
        ];

        $view = new \Altum\Views\View('link-path/partials/biolink', (array) $tthis);

        return $view->run($data);

    }

    public static function get_biolink_link($link, $user = null) {

        $data = [];
        /* Require different files for different types of links available */
        switch($link->subtype) {

            case 'vimeo':

                if(preg_match('/https:\/\/(player\.)?vimeo\.com(\/video)?\/(\d+)/', $link->location_url, $match)) {
                    $data['embed'] = $match[3];

                    $view_path = 'link-path/partials/biolink_link_vimeo';
                }

                break;

        }

        if(!isset($view_path)) return null;

        /* Prepare the View */
        $data = array_merge($data, [
            'link'                      => $link,
            'user'                      => $user,
        ]);

        $view = new \Altum\Views\View($view_path);

        return $view->run($data);

    }
}
