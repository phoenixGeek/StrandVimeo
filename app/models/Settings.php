<?php

namespace Altum\Models;

use Altum\Database\Database;

class Settings extends Model {

    public function get() {


        $result = Database::$database->query("SELECT * FROM `settings`");
        $data = new \StdClass();

        while($row = $result->fetch_object()) {

            /* Put the value in a variable so we can check if its json or not */
            $value = json_decode($row->value);

            $data->{$row->key} = is_null($value) ? $row->value : $value;

        }

        return $data;
    }

}
