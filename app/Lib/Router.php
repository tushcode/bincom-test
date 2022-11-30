<?php
/*
   * App Core Class
   * Creates URL & loads core controller
   * URL FORMAT - /controller/method/params
   */

namespace App\Lib;

class Router
{
  public $currentController = null;
  public $currentMethod = null;
  protected $urlParams = [];
  protected $error = false;

  public function __construct()
  {
    $url = $this->getUrl();

    // check for controller: no controller given ? then load start-page, home
    if (!$this->currentController) {
      $page = new \App\Controller\HomeController;
      $page->index();
    } elseif (is_readable(APPROOT . '/Controller/' . ucfirst($this->currentController) . 'Controller.php')) {
      // here we did check for controller: does such a controller exist ?

      // Instantiate controller class
      $controller =  "\\App\\Controller\\" . $this->currentController . 'Controller';
      $this->currentController = new $controller;

      // check for method: does such a method exist in the controller ?
      $this->currentMethod = str_replace('-', '_', $this->currentMethod ?? '');
      if (method_exists($this->currentController, $this->currentMethod) && is_callable(array($this->currentController, $this->currentMethod))) {

        if (!empty($this->urlParams)) {
          // Call the method and pass arguments to it
          call_user_func_array(array($this->currentController, $this->currentMethod), $this->urlParams);
        } else {
          // If no parameters are given, just call the method without parameters, like $this->home->method();
          $this->currentController->{$this->currentMethod}();
        }
      } else {
        if (strlen($this->currentMethod) == 0) {
          // no action defined: call the default index() method of a selected controller
          $this->currentController->index();
        } else {
          $this->error = true;
        }
      }
    } else {
      if (!$this->currentMethod) {
        $page = new \App\Controller\HomeController;
        // check for method: does such a method exist in the home controller ?
        $this->currentController = str_replace('-', '_', $this->currentController);
        if (method_exists($page, $this->currentController) && is_callable(array($page, $this->currentController))) {
          $navigate = $this->currentController;
          $page->$navigate();
        } else {
          $this->error = true;
        }
      } else {
        $this->error = true;
      }
    }

    if ($this->error == true) {
      $page = new \App\Controller\ErrorController;
      $page->index();
    }
  }

  public function getUrl()
  {
    if (isset($_GET['url'])) {
      $url = trim($_GET['url'], '/');
      $url = filter_var($url, FILTER_SANITIZE_URL);
      $url = explode('/', $url);

      $this->currentController = isset($url[0]) ? $url[0] : null;
      $this->currentMethod = isset($url[1]) ? $url[1] : null;

      // Remove controller and action from the split URL
      unset($url[0], $url[1]);

      // Rebase array keys and store the URL params
      $this->urlParams = array_values($url);
    }
  }
}
