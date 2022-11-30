<?php

declare(strict_types=1);

/*
'########:'##::::'##::'######::'##::::'##::'######:::'#######::'########::'########:
... ##..:: ##:::: ##:'##... ##: ##:::: ##:'##... ##:'##.... ##: ##.... ##: ##.....::
::: ##:::: ##:::: ##: ##:::..:: ##:::: ##: ##:::..:: ##:::: ##: ##:::: ##: ##:::::::
::: ##:::: ##:::: ##:. ######:: #########: ##::::::: ##:::: ##: ##:::: ##: ######:::
::: ##:::: ##:::: ##::..... ##: ##.... ##: ##::::::: ##:::: ##: ##:::: ##: ##...::::
::: ##:::: ##:::: ##:'##::: ##: ##:::: ##: ##::: ##: ##:::: ##: ##:::: ##: ##:::::::
::: ##::::. #######::. ######:: ##:::: ##:. ######::. #######:: ########:: ########:
:::..::::::.......::::......:::..:::::..:::......::::.......:::........:::........::                                                                                   
*/

// Require namespace for PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * Return true if the request method is POST
 *
 * @return boolean
 */
function is_post_request(): bool
{
    return strtoupper($_SERVER['REQUEST_METHOD']) === 'POST';
}

/**
 * Return true if the request method is GET
 *
 * @return boolean
 */
function is_get_request(): bool
{
    return strtoupper($_SERVER['REQUEST_METHOD']) === 'GET';
}

function currentURL()
{
    return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
}

function maintenance_mode($duration)
{
    $html =
        '<!DOCTYPE html>
    <html xmlns="http://www.w3.org/1999/xhtml" dir="ltr">
      <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Unavailable for Scheduled Maintenance</title>
        <style type="text/css">
          html {
            background: #f1f1f1;
          }
          body {
            background: #fff;
            color: #444;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
            margin: 2em auto;
            padding: 1em 2em;
            max-width: 700px;
            -webkit-box-shadow: 0 1px 3px rgba(0, 0, 0, 0.13);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.13);
          }
          h1 {
            border-bottom: 1px solid #fe2d2d;
            clear: both;
            color: #666;
            font-size: 24px;
            margin: 30px 0 0 0;
            padding: 0;
            padding-bottom: 7px;
          }
          #error-page {
            margin-top: 50px;
          }
          #error-page p,
          #error-page .wp-die-message {
            font-size: 14px;
            line-height: 1.5;
            margin: 25px 0 20px;
          }
          #error-page code {
            font-family: Consolas, Monaco, monospace;
          }
          ul li {
            margin-bottom: 10px;
            font-size: 14px ;
          }
          a {
            color: #0073aa;
          }
          a:hover,
          a:active {
            color: #00a0d2;
          }
          a:focus {
            color: #124964;
            -webkit-box-shadow:
            0 0 0 1px #5b9dd9,
            0 0 2px 1px rgba(30, 140, 190, 0.8);
            box-shadow:
            0 0 0 1px #5b9dd9,
            0 0 2px 1px rgba(30, 140, 190, 0.8);
            outline: none;
          }
        </style>
      </head>
      <body id="error-page">
        <div class="site-message">
          <h1>Temporarily Unavailable For Maintenance</h1>
          <p>Hey there👋<br>Due to <strong>scheduled maintenance</strong>, our website will be down temporarily. Less than ' .
        $duration .
        ' of downtime are scheduled. Please contact  <a href="mailto:' .
        CONFIG['APP']['support_mail'] .
        '">Our Support</a> for additional questions and information.</p>
          <p>Thank you for your patience!<br><a href="' .
        URLROOT .
        '" target="_blank">' .
        CONFIG['APP']['support_team'] .
        '</a></p>
        </div>
      </body>
    </html>';
    echo $html;
}

function AB($str)
{
    $words = explode(" ", $str);
    $acronym = "";
    foreach ($words as $w) {
        $acronym .= $w[0];
    }
    return $acronym;
}

function randName($string_name, $rand_no = 100)
{
    $username_parts = array_filter(explode(" ", strtolower($string_name))); //explode and lowercase name
    $username_parts = array_slice($username_parts, 0, 2); //return only first two arry part

    $part1 = !empty($username_parts[0]) ? substr($username_parts[0], 0, 8) : ""; //cut first name to 8 letters
    $part2 = !empty($username_parts[1]) ? substr($username_parts[1], 0, 5) : ""; //cut second name to 5 letters
    $part3 = $rand_no ? rand(0, $rand_no) : "";

    $username = $part1 . str_shuffle($part2) . $part3; //str_shuffle to randomly shuffle all characters
    return $username;
}

function realTxt($body, $array)
{
    return strtr($body, $array);
}

function Redirect($url, $permanent = false)
{
    header('Location: ' . $url, true, $permanent ? 301 : 302);
    exit();
}

function copyright($year = false)
{
    if ($year == false) {
        $year = date('Y');
    }
    if (intval($year) == date('Y')) {
        return intval($year);
    }
    if (intval($year) < date('Y')) {
        return intval($year) . ' - ' . date('Y');
    }
    if (intval($year) > date('Y')) {
        return date('Y');
    }
}

function readingTime($content)
{
    $words = count(preg_split('~[^\p{L}\p{N}\']+~u', $content));
    $minutes = floor($words / 200);
    $seconds = floor(($words % 200) / (200 / 60));
    return $minutes . ' minute' . ($minutes == 1 ? '' : 's') . ', ' . $seconds . ' second' . ($seconds == 1 ? '' : 's');
}

function executedTime($time_start)
{
    $time_end = microtime(true);
    $execution_time = $time_end - $time_start;
    return number_format((int) $execution_time, 3) . ' seconds';
}

