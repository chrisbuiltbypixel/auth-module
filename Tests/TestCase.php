<?php

namespace Tests;

use Modules\AdminUser\Entities\AdminUser;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * set up a user and select a mosque account
     */
    public function actAsUser()
    {
        $user = factory(User::class)->create();

        // log in the user
        return Passport::actingAs(
            $user,
            [],
            'user_api'
        );
    }

    public function actAsAdminUser()
    {
        $user = factory(AdminUser::class)->create();

        // log in the user
        return Passport::actingAs(
            $user,
            [],
            'admin_api'
        );
    }
}
