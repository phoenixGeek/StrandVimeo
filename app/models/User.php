<?php

namespace Altum\Models;

use Altum\Database\Database;

class User extends Model {

    public function get($user_id) {

        $data = Database::get('*', 'users', ['user_id' => $user_id]);

        if($data) {

            /* Parse the users package settings */
            $data->package_settings = json_decode($data->package_settings);

        }

        return $data;
    }

}
