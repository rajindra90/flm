<?php
/**
 * Created by PhpStorm.
 * Users: Rajindra
 * Date: 11/15/2018
 * Time: 12:00 AM
 */

namespace App\Repositories;

use App\Friends;
use App\Mail\FriendRequest;
use App\User;
use App\Repositories\Contracts\FriendsRepositoryInterface;
use Illuminate\Support\Facades\Mail;

class FriendsRepository implements FriendsRepositoryInterface
{
    /**
     * @var Friends
     */
    private $friends;

    /**
     * @var
     */
    private $users;

    /**
     * FriendsRepository constructor.
     * @param Friends $friends
     * @param User $users
     */
    public function __construct(Friends $friends, User $users)
    {
        $this->friends = $friends;
        $this->users = $users;
    }

    /**
     * @param array $data
     * @param $email
     * @return mixed
     */
    public function createFriendRequest(array $data, $email)
    {
        try {
            $req = $this->friends->create($data);;
            Mail::to(['email' => $email])->queue(new FriendRequest($req));

            return true;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * @param array $data
     * @return bool|string
     */
    public function createFriendAfterAccept(array $data)
    {
        try {
            $this->friends->create($data);

            return true;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * @param $email
     * @return mixed
     */
    public function getFriendId($email)
    {
        $data = $this->users->select(
            'id'
        )->where('status', 1)
            ->where('email', $email)
            ->first();

        if (empty($data)) {
            return false;
        } else {
            return $data->id;
        }
    }

    /**
     * @param $userId
     * @param $friendId
     * @return bool
     */
    public function checkExistRequest($userId, $friendId)
    {
        $count = $this->friends->select(
            'id'
        )->where('status', 1)
            ->where('user_id', $userId)
            ->where('friend_id', $friendId)
            ->count();

        if ($count == 0) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * @param $userId
     * @return mixed
     */
    public function getFriendsById($userId)
    {
        return $this->friends->join('users', 'users.id', 'friends.friend_id')
            ->where('friends.user_id', $userId)
            ->where('friends.status', 1)
            ->select('friends.id', 'users.first_name', 'users.last_name', 'users.email', 'friends.is_accepted')
            ->paginate(10);
    }

    /**
     * @param $data
     * @param $id
     * @return mixed
     */
    public function deleteFriend($data, $id)
    {
        return $this->friends->where('id', $id)->update($data);
    }

    /**
     * @param $userId
     * @return mixed
     */
    public function getFriendRequestById($userId)
    {
        return $this->friends->join('users', 'users.id', 'friends.friend_id')
            ->where('friends.friend_id', $userId)
            ->where('friends.status', 1)
            ->where('friends.is_accepted', 0)
            ->select('friends.id', 'friends.user_id', 'users.first_name', 'users.last_name', 'users.email', 'friends.is_accepted')
            ->paginate(10);
    }

    /**
     * @param $data
     * @param $id
     * @return mixed
     */
    public function acceptRequest($data, $id)
    {
        return $this->friends->where('id', $id)->update($data);
    }

    /**
     * @param $tokan
     * @return mixed
     */
    public function requestEmailAccept($tokan)
    {
        return $this->friends->where('request_token', $tokan)->update(['is_accepted' => 1]);
    }

    /**
     * @param $token
     * @return mixed
     */
    public function getFriendDetailsByToken($token)
    {
        return $data = $this->friends->select(
            'user_id', 'friend_id', 'is_accepted'
        )->where('status', 1)
            ->where('request_token', $token)
            ->first();
    }
}