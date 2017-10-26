<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends TestCase
{
    use DatabaseTransactions;

    public function testUserHasSlug()
    {
        $user = $this->createUser();

        tap($user->fresh(), function ($user) {
            $this->assertNotNull($user);
            $this->assertEquals($user->slug, str_slug($user->email));
        });
    }
}
