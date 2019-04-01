<?php

namespace App\Http\Controllers\Api;

use App\Repositories\FriendsRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Friends AS FriendsResource;
use Illuminate\Support\Facades\Mail;

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
        $friendId = $this->friendsRepo->getFriendId($request['email']);

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

        return $this->friendsRepo->createFriendRequest([
            'user_id' => $userId,
            'friend_id' => $friendId
        ]);
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
         $this->friendsRepo->createFriendRequest([
            'user_id' => $userId,
            'friend_id' => $request->friend_id,
            'is_accepted' => 1
        ]);

        return response()->json([
            'message' => 'Successfully accept friend request!',
        ], 200);

    }

    public function sendEmail(){
        $data = array('name'=>"Virat Gandhi");
        Mail::send(['text'=>'mail'], $data, function($message) {
            $message->to('vprprabodhitha@gmail.com', 'Tutorials Point')->subject
            ('Laravel Basic Testing Mail');
            $message->from('xyz@gmail.com','Virat Gandhi');
        });
        echo "Basic Email Sent. Check your inbox.";
    }
}
