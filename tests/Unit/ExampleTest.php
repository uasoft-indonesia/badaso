<?php

namespace Uasoft\Badaso\Tests\Unit;

use Uasoft\Badaso\Models\User;
use Uasoft\Badaso\Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBadasoExample()
    {

        User::create([
            'name' => 'hello',
            'email' => 'hello@world.com',
            'password' => 'hello@world.com',
        ]);

        dd(User::all()) ;

        $this->assertTrue(true);
    }
}
