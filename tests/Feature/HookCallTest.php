<?php

namespace Tests\Feature;

use App\Call;
use App\Hook;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HookCallTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_can_get_all_the_calls_of_a_hook()
    {
        $hook = create(Hook::class);

        $call1 = $hook->call(['a' => 'test-1']);
        $call2 = $hook->call(['b' => 'test-2']);
        $call3 = $hook->call(['c' => 'test-3']);

        $this->api()
            ->get($hook->url . "/calls")
            ->response()
            ->assertStatus(200)
            ->assertJsonFragment(['payload' => ['a' => 'test-1']])
            ->assertJsonCount(3);
    }



    /** @test */
    public function it_creates_a_call_record_when_a_webhook_is_posted()
    {
        $hook = create(Hook::class);

        $this->api()
            ->post($hook->call_url, ['a' => 'test'])
            ->response()
            ->assertStatus(201);

        $this->assertDatabaseHas('calls', [
            'hook_id' => $hook->id,
            'payload' => json_encode(['a' => 'test'])
        ]);
    }


    /** @test */
    public function it_creates_a_call_record_when_a_webhook_is_getted()
    {
        $hook = create(Hook::class);

        $this->api()
            ->get($hook->call_url, ['a' => 'test'])
            ->response()
            ->assertStatus(201);

        $this->assertDatabaseHas('calls', [
            'hook_id' => $hook->id,
            'payload' => json_encode(['a' => 'test'])
        ]);
    }


    /** @test */
    public function it_creates_a_call_record_when_a_webhook_is_putted()
    {
        $hook = create(Hook::class);

        $this->api()
            ->put($hook->call_url, ['a' => 'test'])
            ->response()
            ->assertStatus(201);

        $this->assertDatabaseHas('calls', [
            'hook_id' => $hook->id,
            'payload' => json_encode(['a' => 'test'])
        ]);
    }


    /** @test */
    public function it_creates_a_call_record_when_a_webhook_is_patched()
    {
        $hook = create(Hook::class);

        $this->api()
            ->patch($hook->call_url, ['a' => 'test'])
            ->response()
            ->assertStatus(201);

        $this->assertDatabaseHas('calls', [
            'hook_id' => $hook->id,
            'payload' => json_encode(['a' => 'test'])
        ]);
    }


    /** @test */
    public function it_creates_a_call_record_when_a_webhook_is_deleted()
    {
        $hook = create(Hook::class);

        $this->api()
            ->delete($hook->call_url, ['a' => 'test'])
            ->response()
            ->assertStatus(201);

        $this->assertDatabaseHas('calls', [
            'hook_id' => $hook->id,
            'payload' => json_encode(['a' => 'test'])
        ]);
    }
 }
