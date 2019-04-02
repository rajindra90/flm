<?php

namespace App\Http\Controllers\Api;

use App\Repositories\FriendsRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Friends AS FriendsResource;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;

class FriendsController extends Controller
{

    /**
     * @var FriendsRepository
     */
    private $friendsRepo;

    /**
     * FriendsController constructor.
     * @param FriendsRepository $friendsRepo
     */
    public function __construct(FriendsRepository $friendsRepo)
    {
        $this->friendsRepo = $friendsRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $userId = $request->user()->id;
        $friendsData = $this->friendsRepo->getFriendsById($userId);

        return FriendsResource::collection($friendsData);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
        ]);

        $userId = $request->user()->id;
        $email = $request['email'];

        $friendId = $this->friendsRepo->getFriendId($email);

        if ($userId == $friendId) {
            return response()->json([
                'message' => 'You cant send invitation to this email address'
            ], 412);
        }

        if (!$friendId) {
            return response()->json([
                'message' => 'There is no user for this email address'
            ], 412);
        }

        if ($this->friendsRepo->checkExistRequest($userId, $friendId)) {
            return response()->json([
                'message' => 'Already sent a friend request to that user '
            ], 412);
        }

        if ($this->friendsRepo->checkExistRequest($friendId, $userId)) {
            return response()->json([
                'message' => 'You have already friend request or both are friends '
            ], 412);
        }

        $data = $this->friendsRepo->createFriendRequest([
            'user_id' => $userId,
            'friend_id' => $friendId,
            'request_token' => Str::random(40) . time(),
        ], $email);

        if ($data) {
            return response()->json([
                'message' => 'Friend request has been sent.'
            ], 200);
        } else {
            return response()->json([
                'message' => $data
            ], 500);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRequestList(Request $request)
    {
        $userId = $request->user()->id;
        $requestData = $this->friendsRepo->getFriendRequestById($userId);

        return response()->json([
            'data' => $requestData
        ], 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteFriend(Request $request)
    {
        $request->validate([
            'id' => 'required|integer'
        ]);

        $data = [
            'status' => 0
        ];

        $this->friendsRepo->deleteFriend($data, $request->id);

        return response()->json([
            'message' => 'Successfully deleted friend!',
        ], 200);

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function acceptRequest(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'friend_id' => 'required|integer',
        ]);

        $data = [
            'is_accepted' => 1
        ];

        $this->friendsRepo->acceptRequest($data, $request->id);

        $userId = $request->user()->id;
        $this->friendsRepo->createFriendAfterAccept([
            'user_id' => $userId,
            'friend_id' => $request->friend_id,
            'is_accepted' => 1
        ]);

        return response()->json([
            'message' => 'Successfully accept friend request!',
        ], 200);

    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function requestEmailAccept()
    {
        $token = Input::get('token', false);

        $frndData = $this->friendsRepo->getFriendDetailsByToken($token);
        $this->friendsRepo->requestEmailAccept($token);
        $this->friendsRepo->createFriendAfterAccept([
            'user_id' => $frndData->friend_id,
            'friend_id' => $frndData->user_id,
            'is_accepted' => 1
        ]);

        return Redirect::to('/#/friendslist');
    }
}
