<?php

declare(strict_types=1);

namespace App\Lib;

//Load the model and the view
class Controller
{
    public function model(string $model)
    {
        //Require model file
        require_once APPROOT . '/Models/' . $model . '.php';
        
        //Instantiate model
        return new $model();
    }

    //Load the view (checks for the file)
    public function view(string $view, ?array $data = [])
    {
        if (file_exists(APPROOT . '/Views/' . $view . '.php') && !empty($view)) {

            if (CONFIG['minify_html'] === true) {
                ob_start("minifyHTML");
            }

            require_once APPROOT . '/Views/' . $view . '.php';

        } else {

            http_response_code(404);
            die("View does not exists.");
        }
    }
}
