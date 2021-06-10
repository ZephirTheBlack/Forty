<?php

namespace Tests\Browser;

use App\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UsersCanCreateStatusesTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * @test
     * @throws \Throwable
     */
    public function los_usuarios_pueden_crear_estados()
    {
        $user= factory(User::class)->create();

        $this->browse(function (Browser $browser) use($user){
            $browser->loginAs($user)
                ->visit('/')
                ->type('body', 'Mi primer estado')
                ->press('#create-status')
                ->waitForText('Mi primer estado')
                ->assertSee('Mi primer estado')
                ->assertSee($user->name)
            ;
        });
    }
    /**
     * @test
     * @throws \Throwable
     */
    public function los_usuarios_pueden_ver_estados_en_tiempo_real()
    {
        $user1= factory(User::class)->create();
        $user2= factory(User::class)->create();

        $this->browse(function (Browser $browser1,Browser $browser2) use($user1 , $user2){
            $browser1->loginAs($user1)
                     ->visit('/');

            $browser2->loginAs($user2)
                     ->visit('/')
                     ->type('body', 'Mi primer estado')
                     ->press('#create-status')
                     ->waitForText('Mi primer estado')
                     ->assertSee('Mi primer estado')
                     ->assertSee($user2->name)
            ;

            $browser1->waitForText('Mi primer estado')
                ->assertSee('Mi primer estado')
                ->assertSee($user2->name);
        });
    }
}
