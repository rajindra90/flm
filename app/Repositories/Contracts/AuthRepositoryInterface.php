<?php
/**
 * Created by PhpStorm.
 * Users: Rajindra
 * Date: 11/15/2018
 * Time: 12:00 AM
 */

namespace App\Repositories\Contracts;


interface AuthRepositoryInterface
{

    public function create(array $data);

    public function confirmEmail($token);

    public function resendEmail($email, $data);

    public function getUserByEmail($email);
}