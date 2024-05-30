<?php

namespace App\DTO;

class LoginDTO
{
    public $username;
    public $password;
    public $tfa_code;

    public function __construct(array $data)
    {
        $this->username = $data['username'];
        $this->password = $data['password'];
        $this->tfa_code = $data['tfa_code'];
    }
}
