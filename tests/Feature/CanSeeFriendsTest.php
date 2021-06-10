<?php

namespace Tests\Feature;

use App\Models\Friendship;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CanSeeFriendsTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @test
     */
    public function un_invitado_no_puede_acceder_al_listado_de_amigos(){
        $this->get(route('friends.index'))->assertRedirect('login');
    }

    /**
     * @test
     */
    public function un_usuario_puede_ver_un_listado_de_amigos()
    {
        $sender= factory(User::class)->create();
        $recipient= factory(User::class)->create();

        factory(Friendship::class)->create([
            'recipient_id'=>$recipient->id,
            'sender_id'=>$sender->id,
            'status'=>'accepted'
        ]);
        $this->actingAs($sender)->get(route('friends.index'))->assertSee($recipient->name);
        $this->actingAs($recipient)->get(route('friends.index'))->assertSee($sender->name);
    }
}
