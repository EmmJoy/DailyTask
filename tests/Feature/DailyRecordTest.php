<?php

namespace Tests\Feature;

use App\Models\DailyRecord;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DailyRecordTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_sent_to_login_before_records(): void
    {
        $this->get(route('records.index'))->assertRedirect(route('login'));
    }

    public function test_a_person_can_register_and_save_a_record(): void
    {
        $this->post('/register', [
            'name' => 'Ada',
            'email' => 'ada@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertRedirect(route('records.index'));

        $this->post(route('records.store'), [
            'record_date' => '2026-11-04',
            'category' => 'work',
            'title' => 'Shipped the log',
            'notes' => 'Wrote the first entry.',
            'mood' => 'good',
            'amount' => '12.50',
            'direction' => 'spent',
        ])->assertRedirect(route('records.index'));

        $this->assertDatabaseHas('daily_records', [
            'title' => 'Shipped the log',
            'user_id' => User::first()->id,
            'category' => 'work',
        ]);
    }

    public function test_someone_cannot_open_another_persons_record(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();
        $record = $owner->records()->create([
            'record_date' => '2026-11-04',
            'category' => 'personal',
            'title' => 'Private note',
        ]);

        $this->actingAs($intruder)
            ->get(route('records.edit', $record))
            ->assertNotFound();
    }
}
