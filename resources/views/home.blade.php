@extends('layouts.app')

@section('content')
    <div class="shell">
        <header class="topbar">
            <a class="brand" href="{{ route('home') }}">
                <span class="mark">D</span>
                <strong>Daily</strong>
            </a>
            <div class="home-links">
                <a href="{{ route('login') }}">Sign in</a>
                <a class="button" href="{{ route('register') }}">Create account</a>
            </div>
        </header>
        <section class="hero">
            <p class="eyebrow">{{ now()->format('l, F j') }}</p>
            <h1>A quiet place for the day you just had.</h1>
            <p class="lede">Write what happened, how it felt, and what money moved. Your notes stay in your own account on this computer’s MySQL database.</p>
            <div class="actions">
                <a class="button" href="{{ route('register') }}">Start today’s log</a>
                <a class="ghost" href="{{ route('login') }}">I already have an account</a>
            </div>
        </section>
    </div>
@endsection
