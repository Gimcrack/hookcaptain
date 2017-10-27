<?php

namespace Tests\Feature;

use App\Call;
use App\Hook;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CallTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_can_get_a_listing_of_all_calls()
    {
        // given some calls
        $calls = create_array(Call::class, 3);

        // try to get them
        $this->api()
            ->get('calls')
            ->response()
            ->assertStatus(200)
            ->assertJsonCount(3);
    }
    
    /** @test */
    public function it_can_get_a_particular_call()
    {
        $call = create(Call::class);

        $this->api()
            ->get("calls/{$call->id}")
            ->response()
            ->assertStatus(200)
            ->assertJsonFragment($call->toArray());
    }
    
    /** @test */
    public function it_can_store_an_anonymous_call_and_create_a_new_hook_for_it()
    {
        $this->withoutExceptionHandling();

        $payload = ['a' => 'test'];

        $this->api()
            ->post("webhooks/_call", $payload)
            ->response()
            ->assertStatus(201);
    }

    /** @test */
    public function it_can_locate_the_correct_webhook_by_recipient()
    {
        $this->withoutExceptionHandling();

        $hook = create(Hook::class);

        $this->api()
            ->post("webhooks/_call", [
                'a' => 'test',
                'recipient' => $hook->email
            ])
            ->response()
            ->assertStatus(201)
            ->assertJsonFragment([
                'hook' => $hook->toArray()
            ]);
    }

    /** @test */
    public function it_can_locate_the_correct_webhook_by_subject()
    {

        $hook = create(Hook::class);

        $this->api()
            ->post("webhooks/_call", [
                'a' => 'test',
                'recipient' => 'webhooks@webhooks.jeremybloomstrom.com',
                'subject' => "Alert for db job [{$hook->slug}]"
            ])
            ->response()
            ->assertStatus(201)
            ->assertJsonFragment([
                'hook' => $hook->toArray()
            ]);
    }


    /** @test */
    public function it_can_locate_the_correct_webhook_by_body()
    {

        $hook = create(Hook::class);

        $this->api()
            ->post("webhooks/_call", [
                'a' => 'test',
                'recipient' => 'webhooks@webhooks.jeremybloomstrom.com',
                'body-plain' => "Alert for db job [{$hook->slug}]"
            ])
            ->response()
            ->assertStatus(201)
            ->assertJsonFragment([
                'hook' => $hook->toArray()
            ]);
    }

    /** @test */
    public function it_can_update_a_call()
    {
        $call = create(Call::class);

        $this->api()
            ->patch("calls/{$call->id}",[
                'a' => 'new-payload'
            ])
            ->response()
            ->assertStatus(202);

        $this->assertDatabaseHas('calls',[
            'payload' => json_encode(['a' => 'new-payload'])
        ]);
    }

    /** @test */
    public function it_can_delete_a_call()
    {
        $call = create(Call::class);

        $this->api()
            ->delete("calls/{$call->id}")
            ->response()
            ->assertStatus(202);

        $this->assertDatabaseMissing('calls', $call->toArray());
    }
    

 }
