<?php

namespace Uasoft\Badaso\Tests\Feature ;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBadasoExample()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
