<?php
/**
 * Created by PhpStorm.
 * Users: Rajindra
 * Date: 11/15/2018
 * Time: 12:00 AM
 */

namespace App\Repositories;

use App\Mail\ConfirmAccount;
use App\User;
use App\Repositories\Contracts\AuthRepositoryInterface;
use Illuminate\Support\Facades\Mail;

class AuthRepository implements AuthRepositoryInterface
{
    /**
     * @var Users
     */
    private $users;

    /**
     * AuthRepository constructor.
     * @param Users $users
     */
    public function __construct(User $users)
    {
        $this->users = $users;
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        try {
            $user = $this->users->create($data);
            Mail::to($user)->queue(new ConfirmAccount($user));

            return true;
        } catch (\Exception $e) {
            return $e->getMessage();
        }

    }

    /**
     * @param $tokan
     * @return mixed
     */
    public function confirmEmail($tokan)
    {
        if($this->users->where('email_verified_token', $tokan)->update(['email_confirm' => 1])){
            return true;
        }else{
            return false;
        }
    }

    /**
     * @param $email
     * @param $data
     * @return bool|string
     */
    public function resendEmail($email, $data)
    {
        if ($this->users->where('email', $email)->update($data)) {
            try {
                $user = $this->getUserByEmail($email);
                Mail::to($user)->queue(new ConfirmAccount($user));
                return true;
            } catch (\Exception $e) {
                return $e->getMessage();
            }

        } else {
            return false;
        }


    }

    /**
     * @param $email
     * @return mixed
     */
    public function getUserByEmail($email)
    {
        return $this->users->select(
            '*'
        )->where('status', 1)
            ->where('email', $email)
            ->first();
    }

}