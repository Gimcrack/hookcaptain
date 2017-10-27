<?php

namespace Tests\Feature;

use App\Hook;
use function create_array;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HookTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_get_a_listing_of_hooks()
    {
        // given we have some hooks
        tap( create_array(Hook::class,3),

            function($hooks) {
                $this->api()
                    ->get("webhooks")
                    ->response()
                    ->assertStatus(200)
                    ->assertJsonFragment($hooks[0])
                    ->assertJsonFragment($hooks[1])
                    ->assertJsonFragment($hooks[2])
                    ->assertJsonCount(3);
            }
        );
    }

    /** @test */
    public function it_can_get_a_single_hook_by_its_slug()
    {
        tap( create_array(Hook::class), function($hook) {
            $this->api()
                ->get("webhooks/{$hook['slug']}")
                ->response()
                ->assertStatus(200)
                ->assertJsonFragment($hook);
        });
    }


    /** @test */
    public function it_can_create_a_hook_with_valid_attributes()
    {
        tap( [
            'name' => 'My Hook',
            'description' => 'My Hook',
            'ttl' => 5
        ], function($attributes) {

            $this->api()
                ->post("webhooks", $attributes)
                ->response()
                    ->assertStatus(201);

            $this->assertDatabaseHas('hooks',$attributes);
        } );
    }


    /** @test */
    public function it_will_not_create_a_hook_without_a_name()
    {
        tap( [
            'name' => null,
            'description' => 'My Hook',
            'ttl' => 5
        ], function($attributes) {

            $this->api()
                ->post("webhooks", $attributes)
                ->response()
                ->assertStatus(422);

            $this->assertDatabaseMissing('hooks',$attributes);
        } );
    }


    /** @test */
    public function it_will_not_create_a_hook_without_a_description()
    {
        tap( [
            'name' => 'My Name',
            'description' => null,
            'ttl' => 5
        ], function($attributes) {

            $this->api()
                ->post("webhooks", $attributes)
                ->response()
                ->assertStatus(422);

            $this->assertDatabaseMissing('hooks',$attributes);
        } );
    }


    /** @test */
    public function it_will_not_create_a_hook_without_ttl()
    {
        tap( [
            'name' => 'My Name',
            'description' => 'My Description',
            'ttl' => null
        ], function($attributes) {

            $this->api()
                ->post("webhooks", $attributes)
                ->response()
                ->assertStatus(422);

            $this->assertDatabaseMissing('hooks',$attributes);
        } );
    }


    /** @test */
    public function it_will_not_create_a_hook_with_a_non_integer_ttl()
    {
        tap( [
            'name' => 'My Name',
            'description' => 'My Description',
            'ttl' => 'some-gibberish'
        ], function($attributes) {

            $this->api()
                ->post("webhooks", $attributes)
                ->response()
                ->assertStatus(422);

            $this->assertDatabaseMissing('hooks',$attributes);
        } );

        tap( [
            'name' => 'My Name',
            'description' => 'My Description',
            'ttl' => 12.5
        ], function($attributes) {

            $this->api()
                ->post("webhooks", $attributes)
                ->response()
                ->assertStatus(422);

            $this->assertDatabaseMissing('hooks',$attributes);
        } );
    }


    /** @test */
    public function it_can_update_an_existing_hook()
    {
        // given a hook
        $hook = create(Hook::class);

        $this->api()
            ->patch($hook->url, [
                'name' => 'New Name'
            ])
            ->response()
            ->assertStatus(202);

        $this->assertDatabaseHas('hooks',['name' => 'New Name']);
    }


    /** @test */
    public function it_can_delete_an_existing_hook()
    {
        // given a hook
        $hook = create(Hook::class);

        $this->api()
            ->delete($hook->url)
            ->response()
            ->assertStatus(202);

        $this->assertDatabaseMissing('hooks', $hook->toArray());
    }
}
