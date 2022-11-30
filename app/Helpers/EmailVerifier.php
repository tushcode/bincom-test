<?php
declare(strict_types = 1); 

namespace App\Helpers;
use App\Helpers\Files;

/** 
 * Class to validate the email address if they exist
 * 
 * @author tushcode.com <tushcode@gmail.com> 
 * @copyright Copyright (c) 2022, tushcode.com
 * @url https://www.tushcode.com
 */ 

class EmailVerifier {

    private $sender = CONFIG['SMTP']['smtp_from'];
    protected $resource = null;
    public $wait = 60;
    protected $response = null;
    private $result = [];
    protected $headers = [];

    /** 
     * SMTP RFC standard line ending. 
    */ 
    const CRLF = "\r\n"; 


    public function __construct($from_ ='olawalesbox@yahoo.com'){
        $this->sender = $from_;
    }

    /*
     * get host by email address
     *
     * @param string $email
     * @return string host
     */

    public static function getDomain(string $email): string{
        if(self::is_invalid($email) == false){
            // Get the domain of the email recipient
            $email_arr = explode('@', $email);
            $domain = array_slice($email_arr, -1);
            return $domain[0];
        }
    } 

    /*
     * get user name by email address
     *
     * @param string $email
     * @return string name
     */
    public static function getUser(string $email): string
    {
        $prefix = substr($email, 0, strrpos($email, '@'));
        $uname = str_replace('.', ' ', $prefix);
        $uname = str_replace('_', ' ', $prefix);
        return ucwords($uname);
    }


    /** 
     * Get array of MX records for host. Sort by weight information. 
     * @param string $hostname The Internet host name. 
     * @return array Array of the MX records found. 
     */ 
    public function getMxRecords(string $email){
        // Get the domain of the email recipient
        $domain = self::getDomain($email); 
        //$output = [];

        // dig it!!! dig it!!!
        //exec('dig @localhost ' . $domain . ' MX +short', $output);
        getmxrr($domain, $output);
        return $output;

        $mx_hosts = array(); 
        $mx_weights = array(); 
        if (getmxrr($domain, $mx_hosts, $mx_weights) === FALSE) { 
            // MX records not found or an error occurred
            return array();
        } else { 
            array_multisort($mx_weights, $mx_hosts); 
        } 
        /** 
         * Add A-record as last chance (e.g. if no MX record is there). 
         * Thanks Nicht Lieb. 
         * @link http://www.faqs.org/rfcs/rfc2821.html RFC 2821 - Simple Mail Transfer Protocol 
         */ 
        if (empty($mx_hosts)) { 
            $mx_hosts[] = $domain; 
        } 
        return $mx_hosts; 
    }

    public function getProvider(string $url, bool $addHTTP = false)
    {
        if($addHTTP == true){
            $url = "http://" . $url;
        }

        preg_match("/[a-z0-9\-]{1,63}\.[a-z\.]{2,6}$/", parse_url($url, PHP_URL_HOST), $_domain_tld);
        return explode('.', $_domain_tld[0])[0];
    }

