@extends('layouts.app')

@section('content')
    <div class="auth-shell">
        <div class="panel auth-card compact">
            <h1>Login</h1>
            <form class="stack" method="post" action="{{ route('login') }}">
                @csrf
                <label>Email <input type="email" name="email" value="{{ old('email') }}" required></label>
                <label>Password <input type="password" name="password" required></label>
                <label style="display:flex;gap:8px;align-items:center;font-weight:400"><input style="width:auto" type="checkbox" name="remember"> Remember me</label>
                <button>Login</button>
            </form>
        </div>
    </div>
@endsection
