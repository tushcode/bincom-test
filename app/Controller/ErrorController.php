<?php

namespace App\Controller;
use App\Lib\Controller;

class ErrorController extends Controller
{
    public function __construct()
    {
        //clear_cache();
    }

    public function index(){
        
        $data = [
            'title' => "Page Not Found - 404",
            'custom_title' => false,
            'tag' => [
              'url'=> URLROOT."/",
              'keyword' => "",
              'info'=> "Sorry Page Was Not Found",
              'image' => "",
            ],
        ];

        ob_start("minifyHTML");
        $this->view('Error/404', $data);
    }

    public function forbidden(){
        $data = [
            'title' => "Forbidden",
            'custom_title' => false,
            'tag' => [
              'url'=> URLROOT."/",
              'keyword' => "",
              'info'=> "Unfortunately, you do not have permission to view this page",
              'image' => "",
            ],
        ];

        ob_start("minifyHTML");
        $this->view('Error/403', $data);
    }

    public function server(){
        $data = [
            'title' => "Internal Server Error",
            'custom_title' => false,
            'tag' => [
              'url'=> URLROOT."/",
              'keyword' => "",
              'info'=> "Sorry, something went wrong. Internal Server Error",
              'image' => "",
            ],
        ];

        ob_start("minifyHTML");
        $this->view('Error/504', $data);
    }

}

?>
