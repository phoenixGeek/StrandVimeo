<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Date;
use Altum\Response;

class ProjectAjax extends Controller {

    public function index() {

        if(!empty($_POST) && isset($_POST['request_type'])) {

            switch($_POST['request_type']) {

                /* Create */
                case 'create': $this->create(); break;

                /* Update */
                case 'update': $this->update(); break;

                /* Delete */
                case 'delete': $this->delete(); break;

            }

        }

        die();
    }

    private function create() {

        $_POST['name'] = trim(Database::clean_string($_POST['name']));

        /* Check for possible errors */
        if(empty($_POST['name'])) {
            $errors[] = $this->language->global->error_message->empty_fields;
        }

        $user_id = 1;

        if(empty($errors)) {

            /* Insert to database */
            $stmt = Database::$database->prepare("INSERT INTO `projects` (`user_id`, `name`, `date`) VALUES (?, ?, ?)");
            $stmt->bind_param('sss', $user_id, $_POST['name'], Date::$date);
            $stmt->execute();
            $project_id = $stmt->insert_id;
            $stmt->close();

            Response::json($this->language->project_create_modal->success_message->created, 'success', ['url' => url('project/' . $project_id)]);

        }
    }

    private function update() {

        $user_id = 1;
        $_POST['project_id'] = (int) $_POST['project_id'];
        $_POST['name'] = trim(Database::clean_string($_POST['name']));

        /* Check for possible errors */
        if(empty($_POST['name'])) {
            $errors[] = $this->language->global->error_message->empty_fields;
        }

        if(empty($errors)) {

            /* Insert to database */
            $stmt = Database::$database->prepare("UPDATE `projects` SET `name` = ? WHERE `project_id` = ? AND `user_id` = ?");
            $stmt->bind_param('sss', $_POST['name'], $_POST['project_id'], $user_id);
            $stmt->execute();
            $stmt->close();

            Response::json($this->language->project_update_modal->success_message->updated, 'success');

        }
    }

    private function delete() {

        $user_id = 1;
        $_POST['project_id'] = (int) $_POST['project_id'];

        /* Check for possible errors */
        if(!Database::exists('project_id', 'projects', ['project_id' => $_POST['project_id']])) {
            $errors[] = true;
        }

        if(empty($errors)) {

            /* Delete from database */
            $stmt = Database::$database->prepare("DELETE FROM `projects` WHERE `project_id` = ? AND `user_id` = ?");
            $stmt->bind_param('ss', $_POST['project_id'], $user_id);
            $stmt->execute();
            $stmt->close();

            Response::json($this->language->project_delete_modal->success_message, 'success');

        }
    }

}
