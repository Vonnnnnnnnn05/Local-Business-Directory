@extends('layouts.app')

@section('content')
    <div class="auth-shell">
        <div class="panel auth-card">
            <h1>Create Account</h1>
            <form class="stack" method="post" action="{{ route('register') }}">
                @csrf
                <div class="form-grid">
                    <label>Name <input name="name" value="{{ old('name') }}" required></label>
                    <label>Email <input type="email" name="email" value="{{ old('email') }}" required></label>
                    <label>Phone <input name="phone" value="{{ old('phone') }}"></label>
                    <label>Account type
                        <select name="role" required>
                            <option value="user" @selected(old('role') === 'user')>User</option>
                            <option value="owner" @selected(old('role') === 'owner')>Business Owner</option>
                        </select>
                    </label>
                    <label>Password <input type="password" name="password" required></label>
                    <label>Confirm password <input type="password" name="password_confirmation" required></label>
                </div>
                <button>Register</button>
            </form>
        </div>
    </div>
@endsection
