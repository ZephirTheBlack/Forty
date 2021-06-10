<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UsersCanRegisterTest extends DuskTestCase
{

    use DatabaseMigrations;
    /**
     * A Dusk test example.
     *
     * @test void
     * @throws \Throwable
     */
    public function los_usuarios_pueden_registrarse()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register')
                    ->type('name','KevinRodriguez')
                    ->type('first_name','Kevin')
                    ->type('last_name','Rodriguez')
                    ->type('email','risas@gmail.com')
                    ->type('password','secret')
                    ->type('password_confirmation','secret')
                    ->press('@register-btn')
                    ->assertPathIs('/')
                    ->assertAuthenticated()
            ;
        });
    }
    /**
     * A Dusk test example.
     *
     * @test void
     * @throws \Throwable
     */
    public function los_usuarios_no_pueden_registrarse_con_informacion_invalida()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register')
                ->type('name','')
                ->press('@register-btn')
                ->assertPathIs('/register')
                ->assertPresent('@validation-errors')
            ;
        });
    }
}
