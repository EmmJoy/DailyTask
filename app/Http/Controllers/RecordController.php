<?php

namespace App\Http\Controllers;

use App\Http\Requests\RecordRequest;
use App\Models\DailyRecord;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RecordController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $filters = [
            'q' => trim((string) $request->query('q', '')),
            'category' => (string) $request->query('category', ''),
            'mood' => (string) $request->query('mood', ''),
            'from' => (string) $request->query('from', ''),
            'to' => (string) $request->query('to', ''),
        ];

        $records = $user->records()
            ->when($filters['q'] !== '', function ($query) use ($filters) {
                $query->where(function ($query) use ($filters) {
                    $query->where('title', 'like', '%'.$filters['q'].'%')
                        ->orWhere('notes', 'like', '%'.$filters['q'].'%');
                });
            })
            ->when($filters['category'] !== '', fn ($query) => $query->where('category', $filters['category']))
            ->when($filters['mood'] !== '', fn ($query) => $query->where('mood', $filters['mood']))
            ->when($filters['from'] !== '', fn ($query) => $query->whereDate('record_date', '>=', $filters['from']))
            ->when($filters['to'] !== '', fn ($query) => $query->whereDate('record_date', '<=', $filters['to']))
            ->orderByDesc('record_date')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        $month = now();

        $stats = [
            'today' => $user->records()->whereDate('record_date', $month->toDateString())->count(),
            'week' => $user->records()->whereBetween('record_date', [
                $month->copy()->startOfWeek()->toDateString(),
                $month->copy()->endOfWeek()->toDateString(),
            ])->count(),
            'spent' => (float) $user->records()
                ->where('direction', 'spent')
                ->whereYear('record_date', $month->year)
                ->whereMonth('record_date', $month->month)
                ->sum('amount'),
            'earned' => (float) $user->records()
                ->where('direction', 'earned')
                ->whereYear('record_date', $month->year)
                ->whereMonth('record_date', $month->month)
                ->sum('amount'),
        ];

        return view('records.index', [
            'records' => $records,
            'filters' => $filters,
            'stats' => $stats,
            'categories' => DailyRecord::CATEGORIES,
            'moods' => DailyRecord::MOODS,
        ]);
    }

    public function create(): View
    {
        return view('records.form', [
            'record' => new DailyRecord([
                'record_date' => now()->toDateString(),
            ]),
            'categories' => DailyRecord::CATEGORIES,
            'moods' => DailyRecord::MOODS,
            'directions' => DailyRecord::DIRECTIONS,
        ]);
    }

    public function store(RecordRequest $request): RedirectResponse
    {
        $request->user()->records()->create($request->validated());

        return redirect()->route('records.index')->with('status', 'Record saved.');
    }

    public function edit(DailyRecord $record): View
    {
        $this->authorize('update', $record);

        return view('records.form', [
            'record' => $record,
            'categories' => DailyRecord::CATEGORIES,
            'moods' => DailyRecord::MOODS,
            'directions' => DailyRecord::DIRECTIONS,
        ]);
    }

    public function update(RecordRequest $request, DailyRecord $record): RedirectResponse
    {
        $this->authorize('update', $record);

        $record->update($request->validated());

        return redirect()->route('records.index')->with('status', 'Record updated.');
    }

    public function destroy(DailyRecord $record): RedirectResponse
    {
        $this->authorize('delete', $record);

        $record->delete();

        return redirect()->route('records.index')->with('status', 'Record deleted.');
    }
}
