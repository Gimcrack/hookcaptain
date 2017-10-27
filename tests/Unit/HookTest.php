<?php

namespace Tests\Unit;

use App\Call;
use App\Hook;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HookTest extends TestCase
{
    use RefreshDatabase;


    /**
     * @test
     * @expectedException \Illuminate\Database\QueryException
     */
    public function it_has_a_name()
    {
        create( Hook::class, [
            'name' => null,
        ] );
    }


    /**
     * @test
     * @expectedException \Illuminate\Database\QueryException
     */
    public function it_has_a_description()
    {
        create( Hook::class, [
            'description' => null,
        ] );
    }


    /**
     * @test
     * @expectedException \Illuminate\Database\QueryException
     */
    public function it_has_a_ttl()
    {
        create( Hook::class, [
            'ttl' => null,
        ] );
    }


    /** @test */
    public function it_can_be_created_with_valid_attributes()
    {
        tap( [
            'name' => 'hook-name',
            'description' => 'hook-description',
            'ttl' => 5,
        ], function ( $attributes ) {
            create( Hook::class, $attributes );

            $this->assertDatabaseHas( 'hooks', $attributes );
        } );
    }


    /** @test */
    public function it_generates_a_random_slug_for_a_new_instance()
    {
        $hook = create( Hook::class );

        $this->assertTrue( strlen( $hook->slug ) > 6 );
    }


    /** @test */
    public function it_generates_different_slugs_for_different_hooks()
    {
        $hook1 = create( Hook::class );
        $hook2 = create( Hook::class );

        $this->assertNotEquals( $hook1->slug, $hook2->slug );
    }


    /** @test */
    public function it_can_get_the_hook_url()
    {
        $hook = create( Hook::class );

        $this->assertEquals( "webhooks/{$hook->slug}", $hook->url );
    }



    /** @test */
    public function it_can_get_the_call_hook_url()
    {
        $hook = create( Hook::class );

        $this->assertEquals( "webhooks/{$hook->slug}/_call", $hook->call_url );
    }
    
    /** @test */
    public function it_can_get_the_hook_email_address()
    {
        $hook = create(Hook::class);
        $mail_domain = config('mail.webhooks.domain');

        $this->assertEquals("{$hook->slug}@{$mail_domain}", $hook->email);
    }

    /** @test */
    public function it_can_get_the_calls_of_a_hook()
    {
        // given a hook
        $hook = create(Hook::class);

        // make some calls
        create(Call::class, ['hook_id' => $hook->id], 3);

        // try to get the calls
        $calls = Call::ofHook($hook);

        $this->assertCount(3, $hook->calls);
        $this->assertInstanceOf(Call::class, $hook->calls->first());
    }

    /** @test */
    public function it_can_be_found_by_slug()
    {
        $hook = create(Hook::class);

        $found = Hook::findBySlug($hook->slug);

        $this->assertTrue( $hook->is($found) );
    }

    /** @test */
    public function it_can_generate_a_new_hook_with_defaults()
    {
        $hook = Hook::defaults();

        $this->assertEquals('New Webhook', $hook->name);
    }


    public function it_can_get_the_last_call_date()
    {
        $hook = create(Hook::class);

        $this->assertNull($hook->last_called_at);

        $hook->call();

        $this->assertNotNull($hook->last_called_at);
    }

}