function calcDistance($departureLatitude, $departureLongitude, $arrivalLatitude, $arrivalLongitude)
{
    $a1 = deg2rad($arrivalLatitude);
    $b1 = deg2rad($arrivalLongitude);
    $a2 = deg2rad($departureLatitude);
    $b2 = deg2rad($departureLongitude);
    $res = 2 * asin(sqrt(pow(sin(($a2 - $a1) / 2), 2) + cos($a2) * cos($a1) * pow(sin(($b2 - $b1) / 2), 2)));
    return 6371008 * $res;
}

function csv_download($fields, $array, $filename = "export.csv", $delimiter = ",")
{
    $f = fopen('php://memory', 'w');
    fputcsv($f, $fields);
    foreach ($array as $line) {
        fputcsv($f, $line, $delimiter);
    }
    fseek($f, 0);
    header('Content-Encoding: UTF-8');
    header("Content-Transfer-Encoding: UTF-8");
    header('Content-type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="' . $filename . '.csv";');
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    fpassthru($f);
    fclose($f);
}

function csv_upload($FILE)
{
    $file_mimes = [
        'text/x-comma-separated-values',
        'text/comma-separated-values',
        'application/octet-stream',
        'application/vnd.ms-excel',
        'application/x-csv',
        'text/x-csv',
        'text/csv',
        'application/csv',
        'application/excel',
        'application/vnd.msexcel',
        'text/plain',
    ];
    if (!empty($_FILES[$FILE]['name']) && in_array($_FILES[$FILE]['type'], $file_mimes)) {
        if (is_uploaded_file($_FILES[$FILE]['tmp_name'])) {
            $csv_file = fopen($_FILES[$FILE]['tmp_name'], 'r');
            while (($getData = fgetcsv($csv_file)) !== false) {
                $imports[] = $getData;
            }
            fclose($csv_file);
            return $imports;
        }
    } else {
        return false;
    }
}

function gen_uuid()
{
    return strtoupper(
        sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            // 32 bits for "time_low"
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),

            // 16 bits for "time_mid"
            mt_rand(0, 0xffff),

            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand(0, 0x0fff) | 0x4000,

            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand(0, 0x3fff) | 0x8000,

            // 48 bits for "node"
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        )
    );
}

function isSecure()
{
    if (!empty($_SERVER['https'])) {
        return true;
    }

    if (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
        return true;
    }

    return false;
}

function paginate_range($total_page, $current_page)
{
    /* Set subgroup page start: */
    if ($total_page < 6) {
        $start = 2;
    } elseif ($current_page < 3) {
        $start = 2;
    } elseif ($current_page > $total_page - 3) {
        $start = $total_page - 3;
    } else {
        $start = $current_page - 1;
    }

    /* Page 1 (always):                                   */
    $array_output = array_merge([], [1]);
    /* Initial separator:                                 */
    if ($start > 2) {
        $array_output = array_merge($array_output, [0]);
    }
    /* Page subgroup: ends if reached +2 or pages-1       */
    for ($i = $start; $i < $total_page; $i++) {
        $array_output = array_merge($array_output, [$i]);
        if ($i > $start + 1) {
            break;
        }
    }
    /* Final separator:                                   */
    if ($start < $total_page - 3) {
        $array_output = array_merge($array_output, [0]);
    }

    /* Last page:                                         */
    if ($total_page > 1) {
        $array_output = array_merge($array_output, [$total_page]);
    }

    /* Output:                                            */
    return $array_output;
}


function invoiceGen($input, $pad_len = 7, $prefix = null)
{
    if ($pad_len <= strlen($input)) {
        trigger_error('<strong>$pad_len</strong> cannot be less than or equal to the length of <strong>$input</strong> to generate invoice number', E_USER_ERROR);
    }
    if (is_string($prefix)) {
        return sprintf("%s%s", $prefix, str_pad($input, $pad_len, "0", STR_PAD_LEFT));
    }
    return str_pad($input, $pad_len, "0", STR_PAD_LEFT);
}

function json_output($data = [])
{
    header('Content-Type: application/json');
    echo json_encode($data, JSON_PRETTY_PRINT);
    exit();
}

function clear_cache()
{
    header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
}

function uncomma($text)
{
    $text = str_replace(",", "", $text);
    return $text;
}

function swap($text, $symbol, $to)
{
    $text = str_replace($symbol, $to, $text);
    return $text;
}

function dash($text)
{
    $text = str_replace(" ", "-", $text);
    return $text;
}

function undash($text)
{
    $text = str_replace("-", " ", $text);
    return $text;
}

/**
 * Converts multidimensional arrays to single array line
 * @param array $array
 *
 * @return [type]
 */
function singleArray(array $array)
{
    if (!is_array($array)) {
        return false;
    }
    $result = [];
    foreach ($array as $key => $value) {
        if (is_array($value)) {
            $result = array_merge($result, singleArray($value));
        } else {
            $result[$key] = $value;
        }
    }
    return $result;
}

function arrK(array $arr, $value)
{
    $items = [];
    foreach ($arr as $key => $val) {
        if ($value == $val) {
            array_push($items, $key);
        }
    }
    return $items;
}

/**
 * Checks if array any keys exists
 * @param array $check
 * @param array $all
 *
 * @return [type]
 */
function arrContain(array $check, array $all)
{
    return count(array_intersect($check, $all)) === count($check);
}

function arrRemove(array $items, array $all)
{
    return array_diff($all, $items);
}

function arrAny(array $items, array $all)
{
    return !empty(array_intersect($items, $all));
}

function xArray($arr, $pos)
{
    unset($arr[$pos]);
}

function multiKey($arrays, $ks)
{
    $GLOBALS['ks'] = $ks;
    return array_map(function ($ar) {
        return $ar[$GLOBALS['ks']];
    }, $arrays);
}

/**
 * Remove duplicates from multidimensional array values by key
 * @param array $array
 * @param string $key
 *
 * @return [type]
 */

