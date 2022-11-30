<?php

declare(strict_types=1);
/*
* PDO Database Class
* Connect to database
* Create prepared statements
* Bind values
* Return rows and results
*/


namespace App\lib;

use PDO;
use Nette\Database\Connection;

class Database extends Connection
{
    protected $dbHost = DATABASE['host'];
    protected $dbUser = DATABASE['user'];
    protected $dbPass = DATABASE['pass'];
    protected $dbName = DATABASE['name'];
    protected $dbCharset = DATABASE['charset'];

    //private $db;
    public $db;

    // PDO OPTION SETTINGS
    private $options;

    public function __construct()
    {
        // START DB CONNECTION
        
        $this->options = array(
            "lazy" => true,
            "charset" => $this->dbCharset,
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            PDO::ATTR_EMULATE_PREPARES   => false,
            PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
            PDO::MYSQL_ATTR_FOUND_ROWS => true,
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES "UTF8"',
        );
        
    }

    /**
     * Open database connection.
     *
     * @param $status true : false  true means open false close
     *
     * @return bool
     */
    public function connectDB()
    {   
        // CREATE PDO INSTANCE
        $conn = 'mysql:host=' . $this->dbHost . ';dbname=' . $this->dbName;

        // DATABASE CONNECTION
        $this->db = new Connection($conn, $this->dbUser, $this->dbPass, $this->options);
        $this->db->disconnect();
        return $this->db;
    }
}
