<?php
declare(strict_types = 1); 

namespace App\Controller;

use App\Lib\{Controller, Database};
use Nette\Utils\{Json, Strings, Validators, Arrays, Html, Random};
use Nette\Http\Url;

class HomeController extends Controller
{   
    public $httpRequest, $httpResponse;
    protected $db;

    public function __construct()
    {
        $this->httpRequest = (new \Nette\Http\RequestFactory)->fromGlobals();
        $this->httpResponse = (new \Nette\Http\Response);
    }

    public function index()
    {   
        // DATABASE CONNECTION
        $this->db = (new Database)->connectDB();
        parent::view('home/result_1');
    }

    public function question_2()
    {   
        if ($this->httpRequest->isMethod('POST')) {

            $_POST =  json_decode(file_get_contents('php://input'), true);

            $polling = (new \App\Models\Election)->LGA_Polling(\intval($_POST['lga']));
            print Json::encode(['status' => true, 'data'=> ['result' => $polling]]);

        }else{

            // DATABASE CONNECTION
            $this->db = (new Database)->connectDB();
            parent::view('home/result_2');
        }
    }

    public function question_3()
    {   
        if ($this->httpRequest->isMethod('POST')) {

            $_post = sanitize($this->httpRequest->getPost());

            $polling = (new \App\Models\Election)->storeResult($_post);
            print Json::encode($polling);

        }else{

            // DATABASE CONNECTION
            $this->db = (new Database)->connectDB();
            parent::view('home/result_3');
        }
    }

}

?>
