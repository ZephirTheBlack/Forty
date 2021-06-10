<?php

namespace Tests\Feature;


use App\Models\Status;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CanLikeStatusesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public  function usuarios_invitados_no_pueden_dar_like_a_los_estados(){
        $status=factory(Status::class)->create();
        $response= $this->postJson(route('statuses.likes.store',$status));
        $response->assertStatus(401);
    }

    /**
     * @test
     */
    public function un_usuario_autenticado_puede_dar_like_y_quitar_likes_a_los_estados()
    {

        $user=factory(User::class)->create();
        $status=factory(Status::class)->create();

        $this->assertCount(0, $status->likes);

        $response=$this->actingAs($user)->postJson( route('statuses.likes.store',$status));

        $response->assertJsonFragment([
            'likes_count' => 1
        ]);

        $this->assertCount(1, $status->fresh()->likes);

        $this->assertDatabaseHas('likes',['user_id'=> $user->id,]);

        $response=$this->actingAs($user)->deleteJson( route('statuses.likes.destroy',$status));

        $response->assertJsonFragment([
            'likes_count' => 0
        ]);
        $this->assertCount(0, $status->fresh()->likes);
        $this->assertDatabaseMissing('likes',['user_id'=> $user->id,]);

    }

}
