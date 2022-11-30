<?php
declare(strict_types = 1); 

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

/**
 * Tushcode Developer
 *
 * @package app
 * @author  Tushcode <tushcode@gmail.com>
 * @link https://github.com/tushcode/
 * @version 1.0.0
 * @link https://tushcode.com.ng
 * @license http://opensource.org/licenses/MIT MIT License
 */

if (session_status() == PHP_SESSION_NONE) {
    session_start([ 
     'cookie_lifetime' => 604800 * 7,  // 7 Days Session
     'gc_maxlifetime' => 604800 * 7, 
    ]); 
}else{
    session_regenerate_id(true);
}

header( 'Strict-Transport-Security: max-age=31536000; includeSubDomains; preload' );
require_once __DIR__ . '/../app/init.php';

