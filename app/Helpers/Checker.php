<?php
namespace App\Helpers;
use App\Helpers\Files;

/**
 * Checker
 * Verifications Of Emails, Phone Numbers, Domains, checks for phone number, network provider etc
 */
class Checker
{


    public $networks = [
        'Glo' => ['0805', '0705', '0905', '0807', '0815', '0811'],
        'MTN' => ['0806', '0803', '0816', '0813', '0810', '0814', '0903', '0906', '0703', '0706'],
        'Airtel' => ['0802', '0902', '0701', '0808', '0708', '0812', '0907'],
        '9mobile' => ['0809', '0909', '0817', '0818', '0908'],
        'Ntel' => ['0804'],
        'Smile' => ['0702'],
    ];

    public $nigerian_phone_number_prefixes = [
        '0803','0806','0703','0706','0813','0816','0810','0814','0903','0906','0805','0807','0705','0815','0811','0905','0802','0808','0708','0704','0812','0701','0901','0902','0904','0809','0818','0817','0908','0909','0804','0702','07028','07029','0819','07025','07026','07027','0709','0707'];

    /**
     * @param mixed $phone
     *
     * @return [network_name]
     */
    public function network($phone)
    {
        $number_prefix = substr($phone, 0, 4);
        if (in_array($number_prefix, $this->networks['Glo'])) {
            return 'Glo';
        } elseif (in_array($number_prefix, $this->networks['Smile'])) {
            return 'Smile';
        } elseif (in_array($number_prefix, $this->networks['MTN'])) {
            return 'MTN';
        } elseif (in_array($number_prefix, $this->networks['Airtel'])) {
            return 'Airtel';
        } elseif (in_array($number_prefix, $this->networks['9mobile'])) {
            return '9mobile';
        } elseif (in_array($number_prefix, $this->networks['Ntel'])) {
            return 'Ntel';
        } else {
            return 'Unknown';
        }
    }

    /**
     * @param mixed $phone
     *
     * @return $bool
     */
    public function phone_format($phone)
    {
        if (strlen($phone) > 10 && strlen($phone) < 12) {
            $number_prefix = substr($phone, 0, 4);
            if (in_array($number_prefix, $this->nigerian_phone_number_prefixes)) {
                return true;
            }
        } else {
            return false;
        }
    }

    /**
     * @param mixed $phone
     *
     * @return @string
     */
    public function phone_prefix($phone)
    {
        $nigeria_prefix = '+234' . substr($phone, 1);
        return $nigeria_prefix;
    }

    /**
     * Check if email is disposable
     * @param mixed $email
     *
     * @return [type]
     */
    public function disposable($email)
    {
        $files = new \App\Helpers\Files;
        $rfiles = $files->open(APPROOT.'/Helpers/data/disposable.txt', 'readOnly')->read(APPROOT.'/Helpers/data/disposable.txt');
        $arrFile = explode("\n", $rfiles);
        $domain = substr(strrchr($email, "@"), 1);
        if (!in_array($domain, $arrFile)) {
            return true;
        }
    }

    public function mail_format($email)
    {
        $emailIsValid = false;
        if (!empty($email)) {
            $domain = ltrim(stristr($email, '@'), '@') . '.';
            $user = stristr($email, '@', true);
            if (!empty($user) && !empty($domain) && checkdnsrr($domain)) {
                $emailIsValid = true;
            }
        }
        return $emailIsValid;
    }

    public function mail_mx($email)
    {
        $email_host = explode("@", $email);
        $host = end($email_host) . ".";
        return checkdnsrr($host, "MX");
    }
}
