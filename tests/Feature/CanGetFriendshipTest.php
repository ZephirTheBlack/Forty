<?php

namespace Tests\Feature;

use App\Models\Friendship;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CanGetFriendshipTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @test
     */
    public function los_invitados_no_pueden_tener_amistades(){
        $this->getJson(route('friendships.show', 'kevin'))->assertStatus(401);
    }

    /**
     * @test
     */
    public function puede_tener_una_amistad()
    {
        $sender=factory(User::class)->create();
        $recipient=factory(User::class)->create();

        $friendship = Friendship::create([
            'sender_id'=> $sender->id,
            'recipient_id'=>$recipient->id
        ]);

        $response=$this->actingAs($sender)->getJson(route('friendships.show',$recipient));

        $response->assertJsonFragment([
            'friendship_status'=>$friendship->fresh()->status
        ]);
    }
}