function unique_multi_array(array $array, string $key)
{
    $temp_array = [];
    $i = 0;
    $key_array = [];
    foreach ($array as $val) {
        foreach ($val as $key1 => $value1) {
            if (!in_array($value1[$key], $key_array)) {
                // will check if already in array
                $key_array[$i] = $value1[$key]; // once added then wont be added again
                $temp_array[$i] = $value1; // result array
            }
            $i++;
        }
    }
    $ret[] = $temp_array;
    return $ret;
}

/**
 * Remove duplicates from non multidimensional array values by key
 * @param array $array
 * @param string $key
 *
 * @return array
 */
function unique_single_array(array $array, string $key): array
{
    $temp_array = [];
    $i = 0;
    $key_array = [];

    foreach ($array as $val) {
        if (!in_array($val[$key], $key_array)) {
            $key_array[$i] = $val[$key];
            $temp_array[$i] = $val;
        }
        $i++;
    }
    return $temp_array;
}

function rowInCol(array $_arr, $_key, $_val)
{
    $keys = array_keys(array_column($_arr, $_key), $_val);
    return $new_array = array_map(function ($k) use ($_arr) {
        return $_arr[$k];
    }, $keys);
}

function find_key_value(array $array, string $key, string $val)
{
    foreach ($array as $item) {
        if (is_array($item) && find_key_value($item, $key, $val)) {
            return $item;
        }

        if (isset($item[$key]) && $item[$key] == $val) {
            return $item;
        }
    }

    return false;
}

function unsetArray(array $ARR, $keys)
{
    $GLOBALS['keys'] = $keys;
    return array_map(function (array $elem) {
        foreach ($GLOBALS['keys'] as $rmKey) {
            unset($elem[$rmKey]);
        }
        return $elem;
    }, $ARR);
}

function matchKeys($keys, $arr)
{
    $match_items = [];
    foreach ($arr as $match_keys => $match_val) {
        if (in_array($match_keys, $keys)) {
            array_push($match_items, $match_val);
        }
    }
    return $match_items;
}

function _liTree($tree, $att = '', $type = '<ul>')
{
    if (!empty($att)) {
        $st1 = '<' . $att . '>';
        $st2 = '</' . $att . '>';
    } else {
        $st1 = '';
        $st2 = '';
    }
    $html = $type . PHP_EOL;
    foreach ($tree as $item) {
        if (is_array($item)) {
            $html .= _liTree($item);
        } else {
            $html .= '<li>' . $st1 . $item . $st2 . '</li>' . PHP_EOL;
        }
    }
    return $html .= '</ul>' . PHP_EOL;
}

/**
 * Limits string text to certain number
 * @param string $text
 * @param int $chars_limit
 *
 * @return string
 */
function limit(string $text, int $chars_limit): string
{
    if (strlen($text) > $chars_limit) {
        $new_text = substr($text, 0, $chars_limit);
        $new_text = trim($new_text);
        return $new_text . "...";
    } else {
        return $text;
    }
}

function insertAtPosition($string, $insert, $position) {
    return substr_replace($string, $insert, $position, 0);
}

function removeAtPosition($string, $insert, $position) {
    return substr_replace($string, $insert, $position, 0);
}

function clean_input($input)
{
    $search = [
        '@<script[^>]*?>.*?</script>@si', // Strip out javascript
        '@<[\/\!]*?[^<>]*?>@si', // Strip out HTML tags
        '@<style[^>]*?>.*?</style>@siU', // Strip style tags properly
        '@<![\s\S]*?--[ \t\n\r]*>@', // Strip multi-line comments
    ];

    $output = preg_replace($search, '', $input);
    return $output;
}

function sanitize($input)
{
    if (is_array($input)) {
        foreach ($input as $var => $val) {
            $output[$var] = sanitize($val);
        }
    } else {
        $input = str_replace('"', "", $input);
        $input = str_replace("'", "", $input);
        $input = clean_input($input);
        $output = htmlentities($input, ENT_QUOTES);
    }
    return @$output;
}

function _script($src, $attr = "")
{
    return "<script src='" . $src . "' " . $attr . "></script>" . PHP_EOL;
}

function _style($src, $attr = "")
{
    return "<link " . $attr . " rel='stylesheet' href='" . $src . "' />" . PHP_EOL;
}

function _NA($str)
{
    if (empty($str)) {
        return "N/A";
    } else {
        return $str;
    }
}

function daysPercent($start, $end){
    $start = strtotime($start);
    $end = strtotime($end);
    $current = strtotime(DATENOW);
    $completed = (($current - $start) / ($end - $start)) * 100;
    return $completed;
}

function PercentDiff($oldFigure, $newFigure) {
    if (($oldFigure != 0) && ($newFigure != 0)) {
        $percentChange = (1 - $oldFigure / $newFigure) * 100;
    }
    else {
        $percentChange = null;
    }
    return $percentChange;
}

function DFormat($datetime, $is_short = false)
{   
    if($is_short == false){
        return date('D, M jS Y', strtotime($datetime));
    }else{
        return date('M j Y', strtotime($datetime));
    }
}

function DTFormat($datetime)
{
    if ($datetime == "") {
        return "";
    } else {
        return date('D, M jS Y h:i A', strtotime($datetime));
    }
}

function TFormat($time)
{
    return date('h:i A', strtotime($time));
}

function switchDate($datetime, $format)
{
    return date($format, strtotime($datetime));
}

function addDate($dur, $num)
{
    return date("Y-m-d H:i:s", strtotime("+" . $num . " " . $dur));
}

function extraDate($date, $dur, $num)
{
    return date("Y-m-d H:i:s", strtotime("+" . $num . " " . $dur, strtotime($date)));
}

function setDateTime($time, $duration)
{
    return date("Y-m-d H:i:s", strtotime(date("Y-m-d") . ' ' . $time . ' ' . $duration));
}

function passDate($datetime)
{
    if (new DateTime() >= new DateTime($datetime)) {
        return true;
    } else {
        return false;
    }
}

function passTime($tim)
{
    $is_passed = false;
    if (strtotime(date("Y-m-d H:i:s")) > strtotime($tim)) {
        $is_passed = true;
    }
    return $is_passed;
}

