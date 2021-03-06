<?php

/* Generate chart data for based on the date key and each of keys inside */
function get_chart_data(Array $main_array) {

    $results = [];

    foreach($main_array as $date_label => $data) {

        foreach($data as $label_key => $label_value) {

            if(!isset($results[$label_key])) {
                $results[$label_key] = [];
            }

            $results[$label_key][] = $label_value;

        }

    }

    foreach($results as $key => $value) {
        $results[$key] = '["' . implode('", "', $value) . '"]';
    }

    $results['labels'] = '["' . implode('", "', array_keys($main_array)) . '"]';

    return $results;
}

function get_gravatar($email, $s = 80, $d = 'mp', $r = 'g', $img = false, $atts = []) {
    $url = 'https://www.gravatar.com/avatar/';
    $url .= md5(strtolower(trim($email)));
    $url .= "?s=$s&d=$d&r=$r";

    if ($img) {
        $url = '<img src="' . $url . '"';

        foreach ($atts as $key => $val) {
            $url .= ' ' . $key . '="' . $val . '"';
        }

        $url .= ' />';
    }

    return $url;
}

function get_admin_options_button($type, $target_id) {

    switch($type) {

        case 'user' :
            return '
                <div class="dropdown">
                    <a href="#" data-toggle="dropdown" class="text-secondary dropdown-toggle dropdown-toggle-simple">
                        <i class="fa fa-fw fa-ellipsis-v"></i>
                        
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="admin/user-view/' . $target_id . '"><i class="fa fa-fw fa-eye"></i> ' . \Altum\Language::get()->global->view . '</a>
                            <a class="dropdown-item" href="admin/user-update/' . $target_id . '"><i class="fa fa-fw fa-pencil-alt"></i> ' . \Altum\Language::get()->global->edit . '</a>
                            <a class="dropdown-item" data-confirm="' . \Altum\Language::get()->global->info_message->confirm_delete . '" href="admin/users/delete/' . $target_id . \Altum\Middlewares\Csrf::get_url_query() . '"><i class="fa fa-fw fa-times"></i> ' . \Altum\Language::get()->global->delete . '</a>
                            <a href="#" data-toggle="modal" data-target="#user_login" data-user-id="' . $target_id . '" class="dropdown-item"><i class="fa fa-fw fa-sign-in-alt"></i> ' . \Altum\Language::get()->global->login . '</a>
                        </div>
                    </a>
                </div>';

            break;


        case 'link' :
            return '
                <div class="dropdown">
                    <a href="#" data-toggle="dropdown" class="text-secondary dropdown-toggle dropdown-toggle-simple">
                        <i class="fa fa-fw fa-ellipsis-v"></i>
                        
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" data-confirm="' . \Altum\Language::get()->global->info_message->confirm_delete . '" href="admin/links/delete/' . $target_id . \Altum\Middlewares\Csrf::get_url_query() . '"><i class="fa fa-fw fa-times"></i> ' . \Altum\Language::get()->global->delete . '</a>
                        </div>
                    </a>
                </div>';

            break;

        case 'domain' :
            return '
                <div class="dropdown">
                    <a href="#" data-toggle="dropdown" class="text-secondary dropdown-toggle dropdown-toggle-simple">
                        <i class="fa fa-fw fa-ellipsis-v"></i>
                        
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="admin/domain-update/' . $target_id . '"><i class="fa fa-fw fa-pencil-alt"></i> ' . \Altum\Language::get()->global->edit . '</a>
                            <a class="dropdown-item" data-confirm="' . \Altum\Language::get()->global->info_message->confirm_delete . '" href="admin/domains/delete/' . $target_id . \Altum\Middlewares\Csrf::get_url_query() . '"><i class="fa fa-fw fa-times"></i> ' . \Altum\Language::get()->global->delete . '</a>
                        </div>
                    </a>
                </div>';

            break;


        case 'pages_category' :
            return '
                <div class="dropdown">
                    <a href="#" data-toggle="dropdown" class="text-secondary dropdown-toggle dropdown-toggle-simple">
                        <i class="fa fa-fw fa-ellipsis-v"></i>
                        
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="admin/pages-category-update/' . $target_id . '"><i class="fa fa-fw fa-pencil-alt"></i> ' . \Altum\Language::get()->global->edit . '</a>
                            <a href="#" data-toggle="modal" data-target="#pages_category_delete" data-pages-category-id="' . $target_id . '" class="dropdown-item"><i class="fa fa-fw fa-times"></i> ' . \Altum\Language::get()->global->delete . '</a>
                        </div>
                    </a>
                </div>';

            break;

        case 'page' :
            return '
                <div class="dropdown">
                    <a href="#" data-toggle="dropdown" class="text-secondary dropdown-toggle dropdown-toggle-simple">
                        <i class="fa fa-fw fa-ellipsis-v"></i>
                        
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="admin/page-update/' . $target_id . '"><i class="fa fa-fw fa-pencil-alt"></i> ' . \Altum\Language::get()->global->edit . '</a>
                            <a href="#" data-toggle="modal" data-target="#page_delete" data-page-id="' . $target_id . '" class="dropdown-item"><i class="fa fa-fw fa-times"></i> ' . \Altum\Language::get()->global->delete . '</a>
                        </div>
                    </a>
                </div>';

            break;

        case 'package' :
            return '
                <div class="dropdown">
                    <a href="#" data-toggle="dropdown" class="text-secondary dropdown-toggle dropdown-toggle-simple">
                        <i class="fa fa-fw fa-ellipsis-v"></i>
                        
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="admin/package-update/' . $target_id . '"><i class="fa fa-fw fa-pencil-alt"></i> ' . \Altum\Language::get()->global->edit . '</a>
                            
                            ' . (is_numeric($target_id) ?
                                '<a class="dropdown-item" data-confirm="' . \Altum\Language::get()->global->info_message->confirm_delete . '" href="admin/packages/delete/' . $target_id . \Altum\Middlewares\Csrf::get_url_query() . '"><i class="fa fa-fw fa-times"></i> ' . \Altum\Language::get()->global->delete . '</a>'
                                : null) . '
                            
                        </div>
                    </a>
                </div>';

            break;

        case 'code' :
            return '
                <div class="dropdown">
                    <a href="#" data-toggle="dropdown" class="text-secondary dropdown-toggle dropdown-toggle-simple">
                        <i class="fa fa-fw fa-ellipsis-v"></i>
                        
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="admin/code-update/' . $target_id . '"><i class="fa fa-fw fa-sm fa-pencil-alt mr-1"></i> ' . \Altum\Language::get()->global->edit . '</a>
                            <a href="#" data-toggle="modal" data-target="#code_delete" data-code-id="' . $target_id . '" class="dropdown-item"><i class="fa fa-fw fa-sm fa-times mr-1"></i> ' . \Altum\Language::get()->global->delete . '</a>
                        </div>
                    </a>
                </div>';

            break;

    }
}

