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
        $rooms = Room::with(['players', 'creator'])->get();

        $rooms->each(function ($room) {
            $room->playersWithoutCreator = $room->players->where('id', '!=', $room->creator_id);
        });

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

        $room->players()->attach($user->id, ['score' => 0]);

        $words = explode(',', $request->words);
        foreach ($words as $word) {
            $room->words()->create(['word' => trim($word)]);
        }

        return redirect()->route('rooms.show', $room);
    }

    public function show(Room $room) {

        $room->load(['creator', 'players', 'words']);
        $user = Auth::user();

        /* AJAX */
        if (request()->ajax()) {
            $players = $room->players->filter(fn ($player) => $player->id !== $room->creator_id)->values();
            return response()->json($players);
        }

        if (!($room->players->contains('id', $user->id) || $room->creator_id === $user->id)) {
            return redirect()->route('rooms.index');
        }

        return view('rooms.show', compact('room'));
    }

    public function join(Room $room) {
        $user = Auth::user();

        if ($room->players()->where('user_id', $user->id)->exists()) {
            return redirect()->back()->with('info', 'You are already in this room!');
        }

        if ($room->players()->count() >= $room->max_users) {
            return redirect()->back()->with('error', 'Room is full!');
        }

        $room->players()->attach($user->id, ['score' => 0]);

        /* DEBBUGING */
       // \Log::debug('User joined room', ['user_id' => $user->id, 'room_id' => $room->id]);

        return redirect()->route('rooms.show', $room->id)->with('success', 'You have joined the room!');
    }

    public function leave(Room $room) {
        $user = Auth::user();

        if ($room->players()->where('user_id', $user->id)->exists()) {
            $room->players()->detach($user->id);
        }

        return response()->noContent();
    }

    public function delete(Room $room) {
        $room->delete();
        return redirect()->back();
    }

    public function startGame(Room $room) {

        if (Auth::id() !== $room->creator_id) {
            abort(403, 'Only the room creator can start the game!');
        }

        $room->is_started = true;
        $room->save();

        return redirect()->route('rooms.start', $room->id);
    }

    public function start(Room $room) {

        $user = Auth::user();

        $room->load('players');

        $isPlayer = $room->players()->where('user_id', $user->id)->exists();

        if (!($isPlayer || $room->creator_id === $user->id)) {
            return redirect()->route('rooms.index');
        }

        $room->load('players', 'words');

        return view('rooms.game', compact('room'));
    }

    public function status(Room $room) {
        $user = Auth::user();
        $room->load('players', 'words', 'winner');

        if (!($room->players->contains('id', $user->id) || $room->creator_id === $user->id)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json([
            'is_started' => $room->is_started,
            'winner_id' => $room->winner_id,
            'winner_name' => optional($room->winner)->name,
        ]);
    }

    public function win(Room $room) {
        $user = Auth::user();
        $room->load('players', 'words', 'winner');

        if (!($room->players->contains('id', $user->id) || $room->creator_id === $user->id)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if ($room->winner_id !== null) {
            return response()->json(['error' => 'Game already finished!'], 403);
        }

        $room->winner_id = $user->id;
        $room->is_started = false;
        $room->save();

        $room->players()->updateExistingPivot($user->id, [
            'score' => \DB::raw('score + 1')
        ]);

        return response()->json(['success' => true, 'winner_name' => $user->name]);
    }

}