function getAge($dob)
{
    $today = date("Y-m-d");
    $diff = date_diff(date_create($dob), date_create($today));
    return $diff->format('%y');
}

/**
 * Number of days between two dates.
 *
 * @param date $dt1    First date
 * @param date $dt2    Second date
 * @return int
 */
function daysBetween($dt1, $dt2 = DATENOW) {
    return date_diff( date_create((string) $dt2),  date_create((string) $dt1))->format('%a');
}

function monthName($monthNumber)
{
    $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    return $months[$monthNumber - 1];
}

//PHP Calculating Days In A Month
function days_in_month($month, $year = null)
{
    $year = empty($year) ? date('Y') : $year;
    return $month == 2 ? ($year % 4 ? 28 : ($year % 100 ? 29 : ($year % 400 ? 28 : 29))) : ((($month - 1) % 7) % 2 ? 30 : 31);
}

// CHECK IF DATE IS WEEKEND
function isWeekend($date)
{
    $weekDay = date('w', strtotime($date));
    return $weekDay == 0 || $weekDay == 6;
}

function downloadFile($file, $name)
{
    $file_name = $file;
    $mime = 'application/force-download';
    header('Pragma: public'); // required
    header('Expires: 0'); // no cache
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Cache-Control: private', false);
    header('Content-Type: ' . $mime);
    header('Content-Disposition: attachment; filename="' . empty($name) ? basename($file_name) : $name . '"');
    header('Content-Transfer-Encoding: binary');
    header('Connection: close');
    readfile($file_name);
    exit();
}

function next_month($months)
{
    if ($months == 0) {
        return false;
    }

    $today = date('Y-m-d');
    $next_due_date = strtotime($today . ' + ' . $months . ' Months');
    return date('Y-m-d', $next_due_date);
}

function slugify($string)
{
    //Lower case everything
    $string = strtolower($string);
    $string = str_replace('&amp;', '-', $string);
    $string = str_replace('&', '-', $string);
    $string = str_replace('And', '-', $string);
    $string = str_replace('and', '-', $string);
    $string = preg_replace("/[\s-]+/", " ", $string);
    $string = preg_replace("/[\s_]/", "-", $string);
    $string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
    return $string;
}

function minifyHTML($Html)
{
    $Search = [
        '/(\n|^)(\x20+|\t)/',
        '/(\n|^)\/\/(.*?)(\n|$)/',
        '/\n/',
        '/\<\!--.*?-->/',
        '/(\x20+|\t)/', # Delete multispace (Without \n)
        '/\>\s+\</', # strip whitespaces between tags
        '/(\"|\')\s+\>/', # strip whitespaces between quotation ("') and end tags
        '/=\s+(\"|\')/',
    ]; # strip whitespaces between = "'
    $Replace = ["\n", "\n", " ", "", " ", "><", "$1>", "=$1"];

    $Html = preg_replace($Search, $Replace, $Html);
    return $Html;
}

function maskEmail(string $email, $char_shown_back = 2)
{
    $mail_parts = explode("@", $email);
    $length = strlen($mail_parts[0]);
    $show = floor($length / 2);
    $hide = $length - $show;
    $replace = str_repeat("*", (int) $hide);
    return substr_replace($mail_parts[0], $replace, (int) $show, (int) $hide) . "@" . substr_replace($mail_parts[1], "**", 0, $char_shown_back);
}

function baseurl($url)
{
    $http_scheme = parse_url($url, PHP_URL_SCHEME);
    $http_host = parse_url($url, PHP_URL_HOST);
    return $http_scheme . '://' . $http_host;
}

function position($number)
{
    $ends = ['th', 'st', 'nd', 'rd', 'th'];
    if ($number % 100 >= 11 && $number % 100 <= 13) {
        return $number . 'th';
    } else {
        return $number . $ends[$number % 10];
    }
}

function timeago($time_ago)
{
    $time_ago = strtotime($time_ago);
    $cur_time = time();
    $time_elapsed = $cur_time - $time_ago;
    $seconds = $time_elapsed;
    $minutes = round($time_elapsed / 60);
    $hours = round($time_elapsed / 3600);
    $days = round($time_elapsed / 86400);
    $weeks = round($time_elapsed / 604800);
    $months = round($time_elapsed / 2600640);
    $years = round($time_elapsed / 31207680);
    // Seconds
    if ($seconds <= 60) {
        return "just now";
    }
    //Minutes
    elseif ($minutes <= 60) {
        if ($minutes == 1) {
            return "1 minute ago";
        } else {
            return "$minutes mins ago";
        }
    }
    //Hours
    elseif ($hours <= 24) {
        if ($hours == 1) {
            return "1 hour ago";
        } else {
            return "$hours hours ago";
        }
    }
    //Days
    elseif ($days <= 7) {
        if ($days == 1) {
            return "yesterday";
        } else {
            return "$days days ago";
        }
    }
    //Weeks
    elseif ($weeks <= 4.3) {
        if ($weeks == 1) {
            return "1 week ago";
        } else {
            return "$weeks weeks ago";
        }
    }
    //Months
    elseif ($months <= 12) {
        if ($months == 1) {
            return "1 mo ago";
        } else {
            return "$months months ago";
        }
    }
    //Years
    else {
        if ($years == 1) {
            return "1 yr ago";
        } else {
            return "$years yrs ago";
        }
    }
}

