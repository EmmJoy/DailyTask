<?php

namespace App\Policies;

use App\Models\DailyRecord;
use App\Models\User;

class DailyRecordPolicy
{
    public function update(User $user, DailyRecord $record): bool
    {
        return $record->user_id === $user->id;
    }

    public function delete(User $user, DailyRecord $record): bool
    {
        return $record->user_id === $user->id;
    }
}
