    @extends('layouts.fe_master')

    @section('content')

        <section class="min-vh-100 bg-light d-flex flex-column">

            @if (session('error'))
                <div class="alert alert-danger w-75 mx-auto">
                    {{ session('error') }}
                </div>
            @elseif (session('success'))
                <div class="alert alert-success w-75 mx-auto">
                    {{ session('success') }}
                </div>
            @endif

            <header class="p-3 bg-primary text-white text-center">
                <h1 class="h4 mb-0">Salas Disponíveis</h1>
            </header>

            <main class="flex-grow-1 overflow-auto p-3">
                <div class="row g-3">

                    <div class="text-center mb-5">
                        <span class="display-5">Hello, {{ Auth::user()->name }}</span>
                    </div>

                    @if (!$rooms->isEmpty())
                        @foreach ($rooms as $room)
                            <div class="col-12 col-sm-6 col-md-4">
                                <div class="card shadow-sm h-100">
                                    <div class="card-body d-flex flex-column">
                                        <h5 class="card-title">{{ $room->name }}</h5>
                                        <p class="card-text flex-grow-1 text-truncate" style="max-height: 3.6em;">
                                            {{ $room->description }}
                                        </p>
                                        <small class="text-muted mb-2">Created by: {{ $room->creator->name }}</small>
                                        <form action="{{ route('rooms.join', $room->id) }}" method="POST">
                                            @csrf
                                            <input type="submit" value="Join" class="btn btn-primary">
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p>No rooms</p>
                    @endif

                </div>
            </main>

            <footer class="p-3 bg-white border-top text-center">
                <button type="button" class="btn btn-success btn-lg rounded-pill shadow" data-bs-toggle="modal"
                    data-bs-target="#createRoomModal" style="width: 100%; max-width: 400px;">
                    + Create New Room
                </button>
            </footer>

            <!-- NEW ROOM MODAL -->
            <div class="modal fade" id="createRoomModal" tabindex="-1" aria-labelledby="createRoomModal" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route('rooms.store') }}" method="POST">
                            @csrf

                            <div class="modal-header">
                                <h5 class="modal-title" id="createRoomModal">Create New Room</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                            </div>


                            <div class="modal-body">

                                <div class="mb-3">
                                    <label for="roomName" class="form-label">Room Name</label>
                                    <input type="text" name="name" class="form-control" id="roomName" required autofocus>
                                </div>

                                <div class="mb-3">
                                    <label for="roomDescription" class="form-label">Description (optional)</label>
                                    <textarea class="form-control" name="description" id="roomDescription" rows="3"
                                        placeholder="Have fun trying to guess the word!"></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="roomWords" class="form-label">Words (separated by commas)</label>
                                    <textarea class="form-control" name="words" id="roomWords" rows="3" placeholder="Ex: apple, banana, orange"
                                        required></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="roomWords" class="form-label">Max Players</label>
                                    <input type="number" name="max_users" id="roomMaxPlayers" class="form-control"
                                        value="4" min="2" max="10" required>
                                </div>

                            </div>


                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success">Create</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            </div>

                        </form>

                    </div>
                </div>
            </div>

        </section>