    /*
     * validate email
     *
     * @param string $email
     * @return bool
    */
    public static function is_invalid(string $email): bool{

        if(filter_var($email, FILTER_VALIDATE_EMAIL) && filter_var($email, FILTER_VALIDATE_EMAIL, FILTER_FLAG_EMAIL_UNICODE) && mb_check_encoding($email, 'UTF-8') && mb_detect_encoding($email, 'UTF-8', true) && preg_match("/^[a-z0-9\._\-\@]+$/i", $email)) {
            if (preg_match('/^(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){255,})(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){65,}@)(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22))(?:\.(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-[a-z0-9]+)*\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-[a-z0-9]+)*)|(?:\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\]))$/iD', $email)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param string $email
     * 
     * @return bool
     */
    public static function is_disposable(string $email){
        $files = new \App\Helpers\Files;
        $host_name = self::getDomain($email);
        $read_files = $files->open(APPROOT.'/Helpers/data/disposable.txt', 'readOnly')->read(APPROOT.'/Helpers/data/disposable.txt');
        // Place each line of disposable.txt into array
        $arrFile = explode("\r\n", $read_files);
        if (in_array($host_name, $arrFile)) {
            return true;
        }

        return false;
    }

    /**
     * get the A DNS records for the host
     * @param string $email
     * 
     * @return bool
     */
    public static function is_dns(string $email): bool{

        if(!empty(dns_get_record(self::getDomain($email), DNS_A)) && !empty(dns_get_record(self::getDomain($email), DNS_NS))){
            return true;
        }
        return false;
    }

    /* say helo to server
     *
     * @param string $hi
     * @return string
     */
    public function sayHello ($hi = 'hi') {
        // check if resource is set
        if (!$this->resource) {
            throw new \Exception ('No socket opened.');
            return;
        }

        // say hello
        fwrite ($this->resource, "helo " . $hi . "\r\n");
        $res = '';
        for ($i = 0; $i < $this->wait; $i++) {
            sleep(1); // sleep 1 second
            // get response
            $response = fgets($this->resource, 1028);
            // if response is empty,
            // break loop
            if (!trim(strval($response)) && $res != '') {
                break;
            }

            $res .= $response;
        }

        return $res;
    }

    /*
     * set mail from
     *
     * @param string $email
     * @return string
     */
    public function mailFrom ($email) {
        // check if resource is set
        if (!$this->resource) {
            throw new \Exception ('No socket opened.');
            return;
        }

        // set mail from
        fwrite ($this->resource, "MAIL FROM: <" . $email . ">\r\n");
        $res = '';
        for ($i = 0; $i < $this->wait; $i++) {
            sleep(1); // sleep 1 second
            // get response
            $response = fgets ($this->resource, 1028);
            // if response is empty,
            // break loop
            if (!trim(strval($response)) && $res != '') {
                break;
            }

            $res .= $response;
        }

        return $res;
    }

    /*
     * set rcpt to
     *
     * @param string $email
     * @return string
     */
    public function rcptTo ($email) {
        
        // check if resource is set
        if (!$this->resource) {
            throw new \Exception ('No socket opened.');
            return;
        }

        // set mail from
        fwrite ($this->resource, "RCPT TO: <" . $email . ">\r\n");
        $res = '';
        for ($i = 0; $i < $this->wait; $i++) {
            sleep(1); // sleep 1 second
            // get response
            $response = fgets ($this->resource, 1028);
            // if response is empty,
            // break loop
            if (!trim(strval($response)) && $res != '') {
                break;
            }

            $res .= $response;
        }

        $this->response = $res;
        return $res;
    }

    /*
     * data request to telnet
     */
    public function data () {
        // check if resource is set
        if (!$this->resource) {
            throw new \Exception ('No socket opened.');
            return;
        }

        // set mail from
        fwrite ($this->resource, "DATA\r\n");
        
        $res = '';
        for ($i = 0; $i < $this->wait; $i++) {
            sleep(1); // sleep 1 second
            // get response
            $response = fgets ($this->resource, 1028);
            // if response is empty,
            // break loop
            if (!trim(strval($response)) && $res != '') {
                break;
            }

            $res .= $response;
        }
        
        return $res;
    }

    /*
     * add email header
     *
     * @param string key
     * @param string value
     */
    public function addHeader ($key, $value) {
        // check if resource is set
        if (!$this->resource) {
            throw new \Exception ('No socket opened.');
            return;
        }

        if ($key && $value) {
            $this->headers[] = $key . ':' . $value;
        }

        return $this;
    }
    
    /*
     * set email body
     *
     * @param string body
     */
    public function setBody($body) {
        // check if resource is set
        if (!$this->resource) {
            throw new \Exception ('No socket opened.');
            return;
        }

        // set headers
        foreach ($this->headers as $header) {
            fwrite ($this->resource, $header . "\r\n");
        }

        // now set the body
        fwrite ($this->resource, "\r\n");
        fwrite ($this->resource, $body . "\r\n");
        fwrite ($this->resource, ".\r\n");
        
        $res = '';
        for ($i = 0; $i < 10; $i++) {
            sleep(1); // sleep 1 second
            // get response
            $response = fgets ($this->resource, 1028);
            // if response is empty,
            // break loop
            if (!trim(strval($response)) && $res != '') {
                break;
            }

            $res .= $response;
        }
        
        // get response
        $this->response = $res;
        return $this;
    }

    /**
     * @param string $email
     * @param int $port
     * 
     * @return [type]
     */
    public function is_deliverable(string $email, int $port = 25)
    {   
        $domain_mx = self::getMxRecords($email);
        $client_host = self::getDomain($email);
        $host = self::getDomain($this->sender);
        
        // telnet baby
        $this->resource = fsockopen($domain_mx[0], $port, $errno, $errstr, 30);
        
        // no blocking
        stream_set_blocking($this->resource, false);
        // check if resource is set
        if ($this->resource) {

            // say hello
            $hello_response = $this->sayHello();
            if(!$hello_response){
                // say hello
                $hello_response = $this->sayHello($host);
            }

            // set mail from
            $this->mailFrom($this->sender);

            // set receive to
            $this->rcptTo($email);
            if(in_array($client_host, ['yahoo.com', 'ymail.com', 'yahoo.co.uk'])) {
                $this->data();
                $this->addHeader('Subject', 'test');
                $this->setBody('This is a test');
            }

            // match this response: recipient <email@domain.com> ok.
            if (preg_match('/ok/i', $this->response) && preg_match('/recipient/i', $this->response)) {
                $this->result = array_merge($this->result, ["is_active" => true]);
            }

            // match this response: OK [random-character] - gsmtp
            if (preg_match('/OK\s.*\s\-\sgsmtp/', $this->response)) {
                $this->result = array_merge($this->result, ["is_active" => true]);
            }

            // match unauthenticated response, this means it's valid but we are not authorized to send an email here
            // seen this on zoho servers
            if (preg_match('/unauthenticated/', $this->response)) {
                $this->result = array_merge($this->result, ["is_active" => true]);
            }

            // this is seen on outlook
            if (preg_match('/Recipient\sOK/i', $this->response)) {
                $this->result = array_merge($this->result, ["is_active" => true]);
            }

            // yahoo
            if (preg_match('/ok/', $this->response) && preg_match('/dirdel/', $this->response)) {
                $this->result = array_merge($this->result, ["is_active" => true]);
            }

            // Accepted
            if (preg_match('/accepted/i', $this->response)) {
                $this->result = array_merge($this->result, ["is_active" => true]);
            }

            if (preg_match('/\sOk/', $this->response)) {
                $this->result = array_merge($this->result, ["is_active" => true]);
            }

            if(empty($this->result)){
                $this->result = array_merge($this->result, ["is_active" => false]);
            }

            return $this->result;
        }
    }

    public function run(string $email){
        $time_start = microtime(true);

        $this->result = array_merge($this->result, ["domain" => self::getDomain($email), "user" => self::getUser($email), "email"=> $email]);
        $this->result = array_merge($this->result, ["invalid" => self::is_invalid($email)]);
        $this->result = array_merge($this->result, ["disposable" => self::is_disposable($email)]);
        $this->result = array_merge($this->result, ["dns_found" => self::is_dns($email)]);
        $this->result = array_merge($this->result, ["mx_record" => self::getMxRecords($email)[0] ?? NULL, "smtp_provider"=> self::getProvider(self::getMxRecords($email)[0], true)]);
        $this->result = array_merge($this->result, ["duration" => executedTime($time_start)]);

        return json_output($this->result);
    }

    public function check(string $email)
    {
        if(self::getDomain($email) == true && self::is_invalid($email) == false && self::is_disposable($email) == false && self::is_dns($email) == true && !empty(self::getMxRecords($email)[0])){
            return true;
        }
        return false;
    }
}

// $dd = new EmailVerifier;
// echo ($dd->run("julius_akinwusi@yahoo.com"));

