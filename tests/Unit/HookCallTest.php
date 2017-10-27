<?php

namespace Tests\Unit;

use App\Hook;
use App\Call;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HookCallTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_can_call_a_hook()
    {
        // given a hook
        $hook = create(Hook::class);

        // call the hook
        $hook->call( $payload = ['a' => 'test'] );

        // make sure it's in the db
        $this->assertDatabaseHas('calls', [
            'hook_id' => $hook->id,
            'payload' => json_encode(['a' => 'test']),
        ]);
    }
 }
