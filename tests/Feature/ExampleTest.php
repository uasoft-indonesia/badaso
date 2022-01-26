<?php

namespace Uasoft\Badaso\Tests\Feature;

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
