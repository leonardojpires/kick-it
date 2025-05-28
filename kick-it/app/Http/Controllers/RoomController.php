<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::all();
        $user = User::findOrFail(1);
        return view('main.index', compact('rooms', 'user'));
    }

    public function store(Request $request) {

        $user = User::findOrFail(1); // Temporary user ID

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'max_users' => 'required|integer|min:2|max:10',
            'words' => 'required|string'
        ]);

        $room = Room::create([
            'name' => $request->name,
            'description' => $request->description,
            'creator_id' => User::findOrFail(1)->id, // Temporary user ID
            'max_users' => $request->max_users,
        ]);

        $words = explode(',', $request->words);
        foreach ($words as $word) {
            $room->words()->create(['word' => trim($word)]);
        }

        return redirect()->back()->with('success', 'Room created successfully!');

    }
}
