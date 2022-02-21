<?php

namespace Tests\Unit;

use Tests\TestCase;

class ExpireAtTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function ExpireAt()
    {
        if ($this->get("/booking")) {
            $this->assertTrue(true);
        } else {

            $this->assertTrue(false);
        }

    }

}