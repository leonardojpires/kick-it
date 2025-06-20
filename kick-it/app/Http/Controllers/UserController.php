<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function show(User $user) {
        $user = User::findOrFail($user->id);
        $user_score = $user->rooms()->sum('room_user.score');

        return view('profile.index', [
            'user' => $user,
            'user_score' => $user_score,
        ]);
    }
}
