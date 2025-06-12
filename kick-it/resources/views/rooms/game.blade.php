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
                                {{ str_repeat(' _ ', strlen($word->word)) }} <span>{{ "(" . strlen($word->word) . ")" }}</span>
                            </span>
                        </div>
                        <input type="text" name="guess_{{ $index }}" class="form-control mb-2" placeholder="Type your guess..." autocomplete="off" data-word="{{ strtolower($word->word) }}">
                        <button type="button" class="btn btn-primary validate-btn" data-index="{{ $index }}">Guess</button>
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

<script>
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

            if (guess === correctWord ) {
                feedback.textContent = '✅ Correct!';
                feedback.style.color = 'green';
                input.disabled = true;
                button.disabled = true;

                const listItem = document.createElement('li');
                listItem.classList.add('list-group-item', 'bg-dark', 'text-white');
                wordsGuessedList.appendChild(listItem);
                listItem.textContent = correctWord;

            }
            else {
                feedback.textContent = '❌ Wrong, try again!';
                feedback.style.color = 'red';
            }
        });
    });
</script>
@endsection