function paginate($item_per_page, $current_page, $total_records, $page_url)
{
    $pagination = '';
    $total_pages = ceil($total_records / $item_per_page);
    if ($total_pages > 0 && $total_pages != 1 && $current_page <= $total_pages) {
        //verify total pages and current page number
        $right_links = $current_page + 5;
        $previous = $current_page - 5; //previous link
        $next = $current_page + 1; //next link
        $first_link = true; //boolean var to decide our first link

        if ($current_page > 1) {
            $previous_link = $previous == 0 ? 1 : $previous;
            $pagination .= '<li class="page-item"><a class="page-link" href="' . $page_url . 'page=1" title="First">First</a></li>'; //first link
            $pagination .= '<li class="page-item"><a class="page-link" href="' . $page_url . 'page=' . $previous_link . '" title="Previous">Prev</a></li>'; //previous link
            for ($i = $current_page - 2; $i < $current_page; $i++) {
                //Create left-hand side links
                if ($i > 0) {
                    $pagination .= '<li class="page-item"><a class="page-link" href="' . $page_url . 'page=' . $i . '">' . $i . '</a></li>';
                }
            }
            $first_link = false; //set first link to false
        }

        if ($first_link) {
            //if current active page is first link
            $pagination .= '<li class="page-item active"><a class="page-link" href="#!">' . $current_page . '</a></li>';
        } elseif ($current_page == $total_pages) {
            //if it's the last active link
            $pagination .= '<li class="page-item active"><a class="page-link" href="#!">' . $current_page . '</a></li>';
        } else {
            //regular current link
            $pagination .= '<li class="page-item active"><a class="page-link" href="#!">' . $current_page . '</a></li>';
        }

        for ($i = $current_page + 1; $i < $right_links; $i++) {
            //create right-hand side links
            if ($i <= $total_pages) {
                $pagination .= '<li class="page-item"><a class="page-link" href="' . $page_url . 'page=' . $i . '">' . $i . '</a></li>';
            }
        }
        if ($current_page < $total_pages) {
            $next_link = $i > $total_pages ? $total_pages : $i;
            $pagination .= '<li class="page-item"><a class="page-link" href="' . $page_url . 'page=' . $next_link . '" >Next</a></li>'; //next link
            $pagination .= '<li class="page-item"><a class="page-link" href="' . $page_url . 'page=' . $total_pages . '" title="Last">Last</a></li>'; //last link
        }
    }
    return $pagination; //return pagination links
}

function curl_get($url, $headers = ['Content-Type:application/json'])
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_TIMEOUT, 0);
    curl_setopt($curl, CURLOPT_ENCODING, 'gzip,deflate');
    curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; tush-device/2.0 http://www.bing.com/ping-ip.htm)');
    curl_setopt($curl, CURLOPT_REFERER, "https://google.com");
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    if (count($headers) > 0) {
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    }
    $result = curl_exec($curl);
    if (curl_errno($curl)) {
        return json_encode(curl_error($curl));
    }
    curl_close($curl);
    return json_decode($result, true);
}

function curl_post($url, $post_vars = [], $is_query = false, $headers = ['Content-Type:application/json'])
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, true);
    if ($is_query === true) {
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post_vars));
    } else {
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($post_vars));
    }
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    //curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    //curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
    curl_setopt($curl, CURLOPT_TIMEOUT, 0);
    curl_setopt($curl, CURLOPT_ENCODING, "UTF-8");
    curl_setopt($curl, CURLOPT_COOKIE, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    if (count($headers) > 0) {
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    }
    $result = curl_exec($curl);
    if (curl_errno($curl)) {
        return json_encode(curl_error($curl));
    }
    curl_close($curl);
    return json_decode($result, true);
}

function debug_code($code)
{
    echo "<pre>";
    var_dump($code);
    echo "</pre>";
}

/**
 * Dynamic SEO meta tage with facebook and twitter
 */
class MetaTags
{
    /**
     * Generated tags
     * @var array
     */

    public $meta;
    public $sitename, $title, $url, $description;

    public function __construct($title, $url, $description, $sitename)
    {
        $this->title = $title;
        $this->url = $url;
        $this->description = $description;
        $this->sitename = $sitename;

        $this->meta .= '<meta http-equiv="content-language" content="en-us" />' . "\n";
        $this->meta .= '<meta name="format-detection" content="telephone=no">' . "\n";
        $this->meta .= '<meta name= "revisit-after" content="2 days">' . "\n";
        $this->meta .= '<meta name="apple-mobile-web-app-title" content="' . $this->title . '">' . "\n";
        $this->meta .= '<meta name="application-name" content="' . $this->title . '">' . "\n";
        $this->meta .= '<meta name="mobile-web-app-capable" content="yes">' . "\n";
        $this->meta .= '<meta name="apple-mobile-web-app-capable" content="yes">' . "\n";
        $this->meta .= '<meta name="title" content="' . $this->title . '">' . "\n";
        $this->meta .= '<meta name="description" content="' . $this->description . '">' . "\n";
        $this->meta .= '<meta name="author" content="' . $this->sitename . '">' . "\n";
        $this->meta .= '<meta name="publisher" content="' . $this->sitename . '">' . "\n";
        $this->meta .= '<meta name="msapplication-starturl" content="' . baseurl($url) . '">' . "\n";
        $this->meta .= '<base href="' . baseurl($url) . '">' . "\n";
        $this->meta .= '<link rel="canonical" href="' . $url . '" />' . "\n";
    }

    public function keywords($keyword)
    {
        $this->meta .= !empty($keyword) ? '<meta name="keywords" content="' . $keyword . '" />' . "\n" : null;
    }

    public function copyright($copyright)
    {
        $this->meta .= !empty($copyright) ? '<meta name="copyright" content="' . $copyright . '" />' . "\n" : null;
    }

    public function contact($contact)
    {
        $this->meta .= !empty($contact) ? '<meta name="contact" content="' . $contact . '" />' . "\n" : null;
    }

    public function googleCode($google_code)
    {
        $this->meta .= !empty($google_code) ? '<meta name="google-site-verification" content="' . $google_code . '" />' . "\n" : null;
    }

    public function sitemap($url)
    {
        $this->meta .= !empty($url) ? '<link rel="sitemap" type="application/xml" title="Sitemap" href="' . $url . '">' . "\n" : null;
    }

    public function bots($robots)
    {
        $this->meta .= !empty($robots) ? '<meta name="robots" content="' . $robots . '" />' . "\n" : null;
    }

