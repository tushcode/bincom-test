<?php
declare(strict_types = 1); 

namespace App\Helpers;

class Bank
{
    public function __construct()
    {
        $read_files = file_get_contents(APPROOT . '/Helpers/data/ng-banks.json');
        $this->bank_list = json_decode($read_files, true);
    }

    public function ng_bank_dropdown(?string $default_option = '', ?string $option_value = 'name')
    {
        $option = "";
        foreach ($this->bank_list as $key => $value) {
            $selected = $value['name'] == $default_option ? ' selected' : '';
            $option .= '<option value="' . $value[$option_value] . '"' . $selected . '>' . $value['name'] . '</option>' . "\n";
        }
        return $option;
    }
}
