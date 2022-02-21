<?php

namespace Tests\Unit;

use Tests\TestCase;

class BookingTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function BookingIndexRouteTest()
    {
        if ($this->get("/booking")) {
            $this->assertTrue(true);
        } else {

            $this->assertTrue(false);
        }

    }

    public function TestUserdDup()
    {
        $user1 = User::make([
            'name' => 'John Doe',
            'email' => 'johndoe@gmail.com',
        ]);

        $user2 = User::make([
            'name' => 'Mary Jane',
            'email' => 'maryjane@gmail.com',
        ]);

        $this->assertTrue($user1->name != $user2->name);
    }

}