    public function googlebot($robots)
    {
        $this->meta .= !empty($robots) ? '<meta name="googlebot" content="' . $robots . '" />' . "\n" : null;
        $this->meta .= !empty($robots) ? '<meta name="bingbot" content="' . $robots . '" />' . "\n" : null;
    }

    public function googleNews($robots)
    {
        $this->meta .= !empty($robots) ? '<meta name="googlebot-news" content="' . $robots . '" />' . "\n" : null;
    }

    public function color($theme_color)
    {
        $this->meta .= !empty($theme_color) ? '<meta name="msapplication-navbutton-color" content="' . $theme_color . '"/>' . "\n" : null;
        $this->meta .= !empty($theme_color) ? '<meta name="apple-mobile-web-app-status-bar-style" content="' . $theme_color . '"/>' . "\n" : null;
        $this->meta .= !empty($theme_color) ? '<meta name="msapplication-TileColor" content="' . $theme_color . '">' . "\n" : null;
        $this->meta .= !empty($theme_color) ? '<meta name="theme-color" content="' . $theme_color . '">' . "\n" : null;
    }

    public function fb_meta($image_url, $app_id = '', $admins_id = '', $type = 'website')
    {
        $this->meta .= '<!-- markup for facebook -->' . "\n";
        $this->meta .= '<meta property="og:type" content="' . $type . '"/>' . "\n";
        $this->meta .= '<meta property="og:title" content="' . $this->title . '"/>' . "\n";
        $this->meta .= '<meta property="og:url" content="' . $this->url . '"/>' . "\n";
        $this->meta .= '<meta property="og:site_name" content="' . $this->sitename . '"/>' . "\n";
        $this->meta .= !empty($image_url) ? '<meta property="og:image" content="' . $image_url . '"/>' . "\n" : null;
        $this->meta .= '<meta property="og:description" content="' . $this->description . '"/>' . "\n";
        $this->meta .= '<meta property="og:locale" content="en_US"/>' . "\n";
        $this->meta .= !empty($app_id) ? '<meta property="fb:app_id" content="' . $app_id . '"/>' . "\n" : null;
        $this->meta .= !empty($admins_id) ? '<meta property="fb:admins" content="' . $admins_id . '"/>' . "\n" : null;

        $this->meta .= '<!-- Schema.org for Google -->';
        $this->meta .= '<meta itemprop="name" content="' . $this->title . '">' . "\n";
        $this->meta .= '<meta itemprop="description" content="' . $this->description . '">' . "\n";
        $this->meta .= !empty($image_url) ? '<meta name="image" content="' . $image_url . '">' . "\n" : null;
    }

    public function tw_meta($image_url, $_company = '', $_creator = '', $card = 'summary')
    {
        $this->meta .= '<!-- Twitter Meta Tags -->' . "\n";
        $this->meta .= '<meta name="twitter:description" content="' . $this->description . '"/>' . "\n";
        $this->meta .= '<meta name="twitter:card" content="' . $card . '">' . "\n";
        $this->meta .= '<meta property="twitter:domain" content="saloveo.com">' . "\n";
        $this->meta .= '<meta property="twitter:url" content="' . $this->url . '">' . "\n";
        $this->meta .= '<meta name="twitter:title" content="' . $this->title . '">' . "\n";
        $this->meta .= !empty($image_url) ? '<meta name="twitter:image" content="' . $image_url . '">' . "\n" : null;
        $this->meta .= !empty($_company) ? '<meta name="twitter:site" content="@' . $_company . '">' . "\n" : null;
        $this->meta .= !empty($_creator) ? '<meta name="twitter:creator" content="@' . $_creator . '">' . "\n" : null;
    }

    public function tags()
    {
        echo $this->meta;
    }
}

/**
 * SendMail
 * Send Mails Using Different APIs OR Default PHP Mailer
 */
class Sendmail
{
    public $from, $from_name, $receiver, $subject;
    private $body;
    private $mail;
    /**
     * Starts here
     */

    public function __construct()
    {
    }

