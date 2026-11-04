@extends('layouts.app')

@section('content')
    <div class="auth-wrap">
        <form class="panel auth-card" method="POST" action="{{ route('login') }}">
            @csrf
            <p class="eyebrow">Welcome back</p>
            <h1 style="font-size: 2.4rem; margin-bottom: 18px;">Sign in</h1>
            @include('partials.errors')
            <div class="form-grid" style="grid-template-columns: 1fr;">
                <label>Email
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus>
                </label>
                <label>Password
                    <input type="password" name="password" required>
                </label>
                <label class="check">
                    <input type="checkbox" name="remember" value="1" @checked(old('remember'))>
                    Keep me signed in
                </label>
            </div>
            <div class="form-actions">
                <a class="ghost" href="{{ route('register') }}">Create account</a>
                <button class="button" type="submit">Sign in</button>
            </div>
        </form>
    </div>
@endsection
