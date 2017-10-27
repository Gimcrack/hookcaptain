<?php

namespace Tests\Unit;

use App\Call;
use App\Hook;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CallTest extends TestCase
{
    use RefreshDatabase;


    /**
     * @test
     * @expectedException \Illuminate\Database\QueryException
     */
    public function it_cannot_be_created_without_a_hook()
    {
        create( Call::class, [
            'hook_id' => null,
        ] );
    }


    /** @test */
    public function it_can_be_created_with_an_optional_payload()
    {
        $attributes = [
            'payload' => [
                'a' => 'some-value',
                'b' => 'some-other-value',
            ],
        ];

        create( Call::class, $attributes );
        create( Call::class, ['payload' => null]);


        $this->assertDatabaseHas( 'calls', [ 'payload' => json_encode( $attributes['payload'] ) ] );
        $this->assertDatabaseHas( 'calls', [ 'payload' => null ] );
        $this->assertCount(2, Call::all());
    }


    /**
     * @test
     */
    public function it_has_a_valid_webhook()
    {
        $call = create( Call::class );

        $this->assertInstanceOf( Hook::class, $call->hook );
    }



}