/* Helper to output proper and nice numbers */
function nr($number, $decimals = 0, $extra = false) {

    if($extra) {
        $formatted_number = $number;
        $touched = false;

        if(!$touched && (!is_array($extra) || (is_array($extra) && in_array('B', $extra)))) {

            if($number > 999999999) {
                $formatted_number = number_format($number / 1000000000, $decimals, \Altum\Language::get()->global->number->decimal_point, \Altum\Language::get()->global->number->thousands_separator) . 'B';

                $touched = true;
            }

        }

        if(!$touched && (!is_array($extra) || (is_array($extra) && in_array('M', $extra)))) {

            if($number > 999999) {
                $formatted_number = number_format($number / 1000000, $decimals, \Altum\Language::get()->global->number->decimal_point, \Altum\Language::get()->global->number->thousands_separator) . 'M';

                $touched = true;
            }

        }

        if(!$touched && (!is_array($extra) || (is_array($extra) && in_array('K', $extra)))) {

            if($number > 999) {
                $formatted_number = number_format($number / 1000, $decimals, \Altum\Language::get()->global->number->decimal_point, \Altum\Language::get()->global->number->thousands_separator) . 'K';

                $touched = true;
            }

        }

        if($decimals > 0) {
            $dotzero = '.' . str_repeat('0', $decimals);
            $formatted_number = str_replace($dotzero, '', $formatted_number);
        }

        return $formatted_number;
    }

    if($number == 0) {
        return 0;
    }

    return number_format($number, $decimals, \Altum\Language::get()->global->number->decimal_point, \Altum\Language::get()->global->number->thousands_separator);
}

