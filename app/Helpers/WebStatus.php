<?php
declare(strict_types = 1); 

namespace App\Helpers;

class WebStatus
{   
    private $domain;

    protected $from = 'noreply@domain.com';
    protected $fromName = 'Website Status Checker';

    public function __construct(string $website)
    {
        // Check if URL is correct
        if (filter_var($website, FILTER_VALIDATE_URL)) {
            $this->domain = filter_var($website, FILTER_VALIDATE_URL);
        }
        ini_set('max_execution_time', 0);
    }

    /**
     * Set the name and email address where any automated email will com from
     * @param string $email This should be the email address where the automated emails will come from
     * @param string $name This should be the displayed name where the emails come from
     * @return $this
     */
    public function setEmailFrom($email, $name)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->from = filter_var($email, FILTER_SANITIZE_EMAIL);
        }

        $this->fromName = filter_var($name, FILTER_SANITIZE_SPECIAL_CHARS);
        return $this;
    }

    /**
     * Retrieves the websites SSL certificate and retrieves the certificate information
     * @param string $url This should the website address
     * @return array The certificate information will be returned as an array
     */
    protected function getSSLCert()
    {
        $domain = 'https://'.str_replace(['http://', 'https://'], '', strtolower($this->domain)); // Force https else it fails
        $original_parse = parse_url($domain, PHP_URL_HOST);
        $get = stream_context_create(["ssl" => ["capture_peer_cert" => true]]);
        $read = stream_socket_client("ssl://".$original_parse.":443", $errno, $errstr, 30, STREAM_CLIENT_CONNECT, $get);
        $cert = stream_context_get_params($read);
        return openssl_x509_parse($cert['options']['ssl']['peer_certificate']);
    }

    /**
     * Sends the emails if that option is set
     * @return boolean If the email is sent successfully will return true else returns false
     */
    protected function triggerEmail()
    {

    }

    /**
     * Checks a single domain name if it is valid
     * @param string $website This should be the domain name
     * @param int $i If multiple websites are being checked this will be an incrementing integer
     */

    protected function checkDomain($website)
    {
    }

    /*
    * Actual function to get Website Up or Down
    */
    public function isLive($host){
        
        if($socket = @fsockopen($host, 80, $errno, $errstr, 30)) {
            fclose($socket);
            return true;
        } else {
            return false;
        }
    }

}
