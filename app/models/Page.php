<?php

namespace Altum\Models;

use Altum\Database\Database;

class Page extends Model {

    public function get_pages($position) {

        $data = [];

        $result = Database::$database->query("SELECT `url`, `title`, `type` FROM `pages` WHERE `position` = '{$position}'");

        while($row = $result->fetch_object()) {

            if($row->type == 'internal') {

                $row->target = '_self';
                $row->url = url('page/' . $row->url);

            } else {

                $row->target = '_blank';

            }

            $data[] = $row;
        }

        return $data;
    }

}
