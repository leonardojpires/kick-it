@extends('layouts.fe_master')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm rounded-4">
        <div class="card-body text-center">

            <h4 class="mb-1"></h4>
            <p class="text-muted mb-2">{{ $user->name }}</p>

            <ul class="list-group list-group-flush text-start mt-3">
                <li class="list-group-item">
                    <strong>Email:</strong> {{ $user->email}}
                </li>
                <li class="list-group-item">
                    <strong>Score:</strong> {{ $user->full_score }} points
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection
