<?php


namespace App\Services;


class TokenRandomizeService
{
    public $token;

    /**
     * @return mixed
     */
    public function __construct()
    {
        $this->token = substr(sha1(rand()), 0, 100);
    }
}