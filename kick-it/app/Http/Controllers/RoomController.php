<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::all();
        $user = Auth::user();
        return view('main.index', compact('rooms', 'user'));
    }

    public function store(Request $request) {

        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'max_users' => 'required|integer|min:2|max:10',
            'words' => 'required|string'
        ]);

        $room = Room::create([
            'name' => $request->name,
            'description' => $request->description,
            'creator_id' => Auth::user()->id,
            'max_users' => $request->max_users,
        ]);

        $words = explode(',', $request->words);
        foreach ($words as $word) {
            $room->words()->create(['word' => trim($word)]);
        }

        return redirect()->back()->with('success', 'Room created successfully!');
    }

    public function show(Room $room) {

        $room->load(['creator', 'players', 'words']);
        return view('rooms.show', compact('room'));
    }

    public function join(Room $room) {
        $user = Auth::user();

        if ($room->players()->count() >= $room->max_users) {
            return redirect()->back()->with('error', 'Room is full!');
        }

        if ($room->players()->where('user_id', $user->id)->exists()) {
            return redirect()->back()->with('info', 'You are alreay in this room!');
        }

        $room->players()->attach($user->id, ['score' => 0]);

        return redirect()->route('rooms.show', $room->id)->with('success', 'You have joined the room!');
    }

    public function leave(Room $room) {
        $user = Auth::user();

        if ($room->players()->where('user_id', $user->id)->exists()) {
            $room->players()->detach($user->id);
        }

        return response()->noContent();
    }
}
