<?php

namespace Tests\Browser;

use \App\User;
use App\Models\Status;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UsersCanLikesStatusesTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * @test
     * @throws \Throwable
     */
    public function usuarios_pueden_dar_like_y_dislike_a_los_estados()
    {
        $user= factory(User::class)->create();
        $status= factory(Status::class)->create();

        $this->browse(function (Browser $browser) use ($user, $status){
            $browser->loginAs($user)
                ->visit('/')
                ->waitForText($status->body)
                ->assertSeeIn('@likes-count',0)
                ->press('@like-btn')
                ->waitForText('Te Gusta')
                ->assertSee('Te Gusta')
                ->assertSeeIn('@likes-count',1)

                ->press('@like-btn')
                ->waitForText('Me Gusta')
                ->assertSee('Me Gusta')
                ->assertSeeIn('@likes-count',0)
            ;
        });
    }

    /**
     * @test
     * @throws \Throwable
     */
    public function usuarios_pueden_ver_likes_y_unlikes_en_tiempo_real()
    {
        $user= factory(User::class)->create();
        $status= factory(Status::class)->create();

        $this->browse(function (Browser $browser1, Browser $browser2) use ($user, $status){
            $browser1->visit('/');

            $browser2->loginAs($user)
                ->visit('/')
                ->waitForText($status->body)
                ->assertSeeIn('@likes-count',0)
                ->press('@like-btn')
                ->waitForText('Te Gusta')
            ;
            $browser1->assertSeeIn('@likes-count',1);

            $browser2->press('@like-btn')
                ->waitForText('Me Gusta')
            ;
            $browser1->assertSeeIn('@likes-count',0);
        });
    }



    /**
     * @test
     * @throws \Throwable
     */
    public function invitados_no_pueden_dar_like_y_dislike_a_los_estados()
    {

        $status= factory(Status::class)->create();

        $this->browse(function (Browser $browser) use ( $status){
            $browser
                ->visit('/')
                ->waitForText($status->body)
                ->press('@like-btn')
                ->assertPathIs('/login')
            ;
        });
    }
}
