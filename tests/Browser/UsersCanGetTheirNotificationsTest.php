<?php

namespace Tests\Browser;

use App\Models\Status;
use App\User;
use Illuminate\Notifications\DatabaseNotification;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UsersCanGetTheirNotificationsTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * @test
     * @throws \Throwable
     */
    public function los_usuarios_pueden_ver_sus_notifiaciones_en_la_barra_de_navegacion()
    {
        $user=factory(User::class)->create();

        $status=factory(Status::class)->create();

        $notification=factory(DatabaseNotification::class)->create([
            'notifiable_id'=> $user->id,
            'data'=>[
                'message'=>'Has recibido un like',
                'link'=> route('statuses.show',$status)
            ]
        ]);

        $this->browse(function (Browser $browser) use($user,$notification,$status) {
            $browser->loginAs($user)
                ->visit('/')
                ->resize(1024,768)
                ->click('@notifications')
                ->assertSee('Has recibido un like')
                ->click("@{$notification->id}")
                ->assertUrlIs($status->path())

                ->click('@notifications')
                ->press("@mark-as-read-{$notification->id}")
                ->waitFor("@mark-as-unread-{$notification->id}")
                ->assertMissing("@mark-as-read-{$notification->id}")

                ->press("@mark-as-unread-{$notification->id}")
                ->waitFor("@mark-as-read-{$notification->id}")
                ->assertMissing("@mark-as-unread-{$notification->id}")
            ;
        });
    }
    /**
     * @test
     * @throws \Throwable
     */
    public function los_usuarios_pueden_ver_sus_notifiaciones_de_los_likes_en_la_barra_de_navegacion_en_tiempo_real()
    {
        $user1=factory(User::class)->create();
        $user2=factory(User::class)->create();

        $status=factory(Status::class)->create(['user_id'=>$user1->id]);

        $this->browse(function (Browser $browser1,Browser $browser2) use($user1,$user2,$status) {
                $browser1->loginAs($user1)
                    ->visit('/')
                    ->resize(1024,768)
                    ;
                $browser2->loginAs($user2)
                    ->visit('/')
                    ->press('@like-btn')
                    ->pause(1000)
                ;
                $browser1->assertSeeIn('@notifications-count',1);
        });
    }
    /**
     * @test
     * @throws \Throwable
     */
    public function los_usuarios_pueden_ver_sus_notifiaciones_de_los_comentarios_en_la_barra_de_navegacion_en_tiempo_real()
    {
        $user1=factory(User::class)->create();
        $user2=factory(User::class)->create();

        $status=factory(Status::class)->create(['user_id'=>$user1->id]);

        $this->browse(function (Browser $browser1,Browser $browser2) use($user1,$user2,$status) {
            $browser1->loginAs($user1)
                ->visit('/')
                ->resize(1024,768)
            ;
            $browser2->loginAs($user2)
                ->visit('/')
                ->type('comment', 'Mi comentario')
                ->press('@comment-btn')
                ->pause(1000)
            ;
            $browser1->assertSeeIn('@notifications-count',1);
        });
    }
}
