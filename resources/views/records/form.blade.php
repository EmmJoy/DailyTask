@extends('layouts.app')

@php($editing = $record->exists)

@section('content')
    <section class="hero">
        <p class="eyebrow">{{ $editing ? 'Revise' : 'New entry' }}</p>
        <h1>{{ $editing ? 'Edit this record' : 'What should today remember?' }}</h1>
    </section>

    <form class="panel" method="POST" action="{{ $editing ? route('records.update', $record) : route('records.store') }}">
        @csrf
        @if ($editing)
            @method('PUT')
        @endif
        @include('partials.errors')
        <div class="form-grid">
            <label>Date
                <input type="date" name="record_date" value="{{ old('record_date', optional($record->record_date)->toDateString()) }}" required>
            </label>
            <label>Category
                <select name="category" required>
                    @foreach ($categories as $value => $label)
                        <option value="{{ $value }}" @selected(old('category', $record->category) === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </label>
            <label class="span-2">Title
                <input type="text" name="title" value="{{ old('title', $record->title) }}" maxlength="120" required placeholder="A short name for this moment">
            </label>
            <label class="span-2">Notes
                <textarea name="notes" maxlength="5000" placeholder="What happened, what you noticed, what you want to remember">{{ old('notes', $record->notes) }}</textarea>
            </label>
            <label>Mood
                <select name="mood">
                    <option value="">No mood</option>
                    @foreach ($moods as $value => $label)
                        <option value="{{ $value }}" @selected(old('mood', $record->mood) === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </label>
            <label>Money
                <select name="direction">
                    <option value="">No amount</option>
                    @foreach ($directions as $value => $label)
                        <option value="{{ $value }}" @selected(old('direction', $record->direction) === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </label>
            <label class="span-2">Amount
                <input type="number" name="amount" min="0" max="999999.99" step="0.01" value="{{ old('amount', $record->amount) }}" placeholder="Leave blank if this is not about money">
            </label>
        </div>
        <div class="form-actions">
            <a class="ghost" href="{{ route('records.index') }}">Cancel</a>
            <button class="button" type="submit">{{ $editing ? 'Save changes' : 'Save record' }}</button>
        </div>
    </form>
@endsection
