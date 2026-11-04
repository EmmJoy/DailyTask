@extends('layouts.app')

@section('content')
    <div class="auth-wrap">
        <form class="panel auth-card" method="POST" action="{{ route('register') }}">
            @csrf
            <p class="eyebrow">New journal</p>
            <h1 style="font-size: 2.4rem; margin-bottom: 18px;">Create your account</h1>
            @include('partials.errors')
            <div class="form-grid" style="grid-template-columns: 1fr;">
                <label>Name
                    <input type="text" name="name" value="{{ old('name') }}" required autofocus>
                </label>
                <label>Email
                    <input type="email" name="email" value="{{ old('email') }}" required>
                </label>
                <label>Password
                    <input type="password" name="password" required>
                </label>
                <label>Confirm password
                    <input type="password" name="password_confirmation" required>
                </label>
            </div>
            <div class="form-actions">
                <a class="ghost" href="{{ route('login') }}">Sign in</a>
                <button class="button" type="submit">Create account</button>
            </div>
        </form>
    </div>
@endsection
