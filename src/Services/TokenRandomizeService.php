<?php


namespace App\Services;


class TokenRandomizeService
{
    /**
     * @return string
     */
    public function generateToken() : string
    {
        return sha1(rand());
    }
}