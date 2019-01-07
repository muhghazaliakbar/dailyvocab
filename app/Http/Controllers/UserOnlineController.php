<?php

namespace App\Http\Controllers;

use App\Events\UserOnline;
use App\User;
use Illuminate\Http\Request;

class UserOnlineController extends Controller
{
//    /**
//     * UserOnlineController constructor.
//     */
//    public function __construct()
//    {
//        $this->middleware('auth');
//    }

    /**
     * @param User $user
     */
    public function __invoke(User $user)
    {
        $user->update(['status' => 'online']);

        broadcast(new UserOnline($user));
    }

}