function get_domain($url) {

    $host = parse_url($url, PHP_URL_HOST);

    $host = explode('.', $host);

    /* Return only the last 2 array values combined */
    return implode('.', array_slice($host, -2, 2));
}

function get_ip() {
    if(array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)) {

        if(strpos($_SERVER['HTTP_X_FORWARDED_FOR'], ',')) {
            $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);

            return trim(reset($ips));
        } else {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        }

    } else if (array_key_exists('REMOTE_ADDR', $_SERVER)) {
        return $_SERVER['REMOTE_ADDR'];
    } else if (array_key_exists('HTTP_CLIENT_IP', $_SERVER)) {
        return $_SERVER['HTTP_CLIENT_IP'];
    }

    return '';
}

function get_device_type($user_agent) {
    $mobile_regex = '/(?:phone|windows\s+phone|ipod|blackberry|(?:android|bb\d+|meego|silk|googlebot) .+? mobile|palm|windows\s+ce|opera mini|avantgo|mobilesafari|docomo)/i';
    $tablet_regex = '/(?:ipad|playbook|(?:android|bb\d+|meego|silk)(?! .+? mobile))/i';

    if(preg_match_all($mobile_regex, $user_agent)) {
        return 'mobile';
    } else {

        if(preg_match_all($tablet_regex, $user_agent)) {
            return 'tablet';
        } else {
            return 'desktop';
        }

    }
}

