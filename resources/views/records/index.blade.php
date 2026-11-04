@extends('layouts.app')

@section('content')
    <section class="hero">
        <p class="eyebrow">{{ now()->format('l') }}</p>
        <h1>{{ now()->format('F j') }}</h1>
        <p class="lede">{{ $stats['today'] === 0 ? 'Nothing written for today yet.' : $stats['today'].' '.str('entry')->plural($stats['today']).' saved for today.' }}</p>
    </section>

    <section class="stats">
        <article class="stat"><span>Today</span><strong>{{ $stats['today'] }}</strong></article>
        <article class="stat"><span>This week</span><strong>{{ $stats['week'] }}</strong></article>
        <article class="stat"><span>Spent this month</span><strong class="spent">{{ number_format($stats['spent'], 2) }}</strong></article>
        <article class="stat"><span>Earned this month</span><strong class="earned">{{ number_format($stats['earned'], 2) }}</strong></article>
    </section>

    <form class="panel filters" method="GET" action="{{ route('records.index') }}">
        <label>Search
            <input type="search" name="q" value="{{ $filters['q'] }}" placeholder="Title or notes">
        </label>
        <label>Category
            <select name="category">
                <option value="">All</option>
                @foreach ($categories as $value => $label)
                    <option value="{{ $value }}" @selected($filters['category'] === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </label>
        <label>Mood
            <select name="mood">
                <option value="">All</option>
                @foreach ($moods as $value => $label)
                    <option value="{{ $value }}" @selected($filters['mood'] === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </label>
        <label>From
            <input type="date" name="from" value="{{ $filters['from'] }}">
        </label>
        <label>To
            <input type="date" name="to" value="{{ $filters['to'] }}">
        </label>
        <button class="button" type="submit">Filter</button>
    </form>

    @forelse ($records as $record)
        @if ($loop->first || $records[$loop->index - 1]->record_date->toDateString() !== $record->record_date->toDateString())
            <h2 class="day">{{ $record->record_date->format('l, F j, Y') }}</h2>
        @endif
        <article class="record">
            <header>
                <div>
                    <h2>{{ $record->title }}</h2>
                    <div class="meta">
                        <span class="pill">{{ $record->categoryLabel() }}</span>
                        @if ($record->mood)
                            <span class="pill mood-{{ $record->mood }}">{{ $record->moodLabel() }}</span>
                        @endif
                        @if ($record->amount !== null)
                            <span class="pill money-{{ $record->direction }}">{{ $record->direction === 'spent' ? 'Spent' : 'Earned' }} {{ number_format((float) $record->amount, 2) }}</span>
                        @endif
                    </div>
                </div>
            </header>
            @if ($record->notes)
                <p class="notes">{{ $record->notes }}</p>
            @endif
            <footer>
                <a href="{{ route('records.edit', $record) }}">Edit</a>
                <form class="inline" method="POST" action="{{ route('records.destroy', $record) }}" onsubmit="return confirm('Delete this record?')">
                    @csrf
                    @method('DELETE')
                    <button class="linkish" type="submit">Delete</button>
                </form>
            </footer>
        </article>
    @empty
        <div class="panel empty">
            <p>No records match this view.</p>
            <a class="button" href="{{ route('records.create') }}">Write the first one</a>
        </div>
    @endforelse

    {{ $records->links() }}
@endsection
