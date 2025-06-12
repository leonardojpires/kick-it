
    @extends('layouts.fe_master')

    @section('content')

    <section class="min-vh-100 d-flex flex-column bg-dark text-white p-4">

        <header class="mb-2 text-center">
            <h1 class="display-3 fw-bold text-warning">{{ $room->name }} 🎲</h1>
            <p class="lead fst-italic">{{ $room->description }}</p>
            <p class="text-white">Created By: <span class="fw-bold text-info">{{ $room->creator->name }}</span></p>
        </header>

        <main class="flex-grow-1 d-flex flex-column justify-content-center align-items-center gap-4">

            <div class="mb-5">
                @if (Auth::user()->id === $room->creator->id)
                    <form action="{{ route('rooms.startGame', $room->id) }}" method="POST">
                        @csrf
                        <button class="btn btn-success" id="startGameButton">Start Game</button>
                    </form>
                @endif
            </div>

            <div class="card bg-gradient bg-secondary shadow-lg border-warning w-75">
                <div class="card-header text-center bg-warning text-dark fw-bold fs-5">
                    👥 Players in the Room
                </div>
                <ul class="list-group list-group-flush">
                    @forelse ($room->players->where('id', '!=', $room->creator->id) as $player)
                        <li class="list-group-item bg-dark text-white text-center">
                            🎮 {{ $player->name }}
                        </li>
                    @empty
                        <li class="list-group-item bg-dark text-white text-center">
                            🚶‍♂️ There's no one here yet...
                        </li>
                    @endforelse
                </ul>
            </div>

            <div class="alert alert-info text-center w-75 fs-5 shadow-sm" role="alert">
                ⏳ Waiting for the game to start... <br> Get ready for a lot of fun! 🎉🎮✨
            </div>

        </main>

    </section>

    <script>
        // If the user reloads or leaves the page, remove them from the room
        window.addEventListener('beforeunload', function (e) {

            const isCreator = {{ Auth::id() === $room->creator->id ? 'true' : 'false' }}

            if (!isCreator) {
                navigator.sendBeacon("{{ route('rooms.leave', $room->id) }}", new URLSearchParams({
                    _token: "{{ csrf_token() }}"
                }));
            }

        });

        /* ----------------------------- */

        const playerList = document.querySelector('.list-group');

        function updatePlayerList() {
            fetch("{{ route('rooms.show', $room->id) }}", {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(players => {
                playerList.innerHTML = '';

                if (players.length === 0) {

                    playerList.innerHTML = `
                        <li class="list-group-item bg-dark text-white text-center">
                            🚶‍♂️ There's no one here yet...
                        </li>
                    `;
                } else {
                    players.forEach(player => {
                        playerList.innerHTML += `
                            <li class="list-group-item bg-dark text-white text-center">
                                🎮 ${player.name}
                            </li>
                        `;
                    });
                }

                if (!(players.length >= 2)) {
                    document.getElementById('startGameButton').disabled = true;
                } else {
                    document.getElementById('startGameButton').disabled = false;
                }

            })
            .catch(error => console.error('Erro ao buscar jogadores:', error));
        }

        setInterval(updatePlayerList, 3000);
        updatePlayerList();

        /* ----------------------------- */

        // Sets an interval to check if the game has started
        let gameStatusInterval = setInterval(checkGameStarted, 3000);
        checkGameStarted();

        // AJAX
        function checkGameStarted() {
            fetch("{{ route('rooms.status', $room->id) }}", {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }).then(response => {
                if (!response.ok) {
                    throw new Error('Unauthorized');
                }
                return response.json();
            }).then(data => {
                if (data.is_started) {
                    // Stops the interval when game starts. This prevents further excessive resource usage
                    clearInterval(gameStatusInterval);

                    // Redirects the users to the game
                    window.location.href = "{{ route('rooms.start', $room->id) }}"
                }
            }).catch(error => {
                console.error(`Error verifying game status ${error}`);
            });
        }

    </script>

    @endsection
