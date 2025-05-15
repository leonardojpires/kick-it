@extends('layouts.fe_master')

@section('content')

<section class="d-flex justify-content-center align-items-center min-vh-100 bg-light">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12 col-sm-10 col-md-6 col-lg-4">
        <div class="card shadow-sm border-0">
          <div class="card-body">
            <h2 class="text-center mb-4">Criar Conta</h2>
            <form method="POST" action="{{ route('register') }}">
              @csrf

              <div class="mb-3">
                <label for="name" class="form-label">Nome</label>
                <input
                  type="text"
                  class="form-control"
                  id="name"
                  name="name"
                  required
                  autofocus
                >
              </div>

              <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input
                  type="email"
                  class="form-control"
                  id="email"
                  name="email"
                  required
                >
              </div>

              <div class="mb-3">
                <label for="password" class="form-label">Palavra-passe</label>
                <input
                  type="password"
                  class="form-control"
                  id="password"
                  name="password"
                  required
                >
              </div>

              <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirmar Palavra-passe</label>
                <input
                  type="password"
                  class="form-control"
                  id="password_confirmation"
                  name="password_confirmation"
                  required
                >
              </div>

              <button type="submit" class="btn btn-success w-100">Registar</button>

              <div class="text-center mt-3">
                <a href="{{ route('login') }}" class="small">Já tens conta? Entra aqui</a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection 
