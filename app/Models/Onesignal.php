<?php
declare(strict_types=1);

namespace App\Models;
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
    public function PUSH_USER(string $title, string $body, $url, array $userid, $banner = null)
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
