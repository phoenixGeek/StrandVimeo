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
                `links`.*, `domains`.`scheme`, `domains`.`host`
            FROM 
                `links`
            LEFT JOIN 
                `domains` ON `links`.`domain_id` = `domains`.`domain_id`
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
            $row->full_url = $row->domain_id ? $row->scheme . $row->host . '/' . $row->url : url($row->url);

            $links_logs[] = $row;
        }

        /* Get statistics */
        if(count($links_logs)) {
            $logs_chart = [];
            $start_date_query = (new \DateTime())->modify('-30 day')->format('Y-m-d H:i:s');
            $end_date_query = (new \DateTime())->modify('+1 day')->format('Y-m-d H:i:s');
            $project_ids = implode(', ', array_unique(array_map(function($row) {
                return (int) $row->link_id;
            }, $links_logs)));

            $logs_result = Database::$database->query("
                SELECT
                     `count`,
                     DATE_FORMAT(`date`, '%Y-%m-%d') AS `formatted_date`
                FROM
                     `track_links`
                WHERE
                    `link_id` IN ({$project_ids})
                    AND (`date` BETWEEN '{$start_date_query}' AND '{$end_date_query}')
                ORDER BY
                    `formatted_date`
            ");

            /* Generate the raw chart data and save logs for later usage */
            while($row = $logs_result->fetch_object()) {
                $logs[] = $row;

                $row->formatted_date = \Altum\Date::get($row->formatted_date, 4);

                /* Handle if the date key is not already set */
                if (!array_key_exists($row->formatted_date, $logs_chart)) {
                    $logs_chart[$row->formatted_date] = [
                        'impressions' => 0,
                        'uniques' => 0,
                    ];
                }

                /* Distribute the data from the database row */
                $logs_chart[$row->formatted_date]['uniques']++;
                $logs_chart[$row->formatted_date]['impressions'] += $row->count;
            }

            $logs_chart = get_chart_data($logs_chart);
        }

        /* Create Link Modal */
        $domains = "minibio.link";

        $data = [
            'project' => $project
        ];

        $view = new \Altum\Views\View('project/create_link_modals', (array) $this);
        \Altum\Event::add_content($view->run($data), 'modals');


        /* Prepare the View */
        $data = [
            'project'        => $project,
            'domains'        => $domains,
            'links_logs'     => $links_logs,
            'logs_chart'     => $logs_chart ?? false,
        ];

        $view = new \Altum\Views\View('project/index', (array) $this);
        $this->add_view_content('content', $view->run($data));


        /* Set a custom title */
        Title::set(sprintf($this->language->project->title, $project->name));

    }

}
