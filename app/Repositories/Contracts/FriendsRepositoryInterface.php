<?php
/**
 * Created by PhpStorm.
 * Users: Rajindra
 * Date: 11/15/2018
 * Time: 12:00 AM
 */

namespace App\Repositories\Contracts;


interface FriendsRepositoryInterface
{

    public function createFriendRequest(array $data);

    public function getFriendId($email);

    public function checkExistRequest($userId, $friendId);

    public function getFriendsById($userId);

    public function deleteFriend($data, $id);

    public function getFriendRequestById($userId);

}