    public function SMTP(?array $meta, ?bool $multi = false)
    {
        //Create an instance; passing `true` enables exceptions
        $this->mail = new PHPMailer(true);
        try {
            // Enable SMTP debugging
            // 0 = off (for production use)
            // 1 = client messages
            // 2 = client and server messages
            $this->mail->SMTPDebug = 0;
            $this->mail->MailerDebug = true;
            $this->mail->Debugoutput = 'html';
            $this->mail->Encoding = "base64";
            $this->mail->CharSet = 'UTF-8';
            $this->mail->SMTPAutoTLS = true;
            $this->mail->SMTPKeepAlive = true; //SMTP connection will not close after each email sent, reduces SMTP overhead
            $this->mail->isSMTP();

            // Set the hostname of the mail server
            $this->mail->Host = CONFIG['SMTP']['smtp_host'];
            //Whether to use SMTP authentication
            $this->mail->SMTPAuth = CONFIG['SMTP']['smtp_auth'];
            //Username to use for SMTP authentication
            $this->mail->Username = CONFIG['SMTP']['smtp_username'];
            //Password to use for SMTP authentication
            $this->mail->Password = CONFIG['SMTP']['smtp_password'];
            $this->mail->SMTPSecure = CONFIG['SMTP']['smtp_secure'];
            // Set the SMTP port number - likely to be 25, 465 or 587
            $this->mail->Port = CONFIG['SMTP']['smtp_port'];

            $this->mail->WordWrap = 50;
            $this->mail->AltBody = 'To view the message, please use an HTML compatible email viewer!'; // optional - MsgHTML will create an alternate automatically
            $this->mail->isHTML(true);

            // $mail->AddReplyTo( _CONFIG['reply_email'], _CONFIG['reply_name']);
            if ($multi === true) {
                foreach ($meta as $keys => $_queue) {
                    if (isset($_queue['meta']['receiver']['name']) && !empty($_queue['meta']['receiver']['name'])) {
                        $this->mail->AddAddress($_queue['meta']['receiver']['to'], $_queue['meta']['receiver']['name']);
                    } else {
                        $this->mail->AddAddress($_queue['meta']['receiver']['to']);
                    }

                    // attach an image to track when the email is opened
                    if (isset($_queue['tracker']) && CONFIG['QUEUE']['track_open'] == true) {
                        $_queue['body'] .= "<img src='" . $_queue['tracker'] . "' width='1' height='1' />";
                    }

                    // Attachement
                    if (isset($_queue['attach']) && !empty($attach)) {
                        if (is_array($attach)) {
                            foreach ($attach as $attach_f) {
                                $this->mail->AddAttachment($attach_f); // attachment
                            }
                        } else {
                            $this->mail->addAttachment($attach);
                        }
                    }

                    $this->mail->setFrom($_queue['meta']['sender']['from'], $_queue['meta']['sender']['name']);
                    $this->mail->Subject = $_queue['subject'];
                    $this->mail->Body = $_queue['body'];
                    $this->mail->msgHTML = $_queue['body'];

                    // Trigger the email to send to the recipient
                    $this->mail->send();

                    // Add To Queue Report Logger
                    $_queue_report[$keys]['recipient'] = json_encode(["to" => $_queue['meta']['receiver']['to'], "name" => $_queue['meta']['receiver']['name']]);
                    $_queue_report[$keys]['queue'] = $_queue['queue'];
                    $_queue_report[$keys]['tracker'] = $_queue['tracker'];
                    $_queue_report[$keys]['subject'] = $_queue['subject'];
                    $_queue_report[$keys]['body'] = $_queue['body'];
                    $_queue_report[$keys]['sent_at'] = DATENOW;

                    // Clear all addresses and attachments for the next iteration
                    $this->mail->clearAddresses();
                    $this->mail->clearAttachments();
                    //$this->mail->ClearAllRecipients();
                }
            } else {
                $this->mail->setFrom($meta['sender']['from'], $meta['sender']['name']);

                if (isset($meta['receiver']['name']) && !empty($meta['receiver']['name'])) {
                    $this->mail->AddAddress($meta['receiver']['to'], $meta['receiver']['name']);
                } else {
                    $this->mail->AddAddress($meta['receiver']['to']);
                }

                // attach an image to track when the email is opened
                if (isset($meta['tracker']) && CONFIG['QUEUE']['track_open'] == true) {
                    $meta['body'] .= "<img src='" . $meta['tracker'] . "' width='1' height='1' />";
                }

                // Attachment
                if (isset($meta['attach']) && !empty($meta['attach'])) {
                    if (is_array($meta['attach'])) {
                        foreach ($meta['attach'] as $attach_f) {
                            $this->mail->AddAttachment($attach_f); // attachment
                        }
                    } else {
                        $this->mail->addAttachment($meta['attach']);
                    }
                }

                $this->mail->Subject = $meta['subject'];
                $this->mail->Body = $meta['body'];
                $this->mail->msgHTML = $meta['body'];
                $this->mail->send();

                $_queue_report['recipient'] = json_encode(["to" => $meta['receiver']['to'], "name" => $meta['receiver']['name']]);
                $_queue_report['queue'] = $meta['queue'];
                $_queue_report['tracker'] = $meta['tracker'];
                $_queue_report['subject'] = $meta['subject'];
                $_queue_report['body'] = $meta['body'];
                $_queue_report['sent_at'] = DATENOW;
            }

            // Return Sent Mails For Report Logging
            $return_report = ["status" => true, "data" => $_queue_report];
            return $return_report;
        } catch (\Exception $e) {
            //Reset the connection to abort sending this message
            //The loop will continue trying to send to the rest of the list
            $this->mail->getSMTPInstance()->reset();

            // Return Sent Mails For Report Logging
            $return_report = ["status" => true, "data" => $this->mail->ErrorInfo];
            return $return_report;

            //throw new Exception("Mailer Error: ". $this->mail->ErrorInfo);
        }
    }
}

/**
 * Onesignal Push Notifications
 * Send push notifications to users via Onesignal Rest API
 */
class OneSignal
{
    private $app_id, $auth_key, $fields;
    public $favicon, $logo, $banner;

    /**
     * @param mixed $app_id
     * @param mixed $auth_key
     * @param mixed $favicon
     * @param mixed $logo
     * @param mixed $banner
     */
    public function __construct()
    {
        $this->app_id = CONFIG['PUSH']['app'];
        $this->auth_key = CONFIG['PUSH']['rest'];
        $this->favicon = CONFIG['PUSH']['favicon'];
        $this->logo = CONFIG['PUSH']['logo'];

        $this->fields = [
            'app_id' => $this->app_id,
            'priority' => '10',
            'isAnyWeb' => true,
            'chrome_web_badge' => $this->favicon,
            'chrome_web_icon' => $this->logo,
            'large_icon' => $this->logo,
        ];
    }

    /**
     * @param mixed $title
     * @param mixed $body
     * @param mixed $url
     * @param mixed $userid
     *
     * @return [type]
     */
    public function PUSH_USER(string $title, string $body, $url, $userid, $banner = null)
    {
        $content = ["en" => $body];
        $headings = ["en" => $title];

        $fields = [
            'chrome_web_image' => $banner,
            'big_picture' => $banner,
            'include_player_ids' => array_filter($userid),
            'headings' => $headings,
            'url' => $url,
            'contents' => $content,
        ];

        $this->fields = $this->fields + $fields;

        $this->_CURL();
    }

