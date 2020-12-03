<?php

namespace Altum;

class Date {
    public static $date;
    public static $timezone = '';
    public static $default_timezone = 'UTC';

    public static function validate($date, $format = 'Y-m-d') {
        $d = \DateTime::createFromFormat($format, $date);

        return $d && $d->format($format) === $date;
    }

    /* Helper to easily and fast output dates to the screen */
    public static function get($date = '', $format_type = -1, $timezone = '', $expiration_days = '') {

        $timezone = !$timezone ? self::$timezone : $timezone;

        $datetime = (new \DateTime($date))->setTimezone(new \DateTimeZone($timezone));

        /* No format at all */
        if(is_null($format_type)) {
            return $datetime;
        }

        switch($format_type) {

            case $format_type === -1:

                return $datetime->format('Y-m-d H:i:s');

                break;

            case $format_type === 0:
                return sprintf(
                    Language::get()->global->date->datetime_ymd_format,
                    $datetime->format('Y'),
                    $datetime->format('m'),
                    $datetime->format('d')
                );

                break;    

            case $format_type === 1:

                return sprintf(
                    Language::get()->global->date->datetime_ymd_his_format,
                    $datetime->format('Y'),
                    $datetime->format('m'),
                    $datetime->format('d'),
                    $datetime->format('H'),
                    $datetime->format('i'),
                    $datetime->format('s')
                );

                break;

            case $format_type === 2:

                return sprintf(
                    Language::get()->global->date->datetime_readable_format,
                    $datetime->format('j'),
                    Language::get()->global->date->long_months->{$datetime->format('n')},
                    $datetime->format('Y')
                );

                break;

            case $format_type === 3:

                return sprintf(
                    Language::get()->global->date->datetime_his_format,
                    $datetime->format('H'),
                    $datetime->format('i'),
                    $datetime->format('s')
                );

                break;

            case $format_type === 4:

                return sprintf(
                    Language::get()->global->date->datetime_ymd_format,
                    $datetime->format('Y'),
                    $datetime->format('m'),
                    $datetime->format('d')
                );
                break;

            case $format_type === 5:

                $currentDateTime = $datetime->format('Y-m-d H:i:s');
                return date('Y-m-d H:i:s', strtotime('+10 year', strtotime($currentDateTime)));
                break;
            
            case $format_type === 6:

                $currentDateTime = $datetime->format('Y-m-d H:i:s');
                return date('Y-m-d H:i:s', strtotime('+'. $expiration_days. ' day', strtotime($currentDateTime)));
                break;
            
            case $format_type === 7:

                $currentDateTime = $datetime->format('Y-m-d H:i:s');
                return date('Y-m-d H:i:s', strtotime('+1 day', strtotime($currentDateTime)));
                break;

            case $format_type === 8:

                $currentDateTime = $datetime->format('Y-m-d H:i:s');
                return date('Y-m-d H:i:s', strtotime('+1 month', strtotime($currentDateTime)));
                break;
            /* No specific format type */
            default:

                return $datetime->format($format_type);

                break;
        }

    }


    /* Helper to have the timeago from one point to now */
    public static function get_timeago($date) {

        $estimate_time = time() - (new \DateTime($date))->getTimestamp();

        if($estimate_time < 1) {
            return Language::get()->global->date->now;
        }

        $condition = [
            12 * 30 * 24 * 60 * 60  =>  'year',
            30 * 24 * 60 * 60       =>  'month',
            24 * 60 * 60            =>  'day',
            60 * 60                 =>  'hour',
            60                      =>  'minute',
            1                       =>  'second'
        ];

        foreach($condition as $secs => $str) {
            $d = $estimate_time / $secs;

            if($d >= 1) {
                $r = round($d);

                /* Determine the language string needed */
                $language_string_time = $r > 1 ? Language::get()->global->date->{$str . 's'} : Language::get()->global->date->{$str};

                return sprintf(
                    Language::get()->global->date->time_ago,
                    $r,
                    $language_string_time
                );
            }
        }
    }

}
