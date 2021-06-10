<?php

namespace Tests\Unit\Models;

use App\Models\Friendship;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FriendshipTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function una_solicitud_te_amistad_pertenece_a_alguien_que_envia()
    {
        $sender=factory(User::class)->create();
        $friendship = factory(Friendship::class)->create(['sender_id'=>$sender->id]);

        $this->assertInstanceOf(User::class, $friendship->sender);
    }

    /**
     * @test
     */
    public function una_solicitud_te_amistad_pertenece_a_alguien_que_recibe()
    {
        $recipient=factory(User::class)->create();
        $friendship = factory(Friendship::class)->create(['recipient_id'=>$recipient->id]);

        $this->assertInstanceOf(User::class, $friendship->recipient);
    }

    /**
     * @test
     */
    public function podemos_encontrar_solicitudes_de_amistad_por_el_usuario_que_envia_y_el_que_recibe(){

        $sender=factory(User::class)->create();
        $recipient=factory(User::class)->create();

        $friendship = factory(Friendship::class,2)->create(['recipient_id'=>$recipient->id]);
        $friendship = factory(Friendship::class)->create(['sender_id'=>$sender->id]);

        Friendship::create([
            'recipient_id'=>$recipient->id,
            'sender_id'=>$sender->id
        ]);

        $foundFriendship=Friendship::betweenUsers($sender,$recipient)->first();

        $this->assertEquals($sender->id,$foundFriendship->sender_id);
        $this->assertEquals($recipient->id,$foundFriendship->recipient_id);

        $foundFriendship2=Friendship::betweenUsers($recipient,$sender)->first();

        $this->assertEquals($recipient->id,$foundFriendship2->recipient_id);
        $this->assertEquals($sender->id,$foundFriendship2->sender_id);
    }
}