    /**
     * @param mixed $title
     * @param mixed $body
     * @param mixed $url
     * @param mixed $sendaft
     *
     * @return [type]
     */
    public function PUSH_AFTER($title, $body, $url, $sendaft)
    {
        $content = ["en" => $body];
        $headings = ["en" => $title];

        $button_array = [];
        array_push($button_array, [
            "id" => "check-it",
            "text" => "Check It Out",
            "url" => $url,
        ]);

        $fields = [
            'included_segments' => ['All'],
            'send_after' => $sendaft,
            'app_id' => $this->app_id,
            'priority' => '10',
            'isAnyWeb' => true,
            'headings' => $headings,
            'url' => $url,
            'buttons' => $button_array,
            'web_buttons' => $button_array,
            'chrome_web_badge' => $this->favicon,
            'chrome_web_icon' => $this->logo,
            'chrome_web_image' => $this->banner,
            'big_picture' => $this->banner,
            'large_icon' => $this->logo,
            'contents' => $content,
        ];

        $this->_CURL();
    }

    /**
     * @param mixed $title
     * @param mixed $body
     * @param mixed $url
     *
     * @return [type]
     */
    public function PUSH($title, $body, $url)
    {
        $content = ["en" => $body];
        $headings = ["en" => $title];

        $button_array = [];
        array_push($button_array, [
            "id" => "check-it",
            "text" => "Check It Out",
            "url" => $url,
        ]);

        $fields = [
            'included_segments' => ['All'],
            'app_id' => $this->app_id,
            'priority' => '10',
            'isAnyWeb' => true,
            'headings' => $headings,
            'url' => $url,
            'buttons' => $button_array,
            'web_buttons' => $button_array,
            'chrome_web_badge' => $this->favicon,
            'chrome_web_icon' => $this->logo,
            'chrome_web_image' => $this->banner,
            'big_picture' => $this->banner,
            'large_icon' => $this->logo,
            'contents' => $content,
        ];
        $this->_CURL();
    }

    /**
     * @return [type]
     */
    public function _CURL()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json; charset=utf-8', 'Authorization: Basic ' . $this->auth_key]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($this->fields));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        curl_close($ch);
    }
}

/**
 * Authetication Class
 * Encrypt strings, hash passwords, verify passwords
 */
class Auth
{
    const SESS_CIPHER = 'AES-256-CBC';
    const Secret_key = 'DG7Gq)OJR_|?Q_[F]H$m4V8q*&^%$#%^';
    const Secret_iv = 'G+v*qZ$dQ4DEVM';

    /**
     * Encrypts the session ID and returns it as a base 64 encoded string.
     *
     * @param $session_id
     * @return string
     */

    public function passwordCheck($pwd, $strength = CONFIG['password_strength'])
    {
        $score = 5;
        $msg = [];

        if ($strength >= 0) {
            if (empty($pwd)) {
                $score -= 1;
                $msg[] = "Password should field should not be empty";
            }
        }
        if ($strength >= 1) {
            if (strlen($pwd) < CONFIG['password_length']) {
                $score -= 1;
                $msg[] = "Password should contain at least 7 characters";
            }
        }
        if ($strength >= 2) {
            if (!preg_match('@[A-Z]@', $pwd)) {
                $score -= 1;
                $msg[] = "Password should contain at least 1 upper case character";
            }
        }
        if ($strength >= 3) {
            if (!preg_match('@[a-z]@', $pwd)) {
                $score -= 1;
                $msg[] = "Password should contain at least 1 lower case character";
            }
        }
        if ($strength >= 4) {
            if (!preg_match('@[0-9]@', $pwd)) {
                $score -= 1;
                $msg[] = "Password should contain at least 1 numeric character";
            }
        }
        if ($strength >= 5) {
            if (!preg_match('@[^\w]@', $pwd)) {
                $score -= 1;
                $msg[] = "Password should contain at least 1 special character";
            }
        }
        if (empty($msg)) {
            $status = "success";
        } else {
            $status = "error";
        }
        return ['status' => $status, "data" => ["msg" => $msg, "score" => $score]];
    }

    /**
     * @param mixed $password
     *
     * @return [type]
     */
    public function hash($password)
    {
        return password_hash($password, PASSWORD_BCRYPT, [15]);
    }

    /**
     * @param mixed $password
     * @param mixed $old_password
     *
     * @return [type]
     */
    public function isHash($password, $old_password)
    {
        if (password_verify($password, $old_password)) {
            return true;
        }
    }

    /**
     * Generate salts.
     *
     * @param (int)  $length  Length of salts.
     * @param (bool) $special Should special chars include or not.
     *
     * @since 1.0.0
     *
     * @return string
     */
    public function salt(int $length, $special = false)
    {
        $s = $special === true ? ['@', '#', '$', '%', '^', '&', '*', '-', '_'] : [];
        $chars = array_merge(range(0, 9), range('a', 'z'), $s, range('A', 'Z'), range(0, 9), $s);
        $stringlength = count($chars); //Used Count because its array now
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $chars[rand(0, $stringlength - 1)];
        }

        return $randomString;
    }

    /**
     * @param mixed $q
     *
     * @return [type]
     */
    public function Close($q)
    {
        // Get the MD5 hash salt as a key.
        $key = $this->_getSalt();
        // For an easy iv, MD5 the salt again.
        $iv = $this->_getIv();
        // Encrypt the session ID.
        $ciphertext = openssl_encrypt(strval($q), self::SESS_CIPHER, $key, 0, $iv);
        $encryptedSessionId = base64_encode($ciphertext);
        return $encryptedSessionId;
    }

    /**
     * Decrypts a base 64 encoded encrypted session ID back to its original form.
     *
     * @param $encryptedSessionId
     * @return string
     */
    public function Open($q)
    {
        // Get the MD5 hash salt as a key.
        $key = $this->_getSalt();
        // For an easy iv, MD5 the salt again.
        $iv = $this->_getIv();
        // Encrypt the session ID.
        return openssl_decrypt(base64_decode(strval($q)), self::SESS_CIPHER, $key, 0, $iv);
    }

    public function _getIv()
    {
        return substr(hash('sha256', self::Secret_iv), 0, 16);
    }

    public function _getSalt()
    {
        return hash('sha256', self::Secret_key);
    }
}
