<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Title;

class Project extends Controller {

    public function index() {

        $project_id = isset($this->params[0]) ? (int) $this->params[0] : false;

        /* Make sure the project exists and is accessible to the user */
        if(!$project = Database::get('*', 'projects', ['project_id' => $project_id, 'user_id' => 1])) {
            redirect('dashboard');
        }

        /* Get the links list for the project */
        $links_result = Database::$database->query("
            SELECT 
                `links`.*
            FROM 
                `links`
            WHERE 
                `links`.`project_id` = {$project->project_id} AND 
                `links`.`user_id` = 1 AND 
                (`links`.`subtype` = 'base' OR `links`.`subtype` = '')
            ORDER BY
                `links`.`url`
        ");

        /* Iterate over the links */
        $links_logs = [];

        while($row = $links_result->fetch_object()) {
            $row->full_url = url($row->url);

            $links_logs[] = $row;
        }

        $data = [
            'project' => $project
        ];

        $view = new \Altum\Views\View('project/create_link_modals', (array) $this);
        \Altum\Event::add_content($view->run($data), 'modals');


        /* Prepare the View */
        $data = [
            'project'        => $project,
            'links_logs'     => $links_logs,
        ];

        $view = new \Altum\Views\View('project/index', (array) $this);
        $this->add_view_content('content', $view->run($data));

        /* Set a custom title */
        Title::set(sprintf($this->language->project->title, $project->name));

    }

}
