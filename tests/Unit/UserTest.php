<?php

namespace Tests\Unit;

use App\Models\Friendship;
use App\Models\Status;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function el_nombre_de_la_ruta_debe_ser_name()
    {
        $user= factory(User::class)->make();

        $this->assertEquals('name' , $user->getRouteKeyName());
    }

    /**
     * @test
     */
    public function un_usuario_tiene_el_link_a_su_perfil()
    {
        $user= factory(User::class)->make();

        $this->assertEquals(route('users.show',$user),$user->link());
    }

    /**
     * @test
     */
    public function un_usuario_tiene_un_avatar()
    {
        $user= factory(User::class)->make();

        $this->assertEquals('/storage/default-avatar.jpg',$user->avatar());
        $this->assertEquals('/storage/default-avatar.jpg',$user->avatar);
    }

    /**
     * @test
     */

    public function un_usuario_tiene_muchos_estados(){
        $user= factory(User::class)->create();

        factory(Status::class)->create(['user_id'=>$user->id]);

        $this->assertInstanceOf(status::class, $user->statuses->first());
    }

    /**
     * @test
     */

    public function un_usuario_puede_enviar_solicitudes_de_amistad(){
        $sender= factory(User::class)->create();
        $recipient= factory(User::class)->create();

        $friendship=$sender->sendFriendRequestTo($recipient);

        $this->assertTrue($friendship->sender->is($sender));
        $this->assertTrue($friendship->recipient->is($recipient));
    }
    /**
     * @test
     */

    public function un_usuario_puede_aceptar_solicitudes_de_amistad(){
        $sender= factory(User::class)->create();
        $recipient= factory(User::class)->create();

        $sender->sendFriendRequestTo($recipient);

        $friendship=$recipient->acceptFriendRequestFrom($sender);

        $this->assertEquals('accepted',$friendship->status);

    }
    /**
     * @test
     */

    public function un_usuario_puede_denegar_solicitudes_de_amistad(){
        $sender= factory(User::class)->create();
        $recipient= factory(User::class)->create();

        $sender->sendFriendRequestTo($recipient);

        $friendship=$recipient->denyFriendRequestFrom($sender);

        $this->assertEquals('denied',$friendship->status);

    }
    /**
     * @test
     */
    public function un_usuario_puede_obtener_todas_las_solicitudes_de_amistad(){
        $sender= factory(User::class)->create();
        $recipient= factory(User::class)->create();

        $sender->sendFriendRequestTo($recipient);

        $this->assertCount(0,$recipient->friendshipRequestSent);
        $this->assertCount(1,$recipient->friendshipRequestReceived);
        $this->assertInstanceOf(Friendship::class,$recipient->friendshipRequestReceived->first());

        $this->assertCount(1,$sender->friendshipRequestSent);
        $this->assertCount(0,$sender->friendshipRequestReceived);
        $this->assertInstanceOf(Friendship::class,$sender->friendshipRequestSent->first());
    }

    /**
     * @test
     */

    public function un_usuario_puede_obtener_a_sus_amigos(){
        $sender= factory(User::class)->create();
        $recipient= factory(User::class)->create();

        $sender->sendFriendRequestTo($recipient);
        $this->assertCount(0,$recipient->friends());
        $this->assertCount(0,$sender->friends());

        $recipient->acceptFriendRequestFrom($sender);
        $this->assertCount(1,$recipient->friends());
        $this->assertCount(1,$sender->friends());

        $this->assertEquals($recipient->name, $sender->friends()->first()->name);
        $this->assertEquals($sender->name, $recipient->friends()->first()->name);
    }
}
