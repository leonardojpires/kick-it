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
            <div class="mb-4 text-center" id="wordUnderscores">
                @foreach ($room->words as $word)
                    <span class="badge bg-secondary fs-5 mx-1">
                        {{ str_repeat(' _ ', strlen($word->word)) }}
                    </span>
                @endforeach
            </div>

            <div class="mb-3">
                <label for="wordInput" class="form-label">Enter your guess:</label>
                <input type="text" class="form-control" id="wordInput" placeholder="Type a word..." autocomplete="off">
            </div>

            <div id="guessedWords" class="mt-4">
                <h5>✅ Guessed Words:</h5>
                <ul class="list-group list-group-flush bg-dark" id="guessedWordsList">
                    <!-- Guessed words will be inserted here -->
                </ul>
            </div>

            <div class="mt-4 text-end">
                <button class="btn btn-secondary" disabled>Waiting for someone to win...</button>
            </div>
        </div>
    </div>
</div>

<script>
    const wordInput = document.getElementById('wordInput');
    const guessedWordsList = document.getElementById('guessedWordsList');
    const guessedWords = [];

    wordInput.addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
            const word = wordInput.value.trim().toLowerCase();
            if (word && !guessedWords.includes(word)) {
                // This is where you'd send it to the server for validation
                guessedWords.push(word);
                guessedWordsList.innerHTML += `
                    <li class="list-group-item bg-dark text-success">${word}</li>
                `;
                wordInput.value = '';
            }
        }
    });
</script>
@endsection
