<?php
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

// LOAD CONFIG
require_once __DIR__. '/Config/config.php';

// AUTOLOAD CORE LIBRARIES
require_once __DIR__. '/../vendor/autoload.php';

// LOAD HELPERS FILES
require_once APPROOT. '/Helpers/Functions.php';
require_once APPROOT. '/Helpers/Location.php';
require_once APPROOT. '/Helpers/Bank.php';
require_once APPROOT. '/Helpers/Checker.php';
require_once APPROOT. '/Helpers/EmailVerifier.php';
require_once APPROOT. '/Helpers/Files.php';
require_once APPROOT. '/Helpers/Security.php';

use App\Lib\Router;

// CHECK IF MAINTENANCE MODE IS ACTIVE
if(CONFIG['maintenance'] == true){
    // DISPLAY MAINTENANCE TEMPLATE
    maintenance_mode(CONFIG['maintenance_time']);
}else{
    // LOAD CORE CLASS
    $init = new Router;
}


