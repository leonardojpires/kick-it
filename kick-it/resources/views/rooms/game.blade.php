@extends('layouts.fe_master')

@section('content')
    <div class="container mt-5">
        <div class="card bg-dark text-white">
            <div class="card-header text-center">
                <h3>🎮 Game On!</h3>
                <h4>{{ $room->name }}</h4>
                <p>Type the words as fast as you can to win!</p>
            </div>
            <div class="card-body">
                <form id="gameForm">
                    @foreach ($room->words as $index => $word)
                        <div class="mb-4">
                            <div class="fs-4 text-center mb-1">
                                <span class="badge bg-secondary fs-5 mb-1">
                                    {{ str_repeat(' _ ', strlen($word->word)) }}
                                    <span>{{ '(' . strlen($word->word) . ')' }}</span>
                                </span>
                            </div>
                            <input type="text" name="guess_{{ $index }}" class="form-control mb-2"
                                placeholder="Type your guess..." autocomplete="off"
                                data-word="{{ strtolower($word->word) }}">
                            <button type="button" class="btn btn-primary validate-btn"
                                data-index="{{ $index }}">Guess</button>
                            <div class="mt-2" id="feedback_{{ $index }}"></div>
                        </div>
                    @endforeach
                </form>

                <div id="guessedWords" class="mt-4">
                    <h5>✅ Guessed Words:</h5>
                    <ul class="list-group list-group-flush bg-dark" id="guessedWordsList">

                    </ul>
                </div>

                <div class="mt-4 text-end">
                    <button class="btn btn-secondary" disabled>Waiting for someone to win...</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="winnerModal" tabindex="-1" aria-labelledby="winnerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header">
                    <h5 class="modal-title" id="winnerModalLabel">🏆 You won!</h5>
                </div>
                <div class="modal-body">
                    You guessed all the words! Great job.
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="loserModal" tabindex="-1" aria-labelledby="loserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header">
                    <h5 class="modal-title" id="loserModalLabel">😞 Game Over</h5>
                </div>
                <div class="modal-body">
                    You lost! Better luck next time!
                </div>
            </div>
        </div>
    </div>

    <script>
        let guessedCount = 0;
        const totalWords = {{ count($room->words) }};
        const roomWinnerId = {{ $room->winner_id ?? null }};
        const currentUserId = {{ Auth::user()->id }};

        document.addEventListener('DOMContentLoaded', () => {
            if (roomWinnerId && roomWinnerId !== currentUserId) {
                document.querySelectorAll('.validate-btn').forEach(btn => btn.disabled = true);
                document.querySelectorAll('input[type=text]').forEach(input => input.disabled = true);

                const loserModal = new bootstrap.Modal(document.getElementById('loserModal'));
                loserModal.show();
            }


            document.querySelectorAll('.validate-btn').forEach(button => {
                button.addEventListener('click', () => {
                    const index = button.getAttribute('data-index');
                    const input = document.querySelector(`input[name="guess_${index}"]`);
                    const feedback = document.getElementById(`feedback_${index}`);
                    const guess = input.value.trim().toLowerCase();
                    const correctWord = input.getAttribute('data-word');
                    const wordsGuessedList = document.getElementById('guessedWordsList');

                    if (guess === '') {
                        feedback.textContent = 'Please enter a guess!';
                        feedback.style.color = 'orange';
                        return;
                    }

                    if (guess === correctWord) {
                        feedback.textContent = '✅ Correct!';
                        feedback.style.color = 'green';
                        input.disabled = true;
                        button.disabled = true;

                        guessedCount++;

                        const listItem = document.createElement('li');
                        listItem.classList.add('list-group-item', 'bg-dark', 'text-white');
                        listItem.innerHTML = `<span>${correctWord}</span>`;
                        wordsGuessedList.appendChild(listItem);

                        if (guessedCount === totalWords) {
                            fetch("{{ route('rooms.win', $room->id) }}", {
                                    method: "POST",
                                    headers: {
                                        "Content-Type": "application/json",
                                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                                    },
                                    body: JSON.stringify({})
                                }).then(res => res.json())
                                .then(data => {
                                    if (data.success) {
                                        const winnerModal = new bootstrap.Modal(document
                                            .getElementById(
                                                'winnerModal'));
                                        winnerModal.show();

                                        setTimeout(() => {
                                            window.location.href =
                                                "{{ route('rooms.index') }}"
                                        }, 5000);
                                    }
                                })
                        }


                    } else {
                        feedback.textContent = '❌ Wrong, try again!';
                        feedback.style.color = 'red';
                    }
                });
            });
        });
    </script>
@endsection
