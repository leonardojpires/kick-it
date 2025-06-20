@extends('layouts.fe_master')

@section('content')
<div class="min-vh-100 d-flex flex-column justify-content-between bg-dark text-white px-4 py-5">

    <header class="text-center mb-5">
        <h1 class="fw-bold display-4">🎯 Kick-it</h1>
        <p class="mt-2 text-light">Create a room, define the words to guess and have fun with your friends in this addicting and cool game!</p>
    </header>

    <div class="text-center mb-5">
        <img src="{{ asset('images/kickit-hero.svg') }}" alt="Kick-it gameplay" class="img-fluid" style="max-height: 250px;">
    </div>

    <div class="text-center">
        <a href="{{ route('rooms.index') }}" class="btn btn-warning btn-lg w-100 mb-3">🎮 Explore And Create Rooms</a>
        <a href="#como-jogar" class="btn btn-outline-light w-100">📖 How to play?</a>
    </div>
</div>
@endsection
