<?php

namespace Tests\Browser;

use App\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserCanLoginTest extends DuskTestCase
{
    use DatabaseMigrations;
    /**
     * A Dusk test example.
     *
     * @test
     * @throws \Throwable
     */
    public function usuarios_registrados_pueden_hacer_login()
    {
        factory(User::class)->create([
            'email'=> 'kcrodsua21@gmail.com'
        ]);

        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('email', 'kcrodsua21@gmail.com')
                ->type('password', 'secret')
                ->press('@login-btn')
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
    public function los_usuarios_no_pueden_logearse_con_informacion_invalida()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('email','')
                ->press('@login-btn')
                ->assertPathIs('/login')
                ->assertPresent('@validation-errors')
            ;
        });
    }
}