function get_country_from_country_code($code) {
    $code = strtoupper($code);

    $country_list = [
        'AF' => 'Afghanistan',
        'AX' => 'Aland Islands',
        'AL' => 'Albania',
        'DZ' => 'Algeria',
        'AS' => 'American Samoa',
        'AD' => 'Andorra',
        'AO' => 'Angola',
        'AI' => 'Anguilla',
        'AQ' => 'Antarctica',
        'AG' => 'Antigua and Barbuda',
        'AR' => 'Argentina',
        'AM' => 'Armenia',
        'AW' => 'Aruba',
        'AU' => 'Australia',
        'AT' => 'Austria',
        'AZ' => 'Azerbaijan',
        'BS' => 'Bahamas',
        'BH' => 'Bahrain',
        'BD' => 'Bangladesh',
        'BB' => 'Barbados',
        'BY' => 'Belarus',
        'BE' => 'Belgium',
        'BZ' => 'Belize',
        'BJ' => 'Benin',
        'BM' => 'Bermuda',
        'BT' => 'Bhutan',
        'BO' => 'Bolivia',
        'BQ' => 'Bonaire, Saint Eustatius and Saba',
        'BA' => 'Bosnia and Herzegovina',
        'BW' => 'Botswana',
        'BV' => 'Bouvet Island',
        'BR' => 'Brazil',
        'IO' => 'British Indian Ocean Territory',
        'VG' => 'British Virgin Islands',
        'BN' => 'Brunei',
        'BG' => 'Bulgaria',
        'BF' => 'Burkina Faso',
        'BI' => 'Burundi',
        'KH' => 'Cambodia',
        'CM' => 'Cameroon',
        'CA' => 'Canada',
        'CV' => 'Cape Verde',
        'KY' => 'Cayman Islands',
        'CF' => 'Central African Republic',
        'TD' => 'Chad',
        'CL' => 'Chile',
        'CN' => 'China',
        'CX' => 'Christmas Island',
        'CC' => 'Cocos Islands',
        'CO' => 'Colombia',
        'KM' => 'Comoros',
        'CK' => 'Cook Islands',
        'CR' => 'Costa Rica',
        'HR' => 'Croatia',
        'CU' => 'Cuba',
        'CW' => 'Curacao',
        'CY' => 'Cyprus',
        'CZ' => 'Czech Republic',
        'CD' => 'Democratic Republic of the Congo',
        'DK' => 'Denmark',
        'DJ' => 'Djibouti',
        'DM' => 'Dominica',
        'DO' => 'Dominican Republic',
        'TL' => 'East Timor',
        'EC' => 'Ecuador',
        'EG' => 'Egypt',
        'SV' => 'El Salvador',
        'GQ' => 'Equatorial Guinea',
        'ER' => 'Eritrea',
        'EE' => 'Estonia',
        'ET' => 'Ethiopia',
        'FK' => 'Falkland Islands',
        'FO' => 'Faroe Islands',
        'FJ' => 'Fiji',
        'FI' => 'Finland',
        'FR' => 'France',
        'GF' => 'French Guiana',
        'PF' => 'French Polynesia',
        'TF' => 'French Southern Territories',
        'GA' => 'Gabon',
        'GM' => 'Gambia',
        'GE' => 'Georgia',
        'DE' => 'Germany',
        'GH' => 'Ghana',
        'GI' => 'Gibraltar',
        'GR' => 'Greece',
        'GL' => 'Greenland',
        'GD' => 'Grenada',
        'GP' => 'Guadeloupe',
        'GU' => 'Guam',
        'GT' => 'Guatemala',
        'GG' => 'Guernsey',
        'GN' => 'Guinea',
        'GW' => 'Guinea-Bissau',
        'GY' => 'Guyana',
        'HT' => 'Haiti',
        'HM' => 'Heard Island and McDonald Islands',
        'HN' => 'Honduras',
        'HK' => 'Hong Kong',
        'HU' => 'Hungary',
        'IS' => 'Iceland',
        'IN' => 'India',
        'ID' => 'Indonesia',
        'IR' => 'Iran',
        'IQ' => 'Iraq',
        'IE' => 'Ireland',
        'IM' => 'Isle of Man',
        'IL' => 'Israel',
        'IT' => 'Italy',
        'CI' => 'Ivory Coast',
        'JM' => 'Jamaica',
        'JP' => 'Japan',
        'JE' => 'Jersey',
        'JO' => 'Jordan',
        'KZ' => 'Kazakhstan',
        'KE' => 'Kenya',
        'KI' => 'Kiribati',
        'XK' => 'Kosovo',
        'KW' => 'Kuwait',
        'KG' => 'Kyrgyzstan',
        'LA' => 'Laos',
        'LV' => 'Latvia',
        'LB' => 'Lebanon',
        'LS' => 'Lesotho',
        'LR' => 'Liberia',
        'LY' => 'Libya',
        'LI' => 'Liechtenstein',
        'LT' => 'Lithuania',
        'LU' => 'Luxembourg',
        'MO' => 'Macao',
        'MK' => 'Macedonia',
        'MG' => 'Madagascar',
        'MW' => 'Malawi',
        'MY' => 'Malaysia',
        'MV' => 'Maldives',
        'ML' => 'Mali',
        'MT' => 'Malta',
        'MH' => 'Marshall Islands',
        'MQ' => 'Martinique',
        'MR' => 'Mauritania',
        'MU' => 'Mauritius',
        'YT' => 'Mayotte',
        'MX' => 'Mexico',
        'FM' => 'Micronesia',
        'MD' => 'Moldova',
        'MC' => 'Monaco',
        'MN' => 'Mongolia',
        'ME' => 'Montenegro',
        'MS' => 'Montserrat',
        'MA' => 'Morocco',
        'MZ' => 'Mozambique',
        'MM' => 'Myanmar',
        'NA' => 'Namibia',
        'NR' => 'Nauru',
        'NP' => 'Nepal',
        'NL' => 'Netherlands',
        'NC' => 'New Caledonia',
        'NZ' => 'New Zealand',
        'NI' => 'Nicaragua',
        'NE' => 'Niger',
        'NG' => 'Nigeria',
        'NU' => 'Niue',
        'NF' => 'Norfolk Island',
        'KP' => 'North Korea',
        'MP' => 'Northern Mariana Islands',
        'NO' => 'Norway',
        'OM' => 'Oman',
        'PK' => 'Pakistan',
        'PW' => 'Palau',
        'PS' => 'Palestinian Territory',
        'PA' => 'Panama',
        'PG' => 'Papua New Guinea',
        'PY' => 'Paraguay',
        'PE' => 'Peru',
        'PH' => 'Philippines',
        'PN' => 'Pitcairn',
        'PL' => 'Poland',
        'PT' => 'Portugal',
        'PR' => 'Puerto Rico',
        'QA' => 'Qatar',
        'CG' => 'Republic of the Congo',
        'RE' => 'Reunion',
        'RO' => 'Romania',
        'RU' => 'Russia',
        'RW' => 'Rwanda',
        'BL' => 'Saint Barthelemy',
        'SH' => 'Saint Helena',
        'KN' => 'Saint Kitts and Nevis',
        'LC' => 'Saint Lucia',
        'MF' => 'Saint Martin',
        'PM' => 'Saint Pierre and Miquelon',
        'VC' => 'Saint Vincent and the Grenadines',
        'WS' => 'Samoa',
        'SM' => 'San Marino',
        'ST' => 'Sao Tome and Principe',
        'SA' => 'Saudi Arabia',
        'SN' => 'Senegal',
        'RS' => 'Serbia',
        'SC' => 'Seychelles',
        'SL' => 'Sierra Leone',
        'SG' => 'Singapore',
        'SX' => 'Sint Maarten',
        'SK' => 'Slovakia',
        'SI' => 'Slovenia',
        'SB' => 'Solomon Islands',
        'SO' => 'Somalia',
        'ZA' => 'South Africa',
        'GS' => 'South Georgia and the South Sandwich Islands',
        'KR' => 'South Korea',
        'SS' => 'South Sudan',
        'ES' => 'Spain',
        'LK' => 'Sri Lanka',
        'SD' => 'Sudan',
        'SR' => 'Suriname',
        'SJ' => 'Svalbard and Jan Mayen',
        'SZ' => 'Swaziland',
        'SE' => 'Sweden',
        'CH' => 'Switzerland',
        'SY' => 'Syria',
        'TW' => 'Taiwan',
        'TJ' => 'Tajikistan',
        'TZ' => 'Tanzania',
        'TH' => 'Thailand',
        'TG' => 'Togo',
        'TK' => 'Tokelau',
        'TO' => 'Tonga',
        'TT' => 'Trinidad and Tobago',
        'TN' => 'Tunisia',
        'TR' => 'Turkey',
        'TM' => 'Turkmenistan',
        'TC' => 'Turks and Caicos Islands',
        'TV' => 'Tuvalu',
        'VI' => 'U.S. Virgin Islands',
        'UG' => 'Uganda',
        'UA' => 'Ukraine',
        'AE' => 'United Arab Emirates',
        'GB' => 'United Kingdom',
        'US' => 'United States',
        'UM' => 'United States Minor Outlying Islands',
        'UY' => 'Uruguay',
        'UZ' => 'Uzbekistan',
        'VU' => 'Vanuatu',
        'VA' => 'Vatican',
        'VE' => 'Venezuela',
        'VN' => 'Vietnam',
        'WF' => 'Wallis and Futuna',
        'EH' => 'Western Sahara',
        'YE' => 'Yemen',
        'ZM' => 'Zambia',
        'ZW' => 'Zimbabwe',
    ];

    if(!isset($country_list[$code])) {
        return $code;
    } else {
        return $country_list[$code];
    }
}

/* Dump & die */
function dd($string = null) {
    var_dump($string);
    die();
}

/* Output in debug.log file */
function dl($string = null) {
    ob_start();

    print_r($string);

    $content = ob_get_clean();

    error_log($content);
}
