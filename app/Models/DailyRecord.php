<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['record_date', 'category', 'title', 'notes', 'mood', 'amount', 'direction'])]
class DailyRecord extends Model
{
    public const CATEGORIES = [
        'personal' => 'Personal',
        'work' => 'Work',
        'health' => 'Health',
        'finance' => 'Finance',
        'learning' => 'Learning',
    ];

    public const MOODS = [
        'great' => 'Great',
        'good' => 'Good',
        'okay' => 'Okay',
        'low' => 'Low',
        'rough' => 'Rough',
    ];

    public const DIRECTIONS = [
        'spent' => 'Spent',
        'earned' => 'Earned',
    ];

    protected function casts(): array
    {
        return [
            'record_date' => 'date',
            'amount' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where($field ?? $this->getRouteKeyName(), $value)
            ->where('user_id', auth()->id())
            ->firstOrFail();
    }

    public function categoryLabel(): string
    {
        return self::CATEGORIES[$this->category] ?? $this->category;
    }

    public function moodLabel(): ?string
    {
        return $this->mood ? (self::MOODS[$this->mood] ?? $this->mood) : null;
    }
}
