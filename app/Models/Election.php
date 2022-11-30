<?php

declare(strict_types=1);

namespace App\Models;

use App\Lib\Database;
use App\Helpers\Security;
use Nette\Utils\{Json, Strings, Validators, Arrays, Html, Random};

class Election extends Database
{

    public function __construct()
    {
        // DATABASE CONNECTION
        $this->db = (new Database())->connectDB();
    }

    /**
     * Use Local Government ID to get total polling score 
     * @param int $lga_id
     * 
     * @return int
     */
    public function LGA_Polling(int $lga_id): int
    {
        $stmt = $this->db->fetch('SELECT SUM(party_score) as votes FROM announced_pu_results pu_r INNER JOIN polling_unit pu ON pu_r.polling_unit_uniqueid = pu.uniqueid WHERE polling_unit_uniqueid IN(SELECT uniqueid FROM `polling_unit` WHERE lga_id = ?)', $lga_id);
        return (int) $stmt['votes'] ?? 0;
    }

    public function storeResult(array $data){

        $result = $this->db->transaction(function ($database) use ($data) {

            $secure = new Security;

            $database->query('INSERT INTO announced_pu_results',  [
                'result_id' => NULL, 
                'polling_unit_uniqueid' => $data['poll_id'], 
                'party_abbreviation' => $data['poll_party'], 
                'party_score' => $data['poll_score'], 
                'entered_by_user' => 'Solomon Okafor', 
                'date_entered' => \DATENOW, 
                'user_ip_address' => $secure->client_ip(),
            ]);

            return $database->getInsertId();
        });

        if($result){
            return ['status' => true, 'data' => ['message' => "You've been successful in adding a polling score to the announced polling unit database."]];
        }else{
            return ['status' => false, 'data' => ['message' => 'Sorry, there was a problem processing your request. Please try again later.']];
        }
    }
}
