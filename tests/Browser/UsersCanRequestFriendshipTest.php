<?php

namespace Tests\Browser;

use App\Models\Friendship;
use App\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UsersCanRequestFriendshipTest extends DuskTestCase
{
    use DatabaseMigrations;
    /**
     * @test
     * @throws \Throwable
     */
    public function los_invitados_no_pueden_crear_solicitudes_de_amistad()
    {

        $recipient=factory(User::class)->create();

        $this->browse(function (Browser $browser) use($recipient) {
            $browser->visit(route('users.show',$recipient))
                ->press('@request-friendship')
                ->assertPathIs('/login')
            ;
        });
    }
    /**
     * @test
     * @throws \Throwable
     */
    public function los_usuarios_pueden_responder_solicitudes_de_amistad()
    {
        $sender=factory(User::class)->create();
        $recipient=factory(User::class)->create();

        $this->browse(function (Browser $browser) use($sender,$recipient) {
            $browser->loginAs($sender)
                    ->visit(route('users.show',$recipient))
                    ->press('@request-friendship')
                    ->waitForText('Cancelar solicitud')
                    ->assertSee('Cancelar solicitud')
                    ->visit(route('users.show',$recipient))
                    ->waitForText('Cancelar solicitud')
                    ->assertSee('Cancelar solicitud')
                    ->press('@request-friendship')
                    ->waitForText('Solicitar amistad')
                    ->assertSee('Solicitar amistad')
            ;
        });
    }
    /**
     * @test
     * @throws \Throwable
     */
    public function un_usuario_no_puede_enviarse_peticiones_de_amistad_asi_mismo()
    {
        $user=factory(User::class)->create();

        $this->browse(function (Browser $browser) use($user) {
            $browser->loginAs($user)
                ->visit(route('users.show',$user))
                ->assertMissing('@request-friendship')
                ->assertSee('Eres tú')
            ;
        });
    }
    /**
     * @test
     * @throws \Throwable
     */
    public function los_usuarios_pueden_eliminar_solicitudes_de_amistad_aceptadas()
    {
        $sender=factory(User::class)->create();
        $recipient=factory(User::class)->create();

        Friendship::create([
            'sender_id'=>$sender->id,
            'recipient_id'=>$recipient->id,
            'status'=>'accepted'
        ]);

        $this->browse(function (Browser $browser) use($sender,$recipient) {
            $browser->loginAs($sender)
                ->visit(route('users.show',$recipient))
                ->waitForText('Eliminar de mis amigos')
                ->assertSee('Eliminar de mis amigos')
                ->press('@request-friendship')
                ->waitForText('Solicitar amistad')
                ->assertSee('Solicitar amistad')
                ->visit(route('users.show',$recipient))
                ->waitForText('Solicitar amistad')
                ->assertSee('Solicitar amistad')
            ;
        });
    }
    /**
     * @test
     * @throws \Throwable
     */
    public function los_que_envian_no_pueden_eliminar_solicitudes_de_amistad_denegadas()
    {
        $sender=factory(User::class)->create();
        $recipient=factory(User::class)->create();

        Friendship::create([
            'sender_id'=>$sender->id,
            'recipient_id'=>$recipient->id,
            'status'=>'denied'
        ]);

        $this->browse(function (Browser $browser) use($sender,$recipient) {
            $browser->loginAs($sender)
                ->visit(route('users.show',$recipient))
                ->waitForText('Solicitud denegada')
                ->assertSee('Solicitud denegada')
                ->press('@request-friendship')
                ->waitForText('Solicitud denegada')
                ->assertSee('Solicitud denegada')
                ->visit(route('users.show',$recipient))
                ->waitForText('Solicitud denegada')
                ->assertSee('Solicitud denegada')
            ;
        });
    }
    /**
     * @test
     * @throws \Throwable
     */
    public function los_usuarios_pueden_aceptar_solicitudes_de_amistad()
    {
        $sender=factory(User::class)->create();
        $recipient=factory(User::class)->create();

        Friendship::create([
            'sender_id'=>$sender->id,
            'recipient_id'=>$recipient->id,
        ]);

        $this->browse(function (Browser $browser) use($sender,$recipient) {
            $browser->loginAs($recipient)
                ->visit(route('accept-friendships.index'))
                ->assertSee($sender->name)
                ->press('@accept-friendship')
                ->waitForText('son amigos')
                ->assertSee('son amigos')
                ->visit(route('accept-friendships.index'))
                ->assertSee('son amigos')
            ;
        });
    }
    /**
     * @test
     * @throws \Throwable
     */
    public function los_usuarios_pueden_denegar_solicitudes_de_amistad()
    {
        $sender=factory(User::class)->create();
        $recipient=factory(User::class)->create();

        Friendship::create([
            'sender_id'=>$sender->id,
            'recipient_id'=>$recipient->id,
        ]);

        $this->browse(function (Browser $browser) use($sender,$recipient) {
            $browser->loginAs($recipient)
                ->visit(route('accept-friendships.index'))
                ->assertSee($sender->name)
                ->press('@deny-friendship')
                ->waitForText('Solicitud denegada')
                ->assertSee('Solicitud denegada')
                ->visit(route('accept-friendships.index'))
                ->assertSee('Solicitud denegada')
            ;
        });
    }
    /**
     * @test
     * @throws \Throwable
     */
    public function los_usuarios_pueden_eliminar_solicitudes_de_amistad()
    {
        $sender=factory(User::class)->create();
        $recipient=factory(User::class)->create();

        Friendship::create([
            'sender_id'=>$sender->id,
            'recipient_id'=>$recipient->id,
        ]);

        $this->browse(function (Browser $browser) use($sender,$recipient) {
            $browser->loginAs($recipient)
                ->visit(route('accept-friendships.index'))
                ->assertSee($sender->name)
                ->press('@delete-friendship')
                ->waitForText('Solicitud eliminada')
                ->assertSee('Solicitud eliminada')
                ->visit(route('accept-friendships.index'))
                ->assertDontSee('Solicitud eliminada')
                ->assertDontSee($sender->name)
            ;
        });
    }
}
