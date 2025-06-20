@extends('layouts.fe_master')

@section('content')

<section class="d-flex justify-content-center align-items-center min-vh-100 bg-light">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12 col-sm-10 col-md-6 col-lg-4">
        <div class="card shadow-sm border-0">
          <div class="card-body">
            <h2 class="text-center mb-4">Log In</h2>
            <form method="POST" action="{{ route('auth.authenticate') }}">
              @csrf

              <div class="mb-3">
                <label for="email" class="form-label">E-Mail</label>
                <input
                  type="email"
                  class="form-control"
                  id="email"
                  name="email"
                  required
                  autofocus
                >
              </div>

              <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input
                  type="password"
                  class="form-control"
                  id="password"
                  name="password"
                  required
                >
              </div>

              <div class="mb-3 form-check">
                <input
                  type="checkbox"
                  class="form-check-input"
                  id="remember"
                  name="remember"
                >
              </div>

              <button type="submit" class="btn btn-primary w-100">Sign In</button>

              <div class="text-center mt-3">
                <a href="{{ route('register') }}" class="small">Sign Up</a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
