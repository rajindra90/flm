<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RegisterTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @test
     *
     * @return void
     */
    public function user_register()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('#/register')
                    ->assertSee('Sign Up')
            ->type('firstName','Test1')
            ->type('lastName','Test2')
            ->type('email','sssassdss@ssss.ss')
            ->type('password','123456')
            ->type('confirmPassword','123456')
            ->press('signUp')
            ->assertSee('User has been created Successfully.Please confirm your email.');
        });
    }
}
