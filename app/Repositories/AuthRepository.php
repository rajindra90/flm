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
            Mail::to($user)->send(new ConfirmAccount($user));

            return $user;
        } catch (\Exception $e) {

        }

    }

    public function confirmEmail($tokan)
    {
        return $this->users->where('email_verified_token', $tokan)->update(['email_confirm' => 1]);
    }

}